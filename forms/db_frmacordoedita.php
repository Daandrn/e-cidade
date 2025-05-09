<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_libdicionario.php");
require_once("libs/db_utils.php");

//MODULO: Acordos
$clacordo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac17_sequencial");
$clrotulo->label("descrdepto");
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac08_descricao");
$clrotulo->label("ac50_descricao");
$clrotulo->label("z01_nome");
$clrotulo->label("ac16_licitacao");
$clrotulo->label("l20_objeto");

if ($db_opcao == 1) {
    $db_action = "aco1_acordo004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
    $db_action = "aco1_acordo005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
    $db_action = "aco1_acordo006.php";
}

$aClassificacao = array();
$oDaoAcordoClassificacao = new cl_acordoclassificacao();
$sSqlClassificacao = $oDaoAcordoClassificacao->sql_query_file();
$rsClassificacao = $oDaoAcordoClassificacao->sql_record($sSqlClassificacao);
$aClassificacao[0] = "Selecione";
if ($oDaoAcordoClassificacao->numrows > 0) {

    for ($iIndiceClassificacao = 0; $iIndiceClassificacao < $oDaoAcordoClassificacao->numrows; $iIndiceClassificacao++) {

        $oDadosClassificacao = db_utils::fieldsMemory($rsClassificacao, $iIndiceClassificacao);
        $aClassificacao[$oDadosClassificacao->ac46_sequencial] = $oDadosClassificacao->ac46_descricao;
    }

}

$aLeis = array();
$aLeis[0] = "Selecione";
$oDaoAcordoLeis = new cl_acordoleis();
$sSqlLeis = $oDaoAcordoLeis->sql_query_file();
$rsLeis = $oDaoAcordoLeis->sql_record($sSqlLeis);
if ($oDaoAcordoLeis->numrows > 0) {

    for ($iIndiceLeis = 0; $iIndiceLeis < $oDaoAcordoLeis->numrows; $iIndiceLeis++) {

        $oAcordoLeis = db_utils::fieldsMemory($rsLeis, $iIndiceLeis);
        $aLeis[$oAcordoLeis->ac54_sequencial] = $oAcordoLeis->ac54_descricao;
    }

}

db_app::load("dbtextFieldData.widget.js");

?>
<style>
    .fieldsetinterno {
        border: 0px;
        border-top: 2px groove white;
        border-bottom: 2px groove white;
    }

    td {
        white-space: nowrap
    }

    .table-vigencia td {
        width: 8%;
        white-space: nowrap
    }

    #ac02_descricao, #nomecontratado, #descrdepto, #ac08_descricao {
        width: 75%;
    }

    #ac16_objeto {
        width: 100%;
    }
</style>

