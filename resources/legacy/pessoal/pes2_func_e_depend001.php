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
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('r13_codigo');
$clrotulo->label('r13_descr');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Verifique !.');
    return false;
  }
  return true;
}


function js_emite(){
  qry="";
  qry +="&rh01_regist="+document.form1.rh01_regist.value;
  qry +="&r13_codigo="+document.form1.r70_codigo.value;
  qry +="&r13_descr="+document.form1.r70_descr.value;
  jan = window.open('pes2_func_e_depend002.php?&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right"   nowrap title="Digite o Ano / Mes de compet�ncia" >
        <strong>Ano / M�s :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Trh01_regist?>">
      <?
      db_ancora(@ $Lrh01_regist, "js_pesquisarh01_regist(true);", 1);
      ?>
    </td>
    <td>
      <?
      db_input('rh01_regist', 8, $Irh01_regist, true, 'text', 1, " onchange='js_pesquisarh01_regist(false);'")
      ?>
      <?
      db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tr13_codigo?>">
    <b>
      <?
      db_ancora("Lota��o:", "js_pesquisar13_codigo(true);", 1);
      ?>
      </b>
    </td>
    <td>
      <?
      db_input('r70_codigo', 8, $Ir13_codigo, true, 'text', 1, " onchange='js_pesquisar13_codigo(false);'")
      ?>
      <?
      db_input('r70_descr', 60, $Ir13_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
     if(document.form1.rh01_regist.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       location.href = "pes1_pontofx001.php?ponto="+document.form1.ponto.value;
     }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.rh01_regist.focus();
    document.form1.rh01_regist.value = '';
  }else{
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisar13_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhlota','func_rhlota.php?funcao_js=parent.js_mostralotacao1|r70_codigo|r70_descr','Pesquisa',true);
  }else{
     if(document.form1.r70_codigo.value != ''){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhlota','func_rhlota.php?pesquisa_chave='+document.form1.r70_codigo.value+'&funcao_js=parent.js_mostralotacao','Pesquisa',false);
     }else{
       document.form1.r70_descr.value = '';
     }
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r70_descr.value  = chave;
  if(erro==true){
    document.form1.r70_codigo.value = '';
    document.form1.r70_codigo.focus();
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r70_codigo.value = chave1;
  document.form1.r70_descr.value  = chave2;
  db_iframe_rhlota.hide();
}

</script>
