<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcfontes_classe.php");
require("libs/db_liborcamento.php");
include("classes/db_orcparametro_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcparametro = new cl_orcparametro;
$clestrutura = new cl_estrutura;
$clorcfontes = new cl_orcfontes;
$clorcfontes->rotulo->label("o57_codfon");
$clorcfontes->rotulo->label("o57_fonte");
if(isset($o50_estrutreceita)){
 $chave_o57_fonte= str_replace(".","",$o50_estrutreceita);
}
?>
<html>
<head>
<script>
function js_limpa(){
  document.form2.chave_o57_codfon.value='';
  document.form2.o50_estrutreceita.value='';
}
</script>
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
            <td width="4%" align="right" nowrap title="<?=$To57_codfon?>">
              <?=$Lo57_codfon?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o57_codfon",6,$Io57_codfon,true,"text",4,"","chave_o57_codfon");
		       ?>
            </td>
          </tr>
<?
         $clestrutura->mascara =false;
         $clestrutura->input   =false;
         $clestrutura->nomeform="form2";//o nome do campo � DB_txtdotacao
	 $clestrutura->estrutura('o50_estrutreceita');
?> 
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onclick='js_limpa();' >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcfontes.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "o57_anousu = ".(db_getsession("DB_anousu")+1) ;
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orcfontes.php")==true){
             include("funcoes/db_func_orcfontes.php");
           }else{
           $campos = "orcfontes.*";
           }
        }
        $campos .= ", fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') as deducao";
        if(isset($chave_o57_codfon) && (trim($chave_o57_codfon)!="") ){
	         $sql = $clorcfontes->sql_query_previsao(null,null,$campos,"o57_codfon",$dbwhere." and o57_codfon = $chave_o57_codfon");
           
        }else if(isset($chave_o57_fonte) && (trim($chave_o57_fonte)!="") ){
          
	         $sql = $clorcfontes->sql_query_previsao(null,null,$campos,"o57_fonte",$dbwhere." and o57_fonte like '$chave_o57_fonte%'");
        }else{
           $sql = $clorcfontes->sql_query_previsao(null,null,$campos,"o57_fonte",$dbwhere);
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          if (isset($lPesquisaCodigo)) {
            echo $clorcfontes->sql_query_previsao(null,null,"orcfontes.*",null,$dbwhere." and o57_codfon = '$pesquisa_chave'");
             $result = $clorcfontes->sql_record($clorcfontes->sql_query_previsao(null,null,"orcfontes.*",null,$dbwhere." and o57_codfon = '$pesquisa_chave'")); 
          } else {
             $result = $clorcfontes->sql_record($clorcfontes->sql_query_previsao(null,null,"orcfontes.*",null,$dbwhere." and o57_fonte = '$pesquisa_chave'"));
          }
          if($clorcfontes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o57_descr',false);</script>";
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
