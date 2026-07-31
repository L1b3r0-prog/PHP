<?php
require_once __DIR__ . '/../config/Database.php';

/**
 * User.php
 * Abstract base class for Administrator and Client.
 * Holds shared fields (id, name, phone, email, type) and shared
 * behaviour: register, login, logout, validation.
 */
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

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPhone(): string { return $this->phone; }
    public function getEmail(): string { return $this->email; }
    public function getType(): string { return $this->type; }

    /**
     * Common consumer webmail domains accepted for CLIENT registration.
     * Admin accounts use a separate, stricter domain check (see validate()).
     */
    private const CLIENT_EMAIL_DOMAINS = [
        'gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com',
        'live.com', 'icloud.com', 'protonmail.com',
    ];

    /** Domain required for staff/administrator accounts. Adjust to your organisation's real domain. */
    private const ADMIN_EMAIL_DOMAIN = 'myrecordingstudio.com';

    /**
     * Validates registration input. Returns array of error strings (empty = valid).
     * $type controls which email domain rule applies: 'client' -> common webmail
     * providers only, 'admin' -> the organisation's own domain only.
     */
    public static function validate(string $name, string $phone, string $email, string $password, string $type = 'client'): array {
        $errors = [];
        if (trim($name) === '') $errors[] = 'Name is required.';
        if (!preg_match('/^[0-9]{1,8}$/', $phone)) $errors[] = 'Phone number must be numeric and at most 8 digits.';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email address is invalid.';
        } else {
            $domain = strtolower(substr(strrchr($email, '@'), 1));
            if ($type === 'admin') {
                if ($domain !== self::ADMIN_EMAIL_DOMAIN) {
                    $errors[] = 'Administrator accounts must use an @' . self::ADMIN_EMAIL_DOMAIN . ' email address.';
                }
            } else {
                if (!in_array($domain, self::CLIENT_EMAIL_DOMAINS, true)) {
                    $errors[] = 'Please use a personal email address (Gmail, Hotmail, Outlook, Yahoo, etc.).';
                }
            }
        }

        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        return $errors;
    }

    /**
     * Registers a new user (admin or client). Returns new user id, or throws on duplicate email.
     */
    public static function register(string $name, string $phone, string $email, string $password, string $type): int {
        $db = Database::getConnection();

        $check = $db->prepare('SELECT user_id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            throw new Exception('An account with this email already exists.');
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare('INSERT INTO users (name, phone, email, password, type) VALUES (?,?,?,?,?)');
        $stmt->execute([$name, $phone, $email, $hash, $type]);
        return (int)$db->lastInsertId();
    }

    /**
     * Attempts login. Returns associative row on success, null on failure.
     */
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
