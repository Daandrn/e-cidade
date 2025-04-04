<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_saltes_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_saltescontrapartida_classe.php");
require_once("classes/db_saltesextra_classe.php");

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);

$clsaltes              = new cl_saltes;
$clsaltescontrapartida = new cl_saltescontrapartida;
$clsaltesextra         = new cl_saltesextra;
$db_opcao = 22;
$db_botao = false;
$sqlerro = false;
$mostrarCampo2 = 1;
$db_opcaonovo = 1;
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  db_inicio_transacao();
     /**
      * Verificamos se a data de cria�ao eh menor ou igual a data do saldo;
      */
     if ($k13_datvlr != "") {

       if (db_strtotime(implode("-", array_reverse(explode("/", $k13_datvlr)))) <
           db_strtotime(implode("-", array_reverse(explode("/", $k13_dtimplantacao))))) {

          $sqlerro               = true;
          $clsaltes->erro_status = "0";
          $clsaltes->erro_msg    = "Data de Cria��o da conta deve ser menor ou igual a data de atualiza��o do saldo";

        }
     }
     $oDaoConDataConf  = new cl_condataconf();
     $oDataImplantacao = date('Y-m-d', strtotime(str_replace('/', '-', $k13_dtimplantacao)));
     $sWhere           = "    c99_data   >= '{$oDataImplantacao}'::date
                              and c99_instit  = " . db_getsession('DB_instit')." and c99_anousu = " .db_getsession('DB_anousu');
     $sSqlValidaFechamentoContabilidade = $oDaoConDataConf->sql_query(null, null, '*', null, $sWhere);
     $rsValidaFechamentoContabilidade   = $oDaoConDataConf->sql_record($sSqlValidaFechamentoContabilidade);
     db_fieldsmemory($rsValidaFechamentoContabilidade, 0);
     $data_convertida                   = DateTime::createFromFormat('Y-m-d', $c99_data);
     if ($c99_data) {
      $dataEncerramento = $data_convertida->format('d/m/Y');
     }
     
     if ($oDaoConDataConf->numrows > 0) {
       $periodoEncerrado = 1;
     }

     if ($periodoEncerrado == 1 && !empty($k13_limite)) {
      $dateLimite = parseDate($k13_limite);
      $dateEncerramento = parseDate($dataEncerramento);
  
        if ($dateLimite < $dateEncerramento) {
            $sqlerro                  = true;
            echo "<script>
            alert('A data limite informada dever� ser maior que a data de encerramento do per�odo cont�bil(" . $dataEncerramento . ").');
            window.history.back(); 
            </script>";
        }
     }
  
     if (!empty($k13_limite) && !empty($k13_dtreativacaoconta)) {
        $sqlerro                  = true;
        echo "<script>
        alert('A data da reativa��o de conta somente poder� ser preenchida se o campo data limite estiver vazio');
        window.history.back(); 
        </script>";
     }

     $clcondataconf     = new cl_condataconf;
     $resultControle    = $clcondataconf->sql_record($clcondataconf->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit'),'c99_data as dataencerramento'));
     db_fieldsmemory($resultControle,0);
     $data_encerramento = DateTime::createFromFormat('Y-m-d', $dataencerramento);  
     if ($dataencerramento) {
       $dataencerramentoco = $data_encerramento->format('d/m/Y');
     } 
     
     if (!empty($k13_dtreativacaoconta)) {
        $dateEncerramento = parseDate($dataencerramentoco);
        $datereativacaoconta = parseDate($k13_dtreativacaoconta);
        $dataimplantaoconta = parseDate($db83_dataimplantaoconta);
      
        if ($datereativacaoconta < $dateEncerramento) {
          $sqlerro                  = true;
          echo "<script>
          alert('A data da reativa��o de conta informada dever� ser maior que a data de encerramento do per�odo cont�bil(" . $dataencerramentoco . ").');
          window.history.back(); 
          </script>";           
        }


        if ($datereativacaoconta < $dataimplantaoconta) {
          $sqlerro                  = true;
          echo "<script>
          alert('A data da reativa��o de conta informada tem que ser maior ou igual a data de implanta��o.');
          window.history.back(); 
          </script>";           
        }
      }

      if (!empty($k13_limite)) {
        $datalimite = parseDate($k13_limite);
        $dataimplantaoconta = parseDate($db83_dataimplantaoconta);
      
        if ($datalimite < $dataimplantaoconta) {
          $sqlerro                  = true;
          echo "<script>
          alert('A data limite informada tem que ser maior ou igual a data de implanta��o.');
          window.history.back(); 
          </script>";           
        }
      }
  
     if (!$sqlerro) {

       $db_opcao            = 2;
       $k13_conta           = $k13_reduz;
       $clsaltes->k13_conta = $k13_reduz;
       $clsaltes->alterar($k13_reduz);
       if ($k103_contrapartida != '') {

         $clsaltescontrapartida->excluir(null,"k103_saltes = {$k13_reduz}");
         $clsaltescontrapartida->k103_contrapartida = $k103_contrapartida;
         $clsaltescontrapartida->k103_saltes        = $k13_reduz;
         $clsaltescontrapartida->incluir(null);
       } else {
         $clsaltescontrapartida->excluir(null,"k103_saltes = {$k13_reduz}");
       }
       if ($k109_saltesextra != '') {

         $clsaltesextra->excluir(null,"k109_saltes = {$k13_reduz}");
         $clsaltesextra->k109_contaextra = $k109_saltesextra;
         $clsaltesextra->k109_saltes     = $k13_reduz;
         $clsaltesextra->incluir(null);
       } else {
         $clsaltesextra->excluir(null,"k109_saltes = {$k13_reduz}");
       }

       /** [ExtensaoFiltroDespesa] Modificacao 1 */

     }
     db_fim_transacao($sqlerro);

}else if(isset($chavepesquisa)){

   $db_opcao = 2;
   $result   = $clsaltes->sql_record($clsaltes->sql_query($chavepesquisa));
   db_fieldsmemory($result,0,true);
   $sSqlContrapartida = $clsaltescontrapartida->sql_query_contrapartida(null,
                                                                        "k103_contrapartida,k13_descr as k103_descr",
                                                                        null,
                                                                        "k103_saltes = {$chavepesquisa}");

   $rsContrapartida = $clsaltescontrapartida->sql_record($sSqlContrapartida);
   if ($clsaltescontrapartida->numrows > 0) {
     db_fieldsmemory($rsContrapartida, 0);
   }
   $sSqlContaextra = $clsaltesextra->sql_query_extra(null,
                                                          "k109_contaextra as k109_saltesextra,k13_descr as k103_descrextra",
                                                          null,
                                                          "k109_saltes = {$chavepesquisa}");

   $rsContaExtra = $clsaltesextra->sql_record($sSqlContaextra);
   if ($clsaltesextra->numrows > 0) {
     db_fieldsmemory($rsContaExtra, 0);
   }

   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmsaltes.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  if($clsaltes->erro_status=="0"){
    $clsaltes->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsaltes->erro_campo!=""){
      echo "<script> document.form1.".$clsaltes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsaltes->erro_campo.".focus();</script>";
    };
  }else{
    $clsaltes->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}

function parseDate($dateString) {
  $date = DateTime::createFromFormat('d/m/Y', $dateString);
  return $date;
}
?>