<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// HEMOS AÑADIDO EL MENSAJE DE ERROR Y VUELTA AUTOMÁTICA ATRÁS SI EL USUARIO YA EXISTE EN LA BASE DE DATOS


inicioHTML("eduFlow ERP: Alta usuario");
echo "<body>";
cabeceraInicio();

?>
    <main>
        <?php
        // Usamos la función que comprueba y vuelca el contenido de las variables del formulario en las variables homónimas listadas
        list($nombre, $apellidos, $user, $perfil, $password, $contenido) = comprobacionVariables();
        // Utilizamos la variable $contenido devuelta por la función para condicionar el flujo del programa según si está o no el contenido requerido
        if ($contenido) {
            // Creamos una variable que almacene información si existe el usuario indroducido o null si no
            $existeUsuario = UsuarioControlador::existeUsuario($user);
            // Si existe volvemos al formulario editable con un mensaje de error
            if ($existeUsuario) {
                ?>
                    <!-- Hacemos un formulario invisible que se envíe automáticamente, para que el formulario anterior siga relleno -->
                    <form id="autoForm" class="hidden-form" method="POST" action="index.php?vista=admin_alta_usuario&error=userexists">
                        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
                        <input type="hidden" name="apellidos" value="<?php echo $apellidos ?>">
                        <input type="hidden" name="user" value="<?php echo $user ?>">
                        <input type="hidden" name="perfil" value="<?php echo $perfil ?>">
                        <!-- TODO Ver si hace falta eliminar contraseñas de algunos formularios por cuestiones de encriptación -->
                        <input type="hidden" name="password" value="<?php echo $password ?>">
                    </form>
                    <script>
                        document.getElementById('autoForm').submit();
                    </script>
                <?php
            // Si no existe mostramos el formulario no editable de confirmación
            } else {
                ?>
                    <p class="titulo-pagina">Confirmar usuario</p>
                    <!-- Este formulario nos redigirá a un mensaje de confirmación de registro final si no se cancela/corige-->
                    <form method="post">
                        <!-- Volcaremos las variables que recogen los datos del formulario en los valores de los input y usaremos readonly para que no sean editables en esta pantalla -->
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre ?>" readonly><br>
                        <label for="apellidos">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos ?>" readonly><br>
                        <label for="user">Usuario</label>
                        <input type="text" id="user" name="user" value="<?php echo $user ?>" readonly><br>
                        <!-- El input perfil pasa a ser sólo texto -->
                        <label for="perfil">Perfil</label>
                        <input type="text" id="perfil" name="perfil" value="<?php echo $perfil ?>" readonly><br>
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" value="<?php echo $password ?>" readonly><br>
                        <input type="submit" value="Confirmar" class="boton-form" formaction="index.php?vista=admin_alta_resultado">
                        <!-- El botón corregir dará un paso atrás en el historial -->
                        <input type="submit" value="Corregir" class="boton-form" formaction="index.php?vista=admin_alta_usuario">
                    </form>
                <?php
            }
        } else {
            // Si algo sale mal se imprimirá un mensaje de error con el mismo estilo y formato que los títulos de sección
            echo <<<HTML
            <p class="titulo-pagina">ERROR: Los datos no han podido ser registrados</p>
            <input type="button" value="Volver a inicio" class="boton-volver" onclick="window.location.href='index.php'">
            HTML;
        }
echo "</main>";
echo "</body>";
echo "</html>";