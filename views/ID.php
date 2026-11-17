<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identification</title>
</head>
<body>
    <h1>S identifier</h1>
    <form action="../controller.php" method="post">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br><br>

        <input type="submit" name="connexion" value="Se connecter">  
    </form>
</body>
</html>
