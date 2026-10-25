#!/usr/bin/env bash
# One-time fix: syncs the postgres 'unionos' user password to match docker-compose.yml
# Run as a user with docker access:  sudo bash scripts/db-fix.sh
set -euo pipefail

CONTAINER="unionos_postgres"
DB_USER="unionos"
DB_NAME="unionos"
DB_PASS="957dac96cb58d06515fea4dc"
PROJECT_DIR="$(cd "$(dirname "$0")/.." && pwd)"

echo "==> Checking container state..."
STATUS=$(docker inspect --format '{{.State.Status}}' "$CONTAINER" 2>/dev/null || echo "missing")

case "$STATUS" in
  running)
    echo "    Container is running."
    ;;
  exited|created|paused)
    echo "    Container exists but is stopped (${STATUS}) — starting it..."
    docker start "$CONTAINER"
    ;;
  missing)
    echo "    Container not found — bringing up with docker compose (no-recreate)..."
    docker compose -f "${PROJECT_DIR}/docker-compose.yml" up -d --no-recreate postgres
    ;;
esac

echo "==> Waiting for postgres to accept connections..."
for i in $(seq 1 30); do
  if docker exec "$CONTAINER" pg_isready -U "$DB_USER" >/dev/null 2>&1; then
    echo "    Ready."
    break
  fi
  echo "    Waiting... (${i}/30)"
  sleep 2
done

echo "==> Resetting password for role '${DB_USER}'..."
docker exec "$CONTAINER" psql -U "$DB_USER" -c \
  "ALTER USER ${DB_USER} WITH PASSWORD '${DB_PASS}';"
echo "    Password updated."

echo "==> Verifying connection with new password..."
docker exec "$CONTAINER" env PGPASSWORD="$DB_PASS" \
  psql -U "$DB_USER" -d "$DB_NAME" -c '\conninfo'
echo "    Connection verified."

echo ""
echo "==> Clearing Laravel config cache..."
cd "$PROJECT_DIR"
php artisan config:clear

echo ""
echo "==> Running migrations..."
php artisan migrate --force

echo ""
echo "Done. Database is authenticated and migrations are up to date."
