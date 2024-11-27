<?php

namespace Controllers;

use Exception;
use Model\CumplimientoArresto;
use Model\Sancion;
use Model\Instructor;
use MVC\Router;

class CumplimientoArrestoController {
    public static function index(Router $router) {
        try {
            $sanciones = Sancion::obtenerSanciones();
            $instructores = Instructor::obtenerInstructorconQuery();

            if (!$sanciones || !$instructores) {
                throw new Exception("Error al obtener los datos necesarios");
            }

            $router->render('cumplimiento/index', [
                'sanciones' => $sanciones,
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
            if (empty($_POST['cum_sancion_id']) || empty($_POST['cum_fecha']) || 
                empty($_POST['cum_instructor_supervisa'])) {
                throw new Exception("Todos los campos obligatorios deben ser completados");
            }

            $_POST['cum_sancion_id'] = filter_var($_POST['cum_sancion_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['cum_instructor_supervisa'] = filter_var($_POST['cum_instructor_supervisa'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['cum_horas_cumplidas'] = filter_var($_POST['cum_horas_cumplidas'] ?? 0, FILTER_SANITIZE_NUMBER_INT);

            $cumplimiento = new CumplimientoArresto($_POST);
            $alertas = $cumplimiento->validar();
            
            if (!empty($alertas)) {
                throw new Exception(array_shift($alertas['error']));
            }

            $resultado = $cumplimiento->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cumplimiento registrado exitosamente',
                    'id' => $resultado['id']
                ]);
            } else {
                throw new Exception("No se pudo registrar el cumplimiento");
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
                $cumplimiento = CumplimientoArresto::obtenerCumplimiento($_GET['id']);
                if ($cumplimiento) {
                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Datos encontrados',
                        'datos' => $cumplimiento
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'No se encontró el registro'
                    ]);
                }
                return;
            }

            $cumplimientos = CumplimientoArresto::obtenerCumplimientos();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $cumplimientos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar registros: ' . $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI() {
        header('Content-Type: application/json');
        try {
            if (!isset($_POST['cum_id'])) {
                throw new Exception("ID de cumplimiento no proporcionado");
            }

            $_POST['cum_id'] = filter_var($_POST['cum_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['cum_sancion_id'] = filter_var($_POST['cum_sancion_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['cum_instructor_supervisa'] = filter_var($_POST['cum_instructor_supervisa'], FILTER_SANITIZE_NUMBER_INT);

            $cumplimiento = CumplimientoArresto::find($_POST['cum_id']);
            if (!$cumplimiento) {
                throw new Exception("Registro no encontrado");
            }

            $cumplimiento->sincronizar($_POST);
            $alertas = $cumplimiento->validar();
            
            if (!empty($alertas)) {
                throw new Exception(array_shift($alertas['error']));
            }

            $resultado = $cumplimiento->actualizar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Registro actualizado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo actualizar el registro");
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
            if (!isset($_POST['cum_id'])) {
                throw new Exception("ID de cumplimiento no proporcionado");
            }

            $_POST['cum_id'] = filter_var($_POST['cum_id'], FILTER_SANITIZE_NUMBER_INT);
            
            $cumplimiento = CumplimientoArresto::find($_POST['cum_id']);
            if (!$cumplimiento) {
                throw new Exception("Registro no encontrado");
            }

            $resultado = $cumplimiento->eliminar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Registro eliminado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo eliminar el registro");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el registro: ' . $e->getMessage()
            ]);
        }
    }
}