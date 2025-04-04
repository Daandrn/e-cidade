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
include("dbforms/db_classesgenericas.php");
include("classes/db_liclancedital_classe.php");
include("classes/db_liclicitemlote_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clliclicitemlote        = new cl_liclicitemlote;
$cliframe_seleciona_lote = new cl_iframe_seleciona;
$clliclancedital   = new cl_liclancedital;

$clliclicitemlote->rotulo->label();

$erro_msg  = "";

if (!isset($db_tranca) && trim(@$db_tranca) == "") {
    $db_tranca = 1;
}

if (!isset($flag_auto) && trim(@$flag_auto) == "") {
    $flag_auto = false;  // Inicializado antes do submit
}

if (isset($incluir) && trim($incluir) != "") {
    $vetor_itens = split(",", $l04_liclicitem);
    $sqlerro     = false;
    $flag_auto   = false;
    $auto_descr  = "";

    //     db_msgbox("Antes ".$db_tranca);

    db_inicio_transacao();

    // Verifica descricao vazia
    if (
        isset($l04_descricao)      &&
        trim($l04_descricao) == "" &&
        substr(trim($l04_descricao), 0, 9) != "AUTO_LOTE"
    ) {
        $res_licitacao = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null, "substr(l04_descricao,11,5) as sequencial", "l04_codigo desc limit 1", "l21_codliclicita = $licitacao"));
        if ($clliclicitemlote->numrows > 0) {
            db_fieldsmemory($res_licitacao, 0);

            if (trim($sequencial) == "") {
                $flag_auto = true;  // Caso, positivo gera descricao automaticamente
            }
        }
    } else {
        if (substr(trim($l04_descricao), 0, 9) == "AUTO_LOTE") {  // Verifica se usuario nao digitou AUTO_LOTE(tentativa de gerar lote automatico)
            $res_licitacao = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null, "substr(l04_descricao,11,5) as sequencial", "l04_codigo desc limit 1", "l21_codliclicita = $licitacao and l04_descricao like 'AUTO_LOTE_%'"));
            if ($clliclicitemlote->numrows == 0) {
                $erro_msg  = "Descri�ao inv�lida.";
                $sqlerro   = true;
            } else {
                if ($db_tranca == 3) {
                    $flag_auto = true;
                } else {
                    $erro_msg  = "Descri��o inv�lida!";
                    $sqlerro   = true;
                }
            }
        } else {
            $res_licitacao = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null, "substr(l04_descricao,11,5) as sequencial", "l04_codigo desc limit 1", "l21_codliclicita = $licitacao and l04_descricao like '$l04_descricao'"));

            if ($clliclicitemlote->numrows > 0) {
                $erro_msg = "Descri��o j� cadastrada.";
                $sqlerro  = true;
            }

            if ($sqlerro == false) {
                $res_licitacao = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null, "substr(l04_descricao,11,5) as sequencial", "l04_codigo desc limit 1", "l21_codliclicita = $licitacao and l04_descricao like 'AUTO_LOTE_%'"));

                if ($clliclicitemlote->numrows > 0) {
                    $erro_msg  = 'Foram gerados descri��es autom�ticas.\nDeixe a descri��o vazia para gerar automaticamente.';
                    $sqlerro   = true;
                }
            }
        }
    }

    if ($flag_auto == false) {
        if (isset($l04_descricao) && trim($l04_descricao) != "") {
            $clliclicitemlote->l04_descricao = strtoupper(trim($l04_descricao));
        } else {
            $erro_msg = "Falta a descri��o do lote.";
            $sqlerro  = true;
        }
    }

    if ($flag_auto == true) {
        $auto_descr    = "AUTO_LOTE_";
        $sequencial    = 1;

        $res_licitacao = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null, "substr(l04_descricao,11,5) as sequencial", "l04_codigo desc limit 1", "l21_codliclicita = $licitacao and l04_descricao like 'AUTO_LOTE_%'"));
        if ($clliclicitemlote->numrows > 0) {
            db_fieldsmemory($res_licitacao, 0);
            $sequencial = intval($sequencial) + 1;
        }

        $auto_descr   .= db_formatar($sequencial, "s", "0", 5, "e", 0);
    }

    if ($sqlerro == false) {
        $clliclicitemlote->l04_descricao = strtoupper(trim($l04_descricao));
        $sequenciallote = 0;
        $clliclicitemlote->l04_numerolote = null;
        for ($i = 0; $i < sizeof($vetor_itens); $i++) {
            $clliclicitemlote->l04_liclicitem = trim($vetor_itens[$i]);

            if ($flag_auto == true) {
                $l04_descricao = $auto_descr;
                $clliclicitemlote->l04_descricao = strtoupper(trim($l04_descricao));
            }
            $sequenciallote++;
            $clliclicitemlote->l04_seq = $sequenciallote;
            $clliclicitemlote->incluir(null);

            if ($clliclicitemlote->erro_status == 0) {
                $erro_msg = $clliclicitemlote->erro_msg;
                $sqlerro  = true;
                break;
            }
        }
    }

    if ($sqlerro == false) {
        $erro_msg   = $clliclicitemlote->erro_msg;
        $l04_codigo = $clliclicitemlote->l04_codigo;

        if ($flag_auto == true) {
            if (isset($auto_descr) && trim($auto_descr) != "") {
                $db_tranca = 3;
            }
            $l04_descricao = "AUTO_LOTE_" . db_formatar(($sequencial + 1), "s", "0", 5, "e", 0);
        }
    }

    db_fim_transacao($sqlerro);

    //     db_msgbox("Depois ".$db_tranca);
}
?>
<html>

