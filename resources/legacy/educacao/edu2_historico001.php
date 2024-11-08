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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$oDaoEduRelatModel    = new cl_edu_relatmodel();
$oDaoTransfEscolaRede = new cl_transfescolarede();
$oDaoEscolaDiretor    = new cl_escoladiretor();
$oDaoRechumanoAtiv    = new cl_rechumanoativ();
$oDaoHistorico        = new cl_historico();
$oRotulo              = new rotulocampo();
$oDaoEduParametros    = new cl_edu_parametros();

$iModulo = db_getsession("DB_modulo");
$iEscola = db_getsession("DB_coddepto");

if (!isset($iTipoAluno)) {
  $iTipoAluno = "1";	
}

$iCodigoAluno = '';
$sNomeAluno   = '';

$sDisplayTable          = 'display-table;';
$sDisplayRow            = 'display-row;';
$sFiltroReclassificacao = 'display-row;';

$sOcultaAluno  = 'none;';

if (isset($oGet->ed47_i_codigo) && !empty($oGet->ed47_i_codigo)) {
	
	$sDisplayTable = 'none';
	$sDisplayRow   = 'none';
	$sOcultaAluno  = 'display-row;';
	$iCodigoAluno  = $oGet->ed47_i_codigo;
	$sNomeAluno    = $oGet->ed47_v_nome;
}

$sSqlEduParametros = $oDaoEduParametros->sql_query_file( 
                                                         null, 
                                                         "ed233_reclassificaetapaanterior",
                                                         null,
                                                         "ed233_i_escola = {$iEscola}" 
                                                       );
$rsEduParametros   = db_query( $sSqlEduParametros );

