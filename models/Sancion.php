<?php

namespace Model;

class Sancion extends ActiveRecord {
    protected static $tabla = 'car_sancion';
    protected static $idTabla = 'san_id';
    protected static $columnasDB = [
        'san_id',
        'san_catalogo',
        'san_fecha_sancion',
        'san_falta_id',
        'san_instructor_ordena',
        'san_horas_arresto',
        'san_demeritos',
        'san_observaciones',
        'san_situacion'
    ];

    public $san_id;
    public $san_catalogo;
    public $san_fecha_sancion;
    public $san_falta_id;
    public $san_instructor_ordena;
    public $san_horas_arresto;
    public $san_demeritos;
    public $san_observaciones;
    public $san_situacion;

    public function __construct($args = []) {
        $this->san_id = $args['san_id'] ?? null;
        $this->san_catalogo = $args['san_catalogo'] ?? '';
        $this->san_fecha_sancion = $args['san_fecha_sancion'] ?? '';
        $this->san_falta_id = $args['san_falta_id'] ?? '';
        $this->san_instructor_ordena = $args['san_instructor_ordena'] ?? '';
        $this->san_horas_arresto = $args['san_horas_arresto'] ?? null;
        $this->san_demeritos = $args['san_demeritos'] ?? null;
        $this->san_observaciones = $args['san_observaciones'] ?? '';
        $this->san_situacion = $args['san_situacion'] ?? 1;
    }

