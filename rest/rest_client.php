<?php
/* ACTIVIDAD NF4
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Utilizaremos funciones vista pero como muchas funciones difieren en detalles como rutas o el valor de entrada vamos a copiarlas
// Con esto procuraremos que se siga la línea visual de la app original

// Creamos el inicio de un HTML
echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <title>API-REST eduFlow</title>
            <meta charset='UTF-8' />
            <link rel='icon' type='image/x-icon' href='../vista/IMG/favicon.png'/>
            <link rel='stylesheet' href='../vista/estilos.css' />
            <script src='../vista/eduflow.js' defer></script>
        </head>
    ";
// Creamos el body
echo "<body>";
// Llamamos a la cabecera
echo "
            <header>
                <img src='../vista/IMG/logo.png' alt='eduFlow' class='logo' />
            </header>
        ";
// Creamos el main
echo "<main>";
// Título
echo "<p class='titulo-pagina'>Listar Clases</p>";

    // Formulario para realizar el filtrado
    echo
    '<form method="GET" action="">
        <label for="profesor">Filtrar por profesor:</label>
        <input type="text" name="profesor" id="profesor" placeholder="ej: María López">
        <button type="submit">Listar</button>
    </form>';

    // Comprobación de que el método es GET y existe el parámetro del profesor
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['profesor'])) {
        // Obtención del valor del parámetro profesor
        $profesor = $_GET['profesor'];
        // Crea la url del servicio REST añadiendo el nombre del profesor
        // ej: http://localhost/eduFlow/rest/rest_client.php?profesor=Carlos+Ruiz
        $url = "http://localhost/Eduflow/rest/rest_server.php/" . urlencode($profesor);
    } else {
        // Si no, se carga todo, sin filtro
        $url = "http://localhost/Eduflow/rest/rest_server.php/";
    }

    // Variable que envía una petición HTTP GET a la $url y devuelve un string JSON que almacenamos
    $response = file_get_contents($url);
    // Convertimos la variable anterior (string JSON) en un array que podamos recorrer o manipular
    $clases = json_decode($response, true);

    // Recorremos la variable $clases para imprimir las que correspondan según la petición en bloques div como los que usa la aplicación original
    foreach ($clases as $clase):
        echo "
        <div class='lista-clases'>
        <div class='cabecera-clases'>
            <p>"; echo $clase['dia']; echo "</p>
        </div>
        <div class='contenido-clases'>
            <div class='lineas-clases'>
                <p class='descriptor-clases'>Asignatura</p> <p class='info-clases'>"; echo $clase['asignatura']; echo "</p>
            </div>
            <div class='lineas-clases'>
                <p class='descriptor-clases'>Profesor/a</p> <p class='info-clases'>"; echo $clase['profesor']; echo "</p>
            </div>
            <div class='lineas-clases'>
                <p class='descriptor-clases'>Hora</p> <p class='info-clases'>"; echo $clase['hora']; echo "</p>
            </div>
            <div class='lineas-clases'>
                <p class='descriptor-clases'>Aula</p> <p class='info-clases'>"; echo $clase['aula']; echo "</p>
            </div>
        </div>
    </div>
    ";
    endforeach;

// Cerramos el HTML
echo "</main>";
echo "</body>";
echo "</html>";

?>