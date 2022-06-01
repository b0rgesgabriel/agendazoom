<?php

session_start();
date_default_timezone_set('America/Sao_Paulo');

//Incluir conexao com BD
include_once("../conexao.php");
// FUNÇÕES QUE CONVERTEM FORMATOS DAS DATAS E CRIAM O PERÍODO DE TOLERÂNCIA DE 110 MINUTOS ENTRE EVENTOS



function converteDataMenor($date_str)
{
	$date = DateTime::createFromFormat('d/m/Y H:i', $date_str);
	$date->modify('-110 minutes');
	return $date->format('Y-m-d H:i:s');	}

function converteDataMaior($date_str){

	$date = DateTime::createFromFormat('d/m/Y H:i', $date_str);
	$date->modify('+110 minutes');
    return $date->format('Y-m-d H:i:s');	
}
function converteData($date_str)

{	$date = DateTime::createFromFormat('d/m/Y H:i', $date_str);
	return $date->format('Y-m-d H:i:s');	
}

$userDepartamento = $_SESSION['usuarioDepartamento'];
$nivelLogado = $_SESSION['usuarioNiveisAcessoId'];//nível 1 = administrador, nível 2 = funcionário, nível 3 = secge, nível 4 consulta>
$dateTime = date('Y-m-d H:i:s');//FORMATO AMERICANO PARA COMPARAÇÃO DE DATAS
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
$startMenor = converteDataMenor($start);//pega o início do evento menos 29 minutos
$startMaior = converteDataMaior($start);//pega o início do evento mais 29 minutos
$endMenor = converteDataMenor($end);//pega o fim do evento menos 29 minutos
$endMaior = converteDataMaior($end);//pega o fim do evento mais 29 minutos

