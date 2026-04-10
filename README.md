# Job Portal Application

A role-based job portal built with Laravel 13 where employers can post jobs, employees can apply, and admins can manage the platform.

## Project Description

This application supports the full hiring flow:

- Public users can browse and filter active jobs.
- Employees can register, build profiles, upload resumes, and apply to jobs.
- Employers can create company profiles, post jobs, and manage listings.
- Admins can monitor users, companies, jobs, and system settings.
- Email notifications are sent when job applications are submitted.
- The UI has been modernized with responsive layouts and a blue-gradient visual theme.

## Core Features

### 1) Authentication and Roles

- Laravel Breeze authentication (register, login, reset password, verify email).
- Role-based users: `admin`, `employer`, `employee`.
- Registration allows `employee` and `employer`; admin is seeded.

### 2) Employer Module

- Create and update company profile (logo, description, industry, website, size, location).
- Create, edit, close, and delete job postings.
- View personal job dashboard and posting status.

### 3) Employee Module

- Create and update employee profile (bio, skills, education, experience, resume, etc.).
- Browse jobs with filters:
  - Search (title/company)
  - Location
  - Job type
  - Salary range
- Apply to active jobs with cover letter and resume upload.
- Prevent duplicate applications per job/user.
- Track applications and statuses (`pending`, `reviewed`, `accepted`, `rejected`).

### 4) Admin Module

- View paginated users, companies, and jobs.
- Delete users, companies, and jobs.
- View system summary/settings page.

### 5) Notifications and Email

- Sends employer notification on new application.
- Sends applicant confirmation email after submission.
- Email templates are in `resources/views/emails`.

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite
- **Database:** MySQL (default in `.env.example`)
- **Auth Scaffolding:** Laravel Breeze
- **Queue/Cache/Session:** database drivers configured by default

## Data Model (High Level)

- `users` (includes `user_type`)
- `companies` (belongs to user, employer profile)
- `employee_profiles` (belongs to user, employee profile)
- `job_postings` (belongs to company, mapped by `App\Models\Job`)
- `job_applications` (belongs to job and user, unique pair on `job_id + user_id`)

## Authorization

Policies and gates enforce access:

- `JobPolicy` for employer ownership of jobs
- `CompanyPolicy` for employer ownership of company profile
- `EmployeeProfilePolicy` for employee ownership of profile
- Gate `isAdmin` for admin-only routes

## Main Routes

- Public:
  - `GET /` landing page
  - `GET /jobs` browse jobs
  - `GET /jobs/{job}` view job details
- Authenticated:
  - `GET /dashboard`
  - Company: `company.index`, `company.store`, `company.update`
  - Employer Jobs: `jobs.index`, `jobs.create`, `jobs.store`, `jobs.edit`, `jobs.update`, `jobs.destroy`
  - Employee Profile: `profile.employee`, `profile.employee.store`, `profile.employee.update`
  - Applications: `job-applications.create`, `job-applications.store`, `job-applications.index`
- Admin:
  - `admin.users`, `admin.companies`, `admin.jobs`, `admin.settings`
  - Destroy routes for users/companies/jobs

## Installation and Setup

### Prerequisites

- PHP `^8.3`
- Composer
- Node.js + npm
- MySQL (or compatible DB configured in `.env`)

### Quick Setup (recommended)

```bash
composer run setup
php artisan storage:link
php artisan db:seed
composer run dev
```

### Manual Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan db:seed
npm install
npm run dev
php artisan serve
```

## Environment Notes

Update `.env` for:

- `DB_*` values (database connection)
- `MAIL_*` values for real email delivery (default is `log`)
- `APP_URL` for correct URL generation

## Seeded Accounts

Default seeder creates:

- Admin: `admin@jobportal.com` / `password`
- Test user: `test@example.com` (factory-based user)

## Useful Commands

```bash
# Run app services (server + queue listener + logs + vite)
composer run dev

# Run tests
composer test

# Build assets for production
npm run build
```

## File Uploads

- Employer company logo: stored under `public` disk (`company-logos`)
- Employee resume/profile and job application resume: stored under `public` disk (`resumes`)
- Run `php artisan storage:link` so uploaded files are publicly accessible.

## Project Structure (Key Parts)

- `app/Http/Controllers` business logic by module
- `app/Models` Eloquent entities and relationships
- `app/Policies` role/ownership authorization
- `app/Mail` mail classes for application notifications
- `resources/views` Blade templates (modernized UI)
- `routes/web.php` main web routes
- `database/migrations` schema definitions

## Current UI/UX Direction

- Fully responsive Blade views
- Shared modern component styling
- Blue-gradient page backgrounds
- Improved visual hierarchy for admin, jobs, company, profile, and application flows

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).
# Job_Portal_Appliction_Laravel
