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

//MODULO: saude
$clprontsaida->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd24_c_atendimento");
$clrotulo->label("descrdepto");
$clrotulo->label("m70_codmatmater");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd27_i_codigo?>">
       <?=@$Lsd27_i_codigo?>
    </td>
    <td>
<?
db_input('sd27_i_codigo',10,$Isd27_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_prontuario?>">
       <?
       db_ancora(@$Lsd27_i_prontuario,"js_pesquisasd27_i_prontuario(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd27_i_prontuario',10,$Isd27_i_prontuario,true,'text',$db_opcao," onchange='js_pesquisasd27_i_prontuario(false);'")
?>
       <?
db_input('sd24_c_atendimento',11,$Isd24_c_atendimento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_departamento?>">
       <?
       db_ancora(@$Lsd27_i_departamento,"js_pesquisasd27_i_departamento(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd27_i_departamento',10,$Isd27_i_departamento,true,'text',$db_opcao," onchange='js_pesquisasd27_i_departamento(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_material?>">
       <?
       db_ancora(@$Lsd27_i_material,"js_pesquisasd27_i_material(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd27_i_material',10,$Isd27_i_material,true,'text',$db_opcao," onchange='js_pesquisasd27_i_material(false);'")
?>
       <?
db_input('m70_codmatmater',10,$Im70_codmatmater,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_quantidade?>">
       <?=@$Lsd27_i_quantidade?>
    </td>
    <td>
<?
db_input('sd27_i_quantidade',10,$Isd27_i_quantidade,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_usuario?>">
       <?
       db_ancora(@$Lsd27_i_usuario,"js_pesquisasd27_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('sd27_i_usuario',10,$Isd27_i_usuario,true,'text',$db_opcao," onchange='js_pesquisasd27_i_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_d_data?>">
       <?=@$Lsd27_d_data?>
    </td>
    <td>
<?
db_inputdata('sd27_d_data',@$sd27_d_data_dia,@$sd27_d_data_mes,@$sd27_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd27_i_prontuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_prontuarios','func_prontuarios.php?funcao_js=parent.js_mostraprontuarios1|sd24_i_id|sd24_c_atendimento','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_prontuario.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_prontuarios','func_prontuarios.php?pesquisa_chave='+document.form1.sd27_i_prontuario.value+'&funcao_js=parent.js_mostraprontuarios','Pesquisa',false);
     }else{
       document.form1.sd24_c_atendimento.value = '';
     }
  }
}
function js_mostraprontuarios(chave,erro){
  document.form1.sd24_c_atendimento.value = chave;
  if(erro==true){
    document.form1.sd27_i_prontuario.focus();
    document.form1.sd27_i_prontuario.value = '';
  }
}
function js_mostraprontuarios1(chave1,chave2){
  document.form1.sd27_i_prontuario.value = chave1;
  document.form1.sd24_c_atendimento.value = chave2;
  db_iframe_prontuarios.hide();
}
function js_pesquisasd27_i_departamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_departamento.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.sd27_i_departamento.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd27_i_departamento.focus();
    document.form1.sd27_i_departamento.value = '';
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.sd27_i_departamento.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisasd27_i_material(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matestoque','func_matestoque.php?funcao_js=parent.js_mostramatestoque1|m70_codigo|m70_codmatmater','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_material.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matestoque','func_matestoque.php?pesquisa_chave='+document.form1.sd27_i_material.value+'&funcao_js=parent.js_mostramatestoque','Pesquisa',false);
     }else{
       document.form1.m70_codmatmater.value = '';
     }
  }
}
function js_mostramatestoque(chave,erro){
  document.form1.m70_codmatmater.value = chave;
  if(erro==true){
    document.form1.sd27_i_material.focus();
    document.form1.sd27_i_material.value = '';
  }
}
function js_mostramatestoque1(chave1,chave2){
  document.form1.sd27_i_material.value = chave1;
  document.form1.m70_codmatmater.value = chave2;
  db_iframe_matestoque.hide();
}
function js_pesquisasd27_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_usuario.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.sd27_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.sd27_i_usuario.focus();
    document.form1.sd27_i_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.sd27_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_prontsaida','func_prontsaida.php?funcao_js=parent.js_preenchepesquisa|sd27_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_prontsaida.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
