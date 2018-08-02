<?php
/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2@@
#THE FILE UPLOADER CLASS
*/
class FileUploader extends CATimageCreator{
	private $_FileName;
	private $_getFileSize;
	private $_getFileError;
	private $_getFile=array();
	private $_errorMessage=array();
	private $_successMessages=array();
	private $_fileUploadError=array();
	private $_arrOfFileNames=array();
	private $_data=array();
	private $_file;
	private $_getFileType;
	private $_table;
	private $_dirToStoreFiles;
	private $_FolderNameToStoreFileUnder;
	private $_base_dir;
	private $_dir;
	private $_isFileSaved;
	
	
	
	
	//THE FUNCTION CONSTRUCTOR
	public function __construct($file){
		if(isset($file) && !empty($file)){
			$this->_file=$file;
		}else{
			$this->_errorMessage[]="Empty file provided";
		}
	}
	
	public function setFolderName($nameOfFolder,$required=true){
		if(isset($nameOfFolder) && !empty($nameOfFolder) && $required==true){
			$this->_FolderNameToStoreFileUnder=trim($nameOfFolder);
		}else if($nameOfFolder=="" || empty($nameOfFolder) && $required==false){
			$this->_FolderNameToStoreFileUnder=$this->_dirToStoreFiles;
		}else{
			$this->_errorMessage[]="Please provide a folder name or set required to false";
		}
	}
	
	public function setDirectory($dir){
		if(isset($dir) && !empty($dir)){
			if(is_dir($dir)){
				$this->_dirToStoreFiles=trim($dir);
			}else{
				$createDir=mkdir($dir);
				if($createDir==true){
					$this->_dirToStoreFiles=$dir;
				}else{
					$this->_errorMessage[]='Directory creation failed for the given path';
				}
			}
		}else{
			$this->_errorMessage[]='Provide a directory to store files';
		}
	}
	
	public function setFolderPath($folderPath){
		if(isset($folderPath) && !empty($folderPath)){
			$this->_base_dir=$folderPath;
		}else{
			$this->_errorMessage[]="Incorrect directory path provided";
		}
	}
	public function makeDirectory(){
		
		if(isset($this->_base_dir) && isset($this->_FolderNameToStoreFileUnder)){
			$this->_dir=$this->_base_dir.$this->_FolderNameToStoreFileUnder.'/';
		}else{
			$this->_errorMessage[]='makeDirectory() fails: wrong path provided';
		}
	}
	public function setFileName($newfilename){
		if(isset($newfilename) && !empty($newfilename)){
			$this->_FileName=trim($newfilename);
		}else{
			$this->_errorMessage[]="Empty file name provided";
		}
		
	
		
	}
	
	public function saveFile(){
		if(is_dir($this->_dir)){
			$this->_isFileSaved=move_uploaded_file($this->_file,$this->_dir);
		}else{
			
			$this->_errorMessage[]='directory is not set';
		}
		//IF THE FILE IS SAVE PRINT OUT SAVE MESSAGE
		if($this->_isFileSaved==true){
			$this->_message[]="The file is successfully saved";
		}else{
			$this->_errorMessage[]="The file upload fails";
		}
	}
	
