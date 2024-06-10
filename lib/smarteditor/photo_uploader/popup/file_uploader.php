<?php
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

	// if (preg_match("/(html|htm|php|cgi|phtml|shtml)/i", $file_extension)) {
	if (empty($file_extension) || eregi($forbidden_extension_patten , $file_extension))
		$file_extension = "tmp";

	$tar_file = uniqid('').".".$file_extension;
	while (file_exists($tar_folder.$tar_file)) {
		$tar_file = uniqid('').".".$file_extension;
	}
	return $tar_file;
}

// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if($bSuccessUpload) {

	$tmp_path = $_FILES['Filedata']['tmp_name'];

	$uploaddir = $_CONFIG_UPLOAD_DIR."/".$_GET['board']."/";
	$new_fname = main_photo_fname($_FILES['Filedata']['name'],$uploaddir);
	$new_path = $uploaddir.$new_fname;

	if(! @move_uploaded_file($tmp_path, $new_path) )
		f_ERROR_MSG("Fail Upload");

	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($_FILES['Filedata']['name']));
	$url .= "&sFileURL=".$_CONFIG_UPLOAD_DIR_HTTP."/".$_GET['board']."/".$new_fname;

/*
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$uploadDir = '../../upload/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}
	
	$newPath = $uploadDir.urlencode($_FILES['Filedata']['name']);
	
	@move_uploaded_file($tmp_name, $new_path);
	
	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($name));
	$url .= "&sFileURL=/smarteditor/demo/upload/".urlencode(urlencode($name));
	*/
}
// FAILED
else {
	$url .= '&errstr=error';
}

//header('Location: '. $url);
?>