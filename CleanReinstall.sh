docker compose down
docker image prune -a
docker volume rm --force fhooe-mongo-dock_mongodata;
docker compose up -d
