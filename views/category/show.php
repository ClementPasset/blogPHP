<?php
use App\Connexion;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id=(int)$params['id'];
$slug=$params['slug'];

$pdo=Connexion::getPDO();

$categoryTable=new CategoryTable($pdo);
$category=$categoryTable->find($id);

if($category===null){
    throw new Exception("Aucune catégorie ne correspond à cet ID.");
}
if($category->getSlug()!==$slug){
    $url=$router->url('category',['slug'=>$category->getSlug(),'id'=>$id]);
    http_response_code(301);
    header('Location: '.$url);
}
$title= "Catégorie ".htmlentities($category->getName());

[$posts,$paginatedQuery]=(new PostTable($pdo))->findPaginatedForCategory($category->getID());

$link=$router->url('category',['id'=>$category->getID(),'slug'=>$category->getSlug()]);

?>

<h1> <?= $title ?></h1>

<div class="row">
    <?php foreach($posts as $post): ?>
        <?php
        $categoriesHTML=[];
        foreach($post->getCategories() as $category){
            $url=$router->url('category',['slug'=>$category->getSlug(),'id'=>$category->getID()]);
            $categoriesHTML[]= <<<HTML
            <a href="{$url}">{$category->getName()}</a>
HTML;
        }
        ?>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $post->getName() ?></h5>
                    <p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
                    <?= implode(', ',$categoriesHTML) ?>
                    <p><?= $post->getExcerpt() ?></p>
                    <p>
                        <a href="<?= $router->url('post',['id'=>$post->getId(),'slug'=>$post->getSlug()]) ?>" class="btn btn-primary">Voir plus</a>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousLink($link) ?>
    <?= $paginatedQuery->nextLink($link) ?>
</div>