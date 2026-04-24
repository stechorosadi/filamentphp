# Documentation

Operational and developer guide for the Filament CMS admin panel.
For technical internals (schema, models, resources) see [ARCHITECTURE.md](ARCHITECTURE.md).

---

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Roles & Permissions](#roles--permissions)
5. [Features Guide](#features-guide)
6. [Extending the App](#extending-the-app)
7. [Deployment](#deployment)

---

## Requirements

| Requirement | Minimum Version |
|---|---|
| PHP | 8.3 |
| MySQL | 8.0 |
| Node.js | 18 |
| Composer | 2.x |

Required PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` (for image resizing)

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

Edit `.env` with your database and app settings (see [Configuration](#configuration)).

### 3. Database

```bash
php artisan migrate
php artisan db:seed   # optional: seed roles and initial admin user
```

### 4. Storage symlink

```bash
php artisan storage:link
```

This symlinks `storage/app/public/` to `public/storage/` so uploaded files are web-accessible.

### 5. Build frontend assets

```bash
npm run build        # production
npm run dev          # development with hot reload
```

### 6. Set up roles and permissions

```bash
php artisan shield:generate --all
php artisan shield:super-admin --user=1   # make user ID 1 a super admin
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

The admin panel is accessible at `/admin`. To change this, update the `->path()` value in:
`app/Providers/Filament/AdminPanelProvider.php`

```php
->path('admin')   // change 'admin' to your preferred path
```

### File Storage

All uploads use Laravel's `public` disk. Files are stored under `storage/app/public/` and served from `public/storage/` via the symlink.

Upload directories (all relative to `storage/app/public/`):

| Feature | Directory |
|---|---|
| User avatars | `avatars/` |
| Education certificates | `user-certificates/` |
| Publication files | `user-publications/` |
| Content header images | `content-headers/` |
| Content featured images | `content-featured/` |
| Content image attachments | `content-images/` |
| Content file attachments | `content-files/` |

> Files are automatically deleted from storage when the associated record is updated (field changed) or deleted.

---

## Roles & Permissions

### Available Roles

| Role | Description |
|---|---|
| `super_admin` | Full access to everything, bypasses all permission checks |
| `admin` | Access to panel, subject to Shield permission policies |

Both roles can log into the admin panel. Access is controlled in `User::canAccessPanel()`.

### Setting Up Shield

After running migrations, generate permissions for all resources:

```bash
php artisan shield:generate --all
```

This creates permission records for every Filament resource (e.g. `view_user`, `create_content`, `delete_content_category`, etc.).

### Assigning Roles

**Via the admin panel:**
1. Go to **Users** → edit a user
2. Select one or more roles in the **Roles** field

**Via Artisan:**
```bash
php artisan shield:super-admin --user={id}
```

**Via Tinker:**
```bash
php artisan tinker
$user = App\Models\User::find(1);
$user->assignRole('admin');
```

---

## Features Guide

### Users

**Location:** Admin panel → Users

Each user has a profile with avatar, name, email, and roles. Users can also have **Education History** and **Publications** entries managed via relation manager tabs on the edit/view page.

**Creating a user:**
1. Click **New User**
2. Fill in name, email, roles, and upload an avatar (optional)
3. Set a password — minimum 8 characters
4. Save

**Changing a password (edit page):**
- Enter the current password, new password, and confirmation
- Leave all three blank to keep the existing password

**Avatar:** Uploaded images are auto-cropped to 200×200px (1:1 ratio). Displayed as a circle in the table — click to preview full size.

---

### Education History

**Location:** Users → Edit/View → Education History tab

Tracks a user's academic background.

| Field | Notes |
|---|---|
| Institution | Required — school or university name |
| Degree / Qualification | e.g. Bachelor of Science, Diploma |
| Field of Study | e.g. Computer Science |
| Start Year | 4-digit year, required |
| End Year | 4-digit year, leave blank if ongoing |
| GPA / Grade | Free text, e.g. "3.8/4.0" or "Distinction" |
| Description | Optional notes or achievements |
| Certificate | Upload PDF or image (max 2MB) |

Entries can be **dragged to reorder** using the handle on the left of each row.

---

### Publications

**Location:** Users → Edit/View → Publications tab

Tracks books, journal articles, research papers, and conference papers.

| Field | Notes |
|---|---|
| Title | Required |
| Type | Book, Journal Article, Research Paper, Conference Paper, Other |
| Publisher / Journal | Name of publisher or journal |
| Year Published | 4-digit year |
| ISBN | Optional identifier |
| DOI | Optional digital object identifier |
| URL | Optional link to publication |
| Description / Abstract | Optional summary |
| File / Cover | Upload PDF or image (max 5MB) |

Publication type is displayed as a **color-coded badge** in the table.
Entries can be **dragged to reorder**.

---

### Content

**Location:** Admin panel → Content Management → Contents

Rich content with images, classifications, tags, and media attachments.

**Creating content:**
1. Click **New Content**
2. Fill in title — the slug is auto-generated as `YYYY-MM-DD-your-title`
3. Write content in the RichEditor (supports bold, italic, lists, links, images)
4. Optionally add excerpt, YouTube embed URL, classification, category, and tags
5. Upload a header image and/or featured image (auto-resized to 1000×600px, 5:3 ratio)
6. Save — then manage image, file, and link attachments from the tabs below

**Header / Featured Images:** Uploaded with an image editor for cropping. Auto-resized to 1000×600px cover mode.

**Content display:** On the view page, the `content` field is rendered as HTML with full prose typography styling (headings, lists, code blocks, links).

---

### Image Attachments

**Location:** Contents → Edit/View → Image Attachments tab

Upload images linked to a content item.

- Accepted: JPEG, PNG (max 1MB each)
- Images auto-resize to 1000px wide (contain mode, no upscaling)
- Built-in **image editor** for cropping/rotating before save
- Add an optional **caption** per image
- Click any image thumbnail to **preview full size** in a modal
- Drag rows to **reorder**

---

### File Attachments

**Location:** Contents → Edit/View → File Attachments tab

Upload documents linked to a content item.

- Accepted: PDF, Word (`.doc`, `.docx`), Excel (`.xls`, `.xlsx`), PowerPoint (`.ppt`, `.pptx`) — max 2MB
- Set a **Display Name** shown in the table (separate from the filename)
- File type (extension) is shown as a badge in the table

---

### Link Attachments

**Location:** Contents → Edit/View → Link Attachments tab

Add external URLs linked to a content item.

- Enter a **URL** (validated as a valid URL)
- Optionally add a **Label** (display text)
- URLs are shown truncated with a **copy** button in the table

---

### Categories

**Location:** Admin panel → Content Management → Categories

Simple name + auto-generated slug. Used to group content broadly.

---

### Classifications

**Location:** Admin panel → Content Management → Classifications

Simple name + auto-generated slug. Used to classify content by type or theme.

---

### Tags

Tags are created **inline** when editing content via the Tags field — no separate management page is needed. Type a new tag name and select "Create" from the dropdown.

---

## Extending the App

### Adding a New Filament Resource

1. Create the model and migration:
```bash
php artisan make:model MyModel -m
```

2. Create the Filament resource:
```bash
php artisan make:filament-resource MyModel --generate
```

3. Customize `form()`, `table()`, and `infolist()` in the generated resource file.

4. Set navigation group and icon in the resource class:
```php
protected static ?string $navigationGroup = 'Content Management';
protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document';
```

---

### Adding a New Relation Manager

Follow the pattern in any existing relation manager (e.g. `ImageAttachmentsRelationManager`):

1. Create the manager file in `app/Filament/Resources/{Resource}/RelationManagers/`
2. Set `protected static string $relationship` to match the Eloquent method name on the parent model
3. Define `form()` and `table()` methods
4. Register it in the parent resource's `getRelations()`:

```php
public static function getRelations(): array
{
    return [
        RelationManagers\YourRelationManager::class,
    ];
}
```

5. Add the corresponding `HasMany` relationship on the parent model.

---

### Adding a New Upload Field with Auto-Cleanup

1. Add the column to your migration:
```php
$table->string('file_path')->nullable();
```

2. Add auto-cleanup to the model's `booted()` method (same pattern as all existing models):
```php
protected static function booted(): void
{
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
}
```

3. Add a `FileUpload` component in the form:
```php
FileUpload::make('file_path')
    ->disk('public')
    ->directory('your-directory')
    ->maxSize(2048),
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
```

### Environment

Set these in your production `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### File permissions

Ensure the web server can write to:
- `storage/`
- `bootstrap/cache/`

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Running migrations in production

Always back up the database before migrating:

```bash
php artisan migrate --force
```

### Queue (if applicable)

If you add queued jobs or notifications, run a queue worker:

```bash
php artisan queue:work --daemon
```

Use a process manager (Supervisor) to keep it running.
