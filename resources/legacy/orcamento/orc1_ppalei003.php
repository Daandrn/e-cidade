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
include("classes/db_ppalei_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_ppaleidadocomplementar_classe.php");
include("classes/db_ppaversao_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clppalei = new cl_ppalei;
$clppaleidadocomplementar = new cl_ppaleidadocomplementar();
$clppaversao = new cl_ppaversao();
$db_botao = false;
$db_opcao = 33;
$lSqlerro     = false;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clppaleidadocomplementar->o142_dataleippa = $o01_sequencial;
  $sWhere = "o142_ppalei = $o01_sequencial";
  $clppaleidadocomplementar->excluir(null,$sWhere);
  if ($clppaleidadocomplementar->erro_status == 0) {
      
    $lSqlerro = true;
    $clppalei->erro_msg    = $clppaleidadocomplementar->erro_msg;
    $clppalei->erro_status = "0";
  }
  
if (!$lSqlerro) {
   
   $sWhere = "o119_ppalei = $o01_sequencial";
   $clppaversao->excluir(null, $sWhere);
    if ($clppaversao->erro_status == 0) {
      
      $lSqlerro = true;
      $clppaversao->erro_status = "0";
    }   
  }
  
  if (!$lSqlerro) {
    
   $clppalei->excluir($o01_sequencial);
    if ($clppalei->erro_status == 0) {
      
      $lSqlerro = true;
      $clppalei->erro_status = "0";
    }   
  }
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clppalei->sql_record($clppalei->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   
   $sWhere = "o142_ppalei = $chavepesquisa";
   $sSqlDadosComplementares = $clppaleidadocomplementar->sql_query_file(null,'*',null, $sWhere);
   $rsDadosComplementares = $clppaleidadocomplementar->sql_record($sSqlDadosComplementares);
   if ($clppaleidadocomplementar->numrows > 0) {
    
     db_fieldsmemory($rsDadosComplementares, 0); 
    
   }
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
	include("forms/db_frmppalei.php");
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clppalei->erro_status=="0"){
    $clppalei->erro(true,false);
  }else{
    $clppalei->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
