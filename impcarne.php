<?
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

//include("assinatura.php");
//$classinatura = new cl_assinatura;

// MODELO 1  - CARNES DE PARCELAMENTO
// MODELO 2  - RECIBO DE PAGAMENTO ( 2 VIAS )
// MODELO 3  - ALVAR�
// MODELO 4  - BOLETO
// MODELO 5  - AUTORIZA��O DE EMPENHO
// MODELO 6  - NOTA DE EMPENHO
// MODELO 7  - ORDEM DE PAGAMENTO
// MODELO 8  - FICHA DE TRANSFERENCIA DE BENS
// MODELO 10 - ORDEM DE COMPRA
// MODELO 11 - SOLICITA��O DE COMPRA
// MODELO 22 - RECIBO DE PAGAMENTO ( 1 VIAS )

class db_impcarne extends cl_assinatura {


/////   VARI�VEIS PARA EMISSAO DE CARNES DE PARCELAMENTO - MODELO 1

  var $modelo    = 1;

  var $qtdcarne  = null;
  var $tipodebito= 'TIPO DE D�BITO';
  var $tipoinscr1= null;
  var $prefeitura= 'PREFEITURA DBSELLER';
  var $secretaria= 'SECRETARIA DE FAZENDA';
  var $debito    = null;
  var $logo      = null;
  var $parcela   = null;
  var $titulo1   = '';
  var $descr1    = null;
  var $titulo2   = 'C�d de Arrecada��o';
  var $descr2    = null;
  var $titulo3   = 'Contribuinte/Endere�o';
  var $descr3_1  = null;
  var $descr3_2  = null;
  var $titulo4   = 'Instru��es';
  var $descr4_1  = null;
  var $descr4_2  = null;
  var $titulo5   = 'Parcela';
  var $descr5    = null;
  var $titulo6   = 'Vencimento';
  var $descr6    = null;
  var $titulo7   = 'Valor';
  var $descr7    = null;
  var $titulo8   = '';
  var $descr8    = null;
  var $titulo9   = 'C�d. de Arrecada��o';
  var $descr9    = null;
  var $titulo10  = 'Parcela';
  var $descr10   = null;
  var $titulo11  = 'Contribuinte/Endere�o';
  var $descr11_1 = null;
  var $descr11_2 = null;
  var $titulo12  = 'Instru��es';
  var $descr12_1 = null;
  var $descr12_2 = null;
  var $titulo13  = '';
  var $descr13   = null;
  var $titulo14  = 'Vencimento';
  var $descr14   = null;
  var $texto     = null;
  var $titulo15  = 'Valor';
  var $descr15   = null;
  var $descr16_1 = null;
  var $descr16_2 = null;
  var $descr16_3 = null;
  var $linha_digitavel = null;
  var $codigo_barras = null;
  var $objpdf = null;

//////  VARI�VEIS PARA EMISSAO DE RECIBO DE PAGAMENTO - MODELO 2

  var $enderpref = null;
  var $tipocompl = null;
  var $tipolograd= null;
  var $tipobairro= null;
  var $municpref = null;
  var $telefpref = null;
  var $emailpref = null;
  var $nome      = null;
  var $ender     = null;
  var $compl     = null;
  var $munic     = null;
  var $uf        = null;
  var $cep       = null;
  var $tipoinscr = 'Matr/Inscr';
  var $nrinscr   = null;
  var $nrinscr1  = null;
  var $ip        = null;
  var $nomepri   = '';
  var $nrpri     = '';
  var $complpri  = '';
  var $bairropri = null;
  var $datacalc  = null;
  var $taxabanc  = 0;
  var $rowspagto = 0;
  var $receita   = null;
  var $receitared= null;
  var $dreceita  = null;
  var $ddreceita = null;
  var $valor     = null;
  var $historico = null;
  var $histparcel= null;
  var $recorddadospagto = 0;
  var $linhasdadospagto = 0;
  var $dtvenc    = null;
  var $numpre    = null;
  var $valtotal  = null;

//////  VARI�VEIS PARA EMISSAO DE ALVAR�

  var $tipoalvara  = null;
  var $ativ        = null;
  var $descrativ   = null;
  var $outrasativs = null;
  var $q02_memo    = null;
  var $numero      = null;
  var $q02_obs     = null;
  var $q03_atmemo  = null; //obs das atividades
  var $processo    = null;
  var $datainc     = null;
  var $datafim     = null;
  var $cnpjcpf     = null;

//////  FICHA DE COMPENSACAO

  var $numbanco		= '';
  var $localpagamento   = '';
  var $cedente		= '';
  var $agencia_cedente	= '';
  var $data_documento	= '';
  var $numero_documento = '';
  var $especie_doc	= '';
  var $aceite		= '';
  var $data_processamento = '';
  var $nosso_numero	= '';
  var $codigo_cedente	= '';
  var $carteira		= '';
  var $especie		= '';
  var $quantidade	= '';
  var $valor_documento	= '';
  var $instrucoes1	= '';
  var $instrucoes2	= '';
  var $instrucoes3	= '';
  var $instrucoes4	= '';
  var $instrucoes5	= '';
  var $desconto_abatimento = '';
  var $outras_deducoes	= '';
  var $mora_multa	= '';
  var $outros_acrecimos	= '';
  var $valor_cobrado	= '';
  var $sacado1		= '';
  var $sacado2		= '';
  var $sacado3		= '';

//// variaveis para a solicita��o de compras

  var $pc10_numero      = null;         // numero da solicita��o
  var $descrdepto       = '';           // descri��o do departamento
  var $pc50_descr       = '';           // descri��o do tipo de compra
  var $pc10_data        = null;         // data da solicita��o
  var $pc10_vlrap       = null;         // valor da solicita��o
  var $pc10_resumo      = '';
  var $pc11_prazo       = '';
  var $pc11_pgto        = '';
  var $pc11_resum       = '';
  var $pc11_just        = '';
  var $pc13_coddot      = '';
  var $pc13_quant       = '';
  var $pc13_valor       = '';
  var $valtot           = null;         // valor total dos itens lancados
  var $recorddasdotac 	= null;		// record set das dotacoes
  var $linhasdasdotac 	= null;		// numero de dotacoes
  var $coddotac         = null;         // codigo da dotacao
  var $quantdotac       = null;         // quantidade de itens na dotacao
  var $valdotac         = null;         // valor da dotacao

//// variaveis para a autoriza��o de empenho E ORDEM DE COMPRA

  var $ano		= null;		// ano
  var $numaut 		= null;  	// numero do empenho
  var $numsol 		= null;  	// numero do empenho
  var $numemp 		= null;  	// numero do empenho
  var $codemp 		= null;  	// numero do empenho do ano
  var $emissao 		= null;  	// data da emissao
  var $orgao 		= null;  	// data da emissao
  var $descr_orgao	= null;  	// data da emissao
  var $unidade 		= null;  	// data da emissao
  var $descr_unidade	= null;  	// data da emissao
  var $funcao 		= null;  	// data da emissao
  var $descr_funcao	= null;  	// data da emissao
  var $projativ		= null;  	// data da emissao
  var $descr_projativ	= null;  	// data da emissao
  var $sintetico	= null;  	// data da emissao
  var $descr_sintetico	= null;  	// data da emissao
  var $recurso   	= null;  	// data da emissao
  var $descr_recurso    = null;  	// data da emissao
  var $orcado		= null;  	// data da emissao
  var $saldo_ant	= null;  	// data da emissao
  var $empenhado	= null;  	// data da emissao
  var $numcgm 		= null;		// cgm do fornecedor
//  var $nome   		= null;		// nome do fornecedor
//  var $ender  		= null;		// endere�o do fornecedor
//  var $munic  		= null;		// municipio do fornecedor
  var $dotacao 		= null;		// dotacao orcamentaria (orgao,unidade,funcao,subfuncao,programa,projativ,elemento,recurso)
  var $descrdotacao 	= null;		// descricao da dotacao
  var $coddot		= null;		// codigo reduzido da despesa
  var $destino		= null;		// destino do material ou servi�o
  var $resumo		= null;		// destino do material ou servi�o
  var $licitacao  	= null;		// tipo de licita��o
  var $num_licitacao  	= null;		// numero da licita��o
  var $descr_licitacao 	= null;		// descri��o do tipo de licita��o
  var $descr_tipocompra	= null;		// descri��o do tipo de compra
  var $prazo_ent  	= null;		// prazo de entrega
  var $obs		= null;		// observa��es
  var $cond_pag		= null;		// condi��es de pagamento
  var $out_cond		= null;		// outras condi��es de pagamento
  var $contato		= null;		// contato
  var $telef_cont 	= null;		// telefone do contato
  var $recorddositens 	= null;		// record set dos itens
  var $linhasdositens 	= null;		// numero de itens da autorizacao
  var $item	    	= null;		// codigo do item
  var $quantitem    	= null;		// quantidade do item
  var $valoritem    	= null;		// valor unit�rio do item
  var $empempenho       = null;         // cod empenho para emiss�o de ordem de compra
  var $dataordem        = null;         // data da gera��o da ordem de compra
  var $observacaoitem   = null;
  var $descricaoitem    = null;
  var $ordpag		= null;		// numero da ordem de pagamento
  var $elemento		= null;		// elemento da despesa
  var $descr_elemento	= null;		// descri��o do elemento da despesa
  var $elementoitem	= null;		// elemento do item da ordem de pagamento
  var $descr_elementoitem= null;	// descri��o do elemento do item da ordem de pagamento
  var $outrasordens     = null;		// saldo das outras ordens de pagamento do empenho
  var $vlrrec           = null;		// valor das receitas de reten�oes
  var $cnpj             = null;         // cpf ou cnpj do credor

/// variaveis para a nota de empenho
  function db_impcarne($objpdf){
    $this->objpdf = $objpdf;


  }
  function muda_pag($pagina,$xlin,$xcol){
    $x = false;
    if(($this->objpdf->gety() > $this->objpdf->h - 78 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h-30 && $pagina != 1)){
      if($this->objpdf->PageNo() == 1){
        $this->objpdf->text(111.2,$xlin+214,'Continua na P�gina '.($this->objpdf->PageNo()+1));
        // $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');
        $this->objpdf->rect($xcol,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->rect($xcol+68,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
        $y = 260;
        $this->objpdf->SetXY(2,$y);
        $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'DIRETOR DE COMPRAS',0,"C",0);

        $this->objpdf->SetXY(72,$y);
        $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'SECRETARIA MUNICIPAL',0,"C",0);

        $this->objpdf->SetXY(142,$y);
        $this->objpdf->MultiCell(70,4,'VISTO'."\n\n\n".'',0,"C",0);

        $this->objpdf->setfillcolor(0,0,0);
        $this->objpdf->text($xcol+5,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
        $this->objpdf->SetFont('Arial','',4);
        $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
        $this->objpdf->setfont('Arial','',11);
      }else{
        $this->objpdf->text(112.5,$xlin+271,'Continua na P�gina '.($this->objpdf->PageNo()+1));
      }
      $this->objpdf->addpage();
      $pagina += 1;
      $muda_pag = true;

      $this->objpdf->settopmargin(1);
      $xlin = 20;
      $xcol = 4;

      // Imprime cabe�alho com dados sobre a prefeitura se mudar de p�gina
      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(130,$xlin-13,'SOLICITA��O DE COMPRA N'.CHR(176));
      $this->objpdf->text(185,$xlin-13,db_formatar($this->pc10_numero,'s','0',6,'e'));
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin-8,$this->municpref);
      $this->objpdf->text(40,$xlin-5,$this->telefpref);
      $this->objpdf->text(40,$xlin-2,$this->emailpref);
//	    $this->objpdf->text(40,$xlin+2,'Continua��o da P�gina '.($this->objpdf->PageNo()-1));
      $this->objpdf->text(130,$xlin+2,'P�gina '.$this->objpdf->PageNo());

      $xlin = 0;
      $this->objpdf->Setfont('Arial','B',8);

      // Caixa de texto para labels
      $this->objpdf->rect($xcol    ,$xlin+24,15,6,2,'DF','12');
      $this->objpdf->rect($xcol+15 ,$xlin+24,20,6,2,'DF','12');
      $this->objpdf->rect($xcol+35 ,$xlin+24,107,6,2,'DF','12');
      $this->objpdf->rect($xcol+142,$xlin+24,30,6,2,'DF','12');
      $this->objpdf->rect($xcol+172,$xlin+24,30,6,2,'DF','12');

      $this->objpdf->rect($xcol    ,$xlin+30,15,262,2,'DF','34');
      $this->objpdf->rect($xcol+15 ,$xlin+30,20,262,2,'DF','34');
      $this->objpdf->rect($xcol+35 ,$xlin+30,107,262,2,'DF','34');
      $this->objpdf->rect($xcol+142,$xlin+30,30,262,2,'DF','34');
      $this->objpdf->rect($xcol+172,$xlin+30,30,262,2,'DF','34');

      $this->objpdf->sety($xlin+66);
      $alt = 4;

      //Label das colunas
      $this->objpdf->text($xcol+   4,$xlin+28,'ITEM');
      $this->objpdf->text($xcol+15.5,$xlin+28,'QUANTIDADE');
      $this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVI�O');
      $this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNIT�RIO');
      $this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');

      $maiscol = 0;
      $xlin = 20;
      // Seta altura nova para impress�o dos dados
      $this->objpdf->sety($xlin+11);
      $x = true;
    }
    return $x;
  }

