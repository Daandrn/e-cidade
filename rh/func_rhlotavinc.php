<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhlotavinc_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhlotavinc = new cl_rhlotavinc;
$clrhlotavinc->rotulo->label("rh25_codlotavinc");
$clrhlotavinc->rotulo->label("rh25_codigo");
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
            <td width="4%" align="right" nowrap title="<?=$Trh25_codlotavinc?>">
              <?=$Lrh25_codlotavinc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh25_codlotavinc",6,$Irh25_codlotavinc,true,"text",4,"","chave_rh25_codlotavinc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh25_codigo?>">
              <?=$Lrh25_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh25_codigo",4,$Irh25_codigo,true,"text",4,"","chave_rh25_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhlotavinc.hide();">
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
           if(file_exists("funcoes/db_func_rhlotavinc.php")==true){
             include("funcoes/db_func_rhlotavinc.php");
           }else{
           $campos = "rhlotavinc.*";
           }
        }
        if(isset($chave_rh25_codlotavinc) && (trim($chave_rh25_codlotavinc)!="") ){
	         $sql = $clrhlotavinc->sql_query($chave_rh25_codlotavinc,$campos,"rh25_codlotavinc");
        }else if(isset($chave_rh25_codigo) && (trim($chave_rh25_codigo)!="") ){
	         $sql = $clrhlotavinc->sql_query("",$campos,"rh25_codigo"," rh25_codigo like '$chave_rh25_codigo%' ");
        }else{
           $sql = $clrhlotavinc->sql_query("",$campos,"rh25_codlotavinc","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhlotavinc->sql_record($clrhlotavinc->sql_query($pesquisa_chave));
          if($clrhlotavinc->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh25_codigo',false);</script>";
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
