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

//MODULO: fiscal
$clcadfiscais->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tid_usuario?>">
       <?
       db_ancora(@$Lid_usuario,"js_pesquisaid_usuario(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('id_usuario',5,$Iid_usuario,true,'text',$db_opcao," onchange='js_pesquisaid_usuario(false);'")
?>
       <?
db_input('nome',45,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao"  type="submit" id="db_opcao"  value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="instit"    type="hidden" id="instit"    value="<?=db_getsession('DB_instit')?>" >
</form>
<script>
function js_pesquisaid_usuario(mostra){
	var instit = document.form1.instit.value;
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios_instit.php?instit='+instit+'&funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios_instit.php?instit='+instit+'&pesquisa_chave='+document.form1.id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.id_usuario.focus();
    document.form1.id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cadfiscais','func_cadfiscais_instit.php?funcao_js=parent.js_preenchepesquisa|id_usuario','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cadfiscais.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>
