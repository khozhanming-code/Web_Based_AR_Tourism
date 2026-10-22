# AR Tourism Explorer

A generic, mobile-first PHP/MySQL tourism platform with an AR poster workflow. It is intentionally framework-free and suitable for XAMPP, cPanel, and HTTPS hosting.

## Features

- Public destination, attraction, search, map, and YouTube pages.
- Secure PDO database access, password hashing, sessions, CSRF protection, escaping, and validated image uploads.
- Admin dashboard, categories, destinations, attractions, posters, activity logs, and a normalized-coordinate hotspot editor.
- JSON AR endpoints (`api/ar.php`) and a mobile AR experience page using MindAR/Three.js-compatible target metadata.
- Responsive Bootstrap UI, accessible labels, lazy-loaded images, and HTTPS camera messaging.

## XAMPP installation

1. Copy this folder to `C:\xampp\htdocs\Web-Based AR Tourism`.
2. Start Apache and MySQL.
3. Open phpMyAdmin and import [`database/database.sql`](database/database.sql).
4. Edit database constants in [`includes/config.php`](includes/config.php) if your MySQL credentials differ.
5. Create an admin from the project directory:

   `php database/seed_admin.php admin@example.com ChangeMe123!`

6. Browse to `http://localhost/Web-Based%20AR%20Tourism/` and sign in at `/admin/login.php`.

## AR target compilation

Upload a poster in Admin → AR Posters. Use the **official MindAR Target Compiler** link on that page (or [MindAR's compiler page](https://hiukim.github.io/mind-ar-js-doc/tools/compile/)) to convert the poster image into a `.mind` target, then upload that file in the poster form; the system stores it under `assets/ar-targets/` and marks the poster `READY` automatically. Add a hotspot with a direct MP4/WebM URL or a YouTube URL. On the AR page, press **START AR**, point the camera at the poster, and the video starts after the image is recognized. Posters without a `.mind` file remain `NOT_COMPILED` and cannot be selected for AR. If the online page is blocked, use the MindAR compiler package locally from the official GitHub project.

Camera access requires HTTPS on phones. `localhost` is allowed by modern browsers for local testing.

## cPanel deployment

Upload the files to `public_html`, create a MySQL database/user, import the SQL, update `includes/config.php`, ensure `assets/uploads` and `assets/ar-targets` are writable, and enable HTTPS. Never commit real credentials.

## Validation

Run `php -l` on PHP files after changes. Test login, unauthorized admin access, CRUD forms, upload validation, CSRF rejection, `/api/ar.php?poster_id=...`, and camera permissions on a supported mobile browser.
