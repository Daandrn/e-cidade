<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empage_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempage = new cl_empage;
$clempage->rotulo->label("e80_codage");
$clempage->rotulo->label("e80_data");
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
            <td width="4%" align="right" nowrap title="<?=$Te80_codage?>">
              <?=$Le80_codage?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e80_codage",8,$Ie80_codage,true,"text",4,"","chave_e80_codage");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te80_data?>">
              <?=$Le80_data?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e80_data",10,$Ie80_data,true,"text",4,"","chave_e80_data");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empage.hide();">
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
           if(file_exists("funcoes/db_func_empage.php")==true){
             include("funcoes/db_func_empage.php");
           }else{
           $campos = "empage.*";
           }
        }
        if(isset($chave_e80_codage) && (trim($chave_e80_codage)!="") ){
	         $sql = $clempage->sql_query($chave_e80_codage,$campos,"e80_codage desc");
        }else if(isset($chave_e80_data) && (trim($chave_e80_data)!="") ){
	         $sql = $clempage->sql_query("",$campos,"e80_data desc"," e80_data like '$chave_e80_data%'");
        }else{
           $sql = $clempage->sql_query("",$campos,"e80_codage desc","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clempage->sql_record($clempage->sql_query($pesquisa_chave));
          if($clempage->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e80_data',false);</script>";
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
