<?php
class BaseController extends \Phalcon\Mvc\Controller
{
	/**
	 *设置数据库连接
	 */

	/*
	 * check the data value.
	 *
	 * @param unknown_type $value
	 * @return unknown
	 */
	function check_input($value)
	{
		// 去除斜杠
		if (get_magic_quotes_gpc())
	   {
	  	$value = stripslashes($value);
	   }
		// 如果不是数字则加引号
		if (!is_numeric($value))
		  {
		  $value = "'" . mysql_real_escape_string($value) . "'";
		  }
		return $value;
	}
	 /**
	  * Save the value to db.
	  * @param String $TableName  the table name.
	  * @param String array $ColumnValues  the table column and value Map.
	  */
	 function saveRow($TableName , $ColumnValues,$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	    $sql = "";
	    $insertColumn = "";
	    $insertValue = "";
	    $TableName = dbGetPrefixTableNames($TableName);
	    foreach( $ColumnValues as $key=>$value  ){
	       $insertColumn = $insertColumn.$key.",";
	       if( $value == 'now()' ){
	          $insertValue = $insertValue.$value.",";
	       }else{
	       	  $value = $this->check_input($value);
	          $insertValue = $insertValue.$value.",";
	       }
	    }
	    $insertColumn = substr($insertColumn,0,strlen($insertColumn)-1);
	    $insertValue = substr($insertValue,0,strlen($insertValue)-1);
	    $sql = "insert into ".$TableName." (".$insertColumn.") values( ".$insertValue.")";
	    log_message('info','sql:'.$sql);
	 	$this->test($sql);
	 	$DB->Execute($sql);
	 	return $DB->Insert_ID();
	 }
	 /**
	  * Update table
	  * @param String        $TableName  the table name.
	  * @param String array  $columnAndValue  the table column and value Map.
	  * @param String        $pkName   the primary key name.
	  * @param String        $pkValue  the primary key value.
	  */
	 function updateRowByPk($TableName,$columnAndValue,$pkName,$pkValue,$DB=""){
	 	$condtion = array($pkName=>$pkValue);
	 	$this->updateRow($TableName,$columnAndValue,$condtion,$DB);
	 }
	 /**
	  * Update table
	  * @param String        $TableName  the table name.
	  * @param String array  $columnAndValue  the table column and value Map.
	  * @param String array  $condition   the primary key name.
	  * @param db ojbect     $DB          the sqllite private database visit object
	  */
	 function updateRow($TableName,$columnAndValue,$condition="",$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$TableName = dbGetPrefixTableNames($TableName);
	 	$sql = 'update '.$TableName.' set ';
	 	$update ='';
		foreach( $columnAndValue as $key=>$value ){
		    if( $value == 'now()' ){
	         //$value = $this->check_input($value);
			$update = $update.$key.'='.$value .',';
	       }else{
	       	  $value = $this->check_input($value);
			$update = $update.$key.'='.$value .',';
	       }
		}
		$update = substr($update,0,strlen($update)-1);
		$sql = $sql.$update;
		if( empty($condition)){
			$sql = $sql;
		}else{
			foreach($condition as $key=>$value){
				$value = $this->check_input($value);
				$conditionString = $key.'='.''.$value .''.',';
			}
			$conditionString = substr($conditionString,0,strlen($conditionString)-1);
			$sql = $sql." where ".$conditionString;
		}
		 $this->test($sql);
		$DB->Execute($sql);
	 }
	 /**
	  *  excute update sql.
	  *  @param string  $updateSql   the excute sql
	  */
	 function updateBySql($updateSql,$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$DB->Execute($updateSql);
	 }
	 /**
	  * delete table for search condtion
	  * @param String        $TableName  the table name.
	  * @param String array  $condtion  the table column and value Map.
	  */
	function deleteRows( $TableName , $condition ,$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$TableName = dbGetPrefixTableNames($TableName);
	 	$sql = "delete  from ".$TableName;
	    if( empty($condition)){
			$sql = $sql;
		}else{
			foreach($condition as $key=>$value){
				$value = $this->check_input($value);
				$conditionString = $key.'='.''.$value .''.',';
			}
			$conditionString = substr($conditionString,0,strlen($conditionString)-1);
			$sql = $sql." where ".$conditionString;
		}
	 	$this->test($sql);
	 	$DB->Execute($sql);
	}
	 /**
	  *  excute delete sql.
	  *  @param string  $deleteSql  the delete string.
	  */
	function deleteBySql($deleteSql,$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$DB->Execute($deleteSql);
	}
	 /**
	  * get row by primary key
	  * @param String $TableName   the table name.
	  * @param String $column      the filed of search result.
	  * @param String $pkColumn	    the primary key column name.
	  * @param String $value       the primary key value.
	  */
	function getRowByPk($TableName,$column="",$pkColumn,$value,$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$TableName = dbGetPrefixTableNames($TableName);
	 	$value = $this->check_input($value);
	    if(empty($column))
	 	    $sql = "select * from ".$TableName." where ".$pkColumn. "=".$value."";
	    else
	        $sql = "select ".$column." from  ". $TableName." where ".$pkColumn. "=".$value."";
	 	 $this->test($sql);
	 	$Data = $DB->GetRow($sql);
	 	return $Data;
	}
	/**
	 * 查询一条记录
	 *
	 * @param unknown_type $sql
	 * @param unknown_type $DB
	 * @return unknown
	 */
  	function getRowByPkOne($sql,$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$this->test($sql);
	 	$Data = $DB->GetRow($sql);
	 	return $Data;
	}
	 /**
	  * search table
	  * @param String $TableName  the table name
	  * @param String $columns    the field of seach result
	  * @param String $ondtion    the search condition,it is sotre by array. exp $condition = array("id"=>"1");
	  * @param String $orderBy    the order desc.
	  * @param String $limit      the pagedtion.
	  */
	 function getRowsByCondition($TableName,$columns = "" ,$ondtion="",$orderBy="",$limit="",$DB=""){
	 	if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	$TableName = dbGetPrefixTableNames($TableName);
	 	$conditionString = '';
	    if($columns == "")
	 	    $sql = "select * from ".$TableName;
	    else
	        $sql = "select ".$columns." from ". $TableName;
	    if( $ondtion == ""){
			$sql = $sql;
		}else{
			foreach($ondtion as $key=>$value){
				$value = $this->check_input($value);
				$conditionString = $conditionString. ' and '.$key.'='.''.$value .'';
			}
			$sql = $sql." where (1=1) ".$conditionString;
		}		   if( empty($orderBy) ){
	   	$sql = $sql;
	   }else{
	   	  $sql = $sql." order by ".$orderBy;
	   }
	    if( $limit != '' ){
	   	   $sql = $sql . ' LIMIT  ' .$limit ;
	   }
	   //echo $sql.'<br>';
	    $this->test($sql);
	   $DataList = $DB->GetAll($sql);
	   return $DataList;
	}
	 /**
	  * search table by sql
	  * @param datatype $sqlchareter   the table name
	  * @param datatype $ondtion       the search condition,it is sotre by array. exp $condition = array("id"=>"1");
	  * @param datatype $limit         the pagedtion.
	  */
	 function getRowsBySQl($sqlchareter,$condition,$limit="",$DB=""){

	    if(empty($DB))
	    {
	        global $MyDB;
	        $DB = $MyDB;
	    }
	 	if( $limit != '' ){
	   	   $sql = $sqlchareter . ' LIMIT  ' .$limit ;
	    }else{
	       $sql = $sqlchareter;
	    }
	 	if( $condition == ''){
			$sql = $sql;
		}else{
			foreach($condition as $key=>$value){
			   $value = $this->check_input($value);
			   $sql =  str_replace(':'.$key,$value,$sql);
			}
		}
	    $this->test($sql);
	    $DataList = $DB->GetAll($sql);
	    return $DataList;
	}
}
?>