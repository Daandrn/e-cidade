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
include("classes/db_rharqbanco_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrharqbanco = new cl_rharqbanco;
$clrotulo = new rotulocampo;
$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');

if(isset($emite2)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrharqbanco->alterar($rh34_codarq);
  $rh34_sequencial += 1;
  db_fim_transacao($sqlerro);
}else if(isset($rh34_codarq)){
  $result = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
  if($clrharqbanco->numrows > 0){
    db_fieldsmemory($result,0);
    if($rh34_sequencial > 1){
      $rh34_sequencial += 1;
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_valores(){
  if(document.form1.rh34_codarq.value==""){
    alert('C�digo do arquivo n�o informado!');
    document.form1.rh34_codarq.focus();
    return false;
  }else if(document.form1.datagera_dia.value == "" || document.form1.datagera_mes.value == "" || document.form1.datagera_ano.value == ""){
    alert("Data da gera��o do arquivo n�o informada");
    document.form1.datagera_dia.select();
    return false;
  }else if(document.form1.datadeposit_dia.value == "" || document.form1.datadeposit_mes.value == "" || document.form1.datadeposit_ano.value == ""){
    alert("Data de dep�sito n�o informada");
    document.form1.datadeposit_dia.select();
    return false;
  }else{
    return true;
  }
}

tempoinicial = new Date();
function js_emite(){
  js_controlarodape(true, false);
  qry  = 'rh34_codarq='+document.form1.rh34_codarq.value;
  qry += '&datadeposit='+document.form1.datadeposit_ano.value+'-'+document.form1.datadeposit_mes.value+'-'+document.form1.datadeposit_dia.value;
  qry += '&datagera='+document.form1.datagera_ano.value+'-'+document.form1.datagera_mes.value+'-'+document.form1.datagera_dia.value;
  qry += '&codban='+document.form1.rh34_codban.value;
  qry += '&tiparq='+document.form1.tiparq.value;
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_geraarqbanco','pes2_caremitearqbanco002.php?'+qry,'Gerando Arquivo',false);
}

function js_detectaarquivo(arquivo,pdf){
  js_controlarodape(false, true);
  CurrentWindow.corpo.db_iframe_geraarqbanco.hide();
  listagem = arquivo+"#Download arquivo TXT (pagamento eletr�nico)|";
  listagem+= pdf+"#Download relat�rio";
  js_montarlista(listagem,"form1");
}

function js_erro(msg){
  js_controlarodape(false, false);
  CurrentWindow.corpo.db_iframe_geraarqbanco.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_geraarqbanco.hide();
}
function js_controlarodape(mostra, hora){
  if(mostra == true){
    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
  if(hora == true){
    tempofinal = new Date();
    tempototal = new Date(tempofinal - tempoinicial - 75600000);
    hora = tempototal.getHours()   < 10 ? '0' + tempototal.getHours()   : tempototal.getHours();
    minu = tempototal.getMinutes() < 10 ? '0' + tempototal.getMinutes() : tempototal.getMinutes();
    segu = tempototal.getSeconds() < 10 ? '0' + tempototal.getSeconds() : tempototal.getSeconds();
    tempototal = hora+':'+minu+':'+segu;
    parent.bstatus.document.getElementById('st').innerHTML += " - <b>Tempo de gera��o: "+tempototal+"</b>";
  }
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
<table align="center" border="0">
  <form name="form1" method="post" action="">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><b>Data da Gera��o:</b></td>
    <td>
      <?
      if((!isset($datagera_dia) || (isset($datagera_dia) && trim($datagera_dia) == "")) && (!isset($datagera_mes) || (isset($datagera_mes) && trim($datagera_mes) == "")) && (!isset($datagera_ano) || (isset($datagera_ano) && trim($datagera_ano) == ""))){
        $datagera_dia=date('d',db_getsession('DB_datausu'));
        $datagera_mes=date('m',db_getsession('DB_datausu'));
        $datagera_ano=date('Y',db_getsession('DB_datausu'));
      }
      db_inputdata('datagera',@$datagera_dia,@$datagera_mes,@$datagera_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td><b>Data do Dep�sito:</b></td>
    <td>
      <?
      if((!isset($datadeposit_dia) || (isset($datadeposit_dia) && trim($datadeposit_dia) == "")) && (!isset($datadeposit_mes) || (isset($datadeposit_mes) && trim($datadeposit_mes) == "")) && (!isset($datadeposit_ano) || (isset($datadeposit_ano) && trim($datadeposit_ano) == ""))){
        $datadeposit_dia = "";
        $datadeposit_mes = "";
        $datadeposit_ano = "";
      }
      db_inputdata('datadeposit',@$datadeposit_dia,@$datadeposit_mes,@$datadeposit_ano,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td align="left" nowrap title="<?=@$Trh34_codarq?>">
      <?db_ancora(@$Lrh34_codarq,"js_pesquisa(true);",1);?>
    </td>
    <td align="left" nowrap colspan="3">
      <?db_input("rh34_codarq",6,@$Irh34_codarq,true,"text",4,"onchange='js_pesquisa(false);'");?>
      <?db_input("rh34_descr",40,@$Irh34_descr,true,"text",3);?>
      <?db_input("rodape",40,0,true,"hidden",3);?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_codban?>">
      <?
      db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",1);
      ?>
    </td>
    <td colspan="3">
      <?
      db_input('rh34_codban',6,$Irh34_codban,true,'text',1," onchange='js_pesquisarh34_codban(false);'")
      ?>
      <?
      db_input('db90_descr',40,$Idb90_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_agencia?>">
      <?=@$Lrh34_agencia?>
    </td>
    <td>
      <?
      db_input('rh34_agencia',5,$Irh34_agencia,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td>
      <?
      db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_conta?>">
      <?=@$Lrh34_conta?>
    </td>
    <td>
      <?
      db_input('rh34_conta',15,$Irh34_conta,true,'text',1,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
      <?=@$Lrh34_dvconta?>
    </td>
    <td>
      <?
      db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_convenio?>">
      <?=@$Lrh34_convenio?>
    </td>
    <td>
      <?
      db_input('rh34_convenio',20,$Irh34_convenio,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_sequencial?>">
      <?=@$Lrh34_sequencial?>
    </td>
    <td>
      <?
      db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <strong>Tipo de arquivo:</strong>
    </td>
    <td>
      <?
      $arr_tiparq = Array(
                          "f"=>"FUNDEB",
                          "d"=>"FUNDEF",
                          "m"=>"MDE",
                          "a"=>"ASPS",
                          "s"=>"FES",
			                    "l"=>"LIVRE",
			                    "1"=>"FNS/PAB",
			                    "2"=>"FNS/PACS",
			                    "3"=>"FNS/SBUCAL/PSB",
			                    "4"=>"FNS/PSF",
			                    "5"=>"FNS/SAUDE AMBIENTAL",
			                    "6"=>"FNS/SAUDE MENTAL",
			                    "7"=>"FES/PSF ",
			                    "8"=>"FES/SBUCAL/PSB",
			                    "9"=>"SPS/VIGILANCIA SANITARIA",
			                    "10"=>"SPS/VIGILANCIA SAUDE AMBIENTAL",
			                    "11"=>"ESF-FNS/PAB FIXO",
			                    "12"=>"ESF-FNS/PSF PR.SEL",
			                    "13"=>"VIG SAUDE AMBIENT/ FNS",
			                    "14"=>"ESF-FES/PSF",
			                    "15"=>"FES/PACS",
			                    "16"=>"DST-AIDS/FNS",
			                    "t"=>"Todos",
                         );
      db_select("tiparq", $arr_tiparq, true, 1);
      ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align = "center">
      <input name="emite2" id="emite2" type="submit" value="Processar" onclick="return js_valores();" >
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rharqbanco','func_rharqbanco.php?funcao_js=parent.js_mostra1|rh34_codarq|rh34_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codarq.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rharqbanco','func_rharqbanco.php?pesquisa_chave='+document.form1.rh34_codarq.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }else{
      document.form1.rh34_codarq.value = '';
      document.form1.rh34_descr.value = '';
      location.href = 'pes2_caremitearqbanco001.php';
    }
  }
}
function js_mostra(chave,erro){
  if(erro==true){
    document.form1.rh34_descr.value = chave;
    document.form1.rh34_codarq.value = '';
    document.form1.rh34_codarq.focus();
    location.href = 'pes2_caremitearqbanco001.php';
  }else{
    document.form1.submit();
  }
}
function js_mostra1(chave1,chave2){
  document.form1.rh34_codarq.value = chave1;
  document.form1.submit();
  db_iframe_rharqbanco.hide();
}
function js_pesquisarh34_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codban.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = '';
    }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave;
  if(erro==true){
    document.form1.rh34_codban.focus();
    document.form1.rh34_codban.value = '';
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
</script>
<?
if(isset($emite2)){
  if($clrharqbanco->erro_status=="0"){
    $clrharqbanco->erro(true,false);
    $db_botao=true;
    if($clrharqbanco->erro_campo!=""){
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".focus();</script>";
    };
  }else{
    echo "<script>js_emite();</script>";
  };
};
?>
