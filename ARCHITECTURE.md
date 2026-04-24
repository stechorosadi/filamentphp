# Architecture

Laravel 13 + Filament 5 admin panel (CMS + user profile management).
Panel path: `/admin` — accessible to users with `super_admin` or `admin` roles.

---

## Tech Stack

| Layer | Package | Version |
|---|---|---|
| Framework | laravel/framework | ^13.0 |
| Admin Panel | filament/filament | ^5.0 |
| Permissions | spatie/laravel-permission | ^7.3 |
| Shield (RBAC) | bezhansalleh/filament-shield | ^4.2 |
| Menu Builder | datlechin/filament-menu-builder | ^1.0 |
| CSS | tailwindcss | ^4.0 |
| Build Tool | vite | ^8.0 |

---

## Database Schema

### `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| email | varchar | unique |
| email_verified_at | timestamp | nullable |
| password | varchar | hashed |
| avatar_url | varchar | nullable, public disk `avatars/` |
| remember_token | varchar | nullable |
| timestamps | | |

### `user_educations`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | cascadeOnDelete |
| institution | varchar | required |
| degree | varchar | nullable |
| field_of_study | varchar | nullable |
| start_year | smallint unsigned | required |
| end_year | smallint unsigned | nullable (null = Present) |
| gpa | varchar(20) | nullable |
| description | text | nullable |
| certificate_path | varchar | nullable, public disk `user-certificates/` |
| order | int unsigned | default 0, drag-to-reorder |
| timestamps | | |

### `user_publications`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | cascadeOnDelete |
| title | varchar | required |
| type | varchar | book / journal_article / research_paper / conference_paper / other |
| publisher | varchar | nullable |
| year | smallint unsigned | nullable |
| isbn | varchar(30) | nullable |
| doi | varchar | nullable |
| url | varchar | nullable |
| description | text | nullable |
| file_path | varchar | nullable, public disk `user-publications/` |
| order | int unsigned | default 0, drag-to-reorder |
| timestamps | | |

### `contents`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | cascadeOnDelete |
| title | varchar(100) | required |
| slug | varchar | unique |
| content_classification_id | FK → content_classifications | nullable, nullOnDelete |
| content_category_id | FK → content_categories | nullable, nullOnDelete |
| header_image | varchar | nullable, public disk `content-headers/` |
| featured_image | varchar | nullable, public disk `content-featured/` |
| excerpt | text | nullable |
| content | longText | required |
| youtube_url | varchar | nullable |
| timestamps | | |

### `content_categories`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | required |
| slug | varchar | unique, auto-generated on create |
| timestamps | | |

### `content_classifications`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | required |
| slug | varchar | unique, auto-generated on create |
| timestamps | | |

### `tags`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | required |
| slug | varchar | unique, auto-generated on create |
| timestamps | | |

### `content_tag` (pivot)
| Column | Type | Notes |
|---|---|---|
| content_id | FK → contents | cascadeOnDelete |
| tag_id | FK → tags | cascadeOnDelete |

### `content_images`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| content_id | FK → contents | cascadeOnDelete |
| path | varchar | public disk `content-images/` |
| caption | varchar | nullable |
| order | int unsigned | default 0, drag-to-reorder |
| timestamps | | |

### `content_files`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| content_id | FK → contents | cascadeOnDelete |
| path | varchar | public disk `content-files/` |
| original_name | varchar | display name |
| order | int unsigned | default 0 |
| timestamps | | |

### `content_links`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| content_id | FK → contents | cascadeOnDelete |
| url | varchar(2048) | required |
| label | varchar | nullable |
| order | int unsigned | default 0 |
| timestamps | | |

---

## Models

### `User` — `app/Models/User.php`
- Implements `FilamentUser`, `HasAvatar`
- Traits: `HasFactory`, `Notifiable`, `HasRoles` (Spatie)
- Panel access: requires `super_admin` or `admin` role
- Auto-deletes `avatar_url` from storage on update/delete
- **Relationships**: `educationHistory()` HasMany UserEducation, `publications()` HasMany UserPublication

### `UserEducation` — `app/Models/UserEducation.php`
- Table: `user_educations` (explicit `$table` set)
- Auto-deletes `certificate_path` from storage on update/delete
- **Relationships**: `user()` BelongsTo User

### `UserPublication` — `app/Models/UserPublication.php`
- Table: `user_publications` (explicit `$table` set)
- Auto-deletes `file_path` from storage on update/delete
- **Relationships**: `user()` BelongsTo User

### `Content` — `app/Models/Content.php`
- Auto-deletes `header_image` and `featured_image` from storage on update/delete
- **Relationships**: `user()` BelongsTo, `classification()` BelongsTo ContentClassification, `category()` BelongsTo ContentCategory, `tags()` BelongsToMany Tag, `imageAttachments()` HasMany ContentImage, `fileAttachments()` HasMany ContentFile, `linkAttachments()` HasMany ContentLink

### `ContentCategory` / `ContentClassification` / `Tag`
- All auto-generate `slug` from `name` via `booted()` creating hook using `Str::slug()`

### `ContentImage` — `app/Models/ContentImage.php`
- Auto-deletes `path` from storage on update/delete

