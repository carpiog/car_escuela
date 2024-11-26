<?php

namespace Controllers;

use Exception;
use Model\Sancion;
use Model\Alumno;
use Model\Falta;
use Model\Instructor;
use MVC\Router;

class SancionController {
    public static function index(Router $router) {
        try {
            // Obtener los datos necesarios para los selects del formulario
            $alumnos = Alumno::obtenerAlumnos(); // Usando el método que incluye nombres completos
            $faltas = Falta::buscarTodos(); // Usando el método que incluye categorías
            $instructores = Instructor::obtenerInstructorconQuery(); // Usando el método que incluye nombres completos

            if (!$alumnos || !$faltas || !$instructores) {
                throw new Exception("Error al obtener los datos necesarios");
            }

            $router->render('sancion/index', [
                'alumnos' => $alumnos,
                'faltas' => $faltas,
                'instructores' => $instructores
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al cargar datos iniciales: ' . $e->getMessage()
            ]);
        }
    }

    public static function guardarAPI() {
        header('Content-Type: application/json');
        try {
            // Validar campos requeridos
            if (empty($_POST['san_catalogo']) || empty($_POST['san_fecha_sancion']) || 
                empty($_POST['san_falta_id']) || empty($_POST['san_instructor_ordena'])) {
                throw new Exception("Todos los campos son obligatorios");
            }

            // Sanitizar datos
            $_POST['san_catalogo'] = filter_var($_POST['san_catalogo'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['san_falta_id'] = filter_var($_POST['san_falta_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['san_instructor_ordena'] = filter_var($_POST['san_instructor_ordena'], FILTER_SANITIZE_NUMBER_INT);
            
            // Verificar si existe una sanción similar
            if (Sancion::existeSancion($_POST['san_catalogo'], $_POST['san_fecha_sancion'], $_POST['san_falta_id'])) {
                throw new Exception("Ya existe una sanción registrada para este alumno en la misma fecha con la misma falta.");
            }

            // Crear y validar la sanción
            $sancion = new Sancion($_POST);
            $alertas = $sancion->validar();
            
            if (!empty($alertas)) {
                throw new Exception(array_shift($alertas['error']));
            }

            $resultado = $sancion->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Sanción guardada exitosamente',
                    'id' => $resultado['id']
                ]);
            } else {
                throw new Exception("No se pudo guardar la sanción");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI() {
        header('Content-Type: application/json');
        try {
            if (isset($_GET['id'])) {
                $sancion = Sancion::obtenerSancion($_GET['id']);
                if ($sancion) {
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Datos encontrados',
                        'datos' => $sancion
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'No se encontró la sanción'
                    ]);
                }
                return;
            }
    
            $sanciones = Sancion::obtenerSanciones();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $sanciones
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar sanciones: ' . $e->getMessage()
            ]);
        }
    }    

    public static function modificarAPI() {
        header('Content-Type: application/json');
        try {
            if (!isset($_POST['san_id'])) {
                throw new Exception("ID de sanción no proporcionado");
            }

            // Sanitizar datos
            $_POST['san_id'] = filter_var($_POST['san_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['san_catalogo'] = filter_var($_POST['san_catalogo'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['san_falta_id'] = filter_var($_POST['san_falta_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['san_instructor_ordena'] = filter_var($_POST['san_instructor_ordena'], FILTER_SANITIZE_NUMBER_INT);

            // Buscar la sanción
            $sancion = Sancion::find($_POST['san_id']);
            if (!$sancion) {
                throw new Exception("Sanción no encontrada");
            }

            // Verificar duplicados
            if (Sancion::existeSancion($_POST['san_catalogo'], $_POST['san_fecha_sancion'], $_POST['san_falta_id'], $_POST['san_id'])) {
                throw new Exception("Ya existe una sanción registrada para este alumno en la misma fecha con la misma falta.");
            }

            // Sincronizar y validar
            $sancion->sincronizar($_POST);
            $alertas = $sancion->validar();
            
            if (!empty($alertas)) {
                throw new Exception(array_shift($alertas['error']));
            }

            $resultado = $sancion->actualizar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Sanción modificada exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo modificar la sanción");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI() {
        header('Content-Type: application/json');
        try {
            if (!isset($_POST['san_id'])) {
                throw new Exception("ID de sanción no proporcionado");
            }

            $_POST['san_id'] = filter_var($_POST['san_id'], FILTER_SANITIZE_NUMBER_INT);
            
            $sancion = Sancion::find($_POST['san_id']);
            if (!$sancion) {
                throw new Exception("Sanción no encontrada");
            }

            $resultado = $sancion->eliminar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Sanción eliminada exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo eliminar la sanción");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la sanción: ' . $e->getMessage()
            ]);
        }
    }
}