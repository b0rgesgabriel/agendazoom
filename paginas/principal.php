<?php session_start();
$month = date("Y-m");
$month .= "-01";

if (!isset($_SESSION['usuarioNome']) or !isset($_SESSION['usuarioId']) or !isset ($_SESSION['usuarioNiveisAcessoId']) or !isset($_SESSION['usuarioEmail'])){
	unset(
		$_SESSION['usuarioId'],
		$_SESSION['usuarioNome'],
		$_SESSION['usuarioNiveisAcessoId'],
	    $_SESSION['usuarioEmail'],
        $_SESSION['usuarioDepartamento']
	);
	//redirecionar o usuario para a página de login
	header("Location: ../index.php");
}

if (isset($_POST['status'])){
  $status = $_POST['status'];
}

include_once("../conexao.php");


$result_nivel_id = "SELECT nivel_acesso_id FROM usuarios";
//    LÓGICA PARA EXIBIÇÃO DOS AUDITÓRIOS NO CALENDÁRIO  //
        if(isset($_GET['nomeaud']))
        {
        $nomeaud = $_GET['nomeaud'];
        }
        else
        {
            $nomeaud = '';
        }

    $dateTime = date('d-m-Y H:i:s');//FORMATO AMERICANO PARA COMPARAÇÃO DE DATAS
//eventos anteriores ao mês vigente não são exibidos no calendário, apenas nos relatórios
  //  $result_events = "SELECT * FROM events WHERE start >= CURDATE()";


  //$result_events = "SELECT id, responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2,formato, cadastradoPor, dataCadastro, modificadoPor, nivel_cadastro, modificadoEm, solicitante, obs FROM events";

 //$result_events = "SELECT id, responsavel, telefone, email, title, color, start, end, aud, local, setor, status, sigla, tsinal, tsinal2,formato, cadastradoPor, dataCadastro, modificadoPor, nivel_cadastro, modificadoEm, solicitante, obs FROM events";
 
 
 
 $result_events = "SELECT * FROM events WHERE start != 0 ";
 
 if ($nomeaud == 'WEBEX')
    {
       $subtitulo = "Plataforma WEBEX";
       $result_events .= " AND sigla = '$nomeaud' ";
      //  $result_events .= " AND sigla = '$nomeaud' AND status != 2";
        }          
     else if  ($nomeaud == 'PRO500_07')
   {
     $subtitulo = "Plataforma PRO500_07";
     $result_events .= " AND sigla = '$nomeaud' ";
   //  $result_events .= " AND sigla = '$nomeaud' AND status != 2";
    }  
    else if  ($nomeaud == 'ZOOM_1000')
    {
     $subtitulo = "Plataforma ZOOM_1000";   
    $result_events .= " AND sigla = '$nomeaud'";
    }  
    else if  ($nomeaud == 'PRO100_01')
      {
       $subtitulo = "Plataforma PRO100_01";
       $result_events .= " AND sigla = '$nomeaud'";
      }
     else if  ($nomeaud == 'PRO100_02')
      {
       $subtitulo = "Plataforma PRO100_02";
       $result_events .= " AND sigla = '$nomeaud' ";
       }
        else if  ($nomeaud == 'PRO100_03')
       {
        $subtitulo = "Plataforma PRO100_03";
        $result_events .= " AND sigla = '$nomeaud'";
        }
        else if  ($nomeaud == 'PRO100_04')
       {
        $subtitulo = "Plataforma PRO100_04";
        $result_events .= " AND sigla = '$nomeaud' ";
       }
       else if  ($nomeaud == 'PRO100_05')
        {
         $subtitulo = "Plataforma PRO100_05";
         $result_events .= " AND sigla = '$nomeaud' ";
       }
        else if  ($nomeaud == 'PRO100_06')
       {
        $subtitulo = "Plataforma PRO100_06";
        $result_events .= " AND sigla = '$nomeaud'";
        }
        else if  ($nomeaud == 'PRO300_10')
       {
        $subtitulo = "Plataforma PRO300_10";
        $result_events .= " AND sigla = '$nomeaud'";
        }

        else if  ($nomeaud == 'PRO300_11')
       {
        $subtitulo = "Plataforma PRO300_11";
        $result_events .= " AND sigla = '$nomeaud' ";
        }
        else if  ($nomeaud == 'PRO300_12')
       {
        $subtitulo = "Plataforma PRO300_12";
        $result_events .= " AND sigla = '$nomeaud'";
        }
        else if  ($nomeaud == 'PRO300_13')
       {
        $subtitulo = "Plataforma PRO300_13";
        $result_events .= " AND sigla = '$nomeaud'";
        }
        else if  ($nomeaud == 'PRO300_14')
       {
        $subtitulo = "Plataforma PRO300_14";
        $result_events .= " AND sigla = '$nomeaud'";
        }
        else if  ($nomeaud == 'PRO300_15')
       {
        $subtitulo = "Plataforma PRO300_15";
        $result_events .= " AND sigla = '$nomeaud' ";
        }
        else if  ($nomeaud == 'PRO300_17')
        {
         $subtitulo = "Plataforma PRO300_17";
         $result_events .= " AND sigla = '$nomeaud'  ";
         }
         else if  ($nomeaud == 'PRO300_18')
         {
          $subtitulo = "Plataforma PRO300_18";
          $result_events .= " AND sigla = '$nomeaud' ";
          }
          else if ($nomeaud == 'CANC')
          {
            $subtitulo = "Reservas Canceladas";
            $result_events .= " AND status = 2"; 
          }
           else if  ($nomeaud == 'NÃO_SE_APLICA')
         {
          $subtitulo = "Eventos Presenciais";
          $result_events .= " AND sigla = '$nomeaud' ";
          }
          else if  ($nomeaud == 'FACEBOOK')
          {
           $subtitulo = "Transmissão por FACEBOOK";
           $result_events .= " AND tsinal = '$nomeaud' ";
           }
           else if  ($nomeaud == 'YOUTUBE')
           {
            $subtitulo = "Transmissão por YOUTUBE";
            $result_events .= " AND tsinal = '$nomeaud' ";
            }        
            else if  ($nomeaud == 'INSTAGRAM')
           {
            $subtitulo = "Transmissão por INSTAGRAM";
            $result_events .= " AND tsinal = '$nomeaud' ";
            }
          
          
          else 
         {
          $subtitulo = ""; //Buscar Todas
          $result_events .= " AND status != 4";
         }
         
 $resultado_events = mysqli_query($conn, $result_events);
 $nivelLogado = $_SESSION['usuarioNiveisAcessoId'];//nível 1 = administrador, nível 2 = funcionário, nível 3 = secge, nível 4 consulta>
 $userDepartamento = $_SESSION['usuarioDepartamento'];
 $dateTime = date('d-m-Y H:i:s');//FORMATO AMERICANO PARA COMPARAÇÃO DE DATAS





?>

<!DOCTYPE html>
<html lang="pt-br">

