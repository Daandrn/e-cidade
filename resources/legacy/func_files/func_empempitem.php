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
include("classes/db_empempitem_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempempitem = new cl_empempitem;
$clempempitem->rotulo->label("e62_numemp");
$clempempitem->rotulo->label("e62_sequen");
$clempempitem->rotulo->label("e62_item");
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
            <td width="4%" align="right" nowrap title="<?=$Te62_numemp?>">
              <?=$Le62_numemp?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e62_numemp",8,$Ie62_numemp,true,"text",4,"","chave_e62_numemp");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te62_sequen?>">
              <?=$Le62_sequen?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e62_sequen",6,$Ie62_sequen,true,"text",4,"","chave_e62_sequen");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te62_item?>">
              <?=$Le62_item?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("e62_item",6,$Ie62_item,true,"text",4,"","chave_e62_item");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframeempempitem.hide();">
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
           if(file_exists("funcoes/db_func_empempitem.php")==true){
             include("funcoes/db_func_empempitem.php");
           }else{
           $campos = "empempitem.*";
           }
        }
        
        if(isset($chave_e62_numemp) && (trim($chave_e62_numemp)!="") ){
           $dbwhere1 = "e62_numemp = ".$chave_e62_numemp;
	         $sql = $clempempitem->sql_query($chave_e62_numemp,$chave_e62_sequen,"e62_numemp, e62_item, e62_sequen, e62_quant, pc01_descrmater, e62_vlrun","e62_numemp");
           $sq = $clempempitem->sql_record($clempempitem->sql_query($chave_e62_numemp,$chave_e62_sequen,$campos,"e62_numemp"));
        }else if(isset($chave_e62_item) && (trim($chave_e62_item)!="") ){
	         $sql = $clempempitem->sql_query("","",$campos,"e62_item"," e62_item like '$chave_e62_item%' ");
           $sq = $clempempitem->sql_record($clempempitem->sql_query("","",$campos,"e62_item"," e62_item like '$chave_e62_item%' "));
        }else{
           $sql = $clempempitem->sql_query("","",$campos,"e62_numemp#e62_sequen","");
        }
       
        
        if($clempempitem->numrows>1){
          echo "<script>alert('Selecione um item do empenho');</script>"; 
        }
        db_lovrot($sql, 15, "()", "", $funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clempempitem->sql_record($clempempitem->sql_query($pesquisa_chave));
          if($clempempitem->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e62_item',false);</script>";
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