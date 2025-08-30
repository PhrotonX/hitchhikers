#Only install if neeed.
docker exec -it hitchhikers_app composer install
docker exec -it hitchhikers_node npm install

docker exec -it hitchhikers_app php artisan migrate
docker exec -it hitchhikers_app php artisan storage:link