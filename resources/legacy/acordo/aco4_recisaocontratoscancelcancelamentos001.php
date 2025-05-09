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

$db_opcao = 3;

$clacordo->rotulo->label();
$clacordomovimentacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac10_sequencial");
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac10_datamovimento");
$clrotulo->label("ac10_obs");

// funcao do sql
function sql_query_file ( $c99_anousu=null,$c99_instit=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
        $campos_sql = split("#",$campos);
        $virgula = "";
        for($i=0;$i<sizeof($campos_sql);$i++){
            $sql .= $virgula.$campos_sql[$i];
            $virgula = ",";
        }
    }else{
        $sql .= $campos;
    }
    $sql .= " from condataconf ";
    $sql2 = "";
    if($dbwhere==""){
        if($c99_anousu!=null ){
            $sql2 .= " where condataconf.c99_anousu = $c99_anousu ";
        }
        if($c99_instit!=null ){
            if($sql2!=""){
                $sql2 .= " and ";
            }else{
                $sql2 .= " where ";
            }
            $sql2 .= " condataconf.c99_instit = $c99_instit ";
        }
    }else if($dbwhere != ""){
        $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
        $sql .= " order by ";
        $campos_sql = split("#",$ordem);
        $virgula = "";
        for($i=0;$i<sizeof($campos_sql);$i++){
            $sql .= $virgula.$campos_sql[$i];
            $virgula = ",";
        }
    }
    return $sql;
}

$anousu = db_getsession('DB_anousu');

$sSQL = "select to_char(c99_datapat,'YYYY') c99_datapat
          from condataconf
            where c99_instit = ".db_getsession('DB_instit')."
              order by c99_anousu desc limit 1";

$rsResult       = db_query($sSQL);
$maxC99_datapat = db_utils::fieldsMemory($rsResult, 0)->c99_datapat;

$sNSQL = "";
if ($anousu > $maxC99_datapat) {
  $sNSQL = sql_query_file($maxC99_datapat,db_getsession('DB_instit'),'c99_datapat');
} else {
    $sNSQL = sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit'),'c99_datapat');
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_pesquisarCancelaCancelamento();">
<?='<input id="c99_datapat_hidden" type="hidden" value="'.$c99_datapat.'">'?>

<!--  Data do movimento      -->
<?
db_inputdata('ac10_datamovimentoantiga',@$ac10_datamovimentoantiga_dia,
    @$ac10_datamovimentoantiga_mes,
    @$ac10_datamovimentoatiga_ano, true, 'hidden', $db_opcao, "");
?>
<table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
  <tr>
    <td valign="top" align="center">
      <fieldset>
        <legend><b>Desfazer Cancelamento da Rescis�o do Acordo</b></legend>
        <table align="center" border="0">
          <tr>
            <td title="<?=@$Tac10_sequencial?>" align="left">
              <b>C�digo:</b>
            </td>
            <td align="left">
              <?
                db_input('ac10_sequencial',10,$Iac10_sequencial,true,'text',3,"");
              ?>
            </td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td title="<?=@$Tac16_sequencial?>" align="left">
              <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);",$db_opcao); ?>
            </td>
            <td align="left">
              <?
                db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text',
                         $db_opcao," onchange='js_pesquisaac16_sequencial(false);'");
              ?>
            </td>
            <td align="left">
              <?
                db_input('ac16_resumoobjeto',40,$Iac16_resumoobjeto,true,'text',3);
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=@$Tac10_datamovimento?>" align="left">
              <b>Data:</b>
            </td>
            <td align="left">
              <?
                db_inputdata('ac10_datamovimento',@$ac10_datamovimento_dia,
                                                  @$ac10_datamovimento_mes,
                                                  @$ac10_datamovimento_ano, true, 'text', $db_opcao, "");
              ?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3">
              <fieldset>
                <legend>
                  <b>Observa��o</b>
                </legend>
                  <?
                    db_textarea('ac10_obs',5,64,$Iac10_obs,true,'text',1,"");
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
      <input id="cancelar" name="cancelar" type="button" value="Cancelar"
             onclick="return js_cancelarCancelamentoRecisao();" disabled>
      <input id="pesquisar" name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisarCancelaCancelamento();">
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
$('ac10_sequencial').style.width    = "100%";
$('ac16_sequencial').style.width    = "100%";
$('ac16_resumoobjeto').style.width  = "100%";
$('ac10_datamovimento').style.width = "100%";

var sUrl = 'con4_contratosmovimento.RPC.php';

/**
 * Pesquisa recis�o cancelamento
 */
