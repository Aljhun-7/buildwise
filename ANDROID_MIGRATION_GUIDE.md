# IAS_BuildWise Android Migration Guide

This guide explains how to convert this Laravel web project into an Android app using Android Studio, what changes happen in backend/API/database, and what to do so everything works again.

## 1. Current Architecture (Now)

- Frontend: Laravel Blade pages (`resources/views/...`)
- Backend: Laravel controllers (`app/Http/Controllers/...`)
- Database: MySQL (`buildwise`)
- Auth: Session-based web auth (plus login/register logic)
- Business flows: products, sales, activity logs, account settings

When converting to Android, the web Blade UI is replaced by native Android UI. Laravel remains as backend, but mostly through API endpoints returning JSON.

## 2. Recommended Conversion Strategy

Use **Native Android + Laravel REST API** (recommended), not full WebView.

- Keep Laravel + MySQL as backend.
- Add API routes/controllers/resources for mobile.
- Build Android app in Kotlin (Jetpack Compose or XML).

## 3. Step-by-Step Conversion

## Step 1: Freeze and backup

1. Backup database (`buildwise`) and project files.
2. Export SQL dump.
3. Commit current code state.

## Step 2: Prepare Laravel for API mode

1. Add API routes in `routes/api.php`.
2. Keep existing business logic, but return JSON instead of Blade.
3. Add API Resource classes for consistent responses.

Example API groups:
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout`
- `GET /api/v1/products`
- `POST /api/v1/sales/process`
- `GET /api/v1/sales/reports`
- `GET /api/v1/account/settings`
- `POST /api/v1/account/profile-picture`

## Step 3: Mobile authentication

Recommended: Laravel Sanctum token auth for mobile.

1. Issue token on login.
2. Android stores token securely (EncryptedSharedPreferences).
3. Send `Authorization: Bearer <token>` for every protected request.

## Step 4: Enable CORS and API security

1. Configure `config/cors.php` for mobile app domain/IP.
2. Force HTTPS in production.
3. Add request validation + rate limiting for API routes.

## Step 5: Support mobile-ready data behavior

1. Add pagination for list APIs.
2. Add filtering/sorting params (`search`, `category`, `period`, `user_id`).
3. Add proper error JSON (`success`, `message`, `errors`).

## Step 6: File upload support for profile/product images

1. Keep storage disk `public`.
2. Ensure `php artisan storage:link`.
3. Return full file URLs in API responses.

## Step 7: Create Android project in Android Studio

1. New project in Android Studio (Kotlin).
2. Add dependencies:
   - Retrofit + OkHttp
   - Gson/Moshi
   - Coroutines
   - Lifecycle ViewModel
   - Room (optional offline cache)
3. Configure `BASE_URL` to your Laravel server.

## Step 8: Build Android layers

1. `data` layer: API services + DTOs.
2. `domain` layer: use-cases/business wrappers.
3. `ui` layer: screens:
   - Login
   - Dashboard
   - Products
   - Sales reports
   - Account settings

## Step 9: Migrate each feature

1. Auth (login/logout/register if needed)
2. Product list/create/update/archive
3. Sale processing
4. Order history/reporting by user
5. Login logs + product logs
6. Profile + profile picture upload

## Step 10: Test end-to-end

1. Test one user order flow.
2. Test multiple users ordering same product (concurrency).
3. Verify stock updates and order numbers are unique.
4. Validate API auth/permissions.

## Step 11: Deploy setup

1. Backend on production server with HTTPS.
2. MySQL production DB.
3. Update Android `BASE_URL`.
4. Build signed APK/AAB.

## 4. What Changes in Backend, API, and Database

## Backend changes

- Before: controllers return Blade views.
- After: API controllers return JSON.
- Business logic should stay centralized in services/controllers, reused by web + mobile.

## API changes

- Add versioned endpoints (`/api/v1/...`).
- Add token-based auth middleware (`auth:sanctum`).
- Add standardized response format and error handling.
- Add pagination/meta for lists.

## Database changes

Your core tables remain valid (`users`, `products`, `sales`, `product_activity_logs`, `login_logs`).

Likely additions:
- `personal_access_tokens` (for Sanctum)
- optional device/session tracking table
- optional push notification token table (`device_tokens`)

No need to redesign DB unless adding mobile-only features.

## 5. What To Do So It Works Again (Recovery Checklist)

After conversion/update, run:

1. `composer install`
2. `php artisan key:generate` (if needed)
3. `php artisan migrate`
4. `php artisan storage:link`
5. `php artisan config:clear`
6. `php artisan cache:clear`
7. `php artisan route:clear`
8. `php artisan view:clear`

Then verify:

1. API routes exist: `php artisan route:list`
2. DB connection is correct in `.env`
3. CORS allows mobile requests
4. Android `BASE_URL` points to reachable server
5. Token auth works (no 401)
6. File uploads work (`storage` permissions OK)

## 6. Common Issues and Fixes

- `401 Unauthorized`: token missing/expired -> re-login and attach bearer token.
- `419 Page Expired`: using web CSRF flow on API endpoint -> use token auth API route.
- Image not loading: missing `storage:link` or wrong URL mapping.
- API not reachable on emulator:
  - use `10.0.2.2` for localhost from Android emulator
  - ensure Apache/Laravel host is accessible
- Concurrent sales stock mismatch:
  - keep DB transaction + row lock in sale processing

## 7. Suggested Execution Order for This Project

1. Finalize Laravel API endpoints for existing features.
2. Test APIs in Postman first.
3. Build Android login + token handling.
4. Build product + sales screens.
5. Build account settings + logs screen.
6. Run integrated QA with 2+ user accounts.
7. Release beta APK.

