<?php
namespace Model;

class GradoAcademico extends ActiveRecord {
    protected static $tabla = 'car_grado_academico';
    protected static $idTabla = 'gra_id';
    protected static $columnasDB = ['gra_id', 'gra_nombre', 'gra_orden'];

    public $gra_id;
    public $gra_nombre;
    public $gra_orden;
}