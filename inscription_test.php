<?php

    session_start();
    if (!empty($_POST)) {

        require 'Database.php';
        $db_connect = new Database('projet');
        $pdo = $db_connect->getPDO();

    /**
     * Tableau d'erreurs qui est rempli au fur à mésure du remplissage du formulaire
     */

    $wrong_inputs = array();

    /**
     * Traitement du champs Pseudo :
     * Limitation de caractères acceptés et rendre le champs obligatoire 
     */

    if (empty($_POST['pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/',$_POST['pseudo'])) {
        $wrong_inputs['pseudo'] = "Entrer un Pseudo correct";
    }
    else {
        $verify_exist_pseudo_request = "SELECT id_Utilisateur FROM Utilisateur WHERE Pseudo = ?";
        $verify_exist_pseudo = $pdo->prepare($verify_exist_pseudo_request);
        $verify_exist_pseudo->execute([$_POST['pseudo']]);
        $exist_pseudo = $verify_exist_pseudo->fetch();
        if ($exist_pseudo) {
            $wrong_inputs['pseudo']= "Ce pseudo est déja utilisé";
        }

    }


    /**
     * Traitement du champs Email :
     * Limitation de caractères acceptés et rendre le champs obligatoire 
     */

    if (empty($_POST['email']) || !preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i',$_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
        $wrong_inputs['email'] = "Entrer un email valide";
    }
    else {
        $verify_exist_email_request = "SELECT id_Utilisateur FROM Utilisateur WHERE Email = ?";
        $verify_exist_email = $pdo->prepare($verify_exist_email_request);
        $verify_exist_email ->execute([$_POST['email']]);
        $exist_email = $verify_exist_email->fetch();
        if ($exist_email) {
            $wrong_inputs['email'] = "Cet email est déja utilisé";
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


<?php require '../Templates/header.php' ?>


<!-- Formulaire d'inscription -->
    
    <div class="modal fade" id="form_inscription" tabindex="-1" role="dialog" aria-labelledby="form_inscriptionCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal_inscription">
                <div class="modal-header">
                    <h5 class="modal-title form_title" id="form_inscriptionTitle">Inscription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($wrong_inputs)): ?>
                        <div class="alert alert-danger">
                            Vous n'êtes pas connecté
                            <ul>
                                <?php foreach ($wrong_inputs as $wrong_input): ?>
                                <li>
                                    <?= $wrong_input ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST" >
                        <div >
                            <input type="text" name="pseudo" class="form-control" id="psInput" placeholder="Pseudo">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" id="emailInput" placeholder="Email">
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="password" name="psw" class="form-control" id="pswInput" placeholder="Mot de passe">
                        </div>
                        <div class="form-group">
                            <input type="password" name="psw_confirm" class="form-control" id="psw_confirmInput" placeholder="Confirmation du mot de passe">
                        </div>
                        <button type="submit" class="btn btn-danger connect_submit">S'inscrire</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <span class="new">Vous avez déjà un compte</span>
                        ?
                    <a class="new_account" href="">Se connecter</a>
                </div>
            </div>
        </div>
    </div>

<div class="space"></div>



<?php require '../Templates/footer.php' ?>


