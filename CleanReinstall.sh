docker compose down
docker image prune
docker volume rm --force fhooe-mongo-dock_mongodata;
docker compose up -d