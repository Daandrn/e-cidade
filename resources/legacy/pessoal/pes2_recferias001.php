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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1">
<center>
<table style="margin-top: 10px;">
  <?
  if(!isset($tipo)){
    $tipo = "m";
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
  $geraform->usaorga = false;                      // PERMITIR SELE��O DE �RG�O
  $geraform->usaloca = true;                      // PERMITIR SELE��O DE LOCAL DE TRABALHO
  $geraform->usarecu = false;                      // PERMITIR SELE��O DE RECURSO


  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATR�CULA INICIAL
  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATR�CULA FINAL
  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELE��O DE MATR�CULAS

  $geraform->lo1nome = "lotai";                  // NOME DO CAMPO DA LOTA��O INICIAL
  $geraform->lo2nome = "lotaf";                  // NOME DO CAMPO DA LOTA��O FINAL
  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELE��O DE LOTA��ES

  $geraform->or1nome = "orgaoi";                  // NOME DO CAMPO DO �RG�O INICIAL
  $geraform->or2nome = "orgaof";                  // NOME DO CAMPO DO �RG�O FINAL
  $geraform->or3nome = "selorg";                  // NOME DO CAMPO DE SELE��O DE �RG�OS

  $geraform->rc1nome = "recuri";                  // NOME DO CAMPO DO RECURSO INICIAL
  $geraform->rc2nome = "recurf";                  // NOME DO CAMPO DO RECURSO FINAL
  $geraform->rc3nome = "selrec";                  // NOME DO CAMPO DE SELE��O DE RECURSOS

  $geraform->tr1nome = "locali";                  // NOME DO CAMPO DO LOCAL INICIAL
  $geraform->tr2nome = "localf";                  // NOME DO CAMPO DO LOCAL FINAL
  $geraform->tr3nome = "selloc";                  // NOME DO CAMPO DE SELE��O DE LOCAIS

  $geraform->trenome = "tipo";                    // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO

  $geraform->masnome   = "ordem";                 // NOME DO CAMPO ORDEM

  $geraform->resumopadrao = "g";                  // TIPO DE RESUMO PADRAO
//  $geraform->valortipores = "g";                // TIPO DE RESUMO PADRAO
  $geraform->filtropadrao = "s";                  // NOME DO DAS LOTA��ES SELECIONADAS

  $geraform->strngtipores = "mltg";              // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       l - lota��o,
                                                  //                                       o - �rg�o,
                                                  //                                       t - local de trabalho
                                                  //                                       s - recurso

  $geraform->tipofol = true;                      // MOSTRAR DO CAMPO PARA TIPO DE FOLHA

  $geraform->arr_tipofol = array(
                                 "r14"=>"Sal�rio",
                                 "r31"=>"F�rias",
                                 "r48"=>"Complementar"
                                );
  $geraform->complementar = "r48";                // VALUE DA COMPLEMENTAR PARA BUSCAR SEMEST

  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATR�CULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTA��ES SELECIONADAS
  $geraform->campo_auxilio_orga = "faixa_orgao";  // NOME DO DOS �RG�OS SELECIONADOS
  $geraform->campo_auxilio_recu = "faixa_recu";  // NOME DO DOS RECURSOS SELECIONADOS
  $geraform->campo_auxilio_loca = "faixa_local";  // NOME DO DOS LOCAIS SELECIONADOS

  $geraform->mostord   = false;                    // CAMPO PARA ESCOLHA DE ORDEM
  $geraform->mostnal   = false;                    // TIPO DE ORDEM ALF./NUM
  $geraform->selecao   = false;                    // CAMPO PARA ESCOLHA DA SELE��O
  $geraform->selregime = false;                    // CAMPO PARA ESCOLHA DO REGIME
  $geraform->atinpen   = false;                    // CAMPO PARA ESCOLHA DO REGIME

  $geraform->onchpad   = true;                    // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form($anofolha,$mesfolha);
  ?>

  <tr>
    <td colspan="2" align = "center">
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </td>
  </tr>
</table>
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){
  qry= "?tipo="+document.form1.tipo.value;
  qry+= "&ano="+document.form1.anofolha.value;
  qry+= "&mes="+document.form1.mesfolha.value;
  qry+= "&tipofolha="+document.form1.tipofol.value;

  if(document.form1.complementar){
    qry+= "&semest="+document.form1.complementar.value;
  }

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
  }else if(document.form1.lotai){
    lotini = document.form1.lotai.value;
    lotfim = document.form1.lotaf.value;
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

  if(document.form1.tipofol.value == 'r31'){

    jan = window.open('pes2_recferias002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }else if(document.form1.tipofol.value == 'r14'){
    jan = window.open('pes2_recferias003.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }else if(document.form1.tipofol.value == 'r48'){
    jan = window.open('pes2_recferias004.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

}
</script>
