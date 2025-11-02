# Quick Start Guide

Follow these steps to get your Merchant Pay application running with Docker.

## Step 1: Install Docker (if not already installed)

### For Ubuntu/Debian:
```bash
# Install Docker
sudo apt update
sudo apt install -y docker.io docker-compose

# Start Docker service
sudo systemctl start docker
sudo systemctl enable docker

# Add your user to docker group (to run without sudo)
sudo usermod -aG docker $USER
# Log out and log back in for this to take effect
# Or run: newgrp docker
```

### For macOS/Windows:
Install Docker Desktop from https://www.docker.com/products/docker-desktop
- Docker Desktop includes both Docker and Docker Compose

### Verify Docker installation:
```bash
docker --version

# Check Docker Compose version (try both formats)
docker-compose --version  # v1 (legacy)
docker compose version     # v2 (plugin)
```

## Step 2: Navigate to Project Directory

```bash
cd /path/to/merchant_pay
```

## Step 3: Start the Application

### Option A: Using Docker Compose (Recommended)

**Docker Compose v1 (legacy):**
```bash
docker-compose up
```

**Docker Compose v2 (plugin):**
```bash
docker compose up
```

### Option B: Using the start script
```bash
chmod +x start.sh
./start.sh
```

The start script automatically detects your Docker Compose version and uses the appropriate command.

### Option C: Run in detached mode (background)

**Docker Compose v1:**
```bash
docker-compose up -d
```

**Docker Compose v2:**
```bash
docker compose up -d
```

## Step 4: Wait for Services to Start

The first time you run this, it will:
- Download Docker images (may take a few minutes)
- Install backend dependencies (Composer packages)
- Install frontend dependencies (npm packages)
- Run database migrations
- Set up the database
- Generate Laravel application key

**Watch the logs** - you'll know it's ready when you see:
- `Backend setup completed!`
- Frontend dev server running on port 5173
- All containers showing as "Up" (and MySQL showing "healthy")
- No error messages in the logs

**First run typically takes 5-10 minutes.** Subsequent runs are much faster due to caching.

## Step 5: Access the Application

Once all services are running, access:

- **🌐 Frontend Application:** http://localhost
- **🔧 Backend API:** http://localhost/api
- **✅ API Test Endpoint:** http://localhost/api/test
- **💻 Frontend Dev Server (direct):** http://localhost:5173
- **🗄️ phpMyAdmin:** http://localhost:8080
- **📊 Database:** localhost:3307

### Quick Test

Test if the API is working:
```bash
curl http://localhost/api/test
```

You should see:
```json
{"success":true,"message":"API is working"}
```

## Quick Commands

### View all running containers:

**Docker Compose v1:**
```bash
docker-compose ps
```

**Docker Compose v2:**
```bash
docker compose ps
```

### View logs:

**Docker Compose v1:**
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mysql
docker-compose logs -f nginx
```

**Docker Compose v2:**
```bash
# All services
docker compose logs -f

# Specific service
docker compose logs -f backend
docker compose logs -f frontend
docker compose logs -f mysql
docker compose logs -f nginx
```

### Stop the application:

**Docker Compose v1:**
```bash
docker-compose down
```

**Docker Compose v2:**
```bash
docker compose down
```

### Stop and remove all data (fresh start):

**Docker Compose v1:**
```bash
docker-compose down -v
```

**Docker Compose v2:**
```bash
docker compose down -v
```

### Restart a specific service:

**Docker Compose v1:**
```bash
docker-compose restart backend
docker-compose restart frontend
docker-compose restart nginx
```

**Docker Compose v2:**
```bash
docker compose restart backend
docker compose restart frontend
docker compose restart nginx
```

### Rebuild containers:

**Docker Compose v1:**
```bash
docker-compose up --build
```

**Docker Compose v2:**
```bash
docker compose up --build
```

## Troubleshooting

### If port 80 is already in use:

Edit `docker-compose.yml` and change:
```yaml
nginx:
  ports:
    - "8081:80"  # Use 8081 instead of 80
