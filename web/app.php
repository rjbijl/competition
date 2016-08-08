<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

require_once __DIR__.'/../app/environment.php';
require_once __DIR__.'/../app/AppKernel.php';

$debug = APPLICATION_ENV === 'dev';
if ($debug) {
    Debug::enable();
}

$kernel = new AppKernel(APPLICATION_ENV, $debug);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
