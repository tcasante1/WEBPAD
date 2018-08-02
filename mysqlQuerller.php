<?php
class Retriver{
protected $_host;
protected $_user;
protected $_pwd;
protected $_db;
private $_link;
private $_single_r;
private $_single_c;
private $_all_r;
private $_three_r;
private $_rows;
# 3/26/2015
private $_singleColumnWithoutCondition;
protected $_LikeRows;
protected $_rows_using_c;
protected $_tableName;
protected $_rowCount;
protected $_exitcutsql;
protected $_maxLimit;
protected $_minLimit;
protected $_limitedRecords;
protected $__two_r;
protected $_totalRecords;
private $_error=array();
protected $_recursiveRow;
# 3/26/2015
protected $_custumsearchtable;

public function __construct($user, $password, $host, $dbname){
$this->_host=$host;
$this->_user=$user;
$this->_db=$dbname;
$this->_pwd=$password;
}

public function connect(){
$connect=mysqli_connect($this->_host, $this->_user, $this->_pwd);
if($connect===false){
  throw new Exception(die(mysqli_error()),0);
}else{
  $this->_link=$connect;
}
}

public function check_for_null($name){
if(isset($name) && !empty($name) && is_string($name)){
  return true;
  }else{
	  return false;
  }
}

public function set_db(){
if($this->check_for_null($this->_db)===true){
  mysqli_select_db($this->_link, $this->_db);
}else{
  throw new Exception(die(mysqli_error()), 1);
}
}

public function set_single_row_u_c($tablename, $fieldname, $cond){
$query="SELECT * FROM $tablename WHERE $fieldname='$cond'";
$result=mysqli_query($this->_link,$query);
if($result===false){
  throw new Exception(die(mysqli_error($this->_link)), 2);
}else{
  $this->_single_r=$result;
}
}

public function set_all_rows($tablename,$orderbyid){
if($this->check_for_null($tablename)==true){
  $sql="SELECT * FROM $tablename ORDER BY $orderbyid DESC";
  $query=mysqli_query($this->_link,$sql);
  if($query==true){
	  $this->_all_r=$query;
  }else{
	  throw new Exception('Fail to retrive data');
  }
  }else{
  throw new Exception("Provide table name");
  }
}

public function set_single_row($tablename, $columnName,$field, $condition){
if($this->check_for_null($tablename)==true && $this->check_for_null($columnName)==true && $this->check_for_null($field)&& $this->check_for_null($condition)){
$sql="SELECT $columnName FROM $tablename WHERE $field='$condition'";
$query=mysqli_query($sql, $this->_link);
if($query==true){
  $this->_single_c=$query;
}else{
  throw new Exception(die(mysqli_error()),3);
}
}
}

public function set_two_rows($tablename,$column1,$column2,$conditionValue1,$conditionValue2){
if($this->check_for_null($tablename)==true && $this->check_for_null($column1)==true && $this->check_for_null($column2)==true && $this->check_for_null($conditionValue1)==true && $this->check_for_null($conditionValue2)==true){
$sql="SELECT * FROM $tablename WHERE $column1='$conditionValue1' AND $column2='$conditionValue2'";
$query=mysqli_query($this->_link,$sql);
if($query==true){
$this->_two_r=$query;
}else{
throw new Exception(die(mysqli_error($this->_link)),4); 
}
}
}

public function get_two_rows(){
	return $this->_two_r;
}

public function set_three_rows($tablename, $field1, $field2,$field3){
if($this->check_for_null($tablename)==true && $this->check_for_null($field1)==true && 
   $this->check_for_null($field2)==true && $this->check_for_null($field3)==true){
	   $sql="SELECT $field1, $field2, $field3 FROM $tablename";
	   $query=mysqli_query($this->_link,$sql);
	   if($query==true){
		   $this->_three_r=$query;
	   }else{
		   throw new Exception(die(mysqli_error($this->_link)),4); 
	   }
}
}

public function allrows($tablename, $fieldname1,$fieldname2, $cond1){
$query="SELECT * FROM $tablename WHERE $fieldname1='$cond1' ORDER BY comment_id,comment_date DESC";
$result=mysqli_query($this->_link,$query);
if($result==true){
  $this->_rows=$result;
  }else{
  throw new Exception(die(mysqli_error($this->_link)));	
}
}

public function set_row_using_c($tablename,$con_column,$con_value){
if(!is_null($tablename) && !is_null($con_column) && !is_null($con_value)){
  $tablename=mysqli_real_escape_string($this->_link,$tablename);
  $con_value=mysqli_real_escape_string($this->_link,$con_value);
  $con_column=mysqli_real_escape_string($this->_link,$con_column);
  $query="SELECT * FROM $tablename WHERE $con_column='$con_value'";
  $result=mysqli_query($this->_link,$query) or die(mysqli_error());
  if($result !=false){
	  $this->_rows_using_c=$result;
  }else{
	  $this->_error[]="set_row_using_c failed to retrive the specified data";
	  throw new Exception("Fail to set data. trace back from set_row_using_c");
  }
}else{
  throw new Exception("Empty variables cannot be processed");
}
}
public function setLikeRows($tablename,$fieldname,$data){
if(!is_null($tablename) && !is_null($fieldname) && !is_null($data)){
$sql="SELECT * FROM $tablename WHERE $fieldname LIKE '%$data'";
$query=mysqli_query($this->_link,$query)or die(mysqli_error($this->_link));
if($query==true){
  $this->_LikeRows=$query;
}else{
  throw new Exception("Fail to process sql");
}
}else{
  throw new Exception("Empty field cannot be processed");
}
}

public function count_rows($tablename,$con_column,$con_value){
if(!is_null($tablename) && !is_null($con_column) && !is_null($con_value)){
  $tablename=mysqli_real_escape_string($this->_link,$tablename);
  $con_value=mysqli_real_escape_string($this->_link,$con_value);
  $con_column=mysqli_real_escape_string($this->_link,$con_column);
  $query="SELECT * FROM $tablename WHERE $con_column='$con_value'";
  $result=mysqli_query($this->_link,$query) or die(mysqli_error($this->_link));
  if($result !=false){
	  $this->_rowCount=mysqli_num_rows($result);
  }else{
	  throw new Exception("Fail to count rows. trace back from count_rows");
  }
}else{
  throw new Exception("Empty variables cannot be processed");
}
}

//RETRIVE LIMITED ROWS FROM THE DATABASE
public function set_limited_rows($tablename,$orderbyid,$min,$max){
$query="SELECT * FROM $tablename ORDER BY $orderbyid DESC LIMIT $min,$max";
$result=mysqli_query($this->_link,$query);
if($result==false){
  throw new Exception(die(mysqli_error($this->_link)));
}else{
  $this->_limitedRecords=$result;
  $this->_maxLimit=$max;
  $this->_minLimit=$min;
  $query="SELECT COUNT(*) FROM $tablename";
  $result=mysqli_query($this->_link,$query);
  if($result==false){
  throw new Exception(die(mysqli_error($this->_link)));
  }else{
	    $row=mysqli_fetch_row($result); 
		$this->_totalRecords=$row[0];
   }
 }
}

//MAKE A CUSTOM SEARCH WITH LIMITED RECORDS
public function cat_custom_search($searchValue,$columnId,$min,$max){
	$mytableArray=array(
	"cars"=>"Cars and Vehicles",
	"bikes"=>"Bikes and Motor Bikes",
	"parts"=>"Parts and Accessories",
	"heavydutytrucks"=>"Heavy Duty Trucks",
	"buses"=>"Buses",
	"apartments"=>"Apartments",
	"houses"=>"Houses",
	"hostels"=>"Rooms",
	"lands"=>"Land",
	"farms"=>"Farms",
	"hostels"=>"Hostels",
	"stores"=>"Stores",
	"phones"=>"Mobile Phones",
	"phonesaccessories"=>"Phones Accessories",
	"cameras"=>"Video Cameras and Camcoders",
	"tvdvdcd"=>"Tv, DVD and CD Players",
	"audiomp3"=>"Audio and MP3 Players",
	"laptops"=>"Laptops and Tablets",
	"computers"=>"Computers and Accessories",
	"musicalinstruments"=>"Musical Instruments",
	"games"=>"Console Games",
	"clothes"=>"Clothing and Wear",
	"clothes"=>"Children Items",
	"arts"=>"Arts and Collectibles",
	"theatretickets"=>"Theatre Tickets",
	"movietickets"=>"Movie Tickets",
	"partytickets"=>"Party Tickets",
	"hotels"=>"Hotel Booking",
	"jobs"=>"Job Vacancies",
	"services"=>"Services",
	"fruits"=>"Fruits",
	"vegetables"=>"Vegetables",
	"meats"=>"Fish and Meats",
	"crops"=>"Crops Seeds and Plants",
	"farmanimals"=>"Farm Animals",
	"pets"=>"Pets",
	"petsaccessories"=>"Accessories for pets",
	"animalfeeds"=>"Food for animals and pets",
	"caretakers"=>"Pet Caretakers",
	"vertinery"=>"Vertinery Services",
	"privateclinics"=>"Private Clinics",
	"privatedoctor"=>"Private Doctor",
	"emergency"=>"Emergency Services",
	"funerals"=>"Funeral Services",
	"drugs"=>"Drugs(Authodox and Herbal)",
	"textbooks"=>"Text Books",
	"teacherandtraining"=>"Teaching and Training",
	"privateteachers"=>"Private Teachers",
	"exams"=>"Exams Services",
	"assignments"=>"Home work and Assigments",
	"data"=>"Data Analysis(SPSS,EXCELL, miniTab,etc)",
	"other"=>"Other"
	);
	//LOOP THROUGH TO FOR THE RIGHT TABLE
	foreach($mytableArray as $key=>$value){
		if(strcmp($searchValue,$value)==0){
			//SAVE THE FOUND TABLE
			$this->_custumsearchtable=$key;
			break;
		}
		//END THE LOOPING IF THE RIGHT 
		//DATA HAVE BEING OBTAINED
	}
	//MAKE A REQUEST TO THE DATABASE
	$this->set_limited_rows($this->_custumsearchtable,$columnId,$min,$max);
	$this->_recursiveRow=$this->get_limited_records();
}
public function set_single_colum_data($tablename,$orderbyid){
if($this->check_for_null($tablename)==true){
  $sql="SELECT * FROM $tablename ORDER BY $orderbyid";
  $query=mysqli_query($this->_link,$sql);
  if($query==true){
	  $this->_singleColumnWithoutCondition=$query;
  }else{
	  throw new Exception('Fail to retrive data');
  }
  }else{
  throw Exception("Provide table name");
}
}

public function exitSQLCommand($sql){
if(isset($sql) && !empty($sql)){
  $sql=mysqli_real_escape_string($this->_link,$sql);
  $query=mysqli_query($this->_link,$sql) or die(mysqli_error($this->_link));
  if($query==true){
	  $this->_exitcutsql=$query;
  }else{
	  throw new Exception("exitSQLCommand Fails. Check your sql Language");
  }
}
}


//THE GETTER FUNCTIONS
//3/26/205
public function get_custom_search_table(){
	return $this->_custumsearchtable;
}
public function get_single_colum_data(){
	return $this->_singleColumnWithoutCondition;
}

public function get_custom_search_results(){
	if(!empty($this->_recursiveRow)){
		return $this->_recursiveRow;
	}
}

public function get_min_limit(){
return $this->_minLimit;
}

public function get_max_limit(){
return $this->_maxLimit;
}

public function get_limited_records(){
return $this->_limitedRecords;
}
public function get_total_records(){
	return $this->_totalRecords;
}
public function getResultsFromExitCuttedCommand(){
return $this->_exitcutsql;
}

public function set_table_name($name){
if(!is_null($name)){
  $this->_tableName=trim($name);
}
}
public function check_table_exists(){
$tablename=$this->_tableName;
$tablename=mysqli_escape_string($tablename);
}

public function get_count_rows(){
return $this->_rowCount;
}
public function get_link(){
return $this->_link;
}

public function getLikeRows(){
return $this->_LikeRows;
}
public function get_single_row_u_c(){
return $this->_single_r;
}

public function get_single_row(){
return $this->_single_c;
}

public function get_three_row(){
return $this->_three_r;
}

public function get_all_rows(){
return $this->_all_r;
}

public function return_rows(){
return $this->_rows;
}

public function get_con_rows(){
return $this->_rows_using_c;
  }
  
