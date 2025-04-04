<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_veiculos_classe.php");
include("classes/db_veiccadmodelo_classe.php");
include("classes/db_veiccadmarca_classe.php");
include("classes/db_veicespecificacao_classe.php");
include "classes/db_veiculosplaca_classe.php";

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculos      = new cl_veiculos;
$clveiccadmodelo = new cl_veiccadmodelo;
$clveiccadmarca  = new cl_veiccadmarca;
$clveicespecificacao   = new cl_veicespecificacao;

$clveiculos->rotulo->label("ve01_codigo");
$clveiculos->rotulo->label("ve01_placa");
$clveiculos->rotulo->label("ve01_veiccadmodelo");
$clveiculos->rotulo->label("ve01_veiccadmarca");
$clveiculos->rotulo->label("si04_especificacao");
?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="50%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="">
            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tve01_codigo ?>">
                <?= $Lve01_codigo ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("ve01_codigo", 10, $Ive01_codigo, true, "text", 4, "", "chave_ve01_codigo");
                ?>
              </td>
            </tr>

            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tve01_placa ?>">
                <?= $Lve01_placa ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("ve01_placa", 7, $Ive01_placa, true, "text", 4, "", "chave_ve01_placa");
                ?>
              </td>
            </tr>

            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tve01_veiccadmodelo ?>">
                <?= $Lve01_veiccadmodelo ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                $result = $clveiccadmodelo->sql_record($clveiccadmodelo->sql_query_file());
                ?>
                <select name="chave_ve01_veiccadmodelo" id="chave_ve01_veiccadmodelo">
                  <option value="-1">Nenhum</option>
                  <?
                  for ($i = 0; $i < $clveiccadmodelo->numrows; $i++) {
                    db_fieldsmemory($result, $i);
                  ?>
                    <option value="<?= $ve22_codigo ?>"><? echo $ve22_codigo . " - " . $ve22_descr; ?></option>
                  <?
                  }
                  ?>
                </select>
              </td>
            </tr>

            <tr>
              <td width="4%" align="right" nowrap title="<?= $Tve01_veiccadmarca ?>">
                <?= $Lve01_veiccadmarca ?>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                $result = $clveiccadmarca->sql_record($clveiccadmarca->sql_query_file());
                ?>
                <select name="chave_ve01_veiccadmarca" id="chave_ve01_veiccadmarca">
                  <option value="-1">Nenhum</option>
                  <?
                  for ($i = 0; $i < $clveiccadmarca->numrows; $i++) {
                    db_fieldsmemory($result, $i);
                  ?>
                    <option value="<?= $ve21_codigo ?>"><? echo $ve21_codigo . " - " . $ve21_descr; ?></option>
                  <?
                  }
                  ?>
                </select>
              </td>
            </tr>

            <tr>
              <td width="4%" align="right" nowrap title="Especificação">
                <b>Especificação: </b>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                $result = $clveicespecificacao->sql_record($clveicespecificacao->sql_query_file());
                ?>
                <select name="chave_si05_codigo" id="chave_si05_codigo">
                  <option value="-1">Nenhum</option>
                  <?
                  for ($i = 0; $i < $clveicespecificacao->numrows; $i++) {
                    db_fieldsmemory($result, $i);
                  ?>
                    <option value="<?= $si05_codigo ?>"><? echo $si05_codigo . " - " . $si05_descricao; ?></option>
                  <?
                  }
                  ?>
                </select>
              </td>
            </tr>

            <tr>
              <td colspan="2">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veiculos.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
        if (!isset($pesquisa_chave)) {
          if (isset($campos) == false) {
            if (file_exists("funcoes/db_func_veiculos.php") == true) {
              include("funcoes/db_func_veiculos.php");
            } else {
              $campos = "veiculos.*";
            }
          }

          $sWhereInstituicao = null;

          if (isset($instit)) {
            $sWhereInstituicao = 'and ve01_instit = ' . db_getsession("DB_instit");
          }
          if (isset($tipoabast)) {
            $sWhereInstituicao = ' and ve01_veictipoabast = 1 ';
          }

          if (isset($central)) {
            if ($central != 0) {
              $sWhereInstituicao = " and ve40_veiccadcentral={$central} ";
            }
          }

          if (!empty($_SESSION['DB_instit'])) {
            $sWhereInstituicao .= 'and db_depart.instit = ' . $_SESSION['DB_instit'];
          }

          $campos .= ",veiculos.ve01_quantcapacidad,ve01_codigoant,db_depart.instit";

          //$dbwhere = " (ve36_coddepto = ".db_getsession("DB_coddepto")." or  ve37_coddepto = ".db_getsession("DB_coddepto").") ";

          if (isset($chave_ve01_codigo) && trim($chave_ve01_codigo) != "") {
            $sql = $clveiculos->sql_query(null, $campos, "ve01_codigo", "ve01_codigo = $chave_ve01_codigo $sWhereInstituicao ");
          } else if (isset($chave_ve01_placa) && trim($chave_ve01_placa) != "") {
            // Busca o veiculo por placa nas alterações de placa
            $clveiculosplaca  = new cl_veiculosplaca;
            $sqlBuscaVeiculo = $clveiculosplaca->sql_query(null, "distinct ve76_veiculo", "", "trim(ve76_placaanterior) like '$chave_ve01_placa%'");
            $resultBuscaVeiculo = $clveiculosplaca->sql_record($sqlBuscaVeiculo);

            $alterarplaca = null;
            if ($resultBuscaVeiculo != false && $clveiculosplaca->numrows > 0) {
              if ($clveiculosplaca->numrows == 1) {
                $alterarplaca = db_utils::getCollectionByRecord($resultBuscaVeiculo)[0];
              }
            }

            if (!empty($alterarplaca)) {
              $sql = $clveiculos->sql_query(null, $campos, "ve01_codigo", "ve01_codigo = $alterarplaca->ve76_veiculo $sWhereInstituicao ");
            } else {
              $sql = $clveiculos->sql_query("", $campos, "ve01_placa", "ve01_placa = '$chave_ve01_placa' $sWhereInstituicao ");
            }
          } else if (isset($chave_ve01_veiccadmodelo)  && trim($chave_ve01_veiccadmodelo) != "" && $chave_ve01_veiccadmodelo > 0) {
            $sql = $clveiculos->sql_query("", $campos, "veiccadmodelo.ve22_descr", "ve01_veiccadmodelo = $chave_ve01_veiccadmodelo $sWhereInstituicao ");
          } else if (isset($chave_ve01_veiccadmarca) && trim($chave_ve01_veiccadmarca) != ""  && $chave_ve01_veiccadmarca > 0) {
            $sql = $clveiculos->sql_query("", $campos, "veiccadmarca.ve21_descr", "ve01_veiccadmarca = $chave_ve01_veiccadmarca $sWhereInstituicao ");
          } else if (isset($chave_si05_codigo) && trim($chave_si05_codigo) != "" && $chave_si05_codigo > 0) {
            $sql = $clveiculos->sql_query("", $campos, "si05_descricao", "si05_codigo = $chave_si05_codigo $sWhereInstituicao ");
          } else {
            $sql = $clveiculos->sql_query("", $campos, "ve01_codigo", "1=1 $sWhereInstituicao ");
          }
          $repassa = array();
          if (
            isset($chave_ve01_codigo)        || isset($chave_ve01_placa)        ||
            isset($chave_ve01_veiccadmodelo) || isset($chave_ve01_veiccadmarca) ||
            isset($chave_si05_codigo)
          ) {
            $repassa = array(
              "chave_ve01_codigo" => $chave_ve01_codigo,
              "chave_ve01_placa" => $chave_ve01_placa,
              "chave_ve01_veiccadmodelo" => $chave_ve01_veiccadmodelo,
              "chave_ve01_veiccadmarca" => $chave_ve01_veiccadmarca,
              "chave_si05_codigo" => $chave_si05_codigo
            );
          }

          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        } else {
          if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $result = $clveiculos->sql_record($clveiculos->sql_query($pesquisa_chave));
            if (isset($sigla) && $sigla == true) {
              if ($clveiculos->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "('$ve07_sigla',false);</script>";
              }
            } else {
              if ($clveiculos->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "(false,'$ve01_codigo','$ve01_placa','$ve22_descr', '$ve01_quantcapacidad');</script>";
              } else {
                echo "<script>" . $funcao_js . "(true,'Chave(" . $pesquisa_chave . ") não Encontrado');</script>";
              }
            }
          } else {
            echo "<script>" . $funcao_js . "('',false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
</body>

</html>
<?
if (!isset($pesquisa_chave)) {
?>
  <script>
  </script>
<?
}
?>
<script>
  js_tabulacaoforms("form2", "chave_ve01_codigo", true, 1, "chave_ve01_placa", true);
</script>