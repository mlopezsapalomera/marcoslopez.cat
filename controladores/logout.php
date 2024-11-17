<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
session_unset(); // Despeja totes les variables de sessió
session_destroy(); // Destrueix la sessió
header(header: 'Location: ../index.php'); // Redirigeix l'usuari de tornada a la pàgina principal
exit();
?>