<?php

namespace Model;

class DemeritoAcumulado extends ActiveRecord {
    protected static $tabla = 'v_demeritos_acumulados';
    protected static $columnasDB = [
        'alu_id',
        'alu_catalogo',
        'gra_nombre',
        'ran_nombre',
        'alumno_nombre',
        'ciclo_escolar',
        'total_sanciones',
        'demeritos_acumulados',
        'conducta'
    ];

    public $alu_id;
    public $alu_catalogo;
    public $gra_nombre;
    public $ran_nombre;
    public $alumno_nombre;
    public $ciclo_escolar;
    public $total_sanciones;
    public $demeritos_acumulados;
    public $conducta;

    public function __construct($args = []) {
        $this->alu_id = $args['alu_id'] ?? null;
        $this->alu_catalogo = $args['alu_catalogo'] ?? '';
        $this->gra_nombre = $args['gra_nombre'] ?? '';
        $this->ran_nombre = $args['ran_nombre'] ?? '';
        $this->alumno_nombre = $args['alumno_nombre'] ?? '';
        $this->ciclo_escolar = $args['ciclo_escolar'] ?? '';
        $this->total_sanciones = $args['total_sanciones'] ?? 0;
        $this->demeritos_acumulados = $args['demeritos_acumulados'] ?? 0;
        $this->conducta = $args['conducta'] ?? '';
    }

    // Método para obtener todos los registros ordenados por grado y conducta
    public static function obtenerTodos() {
        $query = "SELECT * FROM v_demeritos_acumulados 
                  ORDER BY 
                    CASE 
                        WHEN gra_nombre = 'PRIMERO BASICO' THEN 1
                        WHEN gra_nombre = 'SEGUNDO BASICO' THEN 2
                        WHEN gra_nombre = 'TERCERO BASICO' THEN 3
                        WHEN gra_nombre = 'CUARTO BACHILLERATO' THEN 4
                        WHEN gra_nombre = 'QUINTO BACHILLERATO' THEN 5
                    END, 
                    NVL(demeritos_acumulados, 0) DESC";
        return static::fetchArray($query);
    }

    // Método para obtener alumnos en riesgo (conducta DEFICIENTE o MALA)
    public static function obtenerAlumnosEnRiesgo() {
        $query = "SELECT * FROM v_demeritos_acumulados 
                  WHERE conducta IN ('DEFICIENTE', 'MALA') 
                  ORDER BY NVL(demeritos_acumulados, 0) DESC";
        return static::fetchArray($query);  // Usamos fetchArray que ya maneja el sanitizado
    }

    // Método para obtener estadísticas agrupadas por grado
    public static function obtenerEstadisticasPorGrado() {
        $query = "SELECT 
                    gra_nombre,
                    COUNT(*) as total_alumnos,
                    SUM(NVL(total_sanciones, 0)) as total_sanciones,
                    ROUND(AVG(NVL(demeritos_acumulados, 0)), 2) as promedio_demeritos,
                    COUNT(CASE WHEN conducta = 'EXCELENTE' THEN 1 END) as excelente,
                    COUNT(CASE WHEN conducta = 'MUY BUENA' THEN 1 END) as muy_buena,
                    COUNT(CASE WHEN conducta = 'BUENA' THEN 1 END) as buena,
                    COUNT(CASE WHEN conducta = 'REGULAR' THEN 1 END) as regular,
                    COUNT(CASE WHEN conducta = 'DEFICIENTE' THEN 1 END) as deficiente,
                    COUNT(CASE WHEN conducta = 'MALA' THEN 1 END) as mala
                 FROM v_demeritos_acumulados 
                 GROUP BY gra_nombre
                 ORDER BY 
                    CASE 
                        WHEN gra_nombre = 'PRIMERO BASICO' THEN 1
                        WHEN gra_nombre = 'SEGUNDO BASICO' THEN 2
                        WHEN gra_nombre = 'TERCERO BASICO' THEN 3
                        WHEN gra_nombre = 'CUARTO BACHILLERATO' THEN 4
                        WHEN gra_nombre = 'QUINTO BACHILLERATO' THEN 5
                    END";
        return static::fetchArray($query);  // Usamos fetchArray que ya maneja el sanitizado
    }

    // Método para obtener un alumno específico por su ID
    public static function obtenerPorID($id) {
        // Usamos el método where de ActiveRecord, que manejará correctamente la sanitización
        $resultado = static::where('alu_id', $id);
        return empty($resultado) ? null : $resultado[0];  // Retorna el primer resultado o null si no hay coincidencias
    }
}
