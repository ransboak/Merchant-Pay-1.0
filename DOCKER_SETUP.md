# Docker Setup Guide

This guide will help you run the entire Merchant Pay application using Docker.

## Prerequisites

- Docker (version 20.10 or higher)
- Docker Compose (any version - v1 or v2)

**Note:** This guide shows commands for both Docker Compose v1 (`docker-compose`) and v2 (`docker compose`). Use whichever applies to your installation.

## Quick Start

1. **Start all services:**

   **Docker Compose v1 (legacy):**
   ```bash
   docker-compose up
   ```
   
   **Docker Compose v2 (plugin):**
   ```bash
   docker compose up
   ```
   
   Or run in detached mode:
   ```bash
   docker-compose up -d  # v1
   docker compose up -d  # v2
   ```

2. **Wait for services to be ready** (first run may take a few minutes):
   - MySQL database initialization
   - Backend setup (Composer install, migrations, etc.)
   - Frontend dependencies installation

3. **Access the application:**
   - Frontend: http://localhost
   - Backend API: http://localhost/api
   - API Test: http://localhost/api/test
   - Frontend Dev Server (direct): http://localhost:5173
   - phpMyAdmin: http://localhost:8080
   - MySQL (if needed): localhost:3307

## Services

The Docker setup includes:

1. **MySQL Database** (`mysql`)
   - Database: `merchant_pay`
   - User: `merchant_user`
   - Password: `merchant_password`
   - Root Password: `root_password`
   - Port: `3307` (host) / `3306` (container)

2. **Laravel Backend** (`backend`)
   - PHP 8.2 with FPM
   - Runs on port 9000 (internal, PHP-FPM)
   - Automatically sets up dependencies and migrations
   - Volume mounted for live code updates

3. **Vue.js Frontend** (`frontend`)
   - Node.js 18 (Alpine)
   - Vite dev server on port 5173
   - Hot reload enabled
   - Volume mounted for live code updates

4. **Nginx** (`nginx`)
   - Reverse proxy
   - Routes `/api` to backend (Laravel)
   - Routes `/` to frontend (Vite)
   - Port 80 (HTTP) and 443 (HTTPS)

5. **phpMyAdmin** (`phpmyadmin`)
   - Web-based MySQL administration tool
   - Access at http://localhost:8080
   - Pre-configured to connect to MySQL container
   - Server: `mysql`
   - Username: `root` or `merchant_user`
   - Password: `root_password` or `merchant_password`

6. **Backend Setup** (`backend-setup`)
   - One-time initialization service
   - Runs as root for permission setup
   - Installs Composer dependencies
   - Generates Laravel application key
   - Runs database migrations
   - Sets proper file permissions

## Useful Commands

### View logs

**Docker Compose v1:**
```bash
docker-compose logs -f
```

**Docker Compose v2:**
```bash
docker compose logs -f
```

### View logs for specific service

**Docker Compose v1:**
```bash
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f nginx
docker-compose logs -f mysql
```

**Docker Compose v2:**
```bash
docker compose logs -f backend
docker compose logs -f frontend
docker compose logs -f nginx
docker compose logs -f mysql
```

### Stop all services

```bash
docker-compose down  # v1
docker compose down  # v2
```

### Stop and remove volumes (clean slate)

```bash
docker-compose down -v  # v1
docker compose down -v  # v2
```

### Rebuild containers

```bash
docker-compose up --build  # v1
docker compose up --build  # v2
```

### Execute commands in containers

**Backend (Laravel):**

**Docker Compose v1:**
```bash
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan tinker
docker-compose exec backend composer install
docker-compose exec backend php artisan config:clear
docker-compose exec backend php artisan cache:clear
```

**Docker Compose v2:**
```bash
docker compose exec backend php artisan migrate
docker compose exec backend php artisan tinker
docker compose exec backend composer install
docker compose exec backend php artisan config:clear
docker compose exec backend php artisan cache:clear
```

**Frontend:**

**Docker Compose v1:**
```bash
docker-compose exec frontend npm install
docker-compose exec frontend npm run build
```

**Docker Compose v2:**
```bash
docker compose exec frontend npm install
docker compose exec frontend npm run build
```

**MySQL:**

**Docker Compose v1:**
```bash
docker-compose exec mysql mysql -u merchant_user -pmerchant_password merchant_pay
```

**Docker Compose v2:**
```bash
docker compose exec mysql mysql -u merchant_user -pmerchant_password merchant_pay
```

**phpMyAdmin:**
Access via browser at http://localhost:8080
- Server: `mysql` (pre-filled)
- Username: `root` or `merchant_user`
- Password: `root_password` or `merchant_password`

### Access container shell

**Docker Compose v1:**
```bash
docker-compose exec backend bash
docker-compose exec frontend sh
docker-compose exec mysql bash
docker-compose exec nginx sh
```

**Docker Compose v2:**
```bash
docker compose exec backend bash
docker compose exec frontend sh
docker compose exec mysql bash
docker compose exec nginx sh
```

### View running containers

```bash
docker-compose ps  # v1
docker compose ps  # v2
```

### Restart services

