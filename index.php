<?php
    session_start();
    require('./class/Autoloader.php');
    Autoloader::register();

    $mysql = new Mysql();
    $data = $mysql->query("SELECT * FROM stocks");

    if(!isset($_SESSION['idUser'])) {
         header('Location:./login');
        
    }


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

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/theme.css">

        <link rel="stylesheet" href="css/style.css">
        <title>Articles</title>
    </head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">E-Boutique</a>
                <h3>Bienvenue, <?= $_SESSION['usernameUser']?></h3>
                    <a class="nav-link btn btn-secondary" aria-current="page" id="cart" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Panier</a>
                </div>
            </div>
        </nav>
    </header>

 

    <div class="container">
        <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] === "notFound") echo "<div class='alert alert-danger'>L'article n'existe pas</div>";
                if ($_GET['error'] === "unavailable") echo "<div class='alert alert-warning'>L'article n'est plus disponnible.</div>";
                if ($_GET['error'] === "notEnough") echo "<div class='alert alert-warning'>Pas assez d'articles !</div>";
            }

            if(isset($_GET['success'])) {
                echo "<div class='alert alert-success'>Votre page à bien été ajoutée.</div>";
            }
        ?>
        <div class="row">
            <?php
                foreach($data as $article) {
                    ?>
                        <div class="article col-6">
                            <div class="card">
                                <div class="card-header">
                                    Article
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?= $article->title ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Stock disponnible : <?= $article->stock?></h6>
                                    <p class="card-text"><?= $article->ref?> </p>
                                    <form method="get" action="cart.php">
                                        <div class="row">
                                            <div class="col-3">
                                                <input class="form-control" name="quantity" type="number" max="<?= $article->stock ?>" min="1" value="1">
                                            </div>
                                            <div class="col-9">
                                                <?php
                                                    if ($article->stock >= 1) echo '<button type="submit" name="id" value="'.$article->id.'" class="btn btn-primary">Réserver</button>';
                                                    else echo '<a href="#" class="btn btn-primary disabled">Indisponnible</a>';
                                                ?>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
    </div>


                


    <!-- Modal : Panier -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mon panier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="spinner-border" id="loading" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div id="cartContent">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>






    <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</body>
</html>