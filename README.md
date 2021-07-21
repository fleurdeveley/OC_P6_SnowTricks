# OC_P6_SnowTricks

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5a451dd063364417bfce07175fbed8e2)](https://www.codacy.com/gh/fleurdeveley/OC_P6_SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=fleurdeveley/OC_P6_SnowTricks&amp;utm_campaign=Badge_Grade)

## Description of the project
  * As a part of study project, creation of a community snowboard site.

## Technologies
  * PHP 7.4
  * Symfony 5.3
  * Composer 1.10.1
  * Bootstrap 5.0
  * MVC
  * GitHub

## PHP Dependencies
  * "cocur/slugify": "^4.0",
  * "symfony/asset": "5.3.*",
  * "symfony/mailer": "5.3.*",
  * "symfony/security-bundle": "5.3.*",
  * "symfony/form": "5.3.*",
  * "symfony/validator": "5.3.*",

## Source
 1. Clone the GitHub repository :
```
  git clone https://github.com/fleurdeveley/OC_P6_SnowTricks.git
```

## Installation
 2. Enter the project file :
```
  cd OC_P6_Snowtricks
```

 3. Copy .env files : 
```
  cp .env.example .env
```
```
  cp app/html/.env app/html/.env.local
```

 4. Replace the line 
```
  DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
``` 
by 
```
  DATABASE_URL="mysql://admin:password@project6_mysql:3306/project6?serverVersion=5.7" 
```
and configure your smtp server on the line 
```
  MAILER_DSN=smtp://user:password@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login
```

 5. Create the docker network
```
  docker network create project6
```

 6. Launch the containers
```
  docker-composer up -d
```

 7. Enter the PHP container to launch the commands for the database
```
  docker exec -ti [nom du container php] bash
```

 8. Install php dependencies with composer
```
  composer install
```

 9. Install the database
```
  php bin/console doctrine:migrations:migrate
```

 10. Install the fixture (dummy data demo)
```
  php bin/console doctrine:fixtures:load
```

 11. Leave the container
```
  exit
```

## Database
  * Connection to PHPMyAdmin : http://localhost:8081
  * Server : project6_mysql
  * User : admin
  * Password : password

## Access to the project
  * http://localhost:8080
  * Login : user1@gmail.com
  * Password : password
