<?php

use App\Auth;
use App\Connexion;
use App\HTML\Form;
use App\Objects;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;


Auth::check();

$pdo=Connexion::getPDO();
$table=new CategoryTable($pdo);
$item=$table->find($params['id']);
$success=false;
$errors=[];
$fields=['name','slug'];

if(!empty($_POST)){
    $v=new CategoryValidator($_POST,$table,$item->getID());
    if($v->validate()){
        Objects::hydrate($item,$_POST,$fields);
        $table->update([
            'name'=>$item->getName(),
            'slug'=>$item->getSlug()
        ],$item->getID());
        $success=true;
    }else{
        $errors=$v->errors();
    }
}

$form=new Form($item,$errors);

?>

<?php if($success): ?>
    <div class="alert alert-success">
        La catégorie a bien été modifiée.
    </div>
<?php endif ?>
<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        La catégorie n'a pas pu être modifiée. Merci de corriger le formulaire.
    </div>
<?php endif ?>

<h1>Édition de la catégorie <?= htmlentities($item->getName())?></h1>

<?php require '_form.php' ?>