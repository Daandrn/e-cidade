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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_iptubase_classe.php");

  db_postmemory($HTTP_SERVER_VARS);
  db_postmemory($HTTP_POST_VARS);

  $db_botao=1;
  $db_opcao=1;

  $cliptubase = new cl_iptubase;
  $cliptubase->rotulo->label();
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">

    <style type="text/css">
      <!--
      td {
	      font-family: Arial, Helvetica, sans-serif;
	      font-size  : 12px;
      }

      input {
        font-family: Arial, Helvetica, sans-serif;
        font-size  : 12px;
        height     : 17px;
        border     : 1px solid #999999;
      }
      -->
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <table align="center" width="700" height="430" border="0" valign="top" cellspacing="0" cellpadding="0"
	  bgcolor="#cccccc">
    <tr>
      <td align="center" valign="top" bgcolor="#cccccc">
        <form name="form1" method="post" action="agu4_descontomatricula022.php"
          onSubmit="return js_verifica_campos_digitados();">
          <fieldset style="margin-top: 50px; width: 790px; height: 70px;">
            <legend>
              <strong>Desconto (Lan�a Desconto)</strong>
            </legend>

            <table border="0" cellspacing="1" cellpadding="0" style="margin-top: 10px;">
              <tr>
                <td>
                  <?
                    db_ancora($Lj01_matric, ' js_matri(true); ', 1);
                  ?>
                </td>
                <td>
                  <?
                    db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1, "onchange='js_matri(false)'");
                    db_input('z01_nome', 60, 0, true, 'text', 3, "", "z01_nomematri");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
          <p align="center">
            <input type="submit" name="pesquisar" value="Pesquisar">
          </p>
        </form>
      </td>
    </tr>
	</table>
	<?
	  db_menu(db_getsession("DB_id_usuario"),
	          db_getsession("DB_modulo"),
	          db_getsession("DB_anousu"),
	          db_getsession("DB_instit")
	         );
	?>
  </body>
</html>

<script>

  function js_matri(mostra) {

    var matri = document.form1.j01_matric.value;

    if (mostra == true) {
      js_OpenJanelaIframe('', 'db_iframe3', 'func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome',
    	  'Pesquisa', true);
    } else {
      js_OpenJanelaIframe('', 'db_iframe3', 'func_iptubase.php?pesquisa_chave=' + matri
    	  + '&funcao_js=parent.js_mostramatri1', 'Pesquisa', false);
    }
  }


  function js_mostramatri(chave1, chave2) {

    document.form1.j01_matric.value    = chave1;
    document.form1.z01_nomematri.value = chave2;
    db_iframe3.hide();
  }


  function js_mostramatri1(chave, erro) {

    document.form1.z01_nomematri.value = chave;

    if (erro == true) {

    	document.form1.j01_matric.focus();
      document.form1.j01_matric.value = '';
    }
  }

</script>
