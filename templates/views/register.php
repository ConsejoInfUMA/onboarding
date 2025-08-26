<?php $this->layout('layouts/default', ['title' => 'Register']) ?>

<h3>Registro</h3>

<p>¡Bienvenid@, <?=$this->e($user->firstName)?>!</p>

<form method="post" action="<?= $this->url('/register') ?>" enctype="multipart/form-data">
    <fieldset>
        <input type="hidden" name="token" value="<?=$this->e($token)?>">
        <label>
            Email
            <input type="text" aria-label="Email" value="<?=$this->e($user->email)?>" readonly disabled>
        </label>
        <label>
            Username
            <input type="text" name="username" aria-label="Username" value="<?=$this->e($user->username)?>">
        </label>
        <label>
            Password
            <input type="password" name="password" placeholder="Password" aria-label="Password">
        </label>
        <label>
            Confirm password
            <input type="password" name="password_confirm" placeholder="Password Confirm" aria-label="Password Confirm">
        </label>
    </fieldset>

    <button type="submit">Register</button>
</form>
