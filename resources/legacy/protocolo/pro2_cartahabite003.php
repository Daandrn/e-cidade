<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('std/db_stdClass.php');
require_once('libs/db_libsys.php');
require_once('dbagata/classes/core/AgataAPI.class');
require_once('model/documentoTemplate.model.php');

$oGet = db_utils::postMemory($_GET);

ini_set("error_reporting","E_ALL & ~NOTICE");

$clagata = new cl_dbagata("projetos/modelo_habite.agt");

$oInstit = new Instituicao(db_getsession('DB_instit'));
if($oInstit->getCodigoCliente() == Instituicao::COD_CLI_BURITIZEIRO||$oInstit->getCodigoCliente() == Instituicao::COD_CLI_CURRAL_DE_DENTRO) {
	$clagata = new cl_dbagata("projetos/modelo_habite_novo.agt");
}

$api     = $clagata->api;

$sCaminhoSalvoSxw = "tmp/carta_habite_" . date('YmdHis') . db_getsession("DB_id_usuario") . ".sxw";

$api->setOutputPath($sCaminhoSalvoSxw);

$api->setParameter('$codigo_habite', $oGet->codigo);
$api->setParameter('$codigo_instituicao', db_getsession('DB_instit'));
$api->setParameter('$codigo_usuario', db_getsession('DB_id_usuario'));

try {

	$oDocumentoTemplate = new documentoTemplate(15);

} catch (Exception $eException){

	$sErroMsg  = $eException->getMessage();
	db_redireciona("db_erros.php?fechar=true&db_erro={$sErroMsg}");

}

if($api->parseOpenOffice($oDocumentoTemplate->getArquivoTemplate())){
	
	$sNomeRelatorio   = "tmp/carta_habite_" . date('YmdHis') . db_getsession("DB_id_usuario") . ".pdf";

	$sComandoConverte = db_stdClass::ex_oo2pdf($sCaminhoSalvoSxw, $sNomeRelatorio);

	if (!$sComandoConverte) {

	  $sMsg = _M('tributario.projetos.pro2_cartahabite003.falha_gerar_pdf');
		db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");

	} else {
	
		db_redireciona($sNomeRelatorio);

	}

}