# 🛠️ Technician Hiring Platform – Backend (Laravel API)

This is the **backend API** for the Technician Hiring Platform, built using **Laravel 10+**, designed to serve a frontend (Angular) client for managing technician job postings and bids.

---

## 🚀 Features

- User registration & login (Job Owner, Artisan, Admin)
- Job posting creation, update, soft deletion
- Bids system for artisans
- Admin approval and moderation
- RESTful API structure with JSON responses
- File uploads (attachments)
- Soft delete support with `deleted_at`

---

## 🧰 Technologies Used

- Laravel (PHP Framework)
- MySQL/MariaDB
- Laravel Sanctum (for auth if used)
- File upload via public assets

---


## Installation & Setup

1. **Clone the repository**  
   ```bash
   git clone https://github.com/your-username/backend-THP.git
   cd backend-THP/project-THP
   ```

2. **Install dependencies**  
   ```bash
   composer install
   ```

3. **Copy `.env` file and generate app key**  
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**  
   - Open `.env` file.
   - Set your database name, username, and password.

5. **Run migrations**  
   ```bash
   php artisan migrate
   ```

6. **Start the development server**  
   ```bash
   php artisan serve
   ```

7. **Access the API**  
   - The base URL will be: `http://localhost:8000/api` or any API in the "api.php" Rotes

## Notes

- Make sure the `public/assets` folder is writable if attachments are being uploaded.
- Use Postman or connect the Angular frontend to test endpoints.
- To view soft-deleted posts, enable `withTrashed()` on your Eloquent queries if needed.

---

For any issues or questions, contact the development team.
