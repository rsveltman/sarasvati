<?php

	require_once('calculaPos.php');
	// array de posicoes = $_SESSION['arrayPos'];

	// duracao do clipe = $dur (segundos)

	// bpm = $bpm.      beats por segundo = $bps = $bpm / 60.

	// quantidade de beats (divisoes do canvas) = $dur * $bps.

	// posição Y / 30 = $keyPos.



	// ACHAR A MEDIA DE CADA DIVISAO

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
			$bpm = $_POST["bpm"];	// beats por minuto.
		} else {
			$bpm = 120;
		}
		return $bpm;
	}

	function calculaBeats(){

		$bpm = getBpm();

		if (isset($_POST["duracao"])){
			$dur = $_POST["duracao"];	// duracao do clipe.
		} else {
			$dur = 5;
		}

		$bps = $bpm / 60; // beats por segundo = $bps = $bpm / 60.

		$beats = $dur * $bps; // quantidade de beats (divisoes do canvas).

		return $beats;
	}

	function calculaDivisao($beats){
		$strPosicoes = $_POST['arrayF'];
		if (!$strPosicoes){
			echo "Erro, nao tem registro.";
		}
		$arrayDivisoes = array();

		// Tamanho da divisao em pixels - Largura do canvas (720px) / Qtde de divisoes.
		$divisaoPixels = 720 / $beats;

		for ($i = 0; $i < $beats; $i++){
			$arrayDivisoes[] = array();
		}

		$arrayTudo = explode(',', $strPosicoes);
		$arrayGrupos = array();
		$c = count($arrayTudo);
		for ($i=0; $i < $c; $i = $i+4) {
			$arrayGrupos[] = array($arrayTudo[$i], $arrayTudo[$i+1], $arrayTudo[$i+2], $arrayTudo[$i+3]);
		}
 

		foreach ($arrayGrupos as $p) { // p[0] = channel, p[1] = posicao X, p[2] = nota, p[3] = instrumento.

			$posX = floor($p[1] / $divisaoPixels);

			$arrPorX = array("channel" => $p[0], "nota" => $p[2], "inst" => $p[3]);

			array_push($arrayDivisoes[$posX], $arrPorX);
		}
		return $arrayDivisoes;
	}

	function criaCanais($divs){

		$presentes = array();

		foreach ($divs as $key => $value) {

			$q = count($value);
			if ($q > 0){
				foreach ($value as $k => $v) {

					//$arr = array($v['channel'], $v['nota'], $v['inst'], $key);
					$chan = $v['channel'];
					if (!in_array($chan, $presentes)){
						$presentes[] = $chan;
					}
				}	
			}
		}
		$qcan = count($presentes);
		$can = array_fill(0, $qcan, array());
		$qdivs = count($divs);
		foreach ($can as $k => $c){
			$can[$k] = array_fill(0, $qdivs, NULL);
		}
		
		for ($i = 0; $i < $qcan; $i++){
			$can[$i]['channel'] = $presentes[$i]; 
		
		
			foreach ($divs as $key => $value){

				if (!empty($value)) {
					
					foreach ($value as $k => $v){
						
						if ($can[$i]['channel'] == $v['channel']){
							
								$can[$i][$key][] = ($v['nota'] - 12);
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