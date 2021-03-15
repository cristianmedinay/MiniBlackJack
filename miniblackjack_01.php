<!DOCTYPE html>
<html>
<?php

var_export($_POST);
$path = "https://magicomagico.com/gilbertron/recursos/";

//es la primera vez
$aCartasSalidas = array();
$aCartasSalidasBanca = array();
$sumaCartasJugador = 0;
$sumaCartasJugadorAux = 0;
$sumaCartasBanca = 0;
$bancaSePaso = 0;
$ganaJugador = 0;

if(Count($_POST)==0){
	$naipe1 = darCarta();
	$naipe2 = darCarta();
	$aCartasSalidas[] = $naipe1;
	$aCartasSalidas[] = $naipe2;
	
	
	$naipe1Banca = darCarta();
	$aCartasSalidasBanca[] = $naipe1Banca;
	$_POST['darCartaActivado'] = 0;
	$sumaCartasJugador = sumarCartas($aCartasSalidas);
	$sumaCartasBanca = sumarCartas($aCartasSalidasBanca);
}else{

	$aCartasSalidas = explode(",",$_POST['manoJugador']);
	$aCartasSalidasBanca = explode(",",$_POST['manoBanca']);

	if($_POST['proceso']=="PEDIR"){
		$aCartasSalidas[] = darCarta();
	}else if($_POST['proceso']=="PLANTARSE"){
		$_POST['darCartaActivado'] = 1;


	}else if($_POST['proceso']=="DAR_CARTA_BLANCA"){

		$aCartasSalidasBanca[] = darCarta();
	}
	
	$sumaCartasJugador = sumarCartas($aCartasSalidas);
	$sumaCartasBanca = sumarCartas($aCartasSalidasBanca);


	$sumaCartasJugadorAux=$sumaCartasJugador;

	//$sumaJugador = $_POST['sumaJugador'];

	if(strpos($sumaCartasJugador, ",")){

		$posComa = strrpos($sumaCartasJugador, ",");
		$sumaCartasJugadorAux = substr($sumaCartasJugador, $posComa+1);

		if(($sumaCartasJugadorAux+0)>21){
			$sumaCartasJugadorAux = substr($sumaCartasJugador, 0, $posComa);
			$sumaCartasJugador = $sumaCartasJugadorAux;
		}

	}

	if($sumaCartasBanca>16){ //fin de la partida
		if($sumaCartasBanca>21){
			$bancaSePaso=1;
		}else{
			if($sumaCartasJugadorAux>$sumaCartasBanca){
				$ganaJugador = 1;
			}else{
				$ganaJugador = 0;
			}
		}
	}



	
}

function sumarCartas($aCartas_in){

		$sumaAux=0;
		$sumaAux2=0;
		foreach($aCartas_in as $cartaI){
			$indice = $cartaI[0];
			$indice2 = $cartaI[0];
			switch ($indice) {
				case 'A':
					$indice=1;
					$indice2=11;
					break;
				case "1"://como es el unico que empieza por 1 se puede hacer asi el 10
				case "J":
				case "Q":
				case "K":
					$indice = 10;
					$indice2=10;
					break;
				}

				/*if(count($cartaI)>2){
					$indice=10;
				}*/
				$sumaAux2 = $sumaAux2 + $indice2;
				$sumaAux = $sumaAux + $indice;	
						
			}
			if($sumaAux!=$sumaAux2){

				$sumaAux=$sumaAux.",".$sumaAux2;
			}
			return $sumaAux;


		
	}

