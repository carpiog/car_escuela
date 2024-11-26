<?php

namespace Controllers;

use Exception;
use Model\Instructor;
use MVC\Router;

class InstructorController {
    public static function index(Router $router) {
        $router->render('instructor/index', []);
    }

    public static function guardarAPI() {
        header('Content-Type: application/json');
        
        try {
            $_POST['ins_catalogo'] = filter_var($_POST['ins_catalogo'], FILTER_SANITIZE_NUMBER_INT);
            
            // Validar que el catálogo exista en la tabla mper
            if (!Instructor::validarCatalogo($_POST['ins_catalogo'])) {
                throw new Exception("El catálogo ingresado no existe en el sistema.");
            }

            // Validar que el catálogo no esté ya registrado como instructor
            if (Instructor::existeInstructor($_POST['ins_catalogo'])) {
                throw new Exception("Este oficial ya está registrado como instructor.");
            }

            $instructor = new Instructor($_POST);
            $resultado = $instructor->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Instructor guardado exitosamente',
                    'id' => $resultado['id']
                ]);
            } else {
                throw new Exception("No se pudo guardar el instructor");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI() {
        header('Content-Type: application/json');
        
        try {
            $_POST['ins_id'] = filter_var($_POST['ins_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['ins_catalogo'] = filter_var($_POST['ins_catalogo'], FILTER_SANITIZE_NUMBER_INT);

            if (!$_POST['ins_id']) {
                throw new Exception("ID de instructor inválido");
            }

            $instructor = Instructor::find($_POST['ins_id']);
            
            if (!$instructor) {
                throw new Exception("Instructor no encontrado");
            }

            // Validar que el catálogo exista en la tabla mper
            if (!Instructor::validarCatalogo($_POST['ins_catalogo'])) {
                throw new Exception("El catálogo ingresado no existe en el sistema.");
            }

            // Validar que el nuevo catálogo no esté ya registrado como instructor
            if (Instructor::existeInstructor($_POST['ins_catalogo'], $_POST['ins_id'])) {
                throw new Exception("Este oficial ya está registrado como instructor.");
            }

            $instructor->sincronizar($_POST);
            $resultado = $instructor->actualizar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Instructor modificado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo modificar el instructor");
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
            $instructores = Instructor::obtenerInstructorconQuery();
            
            if ($instructores) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Datos encontrados',
                    'datos' => $instructores
                ]);
            } else {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay instructores registrados',
                    'datos' => []
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar instructores: ' . $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['ins_id'])) {
                throw new Exception("No se recibió el ID del instructor");
            }

            $_POST['ins_id'] = filter_var($_POST['ins_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (!$_POST['ins_id']) {
                throw new Exception("ID de instructor inválido");
            }

            $instructor = Instructor::find($_POST['ins_id']);
            
            if (!$instructor) {
                throw new Exception("Instructor no encontrado");
            }

            $resultado = $instructor->eliminar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Instructor eliminado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo eliminar el instructor");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el instructor: ' . $e->getMessage()
            ]);
        }
    }
}