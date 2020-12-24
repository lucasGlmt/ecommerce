# TP BD - E-commerce
## Créer une base de données
```sql
CREATE DATABASE IF NOT EXISTS ecommercegl
```
## Créer une table stock
```sql
CREATE TABLE stocks
(
	id INT(11) AUTO_INCREMENT,
	ref VARCHAR(255),
	nom VARCHAR(255),
	nbstocks INT(255),
) ;
```
## Créer une table réservation
```sql
CREATE TABLE reservation
(
	id INT(11) AUTO_INCREMENT,
	idsession VARCHAR(26),
	idproduit INT(255),
	qteReserv INT(255),
) ;
```
## Etude du problème de concurrence
```php
// Ajouter l'article dans reservation
$mysql->query("INSERT INTO reservation (idProduit, idSession, qte) VALUES ('{$response[0]->id}','{$_SESSION["id"]}', '{$quantity}')");

//Décrémenter le stock dans la table stocks sur l'article en question
$mysql->query("UPDATE stocks SET stock = stock - '{$quantity}' WHERE id='{$choice}'");
```

Lors de la réservation d'un article, j'appelle une méthode __query__ de la classe mysql pour pouvoir réaliser des transactions depuis php. Si deux utilisateurs font des requêtes en même temps (< 1ms ~) alors pdo va executer une requête après l'autre car j'utilise `prepare` et `execute`.
On aurait pu aussi utiliser les instructions de verrouillage : 
```sql
LOCK IN SHARE MODE --- Instruction de verrouillage partagée
LOCK TABLES --- Verrouiller une table
UNLOCK TABLES --- Déverrouiller une table
```

## Annuler les réservations dont la session a expirée

### Détecter si une session est existante
Dans php, pour exister si une session existe, on peut utiliser la fonction `isset()` qui renvoie true ou false si elle existe ou non.
```php
if(isset($_SESSION['idUser'])) 
```


### Récupérer les articles réservés
```php
$articlesReserves = $mysql->query("SELECT * FROM reservation WHERE idSession = '{$_SESSION["idUser"]}'");
            foreach($articlesReserves as $article) {
                $mysql->query("DELETE FROM reservation WHERE idProduit = '{$article->idProduit}'");
                $mysql->query("UPDATE stocks set stock = sotck + '{$article->qte}'");
            }
```
Donc si la session n'existe plus, on peut récupérer les articles.
On peut utiliser un timeout (de 30 minutes par exemple) pour détruire la session.