  function imprime() {
    if($this->modelo == 1){
      if ( ($this->qtdcarne % 4 ) == 0 ){
        $this->objpdf->AddPage();
      }
      $this->objpdf->SetLineWidth(0.05);
      $this->qtdcarne += 1;
      $top = $this->objpdf->GetY()-5;
      $this->objpdf->SetFont('Arial','B',8);
      $this->objpdf->SetTextColor(0,0,0);
      $this->objpdf->SetFillColor(250,250,250);
      $this->objpdf->SetX(17);
      $this->objpdf->Text(17,$top,$this->prefeitura,0,0,"L",0);
      $this->objpdf->SetX(105);
      $this->objpdf->Text(105,$top,$this->prefeitura,0,1,"L",0);
      $this->objpdf->SetX(170);
      $this->objpdf->SetX(17);
      $this->objpdf->SetFont('Arial','',7);
      $this->objpdf->Text(17,$top+3,$this->secretaria,0,0,"L",0);
      $this->objpdf->SetX(105);
      $this->objpdf->Text(105,$top+3,$this->secretaria,0,1,"L",0);
      $this->objpdf->Ln(2);
      $this->objpdf->SetFont('Arial','B',8);
      $this->objpdf->SetX(10);
      $this->objpdf->Cell(90,4,$this->tipodebito,0,0,"C",0);
      $this->objpdf->SetX(105);
      $this->objpdf->Cell(90,4,$this->tipodebito,0,1,"C",0);
      $y = $this->objpdf->GetY()-1;
      $this->objpdf->Image('imagens/files/'.$this->logo,8,$y-14,8);
      $this->objpdf->Image('imagens/files/'.$this->logo,95,$y-14,8);
      $this->objpdf->SetFont('Times','',5);
      $this->objpdf->RoundedRect(10,$y+1,32,6,2,'DF','1234'); // matricula/ inscri��o
      $this->objpdf->RoundedRect(43,$y+1,27,6,2,'DF','1234'); // cod. de arrecada��o
      $this->objpdf->RoundedRect(71,$y+1,20,6,2,'DF','1234'); // parcela

      $this->objpdf->RoundedRect(10,$y+8,81,12,2,'DF','1234'); // nome / endere�o

      $this->objpdf->RoundedRect(10,$y+21,81,14,2,'DF','1234'); // instru�oes

      $this->objpdf->RoundedRect(10,$y+36,39,7,2,'DF','1234'); // vencimento
      $this->objpdf->RoundedRect(50,$y+36,41,7,2,'DF','1234'); // valor

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(13,$y+3,$this->titulo1); // matricula/ inscri��o
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(13,$y+6,$this->descr1); // numero da matricula ou inscricao

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(45,$y+3,$this->titulo2); // cod. de arrecada��o
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(47,$y+6,$this->descr2); // numpre

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(73,$y+3,$this->titulo5); // Parcela
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(76,$y+6,$this->descr5); // Parcela inicial e total de parcelas

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(13,$y+10,$this->titulo3); // contribuinte/endere�o
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(13,$y+13,$this->descr3_1); // nome do contribuinte
      $this->objpdf->Text(13,$y+16,$this->descr3_2); // endere�o

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(13,$y+23,$this->titulo4); // Instru��es
      $this->objpdf->SetFont('Arial','B',7);
      $xx = $this->objpdf->getx();
      $yy = $this->objpdf->gety();
      $this->objpdf->setleftmargin(10);
      $this->objpdf->setrightmargin(120);
      $this->objpdf->sety($y+23);
      $this->objpdf->multicell(0,3,$this->descr4_1); // Instru��es 1 - linha 1
//        $this->objpdf->multicell(0,3,$this->descr4_2); // Instru��es 1 - linha 2
      $this->objpdf->setxy($xx,$yy);

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(13,$y+38,$this->titulo6); // Vencimento
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(20,$y+41,$this->descr6); // Data de Vencimento

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(53,$y+38,$this->titulo7); // valor
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(56,$y+41,$this->descr7); // qtd de URM ou valor


      $this->objpdf->RoundedRect(95,$y+1,33,6,2,'DF','1234'); // matricula / inscricao
      $this->objpdf->RoundedRect(129,$y+1,27,6,2,'DF','1234'); // cod. arrecadacao
      $this->objpdf->RoundedRect(157,$y+1,20,6,2,'DF','1234'); // parcela
      $this->objpdf->RoundedRect(178,$y+1,31,6,2,'DF','1234'); // livre

      $this->objpdf->RoundedRect(95,$y+8,82,13,2,'DF','1234'); // nome / endereco
      $this->objpdf->RoundedRect(95,$y+22,114,13,2,'DF','1234'); // instrucoes

      $this->objpdf->RoundedRect(178,$y+8,31,6,2,'DF','1234'); // vencimento
      $this->objpdf->RoundedRect(178,$y+15,31,6,2,'DF','1234'); // valor


      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(97,$y+3,$this->titulo8); // matricula / inscricao
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(97,$y+6,$this->descr8); // numero da matricula ou inscricao

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(131,$y+3,$this->titulo9); // cod. de arrecada��o
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(133,$y+6,$this->descr9); // numpre

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(159,$y+3,$this->titulo10); // parcela
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(162,$y+6,$this->descr10); // parcela e total das parcelas

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(180,$y+3,$this->titulo13); // livre
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(183,$y+6,$this->descr13); // livre

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(97,$y+10,$this->titulo11); // contribuinte / endere�o
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(97,$y+13,$this->descr11_1); // nome do contribuinte
      $this->objpdf->Text(97,$y+16,$this->descr11_2); // endere�o

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(97,$y+24,$this->titulo12); // instru��es
      $this->objpdf->SetFont('Arial','B',7);
      $xx = $this->objpdf->getx();
      $yy = $this->objpdf->gety();
      $this->objpdf->setleftmargin(97);
      $this->objpdf->setrightmargin(2);
      $this->objpdf->sety($y+24);
      $this->objpdf->multicell(0,3,$this->descr12_1); // Instru��es 2 - linha 1
      $this->objpdf->multicell(0,3,$this->descr12_2); // Instru��es 2 - linha 2
      $this->objpdf->setxy($xx,$yy);

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(180,$y+10,$this->titulo14); // vencimento
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(180,$y+13,$this->descr14); // data de vencimento

      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->Text(180,$y+17,$this->titulo15); // valor
      $this->objpdf->SetFont('Arial','B',7);
      $this->objpdf->Text(180,$y+20,$this->descr15); // total de URM ou valor

      $this->objpdf->SetLineWidth(0.05);
      $this->objpdf->SetDash(1,1);
//        $this->objpdf->Line(5,$y+58,205,$y+58); // linha tracejada horizontal
      $this->objpdf->Line(93,$y-30,93,$y+60); // linha tracejada vertical
      $this->objpdf->SetDash();
      $this->objpdf->Ln(70);
      $this->objpdf->SetFillColor(0,0,0);
      $this->objpdf->SetFont('Arial','',10);

      $this->objpdf->SetFont('Arial','',4);
      $this->objpdf->TextWithDirection(2,$y+30,$this->texto,'U'); // texto no canhoto do carne

      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text(10,$y+46,$this->descr16_1); //
      $this->objpdf->Text(10,$y+50,$this->descr16_2); //
      $this->objpdf->Text(10,$y+54,$this->descr16_3); //
      $this->objpdf->Text(105,$y+38,$this->linha_digitavel);
      $this->objpdf->int25(95,$y+39,$this->codigo_barras,15,0.341);
      /*
              $y = $this->objpdf->gety();
              $this->objpdf->setleftmargin(10);
              $this->objpdf->setrightmargin(120);
              $this->objpdf->sety($y+28);
              $this->objpdf->multicell(0,3,$this->descr4_1); // Instru��es 1 - linha 1
              $this->objpdf->ln(39.5);
      */
    }else if ( $this->modelo == 22 ) {

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->Setfont('Arial','B',12);
      $this->objpdf->setfillcolor(245);
      $this->objpdf->roundedrect(05,05,200,288,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
      $this->objpdf->Image('imagens/files/'.$this->logo,45,9,20);
      $this->objpdf->text(70,15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',12);
      $this->objpdf->text(70,20,$this->enderpref);
      $this->objpdf->text(70,25,$this->municpref);
      $this->objpdf->text(70,30,$this->telefpref);
      $this->objpdf->text(70,35,$this->emailpref);

      $this->objpdf->setfillcolor(245);
      $this->objpdf->Roundedrect(15,45,110,35,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text(16,47,'Identifica��o:');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text(16,51,'Nome :');
      $this->objpdf->text(32,51,$this->nome);
      $this->objpdf->text(16,56,'Endere�o :');
      $this->objpdf->text(32,56,$this->ender);
      $this->objpdf->text(16,60,'Munic�pio :');
      $this->objpdf->text(32,60,$this->munic);
      $this->objpdf->text(16,64,'CEP :');
      $this->objpdf->text(32,64,$this->cep);
      $this->objpdf->text(16,68,'Data :');
      $this->objpdf->text(32,68,date('d/m/Y'));
      $this->objpdf->text(50,68,'Hora: '.date("H:i:s"));
      $this->objpdf->text(16,72,$this->tipoinscr);
      $this->objpdf->text(32,72,$this->nrinscr);
      $this->objpdf->text(16,76,'IP :');
      $this->objpdf->text(32,76,$this->ip);
      $this->objpdf->Setfont('Arial','',6);

      $this->objpdf->Roundedrect(130,45,65,35,2,'DF','1234');
      $this->objpdf->text(132,47,$this->tipoinscr);
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text(132,50,$this->nrinscr);
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text(132,55,'Logradouro :');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text(132,58,$this->nomepri);
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text(132,63,'N�mero/Complemento :');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text(132,66,$this->nrpri."      ".$this->complpri);
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text(132,71,'Bairro :');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text(132,74,$this->bairropri);

      $this->objpdf->Setfont('Arial','B',11);
      $this->objpdf->text(70,87,'DAM V�LIDO AT�: '.$this->datacalc);

      $this->objpdf->setfillcolor(245);
      $this->objpdf->Roundedrect(15,90,180,65,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',8);

      $this->objpdf->SetXY(17,96);
      if($this->taxabanc!=0){
        $this->objpdf->Cell(20,4,'Taxa Banc�ria',0,0,"L",0);
        $this->objpdf->Cell(20,4,db_formatar($this->taxabanc,'f'),0,1,"R",0);
      }

      for($i = 0;$i < $this->linhasdadospagto ;$i++) {
        $this->objpdf->setx(17);
        $this->objpdf->cell(5,4,trim(pg_result($this->recorddadospagto,$i,$this->receita)),0,0,"C",0);
        if ( trim(pg_result($this->recorddadospagto,$i,$this->ddreceita) ) == ''){
          $this->objpdf->cell(70,4,trim(pg_result($this->recorddadospagto,$i,$this->dreceita)),0,0,"L",0);
        }else{
          $this->objpdf->cell(70,4,trim(pg_result($this->recorddadospagto,$i,$this->ddreceita)),0,0,"L",0);
        }
        $this->objpdf->cell(15,4,db_formatar(pg_result($this->recorddadospagto,$i,$this->valor),'f'),0,1,"R",0);
      }
      $this->objpdf->SetXY(15,158);
      $this->objpdf->multicell(0,4,'HIST�RICO :   '.$this->historico);
      $this->objpdf->setx(15);
      $this->objpdf->multicell(0,4,$this->histparcel);
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Roundedrect(10,195,190,46,2,'DF','1234');

      $this->objpdf->setfont('Arial','',6);
      $this->objpdf->setfillcolor(245);
      $this->objpdf->Roundedrect(40,200,48,10,2,'DF','1234');
      $this->objpdf->Roundedrect(93,200,48,10,2,'DF','1234');
      $this->objpdf->Roundedrect(146,200,48,10,2,'DF','1234');
      $this->objpdf->text(42,202,'Vencimento');
      $this->objpdf->text(95,202,'C�digo de Arrecada��o');
      $this->objpdf->text(148,202,'Valor a Pagar');
      $this->objpdf->setfont('Arial','',10);
      $this->objpdf->text(48,207,$this->dtvenc);
      $this->objpdf->text(101,207,$this->numpre);
      $this->objpdf->text(153,207,$this->valtotal);

      $this->objpdf->SetDash(0.8,0.8);
      $this->objpdf->line(5,242.5,205,242.5);
      $this->objpdf->SetDash();
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Roundedrect(10,244,190,46,2,'DF','1234');
      $this->objpdf->setfont('Arial','',12);
      $this->objpdf->setfillcolor(0,0,0);
      $this->objpdf->Image('imagens/files/'.$this->logo,12,200,25);
      $this->objpdf->text(60,218,$this->linhadigitavel);
      $this->objpdf->int25(60,220,$this->codigobarras,15,0.341);
      $this->objpdf->setfillcolor(245);
      $this->objpdf->Roundedrect(40,250,48,10,2,'DF','1234');
      $this->objpdf->Roundedrect(93,250,48,10,2,'DF','1234');
      $this->objpdf->Roundedrect(146,250,48,10,2,'DF','1234');
      $this->objpdf->setfont('Arial','',6);
      $this->objpdf->text(42,252,'Vencimento');
      $this->objpdf->text(95,252,'C�digo de Arrecada��o');
      $this->objpdf->text(148,252,'Valor a Pagar');
      $this->objpdf->setfont('Arial','',10);
      $this->objpdf->text(48,257,$this->dtvenc);
      $this->objpdf->text(101,257,$this->numpre);
      $this->objpdf->text(153,257,$this->valtotal);
      $this->objpdf->Image('imagens/files/'.$this->logo,12,250,25);
      $this->objpdf->SetFont('Arial','',5);
      $this->objpdf->text(10,$this->objpdf->h-2,'Base: '.@$GLOBALS["DB_NBASE"]);
      $this->objpdf->setfillcolor(0,0,0);
      $this->objpdf->setfont('Arial','',12);
      $this->objpdf->text(60,268,$this->linhadigitavel);
      $this->objpdf->int25(60,270,$this->codigobarras,15,0.341);
    }else if ( $this->modelo == 3 ) {
      $this->objpdf->SetTextColor(0,0,0);
      $this->objpdf->SetFont('Arial','B',12);
      $coluna = 44;
      $linha = 35;
      $this->objpdf->SetLineWidth(1);
      $this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
      $this->objpdf->SetLineWidth(0.5);
      $this->objpdf->roundedrect(39,2,133,191,2,'1234');
      $this->objpdf->SetLineWidth(0.2);
      $this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
      $this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);

//	$this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
//	$this->objpdf->roundedrect(42,$linha+72,127,15,2,'1234'); // obs da atividade principal

//  	$this->objpdf->roundedrect(42,$linha+88,127,5,2,'1234'); // descricao da atividade secundaria
//	$this->objpdf->roundedrect(42,$linha+94,127,15,2,'1234'); // obs da atividade secundaria


//	$this->objpdf->setdrawcolor(235);

      $this->objpdf->setxy(65,5);
      $this->objpdf->setfont('Arial','B',13);
      $this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

      $this->objpdf->setxy(65,10);
      $this->objpdf->setfont('Arial','B',13);
      $this->objpdf->setleftmargin(50);
      $this->objpdf->setrightmargin(50);
      $this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

      $this->objpdf->settextcolor(150);
      $this->objpdf->setxy(85,25);
      $this->objpdf->setfont('Arial','B',60);
      $this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio
      $this->objpdf->settextcolor(0,0,0);

      $this->objpdf->setxy(84,24);
      $this->objpdf->setfont('Arial','B',60);
      $this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio

      $this->objpdf->Ln(6);
//	$this->objpdf->sety(38);
      $this->objpdf->SetFont('Arial','',11);
      $this->objpdf->Multicell(0,6,$this->texto); // texto

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+35,'CCM:'); // atividade / inscricao
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+35,$this->ativ.' / '.$this->nrinscr); // atividade / inscricao

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+39,"NOME/RAZAO SOCIAL: "); // nome
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+39,$this->nome); // nome

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+43,"ENDERE�O: "); // endereco
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+43,$this->ender); // endereco

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+47,"N�MERO: "); // endereco
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+47,($this->numero == ""?"":$this->numero));

      if ($this->compl != "") {
        $this->objpdf->SetFont('Arial','B',9);
        $this->objpdf->Text($coluna + 60 ,$linha+47,"COMPLEMENTO: "); // endereco
        $this->objpdf->SetFont('Arial','',9);
        $this->objpdf->Text($coluna + 90,$linha+47,($this->compl == ""?"":$this->compl));
      }

      $this->objpdf->setx(40);
      if($this->q02_memo!=''){
        $this->objpdf->SetFont('Arial','B',9);
        $this->objpdf->Text($coluna,$linha+51,"OBSERVA��O: "); // observa��o
        $this->objpdf->SetFont('Arial','',9);
        $this->objpdf->sety($linha+52);
        $this->objpdf->Multicell(0,3,$this->q02_memo); // texto
        $this->objpdf->SetFont('Arial','B',10);
        $this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
        $linha = 102;
      } else {
        $this->objpdf->roundedrect(42,$linha+30,127,20,2,'1234');
        $linha = 87;
      }

      $this->objpdf->sety($linha);

      $this->objpdf->roundedrect(42,$linha-1,127,5,2,'1234');
      $this->objpdf->SetFont('Arial','B',8);
      $this->objpdf->Ln(0.5);
      $this->objpdf->setx(45);
      $this->objpdf->Multicell(0,3,"ATIVIDADE PRINCIPAL: " . $this->descrativ) ; // descri��o da atividade principal
      $linha += 6;
      $obs='';
      if(isset($this->q03_atmemo[$this->ativ])){
        if ($this->q03_atmemo[$this->ativ] != '') {;
          $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade principal
          $obs = $this->q03_atmemo[$this->ativ];
          $this->objpdf->Ln(3);
          $this->objpdf->SetFont('Arial','',7);
          $this->objpdf->Multicell(0,3,$this->q03_atmemo[$this->ativ]); // texto
          $linha += 16;
        }
      }

      $this->objpdf->sety($linha);

      $num_outras=count($this->outrasativs);
      $x=105;
      if ($num_outras >0 ) {

        $x=$x+4;
        reset($this->outrasativs);
        for($i=0; $i<$num_outras; $i++){
          $yyy = $this->objpdf->gety();
          $chave=key($this->outrasativs);
          $obs='';
          if(isset($this->q03_atmemo[$chave])){
            $obs = $this->q03_atmemo[$chave];
          }

          $this->objpdf->SetFont('Arial','B',8);

          $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
          $this->objpdf->Ln(0.5);
          $this->objpdf->setx(45);
          $this->objpdf->Multicell(0,3,"ATIVIDADE SECUND�RIA: " . $this->outrasativs[$chave]); // texto
          $linha += 6;

          if($obs!=""){
            $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade secundaria
            $this->objpdf->Ln(3);
            $this->objpdf->SetFont('Arial','',7);
            $this->objpdf->Multicell(0,3,$obs); // texto
            $linha += 16;
          }

          $x=$x+4;
          next($this->outrasativs);
//             $this->objpdf->ln(2.5);
          $this->objpdf->sety($linha);
        }
      }
      $x=64;