// ----------------------------------------------------------------------------------- //
$aud = filter_input(INPUT_POST, 'aud', FILTER_SANITIZE_STRING);
$setor = filter_input(INPUT_POST, 'setor', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
$tsinal = filter_input(INPUT_POST, 'tsinal', FILTER_SANITIZE_STRING);
$tsinal2 = filter_input(INPUT_POST, 'tsinal2', FILTER_SANITIZE_STRING);
$local = filter_input(INPUT_POST, 'local', FILTER_SANITIZE_STRING);
$formato= filter_input(INPUT_POST, 'formato', FILTER_SANITIZE_STRING);

//PEGA O NOME DO AUDITÓRIO SEM A SIGLA

//$audConsulta = substr($aud, 6);

$audio = explode(".",$aud);

//var_dump($audio[0]);

echo "<br>";

//var_dump($audio[1]);

$testeGeraldo = $audio[0];

//var_dump($audio);

//VERIFICA SE O INTERVALO DOS HORÁRIOS DIGITADOS PELO USUÁRIO ESTÃO NO START E NO END DO BANCO, SE ESTIVER NÃO PERMITE CADASTRAR/EDITAR



$sqlverificaInicio = "SELECT * FROM events WHERE ('$startMenor' BETWEEN  start AND end AND status != 2  AND aud = '$testeGeraldo') OR ('$startMaior' BETWEEN start AND end  AND status != 2 AND aud = '$testeGeraldo')";

						  

$sqlverificaFim = "SELECT * FROM events WHERE ('$endMenor' BETWEEN start AND end AND status != 2 AND aud = '$testeGeraldo') OR ('$endMaior' BETWEEN start AND end AND status != 2  AND aud = '$testeGeraldo')";

					       

$verificaInicio = mysqli_query($conn, $sqlverificaInicio) or die(mysqli_error($conn));					  

$verificaFim = mysqli_query($conn, $sqlverificaFim) or die(mysqli_error($conn));					  

//var_dump($sqlverificaInicio);

//echo "<br>";

//var_dump($endMenor);

/*$verificaInicio = mysqli_query($conn, "SELECT * FROM events 

										WHERE '$startMenor' 

										BETWEEN start AND end 

										AND aud = '$aud' 

										AND status != 2 



										OR '$startMaior' BETWEEN start AND end 

										AND aud = '$aud' 

										AND status != 2");*/

										

/*$verificaFim = mysqli_query($conn, "SELECT * FROM events 

									WHERE '$endMenor' 

									BETWEEN start AND end 

									AND aud = '$aud' 

									AND status != 2 



									OR '$endMaior' BETWEEN start AND end 

									AND aud = '$aud' 

									AND status != 2");*/



$linhaInicio = mysqli_num_rows($verificaInicio);

$linhaFim = mysqli_num_rows($verificaFim);




if (($linhaInicio == 0) && ($linhaFim == 0) ){



	//CHECA SE DATA/HORA INICIAL É IGUAL OU MENOR A DATA/HORA FINAL, CASO SIM NÃO PERMITE CADASTRAR/EDITAR

		// funcionando 15/03/2021

	if($startConvert === $endConvert){

		echo  "<script> window.alert ('Evento não cadastrado! Data inicial e data final não podem ser iguais!'); 
				 window.location.href='principal.php'
			  </script>";
		return false;	  
	}


	else if($startConvert > $endConvert){

			// funcionando 15/03/2021
		echo  "<script> window.alert (' Evento não cadastrado! Data final não pode ser menor que a data inicial!'); 
		    event.preventdefault();
			 window.location.href='principal.php'
			  </script>";
		return false;
	}

	else if(($startConvert < $dateTime) || ($endConvert < $dateTime)){
		// funcionando 15/03/2021
		echo  "<script> window.alert ('Data selecionada anterior a hoje!');
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

	//$aud_cor = explode(".", $aud);   cor para auditorio
	$status_cor = explode(".", $status);
	$aud_sigla = explode(".", $aud);
	$tsinalx = explode(".", $tsinal);////////
	$data = explode(" ", $end);
	list($date, $hora) = $data;


	$data_sem_barra = array_reverse(explode("/", $date));
	$data_sem_barra = implode("-", $data_sem_barra);
	$end_sem_barra = $data_sem_barra . " " . $hora;

	

	

	$us = $_SESSION['usuarioId'];
	$sql = "SELECT departamento from usuarios where id = '$us' ";
	$sqlResult = mysqli_query($conn, $sql) or die(mysqli_erro($conn));
	$departamento = mysqli_fetch_assoc($sqlResult);
	
	//$departamento['departamento'];



	//var_dump($departamento['departamento']);

	//echo "<br>";

	//$audio = explode(".",$aud);

	//echo "<br>";

    $licenca_departamento = $audio[1];

	//var_dump($licenca_departamento);


/*   $formato ['formato'];
	if (($local != " ") && ($aud != " ") )	{

		$formato = "HIBRIDO";

	} else if (($local == " ") && ($aud != " ") ){

		$formato = "ON LINE";

	} else (($local != " ") && ($aud == " ") ){

		$formato = "Presencial";
	}
*/

		

		


 //-----------Cadstro de evento são inseridos relacionando licenças  com os departamantos 

        



//OK

	if($licenca_departamento == "pro100_01" and $departamento['departamento'] =="DEAMA"):

		//departamento ***Cursos DEAMA***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'", "'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'","'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events);





		elseif($licenca_departamento == "pro300_17" and $departamento['departamento'] =="DEAMA"):

		

			//departamento ***Cursos Livres/pro100_17---  DEAMA***
	
			$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
	
							  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'","'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
	
			$resultado_events = mysqli_query($conn, $result_events);

        

        //-------------------------------------------------------licenças DEDES

        

        //OK

         elseif($licenca_departamento == "pro100_02" and $departamento['departamento'] =="DINSE"):

		

		//departamento ***Cursos Livres/pro100_02 ---  DINSE***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'","'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events);

        

        //OK

         elseif($licenca_departamento == "pro100_03" and $departamento['departamento'] =="DEDES"):

		

		//departamento ***Cursos Livres/ pro100_03  DEDES***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'", "'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'",  "'.$tsinalx[0].'", "'.$tsinalx[1].'","'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events);

        

          //OK

       



		elseif($licenca_departamento == "pro300_10" and $departamento['departamento'] =="DEDES"):

		

			//departamento ***Cursos Livres/DEDES***
	
			$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
	
							  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
	
			$resultado_events = mysqli_query($conn, $result_events);




			elseif($licenca_departamento == "pro300_11" and $departamento['departamento'] =="DEDES"):

		

				//departamento ***Cursos Livres/DEDES***
		
				$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
		
								  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
		
				$resultado_events = mysqli_query($conn, $result_events);



				elseif($licenca_departamento == "pro300_12" and $departamento['departamento'] =="DEDES"):

		

					//departamento ***Cursos Livres/DEDES***
			
					$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
			
									  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
			
					$resultado_events = mysqli_query($conn, $result_events);





					elseif($licenca_departamento == "pro300_13" and $departamento['departamento'] =="DINSE"):

		

						//departamento ***Cursos Livres/DINSE***
				
						$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
				
										  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
				
						$resultado_events = mysqli_query($conn, $result_events);
	

        

        

         //OK  licença zoom_1000  pode cadastrar GABINETE E  DEDES

        

        elseif($licenca_departamento == "Zoom_1000" and $departamento['departamento'] =="DEDES"):

		

		//departamento ***Cursos Livres/GABINETE***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events); 



         //-------------------------------------------------------licenças DEDES

        

    //OK 

	    elseif($licenca_departamento == "pro100_04" and $departamento['departamento'] =="DENSE"):

		

		//departamento ***Cursos Livres/DENSE***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events);

            

     //ERRO cadastrando mesmo horario  

	   elseif($licenca_departamento == "pro100_05" and $departamento['departamento'] =="DENSE"):

		

		//departamento ***Cursos Livres/DENSE***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'",  "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events);





		elseif($licenca_departamento == "pro300_14" and $departamento['departamento'] =="DENSE"):

		

			//departamento ***Cursos Livres/DENSE***
	
			$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
	
							  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'",  "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
	
			$resultado_events = mysqli_query($conn, $result_events);






			elseif($licenca_departamento == "pro300_15" and $departamento['departamento'] =="DENSE"):

		

				//departamento ***Cursos Livres/DENSE***
		
				$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
		
								  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'",  "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
		
				$resultado_events = mysqli_query($conn, $result_events);

				







                 //-------------------------------------------------------licenças GABINETE

        //OK

        elseif($licenca_departamento == "pro100_06" and $departamento['departamento'] =="GABINETE"):

		

		//departamento ***Cursos Livres/DENSE***

		$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 

		                  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'",  "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

		$resultado_events = mysqli_query($conn, $result_events);






		elseif($licenca_departamento == "pro300_18" and $departamento['departamento'] =="GABINETE"):

		

			//departamento ***Cursos Livres/DENSE***
	
			$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
	
							  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'",  "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
	
			$resultado_events = mysqli_query($conn, $result_events);

			





		elseif($licenca_departamento == "Zoom_1000" and $departamento['departamento'] =="GABINETE"):

		

			//departamento ***Cursos Livres/GABINETE***
	
			$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
	
							  VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$local.'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'", "'.$tsinalx[0].'", "'.$tsinalx[1].'", "'.$formato.'", "'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';
	
			$resultado_events = mysqli_query($conn, $result_events); 
	
			

			elseif ($licenca_departamento == "Zoom_500" and $departamento['departamento'] == "GABINETE") :

				//departamento ***/GABINETE***
	
				$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud,local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
							  VALUES ("' . $responsavel . '","' . $telefone . '","' . $email . '","' . $title . '","'.$status_cor[1].'", "' . $start_sem_barra . '", "' . $end_sem_barra . '", "' . $aud_sigla[1] . '","'.$local.'", "'.$setor.'","' . $status_cor[0] . '","' . $aud_sigla[0] . '", "'.$tsinalx[0].'",  "'.$tsinalx[1].'","'.$formato.'", "' . $_SESSION['usuarioId'] . '","'. $dateTime . '", "' . $_SESSION['usuarioNiveisAcessoId'] . '")';
	
	
	
				$resultado_events = mysqli_query($conn, $result_events);



					// Departamento ALL são para todos os perfis que cadastram e editam um evento que não possui licença zoom
				elseif ($licenca_departamento == "não_se_aplica" and $departamento['departamento'] == "ALL") :

					//departamento ***/GABINETE***
		
					$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud,local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
								  VALUES ("' . $responsavel . '","' . $telefone . '","' . $email . '","' . $title . '","'.$status_cor[1].'", "' . $start_sem_barra . '", "' . $end_sem_barra . '", "' . $aud_sigla[1] . '","'.$local.'", "'.$setor.'","' . $status_cor[0] . '","' . $aud_sigla[0] . '", "'.$tsinalx[0].'",  "'.$tsinalx[1].'","'.$formato.'", "' . $_SESSION['usuarioId'] . '","'. $dateTime . '", "' . $_SESSION['usuarioNiveisAcessoId'] . '")';
		
		
		
					$resultado_events = mysqli_query($conn, $result_events);
					


						// insert para eventos sem licença zoom
					elseif ($licenca_departamento == "não_se_aplica" and $departamento['departamento'] =! "0") :

						//departamento ***/GABINETE***
			
						$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud,local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
									  VALUES ("' . $responsavel . '","' . $telefone . '","' . $email . '","' . $title . '","'.$status_cor[1].'", "' . $start_sem_barra . '", "' . $end_sem_barra . '", "' . $aud_sigla[1] . '","'.$local.'", "'.$setor.'","' . $status_cor[0] . '","' . $aud_sigla[0] . '", "'.$tsinalx[0].'",  "'.$tsinalx[1].'","'.$formato.'", "' . $_SESSION['usuarioId'] . '","'. $dateTime . '", "' . $_SESSION['usuarioNiveisAcessoId'] . '")';
			
			
			
						$resultado_events = mysqli_query($conn, $result_events);


					




				elseif ($licenca_departamento == "Feriado" and $departamento['departamento'] == "DETEC") :

					//departamento ***/GABINETE***
		
					$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2, formato, cadastradoPor, dataCadastro, nivel_cadastro) 
								  VALUES ("' . $responsavel . '","' . $telefone . '","' . $email . '","' . $title . '", "'.$status_cor[1].'", "' . $start_sem_barra . '", "' . $end_sem_barra . '", "' . $aud_sigla[1] . '","'.$local.'","' . $setor . '","' . $status_cor[0] . '","' . $aud_sigla[0] . '",  "'.$tsinalx[0].'", "'.$tsinalx[1].'","'.$formato.'","' . $_SESSION['usuarioNome'] . '","' . $dateTime . '", "' . $_SESSION['usuarioNiveisAcessoId'] . '")';
						
					$resultado_events = mysqli_query($conn, $result_events);
                      

      

        else:

        

		echo  "<script> window.alert ('Evento não cadastrado! Esta Licença pertence a outro departamento!'); 


	    </script>";    

      

	endif;

}

/*

	$result_events = 'INSERT INTO events (responsavel, telefone, email, title, color, start, end, aud, setor, status, sigla, cadastradoPor, dataCadastro, nivel_cadastro) 

	VALUES ("'.$responsavel.'","'.$telefone.'","'.$email.'","'.$title.'", "'.$status_cor[1].'", "'.$start_sem_barra.'", "'.$end_sem_barra.'", "'.$aud_sigla[1].'","'.$setor.'","'.$status_cor[0].'","'.$aud_sigla[0].'","'.$_SESSION['usuarioId'].'","'.$dateTime.'", "'.$_SESSION['usuarioNiveisAcessoId'].'")';

	$resultado_events = mysqli_query($conn, $result_events);

	}

*/	



	if(mysqli_insert_id($conn)){



		echo  "<script> window.alert ('Perfeito! O evento foi cadastrado com sucesso!'); 

				 window.location.href='principal.php'

			  </script>";

	}else{



		echo  "<script> window.alert ('Erro ao cadastrar! O Evento não foi cadastrado.'); 

				 window.location.href='principal.php'

			  </script>";
	}


}else  //SE O INTERVALO DOS HORÁRIOS FOR ENCONTRADO NO BANCO PARA AQUELE AUDITÓRIO


{
	echo  "<script> window.alert (' Não foi possível cadastrar o evento!  O Horário já está reservado! O intervalo entre os eventos são de 2 horas.'); 

				 window.location.href='principal.php'
			  </script>";
}

?>