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

//MODULO: recursoshumanos
$clrhestagioquesito->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h50_sequencial");
?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Quesito do Est�gio</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th51_sequencial?>">
       <?=@$Lh51_sequencial?>
    </td>
    <td>
<?
db_input('h51_sequencial',10,$Ih51_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th51_rhestagio?>">
       <?
       db_ancora(@$Lh51_rhestagio,"js_pesquisah51_rhestagio(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('h51_rhestagio',10,$Ih51_rhestagio,true,'text',$db_opcao," onchange='js_pesquisah51_rhestagio(false);'")
?>
       <?
db_input('h50_sequencial',10,$Ih50_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th51_descr?>">
       <?=@$Lh51_descr?>
    </td>
    <td>
<?
db_input('h51_descr',40,$Ih51_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table></fieldset>
  </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah51_rhestagio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhestagio','func_rhestagio.php?funcao_js=parent.js_mostrarhestagio1|h50_sequencial|h50_sequencial','Pesquisa',true);
  }else{
     if(document.form1.h51_rhestagio.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhestagio','func_rhestagio.php?pesquisa_chave='+document.form1.h51_rhestagio.value+'&funcao_js=parent.js_mostrarhestagio','Pesquisa',false);
     }else{
       document.form1.h50_sequencial.value = '';
     }
  }
}
function js_mostrarhestagio(chave,erro){
  document.form1.h50_sequencial.value = chave;
  if(erro==true){
    document.form1.h51_rhestagio.focus();
    document.form1.h51_rhestagio.value = '';
  }
}
function js_mostrarhestagio1(chave1,chave2){
  document.form1.h51_rhestagio.value = chave1;
  document.form1.h50_sequencial.value = chave2;
  db_iframe_rhestagio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhestagioquesito','func_rhestagioquesito.php?funcao_js=parent.js_preenchepesquisa|h51_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagioquesito.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
