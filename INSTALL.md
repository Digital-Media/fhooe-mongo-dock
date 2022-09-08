# Installation of fhooe-mongo-dock

## Get repo

Install it in any path on your computer.
Open Powershell (PS) or other Terminal (prompt may be different in this case).

```shell
git clone https://github.com/Digital-Media/fhooe-mongo-dock.git
```

## Docker

cd <path-to-fhooe-mongo-dock>
```
docker compose up -d
```
Access mongodb via commandline in container
```
docker exec -it mongodb /bin/bash -c "mongo"
```
Access container mongo-express via commandline
```
docker exec -it mongo-express /bin/bash
```
Access mongo-express vis browser: `http://localhost:8083`
or [download](https://www.mongodb.com/try/download/compass) and install MongoDB Compass for a GUI. 

Access mongoapp container if you faces installation issues.
```
docker exec -it mongoapp /bin/bash
```
### List connection to MongoDB 
mongo> 
  ```
  db.currentOp(true).inprog.reduce((accumulator, connection) => { ipaddress = connection.client ? connection.client.split(":")[0] : "Internal"; accumulator[ipaddress] = (accumulator[ipaddress] || 0) + 1; accumulator["TOTAL_CONNECTION_COUNT"]++; return accumulator; }, { TOTAL_CONNECTION_COUNT: 0 });
  ```
## Cloud

Get a free MongoDB Atlas account or sign in with Google [HERE](https://www.mongodb.com/cloud/atlas/register).
