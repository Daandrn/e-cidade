<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhlotaexe_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhlotaexe = new cl_rhlotaexe;
$clrhlotaexe->rotulo->label("rh26_anousu");
$clrhlotaexe->rotulo->label("rh26_codigo");
$clrhlotaexe->rotulo->label("rh26_unidade");
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
            <td width="4%" align="right" nowrap title="<?=$Trh26_codigo?>">
              <?=$Lrh26_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh26_codigo",4,$Irh26_codigo,true,"text",4,"","chave_rh26_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh26_unidade?>">
              <?=$Lrh26_unidade?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh26_unidade",2,$Irh26_unidade,true,"text",4,"","chave_rh26_unidade");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhlotaexe.hide();">
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
           if(file_exists("funcoes/db_func_rhlotaexe.php")==true){
             include("funcoes/db_func_rhlotaexe.php");
           }else{
           $campos = "rhlotaexe.*";
           }
        }
        if(isset($chave_rh26_codigo) && (trim($chave_rh26_codigo)!="") ){
	         $sql = $clrhlotaexe->sql_query(db_getsession('DB_anousu'),$chave_rh26_codigo,$campos,"rh26_codigo");
        }else if(isset($chave_rh26_unidade) && (trim($chave_rh26_unidade)!="") ){
	         $sql = $clrhlotaexe->sql_query(db_getsession('DB_anousu'),"",$campos,"rh26_unidade"," rh26_unidade like '$chave_rh26_unidade%' ");
        }else{
           $sql = $clrhlotaexe->sql_query(db_getsession('DB_anousu'),"",$campos,"rh26_anousu#rh26_codigo","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhlotaexe->sql_record($clrhlotaexe->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clrhlotaexe->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh26_unidade',false);</script>";
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
