<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticaController {

    public static function index(Router $router) {
        // Renderiza la vista principal de estadísticas
        $router->render('estadisticas/index');
    }

    public static function tiposAPI() {
        try {
            $sql = 'SELECT 
                    tf.tip_nombre AS tipo_falta,
                    COUNT(san.san_id) AS total_sanciones
                    FROM 
                    car_sancion san
                    JOIN 
                    car_falta fal ON san.san_falta_id = fal.fal_id
                    JOIN 
                    car_categoria_falta cf ON fal.fal_categoria_id = cf.cat_id
                    JOIN 
                    car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id
                    WHERE 
                    san.san_situacion = 1
                    GROUP BY 
                    tf.tip_nombre
                    ORDER BY 
                    total_sanciones DESC';

            $datos = ActiveRecord::fetchArray($sql);

            echo json_encode($datos);
        } catch (Exception $e) {
            self::handleException($e, 'Error al obtener estadísticas por tipo.');
        }
    }

    public static function gradosAPI() {
        try {
            $sql = 'SELECT 
                    ga.gra_nombre AS grado,
                    COUNT(san.san_id) AS total_sanciones,
                    ga.gra_orden  
                    FROM 
                    car_sancion san
                    JOIN 
                    car_alumno alu ON san.san_catalogo = alu.alu_id
                    JOIN 
                    car_grado_academico ga ON alu.alu_grado_id = ga.gra_id
                    GROUP BY 
                    ga.gra_nombre, ga.gra_orden  
                    ORDER BY 
                    ga.gra_orden DESC';

            $datos = ActiveRecord::fetchArray($sql);

            echo json_encode($datos);
        } catch (Exception $e) {
            self::handleException($e, 'Error al obtener estadísticas por grado.');
        }
    }

    public static function faltasAPI() {
        try {
            $sql = 'SELECT f.fal_descripcion AS descripcion, COUNT(*) AS total
                FROM car_sancion s
                INNER JOIN car_falta f ON s.san_falta_id = f.fal_id
                WHERE s.san_situacion = 1
                GROUP BY f.fal_descripcion';

            $datos = ActiveRecord::fetchArray($sql);

            echo json_encode($datos);
        } catch (Exception $e) {
            self::handleException($e, 'Error al obtener estadísticas de faltas frecuentes.');
        }
    }

    /**
     * Método para manejar excepciones y devolver una respuesta JSON.
     */
    private static function handleException(Exception $e, $mensaje) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => $mensaje,
            'codigo' => 0
        ]);
    }
}
