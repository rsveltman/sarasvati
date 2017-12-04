<?php

require_once('processaPos.php');
require_once('produzMidi.php');



 $beats = calculaBeats();
 $divisoes = calculaDivisao($beats);
 $canais = criaCanais($divisoes);
 $bpm = getBpm();
 $reps = getReps();
 criaMidi($canais, $bpm, $reps);
 //$dl = urlencode($file);

//header("location: download.php?f=" . $dl);

//header("location: index.php?file=" . basename($file, '.mid'));


?>