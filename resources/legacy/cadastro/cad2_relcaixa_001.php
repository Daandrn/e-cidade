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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_AbreJanelaRelatorio() {
	itemselecionado = 0;
	numElems = document.form1.ordem_relatorio.length;
	for (i=0;i<numElems;i++) {
	  if (document.form1.ordem_relatorio[i].checked) itemselecionado = i;
	}
	relatorio = document.form1.opcao_relatorio.value;
	ordem = document.form1.ordem_relatorio[itemselecionado].value;
    window.open('cad2_relcaixa_002.php?opcaoRelatorio='+relatorio+'&opcaoOrdem='+ordem);  }
</script>
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
<?
  if (isset($db_opcao)) {
?>
<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"><table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><form name="form1" method="post" action="../../../FrontController.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><table width="100%" border="1" cellspacing="0" cellpadding="0">
                      <tr align="center">
                        <td width="34%" bgcolor="#0099CC"><strong>Relatorio</strong></td>
                        <td colspan="2" bgcolor="#0099CC"><strong>Ordem</strong></td>
                      </tr>
                      <?
    if ($db_opcao == "tabrec") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Receitas Cadastradas</td>
                        <td width="22%"> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp; Alfabetica&nbsp;</td>
                        <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    } else if ($db_opcao == "tabrecjm") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Tipos de Juros e Multa</td>
                        <td> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp;Alfabetica&nbsp;</td>
                        <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    } else if ($db_opcao == "saltes") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Contas da Tesouraria</td>
                        <td> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp;Alfabetica&nbsp;</td>
                        <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    } else if ($db_opcao == "cadban") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Bancos</td>
                        <td> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp;Alfabetica&nbsp;</td>
                        <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    } else if ($db_opcao == "arretipo") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Tipos de D&eacute;bitos</td>
                        <td> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp;Alfabetica&nbsp;</td>
                        <td width="37%">&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    } else if ($db_opcao == "histcalc") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Hist&oacute;ricos</td>
                        <td> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp;Alfabetica&nbsp;</td>
                        <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    } else if ($db_opcao == "cfautent") {
?>
                      <tr>
                        <td> <input type="radio" name="opcao_relatorio" value="<?=$db_opcao?>" checked>
                          &nbsp;Autenticadoras</td>
                        <td> <input name="ordem_relatorio" type="radio" value="alfabetica" checked>
                          &nbsp;Alfabetica&nbsp;</td>
                        <td>&nbsp; <input type="radio" name="ordem_relatorio" value="numerica">
                          Numerica&nbsp;</td>
                      </tr>
                      <?
    }
?>
                      <tr align="center">
                        <td colspan="3">&nbsp;</td>
                      </tr>
                      <tr align="center">
                        <td colspan="3"><input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
</table>
<?
  }
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
