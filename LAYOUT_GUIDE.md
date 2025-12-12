# Layout Guide - HitchHikers Application

## Overview
This application uses **TWO layout systems** to match the frontend design:

1. **`layouts/app.blade.php`** - Simple header for regular pages
2. **`layouts/dashboard.blade.php`** - Minimal toolbar + sidebar for dashboard pages

---

## Critical Understanding

### Dashboard Structure:
1. **First page of any dashboard = Index page (`/`)**
   - Passengers see: ride search, available rides, map
   - Drivers see: same index page (driver features enabled if enrolled)
   - Owners see: redirected to owner dashboard

2. **Subsequent dashboard pages = Separate pages**
   - Driver Earnings (`/driver/earnings`)
   - Driver Rides (`/driver/rides`)
   - Owner Dashboard (`/owner/dashboard`)

---

## Layout System

### 1. Regular Pages (layouts/app.blade.php)
**Uses:** Simple header from dev branch
**Header Style:** Logo | Home | About | Logout | Get Started button
**File:** `resources/views/layouts/header.blade.php`

#### Pages Using This Layout:
- `/` (home/index) - Main dashboard for all users
- `/landing` - Landing page for guests
- `/about` - About page
- `/ride/create` - Create ride form
- `/vehicle/create` - Vehicle registration
- `/user/{user}` - User profile view
- `/login`, `/register` - Authentication pages

---

### 2. Dashboard Pages (layouts/dashboard.blade.php)
**Uses:** Minimal toolbar + left sidebar navigation
**Header Style:** Logo (left) | Theme Toggle + Notifications + User Icon (right)
**File:** `resources/views/layouts/header-dashboard.blade.php`

#### Pages Using This Layout:
- `/driver/earnings` - Driver earnings and transaction history
- `/driver/rides` - Driver ride management (approve/reject requests)
- `/owner/dashboard` - Owner statistics and permission management

**Note:** `/driver/dashboard` redirects to `/` (home)

---

## Route Structure

### Driver Routes:
```php
Route::get('driver/dashboard', fn() => redirect()->route('home'))->name('driver.dashboard');
Route::get('driver/earnings', [DriverController::class, 'earnings'])->name('driver.earnings');
Route::get('driver/rides', [DriverController::class, 'rides'])->name('driver.rides');
```

### Owner Routes:
```php
Route::get('owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
```

---

## Troubleshooting

### "Changes not showing after hard refresh"
1. **Verify correct URL:**
   - Index page: `http://localhost:8000/` (simple header)
   - Driver earnings: `http://localhost:8000/driver/earnings` (minimal toolbar + sidebar)

2. **Clear all caches:**
   ```bash
   php artisan view:clear
   php artisan cache:clear
   npm run build
   ```

3. **Hard refresh browser:** `Ctrl + Shift + R`

### "Database error: Collection::with does not exist"
**Fixed in commit d859970** - Moved `->with()` before `->first()`

---

## Key Differences: Regular vs Dashboard Pages

| Feature | Regular Pages (layouts.app) | Dashboard Pages (layouts.dashboard) |
|---------|----------------------------|-------------------------------------|
| Header | Simple nav bar | Minimal toolbar |
| Navigation | Top horizontal links | Left sidebar menu |
| Used By | Index, Landing, About, Forms | Earnings, Rides, Owner Dashboard |

