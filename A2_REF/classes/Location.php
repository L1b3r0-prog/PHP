<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Studio.php';

/**
 * Location.php
 * CRUD for studio locations, plus the search and list variations
 * required by both Client and Administrator.
 */
class Location {

    public static function validate(string $description, $numStudios, $costPerHour): array {
        $errors = [];
        if (trim($description) === '') $errors[] = 'Description is required.';
        if (!is_numeric($numStudios) || (int)$numStudios < 1) $errors[] = 'Number of studios must be at least 1.';
        if (!is_numeric($costPerHour) || (float)$costPerHour < 0) $errors[] = 'Cost per hour must be a positive number.';
        return $errors;
    }

    public static function create(string $description, int $numStudios, float $costPerHour): int {
        $errors = self::validate($description, $numStudios, $costPerHour);
        if (!empty($errors)) throw new Exception(implode(' ', $errors));

        $db = Database::getConnection();
        $stmt = $db->prepare('INSERT INTO locations (description, num_studios, cost_per_hour) VALUES (?,?,?)');
        $stmt->execute([$description, $numStudios, $costPerHour]);
        $locationId = (int)$db->lastInsertId();

        Studio::createForLocation($locationId, $numStudios);
        return $locationId;
    }

    public static function update(int $locationId, string $description, int $numStudios, float $costPerHour): void {
        $errors = self::validate($description, $numStudios, $costPerHour);
        if (!empty($errors)) throw new Exception(implode(' ', $errors));

        $db = Database::getConnection();
        $stmt = $db->prepare('UPDATE locations SET description=?, num_studios=?, cost_per_hour=? WHERE location_id=?');
        $stmt->execute([$description, $numStudios, $costPerHour, $locationId]);

        Studio::syncForLocation($locationId, $numStudios);
    }

    public static function findById(int $locationId): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM locations WHERE location_id = ?');
        $stmt->execute([$locationId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** All locations */
    public static function all(): array {
        $db = Database::getConnection();
        return $db->query('SELECT * FROM locations ORDER BY description')->fetchAll();
    }

    /**
     * Locations that have at least one studio with no active booking
     * covering the current moment (i.e. free right now).
     */
    public static function withAvailableStudios(): array {
        $db = Database::getConnection();
        $sql = "SELECT l.* FROM locations l
                WHERE EXISTS (
                    SELECT 1 FROM studios s WHERE s.location_id = l.location_id
                    AND NOT EXISTS (
                        SELECT 1 FROM bookings b
                        WHERE b.studio_id = s.studio_id AND b.status = 'active'
                          AND TIMESTAMP(b.booking_date, b.start_time) <= NOW()
                          AND TIMESTAMP(b.booking_date, b.end_time)   > NOW()
                    )
                )
                ORDER BY l.description";
        return $db->query($sql)->fetchAll();
    }

    /** Locations where every studio is currently booked (fully booked right now) */
    public static function fullyBooked(): array {
        $db = Database::getConnection();
        $sql = "SELECT l.* FROM locations l
                WHERE NOT EXISTS (
                    SELECT 1 FROM studios s WHERE s.location_id = l.location_id
                    AND NOT EXISTS (
                        SELECT 1 FROM bookings b
                        WHERE b.studio_id = s.studio_id AND b.status = 'active'
                          AND TIMESTAMP(b.booking_date, b.start_time) <= NOW()
                          AND TIMESTAMP(b.booking_date, b.end_time)   > NOW()
                    )
                )
                ORDER BY l.description";
        return $db->query($sql)->fetchAll();
    }

    /**
     * Partial-match search across LocationID and Description.
     * Any field left blank is ignored (combination search).
     */
    public static function search(string $locationId = '', string $description = ''): array {
        $db = Database::getConnection();
        $sql = 'SELECT * FROM locations WHERE 1=1';
        $params = [];

        if ($locationId !== '') {
            $sql .= ' AND location_id LIKE ?';
            $params[] = '%' . $locationId . '%';
        }
        if ($description !== '') {
            $sql .= ' AND description LIKE ?';
            $params[] = '%' . $description . '%';
        }

        $sql .= ' ORDER BY description';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