if ( $rsEduParametros && pg_num_rows( $rsEduParametros ) > 0 ) {
  
  $sMostraEtapaAnterior = db_utils::fieldsMemory( $rsEduParametros, 0 )->ed233_reclassificaetapaanterior;
  
  if ( $sMostraEtapaAnterior == 'f' ) {
    $sFiltroReclassificacao = 'none;';
  }
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  
  	.bloqueado {
  		background-color:#DEB887;
  	}
  </style>
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  
<?
  if (db_getsession("DB_modulo") == 1100747) {
    MsgAviso(db_getsession("DB_coddepto"), "escola");
  }
?>
  <table width="100%;" align="center" style="margin-top: 25px;" bgcolor="#CCCCCC">
   <tr>
    <td valign="top">
     <br>
     <form name="form1" method="post">
      <fieldset style="width:95%"><legend><b>Relat�rio Hist�rico Escolar</b></legend>
       <table border="0">
        <tr colspan= '3'>    
        <tr>
           <td>
             <table style="display: <?php echo $sDisplayTable;?>;"><!--Inicio tabela selects escola filtro-->
               
                <?
                  if ($iModulo == 7159) {   
                    	    	     
                    echo '<td align="left" colspan = "3">'; 
                    echo ' <b>Selecione a escola:</b>';             
                     echo '</td>';
                                echo '<td>';
                
                           $oDaoEscola     = db_utils::getdao('escola');              
                           $sSqlEscola     = $oDaoEscola->sql_query_file("","ed18_i_codigo,ed18_c_nome","","");                                                                      
                           $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);            
                           $iLinhas        = $oDaoEscola->numrows;                       
                           echo '<select name="escola" id="escola" onChange="js_alunoshist(this.value,document.form1.tipoaluno.value, true);" 
                                         style="height:18px;font-size:10px;width:290px">';
                           echo ' <option value="">Selecione a Escola</option>';        
                                   for ($iCont = 0; $iCont < $iLinhas; $iCont++) {                       
                                     $oDadosEscola = db_utils::fieldsmemory($rsResultEscola,$iCont);                          
                                     echo " <option value='$oDadosEscola->ed18_i_codigo'>$oDadosEscola->ed18_c_nome</option>";               
                                   }                   
                           echo ' </select>';
                           echo '</td>';
                  } else {
                    	
                    $iEscola = db_getsession("DB_coddepto");
                    echo "<input type= 'hidden' id ='escola' value = '$iEscola' >";
                      
                  }
                ?>          
                </tr>
               <tr>
                <td colspan='3'>
                 <b>Filtro:</b>
                 </td><td>
                 <select id="tipoaluno" name="tipoaluno" style="height:18px;font-size:10px;width:290px;"  
                         onchange="js_alunoshist(document.form1.escola.value,this.value);">
                  <option value="1" <?=$iTipoAluno == "1" ? "selected" : ""?>>Alunos vinculados nesta escola</option>
                  <option value="2" <?=$iTipoAluno == "2" ? "selected" : ""?>>Alunos sem v�nculos com escolas</option>
                  <option value="3" <?=$iTipoAluno == "3" ? "selected" : ""?>>Alunos transferidos na rede</option>
                 </select>
                </td>
               </tr>
             </table>
           </td>
        </tr>
        <tr style="display: <?php echo $sDisplayRow;?>;">
          <td style="width:450px;">          
              <b>Alunos com registro de Hist�rico:</b><br>
              <select name="alunoshist" id="alunoshist" size="20" onclick="js_desabinc()"  
                      multiple style="font-size:9px;width:450px;">            
            </select>
          </td>
          <td align="center">
            <br>
            <table >
              <tr>
               <td>
                <input id="incluirum" name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" 
                       style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                              font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
               </td>
              </tr>
              <tr><td height="1"></td></tr>
              <tr>
               <td>
                <input id="incluirtodos" name="incluirtodos" title="Incluir Todos" 
                       type="button" value=">>" onclick="js_incluirtodos();" 
                       style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                              font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
               </td>
              </tr>
              <tr><td height="3"></td></tr>
              <tr>
               <td>
                <hr>
               </td>
              </tr>
              <tr><td height="3"></td></tr>
              <tr> 
               <td>
                <input id="excluirum" name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
                       style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                              font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
               </td>
              </tr>
              <tr><td height="1"></td></tr>
              <tr>
               <td>
                <input id="excluirtodos" name="excluirtodos" title="Excluir Todos" type="button" value="<<" 
                       onclick="js_excluirtodos();" 
                       style="border:1px outset;border-top-color:#f3f3f3;
                              border-left-color:#f3f3f3;background:#cccccc;font-size:12px;font-weight:bold;width:30px;
                              height:15px;padding:0px;" disabled>
               </td>
              </tr>
            </table>
           </td>
           <td>
             <b>Alunos para impress�o :</b><br>
             <select name="alunos[]" id="alunos" size="20" onclick="js_desabexc()"  
                     multiple style="font-size:9px;width:350px;">
             </select>
           </td>
        </tr>
       
       <tr id='alunoHistorico' style="display: <?php echo $sOcultaAluno;?>" >
         <td><label style="width:98px; display: block; float: left;"><b>Aluno:</b></label>
           <input class='bloqueado' type="text" id='aluno' name='aluno' value="<?php echo $iCodigoAluno;?>" size='10' 
                  readonly="readonly" />
           <input class='bloqueado' type="text" id='nome' name='nome' value="<?php echo $sNomeAluno;?>" size='50' 
                  readonly="readonly" />
         </td>
       </tr>
       
       <tr>
        <td nowrap colspan = '3'>
        <table><!--inicio table -->
        <tr>
         <td nowrap colspan= '2'>
          <b>Tipo do Modelo:</b>
         </td>
         <td>
          <select name="tipohistorico" id="tipohistorico" style="font-size:9px;width:290px;" Onchange = "js_Orientacao();">           
         </select>
        </td>
       </tr>
       <tr id='trEtapasLancadas' style='display: none'>
        <td colspan='2'><b>Exibir Etapas:</b></td>
        <td>
          <select id="etapasLancadas" style="font-size:9px;width:290px;">
            <option value="1">Somente Etapas Registradas</option>
            <option value="2">Todas Etapas do Curso</option>
          </select>
        </td>
       </tr>
       <tr id='tdOrientacao' style='display: none'>
        <td colspan='2'>
         <b>Orienta��o:</b>
        </td>
        <td>
         <select name="orientacao" id="orientacao" style="font-size:9px;width:290px;" onchange='js_validaTipoRegistro()'>
         </select>
        </td>
       </tr>
       <tr id = 'tdCabecalho'>
        <td colspan='2'>
         <b>Disposi��o do Cabe�alho:</b>
        </td>
        <td>
         <select name="disposicao" id="disposicao" style="font-size:9px;width:290px;" >
          <option value='0'>Selecione</option>
          <option value='1'>Disposi��o 1</option>
          <option value='2'>Disposi��o 2</option>
         </select>
        </td>
       </tr>   
       <tr>
        <td nowrap colspan= '2'>
         <b>Registros:</b>
         </td><td>
         <select name="tiporegistro" id= "tiporegistro" style="font-size:9px;width:290px;">
          <option value='A'>Etapas APROVADAS</option>
          <option value='AR'>Etapas APROVADAS e REPROVADAS</option>
          <option value='U'>Listar �ltimo Registro</option>
         </select>
        </td>
       </tr>
       
       <tr style="display: <?php echo $sFiltroReclassificacao; ?>">
         <td colspan= '2'><label class="bold">Exibir Reclassifica��o:</label></td>
         <td>
           <select name="exibir_reclassificacao" id= "exibir_reclassificacao" style="font-size:9px;width:290px;">
             <option value='f' selected>N�O</option>
             <option value='t'>SIM</option>
           </select>
         </td>
       </tr>
       
       <tr>
         <td nowrap colspan= '2'>
          <b>Diretor:</b>          
         </td>
         <td>
          <select id="select_diretor" name="select_diretor" style="font-size:9px;width:290px;">        
          </select>
         </td>
        </tr>
        <tr>
         <td nowrap colspan= '2'>
          <b>Secret�rio:</b>           
         </td>
         <td>
          <select id="select_secretario" name="select_secretario" style="font-size:9px;width:290px;">          
          </select>
         </td>
        </tr>
        </table> <!--fecha table-->
        </td></tr>
        <tr>
         <td colspan='3'>
          <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();" disabled>
         </td>
        </tr>
        <tr style="display: <?php echo $sDisplayRow;?>;">
         <td align="center" valign="top"  colspan="3">
          <br>
          <fieldset style="align:center">
           Para selecionar mais de um aluno mantenha pressionada a tecla CTRL e clique sobre o nome dos alunos.
           <div id= 'div'>
            <h4 align= 'left'>Disposi��es do Cabe�alho</h4>
             1 - Neste modelo o sistema posicionar� as informa��es lado a lado na seguinte ordem :
                 Bras�o da Rep�blica Federativa do Brasil ou logotipo do munic�pio, texto inserido no campo cabe�alho
                 e o nome da escola, mantenedora, endere�o e atos legais.<br>
             2 - Neste modelo o sistema posicionar� as informa��es centralizadas uma abaixo da outra:
                 Bras�o da Rep�blica Federativa do Brasil ou logotipo do munic�pio e texto inserido no campo cabe�alho no topo do
                 cabe�alho centralizado, abaixo deste o t�tulo do documento e abaixo deste o nome da escola, mantenedora,
                 atos legais e endere�o.

            </div> 
          </fieldset>
         </td>
        </tr>
       </table>
      </fieldset>
     </td>
    </tr>
   </table>
  </form>
  <div style="display: <?php echo $sDisplayTable;?>;">
   <?
     db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
             db_getsession("DB_anousu"),db_getsession("DB_instit")
            );
   ?>
  </div>
 </body>
