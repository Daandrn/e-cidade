<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_veicabast_classe.php");
include("classes/db_veiccadcentraldepart_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveicabast            = new cl_veicabast;
$clveiccadcentraldepart = new cl_veiccadcentraldepart;

$clveicabast->rotulo->label("ve70_codigo");
$clveiccadcentraldepart->rotulo->label("ve37_veiccadcentral");

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
        <table width="40%" border="0" align="center" cellspacing="0">
	     <form name="form1" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tve70_codigo?>">
              <?=$Lve70_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ve70_codigo",10,$Ive70_codigo,true,"text",4,"","chave_ve70_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Tipo de Posto"><b>Tipo de Posto:</b></td>
            <td width="96%" align="left" nowrap> 
              <select name="tipo_posto">
               <option value="0">Ambos</option>
               <option value="1">Interno</option>
               <option value="2">Externo</option>
              </select>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veicabast.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = " ve70_ativo = 0 ";
      
      if (isset($chave_ve37_sequencial) && trim($chave_ve37_sequencial) != "" && $chave_ve37_sequencial != "0"){
        $dbwhere .= " and ve37_sequencial = $chave_ve37_sequencial "; 
      } else {
        $dbwhere .= " and (ve36_coddepto = ".db_getsession("DB_coddepto")." or 
                           ve37_coddepto = ".db_getsession("DB_coddepto").") ";
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_veicabast.php")==true){
             include("funcoes/db_func_veicabast.php");
           }else{
           $campos = "veicabast.*";
           }
        }

        if(isset($chave_ve70_codigo) && (trim($chave_ve70_codigo)!="") ){
	         $sql = $clveicabast->sql_query_anulado(null,$campos,"ve70_codigo","ve70_codigo = $chave_ve70_codigo and $dbwhere");
        }else if(isset($tipo_posto) && $tipo_posto == 1){  // interno
           $sql = $clveicabast->sql_query_anulado("",$campos,"ve70_codigo","$dbwhere and (ve71_nota is null or ve71_nota = '')");
        }else if(isset($tipo_posto) && $tipo_posto == 2){ // externo
           $sql = $clveicabast->sql_query_anulado("",$campos,"ve70_codigo","$dbwhere and ve34_veiccadposto is not null");
        }else {
           $sql = $clveicabast->sql_query_anulado("","distinct $campos","","$dbwhere");
        }
        $repassa = array();
        if(isset($chave_ve70_codigo)){
          $repassa = array("chave_ve70_codigo"=>$chave_ve70_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
            echo $clveicabast->sql_query_anulado(null,"*",null,"ve70_codigo = $pesquisa_chave and $dbwhere");
          $result = $clveicabast->sql_record($clveicabast->sql_query_anulado(null,"*",null,"ve70_codigo = $pesquisa_chave and $dbwhere"));
          if($clveicabast->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ve70_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_ve70_codigo",true,1,"chave_ve70_codigo",true);
</script>