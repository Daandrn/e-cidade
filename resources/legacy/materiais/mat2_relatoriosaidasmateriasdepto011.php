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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("std/db_stdClass.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_parcustos_classe.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);

$clparcustos = new cl_parcustos;
$aux = new cl_arquivo_auxiliar;

$rsParam                  = $clparcustos->sql_record($clparcustos->sql_query_file(db_getsession("DB_anousu"),"cc09_tipocontrole","","cc09_instit = ".db_getsession("DB_instit")) );
if($clparcustos->numrows > 0){
 db_fieldsmemory($rsParam,0);
}else{
 $cc09_tipocontrole = 0;
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js,
                  strings.js,
                  prototype.js,
                  datagrid.widget.js,
                  widgets/DBLancador.widget.js,
                  estilos.css,
                  grid.style.css
                 ");

    db_app::load("widgets/windowAux.widget.js,
    		          widgets/dbtextField.widget.js,
    		          dbmessageBoard.widget.js,
    		          dbcomboBox.widget.js,
    		          prototype.maskedinput.js,
    	 	          DBTreeView.widget.js,
    		          arrays.js");


  ?>


<style >

  #fieldset_departamentos{

    width:600px;
  }
  #departamentos  {
    width: 100% !important
  }
  #fieldset_departamentos table{

    width:600px;
  }

</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >

  <table    align="center" border='0'>
    <form name="form1" method="post" action="">
       <tr>
           <td>
                <strong>Op��es:</strong>
            </td>
            <td>
                <select name="ver">
                    <option name="condicao1" value="com">Com os departamentos selecionados</option>
                    <option name="condicao1" value="sem">Sem os departamentos selecionados</option>
                </select>
          </td>
       </tr>
      <tr>
       <td colspan="3">
         <div id="ctnDepartamentoOrigem"></div>
       </td>
      </tr>

      <tr>
        <td colspan=3 >
          <div id='ctnDptoDestino'></div>
        </td>
      </tr>


	      <tr>
	        <td>
	          <strong>Grupo / Subgrupo:</strong>
	        </td>
	        <td>
	          <input type="button" name="escolheGrupo" id ="escolheGrupo" value="Escolher" onclick="js_escolheGrupoSubgrupo();" class="inputPadrao" >
	        </td>
	      </tr>


	      <tr id='data' style='display:""'>
	        <td>
	          <b>Per�odo: </b>
	        </td>
	        <td>
	          <?
	           db_inputdata('data1','','','',true,'text',1,"");
	           echo "<b> a</b> ";
	           db_inputdata('data2','','','',true,'text',1,"");
	         ?>&nbsp;
		      </td>
	      </tr>
	      <tr id='ordem' style='display:""'>
	        <td align="left"  title="Ordem por Codigo/Departamento/Alfab�tica" >
	          <strong>Ordem:</strong>
	        </td>
	        <td>
		       <?
		       $tipo_ordem = array(
              "c" => "Alfab�tica",
              "a" => "Codigo",
              "b" => "Departamento",
              "d" => "Data"
            );
		       db_select("ordem",$tipo_ordem,true,2); ?>
	   	    </td>
		    </tr>

        <tr>
          <td><strong>Agrupar por departamento:</strong></td>
          <td>
            <select name="agrupar_dpto">
              <option value="nao" selected>N�o</option>
              <option value="sim">Sim</option>
            </select>
          </td>
        </tr>



      <tr>
        <td colspan="2" align = "center"><br>
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
          <input  name="imprimircsv" id="imprimircsv" type="button" value="Exportar CSV" onclick="js_imprimircsv();" >
        </td>
      </tr>
  </form>
 </table>
</body>
</html>
<script>

var oLancadorDestino = new DBLancador('LancadorDestino');
    oLancadorDestino.setLabelAncora("Depart.:");
    oLancadorDestino.setTextoFieldset("Departamentos - Destino");
    oLancadorDestino.setTituloJanela("Pesquisar Departamentos - Destino");
    oLancadorDestino.setNomeInstancia("oLancadorDestino");
    oLancadorDestino.setParametrosPesquisa("func_db_depart.php", ["coddepto", "descrdepto"]);
    oLancadorDestino.setGridHeight(150);
    oLancadorDestino.show($("ctnDptoDestino"));

