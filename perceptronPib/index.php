<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<title>Perceptron simple</title>
</head>
<body>
	<div class="container">
		<h1>Entrenar Neurona</h1>
		<br>
		<div class="row">
			<div class="col-md-12">
				<button onclick="entrenar();" class="btn btn-success">Entrenar</button>
			</div>
		</div>
		<hr>
		<div id="variables" style="display: none;">
			<h1>Probar neurona</h1>
			<p id="ecuacion"></p>
			<div class="row">
				<div class="col-md-2">
					<label>C</label>
					<br>
					<input type="text" class="form-control" name="c" id="c">
				</div>
				<div class="col-md-2">
					<label>I</label>
					<br>
					<input type="text" class="form-control" name="i" id="i">
				</div>
				<div class="col-md-2">
					<label>G</label>
					<br>
					<input type="text" class="form-control" name="g" id="g">
				</div>
				<div class="col-md-2">
					<label>X</label>
					<br>
					<input type="text" class="form-control" name="x" id="x">
				</div>
				<div class="col-md-2">
					<label>M</label>
					<br>
					<input type="text" class="form-control" name="m" id="m">
				</div>
				<div class="col-md-2">
					<br>
					<button onclick="probar();" class="btn btn-success">Calcular</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h1 id="pib"></h1>
				</div>
			</div>
		</div>
		<div id="respuesta"></div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</html>
<script type="text/javascript">
	var data;
	function entrenar() {
		var json = {"w1":0.5,"w2":0.9,"w3":1,"w4":0.7,"w5":0.9,"e":0.1,"theta":1};
		$.post('http://calculo_pip.com.devel/api/entrenar_pib',json).done(function(resp) {
			data = resp;
			if (resp.code==200) {
				$("#variables").show();
				$("#ecuacion").html("Ecuacion: "+resp.ecuacion);
			}
		}).fail(function(err) {
			console.log(err)
		})
		
	}
	function probar() {
		var json = {
			"w1":data.w1,
			"w2":data.w2,
			"w3":data.w3,
			"w4":data.w4,
			"w5":data.w5,
			"e":data.e,
			"theta":data.theta,
			"c": $("#c").val(),
			"i": $("#i").val(),
			"g": $("#g").val(),
			"x": $("#x").val(),
			"m": $("#m").val()
		};
		console.log(json);
		$.post('http://calculo_pip.com.devel/api/calcular_pib',json).done(function(resp) {
			console.log(resp);
			if (resp.code==200) {
				$("#pib").html("PIB: "+resp.pib);
			}
		}).fail(function(err) {
			console.log(err)
		})
	}
</script>