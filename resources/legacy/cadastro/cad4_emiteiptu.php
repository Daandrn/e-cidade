<?php

require_once("fpdf151/scpdf.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_layouttxt.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_libtributario.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_iptucalc_classe.php");
require_once("classes/db_iptunump_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_massamat_classe.php");
require_once("classes/db_iptuender_classe.php");

require_once("classes/db_cadban_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_db_docparag_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_listadoc_classe.php");
require_once("classes/db_db_layouttxtgeracao_classe.php");
require_once("libs/db_app.utils.php");

$cliptucalc            = new cl_iptucalc;
$cliptuender           = new cl_iptuender;
$cliptunump            = new cl_iptunump;
$clmassamat            = new cl_massamat;

$clcadban              = new cl_cadban;
$cldb_config           = new cl_db_config;
$cldb_docparag         = new cl_db_docparag;
$clarrematric          = new cl_arrematric;
$cllistadoc            = new cl_listadoc;
$cllote                = new cl_lote;
$clsetor               = new cl_setor;
$cldb_layouttxtgeracao = new cl_db_layouttxtgeracao;

$clrotulo = new rotulocampo();
$cllote->rotulo->label();
$clsetor->rotulo->label();
$clrotulo->label("j01_numcgm");

db_postmemory($HTTP_POST_VARS);

?>
<html>
 <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
   <?php
     db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js, grid.style.css");
     db_app::load("estilos.css, classes/dbViewAvaliacoes.classe.js, widgets/windowAux.widget.js");
     db_app::load("widgets/dbmessageBoard.widget.js, dbcomboBox.widget.js, DBHint.widget.js");
   ?>
