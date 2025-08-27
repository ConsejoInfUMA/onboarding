<?php $this->layout('layouts/default', ['title' => 'Invites', 'full' => true]) ?>

<section>
    <h3>Usuarios invitados</h3>
    <?php $this->insert('partials/table', ['users' => $users]) ?>
</section>
