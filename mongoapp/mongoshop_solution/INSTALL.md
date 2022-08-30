# Installation of fhooe-mongo-dock

Open Powershell (PS) or other Terminal (prompt my be different then).

## Starting Docker

See [fhooe-mongo-dock](https://github.com/Digital-Media/fhooe-mongo-dock)

### Using Docker to get Repo

If you use private repos built by [Upper Austria University of Applied Sciences (FH Oberösterreich), Hagenberg Campus](https://www.fh-ooe.at/en/hagenberg-campus/).

```shell
docker exec -it mongoapp /bin/bash -c "cd /var/www/html && git clone https://github.com/Digital-Media/mongoshop.git"
```
```shell
docker exec -it mongoapp /bin/bash -c "cd /var/www/html/mongoshop && composer install && chmod -R 777 *"
```
```shell
docker exec -it mongoapp /bin/bash -c "cd /var/www/html/mongoshop && composer update"
```