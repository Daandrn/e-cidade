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
require_once("libs/db_libpessoal.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

$oJson              = new services_json();
$oParametros        = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = "";

try {

  switch ($oParametros->sExecucao) {

    case "getPreferencias":

      $oUsuario                = UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario"));
      $oPreferencias           = $oUsuario->getPreferenciasUsuario();
      $oRetorno->oPreferencias = $oJson->decode($oPreferencias->toJSON());
      break;
    case "salvar":

      $oUsuario      = UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario"));

      $oPreferencias = $oUsuario->getPreferenciasUsuario();

      $oPreferencias->setOrdenacao($oParametros->oPreferencias->ordenacao);
      $oPreferencias->setSkin($oParametros->oPreferencias->skin);
      $oPreferencias->setExibeBusca($oParametros->oPreferencias->busca);
      $oPreferencias->setHabilitaCacheMenu($oParametros->oPreferencias->lHabilitaCacheMenu);

      $oPreferencias->limparFiltrosPersonalizados();

      foreach ($oParametros->oPreferencias->oFiltrosPersonalizados as $sRotina => $aFiltros) {
        foreach ($aFiltros as $sFiltro) {
          $oPreferencias->adicionarFiltroPersonalizado($sRotina, $sFiltro);
        }
      }

      $oPreferencias->salvar();

      break;
    default:
      throw new Exception("Erro ao processar a requisição. Método n�o encontrado.");
      break;
  }
} catch (Exception $eException) {

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eException->getMessage());
}

echo $oJson->encode($oRetorno);