</html>
<script>

var oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_historico001.php');
oDBFormCache.setElements(new Array($('etapasLancadas')));
oDBFormCache.load();

$('etapasLancadas').onchange = function() {
  oDBFormCache.save();
};

<?
if ($iModulo != 7159) { 
	echo "js_alunoshist($iEscola, 1)";
}
?>

var oGet = js_urlToObject();

function js_alunoshist(escola, tipoaluno) {

  var oParam           = new Object(); 
      oParam.exec      = "PesquisaAlunoHist";
      oParam.escola    = escola;
      oParam.tipoaluno = tipoaluno;

  var url              = 'edu4_escola.RPC.php';    

  js_webajax(oParam,'js_retornoPesquisaAlunoHist',url);
	js_limpa();

}


function js_retornoPesquisaAlunoHist(oRetorno) {    

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
	    
  if (oRetorno.iStatus  != 1) {
	      
    alert(oRetorno.sMessage.urlDecode());
    return false;
	  
  } else {
	                         	    
  	for (var i = 0;i < oRetorno.aResultHistorico.length; i++) {
		
	    with (oRetorno.aResultHistorico[i]) {
	                        
	      sHtml += '<option value="'+oRetorno.aResultHistorico[i].ed47_i_codigo+'">';
	      sHtml += oRetorno.aResultHistorico[i].ed47_v_nome.urlDecode()+" - "+oRetorno.aResultHistorico[i].ed47_i_codigo+'</option>';     	        
	             
	    }   
	              
	  }
	  $('incluirtodos').disabled            = false;	
	  $('alunoshist').innerHTML             = sHtml;
	  document.form1.alunoshist[0].selected = true;

  }  
	    
  $('alunoshist').disabled  = false;
  js_TipoRelatorio();
  js_Orientacao();

  if (oRetorno.iEscola != "") {

    js_Diretor(oRetorno.iEscola);
    js_Sec(oRetorno.iEscola);
    
  } else {
   
	  js_Diretor($('escola').value);
	  js_Sec($('escola').value);
	   
  }

}

