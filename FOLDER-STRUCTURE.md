# BuildWise Laravel Project Structure

After creating your Laravel project, place files in these locations:

## Directory Structure

```
buildwise/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ← Copy here
│   │   │   └── DashboardController.php      ← Copy here
│   │   ├── Middleware/
│   │   │   └── CheckRole.php                ← Copy here
│   │   ├── Requests/
│   │   │   ├── LoginRequest.php             ← Copy here
│   │   │   └── RegisterRequest.php          ← Copy here
│   │   └── Kernel.php                       ← Replace existing
│   └── Models/
│       └── User.php                         ← Replace existing
├── database/
│   └── migrations/
│       └── YYYY_MM_DD_000000_create_users_table.php  ← Copy here
│           (Rename with current date, e.g., 2024_02_08_000000_create_users_table.php)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php                ← Copy layout.blade.php here
│       ├── auth/
│       │   ├── login.blade.php              ← Copy here
│       │   └── register.blade.php           ← Copy here
│       └── dashboards/
│           ├── admin.blade.php              ← Copy here
│           └── user.blade.php               ← Copy here
├── routes/
│   └── web.php                              ← Replace existing
├── .env                                     ← Update with database config
└── README.md                                ← Project documentation

```

## Quick Setup Steps

1. **Create Laravel Project:**
   ```bash
   composer create-project laravel/laravel buildwise
   cd buildwise
   ```

2. **Create Required Directories:**
   ```bash
   mkdir -p resources/views/layouts
   mkdir -p resources/views/auth
   mkdir -p resources/views/dashboards
   mkdir -p app/Http/Requests
   ```

3. **Copy Files:**
   - Copy all provided PHP and Blade files to their respective locations
   - Pay attention to the folder structure above

4. **Setup Database:**
   - Update `.env` file with your database credentials
   - Run: `php artisan migrate`
   - OR import `database-setup.sql` directly into MySQL

5. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```

6. **Start Server:**
   ```bash
   php artisan serve
   ```

7. **Access the Application:**
   - Open browser to: `http://localhost:8000`
   - Login page will be shown automatically

## File Checklist

- [ ] AuthController.php
- [ ] DashboardController.php
- [ ] CheckRole.php
- [ ] LoginRequest.php
- [ ] RegisterRequest.php
- [ ] User.php (Model)
- [ ] Kernel.php
- [ ] create_users_table.php (Migration)
- [ ] web.php (Routes)
- [ ] app.blade.php (Layout)
- [ ] login.blade.php
- [ ] register.blade.php
- [ ] admin.blade.php
- [ ] user.blade.php
- [ ] .env (configured)

## Test Credentials

After running migrations or importing SQL:

**Administrator:**
- Username: `admin`
- Password: `Admin@123`

**Regular User:**
- Username: `user1`
- Password: `User@123`

## Notes

- Make sure all blade files have the `.blade.php` extension
- Ensure proper namespace declarations in PHP files
- Check that middleware is registered in Kernel.php
- Verify database connection in .env before migrating
