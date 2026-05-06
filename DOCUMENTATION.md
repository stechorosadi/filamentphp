# Documentation

Operational and developer guide for the Filament CMS admin panel and public frontend.
For technical internals (schema, models, resources) see [ARCHITECTURE.md](ARCHITECTURE.md).

---

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Dual-Language Support](#dual-language-support)
5. [Roles & Permissions](#roles--permissions)
6. [Admin Panel Features](#admin-panel-features)
7. [Public Frontend](#public-frontend)
8. [Extending the App](#extending-the-app)
9. [Deployment](#deployment)

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
mkdir -p storage/app/fonts   # required for dompdf font cache
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

`app/Providers/Filament/AdminPanelProvider.php` → `->path('arsiparis')`

> **Note:** User self-registration is disabled. New users must be created by a `super_admin` via **Admin panel → Users → New User**.

### Navigation Menu

The public frontend navbar is managed via **Filament → Menu Builder → Header Menu - Top Right**. Add, remove, or reorder items there without touching code.

### Site Settings

**Admin panel → Settings → Site Settings** (super_admin only):
- **Identity**: site title, tagline, meta description
- **Branding**: logo (auto-resized to 128px height), favicon (auto-resized to 32×32px)
- **Social Media**: Facebook, Instagram, X, YouTube URLs
- **Contact Info**: email, address (displayed in the top bar)
- **Theme Colors**: accent, background, and text colors for light/dark modes

---

## Dual-Language Support

The site serves content in **English (EN)** and **Indonesian (ID)**. All frontend URLs are prefixed with the locale:

- `/id/` — Indonesian (default; root `/` redirects here)
- `/en/` — English

### How translations work

- Models using `spatie/laravel-translatable` store text fields as JSON: `{"en": "...", "id": "..."}`.
- Translatable models: `Content` (`title`, `excerpt`, `content`), `ContentCategory` (`name`, `description`), `ContentClassification` (`name`), `Tag` (`name`), `TeamMember` (name/position fields), `SiteSetting` (text/contact fields).
- In Filament forms, each translatable field shows a tab for EN and ID.
- In views, use `$model->getTranslation('field', app()->getLocale())` or rely on the `HasTranslations` magic getter (which uses the current app locale automatically).

### Adding translated content

When creating or editing any content, categories, classifications, or tags in the admin panel, fill in both the **EN** and **ID** tabs for each text field. Leaving a locale empty will display a blank value for visitors using that language.

### Language toggle

The frontend navbar has an **EN / ID** toggle button. It reconstructs the current URL with the opposite locale, keeping the user on the same page. All internal links use the `lroute()` helper to preserve the active locale automatically.

### Translation files

Static UI strings (labels, buttons, error messages) are in:
- `lang/en/ui.php` — English
- `lang/id/ui.php` — Indonesian

---

## Roles & Permissions

| Role | Description |
|---|---|
| `super_admin` | Full access to all resources and features |
| `Content Manager` | Access limited to Content CRUD |
| *(no role)* | Can log in and see the Dashboard only |

### Setting Up Shield

```bash
php artisan shield:generate --all
php artisan shield:super-admin --user={id}
```

### Assigning Roles

**Via admin panel:** Users → edit user → Roles field

---

## Admin Panel Features

### Dashboard

The dashboard shows four live stat cards:

| Card | Shows |
|---|---|
| **Articles** | Total count + published progress bar + featured/archived/draft pills |
| **Total Views** | Sum of all article views + avg per published article |
| **Taxonomy** | Combined categories + classifications, split into two sub-boxes |
| **Team Members** | Total count + visible progress bar + hidden count |

---

### Users

**Location:** Admin panel → Users

**Creating a user:**
1. Click **New User** → fill name, email, roles, optional avatar
2. Set a password (min 8 characters)

**Changing a password:**
- **super_admin:** sees only New Password + Confirm — no old password required
- **Other roles:** must enter Current Password, New Password, Confirm
- Leave all password fields blank to keep the existing password

**Avatar:** Auto-cropped to 200×200px. Click the avatar in the table to preview full size.

**User profile tabs (Education, Work Experience, Certifications, Publications):**

Each tab is a relation manager with drag-to-reorder support.

| Tab | Fields |
|---|---|
| Education History | Institution, Degree, Field of Study, Period (start–end year), GPA, Description, Certificate upload |
| Work Experience | Company, Job Title, Department, Period, Description |
| Certifications | Title, Issuing Organization, Category (Training/Seminar/Workshop/Professional/Online Course), Year, Description, Certificate upload |
| Publications | Title, Type (Book/Journal/Research/Conference/Other), Publisher, Year, ISBN, DOI, URL, Abstract, File upload |

Certificate and file image uploads are auto-resized to 1000px width (PDFs are uploaded as-is).

---

### Content

**Location:** Admin panel → Content Management → Contents

**Creating content:**
1. **New Content** → title (slug auto-generated as `YYYY-MM-DD-your-title`); fill both EN and ID tabs
2. Write in the RichEditor, add excerpt, optional YouTube embed URL
3. Set **Article Date** — used for display ordering across all frontend sections
4. Select classification, category, tags (can create tags inline)
5. Upload header image and/or featured image
6. Set status toggles (see below)
7. Save — then manage image, file, and link attachments from the relation manager tabs

**Status toggles:**

| Toggle | Effect |
|---|---|
| **Published** | Content only appears on the frontend when ON |
| **Featured** | Content appears in the homepage hero slider when ON + featured_image uploaded |
| **Archived** | Content is hidden from hero, latest, and popular sections but still public; appears on `/archive` page and in search results with an "Archived" badge |

All three toggles can be flipped inline in the content table without opening the edit page.

**Page Views:** Automatically tracked — each unique visitor session increments the counter once per article.

---

### Categories

**Location:** Admin panel → Content Management → Categories

- **Name** — auto-generates a URL slug
- **Description** — max 500 chars; shown on category page and homepage cards
- **Icon** — any Heroicon from the searchable dropdown
- **Image** — PNG, 1:1 ratio, auto-resized to 100×100px

---

### Classifications

**Location:** Admin panel → Content Management → Classifications

- **Name**, **Icon**, **Image** (same as categories, no description field)

---

### Tags

Tags can be created here **or inline** when editing content. The table shows how many articles use each tag.

---

### Team Members

**Location:** Admin panel → Team Members

- **Photo** — click photo thumbnail in the table to preview full size
- **Full name** is composed of: `Front Title + User Name + Back Title`
- **Sort Order** — drag rows to reorder the public team page display
- **Visible** — hidden members do not appear on the public team page

---

### Menu Builder

**Location:** Admin panel → Menu Builder

Manages the public frontend navigation. The navbar reads from **"Header Menu - Top Right"**.

To update the navbar:
1. Open **Header Menu - Top Right** → add, edit, or reorder menu items → Save
2. Changes appear immediately (no rebuild needed)

---

## Public Frontend

### Homepage (`/{locale}/`)

| Section | Description |
|---|---|
| **Hero Slider** | Up to 5 featured+published+non-archived articles ordered by `article_date`; auto-advances every 11s; **Watch Video** button appears only when the slide has a YouTube URL — opens a video modal |
| **Search** | Search bar with article/category/classification counts; redirects to `/{locale}/search` |
| **Browse by Category** | 4-col grid; first category spans 2 cols |
| **Latest Content** | Paginated grid (9/page); excludes archived content; ordered by `article_date` |
| **Classifications** | Dark section; horizontal cards |
| **Most Popular** | Top 5 by views (non-archived); featured card + ranked list |

---

### Search Page (`/{locale}/search?q=`)

- Searches: title, excerpt, category name, classification name, tag names — **locale-aware** (searches the active language's text)
- **Includes archived content** — shown with "Archived" badge
- Paginated (12/page)

---

### Archive Page (`/{locale}/archive`)

Dedicated page for all published+archived content:
- Shows articles marked as **Archived** that are excluded from the homepage
- Each card has the "Archived" badge permanently visible
- Paginated (12/page)
- Accessible via direct URL — link from your navigation if needed

---

### Sitemap (`/{locale}/sitemap` and `/sitemap.xml`)

- **`/sitemap.xml`** — machine-readable XML sitemap for search engines; includes all published non-archived articles, categories, classifications, active tags, visible team members
- **`/{locale}/sitemap`** — human-readable HTML sitemap with the same links, localised labels

---

### Category Page (`/{locale}/categories/{slug}`)

- 3-col paginated content grid (9/page); ordered by `article_date`

---

### Classification Page (`/{locale}/classifications/{slug}`)

- 3-col paginated content grid (9/page); ordered by `article_date`

---

### Tag Page (`/{locale}/tags/{slug}`)

- Paginated grid (12/page) of all published content with that tag; ordered by `article_date`
- Sidebar shows up to 12 other tags
- Tags are clickable pills on the article detail page

---

### Article Detail (`/{locale}/articles/{slug}`)

- Breadcrumb → title → classification/category/archived badges → header image
- **Left sidebar** (sticky on desktop): author, reading time, views, published date, **social share buttons** (Twitter/X, Facebook, WhatsApp, Copy Link), **Export to PDF** button
- **Right**: excerpt, rich content, YouTube embed, **clickable tag pills** (link to `/{locale}/tags/{slug}`), image gallery (masonry 2-col), file downloads, related links
- **Related articles** at the bottom — non-archived content from same category/classification; ordered by `article_date`

**Export to PDF:** Downloads a formatted A4 PDF with logo, header image, content, YouTube thumbnail (if present), 3-col image gallery, file list, and link list.

**Social share buttons:**
- **Twitter/X**, **Facebook**, **WhatsApp** — open platform share dialogs in new tab
- **Copy Link** — copies URL to clipboard with 2-second "Copied!" feedback

---

### Team Page (`/team`)

- Lists all visible team members
- Each card is **clickable** — navigates to the member detail page

---

### Team Member Detail (`/team/{id}`)

| Section | Description |
|---|---|
| **Hero** | Photo, full name with titles, position, employee number, social links |
| **Education & Experience** | Two-column timeline (side by side on desktop) with year badges, institution/company, description |
| **Certificates** | 2-col grid with category badge, issuer, year |
| **Publications** | List with type + year badges, publisher, abstract, View link |
| **Bottom bar** | ← Back to Team Members (left) | Export to PDF (right) |

**Export to PDF:** Downloads an A4 PDF of the member's full profile including all sections.

---

### Error Pages

Custom error pages for 404, 403, 500, 503, 419, 429:
- Match site colour palette with dark/light mode support
- **Dual-language** — error title and description shown in both English and Indonesian
- **15-second SVG countdown ring** auto-redirects to homepage
- Standalone — no database queries (safe for 500/503)

---

## Extending the App

### Adding a New Filament Resource

```bash
php artisan make:model MyModel -m
php artisan make:filament-resource MyModel --generate
php artisan shield:generate --all
```

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

> **Important:** If child records are deleted via DB-level `cascadeOnDelete()`, their Eloquent `deleting` hooks will NOT fire. Add file cleanup to the parent model's `deleting` hook instead (see `Content::booted()` for the pattern).

### Adding a New Frontend Route

1. Add route inside the `/{locale}` prefix group in `routes/web.php`
2. Accept `string $locale` as the first parameter in the controller method
3. Use `lroute()` instead of `route()` for all internal links in the view
4. Create view in `resources/views/` extending `layouts.front`
5. Wire navbar links via Menu Builder

### Adding a New Error Page

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
mkdir -p storage/app/fonts        # dompdf font cache
php artisan shield:generate --all  # if new resources were added
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

### Troubleshooting: PDF export 500 error

If PDF export returns 500 on the live server:
1. Ensure `barryvdh/laravel-dompdf` is in `bootstrap/providers.php` (already committed — bypasses auto-discovery)
2. Ensure `storage/app/fonts/` directory exists and is writable
3. Run `php artisan config:cache` to pick up `config/dompdf.php`
