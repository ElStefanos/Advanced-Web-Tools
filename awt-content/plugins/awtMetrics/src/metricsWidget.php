<?php

$widget = new awtMetrics();

$total = $widget->getMetricsTotal();
$unique = $widget->getMetricsUnique();
$mostVisited = $widget->getMostVisited();

if($mostVisited !== false && count($mostVisited) != 0) {
    $mostVisited = $mostVisited[0]["url"];
} else {
    $mostVisited = "Not enough data.";
}

?>

<div class="metrics-widgets-group">
    <div class="metrics-widget shadow">
        <h3>Total number of visits</h3>
        <p><?php echo $total ?></p>
    </div>
    <div class="metrics-widget shadow">
        <h3>Total number of unique visitors</h3>
        <p><?php echo $unique ?></p>
    </div>
    <div class="metrics-widget shadow">
        <h3>Most visited page</h3>
        <p><?php echo $mostVisited ?></p>
    </div>
</div>

<style>
    .metrics-widgets-group {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
        width: 350px;
        height: 400px;
        gap: 10px;
    }

    .metrics-widget {
        height: 150px;
        width: 150px;
        padding: 10px;
        border-radius: 10px;
    }

    .metrics-widget p {
        font-weight: 600;
        font-size: 25px;
    }

    .metrics-widget:nth-child(1) {
        background: orangered;
        color: #fff;
    }

    .metrics-widget:nth-child(2) {
        background: var(--secondary-color);
        color: #fff;
    }

    .metrics-widget:nth-child(3) {
        background: teal;
        color: #fff;
        width: 330px;
    }

</style>