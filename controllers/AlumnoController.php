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
            // Sanitización de los datos recibidos
            $_POST['alu_catalogo'] = filter_var($_POST['alu_catalogo'], FILTER_SANITIZE_NUMBER_INT);

            // Verificar si el alumno ya existe
            if (Alumno::existeAlumno($_POST['alu_catalogo'])) {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este alumno ya está registrado con ese catálogo.'
                ]);
                return;
            }

            // Decodificar los datos UTF-8 si es necesario
            // Aquí puedes usar utf8_decode para convertir los campos que lo necesiten
            $alumno_data = $_POST;

            // Usar utf8_decode en los campos que puedan contener caracteres especiales
            $alumno_data['alu_primer_nombre'] = utf8_decode($alumno_data['alu_primer_nombre']);
            $alumno_data['alu_segundo_nombre'] = utf8_decode($alumno_data['alu_segundo_nombre']);
            $alumno_data['alu_primer_apellido'] = utf8_decode($alumno_data['alu_primer_apellido']);
            $alumno_data['alu_segundo_apellido'] = utf8_decode($alumno_data['alu_segundo_apellido']);

            // Si es necesario, puedes también usar utf8_encode para codificar los datos antes de guardarlos
            // $alumno_data['alu_primer_nombre'] = utf8_encode($alumno_data['alu_primer_nombre']);

            // Crear el objeto Alumno
            $alumno = new Alumno($alumno_data);
            $resultado = $alumno->crear();

            // Responder según el resultado de la operación
            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno guardó exitosamente',
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
            // Sanitización de los datos recibidos
            $_POST['alu_id'] = filter_var($_POST['alu_id'], FILTER_SANITIZE_NUMBER_INT);
            $_POST['alu_catalogo'] = filter_var($_POST['alu_catalogo'], FILTER_SANITIZE_NUMBER_INT);

            // Verificar que el ID del alumno sea válido
            if (!$_POST['alu_id']) {
                throw new Exception("ID de Alumno inválido");
            }

            // Buscar el alumno por su ID
            $alumno = Alumno::find($_POST['alu_id']);
            if (!$alumno) {
                throw new Exception("Alumno no encontrado");
            }

            // Verificar si el catálogo ya está registrado para otro alumno
            if (Alumno::existeAlumno($_POST['alu_catalogo'], $_POST['alu_id'])) {
                throw new Exception("Este Alumno ya está registrado con ese catálogo.");
            }

            // Decodificar los datos UTF-8 si es necesario
            // Aquí puedes usar utf8_decode para convertir los campos que lo necesiten
            $alumno_data = $_POST;

            // Usar utf8_decode en los campos que puedan contener caracteres especiales
            $alumno_data['alu_primer_nombre'] = utf8_decode($alumno_data['alu_primer_nombre']);
            $alumno_data['alu_segundo_nombre'] = utf8_decode($alumno_data['alu_segundo_nombre']);
            $alumno_data['alu_primer_apellido'] = utf8_decode($alumno_data['alu_primer_apellido']);
            $alumno_data['alu_segundo_apellido'] = utf8_decode($alumno_data['alu_segundo_apellido']);

            // Sincronizar los datos del alumno con los datos recibidos en el formulario
            $alumno->sincronizar($alumno_data);

            // Realizar la actualización en la base de datos
            $resultado = $alumno->actualizar();

            // Verificar si la actualización fue exitosa
            if ($resultado['resultado']) {
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Alumno modificado exitosamente'
                ]);
            } else {
                throw new Exception("No se pudo modificar el Alumno");
            }
        } catch (Exception $e) {
            // Manejar cualquier excepción y devolver un mensaje de error
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
