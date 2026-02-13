<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Se ha creado este formulario para la actualización


inicioHTML("eduFlow ERP: Actualizar Usuario");
echo "<body>";
cabeceraInicio();

// Comprobamos el contenido de las variables del formulario y volcamos en variables
// TODO averiguar si la función comprobacionVariables en controlador puede modificarse para adaptarse a múltiples formularios
if (isset($_POST['id_usuario']) && isset($_POST['nombre_usuario']) && isset($_POST['contrasena_hash']) && isset($_POST['perfil']) && isset($_POST['nombre_completo']) && isset($_POST['perfil']) && isset($_POST['fecha_alta'])) {

        $id_usuario = $_POST['id_usuario'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena_hash = $_POST['contrasena_hash'];
        $nombre_completo = $_POST['nombre_completo'];
        $perfil = $_POST['perfil'];
        $fecha_alta = $_POST['fecha_alta'];

        ?>
        <main>
            <p class="titulo-pagina">Actualizar usuario</p>
            <form action="index.php?vista=admin_gestion_usuarios" method="POST">
                <input type="hidden" name="accion" value="actualizar">
                <label for="id_usuario">Id Usuario</label>
                <input type="text" id="id_usuario" name="id_usuario" value="<?php echo $id_usuario ?>" readonly><br>
                <label for="nombre_completo">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo $nombre_completo ?>" required><br>
                <label for="nombre_usuario">Nombre Usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo $nombre_usuario ?>" readonly><br>
                <label for="perfil">Perfil</label>
                <select id="perfil" name="perfil">
                <!-- Se han añadido condicionales ternarios a las opciones de los selcet en formularios que trabajan con datos previos, para evitar cambios a la primera opción por defecto que pasen inadvertidos -->
                <option value="admin" <?= ($perfil == "admin") ? "selected" : "" ?>>Administrador</option>
                <option value="estudiante" <?= ($perfil == "estudiante") ? "selected" : "" ?>>Estudiante</option>
                <option value="profesor" <?= ($perfil == "profesor") ? "selected" : "" ?>>Profesor</option></select><br>
                <label for="contrasena_hash">Contraseña</label>
                <input type="password" id="contrasena_hash" name="contrasena_hash" value="" required maxlength="6"><br>
                <input type="submit" value="Aceptar" class="boton-form">
                <input type="button" value="Cancelar" class="boton-form" onclick="window.location.href='index.php?vista=admin_gestion_usuarios'">
            </form><br>
        </main>
        <?php

} else {
}
echo "</body>";
echo "</html>";
?>