<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
include("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET,0);

$oAbas = new cl_criaabas;

$oRotulo = new rotulocampo;
$oRotulo->label('DBtxt21');
$oRotulo->label('DBtxt22');


$aAbas    = array();
$aTitulos = array();
$aFontes  = array();
$aTamanho  = array();
$dtAnousu = db_getsession("DB_anousu");

if(isset($oGet->newlrf) && $oGet->newlrf == true){
    $iCodigoRelatorio = 96;
}else{
    $iCodigoRelatorio = 96;

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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    if ($dtAnousu <= 2007) {
      
      $oAbas->identifica = array("relatorio"=>"Relatorio");
      $oAbas->title      = array("relatorio"=>"Relatorio");
      $oAbas->src        = array("relatorio"=>"con2_anexodemonstrativofuncaosubfuncao011.php");
      $oAbas->sizecampo  = array("relatorio"=>"23");
      
    } else {
      
      $oAbas->identifica = array("relatorio"=>"Relatorio","notas"=>"Fonte/Notas Explicativas");
      $oAbas->title      = array("relatorio"=>"Relatorio","notas"=>"Fonte/Notas Explicativas");
      $oAbas->src        = array("relatorio"=>"con2_anexodemonstrativofuncaosubfuncao011.php",
                                 "notas"    =>"con2_conrelnotas.php?c83_codrel={$iCodigoRelatorio}");
      $oAbas->sizecampo  = array("relatorio"=>"23","notas"=>"23");
    }

    $oAbas->cria_abas();    
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