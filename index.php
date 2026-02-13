<?php
/*
ACTIVIDAD NF2
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas
*/

// Requerimos las funciones
include_once 'vista/funciones_vista.php';
include_once 'controlador/funciones_controlador.php';

// Usamos la función inicializa la sesión, usamos un if que evite un NOTICE en XAMPP
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprobamos si el parámetro público 'vista' NO está definido
if (!isset($_GET['vista'])) {
    // Si NO está definido, comprobamos si el parámetro público de sesión 'usuario' está definido
    if (isset($_SESSION['perfil'])) {
        // Si 'perfil' está definido, comprobamos el tipo de usuario y lo enviamos a su correspondiente dashboard
        if ($_SESSION['perfil']=="admin") {
            header('Location: ./index.php?vista=admin_dashboard');
        }
        if ($_SESSION['perfil']=="estudiante") {
            header('Location: ./index.php?vista=alumno_dashboard');
        }
        if ($_SESSION['perfil']=="profesor") {
            header('Location: ./index.php?vista=profe_dashboard');
        }
    } else {
        // Si 'perfil' NO está definido, agrega el valor inicio a vista para redirigir al formulario de inicio si no se está logueado
        header('Location: ./index.php?vista=inicio');
    }
} else {
    // Si el parámetro 'vista' NO está definido pero el usuario sí
    if (isset($_SESSION['usuario'])) {
        // Llamamos a la función de cierre Programado
        cierreProgramado();
    }
}

// Establecemos una variable para recoger los mensajes de error si los hubiera, que será según el valor de error en la ruta si existe. Se tratará como variable local en las vistas
$mensaje = enruteError();
// Llamamos a la función que enruta según el valor de vista
enrute();

?>
