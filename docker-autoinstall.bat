@echo off
REM ===========================
REM Full auto-installer for docker container
REM ===========================

REM Batch 1
copy /Y .env.example .env

docker compose down -v --rmi all --remove-orphans
docker builder prune -af
REM docker compose up --build
docker compose build
docker compose up -d

echo Please wait...
timeout /t 15 >nul

REM Batch 2
docker exec -it hitchhikers_app composer install
docker exec -it hitchhikers_node npm install

docker exec -it hitchhikers_app php artisan migrate
docker exec -it hitchhikers_app php artisan storage:link

echo Please wait...
timeout /t 20 >nul

REM Batch 3 (Key generation)
docker exec -it hitchhikers_app php artisan key:generate
docker exec -it hitchhikers_app php artisan config:clear
docker exec -it hitchhikers_app php artisan cache:clear
docker exec -it hitchhikers_app php artisan config:cache

docker compose down -v
docker compose up -d

echo Please wait...
timeout /t 15 >nul

REM Migrate
docker exec -it hitchhikers_app php artisan migrate
