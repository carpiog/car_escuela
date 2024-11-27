<?php

namespace Controllers;

use Model\Alumno;
use MVC\Router;
use Mpdf\Mpdf;
use Exception;

class ReporteController
{

    public static function sancionesPDF(Router $router)
    {
        try {
            // Obtener el ID directamente del parámetro
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception("ID de alumno no proporcionado");
            }

            // Configuración del MPDF
            $mpdf = new Mpdf([
                "default_font_size" => "12",
                "default_font" => "arial",
                "orientation" => "P",
                "margin_top" => "30",
                "format" => "Letter"
            ]);

            // Obtener datos del alumno y sus sanciones
            $datos = Alumno::obtenerSancionesAlumno($id);

            if (empty($datos)) {
                throw new Exception("No se encontró información del alumno");
            }

            // Cargar encabezado y pie de página
            $header = $router->load('pdf/header');
            $footer = $router->load('pdf/footer');

            // Configurar header y footer
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            // Cargar estilos CSS
            $css = file_get_contents(__DIR__ . '/../views/pdf/styles.css');
            $mpdf->WriteHTML($css, 1);

            // Cargar la plantilla del reporte
            $html = $router->load('pdf/sanciones', [
                'datos' => $datos,
                'alumnoInfo' => $datos[0]
            ]);

            // Agregar el contenido HTML
            $mpdf->WriteHTML($html, 2);

            // Numerar páginas
            $mpdf->AliasNbPages();

            // Generar el PDF
            $mpdf->Output("Reporte_Sanciones.pdf", "I");
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al generar el PDF: ' . $e->getMessage()
            ]);
        }
    }
};