 public function getErrorMessages(){
	 return $this->_error;
 }
}



// @@@@UPDATE CLASS @@@@ \\
class mysqliUpdator extends Retriver{
public function __construct($server, $username, $password, $database){
if(!is_null($server) && !is_null($database) && !is_null($password) && !is_null($username)){
  $this->_host=$server;
  $this->_pwd=$password;
  $this->_user=$username;
  $this->_db=$database;
}
}
public function update_row($tablename, $fieldname, $data,$field,$condition){
if(!is_null($tablename) && !is_null($fieldname) && !is_null($data) && !is_null($condition)){
  $sql="UPDATE $tablename SET $fieldname='$data' WHERE $field='$condition'";
  $update=mysqli_query($sql, $this->get_link());
  if($update===false){
	  throw new Exception(die(mysqli_error($this->get_link())));
  }
}else{
  throw new Exception("Empty data cannot be processed");
  }
 }

}

class MysqlDelector{
protected $_server;
protected $_pwd;
protected $_user;
protected $_databaseName;
private $_link;
private $_success;

public function __construct($user,$pwd,$server,$db){
$user=trim($user);
$pwd=trim($pwd);
$server=trim($server);
$db=trim($db);
if(!is_null($db) && !is_null($pwd) && !is_null($server) && !is_null($user)){
  $this->_databaseName=$db;
  $this->_pwd=$pwd;
  $this->_server=$server;
  $this->_user=$user;
}else{
  throw new Exception("Invalid connection parameters provided");
}
}

//MAKE A CONNECTION TO THE DATABASE
public function connect(){
$this->_link=mysqli_connect($this->_server,$this->_user,$this->_pwd);
if($this->_link==true){
  mysqli_select_db($this->_link,$this->_databaseName);
}else{
  throw new Exception("mysqli failled to create connection");
}
}

public function del_single_row($tablename,$conColumn,$conValue){
if(isset($tablename) && isset($conColumn) && isset($conValue)){
$tablename=trim(mysqli_real_escape_string($this->_link,$tablename));
$conColumn=trim(mysqli_real_escape_string($this->_link,$conColumn));
$conValue=trim(mysqli_real_escape_string($this->_link,$conValue));
$sql="DELETE FROM $tablename WHERE $conColumn='$conValue'";
$query=mysqli_query($this->_link,$sql);
if($query==true){
  $this->_success="Record successfully deleted";
}else{
  throw new Exception(die(mysqli_error($this->_link)));
}
}else{
  throw new Exception("Invalid data provided. traceBack form del_single_row");
}
}

public function getSuccessMessage(){
return $this->_success;
}
}
?>