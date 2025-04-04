<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oGet       = db_utils::postMemory($_GET);

$lMenu      = true;
if (isset($oGet->lMenu) && trim($oGet->lMenu) != '' && $oGet->lMenu == 'false') {

	$lMenu = false;
}

$sQueryUrl = "";

if (isset($oGet->lFisico) && trim($oGet->lFisico) != '') {
	$sQueryUrl = "?lFisico=".$oGet->lFisico."&lJuridico=false";
}

if (isset($oGet->lJuridico) && trim($oGet->lJuridico) != '') {
  $sQueryUrl .= "?lFisico=false&lJuridico=".$oGet->lJuridico;
}

if (isset($oGet->funcaoRetorno) && trim($oGet->funcaoRetorno) != '') {

  if (trim($sQueryUrl) == "") {

  	$sQueryUrl = "?funcaoRetorno=".$oGet->funcaoRetorno;
  } else {

  	$sQueryUrl .= "&funcaoRetorno=".$oGet->funcaoRetorno;
  }
}

$clcriaabas = new cl_criaabas;
$db_opcao   = 1;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<? if ($lMenu) {?>
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<? } ?>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <?
	     $clcriaabas->identifica = array( "cgm"        => "Dados CGM",
	                                       "documentos" => "Documentos",
                                         "fotos"      => "Fotos",
                                         "contasbanco"=> "Contas Banc�rias"
	                                     );
	     $clcriaabas->src      = array( "cgm"        => "prot1_cadgeralmunic004.php".$sQueryUrl );
       $clcriaabas->disabled = array( "documentos" => "true", "fotos" => "true", "contasbanco" => true );
       $clcriaabas->sizecampo  = array("contasbanco"=>20);

	     $clcriaabas->cria_abas();
     ?>
    </td>
  </tr>
</table>
<form name="form1">
</form>
<?
  if ($lMenu) {

	 db_menu(db_getsession("DB_id_usuario"),
	         db_getsession("DB_modulo"),
	         db_getsession("DB_anousu"),
	         db_getsession("DB_instit")
	        );
  }
?>
</body>
</html>