<?php $this->layout('layouts/default', ['title' => 'Error']) ?>

<h3>There was an error processing your request!</h3>
<p><?=$this->e($error)?></p>
