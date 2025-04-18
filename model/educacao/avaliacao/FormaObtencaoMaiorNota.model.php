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
 * Calculo: Maior Nota.
 * Retorna a maior nota dentro de um conjunto de avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.13 $
 */
require_once("model/educacao/avaliacao/FormaObtencao.model.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
class FormaObtencaoMaiorNota extends FormaObtencao implements IFormaObtencao {

  /**
   * Define as notas que ira ser usaddo no calculo
   * Dever� ser instancias de AvaliacaoAproveitamento
   * @param array   $aAproveitamentos
   * @param integer $iAno
   * @return ValorAproveitamentoNota
   */
  public function processarResultado( $aAproveitamentos, $iAno ) {

    /**
     * Verificamos a maior nota entre os Aproveitamentos
     */
    $mAproveitamento     = new ValorAproveitamentoNota('');
    $aNotasPeriodos      = $this->getElementosParaCalculo( $aAproveitamentos, $iAno );
    $temNotaComValorZero = false;
    $aElementos          = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();
    foreach ($aNotasPeriodos as $oNotaDoAproveitamento) {

      if ($oNotaDoAproveitamento->isAmparado()) {
        continue;
      }

      $oElemento              = $aElementos[$oNotaDoAproveitamento->getOrdemSequencia()];
      $nAproveitamentoPeriodo = $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento();
      if (!$this->isCalculoNotaParcial() && $oElemento->isObrigatorio() &&  $nAproveitamentoPeriodo === "") {

        $mAproveitamento = new ValorAproveitamentoNota('');
        break;
      }
      if ($oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento() === 0) {
        $temNotaComValorZero = true;
      }
      if( $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento() > $mAproveitamento->getAproveitamento() ) {
        $mAproveitamento = $oNotaDoAproveitamento->getValorAproveitamento();
      }
    }

    $mAproveitamento = ArredondamentoNota::arredondar( $mAproveitamento, $iAno );

    if ($temNotaComValorZero && $mAproveitamento->getAproveitamento() == "") {
      $mAproveitamento->setAproveitamento(0);
    }

    /**
     * Devolvemos as notas Originais
     */
    $this->acertaNotasSubstituidasParaCalculo($aNotasPeriodos);
    return $mAproveitamento;
  }
}