	protected function checkFileUploadError($uploaderror){
		if($uploaderror != UPLOAD_ERR_OK){
			switch($uploaderror){
			case UPLOAD_ERR_INI_SIZE:
			$this->_fileUploadError[]="The uploaded file exceeds the upload_max_filesize directive in php.ini";
			break;
			case UPLOAD_ERR_FORM_SIZE:
			$this->_fileUploadError[]="The uploaded file exceeds the max_file_size directive that was specified in the html form";
			break;
			case UPLOAD_ERR_PARTIAL:
			$this->_fileUploadError[]="The file was only partially uploaded";
			break;
			case UPLOAD_ERR_NO_FILE:
			$this->_fileUploadError[]="No file was uploaded";
			break;
			case UPLOAD_ERR_NO_TMP_DIR:
			$this->_fileUploadError[]="Missing a tempory folder";
			break;
			case UPLOAD_ERR_CANT_WRITE:
			$this->_fileUploadError[]="File to write the file to disk";
			break;
			default:
			$this->_fileUploadError[]="Fail to upload the specified file";
		}
   }
 }
 
 
 public function readAndSaveMultipleFileUpload($FILES,$nameOfFileInPost){
	 
	if(isset($FILES) && !empty($FILES) && isset($nameOfFileInPost) && !empty($nameOfFileInPost)){
	//PROCESS THE FILE UPLOAD AND SAVE THEM. RETURNS THEIR NAMES IN A FORM OF ARRAY
	//process the image files
	for($i=0;$i<count($_FILES);$i++){
		$errors=$_FILES[$nameOfFileInPost.$i]['error'];
        //get the name of the current image
        $image=$nameOfFileInPost.$i;
        $tmp_file=$_FILES[$image]['tmp_name'];
        $type=$_FILES[$image]['type'];
		$this->checkFileUploadError($errors);
		$this->PostImage($this->_dir,$tmp_file,$this->_FileName.$i);
     	//check to see if the image file is saved successfully
		if(empty($this->getImageErrorMessages())){
			$newname=$this->getImageName();
			array_push($this->_arrOfFileNames,$newname);
			array_push($this->_successMessages,"The file is successfully uploaded");
		}else{
			$this->_errorMessage[]=$this->getImageErrorMessages();
		  }
		}//end of the for statement
	 }else{
		 
	 }//end of if statement
 }//end of readMultipleupload function
 
 //GETTERS
 public function getFileUploadError(){
	 return $this->_fileUploadError;
 }
 public function getErrorMessages(){
	 return $this->_errorMessage;
 }
 public function getSuccessMessage(){
	 return $this->_successMessages;
 }
 
 public function getImageNames(){
	 return $this->_arrOfFileNames;
 }
 public function getDirectory(){
	 if(isset($this->_dir) && !empty($this->_dir)){
		 return $this->_dir;
	 }elseif(isset($this->_dirToStoreFiles) && empty($this->_dir)){
		 return $this->_dirToStoreFiles;
	 }
 }
}//end of class


/*
########################################################################################################################################################################################################################################################################################33333&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&((((())))((((()))))))))############################################
*/
class CATimageCreator{
	//DECLARE VARIABLES
	protected $_imageFile;
	protected $_resizedImage;
	private $_imageType;
	private $_newImageHeight;
	private $_newImageWidth;
	private $_newImage;
	private $_width;
	private $_height;
	private $imageFile;
	private $_ext;
	private $_imageName;
	private $_newImageName;
	private $_image_fm_file_upload;
	private $_ImageError=array();
	const MAX=200;
	const UPLOAD_MAX=500;
	private $_profileImageExt;
	private $_successMessages=array();
	private $_result;
	
	//CREATE A CONSTRUCTOR METHOD
	public function __construct(){
	}
	//setters
	public function setWidthHeightAndType($filename){
			$arrayOfileDetails=getimagesize($filename);
			if(is_array($arrayOfileDetails) && !empty($array)){
				list($width,$height,$type)=$arrayOfileDetails;
				$this->_width=$width;
				$this->_height=$height;
				$this->_imageType=$type;
			}else{
			    $this->_ImageError[]="setWidthAndHeight() failed";
			}
	}
	
	public function setWidth($width){
		if(isset($width) && is_int($width)){
			$this->_newImageWidth;
		}else{
		}
	}
	public function setHeight($height){
		if(isset($height) && is_int($height)){
			$this->_newImageHeight=$height;
		}
	}
	
