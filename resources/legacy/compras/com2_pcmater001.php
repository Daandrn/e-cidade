<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label('o56_elemento');
$clrotulo->label('o56_descr');

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="left" nowrap title="Ordem Alfabética/Numérica" >
        <strong>Ordem :&nbsp;&nbsp;</strong>
        </td>
        <td>
	  <?
	    $tipo_ordem = array("a"=>"Alfabética","b"=>"Numérica");
	    db_select("ordem",$tipo_ordem,true,2);
	  ?>
        </td>
      </tr>
      <tr>
        <td><strong>Grupo :&nbsp;&nbsp;</strong></td>
        <td>
	  <?
	    $tipo_grupo = array("geral"=>"Geral","sub_grupo"=>"Sub-grupo","elemento"=>"Elemento");
	    db_select("grupo",$tipo_grupo,true,2,"OnChange='js_grupo();'");
	  ?>
	</td>
      </tr>
      <tr>
        <td id="td1" style="visibility:hidden" nowrap align="right" title="<?=@$o56_descr?>"><?db_ancora(@$Lo56_elemento,"js_elemento(true);",1);?></td>
        <td id="td2" style="visibility:hidden" nowrap>
        <?
           db_input('o56_elemento',13,$Io56_elemento,true,'text',1," onchange='js_elemento(false);'");
           db_input('o56_descr',60,$Io56_descr,true,'text',3,'');
        ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_selecionarFormatoRelatorio();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_elemento(mostra){
  if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_elemento','func_pcmaterele.php?funcao_js=parent.js_mostraelemento1|o56_elemento|o56_descr','Pesquisa',true);
  }else{
    if (document.form1.o56_elemento.value != "") {
	   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_elemento','func_pcmaterele.php?pesquisa_chave=xx&chave_o56_elem='+document.form1.o56_elemento.value+'&funcao_js=parent.js_mostraelemento','Pesquisa',false);
    }
  }
}
function js_mostraelemento(chave,erro){
   document.form1.o56_descr.value = chave;

   if(erro==true){
       document.form1.o56_elemento.focus();
       document.form1.o56_elemento.value = "";
   }
}
function js_mostraelemento1(chave1,chave2){
   document.form1.o56_elemento.value = chave1;
   document.form1.o56_descr.value    = chave2;
   db_iframe_elemento.hide();
}
function js_grupo(){
  var td1 = document.getElementById("td1");
  var td2 = document.getElementById("td2");
  if (document.form1.grupo.value == "elemento"){
       td1.style.visibility = "visible";
       td2.style.visibility = "visible";
       document.form1.o56_elemento.value = "";
       document.form1.o56_descr.value    = "";
  } else {
       td1.style.visibility = "hidden";
       td2.style.visibility = "hidden";
       document.form1.o56_elemento.value = "";
       document.form1.o56_descr.value    = "";
  }
}
function js_pesquisatabdesc(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave='+document.form1.codsubrec.value+'&funcao_js=parent.js_mostratabdesc';
     }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave;
  if(erro==true){
     document.form1.codsubrec.focus();
     document.form1.codsubrec.value = '';
  }
}
function js_mostratabdesc1(chave1,chave2){
     document.form1.codsubrec.value = chave1;
     document.form1.k07_descr.value = chave2;
     db_iframe.hide();
}

function js_selecionarFormatoRelatorio() {

  var iHeight = 200;
  var iWidth = 300;
  windowFormatoRelatorio = new windowAux('windowFormatoRelatorio',
    'Gerar Relatório ',
    iWidth,
    iHeight
  );

  var sContent = "<div style='margin-top:30px;'>";
  sContent += "<fieldset>";
  sContent += "<legend>Selecione o formato do relatório:</legend>";
  sContent += "  <div >";
  sContent += "  <input checked type='radio' id='pdf' name='formato'>";
  sContent += "  <label>PDF</label>";
  sContent += "  </div>";
  sContent += "  <div>";
  sContent += "  <input type='radio' id='excel' name='formato'>";
  sContent += "  <label>EXCEL</label>";
  sContent += "  </div>";
  sContent += "</fieldset>";
  sContent += "<center>";
  sContent += "<input type='button' id='btnGerar' value='Confirmar' onclick='js_gerarRelatorio()'>";
  sContent += "</center>";
  sContent += "</div>";
  windowFormatoRelatorio.setContent(sContent);
  windowFormatoRelatorio.show();

  document.getElementById('windowwindowFormatoRelatorio_btnclose').onclick = destroyWindow;

}

function destroyWindow() {
  windowFormatoRelatorio.destroy();
}

function js_gerarRelatorio() {

  if (document.getElementById('pdf').checked == true) {
    jan = window.open('com2_pcmater002.php?ordem='+document.form1.ordem.value+'&grupo='+document.form1.grupo.value+'&elemento='+document.form1.o56_elemento.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0, 0);
    return;
  }

  jan = window.open('com2_pcmater002excel.php?ordem='+document.form1.ordem.value+'&grupo='+document.form1.grupo.value+'&elemento='+document.form1.o56_elemento.value, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
  jan.moveTo(0, 0);

}

</script>


<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>
