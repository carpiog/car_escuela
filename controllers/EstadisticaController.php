<?php

namespace Controllers;

use Model\EstadisticasSancion;
use MVC\Router;
use Exception;

class EstadisticasController {

    public static function index(Router $router): void {
        // Cargar datos iniciales para la vista
        $tiposSanciones = EstadisticasSancion::obtenerEstadisticasPorTipo();
        $gradosSanciones = EstadisticasSancion::obtenerEstadisticasPorGrado();
        $tendenciasMensuales = EstadisticasSancion::obtenerTendenciasMensuales();
        $faltasFrecuentes = EstadisticasSancion::obtenerFaltasFrecuentes();

        $router->render('estadisticas/index', [
            'tiposSanciones' => $tiposSanciones,
            'gradosSanciones' => $gradosSanciones,
            'tendenciasMensuales' => $tendenciasMensuales,
            'faltasFrecuentes' => $faltasFrecuentes
        ]);
    }

    private static function jsonResponse($datos, bool $error = false, string $mensaje = ''): void {
        header('Content-Type: application/json');
        echo json_encode([
            'error' => $error,
            'mensaje' => $mensaje,
            'datos' => $datos
        ]);
        exit;
    }

    public static function estadisticasGeneralesAPI(): void {
        try {
            $datosGenerales = [
                'tiposSanciones' => EstadisticasSancion::obtenerEstadisticasPorTipo(),
                'gradosSanciones' => EstadisticasSancion::obtenerEstadisticasPorGrado(),
                'tendenciasMensuales' => EstadisticasSancion::obtenerTendenciasMensuales(),
                'faltasFrecuentes' => EstadisticasSancion::obtenerFaltasFrecuentes()
            ];
            self::jsonResponse($datosGenerales);
        } catch (Exception $e) {
            self::jsonResponse([], true, $e->getMessage());
        }
    }

    public static function tiposAPI(): void {
        try {
            $datos = EstadisticasSancion::obtenerEstadisticasPorTipo();
            self::jsonResponse($datos);
        } catch (Exception $e) {
            self::jsonResponse([], true, $e->getMessage());
        }
    }

    public static function gradosAPI(): void {
        try {
            $datos = EstadisticasSancion::obtenerEstadisticasPorGrado();
            self::jsonResponse($datos);
        } catch (Exception $e) {
            self::jsonResponse([], true, $e->getMessage());
        }
    }

    public static function tendenciasAPI(): void {
        try {
            $datos = EstadisticasSancion::obtenerTendenciasMensuales();
            self::jsonResponse($datos);
        } catch (Exception $e) {
            self::jsonResponse([], true, $e->getMessage());
        }
    }

    public static function faltasAPI(): void {
        try {
            $datos = EstadisticasSancion::obtenerFaltasFrecuentes();
            self::jsonResponse($datos);
        } catch (Exception $e) {
            self::jsonResponse([], true, $e->getMessage());
        }
    }

    public static function exportarCSV(): void {
        try {
            // Obtener todos los datos
            $tiposSanciones = EstadisticasSancion::obtenerEstadisticasPorTipo();
            $gradosSanciones = EstadisticasSancion::obtenerEstadisticasPorGrado();
            $tendenciasMensuales = EstadisticasSancion::obtenerTendenciasMensuales();
            $faltasFrecuentes = EstadisticasSancion::obtenerFaltasFrecuentes();

            // Generar archivo CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=estadisticas_sanciones.csv');
            $output = fopen('php://output', 'w');

            // Escribir encabezados y datos para cada conjunto
            self::escribirSeccionCSV($output, 'Sanciones por Tipo', $tiposSanciones);
            self::escribirSeccionCSV($output, 'Sanciones por Grado', $gradosSanciones);
            self::escribirSeccionCSV($output, 'Tendencias Mensuales', $tendenciasMensuales);
            self::escribirSeccionCSV($output, 'Faltas Frecuentes', $faltasFrecuentes);

            fclose($output);
            exit;
        } catch (Exception $e) {
            // Manejar error de exportación
            header('Content-Type: application/json');
            echo json_encode([
                'error' => true, 
                'mensaje' => 'Error al exportar: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    private static function escribirSeccionCSV($output, string $titulo, array $datos): void {
        // Escribir título de sección
        fputcsv($output, [$titulo]);
        
        // Si hay datos, escribir encabezados
        if (!empty($datos)) {
            fputcsv($output, array_keys($datos[0]));
            
            // Escribir datos
            foreach ($datos as $fila) {
                fputcsv($output, $fila);
            }
        }
        
        // Línea en blanco entre secciones
        fputcsv($output, []);
    }

    /**
     * Generar informe detallado
     */
    public static function generarInforme(): void {
        try {
            $informe = [
                'resumen' => [
                    'total_sanciones' => array_sum(array_column(
                        EstadisticasSancion::obtenerEstadisticasPorTipo(), 
                        'total_sanciones'
                    )),
                    'total_alumnos_sancionados' => array_sum(array_column(
                        EstadisticasSancion::obtenerEstadisticasPorTipo(), 
                        'total_alumnos'
                    ))
                ],
                'tipos_sanciones' => EstadisticasSancion::obtenerEstadisticasPorTipo(),
                'sanciones_por_grado' => EstadisticasSancion::obtenerEstadisticasPorGrado(),
                'tendencias_mensuales' => EstadisticasSancion::obtenerTendenciasMensuales(),
                'faltas_frecuentes' => EstadisticasSancion::obtenerFaltasFrecuentes()
            ];

            // Renderizar vista de informe o devolver JSON
            header('Content-Type: application/json');
            echo json_encode($informe);
            exit;
        } catch (Exception $e) {
            self::jsonResponse([], true, $e->getMessage());
        }
    }
}