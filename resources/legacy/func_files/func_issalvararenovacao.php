<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issalvara_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissalvara = new cl_issalvara;
$clissalvara->rotulo->label("q123_sequencial");
$clissalvara->rotulo->label("q123_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tq123_sequencial?>">
              <?=$Lq123_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
               db_input("q123_sequencial",10,$Iq123_sequencial,true,"text",4,"","chave_q123_sequencial");
               ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tq123_sequencial?>">
              <strong>Nome:</strong>
            </td>
            <td width="96%" align="left" nowrap>
              <?
               db_input("z01_nome",30,"",true,"text",4,"","chave_z01_nome");
               ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" >             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $campos = "q123_sequencial, q123_inscr, z01_nome";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issalvara.php")==true){
             include("funcoes/db_func_issalvara.php");
           }else{
           $campos = "issalvara.*";
           }
        }
        $iDepto = db_getsession('DB_coddepto');
        $sWhere   = " q99_depto = {$iDepto} and q98_permiterenovacao is true ";
        $sOrderBy = " q123_sequencial ";
        if(isset($chave_q123_sequencial) && (trim($chave_q123_sequencial)!="") ){
             $sql = $clissalvara->sql_queryConsultaRenovacao(null, $campos, null, "q123_inscr = {$chave_q123_sequencial}");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
             $sql = $clissalvara->sql_queryConsultaRenovacao("",$campos,"q123_sequencial"," z01_nome ilike '$chave_z01_nome%' ");
        }else{
           $sql = $clissalvara->sql_queryConsultaRenovacao("",$campos,"q123_sequencial",$sWhere);
        }
        $repassa = array();
        if(isset($chave_q123_sequencial)){
          $repassa = array("chave_q123_sequencial"=>$chave_q123_sequencial,"chave_q123_sequencial"=>$chave_q123_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
      	
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        	
          $result = $clissalvara->sql_record($clissalvara->sql_queryConsultaRenovacao(null, $campos, " q123_sequencial DESC limit 1 ", " q123_inscr = {$pesquisa_chave} "));
          if($clissalvara->numrows!=0){
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q123_sequencial',false, '$q123_inscr', '$z01_nome');</script>";
          } else {
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
js_tabulacaoforms("form2","chave_q123_sequencial",true,1,"chave_q123_sequencial",true);
</script>