<?php

session_start();
include "includes/conexion.php";

$usuarioActivo = $_SESSION["usuario"] ?? null;
$idUsuario = $usuarioActivo ? (int) $usuarioActivo["id"] : 0;

if (!isset($_SESSION["favoritos"])) {
    $_SESSION["favoritos"] = [];
}


function agregarFavoritoBD($conexion, $idUsuario, $idProducto)
{
    $stmt = $conexion->prepare("SELECT id FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
    $stmt->bind_param("ii", $idUsuario, $idProducto);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        $stmt = $conexion->prepare("INSERT INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
        $stmt->bind_param("ii", $idUsuario, $idProducto);
        $stmt->execute();
    }
}



if ($idUsuario > 0 && !empty($_SESSION["favoritos"])) {

    foreach ($_SESSION["favoritos"] as $idGuardado) {
        agregarFavoritoBD($conexion, $idUsuario, (int) $idGuardado);
    }

    $_SESSION["favoritos"] = [];
}



if (isset($_GET["id"])) {

    $idAgregar = (int) $_GET["id"];

    if ($idAgregar > 0) {

        if ($idUsuario > 0) {
            agregarFavoritoBD($conexion, $idUsuario, $idAgregar);
        } elseif (!in_array($idAgregar, $_SESSION["favoritos"])) {
            $_SESSION["favoritos"][] = $idAgregar;
        }
    }

    header("Location: favoritos.php");
    exit;
}



if (isset($_GET["quitar"])) {

    $idQuitar = (int) $_GET["quitar"];

    if ($idUsuario > 0) {

        $stmt = $conexion->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
        $stmt->bind_param("ii", $idUsuario, $idQuitar);
        $stmt->execute();

    } else {

        $_SESSION["favoritos"] = array_values(
            array_diff($_SESSION["favoritos"], [$idQuitar])
        );
    }

    header("Location: favoritos.php");
    exit;
}



$favoritos = [];

if ($idUsuario > 0) {

    $sql = "
        SELECT p.*
        FROM favoritos f
        INNER JOIN productos p ON p.id = f.id_producto
        WHERE f.id_usuario = ?
        ORDER BY f.id DESC
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();

    $favoritos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} elseif (!empty($_SESSION["favoritos"])) {

    $ids = $_SESSION["favoritos"];
    $marcadores = implode(",", array_fill(0, count($ids), "?"));

    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id IN ($marcadores)");
    $stmt->bind_param(str_repeat("i", count($ids)), ...$ids);
    $stmt->execute();

    $favoritos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$totalFavoritos = count($favoritos);

include "includes/header.php";
?>

<main class="favoritos-page">

    <div class="container">

        <p class="page-breadcrumb">
            <a href="index.php">Inicio</a> / <strong>Favoritos</strong>
        </p>

        <section class="favoritos-header">

            <div>

                <p class="eyebrow eyebrow--dark">
                    TU SELECCIÓN
                </p>

                <h1 class="page-title">
                    Piezas <em>guardadas</em>
                </h1>

                <p class="page-intro">
                    Los modelos que guardaste para mirar con calma,
                    comparar y consultarnos cuando quieras.

                    <?php if ($idUsuario === 0) { ?>
                        <a href="registro.php" class="text-decoration-underline">Iniciá sesión</a>
                        para conservarlos en tu cuenta.
                    <?php } ?>
                </p>

            </div>

            <div class="favoritos-header__aside">

                <span class="catalog-count">
                    <?php echo $totalFavoritos; ?>
                    <?php echo $totalFavoritos === 1 ? "PIEZA" : "PIEZAS"; ?>
                </span>

                <a href="catalogo.php" class="section-link">
                    SEGUIR EXPLORANDO →
                </a>

            </div>

        </section>

        <?php if ($totalFavoritos > 0) { ?>

            <div class="row g-4">

                <?php foreach ($favoritos as $producto) { ?>

                    <div class="col-12 col-sm-6 col-lg-4">

                        <article class="catalog-product">

                            <div class="catalog-product__image">

                                <a href="producto.php?id=<?php echo $producto["id"]; ?>">
                                    <img
                                        src="img/productos/<?php echo htmlspecialchars($producto["imagen"]); ?>"
                                        alt="<?php echo htmlspecialchars($producto["nombre"]); ?>"
                                    >
                                </a>

                                <a
                                    href="favoritos.php?quitar=<?php echo $producto["id"]; ?>"
                                    class="catalog-heart catalog-heart--active"
                                    title="Quitar de favoritos"
                                >
                                    ♥
                                </a>

                            </div>

                            <div class="catalog-product__meta">
                                <?php echo htmlspecialchars($producto["coleccion"]); ?>
                                ·
                                <?php echo htmlspecialchars($producto["material"]); ?>
                            </div>

                            <div class="catalog-product__bottom">

                                <h2>
                                    <?php echo htmlspecialchars($producto["nombre"]); ?>
                                </h2>

                                <span>
                                    $<?php echo number_format($producto["precio"], 0, ",", "."); ?>
                                </span>

                            </div>

                            <div class="fav-actions">

                                <a
                                    href="producto.php?id=<?php echo $producto["id"]; ?>"
                                    class="btn-lumiere btn-lumiere--sm btn-lumiere--dark"
                                >
                                    VER PRODUCTO
                                </a>

                                <a
                                    href="favoritos.php?quitar=<?php echo $producto["id"]; ?>"
                                    class="btn-lumiere btn-lumiere--sm btn-lumiere--outline-dark"
                                >
                                    QUITAR
                                </a>

                            </div>

                        </article>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="catalog-empty">

                <h2>Todavía no guardaste piezas</h2>

                <p>
                    Tocá el corazón de cualquier modelo del catálogo
                    para guardarlo acá.
                </p>

                <a href="catalogo.php" class="btn-lumiere btn-lumiere--dark">
                    IR AL CATÁLOGO
                </a>

            </div>

        <?php } ?>

        <section class="cta-azul">

            <div>

                <p class="eyebrow eyebrow--gold">
                    ATENCIÓN PERSONALIZADA
                </p>

                <h2>
                    ¿Dudas sobre algún <em>modelo</em>?
                </h2>

                <p>
                    Escribinos y te acompañamos a elegir la montura ideal.
                </p>

            </div>

            <a href="contacto.php" class="btn-lumiere btn-lumiere--outline-light">
                HACER UNA CONSULTA →
            </a>

        </section>

    </div>

</main>

<?php
include "includes/footer.php";
?>