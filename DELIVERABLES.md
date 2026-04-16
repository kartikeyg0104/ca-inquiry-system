# TaxSafar CA Inquiry System - Deliverables

## System Architecture Highlights
* **Language & Runtime:** PHP 8.1+, HTML5, CSS3, JavaScript (Vanilla UI interaction).
* **Database:** MySQL 8.0+ using native PHP PDO extension for secure connectivity.
* **Front-end Framework:** Bootstrap 5.3 (via CDN), Font Awesome 6.
* **Architecture Pattern:** Custom Front Controller (`public/index.php`), MVC-inspired lightweight file structure.
* **Security Baseline:** Argon2id password hashing, Session Hijacking/Fixation prevention, CSRF validations, XSS sanitization (`htmlspecialchars`), PRG data-mutation routing, Secure DB credentials via `.env` paradigm.

## Important Directories & Files
* `config/` - Contains system configurations (environment toggle, database drivers).
* `database.sql` - Core schema definition.
* `process/` - Handles form submissions (business logic for CRUD/Auth).
* `public/` - The document root containing entry scripts (`index.php`, `setup.php`) and static assets (`assets/css`, `js`).
* `src/` - PHP Class definitions (Models for `Admin` and `Inquiry`).
* `views/` - Segregated UI layouts (Admin templates, Landing page, 404/500 error views, shared partials).
* `storage/` - Holds temporal application files (e.g., `setup.lock` to secure initialization).

## Final Deployment Steps
1. Push codebase to production Git repository.
2. Clone repository onto production web server.
3. Point Web Server Document Root to the `<project-dir>/public` folder.
4. Copy `.env.example` to `.env` and populate production MySQL configurations. Sets `APP_ENV=production`.
5. Run migrations (`database.sql`).
6. Access `<domain.com>/setup.php` to provision root admin.
7. Confirm `storage/setup.lock` generation to prevent unauthorized reconfiguration.
