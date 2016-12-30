<?php

namespace app\classes;

use app\classes\SqlFormatter as SQLF;
/**
 * SQL Formatter is a collection of utilities for debugging SQL queries.
 * It includes methods for formatting, syntax highlighting, removing comments, etc.
 *
 * @package    SqlFormatter
 * @author     Jeremy Dorn <jeremy@jeremydorn.com>
 * @author     Florin Patan <florinpatan@gmail.com>
 * @copyright  2013 Jeremy Dorn
 * @license    http://opensource.org/licenses/MIT
 * @link       http://github.com/jdorn/sql-formatter
 * @version    1.2.18
 */

class F {

    protected function processOutput($variable) {
        return is_bool($variable) ? ($variable ? "true" : "false") : $variable;
    }

    public static function p($variable, $delimitador = '', $die = false, $destino = false) {
        $pre = "<pre>".$delimitador;
        $post = $delimitador."</pre>";
        if ($destino) 
            return $pre.print_r(self::processOutput($variable), true).$post;
        else {
            echo $pre;
            print_r(self::processOutput($variable));
            echo $post;
        }
        if ($die) die();
    }

    public static function pd($variable, $delimitador = '') {
    	self::p($variable, $delimitador, true);
    }

    public static function pv($variable, $delimitador = '') {
    	return self::p($variable, $delimitador, false, true);
    }

    // print comentado (sirve para que al buscar 'F::p' no aparezcan los comentados '//F::p')
    public static function pc() { 
        return null;
    }

    public static function sql($variable, $delimitador = '', $die = false, $destino = false) {
        return self::p(SQLF::format($variable), $delimitador, $die, $destino);
    }  

}