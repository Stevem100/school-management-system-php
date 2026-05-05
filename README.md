# School Management System - PHP ERP

A comprehensive, multi-school, multi-branch School Management System built with pure PHP (no framework). Features Laravel-like routing, Supabase as database backend, and a modern responsive UI with Tailwind CSS.

## Features

- **Multi-School / Multi-Branch** support with role-based access control
- **20+ Modules**: Dashboard, Schools, Branches, Users, Roles, Modules, Students, Classes, Subjects, Exams, Results, Attendance, Timetable, Skills (CBC), Fees, Payments, Library, Transport, Hostel, LMS, Communication, Reports
- **Role-Based Access Control**: 11 roles (SuperAdmin, SchoolAdmin, BranchAdmin, Dean, Teacher, Accountant, Librarian, TransportManager, HostelManager, Parent, Student)
- **Authentication**: Session-based with SHA-256 password hashing
- **RESTful API**: Full JSON API alongside web views
- **Responsive Design**: Mobile-first with Tailwind CSS and dark mode support
- **Supabase Backend**: PostgreSQL via Supabase REST API (PostgREST)

## Tech Stack

- **PHP 8.0+** (no framework, no Composer)
- **Supabase** (PostgreSQL + PostgREST API)
- **Tailwind CSS** (via CDN)
- **Chart.js** (via CDN for dashboard charts)

## Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Stevem100/school-management-system-php.git
cd school-management-system-php
```

### 2. Configure Environment
Copy `.env.example` to configure:
```bash
# Edit config/app.php with your Supabase credentials
```

### 3. Set Up Database
Import the SQL schema in your Supabase SQL Editor:
1. Go to Supabase Dashboard → SQL Editor
2. Copy and run `setup.sql`
3. Copy and run `seed-data.sql` (for demo data)

### 4. Deploy
Upload all files to your web server with Apache (with `mod_rewrite` enabled).

**Required Apache modules:**
- `mod_rewrite`
- `mod_headers`

**PHP extensions:**
- `curl`
- `json`
- `session`
- `mbstring`

### 5. Test
Navigate to your domain. You should see the login page with 13 demo accounts.

## Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| SuperAdmin | admin@school.com | admin123 |
| School Admin | schooladmin@greenfield.ac.ke | demo123 |
| Branch Admin | branchadmin@greenfield.ac.ke | demo123 |
| Dean | dean@greenfield.ac.ke | demo123 |
| Teacher | mary@greenfield.ac.ke | demo123 |
| Accountant | accounts@greenfield.ac.ke | demo123 |
| Librarian | library@greenfield.ac.ke | demo123 |
| Transport Manager | transport@greenfield.ac.ke | demo123 |
| Hostel Manager | hostel@greenfield.ac.ke | demo123 |
| Student | brian.njorgemc@greenfield.ac.ke | student123 |

## Project Structure

```
├── index.php              # Entry point (front controller)
├── .htaccess              # URL rewriting
├── config/app.php         # Application config
├── core/                  # Framework core
│   ├── Router.php         # Laravel-like routing
│   ├── Database.php       # Supabase REST client
│   ├── Auth.php           # Authentication
│   ├── Session.php        # Session management
│   ├── Controller.php     # Base controller
│   ├── Request.php        # Request wrapper
│   ├── Response.php       # Response helpers
│   └── helpers.php        # Utility functions
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes
├── app/Controllers/       # Application controllers
├── views/                 # PHP views (HTML + PHP)
│   ├── layouts/           # App layout, header, sidebar
│   ├── auth/              # Login page
│   ├── dashboard/         # Dashboard
│   └── [modules]/         # Each module's views
├── public/                # Static assets
├── setup.sql              # Database schema
└── seed-data.sql          # Demo data
```

## License

MIT
