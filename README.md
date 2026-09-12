# Technician Hiring Platform â€” Backend API

A Laravel REST API that powers a technical hiring platform, connecting job owners (employers) who post work requests with artisans (technicians) who bid on them. Designed to be consumed by the companion Angular frontend, [Project-THP](https://github.com/ali-yaqoup/Backend-THP/tree/master/project-THP).

> **Note:** The Laravel application lives inside the `project-THP/` subdirectory of this repo.

---

## Tech Stack

| Technology | Version |
|---|---|
| PHP | ^8.2 |
| Laravel Framework | ^12.0 |
| Laravel Sanctum | ^4.1 |
| MySQL / MariaDB | â€” |
| PHPUnit | ^11.5 |

---

## Features

- **Three-role user system** â€” Employer, Artisan, and Admin roles stored via a `Role` model
- **Two-step login with OTP** â€” Login proceeds in two steps (`/login-step1`, `/login-step2`) with an `LoginOtp` model backing it
- **Email verification** â€” Signed email-verification link marks users as verified and sets their status to `pending`
- **Password reset via OTP** â€” Send and verify an OTP to reset a password
- **Job post management** â€” Employers create, update, and soft-delete `FormPost` records with file attachments
- **Bidding system** â€” Artisans submit bids on posts (`Bid` model); employers view bids per post and accept or reject them
- **Admin moderation** â€” Admin endpoints for listing/deleting users and posts, toggling user status, and viewing platform statistics (user counts, post counts, deleted-record counts)
- **JSON-only API** â€” All responses are JSON; no server-rendered views

---

## Getting Started

The Laravel project is in the `project-THP/` subdirectory. All commands below should be run from that directory.

```bash
# 1. Clone the repository
git clone https://github.com/ali-yaqoup/Backend-THP.git
cd Backend-THP/project-THP

# 2. Install PHP dependencies
composer install

# 3. Copy the environment file and generate an app key
cp .env.example .env
php artisan key:generate

# 4. Configure your database credentials in .env
#    DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Run migrations
php artisan migrate

# 6. Start the development server
php artisan serve
```

The API will be available at `http://localhost:8000/api`.

---

## Project Structure

```
project-THP/
â”œâ”€â”€ app/
â”‚   â”œâ”€â”€ Http/           # Controllers and middleware (Auth, Admin, Post, Bid)
â”‚   â”œâ”€â”€ Models/         # Eloquent models: User, FormPost, Bid, Role, LoginOtp
â”‚   â”œâ”€â”€ Notifications/  # Email notification classes
â”‚   â””â”€â”€ Providers/      # Service providers
â”œâ”€â”€ database/           # Migrations and seeders
â”œâ”€â”€ routes/
â”‚   â””â”€â”€ api.php         # All API route definitions
â”œâ”€â”€ storage/            # File uploads and logs
â””â”€â”€ tests/              # PHPUnit test suite
```

---

## API Endpoints

All routes are prefixed with `/api`.

### Public

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/register` | Register a new user |
| `POST` | `/login-step1` | First step of OTP login |
| `POST` | `/login-step2` | Second step of OTP login |
| `POST` | `/password/send-otp` | Send password-reset OTP |
| `POST` | `/password/reset` | Reset password with OTP |
| `GET` | `/email/verify/{id}/{hash}` | Verify email address |

### Employer (auth:sanctum + employer role)

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/posts` | List all job posts |
| `GET` | `/posts/{id}` | Get a single job post |
| `POST` | `/posts` | Create a new job post |
| `PUT` | `/posts/{id}` | Update a job post |
| `DELETE` | `/posts/{id}` | Soft-delete a job post |
| `GET` | `/posts/bids/{postId}` | List bids for a post |
| `PUT` | `/bids/{id}/status` | Accept or reject a bid |

### Admin (auth:sanctum + admin role)

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/admin/users` | List all users |
| `DELETE` | `/admin/users/{id}` | Delete a user |
| `PUT` | `/users/{id}` | Update a user |
| `PATCH` | `/users/{id}/status` | Toggle user status |
| `GET` | `/admin/form-posts` | List all job posts |
| `DELETE` | `/admin/form-posts/{id}` | Delete a job post |
| `GET` | `/admin/form-posts-count` | Count active posts |
| `GET` | `/admin/form-posts-deleted-count` | Count soft-deleted posts |
| `GET` | `/admin/artisan-count` | Count artisan users |
| `GET` | `/admin/user-count` | Count all users |
| `GET` | `/admin/users-deleted-count` | Count deleted users |
| `GET` | `/admin/user-stats` | Aggregated user statistics |

---

## Related Repository

This backend is built to serve the **Project-THP** Angular frontend, which is included in this repo under `project-THP/`. A standalone Angular client repository may also be maintained separately.

## License & copyright

Copyright © 2026 Ali Yaqoub. All rights reserved.

This software and its contents are proprietary. Unauthorized copying, distribution, modification, or commercial use is prohibited without prior written permission from the copyright holder.
