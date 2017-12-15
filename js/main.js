var player;

var arrayKeys = [];
var instr = 1;
var channel = 0;

var piano = 1;
var guitar = 26;
// var flute = 75;
var drum = 116;
var horn = 60;

var color = 5;

var delay = 0; // play one note every quarter second
var velocity = 127; // how hard the note hits

window.onload = function () {
  MIDI.loadPlugin({
    //soundfontUrl: "http://gleitz.github.io/midi-js-soundfonts/FluidR3_GM/", // FAIL
    soundfontUrl: '../sarasvati/soundfont/',
    instruments: ["bright_acoustic_piano", "electric_guitar_jazz", "french_horn", "taiko_drum" ], // 1, 26, 60, 116  //  "pan_flute" - 75
    onsuccess: function () {
        //var channel = 0; // MIDI allows for 16 channels, 0-15
            // middle C (C4) according to General MIDI
        // play the note
        MIDI.programChange(0, piano);
        MIDI.programChange(1, guitar);
        MIDI.programChange(2, horn);
        MIDI.programChange(10, drum); // channel 10 reserved for percussion
        MIDI.setVolume(0, 127);
        MIDI.setVolume(1, 127);
        MIDI.setVolume(2, 127);
        MIDI.setVolume(10, 127);
    }
  });
};

function getChannel(ins){
  switch (ins) {
    case 1:
      channel = 0;
      break;
    case 26:
      channel = 1;
      break;
    case 60:
      channel = 2;
      break;
    case 116:
      channel = 10;
      break;
  }
}

function play(note){
  var n = note;
	//1st note = 21 (A0)
	//last note = 108 (C8)

	MIDI.noteOn(channel, n, velocity, delay);
	MIDI.noteOff(channel, n, delay + 0.85);
}


// canvas

    var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');


    var painting = document.getElementById('paint');
    var paint_style = getComputedStyle(painting);
    canvas.width = parseInt(paint_style.getPropertyValue('width'));
    canvas.height = parseInt(paint_style.getPropertyValue('height'));
    canvas.style.border = '1px solid white';

    var mouse = {x: 0, y: 0};
    var touch = false;
    var instr = "0";

    ctx.strokeStyle = "#76B03C";
  
    // Detect touch on mobile
      canvas.addEventListener("touchstart", function (e) {
              touch = true;
              mouse = getTouchPos(canvas, e);
              
              ctx.beginPath();
              ctx.moveTo(mouse.x, mouse.y);
          
              canvas.addEventListener('touchmove', onPaint, false);
              
            }, false);
            
      canvas.addEventListener("touchend", function (e) {
              canvas.removeEventListener('touchmove', onPaint, false);
            }, false);
            
      canvas.addEventListener("touchmove", function (e) {
              mouse = getTouchPos(canvas, e);
            
            }, false);
  
    
    // Position of touch relative to canvas
      function getTouchPos(canvasDom, touchEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
          x: touchEvent.touches[0].pageX,
          y: touchEvent.touches[0].pageY //  - rect.x/y
        };
      }
    
    
    // Position of pointer relative to canvas
      function getMousePos(canvasDom, mouseEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
          x: mouseEvent.pageX - rect.left,
          y: mouseEvent.pageY - rect.top
        };
      }
    
  
    // Prevent normal touch behaviour on canvas
      document.body.addEventListener("touchstart", function (e) {
        if (e.target == canvas) {
          e.preventDefault();
        }
      }, false);
      document.body.addEventListener("touchend", function (e) {
        if (e.target == canvas) {
          e.preventDefault();
        }
      }, false);
      document.body.addEventListener("touchmove", function (e) {
        if (e.target == canvas) {
          e.preventDefault();
        }
      }, false);
      
  
    
    // Detect mouse movement
      canvas.addEventListener('mousemove', function(e) {
        mouse = getMousePos(canvas, e);
        
      }, false);
      
      canvas.addEventListener('mousedown', function(e) {
          mouse = getMousePos(canvas, e);
          ctx.beginPath();
          ctx.moveTo(mouse.x, mouse.y);
          
          canvas.addEventListener('mousemove', onPaint, false);
      }, false);
       
      canvas.addEventListener('mouseup', function() {
          canvas.removeEventListener('mousemove', onPaint, false);
      }, false);
    

    ctx.lineWidth = 2;
    ctx.lineJoin = 'miter';
    ctx.lineCap = 'butt';

    function getInstr() {
        var i = document.getElementById('instr');
        instr = Number(i.options[i.selectedIndex].value);
        getChannel(instr);
    }
    
    function getColor(hex, c) {
        color = c;
        ctx.strokeStyle = hex;
    }

    var onPaint = function() {
        ctx.lineTo(mouse.x, mouse.y);
        ctx.stroke();
        calcKey(mouse.x, mouse.y);
    };

    function calcKey(x,y){
    	var n = Math.floor(y/30);
    	var note = (n+24) + (color * 12);
      arrayKeys.push([channel, x, note, instr]);
    	play(note);
    }

    function send(){
    	document.getElementById("arrayFinal").value = arrayKeys;
      document.getElementById("getArray").submit();
    }
    
    function clearCanvas(){
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        arrayKeys = [];
    }