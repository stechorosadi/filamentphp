# Architecture

Laravel 13 + Filament 5 admin panel (CMS + user profile management) with a public-facing frontend.
Panel path: `/arsiparis` — accessible to any authenticated user; FilamentShield policies control per-resource access.

---

## Tech Stack

| Layer | Package | Version |
|---|---|---|
| Framework | laravel/framework | ^13.0 |
| Admin Panel | filament/filament | ^5.0 |
| Permissions | spatie/laravel-permission | ^7.3 |
| Shield (RBAC) | bezhansalleh/filament-shield | ^4.2 |
| Menu Builder | datlechin/filament-menu-builder | ^1.0 |
| PDF Export | barryvdh/laravel-dompdf | ^3.1 |
| Translations | spatie/laravel-translatable | ^6.x |
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

### `user_experiences`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | cascadeOnDelete |
| company | varchar | required |
| job_title | varchar | nullable |
| department | varchar | nullable |
| start_year | smallint unsigned | required |
| end_year | smallint unsigned | nullable (null = Current) |
| description | text | nullable |
| order | int unsigned | default 0, drag-to-reorder |
| timestamps | | |

### `user_certifications`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | cascadeOnDelete |
| title | varchar | required |
| issuing_organization | varchar | nullable |
| category | varchar | training / seminar / workshop / professional_certification / online_course |
| issue_year | smallint unsigned | nullable |
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
| title | json | translatable (EN/ID) |
| slug | varchar | unique |
| content_classification_id | FK → content_classifications | nullable, nullOnDelete |
| content_category_id | FK → content_categories | nullable, nullOnDelete |
| header_image | varchar | nullable, public disk `content-headers/` |
| featured_image | varchar | nullable, public disk `content-featured/` |
| excerpt | json | nullable, translatable (EN/ID) |
| content | json (longText) | required, translatable (EN/ID) |
| youtube_url | varchar | nullable |
| article_date | date | nullable — used for ordering (falls back to `created_at`) |
| published | boolean | default false — controls frontend visibility |
| featured | boolean | default false — pins to hero slider |
| archived | boolean | default false — hides from hero/latest/popular; shows on `/archive` page |
| views | unsignedBigInteger | default 0 — incremented once per session per article |
| timestamps | | |

### `content_categories`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | json | required, translatable (EN/ID) |
| slug | varchar | unique, auto-generated on create |
| icon | varchar | nullable — Heroicon string e.g. `heroicon-o-beaker` |
| image | varchar | nullable, public disk `content-categories/` |
| description | json | nullable, translatable (EN/ID) — max 500 chars |
| timestamps | | |

### `content_classifications`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | json | required, translatable (EN/ID) |
| slug | varchar | unique, auto-generated on create |
| icon | varchar | nullable — Heroicon string |
| image | varchar | nullable, public disk `content-classifications/` |
| timestamps | | |

### `tags`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | json | required, translatable (EN/ID) |
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

### `team_members`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | nullable |
| name | varchar | nullable (fallback when no linked user) |
| front_title | varchar | nullable — prefix e.g. "Dr." |
| back_title | varchar | nullable — suffix e.g. "M.Sc." |
| position | varchar | nullable |
| employee_number | varchar | nullable |
| photo | varchar | nullable, public disk `team-photos/` |
| instagram_url / facebook_url / x_url / threads_url / youtube_url | varchar | nullable |
| sort_order | int | default 0 |
| is_visible | boolean | default true |
| timestamps | | |

### `site_settings`
Single-row settings table. Key columns: `site_title`, `site_tagline`, `site_description`, `vision`, `mission`, `logo_path` (public disk `site/`), `favicon_path` (public disk `site/`), social URL fields, contact fields, theme color fields. Text fields (`site_title`, `site_tagline`, `site_description`, `vision`, `mission`, contact/address fields) are translatable JSON (EN/ID).

---

## Models

