<?php
define('JOB', 1);

include '../../awt-config.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-autoLoader.php';
include_once JOBS . 'loaders' . DIRECTORY_SEPARATOR . 'awt-pluginLoader.php';
include_once JOBS . 'awt-domainBuilder.php';

use admin\{authentication, profiler};
use menu\menu;

$check = new authentication;
$profiler = new profiler;

if (!$check->checkAuthentication()) {
    header("Location: ../login.php");
    exit();
}

$menu = new menu;

if(isset($_POST["fetch_all_menus"])) {
    die(json_encode($menu->retrieveAllMenus()));
}

if(isset($_POST["updateMenu"])) {
    $data = json_decode($_POST["data"], true);
    $menu->updateMenu($data[0]['name'], $data[0]['items']);
}


if(isset($_POST["set_active_menu"])) {
    $menu->setActiveMenu($_POST["set_active_menu"]);
}

if(isset($_POST["create_menu"])) {
    $menu->createMenu($_POST["create_menu"], " ");
}
