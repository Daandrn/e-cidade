<?php
//ini_set('display_errors', true);
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

require_once('classes/db_acordocomissao_classe.php');
require_once('classes/db_acordocomissaomembro_classe.php');
require_once("classes/db_acordotipo_classe.php");
require_once("classes/db_acordopenalidade_classe.php");
require_once("classes/db_acordogarantia_classe.php");
require_once("classes/db_acordoprogramacaofinanceira_classe.php");
require_once("classes/db_acordoposicaoperiodo_classe.php");
require_once("classes/db_acordoitemprevisao_classe.php");
require_once('model/AcordoComissao.model.php');
require_once('model/Acordoaux.model.php');
require_once('model/AcordoItem.model.php');
require_once('model/AcordoComissaoMembro.model.php');
require_once("model/AcordoPenalidade.model.php");
require_once("model/AcordoGarantia.model.php");
require_once("model/CgmFactory.model.php");
require_once('model/CgmBase.model.php');
require_once('model/CgmFisico.model.php');
require_once('model/CgmJuridico.model.php');
require_once("model/MaterialCompras.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/AcordoDocumento.model.php");
require_once("model/Dotacao.model.php");
require_once("model/licitacao.model.php");
require_once("model/ProcessoCompras.model.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/DBTime.php");
require_once("std/DBDate.php");
require_once("model/contrato/AcordoItemTipoCalculoFactory.model.php");

db_app::import("configuracao.DBDepartamento");

$oJson             = new services_json();
//$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oParam           = $oJson->decode(str_replace("\\", "", $_POST["json"]));



$sCaminhoMensagens = "patrimonial.contratos.con4_contratos.";
$oErro             = new stdClass();

if (isset($oParam->descricao) && !empty($oParam->descricao)) {
    $oParam->descricao = str_replace("<contrabarra>", "\\", $oParam->descricao);
}

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->itens   = array();

