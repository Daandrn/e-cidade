xxxxxxx<?
//MODULO: educa��o
$clcurso->rotulo->label();
$clrotulo = new rotulocampo;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted29_i_codigo?>">
   <?=@$Led29_i_codigo?>
  </td>
  <td>
   <?db_input('ed29_i_codigo',10,$Ied29_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted29_c_descr?>">
   <?=@$Led29_c_descr?>
  </td>
  <td>
   <?db_input('ed29_c_descr',40,$Ied29_c_descr,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted29_i_ensino?>">
   <?db_ancora(@$Led29_i_ensino,"js_pesquisaed29_i_ensino(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed29_i_ensino',10,$Ied29_i_ensino,true,'text',$db_opcao," onchange='js_pesquisaed29_i_ensino(false);'")?>
   <?db_input('ed10_c_descr',30,@$Ied10_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted29_c_historico?>">
   <?=@$Led29_c_historico?>
  </td>
  <td>
   <?
   $x = array('S'=>'SIM','N'=>'N�O');
   db_select('ed29_c_historico',$x,true,$db_opcao,"");
   ?>
  </td>
 </tr>
</table>
</center>
<input type="hidden" name="ed71_i_codigo" value="<?=@$ed71_i_codigo?>"
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=$db_opcao==1?"disabled":""?>>
</form>
<script>
function js_pesquisaed29_i_ensino(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?funcao_js=parent.js_mostraensino1|ed10_i_codigo|ed10_c_descr','Pesquisa de Ensinos',true);
 }else{
  if(document.form1.ed29_i_ensino.value != ''){
   js_OpenJanelaIframe('','db_iframe_ensino','func_ensino.php?pesquisa_chave='+document.form1.ed29_i_ensino.value+'&funcao_js=parent.js_mostraensino','Pesquisa Ensinos',false);
  }else{
   document.form1.ed10_c_descr.value = '';
  }
 }
}
function js_mostraensino(chave,erro){
 document.form1.ed10_c_descr.value = chave;
 if(erro==true){
  document.form1.ed29_i_ensino.focus();
  document.form1.ed29_i_ensino.value = '';
 }
}
function js_mostraensino1(chave1,chave2){
 document.form1.ed29_i_ensino.value = chave1;
 document.form1.ed10_c_descr.value = chave2;
 db_iframe_ensino.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_curso','func_cursoedu.php?funcao_js=parent.js_preenchepesquisa|ed29_i_codigo','Pesquisa de Cursos',true);
}
function js_preenchepesquisa(chave){
 db_iframe_curso.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}
</script>
