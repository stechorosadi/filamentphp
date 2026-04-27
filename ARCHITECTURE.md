# Architecture

Laravel 13 + Filament 5 admin panel (CMS + user profile management) with a public-facing frontend.
Panel path: `/admin` — accessible to any authenticated user; FilamentShield policies control per-resource access.

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
| password | varchar | hashed (`'hashed'` cast) |
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
| published | boolean | default false — controls frontend visibility |
| featured | boolean | default false — pins to hero slider |
| views | unsignedBigInteger | default 0 — incremented once per session per article |
| timestamps | | |

### `content_categories`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | required |
| slug | varchar | unique, auto-generated on create |
| icon | varchar | nullable — Heroicon string e.g. `heroicon-o-beaker` |
| image | varchar | nullable, public disk `content-categories/` — PNG, 100×100 1:1 |
| description | text | nullable — scope/topic description, max 500 chars |
| timestamps | | |

### `content_classifications`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar | required |
| slug | varchar | unique, auto-generated on create |
| icon | varchar | nullable — Heroicon string |
| image | varchar | nullable, public disk `content-classifications/` — PNG, 100×100 1:1 |
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
- `canAccessPanel()` returns `true` for all authenticated users — FilamentShield policies control per-resource access
- `'password' => 'hashed'` cast — never store plain text; the cast hashes on `fill()`
- Auto-deletes `avatar_url` from storage on update/delete
- **Relationships**: `educationHistory()` HasMany UserEducation, `publications()` HasMany UserPublication

### `Content` — `app/Models/Content.php`
- Casts: `published → boolean`, `featured → boolean`, `views → integer`
- Auto-deletes `header_image` and `featured_image` from storage on update/delete
- **Relationships**: `user()` BelongsTo, `classification()` BelongsTo ContentClassification, `category()` BelongsTo ContentCategory, `tags()` BelongsToMany Tag, `imageAttachments()` HasMany ContentImage, `fileAttachments()` HasMany ContentFile, `linkAttachments()` HasMany ContentLink

### `ContentCategory` — `app/Models/ContentCategory.php`
- Auto-generates `slug` from `name` on creating
- Auto-deletes `image` from storage on field change (updating) and record delete
- **Fillable**: `name`, `slug`, `icon`, `image`, `description`

### `ContentClassification` — `app/Models/ContentClassification.php`
- Auto-generates `slug` from `name` on creating
- Auto-deletes `image` from storage on field change (updating) and record delete
- **Fillable**: `name`, `slug`, `icon`, `image`

### `Tag` — `app/Models/Tag.php`
- Traits: `HasFactory`, `HasRoles`
- Auto-generates `slug` from `name` on creating
- **Relationships**: `contents()` BelongsToMany Content

### `ContentImage` / `ContentFile` / `ContentLink`
- All auto-delete their file (where applicable) from storage on update/delete

---

## Filament Resources

### `UserResource` — `app/Filament/Resources/UserResource.php`
- Nav icon: `heroicon-o-users`, sort: 1
- **Form**: name, email, roles (Select multi), avatar_url (FileUpload 200×200 1:1), password (create only)
- **Change Password** (edit only):
  - `super_admin`: sees only New Password + Confirm — no old password required; updates via `DB::table()->update()` to bypass `'hashed'` cast double-hashing
  - Other roles: must enter Current Password (validated against stored hash), New Password, Confirm
- **Table**: avatar (circular, click-to-preview modal), name, email, roles (badge), joined date
- **Pages**: List, Create, View, Edit

### `ContentResource` — `app/Filament/Resources/ContentResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-document-text`, sort: 1
- **Form** (2-col): title (auto-slug with date prefix), slug (readOnly), excerpt, RichEditor, youtube_url | author, **Publishing section** (Published toggle + Featured toggle), classification, category, tags (inline create), header_image, featured_image
- **Table**: header_image (preview modal), title, **Published** (ToggleColumn — inline toggle), **Featured** (IconColumn star), **Views** (TextColumn with eye icon), author, classification, category, tags, created_at
- **Filters**: Published (TernaryFilter), Featured (TernaryFilter), classification, category
- **Infolist**: images, title, slug, excerpt, content (prose styled), youtube_url | author, classification, category, published (IconEntry), featured (IconEntry), tags | timestamps + **views count**
- **Relation Managers**: ImageAttachments, FileAttachments, LinkAttachments
- **Pages**: List, Create, View, Edit

