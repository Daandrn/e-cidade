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
include("dbforms/db_classesgenericas.php");
require_once("classes/db_isstipoalvaradepto_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);



$clisstipoalvaradepto = new cl_isstipoalvaradepto;
$cliframe_seleciona   = new cl_iframe_seleciona;
$clrotulo             = new rotulocampo;
$cltabdesc->k07_instit = db_getsession("DB_instit");
$db_botao = true;

$db_opcao = $_GET['dbopcao'];

if(isset($incluir) or isset($alterar)){
	db_inicio_transacao();
	$erro = false;
	$db_opcao= 2 ;

	
	$clisstipoalvaradepto->q99_isstipoalvara               = $q98_sequencial;
	$clisstipoalvaradepto->excluir(null,"q99_isstipoalvara = $q98_sequencial");
	if($clisstipoalvaradepto->erro_status="0"){
  	    $erro = true;
		    $msgerro = $clisstipoalvaradepto->erro_msg;
	}	
	
	$depto = explode("#",$chaves); 
  for($w=0;$w<count($depto);$w++){
    if($erro==false){
    	
      $clisstipoalvaradepto->q99_depto         = $depto[$w];
      $clisstipoalvaradepto->q99_isstipoalvara = $q98_sequencial;
      $clisstipoalvaradepto->incluir(null);
      if ($clisstipoalvaradepto->erro_status = 0) {
      	
  	    $erro = true;
		    $msgerro = $clisstipoalvaradepto->erro_msg;
				break;      	
      }
      
      
	  }  
 	}
  db_fim_transacao($erro);
}else if(isset($excluir)){
	db_inicio_transacao();
	$erro = false;
	$db_opcao= 3 ;
	
	
	$clisstipoalvaradepto->q99_isstipoalvara               = $q98_sequencial;
	$clisstipoalvaradepto->excluir(null,"q99_isstipoalvara = $q98_sequencial");
	if($clisstipoalvaradepto->erro_status="0"){
  	    $erro = true;
		    $msgerro = $clisstipoalvaradepto->erro_msg;
	}	
	db_fim_transacao($erro);
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
	<center>
<form name="form1" >

<table width="680" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  colspan="2">&nbsp;
    </td>
  </tr>
  <tr>
    <td nowrap title="Tipo de Alvar�"   >
       Tipo de Alvar�
    </td>
    <td> 
			<?
			db_input('q98_sequencial',5,'',true,'text',3,"")
			?>
			<?
      db_input('q98_descricao',70,'',true,'text',3,"")
      ?>
    </td>
  </tr>
	<tr>
		<td colspan="2" align="center">
		   <?
			
			$sql = "select coddepto,descrdepto from db_depart where instit = ".db_getsession("DB_instit")." order by descrdepto";
			$sqlmarca = "select coddepto,descrdepto 
			             from db_depart 
			             inner join isstipoalvaradepto on coddepto = q99_depto
			             where q99_isstipoalvara      = ".$q98_sequencial." 
									 order by descrdepto";
			$cliframe_seleciona->chaves  = "coddepto";
      $cliframe_seleciona->campos  = "coddepto,descrdepto";
      $cliframe_seleciona->legenda = "Departamentos";
      $cliframe_seleciona->sql     = $sql;
      $cliframe_seleciona->sql_marca = $sqlmarca;
      $cliframe_seleciona->iframe_height ="300";
      $cliframe_seleciona->iframe_width  ="400";
      //$cliframe_seleciona->dbscript      = "";
      $cliframe_seleciona->iframe_nome ="deptos"; 
      $cliframe_seleciona->iframe_seleciona($db_opcao);
      
      ?>
	  </td>
	</tr>
	
	<tr>
		<td colspan="2" align="center"> 
 			<input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" type="submit" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick='js_gera_chaves();' >
		</td>
	</tr>
</table>
</form>	
</center>
</body>
</html>
<?
if(isset($incluir) or isset($alterar) or isset($excluir)){
  if($erro==true){
	  db_msgbox($msgerro);
  }else{
  	if(isset($incluir)){
  		db_msgbox("Influs�o efetuada com sucesso!");
  	}elseif(isset($alterar)){
  		db_msgbox("Altera��o efetuada com sucesso!");
  	}elseif(isset($excluir)){
  		db_msgbox("Exclus�o efetuada com sucesso!");
  	}
  }
}
?>