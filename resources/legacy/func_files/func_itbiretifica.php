<?php

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbi_classe.php");
include("libs/db_app.utils.php");

$situacao = "";
$tipo = "";
$sWhereLogradouro = "";

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if (!isset($setorCodigo)) {
    $setorCodigo = '';
}

if (!isset($quadra)) {
    $quadra = '';
}
if (!isset($lote)) {
    $lote = '';
}

$clitbi = new cl_itbi;
$clitbi->rotulo->label("it01_guia");

$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("it18_nomelograd");

?>
<html lang="">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title></title>
    <?php
    db_app::load('estilos.css');
    db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
    ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
    <table align="center">
        <tr>
            <td title="<?= $Tit01_guia ?>">
                <?= $Lit01_guia ?>
            </td>
            <td>
                <?php
                db_input("it01_guia", 10, $Iit01_guia, true, "text", 4, "", "chave_it01_guia");
                ?>
            </td>
        </tr>

        <tr>
            <td>
                <?php
                db_ancora("<b>Matr�cula :</b>", ' js_matri(true); ', 1);
                ?>
            </td>
            <td>
                <?php
                db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1, "onchange='js_matri(false)'");
                db_input('z01_nome', 28, 0, true, 'text', 3, "", "z01_nomematri");
                ?>
            </td>
        </tr>

        <tr>
            <td><b>Logradouro :</b></td>
            <td>
                <?php
                db_input('logradouroid', 40, '', true, 'hidden', 3);
                db_input('it18_nomelograd', 40, $Iit18_nomelograd, true, 'text', 1);
                ?>
            </td>
        </tr>

        <tr>
            <td title="Setor/Quadra/Lote"><strong>Setor/Quadra/Lote:</strong></td>

            <td>
                <?php
                db_input('j34_setor', 10, $Ij34_setor, true, 'text', 1);
                db_input('j34_quadra', 10, $Ij34_quadra, true, 'text', 1);
                db_input('j34_lote', 10, $Ij34_lote, true, 'text', 1);
                ?>
            </td>
        </tr>

        <tr>
            <td><b>Tipo :</b>
            </td>
            <td>
                <?php
                $aTipo = array('t' => 'Todos',
                    'u' => 'Urbano',
                    'r' => 'Rural');

                db_select('tipo', $aTipo, true, 2, " style='width:295px;'");
                ?>
            </td>
        </tr>
        <tr>
            <td><b>Periodo de :</b>
            </td>
            <td>
                <?php
                db_inputdata('dtIni', '', '', '', true, 'text', 1, '');
                ?> &nbsp; <b> a </b> &nbsp;
                <?php
                db_inputdata('dtFim', '', '', '', true, 'text', 1, '');
                ?>
            </td>
        </tr>
        <tr>
            <td><b>Situa�ao:</b>
            </td>
            <td>
                <?php
                $aSituacao = array(
                    '2'=>'Aberto',
                    '3'=>'Pago'
                );
                db_select('situacao',$aSituacao,true,2," style='width:295px;'");
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <div id="pesquisa"></div>
            </td>
        </tr>

        <tr>
            <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframeitbi.hide();">
            </td>
        </tr>
    </table>

    <table align="center">
        <tr>
            <td>
                <?php

                if (!isset($pesquisa_chave)) {
                    if (isset($campos) == false) {
                        if (file_exists("funcoes/db_func_itbi.php") == true) {
                            include("funcoes/db_func_itbi.php");
                        } else {
                            $campos = "itbi.*";
                        }
                    }

                    $sWhere = " it14_guia is not null and ";
                    $sWhere .= " case                                                                                ";
                    $sWhere .= "     when db_usuarios.usuext = 1 then                                                ";
                    $sWhere .= "       case                                                                          ";
                    $sWhere .= "       when itbi.it01_id_usuario = " . db_getsession('DB_id_usuario') . " then true      ";
                    $sWhere .= "         else false                                                                  ";
                    $sWhere .= "       end                                                                           ";
                    $sWhere .= "     else                                                                            ";
                    $sWhere .= "       case                                                                          ";
                    $sWhere .= "       when itbi.it01_coddepto = " . db_getsession('DB_coddepto') . " then true          ";
                    $sWhere .= "       else false                                                                    ";
                    $sWhere .= "       end                                                                           ";
                    $sWhere .= "     end                                                                             ";

                    if (isset($chave_it01_guia) && (trim($chave_it01_guia) != "")) {
                        $sWhere .= " and it01_guia like '$chave_it01_guia%' ";
                    }

                    if (isset($j01_matric) && trim($j01_matric) != "") {
                        $sWhere .= " and it06_matric = $j01_matric";
                    }

                    if (isset($dtfim) && isset($dtini)) {

                        $dtIni = implode("-", array_reverse(explode("/", $dtini)));
                        $dtFim = implode("-", array_reverse(explode("/", $dtfim)));

                        if (!empty($dtIni) && !empty($dtFim)) {
                            $sWhere .= " and it01_data between '{$dtIni}' and '{$dtFim}'";
                        } else if (!empty($dtIni)) {
                            $sWhere .= " and it01_data >= '{$dtIni}' ";
                        } else if (!empty($dtFim)) {
                            $sWhere .= " and it01_data <= '{$dtFim}' ";
                        }

                    }

                    if (isset($it18_nomelograd) && $it18_nomelograd != "") {
                        $sWhereLogradouro = " where logradouro = '{$it18_nomelograd}' ";
                    }

                    if (isset($j34_setor) && $j34_setor != "") {
                        $sWhere .= " and j34_setor = '" . str_pad($j34_setor, 4, "0", STR_PAD_LEFT) . "'";
                    }

                    if (isset($j34_quadra) && $j34_quadra != "") {
                        $sWhere .= " and j34_quadra = '" . str_pad($j34_quadra, 4, "0", STR_PAD_LEFT) . "'";
                    }

                    if (isset($j34_lote) && $j34_lote != "") {
                        $sWhere .= " and j34_lote = '" . str_pad($j34_lote, 4, "0", STR_PAD_LEFT) . "'";
                    }

                    if (isset($setorCodigo) || isset($quadra) || isset($lote)) {

                        if (isset($setor) and $setor != '') {
                            $sWhere .= " and j05_codigoproprio = '{$setorCodigo}' ";
                        }
                        if (isset($quadra) and $quadra != '') {
                            $sWhere .= " and j06_quadraloc = '{$quadra}' ";
                        }
                        if (isset($lote) and $lote != '') {
                            $sWhere .= " and j06_lote = '{$lote}' ";
                        }

                    }

                    if ( $situacao == "2" ) {
                        $sWhere         .= " and arrepaga.k00_numpre is null";
                        $sWhere         .= " and it16_guia is null";
                    }

                    if ( $situacao == "3" ) {
                        $sWhere         .= " and arrepaga.k00_numpre is not null";
                    }

                    if ($tipo == "u") {
                        $sWhere .= " and it05_guia is not null ";
                    } else if ($tipo == "r") {
                        $sWhere .= " and it18_guia is not null ";
                    }

                    $sWhere .= " and it36_guia is null ";

                    if (!empty($j01_matric) ||
                        !empty($j34_setor) ||
                        !empty($tipo) ||
                        !empty($j34_quadra) ||
                        !empty($j34_lote) ||
                        !empty($it18_nomelograd)) {

                        $sql = $clitbi->sql_query_itbi("", "distinct " . $campos, "it01_guia", $sWhere, $sWhereLogradouro);
                        db_lovrot($sql, 15, "()", "", $funcao_js);
                    }

                } else {

                    if ($pesquisa_chave != null && $pesquisa_chave != "") {
                        $result = $clitbi->sql_record($clitbi->sql_query_lib($pesquisa_chave));
                        if ($clitbi->numrows != 0) {
                            db_fieldsmemory($result, 0);
                            echo "<script>" . $funcao_js . "('$it01_guia',false);</script>";
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

    <script>
        function js_matri(mostra) {
            const matri = document.form2.j01_matric.value;
            const w = document.body.clientWidth - 20;
            const h = document.body.clientHeight - 20;

            if (mostra === true) {
                js_OpenJanelaIframe('', 'db_iframe3', 'func_matricitbi.php?valida=false&funcao_js=parent.js_mostramatri|0|1', 'Pesquisa', true, null, null, w, h);
            } else {
                js_OpenJanelaIframe('', 'db_iframe3', 'func_matricitbi.php?pesquisa_chave=' + matri + '&funcao_js=parent.js_mostramatri1', 'Pesquisa', false);
            }
        }

        function js_mostramatri(chave1, chave2) {
            document.form2.j01_matric.value = chave1;
            document.form2.z01_nomematri.value = chave2;
            db_iframe3.hide();
        }

        function js_mostramatri1(chave, erro) {
            document.form2.z01_nomematri.value = chave;
            if (erro === true) {
                document.form2.j01_matric.focus();
                document.form2.j01_matric.value = '';
            }
        }
    </script>
</form>
</body>
</html>
<?php
if (!isset($pesquisa_chave)) {
    ?>
    <script>
    </script>
    <?php
}
?>

<script>
    var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
    <?php
    echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');";
    ?>
</script>
