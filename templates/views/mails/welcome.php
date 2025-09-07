<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$this->url('/css/main.css')?>">
    <title>¡Bienvenido, <?=$this->e($user->firstName)?>!</title>
</head>
<body>
    <p>
        Hola <?=$this->e($user->firstName)?>,
    </p>
    <p>
        Debido a que eres representante de asignatura,
        formas parte del <a href="https://www.uma.es/etsi-informatica/info/126304/consejo-de-estudiantes/" target="_blank">Consejo de Estudiantes de la ETSII</a>.
    </p>
    <p>
        ¡Bienvenid@! :)
    </p>

    <p>
        A todos los miembros del consejo se le crea una cuenta en nuestro servidor.
    </p>

    <p>
        Para activar tu cuenta haz click <a href="<?=$this->url('/register', ['token' => $token])?>">aquí</a>
    </p>

    <p>¡Un saludo!</p>
    <br>
    <img class="logo" src="<?=$this->url('/assets/logo-wide.png')?>" />
</body>
</html>
