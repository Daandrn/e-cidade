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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tfd_tipotratamento_classe.php");
require_once("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cltfd_tipotratamento = new cl_tfd_tipotratamento;

$db_opcao = 22;
$db_botao = false;

if(isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $cltfd_tipotratamento->alterar($tf04_i_codigo);
  db_fim_transacao($cltfd_tipotratamento->erro_status == '0' ? true : false);

} else if(isset($chavepesquisa)) {

   $db_opcao = 2;
   $result = $cltfd_tipotratamento->sql_record($cltfd_tipotratamento->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;

?>
     <script>

       parent.document.formaba.a2.disabled = false;
       parent.document.formaba.a3.disabled = false;
       (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href='tfd1_tfd_tipotratamentoproced001.php?tf05_i_tipotratamento=<?=$chavepesquisa?>&tf04_c_descr=<?=@$tf04_c_descr?>';
       (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href='tfd1_tfd_tipotratamentodoc001.php?tf06_i_tipotratamento=<?=$chavepesquisa?>&tf04_c_descr=<?=@$tf04_c_descr?>';

     </script>
<?
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <fieldset style='width: 75%;'> <legend><b>Tratamento</b></legend>
	      <?
      	require_once("forms/db_frmtfd_tipotratamento.php");
        ?>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if(isset($alterar)){
  if($cltfd_tipotratamento->erro_status=="0"){
    $cltfd_tipotratamento->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltfd_tipotratamento->erro_campo!=""){
      echo "<script> document.form1.".$cltfd_tipotratamento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltfd_tipotratamento->erro_campo.".focus();</script>";
    }
  }else{
    $cltfd_tipotratamento->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","tf04_c_descr",true,1,"tf04_c_descr",true);
</script>
