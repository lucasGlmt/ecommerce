<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/style.css">


    <title>E-Boutique</title>
</head>
<body>
   <div class="container">
        <div class="cbox">
            <form method="POST" action='#'>
                <h1>Connectez-vous !</h1>
                <div class="form-group">
                    <label for="username">Nom d'uilisateur</label>
                    <input class="form-control" name="username" required="true" type="text">
                </div>
                <button name="submit" type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>

    <?php
        if(isset($_POST["submit"])) {
            $_SESSION['idUser'] = uniqid();
            $_SESSION['dateLogin'] = time();
            $_SESSION['usernameUser'] = htmlentities(trim(addslashes($_POST['username'])));
            header("Location:./");
        }
    ?>
</body>
</html>