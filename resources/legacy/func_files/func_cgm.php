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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgm_classe.php");

db_postmemory($HTTP_POST_VARS);

if (!isset($pesquisar)) {
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
}

$clcgm = new cl_cgm;
$clrotulo = new rotulocampo;

$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_cgccpf");

$clrotulo->label("DBtxt30");
$clrotulo->label("DBtxt31");

if(isset($script) && !empty($script)) { ?>
  <script>
  <?php
    $vals = "";
    $vir  = "";
    $camp = explode(",",$valores);

    for ($f = 0; $f < count($camp); $f++) {
      $vals .= $vir . "'" . $camp[$f] . "'";
      $vir   = ",";
    }
    echo $script."(".$vals.")";
  ?>
  </script>
<?php
exit;
}

if (isset($testanome) && !isset($pesquisa_chave)) {

  $funmat = explode("\|",$funcao_js);
  $func_antes = $funmat[0];
  $valores = "";
  $camp = "";
  $vir = "";

  for ($i = 1; $i < count($funmat); $i++) {
    if($funmat[$i] == "0") {
      $funmat[$i] = "z01_numcgm";
    }

    if($funmat[$i] == "1") {
      $funmat[$i] = "z01_nome";
    }

    $valores .= "|" . $funmat[$i];
    $camp  .= $vir . $funmat[$i];
    $vir .= ",";
  }

  $funmat[0] = "js_testanome";
  $funcao_js = $funmat[0] . "|z01_numcgm|z01_ender|z01_cgccpf" . $valores;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_submit_numcgm_buscanome(numcgm){
    document.form_busca_dados.numcgm_busca_dados.value = numcgm;
    document.form_busca_dados.submit();
  }

  function js_limpa(){
    for (var i = 0; i < document.form2.elements.length; i++) {
      if (document.form2.elements[i].type == 'text') {
        document.form2.elements[i].value = "";
      }
    }
  }

  function js_hide(){
    parent.db_iframe_cgm.hide();
  }

  <?php if(isset($testanome) && !isset($pesquisa_chave)): ?>

    function js_testanome(z01_numcgm, ender, cgccpf, <?= $camp; ?>) {

      alerta = "";
      if (ender == "") {
        alerta += "Endere�o\n";
      }
      if (cgccpf == "") {
        alerta += "CPF/CNPJ\n";
      }
      if (alerta != "") {
        alert("O Contribuinte n�o possui o CGM atualizado");
        <?php echo "location.href = 'prot1_cadcgm002.php?chavepesquisa=' + z01_numcgm + '&testanome={$func_antes}&valores={$valores}&funcao_js={$func_antes}{$valores}';"; ?>
      } else {
        <?= $func_antes . "(" . $camp . ")"; ?>;
      }
    }
  <?php endif; ?>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form2" class="container" method="post" action="" >
    <fieldset>
      <legend>Pesquisa CGM</legend>
      <table border="0" class="form-container">
        <tr>
          <td>
            <label for="">
              <?= $Lz01_numcgm; ?>
            </label>
          </td>
          <td>
            <?php db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',4,"","numcgmDigitadoParaPesquisa"); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="">
              <?=$DBtxt30; ?>:
            </label>
          </td>
          <td>
            <?php db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"",'cpf'); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="">
              <?= $DBtxt31; ?>:
            </label>
          </td>
          <td>
            <?php db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"",'cnpj'); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="">
              <?=$Lz01_nome?>
            </label>
          </td>
          <td>
            <?php db_input('z01_nome',40,$Iz01_nome,true,'text',4,"",'nomeDigitadoParaPesquisa'); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <table style="margin: 0 auto;">
      <tr>
        <td>
          <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
          <input name="limpar" type="button" id="naoencontrado2" value="Limpar" onClick="js_limpa()">
          <input name="Fechar" type="button" id="limpar" value="Fechar" onClick="js_hide()">
          <?php if (isset($testanome)) { ?>
            <input name="Incluir" type="button" value="Incluir Novo CGM" onClick="location.href = 'prot1_cadcgm001.php?testanome=<?=$func_antes?>&valores=<?=$valores?>&funcao_js=<?=$func_antes.$valores?>'">
            <script>
              var permissao_parcelamento = <?= db_permissaomenu(db_getsession("DB_anousu"), 604, 1305); ?>;
              if (permissao_parcelamento == false) {
                document.form2.Incluir.disabled = true;
              }
            </script>
          <?php } ?>
        </td>
      </tr>
    </table>
  </form>

  <table class="container">
    <tr>
      <td>
        <?php if (!isset($pesquisa_chave)) { ?>
          <script>
            js_limpa();
            document.form2.nomeDigitadoParaPesquisa.focus();
          </script>
          <?php
          if (isset($campos) == false) {

            $campos = "cgm.z01_numcgm,
                       z01_nome,trim(z01_cgccpf) as z01_cgccpf,
                       case when length(trim(z01_cgccpf)) = 14 then 'JURIDICA' else 'F�SICA' end as tipo,
                       trim(z01_ender) as z01_ender,
                       z01_munic, z01_uf,
                       z01_cep,
                       z01_email,
                       z01_login,
                       z01_mae,
                       z01_pai";
          }

          $clnome = new cl_cgm;
          $sql    = '';

          if (isset($nomeDigitadoParaPesquisa) && !empty($nomeDigitadoParaPesquisa)) {
            $nomeDigitadoParaPesquisa = strtoupper($nomeDigitadoParaPesquisa);
            $sql = $clnome->sqlnome($nomeDigitadoParaPesquisa,$campos);
          } else if (isset($numcgmDigitadoParaPesquisa) && !empty($numcgmDigitadoParaPesquisa)) {
            $sql = $clnome->sql_query($numcgmDigitadoParaPesquisa,$campos);
          } else if (isset($cpf) && !empty($cpf)) {
            $sql = $clnome->sql_query("",$campos,""," z01_cgccpf = '$cpf' ");
          } else if(isset($cnpj) && !empty($cnpj)) {
            $sql = $clnome->sql_query("",$campos,""," z01_cgccpf = '$cnpj' ");
          } else {
            $sql = "";
            if (isset($z01_numcgm) && !empty($z01_numcgm)) {
              $sql = $clnome->sql_query($z01_numcgm,$campos);
            }
          }

          db_lovrot($sql, 14, "()", "", $funcao_js);
        } else {
          if (!empty($pesquisa_chave)) {
            $result = $clcgm->sql_record($clcgm->sql_query($pesquisa_chave));
            if (!isset($testanome)) {
              if (($result != false) && (pg_numrows($result) != 0)) {
                db_fieldsmemory($result, 0);
                if (isset($lNovoDetalhe) && $lNovoDetalhe == 1) {
                  echo "<script>" . $funcao_js . "('{$z01_nome}', false);</script>";
                } else {
                  echo "<script>" . $funcao_js . "(false, \"$z01_nome\");</script>";
                }
              } else {

                if (isset($lNovoDetalhe) && $lNovoDetalhe == 1) {
                  echo "<script>" . $funcao_js . "('C�digo (" . $pesquisa_chave . ") n�o Encontrado', true);</script>";
                } else {
                  echo "<script>" . $funcao_js . "(true, 'C�digo (" . $pesquisa_chave . ") n�o Encontrado');</script>";
                }
              }
            }
            else {
              if (($result != false) && (pg_numrows($result) != 0)) {
                db_fieldsmemory($result, 0);

                echo "<script>\n";
                if ($z01_ender == '' || $z01_cgccpf == '') {
                  echo "alert('Contribuinte com o CGM desatualizado')\n" . $funcao_js . "(true, 'Contribuinte com o CGM desatualizado');\n";
                } else {
                  echo "" . $funcao_js . "(false, \"$z01_nome\");\n";
                }
                echo"</script>\n";
              } else {
                //echo "<script> alert('C�digo (".$pesquisa_chave.") n�o Encontrado')</script>";
                echo "<script>" . $funcao_js . "(true, 'C�digo (" . $pesquisa_chave . ") n�o Encontrado');</script>\n";
              }
            }
          }
        }
        ?>
      </td>
    </tr>
  </table>
</body>
</html>