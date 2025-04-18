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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_bensguarda_classe.php");
require_once ("classes/db_bensguardaitem_classe.php");
require_once ("classes/db_bensguardaitemdev_classe.php");
require_once ("libs/db_app.utils.php");

$clbensguarda = new cl_bensguarda;
$clbensguardaitem = new cl_bensguardaitem;
$clbensguardaitemdev = new cl_bensguardaitemdev;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;

if (isset($chavepesquisa)) {
    $db_opcao = 3;
    $db_botao = true;
    $result = $clbensguarda->sql_record($clbensguarda->sql_query($chavepesquisa));
  
    $sSqlGuarda = $clbensguardaitem->sql_query_file('', 'count(*)', '', 't22_bensguarda = '. $chavepesquisa);
    $rsGuarda = $clbensguardaitem->sql_record($sSqlGuarda);
    $iLinhas = db_utils::fieldsMemory($rsGuarda, 0)->count;

    db_fieldsmemory($result, 0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
    <div style="margin-top: 35px; width: 500px;">
  	  <?
      include("forms/db_frmbensguarda.php");
      ?>
    </div>
  </center>
</body>
</html>
<?
if (isset($excluir)) {
  if ($sqlerro == true) {
    db_msgbox($erro_msg);
    if ($clbensguarda->erro_campo != "") {
      echo "<script> document.form1." . $clbensguarda->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clbensguarda->erro_campo . ".focus();</script>";
    }
    ;
  } else {
    db_msgbox($erro_msg);
    echo "
  <script>
    function js_db_tranca(){
      parent.location.href='pat1_bensguarda003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if (isset($chavepesquisa)) {
  echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.bensguardaitem.disabled=true;
         parent.iframe_bensguardaitem.location.href='pat1_bensguardaitem001.php?db_opcaoal=33&t22_codigo=" . @$t21_codigo . "';
     ";
  if (isset($liberaaba)) {
    echo "  parent.mo_camada('bensguardaitem');";
  }
  echo "}\n
    js_db_libera();
  </script>\n
 ";
}
if ($db_opcao == 22 || $db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}


if($db_opcao == 33 || $db_opcao == 3 )  {
  ?>
    <script>

        document.getElementById('db_opcao').addEventListener('click', (e) => {

            e.preventDefault();

            let iLinhas = parseInt("<?=$iLinhas?>");
            let resposta = false;

            if(iLinhas){
                resposta = confirm('Existem bens vinculados a guarda, deseja realmente exclu�-la?');
            }else{
                resposta = confirm('Deseja realmente excluir a guarda?');
            }

            if(resposta){
                let oParam = new Object;
                oParam.exec = 'excluiTermoGuarda';
                oParam.t21_codigo = "<?=$t21_codigo?>";
                let oAjax = new Ajax.Request('pat1_bensnovo.RPC.php',
                {
                    method: 'POST',
                    parameters: 'json='+Object.toJSON(oParam), 
                    onComplete: js_retorno
                });

            } else {
                parent.document.formaba.bensguardaitem.disabled = false;
                top.corpo.iframe_bensguardaitem.location.href = 'pat1_bensguardaitem001.php?t22_bensguarda=<?=$t21_codigo?>';
                parent.iframe_bensguarda.location.href='pat1_bensguarda005.php?chavepesquisa=<?=$t21_codigo?>';
            }
          
        });

        function js_retorno(oAjax){
            
            let oResponse = eval('('+oAjax.responseText+')');

            if(oResponse.status == 2){
                alert(oResponse.msg.urlDecode());
            }else{
                alert('Guarda exclu�da com sucesso!');
                js_limpaCampos();
                document.form1.pesquisar.click();
            }

        }

        function js_limpaCampos(){
            document.form1.t21_codigo.value = '';
            document.form1.t21_numcgm.value = '';
            document.form1.z01_nome.value = '';
            document.form1.t21_tipoguarda.value = '';
            document.form1.t20_descr.value = '';
            document.form1.t21_representante.value = '';
            document.form1.t21_cpf.value = '';
            document.form1.t21_data.value = '';
            document.form1.t21_obs.value = '';
            document.form1.db_opcao.disabled = true;
        }

    </script>
<?php
}
?>