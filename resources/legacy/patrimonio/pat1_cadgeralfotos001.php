<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_bemfoto_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);
$clbem = new cl_bens;
$clbem->rotulo->label();
$clbemfoto = new cl_bemfoto;
$db_opcaoal = 1;
$clbemfoto->rotulo->label();

//testa para saber se � pessoa f�sica ou jur�dica
//seta vari�vel para exibir parte pertiente a cada tipo no formul�rio
if (isset($oPost->cpf) && trim($oPost->cpf) != "") {
	$lPessoaFisica = true;
} else {
	$lPessoaFisica = false;
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
	<?
	db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                   dbViewCadEndereco.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
                   datagrid.widget.js");
	db_app::load("estilos.css,grid.style.css");
	?>
</head>
<body class="body-default">
<form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">
    <center>
        <table>
            <tr>
                <td valign="top">
                    <fieldset>
                        <legend>
                            <strong>Adicionar Foto:</strong>
                        </legend>
                        <table>
                            <tr>
                                <td valign="top">
                                    <p><b>Arquivo da Foto:</b></p>
                                </td>
                                <td valign='top'>
									<?
									db_input("uploadfile", 30, 0, true, "file", $db_opcaoal);
									db_input("namefile", 30, 0, true, "hidden", $db_opcaoal);
									?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><b>Foto ativa:</b></p>
                                </td>
                                <td>
									<?
									db_select("t54_fotoativa", array("t" => "Sim", "f" => "N�o"), true, $db_opcaoal);
									?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><b>Foto Principal:</b></p>
                                </td>
                                <td>
									<?
									db_select("t54_principal", array("t" => "Sim", "f" => "N�o"), true, 1);
									?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
                <td rowspan="2">
                    <img src="imagens/moveis.png" width="95" height="120" id='preview'>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <input type='button' id='btnSalvar' Value='Salvar'>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <fieldset>
                        <legend>
                            <strong>Fotos Cadastradas</strong>
                        </legend>
                        <div id='ctnDbGridFotos'>
                        </div>
                    </fieldset>
                </td>
            </tr>
        </table>
        <strong>Apenas ser�o aceitas imagens do tipo "JPG", "JPEG" e "PNG" com tamanho m�ximo de <span
                    style='color:red'>100 KB</span>.</strong>
    </center>

</form>
</body>
<div id='teste' style='display:none'>
</div>
</html>
<div style="position: absolute;padding: 3px; background-color:#FFFFCC; border: 1px solid #999999; display:none"
     id='ctnDisplayFoto'>
    <img width="95" height="120" style='border:1px inset white' id='previewfotogrid'>
</div>
<script>

    var iNumBem = '<?=$oGet->t52_codbem?>';
    var iLote = '<?=$oGet->cod_lote?>';
    var tela_inativa = '<?=$oGet->db_opcaoal?>';
    var iPlaca = '<?=$oGet->cod_placa?>';

    var sUrlRpc = "pat1_bensnovo.RPC.php";

    oGridFotos = new DBGrid('gridFotos');
    oGridFotos.nameInstance = "oGridFotos";
    oGridFotos.setHeight(200);
    oGridFotos.setCellAlign(new Array("right", "center", "center", "center", "center", "center", "center"));
    oGridFotos.setHeader(new Array("Codigo", "Data", "Hora", "Pric", "Ativa", "Ver", "A��o"));
    oGridFotos.show($('ctnDbGridFotos'));

    if (tela_inativa == 33) {
        js_desabilitaCampos();
    }
    /**
     * Cria um listener para subir a imagem, e criar um preview da mesma
     */
    $('uploadfile').observe('change', function () {

        startLoading();
        var iFrame = document.createElement("iframe");
        iFrame.src = 'func_uploadfile.php?clone=form1&formats=3';
        iFrame.id = 'uploadIframe';
        $('teste').appendChild(iFrame);
    });

    function startLoading() {
        js_divCarregando('Aguarde... Carregando Imagem', 'msgbox');
    }

    function endLoading() {
        js_removeObj('msgbox');
    }

    function js_previewImagem(iOid, oDiv) {

        el = oDiv;
        var x = 0;
        var y = el.offsetHeight;

        /*
		 * calculamos a distancia do dropdown em rela��o a p�gina,
		 * para podemos renderiza-lo na posi��o correta.
		 */
        while (el.offsetParent && el.id.toUpperCase() != 'wndAuxiliar') {

            if (el.className != "windowAux12") {

                x += new Number(el.offsetLeft);
                y += new Number(el.offsetTop);

            }
            el = el.offsetParent;

        }
        x += new Number(el.offsetLeft);
        y += new Number(el.offsetTop) + 4;
        /*
		 * Pegamos a largura do dropdown, e diminuimos da posi�ao do cursors
		 */
        $('ctnDisplayFoto').style.left = x + 40;
        $('ctnDisplayFoto').style.top = y - ($('gridFotosbody').scrollTop);
        $('previewfotogrid').src = 'func_mostrarimagem.php?oid=' + iOid;
        $('ctnDisplayFoto').style.display = '';
    }

    function js_closePreview() {

        $('previewfotogrid').src = '';
        $('ctnDisplayFoto').style.display = 'none';
    }

    function js_salvarFoto() {

        if ($F('namefile') == '') {

            alert('Escolha uma Foto!');
            return false;
        }
        var oParam = new Object();
        oParam.exec = 'adicionarFoto';
        oParam.iBem = iNumBem;
        oParam.iLote = iLote;
        oParam.iPlaca = iPlaca;
        oParam.principal = $F('t54_principal') == "t" ? true : false;
        oParam.ativa = $F('t54_fotoativa') == "t" ? true : false;
        oParam.arquivo = $F('namefile');

        js_divCarregando('Aguarde... Salvando Imagem', 'msgbox');
        var oAjax = new Ajax.Request(
            sUrlRpc,
            {
                parameters: 'json=' + Object.toJSON(oParam),
                method: 'post',
                onComplete: js_retornoSalvarFoto
            });
    }

    function js_retornoSalvarFoto(oAjax) {

        js_removeObj("msgbox");
        var oRetorno = eval('(' + oAjax.responseText + ")");
        if (oRetorno.status == 1) {

            $('uploadfile').value = '';
            $('preview').src = 'imagens/moveis.png';
            js_getFotos();
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_getFotos() {
        var oParam = new Object();
        oParam.exec = 'getFotos';
        oParam.iBem = iNumBem;
        oParam.iLote = iLote;
        oParam.iPlaca = iPlaca;
        var oAjax = new Ajax.Request(
            sUrlRpc,
            {
                parameters: 'json=' + Object.toJSON(oParam),
                method: 'post',
                onComplete: js_retornoGetFotos
            });
    }

    function js_retornoGetFotos(oAjax) {
        var oRetorno = eval('(' + oAjax.responseText + ')');
        oGridFotos.clearAll(true);
        oRetorno.itens.each(function (oFoto, id) {

            var aLinha = new Array();
            aLinha[0] = oFoto.codigo;
            aLinha[1] = js_formatar(oFoto.data, 'd');
            aLinha[2] = oFoto.hora;
            aLinha[3] = eval("principal" + oFoto.codigo + " = new DBComboBox('principal" + oFoto.codigo + "','principal" + oFoto.codigo + "')");
            aLinha[3].addItem('1', 'Sim')
            aLinha[3].addItem('2', 'N�o');
            aLinha[3].setValue(oFoto.principal ? '1' : '2');
            aLinha[4] = eval("ativa" + oFoto.codigo + " = new DBComboBox('ativa" + oFoto.codigo + "','ativa" + oFoto.codigo + "')");
            aLinha[4].addItem('1', 'Sim')
            aLinha[4].addItem('2', 'N�o');
            aLinha[4].setValue(oFoto.ativa ? '1' : '2');
            aLinha[5] = '<input type="button" value="..." onclick="js_previewImagem(' + oFoto.oid + ', this)" onblur="js_closePreview()">';
            aLinha[6] = '<input type="button" value="A" onclick="js_alterarFoto(' + id + ')">';
            aLinha[6] += '<input type="button" value="E" onclick="js_excluirFoto(' + oFoto.codigo + ')">';
            oGridFotos.addRow(aLinha);
        });
        oGridFotos.renderRows();
    }

    $('btnSalvar').observe("click", js_salvarFoto);
    js_getFotos();

    function js_excluirFoto(iFoto) {

        if (!confirm('Confirma a Exclus�o da Imagem?')) {
            return false;
        }
        var oParam = new Object();
        oParam.exec = 'excluirFoto';
        oParam.iBem = iNumBem;
        oParam.iFoto = iFoto;
        js_divCarregando('Aguarde... excluindo imagem', 'msgbox');
        var oAjax = new Ajax.Request(
            sUrlRpc,
            {
                parameters: 'json=' + Object.toJSON(oParam),
                method: 'post',
                onComplete: js_retornoSalvarFoto
            });

    }

    function js_alterarFoto(iRow) {

        oRow = oGridFotos.aRows[iRow];
        var iFoto = oRow.aCells[0].getValue();
        var lPrincipal = oRow.aCells[3].getValue() == 1 ? true : false;
        var lAtiva = oRow.aCells[4].getValue() == 1 ? true : false;

        var oParam = new Object();
        oParam.exec = 'alterarFoto';
        oParam.iBem = iNumBem;
        oParam.iFoto = iFoto;
        oParam.lPrincipal = lPrincipal;
        oParam.lAtiva = lAtiva;
        js_divCarregando('Aguarde... Alterar imagem', 'msgbox');
        var oAjax = new Ajax.Request(
            sUrlRpc,
            {
                parameters: 'json=' + Object.toJSON(oParam),
                method: 'post',
                onComplete: js_retornoSalvarFoto
            });
    }

    function js_desabilitaCampos() {
        document.getElementById('ctnDbGridFotos').style['pointer-events'] = 'none';
        document.getElementById('t54_fotoativa').disabled = true;
        document.getElementById('t54_principal').disabled = true;
        document.getElementById('btnSalvar').disabled = true;
        document.getElementById('uploadfile').disabled = true;
    }
</script>
