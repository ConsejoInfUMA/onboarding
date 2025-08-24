<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido, <?=$this->e($user->firstName)?>!</title>
</head>
<body>
    <p>
        Hola <?=$this->e($user->firstName)?>,
    </p>
    <p>
        Debido a que eres representante de asignatura, formas parte del Consejo de Estudiantes.
    </p>
    <p>
        ¡Bienvenid@! A todos los miembros del consejo se le genera una cuenta en nuestro servidor.
    </p>
    <p>
        Tenemos disponibles los siguientes servicios:
    </p>
    <ul>
        <li><a href="<?=$this->instance_url('/nextcloud')?>">Nextcloud</a>: Este es nuestro sitio principal de trabajo.</li>
    </ul>

    <p>Éstas son tus credenciales:</p>
    <ul>
        <li>Nombre de usuario: <?=$this->e($user->username)?></li>
        <li>Contraseña: <?=$this->e($user->password)?></li>
    </ul>

    <p>Puedes cambiar tu contraseña <a href="<?=$this->instance_url('/')?>">aquí</a></p>

    <p>¡Un saludo!</p>
</body>
</html>
