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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once('classes/db_transferenciaveiculos_classe.php');
require_once('classes/db_veiculostransferencia_classe.php');

$oVeicTransf = new cl_veiculostransferencia;
$funcao_js                = "";

/*
 * Recupera as informações passadas por GET para o objeto $oGet e efetua a busca
 * de retiradas e exibe na db_lovrot
 */
$oGet                    = db_utils::postMemory($_GET, false);
$sCampos                 = "ve81_sequencial, ve81_codigo, ve81_placa, ve80_dt_transferencia, ve81_codigonovo, ve81_codigoant, ve81_codunidadesubant, ve81_codunidadesubatual";
$sWhere                  = "ve81_codigo = {$oGet->veiculo}";
$sSqlBuscaTransferencia  = $oVeicTransf->sql_buscar_transferencia(null, $sCampos, "", $sWhere);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<center>
    <fieldset>
        <?db_lovrot($sSqlBuscaTransferencia, 15, "()", "%", $funcao_js);?>
    </fieldset>
</center>
</body>
</html>