### `User` — `app/Models/User.php`
- Implements `FilamentUser`, `HasAvatar`
- Traits: `HasFactory`, `Notifiable`, `HasRoles` (Spatie)
- Auto-deletes `avatar_url` from storage on update/delete
- **deleting hook** also manually cleans up child files (DB cascade bypasses Eloquent hooks): education `certificate_path`, certification `certificate_path`, publication `file_path`
- **Relationships**: `educationHistory()` HasMany UserEducation, `workExperience()` HasMany UserExperience, `certifications()` HasMany UserCertification, `publications()` HasMany UserPublication

### `Content` — `app/Models/Content.php`
- Traits: `HasTranslations` — translatable fields: `title`, `excerpt`, `content`
- Casts: `published → boolean`, `featured → boolean`, `archived → boolean`, `article_date → date`, `views → integer`
- Auto-deletes `header_image` and `featured_image` from storage on update/delete
- **deleting hook** also manually deletes `imageAttachments` files and `fileAttachments` files (DB cascade bypasses Eloquent hooks on child rows)
- **Relationships**: `user()`, `classification()`, `category()`, `tags()`, `imageAttachments()`, `fileAttachments()`, `linkAttachments()`

### `TeamMember` — `app/Models/TeamMember.php`
- `fullName()` helper — assembles `front_title + user.name/name + back_title`
- Auto-deletes `photo` from storage via `deleting` hook (not `deleted`)
- **Relationships**: `user()` BelongsTo

### `ContentCategory` / `ContentClassification`
- Traits: `HasTranslations` — translatable: `name`, `description` (category); `name` (classification)
- Auto-generate `slug` from `name` on creating
- Auto-delete both `image` **and** `icon` from storage on field change and record delete

### `Tag` — `app/Models/Tag.php`
- Traits: `HasTranslations` — translatable: `name`
- Auto-generate `slug` on creating

### `SiteSetting` — `app/Models/SiteSetting.php`
- Traits: `HasTranslations` — translatable: `site_title`, `site_tagline`, `site_description`, `vision`, `mission`, contact/address fields
- `vision` and `mission` store rich HTML content (JSON, EN/ID), displayed on the About page
- Singleton via `SiteSetting::instance()`
- Auto-deletes `logo_path` and `favicon_path` on field change (updating) **and** on record delete

### `ContentImage` / `ContentFile` / `ContentLink`
- All auto-delete their file (where applicable) from storage on direct update/delete

---

## Filament Resources

### `UserResource` — `app/Filament/Resources/UserResource.php`
- **Table**: avatar (circular, click-to-preview modal), name, email, roles, joined date
- **Relation Managers** (tabs on edit/view): EducationHistory, WorkExperience, Certifications, Publications

### `ContentResource` — `app/Filament/Resources/ContentResource.php`
- **Form Publishing section**: Published toggle, Featured toggle, **Archived toggle**
- **Table**: ToggleColumn for Published and Archived, IconColumn for Featured, Views, author, classification, category
- **Filters**: Published, Featured, **Archived** (all TernaryFilter)
- **Relation Managers**: ImageAttachments, FileAttachments, LinkAttachments

### `TeamMemberResource` — `app/Filament/Resources/TeamMembers/TeamMemberResource.php`
- Split structure: `Schemas/TeamMemberForm.php`, `Tables/TeamMembersTable.php`
- **Table**: photo column (circular, click-to-preview modal), full name, position, employee number, visible toggle, sort order
- Drag-to-reorder support

### Other resources: `ContentCategoryResource`, `ContentClassificationResource`, `TagResource`, `ManageSiteSettings` page — unchanged from previous version.

---

## Relation Managers (UserResource)

| Manager | Relationship | Key Fields |
|---|---|---|
| `EducationHistoryRelationManager` | `educationHistory` | institution, degree, field_of_study, start_year, end_year, gpa, description, certificate_path (auto-resize 1000px) |
| `WorkExperienceRelationManager` | `workExperience` | company, job_title, department, start_year, end_year, description |
| `CertificationsRelationManager` | `certifications` | title, issuing_organization, category (select), issue_year, description, certificate_path (auto-resize 1000px) |
| `PublicationsRelationManager` | `publications` | title, type, publisher, year, isbn, doi, url, description, file_path (auto-resize 1000px) |

