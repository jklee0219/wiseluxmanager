<?php
$setRPath = "../../../";
include($setRPath.'comm/_resource.php');

	function name2Ext($filename) {
		if (!empty($filename)) {
			if (ereg("\.([^\.]+)$", $filename, $tmp_reg)) return substr(strtolower($tmp_reg[1]), 0, 4);
			else return 'tmp';
		} else return '';
	} 

	function main_photo_fname($ori_name,$tar_folder) {
		$forbidden_extension_patten = '(html|htm|php|cgi|phtml|shtml)$';
		$tar_file = '';
		$file_extension = name2Ext($ori_name);
		if (empty($file_extension) || eregi($forbidden_extension_patten , $file_extension)) $file_extension = "tmp";
		$tar_file = uniqid('').".".$file_extension;
		while (file_exists($tar_folder.$tar_file)) {
			$tar_file = uniqid('').".".$file_extension;
		}
		return $tar_file;
	}


 	$sFileInfo = '';
	$headers = array();
	 
	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		} 
	}
	
	$uploaddir = $_CONFIG_UPLOAD_DIR."/".$_GET['board']."/";
	
	if($headers['file_size'] > 0){
		$new_fname = main_photo_fname($headers['file_name'],$uploaddir);

		$file = new stdClass; 
		$file->name = $new_fname;	
		$file->size = $headers['file_size'];
		$file->content = file_get_contents("php://input"); 

		$newPath = $uploaddir.$new_fname;
		
		if(file_put_contents($newPath, $file->content)) {
			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=".$file->name;
			$sFileInfo .= "&sFileURL=http://".$_SERVER["HTTP_HOST"]."/".$_CONFIG_UPLOAD_DIR_HTTP."/".$_GET['board']."/".$file->name;
		}
		echo $sFileInfo;
	}else{
		echo "error";
	}

/*
	$file = new stdClass;
	$file->name = rawurldecode($headers['file_name']);
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");

	$uploadDir = '../../upload/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}
	
	$newPath = $uploadDir.iconv("utf-8", "cp949", $file->name);
	
	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$file->name;
		$sFileInfo .= "&sFileURL=/smarteditor/demo/upload/".$file->name;
	}
	
	echo $sFileInfo;
	*/
 ?>