# Getting Started into Contribution
Before you start, please clone or download this repository from GitHub.
After 

## If you don't want to use Docker
Please make sure that the following dependencies and software are installed:
- Git (account needed)
- PHP
- Composer
- Node.js
- Vite
- VS Code or any other code editors

After installation and configuration, open the root folder of the repository, run VS Code, open a terminal and run the following commands:
```
php artisan serve
```
and
```
npm run dev
```

## If you want to use DOcker
Please make sure that the following dependencies and software are installed:
- Git (account needed)
- WSL2 or Windows Subsystem for Linux (Optional, but recommended for performance)
- Docker
- VS Code or any other code editors

After installation and configuration, clone or move the cloned repository within home directory of WSL2 and run the commands that is available in [this file](../docker-rebuild.sh).

If you are on production, please be sure to change the APP_ENV of [docker-compose file](../docker-compose.yaml) into **production** (case-sensitive). Otherwise, if you are on development, please make sure it is set on **development**.

After building the Docker container, run the following command:
```
docker compose up -d
```
This command will be used to run the Docker containers and their services. There is no need to re-run the [docker-rebuild file](../docker-rebuild.sh) unless an update or a change for Docker container is needed.

**Note**
- All commands that are needed to configure or use CLI applications within the website requires appending the following command:
```
docker exec -it [service_name] [command]
```
in which **service_name** is either:
- **hitchhike_app** for Laravel
- **hitchhike_web** for Nginx
- **hitchhike_db** for MySQL
- **hitchhike_node** for NodeJS and npm

***Example***
```
docker exec -it hitchhike_app php artisan serve
docker exec -it hitchhike_node npm run dev
docker exec -it hitchhike_db mysql -u root -p
```

## If you do not want to use Git
Please contact the main developer in order to contribute.

## After configuration
After the preceeding steps, open (http://localhost:8000/)[http://localhost:8000] to view the website.

If a database connection error has occured, please re-run the following commands:
**Non-docker**
```
php artisan migrate
```
**With Docker**
```
docker exec -it hitchhikers_db php artisan migrate
```

If error persists, consult the main developer.