var oLancadorOrigem = new DBLancador('LancadorOrigem');
    oLancadorOrigem.setLabelAncora("Depart.:");
    oLancadorOrigem.setTextoFieldset("Departamentos - Origem");
    oLancadorOrigem.setTituloJanela("Pesquisar Departamentos - Origem");
    oLancadorOrigem.setNomeInstancia("oLancadorOrigem");
    oLancadorOrigem.setParametrosPesquisa("func_db_depart.php", ["coddepto", "descrdepto"]);
    oLancadorOrigem.setGridHeight(150);
    oLancadorOrigem.show($("ctnDepartamentoOrigem"));


function js_testord(valor){
	if (valor=='S'){
		document.form1.ordem.value='b';
		document.form1.ordem.disabled=true;
	}else{
		document.form1.ordem.value='a';
		document.form1.ordem.disabled=false;
	}
}


var windowGrupo = '';
var oTreeViewGrupos = new DBTreeView('treeViewGrupo');

function js_escolheGrupoSubgrupo() {

  if ( !(windowGrupo instanceof windowAux) ) {

    var iTamWidth          = screen.availWidth / 2;
    var iTamHeight         = screen.availHeight / 2;
    var iTamHeightFieldset = iTamHeight - 150;

    /**
     *  Configura��es do componente WindowAux
     */
    windowGrupo = new windowAux("escolheGrupo", "Escolha de Grupos/Subgrupos", iTamWidth, iTamHeight);
    var sContent  = "<div id='divEscolheGrupo' style=''>";
        sContent += "  <div id='divListaGrupo' style='padding: 2px;'>";
        sContent += "    <fieldset id='' style='height:"+iTamHeightFieldset+";'>";
        sContent += "       <div id='ctnTreeView' style='width:100%'>";
        sContent += "       </div>";
        sContent += "    </fieldset>";
        sContent += "    <center>";
        sContent += "      <input type='button' name='btnSalvarGrupos' id='btnSalvarGrupos' value='Salvar' onclick='windowGrupo.hide();'>";
        sContent += "    </center>";
        sContent += "  </div>";
        sContent += "</div>";

    windowGrupo.setContent(sContent);
    windowGrupo.setShutDownFunction(function() {
      windowGrupo.hide();
    });
    oTreeViewGrupos.show($('ctnTreeView'));
    oNoPrincipal = oTreeViewGrupos.addNode("0", "Grupos / Subgrupos");
    js_divCarregando("Aguarde, buscando registros","msgBox");
    var sRPCArq = 'mat4_materialgrupo.RPC.php';
    var oParam  = new Object();
    oParam.exec = 'getGrupos';
    var oAjax   = new Ajax.Request(sRPCArq,
                                   {method: 'post',
                                    asynchronous: false,
                                    parameters: 'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoWindowGrupo
                                   }) ;


    oTreeViewGrupos.allowFind(true);
    oTreeViewGrupos.setFindOptions('matchedonly');

    /**
     *  Configura��es do componente Message Board
     */
    var sIdMsgBoard       = "helpWindowGrupo";
    var sTitleMsgBoard    = "Escolha de Grupos / Subgrupos";
    var sHelpMsgBoard     = "Escolha os grupos/subgrupos para adicionar ao filtro.";
    var oWhereAddMsgBoard = "escolheGrupo";
    var ajudaWindowGrupo  = new DBMessageBoard(sIdMsgBoard, sTitleMsgBoard, sHelpMsgBoard,
                                               windowGrupo.getContentContainer());


  }
  windowGrupo.show();
}

function js_retornoWindowGrupo (oAjax) {

  js_removeObj("msgBox");
  var oRetorno      = eval("(" + oAjax.responseText + ")");
  var iTotalRetorno = oRetorno.aGrupos.length;
  var sRetornoInfo  = oRetorno.aGrupos;

  for (var i = 0; i < iTotalRetorno; i++) {


    with(oRetorno.aGrupos[i]) {

      var iRetNivel      = nivel;
      var sRetLabel      = estrutural+" - "+descricaogrupo.urlDecode();
      var sRetParentNode = conta_pai;
      oCheck = function (oNode, event) {

       if (oNode.checkbox.checked) {
           oNode.checkAll(event);
        } else {
           oNode.uncheckAll(event);
        }
      }
      oTreeViewGrupos.addNode(codigogrupo,
                              sRetLabel,
                              sRetParentNode,
                              null,
                              null,
                              {checked:false,
                               onClick:oCheck
                              }
                              );
    }
  }
   oNoPrincipal.expand();
}

