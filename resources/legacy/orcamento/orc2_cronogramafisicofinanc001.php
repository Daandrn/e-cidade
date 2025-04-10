<?php
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

/**
 *
 * @author Iuri Guncthnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
$clrotulo = new rotulocampo;
$clrotulo->label("o74_sequencial");
$clrotulo->label("o74_descricao");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center">
     <tr height="25">
       <td>&nbsp;</td>
     </tr>
   </table>
   <center>
     <form method="post" name='form1'>
     <table>
       <tr>
         <td>
           <fieldset>
             <legend>
               <b>Relatorio IFR - 1A</b>
             </legend>
             <table>
               <tr>
                 <td nowrap>
                   <b>Ano:</b>
                  </td>
                  <td>
                    <?
                     $sSqlAno     = "select distinct o103_anousu";
                     $sSqlAno    .= "  from pactovalorsaldo   ";
                     $sSqlAno    .= " order by o103_anousu     ";
                     $rsAno       = db_query($sSqlAno);
                     $aAnos       = db_utils::getColectionByRecord($rsAno);
                     $aAnosSelect = array();
                     foreach ($aAnos as $oAno) {
                       $aAnosSelect[$oAno->o103_anousu] = $oAno->o103_anousu;
                     }
                     $iAno    = db_getsession("DB_anousu");
                     db_select("iAno",$aAnosSelect, true,1);
                    ?>
                  </td>
               </tr>
               <tr>
                 <td nowrap >
                   <?
                   db_ancora("<b>Plano:</b>","js_pesquisapactoplano(true);",1);
                   ?>
                 </td>
                 <td>
                  <?
                  db_input('o74_sequencial',10,$Io74_sequencial,true,'text',1," onchange='js_pesquisapactoplano(false);'");
                  db_input('o74_descricao',40,$Io74_descricao,true,'text',3,'');
                  ?>
                </td>
              </tr>
               <tr>
                 <td colspan='2' style='text-align:center'>
                   <input type='button' value='emitir' onclick="js_emiteRel()">
                 </td>
               </tr>
             </table>
           </fieldset>
         </td>
       </tr>
     </table>
     </form>
   </center>
</body>
</html>
<script>
function js_pesquisapactoplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_pactoplano',
                        'func_pactoplano.php?funcao_js=parent.js_mostrapactoplano1|o74_sequencial|o74_descricao',
                        'Pesquisa de Planos',
                        true);
  }else{
     if(document.form1.o74_sequencial.value != ''){
        js_OpenJanelaIframe('',
                            'db_iframe_pactoplano',
                            'func_pactoplano.php?pesquisa_chave='+document.form1.o74_sequencial.value+
                            '&funcao_js=parent.js_mostrapactoplano','Pesquisa',false);
     }else{
       document.form1.o74_descricao.value = '';
     }
  }
}
function js_mostrapactoplano(chave,erro){
  document.form1.o74_descricao.value = chave;
  if(erro==true){

    document.form1.o74_sequencial.focus();
    document.form1.o74_sequencial.value = '';

  }
}
function js_mostrapactoplano1(chave1,chave2){

  document.form1.o74_sequencial.value = chave1;
  document.form1.o74_descricao.value = chave2;
  db_iframe_pactoplano.hide();

}
 function js_emiteRel() {

   if ($F('o74_sequencial') == "") {

     alert('Informe o Plano');
     return false;

   }
   var iPeriodo = $F('iAno');
   var sUrl     = 'orc2_cronogramafisicofinanc002.php?iAno='+iPeriodo+'&iPlano='+$F('o74_sequencial');
   window.open(sUrl,'','location=0');

 }

</script>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>