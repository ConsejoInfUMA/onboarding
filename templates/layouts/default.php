<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <link rel="stylesheet" href="<?=$this->url('/css/main.css')?>">
    <title><?= $this->e($title) ?> - Onboarding CEETSII</title>
</head>

<body>
    <header>
        <picture>
            <source class="icon" srcset="<?=$this->url('/assets/logo-white.png')?>" media="(prefers-color-scheme: dark)" />
            <source class="icon" srcset="<?=$this->url('/assets/logo-multi.png')?>" media="(prefers-color-scheme: light)" />
            <img class="icon" src="<?=$this->url('/assets/logo-multi.png')?>" />
        </picture>
        <h1>Onboarding CEETSII</h1>
        <nav>
            <ul>
                <li>
                    <a href="<?=$this->url('/')?>">Home</a>
                </li>
                <li>
                    <a href="<?=$this->url('/invites')?>">Invites</a>
                </li>
                <?php if ($this->isLoggedIn()): ?>
                <li>
                    <a href="<?=$this->url('/logout')?>">Logout</a>
                </li>
                <?php endif ?>
            </ul>
        </nav>
    </header>
    <?php if (isset($full) && $full): ?>
    <main style="grid-column: unset;">
    <?php else: ?>
    <main>
    <?php endif ?>
        <?= $this->section('content') ?>
    </main>
</body>

</html>
