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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_acordomovimentacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clacordo             = new cl_acordo;
$clacordomovimentacao = new cl_acordomovimentacao;

$db_opcao = 1;

$clacordo->rotulo->label();
$clacordomovimentacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac16_valorrescisao");
$clrotulo->label("ac10_datamovimento");
$clrotulo->label("ac10_obs");

// funcao do sql
function sql_query_file($c99_anousu = null, $c99_instit = null, $campos = "*", $ordem = null, $dbwhere = "")
{
  $sql = "select ";
  if ($campos != "*") {
    $campos_sql = split("#", $campos);
    $virgula = "";
    for ($i = 0; $i < sizeof($campos_sql); $i++) {
      $sql .= $virgula . $campos_sql[$i];
      $virgula = ",";
    }
  } else {
    $sql .= $campos;
  }
  $sql .= " from condataconf ";
  $sql2 = "";
  if ($dbwhere == "") {
    if ($c99_anousu != null) {
      $sql2 .= " where condataconf.c99_anousu = $c99_anousu ";
    }
    if ($c99_instit != null) {
      if ($sql2 != "") {
        $sql2 .= " and ";
      } else {
        $sql2 .= " where ";
      }
      $sql2 .= " condataconf.c99_instit = $c99_instit ";
    }
  } else if ($dbwhere != "") {
    $sql2 = " where $dbwhere";
  }
  $sql .= $sql2;
  if ($ordem != null) {
    $sql .= " order by ";
    $campos_sql = split("#", $ordem);
    $virgula = "";
    for ($i = 0; $i < sizeof($campos_sql); $i++) {
      $sql .= $virgula . $campos_sql[$i];
      $virgula = ",";
    }
  }
  return $sql;
}

$anousu = db_getsession('DB_anousu');

$sSQL = "select to_char(c99_datapat,'YYYY') c99_datapat
          from condataconf
            where c99_instit = " . db_getsession('DB_instit') . "
              order by c99_anousu desc limit 1";

$rsResult       = db_query($sSQL);
$maxC99_datapat = db_utils::fieldsMemory($rsResult, 0)->c99_datapat;

$sNSQL = "";
if ($anousu > $maxC99_datapat) {
  $sNSQL = sql_query_file($maxC99_datapat, db_getsession('DB_instit'), 'c99_datapat');
} else {
  $sNSQL = sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'), 'c99_datapat');
}

$result = db_query($sNSQL);
$c99_datapat = db_utils::fieldsMemory($result, 0)->c99_datapat;

