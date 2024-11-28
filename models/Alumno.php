<?php

namespace Model;

class Alumno extends ActiveRecord
{
    protected static $tabla = 'car_alumno';
    protected static $idTabla = 'alu_id';
    protected static $columnasDB = [
        'alu_id',
        'alu_catalogo',
        'alu_primer_nombre',
        'alu_segundo_nombre',
        'alu_primer_apellido',
        'alu_segundo_apellido',
        'alu_grado_id',
        'alu_rango_id',
        'alu_sexo',
        'alu_situacion'
    ];

    public $alu_id;
    public $alu_catalogo;
    public $alu_primer_nombre;
    public $alu_segundo_nombre;
    public $alu_primer_apellido;
    public $alu_segundo_apellido;
    public $alu_grado_id;
    public $alu_rango_id;
    public $alu_sexo;
    public $alu_situacion;

    // Constructor
    public function __construct($args = [])
    {
        $this->alu_id = $args['alu_id'] ?? null;
        $this->alu_catalogo = $args['alu_catalogo'] ?? null;
        $this->alu_primer_nombre = $args['alu_primer_nombre'] ?? null;
        $this->alu_segundo_nombre = $args['alu_segundo_nombre'] ?? null;
        $this->alu_primer_apellido = $args['alu_primer_apellido'] ?? null;
        $this->alu_segundo_apellido = $args['alu_segundo_apellido'] ?? null;
        $this->alu_grado_id = $args['alu_grado_id'] ?? null;
        $this->alu_rango_id = $args['alu_rango_id'] ?? null;
        $this->alu_sexo = $args['alu_sexo'] ?? null;
        $this->alu_situacion = $args['alu_situacion'] ?? 1;
    }

    // Validar si el alumno existe por el catálogo
    public static function existeAlumno($catalogo, $id = null)
    {
        $sql = "SELECT COUNT(*) as cuenta 
                FROM car_alumno 
                WHERE alu_catalogo = " . self::$db->quote($catalogo) . "
                AND alu_situacion = 1";

        if ($id) {
            $sql .= " AND alu_id != " . self::$db->quote($id);
        }

        $resultado = self::fetchFirst($sql);
        return (int)$resultado['cuenta'] > 0;
    }

    // Obtener todos los alumnos
    public static function obtenerAlumnos()
    {
        $sql = "SELECT 
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
                    a.alu_situacion = 1
                ORDER BY 
                    g.gra_orden ASC";

        return self::fetchArray($sql);
    }

    // Agregar este método a tu clase Alumno
    public static function obtenerSancionesAlumno($id)
    {
        $sql = "SELECT 
                a.alu_catalogo,
                a.alu_primer_nombre || ' ' || a.alu_segundo_nombre || ' ' || 
                a.alu_primer_apellido || ' ' || a.alu_segundo_apellido AS alumno_nombre,
                g.gra_nombre,
                r.ran_nombre,
                s.san_fecha_sancion,
                s.san_horas_arresto,
                s.san_demeritos,
                f.fal_descripcion,
                cf.cat_nombre AS categoria_falta,
                tf.tip_nombre AS tipo_falta
            FROM 
                car_alumno a
            JOIN 
                car_grado_academico g ON a.alu_grado_id = g.gra_id
            JOIN 
                car_rango r ON a.alu_rango_id = r.ran_id
            LEFT JOIN 
                car_sancion s ON a.alu_id = s.san_catalogo
            LEFT JOIN 
                car_falta f ON s.san_falta_id = f.fal_id
            LEFT JOIN 
                car_categoria_falta cf ON f.fal_categoria_id = cf.cat_id
            LEFT JOIN 
                car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id
            WHERE 
                a.alu_situacion = 1 
                AND a.alu_id = " . self::$db->quote($id) . "
            ORDER BY 
                s.san_fecha_sancion DESC";

        return self::fetchArray($sql);
    }
    // En lugar de eliminar físicamente, actualizamos la situación a 0
    public function eliminar()
    {
        $this->alu_situacion = 0;
        return $this->actualizar();
    }
}