//        if($this->q02_obs!=''){
//	  $this->objpdf->Text($coluna,$linha+$x,"OBSERVA��O: "); // descri��o da atividade principal
//	  $this->objpdf->Text($coluna + 45,$linha+$x,$this->q02_obs); // descri��o da atividade principal
//	  $x=$x+4;
//	}

//        $linha = $this->objpdf->gety();
      $this->objpdf->SetFont('Arial','B',12);
      $this->objpdf->Text($coluna+55,$linha + 5,"Sapiranga, ".date('d')." de ".db_mes( date('m') )." de ".date('Y') . "."); // data

      $this->objpdf->sety(125);
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Multicell(0,6,$this->obs); // observa��o
      $this->objpdf->setfont('arial','',6);
      $this->objpdf->SetXY($coluna-18,165);
      $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRET�RIO DA IND. COM. E TURISMO',0,"C",0);
      $this->objpdf->SetXY($coluna+50,165);
      $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);

//        $this->objpdf->SetXY($coluna-35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRET�RIO DA IND. COM. E TURISMO',0,"C",0);
//        $this->objpdf->SetXY($coluna+35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);


      $this->objpdf->sety(180);
      $this->objpdf->setfont('arial','B',12);
      $this->objpdf->multicell(0,8,'FIXAR EM LUGAR VIS�VEL',1,"C");
      $this->objpdf->SetFont('Arial','B',10);


    }else if ( $this->modelo == 4 ) {

      // BOLETO BANC�RIO

      $linha = 186;
      $pdf->Line(47,$linha,47,$linha+9);
      $pdf->Line(63,$linha,63,$linha+9);
      $pdf->SetLineWidth(0.6);
      $pdf->Line(10,$linha+9,195,$linha+9);
      $pdf->SetLineWidth(0.2);

      $pdf->Line(10,$linha+17,195,$linha+17);
      $pdf->Line(10,$linha+25,195,$linha+25);
      $pdf->Line(10,$linha+33,195,$linha+33);
      $pdf->Line(10,$linha+41,195,$linha+41);
      $pdf->Line(149,$linha+49,195,$linha+49);
      $pdf->Line(149,$linha+57,195,$linha+57);
      $pdf->Line(149,$linha+65,195,$linha+65);
      $pdf->Line(149,$linha+73,195,$linha+73);
      $pdf->Line(10,$linha+81,195,$linha+81);

      $pdf->Line(149,$linha+9,149,$linha+81);
      $pdf->Line(169,$linha+9,169,$linha+17);
      $pdf->Line(40,$linha+25,40,$linha+33);
      $pdf->Line(86,$linha+25,86,$linha+33);
      $pdf->Line(112,$linha+25,112,$linha+33);
      $pdf->Line(125,$linha+25,125,$linha+33);

      $pdf->Line(45,$linha+33,45,$linha+41);
      $pdf->Line(65,$linha+33,65,$linha+41);
      $pdf->Line(91,$linha+33,91,$linha+41);
      $pdf->Line(121,$linha+33,121,$linha+41);

      $pdf->Line(10,$linha+93,195,$linha+93);

      //codigo de barras
      $this->objpdf->SetFillColor(0,0,0);

      $this->objpdf->int25(10,$linha+94,$this->codigo_barras,20,0.341);


      // quadrado inferior //
      $this->objpdf->Image('imagens/files/Brasao.png',10,187,35,7);
      $this->objpdf->SetFont('Arial','b',14);
      $this->objpdf->Text(49,$linha+7,$this->numbanco);			// numero do banco
      $this->objpdf->SetFont('Arial','b',11);
      $this->objpdf->Text(70,$linha+7,$this->linha_digitavel);
      $this->objpdf->SetFont('Arial','b',5);
      $this->objpdf->Text(13,$linha+11,"Local de Pagamento");
      $this->objpdf->Text(151,$linha+11,"Parcela");
      $this->objpdf->Text(171,$linha+11,"Vencimento");
      $this->objpdf->Text(13,$linha+19,"Cedente");
      $this->objpdf->Text(151,$linha+19,"Ag�ncia/C�digo Cedente");
      $this->objpdf->Text(13,$linha+27,"Data do Documento");
      $this->objpdf->Text(42,$linha+27,"N�mero do Documento");
      $this->objpdf->Text(88,$linha+27,"Esp�cie Doc.");
      $this->objpdf->Text(114,$linha+27,"Aceite");
      $this->objpdf->Text(127,$linha+27,"Data do Processamento");
      $this->objpdf->Text(151,$linha+27,"Nosso N�mero");
      $this->objpdf->Text(13,$linha+35,"C�digo do Cedente");
      $this->objpdf->Text(47,$linha+35,"Carteira");
      $this->objpdf->Text(67,$linha+35,"Esp�cie");
      $this->objpdf->Text(93,$linha+35,"Quantidade");
      $this->objpdf->Text(123,$linha+35,"Valor");
      $this->objpdf->Text(151,$linha+35,"( = ) Valor do Documento");
      $this->objpdf->Text(13,$linha+43,"Instru��es");
      $this->objpdf->Text(151,$linha+43,"( - ) Desconto / Abatimento");
      $this->objpdf->Text(151,$linha+51,"( - ) Outras Dedu��es");
      $this->objpdf->Text(151,$linha+59,"( + ) Mora / Multa");
      $this->objpdf->Text(151,$linha+67,"( + ) Outros Acr�cimos");
      $this->objpdf->Text(151,$linha+75,"( = ) Valor Cobrado");
      $this->objpdf->Text(13,$linha+83,"Sacado");
      $this->objpdf->Text(13,$linha+91,"Sacador/Avalista");
      $this->objpdf->Text(160,$linha+99,"Autentica��o Mec�nica");

      $this->objpdf->SetFont('Arial','b',8);
      $this->objpdf->Text(13,$linha+15,$this->localpagamento);  	// local de pagamento
      $this->objpdf->SetFont('Arial','',10);
      $this->objpdf->Text(151,$linha+15,$this->parcela);  		// parcela
      $this->objpdf->Text(171,$linha+15,$this->dtvenc);  		// vencimento
      $this->objpdf->Text(13,$linha+23,$this->cedente);  		// cedente
      $this->objpdf->Text(151,$linha+23,$this->agencia_cedente);  	// agencia do cedente
      $this->objpdf->Text(13,$linha+31,$this->data_documento);  	// data do documento
      $this->objpdf->Text(42,$linha+31,$this->numero_documento);	// numero do documento
      $this->objpdf->Text(88,$linha+31,$this->especie_doc);  		// especie do documento
      $this->objpdf->Text(114,$linha+31,$this->aceite);  		// aceite
      $this->objpdf->Text(127,$linha+31,$this->data_processamento);	// data do processamento
      $this->objpdf->Text(151,$linha+31,$this->nosso_numero);  	// nosso numero
      $this->objpdf->Text(13,$linha+39,$this->codigo_cedente); 	// codigo do cedente
      $this->objpdf->Text(47,$linha+39,$this->carteira);  		// carteira
      $this->objpdf->Text(67,$linha+39,$this->especie);  		// especie
      $this->objpdf->Text(93,$linha+39,$this->quantidade); 		// quantidade
      $this->objpdf->Text(123,$linha+39,$this->valor);  		// valor
      $this->objpdf->Text(151,$linha+39,$this->valor_documento);  	// valor do documento

      $this->objpdf->Text(20,$linha+54,$this->instrucoes1);  		// instrucoes 1
      $this->objpdf->Text(20,$linha+58,$this->instrucoes2);  		// instrucoes 2
      $this->objpdf->Text(20,$linha+62,$this->instrucoes3);  		// instrucoes 3
      $this->objpdf->Text(15,$linha+70,$this->instrucoes4);  		// instrucoes 4
      $this->objpdf->Text(20,$linha+74,$this->instrucoes5);  		// instrucoes 5

      $this->objpdf->Text(151,$linha+47,$this->desconto_abatimento);	// desconto/abatimento
      $this->objpdf->Text(151,$linha+55,$this->outras_deducoes); 	// outras deducoes
      $this->objpdf->Text(151,$linha+63,$this->mora_multa);  		// multa
      $this->objpdf->Text(151,$linha+71,$this->outros_acrecimos);	// outros acrescimos
      $this->objpdf->Text(151,$linha+79,$this->valor_cobrado);  	// valor cobrado

      $this->objpdf->Text(29,$linha+85,$this->sacado1);  		// sacado 1
      $this->objpdf->Text(29,$linha+88,$this->sacado2);		// sacado 2
      $this->objpdf->Text(29,$linha+91,$this->sacado3);		// sacado 3

    }else if ( $this->modelo == 2 ) {

      //// RECIBO

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $this->objpdf->line(2,148.5,208,148.5);
      $xlin = 20;
      $xcol = 4;
      for ($i = 0;$i < 2;$i++){
        $this->objpdf->setfillcolor(245);
        $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
        $this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
        $this->objpdf->Setfont('Arial','B',11);
        $this->objpdf->text(150,$xlin-13,'DAM V�LIDO AT�: ');
        $this->objpdf->text(159,$xlin-8,$this->datacalc);
        $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
        $this->objpdf->Setfont('Arial','B',9);
        $this->objpdf->text(40,$xlin-15,$this->prefeitura);
        $this->objpdf->Setfont('Arial','',9);
        $this->objpdf->text(40,$xlin-11,$this->enderpref);
        $this->objpdf->text(40,$xlin-8,$this->municpref);
        $this->objpdf->text(40,$xlin-5,$this->telefpref);
        $this->objpdf->text(40,$xlin-2,$this->emailpref);
//		$this->objpdf->setfillcolor(245);

        $this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+119,20,2,'DF','1234');
        $this->objpdf->Setfont('Arial','',6);
        $this->objpdf->text($xcol+2,$xlin+4,'Identifica��o:');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+2,$xlin+7,$this->tipoinscr);
        $this->objpdf->text($xcol+17,$xlin+7,$this->nrinscr);
        $this->objpdf->text($xcol+30,$xlin+7,'Nome :');
        $this->objpdf->text($xcol+40,$xlin+7,$this->nome);
        $this->objpdf->text($xcol+2,$xlin+11,'Endere�o :');
        $this->objpdf->text($xcol+17,$xlin+11,$this->ender);
        $this->objpdf->text($xcol+2,$xlin+15,'Munic�pio :');
        $this->objpdf->text($xcol+17,$xlin+15,$this->munic);
        $this->objpdf->text($xcol+75,$xlin+15,'CEP :');
        $this->objpdf->text($xcol+82,$xlin+15,$this->cep);
        $this->objpdf->text($xcol+2,$xlin+19,'Data :');


        $this->objpdf->text($xcol+17,$xlin+19, date("d-m-Y",db_getsession("DB_datausu")));



        $this->objpdf->text($xcol+40,$xlin+19,'Hora: '.date("H:i:s"));





        $this->objpdf->text($xcol+75,$xlin+19,'IP :');
        $this->objpdf->text($xcol+82,$xlin+19,$this->ip);
        $this->objpdf->Setfont('Arial','',6);

        $this->objpdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');
        $this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr1);
        $this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr1);
        $this->objpdf->text($xcol+128,$xlin+11,$this->tipolograd);
        $this->objpdf->text($xcol+145,$xlin+11,$this->nomepri);
        $this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
        $this->objpdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
        $this->objpdf->text($xcol+128,$xlin+19,$this->tipobairro);
        $this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

//		$this->objpdf->setfillcolor(245);
        $this->objpdf->Roundedrect($xcol,$xlin+24,202,45,2,'DF','1234');
        $this->objpdf->sety($xlin+24);
        $maiscol = 0;
        $yy = $this->objpdf->gety();
        for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {
          if ($ii == 14 ){
            $maiscol = 100;
            $this->objpdf->sety($yy);
          }
          if($ii==0 || $ii == 14){

            $this->objpdf->setx($xcol+3+$maiscol);
            $this->objpdf->cell(4,3,"Rec",0,0,"L",0);
            $this->objpdf->cell(11,3,"    Reduz",0,0,"L",0);
            $this->objpdf->cell(63,3,"    Descri��o",0,0,"L",0);
            $this->objpdf->cell(15,3,"                Valor",0,1,"L",0);

          }
          $this->objpdf->setx($xcol+3+$maiscol);
          $this->objpdf->cell(6,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
          $this->objpdf->cell(11,3,"(".trim(pg_result($this->recorddadospagto,$ii,$this->receitared)).")",0,0,"R",0);
          if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
            $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)),0,0,"L",0);
          }else{
            $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)),0,0,"L",0);
          }
          $this->objpdf->cell(15,3,db_formatar(pg_result($this->recorddadospagto,$ii,$this->valor),'f'),0,1,"R",0);
        }
        $this->objpdf->Roundedrect($xcol,$xlin+71,202,30,2,'DF','1234');
        $this->objpdf->SetY($xlin+72);
        $this->objpdf->SetX($xcol+3);
        $this->objpdf->multicell(0,4,'HIST�RICO :   '.$this->historico);
        $this->objpdf->SetX($xcol+3);
        $this->objpdf->multicell(0,4,$this->histparcel);
        $this->objpdf->Setfont('Arial','',6);
        $this->objpdf->setx(15);

        $this->objpdf->Roundedrect(128,$xlin+103,38,10,2,'DF','1234');
        $this->objpdf->Roundedrect(168,$xlin+103,38,10,2,'DF','1234');
        $this->objpdf->Roundedrect(146,$xlin+115,40,10,2,'DF','1234');
        $this->objpdf->text(130,$xlin+105,'Vencimento');
        $this->objpdf->text(170,$xlin+105,'C�digo de Arrecada��o');
        $this->objpdf->text(148,$xlin+118,'Valor a Pagar');
        $this->objpdf->setfont('Arial','',10);
        $this->objpdf->text(135,$xlin+110,$this->dtvenc);
        $this->objpdf->text(170,$xlin+110,$this->numpre);
        $this->objpdf->text(155,$xlin+123,$this->valtotal);

        $this->objpdf->setfillcolor(0,0,0);
        $this->objpdf->SetFont('Arial','',4);
