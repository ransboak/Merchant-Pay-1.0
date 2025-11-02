#!/bin/bash

echo "🚀 Starting Merchant Pay Application with Docker..."
echo ""

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose and try again."
    exit 1
fi

# Use docker compose (newer syntax) if available, otherwise docker-compose
if docker compose version &> /dev/null; then
    COMPOSE_CMD="docker compose"
else
    COMPOSE_CMD="docker-compose"
fi

echo "📦 Building and starting containers..."
$COMPOSE_CMD up --build -d

echo ""
echo "⏳ Waiting for services to be ready..."
sleep 5

# Wait for MySQL to be healthy
echo "🔍 Checking MySQL health..."
timeout=60
elapsed=0
while [ $elapsed -lt $timeout ]; do
    if $COMPOSE_CMD exec -T mysql mysqladmin ping -h localhost -u root -proot_password --silent 2>/dev/null; then
        echo "✅ MySQL is ready!"
        break
    fi
    echo "   Waiting for MySQL... ($elapsed/$timeout seconds)"
    sleep 2
    elapsed=$((elapsed + 2))
done

echo ""
echo "📋 Checking service status..."
$COMPOSE_CMD ps

echo ""
echo "✨ Application is starting up!"
echo ""
echo "📍 Access points:"
echo "   - Frontend: http://localhost"
echo "   - Backend API: http://localhost/api"
echo "   - Frontend Dev Server: http://localhost:5173"
echo "   - phpMyAdmin: http://localhost:8080"
echo "   - MySQL: localhost:3307"
echo ""
echo "📊 To view logs:"
echo "   $COMPOSE_CMD logs -f"
echo ""
echo "🛑 To stop:"
echo "   $COMPOSE_CMD down"
echo ""
echo "⏱️  Please wait a few more seconds for all services to fully initialize..."

