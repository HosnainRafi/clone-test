# Dynamic Route System Based on Site Type

This document explains the implementation of the dynamic routing system that changes route prefixes based on the site type (university, department, or faculty).

## Overview

The system automatically adjusts admin route prefixes based on the type of site:

- **University Site** (`localhost:8000`): `/admin/university/*`
- **Department Site** (`cse.localhost:8000`, `ict.localhost:8000`): `/admin/department/*`
- **Faculty Site** (when logged in as teacher): `/admin/faculty/*`

## Database Changes

### Migration: Add `site_type` Column

A new column `site_type` has been added to the `sites` table:

```php
Schema::table('sites', function (Blueprint $table) {
    $table->enum('site_type', ['university', 'department', 'faculty'])
        ->default('university')
        ->after('subdomain');
});
```

**To run the migration:**

```bash
php artisan migrate
```

### Update Site Records

Update your existing site records to set the correct `site_type`:

```sql
-- Set main university site
UPDATE sites SET site_type = 'university' WHERE domain = 'localhost';

-- Set department sites
UPDATE sites SET site_type = 'department' WHERE domain LIKE '%.localhost' AND domain != 'localhost';

-- Faculty sites will be determined by authentication
```

## Backend Implementation

### 1. Site Model Update

The `Site` model has been updated to include `site_type` in fillable fields:

```php
protected $fillable = [
    'name', 'description', 'domain', 'subdomain',
    'site_type', // NEW
    'theme_id', 'theme_name', 'settings', 'is_active',
    'created_by', 'updated_by',
];
```

### 2. SubdomainMiddleware Enhancement

The middleware now determines and shares the `site_type` and `adminPrefix`:

```php
// Determine route prefix based on site type
$siteType = $site->site_type ?? 'university';
$adminPrefix = match ($siteType) {
    'department' => 'admin/department',
    'faculty' => 'admin/faculty',
    default => 'admin/university',
};

$request->merge([
    'siteType' => $siteType,
    'adminPrefix' => $adminPrefix,
]);
```

### 3. RouteHelper Class

A new helper class `App\Helpers\RouteHelper` provides methods for generating dynamic admin routes:

```php
// Get admin route prefix
RouteHelper::getAdminPrefix(); // Returns 'admin/university', 'admin/department', or 'admin/faculty'

// Generate admin route
RouteHelper::admin('dashboard'); // Returns '/admin/university/dashboard'

// Check if route is active
RouteHelper::isActive('dashboard'); // Returns true/false
```

## Frontend Implementation

### 1. Vue Composable: `useAdminRoutes()`

A new composable provides reactive route generation:

```typescript
import { useAdminRoutes } from '@/composables/useAdminRoutes';

const { siteType, adminPrefix, adminRoute, isActiveRoute } = useAdminRoutes();

// Usage examples:
adminRoute('dashboard'); // '/admin/university/dashboard'
adminRoute('teachers'); // '/admin/university/teachers'
adminRoute('teacher/profile/basic-info'); // '/admin/university/teacher/profile/basic-info'

siteType.value; // 'university', 'department', or 'faculty'
adminPrefix.value; // '/admin/university', '/admin/department', or '/admin/faculty'
```

### 2. Updated Components

#### SidebarArea.vue

The sidebar now uses dynamic routes:

```vue
<script setup>
import { useAdminRoutes } from '@/composables/useAdminRoutes';

const { adminRoute } = useAdminRoutes();

const menuItems = computed(() => [
    { label: 'Dashboard', route: adminRoute('dashboard') },
    { label: 'Teachers', route: adminRoute('teachers') },
    { label: 'Pages', route: adminRoute('pages') },
    // ... more items
]);
</script>
```

#### TeacherProfileLayout.vue

Teacher profile navigation now adapts to site type:

```vue
<script setup>
const { adminRoute, siteType } = useAdminRoutes();

const menuItems = computed(() => {
    const profilePrefix = siteType.value === 'faculty' ? 'teacher/profile' : 'teacher/profile';

    return [
        { name: 'Basic Info', path: adminRoute(`${profilePrefix}/basic-info`) },
        { name: 'About Me', path: adminRoute(`${profilePrefix}/about`) },
        // ... more items
    ];
});
</script>
```

## Route Examples

### University Site (localhost:8000)

| Page            | Old Route                           | New Route                                      |
| --------------- | ----------------------------------- | ---------------------------------------------- |
| Dashboard       | `/admin/dashboard`                  | `/admin/university/dashboard`                  |
| Teachers        | `/admin/teachers`                   | `/admin/university/teachers`                   |
| Teacher Profile | `/admin/teacher/profile/basic-info` | `/admin/university/teacher/profile/basic-info` |
| Pages           | `/admin/pages`                      | `/admin/university/pages`                      |

