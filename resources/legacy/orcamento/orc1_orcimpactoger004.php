<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcimpactoger_classe.php");

$clorcimpactoger = new cl_orcimpactoger;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
    $clorcimpactoger->incluir(null);
    $erro_msg = $clorcimpactoger->erro_msg;
    if($clorcimpactoger->erro_status==0){
      $sqlerro=true;
    }
  db_fim_transacao($sqlerro);
  if($sqlerro==false){
   $db_opcao=2;
   $o62_codimpger = $clorcimpactoger->o62_codimpger;
  }
}else if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
    $clorcimpactoger->alterar($o62_codimpger);
    $erro_msg = $clorcimpactoger->erro_msg;
    if($clorcimpactoger->erro_status==0){
      $sqlerro=true;
    }
  db_fim_transacao($sqlerro);
  $db_opcao=2;
}else if(isset($chavepesquisa)){
   $result = $clorcimpactoger->sql_record($clorcimpactoger->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);

  $db_opcao=2;
}else{


$db_opcao=1;
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="300" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmorcimpactoger.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if( (isset($chavepesquisa)||isset($incluir)) && empty($alterar)){
  if($tipo == "receita"){
       echo "
	<script>\n
	       parent.document.formaba.orcimpactorecmov.disabled=false;\n
	       CurrentWindow.corpo.iframe_orcimpactorecmov.location.href='orc1_orcimpactorecmov001.php?o63_codimpger=".@$o62_codimpger."';\n
	       parent.mo_camada('orcimpactorecmov');\n
	</script>\n
       ";
  }else{
       echo "
	<script>\n
	       parent.document.formaba.orcimpactomov.disabled=false;\n
	       CurrentWindow.corpo.iframe_orcimpactomov.location.href='orc1_orcimpactomov004.php?o63_codimpger=".@$o62_codimpger."';\n
	       parent.mo_camada('orcimpactomov');\n
	</script>\n
       ";
  }
}

//if( (isset($incluir) || isset($alterar)){
//    db_msgbox($erro_msg);
//}
?>
