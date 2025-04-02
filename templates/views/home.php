<?php $this->layout('layouts/default', ['title' => 'Home']) ?>

<h3>Dashboard</h3>

<form method="post" action="<?= $this->url('/') ?>" enctype="multipart/form-data">
    <p>
        <label>CSV</label>
        <input accept="text/csv" type="file" name="csv">
    </p>

    <button type="submit">Start import</button>
</form>
