<?php
namespace Model;

class Falta extends ActiveRecord {
    protected static $tabla = 'car_falta';
    protected static $idTabla = 'fal_id';
    protected static $columnasDB = ['fal_id', 'fal_categoria_id', 'fal_descripcion', 'fal_horas_arresto', 'fal_demeritos', 'fal_situacion'];

    public $fal_id;
    public $fal_categoria_id;
    public $fal_descripcion;
    public $fal_horas_arresto;
    public $fal_demeritos;
    public $fal_situacion;

    public function __construct($args = []) {
        $this->fal_id = $args['fal_id'] ?? null;
        $this->fal_categoria_id = $args['fal_categoria_id'] ?? '';
        $this->fal_descripcion = $args['fal_descripcion'] ?? '';
        $this->fal_horas_arresto = $args['fal_horas_arresto'] ?? null;
        $this->fal_demeritos = $args['fal_demeritos'] ?? null;
        $this->fal_situacion = $args['fal_situacion'] ?? 1;
    }

    public static function buscarTodos($tipo = null) {
        $query = "SELECT DISTINCT
                    f.fal_id,
                    f.fal_descripcion,
                    f.fal_horas_arresto,
                    f.fal_demeritos,
                    cf.cat_nombre as categoria_nombre,
                    tf.tip_nombre as tipo_nombre,
                    tf.tip_id as tipo_id,
                    CASE tf.tip_nombre 
                        WHEN 'LEVE' THEN 1 
                        WHEN 'GRAVE' THEN 2 
                        WHEN 'GRAVISIMA' THEN 3 
                    END as orden_tipo
                FROM " . static::$tabla . " f 
                JOIN car_categoria_falta cf ON f.fal_categoria_id = cf.cat_id 
                JOIN car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id 
                WHERE f.fal_situacion = 1";
    
        if ($tipo && $tipo !== 'TODAS') {
            $query .= " AND tf.tip_nombre = '" . $tipo . "'";
        }
    
        // Ordenar por ID para mantener un orden numérico consistente
        $query .= " ORDER BY f.fal_id ASC";
    
        return static::fetchArray($query);
    }
}