<head ("refresh: 0");>
    <meta charset='utf-8' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="IE=11">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGENDA EMERJ</title>
    <!-- inicio Apple Touch Icons-->
    <link rel="apple-touch-icon" sizes="152x152" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
    <link rel="apple-touch-icon" sizes="144x144" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
    <link rel="apple-touch-icon" sizes="120x120" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
    <link rel="apple-touch-icon" sizes="114x114" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
    <link rel="apple-touch-icon" sizes="76x76" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
    <link rel="apple-touch-icon" sizes="72x72" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
    <!--//////////////////////////////////////////////// -->
    <link href="../css/smartphone.css" rel="stylesheet" media="screen and (min-width:300px) and (max-width:896px)">
    <link href="../css/personalizado.css" rel="stylesheet" media="screen">
    <link href="https://fonts.googleapis.com/css?family=Francois+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Mitr&display=swap" rel="stylesheet">
    <link href="../css/css.css" rel="stylesheet">
    <link href='../css/bootstrap.min.css' rel='stylesheet'>
    <link href='../css/fullcalendar.min.css' rel='stylesheet' />
    <link href='../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link rel="stylesheet" type="text/css" href="../css/print.css" media="print" />
    <link href='../css/personalizado.css' rel='stylesheet' />
    <script src='../js/jquery.min.js'></script>
    <script src="../js/jquery.inputmask.bundle.js"></script><!-- Biblioteca para máscara -->
    <script src='../js/bootstrap.min.js'></script>
    <script src='../js/moment.min.js'></script>
    <script src='../js/fullcalendar.min.js'></script>
    <script src='../locale/pt-br.js'></script>

    <script>
    //CHAMADA DA FUNÇÃO DE CONTROLE DE SESSÃO OCIOSA
    window.onload = function() {
        inactivityTime();
    }
    //MUDA O TAMANHO DO CALENDÁRIO DE ACORDO COM A LARGURA DA TELA

    $(document).ready(function() {
        $('#calendar').fullCalendar({

            height: () => {
                console.log(screen.width);
                if (screen.width < 896) {
                    return 480
                } else {
                    return 1200
                }
            },

            //  Informa o Título do evento no calendário 
            eventRender: function(eventObj, $el) {
                $el.popover({
                    title: eventObj.title,
                    content: eventObj.aud,


                    trigger: 'hover',
                    placement: 'top',
                    container: 'body'
                });
            },

            events: [{
                title: 'title',
                content: 'aud',


            }, ],


            <?php
                   //se não for diretor, botão Novo é habilitado   botão novo 
                   if ($nivelLogado < 8){  
                        ?>
            customButtons: {
                myCustomButton: {
                    text: 'Novo',
                    click: function(start, end) {
                        $('#cadastrar #start').val(moment(start).format('DD/MM/YYYY HH:mm'));
                        $('#cadastrar #end').val(moment(start).format('DD/MM/YYYY HH:mm'));
                        $('#cadastrar').modal('show');


                    }
                }

            },
            customButtons: {
                myCustomButton: {
                    text: 'Novo',
                    click: function(start, end) {
                        $('#excluir #start').val(moment(start).format('DD/MM/YYYY HH:mm'));
                        $('#excluir #end').val(moment(start).format('DD/MM/YYYY HH:mm'));
                        $('#excluir').modal('show');


                    }
                }

            },
            <?php    
                    }
                ?>
            header: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'myCustomButton month,listWeek'
            },
            defaultView: $(window).width() < 896 ? 'listWeek' : 'month',
            timeZone: 'America/Sao_Paulo',
            navLinks: true, // can click day/week names to navigate views
            <?php
                    //se não for diretor, consegue cadastrar evento pelo calendário
                    if ($nivelLogado < 7){
                ?>
            editable: false, // desabilita o drag-and-drop dos eventos
            <?php
                    }        
                ?>
            eventLimit: true, // allow "more" link when too many events


            eventClick: function(event) {
                //var audcolor = event.aud + "." + event.color;
                var statuscolor = event.status + "." + event.color;

                if (event.responsavel == '') {
                    var respText = "Não informado";
                } else {
                    var respText = event.responsavel;
                }
                if (event.telefone == '') {
                    var telText = "Não informado";
                } else {
                    var telText = event.telefone;
                }
                if (event.email == '') {
                    var emailText = "Não informado";
                } else {
                    var emailText = event.email;
                }
                if (event.setor == '') {
                    var setorText = "Não informado";
                } else {
                    var setorText = event.setor;
                }
                if (event.status == 0) {
                    var statusText = "Previsto";
                } else if (event.status == 1) {
                    var statusText = "Confirmado";
                } else if (event.status == 2) {
                    var statusText = "Cancelado";

                } else if (event.status == 3) {
                    var statusText = "Transmissão de Sinal";
                } else if (event.status == 4) {
                    var statusText = "Pré-Agenda";
                } else if (event.status == 5) {
                    var statusText = "Feriado";
                }
                if (event.status == 6) {
                    $("#ocultStatus").css('display', 'none');
                } else {
                    $("#ocultStatus").css('display', 'block');
                }
                if (event.status != 2) {
                    $("#ocultObs").css('display', 'none');
                } else {
                    $("#ocultObs").css('display', 'block');
                }
                // Ocultando o botão de cancelar evento, caso o evento já esteja com o status de cancelado //
                if (event.status == 2) {
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.status == 5 && event.nivelLogado != 1) {
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.nivel_cadastro == 2 && event.nivelLogado == 2) {
                    //secge só pode editar e cancelar o próprio evento
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                } else if (event.nivel_cadastro != 2 && event.nivelLogado == 2) {
                    //eventos cadastrados por outros perfis não podem ser alterados por secge
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.nivel_cadastro == 3 && event.nivelLogado == 3) {
                    //secge só pode editar e cancelar o próprio evento
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                } else if (event.nivel_cadastro != 3 && event.nivelLogado == 3) {
                    //eventos cadastrados por outros perfis não podem ser alterados por secge
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.nivel_cadastro == 4 && event.nivelLogado == 4) {
                    //secge só pode editar e cancelar o próprio evento
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                } else if (event.nivel_cadastro != 4 && event.nivelLogado == 4) {
                    //eventos cadastrados por outros perfis não podem ser alterados por secge
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.nivel_cadastro == 5 && event.nivelLogado == 5) {
                    //secge só pode editar e cancelar o próprio evento
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                } else if (event.nivel_cadastro != 5 && event.nivelLogado == 5) {
                    //eventos cadastrados por outros perfis não podem ser alterados por secge
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.nivel_cadastro == 6 && event.nivelLogado == 6) {
                    //secge só pode editar e cancelar o próprio evento
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                } else if (event.nivel_cadastro != 6 && event.nivelLogado == 6) {
                    //eventos cadastrados por outros perfis não podem ser alterados por secge
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } else if (event.nivel_cadastro == 7 && event.nivelLogado == 7) {
                    //secge só pode editar e cancelar o próprio evento
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                } else if (event.nivel_cadastro != 7 && event.nivelLogado == 7) {
                    //eventos cadastrados por outros perfis não podem ser alterados por secge
                    $(".btn-cancel").hide();
                    $(".btn-canc-vis").hide();
                } 
                
                else {
                    $(".btn-cancel").show();
                    $(".btn-canc-vis").show();
                }


                var sigla = event.sigla;
                var audsigla = sigla + "." + event.aud;
                var tsinalx = event.tsinal + "." + event.tsinal2;
                var formatEnvent = event.formato;
                // var dtCadastro= date('Y-m-d H:i:s');
                // var dataCadastro = dtCadastro;

                $('#visualizar #id').val(event.id);
                $('#visualizar #title').text(event.title); // Add aqui
                $('#visualizar #title').val(event.title); // Add aqui
                $('#visualizar #responsavel').text(respText);
                $('#visualizar #responsavel').val(respText);
                $('#visualizar #setor').text(setorText);
                $('#visualizar #setor').val(setorText);
                $('#visualizar #evento').text(event.evento);
                $('#visualizar #evento').val(event.evento);
                $('#visualizar #start').text(event.start.format('DD/MM/YYYY HH:mm'));
                $('#visualizar #start').val(event.start.format('DD/MM/YYYY HH:mm'));
                if (event.status == 5) {
                    //se evento for feriado, o end não pode ser diferente do start		
                    $('#visualizar #end').text(event.start.format('DD/MM/YYYY HH:mm'));
                    $('#visualizar #end').val(event.start.format('DD/MM/YYYY HH:mm'));
                } else {
                    $('#visualizar #end').text(event.end.format('DD/MM/YYYY HH:mm'));
                    $('#visualizar #end').val(event.end.format('DD/MM/YYYY HH:mm'));
                }
                $('#visualizar #statusExibir').val(statusText); // statusexibir - visualizar eventos
                $('#visualizar #statusExibir').text(
                    statusText); // statusexibir - visualizar eventos
                $('#visualizar #dataCadastro').text(event.dataCadastro);
                $('#visualizar #audExibir').text(event.aud); //nome do auditorio por extenso
                $('#visualizar #aud').val(audsigla);

                $('#visualizar #tsinalx').text(event
                    .tsinal); // conmentei para aparecer a recuperação na tela do editar
                $('#visualizar #tsinal').val(tsinalx);

                $('#visualizar #local').text(event.local);
                $('#visualizar #local').val(event.local);
                $('#visualizar #formatEnvent').text(event.formato);
                $('#visualizar #formato').val(formatEnvent);

                $('#visualizar #solicitante').text(event
                    .solicitante); // statusexibir - visualizar eventos
                //$('#visualizar #solicitante').val(event.solicitante); // statusexibir - visualizar eventos
                $('#visualizar #obs').text(event.observacao);
                //$('#visualizar #obs').val(event.observacao);
                $('#visualizar #status').val(statuscolor);
                $('#visualizar').modal('show');

                return false;
            },
            // aqui onde seleciono a posição na tabela
            <?php
                //o nível é menor que 7  consegue cadastrar evento pelo calendário, acima é ó visualização
                if ($nivelLogado < 8){
                    ?>
            selectable: true,
            selectHelper: true,
            <?php
                }
                ?>
            select: function(start, end) {
                $('#cadastrar #start').val(moment(start).format('DD/MM/YYYY HH:mm:'));
                $('#cadastrar #end').val(moment(start).format(
                    'DD/MM/YYYY HH:mm')); //data final por padrão igual a inicial
                $('#cadastrar').modal('show');


            },
            events: [
                <?php
					while($row_events = mysqli_fetch_array($resultado_events)){
					?> {
                    nivelLogado: '<?= $_SESSION['usuarioNiveisAcessoId'] ?>',
                    nivel_cadastro: '<?= $nivel_cadastro = $row_events['nivel_cadastro'] ?>',
                    id: '<?= $row_events['id'] ?>',
                    responsavel: '<?= $row_events['responsavel'] ?>',
                    telefone: '<?= $row_events['telefone'] ?>',
                    email: '<?= $row_events['email'] ?>',
                    color: '<?= $row_events['color'] ?>',
                    evento: '<?= $row_events['title'] ?>', // Onde exibe o nome do auditório na tabela mes                                         
                    title: '<?=  $row_events['title'] ?>', // sigla no calendário
                    setor: '<?= $row_events['setor'] ?>',
                    status: '<?= $row_events['status'] ?>',
                    aud: '<?= $row_events['aud'] ?>',
                    solicitante: '<?= $row_events['solicitante'] ?>',
                    observacao: '<?= $row_events['obs'] ?>',
                    tsinal: '<?= $row_events['tsinal'] ?>',
                    tsinal2: '<?= $row_events['tsinal2'] ?>',
                    dataCadastro: '<?= $row_events['dataCadastro'] ?>',
                    local: '<?= $row_events['local'] ?>',
                    sigla: '<?= $row_events['sigla'] ?>',
                    formato: '<?= $row_events['formato'] ?>',





                    <?php
                           //para não exibir hora inicial do feriado, não pode ter início e fim diferentes e allDay deve ser true
                            if($row_events['sigla'] == 'FERIADO'){
                               ?>
                    start: '<?= $row_events['start'] ?>',
                    end: '<?= $row_events['start'] ?>',
                    allDay: true
                    <?php
                          }
                            else if($row_events['sigla'] != 'FERIADO'){
                                ?>
                    start: '<?= $row_events['start'] ?>',
                    end: '<?= $row_events['end'] ?>',
                    allDay: false
                    <?php
                            }
                        ?>
                },
                <?php
						}
						?>
            ],
            //acrescenta a hora inicial completa do evento na miniatura do calendário
            timeFormat: 'H:mm' // uppercase H for 24-hour clock
        });
    });
    //Mascara para o campo data e hora
    function DataHora(evento, objeto) {
        var keypress = (window.event) ? event.keyCode : evento.which;
        campo = eval(objeto);
        if (campo.value == '00/00/0000 00:00') {
            campo.value = ""
        }
        caracteres = '0123456789';
        separacao1 = '/';
        separacao2 = ' ';
        separacao3 = ':';
        conjunto1 = 2;
        conjunto2 = 5;
        conjunto3 = 10;
        conjunto4 = 13;
        conjunto5 = 16;

        if ((caracteres.search(String.fromCharCode(keypress)) != -1) && campo.value.length < (16)) {
            if (campo.value.length == conjunto1)
                campo.value = campo.value + separacao1;
            else if (campo.value.length == conjunto2)
                campo.value = campo.value + separacao1;
            else if (campo.value.length == conjunto3)
                campo.value = campo.value + separacao2;
            else if (campo.value.length == conjunto4)
                campo.value = campo.value + separacao3;
            else if (campo.value.length == conjunto5)
                campo.value = campo.value + separacao3;
        } else {
            event.returnValue = false;
        }
    }
    /* função para o funcionamento do botao do menu mobile */
    $(function() {
        $("div#menu-btn").click(function() {
            $("nav ul#menu-mobile").toggle();
        });
        $(window).resize(function() {
            var largura = $(window).width();
            if (largura >= 701) {
                $("nav ul#menu-mobile").hide();
            }
        });
    });

    //  * Função para alterar o tipo do action do form gerar Relatório */    
    function TipoRelatorio() {
        var tipo = document.getElementById("tipo_Relatorio").value;
        if (tipo == "excel") {
            document.form1.action = "gerar_planilha.php";
        } else if (tipo == "pdf") {
            document.form1.action = "gerar_frm_prereserva.php";
        } else if (tipo == "frm") {
            document.form1.action = "gerar_frm.php";
        }
    }

    function TipoRelatorioUser() {
        var tipo = document.getElementById("tipo_RelatorioUser").value;
        if (tipo == "excelUser") {
            document.form2.action = "user_planilha.php";
        } else {
            document.form2.action = "user_relatorio.php";
        }
    }
    </script>

