<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Se ha modificado la lógica del login para hacer uso de la consulta a la base de datos

require_once 'funciones_controlador.php';

// FICHERO CONTROLADOR: Procesa la información del formulario de inicio (login) y redirige según los datos recibidos

    // Usamos la función inicializa la sesión, usamos un if que evite un NOTICE en XAMPP
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Comprobamos que existen datos recogidos por el formulario
    if (isset($_POST['usuario']) && isset($_POST['password'])) {
        // Utilizamos el método login de nuestra clase UsuarioControlador para comprobar si existe un usuario con esa contraseña encriptada
        $usuario = UsuarioControlador::loginUsuario($_POST['usuario'], $_POST['password']);
        // Si la consulta SQL existe en la tabla de usuarios
        if ($usuario) {
            // Definimos un id de sesión
            session_id(md5('eduFlow'));
            // Creamos una variable de perfil que en base a la consulta del login guarde el valor del perfil
            $_SESSION['perfil'] = $usuario['perfil'];
            // Creamos una variable de sesión llamada usuario donde definimos el nombre de usuario
            $_SESSION['usuario'] = $usuario['nombre_usuario'];
            // Creamos una variable de sesión llamada tiempo donde guardamos la hora de inicio de sesión (en segundos)
            $_SESSION['tiempo'] = time();
            header ("Location: ../index.php");
        } else {
            // Si el usuario y/o contraseña no existen en la base de datos continuamos en el formulario de login
            header ("Location: ../index.php?vista=inicio&error=login");
        }
    } else {
        // Si no se ha enviado el formulario, mostramos el formulario
        header ("Location: ../index.php?vista=inicio");
    }
    ?>