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

use App\ViewModel\Veiculo\Abastecimento\Importacao\DbFrAbastecimentoViewModel;

$dadosImportados = [];
$existeDadosImportados = false;
$viewModel = new DbFrAbastecimentoViewModel();

$dataReferenciaEmpHidden = '';
$listaErros = [];
$nomeArquivo = '';
$resultValidation = null;
$dadosImportados = [];

$dataInicial = new DateTime(implode('/',array_reverse(explode('/',$dataInicial))));
$dataFinal = new DateTime(implode('/',array_reverse(explode('/',$dataFinal))));

if (!empty($_FILES)) {
    $nomeArquivo =  empty($nomeArquivoHidden) || !empty($_FILES['arquivo']['name']) ? $_FILES['arquivo']['name'] : $nomeArquivoHidden;

    $dadosImportados = $viewModel->getDadosImportados($nomeArquivo);

    if($dataFinal < $dataInicial){
        echo "<script>alert('A data do per�odo final deve ser maior ou igual a do per�odo Inicial.');</script>";
    }else{
        $quantidadeItens = count($dadosImportados);

        if ($quantidadeItens > 0) {
            $resultValidation = $viewModel->validaDadosImportados($dadosImportados);
            $existeDadosImportados = true;
            if ($resultValidation->hasErrors()) {
                $listaErros = $resultValidation->getErrors();
                $existeDadosImportados = false;
            } else {
                $dadosImportados = $viewModel->filtrarPorData($dadosImportados, $dataInicial, $dataFinal);
                $quantidadeItens = count($dadosImportados);
                $viewModel->ordenaDados($dadosImportados);
                $dataReferenciaEmpHidden = $viewModel->getMaiorData($dadosImportados);
            }
        }

        if ($quantidadeItens === 0) {
            echo "<script>alert('N�o h� abastecimentos para ser importados');</script>";
        }
    }
}

?>
<style>
    .form_movimentacoes {
        width: 68%;
        height: 150px;
        padding: 15px;
        margin-bottom: 10px;
    }

    .column {
        display: flex;
        flex-direction: column;
        align-items: start;
        justify-content: center;
    }

    .w-35 {
        width: 35%;
    }

    .w-60 {
        width: 60%;
    }

    .caixa {
        width: 68%;
        height: 410px;
        background-color: #3CA980;
    }

    .scroll {
        width: 70%;
        max-height: 350px;
        overflow-y: auto;
        padding: 0 15px;
    }

    .row {
        display: flex;
        width: 100%;
        margin-bottom: 5px;
    }

    .row_align_right {
        width: 98%;
        justify-content: end;
        padding-right: 2%;
    }

    .row:first {
        height: 50%;
    }

    .w-35>.row {
        text-align: right;
    }

    .w-60>.row {
        text-align: left;
    }

    .row-controls {
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }

    .control {
        margin-right: 10px;
    }

    label {
        font-weight: 700;
    }

    #dtjs_dataInicial #dtjs_dataFinal {
        margin: 0 10px;
    }

    .table-errors,
    .table-errors tbody td,
    .table-errors thead th {
        border-collapse: collapse;
        border: 1px solid black;
    }

    .table-errors td {
        padding: 0px 3px;
    }

    .table-errors thead th {
        background-color: #3CA980;
    }

    .table-errors tbody tr:nth-child(odd) {
        background-color: #A7D6C4;
    }

    .form_empenho_abastecimento {
        width: 68%;
        padding: 15px;
    }

    .form_empenho_abastecimento .row input {
        margin-left: 5px;
    }

    #table-result {
        width: 70%;
        border: 0px solid black;
    }

    #table-result thead th {
        border: 0px solid red;
        background: #eeeff2;
    }

    #table-result tbody td {
        background: #eeeff2;
        border: 0px solid red;
    }
