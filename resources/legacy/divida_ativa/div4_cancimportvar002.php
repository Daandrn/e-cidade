<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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
include("classes/db_arretipo_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrecant_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_arreold_classe.php");
include("classes/db_divida_classe.php");
include("classes/db_divold_classe.php");
include("classes/db_issvardiv_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
require_once("model/cancelamentoDebitos.model.php");
require_once("libs/db_sql.php");
db_postmemory($HTTP_POST_VARS);
$oCancelaDebito     = new cancelamentoDebitos();
$cliframe_seleciona = new cl_iframe_seleciona;
$clarretipo         = new cl_arretipo;
$clarrecad          = new cl_arrecad;
$clarrecant         = new cl_arrecant;
$clarrehist         = new cl_arrehist;
$clarreold          = new cl_arreold;
$cldivold           = new cl_divold;
$cldivida           = new cl_divida;
$clissvardiv        = new cl_issvardiv;
$clrotulo           = new rotulocampo;
$sqlerro            = false;
$clarrecad->rotulo->label();
$clrotulo->label("k00_tipo");
if (isset($cancelar)&&isset($chaves)&&$chaves!=""){
  $numpre     = "";
  $numpre_ant = "teste";
  $numpar     = "";
  $receita    = "";
  $vir        = "";
  $info       = explode('#',$chaves);
  db_inicio_transacao();
  $aDebitos = array();
  for ( $w=0; $w < count($info); $w++ ) {

    $dados          = explode('-',$info[$w]);
    $numpre         = $dados[0];
    $numpar         = $dados[1];
    $receita        = $dados[2];
    $result_arrediv = $clarrecad->sql_record($clarrecad->sql_query_div(null,"distinct arrecad.*,divida.v01_coddiv",null,"
                                                                arrecad.k00_numpre    = $numpre and
                                                                arrecad.k00_numpar    = $numpar and
                                                                arrecad.k00_receit    = $receita and
                                                                arreinstit.k00_instit = ".db_getsession('DB_instit') ));
    $nurows_arrediv = $clarrecad->numrows;
    for ($y=0; $y < $nurows_arrediv; $y++) {

      db_fieldsmemory($result_arrediv,$y);
      $coddiv    = $v01_coddiv;


       //Criar um array com as informa��es de cada d�bito
	    $aDadosDebitos = array();
	    $aDadosDebitos['Numpre']  = $numpre;
	    $aDadosDebitos['Numpar']  = $numpar;
	    $aDadosDebitos['Receita'] = $receita;
	    $aDebitos[] = $aDadosDebitos;


      $result_divold  = $cldivold->sql_record($cldivold->sql_query_old(null,"divold.*",null,"k10_coddiv = $v01_coddiv"));
      $numrows_divold = $cldivold->numrows;
      for($i=0;$i<$numrows_divold;$i++){
	     db_fieldsmemory($result_divold,$i);
	     $result_arreold  = $clarreold->sql_record($clarreold->sql_query_file(null,'*',null,"k00_numpre = $k10_numpre and
	                                                                                         k00_numpar = $k10_numpar and
	                                                                                         k00_receit = $k10_receita"));
	     $numrows_arreold = $clarreold->numrows;
	     for($z=0;$z<$numrows_arreold;$z++){
	        db_fieldsmemory($result_arreold,$z);
	        $clarrecad->k00_numpre = $k00_numpre;
	        $clarrecad->k00_numpar = $k00_numpar;
	        $clarrecad->k00_numcgm = $k00_numcgm;
	        $clarrecad->k00_dtoper = $k00_dtoper;
	        $clarrecad->k00_receit = $k00_receit;
	        $clarrecad->k00_hist   = $k00_hist  ;
	        $clarrecad->k00_valor  = $k00_valor ;
	        $clarrecad->k00_dtvenc = $k00_dtvenc;
	        $clarrecad->k00_numtot = $k00_numtot;
	        $clarrecad->k00_numdig = $k00_numdig;
	        $clarrecad->k00_tipo   = $k00_tipo  ;
	        $clarrecad->k00_tipojm = "$k00_tipojm";
	        $clarrecad->incluir();
	        if($clarrecad->erro_status==0){
	          $sqlerro=true;
	          $erro_msg = $clarrecad->erro_msg."--- Inclus�o Arrecad";
	          break;
	        }
	     }
	     $clissvardiv->excluir($k10_coddiv);
	     if($clissvardiv->erro_status=='0'){
	        $sqlerro=true;
	        $erro_msg = $clissvardiv->erro_msg;
	        break;
	     }
	     $cldivold->excluir(null,"k10_coddiv  = $k10_coddiv and
	                              k10_numpre  = $k10_numpre and
	                              k10_numpar  = $k10_numpar and
	                              k10_receita = $k10_receita");
	     if($cldivold->erro_status==0){
	        $erro_msg = $cldivold->erro_msg."--- Exclus�o divold";
	        $sqlerro=true;
	        break;
	     }

	      $clarreold->excluir_where("k00_numpre=$k10_numpre and k00_numpar=$k10_numpar and k00_receit=$k10_receita");
	      if($clarreold->erro_status==0){
	        $erro_msg = $clarreold->erro_msg."--- Exclus�o arreold";
	        $sqlerro=true;
	        break;
	      }
      }
    }
  }

  if ( count($aDebitos) > 0 ) {
    try {

      $oCancelaDebito->setArreHistTXT("CANCELAMENTO DE IMPORTA��O DE ISSQN VARI�VEL");
      $oCancelaDebito->setTipoCancelamento(2);
      $oCancelaDebito->setCadAcao(5);
      $oCancelaDebito->geraCancelamento($aDebitos);

    } catch (Exception $eException) {
      $erro_msg = $eException->getMessage();
      $sqlerro  = true;
    }
  }

  db_fim_transacao($sqlerro);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_passainfo(valor){
  document.form1.controle.value=valor;
  document.form1.submit();
}
function js_submit_form(){
  js_gera_chaves();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<center>
<form name="form1" method="post">
<table border='0'>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <?
  $inner_arrecad = "";
  $inner_tipo = "";
  $inner = "";
  $where = "";
  if (isset($q02_inscr)&&$q02_inscr!=""){
    $inner_arrecad = " inner join arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
		                   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
											                      and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
    $inner = " inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre ";
  	$where = " and arreinscr.k00_inscr = $q02_inscr ";
    $tab = " arreinscr  ";
  }
  ?>
  <tr>
    <td colspan=2>
    <?
    $campos = " distinct divida.v01_exerc,arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit,k02_descr,arrecad.k00_dtvenc ";
    $sql_numpres = "select $campos
	         		from $tab
						$inner_arrecad
	        			inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo
            			inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo
						inner join tabrec on tabrec.k02_codigo = arrecad.k00_receit
						inner join divida on v01_numpre=arrecad.k00_numpre
						                 and v01_numpar=arrecad.k00_numpar
						                 and v01_instit = ".db_getsession('DB_instit')."
						inner join divold on k10_coddiv = v01_coddiv
		    			inner join arreold on arreold.k00_numpre = k10_numpre
		    			                  and arreold.k00_numpar = k10_numpar
		    			                  and arreold.k00_receit = k10_receita
		    			inner join arretipo arretipoold on arretipoold.k00_tipo = arreold.k00_tipo
             		where arretipoold.k03_tipo = 3
             		  and arretipo.k03_tipo = 5
             		      $where
		     		order by arrecad.k00_numpre,arrecad.k00_numpar";

    $cliframe_seleciona->campos  = "v01_exerc,k00_numpre,k00_numpar,k00_receit,k02_descr,k00_dtvenc";
    $cliframe_seleciona->legenda="Numpre's";
    $cliframe_seleciona->sql=$sql_numpres;
    //$cliframe_seleciona->sql_marca=$sql_marca;
    $cliframe_seleciona->iframe_height ="250";
    $cliframe_seleciona->iframe_width ="500";
    $cliframe_seleciona->iframe_nome ="numpres";
    $cliframe_seleciona->chaves = "k00_numpre,k00_numpar,k00_receit";
    $cliframe_seleciona->iframe_seleciona(1);
    ?>
    </td>
  </tr>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    <input name="cancelar" type="submit"  value="Cancelar" onclick="js_submit_form();">
    </td>
  </tr>
</table>
<?
db_input('q02_inscr',10,'',true,'hidden',3);
db_input('inner',10,'',true,'hidden',3);
db_input('where',10,'',true,'hidden',3);
?>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
/*
function js_mandadados(tipor,tipdes,inner,where){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','div4_importadivida033.php?k00_tipo_or='+tipor+'&k00_tipo_des='+tipdes+'&txt_where='+where+'&txt_inner='+inner,'Pesquisa',true);
    jan.moveTo(0,0);
}
*/
</script>
<?
if (isset($cancelar)){
  if ($sqlerro==false){
    db_msgbox("Cancelamento Efetuado!!");
    echo "<script>location.href='div4_cancimportvar001.php'</script>";
  }else{
    db_msgbox("Cancelamento n�o efetuado!!");
    db_msgbox($erro_msg);
  }
}
?>
