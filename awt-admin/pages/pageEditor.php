<?php

defined('DASHBOARD') or die("You should not do that..");
defined('ALL_CONFIG_LOADED') or die("An error has occured");

?>
<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery UI library -->
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<!-- Include jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
</head>
<script src="./javascript/themeEditor/hidenav.js">
</script>
<script src="./javascript/pageEditor/pageEditor.js">
</script>
<link rel="stylesheet" href="./css/pageEditor.css">

<div class="dialog">

</div>

<section class="page">
    <div class="preview">
        <?php
        $paging->loadPage($_GET['editPage']);
        ?>
    </div>
    <div class="editor-tools">
        
        <div class="block-options">
        </div>
    </div>
</section>