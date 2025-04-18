<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

?>
<html>
<head>
  <?php
  db_app::load('scripts.js, prototype.js, strings.js,DBLookUp.widget.js,EmissaoRelatorio.js');
  db_app::load('estilos.css');
  ?>
</head>
<body class="body-default">
<div class="container">
  <fieldset>
    <legend>Relat�rio de Devolu��es</legend>
    <table>
      <tr>
        <td>
          <label class="bold" for="datainicial">Per�odo de:</label>
        </td>
        <td>
          <?php db_inputdata('datainicial', null, null, null, true, 'text', 1); ?>
          <label class="bold" for="datafinal"> at� </label>
          <?php db_inputdata('datafinal', null, null, null, true, 'text', 1); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="z01_numcgm" class="for">
            <a id="cgm_ancora">Nome/Raz�o Social:</a>
          </label>
        </td>
        <td>
          <input type="text" name="z01_numcgm" id="z01_numcgm">
          <input type="text" name="z01_nome" id="z01_nome">
        </td>
      </tr>
    </table>
  </fieldset>

  <input type="button" name="emitir" id="emitir" value="Emitir" />
</div>

<?php
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>

<script type="text/javascript">

  var oCgm = $("cgm_ancora");
  var oNumCgm =$("z01_numcgm");
  var oNomeCgm =$("z01_nome");
  var oCgmLookup = new DBLookUp(oCgm, oNumCgm, oNomeCgm, {
    "sArquivo": "func_nome.php",
    "sObjetoLookUp": "db_iframe_numcgm",
    "sLabel": "Pesquisar"
  });

  $('emitir').addEventListener('click', function() {

    var oParametros = {
      iCgm: $F('z01_numcgm'),
      sDataInicial: $F('datainicial'),
      sDataFinal: $F('datafinal')
    };

    new EmissaoRelatorio("arr2_relatoriodevolucoes002.php", oParametros).open();
  });
</script>
</body>
</html>
