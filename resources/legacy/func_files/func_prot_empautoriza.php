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
//ini_set("display_erros", 1);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empautoriza_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempautoriza = new cl_empautoriza;
$clempautoriza->rotulo->label("e54_autori");
$clempautoriza->rotulo->label("e54_anousu");
$rotulo = new rotulocampo;
$rotulo->label("z01_nome");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form2.chave_e54_autori.focus();'>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Te54_autori?>"><?=$Le54_autori?></td>
            <td width="96%" align="left" nowrap>
            <? db_input("e54_autori",6,$Ie54_autori,true,"text",4,"","chave_e54_autori"); ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>"><?=$Lz01_nome?></td>
            <td width="96%" align="left" nowrap>
            <? db_input("z01_nome",45,"",true,"text",4,"","chave_z01_nome"); ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Te54_anousu?>">
            <?=$Le54_anousu?>
            </td>
            <td width="96%" align="left" nowrap>
            <? db_input("e54_anousu",4,$Ie54_anousu,true,"text",4,"","chave_e54_anousu"); ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_protempautoriza.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
        $where_anul = "";
        if (isset($anul)) {
          $where_anul = " and e54_anulad is null ";
        }
        if (!isset($pesquisa_chave)) {
          if (isset($campos)==false) {
            if (file_exists("funcoes/db_func_empautoriza.php")==true) {
              include("funcoes/db_func_empautoriza.php");
            } else {
              $campos = "empautoriza.*";
            }
          }
          $campos = "empautoriza.e54_autori,
                     empautoriza.e54_numcgm,
                     cgm.z01_nome,
                     empautoriza.e54_depto,
                     empautoriza.e54_telef,
                     empautoriza.e54_numsol,
                     empautoriza.e54_emiss,
                     empautoriza.e54_anousu as db_e54_anousu,
                     empempenho.e60_destin";

          if (isset($chave_e54_autori) && (trim($chave_e54_autori)!="") ) {
            //$sql = $clempautoriza->sql_query(null,$campos,"e54_autori"," e54_autori=$chave_e54_autori $where_anul ");
            $sql = "
              SELECT empautoriza.e54_autori,
                     empautoriza.e54_numcgm,
                     cgm.z01_nome,
                     empautoriza.e54_depto,
                     empautoriza.e54_telef,
                     empautoriza.e54_numsol,
                     empautoriza.e54_emiss,
                     empautoriza.e54_anousu AS db_e54_anousu,
                     empempenho.e60_destin,
                     sum(e55_vltot) as e55_vltot
              FROM empautoriza
              INNER JOIN cgm ON cgm.z01_numcgm = empautoriza.e54_numcgm
              inner join empautitem on e54_autori = e55_autori
              INNER JOIN db_config ON db_config.codigo = empautoriza.e54_instit
              INNER JOIN db_usuarios ON db_usuarios.id_usuario = empautoriza.e54_login
              INNER JOIN db_depart ON db_depart.coddepto = empautoriza.e54_depto
              INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = empautoriza.e54_codcom
              INNER JOIN concarpeculiar ON concarpeculiar.c58_sequencial = empautoriza.e54_concarpeculiar
              LEFT JOIN empempaut ON empautoriza.e54_autori = empempaut.e61_autori
              LEFT JOIN empempenho ON empempenho.e60_numemp = empempaut.e61_numemp
              LEFT JOIN empautidot ON e56_autori = empautoriza.e54_autori AND e56_anousu=e54_anousu
              LEFT JOIN orcdotacao ON e56_Coddot = o58_coddot AND e56_anousu = o58_anousu
              WHERE e54_autori = {$chave_e54_autori} {$where_anul}
              group by empautoriza.e54_autori, cgm.z01_nome, empempenho.e60_destin
              ORDER BY e54_autori
            ";

          } else if (isset($chave_e54_anousu) && (trim($chave_e54_anousu)!="") ) {
            //$sql = $clempautoriza->sql_query("",$campos,"e54_anousu"," e54_anousu like '$chave_e54_anousu%' $where_anul ");
            $sql = "
              SELECT empautoriza.e54_autori,
                     empautoriza.e54_numcgm,
                     cgm.z01_nome,
                     empautoriza.e54_depto,
                     empautoriza.e54_telef,
                     empautoriza.e54_numsol,
                     empautoriza.e54_emiss,
                     empautoriza.e54_anousu AS db_e54_anousu,
                     empempenho.e60_destin,
                     sum(e55_vltot) as e55_vltot
              FROM empautoriza
              INNER JOIN cgm ON cgm.z01_numcgm = empautoriza.e54_numcgm
              inner join empautitem on e54_autori = e55_autori
              INNER JOIN db_config ON db_config.codigo = empautoriza.e54_instit
              INNER JOIN db_usuarios ON db_usuarios.id_usuario = empautoriza.e54_login
              INNER JOIN db_depart ON db_depart.coddepto = empautoriza.e54_depto
              INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = empautoriza.e54_codcom
              INNER JOIN concarpeculiar ON concarpeculiar.c58_sequencial = empautoriza.e54_concarpeculiar
              LEFT JOIN empempaut ON empautoriza.e54_autori = empempaut.e61_autori
              LEFT JOIN empempenho ON empempenho.e60_numemp = empempaut.e61_numemp
              LEFT JOIN empautidot ON e56_autori = empautoriza.e54_autori AND e56_anousu=e54_anousu
              LEFT JOIN orcdotacao ON e56_Coddot = o58_coddot AND e56_anousu = o58_anousu
              WHERE e54_anousu LIKE '{$chave_e54_anousu}%' {$where_anul}
              group by empautoriza.e54_autori, cgm.z01_nome, empempenho.e60_destin
              ORDER BY e54_anousu
            ";

          } else if (isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ) {
            //$sql = $clempautoriza->sql_query("",$campos,"e54_anousu"," z01_nome like '$chave_z01_nome%' $where_anul ");
            $sql = "
              SELECT empautoriza.e54_autori,
                     empautoriza.e54_numcgm,
                     cgm.z01_nome,
                     empautoriza.e54_depto,
                     empautoriza.e54_telef,
                     empautoriza.e54_numsol,
                     empautoriza.e54_emiss,
                     empautoriza.e54_anousu AS db_e54_anousu,
                     empempenho.e60_destin,
                     sum(e55_vltot) as e55_vltot
              FROM empautoriza
              INNER JOIN cgm ON cgm.z01_numcgm = empautoriza.e54_numcgm
              inner join empautitem on e54_autori = e55_autori
              INNER JOIN db_config ON db_config.codigo = empautoriza.e54_instit
              INNER JOIN db_usuarios ON db_usuarios.id_usuario = empautoriza.e54_login
              INNER JOIN db_depart ON db_depart.coddepto = empautoriza.e54_depto
              INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = empautoriza.e54_codcom
              INNER JOIN concarpeculiar ON concarpeculiar.c58_sequencial = empautoriza.e54_concarpeculiar
              LEFT JOIN empempaut ON empautoriza.e54_autori = empempaut.e61_autori
              LEFT JOIN empempenho ON empempenho.e60_numemp = empempaut.e61_numemp
              LEFT JOIN empautidot ON e56_autori = empautoriza.e54_autori AND e56_anousu=e54_anousu
              LEFT JOIN orcdotacao ON e56_Coddot = o58_coddot AND e56_anousu = o58_anousu
              WHERE z01_nome LIKE '{$chave_z01_nome}%' {$where_anul}
              group by empautoriza.e54_autori, cgm.z01_nome, empempenho.e60_destin
              ORDER BY e54_anousu
            ";

          } else {
            $sql = "";
          }

          db_lovrot($sql,15,"()","",$funcao_js);
        } else {
          if ($pesquisa_chave!=null && $pesquisa_chave!="") {
            /*$result = $clempautoriza->sql_record($clempautoriza->sql_query(null,"*","","e54_autori=$pesquisa_chave $where_anul "));
            if ($clempautoriza->numrows!=0) {
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$e54_autori',$z01_nome','$e54_emiss',false);</script>";
            }*/

            if (isset($prot)) {
              $sql = "
                SELECT empautoriza.e54_autori,
                       empautoriza.e54_numcgm,
                       cgm.z01_nome,
                       empautoriza.e54_depto,
                       empautoriza.e54_telef,
                       empautoriza.e54_numsol,
                       empautoriza.e54_emiss,
                       empautoriza.e54_anousu AS db_e54_anousu,
                       empempenho.e60_destin,
                       sum(e55_vltot) as e55_vltot
                FROM empautoriza
                INNER JOIN cgm ON cgm.z01_numcgm = empautoriza.e54_numcgm
                inner join empautitem on e54_autori = e55_autori
                INNER JOIN db_config ON db_config.codigo = empautoriza.e54_instit
                INNER JOIN db_usuarios ON db_usuarios.id_usuario = empautoriza.e54_login
                INNER JOIN db_depart ON db_depart.coddepto = empautoriza.e54_depto
                INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = empautoriza.e54_codcom
                INNER JOIN concarpeculiar ON concarpeculiar.c58_sequencial = empautoriza.e54_concarpeculiar
                LEFT JOIN empempaut ON empautoriza.e54_autori = empempaut.e61_autori
                LEFT JOIN empempenho ON empempenho.e60_numemp = empempaut.e61_numemp
                LEFT JOIN empautidot ON e56_autori = empautoriza.e54_autori AND e56_anousu=e54_anousu
                LEFT JOIN orcdotacao ON e56_Coddot = o58_coddot AND e56_anousu = o58_anousu
                WHERE e54_autori = '{$pesquisa_chave}'
                group by empautoriza.e54_autori, cgm.z01_nome, empempenho.e60_destin
                ORDER BY e54_autori
           ";

           $result = db_query($sql);
           db_fieldsMemory($result,0);
           echo "<script>".$funcao_js."('$e54_autori','$z01_nome','$e54_emiss','$e55_vltot',false);</script>";
          }

            else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
            }
          }

          else {
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