</head>
<body class="body-default" onload="js_mostraOpVenc();js_mostracapa(); js_gridUnicas();">
<div class="container">
  <form name="form1" action="" method="post">
    <fieldset>
      <legend>Emiss�o Geral de IPTU</legend>

      <fieldset id='configGeral'>
        <legend>Configura��o de Gera��o</legend>

        <table border="0" class="form-container">
         <tr>
           <td width="45%">
             <strong>Quantidade de registros do select:</strong>
           </td>
           <td nowrap>
             <input type='text' size="20px;" id='quantidade' name='quantidade' value=<?=(isset($quantidade)?$quantidade:1000)?>>
           </td>
         </tr>

         <tr>
           <td width="45%">
             <strong>Quantidade de registros a gerar no txt:</strong>
           </td>
           <td nowrap>
             <input type='text' size="20px;" id='quantidade_registros_real' name='quantidade_registros_real' value=<?=(isset($quantidade_registros_real)?$quantidade_registros_real:"")?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Tipo de Arquivo:</strong>
           </td>
           <td>
             <?
               $aTipos = array ("txt"    => "TXT",
                                "pdf"    => "PDF",
                                "IGC702" => "IGC702",
                                "txtbsj" => "TXT BSJ"
                               );
               db_select('tipo', $aTipos, true, 1,"onChange='js_mostracapa();js_mostraOpVenc();' style='width:165px;'");
             ?>
           </td>
         </tr>
          <tr>
           <td>
             <strong>Ordem da Emiss�o:</strong>
           </td>
           <td>
             <?
                $aOrdem = array ("endereco"        => "Cidade / Logradouro",
                                 "bairroender"     => "Bairro / Logradouro",
                                 "alfabetica"      => "Alfab�tica / Nome",
                                 "zonaentrega"     => "Zona de entrega",
                                 "refant"          => "Refer�ncia Anterior",
                                 "setorquadralote" => "Setor / Quadra / Lote",
                                 "bairroalfa"      => "Bairro / Alfab�tica"
                                );
                db_select('ordem', $aOrdem, true, 2,"style='width:165px;'");
             ?>
           </td>
         </tr>

         <tr id = "geraOpVenc" style='display: none;'>
           <td>
             <strong>Gera Op��o de Vencimento:</strong>
           </td>
           <td>
             <?
                $aOpVenc = array ("0" => "N�o", "1" => "Sim");
                db_select('opVenc', $aOpVenc, true, 2,"style='width:165px;'");
             ?>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Taxa de Expediente:</strong>
           </td>
           <td>
               <input type='text' size="20px;" value="0" id='tx_banc' name='tx_banc' onblur="js_ValidaMaiusculo(this,'f',event);" oninput="js_ValidaCampos(this,4,'Percentual de Corre��o','t','f',event);" onkeydown="return js_controla_tecla_enter(this,event);">
           </td>
         </tr>

        </table>
      </fieldset>

      <fieldset style="margin-top: 20px;">
        <legend>Configura��o de Dados</legend>

        <table border="0" class="form-container">
          <tr>
           <td width="45%">
             <strong>Tipo de Im�vel:</strong>
           </td>
           <td>
             <?
               $aEspecie = array ("todos"       => "Todos",
                                  "predial"     => "Somente Predial",
                                  "territorial" => "Somente Territorial"
                                  );
               db_select('especie', $aEspecie, true, 1);
             ?>
           </td>
         </tr>

         <tr id="showSetor" style='display: none;'>
            <td nowrap title="<?=$Tj34_setor?>">
               <?php
               db_ancora($Lj34_setor,"js_pesquisaj34_setor(true);",1);
               ?>
            </td>
            <td>
              <?
               db_input('j34_setor',10,$Ij34_setor,true,'text',1,"onchange='js_pesquisaj34_setor(false);'");
               db_input('j30_descr',51,$Ij30_descr,true,'text',3,'');
              ?>
            </td>
          </tr>

         <tr>
           <td>
             <strong>Valor M�nimo de:</strong>
           </td>
           <td>
             <input type='text' id='vlrmin' size='10' name='vlrmin' value=<?=(isset($vlrmin)?$vlrmin:0)?>>
             <strong>�</strong>
             <input type='text' id='vlrmax' size='10' name='vlrmax' value=<?=(isset($vlrmax)?$vlrmax:999999999)?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Valor de Intervalo para Parcelado:</strong>
           </td>
           <td>
             <input type='text' id='vlrminunica' name='vlrminunica' size='10' value=<?=(isset($vlrminunica)?$vlrminunica:0)?>><strong> � </strong>
             <input type='text' id='vlrmaxunica' name='vlrmaxunica' size='10' value=<?=(isset($vlrmaxunica)?$vlrmaxunica:999999999)?>>
             <?
               $aIntervaloParcelamento = array ("desconsiderar" => "Desconsiderar intervalo",
                                                "gerar"         => "Gerar para os que estiverem no intervalo",
                                                "naogerar"      => "Nao gerar para os que estiverem no intervalo"
                                               );
               db_select('intervalo', $aIntervaloParcelamento, true, 1,"style='width:276px;'");
             ?>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Filtro Principal:</strong>
           </td>
           <td>
             <?
               $aFiltroPrincipal = array ("normal"  => "Normal",
                                          "compgto" => "Somente sem parcelas em atraso",
                                          "sempgto" => "Somente os registros sem pagamentos"
                                          );
               db_select('filtroprinc', $aFiltroPrincipal, true, 1,"style='width:480px;'");
             ?>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Vinculo com Imobili�ria:</strong>
           </td>
           <td>
             <?
               $aVinculoImobiliaria = array ( "todos" => "Imprimir todos os registros, independente do vinculo com imobiliaria",
                                              "com"   => "Somente os que tenham vinculo com imobiliaria",
                                              "sem"   => "Somente os que nao tenham vinculo com imobiliaria"
                                            );
               db_select('imobiliaria', $aVinculoImobiliaria, true, 1,"style='width:480px;'");
             ?>
           </td>
         </tr>
         <tr>
           <td>
             <strong>Vinculo com Loteamentos:</strong>
           </td>
           <td>
             <?
               $aVinculoLoteamentos = array ( "todos" => "Imprimir todos os registros, independente do vinculo com loteamento",
                                              "com"   => "Somente os que tenham vinculo com loteamento",
                                              "sem"   => "Somente os que nao tenham vinculo com loteamento"
                                            );
               db_select('loteamento', $aVinculoLoteamentos, true, 1,"style='width:480px;'");
             ?>
           </td>
         </tr>
        </table>

      </fieldset>

      <fieldset style="margin-top: 20px;">
        <legend>Outros Filtros</legend>

        <table border="0" class="form-container">
        <tr>
            <td title="<?=$Tj01_numcgm?>"><?
                db_ancora($Lj01_numcgm,"js_pesquisaj01_numcgm(true);",1);
                ?></td>
            <td><?
                db_input('j01_numcgm',10,$Ij01_numcgm,true,'text',1," onchange='js_pesquisaj01_numcgm(false);'")
                ?> <?
                db_input('z01_nome',56,$Iz01_nome,true,'text',3,'')
                ?></td>
        </tr>

         <tr id="tercDigBarrasUnica" style="display: none;">
           <td width="45%">
             <strong>Terceiro Digito do Codigo de Barras das �nicas:</strong>
           </td>

           <td>
             <?
               $aTercDigBarraUnicas = array ("seis" => "6 (seis)", "sete" => "7 (sete)");
               db_select('barrasunica', $aTercDigBarraUnicas, true, 1,"style='width:165px;'");
             ?>
           </td>
         </tr>

         <tr id="tercDigBarrasParc" style="display:none;">
           <td>
             <strong>Terceiro Digito do C�digo de Barras Parcelado:</strong>
           </td>

           <td>
             <?
               $aTercDigBarraParcelados = array ("seis" => "6 (seis)", "sete" => "7 (sete)");
               db_select('barrasparc', $aTercDigBarraParcelados, true, 1,"style='width:165px;'");
             ?>
           </td>
         </tr>

         <tr id="capa" style="display:none;">
           <td>
             <strong>Imprimir Capa:</strong>
           </td>
           <td>
             <?
               $aImpCapa = array ("n" => "N�o", "s" => "Sim");
               db_select('imprimecapa', $aImpCapa, true, 1,"style='width:165px;'");
             ?>
           </td>
         </tr>

         <tr>
           <td width="45%">
             <strong>Mensagem D�bitos Anos Anteriores:</strong>
           </td>
           <td>
             <?
               $aMsgDebitosAnt = array ("n" => "N�o", "s" => "Sim");
               db_select('mensagemanosanteriores', $aMsgDebitosAnt, true, 1,"style='width:165px;'");
             ?>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Considerar Movimento nos Anos:</strong>
           </td>
           <td nowrap>
             <input type='text' size="20px;" name='processarmovimentacao' id='processarmovimentacao' value=<?=(isset($processarmovimentacao)?$processarmovimentacao:"")?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Parcela Obrigat�ria em Aberto:</strong>
           </td>
           <td>
             <input type='text' id='parcobrig' name='parcobrig' value=<?=(isset($parcobrig)?$parcobrig:"")?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Quantidade total de parcelas:</strong>
           </td>
           <td>
             <input type='text' id='quantidadeparcelas' name='quantidadeparcelas' value=<?=(isset($quantidadeparcelas)?$quantidadeparcelas:"")?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Gerar Apenas para as Matr�culas:</strong>
           </td>
           <td>
             <input type='text' id='listamatrics' name='listamatrics' value=<?=(isset($listamatrics)?$listamatrics:"")?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Gerar com Cidade em Branco e sem Caixa Postal:</strong>
           </td>
           <td>
             <input type='checkbox' id='cidadebranco' name='cidadebranco'<?=(isset($cidadebranco)?"checked":"")?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Express�o "Isento" Quando Taxa/Imposto Zerado:</strong>
           </td>
           <td>
             <input type='checkbox' id='zerado' name='zerado' <?=(!isset($ordem)?"checked":(isset($zerado)?"checked":""))?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Somente com Endere�o V�lido ou com Caixa Postal:</strong>
           </td>
           <td>
             <input type='checkbox' id= 'entregavalido' name='entregavalido'<?=(!isset($ordem)?"checked":(isset($entregavalido)?"checked":""))?>>
           </td>
         </tr>

         <tr>
           <td>
             <strong>Processar Massa Falida:</strong>
           </td>
           <td>
             <input type='checkbox' id='proc' name='proc' <?=(isset($proc)?"checked":"")?> >
           </td>
         </tr>

         <tr>
            <td>
                <strong>Imprimir somente Cota �nica:</strong>
            </td>
            <td>
                <input type='checkbox' id='somenteunicas' name='somenteunicas' <?=(isset($somenteunicas)?"checked":"")?> >
            </td>
        </tr>

         <tr>
           <td>
             <strong>Ano:</strong>
           </td>
           <td>
             <?
              $result = db_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");

              if (pg_numrows($result) > 0) {

                echo "<select id='anousu' name='anousu' onChange='document.form1.submit();' style='width:165px;'>";

                if(!isset($anousu)) {
                   $anousu = pg_result($result, 0, "j18_anousu");
                }

                for ($i = 0; $i < pg_numrows($result); $i ++) {
                  db_fieldsmemory($result, $i);

                  echo "<option value='{$j18_anousu}' " ;
                           if(isset($anousu) && $anousu == $j18_anousu){
                           	 echo "selected";
                           } ;
                   echo  ">";
                   echo $j18_anousu;
                   echo "</option>";

                }
               echo "</select>";
             }
             ?>
           </td>
         </tr>
        </table>
      </fieldset>

      <fieldset style="margin-top: 20px;" >
        <legend>Parcela(s) �nica(s)</legend>

        <div id='cntUnicas' style="width:98%"> </div>
      </fieldset>

        <?php

          $datausu = date('Y-m-d',db_getsession('DB_datausu'));

          $sql  =  " select distinct k00_dtvenc, k00_dtoper, k00_percdes ";
          $sql .=  "   from recibounica ";
          $sql .=  "        inner join iptunump on j20_numpre = k00_numpre ";
          $sql .=  "                           and j20_anousu = {$anousu} ";
          $sql .=  "  where k00_tipoger = 'G' ";
          $sql .=  "    and k00_dtvenc > '{$datausu}' order by k00_dtvenc, k00_percdes ";

          $result = db_query($sql);
        ?>
     </fieldset>

     <input name="totcheck" type="hidden" id="totcheck" value="<?=pg_numrows($result)?>" />
     <input name="geracarnes" type="submit" id="geracarnes" value="Gerar Carnes" onclick="js_getUnicas(); return js_mostra_processando();"/>
     <input type="hidden" value="" name="listaUnicas" id="listaUnicas" />
   </form>
 </div>
  <? db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
 </body>