    // Obtener una sanción específica con toda su información relacionada
    public static function obtenerSancion($id) {
        $query = "SELECT 
            s.*,
            TRIM(a.alu_primer_nombre || ' ' || 
                NVL(a.alu_segundo_nombre, '') || ' ' ||
                a.alu_primer_apellido || ' ' || 
                NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
            f.fal_descripcion,
            cf.cat_nombre AS categoria_falta,
            tf.tip_nombre AS tipo_falta,
            i.ins_catalogo,
            TRIM(m.per_nom1 || ' ' || 
                CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                m.per_ape1 || ' ' ||
                CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 || ' ' ELSE '' END
            ) AS instructor_nombre
        FROM car_sancion s
        JOIN car_alumno a ON s.san_catalogo = a.alu_catalogo
        JOIN car_falta f ON s.san_falta_id = f.fal_id
        JOIN car_categoria_falta cf ON f.fal_categoria_id = cf.cat_id
        JOIN car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id
        JOIN car_instructor i ON s.san_instructor_ordena = i.ins_id
        JOIN mper m ON i.ins_catalogo = m.per_catalogo
        WHERE s.san_id = " . self::$db->quote($id) . " 
        AND s.san_situacion = 1";

        return self::fetchFirst($query);
    }

    // Obtener todas las sanciones con información relacionada
    public static function obtenerSanciones() {
        $query = "SELECT 
    s.san_id,
    s.san_catalogo,
    s.san_fecha_sancion,
    s.san_horas_arresto,
    s.san_demeritos,
    a.alu_catalogo,
    TRIM(a.alu_primer_nombre || ' ' ||
         NVL(a.alu_segundo_nombre, '') || ' ' ||
         a.alu_primer_apellido || ' ' ||
         NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
    r.ran_nombre,
    g.gra_nombre,
    g.gra_orden, -- Agregamos el orden del grado
    f.fal_descripcion,
    cf.cat_nombre AS categoria_falta,
    tf.tip_nombre AS tipo_falta,
    (g_ins.gra_desc_ct || ' DE ' || a_ins.arm_desc_ct) AS grado_arma,
    TRIM(m.per_nom1 || ' ' ||
         CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
         m.per_ape1 || ' ' ||
         CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 || ' ' ELSE '' END ||
         CASE WHEN m.per_ape3 IS NOT NULL THEN m.per_ape3 ELSE '' END
    ) AS nombres_apellidos
FROM car_sancion s
LEFT JOIN car_alumno a ON s.san_catalogo = a.alu_id
LEFT JOIN car_rango r ON a.alu_rango_id = r.ran_id
LEFT JOIN car_grado_academico g ON a.alu_grado_id = g.gra_id
LEFT JOIN car_falta f ON s.san_falta_id = f.fal_id
LEFT JOIN car_categoria_falta cf ON f.fal_categoria_id = cf.cat_id
LEFT JOIN car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id
LEFT JOIN car_instructor i ON s.san_instructor_ordena = i.ins_id
LEFT JOIN mper m ON i.ins_catalogo = m.per_catalogo
LEFT JOIN grados g_ins ON m.per_grado = g_ins.gra_codigo
LEFT JOIN armas a_ins ON m.per_arma = a_ins.arm_codigo
WHERE s.san_situacion = 1
ORDER BY g.gra_orden DESC";

        return self::fetchArray($query);
    }

    // Obtener sanciones por alumno
    public static function obtenerSancionesPorAlumno($catalogo) {
        $query = "SELECT 
            s.*,
            f.fal_descripcion,
            cf.cat_nombre AS categoria_falta,
            tf.tip_nombre AS tipo_falta,
            i.ins_catalogo,
            TRIM(m.per_nom1 || ' ' || 
                CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                m.per_ape1 || ' ' ||
                CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 || ' ' ELSE '' END
            ) AS instructor_nombre
        FROM car_sancion s
        JOIN car_falta f ON s.san_falta_id = f.fal_id
        JOIN car_categoria_falta cf ON f.fal_categoria_id = cf.cat_id
        JOIN car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id
        JOIN car_instructor i ON s.san_instructor_ordena = i.ins_id
        JOIN mper m ON i.ins_catalogo = m.per_catalogo
        WHERE s.san_catalogo = " . self::$db->quote($catalogo) . "
        AND s.san_situacion = 1
        ORDER BY s.san_fecha_sancion DESC";

        return self::fetchArray($query);
    }

    // Obtener sanciones por instructor
    public static function obtenerSancionesPorInstructor($instructor_id) {
        $query = "SELECT 
            s.*,
            TRIM(a.alu_primer_nombre || ' ' || 
                NVL(a.alu_segundo_nombre, '') || ' ' ||
                a.alu_primer_apellido || ' ' || 
                NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
            f.fal_descripcion,
            cf.cat_nombre AS categoria_falta,
            tf.tip_nombre AS tipo_falta
        FROM car_sancion s
        JOIN car_alumno a ON s.san_catalogo = a.alu_catalogo
        JOIN car_falta f ON s.san_falta_id = f.fal_id
        JOIN car_categoria_falta cf ON f.fal_categoria_id = cf.cat_id
        JOIN car_tipo_falta tf ON cf.cat_tipo_id = tf.tip_id
        WHERE s.san_instructor_ordena = " . self::$db->quote($instructor_id) . "
        AND s.san_situacion = 1
        ORDER BY s.san_fecha_sancion DESC";

        return self::fetchArray($query);
    }

    // Verificar si ya existe una sanción similar
    public static function existeSancion($catalogo, $fecha, $falta_id, $id = null) {
        $query = "SELECT COUNT(*) as cuenta 
                FROM car_sancion 
                WHERE san_catalogo = " . self::$db->quote($catalogo) . "
                AND san_fecha_sancion = " . self::$db->quote($fecha) . "
                AND san_falta_id = " . self::$db->quote($falta_id) . "
                AND san_situacion = 1";

        if ($id) {
            $query .= " AND san_id != " . self::$db->quote($id);
        }

        $resultado = self::fetchFirst($query);
        return (int)$resultado['cuenta'] > 0;
    }

    // Validar los datos antes de guardar o actualizar
    public function validar() {
        if (empty($this->san_catalogo)) {
            self::setAlerta('error', 'El catálogo del alumno es obligatorio');
        }
        if (empty($this->san_fecha_sancion)) {
            self::setAlerta('error', 'La fecha de sanción es obligatoria');
        }
        if (empty($this->san_falta_id)) {
            self::setAlerta('error', 'Debe seleccionar una falta');
        }
        if (empty($this->san_instructor_ordena)) {
            self::setAlerta('error', 'Debe seleccionar un instructor');
        }

        return self::getAlertas();
    }

    // En lugar de eliminar físicamente, actualizamos la situación a 0
    public function eliminar() {
        $this->san_situacion = 0;
        return $this->actualizar();
    }
}