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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");

db_postmemory($HTTP_POST_VARS);

$action   = "lic1_liclicitemcancanu002.php";

$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_enviar(){
  if(document.form1.l20_codigo.value == ""){
      document.form1.l20_codigo.focus();
      alert("Informe o c�digo da Licita��o");
  } else {
      document.form1.submit();
  }
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.l20_codigo.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="<?=($action)?>">
<table border='0'>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td  align="left" nowrap title="<?=$Tl20_codigo?>">
    <b>
    <?db_ancora('Licita��o',"js_pesquisa_liclicita(true);",1);?>&nbsp;:
    </b>
    </td>
    <td align="left" nowrap>
      <?
        db_input("l20_codigo",6,$Il20_codigo,true,"text",4,"onchange='js_pesquisa_liclicita(false);'");
      ?>
    </td>
  </tr>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="enviar" type="button" onclick="js_enviar();" value="Enviar dados" <?=($db_botao == true?"disabled":"")?>>
    </td>
  </tr>
</table>
</form>
</center>
<?
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicitemcancanu.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_liclicita','func_liclicitemcancanu.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = '';
     }
  }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){
      document.form1.enviar.disabled  = true;
      alert("Licitacao ja julgada ou com autorizacao ativa ou sem itens anulados.");
      document.form1.l20_codigo.value = '';
      document.form1.l20_codigo.focus();
  } else {
      document.form1.enviar.disabled  = false;
      document.form1.l20_codigo.value = chave;
  }
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;
   db_iframe_liclicita.hide();
   document.form1.enviar.disabled  = false;
   js_enviar();
}

js_pesquisa_liclicita(true);
</script>
</body>
</html>
