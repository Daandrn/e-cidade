<?php

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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pcmater_classe.php");
require_once("classes/db_pcmaterele_classe.php");
require_once("classes/db_pcgrupo_classe.php");
require_once("classes/db_pcsubgrupo_classe.php");
db_postmemory($_GET);
db_postmemory($_POST);
$clpcmater    = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo    = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$clpcgrupo->rotulo->label();
$clpcsubgrupo->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc07_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
if (isset($o56_codele) and trim($o56_codele) != '') {
  $chave_pc07_codele = $o56_codele;
}

?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>
    function js_reload() {
      document.form1.submit();
    }
  </script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='document.form1.chave_pc01_descrmater.focus();'>
  <table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="3">
          <form name="form1" method="post" action="">
            <tr>
              <td width="4%" align="left" nowrap title="<?= $Tpc01_codmater ?>"><?= $Lpc01_codmater ?></td>
              <td width="46%" align="left" nowrap><? db_input("pc01_codmater", 6, $Ipc01_codmater, true, "text", 4, "", "chave_pc01_codmater"); ?> </td>
              <td width="4%" align="left" nowrap title="<?= $Tpc01_descrmater ?>"> <?= $Lpc01_descrmater ?></td>
              <td width="46%" align="left" nowrap><? db_input("pc01_descrmater", 50, $Ipc01_descrmater, true, "text", 4, "", "chave_pc01_descrmater"); ?></td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?= $Tpc07_codele ?>"><b>C�digo do Elemento:</b></td>
              <td width="46%" align="left" nowrap><? db_input("pc07_codele", 6, $Ipc07_codele, true, "text", 4, "", "chave_pc07_codele"); ?> </td>
              <td width="4%" align="left" nowrap title="<?= $To56_descr ?>"> <b>Descri��o do Elemento:</b></td>
              <td width="46%" align="left" nowrap><? db_input("o56_descr", 50, $Io56_descr, true, "text", 4, "", "chave_o56_descr"); ?></td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?= $To56_elemento ?>"> <?= $Lo56_elemento ?></td>
              <td width="46%" align="left" nowrap><? db_input("o56_elemento", 15, $Io56_elemento, true, "text", 4, "", "chave_o56_elemento"); ?></td>
              <td width="4%" align="left" nowrap title="Selecionar todos, ativos ou inativos"><b>Sele��o por:</b></td>
              <td width="46%" align="left" nowrap>
                <?
                if (!isset($opcao)) {
                  $opcao = "f";
                }
                if (!isset($opcao_bloq)) {
                  $opcao_bloq = 1;
                }
                $arr_opcao = array("i" => "Todos", "f" => "Ativos", "t" => "Inativos");
                db_select('opcao', $arr_opcao, true, $opcao_bloq, "onchange='js_reload();'");
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?= $Tpc03_codgrupo ?>"><b>Grupo:</b></td>
              <td width="46%" align="left" nowrap>
                <?
                $res_pcgrupo = $clpcgrupo->sql_record($clpcgrupo->sql_query_file(null, "pc03_codgrupo,pc03_descrgrupo", "pc03_descrgrupo", "pc03_ativo='t'"));
                db_selectrecord("pc03_codgrupo", $res_pcgrupo, true, 4, "", "chave_pc03_codgrupo", "", "0-Todos", "js_reload()");
                ?>
              </td>
              <td width="4%" align="left" nowrap title="<?= $Tpc04_codsubgrupo ?>"><b>Subgrupo:</b></td>
              <td width="46%" align="left" nowrap>
                <?

                $sWhereSubGrupo  = "pc04_ativo='t' ";
                if (isset($chave_pc03_codgrupo) && !empty($chave_pc03_codgrupo)) {

                  $sWhereSubGrupo .= "and pc04_codgrupo = " . @$chave_pc03_codgrupo;
                }

                $sCamposSubGrupo = "pc04_codsubgrupo,pc04_descrsubgrupo";

                $sSqlSubGrupo   = $clpcsubgrupo->sql_query_file(null, $sCamposSubGrupo, "pc04_descrsubgrupo", $sWhereSubGrupo);
                $res_pcsubgrupo = $clpcsubgrupo->sql_record($sSqlSubGrupo);

                db_selectrecord("pc04_codsubgrupo", $res_pcsubgrupo, true, 4, "", "chave_pc04_codsubgrupo", "", "0-Todos", "js_reload()");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="4" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcmater.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?
        $dDataAtual = date('Y-m-d', db_getsession("DB_datausu"));

        //echo($clpcmaterele->sql_query_file(null,null,"pc07_codmater as pc01_codmater","pc07_codmater"," pc07_codele=$o56_codele "));exit;
        $where_ativo = " pc07_codele is not null ";

        $instituicao = db_getsession('DB_instit');

        $where_instituicao = " and pcmater.pc01_instit in (0, $instituicao) ";

        /*
       * Trecho descontinuado devido a OC3770
       * Coment�rio removido do trecho devido a OC10276
       */
        $where_ativo .= " and pc01_data <= '$dDataAtual' ";

        if (isset($opcao) && trim($opcao) != "i") {
          $where_ativo .= " and pc01_ativo='$opcao' ";
        }

        if (!empty($lServico)) {
          $where_ativo .= " and pc01_servico is {$lServico} ";
        }

        if (!isset($pesquisa_chave)) {
          if (empty($campos)) {
            if (file_exists("funcoes/db_func_pcmatersolicita.php") == true) {
              include("funcoes/db_func_pcmatersolicita.php");
            } else {
              $campos = "pcmater.*";
            }
          }

          $campos = "distinct pcmater.pc01_codmater,
                 regexp_replace(pcmater.pc01_descrmater,'\r|\n','','g') as pc01_descrmater,
                 pcmater.pc01_complmater,o56_codele,o56_elemento,substr(o56_descr,1,40) as o56_descr,
                 pcsubgrupo.pc04_descrsubgrupo as DB_pc04_descrsubgrupo,
                 CASE
                  WHEN pcmater.pc01_servico = false THEN 'N�o'
                  WHEN pcmater.pc01_servico = true THEN 'Sim'
                  END AS pc01_servico,
                  CASE
                  WHEN pcmater.pc01_veiculo = false THEN 'N�o'
                  WHEN pcmater.pc01_veiculo = true THEN 'Sim'
                  END AS pc01_veiculo,
                  pcmater.pc01_instit,
                  pcmater.pc01_codmaterant
                 ";

          $repassa = array(
            "chave_pc01_codmater"    => @$chave_pc01_codmater,
            "chave_pc01_descrmater"  => @$chave_pc01_descrmater,
            "chave_pc07_codele"      => @$chave_pc07_codele,
            "chave_o56_descr"        => @$chave_o56_descr,
            "chave_o56_elemento"     => @$chave_o56_elemento,
            "chave_pc03_codgrupo"    => @$chave_pc03_codgrupo,
            "chave_pc04_codsubgrupo" => @$chave_pc04_codsubgrupo
          );

          $sql = $clpcmater->sql_query_desdobra(null, $campos, "pc01_codmater", " $where_ativo $where_instituicao");

            if (isset($chave_pc01_codmater) && (trim($chave_pc01_codmater) != "")) {
            $sql = $clpcmater->sql_query_desdobra(null, $campos, "pc01_codmater", "pc01_codmater=$chave_pc01_codmater and $where_ativo $where_instituicao");
          } else if (isset($chave_pc01_descrmater) && (trim($chave_pc01_descrmater) != "")) {
            $sql = $clpcmater->sql_query_desdobra("", $campos, "pc01_descrmater", " pc01_descrmater like '$chave_pc01_descrmater%' and $where_ativo $where_instituicao");
          } elseif (isset($chave_pc07_codele) && (trim($chave_pc07_codele) != "")) {
            $sql = $clpcmater->sql_query_desdobra(null, $campos, "pc01_codmater", "pc07_codele=$chave_pc07_codele and $where_ativo $where_instituicao");
          } else if (isset($chave_o56_descr) && (trim($chave_o56_descr) != "")) {
            $sql = $clpcmater->sql_query_desdobra("", $campos, "pc01_codmater", " o56_descr like '$chave_o56_descr%' and $where_ativo $where_instituicao");
          } else if (isset($chave_o56_elemento) && (trim($chave_o56_elemento) != "")) {
            $sql = $clpcmater->sql_query_desdobra("", $campos, "pc01_codmater", " o56_elemento like '$chave_o56_elemento%' and $where_ativo $where_instituicao");
          } else if (isset($chave_pc03_codgrupo) && trim($chave_pc03_codgrupo) != "" && $chave_pc03_codgrupo <> 0) {
            if (isset($chave_pc04_codsubgrupo) && trim($chave_pc04_codsubgrupo) != "" && $chave_pc04_codsubgrupo <> 0) {
              $where_subgrupo = " and pc04_codsubgrupo = $chave_pc04_codsubgrupo ";
            } else {
              $where_subgrupo = "";
            }
            $sql = $clpcmater->sql_query_desdobra("", $campos, "pc01_codmater", "pc03_codgrupo = $chave_pc03_codgrupo and pc04_codsubgrupo = pc01_codsubgrupo and pc04_codgrupo = pc03_codgrupo and $where_ativo $where_subgrupo $where_instituicao");
          }
          db_lovrot(@$sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        } else {

          if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $result = $clpcmater->sql_record($clpcmater->sql_query_desdobra(null, "regexp_replace(pcmater.pc01_descrmater,'\r|\n','','g') as pc01_descrmater,pc01_veiculo,pc01_servico, pc01_complmater", "", "pc01_codmater=$pesquisa_chave and $where_ativo $where_instituicao"));
            if ($clpcmater->numrows != 0) {
              db_fieldsmemory($result, 0);
              echo "<script>" . $funcao_js . "('" . addslashes($pc01_descrmater) . "',false,'$pc01_veiculo','$pc01_servico');</script>";
            } else {
              echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") n�o Encontrado',true);</script>";
            }
          } else {
            echo "<script>" . $funcao_js . "('',false,'$pc01_servico');</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
</body>

</html>
<script>
  <?
  // CADASTRO DE PCMATER
  // Quando o usu�rio for incluir um item, aparecer� a func_pcmater.php para caso ele queira pegar dados de um item
  // j� criado... EX.:o usu�rio ja tem um cadastro de caneta preta com Elemens,grupo e sub-grupo... Para o cadastro
  // de uma caneta azul,usar� os mesmos dados e mudar� apenas a descri��o do item... Ent�o, quando ele selecionar o
  // item caneta preta, a func retornar� os dados para o usu�rio alterar apenas a descri��o. Caso o item procurado
  // n�o exista  (numrows seja igual a zero), a func jogar� para o cadastro apenas a descri��o procurada...
  if (isset($zero)) {
    echo "parent.document.form1.pc01_descrmater.value = document.form1.chave_pc01_descrmater.value;";
    echo "parent.db_iframe_pcmater.hide();";
  }
  ?>
</script>
