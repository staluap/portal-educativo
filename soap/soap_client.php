<?php
/* ACTIVIDAD NF4
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

$opciones = [
    // ENDPOINT o PUNTO FINAL
    'location' => 'http://localhost/eduFlow/soap/soap_server.php',
    // DIRECTORIO
    'uri' => 'http://localhost/eduFlow/soap',
    // Seguimiento de errores
    'trace' => 1
];

//Construimos el ENVELOPE o SOBRE del SOAP
// NULL implica que en este caso no hay WSDL
$client = new SoapClient(null, $opciones);

//__________________________________________________________________________

// Utilizaremos funciones vista pero como muchas funciones difieren en detalles como rutas o el valor de entrada vamos a copiarlas
// Con esto procuraremos que se siga la línea visual de la app original

// Usamos la función inicializa la sesión, usamos un if que evite un NOTICE en XAMPP
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Creamos el inicio de un HTML
echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <title>API-POST eduFlow</title>
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



// Comprobamos si hay enviada una variable que recoja el nombre del profesor que usa el servicio
if (isset($_POST['profesor'])) {
    // Si existe, asignamos el nombre del profesor a una variable de sesión
    $_SESSION['profesor'] = $_POST['profesor'];
}


// Encaminamos la vista según si hay sesión de profesor establecida o no
if (isset($_SESSION['profesor'])) {

    // Comprobamos si la página ha recibido un POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Tenemos dos formularios así que distinguimos con un hidden input cuál entra
        switch ($_POST['form_id']) {
            
            case 'del_tareas':
                // Comprobamos que se hayan recibido todas las variables
                if (isset($_POST['asignatura'])) {
                    // Borramos filtrando con asignatura y profesor
                    $client->deleteTareas($_POST['asignatura'], $_SESSION['profesor']);
                }
                break;

            case 'add_tareas':
                // Comprobamos que se han enviado todas las variables del formulario
                if (isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['fecha']) && isset($_POST['urgente']) && isset($_POST['asignatura'])) {
                    // Añadimos
                    $client->addTarea(
                        $_POST['titulo'],
                        $_POST['descripcion'],
                        $_POST['fecha'],
                        $_SESSION['profesor'],
                        $_POST['urgente'],
                        $_POST['asignatura']
                    );
                }
                break;
        }
        // Redirigimos a la propia página para recargar (así recargará el listado)
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }


    // Bienvenida del profesor
    echo "<p class='titulo-bienvenida'>Bienvenid@ "; echo $_SESSION['profesor']; echo "</p>";
    // Título sección LISTADO
    echo "<p class='titulo-pagina'>Listado de tareas</p>";
    // Listamos las tareas de ese profesor
    getAndPrintTareas($client);


    // Título formulario AÑADIR
    echo "<p class='titulo-pagina'>Añadir tarea</p>";
    // FORMULARIO PARA AÑADIR TAREA
    echo <<<HTML
    <form method="POST" action="">

        <label for="titulo">Título</label>
        <input type="text" name="titulo" id="titulo" required>
        <br>
        <label for="descripcion" class="postTxALabel">Descripción</label>
        <textarea class="postTxA"name="descripcion" rows="4" cols="50" required></textarea>
        <br>
        <label for="fecha">Fecha de entrega</label>
        <input type="date" name="fecha" required><br>
        <br>
        <label for="urgente">Urgente</label>
        <select name="urgente" required>
            <option value="no">no</option>
            <option value="si">si</option>
        </select>
        <br>
        <label for="asignatura">Asignatura</label>
        <input type="text" name="asignatura" id="asignatura" required>
        <br>
        <input type="hidden" name="form_id" value="add_tareas">
        <button type="submit" class="boton-form">Enviar</button>
    </form>
    HTML;


    // Título formulario BORRADO
    echo "<p class='titulo-pagina'>Borrar tareas por asignatura</p>";
    // FORMULARIO PARA BORRAR TODAS LAS TAREAS DE UNA MATERIA
    echo <<<HTML
    <form method="POST" action="">
        <label for="asignatura">Asignatura</label>
        <input type="text" name="asignatura" id="asignatura" required>
        <input type="hidden" name="form_id" value="del_tareas">
        <button type="submit" class="boton-form">Borrar</button>
    </form>
    HTML;

} else {
    // Título general API
    echo "<p class='titulo-pagina'>Gestión de Tareas</p>";
    // Mostramos un formulario para introducir el profesor
    echo <<<HTML
    <form method="POST" action="">
        <label for="profesor">Profesor</label>
        <input type="text" name="profesor" id="profesor">
        <button type="submit" class="boton-form">Enviar</button>
    </form>
    HTML;
}

// Función para imprimir los recuadros de tareas siguiendo la línea de tareas anteriores
function imprimirTareas($tareas){
    foreach ($tareas as $tarea) {
    //echo "<p>Título: {$tarea['titulo']}, Descripción: {$tarea['descripcion']}, Fecha de entrega: {$tarea['fecha_entrega']}, Profesor: {$tarea['profesor']}, Imagen: {$tarea['imagen']}</p>, Urgente: {$tarea['urgente']}, Asignatura: {$tarea['asignatura']}";

    $nombre_tarea = $tarea['titulo'];
    $descripcion = $tarea['descripcion'];
    $fecha_max = $tarea['fecha_entrega'];
    $profesor = $tarea['profesor'];
    $archivo_entrega = $tarea['imagen'];
    $urgente = $tarea['urgente'];
    $asignatura = $tarea['asignatura'];

    echo "
    <div class='lista-tareas'>
    <div class='cabecera-tareas'>
        <p>{$asignatura}</p>
    ";
    if ( $urgente == "si") {
        echo "
        <div class='tarea-urgente'>
            <p class='signo-urgente'>&#9888;</p>
            <p class='texto-urgente'>urgente</p>
        </div>
        ";
        }
        echo <<<HTML
        </div>
        <div class="contenido-tareas">
            <div class="lineas-tareas">
                <p class="descriptor-tareas">Fecha de entrega</p> <p class="info-tareas">$fecha_max</p>
            </div>
            <div class="lineas-tareas">
                <p class="descriptor-tareas">Título</p> <p class="info-tareas">$nombre_tarea</p>
            </div>
            <div class="lineas-tareas">
                <p class="descriptor-tareas">Descripción</p> <p class="info-tareas">$descripcion</p>
            </div>
            <div class="lineas-tareas">
                <p class="descriptor-tareas">Profesor</p> <p class="info-tareas">$profesor</p>
            </div>
            <div class="lineas-tareas">
                <p class="descriptor-tareas">Documentos</p> <a href="../modelo/assets/img/$archivo_entrega" class="enlace-tareas">$archivo_entrega</a>
            </div>
        </div>
    </div>
    HTML;

    }
}

// Función que facilita imprimir tareas al iniciar, al borrar o al añadir
function getAndPrintTareas ($client) {
    $tareas = $client->getTareas($_SESSION['profesor']);
    // Si el resultado no está vacío
    if (!empty($tareas)) {
        // Imprimimos con una función que tenemos al final del archivo
        imprimirTareas($tareas);
    } else {
        echo "<p>No se han encontrado tareas asociadas a este profesor</p>";
    }
}

?>
