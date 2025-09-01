docker compose up -d
docker exec -it hitchhikers_node npm run build
docker exec -it hitchhikers_node npm run dev