```markdown
# ScholaSys – Graduate Data Management System

ScholaSys is a complete web‑based **Graduate Data Management System** built with PHP (vanilla) and MySQL, running on XAMPP. It helps educational institutions track graduates, manage employment data, conduct tracer surveys, organize alumni events, and more. The system includes advanced features like user roles, 2FA, email reminders, PDF reports, document uploads, and a public alumni directory.

---

## 📋 Requirements

- **XAMPP** (Apache, MySQL, PHP 7.4 or higher) – tested with PHP 8.2.12  
- **Composer** (for installing dependencies)  
- Web browser (Chrome, Firefox, Edge)

---

## 🚀 Installation

1. **Clone or download** this repository into your XAMPP `htdocs` folder:  
   ```
   C:\xampp\htdocs\scholasys\
   ```

2. **Start XAMPP** and enable Apache and MySQL.

3. **Create a database** named `scholasys` in phpMyAdmin.

4. **Import the database schema**  
   Open phpMyAdmin, select the `scholasys` database, and import the file `scholasys.sql` (provided in the root folder).  
   *If you have an existing database, run the individual `CREATE TABLE` and `ALTER` statements from the documentation.*

5. **Install PHP dependencies** with Composer:  
   Open a command prompt in the project folder and run:
   ```bash
   composer install
   ```
   This installs PHPMailer, Dompdf, and TwoFactorAuth.

6. **Set file permissions** – ensure the `assets/uploads/` folder is writable (on Windows it usually is).

7. **Update SMTP settings** (if you plan to use email reminders or newsletters):  
   Edit `send_survey_reminders.php`, `send_anniversary_reminders.php`, and `pages/newsletter.php` with your email credentials.

8. **Access the system** in your browser:  
   ```
   http://localhost/scholasys/public/
   ```

9. **Log in** with the default administrator account:
   - Username: `admin`
   - Password: `admin123`

   **Important:** Change the password immediately after first login.

---

## ✨ Features

### Core Management
- **Graduate Records** – add, edit, search, filter, paginate, and export (CSV)
- **Employment Tracking** – current job (employer, title, salary, type) and complete **employment history** (multiple jobs with dates)
- **Tracer Surveys** – public form with unique token links; auto‑record responses
- **Reports** – employment rate by program, top employers, PDF download
- **Dashboard** – statistics, employment trend chart, salary distribution, industry pie chart, recent graduates

### Alumni Engagement
- **Events Management** – create events, capacity, public registration, attendance tracking
- **Announcements** – post news/updates visible on the dashboard
- **Public Alumni Directory** – opt‑in directory with search/filter

### User & Security
- **Multi‑role Users** – Admin and Staff roles; Admin can manage users, Staff can manage graduates but cannot delete
- **Two‑Factor Authentication (2FA)** – optional, uses Google Authenticator
- **Password Change** – users can change their own password
- **Activity Logs** (optional – you can extend)

### Automation
- **Automated Survey Reminders** – cron script sends emails at 6, 12, 24, 36 months after graduation
- **Anniversary Reminders** – congratulatory emails on 1st, 5th, 10th, etc., anniversaries

### Data Tools
- **CSV Import** – bulk import graduates from a CSV file
- **Custom Report Builder** – select fields and filters, generate dynamic tables, export to CSV
- **Document Uploads** – profile picture and CV (PDF) for each graduate
- **Newsletter** – send emails to all alumni or filter by program/year (uses PHPMailer)

---

## 📁 File Structure

```
scholasys/
├── assets/               # CSS, JS, uploads
├── includes/             # config.php, header.php, footer.php
├── pages/                # All protected pages (login required)
├── public/               # Public pages (login, survey, directory, 2FA)
├── vendor/               # Composer dependencies
├── send_*.php            # Cron scripts
├── scholasys.sql         # Database dump
└── README.md
```

---

## 🔧 Cron Jobs (Windows Task Scheduler)

To automate survey and anniversary emails, create two scheduled tasks that run daily:

- **Survey reminders**  
  `php C:\xampp\htdocs\scholasys\send_survey_reminders.php`
- **Anniversary reminders**  
  `php C:\xampp\htdocs\scholasys\send_anniversary_reminders.php`

Update the SMTP credentials inside those files before running.

---

## 🛠️ Troubleshooting

- **Login fails** – ensure the admin password hash is correct. Run `reset_password.php` (see FAQ) to reset.
- **PDF not generated** – verify `dompdf` is installed and `vendor/autoload.php` is included.
- **404 errors** – check that all files are placed in the correct folders (`pages/` vs `public/`).
- **2FA QR code not showing** – the page falls back to showing the secret; you can enter it manually in Google Authenticator.
- **Composer errors** – ensure PHP version meets requirements (8.2+ works for our dependencies). If you encounter platform issues, run `composer install --ignore-platform-reqs`.

---

## 📝 License

This project is open‑source and free to use for educational institutions. No warranty is provided.

---

## 👥 Contributors

Developed as a comprehensive solution for graduate data management. For questions or contributions, please contact the system administrator.

---

**Enjoy using ScholaSys!** 🎓
```
