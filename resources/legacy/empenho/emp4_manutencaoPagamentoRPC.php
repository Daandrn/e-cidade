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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/CgmBase.model.php"));
require_once(modification("model/CgmJuridico.model.php"));
require_once(modification("model/CgmFisico.model.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification('model/agendaPagamento.model.php'));
require_once(modification("model/impressaoCheque.model.php"));
require_once(modification('model/empenho/EmpenhoFinanceiro.model.php'));
require_once(modification('model/empenho/EmpenhoFinanceiroItem.model.php'));
require_once(modification('model/MaterialCompras.model.php'));
require_once(modification("classes/ordemPagamento.model.php"));
require_once(modification("model/caixa/AutenticacaoArrecadacao.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/financeiro/ContaBancaria.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlano.model.php"));
require_once(modification("model/contabilidade/planoconta/ClassificacaoConta.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaCorrente.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaOrcamento.model.php"));
require_once(modification("model/contabilidade/planoconta/ContaPlanoPCASP.model.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/FileException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("model/configuracao/Agenda.model.php"));
require_once(modification("model/configuracao/DBDepartamento.model.php"));
require_once(modification("model/configuracao/DBDivisaoDepartamento.model.php"));
require_once(modification("model/configuracao/DBEstrutura.model.php"));
require_once(modification("model/configuracao/DBEstruturaValor.model.php"));
require_once(modification("model/configuracao/DBFormCache.model.php"));
require_once(modification("model/configuracao/DBLogJSON.model.php"));
require_once(modification("model/configuracao/DBLog.model.php"));
require_once(modification("model/configuracao/DBLogTXT.model.php"));
require_once(modification("model/configuracao/DBLogXML.model.php"));
require_once(modification("model/configuracao/Instituicao.model.php"));
require_once(modification("model/configuracao/Job.model.php"));
require_once(modification("model/configuracao/RemessaWebService.model.php"));
require_once(modification("model/configuracao/TaskManager.model.php"));
require_once(modification("model/configuracao/Task.model.php"));
require_once(modification("model/configuracao/UsuarioSistema.model.php"));
require_once(modification("model/caixa/ArrecadacaoReceitaOrcamentaria.model.php"));
require_once(modification("model/caixa/AutenticacaoArrecadacao.model.php"));
require_once(modification("model/caixa/AutenticacaoBaixaBanco.model.php"));
require_once(modification("model/caixa/AutenticacaoPlanilha.model.php"));
require_once(modification("model/caixa/LancamentoContabilAjusteBaixaBanco.model.php"));
require_once(modification("model/caixa/PlanilhaArrecadacao.model.php"));
require_once(modification("model/caixa/ReceitaPlanilha.model.php"));
require_once(modification("model/contabilidade/DocumentoContabilConjuntoRegra.model.php"));
require_once(modification("model/contabilidade/DocumentoContabil.model.php"));
require_once(modification("model/contabilidade/DocumentoContabilRegra.model.php"));
require_once(modification("model/contabilidade/EventoContabilLancamento.model.php"));
require_once(modification("model/contabilidade/EventoContabil.model.php"));
require_once(modification("model/contabilidade/GrupoContaOrcamento.model.php"));
require_once(modification("model/contabilidade/InscricaoPassivoOrcamentoItem.model.php"));
require_once(modification("model/contabilidade/InscricaoPassivoOrcamento.model.php"));
require_once(modification("model/contabilidade/RegraLancamentoContabil.model.php"));
require_once(modification("model/contabilidade/SingletonDocumentoContabil.model.php"));
require_once(modification("model/contabilidade/contacorrente/AdiantamentoConcessao.model.php"));
require_once(modification("model/contabilidade/contacorrente/AdiantamentoConcessaoRepository.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteBase.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteContrato.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteContratoRepository.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteRepositoryBase.model.php"));
require_once(modification("model/contabilidade/contacorrente/ContaCorrenteRepositoryFactory.model.php"));
require_once(modification("model/contabilidade/contacorrente/CredorFornecedorDevedor.model.php"));
require_once(modification("model/contabilidade/contacorrente/CredorFornecedorDevedorRepository.model.php"));
require_once(modification("model/contabilidade/contacorrente/DisponibilidadeFinanceira.model.php"));
require_once(modification("model/contabilidade/contacorrente/DisponibilidadeFinanceiraRepository.model.php"));
require_once(modification("model/contabilidade/contacorrente/DomicilioBancario.model.php"));
require_once(modification("model/contabilidade/contacorrente/DomicilioBancarioRepository.model.php"));
require_once(modification("model/contabilidade/lancamento/EscrituracaoRestosAPagarNaoProcessados.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarAberturaExercicioOrcamento.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarArrecadacaoReceita.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarContaCorrente.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarDepreciacao.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarEmLiquidacaoMaterialPermanente.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarEmLiquidacao.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoEmLiquidacaoMaterialAlmoxarifado.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoLiquidacao.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarEmpenho.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarEmpenhoPassivo.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarInscricao.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarInscricaoRestosAPagarNaoProcessados.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarInventario.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarMovimentacaoEstoque.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarProvisaoDecimoTerceiro.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarProvisaoFerias.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarReconhecimentoReceitaFatoGerador.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarSlip.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoEmpenhoEmLiquidacao.model.php"));
require_once(modification("model/contabilidade/lancamento/ReceitaFatoGerador.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraAnulacaoSlip.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraArrecadacaoReceita.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraBaixaInscricaoPassivoSemSuporteOrcamentario.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraEmLiquidacao.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraEmpenhoPassivoSemSuporteOrcamentario.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraInscricaoPassivoSemSuporteOrcamentario.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoAberturaExercicio.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoContabilFactory.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoContaDepreciacao.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoDevolucaoAdiantamento.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoEmLiquidacaoMaterialConsumo.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoEmLiquidacaoMaterialPermanente.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoEmpenhoPrestacaoConta.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoEntradaEstoque.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoLiquidacaoEmpenho.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoProvisaoDecimoTerceiro.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoProvisaoFerias.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoReavaliacaoBem.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLancamentoRestosAPagar.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraLiquidacaoEmpenhoPassivoSemSuporteOrcamentario.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraMovimentacaoEstoque.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraPagamentoSlip.model.php"));
require_once(modification("model/contabilidade/lancamento/RegraReconhecimentoReceitaFatoGerador.model.php"));
require_once(modification("model/orcamento/CaracteristicaPeculiar.model.php"));
require_once(modification("model/orcamento/Orgao.model.php"));
require_once(modification("model/orcamento/ReceitaContabil.model.php"));
require_once(modification("model/orcamento/ReceitaExtraOrcamentaria.model.php"));
require_once(modification("model/orcamento/ReceitaOrcamentaria.model.php"));
require_once(modification("model/orcamento/Recurso.model.php"));
require_once(modification("model/orcamento/TribunalEstrutura.model.php"));
require_once(modification("model/orcamento/Unidade.model.php"));
require_once(modification("std/DBDate.php"));

require_once modification('model/impressaoAutenticacao.php');

$oGet     = db_utils::postMemory($_GET);
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

switch($oParam->exec) {

  case "getMovimentos" :

    // variavel de controle para configura��o de arquivos padrao OBN
    $lArquivoObn = false;
    $lTrazContasFornecedor = true;
    $lTrazContasRecurso    = true;
    if (!empty($oParam->params[0]->lObn)) {

      $lTrazContasFornecedor = false;
      $lTrazContasRecurso    = true;
      $lArquivoObn = true;
    }

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sJoin   = '';
    $sWhereIni  = " ((round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
    $sWhereIni .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0) ";
    $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
    $sWhereIni .= " and e80_data  <= '".date("Y-m-d",db_getsession("DB_datausu"))."'";
    $sWhereIni .= " and e60_instit = ".db_getsession("DB_instit");
    $sWhere     = $sWhereIni;
    $oAgenda->setOrdemConsultas("e82_codord, e81_codmov");
    if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
      $sWhere .= " and e50_codord = {$oParam->params[0]->iOrdemIni}";
    } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {
      $sWhere .= " and e50_codord between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";
    }

    if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {
      $sWhere .= " and e50_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
    } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
      $dtDataFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere .= " and e50_data between '{$dtDataIni}' and '{$dtDataFim}'";

    } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere    .= " and e50_data <= '{$dtDataFim}'";
    }

    //Filtro para Empenho
    if ($oParam->params[0]->iCodEmp!= '') {

      if (strpos($oParam->params[0]->iCodEmp,"/")) {

        $aEmpenho = explode("/",$oParam->params[0]->iCodEmp);
        $sWhere .= " and e60_codemp = '{$aEmpenho[0]}' and e60_anousu={$aEmpenho[1]}";

      } else {
        $sWhere .= " and e60_codemp = '{$oParam->params[0]->iCodEmp}' and e60_anousu=".db_getsession("DB_anousu");
      }

    }

    $sCredorCgm = '';

    //filtro para filtrar por credor
    if ($oParam->params[0]->iNumCgm != '') {

      $sWhere    .= " and (e60_numcgm = {$oParam->params[0]->iNumCgm})";
      $sCredorCgm = $oParam->params[0]->iNumCgm;
    }
    if ($oParam->params[0]->iAutorizadas == 2) {

      $lAutorizadas      = true;
      if ($oParam->params[0]->sDtAut != "") {

        $sDtAut   = implode("-", array_reverse(explode("/", $oParam->params[0]->sDtAut)));
        $sWhere .= " and e42_dtpagamento = '{$sDtAut}'";

      }


      $sWhere .= " and e43_autorizado is true ";

    } else if ($oParam->params[0]->iAutorizadas == 3) {

      $sWhere .= " and e43_empagemov is null";
    }

    if ($oParam->params[0]->iOPauxiliar != '') {

      $sWhere .= " and e42_sequencial = {$oParam->params[0]->iOPauxiliar}";
    }
    if ($oParam->params[0]->iRecurso != '') {

      $sWhere .= " and o15_codigo = {$oParam->params[0]->iRecurso}";
    }
    if ($oParam->params[0]->iOPManutencao != '') {

      $sWhere .= " or ( e42_sequencial = {$oParam->params[0]->iOPManutencao}  and $sWhereIni)";
      $oAgenda->setOrdemConsultas("e42_sequencial,e43_sequencial, e81_codmov,e50_codord");

    } else if (!empty($oParam->params[0]->e03_numeroprocesso)) {

      $sProcesso = addslashes(db_stdClass::normalizeStringJson($oParam->params[0]->e03_numeroprocesso));
      $sWhere   .= " and e03_numeroprocesso = '{$sProcesso}'";
    }

    // validamos se � configuracao OBN
    if ($lArquivoObn == true) {
      $sWhere .= " and empagemovforma.e97_codforma = 3 ";
    }

    $sJoin   .= " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
    $sJoin   .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
    $sJoin   .= " left join pagordemprocesso on e50_codord = e03_pagordem ";

    if (property_exists($oParam->params[0], 'orderBy') && $oParam->params[0]->orderBy == "cgm.z01_nome") {
      $oAgenda->setOrdemConsultas("case when trim(a.z01_nome)   is not null then a.z01_nome   else cgm.z01_nome end");
    }

	$lContaUnicaFundeb = false;
    $aParametrosCaixa = db_stdClass::getParametro("caiparametro", array(db_getsession("DB_instit")));

	if (count($aParametrosCaixa) > 0) {
      $lContaUnicaFundeb = $aParametrosCaixa[0]->k29_cotaunicafundeb == "t" ? true : false;
    }

    $aOrdensAgenda = $oAgenda->getMovimentosAgenda($sWhere,$sJoin,$lTrazContasFornecedor , $lTrazContasRecurso,'',$oParam->params[0]->lVinculadas, $sCredorCgm, $lContaUnicaFundeb);

    if (!empty($oParam->params[0]->lTratarMovimentosConfigurados) && $oParam->params[0]->lTratarMovimentosConfigurados) {

      $aMovimentosConfigurados = array();
      foreach ($aOrdensAgenda as $oStdMovimento) {

        if ($oStdMovimento->e91_codmov != "" || $oStdMovimento->e90_codmov != "") {
          continue;
        } else {
          $aMovimentosConfigurados[] = $oStdMovimento;
        }
      }
      $aOrdensAgenda = $aMovimentosConfigurados;
    }
    if (count($aOrdensAgenda) > 0) {

      $oRetono->status           = 1;
      $oRetono->mensagem         = 1;
      $oRetono->totais           = $oAgenda->getTotaisAgenda($sWhere);
      $oRetono->aNotasLiquidacao = $aOrdensAgenda;
      echo $oJson->encode($oRetono);

    } else {

      $oRetono->status           = 2;
      $oRetono->mensagem         = "";
      $oRetono->aNotasLiquidacao = array();
      echo $oJson->encode($oRetono);

    }
    break;



  case 'efetuarPagamentoSlip':

    $oAgenda                        = new agendaPagamento();
    $oRetorno                       = new stdClass();
    $oRetorno->status               = '1';
    $oRetorno->iCodigoOrdemAuxiliar = null;
    $oRetorno->aAutenticacoes       = array();

    try {

      db_inicio_transacao();


      foreach ($oParam->aMovimentos as $oMovimento) {
        $oAgenda->configurarPagamentos($oParam->dtPagamento, $oMovimento);
      }

      /*
       * Se o usuario marcou a opcao para "Efetuar pagamento" o sistema gera pagamento sequingo a mesma logica
      *   da rotina de pagamento de empenho por agenda (Caixa > Procedimentos > Agenda > Pgtos Empenho p/ Agenda )
      */

      if ($oParam->lEfetuarPagamento) {

        foreach ($oParam->aMovimentos as $oMovimento) {

          $oTransferencia = TransferenciaFactory::getInstance(null, $oMovimento->iCodNota);

            $rsSlipFonte = db_query("SELECT k29_recurso FROM sliprecurso WHERE k29_slip = {$oMovimento->iCodNota}");

            if (pg_numrows($rsSlipFonte) == 0 && db_getsession("DB_anousu") >= 2022) {
                throw new Exception("Para efetuar o pagamento � necess�rio alterar o Slip informando a respectiva fonte de recursos");
            }

            if (isset($oMovimento->iRecurso) && $oMovimento->iRecurso != '')
                $oTransferencia->setFonteRecurso($oMovimento->iRecurso);

		  if (isset($oMovimento->iCodCheque) && $oMovimento->iCodCheque != '') {
			$oTransferencia->setCheque($oMovimento->iCodCheque);
		  }

          if ( $oTransferencia->getContaCredito() != "" ) {

              $oContaTesouraria = new contaTesouraria($oTransferencia->getContaCredito());
              $oContaTesouraria->validaContaPorDataMovimento($oParam->dtPagamento);

          }

          //parametro adcionado devido a OC20697
          $oTransferencia->executaAutenticacao(str_replace('/', '-', $oParam->dtPagamento));

          if (USE_PCASP) {
            $oTransferencia->executarLancamentoContabil(date('Y-m-d', strtotime(str_replace('/', '-', $oParam->dtPagamento))));
          }

          $oAutentica                 = new stdClass();
          $oAutentica->iNota          = $oMovimento->iCodNota;
          $oAutentica->sAutentica     = $oTransferencia->getStringAutenticacao();
          $oRetorno->aAutenticacoes[] = $oAutentica;
        }
      }
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    echo $oJson->encode($oRetorno);

    break;

  case "configurarPagamento" :

    $oAgenda                       = new agendaPagamento();
    $oRetorno                       = new stdClass();
    $oRetorno->status               = '1';
    $oRetorno->iCodigoOrdemAuxiliar = null;
    $oRetorno->aAutenticacoes      = array();
    try {

      db_inicio_transacao();
      $iCodigoOrdemAuxiliar = null;
      if ($oParam->lEmitirOrdeAuxiliar) {
        $iCodigoOrdemAuxiliar =  $oAgenda->autorizarPagamento($oParam->dtPagamento);
      }
      /*
       * Adiciona o Movimento na ordem auxiliar escolhida pelo usu�rio
       */
      if (isset($oParam->iOPAuxiliarManutencao) && $oParam->iOPAuxiliarManutencao != "") {
        $iCodigoOrdemAuxiliar = $oParam->iOPAuxiliarManutencao;
        $oParam->lEmitirOrdeAuxiliar = true;
      }

      foreach ($oParam->aMovimentos as $oMovimento) {


        $oAgenda->configurarPagamentos($oParam->dtPagamento, $oMovimento, $iCodigoOrdemAuxiliar, $oParam->lEmitirOrdeAuxiliar, $oParam->lEfetuarPagamento);

        if ( isset($oMovimento->iContaSaltes) && $oMovimento->iContaSaltes != "" ) {

            $oContaTesouraria = new contaTesouraria($oMovimento->iContaSaltes);
            $oContaTesouraria->validaContaPorDataMovimento($oParam->dtPagamento);

        }

        $iCodForma = $oMovimento->iCodForma;
        $iCodMov   = $oMovimento->iCodMov;

        /**
         * Verificamos se o codigo do movimento est� vinculado a algum tipo de transmiss�o. caso esteja, deletamos os
         * detalhes pois o usu�rio pode ter alterado o valor a ser pago no movimento, entrando assim em conflito com os
         * detalhes (c�digos de barras) lan�ados na configura��o de envio.
         */
        $oDaoEmpAgeMovTipoTransmissao   = db_utils::getDao('empagemovtipotransmissao');
        $sSqlBuscaConfiguracaoMovimento = $oDaoEmpAgeMovTipoTransmissao->sql_query_file(null, "*", null, "e25_empagemov = {$iCodMov}");
        $rsBuscaConfiguracaoMovimento   = $oDaoEmpAgeMovTipoTransmissao->sql_record($sSqlBuscaConfiguracaoMovimento);
        if ($oDaoEmpAgeMovTipoTransmissao->numrows > 0 && $iCodForma == 3) {

          $oDaoDetalheTransmissao = new cl_empagemovdetalhetransmissao();
          $oDaoDetalheTransmissao->excluir(null, "e74_empagemov = {$iCodMov}");
          if ($oDaoDetalheTransmissao->erro_status == "0") {
            throw new BusinessException("N�o foi poss�vel excluir as configura��es do movimento {$iCodMov}.");
          }

        } else {

          $oDaoEmpAgeMovTipoTransmissao->excluir (null, "e25_empagemov = {$iCodMov}");
          if ($oDaoEmpAgeMovTipoTransmissao->erro_status == 0) {
            throw new Exception("ERRO [0] - Vinculando Movimento Tipo Transmissao - " .$oDaoEmpAgeMovTipoTransmissao->erro_msg );
          }

          if ($iCodForma == 3) {

            $oDaoEmpAgeMovTipoTransmissao->e25_empagemov             = $iCodMov;
            $oDaoEmpAgeMovTipoTransmissao->e25_empagetipotransmissao = ParametroCaixa::getTipoTransmissaoPadrao();
            $oDaoEmpAgeMovTipoTransmissao->incluir(null);
            if ($oDaoEmpAgeMovTipoTransmissao->erro_status == 0) {
               throw new Exception("ERRO [1] - Vinculando Movimento Tipo Transmissao - " .$oDaoEmpAgeMovTipoTransmissao->erro_msg );
            }
          }
        }
      }

		/*
		* Se o usuario marcou a opcao para "Efetuar pagamento" o sistema gera pagamento seguindo a mesma logica
		*   da rotina de pagamento de empenho por agenda (Caixa > Procedimentos > Agenda > Pgtos Empenho p/ Agenda )
		*/
    if ($oParam->lEfetuarPagamento) {

			if ($iCodForma == 2 && $oMovimento->iCheque == '' && $oMovimento->iCodCheque == '') {
				throw new Exception("ERRO [2] - Para efetuar o pagamento � necess�rio emitir o cheque.");
			}

			foreach ($oParam->aMovimentos as $oMovimento) {

        if (!empty($oParam->dtPagamento) && $oParam->dtPagamento != '//') {
          $dtAuxData = new DBDate($oParam->dtPagamento);
          $dtAuxData = $dtAuxData->getDate();
          $data = $dtAuxData; // aqui ele atribui a data_para_pagamento enviada pelo usu�rio
          unset($dtAuxData);
        } else {
            $data = date("Y-m-d", db_getsession("DB_datausu"));
        }

				$oOrdemPagamento = new ordemPagamento($oMovimento->iCodNota, $data);

				if (isset($oMovimento->iCheque) && trim($oMovimento->iCheque) != '') {
					$oOrdemPagamento->setCheque($oMovimento->iCheque);
				} else {
					$oOrdemPagamento->setCheque(null);
				}

				if (isset($oMovimento->iCodCheque) && trim($oMovimento->iCodCheque) != '') {
					$oOrdemPagamento->setChequeAgenda($oMovimento->iCodCheque);
				}

				$oOrdemPagamento->setConta($oMovimento->iContaSaltes); // temos que verificar esses parametros
				$oOrdemPagamento->setValorPago($oMovimento->nValor);
				$oOrdemPagamento->setMovimentoAgenda($oMovimento->iCodMov);
				$oOrdemPagamento->setHistorico('');
				$oOrdemPagamento->pagarOrdem();

				$oRetorno->iItipoAutent     = $oOrdemPagamento->oAutentica->k11_tipautent;
				$c70_codlan                 = $oOrdemPagamento->iCodLanc;
				$oAutentica                 = new stdClass();
				$oAutentica->iNota          = $oMovimento->iCodNota;
				$oAutentica->sAutentica     = $oOrdemPagamento->getRetornoautenticacao();
				$oRetorno->aAutenticacoes[] = $oAutentica;

        $clempempenho = new cl_empempenho;
        $controlaDivida = $clempempenho->getControlaDividaParam(db_getsession("DB_anousu"));
        
        if ($controlaDivida == true) {
          
          list($empenho, $ano) = explode('/', $oMovimento->empenho);
          $clempelemento = new cl_empelemento;
          $instituicao  = db_getsession("DB_instit");
          $where = "empempenho.e60_codemp = '{$empenho}' and empempenho.e60_anousu = {$ano} and e60_instit = {$instituicao}";
          $result = $clempelemento->sql_record($clempelemento->sql_query(null, null, "substr(o56_elemento,2,2) as elemento", "e64_codele",$where));                   
          if($clempelemento->numrows > 0){
            $oResult = db_utils::fieldsMemory($result,0);
          }
         
          if ($oResult->elemento == 32 || $oResult->elemento == 46) {
            $sDataMovimento = App\Support\String\DateFormatter::convertDateFormatBRToISO($oParam->dtPagamento);
            $sSqlConsultaFimPeriodoContabil = "SELECT * FROM condataconf WHERE c99_anousu = ".db_getsession('DB_anousu')." and c99_instit = ".db_getsession('DB_instit');
            $rsConsultaFimPeriodoContabil   = db_query($sSqlConsultaFimPeriodoContabil);
            if (pg_num_rows($rsConsultaFimPeriodoContabil) > 0) {
                $oFimPeriodoContabil = db_utils::fieldsMemory($rsConsultaFimPeriodoContabil, 0);
                
                if ($oFimPeriodoContabil->c99_data != '' && db_strtotime($sDataMovimento) <= db_strtotime($oFimPeriodoContabil->c99_data)) {                
                    $erro = true;
                }
            }
           
            if (!$erro) {
              $dtPagamento    = App\Support\String\DateFormatter::convertDateFormatBRToISO($oParam->dtPagamento);
              $clempempenho = new cl_empempenho;
              $instituicao  = db_getsession("DB_instit");
              $where    = " empempenho.e60_codemp = '{$empenho}' and empempenho.e60_anousu = {$ano} and e60_instit = {$instituicao} ";
              $res      = $clempempenho->sql_record($clempempenho->sql_query_file(null,"*",null,$where));

              if ($clempempenho->numrows > 0) {
                $oEmpenho = db_utils::fieldsMemory($res, 0);
              } 

              $justificativa = "Pagamento da OP ($oMovimento->iCodNota) do Empenho ($empenho)";
                    
              $clMovimentacao = new cl_movimentacaodedivida();
              $clMovimentacao->op02_operacaodecredito = $oEmpenho->e60_dividaconsolidada;
              $clMovimentacao->op02_movimentacao      = 2;
              $clMovimentacao->op02_tipo              = 0;
              $clMovimentacao->op02_data              = $dtPagamento;
              $clMovimentacao->op02_justificativa     = $justificativa;
              $clMovimentacao->op02_valor             = $oMovimento->nValor;
              $clMovimentacao->op02_movautomatica     = 't';
              $clMovimentacao->op02_codigoplanilha    = $oMovimento->iCodMov;
              $clMovimentacao->incluir();
  
              if ($clMovimentacao->erro_status == "0"){
                $oRetorno->status = 2;
                $oRetorno->message = "Erro ao incluir movimenta��o autom�tica na D�vida Consolidada.";
                throw new Exception($oRetorno->message);
              } 
            } else {
  
                $oRetorno->status = 2;
                $oRetorno->message = "N�o foi poss�vel alterar a movimenta��o autom�tica na D�vida Consolidada.<br> A data � inferior � data de encerramento do per�odo cont�bil.";
                throw new Exception($oRetorno->message);
            }
        }
        } 
        
			}
      	}
      
      $oRetorno->iCodigoOrdemAuxiliar = $iCodigoOrdemAuxiliar;
      db_fim_transacao(false);

    }
    catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    echo $oJson->encode($oRetorno);
    break;

  case "getMovimentosSlip":

    // variavel de controle para configura��o de arquivos padrao OBN
    $lArquivoObn = false;
    if (!empty($oParam->params[0]->lObn)) {
      $lArquivoObn = true;
    }

    $oAgenda = new agendaPagamento();
    $oAgenda->setUrlEncode(true);
    $sWhere  = " s.k17_instit = ".db_getsession("DB_instit");
    $sWhere .= " and e81_cancelado is null ";

    if (!isset($oParam->params[0]->lBuscaCheque) && $oParam->params[0]->lBuscaCheque != 1) {

      	$sWhere .= " and e91_codmov is null    ";
    	$sWhere .= " and (e90_cancelado is true or e90_cancelado is null)";

    }

    $sWhere .= "and k17_situacao in(1,3)   ";
    if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim == "") {
      $sWhere .= " and s.k17_codigo = {$oParam->params[0]->iOrdemIni}";
    } else if ($oParam->params[0]->iOrdemIni != '' && $oParam->params[0]->iOrdemFim != "") {
      $sWhere .= " and s.k17_codigo between  {$oParam->params[0]->iOrdemIni} and {$oParam->params[0]->iOrdemFim}";
    }

    if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim == "") {
      $sWhere .= " and k17_data = '".implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)))."'";
    } else if ($oParam->params[0]->dtDataIni != "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataIni = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataIni)));
      $dtDataFim = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere .= " and k17_data between '{$dtDataIni}' and '{$dtDataFim}'";

    } else if ($oParam->params[0]->dtDataIni == "" && $oParam->params[0]->dtDataFim != "") {

      $dtDataFim  = implode("-",array_reverse(explode("/",$oParam->params[0]->dtDataFim)));
      $sWhere    .= " and k17_data <= '{$dtDataFim}'";
    }

    //filtro para filtrar por credor
    if ($oParam->params[0]->iNumCgm != '') {
      $sWhere .= " and (k17_numcgm = {$oParam->params[0]->iNumCgm})";
    }

    if ($oParam->params[0]->iRecurso != '') {
      $sWhere .= " and ctapag.c61_codigo = {$oParam->params[0]->iRecurso}";
    }

    if ($lArquivoObn == true) {
      $sWhere .= " and empagemovforma.e97_codforma = 3 ";
    }

    if ( isset($oParam->params[0]->k145_numeroprocesso) && !empty($oParam->params[0]->k145_numeroprocesso) ) {

      $sProcesso = db_stdClass::normalizeStringJsonEscapeString($oParam->params[0]->k145_numeroprocesso);
      $sWhere .= " and k145_numeroprocesso = '{$sProcesso}' ";
    }

    $aSlipsAgenda = $oAgenda->getSlips($sWhere, true);
    if (count($aSlipsAgenda) > 0) {

      $oRetono->status           = 1;
      $oRetono->mensagem         = 1;
      $oRetono->aSlips           = $aSlipsAgenda;
      echo $oJson->encode($oRetono);

    } else {

      $oRetono->status           = 2;
      $oRetono->mensagem         = "";
      echo $oJson->encode($oRetono);

    }
    break;

  case "cancelaMovimentoOrdemAuxiliar":

    $oAgenda                       = new agendaPagamento();
    $oRetono                       = new stdClass();
    $oRetono->status               = '1';
    $oRetono->message              = '1';
    $oRetono->iCodigoOrdemAuxiliar = null;

    try {

      db_inicio_transacao();
      $iCodigoOrdemAuxiliar = $oParam->iOPAuxiliarManutencao;
      foreach ($oParam->aMovimentos as $oMovimento) {
        $oAgenda->cancelaMovimentoOrdemAuxiliar($iCodigoOrdemAuxiliar, $oMovimento->iCodMov);
      }
      $oRetono->iCodigoOrdemAuxiliar = $iCodigoOrdemAuxiliar;
      db_fim_transacao(false);
    }
    catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetono->status  = 2;
      $oRetono->message = urlencode($eErro->getMessage());

    }
    echo $oJson->encode($oRetono);
    break;

  case "agruparMovimentos":

    $oRetorno                       = new stdClass();
    $oRetorno->status               = 1;
    $oRetorno->message              = '1';
    $oRetorno->totalagrupados       = "".count($oParam->aMovimentosAgrupar)."";
    $oAgenda                       = new agendaPagamento();
    try {

      db_inicio_transacao();
      $oAgenda->agruparMovimentos($oParam->aMovimentosAgrupar);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status               = 2;
      $oRetorno->message              = urlencode($eErro->getMessage());

    }
    echo $oJson->encode($oRetorno);
    break;

    case "atualizaContaPagadoraPadrao":

        try {

            db_inicio_transacao();

            /**
             * Altera conta pagadora padr�o que foi definida na liquida��o
             */
            if (isset($oParam->iCodMov) && $oParam->iCodMov != '') {

                $oDaoPagOrdem   = db_utils::getDao("pagordem");
                $sWhere         = " e81_codmov = {$oParam->iCodMov}";
                $rsOrdem        = $oDaoPagOrdem->sql_record($oDaoPagOrdem->sql_query_pagordemagenda(null, "pagordem.*", null, $sWhere));

                if ($oDaoPagOrdem->numrows > 0) {

                    $oPagOrdem = db_utils::fieldsMemory($rsOrdem,0);

                    if ($oParam->iConta != $oPagOrdem->e50_contapag) {

                        $oDaoPagOrdem->e50_codord   = $oPagOrdem->e50_codord;
                        $oDaoPagOrdem->e50_contapag = $oParam->iConta;
                        $oDaoPagOrdem->alterar($oPagOrdem->e50_codord);

                        if ($oDaoPagOrdem->erro_status == 0) {
                            throw new Exception("Erro ao alterar a conta pagadora da ordem de pagamento ($oPagOrdem->e50_codord).");
                        }
                    }
                }
            }

            $oRetorno           = new stdClass();
            $oRetorno->status   = 1;
            $oRetorno->message  = 'Conta Pagadora alterada.';

            db_fim_transacao(false);

        } catch (Exception $eErro) {

            db_fim_transacao(true);

            $oRetorno->status   = 2;
            $oRetorno->message  = urlencode($eErro->getMessage());

        }
    echo $oJson->encode($oRetorno);
    break;

    case "migracao" :

        $oAgenda                       = new agendaPagamento();
        $oRetorno                       = new stdClass();
        $oRetorno->status               = '1';
        $oRetorno->iCodigoOrdemAuxiliar = null;
        $oRetorno->aAutenticacoes      = array();
        try {

            db_inicio_transacao();
            $iCodigoOrdemAuxiliar = null;
            $sql  = "select distinct id   , icodforma ,  nvalor  , icontapagadora , icontasaltes , inota , dtautoriza, empord.*,  ";
            $sql .= " round(COALESCE((select sum(e23_valorretencao) as nvalorretencao from retencaoreceitas inner join retencaopagordem on e23_retencaopagordem=e20_sequencial where e20_pagordem=inota),0),2) as nvalorretencao ";
            $sql .= " from empenho.pagordemmigracao inner join empord on inota=e82_codord ";
            $sql .= " where inota not in (12871, 12908) ";
            $sql .= " order by inota ";
            $aMovimentos = db_utils::getCollectionByRecord(db_query($sql), false , false , false);
            //echo "<pre>";print_r($aMovimentos);exit;
            foreach ($aMovimentos as $oMovimento) {

                $oMovConfig                   = new stdClass();
                $oMovConfig->iCodForma         = 3;
                $oMovConfig->iCodMov           = $oMovimento->e82_codmov;
                $oMovConfig->nValor            = $oMovimento->nvalor - $oMovimento->nvalorretencao;
                $oMovConfig->iContaPagadora    = $oMovimento->icontapagadora;
                $oMovConfig->iContaSaltes      = $oMovimento->icontasaltes;
                $oMovConfig->iCodNota          = $oMovimento->inota;
                $oMovConfig->nValorRetencao    = $oMovimento->nvalorretencao;
                $oMovConfig->sConCarPeculiar   = '000';

                $oAgenda->configurarPagamentos($oMovimento->dtautoriza, $oMovConfig, $iCodigoOrdemAuxiliar, false, true);

                $oOrdemPagamento = new ordemPagamento($oMovimento->inota, $oMovimento->dtautoriza);


                $oOrdemPagamento->setCheque(null);

                $oOrdemPagamento->setConta($oMovConfig->iContaSaltes);
                $oOrdemPagamento->setValorPago($oMovConfig->nValor);
                $oOrdemPagamento->setMovimentoAgenda($oMovConfig->iCodMov);
                $oOrdemPagamento->setHistorico('');
                $oOrdemPagamento->pagarOrdem();

                $oRetorno->iItipoAutent     = $oOrdemPagamento->oAutentica->k11_tipautent;
                $c70_codlan                 = $oOrdemPagamento->iCodLanc;
                $oAutentica                 = new stdClass();
                $oAutentica->iNota          = $oMovConfig->iCodNota;
                $oAutentica->sAutentica     = $oOrdemPagamento->getRetornoautenticacao();
                $oRetorno->aAutenticacoes[] = $oAutentica;

            }


            $oRetorno->iCodigoOrdemAuxiliar = $iCodigoOrdemAuxiliar;
            db_fim_transacao(false);

        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());

        }
        echo $oJson->encode($oRetorno);
        break;
        
    case "alterarContaFornecedor":

      $clPagordem = new cl_pagordem;
      $clPagordem->alteraContaFornecedor($oParam->iCodord, $oParam->iContaFornecedor);
      $oRetorno         = new stdClass();
      $oRetorno->status = $clPagordem->erro_status;
      if ($clPagordem->erro_status == 1){
        $oRetorno->message = "Conta de fornecedor alterada com sucesso.";
      } else {
        $oRetorno->message = "Houve um erro na altera��o da conta de fornecedor.";
      }
      echo $oJson->encode($oRetorno);

    break;
}
