#!/bin/bash
SCRIPTPATH="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
DB_NAME="$SCRIPTPATH/../data.db"
SQL_FILE="$SCRIPTPATH/structure.sql"

echo "Initializing database..."
sqlite3 "$DB_NAME" < "$SQL_FILE"

echo "Current tables:"
sqlite3 "$DB_NAME" ".tables"