</style>
<form method="post" enctype="multipart/form-data" onsubmit="return verificaCamposImportacao()">
    <fieldset class="form_movimentacoes">
        <legend>Importar Abastecimento</legend>
        <div class="box">
            <div class="row">
                <div class="column w-35">
                    <div class="row row_align_right">
                        <label for="arquivo">Importar XLSX:</label>
                    </div>
                    <div class="row row_align_right">
                        <label for="nomeArquivo">Nome do Arquivo: </label>
                    </div>
                    <div class="row row_align_right">
                        <label>Per�odo: </label>
                    </div>
                </div>

                <div class="column w-60">
                    <div class="row">
                        <input type="file" name="arquivo" id="arquivo" style="width: 256px;">
                    </div>
                    <div class="row">
                        <?php db_input('nomeArquivo', 35, $nomeArquivo, true, 'text', 3, "", "", "", "width: 255px"); ?>
                        <input type="hidden" id="nomeArquivoHidden" name="nomeArquivoHidden" value="<?= $nomeArquivo ?>">
                        <input type="hidden" id="dataReferenciaEmpHidden" name="dataReferenciaEmpHidden" value="<?= $dataReferenciaEmpHidden ?>">
                    </div>
                    <div class="row">
                        <?php
                        if (!empty($dataInicial) && !empty($dataFinal)) {
                            db_inputdata("dataInicial", $dataInicial->format('d'), $dataInicial->format('m'), $dataInicial->format('Y'), '', 'text', 1, '', 'dataInicial');
                            echo " <strong style='margin: 0 15px;'>at�</strong> ";
                            db_inputdata("dataFinal", $dataFinal->format('d'), $dataFinal->format('m'), $dataFinal->format('Y'), '', 'text', 1, '', 'dataFinal');
                        } else {
                            db_inputdata("dataInicial", '', '', '', '', 'text', 1, '', 'dataInicial');
                            echo " <strong style='margin: 0 15px;'>at�</strong> ";
                            db_inputdata("dataFinal", '', '', '', '', 'text', 1, '', 'dataFinal');
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row row-controls">
                <div class="control">
                    <input name='processar' type='submit' id="Processar" value="Processar" />
                </div>
                <div class="control">
                    <input name='exportar' type='button' id="exportar" value="Gerar Planilha" onclick="gerar()" />
                </div>
            </div>
    </fieldset>
</form>
<?php if (!empty($resultValidation) && $resultValidation->hasErrors()) : ?>
    <section>
        <h3>A importa��o cont�m erros</h3>
        <table class="table-errors">
            <thead>
                <tr>
                    <th>Codigo de Abastecimendo</th>
                    <th>Mensagem</th>
                    <th>Campo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaErros as $error) : ?>
                    <tr>
                        <td><?= $error->codigoAbastecimento; ?></td>
                        <td><?= $error->message; ?></td>
                        <td><?= $error->field; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>

