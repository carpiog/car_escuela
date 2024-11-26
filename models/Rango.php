<?php

namespace Model;

class Rango extends ActiveRecord
{
    protected static $tabla = 'car_rango';
    protected static $columnasDB = ['ran_id', 'ran_nombre', 'ran_situacion'];

    public $ran_id;
    public $ran_nombre;
    public $ran_situacion;
}
