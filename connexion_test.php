<?php
session_start();
if (!empty($_POST)) {
  require 'Database.php';
  $db_connect = new Database('projet');
  $pdo = $db_connect->getPDO();


  $wrong_inputs = array();

if(!empty($_POST['pseudo'])&&!empty($_POST['psw'])) {

  $verify_exist_pseudo_request = "SELECT Pseudo,Mot_de_passe FROM Utilisateur WHERE Pseudo = ?";
  $verify_exist_pseudo = $pdo->prepare($verify_exist_pseudo_request);
  $verify_exist_pseudo->execute([$_POST['pseudo']]);
  $exist_pseudo = $verify_exist_pseudo->fetch();
  
  if(count($exist_pseudo) > 0){
    if($_POST['psw'] == $results['psw']){
      $pseudo = $results['pseudo'];
    } else {
      $message = "Mot de passe n'est pas correct!";
    }
  } else {
    $message = "Ce pseudo n'existe pas";
  }
}

if( isset($_SESSION['pseudo'])) {
  $records = $db->prepare('SELECT id_Utilisateur,psw FROM Utilisateur WHERE pseudo = :pseudo');
  $records->bindParam(':pseudo', $_POST['pseudo']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $pseudo = $results['pseudo'];
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