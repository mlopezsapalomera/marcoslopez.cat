<?php
//Marcos Lopez Medina
require_once 'model/db.php'; 
function mostrarArticulos() {
    global $conn; 

    // Nombre d'articles per pàgina
    $articulos_por_pagina = 5;

    // Obtenir el nombre total d'articles
    $consultaTotal = $conn->query("SELECT COUNT(*) AS total FROM articulos");
    $total_articulos = $consultaTotal->fetch_assoc()['total'];

    // Calcular el nombre total de pàgines
    $total_paginas = ceil($total_articulos / $articulos_por_pagina);

    // Obtenir la pàgina actual des de la URL o establir 1 com a valor per defecte
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular l'índex del primer article de la pàgina actual
    $inicio = ($pagina_actual - 1) * $articulos_por_pagina;

    // Preparar la consulta per obtenir els articles de la pàgina actual
    $consultaArticulos = $conn->prepare("SELECT id, nombre, cuerpo FROM articulos LIMIT ?, ?");
    $consultaArticulos->bind_param("ii", $inicio, $articulos_por_pagina);
    $consultaArticulos->execute();
    $resultados = $consultaArticulos->get_result();

    // Generar la llista d'articles
    $html = '<ul>';
    while ($articulo = $resultados->fetch_assoc()) {
        $html .= '<li>';
        $html .= '<h3>' . htmlspecialchars($articulo['nombre']) . '</h3>';
        $html .= '<p>' . htmlspecialchars($articulo['cuerpo']) . '</p>';
        $html .= '</li>';
    }
    $html .= '</ul>';

    // Generar els enllaços de paginació
    $html .= '<div class="pagination">';
    if ($pagina_actual > 1) {
        $html .= '<a href="?pagina=' . ($pagina_actual - 1) . '">« Anterior</a>';
    }
    for ($i = 1; $i <= $total_paginas; $i++) {
        if ($i == $pagina_actual) {
            $html .= '<span>' . $i . '</span>';
        } else {
            $html .= '<a href="?pagina=' . $i . '">' . $i . '</a>';
        }
    }
    if ($pagina_actual < $total_paginas) {
        $html .= '<a href="?pagina=' . ($pagina_actual + 1) . '">Següent »</a>';
    }
    $html .= '</div>';

    return $html;
}
?>
