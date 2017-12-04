<?php
session_start();
if (!isset($_SESSION['arrayPos'])){
	$_SESSION['arrayPos'] = array();
}

if (isset($_REQUEST["pos"])){
	$pos = $_REQUEST["pos"];
	$arr = explode(',', $pos);
	array_push($arr, $_REQUEST["cor"]);
	array_push($arr, $_REQUEST["inst"]);
	array_push($_SESSION['arrayPos'], $arr);	
}

if (isset($_GET["l"]) && $_GET["l"] == "sim"){
	session_destroy();
}

?>