</html>
<script type="text/javascript">
    function js_pesquisaj01_numcgm(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&testanome=true','Pesquisa',true);
        }else{
            js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+document.form1.j01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa', false);
        }
    }
    function js_mostracgm(erro,chave){
        document.form1.z01_nome.value = chave;
        if(erro==true){
            document.form1.j01_numcgm.focus();
            document.form1.j01_numcgm.value = '';
        }
    }
    function js_mostracgm1(chave1,chave2,chave3){
        document.form1.j01_numcgm.value = chave1;
        document.form1.z01_nome.value = chave2;
        func_nome.hide();
    }
function js_pesquisaj34_setor(mostra){

  if(mostra==true){
     js_OpenJanelaIframe('','db_iframe','func_setor.php?funcao_js=parent.js_mostrasetor1|0|1','Pesquisa',true);
  }else{
     js_OpenJanelaIframe('','db_iframe','func_setor.php?pesquisa_chave='+document.form1.j34_setor.value+'&funcao_js=parent.js_mostrasetor','Pesquisa',false);
  }
}
function js_mostrasetor(chave,erro){

  if(erro==true){
    document.form1.j34_setor.focus();
    document.form1.j34_setor.value = '';
  }
  document.form1.j30_descr.value = chave;
}
function js_mostrasetor1(chave1,chave2){

  document.form1.j34_setor.value = chave1;
  document.form1.j30_descr.value = chave2;
  db_iframe.hide();
}

