<?php
    session_start();



    require('./class/Autoloader.php');
    Autoloader::register();


    $mysql = new Mysql();
    $articles = $mysql->query("SELECT * FROM reservation WHERE idSession = '{$_SESSION["id"]}'");

    $articles = json_encode($articles);
    echo $articles;
?>