function js_Diretor(escola) {

  var oParam        = new Object(); 
      oParam.exec   = "getDiretor";
      oParam.escola = $('escola').value;

  var url           = 'edu4_escola.RPC.php';    

  js_webajax(oParam,'js_retornoPesquisaDiretor',url);
	      
}

function js_retornoPesquisaDiretor(oRetorno) {
	  

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
	      
  if (oRetorno.iStatus  != 1) {
	        
//  	alert(oRetorno.sMessage.urlDecode());
  	return false;
	      
  } else {                                 

	  sHtml += '<option value="">Selecione o Diretor</option>';    
	      
	  for (var i = 0;i < oRetorno.aResultDiretor.length; i++) {
	                        
	    with (oRetorno.aResultDiretor[i]) {
	      
	      sFuncao    = oRetorno.aResultDiretor[i].funcao;
	      sNome      = oRetorno.aResultDiretor[i].nome;
	      sDescricao = oRetorno.aResultDiretor[i].descricao;
	      sTipo      = oRetorno.aResultDiretor[i].tipo;
	      sValue     = sFuncao+" - "+sNome+" - "+sDescricao;
	      sText      = sFuncao+" - "+sNome+" - "+sDescricao;    
	     
	      sHtml     += '<option value="'+sValue+'">';
	      sHtml     += sText.urlDecode()+'</option>';
	                           
	    }  
	                
	  }
	      
	  $('select_diretor').innerHTML             = sHtml;
	  document.form1.select_diretor[0].selected = true;
	      
  }  

  $('select_diretor').disabled  = false;
	          
}

function js_Sec(escola) {
	    
  var oParam        = new Object(); 
      oParam.exec   = "getSecretario"; 
      oParam.escola = $('escola').value;

  var url           = 'edu4_escola.RPC.php';    

  js_webajax(oParam,'js_retornoPesquisaSec',url);
	        
}

function js_retornoPesquisaSec(oRetorno) {
	    

  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
	        
  if (oRetorno.iStatus  != 1) {
	          
//	  alert(oRetorno.sMessage.urlDecode());
	  return false;
	        
  } else {                                 

	  sHtml += '<option value="">Selecione o Secret�rio</option>';
	      
   	for (var i = 0;i < oRetorno.aResultSec.length; i++) {
	                          
	    with (oRetorno.aResultSec[i]) {
	        
	      sFuncao    = oRetorno.aResultSec[i].funcao;
	      sNome      = oRetorno.aResultSec[i].nome;
	      sDescricao = oRetorno.aResultSec[i].descricao;
	      sTipo      = oRetorno.aResultSec[i].tipo;
	      sValue     = sFuncao+" - "+sNome+" - "+sDescricao;
	      sText      = sFuncao+" - "+sNome+" - "+sDescricao;    
	       
	      sHtml     += '<option value="'+sValue+'">';
 	      sHtml     += sText.urlDecode()+'</option>';
	                             
	    }  
	                  
	  }
	        
  	$('select_secretario').innerHTML             = sHtml;
  	document.form1.select_secretario[0].selected = true;
	                
	        
  }  

  $('select_secretario').disabled  = false;
	            
}

function js_TipoRelatorio() {
         
  var oParam            = new Object(); 
      oParam.exec       = "getTipoHistorico"; 
      oParam.escola     = $('escola').value;
      oParam.iRelatorio = $('tipohistorico').value;
  var url               = 'edu4_escola.RPC.php';    

  js_webajax(oParam,'js_retornoPesquisaTipoHistorico',url);
                              
}





function js_retornoPesquisaTipoHistorico(oRetorno) {
     
  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
                        
  if (oRetorno.iStatus  != 1) {
    
    alert(oRetorno.sMessage.urlDecode());
    return false;
                                                      
  } else {                                 
	    	    
    for (var i = 0;i < oRetorno.aResultTipoHistorico.length; i++) {
                                                                                                    
      with (oRetorno.aResultTipoHistorico[i]) {

        iCodigo = oRetorno.aResultTipoHistorico[i].ed217_i_codigo;       
        sNome   = oRetorno.aResultTipoHistorico[i].ed217_c_nome;        

        sHtml   += '<option value="'+iCodigo+'">';
        sHtml   += sNome.urlDecode()+'</option>';
                                   
      }  
                                                                                                                    

      $('tipohistorico').innerHTML             = sHtml;
      document.form1.tipohistorico[0].selected = true;                                                                                                                                                      
                                                                                                                                                     
    }  

    $('tipohistorico').disabled  = false;
                                                                                                                                                                         
  }

}
  
