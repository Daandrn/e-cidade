<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_documentoatributo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldocumentoatributo = new cl_documentoatributo;
$cldocumentoatributo->rotulo->label("db45_sequencial");
$cldocumentoatributo->rotulo->label("db45_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tdb45_sequencial?>">
              <?=$Ldb45_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db45_sequencial",10,$Idb45_sequencial,true,"text",4,"","chave_db45_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb45_sequencial?>">
              <?=$Ldb45_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db45_sequencial",10,$Idb45_sequencial,true,"text",4,"","chave_db45_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_documentoatributo.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_documentoatributo.php")==true){
             include("funcoes/db_func_documentoatributo.php");
           }else{
           $campos = "documentoatributo.*";
           }
        }
        if(isset($chave_db45_sequencial) && (trim($chave_db45_sequencial)!="") ){
	         $sql = $cldocumentoatributo->sql_query($chave_db45_sequencial,$campos,"db45_sequencial");
        }else if(isset($chave_db45_sequencial) && (trim($chave_db45_sequencial)!="") ){
	         $sql = $cldocumentoatributo->sql_query("",$campos,"db45_sequencial"," db45_sequencial like '$chave_db45_sequencial%' ");
        }else{
           $sql = $cldocumentoatributo->sql_query("",$campos,"db45_sequencial","");
        }
        $repassa = array();
        if(isset($chave_db45_sequencial)){
          $repassa = array("chave_db45_sequencial"=>$chave_db45_sequencial,"chave_db45_sequencial"=>$chave_db45_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldocumentoatributo->sql_record($cldocumentoatributo->sql_query($pesquisa_chave));
          if($cldocumentoatributo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db45_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_db45_sequencial",true,1,"chave_db45_sequencial",true);
</script>
