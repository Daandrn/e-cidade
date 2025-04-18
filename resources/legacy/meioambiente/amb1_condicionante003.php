<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$db_opcao      = 3;
$iOpcaoLicenca = 'null';
$sOpcaoTipo    = '';
$sOpcaoPadrao  = 'false';

if ( !empty($oGet->chavepesquisa) ){

  $iCodigoCondicionante = $oGet->chavepesquisa;
  $oCondicionante  = new Condicionante( $iCodigoCondicionante );

  $am10_sequencial = $iCodigoCondicionante;
  $am10_descricao  = $oCondicionante->getDescricao();
  $am10_padrao     = $oCondicionante->getPadrao();

  if ($am10_padrao == "f"){
    $am10_padrao = "false";
  }

  if ($am10_padrao == "t"){
    $am10_padrao = "true";
  }

  $oTipoLicenca    = $oCondicionante->getTipoLicenca();
  if( is_object($oTipoLicenca) ){

    $iOpcaoLicenca    = $oTipoLicenca->getSequencial();
    $am10_tipolicenca = $iOpcaoLicenca;
    $sOpcaoTipo       = "L";
  }else{

    $sOpcaoTipo    = "A";
  }

  if( $am10_padrao == 't'){
    $sOpcaoPadrao = 'true';
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, numbers.js, prototype.js, estilos.css");
      db_app::load("widgets/dbtextFieldData.widget.js, DBLancador.widget.js");
      db_app::load("AjaxRequest.js");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">

    <div class="container">
      <?php require_once("forms/db_frmcondicionante.php"); ?>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
  </body>
</html>

<?php

if( $db_opcao == 3 && empty($oGet->chavepesquisa) ){
  echo "<script>document.formCondicionantes.pesquisar.click();</script>";
}
?>