//	        $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
        $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto . ' - ' . ($i == 1?'2� VIA':'1� VIA'),'U'); // texto no canhoto do carne
        $this->objpdf->setfont('Arial','',11);
        $this->objpdf->text(10,$xlin+108,$this->linhadigitavel);
        $this->objpdf->int25(10,$xlin+110,$this->codigobarras,15,0.341);
        $xlin = 169;

      }

    }else if ( $this->modelo == 11 ) {
////////// MODELO 11  -  SOLICITA��O DE COMPRA
      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $pagina = 1;
      $xlin = 20;
      $xcol = 4;

      // Imprime caixa externa
      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

      // Imprime o cabe�alho com dados sobre a prefeitura
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(130,$xlin-13,'SOLICITA��O DE COMPRA N'.CHR(176));
      $this->objpdf->text(185,$xlin-13,db_formatar($this->pc10_numero,'s','0',6,'e'));
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin- 8,$this->municpref);
      $this->objpdf->text(40,$xlin- 5,$this->telefpref);
      $this->objpdf->text(40,$xlin- 2,$this->emailpref);

      // Caixa com dados da solicita��o
      $this->objpdf->rect($xcol,$xlin+3,$xcol+198,18,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+2,$xlin+5,'Dados do Solicita��o');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+  2,$xlin+ 8,'Departamento');
      $this->objpdf->text($xcol+109,$xlin+ 8,'Tipo');
      $this->objpdf->text($xcol+  2,$xlin+12,'Data');
      $this->objpdf->text($xcol+109,$xlin+12,'Val. Aprox.');
      $this->objpdf->text($xcol+  2,$xlin+16,'Resumo');
      $this->objpdf->Setfont('Arial','',8);

      // Imprime dados da solicita��o
      $this->objpdf->text($xcol+ 23,$xlin+ 8,':  '.$this->descrdepto);
      if(isset($this->pc10_data) && trim($this->pc10_data)!=""){
        $this->pc10_data = db_formatar($this->pc10_data,'d');
      }
      if(isset($this->pc10_vlrap) && trim($this->pc10_vlrap)!=""){
        $this->pc10_vlrap = db_formatar($this->pc10_vlrap,'f');
      }
      $this->objpdf->text($xcol+125,$xlin+ 8,':  '.$this->pc50_descr);
      $this->objpdf->text($xcol+ 23,$xlin+12,':  '.$this->pc10_data);
      $this->objpdf->text($xcol+125,$xlin+12,':   R$ '.$this->pc10_vlrap);
      $this->objpdf->setxy($xcol+22,$xlin+13);
      $this->objpdf->cell(3,4,':  ',0,0,"L",0);
      $this->objpdf->setxy($xcol+24.5,$xlin+13);
      $this->objpdf->multicell(175,4,substr($this->pc10_resumo,0,240),0,"J");

      // Caixas dos label's
      $this->objpdf->rect($xcol    ,$xlin+24,15,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 15,$xlin+24,20,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 35,$xlin+24,107,6,2,'DF','12');
      $this->objpdf->rect($xcol+142,$xlin+24,30,6,2,'DF','12');
      $this->objpdf->rect($xcol+172,$xlin+24,30,6,2,'DF','12');

      // Caixa dos itens
      $this->objpdf->rect($xcol,    $xlin+30,15,185,2,'DF','34');
      // Caixa da quantidade
      $this->objpdf->rect($xcol+ 15,$xlin+30,20,185,2,'DF','34');
      // Caixa dos materiais ou servi�os
      $this->objpdf->rect($xcol+ 35,$xlin+30,107,185,2,'DF','34');
      // Caixa dos valores unit�rios
      $this->objpdf->rect($xcol+142,$xlin+30,30,185,2,'DF','');
      // Caixa dos valores totais dos itens
      $this->objpdf->rect($xcol+172,$xlin+30,30,185,2,'DF','34');

      $this->objpdf->sety($xlin+28);
      $alt = 4;

      // Label das colunas
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+   4,$xlin+28,'ITEM');
      $this->objpdf->text($xcol+15.5,$xlin+28,'QUANTIDADE');
      $this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVI�O');
      $this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNIT�RIO');
      $this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');
      $maiscol = 0;

      $this->objpdf->setleftmargin(8);
      $this->objpdf->sety($xlin+32);

      $xtotal = 0;
      $this->objpdf->SetWidths(array(10,22,105,30,30));
      $this->objpdf->SetAligns(array('C','C','J','R','R'));
      $pc11_codigo_ant = '';
      $muda_pag = false;
      for($ii = 0;$ii < $this->linhasdositens ;$ii++){
        $pagina = $this->objpdf->PageNo();
        db_fieldsmemory($this->recorddositens,$ii);
        $igual = false;
        if($pc11_codigo_ant==pg_result($this->recorddositens,$ii,$this->item)){
          $igual = true;
        }
        if($igual==false && $ii!=0 && $muda_pag==false){
          $muda_pag = false;
          $this->objpdf->ln(0.3);
          $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
          $this->objpdf->ln(1.3);
        }
        $item  = '';
        $quantitem = '';
        $descricaoitem = '';
        $valoritem = '';
        $valtot=  0;
        $valimp= '';
        $prazo = '';
        $pgto  = '';
        $resum = '';
        $just  = '';
        if($igual==false){
          $item  = pg_result($this->recorddositens,$ii,$this->item);
          $pc11_codigo_ant = $item;
          $quantitem = pg_result($this->recorddositens,$ii,$this->quantitem);
          $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
          $valoritem = db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),"f");
          $valtot= pg_result($this->recorddositens,$ii,$this->valtot);
          $valimp= db_formatar($valtot,'f');
          $prazo = pg_result($this->recorddositens,$ii,$this->pc11_prazo);
          $pgto  = pg_result($this->recorddositens,$ii,$this->pc11_pgto);
          $resum = pg_result($this->recorddositens,$ii,$this->pc11_resum);
          $just  = pg_result($this->recorddositens,$ii,$this->pc11_just);
          if(isset($prazo) && trim($prazo)!=""){
            $prazo = "\nPRAZO: ".trim($prazo);
          }
          if(isset($pgto) && trim($pgto)!=""){
            $pgto = "\nCONDI��O: ".trim($pgto);
          }
          if(isset($resum) && trim($resum)!=""){
            $resum = "\nRESUMO: ".trim($resum);
          }
          if(isset($just) && trim($just)!=""){
            $just = "\nJUSTIFICATIVA: ".trim($just);
          }
        }
        $pc13_quant = '';
        $pc13_coddot= '';
        $pc13_valor = '';
        $testaqtd = pg_result($this->recorddositens,$ii,$this->pc13_quant);
        $testacod = pg_result($this->recorddositens,$ii,$this->pc13_coddot);
        $testaval = pg_result($this->recorddositens,$ii,$this->pc13_valor);
        if(isset($testaqtd) && trim($testaqtd)!=""){
          $pc13_quant = $testaqtd;
        }
        $dist = 2.7;
        if(isset($testacod) && trim($testacod)!=""){
          $pc13_coddot= "DOTA��O: ".$testacod;
          $dist = 3.2;
        }
        if(isset($testaval) && trim($testaval)!=""){
          $pc13_valor = db_formatar($testaval,'f');
        }
        if($igual==false){
          $xtotal += $valtot;
        }
        if($igual==false){
          $this->objpdf->Setfont('Arial','B',9);
          $this->objpdf->Row(array($item,
                                   $quantitem,
                                   $descricaoitem,
                                   $valoritem,
                                   $valimp),3,false,0);
          $this->objpdf->Setfont('Arial','',7);
          $x = $this->muda_pag($pagina,$xlin,$xcol);

          $this->objpdf->Row(array('','',$prazo,'',''),3,false,$dist);
          $x = $this->muda_pag($pagina,$xlin,$xcol);

          $this->objpdf->Row(array('','',$pgto,'',''),3,false,$dist);
          $x = $this->muda_pag($pagina,$xlin,$xcol);

          $this->objpdf->Row(array('','',$resum,'',''),3,false,$dist);
          $x = $this->muda_pag($pagina,$xlin,$xcol);

          $this->objpdf->Row(array('','',$just,'',''),3,false,$dist);
          $x = $this->muda_pag($pagina,$xlin,$xcol);
        }
        $this->objpdf->Setfont('Arial','',7);
        $this->objpdf->Row(array('',
                                 $pc13_quant,
                                 $pc13_coddot,
                                 $pc13_valor,
                                 ''),3,false,3);
        $this->objpdf->Setfont('Arial','B',7);
        $x = $this->muda_pag($pagina,$xlin,$xcol);

        // troca de pagina
        $this->objpdf->text(172,268,$x);
      }
      $this->objpdf->Setfont('Arial','B',8);
      $maislin = 268;
      if($this->objpdf->PageNo() == 1){
        $maislin = 211;
      }
      $this->objpdf->text(172,$xlin+$maislin,db_formatar($xtotal,'f'));
      if ($this->objpdf->PageNo() == 1){
        $this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
        $this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
        $this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
        $this->objpdf->text($xcol+120,$xlin+211,'T O T A L');

        $this->objpdf->rect($xcol,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->rect($xcol+68,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->setfillcolor(0,0,0);

        $y = 260;
        $this->objpdf->SetXY(2,$y);
        $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'DIRETOR DE COMPRAS',0,"C",0);

        $this->objpdf->SetXY(72,$y);
        $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'ORDENADOR DA DESPESA',0,"C",0);

        $this->objpdf->SetXY(142,$y);
        $this->objpdf->MultiCell(70,4,'VISTO'."\n\n\n".'',0,"C",0);

        $this->objpdf->setfillcolor(0,0,0);
        $this->objpdf->text($xcol+5,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');

//	  $this->objpdf->SetFont('Arial','',4);
//        $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
//	  $this->objpdf->setfont('Arial','',11);
//        $xlin = 169;
      }else{
        $this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
        $this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
        $this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
        $this->objpdf->text($xcol+120,$xlin+268,'T O T A L');
      }
    }else if ( $this->modelo == 5 ) {

////////// MODELO 5  -  AUTORIZACAO DE EMPENHO

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $pagina = 1;
      $xlin = 20;
      $xcol = 4;

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(130,$xlin-13,'AUTORIZA��O DE EMPENHO N'.CHR(176));
      $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
      $this->objpdf->text(133.5,$xlin-8,'SOLICITA��O DE COMPRA N'.CHR(176));
      $this->objpdf->text(185,$xlin-8,db_formatar($this->numsol,'s','0',6,'e'));
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin- 8,$this->municpref);
      $this->objpdf->text(40,$xlin- 5,$this->telefpref);
      $this->objpdf->text(40,$xlin- 2,$this->emailpref);

      $this->objpdf->rect($xcol,$xlin+2,$xcol+100,28,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+2,$xlin+4,'Dados da Ordem de Compra');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+2,$xlin+ 8,'Licita��o');
      $this->objpdf->text($xcol+2,$xlin+12,'Tipo de Compra');
      $this->objpdf->text($xcol+2,$xlin+16,'Prazo de Entrega');
      $this->objpdf->text($xcol+2,$xlin+20,'Observa��es');
      $this->objpdf->text($xcol+2,$xlin+24,'Cond.de Pagto');
      $this->objpdf->text($xcol+2,$xlin+28,'Outras Condi��es');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+27,$xlin+ 8,':  '.$this->num_licitacao.'  -  '.$this->descr_licitacao);
      $this->objpdf->text($xcol+27,$xlin+12,':  '.$this->descr_tipocompra);
      $this->objpdf->text($xcol+27,$xlin+16,':  '.$this->prazo_ent);
      $this->objpdf->text($xcol+27,$xlin+20,':  '.$this->obs);
      $this->objpdf->text($xcol+27,$xlin+24,':  '.$this->cond_pag);
      $this->objpdf->text($xcol+27,$xlin+28,':  '.$this->out_cond);

      $this->objpdf->rect($xcol+106,$xlin+2,96,28,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+110,$xlin+4,'Dados da Dota��o');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+108,$xlin+ 8,'Dota��o');
      $this->objpdf->text($xcol+108,$xlin+12,'Reduzido');
      $this->objpdf->text($xcol+108,$xlin+16,'Descri��o');
      $this->objpdf->text($xcol+108,$xlin+20,'�rg�o');
      $this->objpdf->text($xcol+108,$xlin+24,'Unidade');
      $this->objpdf->text($xcol+108,$xlin+28,'Destino');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+122,$xlin+ 8,':  '.$this->dotacao);
      $this->objpdf->text($xcol+122,$xlin+12,':  '.$this->coddot.'-'.db_CalculaDV($this->coddot));
      $this->objpdf->text($xcol+122,$xlin+16,':  '.$this->descrdotacao);
      $this->objpdf->text($xcol+122,$xlin+20,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
      $this->objpdf->text($xcol+122,$xlin+24,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
      $this->objpdf->text($xcol+122,$xlin+28,':  '.$this->destino);

      $this->objpdf->rect($xcol,$xlin+32,$xcol+198,20,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+2,$xlin+34,'Dados do Credor');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
      $this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
      $this->objpdf->text($xcol+  2,$xlin+38,'Nome');
      $this->objpdf->text($xcol+  2,$xlin+42,'Endere�o');
      $this->objpdf->text($xcol+102,$xlin+42,'Complemento');
      $this->objpdf->text($xcol+  2,$xlin+46,'Munic�pio');
      $this->objpdf->text($xcol+115,$xlin+46,'CEP');
      $this->objpdf->text($xcol+  2,$xlin+50,'Contato');
      $this->objpdf->text($xcol+110,$xlin+50,'Telefone');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+157,$xlin+38,':  '.$this->cnpj);
      $this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
      $this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
      $this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
      $this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
      $this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
      $this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
      $this->objpdf->text($xcol+18,$xlin+ 50,':  '.$this->contato);
      $this->objpdf->text($xcol+122,$xlin+50,':  '.$this->telef_cont);

      $this->objpdf->Setfont('Arial','B',8);
//	$this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
      $this->objpdf->rect($xcol    ,$xlin+54,15,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 15,$xlin+54,20,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 35,$xlin+54,107,6,2,'DF','12');
      $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
      $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

      $this->objpdf->rect($xcol,    $xlin+60,15,122,2,'DF','34');
      $this->objpdf->rect($xcol+ 15,$xlin+60,20,122,2,'DF','34');
      $this->objpdf->rect($xcol+ 35,$xlin+60,107,122,2,'DF','34');

      $this->objpdf->rect($xcol+142,$xlin+60,30,155,2,'DF','');
      $this->objpdf->rect($xcol+172,$xlin+60,30,155,2,'DF','34');

      $this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
      $this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
      $this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
      $this->objpdf->text($xcol+120 ,$xlin+211,'T O T A L');


      $this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');


      $this->objpdf->sety($xlin+28);
      $alt = 4;

      $this->objpdf->text($xcol+   4,$xlin+58,'ITEM');
      $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
      $this->objpdf->text($xcol+  70,$xlin+58,'MATERIAL OU SERVI�O');
      $this->objpdf->text($xcol+ 145,$xlin+58,'VALOR UNIT�RIO');
      $this->objpdf->text($xcol+ 176,$xlin+58,'VALOR TOTAL');
      $maiscol = 0;

      $this->objpdf->SetWidths(array(10,22,105,30,30));
      $this->objpdf->SetAligns(array('C','C','L','R','R'));

      $this->objpdf->setleftmargin(8);
      $this->objpdf->sety($xlin+62);

      $xtotal = 0;

      for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
        db_fieldsmemory($this->recorddositens,$ii);
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
                                 pg_result($this->recorddositens,$ii,$this->quantitem),
                                 pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
        $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
        $this->objpdf->Setfont('Arial','B',8);
        /////// troca de pagina
        if( ( $this->objpdf->gety() > $this->objpdf->h - 70 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1 )){
          if ($this->objpdf->PageNo() == 1){
            $this->objpdf->text(110,$xlin+214,'Continua na P�gina '.($this->objpdf->PageNo()+1));
            $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');

            $y = 260;

            //// ASSINATURAS DA AUTORIZACAO
            $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
            $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
            $visto =  "VISTO";

            $ass_cont   = $this->assinatura(5000,$cont);
            $ass_ord    = $this->assinatura(5000,$ord);
            $ass_visto  = $this->assinatura(5000,$visto);
            $this->objpdf->SetXY(2,$y);

            $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);

            $this->objpdf->SetXY(72,$y);
            $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);

            $this->objpdf->SetXY(142,$y);
            $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
            //////

            $this->objpdf->setfillcolor(0,0,0);
            $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
            $this->objpdf->SetFont('Arial','',4);
            $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
            $this->objpdf->setfont('Arial','',11);
          }else{
            $this->objpdf->text(110,$xlin+320,'Continua na P�gina '.($this->objpdf->PageNo()+1));
          }
          $this->objpdf->addpage();
          $pagina += 1;

          $this->objpdf->settopmargin(1);
          $xlin = 20;
          $xcol = 4;

          $this->objpdf->setfillcolor(245);
          $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
          $this->objpdf->setfillcolor(255,255,255);
          $this->objpdf->Setfont('Arial','B',9);
          $this->objpdf->text(130,$xlin-13,'AUTORIZA��O DE EMPENHO N'.CHR(176));
          $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
          $this->objpdf->text(133.5,$xlin-8,'SOLICITA��O DE COMPRA N'.CHR(176));
          $this->objpdf->text(185,$xlin-8,db_formatar($this->numsol,'s','0',6,'e'));
          $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
          $this->objpdf->Setfont('Arial','B',9);
          $this->objpdf->text(40,$xlin-15,$this->prefeitura);
          $this->objpdf->Setfont('Arial','',9);
          $this->objpdf->text(40,$xlin-11,$this->enderpref);
          $this->objpdf->text(40,$xlin-8,$this->municpref);
          $this->objpdf->text(40,$xlin-5,$this->telefpref);
          $this->objpdf->text(40,$xlin-2,$this->emailpref);

          $xlin = -30;
          $this->objpdf->Setfont('Arial','B',8);

          $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
          $this->objpdf->rect($xcol+15,$xlin+54,20,6,2,'DF','12');
          $this->objpdf->rect($xcol+35,$xlin+54,107,6,2,'DF','12');
          $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
          $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

          $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
          $this->objpdf->rect($xcol+15,$xlin+60,20,262,2,'DF','34');
          $this->objpdf->rect($xcol+35,$xlin+60,107,262,2,'DF','34');
          $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
          $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

          $this->objpdf->sety($xlin+66);
          $alt = 4;

          $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
          $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
          $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVI�O');
          $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNIT�RIO');
          $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');
          $this->objpdf->text($xcol+38,$xlin+63,'Continua��o da P�gina '.($this->objpdf->PageNo()-1));

          $maiscol = 0;

        }
      }

      $this->objpdf->setxy($xcol+1,$xlin+187);
      $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->multicell(140,3.5,$this->resumo);
      $this->objpdf->Setfont('Arial','B',8);

      $this->objpdf->SetXY(172,$xlin+205);
      $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");

      //	echo $this->numaut."<br>";
      //	echo $pagina;exit;
      if ($pagina == 1){
        $this->objpdf->rect($xcol,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->rect($xcol+68,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
        $this->objpdf->setfillcolor(0,0,0);

        $y = 260;
        $this->objpdf->SetXY(2,$y);


        //// ASSINATURAS DA AUTORIZACAO
        $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
        $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
        $visto =  "VISTO";

        $ass_cont   = $this->assinatura(5000,$cont);
        $ass_ord    = $this->assinatura(5000,$ord);
        $ass_visto  = $this->assinatura(5000,$visto);
        $this->objpdf->SetXY(2,$y);

        $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);

        $this->objpdf->SetXY(72,$y);
        $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);

        $this->objpdf->SetXY(142,$y);
        $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
        //////


        $this->objpdf->setfillcolor(0,0,0);
        $this->objpdf->text($xcol+10,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');

//	   $this->objpdf->SetFont('Arial','',4);
//         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
//	   $this->objpdf->setfont('Arial','',11);
//         $xlin = 169;
      }
    }else if ( $this->modelo == 6 ) {

////////// MODELO 6  -  NOTA DE EMPENHO

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $pagina = 1;
      $xlin = 20;
      $xcol = 4;

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',10);
      $this->objpdf->text(128,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
      $this->objpdf->text(175,$xlin-13,db_formatar($this->codemp,'s','0',6,'e'));
      $this->objpdf->text(134,$xlin-8,'DATA DE EMISS�O : ');
      $this->objpdf->text(175,$xlin-8,$this->emissao);
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin-8,$this->municpref);
      $this->objpdf->text(40,$xlin-5,$this->telefpref);
      $this->objpdf->text(40,$xlin-2,$this->emailpref);

      /// retangulo dos dados da dota��o
      $this->objpdf->rect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+2,$xlin+7,'�rgao');
      $this->objpdf->text($xcol+2,$xlin+11,'Unidade');
      $this->objpdf->text($xcol+2,$xlin+15,'Fun��o');

      $this->objpdf->text($xcol+2,$xlin+22,'Proj/Ativ');
      $this->objpdf->text($xcol+2,$xlin+30,'Rubrica');
      $this->objpdf->text($xcol+2,$xlin+42,'Recurso');
      $this->objpdf->text($xcol+2,$xlin+48,'Licita��o');

      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
      $this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
      $this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);

      $this->objpdf->text($xcol+17,$xlin+22,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);

      $this->objpdf->text($xcol+17,$xlin+30,':  '.db_formatar($this->sintetico,'elemento'));
      $this->objpdf->setxy($xcol+18,$xlin+31);
      $this->objpdf->multicell(90,3,$this->descr_sintetico,0,"L");

      $this->objpdf->text($xcol+17,$xlin+42,':  '.$this->recurso.' - '.$this->descr_recurso);

      $this->objpdf->text($xcol+17,$xlin+48,':  '.$this->descr_licitacao);



      //// retangulo dos dados do credor
      $this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+107,$xlin+7,'Numcgm');
      $this->objpdf->text($xcol+107,$xlin+11,'Nome');
      $this->objpdf->text($xcol+107,$xlin+15,'Endere�o');
      $this->objpdf->text($xcol+107,$xlin+19,'Munic�pio');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
      $this->objpdf->text($xcol+124,$xlin+11,': '.$this->nome);
      $this->objpdf->text($xcol+124,$xlin+15,': '.$this->ender.'  '.$this->compl);
      $this->objpdf->text($xcol+124,$xlin+19,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);

      ///// retangulo dos valores
      $this->objpdf->rect($xcol+106,$xlin+21.5,96,9,2,'DF','1234');
      $this->objpdf->rect($xcol+106,$xlin+32.0,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+155,$xlin+32.0,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+106,$xlin+42.5,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+155,$xlin+42.5,47,9,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+108,$xlin+34.0,'Valor Or�ado');
      $this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
      $this->objpdf->text($xcol+108,$xlin+44.5,'Valor Empenhado');
      $this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+108,$xlin+27,'AUTORIZA��O N'.chr(176).' '.db_formatar($this->numaut,'s','0',5,'e'));
      $this->objpdf->text($xcol+150,$xlin+27,'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
      $this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
      $this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
      $this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->empenhado,'f'));
      $this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_ant - $this->empenhado,'f'));


      /// retangulo do corpo do empenho
      $this->objpdf->rect($xcol,$xlin+60,15,100,2,'DF','');
      $this->objpdf->rect($xcol+15,$xlin+60,137,100,2,'DF','');
      $this->objpdf->rect($xcol+152,$xlin+60,25,123,2,'DF','');
      $this->objpdf->rect($xcol+177,$xlin+60,25,123,2,'DF','');
      $this->objpdf->rect($xcol,$xlin+160,152,23,2,'DF','');

      //// retangulos do titulo do corpo do empenho
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
      $this->objpdf->rect($xcol+15,$xlin+54,137,6,2,'DF','12');
      $this->objpdf->rect($xcol+152,$xlin+54,25,6,2,'DF','12');
      $this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

      //// t�tulo do corpo do empenho
      $this->objpdf->text($xcol+2,$xlin+58,'QUANT');
      $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVI�O');
      $this->objpdf->text($xcol+154,$xlin+58,'VALOR UNIT�RIO');
      $this->objpdf->text($xcol+181,$xlin+58,'VALOR TOTAL');
      $maiscol = 0;

      /// monta os dados para itens do empenho
      $this->objpdf->SetWidths(array(15,137,25,25));
      $this->objpdf->SetAligns(array('C','L','R','R'));

      $this->objpdf->setleftmargin(4);
      $this->objpdf->sety($xlin+62);
      $this->objpdf->Setfont('Arial','',7);
      $ele = 0;
      $xtotal = 0;
      for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
        db_fieldsmemory($this->recorddositens,$ii);
        $this->objpdf->Setfont('Arial','B',7);
        if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
        {
          $this->objpdf->cell(15,4,'',0,0,"C",0);
          $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
          $ele = pg_result($this->recorddositens,$ii,$this->analitico);
        }
        $this->objpdf->Setfont('Arial','',7);
        $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->quantitem),
                                 pg_result($this->recorddositens,$ii,$this->descricaoitem),
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/
                                   pg_result($this->recorddositens,$ii,$this->quantitem),'f'),
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
        $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
        /////// troca de pagina
        if( ( $this->objpdf->gety() > $this->objpdf->h - 93 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1 )){
          if ($this->objpdf->PageNo() == 1){
            $this->objpdf->text(110,$xlin+214,'Continua na P�gina '.($this->objpdf->PageNo()+1));
            $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');

            $y = 260;
            $this->objpdf->SetXY(2,$y);
            $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'DIRETOR DE COMPRAS',0,"C",0);

            $this->objpdf->SetXY(72,$y);
            $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'SECRETARIA DE FINAN�AS',0,"C",0);

            $this->objpdf->SetXY(142,$y);
            $this->objpdf->MultiCell(70,4,'VISTO'."\n\n\n".'',0,"C",0);

            $this->objpdf->setfillcolor(0,0,0);
            $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
            $this->objpdf->SetFont('Arial','',4);
            $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
            $this->objpdf->setfont('Arial','',11);
          }else{
            $this->objpdf->text(110,$xlin+297,'Continua na P�gina '.($this->objpdf->PageNo()+1));
          }
          $this->objpdf->addpage();
          $pagina += 1;

          $this->objpdf->settopmargin(1);
          $xlin = 20;
          $xcol = 4;

          $this->objpdf->setfillcolor(245);
          $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
          $this->objpdf->setfillcolor(255,255,255);
          $this->objpdf->Setfont('Arial','B',11);
          $this->objpdf->text(150,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
          $this->objpdf->text(159,$xlin-8,db_formatar($this->numaut,'s','0',6,'e'));
          $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
          $this->objpdf->Setfont('Arial','B',9);
          $this->objpdf->text(40,$xlin-15,$this->prefeitura);
          $this->objpdf->Setfont('Arial','',9);
          $this->objpdf->text(40,$xlin-11,$this->enderpref);
          $this->objpdf->text(40,$xlin-8,$this->municpref);
          $this->objpdf->text(40,$xlin-5,$this->telefpref);
          $this->objpdf->text(40,$xlin-2,$this->emailpref);
          $xlin = -30;
          $this->objpdf->Setfont('Arial','B',8);

//  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
          $this->objpdf->rect($xcol,$xlin+54,20,6,2,'DF','12');
          $this->objpdf->rect($xcol+20,$xlin+54,122,6,2,'DF','12');
          $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
          $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

//  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
          $this->objpdf->rect($xcol,$xlin+60,20,262,2,'DF','34');
          $this->objpdf->rect($xcol+20,$xlin+60,122,262,2,'DF','34');
          $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
          $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

          $this->objpdf->sety($xlin+66);
          $alt = 4;

//	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
          $this->objpdf->text($xcol+0.5,$xlin+58,'QUANTIDADE');
          $this->objpdf->text($xcol+65,$xlin+58,'MATERIAL OU SERVI�O');
          $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNIT�RIO');
          $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');
          $this->objpdf->text($xcol+38,$xlin+63,'Continua��o da P�gina '.($this->objpdf->PageNo()-1));

          $maiscol = 0;

        }

      }

      if ($pagina == 1){
        $this->objpdf->rect($xcol,$xlin+183,152,6,2,'DF','34');
        $this->objpdf->rect($xcol+152,$xlin+183,25,6,2,'DF','34');
        $this->objpdf->rect($xcol+177,$xlin+183,25,6,2,'DF','34');

        $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
        $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
        $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
        $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');


//	   $this->objpdf->setfillcolor(0,0,0);
        $this->objpdf->SetFont('Arial','',7);
        $this->objpdf->text($xcol+2,$xlin+187,'DESTINO : ',0,1,'L',0);
        $this->objpdf->text($xcol+30,$xlin+187,$this->destino,0,1,'L',0);

        $this->objpdf->setxy($xcol+1,$xlin+165);
        $this->objpdf->text($xcol+2,$xlin+164,'RESUMO : ',0,1,'L',0);
        $this->objpdf->multicell(147,3.5,$this->resumo);

        $this->objpdf->text($xcol+159,$xlin+187,'T O T A L',0,1,'L',0);
        $this->objpdf->setxy($xcol+185,$xlin+182);
        $this->objpdf->cell(30,10,db_formatar($xtotal,'f'),0,0,'f');

        $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
        $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
        $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
        $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
        $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
        $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');

        $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
        $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
        $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);

        $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);

        $this->objpdf->SetFont('Arial','',6);
        $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
        $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
        $this->objpdf->text($xcol+19,$xlin+227,'T�CNICO CONT�BIL');
        $this->objpdf->text($xcol+13,$xlin+240,'SECRET�RIO(A) DA FAZENDA');

        $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
        $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');

        $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
        $this->objpdf->text($xcol+170,$xlin+207,'DATA');
        $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
        $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
        $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
        $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');

        $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');

        $this->objpdf->SetFont('Arial','',7);
        $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
        $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNIC�PIO DE '.$this->municpref.', A IMPORT�NCIA ABAIXO ESPECIFICADA, REFERENTE �:');
        $this->objpdf->text($xcol+2,$xlin+257,'(     ) PARTE DO VALOR EMPENHADO');
        $this->objpdf->text($xcol+102,$xlin+257,'(     ) SALDO/TOTAL EMPENHADO');
        $this->objpdf->text($xcol+2,$xlin+261,'R$');
        $this->objpdf->text($xcol+102,$xlin+261,'R$');
        $this->objpdf->text($xcol+2,$xlin+265,'EM ________/________/________',0,0,'C',0);
        $this->objpdf->text($xcol+42,$xlin+265,'_________________________________________',0,0,'C',0);
        $this->objpdf->text($xcol+102,$xlin+265,'EM ________/________/________',0,0,'C',0);
        $this->objpdf->text($xcol+142,$xlin+265,'_________________________________________',0,1,'C',0);
        $this->objpdf->SetFont('Arial','',6);
        $this->objpdf->text($xcol+62,$xlin+269,'CREDOR',0,0,'C',0);
        $this->objpdf->text($xcol+162,$xlin+269,'CREDOR',0,1,'C',0);

        $this->objpdf->SetFont('Arial','',4);
        $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
        $this->objpdf->setfont('Arial','',11);
        $xlin = 169;
      }
    }else if ( $this->modelo == 7 ) {

////////// MODELO 7  -  ORDEM DE PAGAMENTO

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $pagina = 1;
      $xlin = 20;
      $xcol = 4;
      $ano = $this->ano;

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',10);
      $this->objpdf->text(128,$xlin-13,'ORDEM DE PAGAMENTO N'.CHR(176).': ');
      $this->objpdf->text(177,$xlin-13,db_formatar($this->ordpag,'s','0',6,'e'));
      $this->objpdf->text(134,$xlin-8,'DATA DE EMISS�O : ');
      $this->objpdf->text(175,$xlin-8,$this->emissao);
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin-8,$this->municpref);
      $this->objpdf->text(40,$xlin-5,$this->telefpref);
      $this->objpdf->text(40,$xlin-2,$this->emailpref);

      /// retangulo dos dados da dota��o
      $this->objpdf->rect($xcol,$xlin+2,$xcol+100,35,2,'DF','1234');
      $this->objpdf->Setfont('Arial','B',8);
      if($ano < 2005){
        $this->objpdf->text($xcol+2,$xlin+19,'RESTOS A PAGAR ');
      }else{
        $this->objpdf->text($xcol+2,$xlin+7,'�rgao');
        $this->objpdf->text($xcol+2,$xlin+11,'Unidade');
        $this->objpdf->text($xcol+2,$xlin+15,'Fun��o');

        $this->objpdf->text($xcol+2,$xlin+19,'Proj/Ativ');
        $this->objpdf->text($xcol+2,$xlin+23,'Dota��o');
        $this->objpdf->text($xcol+2,$xlin+27,'Elemento');
        $this->objpdf->text($xcol+2,$xlin+34,'Recurso');

        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
        $this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
        $this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);

        $this->objpdf->text($xcol+17,$xlin+19,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
        $this->objpdf->text($xcol+17,$xlin+23,':  '.$this->dotacao);
        $this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->elemento,'elemento'));
        $this->objpdf->text($xcol+17,$xlin+30,'   '.$this->descr_elemento);
        $this->objpdf->text($xcol+17,$xlin+34,':  '.$this->recurso.' - '.$this->descr_recurso);
      }

      //// retangulo dos dados do credor
      $this->objpdf->rect($xcol+106,$xlin+2,96,23,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+107,$xlin+9,'Numcgm');
      $this->objpdf->text($xcol+107,$xlin+13,'Nome');
      $this->objpdf->text($xcol+107,$xlin+17,'Endere�o');
      $this->objpdf->text($xcol+107,$xlin+21,'Munic�pio');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+124,$xlin+9,': '.$this->numcgm);
      $this->objpdf->text($xcol+124,$xlin+13,': '.$this->nome);
      $this->objpdf->text($xcol+124,$xlin+17,': '.$this->ender.'  '.$this->compl);
      $this->objpdf->text($xcol+124,$xlin+21,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);

      ///// retangulo do empenho
      $this->objpdf->rect($xcol+106,$xlin+28,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+155,$xlin+28,47,9,2,'DF','1234');

      ///// retangulo dos itens
      $this->objpdf->rect($xcol+102,$xlin+ 98, 25, 7,2,'DF','');
      $this->objpdf->rect($xcol+127,$xlin+ 98, 25, 7,2,'DF','');
      $this->objpdf->rect($xcol+152,$xlin+ 98, 25, 7,2,'DF','');
      $this->objpdf->rect($xcol+177,$xlin+ 98, 25, 7,2,'DF','');
      $this->objpdf->rect($xcol+000,$xlin+ 98,102,24,2,'DF','34');
      $this->objpdf->rect($xcol+000,$xlin+ 48,102,50,2,'DF','12');
      $this->objpdf->rect($xcol+102,$xlin+ 48, 25,50,2,'DF','12');
      $this->objpdf->rect($xcol+127,$xlin+ 48, 25,50,2,'DF','12');
      $this->objpdf->rect($xcol+152,$xlin+ 48, 25,50,2,'DF','12');
      $this->objpdf->rect($xcol+177,$xlin+ 48, 25,50,2,'DF','12');
      $this->objpdf->rect($xcol+102,$xlin+105, 75,17,2,'DF','34');
      $this->objpdf->rect($xcol+177,$xlin+105, 25,17,2,'DF','34');

      ///// retangulo das reten��es
      $this->objpdf->rect($xcol+177,$xlin+179, 25, 8,2,'DF','34');
      $this->objpdf->rect($xcol+177,$xlin+171, 25, 8,2,'DF','');
      $this->objpdf->rect($xcol+000,$xlin+133, 75,46,2,'DF','12');
      $this->objpdf->rect($xcol+000,$xlin+179, 75, 8,2,'DF','34');
      $this->objpdf->rect($xcol+75 ,$xlin+133, 25,46,2,'DF','12');
      $this->objpdf->rect($xcol+75 ,$xlin+179, 25, 8,2,'DF','34');
      $this->objpdf->rect($xcol+102,$xlin+133, 75,38,2,'DF','12');
      $this->objpdf->rect($xcol+102,$xlin+171, 75, 8,2,'DF','');
      $this->objpdf->rect($xcol+102,$xlin+179, 75, 8,2,'DF','34');
      $this->objpdf->rect($xcol+177,$xlin+133, 25,38,2,'DF','12');
