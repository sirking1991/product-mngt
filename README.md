# Product Management

A sample app that handles CRUD of products.

It uses PHP/Lumen/MySql on the backend. Uses Microservice architecture and exposing REST endpoints.

Anglar7/Bootstrap on the frontend that consumes RESTFul API

Requires:
- PHP7.x
- MySQL
- composer
- Angular CLI 7.x

Setup and run the backend:
- cd to backend
- composer install
- php -S localhost:8000 -t public

Setup and run the frontend:
- cd to frontend
- ng i
- ng serve --port 8001