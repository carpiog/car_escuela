<?php

namespace Model;

class Instructor extends ActiveRecord
{
    protected static $tabla = 'car_instructor';
    protected static $idTabla = 'ins_id';
    protected static $columnasDB = ['ins_id', 'ins_catalogo', 'ins_situacion'];

    public $ins_id;
    public $ins_catalogo;
    public $ins_situacion;

    public function __construct($args = [])
    {
        $this->ins_id = $args['ins_id'] ?? null;
        $this->ins_catalogo = $args['ins_catalogo'] ?? null;
        $this->ins_situacion = $args['ins_situacion'] ?? 1;
    }

    public static function obtenerInstructorconQuery()
    {
        $sql = "SELECT 
                    ci.ins_id,
                    ci.ins_catalogo,
                    (g.gra_desc_ct || ' DE ' || a.arm_desc_ct) AS grado_arma,
                    TRIM(m.per_nom1 || ' ' || 
                         CASE WHEN m.per_nom2 IS NOT NULL THEN m.per_nom2 || ' ' ELSE '' END ||
                         m.per_ape1 || ' ' ||
                         CASE WHEN m.per_ape2 IS NOT NULL THEN m.per_ape2 || ' ' ELSE '' END ||
                         CASE WHEN m.per_ape3 IS NOT NULL THEN m.per_ape3 ELSE '' END
                    ) AS nombres_apellidos,
                    TRIM(org.org_plaza_desc) || ' - ' || TRIM(d.dep_desc_lg) AS puesto_dependencia
                FROM 
                    car_instructor ci
                JOIN 
                    mper m ON ci.ins_catalogo = m.per_catalogo
                JOIN 
                    morg org ON m.per_plaza = org.org_plaza
                JOIN 
                    mdep d ON org.org_dependencia = d.dep_llave
                JOIN 
                    grados g ON m.per_grado = g.gra_codigo
                JOIN 
                    armas a ON m.per_arma = a.arm_codigo
                WHERE 
                    ci.ins_situacion = 1
                ORDER BY 
                    ci.ins_id";
        
        return self::fetchArray($sql);
    }

    // Validar si el catálogo existe en la tabla mper
    public static function validarCatalogo($catalogo)
    {
        $sql = "SELECT COUNT(*) as cuenta 
                FROM mper 
                WHERE per_catalogo = " . self::$db->quote($catalogo);
        
        $resultado = self::fetchFirst($sql);
        return (int)$resultado['cuenta'] > 0;
    }

    // Validar si el instructor ya existe
    public static function existeInstructor($catalogo, $id = null)
    {
        $sql = "SELECT COUNT(*) as cuenta 
                FROM car_instructor 
                WHERE ins_catalogo = " . self::$db->quote($catalogo) . "
                AND ins_situacion = 1";
        
        if ($id) {
            $sql .= " AND ins_id != " . self::$db->quote($id);
        }
        
        $resultado = self::fetchFirst($sql);
        return (int)$resultado['cuenta'] > 0;
    }

    // En lugar de eliminar físicamente, actualizamos la situación a 0
    public function eliminar()
    {
        $this->ins_situacion = 0;
        return $this->actualizar();
    }
}