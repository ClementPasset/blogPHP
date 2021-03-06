<?php

use App\Connexion;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id=(int)$params['id'];
$slug=$params['slug'];


$pdo=Connexion::getPDO();

$post=(new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if($post->getSlug()!==$slug){
    $url=$router->url('post',['slug'=>$post->getSlug(),'id'=>$id]);
    http_response_code(301);
    header('Location: '.$url);
}

$title=$post->getName();
?>

<h1><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= ($post->getCreatedAt()->format('d F Y')) ?></p>
<?php foreach($post->getCategories() as $category): ?>
    <a href="<?= $router->url('category',['slug'=>$category->getSlug(),'id'=>$category->getID()]) ?>"><?= htmlentities($category->getName()) ?></a>
<?php endforeach ?>
<p><?= $post->getFormatedContent() ?></p>