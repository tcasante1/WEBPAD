<?php
/*
/************************************************************************************************************************************\
/*************************************************************************************************************************************\
#################-----   VALIDATING DATA SUBMITTED FROM FORM INPUT AS ARRAY-----############################################
[**************************************************************************************************************************************]
*/

class TC_Form_Validator{
protected $_inputType;
public $_error =array();
protected $_message=array();
protected $_warning=array();
protected $_submitted;
protected $_required=array();
protected $_missing;
protected $_filters_to_apply;

public function __construct($inputType,$requiredField){
	$this->tc_set_input_type($inputType);
	if(is_array($requiredField)){
		$this->_required=$requiredField;
	}else{
		throw new Exception("Required field must be an array even if is a single value");
	}
}
public function tc_check_empty_field($fieldName){
		if(isset($this->_submitted[$fieldName]) && empty($this->_submitted[$fieldName])){
			$this->_error["$fieldName"] ="$fieldName** is required";
			return true;
			}else{
				return false;
	}
}
public function	tc_validate_integer($value){
	if($this->tc_check_empty_field($value)===false){
		$value =$this->_submitted[$value];
		$filter = FILTER_VALIDATE_INT;
		if(filter_var($value, $filter)===false){
			$this->_error['int'] = "Please provide a valid integer ";
			return false;
		}else{
			return $value;
		}
	   }
	}

public function tc_validate_float($value){
	if($this->tc_check_empty_field($value)===false){
		$value=$this->_submitted[$value];
		$filter =FILTER_VALIDATE_FLOAT;
		$flag = FILTER_FLAG_ALLOW_THOUSAND;
		$option =array('option' => array('decimal' => '.'));
		if(filter_var($value, $filter)===false){
			$this->_error['float'] ="Please provide at least one decimal place";
		return false;
		}else{
			return $value;
		}
	}
}

public function tc_validate_string($key){
	if($this->tc_check_empty_field($key)===false){
		$value=$this->_submitted[$key];
		$filter =FILTER_SANITIZE_STRING;
		$flag = FILTER_FLAG_STRIP_HIGH;
		if(filter_var($value, $filter, $flag)===false){
			$this->_error['string']="$value is invalid. This field accept only letters";
			return false;
		}else{
			return $value;
		}
	}
}

public function tc_validate_url($url){
	if(isset($url) && !empty($url)){
		$filter =FILTER_VALIDATE_URL;
		$sanitize =FILTER_SANITIZE_URL;
		if(filter_var($url, $filter, $sanitize)===false){
			$this->_error['url']="Invalid URL provided. $url";
			return false;
		}else{
			return $url;
		}
	}
}

public function tc_validate_email($key){
	if($this->tc_check_empty_field($key)===false){
		$email=$this->_submitted[$key];
		$filter =FILTER_VALIDATE_EMAIL;
		$sanitize=FILTER_SANITIZE_EMAIL;
		if(filter_var($email, $filter, $sanitize)===false){
			$this->_error['email'] ="Invalid email provided.";
		}
	}
}

public function tc_validate_string_length($key, $min, $max=NULL){
	if(!is_string($this->_submitted[$key]) && !is_int($min) && !is_int($max)){
		throw new Exception("Invalid data provided. Valid data types are String and Integers");
	}else{
		$value =$this->_submitted[$key];
		if(strlen($this->_submitted[$key]) < $min){
			$this->_error['min']="$value cannot be less than $min";
		}elseif(is_int($max)){
		$this->_error['both'] ="$value must be between $min and $max";
		}
		
		if(strlen($value) > $max){
			if($min==0){
				$this->_error['max'] ="$value cannot be more than $max";
			}else{
				$this->_error['both'] ="$value must be between $min and $max";
			}
		}
	}
}
//start Sanitizing
 public function tc_sanitizeString($fieldName, $preservedQuotes=false,$encodeHigh=false, $encodeLow=false,
                                    $stripHigh=false, $stripLow=false,$encodeAmps=false)
   {
	   $this->_filters_to_apply[$fieldName]['filter']=FILTER_SANITIZE_STRING;
	   $this->_filters_to_apply[$fieldName]['flags'] = 0;
	   if($preservedQuotes){
		   $this->_filters_to_apply[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
	   }
	   if($encodeAmps){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_ENCODE_AMP;
	   }
	   if($encodeLow){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_ENCODE_LOW;
		   }
	   if($stripHigh){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_STRIP_HIGH;
	   }
	   if($stripLow){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_STRIP_LOW;
		   }
	   if($encodeLow){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_ENCODE_HIGH;
	   }
	  
	  if(filter_var($fieldName, $this->_filters_to_apply) ===false){
		  throw new Exception("Fail to sanitize $fieldName");
	  }else{
		  return true;
	  }
   }
   
//remove tags from array
 public function tc_sanitizeArrays($fieldName, $preservedQuotes=false,$encodeHigh=false, $encodeLow=false,
                                      $stripHigh=false,$stripLow=false,$encodeAmps=false)
   {
	   $this->_filters_to_apply[$fieldName]['filter']=FILTER_SANITIZE_STRING;
	   $this->_filters_to_apply[$fieldName]['filter'] = FILTER_REQUIRE_ARRAY;
	   $this->_filters_to_apply[$fieldName]['flags'] = 0;
	   if($preservedQuotes){
		   $this->_filters_to_apply[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
	   }
	   if($encodeAmps){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_ENCODE_AMP;
	   }
	   if($encodeLow){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_ENCODE_LOW;
		   }
	   if($stripHigh){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_STRIP_HIGH;
	   }
	   if($stripLow){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_STRIP_LOW;
		   }
	   if($encodeLow){
		   $this->_filters_to_apply[$fieldName]['flags'] |=FILTER_FLAG_ENCODE_HIGH;
	   }
	   
	   if(filter_var($fieldName, $this->_filters_to_apply)===false){
		   throw new Exception("Fail to sanitize the array data, $fieldName");
	   }else{
		   return true;
	   }
   }
   
//end sanitizing

public function tc_set_input_type($type){
	$type = strtolower($type);
	if(!empty($type)){
		switch($type){
			case 'post':
			$this->_inputType=INPUT_POST;
			$this->_submitted =$_POST;
			break;
			
			case 'get':
			$this->_inputType=INPUT_GET;
			$this->_submitted= $_GET;
			default:
			$this->_error['input'] ="Invalid Input type submitted, $type. Acceptable ones are post and get";
		}
	}
	
}
public function tc_check_require_field(){
	$submitted =$this->_submitted;
	$containValue=array();
	foreach($submitted as $key=>$value){
		if(is_array($value)){
			$value=$value;
		}else{
			$value=trim($value);
		}
		
		if(!empty($value)){
			$containValue[$key]=$value;
		}
	}
	
	$this->_missing=array_diff($this->_required, $containValue);
}


public function tc_getError(){
	return $this->_error;
}

public function tc_getMessage(){
	return $this->_message;
}

public function tc_getWarning(){
	return $this->_warning;
}

}

/*
/************************************************************************************************************************************\
/*************************************************************************************************************************************\
#################-----   VALIDATING DATA THAT REQUIRE CONNECTION TO THE DATABASE -----############################################
[**************************************************************************************************************************************]
*/

class TCValidateSingleData{
protected $_filteredString;
protected $_filtered_str_err;

public function tc_validate_and_sanitize_String($string){
	if(!empty($string)){
		$filter=FILTER_SANITIZE_STRING;
		$flag =FILTER_FLAG_STRIP_HIGH;
		if(!filter_var($string, $filter, $flag)===false){
			if(ctype_alnum($string)===true){
				$this->_filteredString=$string;
			}else{
				$this->_filtered_str_err="The given data is not a string";
			}
		}else{
				$this->_filtered_str_err="Fail to apply filter";
	   }
	}else{
		$this->_filtered_str_err="No data provided";
	}
  }

public function tc_verify_password($pws1, $pws2,$passLength){
	if(is_int($passLength)){
	if(strlen($pws1)<$passLength || strlen($pws2)<$passLength){
		$this->_error['pwd'] ="Password must be more than 6 characters";
	   }
	}
	if(strcmp($pws1, $pws2) !==0){
		$this['pwd'] ="Password does not match";
	}
	if(!isset($this->_error['pwd']) && empty($this->_error['pwd'])){
		$pwd=$pws1;
		return $pwd;
	}else{
		return false;
	}
}
public function getFilteredString(){
	return $this->_filteredString;
}

public function getFiteredStrError(){
	return $this->_filtered_str_err;
}
}

/*
/************************************************************************************************************************************\
/*************************************************************************************************************************************\
#################-----   ANOTHER DATA VALIDATING CLASS. THIS IS TO REMOVE DUPLICATION -----############################################
[**************************************************************************************************************************************]
*/
class CheckData{
	
	public function __construct(){
	}
	
	public function checkString($string){
		if(is_string($string) && !empty($string)){
			return $string;
		}else{
			return NULL;
		}
	}
	
	public function trimString($string){
		$string=$this->checkString($string);
		if(!is_null($string)){
			$string=trim($string);
			return $string;
		}else{
			return NULL;
		}
	}
	
  public function removeUnwantedCharachters($string){
	  $string=$this->trimString($string);
	  if(!is_null($string)){
		  if(ctype_alnum($string)===true){
			  return $string;
		  }else{
			  return NULL;
		  }
	  }
  }
  
  public function checkForNumericValue($data){
	  $data=$this->trimString($data);
	  if(!is_null($data)){
		  if(is_numeric($data)){
			  return $data;
		  }else{
			  return NULL;
		  }
	  }
  }

}
?>