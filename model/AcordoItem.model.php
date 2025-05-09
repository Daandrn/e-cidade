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
require_once("model/contrato/AcordoItemPrevisao.model.php");
/**
 * itens do acordo
 * @package contratos
 */
class AcordoItem
{

    /**
     * Codigo do item
     * @var integer
     */
    protected $iCodigo;

    /**
     * Controla item por quantidade (sim ou n�o)
     * @var boolean
     */
    protected $lControlaQuantidade;

    /**
     * C�digo do material
     * @var integer
     */
    protected $iCodigoMaterial;

    /**
     * C�digo da posi��o do item
     * @var integer
     */
    protected $iCodigoPosicao;

    /**
     * C�digo da tipo posi��o do item
     * @var integer
     */
    protected $iCodigoPosicaoTipo;

    /**
     * Valor unit�rio do item
     * @var float
     */
    protected $nValorUnitario;

    /** OC5304
     * Valor aditado do item
     * @var float
     */
    protected $nValorAditado;

    /** OC5304
     * Quantidade aditada do item
     * @var float
     */
    protected $nQuantidadeAditada;

    /**
     * Quantidade do item
     * @var float
     */
    protected $nQuantidade;

    /**
     * C�digo do elemento do item
     * @var integer
     */
    protected $iElemento;

    /**
     * Descri��o do elemento.
     * @var string
     */
    protected $sDescricaoElemento = "";

    /**
     * valor total do item
     * @var float
     */
    protected $nValorTotal;

    /**
     * Dota�oes dos itens
     * @var unknown_type
     */
    protected $aDotacoes = array();

    /**
     * C�digo do item na licita��o
     * @var integer
     */
    protected $iCodigoItemLicitacao;

    /**
     * C�digo do item no processo de compras
     * @var integer
     */
    protected $iCodigoItemProcesso;

    /**
     * C�digo do item no empenho
     * @var integer
     */
    protected $iCodigoItemEmpenho;

    /**
     * resumo do item
     *
     * @var string
     */
    protected $sResumo = '';

    /**
     * item de origem = Licitacao/Processo Compras
     *
     * @var integer
     */
    protected $iCodigoOrigem;

    /**
     * item do contrato que est� ligado  a ele
     *
     * @var integer
     */
    protected $iItemVinculo;

    /**
     * unidade do material
     *
     * @var integer
     */
    protected $iUnidade;

    /*
     * dataincial
     * @var date
     */
    protected $dtDataInicial;

    /*
   * data inicial
   * @var date
   */
    protected $dtDataFinal;

    /*
   * marca
   * @var text
   */
    protected $sMarca;

    /**
     * Per�dos do Item do Acordo
     */
    protected $aPeriodo = array();

    /**
     * Tipo de controle
     * NORMAL / MENSAL
     * @var integer
     */
    protected $iTipoControle;

    /**
     * Controla Quantidade Servico
     * true / false
     * @var boolean
     */
    protected $iServicoQuantidade;

    /**
     * Array de per�odos de execu��o de um item do acordo
     * @var array
     */
    protected $aPeriodosExecucao = array();


    protected $sDescricaoUnidade;

    /**
     * Ordem do Item inclu�do na base de dados
     * @var integer
     */
    protected $iOrdem;

    /**
     * Estrutural do Elemento
     * Valor referente a tabela orcelemento -> o56_elemento
     * @var string
     */
    protected $sEstruturalElemento;


    /**
     * Descri��o do estrutural do elemento
     * @var string
     */
    protected $sDescricaoEstruturalElemento;

    /**
     * Array de per�odos que o usu�rio informa para um item do acordo
     * @var array
     */
    protected $aPeriodosItem = array();

    /**
     * C�digo do per�odo previs�o do item
     * @var integer
     */
    protected $iCodigoPeriodoPrevisao;

    /**
     * Item que deu origem ao item atual
     * @var AcordoItem
     */
    protected $oItemOrigem;

    /**
     * Cole��o de previs�es de execu��o do item
     * @var AcordoItemPrevisao[]
     */
    protected $aPrevisaoExecucao;

    protected $iLicitacao;

    protected $iContrato;

    protected $iContratado;

    protected $iQtdcontratada;

    protected $iLicitem;

    protected $iTipocompratribunal;

    /**
     * Divis�o Mensal das Quantidades
     * @var integer
     */
    const CONTROLE_DIVISAO_QUANTIDADE = 1;

    /**
     * Divis�o Mensal de Valores (m�s)
     * @var integer
     */
    const CONTROLE_DIVISAO_VALOR = 3;

    /**
     * Por Valor
     * @var integer
     */
    const CONTROLE_VALOR = 4;

    /**
     * Por Quantidade
     * @var integer
     */
    const CONTROLE_QUANTIDADE = 5;

    /**
     * @var AcordoPosicao
     */
    private $oPosicao;

    /**
     * Execu��es do item
     * @var AcordoItemExecucao[]
     */
    private $aExecucoes = array();

    /**
     * Quantidade Atualizada do Item
     * @var float
     */
    private $nValorAtualizado = 0;

    /**
     * Quantidade Atualizada do valor
     */
    private $nQuantidadeAtualizada = 0;

    /**
     * Controle de cache dos valores atualizados do item
     * @var bool
     */
    private $lValoresJaAtualizados = false;

    /**
     * Quantidade atualizada do item a partir da ultima posi��o de renova��o ou inclus�o
     * @var integer
     */
    private $nQuantidadeAtualizadaRenovacao = 0;

    /**
     * Valor atualizado do item a partir da ultima posi��o de renova��o ou inclus�o
     * @var integer
     */
    private $nValorAtualizadoRenovacao = 0;

    /**
     * Parametro de obrigatorierade da data de vig�ncia
     * @var boolean
     */
    public $lParametroVigencia = false;

    /**
     * @param integer $iCodigoItem c�digo do item
     */
    public function __construct($iCodigoItem = null)
    {

        $oDaoParametro = db_utils::getDao('parametroscontratos');
        $oDaoParametro = $oDaoParametro->sql_query(null, '*');
        $oDaoParametro = db_query($oDaoParametro);
        $oDaoParametro = db_utils::fieldsMemory($oDaoParametro);
        $this->lParametroVigencia = ($oDaoParametro->pc01_liberarcadastrosemvigencia == 't') ? true : false;

        if (!empty($iCodigoItem) != null) {

            $oDaoAcordoItem = db_utils::getDao("acordoitem");

            $sCamposItens  = "ac20_sequencial,                                         ";
            $sCamposItens .= "ac20_pcmater,                                            ";
            $sCamposItens .= "ac20_elemento,                                           ";
            $sCamposItens .= "ac20_matunid,                                            ";
            $sCamposItens .= "ac20_quantidade,                                         ";
            $sCamposItens .= "ac20_valortotal,                                         ";
            $sCamposItens .= "ac20_valorunitario,                                      ";
            $sCamposItens .= "ac20_marca,                                      ";
            $sCamposItens .= "ac20_acordoposicao,                                      ";
            $sCamposItens .= "ac20_acordoposicaotipo,                                  ";
            $sCamposItens .= "ac20_resumo,                                             ";
            $sCamposItens .= "ac20_tipocontrole,                                       ";
            $sCamposItens .= "ac20_servicoquantidade,                                  ";
            $sCamposItens .= "ac20_ordem,                                              ";
            $sCamposItens .= "ac20_valoraditado,                                       "; //OC5304
            $sCamposItens .= "ac20_quantidadeaditada,                                  "; //OC5304
            $sCamposItens .= "m61_descr,                                               ";
            $sCamposItens .= "o56_elemento,                                            ";
            $sCamposItens .= "o56_descr,                                               ";
            $sCamposItens .= "ac33_acordoitempai,                                      ";
            $sCamposItens .= "ac23_pcprocitem,                                         ";
            $sCamposItens .= "ac24_liclicitem,                                         ";
            $sCamposItens .= "ac44_empempitem,                                         ";
            $sCamposItens .= "ac24_liclicitem,                                         ";
            $sCamposItens .= "l21_codliclicita,                                        ";
            $sCamposItens .= "pcprocitem_compras.pc81_codproc,                         ";
            $sCamposItens .= "case                                                      ";
            $sCamposItens .= "  when solicitem_licitacao.pc11_servicoquantidade is null ";
            $sCamposItens .= "	   then solicitem_compras.pc11_servicoquantidade         ";
            $sCamposItens .= "    else solicitem_licitacao.pc11_servicoquantidade       ";
            $sCamposItens .= "end  as pc11_servicoquantidade                            ";

            $sSqlAcordoitem = $oDaoAcordoItem->sql_query_completo(null, $sCamposItens, null, "ac20_sequencial = $iCodigoItem");

            $rsAcordoItem   = $oDaoAcordoItem->sql_record($sSqlAcordoitem);
            if ($oDaoAcordoItem->numrows > 0) {

                $oDadosItem = db_utils::fieldsMemory($rsAcordoItem, 0, false, false, true);

                /**
                 * Caso nao tenha da solicitacao, pegar o que foi salvo do contrato
                 */
                if ($oDadosItem->pc11_servicoquantidade == '' && $oDadosItem->ac20_servicoquantidade != '') {
                    $oDadosItem->pc11_servicoquantidade = $oDadosItem->ac20_servicoquantidade;
                }

                $this->setCodigo($oDadosItem->ac20_sequencial)
                    ->setMaterial(new MaterialCompras($oDadosItem->ac20_pcmater))
                    ->setElemento($oDadosItem->ac20_elemento)
                    ->setUnidade($oDadosItem->ac20_matunid)
                    ->setQuantidade($oDadosItem->ac20_quantidade)
                    ->setValorAditado($oDadosItem->ac20_valoraditado)
                    ->setQuantiAditada($oDadosItem->ac20_quantidadeaditada)
                    ->setValorTotal($oDadosItem->ac20_valortotal)
                    ->setValorUnitario($oDadosItem->ac20_valorunitario)
                    ->setCodigoPosicao($oDadosItem->ac20_acordoposicao)
                    ->setCodigoPosicaoTipo($oDadosItem->ac20_acordoposicaotipo)
                    ->setResumo($oDadosItem->ac20_resumo)
                    ->setMarca($oDadosItem->ac20_marca)
                    ->setDescricaoUnidade($oDadosItem->m61_descr)
                    ->setTipoControle($oDadosItem->ac20_tipocontrole)
                    ->setEstruturalElemento($oDadosItem->o56_elemento)
                    ->setDescEstruturalElemento($oDadosItem->o56_descr)
                    ->setControlaQuantidade($oDadosItem->pc11_servicoquantidade == '' ? 'f' : $oDadosItem->pc11_servicoquantidade)
                    ->setServicoQuantidade($oDadosItem->ac20_servicoquantidade == '' ? $this->getControlaQuantidade() : $oDadosItem->ac20_servicoquantidade)
                    ->setOrdem($oDadosItem->ac20_ordem);
                $this->sDescricaoElemento = $oDadosItem->o56_descr;
                /**
                 * Caso estejam diferentes, manter o valor de pc11_servicoquantidade
                 */
                $this->setServicoQuantidade($this->getControlaQuantidade());


                $sSqlItemPeriodo = $oDaoAcordoItem->sql_query_periodo($iCodigoItem);
                $rsItemPeriodo   = $oDaoAcordoItem->sql_record($sSqlItemPeriodo);
                $iItemPeriodo    = $oDaoAcordoItem->numrows;

                for ($i = 0; $i < $iItemPeriodo; $i++) {

                    $oItemPeriodos              = db_utils::fieldsMemory($rsItemPeriodo, $i);
                    $oPeriodos                  = new stdClass();
                    $oPeriodos->dtDataInicial   = $oItemPeriodos->ac41_datainicial;
                    $oPeriodos->dtDataFinal     = $oItemPeriodos->ac41_datafinal;
                    $oPeriodos->ac41_sequencial = $oItemPeriodos->ac41_sequencial;

                    $this->aPeriodosItem[] = $oPeriodos;
                }

                $this->iItemVinculo = $oDadosItem->ac33_acordoitempai;

                if ($oDadosItem->ac23_pcprocitem != "") {
                    $this->setOrigem($oDadosItem->ac23_pcprocitem, 1, $oDadosItem->pc81_codproc);
                }

                if ($oDadosItem->ac24_liclicitem != "") {
                    $this->setOrigem($oDadosItem->ac24_liclicitem, 2, $oDadosItem->l21_codliclicita);
                }

                if (!empty($oDadosItem->ac44_empempitem)) {
                    $this->setOrigem($oDadosItem->ac44_empempitem, 6);
                }
                unset($oDadosItem);
                $this->getDotacoes();
            }
        }
    }


    /**
     * @return array
     */
    public function getDotacoes()
    {

        if (count($this->aDotacoes) == 0) {

            $oDaoAcordoItemDotacao  = new cl_acordoitemdotacao;
            $sSqlSomaExecucao       = " COALESCE((select sum(ac32_valor)";
            $sSqlSomaExecucao      .= "    from acordoitemexecutadodotacao";
            $sSqlSomaExecucao      .= "   where ac32_acordoitem = ac22_acordoitem ";
            $sSqlSomaExecucao      .= "     and ac32_coddot     = ac22_coddot),0) as executado,";
            $sSqlDotacoes           = $oDaoAcordoItemDotacao->sql_query_reserva(
                null,
                "ac22_coddot as dotacao,
        {$sSqlSomaExecucao}
        ac22_quantidade as quantidade,
        ac22_sequencial  as codigodotacaoitem,
        ac22_valor as valor,
        o84_orcreserva as reserva,
        o80_valor      as valorreserva,
        ac22_anousu as ano",
                "ac22_coddot",
                "ac22_acordoitem={$this->getCodigo()}
        and ac22_anousu=" . db_getsession("DB_anousu") . ""
            );
            $rsDotacoes            = $oDaoAcordoItemDotacao->sql_record($sSqlDotacoes);
            $this->aDotacoes  = db_utils::getCollectionByRecord($rsDotacoes);
        }
        // var_dump($sSqlDotacoes);
        // var_dump($this->aDotacoes);
        // die();
        return $this->aDotacoes;
    }

    public function getDotacoesAdesao()
    {

        if (count($this->aDotacoes) == 0) {

            $oDaoAcordoItemDotacao  = new cl_acordoitemdotacao;
            $sSqlDotacoes           = "select
	o58_coddot as dotacao,
	pc13_quant as quantidade,
	pc13_sequencial as codigodotacaoitem,
	pc13_valor as valor,
	o80_codres as reserva,
	o80_valor as valorreserva,
	pc13_anousu as ano
from acordoitem
join acordopcprocitem on (ac23_acordoitem) = (ac20_sequencial)
join pcprocitem  ON (ac23_pcprocitem) = (pc81_codprocitem)
join solicitem ON (pcprocitem.pc81_solicitem = solicitem.pc11_codigo)
join pcdotac on (pcdotac.pc13_codigo = solicitem.pc11_codigo)
join orcdotacao on (pc13_anousu, pc13_coddot) = (o58_anousu, o58_coddot)
left join orcreservasol on (o82_solicitem) = (pc11_codigo)
left join orcreserva on (o82_codres) = (o80_codres)
--join solicitempcmater on (pc16_solicitem) = solicitem(pc11_codigo)
where
	ac20_sequencial = {$this->getCodigo()}
	and pc13_anousu = " . db_getsession("DB_anousu") . "
order by
	pc13_coddot";

            $rsDotacoes            = $oDaoAcordoItemDotacao->sql_record($sSqlDotacoes);
            $this->aDotacoes  = db_utils::getCollectionByRecord($rsDotacoes);
        }

        return $this->aDotacoes;
    }

    /**
     * @param unknown_type $aDotacoes
     * @return AcordoItem
     */
    public function adicionarDotacoes($oDotacao)
    {

        /**
         * Verifica o se existe saldo para a inclusao da dotacao
         */
        $nTotalItem  = 0;
        $nTotalValor = 0;
        foreach ($this->aDotacoes as $oDotacaoTemp) {

            $nTotalItem  += $oDotacaoTemp->quantidade;
            $nTotalValor += $oDotacaoTemp->valor;
        }

        $this->aDotacoes[] = $oDotacao;
        return $this;
    }