All relation managers support drag-to-reorder via `order` column.

---

## File Upload Auto-Resize

All image uploads use Filament's `automaticallyResizeImages*` API (Filepond, client-side, PDFs unaffected):

| Upload | Resize |
|---|---|
| User avatar | 200×200px (1:1, force) |
| Logo (`site/`) | 128px height (contain, no upscale) |
| Favicon (`site/`) | 32×32px (contain, no upscale) |
| Content header image | 1280×720px (5:3, force) |
| Content featured image | 1280×720px (5:3, force) |
| Education/Certification certificates | 1000px width (contain, no upscale) |
| Publication file/cover | 1000px width (contain, no upscale) |
| Category/Classification images | 100×100px (1:1, force) |

---

## File Storage

All uploads use Laravel's `public` disk (`storage/app/public/`, symlinked to `public/storage/`).

| Model | Field | Directory |
|---|---|---|
| User | avatar_url | `avatars/` |
| UserEducation | certificate_path | `user-certificates/` |
| UserCertification | certificate_path | `user-certificates/` |
| UserPublication | file_path | `user-publications/` |
| Content | header_image | `content-headers/` |
| Content | featured_image | `content-featured/` |
| ContentCategory | image | `content-categories/` |
| ContentClassification | image | `content-classifications/` |
| ContentImage | path | `content-images/` |
| ContentFile | path | `content-files/` |
| TeamMember | photo | `team-photos/` |
| SiteSetting | logo_path / favicon_path | `site/` |
| — | — | `background/` — static parallax images |
| dompdf | font cache | `storage/app/fonts/` — auto-created on boot |

**File deletion coverage:** All file fields auto-delete via model `booted()` hooks. For models with DB-level `cascadeOnDelete()` (content attachments, user relations), parent model `deleting` hooks manually iterate and delete child files before the cascade removes the rows.

---

## Image Conversion Service

`app/Services/ImageConverter.php` — converts uploaded JPEG/PNG images to WebP on ingest.

| Method | Description |
|---|---|
| `ImageConverter::toWebp($file, $directory)` | Converts a `TemporaryUploadedFile` to WebP (quality 80) using PHP GD; falls back to original file store if `imagewebp` is unavailable |
| `ImageConverter::encodeToWebp($path, $directory)` | Lower-level helper that reads a file path, re-encodes to WebP, and persists to storage |

Applied to all image `FileUpload` fields via `saveUploadedFileUsing` in:
- `ManageSiteSettings` (logo)
- `ContentResource` (header image, featured image)
- `ImageAttachmentsRelationManager`
- `TeamMemberForm` (photo)
- `UserResource` (avatar)

Favicon upload is **intentionally excluded** — browsers require PNG/ICO format. Transparent PNGs are handled correctly via `imagealphablending` + `imagesavealpha`.

---

## PWA Support

The application ships as an installable Progressive Web App.

| Asset | Path | Description |
|---|---|---|
| App manifest | `GET /manifest.webmanifest` | Dynamic JSON; reads `SiteSetting` name, description, and theme colors |
| Service worker | `public/sw.js` | Cache-first strategy for offline support |
| Offline fallback | `public/offline.html` | Standalone HTML shown when network is unavailable and no cache exists |
| Icon 192px | `public/icons/icon-192.png` | Required by the manifest `icons` array |
| Icon 512px | `public/icons/icon-512.png` | Required by the manifest `icons` array |
| Desktop screenshot | `public/storage/screenshots/desktop-screenshot.png` | `form_factor: wide` (1498×903) — used in Chrome install UI |
| Mobile screenshot | `public/storage/screenshots/mobile-screenshot.png` | `form_factor: narrow` (375×798) — used in Chrome install UI |

The manifest route caches a **plain array** of primitive site-setting values (not the Eloquent model) for 300 s — avoids PHP deserialization errors from the `HasTranslations` trait in the cache.

