#!/bin/bash

if [ -x "$(command -v docker)" ]; then
    echo "Docker is installed"
else
    echo "Docker is not installed."
    echo "Installing Docker..."
    sudo apt-get update
    sudo apt-get install ca-certificates curl gnupg lsb-release
    sudo mkdir -p /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
    sudo apt-get update
    sudo apt-get install docker-ce docker-ce-cli containerd.io docker-compose-plugin
fi

# Build and run the image
# docker compose -f docker-compose.local.yml build && docker compose -f docker-compose.local.yml up -d

# Install deps
docker compose exec app composer install

# Remove package lock
# docker compose exec app rm -rf package-lock.json

# Install node deps
docker compose exec app npm install

# Build the frontend like vue, react
docker compose exec app npm run build

# Give correct permission to the storage folder
docker compose exec app chmod o+w ./storage/ -R

# Key
docker compose exec app php artisan key:generate

# Migrate the database
# Dont needed

# Cache the config
# docker compose exec app php artisan config:cache

# Cache the route
# docker compose exec app php artisan route:cache

# Cache the view
# docker compose exec app php artisan view:cache

