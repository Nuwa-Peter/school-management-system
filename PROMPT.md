# St. Joseph's Vocational SS Nyamityobora - School Management System

## Project Vision

Develop a robust and user-friendly web-based School Management System for St. Joseph's Vocational SS Nyamityobora. The system must be highly responsive, supporting desktop, tablet, and mobile views, and feature a modern, intuitive user interface with theme-changing capabilities.

---

## Development Log & Instructions

### Local Development Setup

1.  **Prerequisites:** Ensure you have XAMPP (for MySQL) and Node.js installed.
2.  **Database:** Start the MySQL service in XAMPP and create a database named `st_josephs_db`.
3.  **Environment:** Copy `.env.example` to `.env` and ensure the `DB_` variables are correctly set for your local MySQL.
4.  **Dependencies:** Run `composer install` and `npm install`.
5.  **Application Key:** Run `php artisan key:generate`.
6.  **Clear Caches:** Run `php artisan config:clear` and `php artisan cache:clear` to ensure your `.env` settings are loaded.
7.  **Database Setup:** Run `php artisan migrate:fresh --seed` to create all tables and the initial admin user.
8.  **Run the Servers:**
    *   In one terminal, run `php artisan serve`.
    *   In a **second** terminal, run `npm run dev`.
9.  **Access:** Open your browser to `http://127.0.0.1:8000`.

---

## Project Status & Completed Features

### Phase 1: Core System Setup and User Management (Complete)
- [x] Project Initialization and Database Setup
- [x] UI and Authentication Scaffolding (Laravel Breeze)
- [x] Enhanced User Model with Custom Fields and Roles
- [x] Automatic Unique ID Generation for Students/Teachers
- [x] Initial `Root` User Account Seeder
- [x] Core UI Layout (Sidebar, Top Nav, Footer)
- [x] Role-Based Access Control (RBAC) Middleware
- [x] Basic User Management Interface

### Phase 2: Academic Core (Complete)
- [x] `other_name` field added to User model.
- [x] Class & Stream Management (Database, Models, UI)
- [x] Subject Management (Database, Models, UI)
- [x] Paper Management (Database, Models, UI)
- [x] Teacher Assignment System (Backend & UI)
- [x] Student Assignment System (Backend & UI)
- [x] Mark Entry System for Teachers

### Phase 3: Student Lifecycle & Documents (Complete)
- [x] `lin` (Learner Identification Number) field added.
- [x] PDF and Excel Libraries installed.
- [x] Student Data Management UI with search & filters.
- [x] Advanced Photo Upload (File, Preview, Webcam).
- [x] Bulk Student Upload (Excel).
- [x] Data Download (PDF & Excel).
- [x] Report Card Generation (PDF with Photo & QR Code).
- [x] ID Card Generation (PDF with Photo, LIN, Dates & QR Code).

### Phase 4: Communication & Content (Complete)
- [x] Real-time Chat for staff communication.
- [x] Content Sharing via a simple Video Library for students.
- [x] Digital Notice Board for school-wide announcements.

### Phase 5: Financial Management (Complete)
- [x] Fee Category and Expense Category Management.
- [x] Fee Structure creation system.
- [x] Automatic Invoice Generation based on class.
- [x] Manual Payment Recording (Full/Partial).
- [x] Expense Recording and Categorization.
- [x] Financial Reporting (Outstanding Balances, Income vs. Expenditure, etc.).

### Phase 6: Student Welfare & Co-curriculars (Complete)
- [x] Discipline & Conduct Log on student profiles.
- [x] Confidential Health & Medical Records Management.
- [x] Dormitory / Hostel Management (Rooms & Assignments).
- [x] Extracurricular Activity (Clubs) Management.

### Phase 7: Library & Resource Management (Complete)
- [x] Digital Library System with Book Catalog.
- [x] Book Checkout and Check-in System.
- [x] General School Inventory Management.
- [x] Shared Resource Booking System (e.g., labs, projectors).

### Phase 8: Enhanced Portals (Complete)
- [x] Dedicated Parent Portal with child's information.
- [x] Dedicated Student Portal with grades, timetables, etc.
- [x] Backend logic to link Parent and Student accounts.

### Phase 9 & 10: Advanced Administration (In Progress)
- [x] AI-Powered Predictive Model (Initial Scaffolding).
- [x] System Audit Trail for tracking user actions.
- [x] Alumni Network module to manage graduated students.
- [x] **New:** Scaffolding for a new "Exams" module (Set Exams, View Exams).
