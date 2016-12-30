<?php

namespace app\helpers;
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
class Format
{
    public function curr($val)
    {
    	return is_numeric($val) ? '$ '.number_format($val,2,',','.') : $val;
    }

    public function prcnt($val)
    {
    	return is_numeric($val) ? number_format($val,4,'.','').' %' : $val;
    }

}
