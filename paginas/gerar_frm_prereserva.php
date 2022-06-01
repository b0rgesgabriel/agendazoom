
 <?php
	session_start();
	// DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');

    $month = date("Y-m");
    $month .= "-01";


    if( isset($_SESSION['usuarioNome'])){
        $user   = utf8_encode($_SESSION['usuarioNome']);
    }
	$inicio = date("Y/m/d", strtotime($_GET['data_inicio']));
	$fim = date("Y/m/d", strtotime('+1 day',strtotime( $_GET['data_fim'])));

	//PARA EXIBIR AS DATAS NO RELATÓRIO NO FORMATO d/m/Y
	$inicioConvert = date("d/m/Y", strtotime($_GET['data_inicio']));
	$fimConvert = date("d/m/Y", strtotime('+1 day',strtotime( $_GET['data_fim'])));
	////////////////////////////////////////////////////
		
	$tipo = $_GET['tipo'];
	include_once('../conexao.php');
	include("../pdf/mpdf.php");


	//CHECA SE AS DATAS SÃO VAZIAS, IGUAIS OU SE A DATA INICIAL É MAIOR QUE A FINAL, CASO SIM RETORNA FALSO
	
	if(empty($_GET['data_inicio']) && empty($_GET['data_fim'])){
			echo '<Script>window.alert ("Por Favor! , Informe o campo data para gerar o relatório ")
            window.location.href="principal.php"; 
            </script>';
			return false;
	}
	else if($inicio === $fim){
		echo  "<script> window.alert ('Data inicial e data final não podem ser iguais!'); 
				window.location.href='principal.php'
			</script>";
		return false;	  
	}
	else if($inicio > $fim){
		echo  "<script> window.alert ('Data final não pode ser menor que a data inicial!'); 
				window.location.href='principal.php'
			</script>";
		return false;
	}
	
// ------------------------------------------------------------- FUNÇÃO PARA GERAR CABEÇALHO ----------------------------------------------------------------
function getHeader($conn)
{
   date_default_timezone_set('America/Sao_Paulo'); 
   $user = utf8_encode($_SESSION['usuarioNome']);//sem o encode dá erro de charset
   $data = date('d/m/Y');
   $hora = date('H:i');
    
    $inicio = date("Y/m/d", strtotime($_GET['data_inicio']));
	$fim = date("Y/m/d", strtotime('+1 day',strtotime( $_GET['data_fim'])));
	//PARA EXIBIR AS DATAS NO RELATÓRIO NO FORMATO d/m/Y

	$inicioConvert = date("d/m/Y", strtotime($_GET['data_inicio']));
	$fimConvert = date("d/m/Y", strtotime('+1 day',strtotime( $_GET['data_fim'])));
    $data = date('Y');
    $hora = date('H:i');    
    $month = date("F"); 
    
         $mes = array(

                    'January' => 'JANEIRO',

                    'February' => 'FEVEREIRO',

                    'March' => 'MARÇO',

                    'April' => 'ABRIL',

                    'May' => 'MAIO',

                    'June' => 'JUNHO',

                    'July' => 'JULHO',
                 
                     'August' => 'AGOSTO',
                 
                     'September' => 'SETEMBRO',
                 
                     'October' => 'OUTUBRO',
                 
                     'November' => 'NOVEMBRO',
                 
                     'December ' => 'DEZEMBRO'
                );  
    
    $retorno = "<html>";

	$retorno .= "
	<table class=\"tbl_header\" width=\"100%\" border=\"1\">
        <tr class=\"centralizar\">  
			 <td rowspan=\"3\">
                <img src=\"../imagens/logo.jpg\" width=\"10%\">
             </td>
             <td colspan=\"4\">
                AGENDA SEMANAL DE EVENTOS WEBINAR’S
             </td>
        </tr>
        <tr>  
             <td>
                Ano: $data
             </td>
             <td>
                Mês:$mes[$month]
             </td>
             <td>
               Semana: ".$inicioConvert." a ".$fimConvert ."
             </td>
             <td>
                Pág. {PAGENO}/{nb}
             </td>
        </tr>
        
    </table>";
	$retorno .= "<body>";
    return $retorno;  
 } 

 // ------------------------------------------------------------- FUNÇÃO PARA GERAR RODAPÉ ----------------------------------------------------------------
function getFooter($user)
{  

   date_default_timezone_set('America/Sao_Paulo'); 
   $user = utf8_encode($_SESSION['usuarioNome']);//sem o encode dá erro de charset
   $data = date('d/m/Y');
   $hora = date('H:i');
   $retorno = "<table class=\"tbl_footer\" width=\"1200\">  
		   <tr>  
			 <td align=\"left\">FFRM-EMERJ-026-04</td>  
             <td align=\"left\">Rev:03</td> 
			 <td align=\"right\">Data: $data</td> 
			 <td align=\"right\">Pág. {PAGENO}/{nb}</td>  
		   </tr>  
		 </table>";
		 
   return $retorno;  
 }

