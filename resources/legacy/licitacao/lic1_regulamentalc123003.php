<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_regulamentalc123_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clregulamentalc123 = new cl_regulamentalc123;
if (pg_num_rows(db_query($clregulamentalc123->sql_query())) > 0) {
    $result = $clregulamentalc123->sql_record($clregulamentalc123->sql_query());
    db_fieldsmemory($result, 0);
}
$db_botao = true;
$db_opcao = 3;
if (isset($excluir)) {
    db_inicio_transacao();
    $db_opcao = 3;
    $clregulamentalc123->excluir($oid);
    db_fim_transacao();
} else if (isset($chavepesquisa)) {
    $db_opcao = 3;
    $result = $clregulamentalc123->sql_record($clregulamentalc123->sql_query());
    db_fieldsmemory($result, 0);
    $db_botao = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
    </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
            <center>
                <?
                include("forms/db_frmregulamentalc123.php");
                ?>
            </center>
        </td>
    </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($excluir)) {
    if ($clregulamentalc123->erro_status == "0") {
        $clregulamentalc123->erro(true, false);
    } else {
        $clregulamentalc123->erro(true, true);
    }
}
if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
    js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>
