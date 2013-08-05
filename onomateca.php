<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<link href="http://fonts.googleapis.com/css?family=Oleo+Script" rel="stylesheet" type="text/css">

  <head>
    <title>Onomateca: el diccionario total.</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<style type="text/css">
	body {
	margin:0;
	padding:0;
	border:0;			
	width:100%;
	background:#fff;
	min-width:600px;	
	font-size:90%;
	font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
}

h1, h2, h3 {
	margin:.8em 0 .2em 0;
	padding:0;
}
p {
	margin:.4em 0 .8em 0;
	padding:0;
}



/* column container */
.colmask {
	position:relative;	/* This fixes the IE7 overflow hidden bug */
	clear:both;
	float:left;
	width:100%;			/* width of whole page */
	overflow:hidden;		/* This chops off any overhanging divs */
}
/* common column settings */
.colright,
.colmid,
.colleft {
	float:left;
	width:100%;			/* width of page */
	position:relative;
}
.col1,
.col2,
.col3 {
	float:left;
	position:relative;
	padding:0 0 1em 0;	
	overflow:hidden;
}

.threecol {
	background:#eee;		
}
.threecol .colmid {
	right:25%;			/* width of the right column */
	background:#fff;		/* center column background colour */
}
.threecol .colleft {
	right:50%;			/* width of the middle column */
	background:#f4f4f4;	/* left column background colour */
}
.threecol .col1 {
	width:46%;			/* width of center column content (column width minus padding on either side) */
	left:102%;			/* 100% plus left padding of center column */
}
.threecol .col2 {
	width:21%;			/* Width of left column content (column width minus padding on either side) */
	left:31%;			/* width of (right column) plus (center column left and right padding) plus (left column left padding) */
}
.threecol .col3 {
	width:21%;			/* Width of right column content (column width minus padding on either side) */
	left:85%;			/* Please make note of the brackets here:
					(100% - left column width) plus (center column left and right padding) plus (left column left and right padding) plus (right column left padding) */
}
/* Footer styles */
#footer {
	clear:both;
	float:left;
	width:100%;
	border-top:1px solid #000;
}
#footer p {
	padding:10px;
	margin:0;
}



		
	 #button1 {
	    font-family: Coda Helvetica, Geneva, Arial,
			  SunSans-Regular, sans-serif
		}
	  
	.headword {
		text-align:center;
		font: 400 100px/1.3 'Oleo Script', Helvetica, sans-serif;
		color: #2b2b2b;
		text-shadow: 4px 4px 0px rgba(0,0,0,0.1);
		}	
		
	.transcripcion{
		font: 500 25px/1.3 'arial unicode ms'
	}

	.frecuencia{
		font-family: Consolas, monaco, monospace;
		font-size:400%;
	}
	.nivel10{
		color: #00CC66;
	}
	.nivel9{
		color: #00CC00;
	}
	.nivel8{
		color: #009900;
	}
	.nivel7{
		color: #FF9900;
	}
	.nivel6{
		color: #FF9900;
	}
	.nivel5{
		color: #FF3300;
	}
	.nivel4{
		color: #FF0066;
	}
	.nivel3{
		color: #CC0099;
	}
	.nivel2{
		color: #993399;
	}
	.nivel1{
		color: #3333CC;
	}
	.tipo{
		font-family: Consolas, monaco, monospace;
		font-size:100%;
	}
	.nivelA{
		color: Green;
	}
	.nivelB{
		color: Orange ;
	}
	.nivelC{
		color: OrangeRed ;
	}
	.nivelD{
		color: DarkRed ;
	}
	.resaltado{	
		font-size: 125% ;
	}
	a:link, a:visited {
		color: LightSlateGray ;
		text-decoration: none;
	}
	
  </style>
  
	
  </head>
  <body>
    <div>
	  
      
    
	  
	
	 

