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
include("classes/db_mer_alimento_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_alimento = new cl_mer_alimento;
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $clmer_alimento->alterar($me35_i_codigo);
  db_fim_transacao();

} else if (isset($chavepesquisa)) {

   $db_opcao = 2;
   $result   = $clmer_alimento->sql_record($clmer_alimento->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a3.disabled = false;
   CurrentWindow.corpo.iframe_a2.location.href   = 'mer1_mer_infnutricional001.php?me08_i_alimento=<?=$chavepesquisa?>&me35_c_nomealimento=<?=$me35_c_nomealimento?>';
   CurrentWindow.corpo.iframe_a3.location.href   = 'mer1_mer_alimentomatmater001.php?me36_i_alimento=<?=$chavepesquisa?>&me35_c_nomealimento=<?=$me35_c_nomealimento?>';
  </script>
  <?
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Altera��o de Alimento</b></legend>
	<?
	include("forms/db_frmmer_alimento.php");
	?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($alterar)) {

  if ($clmer_alimento->erro_status == "0") {

    $clmer_alimento->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clmer_alimento->erro_campo != "") {

      echo "<script> document.form1.".$clmer_alimento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_alimento->erro_campo.".focus();</script>";
    }
  } else {
    $clmer_alimento->erro(true,false);
    db_redireciona("mer1_mer_alimento002.php?chavepesquisa=$me35_i_codigo");
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "me35_c_nomealimento", true, 1, "me35_c_nomealimento", true);
</script>
