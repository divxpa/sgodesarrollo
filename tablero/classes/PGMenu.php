<?php

namespace app\classes;

use Yii;
use app\helpers\Format;
use app\classes\F;

class PGMenu {

    /**
    * Devuelve el Arbol del Menu
    */
    public function make($idRol = 0) {

        $idRol = 2; //>> Puesto para solucionar rápido el tema del menú, resolver más prolijo cuando se tengan requerimientos

        if ($idRol) {
            $menuData = Yii::$app->db->createCommand(self::sqlMenu($idRol))->queryAll();
            $menuItems = self::armadoArbol($menuData);
        } else $menuItems = [];

        return $menuItems;
    }

    protected function filterBy($lista, $col, $id)
    {
        return array_filter($lista, function($item) use ($col, $id) 
        {
            return ($item[$col] == $id);
        });
    }

    protected function filterByParentId($lista, $id) 
    {
        $nombreCampoPadre = 'IdMenuPadre';
        return self::filterBy($lista, $nombreCampoPadre, $id);
    }

    protected function modificacionesNodo($item) {
        // ...
        return $item;
    }

    protected function modificacionesNodoPadre($item) 
    {
        $item['url'] = 'javascript:void(0)' ;
        return self::modificacionesNodo($item);
    }

    protected function modificacionesNodoLink($item) 
    {
        $item['url'] = [$item['url']];
        return self::modificacionesNodo($item);
    }


    protected function armadoArbol($contenido) 
    {
        $raiz = ['id' => 0, 'url' =>''];
        $arbol = self::armadoItemArbol($raiz, $contenido);
        return $arbol ? $arbol['items'] : [];
    }

	/**  
     * Procedimiento recursivo para el armado del arbol:
     */
	public function armadoItemArbol($item, $contenido) {
        if ($item['url'] == '') {
            $hijos = self::filterByParentId($contenido, $item['id']);
            $hijosActivos = [];
            foreach ($hijos as $hijo) {
                $hijoProcesado = self::armadoItemArbol($hijo, $contenido);
                if ($hijoProcesado != false) $hijosActivos[] = $hijoProcesado;
            }
            if (sizeof($hijosActivos) != 0) {
                $item['items'] = $hijosActivos;

                // Modificaciones a los Nodos PADRE
                return self::modificacionesNodoPadre($item);
            } else
                return false;
        } else {
            // Filtrado hecho ya desde la consulta SQL
            // if (isset($item['activo']) && $item['activo'] == 0) return false;

            // Modificaciones a los Nodos HIJO
            return self::modificacionesNodoLink($item);
        }
        return $item;
    }   


	/**  
     * Consulta que trae los items según el rol dado:
     */
    protected function sqlMenu($idRol)
    {	
	    $sql = "SELECT
	              m.IdMenu AS id,
	              m.Nombre AS label,
	              -- m.IconClass AS 'icon-class', 
	              IFNULL(m.IdMenuPadre,0) AS IdMenuPadre, 
	              a.Url AS url
	            FROM menutablero AS m
	            LEFT JOIN acciontablero AS a ON m.IdAccion = a.IdAccion
	            LEFT JOIN rolacciontablero AS ra ON a.IdAccion = ra.IdAccion 
	            WHERE
	              (m.Activo = 1) AND ((m.IdAccion IS NULL) OR (a.Activo = 1 AND ra.IdRol = $idRol))
	            ORDER BY 
	              m.IdMenuPadre, -- Primero los Padres (IdMenuPadre = NULL) 
	              m.Orden, -- Ordenados s/ orden asignado
	              m.Nombre -- En caso de orden repetido, ordenar x nombre";

    	return $sql;
    }

}