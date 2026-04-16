# TaxSafar CA Inquiry System - Testing Checklist

## 1. Environment Setup
- [ ] Rename `.env.example` to `.env`.
- [ ] Update `.env` with actual database credentials and set `APP_ENV=local`.
- [ ] Ensure PHP 8.1+ and MySQL are running.
- [ ] Import `database.sql` into the local MySQL database.
- [ ] Ensure the web server (Apache/Nginx/PHP Built-in Server) is serving the `public/` directory.

## 2. Public Facing Features
- [ ] Navigate to the homepage (Landing Page).
- [ ] Verify the Hero section, Services block, and Contact Form are visible and styled correctly.
- [ ] Submit an inquiry with valid data (name, email, phone, service, message).
  - Expectation: Form submits, database records inquiry, redirects back to landing with success message (PRG pattern).
- [ ] Submit an empty form or invalid email.
  - Expectation: Client-side validation prevents submission. Server-side (if tested directly) rejects and returns error context.

## 3. Administrative Setup
- [ ] Navigate to `/public/setup.php` via browser.
- [ ] Fill in details to create the first admin user.
- [ ] Ensure `storage/setup.lock` file is created.
- [ ] Attempt to access `/public/setup.php` again.
  - Expectation: The page shows a "Setup is locked" message.

## 4. Admin Auth Flow
- [ ] Go to `/public/index.php?page=login`.
- [ ] Log in with the newly created admin credentials.
  - Expectation: Redirected to Admin Dashboard.
- [ ] Attempt to access `/public/index.php?page=dashboard` while logged out.
  - Expectation: Redirected to Login page.
- [ ] Click "Logout" from the admin navbar.
  - Expectation: Session destroyed, redirected to Login page.

## 5. Admin Dashboard & CRUD Operations
- [ ] Log in as Admin.
- [ ] View the Dashboard summary numbers (Total Inquiries, New, In Progress).
- [ ] Navigate to "Manage Inquiries" (`?page=inquiries`).
- [ ] Use the Search/Filter functionality (by term or status).
- [ ] Change the status of a specific inquiry using the inline update dropdown (AJAX/form submission).
  - Expectation: Status updates without full page disruption or correctly reflects updated state.
- [ ] Click "Edit" and update the notes/details of an inquiry.
- [ ] Click "Delete" on an inquiry.
  - Expectation: Record is removed from the database after confirmation.

## 6. Security Features
- [ ] Verify CSRF tokens are present in login and inquiry forms.
- [ ] Check `config/app.php` and `config/db.php` to ensure PDO uses prepared statements (SQL Injection prevention).
- [ ] Change `APP_ENV` to `production` in `.env` and trigger a database error manually (e.g. rename a table).
  - Expectation: A custom 500 error page appears, not raw PHP exceptions (Information Disclosure prevention).
- [ ] Introduce malicious input into the public form (e.g., `<script>alert(1)</script>`).
  - Expectation: Admin panel displays escaped text via `htmlspecialchars` (XSS prevention).