<head>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <center>
        <?
        if (isset($licitacao) && trim($licitacao) != "") {
            $sql_marca    = "";
            $campos       = "l21_codigo,pc81_codprocitem,pc01_codmater,pc01_descrmater,pc11_quant,si02_vlprecoreferencia,l04_codigo,l04_descricao,l21_reservado";

            $sql          = $clliclicitemlote->sql_query_licitacao(null, "distinct $campos", "pc81_codprocitem", "l21_codliclicita = $licitacao and l21_situacao = 0");

            $sql_disabled = $clliclicitemlote->sql_query_licitacao(null, "distinct $campos", "pc81_codprocitem", "l21_codliclicita = $licitacao and l21_situacao = 0 and l04_codigo is not null");

            $res_itenslote_desab = $clliclicitemlote->sql_record($sql_disabled);
            $numrows_desab       = $clliclicitemlote->numrows;
            $res_itenslote       = $clliclicitemlote->sql_record($sql);
            $numrows             = $clliclicitemlote->numrows;

            $cliframe_seleciona_lote->campos    = $campos;
            $cliframe_seleciona_lote->legenda   = "";
            $cliframe_seleciona_lote->sql       = $sql;
            $cliframe_seleciona_lote->sql_disabled = $sql_disabled;
            $cliframe_seleciona_lote->sql_marca = $sql_marca;
            $cliframe_seleciona_lote->iframe_height = "300";
            $cliframe_seleciona_lote->iframe_width  = "600";
            $cliframe_seleciona_lote->iframe_nome   = "lote";
            $cliframe_seleciona_lote->chaves        = "l21_codigo";
            $cliframe_seleciona_lote->js_marcador   = "";
            $cliframe_seleciona_lote->dbscript      = "";
            $cliframe_seleciona_lote->iframe_seleciona(1);

            if ($numrows == $numrows_desab) {
                $db_opcao  = 3;
                $db_tranca = 3;
            } else {
                $db_opcao = 1;
            }

            $db_opcao = $db_tranca;
        ?>
            <form name="form2" method="post" action="lic1_liclicitemlotenovo.php">
                <table border="0" cellspacing="2" cellpadding="0" align="center">
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <?
                    db_input("licitacao", 10, "", true, "hidden", 3);
                    db_input("l04_liclicitem", 50, "", true, "hidden", 3);
                    db_input("db_tranca", 1, "", true, "hidden", 3);
                    ?>
                    <tr>
                        <td nowrap align="right" title="<?= @$Tl04_codigo ?>"><?= @$Ll04_codigo ?></td>
                        <td nowrap align="left">
                            <?
                            db_input("l04_codigo", 10, $Il04_codigo, true, "text", 3);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap align="right" title="<?= @$Tl04_descricao ?>"><?= @$Ll04_descricao ?></td>
                        <td nowrap align="left">
                            <?
                            //db_input("l04_descricao", 40, $Il04_descricao, true, "text", $db_opcao);
                            db_textarea('l04_descricao', 4, 40, $Il04_descricao, true, 'text', $db_opcao, "onmouseout = 'oganizarTexto();'", "", "", 250);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td nowrap align="center" colspan="2">
                            <input type="submit" name="incluir" value="Incluir" onClick="return js_valida_dados();">
                            <input type="button" name="fechar" value="Fechar" onClick="js_fechar();">
                        </td>
                    </tr>
                </table>
                <script>
                    var input = document.querySelector('#l04_descricao');

                    function log(e) {

                        var regex = /[*|\":<>[\]{}`\\()';#@&$]/;
                        var key = String.fromCharCode(e.keyCode);

                        if (regex.test(key)) {
                            e.preventDefault();
                            return false;
                        }
                        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
                        if (keyCode == 13) {


                            if (!e) var e = window.event;

                            e.cancelBubble = true;
                            e.returnValue = false;

                            if (e.stopPropagation) {
                                e.stopPropagation();
                                e.preventDefault();
                            }
                        }
                    };

                    input.addEventListener('keypress', log);

                    function js_valida_dados() {
                        var contador = 0;
                        /*
                               if (document.form2.l04_descricao.value == ""){
                                    alert("Falta a descricao do lote");
                                    document.form2.l04_descricao.select();
                                    document.form2.l04_descricao.style.backgroundColor='#99A9AE';
                                    document.form2.l04_descricao.focus();
                                    return false;
                               }
                        */
                        var lista_itens = "";
                        var separador = "";
                        for (i = 0; i < lote.document.form1.elements.length; i++) {
                            console.log(lote.document.form1.elements[i]);
                            console.log(lote.document.form1.elements[i].checked);
                            if (lote.document.form1.elements[i].type == "checkbox") {
                                if (lote.document.form1.elements[i].checked == true) {
                                    lista_itens += separador + lote.document.form1.elements[i].value;
                                    separador = ", ";
                                    contador++;
                                }
                            }
                        }
                        if (contador == 0) {
                            alert("Selecione um item.");
                            return false;
                        } else {
                            document.form2.l04_liclicitem.value = lista_itens;
                        }

                        document.form2.submit();
                        return true;
                    }

                    function js_fechar() {
                        parent.db_iframe_lotenovo.hide();
                        parent.itens_lote.location.href = 'lic1_liclicitemlote011.php?licitacao=<?= $licitacao ?>';
                    }

                    function oganizarTexto() {

                        var texto = document.getElementById("l04_descricao").value;
                        const myArr = texto.split("\n");
                        var correcao = "";
                        if (myArr.length > 0) {
                            for (i = 0; i < myArr.length; i++) {
                                if (i == 0) {
                                    correcao = myArr[i];
                                } else {
                                    correcao += " " + myArr[i];
                                }

                            }
                            document.getElementById("l04_descricao").value = correcao;
                        }

                    }
                </script>
    </center>
    </form>
</body>

</html>
<?
        }

        if (isset($db_opcao) && $db_opcao == 3) {
            if ($numrows == 0 && trim(@$incluir) == "") {
                $erro_msg = "Nenhum item cadastrado para esta licita��o.";
                echo "<script>
                   document.form2.incluir.disabled = true;
                </script>";
            } else {
                if ($numrows == $numrows_desab) {
                    $erro_msg = 'Todos os itens desta licita��o j� possuem lote.\nPara incluir novo lote, excluir lote(s).';
                    echo "<script>
                        document.form2.incluir.disabled = true;
                     </script>";
                }
            }
        }

        if (isset($erro_msg) && trim($erro_msg) != "") {

            /**
             * Se todos os itens foram vinculados a algum lote redireciona para a rotina de editais
             */

            $sSqlCodigos = "SELECT distinct l21_codigo FROM liclicitem WHERE l21_codliclicita = $licitacao";
            $rsCodigos   = db_query($sSqlCodigos);

            $sSqlLotes = "SELECT distinct liclicitemlote.*
                    FROM liclicitemlote
                    WHERE l04_liclicitem IN
                         (SELECT l21_codigo
                         FROM liclicitem
                         WHERE l21_codliclicita = $licitacao)";
            $rsLotes = db_query($sSqlLotes);

            $sSql = $clliclancedital->sql_query('', 'l20_naturezaobjeto, l20_nroedital, l47_sequencial', '', 'l20_codigo = ' . $licitacao);
            $rsSql = $clliclancedital->sql_record($sSql);

            $natureza_objeto = db_utils::fieldsMemory($rsSql, 0)->l20_naturezaobjeto;
            //db_criatabela($rsSql);exit;

            $sqlEditalRedireciona = "
                select 
                    l03_pctipocompratribunal, 
                    l20_tipojulg
                from 
                        liclicita
                    inner join cflicita 
                        on l20_codtipocom = l03_codigo
                where l20_codigo = $licitacao";

            $rsEditalRedireciona = db_query($sqlEditalRedireciona);
            $editalRedireciona = db_utils::fieldsMemory($rsEditalRedireciona, 0);
                        
            $tipojulgamento = $editalRedireciona->l20_tipojulg;
            $codtribunal = $editalRedireciona->l03_pctipocompratribunal;

            if (!$sqlerro && $incluir && pg_numrows($rsLotes) == pg_numrows($rsCodigos)) {

                // Regras para redirecionamento
                // Se (l20_tipojulg == 3) AND
                // Modalidade in (1 => "Dispensa", 2 => "Dispensa por Chamada P�blica", 3 => "Inexigibilidade por credenciamento" ou 4 =>"Inexigibilidade") AND
                // Codigo do Tribunal in (100,101,102 e 103)
                $codigosTribunal = [100,101,102,103];
                if ($tipojulgamento == 3 && in_array($codtribunal, $codigosTribunal)) {
                    echo "<script> parent.parent.window.location.href='lic4_editalabas.php?licitacao=$licitacao';</script>";
                }
            }

            echo "<script>
              document.form2.l04_descricao.select();
              document.form2.l04_descricao.focus();
           </script>";
        }
?>