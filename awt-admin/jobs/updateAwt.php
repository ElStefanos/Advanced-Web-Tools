<?php

define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use store\store;

use admin\{authentication, profiler};

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

if($profiler->checkPermissions(0)) {
    $update = new store("getLatestAWTVersion", "Advanced Web Tools", "AWT");

    $update->updateAWTVersion();
    header("Location: ". HOSTNAME ."/awt-admin/index.php?page=Settings&status=update_succesfull");
    exit();
}

header("Location: ". HOSTNAME ."/awt-admin/index.php?page=Settings&status=not_allowed");
exit();