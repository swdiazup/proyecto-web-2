<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière Ópticos</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark">

        <div class="container">

            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <span class="brand-main">Lumière</span>
                <span class="brand-sub">ÓPTICOS</span>
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Abrir navegación"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="catalogo.php">Catálogo</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="favoritos.php">Favoritos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="registro.php">Registro</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contacto</a>
                    </li>

                </ul>

                <div class="header-actions d-flex align-items-center gap-3">
                    <a href="catalogo.php" class="header-icon" aria-label="Buscar"><i class="bi bi-search"></i></a>
                    <a href="favoritos.php" class="header-icon" aria-label="Favoritos"><i class="bi bi-heart"></i></a>
                    <a href="carrito.php" class="header-icon" aria-label="Carrito"><i class="bi bi-bag"></i></a>

                    <?php if (isset($_SESSION['usuario'])): ?>
                        <a href="#" class="header-icon avatar" aria-label="Mi cuenta">
                            <?php echo mb_strtoupper(mb_substr($_SESSION['usuario']['nombre'], 0, 1)); ?>
                        </a>
                    <?php else: ?>
                        <a href="registro.php" class="header-icon" aria-label="Iniciar sesión"><i class="bi bi-person"></i></a>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    </nav>
</header>