### Department Site (cse.localhost:8000)

| Page      | Old Route               | New Route                          |
| --------- | ----------------------- | ---------------------------------- |
| Dashboard | `/admin/dashboard`      | `/admin/department/dashboard`      |
| Teachers  | `/admin/teachers`       | `/admin/department/teachers`       |
| News      | `/admin/news-section`   | `/admin/department/news-section`   |
| Events    | `/admin/events-section` | `/admin/department/events-section` |

### Faculty Site (when logged in as teacher)

| Page         | Old Route                             | New Route                     |
| ------------ | ------------------------------------- | ----------------------------- |
| Profile      | `/admin/teacher/profile/basic-info`   | `/admin/faculty/basic-info`   |
| About        | `/admin/teacher/profile/about`        | `/admin/faculty/about`        |
| Publications | `/admin/teacher/profile/publications` | `/admin/faculty/publications` |

## Updating Existing Links

### Before

```vue
<a href="/admin/dashboard">Dashboard</a>
<Link href="/admin/teachers">Teachers</Link>
```

### After

```vue
<script setup>
import { useAdminRoutes } from '@/composables/useAdminRoutes';
const { adminRoute } = useAdminRoutes();
</script>

<template>
    <a :href="adminRoute('dashboard')">Dashboard</a>
    <Link :href="adminRoute('teachers')">Teachers</Link>
</template>
```

## Backend Route Structure (Future Enhancement)

Currently, routes in `web.php` use the old structure. To fully implement dynamic routing on the backend, you would create route groups:

```php
// University routes
Route::prefix('admin/university')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    // ... more routes
});

// Department routes
Route::prefix('admin/department')->group(function () {
    Route::get('/dashboard', [DepartmentDashboardController::class, 'index']);
    Route::get('/news-section', [NewsController::class, 'index']);
    // ... more routes
});

// Faculty routes
Route::prefix('admin/faculty')->group(function () {
    Route::get('/basic-info', [FacultyProfileController::class, 'basicInfo']);
    Route::get('/about', [FacultyProfileController::class, 'about']);
    // ... more routes
});
```

## Testing

### 1. Test University Site

- Visit: `http://localhost:8000/admin/university/dashboard`
- Check sidebar links use `/admin/university/*` prefix

### 2. Test Department Site

- Visit: `http://cse.localhost:8000/admin/department/dashboard`
- Check sidebar links use `/admin/department/*` prefix

### 3. Test Faculty Site

- Login as a teacher
- Visit teacher profile pages
- Check links use `/admin/faculty/*` prefix

## Configuration

### Setting Site Types via Database

```sql
-- Main university
UPDATE sites SET site_type = 'university' WHERE id = 1;

-- CSE Department
UPDATE sites SET site_type = 'department' WHERE subdomain = 'cse';

-- ICT Department
UPDATE sites SET site_type = 'department' WHERE subdomain = 'ict';
```

### Setting Site Types via Seed/Factory

```php
Site::create([
    'name' => 'Computer Science Department',
    'domain' => 'cse.localhost',
    'subdomain' => 'cse',
    'site_type' => 'department', // Set type here
    'is_active' => true,
]);
```

## Troubleshooting

### Routes Not Working

1. **Clear route cache:**

    ```bash
    php artisan route:clear
    php artisan cache:clear
    ```

2. **Check site_type in database:**

    ```sql
    SELECT id, name, domain, site_type FROM sites;
    ```

3. **Verify middleware is applied:**
    - Check `SubdomainMiddleware` is in the route group
    - Ensure `siteData` is passed to frontend

### Frontend Not Updating

1. **Rebuild frontend assets:**

    ```bash
    npm run build
    # or for dev
    npm run dev
    ```

2. **Check browser console** for any JavaScript errors

3. **Verify composable import:**
    ```typescript
    import { useAdminRoutes } from '@/composables/useAdminRoutes';
    ```

## Benefits

1. **Clearer URL Structure**: URLs clearly indicate the site type
2. **Better Organization**: Separates university, department, and faculty admin areas
3. **Scalability**: Easy to add new site types
4. **Security**: Can apply different middleware/permissions per site type
5. **SEO**: Better URL structure for public-facing pages

## Future Enhancements

1. **Role-Based Routing**: Automatically detect faculty role and use `/admin/faculty/*` routes
2. **Custom Route Patterns**: Allow sites to define custom route patterns
3. **Multi-Tenancy**: Complete isolation between different site types
4. **Permission System**: Different permissions for university/department/faculty admins

## Support

For issues or questions:

1. Check this documentation
2. Review the code in:
    - `app/Helpers/RouteHelper.php`
    - `app/Http/Middleware/SubdomainMiddleware.php`
    - `resources/js/composables/useAdminRoutes.ts`
3. Contact the development team
