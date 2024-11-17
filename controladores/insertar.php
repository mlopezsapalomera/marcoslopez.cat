<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $cuerpo = $_POST['cuerpo'];

    // Preparar i executar la consulta d'inserció
    $query = "INSERT INTO articulos (nombre, cuerpo) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nombre, $cuerpo);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Article insertat correctament.";
    } else {
        $_SESSION['error_message'] = "Error en insertar l'article: " . $conn->error;
    }
    
    $stmt->close();
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}

?>