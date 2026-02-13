<?php
/* ACTIVIDAD NF4
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Creamos una clase que recoja todos los métodos del servicio
class ServicioTareas {

    // Recogeremos el archivo completo sin usar métodos del controlador
    // Lo hacemos así porque si no la escritura del XML podría romperse si no guardamos usando todo el contenido del XML
    private $xmlFile = '../modelo/assets/eduFlow.xml';


    // Muestra las tareas del profesor que esté usando la API
    public function getTareas($profesor) {
        // Pasamos el XML a SimpleXML para su manejo
        $xml = simplexml_load_file($this->xmlFile);
        // Variable que recoge el resultado de la consulta
        $resultado = [];
        // Recorremos cada tarea, en tareas del XML
        foreach ($xml->tareas->tarea as $tarea) {
            // Si el profesor en la tarea es igual al profesor en el parámetro
            if ($tarea->profesor == $profesor) {
                // Introducimos un objeto tarea con todos sus elementos
                $resultado[] = [
                    'titulo' => (string) $tarea->titulo,
                    'descripcion' => (string) $tarea->descripcion,
                    'fecha_entrega' => (string) $tarea->fecha_entrega,
                    'profesor' => (string) $tarea->profesor,
                    'imagen' => (string) $tarea->imagen,
                    'urgente' => (string) $tarea->urgente,
                    'asignatura' => (string) $tarea->asignatura
                ];
            }
        }
        // Devolvemos el array
        return $resultado;
    }

    // Método para añadir tareas, con todos los parámetros que usa el XML
    public function addTarea ($titulo, $descripcion, $fecha_entrega, $profesor, $urgente, $asignatura) {

        // Pasamos el XML a SimpleXML para su manejo
        $xml = simplexml_load_file($this->xmlFile);

        // En vez de trabajar con arrays, trabajaremos con XML para que los cambios persistan
        //Creamos un nodo para la nueva tarea
        $nuevaTarea = $xml->tareas->addChild('tarea');
        // Incluimos cada hijo con el parámetro adecuado
        $nuevaTarea->addChild('titulo', $titulo);
        $nuevaTarea->addChild('descripcion', $descripcion);
        $nuevaTarea->addChild('fecha_entrega', $fecha_entrega);
        $nuevaTarea->addChild('profesor', $profesor);
        $nuevaTarea->addChild('imagen', "portada_1.png");
        $nuevaTarea->addChild('urgente', $urgente);
        $nuevaTarea->addChild('asignatura', $asignatura);

        // Guardar cambios en el XML
        $xml->asXML($this->xmlFile);
    }

    public function deleteTareas($asignatura, $profesor) {
        // Pasamos el XML a SimpleXML para su manejo
        $xml = simplexml_load_file($this->xmlFile);
        // Volcamos en tareas el XML de los nodos tarea sin sus padres o raíz
        $tareas = $xml->tareas->tarea;
        // Contamos el total de tareas, para poder recorrer hacia atrás de forma medida
        $total = count($tareas);

        // Recorremos los nodos de atrás hacia adelante, de forma que al borrar no haya desfases de posición
        // Hacemos esto porque en XML puede haber desfases que no ocurren con los Array
        // Iniciamos el índice en la última posición de lectura, leemos mientras el índice sea mayor o igual a cero y cada lectura reducimos el valor del índice
        for ($i = $total - 1; $i >= 0; $i--) {
            // La tarea que estamos leyendo es la de la variable tareas en el índice actual del bucle
            $tarea = $tareas[$i];
            // Comprobamos si la tarea del índice actual coincide con los parámetros del profesor y la asignatura
            if ((string)$tarea->asignatura === $asignatura && (string)$tarea->profesor === $profesor) {
                // Si hay coincidencia, borramos la tarea
                unset($xml->tareas->tarea[$i]);
            }
        }
        // Guardamos los cambios en el XML (sobreescribiéndolo)
        $xml->asXML($this->xmlFile);
    }
}

// DEFINICIÓN DEL SERVICIO__________________________________________

// Ruta base de nuestro servidor
$opciones = ['uri' => 'http://localhost/eduFlow/soap/soap_server.php'];
// Configuración y tipo de recursos
$server = new SoapServer(null, $opciones); // null es sin WSDL, si se define va ahí

// Exponemos la clase en el servicio para permitir su uso y disponibilidad en la API
$server->setClass('ServicioTareas');

// Manejo de las solicitudes y respuestas del servidor
$server->handle();

?>