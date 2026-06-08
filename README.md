# BuildWise Authentication System
## Laravel-based Secure Inventory Management Login System

### 🎯 Features

✅ **Secure Authentication System**
- Login with username/password
- Registration with comprehensive validation
- Separate dashboards for Admin and User roles
- Account lockout after 5 failed login attempts (15 minutes)
- Rate limiting to prevent brute force attacks
- Session regeneration to prevent fixation attacks
- CSRF protection enabled
- Password hashing with bcrypt

✅ **Password Security**
- Real-time password strength indicator
- Visual strength meter (Weak/Medium/Strong)
- Password requirements validation:
  - Minimum 8 characters
  - Mixed case (uppercase & lowercase)
  - At least one number
  - At least one special character
- Password confirmation field
- Eye icon toggle for password visibility

✅ **User Registration**
- Collects: Username, Full Name, Birthdate, Role (Admin/User), Mobile Number, Password
- Client-side and server-side validation
- Unique username validation
- Mobile number format validation
- Age verification (birthdate must be before today)

✅ **Modern Professional Design**
- Clean, corporate hardware store theme
- Professional color palette:
  - Primary: Deep Navy Blue (#1e3a5f)
  - Secondary: Warm Amber/Orange (#d97706)
  - Accent: Professional Green (#059669)
- Responsive design for mobile and desktop
- Smooth animations and transitions
- Gradient backgrounds with floating elements

---

## 📋 Installation Guide

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM (optional, for asset compilation)

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel buildwise
cd buildwise
```

### Step 2: Database Setup

1. Create MySQL database:
```sql
CREATE DATABASE buildwise CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=buildwise
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Copy Files

Copy the provided files to your Laravel project:

**Models:**
- `User.php` → `app/Models/User.php`

**Controllers:**
- `AuthController.php` → `app/Http/Controllers/AuthController.php`
- `DashboardController.php` → `app/Http/Controllers/DashboardController.php`

**Requests:**
- `LoginRequest.php` → `app/Http/Requests/LoginRequest.php`
- `RegisterRequest.php` → `app/Http/Requests/RegisterRequest.php`

**Middleware:**
- `CheckRole.php` → `app/Http/Middleware/CheckRole.php`
- `Kernel.php` → `app/Http/Kernel.php`

**Migrations:**
- `create_users_table.php` → `database/migrations/YYYY_MM_DD_000000_create_users_table.php`

**Routes:**
- `web.php` → `routes/web.php`

**Views:**
- `layout.blade.php` → `resources/views/layouts/app.blade.php`
- `login.blade.php` → `resources/views/auth/login.blade.php`
- `register.blade.php` → `resources/views/auth/register.blade.php`
- `admin.blade.php` → `resources/views/dashboards/admin.blade.php`
- `user.blade.php` → `resources/views/dashboards/user.blade.php`

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🔒 Security Features Implemented

### 1. **Brute Force Protection**
- Rate limiting on login attempts (5 attempts per minute per IP)
- Account lockout after 5 failed attempts (15 minutes)
- Failed login attempt tracking in database

### 2. **Password Security**
- Bcrypt hashing (Laravel default)
- Strong password requirements enforced
- Password confirmation required
- No password storage in plain text
- Password visibility toggle (doesn't compromise security)

### 3. **Session Security**
- Session regeneration on login (prevents session fixation)
- Session invalidation on logout
- Token regeneration on logout
- HttpOnly cookies
- SameSite cookie protection

### 4. **CSRF Protection**
- Laravel's built-in CSRF token validation
- All forms include @csrf directive
- POST/PUT/DELETE requests protected

### 5. **SQL Injection Prevention**
- Eloquent ORM with parameterized queries
- No raw SQL queries with user input
- Input validation and sanitization

### 6. **XSS Prevention**
- Blade templating engine auto-escapes output
- {{ }} syntax prevents XSS
- Content Security Policy headers (recommended to add)

### 7. **Input Validation**
- Server-side validation on all inputs
- Type checking (email, date, numeric)
- Length restrictions
- Format validation (mobile numbers, usernames)
- Unique constraint validation

### 8. **Authentication Security**
- Remember me token (secure, hashed)
- Logout invalidates all tokens
- Middleware protection on protected routes
- Role-based access control

---

## 🛡️ Additional Security Recommendations

### 1. Add HTTPS (Production)
```apache
# Force HTTPS in .htaccess
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 2. Security Headers (Add to public/.htaccess)
```apache
# Security Headers
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
```

### 3. Environment Security
- Never commit `.env` file
- Use strong database passwords
- Rotate APP_KEY regularly
- Disable debug mode in production (`APP_DEBUG=false`)

### 4. Database Security
```sql
-- Create dedicated database user with limited privileges
CREATE USER 'buildwise_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON buildwise.* TO 'buildwise_user'@'localhost';
FLUSH PRIVILEGES;
```

### 5. File Permissions (Linux/Mac)
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Enable Laravel Security Features
```php
// config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'strict',

// config/cors.php
'supports_credentials' => true,
```

---

## 📱 Usage Guide

### Registration Flow
1. Navigate to `/register`
2. Fill in all required fields:
   - Username (unique, alphanumeric with dashes/underscores)
   - Full Name
   - Birthdate (must be before today)
   - Role (Admin or User)
   - Mobile Number (10-15 digits)
   - Password (strong password with indicator)
   - Confirm Password
3. Click "Create Account"
4. Automatically logged in and redirected to role-specific dashboard

### Login Flow
1. Navigate to `/login` (or root `/`)
2. Enter username and password
3. Optionally check "Remember me"
4. Click "Sign In"
5. Redirected to role-specific dashboard:
   - Admin → `/admin/dashboard`
   - User → `/user/dashboard`

### Account Security
- 5 failed login attempts lock account for 15 minutes
- Password must meet all strength requirements
- Session expires after 2 hours of inactivity (configurable)

---

## 🎨 Design Customization

### Color Palette
Located in `resources/views/layouts/app.blade.php`:

```css
--primary: #1e3a5f;          /* Deep Navy Blue */
--secondary: #d97706;        /* Warm Amber/Orange */
--accent: #059669;           /* Professional Green */
```

### Fonts
- Headings: **Outfit** (Google Fonts)
- Body: **DM Sans** (Google Fonts)

To change fonts, update the Google Fonts import in layout.blade.php

---

## 🧪 Testing

### Create Test Users

```php
// Run in tinker (php artisan tinker)
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create Admin
User::create([
    'username' => 'admin',
    'name' => 'System Administrator',
    'birthdate' => '1990-01-01',
    'role' => 'admin',
    'mobile_number' => '09171234567',
    'password' => Hash::make('Admin@123'),
]);

// Create User
User::create([
    'username' => 'user1',
    'name' => 'John Doe',
    'birthdate' => '1995-06-15',
    'role' => 'user',
    'mobile_number' => '09181234567',
    'password' => Hash::make('User@123'),
]);
```

---

## 🐛 Troubleshooting

### Issue: "Class CheckRole not found"
**Solution:** Make sure middleware is registered in `app/Http/Kernel.php`

### Issue: Database connection error
**Solution:** Verify `.env` database credentials and ensure MySQL is running

### Issue: Validation errors not showing
**Solution:** Check that `@error` directives match form field names

### Issue: Password strength not updating
**Solution:** Clear browser cache or use incognito mode

### Issue: "419 Page Expired" on form submission
**Solution:** Ensure `@csrf` token is included in all forms

---

## 📊 Database Schema

### Users Table
| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint | PRIMARY KEY, AUTO_INCREMENT |
| username | varchar(255) | UNIQUE, NOT NULL |
| name | varchar(255) | NOT NULL |
| birthdate | date | NOT NULL |
| role | enum('admin','user') | NOT NULL, DEFAULT 'user' |
| mobile_number | varchar(255) | NOT NULL |
| email | varchar(255) | UNIQUE, NULLABLE |
| password | varchar(255) | NOT NULL |
| failed_login_attempts | integer | DEFAULT 0 |
| locked_until | timestamp | NULLABLE |
| remember_token | varchar(100) | NULLABLE |
| created_at | timestamp | NULLABLE |
| updated_at | timestamp | NULLABLE |

---

## 📝 License

This authentication system is built with Laravel, which is open-sourced software licensed under the MIT license.

---

## 👨‍💻 Support

For issues or questions:
1. Check Laravel documentation: https://laravel.com/docs
2. Review security best practices: https://laravel.com/docs/security
3. Check error logs: `storage/logs/laravel.log`

---

## 🔄 Future Enhancements

Recommended additions:
- Email verification on registration
- Two-factor authentication (2FA)
- Password reset functionality
- Activity logging
- API authentication with Laravel Sanctum
- Admin user management interface
- Audit trails for sensitive actions
- IP whitelisting for admin access
- Automated backup system

---

**Built with ❤️ using Laravel Framework**
