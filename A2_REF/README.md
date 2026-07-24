# MyRecordingStudio

ISIT307 Assignment #2 reference solution. OOP PHP + MySQL.

## Setup (XAMPP/MAMP)

1. Install XAMPP, start Apache + MySQL.
2. Copy this folder into `htdocs/myrecordingstudio`.
3. Open phpMyAdmin, import `database/schema.sql` (or run `mysql -u root -p < database/schema.sql`).
4. `config/Database.php` defaults to host `127.0.0.1`, db `myrecordingstudio`, user `root`, no password — matches default XAMPP. Edit if your setup differs.
5. Visit `http://localhost/myrecordingstudio/`.

## Test logins (from schema.sql sample data)

- Admin: `admin@studio.com` / `admin123`
- Client: `client@studio.com` / `client123`

## File overview

- `config/Database.php` — PDO singleton connection.
- `classes/User.php` — abstract base: register, login, logout, validation, client/active-client lists.
- `classes/Client.php`, `classes/Administrator.php` — role subclasses.
- `classes/Location.php` — location CRUD, search, all/available/fully-booked lists.
- `classes/Studio.php` — individual bookable rooms per location; availability lookup.
- `classes/Booking.php` — core rules: 10am–10pm bounds, 1–12hr duration, overlap check, cost calc, create/modify/cancel, completed/upcoming lists.
- `includes/bootstrap.php` — session start, class autoload, auth helper functions.
- `includes/header.php` / `footer.php` — shared layout + nav.
- Root `.php` files — one page per feature (see Design Requirements in the brief).

## Business rules implemented

- Session must start ≥ 10:00 and end ≤ 22:00.
- Duration 1–12 hours.
- No double-booking: each location auto-assigns the first free studio for the slot; rejects if none free.
- Modify/cancel blocked once `NOW() >= booking start`.
- Search is partial-match (`LIKE`) and combinable across LocationID + Description.
- Passwords hashed with bcrypt (`password_hash`/`password_verify`).

## Known simplifications (mention in your report)

- "Currently available/fully booked" and "clients currently active" are evaluated against the live server clock (`NOW()`), per the assignment wording ("currently using a studio").
- No payment functionality, per the brief.
- This is a reference/teaching build — read through it, understand every method, and be ready to explain and modify it live, since the brief requires answering the tutor's questions.
