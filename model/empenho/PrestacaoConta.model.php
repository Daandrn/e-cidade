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

/**
 * Model para realizar prestacao de conta
 *
 * @author   Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @package  empenho
 * @version  1.0 $
 */
class PrestacaoConta {

	/**
	 * Objeto do empenho financeiro
	 * @var EmpenhoFinanceiro
	 */
	private $oEmpenhoFinanceiro;

  /**
   * N�mero do Sequencial do registro da presta��o de contas da tabela emppresta
   * @var Integer
   */
  private $iSequencialPrestacaoConta;

  /**
   * N�mero do Sequencial do registro da presta��o de contas da tabela emppresta
   * @var Integer
   */
  public $dataLancamento = null;

	/**
	 * Itens da prestacao de contas
	 * @var array
	 */
	private $aItens = array();

	/**
	 * Construtor da classe, recebe por parametro o empenho financeiro
	 * @param EmpenhoFinanceiro $oEmpenhoFinanceiro
   * @param Integer $iSequencialPrestacaoConta
	 */
	public function __construct(EmpenhoFinanceiro $oEmpenhoFinanceiro, $iSequencialPrestacaoConta, $dataLancamento = null) {
    $this->oEmpenhoFinanceiro        = $oEmpenhoFinanceiro;
    $this->iSequencialPrestacaoConta = $iSequencialPrestacaoConta;
		$this->dataLancamento = $dataLancamento;
	}

  /**
   * Metodo criado para executar lancamento contabil
   * Recebe como parametro un $oLancamentoAuxiliarEmpenho
   * Executar o lancamento cfe exemplo da Classe Transferencia.model., e codigo do tipo do documento.
   * @param object  $oLancamentoAuxiliarEmpenho
   * @param integer $iCodigoDocumento
   * @return bool
   */
  protected function executarLancamentoContabil($oLancamentoAuxiliarEmpenho, $iCodigoDocumento) {

  	$oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoDocumento);
  	$iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();
  	$oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
  	$oEventoContabil->executaLancamento($oLancamentoAuxiliarEmpenho, $this->dataLancamento);

