<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

if(isset($HTTP_POST_VARS["enviar"])) {
  db_postmemory($HTTP_POST_VARS);
  $k13_saldo = $k13_saldo==""?"null":$k13_saldo;
  $k13_vlratu = $k13_vlratu==""?"null":$k13_vlratu;
  $k13_datvlr = $k13_datvlr_ano."-".$k13_datvlr_mes."-".$k13_datvlr_dia;
  $k13_datvlr = trim($k13_datvlr)=="--"?"null":"'$k13_datvlr'";

  pg_exec("BEGIN");

  $result = pg_exec("select c01_reduz,c01_estrut,c01_descr from plano where c01_anousu = ".db_getsession("DB_anousu")." and c01_reduz = $k13_conta");
  db_fieldsmemory($result,0);

  pg_exec("update saltes set 
                         k13_saldo  = $k13_saldo,
                         k13_ident  = '$k13_ident',
                         k13_vlratu = $k13_vlratu,
                         k13_datvlr = $k13_datvlr,
                         k13_descr  = '$c01_descr'
		   where k13_conta = $val_ant") or die("Erro(19) alterando saltes:");		  
  pg_exec("update saltesplan set 
                         c01_reduz  = $c01_reduz,
                         c01_estrut  = '$c01_estrut',
                         c01_anousu = ".db_getsession("DB_anousu")."
		   where k13_conta = $val_ant") or die("Erro(19) alterando saltesplan:");		  

  pg_exec("END");

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
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
<?
if(!isset($HTTP_POST_VARS["procurar"])) {
  echo "<center>\n<form name=\"form1\" method=\"post\"><br>\n";
  db_contas("k13_conta","",2);
  echo "\n<input type=\"submit\" name=\"procurar\" value=\"Procurar\">
  </form>
  </center>\n";
} else {
  $result = pg_exec("select saltes.*,k13_datvlr 
                     from saltes where k13_conta = ".$HTTP_POST_VARS["k13_conta"]);
  db_fieldsmemory($result,0);
  ?>
  <br>
  <?
  $alterar = 1;
  include("forms/db_frmsaltes.php");
  echo "<script>
        document.form1.val_ant.value = '$k13_conta';
	    </script>\n";
}
?>		
	</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
