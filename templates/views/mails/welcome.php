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
        ¡Bienvenid@! :)
    </p>

    <p>
        A todos los miembros del consejo se le crea una cuenta en nuestro servidor.
    </p>

    <p>
        Para activar tu cuenta haz click <a href="<?=$this->url('/register', ['token' => $token])?>">aquí</a>
    </p>

    <p>¡Un saludo!</p>
</body>
</html>
