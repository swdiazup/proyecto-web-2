<?php
session_start();
include "includes/conexion.php";

$mensaje = "";
$tipoMensaje = "";
$exito = false;
$accion = $_POST["accion"] ?? "";


// Cancelar: vuelve a empezar desde el paso 1
if (isset($_GET["cancelar"])) {
    unset($_SESSION["recuperar"]);
    header("Location: recuperar.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // PASO 1: buscar el usuario por email
    if ($accion === "buscar") {

        $email = trim($_POST["email"] ?? "");

        if ($email === "") {

            $mensaje = "Ingresá tu email.";
            $tipoMensaje = "error";

        } else {

            $stmt = $conexion->prepare("SELECT id, preguntaSeguridad FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $usuario = $stmt->get_result()->fetch_assoc();

            if (!$usuario) {

                $mensaje = "No encontramos una cuenta con ese email.";
                $tipoMensaje = "error";

            } elseif ($usuario["preguntaSeguridad"] === null) {

                $mensaje = "Esta cuenta no tiene pregunta de seguridad. Escribinos desde Contacto.";
                $tipoMensaje = "error";

            } else {

                $_SESSION["recuperar"] = [
                    "id" => $usuario["id"],
                    "pregunta" => $usuario["preguntaSeguridad"],
                    "verificado" => false
                ];
            }
        }
    }


    // PASO 2: comprobar la respuesta
    if ($accion === "responder" && isset($_SESSION["recuperar"])) {

        $respuesta = trim($_POST["respuesta"] ?? "");

        $stmt = $conexion->prepare("SELECT respuestaSeguridad FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $_SESSION["recuperar"]["id"]);
        $stmt->execute();

        $usuario = $stmt->get_result()->fetch_assoc();

        if ($usuario && password_verify(mb_strtolower($respuesta), $usuario["respuestaSeguridad"])) {

            $_SESSION["recuperar"]["verificado"] = true;

        } else {

            $mensaje = "La respuesta no es correcta.";
            $tipoMensaje = "error";
        }
    }


    // PASO 3: guardar la contraseña nueva
    if ($accion === "cambiar" && !empty($_SESSION["recuperar"]["verificado"])) {

        $password = $_POST["password"] ?? "";
        $password2 = $_POST["password2"] ?? "";

        if (strlen($password) < 6) {

            $mensaje = "La contraseña debe tener al menos 6 caracteres.";
            $tipoMensaje = "error";

        } elseif ($password !== $password2) {

            $mensaje = "Las contraseñas no coinciden.";
            $tipoMensaje = "error";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $_SESSION["recuperar"]["id"]);

            if ($stmt->execute()) {
                unset($_SESSION["recuperar"]);
                $exito = true;
            } else {
                $mensaje = "No pudimos cambiar la contraseña. Probá de nuevo.";
                $tipoMensaje = "error";
            }
        }
    }
}


// ¿En qué paso estamos?
if ($exito) {
    $paso = 0;
} elseif (!empty($_SESSION["recuperar"]["verificado"])) {
    $paso = 3;
} elseif (isset($_SESSION["recuperar"])) {
    $paso = 2;
} else {
    $paso = 1;
}

include "includes/header.php";
?>

<main class="registro-page">

    <div class="container">

        <p class="page-breadcrumb">
            <a href="index.php">Inicio</a> / <a href="registro.php">Registro</a> / <strong>Recuperar contraseña</strong>
        </p>

        <section class="registro-header">

            <p class="eyebrow eyebrow--dark">
                TU CUENTA LUMIÈRE
            </p>

            <h1 class="page-title">
                Recuperá tu <em>contraseña</em>
            </h1>

            <p class="page-intro">
                Respondé tu pregunta de seguridad y elegí una contraseña nueva.
            </p>

        </section>

        <?php if ($mensaje !== "") { ?>
            <div class="form-alert <?php echo $tipoMensaje === "error" ? "form-alert--error" : ""; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php } ?>

        <div class="row justify-content-center">

            <div class="col-12 col-lg-6">

                <div class="registro-card form-lumiere">

                    <?php if ($paso === 0) { ?>

                        <div class="text-center">
                            <h2>¡Listo!</h2>
                            <p class="registro-card__intro">
                                Tu contraseña fue cambiada. Ya podés iniciar sesión.
                            </p>
                            <a href="registro.php" class="btn-lumiere btn-lumiere--dark">
                                INICIAR SESIÓN
                            </a>
                        </div>

                    <?php } elseif ($paso === 1) { ?>

                        <h2>Paso 1 de 3</h2>

                        <p class="registro-card__intro">
                            Ingresá el email con el que creaste tu cuenta.
                        </p>

                        <form action="recuperar.php" method="POST">

                            <input type="hidden" name="accion" value="buscar">

                            <div class="mb-4">
                                <label for="rec-email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    id="rec-email"
                                    name="email"
                                    class="form-control"
                                    placeholder="tu@email.com"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn-lumiere btn-lumiere--dark w-100">
                                CONTINUAR →
                            </button>

                        </form>

                    <?php } elseif ($paso === 2) { ?>

                        <h2>Paso 2 de 3</h2>

                        <p class="registro-card__intro">
                            <?php echo htmlspecialchars($_SESSION["recuperar"]["pregunta"]); ?>
                        </p>

                        <form action="recuperar.php" method="POST">

                            <input type="hidden" name="accion" value="responder">

                            <div class="mb-4">
                                <label for="rec-respuesta" class="form-label">Tu respuesta</label>
                                <input
                                    type="text"
                                    id="rec-respuesta"
                                    name="respuesta"
                                    class="form-control"
                                    placeholder="Tu respuesta"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn-lumiere btn-lumiere--dark w-100">
                                VERIFICAR →
                            </button>

                        </form>

                    <?php } else { ?>

                        <h2>Paso 3 de 3</h2>

                        <p class="registro-card__intro">
                            Elegí tu contraseña nueva.
                        </p>

                        <form action="recuperar.php" method="POST">

                            <input type="hidden" name="accion" value="cambiar">

                            <div class="row g-3 mb-4">

                                <div class="col-12 col-sm-6">
                                    <label for="rec-password" class="form-label">Contraseña nueva</label>
                                    <input
                                        type="password"
                                        id="rec-password"
                                        name="password"
                                        class="form-control"
                                        placeholder="Mínimo 6 caracteres"
                                        minlength="6"
                                        required
                                    >
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label for="rec-password2" class="form-label">Repetir contraseña</label>
                                    <input
                                        type="password"
                                        id="rec-password2"
                                        name="password2"
                                        class="form-control"
                                        placeholder="Repetila"
                                        minlength="6"
                                        required
                                    >
                                </div>

                            </div>

                            <button type="submit" class="btn-lumiere btn-lumiere--dark w-100">
                                GUARDAR CONTRASEÑA
                            </button>

                        </form>

                    <?php } ?>

                    <?php if ($paso === 2 || $paso === 3) { ?>
                        <p class="registro-legal text-center">
                            <a href="recuperar.php?cancelar=1">Empezar de nuevo</a>
                        </p>
                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</main>

<?php
include "includes/footer.php";
?>
