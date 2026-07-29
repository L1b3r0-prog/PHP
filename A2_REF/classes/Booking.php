<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Studio.php';

/**
 * Booking.php
 * Core booking logic: validation (operating hours, duration, overlap),
 * cost calculation, create/modify/cancel, and the various list views
 * required for Client and Administrator.
 */
class Booking {
    public const OPEN_TIME  = '10:00:00';
    public const CLOSE_TIME = '22:00:00';
    public const MIN_HOURS  = 1;
    public const MAX_HOURS  = 12;

    /**
     * Validates booking input against the business rules.
     * Returns array of error strings (empty = valid).
     */
    public static function validate(string $date, string $startTime, int $duration): array {
        $errors = [];

        $today = date('Y-m-d');
        if ($date < $today) {
            $errors[] = 'Booking date cannot be in the past.';
        }

        if ($duration < self::MIN_HOURS || $duration > self::MAX_HOURS) {
            $errors[] = 'Duration must be between ' . self::MIN_HOURS . ' and ' . self::MAX_HOURS . ' hours.';
        }

        // normalise start time to H:i:s for comparison
        $start = date('H:i:s', strtotime($startTime));
        if ($start < self::OPEN_TIME) {
            $errors[] = 'Start time cannot be before 10:00 AM.';
        }

        $end = date('H:i:s', strtotime($start . ' +' . $duration . ' hours'));
        // if adding hours rolls past midnight, strtotime wraps -- catch that explicitly
        if ($end <= $start || $end > self::CLOSE_TIME) {
            $errors[] = 'Session must end by 10:00 PM.';
        }

        // Prevent booking a date/time already in the past (today only)
        if ($date === $today && $start < date('H:i:s')) {
            $errors[] = 'Start time cannot be in the past.';
        }

        return $errors;
    }

    public static function calculateEndTime(string $startTime, int $duration): string {
        $start = date('H:i:s', strtotime($startTime));
        return date('H:i:s', strtotime($start . ' +' . $duration . ' hours'));
    }

    public static function calculateCost(int $duration, float $costPerHour): float {
        return round($duration * $costPerHour, 2);
    }

    /**
     * Checks whether a studio already has an active booking overlapping
     * the given date/time range. Excludes $excludeBookingId when modifying.
     */
    public static function hasOverlap(int $studioId, string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) c FROM bookings
                WHERE studio_id = ? AND booking_date = ? AND status = 'active'
                  AND start_time < ? AND end_time > ?";
        $params = [$studioId, $date, $endTime, $startTime];

        if ($excludeBookingId !== null) {
            $sql .= ' AND booking_id != ?';
            $params[] = $excludeBookingId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetch()['c'] > 0;
    }

