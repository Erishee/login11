<?php
    session_start();
    require '../Database/Database.php';
    $db_connect = new Database('BDD_Projet');
    $pdo = $db_connect->getPDO();
    $_SESSION['auth']['id_Utilisateur'];//id_Utilisateur->attribut qui est fixe dans la table Utilisateur
    $id=$_SESSION['auth']['id_Utilisateur'];
    //var_dump($_SESSION['auth']);
    
        $getemail ="SELECT Email FROM Utilisateur WHERE id_Utilisateur=?";
        $mail=$pdo->prepare($getemail);
        $mail->execute([$id]);
        $mailuser=$mail->fetch(PDO::FETCH_ASSOC);
        print_r($mailuser);

        $getname ="SELECT Pseudo FROM Utilisateur WHERE id_Utilisateur=?";
        $name=$pdo->prepare("$getname");
        $name->execute([$id]);
        $nameuser=$name->fetch(PDO::FETCH_ASSOC);
        print_r($nameuser);

    if(isset($_POST['submit'])){
            
            $Message=null;

            //changer pseudo
        
            if(!empty(($_POST['Pseudo']))){
                if (!preg_match('/^[a-zA-Z0-9_]+$/',$_POST['Pseudo'])) {
                $Message = "Entrer un Pseudo correcte !";
                }
                else{
                $verify_exist_pseudo_request = "SELECT * FROM Utilisateur WHERE Pseudo =?";
                $verify_exist_pseudo = $pdo->prepare($verify_exist_pseudo_request);
                $verify_exist_pseudo->execute([$_POST['Pseudo']]);
                $exist_pseudo = $verify_exist_pseudo->fetch();
                if ($exist_pseudo) {
                    $Message1="Ce pseudo est déja utilisé";
                }
                else{
                $changer_pseudo=$pdo->prepare("UPDATE Utilisateur SET Pseudo=? WHERE id_Utilisateur=?");//
                    $changer_pseudo->execute([$_POST['Pseudo'],$id]);
                    if($changer_pseudo){
                        $name=$_POST['Pseudo'];
                        $Message1="success1";//message
                        }
                    }
                }
        
        }
        
        
        
        //changer adresse mail
        
        
            if (!empty(($_POST['email'])))
                {
                $verify_exist_email_request = "SELECT id_Utilisateur FROM Utilisateur WHERE email = ?";
                $verify_exist_email = $pdo->prepare($verify_exist_email_request);
                $verify_exist_email ->execute([$_POST['email']]);
                $exist_email = $verify_exist_email->fetch();
                if ($exist_email) {
                    $Message2= "Cet email est déja utilisé";
                }
                else{
                    $changer_email=$pdo->prepare("UPDATE Utilisateur SET Email= ? WHERE id_Utilisateur=?");//
                    $changer_email->execute([$_POST['email'],$id]);
                    if($changer_email){
                        $mail= $_POST['email'];
                        $Message2='success2';//message
                    }
                }
            }
            
        
        //changer mot de passe
            if(empty(($_POST['password']))){
                if(!empty(($_POST['email']))&&!empty(($_POST['Pseudo']))){
                    header('Location:editiondeprofil.php');
                }
                
            }
            else{
                if(($_POST['password'])!=($_POST['psw_confirm'])){
                    $Message='';
                }
                $pws=password_hash($_POST['password'],PASSWORD_BCRYPT);
                $changer_psw=$pdo->prepare("UPDATE Utilisateur SET Mot_de_passe=? WHERE id_Utilisateur=?");//
                $changer_psw->execute([$pws,$id]);
                if($changer_psw){
                    $Message3='success3';//message
                }
            }
                //$getuser_request="SELECT Pseudo,Email FROM Utilisateur WHERE id_Utilisateur=?"
                //$getuserinfo=$pdo->prepare($getuserinfo_requestas);
                //$getuserinfo=execute([$id]);
                //print_r（mysql_fetch_row($getuserinfo));
               
                
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pinyon+Script" rel="stylesheet">
    <link rel="stylesheet" href="../../owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="../../css/style_dashboard.css">
</head>
<body>
<header>
    <nav class="navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">SuperQ</a>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Nos thématiques</a>
                </li>
            </ul>
            <div class="dropdown">
                <div class="avatar" id="user_options" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,20">
                    <img src="https://img.icons8.com/ios/50/000000/user-filled.png" class="dropdown-toggle">
                </div>
                <div class="dropdown-menu" aria-labelledby="user_options">
                    <a class="dropdown-item" href="#">Modifier le profil</a>
                    <a class="dropdown-item" href="../Espace_membre/deconnexion.php">Se deconnecter</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<div></div>


<?php
//echo ("Votre Pseudo:$n</br>");
//echo("Votre Email:$m");
?></p>

<div class=main-content>
        <form action="editiondeprofil.php" method="post">
    <div class=form>
            <div class=text>Pseudo:</div>

            <div class="form-group"> <input type="text" name="Pseudo" 
            placeholder="Entrer un nouveau pseudo"  >
            <label for="Pseudo"><div class=text1>*Enter un nouveau pseudo</div></label> </div>

            <div class=text>E-mail: </div>

            <div class="form-group"><input type="email" name="email" 
            placeholder="Entrer un nouveau email" >
            <label for="email"><div class=text1>*Enter une nouvelle adresse mail</div></label> </div>

            <div class=text>Mot de passe:</div>

            <div class="form-group"> <input type="password" name="password" 
            placeholder="Entrer un nouveau mot de passe" >
            <label for="Mot_de_passe"><div class=text1>*Enter un nouveau mot de passe</div></label> </div>

            <div class=text>Veification de mot de passe:</div>

            <div class="form-group"> <input type="password" name="psw_confirm" 
            placeholder="Veification de mot de passe" >
            <label for="Mot_de_passe"><div class=text1>*Veification de mot de passe</div></label> </div>

            <button class="button" type="submit" name="submit">modifier</button>
    </div>
</div>
    <style type="text/css">
    .main-content{
        background-color: #80add4bf;
        border: 1px solid #ffffff73;
        height: 800px;
        margin: auto;
        margin-bottom: 50px;
    }
    .form{
        width: 480px;
        height: 600px;
        background-color: white;
        border-radius: 20px;
        margin-left: 400px;
    }
    input{
        border-color: #cae6d2;
        border-radius: 5px;
        height: 35px;
        width:400px;
        box-shadow: 0px 2px 2px #96bce2;
}
    .text{
        text-align: left;
        margin-top: 40px;
        margin-left: 40px;
        font-family: 'MS Sans Serif', Geneva, sans-serif;
        font-size: 20px;
    }
    .text1{
        float: left;
        margin-top: 5px;
        margin-left: 40px;
        font-family: 'MS Sans Serif', Geneva, sans-serif;
        font-size: 15px;
        color: #f50000c2;
    }

    .form-group { 
        position: relative;
        padding-top: 1.5rem;
        float: right;
        text-align: center;
    }
    label { 
        position: relative;
        float: left;
        font-size: var(--font-size-small);
        opacity: 1;
        transform: translateY(0);
        transition: all 0.2s ease-out;
            }
    input:placeholder-shown + label { 
            opacity: 0; 
            transform: translateY(1rem);
            }
    .button {
        margin-left: 170px;
        font-family: 'MS Sans Serif', Geneva, sans-serif;
        margin-top: 10px;
        background-color: #e7e7e7;
        color: black;
        padding: 15px 32px;
        text-align: center;
        display: inline-block;
        font-size: 20px;
        border-radius: 8px;
        border: 2px solid #4CAF50;
        -webkit-transition-duration: 0.4s;
        transition-duration: 0.4s;
    }
    .button:hover {
        background-color: #4CAF50; /* Green */
        color: white;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
    }
</style>
<?php

//echo "<script type='text/javascript'>alert('$Message');</script>";



?>
</form>
</body>
</html>