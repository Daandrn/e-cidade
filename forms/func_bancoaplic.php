<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_bancoaplic_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbancoaplic = new cl_bancoaplic;
$clbancoaplic->rotulo->label("k90_id");
$clbancoaplic->rotulo->label("k90_id");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk90_id?>">
              <?=$Lk90_id?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k90_id",10,$Ik90_id,true,"text",4,"","chave_k90_id");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk90_id?>">
              <?=$Lk90_id?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k90_id",10,$Ik90_id,true,"text",4,"","chave_k90_id");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bancoaplic.hide();">
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
           if(file_exists("funcoes/db_func_bancoaplic.php")==true){
             include("funcoes/db_func_bancoaplic.php");
           }else{
           $campos = "bancoaplic.*";
           }
        }
        if(isset($chave_k90_id) && (trim($chave_k90_id)!="") ){
	         $sql = $clbancoaplic->sql_query($chave_k90_id,$campos,"k90_id");
        }else if(isset($chave_k90_id) && (trim($chave_k90_id)!="") ){
	         $sql = $clbancoaplic->sql_query("",$campos,"k90_id"," k90_id like '$chave_k90_id%' ");
        }else{
           $sql = $clbancoaplic->sql_query("",$campos,"k90_id","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clbancoaplic->sql_record($clbancoaplic->sql_query($pesquisa_chave));
          if($clbancoaplic->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k90_id',false);</script>";
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
