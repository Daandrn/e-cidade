<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Eventos;

use cl_rubricasesocial;
use db_utils;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\EventoBase;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\TipoPontoConstants;
use ECidade\RecursosHumanos\ESocial\Agendamento\Eventos\Traits\ValoresPontoEvento;

/**
 * Classe responsavel por montar as informacoes do evento S1207 Esocial
 *
 * @package  ECidade\RecursosHumanos\ESocial\Agendamento\Eventos
 * @author   Robson de Jesus
 */
class EventoS1207 extends EventoBase
{
    use ValoresPontoEvento;

    /**
     *
     * @param \stdClass $dados
     */
    public function __construct($dados)
    {
        parent::__construct($dados);
    }

    /**
     * Retorna dados no formato necessario para envio
     * pela API sped-esocial
     * @return array stdClass
     */
    public function montarDados()
    {
        $ano = $this->ano();
        $mes = $this->mes();
        $aDadosAPI = array();
        $iSequencial = 1;
        foreach ($this->dados as $oDados) {

            $aDadosPorMatriculas = $this->buscarDadosPorMatricula($oDados->z01_cgccpf);
            if ($aDadosPorMatriculas[0]->cpftrab == null) {
                continue;
            }

            $oDadosAPI                                   = new \stdClass();
            $oDadosAPI->evtBenPrRP                      = new \stdClass();
            $oDadosAPI->evtBenPrRP->sequencial          = $iSequencial;
            $oDadosAPI->evtBenPrRP->modo                = $this->modo;
            $oDadosAPI->evtBenPrRP->indRetif            = 1;
            $oDadosAPI->evtBenPrRP->nrRecibo            = null;

            $oDadosAPI->evtBenPrRP->indapuracao         = $this->indapuracao;
            $oDadosAPI->evtBenPrRP->perapur             = $ano . '-' . $mes;
            if ($this->indapuracao == 2) {
                $oDadosAPI->evtBenPrRP->perapur         = $ano;
            }
            $oDadosAPI->evtBenPrRP->cpfbenef             = $aDadosPorMatriculas[0]->cpftrab;

            $std = new \stdClass();
            $seqdmdev = 0;
            for ($iCont = 0; $iCont < count($aDadosPorMatriculas); $iCont++) {
                $aIdentificador = $this->buscarIdentificador($aDadosPorMatriculas[$iCont]->matricula, $aDadosPorMatriculas[$iCont]->rh30_regime);
                for ($iCont2 = 0; $iCont2 < count($aIdentificador); $iCont2++) {
                    $std->dmdev[$seqdmdev] = new \stdClass();

                    if ($aIdentificador[$iCont2]->idedmdev == 1) {
                        $std->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfsal' . $this->mes();
                    }
                    if ($aIdentificador[$iCont2]->idedmdev == 2) {
                        $std->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfres' . $this->mes();
                    }
                    if ($aIdentificador[$iCont2]->idedmdev == 3) {
                        $std->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfcom' . $this->mes();
                    }
                    if ($aIdentificador[$iCont2]->idedmdev == 4) {
                        $std->dmdev[$seqdmdev]->idedmdev = $aDadosPorMatriculas[$iCont]->matricula . 'gerfs13' . $this->mes();
                    }

                    $std->dmdev[$seqdmdev]->nrbeneficio = $aDadosPorMatriculas[$iCont]->matricula_esocial;

                    $std->dmdev[$seqdmdev]->infoperapur = new \stdClass();

                    $std->dmdev[$seqdmdev]->infoperapur->ideestab[0] = new \stdClass();
                    $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->tpinsc = 1;
                    $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->nrinsc = $aDadosPorMatriculas[$iCont]->nrinsc;

                    $aDadosValoreRubrica = $this->buscarValorRubrica($aDadosPorMatriculas[$iCont]->matricula, $aIdentificador[$iCont2]->idedmdev);
                    for ($iCont4 = 0; $iCont4 < count($aDadosValoreRubrica); $iCont4++) {
                        $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->itensremun[$iCont4] = new \stdClass();
                        $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->itensremun[$iCont4]->codrubr = $aDadosValoreRubrica[$iCont4]->codrubr;
                        $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->itensremun[$iCont4]->idetabrubr = $aDadosValoreRubrica[$iCont4]->idetabrubr;
                        $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->itensremun[$iCont4]->vrunit = $aDadosValoreRubrica[$iCont4]->vrrubr;
                        $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->itensremun[$iCont4]->vrrubr = $aDadosValoreRubrica[$iCont4]->vrrubr;
                        $std->dmdev[$seqdmdev]->infoperapur->ideestab[0]->itensremun[$iCont4]->indapurir = $aDadosValoreRubrica[$iCont4]->indapurir;
                    }
                    $seqdmdev++;
                }
            }

            $oDadosAPI->evtBenPrRP->dmdev = $std->dmdev;

            $aDadosAPI[] = $oDadosAPI;
            $iSequencial++;
        }
        return $aDadosAPI;
    }