PWA meta tags (`<link rel="manifest">`, `<meta name="theme-color">`, `apple-touch-icon`) are injected in `layouts/front.blade.php`.

---

## PDF Export

Package: `barryvdh/laravel-dompdf` v3.x — explicitly registered in `bootstrap/providers.php` to bypass auto-discovery issues on live servers.

Config: `config/dompdf.php` — `enable_remote: true` (allows remote image fetching for storage URLs and YouTube thumbnails), font cache at `storage/app/fonts/` (auto-created in AppServiceProvider).

| Export | Route | View |
|---|---|---|
| Content article | `GET /articles/{slug}/pdf` | `resources/views/content/pdf.blade.php` |
| Team member profile | `GET /team/{member}/pdf` | `resources/views/team/pdf.blade.php` |

PDF views use inline styles (no Tailwind dependency) and HTML tables for layout (flexbox/grid not supported by dompdf).

---

## Security Headers

`app/Http/Middleware/SecurityHeaders.php` — applied globally via `bootstrap/app.php`.

| Header | Value |
|---|---|
| X-Content-Type-Options | nosniff |
| X-Frame-Options | SAMEORIGIN |
| Referrer-Policy | strict-origin-when-cross-origin |
| Permissions-Policy | camera=(), microphone=(), geolocation=() |
| Content-Security-Policy | default-src 'self'; script-src includes unpkg.com + Cloudflare; frame-src includes `youtube.com` + `youtube-nocookie.com` (for embeds) |

---

## Dashboard Widget

`app/Filament/Widgets/StatsOverviewWidget` — custom Widget with inline-styled blade view (`resources/views/filament/widgets/stats-overview-widget.blade.php`). Uses inline styles (Tailwind not loaded in admin panel CSS).

Four gradient stat cards:
1. **Articles** — total count, published progress bar, featured/archived/draft pills
2. **Total Views** — sum across all articles, avg per published article
3. **Taxonomy** — combined categories + classifications, split grid
4. **Team Members** — total, visible progress bar, hidden count

Sort: `0` (appears after AccountWidget −3 and FilamentInfoWidget −2).

---

## Authorization / Policies

All policies in `app/Policies/`. Registered via auto-discovery + explicit `Gate::policy()` in `AppServiceProvider` for third-party models.

**`canAccessPanel()`** returns `true` for all authenticated users. Users without roles see only the Dashboard — FilamentShield blocks all resource navigation.

---

## Panel Configuration
`app/Providers/Filament/AdminPanelProvider.php`

- Path: `/arsiparis`, default panel, **login only** (registration disabled)
- **Dynamic brand logo** — `brandLogo()` closure reads `SiteSetting::instance()` at runtime; renders logo image + site title if a logo is set, falls back to `app.name` on error
- Primary color: Amber
- Plugins: `FilamentShieldPlugin`, `FilamentMenuBuilderPlugin`
- Widgets auto-discovered from `app/Filament/Widgets/`

`AppServiceProvider::boot()`:
- `Gate::policy()` for Tag and Menu models
- `View::share('siteSetting', ...)` — cached SiteSetting instance shared with all views (TTL 300s)
- `View::composer('layouts.front', ...)` — injects nav/footer menu items
- Auto-creates `storage/app/fonts/` directory for dompdf font cache

---

## Frontend

### Routes — `routes/web.php`

All frontend routes are prefixed with `/{locale}/` (where `locale` is `en` or `id`). The root `/` redirects to `/id/`. The `setlocale` middleware reads the route parameter, validates it, and calls `app()->setLocale()`.

