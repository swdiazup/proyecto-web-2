<?php
session_start();
include "includes/conexion.php";

$usuarioActivo = $_SESSION["usuario"] ?? null;


// Si no inició sesión y quiere agregar algo, lo mandamos al login
if (!$usuarioActivo && isset($_GET["agregar"])) {
    header("Location: registro.php");
    exit;
}


if ($usuarioActivo) {

    $idUsuario = (int) $usuarioActivo["id"];


    // AGREGAR: si ya estaba en el carrito, suma 1 a la cantidad
    if (isset($_GET["agregar"])) {

        $idProducto = (int) $_GET["agregar"];

        $stmt = $conexion->prepare("SELECT id FROM productos WHERE id = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {

            $stmt = $conexion->prepare("
                INSERT INTO carrito (id_usuario, id_producto, cantidad)
                VALUES (?, ?, 1)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + 1
            ");
            $stmt->bind_param("ii", $idUsuario, $idProducto);
            $stmt->execute();
        }

        header("Location: carrito.php");
        exit;
    }


    // SUMAR 1
    if (isset($_GET["sumar"])) {

        $idProducto = (int) $_GET["sumar"];

        $stmt = $conexion->prepare("UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = ? AND id_producto = ?");
        $stmt->bind_param("ii", $idUsuario, $idProducto);
        $stmt->execute();

        header("Location: carrito.php");
        exit;
    }


    // RESTAR 1 (nunca baja de 1; para sacarlo se usa "Quitar")
    if (isset($_GET["restar"])) {

        $idProducto = (int) $_GET["restar"];

        $stmt = $conexion->prepare("UPDATE carrito SET cantidad = cantidad - 1 WHERE id_usuario = ? AND id_producto = ? AND cantidad > 1");
        $stmt->bind_param("ii", $idUsuario, $idProducto);
        $stmt->execute();

        header("Location: carrito.php");
        exit;
    }


    // QUITAR el producto del carrito
    if (isset($_GET["quitar"])) {

        $idProducto = (int) $_GET["quitar"];

        $stmt = $conexion->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
        $stmt->bind_param("ii", $idUsuario, $idProducto);
        $stmt->execute();

        header("Location: carrito.php");
        exit;
    }


    // LISTAR los productos del carrito (une carrito con productos)
    $stmt = $conexion->prepare("
        SELECT p.id, p.nombre, p.precio, p.imagen, p.coleccion, c.cantidad
        FROM carrito c
        INNER JOIN productos p ON p.id = c.id_producto
        WHERE c.id_usuario = ?
    ");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();

    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $total = 0;
    $cantidadTotal = 0;

    foreach ($items as $item) {
        $total += $item["precio"] * $item["cantidad"];
        $cantidadTotal += $item["cantidad"];
    }
}

include "includes/header.php";
?>

<main class="registro-page">

    <div class="container">

        <p class="page-breadcrumb">
            <a href="index.php">Inicio</a> / <strong>Carrito</strong>
        </p>

        <section class="registro-header">

            <p class="eyebrow eyebrow--dark">
                TU SELECCIÓN
            </p>

            <h1 class="page-title">
                Tu <em>carrito</em>
            </h1>

            <p class="page-intro">
                Revisá tus piezas antes de confirmar el pedido.
            </p>

        </section>

        <?php if (!$usuarioActivo) { ?>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="registro-card text-center">
                        <h2>Iniciá sesión</h2>
                        <p class="registro-card__intro">
                            Necesitás una cuenta para usar el carrito.
                        </p>
                        <a href="registro.php" class="btn-lumiere btn-lumiere--dark">
                            INICIAR SESIÓN
                        </a>
                    </div>
                </div>
            </div>

        <?php } elseif (count($items) === 0) { ?>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="registro-card text-center">
                        <h2>Tu carrito está vacío</h2>
                        <p class="registro-card__intro">
                            Explorá el catálogo y encontrá tu próxima pieza.
                        </p>
                        <a href="catalogo.php" class="btn-lumiere btn-lumiere--dark">
                            VER CATÁLOGO
                        </a>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="row g-4 align-items-start">

                <div class="col-12 col-lg-8">

                    <?php foreach ($items as $item) { ?>

                        <article class="carrito-item">

                            <a href="producto.php?id=<?php echo $item["id"]; ?>">
                                <img
                                    src="img/productos/<?php echo htmlspecialchars($item["imagen"]); ?>"
                                    alt="<?php echo htmlspecialchars($item["nombre"]); ?>"
                                    class="carrito-item__img"
                                >
                            </a>

                            <div class="carrito-item__info">

                                <p class="product-meta mb-1">
                                    Colección <?php echo htmlspecialchars($item["coleccion"]); ?>
                                </p>

                                <h2 class="carrito-item__nombre">
                                    <?php echo htmlspecialchars($item["nombre"]); ?>
                                </h2>

                                <p class="carrito-item__precio">
                                    $<?php echo number_format($item["precio"], 0, ",", "."); ?> c/u
                                </p>

                                <a href="carrito.php?quitar=<?php echo $item["id"]; ?>" class="carrito-item__quitar">
                                    Quitar
                                </a>

                            </div>

                            <div class="carrito-cantidad">
                                <a href="carrito.php?restar=<?php echo $item["id"]; ?>" aria-label="Restar uno">−</a>
                                <span><?php echo $item["cantidad"]; ?></span>
                                <a href="carrito.php?sumar=<?php echo $item["id"]; ?>" aria-label="Sumar uno">+</a>
                            </div>

                            <div class="carrito-item__subtotal">
                                $<?php echo number_format($item["precio"] * $item["cantidad"], 0, ",", "."); ?>
                            </div>

                        </article>

                    <?php } ?>

                </div>

                <div class="col-12 col-lg-4">

                    <div class="registro-card carrito-resumen">

                        <h2>Resumen</h2>

                        <div class="carrito-resumen__fila">
                            <span>Productos</span>
                            <span><?php echo $cantidadTotal; ?></span>
                        </div>

                        <div class="carrito-resumen__fila carrito-resumen__total">
                            <span>Total</span>
                            <span>$<?php echo number_format($total, 0, ",", "."); ?></span>
                        </div>

                        <a href="catalogo.php" class="btn-lumiere btn-lumiere--outline-dark w-100">
                            SEGUIR COMPRANDO
                        </a>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</main>

<?php
include "includes/footer.php";
?>
