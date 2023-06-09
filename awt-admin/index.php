<?php

    define('DASHBOARD', 1);
    
    include '../awt-config.php';
    include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-navbarLoader.php';
    include_once JOBS.'awt-domainBuilder.php';
    include_once FUNCTIONS.'awt-navbar.fun.php';
    include_once JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-pluginLoader.php';


    use admin\authentication;
    use admin\profiler;
    $check = new authentication;

    if(!$check->checkAuthentication()) {
        header("Location: ./login.php");
        exit();
    }

    use paging\paging;

    if(!isset($_GET['page'])) {
        header('Location: ./?page=Dashboard');
        exit();
    }
    

    $profiler = new profiler;
    $profile = $profiler->getProfile();

    
    $paging = new paging($pluginPages);
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/navbar.css">
    <script src="https://kit.fontawesome.com/9623f60d76.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="top-nav">
        <div class="profiler">
            <h3>Welcome <?php echo $profile['fname']. " ". $profile['lname'];?></h3>
            <h5>Username: <?php echo $profile['name']; ?></h5>
        </div>
    </nav>
    <section class="main-section">
        <nav class="main-navbar">
            <?php navbarLoader($navbar); ?>
        </nav>
        <section class="page">
            <?php $paging->getPage(true, "paging"); ?>
        </section>
    </section>
</body>

</html>