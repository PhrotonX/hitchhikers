#Only run once.
docker exec -it hitchhikers_app php artisan key:generate
docker compose down -v
docker compose up -d