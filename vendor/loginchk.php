<?php
session_cache_expire(60);
session_start();
$pagename = basename($_SERVER['PHP_SELF']);
$s_seller = isset($_SESSION['seller']) ? trim($_SESSION['seller']) : '';
$s_sellerphone = isset($_SESSION['sellerphone']) ? trim($_SESSION['sellerphone']) : '';
$s_birth = isset($_SESSION['birth']) ? trim($_SESSION['birth']) : '';

if(($s_seller == '' || $s_sellerphone == '' || $s_birth == '') && $pagename == 'list.php') exit(header('Location: /vendor/confirm.php'));
if($s_seller != '' && $s_sellerphone != '' && $s_birth != '' && $pagename == 'confirm.php') exit(header('Location: /vendor/list.php'));