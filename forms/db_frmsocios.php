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

//MODULO: issqn
$clsocios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq95_cgmpri?>">
       <?
       db_ancora(@$Lq95_cgmpri,"js_pesquisaq95_cgmpri(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('q95_cgmpri',6,$Iq95_cgmpri,true,'text',$db_opcao," onchange='js_pesquisaq95_cgmpri(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq95_numcgm?>">
       <?
       db_ancora(@$Lq95_numcgm,"js_pesquisaq95_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('q95_numcgm',6,$Iq95_numcgm,true,'text',$db_opcao," onchange='js_pesquisaq95_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq95_perc?>">
       <?=@$Lq95_perc?>
    </td>
    <td>
<?
db_input('q95_perc',15,$Iq95_perc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq95_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.q95_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q95_numcgm.focus();
    document.form1.q95_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.q95_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaq95_cgmpri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.q95_cgmpri.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q95_cgmpri.focus();
    document.form1.q95_cgmpri.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.q95_cgmpri.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_socios','func_socios.php?funcao_js=parent.js_preenchepesquisa|q95_numcgm|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_socios.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
+"&chavepesquisa1="+chave1}
</script>
