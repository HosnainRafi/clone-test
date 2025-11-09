# Site Controller Migration

## Overview
This document describes the migration of site management functionality from route closures to a dedicated controller.

## Files Changed

### 1. Created: `app/Http/Controllers/Admin/SiteController.php`
- **Purpose**: Centralized controller for all site management operations
- **Structure**: Handles both web views and API endpoints
- **Key Features**:
  - Full CRUD operations for sites
  - JSON validation for menuItems
  - Theme management
  - Domain validation
  - Status toggling
  - Search and pagination support

### 2. Updated: `routes/admin.php`
- **Before**: All site management logic in route closures
- **After**: Clean route definitions pointing to controller methods
- **Benefits**: 
  - Improved maintainability
  - Better code organization
  - Easier testing
  - Cleaner route file

## Controller Methods

### Web Interface Methods
- `index()` - List sites with pagination and filtering
- `create()` - Show create form
- `store()` - Store new site
- `show()` - Display site details
- `edit()` - Show edit form
- `update()` - Update existing site
- `destroy()` - Delete site

### API Methods
- `apiIndex()` - API endpoint for listing sites
- `apiShow()` - API endpoint for single site
- `apiStore()` - API endpoint for creating site
- `apiUpdate()` - API endpoint for updating site
- `apiDestroy()` - API endpoint for deleting site

### Utility Methods
- `getThemes()` - Get available themes
- `validateDomain()` - Check domain uniqueness
- `toggleStatus()` - Toggle site active status
- `validateMenuItems()` - Validate JSON menu structure

## Route Structure

### Web Routes (with Inertia.js)
```php
Route::prefix('sites')->name('sites.')->group(function () {
    Route::get('/', [SiteController::class, 'index'])->name('index');
    Route::get('/create', [SiteController::class, 'create'])->name('create');
    Route::post('/', [SiteController::class, 'store'])->name('store');
    Route::get('/{id}', [SiteController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SiteController::class, 'edit'])->name('edit');
    Route::put('/{id}', [SiteController::class, 'update'])->name('update');
    Route::delete('/{id}', [SiteController::class, 'destroy'])->name('destroy');
});
```

### API Routes (JSON responses)
```php
Route::prefix('api/admin/sites')->group(function () {
    Route::get('/', [SiteController::class, 'apiIndex']);
    Route::get('/{id}', [SiteController::class, 'apiShow']);
    Route::post('/', [SiteController::class, 'apiStore']);
    Route::put('/{id}', [SiteController::class, 'apiUpdate']);
    Route::delete('/{id}', [SiteController::class, 'apiDestroy']);
    // ... utility endpoints
});
```

## Benefits of This Migration

1. **Maintainability**: Code is now organized in a dedicated controller
2. **Testability**: Controller methods can be easily unit tested
3. **Reusability**: Methods can be called from other parts of the application
4. **Code Organization**: Related functionality is grouped together
5. **Type Safety**: Better IDE support and type checking
6. **Documentation**: Methods are properly documented with PHPDoc

## Frontend Integration

The Vue.js components in `resources/js/pages/Site/` are designed to work with these controller endpoints:

- `Site/Index.vue` → `SiteController@index`
- `Site/Create.vue` → `SiteController@create` & `SiteController@store`
- `Site/Edit.vue` → `SiteController@edit` & `SiteController@update`
- `Site/Show.vue` → `SiteController@show`

## Data Flow

1. **Web Interface**: Routes → Controller → Inertia Response → Vue Components
2. **API Interface**: Routes → Controller → JSON Response
3. **JSON Validation**: Frontend → API endpoints → Validation → Response

This architecture provides a clean separation of concerns and makes the codebase more professional and maintainable.