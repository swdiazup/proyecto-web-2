<?php
include "includes/header.php";
include "includes/conexion.php";


$buscar = $_GET["buscar"] ?? "";
$coleccion = $_GET["coleccion"] ?? "";
$forma = $_GET["forma"] ?? "";
$color = $_GET["color"] ?? "";
$material = $_GET["material"] ?? "";
$orden = $_GET["orden"] ?? "destacados";


$sql = "SELECT * FROM productos WHERE 1=1";

$tipos = "";
$valores = [];


if ($buscar !== "") {
    $sql .= " AND (
        nombre LIKE ?
        OR coleccion LIKE ?
        OR forma LIKE ?
        OR color LIKE ?
    )";

    $busqueda = "%" . $buscar . "%";

    $tipos .= "ssss";

    $valores[] = $busqueda;
    $valores[] = $busqueda;
    $valores[] = $busqueda;
    $valores[] = $busqueda;
}


if ($coleccion !== "") {
    $sql .= " AND coleccion = ?";
    $tipos .= "s";
    $valores[] = $coleccion;
}


if ($forma !== "") {
    $sql .= " AND forma = ?";
    $tipos .= "s";
    $valores[] = $forma;
}


if ($color !== "") {
    $sql .= " AND color = ?";
    $tipos .= "s";
    $valores[] = $color;
}


if ($material !== "") {
    $sql .= " AND material = ?";
    $tipos .= "s";
    $valores[] = $material;
}


switch ($orden) {

    case "precio-menor":
        $sql .= " ORDER BY precio ASC";
        break;

    case "precio-mayor":
        $sql .= " ORDER BY precio DESC";
        break;

    case "nombre":
        $sql .= " ORDER BY nombre ASC";
        break;

    default:
        $sql .= " ORDER BY id ASC";
        break;
}


$stmt = $conexion->prepare($sql);

if (!empty($valores)) {
    $stmt->bind_param($tipos, ...$valores);
}

$stmt->execute();

$resultado = $stmt->get_result();

$totalProductos = $resultado->num_rows;

function urlFiltro($clave, $valor)
{
    $params = array_filter($_GET, function ($v) {
        return $v !== "";
    });

    if (($params[$clave] ?? "") === $valor) {
        unset($params[$clave]);
    } else {
        $params[$clave] = $valor;
    }

    return "catalogo.php" . (empty($params) ? "" : "?" . http_build_query($params));
}


$colecciones = ["Morrocoy", "London", "Azul", "Ámbar", "Vértice"];

$formas = ["Redonda", "Cat Eye", "Cuadrada", "Rectangular", "Aviador"];

$colores = [
    "Negro"  => "color-black",
    "Carey"  => "color-carey",
    "Azul"   => "color-blue",
    "Dorado" => "color-gold"
];

$materiales = ["Metal", "Acetato"];
?>


