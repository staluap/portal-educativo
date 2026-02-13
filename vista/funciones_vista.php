<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// NOTA: Comprimiremos las funciones anteriores, dejaremos expandidas las nuevas o modificadas para la tarea

// Función que genera el head del HTML
function inicioHTML($titulo)
{
  echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <title>{$titulo}</title>
            <meta charset='UTF-8' />
            <link rel='icon' type='image/x-icon' href='vista/IMG/favicon.png'/>
            <link rel='stylesheet' href='vista/estilos.css' />
            <script src='vista/eduflow.js' defer></script>
        </head>
    ";
}

// Función que genera la cabecera de inicio (header) según si se está o no logueado
function cabeceraInicio()
{
  if (isset($_SESSION['usuario'])) {
    echo "
            <header>
                <img src='vista/IMG/logo.png' alt='eduFlow' class='logo' />

              <div class='cabecera-logueada'>
                <p>Bienvenid@ "; echo $_SESSION['usuario']; echo " | Sesion iniciada a las "; echo date("G:i", $_SESSION['tiempo']); echo "</p>
                <input type='button' value='Cerrar sesión' class='boton-cierre' onclick=\"window.location.href='controlador/cierre_sesion.php'\">
              </div>
            </header>
            ";
  } else {
    echo "
            <header>
                <img src='vista/IMG/logo.png' alt='eduFlow' class='logo' />
            </header>
        ";
  }
}

// Función que introduce una bienvenida genérica para usar en los dashboards
function bienvenidaDashboard() {
  echo "<p class='titulo-bienvenida'>Bienvenid@ a eduFlow, "; echo $_SESSION['usuario']; echo "</p>";
}

// Función que genera el título de página en el main
function tituloPagina($titulo) {
  echo "<p class='titulo-pagina'>{$titulo}</p>";
}

// Función genera los recuadros de clases para ALUMNOS y PROFESORES
function clasesVista($clases) {
  if(isset($clases)) {
    foreach ($clases->clase as $clase):
    echo "
    <div class='lista-clases'>
    <div class='cabecera-clases'>
        <p>"; echo $clase->dia; echo "</p>
    </div>
    <div class='contenido-clases'>
        <div class='lineas-clases'>
            <p class='descriptor-clases'>Asignatura</p> <p class='info-clases'>"; echo $clase->asignatura; echo "</p>
        </div>
        <div class='lineas-clases'>
            <p class='descriptor-clases'>Profesor/a</p> <p class='info-clases'>"; echo $clase->profesor; echo "</p>
        </div>
        <div class='lineas-clases'>
            <p class='descriptor-clases'>Hora</p> <p class='info-clases'>"; echo $clase->hora; echo "</p>
        </div>
        <div class='lineas-clases'>
            <p class='descriptor-clases'>Aula</p> <p class='info-clases'>"; echo $clase->aula; echo "</p>
        </div>
    </div>
  </div>
  ";
  endforeach;
  } else {
    echo "<p>Ha ocurrido un error en la carga de clases</p>";
  }
}

// Función que genera los recuadros de tareas para ALUMNOS
// NF3: Añadimos que use la función controlador de entregar tarea al pulsar el botón
function tareasVistaAlumno($tareas) {
  if(isset($tareas)) {
    // Leemos cada tarea almacenada en el array
    foreach ($tareas->tarea as $tarea):
      // Creamos variables más sencillas con los items
      $fecha_max = $tarea->fecha_entrega;
      $urgente = $tarea->urgente;
      $descripcion = $tarea->descripcion;
      // Variables importantes para escritura en BD
      $nombre_tarea = $tarea->titulo;
      $asignatura = $tarea->asignatura;
      $nombre_profesor = $tarea->profesor;
      $nombre_alumno = UsuarioControlador::nombreCompleto($_SESSION['usuario']);
      $archivo_entrega = $tarea->imagen;
    ?>
    <div class="lista-tareas">
    <div class="cabecera-tareas">
        <p><?php echo $asignatura?></p>
        <?php
        if ( $urgente == "si") {
        echo "
        <div class='tarea-urgente'>
            <p class='signo-urgente'>&#9888;</p>
            <p class='texto-urgente'>urgente</p>
        </div>
        ";
        }
    echo <<<HTML
    </div>
    <div class="contenido-tareas">
        <div class="lineas-tareas">
            <p class="descriptor-tareas">Fecha de entrega</p> <p class="info-tareas">$fecha_max</p>
        </div>
        <div class="lineas-tareas">
            <p class="descriptor-tareas">Título</p> <p class="info-tareas">$nombre_tarea</p>
        </div>
        <div class="lineas-tareas">
            <p class="descriptor-tareas">Descripción</p> <p class="info-tareas">$descripcion</p>
        </div>
        <div class="lineas-tareas">
            <p class="descriptor-tareas">Documentos</p> <a href="modelo/assets/img/$archivo_entrega" class="enlace-tareas">$archivo_entrega</a>
        </div><br><br><br>
        <div class="lineas-tareas">
          <!-- Generamos un formulario para poder usarlo para pasar de cliente a PHP y accionar una función PHP sin perder los datos XML -->
          <form class="hidden-form" method="POST">
            <input type="hidden" name="nombre_tarea" value="$nombre_tarea">
            <input type="hidden" name="asignatura" value="$asignatura">
            <input type="hidden" name="nombre_profesor" value="$nombre_profesor">
            <input type="hidden" name="nombre_alumno" value="$nombre_alumno">
            <input type="hidden" name="archivo_entrega" value="$archivo_entrega">
            <input type='submit' name='entregar' value='Entregar' class='boton-entrega' onclick="window.location.reload()">
          </form>
        </div>
    </div>
  </div>
  HTML;
    endforeach;
  } else {
    echo '<p>Ha ocurrido un error en la carga de tareas</p>';
  }
}

