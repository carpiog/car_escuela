<?php
namespace Controllers;

use Exception;
use Model\Falta;
use MVC\Router;

class FaltaController {
    public static function index(Router $router) {
        $tipo = $_GET['tipo'] ?? 'TODAS';
        
        $router->render('falta/index', [
            'titulo' => $tipo
        ]);
    }

    public static function buscarAPI() {
        try {
            $tipo = isset($_GET['tipo']) ? strtoupper($_GET['tipo']) : null;
            $faltas = Falta::buscarTodos($tipo);

            if ($faltas === false) {
                throw new Exception("Error al obtener los datos");
            }

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $faltas
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar faltas: ' . $e->getMessage(),
                'datos' => []
            ]);
        }
    }
}