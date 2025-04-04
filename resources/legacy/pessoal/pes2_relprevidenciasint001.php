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
include("dbforms/db_funcoes.php");
include("classes/db_rhregime_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrhregime = new cl_rhregime;
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  jan = window.open('pes2_relprevidenciasint002.php?&ano='+document.form1.DBtxt23.value+
                                        '&prev='+document.form1.prev.value+
                                        '&folha='+document.form1.folha.value+
                                        '&vinculo='+document.form1.vinculo.value+
                                        '&sembase='+document.form1.sembase.value+
                                        '&ordem='+document.form1.ordem.value+
                                        '&campoextra='+document.form1.campoextra.value+
                                        '&mes='+document.form1.DBtxt25.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de compet�ncia" >
        <strong>Ano / M�s :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="left" nowrap title="Tabela de Previd�ncia" >
        <strong>Tabela de Previd�ncia :&nbsp;&nbsp;</strong>
        </td>
        <td>
         <?
	 $sql = "select distinct (cast(r33_codtab as integer) - 2) as r33_codtab, 
                                                     r33_nome 
           from inssirf 
           where r33_anousu = ".db_anofolha()." 
             and r33_mesusu = ".db_mesfolha()."
             and r33_codtab > 2 
             and r33_instit = ".db_getsession('DB_instit');
	 $res = pg_query($sql);
         db_selectrecord('prev', $res, true, 4);
         ?>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Tipo de Folha :</strong>
        </td>
        <td>
          <?
          $arr_folha = array("r14"=>"Sal�rio", "r35"=>"13o Sal�rio","r48"=>"Complementar","r20"=>"Rescis�o","todas"=>"Todas","todasexc13" => "Todas - Exceto 13o");
          db_select('folha',$arr_folha,true,4);
          ?>
       </td>
     </tr>
  <tr>
    <td align="right" nowrap title="Regime">
    <strong>V�nculo:</strong>
    </td>
    <td>
      <?
      $arr_vinculo = array("T"=>"Todos", "A"=>"Ativos", "I"=>"Inativos","P"=>"Pensionistas","IP"=>"Inativos/Pensionistas");
      db_select('vinculo', $arr_vinculo, true, 4);
      ?>
    </td>
  </tr>
      <tr>
        <td align="right"><strong>Ordem :</strong>
        </td>
        <td>
          <?
          $arr_ordem = array("A"=>"Alfab�tica", "N"=>"Num�rica");
          db_select('ordem',$arr_ordem,true,4);
          ?>
       </td>
     </tr>
      <tr>
        <td align="right"><strong>Imprimir Sem Base :</strong>
        </td>
        <td>
          <?
          $arr_sembase = array("N"=>"N�o", "S"=>"Sim");
          db_select('sembase',$arr_sembase,true,4);
          ?>
       </td>
     </tr>
     <tr>
        <td align="right">
           <strong>Al�quota complementar %:</strong>
        </td>
        <td align="left" >
          <?
          db_input('campoextra',4,4,true,'text',2,'');
          ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>