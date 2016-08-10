#!/bin/bash
# exit on any error
set -e
echo "====================================="
echo "=== Competition Manager updater ==="
echo "====================================="
echo ""
echo "Updating to origin master in directory ${PWD}"
echo "Please provide your credentials, if asked for"
git pull origin master
echo ""
echo "Running composer"
composer.phar install
echo ""
echo "Running doctrine migrations"
bin/console do:mi:mi
echo ""
echo "Update complete!"