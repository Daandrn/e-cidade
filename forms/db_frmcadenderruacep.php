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

//MODULO: Configuracoes
$clcadenderruacep->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db74_descricao");
?>
<form name="form1" method="post" action="">
<center>

<table align=center style="margin-top: 15px;">
<tr><td>

<fieldset>
<legend><b>CEP</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb86_sequencial?>">
       <?=@$Ldb86_sequencial?>
    </td>
    <td>
<?
db_input('db86_sequencial',10,$Idb86_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb86_cadenderrua?>">
       <?
       db_ancora(@$Ldb86_cadenderrua,"js_pesquisadb86_cadenderrua(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('db86_cadenderrua',10,$Idb86_cadenderrua,true,'text',$db_opcao," onchange='js_pesquisadb86_cadenderrua(false);'")
?>
       <?
db_input('db74_descricao',40,$Idb74_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb86_cep?>">
       <?=@$Ldb86_cep?>
    </td>
    <td>
<?
db_input('db86_cep',10,$Idb86_cep,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>

</fieldset>

</td></tr>
</table>

  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb86_cadenderrua(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadenderrua','func_cadenderrua.php?funcao_js=parent.js_mostracadenderrua1|db74_sequencial|db74_descricao','Pesquisa',true);
  }else{
     if(document.form1.db86_cadenderrua.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadenderrua','func_cadenderrua.php?pesquisa_chave='+document.form1.db86_cadenderrua.value+'&funcao_js=parent.js_mostracadenderrua','Pesquisa',false);
     }else{
       document.form1.db74_descricao.value = '';
     }
  }
}
function js_mostracadenderrua(chave,erro){
  document.form1.db74_descricao.value = chave;
  if(erro==true){
    document.form1.db86_cadenderrua.focus();
    document.form1.db86_cadenderrua.value = '';
  }
}
function js_mostracadenderrua1(chave1,chave2){
  document.form1.db86_cadenderrua.value = chave1;
  document.form1.db74_descricao.value = chave2;
  db_iframe_cadenderrua.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadenderruacep','func_cadenderruacep.php?funcao_js=parent.js_preenchepesquisa|db86_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadenderruacep.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
