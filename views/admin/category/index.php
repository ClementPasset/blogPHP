<?php

use App\Auth;
use App\Connexion;
use App\Table\CategoryTable;

Auth::check();

$title="Gestion des catgéories";
$pdo=Connexion::getPDO();
$link = $router->url('admin_categories');
$items=(new CategoryTable($pdo))->all();
?>

<?php if(isset($_GET['delete'])): ?>
    <div class="alert alert-success">
        La catégorie a bien été supprimée.
    </div>
<?php endif ?>

<h1 class="mb-4">Administration des catégories</h1>

<table class="table">
    <thead>
        <th>ID</th>
        <th>Titre</th>
        <th>URL</th>
        <th><a href="<?= $router->url('admin_category_new') ?>" class="btn btn-primary">Nouveau</a></th>
    </thead>
    <tbody>
        <?php foreach($items as $item): ?>
            <tr>
                <td>#<?= $item->getID() ?></td>
                <td>
                    <a href="<?= $router->url('admin_category',['id'=>$item->getID()]) ?>"><?= htmlentities($item->getName()) ?></a>
                </td>
                <td>
                    <?= $item->getSlug() ?>
                </td>
                <td>
                    <a href="<?= $router->url('admin_category',['id'=>$item->getID()]) ?>" class="btn btn-primary">Éditer</a>
                    <form style="display:inline" action="<?= $router->url('admin_category_delete',['id'=>$item->getID()]) ?>" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer la catégorie ?')">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
