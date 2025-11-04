#!/bin/bash
# Initialize MongoDB Replica Set for Transactions
# This script can be run manually or as part of container startup

echo "Waiting for MongoDB to be ready..."
until mongosh --host mongodb --eval "db.adminCommand('ping')" --quiet > /dev/null 2>&1; do
    echo "Waiting for MongoDB..."
    sleep 2
done

echo "Checking replica set status..."
STATUS=$(mongosh --host mongodb --eval "try { rs.status(); print('INITIALIZED'); } catch(e) { print('NOT_INITIALIZED'); }" --quiet)

if [[ "$STATUS" == *"NOT_INITIALIZED"* ]]; then
    echo "Initializing replica set..."
    mongosh --host mongodb <<EOF
rs.initiate({
    _id: "rs0",
    members: [
        { _id: 0, host: "mongodb:27017" }
    ]
})
EOF
    echo "Replica set initialized!"
else
    echo "Replica set already initialized."
fi