var aEventoShow = new Array('onMouseover','onFocus');
var aEventoHide = new Array('onMouseout' ,'onBlur');

var oDbHintQuantidade = new DBHint('oDbHintQuantidade');
    sHintQuantidade  = "Quantidade de registros a processar no select principal. <br> ";
    sHintQuantidade += "Nao significa que vao ser gerados essa quantidade de registros no txt, <br> ";
    sHintQuantidade += "pois existes testes e bloqueios que podem limitar alguns registros, dependendo dos filtros. <br> ";
    sHintQuantidade += "<strong>* deixe em branco para processar todos </strong>";
    oDbHintQuantidade.setText(sHintQuantidade);
    oDbHintQuantidade.setShowEvents(aEventoShow);
    oDbHintQuantidade.setHideEvents(aEventoHide);
    oDbHintQuantidade.make($('quantidade'));

var oDbHintQuantidadeRegistrosReal = new DBHint('oDbHintQuantidadeRegistrosReal');
    sHintQuantidadeRegistrosReal  = "Quantidade de registros real a serem gerados no txt. <br> ";
    sHintQuantidadeRegistrosReal += "<br> Valor limitado ao campo [Quantidade de registros do select].  <br> ";
    sHintQuantidadeRegistrosReal += "<strong>* deixe em branco para processar todos </strong>";
    oDbHintQuantidadeRegistrosReal.setText(sHintQuantidadeRegistrosReal);
    oDbHintQuantidadeRegistrosReal.setShowEvents(aEventoShow);
    oDbHintQuantidadeRegistrosReal.setHideEvents(aEventoHide);
    oDbHintQuantidadeRegistrosReal.make($('quantidade_registros_real'));

var oDbHintTipo = new DBHint('oDbHintTipo');
    oDbHintTipo.setText('Tipo de arquivo a ser gerado. ');
    oDbHintTipo.setShowEvents(aEventoShow);
    oDbHintTipo.setHideEvents(aEventoHide);
    oDbHintTipo.make($('tipo'));

var oDbHintOrdem = new DBHint('oDbHintOrdem');
    oDbHintOrdem.setText('Definir� a ordem em que os carnes ser�o gerados. ');
    oDbHintOrdem.setShowEvents(aEventoShow);
    oDbHintOrdem.setHideEvents(aEventoHide);
    oDbHintOrdem.make($('ordem'));

var oDbHintOpVencimento = new DBHint('oDbHintOpVencimento');
    oDbHintOpVencimento.setText('Definir� se ser�o gerados op��es de vencimentos adicionais <br> para as parcelas e �nicas. ');
    oDbHintOpVencimento.setShowEvents(aEventoShow);
    oDbHintOpVencimento.setHideEvents(aEventoHide);
    oDbHintOpVencimento.make($('opVenc'));

var oDbHintEspecie = new DBHint('oDbHintEspecie');
    oDbHintEspecie.setText('Definir se ser�o gerados carn�s <br> do tipo territorial, predial ou todos. ');
    oDbHintEspecie.setShowEvents(aEventoShow);
    oDbHintEspecie.setHideEvents(aEventoHide);
    oDbHintEspecie.make($('especie'));

var oDbHintValorMinimo = new DBHint('oDbHintValorMinimo');
    oDbHintValorMinimo.setText('Valor m�nimo que deseja considerar <br> para a emiss�o geral. ');
    oDbHintValorMinimo.setShowEvents(aEventoShow);
    oDbHintValorMinimo.setHideEvents(aEventoHide);
    oDbHintValorMinimo.make($('vlrmin'));

var oDbHintValorMaximo = new DBHint('oDbHintValorMaximo');
    oDbHintValorMaximo.setText('Valor m�ximo que deseja considerar <br> para a emiss�o geral. ');
    oDbHintValorMaximo.setShowEvents(aEventoShow);
    oDbHintValorMaximo.setHideEvents(aEventoHide);
    oDbHintValorMaximo.make($('vlrmax'));

var oDbHintValorValorMinUnica = new DBHint('oDbHintValorValorMinUnica');
    oDbHintValorValorMinUnica.setText('Informe o intervalo de valores que deseja <br> considerar nas parcelas, para que seja <br> gerado o carn� em apenas uma parcela. ');
    oDbHintValorValorMinUnica.setShowEvents(aEventoShow);
    oDbHintValorValorMinUnica.setHideEvents(aEventoHide);
    oDbHintValorValorMinUnica.make($('vlrminunica'));

