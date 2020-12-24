<?php
session_start();




$choice = $_GET['id'];
$quantity = $_GET['quantity'];

require('./class/Autoloader.php');
Autoloader::register();

$mysql = new Mysql();
$response = $mysql->query("SELECT * FROM stocks WHERE id = '{$choice}' ");



    // Vérifier que la session existe
    if(isset($_SESSION['idUser'])) {

        // Verifier que la session est terminée (au niveau du timeout) - 30 SECONDES
        if(time() - $_SESSION["dateLogin"] > 30) {

            $articlesReserves = $mysql->query("SELECT * FROM reservation WHERE idSession = '{$_SESSION["idUser"]}'");
            foreach($articlesReserves as $article) {
                $mysql->query("DELETE FROM reservation WHERE idProduit = '{$article->idProduit}'");
                $mysql->query("UPDATE stocks set stock = sotck + '{$article->qte}'");
            }
            
            session_unset();
            session_destroy();
            
        }
    }



// vérifier $response
if (empty($response)) {
    header('Location:./?error=notFound');
    return false;
}

// vérifier si article disponnible
if ($response[0]->stock == 0) {
    header('Location:./?error=unavailable');
    return false;
}

// vérifier si stock - quantity > 0
if ($response[0]->stock - $quantity < 0) {
    header('Location:./?error=notEnough');
    return false;
}

// Ajouter l'article dans reservation
$mysql->query("INSERT INTO reservation (idProduit, idSession, qte) VALUES ('{$response[0]->id}','{$_SESSION["id"]}', '{$quantity}')");

//Décrémenter le stock dans la table stocks sur l'article en question
$newStock = $response[0]->stock -= $quantity;

$mysql->query("UPDATE stocks SET stock = '{$newStock}' WHERE id='{$choice}'");




header("Location:./?success");