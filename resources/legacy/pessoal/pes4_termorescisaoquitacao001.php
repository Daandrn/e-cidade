<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_gerfcom_classe.php");
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1">
<table align="center" style="padding-top: 25px;">
<tr><td>
<fieldset>
<legend><strong>Termos de Quita��o de Rescis�o do Contrato de Trabalho</strong></legend>
<table>
  <?
  if(!isset($tipo)){
    $tipo = "l";
  }
  if(!isset($filtro)){
    $filtro = "i";
  }
  if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
    $anofolha = db_anofolha();
  }
  if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
    $mesfolha = db_mesfolha();
  }
  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->usaregi = true;                      // PERMITIR SELE��O DE MATR�CULAS
  $geraform->usalota = true;                      // PERMITIR SELE��O DE LOTA��ES
  $geraform->usaorga = true;                      // PERMITIR SELE��O DE �RG�O
  $geraform->usaloca = true;                      // PERMITIR SELE��O DE LOCAL DE TRABALHO
  $geraform->usarecu = true;                      // PERMITIR SELE��O DE LOCAL DE TRABALHO

  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATR�CULA INICIAL
  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATR�CULA FINAL
  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELE��O DE MATR�CULAS

  $geraform->lo1nome = "lotaci";                  // NOME DO CAMPO DA LOTA��O INICIAL
  $geraform->lo2nome = "lotacf";                  // NOME DO CAMPO DA LOTA��O FINAL
  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELE��O DE LOTA��ES

  $geraform->or1nome = "orgaoi";                  // NOME DO CAMPO DO �RG�O INICIAL
  $geraform->or2nome = "orgaof";                  // NOME DO CAMPO DO �RG�O FINAL
  $geraform->or3nome = "selorg";                  // NOME DO CAMPO DE SELE��O DE �RG�OS 

  $geraform->tr1nome = "locali";                  // NOME DO CAMPO DO LOCAL INICIAL
  $geraform->tr2nome = "localf";                  // NOME DO CAMPO DO LOCAL FINAL
  $geraform->tr3nome = "selloc";                  // NOME DO CAMPO DE SELE��O DE LOCAIS

  $geraform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO

  $geraform->rc1nome = "recuri"; // Nome do campo RECURSO 1.
  $geraform->rc2nome = "recurf"; // Nome do campo RECURSO 2.
  $geraform->rc3nome = "selrec"; // Nome do objeto de sele��o de recurso.
  $geraform->rc4nome = "Recurso";  // Nome para o Label do resumo , intervalo e selecao.

  $geraform->strngtipores = "glomts";              // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       l - lota��o,
                                                  //                                       o - �rg�o,
                                                  //                                       m - matr�cula,
                                                  //                                       t - local de trabalho

  $geraform->tipofol = false;                      // MOSTRAR DO CAMPO PARA TIPO DE FOLHA

  $geraform->complementar       = "r48";                // VALUE DA COMPLEMENTAR PARA BUSCAR SEMEST
  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATR�CULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTA��ES SELECIONADAS
  $geraform->campo_auxilio_orga = "faixa_orgao";  // NOME DO DOS �RG�OS SELECIONADOS
  $geraform->campo_auxilio_loca = "faixa_local";  // NOME DO DOS LOCAIS SELECIONADOS

  $geraform->selecao = true;                      // CAMPO PARA ESCOLHA DA SELE��O
  $geraform->selregime = false;                    // CAMPO PARA ESCOLHA DO REGIME

  $geraform->onchpad = true;                      // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form($anofolha,$mesfolha);
  ?>
  <tr>
    <td colspan="2" align = "center"> 
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </td>
  </tr>
</table>
</fieldset>
</tr></td>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){

  qry = "?tipo="+document.form1.tipo.value;
  qry+= "&ano="+document.form1.anofolha.value;
  qry+= "&mes="+document.form1.mesfolha.value;
  qry+= "&sel="+document.form1.selecao.value;

  if(document.form1.selreg){
    if(document.form1.selreg.length > 0){
      faixareg = js_campo_recebe_valores();
      qry+= "&fre="+faixareg;
    }
  }else if(document.form1.regisi){
    regini = document.form1.regisi.value;
    regfim = document.form1.regisf.value;
    qry+= "&rei="+regini;
    qry+= "&ref="+regfim;
  }

  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      faixalot = js_campo_recebe_valores();
      qry+= "&flt="+faixalot;
    }
  }else if(document.form1.lotaci){
    lotini = document.form1.lotaci.value;
    lotfim = document.form1.lotacf.value;
    qry+= "&lti="+lotini;
    qry+= "&ltf="+lotfim;
  }

  if(document.form1.selloc){
    if(document.form1.selloc.length > 0){
      faixaloc = js_campo_recebe_valores();
      qry+= "&flc="+faixaloc;
    }
  }else if(document.form1.locali){
    locini = document.form1.locali.value;
    locfim = document.form1.localf.value;
    qry+= "&lci="+locini;
    qry+= "&lcf="+locfim;
  }

  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      faixaorg = js_campo_recebe_valores();
      qry+= "&for="+faixaorg;
    }
  }else if(document.form1.orgaoi){
    orgini = document.form1.orgaoi.value;
    orgfim = document.form1.orgaof.value;
    qry+= "&ori="+orgini;
    qry+= "&orf="+orgfim;
  }

  if(document.form1.selrec){
    if(document.form1.selrec.length > 0){
      faixarec = js_campo_recebe_valores();
      qry+= "&frc="+faixarec;
    }
  }else if(document.form1.recuri){
    recini = document.form1.recuri.value;
    recfim = document.form1.recurf.value;
    qry+= "&rci="+recini;
    qry+= "&rcf="+recfim;
  }

  jan = window.open('pes4_termorescisaoquitacao002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
</script>