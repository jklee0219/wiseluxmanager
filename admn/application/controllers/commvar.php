<?php
//현재 페이지명(파일명)
define('PAGENAME', basename($_SERVER['PHP_SELF'], '.php'));

//로그인되었을때 세션 값
define('LOGIN_SESSION_VALUE', 'Login_Ok');

//공통 copyright 문구
define('COPYRIGHT', '');

//파일 업로드 제한 용량(MB)
define('LIMIT_FILE_SIZE', 1);

//텍스트에디터 이미지 가로 최대 사이즈
define('TXTEDITOR_IMAGE_RESIZE_WIDTH', 650);

//메인배너관리 이미지 사이즈
define('SCREENEDIT_IMAGE_WIDTH', 1000);
define('SCREENEDIT_IMAGE_HEIGHT', 500);

//이미지 확장자 제한
define('IMAGE_FILE_EXT', 'gif,png,bmp,jpg,jpeg');

//첨부파일 확장자 제한
define('ATTACH_FILE_EXT', 'gif,png,bmp,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,pdf,hwp,zip');

//첨부파일 업로드 폴더
define('ATTACH_FILE_PATH', '/attach/'.date('Y').'/'.date('m').'/'.date('d').'/');