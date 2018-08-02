<?php
class TimeSetter{
	protected $_day;
	protected $_time;
	protected $_minutes;
	protected $_seconds;
	protected $_miliseconds;
	protected $_microseconds;
	protected $_date;
	protected $_format;
	protected $_month;
	protected $_year;
	
	public function __construct(){
	}
	
	public function setFormat($format){
		$this->_format =$format;
		}
	public function create_date(){
		$this->_date=date($this->_format);
	}
	
	public function create_time(){
		$this->_time=date($this->_format);
	}
	
	public function create_week($week_format){
		if(isset($week_format) && !empty($week_format)){
		$this->_week=date($week_format);
		}
	}
	
	public function create_days($day_format){
		if(isset($day_format) && !empty($day_format)){
		$this->_day=date($day_format);
		}else{
			throw new Exception("$day_format is not a valid format");
		}
	}
	
	public function create_month($month_format){
		if(isset($month_format) && !empty($month_format)){
		$this->_month=date($month_format);
		}else{
			throw new Exception("$month_format is not a valid format");
		}
	}
	
	public function compare_date($date1, $date2){
		if(isset($date1) && !empty($date1) && isset($date2) && !empty($date2)){
			$date1=strtotime($date1);
			$date2=strtotime($date2);
		}else{
			throw new Exception("The $date1 and $date are not a valid dates");
		}
		
		if($date1===$date2){
			return true;
		}else{
			return false;
		}
	}
	
	public function compare_month($month1,$month2){
		if(isset($month1) && !empty($month1) && isset($month2) && !empty($month2)){
			$month1=strtotime($month1);
			$month2=strtotime($month2);
		}
		if($month1===$month2){
			return true;
		}else{
			return false;
		}
	}
	
	public function compare_days($day1,$day2){
		if(isset($day1) && !empty($day1) && isset($day2) && !empty($day2)){
			$day1=strtotime($day1);
			$day2=strtotime($day2);
		}else{
			throw new Exception("$day1 and $day2 are not a valid dates");
		}
			if($day1===$day2){
				return true;
			}else{
				return false;
			}
	}
	
	public function create_year($year_format){
		if(isset($year_format) && !empty($year_format)){
		}
	}
	
	public function get_date(){
		return $this->_date;
	}
	
	public function get_time(){
		return $this->_time;
	}
	
	public function get_num_days_in_weeks(){
		return $this->get_num_days_in_weeks();
	}
	
	public function get_num_days_in_year(){
	}
}
?>