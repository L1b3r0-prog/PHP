<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Studio.php';

class Booking {
    public const OPEN_TIME  = '10:00:00';
    public const CLOSE_TIME = '22:00:00';
    public const MIN_HOURS  = 1;
    public const MAX_HOURS  = 12;
    public const MAX_ADVANCE_MONTHS = 6;

    /** Latest date a booking may be made for */
    public static function maxBookingDate(): string {
        return date('Y-m-d', strtotime('+' . self::MAX_ADVANCE_MONTHS . ' months'));
    }

    
    /** Valid hourly time slots that stops at 21:00 as the shortest session is 1 hour and must end by 22:00 */
    public static function hourlyStartSlots(): array {
        $slots = [];
        $openHour = (int)substr(self::OPEN_TIME, 0, 2);
        $closeHour = (int)substr(self::CLOSE_TIME, 0, 2);
        for ($h = $openHour; $h < $closeHour; $h++) {
            $slots[] = sprintf('%02d:00', $h);
        }
        return $slots;
    }

    /** Validates the booking inputs */
    public static function validate(string $date, string $startTime, int $duration): array {
        $errors = [];

        $today = date('Y-m-d');
        if ($date < $today) {
            $errors[] = 'Booking date cannot be in the past.';
        }

        if ($date > self::maxBookingDate()) {
            $errors[] = 'Bookings can only be made up to ' . self::MAX_ADVANCE_MONTHS . ' months in advance.';
        }

        if ($duration < self::MIN_HOURS || $duration > self::MAX_HOURS) {
            $errors[] = 'Duration must be between ' . self::MIN_HOURS . ' and ' . self::MAX_HOURS . ' hours.';
        }

        // Normalises the start time to H:i:s for comparison
        $start = date('H:i:s', strtotime($startTime));
        if ($start < self::OPEN_TIME) {
            $errors[] = 'Time Slot cannot be before 10:00 AM.';
        }

        $end = date('H:i:s', strtotime($start . ' +' . $duration . ' hours'));
        // If the timeslot ends past the cut off time, the error will be prompt
        if ($end <= $start || $end > self::CLOSE_TIME) {
            $errors[] = 'Session must end by 10:00 PM.';
        }

        // Prevents booking in a time slot in the past
        if ($date === $today && $start < date('H:i:s')) {
            $errors[] = 'Time Slot cannot be in the past.';
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

    /** Checks if the studios has an overlapping active booking */
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

    /** Creates a booking at a specific location for a client which also automatically assigns a free studio */
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

    /** Same as create() but the Administrator books on behalf of a chosen client */
    public static function createByAdmin(int $locationId, int $clientId, string $date, string $startTime, int $duration): int {
        return self::create($locationId, $clientId, $date, $startTime, $duration);
    }

    /** Modifies an existing booking's date/time/duration and blocks if the session has already started */
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

    /** Cancels a booking and the feature is blocked if the session has already started */
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

    /** Time-based status for display, independent of the stored active/cancelled column: 'pending' (hasn't started yet), 
     * 'active' (session is currently in progress), or 'completed' (end time has passed).
     */
    public static function timeStatus(array $booking): string {
        $now = time();
        $start = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
        $end = strtotime($booking['booking_date'] . ' ' . $booking['end_time']);

        if ($now < $start) return 'pending';
        if ($now < $end) return 'active';
        return 'completed';
    }

    public static function findById(int $bookingId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM bookings WHERE booking_id = ?');
        $stmt->execute([$bookingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Full booking details joined with studio/location/client for confirmations & lists */
    public static function detailedById(int $bookingId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare(self::detailSelect() . ' WHERE b.booking_id = ?');
        $stmt->execute([$bookingId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private static function detailSelect(): string {
        return "SELECT b.*, s.studio_number, l.description AS location_description,
                        l.location_id, l.cost_per_hour, u.name AS client_name, u.email AS client_email
                 FROM bookings b
                 JOIN studios s ON s.studio_id = b.studio_id
                 JOIN locations l ON l.location_id = s.location_id
                 JOIN users u ON u.user_id = b.client_id";
    }

    /** Client's previously completed sessions (end time already passed) */
    public static function completedForClient(int $clientId): array {
        $db = Database::getConnection();
        $sql = self::detailSelect() . " WHERE b.client_id = ? AND b.status = 'active'
                AND TIMESTAMP(b.booking_date, b.end_time) < NOW()
                ORDER BY b.booking_date DESC, b.start_time DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    /** Client's current + future sessions */
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
