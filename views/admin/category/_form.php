<form action="" method="POST">
    <?= $form->input('name','Titre') ?>
    <?= $form->input('slug','URL') ?>
    <button class="btn btn-primary"><?php if($item->getID()===null){ echo 'Créer';}else{echo 'Modifier';} ?></button>
</form>
