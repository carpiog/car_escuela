<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\AlumnoController;
use MVC\Router;
use Controllers\AppController;
use Controllers\ArrestoController;
use Controllers\DemeritoController;
use Controllers\EstadisticaController;
use Controllers\FaltaController;
use Controllers\InstructorController;
use Controllers\ReporteController;
use Controllers\SancionController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);
$router->get('/', [AppController::class, 'index']);

//REGISTRAR INSTRUCTORES DE ALTA
$router->get('/instructor', [InstructorController::class, 'index']);
$router->post('/API/instructor/guardar', [InstructorController::class, 'guardarAPI']);
$router->get('/API/instructor/buscar', [InstructorController::class, 'buscarAPI']);
$router->post('/API/instructor/modificar', [InstructorController::class, 'modificarAPI']);
$router->post('/API/instructor/eliminar', [InstructorController::class, 'eliminarAPI']);

//REGISTRAR ALUMNOS
$router->get('/alumno', [AlumnoController::class, 'index']);
$router->post('/API/alumno/guardar', [AlumnoController::class, 'guardarAPI']);
$router->get('/API/alumno/buscar', [AlumnoController::class, 'buscarAPI']);
$router->post('/API/alumno/modificar', [AlumnoController::class, 'modificarAPI']);
$router->post('/API/alumno/eliminar', [AlumnoController::class, 'eliminarAPI']);

// Ruta para la vista de filtrado por grado
$router->get('/alugrado', [ReporteController::class, 'index']);
$router->get('/API/alumno/buscar', [ReporteController::class, 'buscarAPI']);
// Ruta para generar el PDF del listado de Alumnos
$router->get('/reporte/sancionesPDF', [ReporteController::class, 'sancionesPDF']);


//TODAS LAS SANCIONES
$router->get('/falta', [FaltaController::class, 'index']);
$router->post('/API/falta/guardar', [FaltaController::class, 'guardarAPI']);
$router->get('/API/falta/buscar', [FaltaController::class, 'buscarAPI']);
$router->post('/API/falta/eliminar', [FaltaController::class, 'eliminarAPI']);

//REGISTRAR ARRESTO
$router->get('/sancion', [SancionController::class, 'index']);
$router->post('/API/sancion/guardar', [SancionController::class, 'guardarAPI']);
$router->get('/API/sancion/buscar', [SancionController::class, 'buscarAPI']);
$router->post('/API/sancion/eliminar', [SancionController::class, 'eliminarAPI']);

//VISTAS PARA VER DEMERITOS ACUMULADOS, ASI TAMBIEN LA CONDUCTA
$router->get('/demerito', [DemeritoController::class, 'index']);
$router->get('/API/demerito/buscar', [DemeritoController::class, 'buscarAPI']);
$router->get('/API/demerito/riesgo', [DemeritoController::class, 'alumnosRiesgoAPI']);

// RUTAS PARA ESTADÍSTICAS DE SANCIONES
$router->get('/estadisticas', [EstadisticaController::class, 'index']);
$router->get('/API/estadisticas/tipos', [EstadisticaController::class, 'tiposAPI']);
$router->get('/API/estadisticas/grados', [EstadisticaController::class, 'gradosAPI']);
$router->get('/API/estadisticas/faltas', [EstadisticaController::class, 'faltasAPI']);
$router->get('/API/estadisticas/todas', [EstadisticaController::class, 'todasAPI']);

// RUTAS PARA CONTROL DE ARRESTOS
$router->get('/arresto', [ArrestoController::class, 'index']);
$router->get('/API/arresto/listar', [ArrestoController::class, 'listarAPI']); 
$router->post('/API/arresto/actualizar', [ArrestoController::class, 'actualizarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
