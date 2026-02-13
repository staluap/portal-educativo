<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Sólo se ha modificado la dirección del botón Gestión de Usuarios a 'admin_gestion_usuarios.php' en vez de a 'admin_alta_usuario.php'


// Creamos el inicio de un HTML con el titulo para esta página
inicioHTML("eduFlow ERP: Dashboard");
// Creamos el body
echo "<body>";
// Llamamos a la cabecera
cabeceraInicio();
// Incluímos el HTML particular de esta vista
echo <<<HTML
        <main>
            <p class="titulo-bienvenida">Bienvenid@ a eduFlow</p>
            <p class="titulo-pagina">ERP eduFlow</p>
            <div class="boton-dashboard">
                <!-- Este enlace deberá llevarnos al listado de usuarios (controla un cambio de vista) -->
                <!-- Para ello cambiamos el valor de la vista en el index 'admin_gestion_usuarios', para que en el switch del index nos redirija a admin_alta_usuario.php -->
                <a href="./index.php?vista=admin_gestion_usuarios" class="opcion-dashboard">
                Gestión de Usuarios
                </a>
            </div>
            <div class="boton-dashboard">
                <a href="" class="opcion-dashboard">
                Gestión de Calendario
                </a>
            </div>
            <div class="boton-dashboard">
                <a href="" class="opcion-dashboard">
                Gestión de Tareas
                </a>
            </div>
        </main>
    </body>
</html>
HTML;
?>