</head>
<link rel="icon" href="../imagens/logo_m_v2_2_fw_P40_2.ico">
<!--////////////////////MENU SUPERIOR DESKTOP /////////-->
<div id="menu_desktop">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">

                <div class="navbar-brand">
                    <svg style="margin-left: 10px; margin-top: -5px;" width="30" height="30">
                        <path d="M0,5 30,5" stroke="#fff" stroke-width="4" />
                        <path d="M0,15 30,15" stroke="#fff" stroke-width="4" />
                        <path d="M0,25 30,25" stroke="#fff" stroke-width="4" />
                    </svg>
                </div>
            </div>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a style="color:white; margin-left:20px;" href="#" class="dropdown-toggle" data-toggle="dropdown"
                        role="button" aria-haspopup="true" aria-expanded="false">Relatórios <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a style="cursor: pointer;" data-toggle="modal"
                                data-target="#exampleModalCenter2">Eventos</a></li>
                        <?php 
               //relatório de usuário apenas para admin
               if ($nivelLogado == 1){
                ?>
                        <li><a style="cursor: pointer;" data-toggle="modal"
                                data-target="#exampleModalCenter4">Usuários</a></li>
                        <?php    
                }
            ?>
                    </ul>
                </li>
                <?php
            if($nivelLogado == 1)
            {
                ?>
                <li><a style="color:white; margin-left:20px; cursor: pointer;" data-toggle="modal"
                        data-target="#exampleModalCenter">Cadastrar Usuários</a></li>
                <li><a style="color:white; margin-left:20px; cursor: pointer;" data-toggle="modal"
                        data-target="#exampleModalCenter3">Redefinir Usuários</a></li>
                <li><a style="color:white; margin-left:20px; cursor: pointer;" data-toggle="modal"
                        data-target="#exampleModalCenter3">Excluir Evento</a></li>
                <?php
            }
        ?>
                <li></li>
            </ul>
            <form class="navbar-form navbar-right">
                <div id="menu_user" class="dropdown" style="margin-right:10px;">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false"><img style="margin-right:5px;" src="../imagens/images_YfL_icon.ico "><span
                            id="bemvindo" ; style="font-size:120%;"> Bem-vindo(a),
                            <?php echo $_SESSION['usuarioNome']?><span class="caret"></span>
                        </span></a>
                    <ul class="dropdown-menu" style="margin-right: 120px; cursor: context-menu;">
                        <li><a data-toggle="modal" data-target="#exampleModalCenter5">Alterar Senha</a></li>
                    </ul>
                    <a id="sair" class="btn btn-default" href="../sair.php" target="_self" title="sair">Sair</a>
            </form>
            <!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div>
