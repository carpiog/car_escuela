<?php

namespace Model;

use Exception;

class EstadisticasSancion extends ActiveRecord {
    // Constante para mapeo de meses
    private const MESES = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    protected static $tabla = 'v_sanciones_por_tipo'; 
    protected static $columnasDB = [
        'tipo',
        'categoria',
        'total_sanciones',
        'total_alumnos',
        'total_demeritos'
    ];


    public string $tipo;
    public string $categoria;
    public int $total_sanciones;
    public int $total_alumnos;
    public int $total_demeritos;

    public function __construct(array $args = []) {
        $this->tipo = $args['tipo'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
        $this->total_sanciones = $args['total_sanciones'] ?? 0;
        $this->total_alumnos = $args['total_alumnos'] ?? 0;
        $this->total_demeritos = $args['total_demeritos'] ?? 0;
    }
    

    public static function obtenerEstadisticasPorTipo(): array {
        try {
            $query = "SELECT * FROM v_sanciones_por_tipo ORDER BY total_sanciones DESC";
            $resultado = self::$db->query($query);
            return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Exception $e) {
            self::logError($e);
            return [];
        }
    }

    public static function obtenerEstadisticasPorGrado(): array {
        try {
            $query = "SELECT * FROM v_sanciones_por_grado ORDER BY total_sanciones DESC";
            $resultado = self::$db->query($query);
            return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Exception $e) {
            self::logError($e);
            return [];
        }
    }

    public static function obtenerTendenciasMensuales(): array {
        try {
            $query = "SELECT *, 
                      CASE mes " . self::generarCasosMeses() . " 
                      END as nombre_mes 
                      FROM v_tendencias_mensuales 
                      ORDER BY mes ASC";
            $resultado = self::$db->query($query);
            return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Exception $e) {
            self::logError($e);
            return [];
        }
    }

    private static function generarCasosMeses(): string {
        $casos = [];
        foreach (self::MESES as $numero => $nombre) {
            $casos[] = "WHEN $numero THEN '$nombre'";
        }
        return implode(' ', $casos) . " ELSE 'Desconocido' END";
    }

    public static function obtenerFaltasFrecuentes(): array {
        try {
            $query = "SELECT * FROM v_faltas_frecuentes ORDER BY total DESC LIMIT 5";
            $resultado = self::$db->query($query);
            return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Exception $e) {
            self::logError($e);
            return [];
        }
    }

    private static function logError(Exception $e): void {
        error_log('Error en EstadisticasSancion: ' . $e->getMessage());
        // Puedes añadir más lógica de logging si es necesario
    }
}