<?php
    require_once __DIR__ . "/../config/Database.php";

    abstract class User {
        protected ?int $id;
        protected string $name;
        protected string $phone;
        protected string $email;
        protected string $type;

        public function __construct(?int $id, string $name, string $phone, string $email, string $type) {
            $this->id = $id;
            $this->name = $name;
            $this->phone = $phone;
            $this->email = $email;
            $this->type = $type;
        }

        public function getId(): ?int {return $this->id;}
        public function getName(): string {return $this->name;}
        public function getPhone(): string {return $this->phone;}
        public function getEmail(): string {return $this->email;}
        public function getType(): string {return $this->type;}

        public static function validate(string $name, string $phone, string $email, string $password): array {
            $errors = [];
            if (trim($name) === "") $errors[] = "Name is required.";
            if (!preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) $errors[] = "Phone number is invalid.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email address is invalid.";
            if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
            return $errors;
        }

        # Registers a new user
        public static function register(string $name, string $phone, string $email, string $password, string $type): int {
            $db = Database::getConnection();

            $check = $db->prepare("SELECT user_id FROM user WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetch()) {
                throw new Exception("An account with this email already exists.");
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("INSERT INTO users (name, phone, email, password, type) VALUES (?,?,?,?,?)");
            $stmt->execute([$name, $phone, $email, $hash, $type]);
            return (int)$db->lastInsertId();
        }

        # This attempts login and nulls on failure
        public static function attemptLogin(string $email, string $password): ?array {
            $db = Database::getConnection();
            $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $row = $stmt->fetch();

            if ($row && password_verify($password, $row['password'])) {
                return $row;
            }
            return null;
        }

        public static function logout(): void {
            session_unset();
            session_destroy();
        }

        /**
         * Loads a user row and returns a hydrated Client or Administrator instance.
         */
        public static function findById(int $id): ?User {
            $db = Database::getConnection();
            $stmt = $db->prepare('SELECT * FROM users WHERE user_id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if (!$row) return null;

            return $row['type'] === 'admin'
                ? new Administrator($row['user_id'], $row['name'], $row['phone'], $row['email'])
                : new Client($row['user_id'], $row['name'], $row['phone'], $row['email']);
        }

        /** All clients (Administrator use) */
        public static function allClients(): array {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM users WHERE type = 'client' ORDER BY name");
            return $stmt->fetchAll();
        }

        /** Clients currently using a studio right now (Administrator use) */
        public static function activeClients(): array {
            $db = Database::getConnection();
            $sql = "SELECT DISTINCT u.* FROM users u
                    JOIN bookings b ON b.client_id = u.user_id
                    WHERE b.status = 'active'
                    AND TIMESTAMP(b.booking_date, b.start_time) <= NOW()
                    AND TIMESTAMP(b.booking_date, b.end_time)   > NOW()
                    ORDER BY u.name";
            return $db->query($sql)->fetchAll();
        }
    }