// ----------------------------------------------------------- FUNÇÃO PARA GERAR TABELAS COM FOREACH -------------------------------------------------------

function getTabela($conn, $tipo, $inicio, $fim, $inicioConvert, $fimConvert)
{  

	try //Tratamento de erro Moodle
	{
	
        
    if ($tipo == 'instagram')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'INSTAGRAM' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA DO INSTAGRAM: DE $inicioConvert A $fimConvert";
	}
        else if($tipo == 'moodle')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'MOODLE' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA MOODLE: DE $inicioConvert A $fimConvert";
	}
        
    else if($tipo == 'zoom_500')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'ZOOM_500' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA ZOOM_500 : DE $inicioConvert A $fimConvert";
	}
        else if($tipo == 'zoom_1000')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'ZOOM_1000' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA ZOOM_1000 : DE $inicioConvert A $fimConvert";
	} 
        
        
        
        
        
         else if($tipo == 'webex')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'WEBEX' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA WEBEX : DE $inicioConvert A $fimConvert";
	}
         else if($tipo == 'teams')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'TEAMS' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA TEAMS : DE $inicioConvert A $fimConvert";
	}
        
         else if($tipo == 'webinar')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'WEB' AND status != 5 ORDER BY start";
		$nomeaud = "DA PLATAFORMA WEBINAR : DE $inicioConvert A $fimConvert";
	} 
        
     else if($tipo == 'aca')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'ACA' AND status != 5 ORDER BY start";
		$nomeaud = "DO AUDITÓRIO ANTONIO CARLOS: DE $inicioConvert A $fimConvert";
	}
	else if ($tipo == 'nra')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'NRA' AND status != 5 ORDER BY start";
		$nomeaud = "DO AUDITÓRIO NELSON RIBEIRO ALVES: DE $inicioConvert A $fimConvert";
	}
	else if ($tipo == 'aps')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'APS' AND status != 5 ORDER BY start";
		$nomeaud = "DO AUDITÓRIO PENALVA SANTOS: DE $inicioConvert A $fimConvert";
		
	}else if ($tipo == 'apv')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'APV' AND status != 5 ORDER BY start";
		$nomeaud = "DO AUDITÓRIO PAULO VENTURA: DE $inicioConvert A $fimConvert";
	}else if ($tipo == 'tp')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'TP' AND status != 5 ORDER BY start";
		$nomeaud = "TRIBUNAL PLENO: DE $inicioConvert A $fimConvert";
	}else if ($tipo == 'jnc')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND sigla = 'JNC' AND status != 5 ORDER BY start";
		$nomeaud = "DO JOSÉ NAVEGA CRETTON : DE $inicioConvert A $fimConvert";
	}


	else if ($tipo == 'canc')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND status = 2 ORDER BY start";
		$nomeaud = "DAS RESERVAS CANCELADAS: DE $inicioConvert A $fimConvert";
	}
	else if ($tipo == 'tran')
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND status = 3 ORDER BY start";
		$nomeaud = "DAS TRANSMISSÕES DE SINAL: DE $inicioConvert A $fimConvert";
	}
	else 
	{
		$select = "SELECT * FROM events WHERE start BETWEEN '$inicio' AND '$fim' AND status = 0 ORDER BY start";
		$nomeaud = "DE TODOS OS AUDITÓRIOS: DE $inicioConvert A $fimConvert";
	}
	
		  
      $color  = false;  
      $retorno = "";
      $retorno .= "<table class=\"tabela\" width=\"100%\" cellpadding=\"8\">
		<thead>
            <tr class=\"false-top\">
                <td colspan=\"4\"></td>
            </tr>
            <tr class=\"false-top\">
                <td colspan=\"4\"></td>
            </tr>
            <tr class=\"false-top\">
                <td colspan=\"4\">IMPORTANTE: Sempre verifique no site do TJRJ se a versão impressa do documento está atualizada.</td>
            </tr>
            <tr>
               
                <td width=\"10%\">DATA</td>	
                <td width=\"20%\">DIA da SEMANA</td>			
               	<td width=\"50%\">EVENTO</td>
                <td width=\"20%\">PLATAFORMA</td>
                
            </tr>
		</thead>
        <tbody>";
		
        $contador = 1; //Criei contador para fazer ordenação da tabela
				
		$result = mysqli_query($conn , $select);

		//SE NÃO HOUVER RESULTADOS NO FILTRO DE BUSCA SELECIONADO, EMITE ALERT E RETORNA PARA A PRINCIPAL

		if(mysqli_num_rows($result) == 0){
			echo '<Script>window.alert ("Não há resultados para o filtro selecionado!")
			window.location.href="principal.php"; 
			</script>';
			break;
		}

		//SE HOUVER RESULTADOS, PROSSEGUE NORMAL
		
		while($reg = mysqli_fetch_assoc($result)){


			//VARIÁVEIS PARA AJUSTE DE EXIBIÇÃO NO RELATÓRIO
			$dt_inicio = date("H:i",strtotime($reg['start']));
			$dt_fim    = date("H:i",strtotime($reg['end']));
            $somenteData = date("d/m/Y",strtotime($reg['start']));
            $diaSemana = date("D",strtotime($reg['start']));
            
             $semana = array(
                    'Sun' => 'Domingo',
                    'Mon' => 'Segunda-Feira',
                    'Tue' => 'Terca-Feira',
                    'Wed' => 'Quarta-Feira',
                    'Thu' => 'Quinta-Feira',
                    'Fri' => 'Sexta-Feira',
                    'Sat' => 'Sábado'
                );

                     

            
                
                
            
			$dt_final  = date("d/m/Y H:i",strtotime($reg['modificadoEm']));
			if ($reg['dataCadastro'] == '0000-00-00 00:00:00'|| $reg['dataCadastro'] == NULL || $reg['cadastradoPor'] == NULL){
				$dt_cad = '-';
				$cadastradoPor = '-';
			}
			else{
				$dt_cad = date("d/m/Y H:i",strtotime($reg['dataCadastro']));
				$cadastradoPor = $reg['cadastradoPor'];
			}

			if ($reg['solicitante'] == ''){
				$exibeSolicitante = 'NÃO';
				$exibeObs = '-';
			}
			else {
				$exibeSolicitante = $reg['solicitante'];
				$exibeObs = $reg['obs'];
			}
            
            
            if ($reg['aud'] == 'feriado'){

				$plataforma = '';

				

			} else {

				$plataforma = $reg['aud'];

            }
            
            
            
            

			if ($reg['status'] == 0) {
				$exibeStatus = "Pré-reserva";
			} else if ($reg['status'] == 1) {
				$exibeStatus = "Confirmado-FRM";
			}else if ($reg['status'] == 2) {
				$exibeStatus = "Cancelado";
			} else if ($reg['status'] == 3) {
				$exibeStatus = "Transmissão de sinal";
			}

			if ($reg['title'] == '') {
				$exibetitle = "-";
			}
			else{
				$exibetitle = $reg['title'];
			}
			if ($reg['contato'] == ''){
				$exibeContato = "-";
			}
			else{
				$exibeContato = $reg['contato'];
			}
			if ($reg['telefone'] == ''){
				$exibeTelefone = "-";
			}
			else{
				$exibeTelefone = $reg['telefone'];
			}
			if ($reg['email'] == ''){
				$exibeEmail = "-";
			}
			else{
				$exibeEmail = $reg['email'];
			}
			if ($reg['modificadoPor'] == ''){
				$exibeModificador = "-";
			}
			else{
				$exibeModificador = $reg['modificadoPor'];
			}
			/////////////////////////////////////////////////

			$retorno .= ($color) ? "<tr>" : "<tr class=\"zebra\">";  
					
		   
            
            
            
		    $retorno .= "<td>{$somenteData}</td>";
			$retorno .= "<td>$semana[$diaSemana] {$dt_inicio} às {$dt_fim}</td>";	
            $retorno .= "<td>{$exibetitle}</td>";
            $retorno .= "<td>{$plataforma}</td>";

			
			
		
          
			$retorno .= "</tr>";  
			$color = !$color;  
			$contador++;
         }
			
        $retorno .= "</tbody></table>";
		$retorno .= "<br>"; 
		$retorno .= "</body>"; 
		$retorno .= "</html>"; 
		return $retorno;  
		
	}catch(mysqli_sql_exception $e)
	{
		echo $e; //Caso tenha erro, exibo mensagem
	}
}
	
//==============================================================
//==============================================================

$mpdf=new mPDF('c','A4-L','','',5,5,25,15,5,5); //Margem para o PDF no formato A4 e orientação paisagem (A4-L)

$mpdf->SetDisplayMode('fullpage');

$css = file_get_contents('../css/relatorio.css'); //Aponto para o arquivo Css 

$mpdf->WriteHTML($css,1);	// Parametro para importar CSS -> importante!

$mpdf->SetHTMLHeader(getHeader($conexao, 'O',true)); //Faço exibição do cabeçalho 

$mpdf->SetHTMLFooter(getFooter($retorno,$user)); //Faço exibição do rodapé

$mpdf->WriteHTML(getTabela($conn, $tipo, $inicio, $fim, $inicioConvert, $fimConvert)); //Faço exibição da tabela com os dados do Sistema

$mpdf->Output('relatorio"' . date('d_m_Y_H_i') . '.pdf', 'D');//Força o download e acrescenta a data e hora atual no nome do arquivo

exit;

?>

