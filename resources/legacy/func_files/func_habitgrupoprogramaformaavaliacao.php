<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_habitgrupoprogramaformaavaliacao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clhabitgrupoprogramaformaavaliacao = new cl_habitgrupoprogramaformaavaliacao;
$clhabitgrupoprogramaformaavaliacao->rotulo->label("ht06_sequencial");
$clhabitgrupoprogramaformaavaliacao->rotulo->label("ht06_habittipogrupoprograma");
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
            <td width="4%" align="right" nowrap title="<?=$Tht06_sequencial?>">
              <?=$Lht06_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht06_sequencial",10,$Iht06_sequencial,true,"text",4,"","chave_ht06_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht06_habittipogrupoprograma?>">
              <?=$Lht06_habittipogrupoprograma?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht06_habittipogrupoprograma",10,$Iht06_habittipogrupoprograma,true,"text",4,"","chave_ht06_habittipogrupoprograma");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_habitgrupoprogramaformaavaliacao.hide();">
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
           if(file_exists("funcoes/db_func_habitgrupoprogramaformaavaliacao.php")==true){
             include("funcoes/db_func_habitgrupoprogramaformaavaliacao.php");
           }else{
           $campos = "habitgrupoprogramaformaavaliacao.*";
           }
        }
        if(isset($chave_ht06_sequencial) && (trim($chave_ht06_sequencial)!="") ){
	         $sql = $clhabitgrupoprogramaformaavaliacao->sql_query($chave_ht06_sequencial,$campos,"ht06_sequencial");
        }else if(isset($chave_ht06_habittipogrupoprograma) && (trim($chave_ht06_habittipogrupoprograma)!="") ){
	         $sql = $clhabitgrupoprogramaformaavaliacao->sql_query("",$campos,"ht06_habittipogrupoprograma"," ht06_habittipogrupoprograma like '$chave_ht06_habittipogrupoprograma%' ");
        }else{
           $sql = $clhabitgrupoprogramaformaavaliacao->sql_query("",$campos,"ht06_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ht06_habittipogrupoprograma)){
          $repassa = array("chave_ht06_sequencial"=>$chave_ht06_sequencial,"chave_ht06_habittipogrupoprograma"=>$chave_ht06_habittipogrupoprograma);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clhabitgrupoprogramaformaavaliacao->sql_record($clhabitgrupoprogramaformaavaliacao->sql_query($pesquisa_chave));
          if($clhabitgrupoprogramaformaavaliacao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ht06_habittipogrupoprograma',false);</script>";
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
js_tabulacaoforms("form2","chave_ht06_habittipogrupoprograma",true,1,"chave_ht06_habittipogrupoprograma",true);
</script>