<?php
	/*Aquí empieza el script en php. Consta de tres partes:
	- Una parte en la que recogemos la palabra que el usuario haya introducido en la caja de búsqueda. 
	- Una parte en que pasamos la palabra por las distintas funciones lingüísticas (definir, lematizar, transcribir)
   	y mostramos la información resultante (transcripcion, definiciones, lemas) estructurando la página en 3 columnas. 
	- Una parte en la que conectamos nuestras funciones lingüísticas con las APIs de Apicultur que serán las que nos proporcionen la info.	
	*/
	
	// #1 RECOGIDA DE PARAMETROS	
	//recogemos la palabra que el usuario haya introducido en la caja de búsqueda
	if (isset($_REQUEST['word'])){
		$word = $_REQUEST['word'];
	}	
	

	// #2 PASAMOS LA PALABRA POR FUNCIONES LINGÜÍSTICAS Y MOSTRAMOS LA INFO 
	if (isset($word)){
		/* Mostramos la palabra introducida por el usuario en la cabecera de la web. 
		La etiqueta "headword" nos permitirá después modificar el CSS y ponerle el diseño que queramos
		*/
		echo "<center><span class=headword>".$word."</span></center></br>";
		
		//LEMATIZACIÓN
		//Lematizamos la palabra de entrada para obtener el lema (en caso de que la palabra introducida no fuera el lema sino una forma flexionada).
		$lema_=lematice($word);
		$listaCat=array();
		$listaLemas=array();
			$palabra=$lema_->{'palabra'};
			$lemas_=$lema_->{'lemas'};
			foreach($lemas_ as $lemas){
				$lema=$lemas->{'lema'};
				$categoria=$lemas->{'categoria'};
				$categoriaSimple=$lemas->{'categoriaSimple'};
				array_push($listaCat, $categoriaSimple);
				array_push($listaLemas, $lema);
			}
		//"Deslematizamos" la palabra de entrada para obtener las formas flexionadas (en caso de que la palabra introducida fuera un lema).	
		$deslema_ = deslematice($word);
		$formas_ = $deslema_->{'formas'};
			
		/*
		* A partir de aquí empieza la info de la columna central
		*/
		
		//Creamos el título "Definiciones", bajo el que aparecerán las definiciones.
		echo "<div class=\"colmask threecol\"><div class=\"colmid\"><div class=\"colleft\"><div class=\"col1\"><h2>Definiciones</h2>";
		
		// DEFINCION BÁSICA
		//Llamamos a la función que obtiene la definición básica de la palabra
		$definicionBasicas_ = definaBasica($lema);
		
		// Si existen definiciones para esa palabra en el diccionario básico, se muestran
		if (isset($definicionBasicas_)){
			foreach($definicionBasicas_ as $definicionBasica){  
			$lemaM = $definicionBasica->{'lema'};			
			$definicionM = $definicionBasica->{'definicion'};
			
			echo "<p><span class=resaltado>".$definicionM."</span></p></br>";
			}
		}
		
		// DEFINCION GENERAL
		//Llamamos a la función que obtiene las definiciones de la palabra
		$definiciones_ = defina($lema);

		// Si existen definiciones para esa palabra en el diccionario general, se muestran. En caso de que no haya definiciones, se muestra el mensaje "No hay definiciones disponibles".    
		$definiciones = $definiciones_->{'definiciones'};
		if (is_null($definiciones)){
			echo "<p><span class=definiciones>No hay definiciones disponibles.</span></p></br>";
			echo "</div>";
		} else {	
			foreach($definiciones as $definicion){
			echo "<p><span class=definiciones>".$definicion."</span></p></br>";
			}
			echo "</div>";
		}
		
		/*
		* A partir de aquí empieza la info de la columna lateral izquierda. 
		*/
		
		// TRANSCRIPCION FONETICA
		//Llamamos a la función que obtiene la transcripción fonética de la palabra
		$transcripcion_= transcriba($word);
		//Creamos la columna lateral izquierda
		echo "<div class=\"col2\">";
		// Mostramos el título del apartado "Fonética" bajo el que aparecerá la info relacionada con la transcripción y las sílabas
		echo "<h2>Fon&eacute;tica</h2>";
		//En caso de que no haya transcripción fonética, se mostrará un mensaje que lo indica.
		if (!isset($transcripcion_)){
		echo "No hay transcripci&oacute;n para esta palabra";
		}
		//En caso de que sí haya transcripción fonética, mostramos la transcripción.
		if (isset($transcripcion_)){
			$transcripcion = $transcripcion_->{'transcripcionPalabra'};
			echo "<span class=transcripcion>/</span>";
			foreach($transcripcion as $charAscii){
			$pieces = explode("_", $charAscii);
			$char = "&#".$pieces[0].";";
			echo "<span class=transcripcion>".$char."</span>";
			}
			echo "<span class=transcripcion>/</span>";
			echo "</br>";
		}
		
		// SILABEO
		//Llamamos a la función que obtiene la info silábica de la palabra
		$silabeo_= silabee($word);
		//En caso de que no sea posible obtener la info silábica, se mostrará un mensaje que lo indica.
		if (!isset($silabeo_)){
		echo "No hay silabeo para esta palabra";
		}
		//En caso de que sí haya info silábica, la mostramos (silabeo, sílaba tónica, número de sílabas, tipo de palabra según acentuación).
		if (isset($silabeo_)){
		    $palabraSilabeada = $silabeo_->{'palabraSilabeada'};
			$silabaTonica = $silabeo_->{'silabaTonica'};
			$posSilabaTonica = $silabeo_->{'posSilabaTonica'};
			$numeroSilabas = $silabeo_->{'numeroSilabas'};
			
			echo "<span class=resaltado>".$palabraSilabeada."</span></br>";
			echo "S&iacute;laba t&oacute;nica: <span class=resaltado>".$silabaTonica."</span></br>";
			echo "N&uacute;mero de s&iacute;labas:  <span class=resaltado>".$numeroSilabas."</span></br>";
			
			/*La API nos devuelve un número que indica la posición de la sílaba tónica contando desde atrás. El código que viene a continuación "traduce"
			ese número, de tal manera que si la posición de la sílaba tónica es 1, lo que se muestra es el mensaje "La palabra es aguda", etc. 
			
			*/	
			if($posSilabaTonica=="1"){
				echo "La palabra es  <span class=resaltado>aguda</span></br>";
			}
			if($posSilabaTonica=="2"){
			echo "La palabra es  <span class=resaltado>llana</span></br>";
			}
			if($posSilabaTonica=="3"){
			echo "La palabra es  <span class=resaltado>esdr&uacute;jula</span></br>";
			}
			if($posSilabaTonica=="4"){
			echo "La palabra es  <span class=resaltado>sobresdr&uacute;jula</span></br>";
			}
			echo "</br>";
		}


			
		//CATEGORIA GRAMATICAL
		/*Mostramos el título del apartado "Categoría" bajo el que aparecerán los lemas y la categoría morfológica de la palabra
		La info que aparece aquí (las variables $listaCat y $listaLemas) la obtuvimos al principio, más arriba, cuando llamamos a la función lematice y deslematice. 
		*/
		echo "<h2>Categor&iacute;a</h2>";
		//En caso de que no sea posible obtener la categoría, se mostrará un mensaje que lo indica.
		if(empty($listaCat)){
				echo "No conocemos la categor&iacute;a de esta palabra</br>";
		}
		/*En caso de que sí tengamos la categoría, se muestran todos las posibles categorías de la palabras con su lema asociado. 
		La API de lematización nos devuelve un número que indica la categoría de la palabra. El código que viene a continuación "traduce"
		ese número, de tal manera que si la categoría es 4, lo que se muestra es el mensaje "Sustantivo: " seguido del lema correspondiente, etc. 
		*/
		if(!empty($listaCat)){
				if(!empty($listaLemas)){
					for($o=0;$o<count($listaCat);$o++){
					
							if($listaCat[$o]=="0"){
								echo "No conocemos la categor&iacute;a de esta palabra</br>";
								
							}
							if($listaCat[$o]=="4"){
							echo "<span class=resaltado>Sustantivo: ".$listaLemas[$o]."</span></br>";
							}
							//Si la categoría es un verbo, el infinitivo mostrado llevará un hipervínculo que redirige a su conjugación según el conjugador Onoma.
							if($listaCat[$o]=="5"){
							echo "<span class=resaltado>Verbo: <span class=resaltado><a href=\"http://www.onoma.es/#".$listaLemas[$o]."\" target=\"_blank\">".$listaLemas[$o]."</a></span></span></br>";
							}
							if($listaCat[$o]=="1"){
								echo "<span class=resaltado>Adjetivo calificativo: ".$listaLemas[$o]."</span></br>";
							}
							if($listaCat[$o]=="2"){
								echo "<span class=resaltado>Adverbio</span></br>";
							}
							if($listaCat[$o]=="3"){
								echo "<span class=resaltado>Contracción preposici&oacute;n + art&iacute;culo</span></br>";
							}
		
							if($listaCat[$o]=="6"){
								echo "<span class=resaltado>Pronombre: ".$listaLemas[$o]."</span></br>";
							}
							if($listaCat[$o]=="7"){
								echo "<span class=resaltado>Conjunci&oacute;n</span></br>";
							}
							if($listaCat[$o]=="8"){
								echo "<span class=resaltado>Interjecci&oacute;n</span></br>";
							}
							if($listaCat[$o]=="9"){
								echo "<span class=resaltado>Preposici&oacute;n</span></br>";
							}
							if($listaCat[$o]=="16"){
								echo "<span class=resaltado>Art&iacute;culo: ".$listaLemas[$o]."</span></br>";
							}
							if($listaCat[$o]=="31"){
								echo "<span class=resaltado>Onomatopeya</span></br>";
							}
							if($listaCat[$o]=="32"){
								echo "<span class=resaltado>Locuci&oacute;n</span></br>";
							}
							if($listaCat[$o]=="33"){
								echo "<span class=resaltado>Adjetivo determinativo: ".$listaLemas[$o]."</span></br>";
							}
					}
				}
			}
	
		
		// FAMILIA MORFOLOGICA
		// Mostramos el título del apartado "Familia morfológica" bajo el que aparecerá la info relacionada con las variantes morfológicas de la palabra 
		echo "<h2>Familia morfol&oacute;gica</h2>";
		//Si la palabra es una forma flexionada, su familia será el lema: es decir, si la palabra introducida es "dijéramos", su familiar más cercano será el lema "decir".
		if(empty($formas_)&&!($categoria==0)){
			echo "<span class=formas>".$lema."</span></br>";
			}
		//Si la palabra es una palabra desconocida ($categoria==0=, mostraremos un mensaje que indique no hay familia morfológica.
		if(empty($formas_)&&$categoria==0){
			echo "Esta palabra no tiene familia :(";
			}
		//Si la palabra introducida es un lema, su familia morfológica serán todas las posibles flexiones (formas conjugadas, femeninos, plurales, diminutivos, etc) 		
		if(!empty($formas_)){
			$lema=$word;
			$yadichos=array();
			foreach($formas_ as $formas){
				$palabra=$formas->{'palabra'};
				$categoria=$formas->{'categoria'};
				if(in_array($palabra, $yadichos) == false){
				echo "<span class=formas>".$palabra." </span>";
				array_push($yadichos, $palabra);
				}
			}
			echo "</br>";
		}
		
		//RIMAS
		//Llamamos a la función que obtiene rimas de la palabra
		$rima_ = rime($word,$categoriaSimple);
		//Mostramos el título "Rimas" bajo el que aparecerán las rimas de la palabra introducida
		echo "<h2>Rimas</h2>";
		//Si no hay rimas, lo indicamos en el mensaje.
		if(empty($rima_)){
		echo "<span class=rima>No hay rimas para esta palabra</span>";
		}
		//Si sí hay rimas, las mostramos.
		if (!empty($rima_)){
		    foreach($rima_ as $objetoRima){
			$rima = $objetoRima->{'palabra'};
			echo "<span class=rima>".$rima." </span>";
			}
			echo "</br>";
		}	
		echo "</div>";
		
		/*
		* A partir de aquí empieza la columna lateral derecha
		*/
		
		// FRECUENCIA
		//Llamamos a la función que obtiene el nivel de frecuencia de la palabra
		$frecuencia_= frecuente($lema);
		//Creamos la tercera columna y mostramos el título "Frecuencia" bajo el que aparecerá la info de frecuencia de la palabra
		echo "<center><div class=\"col3\"><h2>Frecuencia</h2>";
		//Si no hay nivel de frecuencia, lo indicamos en el mensaje.
		if(empty($frecuencia_)){
			echo "<span class=rima>No hay frecuencia para esta palabra</span>";
		}
			
		/*En caso de que sí tengamos la frecuencia, se muestra el nivel. 
		La API de frecuencia nos devuelve un número del 0 al 10 que indica la frecuencia de la palabra. El código que viene a continuación "traduce"
		ese número, de tal manera que se muestra el nivel de frecuencia sobre 10 acompañado de un subtítulo asociado al nivel de frecuencia 
		que indica el grado de frecuencia (imprescindible, básica, diaria...).
		Cada nivel tiene unas etiquetas span determinadas que nos permitirán después modificar el CSS para que aparezcan con unos colores y tamaños determinados.
		*/
		if(!empty($frecuencia_)){
		    $frecuencia = $frecuencia_->{'valor'};
			if($frecuencia=="-1"){
			echo "No conocemos esta palabra</br>";
			}
			if($frecuencia=="0"){
			echo "No tenemos nivel de frecuencia para esta palabra</br>";
			}
			if($frecuencia=="10"){
			echo "<span class=nivel10><span class=frecuencia>10/10</span></span></br>";
			echo "<center><span class=nivel10><span class=tipo>Imprescindible</span></span></center></br>";
			}
			if($frecuencia=="9"){
			echo "<span class=nivel9><span class=frecuencia>9/10</span></span></br>";
			echo "<center><span class=nivel9><span class=tipo>B&aacute;sica</span></span></center></br>";
			}
					if($frecuencia=="8"){
			echo "<span class=nivel8><span class=frecuencia>8/10</span></span></br>";
			echo "<center><span class=nivel8><span class=tipo>Diaria</span></span></center></br>";
			}
					if($frecuencia=="7"){
			echo "<span class=nivel7><span class=frecuencia>7/10</span></span></br>";
			echo "<center><span class=nivel7><span class=tipo>Normal</span></span></center></br>";
			}
					if($frecuencia=="6"){
			echo "<span class=nivel6><span class=frecuencia>6/10</span></span></br>";
			echo "<center><span class=nivel6><span class=tipo>Peculiar</span></span></center></br>";
			}
					if($frecuencia=="5"){
			echo "<span class=nivel5><span class=frecuencia>5/10</span></span></br>";
			echo "<center><span class=nivel5><span class=tipo>Infrecuente</span></span></center></br>";
			}
					if($frecuencia=="4"){
				echo "<span class=nivel4><span class=frecuencia>4/10</span></span></br>";
			echo "<center><span class=nivel4><span class=tipo>Inusual</span></span></center></br>";
			}
					if($frecuencia=="3"){
				echo "<span class=nivel3><span class=frecuencia>3/10</span></span></br>";
			echo "<center><span class=nivel3><span class=tipo>Extra&ntilde;a<span></span></center></br>";
			}
					if($frecuencia=="2"){
				echo "<span class=nivel2><span class=frecuencia>2/10</span></span></br>";
			echo "<center><span class=nivel2><span class=tipo>Rara</span></span></center></br>";
			}
					if($frecuencia=="1"){
				echo "<span class=nivel1><span class=frecuencia>1/10</span></span></br>";
			echo "<center><span class=nivel1><span class=tipo>H&aacute;pax</span></span></center></br>";
			}
		}	
		
		// NIVELES ABC CERVANTES
		//Llamamos a la función que obtiene el nivel de dificultad del Cervantes
		$cervantes_= nivele($lema);
		//Si no obtenemos ningún nivel, mostramos un mensaje que lo advierte.
		if(empty($cervantes_)){
				echo "<span class=rima>No hay nivel para esta palabra</span>";
		}
		/*En caso de que sí tengamos el nivel ABC del Cervantes, se muestra. 
		La API de nivel del Cervantes nos devuelve un número del 0 al 4 que indica el nivel de complejidad de la palabra
		(1 equivalente al nivel A, 2 a B y 3 equivalente al C; el 0 es desconocido.
		El nivel 4 se asigna a palabras técnicas, anticuadas, etc, que ni un nativo tendría por qué conocer.
		El código que viene a continuación "traduce" ese número, de tal manera que se muestra el nivel expresado como A/B/C/D
		acompañado de un subtítulo que indica el nivel de complejidad (básico, intermedio, avanzado, nativo).
		Cada nivel tiene unas etiquetas span determinadas que nos permitirán después modificar el CSS para que aparezcan con unos colores y tamaños determinados.
		*/
		if(!empty($cervantes_)){
			 $cervantes = $cervantes_->{'valor'};
			echo "<h2>Nivel ABC</h2>";
			if($cervantes=="0"){
			echo "No conocemos esta palabra</br>";
			}
			if($cervantes=="1"){
			echo "<span class=nivelA><span class=frecuencia>A</span></span></br>";
			echo "<span class=nivelA><span class=tipo>B&aacute;sico</span></span></br>";
			}
			if($cervantes=="2"){
			echo "<span class=nivelB><span class=frecuencia>B</span></span></br>";
			echo "<span class=nivelB><span class=tipo>intermedio</span></span></br>";
			}
			if($cervantes=="3"){
			echo "<span class=nivelC><span class=frecuencia>C</span></span></br>";
			echo "<span class=nivelC><span class=tipo>avanzado</span></span></br>";
			}
			if($cervantes=="4"){
			echo "<span class=nivelD><span class=frecuencia>D</span></span></br>";
			echo "<span class=nivelD><span class=tipo>experto</span></span></br>";
			}
			
			
		}
		// SINÓNIMOS
		// Llamamos a la función que obtiene sinónimos de la palabra dada.
		$sinonimos_ = sinonime($lema);
		// Mostramos el título "Palabras relacioandas" bajo el que aparecerán los sinónimos y términos relacionados que nos devuelva la función. 
		echo "<h2>Palabras relacionadas</h2>";
		// Si no hay sinónimos se advierte en el mensaje
		if(empty($sinonimos_)){
				echo "<span class=rima>No hay palabras relacionadas</span>";
		}
		// Si sí hay sinónimos, se muestran. 
		if (!empty($sinonimos_)){	
		    foreach($sinonimos_ as $sinonimosObj){
			$sinonimo = $sinonimosObj->{'valor'};
			echo "<span class=sinonimo>".$sinonimo." </span>";
			}
			echo "</br></center>";
		}			
		echo "</div></div></div></div>";	
		
	}

		

		
	// #3 FUNCIONES QUE CONECTAN CON LAS APIS A LAS QUE NOS SUSCRIBIMOS EN APICULTUR
	
		function silabee($word){
	
		//Este es nuestro API key	
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		//Esta es la URL de llamada a la API de silabeo
		$url="https://store.apicultur.com/api/silabeame/1.0.0/".$word;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener el silabear la palabra.".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	return $obj;
	}
		
		
	function deslematice($word){
		//Aquí va nuestro API key	
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		// Esta es la URL de la API a la que queremos llamar
		$url="https://store.apicultur.com/api/deslematiza/1.0.0/".$word;	
		
		#Iniciamos curl
		$ch = curl_init();

		#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
		curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

		#Pasamos la url de la api 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		#Introducimos en una variable el valor que nos devuelve la api
		$respuesta = curl_exec($ch);
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		#Cerrar el recurso cURL y liberar recursos
		curl_close($ch);	
    
		#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
		switch ($http_status) {
			case '200':
			$obj = json_decode($respuesta);
		break;
		default:
		echo "<br/>error al obtener las formas flexionadas de la palabra.".$word;
		echo "<br/>Error:" .$http_status ;
		break;
	}
	
	return $obj;
	}
	
	

	

	
	function rime($word, $categoria){
		#API Key 			
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		if($categoria==1||$categoria==4||$categoria==5){
		$url="https://store.apicultur.com/api/rima/1.0.0/".$word."/true/0/10/true";	
		}else{
		$url="https://store.apicultur.com/api/rima/1.0.0/".$word."/true/".$categoria."/10/true";	
		}
		
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
		#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener rimas de la palabra.".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	

	
	
		

		function lematice($word){
		#API Key 
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		
		# URL de la API
		$url="https://store.apicultur.com/api/lematiza-clasico/1.0.0/".$word;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener los lemas de".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
			function transcriba($word){
		#API Key			
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
	
		#URL de la API
		$url="https://store.apicultur.com/api/transcribe/1.0.0/".$word."/1";	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener la transcripcion fonetica de la palabra.".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
	function frecuente($lema){
		#API Key
			
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		#URL de la API
		$url="https://store.apicultur.com/api/calculaFrecuencia10/1.0.0/".$lema;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener el nivel de frecuencia de la palabra.".$lema;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
	function nivele($word){
		#API Key 			
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		# URL de la API
		$url="https://store.apicultur.com/api/damenivel/1.0.0/".$word;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener el nivel de la palabra.".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
	function definaBasica($word){
		#API Key
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		
		#URL de la API
		$url="https://store.apicultur.com/api/define/1.0.0/".$word;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener la definicion básica de la palabra ".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
	
	function defina($word){
		#API Key
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
	
		#URL de la API
		$url="https://store.apicultur.com/api/dicc/1.0.0/definiciones/10/".$word;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener la definicion de la palabra.".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
		function sinonime($word){
		#API Key
		$access_key = "ESCRIBE AQUÍ TU API KEY";		
		
		#URL
		$url="https://store.apicultur.com/api/sinonimosporpalabra/1.0.0/".$word;	
		
	#Iniciamos curl
	$ch = curl_init();

	#Pasamos nuestro API Key y señ¡¬¡mos que lo que nos van a devolver es JSON
	curl_setopt($ch,CURLOPT_HTTPHEADER,array( 'Accept: application/json', 'Authorization: Bearer ' . $access_key ));

	#Pasamos la url de la api 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	#Introducimos en una variable el valor que nos devuelve la api
	$respuesta = curl_exec($ch);
		
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	#Cerrar el recurso cURL y liberar recursos
	curl_close($ch);	
    
	#Comprobamos el código devuelto por la API (si es 200 es que todo ha ido bien) para ver que todo ha salido correctamente y en caso positivo devolvemos ok
	switch ($http_status) {
    case '200':
		$obj = json_decode($respuesta);
	  break;
    default:
	  echo "<br/>error al obtener palabras relacionadas con la palabra".$word;
	  echo "<br/>Error:" .$http_status ;
      break;
	}
	
	return $obj;
	}
	
	 
?>


	<p>
       <center><form action="onomateca.php" method="POST" name="theform">
		<h3>Onomateca</h3>
		<span class="type"><input type="text" name="word" />
	
		
		<input type="submit" id="button1" value="Buscar!" />
		</span>
		</br>
		 <span class="instruccion">Introduce una palabra en espaÃ±ol y te dirÃ© cuanto sÃ© de ella...</span>
	  </form></center>
	</p>
	
<div id="footer">
	<p>Este diccionario est&aacute; construido con las APIs de  <a href="https://store.apicultur.com"/>Apicultur</a>. Cualquiera puede cocinar el diccionario a su gusto utilizando las APIs y condimentando a voluntad. En el tutorial se explica la receta.</p>
</div>
 </body>
</html>
	
