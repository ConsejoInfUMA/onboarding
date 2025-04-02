<?php $this->layout('layouts/default', ['title' => 'User import preview', 'full' => true]) ?>

<section>
    <h3>Usuarios escaneados</h3>
    <form>
        <figure>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo electrónico</th>
                        <th>Nombre de usuario (Autogenerado)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $i => $user): ?>
                        <tr>
                            <td>
                                <input name="users[<?= $i ?>][firstName]" type="text" value="<?= $this->e($user->firstName) ?>" />
                            </td>
                            <td>
                                <input name="users[<?= $i ?>][lastName]" type="text" value="<?= $this->e($user->lastName) ?>" />
                            </td>
                            <td>
                                <input name="users[<?= $i ?>][email]" type="email" value="<?= $this->e($user->email) ?>" />
                            </td>
                            <td>
                                <input name="users[<?= $i ?>][username]" type="text" value="<?= $this->e($user->username) ?>" />
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </figure>

        <button type="submit">Continuar</button>
    </form>
</section>
