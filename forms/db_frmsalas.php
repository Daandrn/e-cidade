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
$clsalas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed09_c_situacao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted08_i_codigo?>">
       <?=@$Led08_i_codigo?>
    </td>
    <td>
<?
db_input('ed08_i_codigo',5,$Ied08_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted08_i_matricula?>">
       <?
       db_ancora(@$Led08_i_matricula,"js_pesquisaed08_i_matricula(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed08_i_matricula',5,$Ied08_i_matricula,true,'text',$db_opcao," onchange='js_pesquisaed08_i_matricula(false);'")
?>
       <?
db_input('ed09_c_situacao',20,$Ied09_c_situacao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted08_f_comportamento?>">
       <?=@$Led08_f_comportamento?>
    </td>
    <td>
<?
db_input('ed08_f_comportamento',3,$Ied08_f_comportamento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted08_f_nota?>">
       <?=@$Led08_f_nota?>
    </td>
    <td>
<?
db_input('ed08_f_nota',3,$Ied08_f_nota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted08_f_frequencia?>">
       <?=@$Led08_f_frequencia?>
    </td>
    <td>
<?
db_input('ed08_f_frequencia',3,$Ied08_f_frequencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed08_i_matricula(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matriculas','func_matriculas.php?funcao_js=parent.js_mostramatriculas1|ed09_i_codigo|ed09_c_situacao','Pesquisa',true);
  }else{
     if(document.form1.ed08_i_matricula.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matriculas','func_matriculas.php?pesquisa_chave='+document.form1.ed08_i_matricula.value+'&funcao_js=parent.js_mostramatriculas','Pesquisa',false);
     }else{
       document.form1.ed09_c_situacao.value = '';
     }
  }
}
function js_mostramatriculas(chave,erro){
  document.form1.ed09_c_situacao.value = chave;
  if(erro==true){
    document.form1.ed08_i_matricula.focus();
    document.form1.ed08_i_matricula.value = '';
  }
}
function js_mostramatriculas1(chave1,chave2){
  document.form1.ed08_i_matricula.value = chave1;
  document.form1.ed09_c_situacao.value = chave2;
  db_iframe_matriculas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_salas','func_salas.php?funcao_js=parent.js_preenchepesquisa|ed08_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_salas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
