<?

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clcriaabas     = new cl_criaabas;
$db_opcao = 1;
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
    <table width="100%" height="18" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr>
            <td width="360">&nbsp;</td>
            <td width="263">&nbsp;</td>
            <td width="25">&nbsp;</td>
            <td width="140">&nbsp;</td>
        </tr>
    </table>
    <table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
                <?
                $clcriaabas->identifica = array(
                    "infoambiente"      => "Informações do Ambiente",
                    "atividades"        => "Atividades Desempenhadas",
                    "agentesnocivos"    => "Agentes Nocivos",
                    "inforelativasresp" => "Informações Relativas ao Responsável Pelos Registros Ambientais"
                );

                $clcriaabas->sizecampo  = array(
                    "infoambiente"      => "25",
                    "atividades"        => "25",
                    "agentesnocivos"    => "25",
                    "inforelativasresp" => "55"
                );

                $clcriaabas->src        = array(
                    "infoambiente"      => "eso1_infoambiente003.php",
                    "atividades"        => "eso1_atividades001.php",
                    "agentesnocivos"    => "eso1_agentesnocivos001.php",
                    "inforelativasresp" => "eso1_inforelativasresp001.php"
                );

                $clcriaabas->cria_abas();

                ?>
            </td>
        </tr>
    </table>
    <form name="form1">
    </form>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>