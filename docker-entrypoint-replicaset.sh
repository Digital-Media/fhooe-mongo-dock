#!/usr/bin/env bash
# Init script to initialize MongoDB replica set
# This script runs in a separate init container

set -e

echo "Waiting for MongoDB to be ready..."
until mongosh --host mongodb --eval "db.adminCommand('ping')" --quiet > /dev/null 2>&1; do
  echo "Waiting for MongoDB..."
  sleep 2
done

echo "MongoDB is ready! Checking replica set status..."

# Check if replica set is already initialized
RS_STATUS=$(mongosh --host mongodb --eval "try { rs.status(); print('INITIALIZED'); } catch(e) { print('NOT_INITIALIZED'); }" --quiet 2>&1 || echo "NOT_INITIALIZED")

if [[ "$RS_STATUS" == *"NOT_INITIALIZED"* ]]; then
  echo "Initializing replica set..."
  mongosh --host mongodb --eval "
    rs.initiate({
      _id: 'rs0',
      members: [
        { _id: 0, host: 'mongodb:27017' }
      ]
    })
  " --quiet
  
  echo "Waiting for replica set to stabilize..."
  sleep 5
  
  # Verify initialization
  RS_CHECK=$(mongosh --host mongodb --eval "try { var s = rs.status(); print(s.ok); } catch(e) { print('0'); }" --quiet 2>&1 | head -1 || echo "0")
  if [[ "$RS_CHECK" == "1" ]]; then
    echo "✅ Replica set initialized successfully!"
  else
    echo "⚠️ Replica set may need a moment to stabilize"
  fi
else
  echo "✅ Replica set already initialized"
fi

