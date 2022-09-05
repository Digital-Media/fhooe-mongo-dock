# Installation of fhooe-mongo-dock

Open Powershell (PS) or other Terminal (prompt my be different then).

## Starting Docker

See [fhooe-mongo-dock](https://github.com/Digital-Media/fhooe-mongo-dock)

### Using Docker to get Repo

```shell
docker exec -it mongoapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/mongoshop_solution.git"
```
```shell
docker exec -it mongoapp /bin/bash -c "cd /var/www/html/mongoshop_solution && composer install && chmod -R 777 *"
```
```shell
docker exec -it mongoapp /bin/bash -c "cd /var/www/html/mongoshop_solution && composer update"
```

## Cloud

See [MonogShop](https://github.com/Digital-Media/mongoshop/blob/main/INSTALL.md#cloud).