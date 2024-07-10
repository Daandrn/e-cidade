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
//MODULO: material
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matrequi_classe.php");
include("classes/db_atendrequi_classe.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatrequi = new cl_matrequi;
$clatendrequi = new cl_atendrequi;
$clmatrequiitem = new cl_matrequiitem;
$cldb_depart = new cl_db_depart;
$cldb_usuarios = new cl_db_usuarios;
$clmatrequi->rotulo->label();
$clatendrequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
$db_opcao=3;
if (isset($codigo)&&$codigo!=""){
  $result_atendrequi=$clatendrequi->sql_record($clatendrequi->sql_query_requi($codigo,"distinct atendrequi.*,descrdepto,nome,m40_codigo"));
  if ($clatendrequi->numrows!=0){
    db_fieldsmemory($result_atendrequi,0);
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
<script>
function js_matrequi(codigo){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_requi','mat3_consultarequi001.php?codigo='+codigo,'Consulta Requisi��o',true);
}
</script>
<style>
<?//$cor="#999999"?>
.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr>
<td  align="center" valign="top" >
  <form name="form1" method="post" action="">
  <center>
  <table border="0">
    <tr>
    <td nowrap title="<?=@$Tm42_codigo?>">
      <b>C�digo: </b>
      <?//=@$Lm40_codigo?>
    </td>
    <td>
<?
db_input('m42_codigo',10,$Im42_codigo,true,'text',3,"");

?>
     <b>
	     <?db_ancora('Requisi��o:',"js_matrequi($m40_codigo);",1);?>:
     </b>
<?
db_input('m40_codigo',10,$Im40_codigo,true,'text',3,"")
?>

    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm42_depto?>">
       <?
       db_ancora(@$Lm42_depto,"",3);
       ?>
    </td>
    <td>
<?
db_input('m42_depto',10,$Im42_depto,true,'text',3,"")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm42_login?>">
       <?
       db_ancora(@$Lm42_login,"",3);
       ?>
    </td>
    <td>
<?
db_input('m42_login',10,$Im42_login,true,'text',3,"")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm42_data?>">
       <?=@$Lm42_data?>
    </td>
    <td nowrap title="<?=@$Tm42_hora?>">
<?
db_inputdata('m42_data',@$m42_data_dia,@$m42_data_mes,@$m42_data_ano,true,'text',3,"")
?>
       <?=@$Lm42_hora?>
<?
db_input('m42_hora',5,$Im42_hora,true,'text',3,"")
?>
    </td>
  </tr>
  </table>
  <table>
    <tr>
      <td align=center>
       <iframe name="atendrequiitem" id="atendrequiitem" src="mat3_conatendrequiiframe001.php?codigo=<?=$codigo?>" width="720" height="150" marginwidth="0" marginheight="0" frameborder="0">
       </iframe>
       <br>
       <br>
<!--       <input type=button value=Voltar onclick='parent.db_iframe_atendrequi.hide();';> -->
      </td>
    </tr>
  </table>
  </center>
</form>
</td>
</tr>
</table>
<script>
</script>
</body>
</html>
