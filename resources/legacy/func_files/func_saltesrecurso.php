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
include("classes/db_saltes_classe.php");
include("classes/db_contabancaria_classe.php");
 
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsaltes = new cl_saltes;
$clcontabancaria = new cl_contabancaria;
$clsaltes->rotulo->label("k13_conta");
$clsaltes->rotulo->label("k13_descr");
$clcontabancaria->rotulo->label("db83_numerocontratooc");
$clcontabancaria->rotulo->label("db83_dataassinaturacop");
$clcontabancaria->rotulo->label("db83_codigoopcredito");
$clcontabancaria->rotulo->label("db83_tipoconta");

$ano = db_getsession("DB_anousu"); //ano 

?>  
<html> 

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form2.chave_k13_conta.focus();'>
  <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="">
            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tk13_conta ?>">
                <?= $Lk13_conta ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("k13_conta", 5, $Ik13_conta, true, "text", 4, "", "chave_k13_conta");
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tk13_descr ?>">
                <?= $Lk13_descr ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("k13_descr", 40, $Ik13_descr, true, "text", 4, "", "chave_k13_descr");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="recurso" type="hidden" id="recurso" value="<?= $recurso ?>">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_saltes.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
        $where = "";
        if (isset($data_limite) && trim($data_limite) != "") {
          $where = "(k13_limite is null or k13_limite >= '$data_limite') and";
        }
        if (!isset($pesquisa_chave)) {
          if (isset($campos) == false) {
            if (file_exists("funcoes/db_func_saltes.php") == true) {
              include("funcoes/db_func_saltes.php");
            } else {
              $campos = "saltes.*";
            }
          }
          if (isset($chave_k13_conta) && (trim($chave_k13_conta) != "")) {
            $sql = $clsaltes->sql_query($chave_k13_conta, $campos, "", "$where k13_conta=$chave_k13_conta  and c61_instit = " . db_getsession("DB_instit") . ($recurso == "0" ? "" : " and c61_codigo = $recurso"));
          } else if (isset($chave_k13_descr) && (trim($chave_k13_descr) != "")) {
            $sql = $clsaltes->sql_query("", $campos, "k13_descr", "$where k13_descr like '$chave_k13_descr%' and c61_instit = " . db_getsession("DB_instit") . ($recurso == "0" ? "" : " and c61_codigo = $recurso"));
          } else {
            $sql = $clsaltes->sql_query_anousu("", $campos, "k13_conta", "$where c61_instit = " . db_getsession("DB_instit") . ($recurso == "0" ? "" : " and c61_codigo = $recurso"));
          } 
         
          db_lovrot($sql, 15, "()", "", $funcao_js);
        } else {
          if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $result = $clsaltes->sql_record($clsaltes->sql_query_anousu(null, "*", "", "$where k13_conta=$pesquisa_chave and c61_instit = " . db_getsession("DB_instit")));

            if ($clsaltes->numrows != 0) {
              db_fieldsmemory($result, 0);
              $resultconta = $clcontabancaria->sql_record($clcontabancaria->sql_query(null, "*", "k13_conta", " db83_conta='$c63_conta'"));
              db_fieldsmemory($resultconta, 0);
              echo "<script>" . $funcao_js . "('$k13_conta','$k13_descr','$c61_codigo','$db83_codigoopcredito','$db83_tipoconta',false);</script>";
            } else {
              echo "<script>" . $funcao_js . "('','Chave(" . $pesquisa_chave . ") no Encontrado','',true);</script>";
            }
          } else {
            echo "<script>" . $funcao_js . "('','','',false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
</body>

</html>