### `ContentFile` — `app/Models/ContentFile.php`
- Auto-deletes `path` from storage on update/delete

### `ContentLink` — `app/Models/ContentLink.php`
- No file cleanup needed

---

## Filament Resources

### `UserResource` — `app/Filament/Resources/UserResource.php`
- Nav icon: `heroicon-o-users`, sort: 1
- **Form**: name, email, roles (Select multi), avatar_url (FileUpload 200×200 1:1 crop), password (create only), change password (edit only)
- **Table**: avatar (circular, click-to-preview modal), name, email, roles (badge), joined date
- **Infolist**: avatar, name, email, roles, timestamps
- **Relation Managers**: EducationHistoryRelationManager, PublicationsRelationManager
- **Pages**: List, Create, View, Edit

### `ContentResource` — `app/Filament/Resources/ContentResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-document-text`, sort: 1
- **Form** (2-col): title (auto-slug with date prefix), slug (readOnly), excerpt, RichEditor (480px min-height), youtube_url | author (disabled, defaults to auth user), classification, category, tags (inline create), header_image (1000×600 5:3 crop), featured_image (1000×600 5:3 crop)
- **Table**: header_image (click-to-preview modal), title, author, classification, category, tags, created_at
- **Filters**: classification, category
- **Infolist**: header_image + featured_image (both click-to-preview modal), title, slug, excerpt, content (`.fi-content-prose` prose styling), youtube_url, author/classification/category/tags, timestamps
- **Relation Managers**: ImageAttachmentsRelationManager, FileAttachmentsRelationManager, LinkAttachmentsRelationManager
- **Pages**: List, Create, View, Edit

### `ContentCategoryResource` — `app/Filament/Resources/ContentCategoryResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-folder`, sort: 4
- **Form**: name (auto-slug), slug (readOnly)
- **Pages**: List, Create, Edit

### `ContentClassificationResource` — `app/Filament/Resources/ContentClassificationResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-tag`, sort: 3
- **Form**: name (auto-slug), slug (readOnly)
- **Pages**: List, Create, Edit

---

## Relation Managers

### `EducationHistoryRelationManager`
`app/Filament/Resources/UserResource/RelationManagers/EducationHistoryRelationManager.php`
- Relationship: `educationHistory` (User → UserEducation)
- Form: institution, degree, field_of_study, start_year, end_year, gpa, description, certificate_path (PDF/image, 2MB)
- Table: institution, degree, field, period ("2018 – 2022" or "2020 – Present"), gpa
- Reorderable by `order`

### `PublicationsRelationManager`
`app/Filament/Resources/UserResource/RelationManagers/PublicationsRelationManager.php`
- Relationship: `publications` (User → UserPublication)
- Form: title, type (Select), publisher, year, isbn, doi, url, description, file_path (PDF/image, 5MB)
- Table: title, type (color-coded badge), publisher, year
- Type colors: book=green, journal_article=blue, research_paper=yellow, conference_paper=red, other=gray
- Reorderable by `order`

### `ImageAttachmentsRelationManager`
`app/Filament/Resources/ContentResource/RelationManagers/ImageAttachmentsRelationManager.php`
- Relationship: `imageAttachments` (Content → ContentImage)
- Form: path (FileUpload, image editor, auto-resize 1000px width contain), caption
- Table: path (ImageColumn, click-to-preview modal, max-height 700px), caption, order
- Reorderable by `order`

### `FileAttachmentsRelationManager`
`app/Filament/Resources/ContentResource/RelationManagers/FileAttachmentsRelationManager.php`
- Relationship: `fileAttachments` (Content → ContentFile)
- Form: path (FileUpload, PDF/Office docs, 2MB), original_name
- Table: original_name, file type (extension badge), order

### `LinkAttachmentsRelationManager`
`app/Filament/Resources/ContentResource/RelationManagers/LinkAttachmentsRelationManager.php`
- Relationship: `linkAttachments` (Content → ContentLink)
- Form: url, label
- Table: label, url (copyable), order

---

## Panel Configuration
`app/Providers/Filament/AdminPanelProvider.php`

- Path: `/admin`, default panel, login enabled
- Primary color: Amber
- Plugins: `FilamentShieldPlugin` (RBAC), `FilamentMenuBuilderPlugin` (dynamic menus)
- Custom CSS: `public/css/filament-admin.css` registered via `Css::make()->relativePublicPath()`

---

## Custom Assets

### `public/css/filament-admin.css`
Scoped typography styles for `.fi-content-prose` class applied to the content RichEditor output in the Content infolist. Styles headings (h1–h4), paragraphs, lists, bold/italic, links (amber), blockquotes, inline code, and code blocks. Includes dark mode overrides.

---

## File Storage

All uploads use Laravel's `public` disk (`storage/app/public/`, symlinked to `public/storage/`).

| Model | Field | Directory |
|---|---|---|
| User | avatar_url | `avatars/` |
| UserEducation | certificate_path | `user-certificates/` |
| UserPublication | file_path | `user-publications/` |
| Content | header_image | `content-headers/` |
| Content | featured_image | `content-featured/` |
| ContentImage | path | `content-images/` |
| ContentFile | path | `content-files/` |

All file fields auto-delete from storage via model `booted()` hooks on record update (if field changes) and on record delete.
