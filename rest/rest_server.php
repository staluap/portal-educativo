<?php
/* ACTIVIDAD NF4
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */


// ESTRUCTURA INICIAL _________________________________________________________

//incluímos las funciones de controlador, que a su vez conectan con el modelo, para poder obtener arrays de información
include_once '../controlador/funciones_controlador.php';

// Indicamos el modelo de dato con el que vamos a trabajar (JSON)
header("Content-Type: application/json");

// Método o respuesta del servidor
$method = $_SERVER['REQUEST_METHOD'];

// Creamos una variable path_info. Si PATH_INFO existe toma su valor, si no es null
$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;
// Creamos una variable de petición
//Separamos la dirección en trozos delimitados por las barras de dirección
$request = $pathInfo ? explode('/', trim($pathInfo, '/')) : [];



// FUNCIONES _________________________________________________________________

// Función que recibe un string (en este caso del nombre del profesor) para simplificarlo a la hora de comparar: todo en minúsculas, sin espacios, y convirtiendo tildes a su versión sin tilde.
// Esto lo hemos hecho porque estábamos haciendo pruebas con direcciones en POSTMAN y el navegador y los espacios y tildes suponían un problema
function normalizar($string) {
    return strtolower(trim(iconv('UTF-8', 'ASCII//TRANSLIT', $string)));
}

// Función para obtener las clases, con un parámetro opcional de profesor
function getClases($profesor = null) {
    //  Aprovechamos el método preexistente en funciones controlador para obtener sólo las clases (todas las clases)
    $clasesXML = ControlAgenda::arrayClasesXML('../modelo/assets/eduFlow.xml');
    // Array vacío que recogerá las clases que deban ser mostradas
    $consulta = [];

    // Recorremos el array de clases, clase por clase
    foreach ($clasesXML->clase as $clase) {
        // Aplicamos normalizar al nombre del profesor contenido en el XML de ese nodo clase
        $profeXML = normalizar((string)$clase->profesor);
        // Comprobamos si se ha pasado parámetro por el método, si es así normalizamos el string del parámetro
        $profeParam = $profesor ? normalizar($profesor) : null;

        // Indicamos que si el parámetro pasado por el método es null se imprima la clase (lo que imprimirá todo)
        // O bien, si hay parámetro que se imprima la clase sólo si coincide el profesor del XML con el pasado por parámetro
        if ($profeParam === null || $profeXML === $profeParam) {
            // Añadimos a la consulta un objeto más en el array si se cumple alguna de las dos condiciones
            $consulta[] = [
                // Volcamos cada hijo del nodo clase en un par clave-valor
                'asignatura' => (string)$clase->asignatura,
                'profesor'   => (string)$clase->profesor,
                'dia'        => (string)$clase->dia,
                'hora'       => (string)$clase->hora,
                'aula'       => (string)$clase->aula,
            ];
        }
    }

    // Devolvemos la variable consulta con su contenido
    return $consulta;
}


// PROCESAMIENTO ______________________________________________________________

// Si el método realizado es GET
if ($method === 'GET') {
    // Comprobamos si existe alguna petición (request) en la posición 0 de la url, y de ser así extraemos el valor con urldecode y lo asignamos a la variable profesor. De no existir, asignamos null (devolviendo todas las clases)
    $profesor = isset($request[0]) ? urldecode($request[0]) : null;
    // Usamos el método que obtiene las clases con el parámetro obtenido en la petición
    // Imprimimos con json
    echo json_encode(getClases($profesor));

// No hay otros métodos, cualquier otra acción fuera de lo definido deberá dar ERROR
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>