<?php
function encrypt($string, $key) {
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result .= $char;
	}
	return base64_encode($result);
}

function decrypt($string, $key) {
	$result = '';
	$string = base64_decode($string);
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result .= $char;
	}
	return $result;
}

function stripos_array($haystack, $needles)
{
	if (is_array ($needles)) {
		foreach ($needles as $str) {
			$pos = stripos($haystack, $str);

			if ($pos !== false) {
				return $pos;
			}
		}
		return false;
	} else {
		return stripos($haystack, $needles);
	}
}

function quotationMagic($str)
{
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

function doMsgLocation($message, $location_url='')
{
	$msg = "
	<html>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<script>
	alert(\"$message\");";

	if($location_url){
		$msg .= "document.location.href='$location_url';</script></html>";
		echo $msg;
		exit();
	}else{
		$msg .= "</script></html>";
		echo $msg;
	}
}

function doMsgLocationHB($message)
{
    echo 
	"<html>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<script>
	alert(\"$message\");;
    history.back();
    </script>
    </html>";
}

function getNewFilename($realFileName, $tmpFileName)
{
	$returnVal = false;
	$fileExt = substr($realFileName, strrpos($realFileName, ".") + 1); //파일 확장자
	$fileExt = strtolower($fileExt);

	if(file_exists($tmpFileName))
	{
		$newFileName = uniqid().'_'.date('YmdHis');
		if(in_array($fileExt, explode(',',IMAGE_FILE_EXT)))
		{
			list($width, $height) = getimagesize($tmpFileName);
			$newFileName = $newFileName.'_'.$width.'_'.$height;
		}

		$newFileName = $newFileName.'.'.$fileExt;
		$returnVal = $newFileName;
	}

	return $returnVal;
}

function encode_substr($str,$start,$end,$encoding) {
	$str_orgin = $str;
	$str_strip = strip_tags($str);
	$str = $str_strip;
	mb_internal_encoding($encoding);
	$str_len = mb_strlen($str);
	if ($end<$str_len) {$str = trim(mb_substr($str,$start,$end)).'..';}
	else {$str = mb_substr($str,$start,$end);}

	return $str;
}

function getQueryCount($query)
{
	global $dbconn;
	$returnResultValue = 0;
	if ($dbconn) {
		$result = mysqli_query($dbconn,$query);
		$returnResultValue = mysqli_fetch_row($result);
		$returnResultValue = $returnResultValue[0];
		mysqli_free_result($result);
	}
	return $returnResultValue;
}

function getQueryResult($query)
{
	global $dbconn;
	$returnResultArray = array ();
	if ($dbconn && $query) {
		$result = mysqli_query($dbconn,$query);
		if ($result) {
			if(mysqli_num_rows($result) > 0){
				while ($qry_result_row = $result->fetch_assoc()) {
					array_push($returnResultArray,$qry_result_row);
				}
			}
			mysqli_free_result($result);
		}
	}
	return $returnResultArray;
}
