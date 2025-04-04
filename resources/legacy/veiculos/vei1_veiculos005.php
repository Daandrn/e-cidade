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
include("classes/db_veiculos_classe.php");
include("classes/db_veicresp_classe.php");
include("classes/db_veicpatri_classe.php");
include("classes/db_veicparam_classe.php");
include("classes/db_veiculoscomb_classe.php");
include("classes/db_veictipoabast_classe.php");
include("classes/db_veiccentral_classe.php");
include("classes/db_tipoveiculos_classe.php");
include("classes/db_pcforne_classe.php");
include("classes/db_cgm_classe.php");
require_once("classes/db_condataconf_classe.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveiculos      = new cl_veiculos;
$clveicresp      = new cl_veicresp;
$clveicpatri     = new cl_veicpatri;
$clveicparam     = new cl_veicparam;
$clveictipoabast = new cl_veictipoabast;
$clveiccentral   = new cl_veiccentral;
$clveicabast     = new cl_veicabast;
$cltipoveiculos  = new cl_tipoveiculos;
$clpcforne = new cl_pcforne;
$clcgm = new cl_cgm;

$db_opcao = 22;
$db_botao = false;

if (isset($chavepesquisa)) {
    $codigoVeiculo = $chavepesquisa;
}

if (isset($alterar)) {

    $clcondataconf = new cl_condataconf;

    if ($sqlerro == false) {
        // $result = db_query($clcondataconf->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit')));
        // $c99_datapat = db_utils::fieldsMemory($result, 0)->c99_datapat;
        // $datecadastro = implode("-",array_reverse(explode("/",$ve01_dtaquis)));

        $anousu = db_getsession('DB_anousu');

        $sSQL = "select to_char(c99_datapat,'YYYY') c99_datapat
    from condataconf
    where c99_instit = " . db_getsession('DB_instit') . "
    order by c99_anousu desc limit 1";

        $rsResult       = db_query($sSQL);
        $maxC99_datapat = db_utils::fieldsMemory($rsResult, 0)->c99_datapat;

        $sNSQL = "";
        if ($anousu > $maxC99_datapat) {
            $sNSQL = $clcondataconf->sql_query_file($maxC99_datapat, db_getsession('DB_instit'), 'c99_datapat');
        } else {
            $sNSQL = $clcondataconf->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'), 'c99_datapat');
        }

        $result = db_query($sNSQL);
        $c99_datapat = db_utils::fieldsMemory($result, 0)->c99_datapat;
        $datecadastro = implode("-", array_reverse(explode("/", $ve01_dtaquis)));

        if ($c99_datapat != "" && $datecadastro <= $c99_datapat) {
            $sqlerro = true;
            $erro_msg = "O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.";
        } else {
            $sqlerro = false;
        }
    }
    db_inicio_transacao();
    $db_opcao = 2;


    $result = $clveiculos->sql_record($clveiculos->sql_query_file(null, "ve01_veictipoabast as tipoabast ", null, "ve01_codigo = $ve01_codigo"));
    if ($clveiculos->numrows > 0) {
        db_fieldsmemory($result, 0);
        if ($tipoabast != $ve01_veictipoabast and $tipoabast != "true") {
            $result = $clveicabast->sql_record($clveicabast->sql_query_file(null, "ve70_codigo", null, "ve70_veiculos= $ve01_codigo"));
            if ($clveicabast->numrows > 0) {
                $sqlerro  = true;
                $erro_msg = "J� existe abastecimento cadastrado para este ve�culo.Verifique.";
                $clveicabast->erro_campo = "ve70_codigo";
            }
        }
    }

    $result = $clveiculos->sql_record($clveiculos->sql_query_file(null, "*", null, "ve01_codigo = '$codigoVeiculo'"));

    if ($result) {
        $veiculoPesquisado = db_utils::fieldsMemory($result, 0);
    } else $veiculoPesquisado = '';

    if ($veiculoPesquisado) {
        if ($ve01_placa != '') {
            $result = $clveiculos->sql_record($clveiculos->sql_query_file(null, "ve01_placa, ve01_codigo", null, "ve01_placa = '$ve01_placa' and ve01_instit = " . db_getsession('DB_instit')));
            $codigo = db_utils::fieldsMemory($result, 0)->ve01_codigo;
            if ($codigo)
                if ($codigo != $veiculoPesquisado->ve01_codigo) {
                    $sqlerro  = true;
                    $erro_msg = "Placa j� cadastrada para outro ve�culo. Verifique.";
                    $clveiculos->erro_campo = "ve01_placa";
                }
        }

        if ($ve01_ranavam != '') {
            $result = $clveiculos->sql_record($clveiculos->sql_query_file(null, "*", null, "ve01_ranavam = $ve01_ranavam and ve01_instit = " . db_getsession('DB_instit')));
            $codigo = db_utils::fieldsMemory($result, 0)->ve01_codigo;
            if ($codigo)
                if ($codigo != $veiculoPesquisado->ve01_codigo) {
                    $sqlerro  = true;
                    $erro_msg = "Renavam j� cadastrado para outro ve�culo. Verifique.";
                    $clveiculos->erro_campo = "ve01_ranavam";
                }
        }

        if ($ve01_chassi != '') {
            $result = $clveiculos->sql_record($clveiculos->sql_query_file(null, "*", null, "ve01_chassi = '$ve01_chassi' and ve01_instit = " . db_getsession('DB_instit')));
            $codigo = db_utils::fieldsMemory($result, 0)->ve01_codigo;
            if ($codigo)
                if ($codigo != $veiculoPesquisado->ve01_codigo) {
                    $sqlerro  = true;
                    $erro_msg = "Chassi j� cadastrado para outro ve�culo. Verifique.";
                    $clveiculos->erro_campo = "ve01_chassi";
                }
        }
    }

    if ($si04_especificacao == 0 || $si04_especificacao == '') {
        $sqlerro  = true;
        $erro_msg = "Especifica��o do ve�culo n�o informado. Verifique.";
        $clveiculos->erro_campo = "si04_especificacao";
    }

    if ($si04_tipoveiculo == 0 && $si04_tipoveiculo == 0) {
        $sqlerro  = true;
        $erro_msg = "Tipo de ve�culo n�o informado. Verifique.";
        $clveiculos->erro_campo = "si04_tipoveiculo";
    }

    $validacaoNumeroSerie = $clveiculos->validacaoNumeroSerie($ve01_nroserie,$si04_especificacao,$ve01_codigo,"alteracao");
    if($validacaoNumeroSerie == false && $si04_tipoveiculo != 3){
        $sqlerro = true;
        $erro_msg = "Campo N� de S�rie j� cadastrado para o tipo de especifica��o informado.";
    }

    if ($sqlerro == false) {
        $clveiculos->alterar($ve01_codigo, $si04_tipoveiculo);
        $erro_msg = $clveiculos->erro_msg;
        if ($clveiculos->erro_status == "0") {
            $sqlerro = true;
        }
    }

    /*
   * Altera tipo veiculo para sicom
   */
    $result = $cltipoveiculos->sql_record($cltipoveiculos->sql_query_file(null, '*', null, 'si04_veiculos = ' . $ve01_codigo));
    $rows   = pg_num_rows($result);
    if (!empty($rows)) {
        if ($sqlerro == false) {
            $cltipoveiculos->si04_numcgm = $si04_numcgm;
            $cltipoveiculos->alterar($ve01_codigo);
            $erro_msg = $cltipoveiculos->erro_msg;
            if ($cltipoveiculos->erro_status == "0") {
                $sqlerro = true;
            }
        }
    } else {
        if ($sqlerro == false) {
            $cltipoveiculos->si04_veiculos = $ve01_codigo;
            $cltipoveiculos->si04_numcgm = $si04_numcgm;
            $cltipoveiculos->incluir(null);
            $erro_msg = $cltipoveiculos->erro_msg;
            if ($cltipoveiculos->erro_status == "0") {
                $sqlerro = true;
            }
        }
    }

    if ($sqlerro == false) {

        $result_resp = $clveicresp->sql_record($clveicresp->sql_query_file(null, "ve02_codigo", null, " ve02_veiculo = $ve01_codigo "));
        /**
         * Exclui o responsavel, e inclui novamente
         */
        if ($clveicresp->numrows > 0) {

            $clveicresp->excluir(null, "ve02_veiculo={$ve01_codigo}");
            if ($clveicresp->erro_status == "0") {

                $sqlerro = true;
                $erro_msg = $clveicresp->erro_msg;
            }
        }
        if ($ve02_numcgm != 0) {

            $clveicresp->ve02_numcgm = $ve02_numcgm;
            $clveicresp->ve02_veiculo = $clveiculos->ve01_codigo;
            $clveicresp->incluir(null);
            if ($clveicresp->erro_status == "0") {

                $sqlerro = true;
                $erro_msg = $clveicresp->erro_msg;
            }
        }
    }

    if ($sqlerro == false) {

        $result_patri = $clveicpatri->sql_record($clveicpatri->sql_query(null, "ve03_codigo", null, " ve03_veiculo = $ve01_codigo "));
        if ($clveicpatri->numrows > 0) {

            db_fieldsmemory($result_patri, 0);
            /**
             * exclui e incluimos novamente
             */
            $clveicpatri->excluir(null, "ve03_veiculo = {$ve01_codigo}");
            if ($clveicpatri->erro_status == "0") {

                $sqlerro  = true;
                $erro_msg = $clveicpatri->erro_msg;
            }
        }

        if (isset($ve03_bem) && $ve03_bem != '') {

            $clveicpatri->ve03_veiculo = $clveiculos->ve01_codigo;
            $clveicpatri->incluir(null);
            if ($clveicpatri->erro_status == "0") {
                $sqlerro = true;
                $erro_msg = $clveicpatri->erro_msg;
            }
        }
    }
    db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)) {

    $db_opcao = 2;
    $result = $clveiculos->sql_record($clveiculos->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);


    $db_botao = true;
    $result_resp = $clveicresp->sql_record($clveicresp->sql_query(null, "*", null, " ve02_veiculo = $chavepesquisa "));
    if ($clveicresp->numrows > 0) {
        db_fieldsmemory($result_resp, 0);
    }
    $result_patri = $clveicpatri->sql_record($clveicpatri->sql_query(null, "*", null, " ve03_veiculo = $chavepesquisa "));
    if ($clveicpatri->numrows > 0) {
        db_fieldsmemory($result_patri, 0);
    }

    if (isset($codveictipoabast) && trim($codveictipoabast) != "") {
        $ve01_veictipoabast = $codveictipoabast;
    }

    $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($ve01_veictipoabast));
    if ($clveictipoabast->numrows > 0) {
        db_fieldsmemory($result_veictipoabast, 0);
    }

    //Result para modulo sicom
    $result_tipoveiculos = $cltipoveiculos->sql_record($cltipoveiculos->sql_query(null, "*", null, " si04_veiculos = $chavepesquisa"));
    if ($cltipoveiculos->numrows > 0) {
        db_fieldsmemory($result_tipoveiculos, 0);
        $rsCGM = $clcgm->sql_record($clcgm->sql_query(null, "z01_nome as z01_nomecgm", null, " z01_numcgm = $si04_numcgm"));
        db_fieldsmemory($rsCGM, 0);
    }