var oDbHintValorValorMaxUnica = new DBHint('oDbHintValorValorMaxUnica');
    oDbHintValorValorMaxUnica.setText('Informe o intervalo de valores que deseja <br> considerar nas parcelas, para que seja <br> gerado o carn� em apenas uma parcela. ');
    oDbHintValorValorMaxUnica.setShowEvents(aEventoShow);
    oDbHintValorValorMaxUnica.setHideEvents(aEventoHide);
    oDbHintValorValorMaxUnica.make($('vlrmaxunica'));

var oDbHintintervalo = new DBHint('oDbHintintervalo');
    oDbHintintervalo.setText('Selecionar se deseja considerar <br> ou n�o o intervalo indicado. ');
    oDbHintintervalo.setShowEvents(aEventoShow);
    oDbHintintervalo.setHideEvents(aEventoHide);
    oDbHintintervalo.make($('intervalo'));

var oDbHintfiltroprinc = new DBHint('oDbHintfiltroprinc');
    oDbHintfiltroprinc.setText('Informar se deseja garar somente parcelas sem atraso, <br> sem pagamento ou todas. ');
    oDbHintfiltroprinc.setShowEvents(aEventoShow);
    oDbHintfiltroprinc.setHideEvents(aEventoHide);
    oDbHintfiltroprinc.make($('filtroprinc'));

var oDbHintimobiliaria = new DBHint('oDbHintimobiliaria');
    oDbHintimobiliaria.setText('Informar se deseja gerar somente <br> registros com v�nculo em Imobili�ria ou n�o. ');
    oDbHintimobiliaria.setShowEvents(aEventoShow);
    oDbHintimobiliaria.setHideEvents(aEventoHide);
    oDbHintimobiliaria.make($('imobiliaria'));

var oDbHintloteamento = new DBHint('oDbHintloteamento');
    oDbHintloteamento.setText('Informar se deseja gerar registros <br> somente dos im�veis que tenham <br> v�nculo com loteamento. ');
    oDbHintloteamento.setShowEvents(aEventoShow);
    oDbHintloteamento.setHideEvents(aEventoHide);
    oDbHintloteamento.make($('loteamento'));

var oDbHintMensagemAnosAnteriores = new DBHint('oDbHintMensagemAnosAnteriores');
    oDbHintMensagemAnosAnteriores.setText("Informar 'Sim', para exibir mensagem <br> de d�bito dos anos anteriores. ");
    oDbHintMensagemAnosAnteriores.setShowEvents(aEventoShow);
    oDbHintMensagemAnosAnteriores.setHideEvents(aEventoHide);
    oDbHintMensagemAnosAnteriores.make($('mensagemanosanteriores'));

var oDbHintProcessarMovimentacao = new DBHint('oDbHintProcessarMovimentacao');
var sHintProcMovimento  = "Quando informado neste campo o valor 3 por exemplo, <br> o sistema verificar� nos 3 ";
    sHintProcMovimento += "exerc�cios anteriores <br> ao que est� sendo emitido se existem pagamentos.<br><strong>";
    sHintProcMovimento += "* deixe em branco para processar todos</strong>";
    oDbHintProcessarMovimentacao.setText(sHintProcMovimento);
    oDbHintProcessarMovimentacao.setShowEvents(aEventoShow);
    oDbHintProcessarMovimentacao.setHideEvents(aEventoHide);
    oDbHintProcessarMovimentacao.make($('processarmovimentacao'));

var oDbHintParcObrig  = new DBHint('oDbHintParcObrig');
var sHintParcObrig    = "Caso queira gerar os carn�s somente para as matr�culas <br> ";
    sHintParcObrig   += "que estiverem uma determinada parcela em aberto, informe <br> ";
    sHintParcObrig   += "nesse campo o n�mero dessa parcela.";
    oDbHintParcObrig.setText(sHintParcObrig);
    oDbHintParcObrig.setShowEvents(aEventoShow);
    oDbHintParcObrig.setHideEvents(aEventoHide);
    oDbHintParcObrig.make($('parcobrig'));

var oDbHintQuantParc  = new DBHint('oDbHintQuantParc');
var sHintQuantParc    = "Caso queira gerar os carn�s somente para os <br> ";
    sHintQuantParc   += "imoveis que tem uma quantidade especifica de parcelas <br> ";
    sHintQuantParc   += "em aberto. Normalmente utilizado para BSJ. <br> ";
    sHintQuantParc   += "A logica � sempre a quantidade maxima de parcelas mais a parcela unica, <br> ";
    sHintQuantParc   += "ent�o se voc� preencher 2, vao ser processados os imoveis com at� a parcela 1 mais a �nica em aberto. <br> ";
    sHintQuantParc   += "Obs: parcelas unicas somam apenas uma em caso de ter varias. <br> ";
    oDbHintQuantParc.setText(sHintQuantParc);
    oDbHintQuantParc.setShowEvents(aEventoShow);
    oDbHintQuantParc.setHideEvents(aEventoHide);
    oDbHintQuantParc.make($('quantidadeparcelas'));

var oDbHintListaMatriculas = new DBHint('oDbHintListaMatriculas');
    oDbHintListaMatriculas.setText("Informar as matr�culas desejadas para a gera��o dos carnes. <br> <strong>*Separadas por v�rgula</strong>. ");
    oDbHintListaMatriculas.setShowEvents(aEventoShow);
    oDbHintListaMatriculas.setHideEvents(aEventoHide);
    oDbHintListaMatriculas.make($('listamatrics'));

