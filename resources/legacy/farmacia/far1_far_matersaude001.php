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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ('libs/db_utils.php');
require_once ("classes/db_far_matersaude_classe.php");
require_once ("dbforms/db_funcoes.php");
db_postmemory($_POST);
$clfar_matersaude = new cl_far_matersaude;
$db_opcao         = 1;
$db_botao         = true;

if ( isset($incluir) ) {

  $lErro = false;

  /**
   * Valida se o medicamento j� esta cadastrado
   */
  $sWhere  = " fa01_i_codmater = '{$fa01_i_codmater}' ";
  $sSql    = $clfar_matersaude->sql_query_file(null, "1", null, $sWhere);
  $rs      = db_query($sSql);
  if ( $rs && pg_num_rows($rs) > 0 ) {

    db_msgbox("Medicamento ({$m60_descr}) j� cadastrado.");
    $lErro = true;
  }

  /**
   * Valida se o c�digo de barras j� existe no banco
   */
  if ( !empty($fa01_codigobarras) && !$lErro) {

    $sWhere  = " fa01_codigobarras = '{$fa01_codigobarras}' ";
    $sSql    = $clfar_matersaude->sql_query_file(null, "1", null, $sWhere);
    $rs      = db_query($sSql);
    if ( $rs && pg_num_rows($rs) > 0 ) {

      db_msgbox("C�digo de barras ({$fa01_codigobarras}) cadastrado em outro medicamento.");
      $lErro = true;
    }

  }

    $result = db_query("SELECT fa02_i_integracaosigaf FROM far_parametros");
    if ( pg_num_rows($result)>0) {
        db_fieldsmemory($result,0);
    }

    if($fa02_i_integracaosigaf == "t" && $fa01_i_catmat == ""){
        db_msgbox("C�digo CATMAT n�o Informado !");
        $lErro = true;
    }

  if ( !$lErro ) {

  db_inicio_transacao();

    //verifica se a op��o da numera��o pelo material esta selecionada ou n�o
    $result = db_query("select fa02_b_numestoque from far_parametros");
    if ( pg_num_rows($result)>0) {
      db_fieldsmemory($result,0);
    }

    $fa01_i_codigo = null;
    if ( $fa02_b_numestoque == 't' ) {
      $fa01_i_codigo=$fa01_i_codmater;
    }

    $clfar_matersaude->fa01_i_catmat = $fa01_i_catmat;
    $clfar_matersaude->incluir($fa01_i_codigo);
    db_fim_transacao($clfar_matersaude->erro_status == '0' ? true : false);
  }
}
$sLegenda = "Inclus�o de Medicamentos";
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" >

    <div class="container">

      <?php
        include("forms/db_frmfar_matersaude.php");
      ?>
    </div>

</body>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
js_tabulacaoforms("form1","fa01_t_obs",true,1,"fa01_t_obs",true);
</script>
<?
if (isset($incluir)) {

  if ($clfar_matersaude->erro_status=="0") {

    $clfar_matersaude->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clfar_matersaude->erro_campo != "") {

      echo "<script> document.form1.".$clfar_matersaude->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfar_matersaude->erro_campo.".focus();</script>";
    }
  } else {
    $clfar_matersaude->erro(true,true);
  }
}
?>
