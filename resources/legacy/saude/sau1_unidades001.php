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
include("classes/db_unidades_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clunidades = new cl_unidades;
$db_opcao = 1;
$db_opcao1 = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $clunidades->sd02_i_tipounidade = $sd02_i_tipounidade;
 $clunidades->incluir($sd02_i_codigo);
 db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
    <?include("forms/db_frmunidades.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd02_v_cnes",true,1,"sd02_v_cnes",true);
</script>
<?
if(isset($incluir)){
 if($clunidades->erro_status=="0"){
  $clunidades->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clunidades->erro_campo!=""){
   echo "<script> document.form1.".$clunidades->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clunidades->erro_campo.".focus();</script>";
  }
  if($sd02_i_numcgm!=""){
   ?><script>iframe_dados.location.href = "sau1_unidades004.php?chavepesquisa=<?=$sd02_i_numcgm?>";</script><?
  }
 }else{
  $clunidades->erro(true,false);
  db_redireciona("sau1_unidades002.php?chavepesquisa=$sd02_i_codigo");
 }
}
?>
