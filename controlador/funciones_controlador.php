<?php
/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Para la NF3 hemos desarrollado las clases UsuarioControlador y TareasControlador para la gestión entre lecturas e inserciones de base de datos a vista y viceversa

// Introducimos el modelo para poder usar la clase que maneja la base de datos SQL
require_once (__DIR__.'/../modelo/eduFlow_modelo.php');

// Clase donde introducimos los elementos del control de agenda que teníamos en un archivo a parte
class ControlAgenda {

    public static function arrayClasesXML($ruta){
        // Instanciamos el modelo
        $modelo = new eduFlowModelo($ruta);
        // En eduFlow sólo hay una etiqueta clases y otra tarea en el array
        // Es decir, sólo tiene una posición, la primera que es igual a cero
        // Como no hace falta recorrer los array llamamos a esa posición
        $clases = $modelo->cargarClases()[0];
        return $clases;
    }

    public static function arrayTareasXML($ruta){
        // Instanciamos el modelo
        $modelo = new eduFlowModelo($ruta);
        $tareas = $modelo->cargarTareas()[0];
        return $tareas;
    }
}

// Clase estática para realizar CRUD de la base de datos en la tabla de usuarios
class UsuarioControlador {

    // Método para listar TODOS los usuarios
    public static function listarUsuarios() {
        // Consultamos todos los registros de la tabla usuarios
        $consulta ="SELECT * FROM usuarios";
        // Introducimos en una variable la información obtenida de la lectura de la BD
        $usuarios = BaseDatos::consultaLectura($consulta);
        // Devolvemos los registros si existen, en forma de array
        return $usuarios ? $usuarios : null;
    }

    // Método para buscar un usuario en base a su nombre de usuario
    public static function existeUsuario ($nombre_usuario) {
        // Seleccionamos todos los registros con ese nombre de usuario. Al ser un campo único debe existir 1 o 0.
        $consulta ="SELECT * FROM usuarios WHERE nombre_usuario = ?";
        // Se introduce la lectura de la consulta en una variable
        $resultado = BaseDatos::consultaLectura ($consulta, $nombre_usuario);
        // Si existe resultado y no está vacía se devuelve la primera posición (y única) del array en el que se guarda
        return $resultado ? $resultado[0] : null;
    }

    // Método para obtener directamente el nombre completo de un usuario en base a un usuario
    public static function nombreCompleto ($nombre_usuario) {
        $usuario = UsuarioControlador::existeUsuario($nombre_usuario);
        // Si existe el usuario devolvemos el valor del campo nombre
        return $usuario ? $usuario['nombre_completo'] : null;
    }

    // Método para comprobar las credenciales de un login
    public static function loginUsuario($nombre_usuario, $contrasena_hash) {
        // Comprobamos que exista un usuario y que su contraseña sea exactamente la introducida
        $consulta ="SELECT * FROM usuarios WHERE nombre_usuario = ? AND contrasena_hash = ?";
        // Se introduce el resultado de la consulta
        // Integramos el 'hasheado' de la variable contraseña para que la comparación de lectura funcione correctamente
        $resultado = BaseDatos::consultaLectura ($consulta, $nombre_usuario, hash('sha256', $contrasena_hash));
        // Si existe un registro coincidente y no está vacía devolvemos la primera posción (y única) del array que se genera
        return $resultado ? $resultado[0] : null;
    }

    // Método de inserción para crear un nuevo registro en la tabla usuario
    public static function crearUsuario($nombre_usuario, $contrasena_hash, $nombre_completo, $perfil) {
        // Insertaremos todos los datos de la tabla menos aquellos como el nº de usuario y fecha que son automáticos
        $consulta ="INSERT INTO usuarios (nombre_usuario, contrasena_hash, nombre_completo, perfil) VALUES (?, ?, ?, ?)";
        // Usamos el método de inserción en vez de el de lectura
        // Integramos el 'hasheado' de la variable contraseña para que se escriba en la base de datos con esta codificación
        return BaseDatos::consultaInsercion($consulta, $nombre_usuario, hash('sha256', $contrasena_hash), $nombre_completo, $perfil);
    }

