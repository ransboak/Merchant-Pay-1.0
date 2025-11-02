# Merchant Pay - Full Stack Payment Application

A full-stack merchant payment application built with Laravel (backend) and Vue.js (frontend), containerized with Docker for easy setup and deployment.

## ⚙️ Assumptions for Setup

Before setting up and running the **Merchant Pay - Full Stack Payment Application**, the following assumptions are made about your system and environment.  
If **any of these conditions are not met**, setup may fail or require **manual fixes**.

### 1. System Compatibility
- You are using one of the following supported operating systems:
  - **Windows 10/11** with **Docker Desktop** installed  
  - **macOS** (Intel or Apple Silicon) with **Docker Desktop** installed  
  - **Linux (Ubuntu/Debian preferred)** with **Docker Engine** and **Docker Compose** installed
- Your system supports **virtualization**, which is required by Docker.

### 2. Resource Availability
- Your system has at least:
  - **4 GB RAM** (recommended 8 GB for smoother builds)  
  - **2 CPU cores** allocated to Docker  
  - **3–5 GB** of free disk space for Docker images and containers

### 3. Network and Port Availability
- The following ports are free and available for use:
  - `80` → Nginx (Frontend proxy)  
  - `5173` → Frontend (Vite dev server)  
  - `3307` → MySQL (Host access)  
  - `8080` → phpMyAdmin  
- You have an **active internet connection** for pulling Docker images during the initial build.

### 4. User Privileges
- You have **administrator/root privileges** (Linux/macOS) or **Docker Desktop permissions** (Windows).
- On Linux, your user is added to the **`docker` group** to allow running Docker commands without `sudo`.

