<?php $this->layout('layouts/default', ['title' => 'Login']) ?>

<form method="post" action="<?= $this->url('/login') ?>">
    <p>
        <label>Password</label>
        <input type="password" name="password">
    </p>

    <button type="submit">Send</button>
</form>
