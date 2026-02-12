# Stat Track

A web application built with Laravel and Vue.js for tracking statistics.

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js 16.x or higher
- MySQL or PostgreSQL database

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/23DP2ASalk/my_website.git
cd my_website
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Configure environment

Copy the example environment file and configure your database:

```bash
cp .env.example .env
```

Edit `.env` and set your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Build assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 8. Start the application

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Development

### Running in development mode

Terminal 1 - Laravel server:
```bash
php artisan serve
```

Terminal 2 - Vite dev server:
```bash
npm run dev
```

### Running tests

```bash
php artisan test
```

## Tech Stack

- Laravel 10.x - PHP Framework
- Vue 3 - JavaScript Framework
- Vuetify - Material Design Component Framework
- Inertia.js - Modern monolith stack
- Vite - Frontend build tool

## Project Structure

```
.
├── app/                # Application code
│   ├── Http/          # Controllers, Middleware
│   └── Models/        # Eloquent models
├── bootstrap/         # Framework bootstrap
├── config/            # Configuration files
├── database/          # Migrations, seeds, factories
├── public/            # Public assets
├── resources/         # Views, JavaScript, CSS
│   ├── js/           # Vue components
│   └── views/        # Blade templates
├── routes/            # Route definitions
├── storage/           # Logs, cache, uploads
├── tests/             # Automated tests
└── vuetify/           # Vuetify configuration
```

## Database

Run migrations:
```bash
php artisan migrate
```

Rollback migrations:
```bash
php artisan migrate:rollback
```

Fresh migration (drop all tables):
```bash
php artisan migrate:fresh
```

## Deployment

### 1. Install dependencies

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 2. Configure environment

Set up your production `.env` file with proper settings:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### 3. Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Set permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Troubleshooting

### Clear cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Regenerate autoloader

```bash
composer dump-autoload
```

### Fix storage permissions

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-source software.
