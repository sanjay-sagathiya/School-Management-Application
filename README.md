<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.


## Installation Instructions

To install this project, follow these steps:

1. **Clone the repository**:
	```bash
	git clone https://github.com/yourusername/school-management-application.git
	cd school-management-application
	```

2. **Install dependencies**:
	```bash
	composer install
	npm install
	```

3. **Set up the environment**:
	- Copy the `.env.example` file to `.env`:
	```bash
	cp .env.example .env
	```
	- Generate the application key:
	```bash
	php artisan key:generate
	```

4. **Set up the database**:
	- Create a database in your preferred database management system.
	- Update the `.env` file with your database credentials.
	- Run migrations:
	```bash
	php artisan migrate
	```
	- Seed the database:
	```bash
	php artisan db:seed
	```

5. **Set up the Mail configuration in your .env file**:
	For example below
	```bash
	MAIL_MAILER=smtp
	MAIL_SCHEME=null
	MAIL_HOST=127.0.0.1
	MAIL_PORT=1025
	MAIL_USERNAME=null
	MAIL_PASSWORD=null
	MAIL_FROM_ADDRESS="hello@sma.com"
	MAIL_FROM_NAME="${APP_NAME}"
	```

## File Structure

The project has the following structure:

- **app/**: Contains the core application code.
- **bootstrap/**: Contains the application bootstrap files.
- **config/**: Contains configuration files.
- **database/**: Contains database migrations and seeders.
- **public/**: Contains the public assets.
- **resources/**: Contains views and raw assets.
- **routes/**: Contains route definitions.
- **storage/**: Contains logs and compiled files.
- **vendor/**: Contains Composer dependencies.

## Practical Task Overview

Practical Task : Developing a School Management Application Using Laravel

	- Admin should be able to login to the system. (Admin user can be added to the database statically)
	- Admin should be able to manage (Add, Edit, and Delete) Teachers
	- Admin can post new announcements to teachers. Teachers would see them when they login. No emails needed.
	- As a teacher, I should be able to login and manage Students and Parents
	- Teachers can post new announcements. They can be targeted towards Students or Parents or both. Emails should be sent from the system for these announcements.
	- Admin should be able to see all the students, parents, and announcements added by teachers.

Below are git branches and short description about each branch:

SMA-1 : Install Laravel Fortify and create authentication scaffolding
SMA-2 : Add Admin and Teacher functionality
SMA-3 : Add Teacher functionality and refactor code
SMA-4 : Add Announcements and notification functionality
SMA-5 : Add Student and Parent functionality
SMA-6 : Refactor code and update READE.MD file

## License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
