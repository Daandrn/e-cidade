<?
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

//MODULO: Acordos
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clacordoitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("pc01_descrmater");
$db_opcao = 1;
$aParam = db_stdClass::getParametro("empparametro", array(db_getsession("DB_anousu")));
$iCasasDecimais = 2;
if (count($aParam) > 0) {
    $iCasasDecimais = $aParam[0]->e30_numdec;
}

$oDaoAcordo = db_utils::getDao('acordo');
$sSqlAcordo = $oDaoAcordo->sql_query_file($ac20_acordo);
$rsAcordo = $oDaoAcordo->sql_record($sSqlAcordo);
if ($oDaoAcordo->numrows > 0) {

    $oAcordo = db_utils::fieldsMemory($rsAcordo, 0);
}

?>
<style>
    .fracionado input[type='checkbox'] {
        display: none;
        height: 15px
    }

    .fracionado input[type='button'] {
        height: 15px;
    }

    .fracionado {
        background-color: #FFFFFF
    }

    .fracionado td {
        empty-cells: show;
    }

    .fracionadoinvalido {
        background-color: #FF4649
    }

    .fieldsetinterno {
        border: 0px;
        border-top: 2px groove white;
        border-bottom: 2px groove white;
    }

    fieldset.fieldsetinterno table {

        width: 100%;
        table-layout: auto;
    }

    fieldset.fieldsetinterno table tr TD:FIRST-CHILD {

        width: 80px;
        white-space: nowrap;
    }

    select {
        width: 100%;
    }

    fieldset.fieldsetinterno table tr TD {
        white-space: nowrap;
    }

    legend {
        font-weight: bold;
    }
</style>

