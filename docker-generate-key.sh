#Only run once.
docker exec -it hitchhikers_app php artisan key:generate
docker exec -it hitchhikers_app php artisan config:clear
docker exec -it hitchhikers_app php artisan cache:clear
# docker exec -it hitchhikers_app php artisan config:cache
docker compose down -v
docker compose up -d

#Wait for 15-20 seconds...
docker exec -it hitchhikers_app php artisan migrate