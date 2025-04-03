<?php $this->layout('layouts/default', ['title' => 'User import preview', 'full' => true]) ?>

<section>
    <h3>Usuarios escaneados</h3>
    <form method="post" action="<?=$this->url('/diff')?>">
        <?php $this->insert('partials/table', ['users' => $users, 'name' => 'users']) ?>
        <button type="submit">Continuar</button>
    </form>
</section>
