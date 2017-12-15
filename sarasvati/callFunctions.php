<?php

require_once('processPos.php');
require_once('prodMidi.php');



 $beats = calcBeats();
 $divs = calcDiv($beats);
 $chans = makeChan($divs);
 $bpm = getBpm();
 $reps = getReps();
 makeMidi($chans, $bpm, $reps);
 //$dl = urlencode($file);

//header("location: download.php?f=" . $dl);

//header("location: index.php?file=" . basename($file, '.mid'));


?>