// NF3: Función que genera los recuadros de tareas para PROFESORES
// La variable de entrada sale de el método TareasControlador::listarTareasProfesor
function tareasVistaProfesor($tareas) {
  if(isset($tareas)) {
    // Leemos cada tarea almacenada desde la BD en el array
    foreach ($tareas as $tarea) {
      // Volcamos los datos de los sub-arrays usando clave-valor por cada columna de la tabla
      $id_tarea = $tarea['id_tarea'];
      $nombre_tarea = $tarea['nombre_tarea'];
      $asignatura = $tarea['asignatura'];
      $nombre_alumno = $tarea['nombre_alumno'];
      $fecha_entrega = $tarea['fecha_entrega'];
      $mensaje_profesor = $tarea['mensaje_profesor'];
      $archivo_entrega = $tarea['archivo_entrega'];

      echo <<<HTML
      <div class="lista-tareas-profe">
        <div class="cabecera-tareas">
            <p>$asignatura</p>
        </div>
        <div class="contenido-tareas">
          <div class="lineas-tareas">
              <p class="descriptor-tareas">Título</p> <p class="info-tareas">$nombre_tarea</p>
          </div>
          <div class="lineas-tareas">
              <p class="descriptor-tareas">Alumno</p> <p class="info-tareas">$nombre_alumno</p>
          </div>
          <div class="lineas-tareas">
              <p class="descriptor-tareas">Fecha de envío</p> <p class="info-tareas">$fecha_entrega</p>
          </div>
          <div class="lineas-tareas">
              <p class="descriptor-tareas">Documentos</p> <a href="modelo/assets/img/$archivo_entrega" class="enlace-tareas">$archivo_entrega</a>
          </div>
          <div class="lineas-tareas">
            <p class="descriptor-tareas">Comentario</p>
            <!-- Generamos un formulario para recoger el comentario -->
            <form class="textarea-form" method="POST">
              <textarea class="comentProfe" name="comentario">$mensaje_profesor</textarea>
              <input type="hidden" name="id_tarea" value="$id_tarea">
              <input type='submit' name='comentar' value='Comentar' class='boton-comentario' onclick="window.location.reload()">
            </form>
          </div>
        </div>
      </div>
    HTML;
    }
  }
}


// Función que genera un listado en forma de tabla para los usuarios en el dashboard de admin
// Pasamos como parámetro una variable $usuarios que debe contener el listado de usuarios de la BD
function tablaUsuarios($usuarios) {
  // Variable que concatenará el HTML para devolver la tabla en una sola variable
  $tablaUsuarios = "";
  // Títulos de columna
  $tablaUsuarios .= <<<HTML
    <table>
      <tr>
        <th>id_usuario</th>
        <th>nombre_usuario</th>
        <th>contrasena_hash</th>
        <th>nombre_completo</th>
        <th>perfil</th>
        <th>fecha_alta</th>
        <th>EDITAR</th>
        <th>BORRAR</th>
      </tr>
  HTML;
  // Se comprueba que el parámetro $usuarios introducido no está vacío
  if(isset($usuarios)) {
    // Leemos cada posición del array y usando clave-valor guardamos en una variable cada campo de la tabla
    foreach ($usuarios as $usuario) {
      $id_usuario = $usuario['id_usuario'];
      $nombre_usuario = $usuario['nombre_usuario'];
      // TODO Revisar si necesita encriptación o no
      $contrasena_hash = $usuario['contrasena_hash'];
      $nombre_completo = $usuario['nombre_completo'];
      $perfil = $usuario['perfil'];
      $fecha_alta = $usuario['fecha_alta'];
      // Concatenamos una fila de la tabla con los datos anteriores, cada uno en su columna correspondiente
      $tablaUsuarios .= <<<HTML
        <tr>
          <td>$id_usuario</td>
          <td>$nombre_usuario</td>
          <td>$contrasena_hash</td>
          <td>$nombre_completo</td>
          <td>$perfil</td>
          <td>$fecha_alta</td>
          <!-- Aquí generamos por cada usuario/fila un botón para actualizar ese usuario-->
          <td>
             <!-- Creamos un formulario invisible que recoja los datos del usuario en la fila, para tenerlos guardados en el formulario de actualización -->
            <form class="hidden-form" method="POST">
              <input type="hidden" name="id_usuario" value="$id_usuario">
              <input type="hidden" name="nombre_usuario" value="$nombre_usuario">
              <input type="hidden" name="contrasena_hash" value="$contrasena_hash">
              <input type="hidden" name="nombre_completo" value="$nombre_completo">
              <input type="hidden" name="perfil" value="$perfil">
              <input type="hidden" name="fecha_alta" value="$fecha_alta">
              <!-- Al hacer click en el botón se envían los datos del formulario, se va a una página de actualización-->
              <button type="submit" class="iconoBD" formaction="index.php?vista=admin_actualizar_usuario">
                <p>&#128393;</p>
              </button>
            </form>
          </td>
          <!-- Aquí generamos por cada usuario/fila un botón para eliminarlo -->
          <td>
            <!-- Creamos un formulario que refresque la página enviando datos que permitan realizar la acción de eliminar al usuario en esa fila -->
            <form class="hidden-form" method="POST">
            <input type="hidden" name="accion" value="eliminar">  
            <input type="hidden" name="id_usuario" value="$id_usuario">
              <!-- Al hacer click en el botón se envían los datos del formulario, se recarga la página y se procesan -->
              <button type="submit" class="iconoBD" onclick="window.location.reload();">
                <p>&#128465;</p>
              </button>
            </form>
          </td>
        </tr>
      HTML;
    }
  }
  $tablaUsuarios .= "</table>";
  return $tablaUsuarios;
}