//        $this->objpdf->Roundedrect($xcol+177,$xlin+179, 25,5,2,'DF','34');


      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+108,$xlin+30,'Empenho N'.chr(176));
      $this->objpdf->text($xcol+157,$xlin+30,'Valor do Empenho');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+130,$xlin+34,db_formatar($this->numemp,'s','0',6,'e'));
      $this->objpdf->text($xcol+180,$xlin+34,db_formatar($this->empenhado,'f'));

      //// retangulos do titulo do corpo do empenho
//	$this->objpdf->line($xcol,$xlin+42,$xcol+202,$xlin+42);


      $this->objpdf->Setfont('Arial','B',10);
      $this->objpdf->text($xcol+2,$xlin+46,'Dados da Ordem de Compra');
      $this->objpdf->Setfont('Arial','B',6);

      //// t�tulo do corpo do empenho
      $maiscol = 0;

      /// monta os dados dos elementos da ordem de compra
      $this->objpdf->SetWidths(array(20,80,25,25,25,25));
      $this->objpdf->SetAligns(array('L','L','R','R','R','R'));
      $this->objpdf->setleftmargin(4);
      $this->objpdf->sety($xlin+48);
      $this->objpdf->cell(20,4,'ELEMENTO',0,0,"L");
      $this->objpdf->cell(80,4,'DESCRI��O',0,0,"L");
      $this->objpdf->cell(25,4,'VALOR',0,0,"R");
      $this->objpdf->cell(25,4,'ANULADO',0,0,"R");
      $this->objpdf->cell(25,4,'PAGO',0,0,"R");
      $this->objpdf->cell(25,4,'SALDO',0,1,"R");
      $this->objpdf->Setfont('Arial','',7);
      $total_pag = 0;
      $total_emp = 0;
      $total_anu = 0;
      $total_sal = 0;
      for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
        db_fieldsmemory($this->recorddositens,$ii);

        $this->objpdf->Setfont('Arial','',7);
        $this->objpdf->Row(array(
          ($ano < 2005?' ':pg_result($this->recorddositens,$ii,$this->elementoitem)),
          ($ano < 2005?'RESTOS A PAGAR':pg_result($this->recorddositens,$ii,$this->descr_elementoitem)),
          db_formatar(pg_result($this->recorddositens,$ii,$this->vlremp),'f'),
          db_formatar(pg_result($this->recorddositens,$ii,$this->vlranu),'f'),
          db_formatar(pg_result($this->recorddositens,$ii,$this->vlrpag),'f'),
          db_formatar(pg_result($this->recorddositens,$ii,$this->vlrsaldo),'f')),3,false,3);
        $total_emp  += pg_result($this->recorddositens,$ii,$this->vlremp);
        $total_anu  += pg_result($this->recorddositens,$ii,$this->vlranu);
        $total_pag  += pg_result($this->recorddositens,$ii,$this->vlrpag);
        $total_sal  += pg_result($this->recorddositens,$ii,$this->vlrsaldo);
      }


      /// monta os dados das reten��es da ordem de compra
      $this->objpdf->SetWidths(array(10,62,25));
      $this->objpdf->SetAligns(array('C','L','R'));
      $this->objpdf->setleftmargin(4);
      $this->objpdf->setxy($xcol+102,$xlin+134);
      $this->objpdf->Setfont('Arial','B',10);
      $this->objpdf->text($xcol+104,$xlin+131,'Dados das Reten��es');
      $this->objpdf->text($xcol+2,$xlin+131,'Repasses');
      $this->objpdf->Setfont('Arial','b',7);
      $this->objpdf->cell(10,4,'REC.',0,0,"L");
      $this->objpdf->cell(62,4,'DESCRI��O',0,0,"L");
      $this->objpdf->cell(25,4,'VALOR',0,1,"R");
      $this->objpdf->Setfont('Arial','',7);
      $total_ret = 0;
      for($ii = 0;$ii < $this->linhasretencoes ;$ii++) {
        $this->objpdf->setx($xcol+102);
        db_fieldsmemory($this->recordretencoes,$ii);
        $this->objpdf->Setfont('Arial','',7);
        $this->objpdf->Row(array(
          pg_result($this->recordretencoes,$ii,$this->receita),
          pg_result($this->recordretencoes,$ii,$this->dreceita),
          db_formatar(pg_result($this->recordretencoes,$ii,$this->vlrrec),'f')),3,false,3);
        $total_ret += pg_result($this->recordretencoes,$ii,$this->vlrrec);
      }





      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->setxy($xcol+100,$xlin+100);
      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->cell(25,4,db_formatar($total_emp,'f'),0,0,"R");
      $this->objpdf->cell(25,4,db_formatar($total_anu,'f'),0,0,"R");
      $this->objpdf->cell(25,4,db_formatar($total_pag,'f'),0,0,"R");
      $this->objpdf->cell(25,4,db_formatar($total_sal,'f'),0,1,"R");


      $this->objpdf->setxy($xcol+127,$xlin+107);
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->cell(50,5,'TOTAL DA ORDEM',0,0,"R");
      $this->objpdf->cell(23,5,db_formatar($total_emp-$total_anu,'f'),0,1,"R");
      $this->objpdf->setx($xcol+127);
      $this->objpdf->cell(50,5,'OUTRAS ORDENS',0,0,"R");
      $this->objpdf->cell(23,4,db_formatar($this->outrasordens,'f'),0,1,"R");
      $this->objpdf->setx($xcol+127);
      $this->objpdf->cell(50,5,'VALOR RESTANTE',0,0,"R");
      $this->objpdf->cell(23,4,db_formatar($this->empenhado - $this->outrasordens - $total_emp - $total_anu ,'f'),0,1,"R");
      $this->objpdf->Setfont('Arial','b',8);
      $this->objpdf->text($xcol+2,$xlin+102,'OBSERVA��ES :');
      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->setxy($xcol,$xlin+103);
      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->multicell(102,4,$this->obs);

      /// total das reten��es
      $this->objpdf->setxy($xcol+127,$xlin+172);
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->cell(50,5,'TOTAL ',0,0,"R");
      $this->objpdf->cell(23,5,db_formatar($total_ret,'f'),0,1,"R");

      /// total dos repasses
      $this->objpdf->setxy($xcol,$xlin+181);
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->cell(75,5,'TOTAL ',0,0,"R");
      $this->objpdf->cell(23,5,db_formatar(0,'f'),0,1,"R");

      /// liquido da ordem de compra
      $this->objpdf->setxy($xcol+127,$xlin+181);
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->cell(50,5,'L�QUIDO DA ORDEM DE COMPRA ',0,0,"R");
      $this->objpdf->cell(23,5,db_formatar($total_sal - $total_ret,'f'),0,1,"R");


      $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
      $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
      $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
      $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','');


      $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
      $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
      $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
      $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
      $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
      $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');


      $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
      $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
      $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);

      $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);

      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
      $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
      $this->objpdf->text($xcol+19,$xlin+227,'T�CNICO CONT�BIL');
      $this->objpdf->text($xcol+13,$xlin+240,'SECRET�RIO(A) DA FAZENDA');

      $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
      $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');

      $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
      $this->objpdf->text($xcol+170,$xlin+207,'DATA');
      $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
      $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
      $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
      $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');

      $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');

      $this->objpdf->SetFont('Arial','',7);
      $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
      $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNIC�PIO DE '.$this->municpref.', A IMPORT�NCIA ABAIXO ESPECIFICADA, REFERENTE �:');
      $this->objpdf->text($xcol+2,$xlin+257,'(     ) PARTE DO VALOR EMPENHADO');
      $this->objpdf->text($xcol+102,$xlin+257,'(     ) SALDO/TOTAL EMPENHADO');
      $this->objpdf->text($xcol+2,$xlin+261,'R$');
      $this->objpdf->text($xcol+102,$xlin+261,'R$');
      $this->objpdf->text($xcol+2,$xlin+265,'EM ________/________/________',0,0,'C',0);
      $this->objpdf->text($xcol+42,$xlin+265,'_________________________________________',0,0,'C',0);
      $this->objpdf->text($xcol+102,$xlin+265,'EM ________/________/________',0,0,'C',0);
      $this->objpdf->text($xcol+142,$xlin+265,'_________________________________________',0,1,'C',0);
      $this->objpdf->SetFont('Arial','',6);
      $this->objpdf->text($xcol+62,$xlin+269,'CREDOR',0,0,'C',0);
      $this->objpdf->text($xcol+162,$xlin+269,'CREDOR',0,1,'C',0);

      $this->objpdf->SetFont('Arial','',4);
      $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
      $this->objpdf->setfont('Arial','',11);
      $xlin = 169;

    }else if ( $this->modelo == 8 ) {

      //// Ficha de transferencia de bens


      $this->objpdf->AliasNbPages();
//	$this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $this->objpdf->line(2,148.5,208,148.5);
      $xlin = 20;
      $xcol = 4;
      $comeco = 0;
      $passada = 0;
      if ($this->linhasbens < 40)
        $vias = 2;
      elseif ($this->linhasbens < 80)
        $vias = 4;
      elseif ($this->linhasbens < 120)
        $vias = 6;
      elseif ($this->linhasbens < 160)
        $vias = 8;
      elseif ($this->linhasbens < 200)
        $vias = 10;
      for ($i = 0;$i < $vias;$i++){
        if (($i % 2 ) == 0)
          $this->objpdf->AddPage();
        $this->objpdf->setfillcolor(245);
        $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
        $this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
        $this->objpdf->Setfont('Arial','B',11);
        $this->objpdf->text(150,$xlin-13,'TRANSFER�NCIA N'.chr(176).'  '.$this->codtransf);
        $this->objpdf->text(159,$xlin-8,$this->datacalc);
        $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
        $this->objpdf->Setfont('Arial','B',9);
        $this->objpdf->text(40,$xlin-15,$this->prefeitura);
        $this->objpdf->Setfont('Arial','',9);
        $this->objpdf->text(40,$xlin-11,$this->enderpref);
        $this->objpdf->text(40,$xlin-8,$this->municpref);
        $this->objpdf->text(40,$xlin-5,$this->telefpref);
        $this->objpdf->text(40,$xlin-2,$this->emailpref);
//		$this->objpdf->setfillcolor(245);

        $this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+98,20,2,'DF','1234');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+2,$xlin+5,'Origem:');
        $this->objpdf->Setfont('Arial','b',8);
        $this->objpdf->text($xcol+2,$xlin+9,'Departamento ');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+22,$xlin+9,':  '.$this->origem);
        $this->objpdf->Setfont('Arial','b',8);
        $this->objpdf->text($xcol+2,$xlin+16,'Usuario');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+22,$xlin+16,':  '.$this->usuario);
        $this->objpdf->Setfont('Arial','',6);

        $this->objpdf->Roundedrect($xcol+104,$xlin+2,98,20,2,'DF','1234');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+106,$xlin+5,'Destino:');
        $this->objpdf->Setfont('Arial','b',8);
        $this->objpdf->text($xcol+106,$xlin+9,'Departamento');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+128,$xlin+9,':  '.$this->destino);

