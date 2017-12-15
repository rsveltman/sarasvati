<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <!-- midi.js css -->
  <link href="./css/MIDIPlayer.css" rel="stylesheet" type="text/css" />
  <link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- polyfill -->
	<script src="../inc/shim/Base64.js" type="text/javascript"></script>
	<script src="../inc/shim/Base64binary.js" type="text/javascript"></script>
	<script src="../inc/shim/WebAudioAPI.js" type="text/javascript"></script>
  <script src="../inc/shim/WebMIDIAPI.js" type="text/javascript"></script>
  <!-- jasmid package -->
  <script src="../inc/jasmid/stream.js"></script>
  <script src="../inc/jasmid/midifile.js"></script>
  <script src="../inc/jasmid/replayer.js"></script>
	<!-- midi.js package -->
	<script src="../js/midi/audioDetect.js" type="text/javascript"></script>
	<script src="../js/midi/gm.js" type="text/javascript"></script>
	<script src="../js/midi/loader.js" type="text/javascript"></script>
	<script src="../js/midi/plugin.audiotag.js" type="text/javascript"></script>
	<script src="../js/midi/plugin.webaudio.js" type="text/javascript"></script>
	<script src="../js/midi/plugin.webmidi.js" type="text/javascript"></script>
  <script src="../js/midi/plugin.audiotag.js" type="text/javascript"></script>
  <script src="../js/midi/plugin.webaudio.js" type="text/javascript"></script>
  <script src="../js/midi/plugin.webmidi.js" type="text/javascript"></script>
  <script src="../js/midi/player.js" type="text/javascript"></script>
  <script src="../js/midi/synesthesia.js" type="text/javascript"></script>
	<!-- utils -->
	<script src="../js/util/dom_request_xhr.js" type="text/javascript"></script>
	<script src="../js/util/dom_request_script.js" type="text/javascript"></script>
  
  

	    <style>
      body {
        margin: 0px;
        padding: 0px;
      }

      #myCanvas {
        width: 720px; 
        height: 360px;
      }

      .options {
        margin-top: 1px;
        display: inline-block;
        position: fixed;
        font-size: 14px;
      }

      .sel {
        display: inline-block;
        margin-left: 400px;
      }

      .clear {
        display: inline-block;
        font-size: 14px;
        margin-left: 10px;
      }

      .dev {
        display: inline;
        position: relative;
        float: right;
      }

      .container {
          position: fixed;
          padding: 0;
          margin: 0;
          width: 780px;
      }

      .colors {
        display: block;
        float: left;
        width: 26px;
      }

      .color {
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgb(0, 0, 0);
        display: block;
      }

      .c0 {
        background: #3C76B0;
      }

      .c1 {
        background: #76B03C;
      }

      .c2 {
        background: #B0B03C;
      }

      .c3 {
        background: #B0763C;
      }

      .c4 {
        background: #B03C3C;
      }

      .c5 {
        background: #B03C76;
      }

      .c6 {
        background: #763CB0;
      }

      .fill-div {
        display: block;
        height: 100%;
        width: 100%;
        text-decoration: none;
      }
    </style>
</head>
<body>

  <div class="container">
    
    <div class="colors">

        <div class="color c0"><a href="#" id="#3C76B0" class="fill-div" onClick="getColor(this.id, 0)"></a></div>

        <div class="color c1"><a href="#" id="#76B03C" class="fill-div" onClick="getColor(this.id, 1)"></a></div>

        <div class="color c2"><a href="#" id="#B0B03C" class="fill-div" onClick="getColor(this.id, 2)"></a></div>

        <div class="color c3"><a href="#" id="#B0763C" class="fill-div" onClick="getColor(this.id, 3)"></a></div>

        <div class="color c4"><a href="#" id="#B03C3C" class="fill-div" onClick="getColor(this.id, 4)"></a></div>

        <div class="color c5"><a href="#" id="#B03C76" class="fill-div" onClick="getColor(this.id, 5)"></a></div>

        <div class="color c6"><a href="#" id="#763CB0" class="fill-div" onClick="getColor(this.id, 6)"></a></div>

      </div>
      
    	<div id="paint" style="width: 720px; height: 360px; margin: 0 auto; background-color: darkslategrey;">
    		<canvas id="myCanvas"></canvas>
  	  </div>
      <div>
        <div class="form-group options">
            <form id="getArray" action="callFunctions.php" method="post">
              <label for="bpm">BPM</label>
              <input type="text" name="bpm" size="3" value="180">
              
              <label for="duracao">Dur: </label>
              <input type="text" name="duration" size="3" value="5">
  
              <label for="repeticoes">Repetições</label>
              <input type="text" name="reps" size="3" value="4">
  
              <input type="hidden" id="arrayFinal" name="arrayF" value="">
              
              <input type="button" name="publish" value="Enviar" onClick="send()">
            </form>
        </div>
  
        <div class="sel">
          <div class="form-group">
              <select class="form-control" style="height:29px; font-size:9px;" id="instr" onchange="getInstr()">
                <option value="1">Piano</option>
                <option value="60">Sopro</option>
                <option value="26">Guitarra</option>
                <option value="116">Percussão</option>
              </select>
          </div>
        </div>
      

        <div class="clear">
          <button onclick="clearCanvas()">Limpar</button>
        </div>

        <!--<div class="clear">-->
        <!--  <button onclick="< ?php //echo "MIDIjs.play('tmp/" . $_GET['file'] . ".mid')"; ?>" >Tocar</button>-->
        <!--</div>-->
          
      </div>
  </div>

<script src="../js/main.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<!--<script type='text/javascript' src='http://www.midijs.net/lib/midi.js'></script>-->
</body>
</html>