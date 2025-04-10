<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: GET, POST");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once(modification("classes/empenho.php"));
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getItensEmpenho":

      $sCamposEmpenho = "DISTINCT riseqitem AS item_empenho ,
          ricodmater AS codigo_material ,
          rsdescr AS descricao_material ,
          e62_descr ,
          rnquantini AS quantidade ,
          rnvalorini AS valor_total ,
          rnvaloruni AS valor_unitario ,
          rnsaldoitem AS saldo ,
          round(rnsaldovalor,2) AS saldo_valor ,
          o56_descr ,
          pc01_servico AS tipo,
          ac16_numeroacordo as contrato,
          ac16_anousu as contrato_ano,
          e55_autori as e55_autori,
          e55_sequen as e55_sequen,
          e55_servicoquantidade as forma_controle,
          CASE
              WHEN pcorcamval.pc23_obs IS NOT NULL THEN pcorcamval.pc23_obs
              WHEN pcorcamvalpai.pc23_orcamitem IS NOT NULL THEN pcorcamvalpai.pc23_obs
              ELSE ''
          END AS observacao";
      $sWhereEmpenho   = "e60_numemp = {$oParam->iCodigoEmpenho}";

      $oDaoEmpenho      = db_utils::getDao("empempenho");
      $sSqlItensEmpenho = $oDaoEmpenho->sql_query_itens_consulta_empenho($oParam->iCodigoEmpenho, $sCamposEmpenho);
      $rsBuscaEmpenho   = $oDaoEmpenho->sql_record($sSqlItensEmpenho);

      $aItensRetorno = array();
      for ($iRowItem = 0; $iRowItem < $oDaoEmpenho->numrows; $iRowItem++) {

        $oStdItem = db_utils::fieldsMemory($rsBuscaEmpenho, $iRowItem);
        $oStdItem->descricao_material = urlencode($oStdItem->descricao_material);
        $oStdItem->observacao         = urlencode($oStdItem->observacao);
        $aItensRetorno[] = $oStdItem;
      }
      $oRetorno->aItensEmpenho = $aItensRetorno;
      break;

    case "getItensAnulados":

      $oDaoItemAnulado    = db_utils::getDao("empanuladoitem");
      $sCamposItemAnulado = "pc01_codmater,pc01_descrmater,e37_qtd, e37_vlranu,e94_data";
      $sSqlAnulados       = $oDaoItemAnulado->sql_query(null, $sCamposItemAnulado, "e62_sequen", "e62_numemp = {$oParam->iCodigoEmpenho}");
      $rsBuscaItemAnulado = $oDaoItemAnulado->sql_record($sSqlAnulados);

      $oRetorno->aItensAnuladoEmpenho = db_utils::getCollectionByRecord($rsBuscaItemAnulado);

      break;
        case 'alterarFormaControle':
            try {
                $oEmpenho = new empenho();
                $oEmpenho->alterarFormaControle($oParam->bFormaControle, $oParam->iAutoriza, $oParam->iSequencia);
            } catch (Exception $eErro) {
                $oRetorno->status  = 2;
                $oRetorno->message = urlencode($eErro->getMessage());
            }
        break;
    }

    
  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);

?>
