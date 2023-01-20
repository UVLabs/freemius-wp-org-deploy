#!/usr/bin/env bash

# Prepare plugin for uploading to Freemius.

# Start fresh
rm -rf dist
rm -rf artifact

# Make our directories
mkdir -p dist
mkdir -p artifact

# Remove dev dependencies
composer install --no-dev
composer dumpautoload

# Run Prettier
# npm run format

# Build our JS files with parcel
# npm run build

# Sync dist folder 
# Create '.distignore' file in your plugin's root directory and populate it with files you'd like to exclude from final zip file.
rsync -acvP --delete --exclude-from=".distignore" ./ "./dist"

# Change to our dist folder and zip to artifact folder
(cd dist && zip -r ../artifact/plugin.zip .)

# Delete dist folder
rm -rf dist

# Re-add dev dependencies
composer install
composer dumpautoload

