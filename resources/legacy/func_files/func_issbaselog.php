<?php
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
include("classes/db_issbaselog_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissbaselog = new cl_issbaselog();
$clissbaselog->rotulo->label("q102_sequencial");
$clissbaselog->rotulo->label("q102_sequencial");
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
        <td height="63" align="center" valign="top">
            <table width="35%" border="0" align="center" cellspacing="0">
                <form name="form2" method="post" action="">
                    <tr>
                        <td width="4%" align="right" nowrap title="<?= $Tq102_sequencial ?>">
                            <?= $Lq102_sequencial ?>
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?php
                            db_input("q102_sequencial", 10, $Iq102_sequencial, true, "text", 4, "", "chave_q102_sequencial");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                            <input name="limpar" type="reset" id="limpar" value="Limpar">
                            <input name="Fechar" type="button" id="fechar" value="Fechar"
                                   onClick="parent.db_iframe_issbaselog.hide();">
                        </td>
                    </tr>
                </form>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" valign="top">
            <?php
            $where = ' 1 = 1 ';

            if (!empty($q102_inscr)) {
                $where .= " and q102_inscr = {$q102_inscr} ";
            }

            if (!empty($q102_issbaselogtipo)) {
                $where .= " and q102_issbaselogtipo = {$q102_issbaselogtipo} ";
            }

            if (!isset($pesquisa_chave)) {
                if (isset($campos) == false) {
                    if (file_exists("funcoes/db_func_issbaselog.php") == true) {
                        include("funcoes/db_func_issbaselog.php");
                    } else {
                        $campos = "issbaselog.*";
                    }
                }
                if (isset($chave_q102_sequencial) && (trim($chave_q102_sequencial) != "")) {
                    $sql = $clissbaselog->sql_query($chave_q102_sequencial, $campos, "q102_sequencial", $where);
                } else if (isset($chave_q102_sequencial) && (trim($chave_q102_sequencial) != "")) {
                    $sql = $clissbaselog->sql_query("", $campos, "q102_sequencial", " q102_sequencial like '$chave_q102_sequencial%' and $where");
                } else {
                    $sql = $clissbaselog->sql_query("", $campos, "q102_sequencial", $where);
                }
                $repassa = array();
                if (isset($chave_q102_sequencial)) {
                    $repassa = array("chave_q102_sequencial" => $chave_q102_sequencial, "chave_q102_sequencial" => $chave_q102_sequencial);
                }
                db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
            } else {
                if ($pesquisa_chave != null && $pesquisa_chave != "") {
                    $result = $clissbaselog->sql_record($clissbaselog->sql_query($pesquisa_chave, "*", "q102_sequencial", $where));
                    if ($clissbaselog->numrows != 0) {
                        db_fieldsmemory($result, 0);
                        echo "<script>" . $funcao_js . "('$q102_sequencial',false);</script>";
                    } else {
                        echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") n�o Encontrado',true);</script>";
                    }
                } else {
                    echo "<script>" . $funcao_js . "('',false);</script>";
                }
            }
            ?>
        </td>
    </tr>
</table>
</body>
</html>
<script>
    js_tabulacaoforms("form2", "chave_q102_sequencial", true, 1, "chave_q102_sequencial", true);
</script>
