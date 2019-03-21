<?php
    session_start();
    if (!empty($_POST)) {

        require '../Database/Database.php';
        $db_connect = new Database('BDD_Projet');
        $pdo = $db_connect->getPDO();
    
        $wrong_inputs = array();//message d'erreur

    
    //changer pseudo
    
    if (empty($_POST['Pseudo']) || !preg_match('/^[a-zA-Z0-9_]+$/',$_POST['Pseudo'])) {
    $wrong_inputs['Pseudo'] = "Entrer un Pseudo correct";
    }
    else{
        $verify_exist_pseudo_request = "SELECT * FROM Utilisateur WHERE Pseudo =?";
        $verify_exist_pseudo = $pdo->prepare($verify_exist_pseudo_request);
        $verify_exist_pseudo->execute([$_POST['Pseudo']]);
        $exist_pseudo = $verify_exist_pseudo->fetch();
        if ($exist_pseudo) {
            $wrong_inputs['Pseudo']= "Ce pseudo est déja utilisé";
        }
        else{
        $changer_pseudo=$pdo->prepare("UPDATE Utilisateur SET Pseudo=?");//
            $changer_pseudo->execute([$_POST['Pseudo']]);
            if($changer_pseudo){
                echo'success1';//message
            }
        }
    }
    
    
    
    //changer adresse mail
    
    
    if (empty(($_POST['email']))) {
        $wrong_inputs['email'] = "Entrer un email valide";
    }
    else {
        $verify_exist_email_request = "SELECT id_Utilisateur FROM Utilisateur WHERE email = ?";
        $verify_exist_email = $pdo->prepare($verify_exist_email_request);
        $verify_exist_email ->execute([$_POST['email']]);
        $exist_email = $verify_exist_email->fetch();
        if ($exist_email) {
            $wrong_inputs['email'] = "Cet email est déja utilisé";
        }
        else{
            $changer_email=$pdo->prepare("UPDATE Utilisateur SET Email= ?");//
            $changer_email->execute([$_POST['email']]);
            if($changer_email){
                echo'success2';//message
            }
        }
    }
    
    
    //changer mot de passe
    
    if (empty(($_POST['password']))) {
        $wrong_inputs['password'] = "Entrer un nouveau mot de passe";
    }
    else{
        $pws=password_hash($_POST['password'],PASSWORD_BCRYPT);
        $changer_psw=$pdo->prepare("UPDATE Utilisateur SET Mot_de_passe= ?");//
        $changer_psw->execute([$pws]);
        if($changer_psw){
            echo'success3';//message
        }
    }
    
    
    

}
?>
<html lang="en">
<body>

<form action="editiondeprofil.php" method="post">
Pseudo: <input type="text" name="Pseudo"></p>
E-mail: <input type="text" name="email"></p>
Mot de passe:<input type="text" name="password"></p>
<input type="submit" name="submit3" value="modifier">

</form>

</body>
</html>