?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
  <style>
    td {
      white-space: nowrap;
    }

    fieldset table td:first-child {
      width: 80px;
      white-space: nowrap;
    }
  </style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?= '<input id="c99_datapat_hidden" type="hidden" value="' . $c99_datapat . '">' ?>
  <table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
    <tr>
      <td valign="top" align="center">
        <fieldset>
          <legend><b>Rescis�o do Acordo</b></legend>
          <table align="center" border="0">
            <tr>
              <td title="<?= @$Tac16_sequencial ?>" align="left">
                <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);", $db_opcao); ?>
              </td>
              <td align="left">
                <?
                db_input(
                  'ac16_sequencial',
                  10,
                  $Iac16_sequencial,
                  true,
                  'text',
                  $db_opcao,
                  " onchange='js_pesquisaac16_sequencial(false);'"
                );
                ?>
              </td>
              <td align="left">
                <?
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td title="<?= @$Tac10_datamovimento ?>" align="left">
                <b>Data:</b>
              </td>
              <td align="left">
                <?
                db_inputdata(
                  'ac10_datamovimento',
                  @$ac10_datamovimento_dia,
                  @$ac10_datamovimento_mes,
                  @$ac10_datamovimento_ano,
                  true,
                  'text',
                  $db_opcao,
                  ""
                );
                ?>
              </td>
              <td>&nbsp;</td>
            </tr>

            <tr id="trdatareferencia" style="display: none;">
              <td title="<?= @$Tac10_datareferencia ?>" align="left">
                <b>Data de Refer�ncia:</b>
              </td>
              <td align="left">
                <?
                db_inputdata(
                  'ac10_datareferencia',
                  @$ac10_datareferencia_dia,
                  @$ac10_datareferencia_mes,
                  @$ac10_datareferencia_ano,
                  true,
                  'text',
                  $db_opcao,
                  ""
                );
                ?>
              </td>
              <td>&nbsp;</td>
            </tr>

            <tr>
              <td title="<?= @$Tac16_sequencial ?>" align="left">
                <b>Valor Rescindido R$:</b>
              </td>
              <td align="left" colspan="2">
                <?php db_input('ac16_valorrescisao', 10, $Iac16_valorrescisao, true, 'text', $db_opcao); ?>
              </td>
            </tr>

            <tr>
              <td colspan="3">
                <fieldset>
                  <legend>
                    <b>Observa��o</b>
                  </legend>
                  <?
                  db_textarea('ac10_obs', 5, 66, $Iac10_obs, true, 'text', $db_opcao, "");
                  ?>
                </fieldset>
              </td>
            </tr>
            <tr id="justificativa" style="display:none;">
              <td colspan="3">
                <fieldset>
                  <legend>
                    <b>Justificativa</b>
                  </legend>
                  <?
                  db_textarea('ac10_justificativa', 5, 66, $Iac10_justificativa, true, 'text', $db_opcao, "");
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
    <tr>
      <td align="center">
        <input id="incluir" name="incluir" type="button" value="Incluir" onclick="return js_recisaoContrato();">
      </td>
    </tr>
  </table>
  <?php
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>
<script>
  $('ac16_sequencial').style.width = "100%";
  $('ac16_resumoobjeto').style.width = "100%";

  var sUrl = 'con4_contratosmovimento.RPC.php';

  /**
   * Pesquisa acordos
   */
  function js_pesquisaac16_sequencial(lMostrar) {

    if (lMostrar == true) {

      var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4';
      js_OpenJanelaIframe('top.corpo',
        'db_iframe_acordo',
        sUrl,
        'Pesquisar Acordo',
        true);
    } else {

      if ($('ac16_sequencial').value != '') {

        var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave=' + $('ac16_sequencial').value +
          '&funcao_js=parent.js_mostraacordo&iTipoFiltro=4';

        js_OpenJanelaIframe('top.corpo',
          'db_iframe_acordo',
          sUrl,
          'Pesquisar Acordo',
          false);
      } else {
        $('ac16_sequencial').value = '';
      }
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo(chave1, chave2, erro) {

    if (erro == true) {

      $('ac16_sequencial').value = '';
      $('ac16_resumoobjeto').value = chave1;
      $('ac16_sequencial').focus();
    } else {

      $('ac16_sequencial').value = chave1;
      $('ac16_resumoobjeto').value = chave2;
    }
  }

  /**
   * Retorno da pesquisa acordos
   */
  function js_mostraacordo1(chave1, chave2) {
    var oParam = new Object();
    oParam.exec = "getleilicitacao";
    oParam.licitacao = chave1;

    var oAjax = new Ajax.Request(sUrl, {
      method: 'post',
      parameters: 'json=' + js_objectToJson(oParam),
      onComplete: js_retornoLeiLicitacao
    });
    $('ac16_sequencial').value = chave1;
    $('ac16_resumoobjeto').value = chave2;
    db_iframe_acordo.hide();
  }

  function js_retornoLeiLicitacao(oAjax) {



    var oRetorno = eval("(" + oAjax.responseText + ")");
    if(oRetorno.lei==1){
      $('justificativa').style.display = '';
    }else{
      $('justificativa').style.display = 'none';
    }
  }

  /**
   * Incluir assinatura para o contrato
   */
  function js_recisaoContrato() {

    if ($('ac16_sequencial').value == '') {

      alert('Acordo n�o informado!');
      return false;
    }

    if ($('ac10_datamovimento').value == '') {

      alert('Data n�o informada!');
      return false;
    }

    if ($('ac10_datareferencia').value == '' && document.getElementById("trdatareferencia").style.display == 'contents') {

      alert('Data de Refer�ncia n�o informada!');
      return false;
    }

    if ($('ac10_justificativa').value == '' && document.getElementById("justificativa").style.display != 'none') {

      alert('Usu�rio: Este contrato  decorrente de Licit��o e est� utilizando a lei n 14133/2021, sendo assim, � necess�rio o preenchimento do campo Justificativa.');
      return false;
    }

    /**
     * Verificar Encerramento Periodo Patrimonial
     */
    //    DATA INSERIDA
    var partesData = $('ac10_datamovimento').value.split("/");
    var data = new Date(partesData[2], partesData[1] - 1, partesData[0]);
    var dataInserida = data;

    //    DATA DO SISTEMA
    var partesData = $("c99_datapat_hidden").value.split("-");
    var data = new Date(partesData[0], partesData[1] - 1, partesData[2]);
    var dataPatrimonial = data;

    //    DATA DE REFERNCIA
    var partesData = $('ac10_datareferencia').value.split("/");
    var dataReferencia = new Date(partesData[2], partesData[1] - 1, partesData[0]);


    if (dataInserida <= dataPatrimonial && $('ac10_datareferencia').value == '') {
      document.getElementById("trdatareferencia").style.display = 'contents';
      alert("O per�odo j� foi encerrado para envio do SICOM. Preencha o campo Data de Refer�ncia com uma data no m�s subsequente.");
      return;
    }

    if ($F('ac10_datareferencia').value != "") {

      if (dataReferencia.getMonth() == dataPatrimonial.getMonth() && dataReferencia.getFullYear() == dataPatrimonial.getFullYear()) {
        alert("Usu�rio: A data de refer�ncia dever ser no m�s posterior ao m�s da data inserida.");
        return;
      }

      if (dataReferencia <= dataPatrimonial) {
        alert("O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
        return;
      }

    }


    js_divCarregando('Aguarde incluindo recis�o...', 'msgBoxRecisaoContrato');

    var oParam = new Object();
    oParam.exec = "rescindirContrato";
    oParam.acordo = $F('ac16_sequencial');
    oParam.dtmovimentacao = $F('ac10_datamovimento');
    oParam.valorrescisao = $F('ac16_valorrescisao');
    oParam.observacao = encodeURIComponent(tagString($F('ac10_obs')));
    oParam.datareferencia = $F('ac10_datareferencia');
    oParam.justificativa = $F('ac10_justificativa');

    var oAjax = new Ajax.Request(sUrl, {
      method: 'post',
      parameters: 'json=' + js_objectToJson(oParam),
      onComplete: js_retornoDadosRecisao
    });
  }

  /**
   * Retorna os dados da incluso
   */
  function js_retornoDadosRecisao(oAjax) {

    js_removeObj("msgBoxRecisaoContrato");

    var oRetorno = eval("(" + oAjax.responseText + ")");

    $('ac16_sequencial').value = "";
    $('ac16_resumoobjeto').value = "";
    $('ac10_datamovimento').value = "";
    $('ac10_datareferencia').value = "";
    $('ac16_valorrescisao').value = "";
    $('ac10_justificativa').value = "";
    $('ac10_obs').value = "";
    document.getElementById("trdatareferencia").style.display = 'none'

    if (oRetorno.status == 2) {

      alert(oRetorno.erro.urlDecode());
      return false;
    } else {

      alert("Inclus�o efetuada com Sucesso.");
      return true;
    }
  }
</script>

</html>
