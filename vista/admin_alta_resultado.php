<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */


// Creamos el inicio de un HTML con el titulo para esta página
inicioHTML("eduFlow ERP: Alta usuario");
// Creamos el body
echo "<body>";
// Llamamos a la cabecera
cabeceraInicio();

echo "<main>";
        // Usamos la función que comprueba el contenido de las variables del formulario
        list($nombre, $apellidos, $user, $perfil, $password, $contenido) = comprobacionVariables();
        // Utilizamos la variable $contenido devuelta por la función para condicionar el flujo del programa según si está o no el contenido requerido
        if ($contenido) {
            // Concatenamos el nombre completo
            $nombre_completo = $nombre . " " . $apellidos;
            // Introducimos los datos como un usuario nuevo en la tabla de usuarios de la base de datos
            UsuarioControlador::crearUsuario($user, $password, $nombre_completo, $perfil);
            echo "<p class='titulo-pagina'>¡Usuario registrado!</p>";
            echo "
                        <div class='contenedor-confirmacion'>
                            <p class='confirmacion'>El usuario <b>$nombre $apellidos</b> ha sido creado exitosamente</p><br>
                            <p class='confirmacion'>Perfil: $perfil</p><br>";

        }
        ?>
        <input type="button" value="Volver a inicio" class="boton-volver" onclick="window.location.href='index.php'">
    </main>
</body>
</html>