<?php
	error_reporting(E_ALL);



	require('./midi_class_v178/classes/midi.class.php');

	function tiraNota($arr, $nota){
		$n = array_diff($arr, array($nota));
		return $n;
	}

	function limpaNotas(){
		$n = array();
		return $n;
	}

	function criaMidi($canais, $bpm, $reps){
		$midi = new Midi();
		$save_dir = './tmp/'; // colocar endereco absoluto ou n
		srand((double)microtime()*1000000);
		$file = $save_dir.rand().'.mid';
		
		$midi->open(480); //timebase=480, quarter note=120
		$midi->setBpm($bpm);
		$bps = $bpm / 60; // beats por segundo = $bps = $bpm / 60.
		

		$tfinal = 0;

		$duracaoDoBeat = floor(1705 / $bps); // 1705 e' o valor de um segundo. 




		foreach ($canais as $key => $value) { // itera pelos canais

			$prevtime = 0;
			
			$ch = ($key+5);
			
			$inst= $value["instr"]; // instrumento do canal
			// echo"<br>instrumento: " . $inst;
			$vol = 127; // volume temporario
			$time = 0; // marcacao de tempo inicial -- depois cresce ao passo de 120
			$tn = $midi->newTrack() - 1;
			$notasOn = limpaNotas();

			$midi->addMsg($tn, "0 PrCh ch=$ch p=$inst");


			// repeticoes
			for ($r=0; $r < $reps; $r++){
				// echo"<br>rep= " . $r ;

					foreach ($value as $posicao => $oitava) { // itera pelas posicoes do canal
					
						
						if ($posicao === "instr" || $posicao === "channel"){
							// echo"<br> posicao eh instr ou channel";
							continue;
						}

						if (empty($oitava)){ // silencio
						// echo"<br>oitava vazia na posicao " . $posicao;

							if (!empty($notasOn)){
								// echo"<br>tinha notas no notasOn";

								// cria msg de off para cada nota em notasOn
								foreach ($notasOn as $key => $n) {
									$midi->addMsg($tn, "$time Off ch=$ch n=$n v=$vol");
									// echo"<br>silenciando notas na posicao " . $posicao;
									// echo"<br>" . $n . " silenciada no tempo " . $time;
								}
								$notasOn = limpaNotas();
								// echo"<br> notasOn limpa";
							}
						} else { // tem nota na posicao
						// echo"<br>tem nota na posicao, tempo: " . $time;

							$notasFim = array_diff($notasOn, $oitava); // encontra notas ativas que nao estao nesta posicao

							if ($notasFim){
								// echo"<br>tem notas em notasOn que nao estao nesta posicao";
								foreach ($notasFim as $k => $v) {
									$midi->addMsg($tn, "$time Off ch=$ch n=$v v=$vol"); // msg de Off para notas que estavam ativas e nao estao mais
									$notasOn = tiraNota($notasOn, $v);
									// echo"<br>silenciando nota " . $v . " no tempo " . $time;
									// echo"<br>tirando nota de notasOn";
								}
							}
							foreach ($oitava as $k => $nota) {
								// echo"<br>nota na oitava: " . $nota . " posicao: " . $posicao;
								if(!in_array($nota, $notasOn)){
									$notasOn[] = $nota;
									// echo"<br>colocando nota no notasOn";
									

									$midi->addMsg($tn, "$time On ch=$ch n=$nota v=$vol"); // cria msg de On para nota
									// echo"<br>msg de on para nota " . $nota . " no tempo " . $time;
								} else {
									// echo"<br>nota ja estava no array";
										//$midi->addMsg($tn, "$time On ch=$ch n=$oitava v=$v");
								}
							}
						}
						$time = (($posicao+1) * $duracaoDoBeat) + $prevtime;
						// echo"<br>novo tempo: " . $time . " --- prevtime: " . $prevtime;
					}
					$prevtime = $time;
					// echo"<br>novo prevtime: " . $prevtime;

			} // fim das repeticoes
			$tfinal = ($tfinal > $tn ? $tfinal : $tn);
			$midi->addMsg($tfinal, "$time Meta TrkEnd");

			
		} // fim dos canais
		$midi->saveMidFile($file, 0666);
		// echo'<hr /><pre>'.$midi->getTxt().'</pre>';
		//return $file;
		
		$destFilename  = "out.mid";
		
		$midi->downloadMidFile($destFilename);
	}
?>