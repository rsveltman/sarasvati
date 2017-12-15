<?php


	// duration of clip = $dur (seconds)

	// bpm = $bpm.      beats per second = $bps = $bpm / 60.

	// number of beats (divide canvas by) = $dur * $bps.

	// position Y / 30 = $keyPos.



	function getReps(){
		if (isset($_POST["reps"])){
			$reps = $_POST["reps"];
		} else {
			$reps = 1;
		}
		return $reps;
	}

	function getBpm(){
		if (isset($_POST["bpm"])){
			$bpm = $_POST["bpm"];	
		} else {
			$bpm = 120;
		}
		return $bpm;
	}

	function calcBeats(){

		$bpm = getBpm();

		if (isset($_POST["duration"])){
			$dur = $_POST["duration"];
		} else {
			$dur = 5;
		}

		$bps = $bpm / 60;

		$beats = $dur * $bps; // divide the canvas

		return $beats;
	}

	function calcDiv($beats){
		$strPos = $_POST['arrayF'];
		if (!$strPos){
			echo "Erro, nao tem registro.";
		}
		$arrayDivs = array();

		// Size of each slice in pixels = Canvas width / divs.
		$divPixels = 720 / $beats;

		for ($i = 0; $i < $beats; $i++){
			$arrayDivs[] = array();
		}

		$arrayAll = explode(',', $strPos);
		$arrayGroups = array();
		$c = count($arrayAll);
		for ($i=0; $i < $c; $i = $i+4) {
			$arrayGroups[] = array($arrayAll[$i], $arrayAll[$i+1], $arrayAll[$i+2], $arrayAll[$i+3]);
		}
 

		foreach ($arrayGroups as $p) { // p[0] = channel, p[1] = posicao X, p[2] = nota, p[3] = instrumento.

			$posX = floor($p[1] / $divPixels);

			$arrPorX = array("channel" => $p[0], "key" => $p[2], "inst" => $p[3]);

			array_push($arrayDivs[$posX], $arrPorX);
		}
		return $arrayDivs;
	}

	function makeChan($divs){

		$arrCount = array();

		foreach ($divs as $key => $value) {

			$q = count($value);
			if ($q > 0){
				foreach ($value as $k => $v) {

					//$arr = array($v['channel'], $v['key'], $v['inst'], $key);
					$chan = $v['channel'];
					if (!in_array($chan, $arrCount)){
						$arrCount[] = $chan;
					}
				}	
			}
		}
		$qcan = count($arrCount);
		$can = array_fill(0, $qcan, array());
		$qdivs = count($divs);
		foreach ($can as $k => $c){
			$can[$k] = array_fill(0, $qdivs, NULL);
		}
		
		for ($i = 0; $i < $qcan; $i++){
			$can[$i]['channel'] = $arrCount[$i]; 
		
		
			foreach ($divs as $key => $value){

				if (!empty($value)) {
					
					foreach ($value as $k => $v){
						
						if ($can[$i]['channel'] == $v['channel']){
							
								$can[$i][$key][] = ($v['key'] - 12); // subtract 12 because values are different across libs
								$can[$i]['instr'] = $v['inst'];
						}
					}
				}
			
			}
		}
		
		
		foreach ($can as $key => $value){
			
			foreach ($value as $k => $v){
				if (is_array($v)){
					$p = array();
					foreach ($v as $va){
						if (!in_array($va, $p)){
							$p[] = $va;
						}
							
					}
					$can[$key][$k] = $p;
				}
			}
				
		}
		

		return $can;
	}
?>