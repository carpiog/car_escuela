<?php
namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class ArrestoController {
    public static function index(Router $router) {
        $router->render('arresto/index');
    }

    public static function listarAPI() {
        try {
            $query = "SELECT 
                a.alu_id,
                a.alu_catalogo, 
                g.gra_nombre,
                r.ran_nombre,
                TRIM(a.alu_primer_nombre || ' ' || 
                     NVL(a.alu_segundo_nombre, '') || ' ' ||
                     a.alu_primer_apellido || ' ' || 
                     NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
                SUM(NVL(s.san_horas_arresto, 0)) AS total_horas_arresto,
                SUM(NVL(s.san_horas_cumplidas, 0)) AS horas_cumplidas,
                SUM(NVL(s.san_horas_arresto, 0)) - SUM(NVL(s.san_horas_cumplidas, 0)) AS horas_pendientes,
                s.san_estado AS estado_sancion
            FROM car_alumno a
            LEFT JOIN car_sancion s ON a.alu_id = s.san_catalogo AND s.san_situacion = 1
            JOIN car_grado_academico g ON a.alu_grado_id = g.gra_id
            JOIN car_rango r ON a.alu_rango_id = r.ran_id
            WHERE a.alu_situacion = 1
            GROUP BY 
                a.alu_id, a.alu_catalogo, g.gra_nombre, g.gra_orden, r.ran_nombre, 
                a.alu_primer_nombre, a.alu_segundo_nombre, 
                a.alu_primer_apellido, a.alu_segundo_apellido, s.san_estado
            HAVING SUM(NVL(s.san_horas_arresto, 0)) > SUM(NVL(s.san_horas_cumplidas, 0))
            ORDER BY g.gra_orden DESC";

            echo json_encode([
                'codigo' => 1,
                'datos' => ActiveRecord::fetchArray($query)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'detalle' => $e->getMessage(),
                'mensaje' => 'Error al obtener el listado de arrestos',
                'codigo' => 0
            ]);
        }
    }

    public static function actualizarAPI() {
        try {
            if (!isset($_POST['alu_id']) || !isset($_POST['horas_cumplidas'])) {
                throw new Exception("Datos incompletos");
            }

            $alu_id = (int)$_POST['alu_id'];
            $horas_cumplidas = (float)$_POST['horas_cumplidas'];
            $horas_restantes = $horas_cumplidas;

            // Obtener todas las sanciones pendientes ordenadas por fecha de sanción
            $sanciones = ActiveRecord::fetchArray("
                SELECT san_id, san_horas_arresto, san_horas_cumplidas 
                FROM car_sancion 
                WHERE san_catalogo = {$alu_id} 
                AND san_situacion = 1
                AND san_estado = 'P'
                ORDER BY san_fecha_sancion ASC
            ");

            if (!$sanciones) {
                throw new Exception("No se encontraron sanciones pendientes");
            }

            // Distribuir las horas cumplidas entre las sanciones
            foreach ($sanciones as $sancion) {
                if ($horas_restantes <= 0) break;

                $horas_pendientes_sancion = $sancion['san_horas_arresto'] - $sancion['san_horas_cumplidas'];
                $horas_a_aplicar = min($horas_restantes, $horas_pendientes_sancion);

                if ($horas_a_aplicar > 0) {
                    $nuevas_horas_cumplidas = $sancion['san_horas_cumplidas'] + $horas_a_aplicar;
                    
                    $queryUpdate = "UPDATE car_sancion 
                        SET 
                            san_horas_cumplidas = {$nuevas_horas_cumplidas},
                            san_estado = CASE 
                                WHEN san_horas_arresto <= {$nuevas_horas_cumplidas} THEN 'C' 
                                ELSE 'P' 
                            END
                        WHERE san_id = {$sancion['san_id']}";

                    ActiveRecord::SQL($queryUpdate);
                    $horas_restantes -= $horas_a_aplicar;
                }
            }

            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Horas registradas correctamente'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al actualizar: ' . $e->getMessage()
            ]);
        }
    }
}