//		$this->objpdf->setfillcolor(245);
        $this->objpdf->Roundedrect($xcol,$xlin+24,202,70,2,'DF','1234');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+2,$xlin+27,'Itens a Transmitir :');
        $this->objpdf->Setfont('Arial','b',8);
        $this->objpdf->text($xcol+2,$xlin+30,'BEM');
        $this->objpdf->text($xcol+25,$xlin+30,'DESCRI��O');
        $this->objpdf->text($xcol+75,$xlin+30,'CLASSIFICA��O');
        $this->objpdf->text($xcol+102,$xlin+30,'BEM');
        $this->objpdf->text($xcol+125,$xlin+30,'DESCRI��O');
        $this->objpdf->text($xcol+175,$xlin+30,'CLASSIFICA��O');
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->sety($xlin+31);
        $maiscol = 0;
        $yy = $this->objpdf->gety();
        for($ii = $comeco;$ii < $this->linhasbens ;$ii++) {
          if (($ii % 40 ) == 0 && $ii > 0 && $passada == 0){
            $maiscol = 0;
            $passada ++;
            $comeco = $ii;
            break;
          }elseif (($ii % 20 ) == 0 && $ii > 0 && ($ii % 40 ) != 0){
            $maiscol = 100;
            $this->objpdf->sety($yy);
          }

          $this->objpdf->setx($xcol+3+$maiscol);
          $this->objpdf->cell(5,3,trim(pg_result($this->recordbens,$ii,$this->bem)),0,0,"R",0);
          $this->objpdf->cell(70,3,trim(pg_result($this->recordbens,$ii,$this->descr_bem)),0,0,"L",0);
          $this->objpdf->cell(15,3,pg_result($this->recordbens,$ii,$this->class_bem),0,1,"R",0);
          if(($ii+1) == $this->linhasbens ){
            $comeco = 0;
            $passada = 0;
            break;
          }
        }
        $this->objpdf->line($xcol+10,$xlin+116,$xcol+70,$xlin+116);
        $this->objpdf->text($xcol+30,$xlin+120,'TRANSMITENTE');
        $this->objpdf->line($xcol+135,$xlin+116,$xcol+195,$xlin+116);
        $this->objpdf->text($xcol+155,$xlin+120,'RECEBEDOR');

        if (($i % 2 ) == 0)
          $xlin = 169;
        else
          $xlin = 20;

      }

    }else if ( $this->modelo == 9 ) {

      $this->objpdf->SetTextColor(0,0,0);
      $this->objpdf->SetFont('Arial','B',12);
      $coluna = 44;
      $linha = 35;
      $this->objpdf->SetLineWidth(1);
      $this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
      $this->objpdf->SetLineWidth(0.5);
      $this->objpdf->roundedrect(39,2,133,191,2,'1234');
      $this->objpdf->SetLineWidth(0.2);
      $this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
      $this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);

