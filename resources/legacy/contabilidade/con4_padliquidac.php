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

/**
 * plugin
 */
if (file_exists('model/ContratosPADRS.model.php')) {
  require_once ('model/ContratosPADRS.model.php');
}

class liquidac {

  var $arq=null;

  function liquidac($header){
    umask(74);
    $this->arq = fopen("tmp/LIQUIDAC.TXT",'w+');
    fputs($this->arq,$header);
    fputs($this->arq,"\r\n");
  }

  public function processa($instit=1,$data_ini="",$data_fim="",$tribinst=null,$subelemento="") {

    global $contador,$nomeinst;
    $contador = 0;

    $iAnoSessao = db_getsession("DB_anousu");

    $sele = " ( $instit ) ";

    $sql = "
      select e60_anousu  as ano,
	           trim(e60_codemp)::integer   as c75_numemp,
	           e60_numemp,
	           c75_codlan,
	           c75_data,
	           round(c70_valor,2)::float8  as c70_valor,
	           case when c53_tipo = 20
	             then  '+'
	             else  '-'
             end as sinal,
             'Liquida��o N�mero :'||c70_codlan as historico,
             c75_codlan as operacao,
             e60_instit
      	from conlancamemp
             inner join conlancam    on c70_codlan = c75_codlan
             inner join empempenho   on e60_numemp = c75_numemp
             inner join conlancamdoc on c71_codlan = c75_codlan
             inner join conhistdoc   on c53_coddoc = c71_coddoc
                                    and (c53_tipo = 20 or c53_tipo = 21)
       where (c75_data between '{$data_ini}' and '{$data_fim}')
         and e60_emiss  <= '{$data_fim}'
         and e60_anousu  = {$iAnoSessao}
         and e60_instit in {$sele}

       union all

      select e60_anousu as ano,
             trim(e60_codemp)::integer as c75_numemp,
             e60_numemp,
             0 as c75_codlan,
             e60_emiss as c75_data,
             round((e91_vlrliq-e91_vlrpag),2)::float8 as c70_valor,
             '+' as sinal,
             e60_resumo as historico,
             0 as operacao,
             e60_instit
        from empresto
             inner join empempenho on e60_numemp = e91_numemp
       where e91_anousu = {$iAnoSessao}
         and e60_instit in {$sele}
         and e91_rpcorreto is false

       union all

      select e60_anousu as ano,
             trim(e60_codemp)::integer as c75_numemp,
             e60_numemp,
             c75_codlan,
             c75_data,
             round(c70_valor,2)::float8 as c70_valor,
             case when c53_tipo = 20 then
                '+'  else '-'
             end as sinal,
             'Liquida��o N�mero :'||c70_codlan as historico,
             c75_codlan as operacao,
             e60_instit
        from empresto
    	       inner join conlancamemp  on e91_numemp = c75_numemp
    	       inner join conlancam     on c70_codlan = c75_codlan
    	       inner join empempenho    on e60_numemp = c75_numemp
             inner join conlancamdoc  on c71_codlan = c75_codlan
    	       inner join conhistdoc    on c53_coddoc = c71_coddoc
                                     and (c53_tipo = 20 or c53_tipo = 21)
       where 1=1
      /* and e91_rpcorreto is true */
    	   and (c75_data between '{$data_ini}' and '{$data_fim}')
    	   and e60_emiss <= '{$data_fim}'
    	   and e60_anousu < {$iAnoSessao}
    	   and e60_instit in {$sele}
    	   and e91_anousu = {$iAnoSessao}

	     union all

      select e60_anousu as ano,
             trim(e60_codemp)::integer as c75_numemp,
             e60_numemp,
             c75_codlan,
             c75_data,
             round(c70_valor,2)::float8 as c70_valor,
              '-' as sinal,
             'Liquida��o N�mero :'||c70_codlan as historico,
             c75_codlan as operacao,
             e60_instit
        from conlancamemp
             inner join conlancam     on c70_codlan = c75_codlan
             inner join empempenho    on e60_numemp = c75_numemp
             inner join conlancamdoc  on c71_codlan = c75_codlan
       where c75_data between '{$data_ini}' and '{$data_fim}'
         and c71_coddoc = 31
         and e60_emiss <= '{$data_fim}'
         and e60_anousu < {$iAnoSessao}
         and e60_instit in {$sele}
       order by ano, c75_numemp ";


    $sql = "select * from ($sql) as x order by x.c75_data";

    $res  = db_query($sql);
    $rows = pg_numrows($res);

    for ($x = 0; $x < $rows; $x++) {

      $oStdInformacao = db_utils::fieldsMemory($res, $x);

      $ano         = $oStdInformacao->ano;
      $instituicao = $oStdInformacao->e60_instit;
      $empenho     = $ano.str_pad($instituicao, 2, "0", STR_PAD_LEFT)."0".formatar($oStdInformacao->c75_numemp,6,'n');
      $liquidacao  = formatar($oStdInformacao->c75_codlan, 20, 'n');
      $data        = formatar($oStdInformacao->c75_data, 8, 'd');
      $valor       = formatar($oStdInformacao->c70_valor, 13, 'v');
      $sinal       = $oStdInformacao->sinal;
      $sObsoleto   = str_pad(" ", 165, " ", STR_PAD_RIGHT);
      $hist        = $oStdInformacao->historico;

      $hist = trim($hist);
      if (empty($hist)) $hist = "Sem Resumo";

      $hist      = addcslashes($hist, "\n\r");
      $historico = formatar($hist, 400, 'c');
      $operacao  = formatar($oStdInformacao->operacao, 30, 'c');

      if ($valor == 0) {
        continue;
      }
      //--
      $line = $empenho.$liquidacao.$data.$valor.$sinal.$sObsoleto.$operacao.$historico;

      if (db_getsession("DB_anousu") >= 2014) {

        // n�o se aplica
        $sExisteContrato    = 'X';
        $sNumeroContrato    = str_repeat('0', 20);
        $sNumeroAnoContrato = str_repeat(' ', 20);
        $sAnoContrato       = str_repeat('0', 4);

        /**
         * Plugin
         */
        if (file_exists('model/ContratosPADRS.model.php')) {

          $oContratosPADRS = ContratosPADRS::getByLancamento($liquidacao);

          if ($oContratosPADRS) {

            $sExisteContrato = $oContratosPADRS->sCabecalho;
            $sNumeroContrato = str_pad($oContratosPADRS->iNumero, 20, '0', STR_PAD_LEFT);
            $sNumeroAnoContrato = str_pad($oContratosPADRS->iNumero . '/' . $oContratosPADRS->iAno, 20, ' ', STR_PAD_LEFT);
            $sAnoContrato = str_pad($oContratosPADRS->iAno, 4, '0', STR_PAD_LEFT);
          }
        }

        $line .= $sExisteContrato . $sNumeroContrato . $sNumeroAnoContrato . $sAnoContrato;
      }

      fputs($this->arq,$line);
      fputs($this->arq,"\r\n");
      $contador = $contador+1; // incrementa contador global
    }

    //trailer
    $contador = espaco(10-(strlen($contador)),'0').$contador;
    $line = "FINALIZADOR".$contador;
    fputs($this->arq,$line);
    fputs($this->arq,"\r\n");

    fclose($this->arq);
    return "true";
  }
}
