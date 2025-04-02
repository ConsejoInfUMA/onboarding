<?php $this->layout('layouts/default', ['title' => 'Diff', 'full' => true]) ?>

<form method="post" action="<?=$this->url('/diff/apply')?>">
    <section>
        <h3>Usuarios a agregar a la base de datos</h3>
        <?php $this->insert('partials/table', ['users' => $usersAdd, 'name' => 'usersAdd']) ?>
    </section>

    <section>
        <h3>Usuarios sin modificar</h3>
        <?php $this->insert('partials/table', ['users' => $usersOk]) ?>
    </section>

    <section>
        <h3>Usuarios a eliminar de la base de datos</h3>
        <?php $this->insert('partials/table', ['users' => $usersRemove, 'name' => 'usersRemove']) ?>
    </section>

    <button type="submit">Aplicar cambios</button>
</form>