//	$this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
//	$this->objpdf->roundedrect(42,$linha+72,127,15,2,'1234'); // obs da atividade principal

//  	$this->objpdf->roundedrect(42,$linha+88,127,5,2,'1234'); // descricao da atividade secundaria
//	$this->objpdf->roundedrect(42,$linha+94,127,15,2,'1234'); // obs da atividade secundaria

//	$this->objpdf->setdrawcolor(235);

      $this->objpdf->setxy(65,15);
      $this->objpdf->setfont('Arial','B',13);
      $this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

      $this->objpdf->setxy(65,23);
      $this->objpdf->setfont('Arial','B',13);
      $this->objpdf->setleftmargin(50);
      $this->objpdf->setrightmargin(50);
      $this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

      $this->objpdf->Ln(6);
      $this->objpdf->sety(38);
      $this->objpdf->SetFont('Arial','',11);
      $this->objpdf->multicell(0,5,db_geratexto($this->texto),0,"J",0,$db02_inicia);
//	$this->objpdf->multicell(0,6,db_geratexto($db02_texto),0,"J",0,$db02_inicia);

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+35,'INSCRI��O:'); // inscricao

      if ($this->processo > 0) {
        $this->objpdf->Text($coluna + 70,$linha+35,'PROCESSO:'); // inscricao
      }

      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+35,$this->nrinscr); // inscricao

      if ($this->processo > 0) {
        $this->objpdf->Text($coluna + 90,$linha+35,$this->processo); // processo
      }

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+39,"NOME/RAZAO SOCIAL: "); // nome
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+39,$this->nome); // nome

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+43,"CNPJ/CPF: ");
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+43,$this->cnpjcpf);


      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+47,"ENDERE�O: "); // endereco
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+47,$this->ender); // endereco

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+51,"N�MERO: "); // endereco
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+51,($this->numero == ""?"":$this->numero));

      if ($this->compl != "") {
        $this->objpdf->SetFont('Arial','B',9);
        $this->objpdf->Text($coluna + 60 ,$linha+51,"COMPLEMENTO: "); // endereco
        $this->objpdf->SetFont('Arial','',9);
        $this->objpdf->Text($coluna + 90,$linha+51,($this->compl == ""?"":$this->compl));
      }

      $this->objpdf->SetFont('Arial','B',9);
      $this->objpdf->Text($coluna,$linha+55,"DATA DE INCLUSAO: ");
      if ($this->datafim != "") {
        $this->objpdf->Text($coluna + 60,$linha+55,"VALIDADE AT�: ");
      }
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Text($coluna + 40,$linha+55,db_formatar($this->datainc,'d'));
      if ($this->datafim != "") {
        $this->objpdf->Text($coluna + 85,$linha+55,db_formatar($this->datafim,'d'));
      }

      $this->objpdf->setx(40);

      if($this->q02_memo!=''){
        $this->objpdf->SetFont('Arial','B',9);
        $this->objpdf->Text($coluna,$linha+59,"OBSERVA��O: "); // observa��o
        $this->objpdf->SetFont('Arial','',9);
        $this->objpdf->sety($linha+60);
        $this->objpdf->Multicell(0,3,$this->q02_memo); // texto
        $this->objpdf->SetFont('Arial','B',10);
        $this->objpdf->roundedrect(42,$linha+30,127,38,2,'1234');
        $linha = 109;
      } else {
        $this->objpdf->roundedrect(42,$linha+30,127,27,2,'1234');
        $linha = 95;
      }

      $this->objpdf->sety($linha);

      $this->objpdf->roundedrect(42,$linha-1,127,5,2,'1234');
      $this->objpdf->SetFont('Arial','B',8);
      $this->objpdf->Ln(0.5);
      $this->objpdf->setx(45);
      $this->objpdf->Multicell(0,3,"ATIVIDADE PRINCIPAL: " . $this->descrativ) ; // descri��o da atividade principal
      $linha += 6;
      $obs='';
      if(isset($this->q03_atmemo[$this->ativ])){
        if ($this->q03_atmemo[$this->ativ] != '') {;
          $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade principal
          $obs = $this->q03_atmemo[$this->ativ];
          $this->objpdf->Ln(3);
          $this->objpdf->SetFont('Arial','',7);
          $this->objpdf->Multicell(0,3,$this->q03_atmemo[$this->ativ]); // texto
          $linha += 16;
        }
      }

      $this->objpdf->sety($linha);

      $num_outras=count($this->outrasativs);
      $x=105;
      if ($num_outras >0 ) {

        $x=$x+4;
        reset($this->outrasativs);
        for($i=0; $i<$num_outras; $i++){
          $yyy = $this->objpdf->gety();
          $chave=key($this->outrasativs);
          $obs='';
          if(isset($this->q03_atmemo[$chave])){
            $obs = $this->q03_atmemo[$chave];
          }

          $this->objpdf->SetFont('Arial','B',8);

          $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
          $this->objpdf->Ln(0.5);
          $this->objpdf->setx(45);
          $this->objpdf->Multicell(0,3,"ATIVIDADE SECUND�RIA: " . $this->outrasativs[$chave]); // texto
          $linha += 6;

          if($obs!=""){
            $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade secundaria
            $this->objpdf->Ln(3);
            $this->objpdf->SetFont('Arial','',7);
            $this->objpdf->Multicell(0,3,$obs); // texto
            $linha += 16;
          }

          $x=$x+4;
          next($this->outrasativs);
//             $this->objpdf->ln(2.5);
          $this->objpdf->sety($linha);
        }
      }
      $x=64;
//        if($this->q02_obs!=''){
//	  $this->objpdf->Text($coluna,$linha+$x,"OBSERVA��O: "); // descri��o da atividade principal
//	  $this->objpdf->Text($coluna + 45,$linha+$x,$this->q02_obs); // descri��o da atividade principal
//	  $x=$x+4;
//	}

//        $linha = $this->objpdf->gety();
      $this->objpdf->SetFont('Arial','B',12);
      $this->objpdf->Text($coluna+40,$linha + 5,"DATA DE EMISS�O DESTE DOCUMENTO."); // data
      $this->objpdf->Text($coluna+40,$linha + 10,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . "."); // data

      $this->objpdf->sety(125);
      $this->objpdf->SetFont('Arial','',9);
      $this->objpdf->Multicell(0,6,$this->obs); // observa��o
      $this->objpdf->setfont('arial','',6);
      $this->objpdf->SetXY($coluna-18,165);

      global $db02_texto;

      $sqlparag = "select db02_texto
		     from db_documento
		     inner join db_docparag on db03_docum = db04_docum
		     inner join db_paragrafo on db04_idparag = db02_idparag
		     where db03_docum = 26 and db02_descr like '%Assinatura Secretario%'";
      $resparag = db_query($sqlparag);

      if ( pg_numrows($resparag) == 0 ) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 26 com os paragrafos do alvara!');
        exit;
      }

      db_fieldsmemory($resparag,0);

      $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$db02_texto,0,"C",0);
      $this->objpdf->SetXY($coluna+50,165);
      $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);

//        $this->objpdf->SetXY($coluna-35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRET�RIO DA IND. COM. E TURISMO',0,"C",0);
//        $this->objpdf->SetXY($coluna+35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);


      $this->objpdf->sety(180);
      $this->objpdf->setfont('arial','B',12);
      $this->objpdf->multicell(0,8,'FIXAR EM LUGAR VIS�VEL',1,"C");
      $this->objpdf->SetFont('Arial','B',10);


    }else if ( $this->modelo == 10 ) {

////////// MODELO 10  -  ORDEM DE COMPRA

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $this->objpdf->setleftmargin(4);
      $pagina = 1;
      $xlin = 20;
      $xcol = 4;

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(130,$xlin-13,'ORDEM DE COMPRA N'.CHR(176));
      $this->objpdf->text(185,$xlin-13,db_formatar($this->numordem,'s','0',6,'e'));
      $this->objpdf->text(130,$xlin-10,'DATA :');
      $this->objpdf->text(185,$xlin-10,db_formatar($this->dataordem,'d'));
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin- 8,$this->municpref);
      $this->objpdf->text(40,$xlin- 5,$this->telefpref);
      $this->objpdf->text(40,$xlin- 2,$this->emailpref);

      $this->objpdf->rect($xcol,$xlin+2,$xcol+198,20,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+2,$xlin+4.5,'Dados do Fornecedor');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+109,$xlin+8,'Numcgm');
      $this->objpdf->text($xcol+150,$xlin+8,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
      $this->objpdf->text($xcol+  2,$xlin+8,'Nome');
      $this->objpdf->text($xcol+  2,$xlin+12,'Endere�o');
      $this->objpdf->text($xcol+102,$xlin+12,'Complemento');
      $this->objpdf->text($xcol+  2,$xlin+16,'Munic�pio');
      $this->objpdf->text($xcol+115,$xlin+16,'CEP');
      $this->objpdf->text($xcol+  2,$xlin+20,'Contato');
      $this->objpdf->text($xcol+110,$xlin+20,'Telefone');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+157,$xlin+8,' :  '.$this->cnpj);
      $this->objpdf->text($xcol+122,$xlin+8,':  '.$this->numcgm);
      $this->objpdf->text($xcol+18,$xlin+ 8,':  '.$this->nome);
      $this->objpdf->text($xcol+18,$xlin+ 12,':  '.$this->ender);
      $this->objpdf->text($xcol+122,$xlin+12,':  '.$this->compl);
      $this->objpdf->text($xcol+18,$xlin+ 16,':  '.$this->munic.'-'.$this->uf);
      $this->objpdf->text($xcol+122,$xlin+16,':  '.$this->cep);
      $this->objpdf->text($xcol+18,$xlin+ 20,':  '.$this->contato);
      $this->objpdf->text($xcol+122,$xlin+20,':  '.$this->telef_cont);
      if ($this->obs!=""){
        $this->objpdf->sety($xlin+24);

        $posicao_atual=$this->objpdf->gety();
//	  $this->objpdf->multicell(0,3,$this->obs,1);
        $this->objpdf->multicell(202,4,"Observa��es:  ".$this->obs,1);
        $posicao_depois=$this->objpdf->gety();

//	  $this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
        $xlin+=$posicao_depois-$posicao_atual+2;
      }
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->rect($xcol    ,$xlin+24,12,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 12,$xlin+24,15,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 27,$xlin+24,11,6,2,'DF','12');
      $this->objpdf->rect($xcol+ 38,$xlin+24,104,6,2,'DF','12');
      $this->objpdf->rect($xcol+142,$xlin+24,30,6,2,'DF','12');
      $this->objpdf->rect($xcol+172,$xlin+24,30,6,2,'DF','12');

      $this->objpdf->rect($xcol    ,$xlin+30,12,235  -$xlin ,2,'DF','34');
      $this->objpdf->rect($xcol+ 12,$xlin+30,15,235  -$xlin ,2,'DF','34');
      $this->objpdf->rect($xcol+ 27,$xlin+30,11,235  -$xlin ,2,'DF','34');
      $this->objpdf->rect($xcol+ 38,$xlin+30,104,235 -$xlin ,2,'DF','34');
      $this->objpdf->rect($xcol+142,$xlin+30,30,235  -$xlin ,2,'DF','');
      $this->objpdf->rect($xcol+172,$xlin+30,30,235  -$xlin ,2,'DF','34');




