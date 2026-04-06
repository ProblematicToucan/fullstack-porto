# Fullstack Portfolio & Blog

This is a modern, high-performance, full-stack portfolio and blog platform built to showcase projects, share insights, and interact with visitors via an AI-powered assistant.

## Platform Features

- **Project Showcase**: Display projects with rich media (`ProjectMedia`), categories, and tech stack information.
- **Blog System**: Built-in blogging capabilities (`Post`) to share technical articles and updates.
- **AI Guest Assistant**: Features a sophisticated conversational assistant (`GuestAssistant`) using the Laravel AI SDK. It acts as an interactive guide for visitors, leveraging RAG (Retrieval-Augmented Generation) with Hybrid Search (PostgreSQL Full-Text Search + Vector Embeddings) across a knowledge base.
- **Admin Dashboard**: A secure and elegant backend control panel powered by Filament v5 for managing content directly.
- **Extensible Storage**: Pre-configured support for local, AWS S3, and Cloudflare R2 object storage for project assets.

## Tech Stack

- **Framework**: [Laravel 12](https://laravel.com) / PHP 8.2+
- **Frontend**: Blade Templating, [Tailwind CSS v4](https://tailwindcss.com), Vite
- **Admin Panel**: [Filament v5](https://filamentphp.com)
- **High Performance**: Optimized with [Laravel Octane](https://laravel.com/docs/octane) (FrankenPHP)
- **AI Integration**: [Laravel AI SDK](https://github.com/laravel/ai) providing intelligent tools (`GetPortfolioProject`, `ListPortfolioProjects`, `PortfolioKnowledgeSearch`)
- **Database**: PostgreSQL (with `pgvector` and Trigram search enabled for the hybrid search features)

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL database (Supabase recommended for pgvector support)

### Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd fullstack-porto
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the example environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   *Important: Configure your `.env` to connect to your PostgreSQL database, as SQLite does not natively support the pgvector and full-text search requirements out of the box.*

5. **Run Database Migrations:**
   ```bash
   php artisan migrate
   ```

6. **Build Frontend Assets:**
   ```bash
   npm run build
   ```
   *(Or run `npm run dev` for hot module replacement during development).*

7. **Start the Application:**
   For local development:
   ```bash
   php artisan serve
   ```
   For high-performance execution using Octane:
   ```bash
   php artisan octane:start --server=frankenphp
   ```

## Development

- You can access the backend management interface via `/admin`.
- Make sure that if you are contributing new components, you utilize Tailwind core utilities and follow the standard Filament conventions for administrative pages.
