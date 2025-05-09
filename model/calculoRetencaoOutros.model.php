<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


require_once ('interfaces/calculoRetencao.interface.php');

class calculoRetencaoOutros implements iCalculoRetencao {
  
  
   /* tipo do calculo
   * @var integer
   */
  private $iTipo = 1;
  
  /**
   * Valor da base de calculo
   *
   * @var float
   */
  private $nValorBaseCalculo = 0;
  
  /**
   * Valor da Dedu��o informado pelo usu�rio.
   *
   * @var float
   */ 
  private $nValorDeducao = 0;
  
  /**
   * cpf ou cnpj a calcular o imposto.
   *
   * @var integer
   */
  private $iCgcCpf = null;
  
  /**
   * Tabela do IRRF a ser usada
   *
   * @var object
   */
  private $oTabelaIrrf = null;
  
  /**
   * Valor da Aliquota
   *
   * @var float
   */
  private $nAliquota  = 0;
  
  /**
   * Valor a ser adicionado da nota
   *
   * @var float
   */
  private $nValorNota= 0;
  
  /**
   * C�digos Auxiliares doe Movimentos 
   *
   * @var array
   */
  private $aCodigoMovimentos = array();
  
  /**
   * metodo construtor da classe
   * @param integer $iTipo tipo do calculo 1 - pessoa fisica
   */
  function __construct($iCgcCpf, $iTipo = 1) {

    $this->iTipo   = 1;
    $this->iCgcCpf = (string)$iCgcCpf;
    
  }
  
  /**
   * 
   * @see iCalculoRetencao::calculaBasedeCalculo()
   */
  function calculaBasedeCalculo() {

    $nValorBaseCalculo = 0;
    if (empty($this->iCgcCpf)) {
      throw new Exception("Erro [2] CPF/CNPJ n�o Informado!\nOpera��o cancelada");
    }
    if (empty($this->nValorBaseCalculo) || $this->nValorBaseCalculo == 0) {
      throw new Exception("Erro [4] Valor da Base de calculo n�o informado!\nOpera��o cancelada");
    }
    $nValorBaseCalculo = $this->nValorBaseCalculo - $this->nValorDeducao;
    if ($nValorBaseCalculo < 0) {
      $nValorBaseCalculo = 0;
    }
    $this->nValorBaseCalculo = $nValorBaseCalculo;
    return $nValorBaseCalculo;
  }
  
  /**
   * 
   * @see iCalculoRetencao::calcularRetencao()
   */
  function calcularRetencao() {
    
    $this->nValorBaseCalculo = $this->calculaBasedeCalculo();
    
    $nValorRetido = $this->nValorBaseCalculo * ($this->nAliquota/100);
    return $nValorRetido;
     
  }
  
  /**
   * 
   * @see iCalculoRetencao::getAliquota()
   */
  function getAliquota() {
    return $this->nAliquota;
  }
  
  /**
   * 
   * @see iCalculoRetencao::getValorBaseCalculo()
   */
  function getValorBaseCalculo() {
    return $this->nValorBaseCalculo;
  }
  
  /**
   * 
   * @param float $nValorAliquota 
   * @see iCalculoRetencao::setAliquota()
   */
  function setAliquota($nValorAliquota) {
    $this->nAliquota = $nValorAliquota;
  }
  
  /**
   * @param $nValorDeducao 
   * @see iCalculoRetencao::setDeducao()
   */
  function setDeducao($nValorDeducao) {
    $this->nValorDeducao = $nValorDeducao;
  }
  
  /**
   * Seta o valor da base de calculo
   *
   * @param float8 $nValorBaseCalculo valor da base de calculo
   */
  function setBaseCalculo($nValorBaseCalculo) {
    $this->nValorBaseCalculo = $nValorBaseCalculo;
  }
  /**
   * Define o valor da nota
   *
   * @param float $nValorNota valor da nota a ser contabilizado na retencao.
   */
  function setValorNota($nValorNota) {
    
    $this->nValorNota = $nValorNota;
  }
  
  /**
   * Define a data base para calculo das retencoes;
   *
   * @param string $dtDataBase data base para caculo formato dd/mm/YYY
   */
  function setDataBase($dtDataBase) {
    
    $dtDataBase = implode("-", array_reverse(explode("/", $dtDataBase)));
    $this->dtBaseCalculo = $dtDataBase;
    
  }
  
  /**
   * Define  o Codigo dos Movimentos
   *
   * @param unknown_type $aCodigosMovimentos
   */
  function setCodigoMovimentos($aCodigosMovimentos) {
     $this->aCodigoMovimentos = $aCodigosMovimentos;  
  }
}

?>