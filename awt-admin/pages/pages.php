<?php

defined('DASHBOARD') or  die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

use admin\authentication;

$check = new authentication;

if (!$check->checkAuthentication()) {
    header("Location: ./login.php");
    exit();
}

?>

<script src="./javascript/pages/pages.js">
</script>
<link rel="stylesheet" href="./css/pages.css">
<section>

    <div class="emptyPageContainer">
        <input type="text" class="pageName input" placeholder="Page name"/>
        <button type="button" class="button" onclick="createEmptyPage('.pageName', '<?php echo HOSTNAME; ?>')">Create</button>
    </div>
    <div class="pages">
    </div>
</section>
<script>
    $(document).ready(function() {

        fetchPages(".pages", "<?php echo HOSTNAME; ?>");

    });
</script>