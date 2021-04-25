<?php http_response_code(404); ?>

<h1>Page introuvable</h1>

<?php if(isset($e)): ?>
    <p><?= $e->getMessage() ?></p>
<?php endif ?>