switch ($oParam->exec) {

    case 'getMembros':

        try {

            $oAcordo = new AcordoComissao($oParam->iAcordo);

            $oAcordoStd = new stdClass();
            $oAcordoStd->iCodigo      = $oAcordo->getCodigo();
            $oAcordoStd->sDescricao   = $oAcordo->getDescricao();
            $oAcordoStd->sObservacao  = $oAcordo->getObservacao();
            $oAcordoStd->sDataInicial = $oAcordo->getDataInicial();
            $oAcordoStd->sDataFinal   = $oAcordo->getDataInicial();
            $oAcordoStd->aMembros     = array();

            foreach ($oAcordo->getMembros() as $oM) {

                $oMemb = new stdClass();

                $oMemb->iCodigo           = $oM->getCodigo();
                $oMemb->iCodigoCgm        = $oM->getCodigoCgm();
                $oMemb->sNome             = urlencode($oM->getNome());
                $oMemb->iCodigoComissao   = $oM->getCodigoComissao();
                $oMemb->iResponsabilidade = $oM->getResponsabilidade();

                $oAcordoStd->aMembros[] = $oMemb;
            }


            $oRetorno->oAcordo = $oAcordoStd;
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'getAcordo':

        try {

            $oDaoAcordo                = new cl_acordocomissao();
            $sSql                      = $oDaoAcordo->sql_query(NULL, "*", "", "ac08_sequencial={$oParam->ac08_sequencial}");
            $rsAcordo                  = $oDaoAcordo->sql_record($sSql);
            $oAcordo                   = db_utils::fieldsMemory($rsAcordo, 0, false, false, true);

            $oRetorno->oAcordo         = $oAcordo;
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'carregaMembro':

        try {

            $oMembro = new AcordoComissaoMembro($oParam->iCodigo);

            $oMembroStd = new stdClass();
            $oMembroStd->iCodigo           = $oMembro->getCodigo();
            $oMembroStd->iCodigoCgm        = $oMembro->getCodigoCgm();
            $oMembroStd->sNome             = urlencode($oMembro->getNome());
            $oMembroStd->iCodigoComissao   = $oMembro->getCodigoComissao();
            $oMembroStd->iResponsabilidade = $oMembro->getResponsabilidade();

            $oRetorno->sAcao   = $oParam->sAcao;
            $oRetorno->oMembro = $oMembroStd;
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'alteraMembro':

        try {

            $oAcordo = new AcordoComissao($oParam->iCodigoComissao);

            $result_dtcadcgm = db_query("select z09_datacadastro from historicocgm where z09_numcgm = {$oParam->iCodigoCgm} and z09_tipo = 1");
            db_fieldsmemory($result_dtcadcgm, 0)->z09_datacadastro;
            $z09_datacadastro   = implode("/", (array_reverse(explode("-", $z09_datacadastro))));
            $dtcadastro = $oAcordo->getDataInicial();

            if ($dtcadastro < $z09_datacadastro) {
                throw new Exception("Usu�rio: A data de cadastro do CGM informado é superior a data do procedimento que está sendo realizado. Corrija a data de cadastro do CGM e tente novamente!");
            }

            $oMembro = new AcordoComissaoMembro($oParam->iCodigo);
            $oMembro->setCodigoCgm($oParam->iCodigoCgm);
            $oMembro->setResponsabilidade($oParam->iResponsabilidade);

            db_inicio_transacao();
            $erromsg = $oMembro->save();
            db_fim_transacao(false);

            $oRetorno->message = urlencode("Membro Alterado com sucesso.");
            $oRetorno->iCodigo = $oMembro->getCodigoComissao();
        } catch (Exception  $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'incluiMembro':

        try {

            $oAcordo = new AcordoComissao($oParam->iCodigoComissao);

            $result_dtcadcgm = db_query("select z09_datacadastro from historicocgm where z09_numcgm = {$oParam->iCodigoCgm} and z09_tipo = 1");
            db_fieldsmemory($result_dtcadcgm, 0)->z09_datacadastro;
            $z09_datacadastro   = implode("/", (array_reverse(explode("-", $z09_datacadastro))));
            $dtcadastro = $oAcordo->getDataInicial();

            if ($dtcadastro < $z09_datacadastro) {
                throw new Exception("Usu�rio: A data de cadastro do CGM informado é superior a data do procedimento que está sendo realizado. Corrija a data de cadastro do CGM e tente novamente!");
            }

            if (!$oAcordo->membroExists($oParam->iCodigoCgm)) {

                $oMembro = new AcordoComissaoMembro();
                $oMembro->setCodigoComissao($oParam->iCodigoComissao);
                $oMembro->setCodigoCgm($oParam->iCodigoCgm);
                $oMembro->setResponsabilidade($oParam->iResponsabilidade);

                db_inicio_transacao();
                $erromsg = $oMembro->save();
                db_fim_transacao(false);

                $oRetorno->message = urlencode("Membro incluido com sucesso.");
                $oRetorno->iCodigo = $oMembro->getCodigoComissao();
            } else {

                $oRetorno->message = urlencode("Membro já está presente na comissão.");
                $oRetorno->iCodigo = $oParam->iCodigoComissao;
            }
        } catch (Exception  $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case 'exluiMembro':

        try {


            $oMembro = new AcordoComissaoMembro($oParam->iCodigo);
            $erromsg = $oMembro->excluir();

            $oRetorno->message = urlencode("Membro excluído com sucesso.");
            $oRetorno->iCodigo = $oMembro->getCodigoComissao();
        } catch (Exception  $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }

        break;

    case "pesquisaTipoAcordo":

        $oRetorno->aTipoAcordo = array();
        $clacordotipo          = new cl_acordotipo;
        $sCampos               = "acordotipo.ac04_sequencial as codigo, acordotipo.ac04_descricao as descricao";
        $sSqlTipoAcordo        = $clacordotipo->sql_query(null, $sCampos, null, "");
        $rsSqlTipoAcordo       = $clacordotipo->sql_record($sSqlTipoAcordo);
        $oRetorno->aTipoAcordo = db_utils::getCollectionByRecord($rsSqlTipoAcordo, false, false, true);
        break;

        /*
     * Pesquisa Acordo Penalidade
    */

    case "pesquisaAcordoPenalidade":

        try {

            $oAcordoPenalidade         = new AcordoPenalidade($oParam->codigo);
            $oRetorno->aTiposContratos = array();
            $oRetorno->iCodigo         = $oAcordoPenalidade->getCodigo();
            $oRetorno->sDescricao      = urlencode($oAcordoPenalidade->getDescricao());
            $oRetorno->sObservacao     = urlencode($oAcordoPenalidade->getObservacao());
            $oRetorno->sTextoPadrao    = urlencode($oAcordoPenalidade->getTextoPadrao());
            $oRetorno->dtValidade      = urlencode(db_formatar($oAcordoPenalidade->getDataLimite(), 'd'));
            $oRetorno->aTiposContratos = $oAcordoPenalidade->getTiposContratos();
        } catch (Exception $eExeption) {

            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
     * Processa incluir/aterar Acordo Penalidade
    */
    case "salvarPenalidade":

        try {

            db_inicio_transacao();

            $oAcordoPenalidade = new AcordoPenalidade($oParam->codigo);


            //$oAcordoPenalidade->setDescricao(utf8_decode($oParam->descricao));
            //$oAcordoPenalidade->setObservacao(utf8_decode($oParam->observacao));
            //$oAcordoPenalidade->setTextoPadrao(utf8_decode($oParam->textopadrao));

            $oAcordoPenalidade->setDescricao(addslashes(db_stdClass::normalizeStringJson($oParam->descricao)));
            $oAcordoPenalidade->setObservacao(addslashes(db_stdClass::normalizeStringJson($oParam->observacao)));
            $oAcordoPenalidade->setTextoPadrao(addslashes(db_stdClass::normalizeStringJson($oParam->textopadrao)));


            $dtValidade = implode("-", array_reverse(explode("/", $oParam->datalimite)));
            $oAcordoPenalidade->setDataLimite($dtValidade);

            $oAcordoPenalidade->removeTipoContrato();

            foreach ($oParam->aTiposAcordos as $oTipoAcordo) {
                $oAcordoPenalidade->addTipoContrato($oTipoAcordo->iCodTipoAcordo);
            }

            $oAcordoPenalidade->save();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
     * Processa excluir Acordo Penalidade
    */
    case "excluirPenalidade":

        try {

            db_inicio_transacao();

            $oAcordoPenalidade = new AcordoPenalidade($oParam->codigo);
            $oAcordoPenalidade->excluir();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
     * PESQUISA GARANTIA
    */
    case "pesquisaGarantia":

        try {

            $oAcordoGarantia           = new AcordoGarantia($oParam->codigo);

            $oRetorno->oAcordoGarantia = new stdClass();
            $oRetorno->oAcordoGarantia->iCodigo         = urlencode($oAcordoGarantia->getCodigo());
            $oRetorno->oAcordoGarantia->sDescricao      = urlencode($oAcordoGarantia->getDescricao());
            $oRetorno->oAcordoGarantia->sObservacao     = urlencode($oAcordoGarantia->getObservacao());
            $oRetorno->oAcordoGarantia->sTextoPadrao    = urlencode($oAcordoGarantia->getTextoPadrao());
            $oRetorno->oAcordoGarantia->sDataLimite     = implode("/", array_reverse(explode("-", $oAcordoGarantia->getDataLimite())));
            $oRetorno->oAcordoGarantia->aTiposContratos = $oAcordoGarantia->getTiposContratos();
        } catch (Exception $eExeption) {

            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
     * REALIZA INCLUSAO/EXCLUSAO DE GARANTIA
    */
    case "salvarGarantia":

        try {

            db_inicio_transacao();

            $sLimite = implode("-", array_reverse(explode("/", $oParam->sDataLimite)));

            $oAcordoGarantia = new AcordoGarantia($oParam->iCodigo);


            //$oAcordoGarantia->setDescricao(utf8_decode($oParam->sDescricao));
            //$oAcordoGarantia->setObservacao(utf8_decode($oParam->sObservacao));
            //$oAcordoGarantia->setTextoPadrao(utf8_decode($oParam->sTextoPadrao));


            $oAcordoGarantia->setDescricao(addslashes(db_stdClass::normalizeStringJson($oParam->sDescricao)));
            $oAcordoGarantia->setObservacao(addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao)));
            $oAcordoGarantia->setTextoPadrao(addslashes(db_stdClass::normalizeStringJson($oParam->sTextoPadrao)));




            $oAcordoGarantia->setDataLimite($sLimite);

            $oAcordoGarantia->removeTipoContrato();

            foreach ($oParam->aTiposAcordos as $oTipoAcordo) {
                $oAcordoGarantia->addTipoContrato($oTipoAcordo->iCodTipoAcordo);
            }

            $oAcordoGarantia->save();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

        /*
     * EXCLUI GARANTIA
    */
    case "excluiGarantia":

        try {

            db_inicio_transacao();

            $oAcordoGarantia = new AcordoGarantia($oParam->iCodigo);
            $oAcordoGarantia->excluir();

            db_fim_transacao(false);
        } catch (Exception $eExeption) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
        }

        break;

    case "getLicitacoesContratado":


        $aItens = licitacao::getLicitacoesByFornecedor($oParam->iContratado, true, true);

        $aItensDevolver = array();
        foreach ($aItens as $oStdDadosLicitacao) {

            if ($oStdDadosLicitacao->l20_usaregistropreco == 't') {
                continue;
            }
            $aItensDevolver[] = $oStdDadosLicitacao;
        }

        /**
         * se o contrato esta preenchido, estamos em alteração
         * verificamos se para esse contrato ja foi incluido itens, de alguma outra licitação
         * se tiver, n�o mostramos outras licitações
         */
        if (isset($oParam->iContrato) && $oParam->iContrato != '') {

            $oDaoAcordoItem = db_utils::getDao("acordoitem");
            $sSqlAcordoItem = $oDaoAcordoItem->sql_query(null, "*", null, "ac26_acordo = {$oParam->iContrato}");
            $rsAcordoItem   = $oDaoAcordoItem->sql_record($sSqlAcordoItem);
            if ($oDaoAcordoItem->numrows > 0) {
                $aItensDevolver = '';
            }
        }

        $oRetorno->itens = $aItensDevolver;
        $oRetorno->itensSelecionados = array();

        if (isset($_SESSION["dadosSelecaoAcordo"])) {
            $oRetorno->itensSelecionados = $_SESSION["dadosSelecaoAcordo"];
        }
        break;


    case "getProcessosContratado":

        $oItens = ProcessoCompras::getProcessosByFornecedor($oParam->iContratado, true);
        /**
         * se o contrato esta preenchido, estamos em alteração
         * verificamos se para esse contrato ja foi incluido itens, de alguma outra licitação
         * se tiver, n�o mostramos outras licitações
         */
        if (isset($oParam->iContrato) && $oParam->iContrato != '') {

            $oDaoAcordoItem = db_utils::getDao("acordoitem");
            $sSqlAcordoItem = $oDaoAcordoItem->sql_query(null, "*", null, "ac26_acordo = {$oParam->iContrato}");
            $rsAcordoItem   = $oDaoAcordoItem->sql_record($sSqlAcordoItem);
            if ($oDaoAcordoItem->numrows > 0) {

                $oItens = '';
            }
        }

        $oRetorno->itens = $oItens;
        $oRetorno->itensSelecionados = array();

        if (isset($_SESSION["dadosSelecaoAcordo"])) {
            $oRetorno->itensSelecionados = $_SESSION["dadosSelecaoAcordo"];
        }
        break;


    case "setDadosSelecao":

        $_SESSION["dadosSelecaoAcordo"] = $oParam->itens;

        break;

    case "getNumeroContrato":

        try {

            $oRetorno->numero = Acordo::getProximoNumeroContrato($oParam->iGrupo);
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "getNumeroContratoAno":

        try {

            $oRetorno->numero = Acordo::getProximoNumeroDoAno($oParam->iAno, $oParam->iInstit);
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "salvarContrato":

        try {


            db_inicio_transacao();

            $lAcordoValido            = true;
            $sMessagemInvalido        = '';
            if ($oParam->contrato->iOrigem == 1 || $oParam->contrato->iOrigem == 2) {
                if (!isset($_SESSION["dadosSelecaoAcordo"])) {

                    $lAcordoValido = false;
                    $sMessagemInvalido  = "Acordo sem vinculo com licitação/Processo de compras";
                }
            } else if (isset($_SESSION["dadosSelecaoAcordo"]) && trim(isset($_SESSION["dadosSelecaoAcordo"])) == "") {

                $lAcordoValido = false;
                $sMessagemInvalido  = "Acordo sem vinculo com licitação/Processo de compras";
            }

            if ($oParam->contrato->iOrigem != "1") {
                if ($oParam->contrato->iLicitacao == "" || $oParam->contrato->iLicitacao == null) {
                    $oLicitacao = $_SESSION["dadosSelecaoAcordo"][0];
                    $oParam->contrato->iLicitacao = $oLicitacao;
                }
            }

            if ($oParam->contrato->iOrigem == 1 && $oParam->contrato->iTipoOrigem == 2) {
                if ($oParam->contrato->iLicitacao == "") {
                    $lAcordoValido = false;
                    $sMessagemInvalido = "Acordo sem vinculo com Licitação.";
                }
            }

            if ($oParam->contrato->iOrigem == 1 && $oParam->contrato->iTipoOrigem == 4) {
                if ($oParam->contrato->iAdesaoregpreco == "") {
                    $lAcordoValido = false;
                    $sMessagemInvalido = "Acordo sem vinculo com Adesão de Registro de Preço.";
                }
            }

            if ($oParam->contrato->dtPublicacao < $oParam->contrato->dtAssinatura) {
                $lAcordoValido = false;
                $sMessagemInvalido = "A data de publicação do acordo {$oParam->contrato->dtPublicacao} n�o pode ser anterior a data de assinatura {$oParam->contrato->dtAssinatura}.";
            }

            if ($lAcordoValido) {
                $result_dtcadcgm = db_query("select z09_datacadastro from historicocgm where z09_numcgm = {$oParam->contrato->iContratado} and z09_tipo = 1");
                db_fieldsmemory($result_dtcadcgm, 0)->z09_datacadastro;
                $dtsession   = date("Y-m-d", db_getsession("DB_datausu"));

                if ($dtsession < $z09_datacadastro) {
                    $sMessagemInvalido = "Usu�rio: A data de cadastro do CGM informado é superior a data do procedimento que está sendo realizado. Corrija a data de cadastro do CGM e tente novamente!";
                    $lAcordoValido = true;
                }

                /**
                 * controle de encerramento peri. Patrimonial
                 */
                $clcondataconf = new cl_condataconf;
                $resultControle = $clcondataconf->sql_record($clcondataconf->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'), 'c99_datapat'));
                db_fieldsmemory($resultControle, 0);

                if ($dtsession <= $c99_datapat) {
                    $sMessagemInvalido = "O período já foi encerrado para envio do SICOM. Verifique os dados do lançamento e entre em contato com o suporte.";
                    $lAcordoValido = true;
                }
            }

            $oLicitacao = db_utils::getDao('liclicita');
            $rsLicitacao   = $oLicitacao->sql_record($oLicitacao->sql_query_file($oParam->contrato->iLicitacao, 'l20_naturezaobjeto'));
            $iNatureza     = db_utils::fieldsMemory($rsLicitacao, 0)->l20_naturezaobjeto;

            $rsSql = db_query('SELECT ac02_acordonatureza from acordogrupo where ac02_sequencial = ' . $oParam->contrato->iGrupo);
            $iNaturezaAcordo = db_utils::fieldsMemory($rsSql, 0)->ac02_acordonatureza;

            if (in_array($oParam->contrato->iTipoOrigem, array(2, 3))) {
                if ($iNatureza != $iNaturezaAcordo) {
                    throw new Exception('Há divergência entre a Natureza do Contrato e a Natureza da Licitação!');
                }
            }

            if ($lAcordoValido) {

                $oParam->contrato->nValorContrato = str_replace(',', '.', str_replace(".", "", $oParam->contrato->nValorContrato));
                if ($oParam->contrato->iNumero != "" && $oParam->contrato->iNumero != null) {
                    $iNumacordo  = $oParam->contrato->iNumero;
                } else {
                    $iNumacordo = db_query("select nextval('acordo_ac16_numeroacordo_seq')");
                    $iNumacordo = pg_result($iNumacordo, 0, 0);
                }

                $oContratado = CgmFactory::getInstanceByCgm($oParam->contrato->iContratado);
                $oContrato = new Acordoaux($oParam->contrato->iCodigo);
                $oContrato->setAno($oParam->contrato->iAnousu != "" ? $oParam->contrato->iAnousu : db_getsession("DB_anousu"));
                $oContrato->setDataAssinatura($oParam->contrato->dtAssinatura);
                $oContrato->setDataPublicacao($oParam->contrato->dtPublicacao);
                $oContrato->setDataInicial($oParam->contrato->dtInicio);
                $oContrato->setDataFinal($oParam->contrato->dtTermino);
                $oContrato->setGrupo($oParam->contrato->iGrupo);
                $oContrato->setSituacao(1);
                $oContrato->setInstit(db_getsession("DB_instit"));
                $oContrato->setLei($oParam->contrato->sLei);
                $oContrato->setNumero($iNumacordo);
                if ($oParam->contrato->iNumero != "" && $oParam->contrato->iNumero != null) {
                    $oContrato->setNumeroAcordo($oParam->contrato->iNumero);
                }
                $oContrato->setOrigem($oParam->contrato->iOrigem);
                $oContrato->setTipoOrigem($oParam->contrato->iTipoOrigem);
                $oContrato->setObjeto(db_stdClass::normalizeStringJsonEscapeString($oParam->contrato->sObjeto));
                $oContrato->setResumoObjeto(db_stdClass::normalizeStringJsonEscapeString($oParam->contrato->sResumoObjeto));
                $oContrato->setDepartamento(db_getsession("DB_coddepto"));
                $oContrato->setQuantidadeRenovacao($oParam->contrato->iQtdRenovacao);
                $oContrato->setTipoRenovacao($oParam->contrato->iUnidRenovacao);
                $oContrato->setDepartamentoResponsavel($oParam->contrato->iDepartamentoResponsavel);
                $oContrato->setEmergencial($oParam->contrato->lEmergencial);
                $oContrato->setContratado($oContratado);
                $oContrato->setProcesso($oParam->contrato->sProcesso);
                $oContrato->setFormaFornecimento($oParam->contrato->sFormaFornecimento);
                $oContrato->setVeiculoDivulgacao($oParam->contrato->sVeiculoDivulgacao);
                $oContrato->setFormaPagamento($oParam->contrato->sFormaPagamento);
                $oContrato->setCpfsignatariocontratante();
                if (!empty($oParam->contrato->iComissao)) {
                    $oContrato->setComissao(new AcordoComissao($oParam->contrato->iComissao));
                }
                $oContrato->setPeriodoComercial($oParam->contrato->lPeriodoComercial);
                $oContrato->setCategoriaAcordo($oParam->contrato->iCategoriaAcordo);
                $oContrato->setTipoUnidadeTempoVigencia($oParam->contrato->iTipoUnidadeTempoVigencia);
                $oContrato->setQtdPeriodoVigencia($oParam->contrato->iQtdPeriodoVigencia);
                $oContrato->setLicitacao($oParam->contrato->iLicitacao);
                $oContrato->setiLicoutroorgao($oParam->contrato->iLicoutroorgao);
                $oContrato->setiAdesaoregpreco($oParam->contrato->iAdesaoregpreco);
                $oContrato->setValorContrato($oParam->contrato->nValorContrato);
                $oContrato->setDataInclusao(date("Y-m-d"));
                $oContrato->setReajuste($oParam->contrato->iReajuste);
                $oContrato->setCriterioReajuste($oParam->contrato->iCriterioreajuste);
                $oContrato->setDataReajuste($oParam->contrato->dtReajuste);
                $oContrato->setPeriodoreajuste($oParam->contrato->sPeriodoreajuste);
                $oContrato->setIndiceReajuste($oParam->contrato->iIndicereajuste);
                $oContrato->setDescricaoReajuste($oParam->contrato->sDescricaoreajuste);
                $oContrato->setDescricaoIndice($oParam->contrato->sDescricaoindice);
                $oContrato->setVigenciaIndeterminada($oParam->contrato->iVigenciaIndeterminada);
                $oContrato->setTipoPagamento($oParam->contrato->iTipoPagamento);
                $oContrato->setNumeroParcela($oParam->contrato->iNumeroParcela);
                $oContrato->setValorParcela($oParam->contrato->iValorParcela);
                $oContrato->setIdentificarCipi($oParam->contrato->sIdentificarCipi);
                $oContrato->setUrlCipi($oParam->contrato->sUrlCipi);
                $oContrato->setInformacoesComplementares($oParam->contrato->sInformacoesComplementares);
                $oContrato->save();

                /*
                 * verificamos se existe empenhos a serem vinculados na seção
                 */
                $oDaoEmpEmpenhoContrato   = db_utils::getDao("empempenhocontrato");
                $oDaoAcordoPosicao        = db_utils::getDao("acordoposicao");
                $oDaoAcordoItem           = db_utils::getDao("acordoitem");
                $oDaoAcordoEmpEmpitem     = db_utils::getDao("acordoempempitem");
                $oDaoEmpEmpitem           = db_utils::getDao("empempitem");
                $oDaoAcordoPosicaoPeriodo = db_utils::getDao("acordoposicaoperiodo");
                $oDaoAcordoVigencia       = db_utils::getDao("acordovigencia");
                $oDaoAcordoItemPeriodo    = db_utils::getDao("acordoitemperiodo");
                $oDaoAcordo               = db_utils::getDao("acordo");
                $oDaAcordoItemPrevisao    = db_utils::getDao("acordoitemprevisao");
                $iContrato                = $oContrato->getCodigoAcordo();

                $aSessaoEmpenhos = db_getsession("oEmpenhosSalvar", false);

                /*
                 * verificamos se a origem nao for empenhos
                 * devemos desfazer todos possiveis antigos vinculos
                 *  deletar acordoempempitem
                 *  deletar acordoitem
                 *  deletar acordoposicao
                 *  deletar empempenhocontrato
                 *  $oDaoEmpEmpenhoContrato = db_utils::getDao("empempenhocontrato");
                 *  $oDaoAcordoPosicao      = db_utils::getDao("acordoposicao");
                 *  $oDaoAcordoItem         = db_utils::getDao("acordoitem");
                 *  $oDaoAcordoEmpEmpitem   = db_utils::getDao("acordoempempitem");
                 *  $oDaoEmpEmpitem         = db_utils::getDao("empempitem");
                 *
                 *  buscamos possiveis vinculos existentes entre o contrato e empenhos
                 */
                if ($oParam->contrato->iOrigem != 6) {

                    $sSqlEmpenhosVinculados = $oDaoEmpEmpenhoContrato->sql_query_file(null, "*", null, "e100_acordo = {$iContrato}");
                    $rsEmpenhosVinculados   = $oDaoEmpEmpenhoContrato->sql_record($sSqlEmpenhosVinculados);

                    if ($oDaoEmpEmpenhoContrato->numrows > 0) {

                        for ($iEmpEmpenhoContrato = 0; $iEmpEmpenhoContrato < $oDaoEmpEmpenhoContrato->numrows; $iEmpEmpenhoContrato++) {

                            $oValoresEmpEmpenhoContrato = db_utils::fieldsMemory($rsEmpenhosVinculados, $iEmpEmpenhoContrato);

                            //trazemos os empempitem para deletar da acordoempempitem
                            $sSqlEmpEmpItem = $oDaoEmpEmpitem->sql_query_file(null, null, "e62_sequencial", null, "e62_numemp = {$oDaoEmpEmpenhoContrato->e100_numemp}");
                            $rsEmpEmpItem   = $oDaoEmpEmpitem->sql_record($sSqlEmpEmpItem);
                            if ($oDaoEmpEmpitem->numrows > 0) {

                                // for($iEmpEmpitem = 0; $iEmpEmpitem < $oDaoEmpEmpitem->numrows; $iEmpEmpitem++){

                                //     $oValorEmpEmpitem = db_utils::fieldsMemory($rsEmpEmpItem, $iEmpEmpitem);
                                //     $oDaoAcordoEmpEmpitem->excluir(null, "ac44_empempitem = {$oValorEmpEmpitem->e62_sequencial}");
                                //     if ($oDaoAcordoEmpEmpitem->erro_status == 0) {

                                //         //throw new Exception(" [ 8 ] - ERRO - Desvinculando itens - " . $oDaoAcordoEmpEmpitem->erro_msg);
                                //         $oErro->erro_msg = $oDaoAcordoEmpEmpitem->erro_msg;
                                //         throw new Exception($sCaminhoMensagens."acordo_empempitem_excluir", $oErro);
                                //     }
                                // }
                            }

                            /*
                             * trazemos os acordoposicao para deletar da acordoitem
                             * depois da acordoposicao
                             */
                            $sSqlAcordoPosicao = $oDaoAcordoPosicao->sql_query_file(null, "ac26_sequencial", null, "ac26_acordo = {$iContrato}");
                            $rsAcordoPosicao   = $oDaoAcordoPosicao->sql_record($sSqlAcordoPosicao);
                            // if ($oDaoAcordoPosicao->numrows > 0) {

                            //     for($iAcordoPosicao = 0; $iAcordoPosicao < $oDaoAcordoPosicao->numrows; $iAcordoPosicao++){

                            //         $oValorAcordoPosicao = db_utils::fieldsMemory($rsAcordoPosicao, $iAcordoPosicao);
                            //         $oDaoAcordoItem->excluir(null, "ac20_acordoposicao = {$oValorAcordoPosicao->ac26_sequencial}");
                            //         if ($oDaoAcordoItem->erro_status == 0) {

                            //             $oErro->erro_msg = $oDaoAcordoItem->erro_msg;
                            //             throw new Exception(_M($sCaminhoMensagens."acordo_item_excluir", $oErro));
                            //         }

                            //         $oDaoAcordoVigencia->excluir(null, "ac18_acordoposicao = {$oValorAcordoPosicao->ac26_sequencial}");
                            //         if ($oDaoAcordoVigencia->erro_status == 0) {

                            //             $oErro->erro_msg = $oDaoAcordoVigencia->erro_msg;
                            //             throw new Exception(_M($sCaminhoMensagens."acordo_vigencia_excluir", $oErro));

                            //         }

                            //         $oDaoAcordoPosicaoPeriodo->excluir(null, "ac36_acordoposicao = {$oValorAcordoPosicao->ac26_sequencial}");
                            //         if($oDaoAcordoPosicaoPeriodo->erro_status == 0){

                            //             $oErro->erro_msg = $oDaoAcordoPosicaoPeriodo->erro_msg;
                            //             throw new Exception(_M($sCaminhoMensagens."acordo_posicao_periodo_excluir", $oErro));
                            //         }

                            //         $oDaoAcordoPosicao->excluir($oValorAcordoPosicao->ac26_sequencial);
                            //         if ($oDaoAcordoPosicao->erro_status == 0) {

                            //             $oErro->erro_msg = $oDaoAcordoPosicao->erro_msg;
                            //             throw new Exception(_M($sCaminhoMensagens."acordo_posicao", $oErro));
                            //         }
                            //     }
                            // }
                        }

                        // $oDaoEmpEmpenhoContrato->excluir(null, "e100_acordo = {$iContrato}");
                        // if ($oDaoEmpEmpenhoContrato->erro_status == 0) {

                        //     $oErro->erro_msg = $oDaoEmpEmpenhoContrato->erro_msg;
                        //     throw new Exception(_M($sCaminhoMensagens."empempenho_contrato_excluir", $oErro));
                        // }
                    }
                }
                $oDaoAcordo->ac16_semvigencia = 'f';
                $oDaoAcordo->ac16_sequencial = $iContrato;

                if ($oParam->contrato->iCodigo == null || $oParam->contrato->iCodigo == "")
                    $oDaoAcordo->ac16_semvigencia = 't';
                $oDaoAcordo->ac16_sequencial = $iContrato;

                $oDaoAcordo->alterar($iContrato);
                if ($oDaoAcordo->erro_status == 0) {
                    throw new Exception($oDaoAcordo->erro_msg);
                }
                db_fim_transacao(false);
                $_SESSION["oContrato"]     = $oContrato;
                $oRetorno->iCodigoContrato = $oContrato->getCodigoAcordo();
                $oRetorno->sDataInclusao = $oContrato->getDataInclusao();
                $oRetorno->iAnousu = $oContrato->getAno();
            } else {

                db_fim_transacao(true);
                $oRetorno->status  = 2;
                $oRetorno->message = urlencode(str_replace("\\n", "\n", $sMessagemInvalido));
            }
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }

        break;

    case "getDadosAcordo":

        try {

            unset($_SESSION["oContrato"]);
            $oContrato                = new Acordo($oParam->iContrato);
            $iDepartamentoResponsavel = $oContrato->getDepartamentoResponsavel();
            $oDepartamento            = new DBDepartamento($iDepartamentoResponsavel);
            $_SESSION["oContrato"]    = $oContrato;

            $oDadosContrato = new stdClass();
            $oDadosContrato->iSequencial                  = $oContrato->getCodigoAcordo();
            $oDadosContrato->dtInclusao                   = $oContrato->getDataInclusao();
            $oDadosContrato->iAnousu                      = $oContrato->getAno();
            $oDadosContrato->iOrigem                      = $oContrato->getOrigem();
            $oDadosContrato->iTipoOrigem                  = $oContrato->getTipoOrigem();
            $oDadosContrato->iGrupo                       = $oContrato->getGrupo();
            $oDadosContrato->iNumero                      = $oContrato->getNumeroAcordo();
            $oDadosContrato->iContratado                  = $oContrato->getContratado()->getCodigo();
            $oDadosContrato->sNomeContratado              = urlencode($oContrato->getContratado()->getNome());
            $oDadosContrato->iDepartamentoResponsavel     = $iDepartamentoResponsavel;
            $oDadosContrato->sNomeDepartamentoResponsavel = urlencode($oDepartamento->getNomeDepartamento());
            $oDadosContrato->dtInicio                     = $oContrato->getDataInicial();
            $oDadosContrato->dtTermino                    = $oContrato->getDataFinal();
            $oDadosContrato->dtAssinatura                 = $oContrato->getDataAssinatura();
            $oDadosContrato->dtPublicacao                 = $oContrato->getDataPublicacao();
            $oDadosContrato->sLei                         = $oContrato->getLei();
            $oDadosContrato->iComissao                    = $oContrato->getComissao()->getCodigo();
            $oDadosContrato->sNomeComissao                = urlencode($oContrato->getComissao()->getDescricao());
            $oDadosContrato->sObjeto                      = urlencode($oContrato->getObjeto());
            $oDadosContrato->sResumoObjeto                = urlencode($oContrato->getResumoObjeto());
            $oDadosContrato->sNumeroProcesso              = urlencode($oContrato->getProcesso());
            $oDadosContrato->sFormaFornecimento           = urlencode($oContrato->getFormaFornecimento());
            $oDadosContrato->sVeiculoDivulgacao           = urlencode($oContrato->getVeiculoDivulgacao());
            $oDadosContrato->sFormaPagamento              = urlencode($oContrato->getFormaPagamento());
            $oDadosContrato->sCpfsignatariocontratante    = urlencode($oContrato->getCpfsignatariocontratante());
            $oDadosContrato->iNumeroRenovacao             = $oContrato->getQuantidadeRenovacao();
            $oDadosContrato->iTipoRenovacao               = $oContrato->getTipoRenovacao();
            $oDadosContrato->lPeriodoComercial            = $oContrato->getPeriodoComercial() == "t" ? "true" : false;
            $oDadosContrato->iCategoriaAcordo             = $oContrato->getCategoriaAcordo();
            $oDadosContrato->iTipoUnidadeTempoVigencia    = $oContrato->getTipoUnidadeTempoVigencia();
            $oDadosContrato->iQtdPeriodoVigencia          = $oContrato->getQtdPeriodoVigencia();
            $oDadosContrato->lEmergencial                 = $oContrato->isEmergencial();
            $oDadosContrato->iLicitacao                   = $oContrato->getLicitacao();
            $oDadosContrato->iLicoutroorgao               = $oContrato->getiLicoutroorgao();
            $oDadosContrato->iAdesaoregpreco              = $oContrato->getiAdesaoregpreco();
            $oDadosContrato->nValorContrato               = $oContrato->getValorContrato();
            $oDadosContrato->iReajuste                    = $oContrato->getReajuste();
            $oDadosContrato->iCriterioreajuste            = $oContrato->getCriterioReajuste();
            $oDadosContrato->dtReajuste                   = $oContrato->getDataReajuste();
            $oDadosContrato->sPeriodoreajuste             = $oContrato->getPeriodoreajuste();
            $oDadosContrato->iIndicereajuste              = $oContrato->getIndiceReajuste();
            $oDadosContrato->sDescricaoreajuste           = urlencode($oContrato->getDescricaoReajuste());
            $oDadosContrato->sDescricaoindice             = urlencode($oContrato->getDescricaoIndice());
            $oDadosContrato->iTipoPagamento               = $oContrato->getTipoPagamento();
            $oDadosContrato->iNumeroParcela               = $oContrato->getNumeroParcela();
            $oDadosContrato->iValorParcela                = $oContrato->getValorParcela();
            $oDadosContrato->sIdentificarCipi             = $oContrato->getIdentificarCipi();
            $oDadosContrato->sUrlCipi                     = $oContrato->getUrlCipi();
            $oDadosContrato->sInformacoesComplementares   = $oContrato->getInformacoesComplementares();
            $oRetorno->contrato = $oDadosContrato;
        } catch (Exception $eErro) {

            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "getElementosMateriais":

        $oMaterial = new MaterialCompras($oParam->iMaterial);
        $oRetorno->itens  = $oMaterial->getElementos();
        break;

    case "adicionarItem":

        try {

            db_inicio_transacao();

            $oItemContrato = new AcordoItem();
            $oContrato     = $_SESSION["oContrato"];
            $oPosicao      = $oContrato->getUltimaPosicao();

            foreach ($oPosicao->getItens() as $item) {
                if ($item->getMaterial()->getMaterial() == $oParam->material->iMaterial) {
                    throw new Exception("Material já vinculado.");
                }
            }
            //                    var_dump($oParam->material->aPeriodo);
            $oItemContrato->setCodigoPosicao($oPosicao->getCodigo());
            $oItemContrato->setElemento($oParam->material->iElemento);
            $oItemContrato->setQuantidade($oParam->material->nQuantidade);
            $oItemContrato->setValorUnitario($oParam->material->nValorUnitario);
            $oItemContrato->setUnidade($oParam->material->iUnidade);
            $oItemContrato->setMarca(utf8_decode($oParam->material->sMarca));
            $oItemContrato->setResumo(addslashes(db_stdClass::normalizeStringJson($oParam->material->sResumo)));
            $oItemContrato->setMaterial(new MaterialCompras($oParam->material->iMaterial));
            $oItemContrato->setTipoControle($oParam->material->iTipoControle);
            $oItemContrato->setServicoQuantidade($oParam->material->iServicoQuantidade);
            //$oItemContrato->setPeriodos((array)$oParam->material->aPeriodo[0]);
            //$oItemContrato->setPeriodosExecucao($oContrato->getCodigoAcordo(), $oContrato->getPeriodoComercial());
            $oItemContrato->save();

            $oPosicao->adicionarItens($oItemContrato);

            $oContrato->atualizaValorContratoPorTotalItens('t');

            db_fim_transacao(false);
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;

    case "getItensAcordo":


        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato                = $_SESSION["oContrato"];

            $oRetorno->iTipoContrato  = $oContrato->getOrigem();
            $oPosicao                 = $oContrato->getUltimaPosicao(true);
            $oRetorno->iCodigoPosicao = $oPosicao->getCodigo();
            $aItens                   = $oPosicao->getItens();
            $aDadosSelecaoAcordo      = array();
            if (!isset($_SESSION["dadosSelecaoAcordo"])) {
                $_SESSION["dadosSelecaoAcordo"] = array();
            }

            foreach ($aItens as $oItemContrato) {

                $oItem                        = new stdClass();
                $oItem->codigo                = $oItemContrato->getCodigo();
                $oItem->material              = $oItemContrato->getMaterial()->getDescricao();
                $oItem->codigomaterial        = $oItemContrato->getMaterial()->getMaterial();
                $oItem->quantidade            = $oItemContrato->getQuantidade();
                $oItem->valorunitario         = $oItemContrato->getValorUnitario();
                $oItem->valortotal            = $oItemContrato->getValorUnitario() * $oItemContrato->getQuantidade();
                $oItem->quantidade            = $oItemContrato->getQuantidade();
                $oItem->elemento              = $oItemContrato->getElemento();
                $oItem->marca                 = $oItemContrato->getMarca();
                $oItem->elementocodigo        = $oItemContrato->getDesdobramento();
                $oItem->elementodescricao     = urlencode(str_replace("\\n", "\n", urldecode($oItemContrato->getDescricaoElemento())));
                $oItem->unidade               = $oItemContrato->getUnidade();
                $oItem->resumo                = urlencode(str_replace("\\n", "\n", urldecode($oItemContrato->getResumo())));
                $oItem->tipocontrole          = $oItemContrato->getTipocontrole();
                $oItem->servicoquantidade     = $oItemContrato->getServicoQuantidade();
                $oItem->servico               = $oItemContrato->getMaterial()->isServico();

                /**
                 * Percorremos os periodos do ITEM formatando eles para o formado brasileiro: DD/MM/YYYY
                 */
                $aPeriodosDoItem              = $oItemContrato->getPeriodosItem();
                $aPeriodosFormatados          = array();
                foreach ($aPeriodosDoItem as $oPeriodo) {

                    $oStdPeriodo                = new stdClass();
                    $oStdPeriodo->dtDataInicial = implode("/", array_reverse(explode("-", $oPeriodo->dtDataInicial)));
                    $oStdPeriodo->dtDataFinal   = implode("/", array_reverse(explode("-", $oPeriodo->dtDataFinal)));
                    $aPeriodosFormatados[]      = $oStdPeriodo;
                }
                $oItem->aPeriodosItem         = $aPeriodosFormatados;
                $oItem->ordem                 = $oItemContrato->getOrdem();
                $oItem->totaldotacoes         = 0;
                $oDadosOrigem                 = $oItemContrato->getOrigem();
                if ($oDadosOrigem->tipo != 0) {
                    if (!in_array($oDadosOrigem->codigoorigem, $_SESSION["dadosSelecaoAcordo"])) {
                        $_SESSION["dadosSelecaoAcordo"][] = $oDadosOrigem->codigoorigem;
                    }
                }
                foreach ($oItemContrato->getDotacoes() as $oDotacao) {
                    $oItem->totaldotacoes  += $oDotacao->valor;
                }
                if (isset($oParam->iCodigoItem)) {

                    if ($oParam->iCodigoItem ==  $oItemContrato->getCodigo()) {
                        $oRetorno->item = $oItem;
                    }
                } else {
                    $oRetorno->itens[]     = $oItem;
                }
            }
        }
        break;

    case "alterarItem":

        try {
            if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

                $oContrato = $_SESSION["oContrato"];
                $oPosicao  = $oContrato->getUltimaPosicao();
                $aItens    = $oPosicao->getItens();

                $oRetorno->lAlterarDotacao = false;
                foreach ($aItens as $oItem) {

                    if ($oParam->material->iCodigo ==  $oItem->getCodigo()) {

                        $oItemContrato = $oItem;
                        break;
                    }
                }
                db_inicio_transacao();

                $oItemContrato->setCodigoPosicao($oPosicao->getCodigo())
                    ->setElemento($oParam->material->iElemento)
                    ->setQuantidade($oParam->material->nQuantidade)
                    ->setValorUnitario($oParam->material->nValorUnitario)
                    ->setUnidade($oParam->material->iUnidade)
                    //->setResumo(utf8_decode($oParam->material->sResumo))
                    ->setResumo(addslashes(db_stdClass::normalizeStringJson($oParam->material->sResumo)))
                    ->setTipoControle($oParam->material->iTipoControle)
                    ->setMarca($oParam->material->sMarca)
                    ->setServicoQuantidade($oParam->material->iServicoQuantidade);
                //->setPeriodos($oParam->material->aPeriodo)
                //->setPeriodosExecucao($oContrato->getCodigoAcordo(), $oContrato->getPeriodoComercial());
                $oItemContrato->setMaterial(new MaterialCompras($oParam->material->iMaterial));

                if (count($oItemContrato->getDotacoes()) == 1) {

                    $aDotacao =  $oItemContrato->getDotacoes();
                    $aDotacao[0]->valor      = $oItemContrato->getValorTotal();
                    $aDotacao[0]->quantidade = $oItemContrato->getQuantidade();
                } else if (count($oItemContrato->getDotacoes()) > 1) {
                    $oRetorno->lAlterarDotacao = true;
                }
                $oItemContrato->save();

                $oContrato->atualizaValorContratoPorTotalItens('t');

                db_fim_transacao(false);
            }
        } catch (Exception $eErro) {

            $oRetorno->status  = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
            db_fim_transacao(true);
        }

        break;

    case "getDotacoesItens":

        $oRetorno->iElementoDotacao = '';
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato = $_SESSION["oContrato"];
            $oPosicao  = $oContrato->getUltimaPosicao();
            $aItens    = $oPosicao->getItens();
            foreach ($aItens as $oItem) {

                if ($oParam->iCodigoItem ==  $oItem->getCodigo()) {
                    $oItemContrato = $oItem;
                    break;
                }
            }
            if (isset($oItemContrato)) {

                $oRetorno->dotacoes         = $oItem->getDotacoes();
                $oRetorno->iElementoDotacao = $oItem->getDesdobramento();
                $oRetorno->servico          = $oItem->getMaterial()->isServico();
                $oRetorno->servicoquantidade = $oItem->getServicoQuantidade();
            } else {

                $oRetorno->status = 2;
                $oRetorno->message = urlencode("O item selecionado n�o foi encontrado.");
            }
        }
        break;

    case "saveDotacaoItens":

        $oRetorno->iElementoDotacao = '';
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato = $_SESSION["oContrato"];
            $oPosicao  = $oContrato->getUltimaPosicao();
            $aItens    = $oPosicao->getItens();

            foreach ($aItens as $oItem) {

                if ($oParam->iCodigoItem ==  $oItem->getMaterial()->getMaterial()) {
                    $oItemContrato = $oItem;
                    break;
                }
            }

            if (isset($oItemContrato)) {

                try {

                    db_inicio_transacao();
                    $oDotacao = new stdClass();
                    $oDotacao->ano         = db_getsession("DB_anousu");
                    $oDotacao->valor       = $oParam->nValor;
                    $oDotacao->dotacao     = $oParam->iDotacao;
                    $oDotacao->quantidade  = str_replace(',', '.', str_replace('.', '', $oParam->nQuantidade));
                    $oItem->adicionarDotacoes($oDotacao);
                    $oRetorno->dotacoes = $oItem->getDotacoes();
                    $oItem->save();
                    $oRetorno->iElementoDotacao = $oItem->getDesdobramento();
                    db_fim_transacao(false);
                } catch (Exception $eErro) {

                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                    $oRetorno->dotacoes = $oItem->getDotacoes();
                }
            } else {

                $oRetorno->status = 2;
                $oRetorno->message = urlencode("O item selecionado n�o foi encontrado.");
            }
        }
        break;

    case "excluirDotacaoItens":
        $oRetorno->iElementoDotacao = '';
        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato = $_SESSION["oContrato"];
            $oPosicao  = $oContrato->getUltimaPosicao();
            $aItens    = $oPosicao->getItens();
            foreach ($aItens as $oItem) {
                if ($oParam->iCodigoItem ==  $oItem->getMaterial()->getMaterial()) {
                    $oItemContrato = $oItem;
                    break;
                }
            }

            if (isset($oItemContrato)) {

                try {

                    db_inicio_transacao();
                    $oItemContrato->removerDotacao($oParam->iDotacao);
                    $oItemContrato->save();

                    $oContrato->atualizaValorContratoPorTotalItens('t');

                    db_fim_transacao(false);
                } catch (Exception $eErro) {

                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                }
            } else {

                $oRetorno->status = 2;
                $oRetorno->message = urlencode("O item selecionado n�o foi encontrado.");
            }
            $oRetorno->dotacoes = $oItem->getDotacoes();
        }

    case "getItensOrigem":

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato = $_SESSION["oContrato"];

            $oDataInicialAcordo        = new DBDate($oContrato->getDataInicial());
            $oRetorno->dtInicialAcordo = $oDataInicialAcordo->convertTo(DBDate::DATA_PTBR);

            $oDataFinalAcordo         = new DBDate($oContrato->getDataFinal());
            $oRetorno->dtFinalAcordo  = $oDataFinalAcordo->convertTo(DBDate::DATA_PTBR);

            /**
             * Quando o usuario fecha o sistema sem incluir os itens, eles deixavam de aparecer, pois
             * dadosSelecaoAcordo deixava de existir na sessao, entao foi adicionado essa condicao para resolver este problema
             */
            if (!isset($_SESSION["dadosSelecaoAcordo"]) || count($_SESSION["dadosSelecaoAcordo"]) == 0) {
                $oLicitacao = licitacao::getLicitacoesByFornecedor($oContrato->getContratado()->getCodigo(), true, true);
                $_SESSION["dadosSelecaoAcordo"][] = $oLicitacao[0]->licitacao;
            }

            if ($oContrato->getOrigem() == 2) {


                $aItens = licitacao::getItensPorFornecedor(
                    $oContrato->getLicitacao(),
                    $oContrato->getContratado()->getCodigo(),
                    0
                );
            } else {

                $aItens = ProcessoCompras::getItensPorFornecedor(
                    $_SESSION["dadosSelecaoAcordo"],
                    $oContrato->getContratado()->getCodigo(),
                    0
                );
            }
            $oRetorno->itens = $aItens;

            $_SESSION["aItensOrigem"] = $aItens;
        }
        break;

    case "adicionarItensOrigem":

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato       = $_SESSION["oContrato"];
            $oPosicao        = $oContrato->getUltimaPosicao();

            if ($oContrato->getOrigem() == 2 || $oContrato->getOrigem() == 1) {

                try {

                    db_inicio_transacao();
                    $lErro = false;

                    $iVigenciaInicial = db_formatar($oContrato->getDataInicial(), 'd');
                    $iVigenciaFinal   = db_formatar($oContrato->getDataFinal(), 'd');

                    foreach ($oParam->aLista as $iIndice => $oItem) {

                        // $iExecucaoInicial = db_formatar($oItem->dtInicial  , 'd') ;
                        // $iExecucaoFinal   = db_formatar($oItem->dtFinal  , 'd') ;

                        // if ($iExecucaoInicial > $iExecucaoFinal) {

                        //     $oErro->codigomaterial = $oItem->codigomaterial;
                        //     throw new Exception(_M($sCaminhoMensagens."periodo_item_maior_execucao", $oErro));
                        // }

                        // if ($iExecucaoInicial < $iVigenciaInicial) {

                        //     $oErro->codigomaterial = $oItem->codigomaterial;
                        //     throw new Exception(_M($sCaminhoMensagens."periodo_item_menor_vigencia", $oErro));
                        // }

                        // if ($iExecucaoFinal > $iVigenciaFinal) {

                        //     $oErro->codigomaterial = $oItem->codigomaterial;
                        //     throw new Exception(_M($sCaminhoMensagens."periodo_execucao_final_maior_vigencia", $oErro));
                        // }

                        if ($oContrato->getOrigem() == 2) {

                            $oPosicao->adicionarItemDeLicitacao($oItem->codigo, $oItem);
                        } else if ($oContrato->getOrigem() == 1) {

                            $oPosicao->adicionarItemDeProcesso($oItem->codigo, $oItem);
                        }
                    }

                    $oContrato->atualizaValorContratoPorTotalItens('t');

                    db_fim_transacao($lErro);
                } catch (Exception $eErro) {

                    db_fim_transacao(true);
                    $oRetorno->status = 2;
                    $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
                }
            }
        }
        break;

    case "excluirFracionamento":

        if (isset($_SESSION["aItensOrigem"])) {

            foreach ($_SESSION["aItensOrigem"] as &$oItem) {

                if ($oItem->codigo == $oParam->iItem) {

                    $oItemOrigem = $oItem;
                    break;
                }
            }

            if (isset($oItemOrigem)) {

                if (isset($oItemOrigem->fracionamentos[$oParam->iFracionamento])) {

                    $oItemOrigem->valortotal += $oItemOrigem->fracionamentos[$oParam->iFracionamento]->valortotal;
                    array_splice($oItemOrigem->fracionamentos, $oParam->iFracionamento, 1);
                }
            }
            $oRetorno->itens = $_SESSION["aItensOrigem"];
        }
        break;

    case "excluirItem":

        if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {

            $oContrato = $_SESSION["oContrato"];
            $oPosicao  = $oContrato->getUltimaPosicao();
            try {

                db_inicio_transacao();

                $oPosicao->removerItem($oParam->material->iCodigo);

                $oContrato->atualizaValorContratoPorTotalItens('t');

                db_fim_transacao(false);
            } catch (Exception $eErro) {

                db_fim_transacao(true);
                $oRetorno->status = 2;
                $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
            }
        }
        break;

    case "getSaldoDotacao":

        $oDotacao             = new Dotacao($oParam->iDotacao, db_getsession("DB_anousu"));
        $oRetorno->saldofinal = $oDotacao->getSaldoFinal();
        break;

    case "getAcordoProgramacaFinanceira":

        if (!empty($oParam->acordo)) {

            $oAcordo                      = new Acordo($oParam->acordo);
            $oRetorno->valortotal         = $oAcordo->getValoresItens();

            $oAcordoProgramacaoFinanceira = new cl_acordoprogramacaofinanceira();
            $sWhere = "ac34_acordo = {$oParam->acordo}";
            $sSqlAcordoProgramacaoFinanceira = $oAcordoProgramacaoFinanceira->sql_query(
                null,
                "acordoprogramacaofinanceira.*",
                null,
                $sWhere
            );
            $rsAcordoProgramacaoFinanceira   = $oAcordoProgramacaoFinanceira->sql_record($sSqlAcordoProgramacaoFinanceira);
            if ($oAcordoProgramacaoFinanceira->numrows > 0) {
                $oProgramacaoFinanceira = db_utils::fieldsMemory($rsAcordoProgramacaoFinanceira, 0);
                $oRetorno->programacaofinanceira = $oProgramacaoFinanceira->ac34_programacaofinanceira;
            } else {
                $oRetorno->programacaofinanceira = null;
            }
        }
        break;

    case "incluirAcordoProgramacaFinanceira":

        try {

            db_inicio_transacao();

            $oAcordoProgramacaoFinanceira = new cl_acordoprogramacaofinanceira();
            $sWhere = "ac34_acordo = {$oParam->acordo} and ac34_programacaofinanceira = {$oParam->codigo}";
            $sSqlAcordoProgramacaoFinanceira = $oAcordoProgramacaoFinanceira->sql_query(
                null,
                "acordoprogramacaofinanceira.*",
                null,
                $sWhere
            );
            $rsAcordoProgramacaoFinanceira   = $oAcordoProgramacaoFinanceira->sql_record($sSqlAcordoProgramacaoFinanceira);
            if ($oAcordoProgramacaoFinanceira->numrows == 0) {

                $oAcordoProgramacaoFinanceira->ac34_programacaofinanceira = $oParam->codigo;
                $oAcordoProgramacaoFinanceira->ac34_acordo                = $oParam->acordo;
                $oAcordoProgramacaoFinanceira->incluir(null);
            }

            db_fim_transacao(false);
        } catch (Exception $eErro) {

            db_fim_transacao(true);
            $oRetorno->status = 2;
            $oRetorno->message = urlencode(str_replace("\\n", "\n", $eErro->getMessage()));
        }
        break;
    case "adicionarDocumento":

        $oAcordo = new Acordo($oParam->acordo);

        try {
            $oAcordo->adicionarDocumento(addslashes(db_stdClass::normalizeStringJson($oParam->descricao)), $oParam->arquivo);
        } catch (Exception $oErro) {

            $oRetorno->message = $oErro->getMessage();
            $oRetorno->status  = 2;
        }
        break;

    case "getDocumento":


        if (isset($oParam->acordo)) {
            $iCodigoAcordo = $oParam->acordo;
        } else if (isset($oParam->ac16_sequencial)) {
            $iCodigoAcordo = $oParam->ac16_sequencial;
        }

        $oAcordo          = new Acordo($iCodigoAcordo);
        $aAcordoDocumento = $oAcordo->getDocumentos();
        $oRetorno->dados  = array();

        for ($i = 0; $i < count($aAcordoDocumento); $i++) {

            $oDocumentos      = new stdClass();
            $oDocumentos->iCodigo    = $aAcordoDocumento[$i]->getCodigo();
            $oDocumentos->iAcordo    = $aAcordoDocumento[$i]->getCodigoAcordo();
            $oDocumentos->sDescricao = urlencode(utf8_encode($aAcordoDocumento[$i]->getDescricao()));
            $oRetorno->dados[] = $oDocumentos;
        }

        $oRetorno->detalhe    = "documentos";
        break;
    case "excluirDocumento":

        $oAcordo          = new Acordo($oParam->acordo);
        try {
            $oAcordo->removeDocumento($oParam->codigoDocumento);
        } catch (Exception $oErro) {

            $oRetorno->message = $oErro->getMessage();
            $oRetorno->status  = 2;
        }

        break;
    case "downloadDocumento":

        $oDocumento = new AcordoDocumento($oParam->iCodigoDocumento);
        db_inicio_transacao();

        // Abrindo o objeto no modo leitura "r" passando como parâmetro o OID.
        $sNomeArquivo = "tmp/{$oDocumento->getNomeArquivo()}";
        pg_lo_export($conn, $oDocumento->getArquivo(), $sNomeArquivo);
        db_fim_transacao(true);
        $oRetorno->nomearquivo = $sNomeArquivo;
        // Setando Cabeçalho do browser para interpretar que o binário que será carregado é de uma foto do tipo JPEG.
        break;

    case "buscaPeriodosItem":

        $oAcordoItem      = new AcordoItem($oParam->iCodigoItem);

        $oRetorno->iCodigoItem = $oParam->iCodigoItem;

        $oRetorno->nomeItem    = $oAcordoItem->getMaterial()->getDescricao();
        $oRetorno->periodos    = $oAcordoItem->getPeriodosItem();

        break;

        /**
         * Exclui um acordo
         *
         * @param integer iAcordo - Código do Acordo
         */
    case 'excluirAcordo':

        try {

            if (!isset($oParam->iAcordo) || empty($oParam->iAcordo)) {
                throw new ParameterException(_M($sCaminhoMensagens . 'acordo_nao_informado'));
            }

            db_inicio_transacao();

            $oAcordo = new Acordo($oParam->iAcordo);
            $oAcordo->remover();

            db_fim_transacao();

            $oRetorno->message = urlencode(_M($sCaminhoMensagens . 'acordo_excluido'));
        } catch (ParameterException $oErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        } catch (BusinessException $oErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        } catch (DBException $oErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        } catch (Exception $oErro) {

            db_fim_transacao(true);
            $oRetorno->status  = 2;
            $oRetorno->message = urlencode($oErro->getMessage());
        }

        break;

    case 'getLeiLicitacao':
        $cl_liclicita = new cl_liclicita;
        $sQueryLicitacao = $cl_liclicita->sql_query_file($oParam->iLicitacao);
        $oResultLicitacao = $cl_liclicita->sql_record($sQueryLicitacao);
        $oRetorno->leiLicitacao = db_utils::fieldsMemory($oResultLicitacao, 0)->l20_leidalicitacao;
        break;
}
/**
 * Função que verifica se a data de assinatura do acordo é anterior a data de homologação da licitação
 * @param $iLicitacao
 * @param $sDataAssinatura
 * @param $bDispensa
 * @return boolean
 */
function validaDataAssinatura($iLicitacao, $sDataAssinatura, $bDispensa = false)
{

    $sCampo = $bDispensa ? "l20_dtpubratificacao" : "l202_datahomologacao";
    $sSql = "select {$sCampo} from homologacaoadjudica where l202_licitacao = {$iLicitacao}";
    $sDataHomolgacao = db_utils::fieldsMemory(db_query($sSql), 0)->$sCampo;
    if (strtotime(str_replace("/", "-", $sDataAssinatura)) < strtotime($sDataHomolgacao)) {
        return false;
    }
    return true;
}
//echo $oJson->encode($oRetorno);
echo json_encode($oRetorno);
