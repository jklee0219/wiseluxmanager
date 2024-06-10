<?php
if($this->session->userdata('ADM_LOGIN') != LOGIN_SESSION_VALUE && stripos(PAGENAME, "login")  === false ) exit(header('Location: /admn/login'));
if($this->session->userdata('ADM_LOGIN') == LOGIN_SESSION_VALUE && PAGENAME == "login") exit(header('Location: /admn/'));

if($_SERVER['PHP_SELF'] == "/admn/index.php") exit(header('Location: /admn/purchase'));