    public function removerDotacao($iDotacao = '')
    {


        if ($iDotacao == '') {

            $this->aDotacoes = array();
        } else {

            $oDaoAcordoItemDotacaoReserva = db_utils::getDao("orcreservaacordoitemdotacao");
            $oDaoReserva                  = db_utils::getDao("orcreserva");
            $oDaoAcordoItemDotacao = db_utils::getDao("acordoitemdotacao");
            $sSqlDotacoes          = $oDaoAcordoItemDotacao->sql_query_file(
                null,
                "*",
                null,
                "ac22_acordoitem={$this->getCodigo()} and ac22_coddot = {$iDotacao}"
            );

            $rsDotacoes           = $oDaoAcordoItemDotacao->sql_record($sSqlDotacoes);
            $iNumRowsDotacao      = $oDaoAcordoItemDotacao->numrows;
            $oDotacaoCadastrada = db_utils::fieldsMemory($rsDotacoes, 0);

            $sSqlReserva = $oDaoAcordoItemDotacaoReserva->sql_query_file(
                null,
                "*",
                null,
                "o84_acordoitemdotacao =
       {$oDotacaoCadastrada->ac22_sequencial}"
            );
            $rsReservaItem = $oDaoAcordoItemDotacaoReserva->sql_record($sSqlReserva);
            if ($oDaoAcordoItemDotacaoReserva->numrows > 0) {

                $oDadosReserva = db_utils::fieldsMemory($rsReservaItem, 0);
                $oDaoAcordoItemDotacaoReserva->excluir($oDadosReserva->o84_sequencial);
                $oDaoReserva->excluir($oDadosReserva->o84_orcreserva);
            }
            $oDaoAcordoItemDotacao->excluir($oDotacaoCadastrada->ac22_sequencial);
            $this->aDotacoes = array();
            $this->getDotacoes();
        }
    }

    public function inserirItensDotacao($oItem)
    {
        $iAnoSessao = db_getsession("DB_anousu");

        if (empty($oItem->iCodigoDotacao)) {
            throw new Exception("ERRO [ 0 ] - Dota��o n�o Informada.");
        }

        if (empty($oItem->iAnoDotacao)) {
            $oItem->iAnoDotacao = $iAnoSessao;
        }

        if ($oItem->iAnoDotacao > $iAnoSessao) {
            throw new Exception("Dotacao {$oItem->iCodigoDotacao}/{$oItem->iAnoDotacao} deve ser uma dota��o do Exerc�cio");
        }

        $oItemAcordoDotacao = db_utils::getDao('acordoitemdotacao');


        $oItemAcordoDotacao->ac22_coddot = $oItem->iCodigoDotacao;
        $oItemAcordoDotacao->ac22_anousu = $iAnoSessao;
        $oItemAcordoDotacao->ac22_acordoitem = $oItem->iCodigoItem;
        $oItemAcordoDotacao->ac22_valor = $oItem->nValor;
        $oItemAcordoDotacao->ac22_quantidade = $oItem->nQuantidade;

        $oItemAcordoDotacao->incluir();

        if ($oItemAcordoDotacao->erro_status == '0') {
            throw new Exception($oItemAcordoDotacao->erro_msg . "Erro");
        }
    }

    /**
     * Realiza a altera��o de uma determinada dota��o por outra.
     *
     * @param integer $iCodigoDotacaoItem C�digo da dota��o do item pcdotac.pc13_sequencial;
     * @param integer $iCodigoDotacao     Codigo da Dota��o no ano
     * @param integer $iAnoDotacao        Ano da Dota��o;
     * @throws Exception
     */
    public function alterarDotacao($oItem)
    {

        $iAnoSessao = db_getsession("DB_anousu");

        if (empty($oItem->iCodigoDotacao)) {
            throw new Exception("ERRO [ 0 ] - Dota��o n�o Informada.");
        }

        if (empty($oItem->iAnoDotacao)) {
            $oItem->iAnoDotacao = $iAnoSessao;
        }

        if ($oItem->iAnoDotacao > $iAnoSessao) {
            throw new Exception("Dotacao {$oItem->iCodigoDotacao}/{$oItem->iAnoDotacao} deve ser uma dota��o do Exerc�cio");
        }

        /* Remove a dota��o do item */
        $oItemAcordoDotacao = db_utils::getDao('acordoitemdotacao');

        $oItemAcordoDotacao->excluir('', "ac22_coddot = $oItem->iCodigoDotacaoItem AND ac22_acordoitem = $oItem->iCodigoItem AND ac22_anousu = $oItem->iAnoDotAnterior");

        $sSqlVlr = " SELECT ac20_sequencial,
                      ac20_valorunitario,
                      ac20_quantidade
               FROM acordoposicao
               JOIN acordoitem ON ac20_acordoposicao = ac26_sequencial
               WHERE ac20_acordoposicao =
                    (SELECT max(ac26_sequencial)
                     FROM acordoposicao
                     WHERE ac26_acordo = $oItem->iAcordo) and ac20_sequencial = " . $oItem->iCodigoItem;

        $rsItem = db_query($sSqlVlr);
        $oNewItem = db_utils::fieldsMemory($rsItem, 0);

        /*
		 * Insere a nova dota��o do item
		 * */

        $oItemAcordoDotacao->ac22_coddot = $oItem->iCodigoDotacao;
        $oItemAcordoDotacao->ac22_anousu = $iAnoSessao;
        $oItemAcordoDotacao->ac22_acordoitem = $oNewItem->ac20_sequencial;
        $oItemAcordoDotacao->ac22_valor = $oNewItem->ac20_valorunitario;
        $oItemAcordoDotacao->ac22_quantidade = $oNewItem->ac20_quantidade;

        $oItemAcordoDotacao->incluir();

        if ($oItemAcordoDotacao->erro_status == '0') {
            throw new Exception($oItemAcordoDotacao->erro_msg);
        }
    }
    /**
     * Busca o c�digo do per�odo da previs�o
     * @return integer
     */
    function getCodigoPeriodoPrevisao()
    {
        return $this->iCodigoPeriodoPrevisao;
    }

