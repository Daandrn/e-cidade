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
include("classes/db_orcprevrec_classe.php");
include("classes/db_orcreceita_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clorcprevrec = new cl_orcprevrec;
$clorcreceita = new cl_orcreceita;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $arr_valores = explode(",",$valores);
  $sqlerro = false;

  if($sqlerro == false){
	  for($i=0; $i<count($arr_valores); $i++){
	  	$clorcprevrec = new cl_orcprevrec;
	    $o34_mes   = ($i+1);
	  	$o34_valor = $arr_valores[$i];

		  $result_novos = $clorcprevrec->sql_record($clorcprevrec->sql_query_file(null,"o34_codigo","","o34_anousu=".db_getsession("DB_anousu")." and o34_codrec=".$receita." and o34_mes=".$o34_mes));
		  $numrows_novos = $clorcprevrec->numrows;

	  	$clorcprevrec->o34_anousu = db_getsession("DB_anousu");
	  	$clorcprevrec->o34_codrec = $receita;
	  	$clorcprevrec->o34_mes    = "$o34_mes";
	  	$clorcprevrec->o34_valor  = "$o34_valor";

		  if($numrows_novos > 0){
		  	db_fieldsmemory($result_novos, 0);
		  	$clorcprevrec->o34_codigo = $o34_codigo;
		  	$clorcprevrec->alterar($o34_codigo);
		  }else{
		  	$clorcprevrec->incluir(null);
		  }

	  	$erro_msg = "Valores mensais atualizados com sucesso.";
			if($clorcprevrec->erro_status == 0) {
				$erro_msg = $clorcprevrec->erro_msg;
				$sqlerro = true;
		  }
	  }
  }
  db_fim_transacao($sqlerro);
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
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcprevrec.php");
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
if(isset($incluir)){
	db_msgbox($erro_msg);
	echo "<script>location.href = 'orc1_orcprevrec001.php?receita=".$receita."&bimestre=".$bimestre."';</script>";
};
?>
<script>
js_tabulacaoforms("form1","receita",true,1,"receita",true);
</script>