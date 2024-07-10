<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhlotavincativ_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhlotavincativ = new cl_rhlotavincativ;
$clrhlotavincativ->rotulo->label("rh39_codlotavinc");
$clrhlotavincativ->rotulo->label("rh39_codelenov");
$clrhlotavincativ->rotulo->label("rh39_projativ");
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
            <td width="4%" align="right" nowrap title="<?=$Trh39_codlotavinc?>">
              <?=$Lrh39_codlotavinc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh39_codlotavinc",6,$Irh39_codlotavinc,true,"text",4,"","chave_rh39_codlotavinc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh39_codelenov?>">
              <?=$Lrh39_codelenov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh39_codelenov",6,$Irh39_codelenov,true,"text",4,"","chave_rh39_codelenov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh39_projativ?>">
              <?=$Lrh39_projativ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh39_projativ",4,$Irh39_projativ,true,"text",4,"","chave_rh39_projativ");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhlotavincativ.hide();">
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
           if(file_exists("funcoes/db_func_rhlotavincativ.php")==true){
             include("funcoes/db_func_rhlotavincativ.php");
           }else{
           $campos = "rhlotavincativ.*";
           }
        }
        if(isset($chave_rh39_codlotavinc) && (trim($chave_rh39_codlotavinc)!="") ){
	         $sql = $clrhlotavincativ->sql_query($chave_rh39_codlotavinc,$chave_rh39_codelenov,$campos,"rh39_codlotavinc");
        }else if(isset($chave_rh39_projativ) && (trim($chave_rh39_projativ)!="") ){
	         $sql = $clrhlotavincativ->sql_query("","",$campos,"rh39_projativ"," rh39_projativ like '$chave_rh39_projativ%' ");
        }else{
           $sql = $clrhlotavincativ->sql_query("","",$campos,"rh39_codlotavinc#rh39_codelenov","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhlotavincativ->sql_record($clrhlotavincativ->sql_query($pesquisa_chave));
          if($clrhlotavincativ->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh39_projativ',false);</script>";
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
