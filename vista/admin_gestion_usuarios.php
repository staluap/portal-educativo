<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */


inicioHTML("eduFlow ERP: Gestión Usuarios");
echo "<body>";
cabeceraInicio();

// Antes de cargar los usuarios, comprobamos si se está enviado un formulario de elminar usuario
// Así se elemina al usuario antes de cargar los usuario que permanecen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'eliminar') {
    $id_usuario = $_POST['id_usuario'];
    UsuarioControlador::eliminarUsuario($id_usuario);
}

// También comprobamos si se ha recibido algún formulario de actualización y se realiza si es así
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'actualizar') {
    $id_usuario = $_POST['id_usuario'];
    $contrasena_hash = $_POST['contrasena_hash'];
    $nombre_completo = $_POST['nombre_completo'];
    $perfil = $_POST['perfil'];
    UsuarioControlador::actualizarUsuario($id_usuario, $contrasena_hash, $nombre_completo, $perfil);
}

// Almacenamos en una variable el array de usuarios que existen el la base de datos a través de la función listar usuarios que hemos creado en las funciones controlador bajo la clase ControlUsuarios
$usuarios = UsuarioControlador::listarUsuarios();
// Usamos la función vista que convierte un array de usuarios en una tabla
// Almacenamos en una variable la table para facilitar la inserción en el HTML
$tablaUsuarios = tablaUsuarios($usuarios);

// Hemos insertado el HTML en el PHP para facilitar la inserción de variables dentro del HTML
echo <<<HTML
        <main>
            <p class="titulo-pagina">Gestión de Usuarios</p>
            <div class="boton-dashboard">
                <!-- Este enlace deberá llevarnos al listado de usuarios (controla un cambio de vista) -->
                <!-- Para ello cambiamos el valor de la vista en el index 'admin_gestion_usuarios', para que en el switch del index nos redirija a admin_alta_usuario.php -->
                <a href="./index.php?vista=admin_alta_usuario" class="opcion-dashboard">
                Añadir usuario
                </a>
            </div>
            <div class="boton-dashboard">
                <a href="./index.php?vista=admin_dashboard" class="opcion-dashboard">
                Volver al dashboard
                </a>
            </div>
            <div class="lista-users">
                $tablaUsuarios
            </div>
        </main>
    </body>
</html>
HTML;
?>