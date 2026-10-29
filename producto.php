<?php
include "includes/header.php";
include "includes/conexion.php";

$id = (int) ($_GET["id"] ?? 0);

$mensajeNoEncontrado = '
    <main class="producto-detalle">
        <div class="container">
            <div class="catalog-empty">
                <h2>Producto no encontrado</h2>
                <p>Puede que el modelo ya no esté disponible.</p>
                <a href="catalogo.php" class="btn-lumiere btn-lumiere--dark">VOLVER AL CATÁLOGO</a>
            </div>
        </div>
    </main>';

if ($id <= 0) {
    echo $mensajeNoEncontrado;
    include "includes/footer.php";
    exit;
}

$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

if (!$producto) {
    echo $mensajeNoEncontrado;
    include "includes/footer.php";
    exit;
}


$sqlRelacionados = "
    SELECT * FROM productos
    WHERE id <> ?
    ORDER BY (coleccion = ?) DESC, RAND()
    LIMIT 4
";

$stmtRelacionados = $conexion->prepare($sqlRelacionados);
$stmtRelacionados->bind_param("is", $producto["id"], $producto["coleccion"]);
$stmtRelacionados->execute();
$relacionados = $stmtRelacionados->get_result();


$clasesColor = [
    "negro"   => "color-black",
    "carey"   => "color-carey",
    "azul"    => "color-blue",
    "dorado"  => "color-gold",
    "blanco"  => "color-white",
    "violeta" => "color-violet"
];

$claseColor = $clasesColor[strtolower($producto["color"])] ?? "";


$nombre = htmlspecialchars($producto["nombre"]);
$imagen = htmlspecialchars($producto["imagen"]);
$coleccion = htmlspecialchars($producto["coleccion"]);
$forma = htmlspecialchars($producto["forma"]);
$color = htmlspecialchars($producto["color"]);
$material = htmlspecialchars($producto["material"]);
$precio = number_format($producto["precio"], 0, ",", ".");
?>

