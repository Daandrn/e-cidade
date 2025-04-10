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
include("classes/db_orcppa_classe.php");
include("classes/db_orcppaval_classe.php");

$clorcppa = new cl_orcppa;
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcdotacaocontr_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_orcfuncao_classe.php");
include("classes/db_orcsubfuncao_classe.php");
include("classes/db_orcprograma_classe.php");
include("classes/db_orcprojativ_classe.php");
include("classes/db_orcproduto_classe.php");
include("classes/db_orcppalei_classe.php");

$clorcprojativ = new cl_orcprojativ;
$clorcdotacao = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcfuncao = new cl_orcfuncao;
$clorcsubfuncao = new cl_orcsubfuncao;
$clorcprograma = new cl_orcprograma;
$clorcproduto = new cl_orcproduto;
$clorcppalei = new cl_orcppalei;


  /*
$clorcppaval = new cl_orcppaval;
  */
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

if(isset($incluir) || isset($alterar)){
  $result=$clorcppalei->sql_record($clorcppalei->sql_query_file($o23_codleippa,"o21_anoini"));
  db_fieldsmemory($result,0);

  $dbwhere  = " o23_codleippa   =  $o23_codleippa and o23_anoexe = $o21_anoini and o23_orgao = $o23_orgao ";
  $dbwhere .= " and o23_unidade = $o23_unidade    and o23_funcao = $o23_funcao and o23_subfuncao=$o23_subfuncao ";
  $dbwhere .= " and o23_programa= $o23_programa   and o23_acao   = $o23_acao";
}

if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clorcppa->sql_record($clorcppa->sql_query_file(null,"o23_codppa","",$dbwhere));
  if($clorcppa->numrows>1){
    $sqlerro  = true;
    $erro_msg = "J� existe...";
    $jaexiste=true;
  }
  if($sqlerro == false){
    $clorcppa->alterar($o23_codppa);
    if($clorcppa->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clorcppa->erro_msg;
  }
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}elseif(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();



  $clorcppa->sql_record($clorcppa->sql_query_file(null,"o23_codppa","",$dbwhere));
  if($clorcppa->numrows>0){
    $sqlerro  = true;
    $erro_msg = "J� existe...";
    $jaexiste=true;
  }
  if($sqlerro == false){
    $clorcppa->o23_anoexe = $o21_anoini;
    $clorcppa->incluir(null);
    $erro_msg = $clorcppa->erro_msg;
    if($clorcppa->erro_status==0){
      $sqlerro=true;
    }else{

   $o23_codppa= $clorcppa->o23_codppa;
    }
  }
  db_fim_transacao($sqlerro);
   $db_opcao = 1;
   $db_botao = true;
}else if(isset($chavepesquisa)|| (isset($chave_nova) && $chave_nova != '')){
   if(isset($chave_nova)){
     $db_opcao = 1;
     $o23_codppa='';
     $chavepesquisa = $chave_nova;
   }else{
     $db_opcao = 2;
     $db_botao = true;
   }
   $result = $clorcppa->sql_record($clorcppa->sql_query_compl($chavepesquisa));
   db_fieldsmemory($result,0);
   if(isset($chave_nova)){
        unset($o23_codppa);
   }
}else{
  if(isset($o23_codppa) && $o23_codppa !=''){
    $db_opcao = 2;
  }else{
    $db_opcao = 1;
  }
}
$db_botao = true;
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmorcppa.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }else{
    //db_msgbox($erro_msg);
    if(isset($incluir)){
      db_redireciona("orc1_orcppa004.php?liberaaba=true&chavepesquisa=$o23_codppa");
    }
  }
}
if(isset($chavepesquisa) && empty($chave_nova)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.orcppaval.disabled=false;
         CurrentWindow.corpo.iframe_orcppaval.location.href='orc1_orcppaval001.php?o24_codppa=".@$o23_codppa."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('orcppaval');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
 if(isset($jaexiste)){
    echo "<script>document.form1.consultar.click();</script>";
 }

?>
