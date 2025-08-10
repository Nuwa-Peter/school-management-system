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

### Phase 5: Financial Management (Complete)
- [x] Fee Structure Management (CRUD for fee types).
- [x] Invoice Generation system (per student, based on class).
- [x] Payment Recording (manual entry by bursar).
- [x] Expense Tracking module.
- [x] Financial Reports (Outstanding Balances, Payment Summaries, Income/Expenditure).
- [x] Invoice Export to PDF and Excel.

### Phase 6: Student Welfare & Co-curriculars (Complete)
- [x] Discipline & Conduct Log (integrated into student profile).
- [x] Health & Medical Records management.
- [x] Dormitory / Hostel Management (CRUD for dorms and rooms).
- [x] Room Assignment system for students.
- [x] Extracurricular Activity Management (CRUD for clubs and memberships).

### Phase 7: Library & Resource Management (Complete)
- [x] Digital Library System with book catalog.
- [x] Book Checkout / Check-in system with basic fine calculation.
- [x] Resource Booking System with interactive calendar view.
- [x] General Inventory Management for school assets.

### Phase 8: Enhanced Portals & Engagement (Complete)
- [x] Parent & Student database relationships established.
- [x] Dedicated Parent Portal dashboard.
- [x] Dedicated Student Portal dashboard.
- [x] Digital Notice Board (Announcements module).
- [x] Role-aware sidebar navigation for all user types.

---

## Next Steps: Phase 9 & 10 Plan

### Phase 9: AI-Powered Academic & Financial Intelligence

1.  ***Predictive Student Performance Model:*** Identify at-risk students using PHP-ML.
2.  ***Subject Performance Analysis:*** Identify subjects where students are consistently struggling.

### Phase 10: Advanced Administration & System Integrity

1.  ***System Audit Trail:*** Create a secure log of all critical actions performed in the system.
2.  ***Alumni Network Module:*** Maintain a connection with students after they graduate.
3.  ***Advanced Timetabling Module:*** Automate the creation of the school's master timetable.
4.  ***Transportation Module:*** Manage the school's transportation services.