var oDbHintCidadeBranco = new DBHint('oDbHintCidadeBranco');
var sHintCidadeBranco  = "Se esse campo estiver selecionado, ser�o gerados <br> no arquivo inclusive as matr�culas que o ";
    sHintCidadeBranco += "endere�o de envio <br> do carn� estiver com a cidade ou caixa postal em branco.";
    oDbHintCidadeBranco.setText(sHintCidadeBranco);
    oDbHintCidadeBranco.setShowEvents(aEventoShow);
    oDbHintCidadeBranco.setHideEvents(aEventoHide);
    oDbHintCidadeBranco.make($('cidadebranco'));

var oDbHintZerado = new DBHint('oDbHintZerado');
var sHintZerado   = "Se esse campo estiver marcado, sempre que gerar <br> carn� de uma matr�cula que possuir isen��o <br> no ";
    sHintZerado  += "IPTU ou taxa, ser� impresso no carn�, <br> no local onde seria impresso o valor a express�o 'ISENTO'.";
    oDbHintZerado.setText(sHintZerado);
    oDbHintZerado.setShowEvents(aEventoShow);
    oDbHintZerado.setHideEvents(aEventoHide);
    oDbHintZerado.make($('zerado'));


var sHintEntregavalido   = "Se esse campo estiver marcado, s� ser�o <br> inseridas no arquivo as matr�culas";
    sHintEntregavalido  += "que possuem <br> endere�o de entrega.";

var oDbHintEntregaValido = new DBHint('oDbHintEntregaValido');
    oDbHintEntregaValido.setText(sHintEntregavalido);
    oDbHintEntregaValido.setShowEvents(aEventoShow);
    oDbHintEntregaValido.setHideEvents(aEventoHide);
    oDbHintEntregaValido.make($('entregavalido'));

var oDbHintProc = new DBHint('oDbHintProc');
var sHintProc   = "Se esse campo estiver marcado, ser�o inseridas no arquivo <br> as matr�culas que estiverem no ";
    sHintProc  += "cadastro de massa falida.";
    oDbHintProc.setText(sHintProc);
    oDbHintProc.setShowEvents(aEventoShow);
    oDbHintProc.setHideEvents(aEventoHide);
    oDbHintProc.make($('proc'));

var oDbHintAnousu = new DBHint('oDbHintAnousu');
    oDbHintAnousu.setText('Exerc�cio da gera��o dos carn�s.');
    oDbHintAnousu.setShowEvents(aEventoShow);
    oDbHintAnousu.setHideEvents(aEventoHide);
    oDbHintAnousu.make($('anousu'));

var oDbHintBarrasUnica = new DBHint('oDbHintBarrasUnica');
var sHintBarrasUnica   = "Esse campo s� ser� utilizado quando o <br> padr�o de cobran�a adotado pela prefeitura para ";
    sHintBarrasUnica  += "<br> os carn�s de IPTU for arrecada��o.";
    oDbHintBarrasUnica.setText(sHintBarrasUnica);
    oDbHintBarrasUnica.setShowEvents(aEventoShow);
    oDbHintBarrasUnica.setHideEvents(aEventoHide);
    oDbHintBarrasUnica.make($('barrasunica'));

var oDbHintBarrasParc = new DBHint('oDbHintBarrasParc');
    oDbHintBarrasParc.setText(sHintBarrasUnica);
    oDbHintBarrasParc.setShowEvents(aEventoShow);
    oDbHintBarrasParc.setHideEvents(aEventoHide);
    oDbHintBarrasParc.make($('barrasparc'));

var oDbHintImprimeCapa = new DBHint('oDbHintImprimeCapa');
    oDbHintImprimeCapa.setText("Informar 'Sim' para imprimir capa para os carn�s.<br><strong>*Arquivo em PDF</strong> ");
    oDbHintImprimeCapa.setShowEvents(aEventoShow);
    oDbHintImprimeCapa.setHideEvents(aEventoHide);
    oDbHintImprimeCapa.make($('imprimecapa'));


function js_mostraOpVenc(){

  document.getElementById('showSetor').style.display = 'none';

  var iMostraOp = $F('tipo');

  if( iMostraOp == 'pdf' ) {
    document.getElementById('showSetor').style.display = '';
  }

  if (iMostraOp == 'txt' ) {

      document.getElementById('geraOpVenc').style.display = '';
      $("opVenc").options.length = 0;
      $("opVenc").options[0]     = new Option('N�o', '0');
      $("opVenc").options[1]     = new Option('Sim', '1');
    } else {

      document.getElementById('geraOpVenc').style.display = 'none';

		  $("opVenc").options.length = 0;
		  $("opVenc").options[0]     = new Option('N�o', '0');
		  $("opVenc").options[1]     = new Option('Sim', '1');
  }
}

function js_getUnicas(){

   var aListaCheckbox = oGridUnicas.getSelection();
   var aListaUnicas   = new Array();

   aListaCheckbox.each(
     function ( aRow ) {
       aListaUnicas.push(aRow[0]);
    }
   );

   var sListaUnicas = aListaUnicas.join('U');
   $('listaUnicas').value = sListaUnicas;
}

