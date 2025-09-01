# Full auto-installer for docker container

# Batch 1
cp -p .env.example .env

docker compose down -v --rmi all --remove-orphans
docker builder prune -af
# docker compose up --build
docker compose build
docker compose up -d

PID=$!
wait $PID

# Batch 2
docker exec -it hitchhikers_app composer install
docker exec -it hitchhikers_node npm install

docker exec -it hitchhikers_app php artisan migrate
docker exec -it hitchhikers_app php artisan storage:link
PID=$!
wait $PID

# Batch 3 (Key generation)
docker exec -it hitchhikers_app php artisan key:generate
docker exec -it hitchhikers_app php artisan config:clear
docker exec -it hitchhikers_app php artisan cache:clear
docker exec -it hitchhikers_app php artisan config:cache
docker compose down -v
docker compose up -d

PID=$!
wait $PID

echo "Please wait..."
sleep 15

# Migrate
docker exec -it hitchhikers_app php artisan migrate