<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió

require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    // Comprovar si les contrasenyes coincideixen
    if ($contraseña !== $confirmar_contraseña) {
        $_SESSION['error_message'] = "Les contrasenyes no coincideixen.";
        $_SESSION['nombre'] = $nombre; // Mantener los datos del formulario
        $_SESSION['email'] = $email; // Mantener los datos del formulario
        header("Location: ../index.php");
        exit();
    }

    // Validar la contrasenya (mínim un caràcter especial, un número, etc.)
    if (!preg_match('/[A-Z]/', $contraseña) || !preg_match('/[0-9]/', $contraseña)) {
        $_SESSION['error_message'] = "La contrasenya ha de contenir almenys una majúscula i un número.";
        $_SESSION['nombre'] = $nombre; // Mantener los datos del formulario
        $_SESSION['email'] = $email; // Mantener los datos del formulario
        header("Location: ../index.php");
        exit();
    }

    // Comprovar si l'usuari ja existeix
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // L'usuari ja existeix
        $_SESSION['error_message'] = "L'usuari ja existeix.";
        $_SESSION['nombre'] = $nombre; // Mantener los datos del formulario
        $_SESSION['email'] = $email; // Mantener los datos del formulario
        $stmt->close();
        header("Location: ../index.php");
        exit();
    }

    // Hashear la contrasenya
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Preparar i executar la consulta d'inserció
    $query = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nombre, $email, $contraseña_hash);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Usuari registrat correctament.";
        unset($_SESSION['nombre'], $_SESSION['email']); // Netejar dades del formulari
    } else {
        $_SESSION['error_message'] = "Error en registrar-se: " . $conn->error;
    }

    $stmt->close();
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}