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
include("classes/db_pcgrupo_classe.php");
include("classes/db_pcsubgrupo_classe.php");
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_historicomaterial_classe.php");
require_once 'libs/renderComponents/index.php';
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$db_botao = false;
$db_opcao = 33;
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir") {
  db_inicio_transacao();
  $db_opcao = 3;
  $sqlerro = false;
  //rotina que exclui todos os registros do pcmaterele
  if ($sqlerro == false) {
    $clpcmaterele->pc07_codmater = $pc01_codmater;
    $clpcmaterele->excluir($pc01_codmater);
    if ($clpcmaterele->erro_status == 0) {
      db_msgbox('erro');
      $sqlerro = true;
    }
  }
  db_query("delete from importacaoitens where pc96_codmater = $pc01_codmater;");
  $clpcmater->excluir($pc01_codmater);
  if ($clpcmater->erro_status == 0) {
    $sqlerro = true;
  }

  if($sqlerro == false){
    $clhistoricomaterial = new cl_historicomaterial;
    $clhistoricomaterial->excluir(
      null,
      "db150_coditem = $pc01_codmater"
    );
    
    if ($clhistoricomaterial->erro_status == 0) {
      $sqlerro = true;
    }
  }

  db_fim_transacao($sqlerro);
} else if (isset($chavepesquisa)) {
  $db_opcao = 3;
  $db_botao = true;
  $result = $clpcmater->sql_record($clpcmater->sql_query($chavepesquisa));
  db_fieldsmemory($result, 0);

  $result = $clpcmaterele->sql_record($clpcmaterele->sql_query_file($chavepesquisa));
  $numrows = $clpcmaterele->numrows;
  $coluna =  '';
  $sep = '';
  for ($i = 0; $i < $numrows; $i++) {
    db_fieldsmemory($result, $i);
    $coluna .=  $sep . $pc07_codele;
    $sep     = "XX";
  }
}
?>

<script type="text/javascript" defer>
  loadComponents(['buttonsSolid']);
</script>

<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?php db_app::load("estilos.bootstrap.css");?>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
          <?
          include("forms/db_frmpcmater.php");
          ?>
        </td>
      </tr>
    </table>
  </center>
  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>

</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir") {
  if ($clpcmater->erro_status == "0") {
    $clpcmater->erro(true, false);
  } else {
    $clpcmater->erro(true, true);
  };
};
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>