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

//MODULO: patrim
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbenscomissaousu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('nome');

if(isset($db_opcaoal)){
   $db_opcao=33;
   $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
   $db_botao=true;
   $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
   $db_opcao = 3;
   $db_botao=true;
}else{
   $db_opcao = 1;
   $db_botao=true;
   if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     $t61_id_usuario = "";
     $nome = "";
   }
}
if(isset($opcao)){
  $result = $clbenscomissaousu->sql_record($clbenscomissaousu->sql_query($t61_codcom,$t61_id_usuario));
  if($result!=false && $clbenscomissaousu->numrows>0){
    db_fieldsmemory($result,0);
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt61_codcom?>">
      <?=@$Lt61_codcom?>
    </td>
    <td>
<?
db_input('t61_codcom',8,$It61_codcom,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt61_id_usuario?>">
       <?
       db_ancora(@$Lt61_id_usuario,"js_pesquisat61_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('t61_id_usuario',8,$It61_id_usuario,true,'text',$db_opcao," onchange='js_pesquisat61_id_usuario(false);'");
?>
<?
db_input('nome',40,$Inome,true,'text',3,"");
?>

    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">
    <?
	 $chavepri= array("t61_codcom"=>@$t61_codcom,"t61_id_usuario"=>@$t61_id_usuario);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->opcoes  = 3;
	 $cliframe_alterar_excluir->sql     = $clbenscomissaousu->sql_query_file($t61_codcom);
	 $cliframe_alterar_excluir->campos  ="t61_codcom,t61_id_usuario";
	 $cliframe_alterar_excluir->legenda="ITENS LAN�ADAS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisat61_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_benscomissaousu','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.t61_id_usuario.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_benscomissaousu','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.t61_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value='';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.t61_id_usuario.focus();
    document.form1.t61_id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.t61_id_usuario.value = chave1;
  document.form1.nome.value=chave2;
  db_iframe_db_usuarios.hide();
}
</script>