function darCarta(){
	global $aCartasSalidas,$aCartasSalidasBanca;
	$aPalos=array("T", "C", "P", "D");
	$aIndices=array("A","2","3","4","5","6","7","8","9","10","J","Q","K");

	$palo = $aPalos[rand(0,3)];
	$indice = $aIndices[rand(0,12)];
	$naipe = $indice.$palo;
	while(in_array($naipe, $aCartasSalidas) || in_array($naipe, $aCartasSalidasBanca)){
		$palo = $aPalos[rand(0,3)];
		$indice = $aIndices[rand(0,12)];
		$naipe = $indice.$palo;
	}
	return $naipe;

}

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
	function fDarCarta(){
		document.getElementById("proceso").value = "DAR_CARTA_BLANCA";
		document.getElementById("manoJugador").value = '<?php echo implode(",",$aCartasSalidas)?>';
		document.getElementById("manoBanca").value = '<?php echo implode(",",$aCartasSalidasBanca)?>';
		//document.getElementById("form1").action = "miniblackjack_01.php";
      	document.getElementById("form1").submit(); 
	}
	function fPedir(){
		document.getElementById("proceso").value = "PEDIR";
		document.getElementById("manoJugador").value = '<?php echo implode(",",$aCartasSalidas)?>';
		document.getElementById("manoBanca").value = '<?php echo implode(",",$aCartasSalidasBanca)?>';
		//document.getElementById("form1").action = "miniblackjack_01.php";
      	document.getElementById("form1").submit(); 
	}

	function fPlantarse(){
		document.getElementById("proceso").value = "PLANTARSE";
		document.getElementById("manoJugador").value = '<?php echo implode(",",$aCartasSalidas)?>';
		document.getElementById("manoBanca").value = '<?php echo implode(",",$aCartasSalidasBanca)?>';
		//document.getElementById("form1").action = "miniblackjack_01.php";
      	document.getElementById("form1").submit(); 
	}

	function fOnLoad(){
		<?php
		if($sumaCartasJugadorAux > 21){
			echo "Swal.fire('Gana la banca','El jugador se ha pasado de 21','error')";
		
		}
		else if($bancaSePaso==1){
			echo "Swal.fire('Gana el jugador','La banca se ha pasado de 21','success')";
		}else if($sumaCartasBanca>16){//fin de la partida
			if($ganaJugador==1){
				echo "Swal.fire('Gana el jugador','Enhorabuena de la buena','success')";
			}else{
				echo "Swal.fire('Gana la banca','OHHHHHHHHHHH','error')";
			}
		}
		?>
	}
</script>
<head>
	<title></title>
</head>


<body onload="fOnLoad()">
	<form action="miniblackjack_01.php" method="post" id="form1">
		<input type="hidden" name="proceso" id="proceso">
		<input type="hidden" name="manoJugador" id="manoJugador">
		<input type="hidden" name="manoBanca" id="manoBanca">
		<input type="hidden" name="darCartaActivado" id="darCartaActivado" value="<?php echo $_POST['darCartaActivado']?>">

	<fieldset>
		<legend>JUGADOR</legend>

		<?php 
		foreach ($aCartasSalidas as $carta) {
			echo '<img height="120px" width="80px" src="'.$path.$carta.'.png"/>';
		}
		?>

	<fieldset>
		<legend>CONTROL</legend>
		<input name="sumaJugador" type="text" value="<?php echo $sumaCartasJugador;?>">
		<button <?php echo (($sumaCartasJugadorAux > 21)?"disabled='disabled' ":"")?> <?php echo (($_POST['darCartaActivado']==1)?"disabled='disabled' ":"")?> onclick="fPedir()" value="Pedir">Pedir</button>
		<button <?php echo (($sumaCartasJugadorAux > 21)?"disabled='disabled' ":"")?> onclick="fPlantarse()" value="Plantarse">Plantarse</button>
	</fieldset>
	</fieldset>

	<fieldset>
	<legend>BANCA (La banca debe pedir con 16 o menos y plantarse con 17 o más)</legend>
	<?php 
		foreach ($aCartasSalidasBanca as $cartaI) {
			echo '<img height="120px" width="80px" src="'.$path.$cartaI.'.png"/>';
		}
		?>


	<fieldset>
		<legend>CONTROL</legend>
		<input name="sumaCartasBanca" type="text" value="<?php echo $sumaCartasBanca;?>">
		<button  <?php echo (($sumaCartasBanca > 16)?"disabled='disabled' ": "")?> <?php echo (($_POST['darCartaActivado']==0)?"disabled='disabled' ":" " )?> onclick="fDarCarta()" value="DarCarta">Dar Carta</button>
	</fieldset>

	</fieldset>
</body>

<?php

var_export($aCartasSalidas)
?>
</form>
</html>