    /**
     * Creates a booking at a specific location for a client.
     * Automatically assigns the first free studio at that location.
     * Returns booking_id on success, throws Exception with a user-facing
     * message on validation/availability failure.
     */
    public static function create(int $locationId, int $clientId, string $date, string $startTime, int $duration): int {
        $errors = self::validate($date, $startTime, $duration);
        if (!empty($errors)) {
            throw new Exception(implode(' ', $errors));
        }

        $db = Database::getConnection();
        $locStmt = $db->prepare('SELECT * FROM locations WHERE location_id = ?');
        $locStmt->execute([$locationId]);
        $location = $locStmt->fetch();
        if (!$location) {
            throw new Exception('Selected location does not exist.');
        }

        $start = date('H:i:s', strtotime($startTime));
        $end = self::calculateEndTime($start, $duration);

        $studioId = Studio::findAvailable($locationId, $date, $start, $end);
        if ($studioId === null) {
            throw new Exception('No studio is available at this location for the selected time slot.');
        }

        $cost = self::calculateCost($duration, (float)$location['cost_per_hour']);

        $stmt = $db->prepare('INSERT INTO bookings
            (studio_id, client_id, booking_date, start_time, duration_hours, end_time, total_cost, status)
            VALUES (?,?,?,?,?,?,?,\'active\')');
        $stmt->execute([$studioId, $clientId, $date, $start, $duration, $end, $cost]);

        return (int)$db->lastInsertId();
    }

    /** Same as create() but Administrator books on behalf of a chosen client */
    public static function createByAdmin(int $locationId, int $clientId, string $date, string $startTime, int $duration): int {
        return self::create($locationId, $clientId, $date, $startTime, $duration);
    }

    /**
     * Modifies an existing booking's date/time/duration.
     * Blocked if the session has already started.
     */
    public static function modify(int $bookingId, string $date, string $startTime, int $duration): void {
        $booking = self::findById($bookingId);
        if (!$booking) throw new Exception('Booking not found.');

        if (self::hasStarted($booking)) {
            throw new Exception('This session has already started and can no longer be modified.');
        }

        $errors = self::validate($date, $startTime, $duration);
        if (!empty($errors)) {
            throw new Exception(implode(' ', $errors));
        }

        $start = date('H:i:s', strtotime($startTime));
        $end = self::calculateEndTime($start, $duration);

        if (self::hasOverlap((int)$booking['studio_id'], $date, $start, $end, $bookingId)) {
            throw new Exception('The studio is already booked for that time slot.');
        }

        $db = Database::getConnection();
        $locStmt = $db->prepare('SELECT l.cost_per_hour FROM locations l
                                  JOIN studios s ON s.location_id = l.location_id
                                  WHERE s.studio_id = ?');
        $locStmt->execute([$booking['studio_id']]);
        $costPerHour = (float)$locStmt->fetch()['cost_per_hour'];
        $cost = self::calculateCost($duration, $costPerHour);

        $stmt = $db->prepare('UPDATE bookings SET booking_date=?, start_time=?, duration_hours=?, end_time=?, total_cost=? WHERE booking_id=?');
        $stmt->execute([$date, $start, $duration, $end, $cost, $bookingId]);
    }

    /** Cancels a booking, blocked if the session has already started */
    public static function cancel(int $bookingId): void {
        $booking = self::findById($bookingId);
        if (!$booking) throw new Exception('Booking not found.');

        if (self::hasStarted($booking)) {
            throw new Exception('This session has already started and can no longer be cancelled.');
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE bookings SET status='cancelled' WHERE booking_id=?");
        $stmt->execute([$bookingId]);
    }

    public static function hasStarted(array $booking): bool {
        $startDateTime = $booking['booking_date'] . ' ' . $booking['start_time'];
        return strtotime($startDateTime) <= time();
    }

    public static function findById(int $bookingId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM bookings WHERE booking_id = ?');
        $stmt->execute([$bookingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Full booking details joined with studio/location/client, for confirmations & lists */
    public static function detailedById(int $bookingId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare(self::detailSelect() . ' WHERE b.booking_id = ?');
        $stmt->execute([$bookingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function detailSelect(): string {
        return "SELECT b.*, s.studio_number, s.label AS studio_label, l.description AS location_description,
                        l.location_id, l.cost_per_hour, u.name AS client_name, u.email AS client_email
                 FROM bookings b
                 JOIN studios s ON s.studio_id = b.studio_id
                 JOIN locations l ON l.location_id = s.location_id
                 JOIN users u ON u.user_id = b.client_id";
    }

    /** Client: previously completed sessions (end time already passed) */
    public static function completedForClient(int $clientId): array {
        $db = Database::getConnection();
        $sql = self::detailSelect() . " WHERE b.client_id = ? AND b.status = 'active'
                AND TIMESTAMP(b.booking_date, b.end_time) < NOW()
                ORDER BY b.booking_date DESC, b.start_time DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    /** Client: current + future sessions */
    public static function upcomingForClient(int $clientId): array {
        $db = Database::getConnection();
        $sql = self::detailSelect() . " WHERE b.client_id = ? AND b.status = 'active'
                AND TIMESTAMP(b.booking_date, b.end_time) >= NOW()
                ORDER BY b.booking_date ASC, b.start_time ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    /** Administrator: every booking, most recent first */
    public static function allBookings(): array {
        $db = Database::getConnection();
        $sql = self::detailSelect() . ' ORDER BY b.booking_date DESC, b.start_time DESC';
        return $db->query($sql)->fetchAll();
    }
}
