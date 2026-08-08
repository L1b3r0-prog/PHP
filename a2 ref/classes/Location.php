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

    /** All locations, newest (highest ID) first */
    public static function all(): array {
        $db = Database::getConnection();
        return $db->query('SELECT * FROM locations ORDER BY location_id ASC')->fetchAll();
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
                ORDER BY l.location_id ASC";
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
                ORDER BY l.location_id ASC";
        return $db->query($sql)->fetchAll();
    }

    /**
     * Partial-match search across LocationID and Description.
     * Description also matches against studio labels within that location
     * (e.g. searching "vocal" finds a location because one of its studios
     * is named "Vocal Booth"), so results can surface via studio name too.
     * Any field left blank is ignored (combination search).
     */
    public static function search(string $locationId = '', string $description = ''): array {
        $db = Database::getConnection();
        $sql = 'SELECT * FROM locations l WHERE 1=1';
        $params = [];

        if ($locationId !== '') {
            $sql .= ' AND l.location_id LIKE ?';
            $params[] = '%' . $locationId . '%';
        }
        if ($description !== '') {
            $sql .= ' AND (l.description LIKE ?
                        OR EXISTS (SELECT 1 FROM studios s WHERE s.location_id = l.location_id AND s.label LIKE ?))';
            $params[] = '%' . $description . '%';
            $params[] = '%' . $description . '%';
        }

        $sql .= ' ORDER BY l.location_id ASC';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Studio display names for a location, e.g. ["Vocal Booth", "Live Room", "Studio 3"] */
    public static function studioNames(int $locationId): array {
        $studios = Studio::forLocation($locationId);
        return array_map(
            fn($s) => Studio::displayName($s['label'], $s['studio_number']),
            $studios
        );
    }

    /**
     * Type-ahead search used by ajax_location_search.php: matches a location's
     * ID, its description, OR the custom label of any studio inside it (e.g.
     * typing "vocal" finds the location that has a studio named "Vocal Booth",
     * typing "2" finds location ID 2).
     * Returns each location plus which studio label matched, if any.
     */
    public static function searchWithStudios(string $term): array {
        $db = Database::getConnection();
        $sql = "SELECT l.*, 
                       (SELECT s.label FROM studios s
                        WHERE s.location_id = l.location_id AND s.label LIKE ?
                        LIMIT 1) AS matched_studio_label
                FROM locations l
                WHERE l.location_id LIKE ?
                   OR l.description LIKE ?
                   OR EXISTS (SELECT 1 FROM studios s WHERE s.location_id = l.location_id AND s.label LIKE ?)
                ORDER BY l.location_id ASC";
        $like = '%' . $term . '%';
        $stmt = $db->prepare($sql);
        $stmt->execute([$like, $like, $like, $like]);
        return $stmt->fetchAll();
    }
}