    // Metodo de actualización de datos, también de escritura en la BD
    public static function actualizarUsuario($id_usuario, $contrasena_hash, $nombre_completo, $perfil) {
        // En base al id de usuario (que es único) localizamos al usuario y modificamos algunos parámetros (otros previamente establecidos quedan igual)
        $consulta ="UPDATE usuarios SET contrasena_hash = ?, nombre_completo = ?, perfil = ? WHERE id_usuario = ?";
        // Integramos el 'hasheado' de la variable contraseña para que se escriba en la base de datos con esta codificación
        return BaseDatos::consultaInsercion($consulta, hash('sha256', $contrasena_hash), $nombre_completo, $perfil, $id_usuario);
    }

    // Método de eliminar registros en la tabla usuario en base al id
    public static function eliminarUsuario ($id_usuario){
        $consulta = "DELETE FROM usuarios WHERE id_usuario = ?";
        return BaseDatos::consultaInsercion($consulta, $id_usuario);
    }
}

// Clase estática para realizar CRUD de la base de datos en la tabla de tareas
class TareasControlador {
    // Método de inserción para crear un nuevo registro en la tabla tareas a través de la entrega del alumno
    public static function entregarTarea($nombre_tarea, $asignatura, $nombre_profesor, $nombre_alumno, $archivo_entrega) {
        // Insertaremos todos los datos de la tabla menos aquellos como el nº de usuario y fecha que son automáticos
        $consulta ="INSERT INTO tareas (nombre_tarea, asignatura, nombre_profesor, nombre_alumno, archivo_entrega) VALUES (?, ?, ?, ?, ?)";
        // Usamos el método de inserción en vez de el de lectura
        return BaseDatos::consultaInsercion($consulta, $nombre_tarea, $asignatura, $nombre_profesor, $nombre_alumno, $archivo_entrega);
    }

    // Método para actualizar una entrega
    public static function actualizarTarea($id_tarea, $archivo_entrega) {
        // Insertaremos todos los datos de la tabla menos aquellos como el nº de usuario y fecha que son automáticos
        $consulta ="UPDATE tareas SET archivo_entrega = ?, fecha_entrega = NOW() WHERE id_tarea = ?";
        return BaseDatos::consultaInsercion($consulta, $archivo_entrega, $id_tarea);
    }

    // Método para localizar una tarea existente sin conocer el id. Para ello pondremos bastantes variables para que sea inequívoco
    public static function localizarTarea($nombre_tarea, $asignatura, $nombre_profesor, $nombre_alumno) {
        $consulta = "SELECT * FROM tareas WHERE nombre_tarea = ? AND asignatura = ? AND nombre_profesor = ? AND nombre_alumno = ?";
        $resultado = BaseDatos::consultaLectura ($consulta, $nombre_tarea, $asignatura, $nombre_profesor, $nombre_alumno);
        return $resultado ? $resultado[0] : null;
    }

    // Método para buscar todas las tareas entregadas asociadas a un profesor
    public static function listarTareasProfesor ($nombre_profesor) {
        // Seleccionamos todos los registros con ese nombre de profesor
        $consulta ="SELECT * FROM tareas WHERE nombre_profesor = ?";
        // Se introduce la lectura de la consulta en una variable
        $tareas = BaseDatos::consultaLectura ($consulta, $nombre_profesor);
        // Si existe resultado y no está vacía se devuelve la variable con el array de tareas de ese profesor
        return $tareas ? $tareas : null;
    }

    // Método para escribir el mensaje del profesor en una tarea entregada. Usaremos el id de la tarea específica
    public static function comentarTarea ($id_tarea, $mensaje_profesor) {
        // Actualizamos la tabla tareas, sólo el campo del mensaje del profesor, donde el id corresponda con la tarea en la que escribimos
        $consulta ="UPDATE tareas SET mensaje_profesor = ? WHERE id_tarea = ?";
        return BaseDatos::consultaInsercion($consulta, $mensaje_profesor, $id_tarea);
    }
}

// FUNCIONES DESARROLLADAS ANTES DE NF3_________________________________________________________________________________

// Vamos a crear una función que encapsule el cierre analógico
function cierreAnalogico()
{
    // Función de iniciar sesión
    session_start();
    // Eliminamos datos de la sesión en el lado cliente
    session_unset();
    // Eliminamos datos de la sesión en el lado servidor
    session_destroy();
    // Redirigir al usuario al index
    header("Location: ../index.php?vista=inicio");
    exit();
}

