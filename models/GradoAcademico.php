<?php
namespace Model;

class GradoAcademico extends ActiveRecord {
    protected static $tabla = 'car_grado_academico';
    protected static $idTabla = 'gra_id';
    protected static $columnasDB = ['gra_id', 'gra_nombre', 'gra_orden'];

    public $gra_id;
    public $gra_nombre;
    public $gra_orden;


public static function obtenerGrado() {
    $query = "SELECT * FROM car_grado_academico where gra_situacion = 1";
    return static::fetchArray($query);  // Usamos fetchArray que ya maneja el sanitizado
}

}

