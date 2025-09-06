# Command to view laravel.log file for configurations with Docker.
docker exec -it hitchhikers_app bash

cd /var/www/storage/logs
tail -f laravel.log