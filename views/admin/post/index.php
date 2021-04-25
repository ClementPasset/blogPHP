<?php

use App\Auth;
use App\Connexion;
use App\Table\PostTable;

Auth::check();

$title="Gestion des articles";
$pdo=Connexion::getPDO();
$link = $router->url('admin_posts');
[$posts,$pagination]=(new PostTable($pdo))->findPaginated();
?>

<?php if(isset($_GET['delete'])): ?>
    <div class="alert alert-success">
        L'article a bien été supprimé.
    </div>
<?php endif ?>

<h1 class="mb-4">Administration des articles</h1>

<table class="table">
    <thead>
        <th>ID</th>
        <th>Titre</th>
        <th><a href="<?= $router->url('admin_post_new') ?>" class="btn btn-primary">Nouveau</a></th>
    </thead>
    <tbody>
        <?php foreach($posts as $post): ?>
            <tr>
                <td>#<?= $post->getID() ?></td>
                <td>
                    <a href="<?= $router->url('admin_post',['id'=>$post->getID()]) ?>"><?= htmlentities($post->getName()) ?></a>
                </td>
                <td>
                    <a href="<?= $router->url('admin_post',['id'=>$post->getID()]) ?>" class="btn btn-primary">Éditer</a>
                    <form style="display:inline" action="<?= $router->url('admin_post_delete',['id'=>$post->getID()]) ?>" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer l\'article ?')">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link) ?>
    <?= $pagination->nextLink($link) ?>
</div>