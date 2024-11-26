<?php

namespace Controllers;

use Exception;
use Model\CumplimientoArresto;
use Model\Sancion;
use Model\Instructor;
use MVC\Router;

class CumplimientoController {
    public static function index(Router $router) {
        try {
            // Obtener sanciones con arrestos pendientes
            $query = "SELECT s.*, 
                     TRIM(a.alu_primer_nombre || ' ' || 
                          NVL(a.alu_segundo_nombre, '') || ' ' ||
                          a.alu_primer_apellido || ' ' || 
                          NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
                     r.ran_nombre,
                     g.gra_nombre,
                     g.gra_orden
                     FROM car_sancion s
                     JOIN car_alumno a ON s.san_catalogo = a.alu_id
                     JOIN car_rango r ON a.alu_rango_id = r.ran_id
                     JOIN car_grado_academico g ON a.alu_grado_id = g.gra_id
                     WHERE s.san_situacion = 1 
                     AND s.san_horas_arresto > 0
                     AND s.san_horas_arresto > (
                         SELECT COALESCE(SUM(cum_horas_cumplidas), 0)
                         FROM car_cumplimiento_arresto
                         WHERE cum_sancion_id = s.san_id
                         AND cum_situacion = 1)
                     ORDER BY g.gra_orden DESC, s.san_fecha_sancion DESC";
            
            $sanciones = Sancion::SQL($query);
            $instructores = Instructor::obtenerInstructorconQuery();

            if (!$sanciones || !$instructores) {
                throw new Exception("Error al obtener los datos necesarios");
            }

            $router->render('cumplimiento/index', [
                'titulo' => 'Control de Cumplimiento de Arrestos',
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
                    throw new Exception("No se encontró el cumplimiento");
                }
                return;
            }
    
            $cumplimientos = CumplimientoArresto::obtenerPendientes();
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $cumplimientos
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar cumplimientos: ' . $e->getMessage()
            ]);
        }
    }

    public static function guardarAPI() {
        header('Content-Type: application/json');
        try {
            if (empty($_POST['cum_sancion_id']) || empty($_POST['cum_fecha']) || 
                empty($_POST['cum_instructor_supervisa']) || empty($_POST['cum_horas_cumplidas'])) {
                throw new Exception("Todos los campos son obligatorios");
            }

            $cumplimiento = new CumplimientoArresto($_POST);
            $alertas = $cumplimiento->validar();
            
            if (!empty($alertas)) {
                throw new Exception(array_shift($alertas['error']));
            }

            $resultado = $cumplimiento->registrarCumplimiento();

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

    public static function modificarAPI() {
        header('Content-Type: application/json');
        try {
            if (!isset($_POST['cum_id'])) {
                throw new Exception("ID de cumplimiento no proporcionado");
            }

            $cumplimientoExistente = CumplimientoArresto::find($_POST['cum_id']);
            if (!$cumplimientoExistente) {
                throw new Exception("Cumplimiento no encontrado");
            }

            $cumplimientoExistente->sincronizar($_POST);
            $alertas = $cumplimientoExistente->validar();
            
            if (!empty($alertas)) {
                throw new Exception(array_shift($alertas['error']));
            }

            $resultado = $cumplimientoExistente->registrarCumplimiento();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cumplimiento modificado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo modificar el cumplimiento");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public static function cambiarEstadoAPI() {
        header('Content-Type: application/json');
        try {
            if (!isset($_POST['cum_id']) || !isset($_POST['estado'])) {
                throw new Exception("Datos incompletos");
            }

            $cumplimiento = CumplimientoArresto::find($_POST['cum_id']);
            if (!$cumplimiento) {
                throw new Exception("Cumplimiento no encontrado");
            }

            switch ($_POST['estado']) {
                case 'N': // No cumplió
                    $resultado = $cumplimiento->marcarNoCumplio($_POST['observaciones'] ?? '');
                    break;
                case 'T': // Trasladar
                    if (empty($_POST['nueva_fecha'])) {
                        throw new Exception("La fecha de traslado es obligatoria");
                    }
                    $resultado = $cumplimiento->trasladar($_POST['nueva_fecha'], $_POST['observaciones'] ?? '');
                    break;
                default:
                    throw new Exception("Estado no válido");
            }

            if ($resultado) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Estado actualizado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo actualizar el estado");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}