<!--//////////////// USERS ONLINE ////////// ---->
<?php    
        if ($nivelLogado == 1){ 
    ?>
<div id="users_online">
    <?php require_once("users_online.php"); ?>
</div>
<?php 
      }
    ?>

<body>
    <!----///////// Menu Mobile ///////////------>
    <nav>
        <img id="imagem_mobile" src="../imagens/logo_M_v2.fw.png" class img-fluid alt="AGENDA" title="EMERJ">
        <ul id="menu-mobile" class="nav navbar-nav">
            <li class="dropdown">
                <a style="margin-bottom:-15px;" href="#" class="dropdown-toggle" data-toggle="dropdown"
                    role="button">RELATÓRIOS<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a style="margin-bottom:-15px;" data-toggle="modal"
                            data-target="#exampleModalCenter2">EVENTOS</a></li>
                    <?php 
                //relatório de usuário apenas para admin
                if ($nivelLogado == 1){
                ?>
                    <li><a style="margin-bottom:-15px;" data-toggle="modal"
                            data-target="#exampleModalCenter4">USUÁRIOS</a></li>
                    <?php } ?>
            </li>
        </ul>
        <?php    
             if ($nivelLogado == 1){ 
                    ?>
        <li><a style="margin-top:10px;" data-toggle="modal" data-target="#exampleModalCenter3">REDEFINIR USUÁRIO</a>
        </li>
        <li><a data-toggle="modal" href="#exampleModalCenter">CADASTRAR USUÁRIO</a></li>
        <?php
                    }
                    ?>
        <li><a href="../sair.php">SAIR</a></li>
        </ul>
        <div id="menu-btn">
            <svg width="30" height="30">
                <path d="M0,5 30,5" stroke="#0c344c" stroke-width="4" />
                <path d="M0,15 30,15" stroke="#0c344c" stroke-width="4" />
                <path d="M0,25 30,25" stroke="#0c344c" stroke-width="4" />
            </svg>
        </div>
    </nav>
    <!-- ///////////////////////////////////////// -->
    <div class="container">
        <div class="page-header">
            <center><img id="imagens_desktop" src="../imagens/logo_M_v2.fw.png" alt="AGENDA" title="EMERJ"></center>
            <h4 id="faixa"><span style="margin-left:30px;"></span></h4><br>
            <?php
             //alerts
                if(isset($_SESSION['msg'])){
                  echo 
              $_SESSION['msg'];
                    unset($_SESSION['msg']);
                 }
            ?>
            <form class="navbar-form navbar-right">
                <div id="bemvindo_mobile" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">Bem-vindo(a), <?php echo $_SESSION['usuarioNome'] ?><span
                            class="caret"></span>
                        </span></a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" data-target="#exampleModalCenter5">Alterar Senha</a></li>
                    </ul>
                </div>
            </form>
        </div>
        <!-- SELECT DOS AUDITÓRIOS NO CALENDÁRIO -->
        <div class="table-responsive">
            <!--------------- SELEÇÃO DOS AUDITÓRIOS NO CALENDÁRIO  tela  principal ------------------------------------------------->
            <form class="form-inline left" method="GET">
                <select id="nomeaud" name="nomeaud" class="form-control" onChange="this.form.submit()">
                    <option value="">Buscar Todas</option>
                    <option value="">Plataformas</option>
                    <!-- <option value="INSTAGRAM">Instagram</option> -->
                    <!-- <option value="MOODLE">Moodle</option>-->
                    <option value="PRO500_07">Pro 500_07</option>
                    <option value="ZOOM_1000">Zoom_1000</option>
                    <option value="PRO100_01">Pro 100_01</option>
                    <option value="PRO100_02">Pro 100_02</option>
                    <option value="PRO100_03">Pro 100_03</option>
                    <option value="PRO100_04">Pro 100_04</option>
                    <option value="PRO100_05">Pro 100_05</option>
                    <option value="PRO100_06">Pro 100_06</option>
                    <option value="PRO300_10">Pro 300_10</option>
                    <option value="PRO300_11">Pro 300_11</option>
                    <option value="PRO300_12">Pro 300_12</option>
                    <option value="PRO300_13">Pro 300_13</option>
                    <option value="PRO300_14">Pro 300_14</option>
                    <option value="PRO300_15">Pro 300_15</option>
                    <option value="PRO300_17">Pro 300_17</option>
                    <option value="PRO300_18">Pro 300_18</option>
                    <option value="YOUTUBE">Sinal Youtube</option>
                    <option value="FACEBOOK">Sinal Facebook</option>
                    <option value="INSTAGRAM">Sinal Instagram</option>
                    <option value="NÃO_SE_APLICA">Presencial</option>
                    <option value="CANC">Cancelado</option>
                     

                    <!--  <option value="TEAMS">Teams</option> -->

                    <!-- <option value="ACA">Antonio Carlos Amorim</option>

                           <option value="JNC">José Navega Cretton</option>
                        <option value="NRA">Nelson Ribeiro Alves</option>
                        <option value="APV">Paulo Ventura</option>
                        <option value="APS">Penalva Santos</option>
                        <option value="TRP">Tribunal Pleno</option>
                        <option value="TRAN">Transmissão de Sinal</option>
                        <option value="CANC">Reservas Canceladas</option> -->
                </select>
            </form>
        </div>



        <!-- SELECT DOS AUDITÓRIOS NO CALENDÁRIO                  
       <div class="table-responsive">
		      SELEÇÃO DOS AUDITÓRIOS NO CALENDÁRIO  tela  principal 
                 <select id="nomeaud" name="nomeaud" class="form-control" onChange="this.form.submit()">
                       <option value="">Transmissões</option>
                      <option value="">Buscar Todas</option>                       
                            <option value="YOUTUBE">YOUTUBE</option>
                            <option value="ZOOM_1000">Zoom_1000</option>
                            <option value="PRO300_01">Pro 300_01</option>
                            <option value="PRO300_02">Pro 300_02</option>                         
				    </select>
                    </form>
        </div> -->

        <!-- SubTitulo da página -->
        <center>
            <div id="sub"><?php echo $subtitulo ?></div>
        </center>
        <br>
        <div id='calendar'></div>
    </div>
    <div class="modal fade" id="visualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        data-backdrop="static" width="150px" ;>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#0c344c;">
                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>
                    <h3 style="color:white" class="modal-title text-center">Informações do Evento</h3>
                </div>
                <div class="modal-body">

                    <div class="visualizar">
                        <dl class="dl-horizontal">
                            <!-- ONDE HÁ DIVS É PARA OCULTAR O CONTEÚDO EM CASOS ESPECÍFICOS -->
                            <dt>Título:</dt>

                            <dd id="title"></dd>
                            <div id="ocultStatus">
                                <dt>Responsável:</dt>
                                <dd id="responsavel"></dd>
                                <dt>Departamento:</dt>
                                <dd id="setor"></dd>
                                <dt>Início:</dt>
                                <dd id="start"></dd>
                                <dt>Fim:</dt>
                                <dd id="end"></dd>
                                <dt>Status:</dt>
                                <dd id="statusExibir"></dd>
                                <dt>Data de Inclusão:</dt>
                                <dd id="dataCadastro"></dd>
                                <dt>Licença Zoom:</dt>
                                <dd id="audExibir"></dd>
                                <dt>Transmissão:</dt>
                                <dd id="tsinalx"></dd>
                                <dt>Local</dt>
                                <dd id="local"></dd>
                                <dt>Formato</dt>
                                <dd id="formatEnvent"></dd>


                            </div>
                            <div id="ocultObs">
                                <dt>Cancelado por:</dt>
                                <dd id="solicitante"></dd>
                                <dt>Observação:</dt>
                                <dd id="obs"></dd>
                            </div>
                            <!--ajax valor -->
                        </dl>
                        <div class="modal-footer"
                            style="background:#0c344c; padding:3%; margin-bottom:-15px; margin-left:-15px; margin-right:-15px;">
                            <?php if($nivelLogado < 8){
                            //nível consulta não edita nem cancela evento, secretaria só edita e cancela o próprio evento (lógica no events do javascript)    
                               ?>
                            <button class="btn btn-canc-vis btn-default">
                                <spam class="glyphicon glyphicon-pencil"> Editar Evento
                            </button></spam>
                            <button class="btn btn-cancel btn-default">
                                <spam class="glyphicon glyphicon-remove-circle"></spam> Cancelar evento
                            </button></spam>
                            <?php 
                            }
                        ?>
                        </div>
                    </div>

                </div>

                <div class="delete">
                    <!-- Modal para excluir evento -->

                    <form class="form-horizontal" method="POST" action="proc_atualiza_status.php">

                        <div class="form-group">

                            <label for="responsavel" class="col-sm-3 control-label">Solicitante:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="solicitante" id="solicitante"
                                    placeholder="Responsável pelo cancelamento" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="obs" class="col-sm-3 control-label">Observação:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="obs" id="obs" placeholder="Observação"
                                    required>
                            </div>

                        </div>
                        <div class="modal-footer" style="background:#0c344c">
                            <input type="hidden" class="form-control" name="id" id="id">
                            <button type="button" class="btn btn-visao btn-default glyphicon glyphicon-arrow-left">
                                Voltar</button>
                            <button type="submit" class="btn btn-default glyphicon glyphicon-ok"> Confirmar</button>
                    </form>

                </div>

            </div>
            <!-- clock widget start -->

            <!-- EDITAR   EDITAR ---//////////////////////////////////////////////////   -->
            <div class="form">

                <form class="form-horizontal" method="POST" action="proc_edit_evento.php">

                    <input type="hidden" id="nivel_cadastro" name="nivel_cadastro">
                    <!-- PEGAR O NÍVEL DE QUEM ESTÁ EDITANDO O EVENTO -->



                    <div class="form-group">
                        <label for="Titulo" class="col-sm-3 control-label">Título:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="evento" id="evento"
                                placeholder="Titulo do evento" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="responsavel" class="col-sm-3 control-label">Responsável:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="responsavel" id="responsavel"
                                placeholder="Responsável pelo agendamento">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="setor" class="col-sm-3 control-label">Setor:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="setor" id="setor"
                                placeholder="Setor do responsável ">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Início:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="start" id="start"
                                onKeyPress="DataHora(event, this)" require>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Fim:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="end" id="end"
                                onKeyPress="DataHora(event, this)" require>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Status:</label>
                        <div class="col-sm-6">
                            <select name="status" class="form-control" id="status">

                                <?php 
                            if ($nivelLogado == 1){ ?>

                                <option style="color:#FFBF00;" value='0.#FFBF00'>Previsto</option>

                                <option style="color:#31B404;" value='1.#31B404'>Confirmado</option>
                                <!--   <option style="color:#66CDAA;" value='3.#66CDAA'>Transmissão de Sinal</option>   -->
                                <option id="feriado" style="color:#FF0000;" value='5.#FF0000'>Feriado</option>


                                <?php } else { 

                                    ?>
                                <option style="color:#FFBF00;" value='0.#FFBF00'>Previsto</option>
                                <option style="color:#31B404;" value='1.#31B404'>Confirmado</option>
                                <!--  <option style="color:#66CDAA;" value='3.#66CDAA'>Transmissão de Sinal</option> -->
                                <?php }
                                 ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Licença Zoom:</label>
                        <div class="col-sm-6">
                            <select name="aud" class="form-control" id="aud">
                                <?php 

                                  if( $userDepartamento == "DETEC") { ?>

                                <option value="FERIADO.Feriado">Feriado</option>
                                <option value="PRO100_.pro100_01">Pro 100_01</option>
                                <option value="PRO100_02.pro100_02">Pro 100_02 </option>
                                <option value="PRO100_03.pro100_03">Pro 100_03</option>
                                <option value="PRO100_04.pro100_04">Pro 100_04</option>
                                <option value="PRO100_05.pro100_05">Pro 100_05</option>
                                <option value="PRO100_06.pro100_06">Pro 100_06</option>
                                <option value="PRO500_07.pro500_07">Pro 500_07</option>
                                <option value="ZOOM_1000.Zoom_1000">ZOOM_1000</option>
                                <option value="ZOOM_500.Zoom_500">ZOOM_500</option>
                                <option value="PRO300_10.pro300_10">Pro 300_10</option>
                                <option value="PRO300_11.pro300_11">Pro 300_11</option>
                                <option value="PRO300_12.pro300_12">Pro 300_12</option>
                                <option value="PRO300_13.pro300_13">Pro 300_13</option>
                                <option value="PRO300_14.pro300_14">Pro 300_14</option>
                                <option value="PRO300_15.pro300_15">Pro 300_15</option>
                                <option value="PRO300_17.pro300_17">Pro 300_17</option>
                                <option value="PRO300_18.pro300_18">Pro 300_18</option>
                                <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>

                                <?php } elseif ($userDepartamento == "DEAMA" ) { ?>
                                <option value="PRO100_01.pro100_01">Pro 100_01</option>
                                <option value="PRO300_17.pro300_17">Pro 300_17</option>
                                <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>



                                <?php } elseif ($userDepartamento == "GABINETE" ) { ?>
                                <option value="PRO100_06.pro100_06">Pro 100_06</option>
                                <option value="PRO300_18.pro300_18">Pro 300_18</option>
                                <option value="ZOOM_1000.Zoom_1000">ZOOM_1000</option>
                                <option value="ZOOM_500.Zoom_500">ZOOM_500</option>
                                <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>


                                <?php } elseif ($userDepartamento == "DEDES" ) { ?>

                                <option value="PRO100_03.pro100_03">Pro 100_03</option>
                                <option value="PRO300_10.pro300_10">Pro 300_10</option>
                                <option value="PRO300_11.pro300_11">Pro 300_11</option>
                                <option value="PRO300_12.pro300_12">Pro 300_12</option>
                               <!-- <option value="PRO500_07.pro500_07">Pro 500_07</option> -->
                                <option value="ZOOM_1000.Zoom_1000">ZOOM_1000</option>
                                <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>


                                <?php } elseif ($userDepartamento == "DENSE" ) { ?>
                                <option value="PRO100_04.pro100_04">Pro 100_04</option>
                                <option value="PRO100_05.pro100_05">Pro 100_05</option>
                                <option value="PRO300_14.pro300_14">Pro 300_14</option>
                                <option value="PRO300_15.pro300_15">Pro 300_15</option>
                                <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>

                                <?php } elseif ($userDepartamento == "DINSE" ) { ?>
                                <option value="PRO100_02.pro100_02">Pro 100_02</option>
                                <option value="PRO300_13.pro300_13">Pro 300_13</option>


                                <?php } elseif ($nivelLogado == 7 ) { ?>
                                <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>

                                <?php } ?>


                            </select>
                        </div>
                    </div>



                    <div class="form-group"> <label for="inputEmail3"
                            class="col-sm-3 control-label">Transmissão:</label>
                        <div class="col-sm-6">
                            <select name="tsinal" class="form-control" id="tsinal">
                                <?php 
                                                        if($nivelLogado < 4){ ?>
                                <option value="S/ trasmissão. s/ trasmissão">Sem transmissão de sinal</option>
                                <option value="YOUTUBE.Youtube">Youtube</option>
                                <option value="FACEBOOK.Facebook">Facebook</option>
                                <option value="INSTAGRAM.Instagram">Instagram </option>
                                <?php } else {
                                                        ?>
                                <option value="S/ trasmissão. s/ trasmissão">Sem transmissão de sinal</option>


                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group"> <label for="inputEmail3" class="col-sm-3 control-label">Formato </label>
                        <div class="col-sm-6">
                            <select name="formato" class="form-control" id="formato">
                                <?php 
                                                    if($nivelLogado < 7){ ?>
                                <option value="Hibrido ">Hibrido </option>
                                <option value="On line">On line</option>
                                <option value="Presencial">Presencial</option>

                                <?php } else {
                                                        ?>

                                <option value="Presencial">Presencial</option>

                                <?php } ?>
                            </select>
                        </div>
                    </div>




                    <div class="form-group">
                        <i class="material-icons"></i>
                        <label for="inputEmail3" class="col-sm-3 control-label">Local:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="local" id="local" placeholder="Local">
                        </div>
                    </div>





                    <div class="modal-footer" style="background:#0c344c">
                        <input type="hidden" class="form-control" name="id" id="id">
                        <button type="button" class="btn btn-canc-edit btn-default glyphicon glyphicon-arrow-left">
                            Voltar</button>
                        <button type="submit" class="btn btn-default glyphicon glyphicon-floppy-disk"> Salvar
                            Alterações</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    </div>
    </div>


    <!-- AQUI INICIA O CADASTRO DE EVENTO ------------------------------------------------------------------>
    <div class="modal fade" id="cadastrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#0c344c;">
                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>
                    <h4 style="color:white" class="modal-title text-center">Cadastrar Evento</h4>
                    <input type="hidden" id="nivel_cadastro" name="nivel_cadastro">
                    <!-- PEGAR O NÍVEL DE QUEM ESTÁ CADASTRANDO O EVENTO -->
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" action="proc_cad_evento.php">



                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Título:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="evento" id="evento"
                                    placeholder="Titulo do evento" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <i class="material-icons"></i>
                            <label for="inputEmail3" class="col-sm-3 control-label">Responsável:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="responsavel" id="responsavel"
                                    placeholder="Responsável pelo agendamento">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="setor" class="col-sm-3 control-label">Setor:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="setor" id="setor"
                                    placeholder="Digite o setor">
                            </div>
                        </div>
                        <!--     <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Telefone:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Ramal ou celular do responsável">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Email:</label>
                            <div class="col-sm-6">
                               <input type="email" class="form-control" name="email" id="email" placeholder="Email do responsável">0
                               
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Início:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="start" id="start"
                                    onKeyPress="DataHora(event, this)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Fim:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="end" id="end"
                                    onKeyPress="DataHora(event, this)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Status:</label>
                            <div class="col-sm-6">
                                <select name="status" class="form-control" id="status">
                                    <?php 
                                    if ($nivelLogado == 1){ ?>
                                    <option style="color:#FFBF00;" value='0.#FFBF00'>Previsto</option>
                                    <option style="color:#31B404;" value='1.#31B404'>Confirmado</option>
                                    <!--  <option style="color:#66CDAA;" value='3.#66CDAA'>Transmissão de Sinal</option> -->
                                    <option id="feriado" style="color:#FF0000;" value='5.#FF0000'>Feriado</option>
                                    <?php } else { 
                            ?>
                                    <option style="color:#FFBF00;" value='0.#FFBF00'>Previsto</option>
                                    <option style="color:#31B404;" value='1.#31B404'>Confirmado</option>
                                    <!-- <option style="color:#66CDAA;" value='3.#66CDAA'>Transmissão de Sinal</option> -->
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Licença Zoom</label>
                            <div class="col-sm-6">
                                <select name="aud" class="form-control" id="aud">
                                    <?php 

                                  if( $userDepartamento == "DETEC") { ?>
                                    <option value="FERIADO.Feriado">Feriado</option>
                                    <option value="PRO100_01.pro100_01">Pro 100_01</option>
                                    <option value="PRO100_02.pro100_02">Pro 100_02 </option>
                                    <option value="PRO100_03.pro100_03">Pro 100_03</option>
                                    <option value="PRO100_04.pro100_04">Pro 100_04</option>
                                    <option value="PRO100_05.pro100_05">Pro 100_05</option>
                                    <option value="PRO100_06.pro100_06">Pro 100_06</option>
                                    <option value="PRO500_07.pro500_07">Pro 500_07</option>
                                    <option value="ZOOM_1000.Zoom_1000">ZOOM_1000</option>
                                    <option value="ZOOM_500.Zoom_500">ZOOM_500</option>
                                    <option value="PRO300_10.pro300_10">Pro 300_10</option>
                                    <option value="PRO300_11.pro300_11">Pro 300_11</option>
                                    <option value="PRO300_12.pro300_12">Pro 300_12</option>
                                    <option value="PRO300_13.pro300_13">Pro 300_13</option>
                                    <option value="PRO300_14.pro300_14">Pro 300_14</option>
                                    <option value="PRO300_15.pro300_15">Pro 300_15</option>
                                    <option value="PRO300_17.pro300_17">Pro 300_17</option>
                                    <option value="PRO300_18.pro300_18">Pro 300_18</option>
                                    <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>





                                    <?php } elseif ($userDepartamento == "DEAMA" ) { ?>
                                    <option value="PRO100_01.pro100_01">Pro 100_01</option>
                                    <option value="PRO300_17.pro300_17">Pro 300_17</option>
                                    <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>



                                    <?php } elseif ($userDepartamento == "GABINETE" ) { ?>
                                    <option value="PRO100_06.pro100_06">Pro 100_06</option>
                                    <option value="PRO300_18.pro300_18">Pro 300_18</option>
                                    <option value="ZOOM_1000.Zoom_1000">ZOOM_1000</option>
                                    <option value="ZOOM_500.Zoom_500">ZOOM_500</option>
                                    <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>



                                    <?php } elseif ($userDepartamento == "DEDES" ) { ?>

                                    <option value="PRO100_03.pro100_03">Pro 100_03</option>
                                    <option value="PRO300_10.pro300_10">Pro 300_10</option>
                                    <option value="PRO300_11.pro300_11">Pro 300_11</option>
                                    <option value="PRO300_12.pro300_12">Pro 300_12</option>
                                    <!--<option value="PRO500_07.pro500_07">Pro 500_07</option>-->
                                    <option value="ZOOM_1000.Zoom_1000">ZOOM_1000</option>
                                    <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>



                                    <?php } elseif ($userDepartamento == "DENSE" ) { ?>
                                    <option value="PRO100_04.pro100_04">Pro 100_04</option>
                                    <option value="PRO100_05.pro100_05">Pro 100_05</option>
                                    <option value="PRO300_14.pro300_14">Pro 300_14</option>
                                    <option value="PRO300_15.pro300_15">Pro 300_15</option>
                                    <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>

                                    <?php } elseif ($userDepartamento == "DINSE" ) { ?>
                                <option value="PRO100_02.pro100_02">Pro 100_02</option>
                                <option value="PRO300_13.pro300_13">Pro 300_13</option>


                                    <?php } elseif ($nivelLogado == 7 ) { ?>
                                    <option value="NÃO_SE_APLICA.não_se_aplica">Não se aplica</option>

                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                        <div class="form-group"> <label for="inputEmail3" class="col-sm-3 control-label">Transmissão
                            </label>
                            <div class="col-sm-6">
                                <select name="tsinal" class="form-control" id="tsinal">
                                    <?php 
                                        if($nivelLogado < 4){ ?>
                                    <option value="S/ trasmissão. s/ trasmissão">Sem transmissão de sinal</option>
                                    <option value="YOUTUBE.Youtube">Youtube</option>
                                    <option value="FACEBOOK.Facebook">Facebook</option>
                                    <option value="INSTAGRAM.Instagram">Instagram </option>
                                    <?php } else {
                                            ?>
                                    <option value="S/ trasmissão. s/ trasmissão">Sem transmissão de sinal</option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group"> <label for="inputEmail3" class="col-sm-3 control-label">Formato
                            </label>
                            <div class="col-sm-6">
                                <select name="formato" class="form-control" id="formato">
                                    <?php 
                                        if($nivelLogado < 7){ ?>
                                    <option value="Hibrido ">Hibrido </option>
                                    <option value="On line">On line</option>
                                    <option value="Presencial">Presencial</option>

                                    <?php } else {
                                            ?>

                                    <option value="Presencial">Presencial</option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>



                        <div class="form-group">
                            <i class="material-icons"></i>
                            <label for="inputEmail3" class="col-sm-3 control-label">Local</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="local" id="local"
                                    placeholder="Informe o local do seu evento ">
                            </div>
                        </div>

                </div>





                <div class="modal-footer" style="background:#0c344c">
                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-floppy-save"> Cadastrar</spam>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>



    <!-- MODAL CADASTRO  DE USUARIO -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#0c344c;">
                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>
                    <h4 style="color:white" class="modal-title text-center">Cadastrar Usuário</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" action="proc_cad_usuario.php">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label ">Nome:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="nome" id="nome"
                                    placeholder="Nome do usuário">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Senha:</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Email:</label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Licença</label>
                            <div class="col-sm-6">
                                <select name="departamento" class="form-control" id="departamento">
                                    <option value=''>Selecione um Departamento</option>
                                    <option value='DETEC'> Licença DETEC</option>
                                    <option value='DEAMA'>Licença DEAMA</option>
                                    <option value='DEDES'> Licença DEDES</option>
                                    <option value='DENSE'>Licença DENSE</option>
                                    <option value='DINSE'>Licença DINSE</option>
                                    <option value='GABINETE'>Licença GABINETE</option>
                                    <option value='SEM LICENCA'>SEM Licença</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Perfil</label>
                            <div class="col-sm-6">
                                <select name="niveis_acesso_id" class="form-control" id="niveis_acesso_id">
                                    <option value=''>Selecione um perfil</option>
                                    <option value='1'>ADMINISTRADOR</option>
                                    <option value='2'>DEDES</option>
                                    <option value='3'>GABINETE</option>
                                    <option value='4'>DEAMA</option>
                                    <option value='5'>DENSE</option>
                                    <option value='6'>DINSE</option>
                                    <option value='7'>CADASTRO GERAL</option>

                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer" style="background:#0c344c">
                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-floppy-save"> Cadastrar</spam>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL REDEFINIR USUARIO E ALTERAR SENHA -->

    <!-- REDEFINIR USUÁRIO -->

    <div class="modal fade" id="excluir" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle2" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#0c344c;">
                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>
                    <h4 style="color:white" class="modal-title text-center">Excluir Evento</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" action="proc_edit_usuario.php">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Email:</label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Senha:</label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha"
                                    required>
                            </div>
                        </div>
                </div>

                <div class="modal-footer" style="background:#0c344c">
                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-floppy-save"> Salvar</spam>
                    </button>

                </div>
                </form>
            </div>
        </div>

    </div>

    <!-- ALTERAR SENHA -->


    <div class="modal fade" id="exampleModalCenter5" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header" style="background:#0c344c;">

                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>

                    <h4 style="color:white" class="modal-title text-center">Alterar Senha</h4>
                </div>


                <div class="modal-body">



                    <form class="form-horizontal" method="POST" action="proc_edit_senha.php">

                        <div class="form-group">

                            <label for="inputEmail3" class="col-sm-3 control-label">Senha:</label>

                            <div class="col-sm-6">

                                <input type="password" class="form-control" name="senha1" id="senha1"
                                    placeholder="Digite a nova senha" required>

                            </div>

                        </div>


                        <div class="form-group">


                            <label for="inputEmail3" class="col-sm-3 control-label">Confirme:</label>

                            <div class="col-sm-6">

                                <input type="password" class="form-control" name="senha2" id="senha2"
                                    placeholder="Confirme a nova senha" required>

                            </div>

                        </div>
                </div>

                <div class="modal-footer" style="background:#0c344c">

                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-floppy-save"> Salvar</spam>
                    </button>

                </div>
                </form>

            </div>

        </div>

    </div>
    <!-- MODAL EXCLUIR EVENTO -->

    <div class="modal fade" id="exampleModalCenter3" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#0c344c;">
                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>
                    <h4 style="color:white" class="modal-title text-center">Excluir Evento</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="DELETE" action="">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Nome do Evento:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="events" id="events" placeholder="Evento"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Data do Evento</label>
                            <div class="col-sm-7">
                                <input type="date" class="form-control" name="data" id="date" placeholder="Data"
                                    required>
                            </div>
                        </div>
                </div>

                <div class="modal-footer" style="background:#0c344c">
                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-trash"> Excluir</spam>
                    </button>

                </div>
                </form>
            </div>
        </div>

    </div>

    <!----------------------- Gerar Relatório ------------------->


    <!-- Modal Auditórios -->


    <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">


        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header" style="background:#0c344c;">


                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>

                    <h4 style="color:white" class="modal-title text-center">Relatório de Eventos</h4>

                </div>


                <div class="modal-body">

                    <form name="form1" class="form-horizontal" method="GET" action="" Onsubmit="TipoRelatorio();">


                        <div class="form-group">

                            <label for="inputEmail3" class="col-sm-3 control-label ">De:</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" name="data_inicio" id="data_inicio">
                            </div>

                        </div>



                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Até:</label>

                            <div class="col-sm-6">

                                <input type="date" class="form-control" name="data_fim" id="data_fim">

                            </div>

                        </div>



                        <div class="form-group">


                            <label for="inputEmail3" class="col-sm-3 control-label">Plataforma:</label>
                            <div class="col-sm-6">

                                <select name="tipo" class="form-control" id="tipo" required>

                                    <!-- <option value="TODOS">Todos </option>
                                    <option value="aca">Antonio Carlos Amorim</option>
                                    <option value="jnc">José Navega Cretton</option>
                                    <option value="nra">Nelson Ribeiro Alves </option>
                                    <option value="apv">Paulo Ventura</option>
                                    <option value="aps">Penalva Santos</option>
                                    <option value="trp">Tribunal Pleno</option>
                                    <option value="tran">Transmissão de sinal</option>
                                    <option value="canc">Reservas canceladas</option> -->

                                    <option value="TODOS">Todos </option>
                                    <!--<option value="instagram">Instagram</option>-->
                                    <!--<option value="moodle">Moodle</option>-->
                                    <!--<option value="teams">Teams</option>-->
                                    <!--<option value="pro100_01">Pro 100_01</option>-->
                                    <!--<option value="pro100_02">Pro 100_02</option>-->
                                    <!--<option value="pro100_03">Pro 100_03</option>-->
                                    <!--<option value="pro100_04">Pro 100_04</option>-->
                                    <!--<option value="pro100_05">Pro 100_05</option>-->
                                    <!--<option value="pro100_06">Pro 100_06</option>-->
                                    <!--<option value="pro500_07">Pro 500_07</option>-->
                                    <!--<option value="zoom_1000">ZOOM_1000</option>-->
                                </select>

                            </div>

                        </div>



                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Relatório:</label>
                            <div class="col-sm-6">
                                <select name="tipo_Relatorio" class="form-control" id="tipo_Relatorio" required>
                                    <option value="frm" selected>FRM de eventos confirmados - pdf </option>
                                    <option value="pdf">FRM de eventos - com pré-reserva - pdf </option>
                                    <!-- <option value="pdf">Relatório Completo</option> -->
                                    <option value="excel">Relatório Excel</option>
                                </select>

                            </div>

                        </div>


                </div>


                <div class="modal-footer" style="background:#0c344c">
                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-floppy-save"> Gerar</spam>
                    </button>


                </div>

                </form>

            </div>


        </div>


    </div><br>



    <!-- Modal Usuários -->

    <div class="modal fade" id="exampleModalCenter4" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header" style="background:#0c344c;">

                    <button style="float:right" type="button" class="btn btn-default" data-dismiss="modal"
                        aria-label="Close"><span aria-hidden="true">X</span></button>

                    <h4 style="color:white" class="modal-title text-center">Relatório de Usuários</h4>

                </div>



                <div class="modal-body">

                    <form name="form2" class="form-horizontal" method="GET" action="" Onsubmit="TipoRelatorioUser();">
                        <div class="form-group">

                            <label for="inputEmail3" class="col-sm-3 control-label">Relatório:</label>

                            <div class="col-sm-6">


                                <select name="tipo_RelatorioUser" class="form-control" id="tipo_RelatorioUser" required>

                                    <option value="pdfUser" selected>Relatório PDF </option>
                                    <option value="excelUser">Relatório Excel</option>

                                </select>
                            </div>

                        </div>

                </div>

                <div class="modal-footer" style="background:#0c344c">
                    <button align='right' type="submit" class="btn btn-default">
                        <spam class="glyphicon glyphicon-floppy-save"> Gerar</spam>
                    </button>
                </div>

                </form>
            </div>
        </div>
    </div><br>
    <script>
    //FUNÇÃO SESSÃO OCIOSA

    var inactivityTime = function() {
        var time;
        window.onload = resetTimer;
        // DOM Events
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;

        function logout() {

            alert("Sessão expirada! Favor efetuar o login novamente")

            location.href = '../sair.php'

        }

        //--------------------------------------------------sessão ociosa


        function resetTimer() {

            clearTimeout(time);

            time = setTimeout(logout, 900000) //onde é definido o tempo da sessão ociosa

            // 1000 milliseconds = 1 second

            // sessão de 15 minutos = 900000

        }






    };








    $('.btn-canc-vis').on("click", function() {


        $('.form').slideToggle();
        $('.visualizar').slideToggle();

    });



    $('.btn-canc-edit').on("click", function() {

        $('.visualizar').slideToggle();
        $('.form').slideToggle();
    });






    $('.btn-cancel').on("click", function() {


        $('.delete').slideToggle();

        $('.visualizar').slideToggle();
    });


    $('.btn-visao').on("click", function() {


        $('.visualizar').slideToggle();

        $('.delete').slideToggle();

    });

    // auto close alerts
    window.setTimeout(function() {


        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();


        });

    }, 4000);



    // máscaras

    $("input[id*='telefone']").inputmask({
        mask: ['(99) 9999-9999', '(99) 99999-9999'],
        keepStatic: true

    });



    $("input[id*='start']").inputmask({


        mask: ['99/99/9999 99:99'],
        keepStatic: true

    });

    $("input[id*='end']").inputmask({

        mask: ['99/99/9999 99:99'],


        keepStatic: true



    });
    </script>



    <!--  ---------------------------------------LEGENDA  DAS LICENÇAS ------------------------------------------->



    <center><img id="imagens_legenda" src="../imagens/legenda.png" alt="Agenda" title="EMERJ"></center>




    <!-- Rodapé da Página -->





    <footer class="section footer-classic context-dark bg-image" style="background: #2d3246;">

        <div class="container">
            <div class="row row-30">

                <div class="col-md-4 col-xl-5">

                    <div class="pr-xl-2">


                        <!-- Rights-->


                    </div>


                </div>


                <div class="col-md-4">
                    <h5 style="line-height: 15px;">ESCOLA DA MAGISTRATURA DO ESTADO DO RIO DE JANEIRO - EMERJ</h5>


                    <dl class="contact-list">

                        <dd style="line-height: 15px;"> Endereço: Rua Dom Manuel, nº 25 - Centro </dd>

                    </dl>
                    <dl class="contact-list">
                        <dd style="line-height: 5px;">Email: <a
                                href="mailto:emerj.detec@tjrj.jus.br">emerj.detec@tjrj.jus.br</a></dd>

                    </dl>

                    <dl class="contact-list">

                        <dd style="padding-bottom: -150px;">Telefones: (21) 3133-1880 <span>ou</span> (21) 3133-3367

                        </dd>
                    </dl>

                </div>




                <div class="col-md-4 col-xl-3">

                    <h5>Links</h5>


                    <ul class="nav-list">
                        <li><a style="text-decoration: none;" href="http://www.emerj.tjrj.jus.br" target="_blank">Site
                                da EMERJ</a></li>

                        <li><a style="text-decoration: none;"
                                href="http://www.emerj.tjrj.jus.br/paginas/eventos/eventos_emerj_gratuitos.html"
                                target="_blank">Eventos EMERJ</a></li>
                        <li><a style="text-decoration: none;" href="mailto:emerj.detec@tjrj.jus.br">Dúvidas sobre
                                agenda?</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>