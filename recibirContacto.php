<?php

include "includes/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = $_POST["nombre"] ?? "";
    $email = $_POST["email"] ?? "";
    $asunto = $_POST["asunto"] ?? "";
    $mensaje = $_POST["mensaje"] ?? "";


    if (
        empty($nombre) ||
        empty($email) ||
        empty($asunto) ||
        empty($mensaje)
    ) {

        die("Todos los campos son obligatorios.");

    }


        $sql = "
        INSERT INTO contactos
        (nombre, email, asunto, mensaje, fechaEnvio)
        VALUES (?, ?, ?, ?, CURDATE())
    ";
    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $nombre,
        $email,
        $asunto,
        $mensaje
    );


    if ($stmt->execute()) {

        header("Location: contacto.php?enviado=1");
        exit;

    } else {

        echo "Error al enviar la consulta.";

    }

} else {

    header("Location: contacto.php");
    exit;

}

?>