	public function processFileUpload($filename,$uploadOk){
		$this->checkFileUploadError($uploadOk);
		if(empty($this->_fileUploadError)){
			//PROCESS THE UPLOADED FILE;
			if(isset($filename) && is_dir($dir)){
				//generat the image
				$this->generateImage($dir,$filename);
			}
		}else{
			throw new FileUploadException($uploadOk);
		}
	}
	
	protected function resizeImage(){
		$w=$this->getWidth();
		$h=$this->getHeight();
		
		if (isset($w) && ($w > self::MAX)){
			$this->_newImageWidth=self::MAX;
		}elseif(isset($w) && $w <self::MAX){
			$this->_newImageWidth=self::MAX;
		}else{
			throw new Exception("Width is not defined");
		}
		if(isset($h) && $h> self::MAX){
			$this->_newImageHeight=self::MAX;
		}elseif(isset($h) && $h < self::MAX){
			$this->_newImageHeight=self::MAX;
				}else{
					throw new Exception("Height is not defined");
				}
	}
	
	public function generateImage($DestinationPath,$imagefile,$uploaderror,$filename){
		//set the width, height and file type of the imagefile
		if(empty($this->getErrorMessages())){
			$this->setWidthHeightAndType($imagefile);
			switch($this->_imageType){
			case IMAGETYPE_JPEG:
			$this->_ext=image_type_to_extension(IMAGETYPE_JPEG);
			$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
			$src_img=imagecreatefromjpeg($imagefile);
			$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
			$this->_height);
			$this->_result=imagejpeg($des_img, $dir.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
			if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			case IMAGETYPE_PNG:
			$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
			$src_img=imagecreatefrompng($imagefile);
			$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
			$this->_height);
			$this->_result=imagejpeg($des_img,$dir.$this->_imageName.$this->_ext);
			if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			case IMAGETYPE_GIF:
			$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
			$src_img=imagecreatefromgif($imagefile);
			$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
			$this->_height);
			$this->_result=imagejpeg($des_img,$dir.$this->_imageName.$this->_ext);
			if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			default:
			 throw new Exception('No image was generated');
			}
		}else{
			
		}
	}
	
	public function generateProfilePicture($DestinationPath,$imagefile){
		if(is_null($DestinationPath) || !isset($DestinationPath)){
			$this->_ImageError[]="Image path not specified";
		}else{
			$dir=$DestinationPath;
		}
		//set the width, height and file type of the imagefile
		if(empty($this->getErrorMessages())){
			$this->_newImageHeight=200;
			$this->_newImageWidth=200;
			list($width,$height,$type)=getimagesize($imagefile);
			$this->_width=$width;
			$this->_height=$height;
			$this->_imageType=$type;
		    switch($this->_imageType){
			case IMAGETYPE_JPEG:
			$this->_profileImageExt=image_type_to_extension(IMAGETYPE_JPEG);
			$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
			$src_img=imagecreatefromjpeg($imagefile);
			$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
			$this->_height);
			$this->_result=imagejpeg($des_img, $dir.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
			if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			case IMAGETYPE_PNG:
			$this->_profileImageExt=image_type_to_extension(IMAGETYPE_JPEG);
			$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
			$src_img=imagecreatefrompng($imagefile);
			$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
			$this->_height);
			$this->_result=imagejpeg($des_img, $dir.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
			if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			case IMAGETYPE_GIF:
			$this->_profileImageExt=image_type_to_extension(IMAGETYPE_JPEG);
			$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
			$src_img=imagecreatefromgif($imagefile);
			$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
			$this->_height);
			$this->_result=imagejpeg($des_img, $dir.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
			if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			default:
			 $this->_ImageError[]="generateProfilePicture() failed completly";
			}
		}else{
		}
	}
	//GENERAT POST IMAGE
	public function PostImage($DestinationPath,$imagefile,$filename){
		ini_set("memory_limit","-1");
        ini_set("post_max_size","2000M");
		//get image details
		if(empty($this->_width) || !isset($this->_width) && empty($this->_height) || !isset($this->_height)){
			$arrayOfileDetails=getimagesize($imagefile);
			if(is_array($arrayOfileDetails) && !empty($arrayOfileDetails)){
				list($width,$height,$type)=$arrayOfileDetails;
				$this->_width=$width;
				$this->_height=$height;
				$this->_imageType=$type;
				$this->_newImageWidth=$this->_width;
				$this->_newImageHeight=$this->_height;
			}else{
				  $this->_ImageError[]="Please set the heigth and width of the image";
			}
		}else{
			$this->_newImageHeight=$this->_height;
			$this->_newImageWidth=$this->_width;
		}
		//make sure the width and height are set
		if(is_null($DestinationPath) || !isset($DestinationPath)){
			$this->_ImageError[]="Image path not specified";
		}else{
			$dir=$DestinationPath;
		}
		if(isset($filename) && !empty($filename)){
			$this->_imageName=$filename;
		}else{
			$this->_ImageError[]="Provide a new name for your image";
		}
		if(!isset($this->_newImageHeight) && !isset($this->_newImageWidth)){
			$this->_ImageError[]="Width and heigth are not set";
		}
		//if everything is ok proceed and create the image
		if(empty($this->_ImageError)){
		    switch($this->_imageType){
				case IMAGETYPE_JPEG:
				$this->_ext=image_type_to_extension(IMAGETYPE_JPEG);
				$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
				$src_img=imagecreatefromjpeg($imagefile);
				$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
				$this->_height);
				$this->_result=imagejpeg($des_img, $dir.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
				if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
				$this->_newImageName=$this->_imageName.$this->_ext;
				imagedestroy($des_img);
			}else{
				$this->_ImageError[]="imagejpeg failed to create and saved the image";
			}
			break;
			case IMAGETYPE_PNG:
				$this->_ext=image_type_to_extension(IMAGETYPE_PNG);
				$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
				$src_img=imagecreatefrompng($imagefile);
				$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
				$this->_height);
				$this->_result=imagejpeg($des_img,$dirs.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
				if($this->_result==true){
					$this->_successMessages[]="Image file successfully uploaded";
					$this->_newImageName=$this->_imageName.$this->_ext;
					imagedestroy($des_img);
				}else{
					$this->_ImageError[]="imagejpeg failed to create and saved the image";
				}
			break;
			
			case IMAGETYPE_GIF:
				$this->_ext=image_type_to_extension(IMAGETYPE_GIF);
				$des_img=imagecreatetruecolor($this->_newImageWidth, $this->_newImageHeight);
				$src_img=imagecreatefromgif($imagefile);
				$newImage=imagecopyresampled($des_img,$src_img,0,0,0,0,$this->_newImageWidth,$this->_newImageHeight,$this->_width,
				$this->_height);
				$this->_result=imagejpeg($des_img, $dir.$this->_imageName.image_type_to_extension(IMAGETYPE_JPEG));
				if($this->_result==true){
				$this->_successMessages[]="Image file successfully uploaded";
				$this->_newImageName=$this->_imageName.$this->_ext;
				imagedestroy($des_img);
				}else{
					$this->_ImageError[]="imagejpeg failed to create and saved the image";
				}
			break;
			
			default:
			 $this->_ImageError[]="PostImage failed completly";
			}
		}//the if(empty(imageError)) condition
	}
//GET SETTER METHOD VALUES;
public function getImageErrorMessages(){
	return $this->_ImageError;
}
	public function getWidth(){
		return $this->_width;
	}
	
	public function getImageName(){
		return $this->_newImageName;
	}
	
	public function getHeight(){
		return $this->_height;
	}
	
	public function getNewWidth(){
		return $this->_newImageWidth;
	}
	
	public function getNewHeight(){
		return $this->_newImageHeight;
	}
	
	public function get_image_ext(){
		return $this->_ext;
		
	}
	
	public function get_profile_image_ext(){
		return $this->_profileImageExt;
	}

}
?>