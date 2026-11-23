<?php
session_start();

require_once '../model/model.php';

// Sécurité : redirection si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ID.php");
    exit();
}

// Récupération de l'ID expérimentation
$idExp = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($idExp <= 0) {
    header("Location: PartAcc.php");
    exit();
}

// Chargement des données de l'exp depuis la BDD
$exp = get_exp_details($idExp);
if (!$exp) {
    header("Location: PartAcc.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mon Profil Utilisateur - Le Mans Université</title>
  <link rel="stylesheet" href="../style/Partstyle.css" />
</head>

<body>

  <div class="app-shell">

    <!-- HEADER -->
    <header class="site-header">
      <a class="brand" href="PartAcc.php" aria-label="Le Mans Université">
        <img src="../assets/img/logoLemansBlancSurBleu.png" alt="Le Mans Université" />
      </a>

      <nav class="main-nav" aria-label="Navigation principale">
        <a class="nav-link" href="#dashboard">Dashboard</a>
        <a class="nav-link" href="PartAcc.php">Expérimentations</a>
        <a class="nav-link" href="#ressources">Ressources</a>
        <a class="nav-link" href="#messagerie">Messagerie</a>
      </nav>

      <div class="header-actions">
        <!-- Menu du profil utilisateur -->
        <div class="menu-anchor profile-anchor">
          <button type="button" class="profile-button" aria-haspopup="menu">
            <span class="avatar"><?= htmlspecialchars($initiales) ?></span>
            <span class="profile-copy">
              <strong><?= htmlspecialchars($userNom . '.' . $userPrenom) ?></strong>
              <small><?= htmlspecialchars($userRole) ?></small>
            </span>
            <span class="fa profile-chevron">&#xf078;</span>
          </button>
          <div class="dropdown profile-menu" role="menu" style="display: none;">
            <div class="profile-menu-heading">
              <span class="avatar dark"><?= htmlspecialchars($initiales) ?></span>
              <span>
                <strong><?= htmlspecialchars($userNom . ' ' . $userPrenom) ?></strong>
                <small><?= htmlspecialchars($userRole) ?></small>
              </span>
            </div>
            <a href="PartProfil.php" class="dropdown-item" role="menuitem"><span class="fa">&#xf007;</span> Mon profil</a>
            <a href="#parametres" class="dropdown-item" role="menuitem"><span class="fa">&#xf013;</span> Paramètres</a>
            <a href="../controller.php?action=logout" class="dropdown-item danger" role="menuitem"><span class="fa">&#xf2f5;</span> Se déconnecter</a>
          </div>
        </div>
      </div>
    </header>

    <!-- Bandeau titre -->
    <section class="page-heading">
      <h1>
        <span class="fa">&#xf007;</span>
        Créez votre Profil Utilisateur
      </h1>
    </section>

    <!-- Corps principal -->
    <main class="profil-main-content">
      <section class="form-panel" aria-label="Création du profil utilisateur">
        <form method="POST" action="PartProfil.php" class="profile-form">
          <label>
            <span class="sr-only">Nom</span>
            <input type="text" name="lastName" value="<?= htmlspecialchars($userNom) ?>" placeholder="Nom" required />
          </label>

          <label>
            <span class="sr-only">Prénom</span>
            <input type="text" name="firstName" value="<?= htmlspecialchars($userPrenom) ?>" placeholder="Prénom" required />
          </label>

          <label>
            <span class="sr-only">Âge</span>
            <input type="number" name="age" min="1" max="120" placeholder="Âge" required />
          </label>

          <label>
            <span class="sr-only">Genre</span>
            <select name="gender" required>
              <option value="" disabled selected>Genre</option>
              <option value="femme">Femme</option>
              <option value="homme">Homme</option>
              <option value="non-binaire">Non-binaire</option>
              <option value="non-precise">Je préfère ne pas préciser</option>
            </select>
          </label>

          <label>
            <span class="sr-only">Autres spécificités</span>
            <textarea name="details" placeholder="Autres Spécificités"></textarea>
          </label>

          <button type="submit" class="submit-button">Valider</button>

          <?php if ($errorMessage !== ''): ?>
            <p class="error-message" role="alert"><?= htmlspecialchars($errorMessage) ?></p>
          <?php elseif ($submitted): ?>
            <p class="success-message visible" aria-live="polite">Votre profil a bien été enregistré.</p>
          <?php endif; ?>
        </form>
      </section>

      <figure class="workspace-photo">
        <img src="../assets/img/FondProjet.png" alt="Deux chercheurs travaillant devant plusieurs écrans dans un laboratoire" />
      </figure>
    </main>

    <!-- Pied de page -->
    <footer>
      <span class="fa">&#xf19d;</span>
      <p>
        <strong>Le Mans Université</strong> • <?= htmlspecialchars($userRole) ?> - Mon profil Utilisateur
      </p>
    </footer>

  </div>

</body>

</html>