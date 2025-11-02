# Upgrade Docker Compose Guide

This guide helps you upgrade Docker Compose to support version 3.8 compose files and ensure compatibility with this project.

## Why Upgrade?

The `docker-compose.yml` file uses `version: '3.8'`, which requires:
- **Docker Compose v1.25.0+** (standalone)
- **Docker Compose v2.0.0+** (plugin)

If you're using an older version, you'll see errors like:
- "Version in './docker-compose.yml' is unsupported"
- "Unsupported config option for services"

## Check Your Current Version

First, check what version you have:

```bash
# Check Docker Compose v1 (standalone)
docker-compose --version

# Check Docker Compose v2 (plugin)
docker compose version

# Check Docker version
docker --version
```

## Understanding Docker Compose Versions

### Docker Compose v1 (Legacy)
- Command: `docker-compose` (with hyphen)
- Standalone Python-based tool
- Separate installation required
- Version format: `1.x.x`

### Docker Compose v2 (Modern)
- Command: `docker compose` (with space)
- Integrated into Docker CLI as a plugin
- Comes with Docker Desktop automatically
- Version format: `2.x.x`

**This project works with both versions!** Just use the appropriate command syntax.

## Upgrade Options

### Option 1: Install Docker Compose v2 Plugin (Recommended)

Docker Compose v2 is the modern, recommended approach and comes integrated with Docker.

#### For Ubuntu/Debian:

```bash
# Update package index
sudo apt-get update

# Install Docker Compose plugin
sudo apt-get install docker-compose-plugin

# Verify installation
docker compose version
```

#### For Docker Desktop (macOS/Windows):
Docker Compose v2 is already included! Just make sure Docker Desktop is updated to the latest version.

#### Verify:
```bash
docker compose version
```

You should see output like: `Docker Compose version v2.x.x`

**Usage:** After installing, use `docker compose` (with space) instead of `docker-compose` (with hyphen).

### Option 2: Upgrade Docker Compose v1 to Latest

If you prefer to keep using the standalone version:

```bash
# Download latest docker-compose v1
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Make it executable
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker-compose --version
```

You should see version 1.29.0 or higher.

### Option 3: Install via pip (Alternative)

```bash
# Install pip if not available
sudo apt install python3-pip

# Install docker-compose via pip
sudo pip3 install docker-compose

# Verify
docker-compose --version
```

### Option 4: Upgrade via apt (Ubuntu/Debian)

```bash
# Update package index
sudo apt update

# Upgrade docker-compose
sudo apt install --only-upgrade docker-compose

# Or install latest available version
sudo apt install docker-compose

# Verify
docker-compose --version
```

## After Upgrading

Once you've upgraded, verify everything works:

### Test Docker Compose

**If using v1:**
```bash
docker-compose --version
docker-compose up --help
```

**If using v2:**
```bash
docker compose version
docker compose up --help
```

### Test with the Project

**If using v1:**
```bash
docker-compose config  # Validates docker-compose.yml
docker-compose up -d    # Start services
```

**If using v2:**
```bash
docker compose config  # Validates docker-compose.yml
docker compose up -d    # Start services
```

### Expected Output

You should see:
- No version compatibility errors
- Compose file validates successfully
- Services start without errors

## Migration from v1 to v2

If you're migrating from v1 to v2, here's a quick command reference:

| v1 Command | v2 Command |
|------------|------------|
| `docker-compose up` | `docker compose up` |
| `docker-compose down` | `docker compose down` |
| `docker-compose ps` | `docker compose ps` |
| `docker-compose logs` | `docker compose logs` |
| `docker-compose exec` | `docker compose exec` |
| `docker-compose build` | `docker compose build` |

**Note:** The functionality is identical, only the command syntax changes (hyphen → space).

## Using the Start Script

The project includes a `start.sh` script that automatically detects which Docker Compose version you have and uses the appropriate command. This means you don't need to worry about the version!

```bash
chmod +x start.sh
./start.sh
```

## Troubleshooting Upgrade Issues

### "docker-compose: command not found" after upgrade

Make sure the binary is in your PATH:
```bash
# Check location
which docker-compose

# If not found, ensure /usr/local/bin is in PATH
echo $PATH

# Or create symlink
sudo ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
```

### Version still showing old number

You might have multiple installations:
```bash
# Check all locations
which -a docker-compose

# Remove old versions
sudo rm /usr/bin/docker-compose  # Old location

# Use the new one
/usr/local/bin/docker-compose --version
```

### Conflicts between v1 and v2

You can have both versions installed. Use:
- `docker-compose` for v1
- `docker compose` for v2

They work independently and won't conflict.

## Recommended Setup

**For new installations:** Use Docker Compose v2 (plugin) - it's the future.

**For existing setups:** Either version works fine. The `start.sh` script handles both.

## Version Compatibility

This project's `docker-compose.yml` uses `version: '3.8'`, which is supported by:

| Compose File Version | Docker Compose v1 | Docker Compose v2 |
|---------------------|------------------|-------------------|
| 3.8 | v1.25.0+ | v2.0.0+ |
| 3.7 | v1.24.0+ | v2.0.0+ |
| 3.6 | v1.18.0+ | v2.0.0+ |

**Current project:** Uses version 3.8 (requires v1.25.0+ or v2.0.0+)

## Still Having Issues?

1. **Check Docker version:** `docker --version` (should be 20.10+)
2. **Check Compose version:** `docker-compose --version` or `docker compose version`
3. **Validate compose file:** `docker-compose config` or `docker compose config`
4. **View detailed errors:** `docker-compose up --verbose` or `docker compose up --verbose`
5. **Check logs:** All container logs are available via `docker-compose logs` or `docker compose logs`

## Summary

- **Quick fix:** Install Docker Compose plugin: `sudo apt-get install docker-compose-plugin`
- **Then use:** `docker compose` (with space) instead of `docker-compose` (with hyphen)
- **Or upgrade v1:** Download latest from GitHub releases
- **Easiest:** Use the `start.sh` script which handles version detection automatically

For more details, see `README.md` and `DOCKER_SETUP.md`.
