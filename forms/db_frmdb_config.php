<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: Configuracoes
$cldb_config->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clinfocomplementaresinstit->rotulo->label();


require ("classes/db_db_uf_classe.php");
require ("classes/db_db_tipoinstit_classe.php");
require ("classes/db_ceplocalidades_classe.php");
require ("libs/db_libdicionario.php");

$cluf             = new cl_db_uf();
$cltipoinstit     = new cl_db_tipoinstit();
$clCepLocalidades = new cl_ceplocalidades();

if ($db_opcao == 1) {
  $db_action="con1_db_config004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $db_action="con1_db_config005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action="con1_db_config006.php";
}


?>
<form name="form1" method="post" action="<?=$db_action?>" enctype="multipart/form-data">
<center>

<table align = "center" style="margin-top: 15px;">
  <tr>
  <td>
  <fieldset style="width: 790px;"><legend>
     <a  id='esconderinstituicao' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeInstituicao('');">
       <b>Dados da Institui��o</b>
       <img src='imagens/setabaixo.gif' id='toggleinstituicao' border='0'>
     </a></legend>

  <table border="0" align="center" id="tabInstituicao" style="display: ">

		  <tr>
		    <td nowrap title="<?=@$Tcodigo?>">
		       <?=@$Lcodigo?>
		    </td>
		    <td>
					<?
					db_input('codigo',10,$Icodigo,true,'text',3,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tnomeinst?>">
		       <?=@$Lnomeinst?>
		    </td>
		    <td>
					<?
					db_input('nomeinst',50,$Inomeinst,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tnomeinstabrev?>">
		       <?//=@$Lnomeinstabrev?>
		       <b>Nome abreviado da Institui��o:</b>
		    </td>
		    <td>
		      <?
		      db_input('nomeinstabrev',50,$Inomeinstabrev,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tender?>">
		       <?=@$Lender?>
		    </td>
		    <td>
					<?
					db_input('ender',50,$Iender,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tnumero?>">
		       <?=@$Lnumero?>
		    </td>
		    <td>
		      <?
		      db_input('numero',10,$Inumero,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <!-- Inserir o campo db21_compl -->
		  <tr>
        <td nowrap title="<?=@$Tdb21_compl?>">
           <?=@$Ldb21_compl?>
        </td>
        <td>
          <?
          db_input('db21_compl',50,$Idb21_compl,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>

		  <tr>
		    <td nowrap title="<?=@$Tbairro?>">
		       <?=@$Lbairro?>
		    </td>
		    <td>
		      <?
		      db_input('bairro',35,$Ibairro,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <tr>
        <td nowrap title="<?=@$Tuf?>">
           <?=@$Luf?>
        </td>
        <td>
          <?
          $rsUf = $cluf->sql_record($cluf->sql_query_file(null, '*', 'db12_uf'));
          $iNumRowsUf = $cluf->numrows;

          $aUf = array();
          $aUf["0"] = "Selecione";
          for($iUf = 0; $iUf < $iNumRowsUf ; $iUf++){
            $oRow = db_utils::fieldsMemory($rsUf,$iUf);
            $aUf[$oRow->db12_uf] = $oRow->db12_uf;
          }

          if($db_opcao == 3) {
          	$aUf = array($uf=>$uf);
          }

          db_select("uf",$aUf,true,$db_opcao,"onChange='js_buscaLocalidade();'");
          ?>
        </td>
      </tr>

		  <tr>
		    <td nowrap title="<?=@$Tmunic?>">
		       <?=@$Lmunic?>
		    </td>
		    <td id="idMunic">
					<?
					$x = array(0=>"Selecione");
					if($db_opcao == 2 || $db_opcao == 22 || $db_opcao == 3 || $db_opcao == 33){
			      $sWhere = "cp05_sigla = '$uf'";
						$rsLocalidades = $clCepLocalidades->sql_record($clCepLocalidades->sql_query_file(null,"cp05_localidades",null,$sWhere));
	          $iNumRowsLocalidades = $clCepLocalidades->numrows;

	          $x = array();
	          $x[0] = "Selecione";
	          for($iLocalidades = 0; $iLocalidades < $iNumRowsLocalidades ; $iLocalidades++){
	            $oRow = db_utils::fieldsMemory($rsLocalidades,$iLocalidades);
	            $x[$oRow->cp05_localidades] = $oRow->cp05_localidades;
	          }

					}

					if($db_opcao == 3) {
            $x = array($munic=>$munic);
          }

					db_select('munic',$x,true,$db_opcao,"onChange='js_buscaLocalidades();'");
					//db_input('munic',50,$Imunic,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>

		  <tr>
        <td nowrap title="<?=@$Tcep?>">
           <?=@$Lcep?>
        </td>
        <td>
          <?
          db_input('cep',10,$Icep,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>

		  <tr>
		    <td nowrap title="<?=@$Ttelef?>">
		       <?=@$Ltelef?>
		    </td>
		    <td>
					<?
					db_input('telef',10,$Itelef,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tfax?>">
		       <?=@$Lfax?>
		    </td>
		    <td>
		      <?
		      db_input('fax',10,$Ifax,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Temail?>">
		       <?=@$Lemail?>
		    </td>
		    <td>
					<?
					db_input('email',53,$Iemail,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tcgc?>">
                <strong>CGC / CNPJ</strong>
		    </td>
		    <td>
		      <?
		      db_input('cgc',14,$Icgc,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tnumcgm?>">
		       <?
		        db_ancora(@$Lnumcgm,"js_pesquisanumcgm(true);",$db_opcao);
		       ?>
		    </td>

		    <td>
		      <?
		       db_input('numcgm',10,$Inumcgm,true,'text',$db_opcao," onchange='js_pesquisanumcgm(false);'")
		      ?>
		      <?
		        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Turl?>">
		       <?=@$Lurl?>
		    </td>
		    <td>
		      <?
		      db_input('url',53,$Iurl,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tpref?>">
		       <?=@$Lpref?>
		    </td>

		    <td>
		      <?php
		      db_input('pref',53,$Ipref,true,'text',$db_opcao,"")
		      ?>
		    </td>

		  </tr>
		   <tr>
		    <td nowrap title="<?=@$Tvicepref?>">
		       <?=@$Lvicepref?>
		    </td>
		    <td>
		      <?php
		      db_input('vicepref',53,$Ivicepref,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tprefeitura?>">
		       <?=@$Lprefeitura?>
		    </td>
		    <td>
		      <?php
		      $x = array("f"=>"NAO","t"=>"SIM");
		      db_select('prefeitura',$x,true,$db_opcao,"");
		      ?>
		    </td>
		  </tr>

		   <tr>
		    <td nowrap title="<?=@$Tdb21_ativo?>">
		       <?=@$Ldb21_ativo?>
		    </td>
		    <td>
		      <?php
		      $x = array('1'=>'Ativo','2'=>'Inativo','3'=>'Offline');
		      db_select('db21_ativo',$x,true,$db_opcao,"");
		      ?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tdb21_codcli?>">
		       <?=@$Ldb21_codcli?>
		    </td>
		    <td>
		      <?php
		      db_input('db21_codcli',4,$Idb21_codcli,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

		  <!-- Aqui vai db21_criacao -->
		  <tr>
	      <td nowrap title="<?=@$Tdb21_criacao?>">
	         <?=@$Ldb21_criacao?>
	      </td>
	      <td>
	        <?php
	        db_inputdata('db21_criacao',@$db21_criacao_dia,@$db21_criacao_mes,@$db21_criacao_ano,true,'text',$db_opcao,"")
	        ?>
	      </td>
	    </tr>

		  <!-- Aqui vai db21_datalimite -->
		  <tr>
        <td nowrap title="<?=@$Tdb21_datalimite?>">
           <?=@$Ldb21_datalimite?>
        </td>
        <td>
          <?php
          db_inputdata('db21_datalimite',@$db21_datalimite_dia,@$db21_datalimite_mes,@$db21_datalimite_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>

		  <tr>
		    <td nowrap title="<?=@$Tdb21_codigomunicipoestado?>">
		       <?=@$Ldb21_codigomunicipoestado?>
		    </td>
		    <td>
		      <?php
		      db_input('db21_codigomunicipoestado',10,$Idb21_codigomunicipoestado,true,'text',$db_opcao,"")
		      ?>
		    </td>
		  </tr>

      <tr>
        <td nowrap title="<?=@$Tdb21_codtj?>">
           <?=@$Ldb21_codtj?>
        </td>
        <td>
          <?php
          db_input('db21_codtj',10,$Idb21_codtj,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
	  <tr>
        <td nowrap title="<?=@$Tdb21_habitantes?>">
           <?=@$Ldb21_habitantes?>
        </td>
        <td>
          <?php
          db_input('db21_habitantes',10,$Idb21_habitantes,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tdb21_tipopoder?>">
           <?=@$Ldb21_tipopoder?>
        </td>
        <td>
          <?php
          	db_select('db21_tipopoder', getValoresPadroesCampo("db21_tipopoder"), true, $db_opcao);
          ?>
        </td>
      </tr>

    <tr>
        <td nowrap title="<?=@$Tdb21_apirfb?>">
           <?=@$Ldb21_apirfb?>
           <b>API CPF/CNPJ RFB:</b>
        </td>
        <td>
            <?php
                $aApiRfb = [
                    "1" => "Ativa",
                    "2" => "Apenas Banco da API",
                    "0" => "Inativa"
                ];
          	    db_select('db21_apirfb', $aApiRfb, true, $db_opcao);
            ?>
        </td>
    </tr>

     </table>
  </fieldset>
  </td>
  </tr>

  <!--  Aqui vai ser a segunda aba -->
  <tr>
  <td>
  <fieldset><legend>
     <a  id='esconderfinanceiro' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeFinanceiro('');">
       <b>Dados da Institui��o Financeiro</b>
       <img src='imagens/seta.gif' id='togglefinanceiro' border='0'>
     </a>
  </legend>
  <table border="0" id="tabFinanceiro" style="display: none;">
	  <tr>
	    <td nowrap title="<?=@$Tdtcont?>">
	       <?=@$Ldtcont?>
	    </td>
	    <td>
	      <?
	      db_inputdata('dtcont',@$dtcont_dia,@$dtcont_mes,@$dtcont_ano,true,'text',$db_opcao,"")
	      ?>
	    </td>
	  </tr>

    <tr>
	    <td nowrap title="<?=@$Tcodtrib?>">
	       <?
          db_ancora(@$Lcodtrib,"js_pesquisa_orgaounidade(true);",$db_opcao);
         ?>
	    </td>
	    <td>
	      <?
	      db_input('codtrib',4,$Icodtrib,true,'text',3,"onChange=js_pesquisa_orgaounidade(true);");
	      db_input('orgaounid',70,0,true,'text',3,"");
	      ?>
	    </td>
    </tr>

    <tr>
	    <td nowrap title="<?=@$Ttribinst?>">
	       <?=@$Ltribinst?>
	    </td>
	    <td>
	      <?
	      db_input('tribinst',10,$Itribinst,true,'text',$db_opcao,"")
	      ?>
	    </td>
	  </tr>

    <tr>
	    <td nowrap title="<?=@$Tdb21_tipoinstit?>">
	       <?=@$Ldb21_tipoinstit?>
	    </td>
	    <td>
	      <?
	        $rsTipoInstit = $cltipoinstit->sql_record($cltipoinstit->sql_query_file());
          if($cltipoinstit->numrows > 0){
            $iNumRows = $cltipoinstit->numrows;
            for ($iInd = 0; $iInd < $iNumRows; $iInd++){
              $oRow = db_utils::fieldsMemory($rsTipoInstit,$iInd);
              $x[$oRow->db21_codtipo] = $oRow->db21_nome;
            }
          }
	      //$x = array('01'=>'Prefeitura Municipal','02'=>'C�mara Municipal','03'=>'Secretaria da Educa��o','04'=>'Secretaria da Sa�de','05'=>'RPPS (Exceto Autarquia)','06'=>'Autarquia (Exceto RPPS)','07'=>'Autarquia (RPPS)','08'=>'Funda��o','09'=>'Empresa Estatal Dependente','10'=>'Empresa Estatal n�o Dependente','11'=>'Cons�rcio','12'=>'Outras');
	      db_select('db21_tipoinstit',$x,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>

	 </table>
	 </fieldset>
	 </td>
	 </tr>

        <!-- tributario -->
			 <tr>
			 <td>
			 <fieldset><legend>
			  <a  id='escondertributario' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeTributario('');">
        <b>Dados da Institui��o Tribut�rio</b>
        <img src='imagens/seta.gif' id='toggletributario' border='0'>
        </a>
        </legend>
			 <table border = "0" id="tabTributario" style="display: none;">

			  <tr style="display: none;">
			   <td nowrap title="<?//=@$Ttx_banc?>">
			       <?//=@$Ltx_banc?>
			   </td>
			   <td>
			     <?
			     db_input('tx_banc',15,$Itx_banc,true,'hidden',$db_opcao,"");
			     ?>
			   </td>
			  </tr>
			  <tr style="display: none;">
			    <td nowrap title="<?=@$Tnumbanco?>">
			       <?=@$Lnumbanco?>
			    </td>
			    <td>
			      <?
			      $numbanco = null;
			      db_input('numbanco',10,$Inumbanco,true,'hidden',$db_opcao,"");
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap title="<?=@$Ttpropri?>">
			       <?=@$Ltpropri?>
			    </td>
			    <td>
			      <?
			      $x = array("f"=>"NAO","t"=>"SIM");
			      db_select('tpropri',$x,true,$db_opcao,"");
			      ?>
			    </td>
			  </tr>

		    <tr>
			    <td nowrap title="<?=@$Ttsocios?>">
			       <?=@$Ltsocios?>
			    </td>
			    <td>
			      <?
			      $x = array("f"=>"NAO","t"=>"SIM");
			      db_select('tsocios',$x,true,$db_opcao,"");
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap title="<?=@$Tnomedebconta?>">
			       <?=@$Lnomedebconta?>
			    </td>
			    <td>
			      <?
			      db_input('nomedebconta',20,$Inomedebconta,true,'text',$db_opcao,"")
			      ?>
			    </td>
			  </tr>

			  <tr>
			    <td nowrap title="<?=@$Tdb21_regracgmiss?>">
			       <?=@$Ldb21_regracgmiss?>
			    </td>
			    <td>
			      <?
			      $x = array('0'=>'N�o vincular socios','1'=>'Vincular socios');
			      db_select('db21_regracgmiss',$x,true,$db_opcao,"");
			      ?>
			    </td>
			  </tr>

		    <tr>
			    <td nowrap title="<?=@$Tdb21_regracgmiptu?>">
			       <?=@$Ldb21_regracgmiptu?>
			    </td>
			    <td>
			      <?
			      $x = array('0'=>'Considerar Proprietario e Promitente','1'=>'Considerar Somente Proprietario','2'=>'Considerar Somente Promitente');
			      db_select('db21_regracgmiptu',$x,true,$db_opcao,"");
			      ?>
			    </td>
			  </tr>

		    <tr>
			    <td nowrap title="<?=@$Tdb21_usasisagua?>">
			       <?=@$Ldb21_usasisagua?>
			    </td>

			    <!--  db21_reghra -->
			    <td>
			      <?
			      $x = array("f"=>"NAO","t"=>"SIM");
			      db_select('db21_usasisagua',$x,true,$db_opcao,"");
			      ?>
			    </td>
			</tr>

            <tr>
                <td >
                   <strong>Usa Distrito e Unidade: </strong>
                </td>

                <td>
                  <?
                  $x = array("f"=>"NAO","t"=>"SIM");
                  db_select('db21_usadistritounidade',$x,true,$db_opcao,"");
                  ?>
                </td>
            </tr>

            <tr>
                <td >
                   <strong>Usa D�bito ITBI: </strong>
                </td>

                <td>
                  <?
                  $x = array("f"=>"NAO","t"=>"SIM");
                  db_select('db21_usadebitoitbi',$x,true,$db_opcao,"");
                  ?>
                </td>
            </tr>

		  <tr>
		    <td nowrap title="<?=@$Tident?>">
		       <?=@$Lident?>
		    </td>
		    <td>
					<?
					db_input('ident',4,$Iident,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tdiario?>">
		       <?=@$Ldiario?>
		    </td>
		    <td>
					<?
					db_input('diario',4,$Idiario,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>

		  <tr>
		    <td nowrap title="<?=@$Tsegmento?>">
		       <?=@$Lsegmento?>
		    </td>
		    <td>
					<?
					$x = array('1'=>'Prefeituras','2'=>'Saneamento','3'=>'Energia El�trica e G�s','4'=>'Telecomunica��es','5'=>'�rg�os Governamentais','6'=>'Carnes e Assemelhados ou demais Empresas / �rg�os que ser�o identificadas atrav�s do CNPJ','7'=>'Multas de tr�nsito','9'=>'Uso exclusivo do banco');
					db_select('segmento',$x,true,$db_opcao,"");
					?>
		    </td>
		  </tr>

			  <tr>
			    <td nowrap title="<?=@$Tformvencfebraban?>">
			       <?=@$Lformvencfebraban?>
			    </td>
			    <td>
						<?
						$x = array('1'=>'aaaammdd','2'=>'ddmmaa');
						db_select('formvencfebraban',$x,true,$db_opcao,"");
						?>
        </td>
      </tr>
    </table>
    </fieldset>
    </td>
  </tr>

  <tr>
  <td>
  <fieldset><legend>
     <a  id='esconderinfocomplementar' style="-moz-user-select: none;cursor: pointer" onClick="js_escondeInfocomplamentar('');">
       <b>Dados para TCE/MG e STN</b>
       <img src='imagens/seta.gif' id='toggleinfocomplementar' border='0'>
     </a>
  </legend>
  <table border="0" id="tabInfocomplementar" style="display: none;">
   <tr>
	    <td nowrap title="<?=@$Tsi09_tipoinstit?>">
	       <?=@$Lsi09_tipoinstit?>
	    </td>
	    <td>
	      <?
	      $x = array('1'=>'C�MARA MUNICIPAL - MG','2'=>'PREFEITURA MUNICIPAL - MG','3'=>'AUTARQUIA (EXCEETO RPPS) - MG',
	      '4'=>'FUNDA��O - MG','5'=>'RPPS (REGIME PR�PRIO DE PREVID�NCIA SOCIAL) - MG','6'=>'RPPS - ASSIST�NCIA � SA�DE - MG',
	      '8'=>'EMPRESA P�BLICA (APENAS AS DEPENDENTES) - MG','9'=>'SOCIEDADE DE ECONOMIA MISTA (APENAS AS DEPENDENTES) - MGA'
		  ,'51'=>'CONSORCIO INTERMUNICIPAL', '50' => 'ENTIDADE ESTADUAL', '52' => 'CONS�RCIO P�BLICO INTERESTADUAL'
          ,'53' => 'EMPRESA P�BLICA ESTADUAL (N�O DEPENDENTES)', '54' =>'SOCIEDADE DE ECONOMIA MISTA ESTADUAL (N�O DEPENDENTES)'
          ,'55' => 'EMPRESA P�BLICA (N�O DEPENDENTES)','56'=>'SOCIEDADE DE ECONOMIA MISTA (N�O DEPENDENTES)'
          ,'57'=>'EMPRESA P�BLICA ESTADUAL (DEPENDENTES)','58'=>'SOCIEDADE DE ECONOMIA MISTA ESTADUAL (DEPENDENTES)');
	      db_select('si09_tipoinstit',$x,true,$db_opcao,"onchange='js_mostra_cnpjprefeitura();'");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tsi09_codorgaotce?>">
	       <?=@$Lsi09_codorgaotce?>
	    </td>
	    <td>
	      <?
	      db_input('si09_codorgaotce',12,$Isi09_codorgaotce,true,'text',$db_opcao,"");
	      ?>
	    </td>
	  </tr>
      <tr>
          <td nowrap title="<?=@$Tsi09_codunidadesubunidade?>">
              <?=@$Lsi09_codunidadesubunidade?>
          </td>
          <td>
              <?
              db_input('si09_codunidadesubunidade',12,$Isi09_codunidadesubunidade,true,'text',$db_opcao,"",0);
              ?>
          </td>
      </tr>
      <tr>
          <td nowrap title="<?=@$Tsi09_codfundotcemg?>">
              <?=@$Lsi09_codfundotcemg?>
          </td>
          <td>
              <?
              db_input('si09_codfundotcemg',12,$Isi09_codfundotcemg,true,'text',$db_opcao,"",0);
              ?>
          </td>
      </tr>
	  <tr>
	    <td nowrap title="<?=@$Tsi09_opcaosemestralidade?>">
	       <?=@$Lsi09_opcaosemestralidade?>
	    </td>
	    <td>
	      <?
	      $x = array('1'=>'SIM','2'=>'N�O');
	      db_select('si09_opcaosemestralidade',$x,true,$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
		    <td nowrap title="<?=@$Tnumcgm?>">
		       <?
		        db_ancora("Cgm Gestor","js_pesquisanumcgmgestor(true);",$db_opcao);
		       ?>
		    </td>

		    <td>
		      <?
		       db_input('si09_gestor',10,$Isi09_gestor,true,'text',$db_opcao," onchange='js_pesquisanumcgmgestor(false);'")
		      ?>
		      <?
		        db_input('nomegestor',40,'',true,'text',3,'')
		      ?>
		    </td>
		  </tr>
		  <tr>
	    <td nowrap title="<?=@$Tsi09_cnpjprefeitura?>" id="cnpjprefeitura">
	       <?=@$Lsi09_cnpjprefeitura?>
	    </td>
	    <td>
	      <?
	      db_input('si09_cnpjprefeitura',14,$Isi09_cnpjprefeitura,true,'text',$db_opcao,"");
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="Possui Assessoria Cont�bil">
	       <strong>Assessoria Cont�bil:</strong>
	    </td>
	    <td>
	      <?
	      $x = array('2'=>'N�O','1'=>'SIM');
	      db_select('si09_assessoriacontabil',$x,true,$db_opcao,"onchange='js_mostra_cgm_assessoria()'");
	      ?>
	    </td>
		</tr>
		<tr>
			<td nowrap title="<?=@$Tsi09_instsiconfi?>" id="cnpjprefeitura">
				<?=@$Lsi09_instsiconfi?>
			</td>
	    <td>
	      <? db_input('si09_instsiconfi',14,$Isi09_instsiconfi,true,'text',$db_opcao,""); ?>
	    </td>
	  </tr>
      <tr id="cgmAssessoria">
		    <td nowrap title="Cgm Assessoria Cont�bil">
		       <?
		        db_ancora("Cgm Assessoria Cont�bil","js_pesquisanumcgmassessoria(true);",$db_opcao);
		       ?>
		    </td>

		    <td>
		      <?
		       db_input('si09_cgmassessoriacontabil',10,1,true,'text',$db_opcao," onchange='js_pesquisanumcgmassessoria(false);'")
		      ?>
		      <?
		        db_input('nomeassessoriacontabil',40,'',true,'text',3,'')
		      ?>
		    </td>
	  </tr>
      <tr>
          <td nowrap>
              <strong>Possui CUTE:</strong>
          </td>
          <td>
			  <?
				  $x = array('2' =>'N�O','1' =>'SIM');
				  db_select('possuiCute',$x,true,$db_opcao,"onchange='js_habilitaCamposCute()'");
				  db_input('si09_contaunicatesoumunicipal',1,'',true,'hidden',1,'','','','',1);
			  ?>
          </td>
      </tr>
      <tr id="leiCute">
          <td nowrap >
              <strong>N� da Lei CUTE: </strong>
          </td>
          <td>
			  <?
		        db_input('si09_nroleicute',10,'',true,'text',1,'','','','',6);
		      ?>
		  </td>
      </tr>
      <tr id="dataCute">
          <td nowrap>
              <strong>Data da Lei CUTE:</strong>
          </td>
          <td>
			  <?
                db_inputdata('si09_dataleicute',@$si09_dataleicute_dia,@$si09_dataleicute_mes,@$si09_dataleicute_ano,true,'text',$db_opcao,"")
	          ?>
		  </td>
      </tr>

  </table>
  </fieldset>
  </td>
  </tr>

</table>
</center>

	    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" onclick="return js_validaForm();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?> >

      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >


</form>

<script>

var opcao = "<?= $db_opcao;?>";

if(opcao == 1){
    // $('si09_nroleicute').value = '';
    // $('si09_dataleicute').value = '';
    // $('si09_dataleicute_dia').value = '';
    // $('si09_dataleicute_mes').value = '';
    // $('si09_dataleicute_ano').value = '';
    document.form1.si09_contaunicatesoumunicipal.value = 2;
    document.getElementById('possuiCute').value = 2;
}else{
    if($('si09_nroleicute').value != '' && $('si09_dataleicute').value != ''){
        document.getElementById('possuiCute').value = 1;
        $('si09_contaunicatesoumunicipal').value = 1;
    }else{
        if(document.getElementById('possuiCute').value == 2){
            $('si09_nroleicute').value = '';
            $('si09_dataleicute').value = '';
            $('si09_dataleicute_dia').value = '';
            $('si09_dataleicute_mes').value = '';
            $('si09_dataleicute_ano').value = '';
            $('si09_contaunicatesoumunicipal').value = 2;
        }
    }
}



function js_buscaLocalidade(){
  var oUf = new Object();
  oUf.cp05_sigla = $('uf').value;
  oUf.acao       = "pesquisar";

  var sDados = Object.toJSON(oUf);
  var msgDiv = "Aguarde Pesquisando Localidades da UF{"+oUf.cp05_sigla+"}";
  js_divCarregando(msgDiv,'msgBox');

  sUrl = 'con1_db_config.RPC.php';
  var sQuery = 'dados='+sDados;
  var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: sQuery,
                                            onComplete: js_retornoPesquisaLocalidades
                                          }
                                  );
}

function js_retornoPesquisaLocalidades(oAjax){

  //alert(oAjax.responseText);

  js_removeObj("msgBox");

  var aRetorno = eval("("+oAjax.responseText+")");

  var sExpReg  = new RegExp('\\\\n','g');

  //alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));

  if ( aRetorno.status == 1) {
    return false;
  } else if ( aRetorno.status == 2) {
    js_preencheLocalidades(aRetorno.localidades);
  }
}

function js_preencheLocalidades(aLocalidades){

  var iNumRows = aLocalidades.length;

  if ( iNumRows > 0){

    var ddlLocalidades = "<select name=\"munic\" id=\"munic\" >";
    ddlLocalidades    += "<option value=\""+0+"\">Selecione</option>";
    aLocalidades.each(
      function (oLocalidade,iInd){

        ddlLocalidades +="<option values=\""+oLocalidade.cp05_localidades.urlDecode()+"\">"+oLocalidade.cp05_localidades.urlDecode()+"</option>";

      }

    );
   ddlLocalidades +="</select>";
   $('idMunic').innerHTML = ddlLocalidades;
  }

}

function js_validaForm(){
  if($('nomeinst').value.trim() == ''){
    alert("usu�rio:\n\n Campo Nome da Institui��o n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('nomeinst').focus();
    return false;
  }

  if($('nomeinstabrev').value.trim() == ''){
    alert("usu�rio:\n\n Campo Nome Abreviado da Institui��o n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('nomeinstabrev').focus();
    return false;
  }

  if($('ender').value.trim() == ''){
    alert("usu�rio:\n\n Campo Endere�o n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('ender').focus();
    return false;
  }

  if($('numero').value.trim() == ''){
    alert("usu�rio:\n\n Campo N�mero n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('numero').focus();
    return false;
  }

   if($('bairro').value.trim() == ''){
    alert("usu�rio:\n\n Campo Bairro n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('bairro').focus();
    return false;
  }

  if($('uf').value == 0){
    alert("usu�rio:\n\n Campo UF n�o Selecionado!\n\n");
    js_escondeInstituicao(true);
    $('uf').focus();
    return false;
  }

  if($('munic').value.trim() == 0){
    alert("usu�rio:\n\n Campo Munic�pio n�o Selecionado!\n\n");
    js_escondeInstituicao(true);
    $('munic').focus();
    return false;
  }


  if($('cep').value.trim() == ''){
    alert("usu�rio:\n\n Campo Cep n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('cep').focus();
    return false;
  }

  if($('telef').value.trim() == ''){
    alert("usu�rio:\n\n Campo Telefone n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('telef').focus();
    return false;
  }

  if($('fax').value.trim() == ''){
    alert("usu�rio:\n\n Campo Fax n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('fax').focus();
    return false;
  }

  if($('cgc').value.trim() == ''){
    alert("usu�rio:\n\n Campo CGC n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('cgc').focus();
    return false;
  }

  if($('numcgm').value.trim() == ''){
    alert("usu�rio:\n\n Campo N�mero do CGM n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('numcgm').focus();
    return false;
  }

  if($('url').value.trim() == ''){
    alert("usu�rio:\n\n Campo URL n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('url').focus();
    return false;
  }

  if($('pref').value.trim() == ''){
    alert("usu�rio:\n\n Campo Prefeito n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('pref').focus();
    return false;
  }

  if($('vicepref').value.trim() == ''){
    alert("usu�rio:\n\n Campo Vice Prefeito n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('vicepref').focus();
    return false;
  }

  if($('db21_codcli').value.trim() == ''){
    alert("usu�rio:\n\n Campo C�digo do cliente n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('db21_codcli').focus();
    return false;
  }

  if($('db21_codigomunicipoestado').value.trim() == ''){
    alert("usu�rio:\n\n Campo C�digo do Munic�pio n�o Informado!\n\n");
    js_escondeInstituicao(true);
    $('db21_codigomunicipoestado').focus();
    return false;
  }

  if($('dtcont').value.trim() == ''){
    alert("usu�rio:\n\n Campo Data da Contabilidade n�o Informado!\n\n");
    js_escondeFinanceiro(true);
    $('dtcont').focus();
    return false;
  }

  if($('codtrib').value.trim() == ''){
    alert("usu�rio:\n\n Campo Org�o / Unidade da institui��o n�o Informado!\n\n");
    js_escondeFinanceiro(true);
    $('codtrib').focus();
    return false;
  }

  if($('tribinst').value.trim() == ''){
    alert("usu�rio:\n\n Campo SIAPC / PAD n�o Informado!\n\n");
    js_escondeFinanceiro(true);
    $('tribinst').focus();
    return false;
  }

  if($('nomedebconta').value.trim() == ''){
    alert("usu�rio:\n\n Campo Nome da institui��o no debito em conta n�o Informado!\n\n");
    js_escondeTributario(true);
    $('nomedebconta').focus();
    return false;
  }

  if($('ident').value.trim() == ''){
    alert("usu�rio:\n\n Campo Identidade n�o Informado!\n\n");
    js_escondeTributario(true);
    $('ident').focus();
    return false;
  }

  if($('diario').value.trim() == ''){
    alert("usu�rio:\n\n Campo Di�rio n�o Informado!\n\n");
    js_escondeTributario(true);
    $('diario').focus();
    return false;
  }

  if($('possuiCute').value == 1){
    return js_verificaCute();
  }

  return true;
  //document.form1.submit();

}

function js_verificaCute(){
    if($('si09_nroleicute').value == '' && $('si09_dataleicute').value == ''){
        alert("usu�rio:\n\n Campo N� da Lei CUTE e Data da Lei CUTE n�o Informado!\n\n");
        $('si09_nroleicute').focus();
        // $('si09_dataleicute').focus();
        return false;
    }else{
        if($('si09_nroleicute').value == ''){
            alert("usu�rio:\n\n Campo N� da Lei CUTE n�o Informado!\n\n");
            $('si09_nroleicute').focus();
            return false;
        }
        if($('si09_dataleicute').value == ''){
            alert("usu�rio:\n\n Campo Data da Lei CUTE n�o Informado!\n\n");
            $('si09_dataleicute').focus();
            return false;
        }
    }
}

function js_pesquisanumcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.numcgm.value != ''){
        js_OpenJanelaIframe('','func_nome','func_cgm.php?pesquisa_chave='+document.form1.numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = erro;
  if(erro==true){
    document.form1.numcgm.focus();
    document.form1.numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  func_nome.hide();
}



function js_pesquisanumcgmgestor(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('','func_nome','func_cgm.php?funcao_js=parent.js_mostracgmgestor1|z01_numcgm|z01_nome','Pesquisa',true);
	  }else{
	     if(document.form1.si09_gestor.value != ''){
	        js_OpenJanelaIframe('','func_nome','func_cgm.php?pesquisa_chave='+document.form1.si09_gestor.value+'&funcao_js=parent.js_mostracgmgestor','Pesquisa',false);
	     }else{
	       document.form1.nomegestor.value = '';
	     }
	  }
	}
	function js_mostracgmgestor(chave,erro){
	  document.form1.nomegestor.value = erro;
	  if(erro==true){
	    document.form1.si09_gestor.focus();
	    document.form1.si09_gestor.value = '';
	  }
	}
	function js_mostracgmgestor1(chave1,chave2){
	  document.form1.si09_gestor.value = chave1;
	  document.form1.nomegestor.value = chave2;
	  func_nome.hide();
	}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_preenchepesquisa|codigo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_db_config.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_mostra_cnpjprefeitura(){
	if (document.form1.si09_tipoinstit.value == 2) {
    $('si09_cnpjprefeitura').style.display = 'none';
    $('cnpjprefeitura').style.display = 'none';
    document.form1.si09_cnpjprefeitura.value = '';
	} else {
		$('si09_cnpjprefeitura').style.display = '';
		$('cnpjprefeitura').style.display = '';
	}
}

function js_escondeInstituicao(lExibe){
      if ($('tabInstituicao').style.display == '' && lExibe == true) {

        $('tabInstituicao').style.display = '';
        $('tabFinanceiro').style.display  = 'none';
        $('tabTributario').style.display  = 'none';
        $('tabInfocomplementar').style.display = 'none';
        $('toggleinstituicao').src = 'imagens/setabaixo.gif';
        $('togglefinanceiro').src  = 'imagens/seta.gif';
        $('toggletributario').src  = 'imagens/seta.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';

      }else if ($('tabInstituicao').style.display == '') {

        $('tabInstituicao').style.display = 'none';
        $('tabFinanceiro').style.display = '';
        $('tabTributario').style.display = '';
        $('tabInfocomplementar').style.display = 'none';
        $('toggleinstituicao').src = 'imagens/seta.gif';
        $('togglefinanceiro').src  = 'imagens/setabaixo.gif';
        $('toggletributario').src  = 'imagens/setabaixo.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';

      } else if ($('tabInstituicao').style.display == 'none') {

        $('tabInstituicao').style.display = '';
        $('tabFinanceiro').style.display  = 'none';
        $('tabTributario').style.display  = 'none';
        $('tabInfocomplementar').style.display = 'none';
        $('toggleinstituicao').src = 'imagens/setabaixo.gif';
        $('togglefinanceiro').src  = 'imagens/seta.gif';
        $('toggletributario').src  = 'imagens/seta.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';

      }

}
function js_escondeFinanceiro(lExibe){

       if ($('tabFinanceiro').style.display == '' && lExibe == true) {

        $('tabFinanceiro').style.display = '';
        $('tabTributario').style.display = '';
        $('tabInstituicao').style.display = 'none';
        $('tabInfocomplementar').style.display = 'none';
        $('togglefinanceiro').src='imagens/setabaixo.gif';
        $('toggleinstituicao').src='imagens/seta.gif';
        $('toggletributario').src='imagens/setabaixo.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';

      }else if ($('tabFinanceiro').style.display == '') {

        $('tabFinanceiro').style.display = 'none';
        $('tabTributario').style.display = 'none';
        $('tabInstituicao').style.display = '';
        $('tabInfocomplementar').style.display = 'none';
        $('togglefinanceiro').src='imagens/setabaixo.gif';
        $('toggleinstituicao').src='imagens/seta.gif';
        $('toggletributario').src='imagens/setabaixo.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';
      } else if ($('tabFinanceiro').style.display == 'none') {

        $('tabFinanceiro').style.display = '';
        $('tabTributario').style.display = '';
        $('tabInstituicao').style.display = 'none';
        $('tabInfocomplementar').style.display = 'none';
        $('togglefinanceiro').src='imagens/setabaixo.gif';
        $('toggleinstituicao').src='imagens/seta.gif';
        $('toggletributario').src='imagens/setabaixo.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';
      }

}

function js_escondeInfocomplamentar(lExibe){

    if ($('tabInfocomplementar').style.display == '' && lExibe == true) {

     $('tabInfocomplementar').style.display = '';
     $('tabFinanceiro').style.display = 'none';
     $('tabTributario').style.display = 'none';
     $('tabInstituicao').style.display = 'none';
     $('toggleinfocomplementar').src='imagens/setabaixo.gif';
     $('togglefinanceiro').src='imagens/seta.gif';
     $('toggleinstituicao').src='imagens/seta.gif';
     $('toggletributario').src='imagens/seta.gif';

   }else if ($('tabInfocomplementar').style.display == '') {

	   $('tabInfocomplementar').style.display = 'none';
     $('tabFinanceiro').style.display = 'none';
     $('tabTributario').style.display = 'none';
     $('tabInstituicao').style.display = '';
     $('toggleinfocomplementar').src='imagens/seta.gif';
     $('togglefinanceiro').src='imagens/seta.gif';
     $('toggleinstituicao').src='imagens/setabaixo.gif';
     $('toggletributario').src='imagens/seta.gif';
   } else if ($('tabInfocomplementar').style.display == 'none') {

	   $('tabInfocomplementar').style.display = '';
     $('tabFinanceiro').style.display = 'none';
     $('tabTributario').style.display = 'none';
     $('tabInstituicao').style.display = 'none';
     $('toggleinfocomplementar').src='imagens/setabaixo.gif';
     $('togglefinanceiro').src='imagens/seta.gif';
     $('toggleinstituicao').src='imagens/seta.gif';
     $('toggletributario').src='imagens/seta.gif';
   }

}
function js_escondeTributario(lExibe){

      if ($('tabFinanceiro').style.display == '' && lExibe == true) {
        $('tabTributario').style.display = '';
        $('tabFinanceiro').style.display = '';
        $('tabInstituicao').style.display = 'none';
        $('tabInfocomplementar').style.display = 'none';
        $('togglefinanceiro').src='imagens/setabaixo.gif';
        $('toggletributario').src='imagens/setabaixo.gif';
        $('toggleinstituicao').src='imagens/seta.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';

      } else if ($('tabFinanceiro').style.display == '') {

        $('tabTributario').style.display = 'none';
        $('tabFinanceiro').style.display = 'none';
        $('tabInstituicao').style.display = '';
        $('tabInfocomplementar').style.display = 'none';
        $('togglefinanceiro').src='imagens/setabaixo.gif';
        $('toggletributario').src='imagens/setabaixo.gif';
        $('toggletributario').src='imagens/seta.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';

      } else if ($('tabFinanceiro').style.display == 'none') {
        $('tabTributario').style.display = '';
        $('tabFinanceiro').style.display = '';
        $('tabInstituicao').style.display = 'none';
        $('tabInfocomplementar').style.display = 'none';
        $('togglefinanceiro').src='imagens/setabaixo.gif';
        $('toggletributario').src='imagens/setabaixo.gif';
        $('toggleinstituicao').src='imagens/seta.gif';
        $('toggleinfocomplementar').src  = 'imagens/seta.gif';
      }

}


function js_pesquisa_orgaounidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_orcunidade',
                        'func_db_config_orcunidade.php?funcao_js=parent.js_mostraorcunidade1|o41_orgao|o41_unidade|o40_descr|o41_descr','Pesquisa',true);
  }
}
function js_mostraorcunidade(chave,erro){
  document.form1.o40_descr.value = chave;
  if(erro==true){
    document.form1.o41_orgao.focus();
    document.form1.o41_orgao.value = '';
  }
}
function js_mostraorcunidade1(chave1,chave2,chave3,chave4){
  var strOrgao    = chave1;
  var strUnidade  = chave2;
  var iOrgao    = parseInt(chave1);
  var iUnidade  = parseInt(chave2);
  if(iOrgao < 10){
    strOrgao = '0'+strOrgao;
  }
  if(iUnidade < 10){
    strUnidade = '0'+strUnidade;
  }
  var codtrib   = strOrgao+strUnidade;
  var orgaounid = chave3+' / '+chave4;
  document.form1.codtrib.value = codtrib;
  document.form1.orgaounid.value = orgaounid;
  db_iframe_orcunidade.hide();
}

function js_mostra_cgm_assessoria() {

	if ($('si09_assessoriacontabil').value == 1) {
	  $('cgmAssessoria').style.display = '';
	} else {
		$('cgmAssessoria').style.display = 'none';
		$('si09_cgmassessoriacontabil').value = '';
		$('nomeassessoriacontabil').value = '';
	}

}


function js_habilitaCamposCute(){

    if($('possuiCute').value == 1){
       $('leiCute').style.display = '';
       $('dataCute').style.display = '';
       $('si09_contaunicatesoumunicipal').value = 1;
    }else{
       $('leiCute').style.display = 'none';
       $('dataCute').style.display = 'none';
       $('si09_nroleicute').value = '';
       $('si09_dataleicute').value = '';
       $('si09_dataleicute_dia').value = '';
       $('si09_dataleicute_mes').value = '';
       $('si09_dataleicute_ano').value = '';
       $('si09_contaunicatesoumunicipal').value = 2;
    }
}

function js_pesquisanumcgmassessoria(mostra){
      if(mostra==true){
	    js_OpenJanelaIframe('','func_nome','func_cgm.php?funcao_js=parent.js_mostracgmassessoria1|z01_numcgm|z01_nome','Pesquisa',true);
	  }else{
	     if(document.form1.si09_cgmassessoriacontabil.value != ''){
	        js_OpenJanelaIframe('','func_nome','func_cgm.php?pesquisa_chave='+document.form1.si09_cgmassessoriacontabil.value+'&funcao_js=parent.js_mostracgmassessoria','Pesquisa',false);
	     }else{
	       document.form1.nomeassessoriacontabil.value = '';
	     }
	  }
	}
	function js_mostracgmassessoria(chave,erro){
	  document.form1.nomeassessoriacontabil.value = erro;
	  if(erro==true){
	    document.form1.si09_cgmassessoriacontabil.focus();
	    document.form1.si09_cgmassessoriacontabil.value = '';
	  }
	}
	function js_mostracgmassessoria1(chave1,chave2){
	  document.form1.si09_cgmassessoriacontabil.value = chave1;
	  document.form1.nomeassessoriacontabil.value = chave2;
	  func_nome.hide();
	}

$('nomeinst').focus();

js_mostra_cnpjprefeitura();
js_habilitaCamposCute();
js_mostra_cgm_assessoria();

</script>
