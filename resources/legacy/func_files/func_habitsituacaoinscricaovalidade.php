<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_habitsituacaoinscricaovalidade_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clhabitsituacaoinscricaovalidade = new cl_habitsituacaoinscricaovalidade;
$clhabitsituacaoinscricaovalidade->rotulo->label("ht14_sequencial");
$clhabitsituacaoinscricaovalidade->rotulo->label("ht14_habitsituacaoinscricao");
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
            <td width="4%" align="right" nowrap title="<?=$Tht14_sequencial?>">
              <?=$Lht14_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht14_sequencial",10,$Iht14_sequencial,true,"text",4,"","chave_ht14_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht14_habitsituacaoinscricao?>">
              <?=$Lht14_habitsituacaoinscricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht14_habitsituacaoinscricao",10,$Iht14_habitsituacaoinscricao,true,"text",4,"","chave_ht14_habitsituacaoinscricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_habitsituacaoinscricaovalidade.hide();">
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
           if(file_exists("funcoes/db_func_habitsituacaoinscricaovalidade.php")==true){
             include("funcoes/db_func_habitsituacaoinscricaovalidade.php");
           }else{
           $campos = "habitsituacaoinscricaovalidade.*";
           }
        }
        if(isset($chave_ht14_sequencial) && (trim($chave_ht14_sequencial)!="") ){
	         $sql = $clhabitsituacaoinscricaovalidade->sql_query($chave_ht14_sequencial,$campos,"ht14_sequencial");
        }else if(isset($chave_ht14_habitsituacaoinscricao) && (trim($chave_ht14_habitsituacaoinscricao)!="") ){
	         $sql = $clhabitsituacaoinscricaovalidade->sql_query("",$campos,"ht14_habitsituacaoinscricao"," ht14_habitsituacaoinscricao like '$chave_ht14_habitsituacaoinscricao%' ");
        }else{
           $sql = $clhabitsituacaoinscricaovalidade->sql_query("",$campos,"ht14_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ht14_habitsituacaoinscricao)){
          $repassa = array("chave_ht14_sequencial"=>$chave_ht14_sequencial,"chave_ht14_habitsituacaoinscricao"=>$chave_ht14_habitsituacaoinscricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clhabitsituacaoinscricaovalidade->sql_record($clhabitsituacaoinscricaovalidade->sql_query($pesquisa_chave));
          if($clhabitsituacaoinscricaovalidade->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ht14_habitsituacaoinscricao',false);</script>";
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
js_tabulacaoforms("form2","chave_ht14_habitsituacaoinscricao",true,1,"chave_ht14_habitsituacaoinscricao",true);
</script>
