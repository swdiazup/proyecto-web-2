<?php
include "includes/header.php";
$enviado = isset($_GET["enviado"]);


$modelo = $_GET["modelo"] ?? "";
$asuntoInicial = $modelo !== "" ? "Consulta por el modelo " . $modelo : "";
?>

<main>

    <section class="contacto-hero">

        <div class="container">

            <p class="page-breadcrumb">
                <a href="index.php">Inicio</a> / <strong>Contacto</strong>
            </p>

            <p class="eyebrow eyebrow--dark">
                ESTAMOS PARA VOS
            </p>

            <h1 class="contacto-hero__title">
                Hablemos
            </h1>

            <p class="contacto-hero__text">
                Estamos para ayudarte a encontrar la montura ideal
                y responder todas tus consultas.
            </p>

        </div>

    </section>


    <section class="contacto-section">

        <div class="container">

            <div class="row g-5">


                <div class="col-12 col-lg-5">

                    <div class="contacto-info">

                        <h2>
                            Sobre <em>Lumière</em>
                        </h2>

                        <p>
                            Lumière nace de una idea simple: que elegir
                            anteojos sea tan personal como tu forma de mirar.
                            Seleccionamos monturas ópticas y de sol con diseño
                            contemporáneo y materiales de calidad.
                        </p>

                        <p>
                            Cada modelo se fabrica bajo pedido, y te
                            acompañamos por email en todo el proceso.
                        </p>

                        <div class="contacto-email">

                            <span>Email</span>

                            <a href="mailto:hola@lumiereopticos.com">
                                hola@lumiereopticos.com
                            </a>

                        </div>

                        <div class="contacto-redes">

                            <span>Seguinos</span>

                            <div class="d-flex gap-3">
                                <a href="#" class="social-icon">◎</a>
                                <a href="#" class="social-icon">f</a>
                                <a href="#" class="social-icon">p</a>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-lg-7">

                    <div class="contacto-form form-lumiere">

                        <?php if ($enviado) { ?>
                            <div class="form-alert">
                                ¡Gracias! Recibimos tu consulta y te vamos a responder por email.
                            </div>
                        <?php } ?>

                        <h2>Envianos tu consulta</h2>

                        <p class="contacto-form__intro">
                            Completá el formulario y te respondemos a la brevedad.
                        </p>

                        <form action="recibirContacto.php" method="POST">

                            <div class="row g-3">

                                <div class="col-12 col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input
                                        type="text"
                                        id="nombre"
                                        name="nombre"
                                        class="form-control"
                                        placeholder="Tu nombre"
                                        required
                                    >
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="tu@email.com"
                                        required
                                    >
                                </div>

                                <div class="col-12">
                                    <label for="asunto" class="form-label">Asunto</label>
                                    <input
                                        type="text"
                                        id="asunto"
                                        name="asunto"
                                        class="form-control"
                                        placeholder="¿Sobre qué querés consultarnos?"
                                        value="<?php echo htmlspecialchars($asuntoInicial); ?>"
                                        required
                                    >
                                </div>

                                <div class="col-12">
                                    <label for="mensaje" class="form-label">Mensaje</label>
                                    <textarea
                                        id="mensaje"
                                        name="mensaje"
                                        class="form-control"
                                        placeholder="Escribí tu mensaje"
                                        required
                                    ></textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn-lumiere btn-contacto">
                                        ENVIAR CONSULTA →
                                    </button>

                                    <p class="form-help">
                                        Te respondemos únicamente por email.
                                    </p>
                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <section class="editorial-split">

                <div class="editorial-split__image">
                    <img src="img/productos/citrino.jpg" alt="Anteojos Lumière">
                </div>

                <div class="editorial-split__panel">

                    <p class="eyebrow eyebrow--gold">
                        NUESTRA FILOSOFÍA
                    </p>

                    <h2>
                        Tu mirada, <em>nuestra inspiración.</em>
                    </h2>

                    <p>
                        Creemos que unos anteojos no son solo un accesorio:
                        son la forma en que elegís mostrarte al mundo.
                    </p>

                </div>

            </section>

        </div>

    </section>

</main>

<?php
include "includes/footer.php";
?>