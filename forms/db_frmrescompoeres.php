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

//MODULO: educa��o
$clrescompoeres->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed43_i_codigo");
$clrotulo->label("ed43_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted68_i_codigo?>">
       <?=@$Led68_i_codigo?>
    </td>
    <td>
<?
db_input('ed68_i_codigo',10,$Ied68_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted68_i_procresultado?>">
       <?
       db_ancora(@$Led68_i_procresultado,"js_pesquisaed68_i_procresultado(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed68_i_procresultado',10,$Ied68_i_procresultado,true,'text',$db_opcao," onchange='js_pesquisaed68_i_procresultado(false);'")
?>
       <?
db_input('ed43_i_codigo',10,$Ied43_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted68_i_procresultcomp?>">
       <?
       db_ancora(@$Led68_i_procresultcomp,"js_pesquisaed68_i_procresultcomp(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed68_i_procresultcomp',10,$Ied68_i_procresultcomp,true,'text',$db_opcao," onchange='js_pesquisaed68_i_procresultcomp(false);'")
?>
       <?
db_input('ed43_i_codigo',10,$Ied43_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted68_i_peso?>">
       <?=@$Led68_i_peso?>
    </td>
    <td>
<?
db_input('ed68_i_peso',10,$Ied68_i_peso,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted68_c_minimoaprov?>">
       <?=@$Led68_c_minimoaprov?>
    </td>
    <td>
<?
db_input('ed68_c_minimoaprov',10,$Ied68_c_minimoaprov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed68_i_procresultado(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procresultado','func_procresultado.php?funcao_js=parent.js_mostraprocresultado1|ed43_i_codigo|ed43_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed68_i_procresultado.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procresultado','func_procresultado.php?pesquisa_chave='+document.form1.ed68_i_procresultado.value+'&funcao_js=parent.js_mostraprocresultado','Pesquisa',false);
     }else{
       document.form1.ed43_i_codigo.value = '';
     }
  }
}
function js_mostraprocresultado(chave,erro){
  document.form1.ed43_i_codigo.value = chave;
  if(erro==true){
    document.form1.ed68_i_procresultado.focus();
    document.form1.ed68_i_procresultado.value = '';
  }
}
function js_mostraprocresultado1(chave1,chave2){
  document.form1.ed68_i_procresultado.value = chave1;
  document.form1.ed43_i_codigo.value = chave2;
  db_iframe_procresultado.hide();
}
function js_pesquisaed68_i_procresultcomp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procresultado','func_procresultado.php?funcao_js=parent.js_mostraprocresultado1|ed43_i_codigo|ed43_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed68_i_procresultcomp.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_procresultado','func_procresultado.php?pesquisa_chave='+document.form1.ed68_i_procresultcomp.value+'&funcao_js=parent.js_mostraprocresultado','Pesquisa',false);
     }else{
       document.form1.ed43_i_codigo.value = '';
     }
  }
}
function js_mostraprocresultado(chave,erro){
  document.form1.ed43_i_codigo.value = chave;
  if(erro==true){
    document.form1.ed68_i_procresultcomp.focus();
    document.form1.ed68_i_procresultcomp.value = '';
  }
}
function js_mostraprocresultado1(chave1,chave2){
  document.form1.ed68_i_procresultcomp.value = chave1;
  document.form1.ed43_i_codigo.value = chave2;
  db_iframe_procresultado.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rescompoeres','func_rescompoeres.php?funcao_js=parent.js_preenchepesquisa|ed68_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rescompoeres.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
