<?php

namespace app\classes;

use Yii;

use yii\data\SqlDataProvider;

class DataProviderFromSQL {

	const PAGE_SIZE = 10;


	public function countRows($sql)
	{
	    $regexp_select = "/#select_begin[\s\S]*#select_end/";
	    $sqlCount = preg_match($regexp_select, $sql) ?
	        preg_replace($regexp_select, "count(*)", $sql) :
	        "SELECT COUNT(*) FROM ($sql) AS t";

	    return Yii::$app->db->createCommand($sqlCount)->queryScalar();
	}


	public function create($sql, $sortColumn = false, $pageSize = self::PAGE_SIZE)
	{	
	    return new SqlDataProvider([
	        'sql' => $sortColumn ? "SELECT * FROM ($sql) AS t ORDER BY $sortColumn" : $sql,
	        'totalCount' => self::countRows($sql),
	        'pagination' => ['pageSize' => $pageSize],
	    ]);
	} 


	public function getColumnNames($sql)
	{
		$firstRecord = Yii::$app->db->createCommand("$sql LIMIT 1")->queryOne();
		return $firstRecord ? array_keys($firstRecord) : [];
	}


}