function js_Orientacao() {
         
  var oParam            = new Object(); 
      oParam.exec       = "getOrientacao"; 
      oParam.escola     = $('escola').value;
      oParam.iRelatorio = $('tipohistorico').value;
  var url               = 'edu4_escola.RPC.php';    

  js_webajax(oParam,'js_retornoPesquisaOrientacao',url);
                              
}



function js_retornoPesquisaOrientacao(oRetorno) {
     
  var oRetorno = eval("("+oRetorno.responseText+")");
  sHtml        = '';
                        
  if (oRetorno.iStatus  != 1) {

    $('orientacao').innerHTML           = '';
    $('tdCabecalho').style.display      = 'none';
    $('trEtapasLancadas').style.display = 'none';
    $('div').style.display              = 'none';
    js_validaTipoRegistro();
    return false;
                                                      
  } else {                                 
    
    $('trEtapasLancadas').style.display = '';
    $('tdCabecalho').style.display      = '';
    $('div').style.display              = '';

    var lTemOrientacaoRetrato = false; //Para identificar se o cliente possui modelo retrato cadastrado
                                                                    
    for (var i = 0;i < oRetorno.aResultOrientacao.length; i++) {
                                                                                                    
      with (oRetorno.aResultOrientacao[i]) {

        if (oRetorno.aResultOrientacao[i].ed217_orientacao == 2) {
          lTemOrientacaoRetrato = true;
        }
        
        sOrientacao  = oRetorno.aResultOrientacao[i].ed217_orientacao;       
        sNome        = oRetorno.aResultOrientacao[i].nome;        

        sHtml       += '<option value="'+sOrientacao+'">';
        sHtml       += sNome.urlDecode()+'</option>';
                                   
      }  
    }

    $('orientacao').innerHTML             = sHtml;
    
    document.form1.orientacao[0].selected = true;                                                                                                                                                      
                                                                                                                                                   
  }  
  js_validaTipoRegistro();
  $('orientacao').disabled  = false;
                                                                                                                                                                       
}

