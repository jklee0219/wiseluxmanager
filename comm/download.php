<?php
$filepath = filter_input(INPUT_GET, 'fp');
$realname = filter_input(INPUT_GET, 'rn');
$filepath = $_SERVER['DOCUMENT_ROOT'].$filepath;
$realname = urldecode($realname);
if(file_exists($filepath)){
	$filesize = filesize($filepath);
	$path_parts = pathinfo($filepath);
	$filename = $path_parts['basename'];
	$extension = $path_parts['extension'];

	header("Pragma: public");
	header("Expires: 0");
	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: attachment; filename=\"$realname\"");
	header("Content-Length: $filesize");

	ob_clean();
	flush();
	readfile($filepath);
}else{
?>
	<script>
	alert('해당 파일은 존재하지 않습니다.');
	history.back(-1);
	</script>
<?php
}
?>
