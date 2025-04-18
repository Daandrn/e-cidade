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
include("dbforms/db_funcoes.php");
include("classes/db_lab_exame_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllab_exame = new cl_lab_exame;
$cllab_exame->rotulo->label("la08_i_codigo");
$cllab_exame->rotulo->label("la08_c_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
	      <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tla08_i_codigo?>">
              <?=$Lla08_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
               db_input("la08_i_codigo",10,$Ila08_i_codigo,true,"text",4,"","chave_la08_i_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tla08_c_descr?>">
              <?=$Lla08_c_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_input("la08_c_descr",50,$Ila08_c_descr,true,"text",4,"","chave_la08_c_descr");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_exame.hide();">
             </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = "";
      $sAnd   = "";
      if (isset($iVinculo)) {

      	$sAtivo = "";
      	if (isset($iAtivo)) {
      		$sAtivo = " and la09_i_ativo = $iAtivo ";
      	}
        $sWhere .= " EXISTS (select * from lab_setorexame where la09_i_exame=la08_i_codigo $sAtivo) ";
        $sAnd    = " and ";  
      }
      if (isset($iAtivo)) {

        $sWhere .= "$sAnd la08_i_ativo=$iAtivo ";
        $sAnd    = " and ";
      }
      if (!isset($pesquisa_chave)) {

        if (isset($campos)==false) {

          if(file_exists("funcoes/db_func_lab_exame.php")==true){
            include("funcoes/db_func_lab_exame.php");
          }else{
            $campos = "lab_exame.*";
          }
        }
        if(isset($chave_la08_i_codigo) && (trim($chave_la08_i_codigo)!="") ){
	        $sql = $cllab_exame->sql_query($chave_la08_i_codigo,$campos,"la08_i_codigo",$sWhere);
        }else if(isset($chave_la08_c_descr) && (trim($chave_la08_c_descr)!="") ){
	        $sql = $cllab_exame->sql_query("",$campos,"la08_c_descr","$sWhere$sAnd la08_c_descr like '$chave_la08_c_descr%' ");
        }else{
          $sql = $cllab_exame->sql_query("",$campos,"la08_i_codigo",$sWhere);
        }
        $repassa = array();
        if(isset($chave_la08_i_codigo)){
          $repassa = array("chave_la08_i_codigo"=>$chave_la08_i_codigo,"chave_la08_c_descr"=>$chave_la08_c_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          if ( !empty($sWhere) ) {
            $sWhere  .= " and ";
          }

          $sWhere    .= " la08_i_codigo = {$pesquisa_chave} ";
          $sSqlExame  = $cllab_exame->sql_query("", "*", "", $sWhere);
          $result     = $cllab_exame->sql_record($sSqlExame);
          if ($cllab_exame->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$la08_c_descr',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_la08_i_codigo",true,1,"chave_la08_i_codigo",true);
</script>