| Method | URI | Name | Controller method |
|---|---|---|---|
| GET | `/sitemap.xml` | `sitemap` | `SitemapController@index` |
| GET | `/` | `root` | redirect → `/id/` |
| GET | `/{locale}/` | `home` | `HomeController@index` |
| GET | `/{locale}/search` | `search` | `HomeController@search` |
| GET | `/{locale}/sitemap` | `sitemap.html` | `SitemapController@html` |
| GET | `/{locale}/archive` | `archive` | `HomeController@archive` |
| GET | `/{locale}/categories/{slug}` | `category.show` | `HomeController@category` |
| GET | `/{locale}/classifications/{slug}` | `classification.show` | `HomeController@classification` |
| GET | `/{locale}/tags/{slug}` | `tag.show` | `HomeController@tag` |
| GET | `/{locale}/articles/{slug}` | `content.show` | `HomeController@show` |
| GET | `/{locale}/articles/{slug}/pdf` | `content.pdf` | `HomeController@pdf` |
| GET | `/{locale}/team` | `team` | `HomeController@team` |
| GET | `/{locale}/team/{member}` | `team.member` | `HomeController@memberShow` |
| GET | `/{locale}/about` | `about` | `HomeController@about` |
| GET | `/{locale}/team/{member}/pdf` | `team.member.pdf` | `HomeController@memberPdf` |
| GET | `/manifest.webmanifest` | `manifest` | inline closure — dynamic PWA manifest |

Throttle: 60 req/min for most routes; 30/min for article show; 10/min for PDFs.

**`lroute()` helper** (`app/helpers.php`) — wraps `route()` to automatically inject the current `app()->getLocale()` as the `locale` parameter. Use instead of `route()` in all frontend views and controllers.

### `HomeController` — `app/Http/Controllers/HomeController.php`

All methods that serve locale-prefixed routes accept `string $locale` as first parameter (injected from route). Text searches use `JSON_UNQUOTE(JSON_EXTRACT(column, '$."{locale}"'))` MySQL queries against JSON-stored translatable columns.

| Method | Purpose |
|---|---|
| `index()` | Homepage — featured (non-archived), latest (non-archived), categories, classifications, popular (non-archived); ordered by `article_date`; supports `?search=`, `?category=`, `?classification=` |
| `about()` | About page — passes `$siteSetting` for Vision/Mission content and live article/member/category counts |
| `search()` | Search page — includes archived content; paginate(12), locale-aware full-text search across title, excerpt, category, classification, tags |
| `archive()` | Archive page — only `published=true, archived=true` content; ordered by `article_date`; paginate(12) |
| `category()` | Category page — all published content for that category; paginate(9) |
| `classification()` | Classification page — all published content for that classification; paginate(9) |
| `tag()` | Tag page — all published content with that tag; paginate(12); shows up to 12 other tags |
| `show()` | Article detail — session-deduplicated view counter; 3 related articles ordered by `article_date` |
| `pdf()` | Stream content article as PDF download |
| `team()` | Team listing — visible members only |
| `memberShow()` | Team member detail — loads all user relations |
| `memberPdf()` | Stream team member profile as PDF download |

### `SitemapController` — `app/Http/Controllers/SitemapController.php`

| Method | Purpose |
|---|---|
| `index()` | Renders `/sitemap.xml` — published non-archived articles, categories, classifications, active tags, visible team members |
| `html()` | Renders `/{locale}/sitemap` — human-readable HTML sitemap with the same data |

### Archived Content Behaviour

| Location | Archived shown? |
|---|---|
| Hero slider | No |
| Homepage latest grid | No |
| Most popular section | No |
| Related articles | No |
| `/archive` page | Yes (exclusively) |
| Search results | Yes (with badge) |
| Category / Classification pages | Yes (with badge) |
| Direct article URL | Yes |

### Views

