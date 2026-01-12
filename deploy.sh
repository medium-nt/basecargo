#!/bin/bash

set -e

RED="\e[31m"
GREEN="\e[32m"
YELLOW="\e[33m"
BLUE="\e[34m"
CYAN="\e[36m"
RESET="\e[0m"

echo -e "${CYAN}=== DEPLOY START ===${RESET}"
echo -e "${BLUE}Directory:${RESET} $(pwd)"
echo -e "${BLUE}Time:${RESET} $(date)"
echo "----------------------"

echo -e "${YELLOW}→ Pulling latest changes...${RESET}"
git pull origin main

echo -e "${YELLOW}→ Installing composer dependencies...${RESET}"
php84 composer.phar install --no-interaction --prefer-dist --optimize-autoloader

echo -e "${YELLOW}→ Clearing Laravel caches...${RESET}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo -e "${YELLOW}→ Rebuilding Laravel caches...${RESET}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${YELLOW}→ Running optimize...${RESET}"
php artisan optimize

echo "----------------------"
echo -e "${GREEN}=== DEPLOY COMPLETE ===${RESET}"
echo -e "${BLUE}Time:${RESET} $(date)"
