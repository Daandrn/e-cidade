<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_classesgenericas.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");
$clrotulo->label("l20_numero");
$clrotulo->label("l03_codigo");
$clrotulo->label("l03_descr");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){

	query = 'l20_codigo='+document.form1.l20_codigo.value+'&l20_numero='+document.form1.l20_numero.value;
	query += '&l03_codigo='+document.form1.l03_codigo.value+'&l03_descr='+document.form1.l03_descr.value;
	query += '&data='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
	query += '&data1='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value;
  document.form1.l20_codigo.value='';
	jan = window.open('lic2_venclicitacao002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>
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
      <tr>
         <td  align="right" nowrap title="<?=$Tl03_codigo?>">
          <b>
          <?
            db_ancora("Modalidade :","js_pesquisal03_codigo(true);",1);
          ?>
          </b>
         </td>
         <td  align="left" nowrap>
          <?
            db_input("l03_codigo",8,$Il03_codigo,true,"text",1,"onchange='js_pesquisal03_codigo(false);'");
            db_input("l03_descr",40,$Il03_descr,true,"text",3);
          ?>
         </td>
      </tr>
      <tr>
         <td  align="right" nowrap title="<?=$Tl20_numero?>">
	 <b>
	 <?
	    db_ancora($Ll20_numero,"js_pesquisal20_numero(true);",1);
	 ?>
	 </b>
	 </td>
         <td  align="left" nowrap>
          <?
            db_input("l20_numero",8,$Il20_numero,true,"text",4);
          ?>
         </td>
      </tr>
      <tr>
         <td  align="right" nowrap title="<?=$Tl20_codigo?>">
          <b>
          <?
            db_ancora('Licita��o :',"js_pesquisa_liclicita(true);",1);
          ?>
          </b>
         </td>
         <td align="left" nowrap>
          <?
            db_input("l20_codigo",8,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
          ?>
         </td>
      </tr>
      <tr>
          <td nowrap align="right"><b>Per�odo de:</b></td>
          <td  align="left" nowrap>
           <?
       	     db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
             echo " <b>ate:</b> ";
             db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
           ?>
          </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
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
function js_pesquisal20_numero(mostra){
  if(mostra==true){
    if (document.form1.l03_codigo.value != ""){
         js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_licnumeracao','func_liclicita.php?chave_l03_codigo='+document.form1.l03_codigo.value+'&funcao_js=parent.js_mostralicnumeracao1|l20_numero','Pesquisa',true);
    } else {
         alert("Selecione uma modalidade!");
	 document.form1.l03_codigo.focus();
	 document.form1.l03_codigo.select();
    }
  }
}
function js_mostralicnumeracao1(chave1){
   document.form1.l20_numero.value = chave1;
   db_iframe_licnumeracao.hide();
}
function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicita.php?lContratos=1&situacao=10&funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicita.php?lContratos=1&situacao=10&pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = '';
     }
  }
}
function js_mostraliclicita(chave,erro){
  document.form1.l20_codigo.value = chave;
  if(erro==true){
    document.form1.l20_codigo.value = '';
    document.form1.l20_codigo.focus();
  }
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;
   db_iframe_liclicita.hide();
}
function js_pesquisal03_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cflicita','func_cflicita.php?funcao_js=parent.js_mostracflicita1|l03_codigo|l03_descr','Pesquisa',true);
  }else{
     if(document.form1.l03_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cflicita','func_cflicita.php?pesquisa_chave='+document.form1.l03_codigo.value+'&funcao_js=parent.js_mostracflicita','Pesquisa',false);
     }else{
       document.form1.l03_descr.value = '';
     }
  }
}
function js_mostracflicita(chave,erro){
  document.form1.l03_descr.value = chave;
  if(erro==true){
    document.form1.l03_codigo.focus();
    document.form1.l03_codigo.value = '';
  }
}
function js_mostracflicita1(chave1,chave2){
  document.form1.l03_codigo.value = chave1;
  document.form1.l03_descr.value = chave2;
  db_iframe_cflicita.hide();
}
</script>