### `ContentCategoryResource` — `app/Filament/Resources/ContentCategoryResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-folder`, sort: 4
- **Form**:
  - *Category Details*: name (auto-slug live), slug (readOnly), description (Textarea, max 500 chars, full-width)
  - *Media*: icon (searchable Select of all 1,288 Heroicons), image (FileUpload — PNG, 1:1, 100×100px auto-resize, max 1MB)
- **Table**: image (ImageColumn 40px), icon (inline SVG via `svg()->toHtml()` + HtmlString to bypass Filament sanitiser), name, slug, description (toggleable, hidden by default)
- **Pages**: List, Create, Edit

### `ContentClassificationResource` — `app/Filament/Resources/ContentClassificationResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-tag`, sort: 3
- **Form**:
  - *Classification Details*: name (auto-slug live), slug (readOnly)
  - *Media*: icon (searchable Heroicon Select), image (FileUpload — PNG, 1:1, 100×100px, max 1MB)
- **Table**: image (ImageColumn), icon (inline SVG), name, slug
- **Pages**: List, Create, Edit

### `TagResource` — `app/Filament/Resources/Tags/TagResource.php`
- Nav group: `Content Management`, icon: `heroicon-o-hashtag`, sort: 5
- Split into separate schema/table files (`Tags/Schemas/TagForm.php`, `Tags/Tables/TagsTable.php`)
- **Form** (*Tag Details*): name (live auto-slug), slug (readOnly, unique)
- **Table**: name, slug, articles count (amber badge, `withCount('contents')` via `modifyQueryUsing`), created_at
- **Pages**: List, Create, Edit

---

## Relation Managers

### `EducationHistoryRelationManager` / `PublicationsRelationManager`
_(unchanged — see previous version)_

### `ImageAttachmentsRelationManager` / `FileAttachmentsRelationManager` / `LinkAttachmentsRelationManager`
_(unchanged — see previous version)_

---

## Authorization / Policies

Policies in `app/Policies/`. Both registered via auto-discovery AND explicitly in `AppServiceProvider` for cases where auto-discovery fails (third-party models).

| Policy | Model | Notes |
|---|---|---|
| `ContentPolicy` | `Content` | Auto-discovered |
| `ContentCategoryPolicy` | `ContentCategory` | Auto-discovered |
| `ContentClassificationPolicy` | `ContentClassification` | Auto-discovered |
| `TagPolicy` | `Tag` | Auto-discovered + explicit `Gate::policy()` |
| `MenuPolicy` | `Datlechin\...\Menu` | Third-party — explicit `Gate::policy()` required |
| `RolePolicy` | `Spatie\...\Role` | Explicit registration |

All policies delegate to `$authUser->can('Action:ModelName')` (Spatie permission names).

**`canAccessPanel()`** returns `true` for all authenticated users. Users without roles see only the Dashboard — FilamentShield blocks all resource navigation via `viewAny` policy checks.

---

## Panel Configuration
`app/Providers/Filament/AdminPanelProvider.php`

- Path: `/admin`, default panel, login + registration enabled
- Primary color: Amber
- Plugins: `FilamentShieldPlugin` (RBAC), `FilamentMenuBuilderPlugin` (dynamic menus)
- Custom CSS: `public/css/filament-admin.css`

`AppServiceProvider::boot()` also registers:
- `Gate::policy()` for Tag and Menu models
- `View::composer('layouts.front', ...)` — injects `$navMenuItems` (from "Header Menu - Top Right" DB menu) into every frontend page

---

## Frontend

### Routes — `routes/web.php`

