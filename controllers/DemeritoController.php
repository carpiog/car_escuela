<?php
namespace Controllers;

use Model\DemeritoAcumulado;
use MVC\Router;

class DemeritoController {
    public static function index(Router $router) {
        try {
            $demeritos = DemeritoAcumulado::obtenerTodos();
            $estadisticas = DemeritoAcumulado::obtenerEstadisticasPorGrado();

            $router->render('demerito/index', [
                'demeritos' => $demeritos,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al cargar datos: ' . $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI() {
        header('Content-Type: application/json');
        try {
            $demeritos = isset($_GET['alumno_id']) 
                ? DemeritoAcumulado::obtenerPorID($_GET['alumno_id'])
                : DemeritoAcumulado::obtenerTodos();

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $demeritos
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar datos: ' . $e->getMessage()
            ]);
        }
    }

    public static function alumnosRiesgoAPI() {
        header('Content-Type: application/json');
        try {
            $alumnos = DemeritoAcumulado::obtenerAlumnosEnRiesgo();
            
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $alumnos
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar alumnos en riesgo: ' . $e->getMessage()
            ]);
        }
    }
}