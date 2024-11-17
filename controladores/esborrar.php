<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

// Comprova si l'usuari està connectat
if (!isset($_SESSION['usuario'])) {
    $_SESSION['error_message'] = "Accés denegat. Has d'iniciar sessió.";
    header("Location: ../index.php");
    exit();
}

// Verifica si s'ha rebut l'ID de l'article
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Preparar la consulta per verificar si l'article existeix
    $consultaVerificar = $conn->prepare("SELECT id FROM articulos WHERE id = ?");
    $consultaVerificar->bind_param("i", $id);
    $consultaVerificar->execute();
    $consultaVerificar->store_result();

    // Comprovar si l'article existeix
    if ($consultaVerificar->num_rows > 0) {
        // L'article existeix, procedim a eliminar
        $consultaEliminar = $conn->prepare("DELETE FROM articulos WHERE id = ?");
        $consultaEliminar->bind_param("i", $id);

        if ($consultaEliminar->execute()) {
            $_SESSION['success_message'] = "Article eliminat correctament.";
        } else {
            $_SESSION['error_message'] = "Error en eliminar l'article: " . $conn->error;
        }

        $consultaEliminar->close();
    } else {
        // L'article no existeix
        $_SESSION['error_message'] = "Aquest article no existeix.";
    }

    $consultaVerificar->close();
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
} else {
    $_SESSION['error_message'] = "No s'ha especificat l'ID de l'article.";
    header("Location: ../index.php");
    exit();
}
?>
