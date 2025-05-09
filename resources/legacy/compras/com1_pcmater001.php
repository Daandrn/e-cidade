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
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("classes/db_pcgrupo_classe.php");
include("classes/db_pcsubgrupo_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_condataconf_classe.php");
require_once("classes/db_historicomaterial_classe.php");
require_once 'libs/renderComponents/index.php';
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$clcondataconf = new cl_condataconf;
$db_opcao = 1;
$db_botao = true;
if (((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir")) {
    db_inicio_transacao();
    $pc01_data = implode('-', array_reverse(explode('/', $pc01_data)));

    $sqlerro = false;
    $clpcmater->pc01_libaut = @$pc01_libaut;
    $clpcmater->pc01_ativo = "false";
    $clpcmater->pc01_conversao = "false";
    $clpcmater->pc01_data = $pc01_data;
    /*OC3770*/
    $clpcmater->pc01_tabela = $pc01_tabela;
    $clpcmater->pc01_taxa   = $pc01_taxa;
    $clpcmater->pc01_regimobiliario   = $pc01_regimobiliario;
    $clpcmater->pc01_unid   = $pc01_unid;

    /*FIM - OC3770*/
    if (!empty($pc01_data)) {
        $anousu = db_getsession('DB_anousu');
        $instituicao = db_getsession('DB_instit');
        $result = $clcondataconf->sql_record($clcondataconf->sql_query_file($anousu, $instituicao, "c99_datapat", null, null));
        $c99_datapat = db_utils::fieldsMemory($result, 0)->c99_datapat;

        if (strtotime($pc01_data) <= strtotime($c99_datapat)) {
            $erro_msg = "O per�odo j� foi encerrado para envio do SICOM. Verifique os dados do lan�amento e entre em contato com o suporte.";
            $sqlerro  = true;
            db_msgbox($erro_msg);
        }
    }

    if ($pc01_obras == "0") {
        $erro_msg = "Campo  Material Utilizado em Obras/servi�os?  nao informado.";
        $sqlerro  = true;
        db_msgbox($erro_msg);
    }

    if ($sqlerro == false) {
        $clpcmater->incluir(null);
    }

    if ($clpcmater->erro_status == 0) {
        $sqlerro = true;
    } else {
        $codmater =  $clpcmater->pc01_codmater;
    }

    if($sqlerro == false){
        $clhistoricomaterial = new cl_historicomaterial;
        $rsHistoricoMaterial = $clhistoricomaterial->sql_record(
            $clhistoricomaterial->sql_query(
                null,
                "*",
                null,
                "db150_coditem = $clpcmater->pc01_codmater"
            )
        );
        
        if(pg_num_rows($rsHistoricoMaterial) == 0){
            //inserir na tabela historico material
            $clhistoricomaterial->db150_tiporegistro           = 10;
            $clhistoricomaterial->db150_coditem                = $clpcmater->pc01_codmater;
            $clhistoricomaterial->db150_pcmater                = $clpcmater->pc01_codmater;
            $clhistoricomaterial->db150_dscitem                = substr($clpcmater->pc01_descrmater.'-'.$clpcmater->pc01_complmater,0,999);
            $clhistoricomaterial->db150_unidademedida          = null;
            $clhistoricomaterial->db150_tipocadastro           = 1;
            $clhistoricomaterial->db150_justificativaalteracao = '';
            $clhistoricomaterial->db150_mes                    = date("m", db_getsession("DB_datausu"));
            $clhistoricomaterial->db150_data                   = date("Y-m-d", db_getsession("DB_datausu"));
            $clhistoricomaterial->db150_instit                 = db_getsession('DB_instit');
            $clhistoricomaterial->db150_codunid                = $clpcmater->pc01_unid;
            $clhistoricomaterial->incluir(null);

            if ($clhistoricomaterial->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clhistoricomaterial->erro_msg;
            }
        }
    }
    // echo '<pre>';
    // print_r($erro_msg);
    // exit;
    if ($sqlerro == false) {
        $arr =  explode("XX", $codeles);
        for ($i = 0; $i < count($arr); $i++) {
            if ($sqlerro == false) {
                $elemento = $arr[$i];
                if (trim($elemento) != "") {
                    $clpcmaterele->pc07_codmater = $codmater;
                    $clpcmaterele->pc07_codele = $elemento;
                    $clpcmaterele->incluir($codmater, $elemento);
                    if ($clpcmaterele->erro_status == 0) {
                        $sqlerro = true;
                        break;
                    }
                }
            }
        }
    }
    db_fim_transacao($sqlerro);
}
if (isset($impmater)) {

    $sCampos        = "pc01_descrmater,pc01_complmater,pc01_codsubgrupo,pc01_ativo,pc01_conversao,";
    $sCampos       .= "pc03_codgrupo as pc01_codgrupo,pc01_libaut,pc01_servico,pc01_obras";
    $sSqlPcMater    = $clpcmater->sql_query($impmater, $sCampos, null, "");
    $result_pcmater = $clpcmater->sql_record($sSqlPcMater);
    if ($clpcmater->numrows > 0) {
        db_fieldsmemory($result_pcmater, 0);
    }
}
?>

<script type="text/javascript" defer>
  loadComponents(['buttonsSolid']);
</script>

<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?php db_app::load("estilos.bootstrap.css");?>
    <script>
        function js_iniciar() {
            if (document.form1)
                document.form1.pc01_descrmater.focus();
        }
    </script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr>
            <td width="360" height="18">&nbsp;</td>
            <td width="263">&nbsp;</td>
            <td width="25">&nbsp;</td>
            <td width="140">&nbsp;</td>
        </tr>
    </table>
    <center>
        <table width="920" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
                    <?
                    include("forms/db_frmpcmater.php");
                    ?>
                </td>
            </tr>
        </table>
    </center>
    <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
    ?>
</body>

</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {
    if ($clpcmater->erro_status == "0") {
        $clpcmater->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clpcmater->erro_campo != "") {
            echo "<script> document.form1." . $clpcmater->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clpcmater->erro_campo . ".focus();</script>";
        };
    } else {
        $clpcmater->erro(true, true);
    };
};
?>
<?
if ($db_opcao == "3") {
    echo "
  if(pcmater0011.document.form1.nexclui.value=='T'){
    document.form1.db_opcao.disabled=true;
  }else{
    document.form1.db_opcao.disabled=false;
  }
  ";
} else if ($db_opcao == "1" && !(isset($HTTP_POST_VARS["db_opcao"])) && !isset($impmater) && !isset($codigomater) && !isset($pc01_codmater)) {
    echo "
  <script>
    document.form1.importar.click();
  </script>
  ";
}
?>