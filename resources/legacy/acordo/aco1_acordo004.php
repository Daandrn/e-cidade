<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_acordoaux_classe.php");
require_once("classes/db_acordoacordogarantia_classe.php");
require_once("classes/db_acordoacordopenalidade_classe.php");
require_once("classes/db_acordoitem_classe.php");
require_once("classes/db_parametroscontratos_classe.php");

$oParam = new cl_parametroscontratos;
$oParam = $oParam->sql_query(null,'*');
$oParam = db_query($oParam);
$oParam = db_utils::fieldsMemory($oParam);
$oParam = $oParam->pc01_liberarcadastrosemvigencia;
if($oParam == 't'){
 $clacordo = new cl_acordoaux;
}else{
 $clacordo = new cl_acordo;
}

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clacordo->incluir($ac16_sequencial);
  if($clacordo->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $clacordo->erro_msg;
  db_fim_transacao($sqlerro);
  $ac16_sequencial= $clacordo->ac16_sequencial;
  $db_opcao = 1;
  $db_botao = true;
}
unset($_SESSION["dadosSelecaoAcordo"]);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js");
  db_app::load("dbmessageBoard.widget.js, prototype.js");
  if($oParam == 't'){
    db_app::load("contratosaux.classe.js");
  }else{
    db_app::load("contratos.classe.js");
  }
  db_app::load("estilos.css, grid.style.css");
  ?>
  <script language="JavaScript" type="text/javascript" src="../../../scripts/classes/contratos.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="../../../scripts/classes/contratosaux.classe.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="../../../estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
  if($oParam == 't'){
    include("forms/db_frmacordoaux.php");
  }else{
   include("forms/db_frmacordo.php");
 }
 ?>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clacordo->erro_campo!=""){
      echo "<script> document.form1.".$clacordo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clacordo->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("aco1_acordo005.php?liberaaba=true&chavepesquisa=$ac16_sequencial");
 }
}
?>
