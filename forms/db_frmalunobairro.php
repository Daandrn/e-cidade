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
$clalunobairro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("j13_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted225_i_codigo?>">
       <?=@$Led225_i_codigo?>
    </td>
    <td>
<?
db_input('ed225_i_codigo',10,$Ied225_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted225_i_aluno?>">
       <?
       db_ancora(@$Led225_i_aluno,"js_pesquisaed225_i_aluno(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed225_i_aluno',10,$Ied225_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed225_i_aluno(false);'")
?>
       <?
db_input('ed47_i_codigo',20,$Ied47_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted225_i_bairro?>">
       <?
       db_ancora(@$Led225_i_bairro,"js_pesquisaed225_i_bairro(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed225_i_bairro',10,$Ied225_i_bairro,true,'text',$db_opcao," onchange='js_pesquisaed225_i_bairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed225_i_aluno(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aluno','func_aluno.php?funcao_js=parent.js_mostraaluno1|ed47_i_codigo|ed47_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed225_i_aluno.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aluno','func_aluno.php?pesquisa_chave='+document.form1.ed225_i_aluno.value+'&funcao_js=parent.js_mostraaluno','Pesquisa',false);
     }else{
       document.form1.ed47_i_codigo.value = '';
     }
  }
}
function js_mostraaluno(chave,erro){
  document.form1.ed47_i_codigo.value = chave;
  if(erro==true){
    document.form1.ed225_i_aluno.focus();
    document.form1.ed225_i_aluno.value = '';
  }
}
function js_mostraaluno1(chave1,chave2){
  document.form1.ed225_i_aluno.value = chave1;
  document.form1.ed47_i_codigo.value = chave2;
  db_iframe_aluno.hide();
}
function js_pesquisaed225_i_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.ed225_i_bairro.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.ed225_i_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = '';
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro==true){
    document.form1.ed225_i_bairro.focus();
    document.form1.ed225_i_bairro.value = '';
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.ed225_i_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_alunobairro','func_alunobairro.php?funcao_js=parent.js_preenchepesquisa|ed225_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_alunobairro.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
