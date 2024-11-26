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

    // Estados del cumplimiento
    const PENDIENTE = 'P';    // Aún no inicia el cumplimiento
    const CUMPLIENDO = 'E';   // Está cumpliendo el arresto
    const CUMPLIO = 'C';      // Completó todas las horas
    const NO_CUMPLIO = 'N';   // No se presentó o no cumplió
    const TRASLADADO = 'T';   // Trasladado a otro fin de semana

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
        $this->cum_fecha = $args['cum_fecha'] ?? date('Y-m-d');
        $this->cum_estado = $args['cum_estado'] ?? 'P';
        $this->cum_horas_cumplidas = $args['cum_horas_cumplidas'] ?? 0;
        $this->cum_horas_pendientes = $args['cum_horas_pendientes'] ?? 0;
        $this->cum_fin_semana_inicio = $args['cum_fin_semana_inicio'] ?? null;
        $this->cum_fin_semana_siguiente = $args['cum_fin_semana_siguiente'] ?? null;
        $this->cum_instructor_supervisa = $args['cum_instructor_supervisa'] ?? '';
        $this->cum_observaciones = $args['cum_observaciones'] ?? '';
        $this->cum_situacion = $args['cum_situacion'] ?? 1;
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
        if ($this->cum_horas_cumplidas <= 0) {
            self::setAlerta('error', 'Las horas cumplidas deben ser mayor a 0');
        }
        if ($this->cum_horas_cumplidas > 4) {
            self::setAlerta('error', 'No se pueden cumplir más de 4 horas por día');
        }

        return self::getAlertas();
    }

    public static function obtenerPendientes() {
        $query = "SELECT 
            c.*,
            s.san_horas_arresto,
            a.alu_catalogo,
            TRIM(a.alu_primer_nombre || ' ' || 
                NVL(a.alu_segundo_nombre, '') || ' ' ||
                a.alu_primer_apellido || ' ' || 
                NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
            r.ran_nombre,
            g.gra_nombre,
            g.gra_orden,
            (g_ins.gra_desc_ct || ' DE ' || a_ins.arm_desc_ct) AS grado_arma_instructor,
            TRIM(m.per_nom1 || ' ' || 
                CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                m.per_ape1 || ' ' ||
                CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 || ' ' ELSE '' END
            ) AS instructor_nombre,
            CASE c.cum_estado 
                WHEN 'P' THEN 'Pendiente'
                WHEN 'E' THEN 'Cumpliendo'
                WHEN 'C' THEN 'Cumplió'
                WHEN 'N' THEN 'No Cumplió'
                WHEN 'T' THEN 'Trasladado'
            END AS estado_texto
        FROM car_cumplimiento_arresto c
        JOIN car_sancion s ON c.cum_sancion_id = s.san_id
        JOIN car_alumno a ON s.san_catalogo = a.alu_id
        JOIN car_rango r ON a.alu_rango_id = r.ran_id
        JOIN car_grado_academico g ON a.alu_grado_id = g.gra_id
        JOIN car_instructor i ON c.cum_instructor_supervisa = i.ins_id
        JOIN mper m ON i.ins_catalogo = m.per_catalogo
        LEFT JOIN grados g_ins ON m.per_grado = g_ins.gra_codigo
        LEFT JOIN armas a_ins ON m.per_arma = a_ins.arm_codigo
        WHERE c.cum_estado IN ('P', 'E', 'T')
        AND c.cum_situacion = 1
        AND s.san_situacion = 1
        ORDER BY c.cum_fin_semana_inicio ASC, g.gra_orden DESC";

        return self::fetchArray($query);
    }

    public static function obtenerCumplimiento($id) {
        $query = "SELECT 
            c.*,
            s.san_horas_arresto,
            TRIM(a.alu_primer_nombre || ' ' || 
                NVL(a.alu_segundo_nombre, '') || ' ' ||
                a.alu_primer_apellido || ' ' || 
                NVL(a.alu_segundo_apellido, '')) AS alumno_nombre,
            (g_ins.gra_desc_ct || ' DE ' || a_ins.arm_desc_ct) AS grado_arma_instructor,
            TRIM(m.per_nom1 || ' ' || 
                CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                m.per_ape1 || ' ' ||
                CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 || ' ' ELSE '' END
            ) AS instructor_nombre
        FROM car_cumplimiento_arresto c
        JOIN car_sancion s ON c.cum_sancion_id = s.san_id
        JOIN car_alumno a ON s.san_catalogo = a.alu_id
        JOIN car_instructor i ON c.cum_instructor_supervisa = i.ins_id
        JOIN mper m ON i.ins_catalogo = m.per_catalogo
        LEFT JOIN grados g_ins ON m.per_grado = g_ins.gra_codigo
        LEFT JOIN armas a_ins ON m.per_arma = a_ins.arm_codigo
        WHERE c.cum_id = " . self::$db->quote($id) . " 
        AND c.cum_situacion = 1";

        return self::fetchFirst($query);
    }

    public function registrarCumplimiento() {
        $sancion = Sancion::find($this->cum_sancion_id);
        if (!$sancion) {
            self::setAlerta('error', 'Sanción no encontrada');
            return false;
        }

        if ((int)$this->cum_horas_cumplidas > 4) {
            self::setAlerta('error', 'No se pueden cumplir más de 4 horas por día');
            return false;
        }

        $cumplimientosAnteriores = self::where('cum_sancion_id', $this->cum_sancion_id);
        $horasCumplidasTotal = 0;
        
        foreach ($cumplimientosAnteriores as $cumplimiento) {
            if (isset($cumplimiento->cum_horas_cumplidas)) {
                $horasCumplidasTotal += (int)$cumplimiento->cum_horas_cumplidas;
            }
        }
        
        $horasCumplidasTotal += (int)$this->cum_horas_cumplidas;
        
        if ($horasCumplidasTotal > (int)$sancion->san_horas_arresto) {
            self::setAlerta('error', 'Las horas cumplidas superan las horas de arresto asignadas');
            return false;
        }

        $this->cum_horas_pendientes = (int)$sancion->san_horas_arresto - $horasCumplidasTotal;
        
        if ($this->cum_horas_pendientes === 0) {
            $this->cum_estado = self::CUMPLIO;
        } else if ($horasCumplidasTotal === 0) {
            $this->cum_estado = self::PENDIENTE;
        } else if ($horasCumplidasTotal < (int)$sancion->san_horas_arresto) {
            $this->cum_estado = self::CUMPLIENDO;
        }

        if (!$this->cum_fin_semana_inicio) {
            $this->cum_fin_semana_inicio = $this->obtenerProximoSabado();
            if ($this->cum_horas_pendientes > 0) {
                $this->cum_fin_semana_siguiente = $this->obtenerProximoSabado($this->cum_fin_semana_inicio);
            }
        }

        return $this->guardar();
    }

    public function marcarNoCumplio($observaciones = '') {
        if (!empty($observaciones)) {
            $this->cum_observaciones = $observaciones;
        }
        $this->cum_estado = self::NO_CUMPLIO;
        return $this->guardar();
    }

    public function trasladar($nuevaFecha, $observaciones = '') {
        $this->cum_estado = self::TRASLADADO;
        $this->cum_fin_semana_inicio = $this->obtenerProximoSabado($nuevaFecha);
        $this->cum_fin_semana_siguiente = $this->obtenerProximoSabado($this->cum_fin_semana_inicio);
        if (!empty($observaciones)) {
            $this->cum_observaciones = $observaciones;
        }
        return $this->guardar();
    }

    private function obtenerProximoSabado($fecha = null) {
        $fecha = $fecha ?? date('Y-m-d');
        $timestamp = strtotime($fecha);
        $diaSemana = date('w', $timestamp);
        $diasHastaSabado = (6 - $diaSemana + 7) % 7;
        return date('Y-m-d', strtotime("+{$diasHastaSabado} days", $timestamp));
    }

    public static function obtenerEstadisticas($sancionId) {
        $query = "SELECT 
            SUM(cum_horas_cumplidas) as total_horas_cumplidas,
            COUNT(*) as total_registros,
            MAX(cum_fecha) as ultima_fecha
        FROM car_cumplimiento_arresto 
        WHERE cum_sancion_id = " . self::$db->quote($sancionId) . "
        AND cum_situacion = 1";

        return self::fetchFirst($query);
    }
}