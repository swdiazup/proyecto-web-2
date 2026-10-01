<?php
session_start();
include "includes/conexion.php";

$mensaje = "";
$tipoMensaje = "";   
$accion = $_POST["accion"] ?? "";


if (isset($_GET["salir"])) {
    unset($_SESSION["usuario"]);
    header("Location: registro.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    if ($accion === "login") {

        if ($email === "" || $password === "") {

            $mensaje = "Completá tu email y tu contraseña.";
            $tipoMensaje = "error";

        } else {

            $stmt = $conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $usuario = $stmt->get_result()->fetch_assoc();

            if ($usuario && password_verify($password, $usuario["password"])) {

                $_SESSION["usuario"] = [
                    "id" => $usuario["id"],
                    "nombre" => $usuario["nombre"]
                ];

                header("Location: registro.php");
                exit;

            } else {

                $mensaje = "El email o la contraseña no son correctos.";
                $tipoMensaje = "error";
            }
        }
    }


    if ($accion === "registro") {

        $nombre = trim($_POST["nombre"] ?? "");
        $password2 = $_POST["password2"] ?? "";

        if ($nombre === "" || $email === "" || $password === "") {

            $mensaje = "Todos los campos son obligatorios.";
            $tipoMensaje = "error";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $mensaje = "El email no es válido.";
            $tipoMensaje = "error";

        } elseif (strlen($password) < 6) {

            $mensaje = "La contraseña debe tener al menos 6 caracteres.";
            $tipoMensaje = "error";

        } elseif ($password !== $password2) {

            $mensaje = "Las contraseñas no coinciden.";
            $tipoMensaje = "error";

        } else {

            $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {

                $mensaje = "Ya existe una cuenta con ese email.";
                $tipoMensaje = "error";

            } else {

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nombre, $email, $hash);

                if ($stmt->execute()) {
                    $mensaje = "Tu cuenta fue creada. Ya podés iniciar sesión.";
                    $accion = "";   // limpia los campos del formulario
                } else {
                    $mensaje = "No pudimos crear la cuenta. Probá de nuevo.";
                    $tipoMensaje = "error";
                }
            }
        }
    }
}


function valorPrevio($campo, $accionForm, $accionActual)
{
    if ($accionForm !== $accionActual) {
        return "";
    }
    return htmlspecialchars($_POST[$campo] ?? "");
}

$usuarioActivo = $_SESSION["usuario"] ?? null;

include "includes/header.php";
?>

