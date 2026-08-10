<?php
require_once __DIR__ . '/../config/Database.php';

/**
 * Studio.php
 * Represents a single bookable studio room inside a Location.
 * A Location with num_studios = 3 has 3 rows here.
 */
class Studio {
    public int $studioId;
    public int $locationId;
    public int $studioNumber;

    public function __construct(int $studioId, int $locationId, int $studioNumber) {
        $this->studioId = $studioId;
        $this->locationId = $locationId;
        $this->studioNumber = $studioNumber;
    }

    /** All studios belonging to a location */
    public static function forLocation(int $locationId): array {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM studios WHERE location_id = ? ORDER BY studio_number');
        $stmt->execute([$locationId]);
        return $stmt->fetchAll();
    }

    /** Display label for a studio, e.g. "Studio 3" */
    public static function displayName(int $studioNumber): string {
        return 'Studio ' . $studioNumber;
    }

    /**
     * Finds the first studio at a location that is free for the requested
     * date/time range. Returns studio_id, or null if none available.
     * Bookings always auto-assign the first free studio at the chosen
     * location -- the client does not pick a specific studio.
     */
    public static function findAvailable(int $locationId, string $date, string $startTime, string $endTime): ?int {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT studio_id FROM studios WHERE location_id = ? ORDER BY studio_number');
        $stmt->execute([$locationId]);
        $studios = $stmt->fetchAll();

        foreach ($studios as $studio) {
            if (!Booking::hasOverlap($studio['studio_id'], $date, $startTime, $endTime)) {
                return (int)$studio['studio_id'];
            }
        }
        return null;
    }

    /** Creates the N studio rows for a newly created location */
    public static function createForLocation(int $locationId, int $numStudios): void {
        $db = Database::getConnection();
        $stmt = $db->prepare('INSERT INTO studios (location_id, studio_number) VALUES (?,?)');
        for ($i = 1; $i <= $numStudios; $i++) {
            $stmt->execute([$locationId, $i]);
        }
    }

    /**
     * Adjusts studio rows when a location's num_studios changes on edit.
     * Adds rows if increased; removes highest-numbered empty rows if decreased.
     */
    public static function syncForLocation(int $locationId, int $newCount): void {
        $db = Database::getConnection();
        $current = self::forLocation($locationId);
        $currentCount = count($current);

        if ($newCount > $currentCount) {
            $stmt = $db->prepare('INSERT INTO studios (location_id, studio_number) VALUES (?,?)');
            for ($i = $currentCount + 1; $i <= $newCount; $i++) {
                $stmt->execute([$locationId, $i]);
            }
        } elseif ($newCount < $currentCount) {
            // remove studios above the new count, only if they have no bookings
            $toRemove = array_slice($current, $newCount);
            foreach ($toRemove as $studio) {
                $check = $db->prepare('SELECT COUNT(*) c FROM bookings WHERE studio_id = ?');
                $check->execute([$studio['studio_id']]);
                if ((int)$check->fetch()['c'] === 0) {
                    $del = $db->prepare('DELETE FROM studios WHERE studio_id = ?');
                    $del->execute([$studio['studio_id']]);
                }
            }
        }
    }
}
