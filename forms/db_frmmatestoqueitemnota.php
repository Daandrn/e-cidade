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

//MODULO: material
$clmatestoqueitemnota->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m71_codmatestoque");
$clrotulo->label("e69_codnota");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm74_codmatestoqueitem?>">
       <?
       db_ancora(@$Lm74_codmatestoqueitem,"js_pesquisam74_codmatestoqueitem(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('m74_codmatestoqueitem',10,$Im74_codmatestoqueitem,true,'text',$db_opcao," onchange='js_pesquisam74_codmatestoqueitem(false);'")
?>
       <?
db_input('m71_codmatestoque',10,$Im71_codmatestoque,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm74_codempnota?>">
       <?
       db_ancora(@$Lm74_codempnota,"js_pesquisam74_codempnota(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('m74_codempnota',6,$Im74_codempnota,true,'text',$db_opcao," onchange='js_pesquisam74_codempnota(false);'")
?>
       <?
db_input('e69_codnota',6,$Ie69_codnota,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam74_codmatestoqueitem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?funcao_js=parent.js_mostramatestoqueitem1|m71_codlanc|m71_codmatestoque','Pesquisa',true);
  }else{
     if(document.form1.m74_codmatestoqueitem.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matestoqueitem','func_matestoqueitem.php?pesquisa_chave='+document.form1.m74_codmatestoqueitem.value+'&funcao_js=parent.js_mostramatestoqueitem','Pesquisa',false);
     }else{
       document.form1.m71_codmatestoque.value = '';
     }
  }
}
function js_mostramatestoqueitem(chave,erro){
  document.form1.m71_codmatestoque.value = chave;
  if(erro==true){
    document.form1.m74_codmatestoqueitem.focus();
    document.form1.m74_codmatestoqueitem.value = '';
  }
}
function js_mostramatestoqueitem1(chave1,chave2){
  document.form1.m74_codmatestoqueitem.value = chave1;
  document.form1.m71_codmatestoque.value = chave2;
  db_iframe_matestoqueitem.hide();
}
function js_pesquisam74_codempnota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empnota','func_empnota.php?funcao_js=parent.js_mostraempnota1|e69_codnota|e69_codnota','Pesquisa',true);
  }else{
     if(document.form1.m74_codempnota.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empnota','func_empnota.php?pesquisa_chave='+document.form1.m74_codempnota.value+'&funcao_js=parent.js_mostraempnota','Pesquisa',false);
     }else{
       document.form1.e69_codnota.value = '';
     }
  }
}
function js_mostraempnota(chave,erro){
  document.form1.e69_codnota.value = chave;
  if(erro==true){
    document.form1.m74_codempnota.focus();
    document.form1.m74_codempnota.value = '';
  }
}
function js_mostraempnota1(chave1,chave2){
  document.form1.m74_codempnota.value = chave1;
  document.form1.e69_codnota.value = chave2;
  db_iframe_empnota.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matestoqueitemnota','func_matestoqueitemnota.php?funcao_js=parent.js_preenchepesquisa|m74_codmatestoqueitem|m74_codempnota','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_matestoqueitemnota.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>