?>
     <script>
        parent.document.formaba.veicitensobrig.disabled = false;
        (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_veicitensobrig.location.href = 'vei1_veicitensobrig001.php?ve09_veiculos=<?= @$chavepesquisa ?>';
        parent.document.formaba.veicutilizacao.disabled = false;
        (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_veicutilizacao.location.href = 'vei1_veicutilizacao001.php?ve15_veiculos=<?= @$chavepesquisa ?>';
        parent.document.formaba.veiccentral.disabled = true;
        (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_veiccentral.location.href = 'vei1_veiccentralveiculos001.php?ve09_veiculos=<?= @$chavepesquisa ?>';
        <?
        if (isset($liberaaba) && $liberaaba == true) {
        ?>
            parent.mo_camada('veicitensobrig');
        <?
        }
        ?>
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
<style>
    #ve01_veiccadcor,
    #ve01_veiccadmarca,
    #ve01_veiccadcategcnh,
    #ve01_veictipoabast {
        display: none;
    }

    #ve01_veiccadpotencia,
    #ve01_veiccadtipocapacidade {
        width: 93px;
    }

    #ve01_veiccadpotenciadescr,
    #ve01_veiccadtipocapacidadedescr {
        width: 212px;
    }

    #si04_tipoveiculo,
    #si04_especificacao {
        width: 184px;
    }

    #si04_situacao,
    #ve01_veiccadmarcadescr {
        width: 395px;
    }

    #ve02_numcgm,
    #ve01_veiccadtipo {
        width: 84px;
    }

    #ve06_veiccadcomb {
        width: 394px;
    }

    #ve01_veiccadcategdescr,
    #ve01_veiccadproceddescr {
        width: 133px;
    }

    #ve01_veiccadtipodescr {
        width: 307px;
    }

    #ve01_veiccadcordescr {
        width: 182px;
    }

    #ve01_veictipoabastdescr,
    #ve01_veiccadcategcnhdescr {
        width: 180px;
    }

    .div__anos {
        margin-left: -3px;
    }

    .tr__hidden-veiculos {
        display: none;
    }
