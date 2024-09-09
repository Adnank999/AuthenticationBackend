Laravel Authentication Backend with Role Management
This project implements a Laravel backend with authentication and role management using the Spatie package.

Getting Started
1. Clone the Repository
Clone the repository and install the composer dependencies:

bash
Copy code
git clone <repository-url>
cd <project-directory>
composer install
2. Spatie Role Management Setup
The project uses Spatie Laravel Permissions for role and permission management.

Spatie Files Installation
Install the Spatie package:

bash
Copy code
composer require spatie/laravel-permission
Publish the necessary files:

bash
Copy code
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
Clear cached configurations:

bash
Copy code
php artisan optimize:clear
# or
php artisan config:clear
Add the necessary trait to your User model:

php
Copy code
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasRoles;
}
3. Database Configuration
Open the .env file and configure the database connection:

bash
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
You can either import the provided laravelproject.sql file into your MySQL database or use the Laravel migrations:

Option 1: Import the SQL file
Use any MySQL visualizer (e.g., MySQL Workbench) to import the provided laravelproject.sql.
Ensure the database schema name matches the .env file.
Option 2: Run Migrations
Run the migrations to set up the database:
bash
Copy code
php artisan migrate
4. Seeding the Database
There are three seeders provided to populate the database: PermissionsTableSeeder, RoleTableSeeder, and UserTableSeeder.

Before running the seeders, ensure that the default authentication guard is set to api:

In config/auth.php, change the default guard to api:

php
Copy code
'defaults' => [
    'guard' => env('AUTH_GUARD', 'api'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
Run the seeders to populate roles, permissions, and users:

bash
Copy code
php artisan db:seed
Once seeding is complete, revert the guard back to web in config/auth.php:

php
Copy code
'defaults' => [
    'guard' => env('AUTH_GUARD', 'web'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
5. Running the Application
Start the Laravel development server:

bash
Copy code
php artisan serve
6. Frontend Integration
Check the frontend part of the project and get the login credentials from the UserTableSeeder. You can use the seeded users' email and password to log in.

Notes
Ensure that the api guard is used for roles and permissions in the PermissionsTableSeeder and RoleTableSeeder.
After seeding, remember to switch the guard back to web to handle web-based authentication.
Troubleshooting
If you encounter any issues with the database schema, double-check your .env configurations and ensure the correct schema name is provided.
If permission issues arise, verify that the roles and permissions were seeded correctly using the api guard.
