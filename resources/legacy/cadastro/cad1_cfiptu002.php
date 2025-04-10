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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cfiptu_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_documentotemplate_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cldb_documentotemplate = new cl_db_documentotemplate;
$clcfiptu               = new cl_cfiptu;
$db_botao               = false;
$db_opcao               = 2;

try {

 /**
  * Valida Certidao Existencia
  */
/*if(isset($j18_templatecertidaoexitencia) && $j18_templatecertidaoexitencia != ""){

  $rsExistencia = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query_file(null,'*',null,"db82_sequencial = {$j18_templatecertidaoexitencia} and db82_templatetipo = 18"));

  if ($cldb_documentotemplate->numrows == 0) {
    throw new Exception("Campo Documento Template Exist�ncia Inexistente. Altera��o abortada.");
  }
}*/

 /**
  * Valida Certidao Isen��o
  */

if(isset($j18_templatecertidaoisencao) && $j18_templatecertidaoisencao != ""){

  $rsIsencao = $cldb_documentotemplate->sql_record($cldb_documentotemplate->sql_query_file(null,'*',null,"db82_sequencial = {$j18_templatecertidaoisencao} and db82_templatetipo = 44"));

  if ($cldb_documentotemplate->numrows == 0) {
    throw new Exception("Campo Documento Template Isen��o Inexistente. Altera��o abortada.");
  }
}

if(isset( $alterar )){

  db_inicio_transacao();
  $clcfiptu->alterar($j18_anousu);

  db_fim_transacao();

} else if ( isset($incluir) ) {

  db_inicio_transacao();

  $clcfiptu->incluir($j18_anousu);

  db_fim_transacao();

} else {

	$db_botao = true;
	$result   = $clcfiptu->sql_record($clcfiptu->sql_query_param(db_getsession('DB_anousu')));
	if($result!=false && $clcfiptu->numrows>0){
	  db_fieldsmemory($result,0);
	  $utilizadocpadrao = ($j18_templatecertidaoisencao==""?"0":"1");
	} else {
		$db_opcao = 1;
	}

}

if (isset($importar)) {

	$iAnoAnt  = ($j18_anousu-1);
  $rsCfiptu = $clcfiptu->sql_record($clcfiptu->sql_query_param($iAnoAnt));

  if ($rsCfiptu != false && $clcfiptu->numrows > 0) {
    db_fieldsmemory($rsCfiptu, 0);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1; js_template(<?=$utilizadocpadrao?>);" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <center>
      <?
        require_once("forms/db_frmcfiptu.php");
      ?>
    </center>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar) || isset($incluir)){
  if($clcfiptu->erro_status=="0"){
    $clcfiptu->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfiptu->erro_campo!=""){
      echo "<script> document.form1.".$clcfiptu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfiptu->erro_campo.".focus();</script>";
    }
  }else{
    $clcfiptu->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}

}catch(Exception $oErro) {

  db_msgbox($oErro->getMessage());
  db_redireciona('cad1_cfiptu002.php');
  db_fim_transacao(true);

}
?>
