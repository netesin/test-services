Order and services test
=======================

Clone project 

    git clone https://github.com/netesin/test-services test

Composer

    composer install

Create database schema

    bin/console doctrine:schema:create

Run local dev server

    bin/console server:start
    
    
By default its run on http://127.0.0.1:8000