<main class="catalog-page">

    <div class="container catalog-container">

        <p class="catalog-breadcrumb">
            Inicio / Catálogo
        </p>

        <section class="catalog-header">

            <div>

                <h1>
                    La <em>colección</em>
                </h1>

            </div>

            <div class="catalog-header__text">

                <p>
                    Monturas ópticas y de sol, seleccionadas
                    por una visión muy tuya.
                </p>

                <p>
                    Cada modelo está disponible bajo pedido.
                </p>

            </div>

        </section>

        <section class="catalog-tools">

            <form
                action="catalogo.php"
                method="GET"
                class="catalog-search"
            >

                <span>⌕</span>

                <input
                    type="text"
                    name="buscar"
                    placeholder="Buscar por nombre, forma o color..."
                    value="<?php echo htmlspecialchars($buscar); ?>"
                >

                <button type="submit">
                    BUSCAR
                </button>

            </form>


            <span class="catalog-count">
                <?php echo $totalProductos; ?> PRODUCTOS
            </span>


            <form method="GET" class="catalog-sort">

                <input
                    type="hidden"
                    name="buscar"
                    value="<?php echo htmlspecialchars($buscar); ?>"
                >

                <input
                    type="hidden"
                    name="coleccion"
                    value="<?php echo htmlspecialchars($coleccion); ?>"
                >

                <input
                    type="hidden"
                    name="forma"
                    value="<?php echo htmlspecialchars($forma); ?>"
                >

                <input
                    type="hidden"
                    name="color"
                    value="<?php echo htmlspecialchars($color); ?>"
                >

                <input
                    type="hidden"
                    name="material"
                    value="<?php echo htmlspecialchars($material); ?>"
                >


                <label for="orden">
                    Ordenar por
                </label>

                <select
                    id="orden"
                    name="orden"
                    onchange="this.form.submit()"
                >

                    <option
                        value="destacados"
                        <?php if ($orden === "destacados") echo "selected"; ?>
                    >
                        Destacados
                    </option>

                    <option
                        value="precio-menor"
                        <?php if ($orden === "precio-menor") echo "selected"; ?>
                    >
                        Menor precio
                    </option>

                    <option
                        value="precio-mayor"
                        <?php if ($orden === "precio-mayor") echo "selected"; ?>
                    >
                        Mayor precio
                    </option>

                    <option
                        value="nombre"
                        <?php if ($orden === "nombre") echo "selected"; ?>
                    >
                        Nombre
                    </option>

                </select>

            </form>

        </section>

        <div class="row gy-4 catalog-body">

            <aside class="col-12 col-lg-3 catalog-sidebar">

                <div class="filter-heading">

                    <span>FILTROS</span>

                    <a href="catalogo.php">
                        Limpiar filtros
                    </a>

                </div>

                <div class="filter-group">

                    <h3>COLECCIÓN</h3>

                    <?php foreach ($colecciones as $item) { ?>

                        <a
                            href="<?php echo htmlspecialchars(urlFiltro("coleccion", $item)); ?>"
                            class="<?php echo $coleccion === $item ? 'active' : ''; ?>"
                        >
                            <span class="filter-square"></span>
                            <?php echo $item; ?>
                        </a>

                    <?php } ?>

                </div>

                <div class="filter-group">

                    <h3>FORMA</h3>

                    <?php foreach ($formas as $item) { ?>

                        <a
                            href="<?php echo htmlspecialchars(urlFiltro("forma", $item)); ?>"
                            class="<?php echo $forma === $item ? 'active' : ''; ?>"
                        >
                            <span class="filter-square"></span>
                            <?php echo $item; ?>
                        </a>

                    <?php } ?>

                </div>

                <div class="filter-group">

                    <h3>COLOR</h3>

                    <?php foreach ($colores as $item => $claseColor) { ?>

                        <a
                            href="<?php echo htmlspecialchars(urlFiltro("color", $item)); ?>"
                            class="<?php echo $color === $item ? 'active' : ''; ?>"
                        >
                            <span class="color-dot <?php echo $claseColor; ?>"></span>
                            <?php echo $item; ?>
                        </a>

                    <?php } ?>

                </div>

                <div class="filter-group">

                    <h3>MATERIAL</h3>

                    <?php foreach ($materiales as $item) { ?>

                        <a
                            href="<?php echo htmlspecialchars(urlFiltro("material", $item)); ?>"
                            class="<?php echo $material === $item ? 'active' : ''; ?>"
                        >
                            <span class="filter-square"></span>
                            <?php echo $item; ?>
                        </a>

                    <?php } ?>

                </div>

                <div class="filter-group">

                    <h3>PRECIO</h3>

                    <div class="price-line">
                        <span></span>
                    </div>

                    <div class="price-values">

                        <span>$45.000</span>

                        <span>$65.000</span>

                    </div>

                </div>

            </aside>

            <section class="col-12 col-lg-9">

                <?php if ($totalProductos > 0) { ?>

                    <div class="row g-4">

                        <?php while ($producto = $resultado->fetch_assoc()) { ?>

                            <div class="col-12 col-sm-6 col-lg-4">

                                <article class="catalog-product">

                                    <div class="catalog-product__image">

                                        <a
                                            href="producto.php?id=<?php echo $producto["id"]; ?>"
                                        >

                                            <img
                                                src="img/productos/<?php echo htmlspecialchars($producto["imagen"]); ?>"
                                                alt="<?php echo htmlspecialchars($producto["nombre"]); ?>"
                                            >

                                        </a>


                                        <a
                                            href="favoritos.php?id=<?php echo $producto["id"]; ?>"
                                            class="catalog-heart"
                                            title="Guardar en favoritos"
                                        >
                                            ♡
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

                                </article>

                            </div>

                        <?php } ?>

                    </div>

                    <section class="catalog-help">

                        <div class="catalog-help__content">

                            <h3>
                                ¿Buscás algo en particular?
                            </h3>

                            <p>
                                Contanos qué modelo imaginás
                                y te ayudamos a encontrarlo.
                            </p>

                        </div>

                        <a
                            href="contacto.php"
                            class="catalog-help__button"
                        >
                            HACER UNA CONSULTA →
                        </a>

                    </section>

                <?php } else { ?>

                    <div class="catalog-empty">

                        <h2>
                            No encontramos productos
                        </h2>

                        <p>
                            Probá cambiando los filtros.
                        </p>

                        <a href="catalogo.php" class="btn-lumiere btn-lumiere--dark">
                            VER TODOS LOS PRODUCTOS
                        </a>

                    </div>

                <?php } ?>

            </section>

        </div>

    </div>

</main>


<?php
include "includes/footer.php";
?>