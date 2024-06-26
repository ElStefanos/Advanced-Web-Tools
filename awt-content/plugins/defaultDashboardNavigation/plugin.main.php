<?php

use admin\navbar;

$nav = new navbar;
$logout = new navbar;
$settings = new navbar;

if (!defined('DASHBOARD') || !checkForPlugin('floatingEditor', '0.0.1') == true) {

    $location = HOSTNAME . 'awt-content/plugins/defaultDashboardNavigation/data/icons/';

    $nav->addItem(array('icon' => $location . 'house-solid.svg', 'name' => 'Dashboard', 'link' => HOSTNAME . 'awt-admin/?page=Dashboard', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'file-lines-solid.svg', 'name' => 'Pages', 'link' => HOSTNAME . 'awt-admin/?page=Pages', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'brush-solid.svg', 'name' => 'Themes', 'link' => HOSTNAME . 'awt-admin/?page=Themes', 'permission' => 0));
    $nav->addItem(array('icon' => $location . 'puzzle-piece-solid.svg', 'name' => 'Plugins', 'link' => HOSTNAME . 'awt-admin/?page=Plugins', 'permission' => 0));
    $nav->addItem(array('icon' => $location . 'store-solid.svg', 'name' => 'Store', 'link' => HOSTNAME . 'awt-admin/?page=Store', 'permission' => 0));
    $nav->addItem(array('icon' => $location . 'compass-solid.svg', 'name' => 'Menus', 'link' => HOSTNAME . 'awt-admin/?page=Menus', 'permission' => 1));
    $nav->addItem(array('icon' => $location . 'media.svg', 'name' => "My Media", 'link' => HOSTNAME . 'awt-admin/?page=Media', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'wand-magic-sparkles-solid.svg', 'name' => "Customize", 'link' => HOSTNAME . 'awt-admin/?page=Customize', 'permission' => 1));
    $nav->addItem(array('icon' => $location . 'users-solid.svg', 'name' => 'Accounts', 'link' => HOSTNAME . 'awt-admin/?page=Accounts', 'permission' => 2));
    $nav->addItem(array('icon' => $location . 'envelopes-bulk-solid.svg', 'name' => 'Mail', 'link' => HOSTNAME . 'awt-admin/?page=Mail', 'permission' => 2));
    $settings->addItem(array('icon' => $location . 'toolbox-solid.svg', 'name' => 'Settings', 'link' => HOSTNAME . 'awt-admin/?page=Settings', 'permission' => 0));
    $logout->addItem(array('icon' => $location . 'exit-solid.svg', 'name' => 'Log out', 'link' => HOSTNAME . 'awt-admin/jobs/signInOut.php?logout', 'permission' => 2));

    array_push($navbar, $nav);
    $navbar['end'] = $settings;
    $navbar['last'] = $logout;
}
?>
<?php if (defined('DASHBOARD')) : ?>
    <script src="../awt-src/vendor/jQuery/jquery.min.js"></script>
    <script>
        window.addEventListener("load", (event) => {
            var page = '<?php echo HOSTNAME . 'awt-admin/?page='.$_GET['page']; ?>';
            $('.nav-item[href="' + page + '"]').addClass('current');
           
        });
    </script>
<?php endif; ?>