<?php

use App\Auth;
use App\Connexion;
use App\HTML\Form;
use App\Model\Category;
use App\Objects;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();

$success=false;
$errors=[];
$item=new Category;


if(!empty($_POST)){
    $pdo=Connexion::getPDO();
    $table=new CategoryTable($pdo);

    $v=new CategoryValidator($_POST,$table);
    if($v->validate()){
        Objects::hydrate($item,$_POST,['name','slug']);
        $id=$table->create([
            'name'=>$item->getName(),
            'slug'=>$item->getSlug()
        ]);
        header('Location: ' . $router->url('admin_category',['id' => $id ]));
        exit();
    }else{
        $errors=$v->errors();
    }
}

$form=new Form($item,$errors);

?>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        La catégorie n'a pas pu être enregistrée. Merci de corriger le formulaire.
    </div>
<?php endif ?>

<h1>Création d'une catégorie</h1>

<?php require '_form.php' ?>