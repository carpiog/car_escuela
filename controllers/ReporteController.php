<?php

namespace Controllers;

use Model\Alumno;
use MVC\Router;
use Mpdf\Mpdf;
use Exception;

class ReporteController {
    
    // Método para mostrar la vista principal
    public static function index(Router $router) {
        try {
            // Obtener lista de grados para el filtro
            $grados = Alumno::fetchArray("
                SELECT 
                    gra_id, 
                    gra_nombre 
                FROM car_grado_academico 
                ORDER BY gra_orden ASC
            ");
            
            // Renderizar la vista con los grados
            $router->render('alugrado/index', [
                'titulo' => 'Listado de Alumnos por Grado',
                'grados' => $grados
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al cargar la página: ' . $e->getMessage()
            ]);
        }
    }

    public static function buscarAPI() {
        try {
            $gradoId = isset($_GET['grado']) ? intval($_GET['grado']) : null;
            
            $query = "SELECT 
                    a.alu_id,
                    a.alu_catalogo,
                    a.alu_grado_id,
                    a.alu_rango_id,
                    a.alu_primer_nombre,
                    a.alu_segundo_nombre,
                    a.alu_primer_apellido,
                    a.alu_segundo_apellido,
                    a.alu_sexo,
                    g.gra_nombre AS grado_nombre,
                    r.ran_nombre AS rango_nombre,
                    a.alu_primer_nombre || ' ' || a.alu_segundo_nombre || ' ' || 
                    a.alu_primer_apellido || ' ' || a.alu_segundo_apellido AS nombres_completos
                FROM 
                    car_alumno a
                JOIN 
                    car_grado_academico g ON a.alu_grado_id = g.gra_id
                JOIN 
                    car_rango r ON a.alu_rango_id = r.ran_id
                WHERE 
                    a.alu_situacion = 1";
    
            if ($gradoId) {
                $query .= " AND a.alu_grado_id = " . $gradoId; //usar concatenación directa
            }
    
            $query .= " ORDER BY g.gra_orden ASC";
    
            $alumnos = Alumno::fetchArray($query);
    
            header('Content-Type: application/json');
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $alumnos ?? []
            ]);
    
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error: ' . $e->getMessage(),
                'datos' => []
            ]);
        }
    }

    // Método para generar PDF de sanciones
    public static function sancionesPDF(Router $router) {
        try {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception("ID de alumno no proporcionado");
            }

            // Configuración de MPDF
            $mpdf = new Mpdf([
                "default_font_size" => "12",
                "default_font" => "arial",
                "orientation" => "P",
                "margin_top" => "30",
                "format" => "Letter"
            ]);

            // Obtener datos del alumno y sus sanciones usando el método existente
            $datos = Alumno::obtenerSancionesAlumno($id);

            if (empty($datos)) {
                throw new Exception("No se encontró información del alumno");
            }

            // Cargar plantillas
            $header = $router->load('pdf/header');
            $footer = $router->load('pdf/footer');

            // Configurar header y footer
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            // Cargar estilos CSS
            $css = file_get_contents(__DIR__ . '/../views/pdf/styles.css');
            $mpdf->WriteHTML($css, 1);

            // Cargar y renderizar la plantilla del reporte
            $html = $router->load('pdf/sanciones', [
                'datos' => $datos,
                'alumnoInfo' => $datos[0]
            ]);

            // Escribir contenido
            $mpdf->WriteHTML($html, 2);

            // Configurar numeración de páginas
            $mpdf->AliasNbPages();

            // Generar y mostrar el PDF
            $mpdf->Output("Reporte_Sanciones.pdf", "I");

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al generar el PDF: ' . $e->getMessage()
            ]);
        }
    }
}