<?php
/* ACTIVIDAD NF2
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// MODO VISTA: Las funciones creadas tienen que ver con la estructura HTML y el contenido visible

// Esta es una variable global de la funcion controlador enruteError
global $mensaje;


inicioHTML("eduFlow: Inicio");
echo "<body>";
cabeceraInicio();

// Creamos una variable que recoja un mensaje de error a añadir si existe dicho mensaje
$msn_element = $mensaje ? "<p class='msn-err'>$mensaje</p>" : "";

echo <<<HTML
        <main>
            <p class="titulo-pagina">Acceso de usuarios</p>
            <form action="controlador/control_inicio.php" method="POST">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required /><br>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required maxlength="6"/><br>
                $msn_element
                <input type="submit" value="Iniciar sesión" class="boton-form"/>
            </form>
            <br>
        </main>
    </body>
</html>
HTML;
?>
