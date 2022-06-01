 <?php

session_start();

//pega a hora atual e atualiza o log no banco

date_default_timezone_set('America/Sao_Paulo');

//Incluir conexao com BD

include_once("../conexao.php");



// FUNÇÕES QUE CONVERTEM FORMATOS DAS DATAS E CRIAM O PERÍODO DE TOLERÂNCIA DE 110 MINUTOS ENTRE EVENTOS

function converteDataMenor($date_str)

{

	$date = DateTime::createFromFormat('d/m/Y H:i', $date_str);

	$date->modify('-110 minutes');

	return $date->format('Y-m-d H:i:s');	

}

function converteDataMaior($date_str)

{

	$date = DateTime::createFromFormat('d/m/Y H:i', $date_str);

	$date->modify('+110 minutes');

	return $date->format('Y-m-d H:i:s');	

}

function converteData($date_str)

{

	$date = DateTime::createFromFormat('d/m/Y H:i', $date_str);

	return $date->format('Y-m-d H:i:s');	

}

$userDepartamento = $_SESSION['usuarioDepartamento'];


$dateTime = date('Y-m-d H:i:s');//FORMATO AMERICANO PARA COMPARAÇÃO DE DATAS


$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$title = filter_input(INPUT_POST, 'evento', FILTER_SANITIZE_STRING);
$responsavel = filter_input(INPUT_POST, 'responsavel', FILTER_SANITIZE_STRING);
$telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);


// -- NECESSÁRIO CONVERTER PARA FORMATO AMERICANO PARA COMPARAÇÃO ENTRE DATAS NO PHP --//

$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_STRING);
$end = filter_input(INPUT_POST, 'end', FILTER_SANITIZE_STRING);
$startConvert = converteData($start);
$endConvert = converteData($end);
// criação das variáveis para a query

$startMenor = converteDataMenor($start);//pega o início do evento menos 110 minutos
$startMaior = converteDataMaior($start);//pega o início do evento mais 110 minutos
$endMenor = converteDataMenor($end);//pega o fim do evento menos 110 minutos
$endMaior = converteDataMaior($end);//pega o fim do evento mais 110 minutos
// ----------------------------------------------------------------------------------- //



$aud = filter_input(INPUT_POST, 'aud', FILTER_SANITIZE_STRING);
$setor = filter_input(INPUT_POST, 'setor', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
$tsinal = filter_input(INPUT_POST,'tsinal', FILTER_SANITIZE_STRING);
$tsinal2 = filter_input(INPUT_POST,'tsinal2', FILTER_SANITIZE_STRING);
$local = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING);
$formato = filter_input(INPUT_POST, 'formato', FILTER_SANITIZE_STRING);




//PEGA O NOME DO AUDITÓRIO SEM A SIGLA

$audConsulta = substr($aud, 4);



if ($status != 2){

	//QUANDO FOR EDIÇÃO DE EVENTO NÃO CANCELADO

	//VERIFICA SE O INTERVALO DOS HORÁRIOS DIGITADOS PELO USUÁRIO ESTÃO NO START E NO END DO BANCO, SE ESTIVER NÃO PERMITE CADASTRAR/EDITAR

	$verificaInicio = mysqli_query($conn, "SELECT * FROM events WHERE ('$startMenor' BETWEEN start AND end AND aud = '$audConsulta' AND status != 2) OR ('$startMaior' BETWEEN start AND end AND aud = '$audConsulta' AND status != 2)");



	$verificaFim = mysqli_query($conn, "SELECT * FROM events WHERE ('$endMenor' BETWEEN start AND end AND aud = '$audConsulta' AND status != 2) OR ('$endMaior' BETWEEN start AND end AND aud = '$audConsulta' AND status != 2)");
	
	
	
	
	
	
	



	$linhaInicio = mysqli_num_rows($verificaInicio);

	$linhaFim = mysqli_num_rows($verificaFim);



}

else if ($status == 2){

	//QUANDO FOR EDIÇÃO DE EVENTO CANCELADO

	//VERIFICA SE O INTERVALO DOS HORÁRIOS DIGITADOS PELO USUÁRIO ESTÃO NO START E NO END DO BANCO, SE ESTIVER NÃO PERMITE CADASTRAR/EDITAR

	$verificaInicio = mysqli_query($conn, "SELECT * FROM events WHERE '$startMenor' BETWEEN start AND end AND aud = '$audConsulta'");

	//OR '$startMaior' BETWEEN start AND end AND aud = '$audConsulta'



	$verificaFim = mysqli_query($conn, "SELECT * FROM events WHERE '$endMaior' BETWEEN start AND end AND aud = '$audConsulta'");

	//OR '$endMenor' BETWEEN start AND end AND aud = '$audConsulta'



	$linhaInicio = mysqli_num_rows($verificaInicio);

	$linhaFim = mysqli_num_rows($verificaFim);

}





