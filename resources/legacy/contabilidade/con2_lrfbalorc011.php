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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");

$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('o116_periodo');

$oRelatorio = new relatorioContabil($oGet->c83_codrel);

db_postmemory($HTTP_POST_VARS);

$iAnoUsu = db_getsession("DB_anousu");

$sLabelMsg = "Anexo I - Balan�o Or�ament�rio";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>

variavel = 1;

function js_buscaEdicaoLrf(iAnousu,sFontePadrao){

  if (iAnousu == "2013" || iAnousu == "2014") {
    sNomeArquivoEdicao = "con2_lrfbalorc002_2013.php";
  } else if (iAnousu == "2015") {
    sNomeArquivoEdicao = "con2_lrfbalorc002_2015.php";
  } else {

    var url       = 'con4_lrfbuscaedicaoRPC.php';
    var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao ;
    var objAjax   = new Ajax.Request (url, { method:'post',
                                             parameters:parametro,
                                             onComplete:js_setNomeArquivo}
                                      );
  }
}

function js_setNomeArquivo(oResposta){
  sNomeArquivoEdicao = oResposta.responseText;
}

js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?>,'con2_lrfbalorc002');

function js_emite(){

  var sel_instit  = new Number(document.form1.db_selinstit.value);
  var sel_periodo = document.form1.o116_periodo.value;

  if (sel_periodo == "0"){
    alert("Selecione um periodo");
    return false;
  }

  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Voc� n�o escolheu nenhuma Institui��o. Verifique!');
    return false;
  }else{
    var query = "";
    var obj   = document.form1;

    query  =  sNomeArquivoEdicao+"?db_selinstit="+obj.db_selinstit.value;
    console.log('obj.o116_periodo.value: '+obj.o116_periodo.value,'$oGet->c83_codrel',<?=$oGet->c83_codrel?>);
    query += "&bimestre="+obj.o116_periodo.value;
    query += "&relatorio="+<?=$oGet->c83_codrel?>;


    obj = document.form1;
    jan = window.open(query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

    jan.moveTo(0,0);
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name="form1" method="post" action="" >
	  <table align="center" border="0" cellpadding="0" cellspacing="0">
	   <tr>
	    <td >&nbsp;</td>
	   </tr>
	   <tr>
	    <td colspan=3  class='table_header'>
	     <?=$sLabelMsg?>
	    </td>
	   </tr>
	   <tr>
	    <td>
	      <fieldset>
	       <legend><b>Filtros</b></legend>
			    <table  align="center">
			      <tr>
			        <td align="center" colspan="2">
			          <?
			            db_selinstit('',300,100);
			          ?>
			        </td>
			      </tr>
			      <tr>
			        <td align="right" nowrap>
			          <b>Bimestre :</b>
			        </td>
			        <td>
			          <?
			           if ($iAnoUsu < 2010 ) {

                   $aListaPeriodos = array(
                                    "1B" => "1 � Bimestre",
                                    "2B" => "2 � Bimestre",
                                    "3B" => "3 � Bimestre",
                                    "4B" => "4 � Bimestre",
                                    "5B" => "5 � Bimestre",
                                    "6B" => "6 � Bimestre",
                                    );
                  } else {

                     $aPeriodos = $oRelatorio->getPeriodos();
                     $aListaPeriodos = array();
                     $aListaPeriodos[0] = "Selecione";
                     foreach ($aPeriodos as $oPeriodo) {
                       $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                     }
                  }
                  db_select("o116_periodo", $aListaPeriodos, true, 1);
			          ?>
			        </td>
			      </tr>
			    </table>
	      </fieldset>
	      <table align="center">
	        <tr>
            <td>&nbsp;</td>
	        </tr>
          <tr>
            <td align="center" colspan="2">
               <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
            </td>
          </tr>
	      </table>
	    </td>
	   </tr>
	  </table>
  </form>
</body>
</html>