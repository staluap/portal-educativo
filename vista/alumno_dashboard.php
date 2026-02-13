<?php
/* ACTIVIDAD NF2
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */


// Comprobamos si se ha enviado algún formulario de entrega de tareas con las variables requeridas
if (isset($_POST['nombre_tarea']) && isset($_POST['asignatura']) && isset($_POST['nombre_profesor']) && isset($_POST['nombre_alumno']) && isset($_POST['archivo_entrega'])) {
    // Volcamos en variables
    $nombre_tarea = $_POST['nombre_tarea'];
    $asignatura = $_POST['asignatura'];
    $nombre_profesor = $_POST['nombre_profesor'];
    $nombre_alumno = $_POST['nombre_alumno'];
    $archivo_entrega = $_POST['archivo_entrega'];
    // Comprobamos si la entrega ya se había hecho antes
    $existeEntrega = TareasControlador::localizarTarea($nombre_tarea, $asignatura, $nombre_profesor, $nombre_alumno);
    // Si existe la entrega, no se crea otra, sino que se actualiza
    // Debe actualizar el archivo (ahora mismo no deja subir otro) y debe actualizar la fecha de entrega del alumno
    if ($existeEntrega) {
        // Obtenemos el id, necesario para la función que actualiza
        $id_tarea = $existeEntrega['id_tarea'];
        TareasControlador::actualizarTarea($id_tarea, $archivo_entrega);
    // Si no existe la entrega la creamos
    } else {
        TareasControlador::entregarTarea($nombre_tarea, $asignatura, $nombre_profesor, $nombre_alumno, $archivo_entrega);
    }
}

// Creamos el inicio de un HTML con el titulo para esta página
inicioHTML("eduFlow: Agenda");
// Creamos el body
echo "<body>";
// Llamamos a la cabecera
cabeceraInicio();
// Creamos el main
echo "<main>";
// Bienvenida
bienvenidaDashboard();
// Título
tituloPagina("Agenda del estudiante");

// Cargamos las clases y tareas en una variable
$clases = ControlAgenda::arrayClasesXML('modelo/assets/eduFlow.xml');
$tareas = ControlAgenda::arrayTareasXML('modelo/assets/eduFlow.xml');
// Funciones que imprimen las tablas de clases y tareas con contenido del modelo a través del controlador
clasesVista($clases);
tareasVistaAlumno($tareas);

echo "</main>";
echo "</body>";
echo "</html>";

?>
