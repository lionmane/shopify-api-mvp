# Conservatory Shopify MVP

This is a simple MVP to showcase integration of Shopify with a Laravel backend.

## Dependencies

 - Laravel 5.4
 - PHP 5.6.4 or higher
 - RDBMS (MySQL, Postgres

## How to setup

 1. Run `composer install` to install all dependences
 2. Create the `.env` file and put the appropriate content in (ask Mario)
 3. Run `./artisan migrate` to create the necessary DB tables
 4. Run `./artisan db:seed` to create the initial DB data
 5. Run `./artisan serve` to startup the development webserver

## Creating and modifying database tables
 
To create or modify database tables, please use Laravel migrations. It is not efficient
to edit databases or tables directly and then share SQL snippets.

To create a new migration simply run: `./artisan make:migration`

For a full list of commands and other information, please read the official documentation: https://laravel.com/docs/5.4/migrations