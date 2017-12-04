
      var canvas = document.getElementById('myCanvas');
      var ctx = canvas.getContext('2d');
      var arrpos = [];


      var painting = document.getElementById('paint');
      var paint_style = getComputedStyle(painting);
      canvas.width = parseInt(paint_style.getPropertyValue('width'));
      canvas.height = parseInt(paint_style.getPropertyValue('height'));
      canvas.style.border = '1px solid white';

      var mouse = {x: 0, y: 0};

      var instr = "0";

      var cor = 5;
      var corEscolhida = "#76B03C";
       
      canvas.addEventListener('mousemove', function(e) {
        mouse.x = e.pageX - this.offsetLeft;
        mouse.y = e.pageY - this.offsetTop;
      }, false);

      ctx.lineWidth = 2;
      ctx.lineJoin = 'miter';
      ctx.lineCap = 'butt';


      function getColor2(hex, cor) {
          corEscolhida = hex;
          cor = cor;
          ctx.strokeStyle = corEscolhida;
      }

      function getInstrumento() {
          var i = document.getElementById('instr');
          instr = i.options[i.selectedIndex].value;
      }
       
      canvas.addEventListener('mousedown', function(e) {
          ctx.beginPath();
          ctx.moveTo(mouse.x, mouse.y);
          
       
          canvas.addEventListener('mousemove', onPaint, false);
      }, false);
       
      canvas.addEventListener('mouseup', function() {
          canvas.removeEventListener('mousemove', onPaint, false);
      }, false);
       
      var onPaint = function() {
      	mandaPos(mouse.x, mouse.y);
        ctx.lineTo(mouse.x, mouse.y);
        ctx.stroke();
      };

      function mandaAjax(url, funcao) {

      	var xhttp;
      	xhttp = new XMLHttpRequest();
      	xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
          	funcao(this);
          }
        };
        xhttp.open("GET", url, true);
        xhttp.send();
      }

      function mostraPos(){
      }

      function mandaPos(x,y){
      	url = "calculaPos.php?pos="+x+","+y+"&cor="+cor+"&inst="+instr;
      	mandaAjax(url, addArrPos);
      }






      function addArrPos(resposta){
      	arrpos.push(resposta.responseText);

      }




      function componentToHex(c) {
          var hex = c.toString(16);
          return hex.length == 1 ? "0" + hex : hex;
      }

      function dec2hex(r, g, b) {
          return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
      }

      function coletaPixels(){
        var arrPix = [];

        // itera por todos os pixels do canvas
        for (var y = 0; y < canvas.height; y++) { // itera pelo y
          for (var x = 0; x < canvas.width; x++) { // itera pelo x
            var imgData = ctx.getImageData(x, y, 1, 1);
            if (imgData.data[3] > 0){ // pixel nao e transparente
              var color = dec2hex(imgData.data[0], imgData.data[1], imgData.data[2]);
              var arr = [String(x), String(y), color];
              arrPix.push(arr);
            }
          }
        }
        document.getElementById("arrPix").value = arrPix;
        document.getElementById("coletaPixels").submit();
      }

      function limpa(){
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        limpar();
      }

      function limpar(){
      	mandaAjax("calculaPos.php?l=sim", mostraPos);
      }

