<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/class.image.php';

// default redirection
$url = 'callback.html?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);
$image_size = isset($_POST['image_size']) ? trim($_POST['image_size']) : 650;
if(!is_numeric($image_size)){
	$image_size = 650;
}

// SUCCESSFUL
if($bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = quotationMagic($_FILES['Filedata']['name']);

	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = explode(',', 'gif,png,bmp,jpg,jpeg');

	if(!in_array($filename_ext, $allow_file)) {
		$url .= '&errstr='.$name;
	} else {
		$uploadDir = '/file/'.date('Y').'/'.date('m').'/'.date('d').'/';
		$uploadFullDir = $_SERVER['DOCUMENT_ROOT'].$uploadDir;
		if(!is_dir($uploadFullDir)){
			umask(0);
			mkdir($uploadFullDir, 0770, true);
		}

		if(file_exists($tmp_name)){
			list($width, $height) = getimagesize($tmp_name);
			if($width > $image_size){
				$height = floor( ( $height * $image_size ) / $width);
				$width = $image_size;
			}

			$filename = uniqid().'_'.date('YmdHis');
			$filename = $filename.'_'.$width.'_'.$height;
			$filename = $filename.'.'.$filename_ext;

			$newPath = $uploadFullDir.$filename;

			@move_uploaded_file($tmp_name, $newPath);

			$image = new Image($newPath);
			$image->width($width);
// 			$image->height($height);
			$image->save();
		}

		$url .= "&bNewLine=true";
		$url .= "&sFileName=";
		$url .= "&sFileURL=http://".$_SERVER["HTTP_HOST"].$uploadDir.$filename;
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}

function quotationMagic($str) {
	$dq = 0;
	$sq = 0;
	$rs = "";

	preg_match_all('/./u',$str,$arr);
	foreach ($arr[0] as $k => $v)
	{
		if ($v == "\"")
		{
			if ($dq % 2 == 0)
			{
				$v = "“";
			} else {
				$v = "”";
			}

			$dq++;
		}
		if ($v == "'")
		{
			if ($sq % 2 == 0)
			{
				$v = "‘";
			} else {
				$v = "’";
			}

			$sq++;
		}
		$rs .= $v;
	}

	return $rs;
}

header('Location: '. $url);
?>