<?php if (!empty($resultValidation) && !$resultValidation->hasErrors()) : ?>
    <fieldset class="form_empenho_abastecimento">
        <legend>Empenho para abastecimentos</legend>
        <div class="row">
            <?php db_ancora("Empenho:", "js_pesquisae60_codemp(true,0);", 1); ?>
            <?php db_input('e60_codemp', 10, $Ie60_codemp, true, 'text', 1, "placeholder='num/ano' onchange='js_pesquisae60_codemp(false,0);'") ?>
            <?php
            db_input('z01_nome', 35, $Iz01_nome, true, 'text', 3, "", "", "", "");
            ?>
            <input type='button' id='btnAplicar' value='Aplicar' onclick="aplicarEmpenho();">
        </div>
    </fieldset>

    <table class="DBGrid" id="table-result" style="width: 70%; margin-top:15px">
        <thead>
            <tr>
                <th class="table_header" style="width: 30px; cursor: pointer;" onclick="marcarTodos();">M</th>

                <th style=" width:50px;">
                    C�d Abastecimento
                </th>
                <th style=" width:120px; ">
                    Placa
                </th>
                <th style=" width:120px; ">
                    Data
                </th>
                <th style=" width:100px; ">
                    Valor
                </th>
                <th style=" width:50px; ">
                    Km
                </th>
                <th style=" width:200px; ">
                    Unidade
                </th>
                <th>
                    Empenho
                </th>
            </tr>
        </thead>
    </table>

    <div class="scroll">
        <table class="DBGrid" id="table-result" style="width: 100%;">
            <tbody>


                <?php foreach ($dadosImportados as $key => $value) : ?>
                    <tr id="tr_<?= $value['codigo_abastecimento'] ?>">
                        <td class="table_header" style="width: 30px; cursor: pointer;" data-info="<?= $key ?>">
                            <input name="checkRow" type="checkbox" class="checkboxes">
                            <input type="hidden" name="id" value="<?= $key ?>" />
                            <input type="hidden" name="codigo_abastecimento" value="<?= $value['codigo_abastecimento'] ?>" />
                            <input type="hidden" name="data" value="<?= $value['data'] ?>" />
                            <input type="hidden" name="hora" value="<?= $value['horario'] ?>" />
                            <input type="hidden" name="placa" value="<?= $value['placa'] ?>" />
                            <input type="hidden" name="motorista" value="<?= $value['motorista'] ?>" />
                            <input type="hidden" name="cpf" value="<?= $value['cpf'] ?>" />
                            <input type="hidden" name="unidade" value="<?= $value['unidade'] ?>" />
                            <input type="hidden" name="subunidade" value="<?= $value['subunidade'] ?>" />
                            <input type="hidden" name="combustivel" value="<?= $value['combustivel'] ?>" />
                            <input type="hidden" name="km_abs" value="<?= $value['km_abs'] ?>" />
                            <input type="hidden" name="quantidade_litros" value="<?= $value['quantidade_litros'] ?>" />
                            <input type="hidden" name="preco_unitario" value="<?= $value['preco_unitario'] ?>" />
                            <input type="hidden" name="valor" value="<?= $value['valor'] ?>" />
                            <input type="hidden" name="produto" value="<?= $value['produto'] ?>" />
                            <input type="hidden" name="empenho" value="" />
                        </td>

                        <td id="td_<?= $value['codigo_abastecimento'] ?>" style=" width:86px;">
                            <?= $value['codigo_abastecimento'] ?>
                        </td>

                        <td id="td_placa_<?= $value['codigo_abastecimento'] ?>" style=" width:120px;">
                            <?= $value['placa'] ?>
                        </td>

                        <td id="td_data_<?= $value['codigo_abastecimento'] ?>" style=" width:120px; ">
                            <?= $value['data'] ?>
                        </td>

                        <td id="td_valor_<?= $value['codigo_abastecimento'] ?>" style="width:100px;">
                            <?= db_formatar($value['valor'],'f') ?>
                        </td>

                        <td id="td_km_<?= $value['codigo_abastecimento'] ?>" style="width:50px;">
                            <?= $value['km_abs'] ?>
                        </td>
                        <td id="td_unidade_<?= $value['codigo_abastecimento'] ?>" style="width:200px;">
                            <?= utf8_decode($value['unidade']) ?>
                        <td id="td_empenho_<?= $value['codigo_abastecimento'] ?>" style="display:flex; align-items: center; justify-content: center;">
                            <input type="text" onchange="empvalido(this.value,this.id);js_pesquisaempenholinha(this.value,this.id);" name="input_empenho" id="input_empenho_<?= $key ?>" style="margin-right: 3px;" placeholder="num/ano">
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <table style="width: 68%;" >
        <tbody>
            <tr style="padding: 3px">
                <td align="right">
                 <p><strong>Total de itens:</strong>
                    <span class="nowrap" id="totalitens"> <?= $quantidadeItens ?> </span></p>
                </td>
            </tr>
            <tr>
                <td colspan="7" align="center">
                    <input style="margin-top:10px;" type="button" id="salvarImportacao" value="Salvar" onclick="js_salvar();">
                </td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>