### 5. Environment Configuration
- The **Laravel backend** has a valid `.env` file in the `backend/` directory containing:
  - Database credentials (`DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, etc.)
  - Application URL (`APP_URL`)

### 6. Docker Setup
- The `docker-compose.yml` file exists in the project root (`merchant_pay/`).
- You are using a supported Docker Compose version:
  - **v1.25.0+** (legacy) or  
  - **v2.0.0+** (plugin-based, included with Docker Desktop)

### 7. First-Time Build Notes
- On first setup, Docker will:
  1. Pull and build required images (MySQL, PHP, Node, Nginx, phpMyAdmin)
  2. Install Laravel and Vue dependencies automatically
  3. Run migrations and generate an application key
- This process may take **5–10 minutes** depending on your network speed and hardware.
- All containers (backend, frontend, MySQL, Nginx, phpMyAdmin) must reach a **“healthy”** state before the app becomes fully accessible.

### 8. Optional but Recommended Tools
- **Git** — for cloning and version control  
- **Postman** or **Insomnia** — for testing API endpoints  
- **VS Code** or another modern editor — for development and debugging

---

> ⚠️ **Note:**  
> If any of these conditions are **not met**, the setup may fail or require **manual troubleshooting** to complete successfully.

## 🚀 Quick Start

Get the entire application running with a single command:

```bash
# For Docker Compose v1 (docker-compose with hyphen)
docker-compose up

# For Docker Compose v2 (docker compose with space)
docker compose up

# Or run in detached mode (background)
docker-compose up -d  # v1
docker compose up -d  # v2
```

The application will be available at:
- **Frontend:** http://localhost
- **Backend API:** http://localhost/api
- **phpMyAdmin:** http://localhost:8080
- **Frontend Dev Server:** http://localhost:5173

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Access Points](#access-points)
- [Docker Compose Versions](#docker-compose-versions)
- [Common Commands](#common-commands)
- [Troubleshooting](#troubleshooting)
- [Project Structure](#project-structure)
- [Environment Variables](#environment-variables)
- [Database Configuration](#database-configuration)

## Prerequisites

Before you begin, ensure you have the following installed:

1. **Docker** (version 20.10 or higher)
2. **Docker Compose** (any version - v1 or v2)

### Check Your Versions

```bash
# Check Docker version
docker --version

# Check Docker Compose version
docker-compose --version  # For v1
docker compose version    # For v2
```

## Installation

### Step 1: Install Docker

#### Ubuntu/Debian:

```bash
# Update package index
sudo apt update

# Install Docker
sudo apt install -y docker.io docker-compose

# Start Docker service
sudo systemctl start docker
sudo systemctl enable docker

# Add your user to docker group (to run without sudo)
sudo usermod -aG docker $USER

# Log out and log back in for the group change to take effect
# Or run: newgrp docker
```

#### macOS:

```bash
# Install Docker Desktop from https://www.docker.com/products/docker-desktop
# Docker Desktop includes both Docker and Docker Compose
```

#### Windows:

```bash
# Install Docker Desktop from https://www.docker.com/products/docker-desktop
# Docker Desktop includes both Docker and Docker Compose
```

### Step 2: Verify Installation

```bash
# Test Docker
docker --version
docker run hello-world

# Test Docker Compose
docker-compose --version  # If using v1
docker compose version    # If using v2
```

### Step 3: Clone the repository and navigate to the project

Follow one of the options below to clone the repo to your local machine.

Option A — Clone using HTTPS (recommended for most users):

```bash
# Example (this repository):
git clone https://github.com/ransboak/Merchant-Pay-1.0.git merchant-pay
cd merchant-pay
```

Option B — Clone using SSH (if you have SSH keys configured):

```bash
# Example (this repository):
git clone git@github.com:ransboak/Merchant-Pay-1.0.git merchant-pay
cd merchant-pay
```

Notes for Windows / PowerShell users:

- You can run the same `git` commands in PowerShell. If you'd like to clone into a specific directory, first change to that parent directory:

```powershell
# example: create and move into a projects folder, then clone
mkdir C:\Users\$env:USERNAME\projects; cd C:\Users\$env:USERNAME\projects
git clone https://github.com/ransboak/Merchant-Pay-1.0.git merchant-pay
cd merchant-pay
```

- If you don't have `git` installed, download and install it from https://git-scm.com/ and try the commands again.

After cloning, continue with the setup steps in this README (install dependencies, start Docker, etc.).

## Running the Application

### First Time Setup

The first time you run the application, Docker will:

1. Download required images (MySQL, PHP, Nginx, Node.js, phpMyAdmin)
2. Build custom images for backend and frontend
3. Install backend dependencies (Composer packages)
4. Install frontend dependencies (npm packages)
5. Run database migrations
6. Set up Laravel application key

**This may take 5-10 minutes on first run.**

### Start Commands

Choose the command based on your Docker Compose version:

```bash
# Docker Compose v1 (older versions)
docker-compose up

# Docker Compose v2 (newer versions, 2020+)
docker compose up

# Run in background (detached mode)
docker-compose up -d  # v1
docker compose up -d  # v2

# Build and start (force rebuild)
docker-compose up --build  # v1
docker compose up --build  # v2
```

### Using the Start Script

```bash
# Make script executable (one-time)
chmod +x start.sh

# Run the script
./start.sh
```

The script automatically detects your Docker Compose version and uses the appropriate command.

### Wait for Services to Start

Watch the logs for completion messages:

```
✓ Backend setup completed!
✓ Frontend dev server running on port 5173
✓ Nginx ready
✓ MySQL healthy
```

## Access Points

Once all services are running:

| Service | URL | Description |
|---------|-----|-------------|
| **Frontend Application** | http://localhost | Main application UI (proxied through Nginx) |
| **Backend API** | http://localhost/api | Laravel REST API |
| **API Test Endpoint** | http://localhost/api/test | Test API connectivity |
| **Frontend Dev Server** | http://localhost:5173 | Direct access to Vite dev server |
| **phpMyAdmin** | http://localhost:8080 | Database management interface |
| **MySQL Database** | localhost:3307 | Direct database connection |

## Docker Compose Versions

This project works with **both Docker Compose v1 and v2**. The key differences:

### Docker Compose v1 (Legacy)
- Command: `docker-compose` (with hyphen)
- Standalone Python-based tool
- May need separate installation: `sudo apt install docker-compose`

### Docker Compose v2 (Plugin)
- Command: `docker compose` (with space)
- Integrated into Docker CLI as a plugin
- Comes with Docker Desktop automatically

### How to Determine Your Version

```bash
# Try v2 first (newer)
docker compose version

# If that fails, try v1
docker-compose --version
```

**The project uses `version: '3.8'` in `docker-compose.yml`**, which is supported by:
- Docker Compose v1.25.0+
- Docker Compose v2.0.0+

### If You Get Version Errors

If you see an error like "Version in docker-compose.yml is unsupported":

**Option 1: Upgrade Docker Compose (Recommended)**

For Docker Compose v1:
```bash
# Check current version
docker-compose --version

# For Ubuntu/Debian, update:
sudo apt update
sudo apt install --only-upgrade docker-compose

# Or install latest:
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

For Docker Compose v2 (upgrade Docker):
```bash
# Docker Compose v2 comes with Docker Desktop
# Update Docker Desktop to get the latest version
```

**Option 2: Use Docker Compose v2 Plugin (Recommended)**

```bash
# Install Docker Compose plugin
sudo apt update
sudo apt install docker-compose-plugin

# Use it as: docker compose (with space)
docker compose version
```

## Common Commands

### View Running Containers

```bash
docker-compose ps  # v1
docker compose ps  # v2
```

### View Logs

```bash
# All services
docker-compose logs -f  # v1
docker compose logs -f  # v2

# Specific service
docker-compose logs -f backend  # v1
docker compose logs -f backend # v2

# Last 50 lines
docker-compose logs --tail=50  # v1
docker compose logs --tail=50 # v2
```

### Stop the Application

```bash
docker-compose down  # v1
docker compose down  # v2

# Stop and remove volumes (fresh start)
docker-compose down -v  # v1
docker compose down -v  # v2
```

### Restart Services

```bash
# Restart all
docker-compose restart  # v1
docker compose restart  # v2

# Restart specific service
docker-compose restart backend  # v1
docker compose restart backend  # v2
```

### Execute Commands in Containers

```bash
# Backend container
docker-compose exec backend bash  # v1
docker compose exec backend bash # v2

# Run Laravel artisan commands
docker-compose exec backend php artisan migrate  # v1
docker compose exec backend php artisan migrate # v2

# Frontend container
docker-compose exec frontend sh  # v1
docker compose exec frontend sh # v2
```

### Rebuild Containers

```bash
# Rebuild without cache
docker-compose build --no-cache  # v1
docker compose build --no-cache # v2

# Rebuild and restart
docker-compose up --build  # v1
docker compose up --build # v2
```

## Troubleshooting

### Port Already in Use

If port 80, 5173, 3307, or 8080 is already in use:

**Option 1: Stop the conflicting service**
```bash
# Find what's using port 80
sudo lsof -i :80
# Or
sudo netstat -tlnp | grep :80
```

**Option 2: Change ports in `docker-compose.yml`**
```yaml
nginx:
  ports:
    - "8081:80"  # Change 80 to 8081

frontend:
  ports:
    - "5174:5173"  # Change 5173 to 5174
```

### Containers Won't Start

```bash
# Check logs
docker-compose logs  # v1
docker compose logs  # v2

# Check if Docker is running
sudo systemctl status docker

# Restart Docker service
sudo systemctl restart docker
```

### Permission Denied Errors

```bash
# Fix Laravel storage permissions
docker-compose exec backend chmod -R 775 storage bootstrap/cache  # v1
docker compose exec backend chmod -R 775 storage bootstrap/cache # v2

docker-compose exec backend chown -R www-data:www-data storage bootstrap/cache  # v1
docker compose exec backend chown -R www-data:www-data storage bootstrap/cache # v2
```

### Database Connection Issues

```bash
# Check MySQL container status
docker-compose ps mysql  # v1
docker compose ps mysql # v2

# Check MySQL logs
docker-compose logs mysql  # v1
docker compose logs mysql # v2

# Wait for MySQL to be healthy
docker-compose ps  # Look for "(healthy)" status  # v1
docker compose ps  # Look for "(healthy)" status # v2
```

### API Returns 404

```bash
# Check backend container
docker-compose ps backend  # v1
docker compose ps backend # v2

# Test API directly
curl http://localhost/api/test

# Restart nginx
docker-compose restart nginx  # v1
docker compose restart nginx  # v2
```

### Frontend Not Loading

```bash
# Check frontend container
docker-compose logs frontend  # v1
docker compose logs frontend # v2

# Restart frontend
docker-compose restart frontend  # v1
docker compose restart frontend # v2
```

### Complete Reset

If nothing works, perform a complete reset:

```bash
# Stop and remove everything
docker-compose down -v  # v1
docker compose down -v  # v2

# Remove images (optional)
docker-compose down --rmi all  # v1
docker compose down --rmi all  # v2

# Rebuild and start
docker-compose up --build  # v1
docker compose up --build # v2
```

### Docker Build Cache Issues

If you've updated Dockerfiles and changes aren't taking effect:

```bash
# Rebuild without cache
docker-compose build --no-cache  # v1
docker compose build --no-cache # v2

# Remove all containers and rebuild
docker-compose down  # v1
docker compose down  # v2

docker-compose up --build  # v1
docker compose up --build # v2
```

## Project Structure

```
merchant_pay/
├── backend/              # Laravel backend application
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── routes/
│   ├── Dockerfile
│   └── ...
├── frontend/            # Vue.js frontend application
│   ├── src/
│   ├── public/
│   ├── Dockerfile
│   └── ...
├── nginx/               # Nginx configuration
│   └── nginx.conf
├── docker-compose.yml   # Docker Compose configuration
├── start.sh            # Convenience start script
└── README.md           # This file
```

## Environment Variables

### Backend Environment Variables

Set in `docker-compose.yml` or `.env` file:

- `DB_HOST=mysql`
- `DB_PORT=3306`
- `DB_DATABASE=merchant_pay`
- `DB_USERNAME=merchant_user`
- `DB_PASSWORD=merchant_password`
- `APP_ENV=local`
- `APP_DEBUG=true`
- `APP_URL=http://localhost`

### Frontend Environment Variables

- `VITE_API_URL=http://localhost/api`

## Database Configuration

### Connection Details

- **Host:** `mysql` (from within containers) or `localhost` (from host)
- **Port:** `3307` (host) or `3306` (container)
- **Database:** `merchant_pay`
- **Username:** `merchant_user`
- **Password:** `merchant_password`
- **Root Password:** `root_password`

### Access via phpMyAdmin

1. Navigate to http://localhost:8080
2. Server: `mysql`
3. Username: `root` or `merchant_user`
4. Password: `root_password` or `merchant_password`

## 🧑‍💻 Default User Credentials

When the database is seeded for the first time, a default user account is created for initial access and testing purposes.

Use the following credentials to log in after setup:

| Role | Email | Password |
|------|--------|-----------|
| **User** | user@gmail.com | password123 |

> ⚠️ **Note:**  
> These credentials are intended **only for local development and testing**.  
> Be sure to **update or remove** them before deploying to production for security reasons.


### Access via Command Line

```bash
# From host machine
mysql -h localhost -P 3307 -u merchant_user -pmerchant_password merchant_pay

# From backend container
docker-compose exec backend mysql -h mysql -u merchant_user -pmerchant_password merchant_pay  # v1
docker compose exec backend mysql -h mysql -u merchant_user -pmerchant_password merchant_pay  # v2
```

## Services Overview

### MySQL Database
- **Image:** `mysql:8.0`
- **Port:** `3307:3306`
- **Data Persistence:** Volume `mysql_data`
- **Health Check:** Automatic

### Laravel Backend
- **Base Image:** `php:8.2-fpm`
- **Port:** `9000` (internal, PHP-FPM)
- **Dependencies:** Auto-installed via Composer
- **Migrations:** Auto-run on first setup

### Vue.js Frontend
- **Base Image:** `node:18-alpine`
- **Port:** `5173:5173`
- **Dev Server:** Vite
- **Dependencies:** Auto-installed via npm

### Nginx Web Server
- **Image:** `nginx:alpine`
- **Port:** `80:80`, `443:443`
- **Role:** Reverse proxy for frontend and backend

### phpMyAdmin
- **Image:** `phpmyadmin:latest`
- **Port:** `8080:80`
- **Purpose:** Database administration

## API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user

### Merchants
- `GET /api/merchants` - List all merchants
- `POST /api/merchants` - Create merchant
- `PATCH /api/merchants/{id}/status` - Update merchant status

### Transactions
- `GET /api/transactions` - List transactions
- `POST /api/transactions` - Create transaction
- `GET /api/transactions/fees` - Get total fees

### Settlements
- `GET /api/settlements` - List settlements
- `POST /api/settlements/run` - Run settlement process

### Reports
- `GET /api/reports/summary` - Get summary statistics

### Testing
- `GET /api/test` - Test API connectivity

## Additional Resources

- **Quick Start Guide:** See `QUICK_START.md` for a condensed setup guide
- **Detailed Docker Setup:** See `DOCKER_SETUP.md` for in-depth Docker configuration
- **Docker Compose Upgrade:** See `UPGRADE_DOCKER_COMPOSE.md` if you need to upgrade

## Support

If you encounter issues:

1. Check the [Troubleshooting](#troubleshooting) section above
2. Review container logs: `docker-compose logs` or `docker compose logs`
3. Verify Docker and Docker Compose versions
4. Ensure all required ports are available
5. Check system resources (disk space, memory)


**Happy Coding! 🎉**

