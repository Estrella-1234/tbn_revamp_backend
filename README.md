# Event Management System

This is an Event Management System built with Laravel. It allows administrators to manage events, including creating, editing, viewing, and deleting events. Users can also be managed through this system.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Routes](#routes)
- [License](#license)

## Features

- User management (Create, Read, Update, Delete users)
- Event management (Create, Read, Update, Delete events)
- Upload and manage event posters
- Dashboard with statistics

## Requirements

- PHP >= 8.0
- Composer
- Laravel 8.x
- MySQL or other database supported by Laravel

## Installation

1. Clone the repository:
git clone https://github.com/your-username/event-management-system.git

2. Navigate to the project directory:
cd event-management-system

3. Install dependencies:
composer install

4. Copy the .env.example file to .env and configure your environment variables:
cp .env.example .env

5. Generate an application key:
php artisan key:generate

6. Set up your database and run migrations:
php artisan migrate

7. Link the storage directory to the public directory:
php artisan storage:link

8. Start the development server:
php artisan serve

License
This project is licensed under the MIT License - see the LICENSE file for details.
