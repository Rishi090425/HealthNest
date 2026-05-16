# Domain Routing Guide

This guide explains all the domain routing patterns implemented in your Laravel application.

## Setup: Add to Windows Hosts File

Edit `C:\Windows\System32\drivers\etc\hosts` and add:

```
127.0.0.1 localhost
127.0.0.1 admin.localhost
127.0.0.1 api.localhost
127.0.0.1 blog.localhost
127.0.0.1 support.localhost
127.0.0.1 docs.localhost
127.0.0.1 company1.localhost
127.0.0.1 company2.localhost
127.0.0.1 v1.company1.localhost
127.0.0.1 v2.company1.localhost
```

## Available Domains & Routes

### 1. Main Domain (localhost:8000)
- `http://localhost:8000/` - Welcome page
- `http://localhost:8000/dashboard` - User dashboard (requires auth)
- `http://localhost:8000/register` - Registration
- `http://localhost:8000/login` - Login

### 2. Admin Subdomain (Authenticated)
- `http://admin.localhost:8000/` - Admin Panel
- `http://admin.localhost:8000/dashboard` - Admin Dashboard
- `http://admin.localhost:8000/users` - User Management (requires auth)
- `http://admin.localhost:8000/settings` - System Settings (requires auth)
- `http://admin.localhost:8000/analytics` - Analytics Dashboard (requires auth)

### 3. API Subdomain (Multi-version)
- `http://api.localhost:8000/v1/users` - Get users list
- `http://api.localhost:8000/v1/posts` - Get posts list

### 4. Blog Subdomain
- `http://blog.localhost:8000/` - Blog Home
- `http://blog.localhost:8000/posts` - All posts
- `http://blog.localhost:8000/posts/my-first-post` - Single post
- `http://blog.localhost:8000/author/john-doe` - Posts by author

### 5. Support Subdomain
- `http://support.localhost:8000/` - Support Center
- `http://support.localhost:8000/faq` - FAQ
- `http://support.localhost:8000/tickets` - Support tickets (requires auth)

### 6. Documentation Subdomain
- `http://docs.localhost:8000/` - Documentation Home
- `http://docs.localhost:8000/guide/installation` - Guide section
- `http://docs.localhost:8000/api/v1` - API documentation

### 7. Multi-Tenant Routes (Dynamic Subdomains)
- `http://company1.localhost:8000/` - Account: company1
- `http://company2.localhost:8000/` - Account: company2
- `http://company1.localhost:8000/dashboard` - Dashboard for company1

### 8. Advanced Multi-Tenant with Versioning
- `http://v1.company1.localhost:8000/` - API v1 for Account: company1
- `http://v2.company1.localhost:8000/` - API v2 for Account: company1

## Key Concepts

### Simple Subdomain
```php
Route::domain('admin.localhost')->group(function () {
    Route::get('/', function () {
        return 'Admin Panel';
    });
});
```

### Subdomain with Parameter
```php
Route::domain('{account}.localhost')->group(function () {
    Route::get('/', function ($account) {
        return "Account: {$account}";
    });
});
```

### Subdomain with Middleware
```php
Route::domain('admin.localhost')->middleware(['auth'])->group(function () {
    Route::get('/users', function () {
        return 'Protected route';
    });
});
```

### Nested Parameters (Version + Account)
```php
Route::domain('v{version}.{account}.localhost')->group(function () {
    Route::get('/', function ($version, $account) {
        return "v{$version} for {$account}";
    });
});
```

## Production Setup

For production, replace `localhost` with your actual domain:

```php
// Development
Route::domain('admin.localhost')->group(function () { ... });

// Production
Route::domain('admin.example.com')->group(function () { ... });
```

You can use environment variables:
```php
Route::domain('admin.'.env('APP_DOMAIN'))->group(function () { ... });
```

## Testing Domain Routes

Use cURL or your browser:
```bash
curl http://admin.localhost:8000/dashboard
curl http://blog.localhost:8000/posts
curl http://company1.localhost:8000/
```

Or navigate directly in your browser to any of the URLs listed above.
