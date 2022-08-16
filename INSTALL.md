# Installation of fhooe-mongo-dock

## Get repo

Install it in any path on your computer.
Open Powershell (PS) or other Terminal (prompt my be different then).

```shell
git clone https://github.com/Digital-Media/fhooe-mongo-dock.git
```

## Docker

cd <path-to-hyp3ue_t2>
```
docker compose up -d
```
access mongodb via commandline in container
```
docker exec -it mongo /bin/bash -c mongo
```
access container mongo-express via commandline
```
docker exec -it mongo-express /bin/bash
```
access mongo-express vis browser: `http://localhost:8083`
or [download](https://www.mongodb.com/try/download/compass) and install MongoDB Compass for a GUI. 

## Cloud

Get a free MongoDB Atlas account or sign in with Google [HERE](https://www.mongodb.com/cloud/atlas/register).
