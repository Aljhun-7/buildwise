# 🚀 BuildWise - Quick Start Guide

## What You're Getting

A complete, production-ready Laravel authentication system for BuildWise Inventory Management with:

✅ **Secure Login & Registration System**
✅ **Role-Based Access Control** (Admin & User)
✅ **Modern Professional Design** (Hardware Store Theme)
✅ **Advanced Security Features** (Rate Limiting, Account Lockout, CSRF Protection)
✅ **Real-time Password Strength Indicator**
✅ **Responsive Design** (Mobile & Desktop)

---

## 📦 What's Included

### PHP Files (Backend)
- `AuthController.php` - Login/Registration logic with security
- `DashboardController.php` - Admin/User dashboard controllers
- `User.php` - User model with security methods
- `LoginRequest.php` - Login validation
- `RegisterRequest.php` - Registration validation with strong password rules
- `CheckRole.php` - Role-based middleware
- `Kernel.php` - Middleware configuration

### Blade Templates (Frontend)
- `layout.blade.php` - Main layout with professional design
- `login.blade.php` - Login page with modern UI
- `register.blade.php` - Registration with password strength meter
- `admin.blade.php` - Admin dashboard landing page
- `user.blade.php` - User dashboard landing page

### Database
- `create_users_table.php` - Migration file
- `database-setup.sql` - Direct SQL setup (alternative method)

### Documentation
- `README.md` - Complete documentation
- `FOLDER-STRUCTURE.md` - File placement guide
- This `QUICK-START.md` guide

---

## ⚡ 3-Minute Setup (Method 1 - Recommended)

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+

### Steps

1️⃣ **Create Laravel Project**
```bash
composer create-project laravel/laravel buildwise
cd buildwise
```

2️⃣ **Create Folders**
```bash
mkdir -p resources/views/layouts
mkdir -p resources/views/auth
mkdir -p resources/views/dashboards
mkdir -p app/Http/Requests
```

3️⃣ **Copy All Files** (from buildwise-auth folder to your Laravel project)

See `FOLDER-STRUCTURE.md` for exact placement locations.

4️⃣ **Configure Database**

Edit `.env` file:
```env
DB_DATABASE=buildwise
DB_USERNAME=root
DB_PASSWORD=your_password
```

5️⃣ **Setup Database**
```bash
# Create database first in MySQL:
mysql -u root -p
CREATE DATABASE buildwise;
exit;

# Then run migrations
php artisan migrate
```

6️⃣ **Generate App Key**
```bash
php artisan key:generate
```

7️⃣ **Start Server**
```bash
php artisan serve
```

8️⃣ **Access Application**

Open browser: `http://localhost:8000`

---

## 🗄️ Alternative Setup (Method 2 - Direct SQL)

If you prefer to skip Laravel migrations:

1. Follow steps 1-3 from Method 1
2. Import SQL directly:
```bash
mysql -u root -p < database-setup.sql
```
3. Continue with steps 6-8

---

## 🔐 Test Accounts (Included in SQL)

**Administrator:**
- Username: `admin`
- Password: `Admin@123`
- Access: Full admin dashboard

**Regular User:**
- Username: `user1`
- Password: `User@123`
- Access: User dashboard

---

## 🎨 Design Preview

### Color Scheme (Professional Hardware Store Palette)
- **Primary:** Deep Navy Blue (#1e3a5f)
- **Secondary:** Warm Amber/Orange (#d97706)
- **Accent:** Professional Green (#059669)
- **Neutrals:** Sophisticated grays

### Typography
- **Headings:** Outfit (Bold, Modern)
- **Body:** DM Sans (Clean, Professional)

### Features
- Gradient backgrounds with floating elements
- Smooth animations and transitions
- Card-based layouts
- Modern form designs
- Responsive grid systems

---

## 🛡️ Security Features

### Authentication Security
✅ Rate limiting (5 attempts per minute)
✅ Account lockout (15 min after 5 failed attempts)
✅ Session regeneration
✅ CSRF protection
✅ Password hashing (bcrypt)
✅ Remember me token (secure)

### Password Security
✅ Minimum 8 characters
✅ Mixed case required
✅ Numbers required
✅ Special characters required
✅ Real-time strength indicator
✅ Visual feedback (Weak/Medium/Strong)

### Input Validation
✅ Username uniqueness
✅ Mobile number format
✅ Birthdate validation
✅ Role validation
✅ XSS prevention
✅ SQL injection prevention

---

## 📱 Features Overview

### Registration Page
- Username (unique, alphanumeric + dashes/underscores)
- Full Name
- Birthdate (with date picker)
- Role dropdown (Admin/User)
- Mobile Number (10-15 digits)
- Password with strength meter
- Confirm Password
- Eye icon to toggle password visibility

### Login Page
- Username field
- Password field with toggle
- Remember me checkbox
- Professional design
- Error handling
- Account lockout messaging

### Admin Dashboard
- Welcome message
- Statistics cards (Products, Value, Alerts)
- Quick actions grid
- Professional navbar
- User avatar
- Logout functionality

### User Dashboard
- Personalized welcome
- Account information card
- Getting started guide
- Quick access links
- Clean interface

---

## 🔧 Customization

### Change Colors
Edit `resources/views/layouts/app.blade.php`:
```css
:root {
    --primary: #1e3a5f;     /* Your color */
    --secondary: #d97706;   /* Your color */
    --accent: #059669;      /* Your color */
}
```

### Change Logo
Replace BuildWise icon SVG in:
- `login.blade.php`
- `register.blade.php`
- `admin.blade.php`
- `user.blade.php`

### Adjust Security Settings
In `AuthController.php`:
```php
// Change lockout duration (currently 15 minutes)
$this->locked_until = now()->addMinutes(30); // 30 minutes

// Change max attempts (currently 5)
if ($this->failed_login_attempts >= 3) { // 3 attempts
```

---

## 🐛 Troubleshooting

**"Class CheckRole not found"**
→ Run: `composer dump-autoload`

**"Base table not found"**
→ Run: `php artisan migrate`

**"419 Page Expired"**
→ Clear cache: `php artisan cache:clear`

**Password strength not showing**
→ Clear browser cache or use incognito

**Database connection error**
→ Check `.env` credentials and ensure MySQL is running

---

## 📚 Next Steps

After setup, you can:

1. **Add More Features:**
   - Email verification
   - Password reset
   - Two-factor authentication
   - User management interface

2. **Enhance Security:**
   - Add security headers
   - Enable HTTPS
   - Implement CSP
   - Add audit logging

3. **Build Your Inventory System:**
   - Products management
   - Stock tracking
   - Reports & analytics
   - Order processing

---

## 📞 Support

For detailed information, see:
- `README.md` - Complete documentation
- `FOLDER-STRUCTURE.md` - File placement guide
- Laravel Docs: https://laravel.com/docs

---

## ✅ Pre-Launch Checklist

Before going to production:

- [ ] Change `APP_DEBUG=false` in `.env`
- [ ] Set strong `APP_KEY`
- [ ] Use strong database password
- [ ] Enable HTTPS
- [ ] Add security headers
- [ ] Test all functionality
- [ ] Create backup system
- [ ] Review error logs
- [ ] Test on mobile devices
- [ ] Change default test passwords

---

**You're all set! 🎉**

Run `php artisan serve` and visit `http://localhost:8000` to see your BuildWise login system in action!
