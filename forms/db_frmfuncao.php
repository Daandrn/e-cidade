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

//MODULO: pessoal
$clfuncao->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr37_anousu?>">
       <?=@$Lr37_anousu?>
    </td>
    <td>
<?
$r37_anousu = db_getsession('DB_anousu');
db_input('r37_anousu',4,$Ir37_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_mesusu?>">
       <?=@$Lr37_mesusu?>
    </td>
    <td>
<?
db_input('r37_mesusu',2,$Ir37_mesusu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_funcao?>">
       <?=@$Lr37_funcao?>
    </td>
    <td>
<?
db_input('r37_funcao',5,$Ir37_funcao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_descr?>">
       <?=@$Lr37_descr?>
    </td>
    <td>
<?
db_input('r37_descr',30,$Ir37_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_vagas?>">
       <?=@$Lr37_vagas?>
    </td>
    <td>
<?
db_input('r37_vagas',4,$Ir37_vagas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_cbo?>">
       <?=@$Lr37_cbo?>
    </td>
    <td>
<?
db_input('r37_cbo',5,$Ir37_cbo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_lei?>">
       <?=@$Lr37_lei?>
    </td>
    <td>
<?
db_input('r37_lei',6,$Ir37_lei,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr37_class?>">
       <?=@$Lr37_class?>
    </td>
    <td>
<?
db_input('r37_class',5,$Ir37_class,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_funcao','func_funcao.php?funcao_js=parent.js_preenchepesquisa|r37_anousu|r37_mesusu|r37_funcao','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_funcao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>
