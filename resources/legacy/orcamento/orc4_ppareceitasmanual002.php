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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ppaestimativareceita_classe.php");
include("classes/db_ppaestimativa_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clppaestimativareceita = new cl_ppaestimativareceita;
$clppaestimativa        = new cl_ppaestimativa;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){

    db_inicio_transacao();
    $db_opcao = 2;
    $clppaestimativareceita->alterar($o06_sequencial);

    foreach($aValoresForm as $aValor) {

        $clppaestimativa->o05_valor = $aValor['valor'];
        $clppaestimativa->o05_sequencial = $aValor['sequencial'];
        $clppaestimativa->alterar($aValor['sequencial']);

    }

    db_fim_transacao();
} else if(isset($chavepesquisa)) {
  
   $db_opcao = 2;
   $result = $clppaestimativareceita->sql_record($clppaestimativareceita->sql_query_analitica($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $oLei->o119_sequencial = $o05_ppaversao;
   $oLei->o01_sequencial  = $o119_ppalei;
   $db_botao = true;

   $iAnoPcasp = db_getsession("DB_anousu");
   if (isset($_SESSION['DB_ano_pcasp']) && $_SESSION['DB_ano_pcasp'] > db_getsession("DB_anousu")) {

     $iAnoPcasp = $_SESSION['DB_ano_pcasp'];
   }
   if ($o01_anoinicio - 1 != $iAnoPcasp ) {
     $db_botao = false;  
   }

   //busca valores dos outros anos
   $aValores = array();
   $oDaoPpaEstimativa   = db_utils::getdao('ppaestimativareceita');
   $sWhere              = "o06_codrec = $o06_codrec AND o06_ppaversao = $o06_ppaversao AND o05_base = '$o05_base' AND c61_instit = ".db_getsession("DB_instit");
   $sSqlPpaEstimativa   = $oDaoPpaEstimativa->sql_query_analitica(null, "o05_sequencial, o05_valor, o06_anousu", 'o06_anousu', $sWhere);
   $rsPpaEstimativa     = $oDaoPpaEstimativa->sql_record($sSqlPpaEstimativa);
   $aValores            = db_utils::getcollectionbyrecord($rsPpaEstimativa);
   
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
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
	include("forms/db_frmppaestimativareceita.php");
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clppaestimativareceita->erro_status=="0"){
    $clppaestimativareceita->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clppaestimativareceita->erro_campo!=""){
      echo "<script> document.form1.".$clppaestimativareceita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clppaestimativareceita->erro_campo.".focus();</script>";
    }
  }else{
    $clppaestimativareceita->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","o06_ppaestimativa",true,1,"o06_ppaestimativa",true);
</script>