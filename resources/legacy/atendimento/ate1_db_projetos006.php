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
include("classes/db_db_projetos_classe.php");
include("classes/db_db_projetosclientes_classe.php");
include("classes/db_db_projetosgrupos_classe.php");
include("classes/db_db_projetosusu_classe.php");
$cldb_projetos = new cl_db_projetos;
  /*
$cldb_projetosclientes = new cl_db_projetosclientes;
$cldb_projetosgrupos = new cl_db_projetosgrupos;
$cldb_projetosusu = new cl_db_projetosusu;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_projetosclientes->at64_sequencial=$at60_codigo;
  $cldb_projetosclientes->excluir($at60_codigo);

  if($cldb_projetosclientes->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_projetosclientes->erro_msg;
  $cldb_projetosgrupos->at63_sequencial=$at60_codigo;
  $cldb_projetosgrupos->excluir($at60_codigo);

  if($cldb_projetosgrupos->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_projetosgrupos->erro_msg;
  $cldb_projetosusu->at65_sequencial=$at60_codigo;
  $cldb_projetosusu->excluir($at60_codigo);

  if($cldb_projetosusu->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_projetosusu->erro_msg;
  $cldb_projetos->excluir($at60_codigo);
  if($cldb_projetos->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cldb_projetos->erro_msg;
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $cldb_projetos->sql_record($cldb_projetos->sql_query($chavepesquisa));
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
	include("forms/db_frmdb_projetos.php");
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
    if($cldb_projetos->erro_campo!=""){
      echo "<script> document.form1.".$cldb_projetos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_projetos->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='ate1_db_projetos003.php';
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
         parent.document.formaba.db_projetosgrupos.disabled=false;
         CurrentWindow.corpo.iframe_db_projetosgrupos.location.href='ate1_db_projetosgrupos001.php?db_opcaoal=33&at63_projeto=".@$at60_codigo."';
         parent.document.formaba.db_projetosusu.disabled=false;
         CurrentWindow.corpo.iframe_db_projetosusu.location.href='ate1_db_projetosusu001.php?db_opcaoal=33&at65_projeto=".@$at60_codigo."';
         parent.document.formaba.db_projetosclientes.disabled=false;
         CurrentWindow.corpo.iframe_db_projetosclientes.location.href='ate1_db_projetosclientes001.php?db_opcaoal=33&at64_projeto=".@$at60_codigo."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('db_projetosgrupos');";
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
