<?php
namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;
use Mpdf\Mpdf;

class ConductaController {
   public static function index(Router $router) {
       $router->render('conducta/index', [
           'titulo' => 'Conductas Generales'
       ]);
   }

   public static function buscarAPI() {
       header('Content-Type: application/json');
       try {
           $query = "SELECT 
    a.alu_id,     
    a.alu_catalogo,     
    TRIM(a.alu_primer_nombre || ' ' || NVL(a.alu_segundo_nombre, '') || ' ' || 
         a.alu_primer_apellido || ' ' || NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,     
    r.ran_nombre,     
    g.gra_nombre,     
    COUNT(s.san_id) AS total_sanciones,     
    NVL(SUM(s.san_demeritos), 0) AS total_demeritos 
FROM 
    car_alumno a 
LEFT JOIN 
    car_rango r ON a.alu_rango_id = r.ran_id 
LEFT JOIN 
    car_grado_academico g ON a.alu_grado_id = g.gra_id 
LEFT JOIN 
    car_sancion s ON a.alu_id = s.san_catalogo AND s.san_situacion = 1 
WHERE 
    a.alu_situacion = 1
GROUP BY 
    a.alu_id, a.alu_catalogo, a.alu_primer_nombre, a.alu_segundo_nombre, 
    a.alu_primer_apellido, a.alu_segundo_apellido, r.ran_nombre, g.gra_nombre 
ORDER BY 
    total_demeritos ASC, -- Primero los alumnos con menos deméritos
    total_sanciones ASC; ";

           $conductas = ActiveRecord::fetchArray($query);
           
           echo json_encode([
               'codigo' => 1,
               'datos' => $conductas
           ]);
       } catch (Exception $e) {
           echo json_encode([
               'codigo' => 0,
               'mensaje' => $e->getMessage()
           ]);
       }
   }

   public static function conductaPDF(Router $router) {
       try {
           $mpdf = new Mpdf([
               "default_font_size" => "12",
               "default_font" => "arial",
               "orientation" => "L",
               "margin_top" => "30",
               "format" => "Letter"
           ]);

           $query = "SELECT 
    a.alu_id,     
    a.alu_catalogo,     
    TRIM(a.alu_primer_nombre || ' ' || NVL(a.alu_segundo_nombre, '') || ' ' || 
         a.alu_primer_apellido || ' ' || NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,     
    r.ran_nombre,     
    g.gra_nombre,     
    COUNT(s.san_id) AS total_sanciones,     
    NVL(SUM(s.san_demeritos), 0) AS total_demeritos 
FROM 
    car_alumno a 
LEFT JOIN 
    car_rango r ON a.alu_rango_id = r.ran_id 
LEFT JOIN 
    car_grado_academico g ON a.alu_grado_id = g.gra_id 
LEFT JOIN 
    car_sancion s ON a.alu_id = s.san_catalogo AND s.san_situacion = 1 
WHERE 
    a.alu_situacion = 1
GROUP BY 
    a.alu_id, a.alu_catalogo, a.alu_primer_nombre, a.alu_segundo_nombre, 
    a.alu_primer_apellido, a.alu_segundo_apellido, r.ran_nombre, g.gra_nombre 
ORDER BY 
    total_demeritos ASC, -- Primero los alumnos con menos deméritos
    total_sanciones ASC; ";

           $conductas = ActiveRecord::fetchArray($query);

           $header = $router->load('pdf/header');
           $footer = $router->load('pdf/footer');

           $mpdf->SetHTMLHeader($header);
           $mpdf->SetHTMLFooter($footer);

           $css = file_get_contents(__DIR__ . '/../views/pdf/styles.css');
           $mpdf->WriteHTML($css, 1);

           $html = $router->load('pdf/conducta', [
               'conductas' => $conductas,
               'fecha' => date('Y-m-d')
           ]);

           $mpdf->WriteHTML($html, 2);
           $mpdf->AliasNbPages();
           $mpdf->Output("Conductas_Generales.pdf", "I");

       } catch (Exception $e) {
           header('Content-Type: application/json');
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al generar el PDF: ' . $e->getMessage()
           ]);
       }
   }
}