function js_pesquisarCancelaCancelamento() {

  $('cancelar').disabled  = true;
  var sUrl  = 'func_acordomovimentacao.php?movimento=1&tipo=7';
      sUrl += '&funcao_js=parent.js_mostrarPesquisaRecisaoCancelamento|ac10_sequencial';

  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_cancelamentorecisao',
                      sUrl,
                      'Pesquisar Cancelamento de Recis�o',
                      true);
}

/**
 * Retorno da pesquisa recis�o cancelamento
 */
function js_mostrarPesquisaRecisaoCancelamento(chave) {

  $('cancelar').disabled  = false;
  js_getDadosRecisaoCancelamento(chave);
  db_iframe_cancelamentorecisao.hide();
}

/**
 * Busca o dados cancelamento da recis�o
 */
function js_getDadosRecisaoCancelamento(iCodigo) {

  js_divCarregando('Aguarde pesquisando cancelamentos...', 'msgBoxGetDadosRecisaoCancelamentos');

  var oParam        = new Object();
  oParam.exec       = "getDadosRescisao";
  oParam.codigo     = iCodigo;

  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post',
                                          parameters: 'json='+js_objectToJson(oParam),
                                          onComplete: js_retornoGetDadosRecisaoCancelamentos
                                        }
                                );
}

/**
 * Retorno dos dados cancelamento da recis�o
 */
function js_retornoGetDadosRecisaoCancelamentos(oAjax) {

  js_removeObj("msgBoxGetDadosRecisaoCancelamentos");

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 2) {

    alert(oRetorno.erro.urlDecode());
    $('ac16_sequencial').value     = "";
    $('ac16_resumoobjeto').value   = "";
    $('ac10_datamovimento').value  = "";
    $('ac10_obs').value            = "";
    return false;
  } else {

    $('ac10_sequencial').value     = oRetorno.codigo;
    $('ac16_sequencial').value     = oRetorno.acordo;
    $('ac10_datamovimento').value  = js_formatar(oRetorno.datamovimento,'d');
    $('ac10_datamovimentoantiga').value  = js_formatar(oRetorno.datamovimentoantiga,'d');
    $('ac16_resumoobjeto').value   = oRetorno.descricao.urlDecode();
    $('ac10_obs').value            = "";
    return true;
  }

}

/**
 * Cancelar cancelamento de recis�o
 */
function js_cancelarCancelamentoRecisao() {

  if ($('ac16_sequencial').value == '') {

    alert('Acordo n�o informado!');
    return false;
  }

  if ($('ac10_datamovimento').value == '') {

    alert('Data n�o informada!');
    return false;
  }

  if ($('ac10_sequencial').value == '') {

    alert('C�digo da recis�o n�o informado! Verifique a pesquisa.');
    return false;
  }

    /**
     * Verificar Encerramento Periodo Patrimonial
     */
        //    DATA INSERIDA
    var partesData = $('ac10_datamovimentoantiga').value.split("/");
    var data = new Date(partesData[2], partesData[1]-1, partesData[0]);
    var dataInserida = data;

    //    DATA DO SISTEMA
    var partesData = $("c99_datapat_hidden").value.split("-");
    var data = new Date(partesData[0], partesData[1]-1, partesData[2]);
    var dataPatrimonial = data;

    if(dataInserida <= dataPatrimonial){
        alert("O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.");
        return;
    }

  js_divCarregando('Aguarde cancelando cancelamento...','msgBoxCancelarCancelamentoRecisao');

  var oParam        = new Object();
  oParam.exec       = "desfazerCancelarRecisao";
  oParam.codigo     = $F('ac10_sequencial');
  oParam.sData      = $F('ac10_datamovimento');
  oParam.observacao = encodeURIComponent(tagString($F('ac10_obs')));

  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post',
                                          parameters: 'json='+js_objectToJson(oParam),
                                          onComplete: js_retornoCancelarCancelamentoRecisao
                                        }
                                );
}

/**
 * Retorno dos dados cancelar cancelamento da recis�o
 */
function js_retornoCancelarCancelamentoRecisao(oAjax) {

  js_removeObj("msgBoxCancelarCancelamentoRecisao");

  var oRetorno = eval("("+oAjax.responseText+")");

  $('ac10_sequencial').value    = "";
  $('ac16_sequencial').value    = "";
  $('ac16_resumoobjeto').value  = "";
  $('ac10_datamovimento').value = "";
  $('ac10_obs').value           = "";
  $('cancelar').disabled        = true;

  if (oRetorno.status == 2) {
    alert(oRetorno.erro.urlDecode());
  } else {
    alert("Anula��o de cancelamento efetuada com Sucesso.");
  }

  js_pesquisarCancelaCancelamento();

}
</script>
</html>