/*
 * funcao para montar os registros iniciais da grid
 *
 */
function lista_unicas() {

   var iAnousu          = $('anousu').value;
   var sUrlRPC          = "cad4_emiteiptuRPC.php";
   var msgDiv           = "Aguarde ... \nPesquisando �nicas";
   var oParametros      = new Object();

   oParametros.exec     = 'VerUnicas';
   oParametros.iAnousu  = iAnousu;

   js_divCarregando(msgDiv,'msgBox');

   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaUnicas
                                             });
}

/*
 * funcao para montar a grid com os registros de Unicas
 *  retornado do RPC
 *
 */
function js_retornoCompletaUnicas(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {

      oGridUnicas.clearAll(true);

      if ( oRetorno.dados.length == 0 ) {

        $('cntUnicas').innerHTML = '<strong>Sem �nicas dispon�veis...</strong>'
        return false;

      } else {

			      oRetorno.dados.each(
			                    function (oDado, iInd) {

			                        var aRow    = new Array();
			                            aRow[0] = oDado.id;
 			                            aRow[1] = oDado.unicas.urlDecode();
			                            oGridUnicas.addRow(aRow,null,null,true);
			                       });
			      oGridUnicas.renderRows();
      }
    }
}

 /*
  * Inicia a Montagem do grid UNICAS (sem os registros)
  *
  */
function js_gridUnicas() {

  oGridUnicas = new DBGrid('Unicas');
  oGridUnicas.nameInstance = 'oGridUnicas';
  oGridUnicas.setCheckbox(0);
  oGridUnicas.setCellWidth(new Array('25%',
                                     '85%'
                                   ));

  oGridUnicas.setCellAlign(new Array( 'left',
                                      'left'
                                   ));


  oGridUnicas.setHeader(new Array( 'id',
                                   'Descri��o das Unicas'
                                ));


  oGridUnicas.aHeaders[1].lDisplayed = false;
  oGridUnicas.setHeight(150);

  oGridUnicas.show($('cntUnicas'));
  oGridUnicas.clearAll(true);

  lista_unicas();
}


  function js_validaTercDigito(){

    js_divCarregando('Aguarde, carregando...','msgBox');
    var url     = 'cad4_emiteiptuRPC.php';
    var sQuery  = "anousu="+document.form1.anousu.value;
        sQuery += "&tipo="+document.form1.tipo.value;
    var oAjax   = new Ajax.Request( url, {
                                        method: 'post',
                                        parameters: sQuery,
                                        onComplete: js_recTercDig
                                       }
                                  );
  }

  function js_recTercDig(oAjax){

     js_removeObj("msgBox");
     var aRetorno = eval("("+oAjax.responseText+")");

    if (aRetorno.lErro == true){

      alert(aRetorno.sMsg.urlDecode());
      return false;
    } else {

      if ( aRetorno.sMsg == "s") {
        document.getElementById('tercDigBarrasUnica').style.display = "";
        document.getElementById('tercDigBarrasParc').style.display  = "";
      } else {
       document.getElementById('tercDigBarrasUnica').style.display = "none";
       document.getElementById('tercDigBarrasParc').style.display  = "none";
      }
    }
  }

  function js_abrelayout(){
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_layout', 'cad4_emiteiptulayout001.php', 'Sele��o de Campos ...', true);
  }

  function js_mostra_processando(){

    valini = parseFloat(document.form1.vlrminunica.value);
    valfim = parseFloat(document.form1.vlrmaxunica.value);

    if (valini > valfim) {
      alert('Valor inicial maior que valor final.');
      return false;
    }

    document.form1.processando.style.visibility = 'visible';
    return true;
  }

  function disableform(caminhoform, formstatus){

    js_OpenJanelaIframe('', 'iframe', 'testeprogress.php', 'Processando ...');

    if (formstatus == 'off') {
      for (i = 0; i < caminhoform.elements.length; i++) {
        caminhoform.elements[i].disabled = true;
      }
    }

    if (formstatus == 'on') {
      for (i = 0; i < caminhoform.elements.length; i++) {
        caminhoform.elements[i].disabled = false;
      }
    }

    caminhoform.abilitar.disabled = false;
    caminhoform.desabilitar.disabled = false;
  }

  function js_mostracapa(){

    if (document.form1.tipo.value != 'txt') {
      document.getElementById('capa').style.display = '';
    } else {
      document.getElementById('capa').style.display = 'none';
    }

    js_validaTercDigito();
  }

  js_validaTercDigito();
</script>
<?php