<main class="registro-page">

    <div class="container">

        <p class="page-breadcrumb">
            <a href="index.php">Inicio</a> / <strong>Registro</strong>
        </p>

        <section class="registro-header">

            <p class="eyebrow eyebrow--dark">
                TU CUENTA LUMIÈRE
            </p>

            <h1 class="page-title">
                Te damos la <em>bienvenida</em>
            </h1>

            <p class="page-intro">
                Ingresá para ver tus piezas guardadas
                o creá tu cuenta en pocos pasos.
            </p>

        </section>

        <?php if ($mensaje !== "") { ?>
            <div class="form-alert <?php echo $tipoMensaje === "error" ? "form-alert--error" : ""; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php } ?>

        <?php if ($usuarioActivo) { ?>


            <div class="row justify-content-center">

                <div class="col-12 col-lg-6">

                    <div class="registro-card text-center">

                        <h2>
                            Hola, <?php echo htmlspecialchars($usuarioActivo["nombre"]); ?>
                        </h2>

                        <p class="registro-card__intro">
                            Tu sesión está iniciada.
                        </p>

                        <div class="d-flex flex-wrap justify-content-center gap-2">

                            <a href="favoritos.php" class="btn-lumiere btn-lumiere--dark">
                                VER FAVORITOS
                            </a>

                            <a href="registro.php?salir=1" class="btn-lumiere btn-lumiere--outline-dark">
                                CERRAR SESIÓN
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        <?php } else { ?>

            <div class="row g-4">


                <div class="col-12 col-lg-6">

                    <div class="registro-card form-lumiere">

                        <h2>Iniciar sesión</h2>

                        <p class="registro-card__intro">
                            Accedé con tu email y contraseña.
                        </p>

                        <form action="registro.php" method="POST">

                            <input type="hidden" name="accion" value="login">

                            <div class="mb-3">
                                <label for="login-email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="login-email"
                                    name="email"
                                    class="form-control"
                                    placeholder="tu@email.com"
                                    value="<?php echo valorPrevio("email", "login", $accion); ?>"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="login-password" class="form-label">Contraseña</label>
                                <input
                                    type="password"
                                    id="login-password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Tu contraseña"
                                    required
                                >
                            </div>

                            <div class="registro-card__row">
                                <a href="contacto.php">¿Olvidaste tu contraseña?</a>
                            </div>

                            <button type="submit" class="btn-lumiere btn-lumiere--dark w-100">
                                INGRESAR →
                            </button>

                        </form>

                    </div>

                </div>

                <div class="col-12 col-lg-6">

                    <div class="registro-card form-lumiere">

                        <h2>Crear cuenta</h2>

                        <p class="registro-card__intro">
                            Guardá tus favoritos y consultanos más rápido.
                        </p>

                        <form action="registro.php" method="POST">

                            <input type="hidden" name="accion" value="registro">

                            <div class="mb-3">
                                <label for="reg-nombre" class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    id="reg-nombre"
                                    name="nombre"
                                    class="form-control"
                                    placeholder="Tu nombre"
                                    value="<?php echo valorPrevio("nombre", "registro", $accion); ?>"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="reg-email"
                                    name="email"
                                    class="form-control"
                                    placeholder="tu@email.com"
                                    value="<?php echo valorPrevio("email", "registro", $accion); ?>"
                                    required
                                >
                            </div>

                            <div class="row g-3 mb-4">

                                <div class="col-12 col-sm-6">
                                    <label for="reg-password" class="form-label">Contraseña</label>
                                    <input
                                        type="password"
                                        id="reg-password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Mínimo 6 caracteres"
                                        minlength="6"
                                        required
                                    >
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="reg-password2" class="form-label">Repetir contraseña</label>
                                    <input
                                        type="password"
                                        id="reg-password2"
                                        name="password2"
                                        class="form-control"
                                        placeholder="Repetila"
                                        minlength="6"
                                        required
                                    >
                                </div>

                            </div>

                            <button type="submit" class="btn-lumiere btn-lumiere--outline-dark w-100">
                                CREAR CUENTA
                            </button>

                            <p class="registro-legal">
                                Tus datos se usan solo para gestionar tu cuenta.
                            </p>

                        </form>

                    </div>

                </div>

            </div>

        <?php } ?>


        <section class="editorial-split">

            <div class="editorial-split__image">
                <img src="img/productos/medianoche.jpg" alt="Anteojos Lumière">
            </div>

            <div class="editorial-split__panel">

                <p class="eyebrow eyebrow--gold">
                    BENEFICIOS DE TU CUENTA
                </p>

                <h2>
                    Tu mirada, <em>tu historia.</em>
                </h2>

                <ul class="benefit-list">

                    <li>
                        <span class="benefit-list__icon">♡</span>
                        <div>
                            <strong>Guardá tus piezas favoritas</strong>
                            <span>Volvé a ellas cuando quieras.</span>
                        </div>
                    </li>

                    <li>
                        <span class="benefit-list__icon">✉</span>
                        <div>
                            <strong>Consultas más ágiles</strong>
                            <span>Escribinos sobre cualquier modelo.</span>
                        </div>
                    </li>

                    <li>
                        <span class="benefit-list__icon">✦</span>
                        <div>
                            <strong>Novedades de cada colección</strong>
                            <span>Enterate primero de los nuevos modelos.</span>
                        </div>
                    </li>

                </ul>

            </div>

        </section>

    </div>

</main>

<?php
include "includes/footer.php";
?>