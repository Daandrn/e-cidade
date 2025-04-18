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

//require_once ("model/empenho/FinalidadePagamentoFundeb.model.php");
require_once ("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once ("std/DBDate.php");

/**
 * Model para encapsulamento de um SLIP
 * @author Matheus Felini / Bruno Silva
 * @package caixa
 * @subpackage slip
 * @version $Revision: 1.41 $
 */
abstract class Transferencia {

  /**
   * Objeto Slip
   * @var slip
   */
  protected $oSlip;

  /**
   * Tipo de opera��o da tansferencia
   * @var integer
   */
  protected $iTipoOperacao;

  /**
   * C�digo do Lan�amento cont�bil executado pela transferencia
   * @var integer
   */
  protected $iCodigoLancamento;

  /**
   * Codigo do terminal da autenticacao
   * @var integer
   */
  private $iIDTerminal;

  /**
   * numero sequencial da autenticao
   * @var integer
   */
  private $iNumeroAutenticacao;

  /**
   * data da autenticao
   * @var string
   */

  /**
   * string para autentica��o
   * @var string
   */
  private $sStringAutenticacao;

  /**
   * Data da autentica��o
   * @var date
   */
  private $dtDataAutenticacao;

  /**
   * Finalidade de pagamento a Credito
   * @var FinalidadePagamentoFundeb
   */
  private $oFinalidadePagamentoFundebCredito;

  /**
   * Conta cr�dito do plano de contas
   * @var ContaPlanoPCASP
   */
  private $oContaPlanoCredito;

 /**
   * @var string
   */
  private $iNumDocumento;

       /**
   * Select Tipo
   * @var string
   */
  private $iTipoSelect;
  
  /**
   * @var string
   */
  protected $sProcessoAdministrativo;

    protected $iFonteRecurso;

  /**
   * Construtor da classe
   */
  public function __construct($iCodigoSlip = null) {
    $this->oSlip = new slip($iCodigoSlip);
  }

  /**
   * Retorna o c�digo do lan�amento
   * @return integer
   */
  public function getCodigoLancamento() {
    return $this->iCodigoLancamento;
  }

  /**
   * Codigo do cheque
   *
   * @var integer
   */
  public $iCodCheque = null;
    /**
   * Seta o cheque
   * @param integer $iCodCheque codigo do cheque
   */
  function setCheque($iCodCheque) {
    $this->iCodCheque = $iCodCheque;
  }
  /**
   * Retorna o cheque
   * @return integer
   */
  function getCheque() {

    if ($this->iCodCheque == null) {
      $this->iCodCheque = 0;
    }
    return $this->iCodCheque;
  }

  /**
   * Salva os dados do slip
   */
  public function salvar() {

    $this->oSlip->save();
    $this->salvarVinculoComProcesso();
  }

  /**
   * @param null $sDataLancamento
   * @param bool $lEstorno
   * @return bool
   * @throws Exception
   */
  public function executarLancamentoContabil($sDataLancamento = null, $lEstorno = false, $iCodigoMovimento = null) {

    $iCodigoDocumento        = $this->getDocumentoPorTipoInclusao();

    $sEstornar = 'f';
    if ($lEstorno){
      $sEstornar = 't';
    }

    if ($lEstorno) {

      switch ($iCodigoDocumento) {

        case 160:
          $iCodigoDocumento = 162;
          break;

        case 120:
          $iCodigoDocumento = 121;
          break;

        case 130:
          $iCodigoDocumento = 131;
          break;

        case 140:
          $iCodigoDocumento = 141;
          break;

        case 150:
          $iCodigoDocumento = 152;
          break;
      }
    }

    $sSqlBuscaContaCorrente  = "select corrente.k12_conta,   ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_id,      ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_data,    ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_autent,  ";
    $sSqlBuscaContaCorrente .= "       corrente.k12_valor    ";
    $sSqlBuscaContaCorrente .= "  from corrente ";
    $sSqlBuscaContaCorrente .= "       inner join corlanc  on corrente.k12_id     = corlanc.k12_id      ";
    $sSqlBuscaContaCorrente .= "                          and corrente.k12_data   = corlanc.k12_data    ";
    $sSqlBuscaContaCorrente .= "                          and corrente.k12_autent = corlanc.k12_autent  ";
    $sSqlBuscaContaCorrente .= "       left join corconf  on corconf.k12_id      = corrente.k12_id     ";
    $sSqlBuscaContaCorrente .= "                          and corconf.k12_data    = corrente.k12_data   ";
    $sSqlBuscaContaCorrente .= "                          and corconf.k12_autent  = corrente.k12_autent ";
    $sSqlBuscaContaCorrente .= " where corlanc.k12_codigo  = {$this->getCodigoSlip()} ";
    $sSqlBuscaContaCorrente .= "   and corrente.k12_estorn = '{$sEstornar}' ";
    if (!empty($iCodigoMovimento)) {
      $sSqlBuscaContaCorrente .= " and corconf.k12_codmov = {$iCodigoMovimento} ";
    }
    $rsBuscaCorrente         = db_query($sSqlBuscaContaCorrente);

    if (pg_num_rows($rsBuscaCorrente) == 0) {
      throw new Exception("N?o foi poss�vel buscar os dados da autentica??o para exec?cio dos lan?amentos cont?beis.");
    }

    $oDadosAutenticao        = db_utils::fieldsMemory($rsBuscaCorrente, 0);
    $iCodigoContaCorrente    = $oDadosAutenticao->k12_conta;

    $oContaPlano = new ContaPlanoPCASP(
      null,
      db_getsession('DB_anousu'),
      $iCodigoContaCorrente,
      db_getsession('DB_instit')
    );

    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setRecurso(new Recurso($oContaPlano->getRecurso()));
    if ($this->getFonteRecursos() != null) 
        $oContaCorrenteDetalhe->setRecurso($this->getFonteRecursos());
    $oContaCorrenteDetalhe->setContaBancaria(null);
    $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($this->getCodigoCgm()));

    $oLancamentoAuxiliarSlip = new LancamentoAuxiliarSlip();
    $oLancamentoAuxiliarSlip->setIDTerminal($oDadosAutenticao->k12_id);
    $oLancamentoAuxiliarSlip->setDataAutenticacao($oDadosAutenticao->k12_data);
    $oLancamentoAuxiliarSlip->setNumeroAutenticacao($oDadosAutenticao->k12_autent);
    $oLancamentoAuxiliarSlip->setHistorico($this->getHistorico());
    $oLancamentoAuxiliarSlip->setValorTotal(abs($oDadosAutenticao->k12_valor));
    $oLancamentoAuxiliarSlip->setObservacaoHistorico($this->getObservacao());
    $oLancamentoAuxiliarSlip->setCodigoSlip($this->getCodigoSlip());
    $oLancamentoAuxiliarSlip->setCodigoReduzido($iCodigoContaCorrente);
    $oLancamentoAuxiliarSlip->setFavorecido($this->getCodigoCgm());
    $oLancamentoAuxiliarSlip->setEstorno(false);
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarCredito($this->getCaracteristicaPeculiarCredito());
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarDebito($this->getCaracteristicaPeculiarDebito());
    $oLancamentoAuxiliarSlip->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

    $oDocumentoContabil       = SingletonRegraDocumentoContabil::getDocumento($iCodigoDocumento);
    $iCodigoDocumentoExecutar = $oDocumentoContabil->getCodigoDocumento();

    $oEventoContabil          = new EventoContabil($iCodigoDocumentoExecutar, db_getsession("DB_anousu"));
    if ($oEventoContabil->estorno()) {
      $oLancamentoAuxiliarSlip->setEstorno(true);
    }
    $oEventoContabil->executaLancamento($oLancamentoAuxiliarSlip, $sDataLancamento);
    $this->iCodigoLancamento =  $oEventoContabil->getCodigoLancamento();
    return true;
  }

  /**
   * Retorna o tipo de operacao que usu�rio incluiu de acordo com a tabela sliptipooperacao
   * @throws Exception
   * @return integer codigo do tipo da operacao (sliptipooperacao)
   */
  public function getTipoOperacaoPorInclusao() {

    if (empty($this->iTipoOperacao)) {

      /*
       * Busca o tipo de inclus�o para descobrirmos qual documento vamos executar
       */
      $oDaoSlipTipoOperacao  = db_utils::getDao('sliptipooperacaovinculo');
      $sSqlBuscaTipoOperacao = $oDaoSlipTipoOperacao->sql_query_file($this->getCodigoSlip());
      $rsBuscaTipoOperacao   = $oDaoSlipTipoOperacao->sql_record($sSqlBuscaTipoOperacao);
      if ($oDaoSlipTipoOperacao->numrows == 0) {
        throw new Exception("N�o foi poss�vel localizar o tipo de opera��o do slip {$this->getCodigoSlip()}.");
      }
      $iTipoOperacao       = db_utils::fieldsMemory($rsBuscaTipoOperacao, 0)->k153_slipoperacaotipo;
      $this->iTipoOperacao = $iTipoOperacao;
    }
    return $this->iTipoOperacao;
  }

  /**
   * Autentica um slip
   * @throws Exception
   * @return boolean
   */
  public function executaAutenticacao($dtAutenticacao = null) {

    $iIp                = db_getsession("DB_ip");
    $oDaocfautent       = db_utils::getDao('cfautent');
    $iInstituicaoSessao = db_getsession("DB_instit");
    $sSqlAutenticadora  = $oDaocfautent->sql_query_file(null,
      "k11_id,
       k11_tipautent",
      '',
      "k11_ipterm = '{$iIp}'
                                                         and k11_instit = {$iInstituicaoSessao}"
    );
    $rsAutenticador    = $oDaocfautent->sql_record($sSqlAutenticadora);

    if ($oDaocfautent->numrows == '0') {
      throw new Exception("Cadastre o ip {$iIp} como um caixa.");
    }

    $iCodigoTerminal    = db_utils::fieldsMemory($rsAutenticador, 0)->k11_id;
    $iCodigoSlip        = $this->getCodigoSlip();
    $iCodigoInstituicao = db_getsession("DB_instit");

    // Correcao para OC18200
    if (!$dtAutenticacao) 
        $dtSessao = date("Y-m-d", db_getsession("DB_datausu"));
    else 
        $dtSessao = date("Y-m-d", strtotime($dtAutenticacao));
 
    $sSqlExecutaAutenticacao = "select fc_auttransf({$iCodigoSlip}, '{$dtSessao}', '{$iIp}', true, {$this->getCheque()}, {$iCodigoInstituicao}) as fc_autenticacao";
    $rsExecutaAutenticacao = db_query($sSqlExecutaAutenticacao);
    if (!$rsExecutaAutenticacao) {
      throw new Exception("N�o foi poss�vel realizar a autentica��o");
    }
    $sStringAutenticacao = db_utils::fieldsMemory($rsExecutaAutenticacao, 0)->fc_autenticacao;
    if (substr($sStringAutenticacao, 0, 1) != 1) {
      throw new Exception("N�o foi poss�vel executar a autentica��o.\n\n{$sStringAutenticacao}");
    }

    $this->setIDTerminal($iCodigoTerminal);
    $this->setDataAutenticacao($dtSessao);
    $this->setNumeroAutenticacao(substr($sStringAutenticacao, 1, 7));
    $this->setStringAutenticacao($sStringAutenticacao);

    return true;
  }

  /**
   * Retorna o documento por tipo de inclus�o
   * @throws Exception
   * @return integer - Codigo do documento que ser� executado no lan�amento cont�bil
   */
  public function getDocumentoPorTipoInclusao() {

    $iTipoOperacao    = $this->getTipoOperacaoPorInclusao();
    $iCodigoDocumento = 0;
    switch ($iTipoOperacao) {

      /**
       * Transferencia Financeira
       */
      case 1:
        $iCodigoDocumento = 120;
        break;
      case 2:
        $iCodigoDocumento = 121;
        break;
      case 3:
        $iCodigoDocumento = 130;
        break;
      case 4:
        $iCodigoDocumento = 131;
        break;

      /**
       * Transferencia Bancaria
       */
      case 5:
        $iCodigoDocumento = 140;
        break;
      case 6:
        $iCodigoDocumento = 141;
        break;

      /**
       * Cau��o
       */
      case 7:
        $iCodigoDocumento = 150;
        break;
      case 8:
        $iCodigoDocumento = 152;
        break;
      case 9:
        $iCodigoDocumento = 151;
        break;
      case 10:
        $iCodigoDocumento = 153;
        break;

      /**
       * Dep�sito de Diversas Origens
       */
      case 11:
        $iCodigoDocumento = 160;
        break;
      case 12:
        $iCodigoDocumento = 162;
        break;
      case 13:
        $iCodigoDocumento = 161;
        break;
      case 14:
        $iCodigoDocumento = 163;
        break;
      
      /**
       * Reconhecimento de perdas
       */
      case 15:
        $iCodigoDocumento = 164;
      break;
      case 16:
        $iCodigoDocumento = 165;
      break;

      /**
       * Reconhecimento de ganhos RPPS
       */
      case 17:
        $iCodigoDocumento = 166;
      break;
      case 18:
        $iCodigoDocumento = 167;
      break;
    }
    return $iCodigoDocumento;
  }

  /**
   * Anula um slip
   * @param string $sMotivo
   */
  public function anular($sMotivo,$sDataEstorno) {
    $this->oSlip->anular($sMotivo, true, $this,$sDataEstorno);
  }

  /**
   * @param $iCodigoSlip
   */
  public function setCodigoSlip($iCodigoSlip) {
    $this->oSlip->setSlip($iCodigoSlip);
  }

  /**
   * C�digo sequencial do Slip
   * @return int
   */
  public function getCodigoSlip() {
    return $this->oSlip->getSlip();
  }

  /**
   * @return array
   */
  public function getArrecacoes() {
    return $this->oSlip->getArrecacoes();
  }

 /**
   * Retorna objeto Slip
   * @return Slip
   */
  public function getSlip() {
    return $this->oSlip;
  }

  /**
   * @param integer $iArrecacoes
   */
  public function adicionarArrecadacao($iArrecadacoes) {
    $this->oSlip->addArrecadacao($iArrecadacoes);
  }

  /**
   * @return array
   */
  public function getPagamentos() {
    return $this->oSlip->getPagamentos();
  }

  /**
   * @param array $aPagamentos
   */
  private function setPagamentos($aPagamentos) {
//      $this->oSlip->setPagamentos($aPagamentos);
  }

  /**
   * @return array
   */
  public function getRecursos() {
    return $this->oSlip->getRecursos();
  }

  /**
   * Adiciona um Recurso ao Slip
   *
   * @param integer $iRecurso codigo do recurso
   * @param float   $nValor valor do Recurso
   */
  public function adicionarRecurso($iRecurso, $nValor = 0) {
    $this->oSlip->addRecurso($iRecurso, $nValor);
  }

  /**
   * @return string
   */

  public function getData() {
    return $this->oSlip->getData();
  }

  /**
   * @param string $dtData
   */

  public function setData($dtData) {
    $this->oSlip->setData($dtData);
  }

    /**
   * @param $sNumero Documento
   */
  public function setNumeroDocumento($iNumDocumento) {
    $this->oSlip->setNumeroDocumento($iNumDocumento);
  }

  /**
   * @return string
   */
  public function getNumeroDocumento() {
    return $this->oSlip->getNumeroDocumento();
  }

   /**
   * Retorna o Select Tipo
   * @return integer
   */
  public function getTipoSelect() {
    return $this->oSlip->getTipoSelect();
  }

  /**
   * Seta o codigo de um movimento
   * @param integer $iTipoSelect
   */
  public function setTipoSelect($iTipoSelect) {
    $this->oSlip->setTipoSelect($iTipoSelect);
  }

  /**
   * @return integer
   */
  public function getContaCredito() {
    return  $this->oSlip->getContaCredito();
  }

  /**
   * @param integer $iContaCredito
   */
  public function setContaCredito($iContaCredito) {
    $this->oSlip->setContaCredito($iContaCredito);
  }

  /**
   * @return integer
   */
  public function getContaDebito() {
    return $this->oSlip->getContaDebito();
  }

  /**
   * Retorna um objeto do tipo ContaPlanoPCASP
   * @return ContaPlanoPCASP
   */
  public function getContaPlanoDebito() {

    if ($this->getContaDebito() != "" && empty($this->oContaPlanoDebito)) {

      $oDBDate          = new DBDate($this->getData());
      $oPlanoContaPCASP = new ContaPlanoPCASP(null, $oDBDate->getAno(), $this->getContaDebito(), $this->getInstituicao());
      $this->oContaPlanoDebito = $oPlanoContaPCASP;
    }
    return $this->oContaPlanoDebito;
  }

  /**
   * Retorna um objeto do tipo ContaPlanoPCASP
   * @return ContaPlanoPCASP
   */
  public function getContaPlanoCredito() {

    if ($this->getContaCredito() != "" && empty($this->oContaPlanoCredito)) {

      $oDBDate          = new DBDate($this->getData());
      $oPlanoContaPCASP = new ContaPlanoPCASP(null, $oDBDate->getAno(), $this->getContaCredito(), $this->getInstituicao());
      $this->oContaPlanoCredito = $oPlanoContaPCASP;
    }
    return $this->oContaPlanoCredito;
  }

  /**
   * @param integer $iContaDebito
   */
  public function setContaDebito($iContaDebito) {
    $this->oSlip->setContaDebito($iContaDebito);
  }

  /**
   * @return integer
   */
  public function getSituacao() {
    return $this->oSlip->getSituacao();
  }

  /**
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->oSlip->setSituacao($iSituacao);
  }

  /**
   * @return unknown_type
   */
  public function getTipoPagamento() {
    return $this->oSlip->getTipoPagamento();
  }

  /**
   * @param unknown_type $iTipoPagamento
   */
  public function setTipoPagamento($iTipoPagamento) {
    $this->oSlip->setTipoPagamento($iTipoPagamento);
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->oSlip->getValor();
  }

  public function getDevolucao() {
      return $this->oSlip->iExercicioCompetenciaDevolucao;
  }

  public function setExercicioCompetenciaDevolucao($iExercicio) {
      $this->oSlip->iExercicioCompetenciaDevolucao = $iExercicio;
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->oSlip->setValor($nValor);
  }

  /**
   * @return string
   */
  public function getObservacao() {
    return $this->oSlip->getObservacoes();
  }

  /**
   * @param string $sObservacoes
   */
  public function setObservacao($sObservacoes) {
    $this->oSlip->setObservacoes($sObservacoes);
  }
  /**
   * @return integer
   */
  public function getCodigoCgm() {
    return $this->oSlip->getNumCgm();
  }

  /**
   * @param integer $iNumCgm
   */
  public function setCodigoCgm($iNumCgm) {
    $this->oSlip->setNumCgm($iNumCgm);
  }
  /**
   * @return integer
   */
  public function getHistorico() {
    return $this->oSlip->getHistorico();
  }

  /**
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico) {
    $this->oSlip->setHistorico($iHistorico);
  }
  /**
   * @return integer
   */
  public function getMovimento() {
    return $this->oSlip->getMovimento();
  }

  /**
   * Retorna a institui��o que criou a transferencia
   * @return integer
   */
  public function getInstituicao() {
    return $this->oSlip->getInstituicao();
  }
  /**
   * Seta a institui��o que criou a transferencia
   * @param integer
   */
  public function setInstituicao($iInstituicao) {
    $this->oSlip->setInstituicao($iInstituicao);
  }

  /**
   * Seta a caracteristica peculiar debito
   * @param string $sCodigoCaracteristica
   */
  public function setCaracteristicaPeculiarDebito($sCodigoCaracteristica) {
    $this->oSlip->setCaracteristicaPeculiarDebito($sCodigoCaracteristica);
  }

  /**
   * Retorna a caracteristica peculiar debito
   * @param string
   */
  public function getCaracteristicaPeculiarDebito() {
    return $this->oSlip->getCaracteristicaPeculiarDebito();
  }

  /**
   * Seta a caracteristica peculiar credito
   * @param string $sCodigoCaracteristica
   */
  public function setCaracteristicaPeculiarCredito($sCodigoCaracteristica) {
    $this->oSlip->setCaracteristicaPeculiarCredito($sCodigoCaracteristica);
  }

  /**
   * Retorna a caracteristica peculiar credito
   * @param string
   */
  public function getCaracteristicaPeculiarCredito() {
    return $this->oSlip->getCaracteristicaPeculiarCredito();
  }

  /**
   * Seta o tipo de opera��o
   * @param integer $iTipoOperacao
   */
  public function setTipoOperacao($iTipoOperacao) {
    $this->iTipoOperacao = $iTipoOperacao;
  }

  /**
   * Retorna o tipo de opera��o de um slip
   * @return integer
   */
  public function getTipoOperacao() {
    return $this->iTipoOperacao;
  }

  /**
   * Define a data de autenticacao do SLIP
   * @param string $sDataAutenticacao data da autenticacao
   */
  public function setDataAutenticacao($sDataAutenticacao) {
    $this->dtDataAutenticacao = $sDataAutenticacao;
  }

  /**
   * retorna a data de autenticacao do SLIP
   * @return string data de autenticacao
   */
  public function getDataAutenticacao() {
    return $this->dtDataAutenticacao;
  }

  /**
   * Define o Numero autenticacao do SLIP
   * @param integer $iNumeroAutenticacao numero da autenticacao
   */
  public function setNumeroAutenticacao($iNumeroAutenticacao) {
    $this->iNumeroAutenticacao = $iNumeroAutenticacao;
  }

  /**
   * retorna  numero da autenticacao
   * @return integer numero da autenticacao
   */
  public function getNumeroAutenticacao() {
    return $this->iNumeroAutenticacao;
  }

  /**
   * Define o o id do terminal da autenticacao do SLIP
   * @param integer $iIDTerminal o id do terminal da autenticacao
   */
  public function setIDTerminal($iIDTerminal) {
    $this->iIDTerminal = $iIDTerminal;
  }

  /**
   * retorna o id do terminal da autenticacao
   * @return integer id do terminal autenticacao
   */
  public function getIDTerminal() {
    return $this->iIDTerminal;
  }

  /**
   * Define string para impressao
   * @param string
   */
  public function setStringAutenticacao($sStringAutenticacao) {
    $this->sStringAutenticacao = $sStringAutenticacao;
  }

  /**
   * retorna string para impressao
   * @return string
   */
  public function getStringAutenticacao() {
    return $this->sStringAutenticacao;
  }

  /**
   * Salva a finalidade de pagamento do slip
   * @throws BusinessException
   * @throws Exception
   * @return boolean
   */
  public function salvarFinalidadePagamentoFundeb() {

    $sLocalizacao = "contabilidade.caixa.Transferencia.";

    if (empty($this->oFinalidadePagamentoFundebCredito)) {
      throw new BusinessException("N�o � poss�vel executar o m�todo. Objetos FinalidadePagamentoFundeb n�o foram setados.");
    }

    if ($this->getDataAutenticacao() != "") {
      throw new Exception($sLocalizacao."slip_autenticado");
    }

    $oDaoExcluiFinalidade = db_utils::getDao('slipfinalidadepagamentofundeb');
    $oDaoExcluiFinalidade->excluir(null, "e153_slip = {$this->getCodigoSlip()}");

    if ( !empty($this->oFinalidadePagamentoFundebCredito) ) {

      $oDaoSlipFinalidadePagamentoCredito = db_utils::getDao('slipfinalidadepagamentofundeb');
      $oDaoSlipFinalidadePagamentoCredito->e153_sequencial                = null;
      $oDaoSlipFinalidadePagamentoCredito->e153_slip                      = $this->getCodigoSlip();
      $oDaoSlipFinalidadePagamentoCredito->e153_finalidadepagamentofundeb = $this->oFinalidadePagamentoFundebCredito->getCodigoSequencial();
      $oDaoSlipFinalidadePagamentoCredito->incluir(null);

      if ($oDaoSlipFinalidadePagamentoCredito->erro_status == "0") {
        throw new BusinessException(_M($sLocalizacao."vinculo_conta_finalidadefundeb"));
      }
    }

    return true;
  }

  /**
   * Seta a finalidade de pagamento do fundeb
   * @param FinalidadePagamentoFundeb $oFinalidadePagamentoCredito
   */
  public function setFinalidadePagamentoFundebCredito (FinalidadePagamentoFundeb $oFinalidadePagamentoCredito) {
    $this->oFinalidadePagamentoFundebCredito = $oFinalidadePagamentoCredito;
  }

  /**
   * Retorna a finalidade de pagamento do empenho para a conta credito
   * @return FinalidadePagamentoFundeb
   */
  public function getFinalidadePagamentoFundebCredito() {

    if (empty($this->oFinalidadePagamentoFundebCredito)) {

      $oDaoFinalidadePagamento = db_utils::getDao('slipfinalidadepagamentofundeb');
      $sWhereFinalidade        = "e153_slip = {$this->getCodigoSlip()}";
      $sSqlBuscaFinalidade     = $oDaoFinalidadePagamento->sql_query_file(null, "e153_finalidadepagamentofundeb",
        null, $sWhereFinalidade);
      $rsBuscaFinalidade = $oDaoFinalidadePagamento->sql_record($sSqlBuscaFinalidade);
      if ($oDaoFinalidadePagamento->numrows == 1) {

        $iCodigoSequencialFinalidade = db_utils::fieldsMemory($rsBuscaFinalidade, 0)->e153_finalidadepagamentofundeb;
        $this->oFinalidadePagamentoFundebCredito = new FinalidadePagamentoFundeb($iCodigoSequencialFinalidade);
      }
    }
    return $this->oFinalidadePagamentoFundebCredito;
  }

  /**
   * Exclui o vinculo do tipo de operacao com o slip
   * @throws BusinessException
   * @return boolean true
   */
  protected function excluiVinculoTipoDeOperacao() {

    $oDaoSlipTipoOperacaoVinculo = db_utils::getDao('sliptipooperacaovinculo');
    $oDaoSlipTipoOperacaoVinculo->excluir($this->getCodigoSlip());
    if ($oDaoSlipTipoOperacaoVinculo->erro_status === "0") {

      $sLocalizacao = "financeiro.caixa.Transferencia.exclusao_sliptipooperacaovinculo";
      throw new BusinessException(_M($sLocalizacao));
    }
    return true;
  }


  /**
   * Vincula o slip ao tipo de operacao
   * @throws BusinessException
   * @return boolean true
   */
  protected function vinculaSlipTipoDeOperacao() {

    /**
     * Excluimos o v�nculo para incluirmos novamente
     */
    $this->excluiVinculoTipoDeOperacao();

    $oDaoTipoOperacaoVinculo = db_utils::getDao('sliptipooperacaovinculo');
    $oDaoTipoOperacaoVinculo->k153_slip             = $this->getCodigoSlip();
    $oDaoTipoOperacaoVinculo->k153_slipoperacaotipo = $this->getTipoOperacao();
    $oDaoTipoOperacaoVinculo->incluir($this->getCodigoSlip());

    if ($oDaoTipoOperacaoVinculo->erro_status == 0) {

      $sLocalizacao = "financeiro.caixa.Transferencia.vincular_sliptipooperacaovinculo";
      throw new BusinessException(_M($sLocalizacao));
    }
    return true;
  }

  /**
   * M�todo que verifica se o slip j� possui alguma autentica��o
   * @return boolean
   */
  public function possuiAutenticacao() {

    $oDaoLancamento      = new cl_conlancamslip();
    $sSqlBuscaLancamento = $oDaoLancamento->sql_query_file(null, "*", null, "c84_slip = {$this->getCodigoSlip()}");
    $rsBuscaLancamento   = $oDaoLancamento->sql_record($sSqlBuscaLancamento);
    if ($oDaoLancamento->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Exclui o slip em caso de n�o ter sido autenticado nenhuma vez.
   * @throws BusinessException
   * @return boolean true
   */
  public function excluir() {

    /**
     * OC 12180
     * Exclus�o de Slips anulados ou n�o autenticados.
     */
    if ($this->possuiAutenticacao()) {
            $oDaoLancamento      = new cl_conlancamslip();
            $sSqlBuscaLancamento = $oDaoLancamento->sql_query_lancamento_slip(null, "*", null, "c84_slip = {$this->getCodigoSlip()} and k17_situacao in (1, 4)");
            $rsBuscaLancamento   = $oDaoLancamento->sql_record($sSqlBuscaLancamento);
            if ($oDaoLancamento->numrows = 0) {
                throw new BusinessException(_M("financeiro.caixa.Transferencia.exclusao_transferencia_autenticacao"));
            }

    }

    $oDaoExcluirSlip = new cl_slipconcarpeculiar();
    $oDaoExcluirSlip->excluir(null, "k131_slip  = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_vinculo_cpca");
    }

    $oDaoExcluirSlip = new cl_slipnum();
    $oDaoExcluirSlip->excluir(null, "k17_codigo = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_vinculo_cpca");
    }

    $oDaoExcluirSlip = new cl_sliptipooperacaovinculo();
    $oDaoExcluirSlip->excluir(null, "k153_slip  = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_vinculo_tipotransferencia");
    }

    $oDaoExcluirSlip = new cl_transferenciafinanceirarecebimento();
    $oDaoExcluirSlip ->excluir(null, "k151_slip  = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_transferenciafinanceirarecebimento");
    }

    $oDaoExcluirSlip = new cl_transferenciafinanceira();
    $oDaoExcluirSlip ->excluir(null, "k150_slip  = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_vinculo_tipotransferencia");
    }

    $oDaoExcluirSlip = new cl_empageslip();
    $oDaoExcluirSlip->excluir(null, null, "e89_codigo = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_agenda");
    }

    $oDaoExcluirSlip = new cl_protslip();
    $oDaoExcluirSlip->excluir(null, "p106_slip = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_protocolo");
    }

    $oDaoExcluirSlip = new cl_sliprecurso();
    $oDaoExcluirSlip->excluir(null, "k29_slip = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_recurso");
    }

    $oDaoExcluirSlip = new cl_rhslipfolhaslip();
    $oDaoExcluirSlip->excluir(null, "rh82_slip = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_rhslipfolha");
    }

    $oDaoExcluirSlip = new cl_sliptipooperacaovinculo();
    $oDaoExcluirSlip->excluir(null, "k153_slip = {$this->getCodigoSlip()}");
    if ($oDaoExcluirSlip->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_operacaovinculo");
    }

    $this->excluirVinculoComProcesso();

    $result = db_utils::fieldsMemory($res = db_query($sSqlBuscaLancamento),0);

    if ($result->k17_situacao == 4) {

      $iCodSlip = $this->getCodigoSlip();

      $oDaoExcluirSlip = new cl_slipanul();
      $oDaoExcluirSlip->excluir(null, "k18_codigo = {$this->getCodigoSlip()}");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_anulacao_slip");
      }

    if ($this->atualizaSaldos($iCodSlip)) {
          
          $oDaoExcluirSlip = new cl_slip();
          $oDaoExcluirSlip->excluir($this->getCodigoSlip());
          if ($oDaoExcluirSlip->erro_status == "0") {
            throw new BusinessException("financeiro.caixa.Transferencia.exclusao_transferencia_1");
          }
          return true;
      }

    } else {
      $oDaoExcluirSlip = new cl_slip();
      $oDaoExcluirSlip->excluir($this->getCodigoSlip());
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_transferencia_2");
      }
      return true;
    }
  }

  public function atualizaSaldos($iCodSlip) {

  $clTransferencia = new cl_conlancamslip();
  $sSqlSlip = $clTransferencia->sql_query_lancamento_slip(null, "date_part('y', k17_data) as ano, k17_instit,c70_codlan lancam", null, "c84_slip = $iCodSlip and k17_situacao in (1, 4)");
  $resultSlip = db_utils::fieldsMemory($result = db_query($sSqlSlip),0);
  $sql = "select fc_removeslip($iCodSlip, $resultSlip->ano, $resultSlip->k17_instit)";

      /*
       * exclus�o movimenta��o caixa/tesouraria
       */
      db_inicio_transacao();

      $sqlExcluirautentslip = "create temporary table w_chaveslip on commit drop as select c86_id as id,c86_data as data,c86_autent as autent from conlancamcorrente inner join conlancamslip on c84_conlancam = c86_conlancam inner join slip on k17_codigo = c84_slip where k17_codigo = $iCodSlip;
        delete from conlancamcorrente using w_chaveslip where c86_id = id and c86_data = data and c86_autent = autent;
        delete from corlanc using w_chaveslip where k12_id = id and k12_data = data and k12_autent = autent;
        delete from corautent using w_chaveslip where k12_id = id and k12_data = data and k12_autent = autent;
        delete from corrente using w_chaveslip where k12_id = id and k12_data = data and k12_autent = autent;";

      $sqlExcluirautentslip = db_query($sqlExcluirautentslip);

      /*
       * exclus�o lancamentos contabeis vinculados ao slip
       */

      $rsCodLan = db_query("select c84_conlancam from conlancamslip WHERE c84_slip = $iCodSlip");

      $aCodLan = array();

      for ($iContLan=0;$iContLan < pg_num_rows($rsCodLan); $iContLan++) { 
        $aCodLan[] = db_utils::fieldsMemory($rsCodLan, $iContLan)->c84_conlancam;
      }

      $oDaoExcluirSlip = new cl_conlancamcgm();
      $oDaoExcluirSlip->excluir(null, "c76_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancamcgm");
      }

      $oDaoExcluirSlip = new cl_conlancamdoc();
      $oDaoExcluirSlip->excluir(null, "c71_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancamdoc");
      }

      $oDaoExcluirSlip = new cl_conlancamdoc();
      $oDaoExcluirSlip->excluir(null, "c71_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancamdoc");
      }

      $oDaoExcluirSlip = new cl_conlancamcompl();
      $oDaoExcluirSlip->excluir(null, "c72_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancamcompl");
      }

      $oDaoExcluirSlip = new cl_conlancampag();
      $oDaoExcluirSlip->excluir(null, "c82_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancampag");
      }

      $oDaoExcluirSlip = new cl_conlancaminstit();
      $oDaoExcluirSlip->excluir(null, "c02_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancaminstit");
      }

      $oDaoExcluirSlip = new cl_conlancamordem();
      $oDaoExcluirSlip->excluir(null, "c03_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancamordem");
      }

      $oDaoExcluirSlip = new cl_contacorrentedetalheconlancamval();
      $oDaoExcluirSlip->excluir(null, "c28_conlancamval IN (SELECT c69_sequen FROM conlancamval WHERE c69_codlan IN (". implode(",", $aCodLan). "))");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("Erro ao excluir Slip. Verificar encerramento do periodo contabil");
      }

      $oDaoExcluirSlip = new cl_conlancamval();
      $oDaoExcluirSlip->excluir(null, "c69_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("Erro ao excluir Slip. Verificar encerramento do periodo contabil");
      }

      $oDaoExcluirSlip = new cl_conlancamslip();
      $oDaoExcluirSlip->excluir(null, "c84_conlancam IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancamslip");
      }

      $oDaoExcluirSlip = new cl_conlancam();
      $oDaoExcluirSlip->excluir(null, "c70_codlan IN (". implode(",", $aCodLan). ")");
      if ($oDaoExcluirSlip->erro_status == "0") {
        throw new BusinessException("financeiro.caixa.Transferencia.exclusao_conlancam");
      }


  $rsResultFcRemoveSlip = db_query($sql);

  $sRetorno = db_utils::fieldsMemory($rsResultFcRemoveSlip, 0);

  db_fim_transacao($sqlerro);

  if($sRetorno == 1) {
    return true;
  } else {
    throw new BusinessException("financeiro.caixa.Transferencia.exclusao_function_db");
    return false;
  }

}

  /**
   * @param $sProcesso
   */
  public function setProcessoAdministrativo($sProcesso) {
    $this->sProcessoAdministrativo = $sProcesso;
  }

  /**
   * @return string
   */
  public function getProcessoAdministrativo() {
    return $this->sProcessoAdministrativo;
  }

  /**
   * Vincula um processo administrativo a um slip
   * @return bool
   * @throws BusinessException
   */
  protected function salvarVinculoComProcesso() {

    $this->excluirVinculoComProcesso();

    if (empty($this->sProcessoAdministrativo)) {
      return false;
    }

    $oDaoSlipProcesso                      = new cl_slipprocesso();
    $oDaoSlipProcesso->k145_sequencial     = null;
    $oDaoSlipProcesso->k145_slip           = $this->getCodigoSlip();
    $oDaoSlipProcesso->k145_numeroprocesso = $this->getProcessoAdministrativo();
    $oDaoSlipProcesso->incluir(null);
    if ($oDaoSlipProcesso->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.vinculo_processo");
    }
    return true;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  protected function excluirVinculoComProcesso() {

    $oDaoSlipProcesso = new cl_slipprocesso();
    $oDaoSlipProcesso->excluir(null, "k145_slip = {$this->getCodigoSlip()}");
    if ($oDaoSlipProcesso->erro_status == "0") {
      throw new BusinessException("financeiro.caixa.Transferencia.exclusao_processo");
    }
    return true;
  }


  /**
   * Executa o lan�amento na contabilidade com os dados autenticados na tesouraria
   * @param AutenticacaoTesouraria $oAutenticacao
   * @return bool
   */
  public function executarLancamentoContabilidade (AutenticacaoTesouraria $oAutenticacao) {

    $oContaCorrenteDetalhe = new ContaCorrenteDetalhe();
    $oContaCorrenteDetalhe->setRecurso(new Recurso($oAutenticacao->getContaPagadora()->getRecurso()));
    if ($this->getFonteRecursos() != null) 
        $oContaCorrenteDetalhe->setRecurso($this->getFonteRecursos());
    $oContaCorrenteDetalhe->setContaBancaria(null);
    $oContaCorrenteDetalhe->setCredor(CgmFactory::getInstanceByCgm($this->getCodigoCgm()));

    $oLancamentoAuxiliarSlip = new LancamentoAuxiliarSlip();
    $oLancamentoAuxiliarSlip->setIDTerminal($oAutenticacao->getTerminal());
    $oLancamentoAuxiliarSlip->setDataAutenticacao($oAutenticacao->getData()->getDate());
    $oLancamentoAuxiliarSlip->setNumeroAutenticacao($oAutenticacao->getAutenticacao());
    $oLancamentoAuxiliarSlip->setHistorico($this->getHistorico());
    $oLancamentoAuxiliarSlip->setValorTotal(abs($oAutenticacao->getValor()));
    $oLancamentoAuxiliarSlip->setObservacaoHistorico($this->getObservacao());
    $oLancamentoAuxiliarSlip->setCodigoSlip($this->getCodigoSlip());
    $oLancamentoAuxiliarSlip->setCodigoReduzido($oAutenticacao->getContaPagadora()->getReduzido());
    $oLancamentoAuxiliarSlip->setFavorecido($this->getCodigoCgm());
    $oLancamentoAuxiliarSlip->setEstorno(false);
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarCredito($this->getCaracteristicaPeculiarCredito());
    $oLancamentoAuxiliarSlip->setCaracteristicaPeculiarDebito($this->getCaracteristicaPeculiarDebito());
    $oLancamentoAuxiliarSlip->setContaCorrenteDetalhe($oContaCorrenteDetalhe);

    $iCodigoDocumento = $this->getDocumentoPorTipoInclusao();
    $oEventoContabil  = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, $oAutenticacao->getData()->getAno(), $this->getInstituicao());
    if ($oEventoContabil->estorno()) {
      $oLancamentoAuxiliarSlip->setEstorno(true);
    }
    $oEventoContabil->executaLancamento($oLancamentoAuxiliarSlip, $oAutenticacao->getData()->getDate());
    $this->iCodigoLancamento =  $oEventoContabil->getCodigoLancamento();
    return true;
  }

    /**
     * Permite definir a fonte de recursos
     * @param iFonteRecursos
    */
    public function setFonteRecurso($iFonteRecurso) {
        $this->iFonteRecurso = new Recurso($iFonteRecurso);
    }

    public function getFonteRecursos() {
        if ($this->getCodigoSlip() != null)
            foreach ($this->oSlip->getRecursos() as $iRecurso => $nValor)
                return new Recurso($iRecurso);
        return $this->iFonteRecurso;
    }
}
