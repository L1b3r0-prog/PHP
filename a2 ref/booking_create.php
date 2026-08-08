<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('client');
$pageTitle = 'Book a Studio';
$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationId = (int)($_POST['location_id'] ?? 0);
    $date = trim($_POST['booking_date'] ?? '');
    $startTime = trim($_POST['start_time'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);
    $studioId = (int)($_POST['studio_id'] ?? 0);

    if ($locationId <= 0) $errors[] = 'Please select a location.';
    if ($date === '') $errors[] = 'Please choose a booking date.';
    if ($startTime === '') $errors[] = 'Please choose a start time.';

    // No studio_id in the request means the client never clicked "Check
    // Available Studios" -- treat that as "book me any free one" and let
    // Booking::create() auto-assign. If they DID check, the JS keeps the
    // Confirm button disabled until they pick one, so a submitted studio_id
    // of 0 at that point can't happen through normal use of the form.
    if (empty($errors)) {
        try {
            $bookingId = Booking::create($locationId, current_user_id(), $date, $startTime, $duration, $studioId > 0 ? $studioId : null);
            $confirmation = Booking::detailedById($bookingId);
        } catch (Exception $e) {
            // When auto-assign finds nothing free, Booking::create() throws
            // "No studio is available..." -- that exception message IS the
            // warning shown to a client who skipped checking manually.
            $errors[] = $e->getMessage();
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:560px;margin:0 auto;">
    <h1>Book a Studio</h1>

    <?php if ($confirmation): ?>
        <div class="alert alert-success">
            <strong>Booking Confirmed!</strong><br>
            Booking #<?= (int)$confirmation['booking_id'] ?> at <?= h($confirmation['location_description']) ?> (<?= h(Studio::displayName($confirmation['studio_label'], $confirmation['studio_number'])) ?>)<br>
            Date: <?= h($confirmation['booking_date']) ?><br>
            Time: <?= h(substr($confirmation['start_time'],0,5)) ?> - <?= h(substr($confirmation['end_time'],0,5)) ?> (<?= (int)$confirmation['duration_hours'] ?> hour<?= $confirmation['duration_hours'] > 1 ? 's' : '' ?>)<br>
            Total Cost: $<?= h(number_format((float)$confirmation['total_cost'], 2)) ?>
        </div>
        <p><a class="btn" href="booking_list_upcoming.php">View My Bookings</a> <a class="btn btn-secondary" href="booking_create.php">Book Another</a></p>
    <?php else: ?>
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
        <form method="post" id="booking-form" data-validate>
            <label>Location</label>
            <div class="autocomplete" data-role="location-search">
                <input type="text" class="location-search-input" placeholder="Search by name, ID, or studio..." autocomplete="off" data-label="Location" required>
                <input type="hidden" name="location_id" class="location-hidden-id">
                <div class="suggestions"></div>
            </div>

            <label>Booking Date</label>
            <input type="date" name="booking_date" data-label="Booking date" min="<?= date('Y-m-d') ?>" max="<?= Booking::maxBookingDate() ?>" value="<?= h($_POST['booking_date'] ?? '') ?>" required>

            <label>Start Time (10:00 - 22:00)</label>
            <select name="start_time" data-label="Start time" required>
                <option value="">-- Select a start time --</option>
                <?php foreach (Booking::hourlyStartSlots() as $slot): ?>
                    <option value="<?= h($slot) ?>" <?= ($_POST['start_time'] ?? '') === $slot ? 'selected' : '' ?>><?= h($slot) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Duration (hours, 1-12)</label>
            <input type="number" name="duration" data-label="Duration" min="1" max="12" value="<?= h($_POST['duration'] ?? '1') ?>" required>

            <p><small>Want to pick a specific studio? Click "Check Available Studios" below. Skip it and Confirm Booking will assign you the first free studio automatically.</small></p>

            <button class="btn btn-secondary" type="button" id="check-availability-btn">Check Available Studios</button>

            <div class="form-error" id="availability-check-error"></div>

            <div id="studio-picker" style="display:none;margin-top:16px;">
                <label style="margin-top:0;">Choose a Studio</label>
                <div id="studio-options"></div>
                <input type="hidden" name="studio_id" id="studio_id_input">
            </div>

            <div class="form-error" id="submit-guard-error"></div>

            <button class="btn" type="submit" id="confirm-booking-btn">Confirm Booking</button>
        </form>
        <script>
        (function () {
            const checkBtn = document.getElementById('check-availability-btn');
            const picker = document.getElementById('studio-picker');
            const optionsDiv = document.getElementById('studio-options');
            const studioIdInput = document.getElementById('studio_id_input');
            const confirmBtn = document.getElementById('confirm-booking-btn');
            const form = document.getElementById('booking-form');
            const availabilityError = document.getElementById('availability-check-error');
            const submitGuardError = document.getElementById('submit-guard-error');

            function showError(el, message) {
                el.textContent = message;
                el.classList.add('visible');
            }
            function clearError(el) {
                el.textContent = '';
                el.classList.remove('visible');
            }

            // True only once the client has clicked "Check Available Studios"
            // for the CURRENT set of inputs. While true, Confirm stays locked
            // until a specific studio is picked. If false, Confirm submits
            // with no studio_id at all, which the backend treats as
            // "auto-assign me any free studio".
            let hasCheckedCurrentSlot = false;

            function resetToUnchecked() {
                hasCheckedCurrentSlot = false;
                studioIdInput.value = '';
                picker.style.display = 'none';
                optionsDiv.innerHTML = '';
                confirmBtn.disabled = false;
                clearError(availabilityError);
                clearError(submitGuardError);
            }
            form.querySelector('[name="booking_date"]').addEventListener('change', resetToUnchecked);
            form.querySelector('[name="start_time"]').addEventListener('change', resetToUnchecked);
            form.querySelector('[name="duration"]').addEventListener('input', resetToUnchecked);
            form.querySelector('.location-search-input').addEventListener('input', resetToUnchecked);

            checkBtn.addEventListener('click', function () {
                clearError(availabilityError);
                clearError(submitGuardError);

                const locationId = form.querySelector('.location-hidden-id').value;
                const date = form.querySelector('[name="booking_date"]').value;
                const startTime = form.querySelector('[name="start_time"]').value;
                const duration = form.querySelector('[name="duration"]').value;

                if (!locationId || !date || !startTime || !duration) {
                    showError(availabilityError, 'Please select a location, date, start time, and duration first.');
                    return;
                }

                hasCheckedCurrentSlot = true;
                confirmBtn.disabled = true; // locked until a studio is picked below

                checkBtn.textContent = 'Checking...';
                fetch('ajax_studio_availability.php?location_id=' + encodeURIComponent(locationId) +
                      '&date=' + encodeURIComponent(date) +
                      '&start_time=' + encodeURIComponent(startTime) +
                      '&duration=' + encodeURIComponent(duration))
                    .then(function (res) { return res.json(); })
                    .then(function (studios) {
                        checkBtn.textContent = 'Check Available Studios';
                        if (studios.error) {
                            showError(availabilityError, studios.error);
                            hasCheckedCurrentSlot = false;
                            confirmBtn.disabled = false;
                            return;
                        }
                        if (studios.length === 0) {
                            optionsDiv.innerHTML = '<p style="color:var(--error);margin:6px 0;">No studios available for this time slot. Try a different date, time, or duration -- or go back and press Confirm Booking without checking, so we can tell you plainly there\'s nothing free.</p>';
                            picker.style.display = 'block';
                            confirmBtn.disabled = true;
                            return;
                        }
                        optionsDiv.innerHTML = studios.map(function (s) {
                            return '<div class="suggestion-item studio-choice" data-id="' + s.studio_id + '" ' +
                                   'style="border:1px solid var(--border);border-radius:6px;margin-top:8px;">' +
                                   '<strong>' + s.name + '</strong></div>';
                        }).join('');
                        picker.style.display = 'block';
                    })
                    .catch(function () {
                        checkBtn.textContent = 'Check Available Studios';
                        hasCheckedCurrentSlot = false;
                        confirmBtn.disabled = false;
                        showError(availabilityError, 'Could not check availability. Please try again.');
                    });
            });

            optionsDiv.addEventListener('click', function (e) {
                const item = e.target.closest('.studio-choice');
                if (!item) return;
                document.querySelectorAll('.studio-choice').forEach(function (el) {
                    el.style.borderColor = 'var(--border)';
                    el.style.background = '';
                });
                item.style.borderColor = 'var(--amber)';
                item.style.background = '#fdf3e3';
                studioIdInput.value = item.dataset.id;
                confirmBtn.disabled = false;
                clearError(submitGuardError);
            });

            form.addEventListener('submit', function (e) {
                // Only block submission if they checked availability and then
                // never actually picked one from the results.
                if (hasCheckedCurrentSlot && !studioIdInput.value) {
                    e.preventDefault();
                    showError(submitGuardError, 'Please choose a studio from the list, or reload the page and press Confirm Booking without checking to be auto-assigned one.');
                }
            });
        })();
        </script>
    <?php endif; ?>
</div>
<script src="assets/form-validation.js"></script>
<script src="assets/location-autocomplete.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
