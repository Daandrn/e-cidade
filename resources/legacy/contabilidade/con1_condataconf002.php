<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_condataconf_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clcondataconf = new cl_condataconf;

$db_opcao = 22;
$db_botao = false;

if(isset($c99_alterar2)){

    try {
        db_inicio_transacao();

        $rsConDataConf = $clcondataconf->sql_record($clcondataconf->sql_query_file($c99_anousu,$c99_instit));
        $clcondataconf->c99_usuario = db_getsession("DB_id_usuario");

        //  Se n�o houver um registro criado
        if($rsConDataConf == false || $clcondataconf->numrows==0) {

            if (empty($c99_datapat)) {
                throw new Exception($c99_datapat."Para encerramento do per�odo patrimonial, informe a data limite!");
            }
            $clcondataconf->incluirPeriodoPatrimonial($c99_anousu,$c99_instit, $c99_datapat);
            $sMsg = "Inclus�o do encerramento do per�odo patrimonial realizada com sucesso!";

        }else{

            $sMsg = empty($c99_datapat) ? "Exclus�o do encerramento do per�odo patrimonial realizada com sucesso!" :
                "Altera��o do encerramento do per�odo patrimonial realizada com sucesso!";
            if(empty($c99_data) && empty($c99_datapat)){
                $clcondataconf->excluirPeriodoPatrimonial($c99_anousu,$c99_instit);
            }else{
                $clcondataconf->alterarPeriodoPatrimonial($c99_anousu,$c99_instit, $c99_datapat);
            }

        }

        if ($clcondataconf->erro_status == 0) {
            throw new Exception($clcondataconf->erro_msg);
        }

        db_fim_transacao(false);
        db_msgbox($sMsg);

    } catch (Exception $oException) {

        db_msgbox($oException->getMessage());
        db_fim_transacao(true);

    }
}else{
    if (isset($alterar)) {

        try {
            db_inicio_transacao();

            $rsConDataConf = $clcondataconf->sql_record($clcondataconf->sql_query_file($c99_anousu,$c99_instit));
            $clcondataconf->c99_usuario = db_getsession("DB_id_usuario");

            if($rsConDataConf == false || $clcondataconf->numrows==0) {

                if (empty($c99_data)) {
                    throw new Exception("Para encerramento do per�odo cont�bil, informe a data limite!");
                }
                $clcondataconf->incluir($c99_anousu,$c99_instit);
                $sMsg = "Inclus�o do encerramento do per�odo cont�bil realizada com sucesso!";

            } else {

                if(!empty($c99_data)) {

                    $clcondataconf->alterar($c99_anousu,$c99_instit);
                    $sMsg = "Altera��o do encerramento do per�odo cont�bil realizada com sucesso!";

                } else {

                    if(!empty($c99_datapat)){

                        $c99_data = "";
                        $clcondataconf->alterar($c99_anousu,$c99_instit);
                        $sMsg = "Exclus�o do encerramento do per�odo cont�bil realizada com sucesso!";

                    }else{

                        $clcondataconf->excluir($c99_anousu,$c99_instit,$c99_datapat);
                        $sMsg = "Exclus�o do encerramento do per�odo cont�bil realizada com sucesso!";

                    }

                }

            }

            if ($clcondataconf->erro_status == 0) {
                throw new Exception($clcondataconf->erro_msg);
            }

            db_fim_transacao(false);
            db_msgbox($sMsg);

        } catch (Exception $oException) {

            db_msgbox($oException->getMessage());
            db_fim_transacao(true);

        }

    }
}

$db_opcao = 2;
$result = $clcondataconf->sql_record($clcondataconf->sql_query_file(db_getsession("DB_anousu"),db_getsession("DB_instit")));
if($result!=false && $clcondataconf->numrows>0){
  db_fieldsmemory($result,0);
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
    if (db_getsession("DB_login") == 'dbseller') {

        echo "<br><center><br><H2>Esta rotina n�o pode ser acessada pelo usu�rio dbseller.</h2></center>";
    } else {
      
	   include("forms/db_frmcondataconf.php");
    }      
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
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>