#Getting started Lending Application

## Installation

Please check below details before you start.

Clone the repository

    git clone git@github.com:Roobini19/360bytes.git

Switch to the repo folder

    cd 360bytes

Install all the dependencies using composer

    composer install

Generate a new application key

    php artisan key:generate

Generate a new JWT authentication secret key

    php artisan jwt:secret

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000
