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
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("dbforms/db_classesgenericas.php");
  require_once("classes/db_pcparam_classe.php");
  require_once("std/db_stdClass.php");
  require_once("libs/db_utils.php");
  db_postmemory($HTTP_GET_VARS);

  $clpcparam  = new cl_pcparam;
  $clcriaabas = new cl_criaabas;

  $db_opcao   = 1;
  $erro       = false;

  $result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "*"));
  if($clpcparam->numrows>0){
      db_fieldsmemory($result_tipo,0);
  } else {
      $erro = true;
  }
  
  $lTemParametroRegistro  = false;
  $aParametrosRegistro = db_stdClass::getParametro('registroprecoparam', array(db_getsession("DB_instit")));
  if (count($aParametrosRegistro) > 0) {
    $lTemParametroRegistro = true;
  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onbeforeunload='js_clearSession()'>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
     <td>
     <?
     $clcriaabas->identifica = array("registro" => "Estimativa","itens"=>"Itens");//nome do iframe e o label    
     $clcriaabas->src        = array("registro" => "com4_estimativaregistro011.php?pc54_solicita=$codigoAbertura", 
                                     "itens"    => "com4_solicitaestimativaitens.php");    
     $clcriaabas->title      = array("registro" => "Estimativa","itens"=>"Itens");//nome do iframe e o label    
     $clcriaabas->sizecampo  = array("registro" => "20","itens"=>"20");
     $clcriaabas->cria_abas();
                
     ?> 
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
if (!$lTemParametroRegistro){
      
    db_msgbox('Par�metros do registro de pre�o nao configurado!');
    db_redireciona('com1_registroprecoparam002.php');
  }
?>
</body>
</html>
<script>
js_clearSession = function() {

  var oParam  = new Object();
  oParam.exec = "clearSession"; 
  var oAjax   = new Ajax.Request('com4_solicitacaoCompras.RPC.php',
                                 {
                                  method: "post",
                                  parameters:'json='+Object.toJSON(oParam),
                                });
  return true;
}
</script>
<?
if($erro == true){
  db_msgbox("Par�metros do compras n�o configurados");
}
?>