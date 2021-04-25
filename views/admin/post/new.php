<?php

use App\Auth;
use App\Connexion;
use App\HTML\Form;
use App\Model\Post;
use App\Objects;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();

$pdo=Connexion::getPDO();
$categoryTable=new CategoryTable($pdo);
$categories=$categoryTable->list();
$success=false;
$errors=[];
$post=new Post;


if(!empty($_POST)){
    $postTable=new PostTable($pdo);
    $v=new PostValidator($_POST,$postTable,$post->getID(),$categories);
    if($v->validate()){
        Objects::hydrate($post,$_POST,['name','slug','content','created_at']);
        $postTable->createPost($post,$_POST['categories_ids']);
        header('Location: ' . $router->url('admin_post',['id' => $post->getID() ]));
        exit();
    }else{
        $errors=$v->errors();
    }
}

$form=new Form($post,$errors);

?>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu être enregistré. Merci de corriger le formulaire.
    </div>
<?php endif ?>

<h1>Création d'un article</h1>

<?php require '_form.php' ?>