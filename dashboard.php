<?php session_start() ;?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="../../owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">    <link href="https://fonts.googleapis.com/css?family=Pinyon+Script" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style_test.css">
</head>
<body>
<header class="header" role="banner">
    <div class="topbar">
        <div class="logo">
            <a href="#" class="logo-link">SuperQ</a>
        </div>

        <div class="authentification">
            <a class=" connexion btn " data-toggle="modal" role="button" data-target="#form_connection">Deconnexion</a>
        </div>
    </div>
</header>
<?php if (isset($_SESSION['status'])): ?>
    <?php foreach ($_SESSION['status'] as $status => $info): ?>
        <div class="alert alert-<?= $status ?>" role="alert">
            <h4 class="alert-heading"><?= $info ?></h4>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['status']); ?>
<?php endif; ?>



<?php   require '../Templates/footer.php'; ?>

