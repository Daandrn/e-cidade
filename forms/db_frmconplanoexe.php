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

//MODULO: contabilidade
$clconplanoexe->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o15_descr");
$clrotulo->label("c60_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc62_anousu?>"><?=@$Lc62_anousu?> </td>
    <td><?
            $c62_anousu = db_getsession('DB_anousu');
            db_input('c62_anousu',4,$Ic62_anousu,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc62_reduz?>"><?=@$Lc62_reduz?></td>
    <td><? db_input('c62_reduz',6,$Ic62_reduz,true,'text',3,"");
           db_input('c60_descr',30,"",true,'text',3,"");
         ?>
    </td>
  </tr>



  <tr>
    <td nowrap title="<?=@$Tc62_codrec?>"><? db_ancora(@$Lc62_codrec,"js_pesquisac62_codrec(true);",$db_opcao); ?>
    </td>
    <td><? db_input('c62_codrec',4,$Ic62_codrec,true,'text',$db_opcao," onchange='js_pesquisac62_codrec(false);'") ?>
        <? db_input('o15_descr',45,$Io15_descr,true,'text',3,'')   ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc62_vlrcre ?>"><?=@$Lc62_vlrcre ?></td>
    <td><? db_input('c62_vlrcre',10,$Ic62_vlrcre,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc62_vlrdeb ?>"><?=@$Lc62_vlrdeb ?></td>
    <td><? db_input('c62_vlrdeb',10,$Ic62_vlrdeb,true,'text',$db_opcao,"") ?>
    </td>
  </tr>

 </table>
  </center>
  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_contas(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conplanoreduz','func_conplanoreduz002.php?funcao_js=parent.js_mostraconta|c61_reduz|c60_descr','Pesquisa',true);
}
function js_mostraconta(chave1,chave2){
    document.form1.c62_reduz.value = chave1;
    document.form1.c60_descr.value = chave2;
    db_iframe_conplanoreduz.hide();
}

function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
   }else{
       if(document.form1.c62_codrec.value != ''){
           js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.c62_codrec.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
       }else{
           document.form1.o15_descr.value = '';
       }
   }
}
function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave;
   if(erro==true){
      document.form1.c62_codrec.focus();
      document.form1.c62_codrec.value = '';
   }
}
function js_mostraorctiporec1(chave1,chave2){
    document.form1.c62_codrec.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conplanoexe','func_conplanogeral.php?funcao_js=parent.js_preenchepesquisa|c61_reduz','Pesquisa',true);
 //  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_conplanoexe','func_conplanoexe.php?funcao_js=parent.js_preenchepesquisa|c61_reduz','Pesquisa',true);

}
function js_preenchepesquisa(chave){
    db_iframe_conplanoexe.hide();
    <?
    if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
}
</script>
