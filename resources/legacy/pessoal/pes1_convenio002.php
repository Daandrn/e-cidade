<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_convenio_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clconvenio = new cl_convenio;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;

  $r56_posano = $r56_posano1.$r56_posano2;
  $r56_posmes = $r56_posmes1.$r56_posmes2;
  $r56_posreg = $r56_posreg1.$r56_posreg2;
  $r56_poseve = $r56_poseve1.$r56_poseve2;
  $r56_posq01 = $r56_posq011.$r56_posq012;
  $r56_posq02 = $r56_posq021.$r56_posq022;
  $r56_posq03 = $r56_posq031.$r56_posq032;
  $r56_posq04 = $r56_posq041.$r56_posq042;
  $r56_posq05 = $r56_posq051.$r56_posq052;
  $r56_posq06 = $r56_posq061.$r56_posq062;

  $clconvenio->r56_posano = $r56_posano;
  $clconvenio->r56_posmes = $r56_posmes;
  $clconvenio->r56_posreg = $r56_posreg;
  $clconvenio->r56_instit = db_getsession('DB_instit');
  $clconvenio->r56_poseve = $r56_poseve;
  $clconvenio->r56_posq01 = $r56_posq01;
  $clconvenio->r56_posq02 = $r56_posq02;
  $clconvenio->r56_posq03 = $r56_posq03;
  $clconvenio->r56_posq04 = $r56_posq04;
  $clconvenio->r56_posq05 = $r56_posq05;
  $clconvenio->r56_posq06 = $r56_posq06;
  $clconvenio->alterar($r56_codrel, db_getsession('DB_instit'));
  db_fim_transacao();
}else if(isset($chavepesquisa)){

  $db_opcao = 2;

  $result = $clconvenio->sql_record($clconvenio->sql_query($chavepesquisa, db_getsession('DB_instit'))); 
  db_fieldsmemory($result,0);
  $db_botao = true;
  $r56_posano1 = substr($r56_posano,0,3);
  $r56_posano2 = substr($r56_posano,3,3);
  $r56_posmes1 = substr($r56_posmes,0,3);
  $r56_posmes2 = substr($r56_posmes,3,3);
  $r56_posreg1 = substr($r56_posreg,0,3);
  $r56_posreg2 = substr($r56_posreg,3,3);
  $r56_poseve1 = substr($r56_poseve,0,3);
  $r56_poseve2 = substr($r56_poseve,3,3);
  $r56_posq011 = substr($r56_posq01,0,3);
  $r56_posq012 = substr($r56_posq01,3,3);
  $r56_posq021 = substr($r56_posq02,0,3);
  $r56_posq022 = substr($r56_posq02,3,3);
  $r56_posq031 = substr($r56_posq03,0,3);
  $r56_posq032 = substr($r56_posq03,3,3);
  $r56_posq041 = substr($r56_posq04,0,3);
  $r56_posq042 = substr($r56_posq04,3,3);
  $r56_posq051 = substr($r56_posq05,0,3);
  $r56_posq052 = substr($r56_posq05,3,3);
  $r56_posq061 = substr($r56_posq06,0,3);
  $r56_posq062 = substr($r56_posq06,3,3);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmconvenio.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clconvenio->erro_status=="0"){
    $clconvenio->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clconvenio->erro_campo!=""){
      echo "<script> document.form1.".$clconvenio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconvenio->erro_campo.".focus();</script>";
    };
  }else{
    $clconvenio->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>