<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_regrapontorhrubrica_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clregrapontorhrubrica = new cl_regrapontorhrubrica;
$clregrapontorhrubrica->rotulo->label("rh124_sequencial");
$clregrapontorhrubrica->rotulo->label("rh124_regraponto");
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
            <td width="4%" align="right" nowrap title="<?=$Trh124_sequencial?>">
              <?=$Lrh124_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh124_sequencial",20,$Irh124_sequencial,true,"text",4,"","chave_rh124_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh124_regraponto?>">
              <?=$Lrh124_regraponto?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh124_regraponto",20,$Irh124_regraponto,true,"text",4,"","chave_rh124_regraponto");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_regrapontorhrubrica.hide();">
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
           if(file_exists("funcoes/db_func_regrapontorhrubrica.php")==true){
             include("funcoes/db_func_regrapontorhrubrica.php");
           }else{
           $campos = "regrapontorhrubrica.*";
           }
        }
        if(isset($chave_rh124_sequencial) && (trim($chave_rh124_sequencial)!="") ){
	         $sql = $clregrapontorhrubrica->sql_query($chave_rh124_sequencial,$campos,"rh124_sequencial");
        }else if(isset($chave_rh124_regraponto) && (trim($chave_rh124_regraponto)!="") ){
	         $sql = $clregrapontorhrubrica->sql_query("",$campos,"rh124_regraponto"," rh124_regraponto like '$chave_rh124_regraponto%' ");
        }else{
           $sql = $clregrapontorhrubrica->sql_query("",$campos,"rh124_sequencial","");
        }
        $repassa = array();
        if(isset($chave_rh124_regraponto)){
          $repassa = array("chave_rh124_sequencial"=>$chave_rh124_sequencial,"chave_rh124_regraponto"=>$chave_rh124_regraponto);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clregrapontorhrubrica->sql_record($clregrapontorhrubrica->sql_query($pesquisa_chave));
          if($clregrapontorhrubrica->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh124_regraponto',false);</script>";
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
js_tabulacaoforms("form2","chave_rh124_regraponto",true,1,"chave_rh124_regraponto",true);
</script>
