<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_ppaestimativa_classe.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
$oListaOrgao     = new cl_arquivo_auxiliar;
$clppaestimativa = new cl_ppaestimativa();
$oPost           = db_utils::postMemory($_POST);
$clppaestimativa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_anoinicio");
$clrotulo->label("o01_anofinal");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_numerolei");
$db_opcao = 1;
$aAnos    = array();
$lProcessaManual = false;
if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {

  $oDaoPPALei = db_utils::getDao("ppalei");
  $sSqlLei    = $oDaoPPALei->sql_query($oPost->o05_ppalei);
  $rsLei      = $oDaoPPALei->sql_record($sSqlLei);
  if ($oDaoPPALei->numrows > 0) {

     $oLei          = db_utils::fieldsMemory($rsLei, 0);
     $o01_anoinicio = $oLei->o01_anoinicio;
     $o01_anofinal  = $oLei->o01_anofinal;
     $o01_descricao = $oLei->o01_descricao;
     $o01_numerolei = $oLei->o01_numerolei;
  	 for ($iAno = $o01_anoinicio; $iAno <= $o01_anofinal; $iAno++) {
       $aAnos [$iAno] = $iAno;
     }
   /*
    * Verificamos se j� foi feito o processamento da estimativa da lei.
    * caso j� foi feito, carregamos o programa para edi��o manual dos valores.
    */
   $sSqlEstimativas = $clppaestimativa->sql_query(null,"*",
                                                  "o05_ppalei limit 1",
                                                  "o05_ppalei = {$oPost->o05_ppalei}");
   $rsEstmativas    = $clppaestimativa->sql_record($sSqlEstimativas);
   if ($clppaestimativa->numrows > 0) {
     $lProcessaManual = true;
   }

  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  <form name='form1' method='post'>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Demonstrativo das A��es Validadas</b>
          </legend>
          <table>
            <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'')
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <b>Vers�o:</b>
              </td>
              <td id='verppa'>

              </td>
            </tr>
              <tr>
                <td nowrap title="<?=@$To01_anoinicio?>">
                 <?=@$Lo01_anoinicio?>
                </td>
                <td>
                <?
                  db_input('o01_anoinicio',10,$Io01_anoinicio,true,'text',3,"")
                ?>
               </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_anofinal?>">
                <?=@$Lo01_anofinal?>
              </td>
              <td>
                <?
                  db_input('o01_anofinal',10,$Io01_anofinal,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_numerolei?>">
                 <?=@$Lo01_numerolei?>
              </td>
              <td>
                <?
                  db_input('o01_numerolei',10,$Io01_numerolei,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_numerolei?>">
                 <b>Imprime Rodap�: </b>
              </td>
              <td>
                <?
				  				$aRodape = array("s"=>"Sim","n"=>"N�o");
                  db_select("selrodape",$aRodape,true,1,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="">
                 <b>Forma de Emiss�o: </b>
              </td>
              <td>
                <?
				  				$aForma = array("a"=>"Anal�tico","s"=>"Sint�tico");
                  db_select("selforma",$aForma,true,1,"");
                ?>
              </td>
            </tr>
             <tr>
              <td nowrap>
                 <b>Quebrar por P�gina: </b>
              </td>
              <td>
                <?
                  $aQuebra = array("true"=>"Sim","false"=>"N�o");
                  db_select("selquebra",$aQuebra,true,1,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap>
                 <b>Modelo: </b>
              </td>
              <td>
                <?
                  $aModelo = array(
                                   1 => "PPA",
                                   2 => "LDO"
                                  );
                  db_select("selmodelo",$aModelo,true,1,"onchange='js_setModelo(this.value)'");
                ?>
              </td>
            </tr>
            <tr style="display: none" id='mostramodelo'>
              <td>
                 <b>Ano LDO:</b>
              </td>
              <td>
                <?
                  db_select("selanoldo",$aAnos,true,1,"");
                ?>
              </td>
            </tr>
            <tr>
              <td  colspan='2' style='text-align: center'><table>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $oListaOrgao->cabecalho      = "<strong>Lista por Programa</strong>";
                 $oListaOrgao->codigo         = "o54_programa"; //chave de retorno da func
                 $oListaOrgao->descr          = "o54_descr"; //chave de retorno
                 $oListaOrgao->nomeobjeto     = 'programa';
                 $oListaOrgao->nome_botao     = 'btprograma';
                 $oListaOrgao->funcao_js      = 'js_mostra_programa';
                 $oListaOrgao->funcao_js_hide = 'js_mostra_programa1';
                 $oListaOrgao->sql_exec       = "";
                 $oListaOrgao->func_arquivo   = "func_orcprograma.php";  //func a executar
                 $oListaOrgao->nomeiframe     = "db_iframe_orcprograma";
                 $oListaOrgao->localjan       = "";
                 $oListaOrgao->onclick        = "";
                 $oListaOrgao->db_opcao       = 2;
                 $oListaOrgao->tipo           = 2;
                 $oListaOrgao->top            = 0;
                 $oListaOrgao->linhas         = 5;
                 $oListaOrgao->vwhidth        = '100%';
                 $oListaOrgao->funcao_gera_formulario();
               ?>
               </table>
              </td>
            </tr>
		      	<tr>
              <td  colspan='2' style='text-align: center'>
               <table>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $oListaOrgao->cabecalho      = "<strong>Lista por Org�o</strong>";
                 $oListaOrgao->codigo         = "o40_orgao"; //chave de retorno da func
                 $oListaOrgao->descr          = "o40_descr"; //chave de retorno
                 $oListaOrgao->nomeobjeto     = 'orgao';
                 $oListaOrgao->nome_botao     = 'btorgao';
                 $oListaOrgao->funcao_js      = 'js_mostra_orgao';
                 $oListaOrgao->funcao_js_hide = 'js_mostra_orgao1';
                 $oListaOrgao->sql_exec       = "";
                 $oListaOrgao->func_arquivo   = "func_orcorgao.php";  //func a executar
                 $oListaOrgao->nomeiframe     = "db_iframe_orcorgao";
                 $oListaOrgao->localjan       = "";
                 $oListaOrgao->onclick        = "";
                 $oListaOrgao->db_opcao       = 2;
                 $oListaOrgao->tipo           = 2;
                 $oListaOrgao->top            = 0;
                 $oListaOrgao->linhas         = 5;
                 $oListaOrgao->vwhidth        = '100%';
                 $oListaOrgao->funcao_gera_formulario();
               ?>
               </table>
              </td>
            </tr>
             <tr>
               <td>&nbsp;</td>
               <td >
                 <? db_selinstit('',300,100); ?>
              </td>
            </tr>
			<tr>
			  <td><strong>Layout:</strong></td>
			  <td> <? $aLayout = array("1"=>"Layout 1","2"=>"Layout 2");
                  db_select("layout",$aLayout,true,1,""); ?></td>
			 </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan='2' align="center">
        <input name="imprime" type="button" id="imprime" value="Imprime"
               onclick='js_imprimeRelatorio()'>
      </td>
    </tr>
  </table>
  </form>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

sUrlRPC       = 'orc4_ppaRPC.php';
lJaProcessado = <?=$lProcessaManual?"true":"false"; ?>;


function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_ppalei',
                        'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao',
                        'Pesquisa de Leis para o PPA',
                        true);
  }else{
     if(document.form1.o05_ppalei.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_ppalei',
                            'func_ppalei.php?pesquisa_chave='
                            +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                            'Leis PPA',
                            false);
     }else{
       document.form1.o01_descricao.value = '';
     }
  }
}
function js_mostrappalei(chave, erro) {

  document.form1.o01_descricao.value = chave;
  if(erro==true){
    document.form1.o05_ppalei.focus();
    document.form1.o05_ppalei.value = '';
    js_limpaComboBoxPerspectivaPPA();
  } else {
    document.form1.submit();
  }

}
function js_mostrappalei1(chave1,chave2){

  document.form1.o05_ppalei.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
  document.form1.submit();

}

function js_imprimeRelatorio() {

  iProcessado = $('o05_ppaversao').options[$('o05_ppaversao').selectedIndex].processadodespesa;
  if (iProcessado == 1 || $F('selmodelo') == 2) {

    var sQuery  = "?ppalei="+$F('o05_ppalei');
    		sQuery += "&anoini="+$F('o01_anoinicio');
            sQuery += "&ppaversao="+$F('o05_ppaversao');
    		sQuery += "&anofin="+$F('o01_anofinal');
    		sQuery += "&imprimerodape="+$F('selrodape');
    		sQuery += "&selforma="+$F('selforma');
            sQuery += '&sListaInstit='+form1.db_selinstit.value;
    		var sVirgula = "";
    		sQuery += '&iModelo='+$F('selmodelo');
    		sQuery += '&iAno='+$F('selanoldo');
    		sQuery += '&lQuebra='+$F('selquebra');
    	  var oListaPrograma= $('programa').options;
        var sListaPrograma = "";
        var sVirgula = "";
        if (oListaPrograma.length > 0) {

          for (var i = 0; i < oListaPrograma.length; i++) {

            sListaPrograma += sVirgula+oListaPrograma[i].value;
            sVirgula = ",";
           }
       }
      sQuery += "&programa="+sListaPrograma;

      var oListaOrgao = $('orgao').options;
  	  var sListaOrgao = "";
	  sVirgula = "";
	  if (oListaOrgao.length > 0) {

	     		for (var i = 0; i < oListaOrgao.length; i++) {

	         sListaOrgao += sVirgula+oListaOrgao[i].value;
	         sVirgula = ",";

	      	}
	      	sQuery += "&orgao="+sListaOrgao;
    		}

    var iLayout = document.form1.layout.value;
	var sArquivo = "";
	if(iLayout == 1){
		sArquivo = "orc2_demostrativoacoesvalidadas002.php";
	} else{
		sArquivo = "orc2_demostrativoacoesvalidadas002_2.php";
	}

    jan = window.open(sArquivo+sQuery,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  } else {

    alert('N�o existem estimativas calculadas!');
    return false;


  }

}
function js_setModelo(iModelo) {
  if (iModelo != 2) {
    $('mostramodelo').style.display='none';
  } else {
    $('mostramodelo').style.display='';
  }
}
$('selanoldo').style.width='93px';
$('selmodelo').style.width='93px';
$('selquebra').style.width='93px';
$('selrodape').style.width='93px';
js_drawSelectVersaoPPA($('verppa'));
<?
 if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {
   echo "js_getVersoesPPA({$oPost->o05_ppalei})\n";
 }
?>
</script>
