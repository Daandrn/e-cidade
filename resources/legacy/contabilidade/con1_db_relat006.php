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
include("classes/db_db_relat_classe.php");
include("classes/db_db_relattabelas_classe.php");
include("classes/db_db_relatfiltros_classe.php");
include("classes/db_db_relatcabec_classe.php");
include("classes/db_db_relatselecionados_classe.php");
include("classes/db_db_relatsoma_classe.php");
include("classes/db_db_relatquebra_classe.php");
$cldb_relat = new cl_db_relat;
  /*
$cldb_relattabelas = new cl_db_relattabelas;
$cldb_relatfiltros = new cl_db_relatfiltros;
$cldb_relatcabec = new cl_db_relatcabec;
$cldb_relatselecionados = new cl_db_relatselecionados;
$cldb_relatsoma = new cl_db_relatsoma;
$cldb_relatquebra = new cl_db_relatquebra;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_relattabelas->db92_codigo=$db91_codrel;
  $cldb_relattabelas->excluir($db91_codrel);

  if($cldb_relattabelas->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relattabelas->erro_msg;
  $cldb_relatfiltros->db94_codigo=$db91_codrel;
  $cldb_relatfiltros->excluir($db91_codrel);

  if($cldb_relatfiltros->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relatfiltros->erro_msg;
  $cldb_relatcabec->db95_codigo=$db91_codrel;
  $cldb_relatcabec->excluir($db91_codrel);

  if($cldb_relatcabec->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relatcabec->erro_msg;
  $cldb_relatselecionados->db93_codigo=$db91_codrel;
  $cldb_relatselecionados->excluir($db91_codrel);

  if($cldb_relatselecionados->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relatselecionados->erro_msg;
  $cldb_relatsoma->db96_codigo=$db91_codrel;
  $cldb_relatsoma->excluir($db91_codrel);

  if($cldb_relatsoma->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relatsoma->erro_msg;
  $cldb_relatquebra->db97_codigo=$db91_codrel;
  $cldb_relatquebra->excluir($db91_codrel);

  if($cldb_relatquebra->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relatquebra->erro_msg;
  $cldb_relat->excluir($db91_codrel);
  if($cldb_relat->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_relat->erro_msg;
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $cldb_relat->sql_record($cldb_relat->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmdb_relat.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_relat->erro_campo!=""){
      echo "<script> document.form1.".$cldb_relat->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_relat->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='con1_db_relat003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.db_relattabelas.disabled=false;
         CurrentWindow.corpo.iframe_db_relattabelas.location.href='con1_db_relattabelas001.php?db_opcaoal=33&db92_codigo=".@$db91_codrel."';
         parent.document.formaba.db_relatfiltros.disabled=false;
         CurrentWindow.corpo.iframe_db_relatfiltros.location.href='con1_db_relatfiltros001.php?db_opcaoal=33&db94_codigo=".@$db91_codrel."';
         parent.document.formaba.db_relatcabec.disabled=false;
         CurrentWindow.corpo.iframe_db_relatcabec.location.href='con1_db_relatcabec001.php?db_opcaoal=33&db95_codigo=".@$db91_codrel."';
         parent.document.formaba.db_relatselecionados.disabled=false;
         CurrentWindow.corpo.iframe_db_relatselecionados.location.href='con1_db_relatselecionados001.php?db_opcaoal=33&db93_codigo=".@$db91_codrel."';
         parent.document.formaba.db_relatsoma.disabled=false;
         CurrentWindow.corpo.iframe_db_relatsoma.location.href='con1_db_relatsoma001.php?db_opcaoal=33&db96_codigo=".@$db91_codrel."';
         parent.document.formaba.db_relatquebra.disabled=false;
         CurrentWindow.corpo.iframe_db_relatquebra.location.href='con1_db_relatquebra001.php?db_opcaoal=33&db97_codigo=".@$db91_codrel."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('db_relattabelas');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
