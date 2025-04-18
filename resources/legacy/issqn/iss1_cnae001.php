<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cnae_classe.php");
require_once('classes/db_cnaeanalitica_classe.php');
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$clcnae          = new cl_cnae;
$clcnaeanalitica = new cl_cnaeanalitica; 
$db_opcao        = 1;
$db_botao        = true;
$erro            = false;
if(isset($incluir)){
	
  db_inicio_transacao();
  $clcnae->q71_estrutural = $oPost->q71_estrutural;
  $clcnae->q71_descr      = $oPost->q71_descr;
  $clcnae->incluir("");
  
  if($clcnae->erro_status != "0"){
  	
	  if($Tipo == 'A'){ 
	  	
	  	$clcnaeanalitica->q72_cnae = $clcnae->q71_sequencial;
	    $clcnaeanalitica->incluir("");
	    
	    if($clcnaeanalitica->erro_status == "0"){
	      db_msgbox($clcnaeanalitica->erro_msg);
	    }
	  }
  } else {
  	db_msgbox($clcnae->erro_msg);
   	$erro = true;
  }
  
  db_fim_transacao($erro);
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

<table border="0" cellspacing="0" cellpadding="0" align="center" style="position: relative; top: 40px;">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcnae.php");
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
<script>
js_tabulacaoforms("form1","q71_estrutural",true,1,"q71_estrutural",true);
</script>
<?
if(isset($incluir)){
  if($clcnae->erro_status=="0"){
    $clcnae->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcnae->erro_campo!=""){
      echo "<script> document.form1.".$clcnae->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcnae->erro_campo.".focus();</script>";
    }
  } else {
    db_msgbox($clcnaeanalitica->erro_msg);
    db_redireciona("iss1_cnae001.php");
  }
}
?>