```bash
docker-compose restart  # v1 (restart all)
docker compose restart  # v2 (restart all)

docker-compose restart backend  # v1 (specific service)
docker compose restart backend  # v2 (specific service)
```

## Environment Variables

### Backend Environment
The backend uses environment variables defined in `docker-compose.yml`. Key variables:
- `DB_HOST=mysql`
- `DB_PORT=3306`
- `DB_DATABASE=merchant_pay`
- `DB_USERNAME=merchant_user`
- `DB_PASSWORD=merchant_password`
- `APP_ENV=local`
- `APP_DEBUG=true`
- `APP_URL=http://localhost`

You can override them by creating a `.env` file in the `backend` directory.

### Frontend Environment
The frontend uses `VITE_API_URL` which is set to `http://localhost/api` in `docker-compose.yml`.

## Troubleshooting

### Port conflicts
If ports 80, 5173, 3307, or 8080 are already in use, modify the port mappings in `docker-compose.yml`:

```yaml
nginx:
  ports:
    - "8081:80"  # Change 80 to 8081

frontend:
  ports:
    - "5174:5173"  # Change 5173 to 5174

mysql:
  ports:
    - "3308:3306"  # Change 3307 to 3308

phpmyadmin:
  ports:
    - "8081:80"  # Change 8080 to 8081
```

### Permission issues
If you encounter permission issues with Laravel storage:

**Docker Compose v1:**
```bash
docker-compose exec backend chmod -R 775 storage bootstrap/cache
docker-compose exec backend chown -R www-data:www-data storage bootstrap/cache
```

**Docker Compose v2:**
```bash
docker compose exec backend chmod -R 775 storage bootstrap/cache
docker compose exec backend chown -R www-data:www-data storage bootstrap/cache
```

### Database connection errors
- Wait a few seconds after starting containers for MySQL to be fully ready
- Check MySQL health: `docker-compose ps` or `docker compose ps` (look for "healthy" status)
- View MySQL logs: `docker-compose logs mysql` or `docker compose logs mysql`
- Ensure MySQL container shows as "Up" and "(healthy)"

### API returns 404
- Check backend container is running: `docker-compose ps backend` or `docker compose ps backend`
- Test API: `curl http://localhost/api/test`
- Restart nginx: `docker-compose restart nginx` or `docker compose restart nginx`
- Check nginx configuration is correctly mounted

### Rebuild after code changes
If you make significant changes to Dockerfiles or dependencies:

**Docker Compose v1:**
```bash
docker-compose down
docker-compose build --no-cache
docker-compose up
```

**Docker Compose v2:**
```bash
docker compose down
docker compose build --no-cache
docker compose up
```

### Frontend not loading
- Check frontend container logs: `docker-compose logs frontend` or `docker compose logs frontend`
- Ensure Vite dev server is running (should see "ready in" message)
- Restart frontend: `docker-compose restart frontend` or `docker compose restart frontend`

### Complete reset
If nothing works, perform a complete reset:

**Docker Compose v1:**
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up --build
```

**Docker Compose v2:**
```bash
docker compose down -v
docker compose build --no-cache
docker compose up --build
```

## First Time Setup

On the first run, the `backend-setup` service will:
1. Set proper file permissions (runs as root)
2. Install Composer dependencies
3. Generate Laravel application key
4. Clear configuration and cache
5. Run database migrations

The setup service runs once and exits (restart: "no" in docker-compose.yml).

Subsequent runs will use cached volumes for faster startup.

## Development Workflow

1. Make code changes in `backend/` or `frontend/` directories
2. Changes are reflected immediately (volumes are mounted)
3. Frontend has hot reload enabled via Vite
4. Backend changes may require clearing cache:

   **Docker Compose v1:**
   ```bash
   docker-compose exec backend php artisan config:clear
   docker-compose exec backend php artisan cache:clear
   ```

   **Docker Compose v2:**
   ```bash
   docker compose exec backend php artisan config:clear
   docker compose exec backend php artisan cache:clear
   ```

## API Endpoints

Once running, test the API:
- **Test endpoint:** http://localhost/api/test
- **All routes:** Check `backend/routes/api.php` for available endpoints

Common endpoints:
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `GET /api/merchants` - List merchants
- `POST /api/merchants` - Create merchant
- `GET /api/transactions` - List transactions
- And more...

## Production Considerations

This setup is optimized for development. For production:
- Use production-ready PHP configuration
- Build frontend assets instead of running dev server
- Use proper SSL certificates
- Set secure passwords
- Disable debug mode (`APP_DEBUG=false`)
- Use proper volume management
- Consider using Docker secrets for sensitive data
- Set up proper backup strategy for MySQL volumes
- Use environment-specific `.env` files
- Enable PHP OPcache for better performance

## Docker Compose Version Compatibility

This project uses `version: '3.8'` in `docker-compose.yml`, which is supported by:
- Docker Compose v1.25.0+ (standalone)
- Docker Compose v2.0.0+ (plugin)

The commands work with both versions - just use the appropriate syntax:
- **v1:** `docker-compose` (with hyphen)
- **v2:** `docker compose` (with space)

If you encounter version errors, see `UPGRADE_DOCKER_COMPOSE.md` for upgrade instructions.
