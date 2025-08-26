<?php $this->layout('layouts/default', ['title' => 'Diff results']) ?>

<?php if(count($errors['usersAdd']) === 0 && count($errors['usersRemove']) === 0 ): ?>
    <h3>Todo OK</h3>
<?php endif ?>

<?php if (count($errors['usersAdd']) > 0): ?>
    <p>Los siguientes usuarios no pudieron ser importados:</p>
    <ul>
        <?php foreach($errors['usersAdd'] as $user): ?>
            <li><?=$this->e($user->getFullName())?> (<?=$this->e($user->email)?>)</li>
        <?php endforeach ?>
    </ul>
<?php endif ?>

<?php if (count($errors['usersRemove']) > 0): ?>
    <p>Los siguientes usuarios no pudieron ser eliminados:</p>
    <ul>
        <?php foreach($errors['usersRemove'] as $user): ?>
            <li><?=$this->e($user->getFullName())?> (<?=$this->e($user->email)?>)</li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
