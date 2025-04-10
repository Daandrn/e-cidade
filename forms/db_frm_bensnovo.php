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

// $clBens->rotulo->label();
// $clBensPlaca->rotulo->label();
// $clBensAquisicao->rotulo->label();
// $clBensCadCedente->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("t30_descr");
$clrotulo->label("t33_divisao");
$clrotulo->label("o40_descr");

$clrotulo->label("o41_descr");
$clrotulo->label("t41_placa");

$clrotulo->label("t42_descr");

$clrotulo->label("t45_sequencial");
$clrotulo->label("t45_descricao");

$clrotulo->label("t52_depart");

$clrotulo->label("t52_bem");
$clrotulo->label("t52_descr");
$clrotulo->label("t52_dtaqu");
$clrotulo->label("t52_numcgm");

$clrotulo->label("t64_descr");
$clrotulo->label("t64_class");
$clrotulo->label("t64_codcla");

$clrotulo->label("t04_sequencial");
$clrotulo->label("z01_nome_convenio");

$clrotulo->label("t53_codbem");
$clrotulo->label("t53_ntfisc");
$clrotulo->label("t53_empen");
$clrotulo->label("t53_ordem");
$clrotulo->label("t53_garant");

$clrotulo->label("t44_vidautil");

$clrotulo->label("t44_valoratual");

$clrotulo->label("t54_codbem");
$clrotulo->label("t54_idbql");
$clrotulo->label("t54_obs");

$clrotulo->label("t54_endereco");
$clrotulo->label("t54_valor_terreno");
$clrotulo->label("t54_valor_area");
$clrotulo->label("t54_valor_total");
$clrotulo->label("t54_limites_confrontacoes");
$clrotulo->label("t54_aplicacao");
$clrotulo->label("t54_prop_anterior");
$clrotulo->label("t54_cpfcnpj");
$clrotulo->label("t54_cartorio_tc");
$clrotulo->label("t54_comarca_tc");
$clrotulo->label("t54_registro_tc");
$clrotulo->label("t54_livro_tc");
$clrotulo->label("t54_folha_tc");
$clrotulo->label("t54_data_tc");
$clrotulo->label("t54_cartorio_tp");
$clrotulo->label("t54_tabeliao_tp");
$clrotulo->label("t54_livro_tp");
$clrotulo->label("t54_folha_tp");
$clrotulo->label("t54_data_tp");
$clrotulo->label("t54_escritura_tp");
$clrotulo->label("t54_carta_tp");


$clrotulo->label("t56_situac");
$clrotulo->label("t70_descr");

$clrotulo->label("pc01_descrmater");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
$clrotulo->label("e60_numemp");

$oDataAtual   = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
$oInstituicao = new Instituicao(db_getsession("DB_instit"));

$lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);
$integracao = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);
?>