<script type="text/javascript" src="scripts/classes/DBViewAcordoPrevisao.classe.js"></script>
<script type="text/javascript" src="scripts/classes/DBViewAcordoExecucao.classe.js"></script>
<?
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js, DBHint.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js,dbtextFieldData.widget.js");
db_app::load("time.js");
db_app::load("estilos.css, grid.style.css");
?>
<form name="form1" method="post" action="">
    <center>
        <table border="0" width="40%">
            <tr height="20px;">
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <fieldset id='FormularioManual'>
                        <table border="0" width="100%">
                            <tr style='display: none'>
                                <td nowrap title="<?= @$Tac20_acordo ?>">
                                    <?
                                    echo @$Lac20_acordo;
                                    ?>
                                </td>
                                <td>
                                    <?
                                    db_input('ac20_acordo', 10, @$Iac20_acordo, true, 'text', $db_opcao, " onchange='js_pesquisaac20_acordo(false);'");
                                    db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 3, '')
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td id='tdMatMater' nowrap title="<?= @$Tac20_pcmater ?>">
                                    <?
                                    db_ancora(@$Lac20_pcmater, "js_pesquisaac20_pcmater(true);", $db_opcao);
                                    ?>
                                </td>
                                <td colspan="4">
                                    <?
                                    db_input('ac20_pcmater', 10, $Iac20_pcmater, true, 'text', $db_opcao, " onchange='js_pesquisaac20_pcmater(false);'");
                                    db_input('pc01_descrmater', 40, $Ipc01_descrmater, true, 'text', 3, '')
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tac20_quantidade ?>">
                                    <?= @$Lac20_quantidade ?>
                                </td>
                                <td>
                                    <?
                                    db_input('ac20_quantidade', 10, $Iac20_quantidade, true, 'text', $db_opcao, "")
                                    ?>
                                </td>

                                <td nowrap title="<?= @$Tac20_matunid ?>">
                                    <?= @$Lac20_matunid ?>
                                </td>
                                <td>
                                    <?
                                    $oDaoMatUnid = db_utils::getDao("matunid");
                                    $sSqlUnidades = $oDaoMatUnid->sql_query_file(null, "m61_codmatunid,substr(m61_descr,1,20) as m61_descr", "m61_descr","m61_ativo = 't'");
                                    $rsUnidades = $oDaoMatUnid->sql_record($sSqlUnidades);
                                    $iNumRowsUnidade = $oDaoMatUnid->numrows;
                                    $aUnidades = array(0 => "Selecione");
                                    for ($i = 0; $i < $iNumRowsUnidade; $i++) {

                                        $oUnidade = db_utils::fieldsMemory($rsUnidades, $i);
                                        $aUnidades[$oUnidade->m61_codmatunid] = $oUnidade->m61_descr;
                                    }
                                    db_select("ac20_matunid", $aUnidades, true, 1, "onchange='js_desabilitaItemSelecionar();' style='width:100%'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tac20_valorunitario ?>">
                                    <?= @$Lac20_valorunitario ?>
                                </td>
                                <td>
                                    <?
                                    db_input('ac20_valorunitario', 10, $Iac20_valorunitario, true, 'text', $db_opcao, "")
                                    ?>
                                </td>

                                <td>
                                    <b>Servi�o Controlado por Quantidades:</b>
                                </td>
                                <td>
                                    <?
                                    db_select("ac20_servicoquantidade", array("f" => "N�O", "t" => "SIM"), true, $db_opcao, "onChange='js_verificaServico();'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td nowrap title="<?= @$Tac20_marca ?>">
                                    <b>Marca</b>
                                </td>
                                <td colspan="3">
                                    <?
                                    db_input('ac20_marca', 52, $Iac20_marca, true, 'text', $db_opcao, "")
                                    ?>
                                </td>


                            </tr>
                            <tr>
                                <td nowrap title="<?= @$Tac20_elemento ?>">
                                    <?= @$Lac20_elemento ?>
                                </td>
                                <td colspan="3">
                                    <?
                                    $aDesdobramento = array("0" => "Selecione");
                                    db_select("ac20_elemento", $aDesdobramento, true, $db_opcao, "onchange='js_desabilitaItemSelecionar();' style='width:100%'");
                                    ?>
                                </td>
                            </tr>

                        </table>
                        <table style="width: 100%; display:none;">
                            <tr>
                                <td colspan="4">
                                    <fieldset class='fieldsetinterno'>
                                        <legend>
                                            <b>Previs�o de execu��o</b>
                                        </legend>
                                        <table cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="1%">
                                                    <b>De:</b>
                                                </td>
                                                <td>
                                                    <?
                                                    db_inputdata('ac41_datainicial', @$ac41_datainicial_dia, @$ac41_datainicial_mes, @$ac41_datainicial_ano, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <b>At�:</b>
                                                </td>
                                                <td>
                                                    <?
                                                    db_inputdata('ac41_datafinal', @$ac41_datafinal_dia, @$ac41_datafinal_mes, @$ac41_datafinal_ano, true, 'text', $db_opcao);
                                                    ?>
                                                </td>
                                                <td>
                                                    <input type="button" id='addPerido' value='Adicionar' onclick="addPeriodo();">
                                                </td>

                                            </tr>
                                        </table>
                                        <div id="gridPeriodos">
                                        </div>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                        <table style="width: 100%;">
                            <tr>
                                <td nowrap colspan="4" title="<?= @$Tac20_obseracao ?>">
                                    <fieldset>
                                        <legend><?= @$Lac20_resumo ?></legend>
                                        <?
                                        db_textarea("ac20_resumo", 3, 50, $Iac20_resumo, true, 'text', 1, "style='width:100%'");
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input name="incluir" type="button" id="db_opcao" onclick="addPeriodo();js_saveItem();" value="Incluir">
                    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_limparFormulario();" style='display:none'>


                </td>
            </tr>
        </table>
        <fieldset>
            <legend><b>Itens</b></legend>
            <b>Se ap�s vincular a dota��o a linha permanecer em vermelho, a quantidade do item difere da quantidade da dota��o.</b>
            <div id='cntgriditens'></div>
        </fieldset>
        <?php
        $ac20_tipocontrole = '1';
        db_input('ac20_tipocontrole', 10, 0, 0, 'hidden');
        ?>
        <input name="verificaritens" type="button" id="verificarItens" value="Verificar Itens" onclick='js_getItensOrigem()'>

        <input type='button' onclick='js_verificaTipoAcordo();' value='Itens do Empenho' id='itensEmpenho' style='display:none;' />


    </center>
</form>
<script>
    var sURL = "con4_contratosaux.RPC.php";
    iCodigoItem = '';
    iElementoDotacao = '';
    iCasasDecimais = <?= $iCasasDecimais ?>;
    sTipoOrigem = null;
    desabilitaDotacao = true;
    isServico = 'f';
    isExecutado = new Array();

    function js_pesquisaac20_acordo(mostra) {

        if (mostra == true) {

            js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                'db_iframe_acordo',
                'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_sequencial',
                'Pesquisa', true, '0', '1', '775', '390');
        } else {
            if (document.form1.ac20_acordo.value != '') {
                js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                    'db_iframe_acordo',
                    'func_acordo.php?pesquisa_chave=' + document.form1.ac20_acordo.value +
                    '&funcao_js=parent.js_mostraacordo', 'Pesquisa', false);
            } else {
                document.form1.ac16_sequencial.value = '';
            }
        }
    }

    function js_mostraacordo(chave, erro) {
        document.form1.ac16_sequencial.value = chave;
        if (erro == true) {
            document.form1.ac20_acordo.focus();
            document.form1.ac20_acordo.value = '';
        }
    }

    function js_mostraacordo1(chave1, chave2) {
        document.form1.ac20_acordo.value = chave1;
        document.form1.ac16_sequencial.value = chave2;
        db_iframe_acordo.hide();
    }

    function js_pesquisaac20_pcmater(mostra) {
        if (mostra) {

            js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                'db_iframe_pcmater',
                'func_pcmatercontratos.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater|pc01_servico',
                'Pesquisar Materiais',
                true, '0'
            );
        } else {
            if (document.form1.ac20_pcmater.value != '') {
                js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                    'db_iframe_pcmater',
                    'func_pcmatercontratos.php?pesquisa_chave=' + document.form1.ac20_pcmater.value +
                    '&funcao_js=parent.js_mostrapcmater',
                    'Pesquisar materiais',
                    false, 0);
            } else {
                document.form1.pc01_descrmater.value = '';
            }
        }
    }

    function js_mostrapcmater(chave, erro) {
        document.form1.pc01_descrmater.value = chave;
        if (erro == true) {
            document.form1.ac20_pcmater.focus();
            document.form1.ac20_pcmater.value = '';
        } else {
            js_getElementosMateriais();
        }
    }

    function js_mostrapcmater1(chave1, chave2, chave3) {
        document.form1.ac20_pcmater.value = chave1;
        document.form1.pc01_descrmater.value = chave2;
        db_iframe_pcmater.hide();
        isServico = chave3;
        js_verificaServico();
        js_getElementosMateriais();

    }

    function js_pesquisaMaterial(mostra) {
        if (mostra) {

            js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                'db_iframe_pcmater',
                'func_pcmater.php?funcao_js=parent.js_mostraMaterial|pc01_codmater|pc01_descrmater|pc01_servico',
                'Pesquisar Materiais',
                true, '0'
            );
        } else {

            if (document.form1.ac20_pcmater.value != '') {
                js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                    'db_iframe_pcmater',
                    'func_pcmater.php?pesquisa_chave=' + document.form1.ac20_pcmater.value +
                    '&funcao_js=parent.js_mostrapcmater',
                    'Pesquisar materiais',
                    false, 0);
            } else {
                document.form1.pc01_descrmater.value = '';
            }
        }
    }

    function js_mostrapcmater(chave, erro, chave2) {
        document.form1.pc01_descrmater.value = chave;
        if (erro == true) {
            document.form1.ac20_pcmater.focus();
            document.form1.ac20_pcmater.value = '';
        } else {
            isServico = chave2;
            js_verificaServico();
            js_getElementosMateriais();
        }
    }

    function js_mostraMaterial(chave1, chave2, chave3) {

        oTxtMaterial.setValue(chave1);
        oTxtDescrMaterial.setValue(chave2);
        isServico = chave3;
        js_verificaServico();
        db_iframe_pcmater.hide();
    }

    function js_pesquisao47_coddot(mostra) {
        query = '';
        if (iElementoDotacao != '') {
            query = "elemento=" + iElementoDotacao + "&";
        }

        if (mostra == true) {
            js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                'db_iframe_orcdotacao',
                'func_permorcdotacao.php?' + query + 'funcao_js=parent.js_mostraorcdotacao1|o58_coddot',
                'Pesquisar Dota��es',
                true, 0);

            $('Jandb_iframe_orcdotacao').style.zIndex = '100000000';
        } else {
            js_OpenJanelaIframe('(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordoitem',
                'db_iframe_orcdotacao',
                'func_permorcdotacao.php?' + query + 'pesquisa_chave=' + document.form1.o47_coddot.value +
                '&funcao_js=parent.js_mostraorcdotacao',
                'Pesquisar Dota��es',
                false
            );
        }
    }

    function js_mostraorcdotacao(chave, erro) {
        if (erro == true) {
            document.form1.o47_coddot.focus();
            document.form1.o47_coddot.value = '';
        }
        getSaldoDotacao(chave);
    }

    function js_mostraorcdotacao1(chave1) {

        oTxtDotacao.setValue(chave1);
        db_iframe_orcdotacao.hide();
        $('Jandb_iframe_orcdotacao').style.zIndex = '0';
        $('oTxtQuantidadeDotacao').focus();
        getSaldoDotacao(chave1);

    }

    function js_showGrid() {
        oGridItens = new DBGrid('gridItens');
        oGridItens.nameInstance = 'oGridItens';
        oGridItens.setCellAlign(new Array("center", "center", "left", 'right', 'right', 'right', "left", "center", "center", "center"));
        oGridItens.setCellWidth(new Array("5%", "5%", "20%", '6%', '5%', '5%', "30%", "5%", "6%", "10%"));
        oGridItens.setHeader(new Array("Ordem", "C�digo", "Material", "Quantidade",
            "Vlr Un", "Total", "Elemento", "Per�odos", "Dota��es", "A��o"
        ));
        oGridItens.hasTotalizador = true;
        oGridItens.show($('cntgriditens'));

        var width = $('cntgriditens').scrollWidth - 30;
        $("table" + oGridItens.sName + "header").style.width = width;
        $(oGridItens.sName + "body").style.width = width;
        $("table" + oGridItens.sName + "footer").style.width = width;
    }


    function js_init() {

        if (oGridItens) {
            js_showGrid();
        }

        js_getItens();
    }

    function js_getItens() {

        var oParam = new Object();
        oParam.iAcordo = $F('ac20_acordo');
        oParam.exec = "getItensAcordo";
        js_divCarregando('Aguarde, pesquisando Itens', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoGetItens
            }
        );
    }

    function js_retornoGetItens(oAjax) {

        js_removeObj('msgBox');
        oGridItens.clearAll(true);
        var oRetorno = eval("(" + oAjax.responseText + ")");
        iTipoContrato = oRetorno.iTipoContrato;

        if (oRetorno.iTipoContrato == 2 || oRetorno.iTipoContrato == 1) {

            $('FormularioManual').style.display = '';
            $('db_opcao').style.display = 'none';
            if (oRetorno.itens.length == 0) {
                js_getItensOrigem();
            }

        } else {
            $('FormularioManual').style.display = '';
        }
        if (oRetorno.status == 1) {

            if (oRetorno.itens.length > 0) {

                with((window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordo) {

                    $('ac16_origem').disabled = true;
                    $('ac16_contratado').readOnly = true;
                    $('ac16_contratado').style.backgroundColor = 'rgb(222, 184, 135)';
                }

            } else {

                with((window.CurrentWindow || parent.CurrentWindow).corpo.iframe_acordo) {

                    $('ac16_origem').disabled = true;
                    $('ac16_contratado').readOnly = false;
                    $('ac16_contratado').className = "";
                }
            }

            var nTotal = 0;
            oRetorno.itens.each(function(oLinha, id) {

                with(oLinha) {
                    var sCor = '';
                    if (valortotal == totaldotacoes) {
                        var sCor = '';
                        if (aPeriodosItem.length == 0) {
                            var sCor = 'background-color: #fcd032;';
                        }
                    } else {
                        var sCor = 'background-color: red;';
                    }

                    var aLinha = new Array();
                    aLinha[0] = ordem;
                    aLinha[1] = codigomaterial;
                    aLinha[2] = material.urlDecode();
                    aLinha[3] = quantidade;
                    var iCasas = js_getNumeroCasasDecimais(valorunitario);
                    if (iCasas < 2) {
                        iCasas = 2;
                    }
                    aLinha[4] = js_formatar(valorunitario, 'f', iCasas);
                    aLinha[5] = js_formatar(valortotal, 'f');
                    aLinha[6] = elementocodigo + ' - ' + elementodescricao.urlDecode();
                    aLinha[7] = "<input type='button' value='Ver' id='Periodos' onclick='js_mostraPeriodos(" + codigo + ");'>";
                    aLinha[8] = "<input type='button' value='Dota��es' id='Dotacoes' onclick='js_adicionarDotacao(" + elementocodigo + "," + (ordem - 1) + "," + codigo + ");'>";
                    aLinha[9] = "<input type='button' style='width:50%' value='A' onclick='js_editar(" + codigo + ", " + oRetorno.iTipoContrato + ")'>";
                    if (oRetorno.iTipoContrato != 6) {
                        aLinha[9] += "<input type='button' style='width:50%' value='E' onclick='js_excluir(" + codigo + ")'>";
                    }

                    nTotal = nTotal + parseFloat(valortotal);
                    oGridItens.addRow(aLinha);
                    oGridItens.aRows[id].sStyle += ';padding:1px;' + sCor;
                }
            });

            oGridItens.renderRows();
            $('TotalForCol5').innerHTML = js_formatar(nTotal.toFixed(2), 'f');
        }
    }

    function js_saveItem() {

        var aPeriodo = new Array();
        var aGridPeriodosRows = oGridPeriodos.aRows;
        var iMaterial = $F('ac20_pcmater');
        var iAcordo = $F('ac20_acordo');
        var nValorUnitario = $F('ac20_valorunitario');
        var nQuantidade = $F('ac20_quantidade');
        var sResumo = $F('ac20_resumo');
        var sMarca = $F('ac20_marca');
        var iElemento = $F('ac20_elemento');
        var iUnidade = $F('ac20_matunid');
        var iServicoQuantidade = $F('ac20_servicoquantidade');
        var iTipoControle = 1;

        aGridPeriodosRows.each(function(oPeriodos, iLinha) {

            var oPeriodo = new Object;

            oPeriodo.dtDataInicial = oPeriodos.aCells[0].getValue();
            oPeriodo.dtDataFinal = oPeriodos.aCells[1].getValue();
            oPeriodo.ac41_sequencial = "";
            aPeriodo.push(oPeriodo);
        });

        var oParam = new Object();
        oParam.exec = "adicionarItem";

        if (iMaterial == '') {

            alert('Informe um material!');
            return false;
        }

        if (nQuantidade == '') {

            alert('Informe a quantidade!');
            return false;
        }

        if (iUnidade == 0) {

            alert('Selecione uma unidade!');
            return false;
        }

        if (nValorUnitario == '') {

            alert('Informe um valor unit�rio!!');
            return false;
        }

        if (iElemento == 0) {

            alert('Selecione um desdobramento!');
            return false;
        }

        oParam.material = new Object();
        oParam.material.iCodigo = '';
        if (iCodigoItem != "") {

            oParam.exec = "alterarItem";
            oParam.material.iCodigo = iCodigoItem;
        }

        oParam.material.iMaterial = iMaterial;
        oParam.material.iAcordo = iAcordo;
        oParam.material.iUnidade = iUnidade;
        oParam.material.iElemento = iElemento;
        oParam.material.nQuantidade = nQuantidade;
        oParam.material.sMarca = sMarca;
        oParam.material.nValorUnitario = nValorUnitario;
        oParam.material.sResumo = encodeURIComponent(tagString(sResumo));
        oParam.material.aPeriodo = aPeriodo;
        oParam.material.iTipoControle = iTipoControle;
        oParam.material.iServicoQuantidade = iServicoQuantidade;

        js_divCarregando('Aguarde, salvando itens', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoSaveItem
            }
        );
    }

    function js_retornoSaveItem(oAjax) {
        js_getItens();
        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.status == 1) {

            if (iCodigoItem != "") {

                if (oRetorno.lAlterarDotacao) {

                    sFunction = $('openDotacoes' + iCodigoItem).onclick;
                    sFunction();
                }
            }
            iCodigoItem = '';
            js_limparFormulario();
            js_getItens();
            js_desabilitaItemSelecionar();
        } else {
            alert(oRetorno.message.urlDecode());
        }

        oGridPeriodos.clearAll(true);
    }

    function js_excluir(iCodigo) {

        var sMsgConfirma = 'Confirma a exclus�o do Item?\nClique OK para Confirmar.';
        if (!confirm(sMsgConfirma)) {
            return false;
        }
        var oParam = new Object();
        oParam.exec = "excluirItem";
        oParam.material = new Object();
        oParam.material.iCodigo = iCodigo;
        js_divCarregando('Aguarde, excluindo itens', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoExcluirItem
            }
        );
    }

    function js_retornoExcluirItem(oAjax) {

        js_removeObj('msgBox');
        js_getItens();
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            alert('Item Excluido com sucesso!');
            js_desabilitaItemSelecionar();
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_getElementosMateriais(iValorDefault) {

        iValorElemento = '';
        if (iValorDefault != null) {
            iValorElemento = iValorDefault;
        }
        js_divCarregando('Aguarde, pesquisando elementos do material', 'msgBox');
        var oParam = new Object();
        oParam.iMaterial = $F('ac20_pcmater');
        oParam.exec = "getElementosMateriais";
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoGetElementosMaterias
            }
        );

    }

    function js_retornoGetElementosMaterias(oAjax) {

        js_removeObj('msgBox');
        $('ac20_elemento').options.length = 1;
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            oRetorno.itens.each(function(oItem, id) {

                var oOption = new Option(oItem.descricao.urlDecode(), oItem.codigoelemento);
                $('ac20_elemento').add(oOption, null);
            });
            if (oRetorno.itens.length > 0) {
                $('ac20_elemento').options[1].selected = true;
            }
            if (iValorElemento != '') {
                $('ac20_elemento').value = iValorElemento;
            }
            js_desabilitaItemSelecionar();
        }
    }

    function js_limparFormulario() {

        $('ac20_pcmater').value = '';
        $('pc01_descrmater').value = '';
        $('ac20_valorunitario').value = '';
        $('ac20_quantidade').value = '';
        $('ac20_elemento').options.length = 1;
        $('ac20_matunid').value = '';

        $('ac20_resumo').value = '';
        $('ac20_marca').value = '';
        $('db_opcao').value = 'Incluir';
        $('cancelar').style.display = 'none';
        $('ac20_servicoquantidade').value = 'f';

        // js_getItens();
        // js_desabilitaItemSelecionar();
        return false;
    }


    function js_bloqueiaCampos(iTipoContrato) {

        var CONTRATO_PROCESSO_COMPRAS = 1;
        var CONTRATO_LICITACAO = 2;
        var CONTRATO_MANUAL = 3;
        var CONTRATO_EMPENHO = 6;

        if (iTipoContrato == CONTRATO_EMPENHO) {
            $('itensEmpenho').style.display = '';
        }

        if (iTipoContrato != CONTRATO_PROCESSO_COMPRAS && iTipoContrato != CONTRATO_LICITACAO) {
            $('verificarItens').style.display = 'none';
        }

        if (iTipoContrato == CONTRATO_LICITACAO || iTipoContrato == CONTRATO_PROCESSO_COMPRAS || iTipoContrato == CONTRATO_EMPENHO) {

            $('cancelar').style.display = 'none';

            $('ac20_pcmater').readOnly = true;
            $('ac20_pcmater').style.background = '#DEB887';

            $('ac20_quantidade').readOnly = true;
            $('ac20_quantidade').style.background = '#DEB887';

            $('ac20_matunid').disabled = true;
            $('ac20_matunid').style.background = '#DEB887';

            $('ac20_valorunitario').readOnly = true;
            $('ac20_valorunitario').style.background = '#DEB887';

            $('ac20_servicoquantidade').disabled = true;
            $('ac20_servicoquantidade').style.background = '#DEB887';

            $('ac20_elemento').disabled = true;
            $('ac20_elemento').style.background = '#DEB887';

            $('db_opcao').style.display = '';

            $('tdMatMater').innerHTML = "<strong>C�digo do Item:</strong>";

        } else {

            $('cancelar').style.display = '';

            var sAncora = "<a class='dbancora' onclick='js_pesquisaac20_pcmater(true);' style='text-decoration: underline;' href='#'>";
            sAncora += "<strong>C�digo do Item:</strong>";
            sAncora += "</a>";
            $('tdMatMater').innerHTML = sAncora;
        }

    }

    function js_editar(iCodigo, iTipoContrato) {

        js_bloqueiaCampos(iTipoContrato);

        js_divCarregando('Aguarde, pesquisando material', 'msgBox');
        var oParam = new Object();
        oParam.iCodigoItem = iCodigo;
        oParam.exec = "getItensAcordo";

        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoEditar
            }
        );

    }

    function js_retornoEditar(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {
            with(oRetorno.item) {

                iCodigoItem = codigo;
                $('ac20_pcmater').value = codigomaterial;
                $('pc01_descrmater').value = material.urlDecode();
                $('ac20_valorunitario').value = valorunitario;
                $('ac20_quantidade').value = quantidade;
                $('ac20_elemento').value = elemento;
                $('ac20_matunid').value = unidade;
                $('ac20_marca').value = marca;
                $('ac20_resumo').value = resumo.urlDecode();
                $('ac20_tipocontrole').value = tipocontrole;
                $('ac20_servicoquantidade').value = servicoquantidade;
                isServico = servico ? 't' : 'f';

                $('db_opcao').value = 'Alterar';
                js_getElementosMateriais(elemento);
                js_desabilitaItemSelecionar();
                js_verificaServico();
            }
            aPeriodoItem = oRetorno.item.aPeriodosItem;
            oGridPeriodos.clearAll(true);
            getPeriodos();
        }
    }

    function js_adicionarDotacao(iElemento, iLinha, iItem) {
        oDadosItem = oGridItens.aRows[iLinha];
        var iHeight = js_round((window.innerHeight / 1.3), 0);
        var iWidth = document.width / 2;
        windowDotacaoItem = new windowAux(
            'wndDotacoesItem',
            'Dota��es Item ',
            400,
            400
        );
        var sContent = "<div id='addDotacao'>";
        sContent += "<fieldset><legend><b>Adicionar Dota��o</b>";
        sContent += "  <table>";
        sContent += "   <tr>";
        sContent += "     <td>";
        sContent += "     <a href='#' class='dbancora' style='text-decoration: underline;'";
        sContent += "       onclick='js_pesquisao47_coddot(true);'><b>Dota��o:</b></a>";
        sContent += "     </td>";
        sContent += "     <td id='inputdotacao'></td>";
        sContent += "     <td>";
        sContent += "      <b>Saldo Dota��o:</b>";
        sContent += "     </td>";
        sContent += "     <td id='inputsaldodotacao'></td>";
        sContent += "   </tr>";
        sContent += "   <tr>";
        sContent += "     <td>";
        sContent += "      <b>Quantidade:</b>";
        sContent += "     </td>";
        sContent += "     <td id='inputquantidadedotacao'></td>";
        sContent += "     <td>";
        sContent += "      <b>Valor:</b>";
        sContent += "     </td>";
        sContent += "     <td id='inputvalordotacao'></td>";
        sContent += "    </tr>";
        sContent += "    <tr>";
        sContent += "     <td colspan='4' style='text-align:center'>";
        sContent += "       <input type='button' value='Salvar' id='btnSalvarDotacao' " + ((isExecutado[iItem] == true || desabilitaDotacao == false) ? "disabled='disabled'" : "") + ">";
        sContent += "     </td>";
        sContent += "    </tr>";
        sContent += "  </table>";
        sContent += "</fieldset>";
        sContent += "<fieldset>";
        sContent += "  <div id='cntgridDotacoes'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "</div>";
        windowDotacaoItem.setContent(sContent);
        windowDotacaoItem.setShutDownFunction(function() {
            /*var nTemp1 = js_strToFloat(oDadosItem.aCells[4].getValue());
            var nTemp2 = oGridDotacoes.sum(2, false);

            if ($('dotacoes' + oDadosItem.aCells[0].getValue())) {
                if (nTemp1 == nTemp2) {
                    $('dotacoes' + oDadosItem.aCells[0].getValue()).style.color = 'green';
                } else {
                    $('dotacoes' + oDadosItem.aCells[0].getValue()).style.color = 'red';
                }
              }*/
            windowDotacaoItem.destroy();

        });
        windowDotacaoItem.show(25, 420);
        oMessageBoard = new DBMessageBoard('msgboard1',
            'Adicionar Dotacoes',
            'Dota��es Item ' + oDadosItem.aCells[1].getValue(),
            windowDotacaoItem.getContentContainer()
        );

        oMessageBoard.show();

        $('btnSalvarDotacao').observe("click", js_saveDotacao);
        oTxtDotacao = new DBTextField('oTxtDotacao', 'oTxtDotacao', '', 10);
        oTxtDotacao.show($('inputdotacao'));
        oTxtDotacao.setReadOnly(true);

        oTxtValorDotacao = new DBTextField('oTxtValorDotacao', 'oTxtValorDotacao', '', 10);
        oTxtValorDotacao.addEvent("onKeyPress", "return js_mask(event,\"0-9|,\")");
        oTxtValorDotacao.show($('inputvalordotacao'));
        oTxtValorDotacao.setReadOnly(true);

        oTxtQuantidadeDotacao = new DBTextField('oTxtQuantidadeDotacao', 'oTxtQuantidadeDotacao', '', 10);
        var nValorMaximo = oDadosItem.aCells[3].getValue();
        var nValorUnitario = js_strToFloat(oDadosItem.aCells[4].getValue()).valueOf();
        var sEvent = ";js_validaValorDotacao(this," + nValorMaximo + "," + nValorUnitario + ",\"oTxtValorDotacao\");";
        oTxtQuantidadeDotacao.addEvent("onChange", sEvent);
        oTxtQuantidadeDotacao.addEvent("onKeyPress", "return js_mask(event,\"0-9|,\")");
        oTxtQuantidadeDotacao.show($('inputquantidadedotacao'));

        oTxtSaldoDotacao = new DBTextField('oTxtSaldoDotacao', 'oTxtSaldoDotacao', '', 10);
        oTxtSaldoDotacao.show($('inputsaldodotacao'));
        oTxtSaldoDotacao.setReadOnly(true);

        oGridDotacoes = new DBGrid('gridDotacoes');
        oGridDotacoes.nameInstance = 'oGridDotacoes';
        oGridDotacoes.setCellAlign(new Array("center", "right", "right", "Center"));
        oGridDotacoes.setCellWidth(new Array("28%", "28%", "28%", "10%"));
        oGridDotacoes.setHeader(new Array("Dota��o", "Qtd", "Valor", "A��o"));
        oGridDotacoes.setHeight(iHeight / 3);
        oGridDotacoes.show($('cntgridDotacoes'));

        js_getDotacoesItens(iItem);
    }

    function getSaldoDotacao(iDotacao) {

        var oParam = new Object();
        oParam.exec = "getSaldoDotacao";
        oParam.iDotacao = iDotacao;
        js_divCarregando('Aguarde, pesquisando saldo Dota��es', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoGetSaldotacao
            }
        );

    }

    function js_retornoGetSaldotacao(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        oTxtSaldoDotacao.setValue(js_formatar(oRetorno.saldofinal, "f"));
        js_desabilitaItemSelecionar();
    }

    function js_getDotacoesItens(iItem) {

        var oParam = new Object();
        oParam.exec = "getDotacoesItens";
        oParam.iCodigoItem = iItem;
        js_divCarregando('Aguarde, pesquisando Dota��es', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: (function(ret) {
                    js_retornoGetDotacoes(ret, iItem);
                })
            }
        );

    }

    function js_retornoGetDotacoes(oAjax, iItem) {

        var oRetorno = eval("(" + oAjax.responseText + ")");
        js_removeObj('msgBox');
        js_preencheDotacoes(oRetorno.dotacoes, iItem);
        if (oRetorno.servicoquantidade == 'f' && oRetorno.servico) {
            oTxtValorDotacao.setReadOnly(false);
            oTxtQuantidadeDotacao.setReadOnly(true);
            oTxtQuantidadeDotacao.setValue(1);
        } else if (oRetorno.servicoquantidade == 't' && oRetorno.servico) {
            oTxtValorDotacao.setReadOnly(true);
        } else {
            oTxtValorDotacao.setReadOnly(true);
        }
        windowDotacaoItem.show();
        iElementoDotacao = oRetorno.iElementoDotacao;

    }

    function js_preencheDotacoes(oDados, iItem) {

        oGridDotacoes.clearAll(true);
        oDados.each(function(oRow, iSeq) {
            console.log(oRow);
            var aLinha = new Array();
            aLinha[0] = oRow.dotacao;
            aLinha[1] = oRow.quantidade;
            if (oRow.executado != "") {
                isExecutado[iItem] = true;
            }
            aLinha[2] = js_formatar(oRow.valor, 'f');
            aLinha[3] = "<input type='button' value='E' onclick='js_excluirDotacao(" + oRow.dotacao + ")' " + (isExecutado[iItem] == true || desabilitaDotacao == false ? "disabled='disabled'" : "") + " style='width:100%'>";
            oGridDotacoes.addRow(aLinha);
        });
        oGridDotacoes.renderRows();
    }

    function js_validaValorDotacao(obj, iQuantMax, nValUnitario, oValorTotal) {

        if (js_strToFloat(obj.value) > iQuantMax) {
            obj.value = iQuantMax;
        } else if (obj.value == 0) {
            obj.value = iQuantMax;
        }

        var nValorTotal = js_strToFloat(obj.value) * nValUnitario;
        $(oValorTotal).value = js_formatar(nValorTotal, 'f');
    }

    function js_saveDotacao() {

        if (isExecutado[oDadosItem.aCells[1].getValue()] == true) {
            alert("N�o � possivel adicionar uma dota��o a um �tem j� executado.");
            return false;
        }

        if (oTxtDotacao.getValue() == "") {

            alert('Informe a dota��o!');
            js_pesquisao47_coddot(true);
            return false;

        }
        if (js_strToFloat(oTxtQuantidadeDotacao.getValue()) == 0) {

            alert('Informe uma quantidade para o item!');
            $('oTxtQuantidadeDotacao').focus();
            return false;
        }
        if (((js_round(oGridDotacoes.sum(1, false), 2) + js_strToFloat(oTxtQuantidadeDotacao.getValue())) > oDadosItem.aCells[3].getValue()) && oTxtQuantidadeDotacao.lReadOnly == false) {
            alert('Quantidade Dota��o maior que quantidade do item!');
            $('oTxtQuantidadeDotacao').focus();
            return false;
        }
        if (js_round((js_round(oGridDotacoes.sum(2, false), 2) + js_strToFloat(oTxtValorDotacao.getValue())), 2) > js_strToFloat(oDadosItem.aCells[5].getValue())) {
            alert('Valor Dota��o maior que Valor Total do item!');
            $('oTxtValorDotacao').focus();
            return false;
        }
        for (var i = 0; i < oGridDotacoes.aRows.length; i++) {
            if (oGridDotacoes.aRows[i].aCells[0].getValue() == oTxtDotacao.getValue()) {
                alert('Dota��o j� existe para este item!');
                return false;
            }
        }
        var oParam = new Object();
        oParam.exec = "saveDotacaoItens";
        oParam.iCodigoItem = oDadosItem.aCells[1].getValue();
        oParam.iDotacao = oTxtDotacao.getValue();
        oParam.nQuantidade = oTxtQuantidadeDotacao.getValue();
        oParam.nValor = js_strToFloat(oTxtValorDotacao.getValue()).valueOf();
        js_divCarregando('Aguarde, salvando Dota��es', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoSaveDotacoes
            }
        );
    }

    function js_retornoSaveDotacoes(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            oTxtValorDotacao.setValue('');
            oTxtQuantidadeDotacao.setValue('');
            oTxtDotacao.setValue('');
            oTxtSaldoDotacao.setValue('');
            if (oTxtQuantidadeDotacao.lReadOnly == true) {
                oTxtQuantidadeDotacao.setReadOnly(true);
                oTxtQuantidadeDotacao.setValue(1);
            }

            js_getItens();
            js_preencheDotacoes(oRetorno.dotacoes);
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_excluirDotacao(iDotacao) {

        var oParam = new Object();
        oParam.exec = "excluirDotacaoItens";
        oParam.iDotacao = iDotacao;
        oParam.iCodigoItem = oDadosItem.aCells[1].getValue();
        js_divCarregando('Aguarde, excluindo Dota��es', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_excluirSaveDotacoes
            }
        );
    }

    function js_excluirSaveDotacoes(oAjax) {
        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {
            js_getItens();
            js_preencheDotacoes(oRetorno.dotacoes);
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_montaGridItensOrigens() {

        oGridItensOrigem = new DBGrid('gridItensOrigem');
        oGridItensOrigem.nameInstance = 'oGridItensOrigem';
        oGridItensOrigem.setCheckbox(0);

        oGridItensOrigem.setCellAlign(new Array(
            "right",
            "right",
            "right",
            "left",
            'right',
            'right',
            'right',
            "left",
            "center",
            "center",
            "center",
            "center"
        ));

        oGridItensOrigem.setCellWidth(new Array(
            "1%",
            '1%',
            "7%",
            "50%",
            '15%',
            '10%',
            '10%',
            "10%",
            "1%",
            "1%",
            "1%",
            "1%",
            "1%"
        ));

        oGridItensOrigem.setHeader(new Array(
            "C�digo",
            '',
            "Cod.Mater",
            "Material",
            "Quantidade",
            "Vlr Un",
            "Total",
            "Tipo",
            "",
            "Previs�o Inicial",
            "Previs�o Final",
            "Servi�o",
            "Elemento"
        ));

        oGridItensOrigem.aHeaders[1].lDisplayed = false;
        oGridItensOrigem.aHeaders[2].lDisplayed = false;
        oGridItensOrigem.aHeaders[7].lDisplayed = false;
        oGridItensOrigem.aHeaders[9].lDisplayed = false;
        oGridItensOrigem.aHeaders[10].lDisplayed = false;
        oGridItensOrigem.aHeaders[11].lDisplayed = false;
        oGridItensOrigem.aHeaders[12].lDisplayed = false;
        oGridItensOrigem.aHeaders[13].lDisplayed = false;
        oGridItensOrigem.show($('ctngridItensOrigem'));

        /**
         * Verifica o 'click' do botao Replicar. Percorre as linhas da Grid, verificando quais estao selecionadas e quais
         * campos devem ser replicados para estas linhas selecionadas, substituindo os valores
         */
        $('btnReplicar').observe("click", function() {

            var iLinhasSelecionadas = 0;
            oGridItensOrigem.aRows.each(function(oLinha, iSeq) {

                if (oLinha.isSelected == true) {

                    if (oTxtPrevisaoInicial.getValue() != '') {

                        var aItens = oGridItensOrigem.getElementsByClass('classDataEmissaoInicial');

                        for (var i = 0; i < aItens.length; i++) {
                            if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {
                                aItens[i].value = oTxtPrevisaoInicial.getValue();
                            }
                        }
                    }

                    if (oTxtPrevisaoFinal.getValue() != '') {

                        var aItens = oGridItensOrigem.getElementsByClass('classDataEmissaoFinal');

                        for (var i = 0; i < aItens.length; i++) {
                            if (aItens[i].parentNode.parentNode.childNodes[0].childNodes[0].checked == true) {
                                aItens[i].value = oTxtPrevisaoFinal.getValue();
                            }
                        }
                    }

                    iLinhasSelecionadas++;
                }
            });

            if (iLinhasSelecionadas == 0) {

                alert('Nenhuma linha foi selecionada.');
                return false;
            }
        });
    }


    function js_montaWindowItensOrigens() {

        var iHeight = js_round((window.innerHeight / 1.3), 0);
        var iWidth = document.width - 60;

        if (document.getElementById('wndItensOrigem') != null) {

            windowItensOrigem.destroy();
        }

        windowItensOrigem = new windowAux('wndItensOrigem',
            'Selecionar Itens para Contrato',
            900,
            iHeight
        );

        var sContent = "<div>";
        sContent += "<fieldset style='display:none;'><legend><b>Replicar Informa��es</b></legend>";
        sContent += "  <div id='ctnReplicaInformacoes'>";
        sContent += "    <table>";
        sContent += "      <tr>";
        sContent += "        <td><label><b>Previs�o Inicial:<b></label></td>";
        sContent += "        <td id='ctnPrevisaoInicial'>";
        sContent += "        </td>";
        sContent += "      </tr>";
        sContent += "      <tr>";
        sContent += "        <td><label><b>Previs�o Final:<b></label></td>";
        sContent += "        <td id='ctnPrevisaoFinal'>";
        sContent += "        </td>";
        sContent += "      </tr>";
        sContent += "    </table>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "<center>";
        sContent += "  <input style='display:none;' type='button' value='Replicar' id='btnReplicar'>";
        sContent += "</center>";
        sContent += "<fieldset><legend><b>Escolha os Itens</b></legend>";
        sContent += "  <div id='ctngridItensOrigem'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "</div>";
        sContent += "<center>";
        sContent += "  <input type='button' value='Salvar' id='btnVincularItens'>";
        sContent += "</center>";
        windowItensOrigem.setContent(sContent);
        windowItensOrigem.allowCloseWithEsc(false);

        var sAjuda = 'Selecione abaixo os itens que ir�o fazer parte do contrato. Caso n�o seja exibido nenhum item, selecione na aba <b>Acordo</b> o empenho, processo de compra ou licita��o que possui os itens.';
        oMessageBoard = new DBMessageBoard('msgboardItensOrigem',
            'Escolha os Itens que far�o parte do contrato',
            sAjuda,
            $('windowwndItensOrigem_content')
        );

        windowItensOrigem.setShutDownFunction(function() {
            windowItensOrigem.destroy();
        });

        $('btnVincularItens').onclick = js_vincularItens;

        oTxtPrevisaoInicial = new DBTextFieldData('txtPrevisaoInicial', 'oTxtPrevisaoInicial', null);
        oTxtPrevisaoInicial.show($('ctnPrevisaoInicial'));

        oTxtPrevisaoFinal = new DBTextFieldData('txtPrevisaoFinal', 'oTxtPrevisaoFinal', null);
        oTxtPrevisaoFinal.show($('ctnPrevisaoFinal'));

        windowItensOrigem.show();
        js_montaGridItensOrigens();
    }

    function js_getItensOrigem() {

        js_montaWindowItensOrigens();

        var oParam = new Object();
        oParam.exec = "getItensOrigem";
        js_divCarregando('Aguarde, pesquisando itens do contratante', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_getRetornoGetItens
            }
        );
    }

    function js_getRetornoGetItens(oAjax) {

        js_removeObj('msgBox');

        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {
            js_preencheItensOrigem(oRetorno);
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    oTxtDataEmissao = new DBTextFieldData('oTxtDataEmissao', 'oTxtDataEmissao', null);

    function js_preencheItensOrigem(oRetorno) {

        oGridItensOrigem.clearAll(true);
        var iTotalLinhas = 0;
        $('btnVincularItens').disabled = false;

        var aCodigos = new Array();
        oRetorno.itens.each(function(oRow, iSeq) {

            var aLinha = new Array();

            aCodigos.push(oRow.codigo);
            oTxtDataEmissaoInicial = new DBTextFieldData('oTxtDataEmissaoInicial' + oRow.codigo, 'oTxtDataEmissao', null);
            oTxtDataEmissaoFinal = new DBTextFieldData('oTxtDataEmissaoFinal' + oRow.codigo, 'oTxtDataEmissao', null);

            aLinha[0] = oRow.codigo;
            aLinha[1] = iTotalLinhas;
            aLinha[2] = oRow.codigomaterial;
            aLinha[3] = oRow.material.urlDecode();
            aLinha[4] = oRow.quantidade;
            aLinha[5] = js_formatar(oRow.valorunitario, 'f');
            aLinha[6] = js_formatar(oRow.valortotal, 'f');
            aLinha[7] = oRow.servico == 't' ? 'Servi�o' : 'Material';
            aLinha[8] = "";
            aLinha[9] = oTxtDataEmissaoInicial.toInnerHtml();
            aLinha[10] = oTxtDataEmissaoFinal.toInnerHtml();
            aLinha[11] = oRow.servico;
            aLinha[12] = oRow.elemento;

            var lMarcado = false;
            var lDisabled = false;
            var nValorTotalItem = new Number(oRow.quantidade) * new Number(oRow.valorunitario);
            var nValorTotalFracionado = 0;

            oGridItensOrigem.addRow(aLinha, false, lDisabled, lMarcado);

            iLinhaAtual = iTotalLinhas;
            iTotalLinhas++;

        });
        oGridItensOrigem.renderRows();

        /**
         * Percorremos o array com os codigos para criar um className padr�o para todos inputs existentes na Grid
         */
        aCodigos.each(function(oLinha, iSeq) {

            $('oTxtDataEmissaoInicial' + oLinha).setValue("");
            $('oTxtDataEmissaoInicial' + oLinha).className = 'classDataEmissaoInicial';

            $('oTxtDataEmissaoFinal' + oLinha).setValue("");
            $('oTxtDataEmissaoFinal' + oLinha).className = 'classDataEmissaoFinal';
        });
    }

    function js_vincularItens() {

        var aItens = oGridItensOrigem.getSelection("object");
        if (aItens.length == 0) {

            alert('Selecione um Item!');
            return false;
        }

        var aListaCheckbox = oGridItensOrigem.getSelection();
        var aListaItens = new Array();
        var oDadosItens;
        var lErro = false;

        aListaCheckbox.each(function(aRow) {

            oDadosItens = new Object();
            oDadosItens.codigo = aRow[0];
            oDadosItens.codigomaterial = aRow[3];
            oDadosItens.quantidade = aRow[5];
            oDadosItens.valorunitario = aRow[6];
            oDadosItens.valortotal = aRow[7];
            oDadosItens.iFormaControle = 1;
            oDadosItens.dtInicial = aRow[10];
            oDadosItens.dtFinal = aRow[11];
            oDadosItens.servico = aRow[12];
            oDadosItens.elemento = aRow[13];

            /*      if (oDadosItens.dtInicial == null || oDadosItens.dtInicial == '') {

                    alert('Preencha as datas iniciais do per�odo de execu��o.');
                    lErro = true;
                    throw $break;
                  }

                  if (oDadosItens.dtFinal == null || oDadosItens.dtFinal == '') {

                    alert('Preencha as datas finais do per�odo de execu��o.');
                    lErro = true;
                    throw $break;
                  }*/

            aListaItens.push(oDadosItens);
        });

        if (lErro) {
            return false;
        }

        var oParam = new Object();
        oParam.exec = "adicionarItensOrigem";
        oParam.itens = new Array();
        oParam.aLista = aListaItens;

        aItens.each(function(oRow, id) {
            oParam.itens.push(oRow.aCells[0].getValue());
        });

        js_divCarregando('Aguarde, Vinculando itens selecionados..', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_getRetornoVincularItens
            }
        );
    }

    function js_getRetornoVincularItens(oAjax) {

        js_removeObj('msgBox');
        js_getItens();
        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            windowItensOrigem.destroy();

        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_fracionarItensOrigem(iRow) {

        var oRow = oGridItensOrigem.aRows[iRow];
        var iHeight = js_round((window.innerHeight / 1.5), 0);
        var iWidth = document.width / 2;
        windowItensFraciona = new windowAux('windowItensFraciona',
            'Fracionar Itens',
            iWidth,
            iHeight
        );
        var sContent = "<center><table><tr><td>";
        sContent += "<fieldset><legend><b>Fracionar Item</b></legend>";
        sContent += "<table>";
        sContent += "  <tr>";
        sContent += "   <td>";
        sContent += "      <b><a onclick='js_pesquisaMaterial(true);return false' href='#'>Material:</a></b>";
        sContent += "   </td>";
        sContent += "   <td id='cntTxtIdMaterial'>";
        sContent += "   </td>";
        sContent += "   <td id='cntTxtDescrMaterial'>";
        sContent += "   </td>";
        sContent += "  </tr>";
        sContent += "  <tr>";
        sContent += "   <td>";
        sContent += "      <b>Quantidade:</b>";
        sContent += "   </td>";
        sContent += "   <td id='cntTxtQuantidade'>";
        sContent += "   </td>";
        sContent += "  </tr>";
        sContent += "  <tr>";
        sContent += "   <td>";
        sContent += "      <b>Valor Unit�rio:</b>";
        sContent += "   </td>";
        sContent += "   <td id='cntTxtValorUnitario'>";
        sContent += "   </td>";
        sContent += "  </tr>";
        sContent += "  <tr>";
        sContent += "   <td>";
        sContent += "      <b>Valor Total:</b>";
        sContent += "   </td>";
        sContent += "   <td id='cntTxtValor'>";
        sContent += "   </td>";
        sContent += "  </tr>";
        sContent += "  <tr>";
        sContent += "   <td>";
        sContent += "      <b>Unidade:</b>";
        sContent += "   </td>";
        sContent += "   <td id='cntCboUnidade' colspan=2>";
        sContent += "   </td>";
        sContent += "  </tr>";
        sContent += "  <tr>";
        sContent += "    <td colspan='4'>";
        sContent += "    <fieldset><legend><b>Observa��o</b></legend>";
        sContent += "     <textarea id='oMemoObservacao' rows='3' style='width:100%'></textarea>";
        sContent += "    </fieldset>";
        sContent += "    </td>";
        sContent += "  </tr>";
        sContent += "</table>";
        sContent += "</fieldset>";
        sContent += "</td></tr></table>";
        sContent += "  <input type='button' value='Salvar' id='btnSalvarItens'>";
        sContent += "</center>";
        windowItensFraciona.setContent(sContent);
        windowItensFraciona.allowCloseWithEsc(false);
        oTxtMaterial = new DBTextField('oTxtMaterial', 'oTxtMaterial', '', 10);
        oTxtMaterial.show($('cntTxtIdMaterial'));


        $('btnSalvarItens').stopObserving("click");
        $('btnSalvarItens').observe("click", function() {

            js_fracionarItem(iRow);
        });
        oTxtDescrMaterial = new DBTextField('oTxtDescrMaterial', 'oTxtDescrMaterial', '', 40);
        oTxtDescrMaterial.show($('cntTxtDescrMaterial'));


        oTxtQuantidade = new DBTextField('oTxtQuantidade', 'oTxtQuantidade', oRow.aCells[5].getValue(), 10);
        oTxtQuantidade.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
        oTxtQuantidade.addEvent("onBlur", "js_validaValorUnitario()");
        oTxtQuantidade.show($('cntTxtQuantidade'));

        var nValorUnitario = js_strToFloat(oRow.aCells[6].getValue());
        oTxtValorUnitario = new DBTextField('oTxtValorUnitario', 'oTxtValorUnitario', nValorUnitario, 10);
        oTxtValorUnitario.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
        oTxtValorUnitario.addEvent("onBlur", "js_validaValorTotal()");
        oTxtValorUnitario.show($('cntTxtValorUnitario'));

        oTxtValor = new DBTextField('oTxtValor', 'oTxtValor', js_strToFloat(oRow.aCells[7].getValue()), 10);
        oTxtValor.addEvent("onKeyPress", "return js_mask(event,\"0-9|.\")");
        oTxtValor.addEvent("onBlur", "js_validaValor(this," + js_strToFloat(oRow.aCells[7].getValue()) + ")");
        oTxtValor.setReadOnly(true);
        oTxtValor.show($('cntTxtValor'));

        oCboUnidade = new DBComboBox('oCboUnidade', 'oCboUnidade');
        oCboUnidade.show($('cntCboUnidade'));

        var aUnidades = $('ac20_matunid').options;
        for (var i = 0; i < aUnidades.length; i++) {
            oCboUnidade.addItem(aUnidades[i].value, aUnidades[i].innerHTML);
        };
        windowItensFraciona.setShutDownFunction(function() {
            windowItensFraciona.destroy();
        });
        windowItensFraciona.setChildOf(windowItensOrigem);
        oMessageBoardFraciona = new DBMessageBoard('msgboardItensFraciona',
            'Informe os dados do Item',
            'Informe o Material, e as novas quantidades para o item ' + oRow.aCells[4].getValue(),
            $('windowwindowItensFraciona_content')
        );
        windowItensFraciona.show();
        oAutoComplete = new dbAutoComplete($('oTxtDescrMaterial'), 'com4_pesquisamateriais.RPC.php');
        oAutoComplete.setTxtFieldId(document.getElementById('oTxtMaterial'));
        oAutoComplete.show();
    }

    function js_fracionarItem(iRow) {

        oRow = oGridItensOrigem.aRows[iRow];
        var nQuantidade = oTxtQuantidade.getValue();
        var nValor = oTxtValor.getValue();
        var nValorUnit = oTxtValorUnitario.getValue();
        var iItem = oTxtMaterial.getValue();
        var iUnidade = oCboUnidade.getValue();
        var sObservacao = $F('oMemoObservacao');
        if (new Number(nQuantidade) == 0) {
            alert('Informe uma quantidade valida!');
            return false;
        }
        if (new Number(nValor) == 0) {

            alert('Informe um valor v�lido!');
            return false;
        }
        if (iItem == "") {

            alert('Indique um item para a inclus�o!');
            return false;
        }
        if (iUnidade == "") {

            alert('Informe a unidade do material!');
            return false;
        }

        var oParam = new Object();
        oParam.exec = 'fracionarItemOrigem';

        oParam.iItemOriginal = oRow.aCells[1].getValue();
        oParam.fracionamento = new Object();
        oParam.fracionamento.iItem = iItem;
        oParam.fracionamento.nValor = nValor;
        oParam.fracionamento.nQuantidade = nQuantidade;
        oParam.fracionamento.nValorUnit = nValorUnit;
        oParam.fracionamento.iUnidade = iUnidade;
        oParam.fracionamento.sObservacao = sObservacao;
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoFracionarItens
            }
        );
    }

    function js_retornoFracionarItens(oAjax) {

        var oRetorno = eval("(" + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            if (typeof(windowItensFraciona) != "undefined") {

                windowItensFraciona.destroy();
                delete windowItensFraciona;
            }
            js_preencheItensOrigem(oRetorno.itens);

        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_validaValor(Obj, valorMaximo) {

        if (Obj.value == "" || Obj.value < 0) {
            Obj.value = valorMaximo;
        } else if (Obj.value > valorMaximo) {
            Obj.value = valorMaximo;
        }

    }

    function js_validaValorUnitario() {

        if (oTxtQuantidade.getValue() != oRow.aCells[5].getValue()) {

            var nValorUnitario = oTxtValor.getValue() / oTxtQuantidade.getValue();
            oTxtValorUnitario.setValue(nValorUnitario.toFixed(iCasasDecimais));
            js_validaValorTotal();
        }
    }

    function js_validaValorTotal() {

        var nValorTotal = oTxtValorUnitario.getValue() * oTxtQuantidade.getValue();
        oTxtValor.setValue(nValorTotal.toFixed(2));
    }

    function js_excluirFracionamento(iItem, iFracionamento) {

        var oParam = new Object();
        oParam.exec = 'excluirFracionamento';

        oParam.iItem = iItem;
        oParam.iFracionamento = iFracionamento;
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoFracionarItens
            }
        );
    }

    function js_getNumeroCasasDecimais(nValor) {

        var iNumeroCasasDecimais = 0;
        var sString = new String(nValor);
        if (sString.indexOf('.') > 0) {

            var sParteDecimal = sString.split('\.');
            iNumeroCasasDecimais = sParteDecimal[1].length;
        }
        return iNumeroCasasDecimais;
    }

    function js_desabilitaItemSelecionar() {

        var iMaterialUnidade = $('ac20_matunid').value;
        if (iMaterialUnidade != 0) {
            $('ac20_matunid').options[0].disabled = true;
        }

        var iMaterialDesdobramento = $('ac20_elemento').value;
        if (iMaterialDesdobramento != 0) {
            $('ac20_elemento').options[0].disabled = true;
        }
    }

    /**
     * Cria uma grid dos Periodos do Item
     */
    oGridPeriodos = new DBGrid('gridPeriodos');
    oGridPeriodos.nameInstance = 'oGridPeriodos';
    oGridPeriodos.setCellAlign(new Array("center", "center", "center"));
    oGridPeriodos.setCellWidth(new Array("35%", "35%", "30%"));
    oGridPeriodos.setHeader(new Array("Data Inicial", "Data Final", "A��o"));
    oGridPeriodos.setHeight(70);
    oGridPeriodos.show($('gridPeriodos'));

    var aPeriodoItem = new Array();

    /**
     * Renderiza os Periodos
     */
    function showPeriodos() {

        oGridPeriodos.clearAll(true);

        if (aPeriodoItem.length > 0) {
            getPeriodos();
        }
    }

    /**
     * Itera sobre o array de periodos e add as linhas na grid
     */
    function getPeriodos() {

        oGridPeriodos.clearAll(true);
        aPeriodoItem.each(function(oPeriodos, iLinha) {

            var aRow = new Array();
            aRow[0] = oPeriodos.dtDataInicial;
            aRow[1] = oPeriodos.dtDataFinal;
            aRow[2] = "<input type='button' value='Excluir' onclick='js_excluiPeriodo(" + iLinha + ");'>";
            oGridPeriodos.addRow(aRow);
        });
        oGridPeriodos.renderRows();

    }

    /**
     * Adiciona um objeto de periodo no array de periodos
     */
    function addPeriodo() {
        if (document.form1.db_opcao.value == "Alterar") {
            return true;
        }
        var oPeriodo = new Object();
        var oDtInicial = '<?php echo date("d/m/Y", strtotime($oAcordo->ac16_datainicio)); ?>'; //$("ac41_datainicial");
        var oDtFinal = '<?php echo date("d/m/Y", strtotime($oAcordo->ac16_datafim)); ?>'; //$("ac41_datafinal");

        if (oDtInicial == "" || oDtFinal == "") {

            alert("Ambos os Periodos devem serem preenchidos.");
            return false;
        }

        /**
         * Valida se as datas informadas n�o conflitam com a data de vig�ncia do contrato
         */
        var dtContratoInicio = parent.iframe_acordo.document.form1.ac16_datainicio1.value;
        var dtContratoFim = parent.iframe_acordo.document.form1.ac16_datafim1.value;

        if (js_comparadata(oDtInicial, dtContratoInicio, "<") || js_comparadata(oDtFinal, dtContratoFim, ">") ||
            js_comparadata(oDtInicial, dtContratoFim, ">") || js_comparadata(oDtFinal, dtContratoInicio, "<")) {
            //alert("H� conflito entre as datas do item e a data de vig�ncia do contrato.");
            return false;
        }

        if (!js_validaData(oDtInicial, oDtFinal)) {
            //alert("Nao sei");
            return false;
        }
        oPeriodo.dtDataInicial = oDtInicial;
        oPeriodo.dtDataFinal = oDtFinal;
        aPeriodoItem.push(oPeriodo);

        oDtInicial = "";
        oDtFinal = "";

        showPeriodos();
    }

    /**
     * Valida conflitos nos periodos digitados
     */
    function js_validaData(sDtInicial, sDtFinal) {

        var lErro = false;
        if (js_comparadata(sDtInicial, sDtFinal, ">")) {

            alert("Data inicial deve ser menor ou igual a data final.");
            lErro = true;
        }

        if (aPeriodoItem.length > 0 && !lErro) {

            for (var i = 0; i < aPeriodoItem.length; i++) {

                if (js_comparaPeriodo(sDtInicial, sDtFinal, aPeriodoItem[i].dtDataInicial, aPeriodoItem[i].dtDataFinal)) {

                    /* var sMsgErro = "H� conflito entre os per�odos informados. Confira:\n";
                     sMsgErro += sDtInicial + " - " + sDtFinal + "\n";
                     sMsgErro += aPeriodoItem[i].dtDataInicial + " - " + aPeriodoItem[i].dtDataFinal;
                     alert(sMsgErro);
                     lErro = true;
                     break;*/
                }
            }
        }

        if (lErro) {
            return false;
        }
        return true;
    }

    /**
     * Exclui do array de periodos um periodo especifico
     */
    function js_excluiPeriodo(iLinha) {

        aPeriodoItem.splice(iLinha, 1);
        getPeriodos();
    }

    function js_mostraPeriodos(iCodigoItem) {

        var oParam = new Object();
        oParam.exec = "buscaPeriodosItem";
        oParam.iCodigoItem = iCodigoItem;
        js_divCarregando('Aguarde, pesquisando Per�odos do Item', 'msgBox');
        var oAjax = new Ajax.Request(
            sURL, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: js_retornoPeriodoItem
            }
        );
    }

    function js_retornoPeriodoItem(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("(" + oAjax.responseText + ")");

        /**
         * Construir Windown AUX
         */
        var windowPeriodos = new windowAux('janelaPeriodo', 'Per�odo do Item ', 500, 350);
        windowPeriodos.setContent("<div id='periodosItem'></div>");
        windowPeriodos.setShutDownFunction(function() {
            windowPeriodos.destroy();
        });
        windowPeriodos.show();

        var oMessageBoard = new DBMessageBoard('msgboard1',
            'Per�odos do Item ' + oRetorno.nomeItem.urlDecode(),
            'Periodos cadastrados para o item.',
            windowPeriodos.getContentContainer());
        oMessageBoard.show();

        /**
         * View da
         */
        oGridViewPeriodos = new DBGrid('gridViewPeriodos');
        oGridViewPeriodos.nameInstance = 'oGridViewPeriodos';
        oGridViewPeriodos.setCellAlign(new Array("center", "center"));
        oGridViewPeriodos.setCellWidth(new Array("45%", "45%"));
        oGridViewPeriodos.setHeader(new Array("Data Inicial", "Data Final"));
        oGridViewPeriodos.show($('periodosItem'));

        oGridPeriodos.clearAll(true);

        oRetorno.periodos.each(function(oPeriodos, iLinha) {

            /**
             * Split para configurar as datas no padr�o brasileiro
             */
            var aPeriodoInicial = oPeriodos.dtDataInicial.split("-");
            var aPeriodoFinal = oPeriodos.dtDataFinal.split("-");

            var aRow = new Array();
            aRow[0] = aPeriodoInicial[2] + "/" + aPeriodoInicial[1] + "/" + aPeriodoInicial[0];
            aRow[1] = aPeriodoFinal[2] + "/" + aPeriodoFinal[1] + "/" + aPeriodoFinal[0];
            oGridViewPeriodos.addRow(aRow);
        });
        oGridViewPeriodos.renderRows();

    }

    function js_windowItensEmpenho() {

        var sTituloWindowAux = "Configura��o de Itens do Empenho";
        oWindowAuxEmpenho = new windowAux("oWindowAuxEmpenho", sTituloWindowAux, 950, 500);
        var sConteudoWindow = "<fieldset>";
        sConteudoWindow += "<legend><b>Itens de Empenhos</b></legend>";
        sConteudoWindow += "<center>";
        sConteudoWindow += "<b>Data Inicial: </b>";
        sConteudoWindow += new DBTextFieldData("oDBTextFieldDataInicialGeral", "oDBTextFieldData", "", 10).toInnerHtml();
        sConteudoWindow += "&nbsp;<b>Data Final: </b>";
        sConteudoWindow += new DBTextFieldData("oDBTextFieldDataFinalGeral", "oDBTextFieldData", "", 10).toInnerHtml();
        sConteudoWindow += "&nbsp;<input";
        sConteudoWindow += "  type='button'";
        sConteudoWindow += "  name='btnAlterarDatas'";
        sConteudoWindow += "  id='btnAlterarDatas'";
        sConteudoWindow += "  value='Alterar Todos'";
        sConteudoWindow += "  onclick='js_AlterarDatas();'";
        sConteudoWindow += ">";
        sConteudoWindow += "</center><br>";
        sConteudoWindow += "<div id='ctnGridItensEmpenho'></div>";
        sConteudoWindow += "</fieldset>";
        sConteudoWindow += "<center>";
        sConteudoWindow += "<input";
        sConteudoWindow += "  type='button'";
        sConteudoWindow += "  name='btnSalvarItens'";
        sConteudoWindow += "  id='btnSalvarItens'";
        sConteudoWindow += "  value='Salvar'";
        sConteudoWindow += "  onclick='js_salvaVinculoItensEmpenho();'";
        sConteudoWindow += ">";
        sConteudoWindow += "</center>";

        oWindowAuxEmpenho.setContent(sConteudoWindow);

        oWindowAuxEmpenho.setShutDownFunction(function() {
            alert("Necess�rio vincular os itens de Empenho que ainda n�o possuem vinculo com este contrato.");
        });

        var sTituloMsgBoard = "";
        var sHelpMsgBoard = "";
        var oMsgBoardEmpenho = new DBMessageBoard("oWindowAuxEmpenho",
            sTituloMsgBoard,
            sHelpMsgBoard,
            oWindowAuxEmpenho.getContentContainer()
        );
        oMsgBoardEmpenho.show();
        oGridItensEmpenhos = new DBGrid('ctnGridItensEmpenho');
        oGridItensEmpenhos.nameInstance = 'oGridItensEmpenhos';

        var aHeaders = new Array("Empenho",
            "Item",
            "C�digo Material",
            "Descri��o",
            "Data Inicial",
            "Data Final");

        var aAlign = new Array("right",
            "right",
            "right",
            "right",
            "center",
            "center");

        var aWidth = new Array("8%",
            "7%",
            "10%",
            "45%",
            "15%",
            "15%");


        oGridItensEmpenhos.hasCheckbox = true;
        oGridItensEmpenhos.setCellAlign(aAlign);
        oGridItensEmpenhos.setCellWidth(aWidth);
        oGridItensEmpenhos.setHeader(aHeaders);
        oGridItensEmpenhos.aHeaders[2].lDisplayed = false;
        oGridItensEmpenhos.setHeight(200);
        oWindowAuxEmpenho.hide();
    }

    /**
     * Fun��o que executa o ajax e preenche a grid na windowAux com os lan�amentos encontrados
     */
    function js_carregaItensEmpenho() {

        var oParam = new Object();
        oParam.exec = "getItensEmpenhosAindaNaoVinculados";
        oGet = js_urlToObject();
        oParam.iAcordo = oGet.ac20_acordo;

        js_divCarregando("Aguarde, buscando itens dos empenhos...", "msgBox");
        var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php", {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_preencheGridItensEmpenhos
        });
    }

    //Cria as fun��es em escopo global, para serem referenciadas em qualquer escopo
    var oDBTextFieldData = new DBTextFieldData("oDBTextFieldData", "oDBTextFieldData", "", 10);

    /**
     * Fun��o que preenche a grid com itens de empenhos
     */
    function js_preencheGridItensEmpenhos(oAjax) {


        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.status == 2) {

            alert(oRetorno.message.urlDecode());
            return false;
        }

        /*
         * Caso n�o exista item a ser inserido no contrato, esconde window
         * Caso exista itens a serem incluidos, mostra window e gera grid
         */
        if (oRetorno.aItensEmpenhos == null) {

            oWindowAuxEmpenho.hide();
            js_mostrarTelaModificacaoItens();
            return false;

        } else if (oRetorno.aItensEmpenhos.length > 0) {

            oWindowAuxEmpenho.show();
            oGridItensEmpenhos.show($('ctnGridItensEmpenho'));
            oGridItensEmpenhos.clearAll(true);


            //Preenche cada linha da grid, com o item a ser vinculado ao contrato
            oRetorno.aItensEmpenhos.each(function(oItem, iLinha) {

                var aLinha = new Array();
                aLinha[0] = oItem.iEmpenho;
                aLinha[1] = oItem.iEmpenhoItem;
                aLinha[2] = oItem.iCodigoMaterial;
                aLinha[3] = oItem.sDescricao.urlDecode();

                var oDBTextFieldDataInicial = new DBTextFieldData("oDBTextFieldDataInicial" + iLinha, "oDBTextFieldData", "", 10);
                var oDBTextFieldDataFinal = new DBTextFieldData("oDBTextFieldDataFinal" + iLinha, "oDBTextFieldData", "", 10);

                aLinha[4] = oDBTextFieldDataInicial.toInnerHtml();
                aLinha[5] = oDBTextFieldDataFinal.toInnerHtml();

                oGridItensEmpenhos.addRow(aLinha, true, true, true);
            });

            oGridItensEmpenhos.renderRows();
        }
    }

    /**
     *  Verifica inicialmente o tipo de acordo com a finalidade de, em caso de empenho,
     *  com a finalidade de definir a necessidade de pesquisar itens de empenho ainda n�o vinculados
     */
    function js_verificaTipoAcordo() {

        var oGet = js_urlToObject();
        var oParam = new Object();
        oParam.iAcordo = oGet.ac20_acordo;
        oParam.exec = "verificaTipoAcordo";

        js_divCarregando("Aguarde, verificando Tipo de Acordo...", "msgBox");
        var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php", {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_finalizaVerificacaoTipoAcordo
        });
    }

    function js_finalizaVerificacaoTipoAcordo(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.status == 2) {

            alert(oRetorno.message.urlDecode());
            return false;
        }
        /**
         * Se for Origem Licitacao, os botoes excluir e salvar da Dotacao devem estar desabilitados
         */
        if (oRetorno.iTipoAcordo == 2) {
            desabilitaDotacao = true;
        }
        //Caso Tipo Empenho, verificar os itens ainda n�o vinculados ao contrato
        if (oRetorno.iTipoAcordo == 6) {
            js_carregaItensEmpenho();
        } else {
            //mostra tela, com os outros tipos de origem de contrato
            js_mostrarTelaModificacaoItens();
        }
    }

    function js_mostrarTelaModificacaoItens() {

        oWindowAuxEmpenho.hide();
        //$('db_opcao').onclick = js_saveItem;
        js_init();
        js_desabilitaItemSelecionar();
    }

    //$('db_opcao').onclick = js_saveItem;

    function js_salvaVinculoItensEmpenho() {

        var oGet = js_urlToObject();
        var aItensEmpenhos = oGridItensEmpenhos.getSelection();
        var aItensContrato = new Array();

        aItensEmpenhos.each(function(oItem, iIndice) {

            var aNovoItem = new Object();
            aNovoItem.iEmpenho = oItem[1];
            aNovoItem.iEmpenhoItem = oItem[2];
            aNovoItem.iCodigoMaterial = oItem[3];
            aNovoItem.dtInicial = oItem[5];
            aNovoItem.dtFinal = oItem[6];
            aNovoItem.iTipoControle = 1;
            aItensContrato.push(aNovoItem);
        });

        var oParam = new Object();
        oParam.aItens = aItensContrato;
        oParam.iAcordo = oGet.ac20_acordo;
        oParam.exec = "salvaVinculoItensEmpenho";

        js_divCarregando("Aguarde, salvando vinculos com os itens configurados...", "msgBox");
        var oAjax = new Ajax.Request("ac4_acordoinclusao.rpc.php", {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_finalizaVinculoItensEmpenho
        });

    }

    function js_finalizaVinculoItensEmpenho(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");

        if (oRetorno.iStatus == 2) {

            alert(oRetorno.sMessage.urlDecode());
            return false;
        }
        js_mostrarTelaModificacaoItens();

    }

    function js_AlterarDatas() {

        var aItensEmpenhos = oGridItensEmpenhos.getSelection();

        aItensEmpenhos.each(function(oItem, iIndice) {

            $('oDBTextFieldDataInicial' + iIndice).value = $('oDBTextFieldDataInicialGeral').value;
            $('oDBTextFieldDataFinal' + iIndice).value = $('oDBTextFieldDataFinalGeral').value;
        });
    }

    function js_verificaServico() {

        var servicoquantidade = $('ac20_servicoquantidade').value;
        if (servicoquantidade == 'f' && isServico == "t") {
            $('ac20_quantidade').disabled = true;
            $('ac20_quantidade').readOnly = true;
            $('ac20_quantidade').style.backgroundColor = 'rgb(222, 184, 135)';
            $('ac20_quantidade').value = 1;
        } else if (servicoquantidade == 't' && isServico == "t") {
            $('ac20_quantidade').disabled = false;
            $('ac20_quantidade').readOnly = false;
            $('ac20_quantidade').style.backgroundColor = '#FFF';
        } else {
            $('ac20_quantidade').disabled = false;
            $('ac20_quantidade').readOnly = false;
            $('ac20_quantidade').style.backgroundColor = '#FFF';
        }

        if (isServico == "f") {
            $('ac20_servicoquantidade').options[1].disabled = true;
        } else {
            $('ac20_servicoquantidade').options[1].disabled = false;
        }
    }

    js_showGrid();
    js_windowItensEmpenho();
    js_verificaTipoAcordo();
</script>
<?php
if ($oDaoAcordo->numrows > 0) {

    echo "<script>js_bloqueiaCampos (" . $oAcordo->ac16_origem . ");</script>";
}
?>
