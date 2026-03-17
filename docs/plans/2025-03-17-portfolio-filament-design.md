# Portfolio Website with Filament CMS — Design

**Approach:** Hybrid. Reuse 1.x schema (migrations/models); new Filament resources and Blade frontend on current branch. Filament files generated via **Artisan CLI** only.

---

## 1. Architecture & stack

- **Laravel 12** (existing).
- **Filament 3** — Single admin panel at `/admin` for Projects, Posts, Categories, Tech stacks, About. Add via Composer; all Filament code generated with Artisan (e.g. `make:filament-resource`, `make:filament-user`).
- **Blade** — All public pages; use existing UI components (`x-ui.button`, `x-ui.text`, `x-ui.badge`, etc.) and Vite.
- **Auth** — Filament auth only (same `User` model). No Breeze, no public login/register.

Public: routes → controllers → Blade views. Admin: Filament panel. Database: 1.x-style schema plus About.

---

## 2. Content model & Filament resources

**Models & tables:**

| Entity       | Purpose |
|-------------|---------|
| **Project** | title, slug, description (JSON/rich), project_url, repo_url, image, is_featured, timestamps, soft deletes. Relations: categories (M2M), tech_stacks (M2M), project_media (has-many). |
| **ProjectMedia** | type, path/url, sort order per project. |
| **Category** | name, slug, description. M2M with projects (pivot `project_categories`). |
| **TechStack** | name, slug (optional icon/color). M2M with projects (pivot `project_tech_stacks`). |
| **Post**     | title, slug, is_public, content (JSON/rich), timestamps, soft deletes. |
| **About**    | Single row: heading, body, avatar path, optional links. One Filament singleton-style edit. |

**Filament (Artisan-generated):**

- **ProjectResource** — CRUD; relation managers/repeaters for categories, tech stacks, project media. Form: title, slug (from title), description, project_url, repo_url, image, is_featured.
- **PostResource** — CRUD; title, slug, is_public, content (rich).
- **CategoryResource** — Simple CRUD.
- **TechStackResource** — Simple CRUD.
- **About** — One resource for single-record About (or Settings-style) so bio is editable in admin.

Slugs used for public URLs. Media on default disk (local or S3 later).

---

## 3. Public routes & Blade views

| Route                | Controller              | Purpose |
|---------------------|-------------------------|---------|
| `GET /`             | LandingController       | Home: hero, featured projects, latest posts. |
| `GET /project`      | ProjectController@index | List projects (optional category filter). |
| `GET /project/{project}` | ProjectController@show | Single project by slug. |
| `GET /post`         | PostController@index    | List public posts (`is_public`), newest first. |
| `GET /post/{post}`  | PostController@show     | Single post by slug (public only). |
| `GET /about`        | AboutController         | About page from About record. |

Thin controllers; route model binding by `slug`. Post show only for public posts (scope/404).

**Blade:** One layout (e.g. `layouts.app`) with nav (Home, Projects, Blog, About). Views: landing, projects.index, projects.show, posts.index, posts.show, about. Use `<x-ui.*>` components. Data from Eloquent in controllers only.

---

## 4. Error handling, testing & rollout

- **404:** Default Laravel. Binding by slug; non-public post → 404.
- **Testing:** Feature tests (Pest): `/`, `/project`, `/project/{slug}`, `/post`, `/post/{slug}` (public only), `/about` return expected status; optional Filament smoke test.
- **Rollout:** Migrate on deploy; create Filament user via `make:filament-user` or seed; `npm run build`; `storage:link` if using local uploads.

---

## Implementation note

- Use **Artisan CLI** for all Filament file generation (resources, user, etc.).
- **First-time setup:** Create an admin user with `php artisan make:filament-user`.
