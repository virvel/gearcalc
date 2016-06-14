<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Multispeed gear calculator
	</title>
	<style type="text/css">
	body {
		background-color: #fff;
		font-family: Helvetica;
		font-size: 10pt;
		color: #111;
	}

	label {
		width: 175px;
		float: left;
		text-align: right;
		margin-right: 1em;
	}

	input {
		font-family: Helvetica;
		font-size: 10pt;
	}

	input[type=text]{
		background-color: #ffffff;
		border-top: 2px solid #ccc;
		border-left: 2px solid #ccc;
		border-right: 2px solid #fffeff;
		border-bottom: 2px solid #fffeff;
		padding: 2px;
		border-radius: 5px;
	}

	table {
		border-spacing: 0;
		float: left;
	}
	td {
		border-left: 1px solid #ccc;
		border-top: 1px solid #ccc;
		padding: 5px;
	}
	#holder {
		float: left;
		width: 500px;
		height: 550px;
	}
	svg {
	}

	footer {
		display: block;
		position: absolute;
		bottom: 10px;
		left: 10px;
	}
	</style>
	<script type="text/javascript" src="raphael-min.js"></script>
	<script type="text/javascript" src="g.raphael-min.js"></script>
	<script type="text/javascript" src="g.bar-min.js"></script>
	<script type="text/javascript" src="g.line-min.js"></script>
</head>
<body>
	<form>
			<label for="wheelsize">Wheel diameter (mm)</label>
			<input type="text" size="3" id="wheelsize" value="622"><br />
			<label for="chainrings">Chainrings</label>
			<input type="text" size="3" id="chaingring1" value="53">
			<input type="text" size="3" id="chaingring2" value="46">
			<input type="text" size="3" id="chaingring3" value="36"><br />
			<label for="sprockets">Sprockets</label>
			<input type="text" size="3" id="sprocket1" value="11">
			<input type="text" size="3" id="sprocket2" value="12">
			<input type="text" size="3" id="sprocket3" value="13">
			<input type="text" size="3" id="sprocket4" value="14">
			<input type="text" size="3" id="sprocket5" value="15">
			<input type="text" size="3" id="sprocket6" value="16">		
			<input type="text" size="3" id="sprocket7" value="17">
			<input type="text" size="3" id="sprocket8" value="18">
			<input type="text" size="3" id="sprocket9" value="19">
			<input type="text" size="3" id="sprocket10" value="20">
			<input type="text" size="3" id="sprocket11" value="21"><br />
			<input type="button" id="button" value="Update" />
	</form>

	<script type="text/javascript">
		var sprockets = [];
		var chainrings = [];
		var ratios = [];
		var wheelsize;
		ratios[0] = new Array(11);
		ratios[1] = new Array(11);
		ratios[2] = new Array(11);

			
		var button = document.getElementById('button');
		button.addEventListener("click", calc);

		function calc() {
			wheelsize = document.getElementById('wheelsize').value;
			for (var i=1;i<=3;i++) {
				chainrings[i-1] = parseFloat(document.getElementById('chaingring'+i).value);
			}
			for (var i=1;i<=11;i++) {
				sprockets[i-1] = parseFloat(document.getElementById('sprocket'+i).value);
				for (var j=1;j<=3;j++) {
					ratios[j-1][i-1] = Math.round((chainrings[j-1]/sprockets[i-1])*100)/100;
				}
			}
			createTable();
		}

		function createTable() {
			var table = document.getElementById('table');
			if (document.getElementById('table')) {
				document.body.removeChild(table);
			}
			var table = document.createElement('table');
			table.setAttribute("id", "table")
			document.body.appendChild(table);

			var tr ,td, th;
			tr = document.createElement('tr');
			tr.setAttribute("id", "thtr");
			th = document.createElement('th');
			tr.appendChild(th);

			for (var i = 0; i < 3; i++) {
				th = document.createElement('th');
				th.innerHTML = chainrings[i];
				tr.appendChild(th);
			}
				table.appendChild(tr);

			for (var i = 0; i < 11; i++) {
				tr = document.createElement('tr');
				tr.setAttribute("id", "tr"+i);
				table.appendChild(tr);
				th = document.createElement('th');
				th.innerHTML = sprockets[i];
				tr.appendChild(th);
				for (var j = 0; j < 3; j++) {
					td = document.createElement('td');
					td.innerHTML = ratios[j][i];
					tr.appendChild(td);
				}
			}
				document.body.removeChild(document.getElementById("holder"));
				drawTable();

		}


		function drawTable() {
			var cadArray = [];
			cadArray[0] = new Array();
			cadArray[1] = new Array();
			cadArray[2] = new Array();
			for (var i = 11; i >= 0; i--) {
				cadArray[0][11-i] = Math.round(((((wheelsize*Math.PI*(ratios[0][0]))/1000000)*((11-i+1)*10))*60)*100)/100;
				cadArray[1][11-i] = Math.round(((((wheelsize*Math.PI*(ratios[1][0]))/1000000)*((11-i+1)*10))*60)*100)/100;
				cadArray[2][11-i] = Math.round(((((wheelsize*Math.PI*(ratios[2][0]))/1000000)*((11-i+1)*10))*60)*100)/100;
			}
			console.log(cadArray);

			var holder = document.createElement('div');
			holder.setAttribute("id", "holder");
			document.body.appendChild(holder);
			var r = Raphael("holder"),
			fin2 = function () {
		        var y = [], res = [];
		        for (var i = this.bars.length; i--;) {
		            y.push(this.bars[i].y);
		            res.push(this.bars[i].value || "0");
		        }
		        this.flag = r.popup(this.bars[0].x, Math.min.apply(Math, y), res.join(", ")).insertBefore(this);
		    },
		    fout2 = function () {
		        this.flag.animate({opacity: 0}, 300, function () {this.remove();});
		    };
            var c = r.barchart(35, 10, 400, 200, [ratios[0], ratios[1], ratios[2]], {stacked: true, colors: ["#121621", "#425da5", "#a0b5f5"], type: "round"}).hoverColumn(fin2, fout2);
           	var lines = r.linechart(35, 210, 400, 200, [10, 20, 30, 40 ,50, 60, 70, 80, 90, 100, 110, 120], [cadArray[0], cadArray[1], cadArray[2]], { nostroke: false, colors: ["#a0b5f5","#425da5","#121621"], shade: true, axis: "0 0 1 1", symbol: "circle", smooth: false, axisxstep: 11 }).hoverColumn(function () {
                    this.tags = r.set();

                    for (var i = 0, ii = this.y.length; i < ii; i++) {
                        this.tags.push(r.tag(this.x, this.y[i], this.values[i], 160, 10).insertBefore(this).attr([{ fill: "#fff" }, { fill: this.symbols[i].attr("fill") }]));
                    }
                }, function () {
                    this.tags && this.tags.remove();
                });

		}

	</script>
	<div id="holder"></div>
	<footer>Graphs made using <a href="http://g.raphaeljs.com/">g.Raphaël.js</a></footer>
</body>
</html>