<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_coremp_classe.php");
require_once("classes/db_pagordemnota_classe.php");
require_once("classes/db_empelemento_classe.php");
include ("libs/db_liborcamento.php");

$iAnoUsoSessao      = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");
$oPost               = db_utils::postMemory($_POST);
$clselorcdotacao = new cl_selorcdotacao();

$clselorcdotacao->setDados($oPost->filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$sele_work = $clselorcdotacao->getDados(false);

$sele_desdobramentos="";
$desdobramentos = $clselorcdotacao->getDesdobramento(); // coloca os codele dos desdobramntos no formato (x,y,z)
if ($desdobramentos != "") {
    $sele_desdobramentos = " and o56_codele in ".$desdobramentos; // adiciona desdobramentos
}

$dtDataInicialBanco = implode("-", array_reverse(explode("/", $oPost->dtDataInicial)));
$dtDataFinalBanco   = implode("-", array_reverse(explode("/", $oPost->dtDataFinal)));
$oPost->lQuebraConta == "t" ? $oPost->lQuebraConta = true : $oPost->lQuebraConta = false;
$oPost->lQuebraCredor == "t" ? $oPost->lQuebraCredor = true : $oPost->lQuebraCredor = false;
$oPost->lQuebraRecurso == "t" ? $oPost->lQuebraRecurso = true : $oPost->lQuebraRecurso = false;
$oPost->lQuebraAcordo == "t" ? $oPost->lQuebraAcordo = true : $oPost->lQuebraAcordo = false;

$aOrderBy    = array();
$aGroup_by   = array();
$aWhere      = array();
$aWhereConta = array();

function tipoOrdemEmpenho($lQuebraConta,$lQuebraCredor,$lQuebraAcordo){

      if (($lQuebraConta == 't') && ($lQuebraCredor == 't')) {
        $filtros = quebraPorContaCredor();
        $aGroup_by = $filtros[0];
        $aOrderBy = $filtros[1];
        return array($aGroup_by,$aOrderBy);
      }   

      if ($lQuebraCredor == 't'){
        $filtros = quebraPorCredor();
        $aGroup_by = $filtros[0];
        $aOrderBy = $filtros[1];
        return array($aGroup_by,$aOrderBy);  
        }
      
      if ($lQuebraAcordo == 't'){
        $filtros = quebraPorAcordo();
        $aGroup_by = $filtros[0];
        $aOrderBy = $filtros[1];
        return array($aGroup_by,$aOrderBy); 
      }
      $filtros  = semFiltros();
      $aGroup_by = $filtros[0];
      $aOrderBy = $filtros[1];
      return array($aGroup_by,$aOrderBy);
}
function tipoOrdemAutenticacao(){
  $aOrderBy[] = "ORDER BY k12_data,
                  tipo,
                  k12_autent";
  return array($aOrderBy);
}
function quebraPorContaCredor(){
      $aGroup_by[] = "GROUP BY e60_numcgm,
                      todo.z01_nome,
                      k13_conta,
                      tipo,
                      e60_numemp,
                      todo.k12_empen,
                      todo.e60_codemp,
                      todo.e60_anousu,
                      todo.e50_codord,
                      todo.k12_valor,
                      todo.k12_cheque,
                      todo.k12_autent,
                      todo.k12_data,
                      todo.o15_codtri,
                      todo.k13_descr,
                      todo.k106_sequencial, todo.e60_coddot, todo.e60_resumo, todo.ac16_sequencial,todo.ac16_anousu, todo.ac16_numero ";
      $aOrderBy[] = " ORDER BY k12_data,
                      e60_numcgm,
                      tipo,
                      k13_conta,
                      todo.o15_codtri,
                      e60_codemp ";
      return array($aGroup_by,$aOrderBy);                
}
function quebraPorAcordo(){
      $aGroup_by[] = "GROUP BY e60_numcgm,
                      todo.z01_nome,
                      k13_conta,
                      tipo,
                      e60_numemp,
                      todo.k12_empen,
                      todo.e60_codemp,
                      todo.e60_anousu,
                      todo.e50_codord,
                      todo.k12_valor,
                      todo.k12_cheque,
                      todo.k12_autent,
                      todo.k12_data,
                      todo.o15_codtri,
                      todo.k13_descr,
                      todo.k106_sequencial,todo.e60_coddot, todo.e60_resumo, todo.ac16_sequencial, todo.ac16_anousu, todo.ac16_numero";
      $aOrderBy[] = " ORDER BY todo.ac16_numero ";          
      return array($aGroup_by,$aOrderBy);   
}
function quebraPorCredor(){
      $aGroup_by[] = "GROUP BY e60_numcgm,
                      todo.z01_nome,
                      k13_conta,
                      tipo,
                      e60_numemp,
                      todo.k12_empen,
                      todo.e60_codemp,
                      todo.e60_anousu,
                      todo.e50_codord,
                      todo.k12_valor,
                      todo.k12_cheque,
                      todo.k12_autent,
                      todo.k12_data,
                      todo.o15_codtri,
                      todo.k13_descr,
                      todo.k106_sequencial,todo.e60_coddot, todo.e60_resumo, todo.ac16_sequencial, todo.ac16_anousu, todo.ac16_numero";
      $aOrderBy[] = " ORDER BY k12_data,
                      e60_numcgm,
                      o15_codtri,
                      tipo,
                      k13_conta,
                      e60_codemp ";
    return array($aGroup_by,$aOrderBy);                 
}
function semFiltros(){
      $aGroup_by[] = '';
      $aOrderBy[] = "ORDER BY k12_data,
                    tipo,
                    e60_codemp ";
      return array($aGroup_by,$aOrderBy);             
}

function validarDatas($dtDataInicial, $dtDataFinal, $dtDataInicialBanco, $dtDataFinalBanco, $sWhereContaPagadora, $iInstituicaoSessao){
      if ( !empty($dtDataInicial) && !empty($dtDataFinal) ) {
        $aWhere[] = "k12_data between '{$dtDataInicialBanco}' and '{$dtDataFinalBanco}'";
        $head5    = "Ordem de {$dtDataInicial} at� {$dtDataFinal}";
        return array($aWhere,$head5); 
      }
      if (!empty($dtDataInicial)) {
        $aWhere[] = "k12_data >= '{$dtDataInicialBanco}'";
        $head5    = "Ordem a partir de: {$dtDataInicial}"; 
        return array($aWhere,$head5);
      }
      if (!empty($dtDataFinal)) {
        $aWhere[] = "k12_data <= '{$dtDataFinalBanco}'";
        $head5    = "Ordem at�: {$dtDataInicial}";
        return array($aWhere,$head5);
      }
      $sSqlBuscaDataEmp   = "  select max(coremp.k12_data) as maior,                                    ";
      $sSqlBuscaDataEmp  .= "         min(coremp.k12_data) as menor                                     ";
      $sSqlBuscaDataEmp  .= "    from coremp                                                            ";
      $sSqlBuscaDataEmp  .= "         inner join corrente   on corrente.k12_id     = coremp.k12_id      ";
      $sSqlBuscaDataEmp  .= "                              and corrente.k12_data   = coremp.k12_data    ";
      $sSqlBuscaDataEmp  .= "                              and corrente.k12_autent = coremp.k12_autent  ";
      $sSqlBuscaDataEmp  .= "                              and k12_instit = {$iInstituicaoSessao}       ";
      $sSqlBuscaDataEmp  .= "         inner join saltes     on saltes.k13_conta = corrente.k12_conta    ";
      $sSqlBuscaDataEmp  .= "   where {$sWhereContaPagadora}                                            ";
      $rsBuscaData        = db_query($sSqlBuscaDataEmp);
      $oDadosData         = db_utils::fieldsMemory($rsBuscaData, 0);
      $dtDataInicialBanco = $oDadosData->maior;
      $dtDataFinalBanco   = $oDadosData->menor;
      $head5 = "Ordem de {$dtDataInicial} at� {$dtDataFinal}"; 
      return array($sSqlBuscaDataEmp,$head5,$dtDataInicialBanco,$dtDataFinalBanco); 
}

$validarDatas = validarDatas($dtDataInicial, $dtDataFinal, $dtDataInicialBanco, $dtDataFinalBanco, $sWhereContaPagadora, $iInstituicaoSessao);

if ($oPost->sTipoOrdem == "empenho"){
     $tipoOrdem = tipoOrdemEmpenho($lQuebraConta,$lQuebraCredor,$lQuebraAcordo);
     $aWhere[]     = $validarDatas[0];
     $head5[]      = $validarDatas[1];
     $aGroup_by[]  = $tipoOrdem[0];
     $aOrderBy[]   = $tipoOrdem[1];
}
if ($oPost->sTipoOrdem == "autenticacao"){  
     $tipoOrdem = tipoOrdemAutenticacao();
     $aWhere[]     = $validarDatas[0];
     $head5[]      = $validarDatas[1];
     $aOrderBy[]   = $tipoOrdem[0];
} 

$sWhereContaPagadora = "";
if (!empty($oPost->sContasSelecionadas)) {
  $sWhereContaPagadora = "k13_conta in ({$oPost->sContasSelecionadas})";
  $aWhere[]            = $sWhereContaPagadora;
}

if (!empty($oPost->sCredoresSelecionados)) {
  $aWhere[] = "e60_numcgm in ({$oPost->sCredoresSelecionados})";
}

$sWhereRecursosSelecionados = "";
if (!empty($oPost->sRecursosSelecionados))  {
  $oPost->sRecursosSelecionados = strtr($oPost->sRecursosSelecionados, "*", "'");
  $sWhereRecursosSelecionados = "o15_codtri in ({$oPost->sRecursosSelecionados})";
  $aWhere[]                   = $sWhereRecursosSelecionados;
 
}

$sWhereAcordosSelecionados = "";
if (!empty($oPost->sAcordosSelecionados))  {
  $sWhereAcordosSelecionados = "todo.ac16_sequencial in ({$oPost->sAcordosSelecionados})";
  $aWhere[]                   = $sWhereAcordosSelecionados;
}

$sWhereEmpenho = "";
if ($oPost->iListaEmpenho == 0) {
  $sWhereEmpenho = '1 = 1';
} else {

  if ($oPost->iListaEmpenho == 1) {
    $sWhereEmpenho = "tipo = 'Emp'";
  }elseif($oPost->iListaEmpenho == 3){
    $sWhereEmpenho = "tipo = 'EXT'";
  }
  else {
    $sWhereEmpenho = "tipo = 'RP'";
  }
}

if (!empty($oPost->iTipoBaixa) && $oPost->iTipoBaixa == 2) {
  $aWhere[] = "k106_sequencial <> 2";
} elseif (!empty($oPost->iTipoBaixa) && $oPost->iTipoBaixa == 3) {
  $aWhere[] = "k106_sequencial = 2";
}
$sImplodeWhere     = implode(" AND ", $aWhere[0]);
$sImplodeOrderBy   = implode(", ", $aOrderBy[0]);
$sImplodeGroupBy   = implode(", ", $aGroup_by[0]);
$sSqlBuscaEmpenhos  = "  SELECT * FROM                                                                                                                                                      ";
$sSqlBuscaEmpenhos .= "    (SELECT coremp.k12_empen,                                                          																				";
$sSqlBuscaEmpenhos .= "            e60_numemp,                                                          																					";
$sSqlBuscaEmpenhos .= "            e60_codemp,                                                          																					";
$sSqlBuscaEmpenhos .= "            CASE                                                          																							";
$sSqlBuscaEmpenhos .= "                WHEN e49_numcgm IS NULL THEN e60_numcgm                                                          													";
$sSqlBuscaEmpenhos .= "                ELSE e49_numcgm                                                          																			";
$sSqlBuscaEmpenhos .= "            END AS e60_numcgm,                                                          																				";
$sSqlBuscaEmpenhos .= "            k12_codord AS e50_codord,                                                          																		";
$sSqlBuscaEmpenhos .= "            CASE                                                          																							";
$sSqlBuscaEmpenhos .= "                WHEN e49_numcgm IS NULL THEN cgm.z01_nome                                                          													";
$sSqlBuscaEmpenhos .= "                ELSE cgmordem.z01_nome                                                          																		";
$sSqlBuscaEmpenhos .= "            END AS z01_nome,                                                          																				";
$sSqlBuscaEmpenhos .= "            k12_valor,                                                          																						";
$sSqlBuscaEmpenhos .= "            k12_cheque,                                                          																					";
$sSqlBuscaEmpenhos .= "            e60_anousu,                                                          																					";
$sSqlBuscaEmpenhos .= "            coremp.k12_autent,                                                          																				";
$sSqlBuscaEmpenhos .= "            coremp.k12_data,                                                          																				";
$sSqlBuscaEmpenhos .= "            k13_conta,                                                          																						";
$sSqlBuscaEmpenhos .= "            o15_codtri,                                                          																					";
$sSqlBuscaEmpenhos .= "            k13_descr,                                                          																						";
$sSqlBuscaEmpenhos .= "            CASE                                                          																							";
$sSqlBuscaEmpenhos .= "                WHEN e60_anousu < {$iAnoUsoSessao} THEN 'RP'                                                          												";
$sSqlBuscaEmpenhos .= "                ELSE 'Emp'                                                          																					";
$sSqlBuscaEmpenhos .= "            END AS tipo,                                                          																					";
$sSqlBuscaEmpenhos .= "            k106_sequencial, e60_coddot, e60_resumo, ac16_sequencial , ac16_anousu, ac16_numero                                                       																				";
$sSqlBuscaEmpenhos .= "     FROM coremp                                                          																							";
$sSqlBuscaEmpenhos .= "     INNER JOIN empempenho ON e60_numemp = k12_empen AND e60_instit = {$iInstituicaoSessao}                                                      					";
$sSqlBuscaEmpenhos .= "     INNER JOIN empelemento ON e60_numemp = e64_numemp                                                      					";
$sSqlBuscaEmpenhos .= "     INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu                                                                       ";
$sSqlBuscaEmpenhos .= "     INNER JOIN orcelemento ON e64_codele = o56_codele                                                          							";
$sSqlBuscaEmpenhos .= "     INNER JOIN orctiporec ON o58_codigo = o15_codigo                                                          														";
$sSqlBuscaEmpenhos .= "     INNER JOIN pagordem ON e50_codord = k12_codord                                                          														";
$sSqlBuscaEmpenhos .= "     LEFT  JOIN pagordemconta ON e50_codord = e49_codord                                                          													";
$sSqlBuscaEmpenhos .= "     INNER JOIN corrente ON corrente.k12_id = coremp.k12_id AND corrente.k12_data = coremp.k12_data AND corrente.k12_autent = coremp.k12_autent  					";
$sSqlBuscaEmpenhos .= "     INNER JOIN cgm ON cgm.z01_numcgm = e60_numcgm                                                          															";
$sSqlBuscaEmpenhos .= "     LEFT  JOIN cgm cgmordem ON cgmordem.z01_numcgm = e49_numcgm                                                          											";
$sSqlBuscaEmpenhos .= "     INNER JOIN saltes ON saltes.k13_conta = corrente.k12_conta                                                          											";
$sSqlBuscaEmpenhos .= "     LEFT  JOIN corgrupocorrente ON k105_id = corrente.k12_id AND k105_data = corrente.k12_data AND k105_autent = corrente.k12_autent            					";
$sSqlBuscaEmpenhos .= "     LEFT  JOIN corgrupotipo ON k106_sequencial = k105_corgrupotipo                                                                              ";
$sSqlBuscaEmpenhos .= "      LEFT JOIN empempenhocontrato on  empempenho.e60_numemp = empempenhocontrato.e100_numemp    ";
$sSqlBuscaEmpenhos .= "      LEFT JOIN acordo ON ac16_sequencial = e100_acordo ";
$sSqlBuscaEmpenhos .= "     where {$sele_work}{$sele_desdobramentos}                                                          										";
$sSqlBuscaEmpenhos .= "     UNION                                                          																									";
$sSqlBuscaEmpenhos .= "     SELECT k12_empen,                                                          																						";
$sSqlBuscaEmpenhos .= "                  k12_empen AS e60_numemp,                                                          																	";
$sSqlBuscaEmpenhos .= "                  k17_codigo::varchar AS e60_codemp,                                                          														";
$sSqlBuscaEmpenhos .= "                  k17_numcgm AS e60_numcgm,                                                          																";
$sSqlBuscaEmpenhos .= "                  k12_codord,                                                          																				";
$sSqlBuscaEmpenhos .= "                  z01_nome,                                                          																				";
$sSqlBuscaEmpenhos .= "                  k12_valor,                                                          																				";
$sSqlBuscaEmpenhos .= "                  e91_cheque::integer AS k12_cheque,                                                          														";
$sSqlBuscaEmpenhos .= "                  e60_anousu,                                                          																				";
$sSqlBuscaEmpenhos .= "                  k12_autent,                                                          																				";
$sSqlBuscaEmpenhos .= "                  k12_data,                                                          																				";
$sSqlBuscaEmpenhos .= "                  credito AS k13_conta,                                                          																	";
$sSqlBuscaEmpenhos .= "                  o15_codtri,                                                          																				";
$sSqlBuscaEmpenhos .= "                  descr_credito AS k13_descr,                                                          																";
$sSqlBuscaEmpenhos .= "                  tipo,                                                           																					";
$sSqlBuscaEmpenhos .= "                  0 AS k106_sequencial, 0 as e60_coddot , '' as e60_resumo, ac16_sequencial,ac16_anousu, ac16_numero                                                         																		";
$sSqlBuscaEmpenhos .= "     FROM                                                          																									";
$sSqlBuscaEmpenhos .= "         (SELECT k12_id,                                                          																					";
$sSqlBuscaEmpenhos .= "                 k12_autent,                                                          																				";
$sSqlBuscaEmpenhos .= "                 k12_data,                                                          																					";
$sSqlBuscaEmpenhos .= "                 k12_valor,                                                          																				";
$sSqlBuscaEmpenhos .= "                 CASE                                                          																						";
$sSqlBuscaEmpenhos .= "                     WHEN (h.c60_codsis = 6                                                          																";
$sSqlBuscaEmpenhos .= "                           AND f.c60_codsis = 6) THEN 'tran'                                                          												";
$sSqlBuscaEmpenhos .= "                     WHEN (h.c60_codsis = 6                                                          																";
$sSqlBuscaEmpenhos .= "                           AND f.c60_codsis = 5) THEN 'tran'                                                          												";
$sSqlBuscaEmpenhos .= "                     WHEN (h.c60_codsis = 5                                                          																";
$sSqlBuscaEmpenhos .= "                           AND f.c60_codsis = 6) THEN 'tran'                                                          												";
$sSqlBuscaEmpenhos .= "                     ELSE 'EXT'                                                          																			";
$sSqlBuscaEmpenhos .= "                 END AS tipo,                                                          																				";
$sSqlBuscaEmpenhos .= "                 k12_empen,                                                          																				";
$sSqlBuscaEmpenhos .= "                 e60_codemp,                                                          																				";
$sSqlBuscaEmpenhos .= "                 e60_anousu,                                                          																				";
$sSqlBuscaEmpenhos .= "                 k12_codord,                                                          																				";
$sSqlBuscaEmpenhos .= "                 k12_cheque,                                                          																				";
$sSqlBuscaEmpenhos .= "                 entrou AS debito,                                                          																			";
$sSqlBuscaEmpenhos .= "                 f.c60_descr AS descr_debito,                                                          																";
$sSqlBuscaEmpenhos .= "                 f.c60_codsis AS sis_debito,                                                          																";
$sSqlBuscaEmpenhos .= "                 saiu AS credito,                                                          																			";
$sSqlBuscaEmpenhos .= "                 o15_codtri,                                                          																				";
$sSqlBuscaEmpenhos .= "                 h.c60_descr AS descr_credito,                                                          																";
$sSqlBuscaEmpenhos .= "                 h.c60_codsis AS sis_credito,                                                          																";
$sSqlBuscaEmpenhos .= "                 sl AS k17_codigo,                                                          																			";
$sSqlBuscaEmpenhos .= "                 k17_numcgm,                                                          																				";
$sSqlBuscaEmpenhos .= "                 z01_nome,                                                          																					";
$sSqlBuscaEmpenhos .= "                 corhi AS k12_histcor,                                                          																		";
$sSqlBuscaEmpenhos .= "                 sl_txt AS k17_texto,                                                          																		";
$sSqlBuscaEmpenhos .= "                 e91_cheque, ac16_sequencial, ac16_anousu, ac16_numero                                                          																				";
$sSqlBuscaEmpenhos .= "          FROM                                                          																								";
$sSqlBuscaEmpenhos .= "              (SELECT k12_id,                                                          																				";
$sSqlBuscaEmpenhos .= "                      k12_autent,                                                          																			";
$sSqlBuscaEmpenhos .= "                      k12_data,                                                          																			";
$sSqlBuscaEmpenhos .= "                      k12_valor,                                                          																			";
$sSqlBuscaEmpenhos .= "                      tipo,                                                          																				";
$sSqlBuscaEmpenhos .= "                      k12_empen,                                                          																			";
$sSqlBuscaEmpenhos .= "                      e60_codemp,                                                          																			";
$sSqlBuscaEmpenhos .= "                      e60_anousu,                                                          																			";
$sSqlBuscaEmpenhos .= "                      k12_codord,                                                          																			";
$sSqlBuscaEmpenhos .= "                      k12_cheque,                                                          																			";
$sSqlBuscaEmpenhos .= "                      corlanc AS entrou,                                                          																	";
$sSqlBuscaEmpenhos .= "                      corrente AS saiu,                                                          																	";
$sSqlBuscaEmpenhos .= "                      slp AS sl,                                                          																			";
$sSqlBuscaEmpenhos .= "                      k17_numcgm,                                                          																			";
$sSqlBuscaEmpenhos .= "                      z01_nome,                                                          																			";
$sSqlBuscaEmpenhos .= "                      corh AS corhi,                                                          																		";
$sSqlBuscaEmpenhos .= "                      slp_txt AS sl_txt,                                                          																	";
$sSqlBuscaEmpenhos .= "                      e91_cheque, ac16_sequencial, ac16_anousu, ac16_numero                                                          																			";
$sSqlBuscaEmpenhos .= "               FROM                                                          																						";
$sSqlBuscaEmpenhos .= "                   (SELECT *,                                                          																				";
$sSqlBuscaEmpenhos .= "                           CASE                                                          																			";
$sSqlBuscaEmpenhos .= "                               WHEN coalesce(corl_saltes,0) = 0 THEN 'EXT'                                                          									";
$sSqlBuscaEmpenhos .= "                               ELSE 'tran'                                                          																	";
$sSqlBuscaEmpenhos .= "                           END AS tipo                                                          																		";
$sSqlBuscaEmpenhos .= "                    FROM                                                          																					";
$sSqlBuscaEmpenhos .= "                        (SELECT corrente.k12_id,                                                          															";
$sSqlBuscaEmpenhos .= "                                corrente.k12_autent,                                                          														";
$sSqlBuscaEmpenhos .= "                                corrente.k12_data,                                                          															";
$sSqlBuscaEmpenhos .= "                                corrente.k12_valor,                                                          														";
$sSqlBuscaEmpenhos .= "                                corrente.k12_conta AS corrente,                                                          											";
$sSqlBuscaEmpenhos .= "                                c.k13_conta AS corr_saltes,                                                          												";
$sSqlBuscaEmpenhos .= "                                b.k12_conta AS corlanc,                                                          													";
$sSqlBuscaEmpenhos .= "                                d.k13_conta AS corl_saltes,                                                          												";
$sSqlBuscaEmpenhos .= "                                p.k12_empen,                                                          																";
$sSqlBuscaEmpenhos .= "                                e60_codemp,                                                          																";
$sSqlBuscaEmpenhos .= "                                e60_anousu,                                                          																";
$sSqlBuscaEmpenhos .= "                                p.k12_codord,                                                          																";
$sSqlBuscaEmpenhos .= "                                p.k12_cheque,                                                          																";
$sSqlBuscaEmpenhos .= "                                slip.k17_codigo AS slp,                                                          													";
$sSqlBuscaEmpenhos .= "                                slipnum.k17_numcgm,                                                          														";
$sSqlBuscaEmpenhos .= "                                cgm.z01_nome,                                                          																";
$sSqlBuscaEmpenhos .= "                                corhist.k12_histcor AS corh,                                                          												";
$sSqlBuscaEmpenhos .= "                                slip.k17_texto AS slp_txt,                                                          													";
$sSqlBuscaEmpenhos .= "                                e91_cheque, ac16_sequencial, ac16_anousu, ac16_numero                                                          																	";
$sSqlBuscaEmpenhos .= "                         FROM corrente                                                          																		";
$sSqlBuscaEmpenhos .= "                         INNER JOIN corlanc b ON corrente.k12_id = b.k12_id AND corrente.k12_autent = b.k12_autent AND corrente.k12_data = b.k12_data                ";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN corconf ON corconf.k12_id = corrente.k12_id AND corconf.k12_data = corrente.k12_data AND corconf.k12_autent = corrente.k12_autent ";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN empageconfche ON corconf.k12_codmov = empageconfche.e91_codcheque                                                          		";
$sSqlBuscaEmpenhos .= "                         INNER JOIN slip ON slip.k17_codigo = b.k12_codigo                                                          									";
$sSqlBuscaEmpenhos .= "                         INNER JOIN slipnum ON slip.k17_codigo = slipnum.k17_codigo                                                          						";
$sSqlBuscaEmpenhos .= "                         INNER JOIN cgm ON slipnum.k17_numcgm = cgm.z01_numcgm                                                          								";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN corhist ON corhist.k12_id = b.k12_id AND corhist.k12_data = b.k12_data AND corhist.k12_autent = b.k12_autent                      ";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN coremp p ON corrente.k12_id = p.k12_id AND corrente.k12_autent=p.k12_autent AND corrente.k12_data = p.k12_data                    ";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN empempenho ON e60_numemp = k12_empen AND e60_instit = {$iInstituicaoSessao}                                                       ";
$sSqlBuscaEmpenhos .= "      LEFT JOIN empempenhocontrato on  empempenho.e60_numemp = empempenhocontrato.e100_numemp    ";
$sSqlBuscaEmpenhos .= "      LEFT JOIN acordo ON ac16_sequencial = e100_acordo ";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN saltes c ON c.k13_conta = corrente.k12_conta                                                          							";
$sSqlBuscaEmpenhos .= "                         LEFT JOIN saltes d ON d.k13_conta = b.k12_conta                                                          									";
$sSqlBuscaEmpenhos .= "                         WHERE corrente.k12_instit = {$iInstituicaoSessao}                                                          									";
$sSqlBuscaEmpenhos .= "                             AND corrente.k12_instit = {$iInstituicaoSessao}) AS x) AS xx) AS xxx                                                          			";
$sSqlBuscaEmpenhos .= "          INNER JOIN conplanoexe e ON entrou = e.c62_reduz AND e.c62_anousu = {$iAnoUsoSessao}                                                          				";
$sSqlBuscaEmpenhos .= "          INNER JOIN conplanoreduz i ON e.c62_reduz = i.c61_reduz AND i.c61_anousu = {$iAnoUsoSessao} AND i.c61_instit = {$iInstituicaoSessao}                       ";
$sSqlBuscaEmpenhos .= "          INNER JOIN conplano f ON i.c61_codcon = f.c60_codcon AND i.c61_anousu = f.c60_anousu                                                          				";
$sSqlBuscaEmpenhos .= "          INNER JOIN conplanoexe g ON saiu = g.c62_reduz AND g.c62_anousu = {$iAnoUsoSessao}                                                          				";
$sSqlBuscaEmpenhos .= "          INNER JOIN conplanoreduz j ON g.c62_reduz = j.c61_reduz AND j.c61_anousu = {$iAnoUsoSessao}                                                          		";
$sSqlBuscaEmpenhos .= "          INNER JOIN orctiporec l ON j.c61_codigo = l.o15_codigo                                                          											";
$sSqlBuscaEmpenhos .= "          INNER JOIN conplano h ON j.c61_codcon = h.c60_codcon AND j.c61_anousu = h.c60_anousu                                                          				";
$sSqlBuscaEmpenhos .= "          ORDER BY tipo,                                                           																					";
$sSqlBuscaEmpenhos .= "                   credito,                                                          																				";
$sSqlBuscaEmpenhos .= "                   k12_data,                                                          																				";
$sSqlBuscaEmpenhos .= "                   k12_autent) AS y                                                          																		";
$sSqlBuscaEmpenhos .= "     WHERE tipo = 'ext') AS todo                                                          																			";
$sSqlBuscaEmpenhos .= " WHERE {$sWhereEmpenho}                                                          																					";
$sSqlBuscaEmpenhos .= "   AND {$sImplodeWhere} {$sImplodeGroupBy} {$sImplodeOrderBy}                                                          												";
$rsExecutaBuscaEmpenho = db_query($sSqlBuscaEmpenhos);

$iLinhasRetornadasBuscaEmpenho = pg_num_rows($rsExecutaBuscaEmpenho);
if ($iLinhasRetornadasBuscaEmpenho == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=N�o existem empenhos para o filtro selecionado.");
}

$total = 0;

$aDadosImprimir = array();
for ($iRowBusca = 0; $iRowBusca < $iLinhasRetornadasBuscaEmpenho; $iRowBusca++) {

  $oDadoEmpenho = db_utils::fieldsMemory($rsExecutaBuscaEmpenho, $iRowBusca);

  if ( $oDadoEmpenho->tipo == "Emp" ) {
    $total += $oDadoEmpenho->k12_valor;
  }

  if ( $oDadoEmpenho->e50_codord > 0 ) {

    $oDaoPagOrdemNota   = db_utils::getDao('pagordemnota');
    $sSqlBuscaOrdemNota = $oDaoPagOrdemNota->sql_query($oDadoEmpenho->e50_codord,null,'e69_numero');
    $rsBuscaOrdemNota   = $oDaoPagOrdemNota->sql_record($sSqlBuscaOrdemNota);
    $iTotalOrdemNota    = $oDaoPagOrdemNota->numrows;
    $aNotasEncontradas  = array();
    $aNotasEncontradas[] = "0";
  	if( $oDaoPagOrdemNota->numrows > 0 ) {
  	  for ($iRowOrdem = 0; $iRowOrdem < $iTotalOrdemNota; $iRowOrdem++ ) {
    		$iNumeroOrdem        = db_utils::fieldsMemory($rsBuscaOrdemNota, $iRowOrdem)->e69_numero;
    		$aNotasEncontradas[] = $iNumeroOrdem;
  	  }
    }
  }
  $oDadoEmpenho->aNotas = $aNotasEncontradas;
  $aDadosImprimir[] = $oDadoEmpenho;
  unset($oDadoEmpenho);

}

$iAltura              = 4;
$lTroca               = true;
$nValorSoma           = 0;
$total_geral = 0;
$nValorTotalBanco     = 0;
$nValorTotalRelatorio = 0;
$iTotalRegistros      = 0;
$iTotalRegistroGeral  = 0;

$head1 = "MOVIMENTA��O EMPENHOS PAGOS";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$sTipoEmpenho = $aDadosImprimir[0]->tipo;
$iCodigoBanco = $aDadosImprimir[0]->k13_conta;
$iQuantBanco  = 0;

// OC 3468
// Inclu�do quebra de p�gina diversas por sele��o dos filtros.
// Esse filtro n�o funcionava anteriormente!

$aTeste = array();
foreach ($aDadosImprimir as $iIndice => $oDadoEmpenho) {
  $oDadosAgrupados = new stdClass();
  if ($oPost->lQuebraCredor && $oPost->lQuebraConta && $oPost->lQuebraRecurso ||($oPost->lQuebraConta && $oPost->lQuebraCredor)) {
         $aDadosAgrupados[$oDadoEmpenho->e60_numcgm.$oDadoEmpenho->k13_descr][] = $oDadoEmpenho;
  } else if($oPost->lQuebraCredor && $oPost->lQuebraRecurso){
      $aDadosAgrupados[$oDadoEmpenho->e60_numcgm.$oDadoEmpenho->o15_codtri][] = $oDadoEmpenho;
  } else if($oPost->lQuebraConta && $oPost->lQuebraRecurso){
      $aDadosAgrupados[$oDadoEmpenho->k13_conta.$oDadoEmpenho->o15_codtri][] = $oDadoEmpenho;
  } else if($oPost->lQuebraCredor){
      $aDadosAgrupados[$oDadoEmpenho->e60_numcgm][] = $oDadoEmpenho;
  } else if ($oPost->lQuebraConta) {
      $aDadosAgrupados[$oDadoEmpenho->k13_conta][] = $oDadoEmpenho;
  } else if ($oPost->lQuebraRecurso) {
      $aDadosAgrupados[$oDadoEmpenho->o15_codtri][] = $oDadoEmpenho;
  } else if ($oPost->lQuebraAcordo) {
    $aDadosAgrupados[$oDadoEmpenho->ac16_numero][] = $oDadoEmpenho;
  }
  else $aDadosAgrupados[$oDadoEmpenho->k12_data][] = $oDadoEmpenho;
}

$fp = fopen("tmp/empenhospagos.csv", "w");

fputs($fp, "DATA AUTENT;COD.CON;DESCRI��O CONTA;FONTE;DOT.;EMP/SLIP;ORDEM;NOTAS FISCAIS;CREDOR;VLR.AUTENT;N�CHEQUE;TIPO;CONTRATO;\n");
 
  $count_dados = 0;
  foreach ($aDadosAgrupados as $iIndice => $aDadoEmpenhos) {
    $total_nadata = 0;

    foreach ($aDadoEmpenhos as $oDadoEmpenho) {
      $total_nadata += $oDadoEmpenho->k12_valor;
      $count_dados += 1;  
      $oDadoEmpenho->k13_descr = substr($oDadoEmpenho->k13_descr, 0, 25);
      $empano = trim($oDadoEmpenho->e60_codemp) . '/' . $oDadoEmpenho->e60_anousu;
      $oDadoEmpenho->k12_data = db_formatar($oDadoEmpenho->k12_data, 'd');
      $z01_nome = strlen($oDadoEmpenho->z01_nome) > 36 ? substr($oDadoEmpenho->z01_nome,0,36).'...' : $oDadoEmpenho->z01_nome;
      $k12_valor = db_formatar($oDadoEmpenho->k12_valor, 'f');

      fputs($fp, "$oDadoEmpenho->k12_data;$oDadoEmpenho->k13_conta;$oDadoEmpenho->k13_descr;$oDadoEmpenho->o15_codtri;$oDadoEmpenho->e60_coddot;");
      fputs($fp, "$empano;$oDadoEmpenho->e50_codord;");
      if (count($oDadoEmpenho->aNotas) > 0) {
        $sNotasEncontradas = implode(" - ", $oDadoEmpenho->aNotas);
      }
      fputs($fp, "$sNotasEncontradas;$z01_nome;$k12_valor;$oDadoEmpenho->k12_cheque;$oDadoEmpenho->tipo;");
      if($oDadoEmpenho->ac16_numero != null)
      fputs($fp, "$oDadoEmpenho->ac16_numero"."/"."$oDadoEmpenho->ac16_anousu;");
      else
        fputs($fp, ";");
      fputs($fp, "\n");  

      $clempelemento  = new cl_empelemento;
      $result     = $clempelemento->sql_record($clempelemento->sql_query($oDadoEmpenho->e60_numemp, null, "e64_codele, o56_elemento,o56_descr","e64_codele"));
      if ($clempelemento->numrows > 0) {
          db_fieldsmemory($result,0);
      }

      if($oPost->iPrestacaoConta==2) {
        fputs($fp, "Desdobramento: ; " . substr($o56_elemento,1) . " " . $o56_descr);
        fputs($fp, "\n");
        fputs($fp, "Hist�rico: ; $oDadoEmpenho->e60_resumo");
        fputs($fp, "\n");
        }
        
    }
    $total_geral += $total_nadata;
    $total_nadata = db_formatar($total_nadata, 'f');
    fputs($fp, ";;;;;;;;;SubTotal : $total_nadata;");
    fputs($fp, "\n");
    
  }
  $total_geral = db_formatar($total_geral, 'f');
  fputs($fp, ";;;;;;;;;Total Geral : $total_geral;");
  fputs($fp, "\n");
  fclose($fp);
  
  ?>
 
<script type="text/javascript">
            window.location.href = "tmp/empenhospagos.csv"
            setTimeout("self.close();",500)
</script>