```

Then access at http://localhost:8081

### If containers fail to start:

**Docker Compose v1:**
```bash
# Check logs
docker-compose logs

# Ensure Docker is running
sudo systemctl status docker

# Check disk space
df -h

# Rebuild containers
docker-compose up --build
```

**Docker Compose v2:**
```bash
# Check logs
docker compose logs

# Ensure Docker is running
sudo systemctl status docker

# Check disk space
df -h

# Rebuild containers
docker compose up --build
```

### Permission issues:

**Docker Compose v1:**
```bash
# Fix Laravel storage permissions
docker-compose exec backend chmod -R 775 storage bootstrap/cache
docker-compose exec backend chown -R www-data:www-data storage bootstrap/cache
```

**Docker Compose v2:**
```bash
# Fix Laravel storage permissions
docker compose exec backend chmod -R 775 storage bootstrap/cache
docker compose exec backend chown -R www-data:www-data storage bootstrap/cache
```

### API returns 404:

1. Check backend container is running: `docker-compose ps backend` or `docker compose ps backend`
2. Test API: `curl http://localhost/api/test`
3. Restart nginx: `docker-compose restart nginx` or `docker compose restart nginx`

### Reset everything:

**Docker Compose v1:**
```bash
docker-compose down -v
docker-compose up --build
```

**Docker Compose v2:**
```bash
docker compose down -v
docker compose up --build
```

### Docker Compose version errors:

If you get errors about unsupported compose file version:
- See `UPGRADE_DOCKER_COMPOSE.md` for upgrade instructions
- Or use the `start.sh` script which handles version detection

## Next Steps

1. **Access phpMyAdmin** at http://localhost:8080 to verify database
   - Server: `mysql`
   - Username: `root` or `merchant_user`
   - Password: `root_password` or `merchant_password`

2. **Test the API** at http://localhost/api/test
   - Should return: `{"success":true,"message":"API is working"}`

3. **Open the frontend** at http://localhost and try logging in

4. **Check backend setup** - migrations should have created all tables

5. **View logs** to monitor application activity:
   ```bash
   docker-compose logs -f  # v1
   docker compose logs -f  # v2
   ```

## Database Credentials

- **Database:** `merchant_pay`
- **User:** `merchant_user`
- **Password:** `merchant_password`
- **Root Password:** `root_password`
- **Host:** `mysql` (from within containers) or `localhost` (from host)
- **Port:** `3306` (container) or `3307` (host)

## Docker Compose Version

This project works with **both Docker Compose v1 and v2**:

- **v1:** Uses `docker-compose` (with hyphen) - legacy standalone
- **v2:** Uses `docker compose` (with space) - modern plugin

The `start.sh` script automatically detects which version you have. All commands in this guide show both versions.

## Need Help?

- **Detailed Setup:** See `DOCKER_SETUP.md` for comprehensive documentation
- **Upgrade Docker Compose:** See `UPGRADE_DOCKER_COMPOSE.md` if you need to upgrade
- **Main README:** See `README.md` for full project documentation

## Common Issues

### "docker-compose: command not found"
Install Docker Compose:
```bash
sudo apt install docker-compose  # Ubuntu/Debian
```

### "Version in docker-compose.yml is unsupported"
Upgrade Docker Compose (see `UPGRADE_DOCKER_COMPOSE.md`)

### Frontend shows blank page
- Check frontend container: `docker-compose ps frontend` or `docker compose ps frontend`
- View frontend logs: `docker-compose logs frontend` or `docker compose logs frontend`
- Ensure Vite dev server started successfully

### Backend API not responding
- Check backend container is running
- Check nginx is running and routing correctly
- Test direct API: `curl http://localhost/api/test`
- Restart services: `docker-compose restart backend nginx` or `docker compose restart backend nginx`
