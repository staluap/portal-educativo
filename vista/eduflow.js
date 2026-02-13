/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

// Requerimos de algunos cambios de estilo (display, etc.) según interacción


// Introducimos una variable por cada botón del dashboard profesor
    const botonCalendarioProfe = document.getElementById("calendario-profe");
    const botonTareasProfe = document.getElementById("tareas-profe");
    // Introducimos una variable por cada segmeto diferenciado del dashboard
    const listaTareasProfe = document.querySelectorAll(".lista-tareas-profe");
    const listaClases = document.querySelectorAll(".lista-clases");
// Indicamos que los siguientes scripts sean ejecutados tras la ejecución del contenido HTML
document.addEventListener("DOMContentLoaded", function () {

    // JAVASCRIPT PARA DASHBOAR PROFESOR

    // Vista por defecto
    listaTareasProfe.forEach(tarea => tarea.style.display = "none");
    listaClases.forEach(clase => clase.style.display = "flex");

    // Al pulsar el botón calendario
    botonCalendarioProfe.addEventListener("click", () => {
        listaTareasProfe.forEach(tarea => tarea.style.display = "none");
        listaClases.forEach(clase => clase.style.display = "flex");
    })
    // Al pulsar el botón de tareas
    botonTareasProfe.addEventListener("click", () => {
        listaTareasProfe.forEach(tarea => tarea.style.display = "flex");
        listaClases.forEach(clase => clase.style.display = "none");
    })

});