<table id="minhaTabela" class="table-errors" style="display: none; margin-top: 10px">
    <thead>
        <tr>
            <th>C�digo de Abastecimento</th>
            <th>Mensagem</th>
            <th>Placa</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dados ser�o inseridos aqui via JavaScript -->
    </tbody>
</table>
<script>
    function verificaCamposImportacao() {
        const inputDataInicial = document.getElementById('dataInicial');
        const inputDataFinal = document.getElementById('dataFinal');

        const inputFile = document.getElementById('arquivo');
        const inputNomeArquivo = document.getElementById('nomeArquivo');

        if (inputFile.files.length === 0 && inputNomeArquivo.value == "") {
            alert('� preciso anexar um arquivo');
            return false;
        }


        if (inputDataInicial.value == "") {
            alert("Campo data inicial vazio");
            dataInicial.select();
            return false;
        }

        if (inputDataFinal.value == "") {
            alert("Campo data final vazio");
            dataFinal.select();
            return false;
        }

        return true;
    }

    function gerar() {
        window.location.href = "vei1_xlsabastecimentoPlanilha.php";
        js_removeObj("msgbox");
    }

    function js_salvar() {

        const checkboxes = document.querySelectorAll('.checkboxes');
        let peloMenosUmMarcado = false;


        const tdInformation = document.querySelectorAll('[data-info]');
        const abastecimentos = [];

        tdInformation.forEach(function(td) {
            const children = td.querySelectorAll('input');
            const abastecimento = {};

            children.forEach(function(input) {
                abastecimento[input.name] = input.value;
                let posicao = td.getAttribute('data-info');
                let empenho = document.getElementById('input_empenho_'+posicao).value;
                abastecimento['empenho'] = empenho;
            });
            abastecimentos.push(abastecimento);
        });

        const codigosEmpenhoNulo = getCodigoEmpenhoNulo(abastecimentos);

        if (codigosEmpenhoNulo.length > 0) {
            alert("Existe(em) abastecimento(os) com campo empenho n�o informado. Codigos de Abastecimento: <br/> " + codigosEmpenhoNulo.toString());
            return;
        }

        const oParametros = new Object();
        oParametros.exec = 'importarv2';
        oParametros.abastecimentos = abastecimentos;
        js_divCarregando('Aguarde, salvando abstecimentos', 'msgbox');
        new Ajax.Request('vei1_xlsabastecimento.RPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: oRetornoSalvar
        });
    }

    function getCodigoEmpenhoNulo(abastecimentos) {
        let codigosEmpenho = [];

        abastecimentos.forEach(function(abastecimento, index) {
            let td = 'td_'+abastecimento.codigo_abastecimento;
            let td_placa = 'td_placa_'+abastecimento.codigo_abastecimento;
            let td_data = 'td_data_'+abastecimento.codigo_abastecimento;
            let td_valor = 'td_valor_'+abastecimento.codigo_abastecimento;
            let td_km = 'td_km_'+abastecimento.codigo_abastecimento;
            let td_unidade_ = 'td_unidade_'+abastecimento.codigo_abastecimento;
            let td_empenho = 'td_empenho_'+abastecimento.codigo_abastecimento;

            document.getElementById(td).style.backgroundColor = '#eeeff2';
            document.getElementById(td_placa).style.backgroundColor = '#eeeff2';
            document.getElementById(td_data).style.backgroundColor = '#eeeff2';
            document.getElementById(td_valor).style.backgroundColor = '#eeeff2';
            document.getElementById(td_km).style.backgroundColor = '#eeeff2';
            document.getElementById(td_empenho).style.backgroundColor = '#eeeff2';
            document.getElementById(td_unidade_).style.backgroundColor = '#eeeff2';

            if (abastecimento.empenho == '') {
                let codigoAbastecimento = abastecimento.codigo_abastecimento;

                if (((index + 1) % 6) == 0) {
                    codigoAbastecimento = '<br/>' + codigoAbastecimento;
                }
                codigosEmpenho.push(codigoAbastecimento);
            }
        });
        return codigosEmpenho;
    }

    function oRetornoSalvar(oAjax) {
        const oRetorno = eval('(' + oAjax.responseText + ")");

        js_removeObj("msgbox");
        if (oRetorno.status === 1) {
            alert("Abastecimento(s) cadastrado(s) com sucesso!");
            window.location.href = 'vei1_abastimportacao002.php';
            return;
        }

        alert(oRetorno.message);
        if (oRetorno.errors.length === 0) {
            return;
        }

        document.getElementById('minhaTabela').style.display = '';
        const corpoTabela = document.querySelector("#minhaTabela tbody");

        resetarLinhasTabela(corpoTabela);

        oRetorno.errors.forEach(function(objeto) {
            let td = 'td_'+objeto.codigoAbastecimento;
            let td_placa = 'td_placa_'+objeto.codigoAbastecimento;
            let td_data = 'td_data_'+objeto.codigoAbastecimento;
            let td_valor = 'td_valor_'+objeto.codigoAbastecimento;
            let td_km = 'td_km_'+objeto.codigoAbastecimento;
            let td_empenho = 'td_empenho_'+objeto.codigoAbastecimento;
            let td_unidade = 'td_unidade_'+objeto.codigoAbastecimento;

            document.getElementById(td).style.backgroundColor = '#316648';
            document.getElementById(td_placa).style.backgroundColor = '#316648';
            document.getElementById(td_data).style.backgroundColor = '#316648';
            document.getElementById(td_valor).style.backgroundColor = '#316648';
            document.getElementById(td_km).style.backgroundColor = '#316648';
            document.getElementById(td_empenho).style.backgroundColor = '#316648';
            document.getElementById(td_unidade).style.backgroundColor = '#316648';


            const linha = document.createElement("tr"); // Cria uma nova linha
            Object.values(objeto).forEach(function(valor, index) {
                const celula = document.createElement("td"); // Cria uma nova c�lula
                celula.textContent = valor; // Define o conte�do da c�lula
                linha.appendChild(celula); // Adiciona a c�lula � linha
            });
            corpoTabela.appendChild(linha); // Adiciona a linha ao corpo da tabela
        });
    }

    // Fun��o para resetar as linhas da tabela
    function resetarLinhasTabela(corpoTabela) {
        while (corpoTabela.firstChild) {
            corpoTabela.removeChild(corpoTabela.firstChild);
        }
    }

    function js_pesquisae60_codemp(mostra, controlador) {
        if (mostra == true) {
            const ve70_abast = "";
            const e60_codemp = "";
            const e60_numemp = "";

            const dataAbastecimento = document.getElementById("dataReferenciaEmpHidden").value;

            if (controlador == 0) {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho', 'func_empempenho003.php?filtroabast=0&ve70_abast=' + dataAbastecimento + '&todos=1&importacaoveiculo=1&dataAbastecimento=' + dataAbastecimento + '&funcao_js=parent.js_mostraempempenho2|e60_numemp|e60_codemp|e60_anousu|DB_e60_emiss|e60_numcgm|z01_nome', 'Pesquisa', true);
            } else {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho', 'func_empempenho003.php?filtroabast=0&ve70_abast=' + dataAbastecimento + '&importacaoveiculo=1&dataAbastecimento=' + dataAbastecimento + '&funcao_js=parent.js_mostraempempenho2|e60_numemp|e60_codemp|e60_anousu|DB_e60_emiss|e60_numcgm|z01_nome', 'Pesquisa', true);
            }
        } else {
            let empenho = document.getElementById('e60_codemp').value;
            empvalido(empenho,'e60_codemp');
            const dataAbastecimento = document.getElementById("dataReferenciaEmpHidden").value;

            const e60_numemp = document.getElementById("e60_codemp").value;
            js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho', 'func_empempenho003.php?filtroabast=0&importacaoveiculo=1&dataAbastecimento=' + dataAbastecimento + '&pesquisa_chave=' + e60_numemp + '&funcao_js=parent.js_mostraempempenho&lPesquisaPorCodigoEmpenho=1', 'Pesquisa', false);
        }
    }

    function js_pesquisaempenholinha(empenho,id){
        empvalido(empenho,id);
        const dataAbastecimento = document.getElementById("dataReferenciaEmpHidden").value;
        js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho', 'func_empempenho003.php?filtroabast=0&importacaoveiculo=1&dataAbastecimento=' + dataAbastecimento + '&pesquisa_chave=' + empenho + '&funcao_js=parent.js_mostrarempenholinha&lPesquisaPorCodigoEmpenho=1', 'Pesquisa', false);

    }

    function empvalido(empenho,id) {

        empenho = empenho.split("/");
        anoempenho = empenho[1];

        if (anoempenho == "" || anoempenho == undefined) {
            document.getElementById(event.target.id).value = '';
            return alert('Usu�rio: informar o n�mero do empenho com exerc�cio');
        }

        if(anoempenho.length !== 4){
            document.getElementById(id).value = '';
            return alert('Usuario: N�mero do empenho invalido');
        }
    }

    function js_mostraempempenho(chave1, chave2, chave3, chave4, chave5, chave6) {
        if (chave2 == true) {
            document.getElementById("z01_nome").value = "";
            document.getElementById("e60_codemp").value = "";

        } else if (chave2 == false) {
            document.getElementById("z01_nome").value = "";

        } else {
            empenhoselecionado = chave1;
            dataempenho = chave6;
            document.getElementById("z01_nome").value = chave2;
        }
    }

    function js_mostrarempenholinha(chave1,chave2){
        if(chave2 === true){
            return alert('Usuario: N�mero do Empenho n�o Encontrado.')
        }
    }

    function js_mostraempempenho2(e60_numemp, e60_codemp, e60_anousu, DB_e60_emiss, e60_numcgm, z01_nome) {
        document.getElementById("e60_codemp").value = e60_codemp + "/" + e60_anousu;
        document.getElementById("z01_nome").value = z01_nome;

        db_iframe_empempenho.hide();
    }

    function validaCamposData() {
        const dataInicial = document.getElementById('dataInicial');
        const dataFinal = document.getElementById('dataFinal');

        if (dataInicial.value == "") {
            alert("Campo data inicial vazio");
            dataInicial.select();
            return false;
        }

        if (dataFinal.value == "") {
            alert("Campo data final vazio");
            dataFinal.select();
            return false;
        }

        return true;
    }

    function aplicarEmpenho() {
        const numeroEmpenho = document.getElementById('e60_codemp').value;

        const tdInformation = document.querySelectorAll('[data-info]');

        tdInformation.forEach(function(td) {
            const children = td.querySelectorAll('input');
            if (children[0].checked) {
                children.forEach(function(input) {
                    if (input.name == 'id') {
                        const id = input.value
                        const nameInputEmpenho = 'input_empenho_' + id;
                        document.getElementById(nameInputEmpenho).value = numeroEmpenho;
                    }

                    if (input.name == 'empenho') {
                        input.value = numeroEmpenho;
                    }
                });
            }
        });
    }

    function marcarTodos() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');

        // Verifica se algum est� marcado
        let algumMarcado = false;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                algumMarcado = true;
            }
        });

        // Se algum estiver marcado, desmarque todos. Caso contr�rio, marque todos.
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = !algumMarcado;
        });

    }

    function parseDate(dateString) {
        const parts = dateString.split('/');
        // Note: JavaScript Date object months are 0-based
        const day = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1; // Subtract 1 since months are 0-based
        const year = parseInt(parts[2], 10);
        return new Date(year, month, day);
    }

    function validaDataInicial() {
        console.log("Verifica Data inicial");
        const dataInicial = document.getElementById('dataInicial').value;
        const dataFinal = document.getElementById('dataFinal').value;

        const parsedDataInicial = parseDate(dataInicial);
        const parsedDataFinal = parseDate(dataFinal);

        if (parsedDataInicial >= parsedDataFinal) {
            return false;
        }

        return true;
    }
</script>