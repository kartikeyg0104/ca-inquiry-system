# TaxSafar — CA Firm Inquiry Management System

## 🚀 Project Overview
TaxSafar is a comprehensive, secure, and modern inquiry management system specifically designed for Chartered Accountant (CA) firms. It features a public-facing landing page for clients to submit service inquiries, and a fully authenticated backend administrative portal to track, filter, manage, and update inbound leads.

| Technology | Version / Name | Purpose |
|------------|----------------|---------|
| **Backend** | PHP 8.1+ | Core Server Logic & Templating |
| **Database**| MySQL 8+ | Relational Data Storage |
| **Driver**  | PHP PDO | Secure Database Interaction |
| **Frontend**| Bootstrap 5.3 | Responsive UI & Grid Layouts |
| **Icons**   | Font Awesome 6 | Vector Icons & UI Elements |

## 📁 Folder Structure
```text
ca_inquiry_system/
├── config/                 # Environment & DB configurations
│   ├── app.php             # Core environment constants
│   └── db.php              # Secure PDO connection driver
├── process/                # Pure PRG action controllers (No HTML)
│   ├── admin_login.php     # Rate-limited auth validator
│   ├── admin_logout.php    # Session & Cookie destroyer
│   ├── delete_inquiry.php  # POST-only deletion handler
│   ├── submit_inquiry.php  # Client-side inquiry ingest
│   ├── update_inquiry.php  # Lead data modifier
│   └── update_status.php   # AJAX JSON status modifier
├── public/                 # Public document root
│   ├── index.php           # Primary application router
│   ├── setup.php           # One-time automated Admin seeder
│   └── assets/             # Static CSS, JS, Images
├── scripts/                # CLI Utilities
│   ├── generate_hash.php   # Internal Argon2id tool
│   └── seed.php            # Raw DB default seeder
├── src/                    # Core Model & Helper Classes
│   ├── controllers/        # Action classes
│   ├── helpers/            # Utility functions (auth.php)
│   └── models/             # Database mappings (Admin.php, Inquiry.php)
├── storage/                # Application locks and logs
├── views/                  # Primary HTML UI components
│   ├── admin/              # Secured backend interfaces
│   ├── errors/             # Environment-aware error states
│   ├── landing/            # Public-facing screens
│   └── partials/           # Reusable headers/footers
├── database.sql            # Core database schema and structure
├── .env.example            # Environment credential template
├── TESTING_CHECKLIST.md    # Sanity checks and tests
└── DELIVERABLES.md         # Packaging instructions
```

## ⚙️ Installation & Setup (Step-by-step for XAMPP)

1. Clone or extract the project exactly to: `C:/xampp/htdocs/ca_inquiry_system/`
2. Open the **XAMPP Control Panel** and start both **Apache** and **MySQL**.
3. Open `http://localhost/phpmyadmin` in your browser.
4. Create a new database named `taxsafar_db` (using the `utf8mb4_unicode_ci` collation).
5. Highlight the new database, navigate to the **Import** tab, and upload the `database.sql` file from the root directory.
6. Open `config/db.php` and verify the credentials match your environment (by default XAMPP uses `root` with no password).
7. Access the application via: `http://localhost/ca_inquiry_system/public/`

### Alternative First-Time Setup
If you skipped importing `database.sql`, run the setup wizard by visiting:
`http://localhost/ca_inquiry_system/public/setup.php` (Warning: Ensure you immediately delete this file after execution).

## 🔐 Admin Credentials
- **Access URL:** `http://localhost/ca_inquiry_system/public/?page=login`
- **Email:** `admin@taxsafar.com`
- **Password:** `Admin@1234`

## 🎨 Color Theme
| Element | Hex Code | Purpose |
|---------|----------|---------|
| **Primary** | `#00B140` | Main Brand Green (Buttons, Headers) |
| **Dark Green** | `#007A2E` | Hover States / Interactions |  
| **Mint** | `#F0FBF4` | Background / Hero sections |
| **Dark Base** | `#1A1A2E` | Typography & Body text |

## 🛡️ Security Features List
- **PDO Prepared Statements:** Absolute protection against SQL injection. No string concatenations.
- **Argon2id Password Hashing:** Uses PHP's strongest native memory-hard hashing algorithm (`memory_cost: 65536`).
- **Session Fixation Prevention:** Explicit `$session_regenerate_id(true)` rotation after authentication.
- **CSRF Token Protection:** Secure 32-byte tokens generated via `random_bytes()` verified across all POST requests.
- **XSS Prevention:** Comprehensive utilization of `$htmlspecialchars(..., ENT_QUOTES)` on all external outputs.
- **Rate Limiting:** IP-agnostic session-based strict lockouts (Max 5 attempts / 15 minutes) defending against brute attacks.
- **Timing Attack Defenses:** Intentional `sleep(1)` triggers matching negative credential inputs linearly.
- **POST-Only Mutations:** `GET` parameters never trigger state alterations or deletions.

## 📊 Features Implemented
- [x] Complete public landing page mapping 6 services.
- [x] Robust responsive client inquiry form with native HTML5 Indian Regex rules.
- [x] Secured login interface with password-toggle visibility.
- [x] Admin dashboard presenting real-time aggregations (SUM CASE operations).
- [x] Paginated inquiry search and real-time status filtering configurations.
- [x] Safe editable detailed forms pulling live DB configurations.
- [x] Inline AJAX-based live status updating (New, Contacted, Closed) with toast notifications.
- [x] Safe phantom-aware deletion parameters passing confirmations natively.
- [x] Unified PRG flow architecture.

## 🐛 Troubleshooting

| Problem | Potential Fix |
|---------|---------------|
| **Database Connection Errors** | Verify your XAMPP MySQL server is running. Open `config/db.php` and or `.env` and ensure `DB_PASS` equals `''` (blank) unless you custom-configured XAMPP passwords. |
| **CSS/Styles Failing to Load** | You may be incorrectly typing the URL. Always access the application relative to `/public/`. |
| **CSRF Token Mismatches** | Clear your browser cookies or execute a hard-refresh. If sessions expire natively `public/index.php` will recreate your tokens safely. |
| **Can't Login / Bad Credentials** | If you messed up the hash, use CLI `php scripts/generate_hash.php YourPassword` and update the DB manually, or trigger `setup.php`. |
# ca-inquiry-system
