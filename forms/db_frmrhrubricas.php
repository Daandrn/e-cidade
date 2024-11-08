<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


//MODULO: pessoal
$clrhrubricas->rotulo->label();
$clrhrubelemento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh29_descr");
$clrotulo->label("o56_descr");
$clrotulo->label("rh75_retencaotiporec");
$clrotulo->label("e21_descricao");
$clrotulo->label("rh137_descricao");

if ($db_opcao == 1) {
    $db_action = "pes1_rhrubricas004.php";
} else if ($db_opcao == 2 || $db_opcao == 22) {
    $db_action = "pes1_rhrubricas005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
    $db_action = "pes1_rhrubricas006.php";
}

$rh27_instit = db_getsession("DB_instit");

$oDaoAvaliacaoperguntaopcao = db_utils::getDao("avaliacaoperguntaopcao");
define('ESOCIAL_OPCAO_PREVSOCIAL', 3000947);
define('ESOCIAL_OPCAO_IRRF', 3000948);
define('ESOCIAL_OPCAO_FGTS', 3000949);
define('ESOCIAL_OPCAO_REGIME', 4000361);
?>
<div align="center">
    <form name="form1" method="post" action="<?= $db_action ?>">

        <table border="0">
            <tr>
                <td>

                    <fieldset>
                        <Legend><b>Rubrica</b></Legend>

                        <table width="100%">
                            <tr>
                                <td title="<?= @$Trh27_rubric ?>" width="35%">
                                    <?= @$Lrh27_rubric ?>
                                </td>
                                <td width="65%">
                                    <?
                                    db_input('rh27_rubric', 13, $Irh27_rubric, true, 'text', ($db_opcao == 1 ? 1 : 3), "onblur='js_bloqueiaMedias(this);' onChange='js_validarCodigoRubrica();'");
                                    db_input('rh27_instit', 13, $Irh27_instit, true, 'hidden', 3, "");
                                    if (isset($importar) || isset($codigo_importa)) {
                                        if (isset($importar)) {
                                            $codigo_importa = $importar;
                                        }
                                        db_input('codigo_importa', 13, 0, true, 'hidden', 3, "");
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_descr ?>">
                                    <?= @$Lrh27_descr ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_descr', 50, $Irh27_descr, true, 'text', $db_opcao, "");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_quant ?>">
                                    <?= @$Lrh27_quant ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_quant', 13, $Irh27_quant, true, 'text', $db_opcao, "");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_pd ?>">
                                    <?= @$Lrh27_pd ?>
                                </td>
                                <td>
                                    <?
                                    $x = array("1" => "Provento", "2" => "Desconto", "3" => "Base");
                                    db_select('rh27_pd', $x, true, $db_opcao, "onChange='js_configSelTipo(this.value)'; style='width:101px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_tipo ?>">
                                    <?= @$Lrh27_tipo ?>
                                </td>
                                <td>
                                    <?
                                    $info = array("1" => "Fixa", "2" => "Vari�vel");
                                    db_select('rh27_tipo', $info, true, $db_opcao, "style='width:101px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_ativo ?>">
                                    <?= @$Lrh27_ativo ?>
                                </td>
                                <td>
                                    <?
                                    $arr_ativo = array("t" => "Ativo", "f" => "Inativo");
                                    db_select('rh27_ativo', $arr_ativo, true, $db_opcao, "style='width:101px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= @$Lrh27_valorpadrao ?>
                                </td>
                                <td>
                                    <?php
                                    db_input('rh27_valorpadrao', 13, $Irh27_valorpadrao, true, 'text', $db_opcao, "");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?= @$Lrh27_quantidadepadrao ?>
                                </td>
                                <td>
                                    <?php
                                    db_input('rh27_quantidadepadrao', 13, $Irh27_quantidadepadrao, true, 'text', $db_opcao, "");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_obs ?>" colspan=2 align="center">
                                    <fieldset>
                                        <legend><?= @$Lrh27_obs ?></legend>
                                        <?
                                        db_textarea('rh27_obs', 2, 80, $Irh27_obs, true, 'text', $db_opcao, "");
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                        </table>

                    </fieldset>

                    <fieldset id="ctnInformacoes">
                        <Legend><b>Informa��es</b></Legend>
                        <table width="100%">
                            <?
                            if ($db_opcao == 1 && (!isset($rh27_calc1) || $rh27_calc1 == "") && (!isset($rh27_calc2) || $rh27_calc2 == "")) {

                                $result_media = $clrhtipomedia->sql_record($clrhtipomedia->sql_query_file('0'));
                                if ($clrhtipomedia->numrows > 0) {
                                    db_fieldsmemory($result_media, 0);
                                    $rh27_calc1 = $rh29_tipo;
                                    $rh29_descr = $rh29_descr;
                                    $rh27_calc2 = $rh29_tipo;
                                    $rh29_descr2 = $rh29_descr;
                                }
                            }
                            ?>
                            <tr>
                                <td title="<?= @$Trh27_calc1 ?>" width="35%">
                                    <?
                                    $sFunction = '';
                                    if ($db_opcao != 1 && @$rh27_rubric < 1999) {
                                        $sFunction = "js_pesquisarh27_calc1(true);";
                                    }
                                    db_ancora(@$Lrh27_calc1, "$sFunction\" id='lookupcalc1'", $db_opcao);
                                    ?>
                                </td>
                                <td width="65%">
                                    <?
                                    db_input('rh27_calc1', 10, $Irh27_calc1, true, 'text', $db_opcao, " onchange='js_pesquisarh27_calc1(false);'");
                                    db_input('rh29_descr', 40, $Irh29_descr, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_calc2 ?>">
                                    <?
                                    $sFunction = '';
                                    if ($db_opcao != 1 && @$rh27_rubric < 1999) {
                                        $sFunction = "js_pesquisarh27_calc2(true);";
                                    }
                                    db_ancora(@$Lrh27_calc2, "\" id='lookupcalc2'\"", $db_opcao);
                                    ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_calc2', 10, $Irh27_calc2, true, 'text', $db_opcao, " onchange='js_pesquisarh27_calc2(false);'");
                                    db_input('rh29_descr', 40, $Irh29_descr, true, 'text', 3, '', "rh29_descr2");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?
                                    db_ancora(@$Lrh27_rhfundamentacaolegal, "js_pesquisaFundamentacaoLegal(true);", $db_opcao);
                                    ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_rhfundamentacaolegal', 10, $Irh27_rhfundamentacaolegal, true, 'text', $db_opcao, " onchange='js_pesquisaFundamentacaoLegal(false);' ondrop='return false'");
                                    db_input('rh137_descricao', 40, $Irh137_descricao, true, 'text', 3);
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td> <b>Tipo:</b> </td>
                                <td>
                                    <?
                                    $aTipo = array(
                                        'n' => 'Nenhum',
                                        'e' => 'Empenho',
                                        'c' => 'Consigna��o',
                                        'p' => 'Pagamento-Extra',
                                        'd' => 'Devolu��o'
                                    );

                                    db_select('tipo', $aTipo, true, $db_opcao, "onChange='js_validaTelaTipo(this.value);'; style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr id='linhaDesdobramento'>
                                <td title="<?= @$Trh23_codele ?>">
                                    <? db_ancora(@$Lrh23_codele, "js_pesquisarh23_codele(true);", $db_opcao); ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh23_codele', 10, $Irh23_codele, true, 'text', $db_opcao, " onchange='js_pesquisarh23_codele(false);'");
                                    db_input('o56_descr', 40, $Io56_descr, true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>

                            <tr id='linhaRetencao'>
                                <td title="<?= @$Trh75_retencaotiporec ?>">
                                    <? db_ancora(@$Lrh75_retencaotiporec, "js_pesquisarh75_retencaotiporec(true);", $db_opcao); ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh75_retencaotiporec', 10, $Irh75_retencaotiporec, true, 'text', $db_opcao, " onchange='js_pesquisarh75_retencaotiporec(false);'");
                                    db_input('e21_descricao', 40, '', true, 'text', 3, '');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_limdat ?>"> <?= @$Lrh27_limdat ?> </td>
                                <td>
                                    <?
                                    $x = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_limdat', $x, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_calc3 ?>"> <?= @$Lrh27_calc3 ?> </td>
                                <td>
                                    <?
                                    $x = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_calc3', $x, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_presta ?>"> <?= @$Lrh27_presta ?> </td>
                                <td>
                                    <?
                                    $x = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_presta', $x, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_propi ?>"> <?= @$Lrh27_propi ?> </td>
                                <td>
                                    <?
                                    $x = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_propi', $x, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_calcp ?>"> <?= @$Lrh27_calcp ?> </td>
                                <td>
                                    <?
                                    $x = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_calcp', $x, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_propq ?>"> <?= @$Lrh27_propq ?> </td>
                                <td>
                                    <?
                                    $x = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_propq', $x, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="1" title="<?php echo $Trh27_complementarautomatica; ?>">
                                    <?php echo $Lrh27_complementarautomatica; ?>
                                </td>
                                <td title="<?php echo $Trh27_complementarautomatica; ?>">
                                    <?php
                                    $aAutomaticaComplementar = array("f" => "NAO", "t" => "SIM");
                                    db_select('rh27_complementarautomatica', $aAutomaticaComplementar, true, $db_opcao, "style='width:125px;'");
                                    ?>
                                </td>
                            </tr>

                        </table>
                    </fieldset>

                    <fieldset id="ctneSocial">
                        <Legend><b>eSocial</b></Legend>
                        <table width="100%">
                            <tr>
                                <td colspan="1" title="">
                                    <b>Natureza da Rubrica
                                    </b>
                                </td>
                                <td title="Natureza da Rubrica">
                                    <?php
                                    $result = $clrubricasesocial->sql_record($clrubricasesocial->sql_query_file(null, "e990_sequencial,e990_descricao", 'e990_sequencial asc'));
                                    $aSelect = array("" => "Selecione");
                                    for ($iCont = 0; $iCont < $clrubricasesocial->numrows; $iCont++) {
                                        $aSelect[db_utils::fieldsMemory($result, $iCont)->e990_sequencial] = db_utils::fieldsMemory($result, $iCont)->e990_sequencial . ' - ' . db_utils::fieldsMemory($result, $iCont)->e990_descricao;
                                    }
                                    db_select('e991_rubricasesocial', $aSelect, true, $db_opcao, "style='width:430;'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=" 1" title="<?php echo $Trh27_codincidprev; ?>">
                                    <?php echo $Lrh27_codincidprev; ?>
                                </td>
                                <td title="<?php echo $Trh27_codincidprev; ?>">
                                    <?php
                                    $result = $oDaoAvaliacaoperguntaopcao->sql_record($oDaoAvaliacaoperguntaopcao->sql_query_file(null, "db104_sequencial,db104_valorresposta||' - '||db104_descricao AS db104_descricao", "db104_valorresposta", "db104_avaliacaopergunta = " . ESOCIAL_OPCAO_PREVSOCIAL));
                                    $aSelect = array("" => "Selecione");
                                    for ($iCont = 0; $iCont < $oDaoAvaliacaoperguntaopcao->numrows; $iCont++) {
                                        $aSelect[db_utils::fieldsMemory($result, $iCont)->db104_sequencial] = db_utils::fieldsMemory($result, $iCont)->db104_descricao;
                                    }
                                    db_select('rh27_codincidprev', $aSelect, true, $db_opcao, "style='width:430;'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" title="<?php echo $Trh27_codincidirrf; ?>">
                                    <?php echo $Lrh27_codincidirrf; ?>
                                </td>
                                <td title="<?php echo $Trh27_codincidirrf; ?>">
                                    <?php
                                    $result = $oDaoAvaliacaoperguntaopcao->sql_record($oDaoAvaliacaoperguntaopcao->sql_query_file(null, "db104_sequencial,db104_valorresposta||' - '||db104_descricao AS db104_descricao", "db104_valorresposta::integer", "db104_avaliacaopergunta = " . ESOCIAL_OPCAO_IRRF));
                                    $aSelect = array("" => "Selecione");
                                    for ($iCont = 0; $iCont < $oDaoAvaliacaoperguntaopcao->numrows; $iCont++) {
                                        $aSelect[db_utils::fieldsMemory($result, $iCont)->db104_sequencial] = db_utils::fieldsMemory($result, $iCont)->db104_descricao;
                                    }
                                    db_select('rh27_codincidirrf', $aSelect, true, $db_opcao, "style='width:430;'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" title="<?php echo $Trh27_codincidfgts; ?>">
                                    <?php echo $Lrh27_codincidfgts; ?>
                                </td>
                                <td title="<?php echo $Trh27_codincidfgts; ?>">
                                    <?php
                                    $result = $oDaoAvaliacaoperguntaopcao->sql_record($oDaoAvaliacaoperguntaopcao->sql_query_file(null, "db104_sequencial,db104_descricao", "db104_valorresposta", "db104_avaliacaopergunta = " . ESOCIAL_OPCAO_FGTS));
                                    $aSelect = array("" => "Selecione");
                                    for ($iCont = 0; $iCont < $oDaoAvaliacaoperguntaopcao->numrows; $iCont++) {
                                        $aSelect[db_utils::fieldsMemory($result, $iCont)->db104_sequencial] = db_utils::fieldsMemory($result, $iCont)->db104_descricao;
                                    }
                                    db_select('rh27_codincidfgts', $aSelect, true, $db_opcao, "style='width:430;'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" title="<?php echo $Trh27_codincidregime; ?>">
                                    <?php echo $Lrh27_codincidregime; ?>
                                </td>
                                <td title="<?php echo $Trh27_codincidregime; ?>">
                                    <?php
                                    $result = $oDaoAvaliacaoperguntaopcao->sql_record($oDaoAvaliacaoperguntaopcao->sql_query_file(null, "db104_sequencial,db104_descricao", "db104_valorresposta", "db104_avaliacaopergunta = " . ESOCIAL_OPCAO_REGIME));
                                    $aSelect = array("" => "Selecione");
                                    for ($iCont = 0; $iCont < $oDaoAvaliacaoperguntaopcao->numrows; $iCont++) {
                                        $aSelect[db_utils::fieldsMemory($result, $iCont)->db104_sequencial] = db_utils::fieldsMemory($result, $iCont)->db104_descricao;
                                    }
                                    db_select('rh27_codincidregime', $aSelect, true, $db_opcao, "style='width:430;'");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" title="<?php echo $Trh27_tetoremun; ?>">
                                    <?php echo $Lrh27_tetoremun; ?>
                                </td>
                                <td title="<?php echo $Trh27_tetoremun; ?>">
                                    <?php
                                    $aTetoRemun = array("" => "Selecione", "f" => "NAO", "t" => "SIM");
                                    db_select('rh27_tetoremun', $aTetoRemun, true, $db_opcao, "style='width:52px;'");
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <fieldset id="ctnFormulas">
                        <Legend> <b>F�rmulas</b> </Legend>
                        <table width="100%">
                            <tr>
                                <td title="<?= @$Trh27_form ?>" width="35%"> <?= @$Lrh27_form ?> </td>
                                <td width="65%">
                                    <?php
                                    db_input('rh27_form', 70, $Irh27_form, true, 'text', $db_opcao, '');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_cond2 ?>" colspan="2" align="center">
                                    <fieldset>
                                        <legend><?= @$Lrh27_cond2 ?></legend>
                                        <?
                                        db_textarea('rh27_cond2', 1, 80, $Irh27_cond2, true, 'text', $db_opcao, '');
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_form2 ?>">
                                    <?= @$Lrh27_form2 ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_form2', 70, $Irh27_form2, true, 'text', $db_opcao, '');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_cond3 ?>" colspan="2" align="center">
                                    <fieldset>
                                        <legend><?= @$Lrh27_cond3 ?></legend>
                                        <?php
                                        db_textarea('rh27_cond3', 1, 80, $Irh27_cond3, true, 'text', $db_opcao, '');
                                        ?>
                                    </fieldset>
                                </td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_form3 ?>">
                                    <?= @$Lrh27_form3 ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_form3', 70, $Irh27_form3, true, 'text', $db_opcao, '');
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td title="<?= @$Trh27_formq ?>">
                                    <?= @$Lrh27_formq ?>
                                </td>
                                <td>
                                    <?
                                    db_input('rh27_formq', 70, $Irh27_formq, true, 'text', $db_opcao, '');
                                    ?>
                                </td>
                            </tr>
                        </table>

                    </fieldset>

                </td>
            </tr>
        </table>

        <? if (!isset($tela_pesquisa)) { ?>
            <input onClick="return js_validarFormulario()" name="<?= ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir")) ?>" type="submit" id="db_opcao" value="<?= ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir")) ?>" <?= ($db_botao == false ? "disabled" : "") ?>>

            <? if ($db_opcao == 1) { ?>
                <input name="importar" type="button" id="importar" value="Importar" onclick="js_pesquisa('1');">
            <?
            }
            ?>
            <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa('');">
        <?
        } else {
        ?>
            <input name="fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_pesquisarubrica.hide();">
        <?
        }
        ?>
    </form>
</div>
<script>
    $('rh27_rhfundamentacaolegal').onpaste = function(event) {

        var self = this;
        return setTimeout(function() {
            var lNumeros = new RegExp(/^[0-9\/]+$/).test(self.value);
            if (!lNumeros) {
                alert('C�digo Fundamenta��o Legal deve ser preenchido somente com n�meros!');
                self.value = '';
            }
        }, 5);
    }

    var oToogleInformacoes = new DBToogle('ctnInformacoes', true);
    var oToogleFormulas = new DBToogle('ctnFormulas', true);
    var oToogleeSocial = new DBToogle('ctneSocial', true);
    /**
     * Vaidacao do formulario
     *
     * @access public
     * @return void
     */
    function js_validarFormulario() {

        /**
         * Rubrica vazia
         */
        if ($('rh27_rubric').value == '') {
            alert('C�digo da rubrica n�o informado.');
            return false;
        }

        if (!js_validarCodigoRubrica()) {
            return false;
        }

        if ($F('rh27_valorpadrao') != '' && !js_validaSomenteNumeros($F('rh27_valorpadrao'))) {
            alert('Valor padr�o deve ser preenchido somente com n�meros decimais.');
            return false;
        }


        if ($F('rh27_quantidadepadrao') != '' && !js_validaSomenteNumeros($F('rh27_quantidadepadrao'))) {
            alert('Quantidade padr�o deve ser preenchido somente com n�meros decimais.');
            return false;
        }

        return true;
    }

    function js_validarCodigoRubrica() {

        require_once("scripts/classes/DBViewFormularioFolha/ValidarCodigoRubrica.js");
        /**
         * Valida se o C�digo da Rubrica foi informado no formato correto.
         */
        var lValidaCodigoRubrica = DBViewFormularioFolha.ValidarCodigoRubrica($('rh27_rubric'));

        /**
         * Rubrica invalida
         */
        if (!lValidaCodigoRubrica) {

            alert('C�digo da rubrica com o formato inv�lido.');
            $('rh27_rubric').value = '';
            return false;
        }

        return true;
    }

    var sTipoRetencao = 1;

    /**
     * js_configSelTipo
     *
     * @param mixed \sPd Description.
     *
     * @access public
     * @return mixed Value.
     */
    function js_configSelTipo(sPd) {

        var oOptNenhum = new Option('Nenhum', 'n');
        var oOptEmpenho = new Option('Empenho', 'e');
        var oOptConsignacao = new Option('Consignacao', 'c');
        var oOptPagExtra = new Option('Pagamento-Extra', 'p');
        var oOptDevolucao = new Option('Devolu��o', 'd');

        $('tipo').options.length = 0;
        $('tipo').add(oOptNenhum, null);
        $('tipo').add(oOptEmpenho, null);

        if (sPd == '1') {
            $('tipo').add(oOptPagExtra, null);
            $('tipo').add(oOptDevolucao, null);
        } else {
            $('tipo').add(oOptConsignacao, null);
        }

        js_validaTelaTipo($('tipo').value);

    }

    function js_validaTelaTipo(sTipo) {

        if (sTipo == 'e') {
            $('linhaDesdobramento').style.display = '';
            $('linhaRetencao').style.display = 'none';
            sTipoRetencao = 1;
        } else if (sTipo == 'c') {
            $('linhaDesdobramento').style.display = 'none';
            $('linhaRetencao').style.display = '';
            sTipoRetencao = 2;
        } else if (sTipo == 'p') {
            $('linhaDesdobramento').style.display = 'none';
            $('linhaRetencao').style.display = '';
            sTipoRetencao = 3;
        } else if (sTipo == 'd') {
            $('linhaDesdobramento').style.display = 'none';
            $('linhaRetencao').style.display = '';
            sTipoRetencao = 4;
        } else {
            $('linhaDesdobramento').style.display = 'none';
            $('linhaRetencao').style.display = 'none';
        }

        document.form1.tipo.value = sTipo;

    }

    function js_pesquisarh75_retencaotiporec(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_retencaotiporec', 'func_retencaotiporec.php?tipo=' + sTipoRetencao + '&funcao_js=parent.js_mostraretencaotiporec1|e21_sequencial|e21_descricao', 'Pesquisa', true);
        } else {
            if (document.form1.rh75_retencaotiporec.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_retencaotiporec', 'func_retencaotiporec.php?tipo=' + sTipoRetencao + '&pesquisa_chave=' + document.form1.rh75_retencaotiporec.value + '&funcao_js=parent.js_mostraretencaotiporec', 'Pesquisa', false, '0');
            } else {
                document.form1.e21_descricao.value = '';
            }
        }
    }

    function js_mostraretencaotiporec(chave, erro) {
        document.form1.e21_descricao.value = chave;
        if (erro == true) {
            document.form1.rh75_retencaotiporec.focus();
            document.form1.rh75_retencaotiporec.value = '';
        }
    }

    function js_mostraretencaotiporec1(chave1, chave2) {
        document.form1.rh75_retencaotiporec.value = chave1;
        document.form1.e21_descricao.value = chave2;
        db_iframe_retencaotiporec.hide();
    }




    function js_pesquisarh27_calc1(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_rhtipomedia', 'func_rhtipomedia.php?funcao_js=parent.js_mostrarhtipomedia1|rh29_tipo|rh29_descr', 'Pesquisa', true, '0');
        } else {
            if (document.form1.rh27_calc1.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_rhtipomedia', 'func_rhtipomedia.php?pesquisa_chave=' + document.form1.rh27_calc1.value + '&funcao_js=parent.js_mostrarhtipomedia', 'Pesquisa', false, '0');
            } else {
                document.form1.rh29_descr.value = '';
            }
        }
    }

    function js_mostrarhtipomedia(chave, erro) {
        document.form1.rh29_descr.value = chave;
        if (erro == true) {
            document.form1.rh27_calc1.focus();
            document.form1.rh27_calc1.value = '';
        }
    }

    function js_mostrarhtipomedia1(chave1, chave2) {
        document.form1.rh27_calc1.value = chave1;
        document.form1.rh29_descr.value = chave2;
        db_iframe_rhtipomedia.hide();
    }

    function js_pesquisarh27_calc2(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_rhtipomedia', 'func_rhtipomedia.php?funcao_js=parent.js_mostrarhtipomedia3|rh29_tipo|rh29_descr', 'Pesquisa', true, '0');
        } else {
            if (document.form1.rh27_calc2.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_rhtipomedia', 'func_rhtipomedia.php?pesquisa_chave=' + document.form1.rh27_calc2.value + '&funcao_js=parent.js_mostrarhtipomedia2', 'Pesquisa', false, '0');
            } else {
                document.form1.rh29_descr2.value = '';
            }
        }
    }

    function js_mostrarhtipomedia2(chave, erro) {
        document.form1.rh29_descr2.value = chave;
        if (erro == true) {
            document.form1.rh27_calc2.focus();
            document.form1.rh27_calc2.value = '';
        }
    }

    function js_mostrarhtipomedia3(chave1, chave2) {
        document.form1.rh27_calc2.value = chave1;
        document.form1.rh29_descr2.value = chave2;
        db_iframe_rhtipomedia.hide();
    }

    function js_pesquisa(opcao) {
        js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_rhrubricas', 'func_rhrubricas.php?funcao_js=parent.js_preenchepesquisa' + opcao + '|rh27_rubric&instit=<?= db_getsession("DB_instit") ?>', 'Pesquisa', true, '0');
    }

    function js_preenchepesquisa(chave) {
        db_iframe_rhrubricas.hide();
        <?
        if ($db_opcao != 1) {
            echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
        }
        ?>
    }

    function js_preenchepesquisa1(chave) {
        db_iframe_rhrubricas.hide();
        location.href = "pes1_rhrubricas004.php?importar=" + chave;
    }

    function js_pesquisarh23_codele(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_orcelemento', 'func_rhelementoemp.php?funcao_js=parent.js_mostraorcelemento1|rh38_codele|o56_descr', 'Pesquisa', true, '0');
        } else {
            if (document.form1.rh23_codele.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_orcelemento', 'func_rhelementoemp.php?pesquisa_chave=' + document.form1.rh23_codele.value + '&funcao_js=parent.js_mostraorcelemento', 'Pesquisa', false, '0');
            } else {
                document.form1.o56_descr.value = '';
            }
        }
    }

    function js_mostraorcelemento(chave, erro) {
        document.form1.o56_descr.value = chave;
        if (erro == true) {
            document.form1.rh23_codele.focus();
            document.form1.rh23_codele.value = '';
        }
    }

    function js_mostraorcelemento1(chave1, chave2) {
        document.form1.rh23_codele.value = chave1;
        document.form1.o56_descr.value = chave2;
        db_iframe_orcelemento.hide();
    }

    function js_pesquisaFundamentacaoLegal(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_fundamentacao', 'func_rhfundamentacaolegal.php?funcao_js=parent.js_mostrafundamentacao1|rh137_sequencial|rh137_numero|rh137_descricao', 'Pesquisa', true, '0');
        } else {
            if (document.form1.rh27_rhfundamentacaolegal.value != '') {
                js_OpenJanelaIframe('top.corpo.iframe_rhrubricas', 'db_iframe_fundamentacao', 'func_rhfundamentacaolegal.php?pesquisa_chave=' + $F('rh27_rhfundamentacaolegal') + '&funcao_js=parent.js_mostrafundamentacao', 'Pesquisa', false, '0');
            } else {
                document.form1.rh137_descricao.value = '';
            }
        }
    }

    function js_mostrafundamentacao(chave1, chave2, erro) {

        var descricao = chave1;
        if (chave2 != null) {
            descricao += ' - ' + chave2;
        }

        document.form1.rh137_descricao.value = descricao;
        if (erro == true) {
            document.form1.rh27_rhfundamentacaolegal.focus();
            document.form1.rh27_rhfundamentacaolegal.value = '';
        }
    }

    function js_mostrafundamentacao1(chave1, chave2, chave3) {
        document.form1.rh27_rhfundamentacaolegal.value = chave1;
        document.form1.rh137_descricao.value = chave2 + ' - ' + chave3;
        db_iframe_fundamentacao.hide();
    }

    function js_bloqueiaMedias(obj) {

        if (new Number(obj.value) > 1999) {
            document.getElementById("lookupcalc1").onclick = 'return false;';
            document.getElementById("rh27_calc1").readOnly = true;
            document.getElementById("rh27_calc1").style.backgroundColor = "#DEB887;";
            document.getElementById("lookupcalc2").onclick = 'return false;';
            document.getElementById("rh27_calc2").readOnly = true;
            document.getElementById("rh27_calc2").style.backgroundColor = "#DEB887;"

        } else {

            document.getElementById("rh27_calc1").readOnly = false;
            document.getElementById("lookupcalc1").onclick = function(event) {
                js_pesquisarh27_calc1(true);
            }
            document.getElementById("rh27_calc1").style.backgroundColor = '#FFFFFF';
            document.getElementById("rh27_calc2").readOnly = false;
            document.getElementById("lookupcalc2").onclick = function(event) {
                js_pesquisarh27_calc2(true);
            }
            document.getElementById("rh27_calc2").style.backgroundColor = '#FFFFFF';

        }
    }
    <?
    if ($db_opcao != 3  && $db_opcao != 33 && $db_opcao != 22) {
        echo "js_bloqueiaMedias(document.form1.rh27_rubric);";
    }

    echo "js_configSelTipo(document.form1.rh27_pd.value);";

    if (isset($rh23_codele) && trim($rh23_codele) != '') {
        echo "js_validaTelaTipo('e');";
    } else if (isset($rh75_retencaotiporec) && trim($rh75_retencaotiporec) != '' && $e21_retencaotiporecgrupo == 2) {
        echo "js_validaTelaTipo('c');";
    } else if (isset($rh75_retencaotiporec) && trim($rh75_retencaotiporec) != '' && $e21_retencaotiporecgrupo == 3) {
        echo "js_validaTelaTipo('p');";
    } else if (isset($rh75_retencaotiporec) && trim($rh75_retencaotiporec) != '' && $e21_retencaotiporecgrupo == 4) {
        echo "js_validaTelaTipo('d');";
    } else {
        echo "js_validaTelaTipo('n');";
    }

    ?>
    $('e991_rubricasesocialdescr').style.width = '372px';
</script>