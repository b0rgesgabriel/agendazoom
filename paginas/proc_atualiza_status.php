<?php
session_start();

//pega a hora atual e atualiza o log no banco
date_default_timezone_set('America/Sao_Paulo');
$dateTime = date('Y-m-d H:i:s');

//Incluir conexao com BD
include_once("../conexao.php");

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$solicitante = filter_input(INPUT_POST, 'solicitante', FILTER_SANITIZE_STRING);
$obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_STRING);
$color = '#DF0101';	




 $consul_cad = 'select cadastradoPor from events where id='.$id; 
 $consul_cadrs = mysqli_query($conn, $consul_cad);
 $row_consul_cad = mysqli_fetch_assoc($consul_cadrs);
    
    

  if(!empty($id) && !empty($solicitante)&& $row_consul_cad["cadastradoPor"] == $_SESSION['usuarioId']){
	//Converter a data e hora do formato brasileiro para o formato do Banco de Dados
	
	
	$result_events = 'UPDATE events SET status=2, solicitante= "'.$solicitante.'", obs= "'.$obs.'", color="'.$color.'",  modificadoPor= "'.$_SESSION['usuarioNome'].'", modificadoEm="'.$dateTime.'" WHERE id='.$id;
	$resultado_events = mysqli_query($conn, $result_events);
	
	//Verificar se alterou no banco de dados através "mysqli_affected_rows"
	if(mysqli_affected_rows ($conn)){
		
		echo  "<script> window.alert ('Evento cancelado com sucesso!'); 
				 window.location.href='principal.php'
			  </script>";
	}else{

		echo  "<script> window.alert ('Erro ao cancelar evento!'); 
						window.location.href='principal.php'
		 	  </script>";
	}
	
}else{

	echo  "<script> window.alert ('Não é permitido cancelar evento de outra pessoa.'); 
			window.location.href='principal.php'
			</script>";
}