function js_mandadados(){

  var urls = {
    padrao: 'mat2_relatoriosaidasmateriasdepto002.php',
    separaDepartamentos: 'mat2_relatoriosaidasmateriasdepto002_deptos.php'
  };




 query       = "";
 vir         = "";
 listadepart = "";

 vir                = "";
 listadepartDestino = "";
 oLancadorDestino.getRegistros().each( function( oDados, iIndice){

   listadepartDestino += vir + oDados.sCodigo;
   vir=",";
 });

  vir                = "";
  listadepartOrigem = "";
  oLancadorOrigem.getRegistros().each( function( oDados, iIndice){

    listadepartOrigem += vir + oDados.sCodigo;
    vir=",";
  });

 vir      = "";
 listamat = "";

 for ( x = 0; x < parent.iframe_g2.document.form1.material.length;x++){
  listamat+=vir+parent.iframe_g2.document.form1.material.options[x].value;
  vir=",";
 }

 vir="";
 listausu="";
 for(x=0;x<parent.iframe_g3.document.form1.usuario.length;x++){
  listausu+=vir+parent.iframe_g3.document.form1.usuario.options[x].value;
  vir=",";
 }

 vir        = "";
 listamatestoquetipo = "";
 obj        = parent.iframe_g4.db_iframe_matestoquetipo.document.getElementsByTagName("input");
 nObj       = obj.length;

 for (x=0 ; x < nObj;x++){

   if (obj[x].type == "checkbox" && obj[x].checked==true){
     listamatestoquetipo += vir+obj[x].value;
     vir = ",";
   }

 }

 vir        = "";
 listaorgao = "";
 obj        = parent.iframe_g4.db_iframe_orgao.document.getElementsByTagName("input");
 console.log(obj);
 nObj       = obj.length;

 for (x=0 ; x < nObj;x++){

   if (obj[x].type == "checkbox" && obj[x].checked==true){
     listaorgao += vir+obj[x].value;
     vir = ",";
   }

 }

 var sDataIni = new String(document.form1.data1.value).trim();
 var sDataFim = new String(document.form1.data2.value).trim();


 if ( sDataIni == '' && sDataFim == '' ) {
   alert('Favor informe algum per�odo!');
   return false;
 } else if ( sDataIni == '' ) {
   alert('Favor informe per�odo inicial!');
   return false;
 } else if ( sDataFim == '' ) {
   alert('Favor informe per�odo final!');
   return false;
 }


 var aLinhas = oTreeViewGrupos.getNodesChecked();
 var aContas = new Array();

 aLinhas.each (
   function(oRetornoCheck) {

     aContas.push(oRetornoCheck.value);
   }
 );


 query+='&listadepart=' + listadepartOrigem;
 query+='&verdepart='   + document.form1.ver.value;
 query+='&listamat='    + listamat;
 query+='&vermat='      + parent.iframe_g2.document.form1.ver.value;
 query+='&listausu='    + listausu;
 query+='&verusu='      + parent.iframe_g3.document.form1.ver.value;
 query+='&dataini='     + sDataIni;
 query+='&datafin='     + sDataFim;
 query+='&listadepartDestino='  + listadepartDestino;
 query+='&ordem='               + document.form1.ordem.value;
 query+='&listaorgao='          + listaorgao;
 query+='&listamatestoquetipo=' +listamatestoquetipo;
 query+='&separaDepartamentos=' + document.form1.agrupar_dpto.value;

 query+= '&grupos=' + aContas.implode(',');


 var URL = document.form1.agrupar_dpto.value == 'sim' ? 'separaDepartamentos' : 'padrao';

 jan = window.open( urls[URL] + '?' + query,'',
                   'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);

}

function js_imprimircsv() {

    var urls = {
        padrao: 'mat2_relatoriosaidasmateriasdeptocsv002.php',
        separaDepartamentos: 'mat2_relatoriosaidasmateriasdeptocsv002_deptos.php'
    };




    query       = "";
    vir         = "";
    listadepart = "";

    vir                = "";
    listadepartDestino = "";
    oLancadorDestino.getRegistros().each( function( oDados, iIndice){

        listadepartDestino += vir + oDados.sCodigo;
        vir=",";
    });

    vir                = "";
    listadepartOrigem = "";
    oLancadorOrigem.getRegistros().each( function( oDados, iIndice){

        listadepartOrigem += vir + oDados.sCodigo;
        vir=",";
    });

    vir      = "";
    listamat = "";

    for ( x = 0; x < parent.iframe_g2.document.form1.material.length;x++){
        listamat+=vir+parent.iframe_g2.document.form1.material.options[x].value;
        vir=",";
    }

    vir="";
    listausu="";
    for(x=0;x<parent.iframe_g3.document.form1.usuario.length;x++){
        listausu+=vir+parent.iframe_g3.document.form1.usuario.options[x].value;
        vir=",";
    }

    vir        = "";
    listamatestoquetipo = "";
    obj        = parent.iframe_g4.db_iframe_matestoquetipo.document.getElementsByTagName("input");
    nObj       = obj.length;

    for (x=0 ; x < nObj;x++){

        if (obj[x].type == "checkbox" && obj[x].checked==true){
            listamatestoquetipo += vir+obj[x].value;
            vir = ",";
        }

    }

    vir        = "";
    listaorgao = "";
    obj        = parent.iframe_g4.db_iframe_orgao.document.getElementsByTagName("input");
    console.log(obj);
    nObj       = obj.length;

    for (x=0 ; x < nObj;x++){

        if (obj[x].type == "checkbox" && obj[x].checked==true){
            listaorgao += vir+obj[x].value;
            vir = ",";
        }

    }

    var sDataIni = new String(document.form1.data1.value).trim();
    var sDataFim = new String(document.form1.data2.value).trim();


    if ( sDataIni == '' && sDataFim == '' ) {
        alert('Favor informe algum per�odo!');
        return false;
    } else if ( sDataIni == '' ) {
        alert('Favor informe per�odo inicial!');
        return false;
    } else if ( sDataFim == '' ) {
        alert('Favor informe per�odo final!');
        return false;
    }


    var aLinhas = oTreeViewGrupos.getNodesChecked();
    var aContas = new Array();

    aLinhas.each (
        function(oRetornoCheck) {

            aContas.push(oRetornoCheck.value);
        }
    );


    query+='&listadepart=' + listadepartOrigem;
    query+='&verdepart='   + document.form1.ver.value;
    query+='&listamat='    + listamat;
    query+='&vermat='      + parent.iframe_g2.document.form1.ver.value;
    query+='&listausu='    + listausu;
    query+='&verusu='      + parent.iframe_g3.document.form1.ver.value;
    query+='&dataini='     + sDataIni;
    query+='&datafin='     + sDataFim;
    query+='&listadepartDestino='  + listadepartDestino;
    query+='&ordem='               + document.form1.ordem.value;
    query+='&listaorgao='          + listaorgao;
    query+='&listamatestoquetipo=' +listamatestoquetipo;
    query+='&separaDepartamentos=' + document.form1.agrupar_dpto.value;

    query+= '&grupos=' + aContas.implode(',');


    var URL = document.form1.agrupar_dpto.value == 'sim' ? 'separaDepartamentos' : 'padrao';

    jan = window.open( urls[URL] + '?' + query,'',
        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
}

function js_verifica_orgao(){
  orgao=false;
  obj=parent.iframe_g4.db_iframe_orgao.document.getElementsByTagName("input");
  nObj=obj.length;
   for (i=0;i < nObj;i++){
       if (obj[i].type == "checkbox" && obj[i].checked==true){
         orgao=true;
        }
   }
   if (orgao==true){
     alert("J� existe �rg�o selecionado na guia �rg�os.");
     document.getElementById('departamentos').length = 0;
     return false;
   }
}

</script>
