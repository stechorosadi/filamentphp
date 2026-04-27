# Documentation

Operational and developer guide for the Filament CMS admin panel and public frontend.
For technical internals (schema, models, resources) see [ARCHITECTURE.md](ARCHITECTURE.md).

---

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Roles & Permissions](#roles--permissions)
5. [Admin Panel Features](#admin-panel-features)
6. [Public Frontend](#public-frontend)
7. [Extending the App](#extending-the-app)
8. [Deployment](#deployment)

---

## Requirements

| Requirement | Minimum Version |
|---|---|
| PHP | 8.3 |
| MySQL | 8.0 |
| Node.js | 18 |
| Composer | 2.x |

Required PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd`

---

## Installation

### 1. Clone and install dependencies

```bash
git clone <repo-url>
cd filamentphp
composer install
npm install
```

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database

```bash
php artisan migrate
php artisan db:seed   # optional: seeds roles and initial admin
```

### 4. Storage symlink

```bash
php artisan storage:link
```

### 5. Build frontend assets

```bash
npm run build        # production
npm run dev          # development with hot reload
```

### 6. Set up roles and permissions

```bash
php artisan shield:generate --all
php artisan shield:super-admin --user=1
```

---

## Configuration

### Key `.env` Variables

```env
APP_NAME="Your App Name"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

FILESYSTEM_DISK=public
```

### Panel URL

`app/Providers/Filament/AdminPanelProvider.php` → `->path('admin')`

### Navigation Menu

The public frontend navbar is managed via **Filament → Menu Builder → Header Menu - Top Right**. Add, remove, or reorder items there without touching code. Items support custom titles, URLs, and link targets (`_self` / `_blank`).

---

## Roles & Permissions

### Available Roles

| Role | Description |
|---|---|
| `super_admin` | Full access to all resources and features |
| `Content Manager` | Access limited to Content CRUD (12 permissions) |
| *(no role)* | Can log in and see the Dashboard only — no resource access |

`canAccessPanel()` returns `true` for all authenticated users. FilamentShield policies block resource access based on assigned permissions.

### Setting Up Shield

```bash
php artisan shield:generate --all
php artisan shield:super-admin --user={id}
```

### Assigning Roles

**Via admin panel:** Users → edit user → Roles field

**Via Tinker:**
```bash
php artisan tinker
App\Models\User::find(1)->assignRole('Content Manager');
```

### File Storage

Upload directories (all relative to `storage/app/public/`):

| Feature | Directory |
|---|---|
| User avatars | `avatars/` |
| Education certificates | `user-certificates/` |
| Publication files | `user-publications/` |
| Content header images | `content-headers/` |
| Content featured images | `content-featured/` |
| Category images | `content-categories/` |
| Classification images | `content-classifications/` |
| Content image attachments | `content-images/` |
| Content file attachments | `content-files/` |
| Parallax background images | `background/` |

> Files are automatically deleted from storage when the associated record is updated (field changed) or deleted.

---

## Admin Panel Features

### Users

**Location:** Admin panel → Users

**Creating a user:**
1. Click **New User** → fill name, email, roles, optional avatar
2. Set a password (min 8 characters)

**Changing a password:**
- **super_admin editing any user:** sees only New Password + Confirm — no old password required
- **Other roles editing their own profile:** must enter Current Password, New Password, Confirm
- Leave all blank to keep the existing password

**Avatar:** Auto-cropped to 200×200px (1:1). Click the avatar in the table to preview full size.

---

### Content

**Location:** Admin panel → Content Management → Contents

**Creating content:**
1. **New Content** → title (slug auto-generated as `YYYY-MM-DD-your-title`)
2. Write in the RichEditor, add excerpt, optional YouTube embed URL
3. Select classification, category, tags (can create tags inline)
4. Upload header image and/or featured image (auto-resized 1000×600px, 5:3 crop)
5. Set **Published** toggle (makes content visible on the frontend)
6. Set **Featured** toggle (pins to the homepage hero slider — requires a featured image)
7. Save — then manage image, file, and link attachments from the relation manager tabs

**Published / Featured toggles:**
- `Published` — content only appears on the frontend when this is ON
- `Featured` — content appears in the hero slider when ON AND a `featured_image` is uploaded
- Both can be toggled inline in the content table without opening the edit page

**Page Views:** Automatically tracked — each unique visitor session increments the counter once per article. Visible in the table and infolist.

---

### Categories

**Location:** Admin panel → Content Management → Categories

Each category has:
- **Name** — auto-generates a URL slug
- **Description** — short text describing the scope/topics (max 500 chars); shown on the public category page and homepage cards
- **Icon** — pick any Heroicon from the searchable dropdown (stored as `heroicon-o-name`)
- **Image** — PNG, 1:1 ratio, 100×100px, max 1MB; shown as a thumbnail in admin and on the homepage

---

### Classifications

**Location:** Admin panel → Content Management → Classifications

Each classification has:
- **Name** — auto-generates slug
- **Icon** — searchable Heroicon picker
- **Image** — PNG, 1:1, 100×100px, max 1MB

---

### Tags

**Location:** Admin panel → Content Management → Tags

Tags can be created here **or inline** when editing content. Each tag has name + auto-slug. The table shows how many articles use each tag.

---

### Menu Builder

**Location:** Admin panel → Menu Builder

Manages the public frontend navigation. The navbar reads from the menu named **"Header Menu - Top Right"**.

To update the navbar:
1. Open **Header Menu - Top Right**
2. Add, edit, or reorder menu items
3. Save — changes appear immediately on the frontend (no rebuild needed)

Each menu item supports: Title, URL (relative path or full URL), Link Target (`_self` / `_blank`), Icon.

---

### Education History & Publications

**Location:** Users → Edit/View → tabs

_(See original documentation — these features are unchanged.)_

---

### Image / File / Link Attachments

**Location:** Contents → Edit/View → tabs

_(See original documentation — these features are unchanged.)_

---

## Public Frontend

### Homepage (`/`)

| Section | Description |
|---|---|
| **Hero Slider** | Up to 5 featured+published articles; auto-advances every 6s; Preview modal shows excerpt |
| **Search** | Prominent search bar with total article/category/classification counts; redirects to `/search` |
| **Browse by Category** | 4-col grid; first category is "featured" spanning 2 cols; shows icon, description, article count |
| **Latest Content** | Paginated grid (9/page) with search + filter support; parallax background image |
| **Classifications** | Dark warm section; 4-col horizontal cards with icon + image thumbnail |
| **Most Popular** | Featured #1 full-bleed card + ranked list #2–5 sorted by views |

### Search Page (`/search?q=`)

- Searches: title, excerpt, category name, classification name, tag names
- Keyword highlighted in yellow in result titles
- Empty state shows 6 random categories as suggestions
- Paginated (12/page)

### Category Page (`/categories/{slug}`)

- Header: category image, name, description, article count
- 3-col paginated content grid (9/page)
- Other categories shown at the bottom for exploration

### Classification Page (`/classifications/{slug}`)

- Header: classification image, name, article count
- 3-col paginated content grid (9/page)
- Other classifications at the bottom

### Article Detail (`/articles/{slug}`)

- Breadcrumb → title → tag/category badges → header image → two-column layout
- Left sidebar (sticky on desktop): author avatar, reading time (auto-calculated), views, published date
- Right: excerpt, rich content, YouTube embed, gallery, downloads, related links
- Related articles (same category/classification) at the bottom

### Error Pages

Custom error pages for 404, 403, 500, 503, 419, 429:
- Match site colour palette with dark/light mode support
- Giant watermark error code, friendly icon, title, and human-readable description
- **15-second SVG countdown ring** auto-redirects to homepage
- Buttons: Go to Homepage, Go Back (when referrer exists), Search
- Standalone — no database queries, safe for 500/503 scenarios

---

## Extending the App

### Adding a New Filament Resource

```bash
php artisan make:model MyModel -m
php artisan make:filament-resource MyModel --generate
php artisan shield:generate --all   # generate permissions for the new resource
```

Set navigation group and icon in the resource class, then assign permissions to roles via the Shield panel.

### Adding a New Upload Field with Auto-Cleanup

Follow the pattern in `ContentCategory::booted()`:

```php
static::updating(function (MyModel $record): void {
    if ($record->isDirty('file_path') && $record->getOriginal('file_path')) {
        Storage::disk('public')->delete($record->getOriginal('file_path'));
    }
});
static::deleting(function (MyModel $record): void {
    if ($record->file_path) {
        Storage::disk('public')->delete($record->file_path);
    }
});
```

### Adding a New Frontend Route

1. Add route to `routes/web.php`
2. Add controller method to `HomeController`
3. Create view in `resources/views/` extending `layouts.front`
4. Wire any navbar links via Menu Builder

### Adding a New Error Page

Create `resources/views/errors/{code}.blade.php` extending `errors.layout`:

```blade
@extends('errors.layout')
@section('error_code', '422')
@section('error_title', 'Unprocessable Content')
@section('error_description', 'The submitted data was invalid.')
@section('error_icon')
    <svg ...>...</svg>
@endsection
```

---

## Deployment

### Pre-deployment checklist

```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link
php artisan shield:generate --all   # if new resources were added
```

### Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### File permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```
