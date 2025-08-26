docker compose down -v --rmi all --remove-orphans
docker builder prune -af
docker compose up --build

docker exec -it hitchhikers_app php artisan storage:link
docker exec -it hitchhikers_app php artisan migrate