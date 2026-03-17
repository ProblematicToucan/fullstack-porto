# Portfolio Filament CMS — Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Add Filament 3 as CMS and Blade public site for portfolio (Projects + Posts + About), reusing 1.x schema; all Filament code via Artisan CLI.

**Architecture:** Hybrid — migrations/models from 1.x (projects, posts, categories, tech_stacks, project_media, pivots); new Filament resources and Blade routes/controllers/views on current branch. Single Filament admin panel; no public auth.

**Tech Stack:** Laravel 12, Filament 3, Blade, existing UI components, Vite, Pest.

**Design reference:** `docs/plans/2025-03-17-portfolio-filament-design.md`

---

## Task 1: Install Filament and publish assets

**Files:**
- Modify: `composer.json` (dependency added by Composer)
- Create: (Filament will create) `app/Providers/Filament/AdminPanelProvider.php`, config, assets as per Filament docs

**Step 1: Require Filament**

Run: `composer require filament/filament:"^3.2" -W`

**Step 2: Install Filament panel**

Run: `php artisan filament:install --panels`

Choose "admin" panel when prompted (or use default). Ensure one panel exists at `/admin`.

**Step 3: Create Filament user (for later login)**

Run: `php artisan make:filament-user`

Fill in name, email, password. (Or skip and do in Task 10.)

**Step 4: Verify admin**

Run: `php artisan serve` (or use existing dev command). Visit `http://localhost:8000/admin`, log in. Expected: Filament dashboard.

**Step 5: Commit**

Commit: `composer.json`, `composer.lock`, and any new/updated files from Filament (e.g. `app/Providers/Filament/AdminPanelProvider.php`, config files). Message: `chore: install Filament 3 admin panel`

---

## Task 2: Create migrations (projects, categories, tech_stacks, pivots)

**Files:**
- Create: `database/migrations/xxxx_create_projects_table.php`
- Create: `database/migrations/xxxx_create_categories_and_project_categories_tables.php` (categories + pivot; order after projects)
- Create: `database/migrations/xxxx_create_tech_stacks_and_project_tech_stacks_tables.php` (order after projects)

**Step 1: Projects migration**

Create migration: `php artisan make:migration create_projects_table`

Edit the migration: table `projects` with columns: id, title (string), slug (string unique), description (json), project_url (string nullable), repo_url (string nullable), image (string nullable), is_featured (boolean default false), timestamps, softDeletes.

**Step 2: Run migration**

Run: `php artisan migrate --path=database/migrations/xxxx_create_projects_table.php`

**Step 3: Categories + pivot migration**

Create migration: `php artisan make:migration create_categories_and_project_categories_tables`

In up(): create `categories` (id, name, slug unique, description nullable, timestamps); create `project_categories` (id, project_id FK constrained('projects')->cascadeOnDelete(), category_id FK constrained('categories')->cascadeOnDelete(), unique [project_id, category_id]). Use $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete() so models are not required yet.

**Step 4: Tech stacks + pivot migration**

Create migration: `php artisan make:migration create_tech_stacks_and_project_tech_stacks_tables`

In up(): create `tech_stacks` (id, name, slug unique nullable, logo nullable, description nullable, timestamps); create `project_tech_stacks` (id, project_id FK constrained('projects')->cascadeOnDelete(), tech_stack_id FK constrained('tech_stacks')->cascadeOnDelete(), timestamps). (1.x had logo/description required; making nullable keeps flexibility.)

**Step 5: Run new migrations**

Run: `php artisan migrate`

**Step 6: Commit**

Commit migrations. Message: `feat: add projects, categories, tech_stacks and pivot migrations`

---

## Task 3: Create migrations (project_media, posts, about)

**Files:**
- Create: `database/migrations/xxxx_create_project_media_table.php`
- Create: `database/migrations/xxxx_create_posts_table.php`
- Create: `database/migrations/xxxx_create_about_table.php`

**Step 1: Project media migration**

Run: `php artisan make:migration create_project_media_table`

Table `project_media`: id, project_id (FK cascade), media_type (string), media_url (string), media_description (nullable), timestamps.

**Step 2: Posts migration**

Run: `php artisan make:migration create_posts_table`

Table `posts`: id, title (string index), slug (string unique), is_public (boolean default true), content (json), timestamps, softDeletes.

**Step 3: About migration**

Run: `php artisan make:migration create_about_table`

Table `about`: id, heading (string nullable), body (text nullable), avatar (string nullable), links (json nullable), timestamps. Single row inserted in seeder later.

**Step 4: Run migrations**

Run: `php artisan migrate`

**Step 5: Commit**

Commit. Message: `feat: add project_media, posts, about migrations`

---

## Task 4: Create Eloquent models with relations

**Files:**
- Create: `app/Models/Project.php`
- Create: `app/Models/Category.php`
- Create: `app/Models/TechStack.php`
- Create: `app/Models/ProjectMedia.php`
- Create: `app/Models/Post.php`
- Create: `app/Models/About.php`

**Step 1: Project model**

Run: `php artisan make:model Project`

