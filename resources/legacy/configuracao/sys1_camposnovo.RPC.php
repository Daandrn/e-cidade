<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "getFields":

      $iTabela = $oParam->iTabela;

      $oDaoTabela = db_utils::getDao("db_sysarqcamp");

      $sSql = $oDaoTabela->sql_camposNovo($iTabela, "campo.*, pai.codcam as id_campo_principal, pai.nomecam as campo_principal", "seqarq");

      $rsCampos = $oDaoTabela->sql_record($sSql);

      $oRetorno->oCampos = array();

      if ($oDaoTabela->numrows > 0) {
        $oRetorno->oCampos = db_utils::getCollectionByRecord($rsCampos, false, false, true);
      }

      break;

    case "getDefaultValues":

      $iCodCam          = $oParam->iCodCam;
      $oDaoTabela       = db_utils::getDao("db_syscampodef");
      $sSql             = $oDaoTabela->sql_query_file($iCodCam);
      $rsValoresDefault = $oDaoTabela->sql_record($sSql);

      if ($oDaoTabela->numrows > 0) {

        for ($iCont = 0; $iCont < $oDaoTabela->numrows; $iCont++) {

          $oValorDefault = db_utils::fieldsMemory($rsValoresDefault, $iCont);

          $oRetorno->aValoresDefault[$iCont] = $oValorDefault->defcampo."#&".$oValorDefault->defdescr;
        }
      }

      break;

      case "findField":

        $oRetorno = array();
        $sString = $oParam->sField;

        $oDaoTabela = db_utils::getDao("db_syscampo");

        $sCampos = "codcam as cod, nomecam as label, *";
        $sWhere = "nomecam ilike '%{$sString}%'";

        $sSql = $oDaoTabela->sql_query_file(null, $sCampos, null, $sWhere);

        $rsCampos = $oDaoTabela->sql_record($sSql);

        if ($oDaoTabela->numrows > 0) {

          for ($i=0; $i < $oDaoTabela->numrows; $i++) {

            $oDadosCampo = db_utils::fieldsMemory($rsCampos, $i, false, false, true);
            $oRetorno[] = $oDadosCampo;
          }

        }

      break;

      case "salvar":

        $oDaoCampoDep   = db_utils::getDao("db_syscampodep");
        $oDaoCampoDef   = db_utils::getDao("db_syscampodef");
        $oDaoCampo      = db_utils::getDao("db_syscampo");
        $oDaoSysArqCamp = db_utils::getDao("db_sysarqcamp");

        $aCampos = $oParam->aCampos;
        $iTabela = $oParam->iTabela;

        $aCamposCadastrados = array();

        foreach ($aCampos as $iIndex => $oCampo) {

          $oDaoCampo->codcam       = $oCampo->codigo_campo;
          $oDaoCampo->nomecam      = utf8_decode($oCampo->nome_campo);
          $oDaoCampo->conteudo     = $oCampo->tipo_campo;
          $oDaoCampo->descricao    = utf8_decode($oCampo->descricao);
          $oDaoCampo->valorinicial = utf8_decode($oCampo->default);
          $oDaoCampo->rotulo       = utf8_decode($oCampo->label_form);
          $oDaoCampo->tamanho      = $oCampo->tamanho;
          $oDaoCampo->nulo         = $oCampo->aceita_nulo    ? 'true' : 'false';
          $oDaoCampo->maiusculo    = $oCampo->maiusculo      ? 'true' : 'false';
          $oDaoCampo->autocompl    = $oCampo->auto_completar ? 'true' : 'false';
          $oDaoCampo->aceitatipo   = $oCampo->tipo_validacao;
          $oDaoCampo->tipoobj      = "text";
          $oDaoCampo->rotulorel    = utf8_decode($oCampo->label_rel);

          if (in_array($oDaoCampo->conteudo, array("char", "varchar")) ) {
            $oDaoCampo->conteudo .= "(".$oDaoCampo->tamanho.")";
          }

          $iCodigoSequencia = "0";
          if (empty($oCampo->codigo_campo)) {

            /**
             * Verifica se j� existe o nome do campo cadastrado
             */
            $oDaoCampo->sql_record($oDaoCampo->sql_query_file(null, "codcam", null, "trim(nomecam) = '{$oCampo->nome_campo}'"));
            if ($oDaoCampo->numrows > 0) {
              throw new Exception("Campo: {$oCampo->nome_campo} j� est� cadastrado.");
            }

            $oDaoCampo->incluir(null);
          } else {
            $oDaoCampo->alterar($oCampo->codigo_campo);

            $sSql = $oDaoSysArqCamp->sql_query_file(null, $oDaoCampo->codcam);
            $rsSysArqCamp = $oDaoSysArqCamp->sql_record($sSql);

            if ($oDaoSysArqCamp->erro_status != "0") {
              $oDadoSysArqCamp = db_utils::fieldsMemory($rsSysArqCamp, 0);
              $iCodigoSequencia = $oDadoSysArqCamp->codsequencia;
            }

            $oDaoSysArqCamp->excluir(null, $oDaoCampo->codcam);
          }

          if (isset($oCampo->valores_default)) {

            $oDaoCampoDef->excluir($oDaoCampo->codcam);
            foreach ($oCampo->valores_default as $sValorDefault) {

              $aValorDefault = explode("#&", $sValorDefault);

              $oDaoCampoDef->codcam    = $oDaoCampo->codcam;
              $oDaoCampoDef->defcampo  = $aValorDefault[0];
              $oDaoCampoDef->defdescr  = $aValorDefault[1];
              $oDaoCampoDef->incluir($oDaoCampo->codcam, $aValorDefault[0]);
            }
          }

          if ($oDaoCampo->erro_status == "0") {
            throw new Exception($oDaoCampo->erro_msg);
          }

          /**
           * Cadastra vinculacao do campo principal na tabela db_syscampodep
           */
          if (!empty($oCampo->id_campo_principal)) {

            $oDaoCampoDep->excluir($oDaoCampo->codcam);

            $oDaoCampoDep->codcam    = $oDaoCampo->codcam;
            $oDaoCampoDep->codcampai = $oCampo->id_campo_principal;
            $oDaoCampoDep->incluir($oDaoCampo->codcam);

            if ($oDaoCampoDep->erro_status == "0") {
              die($oDaoCampoDep->erro_msg);
              throw new Exception("Erro ao cadastrar o Campo Principal do campo: {$oDaoCampo->nomecam}");
            }
          }


          /**
           * Cadastra vincula��o na tabela db_sysarqcamp
           */
          $oDaoSysArqCamp->codcam       = $oDaoCampo->codcam;
          $oDaoSysArqCamp->codarq       = $iTabela;
          $oDaoSysArqCamp->seqarq       = $iIndex+1;
          $oDaoSysArqCamp->codsequencia = $iCodigoSequencia;

          $oDaoSysArqCamp->incluir($iTabela, $oDaoCampo->codcam, $iIndex+1);

          if ($oDaoSysArqCamp->erro_status == "0") {
            throw new Exception($oDaoSysArqCamp->erro_msg);
          }


          $aCamposCadastrados[] = $oDaoCampo->codcam;
        }


        /**
         * Busca todos os campo da tabela que foram excluidos da grid da tela.
         */
        $sWhereToDelete = "db_sysarqcamp.codarq = {$iTabela}";
        if (!empty($aCamposCadastrados)) {
          $sWhereToDelete .= " and db_sysarqcamp.codcam not in(".implode(",", $aCamposCadastrados).")";
        }
        $sSql = $oDaoSysArqCamp->sql_query($iTabela, null, null, "db_sysarqcamp.codcam", "seqarq", $sWhereToDelete);
        $rsToDelete = $oDaoSysArqCamp->sql_record($sSql);

        $aCamposParaDeletar = array();
        if ($oDaoSysArqCamp->erro_status != "0") {
          /**
           * Pega os ids dos campos para deletar
           */
          foreach (db_utils::getCollectionByRecord($rsToDelete) as $oToDelete) {
            $aCamposParaDeletar[] = $oToDelete->codcam;
          }

          $oDaoSysArqCamp->excluir(null, null, null, "codcam in (".implode(",", $aCamposParaDeletar).")");
          $oDaoCampo->excluir(null, "codcam in (".implode(",", $aCamposParaDeletar).")");

        }

        $oRetorno->sMessage = "Campo cadastrados com sucesso.";

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