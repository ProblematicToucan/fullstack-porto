# 🚀 Fullstack Portfolio CMS

> **A modern, feature-rich portfolio website with integrated Content Management System built with Laravel, React, and Filament Admin Panel**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![React](https://img.shields.io/badge/React-18.x-blue.svg)](https://reactjs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-blue.svg)](https://www.typescriptlang.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC.svg)](https://tailwindcss.com)
[![Filament](https://img.shields.io/badge/Filament-3.x-orange.svg)](https://filamentphp.com)

## ✨ Features

### 🎨 **Modern Frontend Experience**
- **React 18** with TypeScript for type-safe development
- **Inertia.js** for seamless SPA experience without API complexity
- **Tailwind CSS** with custom design system
- **Radix UI** components for accessible, beautiful interfaces
- **Responsive design** that works perfectly on all devices
- **Dark/Light theme** support
- **Smooth animations** and transitions

### 🛠️ **Powerful CMS Backend**
- **Filament Admin Panel** - Modern, intuitive admin interface
- **Project Management** - Showcase your work with rich media support
- **Blog System** - Write and manage blog posts with rich text editor
- **Category Management** - Organize projects and posts
- **Tech Stack Tracking** - Display technologies used in each project
- **Media Management** - Upload and organize project images/videos
- **SEO Optimization** - Built-in SEO features for better search visibility

### 🏗️ **Robust Architecture**
- **Laravel 11** - Latest PHP framework with modern features
- **MySQL Database** - Reliable data storage with migrations
- **Laravel Octane** - High-performance application server
- **Laravel Sanctum** - API authentication
- **Docker Support** - Easy deployment and development
- **AWS S3/R2 Integration** - Scalable file storage
- **Testing Suite** - Comprehensive tests with Pest PHP

### 🚀 **Performance & Scalability**
- **Server-side rendering** with Inertia.js
- **Asset optimization** with Vite
- **Image optimization** and lazy loading
- **Caching strategies** for optimal performance
- **Database optimization** with proper indexing
- **CDN ready** for global content delivery

## 🎯 **Perfect For**

- **Developers** showcasing their portfolio
- **Designers** displaying their creative work
- **Freelancers** presenting their services
- **Agencies** managing client portfolios
- **Content creators** with integrated blog system
- **Anyone** wanting a professional online presence

## 🛠️ **Tech Stack**

### Backend
- **Laravel 11** - PHP Framework
- **Filament 3** - Admin Panel
- **MySQL** - Database
- **Laravel Octane** - Performance
- **Laravel Sanctum** - Authentication
- **AWS S3/R2** - File Storage

### Frontend
- **React 18** - UI Library
- **TypeScript** - Type Safety
- **Inertia.js** - SPA Framework
- **Tailwind CSS** - Styling
- **Radix UI** - Components
- **Vite** - Build Tool

### Development
- **Docker** - Containerization
- **Pest PHP** - Testing
- **Laravel Pint** - Code Style
- **Rector** - Code Refactoring

## 🚀 **Quick Start**

### Prerequisites
- PHP 8.3+
- Node.js 18+
- Composer
- MySQL/PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/fullstack-porto.git
   cd fullstack-porto
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

### Docker Setup

```bash
docker-compose up -d
```

## 📱 **Screenshots**

### Frontend

#### Landing Page
![Landing Page](screenshots/fullstack-porto.test_landing.png)
*Beautiful hero section with featured projects and modern design*

#### Project Gallery
![Project Page](screenshots/fullstack-porto.test_project.png)
*Grid layout with filtering, search, and detailed project showcase*

#### Profile Page
![Profile Page](screenshots/fullstack-porto.test_profile.png)
*Personal profile and bio section*

### Admin Panel

#### Project Management
![Admin Projects](screenshots/fullstack-porto.test_admin_projects.png)
*Create, edit, and organize projects with rich media support*

#### Blog Management
![Admin Posts](screenshots/fullstack-porto.test_admin_posts.png)
*Rich text editor for blog posts with full content management*

#### Blog Editor
![Admin Post Editor](screenshots/fullstack-porto.test_admin_posts_89899702-2abd-479a-96b9-c87e9d987e81_edit.png)
*Advanced blog post editor with media integration*

## 🎨 **Customization**

### Themes
- Easily customizable with Tailwind CSS
- Component-based architecture
- Dark/light mode support
- Responsive design patterns

### Content Types
- **Projects** - Showcase your work with rich descriptions
- **Blog Posts** - Share your thoughts and expertise
- **Categories** - Organize content by topics
- **Tech Stacks** - Display technologies and skills

### Extensions
- Plugin architecture for easy extensions
- Custom fields support
- API endpoints for external integrations
- Webhook support for automation

## 🔧 **Configuration**

### Environment Variables
```env
APP_NAME="Your Portfolio"
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
```

### Admin Panel Access
- Default admin user created during seeding
- Access at `/admin` route
- Customizable admin theme and branding

## 📊 **Performance Features**

- **Laravel Octane** for high-performance serving
- **Database query optimization**
- **Image optimization and lazy loading**
- **Asset bundling and minification**
- **Caching strategies**
- **CDN integration ready**

## 🧪 **Testing**

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Generate test coverage
php artisan test --coverage
```

## 🚀 **Deployment**

### Production Setup
1. Configure production environment variables
2. Set up database and file storage
3. Run migrations and seeders
4. Build production assets
5. Configure web server (Nginx/Apache)
6. Set up SSL certificates
7. Configure CDN for assets

### Docker Deployment
```bash
docker-compose -f docker-compose.prod.yml up -d
```

## 🤝 **Contributing**

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 **Acknowledgments**

- [Laravel](https://laravel.com) - The PHP framework
- [React](https://reactjs.org) - The UI library
- [Filament](https://filamentphp.com) - The admin panel
- [Tailwind CSS](https://tailwindcss.com) - The CSS framework
- [Inertia.js](https://inertiajs.com) - The SPA framework

## 📞 **Support**

- 📧 Email: support@yourdomain.com
- 💬 Discord: [Join our community](https://discord.gg/your-server)
- 📖 Documentation: [Read the docs](https://docs.yourdomain.com)
- 🐛 Issues: [Report bugs](https://github.com/yourusername/fullstack-porto/issues)

---

<div align="center">

**⭐ Star this repository if you found it helpful!**

Made with ❤️ by [Your Name](https://github.com/yourusername)

</div>