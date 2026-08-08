# MyRecordingStudio

ISIT307 Assignment #2 reference solution. OOP PHP + MySQL.

## Setup (XAMPP/MAMP)

1. Install XAMPP, start Apache + MySQL.
2. Copy this folder into `htdocs/myrecordingstudio`.
3. Open phpMyAdmin, import `database/schema.sql` (or run `mysql -u root -p < database/schema.sql`).
4. `config/Database.php` defaults to host `127.0.0.1`, db `myrecordingstudio`, user `root`, no password — matches default XAMPP. Edit if your setup differs.
5. Visit `http://localhost/myrecordingstudio/`.

## Test logins (from schema.sql sample data)

- Admin: `admin@myrecordingstudio.com` / `admin123`
- Client: `client@gmail.com` / `client123`

## Account creation rules

- **Public `register.php` creates client accounts only.** Client emails must be
  from a common webmail provider (gmail.com, hotmail.com, outlook.com, yahoo.com,
  live.com, icloud.com, protonmail.com) -- edit the list in `User::CLIENT_EMAIL_DOMAINS`
  if you need more.
- **Administrator accounts are never self-registered.** They're created either
  directly in `schema.sql`, or by an existing admin via `admin_create.php`
  (linked only in the nav once logged in as admin, not on the public site).
  Admin emails must match `User::ADMIN_EMAIL_DOMAIN` (default `myrecordingstudio.com`) --
  change that constant to your real organisation domain.

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

## Changelog (this copy)

- Replaced native `alert()` popups in `booking_create.php`'s inline script and
  `assets/location-autocomplete.js` with inline `.form-error` messages styled
  to match the site's existing `.alert-error` look. Client-side and
  server-side validation errors now share one visual language instead of
  mixing browser dialogs with in-page alerts. Destructive-action `confirm()`
  dialogs (cancel booking) were left as native browser confirms, since that's
  a distinct, standard pattern for "are you sure?" prompts.
- Added `.form-error` / `.form-error.visible` rules to `css/style.css`.
- Added `assets/form-validation.js`: intercepts native browser popups
  triggered by `required` / `min` / `max` / `pattern` attributes (e.g.
  picking a booking date in the past, duration outside 1-12, phone number
  not matching the digit pattern, studio count/cost below the allowed
  minimum) and reports them the same way as everything else, in an inline
  `.form-error` box. Opt-in via `data-validate` on a `<form>`; if the script
  fails to load, the form's original HTML attributes still validate
  natively, so nothing goes unchecked. Wired into: `booking_create.php`,
  `booking_edit.php`, `admin_booking_manage.php`, `location_create.php`,
  `location_edit.php`, `register.php`, `admin_create.php`.
- `login.php` left as native `required` only (email/password, no
  min/max/pattern) -- that's ordinary browser behaviour, not the class of
  issue being fixed here.
