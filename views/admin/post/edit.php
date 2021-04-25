<?php

use App\Auth;
use App\Connexion;
use App\HTML\Form;
use App\Objects;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$pdo=Connexion::getPDO();
$postTable=new PostTable($pdo);
$categoryTable=new CategoryTable($pdo);
$categories=$categoryTable->list();
$post=$postTable->find($params['id']);
$categoryTable->hydratePosts([$post]);
$success=false;
$errors=[];

if(!empty($_POST)){
    $v=new PostValidator($_POST,$postTable,$post->getID(),$categories);
    if($v->validate()){
        Objects::hydrate($post,$_POST,['name','slug','content','created_at']);
        $postTable->updatePost($post,$_POST['categories_ids']);
        $categoryTable->hydratePosts([$post]);
        $success=true;
    }else{
        $errors=$v->errors();
    }
}

$form=new Form($post,$errors);

?>

<?php if($success): ?>
    <div class="alert alert-success">
        L'article a bien été modifié.
    </div>
<?php endif ?>
<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu être modifié. Merci de corriger le formulaire.
    </div>
<?php endif ?>

<h1>Édition de l'article <?= htmlentities($post->getName())?></h1>

<?php require '_form.php' ?>