    /**
     * Define o c�digo do per�odo da previs�o
     * @param item $iCodigoPeriodoPrevisao
     * @return AcordoItem
     */
    function setCodigoPeriodoPrevisao($iCodigoPeriodoPrevisao)
    {

        $this->iCodigoPeriodoPrevisao = $iCodigoPeriodoPrevisao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getILicitacao()
    {
        return $this->iLicitacao;
    }

    /**
     * @param mixed $iLicitacao
     */
    public function setILicitacao($iLicitacao)
    {
        $this->iLicitacao = $iLicitacao;
    }

    /**
     * @return mixed
     */
    public function getIContrato()
    {
        return $this->iContrato;
    }

    /**
     * @param mixed $iContrato
     */
    public function setIContrato($iContrato)
    {
        $this->iContrato = $iContrato;
    }

    /**
     * @return mixed
     */
    public function getIContratado()
    {
        return $this->iContratado;
    }

    /**
     * @param mixed $iContratado
     */
    public function setIContratado($iContratado)
    {
        $this->iContratado = $iContratado;
    }

    /**
     * @return mixed
     */
    public function getIQtdcontratada()
    {
        return $this->iQtdcontratada;
    }

    /**
     * @param mixed $iQtdcontratada
     */
    public function setIQtdcontratada($iQtdcontratada)
    {
        $this->iQtdcontratada = $iQtdcontratada;
    }

    /**
     * @return mixed
     */
    public function getILicitem()
    {
        return $this->iLicitem;
    }

    /**
     * @param mixed $iLicitem
     */
    public function setILicitem($iLicitem)
    {
        $this->iLicitem = $iLicitem;
    }

    /**
     * @return mixed
     */
    public function getITipocompratribunal()
    {
        return $this->iTipocompratribunal;
    }

    /**
     * @param mixed $iTipocompratribunal
     */
    public function setITipocompratribunal($iTipocompratribunal)
    {
        $this->iTipocompratribunal = $iTipocompratribunal;
    }

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    /**
     * Retorna se controla o item por quantidade ou n�o
     * @return boolean
     */
    public function getControlaQuantidade()
    {
        return $this->lControlaQuantidade;
    }

    /**
     * Seta o booleando de controle por quantidade
     * @param boolean $lControlaQuantidade
     * @return AcordoItem
     */
    public function setControlaQuantidade($lControlaQuantidade)
    {

        $this->lControlaQuantidade = $lControlaQuantidade;
        return $this;
    }

    /**
     * @param integer $iCodigo
     * @return AcordoItem
     */
    public function setCodigo($iCodigo)
    {

        $this->iCodigo = $iCodigo;
        return $this;
    }

    /**
     *
     * @return MaterialCompras
     *
     */
    public function getMaterial()
    {
        return $this->iCodigoMaterial;
    }

    /**
     * @param integer $iCodigoMaterial
     * @return AcordoItem
     */
    public function setMaterial(MaterialCompras  $iCodigoMaterial)
    {

        $this->iCodigoMaterial = $iCodigoMaterial;
        return $this;
    }

    /**
     * @return integer
     */
    public function getElemento()
    {

        return $this->iElemento;
    }

    /**
     * @param integer $iElemento
     * @return AcordoItem
     */
    public function setElemento($iElemento)
    {

        $this->iElemento = $iElemento;
        return $this;
    }

    /**
     * Retorna a descri��o do elemento.
     * @return string $this->sDescricaoElemento
     */
    public function getDescricaoElemento()
    {
        return $this->sDescricaoElemento;
    }


    /**
     * @return float
     */
    public function getQuantidade()
    {
        return $this->nQuantidade;
    }

    /**
     * @param float $nQuantidade
     * @return AcordoItem
     */
    public function setQuantidade($nQuantidade)
    {

        $this->nQuantidade = $nQuantidade;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorTotal()
    {

        return $this->nValorTotal;
    }

    /**
     * @param float $nValorTotal
     * @return AcordoItem
     */
    public function setValorTotal($nValorTotal)
    {

        $this->nValorTotal = $nValorTotal;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorUnitario()
    {

        return $this->nValorUnitario;
    }

    /**
     * @param float $nValorUnitario
     * @return AcordoItem
     */
    public function setValorUnitario($nValorUnitario)
    {

        $this->nValorUnitario = $nValorUnitario;
        return $this;
    }

    /** OC5304
     * @return float
     */
    public function getValorAditado()
    {

        return $this->nValorAditado;
    }

    /** OC5304
     * @param float $nValorAditado
     * @return AcordoItem
     */
    public function setValorAditado($nValorAditado)
    {

        $this->nValorAditado = $nValorAditado;
        return $this;
    }

    /** OC5304
     * @return float
     */
    public function getQuantiAditada()
    {

        return $this->nQuantidadeAditada;
    }

    /** OC5304
     * @param float $nQuantidadeAditada
     * @return AcordoItem
     */
    public function setQuantiAditada($nQuantidadeAditada)
    {

        $this->nQuantidadeAditada = $nQuantidadeAditada;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCodigoPosicao()
    {

        return $this->iCodigoPosicao;
    }

    /**
     * @param integer $iCodigoPosicao
     * @return AcordoItem
     */
    public function setCodigoPosicao($iCodigoPosicao)
    {

        $this->iCodigoPosicao = $iCodigoPosicao;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoPosicaoTipo()
    {
        return $this->iCodigoPosicaoTipo;
    }

    /**
     * @param int $iCodigoPosicaoTipo
     * @return AcordoItem
     */
    public function setCodigoPosicaoTipo($iCodigoPosicaoTipo)
    {
        $this->iCodigoPosicaoTipo = $iCodigoPosicaoTipo;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUnidade()
    {

        return $this->iUnidade;
    }

    /**
     * @param integer $iUnidade
     * @return AcordoItem
     */
    public function setUnidade($iUnidade)
    {

        $this->iUnidade = $iUnidade;
        return $this;
    }
    /**
     * @return string
     */
    public function getResumo()
    {

        return $this->sResumo;
    }

    /**
     * Define o resumo do item
     * @param string $sResumo
     * @return AcordoItem
     */
    public function setResumo($sResumo)
    {

        $this->sResumo = $sResumo;
        return $this;
    }

    /**
     * Define a data de inicio
     * @param date $dtDatainicial
     * @return AcordoItem
     */
    public function setDataInicial($dtDataInicial)
    {

        $this->dtDataInicial = $dtDataInicial;
        return $this;
    }

    public function getDataInicial()
    {

        return $this->dtDataInicial;
    }


    /**
     * Define a marca
     * @param text $sMarca
     * @return AcordoItem
     */
    public function setMarca($sMarca)
    {

        $this->sMarca = $sMarca;
        return $this;
    }

    public function getMarca()
    {

        return $this->sMarca;
    }

    /**
     * Define a data final
     * @param date $dtDatafinal
     * @return AcordoItem
     */
    public function setDataFinal($dtDataFinal)
    {

        $this->dtDataFinal = $dtDataFinal;
        return $this;
    }

    public function getDataFinal()
    {

        return $this->dtDataFinal;
    }

    /**
     * Define o tipo de controle
     * @param integer $sTipoControle
     * @return AcordoItem
     */
    public function setTipoControle($iTipocontrole)
    {

        $this->iTipoControle = $iTipocontrole;
        return $this;
    }

    public function getTipocontrole()
    {

        return $this->iTipoControle;
    }

    /**
     * Define se controla quantidade em servico
     * @param boolean $sTipoControle
     * @return AcordoItem
     */
    public function setServicoQuantidade($iServicoQuantidade)
    {

        $this->iServicoQuantidade = $iServicoQuantidade;
        return $this;
    }

    public function getServicoQuantidade()
    {

        return $this->iServicoQuantidade;
    }

    /**
     * Persiste o item na base de dados
     *
     * @return AcordoItem
     */
    public function save($lReservarSaldo = false)
    {
        $cl_credenciamentosaldo = new cl_credenciamentosaldo();

        if ($this->getCodigo() == "") {
            $this->setOrdem($this->getProximaPosicao());
        }

        $nValorUnitario     = (float)$this->getValorUnitario();
        $nValorAditado      = (float)$this->getValorAditado(); //OC5304
        $nQuantidadeAditada = (float)$this->getQuantiAditada(); //OC5304

        $oDaoAcordoItem = db_utils::getDao("acordoitem");
        $oDaoAcordoItem->ac20_acordoposicao     = $this->getCodigoPosicao();
        $oDaoAcordoItem->ac20_acordoposicaotipo = $this->getCodigoPosicaoTipo();
        $oDaoAcordoItem->ac20_pcmater           = $this->getMaterial()->getMaterial();
        $oDaoAcordoItem->ac20_elemento          = $this->getElemento();
        $oDaoAcordoItem->ac20_anousu            = db_getsession("DB_anousu");
        $oDaoAcordoItem->ac20_matunid           = $this->getUnidade();
        $oDaoAcordoItem->ac20_quantidade        = "{$this->getQuantidade()}";
        $oDaoAcordoItem->ac20_valorunitario     = $nValorUnitario;
        $oDaoAcordoItem->ac20_marca             = $this->getMarca();
        $oDaoAcordoItem->ac20_valoraditado      = $nValorAditado; //OC5304
        $oDaoAcordoItem->ac20_quantidadeaditada = $nQuantidadeAditada; //OC5304
        $oDaoAcordoItem->ac20_valortotal        = "" . round(($this->getQuantidade() * $nValorUnitario), 2) . "";
        $this->setValorTotal(($this->getQuantidade() * $nValorUnitario));
        $oDaoAcordoItem->ac20_ordem             = $this->getOrdem();
        $oDaoAcordoItem->ac20_resumo            = addslashes($this->getResumo());
        $oDaoAcordoItem->ac20_tipocontrole      = $this->getTipocontrole();
        $oDaoAcordoItem->ac20_servicoquantidade = !$this->getServicoQuantidade() ? 'f' : $this->getServicoQuantidade();

        /**
         * Credenciamentosaldo
         * OC8339
         */

        if ($this->getITipocompratribunal() == "103" || $this->getITipocompratribunal() == "102") {
            $cl_credenciamentosaldo->l213_licitacao = $this->getILicitacao();
            $cl_credenciamentosaldo->l213_item = $this->getCodigo();
            $cl_credenciamentosaldo->l213_qtdcontratada = $this->getIQtdcontratada();
            $cl_credenciamentosaldo->l213_valorcontratado = "" . round(($this->getQuantidade() * $nValorUnitario), 2) . "";
            $cl_credenciamentosaldo->l213_contratado = $this->getIContratado();
            $cl_credenciamentosaldo->l213_acordo = $this->getIContrato();
            $cl_credenciamentosaldo->l213_itemlicitacao = $this->getILicitem();
        }

        /**
         * Define se ser� alterado o contrato ou inclu�do um novo
         */
        if ($this->getCodigo() != "") {

            $oDaoAcordoItem->ac20_sequencial = $this->getCodigo();
            $oDaoAcordoItem->alterar($this->getCodigo());

            /**
             * alterar Credenciamentosaldo
             * OC8339
             */

            if ($this->getITipocompratribunal() == "103" || $this->getITipocompratribunal() == "102") {
                $rsCredenciamentosaldo = $cl_credenciamentosaldo->sql_record($cl_credenciamentosaldo->sql_query_file(null, "*", null, "l213_licitacao = {$this->getILicitacao()} and l213_item={$this->getCodigo()} and l213_contratado={$this->getIContratado()} and l213_acordo = {$this->getIContrato()}"));
                $oCredenciamentosaldo = db_utils::fieldsMemory($rsCredenciamentosaldo, 0);
                $cl_credenciamentosaldo->l213_qtdlicitada = $oCredenciamentosaldo->l213_qtdlicitada;
                $cl_credenciamentosaldo->alterar($oCredenciamentosaldo->l213_sequencial);
            }
        } else {

            $oDaoAcordoItem->incluir(null);
            if ($oDaoAcordoItem->erro_status == 0) {
                throw new Exception("N�o foi poss�vel salvar �tem do acordo.\n\n{$oDaoAcordoItem->erro_msg}");
            }
            $this->setCodigo($oDaoAcordoItem->ac20_sequencial);

            /**
             * Incluir Credenciamentosaldo
             * OC8339
             */

            if ($this->getITipocompratribunal() == "103" || $this->getITipocompratribunal() == "102") {
                $cl_credenciamentosaldo->l213_item = $this->getCodigo();
                $cl_credenciamentosaldo->l213_qtdlicitada = $this->getQuantidade();
                $cl_credenciamentosaldo->incluir();

                if ($cl_credenciamentosaldo->erro_status == 0) {
                    throw new Exception("N�o foi poss�vel salvar �tem do acordo.\n\n{$cl_credenciamentosaldo->erro_msg}");
                }
            }

            /**
             * SALVA NA LICLICITEM
             */
            if ($this->iCodigoItemLicitacao != "") {

                $oDaoVinculoLicitacao = db_utils::getDao("acordoliclicitem");
                $oDaoVinculoLicitacao->ac24_acordoitem = $this->getCodigo();
                $oDaoVinculoLicitacao->ac24_liclicitem = $this->iCodigoItemLicitacao;
                $oDaoVinculoLicitacao->ac24_quantidade = $this->getQuantidade();
                $oDaoVinculoLicitacao->ac24_valor      = $this->getValorTotal();

                $oDaoVinculoLicitacao->incluir(null);
                if ($oDaoVinculoLicitacao->erro_status == 0) {
                    throw new Exception("N�o foi possivel salvar item\n[ET]: {$oDaoVinculoLicitacao->erro_msg}");
                }
            }

            /**
             * Salva na PCPROCITEM
             */
            if ($this->iCodigoItemProcesso != "") {

                $oDaoVinculoProcesso = db_utils::getDao("acordopcprocitem");
                $oDaoVinculoProcesso->ac23_acordoitem = $this->getCodigo();
                $oDaoVinculoProcesso->ac23_pcprocitem = $this->iCodigoItemProcesso;
                $oDaoVinculoProcesso->ac23_quantidade = $this->getQuantidade();
                $oDaoVinculoProcesso->ac23_valor      = $this->getValorTotal();
                $oDaoVinculoProcesso->incluir(null);
                if ($oDaoVinculoProcesso->erro_status == 0) {
                    throw new Exception(pg_last_error() . " N�o foi possivel salvar item\n[ET]: {$oDaoVinculoProcesso->erro_msg}");
                }
            }
        }

        /**
         * Salva vinculo do acordoitem com empempitem, quando origem � empenho
         */
        if (!empty($this->iCodigoItemEmpenho)) {


            $oDaoVinculoProcesso = db_utils::getDao("acordoempempitem");
            $oDaoVinculoProcesso->ac44_acordoitem = $this->getCodigo();
            $oDaoVinculoProcesso->ac44_empempitem = $this->iCodigoItemEmpenho;
            $oDaoVinculoProcesso->incluir(null);

            if ($oDaoVinculoProcesso->erro_status == 0) {
                throw new Exception("Erro t�cnico: erro ao vincular item de empenho (acordoempempitem)");
            }
        }

        $oDaoAcordoItemVinculo = db_utils::getDao("acordoitemvinculo");

        $oDaoAcordoItemVinculo->excluir(null, "ac33_acordoitemfilho={$this->getCodigo()}");

        if ($oDaoAcordoItemVinculo->erro_status == "0") {
            throw new Exception("N�o foi possivel excluir item\n[ET]: {$oDaoAcordoItemVinculo->erro_msg}");
        }

        if (!empty($this->iItemVinculo)) {

            $oDaoAcordoItemVinculo->ac33_acordoitemfilho = $this->getCodigo();
            $oDaoAcordoItemVinculo->ac33_acordoitempai   = $this->iItemVinculo;
            $oDaoAcordoItemVinculo->ac33_tipo            = 1;
            $oDaoAcordoItemVinculo->ac33_valorunitario   = (float)$this->getValorUnitario();
            $oDaoAcordoItemVinculo->ac33_quantidade      = $this->getQuantidade();
            $oDaoAcordoItemVinculo->ac33_valortotal      = $this->getValorTotal();
            $oDaoAcordoItemVinculo->incluir(null);

            if ($oDaoAcordoItemVinculo->erro_status == "0") {
                throw new Exception("[3] - N�o foi possivel salvar item\n[ET]: {$oDaoAcordoItemVinculo->erro_msg}");
            }
        }

        if ($oDaoAcordoItem->erro_status == "0") {
            throw new Exception("[4] - N�o foi possivel salvar item\n[ET]: {$oDaoAcordoItem->erro_msg}");
        }

        $oDaoAcordoItemPeriodo = db_utils::getDao("acordoitemperiodo");
        $aPeriodoItens         = $this->getPeriodosItem();
        $oDaoAcordoItemPeriodo->excluir(null, "ac41_acordoitem = {$this->getCodigo()} and ac41_acordoposicao = {$this->getCodigoPosicao()}");

        if ($oDaoAcordoItemPeriodo->erro_status == 0) {

            throw  new Exception("[ 5 ] - ERRO - atualizando periodos - " . $oDaoAcordoItemPeriodo->erro_msg);
        }

        foreach ($aPeriodoItens as $iIndicePeriodo => $oPeriodo) {

            $oDaoAcordoItemPeriodo->ac41_acordoitem    = $this->getCodigo();
            $oDaoAcordoItemPeriodo->ac41_datainicial   = implode("-", array_reverse(explode("/", $oPeriodo->dtDataInicial)));
            $oDaoAcordoItemPeriodo->ac41_datafinal     = implode("-", array_reverse(explode("/", $oPeriodo->dtDataFinal)));
            $oDaoAcordoItemPeriodo->ac41_acordoposicao = $this->getCodigoPosicao();
            /**
             * Valida se deve ser feita a inclus�o ou altera��o do per�odo de um item.
             */

            $oDaoAcordoItemPeriodo->incluir(null);
            /*
       * Adiciono o sequencial na propriedade corrente
       */
            $aPeriodoItens[$iIndicePeriodo]->ac41_sequencial = $oDaoAcordoItemPeriodo->ac41_sequencial;

            if ($oDaoAcordoItemPeriodo->erro_status == 0) {
                // throw new Exception("N�o foi poss�vel incluir os per�odos cadastrados para o item.".pg_last_error());
            }
        }

        /**
         * Excluimos todas as dota�oes vinculadas com o item e incluimos novamente
         */
        $oDaoAcordoItemDotacaoReserva = db_utils::getDao("orcreservaacordoitemdotacao");
        $oDaoReserva                  = db_utils::getDao("orcreserva");
        $oDaoAcordoItemDotacao = db_utils::getDao("acordoitemdotacao");
        $sSqlDotacoes          = $oDaoAcordoItemDotacao->sql_query_file(
            null,
            "*",
            null,
            "ac22_acordoitem={$this->getCodigo()}"
        );

        $rsDotacoes           = $oDaoAcordoItemDotacao->sql_record($sSqlDotacoes);
        $iNumRowsDotacao      = $oDaoAcordoItemDotacao->numrows;
        $aDotacoesCadastradas = db_utils::getCollectionByRecord($rsDotacoes);
        foreach ($aDotacoesCadastradas as $oDotacaoCadastrada) {

            $sSqlReserva = $oDaoAcordoItemDotacaoReserva->sql_query_file(
                null,
                "*",
                null,
                "o84_acordoitemdotacao =
       {$oDotacaoCadastrada->ac22_sequencial}"
            );
            $rsReservaItem = $oDaoAcordoItemDotacaoReserva->sql_record($sSqlReserva);
            if ($oDaoAcordoItemDotacaoReserva->numrows > 0) {

                $oDadosReserva = db_utils::fieldsMemory($rsReservaItem, 0);
                $oDaoAcordoItemDotacaoReserva->excluir($oDadosReserva->o84_sequencial);
                $oDaoReserva->excluir($oDadosReserva->o84_orcreserva);
            }
            $oDaoAcordoItemDotacao->excluir($oDotacaoCadastrada->ac22_sequencial);
        }

        /**
         * incluimos as dotacoes
         */
        foreach ($this->getDotacoes() as $oDotacao) {

            $oDaoAcordoItemDotacao->ac22_acordoitem = $this->getCodigo();
            $oDaoAcordoItemDotacao->ac22_anousu     = $oDotacao->ano;
            $oDaoAcordoItemDotacao->ac22_valor      = (float)$oDotacao->valor;
            $oDaoAcordoItemDotacao->ac22_coddot     = $oDotacao->dotacao;
            $oDaoAcordoItemDotacao->ac22_quantidade = "" . $oDotacao->quantidade . "";
            $oDaoAcordoItemDotacao->incluir(null);

            if ($oDaoAcordoItemDotacao->erro_status == 0) {

                $sErroMsg  = "Erro ao salvar item ({$this->getMaterial()->getMaterial()}).\n";
                $sErroMsg .= "N�o foi possivel incluir dotacao ({$oDotacao->dotacao})\n.{$oDaoAcordoItemDotacao->erro_msg}";
                throw new Exception($sErroMsg);
            }

        }

        /**
         * incluindo acordoitemprevisao
         */
        $oDaoAcordoItemPrevisao = db_utils::getDao('acordoitemprevisao');
        $iCodigo                = $this->getCodigo();
        $sWhereItemPrevisao     = "ac37_acordoitem = {$iCodigo} ";
        $oDaoAcordoItemPrevisao->excluir(null, $sWhereItemPrevisao);
        if ($oDaoAcordoItemPrevisao->erro_status == 0) {

            throw new Exception("[ 1 ] - ERRO - atualizando per�odos - " . $oDaoAcordoItemPrevisao->erro_msg);
        }

        if ($this->getCodigo() != '') {

            $nQuantidadeTotal = 0;
            foreach ($this->getPeriodos() as $oPeriodoExecucao) {
                $nQuantidadeTotal += $oPeriodoExecucao->quantidade;
            }


            $nTotalComRound      = 0;
            $nTotalSemRound      = 0;
            $nDiferenca          = 0;
            $nTotalComDiferenca  = 0;
            $iContador           = 0;

            foreach ($this->getPeriodos() as $oPeriodoExecucao) {

                if (substr_count($oPeriodoExecucao->datainicial, "/")) {

                    $oPeriodoExecucao->datainicial = implode("-", array_reverse(explode("/", $oPeriodoExecucao->datainicial)));
                    $oPeriodoExecucao->datafinal   = implode("-", array_reverse(explode("/", $oPeriodoExecucao->datafinal)));
                }

                $iContador++;

                $oDaoAcordoItemPrevisao->ac37_acordoitem    = $this->getCodigo();
                $oDaoAcordoItemPrevisao->ac37_acordoperiodo = $oPeriodoExecucao->codigovigencia;
                $oDaoAcordoItemPrevisao->ac37_datainicial   = $oPeriodoExecucao->datainicial;
                $oDaoAcordoItemPrevisao->ac37_datafinal     = $oPeriodoExecucao->datafinal;
                $oDaoAcordoItemPrevisao->ac37_valorunitario = (float)$oPeriodoExecucao->valorunitario;

                $lAlteraPrevisaoExistente = false;

                /**
                 * Efetua o tipo de c�lculo de acordo com o tipo de controle do item
                 */
                switch ($this->getTipocontrole()) {

                    case 2:

                        /*
            * Caso o tipo de c�lculo seja 2. Buscamos se existe uma previs�o para o item. Caso exista,
            * somamos o per�odo existente com o per�odo fornecido pelo usu�rio
            */
                        $sWhereExistenciaItemPrevisao  = "     ac37_acordoperiodo = {$oPeriodoExecucao->codigovigencia}";
                        $sWhereExistenciaItemPrevisao .= " and ac37_acordoitem    = {$this->getCodigo()}";
                        $sSqlBuscaPrevisaoItem = $oDaoAcordoItemPrevisao->sql_query_file(null, "*", null, $sWhereExistenciaItemPrevisao);
                        $rsBuscaPrevisaoItem   = $oDaoAcordoItemPrevisao->sql_record($sSqlBuscaPrevisaoItem);
                        if ($oDaoAcordoItemPrevisao->numrows == 1) {

                            $oDadoItemPrevisao                    = db_utils::fieldsMemory($rsBuscaPrevisaoItem, 0);
                            $nSomaQuantidadeExistente             = $oPeriodoExecucao->quantidade + $oDadoItemPrevisao->ac37_quantidade;
                            $oPeriodoExecucao->quantidade         = $nSomaQuantidadeExistente;
                            $oPeriodoExecucao->quantidadeprevista = $nSomaQuantidadeExistente;
                            $lAlteraPrevisaoExistente             = true;
                        }

                        $oDaoAcordoItemPrevisao->ac37_valor = "" . (round($oPeriodoExecucao->quantidade * $this->getValorTotal(), 2)) / $nQuantidadeTotal . "";
                        break;

                    case 3:
                        $oDaoAcordoItemPrevisao->ac37_valor = "" . (round($oPeriodoExecucao->quantidade * $this->getValorTotal(), 2)) / $nQuantidadeTotal . "";
                        break;

                    default:
                        $oDaoAcordoItemPrevisao->ac37_valor = "" . round(($oPeriodoExecucao->quantidade * $this->getValorUnitario()), 2) . "";
                }

                $oDaoAcordoItemPrevisao->ac37_quantidade         = $oPeriodoExecucao->quantidade;
                $oDaoAcordoItemPrevisao->ac37_quantidadeprevista = "{$oPeriodoExecucao->quantidadeprevista}";

                if ($lAlteraPrevisaoExistente) {

                    $oDaoAcordoItemPrevisao->ac37_sequencial = $oDadoItemPrevisao->ac37_sequencial;
                    $oDaoAcordoItemPrevisao->alterar($oDadoItemPrevisao->ac37_sequencial);
                    if ($oDaoAcordoItemPrevisao->erro_status == 0) {

                        throw new Exception("Erro ao Salvar Previsao.\n{$oDaoAcordoItemPrevisao->erro_msg} >>> " . pg_last_error());
                    }
                } else {

                    $nTotalSemRound    += $oDaoAcordoItemPrevisao->ac37_valor;
                    $nTotalComRound    += round($oDaoAcordoItemPrevisao->ac37_valor, 2);
                    $nDiferenca         = round($nTotalSemRound - $nTotalComRound, 2);
                    $nTotalComDiferenca = $oDaoAcordoItemPrevisao->ac37_valor + $nDiferenca;

                    $oDaoAcordoItemPrevisao->incluir(null);
                    if ($oDaoAcordoItemPrevisao->erro_status == '0') {

                        throw new Exception("Erro ao Salvar Previsao.\n{$oDaoAcordoItemPrevisao->erro_msg} >>> " . pg_last_error());
                    }
                }

                $oPeriodoExecucao->codigo =  $oDaoAcordoItemPrevisao->ac37_sequencial;
            }

            if ($nTotalSemRound > $nTotalComRound) {

                $oAcordoItemPrevisao = db_utils::getDao("AcordoItemPrevisao");
                $oAcordoItemPrevisao->ac37_sequencial = $oDaoAcordoItemPrevisao->ac37_sequencial;
                $oAcordoItemPrevisao->ac37_valor = "$nTotalComDiferenca";
                $oAcordoItemPrevisao->alterar($oAcordoItemPrevisao->ac37_sequencial);
                if ($oDaoAcordoItemPrevisao->erro_status == 0) {

                    throw new Exception("Erro ao Atualizar valor com diferen�a da Previsao.\n{$oAcordoItemPrevisao->erro_msg} >>> " . pg_last_error());
                }
            }
        }
        $this->aPeriodosExecucao = array();
        return $this;
    }

    /**
     * define a origem do item
     *
     * @param integer $iCodigoItem c�digo do item de origem
     * @param integer $iOrigem tipo da origem 1 - processo de compras 2- licitacao
     * @param integer $iCodigoOrigem define o codigo do processo que originou o item (licitacaou ou processo de compras)
     * @return AcordoItem
     */
    public function setOrigem($iCodigoItem, $iOrigem, $iCodigoOrigem = '')
    {


        $this->iCodigoOrigem       = $iCodigoOrigem;

        switch ($iOrigem) {

            case 1:

                $this->iCodigoItemProcesso = $iCodigoItem;
                break;

            case 2:
                $this->iCodigoItemLicitacao = $iCodigoItem;
                break;

            case 6:
                $this->iCodigoItemEmpenho  = $iCodigoItem;
                break;
        }

        return $this;
    }

    /**
     * retorna a origem do item
     * @return Objeto com a origem do item ->tipo:tipo da origem ->codigo: codigo da origem
     */
    public function getOrigem()
    {

        $oOrigem               = new stdClass();
        $oOrigem->tipo         = 0;
        $oOrigem->codigo       = 0;
        $oOrigem->codigoorigem = 0;

        if (!empty($this->iCodigoItemEmpenho)) {

            $oEmpenhoFinanceiroItem      = new EmpenhoFinanceiroItem($this->iCodigoItemEmpenho);

            $oOrigem->oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenhoFinanceiroItem->getNumeroEmpenho());

            $oOrigem->tipo                            = 6;

            $oOrigem->codigo                          = $this->iCodigoItemEmpenho;
        }


        if ($this->iCodigoItemProcesso != "") {

            $oOrigem->tipo   = 1;
            $oOrigem->codigo = $this->iCodigoItemProcesso;
        } else if ($this->iCodigoItemLicitacao != "") {

            $oOrigem->tipo   = 2;
            $oOrigem->codigo = $this->iCodigoItemLicitacao;
        }
        $oOrigem->codigoorigem = $this->iCodigoOrigem;
        return $oOrigem;
    }

    /**
     * define o vinculo com outro item do contrato
     *
     * @param integer $iItem
     * @return AcordoItem
     */
    public function setItemVinculo($iItem)
    {

        $this->iItemVinculo = $iItem;
        return $this;
    }

    public function getItemVinculo()
    {
        return $this->iItemVinculo;
    }

    /**
     * Retorna do desdobramento do elemento usado no material.
     * @return string $sDesdobramento
     */
    public function getDesdobramento()
    {

        $sDesdobramento      = '';
        $oDaoOrcElemento     = db_utils::getDao("orcelemento");

        $sWhereOrcElemento   = "o56_codele = {$this->getElemento()}";
        $sWhereOrcElemento  .= " and o56_anousu = " . db_getsession("DB_anousu");
        $sCamposOrcElemento  = "o56_elemento, o56_descr";
        $sSqlOrcElemento     = $oDaoOrcElemento->sql_query_file(
            null,
            null,
            $sCamposOrcElemento,
            null,
            $sWhereOrcElemento
        );
        $rsSqlOrcElemento    = $oDaoOrcElemento->sql_record($sSqlOrcElemento);
        $iNumRowsOrcElemento = $oDaoOrcElemento->numrows;
        if ($iNumRowsOrcElemento > 0) {

            $sDesdobramento           = substr(db_utils::fieldsMemory($rsSqlOrcElemento, 0)->o56_elemento, 0, 7);
            $this->sDescricaoElemento = db_utils::fieldsMemory($rsSqlOrcElemento, 0)->o56_descr;
        }

        return $sDesdobramento;
    }

    /**
     * remove o item da posi��o
     *
     */
    public function remover()
    {

        /**
         * Verifica se o item possui Saldo executado/Autorizado.
         * Caso tenha algum desses, n�o poder� ser executado a exclus�o do mesmo
         */
        $oSaldos              = $this->getSaldos();
        if ($oSaldos->valorautorizado > 0) {

            $sMessagemInvalido = "Item com autoriza��es geradas.\n N�o ser� poss�vel a exclus�o desse item.";
            throw new Exception($sMessagemInvalido);
        }
        if ($oSaldos->valorexecutado > 0) {

            $sMessagemInvalido = "Item com ordem de compras geradas.\n N�o ser� poss�vel a exclus�o desse item.";
            throw new Exception($sMessagemInvalido);
        }
        $oDaoAcordoItem                  = db_utils::getDao("acordoitem");
        $oDaoAcordoItemExecutado         = db_utils::getDao("acordoitemexecutado");
        $oDaoAcordoItemExecutadoDotacao  = db_utils::getDao("acordoitemexecutadodotacao");
        $oDaoAcordoItemprevisao          = db_utils::getDao("acordoitemprevisao");
        $oDaoVinculoLicitacao            = db_utils::getDao("acordoliclicitem");
        $oDaoVinculoProcesso             = db_utils::getDao("acordopcprocitem");
        $oDaoItemPeriodo                 = db_utils::getDao("acordoitemperiodo");
        $oDaoItemEmpenho                 = db_utils::getDao("acordoempempitem");
        $oDaoAcordoItemExecutadoEmpautItem = db_utils::getDao("acordoitemexecutadoempautitem");
        $oDaocredenciamentosaldo            = db_utils::getDao("credenciamentosaldo");

        /**
         * Excluimos todas as dota�oes vinculadas com o item e incluimos novamente
         */
        if ($this->iCodigoItemLicitacao != "") {

            $oDaoVinculoLicitacao->excluir(null, "ac24_acordoitem = {$this->getCodigo()}");
            if ($oDaoVinculoLicitacao->erro_status == 0) {

                throw new Exception("Erro[1] ao remover item {$this->getMaterial()->getDescricao()}");
            }
        } else if ($this->iCodigoItemProcesso != "") {

            $oDaoVinculoProcesso->excluir(null, "ac23_acordoitem = {$this->getCodigo()}");
            if ($oDaoVinculoProcesso->erro_status == 0) {
                throw new Exception("Erro[2] ao remover item {$this->getMaterial()->getDescricao()}");
            }
        }


        if (!empty($this->iCodigoItemEmpenho)) {

            $oDaoItemEmpenho->excluir(null, "ac44_acordoitem = {$this->getCodigo()}");
            if ($oDaoItemEmpenho->erro_status == 0) {
                throw new Exception("Erro[3] ao remover item {$this->getMaterial()->getDescricao()}");
            }
        }

        $oDaoAcordoItemprevisao->excluir(null, "ac37_acordoitem={$this->getCodigo()}");
        if ($oDaoAcordoItemprevisao->erro_status == 0) {
            throw new Exception("Erro[4] ao remover item {$this->getMaterial()->getDescricao()}");
        }

        $oDaoAcordoItemDotacao = db_utils::getDao("acordoitemdotacao");

        $sSqlItemDotacao = $oDaoAcordoItemDotacao->sql_query_file(null, "ac22_sequencial", null, "ac22_acordoitem = {$this->getCodigo()}");
        $rsItemDotacao   = $oDaoAcordoItemDotacao->sql_record($sSqlItemDotacao);
        if ($oDaoAcordoItemDotacao->numrows > 0) {

            $oDaoOrcreservaAcordoItemDotacao = db_utils::getDao("orcreservaacordoitemdotacao");
            $oDaoOrcreservaAcordoItemDotacao->excluir(null, "o84_acordoitemdotacao in ({$sSqlItemDotacao})");
            if ($oDaoOrcreservaAcordoItemDotacao->erro_status == 0) {
                throw new Exception("Erro ao remover dota��es item {$this->getMaterial()->getDescricao()}\n{$oDaoOrcreservaAcordoItemDotacao->erro_msg}");
            }
        }

        $oDaoAcordoItemDotacao->excluir(null, "ac22_acordoitem={$this->getCodigo()}");
        if ($oDaoAcordoItemDotacao->erro_status == 0) {
            throw new Exception("Erro ao remover dota��es item {$this->getMaterial()->getDescricao()}\n{$oDaoAcordoItemDotacao->erro_msg}");
        }

        /**
         * Exclui os per�odos de um item (acordoitemperiodo)
         */
        $oDaoItemPeriodo->excluir(null, "ac41_acordoitem = {$this->getCodigo()}");
        if ($oDaoItemPeriodo->erro_status == 0) {
            throw new Exception("Erro ao excluir os per�odos do item.");
        }


        $oDaoAcordoItemVinculo = db_utils::getDao("acordoitemvinculo");
        $oDaoAcordoItemVinculo->excluir(null, "ac33_acordoitempai={$this->getCodigo()} ");

        if ($oDaoAcordoItemVinculo->erro_status == "0") {
            throw new Exception("N�o foi possivel excluir item\n[ET]: {$oDaoAcordoItemVinculo->erro_msg}");
        }


        $oDaoAcordoItemExecutadoEmpautItem->excluir(null, "ac19_acordoitemexecutado IN (select ac29_sequencial from acordoitemexecutado where ac29_acordoitem = {$this->getCodigo()})");
        if ($oDaoAcordoItemExecutadoEmpautItem->erro_status == 0) {
            $sErroMsg = "Erro[4] ao remover item {$this->getMaterial()->getDescricao()}\nErro:{$oDaoAcordoItemExecutadoEmpautItem->erro_msg}";
            throw new Exception($sErroMsg);
        }

        $oDaoAcordoItemExecutado->excluir(null, "ac29_acordoitem = {$this->getCodigo()}");
        if ($oDaoAcordoItemExecutado->erro_status == 0) {
            $sErroMsg = "Erro[5] ao remover item {$this->getMaterial()->getDescricao()}\nErro:{$oDaoAcordoItemExecutado->erro_msg}";
            throw new Exception($sErroMsg);
        }

        $oDaoAcordoItemExecutadoDotacao->excluir(null, "ac32_acordoitem = {$this->getCodigo()}");
        if ($oDaoAcordoItemExecutadoDotacao->erro_status == 0) {
            $sErroMsg = "Erro[6] ao remover item {$this->getMaterial()->getDescricao()}\nErro:{$oDaoAcordoItemExecutadoDotacao->erro_msg}";
            throw new Exception($sErroMsg);
        }

        $oDaoAcordoItem->excluir(null, "ac20_sequencial = {$this->getCodigo()}");
        if ($oDaoAcordoItem->erro_status == 0) {

            $sErroMsg = "Erro[7] ao remover item {$this->getMaterial()->getDescricao()}\nErro:{$oDaoAcordoItem->erro_msg}";
            throw new Exception($sErroMsg);
        }

        $oDaocredenciamentosaldo->excluir(null, "l213_item = {$this->getCodigo()}");
        if ($oDaocredenciamentosaldo->erro_status == 0) {

            $sErroMsg = "Erro[7] ao remover item {$this->getMaterial()->getDescricao()}\nErro:{$oDaocredenciamentosaldo->erro_msg}";
            throw new Exception($sErroMsg);
        }
    }

    public function removercredsaldo($icodItemLicitacao)
    {

        $oDaoCredenciamentoSaldo                 = db_utils::getDao("credenciamentosaldo");
        $oDaoCredenciamentoSaldo->excluir(null, "l213_item = {$icodItemLicitacao} ");

        if ($oDaoCredenciamentoSaldo->erro_status == 0) {

            $sErroMsg = "Erro[7] ao remover item {$this->getMaterial()->getDescricao()}\nErro:{$oDaoCredenciamentoSaldo->erro_msg}";
            throw new Exception($sErroMsg);
        }
    }

    /**
     * retorna os saldos do item
     *
     * @return object
     */
    public function getSaldos()
    {

        $oSaldo                             = new stdClass();
        $oSaldo->valorautorizado            = 0;
        $oSaldo->quantidadeautorizada       = 0;
        $oSaldo->valorexecutado             = 0;
        $oSaldo->quantidadeexecutada        = 0;
        $oSaldo->valorautorizar             = $this->getValorTotal();
        $oSaldo->quantidadeautorizar        = $this->getQuantidade();
        $oSaldo->valorexecutar              = $this->getValorTotal();
        $oSaldo->quantidadeexecutar         = $this->getQuantidade();
        $oSaldo->quantidadeautorizadamanual = 0;
        $oSaldo->valorautorizadomanual      = 0;
        if ($this->getCodigo() != null) {

            $sSqlSaldos  = "SELECT coalesce(sum(case when ac29_tipo = 1 then ac29_valor end), 0) as valorAutorizado,";
            $sSqlSaldos .= "       coalesce(sum(case when ac29_tipo = 1 then  ac29_quantidade end), 0) as quantidadeautorizada,";
            $sSqlSaldos .= "       coalesce(sum(case when ac29_tipo = 2 then ac29_valor end),0) as valorExecutado,";
            $sSqlSaldos .= "       coalesce(sum(case when ac29_tipo = 2 then  ac29_quantidade end),0) as quantidadeexecutada,";
            $sSqlSaldos .= "       coalesce(sum(case when ac29_tipo = 1 and ac29_automatico is false ";
            $sSqlSaldos .= "                         then ac29_valor end), 0) as valorAutorizadoManual,";
            $sSqlSaldos .= "       coalesce(sum(case when ac29_tipo = 1 and ac29_automatico is false ";
            $sSqlSaldos .= "                         then  ac29_quantidade end), 0) as quantidadeautorizadaManual";
            $sSqlSaldos .= "  from acordoitemexecutado ";
            $sSqlSaldos .= " where ac29_acordoitem = {$this->getCodigo()}";
            $rsSaldos    = db_query($sSqlSaldos);

            $oCalculoSaldo                       = db_utils::fieldsMemory($rsSaldos, 0);
            $oSaldo->valorautorizado             = $oCalculoSaldo->valorautorizado;
            $oSaldo->valorexecutado              = $oCalculoSaldo->valorexecutado;
            $oSaldo->quantidadeautorizada        = $oCalculoSaldo->quantidadeautorizada;
            $oSaldo->quantidadeexecutada         = $oCalculoSaldo->quantidadeexecutada;
            $oSaldo->valorautorizar             -= $oSaldo->valorautorizado;
            $oSaldo->quantidadeautorizar        -= $oSaldo->quantidadeautorizada;
            $oSaldo->valorexecutar              -= $oSaldo->valorexecutado;
            $oSaldo->quantidadeexecutar         -= $oSaldo->quantidadeexecutada;
            $oSaldo->quantidadeautorizadamanual  = $oCalculoSaldo->quantidadeautorizadamanual;
            $oSaldo->valorautorizadomanual       = $oCalculoSaldo->valorautorizado;
        }

        return $oSaldo;
    }

    /**
     * Remove as reservas de saldo das dotacoes do item.
     * @return AcordoItem
     */
    public function removerReservas()
    {

        $oDaoReserva          = db_utils::getDao("orcreserva");
        $oDaoReservaContrato  = db_utils::getDao("orcreservaacordoitemdotacao");
        foreach ($this->getDotacoes() as $oDotacao) {

            if ($oDotacao->reserva != "") {

                $oDaoReservaContrato->excluir(null, "o84_orcreserva = {$oDotacao->reserva}");
                if ($oDaoReservaContrato->erro_status == 0) {
                    $sMessage = "Erro ao cancelar Reserva do saldo!\n{$oDaoReservaContrato->erro_msg}";
                    throw new Exception($sMessage);
                }

                $oDaoReserva->excluir($oDotacao->reserva);

                if ($oDaoReserva->erro_status == 0) {

                    $sMessage = "Erro ao reservar saldo!\n{$oDaoReserva->erro_msg}";
                    throw new Exception($sMessage);
                }
            }
        }
        return $this;
    }

    /**
     * realiza as reservas de saldo das dotacoes do item;
     * @return AcordoItem
     */
    public function reservarSaldos()
    {

        $oDaoReserva          = db_utils::getDao("orcreserva");
        $oDaoReservaContrato  = db_utils::getDao("orcreservaacordoitemdotacao");
        foreach ($this->getDotacoes() as $oDotacao) {

            /**
             * verificamos se existe saldo na dotacao, e
             * incluimos a reserva
             */
            $oDotacaoSaldo  = new Dotacao($oDotacao->dotacao, $oDotacao->ano);
            $nSaldoReservar = $oDotacao->valor - $oDotacao->executado;
            if (round($oDotacaoSaldo->getSaldoFinal() <= $oDotacao->valor, 2)) {
                $nSaldoReservar = $oDotacaoSaldo->getSaldoFinal();
            }
            if ($nSaldoReservar > 0 && isset($oDotacao->codigodotacaoitem) && $oDotacao->codigodotacaoitem != "") {

                $oDaoReserva->o80_anousu = $oDotacao->ano;
                $oDaoReserva->o80_coddot = $oDotacao->dotacao;
                $oDaoReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoReserva->o80_dtfim  = "{$oDotacao->ano}-12-31";
                $oDaoReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoReserva->o80_valor  = $nSaldoReservar;
                $oDaoReserva->o80_descr  = "reserva item acordo {$this->getCodigo()}";
                $oDaoReserva->o80_justificativa  = "reserva item acordo {$this->getCodigo()}";
                $oDaoReserva->incluir(null);

                if ($oDaoReserva->erro_status == 0) {

                    $sMessage = "Erro ao reservar saldo!\n{$oDaoReserva->erro_msg}";
                    throw new Exception($sMessage);
                }

                $oDaoReservaContrato->o84_orcreserva        = $oDaoReserva->o80_codres;
                $oDaoReservaContrato->o84_acordoitemdotacao = $oDotacao->codigodotacaoitem;
                $oDaoReservaContrato->incluir(null);
                if ($oDaoReservaContrato->erro_status == 0) {

                    $sMessage = "Erro ao reservar saldo!\n{$oDaoReservaContrato->erro_msg}";
                    throw new Exception($sMessage);
                }
            }
            return $this;
        }
    }

    /**
     * Realiza a baixa manual do item
     *
     * @param integer $iPeriodo C�digo do Periodo
     * @param integer $iTipo tipo da baixa = 1 autorizacao, 2 execucao do servico
     * @param float   $nQuantidade quantidade a ser baixada
     * @param float   $nValor valor total da baixa
     * @param boolean $lRecalculaValor recalcula o valor pelo item.
     * @return AcordoItem
     */
    public function baixarMovimentacaoManual($oPeriodo, $iTipo, $nQuantidade, $nValor, $lRecalculaValor = true, $sNotaFiscal = null, $sNumeroProcesso = null, $sObservacao = null)
    {

        /**
         * verifica se o item ainda possui saldo para executar.
         */
        $oDadosPeriodo = $this->getPeriodoByCodigo($oPeriodo->iPeriodo);
        if ((round($nQuantidade, 2) > round($oDadosPeriodo->saldo, 2)) && $this->getTipocontrole() != self::CONTROLE_VALOR && $this->getTipocontrole() != self::CONTROLE_QUANTIDADE) {

            $sMsg = "Quantidades para o per�odo {$oDadosPeriodo->descricao} j� executadas! {$nQuantidade} round " . round($oDadosPeriodo->saldo, 2);
            throw new Exception($sMsg);
        }
        $oDaoAcordoItemExecutadoPeriodo = db_utils::getDao("acordoitemexecutadoperiodo");

        $nTotalPeriodos = 0;
        foreach ($this->getPeriodos() as $oPeriodoQuant) {
            $nTotalPeriodos += $oPeriodoQuant->quantidade;
        }
        $oDaoItemExecucao = db_utils::getDao("acordoitemexecutado");
        if ($lRecalculaValor) {
            if ($this->getTipoControle() == 2 || $this->getTipoControle() == self::CONTROLE_DIVISAO_VALOR) {
                $nValor = round(($nQuantidade * $this->getValorTotal()) / $nTotalPeriodos, 2);
            } else if ($this->getTipoControle() == self::CONTROLE_VALOR) {

                $nQuantidadeExecutada      = round(($nQuantidade / $this->getValorUnitario()), 4);
                $nValor = $nQuantidade;
            } else {
                $nValor =  $nQuantidade * $this->getValorUnitario();
            }
        }
        if ($nValor > 0) {

            $oDaoItemExecucao->ac29_acordoitem     = $this->getCodigo();
            $oDaoItemExecucao->ac29_automatico     = "false";
            $oDaoItemExecucao->ac29_quantidade     = $this->getTipocontrole() == self::CONTROLE_VALOR ? "{$nQuantidadeExecutada}" : "{$nQuantidade}";
            $oDaoItemExecucao->ac29_valor          = "{$nValor}";
            $oDaoItemExecucao->ac29_tipo           = $iTipo;
            $oDaoItemExecucao->ac29_numeroprocesso = $sNumeroProcesso;
            $oDaoItemExecucao->ac29_notafiscal     = $sNotaFiscal;
            $oDaoItemExecucao->ac29_observacao     = $sObservacao;
            $oDaoItemExecucao->incluir(null);
            if ($oDaoItemExecucao->erro_status == 0) {

                $sMessage = "Erro ao baixar a movimenta��o do item.\n{$oDaoItemExecucao->erro_msg}";
                throw new Exception($sMessage);
            }

            /**
             * Salvamos os dados dos periodos a baixa
             */
            $sDataInicial = implode("-", array_reverse(explode("/", $oPeriodo->datainicial)));
            $sDataFinal   = implode("-", array_reverse(explode("/", $oPeriodo->datafinal)));

            $oDaoAcordoItemExecutadoPeriodo->ac38_acordoitemexecutado = $oDaoItemExecucao->ac29_sequencial;
            $oDaoAcordoItemExecutadoPeriodo->ac38_acordoitemprevisao  = $oPeriodo->iPeriodo;
            $oDaoAcordoItemExecutadoPeriodo->ac38_datainicial         = $sDataInicial;
            $oDaoAcordoItemExecutadoPeriodo->ac38_datafinal           = $sDataFinal;
            $oDaoAcordoItemExecutadoPeriodo->incluir(null);
            if ($oDaoAcordoItemExecutadoPeriodo->erro_status == 0) {
                throw new Exception("Erro ao salvar baixa.\n{$oDaoAcordoItemExecutadoPeriodo->erro_msg}");
            }
            $oDaoAcordoItemExecutadoEmpenho = db_utils::getDao("acordoitemexecutadoempenho");
            foreach ($oPeriodo->aEmpenhos as $oEmpenho) {

                $oDaoAcordoItemExecutadoEmpenho->ac39_numemp                     = $oEmpenho->numemp;
                $oDaoAcordoItemExecutadoEmpenho->ac39_acordoitemexecutadoperiodo = $oDaoAcordoItemExecutadoPeriodo->ac38_sequencial;
                $oDaoAcordoItemExecutadoEmpenho->incluir(null);
                if ($oDaoAcordoItemExecutadoEmpenho->erro_status == 0) {
                    throw new Exception("Erro ao salvar baixa.\n{$oDaoAcordoItemExecutadoEmpenho->erro_msg}");
                }
            }
        }
        /**
         * Diminui os valores  de saldo;execucao do item
         */
        $oDadosPeriodo->saldo     -= $nQuantidade;
        $oDadosPeriodo->executado += $nQuantidade;
        $oDadosPeriodo->execucoes  = $this->getExecucoesPeriodo($oPeriodo->iPeriodo);
        return $this;
    }


    public function excluirMovimentacaoManual($iPeriodo, $iCodigoExecucao)
    {

        if (empty($iPeriodo)) {
            throw new Exception('Per�odo nao Informado.');
        }
        $oPeriodo = $this->getPeriodoByCodigo($iPeriodo);

        $oDaoAcordoItemExecutadoPeriodo = db_utils::getDao("acordoitemexecutadoperiodo");
        $sSqlDadosExecucao              = $oDaoAcordoItemExecutadoPeriodo->sql_query_executado($iCodigoExecucao);
        $rsDadosExecucao                = $oDaoAcordoItemExecutadoPeriodo->sql_record($sSqlDadosExecucao);
        if ($oDaoAcordoItemExecutadoPeriodo->numrows == 0) {
            throw new Exception('Execu��o n�o encontrada.');
        }
        $oDadosExecucao = db_utils::fieldsMemory($rsDadosExecucao, 0);
        /**
         * excluimos os empenhos vinculados a movimenta��o
         */
        $oDaoAcordoItemExecutadoEmpenho = db_utils::getDao("acordoitemexecutadoempenho");
        $oDaoAcordoItemExecutadoEmpenho->excluir(null, "ac39_acordoitemexecutadoperiodo = {$oDadosExecucao->ac38_sequencial}");
        if ($oDaoAcordoItemExecutadoEmpenho->erro_status == 0) {
            throw new Exception('Erro ao excluir execu��o. Empenho vinculado nao pode ser excluido.');
        }
        /**
         * excluimos a movimenta��o do periodo
         */
        $oDaoAcordoItemExecutadoPeriodo->excluir($oDadosExecucao->ac38_sequencial);
        if ($oDaoAcordoItemExecutadoPeriodo->erro_status == 0) {
            throw new Exception('Erro ao excluir execu��o do per�odo.');
        }

        /**
         * excluimos  a execu��o manual.
         */
        $oDaoAcordoItemExecutado = db_utils::getDao("acordoitemexecutado");
        $oDaoAcordoItemExecutado->excluir($oDadosExecucao->ac38_acordoitemexecutado);
        if ($oDaoAcordoItemExecutado->erro_status == 0) {
            throw new Exception('Erro ao excluir execu��o do per�odo.');
        }
        /**
         * Atualizamos as execu��es do periodo.
         */
        $oPeriodo->execucoes  = $this->getExecucoesPeriodo($oPeriodo->codigo);
        $oPeriodo->executado -= $oDadosExecucao->ac29_quantidade;
        $oPeriodo->saldo     += $oDadosExecucao->ac29_quantidade;
        if ($this->getTipocontrole() == 4) {
            $oPeriodo->executado = $oDadosExecucao->ac29_valor;
            $oPeriodo->saldo     = $oDadosExecucao->ac29_valor;
        }
        return $oPeriodo;
    }

    public function getPeriodos($lAtualizar = false)
    {

        if (count($this->aPeriodosExecucao) == 0 || $lAtualizar == true) {

            if ($lAtualizar) {
                $this->aPeriodosExecucao = array();
            }
            $sSqlPeriodos     = "select *, ";
            $sSqlPeriodos    .= "       (SELECT coalesce(sum(ac29_quantidade), 0) as execucao ";
            $sSqlPeriodos    .= "          from acordoitemexecutado ";
            $sSqlPeriodos    .= "               inner join acordoitemexecutadoperiodo on ac38_acordoitemexecutado = ac29_sequencial";
            $sSqlPeriodos    .= "          where ac38_acordoitemprevisao = ac37_sequencial) as quantidade_executada ";
            $sSqlPeriodos    .= "  from acordoitemprevisao ";
            $sSqlPeriodos    .= "       inner join acordoposicaoperiodo on ac36_sequencial = ac37_acordoperiodo";
            $sSqlPeriodos    .= " where ac37_acordoitem = {$this->iCodigo} ";
            $sSqlPeriodos    .= " order by ac36_numero";
            $rsPeriodos       = db_query($sSqlPeriodos);
            $iNumRows         = pg_num_rows($rsPeriodos);
            $aPeriodos        = db_utils::getCollectionByRecord($rsPeriodos);
            $nQuantidadeTotal = 0;
            foreach ($aPeriodos as $oPeriodo) {
                $nQuantidadeTotal += $oPeriodo->ac37_quantidade;
            }
            foreach ($aPeriodos as $oDados) {

                $oPeriodo = new stdClass();
                $oPeriodo->vigencia           = $oDados->ac36_numero;
                $oPeriodo->codigovigencia     = $oDados->ac36_sequencial;
                $oPeriodo->descricao          = $oDados->ac36_descricao;
                $oPeriodo->quantidade         = $oDados->ac37_quantidade;
                $oPeriodo->quantidadeprevista = $oDados->ac37_quantidadeprevista;
                $oPeriodo->executado          = $oDados->quantidade_executada;
                $oPeriodo->saldo              = 0;
                if ($this->getTipocontrole() != self::CONTROLE_QUANTIDADE && $this->getTipocontrole() != self::CONTROLE_VALOR) {
                    $oPeriodo->saldo            = $oDados->ac37_quantidade - $oDados->quantidade_executada;
                }
                $oPeriodo->valorunitario      = $oDados->ac37_valorunitario;
                $oPeriodo->valor              = $oDados->ac37_valor;
                $oPeriodo->datainicial        = $oDados->ac37_datainicial;
                $oPeriodo->datafinal          = $oDados->ac37_datafinal;
                $oPeriodo->codigo             = $oDados->ac37_sequencial;

                $aExecucoes = $this->getExecucoesPeriodo($oDados->ac37_sequencial);
                switch ($this->getTipocontrole()) {

                    case self::CONTROLE_DIVISAO_VALOR:
                        $nValor =  round(($oDados->quantidade_executada * $this->getValorTotal()) / $nQuantidadeTotal, 2);
                        break;

                    case self::CONTROLE_VALOR:
                        $nValor = 0;
                        foreach ($aExecucoes as $oExecucao) {
                            $nValor += $oExecucao->valor;
                        }
                        break;

                    default:
                        $nValor =  ($oDados->quantidade_executada * $oDados->ac37_valorunitario);
                        break;
                }
                $oPeriodo->valorexecutado     = $nValor;
                $oPeriodo->execucoes          = $aExecucoes;
                $this->aPeriodosExecucao[]    = $oPeriodo;
            }
        }
        return $this->aPeriodosExecucao;
    }

    /**
     * Retorna o periodo por Codigo
     *
     * @param unknown_type $iPeriodo
     * @return unknown
     */
    public function getPeriodoByCodigo($iPeriodo)
    {

        $aPeriodos = $this->getPeriodos();
        $oPeriodo  = null;
        foreach ($aPeriodos as $oPeriodo) {

            if ($oPeriodo->codigo == $iPeriodo) {
                break;
            }
        }
        return $oPeriodo;
    }


    /**
     * Persiste as informa��es dos periodos da previs�o de execu��o do item.
     *
     */
    public function salvarPeriodos()
    {

        if (!db_utils::inTransaction()) {
            throw new Exception("Nenhum transa��o com o banco de dados aberta.\n Procedimento cancelado");
        }
        $nTotalPeriodos = 0;
        foreach ($this->getPeriodos() as $oPeriodoQuant) {
            $nTotalPeriodos += $oPeriodoQuant->quantidade;
        }
        $oDaoAcordoitemPrevisao   = db_utils::getDao("acordoitemprevisao");
        foreach ($this->getPeriodos() as $oPeriodo) {

            $this->validarQuantidadePeriodo($oPeriodo->codigo, $oPeriodo->quantidadeprevista);
            if ($this->getTipoControle() == 2) {
                $nValor =  (round($oPeriodo->quantidadeprevista * $this->getValorTotal(), 2)) / $nTotalPeriodos;
            } else {

                $nValor =  $oPeriodo->quantidadeprevista * $this->getValorUnitario();
                $oDaoAcordoitemPrevisao->ac37_quantidade  = "{$oPeriodo->quantidadeprevista}";
            }
            $oDaoAcordoitemPrevisao->ac37_sequencial         = $oPeriodo->codigo;
            $oDaoAcordoitemPrevisao->ac37_quantidadeprevista = "{$oPeriodo->quantidadeprevista}";
            $oDaoAcordoitemPrevisao->ac37_valor              = "{$nValor}";
            $oDaoAcordoitemPrevisao->alterar($oPeriodo->codigo);
            if ($oDaoAcordoitemPrevisao->erro_status == 0) {
                throw new Exception("Erro ao Salvar dados do Periodo!\n{$oDaoAcordoitemPrevisao->erro_msg}");
            }
        }

        return $this;
    }

    /**
     * Define os peridos que o item possui, conforme a vigencia do contrato.
     * @return AcordoItem
     */
    public function setPeriodosExecucao($iAcordo, $lPeriodoComercial = false, $iTipoAditamento = null)
    {

        if ($lPeriodoComercial) {
            $this->setPeriodosExecucaoComercial($iAcordo, $iTipoAditamento);
            return true;
        }

        $aPeriodosItem   = $this->getPeriodosItem();

        foreach ($aPeriodosItem as $iIndicePeriodo => $oPeriodo) {

            /**
             * Configura as datas inicial e final para utiliz�-las durante as valida��es do
             * periodos de execu��o
             */
            list($iDiaInicial, $iMesInicial, $iAnoInicial) = explode("/", $oPeriodo->dtDataInicial);
            list($iDiaFinal, $iMesFinal, $iAnoFinal)       = explode("/", $oPeriodo->dtDataFinal);

            /**
             * Calcula as diferen�as de de meses entre a data inicial e data final
             */

            $iDiferencaPeriodo  = 1;
            $oDaoAcordoPosicao  = db_utils::getDao("acordoposicaoperiodo");

            /**
             * Percorremos a diferen�a entre os per�odos para buscar o sequencial cadastrado em acordoposicao.
             *
             * Ex: o contrato tem vig�ncia de 01/01/2012 � 31/10/2012 - isso resulta em 10 meses, ou seja, 10 registros em
             * "acordoposicao".
             *
             * Este FOR, percorre a diferen�a entre os per�odos informados e pega o sequencial da acordoposicao de acordo com
             * o MES e ANO
             */
            for ($iIndice = 1; $iIndice <= $iDiferencaPeriodo; $iIndice++) {

                /*
         * Configura a vari�vel do periodo inicial que ser� salvo n base de dados.
         */
                if ($iIndice == 1) {

                    $oParametros          = new stdClass();
                    $oParametros->nSaldo  = $this->getQuantidade();
                    $dtDataInicialPeriodo = $oPeriodo->dtDataInicial;
                } else {

                    list($dtDiaInicialPeriodo, $dtMesInicialPeriodo, $iAnoInicialPeriodo) = explode("/", $dtDataFinalPeriodo);
                    $dtDataInicialPeriodo =  date("d/m/Y", mktime(0, 0, 0, ($dtMesInicialPeriodo + 1), 1, $iAnoInicialPeriodo));
                }

                list($iDiaInicial, $iMesInicial, $iAnoInicial) = explode("/", $dtDataInicialPeriodo);

                /*
         * Busca o sequencial em acordoposicao de acordo com o mes/ano
         */
                $sSqlAcordoPosicao = $oDaoAcordoPosicao->sql_query_file(
                    null,
                    "ac36_sequencial",
                    null,
                    "ac36_acordoposicao={$this->iCodigoPosicao}
          and (extract(month from ac36_datainicial) =  {$iMesInicial}
          and  extract(year from ac36_datainicial) =  {$iAnoInicial})"
                );
                $rsAcordoPosicao    = $oDaoAcordoPosicao->sql_record($sSqlAcordoPosicao);
                throw new Exception($sSqlAcordoPosicao);



                if ($oDaoAcordoPosicao->numrows == 0 && $this->lParametroVigencia == false) {

                    throw new Exception("Data informada para previs�o de execu��o do item n�o confere com a data de vig�ncia do contrato.");
                }
                $iCodigoVigencia    = db_utils::fieldsMemory($rsAcordoPosicao, 0)->ac36_sequencial;

                /*
         * Configuramos a data final do per�odo para salvarmos na base de dados.
         */
                $iDiasMes           = cal_days_in_month(CAL_GREGORIAN, $iMesInicial, $iAnoInicial);
                $dtDataFinalPeriodo = date("d/m/Y", mktime(0, 0, 0, $iMesInicial, $iDiasMes, $iAnoInicial));

                if ($iMesInicial == $iMesFinal  && $iAnoFinal == $iAnoInicial) {

                    $dtDataFinalPeriodo = "{$iDiaFinal}/{$iMesInicial}/{$iAnoInicial}";
                    $iDiasMes           = $iDiaFinal;
                } else if ($iDiaInicial < $iDiasMes) {
                    $dtDataFinalPeriodo = "{$iDiasMes}/{$iMesInicial}/{$iAnoInicial}";
                }

                /**
                 * Efetua o c�lculo conforme o tipo de controle selecionado para o item
                 */
                $oTipoCalculo = AcordoItemTipoCalculoFactory::getInstance($this->getTipocontrole());
                $oTipoCalculo->setDataInicial($dtDataInicialPeriodo);
                $oTipoCalculo->setDataFinal($dtDataFinalPeriodo);
                $oTipoCalculo->setQuantidade($this->getQuantidade());
                $oTipoCalculo->setValorTotal($this->getValorTotal());
                $oTipoCalculo->setPeriodosItem($aPeriodosItem);
                $oCalculo     = $oTipoCalculo->calcular($iAcordo, $oParametros);
                $oParametros->nSaldo = $oCalculo->nSaldo;

                if (($this->getTipocontrole() == 1 || $this->getTipocontrole() == 2) && $oCalculo->quantidade == 0) {

                    $sMessageErro  = "Quantidade informada dividida pelo per�odo de execu��o do item, menor que zero.\n";
                    $sMessageErro .= "Quantidade inv�lida. ($oCalculo->quantidade)";
                    throw new Exception($sMessageErro);
                }

                /**
                 * Caso for aditamento de reequilibrio/valor, buscamos a quantidade original do item
                 * e dividimos novamente pelo total de periodos da previs�o de execu��o, desta forma o programa
                 * funcionar� como se fosse uma inclus�o
                 */
                if ($iTipoAditamento == Acordo::TIPO_ADITAMENTO_REEQUILIBRIO) {

                    $oAcordoItemOrigem      = self::getItemOrigem($this->getItemPai());
                    $iTotalPrevisaoExecucao = count($oAcordoItemOrigem->getPrevisaoExecucao());
                    $oCalculo->quantidade   = round(($oAcordoItemOrigem->getQuantidade() / $iTotalPrevisaoExecucao), 3);
                }

                /**
                 * Preenche um objeto com os dados fornecidos pelo usu�rio e com os resultados do c�lculo
                 * efetuado com o FACTORY acima.
                 */
                $oPeriodosExecucao                     = new stdClass();
                $oPeriodosExecucao->periodo            = $iIndice;
                $oPeriodosExecucao->datainicial        = $dtDataInicialPeriodo;
                $oPeriodosExecucao->datafinal          = $dtDataFinalPeriodo;
                $oPeriodosExecucao->quantidadeprevista = $oCalculo->quantidade;
                $oPeriodosExecucao->quantidade         = $oCalculo->quantidade;
                $oPeriodosExecucao->valorunitario      = $this->getValorUnitario();
                $oPeriodosExecucao->codigovigencia     = $iCodigoVigencia;
                $this->aPeriodosExecucao[]             = $oPeriodosExecucao;
            }
        }
        return $this;
    }


    /**
     * Define os peridos que o item possui, conforme a vigencia do contrato.
     * @param $iAcordo
     * @param null $iTipoAditamento
     * @throws Exception
     */
    public function setPeriodosExecucaoComercial($iAcordo, $iTipoAditamento = null)
    {

        $aPeriodosItem        = $this->getPeriodosItem();
        $iContadorItemPeriodo = 0;

        foreach ($aPeriodosItem as $iIndicePeriodo => $oPeriodo) {

            $aDataInicial  = explode("/", $oPeriodo->dtDataInicial);
            $dtDataInicial = date("Y-m-d", mktime(0, 0, 0, $aDataInicial[1], $aDataInicial[0], $aDataInicial[2]));

            $aDataFinal    = explode("/", $oPeriodo->dtDataFinal);
            $dtDataFinal   = date("Y-m-d", mktime(0, 0, 0, $aDataFinal[1], $aDataFinal[0], $aDataFinal[2]));

            $sWhere  = "     ac36_acordoposicao={$this->iCodigoPosicao}                                            ";
            $sWhere .= " and(                                                                                      ";
            $sWhere .= "        (ac36_datainicial <= '{$dtDataInicial}' and ac36_datafinal >= '{$dtDataInicial}')  ";
            $sWhere .= "     or (ac36_datainicial <= '{$dtDataFinal}'   and ac36_datafinal >= '{$dtDataFinal}')    ";
            $sWhere .= "     or (ac36_datainicial >= '{$dtDataInicial}' and ac36_datafinal <= '{$dtDataFinal}')    ";
            $sWhere .= "     )                                                                                     ";


            $oDaoAcordoPosicaoPeriodo = db_utils::getDao("acordoposicaoperiodo");
            $sSql  = $oDaoAcordoPosicaoPeriodo->sql_query_file(null, "*", "ac36_datainicial asc", $sWhere);
            $rs    = $oDaoAcordoPosicaoPeriodo->sql_record($sSql);

            if ($oDaoAcordoPosicaoPeriodo->numrows == 0 && $this->lParametroVigencia == false) {
                throw new Exception("Data informada para previs�o de execu��o do item n�o confere com a data de vig�ncia do contrato.");
            }

            for ($iRow = 0; $iRow < $oDaoAcordoPosicaoPeriodo->numrows; $iRow++) {

                if ($iRow == 0) {

                    $oParametros         = new stdClass();
                    $oParametros->nSaldo = $this->getQuantidade();
                }

                $oPeriodoBD       = db_utils::fieldsMemory($rs, $iRow);
                $dtInicialPeriodo = $oPeriodoBD->ac36_datainicial;
                $dtFinalPeriodo   = $oPeriodoBD->ac36_datafinal;

                if ($dtFinalPeriodo > $dtDataFinal) {
                    $dtFinalPeriodo = $dtDataFinal;
                }

                if ($dtInicialPeriodo < $dtDataInicial) {
                    $dtInicialPeriodo = $dtDataInicial;
                }

                if ($dtInicialPeriodo > $dtDataFinal) {
                    continue;
                }

                $aDataInicial     = explode("-", $dtInicialPeriodo);
                $aDataFinal       = explode("-", $dtFinalPeriodo);
                $dtFinalPeriodo   = date("d/m/Y", mktime(0, 0, 0, $aDataFinal[1],  $aDataFinal[2],  $aDataFinal[0]));
                $dtInicialPeriodo = date("d/m/Y", mktime(0, 0, 0, $aDataInicial[1], $aDataInicial[2], $aDataInicial[0]));

                $oTipoCalculo     = AcordoItemTipoCalculoFactory::getInstance($this->getTipocontrole());
                $oTipoCalculo->setDataInicial($dtInicialPeriodo);
                $oTipoCalculo->setDataFinal($dtFinalPeriodo);
                $oTipoCalculo->setQuantidade($this->getQuantidade());
                $oTipoCalculo->setValorTotal($this->getValorTotal());
                $oTipoCalculo->setPeriodosItem($aPeriodosItem);


                $oCalculo            = $oTipoCalculo->calcular($iAcordo, $oParametros);

                /**
                 * Caso for aditamento de reequilibrio/valor, buscamos a quantidade original do item
                 * e dividimos novamente pelo total de periodos da previs�o de execu��o, desta forma o programa
                 * funcionar� como se fosse uma inclus�o
                 */
                if ($iTipoAditamento == Acordo::TIPO_ADITAMENTO_REEQUILIBRIO) {

                    $oAcordoItemOrigem      = self::getItemOrigem($this->getItemPai());
                    $iTotalPrevisaoExecucao = count($oAcordoItemOrigem->getPrevisaoExecucao());
                    $oCalculo->quantidade   = round(($oAcordoItemOrigem->getQuantidade() / $iTotalPrevisaoExecucao), 3);
                }

                $oParametros->nSaldo = $oCalculo->nSaldo;
                $oPeriodosExecucao                     = new stdClass();
                $oPeriodosExecucao->periodo            = $iContadorItemPeriodo + 1;
                $oPeriodosExecucao->datainicial        = $dtInicialPeriodo;
                $oPeriodosExecucao->datafinal          = $dtFinalPeriodo;
                $oPeriodosExecucao->quantidadeprevista = $oCalculo->quantidade;
                $oPeriodosExecucao->quantidade         = $oCalculo->quantidade;
                $oPeriodosExecucao->valorunitario      = $this->getValorUnitario();
                $oPeriodosExecucao->codigovigencia     = $oPeriodoBD->ac36_sequencial;
                $this->aPeriodosExecucao[]             = $oPeriodosExecucao;
                $iContadorItemPeriodo++;
            }
        }
    }

    /**
     * M�todo recursivo para identificar o item original do contrato
     * @param AcordoItem $oAcordoItem
     * @return AcordoItem
     */
    public static function getItemOrigem(AcordoItem $oAcordoItem)
    {

        $oAcordoItemRetorno = $oAcordoItem;
        if ($oAcordoItem->getItemVinculo() != "") {
            $oAcordoItemRetorno = self::getItemOrigem($oAcordoItem->getItemPai());
        }
        return $oAcordoItemRetorno;
    }

    /**
     * Retorna uma cole��o de previs�es para o item
     * @return AcordoItemPrevisao[]
     */
    public function getPrevisaoExecucao()
    {

        $this->aPrevisaoExecucao = array();
        $oDaoItemPrevisao   = new cl_acordoitemprevisao();
        $sSqlBuscaPrevisoes = $oDaoItemPrevisao->sql_query_file(null, "ac37_sequencial", 1, "ac37_acordoitem = {$this->iCodigo}");
        $rsBuscaPrevisoes   = $oDaoItemPrevisao->sql_record($sSqlBuscaPrevisoes);
        if ($oDaoItemPrevisao->numrows > 0) {

            for ($iRowPrevisao = 0; $iRowPrevisao < $oDaoItemPrevisao->numrows; $iRowPrevisao++) {

                $iCodigoPrevisao = db_utils::fieldsMemory($rsBuscaPrevisoes, $iRowPrevisao)->ac37_sequencial;
                $this->aPrevisaoExecucao[] = new AcordoItemPrevisao($iCodigoPrevisao);
            }
        }
        return $this->aPrevisaoExecucao;
    }

    /**
     * Retorna o item que deu origem ao item atual.
     * @return AcordoItem
     */
    public function getItemPai()
    {

        if (!empty($this->iItemVinculo) && empty($this->oItemOrigem)) {
            $this->oItemOrigem = new AcordoItem($this->iItemVinculo);
        }
        return $this->oItemOrigem;
    }


    /**
     * Valida se n�o h� conflito entre as datas informadas e seta o array informado em $aPeriodosItem
     * @param  array $aPeriodosExecucao
     * @throws Exception
     */
    public function setPeriodos(array $aPeriodosExecucao)
    {

        $iTotalPeriodos = count($aPeriodosExecucao);
        if ($iTotalPeriodos == 0) {
            throw new Exception("N�o foram setados per�odos de execu��o para o item.");
        }

        /**
         * Tratamos os dados passado pela assinatura do m�todo para converter as datas
         * para o padr�o americano YYYY-MM-DD
         */
        $aPeriodosTimeStamp = array();
        foreach ($aPeriodosExecucao as $iIndicePeriodo => $oPeriodo) {

            $dtTimeStampInicial = implode("-", array_reverse(explode("/", $oPeriodo->dtDataInicial)));
            $dtTimeStampFinal   = implode("-", array_reverse(explode("/", $oPeriodo->dtDataFinal)));

            /*
       * Configuramos o objeto para compara��o
       */
            $oTimeStampPeriodo            = new stdClass();
            $oTimeStampPeriodo->dtInicial = $dtTimeStampInicial;
            $oTimeStampPeriodo->dtFinal   = $dtTimeStampFinal;
            $aPeriodosTimeStamp[]         = $oTimeStampPeriodo;
        }

        /**
         * Criamos um array para validar comparar as datas. A compara��o de data
         * ser� feita de 1 para 1. Sendo assim, todas ser�o validadas.
         */
        foreach ($aPeriodosTimeStamp as $iIndiceTimestamp => $oTimestamp) {

            $aPeriodosValidar = array();
            for ($iRow = 0; $iRow < $iTotalPeriodos; $iRow++) {

                if ($iRow == $iIndiceTimestamp) {
                    continue;
                }
                $aPeriodosValidar[] = $aPeriodosTimeStamp[$iRow];
            }

            /**
             * Percorremos o array criado comparando as datas entre si.
             */
            foreach ($aPeriodosValidar as $iPeriodo => $oPeriodo) {

                $lComparaDatas = DBTime::db_DataOverlaps(($aPeriodosTimeStamp[$iIndiceTimestamp]->dtInicial),
                    ($aPeriodosTimeStamp[$iIndiceTimestamp]->dtFinal),
                    ($oPeriodo->dtInicial),
                    ($oPeriodo->dtFinal)
                );
                if ($lComparaDatas) {

                    $sPeriodoInicial = implode("/", array_reverse(explode("-", $oPeriodo->dtInicial)));
                    $sPeriodoFinal   = implode("/", array_reverse(explode("-", $oPeriodo->dtFinal)));
                    $sMensagemErro   = "A data {$sPeriodoInicial} e {$sPeriodoFinal} ";
                    $sMensagemErro  .= "est�o conflitando com uma ou mais datas informadas.";
                    throw new Exception($sMensagemErro);
                }
            }
        }
        $this->aPeriodosItem = $aPeriodosExecucao;
        return $this;
    }

    /**
     * Retorna um array com os per�odos setados para o item
     * @return array
     */
    public function getPeriodosItem()
    {
        return $this->aPeriodosItem;
    }

    /**
     * Define a descricao da unidade
     *
     * @param string $sDescricao descri��o da unidade
     * @return AcordoItem
     */
    public function setDescricaoUnidade($sDescricao)
    {

        $this->sDescricaoUnidade = $sDescricao;
        return $this;
    }

    /**
     * retorna a descri��o da Unidade
     *
     * @return string
     */
    public function getDescricaoUnidade()
    {
        return $this->sDescricaoUnidade;
    }

    /**
     * valida se as quantidades informadas para o periodo s�o validas.
     *
     * @param integer $iPeriodo
     * @param float   $nQuantidade
     * @throws Exception
     */
    public function validarQuantidadePeriodo($iPeriodo, $nQuantidade)
    {

        $aPeriodos = $this->getPeriodos();

        /**
         * calcula o valor total do item , caso o controle seja mensal ou o total de quantidades ,
         * caso o o controle do item seja normal. n�o podemos deixar o usu�rio ter quantidade/valor maior que o item do
         * contrato.
         */
        $nQuantidadePeriodo   = 0;
        $nQuantidadePrevista  = 0;
        $nValor               = 0;
        $lErro                = false;
        foreach ($aPeriodos as &$oPeriodo) {

            if ($oPeriodo->codigo != $iPeriodo) {
                $nQuantidadePrevista += $oPeriodo->quantidadeprevista;
            }
            $nQuantidadePeriodo += $oPeriodo->quantidade;
        }
        /*
     * verifica se j� possui itens executados.
     */
        $oPeriodo = $this->getPeriodoByCodigo($iPeriodo);
        if ($nQuantidade < $oPeriodo->executado) {

            $sMsg   = "Quantidades do per�odo inv�lidas.\n";
            $sMsg  .= "Quantidade prevista({$nQuantidade}) � menor que a quantidade ({$oPeriodo->executado}) j� executada do per�odo.";
            throw new Exception($sMsg);
        }
        if ($this->getTipoControle() == 2) {

            if ($nQuantidade > $oPeriodo->quantidade) {

                $sMsg  = 'Quantidades do per�odo inv�lidas. Quantidade total do per�odo � maior que o dias no m�s.';
                throw new Exception($sMsg);
            }
        }

        if ($nQuantidadePrevista + $nQuantidade > $nQuantidadePeriodo) {

            $sMsg  = "Quantidades do per�odo inv�lidas. Quantidade total dos per�odos � maior que a do item.";
            throw new Exception($sMsg);
        }
    }

    /**
     * retorna as execu��es que foram realizadas para cada perido
     *
     * @param integer $iPeriodo codigo do periodo
     * @return array
     */
    public function getExecucoesPeriodo($iPeriodo)
    {

        $aExecucoes           = array();
        $oDaoPeriodoExecutado = db_utils::getDao("acordoitemprevisao");
        $sWhere               = "ac38_acordoitemprevisao = {$iPeriodo}";
        $sSqlExecucoes        = $oDaoPeriodoExecutado->sql_query_execucao(null, "*", 'ac38_sequencial', $sWhere);
        $rsExecucoes          = $oDaoPeriodoExecutado->sql_record($sSqlExecucoes);
        if ($oDaoPeriodoExecutado->numrows > 0) {

            for ($i = 0; $i < $oDaoPeriodoExecutado->numrows; $i++) {

                $oDadoExecucao = db_utils::fieldsMemory($rsExecucoes, $i);

                $oExecucao                      = new stdClass();
                $oExecucao->codigo              = $oDadoExecucao->ac38_sequencial;
                $oExecucao->datainicial         = db_formatar($oDadoExecucao->ac38_datainicial, "d");
                $oExecucao->datafinal           = db_formatar($oDadoExecucao->ac38_datafinal, "d");
                $oExecucao->quantidade          = $oDadoExecucao->ac29_quantidade;
                $oExecucao->valor               = $oDadoExecucao->ac29_valor;
                $oExecucao->notafiscal          = $oDadoExecucao->ac29_notafiscal;
                $oExecucao->processo            = $oDadoExecucao->ac29_numeroprocesso;
                $oExecucao->observacao          = $oDadoExecucao->ac29_observacao;
                $aExecucoes[]                   = $oExecucao;
                unset($oDadoExecucao);
            }
        }
        return $aExecucoes;
    }

    /**
     * Retorna a �ltima posi��o do campo ordem dispon�vel
     *
     * @return integer
     */
    public function getProximaPosicao()
    {

        $oDaoAcordoItem           = db_utils::getDao('acordoitem');
        $sCamposBuscaPosicaoOrdem = " coalesce(max(ac20_ordem), 0)+1 as ultima_posicao ";
        $sWhereBuscaPosicaoOrdem  = " ac20_acordoposicao = {$this->getCodigoPosicao()} ";

        $sSqlBuscaPosicaoOrdem    = $oDaoAcordoItem->sql_query_file(
            null,
            $sCamposBuscaPosicaoOrdem,
            null,
            $sWhereBuscaPosicaoOrdem
        );
        $rsBuscaPosicaoOrdem      = $oDaoAcordoItem->sql_record($sSqlBuscaPosicaoOrdem);
        $iUltimaSequencia         = (int) db_utils::fieldsMemory($rsBuscaPosicaoOrdem, 0)->ultima_posicao;

        return $iUltimaSequencia;
    }

    /**
     * Seta valor na propriedade $iOrdem
     * @param integer $iOrdem
     */
    public function setOrdem($iOrdem)
    {
        $this->iOrdem = $iOrdem;
        return $this;
    }

    /**
     * Retorna o valor da propriedade $iOrdem
     * @return integer $iOrdem
     */
    public function getOrdem()
    {
        return $this->iOrdem;
    }

    /**
     * Seta valor na propriedade sEstruturalElemento
     * @param string $sEstrutural
     */
    public function setEstruturalElemento($sEstrutural)
    {
        $this->sEstruturalElemento = $sEstrutural;
        return $this;
    }

    /**
     * Retorna o valor da propriedade sEstruturalElemento
     * @return string
     */
    public function getEstruturalElemento()
    {
        return $this->sEstruturalElemento;
    }

    public function setDescEstruturalElemento($sDescricaoEstruturalElemento)
    {
        $this->sDescricaoEstruturalElemento = $sDescricaoEstruturalElemento;
        return $this;
    }

    public function getDescEstruturalElemento()
    {
        return $this->sDescricaoEstruturalElemento;
    }

    /**
     * M�todo para mover a previs�o de execu��o de um item
     * @param integer $iPeriodoOrigem
     * @param integer $iPeriodoDestino
     * @throws Exception
     */
    public function moverPrevisaoPeriodo($iPeriodoOrigem, $iPeriodoDestino)
    {

        /**
         * Validamos o conflito de datas para mover o per�odo.
         * Ao M�s inicial deve ser igual ao m�s final. A mesma regra se aplica ao ANO
         */
        list($iDiaInicialDestino, $iMesInicialDestino, $iAnoInicialDestino) = explode("/", $this->getDataInicial());
        list($iDiaFinalDestino, $iMesFinalDestino, $iAnoFinalDestino)       = explode("/", $this->getDataFinal());
        if ($iMesInicialDestino != $iMesFinalDestino || $iAnoInicialDestino != $iAnoFinalDestino) {
            throw new Exception("As datas informadas devem iniciar e terminar no mesmo m�s e ano.");
        }
        if ($iDiaInicialDestino >= $iDiaFinalDestino) {
            throw new Exception("A data inicial est� maior que a data final. Verifique.");
        }

        $oDaoAcordoItemPrevisaoOrigem  = db_utils::getDao("acordoitemprevisao");
        $oDaoAcordoItemPrevisaoDestino = db_utils::getDao("acordoitemprevisao");
        $oDaoItemExecutadoPeriodo      = db_utils::getDao("acordoitemexecutadoperiodo");


        /**
         * Busco os dados da previs�o do per�odo de ORIGEM
         */
        $sWhereAcordoPrevisaoOrigem  = " ac37_acordoitem = {$this->getCodigo()} and ac37_acordoperiodo = {$iPeriodoOrigem}";
        $sSqlAcordoPrevisaoOrigem    = $oDaoAcordoItemPrevisaoOrigem->sql_query(null, "*", null, $sWhereAcordoPrevisaoOrigem);
        $rsDadosAcordoPrevisaoOrigem = $oDaoAcordoItemPrevisaoOrigem->sql_record($sSqlAcordoPrevisaoOrigem);
        $oAcordoPrevisaoOrigem       = db_utils::fieldsMemory($rsDadosAcordoPrevisaoOrigem, 0);

        /**
         * Valido se a competencia (ano/mes) informado pelo cliente s�o as mesmas j� cadastradas para o per�odo
         * Caso seja, lan�amos uma excess�o.
         */
        list($iAnoPrevisaoOrigemInicio, $iMesPrevisaoOrigemInicio, $iDiaPrevisaoOrigemInicio) = explode("-", $oAcordoPrevisaoOrigem->ac37_datainicial);
        list($iAnoPrevisaoOrigemFinal, $iMesPrevisaoOrigemFinal, $iDiaPrevisaoOrigemFinal)    = explode("-", $oAcordoPrevisaoOrigem->ac37_datafinal);
        if (($iMesPrevisaoOrigemInicio == $iMesInicialDestino && $iAnoPrevisaoOrigemInicio == $iAnoInicialDestino) ||
            ($iMesPrevisaoOrigemFinal  == $iMesFinalDestino   && $iAnoPrevisaoOrigemFinal  == $iAnoFinalDestino)
        ) {
            throw new Exception("As datas fornecidas possuem o mesmo m�s e ano da previs�o original.");
        }

        /**
         * Buscamos as quantidades e valores executados no per�odos de ORIGEM
         */
        $sCamposItemExecutadoPeriodo  = " coalesce(sum(coalesce(ac37_quantidadeprevista, 0)), 0) as ac37_quantidadeprevista, ";
        $sCamposItemExecutadoPeriodo .= " coalesce(sum(coalesce(ac29_quantidade, 0)), 0) as quantidade_executada";
        $sWhereItemExecutadoPeriodo   = " ac38_acordoitemprevisao = {$oAcordoPrevisaoOrigem->ac37_sequencial} ";
        $sSqlItemExecutadoPeriodo     = $oDaoItemExecutadoPeriodo->sql_query_executado(
            null,
            $sCamposItemExecutadoPeriodo,
            null,
            $sWhereItemExecutadoPeriodo
        );
        $rsItemExecutadoPeriodo       = $oDaoItemExecutadoPeriodo->sql_record($sSqlItemExecutadoPeriodo);
        $oItemExecutadoOrigem         = db_utils::fieldsMemory($rsItemExecutadoPeriodo, 0);
        /*
     * Validamos se a quantidade executada � igual a quantidade prevista. Caso seja � lan�ado uma exce��o abortando o processo.
     */
        if ($oItemExecutadoOrigem->quantidade_executada != 0) {

            if (
                $oItemExecutadoOrigem->ac37_quantidadeprevista == $oItemExecutadoOrigem->quantidade_executada &&
                ($this->getTipoControle() != 4 && $this->getTipocontrole() != 5)
            ) {
                throw new Exception("A quantidade prevista para o per�odo j� foi executada. Opera��o n�o realizada.");
            }
        }

        /**
         * Busco os dados da previs�o do per�odo de DESTINO
         */
        $sWhereAcordoPrevisaoDestino  = " ac37_acordoitem = {$this->getCodigo()} and ac37_acordoperiodo = {$iPeriodoDestino}";
        $sSqlAcordoPrevisaoDestino    = $oDaoAcordoItemPrevisaoDestino->sql_query_file(null, "*", null, $sWhereAcordoPrevisaoDestino);
        $rsDadosAcordoPrevisaoDestino = $oDaoAcordoItemPrevisaoDestino->sql_record($sSqlAcordoPrevisaoDestino);

        $nQuantidadeExistenteDestino = 0;
        $lHasDadosDestinoPrevisto    = false;
        if ($oDaoAcordoItemPrevisaoDestino->numrows == 1) {

            $oAcordoPrevisaoDestino      = db_utils::fieldsMemory($rsDadosAcordoPrevisaoDestino, 0);
            $nQuantidadeExistenteDestino = $oAcordoPrevisaoDestino->ac37_quantidadeprevista;

            /**
             * Verificamos se o tipo de controle � DOIS (dias). Caso seja, verificamos se o per�odo que ser� movido possui
             * dias suficientes para receber mais dias para executar
             */
            if ($this->getTipocontrole() == 2 || $this->getTipocontrole() == 3) {

                list($iAnoDestino, $iMesDestino, $iDiaDestino) = explode("-", $oAcordoPrevisaoDestino->ac37_datainicial);
                /**
                 * Total de dias no m�s
                 */
                $iDiasExistentesMes      = cal_days_in_month(CAL_GREGORIAN, $iMesDestino, $iAnoDestino);
                if ($this->getTipocontrole() == 3) {
                    $iDiasExistentesMes = 30;
                }
                /**
                 * Quantidade que ser� movida (quantidade - quantidade executada)
                 */
                $nSaldoRestanteParaMover = ($oAcordoPrevisaoOrigem->ac37_quantidade - $oItemExecutadoOrigem->quantidade_executada);
                $nCalculaDiasMover       = ($oAcordoPrevisaoDestino->ac37_quantidade + $nSaldoRestanteParaMover);
                if ($nCalculaDiasMover > $iDiasExistentesMes) {

                    $sMensagemErroDias  = "H� mais dias para mover do que o m�s {$iMesDestino} possui.\n\nO total de dias ";
                    $sMensagemErroDias .= "que podem ser movidos n�o pode ultrapassar {$iDiasExistentesMes} dias.";
                    throw new Exception($sMensagemErroDias);
                }
            }
            $lHasDadosDestinoPrevisto    = true;
        }

        /**
         * Incluimos a previsao com o saldo que � a (quantidade_prevista - quantidade_executada_periodo)
         */
        $nQuantidadePrevistaDestino = 0;
        $nValorTotalPrevisto        = $oAcordoPrevisaoOrigem->ac37_valor;
        if ($this->getTipocontrole() != self::CONTROLE_VALOR && $this->getTipocontrole() != self::CONTROLE_QUANTIDADE) {
            $nQuantidadePrevistaDestino = ($oAcordoPrevisaoOrigem->ac37_quantidade - $oItemExecutadoOrigem->quantidade_executada);
            $nValorTotalPrevisto        = ($nQuantidadePrevistaDestino * $this->getValorUnitario());
        }
        $nTotalQuantidadePrevista   = ($nQuantidadePrevistaDestino + $nQuantidadeExistenteDestino);
        if ($nQuantidadeExistenteDestino != 0) {
            $nValorTotalPrevisto = ($oAcordoPrevisaoOrigem->ac37_valorunitario * $nTotalQuantidadePrevista);
        }
        $oDaoAcordoItemPrevisaoDestino->ac37_acordoitem         = $this->getCodigo();
        $oDaoAcordoItemPrevisaoDestino->ac37_valor              = $nValorTotalPrevisto;
        $oDaoAcordoItemPrevisaoDestino->ac37_valorunitario      = $oAcordoPrevisaoOrigem->ac37_valorunitario;
        $oDaoAcordoItemPrevisaoDestino->ac37_quantidade         = $nTotalQuantidadePrevista;
        $oDaoAcordoItemPrevisaoDestino->ac37_quantidadeprevista = $nTotalQuantidadePrevista;
        $oDaoAcordoItemPrevisaoDestino->ac37_acordoperiodo      = $iPeriodoDestino;
        $oDaoAcordoItemPrevisaoDestino->ac37_datainicial        = "{$iAnoInicialDestino}-{$iMesInicialDestino}-{$iDiaInicialDestino}";
        $oDaoAcordoItemPrevisaoDestino->ac37_datafinal          = "{$iAnoFinalDestino}-{$iMesFinalDestino}-{$iDiaFinalDestino}";

        if ($lHasDadosDestinoPrevisto) {

            $oDaoAcordoItemPrevisaoDestino->ac37_sequencial = $oAcordoPrevisaoDestino->ac37_sequencial;
            $oDaoAcordoItemPrevisaoDestino->alterar($oAcordoPrevisaoDestino->ac37_sequencial);
        } else {
            $oDaoAcordoItemPrevisaoDestino->incluir(null);
        }

        if ($oDaoAcordoItemPrevisaoDestino->erro_status == 0) {

            $sMsgErroPrevisao  = "N�o foi poss�vel salvar a previs�o fornecida.\n";
            $sMsgErroPrevisao .= "Erro: {$oDaoAcordoItemPrevisaoDestino->erro_msg}";
            throw new Exception($sMsgErroPrevisao);
        }

        if ($oDaoAcordoItemPrevisaoDestino->erro_status == 0) {
            throw new Exception("N�o foi poss�vel salvar a nova previs�o destino. \n {$oDaoAcordoItemPrevisaoDestino->erro_msg}");
        }

        /**
         * S� dever� alterar o valor e quantidade previsto caso o tipo de controle do item n�o
         * seja do tipo 4 - Valor, 5 - Quantidade
         */
        if ($this->getTipoControle() != 4 && $this->getTipoControle() != 5) {

            $nQuantidadePrevistaOrigem                             = $oItemExecutadoOrigem->quantidade_executada;
            $nValorOrigemExecutado                                 = ($nQuantidadePrevistaOrigem * $this->getValorUnitario());
            $oDaoAcordoItemPrevisaoOrigem->ac37_valor              = $nValorOrigemExecutado;
            $oDaoAcordoItemPrevisaoOrigem->ac37_quantidade         = $nQuantidadePrevistaOrigem;
            $oDaoAcordoItemPrevisaoOrigem->ac37_quantidadeprevista = $nQuantidadePrevistaOrigem;
            $oDaoAcordoItemPrevisaoOrigem->alterar_where($sWhereAcordoPrevisaoOrigem);
            if ($oDaoAcordoItemPrevisaoOrigem->erro_status == 0) {
                throw new Exception("N�o foi poss�vel salvar a previs�o origem. \n {$oDaoAcordoItemPrevisaoOrigem->erro_msg}");
            }
        }

        /**
         *  Exclui a previs�o de um item caso n�o seja encontrado nada executado no per�odo de origem
         */
        $sSqlBuscaExecutadoDestino = $oDaoItemExecutadoPeriodo->sql_query_file(null, "*", null, "ac38_acordoitemprevisao = {$oAcordoPrevisaoOrigem->ac37_sequencial}");
        $rsBuscaExecutadoDestino   = $oDaoItemExecutadoPeriodo->sql_record($sSqlBuscaExecutadoDestino);
        if ($oDaoItemExecutadoPeriodo->numrows == 0) {

            $sWhereExcluirAcordoItemPrevisao = " ac37_acordoitem = {$this->getCodigo()} and ac37_acordoperiodo = {$iPeriodoOrigem}";
            $oDaoAcordoItemPrevisaoOrigem->excluir(null, $sWhereExcluirAcordoItemPrevisao);
        }
    }

    /**
     * @return int
     */
    public function getCodigoItemLicitacao()
    {
        return $this->iCodigoItemLicitacao;
    }

    /**
     * @return int
     */
    public function getCodigoItemProcessoCompras()
    {
        return $this->iCodigoItemProcesso;
    }

    /**
     * @return int
     */
    public function getCodigoItemEmpenho()
    {
        return $this->iCodigoItemEmpenho;
    }

    /**
     * Retorna a Posicao que o item est� vinculado
     * @return AcordoPosicao
     */
    public function getPosicao()
    {

        if (empty($this->oPosicao)) {
            $this->oPosicao = new AcordoPosicao($this->getCodigoPosicao());
        }
        return $this->oPosicao;
    }

    /**
     * Retorna as execu��es dos itens
     * @return AcordoItemExecucao[]
     */
    public function getExecucoes()
    {

        if (count($this->aExecucoes) > 0) {
            return $this->aExecucoes;
        }
        $oDaoExecucoes  = new cl_acordoitemexecutado();
        $sWhere         = "ac20_ordem = {$this->getOrdem()} and ac26_acordo = {$this->getPosicao()->getAcordo()} and ac29_tipo = 2";
        $sSqlExecucoes  = $oDaoExecucoes->sql_query(null, "acordoitemexecutado.*", "ac29_datainicial, ac29_datafinal", $sWhere);

        $rsExecucoes     = db_query($sSqlExecucoes);
        $iTotalExecucoes = pg_num_rows($rsExecucoes);
        for ($iExecucao = 0; $iExecucao < $iTotalExecucoes; $iExecucao++) {

            $oDadosExecucao = db_utils::fieldsMemory($rsExecucoes, $iExecucao);
            $oExecucao      = new AcordoItemExecucao();
            $oExecucao->setProcesso($oDadosExecucao->ac29_numeroprocesso);
            $oExecucao->setCodigo($oDadosExecucao->ac29_sequencial);
            $oExecucao->setQuantidade($oDadosExecucao->ac29_quantidade);
            $oExecucao->setValor($oDadosExecucao->ac29_valor);
            $oExecucao->setItem($this);
            $oExecucao->setDataInicial(new DBDate($oDadosExecucao->ac29_datainicial));
            $oExecucao->setDataFinal(new DBDate($oDadosExecucao->ac29_datafinal));
            $oExecucao->setNotaFiscal($oDadosExecucao->ac29_notafiscal);
            $this->aExecucoes[] = $oExecucao;
        }
        return $this->aExecucoes;
    }

    /**
     * Valida se o item ainda possui saldo a ser executado
     * @param AcordoItemExecucao $oExecucao
     * @return bool
     */
    public function temSaldoParaExecucaoDosValores(AcordoItemExecucao $oExecucao)
    {

        $aExecucoes            = $this->getExecucoes();
        $nQuantidadeExecutada  = 0;
        $nValorExecutado       = 0;
        foreach ($aExecucoes as $oExecucaoRealizada) {

            if ($oExecucaoRealizada->getCodigo() === $oExecucao->getCodigo()) {
                continue;
            }

            $nValorExecutado      += $oExecucaoRealizada->getValor();
            $nQuantidadeExecutada += $oExecucaoRealizada->getQuantidade();
        }

        if (bccomp($nQuantidadeExecutada + $oExecucao->getQuantidade(), $this->getQuantidadeAtualizada(), 4) === 1) {
            return false;
        }

        if (bccomp(($nValorExecutado + $oExecucao->getValor()), $this->getValorAtualizado(), 2) === 1) {
            return false;
        }
        return true;
    }

    /**
     * Calcula os valores/Quantidades Atualizadas do item do contrato, somando todos as posi��es do contrato
     */
    private function getValoresAtualizados()
    {

        if ($this->lValoresJaAtualizados) {
            return;
        }

        $oDaoAcordoitem  = new cl_acordoitem;

        $iNumeroAditamento = 1;

        $sCampos    = "max(ac26_numero) as numero_aditamento";
        $sWhere     = "ac16_sequencial = {$this->getPosicao()->getAcordo()}";
        $sWhere    .= " and ac26_acordoposicaotipo in (" . AcordoPosicao::TIPO_INCLUSAO . ", " . AcordoPosicao::TIPO_RENOVACAO . ", " . AcordoPosicao::TIPO_ACRESCIMOITEM . "," . AcordoPosicao::TIPO_DECRESCIMOITEM . "," . AcordoPosicao::TIPO_ACRESCIMODECRESCIMOITEM . "," . AcordoPosicao::TIPO_ACRESCIMODECRESCIMOITEMCONJUGADO . ")";
        $sSqlItens  = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem     = $oDaoAcordoitem->sql_record($sSqlItens);

        if ($oDaoAcordoitem->numrows > 0) {
            $iNumeroAditamento = db_utils::fieldsMemory($rsItem, 0)->numero_aditamento;
        }

        $sCampos    = "coalesce(sum(case when ac26_acordoposicaotipo <> " . AcordoPosicao::TIPO_REEQUILIBRIO . " then ac20_quantidade else 0 end ), 0) as quantidade,";
        $sCampos   .= "coalesce(sum(ac20_valortotal),0) as valortotal,";
        $sCampos   .= "coalesce(sum(case when ac26_acordoposicaotipo <> " . AcordoPosicao::TIPO_REEQUILIBRIO . " and ac26_numero >= {$iNumeroAditamento} then ac20_quantidade else 0 end),0) as quantidadetotalrenovacao,";
        $sCampos   .= "coalesce(sum(case when ac26_numero >= {$iNumeroAditamento} then ac20_valortotal else 0 end),0) as valortotalrenovacao";
        $sWhere     = "ac16_sequencial = {$this->getPosicao()->getAcordo()} and ac20_ordem = {$this->getOrdem()}";
        $sSqlItens  = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem     = $oDaoAcordoitem->sql_record($sSqlItens);

        if ($oDaoAcordoitem->numrows > 0) {

            $oValoresItens = db_utils::fieldsMemory($rsItem, 0);
            $this->nQuantidadeAtualizada = $oValoresItens->quantidade;
            $this->nValorAtualizado      = $oValoresItens->valortotal;

            $this->nQuantidadeAtualizadaRenovacao = $oValoresItens->quantidadetotalrenovacao;
            $this->nValorAtualizadoRenovacao      = $oValoresItens->valortotalrenovacao;

            $this->lValoresJaAtualizados = true;
        }
    }

    /**
     * Retorna a quantidade atualizada do item a partir da posi��o de inclus�o ou da ultima renova��o
     * @return float
     */
    public function getQuantidadeAtualizadaRenovacao()
    {
        $this->getValoresAtualizados();
        return $this->nQuantidadeAtualizadaRenovacao;
    }

    /**
     * Retorna o valor atualizado do item a partir da posi��o de inclus�o ou da ultima renova��o
     * @return float
     */
    public function getValorAtualizadoRenovacao()
    {
        $this->getValoresAtualizados();
        return $this->nValorAtualizadoRenovacao;
    }

    /**
     * Retorna a quantidade atualizada do item
     * @return float
     */
    public function getQuantidadeAtualizada()
    {
        $this->getValoresAtualizados();
        return $this->nQuantidadeAtualizada;
    }

    /**
     * Retorna o valor Atualizado do item
     * @return float
     */
    public function getValorAtualizado()
    {

        $this->getValoresAtualizados();
        return $this->nValorAtualizado;
    }

    /**
     * Retorna o valor executado do item
     * @return float
     */
    public function getValorExecutado()
    {

        $nValorExecutado = 0;
        foreach ($this->getExecucoes() as $oExecucao) {
            $nValorExecutado += $oExecucao->getValor();
        }
        return $nValorExecutado;
    }

    /**
     * Percentual executado do item
     * @return float
     */
    public function getPercentualExecutado()
    {

        $nPercentualExecutado = 0;
        if ($this->getValorAtualizado() > 0) {
            $nPercentualExecutado = round(($this->getValorExecutado() * 100) / $this->getValorAtualizado(), 2);
        }
        return $nPercentualExecutado;
    }


    /**
     * @param integer $iCodAcordo c�digo do Acordo
     */
    public function getQuantidadeValorAnterior($iCodAcordo, $iCodMaterial)
    {

        if (!empty($iCodMaterial) != null) {

            $oDaoAcordoItem = db_utils::getDao("acordoitem");
            $sWhere = "ac20_pcmater = {$iCodMaterial} and ac20_acordoposicao in (select ac26_sequencial from acordoposicao where ac26_acordo = {$iCodAcordo})
      and ((ac20_quantidade > 0 and ac20_valorunitario > 0) or (ac20_acordoposicaotipo in (9,10)))";
            $sSqlAcordoitem = $oDaoAcordoItem->sql_query_file(null, "*", "ac20_acordoposicao desc", $sWhere);
            $rsAcordoItem   = $oDaoAcordoItem->sql_record($sSqlAcordoitem);
            if ($oDaoAcordoItem->numrows > 0) {

                return db_utils::fieldsMemory($rsAcordoItem, 0, false, false, true);
            }
        }
    }

    /**
     * Retorna quantidade aditivada em comparacao com a posicao anterior
     * Necessario para o sicom
     * @param integer $iNumeroAditamento
     */
    public function getQuantidadeAditivada($iNumeroAditamento)
    {

        $oDaoAcordoitem  = new cl_acordoitem;

        $sCampos    = "SUM(CASE WHEN ac26_numero = {$iNumeroAditamento} THEN ac20_quantidade
    WHEN ac26_numero = " . ($iNumeroAditamento - 1) . " THEN ac20_quantidade*-1 END) AS aditivado";
        $sWhere     = "ac16_sequencial = {$this->getPosicao()->getAcordo()} AND ac20_ordem = {$this->getOrdem()} AND ac20_pcmater = " . $this->getMaterial()->getMaterial();
        $sSqlItens  = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem     = $oDaoAcordoitem->sql_record($sSqlItens);

        return abs(db_utils::fieldsMemory($rsItem, 0)->aditivado);
    }

    /**
     * Retorna o valor total do aditamento anterior ao informado
     * Necessario para o sicom
     * @param integer $iNumeroAditamento
     */
    public function getValorTotalPosicaoAnterior($iNumeroAditamento)
    {

        $oDaoAcordoitem  = new cl_acordoitem;
        $iNumeroAditamentoAnterior = $iNumeroAditamento;
        $sCampos    = "acordoitem.ac20_valoraditado AS totalanterior";
        $sWhere     = "ac26_numero = {$iNumeroAditamentoAnterior} and ac16_sequencial = {$this->getPosicao()->getAcordo()} AND ac20_ordem = {$this->getOrdem()} AND ac20_pcmater = " . $this->getMaterial()->getMaterial();
        $sSqlItens  = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem     = $oDaoAcordoitem->sql_record($sSqlItens);
        //var_dump($sSqlItens);
        return db_utils::fieldsMemory($rsItem, 0)->totalanterior;
    }



    /**
     * Retorna o valor total do aditamento anterior ao informado
     * Necessario para o sicom
     * @param integer $iNumeroAditamento
     */
    public function getValorTotalPosicaoAnteriors($iNumeroAditamento)
    {

        $oDaoAcordoitem  = new cl_acordoitem;
        $iNumeroAditamentoAnterior = $iNumeroAditamento - 1;
        $sCampos    = "acordoitem.ac20_valorunitario AS totalanterior";
        $sWhere     = "ac26_numero = {$iNumeroAditamentoAnterior} and ac16_sequencial = {$this->getPosicao()->getAcordo()} AND ac20_ordem = {$this->getOrdem()} AND ac20_pcmater = " . $this->getMaterial()->getMaterial();
        $sSqlItens  = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem     = $oDaoAcordoitem->sql_record($sSqlItens);
        //var_dump($sSqlItens);
        return db_utils::fieldsMemory($rsItem, 0)->totalanterior;
    }

    /**
     * Retorna o valor total do aditamento anterior ao informado
     * Necessario para o sicom
     * @param integer $iNumeroAditamento
     */
    public function getQuantidadePosicaoAnterior($iNumeroAditamento)
    {

        $oDaoAcordoitem  = new cl_acordoitem;
        $iNumeroAditamentoAnterior = $iNumeroAditamento - 1;
        $sCampos    = "acordoitem.ac20_quantidade AS quantidadeanterior";
        $sWhere     = "ac26_numero = {$iNumeroAditamentoAnterior} and ac16_sequencial = {$this->getPosicao()->getAcordo()} AND ac20_ordem = {$this->getOrdem()} AND ac20_pcmater = " . $this->getMaterial()->getMaterial();
        $sSqlItens  = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, $sWhere);
        $rsItem     = $oDaoAcordoitem->sql_record($sSqlItens);

        return abs(db_utils::fieldsMemory($rsItem, 0)->quantidadeanterior);
    }
}
