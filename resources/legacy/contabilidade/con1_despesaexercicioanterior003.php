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
include("classes/db_despesaexercicioanterior.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldespesaexercicioanterior = new cl_despesaexercicioanterior;
$cldb_config = new cl_db_config;
$db_botao = false;
$db_opcao = 33;
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir") {
    db_inicio_transacao();
    $db_opcao = 3;
    $cldespesaexercicioanterior->excluir($c233_sequencial);
    db_fim_transacao();
} else if (isset($chavepesquisa)) {
    $db_opcao = 3;
    $result = $cldespesaexercicioanterior->sql_record($cldespesaexercicioanterior->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
    $db_botao = true;
}
?>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("strings.js, grid.style.css, datagrid.widget.js");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC>
    <table border="0" align="center" cellspacing="0" cellpadding="0">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td valign="top" bgcolor="#CCCCCC">
                <center>
                    <br />
                    <? include("forms/db_frmdespesaexercicioanterior.php"); ?>
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
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir") {
    if ($cldespesaexercicioanterior->erro_status == "0") {
        $cldespesaexercicioanterior->erro(true, false);
    } else {
        $cldespesaexercicioanterior->erro(true, true);
    };
};
if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
