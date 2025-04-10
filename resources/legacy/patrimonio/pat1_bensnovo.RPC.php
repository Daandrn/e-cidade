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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("std/DBDate.php");
require_once("EnviaRFID.php"); 
require_once("classes/db_benscontrolerfid_classe.php");

db_app::import("MaterialCompras");
db_app::import("patrimonio.*");
db_app::import("CgmFactory");
db_app::import("exceptions.*");
db_app::import("empenho.*");
db_app::import("Dotacao");
db_app::import("patrimonio.*");
db_app::import("configuracao.Instituicao");
db_app::import("CgmFactory");
db_app::import("Bem");

db_app::import("contabilidade.*");
db_app::import('contabilidade.planoconta.ContaPlano');
db_app::import('contabilidade.planoconta.*');
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.contacorrente.*");

db_postmemory($_POST);

$oPlacaBem          = new PlacaBem();
$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = new stdClass();
$oRetorno->status   = 1;

if (isset($oParam->dbOpcao)) {

  $oRetorno->dbOpcao  = $oParam->dbOpcao;
}

switch ($oParam->exec) {


  case "getBens":

    try {

      $aWhere        = array();
      $aDadosRetorno = array();

      // iniciamos o where onde a bensbaix(bens baixados) for nulo (somente os nao baixados)
      $aWhere[] = "t55_codbem is null";

      // montamos o where  conforme os filtros selecionados pelo usuario
      if (!empty($oParam->iCodigoDepartamento)) {
        $aWhere[] = "t52_depart = {$oParam->iCodigoDepartamento}";
      }
      if (!empty($oParam->iCodigoDivisao)) {
        $aWhere[] = "t30_codigo = {$oParam->iCodigoDivisao}";
      }
      if (!empty($oParam->iCodigoBemInicial)) {
        $aWhere[] = "t52_bem >= {$oParam->iCodigoBemInicial}";
      }
      if (!empty($oParam->iCodigoBemFinal)) {
        $aWhere[] = "t52_bem <= {$oParam->iCodigoBemFinal}";
      }
      if (!empty($oParam->iCodigoClassificacaoInicial)) {
        $aWhere[] = "t64_class >= '{$oParam->iCodigoClassificacaoInicial}'";
      }
      if (!empty($oParam->iCodigoClassificacaoFinal)) {
        $aWhere[] = "t64_class <= '{$oParam->iCodigoClassificacaoFinal}'";
      }
      if (!empty($oParam->iPlacaInicial)) {
        $aWhere[] = "t52_ident >= '{$oParam->iPlacaInicial}'";
      }
      if (!empty($oParam->iPlacaFinal)) {
        $aWhere[] = "t52_ident <= '{$oParam->iPlacaFinal}'";
      }
      if (!empty($oParam->iPlacaBem)) {
        $aWhere = array();
        $aWhere[] = "t55_codbem is null";
        $aWhere[] = "t52_ident = '{$oParam->iPlacaBem}'";
      }

      $sWhere  = implode(" and ", $aWhere);

      $sCamposBens  = "t52_bem, t52_ident, t52_descr, t64_descr, descrdepto ";

      $oDaoBem  = db_utils::getDao('bens');
      $sSqlBens = $oDaoBem->sql_querybensdepto(null, $sCamposBens, "t52_descr", $sWhere);
      $rsBens   = $oDaoBem->sql_record($sSqlBens);

      if ($oDaoBem->numrows == 0) {
        throw new DBException('Nenhum Bem encontrado para os filtros selecionados.');
      }

      if ($oDaoBem->numrows >= 1000) {
        throw new DBException("Foram encontrados muitos bens.\nFavor refine a busca, para um melhor procesamento.");
      }

      /*
       * percorremos os registros criando um standard class para retornar os dados desejados
       */
      for ($iBem = 0; $iBem < $oDaoBem->numrows; $iBem++) {

        $oDadosBem     = db_utils::fieldsMemory($rsBens, $iBem);
        $oDadosRetorno = new stdClass();

        $oDadosRetorno->iCodigoBem      = $oDadosBem->t52_bem;
        $oDadosRetorno->sPlaca          = urlencode($oDadosBem->t52_ident);
        $oDadosRetorno->sDescricao      = urlencode($oDadosBem->t52_descr);
        $oDadosRetorno->sClassificacao  = urlencode($oDadosBem->t64_descr);
        $oDadosRetorno->sDepartamento   = urlencode($oDadosBem->descrdepto);
        $oRetorno->aDadosRetorno[] = $oDadosRetorno;
      }

      $oRetorno->lPossuiIntegracaoPatrimonial =
        ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio(
          new DBDate(date("Y-m-d", db_getsession('DB_datausu'))),
          new Instituicao(db_getsession('DB_instit'))
        );
    } catch (DBException $oException) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($oException->getMessage());
    }



    break;


  case "carregaInclusao":

    $iParametroPlaca      = BensParametroPlaca::getCodigoParametro();
    $oRetorno->dados      = buscaSequencia($iParametroPlaca, $oPlacaBem, $oRetorno);

    break;
  case "buscaDivisao";

    try {

      $oRetorno->departamento = array();

      $oDepartamento = new DBDepartamento($oParam->departamento);
      $aDivisoes     = $oDepartamento->getDivisoes();

      foreach ($aDivisoes as $iDivisao => $oDivisao) {

        $oDepartamento = new stdClass();
        $oDepartamento->t30_codigo = $oDivisao->getCodigo();
        $oDepartamento->t30_descr  = urlencode($oDivisao->getDescricao());
        $oRetorno->departamento[]  = $oDepartamento;
      }
    } catch (Exception $oException) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($oException->getMessage());
    }

    break;
  case "buscaOrgaoUnidade":

    $oDaoCfPatri = db_utils::getDao("cfpatri");
    $rsCfPatri   = $oDaoCfPatri->sql_record($oDaoCfPatri->sql_query_file(null, "t06_pesqorgao"));

    if ($oDaoCfPatri->numrows > 0) {

      $oRetorno->dados->libera = 'f';
      $lLiberaOrgao = db_utils::fieldsMemory($rsCfPatri, 0)->t06_pesqorgao;
      if ($lLiberaOrgao) {

        $oBens  = db_utils::getDao("bens");
        $sSql   = $oBens->sql_query_orgao(null, 'o40_descr, o41_descr', null, "db01_coddepto = {$oParam->departamento}");
        $rsBens = $oBens->sql_record($sSql);
        //die($clbens->sql_query_file($t52_bem,'o40_descr,o41_descr'));
        if ($oBens->numrows > 0) {

          $oRetorno->dados->libera = $lLiberaOrgao;
          $oRetorno->dados->o40_descr = urlencode(db_utils::fieldsMemory($rsBens, 0)->o40_descr);
          $oRetorno->dados->o41_descr = urlencode(db_utils::fieldsMemory($rsBens, 0)->o41_descr);
        }
      }
    }


    break;

  case 'carregaPlacaClasse':

    $oRetorno->dados      = buscaSequencia($oParam->iParametro, $oPlacaBem, $oRetorno, $oParam->iClasse);

    break;

  case 'salvar':

    $oBem        = null;
    $lTipoManual = false;

    if ($oParam->cod_depreciacao == 5) {
      $lTipoManual = true;
    }

    /**
     * S� para quando for inclus�o de Bem
     * Se j� houver deprecia��o, n�o se pode cadastrar um bem com a data inferior ao ultimo mes/ano depreciado
     */
    if (db_stdClass::normalizeStringJson($oParam->acao) == "Incluir") {

      $lErroDataAquisicao = false;
      $oHistoricoCalculo  = null;

      if (hasDepreciacaoIniciada(null, $lTipoManual)) {

        $dtImplantacao = getDataImplantacaoDepreciacao();

        if (!empty($dtImplantacao)) {

          list($iAnoImplantacao, $iMesImplantacao, $iDiaImplantacao) = explode("-", $dtImplantacao);
          list($iDiaAquisicao,   $iMesAquisicao, $iAnoAquisicao) = explode("/", $oParam->t52_dtaqu);

          $oHistoricoCalculo = getUltimoMesAnoHistoricoCalculoDepreciacao($lTipoManual);

          if ($oHistoricoCalculo) {

            if ($oHistoricoCalculo->t57_ano > $iAnoAquisicao) {
              $lErroDataAquisicao = true;
            }
            if (($oHistoricoCalculo->t57_ano >= $iAnoAquisicao) && ($oHistoricoCalculo->mes >= $iMesAquisicao)) {
              $lErroDataAquisicao = true;
            }
          }
        }
      }
      if ($lErroDataAquisicao) {

        $sMsgErro = " A data de aquisi��o deve ser posterior ao �ltimo per�odo depreciado {$oHistoricoCalculo->mes}/{$oHistoricoCalculo->t57_ano}.";
        $oRetorno->message = urlencode($sMsgErro);
        $oRetorno->status = 2;
        break;
      }
    }

    if (!empty($oParam->t52_bem)) {
      $oBem = new Bem($oParam->t52_bem);
    } else {

      $oBem = new Bem();
    }

    /**
     * Seta valores no Objeto Bem
     */
    try {

      db_inicio_transacao();

      /**
       * Inclusao
       * - Define placa do bem
       */
      if (empty($oParam->t52_bem)) {

        /**
         * Parametro para c ontrole da numeracao da placa
         * 1 : PLACA_SEQUENCIAL_AUTOMATICO
         * 2 : PLACA_CLASSIFICACAO_SEQUENCIAL
         * 4 : PLACA_SEQUENCIAL_DIGITADO
         */
        $iParametroPlaca = BensParametroPlaca::getCodigoParametro();

        /**
         * SEQUENCIAL DIGITADO
         */
        if ($iParametroPlaca == BensParametroPlaca::PLACA_SEQUENCIAL_DIGITADO) {

          $oParam->t41_placa = mb_strtoupper($oParam->t41_placa);
          $oPlacaBem->setPlacaSeq($oParam->t41_placa);
        } else {

          /**
           * SEQUENCIAL AUTOMATICO || CLASSIFICACAO + SEQUENCIAL
           * - Busca proxima placa
           */
          $oPlacaBem->setPlacaSeq($oPlacaBem->getProximaPlaca($oParam->sPlaca));

          /**
           * CLASSIFICACAO + SEQUENCIAL
           * - campo sPlaca nao vazio
           */
          if ($iParametroPlaca == BensParametroPlaca::PLACA_CLASSIFICACAO_SEQUENCIAL && !empty($oParam->sPlaca)) {
            $oPlacaBem->setPlaca($oParam->sPlaca);
          }
        }

        $oPlacaBem->setData(date("d/m/Y", db_getsession("DB_datausu")));

        if (empty($oParam->obser)) {
          $oPlacaBem->setObservacao(db_stdClass::normalizeStringJson($oParam->obser));
        }

        $oBem->setPlaca($oPlacaBem);
      }

      $oBem->setDataAquisicao($oParam->t52_dtaqu);
      $oBem->setDescricao(db_stdClass::normalizeStringJson($oParam->t52_descr));
      $oBem->setTipoDepreciacao(new BemTipoDepreciacao($oParam->cod_depreciacao));
      $oBem->setClassificacao(new BemClassificacao($oParam->t64_codcla));
      $oBem->setFornecedor(CgmFactory::getInstanceByCgm($oParam->t52_numcgm));
      $oBem->setTipoAquisicao(new BemTipoAquisicao($oParam->t45_sequencial));

      if (!empty($oParam->t04_sequencial)) {
        $oBem->setCedente(new BemCedente($oParam->t04_sequencial));
      }

      $oBem->setDivisao($oParam->divisao);
      $oBem->setSituacaoBem($oParam->t56_situac);
      $oBem->setVidaUtil($oParam->vidaUtil);
      $oBem->setDepartamento($oParam->t52_depart);
      $oBem->setMedida($oParam->t67_sequencial);
      $oBem->setModelo($oParam->t66_sequencial);
      $oBem->setMarca($oParam->t65_sequencial);

      /**
       * S� s�o setados os valores, caso seja um novo Bem
       */
      if (empty($oParam->t52_bem) || (!empty($oParam->t52_bem) && !hasDepreciacaoIniciada($oParam->t52_bem, $lTipoManual))) {

        $oBem->setValorAquisicao($oParam->vlAquisicao);
        $oBem->setValorResidual($oParam->vlResidual);
        $oBem->setValorDepreciavel($oBem->getValorAquisicao() - $oBem->getValorResidual());
        if ($oBem->getValorAtual() <= 0) {
          $oBem->setValorAtual($oBem->getValorResidual());
        }
      }

      $oParam->obser = trim($oParam->obser);

      if (empty($oParam->garantia)) {
        throw new Exception("Informe a garantia.");
      }

      if (empty($oParam->obser)) {
        throw new Exception("Informe a observa��o do lan�amento.");
      }
      session_start();
      $_SESSION['contabilizado'] = $oParam->contabilizado;
     
      $oBem->setObservacao(addslashes(db_stdClass::normalizeStringJson($oParam->obser)));
      $oBem->setInstituicao(db_getsession("DB_instit"));
      $oBem->setCodigoItemNota($oParam->iCodigoItemNota);
      $oBem->salvar();

      // Enviando Dados dos Bens do E-cidade para a API
      $statusbens = enviaDadosRFid($oParam);
       
      db_fim_transacao(false);

      $result = db_query("select t64_bemtipos from clabens where t64_codcla = $oParam->t64_codcla");
      $clabens = db_utils::fieldsmemory($result, 0);

      if ($clabens->t64_bemtipos == 2) {
        $oRetorno->clabens = 2;
      }
     
      if ($oParam->emp_sistema == "SIM" || $oParam->emp_sistema == "s") {
        $sqlerro=false;
        if($sqlerro==false){
          db_inicio_transacao();
          $codbem = $oBem->getCodigoBem();
          $clbensmater = new cl_bensmater;
          $clbensmater->sql_record($clbensmater->sql_query($codbem));
        
          $clbensmater->t53_codbem = $codbem; 
          $clbensmater->t53_ntfisc = $oParam->cod_notafiscal; 
          $clbensmater->t53_ordem  = $oParam->cod_ordemdecompra; 
          $clbensmater->t53_garant = $oParam->garantia;
          $clbensmater->t53_empen  = $oParam->t53_empen;

          if($clbensmater->numrows == 0){
            $clbensmater->incluir($oBem->getCodigoBem());
          } else {
            $clbensmater->t53_garant  = formateDateReverse($oParam->garantia);
            $clbensmater->alterar($codbem);
          }

          if($clbensmater->erro_status==0){
            $sqlerro=true;
            throw new Exception($clbensmater->erro_msg);
          }
          db_fim_transacao($sqlerro);
        }
        if ($sqlerro == false) {

              db_inicio_transacao();
              $clbensmatemp = new cl_bensmaterialempempenho;
              $clbensmatemp->t11_bensmaterial = $oBem->getCodigoBem();
              $clbensmatemp->t11_empempenho   = $oParam->t53_empen;
              $clbensmatemp->incluir(null);
              if ($clbensmatemp->erro_status == 0) {
                $sqlerro=true;
                throw new Exception($clbensmatemp->erro_msg);
              } 
              
              db_fim_transacao($sqlerro);
        } 
           
      }
      $oRetorno->dados->t52_bem = $oBem->getCodigoBem();
      $oRetorno->statusbens = $statusbens;
    } catch (Exception $oException) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($oException->getMessage());
    }

    break;
  case 'buscaBem':

    $oRetorno->dados = buscaDadosBem($oParam->iCodigoBem);

    $oDataAtual   = new DBDate(date("d/m/Y", db_getsession("DB_datausu")));
    $oInstituicao = new Instituicao(db_getsession("DB_instit"));

    $oRetorno->lPossuiIntegracaoPatrimonial = ParametroIntegracaoPatrimonial::possuiIntegracaoPatrimonio($oDataAtual, $oInstituicao);

    break;

  case 'buscaPlacaString':

    $oRetorno->dados      = buscaSequencia($oParam->iParametro, $oPlacaBem, $oRetorno, $oParam->sPlaca);
    break;

  case 'baixarBem':

    try {

      if (empty($oParam->sObservacao)) {
        throw new Exception(urlencode('Campo descri��o da baixa n�o pode ser nulo.'));
      }

      $iMotivo     = $oParam->iMotivo;
      $dDataBaixa  = $oParam->dtBaixa;  //metodo baixar espera a data:  dia/mes/ano
      $sObservacao = db_stdClass::normalizeStringJson($oParam->sObservacao);
      $iDestino    = $oParam->iDestino;

      db_inicio_transacao();
      $aBensErro = array();
      foreach ($oParam->aBens as $iCodigoBem) {

        $oBem = new Bem($iCodigoBem);

        if ($oBem->getCodigoTipoDepreciacao() == null) {
          $aBensErro[] = $iCodigoBem;
        } else {
          $oBem->baixar($iMotivo, $dDataBaixa, $sObservacao, $iDestino);
          // Enviando Dados dos Bens do E-cidade para a API
          $statusbens = enviaDadosRFid($oParam);
        }
      }
      if (count($aBensErro) > 0) {
        throw new Exception("Necessario atualizar os dados financeiros dos bens (" . implode(",", $aBensErro) . ")");
      }
      $oRetorno->statusbens = $statusbens;
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case 'reativarBem':

    $oBem = new Bem($oParam->iCodigoBem);
    try {

      db_inicio_transacao();
      $oBem->reativar($oParam->sObservacao);
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case "getDadosItemNota":

    $oDaoNotaItemBensPendentes  = db_utils::getDao('empnotaitembenspendente');
    $sCamposItemBensPendentes   = "e69_codnota, ";
    $sCamposItemBensPendentes  .= "e69_dtnota, ";
    $sCamposItemBensPendentes  .= "e69_numemp, ";
    $sCamposItemBensPendentes  .= "e60_numcgm, ";
    $sCamposItemBensPendentes  .= "z01_nome, ";
    $sCamposItemBensPendentes  .= "e62_vlrun, ";
    $sCamposItemBensPendentes  .= "pc01_descrmater, ";
    $sCamposItemBensPendentes  .= "e69_numero as nota_fiscal, ";
    $sCamposItemBensPendentes  .= "m52_codordem as ordem_compra ";
    $sWhereItemBensPendentes    = "e72_sequencial = {$oParam->iCodigoItemNota}";
    $sSqlBuscaDadosBem          = $oDaoNotaItemBensPendentes->sql_query_bens(null, $sCamposItemBensPendentes, null, $sWhereItemBensPendentes);
    $rsBuscaDadosBem            = $oDaoNotaItemBensPendentes->sql_record($sSqlBuscaDadosBem);

    $oDadoItem                 = db_utils::fieldsMemory($rsBuscaDadosBem, 0);
    $oRetorno->e69_codnota     = $oDadoItem->e69_codnota;
    $oRetorno->e69_dtnota      = $oDadoItem->e69_dtnota;
    $oRetorno->e69_numemp      = $oDadoItem->e69_numemp;
    $oRetorno->e60_numcgm      = $oDadoItem->e60_numcgm;
    $oRetorno->z01_nome        = $oDadoItem->z01_nome;
    $oRetorno->e62_vlrun       = $oDadoItem->e62_vlrun;
    $oRetorno->pc01_descrmater = $oDadoItem->pc01_descrmater;
    $oRetorno->nota_fiscal     = urlencode($oDadoItem->nota_fiscal);
    $oRetorno->ordem_compra    = $oDadoItem->ordem_compra;

    break;

  case 'verificaVinculoBens':

    $oBem  = new Bem($oParam->iCodigoBem);
    $oNota = $oBem->getNotaFiscal();
    $oRetorno->aOutrosBensVinculados = array();
    if (!empty($oNota)) {

      $oRetorno->oNota                 = new stdClass();
      $oRetorno->oNota->iNumero        = $oNota->getNumeroNota();
      $oRetorno->oNota->sEmpenho       = "{$oNota->getEmpenho()->getCodigo()}/{$oNota->getEmpenho()->getAnoUso()}";

      /**
       * Caso a tenha nota de liquidacao, e o valor da mesma nao seja liquidado, devemos
       * verificar quais os itens do bem possuem alguem bem vinculado a ela.
       */
      if (!empty($oNota) && $oNota->getValorLiquidado() == 0) {


        $aItensDaNota    = $oNota->getItens();
        foreach ($aItensDaNota as $oItemDaNota) {

          $oBensVinculados = $oItemDaNota->getBensVinculados();
          foreach ($oBensVinculados as $oBemVinculado) {

            $oBemBaixar              = new stdClass();
            $oBemBaixar->sDescricao  = urlencode($oBemVinculado->getDescricao());
            $oBemBaixar->iCodigo     = urlencode($oBemVinculado->getCodigoBem());
            $oBemBaixar->dtAquisicao = urlencode($oBemVinculado->getDataAquisicao());

            $oRetorno->aOutrosBensVinculados[] = $oBemBaixar;
          }
        }
      }
    }
    break;


  case "getCodigoDoItemNaNota":

    $oDaoBensEmpNotaItem = new cl_bensempnotaitem();
    $sSqlBuscaItem       = $oDaoBensEmpNotaItem->sql_query_file(
      null,
      "e136_empnotaitem",
      null,
      "e136_bens = {$oParam->iCodigoBem}"
    );
    $rsBuscaItem = $oDaoBensEmpNotaItem->sql_record($sSqlBuscaItem);
    $oRetorno->lEmpenhoVinculado = false;
    if ($oDaoBensEmpNotaItem->numrows > 0) {

      $oRetorno->lEmpenhoVinculado = true;
      $oRetorno->iCodigoItemNaNota = db_utils::fieldsMemory($rsBuscaItem, 0)->e136_empnotaitem;
    }

    break;

  case 'adicionarFoto':
    try {
      if (isset($oParam->iLote) && $oParam->iLote != '') {
        $oDaoBemLote  = db_utils::getDao('benslote');
        $sql = $oDaoBemLote->sql_query('', 't52_bem', 't52_bem', 't43_codlote = ' . $oParam->iLote);
        $rsBemLote = $oDaoBemLote->sql_record($sql);

        if ($oDaoBemLote->numrows > 0) {
          for ($cont = 0; $cont < $oDaoBemLote->numrows; $cont++) {
            $codigoBem = db_utils::fieldsMemory($rsBemLote, $cont)->t52_bem;
            $oBem = new Bem($codigoBem);
            db_inicio_transacao();
            $oBem->adicionarFoto($oParam->arquivo, $oParam->principal, $oParam->ativa);
            db_fim_transacao(false);
          }
          unlink($oParam->arquivo);
          $oRetorno->status = 1;
        }
      } elseif (isset($oParam->iPlaca) && $oParam->iPlaca != '') {
        $sql = 'select t43_codlote from bensplaca join benslote on t43_bem = t41_bem where t41_placaseq = ' . $oParam->iPlaca;
        $rsBemPlaca = db_query($sql);
        $oDaoBemLote  = db_utils::getDao('benslote');
        $sql = $oDaoBemLote->sql_query('', 't52_bem', 't52_bem', 't43_codlote = ' . db_utils::fieldsMemory($rsBemPlaca, 0)->t43_codlote);
        $rsBemLote = $oDaoBemLote->sql_record($sql);

        if (pg_num_rows($rsBemLote) > 0) {
          for ($cont = 0; $cont < pg_num_rows($rsBemLote); $cont++) {
            $codigoBem = db_utils::fieldsMemory($rsBemLote, $cont)->t52_bem;
            $oBem = new Bem($codigoBem);
            db_inicio_transacao();
            $oBem->adicionarFoto($oParam->arquivo, $oParam->principal, $oParam->ativa);
            db_fim_transacao(false);
          }
          unlink($oParam->arquivo);
          $oRetorno->status = 1;
        }
      } else {
        $oBem = new Bem($oParam->iBem);
        db_inicio_transacao();
        $oBem->adicionarFoto($oParam->arquivo, $oParam->principal, $oParam->ativa);
        $oRetorno->status = 1;
        unlink($oParam->arquivo);
        db_fim_transacao(false);
      }
    } catch (Exception $eErro) {
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

    break;

  case 'getFotos':
    if (isset($oParam->iBem) && $oParam->iBem) {
      $codigoBem = $oParam->iBem;
    } elseif (isset($oParam->iLote) && $oParam->iLote) {
      $oDaoBemLote  = db_utils::getDao('benslote');
      $sql = $oDaoBemLote->sql_query('', 't52_bem', 't52_bem', 't43_codlote = ' . $oParam->iLote);
      $rsBemLote = $oDaoBemLote->sql_record($sql);
      $codigoBem = db_utils::fieldsMemory($rsBemLote, 0)->t52_bem;
    } else {
      $sql = 'select t43_codlote from bensplaca join benslote on t43_bem = t41_bem where t41_placaseq = ' . $oParam->iPlaca;
      $rsBemPlaca = db_query($sql);
      $oDaoBemLote  = db_utils::getDao('benslote');
      $sql = $oDaoBemLote->sql_query('', 't52_bem', 't52_bem', 't43_codlote = ' . db_utils::fieldsMemory($rsBemPlaca, 0)->t43_codlote);
      $rsBemLote = $oDaoBemLote->sql_record($sql);
      $codigoBem = db_utils::fieldsMemory($rsBemLote, 0)->t52_bem;
    }

    $oBem   = new Bem($codigoBem);
    $aFotos = $oBem->getFotos();
    $oRetorno->itens = $aFotos;

    break;

  case 'excluirFoto':

    $oBem = new Bem($oParam->iBem);
    try {

      db_inicio_transacao();
      $oBem->excluirFoto($oParam->iFoto);
      $oRetorno->status = 1;

      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case 'alterarFoto':

    $oBem = new Bem($oParam->iBem);
    try {

      db_inicio_transacao();
      $oBem->alterarFoto($oParam->iFoto, $oParam->lPrincipal, $oParam->lAtiva);
      $oRetorno->status = 1;
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;


  case 'excluiTermoGuarda':

    $oRetorno->status = 1;
    $clbensguardaitemdev = db_utils::getDao('bensguardaitemdev');
    $clbensguardaitem = db_utils::getDao('bensguardaitem');
    $sSqlGuardaDev = $clbensguardaitemdev->sql_query('', 'count(*)', '', 't21_codigo = ' . $oParam->t21_codigo);
    $rsGuardaDev = $clbensguardaitemdev->sql_record($sSqlGuardaDev);

    $iLinhas = db_utils::fieldsMemory($rsGuardaDev, 0)->count;
    $sqlerro = false;

    if ($iLinhas) {
      $sqlerro = true;
      $erro_msg = 'Exclus�o n�o efetuada. J� existe devolu��o para essa guarda!';
    } else {

      $clbensguardaitem->excluir('', "t22_bensguarda = $oParam->t21_codigo");

      if ($clbensguardaitem->erro_status == 0) {
        $sqlerro = true;
      }
    }

    if (!$sqlerro) {
      $clbensguarda = db_utils::getDao('bensguarda');
      $clbensguarda->excluir($oParam->t21_codigo);

      if ($clbensguarda->erro_status == 0) {
        $sqlerro = true;
      }

      $erro_msg = $clbensguarda->erro_msg;
    }

    if ($sqlerro) {
      $oRetorno->status = 2;
    }

    $oRetorno->msg = urlencode($erro_msg);

    break;
  case 'carregaBenspendentes':

    $clbemcontrolerfid = new cl_benscontrolerfid();
    $sSqlBemcontrolerfid =  $clbemcontrolerfid->sql_query_file(null,"*","t214_codigobem","t214_controlerfid = $oParam->controle"); 
    $rsBemcontrolerfid = $clbemcontrolerfid->sql_record($sSqlBemcontrolerfid);
    
    if(pg_num_rows($rsBemcontrolerfid)==0){
      $oRetorno->status = 2;
      $erro_msg = 'Nao foi encontrado nenhum Bem pendente de envio!';
      if($oParam->controle == 3)
        $erro_msg = 'Nao foi encontrado nenhuma Baixa de Bens pendente de envio!';
      $oRetorno->msg = $erro_msg; 
    }

    $oRetorno->dados  = array();

    for ($cont = 0; $cont < pg_num_rows($rsBemcontrolerfid); $cont++) {
      $dadosBens = db_utils::fieldsMemory($rsBemcontrolerfid, $cont);    
      
      $oDadosbens      = new stdClass();
      $oDadosbens->codigobem    = $dadosBens->t214_codigobem;
      $oDadosbens->descricaobem = $dadosBens->t214_descbem;
      $oDadosbens->placabem     = $dadosBens->t214_placabem;
      $oRetorno->dados[]        = $oDadosbens;
      $oRetorno->status         = 1; 
    }   
    break;
    case 'enviarBenspendentes':

      try { 
            $statusbens = enviaDadosRFid($oParam); 
            $oRetorno->statusbens  = $statusbens;
      } catch (Exception $eErro) {
          $oRetorno->status  = 2;
          $oRetorno->message = urlencode($eErro->getMessage());
      }      
      break;
    case 'enviarBaixaspendentes':

        try { 
              $statusbens = enviaDadosRFid($oParam);
              $oRetorno->statusbens  = $statusbens;
        } catch (Exception $eErro) {
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($eErro->getMessage());
        }      
        break;    
    case 'buscarDadosMaterial':
    
          $clbensmater = new cl_bensmater;
          $result = $clbensmater->sql_record($clbensmater->sql_query($oParam->iCodigoBem));

          $oDaoBensEmpNotaItem = new cl_bensempnotaitem();
          $sSqlBuscaItem       = $oDaoBensEmpNotaItem->sql_query_file(
            null,
            "e136_empnotaitem",
            null,
            "e136_bens = {$oParam->iCodigoBem}"
          );

          $rsBuscaItem = $oDaoBensEmpNotaItem->sql_record($sSqlBuscaItem);
          $oRetorno->lEmpenhoVinculado = false;
          if ($oDaoBensEmpNotaItem->numrows > 0) {
            $oRetorno->lEmpenhoVinculado = true;
          }
          if($clbensmater->numrows > 0){
             db_fieldsmemory($result,0);
             $oRetorno->t53_ntfisc = $t53_ntfisc;
             $oRetorno->t53_empen  = $t53_empen;
             $oRetorno->t53_ordem  = $t53_ordem;
             $oRetorno->t53_garant = $t53_garant;
          }  
          break;   

        
}

/**
 * @param $iCodigoBem
 * @return stdClass
 */
function buscaDadosBem($iCodigoBem)
{

  $oBem            = new Bem($iCodigoBem);
  $oDadosBem       = new stdClass();
  $iParametroPlaca = BensParametroPlaca::getCodigoParametro();

  $oDadosBem->t52_bem   = $oBem->getCodigoBem();
  $oDadosBem->sPlaca    = $oBem->getPlaca()->getPlaca();
  $oDadosBem->t41_placa = $oBem->getPlaca()->getPlacaSeq();

  if ($iParametroPlaca == 4) {
    $oDadosBem->t41_placa = $oBem->getPlaca()->getNumeroPlaca();
  }

  $iCodigoAquisicao    = "";
  $sDescricaoAquisicao = "";
  if ($oBem->getTipoAquisicao() != null) {
    $iCodigoAquisicao    = $oBem->getTipoAquisicao()->getCodigo();
    $sDescricaoAquisicao = urlencode($oBem->getTipoAquisicao()->getDescricao());
  }

  $oDadosBem->t52_ident      = $oBem->getIdentificacao();
  $oDadosBem->t52_dtaqu      = db_formatar($oBem->getDataAquisicao(), 'd');
  $oDadosBem->t52_descr      = urlencode($oBem->getDescricao());
  $oDadosBem->t64_class      = urlencode($oBem->getClassificacao()->getClassificacao());
  $oDadosBem->t64_codcla     = $oBem->getClassificacao()->getCodigo();
  $oDadosBem->t64_descr      = urlencode($oBem->getClassificacao()->getDescricao());
  $oDadosBem->t52_numcgm     = $oBem->getFornecedor()->getCodigo();
  $oDadosBem->z01_nome       = urlencode($oBem->getFornecedor()->getNome());
  $oDadosBem->t45_sequencial = $iCodigoAquisicao;
  $oDadosBem->t45_descricao  = $sDescricaoAquisicao;
  $oDadosBem->t52_depart     = $oBem->getDepartamento();
  $oDadosBem->descrdepto     = urlencode(buscaDescricaoDepartamento($oDadosBem->t52_depart));

  $oDadosBem->divisao           = $oBem->getDivisao();
  $oDadosBem->t04_sequencial    = '';
  $oDadosBem->z01_nome_convenio = '';

  if ($oBem->getCedente() != null) {

    $oDadosBem->t04_sequencial    = $oBem->getCedente()->getCodigo();
    $oDadosBem->z01_nome_convenio = urlencode($oBem->getCedente()->getCedente()->getNome());
  }

  $iCodigoDepreciacao    = "";
  $sDescricaoDepreciacao = "";
  if ($oBem->getTipoDepreciacao() != null) {
    $iCodigoDepreciacao    = $oBem->getTipoDepreciacao()->getCodigo();
    $sDescricaoDepreciacao = urlencode($oBem->getTipoDepreciacao()->getDescricao());
  }

  $oDadosBem->t56_situac         = $oBem->getSituacaoBem();
  $oDadosBem->t70_descr          = urlencode(buscaDescricaoSituacao($oDadosBem->t56_situac));
  $oDadosBem->vlAquisicao        = $oBem->getValorAquisicao();
  $oDadosBem->vlResidual         = $oBem->getValorResidual();
  $oDadosBem->vlTotal            = $oBem->getValorAtual();
  $oDadosBem->vlTotalDepreciavel = $oBem->getValorDepreciavel();
  $oDadosBem->cod_depreciacao    = $iCodigoDepreciacao;
  $oDadosBem->descr              = $sDescricaoDepreciacao;
  $oDadosBem->vidaUtil           = $oBem->getVidaUtil();
  $oDadosBem->t67_sequencial     = $oBem->getMedida();
  $oDadosBem->t66_sequencial     = $oBem->getModelo();
  $oDadosBem->t65_sequencial     = $oBem->getMarca();
  $oDadosBem->obser              = urlencode($oBem->getObservacao());
  $oDadosBem->iParametroPlaca    = $iParametroPlaca;
  $oDadosBem->bemComCalculo      = false;
  if ($oBem->getQuantidadeMesesDepreciados() > 0) {
    $oDadosBem->bemComCalculo = true;
  }
  $oDadosBaixa           = $oBem->getDadosBaixa();
  $oDadosBem->bembaixado = $oBem->isBaixado();

  if ($oBem->isBaixado()) {

    $oDadosBem->databaixa   = $oDadosBaixa->databaixa;
    $oDadosBem->motivo      = $oDadosBaixa->motivo;
    $oDadosBem->observacao  = urlencode($oDadosBaixa->observacao);
  }
  $oDadosBem->hasDepreciacao = false;

  if (hasDepreciacaoIniciada($oBem->getCodigoBem())) {
    $oDadosBem->hasDepreciacao = true;
  }

  return $oDadosBem;
}

/**
 *
 * Busca Descri��o da Situa��o
 * @param integer $iCodigoSituacao
 */
function buscaDescricaoSituacao($iCodigoSituacao)
{

  $sSituacao    = null;
  $oDaoSituacao = db_utils::getDao("situabens");
  $rsSituacao   = $oDaoSituacao->sql_record($oDaoSituacao->sql_query_file($iCodigoSituacao));

  if ($oDaoSituacao->numrows > 0) {

    $sSituacao = db_utils::fieldsMemory($rsSituacao, 0)->t70_descr;
  }
  return $sSituacao;
}

/**
 *
 * Busca Descri��o do Departamento
 * @param integer $iCodigoDepartamento
 */
function buscaDescricaoDepartamento($iCodigoDepartamento)
{

  $sDepart    = null;
  $oDaoDepart = db_utils::getDao("db_depart");

  $rsDepart   = $oDaoDepart->sql_record($oDaoDepart->sql_query_file($iCodigoDepartamento));
  if ($oDaoDepart->numrows > 0) {

    $sDepart = db_utils::fieldsMemory($rsDepart, 0)->descrdepto;
  }
  return $sDepart;
}

/**
 *
 * Busca o sequencia da Placa
 * @param Integer $iParametroPlaca
 * @param Object  $oPlacaBem
 * @param String  $sParam
 */
function buscaSequencia($iParametroPlaca, $oPlacaBem, $oRetorno, $sParam = null)
{

  $oPlaca               = new stdClass();
  $oPlaca->bloqueia     = true;
  $oPlaca->parametro    = $iParametroPlaca;
  if ($iParametroPlaca == 3) {
    $oPlaca->bloqueia = false;
  }
  try {

    db_inicio_transacao();
    $oPlaca->t41_placa = $oPlacaBem->getProximaPlaca($sParam);
    $oPlaca->lImpressa = $oPlacaBem->isPlacaImpressa();
    db_fim_transacao(false);
  } catch (Exception $oException) {

    $oRetorno->message   = urlencode($oException->getMessage());
    $oRetorno->status   = 2;
  }

  return $oPlaca;
}

/**
 *
 * Retorna um objeto com m�s e ano ou false
 * @return object|boolean
 */
function getUltimoMesAnoHistoricoCalculoDepreciacao($lTipoManual = false)
{

  $oDaoHistoricoCalculo    = db_utils::getDao("benshistoricocalculo");
  $sWhereHistoricoCalculo  = " t57_ano = (select max(t57_ano)              ";
  $sWhereHistoricoCalculo .= "                   from benshistoricocalculo ";
  $sWhereHistoricoCalculo .= "	  				  where t57_processado = true    ";
  $sWhereHistoricoCalculo .= "							  and t57_ativo      = true)   ";
  $sWhereHistoricoCalculo .= " and t57_processado  = true                  ";
  $sWhereHistoricoCalculo .= " and t57_ativo       = true                  ";
  $sWhereHistoricoCalculo .= " and t57_tipocalculo = 1                  ";
  $sWhereHistoricoCalculo .= " and t57_instituicao = " . db_getsession("DB_instit");
  if ($lTipoManual) {
    $sWhereHistoricoCalculo .= " and t57_tipoprocessamento = 2";
  }
  $sWhereHistoricoCalculo .= " group by t57_ano 													 ";

  $sSqlHistoricoCalculo    = $oDaoHistoricoCalculo->sql_query_file(
    null,
    "max(t57_mes) as mes, t57_ano",
    null,
    $sWhereHistoricoCalculo
  );
  $rsHistoricoCalculo      = $oDaoHistoricoCalculo->sql_record($sSqlHistoricoCalculo);

  if ($oDaoHistoricoCalculo->numrows > 0) {
    return db_utils::fieldsMemory($rsHistoricoCalculo, 0);
  }
  return false;
}

/**
 * Verifica se a deprecia��o j� foi inicializada
 */
function hasDepreciacaoIniciada($iCodigoBem = null, $lTipoManual = false)
{

  $oDaoHistoricoCalculo    = db_utils::getDao("benshistoricocalculo");
  $sWhereCalculo           = "t57_instituicao = " . db_getsession("DB_instit");
  if (!empty($iCodigoBem)) {
    $sWhereCalculo .= " and t58_bens = {$iCodigoBem}";
  }

  if ($lTipoManual) {
    $sWhereCalculo .= " and t57_tipoprocessamento = 2";
  }

  $sSqlCalculosInstituicao = $oDaoHistoricoCalculo->sql_query_historico_depreciacao_iniciada(
    null,
    "t57_sequencial",
    "t57_sequencial limit 1",
    $sWhereCalculo
  );

  $rsCalculoBem = $oDaoHistoricoCalculo->sql_record($sSqlCalculosInstituicao);
  if ($oDaoHistoricoCalculo->numrows > 0) {
    return true;
  }
  return false;
}

/**
 * Retorna uma String com a data da implanta��o da deprecia��o ou retorna null.
 * @return string|NULL
 */
function getDataImplantacaoDepreciacao()
{

  $oDaoImplantacaoDepreciacao   = db_utils::getDao("cfpatriinstituicao");
  $sWhereImplantacaoDepreciacao = "t59_instituicao = " . db_getsession("DB_instit");
  $sSqlImplantacaoDepreciacao   = $oDaoImplantacaoDepreciacao->sql_query_file(
    null,
    "t59_dataimplanatacaodepreciacao",
    null,
    $sWhereImplantacaoDepreciacao
  );
  $rsImplantacaoDepreciacao     = $oDaoImplantacaoDepreciacao->sql_record($sSqlImplantacaoDepreciacao);

  if ($oDaoImplantacaoDepreciacao->numrows > 0) {
    return db_utils::fieldsMemory($rsImplantacaoDepreciacao, 0)->t59_dataimplanatacaodepreciacao;
  }
  return null;
}

function formateDateReverse(string $date): string
{ 
    $data_objeto = DateTime::createFromFormat('d/m/Y', $date);
    $data_formatada = $data_objeto->format('Y-m-d');
    return date('Y-m-d', strtotime($data_formatada));
}

echo $oJson->encode($oRetorno);
