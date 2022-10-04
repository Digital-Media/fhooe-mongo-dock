docker compose down -f
docker image prune -a
docker volume prune -f;
docker compose up -d