  	return true;
  }

  /**
   * Processar o lancamento
   *
   * @param string $sObservacao
   */
  public function processarLancamento($sObservacao) {

    $iAnoUsuSessao    = db_getsession("DB_anousu");
  	$iCodigoDocumento = 414;
    $oEventoContabil  = new EventoContabil($iCodigoDocumento, $iAnoUsuSessao);
    $aLancamentos     = $oEventoContabil->getEventoContabilLancamento();
    $iCodigoHistorico = $aLancamentos[0]->getHistorico();
    $nValorTotalNota  = $this->getTotalDeclaradoPrestacaoConta();

    /**
     * Descobrimos o vinculo entre o desdobramento do empenho e o plano de contas PCASP
     */
    $oContaOrcamento       = new ContaOrcamento($this->oEmpenhoFinanceiro->getDesdobramentoEmpenho(), $iAnoUsuSessao);
    $oPlanoContaPCASP      = $oContaOrcamento->getPlanoContaPCASP();
    if (empty($oPlanoContaPCASP)) {
      throw new BusinessException("Conta {$this->oEmpenhoFinanceiro->getDesdobramentoEmpenho()} do plano or�ament�rio sem v�nculo com o PCASP.");
    }
    $aContasReduzidasPCASP = $oPlanoContaPCASP->getContasReduzidas();
    $iCodigoContaDebito   = null;

  	foreach ($aContasReduzidasPCASP as $oContaReduzida) {

  	  if (db_getsession('DB_instit') == $oContaReduzida->c61_instit) {
  	    $iCodigoContaDebito = $oContaReduzida->c61_reduz;
  	  }
  	}

  	if (empty($iCodigoContaDebito)) {

  	  $sMsgErro  = "N�o foi poss�vel localizar a conta reduzida para esta institui��o. ";
  	  $sMsgErro .= "Revise o cadastro do plano de contas.";
  	  throw new BusinessException ($sMsgErro);
  	}

  	if ($nValorTotalNota != 0) {

  		$oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
  		$oLancamentoAuxiliar->setPrestacaoContas(true);
  		$oLancamentoAuxiliar->setObservacaoHistorico($sObservacao);
  		$oLancamentoAuxiliar->setHistorico($iCodigoHistorico);
  		$oLancamentoAuxiliar->setValorTotal($nValorTotalNota);
  		$oLancamentoAuxiliar->setContaDebito($iCodigoContaDebito);
  		$oLancamentoAuxiliar->setCodigoElemento($this->oEmpenhoFinanceiro->getDesdobramentoEmpenho());
  		$oLancamentoAuxiliar->setEmpenho($this->oEmpenhoFinanceiro->getCodigo());
  		$oLancamentoAuxiliar->setNumeroEmpenho($this->oEmpenhoFinanceiro->getNumero());
  		$oLancamentoAuxiliar->setCodigoDotacao($this->oEmpenhoFinanceiro->getDotacao()->getCodigo());
  		$oLancamentoAuxiliar->setFavorecido($this->oEmpenhoFinanceiro->getCgm()->getCodigo());
  		$oLancamentoAuxiliar->setCaracteristicaPeculiar($this->oEmpenhoFinanceiro->getCaracteristicaPeculiar());

      /**
       * Conta corrente do tipo credor, codigo 104
       */
      $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
      $oContaCorrenteDetalhe->setCredor($this->oEmpenhoFinanceiro->getCgm());
      $oContaCorrenteDetalhe->setRecurso(new Recurso($this->oEmpenhoFinanceiro->getRecurso()));
      $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

  		$this->executarLancamentoContabil($oLancamentoAuxiliar, $iCodigoDocumento);
  	}
  	return true;
  }

  /**
   * Estorna o lan�amento cont�bil
   * @param $sObservacao
   * @return bool
   * @throws BusinessException
   */
  public function estornarLancamento($sObservacao) {

  	$iCodigoDocumento = 415;
  	$iAnoUsuSessao    = db_getsession("DB_anousu");
  	$oEventoContabil  = new EventoContabil($iCodigoDocumento, $iAnoUsuSessao);
  	$aLancamentos     = $oEventoContabil->getEventoContabilLancamento();
  	$iCodigoHistorico = $aLancamentos[0]->getHistorico();

  	/**
  	 * Descobrimos o vinculo entre o desdobramento do empenho e o plano de contas PCASP
  	 */
  	$oContaOrcamento       = new ContaOrcamento($this->oEmpenhoFinanceiro->getDesdobramentoEmpenho(), $iAnoUsuSessao);
  	$oPlanoContaPCASP      = $oContaOrcamento->getPlanoContaPCASP();
  	$aContasReduzidasPCASP = $oPlanoContaPCASP->getContasReduzidas();
  	$iCodigoContaCredito   = null;

  	foreach ($aContasReduzidasPCASP as $oContaReduzida) {

  	  if (db_getsession('DB_instit') == $oContaReduzida->c61_instit) {
  	    $iCodigoContaCredito = $oContaReduzida->c61_reduz;
  	  }
  	}

  	if (empty($iCodigoContaCredito)) {

  	  $sMsgErro  = "N�o foi poss�vel localizar a conta reduzida para esta institui��o. ";
  	  $sMsgErro .= "Revise o cadastro do plano de contas.";
  	  throw new BusinessException ($sMsgErro);
  	}

  	$nValorTotalNota  = $this->getTotalDeclaradoPrestacaoConta();
  	$oLancamentoAuxiliar = new LancamentoAuxiliarEmpenho();
  	$oLancamentoAuxiliar->setPrestacaoContas(true);
  	$oLancamentoAuxiliar->setObservacaoHistorico($sObservacao);
  	$oLancamentoAuxiliar->setHistorico($iCodigoHistorico);
  	$oLancamentoAuxiliar->setValorTotal($nValorTotalNota);
  	$oLancamentoAuxiliar->setContaCredito($iCodigoContaCredito);
  	$oLancamentoAuxiliar->setCodigoElemento($this->oEmpenhoFinanceiro->getDesdobramentoEmpenho());
  	$oLancamentoAuxiliar->setNumeroEmpenho($this->oEmpenhoFinanceiro->getNumero());
  	$oLancamentoAuxiliar->setCodigoDotacao($this->oEmpenhoFinanceiro->getDotacao()->getCodigo());
  	$oLancamentoAuxiliar->setFavorecido($this->oEmpenhoFinanceiro->getCgm()->getCodigo());
  	$oLancamentoAuxiliar->setCaracteristicaPeculiar($this->oEmpenhoFinanceiro->getCaracteristicaPeculiar());

    /**
     * Conta corrente do tipo credor, codigo 104
     */
    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setCredor($this->oEmpenhoFinanceiro->getCgm());
    $oContaCorrenteDetalhe->setRecurso(new Recurso($this->oEmpenhoFinanceiro->getRecurso()));
    $oLancamentoAuxiliar->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

  	$this->executarLancamentoContabil($oLancamentoAuxiliar, $iCodigoDocumento);
  	return true;
  }

  /**
   * Busca os itens de uma prestacao de contas
   * - Retorna um array contendo um stdClass com as contas cadastradas
   * @return array
   */
  public function getItens() {

    if (count($this->aItens) == 0) {

      $oDaoEmpPrestaItem  = db_utils::getDao('empprestaitem');
      $sWherePrestacao    = "e46_numemp = {$this->oEmpenhoFinanceiro->getNumero()}";
      $sWherePrestacao   .= " and e46_emppresta = {$this->iSequencialPrestacaoConta}";

      $sSqlBuscaItem      = $oDaoEmpPrestaItem->sql_query_file(null, "*", null, $sWherePrestacao);
      $rsBuscaItem        = $oDaoEmpPrestaItem->sql_record($sSqlBuscaItem);
      if ($oDaoEmpPrestaItem->numrows > 0) {
        $this->aItens = db_utils::getCollectionByRecord($rsBuscaItem);
      }
    }
    return $this->aItens;
  }

  /**
   * Retorna o total declarado na prestacao de contas
   * @return float
   */
  public function getTotalDeclaradoPrestacaoConta() {

    $nValorTotalNota = 0;
    $aItemPrestacaoConta = $this->getItens();
    foreach ($aItemPrestacaoConta as $oStdItem) {
      $nValorTotalNota += $oStdItem->e46_valor - (float)$oStdItem->e46_desconto;
    }
    //busca o desconto da nota

    $oDaoempdescontonota  = db_utils::getDao('empdescontonota');
    $Odesconto = $oDaoempdescontonota->sql_record("SELECT sum(x.e999_desconto) as desconto FROM
    (SELECT DISTINCT e999_nota,
                     e999_empenho,
                     e999_desconto
     FROM empdescontonota
     WHERE e999_empenho = {$this->oEmpenhoFinanceiro->getNumero()}) x");
    $Odesconto = db_utils::fieldsMemory($Odesconto);
    $nValorTotalNota = $nValorTotalNota - (float) $Odesconto->desconto;
    return $nValorTotalNota;
  }

  /**
   * M�todo que retorna um array contendo os tipos de presta��o de contas.
   * @return Ambigous <multitype:stdClass>
   */
  public static function getTiposPrestacaoContas() {

    $oDaoEmpPrestaTip  = db_utils::getDao('empprestatip');
    $sSqlBuscaTipo     = $oDaoEmpPrestaTip->sql_query_file(null, "*", 'e44_tipo');
    $rsBuscaTipo       = $oDaoEmpPrestaTip->sql_record($sSqlBuscaTipo);
    $aTiposEncontrados = array();
    if ($oDaoEmpPrestaTip->numrows > 0) {
      $aTiposEncontrados = db_utils::getCollectionByRecord($rsBuscaTipo);
    }
    return $aTiposEncontrados;
  }
}
