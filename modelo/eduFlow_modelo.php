<?php

/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// NF3 ________________________________________________
// CLASE PARA MANEJAR CONEXIONES CON LA BASE DE DATOS
class BaseDatos {
    // Creamos una propiedad estática de conexión que por defecto será null
    private static $conexion = null;
    // Crearemos una método estático de acceso a base de datos
    private static  function conexionBD() {
        // Cargamos el fichero de configuración en una variable
        $config = parse_ini_file(__DIR__."/../config.ini");
        // Si la propiedad de conexión es null
        if(self::$conexion === null){
            // damos a la variable de conexión el valor de un objeto mysqli (que representa la conexión PHP-SQL)
            // Para la construcción del objeto mysqli requerimos los valores del archivo config.ini
            self::$conexion = new mysqli($config['server'], $config['user'], $config['pasw'], $config['bd']);
            // Indicamos que en el caso de que el objeto mysqli de error pare la aplicación (con die) y muestre un mensaje de error
            if(self::$conexion->connect_error){
                die("Error en la conexión: " . self::$conexion->connect_error);
            }
            // Si no hay error terminamos de configurar la conexión
            // Configuramos el código de caracteres a usar
            self::$conexion->set_charset('utf8mb4');
        }
        // Si todo ha ido bien, la función devuelve el valor o valores de la conexión
        return self::$conexion;
    }
    // Método para cerrar la conexión a la base de datos y que no quede abierta
    public static function cerrarBD(){
        // Si el valor de la conexión es cualquiera menos null
        if(self::$conexion !== null) {
            // Indicamos que cierre la conexión
            self::$conexion->close();
            // Reseteamos el valor a null
            self::$conexion = null;
        }
    }
    // Función para automatizar el parámetro inicial de un bind_param en un $stmt (statement)
    // Utilizamos los puntos suspensivos para crear un parámetro que acepte un número variable de parámetros
    private static function preparar($conexion, $consulta, ...$parametros){
        $preparacion = $conexion ->prepare($consulta);
        // Si existen parámetros
        if($parametros) {
            // Creamos una variable que contendrá la cadena de tipos del bind_param
            $tipos ='';
            // Indicamos que recorra cada parámetro de todos los parámetros que haya
            foreach($parametros as $parametro) {
                // Usando el condicional ternatio indicamos que si parámetro es un tipo numérico (int)
                // concatene una letra i a la vatiable tipos, y si no una s (de string)
                $tipos .= is_int($parametro) ? 'i' : 's';
            }
            $preparacion->bind_param($tipos,...$parametros);
        }
        return $preparacion;
    }

    // Método para inserciones en la base de datos
    public static function consultaInsercion($consulta,...$parametros){
        $conexion = self::conexionBD();
        $preparacion =self::preparar($conexion, $consulta, ...$parametros);
        // Si la preparación se ejecuta es true, si no es false
        if($preparacion->execute()){
            return true;
        } else {
            return false;
        }
        // Cerramos la BD al terminar cualquier conexión
        $conexion = self::cerrarBD();
    }
    // Método para lecturas en la base de datos
    public static function consultaLectura($consulta,...$parametros){
        $conexion = self::conexionBD();
        $preparacion =self::preparar($conexion, $consulta, ...$parametros);
        $preparacion->execute();
        $resultado = $preparacion->get_result();
        // En este caso buscamos obtener los datos a leer, si la consulta devuelve columnas las almacenamos, si no, devuelve null
        if($resultado->num_rows > 0) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
        // Cerramos la BD al terminar cualquier conexión
        $conexion = self::cerrarBD();
    }
}

// NF2 ________________________________________________
// CLASE PARA LOS DATOS DE eduFlow.xml
class eduFlowModelo {

    // Variable no inicializada que recogerá la ruta según si se trabaja desde el rest/soap o index
    private $xmlFile;
    // Construstor para inicializar la variable
    public function __construct($ruta)
    {
        // Cargamos el fichero
        $this->xmlFile = $ruta;
    }

    // Método para TODA la información del XML (tareas y tareas)
    public function cargarXml() {
        // Si consigue cargar correctamente el archivo XML
        if (file_exists($this->xmlFile)) {
            // Lo volcamos entero sobre la variable eduFlow
            $eduFlow = simplexml_load_file($this->xmlFile);
            // Y devolvemos la variable con el contenido volcado
            return $eduFlow;
        // Si no encontramos el archivo XML
        } else {
            // Forzamos salida del programa y lanzamos un mensaje de error
            exit('ERROR: No pudo abrirse el archivo eduFlow.xml');
        }
    }

    // Método para TODA la información de clases
    public function cargarClases() {
        // Cargamos el archivo entero
        $eduFlow = $this->cargarXml();
        // Con xpath, bajamos a la etiqueta clases y volcamos su contenido
        $clases = $eduFlow->xpath('/eduFlow/clases');
        return $clases;
    }

    // Método para TODA la información de tareas: funciona igual que el de clases
    public function cargarTareas() {
        $eduFlow = $this->cargarXml();
        $tareas = $eduFlow->xpath('/eduFlow/tareas');
        return $tareas;
    }
}


?>