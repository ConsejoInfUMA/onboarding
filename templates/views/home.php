<?php $this->layout('layouts/default', ['title' => 'Inicio']) ?>

<h3>Cuenta</h3>

<form method="post" action="<?= $this->url('/') ?>">
    <p>
        <label>Correo electrónico</label>
        <input type="email" name="email">
    </p>
    <button type="submit">Buscar</button>
</form>
