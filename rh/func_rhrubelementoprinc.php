<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhrubelementoprinc_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhrubelementoprinc = new cl_rhrubelementoprinc;
$clrhrubelementoprinc->rotulo->label("rh24_rubric");
$clrhrubelementoprinc->rotulo->label("rh24_codele");
$clrhrubelementoprinc->rotulo->label("rh24_codele");
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
            <td width="4%" align="right" nowrap title="<?=$Trh24_rubric?>">
              <?=$Lrh24_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh24_rubric",4,$Irh24_rubric,true,"text",4,"","chave_rh24_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh24_codele?>">
              <?=$Lrh24_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh24_codele",6,$Irh24_codele,true,"text",4,"","chave_rh24_codele");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh24_codele?>">
              <?=$Lrh24_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh24_codele",6,$Irh24_codele,true,"text",4,"","chave_rh24_codele");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhrubelementoprinc.hide();">
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
           if(file_exists("funcoes/db_func_rhrubelementoprinc.php")==true){
             include("funcoes/db_func_rhrubelementoprinc.php");
           }else{
           $campos = "rhrubelementoprinc.*";
           }
        }
        if(isset($chave_rh24_rubric) && (trim($chave_rh24_rubric)!="") ){
	         $sql = $clrhrubelementoprinc->sql_query($chave_rh24_rubric,$chave_rh24_codele,$campos,"rh24_rubric");
        }else if(isset($chave_rh24_codele) && (trim($chave_rh24_codele)!="") ){
	         $sql = $clrhrubelementoprinc->sql_query("","",$campos,"rh24_codele"," rh24_codele like '$chave_rh24_codele%' ");
        }else{
           $sql = $clrhrubelementoprinc->sql_query("","",$campos,"rh24_rubric#rh24_codele","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhrubelementoprinc->sql_record($clrhrubelementoprinc->sql_query($pesquisa_chave));
          if($clrhrubelementoprinc->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh24_codele',false);</script>";
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
