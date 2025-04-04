<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *  junto com este programa; se n�o, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conplano_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplano = new cl_conplano;
$clconplano->rotulo->label("c60_codcon");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");
$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");
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
            <td width="4%" align="right" nowrap title="<?=$Tc60_codcon?>">
              <?=$Lc60_codcon?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c60_codcon",6,$Ic60_codcon,true,"text",4,"","chave_c60_codcon");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
              <?=$Lc60_estrut?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c60_estrut",15,$Ic60_estrut,true,"text",4,"","chave_c60_estrut");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc61_reduz?>">
              <?=$Lc61_reduz?>
	    </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c61_reduz",6,$Ic61_reduz,true,"text",4,"","chave_c61_reduz");
		       ?>
            </td>
            <td width="4%" align="right" nowrap title="<?=$Tc60_descr?>">
              <?=$Lc60_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("c60_descr",50,$Ic60_descr,true,"text",4,"","chave_c60_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplano.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhere = " 1 = 1 ";

      if (isset($lEstrutural) && $lEstrutural == 1) {
        $sWhere .= " and c60_estrut ilike '3%' ";
      }

      if (isset($controleext)) {
        $sWhere .= " and (c60_codsis = 7) ";
      }

      if(!isset($pesquisa_chave)) {
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conplano.php")==true){
             include("funcoes/db_func_conplano.php");
           }else{
           $campos = "conplano.*";
           }
        }

        if(isset($chave_c60_codcon) && (trim($chave_c61_reduz)!="") ){

           $sWhere .= " and c61_reduz = $chave_c61_reduz and c60_anousu = ".db_getsession("DB_anousu");
	         $sql     = $clconplano->sql_query(null,null,$campos,"c60_codcon", $sWhere);
        }elseif(isset($chave_c60_codcon) && (trim($chave_c60_codcon)!="") ){

					 $sWhere .= " and c60_codcon = $chave_c60_codcon and c60_anousu=".db_getsession("DB_anousu");
	         $sql     = $clconplano->sql_query($chave_c60_codcon,null,$campos,"c60_codcon",$sWhere);
        }else if(isset($chave_c60_estrut) && (trim($chave_c60_estrut)!="") ){

           $sWhere .= " and c60_estrut like '$chave_c60_estrut%' and c60_anousu=".db_getsession("DB_anousu");
	         $sql     = $clconplano->sql_query("",null,$campos,"c60_codcon", $sWhere);
        }else if(isset($chave_c60_descr) && (trim($chave_c60_descr)!="") ){

           $sWhere .= " and upper(c60_descr) like '$chave_c60_descr%'  and c60_anousu=".db_getsession("DB_anousu");
	         $sql     = $clconplano->sql_query("",null,$campos,"c60_descr",$sWhere);
        }else if( isset($tipo_sql) ){

           $sWhere .= " and c60_anousu=".db_getsession("DB_anousu");
           $sql     = $clconplano->sql_query_reduz("",$campos,null, "c61_reduz as db_c61_reduz,c60_estrut as db_c60_estrut","c60_estrut", $sWhere);
        }
        else if ($filtroCodsis != null && $filtroCodsis != ""){
          $sWhere .= " and c60_codsis = $filtroCodsis and c60_anousu=".db_getsession("DB_anousu");
          $sql     = $clconplano->sql_query("",null,$campos,"c60_estrut", $sWhere);
        }
        else{
          $sWhere .= " and c60_anousu=".db_getsession("DB_anousu");
           $sql     = $clconplano->sql_query("",null,$campos,"c60_estrut", $sWhere);
        }
        db_lovrot($sql,15,"()","",$funcao_js);

      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $campo = "c60_codcon";
          if (($reduz == true) && ($filtroCodsis != null && $filtroCodsis != "")) {
            $campo = "c60_codsis = $filtroCodsis and c61_reduz ";
          } elseif($reduz == true) {
            $campo = "c61_reduz";
          }

          $sWhere .= " and $campo = $pesquisa_chave and c60_anousu = ".db_getsession("DB_anousu");
          $result  = $clconplano->sql_record($clconplano->sql_query2(null, null, "*", null, $sWhere));

          if($clconplano->numrows!=0){

            db_fieldsmemory($result,0);

            if (isset($pegaAnousu)) {
              echo "<script>".$funcao_js."('{$c60_descr}', '{$c60_anousu}',false);</script>";
            } else {
              echo "<script>".$funcao_js."('$c60_descr',false, '$c60_estrut');</script>";
            }

          } else {

	         echo "<script>".$funcao_js."('Reduzido(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }

        } else {
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