//	$this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');


      $this->objpdf->sety($xlin+28);
      $alt = 4;

      $this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
      $this->objpdf->text($xcol+12.5,$xlin+28,'EMPENHO');
      $this->objpdf->text($xcol+27.5,$xlin+28,'QUANT');
      $this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVI�O');
      $this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNIT�RIO');
      $this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');
      $maiscol = 0;


      $this->objpdf->setfillcolor(0,0,0);
      $this->objpdf->text($xcol+10,290,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');

      $this->objpdf->text($xcol+ 120,290,'___________________________________________');

      $this->objpdf->SetWidths(array(6,16,14,105,25,25));
      $this->objpdf->SetAligns(array('C','C','C','L','R','R'));

      $this->objpdf->setleftmargin(8);
      $this->objpdf->sety($xlin+32);

      $xtotal = 0;
      $item=1;
      for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
        db_fieldsmemory($this->recorddositens,$ii);
        $this->objpdf->Setfont('Arial','',8);
        $this->objpdf->Row(array(($item),
                                 pg_result($this->recorddositens,$ii,$this->empempenho),
                                 pg_result($this->recorddositens,$ii,$this->quantitem),
                                 pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
        $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
        $item++;
        $this->objpdf->Setfont('Arial','B',8);
        /////// troca de pagina
        if( ( $this->objpdf->gety() > $this->objpdf->h - 40 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 40 && $pagina != 1 )){
          if ($this->objpdf->PageNo() == 1){
            if ($this->obs!=""){
              $this->objpdf->text(110,290-$xlin,'Continua na P�gina '.($this->objpdf->PageNo()+1));
              //$this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');
            }else $this->objpdf->text(110,$xlin+243,'Continua na P�gina '.($this->objpdf->PageNo()+1));

          }else{
            $this->objpdf->text(110,$xlin+320,'Continua na P�gina '.($this->objpdf->PageNo()+1));
          }
          $this->objpdf->addpage();
          $pagina += 1;

          $this->objpdf->settopmargin(1);
          $xlin = 20;
          $xcol = 4;

          $this->objpdf->setfillcolor(245);
          $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
          $this->objpdf->setfillcolor(255,255,255);
          $this->objpdf->Setfont('Arial','B',9);
          $this->objpdf->text(130,$xlin-13,'ORDEM DE COMPRA N'.CHR(176));
          $this->objpdf->text(185,$xlin-13,db_formatar($this->numordem,'s','0',6,'e'));
          $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
          $this->objpdf->Setfont('Arial','B',9);
          $this->objpdf->text(40,$xlin-15,$this->prefeitura);
          $this->objpdf->Setfont('Arial','',9);
          $this->objpdf->text(40,$xlin-11,$this->enderpref);
          $this->objpdf->text(40,$xlin-8,$this->municpref);
          $this->objpdf->text(40,$xlin-5,$this->telefpref);
          $this->objpdf->text(40,$xlin-2,$this->emailpref);

          $xlin = -30;
          $this->objpdf->Setfont('Arial','B',8);

          $this->objpdf->rect($xcol    ,$xlin+54,12,6,2,'DF','12');
          $this->objpdf->rect($xcol+ 12,$xlin+54,15,6,2,'DF','12');
          $this->objpdf->rect($xcol+ 27,$xlin+54,11,6,2,'DF','12');
          $this->objpdf->rect($xcol+ 38,$xlin+54,104,6,2,'DF','12');
          $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
          $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

          $this->objpdf->rect($xcol,    $xlin+60,12,235,2,'DF','34');
          $this->objpdf->rect($xcol+ 12,$xlin+60,15,235,2,'DF','34');
          $this->objpdf->rect($xcol+ 27,$xlin+60,11,235,2,'DF','34');
          $this->objpdf->rect($xcol+ 38,$xlin+60,104,235,2,'DF','34');
          $this->objpdf->rect($xcol+142,$xlin+60,30,235,2,'DF','');
          $this->objpdf->rect($xcol+172,$xlin+60,30,235,2,'DF','34');

          $this->objpdf->sety($xlin+66);
          $alt = 4;

          $this->objpdf->text($xcol+   2,$xlin+59,'ITEM');
          $this->objpdf->text($xcol+12.5,$xlin+59,'EMPENHO');
          $this->objpdf->text($xcol+27.5,$xlin+59,'QUANT');
          $this->objpdf->text($xcol+  70,$xlin+59,'MATERIAL OU SERVI�O');
          $this->objpdf->text($xcol+ 145,$xlin+59,'VALOR UNIT�RIO');
          $this->objpdf->text($xcol+ 176,$xlin+59,'VALOR TOTAL');
          $this->objpdf->text($xcol+  40,$xlin+63,'Continua��o da P�gina '.($this->objpdf->PageNo()-1));

          $maiscol = 0;

        }
      }

      $xlin = 20;
      $xcol = 4;
      $this->objpdf->rect($xcol,    $xlin+245,142, 10,2,'DF','34');
      $this->objpdf->rect($xcol+142,$xlin+245,30, 10,2,'DF','34');
      $this->objpdf->rect($xcol+172,$xlin+245,30, 10,2,'DF','34');
      $this->objpdf->text($xcol+120 ,$xlin+251,'T O T A L');

      $this->objpdf->SetXY(172,$xlin+245);
      $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");


    }else if ( $this->modelo == 12 ) {

////////// MODELO 12  -  ANULA��O DE EMPENHO

      $this->objpdf->AliasNbPages();
      $this->objpdf->AddPage();
      $this->objpdf->settopmargin(1);
      $pagina = 1;
      $xlin = 20;
      $xcol = 4;

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',10);
      $this->objpdf->text(126,$xlin-13,'NOTA DE ANULA��O N'.CHR(176).': ');
      $this->objpdf->text(175,$xlin-13,db_formatar($this->codemp,'s','0',6,'e'));
      $this->objpdf->text(134,$xlin-8,'DATA DE EMISS�O : ');
      $this->objpdf->text(175,$xlin-8,$this->emissao);
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin-8,$this->municpref);
      $this->objpdf->text(40,$xlin-5,$this->telefpref);
      $this->objpdf->text(40,$xlin-2,$this->emailpref);

      /// retangulo dos dados da dota��o
      $this->objpdf->rect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+2,$xlin+7,'�rgao');
      $this->objpdf->text($xcol+2,$xlin+11,'Unidade');
      $this->objpdf->text($xcol+2,$xlin+15,'Fun��o');

      $this->objpdf->text($xcol+2,$xlin+22,'Proj/Ativ');
      $this->objpdf->text($xcol+2,$xlin+30,'Rubrica');
      $this->objpdf->text($xcol+2,$xlin+42,'Recurso');
      $this->objpdf->text($xcol+2,$xlin+48,'Licita��o');

      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
      $this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
      $this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);

      $this->objpdf->text($xcol+17,$xlin+22,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);

      $this->objpdf->text($xcol+17,$xlin+30,':  '.db_formatar($this->sintetico,'elemento'));
      $this->objpdf->setxy($xcol+18,$xlin+31);
      $this->objpdf->multicell(90,3,$this->descr_sintetico,0,"L");

      $this->objpdf->text($xcol+17,$xlin+42,':  '.$this->recurso.' - '.$this->descr_recurso);

      $this->objpdf->text($xcol+17,$xlin+48,':  '.$this->descr_licitacao);


      //// retangulo dos dados do credor
      $this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
      $this->objpdf->Setfont('Arial','B',8);
      $this->objpdf->text($xcol+107,$xlin+7,'Numcgm');
      $this->objpdf->text($xcol+107,$xlin+11,'Nome');
      $this->objpdf->text($xcol+107,$xlin+15,'Endere�o');
      $this->objpdf->text($xcol+107,$xlin+19,'Munic�pio');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
      $this->objpdf->text($xcol+124,$xlin+11,': '.$this->nome);
      $this->objpdf->text($xcol+124,$xlin+15,': '.$this->ender.'  '.$this->compl);
      $this->objpdf->text($xcol+124,$xlin+19,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);

      ///// retangulo dos valores
      $this->objpdf->rect($xcol+106,$xlin+21.5,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+155,$xlin+21.5,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+106,$xlin+32.0,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+155,$xlin+32.0,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+106,$xlin+42.5,47,9,2,'DF','1234');
      $this->objpdf->rect($xcol+155,$xlin+42.5,47,9,2,'DF','1234');
      $this->objpdf->Setfont('Arial','',6);
      $this->objpdf->text($xcol+157,$xlin+23.5,'Valor Empenhado');
      $this->objpdf->text($xcol+108,$xlin+34.0,'Valor Or�ado');
      $this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
      $this->objpdf->text($xcol+108,$xlin+44.5,'Valor Anulado');
      $this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
      $this->objpdf->Setfont('Arial','',8);
      $this->objpdf->text($xcol+108,$xlin+27,'SEQ. EMP. N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
      $this->objpdf->text($xcol+180,$xlin+27.5,db_formatar($this->empenhado,'f'));
      $this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
      $this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
      $this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->anulado,'f'));
      $this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_ant + $this->anulado,'f'));

      /// retangulo do corpo do empenho
      $this->objpdf->rect($xcol,$xlin+60,15,130,2,'DF','');
      $this->objpdf->rect($xcol+15,$xlin+60,137,130,2,'DF','');
      $this->objpdf->rect($xcol+152,$xlin+60,25,163,2,'DF','');
      $this->objpdf->rect($xcol+177,$xlin+60,25,163,2,'DF','');
      $this->objpdf->rect($xcol,$xlin+190,152,33,2,'DF','');

      //// retangulos do titulo do corpo do empenho
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
      $this->objpdf->rect($xcol+15,$xlin+54,137,6,2,'DF','12');
      $this->objpdf->rect($xcol+152,$xlin+54,25,6,2,'DF','12');
      $this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

      //// t�tulo do corpo do empenho
      $this->objpdf->text($xcol+2,$xlin+58,'QUANT');
      $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVI�O');
      $this->objpdf->text($xcol+154,$xlin+58,'VALOR UNIT�RIO');
      $this->objpdf->text($xcol+181,$xlin+58,'VALOR TOTAL');
      $maiscol = 0;

      /// monta os dados para itens do empenho
      $this->objpdf->SetWidths(array(15,137,25,25));
      $this->objpdf->SetAligns(array('C','L','R','R'));

      $this->objpdf->setleftmargin(4);
      $this->objpdf->sety($xlin+62);
      $this->objpdf->Setfont('Arial','',7);
      $ele = 0;
      $xtotal = 0;
      for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
        db_fieldsmemory($this->recorddositens,$ii);
        $this->objpdf->Setfont('Arial','B',7);
        $this->objpdf->Row(array('',
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),
                                 '',
                                 db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
        $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
        /////// troca de pagina

      }

      if ($pagina == 1){
        $this->objpdf->rect($xcol,$xlin+223,152,6,2,'DF','34');
        $this->objpdf->rect($xcol+152,$xlin+223,25,6,2,'DF','34');
        $this->objpdf->rect($xcol+177,$xlin+223,25,6,2,'DF','34');

//           $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
//           $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
//           $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
//           $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');


//	   $this->objpdf->setfillcolor(0,0,0);
        $this->objpdf->SetFont('Arial','',7);
        $this->objpdf->text($xcol+2,$xlin+227,'DESTINO : ',0,1,'L',0);
        $this->objpdf->text($xcol+30,$xlin+227,$this->destino,0,1,'L',0);

        $this->objpdf->setxy($xcol+1,$xlin+195);
        $this->objpdf->text($xcol+2,$xlin+194,'MOTIVO : ',0,1,'L',0);
        $this->objpdf->multicell(147,3.5,$this->resumo);

        $this->objpdf->text($xcol+159,$xlin+227,'T O T A L',0,1,'L',0);
        $this->objpdf->setxy($xcol+185,$xlin+222);
        $this->objpdf->cell(30,10,db_formatar($xtotal,'f'),0,0,'f');
        /*
                   $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
                   $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
                   $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
             $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
             $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
             $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');

             $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
             $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
             $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);

             $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);

             $this->objpdf->SetFont('Arial','',6);
             $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
             $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
             $this->objpdf->text($xcol+19,$xlin+227,'T�CNICO CONT�BIL');
             $this->objpdf->text($xcol+13,$xlin+240,'SECRET�RIO(A) DA FAZENDA');

             $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
             $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');

             $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
             $this->objpdf->text($xcol+170,$xlin+207,'DATA');
             $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
             $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
             $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
             $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');

                   $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');

             $this->objpdf->SetFont('Arial','',7);
             $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
             $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNIC�PIO DE '.$this->municpref.', A IMPORT�NCIA ABAIXO ESPECIFICADA, REFERENTE �:');
             $this->objpdf->text($xcol+2,$xlin+257,'(     ) PARTE DO VALOR EMPENHADO');
             $this->objpdf->text($xcol+102,$xlin+257,'(     ) SALDO/TOTAL EMPENHADO');
             $this->objpdf->text($xcol+2,$xlin+261,'R$');
             $this->objpdf->text($xcol+102,$xlin+261,'R$');
             $this->objpdf->text($xcol+2,$xlin+265,'EM ________/________/________',0,0,'C',0);
             $this->objpdf->text($xcol+42,$xlin+265,'_________________________________________',0,0,'C',0);
             $this->objpdf->text($xcol+102,$xlin+265,'EM ________/________/________',0,0,'C',0);
             $this->objpdf->text($xcol+142,$xlin+265,'_________________________________________',0,1,'C',0);
             $this->objpdf->SetFont('Arial','',6);
             $this->objpdf->text($xcol+62,$xlin+269,'CREDOR',0,0,'C',0);
             $this->objpdf->text($xcol+162,$xlin+269,'CREDOR',0,1,'C',0);

             $this->objpdf->SetFont('Arial','',4);
                   $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
             $this->objpdf->setfont('Arial','',11);
        */
        $xlin = 169;
      }
    }else{
      echo "<script>alert('Modelo de carne($this->modelo) n�o definido')</script>";
      exit;
    }
  }
}
?>
