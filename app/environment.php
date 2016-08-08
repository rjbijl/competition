<?php 

// Define application environment
if (file_exists(__DIR__ . '/config/environment')) {
    $environment = trim(file_get_contents(__DIR__ . '/config/environment'));
}

if (!isset($environment)) {
    $environment = getenv('APPLICATION_ENV');
}

if (isset($environment) && $environment) {
    define('APPLICATION_ENV', $environment);
    unset($environment);
} else {
    throw new \RuntimeException('No Environment set!');
}
