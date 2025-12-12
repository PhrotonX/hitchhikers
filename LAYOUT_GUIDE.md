# EXPECTED LAYOUT BY PAGE

## Regular Pages (use `layouts/app.blade.php` → `layouts/header.blade.php`)
**URLs**: `/`, `/about`, `/login`, `/register`, `/landing`

**Header Style**: Simple horizontal navigation
- Logo | Home | About | Logout | Get Started
- Matches Image 2 (dev branch style)
- CSS Class: `.main-header`

---

## Driver/Owner Dashboard Pages (use `layouts/dashboard.blade.php` → `layouts/header-dashboard.blade.php`)
**URLs**: `/driver/dashboard`, `/driver/earnings`, `/driver/rides`, `/owner/dashboard`

**Header Style**: Minimal toolbar
- Logo (left) | Theme Toggle + Notifications + User Icon (right)
- NO search bar, NO nav links
- Matches Image 3 (HitchHike-FrontEnd design)
- CSS Class: `.toolbar`

**Left Sidebar**: Blue navigation
- Dashboard
- Ride Management
- Earnings  
- My Profile

---

## Current Files:
- `resources/views/layouts/app.blade.php` - Regular pages layout
- `resources/views/layouts/dashboard.blade.php` - Dashboard pages layout
- `resources/views/layouts/header.blade.php` - Simple header for regular pages
- `resources/views/layouts/header-dashboard.blade.php` - Minimal header for dashboards

## Pages Using Dashboard Layout:
- `resources/views/pages/driver/dashboard.blade.php` (@extends('layouts.dashboard'))
- `resources/views/pages/driver/earnings.blade.php` (@extends('layouts.dashboard'))
- `resources/views/pages/driver/rides.blade.php` (@extends('layouts.dashboard'))
- `resources/views/pages/owner/dashboard.blade.php` (@extends('layouts.dashboard'))

## Pages Using Regular Layout:
- `resources/views/pages/index.blade.php` (@extends('layouts.app'))
- `resources/views/pages/landing.blade.php` (@extends('layouts.app'))
- `resources/views/pages/about.blade.php` (@extends('layouts.app'))

---

## Troubleshooting:
If you see the wrong header:
1. Hard refresh (Ctrl+F5) to clear browser cache
2. Check the URL - index page `/` is DIFFERENT from driver dashboard `/driver/dashboard`
3. Run: `php artisan view:clear && php artisan cache:clear`
