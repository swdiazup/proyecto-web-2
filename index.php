<?php
include "includes/header.php";
include "includes/conexion.php";

$idsProductos = [];

$resultadoIds = $conexion->query("SELECT id, nombre FROM productos");

while ($fila = $resultadoIds->fetch_assoc()) {
    $idsProductos[$fila["nombre"]] = $fila["id"];
}

function linkProducto($nombre, $idsProductos)
{
    if (isset($idsProductos[$nombre])) {
        return "producto.php?id=" . $idsProductos[$nombre];
    }

   
    return "catalogo.php?buscar=" . urlencode($nombre);
}
?>

<main>

    <section class="hero-home">

        <div class="container">
            <div class="row align-items-center hero-home__row">

                <div class="col-12 col-lg-6 hero-home__content">

                    <p class="eyebrow">
                        LUMIÈRE ÓPTICOS
                    </p>

                    <h1 class="hero-home__title">
                        VISIÓN
                        <br>
                        QUE DEFINE
                        <br>
                        TU ESTILO.
                    </h1>

                    <p class="hero-home__text">
                        Diseño contemporáneo, tecnología óptica
                        y una mirada más tuya. Porque ver bien
                        también es una forma de expresarte.
                    </p>

                    <div class="d-flex flex-wrap gap-3">

                        <a
                            href="catalogo.php"
                            class="btn-lumiere btn-lumiere--light"
                        >
                            VER COLECCIÓN
                        </a>

                        <a
                            href="contacto.php"
                            class="btn-lumiere btn-lumiere--outline"
                        >
                            CONOCÉ LA HISTORIA
                        </a>

                    </div>

                </div>

            </div>
        </div>

    </section>

    <section class="featured-section">

        <div class="container">

            <div class="featured-heading">

                <div>

                    <p class="eyebrow eyebrow--dark">
                        NUESTRA SELECCIÓN
                    </p>

                    <h2 class="section-title">
                        Descubrí tu próximo <em>favorito</em>
                    </h2>

                </div>

                <a
                    href="catalogo.php"
                    class="section-link"
                >
                    VER TODO EL CATÁLOGO →
                </a>

            </div>


            <div class="row g-4">


                <div class="col-6 col-md-4 col-lg">

                    <article class="product-card-simple">

                        <a href="<?php echo linkProducto("Aperol", $idsProductos); ?>">
                            <img
                                src="img/productos/aperol.jpg"
                                alt="Anteojos Aperol"
                                class="product-card-simple__image"
                            >
                        </a>

                        <div class="product-card-simple__info">

                            <p class="product-meta">
                                MORROCOY
                            </p>

                            <h3>
                                Aperol
                            </h3>

                            <span>
                                $52.000
                            </span>

                        </div>

                    </article>

                </div>


                <div class="col-6 col-md-4 col-lg">

                    <article class="product-card-simple">

                        <a href="<?php echo linkProducto("Afternoon Tea", $idsProductos); ?>">
                            <img
                                src="img/productos/afternoon-tea.jpg"
                                alt="Anteojos Afternoon Tea"
                                class="product-card-simple__image"
                            >
                        </a>

                        <div class="product-card-simple__info">

                            <p class="product-meta">
                                LONDON
                            </p>

                            <h3>
                                Afternoon Tea
                            </h3>

                            <span>
                                $56.000
                            </span>

                        </div>

                    </article>

                </div>


                <div class="col-6 col-md-4 col-lg">

                    <article class="product-card-simple">

                        <a href="<?php echo linkProducto("Glaciar", $idsProductos); ?>">
                            <img
                                src="img/productos/glaciar.jpg"
                                alt="Anteojos Glaciar"
                                class="product-card-simple__image"
                            >
                        </a>

                        <div class="product-card-simple__info">

                            <p class="product-meta">
                                AZUL
                            </p>

                            <h3>
                                Glaciar
                            </h3>

                            <span>
                                $50.000
                            </span>

                        </div>

                    </article>

                </div>


                <div class="col-6 col-md-4 col-lg">

                    <article class="product-card-simple">

                        <a href="<?php echo linkProducto("Amatista", $idsProductos); ?>">
                            <img
                                src="img/productos/amatista.jpg"
                                alt="Anteojos Amatista"
                                class="product-card-simple__image"
                            >
                        </a>

                        <div class="product-card-simple__info">

                            <p class="product-meta">
                                ÁMBAR
                            </p>

                            <h3>
                                Amatista
                            </h3>

                            <span>
                                $57.000
                            </span>

                        </div>

                    </article>

                </div>


                <div class="col-6 col-md-4 col-lg">

                    <article class="product-card-simple">

                        <a href="<?php echo linkProducto("Vision", $idsProductos); ?>">
                            <img
                                src="img/productos/vision.jpg"
                                alt="Anteojos Vision"
                                class="product-card-simple__image"
                            >
                        </a>

                        <div class="product-card-simple__info">

                            <p class="product-meta">
                                VÉRTICE
                            </p>

                            <h3>
                                Vision
                            </h3>

                            <span>
                                $61.000
                            </span>

                        </div>

                    </article>

                </div>

            </div>

        </div>

    </section>


    <section class="anteojos-section">

        <div class="container">

            <div class="anteojos-heading">

                <div>

                    <p class="eyebrow">
                        SELECCIÓN
                    </p>

                    <h2 class="anteojos-title">
                        ANTEOJOS
                    </h2>

                </div>


                <div class="d-flex gap-2">

                    <button
                        class="slider-btn"
                        type="button"
                    >
                        ‹
                    </button>

                    <button
                        class="slider-btn"
                        type="button"
                    >
                        ›
                    </button>

                </div>

            </div>


            <div class="row g-4">


                <div class="col-12 col-md-4">

                    <article class="highlight-card">

                        <a href="<?php echo linkProducto("Salitre", $idsProductos); ?>">
                            <img
                                src="img/productos/salitre.jpg"
                                alt="Anteojos Salitre"
                                class="highlight-card__image"
                            >
                        </a>

                        <div class="highlight-card__body">

                            <small>
                                MORROCOY
                            </small>

                            <h3>
                                Salitre
                            </h3>

                            <span>
                                $49.000
                            </span>

                        </div>

                    </article>

                </div>


                <div class="col-12 col-md-4">

                    <article class="highlight-card">

                        <a href="<?php echo linkProducto("Misty", $idsProductos); ?>">
                            <img
                                src="img/productos/misty.jpg"
                                alt="Anteojos Misty"
                                class="highlight-card__image"
                            >
                        </a>

                        <div class="highlight-card__body">

                            <small>
                                LONDON
                            </small>

                            <h3>
                                Misty
                            </h3>

                            <span>
                                $60.000
                            </span>

                        </div>

                    </article>

                </div>


                <div class="col-12 col-md-4">

                    <article class="highlight-card">

                        <a href="<?php echo linkProducto("Ojo de Tigre", $idsProductos); ?>">
                            <img
                                src="img/productos/ojo-de-tigre.jpg"
                                alt="Anteojos Ojo de Tigre"
                                class="highlight-card__image"
                            >
                        </a>

                        <div class="highlight-card__body">

                            <small>
                                ÁMBAR
                            </small>

                            <h3>
                                Ojo de Tigre
                            </h3>

                            <span>
                                $54.000
                            </span>

                        </div>

                    </article>

                </div>

            </div>


            <div class="text-center mt-5">

                <a
                    href="catalogo.php"
                    class="btn-lumiere btn-lumiere--outline-light"
                >
                    VER TODOS LOS PRODUCTOS
                </a>

            </div>

        </div>

    </section>

</main>

<?php
include "includes/footer.php";
?>