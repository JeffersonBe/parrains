# Parrains

## Description
When you are a new student in a school, you're looking for someone to help, a sort of godfather right? Then with parrains it's now possible to find your godfather or protege!

The first version of this application has been developed in the case of my student's body position. It's now update with laravel framework to be used easily and safely by anyone. Enjoy!

## Getting Started

### Install composer (globally)
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```
#### Install composer’s dependencies
```
composer install
```
### Install node

Go to [nodejs.org](http://nodejs.org) and follow the instructions.

#### Install Gulp (globally)
```
npm install --global gulp
npm install // on the root of the app
```

### Database and mail credentials
Then set your database credentials within an .env file at the root of the app as follow:
```
APP_ENV=local // change this if running in production
APP_DEBUG=true // hide this if running in production
APP_KEY=SomeRandomString

DB_HOST=localhost
DB_DATABASE=root
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=file
SESSION_DRIVER=file
```

### Migrate database
php artisan migrate

### Finally launch the app
On local
```
php artisan serve
```
On server
Point host to `public/` folder

## Todo
1. Handling form through controller
2. Sending mail to godfather and protege
3. Handling matching by
4. Create page to see all matching
