<?php
    require_once __DIR__ . "/../config/Database.php";

    class Studio {
        public int $studioId;
        public int $locationId;
        public int $studioNumber;

        public function __construct(int $studioId, int $locationId, int $studioNumber) {
            $this->studioId = $studioId;
            $this->locationId = $locationId;
            $this->studioNumber = $studioNumber;
        }

        public static function forLocation(int $locationId): array {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM studios WHERE location_id = ? ORDER BY studio_number");
            $stmt->execute([$locationId]);
            return $stmt->fetchAll();
        }

        public static function findAvailable(int $locationId, string $date, string $startTime, string $endTime): ?int {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT studio_id FROM studios WHERE location_id = ? ORDER BY studio_number");
            $stmt->execute([$locationId]);
            $studios = $stmt->fetchAll();

            foreach ($studios as $studio) {
                if (!booking::hasOverlap($studio["studio_id"], $date, $startTime, $endTime)) {
                    return (int)$studio["studio_id"];
                }
            }
            return null;
        }

        public static function 
    }