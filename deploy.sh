#!/bin/bash

set -e

RED="\e[31m"
GREEN="\e[32m"
YELLOW="\e[33m"
BLUE="\e[34m"
MAGENTA="\e[35m"
CYAN="\e[36m"
RESET="\e[0m"

block() {
    echo -e "${MAGENTA}\n===== $1 =====${RESET}"
}

echo -e "${CYAN}=== DEPLOY START ===${RESET}"
echo -e "${BLUE}Directory:${RESET} $(pwd)"
echo -e "${BLUE}Time:${RESET} $(date)"

block "GIT PULL"
git pull origin main

block "COMPOSER INSTALL"
/opt/php/8.4/bin/php composer.phar install --no-interaction --prefer-dist --optimize-autoloader

block "CLEARING CACHES"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

block "REBUILDING CACHES"
php artisan config:cache
php artisan route:cache
php artisan view:cache

block "OPTIMIZE"
php artisan optimize

echo -e "${GREEN}\n=== DEPLOY COMPLETE ===${RESET}"
echo -e "${BLUE}Time:${RESET} $(date)"
