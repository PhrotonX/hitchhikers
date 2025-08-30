# Getting Started into Contribution
This website uses Docker for containing the website itself and its dependenciesa to ensure that all of the components used to build the website is consistent among all machines. It also uses Git and GitHub for version control system and code collaboration.

**Notes:**
- This article assumes that you are using Windows 10 or 11 PC.
- To run a .sh file on Linux systems, type ```chmod +x scriptname.sh``` if permissions are not set and then type ```./scriptname.sh``` 
- If you cannot run a command within .sh files, simply copy all of its contents, paste it into terminal, then press ENTER to execute.

## If you do not want to use Docker and Git
Please make sure that the following dependencies and software are installed, configured, and tested:
- Git (account needed)
- PHP
- MySQL
- Composer
- Node.js
- VS Code or any other code editors

After installation and configuration, [configure environment variables](../docs/configure_env.md)

Then, run the [nondocker-rebuild.sh](../nondocker-rebuild.sh) file to configure the frameworks.

Before visiting the website, please continue [configuring environment variables](configure_env.md).

Then, open the root folder of the repository, run VS Code, open a terminal and run the following commands:

```
php artisan serve
```
and
```
npm run dev
```

To visit the website, open [http://localhost:8000/](http://localhost:8000).

## If you want to use Docker and Git
There are two ways to get started with contribution with Docker.

### Use GitHub Codespace (VS Code on the web)
You can use GitHub Codespace to build the website through a VS Code integrated within a web browser.

First, visit the https://github.com/PhrotonX/hitchhikers/ page, choose the right branch (preferably dev), then Code > Codespaces > Create a codespace.

After this, skip to [Configuring Docker](#configuring-docker) section.

### Use Local VS Code
Alternatively, you can use the local installation of VS Code into your machine and clone the git repository by doing the following steps:

**But**, before continuing, please make sure that the following dependencies and software are installed:
- Git (account needed)
- WSL2 or Windows Subsystem for Linux (Optional, but recommended for performance)
- Docker
- VS Code or any other code editors

After installation and configuration, go into the folder or directory where you wanted to save the codebase or the repository and then run the following command to clone the repository:
```
git clone git@github.com:PhrotonX/hitchhikers.git
```

(Optional) If you have an installation of WSL2, move the cloned repository within home ```~``` directory of your WSL2 distro. This step is recommended in order to improve the website performance.

### Configuring Docker
First, [configure environment variables](../docs/configure_env.md).

Run the commands that is available in [docker-rebuild-batch-1.sh](../docker-rebuild-batch-1.sh) and then [docker-rebuild-batch-2.sh](../docker-rebuild-batch-2.sh) to build docker containers to be run.

If you are on production, please be sure to change the APP_ENV of [docker-compose file](../docker-compose.yaml) into **production** (case-sensitive). Otherwise, if you are on development, please make sure it is set on **development**.

After building the Docker container, run the following command on [docker.run.sh](../docker-run.sh):
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

Before visiting the website, please continue [configuring environment variables](configure_env.md).

To visit the website with Local VS Code, open [http://localhost:8000/](http://localhost:8000). ```php artisan serve``` is already run after running the built docker containers.

To visit the website with Github Codespaces, Press Ctrl+\` > Ports > Hover port 8000 > Ctrl+Click or Click "Open in Browser" button.

## If you do not want to use Git
Please contact the main developer in order to contribute.

## After configurations
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