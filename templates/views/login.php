<?php $this->layout('layouts/default', ['title' => 'Login']) ?>

<form method="post" action="<?= $this->url('/login') ?>">
    <p>
        <label>Password</label>
        <input type="password" name="password">
    </p>

    <?php if(isset($error)): ?>
    <p>
        <small><?=$this->e($error)?></small>
    </p>
    <?php endif ?>

    <button type="submit">Login</button>
</form>
