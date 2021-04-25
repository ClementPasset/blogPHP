<?php

use App\Connexion;
use App\HTML\Form;
use App\Model\User;
use App\Table\UserTable;

$pdo=Connexion::getPDO();
$user = new User;
$userTable=new UserTable($pdo);
$errors=[];


if(!empty($_POST)){
    if(empty($_POST['username']) || empty($_POST['password'])){
        $user->setUsername($_POST['username']);
        $errors['password'][]="Identifiant ou mot de passe incorrect.";
    }
    try{
        $u=$userTable->findByUsername($_POST['username']);
        if(password_verify($_POST['password'],$u->getPassword()) === false){
            $user->setUsername($_POST['username']);
            $errors['password'][]="Identifiant ou mot de passe incorrect.";
        }else{
            session_start();
            $_SESSION['auth']=$u->getID();
            header('Location: ' . $router->url('admin_posts'));
            exit();
        };
    }catch(Exception $e){
        $user->setUsername($_POST['username']);
        $errors['password'][]="Identifiant ou mot de passe incorrect.";
    }
}

$form=new Form($user,$errors);
?>

<h1>Se connecter</h1>

<?php if(isset($_GET['forbidden'])): ?>
    <div class="alert alert-danger">
        Veuillez vous connecter pour accéder à la page demandée.
    </div>
<?php endif ?>

<form action="<?= $router->url('login') ?>" method="POST">
    <?= $form->input('username',"Nom d'utilisateur") ?>
    <?= $form->input('password',"Mot de passe") ?>
    <button class="btn btn-primary" type="submit">Se connecter</button>
</form>