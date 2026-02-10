<?php $this->layout('layouts/default', ['title' => 'Invites', 'full' => true]) ?>

<section>
    <h3>Usuarios invitados</h3>
    <figure>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo electrónico</th>
                    <th>Nombre de usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $i => $user): ?>
                    <tr>
                        <td><?= $this->e($user->firstName) ?></td>
                        <td><?= $this->e($user->lastName) ?></td>
                        <td><?= $this->e($user->email) ?></td>
                        <td><?= $this->e($user->username) ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </figure>

</section>
