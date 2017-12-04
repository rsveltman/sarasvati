<?php
session_start();
if (isset($_GET['f'])){
	
	require('./midi_class_v178/classes/midi.class.php');
	
	//$srcFile = $_GET['f'];
	$srcFile = basename($_GET['f'],'.mid') . '.mid';
	

	
	$destFilename  = 'output.mid';
	
	$midi = new Midi();
	$midi->downloadMidFile($destFilename, $srcFile);
}
?>