if(isset($geracarnes)){

	$unica = $_POST['listaUnicas'];

  if ($tipo == "txt" or $tipo == "txtbsj") {

    $oMunic =  db_utils::fieldsMemory(db_query("select munic from db_config where prefeitura is true"),0);

    switch (strtoupper($oMunic->munic)) {

        case 'ARAPIRACA_ABC' :

          $sNomeFonte = "_arapiraca";
          break;

        case 'GUAIBA':

          $sNomeFonte = "_guaiba";
          break;


        default:

          $sNomeFonte = '';
          break;
    }

    $querystring="proc=".$proc."&somenteunicas=".$somenteunicas."&entregavalido=".$entregavalido."&zerado=$zerado&local=$local&cidadebranco=".$cidadebranco."&parcobrig=$parcobrig&quantidadeparcelas=$quantidadeparcelas&listamatrics=$listamatrics&unica=$unica&anousu=$anousu&quantidade=$quantidade&quantidade_registros_real=$quantidade_registros_real&processarmovimentacao=$processarmovimentacao&mensagemanosanteriores=$mensagemanosanteriores&ordem=$ordem&especie=$especie&imobiliaria=$imobiliaria&loteamento=$loteamento&filtroprinc=$filtroprinc&barrasparc=$barrasparc&barrasunica=$barrasunica&totcheck=$totcheck&vlrminunica=$vlrminunica&intervalo=$intervalo&vlrmaxunica=$vlrmaxunica&vlrmin=$vlrmin&vlrmax=$vlrmax&tipo=$tipo&opVenc=$opVenc&j01_numcgm=$j01_numcgm&tx_banc=$tx_banc";

    echo " <script>  ";
    echo "   js_OpenJanelaIframe('','db_iframe_carne','cad4_geracarneiptutxt{$sNomeFonte}.php?$querystring','Gerando Arquivo TXT ...',true); ";
    echo " </script> ";

  } else if ($tipo == "pdf") {

    $querystring = "&proc=".$proc."&somenteunicas=".$somenteunicas."&entregavalido=".$entregavalido."&zerado=$zerado&local=$local&cidadebranco=".$cidadebranco."&parcobrig=$parcobrig&listamatrics=$listamatrics&unica=".$unica."&anousu=$anousu&quantidade=$quantidade&processarmovimentacao=$processarmovimentacao&mensagemanosanteriores=$mensagemanosanteriores&ordem=$ordem&especie=$especie&imobiliaria=$imobiliaria&loteamento=$loteamento&filtroprinc=$filtroprinc&barrasparc=$barrasparc&barrasunica=$barrasunica&totcheck=$totcheck&vlrminunica=$vlrminunica&intervalo=$intervalo&vlrmaxunica=$vlrmaxunica&vlrmin=$vlrmin&vlrmax=$vlrmax&capa=$imprimecapa&j34_setor=$j34_setor";
    $sqlmunic    = "select munic from db_config where prefeitura is true";
    $resultmunic = db_query($sqlmunic);
    $linhasmunic = pg_num_rows($resultmunic);

  if($linhasmunic > 0){

    db_fieldsmemory($resultmunic,0);

    if ($munic == "CHARQUEADAS") {
    // charqueadas modelo 28
      echo " <script> ";
        echo "   js_OpenJanelaIframe('','db_iframe_carne','cad4_geracarnesiptu_cha.php?anousu=$anousu&quantidade=$quantidade&processarmovimentacao=$processarmovimentacao&mensagemanosanteriores=$mensagemanosanteriores&ordem=$ordem&tipo=$especie','Emitindo carnes ...',true); ";
        echo " </script> ";

    }else{

      // demais modelo 1
	      $dtVencUnica = "";
	      $vir         = "";
	      $dtudica     = explode("U",$unica);
	      $tam         = (count($dtudica));

	      for($t=0; $t < $tam; $t++){
	          $dtvenc = explode("=",$dtudica[$t]);
	          if(trim($dtvenc[0])!=""){
	            $dtVencUnica .= $vir.$dtvenc[0];
	            $vir      =",";
	          }
	      }

	      echo " <script> ";

        echo "   js_OpenJanelaIframe('','db_iframe_carne','cad4_geracarnesiptu_geral.php?anousu=$anousu&quantidade=$quantidade&processarmovimentacao=$processarmovimentacao&mensagemanosanteriores=$mensagemanosanteriores&ordem=$ordem&tipo=$especie&txtNumpreUnicaSelecionados=$dtVencUnica$querystring&j01_numcgm=$j01_numcgm&tx_banc=$tx_banc','Emitindo carnes ...',true); ";
	      echo " </script> ";

      }

    }

  } else if ($tipo == "IGC702") {

    $querystring = "proc=$proc&somenteunicas=$somenteunicas&entregavalido=$entregavalido&zerado=$zerado&local=$local&cidadebranco=$cidadebranco&parcobrig=$parcobrig&listamatrics=$listamatrics&unica=$unica&anousu=$anousu&quantidade=$quantidade&processarmovimentacao=$processarmovimentacao&mensagemanosanteriores=$mensagemanosanteriores&ordem=$ordem&especie=$especie&imobiliaria=$imobiliaria&loteamento=$loteamento&filtroprinc=$filtroprinc&barrasparc=$barrasparc&barrasunica=$barrasunica&totcheck=$totcheck&vlrminunica=$vlrminunica&intervalo=$intervalo&vlrmaxunica=$vlrmaxunica&vlrmin=$vlrmin&vlrmax=$vlrmax";
    echo " <script> ";
    echo "   js_OpenJanelaIframe('','db_iframe_carne','cad4_geraiptuigc702.php?$querystring','Gerando Arquivo IGC702 ...',true); ";
    echo " </script> ";

  }

}
?>