    private function buscarDadosPorMatricula($cpf)
    {
        $ano = $this->ano();
        $mes = $this->mes();
        $sql = "SELECT
        distinct
        1 as tpInsc,
        cgc as nrInsc,
        z01_cgccpf as cpfTrab,
        rh51_indicadesconto as indMV,
        case when length(rh51_cgcvinculo) = 14 then 1
        when length(rh51_cgcvinculo) = 11 then 2
        end as tpInsc2,
        rh51_cgcvinculo as nrInsc2,
        rh51_basefo as vlrRemunOE,
        'LOTA1' as codLotacao,
        case when rh02_ocorre = '2' then 2
        when rh02_ocorre = '3' then 3
        when rh02_ocorre = '4' then 4
        else '1'
        end as grauExp,
        rh30_regime,
        rh51_cgcvinculo,
        rh01_regist as matricula,
        rh01_esocial as matricula_esocial,
        h13_categoria as codCateg
    from
        rhpessoal
    left join rhpessoalmov on
        rh02_anousu = {$ano}
        and rh02_mesusu = {$mes}
        and rh02_regist = rh01_regist
        and rh02_instit = " . db_getsession("DB_instit") . "
    left join rhinssoutros    on rh51_seqpes                 = rh02_seqpes
    left join rhlota on
        rhlota.r70_codigo = rhpessoalmov.rh02_lota
        and rhlota.r70_instit = rhpessoalmov.rh02_instit
    inner join cgm on
        cgm.z01_numcgm = rhpessoal.rh01_numcgm
    inner join db_config on
        db_config.codigo = rhpessoal.rh01_instit
    inner join rhestcivil on
        rhestcivil.rh08_estciv = rhpessoal.rh01_estciv
    inner join rhraca on
        rhraca.rh18_raca = rhpessoal.rh01_raca
    left join rhfuncao on
        rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
        and rhfuncao.rh37_instit = rhpessoalmov.rh02_instit
    left join rhpescargo on
        rhpescargo.rh20_seqpes = rhpessoalmov.rh02_seqpes
    left join rhcargo on
        rhcargo.rh04_codigo = rhpescargo.rh20_cargo
        and rhcargo.rh04_instit = rhpessoalmov.rh02_instit
    inner join rhinstrucao on
        rhinstrucao.rh21_instru = rhpessoal.rh01_instru
    inner join rhnacionalidade on
        rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion
    left join rhpesrescisao on
        rh02_seqpes = rh05_seqpes
    left join rhsindicato on
        rh01_rhsindicato = rh116_sequencial
    inner join rhreajusteparidade on
        rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade
    left join rhpesdoc on
        rhpesdoc.rh16_regist = rhpessoal.rh01_regist
    left join rhdepend on
        rhdepend.rh31_regist = rhpessoal.rh01_regist
    left join rhregime on
        rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
    left join rhpesfgts on
        rhpesfgts.rh15_regist = rhpessoal.rh01_regist
    inner join tpcontra on
        tpcontra.h13_codigo = rhpessoalmov.rh02_tpcont
    left join rhcontratoemergencial on
        rh163_matricula = rh01_regist
    left join rhcontratoemergencialrenovacao on
        rh164_contratoemergencial = rh163_sequencial
    left join jornadadetrabalho on
        jt_sequencial = rh02_jornadadetrabalho
    left join db_cgmbairro on
        cgm.z01_numcgm = db_cgmbairro.z01_numcgm
    left join bairro on
        bairro.j13_codi = db_cgmbairro.j13_codi
    left join db_cgmruas on
        cgm.z01_numcgm = db_cgmruas.z01_numcgm
    left join ruas on
        ruas.j14_codigo = db_cgmruas.j14_codigo
    left join rescisao on
        rescisao.r59_anousu = rhpessoalmov.rh02_anousu
        and rescisao.r59_mesusu = rhpessoalmov.rh02_mesusu
        and rescisao.r59_regime = rhregime.rh30_regime
        and rescisao.r59_causa = rhpesrescisao.rh05_causa
        and rescisao.r59_caub = rhpesrescisao.rh05_caub::char(2)
    left  outer join (
            SELECT distinct r33_codtab,r33_nome,r33_tiporegime
                                from inssirf
                                where     r33_anousu = {$ano}
                                      and r33_mesusu = {$mes}
                                      and r33_instit = " . db_getsession("DB_instit") . "
                               ) as x on r33_codtab = rhpessoalmov.rh02_tbprev+2
    where rh30_vinculo in ('I','P')
    and cgm.z01_cgccpf = '$cpf'
    and ((rh05_recis is not null
		and date_part('month', rh05_recis) = {$mes}
		and date_part('year', rh05_recis) = {$ano}
		)
		or
		rh05_recis is null
	)";

        $where = " and (exists (SELECT
            1
        from
            gerfsal
        where
            r14_anousu = {$ano}
            and r14_mesusu = {$mes}
            and r14_instit = " . db_getsession("DB_instit") . "
            and r14_regist = rhpessoal.rh01_regist)
            or
            exists (SELECT
            1
        from
            gerfcom
        where
            r48_anousu = {$ano}
            and r48_mesusu = {$mes}
            and r48_instit = " . db_getsession("DB_instit") . "
            and r48_regist = rhpessoal.rh01_regist)
            or
            exists (SELECT
            1
        from
            gerfres
        where
            r20_anousu = {$ano}
            and r20_mesusu = {$mes}
            and r20_instit = " . db_getsession("DB_instit") . "
            and r20_regist = rhpessoal.rh01_regist))";

        if ($this->indapuracao === "2") {
            $where = " and (exists (SELECT
            1
        from
            gerfs13
        where
            r35_anousu = {$ano}
            and r35_mesusu = {$mes}
            and r35_instit = " . db_getsession("DB_instit") . "
            and r35_regist = rhpessoal.rh01_regist)
            )";
        }

        $rsDados = db_query($sql . $where);

        if (pg_num_rows($rsDados) > 0) {
            for ($iCont = 0; $iCont < pg_num_rows($rsDados); $iCont++) {
                $oResult = \db_utils::fieldsMemory($rsDados, $iCont);
                $aItens[] = $oResult;
            }
        }
        return $aItens;
    }

    /**
     * Retorna os valores por rubrica no formato necessario para envio
     * pela API sped-esocial
     * @param int $matricula
     * @param int $ponto
     * @return array stdClass
     */
    private function buscarValorRubrica($matricula, $ponto)
    {
        require_once 'libs/db_libpessoal.php';
        $clrubricasesocial = new cl_rubricasesocial;
        $rsValores = $this->getValoresPorPonto($ponto, $matricula, $this->ano(), $this->mes());
        for ($iCont = 0; $iCont < pg_num_rows($rsValores); $iCont++) {
            $oResult = \db_utils::fieldsMemory($rsValores, $iCont);
            $rubrica = $oResult->rubrica;
            if ($ponto == TipoPontoConstants::PONTO_RESCISAO) {
                $aRubEspeciais = $clrubricasesocial->buscarDadosRubricaEspecial($oResult->rubrica, $oResult->tipo);
                if (count($aRubEspeciais) > 0) {
                    $rubrica = $aRubEspeciais['rubrica'];
                }
            }
            $oFormatado = new \stdClass();
            $oFormatado->codrubr    = $rubrica;
            $oFormatado->idetabrubr = 'tabrub1';
            $oFormatado->vrrubr     = ($oResult->provdesc == 'Provento') ? $oResult->provento : $oResult->desconto;
            $oFormatado->indapurir  = 0;
            $oFormatado->idedmdev   = $oResult->idedmdev;

            $aItens[] = $oFormatado;
        }
        return $aItens;
    }

    /**
     * Busca o identificador de acordo com os pontos existentes para o periodo
     * no formado requerido pela API sped-esocial
     * @param int $matricula
     * @param int $rh30_regime
     * @return array stdClass
     */
    private function buscarIdentificador($matricula, $rh30_regime)
    {
        $iAnoUsu = $this->ano();
        $iMesusu = $this->mes();
        $aPontos = array(TipoPontoConstants::PONTO_13SALARIO);
        if ($this->indapuracao != 2) {
            $aPontos = array(TipoPontoConstants::PONTO_SALARIO, TipoPontoConstants::PONTO_COMPLEMENTAR);
            if ($rh30_regime == 1 || $rh30_regime == 3) {
                $aPontos = array(TipoPontoConstants::PONTO_SALARIO, TipoPontoConstants::PONTO_COMPLEMENTAR, TipoPontoConstants::PONTO_RESCISAO);
            }
        }

        foreach ($aPontos as $opcao) {
            $tipoPonto = $this->getTipoPonto($opcao);
            $sql = "  select distinct
                        case
                        when '{$tipoPonto->arquivo}' = 'gerfsal' then 1
                        when '{$tipoPonto->arquivo}' = 'gerfcom' then 3
                        when '{$tipoPonto->arquivo}' = 'gerfs13' then 4
                        when '{$tipoPonto->arquivo}' = 'gerfres' then 2
                        end as ideDmDev
                        from {$tipoPonto->arquivo}
                        where " . $tipoPonto->sigla . "anousu = '" . $iAnoUsu . "'
                        and  " . $tipoPonto->sigla . "mesusu = '" . $iMesusu . "'
                        and  " . $tipoPonto->sigla . "instit = " . db_getsession("DB_instit") . "
                        and {$tipoPonto->sigla}regist = $matricula";

            $rsIdentificadores = db_query($sql);
            if (pg_num_rows($rsIdentificadores) > 0) {
                for ($iCont = 0; $iCont < pg_num_rows($rsIdentificadores); $iCont++) {
                    $oIdentificadores = \db_utils::fieldsMemory($rsIdentificadores, $iCont);

                    $aItens[] = $oIdentificadores;
                }
            }
        }
        return $aItens;
    }
}