<fieldset style="text-align: left;" id="fieldsetBensNovo">
  <legend class="bold">Inclus�o de Bens</legend>
  <form id='form1' name="form1" method="post" action="">
    <fieldset style="border: none; border-top: 2px groove #FFF;">
      <legend class="bold">Informa��es do Bem</legend>
      <table>
        <tr>
          <td title="<?php echo $Tt52_bem; ?>"><?php echo $Lt52_bem; ?>
          </td>
          <td>
            <?php
            db_input('t52_bem', 10, $It52_bem, true, "text", 3, "");
            ?>
          </td>
          <td id='placa' title="<?php echo $Tt41_placa; ?>">
            <?php echo $Lt41_placa; ?>
          </td>
          <td>
            <?php
            $iOpcaoSequencialPlaca = 3;
            $iOpcaoPlaca           = 3;

            $oBensParametroPlaca = BensParametroPlaca::getInstance();

            /**
             * Liberar para digita��o sequencial da placa quando parametro de controle for 4 - sequencial digitado
             */
            if ($oBensParametroPlaca->getTipoConfiguracaoPlaca() == BensParametroPlaca::PLACA_SEQUENCIAL_DIGITADO) {
              $iOpcaoSequencialPlaca = 1;
            }

            /**
             * Liberar para digita��o placa quando parametro de controle de placa for 3 - texto + sequencial
             */
            if ($oBensParametroPlaca->getTipoConfiguracaoPlaca() == BensParametroPlaca::PLACA_TEXTO_SEQUENCIAL) {
              $iOpcaoPlaca = 1;
            }

            db_input(
              'sPlaca',
              10,
              "",
              true,
              "text",
              $iOpcaoPlaca,
              "onblue='js_ValidaMaiusculo(this,'t',event);'",
              "",
              "",
              "text-transform: uppercase;"
            );
            db_input('t41_placa', 10, "", true, "text", $iOpcaoSequencialPlaca, "onkeypress='return SomenteNumero(event);'");
            ?>
          </td>
          <td colspan="3"><label style="font-weight: bold;"> Placa Impressa: </label> <label id='impressa' style="font-weight: bold;"> N�o </label>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tt52_dtaqu ?>"><?php echo $Lt52_dtaqu; ?></td>
          <td colspan="1">
            <?php
            db_inputdata('t52_dtaqu', @$t52_dtaqu_dia, @$t52_dtaqu_mes, @$t52_dtaqu_ano, true, 'text', 1, "");
                        
            ?>
          </td>
          <td><label id='contabilizador' style="font-weight: bold;">O bem a ser inserido possui dispensa de tombamento:</label> 
          </td>
          <td > <?
              $bloquear = $db_opcao;
              if($db_opcao == 2)
                  $bloquear = 3;

              $contabilizado = array(''=>'Selecione', 'sim'=>'Sim', 'nao'=>'N�o');
              db_select('contabilizado', $contabilizado, true, $bloquear, 'style="width: 90px"');
              
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?php echo $Tt52_descr; ?>"><?php echo $Lt52_descr; ?></td>
          <td colspan="6">
            <?php
            //db_input('t52_descr',81,$It52_descr,true,'text',$db_opcao);
            db_textarea('t52_descr', 5, 78, "", true, "text", $db_opcao, "onkeyup='limitaTextarea(this);'");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?php echo @$Tt64_class; ?>">
            <?php
            db_ancora(@$Lt64_class, "js_pesquisaClasse(true);", (($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao));
            ?>
          </td>
          <td colspan="6">
            <?php
            db_input('t64_codcla', 10, "", true, 'hidden', $db_opcao);
            db_input('t64_class', 10, $It64_class, true, 'text', (($db_opcao == 2 && $lPossuiIntegracaoPatrimonial) ? 3 : $db_opcao), "onchange='js_pesquisaClasse(false);'");
            db_input('t64_descr', 67, $It64_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?php echo @$Tt52_numcgm; ?>" id="tdFornecedor">
            <?php
            db_ancora(@$Lt52_numcgm, "js_pesquisaFornecedor(true);", $db_opcao);
            ?>
          </td>
          <td colspan="6">
            <?php
            //js_pesquisat52_numcgm()
            db_input('t52_numcgm', 10, $It52_numcgm, true, 'text', $db_opcao, "onchange='js_pesquisaFornecedor(false);'");
            db_input('z01_nome', 67, $Iz01_nome, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?php echo @$Tt45_sequencial; ?>">
            <?php
            db_ancora(@$Lt45_descricao, "js_pesquisaTipoAquisicao(true);", $db_opcao);
            ?>
          </td>
          <td colspan="6">
            <?php
            db_input(
              't45_sequencial',
              10,
              $It45_sequencial,
              true,
              'text',
              $db_opcao,
              "onchange='js_pesquisaTipoAquisicao(false);'"
            );
            db_input('t45_descricao', 67, $It45_descricao, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr id='orgao' style="display: none">
          <td><b>Org�o:</b></td>
          <td colspan="5">
            <?php
            db_input('o40_descr', 81, "", true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr id='unidade' style="display: none">
          <td><b>Unidade:</b></td>
          <td colspan="5">
            <?php
            db_input('o41_descr', 81, "", true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?php echo @$Tt52_depart; ?>">
            <?php
            db_ancora(@$Lt52_depart, "js_pesquisaDepartamento(true);", $db_opcao);
            ?>
          </td>
          <td colspan="3">
            <?php
            db_input('t52_depart', 10, $It52_depart, true, 'text', $db_opcao, "onchange='js_pesquisaDepartamento(false);'");
            db_input('descrdepto', 40, $Idescrdepto, true, 'text', 3, '');
            ?>
          </td>
          <td id="l-divisao" style="display: none;" title="<?php echo $Tt52_dtaqu; ?>"><b>Divis�o</b>
          </td>
          <td id="c-divisao" style="display: none;">
            <?php
            $x = array("0" => "Selecione");
            db_select('divisao', $x, true, $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="Conv�nio">
            <?php
            db_ancora("<b>Conv�nio</b>", "js_pesquisaConvenio(true);", $db_opcao);
            ?>
          </td>
          <td nowrap="nowrap" colspan="6">
            <?php
            db_input('t04_sequencial', 10, $It04_sequencial, true, 'text', $db_opcao, "onchange='js_pesquisaConvenio(false);'");
            db_input('z01_nome_convenio', 67, '', true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="<?php echo @$Tt56_situac; ?>">
            <?php
            db_ancora(@$Lt56_situac, "js_pesquisaSituacaoBem(true);", 1);
            ?>
          </td>
          <td nowrap="nowrap" colspan="6">
            <?php
            db_input('t56_situac', 10, $It56_situac, true, 'text', 1, " onchange='js_pesquisaSituacaoBem(false);'");
            db_input('t70_descr', 67, $It70_descr, true, 'text', 3, '');
            db_input("tipo_inclui", 40, "0", true, "hidden", 3, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset style="border: none; border-top: 2px groove #FFF;">
      <legend class="bold">Dados Financeiros</legend>
      <table>
        <tr>
          <td><b>Valor de Aquisi��o:</b></td>
          <td>
            <?php
            db_input(
              'vlAquisicao',
              10,
              $It64_descr,
              true,
              'text',
              $db_opcao,
              'onchange = "js_calculaValorTotal();"
                        onkeypress="return js_mask(event, \'0-9|.\')"'
            );
            ?>
          </td>
          <td style="text-align: right;"><b>Valor de Residual:</b>
          </td>
          <td style="text-align: left;">
            <?php
            db_input(
              'vlResidual',
              10,
              $It64_descr,
              true,
              'text',
              $db_opcao,
              ' onchange = "js_calculaValorTotal()"
                        onkeypress="return js_mask(event, \'0-9|.\')"'
            );
            ?>
          </td>
          <td style="text-align: right;"><b>Valor Depreci�vel:</b>
          </td>
          <td>
            <?php
            db_input('vlTotalDepreciavel', 10, $It64_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Valor Atual:</b>
          </td>
          <td>
            <?php
            db_input('vlTotal', 10, $It64_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title="" id='tdLookupTipoDepreciacao'><b>
              <?php
              db_ancora("<b>Tipo Deprecia��o:</b>", "js_pesquisaTipoDepreciacao(true);", $db_opcao, "", "linkLookupTipoDepreciacao");
              ?>
            </b>
          </td>
          <td nowrap="nowrap" colspan="3">
            <?php
            db_input('cod_depreciacao', 10, $It64_class, true, 'text', $db_opcao, "onchange='js_pesquisaTipoDepreciacao(false);'");
            db_input('descr', 40, $It64_descr, true, 'text', 3, '');
            ?>
          </td>
          <td nowrap="nowrap" title="Vida util do bem em anos.">
            <b>Vida Util:</b>
          </td>
          <td title="Vida util do bem em anos.">
            <?php
            db_input('vidaUtil', 10, $It44_vidautil, true, 'text', $db_opcao, '');
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset id='outros-dados'>
      <legend class='bold'>Outros Dados</legend>
      <table style="width: 100%">
        <tr>
          <td><b>Medida:</b></td>
          <td colspan="5">
            <?php
            $rsBensMedida = $clBensMedida->sql_record($clBensMedida->sql_query());
            db_selectrecord('t67_sequencial', $rsBensMedida, 'true', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td style="width: ">
            <b>Modelo:</b>
          </td>
          <td colspan="5">
            <?php
            $rsBensModelo = $clBensModelo->sql_record($clBensModelo->sql_query());
            db_selectrecord('t66_sequencial', $rsBensModelo, 'true', $db_opcao, "");
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Marca:</b></td>
          <td colspan="5">
            <?php
            $rsBensMarca = $clBensMarca->sql_record($clBensMarca->sql_query());
            db_selectrecord('t65_sequencial', $rsBensMarca, 'true', $db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset id="dadosdomaterial">
	      <legend class="bold" onclick="js_mostraToogleDadosMaterial();">Dados do Material</legend>
	      <table>
          <?php 
            $bloquear = 1;
          ?>
	        <tr>
	          <td><b>Nota Fiscal:</b></td>
	          <td><? db_input('cod_notafiscal', 43, $Icod_notafiscal,true, 'text', $bloquear) ?></td>
	        </tr>
	        <tr>
	          <td><b>Empenho do Sistema</b></td>
	          <td>
              <?php
                $aEmp_sistema = array('s' => 'SIM', 'n' => 'N�O');
                db_select("emp_sistema_select_descr", $aEmp_sistema, true, $bloquear,"", "", "", "0", "");
                ?>
            </td>
	        </tr>
          <tr>
          <td nowrap title="<?=@$Te60_numemp?>" id="tdAncoraEmpenho">
              <label style="font-weight: bold;" id='procAdm'>
                <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",$bloquear); ?>
              </label>
            </td>
            <td>
              <?
                db_input('t53_empen',10,$Ie60_numemp,true,'text',$bloquear," onchange='js_pesquisae60_numemp(false);'","",$cor);
                echo "<span id='procSis'>";
                db_input('z01_nome_empenho',30,$Iz01_nome_empenho,true,'text',3,"");
                echo "</span>";
              ?>
            </td>
          </tr>
	        <tr>
	          <td><b>Ordem de compra:</b></td>
	          <td><? db_input('cod_ordemdecompra', 10, '', true, 'text', $bloquear, "") ?></td>
	        </tr>
	        <tr>
	          <td><b>Garantia:</b></td>
	          <td><? db_inputdata('garantia', '', '', '', true, 'text', $db_opcao, ""); ?></td>
	        </tr>
	      </table>
	  </fieldset>
    <fieldset id='observacoes'>
      <legend class='bold'>Observa��es</legend>
      <?php
      db_textarea('obser', 5, 98, "", true, "text", 2);
      ?>
    </fieldset>
    <?php
    db_input("iCodigoItemNota", 10, false, true, 'hidden', 3);
    db_input("lEmpenhoVinculado", 10,$lEmpenhoVinculado, true, 'hidden', 1,"");
    ?>
  </form>
</fieldset>
<div>

  <br />

  <input name="<?php echo ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="button" id="db_opcao" value="<?php echo ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" onclick="salvarDados();" />

  <?php if ($db_opcao != 1) : ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
  <?php endif; ?>

  <?php if ($db_opcao == 2) { ?>
    <input name="novo" type="button" id="novo" value="Novo" onclick="parent.location.href='pat1_bens001.php';" />
  <?php
  } else {
  ?>
    <input name="importar" type="button" id="importar" value="Importa��o" onClick="js_pesquisa();">
    <input name="novo" type="button" id="novo" value="Novo" onclick="parent.location.href='pat1_bens001.php';" style="display: none;" />
  <?php
  }
  ?>
</div>

<script type="text/javascript">
  // Essa vari�vel � = ao par�metro cfpatriplaca.t07_confplaca
  $("form1").reset();
  var iParametro;
  var dbOpcao = <?php echo $db_opcao; ?>;
  var iParametroPlaca = null;
  var bemComCalculo = false;
  var lPossuiIntegracaoPatrimonial = false;
  var integracao = "<?php print $integracao; ?>";
  if(integracao){   
      $("contabilizado").style.display = "table-row";
      $("contabilizador").style.display = "table-row";
  }
  /**
   * Fun��o chamada ao iniciar
   */
  function js_carregaDadosForm(iDbOpcao) {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "carregaInclusao";
    oObject.dbOpcao = iDbOpcao;

    if (iDbOpcao != 1) {

      $("sPlaca").setAttribute('readonly', 'readonly');
      js_pesquisa();
    }
    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      asynchronous: false,
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoBusca
    });
  }
  /**
   * Retorno do js_carregaDadosForm
   */
  function js_retornoBusca(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");

    if (oRetorno.status == 2 && oRetorno.dados.parametro == 1) {

      alert(oRetorno.message.urlDecode());
    } else {

      iParametroPlaca = oRetorno.dados.parametro;
      dbOpcao = oRetorno.dbOpcao;

      if (oRetorno.dbOpcao == 1 && iParametroPlaca != 3) {
        js_Inclusao(oRetorno.dados);
      }

      if (iParametroPlaca == 3) {
        js_buscaPlacaString();
      }
    }
  }
  /**
   * Formularo em modo Inclus�o
   */
  function js_Inclusao(oDados) {

    if (oDados.bloqueia) {
      $("sPlaca").style.display = "none";
    }

    switch (oDados.parametro) {

      case '1':

        $("t41_placa").setAttribute("readonly", "readonly");
        $("t41_placa").value = oDados.t41_placa;
        break;
    }

    $("impressa").innerHTML = "N�o";
    if (oDados.lImpressa) {
      $("impressa").innerHTML = "Sim";
    }
  }

  function js_buscaPlacaString() {

    var oPlaca = $("placa");
    var a = new Element('a', {
      'class': 'ancora',
      href: '#',
      onclick: "js_pesquisaPlacaString(true)"
    }).update("Placa:");
    oPlaca.innerHTML = "";
    oPlaca.appendChild(a);

    //$("sPlaca").setAttribute("onchange", "buscaPlacaStringDigitda(this.value)");
  }

  $("sPlaca").observe('change', js_buscaPlacaStringDigitda);

  function js_buscaPlacaStringDigitda() {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "buscaPlacaString";
    oObject.sPlaca = $F("sPlaca");
    oObject.iParametro = iParametroPlaca;
    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoPlacaStringDigitda
    });
  }

  function js_retornoPlacaStringDigitda(oJson) {
    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");

    if (oRetorno.status == 1) {
      $("t41_placa").value = oRetorno.dados.t41_placa;
    }
  }

  /** ***********************************************************************************************************
   *
   */
  function js_pesquisaPlacaString() {
    js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_bensplaca',
      'func_bensplacatext.php?funcao_js=parent.js_mostratext|t41_placa', 'Pesquisa', true);
  }

  function js_mostratext(placa) {

    db_iframe_bensplaca.hide();
    js_buscplaca(placa);
  }

  function js_buscplaca(classif) {
    js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_bp', 'pat1_retseqplaca.php?classif=' + classif, '', false);
  }

  function js_retplaca(placa, seq) {

    $("sPlaca").value = placa;
    $("t41_placa").value = seq;
    $("t41_placa").setAttribute("readonly", "readonly");
  }


  /** ***********************************************************************************************************
   * Fun��o de Pesquisa da classe
   */
  function js_pesquisaClasse(mostra) {

    if (mostra) {
      js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_clabens',
        'func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr|' +
        't64_codcla|t64_benstipodepreciacao|t46_descricao|t64_vidautil&analitica=true',
        'Pesquisa', true);
    } else {

      testa = new String($F("t64_class"));

      if (testa != '' && testa != 0) {

        i = 0;
        for (i = 0; i < $("t64_class").value.length; i++) {
          testa = testa.replace('.', '');
        }
        js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_clabens',
          'func_clabens.php?pesquisa_chave=' + testa + '&funcao_js=parent.js_mostraclabens&analitica=true',
          'Pesquisa', false);
      } else {

        if (iParametro == 2 && dbOpcao == 1) {
          $("t64_class").value = "";
        }
        $("t64_descr").value = '';
      }
    }
  }

  function js_mostraclabens(chave, erro, chave2, iCodigoDepreciacao, sDescricaoDepreciacao, iVidaUtil) {

    $("t64_descr").value = chave;
    $("t64_codcla").value = chave2;
    if (erro) {

      $("t64_class").value = "";
      $("t64_class").focus();
      $("t64_codcla").value = "";
    } else {

      if (!bemComCalculo) {

        $("vidaUtil").value = iVidaUtil;
        $("descr").value = sDescricaoDepreciacao;
        $("cod_depreciacao").value = iCodigoDepreciacao;
      }
      if (iParametroPlaca == 2 && dbOpcao == 1) {
        js_buscaPlaca($F("t64_class"));
      }
    }
  }

  function js_mostraclabens1(chave1, chave2, chave3, iCodigoDepreciacao, sDescricaoDepreciacao, iVidaUtil) {

    $("t64_class").value = chave1;
    $("t64_descr").value = chave2;
    $("t64_codcla").value = chave3;
    if (!bemComCalculo) {

      $("vidaUtil").value = iVidaUtil;
      $("descr").value = sDescricaoDepreciacao;
      $("cod_depreciacao").value = iCodigoDepreciacao;
    }
    db_iframe_clabens.hide();
    if (iParametroPlaca == 2 && dbOpcao == 1) {
      js_buscaPlaca($F("t64_class"));
    }
  }

  function js_buscaPlaca(iClasse) {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "carregaPlacaClasse";
    oObject.iClasse = iClasse;
    oObject.iParametro = iParametroPlaca;
    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoPlaca
    });


  }
  /**
   * Retorno do js_carregaDadosForm
   */
  function js_retornoPlaca(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");

    if (oRetorno.status == 2) {
      alert(oRetorno.message);
    } else {

      if (oRetorno.dados.bloqueia) {
        $("sPlaca").style.display = "none";
      }

      switch (oRetorno.dados.parametro) {

        case '2':

          var sPlacaClasse = new String($F("t64_class"));
          $("t41_placa").setAttribute("readonly", "readonly");
          $("t41_placa").value = sPlacaClasse + "" + oRetorno.dados.t41_placa;
          $("sPlaca").value = sPlacaClasse
          break;
      }

      $("impressa").innerHTML = "N�o";
      if (oDados.lImpressa) {
        $("impressa").innerHTML = "Sim";
      }
    }


  }


  /** ***********************************************************************************************************
   * Fun��o de Pesquisa do Fornecedor
   */
  function js_pesquisaFornecedor(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_forne',
        'func_nome.php?funcao_js=parent.js_mostraforne1|z01_numcgm|z01_nome', 'Pesquisa', true);
    } else {

      if (document.form1.t52_numcgm.value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_forne',
          'func_nome.php?pesquisa_chave=' + document.form1.t52_numcgm.value + '&funcao_js=parent.js_mostraforne',
          'Pesquisa', false);
      } else {
        $("z01_nome").value = '';
      }
    }
  }

  function js_mostraforne(erro, chave) {

    $("z01_nome").value = chave;
    if (erro == true) {
      $("t52_numcgm").focus();
      $("t52_numcgm").value = '';
    }
  }

  function js_mostraforne1(chave1, chave2) {

    $("t52_numcgm").value = chave1;
    $("z01_nome").value = chave2;
    db_iframe_forne.hide();
  }

  /** ***********************************************************************************************************
   * Fun��o de Pesquisa da Aquisi��o
   */
  function js_pesquisaTipoAquisicao(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_aquisicao',
        'func_benstipoaquisicao.php?funcao_js=parent.js_mostraAquisicao1|t45_sequencial|t45_descricao', 'Pesquisa', true);
    } else {

      if ($F("t45_sequencial") != '') {
        js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_aquisicao',
          'func_benstipoaquisicao.php?pesquisa_chave=' + $F("t45_sequencial") + '&funcao_js=parent.js_mostraAquisicao',
          'Pesquisa', false);
      } else {
        $("t45_descricao").value = '';
      }
    }
  }

  function js_mostraAquisicao(chave, erro) {

    $("t45_descricao").value = chave;
    if (erro == true) {
      $("t45_sequencial").focus();
      $("t45_sequencial").value = '';
    }

  }

  function js_mostraAquisicao1(chave1, chave2) {

    $("t45_sequencial").value = chave1;
    $("t45_descricao").value = chave2;
    db_iframe_aquisicao.hide();

  }

  /** ***********************************************************************************************************
   * Fun��o de Pesquisa do Departamento
   */
  function js_pesquisaDepartamento(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_bens',
        'db_iframe_db_depart',
        'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto', 'Pesquisa', true);
    } else {

      if (document.form1.t52_depart.value != '') {
        js_OpenJanelaIframe('top.corpo.iframe_bens',
          'db_iframe_db_depart',
          'func_db_depart.php?pesquisa_chave=' + $F("t52_depart") + '&funcao_js=parent.js_mostradb_depart',
          'Pesquisa', false);
      } else {
        $("descrdepto").value = '';
      }
    }
  }

  function js_mostradb_depart(chave, erro) {

    $("descrdepto").value = chave;
    if (erro == true) {

      $("t52_depart").focus();
      $("t52_depart").value = '';
    } else {

      js_setOrgaoUnidade($F("t52_depart"));
      js_liberaDivisao($F("t52_depart"));
    }
  }

  function js_mostradb_depart1(chave1, chave2) {

    $("t52_depart").value = chave1;
    $("descrdepto").value = chave2;
    db_iframe_db_depart.hide();
    js_liberaDivisao(chave1);
    js_setOrgaoUnidade(chave1);
  }

  /** ***********************************************************************************************************
   * Busca Orgao/Unidade, se o Departamento tiver divis�o
   */
  function js_setOrgaoUnidade(iDepartamento) {
    js_carregaOrgaoUnidade(iDepartamento);
  }

  function js_carregaOrgaoUnidade(iDepartamento) {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "buscaOrgaoUnidade";
    oObject.departamento = iDepartamento;

    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando_divisao'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoOrgaoUnidade
    });
  }

  function js_retornoOrgaoUnidade(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");
    $("orgao").style.display = "none";
    $("unidade").style.display = "none";
    if (oRetorno.status == 2) {

      alert(oRetorno.message);
    } else {

      if (oRetorno.dados.libera == "t") {

        $("orgao").style.display = "table-row";
        $("unidade").style.display = "table-row";
        $("o40_descr").value = oRetorno.dados.o40_descr.urlDecode();
        $("o41_descr").value = oRetorno.dados.o41_descr.urlDecode();
      }
    }
  }

  /** ***********************************************************************************************************
   * Busca Divis�o, se o Departamento tiver divis�o
   */
  function js_liberaDivisao(iDepartamento) {
    js_carregaDadosDivisao(iDepartamento);
  }

  function js_carregaDadosDivisao(iDepartamento) {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "buscaDivisao";
    oObject.departamento = iDepartamento;

    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando_divisao'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      asynchronous: false,
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoBuscaDivisao
    });
  }

  function js_retornoBuscaDivisao(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");

    $("l-divisao").style.display = "none";
    $("c-divisao").style.display = "none";
    $("divisao").options.length = 1;

    if (oRetorno.departamento.length > 0) {

      for (var i = 0; i < oRetorno.departamento.length; i++) {

        var oOption = new Element('option', {
          'value': '' + oRetorno.departamento[i].t30_codigo + ''
        }).
        update(oRetorno.departamento[i].t30_descr.urlDecode());
        $("divisao").appendChild(oOption);
        $("divisao").style.width = "100px";
      }
      $("l-divisao").style.display = "table-cell";
      $("c-divisao").style.display = "table-cell";
    }
  }

  /** ***********************************************************************************************************
   * Fun��o de Pesquisa do Convenio
   */
  function js_pesquisaConvenio(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_bens',
        'db_iframe_benscadcedente',
        'func_benscadcedente.php?funcao_js=parent.js_mostraconvenio1|t04_sequencial|z01_nome',
        'Pesquisa', true);
    } else {

      if ($F("t04_sequencial") != '') {
        js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_benscadcedente',
          'func_benscadcedente.php?pesquisa_chave=' + $F("t04_sequencial") + '&funcao_js=parent.js_mostraconvenio',
          'Pesquisa', false);
      } else {
        $("z01_nome_convenio").value = '';
      }
    }
  }

  function js_mostraconvenio(chave, erro) {

    $("z01_nome_convenio").value = chave;
    if (erro == true) {

      $("t04_sequencial").focus();
      $("t04_sequencial").value = '';
    }
  }

  function js_mostraconvenio1(chave1, chave2) {

    $("t04_sequencial").value = chave1;
    $("z01_nome_convenio").value = chave2;
    db_iframe_benscadcedente.hide();
  }

  /** ***********************************************************************************************************
   * Fun��o de Pesquisa do SituacaoBem
   */
  function js_pesquisaSituacaoBem(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_situabens',
        'func_situabens.php?funcao_js=parent.js_mostrasituabens1|t70_situac|t70_descr', 'Pesquisa', true);
    } else {

      if ($F("t56_situac") != '') {
        js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_situabens',
          'func_situabens.php?pesquisa_chave=' + $F("t56_situac") + '&funcao_js=parent.js_mostrasituabens',
          'Pesquisa', false);
      } else {
        $("t70_descr").value = '';
      }
    }
  }

  function js_mostrasituabens(chave, erro) {

    $("t70_descr").value = chave;
    if (erro == true) {

      $("t56_situac").focus();
      $("t56_situac").value = '';
    }
  }

  function js_mostrasituabens1(chave1, chave2) {

    $("t56_situac").value = chave1;
    $("t70_descr").value = chave2;
    db_iframe_situabens.hide();
  }

  /** ***********************************************************************************************************
   * Fun��o de Pesquisa do TipoDepreciacao
   */
  function js_pesquisaTipoDepreciacao(mostra) {

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_deprecBem',
        'func_benstipodepreciacao.php?funcao_js=parent.js_mostraDepreciacao1|t46_sequencial|t46_descricao&limita=true', 'Pesquisa', true);
    } else {

      if ($F("cod_depreciacao") != '') {
        js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_deprecBem',
          'func_benstipodepreciacao.php?pesquisa_chave=' + $F("cod_depreciacao") + '&limita=true&funcao_js=parent.js_mostraDepreciacao',
          'Pesquisa', false);
      } else {
        $("descr").value = '';
      }
    }
  }

  function js_mostraDepreciacao(chave, erro) {
    $("descr").value = chave;
    if (erro == true) {

      $("cod_depreciacao").focus();
      $("cod_depreciacao").value = '';
    }
  }

  function js_mostraDepreciacao1(chave1, chave2) {

    $("cod_depreciacao").value = chave1;
    $("descr").value = chave2;
    db_iframe_deprecBem.hide();
  }

  /** ***********************************************************************************************************
   * Salva os Dados do Formul�rio
   */
  function salvarDados() {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "salvar";
    if ($F("t41_placa").trim() == '') {

      alert(_M("patrimonial.patrimonio.db_frm_bensnovo.informe_placa_bem"));
      return false;
    }
    
    oObject.t52_bem = $F("t52_bem");
    oObject.sPlaca = $F("sPlaca");
    oObject.t41_placa = $F("t41_placa");
    oObject.t52_dtaqu = $F("t52_dtaqu");
    oObject.t52_descr = encodeURIComponent(tagString($F("t52_descr")));
    oObject.t64_codcla = $F("t64_codcla");
    oObject.t52_numcgm = $F("t52_numcgm");
    oObject.t45_sequencial = $F("t45_sequencial");
    oObject.t52_depart = $F("t52_depart");
    oObject.divisao = $F("divisao");
    oObject.t04_sequencial = $F("t04_sequencial");
    oObject.t56_situac = $F("t56_situac");
    oObject.vlAquisicao = $F("vlAquisicao");
    oObject.vlResidual = $F("vlResidual");
    oObject.vlTotal = $F("vlTotal"); //valor depreciavel
    oObject.cod_depreciacao = $F("cod_depreciacao");
    oObject.vidaUtil = $F("vidaUtil");
    oObject.t67_sequencial = $F("t67_sequencial");
    oObject.t66_sequencial = $F("t66_sequencial");
    oObject.t65_sequencial = $F("t65_sequencial");
    oObject.iCodigoItemNota = $F("iCodigoItemNota");
    oObject.cod_notafiscal    = $F("cod_notafiscal");
    oObject.cod_ordemdecompra = $F("cod_ordemdecompra");
    oObject.emp_sistema       = $F("emp_sistema_select_descr");
    oObject.t53_empen         = $F("t53_empen");
    oObject.garantia          = $F("garantia");
    oObject.contabilizado = encodeURIComponent(tagString($F("contabilizado")));
 
    oObject.obser = encodeURIComponent(tagString($F("obser")));
    oObject.acao = encodeURIComponent(tagString("Incluir"));
    if ($F("db_opcao") == "Alterar") {
      oObject.acao = encodeURIComponent(tagString("Alterar"));
    }
    
    if ($F("contabilizado").trim() == "") {
      alert("Campo Bem j� contabilizado via empenho obrigat�rio.");
      return false;
    }

    if ($F("obser").trim() == "") {
      alert("Campo observa��es obrigat�rio.");
      return false;
    }

    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoSalvar
    });
  }

  /**
   * Retorno do js_carregaDadosForm
   */

  function js_retornoSalvar(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");

    if (oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode().replace(/\\n/g, "\n"));

    } else {

      $('t41_placa').disabled = true;
      alert(_M('patrimonial.patrimonio.db_frm_bensnovo.bem_salvo'));

      var statusbens = oRetorno.statusbens;
      statusAPI(statusbens);
      
      $('t52_bem').value = oRetorno.dados.t52_bem;
      $('db_opcao').value = 'Alterar';
      this.importar.style.display = 'none';
      $("novo").style.display = "";
      js_liberarAbas();

      if (oRetorno.clabens == 2) {
        parent.mo_camada('bensimoveis');

      } else {
        parent.mo_camada('bensfotos');

      }


    }
  }

  function js_pesquisa() {

    var url = "func_bens.php?funcao_js=parent.js_preenchepesquisa|t52_bem";
    js_OpenJanelaIframe('top.corpo.iframe_bens', 'db_iframe_bens', url, 'Pesquisa', true);
  }

  function js_preenchepesquisa(t52_bem) {

    db_iframe_bens.hide();
    js_pesquisaBem(t52_bem);
  }

  /**
   * Fun��o chamada ao iniciar
   */
  function js_pesquisaBem(iCodigoBem) {

    var url = 'pat1_bensnovo.RPC.php';
    var oObject = new Object();
    oObject.exec = "buscaBem";
    oObject.dbOpcao = dbOpcao;
    oObject.iCodigoBem = iCodigoBem;
    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.buscando'), 'msgBox');
    var objAjax = new Ajax.Request(url, {
      method: 'post',
      asynchronous: false,
      parameters: 'json=' + Object.toJSON(oObject),
      onComplete: js_retornoBuscaBem
    });
  }

  var sLinkLookupTipoDepreciacao = $('tdLookupTipoDepreciacao').innerHTML;

  /**
   * Retorno do js_carregaDadosForm
   */
  function js_retornoBuscaBem(oJson) {

    js_removeObj("msgBox");
    var oRetorno = eval("(" + oJson.responseText + ")");

    if (oRetorno.status == 2) {

      alert(oRetorno.message);
      return false;
    }

    lPossuiIntegracaoPatrimonial = oRetorno.lPossuiIntegracaoPatrimonial;

    /**
     * Se n�o tivermos no modo inclus�o, ele traz todos os valores.
     */
    if (dbOpcao != 1) {

      var iVidaUtil = "0";

      $("t41_placa").setAttribute('readonly', 'readonly');
      $("sPlaca").setAttribute('readonly', 'readonly');
      $("sPlaca").style.display = "none";
      $("t41_placa").value = oRetorno.dados.t52_ident;
      $("vlAquisicao").value = oRetorno.dados.vlAquisicao;
      $("vlResidual").value = oRetorno.dados.vlResidual;
      $("vlTotal").value = oRetorno.dados.vlTotal;
      $("vlTotalDepreciavel").value = oRetorno.dados.vlTotalDepreciavel;
      $("t52_bem").value = oRetorno.dados.t52_bem;
      $("t52_dtaqu").value = oRetorno.dados.t52_dtaqu;

      if (oRetorno.dados.vidaUtil != '') {
        iVidaUtil = oRetorno.dados.vidaUtil;
      }

      $("vidaUtil").value = iVidaUtil;

      if (oRetorno.dados.hasDepreciacao) {
        js_hasDepreciacaoBloqueiaCampos();
      }

    }

    $("t52_descr").value = oRetorno.dados.t52_descr.urlDecode();
    $("t64_codcla").value = oRetorno.dados.t64_codcla;
    $("t64_class").value = oRetorno.dados.t64_class.urlDecode();
    $("t64_descr").value = oRetorno.dados.t64_descr.urlDecode();
    $("t52_numcgm").value = oRetorno.dados.t52_numcgm;
    $("z01_nome").value = oRetorno.dados.z01_nome.urlDecode();
    $("t45_sequencial").value = oRetorno.dados.t45_sequencial;
    $("t45_descricao").value = oRetorno.dados.t45_descricao.urlDecode();
    $("t52_depart").value = oRetorno.dados.t52_depart;
    $("descrdepto").value = oRetorno.dados.descrdepto.urlDecode();
    $("t04_sequencial").value = oRetorno.dados.t04_sequencial;
    $("z01_nome_convenio").value = oRetorno.dados.z01_nome_convenio.urlDecode();
    $("t56_situac").value = oRetorno.dados.t56_situac;
    $("t70_descr").value = oRetorno.dados.t70_descr.urlDecode();
    $("cod_depreciacao").value = oRetorno.dados.cod_depreciacao;
    $("descr").value = oRetorno.dados.descr.urlDecode();
    $("t67_sequencial").value = oRetorno.dados.t67_sequencial;
    $("t66_sequencial").value = oRetorno.dados.t66_sequencial;
    $("t65_sequencial").value = oRetorno.dados.t65_sequencial;
    $("obser").value = oRetorno.dados.obser.urlDecode();
    js_ProcCod_t66_sequencial('t66_sequencial', 't66_sequencialdescr');
    js_ProcCod_t67_sequencial('t67_sequencial', 't67_sequencialdescr');
    js_ProcCod_t65_sequencial('t65_sequencial', 't65_sequencialdescr');

    $("l-divisao").style.display = "none";
    $("c-divisao").style.display = "none";
    $("divisao").setValue(0);
    $("divisao").options.length = 0;

    if (oRetorno.dados.divisao != "") {

      js_liberaDivisao(oRetorno.dados.t52_depart);
      js_setOrgaoUnidade(oRetorno.dados.t52_depart);
      $("l-divisao").style.display = "table-cell";
      $("c-divisao").style.display = "table-cell";
      $("divisao").setValue(oRetorno.dados.divisao);
    }
    getCodigoItemNaNota(oRetorno.dados.t52_bem);
    bemComCalculo = oRetorno.dados.bemComCalculo;
    js_controlaDadosFinanceiros(oRetorno.dados.bemComCalculo);
    js_liberarAbas();

  }

  function getCodigoItemNaNota(iCodigoBem) 
  { 

    js_divCarregando("Aguarde, verificando v�nculo do bem com empenho do sistema...", "msgBox");
    var oParam = {"exec":"buscarDadosMaterial", "iCodigoBem": iCodigoBem};
 
    new Ajax.Request("pat1_bensnovo.RPC.php",
                      {method: 'post',
                        parameters: 'json='+Object.toJSON(oParam),
                        onComplete: js_retornoBusDadosMat
                      });
  }

  function js_retornoBusDadosMat(oAjax)
  {

    js_removeObj("msgBox");

    var oRetorno = eval("("+oAjax.responseText+")");
  
    let [year, month, day] = oRetorno.t53_garant.split('-');
    let formattedDate = `${day}/${month}/${year}`;

    $("t53_empen").value         = oRetorno.t53_empen;
    $("cod_notafiscal").value    = oRetorno.t53_ntfisc.urlDecode();
    $("cod_ordemdecompra").value = oRetorno.t53_ordem;
    $("garantia").value          = formattedDate;
    $("lEmpenhoVinculado").value = oRetorno.lEmpenhoVinculado;

    if (oRetorno.lEmpenhoVinculado == true) {
      sCor = '#DEB887';
      $("t53_empen").disabled = true;
      $("t53_empen").style.backgroundColor = sCor;
      $("t53_empen").style.color = "#000";
      $("cod_notafiscal").disabled = true;
      $("cod_notafiscal").style.backgroundColor = sCor;
      $("cod_notafiscal").style.color = "#000";
      $("cod_ordemdecompra").disabled = true;
      $("cod_ordemdecompra").style.backgroundColor = sCor;
      $("cod_ordemdecompra").style.color = "#000";
      $("emp_sistema_select_descr").disabled = true;
      $("emp_sistema_select_descr").style.backgroundColor = sCor;
      $("emp_sistema_select_descr").style.color = "#000";

    } else {
        sCor = '';
        $("t53_empen").disabled = false;
        $("t53_empen").style.backgroundColor = sCor;
        $("t53_empen").style.color = "#000";
        $("cod_notafiscal").disabled = false;
        $("cod_notafiscal").style.backgroundColor = sCor;
        $("cod_notafiscal").style.color = "#000";
        $("cod_ordemdecompra").disabled = false;
        $("cod_ordemdecompra").style.backgroundColor = sCor;
        $("cod_ordemdecompra").style.color = "#000";
        $("emp_sistema_select_descr").disabled = false;
        $("emp_sistema_select_descr").style.backgroundColor = sCor;
        $("emp_sistema_select_descr").style.color = "#000";
    }
    
    js_pesquisae60_numemp(false);
  }


  function js_calculaValorTotal() {

    var vlAquisicao = new Number($F("vlAquisicao"));
    var vlResidual = new Number($F("vlResidual"));

    if (vlResidual > vlAquisicao) {

      alert(_M('patrimonial.patrimonio.db_frm_bensnovo.residual_maior_que_aquisicao'));
      $("vlResidual").value = "";
      $("vlAquisicao").focus();
      return false;
    } else {

      $("vlTotalDepreciavel").value = (vlAquisicao - vlResidual).toFixed(2);
      $("vlTotal").value = (vlAquisicao);
    }
  }

  function js_liberarAbas() {

    var iCodigoBem = $F('t52_bem');
    parent.document.formaba.bensimoveis.disabled = false;
    parent.document.formaba.bensmater.disabled = false;
    parent.document.formaba.bensfotos.disabled = false;
    CurrentWindow.corpo.iframe_bensimoveis.location.href = 'pat1_bensimoveis001.php?db_opcaoal=22&t54_codbem=' + iCodigoBem;
    CurrentWindow.corpo.iframe_bensmater.location.href = 'pat1_bensmater001.php?db_opcaoal=22&t53_codbem=' + iCodigoBem;
    CurrentWindow.corpo.iframe_bensfotos.location.href = 'pat1_cadgeralfotos001.php?db_opcaoal=22&t52_codbem=' + iCodigoBem;
  }

  function js_controlaDadosFinanceiros(lBloquear) {

    var sCor = 'white';
    $('tdLookupTipoDepreciacao').innerHTML = sLinkLookupTipoDepreciacao;
    if (lBloquear) {

      $('tdLookupTipoDepreciacao').innerHTML = "<b>" + $('linkLookupTipoDepreciacao').innerHTML + "<b>";
      sCor = '#DEB887';
    }

    $("vlAquisicao").classList.remove('readonly');
    $("vlResidual").classList.remove('readonly');

    /**
     * Caso seja altera��o bloqueia os campos vlr aquisi��o e vlr residual
     */
    if (dbOpcao != 1 && lPossuiIntegracaoPatrimonial) {

      $("vlAquisicao").disabled = true;
      $("vlResidual").disabled = true;

      $("vlAquisicao").classList.add('readonly');
      $("vlResidual").classList.add('readonly');

    } else {

      $("vlAquisicao").disabled = lBloquear;
      $("vlAquisicao").style.backgroundColor = sCor;
      $("vlResidual").disabled = lBloquear;
      $("vlResidual").style.backgroundColor = sCor;
    }

    $("vlTotal").disabled = lBloquear;
    $("vlTotal").style.backgroundColor = '#DEB887';
    $("cod_depreciacao").disabled = lBloquear;
    $("cod_depreciacao").style.backgroundColor = sCor;
    $("descr").disabled = lBloquear;
    $("vidaUtil").disabled = lBloquear;
    $("vidaUtil").style.backgroundColor = sCor;
    $('t52_dtaqu').style.backgroundColor = sCor;
    $('t52_dtaqu').disabled = lBloquear;
  }

  /**
   * Bloqueia campos se a deprecia��o j� foi inicialixada
   */
  function js_hasDepreciacaoBloqueiaCampos() {


    $("t52_dtaqu").setAttribute("readonly", "readonly");
    $("vlAquisicao").setAttribute("readonly", "readonly");
    $("vlResidual").setAttribute("readonly", "readonly");
    $("cod_depreciacao").setAttribute("readonly", "readonly");
    $("vidaUtil").setAttribute("readonly", "readonly");


    form1.dtjs_t52_dtaqu.removeAttribute("onclick");
    form1.dtjs_t52_dtaqu.setAttribute("disabled", "disabled");

    $("t52_dtaqu").style.backgroundColor = "#DEB887";
    $("vlAquisicao").style.backgroundColor = "#DEB887";
    $("cod_depreciacao").style.backgroundColor = "#DEB887";
    $("vidaUtil").style.backgroundColor = "#DEB887";
    $("vlResidual").style.backgroundColor = "#DEB887";

    if ($("linkLookupTipoDepreciacao") != null) {
      $("linkLookupTipoDepreciacao").removeAttribute("class");
      $("linkLookupTipoDepreciacao").style.color = "#000";
      $("linkLookupTipoDepreciacao").style.textDecoration = "none";
      $("linkLookupTipoDepreciacao").style.cursor = "default";
      $("linkLookupTipoDepreciacao").setAttribute("onClick", "return false;");
    }


  }

  function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58)) return true;
    else {
      if (tecla == 8 || tecla == 0) return true;
      else return false;
    }
  }
  function js_pesquisae60_numemp(mostra)
  {
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp|e60_codemp|e60_anousu|z01_nome','Pesquisa',true);
    }else{
      if(document.form1.t53_empen.value != ''){
          js_OpenJanelaIframe('','db_iframe_empempenho','func_empempenhopat.php?pesquisa_chave='+document.form1.t53_empen.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
      }else{
        document.form1.z01_nome_empenho.value = '';
        document.form1.t53_empen.value = '';
      }
    }
  }

  function js_mostraempempenho(chave,chave2,chave3,erro)
  {
    document.form1.z01_nome_empenho.value = chave;
    if(erro==true){
      document.form1.t53_empen.focus();
      document.form1.t53_empen.value = '';
    }
  }
  function js_mostraempempenho1(chave1,chave2,chave3,chave4)
  {
    document.form1.t53_empen.value = chave1;;
    document.form1.z01_nome_empenho.value = chave4;
    db_iframe_empempenho.hide();
  }

  /*Fun��o para limitar texaarea*/
  //"onkeyup='limitaTextarea(this.value);'");
  function limitaTextarea(valor) {

    var qnt = valor.value;
    quantidade = 250;
    total = qnt.length;

    if (total <= quantidade) {
      resto = quantidade - total;
      document.getElementById('contador').innerHTML = resto;
    } else {
      document.getElementById(valor.name).value = qnt.substr(0, quantidade);
      alert("O campo deve conter at� 250 caracteres");
    }
  }

  // Configura Form
  var oOutrosDados = new DBToogle($('outros-dados'), true);
  var oObservacoes = new DBToogle($('observacoes'), true);
  var oObservacoes = new DBToogle($('dadosdomaterial'), true);

  function statusAPI(statusbens)
  {
    if (statusbens == 'f') {
          return;
    }
    if (statusbens == 'Placa repetida') {
          alert(_M('patrimonial.patrimonio.db_frm_bensnovo.bem_placarep'));
          return;
    } 
    if (statusbens != 'success') {
      return alert(_M('patrimonial.patrimonio.db_frm_bensnovo.bem_falha'));
    } 
   

  }

  function js_mostraToogleDadosMaterial() 
  {

    if (oDadosImovel.isDisplayed()) {

      oDadosImovel.show(false);
    }
  }

  $("t67_sequencial").style.width = "50px";
  $("t66_sequencial").style.width = "50px";
  $("t65_sequencial").style.width = "50px";
  $("t67_sequencialdescr").style.width = "150px";
  $("t66_sequencialdescr").style.width = "150px";
  $("t65_sequencialdescr").style.width = "150px";
  $("emp_sistema_select_descr").style.width = "83px";
  $("lEmpenhoVinculado").focus();
</script>