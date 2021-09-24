# symfony
A blog app made with Sensio Labs' Symfony PHP Framework

This app is developped with and uses some bundles for the different features.




## To install all the bundles and features

Make sure you already have composer and Symfony installed and in your favorite terminal

```shell
composer install
```
## Do not forget to modify .env file to your needs

```php
 DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```
## You can now create the database

```shell
symfony console doctrine:database:create

or

 php bin/console doctrine:database:create
```
## You are now able to load the migrations

```shell
symfony console doctrine:migrations:migrate
```

## Now that the database is created you can import the fixtures added to the project

```shell
symfony console doctrine:fixtures:load
```

This should load a set of fake data to your database and make the website enjoyable.

For the admin account :

```
user: remz.duze@mail.com
password: 123456admin
```

# ENJOY


## This app is supposed to look like this:

![mockup](mockup.png)

if it doesn't, 

"fly you fools"

-- <cite>Gandalf the Grey, The FellowShip Of The Ring</cite>