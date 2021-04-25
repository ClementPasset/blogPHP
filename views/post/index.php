<?php

use App\Connexion;
use App\Table\PostTable;

$title= "Blog";
$pdo=Connexion::getPDO();

$table=new PostTable($pdo);
[$posts,$pagination]=$table->findPaginated();


$link=$router->url('home');
?>
<h1>Mon blog</h1>

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
                    <p class="text-muted">
                        <?= $post->getCreatedAt()->format('d F Y') ?> <br>
                        <?= implode(', ',$categoriesHTML) ?>
                    </p>
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
    <?= $pagination->previousLink($link); ?>
    <?= $pagination->nextLink($link); ?>
</div>