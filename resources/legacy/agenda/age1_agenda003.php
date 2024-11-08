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

if(isset($HTTP_POST_VARS["excluir"])) {
  $id = $HTTP_POST_VARS["id"];
  $result = pg_exec("select id from db_contatos where id = $id");
  if(pg_numrows($result) > 0) {
    $result = pg_exec("DELETE FROM db_contatos WHERE id = $id") or die("Erro(10) excluindo db_contatos.");
  } else {
    $DB_ERRO = "C�digo $id n�o encontrado";
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submeter() {
  var expr = /[^0-9]+/;
  var F =  document.form1;
  if(F.id.value.match(expr) || F.id.value == "") {
    alert("Campo C�digo s� aceita numeros e n�o pode ser vazio!");
	F.id.select();
	return false;
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.id.focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
		<div id="ww"></div>
	<form name="form1" method="post" onSubmit="return js_submeter()">
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <Td><strong>C�digo:&nbsp;</strong></Td>
		<td><input type="text" name="id"></td>
	  </tr>
	  <tr>
	    <Td>&nbsp;</Td>
		<td><input type="submit" name="excluir" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')"></td>
	  </tr>
	</table>
	</form>
	</center>
	</td>
  </tr>
</table>
<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?
if(isset($DB_ERRO)) {
  db_msgbox($DB_ERRO);
}
?>
</html>