if (($linhaInicio < 2) && ($linhaFim < 2)){



	//CASO ENCONTRE NO RESULTADO ALGUM EVENTO QUE NÃO SEJA O PRÓPRIO EVENTO EM EDIÇÃO, EMITE ALERTA E ENCERRA LÓGICA

	while ($reg = mysqli_fetch_assoc($verificaInicio)) {

		if($reg['id'] != $id){

			echo  "<script> window.alert ('horário já reservado no período informado!'); 

				  window.location.href='principal.php'

			  	  </script>";

			return false;

		}

	}

	

	

	//CHECA SE DATA/HORA INICIAL É IGUAL OU MENOR A DATA/HORA FINAL, CASO SIM NÃO PERMITE CADASTRAR/EDITAR

	if($startConvert === $endConvert){

		echo  "<script> window.alert ('Data inicial e data final não podem ser iguais!'); 

				 window.location.href='principal.php'	

			  </script>";

		return false;

        

	}else if($startConvert > $endConvert){

		echo  "<script> window.alert ('Data final não pode ser menor que a data inicial!'); 

				 window.location.href='principal.php'

			  </script>";

		return false;

	}

	else{

		//Converter a data e hora do formato brasileiro para o formato do Banco de Dados

		$data = explode(" ", $start);
		list($date, $hora) = $data;
		$data_sem_barra = array_reverse(explode("/", $date));
		$data_sem_barra = implode("-", $data_sem_barra);
		$start_sem_barra = $data_sem_barra . " " . $hora;


		//$aud_cor = explode (".",$aud);
		$status_cor = explode (".",$status);
		$aud_sigla = explode(".",$aud);
		$tsinalx = explode(".", $tsinal);////////

		

		$data = explode(" ", $end);
		list($date, $hora) = $data;
		$data_sem_barra = array_reverse(explode("/", $date));
		$data_sem_barra = implode("-", $data_sem_barra);
		$end_sem_barra = $data_sem_barra . " " . $hora;

		

		//PEGA O NOME DO AUDITÓRIO SEM A SIGLA



			//$audConsulta = substr($aud, 6);
			$audio = explode(".",$aud);
			//var_dump($audio[0]);
			echo "<br>";
			//var_dump($audio[1]);
			//$testeGeraldo = $audio[0];
			//var_dump($audio);
		

		

		

	$us = $_SESSION['usuarioId'];
	$sql = "SELECT departamento from usuarios where id = '$us' ";
	$sqlResult = mysqli_query($conn, $sql) or die(mysqli_erro($conn));
	$departamento = mysqli_fetch_assoc($sqlResult);

	

    $licenca_departamento = $audio[1];

	//var_dump($aud);
	//var_dump($aud);

	

	

	//-----------Editar de evento são inseridos relacionando licenças  com os departamantos 

	

	    /****departamento ***DEAMA****/

	if($licenca_departamento == "pro100_01" and $departamento['departamento'] =="DEAMA"
	 or $licenca_departamento == "pro300_17" and $departamento['departamento'] =="DEAMA" ):

		switch($licenca_departamento):

		 case "pro100_01":		

			  $departamento_licenca = "pro100_01";

			  break;


			  case "pro300_17":		    

				$departamento_licenca = "pro300_17";
   
				 break;



		 endswitch;

		

		$result_events = 'UPDATE events 

		                  SET responsavel="'.$responsavel.'",telefone="'.$telefone.'",email="'.$email.'",title="'.$title.'", color="'.$status_cor[1].'", start="'.$start_sem_barra.'", end="'.$end_sem_barra.'", aud="'.$aud_sigla[1].'",local="'.$local.'", setor="'.$setor.'", status="'.$status_cor[0].'", sigla="'.$aud_sigla[0].'", tsinal="'.$tsinalx[0].'", tsinal2="'.$tsinalx[1].'", formato="'.$formato.'", modificadoPor="'.$_SESSION['usuarioNome'].'", nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'", modificadoEm="'.$dateTime.'" 

					      WHERE id="'.$id.'" ';

		$resultado_events = mysqli_query($conn, $result_events);




		 	// Departamento ALL são para todos os perfis que cadastram e editam um evento que não possui licença zoom  ---->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		elseif($licenca_departamento == "não_se_aplica" and $departamento['departamento'] =="ALL" ):

		switch($licenca_departamento):

		 case "não_se_aplica":		

			  $departamento_licenca = "não_se_aplica";

			  break;		  



		 endswitch;

		

		$result_events = 'UPDATE events 

		                  SET responsavel="'.$responsavel.'",telefone="'.$telefone.'",email="'.$email.'",title="'.$title.'", color="'.$status_cor[1].'", start="'.$start_sem_barra.'", end="'.$end_sem_barra.'", aud="'.$aud_sigla[1].'",local="'.$local.'", setor="'.$setor.'", status="'.$status_cor[0].'", sigla="'.$aud_sigla[0].'", tsinal="'.$tsinalx[0].'", tsinal2="'.$tsinalx[1].'", formato="'.$formato.'", modificadoPor="'.$_SESSION['usuarioNome'].'", nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'", modificadoEm="'.$dateTime.'" 

					      WHERE id="'.$id.'" ';

		$resultado_events = mysqli_query($conn, $result_events);



		 	// Ediçção para eventos que não possui licença zoom  ---->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
		elseif($licenca_departamento == "não_se_aplica" and $departamento['departamento'] =! "0" ):

			switch($licenca_departamento):
	
			 case "não_se_aplica":		
	
				  $departamento_licenca = "não_se_aplica";
	
				  break;		  
	
	
	
			 endswitch;
	
			
	
			$result_events = 'UPDATE events 
	
							  SET responsavel="'.$responsavel.'",telefone="'.$telefone.'",email="'.$email.'",title="'.$title.'", color="'.$status_cor[1].'", start="'.$start_sem_barra.'", end="'.$end_sem_barra.'", aud="'.$aud_sigla[1].'",local="'.$local.'", setor="'.$setor.'", status="'.$status_cor[0].'", sigla="'.$aud_sigla[0].'", tsinal="'.$tsinalx[0].'", tsinal2="'.$tsinalx[1].'", formato="'.$formato.'", modificadoPor="'.$_SESSION['usuarioNome'].'", nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'", modificadoEm="'.$dateTime.'" 
	
							  WHERE id="'.$id.'" ';
	
			$resultado_events = mysqli_query($conn, $result_events);












			

		

		

	//departamento ***Cursos Livres/DENSE***

	elseif($licenca_departamento == "pro100_04" and $departamento['departamento'] =="DENSE" 
	        or $licenca_departamento == "pro100_05" and $departamento['departamento'] =="DENSE"
			or $licenca_departamento == "pro300_14" and $departamento['departamento'] =="DENSE"
			or $licenca_departamento == "pro300_15" and $departamento['departamento'] =="DENSE"):

	    

		switch($licenca_departamento):

		      case "pro100_04":

			  $departamento_licenca = "pro100_04";
			  break;

				    case "pro100_05":   

						$departamento_licenca = "pro100_05";

						break;

					case "pro300_14":   

						$departamento_licenca = "pro300_14";
			
							break;

					case "pro300_15":   

						$departamento_licenca = "pro300_15";
				
								break;



         

		 endswitch;

	    $result_events = 'UPDATE events 

		                  SET responsavel="'.$responsavel.'",telefone="'.$telefone.'",email="'.$email.'",title="'.$title.'", color="'.$status_cor[1].'", start="'.$start_sem_barra.'", end="'.$end_sem_barra.'", aud="'.$aud_sigla[1].'",local="'.$local.'", setor="'.$setor.'", status="'.$status_cor[0].'", sigla="'.$aud_sigla[0].'", tsinal="'.$tsinalx[0].'", tsinal2="'.$tsinalx[1].'", formato="'.$formato.'", modificadoPor="'.$_SESSION['usuarioNome'].'", nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'", modificadoEm="'.$dateTime.'" 

						  WHERE id="'.$id.'" ';

		$resultado_events = mysqli_query($conn, $result_events);

		

		//departamento ***Foruns/DEDES/	GABINETE***

	elseif($licenca_departamento == "pro100_02" and $departamento['departamento'] =="DINSE" 
			or $licenca_departamento == "pro100_03" and $departamento['departamento'] =="DEDES"
			or $licenca_departamento == "pro500_07" and $departamento['departamento'] =="DEDES"
			or $licenca_departamento == "Zoom_1000" and $departamento['departamento'] =="DEDES"
			or $licenca_departamento == "pro300_10" and $departamento['departamento'] =="DEDES"
			or $licenca_departamento == "pro300_11" and $departamento['departamento'] =="DEDES"
			or $licenca_departamento == "pro300_12" and $departamento['departamento'] =="DEDES"
			or $licenca_departamento == "pro300_13" and $departamento['departamento'] =="DEDES"
			
			or$licenca_departamento == "Zoom_1000" and $departamento['departamento'] =="GABINETE"
			or $licenca_departamento == "Zoom_500" and $departamento['departamento'] =="GABINETE"
			or $licenca_departamento == "pro100_06" and $departamento['departamento'] =="GABINETE"
			or $licenca_departamento == "pro300_18" and $departamento['departamento'] =="GABINETE"
			
			or $licenca_departamento == "pro300_13" and $departamento['departamento'] =="DINSE"
		 	or $licenca_departamento == "pro100_02" and $departamento['departamento'] =="DINSE"):

	    switch($licenca_departamento):

						case "pro300_10":		  
							$departamento_licenca = "pro300_10";
							break;
						case "pro300_11":    

							$departamento_licenca = "pro300_11";

							break;

							case "pro300_12":		  
								$departamento_licenca = "pro300_12";
								break;
						case "pro300_13":    
				
							$departamento_licenca = "pro300_13";
				
								break;


								case "pro300_18":    
				
									$departamento_licenca = "pro300_18";
						
										break;

								case "pro500_07":

									

									$departamento_licenca = "pro500_07";

									break;

									// GABINETE

								case "Zoom_1000":

									

									$departamento_licenca = "Zoom_1000";

									break;
									case "Zoom_500":
										
										$departamento_licenca = "Zoom_500";
										break;


									case "pro100_06":
							
										$departamento_licenca = "pro100_06";
										break;
	  
					

		 endswitch;

	    $result_events = 'UPDATE events SET responsavel="'.$responsavel.'",
		telefone="'.$telefone.'",
		email="'.$email.'",
		title="'.$title.'", 
		color="'.$status_cor[1].'",
	    start="'.$start_sem_barra.'",
		end="'.$end_sem_barra.'",
	    aud="'.$aud_sigla[1].'",
	    local="'.$local.'",
	    setor="'.$setor.'",
		status="'.$status_cor[0].'",
		sigla="'.$aud_sigla[0].'",
		tsinal="'.$tsinalx[0].'",
		tsinal2="'.$tsinalx[1].'",
		formato="'.$formato.'",
		modificadoPor="'.$_SESSION['usuarioNome'].'",
		nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'",
	     modificadoEm="'.$dateTime.'" 
   		  WHERE id="'.$id.'" ';

		$resultado_events = mysqli_query($conn, $result_events);

		


		//departamento ***GABINETE***

	/*elseif($licenca_departamento == "pro100_06" and $departamento['departamento'] =="GABINETE"):

	    switch($licenca_departamento):

		 case "pro100_06":

			  

			  $departamento_licenca = "pro100_06";

			  break;

		 	 endswitch;

	    $result_events = 'UPDATE events 

		                  SET responsavel="'.$responsavel.'",telefone="'.$telefone.'",email="'.$email.'",title="'.$title.'", color="'.$status_cor[1].'", start="'.$start_sem_barra.'", end="'.$end_sem_barra.'", aud="'.$aud_sigla[1].'", local="'.$local.'", setor="'.$setor.'", status="'.$status_cor[0].'", sigla="'.$aud_sigla[0].'", tsinal="'.$tsinalx[0].'", tsinal2="'.$tsinalx[1].'", modificadoPor="'.$_SESSION['usuarioNome'].'", nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'", modificadoEm="'.$dateTime.'" 

						  WHERE id="'.$id.'" and aud="'.$departamento_licenca.'" ';

		$resultado_events = mysqli_query($conn, $result_events); */

	else:

		echo  "<script> window.alert ('Erro ao alterar o evento! Favor veficar se o seu departamento tem autorização para licença desejada! - O Evento não foi alterado.');



		window.location.href='principal.php'



	 </script>";

	

	endif;

	/*

		$result_events = 'UPDATE events SET

		responsavel="'.$responsavel.'",telefone="'.$telefone.'",email="'.$email.'",title="'.$title.'", color="'.$status_cor[1].'", start="'.$start_sem_barra.'", end="'.$end_sem_barra.'", aud="'.$aud_sigla[1].'", setor="'.$setor.'", status="'.$status_cor[0].'", sigla="'.$aud_sigla[0].'", modificadoPor="'.$_SESSION['usuarioNome'].'", nivel_cadastro="'.$_SESSION['usuarioNiveisAcessoId'].'", modificadoEm="'.$dateTime.'" WHERE id="'.$id.'"';

		$resultado_events = mysqli_query($conn, $result_events);

     */

		

		//Verificar se alterou no banco de dados através "mysqli_affected_rows"

		

		if(mysqli_affected_rows ($conn)){

			

			

			echo  "<script> window.alert ('Perfeito! O Evento alterado com sucesso!'); 

					window.location.href='principal.php'

				</script>";





			

		}else{

		

			echo  "<script> window.alert ('Erro ao alterar o evento! Favor veficar se o seu departamento tem autorização para licença desejada!
			O Evento não foi editado.');

				window.location.href='principal.php'

				</script>";




		}

	}

	



}else  //SE O INTERVALO DOS HORÁRIOS FOR ENCONTRADO NO BANCO PARA AQUELE AUDITÓRIO

{

	echo  "<script> window.alert ('Horário já reservado! O intervalo entre os eventos são de 2 horas.'); 

				 window.location.href='principal.php'

			  </script>";

}