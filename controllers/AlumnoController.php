<?php

namespace Controllers;

use Model\Alumno;
use Model\GradoAcademico;
use Model\Rango;
use MVC\Router;
use Exception;

class AlumnoController
{
    public static function index(Router $router)
    {
        try {
            $grados = GradoAcademico::where('gra_situacion', 1);
            $rangos = Rango::where('ran_situacion', 1);

            $router->render('alumno/index', [
                'grados' => $grados ?? [],
                'rangos' => $rangos ?? []
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al cargar datos iniciales'
            ]);
        }
    }

    public static function guardarAPI()
    {
        header('Content-Type: application/json');
        try {
            $_POST['alu_catalogo'] = filter_var($_POST['alu_catalogo'], FILTER_SANITIZE_NUMBER_INT);

            if (Alumno::existeAlumno($_POST['alu_catalogo'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este Alumno ya está registrado con ese catálogo.'
                ]);
                return;
            }

            $alumno = new Alumno($_POST);
            $resultado = $alumno->crear();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno guardado exitosamente',
                    'id' => $resultado['id']
                ]);
            } else {
                throw new Exception("No se pudo guardar el Alumno");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json');
        try {
            $alumnos = Alumno::obtenerAlumnos();

            if ($alumnos) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Datos encontrados',
                    'datos' => $alumnos
                ]);
            } else {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'No hay Alumnos registrados',
                    'datos' => []
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar Alumnos: ' . $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        header('Content-Type: application/json');
        try {
            $_POST['alu_id'] = filter_var($_POST['alu_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['alu_catalogo'] = filter_var($_POST['alu_catalogo'], FILTER_SANITIZE_NUMBER_INT);

            if (!$_POST['alu_id']) {
                throw new Exception("ID de Alumno inválido");
            }

            $alumno = Alumno::find($_POST['alu_id']);
            if (!$alumno) {
                throw new Exception("Alumno no encontrado");
            }

            if (Alumno::existeAlumno($_POST['alu_catalogo'], $_POST['alu_id'])) {
                throw new Exception("Este Alumno ya está registrado con ese catálogo.");
            }

            $alumno->sincronizar($_POST);
            $resultado = $alumno->actualizar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno modificado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo modificar el Alumno");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        header('Content-Type: application/json');

        try {
            if (!isset($_POST['alu_id'])) {
                throw new Exception("No se recibió el ID del Alumno");
            }

            $_POST['alu_id'] = filter_var($_POST['alu_id'], FILTER_SANITIZE_NUMBER_INT);

            if (!$_POST['alu_id']) {
                throw new Exception("ID de Alumno inválido");
            }

            $alumno = Alumno::find($_POST['alu_id']);

            if (!$alumno) {
                throw new Exception("Alumno no encontrado");
            }

            $resultado = $alumno->eliminar();

            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno eliminado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo eliminar el Alumno");
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el Alumno: ' . $e->getMessage()
            ]);
        }
    }
}