<form name="form1" method="post" action="<?= $db_action ?>">
    <center>
        <table border="0" cellspacing="0" cellpadding="0" width="36%">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <fieldset>
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td>
                                    <fieldset style='border:0px'>
                                        <table border="0" cellpadding="0" width="100%">
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_sequencial ?>">
                                                    <?= @$Lac16_sequencial ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 3, "");
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="<?= @$Tac16_anousu ?>">
                                                    <strong>Ano do Acordo:</strong>
                                                </td>
                                                <td>
                                                    <?
                                                    $ac16_anousu = $ac16_anousu != "" ? $ac16_anousu : db_getsession('DB_anousu');
                                                    db_input('ac16_anousu', 10, $Iac16_anousu, true, 'text', $db_opcao,"");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_origem ?>">
                                                    <?= @$Lac16_origem ?>
                                                </td>
                                                <td>
                                                    <?
                                                    if(db_getsession('DB_anousu') <= 2017) {
                                                        $aValores = array(
                                                            0 => 'Selecione',
                                                            1 => 'Processo de Compras',
                                                            2 => 'Licita��o',
                                                            3 => 'Manual',
                                                            6 => 'Empenho'
                                                        );
                                                    }else{
                                                        $aValores = array(
                                                            0 => 'Selecione',
                                                            1 => 'Processo de Compras',
                                                            2 => 'Licita��o',
                                                            3 => 'Manual'
                                                        );
                                                    }
                                                    db_select('ac16_origem', $aValores, true, $db_opcao,
                                                        " onchange='js_desabilitaselecionar();js_exibeBotaoJulgamento();js_verificaOrigem(this.value);js_validaCampoValor();' style='width:100%;'");

                                                    ?>
                                                </td>
                                            </tr>
                                            <tr id="trLicitacao" style="display: <?= $db_opcao == 2 ? 'table-row' : 'none' ?> ;">
                                                <td nowrap>
                                                    <?
                                                    db_ancora('<b>Licita��o:</b>',"js_pesquisa_liclicita(true)", 1);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input("ac16_licitacao",10,$Iac16_licitacao,true,"text",1,
                                                        "onchange='js_pesquisa_liclicita(false)'");
                                                    db_input("l20_objeto",40,$Il20_objeto,true,"text",3,'');
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_tipoorigem ?>">
                                                    <?= @$Lac16_tipoorigem ?>
                                                </td>
                                                <td>
                                                    <?
                                                    $aValores = array(
                                                        0 => 'Selecione',
                                                        1 => '1 - N�o ou dispensa por valor',
                                                        2 => '2 - Licita��o',
                                                        3 => '3 - Dispensa ou Inexigibilidade',
                                                        4 => '4 - Ades�o � ata de registro de pre�os',
                                                        5 => '5 - Licita��o realizada por outro �rg�o ou entidade',
                                                        6 => '6 - Dispensa ou Inexigibilidade realizada por outro �rg�o ou entidade',
                                                        7 => '7 - Licita��o - Regime Diferenciado de Contrata��es P�blicas - RDC',
                                                        8 => '8 - Licita��o realizada por consorcio p�blico',
                                                        9 => '9 - Licita��o realizada por outro ente da federa��o',
                                                    );
                                                    db_select('ac16_tipoorigem', $aValores, true, $db_opcao,
                                                        "  style='width:100%;'");

                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="<?= @$Tac16_acordogrupo ?>">
                                                    <?
                                                    db_ancora("Natureza do Contrato:", "js_pesquisaac16_acordogrupo(true);", $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', $db_opcao,
                                                        "onchange='js_pesquisaac16_acordogrupo(false);'");
                                                    db_input('ac02_descricao', 30, $Iac02_sequencial, true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_numeroacordo ?>">
                                                    <?= @$Lac16_numeroacordo ?>
                                                </td>
                                                <td>
                                                    <?
                                                    //$ac16_numeroacordo = $ac16_numeroacordo != "" ? $ac16_numeroacordo : Acordo::getProximoNumeroDoAno($ac16_anousu,db_getsession('DB_instit'));
                                                    db_input('ac16_numeroacordo', 10, $Iac16_numeroacordo, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_contratado ?>">
                                                    <?
                                                    db_ancora(@$Lac16_contratado, "jsPesquisaContratadoHabilitado();", $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_contratado', 10, $Iac16_contratado, true, 'text', 3,
                                                        "onchange='js_pesquisaac16_contratado(false);'");
                                                    db_input('nomecontratado', 30, $Iz01_nome, true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_deptoresponsavel ?>">
                                                    <?
                                                    db_ancora("<b>Depto Respons�vel: </b>", "js_pesquisaac16_deptoresponsavel(true);",
                                                        $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_deptoresponsavel', 10, $Iac16_deptoresponsavel, true, 'text',
                                                        $db_opcao, "onchange='js_pesquisaac16_deptoresponsavel(false)';");
                                                    db_input('descrdepto', 30, $Idescrdepto, true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr style="display : none;">
                                                <td nowrap title="<?= @$Tac16_acordocomissao ?>">
                                                    <?
                                                    db_ancora('<b>Comiss�o:</b>', "onchange=js_pesquisaac16_acordocomissao(true)",
                                                        $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_acordocomissao', 10, $Iac16_acordocomissao, true, 'text', $db_opcao,
                                                        "onchange=js_pesquisaac16_acordocomissao(false)");
                                                    db_input('ac08_descricao', 30, $Iac08_descricao, true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr style="display : none;">
                                                <td nowrap title="<?= @$Tac16_lei ?>">
                                                    <?= @$Lac16_lei ?>
                                                </td>
                                                <td>
                                                    <?

                                                    db_select('ac16_lei', $aLeis, true, $db_opcao,
                                                        " style='width:100%;'");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_numeroprocesso ?>">
                                                    <strong>Processo Administrativo:</strong>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_numeroprocesso', 50, $Iac16_numeroprocesso, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_qtdrenovacao ?>">
                                                    <?= $Lac16_qtdrenovacao; ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_qtdrenovacao', 2, @$Iac16_qtdrenovacao, true, 'text', $db_opcao,
                                                        "", "", "");
                                                    db_select("ac16_tipounidtempo", getValoresPadroesCampo("ac16_tipounidtempo"),
                                                        true, $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <b>Contrato Emergencial:</b>
                                                </td>
                                                <td>
                                                    <?
                                                    $aEmergencial = array("f" => "N�o", "t" => "Sim");
                                                    db_select("ac26_emergencial", $aEmergencial, true, $db_opcao, "style='width:100%'");
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr style="display : none;">
                                                <td>
                                                    <b>Per�odos por M�s Comercial:</b>
                                                </td>
                                                <td>
                                                    <?
                                                    $iCampo = 1;
                                                    $sDisabled = ($db_opcao != 1 || $db_opcao != "1") ? " disabled " : "";
                                                    if ($db_opcao == 2 || $db_opcao == 22) {
                                                        $iCampo = 3;
                                                    }

                                                    $aDivisaoPeriodos = array("true" => "SIM", "false" => "N�O");
                                                    db_select("ac16_periodocomercial", $aDivisaoPeriodos, true, $db_opcao, "style='width:100%' {$sDisabled}");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="Tipo de Instrumento">
                                                    <?
                                                    db_ancora('<b>Tipo Instrumento:</b>', "onchange=js_pesquisaac50_descricao(true)", $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_acordocategoria', 10, $Iac50_descricao, true, 'text', $db_opcao,
                                                        "onchange=js_pesquisaac50_descricao(false)");
                                                    db_input('ac50_descricao', 30, $Iac50_descricao, true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td nowrap title="<?= @$Tac16_formafornecimento ?>">
                                                    <?= @$Lac16_formafornecimento ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_formafornecimento', 50, $Iac16_formafornecimento, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="<?= @$Tac16_formapagamento ?>">
                                                    <?= @$Lac16_formapagamento ?>
                                                </td>
                                                <td>
                                                    <?
                                                    db_textarea('ac16_formapagamento', 3, 48, $Iac16_objeto, true, 'text', $db_opcao, "", "", "", "100");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr id='trValorAcordo'>
                                                <td nowrap title="<?= @$Tac50_descricao ?>">
                                                    <strong>Valor do Acordo:</strong>
                                                </td>
                                                <td>
                                                    <?php
                                                    db_input('ac16_valor', 10, $Iac16_valor, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>


                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <fieldset class='fieldsetinterno'>
                                        <legend>
                                            <b>Vig�ncia</b>
                                        </legend>
                                        <table cellpadding="0" border="0" width="100%" class="table-vigencia">
                                            <tr>
                                                <td width="1%">
                                                    <b>Inicio:</b>
                                                </td>
                                                <td>
                                                    <?
                                                    $iCampo = 1;

                                                    if ($db_opcao == 2 || $db_opcao == 22) {
                                                        $iCampo = 3;
                                                    }

                                                    db_inputdata('ac16_datainicio', @$ac16_datainicio_dia, @$ac16_datainicio_mes,
                                                        @$ac16_datainicio_ano, true, 'text', 2,
                                                        "onchange='return js_somardias();'", "", "",
                                                        "return parent.js_somardias();");
                                                    ?>
                                                </td>
                                                <td>
                                                    <b>Fim:</b>
                                                </td>
                                                <td>
                                                    <?

                                                    db_inputdata('ac16_datafim', @$ac16_datafim_dia, @$ac16_datafim_mes, @$ac16_datafim_ano,
                                                        true, 'text', 2, "onchange='return js_somardias();'",
                                                        "", "", "return parent.js_somardias();");
                                                    ?>
                                                </td>
                                                <td>
                                                    <b>Dias:</b>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('diasvigencia', 10, "", true, 'text', 3);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td nowrap title="Prazo de Execu��o">
                                                    <strong>Prazo de Execu��o:</strong>
                                                </td>
                                                <td>
                                                    <?
                                                    db_input('ac16_qtdperiodo', 2, @$Iac16_qtdperiodo, true, 'text', $db_opcao,
                                                        "", "", "");
                                                    db_select("ac16_tipounidtempoperiodo", getValoresPadroesCampo("ac16_tipounidtempoperiodo"),
                                                        true, $db_opcao);
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tac16_objeto ?>" colspan="2">
                                    <fieldset>
                                        <legend><?= @$Lac16_objeto ?></legend>
                                        <?
                                        db_textarea('ac16_objeto', 3, 58, $Iac16_objeto, true, 'text', $db_opcao, "");
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>

        <input name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>"
               type="submit"
               id="db_opcao"
               value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>"
            <?= ($db_botao == false ? "disabled" : "") ?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

        <input name="pesquisarlicitacoes" type="button" id="pesquisarlicitacoes" style="display: none;"
               value="Julgamentos" onclick="oContrato.verificaLicitacoes();">

        <input name="pesquisarEmpenhos" type="button" id="pesquisarEmpenhos" value="Empenhos"
               onclick="js_MostraEmpenhos();" style="display: none; ">

        <input name="" type="button" id="btImprimirContrato" value="Imprimir" onclick="js_imprimirContrato()">

    </center>
</form>

<style>
    .filtroEmpenho {

        width: 91px;
    }
</style>

<script>

    var sCaminhoMensagens = "patrimonial.contratos.db_frmacordo";

    $('trValorAcordo').style.display = 'none';

    function js_validaCampoValor() {

        var iOrigem = $('ac16_origem').value;
        if (iOrigem == 6) {

            $('trValorAcordo').style.display = 'table-row';
            $('ac16_valor').readOnly = false;
        } else {
            $('trValorAcordo').style.display = 'none';
            $('ac16_valor').value = '';
            $('ac16_valor').readOnly = true;
        }

    }

    function js_validaCampoLicitacao() {

        var iOrigem = $('ac16_origem').value;
        if (iOrigem == 3) {

            $('tdLicitacao').style.display = 'block';
        }

    }

    /**
     * valida antes de colar no campo valor
     */
    $('ac16_valor').onpaste = function (event) {
        return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
    }


    /**
     * funcao para retornar licitacao
     */
    function js_pesquisa_liclicita(mostra){
        if(mostra==true){

            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                'db_iframe_liclicita',
                'func_liclicita.php?situacao=10&funcao_js=parent.js_preencheLicitacao|l20_codigo|l20_objeto',
                'Pesquisa Licita��es',true);
        }else{

            if(document.form1.ac16_licitacao.value != ''){

                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                    'db_iframe_liclicita',
                    'func_liclicita.php?situacao=10&pesquisa_chave='+
                    document.form1.ac16_licitacao.value+'&funcao_js=parent.js_preencheLicitacao1',
                    'Pesquisa',false);
            }else{
                document.form1.ac16_licitacao.value = '';
            }
        }
    }
    /**
     * funcao para preencher licitacao  da ancora
     */
    function js_preencheLicitacao(codigo,objeto)
    {
        document.form1.ac16_licitacao.value = codigo;
        document.form1.l20_objeto.value = objeto;
        db_iframe_liclicita.hide();
    }

    /**
     * funcao para verificar origem do acordo para listar ancora da licitacao
     */
    function js_verificaOrigem(iValor)
    {
        if (iValor == 3) {
            $("trLicitacao").style.display = "";
        } else {
            $("trLicitacao").style.display = "none";
        }
    }

    /**
     * funcao que ira realizar vinculo dos empenhos selecionados
     */
    function js_vincularEmpenhos(iNumCgm) {

        var sListaEmpenhos = js_getEmpenhosSelecionados();
        var iOrigem = $F('ac16_origem');
        var iAcordo = $F('ac16_sequencial');
        var oParametros = new Object();

        oParametros.exec = 'vincularEmpenhos';
        oParametros.iNumCgm = iNumCgm;
        oParametros.sListaEmpenhos = sListaEmpenhos;
        oParametros.iOrigem = iOrigem;
        oParametros.iAcordo = iAcordo;

        js_divCarregando(_M(sCaminhoMensagens + '.vincular_empenho'), 'msgBox');

        var oAjaxLista = new Ajax.Request("ac4_acordoinclusao.rpc.php",
            {
                method: "post",
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: js_retornoVincularEmpenhos
            });

    }

    function js_retornoVincularEmpenhos(oAjax) {

        oGridEmpenhos.clearAll(true);
        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.iStatus == 2) {
            alert(oRetorno.sMessage.urlDecode());
            return false;
        }
        alert(oRetorno.sMessage.urlDecode());
        js_getEmpenhosVinculados();
    }

    /*
     * funcao que ira retornar os empenhos selecionados
     */
    function js_getEmpenhosSelecionados() {

        var aListaCheckbox = oGridEmpenhos.getSelection();
        var aListaEmpenhos = new Array();

        aListaCheckbox.each(
            function (aRow) {
                aListaEmpenhos.push(aRow[0]);
            }
        )

        return aListaEmpenhos;
    }


    function js_getEmpenhosVinculados() {


        var iAcordo = $F("ac16_sequencial");

        if (iAcordo == "") {
            return false;
        }

        js_divCarregando(_M(sCaminhoMensagens + '.empenhos_vinculados'), 'msgBox');

        var oParametros = new Object();
        oParametros.iAcordo = iAcordo;
        oParametros.exec = "getEmpenhosVinculadosAcordo";

        var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php",
            {
                method: "post",
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: js_retornoCompletaEmpenhos
            });

    }

    /*
     * fun��o para retornar os empenhos do cgm do contratado selecionado
     *  e os possiveis filtros selecionados na windowaux
     */
    function js_getEmpenhos(iNumCgm) {

        var iCodigoEmpenho = $F("e60_numemp");
        var iNumeroEmpenho = $F("e60_codemp");
        var dtInicial = $F("oTxtDataEmissaoInicial");
        var dtFinal = $F("oTxtDataEmissaoFinal");
        var iAcordo = $F("ac16_sequencial");

        //var msgDiv         = "Pesquisando Empenhos<br>Aguarde ...";
        var oParametros = new Object();

        oParametros.exec = 'getEmpenhos';
        oParametros.iNumCgm = iNumCgm;
        oParametros.iCodigoEmpenho = iCodigoEmpenho;
        oParametros.iNumeroEmpenho = iNumeroEmpenho;
        oParametros.dtInicial = dtInicial;
        oParametros.dtFinal = dtFinal;
        oParametros.iAcordo = iAcordo;

        //js_divCarregando(msgDiv,'msgBox');
        js_divCarregando(_M(sCaminhoMensagens + '.pesquisando_empenhos'), 'msgBox');

        var oAjaxLista = new Ajax.Request("ac4_acordoinclusao.rpc.php",
            {
                method: "post",
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: js_retornoCompletaEmpenhos
            });

    }

    // fun��o monta grid com empenhos retornados do cgm
    function js_retornoCompletaEmpenhos(oAjax) {


        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");


        if (oRetorno.iStatus == 1) {
            oGridEmpenhos.clearAll(true);
            oRetorno.aDadosRetorno.each(
                function (oDado, iInd) {

                    var lVinculado = false;
                    if (oDado.lVinculado == 'true') {
                        lVinculado = true;
                    }

                    var aRow = new Array();
                    aRow[0] = oDado.e60_numemp;
                    aRow[1] = oDado.e60_codemp;
                    aRow[2] = oDado.e60_emiss;
                    aRow[3] = oDado.e60_vlremp;
                    aRow[4] = oDado.e60_resumo.urlDecode();
                    oGridEmpenhos.addRow(aRow, true, false, lVinculado);

                });
            oGridEmpenhos.renderRows();
        } else {
            alert(oRetorno.sMessage.urlDecode());
        }
    }

    //---    WINDOW AUX para exibi��o de Empenhos ======================//
    function js_MostraEmpenhos() {


        var iNumCgm = $F('ac16_contratado');
        var sContratado = $F("nomecontratado");
        var iLarguraJanela = screen.availWidth - 80;
        var iAlturaJanela = screen.availHeight - 250;
        var iAcordo = $F('ac16_sequencial');

        if (iNumCgm == '' || iNumCgm == null) {

            alert(_M(sCaminhoMensagens + '.mostrar_empenhos'));//'Selecione o Cgm do Contratado.');
            return false;
        }

        windowEmpenhos = new windowAux('windowEmpenhos',
            'Empenhos',
            iLarguraJanela,
            iAlturaJanela
        );

        var sConteudoEmpenhos = "<div>";
        sConteudoEmpenhos += "<div id='sTituloWindow'></div> "; // container do message box

        sConteudoEmpenhos += "<div>";
        sConteudoEmpenhos += " <fieldset><legend><strong>Filtros para Sele��o de Empenho</strong></legend>";
        sConteudoEmpenhos += "  <table border ='0'>";

        sConteudoEmpenhos += "    <tr>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <strong>Contratado:</strong>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "      <td style='color: blue;'>";
        sConteudoEmpenhos += iNumCgm + " - " + sContratado;
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "    </tr>";

        sConteudoEmpenhos += "    <tr>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <strong>Acordo:</strong>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <input  type='text' class='filtroEmpenho' value='" + iAcordo + "'  id='iAcordo' readonly='readonly' style='background-color: #DEB887 ' \>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "    </tr>";

        sConteudoEmpenhos += "    <tr>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <strong>C�digo do Empenho:</strong>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <input class='filtroEmpenho' type='text' id='e60_numemp' onkeyup='js_ValidaCampos(this,1,\"C�digo do Empenho\",\"f\",\"f\",event);' >";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "    </tr>";
        sConteudoEmpenhos += "    <tr>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <strong>N�mero do Empenho:</strong>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <input class='filtroEmpenho' type='text' id='e60_codemp' onkeyup='js_ValidaCampos(this,1,\"N�mero do Empenho\",\"f\",\"f\",event);'>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "    </tr>";
        sConteudoEmpenhos += "    <tr>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "        <strong>Per�odo de Emiss�o:</strong>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "      <td>";
        sConteudoEmpenhos += "       <label id='ctnDataInicial' style='float:left;'></label>";
        sConteudoEmpenhos += "         <strong style='float:left;'>&nbsp;at�&nbsp;</strong>";
        sConteudoEmpenhos += "       <div id='ctnDataFinal' style='float:left;' ></div>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "    </tr>";
        sConteudoEmpenhos += "    <tr>";
        sConteudoEmpenhos += "      <td colspan='2' align='center'>";
        sConteudoEmpenhos += "        <input style='margin-top:10px;' type='button' id='filtraEmpenho' name='filtraEmpenho' value='Filtrar' onclick='js_getEmpenhos(" + iNumCgm + ");'>";
        sConteudoEmpenhos += "      </td>";
        sConteudoEmpenhos += "    </tr>";
        sConteudoEmpenhos += "  </table>";
        sConteudoEmpenhos += " ";
        sConteudoEmpenhos += "<div>";
        sConteudoEmpenhos += "<div id='sContGrid' style='width: 100%;'></div></fieldset> ";    // container da grid com empenhos;
        sConteudoEmpenhos += "<center style='margin-top:10px;'>";
        sConteudoEmpenhos += "  <input type='button' value='Salvar' onclick='js_vincularEmpenhos(" + iNumCgm + ");' />";
        sConteudoEmpenhos += "  <input type='button' value='Cancelar' onclick='windowEmpenhos.destroy();' />";
        sConteudoEmpenhos += "<center>";

        sConteudoEmpenhos += "</div>";

        windowEmpenhos.setContent(sConteudoEmpenhos);

        //============  MESAGE BORD PARA TITULO da JANELA de Empenhos
        var sTextoMessageBoard = 'Procedimento para vincular ou desvincular empenhos ao acordo. <br> ';
        messageBoard = new DBMessageBoard('msgboard1',
            'Empenhos Vinculados e a Vincular',
            sTextoMessageBoard,
            $('sTituloWindow'));

        // instancia dos objetos db_inputdata
        oTxtDataEmissaoInicial = new DBTextFieldData('oTxtDataEmissaoInicial', 'oTxtDataEmissaoInicial', null);
        oTxtDataEmissaoFinal = new DBTextFieldData('oTxtDataEmissaoFinal', 'oTxtDataEmissaoFinal', null);


        //    funcao para corrigir a exibi��o do window aux, apos fechar a primeira vez
        windowEmpenhos.setShutDownFunction(function () {
            windowEmpenhos.destroy();
        });

        windowEmpenhos.show();
        messageBoard.show();                    // chamada que monta message board
        oTxtDataEmissaoInicial.show($('ctnDataInicial')); // chamada que monta input data inicial
        oTxtDataEmissaoFinal.show($('ctnDataFinal'));   // chamada que monta input data final
        js_montaGridEmpenhos();                           // chamada fun��o que monta a grid

        js_getEmpenhosVinculados();
    }

    //fun��o para chamada da grid que tera erros e avisos

    function js_montaGridEmpenhos() {

        var sNameGrid = 'Empenhos';
        oGridEmpenhos = new DBGrid(sNameGrid);
        oGridEmpenhos.nameInstance = 'oGridEmpenhos';
        oGridEmpenhos.allowSelectColumns(false);
        oGridEmpenhos.setCheckbox(0);

        oGridEmpenhos.setCellWidth(new Array('10%',
            '10%',
            '10%',
            '10%',
            '60%'
        ));

        oGridEmpenhos.setCellAlign(new Array('center',
            'right',
            'center',
            'right',
            'left'
        ));

        oGridEmpenhos.setHeader(new Array('C�digo do Empenho',
            'N�mero do Empenho',
            'Emiss�o',
            'Valor Empenhado',
            'Resumo'
        ));

        oGridEmpenhos.setHeight(200);
        oGridEmpenhos.show($('sContGrid'));
        oGridEmpenhos.clearAll(true);

        $('grid' + sNameGrid).style.width = '99%';
    }

    /*
     * funcao que controla a exibi��o
     dos botoes JULGAMENTOS e EMPENHOS
     se a origem for Licitacao || Processo compras exibimos os julgamentos
     se a origem for empenho exibimos o botao para selecionar empenhos
     */
    function js_exibeBotaoJulgamento() {

        var iOrigem = $F('ac16_origem');
        var iAcordo = $F("ac16_sequencial");

        switch (iOrigem) {

            case  '1':
                $('pesquisarlicitacoes').style.display = 'inLine';
                $('pesquisarEmpenhos').style.display = 'none';
                break;

            case  '2':
                $('pesquisarlicitacoes').style.display = 'inLine';
                $('pesquisarEmpenhos').style.display = 'none';
                break;

            case '6' :

                if ($F('ac16_sequencial') != '') {
                    $('pesquisarEmpenhos').style.display = 'inLine';
                }

                $('pesquisarlicitacoes').style.display = 'none';
                break;

            default  :

                $('pesquisarlicitacoes').style.display = 'none';
                $('pesquisarEmpenhos').style.display = 'none';
                break;

        }

        if (iAcordo != '' || iAcordo != null) {
            //alert(5555);
            //$('ac16_origem').disabled        = true;
        }

    }


    var oContrato = new contrato();
    var sURL = "con4_contratos.RPC.php";

    function js_somardias() {

        var sDataInicio = $('ac16_datainicio').value;
        var sDataFim = $('ac16_datafim').value;

        if (js_somarDiasVigencia(sDataInicio, sDataFim) != false) {
            $('diasvigencia').value = js_somarDiasVigencia(sDataInicio, sDataFim);
        }
    }

    function js_desabilitaselecionar() {

        //Limpa op��es de contratado previamente selecionadas
        $("ac16_contratado").value = '';
        $("nomecontratado").value = '';

        var iAcordoOrigem = $('ac16_origem').value;

        if (iAcordoOrigem != 0) {
            $('ac16_origem').options[0].disabled = true;
        }
    }


    function js_validaOrigemTipo(limpar = false) {
        if (limpar) {
            var valido = false;
        } else {
            var valido = true;
            var aProcessoDeCompras = [1];
            var aLicitacao = [2, 3];
            var aManual = [4, 5, 6, 7, 8, 9];

            switch (+$('ac16_origem').value) {
                case 1:
                    if (aProcessoDeCompras.indexOf(+$('ac16_tipoorigem').value) < 0) {
                        alert('Selecione um tipo de origem v�lido para a origem escolhida. Tipo(s) v�lido(s): 1');
                        valido = false;
                    }
                    break;
                case 2:

                    if (aLicitacao.indexOf(+$('ac16_tipoorigem').value) < 0) {
                        alert('Selecione um tipo de origem v�lido para a origem escolhida. Tipo(s) v�lido(s): 2 e 3');
                        valido = false;
                    }
                    break;
                case 3:

                    if (aManual.indexOf(+$('ac16_tipoorigem').value) < 0) {
                        alert('Selecione um tipo de origem v�lido para a origem escolhida. Tipo(s) v�lido(s): 4, 5 , 6, 7, 8 ou 9');
                        valido = false;
                    }
                    break;
                default:
                    alert('Selecione um tipo de origem v�lido para a origem escolhida.');
                    valido = false;
            }
        }
        if (!valido) {
            $('ac16_tipoorigem').value = '0';
            $('ac16_tipoorigem').focus();
        }


    }

    function js_pesquisaac16_acordogrupo(mostra) {

        if (mostra == true) {

            var sUrl = 'func_acordogrupo.php?funcao_js=parent.js_mostraacordogrupo1|ac02_sequencial|ac02_descricao';
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                'db_iframe_acordogrupo',
                sUrl,
                'Pesquisar Grupos de Acordo',
                true,
                '0');
        } else {

            if ($('ac16_acordogrupo').value != '') {

                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                    'db_iframe_acordogrupo',
                    'func_acordogrupo.php?pesquisa_chave=' + $('ac16_acordogrupo').value +
                    '&funcao_js=parent.js_mostraacordogrupo',
                    'Pesquisar Grupos de Acordo',
                    false,
                    '0');
            } else {
                $('ac02_sequencial').value = '';
            }
        }
    }

    function js_mostraacordogrupo(chave, erro) {

        $('ac02_descricao').value = chave;
        if (erro == true) {

            $('ac16_acordogrupo').focus();
            $('ac16_acordogrupo').value = '';
        } else {

            var oGet = js_urlToObject();

            /*
             * Verifica se est� sendo setada a variavel chavepesquisa na url. Caso sim, quer dizer que � um procedimento de altera��o ou exclus�o,
             * sendo assim o programa n�o pode chamar a nova numera��o
             *

            if (!oGet.chavepesquisa) {
                oContrato.getNumeroAcordo();
            }*/

        }

    }

    function js_getNumeroAcordo(){
        var oGet = js_urlToObject();
        if (!oGet.chavepesquisa) {
            oContrato.getNumeroAcordoAno($('ac16_anousu').value,<?=db_getsession('DB_instit')?>);
        }
    }

    function js_mostraacordogrupo1(chave1, chave2) {

        $('ac16_acordogrupo').value = chave1;
        $('ac02_descricao').value = chave2;
        $('ac16_acordogrupo').focus();

        var oGet = js_urlToObject();

        /*
         * Verifica se est� sendo setada a variavel chavepesquisa na url. Caso sim, quer dizer que � um procedimento de altera��o ou exclus�o,
         * sendo assim o programa n�o pode chamar a nova numera��o
         *

        if (!oGet.chavepesquisa) {
            oContrato.getNumeroAcordo();
        }*/

        db_iframe_acordogrupo.hide();
    }

    function js_pesquisa() {

        var sUrl = 'func_acordo.php?funcao_js=parent.js_preenchepesquisa|ac16_sequencial' +
            '&iTipoFiltro=1,4&lAtivo=1&lComExecucao=false';
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
            'db_iframe_acordo',
            sUrl,
            'Pesquisar Acordos',
            true,
            '0',
            '1'
        );
    }

    function js_preenchepesquisa(chave) {

        db_iframe_acordo.hide();
        <?
          if($db_opcao!=1){
            echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
          }
        ?>

    }

    /**
     * funcao para mostrar fornecedores habilitados quando origem do acordo for manual
     */

    function jsPesquisaContratadoHabilitado() {

        if ($('ac16_origem').value == 3) {
            var nLicitacao = $('ac16_licitacao').value;

            if (nLicitacao == '') {
                js_pesquisaac16_contratado(true);
            }else{
                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                    'db_iframe_contratado',
                    'lic3_fornhabilitados.php?l20_codigo=' + nLicitacao + '&funcao_js=parent.js_mostracontratado1|z01_nome|z01_numcgm',
                    'CGM Contratado',
                    true,
                    '0');
            }
        }else {
            nLicitacao == '';
            js_pesquisaac16_contratado(true);
        }
    }

    function js_pesquisaac16_contratado(mostra) {

        if (mostra == true) {

          js_OpenJanelaIframe(
            'CurrentWindow.corpo.iframe_acordo',
            'db_iframe_contratado',
            'func_pcforne.php?infoValidacaoRepresentanteAcordo=true&validaRepresentante=true&funcao_js=parent.js_mostracontratado1|z01_nome|pc60_numcgm',
            'Pesquisa',
            true,
            '0',
            '1'
          );

        } else {

            if ($('ac16_contratado').value != '') {

              js_OpenJanelaIframe(
                'CurrentWindow.corpo.iframe_acordo',
                'db_iframe_contratado',
                'func_pcforne.php?infoValidacaoRepresentanteAcordo=true&validaRepresentante=true&pesquisa_chave=' + $F('ac16_contratado') + 'funcao_js=parent.js_mostracontratado1|z01_nome|pc60_numcgm',
                'Pesquisa',
                false,
                '0',
                '1'
              );

            } else {
                $('nomecontratado').value = '';
            }
        }
    }

    function js_mostracontratado(erro, chave) {

        $('nomecontratado').value = chave;
        if (erro == true) {

            $('ac16_contratado').focus();
            $('ac16_contratado').value = '';
        } else {
            oContrato.verificaLicitacoes();

            if ($('ac16_origem').value == 6) {
                //js_MostraEmpenhos();
            }

        }
    }

    function js_mostracontratado1(chave1, chave2) {

        $('ac16_contratado').value = chave2;
        $('nomecontratado').value = chave1;
        oContrato.verificaLicitacoes();

        if ($('ac16_origem').value == 6) {
            //js_MostraEmpenhos();
        }

        db_iframe_contratado.hide();
    }

    function js_pesquisaac16_deptoresponsavel(mostra) {

        if (mostra == true) {

            var sUrl = 'func_db_depart.php?funcao_js=parent.js_mostradeptoresponsavel1|coddepto|descrdepto';
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                'db_iframe_deptoresponsavel',
                sUrl,
                'Pesquisar CGM',
                true,
                '0');
        } else {

            if ($('ac16_deptoresponsavel').value != '') {

                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                    'db_iframe_acordogrupo',
                    'func_db_depart.php?pesquisa_chave=' + $F('ac16_deptoresponsavel') +
                    '&funcao_js=parent.js_mostradeptoresponsavel',
                    'Pesquisa',
                    false,
                    '0');
            } else {
                $('descrdepto').value = '';
            }
        }
    }

    function js_mostradeptoresponsavel(chave, erro) {

        $('descrdepto').value = chave;
        if (erro == true) {

            $('ac16_deptoresponsavel').focus();
            $('ac16_deptoresponsavel').value = '';
        }
    }

    function js_mostradeptoresponsavel1(chave1, chave2) {

        $('ac16_deptoresponsavel').value = chave1;
        $('descrdepto').value = chave2;
        $('ac16_deptoresponsavel').focus();

        db_iframe_deptoresponsavel.hide();
    }

    function js_pesquisaac16_acordocomissao(mostra) {

        if (mostra == true) {

            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                'db_iframe_comissao',
                'func_acordocomissao.php?funcao_js=parent.js_mostracomissao1|' +
                'ac08_sequencial|ac08_descricao',
                'Pesquisar Comiss�es de Vistoria',
                true,
                '0');
        } else {

            if ($('ac16_acordocomissao').value != '') {

                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                    'db_iframe_comissao',
                    'func_acordocomissao.php?pesquisa_chave=' + $F('ac16_acordocomissao') +
                    '&funcao_js=parent.js_mostracomissao',
                    'Pesquisar Comiss�es de Vistoria',
                    false,
                    '0');
            } else {
                $('ac08_descricao').value = '';
            }
        }
    }

    function js_mostracomissao(chave, erro) {

        $('ac08_descricao').value = chave;
        if (erro) {

            $('ac16_acordocomissao').focus();
            $('ac16_acordocomissao').value = '';
        }
    }

    function js_mostracomissao1(chave1, chave2) {

        $('ac16_acordocomissao').value = chave1;
        $('ac08_descricao').value = chave2;
        $('ac16_acordocomissao').focus();

        db_iframe_comissao.hide();
    }

    function js_pesquisaac50_descricao(mostra) {

        if (mostra == true) {

            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                'db_iframe_acordocategoria',
                'func_acordocategoria.php?funcao_js=parent.js_mostraacordocategoria1|' +
                'ac50_sequencial|ac50_descricao',
                'Pesquisar Tipos de Instrumentos de Acordo',
                true,
                '0');
        } else {

            if ($('ac16_acordocategoria').value != '') {

                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
                    'db_iframe_acordocategoria',
                    'func_acordocategoria.php?pesquisa_chave=' + $F('ac16_acordocategoria') +
                    '&funcao_js=parent.js_mostraacordocategoria',
                    'Pesquisar Categorias de Acordo',
                    false,
                    '0');
            } else {
                $('ac50_descricao').value = '';
            }
        }
    }

    function js_mostraacordocategoria(chave1, chave2) {

        $('ac50_descricao').value = chave1;
        $('ac16_acordocategoria').focus();

        db_iframe_acordocategoria.hide();
    }

    function js_mostraacordocategoria1(chave1, chave2) {

        $('ac16_acordocategoria').value = chave1;
        $('ac50_descricao').value = chave2;
        $('ac16_acordocategoria').focus();

        db_iframe_acordocategoria.hide();
    }

    function js_imprimirContrato() {

        var iContrato = $('ac16_sequencial').value;
        var iTipoOrigem = $('ac16_origem').value;

        var sUrl = 'aco2_impressaoacordo001.php?iContrato=' + iContrato + '&iTipoOrigem=' + iTipoOrigem;
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordo',
            'db_iframe_impressaocontrato',
            sUrl,
            'Impress�o do Contrato',
            true,
            '0');
    }

    $('db_opcao').onclick = oContrato.saveContrato;
    <?
      if ($db_opcao == 2) {
        echo "\noContrato.getContrato({$chavepesquisa});\n";
      } else {
          echo "\njs_somardias();\n";
      }
    ?>

    $('ac16_valor').observe("change", function () {

        $('ac16_valor').value = js_formatar($('ac16_valor').value, "f");
    });

js_somardias();
</script>

