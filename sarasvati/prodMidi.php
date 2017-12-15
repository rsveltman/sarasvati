<?php
	error_reporting(E_ALL);



	require('./midi_class_v178/classes/midi.class.php');

	function removeKey($arr, $key){
		$n = array_diff($arr, array($key));
		return $n;
	}

	function clearKeys(){
		$n = array();
		return $n;
	}

	function makeMidi($channels, $bpm, $reps){
		$midi = new Midi();
		$save_dir = './tmp/';
		srand((double)microtime()*1000000);
		$file = $save_dir.rand().'.mid';
		
		$midi->open(480); //timebase=480, quarter note=120
		$midi->setBpm($bpm);
		$bps = $bpm / 60;
		

		$tfinal = 0;

		$durationOfBeat = floor(1705 / $bps); // 1705 is 1 second




		foreach ($channels as $key => $value) { // each channel

			$prevtime = 0;
			
			$ch = ($key+5);
			
			$inst= $value["instr"]; // channel instrument
			
			$vol = 127;
			$time = 0; // tracking time - step 120
			$tn = $midi->newTrack() - 1;
			$keysOn = clearKeys();

			$midi->addMsg($tn, "0 PrCh ch=$ch p=$inst");


			// reps = loops
			for ($r=0; $r < $reps; $r++){

					foreach ($value as $position => $posValue) { // iterate through positions in channel
					
						
						if ($position === "instr" || $position === "channel"){
							continue;
						}

						if (empty($posValue)){ // silence

							if (!empty($keysOn)){ // active keys

								// silence each active key
								foreach ($keysOn as $key => $n) {
									$midi->addMsg($tn, "$time Off ch=$ch n=$n v=$vol");
								}
								$keysOn = clearKeys();
							}
						} else { // key present

							$keysEnd = array_diff($keysOn, $posValue); // active keys not in position

							if ($keysEnd){
								foreach ($keysEnd as $k => $v) {
									$midi->addMsg($tn, "$time Off ch=$ch n=$v v=$vol"); // silence no longer active keys
									$keysOn = removeKey($keysOn, $v); // remove from keysOn
								}
							}
							foreach ($posValue as $k => $key) {
						
								if(!in_array($key, $keysOn)){
									$keysOn[] = $key; // add active key
									

									$midi->addMsg($tn, "$time On ch=$ch n=$key v=$vol"); // write On msg
								} else {
										//$midi->addMsg($tn, "$time On ch=$ch n=$posValue v=$v"); // on again or continue prev
								}
							}
						}
						$time = (($position+1) * $durationOfBeat) + $prevtime;
					}
					$prevtime = $time;

			} // end of reps
			$tfinal = ($tfinal > $tn ? $tfinal : $tn);
			$midi->addMsg($tfinal, "$time Meta TrkEnd");

			
		} // end of channels
		$midi->saveMidFile($file, 0666);
		// echo'<hr /><pre>'.$midi->getTxt().'</pre>'; // print midi
		//return $file;
		
		$destFilename  = "out.mid";
		
		$midi->downloadMidFile($destFilename); // start download
	}
?>