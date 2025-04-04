<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js, dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, widgets/DBHint.widget.js");
db_app::load("grid.style.css");
db_app::load("estilos.bootstrap.css");
db_app::load("time.js");

db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>DBSeller Inform�tica Ltda - P�gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>

</head>
<style>
    .container {
        margin-top: 10px;
        background-color: #F5FFFB;
        padding: 10px;
        max-width: 800px;
        width: 100%;
        font-size: 12px;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    fieldset>div {
        margin-bottom: 10px;
        /* Espa�amento entre as linhas */
    }
</style>

<body style="font-family: Arial; background-color: #F5FFFB">
    <div class="container">
        <fieldset>
            <legend><strong>Prazo de Entrega</strong></legend>
            <div>
                <label for="sequencial"><strong>Sequencial:</strong></label>
                <?php db_input('sequencial', '', '', true, 'text', 3, 'style="width: 80px;"', '', '', '', '', 'form-control'); ?>
            </div>
            <div>
                <label for="descricao"><strong>Descri��o:</strong></label>
                <?php db_input('descricao', '', '', true, 'text', 3, 'style="width: 700px; text-transform: uppercase;', '', '', '', '74', 'form-control'); ?>
            </div>

            <div>
                <label for="ativo"><strong>Ativo:</strong></label>
                <select class="form-control" name="ativo" id="ativo" style="width: 80px;">
                    <option value="true">SIM</option>
                    <option value="false">N�O</option>
                </select>
            </div>
        </fieldset>
        <div class="button-container">
            <input name="excluir" type="submit" class="btn btn-danger" value="Excluir" onclick="excluirPrazo()">
            <input name="incluir" type="submit" class="btn btn-secondary" value="Pesquisar" onclick="js_pesquisa();">
        </div>

    </div>
</body>

</html>
<script>
    const url = 'com_prazoentrega.RPC.php';

    js_pesquisa();

    function js_pesquisa() {
    js_OpenJanelaIframe('', 'db_iframe_prazos', 'func_prazos.php?funcao_js=parent.js_preenchepesquisa|Sequencial', 'Pesquisa', true);
    }


    function js_preenchepesquisa(Sequencial) {
    document.getElementById('sequencial').value = Sequencial;
    db_iframe_prazos.hide();
    js_getPrazosCadastrados();
    }

function js_getPrazosCadastrados() {
    let oParam = {};
    let pc97_sequencial = document.getElementById('sequencial').value;
    oParam.exec = "getPrazos";
    oParam.sequencial = pc97_sequencial;
    new Ajax.Request('com_prazoentrega.RPC.php', {
        method: 'post',
        parameters: 'json=' + Object.toJSON(oParam),
        onComplete: js_retornoPrazosCadastrados
    });
}

function js_retornoPrazosCadastrados(oAjax) {
    let oRetorno = JSON.parse(oAjax.responseText);
    let prazoentrega = oRetorno.prazo;

    document.getElementById('descricao').value = prazoentrega[0].pc97_descricao;
    document.getElementById('ativo').value = prazoentrega[0].pc97_ativo;
}

function excluirPrazo() {
        if (confirm('Tem certeza que deseja excluir?')) {
            let oParam = {};
            oParam.exec = "excluirPrazo";
            oParam.pc97_sequencial = document.getElementById('sequencial').value;
            

            new Ajax.Request(url, {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParam),
                onComplete: function(response) {
                    let oRetorno = JSON.parse(response.responseText);
                    if (oRetorno.status === 1) {
                        alert('Exclu�do com sucesso!');
                        js_pesquisa();

                    } else {
                        alert('Erro ao excluir : ' + oRetorno.message);
                    }
                }
            });
        }
    }
</script>