| Path | Description |
|---|---|
| `layouts/front.blade.php` | Shared frontend layout — navbar (with language toggle EN/ID), footer |
| `welcome.blade.php` | Homepage — hero slider (11s auto-advance), search, categories, latest, classifications, popular |
| `search.blade.php` | Search results — mobile-responsive search bar |
| `archive.blade.php` | Archive listing — dark hero with archive icon, 3-col content grid, pagination |
| `category/show.blade.php` | Category detail — content grid |
| `classification/show.blade.php` | Classification detail — content grid |
| `tag/show.blade.php` | Tag detail — content grid (12/page), sidebar with up to 12 other tags |
| `content/show.blade.php` | Article detail — clickable tag pills linking to `/tags/{slug}`; sidebar with social share + Export PDF; masonry image gallery |
| `content/pdf.blade.php` | PDF template — header with logo, badges, title, meta, header image, content, tags, YouTube thumbnail, 3-col image gallery, file/link attachments |
| `sitemap.blade.php` | XML sitemap template (served as `application/xml`) |
| `sitemap-html.blade.php` | Human-readable HTML sitemap — articles, categories, classifications, tags, team members |
| `team/index.blade.php` | Team listing — clickable cards (overlay link) |
| `team/show.blade.php` | Member detail — hero, education/experience timeline (2-col), certifications grid, publications list, back link + Export PDF |
| `team/pdf.blade.php` | PDF template — site header, profile hero, education/experience timeline, certifications grid, publications list |
| `about.blade.php` | About page — animated hero, live stats (articles/members/categories), Vision & Mission full-width split rows with background overlay |
| `errors/layout.blade.php` | Shared error page layout — dual-language (EN/ID) title/message, 15s countdown ring |
| `errors/*.blade.php` | Custom error pages (403, 404, 419, 429, 500, 503) — extend `errors.layout`; standalone (no DB queries) |

### Frontend Features
- **Dual-language (EN/ID)** — all routes prefixed with `/{locale}/`; language toggle button in navbar switches locale and stays on the same page (null-safe URL computation); translatable content rendered via `$model->getTranslation('field', app()->getLocale())`
- **Hero slider** — up to 5 featured+published+non-archived items; Alpine.js 11s auto-advance; Watch Video button (only shown when YouTube URL present, opens video in modal, stops on close via `x-if`); ordered by `article_date`
- **Tag pages** — tag pills on article detail page link to `/{locale}/tags/{slug}`; tag page shows paginated content (12/page) and a sidebar of up to 12 other tags
- **Sitemaps** — XML sitemap at `/sitemap.xml`; human-readable HTML sitemap at `/{locale}/sitemap`
- **Article date ordering** — all content lists ordered by `article_date` (explicit date field) rather than `created_at`
- **Social share** — on article sidebar: Twitter/X, Facebook, WhatsApp, Copy Link (Alpine.js clipboard with 2s feedback)
- **Archive page** — `/{locale}/archive` lists all published+archived content with Archived badge always visible
- **Archived badge** — gray pill shown on cards in search, category, classification, tag pages and article detail badges row
- **Team member cards** — full card clickable via invisible overlay `<a>` (z-10); social icons above (z-20)
- **Team member detail** — education + experience side-by-side timeline with dot markers; certificates 2-col grid; publications with type + year badges; PDF export
- **Article image gallery** — CSS `columns-1 sm:columns-2` masonry layout; natural image proportions; lightbox modal
- **Dark / Light mode** — Alpine.js toggle, localStorage persistence
- **Page view tracking** — session-deduplicated, stored in `contents.views`
- **CSS variable theme system** — all frontend colors driven by `SiteSetting` color fields, emitted as `:root` CSS custom properties in `layouts/front.blade.php`. Variables: `--bg-primary`, `--bg-alt`, `--accent`, `--accent-dim`, `--text-primary`, `--text-muted`, `--on-dark`, `--dark-section`, `--dark-bg`, `--accent-on-dark`. Dark mode overrides emitted in the same `<style>` block.
- **WebP auto-conversion** — all image uploads are re-encoded to WebP (quality 80) via `ImageConverter::toWebp()` before storage; falls back to original format if PHP GD `imagewebp` is unavailable
- **PWA** — installable app with dynamic manifest, service worker, offline fallback, and 192/512px icons; screenshots supplied for richer Chrome install UI
- **About page** — `/{locale}/about`; displays Vision & Mission from `SiteSetting` as full-width split rows with a background overlay; live article/member/category counts
- **Accessibility** — all icon-only buttons carry `aria-label`; decorative inline SVGs carry `aria-hidden="true"`
