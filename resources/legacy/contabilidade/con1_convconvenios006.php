<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_convconvenios_classe.php");
include("classes/db_convdetalhaconcedentes_classe.php");
include("classes/db_placaixarec_classe.php");
$clconvconvenios = new cl_convconvenios;
$clconvdetalhaconcedentes = new cl_convdetalhaconcedentes;
$clplacaixarec = new cl_placaixarec;

db_postmemory($HTTP_POST_VARS);
   $db_opcao = 33;
$db_botao = false;

if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();

  $arrecadacoes_vinculadas = $clplacaixarec->validateArrecadacoesByConvenio($c206_sequencial);

  if(!empty($arrecadacoes_vinculadas)) {
    $clconvconvenios->erro_msg = 'N�o � poss�vel excluir o conv�nio. Conv�nio vinculado � arrecada��o de receita.';
    $clconvconvenios->erro_campo = 1;
    $sqlerro=true;
  }

  if(empty($arrecadacoes_vinculadas)) {

    $clconvdetalhaconcedentes->excluir(null, 'c207_codconvenio = '. $c206_sequencial);
    if($clconvdetalhaconcedentes->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clconvdetalhaconcedentes->erro_msg;
    $clconvconvenios->excluir($c206_sequencial);
    if($clconvconvenios->erro_status==0){
      $sqlerro=true;
    }
  }

  $erro_msg = $clconvconvenios->erro_msg;
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clconvconvenios->sql_record($clconvconvenios->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmconvconvenios.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clconvconvenios->erro_campo!=""){
      echo "<script> document.form1.".$clconvconvenios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconvconvenios->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='con1_convconvenios003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.convdetalhaconcedentes.disabled=false;
         parent.document.formaba.convdetalhatermos.disabled=false;
         CurrentWindow.corpo.iframe_convdetalhaconcedentes.location.href='con1_convdetalhaconcedentes001.php?db_opcaoal=33&c207_codconvenio=".@$c206_sequencial."';
         CurrentWindow.corpo.iframe_convdetalhatermos.location.href='con1_convdetalhatermos001.php?db_opcaoal=33&c208_codconvenio=".@$c206_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('convdetalhaconcedentes');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