Add fillable: title, slug, description, project_url, repo_url, image, is_featured. Cast description => 'array'. Use SoftDeletes, HasFactory. getRouteKeyName() => 'slug'. Relations: categories() BelongsToMany(Category::class, 'project_categories'), techStacks() BelongsToMany(TechStack::class, 'project_tech_stacks'), projectMedias() HasMany(ProjectMedia::class). Accessor/mutator for is_featured as boolean if desired.

**Step 2: Category model**

Run: `php artisan make:model Category`

Fillable: name, slug, description. projects() BelongsToMany(Project::class, 'project_categories').

**Step 3: TechStack model**

Run: `php artisan make:model TechStack`

Fillable: name, slug, logo, description. projects() BelongsToMany(Project::class, 'project_tech_stacks').

**Step 4: ProjectMedia model**

Run: `php artisan make:model ProjectMedia`

Fillable: project_id, media_type, media_url, media_description. project() BelongsTo(Project::class).

**Step 5: Post model**

Run: `php artisan make:model Post`

Fillable: title, slug, is_public, content. Cast content => 'array'. SoftDeletes, HasFactory. getRouteKeyName() => 'slug'. Scope for public: scopePublic($q) { $q->where('is_public', true); }

**Step 6: About model**

Run: `php artisan make:model About`

Fillable: heading, body, avatar, links. Cast links => 'array'. No relations. Optional: static method firstOrCreateSingleton() for ensuring one row.

**Step 7: Commit**

Commit. Message: `feat: add Project, Category, TechStack, ProjectMedia, Post, About models`

---

## Task 5: Filament resources — Category and TechStack

**Files:**
- Create: `app/Filament/Resources/CategoryResource.php` (and Pages if generated)
- Create: `app/Filament/Resources/TechStackResource.php` (and Pages if generated)

**Step 1: CategoryResource**

Run: `php artisan make:filament-resource Category --generate`

Adjust form: name, slug (from title or manual), description. Adjust table columns. Ensure list/create/edit work.

**Step 2: TechStackResource**

Run: `php artisan make:filament-resource TechStack --generate`

Adjust form: name, slug (nullable), logo (nullable), description (nullable). Table columns as needed.

**Step 3: Verify**

Visit `/admin`, open Categories and Tech stacks, create one of each. Commit. Message: `feat: add Filament Category and TechStack resources`

---

## Task 6: Filament ProjectResource with relation managers

**Files:**
- Create: `app/Filament/Resources/ProjectResource.php` and Pages
- Create: `app/Filament/Resources/ProjectResource/RelationManagers/CategoriesRelationManager.php`
- Create: `app/Filament/Resources/ProjectResource/RelationManagers/TechStacksRelationManager.php`
- Create: `app/Filament/Resources/ProjectResource/RelationManagers/ProjectMediaRelationManager.php`

**Step 1: ProjectResource**

Run: `php artisan make:filament-resource Project --generate`

Form: title, slug (e.g. from title), description (RichEditor or KeyValue/JSON), project_url, repo_url, image (FileUpload), is_featured (Toggle). Table: title, slug, is_featured, created_at. Slug for route key.

**Step 2: Relation managers**

Run:
- `php artisan make:filament-relation-manager ProjectResource categories name`
- `php artisan make:filament-relation-manager ProjectResource techStacks name`
- `php artisan make:filament-relation-manager ProjectResource projectMedias media_type media_url`

Configure each relation manager (BelongsToMany for categories/techStacks, HasMany for projectMedias). For project_media, form: media_type, media_url, media_description; reorder if desired.

**Step 3: Register relation managers**

In ProjectResource, register the three relation managers in `getRelations()` or via $table->relationship().

**Step 4: Verify**

Create a project, attach categories/tech stacks, add media. Commit. Message: `feat: add Filament ProjectResource with relation managers`

---

## Task 7: Filament PostResource and About resource

**Files:**
- Create: `app/Filament/Resources/PostResource.php` and Pages
- Create: `app/Filament/Resources/AboutResource.php` (or custom single-edit page)

**Step 1: PostResource**

Run: `php artisan make:filament-resource Post --generate`

Form: title, slug, is_public (Toggle), content (RichEditor or Json). Table: title, slug, is_public, updated_at. Slug route key.

**Step 2: About resource**

Option A: Run `php artisan make:filament-resource About --generate` and restrict to one record (e.g. in list only show first row; or use a custom page that edits About::first()).

Option B: Create a Filament custom page that loads About::firstOrCreate([]) and uses a form to save. Register in AdminPanelProvider as a menu item "About".

Implement one option so About is editable from admin (single record). Commit. Message: `feat: add Filament PostResource and About edit`

---

## Task 8: Public routes and controllers

**Files:**
- Modify: `routes/web.php`
- Create: `app/Http/Controllers/LandingController.php`
- Create: `app/Http/Controllers/ProjectController.php`
- Create: `app/Http/Controllers/PostController.php`
- Create: `app/Http/Controllers/AboutController.php`

**Step 1: Routes**

In `routes/web.php`: GET / → LandingController (invokable or index). GET /project → ProjectController@index, GET /project/{project} → ProjectController@show. GET /post → PostController@index, GET /post/{post} → PostController@show (bind post by slug; scope to is_public in route or controller). GET /about → AboutController (invokable). Use Route::get(..., [...])->name('...').

