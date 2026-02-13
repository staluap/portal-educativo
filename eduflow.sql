/* ACTIVIDAD NF3
Desarrollo Web en Entorno Servidor - iFP 2025/2026
Paula Serrano Torrecillas */

/* Tablas tomadas del enunciado */

CREATE DATABASE eduflow;
USE eduflow;
CREATE TABLE usuarios (
id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nombre_usuario VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nombre de usuario para el login (único)',
contrasena_hash VARCHAR(255) NOT NULL COMMENT 'Hash de la contraseña generada con password_hash()',
nombre_completo VARCHAR(100) NOT NULL COMMENT 'Nombre y apellidos del usuario',
perfil ENUM('admin','profesor','estudiante') NOT NULL COMMENT 'Rol del usuario dentro del sistema',
fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del usuario'
);
CREATE TABLE tareas (
id_tarea INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nombre_tarea VARCHAR(150) NOT NULL COMMENT 'Nombre de la tarea (tomado del XML)',
asignatura VARCHAR(100) NOT NULL COMMENT 'Nombre de la asignatura (desde el XML)',
nombre_profesor VARCHAR(100) NOT NULL COMMENT 'Nombre completo del profesor responsable',
nombre_alumno VARCHAR(100) NOT NULL COMMENT 'Nombre completo del alumno que entrega la tarea',
fecha_entrega DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora en la que el alumno realiza la entrega',
mensaje_profesor TEXT NULL COMMENT 'Comentario o corrección escrita por el profesor',
archivo_entrega VARCHAR(255) NULL COMMENT 'Nombre o ruta del archivo entregado (opcional)'
);

CREATE INDEX idx_tareas_profesor ON tareas (nombre_profesor);
CREATE INDEX idx_tareas_alumno ON tareas (nombre_alumno);
CREATE INDEX idx_tareas_asignatura ON tareas (asignatura);

-- Línea de código añadida para tener un usuario admin por defecto
INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena_hash`, `nombre_completo`, `perfil`, `fecha_alta`) VALUES (NULL, 'admin', 'bef57ec7f53a6d40beb640a780a639c83bc29ac8a9816f1fc6c5c6dcd93c4721', 'Paula Serrano', 'admin', current_timestamp());