function js_pesquisa(curso) {  

  alunos  = ""; 
  tipovar = "";
  sep     = "";

  if (!oGet.ed47_i_codigo) {
    for (i = 0; i < $('alunos').length; i++) {
	    
      alunos += sep+$('alunos').options[i].value;
      sep     = ",";
      
    }
  } else {
    alunos = $F('aluno');
  }
  
  if ($('tipohistorico').value == "") {
	  
    alert("Informe o Tipo do Modelo!");
    return false;
    
  }

  var sParametros  = 'alunos='+alunos+'&iTipoRelatorio='+$('tipohistorico').value;
      sParametros += '&iTipoRegistro='+$('tiporegistro').value+'&sDiretor='+$('select_diretor').value;
      sParametros += '&sSecretario='+$('select_secretario').value+'&iEscola='+$('escola').value;
      sParametros += '&sDisposicao='+$('disposicao').value+'&sExibirReclassificacao='+$('exibir_reclassificacao').value;
      sParametros += '&iExibirTodasEtapas=' + $F('etapasLancadas');
  
  
  if ($('orientacao').value == 2) {
    
    if ($('disposicao').value == 0) {

      alert('Escolha uma disposi��o!');
      return false
    }
    
    jan = window.open('edu2_historicoescolarretrato002.php?' + sParametros,'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    
  } else { 

    jan = window.open('edu2_historico002.php?' + sParametros,'',
		                  'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

}

function js_incluir() {
	
 var Tam = $('alunoshist').length;
 var F   = document.form1;
 
 for (x = 0; x < Tam; x++) {
	 
   if (F.alunoshist.options[x].selected == true) {
	   
     F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunoshist.options[x].text,
    	                                                                                  F.alunoshist.options[x].value)
     F.alunoshist.options[x] = null;
     Tam--;
     x--;
     
   }

 }
 
 if ($('alunoshist').length > 0) {
   $('alunoshist').options[0].selected = true;
 } else {
	 
   $('incluirum').disabled    = true;
   $('incluirtodos').disabled = true;
   
 }
 
 $('pesquisar').disabled    = false;
 $('excluirtodos').disabled = false;
 $('alunos').focus();
 
}

function js_incluirtodos() {
	
  var Tam = $('alunoshist').length;
  var F   = document.form1;
  for (i = 0; i < Tam; i++) {  
	  
    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunoshist.options[0].text,
    	                                                                               F.alunoshist.options[0].value)
    F.alunoshist.options[0] = null;
    
  }
  
  $('incluirum').disabled    = true;
  $('incluirtodos').disabled = true;
  $('excluirtodos').disabled = false;
  $('pesquisar').disabled    = false;
  $('alunos').focus();

}

function js_excluir() {
	
  var F = document.getElementById("alunos");
  Tam   = F.length;
  
  for (x = 0; x < Tam; x++) {
	  
    if (F.options[x].selected == true) {
        
      document.form1.alunoshist.options[document.form1.alunoshist.length] = new Option(F.options[x].text,
    	                                                                               F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
      
    }

  }
  
  if ($('alunos').length > 0) {
    $('alunos').options[0].selected = true;
  }

  if ($('alunos').length == 0) {
	  
    $('pesquisar').disabled    = true;
    $('excluirum').disabled    = true;
    $('excluirtodos').disabled = true;
    $('incluirtodos').disabled = false;
    
  }
  
  $('alunoshist').focus();
   
}

function js_excluirtodos() {
	
  var Tam = $('alunos').length;
  var F   = document.getElementById("alunos");

  for (i = 0; i < Tam; i++) {
	  
    $('alunoshist').options[$('alunoshist').length] = new Option($('alunos').options[0].text,
    	                                                         $('alunos').options[0].value);
    $('alunos').options[0] = null;
    
  }
  
  if ($('alunos').length == 0) {
	  
	  $('pesquisar').disabled    = true;
    $('excluirum').disabled    = true;
    $('excluirtodos').disabled = true;
    $('incluirtodos').disabled = false;
    
  } 

  $('alunoshist').focus();

}

function js_limpa() {
       
  var Alunos = document.getElementById("alunos");
  for (var i = 0; i < Alunos.length; i++) { 
    Alunos.length = 0;
  }

  if (Alunos.length == 0) {
                                         
    $('pesquisar').disabled              = true;
    $('excluirum').disabled              = true;
    $('excluirtodos').disabled           = true;
    $('incluirtodos').disabled           = false;
    $('tipohistorico').selectedIndex     = 0;
    $('tiporegistro').selectedIndex      = 0;
    $('select_diretor').selectedIndex    = 0;
    $('select_secretario').selectedIndex = 0;
                                                                                               
  }

  $('alunoshist').focus(); 

}

function js_desabinc() {
	
  for (i = 0; i < $('alunoshist').length; i++) {
	  
    if ($('alunoshist').length > 0 && $('alunoshist').options[i].selected) {
        
      if ($('alunos').length > 0) {          
        $('alunos').options[0].selected = false;
      }
      
      $('incluirum').disabled = false;
      $('excluirum').disabled = true;
      
    }
    
  }
  
}

function js_desabexc() {
	
  for (i = 0; i < $('alunos').length; i++) {
	  
    if ($('alunos').length > 0 && $('alunos').options[i].selected) {
        
      if ($('alunoshist').length > 0) {
        $('alunoshist').options[0].selected = false;
      }
      
      $('incluirum').disabled = true;
      $('excluirum').disabled = false;
      
    }
    
  }
  
}

function js_OrdenarLista(combo) {
	
  var lb    = document.getElementById(combo);
  arrTexts  = new Array();
  arrValues = new Array();
  
  for (i = 0; i < lb.length; i++) {
	  
    arrValues[i] = lb.options[i].value;
    arrTexts[i]  = lb.options[i].text;
    
  }
  
  arrTexts.sort();
  for (i = 0; i < lb.length; i++) {
	  
    lb.options[i].text  = arrTexts[i];
    lb.options[i].value = arrValues[i];
    
  }
  
}

function js_validaTipoRegistro() {

  var iTipoOrientacaoRelatorio    = $F('orientacao');
  var sDisplayAprovadosReprovados = 'inline';

  if (iTipoOrientacaoRelatorio == 2) {

    sDisplayAprovadosReprovados = 'none';

    if ( $('tiporegistro').value == "AR" ) {
      $('tiporegistro').value = "A";
    }
  }

  $('tiporegistro').options[1].style.display = sDisplayAprovadosReprovados;
}
js_validaTipoRegistro();


if (oGet.ed47_i_codigo != '') {
  $('pesquisar').disabled  = false;
}
</script>