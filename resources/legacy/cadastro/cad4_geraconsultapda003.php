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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_pesquisa_edi(codimp,matric,codigo){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_lev','cad4_geraconsultapda004.php?codimp='+codimp+'&matric='+matric+'&codigo='+codigo,'Pesquisa Logradouros/Edificações',true);
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<br><br>
<?
$sql = "select j98_sequen ,
               j98_codimporta,
               j98_matric     ,
               j98_codigo     ,
               j14_nome       ,
               j98_testada    ,
               j98_pavim      ,
               j98_agua       ,
               j98_esgoto     ,
               j98_eletrica   ,
               j98_meiofio    ,
               j98_iluminacao ,
               j98_telefonia  ,
               j98_lixo
        from moblevantamentolog
                      inner join ruas on j98_codigo = j14_codigo
        where j98_codimporta = $codimp and j98_matric = $matric";

$jsfuncao='js_pesquisa_edi|j98_codimporta|j98_matric|j98_codigo';
db_lovrot($sql,15,'()','',$jsfuncao);

?>
</center>
</body>
</html>
