<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Se ha modificado este archivo para ajustar al formulario a las instrucciones cambiando edad por user
// También se ha añadido la posible activación de un mensaje de error


inicioHTML("eduFlow ERP: Alta usuario");
echo "<body>";
cabeceraInicio();

// Esta es una variable global de la funcion controlador enruteError
global $mensaje;
// Creamos una variable que recoja un mensaje de error a añadir si existe dicho mensaje
$msn_element = $mensaje ? "<p class='msn-err'>$mensaje</p><br>" : "";

// Usamos la función que comprueba el contenido de las variables del formulario
list($nombre, $apellidos, $user, $perfil, $password, $contenido) = comprobacionVariables();
// Utilizamos la variable $contenido devuelta por la función para condicionar el flujo del programa según si está o no el contenido requerido
// Si hay contenido se devuelve el formulario con el valor de campo relleno con los valores que tuviesen asignados
if ($contenido) { ?>
  <main>
    <p class="titulo-pagina">Alta usuario</p>
    <form action="index.php?vista=admin_confirmacion_alta" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre ?>" required><br>
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos ?>" required><br>
        <label for="user">Usuario</label>
        <input type="text" id="user" name="user" value="<?php echo $user ?>"><br>
        <label for="perfil">Perfil</label>
        <select id="perfil" name="perfil">
          <option value="admin" <?= ($perfil == "admin") ? "selected" : "" ?>>Administrador</option>
          <option value="estudiante" <?= ($perfil == "estudiante") ? "selected" : "" ?>>Estudiante</option>
          <option value="profesor" <?= ($perfil == "profesor") ? "selected" : "" ?>>Profesor</option></select><br>
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" value="<?php echo $password ?>" required maxlength="6"><br>
        <input type="submit" value="Aceptar" class="boton-form">
        <input type="button" value="Cancelar" class="boton-form" onclick="window.location.href='index.php?vista=admin_gestion_usuarios'">
        <?php echo $msn_element ?>
      </form><br>
  </main>
<?php
// Si no, se devuelve el formulario de alta vacío
} else {
  echo <<<HTML
    <main>
      <p class="titulo-pagina">Alta usuario</p>
      <!-- Añadimos un enlace que modifique el valor de la vista para que desde el switch del index.php nos redirija al formulario no editable de confirmación -->
      <form action="./index.php?vista=admin_confirmacion_alta" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required /><br>
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" required /><br>
        <label for="user">Usuario</label>
        <input type="text" id="user" name="user"/><br>
        <label for="perfil">Perfil</label>
        <select id="perfil" name="perfil">
          <option value="admin">Administrador</option>
          <option value="estudiante">Estudiante</option>
          <option value="profesor">Profesor</option></select><br>
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required maxlength="6"/><br>
        <input type="submit" value="Aceptar" class="boton-form" />
        <!-- Indicamos que al pulsar cancelar se vuelva al index donde estará el dashboard o el formulario de inicio si se ha cerrado sesión -->
        <input type="button" value="Cancelar" class="boton-form" onclick="window.location.href='index.php?vista=admin_gestion_usuarios'"/>
        $msn_element
      </form>
      <br>
    </main>
  HTML;
}
echo "</body>";
echo "</html>";
?>