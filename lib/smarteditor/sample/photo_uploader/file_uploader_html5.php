<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	include_once $_SERVER['DOCUMENT_ROOT'].'/admn/application/controllers/class.image.php';

 	$sFileInfo = '';
	$headers = array();

	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		}
	}

	$filename = quotationMagic(rawurldecode($headers['file_name']));
	$filename_arr = explode('.',$filename);
	$filename_ext = strtolower(array_pop($filename_arr));
	$allow_file = explode(',', 'gif,png,bmp,jpg,jpeg');
	$resize_width = isset($headers['file_resize']) ? $headers['file_resize'] : 650;
	if(!is_numeric($resize_width) || $resize_width > 650){
		$resize_width = 1000;
	}

	if(!in_array($filename_ext, $allow_file)) {
		echo "NOTALLOW_".$filename;
	} else {
		$file = new stdClass;
		$file->name = date("YmdHis").mt_rand().".".$filename_ext;
		$file->content = file_get_contents("php://input");

		$uploadDir = '/file/'.date('Y').'/'.date('m').'/'.date('d').'/';
		$uploadFullDir = $_SERVER['DOCUMENT_ROOT'].$uploadDir;
		//echo $uploadFullDir;
		if(!is_dir($uploadFullDir)){
			umask(0);
			mkdir($uploadFullDir, 0770, true);
		}

		$newPath = $uploadFullDir.$filename;

		if(file_put_contents($newPath, $file->content)) {
			list($width, $height) = getimagesize($newPath);
			if($width > $resize_width){
				$height = floor( ( $height * $resize_width ) / $width);
				$width = $resize_width;
			}

			$filename = uniqid().'_'.date('YmdHis');
			$filename = $filename.'_'.$width.'_'.$height;
			$filename = $filename.'.'.$filename_ext;
			$rename_newPath = $uploadFullDir.$filename;
			@rename($newPath, $rename_newPath);

			$image = new Image($rename_newPath);
			$image->width($width);
// 			$image->height($height);
			$image->save();
			
			//이미지를 카페24서버에 저장 트래픽 문제
			$cafe24_ftp_host = '14.128.159.170';
			$cafe24_ftp_id = 'wiselux';
			$cafe24_ftp_pw = 'ASwsux1103!!';
			
			$fc = ftp_connect($cafe24_ftp_host, "21");
			ftp_login($fc, $cafe24_ftp_id, $cafe24_ftp_pw);
			ftp_pasv($fc, true);
            
			$c24ftppath = "/detail/".date('Y');
			@ftp_mkdir($fc, $c24ftppath);
			$c24ftppath = $c24ftppath."/".date('m');
			@ftp_mkdir($fc, $c24ftppath);
			$c24ftppath = $c24ftppath."/".date('d');
			@ftp_mkdir($fc, $c24ftppath);
			ftp_put($fc, $c24ftppath."/".$filename, $rename_newPath, FTP_BINARY);
			ftp_close($fc);

			$sFileInfo .= "&bNewLine=true";
			$sFileInfo .= "&sFileName=";
			$sFileInfo .= "&sFileURL=https://wiselux.co.kr".$c24ftppath.'/'.$filename;
		}

		echo $sFileInfo;
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
?>
