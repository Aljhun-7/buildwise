BuildWise


Features
Secure Authentication System
- Login with username/password
- Registration with comprehensive validation
- Separate dashboards for Admin and User roles
- Account lockout after 5 failed login attempts (15 minutes)
- Rate limiting to prevent brute force attacks
- Session regeneration to prevent fixation attacks
- CSRF protection enabled
- Password hashing with bcrypt

Password Security
- Real-time password strength indicator
- Visual strength meter (Weak/Medium/Strong)
- Password requirements validation:
  - Minimum 8 characters
  - Mixed case (uppercase & lowercase)
  - At least one number
  - At least one special character
- Password confirmation field
- Eye icon toggle for password visibility

User Registration
- Collects: Username, Full Name, Birthdate, Role (Admin/User), Mobile Number, Password
- Client-side and server-side validation
- Unique username validation
- Mobile number format validation
- Age verification (birthdate must be before today)

Modern Professional Design
- Clean, corporate hardware store theme
- Professional color palette:
  - Primary: Deep Navy Blue (#1e3a5f)
  - Secondary: Warm Amber/Orange (#d97706)
  - Accent: Professional Green (#059669)
- Responsive design for mobile and desktop
- Smooth animations and transitions
- Gradient backgrounds with floating elements


Security Features Implemented
1.Brute Force Protection
- Rate limiting on login attempts (5 attempts per minute per IP)
- Account lockout after 5 failed attempts (15 minutes)
- Failed login attempt tracking in database

2. Password Security
- Bcrypt hashing (Laravel default)
- Strong password requirements enforced
- Password confirmation required
- No password storage in plain text
- Password visibility toggle (doesn't compromise security)

3. Session Security
- Session regeneration on login (prevents session fixation)
- Session invalidation on logout
- Token regeneration on logout
- HttpOnly cookies
- SameSite cookie protection

4. CSRF Protection
- Laravel's built-in CSRF token validation
- All forms include @csrf directive
- POST/PUT/DELETE requests protected

5. SQL Injection Prevention
- Eloquent ORM with parameterized queries
- No raw SQL queries with user input
- Input validation and sanitization

6. XSS Prevention
- Blade templating engine auto-escapes output
- {{ }} syntax prevents XSS
- Content Security Policy headers (recommended to add)

7. Input Validation
- Server-side validation on all inputs
- Type checking (email, date, numeric)
- Length restrictions
- Format validation (mobile numbers, usernames)
- Unique constraint validation

8. Authentication Security
- Remember me token (secure, hashed)
- Logout invalidates all tokens
- Middleware protection on protected routes
- Role-based access control


Usage Guide
Registration Flow
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

Login Flow
1. Navigate to `/login` (or root `/`)
2. Enter username and password
3. Optionally check "Remember me"
4. Click "Sign In"
5. Redirected to role-specific dashboard:
   - Admin → `/admin/dashboard`
   - User → `/user/dashboard`

Account Security
- 5 failed login attempts lock account for 15 minutes
- Password must meet all strength requirements
- Session expires after 2 hours of inactivity (configurable)


Future Enhancements
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
