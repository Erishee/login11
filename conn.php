<?php

    session_start();
    if (!empty($_POST)) {

        require 'Database.php';
        $db_connect = new Database('projet');
        $pdo = $db_connect->getPDO();

    $wrong_inputs = array();

    /**
     * Traitement du champs Pseudo :
     */

    if (empty($_POST['pseudo']){
        $wrong_inputs['pseudo'] = "Entrer votre pseudo !";
    }
    else {
        $verify_exist_pseudo_request = "SELECT id_Utilisateur FROM Utilisateur WHERE Pseudo = ?";
        $verify_exist_pseudo = $pdo->prepare($verify_exist_pseudo_request);
        $verify_exist_pseudo->execute([$_POST['pseudo']]);
        $exist_pseudo = $verify_exist_pseudo->fetch();
        if ($exist_pseudo) {
            if (empty($_POST['psw']){
                $wrong_inputs['psw'] ="Entrer votre mot de passe !";
                else{
                    $pws= password_hash ($_POST['psw'],PASSWORD_BCRYPT);
                    $verify_psw_request = "SELECT Mot_de_passe FROM Utilisateur WHERE Pseudo = ?";
                    $verify_user = $pdo->prepare($verify_user_request);
                    $user = $verify_user->execute([$_POST['pseudo'],$pws]);
                    $_SESSION['auth'] = $user;
                    $_SESSION['status']['success'] = "Vous êtes maintenant connecté";
                    header('Location:dashboard.php');

                }
            }
        }

    }

    /**
     * Mot de passe
     */

    if (empty($_POST['psw']) || ($_POST['psw'])!=($_POST['psw_confirm'])) {
        $wrong_inputs['psw'] ="Les mots de passes ne correspondent pas";
    }


    if (empty($wrong_inputs)) {

     
        $pws= password_hash ($_POST['psw'],PASSWORD_BCRYPT);
        $add_user_request = "INSERT INTO Utilisateur SET Pseudo = ?, Email = ?, Mot_de_passe = ?";
        $add_user = $pdo->prepare($add_user_request);
        $user = $add_user->execute([$_POST['pseudo'],$_POST['email'],$pws]);
        $_SESSION['auth'] = $user;
        $_SESSION['status']['success'] = "Vous êtes maintenant connecté";
        header('Location:dashboard.php');
    }
}
?>

<?php var_dump($_SESSION) ?>
<?php require '../Templates/header.php'; ?>

<div class="modal fade" id="form_connection" tabindex="-1" role="dialog" aria-labelledby="form_connetionCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title form_title" id="form_connectionTitle">Connexion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST"  novalidate>
                    <div class="form-group">
                        <input type="text" class="form-control" id="pseudoInput" placeholder="Pseudo">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="mdpInput" placeholder="Mot de passe">
                    </div>
                    <button type="submit" class="btn btn-danger connect_submit">Se connecter</button>
                    <a href="#">Mot de passe oublié ? </a>
                </form>
            </div>
            <div class="modal-footer">
                <span class="new">Vous êtes nouveau </span>
                ?
                <span class="new_account">Creer votre compte</span>
            </div>
        </div>
    </div>
</div>

<?php require '../Templates/footer.php'; ?>