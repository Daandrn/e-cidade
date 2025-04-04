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
include("libs/db_liborcamento.php");
include("classes/db_db_config_classe.php");
include("classes/db_db_userinst_classe.php");

$cl_db_userinst = new cl_db_userinst;
$cldb_config = new cl_db_config;

$cldb_config->rotulo->label("codigo");
$cldb_config->rotulo->label("nomeinst");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script>
    function js_marca(codigo){

      obj = document.form1;
      obj.db_selinstit.value = "";
      separa = "";
      for(i=0;i<obj.length;i++){
        if(obj.elements[i].name != "marcardesmarcar") {
          if(obj.elements[i].checked){
            obj.db_selinstit.value = obj.db_selinstit.value +separa+ obj.elements[i].value;
            separa = "-";
          }
        }
      }
    }

variavel = 1;
function js_emite(){

 // pega dados da func_selorcdotacao_aba.php
 document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
 jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 document.form1.target = 'safo' + variavel++;
 setTimeout("document.form1.submit()",1000);
 return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

  <table align="center" cellspacing="0" bgcolor="#CCCCCC" border="1" marginwidth="0">
    <form name="form1" method="post" action="orc2_reldespesas002txt.php">

      <?
      // selecina as institui��es que o usuario tem liberadas
      $vetor_instit = array();
      $resit  = $cl_db_userinst->sql_record($cl_db_userinst->sql_query_file(null, null,"id_instit",null,"id_usuario=".db_getsession("DB_id_usuario")));
      if ($cl_db_userinst->numrows > 0) {
        for($x=0;$x<$cl_db_userinst->numrows;$x++){
          db_fieldsmemory($resit,$x);
          $vetor_instit[] = $id_instit;
        }
      }
      $result = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo,nomeinst,prefeitura","codigo"));
      if($result==false || $cldb_config->numrows==0){
        ?>
        <tr><td align="center" valign="top"> Sem Cadastro de Institui��o </td></tr>
        <?
      }else{
        ?>
        <tr>
          <td></td>
          <td align="center"><b>Institui��es</b></td>
        </tr>
        <?
        for($i=0;$i<$cldb_config->numrows;$i++){
          db_fieldsmemory($result,$i);

          // a linha abaixo faz a impressao somente das institui��es autorizadas ao usuario
          if (array_search($codigo,$vetor_instit)===FALSE){
            // institui��o listada nao encontrada nas permissoes do usuario
            continue;

          }

          ?>
          <tr>
            <td align="left" > <input name='cod_<?=$codigo?>' type='checkbox' onclick='js_marca("<?=$codigo?>");<?=($funcao==''?'':$funcao.'();')?>' value='<?=$codigo?>'></td>
            <td align="left" > <strong><?=$nomeinst?></strong></td>
          </tr>
          <?
          if($prefeitura=='t'){
            echo "<script>document.form1.cod_$codigo.click();</script>";
          }
        }
      }
      ?>

    </table>
    <table align="center">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="right" ><strong>N�vel :</strong></td>
        <td>
	  <?
	     $xy = array('8A'=>'Recurso At� o N�vel','1A'=>'�rg�o At� o N�vel','1B'=>'�rg�o s� o N�vel','2A'=>'Unidade At� o N�vel','2B'=>'Unidade s� o N�vel','3A'=>'Fun��o At� o N�vel','3B'=>'Fun��o s� o N�vel','4A'=>'Subfun��o At� o N�vel','4B'=>'Subfun��o s� o N�vel','5A'=>'Programa At� o N�vel','5B'=>'Programa s� o N�vel','6A'=>'Proj/Ativ At� o N�vel','6B'=>'Proj/Ativ s� o N�vel','7A'=>'Elemento At� o N�vel','7B'=>'Elemento s� o N�vel','9A'=>'Recurso At� o N�vel - Completo','8B'=>'Recurso s� o N�vel');
	     db_select('nivel',$xy,true,2,"");
	     $db_selinstit = db_getsession("DB_instit");
       db_input("db_selinstit",10,0,true,"hidden",3);
	   ?>
        </td>
      </tr>

      <tr>
        <td align="right"><strong>Troca de P�gina por �rg�o:</strong>
	</td>

        <td >
	<?
	$x = array('N'=>'N�O','S'=>'SIM');
	db_select('quebra_orgao',$x,true,2,"");
	?>
	</td>
      </tr>
      <tr>
        <td align="right"><strong>Troca de P�gina por Unidade:</strong>
	</td>

        <td >
	<?
	$xx = array('N'=>'N�O','S'=>'SIM');
	db_select('quebra_unidade',$xx,true,2,"");
	?>
	</td>
      </tr>
      <tr>
        <?
        $sql = "select o50_subelem from orcparametro where o50_anousu = ".db_getsession("DB_anousu");
        $result1 = pg_exec($sql);
        $o50_subelem = pg_result($result1,0,0);
        if($o50_subelem=='f'){

          ?>

          <td align="right"><strong>Listar Sub-elementos:</strong>
  	  </td>
          <td >
	  <?
	  $xx = array('N'=>'N�O','S'=>'SIM');
	  db_select('lista_subeleme',$xx,true,2,"");
	  ?>
	  </td>
        <?
 	}else{
	?>
          <td align="right">
  	  </td>
          <td>
	  <?
	  global $lista_subeleme;
	  $lista_subeleme = 'N';
	  db_input("lista_subeleme",15,0,true,'hidden',3);
	  ?>
          </td>
	<?
	}
	?>
      </tr>


      <tr>
        <td align="right"><strong>Formato do arquivo: </strong>
	</td>

        <td >
	<?
	$xx = array('C'=>'CSV','P'=>'PDF');
	db_select('formato_arq',$xx,true,2,"");
	?>
  <?
  /*
 *  configura as datas default
 */
    $anousu  = db_getsession("DB_anousu");
    $dataini = date("m-d",db_getsession("DB_datausu"));
    $datafin = date("m-d",db_getsession("DB_datausu"));
    $dataini = $anousu."-".$dataini;
    $datafin = $anousu."-".$datafin;

    $dt = split('-',$dataini);
    $data_ini_dia   = $dt[2];
    $data_ini_mes = $dt[1];
    $data_ini_ano  = $dt[0];
    $dt = split('-',$datafin);
    $data_fin_dia   = $dt[2];
    $data_fin_mes = $dt[1];
    $data_fin_ano  = $dt[0];
  ?>
	</td>
      </tr>
      </tr>
     <tr>
    <td nowrap align="right"><b>  Per�odo inicial: </b></td>
      <td colspan="2">
         <? db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
      </td>
   </tr>

 <tr>
    <td nowrap align="right"><b>  Per�odo final: </b></td>
      <td colspan="2">
         <? db_inputdata('data_fin',@$data_fin_dia,@$data_fin_mes,@$data_fin_ano,true,'text',1);  ?>
      </td>
   </tr>

      <tr>
        <td colspan="2" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
          <input  name="orgaos" id="orgaos" type="hidden" value="" >
          <input  name="vernivel" id="vernivel" type="hidden" value="" >
          <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
        </td>
      </tr>

  </form>
</table>
</body>
</html>