<main>

    <section class="producto-detalle">

        <div class="container">

            <div class="producto-detalle__top">

                <p class="page-breadcrumb mb-0">
                    <a href="index.php">Inicio</a> /
                    <a href="catalogo.php">Catálogo</a> /
                    <strong><?php echo $nombre; ?></strong>
                </p>

                <a href="catalogo.php" class="section-link">
                    ← VOLVER AL CATÁLOGO
                </a>

            </div>

            <div class="row g-5 align-items-start">

                <div class="col-12 col-lg-7">

                    <div class="producto-detalle__imagen">

                        <img
                            src="img/productos/<?php echo $imagen; ?>"
                            alt="<?php echo $nombre; ?>"
                        >

                        <a
                            href="img/productos/<?php echo $imagen; ?>"
                            target="_blank"
                            rel="noopener"
                            class="producto-detalle__zoom"
                            title="Ver imagen completa"
                        >
                            ⌕
                        </a>

                    </div>

                    <div class="producto-thumbs">

                        <div class="producto-thumb is-active">
                            <img src="img/productos/<?php echo $imagen; ?>" alt="<?php echo $nombre; ?> - vista general">
                        </div>

                        <div class="producto-thumb producto-thumb--izq">
                            <img src="img/productos/<?php echo $imagen; ?>" alt="<?php echo $nombre; ?> - detalle de la montura">
                        </div>

                        <div class="producto-thumb producto-thumb--der">
                            <img src="img/productos/<?php echo $imagen; ?>" alt="<?php echo $nombre; ?> - detalle de la varilla">
                        </div>

                    </div>

                </div>

                <div class="col-12 col-lg-5">

                    <div class="producto-detalle__info">

                        <p class="product-meta">
                            Colección <?php echo $coleccion; ?>
                        </p>

                        <h1 class="producto-detalle__titulo">
                            <?php echo $nombre; ?>
                        </h1>

                        <p class="producto-detalle__precio">
                            $<?php echo $precio; ?>
                        </p>

                        <p class="producto-detalle__descripcion">
                            <?php echo htmlspecialchars($producto["descripcion"]); ?>
                        </p>

                        <div class="producto-detalle__datos">

                            <div>
                                <span>Forma</span>
                                <strong><?php echo $forma; ?></strong>
                            </div>

                            <div>
                                <span>Color</span>
                                <strong>
                                    <?php if ($claseColor !== "") { ?>
                                        <span class="color-dot <?php echo $claseColor; ?>"></span>
                                    <?php } ?>
                                    <?php echo $color; ?>
                                </strong>
                            </div>

                            <div>
                                <span>Material</span>
                                <strong><?php echo $material; ?></strong>
                            </div>

                        </div>

                        <p class="producto-detalle__pedido">
                            <span class="producto-detalle__pedido-icon">◷</span>
                            <span>
                                Este modelo se fabrica <strong>bajo pedido</strong>.
                                Te confirmamos disponibilidad y tiempos por email.
                            </span>
                        </p>

                        <div class="d-grid gap-2">

                            <a
                                href="carrito.php?agregar=<?php echo $producto["id"]; ?>"
                                class="btn-lumiere btn-producto"
                            >   
                                <i class="bi bi-bag"></i> AGREGAR AL CARRITO
                            </a>

                            <a
                                href="contacto.php?modelo=<?php echo urlencode($producto["nombre"]); ?>"
                                class="btn-lumiere btn-producto-secundario"
                            >
                                CONSULTAR MODELO
                            </a>

                            <a
                                href="favoritos.php?id=<?php echo $producto["id"]; ?>"
                                class="btn-lumiere btn-producto-secundario"
                            >
                                <i class="bi bi-heart"></i> GUARDAR EN FAVORITOS
                            </a>

                        </div>

                        <div class="producto-detalle__extras">
                            <span>✉ Asesoramiento por email</span>
                            <span>◇ Fabricación a pedido</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="producto-editorial">

        <div class="container">

            <div class="row g-5">

                <div class="col-12 col-lg-6">

                    <p class="eyebrow eyebrow--dark">
                        SOBRE NUESTRA PIEZA
                    </p>

                    <h2 class="section-title">
                        Una visión en clave <em>contemporánea</em>
                    </h2>

                    <div class="producto-editorial__texto">

                        <p>
                            <?php echo $nombre; ?> es una montura de forma
                            <?php echo strtolower($forma); ?> en
                            <?php echo strtolower($material); ?>, pensada para
                            acompañar el día a día con una presencia sutil.
                            Su diseño equilibra líneas limpias y proporciones
                            cuidadas, en diálogo con el espíritu de la
                            colección <?php echo $coleccion; ?>.
                        </p>

                        <p>
                            Cada pieza se fabrica bajo pedido. Si tenés dudas
                            sobre medidas o graduación, escribinos y te
                            asesoramos por email.
                        </p>

                    </div>

                </div>

                <div class="col-12 col-lg-5 offset-lg-1">

                    <p class="eyebrow eyebrow--dark">
                        ESPECIFICACIONES
                    </p>

                    <table class="producto-specs">

                        <tr>
                            <th>Colección</th>
                            <td><?php echo $coleccion; ?></td>
                        </tr>

                        <tr>
                            <th>Forma</th>
                            <td><?php echo $forma; ?></td>
                        </tr>

                        <tr>
                            <th>Color</th>
                            <td><?php echo $color; ?></td>
                        </tr>

                        <tr>
                            <th>Material</th>
                            <td><?php echo $material; ?></td>
                        </tr>

                        <tr>
                            <th>Disponibilidad</th>
                            <td>Bajo pedido</td>
                        </tr>

                        <tr>
                            <th>Precio</th>
                            <td>$<?php echo $precio; ?></td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </section>


    <?php if ($relacionados->num_rows > 0) { ?>

        <section class="producto-relacionados">

            <div class="container">

                <div class="featured-heading">

                    <div>

                        <p class="eyebrow eyebrow--dark">
                            SEGUÍ EXPLORANDO
                        </p>

                        <h2 class="section-title">
                            También te puede <em>gustar</em>
                        </h2>

                    </div>

                    <a href="catalogo.php" class="section-link">
                        VER CATÁLOGO →
                    </a>

                </div>

                <div class="row g-4">

                    <?php while ($item = $relacionados->fetch_assoc()) { ?>

                        <div class="col-6 col-lg-3">

                            <article class="catalog-product">

                                <div class="catalog-product__image">

                                    <a href="producto.php?id=<?php echo $item["id"]; ?>">
                                        <img
                                            src="img/productos/<?php echo htmlspecialchars($item["imagen"]); ?>"
                                            alt="<?php echo htmlspecialchars($item["nombre"]); ?>"
                                        >
                                    </a>

                                    <a
                                        href="favoritos.php?id=<?php echo $item["id"]; ?>"
                                        class="catalog-heart"
                                        title="Guardar en favoritos"
                                    >
                                        ♡
                                    </a>

                                </div>

                                <div class="catalog-product__meta">
                                    <?php echo htmlspecialchars($item["coleccion"]); ?>
                                    ·
                                    <?php echo htmlspecialchars($item["material"]); ?>
                                </div>

                                <div class="catalog-product__bottom">

                                    <h2>
                                        <?php echo htmlspecialchars($item["nombre"]); ?>
                                    </h2>

                                    <span>
                                        $<?php echo number_format($item["precio"], 0, ",", "."); ?>
                                    </span>

                                </div>

                            </article>

                        </div>

                    <?php } ?>

                </div>

            </div>

        </section>

    <?php } ?>

</main>

<?php
include "includes/footer.php";
?>