# Laravel Starter Kit 🚀

A modern Laravel starter kit with InertiaJS, Vue 3, Tailwind CSS, and Laravel Sail pre-configured for rapid application development.

## ✨ Features

- **Laravel** - Latest version with best practices
- **InertiaJS** - Modern monolith approach for SPAs
- **Vue 3** - Progressive JavaScript framework with Composition API
- **Tailwind CSS** - Utility-first CSS framework
- **Laravel Sail** - Docker development environment
-  **Makefile helpers** - Convenient commands for common tasks

## 📋 Requirements

- docker
- git
- php 8.4
- composer

## 📥 Installation

Create a new project using Composer:

```bash
composer create-project brnbio/laravel-starterkit my-project
cd my-project
```

Or clone the repository:

```bash
git clone https://github.com/brnbio/laravel-starterkit.git my-project
cd my-project
```

Install dependencies and setup the project:

```bash
composer install
make install
```

This command will:
- Copy `.env.example` to `.env`
- Start Docker containers
- Install Composer dependencies
- Install NPM dependencies
- Generate application key
- Run database migrations and seeders

Start the frontend development server:

```bash
make dev
```

Your application will be available at `http://localhost`.

## ⚡ Makefile Commands

Run `make` to see all available commands:

### Docker Commands
- `make up` - Start the application
- `make down` - Stop the application
- `make restart` - Restart the application

### Development Commands
- `make install` - Fresh install (copies .env, installs dependencies, migrates database)
- `make dev` - Start development server with Vite hot reload
- `make precommit` - Run all pre-commit checks (linting, type checking, tests, security audits)
- `make refresh` - Refresh the database (migrate:fresh --seed)

### Backend Commands
- `make phpstan` - Run PHPStan static analysis
- `make pint` - Run Laravel Pint code formatter
- `make test` - Run Pest PHP tests

### Frontend Commands
- `make eslint` - Run ESLint linting


## 💻 Development Workflow

1. Start the application: `make up`
2. Start Vite dev server: `make dev`
3. Make your changes
4. Run pre-commit checks: `make precommit`
5. Commit your changes

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

