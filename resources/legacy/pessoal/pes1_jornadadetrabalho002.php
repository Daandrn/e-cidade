<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_jornadadetrabalho_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cljornadadetrabalho = new cl_jornadadetrabalho;
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {
    db_inicio_transacao();
    $db_opcao = 2;
    $cljornadadetrabalho->alterar($jt_sequencial);
    db_fim_transacao();
} else if (isset($chavepesquisa)) {
    $db_opcao = 2;
    $result = $cljornadadetrabalho->sql_record($cljornadadetrabalho->sql_query($chavepesquisa));
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

    <?
    include("forms/db_frmjornadadetrabalho.php");
    ?>

    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<?
if (isset($alterar)) {
    if ($cljornadadetrabalho->erro_status == "0") {
        $cljornadadetrabalho->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($cljornadadetrabalho->erro_campo != "") {
            echo "<script> document.form1." . $cljornadadetrabalho->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $cljornadadetrabalho->erro_campo . ".focus();</script>";
        }
    } else {
        $cljornadadetrabalho->erro(true, true);
    }
}
if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
    js_tabulacaoforms("form1", "jt_nome", true, 1, "jt_nome", true);
</script>
