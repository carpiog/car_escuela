<?php

namespace Model;

class CumplimientoArresto extends ActiveRecord {
    protected static $tabla = 'car_cumplimiento_arresto';
    protected static $idTabla = 'cum_id';
    protected static $columnasDB = [
        'cum_id',
        'cum_sancion_id',
        'cum_fecha',
        'cum_estado',
        'cum_horas_cumplidas',
        'cum_horas_pendientes',
        'cum_fin_semana_inicio',
        'cum_fin_semana_siguiente',
        'cum_instructor_supervisa',
        'cum_observaciones',
        'cum_situacion'
    ];

    public $cum_id;
    public $cum_sancion_id;
    public $cum_fecha;
    public $cum_estado;
    public $cum_horas_cumplidas;
    public $cum_horas_pendientes;
    public $cum_fin_semana_inicio;
    public $cum_fin_semana_siguiente;
    public $cum_instructor_supervisa;
    public $cum_observaciones;
    public $cum_situacion;

    public function __construct($args = []) {
        $this->cum_id = $args['cum_id'] ?? null;
        $this->cum_sancion_id = $args['cum_sancion_id'] ?? '';
        $this->cum_fecha = $args['cum_fecha'] ?? '';
        $this->cum_estado = $args['cum_estado'] ?? 'P';
        $this->cum_horas_cumplidas = $args['cum_horas_cumplidas'] ?? 0;
        $this->cum_horas_pendientes = $args['cum_horas_pendientes'] ?? 0;
        $this->cum_fin_semana_inicio = $args['cum_fin_semana_inicio'] ?? null;
        $this->cum_fin_semana_siguiente = $args['cum_fin_semana_siguiente'] ?? null;
        $this->cum_instructor_supervisa = $args['cum_instructor_supervisa'] ?? '';
        $this->cum_observaciones = $args['cum_observaciones'] ?? '';
        $this->cum_situacion = $args['cum_situacion'] ?? 1;
    }

    public static function obtenerCumplimiento($id) {
        $query = "SELECT 
            ca.*,
            s.san_horas_arresto,
            TRIM(a.alu_primer_nombre || ' ' || 
                NVL(a.alu_segundo_nombre, '') || ' ' ||
                a.alu_primer_apellido || ' ' || 
                NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
            r.ran_nombre,
            g.gra_nombre,
            f.fal_descripcion,
            (g_ins.gra_desc_ct || ' DE ' || a_ins.arm_desc_ct) AS grado_arma_supervisa,
            TRIM(m.per_nom1 || ' ' || 
                CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                m.per_ape1 || ' ' ||
                CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 ELSE '' END
            ) AS instructor_supervisa_nombre
        FROM car_cumplimiento_arresto ca
        JOIN car_sancion s ON ca.cum_sancion_id = s.san_id
        JOIN car_alumno a ON s.san_catalogo = a.alu_id
        JOIN car_rango r ON a.alu_rango_id = r.ran_id
        JOIN car_grado_academico g ON a.alu_grado_id = g.gra_id
        JOIN car_falta f ON s.san_falta_id = f.fal_id
        JOIN car_instructor i ON ca.cum_instructor_supervisa = i.ins_id
        JOIN mper m ON i.ins_catalogo = m.per_catalogo
        JOIN grados g_ins ON m.per_grado = g_ins.gra_codigo
        JOIN armas a_ins ON m.per_arma = a_ins.arm_codigo
        WHERE ca.cum_id = " . self::$db->quote($id) . "
        AND ca.cum_situacion = 1";

        return self::fetchFirst($query);
    }

    public static function obtenerCumplimientos() {
        $query = "SELECT 
            ca.*,
            s.san_horas_arresto,
            TRIM(a.alu_primer_nombre || ' ' || 
                NVL(a.alu_segundo_nombre, '') || ' ' ||
                a.alu_primer_apellido || ' ' || 
                NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
            r.ran_nombre,
            g.gra_nombre,
            f.fal_descripcion,
            (g_ins.gra_desc_ct || ' DE ' || a_ins.arm_desc_ct) AS grado_arma_supervisa,
            TRIM(m.per_nom1 || ' ' || 
                CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                m.per_ape1 || ' ' ||
                CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 ELSE '' END
            ) AS instructor_supervisa_nombre
        FROM car_cumplimiento_arresto ca
        JOIN car_sancion s ON ca.cum_sancion_id = s.san_id
        JOIN car_alumno a ON s.san_catalogo = a.alu_id
        JOIN car_rango r ON a.alu_rango_id = r.ran_id
        JOIN car_grado_academico g ON a.alu_grado_id = g.gra_id
        JOIN car_falta f ON s.san_falta_id = f.fal_id
        JOIN car_instructor i ON ca.cum_instructor_supervisa = i.ins_id
        JOIN mper m ON i.ins_catalogo = m.per_catalogo
        JOIN grados g_ins ON m.per_grado = g_ins.gra_codigo
        JOIN armas a_ins ON m.per_arma = a_ins.arm_codigo
        WHERE ca.cum_situacion = 1
        ORDER BY ca.cum_fecha DESC";

        return self::fetchArray($query);
    }

    public function validar() {
        if (empty($this->cum_sancion_id)) {
            self::setAlerta('error', 'La sanción es obligatoria');
        }
        if (empty($this->cum_fecha)) {
            self::setAlerta('error', 'La fecha es obligatoria');
        }
        if (empty($this->cum_instructor_supervisa)) {
            self::setAlerta('error', 'El instructor supervisor es obligatorio');
        }
        if ($this->cum_horas_cumplidas < 0) {
            self::setAlerta('error', 'Las horas cumplidas no pueden ser negativas');
        }
        return self::getAlertas();
    }

    public function eliminar() {
        $this->cum_situacion = 0;
        return $this->actualizar();
    }
}