// Vamos a crear un cierre programado a los 30 minutos
// Podríamos indicar que no se cierre si está activa la sesión y que se refresque el tiempo como en los ejercicios de clase
// Sin embargo, no se exigía en el enunciado, por lo que independientemente de la actividad del usuario haremos que cierre a los 30 minutos
function cierreProgramado()
{
    // Usamos la función inicializa la sesión, usamos un if que evite un NOTICE en XAMPP
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    //Control de tiempo a la sesion
    // Establece el tiempo máximo de vida de la sesión (30 minutos en segundos)
    $tiempo_maximo = 30 * 60; // 30 minutos

    // Comprobamos si hay sesión con tiempo establecido y si ha superado el máximo
    if (isset($_SESSION['tiempo'])) {
        if ((time() - $_SESSION['tiempo']) > $tiempo_maximo) {
            session_unset();
            session_destroy();
            header("Location: index.php?vista=inicio");
            exit();
        }
    }
}

//Vamos a crear una función para la comprobación de variables del formulario de alta, porque se repite en varias ocasiones
function comprobacionVariables()
{
    if (isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['user']) && isset($_POST['perfil']) && isset($_POST['password'])) {

        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $user = $_POST['user'];
        $perfil = $_POST['perfil'];
        $password = $_POST['password'];
        // Añadimos la variable contenido para poder usar un booleano que devuelva true o false según si está el contenido requerido
        $contenido = true;

        return [$nombre, $apellidos, $user, $perfil, $password, $contenido];
    } else {
        $contenido = false;
        return $contenido;
    }
}

// Función con un switch que enruta a una vista u otra
function enrute() {
    // Si la variable pública 'vista' ya tiene un valor asociado, usaremos un condicional de tipo switch
    // De esta forma todas las páginas se visualizarán desde el index
    if (isset($_GET['vista'])) {
        switch ($_GET['vista']) {
            // VISTAS ADMINISTRADOR
            // Si el valor de 'vista' es 'admin_dashboard'
            case 'admin_dashboard':
                // Introducimos la vista del dashboard del administrador
                include_once "vista/admin_dashboard.php";
                break;
            // Si el valor de 'vista' es 'admin'_gestion_usuarios
            case 'admin_gestion_usuarios':
                // Introducimos la vista del dashboard del administrador
                include_once "vista/admin_gestion_usuarios.php";
                break;
            // Si el valor de 'vista' es 'admin_actualizar_usuario'
            case 'admin_actualizar_usuario':
                // Introducimos la vista del formulario de alta de usuario
                include_once "vista/admin_actualizar_usuario.php";
                break;
            // Si el valor de 'vista' es 'admin_alta_usuario'
            case 'admin_alta_usuario':
                // Introducimos la vista del formulario de alta de usuario
                include_once "vista/admin_alta_usuario.php";
                break;
            // Si el valor de 'vista' es 'admin_confirmacion_alta'
            case 'admin_confirmacion_alta':
                // Introducimos la vista del formulario de alta de usuario
                include_once "vista/admin_confirmacion_alta.php";
                break;
            case 'admin_alta_resultado':
                // Introducimos la vista del formulario de alta de usuario
                include_once "vista/admin_alta_resultado.php";
                break;
            // VISTAS ESTUDIANTE
            case 'alumno_dashboard':
                // Introducimos la vista del dashboard del alumno a través de la clase controlador
                include_once "vista/alumno_dashboard.php";
                break;
            // VISTAS PROFESOR
            case 'profe_dashboard':
                // Introducimos la vista del dashboard del profesor
                include_once "vista/profe_dashboard.php";
                break;
            // VISTAS GENERALES
            // Si el valor de 'vista' es 'inicio'
            case 'inicio':
                // Redirigimos al formulario de inicio
                include_once "vista/vista_inicio.php";
            // Como valor por defecto
            default:
                // Redirigimos al formulario de inicio
                include_once "vista/vista_inicio.php";
                break;
        }
    }
}

// Esta función enruta a versiones de error de una vista, haciendo visible en la vista el mensaje de error
function enruteError() {
    // Si la variable pública 'vista' ya tiene un valor asociado, usaremos un condicional de tipo switch
    // De esta forma todas las páginas se visualizarán desde el index
    $mensaje = null;
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            // VISTAS ADMINISTRADOR
            // Si el valor de 'vista' es 'admin'
            case 'login':
                // Mensaje de error para inicio de sesión con credenciales incorrectas
                $mensaje = "Credenciales de usuario y contraseña incorrectos.";
                break;
            case 'userexists':
                // Mensaje de error al intentar dar de alta un usuario que ya existe
                $mensaje = "Este nombre de usuario ya existe en la base de datos.";
                break;
            default:
                break;
        }
    }
    return $mensaje;
}

?>