| Method | URI | Name | Controller method |
|---|---|---|---|
| GET | `/` | `home` | `HomeController@index` |
| GET | `/search` | `search` | `HomeController@search` |
| GET | `/categories/{slug}` | `category.show` | `HomeController@category` |
| GET | `/classifications/{slug}` | `classification.show` | `HomeController@classification` |
| GET | `/articles/{slug}` | `content.show` | `HomeController@show` |

### `HomeController` — `app/Http/Controllers/HomeController.php`

| Method | Purpose |
|---|---|
| `index()` | Homepage — featured slider, paginate(9) latest, categories, classifications, popular (top 5 by views), total article count; supports `?search=`, `?category=`, `?classification=` |
| `search()` | Search page — paginate(12), searches title/excerpt/category/classification/tags; random category suggestions for empty state |
| `category()` | Dedicated category page — paginate(9) by category, fetches other categories |
| `classification()` | Dedicated classification page — paginate(9) by classification, fetches other classifications |
| `show()` | Article detail — session-deduplicated view counter (`viewed_content_{id}`), 3 related articles from same category/classification |

### Views

| Path | Description |
|---|---|
| `resources/views/layouts/front.blade.php` | Shared frontend layout — navbar (dynamic from FilamentMenuBuilder "Header Menu - Top Right"), footer with quick links |
| `resources/views/welcome.blade.php` | Homepage — hero slider, search section, categories, latest content (parallax bg), classifications, most popular |
| `resources/views/search.blade.php` | Search results — keyword highlighting, empty state with category suggestions |
| `resources/views/category/show.blade.php` | Category detail — dark header with image/name/description, content grid, other categories |
| `resources/views/classification/show.blade.php` | Classification detail — dark header, content grid, other classifications |
| `resources/views/content/show.blade.php` | Article detail — title → badges → image → sidebar (author, reading time, views, date) → prose content → attachments → related articles |
| `resources/views/errors/layout.blade.php` | Shared error page layout (standalone, no DB) |
| `resources/views/errors/{404,403,500,503,419,429}.blade.php` | Individual error pages |

### Frontend Features
- **Hero slider** — up to 5 featured+published content items; Alpine.js auto-advance (6s), prev/next arrows, dot navigation, Preview modal
- **Search section** — article/category/classification counts, redirects to `/search?q=`
- **Browse by Category** — 4-col grid, first card featured (2-col span), icon glow ring, description, dot grid texture
- **Latest Content** — search + filter, paginate(9), parallax background image (`storage/background/bg-01.jpg`)
- **Classifications** — dark `#4B2E2B` section, 4-col horizontal cards with amber left-border, icon + image thumbnail
- **Most Popular** — featured #1 full-bleed card + ranked list #2–5 with staggered slide-in animation
- **Dark / Light mode** — Alpine.js toggle, `localStorage` persistence, flash prevention script
- **Card animations** — `IntersectionObserver` staggered entrance (opacity + translateY)
- **Page view tracking** — session-deduplicated, stored in `contents.views`
- **Error pages** — 15-second countdown ring (SVG + Alpine.js) auto-redirects to `/`; dark mode aware

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
| ContentCategory | image | `content-categories/` |
| ContentClassification | image | `content-classifications/` |
| ContentImage | path | `content-images/` |
| ContentFile | path | `content-files/` |
| — | — | `background/` — static parallax images |

All file fields auto-delete via model `booted()` hooks on record update (field changed) and delete.

---

## Custom Assets

### `public/css/filament-admin.css`
Scoped typography for `.fi-content-prose` applied to the RichEditor output in the Content infolist. Styles headings, paragraphs, lists, links (amber), blockquotes, code blocks, images. Includes dark mode overrides.

### `resources/css/app.css`
Frontend Tailwind CSS. Includes:
- `@variant dark` (class-based dark mode)
- Animate-fade-up keyframes for hero
- `.prose-content` — article body typography (headings, links, blockquotes, code, images)
- Scrollbar styling (warm palette)
- `::selection` color (`#8C5A3C` background)