</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
                <center>
                    <?
                    include("forms/db_frmveiculos.php");
                    ?>
                </center>
            </td>
        </tr>
    </table>
</body>

</html>
<?
if (isset($alterar)) {
    if ($clveiculos->erro_status == "0" && $sqlerro == true) {
        db_msgbox($erro_msg);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clveiculos->erro_campo != "") {
            echo "<script> document.form1." . $clveiculos->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clveiculos->erro_campo . ".focus();</script>";
        }
    } else {
        if ($sqlerro == false) {
            $clveiculos->erro(true, true);
        } else {
            db_msgbox($erro_msg);
            $db_botao = true;
            echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
            if ($clveiculos->erro_campo != "") {
                echo "<script> document.form1." . $clveiculos->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
                echo "<script> document.form1." . $clveiculos->erro_campo . ".focus();</script>";
            }
        }
    }
}

if (isset($chavepesquisa) && isset($ve01_ativo) && trim($ve01_ativo) == "0") {
    db_msgbox("Um ve�culo baixado n�o pode ser alterado!");
    unset($chavepesquisa);
    echo "<script>document.form1.alterar.disabled=true;</script>";
}

if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
    $db_opcao = 2;
}

?>
<script>
    js_tabulacaoforms("form1", "ve01_placa", true, 1, "ve01_placa", true);
</script>
