Laravel Authentication Backend
This project sets up a Laravel backend with authentication and role management using the Spatie Laravel Permissions package.

Getting Started
1. Clone the Repository
To begin, clone the repository and install the composer dependencies:

bash
Copy code
git clone <repository-url>
cd <project-directory>
composer install
2. Spatie Role Management Setup
This project uses the Spatie Laravel Permissions package for role and permission management. To set up Spatie, follow these steps:

bash
Copy code
composer require spatie/laravel-permission
Next, publish the necessary files:

bash
Copy code
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
Clear the cached configurations:

bash
Copy code
php artisan optimize:clear
# or
php artisan config:clear
Then, add the HasRoles trait to your User model:

php
Copy code
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasRoles;
}
3. Database Configuration
Make sure your database connection is properly configured in the .env file:

bash
Copy code
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
You have two options to set up your database:

Option 1: Import the SQL File
Import the provided laravelproject.sql file into your MySQL database using a tool like MySQL Workbench.
Ensure the schema name matches the one in the .env file.
Option 2: Run Migrations
Alternatively, you can run the Laravel migrations to create the necessary tables:

bash
Copy code
php artisan migrate
4. Seeding the Database
To seed the database with permissions, roles, and users, follow these steps:

Before running the seeders, ensure the default authentication guard is set to api in the config/auth.php file:

php
Copy code
'defaults' => [
    'guard' => env('AUTH_GUARD', 'api'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
Next, seed the database:

bash
Copy code
php artisan db:seed
Once the seeding process is complete, revert the guard back to web:

php
Copy code
'defaults' => [
    'guard' => env('AUTH_GUARD', 'web'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
5. Running the Application
To start the Laravel development server, run:

bash
Copy code
php artisan serve
Open http://localhost:8000 in your browser to see the result.

6. Frontend Integration
To check the frontend part of the project, use the login credentials generated in the UserTableSeeder (email and password).

Notes
Ensure that the api guard is used for roles and permissions when running PermissionsTableSeeder and RoleTableSeeder.
After seeding the database, change the guard back to web to handle web-based authentication properly.
Troubleshooting
If you encounter any issues with the database schema, check your .env configuration to ensure the schema name is correct.
If you experience permission-related issues, confirm that the roles and permissions were seeded correctly using the api guard.
