<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
require("model/retencaoNota.model.php");

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

if ($oParam->exec == "addRetencao") {
    try {
        if ($oParam->params[0]->oRetencao->nValorRetencao <= 0) {
            throw new Exception("Erro - O valor da reten��o deve ser maior que zero!");
        }
        $oRetencaoNota = new retencaoNota($oParam->params[0]->oRetencao->iCodNota);
        $oRetencaoNota->setInSession($oParam->params[0]->inSession);

        if ($oRetencaoNota->podeInserirRetencao($oParam->params[0]->oRetencao) === true) {
            $oRetencaoNota->addRetencao($oParam->params[0]->oRetencao, $oParam->params[0]->inSession, $oParam->params[0]->isUpdate);
        } else {
            throw new Exception("Erro - O valor informado deixa o valor total abaixo de 0.");
        }

        $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
        $oRetorno->status = 1;
        $oRetorno->message = "";
        echo $oJson->encode($oRetorno);

    } catch (Exception $eErro) {
        echo $oJson->encode(array("status" => 2, "message" => urlencode($eErro->getMessage())));
    }

} else if ($oParam->exec == 'apagarRetencao') {

    try {

        db_inicio_transacao();
        $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
        $oRetencaoNota->setInSession(true);
        $oRetencaoNota->excluiRetencoes($oParam->params[0]->iRetencao, @$oParam->params[0]->iNotaLiquidacao);
        $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
        $oRetorno->status = 1;
        $oRetorno->message = "";
        db_fim_transacao(false);
        echo $oJson->encode($oRetorno);
    } catch (Exception $eErro) {

        db_fim_transacao(true);
        echo $oJson->encode(array("status" => 2, "message" => urlencode($eErro->getMessage())));

    }

} else if ($oParam->exec == 'getRetencoes') {

    $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
    if (isset($oParam->params[0]->iCodMov)) {
        $oRetencaoNota->setCodigoMovimento($oParam->params[0]->iCodMov);
    }
    $iAnoUsu = "";
    $iMesUsu = "";
    if ($oParam->params[0]->dtCalculo != "") {

        $aData = explode("/", $oParam->params[0]->dtCalculo);
        $iMesUsu = $aData[1];
        $iAnoUsu = $aData[2];

    }
    $oRetencaoNota->getRetencoesFromDB($oParam->params[0]->iCodOrd, true, 2);
    $oRetencaoNota->setInSession(true);
    $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
    $oRetorno->status = 1;
    $oRetorno->message = "";
    echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "saveRetencoes") {

    try {

        db_inicio_transacao();

        $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
        if ($oParam->params[0]->iCodMov != "") {
            $oRetencaoNota->setCodigoMovimento($oParam->params[0]->iCodMov);
        }
        $oRetencaoNota->setInSession(true);
        $oRetencaoNota->salvar($oParam->params[0]->iCodOrd, $oParam->params[0]->aMovimentos);
        $oRetencaoNota->unsetSession();
        db_fim_transacao(false);
        $lMesAnterior = $oRetencaoNota->hasRetencoesMesAnterior();
        echo $oJson->encode(array("status" => 1,
                "message" => urlencode("Reten��es lan�adas com sucesso"),
                "lMesAnterior" => $lMesAnterior)
        );

    } catch (Exception $eErro) {

        db_fim_transacao(true);
        echo $oJson->encode(array("status" => 2, "message" => urlencode($eErro->getMessage())));

    }
} else if ($oParam->exec == "calculaRetencao") {

    require_once 'model/calculoRetencao.model.php';

    /*
     * Calcula o valor da Retencao
     */
    try {

        $oCalculoretencao = new calculoRetencao($oParam->params[0]->iTipoCalc, $oParam->params[0]->iCpfCnpj);
        $oCalculoretencao->setAliquota($oParam->params[0]->nAliquota);
        $oCalculoretencao->setValorNota($oParam->params[0]->nValorNota);
        if (isset($oParam->params[0]->dtPagamento) && $oParam->params[0]->dtPagamento != "") {
            $oCalculoretencao->setDataBase($oParam->params[0]->dtPagamento);
        }
        if (count($oParam->params[0]->aMovimentos) > 0) {
            $oCalculoretencao->setCodigoMovimentos($oParam->params[0]->aMovimentos);
        }
        /*
         * Caso o usuario lancou uma reten��o de IRRF, procuramos as reten��es
         * de ISSQN, e somamos o valor retido no valor a deduzir da base de calculo.
         */
        if ($oParam->params[0]->iTipoCalc == 1 || $oParam->params[0]->iTipoCalc == 2) {

            $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
            $oRetencaoNota->setInSession(true);
            $aRetencoes = $oRetencaoNota->getRetencoes();
            foreach ($aRetencoes as $oRetencaoAtiva) {

                if ($oRetencaoAtiva->e21_retencaotipocalc == 3 || $oRetencaoAtiva->e21_retencaotipocalc == 7) {
                    $oParam->params[0]->nValorDeducao += $oRetencaoAtiva->e23_valorretencao;
                }
            }
        }
        $oCalculoretencao->setDeducao($oParam->params[0]->nValorDeducao);
        $oCalculoretencao->setBaseCalculo($oParam->params[0]->nValorBase);
        $nValorRetencao = $oCalculoretencao->calcularRetencao();
        $nAliquota = $oCalculoretencao->getAliquota();
        $nValorBase = $oCalculoretencao->getValorBaseCalculo();
        echo $oJson->encode(array("status" => 1,
                "message" => "",
                "nValorRetencao" => $nValorRetencao,
                "nAliquota" => $nAliquota,
                "nValorBase" => $nValorBase
            )
        );

    } catch (Exception $eErro) {

        echo $oJson->encode(array("status" => 2, "message" => urlencode($eErro->getMessage())));

    }
} else if ($oParam->exec == "getRetencoesMovimento") {

    $iMesUsu = date("m", db_getsession("DB_datausu"));
    $iAnoUsu = date("Y", db_getsession("DB_datausu"));
    $oRetencaoNota = new retencaoNota($oParam->iCodNota);
    $oRetorno->status = 1;
    $oRetorno->aRetencoes = $oRetencaoNota->getRetencoesByMovimento($oParam->iCodMov, null, true, true);
    if ($oRetorno->aRetencoes == false || (count($oRetorno->aRetencoes) == 0)) {
        $oRetorno->status == 2;
    } else {
        $oDaoRetencaoCorGrupo = db_utils::getDao("retencaocorgrupocorrente");
        for ($i = 0; $i < count($oRetorno->aRetencoes); $i++) {

            $sWhere = " e47_retencaoreceita = {$oRetorno->aRetencoes[$i]->e23_sequencial}";
            $sWhere .= " and corrente.k12_estorn is false and k112_ativo is true ";
            $sOrder = " corrente.k12_data,corrente.k12_autent desc limit 1";
            $sSqlSlip = $oDaoRetencaoCorGrupo->sql_query_numpre_slip(null,
                "slipcorrente.*",
                $sOrder,
                $sWhere
            );
            $rsSlips = $oDaoRetencaoCorGrupo->sql_record($sSqlSlip);
            if ($oDaoRetencaoCorGrupo->numrows > 0) {
                $oRetorno->aRetencoes[$i]->k17_slip = @db_utils::fieldsMemory($rsSlips, 0)->k112_slip;
            }
        }
    }
    echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "getRecibosRetencao") {


    $oRetorno = new stdClass;
    $oRetorno->status = 1;
    $oRetorno->aRecibos = array();

    $sSqlRecibos = "SELECT e21_descricao,";
    $sSqlRecibos .= "       e21_retencaotipocalc , ";
    $sSqlRecibos .= "       case when k12_numnov<> k12_numpre then k12_numnov else k12_numpre end as codarrecad, ";
    $sSqlRecibos .= "       case when k12_numnov<> k12_numpre then 1 else 2 end as tiporecibo, ";
    $sSqlRecibos .= "       k12_valor, ";
    $sSqlRecibos .= "       e23_valorbase, ";
    $sSqlRecibos .= "       e23_aliquota, ";
    $sSqlRecibos .= "       e21_receita, ";
    $sSqlRecibos .= "       k02_descr, ";
    $sSqlRecibos .= "       case when e49_numcgm is null then e60_numcgm else e49_numcgm end as numcgm, ";
    $sSqlRecibos .= "       case when e49_numcgm is null then cgm.z01_nome else cgmordem.z01_nome end as nome, ";
    $sSqlRecibos .= "      k12_data, ";
    $sSqlRecibos .= "      e20_pagordem, ";
    $sSqlRecibos .= "      e60_codemp||'/'||e60_anousu as empenho, ";
    $sSqlRecibos .= "      (case when k00_tipo is null then ";
    $sSqlRecibos .= "        (select k00_tipo from recibo where recibo.k00_numpre = k12_numpre) ";
    $sSqlRecibos .= "      else k00_tipo  ";
    $sSqlRecibos .= "      end ) as k00_tipo ";
    $sSqlRecibos .= " from retencaoreceitas  ";
    $sSqlRecibos .= "       inner join retencaopagordem         on e20_sequencial      = e23_retencaopagordem ";
    $sSqlRecibos .= "       inner join retencaocorgrupocorrente on e23_sequencial      = e47_retencaoreceita ";
    $sSqlRecibos .= "       inner join corgrupocorrente         on k105_sequencial     = e47_corgrupocorrente ";
    $sSqlRecibos .= "       inner join cornump                  on k12_data            = k105_data  ";
    $sSqlRecibos .= "                                          and k12_id              = k105_id ";
    $sSqlRecibos .= "                                          and k12_autent          = k105_autent ";
    $sSqlRecibos .= "      inner join pagordem                  on e20_pagordem        = e50_codord  ";
    $sSqlRecibos .= "      left  join pagordemconta             on e49_codord          = e50_codord  ";
    $sSqlRecibos .= "      left  join cgm cgmordem              on e49_numcgm          = cgmordem.z01_numcgm  ";
    $sSqlRecibos .= "      inner join empempenho                on e50_numemp          = e60_numemp  ";
    $sSqlRecibos .= "      inner join cgm                       on e60_numcgm          = cgm.z01_numcgm  ";
    $sSqlRecibos .= "      left join arrecant                   on k12_numpre          = k00_numpre ";
    $sSqlRecibos .= "      inner join retencaotiporec           on e23_retencaotiporec = e21_sequencial ";
    $sSqlRecibos .= "      inner join tabrec                    on e21_receita         = k02_codigo ";
    $sSqlRecibos .= " where e23_recolhido is true and e23_ativo is true  and  e21_instit = " . db_getsession("DB_instit");

    if ($oParam->iCodOrdem != "") {
        $sSqlRecibos .= "   and e20_pagordem  = {$oParam->iCodOrdem}";
    }
    if ($oParam->iNumCgm != "") {
        $sSqlRecibos .= "   and ( e60_numcgm  = {$oParam->iNumCgm} or e49_numcgm = {$oParam->iNumCgm}) ";
    }
    $rsRecibos = db_query($sSqlRecibos);
    if ($rsRecibos) {
        $oRetorno->aRecibos = db_utils::getColectionByRecord($rsRecibos, false, false, true);
    }
    echo $oJson->encode($oRetorno);
} else if ($oParam->exec == "configurarRetencoes") {

    try {

        db_inicio_transacao();

        $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
        $oRetencaoNota->setINotaLiquidacao($oParam->params[0]->iCodOrd);
        if ($oParam->params[0]->iCodMov != "") {
            $oRetencaoNota->setCodigoMovimento($oParam->params[0]->iCodMov);
        }
        $oRetencaoNota->setInSession(true);
        $oRetencaoNota->configurarPagamentoRetencoes();
        db_fim_transacao(false);
        echo $oJson->encode(array("status" => 1, "message" => urlencode("Reten��es Configuradas com sucesso")));

    } catch (Exception $eErro) {

        db_fim_transacao(true);
        echo $oJson->encode(array("status" => 2, "message" => urlencode($eErro->getMessage())));

    }
} else if ($oParam->exec == "addRetencaoProducaoRural") {
    try {
        $oDataCalculo = date("Y-m-d", db_getsession("DB_datausu"));
        $clretencaotiporec = new cl_retencaotiporec;
        $clretencaotiporec->rotulo->label("e21_sequencial");
        $clretencaotiporec->rotulo->label("e21_descricao");
        $campos = "retencaotiporec.e21_sequencial,retencaotiporec.e21_retencaotipocalc,retencaotiporec.e21_receita,retencaotiporec.e21_descricao,retencaotiporec.e21_aliquota";
        $sql = $clretencaotiporec->sql_query(null, $campos, "e21_retencaotipocalc, e21_sequencial desc", "e21_retencaotipocalc in ('10','11','12') ");

        $rsRetencoes = db_query($sql);
        $hash = '';

        $camposRetencoes = "e69_codnota, e69_numemp, e60_vlrliq, e23_valorbase, e23_aliquota, e23_valorretencao, e23_retencaotiporec, e21_retencaotipocalc, e69_dtnota ";
        $sqlRetencoes = $clretencaotiporec->sql_query_buscar_retencao(null, $camposRetencoes, null, "z01_cgccpf = '{$oParam->params[0]->oRetencao->iCpfCnpj}' and e23_ativo = true and e21_retencaotipocalc in ('10','11','12') AND e23_dtcalculo = '$oDataCalculo' AND  EXTRACT(MONTH FROM (SELECT e69_dtnota FROM empnota WHERE e69_codnota = {$oParam->params[0]->oRetencao->iCodNota})) = EXTRACT(MONTH FROM e69_dtnota);");
        $rsRet = db_query($sqlRetencoes);
        $aRet = db_utils::getCollectionByRecord($rsRet);

        $aRetencoes = db_utils::getCollectionByRecord($rsRetencoes);
        foreach ($aRetencoes as $oRetencoesConferir) {
            $itens [] = $oRetencoesConferir->e21_retencaotipocalc;
        }

        if (!in_array(10, $itens)) {
            throw new Exception("Lan�amento n�o pode ser realizado, reten��es dos tipos de c�lculo 10 n�o encontrada.");
        }

        if (!in_array(11, $itens)) {
            throw new Exception("Lan�amento n�o pode ser realizado, reten��es dos tipos de c�lculo 11 n�o encontrada.");
        }

        if (!in_array(12, $itens)) {
            throw new Exception("Lan�amento n�o pode ser realizado, reten��es dos tipos de c�lculo 12 n�o encontrada.");
        }

        for ($iCont = 0; $iCont < pg_num_rows($rsRetencoes); $iCont++) {
            $oDadosRetencoes = db_utils::fieldsMemory($rsRetencoes, $iCont);

            if ($hash != $oDadosRetencoes->e21_retencaotipocalc) {

                $oParam->params[0]->oRetencao->nValorRetencao = 0;

                if (pg_num_rows($rsRet) > 0) {

                    for ($iContador = 0; $iContador < pg_num_rows($rsRetencoes); $iContador++) {

                        $oDadosRet = db_utils::fieldsMemory($rsRet, $iContador);

                        if ($oDadosRetencoes->e21_retencaotipocalc == 10 && $oDadosRet->e21_retencaotipocalc == 10) {
                            $oParam->params[0]->oRetencao->nAliquota = strlen($oParam->params[0]->oRetencao->iCpfCnpj) == 11 ? '1.20' : '1.70';
                            $oParam->params[0]->oRetencao->nValorRetencao = FormatNumberTwoDecimal((($oParam->params[0]->oRetencao->nAliquota * $oDadosRet->e60_vlrliq) / 100)) - FormatNumberTwoDecimal($oDadosRet->e23_valorretencao);
                            $oParam->params[0]->oRetencao->iCodigoRetencao = $oDadosRetencoes->e21_sequencial;
                        }

                        if ($oDadosRetencoes->e21_retencaotipocalc == 11 && $oDadosRet->e21_retencaotipocalc == 11) {
                            $oParam->params[0]->oRetencao->nAliquota = '0.10';
                            $oParam->params[0]->oRetencao->nValorRetencao = FormatNumberTwoDecimal((($oParam->params[0]->oRetencao->nAliquota * $oDadosRet->e60_vlrliq) / 100)) - FormatNumberTwoDecimal($oDadosRet->e23_valorretencao);
                            $oParam->params[0]->oRetencao->iCodigoRetencao = $oDadosRetencoes->e21_sequencial;
                        }

                        if ($oDadosRetencoes->e21_retencaotipocalc == 12 && $oDadosRet->e21_retencaotipocalc == 12) {
                            $oParam->params[0]->oRetencao->nAliquota = '0.20';
                            $oParam->params[0]->oRetencao->nValorRetencao = FormatNumberTwoDecimal((($oParam->params[0]->oRetencao->nAliquota * $oDadosRet->e60_vlrliq) / 100)) - FormatNumberTwoDecimal($oDadosRet->e23_valorretencao);
                            $oParam->params[0]->oRetencao->iCodigoRetencao = $oDadosRetencoes->e21_sequencial;
                        }

                    }

                } else {

                    if ($oDadosRetencoes->e21_retencaotipocalc == 10) {
                        $oParam->params[0]->oRetencao->nAliquota = strlen($oParam->params[0]->oRetencao->iCpfCnpj) == 11 ? '1.20' : '1.70';
                        $oParam->params[0]->oRetencao->nValorRetencao = arredondarNumero(($oParam->params[0]->oRetencao->nAliquota * $oParam->params[0]->oRetencao->nValorbase) / 100);
                        $oParam->params[0]->oRetencao->iCodigoRetencao = $oDadosRetencoes->e21_sequencial;
                    }

                    if ($oDadosRetencoes->e21_retencaotipocalc == 11) {
                        $oParam->params[0]->oRetencao->nAliquota = '0.10';
                        $oParam->params[0]->oRetencao->nValorRetencao = arredondarNumero(($oParam->params[0]->oRetencao->nAliquota * $oParam->params[0]->oRetencao->nValorbase) / 100);
                        $oParam->params[0]->oRetencao->iCodigoRetencao = $oDadosRetencoes->e21_sequencial;
                    }

                    if ($oDadosRetencoes->e21_retencaotipocalc == 12) {
                        $oParam->params[0]->oRetencao->nAliquota = '0.20';
                        $oParam->params[0]->oRetencao->nValorRetencao = arredondarNumero(($oParam->params[0]->oRetencao->nAliquota * $oParam->params[0]->oRetencao->nValorbase) / 100);
                        $oParam->params[0]->oRetencao->iCodigoRetencao = $oDadosRetencoes->e21_sequencial;
                    }

                }

                $oRetencaoNota = new retencaoNota($oParam->params[0]->oRetencao->iCodNota);
                $oRetencaoNota->setInSession($oParam->params[0]->inSession);

                if ($oRetencaoNota->podeInserirRetencao($oParam->params[0]->oRetencao) === true) {
                    $oRetencaoNota->addRetencao($oParam->params[0]->oRetencao, $oParam->params[0]->inSession, $oParam->params[0]->isUpdate);
                } else {
                    throw new Exception("Erro - O valor informado deixa o valor total abaixo de 0.");
                }

                if ($oParam->params[0]->oRetencao->nValorRetencao <= 0) {
                    throw new Exception("Erro - O valor da reten��o deve ser maior que zero!");
                }

            }

            $hash = $oDadosRetencoes->e21_retencaotipocalc;
        }
        $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
        $oRetorno->status = 1;
        $oRetorno->message = "";
        echo $oJson->encode($oRetorno);

    } catch (Exception $eErro) {
        echo $oJson->encode(array("status" => 2, "message" => urlencode($eErro->getMessage())));
    }
    //$oRetencao = new retencaoNota($oParam->params[0]->oRetencao->iCodNota);
}

function arredondarNumero($numero)
{
    return floor($numero * 100) / 100;
}

function FormatNumberTwoDecimal($value)
{
    $value = str_replace(',', '.', $value);
    $value = floatval($value);
    $number = number_format($value, 3, ',', '.');
    $number = explode(',', $number);
    $decimals = $number[0] . "," . substr($number[1], 0, 2);
    return str_replace(',', '.', $decimals);
}


?>
