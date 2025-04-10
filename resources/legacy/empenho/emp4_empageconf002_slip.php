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

include("classes/db_pagordem_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagepag_classe.php");
include("classes/db_empageslip_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;
$clempageslip  = new cl_empageslip;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;


$clrotulo = new rotulocampo;
$clrotulo->label("e81_codmov");
$clrotulo->label("k17_codigo");
$clrotulo->label("k17_valor");
$clrotulo->label("k17_data");
$clrotulo->label("k17_debito");
$clrotulo->label("k17_credito");
$clrotulo->label("e40_descr");
$clrotulo->label("c60_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("e83_codtipo");


if(isset($movs)){
  $arr_movs = explode("XX",$movs);
  $arr_m = array();
  for($i=0; $i<count($arr_movs); $i++){
    $arr_m[$arr_movs[$i]] = $arr_movs[$i];
  }
}

$dbwhere = " e81_codage=$e80_codage  and e86_codmov is null and k17_instit = " . db_getsession("DB_instit");
$dbwhere .= " and e89_codmov is not null and c60_anousu=".db_getsession("DB_anousu");
$dbwhere .= " and e80_instit = " . db_getsession("DB_instit");

if(isset($e83_codtipo) && $e83_codtipo != '' ){
  $dbwhere .=" and e85_codtipo=$e83_codtipo ";
}

if(isset($z01_numcgm) && $z01_numcgm != '' ){
  $dbwhere .=" and z01_numcgm = $z01_numcgm ";
}

$sql     = $clempageslip->sql_query_descr(null,null,"e81_valor,e81_codmov,e83_descr,s.*,e40_descr,c60_descr,z01_nome,e89_codigo,e89_codmov",'',$dbwhere);
//die($sql);
$result  = $clempageslip->sql_record($sql); 
$numrows = $clempageslip->numrows; 
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   soma=new Number();
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            

       if(OBJ.elements[i].checked==true){
         valor = new Number(eval("document.form1.valor_"+OBJ.elements[i].value+".value"));
	 soma = new Number(soma+valor);
       }
     }
   }
   parent.document.form1.total.value = soma.toFixed(2); 
   return false;
}

function js_calcula(campo){
  total = new Number(parent.document.form1.total.value);
  valor = new Number(eval("document.form1.valor_"+campo.value+".value"));
  mov = campo.value;
  if(campo.checked==true){
    soma   = new Number(total+valor);
    
  }else{
    soma = new Number(total-valor);
  }
  parent.document.form1.total.value = soma.toFixed(2); 
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
<form name="form1" method="post" action="">
           <?
             db_input("cgm",10,'',true,'hidden',1);
            ?>       
    <center>
      <table  class='bordas'>
        <tr>
          <td class='bordas02' align='center'><a  title='Inverte Marca��o' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas02' align='center' ><small><b>Mov.</b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_codigo?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_credito?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_debito?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLc60_descr?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLe40_descr?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_data?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLk17_valor?></b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLe83_codtipo?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLz01_nome?></b></small></td>
	</tr>
        <?
	  $tot =0;
	  for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);


	    $x= "valor_$e81_codmov";
  	    $$x = $e81_valor;
            db_input("valor_$e81_codmov",10,'',true,'hidden',1);
	    
	    $xeque = '';
	    if (isset($movs) && array_key_exists($e81_codmov,$arr_m)) {
               $xeque = ' checked ';
	       $tot+=$e81_valor;
	    }
	?>
        <tr>
          <td class='bordas' align='right'><input value="<?=$e81_codmov?>" name="CHECK_<?=$e81_codmov?>" <?=$xeque?> onclick="js_calcula(this);" type='checkbox'></td>
          <td class='bordas' align='right'><small > <?=$e81_codmov?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_codigo?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_credito?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_debito?></small></td>
          <td class='bordas' align='left' nowrap ><small > <?=(substr($c60_descr,0,20))?></small></td>
          <td class='bordas' align='right' nowrap><small > <?=$e40_descr?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_data?></small></td>
          <td class='bordas' align='right'><small > <?=$k17_valor?></small></td>
          <td class='bordas' align='right' nowrap><small><?=$e83_descr?></small></td>
          <td class='bordas' align='left' nowrap><small >&nbsp; <?=$z01_nome?></small></td>
	</tr>
        <?
	  }
	?>
      </table>
    </center>
    </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  parent.document.form1.codtipo.value = '<?=$e83_codtipo?>';
  parent.document.form1.total.value = '<?=$tot?>'; 
</script>