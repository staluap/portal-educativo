<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Comprobamos si se ha enviado algún formulario de comentario con las variables que necesitamos
if (isset($_POST['comentario']) && isset($_POST['id_tarea'])) {
    // Volcamos en variables
    $id_tarea = $_POST['id_tarea'];
    $mensaje_profesor = $_POST['comentario'];
    // Actualizamos la tarea con el mensaje del profesor
    TareasControlador::comentarTarea($id_tarea, $mensaje_profesor);
}

// Creamos el inicio de un HTML con el titulo para esta página
inicioHTML("eduFlow: Panel");
// Creamos el body
echo "<body>";
// Llamamos a la cabecera
cabeceraInicio();
// Creamos el main
echo "<main>";
// Bienvenida
bienvenidaDashboard();
// Título
tituloPagina("Panel del profesorado");
// Botones o tabs
echo<<<HTML
<div class='botones-profe'>
    <input type='button' value='Calendario' class='boton-profe' id='calendario-profe'>
    <input type='button' value='Gestión de tareas' class='boton-profe' id='tareas-profe'>
</div>
HTML;

// Cargamos las clases en una variable
$clases = ControlAgenda::arrayClasesXML('modelo/assets/eduFlow.xml');
// Función que imprimen las tablas de clases con contenido del modelo a través del controlador
clasesVista($clases);

// Variable que recoge el nombre completo del usuario de tipo profesor con la sesión iniciada, leyéndolo de la tabla usuarios
$nombre_profesor = UsuarioControlador::nombreCompleto($_SESSION['usuario']);
// Variable que recoge en un array todas las tareas asociadas al usuario de tipo profesor al que pertenece la sesión actual
$tareas = TareasControlador::listarTareasProfesor($nombre_profesor);
// Llamamos a la función vista que genera un recuadro por cada tarea que haya en el array asociada al profesor en cuestión
tareasVistaProfesor($tareas);


echo "</main>";
echo "</body>";
echo "</html>";
?>