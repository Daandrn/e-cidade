<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pcgrupo_classe.php");
include("classes/db_pcsubgrupo_classe.php");
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $sqlerro = false;
  //rotina que exclui todos os registros do pcmaterele
  if($sqlerro == false){
       $clpcmaterele->pc07_codmater = $pc01_codmater;
       $clpcmaterele->excluir($pc01_codmater); 
       if($clpcmaterele->erro_status==0){
	 db_msgbox('erro');
         $sqlerro = true;
       }	 
  }
  
  $clpcmater->excluir($pc01_codmater);
  if($clpcmater->erro_status==0){
    $sqlerro = true;
  }

  db_fim_transacao($sqlerro);


  //
  $conectar = pg_connect("host=192.168.1.1 dbname=sam30 user=postgres");
  //
  $sql = "delete from mater where m01_codigo = lpad($pc01_codmater,7,0)";
  $result = pg_query($sql);
  //
  include("libs/db_conecta.php");
  
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clpcmater->sql_record($clpcmater->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $result = $clpcmaterele->sql_record($clpcmaterele->sql_query_file($chavepesquisa)); 
   $numrows = $clpcmaterele->numrows;
   $coluna =  '';
   $sep = '';
   for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
        $coluna .=  $sep.$pc07_codele;
	$sep     = "XX";
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpcmater.php");
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clpcmater->erro_status=="0"){
    $clpcmater->erro(true,false);
  }else{
    $clpcmater->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
