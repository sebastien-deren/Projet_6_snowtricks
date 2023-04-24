# Projet_6_snowtricks

Projet 6 d'openclassrooms, création d'un site communautaire sur le snowboard avec symfony

## Prerequisites
- Docker
- Docker compose
- PHP 8.2

*All the command below are to be used in the root folder of the project.*

## Setup:
If you have make installed:  
- simply use `make install`    
  :warning: docker can take some time to create the DB, if you run into some error, fallback to the step by step setup :warning:

### Step by step installation:
-start the symfony project with :  
`docker compose up -d`  
`composer install`  
`symfony serve -d`  



-then create the database and the migrations.
`symfony console doctrine:database:create --if-not-exists`  
`symfony console doctrine:migration:migrate`  

-finally load the fixtures with:  
`symfony console doctrine:fixtures:load`  

## Usage:

To acess the site just type "localhost:8000" in your favorite web browser, or use `symfony open:local`.  

To acess the mail service use `symfony open:local:webmail`  
- if you plan to use the mail service don't forget to make symfony consume the asynchronous messages with  
`symfony console messenger:consume async`  
 
You can create new User within our site , if you're in development you can use one of our fake users to connect,  

> sebastienD  
> sebastien

>green  
>MotDePasse


