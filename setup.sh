#!/bin/bash
# exit on any error
set -e
echo "====================================="
echo "=== Competition Manager installer ==="
echo "====================================="
echo ""
echo "Running composer"
composer.phar install
echo ""
echo "Running doctrine migrations"
bin/console do:mi:mi
echo ""
echo "Update complete!"