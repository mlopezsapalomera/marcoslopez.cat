<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenir dades del formulari
    $id = $_POST['id']; // ID de l'article
    $nombre = $_POST['nombre'];
    $cuerpo = $_POST['cuerpo'];

    // Modificar article
    $query = "UPDATE articulos SET nombre = ?, cuerpo = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssi', $nombre, $cuerpo, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Article modificat correctament.";
    } else {
        $_SESSION['error_message'] = "Error en modificar l'article: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}

mysqli_close($conn);
?>