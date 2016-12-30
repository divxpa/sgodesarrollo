<?php

namespace app\classes;

use app\helpers\Format;
use app\classes\F;

class CustomGridColumn {

	public function append($column, $params = "falso")
	{
        //if (is_string($column)) $column = ['attribute' => $attribute];
		if (is_array($params)) return array_merge_recursive($column, $params);

        return $column;	
   	}

	public function num($attribute, $params = "falso")
	{
		$column = [
            'attribute' => $attribute,
            'noWrap' => true,
            'contentOptions' => ['style' => ['text-align' => 'right']],
        ];

		return self::append($column, $params);	
	}

	public function curr($attribute, $params = "falso") 
	{
		$column = self::append(self::num($attribute), 
			[
	            'value' => function($data) use ($attribute) {
	                return Format::curr($data[$attribute]);
	            },
	        ]
        );

		return self::append($column, $params);	
	}

	public function prcnt($attribute, $params = "falso") 
	{
		$column = self::append(self::num($attribute), 
			[
	            'value' => function($data) use ($attribute) {
	                return Format::prcnt($data[$attribute]);
	            },
	        ]
        );

		return self::append($column, $params);	
	}
	
}