**Step 2: LandingController**

Return view('landing' or 'home', compact('featuredProjects', 'latestPosts')). Query: Project::where('is_featured', true)->take(6)->get(); Post::where('is_public', true)->latest()->take(3)->get(). (Create view in Task 9.)

**Step 3: ProjectController**

index: Project::with('categories','techStacks')->latest()->paginate(12); return view('projects.index', compact('projects')). show($project): return view('projects.show', compact('project')). Resolve project by slug (route model binding: Project::where('slug', $slug)->firstOrFail() or implicit binding with getRouteKeyName).

**Step 4: PostController**

index: Post::public()->latest()->paginate(10); return view('posts.index', compact('posts')). show($post): Ensure only public — e.g. Post::where('slug', $post)->where('is_public', true)->firstOrFail() in route or controller; return view('posts.show', compact('post')).

**Step 5: AboutController**

Invokable: $about = About::first(); return view('about', compact('about')).

**Step 6: Commit**

Commit. Message: `feat: add public routes and controllers for landing, projects, posts, about`

---

## Task 9: Blade layout and views

**Files:**
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/landing.blade.php` (or home)
- Create: `resources/views/projects/index.blade.php`, `resources/views/projects/show.blade.php`
- Create: `resources/views/posts/index.blade.php`, `resources/views/posts/show.blade.php`
- Create: `resources/views/about.blade.php`

**Step 1: Layout**

Create `layouts/app.blade.php`: HTML shell, nav with links to /, /project, /post, /about. @yield('content') or <x-slot>. Include Vite assets. Use existing UI components for nav (e.g. x-ui.link, x-ui.button).

**Step 2: Landing**

Extend layout; show hero section and list featured projects (cards with title, image, link to project show) and latest posts (title, link to post show). Use x-ui.text, x-ui.button, x-ui.badge as needed.

**Step 3: Projects index**

Extend layout; loop projects with link to route('project.show', $project). Pagination: {{ $projects->links() }}.

**Step 4: Project show**

Extend layout; show project title, description, image, project_url, repo_url, categories (badges), tech stacks, project media. Use UI components.

**Step 5: Posts index**

Extend layout; loop posts with link to route('post.show', $post). Pagination.

**Step 6: Post show**

Extend layout; show title, content (render JSON/HTML as per content structure), date.

**Step 7: About**

Extend layout; show $about->heading, $about->body, $about->avatar if present, optional links. Handle null $about (e.g. empty state or default text).

**Step 8: Point home route**

Ensure GET / uses the new landing view (already in LandingController). If welcome was used, replace or alias.

**Step 9: Commit**

Commit. Message: `feat: add Blade layout and public views for landing, projects, posts, about`

---

## Task 10: Seed About and optional Filament user; storage link

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Create or modify: `database/seeders/AboutSeeder.php` (optional)

**Step 1: About first row**

In DatabaseSeeder or AboutSeeder: About::firstOrCreate(['id' => 1], ['heading' => 'About', 'body' => '...', 'avatar' => null, 'links' => null]). Run: php artisan db:seed (or --class=AboutSeeder).

**Step 2: Storage link**

Run: php artisan storage:link`. Ensure project image and about avatar uploads use disk that uses this (e.g. public).

**Step 3: Filament user (if not in Task 1)**

Run: php artisan make:filament-user`. Document in README that first-time setup includes this.

**Step 4: Commit**

Commit. Message: `chore: seed About, storage link, doc Filament user`

---

## Task 11: Feature tests (Pest)

**Files:**
- Create or modify: `tests/Feature/LandingTest.php`
- Create: `tests/Feature/ProjectControllerTest.php`
- Create: `tests/Feature/PostControllerTest.php`
- Create: `tests/Feature/AboutControllerTest.php`

**Step 1: Landing test**

Test: get('/') → 200.

**Step 2: Projects tests**

Test: get(route('project.index')) → 200. Test: create a Project with slug 'foo'; get(route('project.show', 'foo')) → 200; get(route('project.show', 'nonexistent')) → 404.

**Step 3: Posts tests**

Test: get(route('post.index')) → 200. Test: create public Post with slug 'bar'; get(route('post.show', 'bar')) → 200. Test: create non-public Post with slug 'private'; get(route('post.show', 'private')) → 404.

**Step 4: About test**

Test: get(route('about')) → 200 (with or without About row).

**Step 5: Run tests**

Run: php artisan test` or `./vendor/bin/pest`. All pass.

**Step 6: Commit**

Commit. Message: `test: add feature tests for landing, projects, posts, about`

---

## Execution handoff

Plan complete and saved to `docs/plans/2025-03-17-portfolio-filament-implementation.md`.

**Two execution options:**

1. **Subagent-driven (this session)** — I dispatch a fresh subagent per task (or batched), review between tasks, fast iteration. Use @superpowers:subagent-driven-development.

2. **Parallel session (separate)** — Open a new session (optionally in a worktree), use @superpowers:executing-plans, and run through the plan with checkpoints.

Which approach do you want?
