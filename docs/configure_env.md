# Configure environment variables

Environment variables are needed for environment-specific configurations. Each computer may differ in configurations that are needed to run this website properly such as the type and version of database installed, the credentials needed to connect to a database, among others.

Without .env files, it is still possible to build a website but it also means that it will directly expose private credentials to the source code such as database user and passwords.

To begin, copy ```.env.example``` file and rename it into ```.env``` then proceed with the next steps.

**Note:**
- Always keep your environment variables private, particularly your sensitive credentials such as app key, user and passwords. Many of environment variables listed in .env file are considered as sensitive information.
- Do not add your .env to Git. These files are tracked on .gitignore file which prevents them from being exposed to the Git repository.
- If you have Docker installed, you might not need to modify many of .env configurations with some exceptions such as the need to set APP_KEY. 

**Important:**
- You need to [Set up APP_KEY](#setting-up-app_key) before continuing with configurations at [Getting Started into Contribution](get_started.md). After configuring, you need to set up [APP_KEY](#setting-up-app_key) and [database credentials](#setting-up-the-database-credentials).

## Setting up APP_ENV
APP_ENV is used to change the behavior of the website depending on the type of environment where the website is deployed. The APP_ENV variable can have the following values:
| Value | Description |
| ----- | ----------- |
| **local** | Local development. Use if currently developing for the website and locally deploying the website. |
| **testing** | Use if the website is under rigorous, internal testing. It is locally deployed. This kind of testing falls under alpha testing. |
| **staging** | Use if the website is under pre-release where the website is deployed on a real server and available to a limited amount of users. This kind of testing falls under beta testing. |
| **production** | Use if the website is under production, where the website is deployed on a real server and publicly available. |

**Note:**
- These values are different from docker-compose.yaml and Dockerfile. In Dockerfile, **development** is used if APP_ENV is under **local** and **testing**. Whereas, **production** is used when APP_ENV is under **staging** and **production**.

## Setting up APP_KEY
You may only generate keys once unless the .env file has been lost. You may lose these files after creating another GitHub codespace or cloning the repository.
### I do not use Docker
If you do not have Docker installed, you might not need to modify many of .env configurations with some exceptions such as the need to set APP_KEY. You can append an APP_KEY value by running [nondocker-generate-key.sh](../nondocker-generate-key.sh) or by running the commands below:
```
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```
Then manually restart the server by pressing Ctrl+C on terminal where Laravel is running and then run a migration:
```
php artisan migrate
```

### I am using Docker
You can append an APP_KEY value by running [docker-generate-key.sh](../docker-generate-key.sh) or by running the commands below:
```
docker exec -it hitchhikers_app php artisan key:generate
docker exec -it hitchhikers_app php artisan config:clear
docker exec -it hitchhikers_app php artisan cache:clear
docker exec -it hitchhikers_app php artisan config:cache
docker compose down -v
docker compose up -d
```

Then run a migration:
```
docker exec -it hitchhikers_app php artisan migrate
```

**Note:**
 - Check for possible duplicates within APP_KEY.

## Setting up the Database Credentials
### I do not use Docker
If you are not on Docker, please open your DBMS and obtain your connection information such as your user account, password, and port number.
```ini
DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
DB_HOST=#The IP address for the database such as localhost
DB_PORT=3306 #3306-3310
DB_DATABASE=hitchhikers
DB_USERNAME=#Your DB username here
DB_PASSWORD=#Your DB password here. Can be blank.
```

### I am using Docker
Keep the environment variables for the database unchanged.