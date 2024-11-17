<?php
// Marcos Lopez Medina
session_start(); // Inicia la sessió
require 'model/db.php'; // Connexió a la base de dades
require 'articles.php'; // Inclou la lògica per mostrar articles

// Comprova si l'usuari està connectat
$is_logged_in = isset($_SESSION['usuario']);

// Obtenir missatges de sessió
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Netejar missatges de sessió
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió d'Articles</title>
    <link rel="stylesheet" href="estils/style.css">
</head>
<body>
    <header>
        <h1>Llista d'Articles</h1>
    </header>

    <main>
        <div class="messages">
            <?php if ($success_message): ?>
                <div class="success" style="color: green;"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="error" style="color: red;"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>

        <div class="articulos-list">
            <?php
                // Mostra la llista d'articles carregats des de la BD
                echo mostrarArticulos();
            ?>
        </div>

        <div class="actions">
            <?php if ($is_logged_in): ?>
                <!-- Botons si l'usuari està connectat -->
                <a href="vista/Inserir.html">Inserir Article</a>
                <a href="vista/Modificar.html">Modificar Article</a>
                <a href="vista/Esborrar.html">Eliminar Article</a>
                <a href="controladores/logout.php">Tancar Sessió</a>
            <?php else: ?>
                <!-- Botons si l'usuari no està connectat -->
                <button onclick="mostrarFormulario('login')">Logar-se</button>
                <button onclick="mostrarFormulario('register')">Registrar-se</button>
            <?php endif; ?>
        </div>
    </main>
    <div id="form-background"></div>

    <div id="form-container">
        <div id="form-content"></div>
    </div>

    <script src="scripts/main.js"></script>
    <script>
        function mostrarFormulario(tipo) {
            var container = document.getElementById('form-container');
            var content = document.getElementById('form-content');

            if (tipo === 'login') {
                content.innerHTML = `
                    <form action="controladores/login.php" method="POST">
                        <label for="email">Email:</label>
                        <input type="text" name="email" required>
                        <label for="contraseña">Contrasenya:</label>
                        <input type="password" name="contraseña" required>
                        <button type="submit">Iniciar Sessió</button>
                        <button type="button" onclick="cerrarFormulario()">Tancar</button>
                    </form>`;
            } else if (tipo === 'register') {
                // Mantener los datos del formulario si hay errores
                var nombre = '<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>';
                var email = '<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>';
                
                content.innerHTML = `
                    <form action="controladores/register.php" method="POST">
                        <label for="nombre">Nom:</label>
                        <input type="text" name="nombre" value="${nombre}" required>
                        <label for="email">Email:</label>
                        <input type="text" name="email" value="${email}" required>
                        <label for="contraseña">Contrasenya:</label>
                        <input type="password" name="contraseña" required>
                        <label for="confirmar_contraseña">Confirmar Contrasenya:</label>
                        <input type="password" name="confirmar_contraseña" required>
                        <button type="submit">Registrar-se</button>
                        <button type="button" onclick="cerrarFormulario()">Tancar</button>
                    </form>`;
            }

            container.style.display = 'block';
        }

        function cerrarFormulario() {
            document.getElementById('form-container').style.display = 'none';
        }
    </script>
</body>
</html>
