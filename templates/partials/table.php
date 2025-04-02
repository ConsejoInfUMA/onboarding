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
                    <?php $this->insert('partials/cell', ['name' => $name, 'i' => $i, 'key' => 'firstName', 'value' => $user->firstName]) ?>
                    <?php $this->insert('partials/cell', ['name' => $name, 'i' => $i, 'key' => 'lastName', 'value' => $user->lastName]) ?>
                    <?php $this->insert('partials/cell', ['name' => $name, 'i' => $i, 'key' => 'email', 'type' => 'email', 'value' => $user->email]) ?>
                    <?php $this->insert('partials/cell', ['name' => $name, 'i' => $i, 'key' => 'username', 'value' => $user->username]) ?>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</figure>
