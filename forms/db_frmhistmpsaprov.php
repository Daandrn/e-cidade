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
$clhistmpsaprov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed62_i_codigo");
$clrotulo->label("ed12_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted28_i_codigo?>">
       <?=@$Led28_i_codigo?>
    </td>
    <td>
<?
db_input('ed28_i_codigo',10,$Ied28_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted28_i_historicomps?>">
       <?
       db_ancora(@$Led28_i_historicomps,"js_pesquisaed28_i_historicomps(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed28_i_historicomps',10,$Ied28_i_historicomps,true,'text',$db_opcao," onchange='js_pesquisaed28_i_historicomps(false);'")
?>
       <?
db_input('ed62_i_codigo',10,$Ied62_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted28_i_disciplina?>">
       <?
       db_ancora(@$Led28_i_disciplina,"js_pesquisaed28_i_disciplina(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed28_i_disciplina',10,$Ied28_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed28_i_disciplina(false);'")
?>
       <?
db_input('ed12_i_codigo',10,$Ied12_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed28_i_historicomps(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_historicomps','func_historicomps.php?funcao_js=parent.js_mostrahistoricomps1|ed62_i_codigo|ed62_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed28_i_historicomps.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_historicomps','func_historicomps.php?pesquisa_chave='+document.form1.ed28_i_historicomps.value+'&funcao_js=parent.js_mostrahistoricomps','Pesquisa',false);
     }else{
       document.form1.ed62_i_codigo.value = '';
     }
  }
}
function js_mostrahistoricomps(chave,erro){
  document.form1.ed62_i_codigo.value = chave;
  if(erro==true){
    document.form1.ed28_i_historicomps.focus();
    document.form1.ed28_i_historicomps.value = '';
  }
}
function js_mostrahistoricomps1(chave1,chave2){
  document.form1.ed28_i_historicomps.value = chave1;
  document.form1.ed62_i_codigo.value = chave2;
  db_iframe_historicomps.hide();
}
function js_pesquisaed28_i_disciplina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_disciplina','func_disciplina.php?funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed12_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed28_i_disciplina.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_disciplina','func_disciplina.php?pesquisa_chave='+document.form1.ed28_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
     }else{
       document.form1.ed12_i_codigo.value = '';
     }
  }
}
function js_mostradisciplina(chave,erro){
  document.form1.ed12_i_codigo.value = chave;
  if(erro==true){
    document.form1.ed28_i_disciplina.focus();
    document.form1.ed28_i_disciplina.value = '';
  }
}
function js_mostradisciplina1(chave1,chave2){
  document.form1.ed28_i_disciplina.value = chave1;
  document.form1.ed12_i_codigo.value = chave2;
  db_iframe_disciplina.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_histmpsaprov','func_histmpsaprov.php?funcao_js=parent.js_preenchepesquisa|ed28_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_histmpsaprov.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
