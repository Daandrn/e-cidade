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

/**
 * classes e fun��es contabeis
 * @package contabilidade
 * Revis�o$Author: dbmauricio $
 * @version $Revision: 1.220 $
 */
require_once(modification("classes/db_contranslan_classe.php"));


//|00|//cl_despdesdobramento
//|10|// emite despesa por desdobramento
//|10|// quando o orcamento e no elemento, o campo c60_estrut aponta para o elemento e o56_elemento para o desdobramento
//|15|//[variavel] = new cl_despdesdobramento();

class cl_desdobramento
{

    function sql($where = "", $dtini, $dtfim, $w_instit = "(1) ", $w_elemento = "")
    {

        $sql = "select
    /* orcdotacao.o58_codele,*/
    conplano.c60_estrut,
    conplano.c60_descr,
    substr(ele.o56_elemento||'00',1,15) as o56_elemento,
    ele.o56_descr,
    sum(case when c53_tipo = 10  then c70_valor else 0 end ) as empenhado,
    sum(case when c53_tipo = 11  then c70_valor else 0 end ) as empenhado_estornado,
    sum(case when c53_tipo = 20  then c70_valor else 0 end ) as liquidado,
    sum(case when c53_tipo = 21  then c70_valor else 0 end ) as liquidado_estornado,
    sum(case when c53_tipo = 30  then c70_valor else 0 end ) as pagamento,
    sum(case when c53_tipo = 31  then c70_valor else 0 end ) as pagamento_estornado,
    sum(case when c53_tipo = 32  then c70_valor else 0 end ) as empenho_rpestornado
    from conlancamele
    inner join conlancam on c67_codlan=c70_codlan
    inner join conlancamemp on c75_codlan = c70_codlan
    inner join empempenho on e60_numemp = c75_numemp and e60_anousu=" . db_getsession("DB_anousu") . "
    inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu
    $w_elemento
    inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=" . db_getsession("DB_anousu") . "
    inner join conlancamdoc on c71_codlan=c70_codlan
    inner join conhistdoc on c71_coddoc=c53_coddoc
    inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele and
    ele.o56_anousu = o58_anousu
    where ";
        if ($where != "") {
            $sql .= " $where and ";
        }
        $sql .= "
    empempenho.e60_instit in $w_instit
    and ( conlancam.c70_data >='$dtini' and conlancam.c70_data <='$dtfim' )
    and conhistdoc.c53_tipo in (10,11,20,21,30,31,32)
    group by /* o58_codele, */
    c60_estrut,
    c60_descr,
    o56_elemento,
    o56_descr
    order by
    o56_elemento
    ";
        return analiseQueryPlanoOrcamento($sql);
    }

    function sql_liquidacao($where = "", $dtini, $dtfim, $w_instit = "(1) ", $w_elemento = "")
    {

        $sql = "select
    c60_estrut,
    c60_descr,
    substr(o56_elemento||'00',1,15) as o56_elemento,
    o56_descr,
    sum(case when c53_tipo = 10  then c70_valor else 0 end ) as empenhado,
    sum(case when c53_tipo = 11  then c70_valor else 0 end ) as empenhado_estornado,
    sum(case when c53_tipo = 20  then c70_valor else 0 end ) as liquidado,
    sum(case when c53_tipo = 21  then c70_valor else 0 end ) as liquidado_estornado,
    sum(case when c53_tipo = 20 and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end) as liquidado_mes_origem,
    sum(case when c53_tipo = 21 and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end) as liquidado_estornado_mes_origem,
    sum(case when c53_tipo = 30  then c70_valor else 0 end ) as pagamento,
    sum(case when c53_tipo = 31  then c70_valor else 0 end ) as pagamento_estornado,
    sum(case when c53_tipo = 32  then c70_valor else 0 end ) as empenho_rpestornado,
    sum(case when c53_tipo = 11 and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end) as estornado_mes_origem,
    sum(case when c53_tipo = 11 and mes_origem_empenho != date_part('month',c70_data) then c70_valor else 0 end) as estornado_mes_diferente,
    sum(case when c53_coddoc in (200, 208, 210) and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end ) as em_liquidacao_mes_origem,
	  sum(case when c53_coddoc in (202, 204, 206) and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end ) as liquidacao_mes_origem,
	  sum(case when c53_coddoc in (201, 209, 211) and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end ) as estorno_em_liquidacao_mes_origem,
	  sum(case when c53_coddoc in (203, 205, 207) and mes_origem_empenho = date_part('month',c70_data) then c70_valor else 0 end ) as estorno_liquidacao_mes_origem
    from
    (select *,
      (select date_part('month', c75_data)
        from conlancamemp
          inner join conlancamdoc on c71_codlan = c75_codlan
          inner join conhistdoc on c71_coddoc = c53_coddoc
        where c75_numemp = e60_numemp
          and c53_tipo = 10 limit 1) as mes_origem_empenho
          from
          (select conplanoorcamento.c60_estrut,
                  conplanoorcamento.c60_descr,
                  substr(ele.o56_elemento||'00',1,15) AS o56_elemento,
                  ele.o56_descr,
                  e60_numemp,
                  c53_tipo,
                  c70_valor,
                  c70_data,
                  c53_coddoc
          from conlancamele
          inner join conlancam on c67_codlan=c70_codlan
          inner join conlancamemp on c75_codlan = c70_codlan
          inner join empempenho on e60_numemp = c75_numemp and e60_anousu=" . db_getsession("DB_anousu") . "
          inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu
          $w_elemento
          inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=" . db_getsession("DB_anousu") . "
          inner join conlancamdoc on c71_codlan=c70_codlan
          inner join conhistdoc on c71_coddoc=c53_coddoc
          inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele and
          ele.o56_anousu = o58_anousu
          where ";
        if ($where != "") {
            $sql .= " $where and ";
        }
        $sql .= "
          empempenho.e60_instit in $w_instit
          and ( conlancam.c70_data >='$dtini' and conlancam.c70_data <='$dtfim' )
          and conhistdoc.c53_tipo in (10,11,20,21,30,31,32,200,201) ) as x ) as xx
      group by
        c60_estrut,
        c60_descr,
        o56_elemento,
        o56_descr
      order by
        o56_elemento
    ";
        return analiseQueryPlanoOrcamento($sql);
    }

    function sql2($where = "", $dtini, $dtfim, $w_instit = "(1) ")
    {
        $sql = "select
    /* orcdotacao.o58_codele,*/
    conplano.c60_estrut,
    conplano.c60_descr,
    substr(ele.o56_elemento||'00',1,15) as o56_elemento,
    ele.o56_descr,
    sum(case when c53_tipo = 10  then c70_valor else 0 end ) as empenhadoa,
    sum(case when c53_tipo = 11  then c70_valor else 0 end ) as empenhado_estornadoa,
    sum(case when c53_tipo = 20  then c70_valor else 0 end ) as liquidadoa,
    sum(case when c53_tipo = 21  then c70_valor else 0 end ) as liquidado_estornadoa,
    sum(case when c53_tipo = 30  then c70_valor else 0 end ) as pagamentoa,
    sum(case when c53_tipo = 31  then c70_valor else 0 end ) as pagamento_estornadoa,
    sum(case when c53_tipo = 32  then c70_valor else 0 end ) as empenho_rpestornadoa
    from conlancamele
    inner join conlancam on c67_codlan=c70_codlan
    inner join conlancamemp on c75_codlan = c70_codlan
    inner join empempenho on e60_numemp = c75_numemp and e60_anousu=" . db_getsession("DB_anousu") . "
    inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu
    $w_elemento
    inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=" . db_getsession("DB_anousu") . "
    inner join conlancamdoc on c71_codlan=c70_codlan
    inner join conhistdoc on c71_coddoc=c53_coddoc
    inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele and
    ele.o56_anousu = o58_anousu
    where ";
        if ($where != "") {
            $sql .= " $where and ";
        }
        $sql .= "
    empempenho.e60_instit in $w_instit
    and ( conlancam.c70_data >='" . db_getsession("DB_anousu") . "-01-01' and conlancam.c70_data <='$dtfim' )
    and conhistdoc.c53_tipo in (10,11,20,21,30,31,32)
    group by /* o58_codele, */
    c60_estrut,
    c60_descr,
    o56_elemento,
    o56_descr
    order by
    o56_elemento
    ";
        return analiseQueryPlanoOrcamento($sql);
    }

    function sql_em_liquidacao_ate_mes($where = "", $dtini, $dtfim, $w_instit = "(1) ")
    {
        $sql = "select
      conplano.c60_estrut,
      conplano.c60_descr,
      substr(ele.o56_elemento||'00',1,15) as o56_elemento,
      ele.o56_descr,
      sum(case when c53_tipo = 10  then c70_valor else 0 end ) as empenhadoa,
      sum(case when c53_tipo = 11  then c70_valor else 0 end ) as empenhado_estornadoa,
      sum(case when c53_tipo = 20  then c70_valor else 0 end ) as liquidadoa,
      sum(case when c53_tipo = 21  then c70_valor else 0 end ) as liquidado_estornadoa,
      sum(case when c53_tipo = 30  then c70_valor else 0 end ) as pagamentoa,
      sum(case when c53_tipo = 31  then c70_valor else 0 end ) as pagamento_estornadoa,
      sum(case when c53_tipo = 32  then c70_valor else 0 end ) as empenho_rpestornadoa,
      sum(case when c53_coddoc in (200, 208, 210) then c70_valor else 0 end ) as em_liquidacaoa,
      sum(case when c53_coddoc in (202, 204, 206) then c70_valor else 0 end ) as liquidacaoa,
      sum(case when c53_coddoc in (201, 209, 211) then c70_valor else 0 end ) as estorno_em_liquidacaoa,
      sum(case when c53_coddoc in (203, 205, 207) then c70_valor else 0 end ) as estorno_liquidacaoa
      from conlancamele
      inner join conlancam on c67_codlan=c70_codlan
      inner join conlancamemp on c75_codlan = c70_codlan
      inner join empempenho on e60_numemp = c75_numemp and e60_anousu=" . db_getsession("DB_anousu") . "
      inner join orcdotacao on o58_coddot = empempenho.e60_coddot  and o58_anousu = e60_anousu
      $w_elemento
      inner join conplano on c60_codcon = orcdotacao.o58_codele and c60_anousu=" . db_getsession("DB_anousu") . "
      inner join conlancamdoc on c71_codlan=c70_codlan
      inner join conhistdoc on c71_coddoc=c53_coddoc
      inner join orcelemento ele on ele.o56_codele=conlancamele.c67_codele and
      ele.o56_anousu = o58_anousu
      where ";
        if ($where != "") {
            $sql .= " $where and ";
        }
        $sql .= "
      empempenho.e60_instit in $w_instit
      and ( conlancam.c70_data >='" . db_getsession("DB_anousu") . "-01-01' and conlancam.c70_data <='$dtfim' )
      and conhistdoc.c53_tipo in (10,11,20,21,30,31,32,200,201)
      group by /* o58_codele, */
      c60_estrut,
      c60_descr,
      o56_elemento,
      o56_descr
      order by
      o56_elemento
      ";
        return analiseQueryPlanoOrcamento($sql);
    }
}

class cl_receita_saldo_mes
{
    //|00|//cl_receita_saldo_mes
    //|10|//calcula a receita arrecadada por mes
    //|15|//[variavel] = new cl_receita_saldo_mes;

    var $receita = null;
    var $anousu = null;
    var $sql = null;
    var $numrows = 0;
    var $result = false;
    var $dtini = null;
    var $dtfim = null;
    var $lPrevisaoCronograma = false;
    var $estrut = null; // string de estruturais
    var $instit = null;
    var $usa_datas = null; // essa variavel indica o usuo das variaveis dtini e dtfim

    function sql_query($receita)
    {
        //    echo("dtini: " . $this->dtini . " - dtfim: " . $this->dtfim . " - instit: " . $this->instit . " - ano: " . $this->anousu . "<br>");

        if ($this->anousu == null)
            $this->anousu = db_getsession("DB_anousu");
        if ($this->dtini == null)
            db_msgbox('Data inicio n�o informada.');
        if ($this->dtfim == null)
            db_msgbox('Data final n�o informada.');

        if ($this->instit == null) {
            if (db_getsession("DB_instit") == 1)
                $ins = " (1)";
            else if (db_getsession("DB_instit") == 2)
                $ins = "(2)";
            else
                $ins = " ( 3 ) ";
        } else {
            $ins = "(" . $this->instit . ")";
        }

        $this->sql = " select * from (
                                  SELECT O70_ANOUSU,O70_CODREC,
                                  o70_instit,
                                  O57_FONTE,O57_DESCR,
                                  O70_VALOR::float8 as o70_valor,
                                  sum(ADICIONAL)::float8 as adicional,
                                  SUM(JANEIRO) AS JANEIRO,
                                  SUM(FEVEREIRO) AS FEVEREIRO,
                                  SUM(MARCO) AS MARCO,
                                  SUM(ABRIL) AS ABRIL,
                                  SUM(MAIO) AS MAIO,
                                  SUM(JUNHO) AS JUNHO,
                                  SUM(JULHO) AS JULHO,
                                  SUM(AGOSTO) AS AGOSTO,
                                  SUM(SETEMBRO) AS SETEMBRO,
                                  SUM(OUTUBRO) AS OUTUBRO,
                                  SUM(NOVEMBRO) AS NOVEMBRO,
                                  SUM(DEZEMBRO) AS DEZEMBRO,
                                  prev_jan::float8 as prev_jan,
                                  prev_fev::float8 as prev_fev,
                                  prev_mar::float8 as prev_mar,
                                  prev_abr::float8 as prev_abr,
                                  prev_mai::float8 as prev_mai,
                                  prev_jun::float8 as prev_jun,
                                  prev_jul::float8 as prev_jul,
                                  prev_ago::float8 as prev_ago,
                                  prev_set::float8 as prev_set,
                                  prev_out::float8 as prev_out,
                                  prev_nov::float8 as prev_nov,
                                  prev_dez::float8 as prev_dez

                            FROM (
                            SELECT O70_ANOUSU,
                            O70_CODREC,
                            o70_instit,
                            o57_fonte,
                            o57_descr,
                            O70_VALOR,
                            ADICIONAL,
                            sum(coalesce(case when O71_MES=1 then  0.0 end,0.0)) as  prev_jan,
                            sum(coalesce(case when O71_MES=2 then  0.0 end,0.0)) as  prev_fev,
                            sum(coalesce(case when O71_MES=3 then  0.0 end,0.0)) as  prev_mar,
                            sum(coalesce(case when O71_MES=4 then  0.0 end,0.0)) as  prev_abr,
                            sum(coalesce(case when O71_MES=5 then  0.0 end,0.0)) as  prev_mai,
                            sum(coalesce(case when O71_MES=6 then  0.0 end,0.0)) as  prev_jun,
                            sum(coalesce(case when O71_MES=7 then  0.0 end,0.0)) as  prev_jul,
                            sum(coalesce(case when O71_MES=8 then  0.0 end,0.0)) as  prev_ago,
                            sum(coalesce(case when O71_MES=9 then  0.0 end,0.0)) as  prev_set,
                            sum(coalesce(case when O71_MES=10 then 0 end,0.0)) as prev_out,
                            sum(coalesce(case when O71_MES=11 then 0.0 end,0.0)) as prev_nov,
                            sum(coalesce(case when O71_MES=12 then 0.0 end,0.0)) as prev_dez,

                            CASE WHEN O71_MES = 1 THEN ARRECADADO ELSE 0::FLOAT8 END AS JANEIRO,
                            CASE WHEN O71_MES = 2 THEN ARRECADADO ELSE 0::FLOAT8 END AS FEVEREIRO,
                            CASE WHEN O71_MES = 3 THEN ARRECADADO ELSE 0::FLOAT8 END AS MARCO,
                            CASE WHEN O71_MES = 4 THEN ARRECADADO ELSE 0::FLOAT8 END AS ABRIL,
                            CASE WHEN O71_MES = 5 THEN ARRECADADO ELSE 0::FLOAT8 END AS MAIO,
                            CASE WHEN O71_MES = 6 THEN ARRECADADO ELSE 0::FLOAT8 END AS JUNHO,
                            CASE WHEN O71_MES = 7 THEN ARRECADADO ELSE 0::FLOAT8 END AS JULHO,
                            CASE WHEN O71_MES = 8 THEN ARRECADADO ELSE 0::FLOAT8 END AS AGOSTO,
                            CASE WHEN O71_MES = 9 THEN ARRECADADO ELSE 0::FLOAT8 END AS SETEMBRO,
                            CASE WHEN O71_MES =10 THEN ARRECADADO ELSE 0::FLOAT8 END AS OUTUBRO,
                            CASE WHEN O71_MES =11 THEN ARRECADADO ELSE 0::FLOAT8 END AS NOVEMBRO,
                            CASE WHEN O71_MES =12 THEN ARRECADADO ELSE 0::FLOAT8 END AS DEZEMBRO
                        FROM (
                              SELECT O70_ANOUSU,O70_CODREC,
                                      o70_instit,
                                      o57_fonte,
                                      o57_descr,
                                      TO_CHAR(C70_DATA,'MM')::integer AS O71_MES,
                                      O70_VALOR,
                                      round(SUM(CASE C53_TIPO WHEN 110 THEN
                                      case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR,2)::FLOAT8 else ROUND(C70_VALOR*-1,2)::FLOAT8 end
                                      WHEN 111 THEN
                                      case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR*-1,2)::FLOAT8 else ROUND(C70_VALOR,2)::FLOAT8 end
                                      ELSE 0::FLOAT8 END ),2) AS ADICIONAL,
                                      round(SUM( CASE C53_TIPO WHEN 100 THEN
                                      case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR,2)::FLOAT8
                                      else ROUND(C70_VALOR*-1,2)::FLOAT8 end
                                      WHEN 101 THEN
                                      case when fc_conplano_grupo( O70_ANOUSU, substr(o57_fonte,1,2) || '%', 9000 ) is false then ROUND(C70_VALOR*-1,2)::FLOAT8
                                      else ROUND(C70_VALOR,2)::FLOAT8 end
                                      ELSE 0::FLOAT8 END ),2) AS ARRECADADO
                                 FROM ORCRECEITA
                                      inner JOIN ORCFONTES ON O70_CODFON = O57_CODFON AND O57_ANOUSU = O70_ANOUSU
                                      left JOIN CONLANCAMREC ON C74_ANOUSU = O70_ANOUSU AND C74_CODREC = O70_CODREC "
            . ($this->usa_datas != null ? "AND c74_data between '" . $this->dtini . "' and '" . $this->dtfim . "'" : "")
            . "
                                      left JOIN CONLANCAM    ON C74_CODLAN = C70_CODLAN
                                      left JOIN CONLANCAMDOC ON C71_CODLAN = C70_CODLAN
                                      left JOIN CONHISTDOC ON C53_CODDOC = C71_CODDOC

    WHERE O70_ANOUSU = " . $this->anousu . "  and o70_instit in $ins
    ";
        if ($this->usa_datas != null) {
            $this->sql .= " AND ( c70_data between '" . $this->dtini . "' and '" . $this->dtfim . "'  or c70_data is null)";
        }

        if ($this->receita != null) {
            $this->sql .= " AND O70_CODREC = " . $this->receita;
        }

        $this->sql .= " GROUP BY O70_ANOUSU,O70_CODREC,o70_instit,O71_MES,O57_FONTE,O57_DESCR, O70_VALOR
    ) AS X

    group by O70_ANOUSU, O70_CODREC, o70_instit, O71_MES,ARRECADADO,o57_fonte, o57_descr, O70_VALOR, ADICIONAL

    ";

        $this->sql .= ") AS Y
    GROUP BY O70_ANOUSU,O70_CODREC,o70_instit,O57_FONTE,O57_DESCR,O70_VALOR,
    prev_jan,
    prev_fev,
    prev_mar,
    prev_abr,
    prev_mai,
    prev_jun,
    prev_jul,
    prev_ago,
    prev_set,
    prev_out,
    prev_nov,
    prev_dez

    ) as X

    ";
        if ($this->receita != null) {
            $this->sql .= " AND O70_CODREC = " . $this->receita;
        }

        $this->sql .= " order by o57_fonte ";
        //die($this->sql);
    }

    function sql_record_file()
    {

        if ($this->sql == null)
            $this->sql_query($this->receita);
        $this->result = db_query($this->sql);
        if ($this->result != false)
            $this->numrows = pg_numrows($this->result);
        else
            $this->numrows = 0;
    }

    function sql_record()
    {

        global $o70_anousu, $o70_codrec, $o57_fonte, $o57_descr, $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro, $o70_valor, $adicional;
        global $prev_jan, $prev_fev, $prev_mar, $prev_abr, $prev_mai, $prev_jun, $prev_jul, $prev_ago, $prev_set, $prev_out, $prev_nov, $prev_dez;

        require_once(modification("libs/db_utils.php"));
        if ($this->sql == null)
            $this->sql_query($this->receita);

        //    echo($this->sql . "<br>");

        db_query("begin");
        db_query("create temporary table work_plano as " . $this->sql);
        db_query("create index work_plano_estrut on work_plano(o57_fonte)");
        $result = db_query("select * from work_plano");
        for ($i = 0; $i < pg_numrows($result); $i++) {

            db_fieldsmemory($result, $i);
            $sSqlSaldoPrevisao = "SELECT mes,coalesce(o34_valor, 0) as valor, {$o70_codrec} as receita
                                 from  generate_series(1,12) as mes";
            $sSqlSaldoPrevisao .= "       left join orcprevrec ";
            $sSqlSaldoPrevisao .= "       on mes = o34_mes and o34_anousu = {$this->anousu} and o34_codrec = $o70_codrec";
            $sSqlSaldoPrevisao .= "    order by mes";
            if ($this->lPrevisaoCronograma) {

                $sSqlSaldoPrevisao = "SELECT coalesce(valor, 0) as valor, {$o70_codrec} as receita, mes";
                $sSqlSaldoPrevisao .= "  from generate_series(1,12) as mes";
                $sSqlSaldoPrevisao .= "       left join (SELECT coalesce(o127_valor, 0) as valor, ";
                $sSqlSaldoPrevisao .= "                         o127_mes ";
                $sSqlSaldoPrevisao .= "                    from cronogramaperspectivareceita  ";
                $sSqlSaldoPrevisao .= "                         inner join orcreceita on o70_codrec  = o126_codrec  ";
                $sSqlSaldoPrevisao .= "                                              and o126_anousu = {$this->anousu}";
                $sSqlSaldoPrevisao .= "                   inner join cronogramametareceita on o127_cronogramaperspectivareceita = o126_sequencial";
                $sSqlSaldoPrevisao .= "                   where o126_codrec = {$o70_codrec} ";
                $sSqlSaldoPrevisao .= "                      ) as bases on mes = o127_mes ";
                $sSqlSaldoPrevisao .= "                                        order by mes ";
            }

            //die($sSqlSaldoPrevisao);
            $rsSaldoPrevisao = db_query($sSqlSaldoPrevisao);
            $aMeses = db_utils::getCollectionByRecord($rsSaldoPrevisao);
            $aValorPrevMes = array();
            foreach ($aMeses as $oSaldoMes) {
                $aValorPrevMes[$oSaldoMes->mes] = $oSaldoMes->valor;
            }
            $prev_jan += $aValorPrevMes[1];
            $prev_fev += $aValorPrevMes[2];
            $prev_mar += $aValorPrevMes[3];
            $prev_abr += $aValorPrevMes[4];
            $prev_mai += $aValorPrevMes[5];
            $prev_jun += $aValorPrevMes[6];
            $prev_jul += $aValorPrevMes[7];
            $prev_ago += $aValorPrevMes[8];
            $prev_set += $aValorPrevMes[9];
            $prev_out += $aValorPrevMes[10];
            $prev_nov += $aValorPrevMes[11];
            $prev_dez += $aValorPrevMes[12];
            $estrutural = $o57_fonte;
            if ($this->lPrevisaoCronograma) {

                db_query(
                    "update work_plano set
          prev_jan  = prev_jan  +$prev_jan,
          prev_fev  = prev_fev  +$prev_fev+0.0,
          prev_mar  = prev_mar  +$prev_mar+0.0,
          prev_abr  = prev_abr  +$prev_abr+0.0,
          prev_mai  = prev_mai  +$prev_mai+0.0,
          prev_jun  = prev_jun  +$prev_jun+0.0,
          prev_jul  = prev_jul  +$prev_jul+0.0,
          prev_ago  = prev_ago  +$prev_ago+0.0,
          prev_set  = prev_set  +$prev_set+0.0,
          prev_out  = prev_out  +$prev_out+0.0,
          prev_nov  = prev_nov  +$prev_nov+0.0,
          prev_dez  = prev_dez  +$prev_dez+0.0

          where o70_codrec = {$o70_codrec}"
                );
            }
            for ($ii = 1; $ii < 10; $ii++) {
                ///o z� colocou isso... 19042005
                if ($estrutural == "") {
                    continue;
                }
                $estrutural = db_le_mae_conplano($estrutural);
                $nivel = db_le_mae_conplano($estrutural, true);
                $result_estrut = db_query("select o57_descr from work_plano where o57_fonte = '$estrutural'");
                // db_criatabela($result_estrut); exit;
                //if ($estrutural == '411229900000000'){
                //   echo ($estrutural." ->" .$o70_valor ." <br>");
                // }
                if (pg_numrows($result_estrut) == 0) {
                    $result_estrut = db_query(
                        "select o57_descr from orcfontes where o57_anousu = " . db_getsession("DB_anousu") . " and o57_fonte = '$estrutural'"
                    );

                    if (pg_numrows($result_estrut) == 0) {
                        $result_estrut = db_query(
                            "select o57_descr from orcfontes where o57_anousu = " . (db_getsession("DB_anousu") - 1) . " and o57_fonte = '$estrutural'"
                        );
                    }
                    if (pg_numrows($result_estrut) == 0) {
                        echo "Conta n�o encontrada nas fontes de Receita Comando:"
                            . "select o57_descr from orcfontes where o57_anousu = " . db_getsession("DB_anousu")
                            . " and o57_fonte = '$estrutural'";
                        exit;
                    }
                    db_fieldsmemory($result_estrut, 0);

                    $sInsert = "insert into work_plano values(
          " . $this->anousu
                        . ",
          0,
          0,
          '$estrutural',
          '$o57_descr',
          $o70_valor,
          $adicional,
          $janeiro,
          $fevereiro,
          $marco,
          $abril,
          $maio,
          $junho,
          $julho,
          $agosto,
          $setembro,
          $outubro,
          $novembro,
          $dezembro,
          $prev_jan,
          $prev_fev,
          $prev_mar,
          $prev_abr,
          $prev_mai,
          $prev_jun,
          $prev_jul,
          $prev_ago,
          $prev_set,
          $prev_out,
          $prev_nov,
          $prev_dez
          )
          ";
                    $result_1 = db_query($sInsert);
                } else {

                    db_query(
                        "update work_plano set
          o70_valor = o70_valor + $o70_valor,
          adicional= adicional  +$adicional,
          janeiro  = janeiro    +$janeiro,
          fevereiro= fevereiro  +$fevereiro,
          marco    = marco      +$marco,
          abril    = abril      +$abril,
          maio     = maio       +$maio,
          junho    = junho      +$junho,
          julho    = julho      +$julho,
          agosto   = agosto     +$agosto,
          setembro = setembro   +$setembro,
          outubro  = outubro    +$outubro,
          novembro = novembro   +$novembro,
          dezembro = dezembro   +$dezembro,
          prev_jan  = prev_jan  +$prev_jan,
          prev_fev  = prev_fev  +$prev_fev+0.0,
          prev_mar  = prev_mar  +$prev_mar+0.0,
          prev_abr  = prev_abr  +$prev_abr+0.0,
          prev_mai  = prev_mai  +$prev_mai+0.0,
          prev_jun  = prev_jun  +$prev_jun+0.0,
          prev_jul  = prev_jul  +$prev_jul+0.0,
          prev_ago  = prev_ago  +$prev_ago+0.0,
          prev_set  = prev_set  +$prev_set+0.0,
          prev_out  = prev_out  +$prev_out+0.0,
          prev_nov  = prev_nov  +$prev_nov+0.0,
          prev_dez  = prev_dez  +$prev_dez+0.0

          where o57_fonte = '$estrutural'"
                    );
                }
                /*
        o70_valor = o70_valor+$o70_valor,
        adicional= adicional+$adicional,

         */

                if ($nivel == 1)
                    break;
            }
        }
        //exit;
        $sql = "
    SELECT O70_ANOUSU,O70_CODREC,o70_instit,O57_FONTE,O57_DESCR,round(O70_VALOR,2) as o70_valor,
    round(adicional,2) as adicional,
    round(JANEIRO,2 ) as JANEIRO,
    round(FEVEREIRO,2) as FEVEREIRO,
    round(MARCO,2  )  as MARCO,
    round(ABRIL,2  )  as ABRIL,
    round(MAIO, 2  )  as MAIO,
    round(JUNHO,2  )  as JUNHO,
    round(JULHO,2  )  as JULHO,
    round(AGOSTO,2 )  as AGOSTO,
    round(SETEMBRO,2)  as SETEMBRO,
    round(OUTUBRO,2 )  as OUTUBRO,
    round(NOVEMBRO,2)  as NOVEMBRO,
    round(DEZEMBRO,2)  as DEZEMBRO,
    prev_jan,
    prev_fev,
    prev_mar,
    prev_abr,
    prev_mai,
    prev_jun,
    prev_jul,
    prev_ago,
    prev_set,
    prev_out,
    prev_nov,
    prev_dez

    from work_plano ";
        //--
        if ($this->estrut != null) {
            $sql .= "where O57_FONTE IN  " . $this->estrut;
        }
        $sql .= "order by o57_fonte ";
        $this->result = db_query($sql);
        if ($this->anousu == 2010) {
            //db_criatabela($this->result);
        }
        if ($this->result != false) {
            $this->numrows = pg_numrows($this->result);
        } else {
            $this->numrows = 0;
        }
    }
} //fim

//|00|//cl_translan
//|10|//pega a picture de um determinado campo do orcparametro e gera um input text com a formatacao da mesma
//|15|//[variavel] = new cl_estrutura;

class cl_translan extends cl_contranslan
{

    var $arr_credito = null;
    var $arr_debito = null;
    var $arr_histori = null;
    var $arr_seqtranslr = null;
    var $coddoc = null;
    var $sql = null;
    var $numrows = null;
    var $result = null;
    var $conta_emp = null; //variavel usada no pagamento de RP
    var $sqlerro = false;

    /**
     * Cache do resource das regras da transacao do lancamento
     * @see cl_translan::getRegrasTransacao()
     * @var resource
     */
    var $rsRegrasTransacao = null;

    /**
     * Cache do total de regras da transacao do lancamento
     * @see cl_translan::getRegrasTransacao()
     * @var integer
     */
    var $iRegrasTransacao = 0;

    /**
     * Cache do ultimo sql executado para buscar regras da transacao do lancamento
     * @see cl_translan::getRegrasTransacao()
     * @var string
     */
    var $sSqlRegrasTransacao = null;

    var $it = null;
    // var $it  = "sapiranga";
    // var $it  = "alegrete";
    // var $it  = "guaiba";

    function cl_translan()
    {
        // carlos, alterando libs
    }

    function cl_zera_variaveis()
    {

        $this->arr_credito = null;
        $this->arr_debito = null;
        $this->arr_histori = null;
        $this->arr_seqtranslr = null;
        $this->coddoc = null;
        $this->sql = null;
        $this->numrows = null;
        $this->result = null;
        $this->conta_emp = null; //variavel usada no pagamento de RP
        $this->sqlerro = false;
    }

    /*
   *  Fun��o que retorna o os creditos e os debitos do empenho
   */
    function db_trans_empenho($codcom = null, $anousu, $iCodigoDocumento = '')
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $c46_seqtranslan;

        $this->cl_zera_variaveis();
        $this->coddoc = 1;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $cont = 0;
        $arr_lans = array();

        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);
            if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {

                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    public function getCodigoElemento($iCodigoElemento, $iAno, $iInstituicao)
    {

        $iCodigoReduzido  = "";
        $sSqlCodigoConta  = "select c61_reduz ";
        $sSqlCodigoConta .= "  from conplanoreduz ";
        $sSqlCodigoConta .= " where c61_codcon = {$iCodigoElemento}";
        $sSqlCodigoConta .= "   and c61_anousu = {$iAno}";
        $sSqlCodigoConta .= "   and c61_instit = {$iInstituicao}";
        $rsBuscaCodigoReduzido = db_query($sSqlCodigoConta);
        if (pg_num_rows($rsBuscaCodigoReduzido) > 0) {
            $iCodigoReduzido = db_utils::fieldsMemory($rsBuscaCodigoReduzido, 0)->c61_reduz;
        }
        return $iCodigoReduzido;
    }

    function db_trans_empenho_contrato($iEmpenho, $anousu, $iCodigoDocumento = '')
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $c46_seqtranslan;

        $this->cl_zera_variaveis();
        $this->coddoc = 1;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
        $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_empenho_acordo(
            null,
            "e100_acordo,
        ac16_acordocategoria",
            null,
            "e100_numemp = {$iEmpenho}"
        );
        $iCategoriaContrato = null;;

        $rsContrato  = $oDaoEmpenhoContrato->sql_record($sSqlContrato);
        if ($oDaoEmpenhoContrato->numrows == 0) {
            return false;
        }

        $iCategoriaContrato = db_utils::fieldsMemory($rsContrato, 0)->ac16_acordocategoria;
        $this->sql = $this->sql_query_lr(
            null,
            "c46_seqtranslan,
                                     c47_seqtranslr,
                                     c46_codhist,
                                     c47_credito,
                                     c47_debito,
                                     c47_compara,
                                     c47_ref",
            'c46_ordem',
            " c45_coddoc = " . $this->coddoc . "
                                     and c45_anousu=" . db_getsession("DB_anousu") . " and c47_anousu=$anousu"
        );

        $rsLancamentosContratos = $this->sql_record($this->sql);
        $cont = 0;
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {

            $oDadosTransacao = db_utils::fieldsmemory($rsLancamentosContratos, $i);
            if ($oDadosTransacao->c47_compara == 5 && $oDadosTransacao->c47_ref == $iCategoriaContrato) {

                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($oDadosTransacao->c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$oDadosTransacao->c46_seqtranslan] = $oDadosTransacao->c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont]    = $oDadosTransacao->c47_credito;
                $this->arr_debito[$cont]     = $oDadosTransacao->c47_debito;
                $this->arr_histori[$cont]    = $oDadosTransacao->c46_codhist;
                $this->arr_seqtranslr[$cont] = $oDadosTransacao->c47_seqtranslr;
                $cont++;
            }
        }
    }

    function db_trans_liquidacao_contrato($iEmpenho, $anousu, $iCodigoDocumento)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $c46_seqtranslan;

        $this->cl_zera_variaveis();
        $this->coddoc = $iCodigoDocumento;

        $oDaoEmpenhoContrato = db_utils::getDao("empempenhocontrato");
        $sSqlContrato        = $oDaoEmpenhoContrato->sql_query_empenho_acordo(
            null,
            "e100_acordo,
                                                                          ac16_acordocategoria",
            null,
            "e100_numemp = {$iEmpenho}"
        );

        $iCategoriaContrato = null;
        $rsContrato  = $oDaoEmpenhoContrato->sql_record($sSqlContrato);
        if ($oDaoEmpenhoContrato->numrows == 0) {
            return false;
        }

        $iCategoriaContrato = db_utils::fieldsMemory($rsContrato, 0)->ac16_acordocategoria;
        $this->sql = $this->sql_query_lr(
            null,
            "c46_seqtranslan,
                                     c47_seqtranslr,
                                     c46_codhist,
                                     c47_credito,
                                     c47_debito,
                                     c47_compara,
                                     c47_ref",
            'c46_ordem',
            " c45_coddoc = " . $this->coddoc . "
                                     and c45_anousu=" . db_getsession("DB_anousu") . " and c47_anousu=$anousu"
        );

        $rsLancamentosContratos = $this->sql_record($this->sql);
        $cont = 0;
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {

            $oDadosTransacao = db_utils::fieldsmemory($rsLancamentosContratos, $i);
            if ($oDadosTransacao->c47_compara == 5 && $oDadosTransacao->c47_ref == $iCategoriaContrato) {

                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($oDadosTransacao->c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$oDadosTransacao->c46_seqtranslan] = $oDadosTransacao->c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont]    = $oDadosTransacao->c47_credito;
                $this->arr_debito[$cont]     = $oDadosTransacao->c47_debito;
                $this->arr_histori[$cont]    = $oDadosTransacao->c46_codhist;
                $this->arr_seqtranslr[$cont] = $oDadosTransacao->c47_seqtranslr;
                $cont++;
            }
        }
    }
    /*
   *  Fun��o que retorna o os creditos e os debitos  do estorno do empenho
   */

    function db_trans_estorna_empenho($codcom = null, $anousu, $iCodigoDocumento = null, $iNumeroEmpenho = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $c46_seqtranslan;
        $this->cl_zera_variaveis();
        $this->coddoc = 2;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));


        $cont = 0;
        if ($iCodigoDocumento == 413) {

            $sSqlBuscaLancamentoInclusao = "select c69_credito, c69_debito, c69_codhist
                                        from conlancamval
                                             inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamval.c69_codlan
                                             inner join conlancamemp on conlancamemp.c75_codlan = conlancamval.c69_codlan
                                       where conlancamdoc.c71_coddoc = 412
                                         and conlancamemp.c75_numemp = {$iNumeroEmpenho}
                                       order by conlancamval.c69_sequen asc limit 1 ";
            $rsBuscaLancamentoInclusao = db_query($sSqlBuscaLancamentoInclusao);
            if (!$rsBuscaLancamentoInclusao) {
                throw new Exception("Imposs�vel buscar as contas executadas no documentos 412.");
            }

            $oStdContas = db_utils::fieldsMemory($rsBuscaLancamentoInclusao, 0);
            $this->arr_credito[$cont]    = $oStdContas->c69_debito;
            $this->arr_debito[$cont]     = $oStdContas->c69_credito;
            $this->arr_histori[$cont]    = $oStdContas->c69_codhist;
            $this->arr_seqtranslr[$cont] = 0;
            $cont++;
        }

        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);
            if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {

                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    /*
   *  Fun��o que retorna o os creditos e os debitos  da  liquidacao do empenho
   *    Quando converter base de dados deve ser indicado o seqtranslan do lan�amento de liquida��o  que tem varios elementos...
   *   $seqtranslan = 15 na base dbseller
   *   $seqtranslan = 3  na base guaiba_2112

   */

    function db_trans_liquida($codcom, $codele, $anousu, $iCodigoDocumento = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara, $c46_ordem;
        $this->cl_zera_variaveis();

        $this->coddoc = 3;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta  = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);

            if ($this->numrows > 0) {

                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $cont = 0;
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);
            if ($c47_compara == 1) {
                $comparador = $c47_debito;
            } else if ($c47_compara == 2) {
                $comparador = $c47_credito;
            } else if ($c47_compara == 3) {
                $comparador = $c47_ref;
            } else {
                $comparador = 0;
            }
            if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3)))
                && ($c47_compara == 0 || $comparador == $codele)
            ) {

                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    /*
   *  Fun��o que retorna o os creditos e os debitos  da liquidaca��o de empenho de capital  quando for o inicio do estrut for 34
   *   Preciso indicar o seqtranslan do primeiro lan�amento da liquida��o capital, o que tem varios elementos
   *   $seqtranslan = 22 na base dbseller
   *   $seqtranslan = 4  na base guaiba_2112
   */
    function db_trans_liquida_capital($codcom, $codele, $anousu)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
        $this->cl_zera_variaveis();
        $this->coddoc = 23;
        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta  = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);
            if ($this->numrows > 0) {
                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $cont = 0;
        $arr_lans = array();

        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);
            //comparador deve ser por debito
            if ($c47_compara == 1) {
                $comparador = $c47_debito;
            } else if ($c47_compara == 2) {
                $comparador = $c47_credito;
            } else if ($c47_compara == 3) {
                $comparador = $c47_ref;
            } else {
                $comparador = 0;
            }
            if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3)))
                && ($c47_compara == 0 || $comparador == $codele)
            ) {
                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    function db_trans_estorna_pagamento_prestacao_contas($codele, $reduzido, $anousu, $iCodigoDocumento, $iNumeroEmpenho = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_seqtranslr, $c61_reduz, $c47_compara, $c47_ref;
        $this->cl_zera_variaveis();
        $this->coddoc = $iCodigoDocumento;
        $coddoc_liq = 3;
        $coddoc_liq_capital = 23;

        $codhist = 9006;
        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);

            if ($this->numrows > 0) {

                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }
        $cont = 0;
        $this->sql = $this
            ->sql_query_lr(
                null,
                "c47_ref, c47_compara, c47_seqtranslr,c47_credito,c47_debito",
                'c46_ordem',
                "c45_coddoc    = {$this->coddoc}
                                        and c45_anousu = " . db_getsession("DB_anousu")
                    . "
                                        and c47_anousu = {$anousu}"
            );
        $this->result = $this->sql_record($this->sql);

        if (USE_PCASP && $this->coddoc == 91) {

            if (empty($iNumeroEmpenho)) {
                return false;
            }

            $oEmpenhoFinanceiro  = new EmpenhoFinanceiro($iNumeroEmpenho);
            $oStdPrestacaoContas = $oEmpenhoFinanceiro->getDadosPrestacaoContas();
            if (!$oStdPrestacaoContas) {
                return false;
            }
        }

        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if (USE_PCASP && $this->coddoc == 91) {

                if ($c47_compara == 6 && $oStdPrestacaoContas->e45_tipo == $c47_ref) {

                    $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                    $this->arr_credito[$cont]    = $c47_credito;
                    $this->arr_debito[$cont]     = $c47_debito;
                    $this->arr_histori[$cont]    = $codhist;
                    $cont++;
                }
            } else {

                if ($c47_debito != 0) {

                    $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                    $this->arr_credito[$cont] = $c47_credito;
                    $this->arr_debito[$cont] = $c47_debito;
                    $this->arr_histori[$cont] = $codhist;
                    $cont++;
                }
            }
        }
    }
    /*
   *  Fun��o que retorna o os creditos e os debitos  do estorno liquidaca��o de empenho   quando for o inicio do estrut for 33
   *    Necess�rio informar o seqtranslan de estorno de liquida��o
   *    $seqtranslan = 30 na base dbseller
   *    $seqtranslan = 34 na base guaiba_2112
   */

    function db_trans_estorna_liquida($codcom, $codele, $anousu, $iCodigoDocumento = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
        $this->cl_zera_variaveis();
        $this->coddoc = 4;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }
        $iAnoSessao = db_getsession("DB_anousu");
        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu={$iAnoSessao}";
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);
            if ($this->numrows > 0) {
                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        /**
         * Validamos o ano da sess�o com o ano do empenho. Caso o ano n�o seja o mesmo os lan�amentos eram todos apagados,
         * com esta valida��o evitamos o erro.
         */
        if ($iAnoSessao != $anousu) {

            $sMensagemErro  = "O ano [{$anousu}] n�o � igual ao ano da sess�o [{$iAnoSessao}]. Verifique a data do ";
            $sMensagemErro .= "lan�amento cont�bil do empenho referente a ao documento {$iCodigoDocumento}";
            $this->erro_msg = $sMensagemErro;
            $this->sqlerro  = true;
            return false;
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $cont = 0;
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);

            //comparador deve sCVer por credito
            if ($c47_compara == 1) {
                $comparador = $c47_debito;
            } else if ($c47_compara == 2) {
                $comparador = $c47_credito;
            } else if ($c47_compara == 3) {
                $comparador = $c47_ref;
            } else {
                $comparador = 0;
            }
            if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3)))
                && ($c47_compara == 0 || $comparador == $codele)
            ) {
                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
        return true;
    }

    /* *****************************  	*	**********************************	*	*****************	***
   *  Fun��o que retorna o os creditos e os debitos  do estorno  liquidaca��o de empenho de capital  quando for o inicio do estrut for 34
   *    Necess�rio informar o seqtranslan de estorno de liquida��o capital
   *    $seqtranslan = 38 a base dbseller
   *    $seqtranslan = 42 a base guaiba_2112
   */

    function db_trans_estorna_liquida_capital($codcom, $codele, $anousu)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
        $this->cl_zera_variaveis();
        $this->coddoc = 24;

        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);

            if ($this->numrows > 0) {

                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $cont = 0;
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);
            //comparador deve ser por credito
            if ($c47_compara == 1) {
                $comparador = $c47_debito;
            } else if ($c47_compara == 2) {
                $comparador = $c47_credito;
            } else if ($c47_compara == 3) {
                $comparador = $c47_ref;
            } else {
                $comparador = 0;
            }
            if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3)))
                && ($c47_compara == 0 || $comparador == $codele)
            ) {
                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    /*
   *  Fun��o que retorna o os creditos e os debitos  do pagamento de empenho
   *    Quando converter base de dados deve ser indicado o seqtranslan do lan�amento de liquida��o  que tem varios elementos...
   *    normalmente � o primeiro lan�amento

  para pagamento � preciso indicar o primeiro lan�amento da liquidacao
   *   $seqtranslan = 15 na base dbseller
   *   $seqtranslan = 3  na base guaiba_2112


  liquida��o capital
   *    $seqtranslan_liq_capital = 22 a base dbseller
   *    $seqtranslan_liq         = 4 a base guaiba_2112
  tambem � preciso indicar o codigo do historico

   */

    function db_trans_pagamento($codele, $reduzido, $anousu, $iNumEmp = null, $iCodigoDocumento = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_seqtranslr, $c61_reduz;
        $this->cl_zera_variaveis();
        $this->coddoc = 5;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $coddoc_liq = 3;
        $coddoc_liq_capital = 23;


        $iDesdobramentoEmpenho = $codele;

        $codhist = 9005;
        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);

            if ($this->numrows > 0) {

                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        $sWhereConta = " and c47_debito  = $codele  ";
        $cont = 0;

        /**
         * Verificamos a que grupo o desdobramento do empenho se refere e aplicamos o
         * documento de acordo com este grupo
         */
        require_once(modification("classes/empenho.php"));
        $iUltimoDocumentoExecutado = empenho::buscaUltimoDocumentoExecutado($iNumEmp, db_getsession('DB_anousu'));
        if (USE_PCASP) {

            $oGrupoOrcamento = GrupoContaOrcamento::getGrupoConta($iDesdobramentoEmpenho, $anousu);

            if ($oGrupoOrcamento) {

                $aDocumentosEvento = DocumentoContabil::getDocumentosPorGrupo($oGrupoOrcamento->getCodigo());
                if (isset($aDocumentosEvento['liquidacao'])) {

                    $coddoc_liq         = $aDocumentosEvento['liquidacao'];
                    $coddoc_liq_capital = $aDocumentosEvento['liquidacao'];
                }

                /**
                 * Caso o empenho seja de amortizacao da divida, ou precatorios, ferias/13 salario
                 * devemos procurar a contra partida no lancamento de empenho.
                 */
                if (in_array($oGrupoOrcamento->getCodigo(), array(10, 12, 13, 15))) {

                    $coddoc_liq         = $aDocumentosEvento['empenho'];
                    $coddoc_liq_capital = $aDocumentosEvento['empenho'];
                    $sWhereConta = "and contranslan.c46_ordem = 1 limit 1";
                }

                if (in_array($oGrupoOrcamento->getCodigo(), array(8, 9))) {

                    $coddoc_liq         = $aDocumentosEvento['em_liquidacao'];
                    $coddoc_liq_capital = $aDocumentosEvento['em_liquidacao'];

                    if (!empenho::possuiLancamentoDeControle($iNumEmp, db_getsession('DB_anousu'), array($aDocumentosEvento['em_liquidacao']))) {

                        /**
                         * Verifica se foi executado lan�amentos de controle do grupo 8 - no caso de dispensa de tombamento
                         */
                        $aDocumentosEventoGrupo8 = DocumentoContabil::getDocumentosPorGrupo(8);

                        if (empenho::possuiLancamentoDeControle($iNumEmp, db_getsession('DB_anousu'), array($aDocumentosEventoGrupo8['em_liquidacao']))) {

                            $coddoc_liq         = $aDocumentosEventoGrupo8['em_liquidacao'];
                            $coddoc_liq_capital = $aDocumentosEventoGrupo8['em_liquidacao'];
                        } else {

                            $coddoc_liq         = 3;
                            $coddoc_liq_capital = 3;
                        }
                    }
                }

                if ($oGrupoOrcamento->getCodigo() == 8) {

                    $oEmpenhoFinanceiro = new EmpenhoFinanceiro($iNumEmp);
                    $aItensEmpenho      = $oEmpenhoFinanceiro->getItens();
                    /**
                     * Verificamos se existe mais de uma conta para o item do material
                     */
                    $oDaoMatmater   = db_utils::getDao("matmater");
                    $aItens         = array();
                    foreach ($aItensEmpenho as $oItem) {
                        $aItens[] = $oItem->getItemMaterialCompras()->getMaterial();
                    }
                    $sItens  = implode(",", $aItens);
                    $sWhere  = "m63_codpcmater in({$sItens})";
                    $sWhere .= " and m66_anousu = " . db_getsession("DB_anousu");
                    $sWhere .= " and c61_instit = " . db_getsession("DB_instit");

                    $sSqlContaGrupo  = $oDaoMatmater->sql_query_grupo(
                        null,
                        "c61_reduz, m66_codcon, m60_codmater, m60_descr",
                        null,
                        $sWhere
                    );
                    $rsContaGrupo    = $oDaoMatmater->sql_record($sSqlContaGrupo);
                    $aContasMaterial = array();
                    $aItens          = db_utils::getCollectionByRecord($rsContaGrupo);
                    foreach ($aItens as $oItem) {

                        if (!in_array($oItem->c61_reduz, $aContasMaterial)) {
                            $aContasMaterial[] = $oItem->c61_reduz;
                        }
                    }

                    $sWhereConta = " and c47_debito  = {$aContasMaterial[0]}";
                }
            }

            /**
             * Verificamos se o empenho � um empenho de prestacao de contas:
             */
            if (!empty($iNumEmp) && USE_PCASP) {

                $sSqlPrestacaoContas = "select 1 ";
                $sSqlPrestacaoContas .= "  from emppresta";
                $sSqlPrestacaoContas .= " where e45_numemp = {$iNumEmp}";
                $sSqlPrestacaoContas .= "   and extract(year from e45_data)::integer > 2012 ";
                $rsPrestacaoContas = db_query($sSqlPrestacaoContas);
                if (pg_num_rows($rsPrestacaoContas) > 0) {

                    $coddoc_liq         = 412;
                    $coddoc_liq_capital = 412;
                    $sWhereConta        = " and contranslan.c46_ordem = 1 limit 1";
                }
            }
        }

        $aDocumentosConferencia = array(
            204 // LIQUIDA��O DESPESA MATERIAL DE CONSUMO
            , 208 // CONTROLE DESPESA EM LIQUIDA��O MP
            , 209 // ESTORNO DE CONTROLE DESPESA EM LIQUIDA��O MP
            , 210 // CONTROLE DESPESA EM LIQUIDA��O MAT ALMOX
            , 211 // ESTORNO DE CONTROLE DESPESA EM LIQUIDA��O MAT ALMO
            , 304 // EMPENHO DA PROVIS�O DE F�RIAS
            , 305 // ESTORNO DE EMPENHO DA PROVIS�O DE F�RIAS
            , 308 // EMPENHO DA PROVIS�O DE 13� SAL�RIO
            , 309 // ESTORNO DE EMPENHO DA PROVIS�O DE 13� SAL�RIO
            , 412 // LIQUIDA��O SUPRIMENTO DE FUNDOS
        );


        $iAnoSessao         = db_getsession("DB_anousu");
        $iInstituicaoSessao = db_getsession("DB_instit");

        //rotina que pega o o valor que foi creditado na liquidacao para colocar no debit  do pagamento...
        if (USE_PCASP /*&& (in_array($coddoc_liq, $aDocumentosConferencia) || in_array($coddoc_liq_capital, $aDocumentosConferencia))*/) {

            $c47_credito     = self::getContaCreditoLiquidacao($iNumEmp, array($coddoc_liq_capital, $coddoc_liq, 84, 3, 23, 202, 204, 206), $iAnoSessao);
            $c47_seqtranslr  = 0;
            $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($coddoc_liq, $iAnoSessao, $iInstituicaoSessao);
            $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
            $codhist         = $aLancamentos[0]->getHistorico();
        } else {

            $sSqlContaCreditoLiquidacao = "select  c47_credito, ";
            $sSqlContaCreditoLiquidacao .= "       c47_seqtranslr, ";
            $sSqlContaCreditoLiquidacao .= "       c46_codhist ";
            $sSqlContaCreditoLiquidacao .= "  from contranslan ";
            $sSqlContaCreditoLiquidacao .= "       inner join contrans on c45_seqtrans = c46_seqtrans ";
            $sSqlContaCreditoLiquidacao .= "       inner join contranslr on c47_seqtranslan = c46_seqtranslan ";
            $sSqlContaCreditoLiquidacao .= " where (c45_coddoc = $coddoc_liq_capital or c45_coddoc = $coddoc_liq) ";
            $sSqlContaCreditoLiquidacao .= "   and c45_anousu  = {$iAnoSessao}";
            $sSqlContaCreditoLiquidacao .= "   and c47_instit  = {$iInstituicaoSessao}";
            $sSqlContaCreditoLiquidacao .= "  {$sWhereConta}";
            $result  = @db_query($sSqlContaCreditoLiquidacao);
            $numrows = @pg_numrows($result);
            if ($numrows == 0) {
                throw new Exception('Lan�amento de ordem 1 sem conta a credito para lan�amento. Verifique conta PCASP ' . $codele);
            }
            db_fieldsmemory($result, 0);
        }

        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $this->arr_credito[$cont]    = $reduzido;
        $this->arr_debito[$cont]     = $c47_credito;
        $this->arr_histori[$cont]    = $codhist;
        $cont++;

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);
            if ($c47_credito != 0) {
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $codhist;
                $cont++;
            }
        }
    }

    function db_trans_pagamento_prestacao_contas($codele, $reduzido, $anousu, $iNumeroEmpenho = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_compara, $c47_ref, $c47_seqtranslr, $c61_reduz, $c46_ordem;
        $this->cl_zera_variaveis();
        $this->coddoc = 90;
        $coddoc_liq = 3;
        $coddoc_liq_capital = 23;

        $iAnoSessao = db_getsession("DB_anousu");

        $codhist = 9005;
        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $iCodigoReduzido = $this->getCodigoElemento($codele, $iAnoSessao, db_getsession("DB_instit"));
            if (!empty($iCodigoReduzido)) {
                $codele = $iCodigoReduzido;
            }
        }

        $cont = 0;
        $this->sql = $this->sql_query_lr(
            null,
            "c47_ref, c47_compara, c47_seqtranslr,c47_credito,c47_debito",
            'c46_ordem',
            "    c45_coddoc = {$this->coddoc}
                                      and c45_anousu = {$iAnoSessao}
                                      and c47_anousu = $anousu"
        );
        $this->result = $this->sql_record($this->sql);
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if (USE_PCASP) {

                if (empty($iNumeroEmpenho)) {
                    continue;
                }

                $oEmpenhoFinanceiro  = new EmpenhoFinanceiro($iNumeroEmpenho);
                $oStdPrestacaoContas = $oEmpenhoFinanceiro->getDadosPrestacaoContas();

                if (!$oStdPrestacaoContas) {
                    continue;
                }

                if ($c47_compara == 6 && $oStdPrestacaoContas->e45_tipo == $c47_ref) {

                    $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                    $this->arr_credito[$cont]    = $c47_credito;
                    $this->arr_debito[$cont]     = $c47_debito;
                    $this->arr_histori[$cont]    = $codhist;
                    $cont++;
                }
            } else {

                if ($c47_credito != 0) {

                    $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                    $this->arr_credito[$cont] = $c47_credito;
                    $this->arr_debito[$cont] = $c47_debito;
                    $this->arr_histori[$cont] = $codhist;
                    $cont++;
                }
            }
        }
    }

    /**
     * @param $iNumeroEmpenho
     * @param array $aCodigoDocumento
     * @param $iAnoSessao
     * @return mixed|integer
     * @throws Exception
     */
    private static function getContaCreditoLiquidacao($iNumeroEmpenho, array $aCodigoDocumento, $iAnoSessao)
    {

        $sCodigoDocumento = implode(",", $aCodigoDocumento);

        $sSqlContaCreditoLiquidacao = " SELECT conlancamval.c69_credito FROM conlancamemp
                                    INNER JOIN conlancam ON conlancam.c70_codlan = conlancamemp.c75_codlan
                                    INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancam.c70_codlan
                                    INNER JOIN conlancamval ON conlancamval.c69_codlan = conlancam.c70_codlan
                                    INNER JOIN conplanoreduz ON c61_reduz = c69_credito AND c61_anousu = c69_anousu
                                    INNER JOIN conplano ON c60_codcon = c61_codcon AND c61_anousu = c60_anousu
                                    WHERE conlancamemp.c75_numemp = {$iNumeroEmpenho}
                                      AND conlancamdoc.c71_coddoc IN ({$sCodigoDocumento})
                                      AND conlancam.c70_anousu = {$iAnoSessao}
                                      AND substr(c60_estrut,1,2) = '21'
                                    ORDER BY conlancamval.c69_sequen ASC, conlancamval.c69_codlan DESC
                                    LIMIT 1";
        $rsBuscaContaLiquidacao = db_query($sSqlContaCreditoLiquidacao);
        if (!$rsBuscaContaLiquidacao || pg_num_rows($rsBuscaContaLiquidacao) == 0) {
            throw new Exception("Ocorreu um erro ao buscar a conta da liquida��o do empenho {$iNumeroEmpenho}.\n" . pg_last_error());
        }
        return db_utils::fieldsMemory($rsBuscaContaLiquidacao, 0)->c69_credito;
    }

    /*
   *  Fun��o que retorna o os creditos e os debitos  do estorno de  pagamento de empenho
   *    Quando converter base de dados deve ser indicado o seqtranslan do lan�amento de liquida��o  que tem varios elementos...
   *    normalmente � o primeiro lan�amento

  para pagamento � preciso indicar o primeiro lan�amento da liquidacao
   *   $seqtranslan = 15 na base dbseller
   *   $seqtranslan = 3  na base guaiba_2112


  liquida��o capital
   *    $seqtranslan_liq_capital = 22 a base dbseller
   *    $seqtranslan_liq         = 4 a base guaiba_2112
  //tambem � preciso indicar o codigo do historico

   */

    function db_trans_estorna_pagamento($codele, $reduzido, $anousu, $iNumEmp, $iCodigoDocumento = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_seqtranslr, $c61_reduz;
        $this->cl_zera_variaveis();
        $this->coddoc = 6;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }
        $coddoc_liq = 3;
        $coddoc_liq_capital = 23;

        $iDesdobramentoEmpenho = $codele;

        $codhist = 9006;
        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);

            if ($this->numrows > 0) {

                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        $cont = 0;
        $sWhereConta = " and c47_debito  = $codele  ";

        /**
         * Verificamos a que grupo o desdobramento do empenho se refere e aplicamos o
         * documento de acordo com este grupo
         */
        require_once(modification("classes/empenho.php"));
        $iUltimoDocumentoExecutado = empenho::buscaUltimoDocumentoExecutado($iNumEmp, db_getsession('DB_anousu'));
        $oGrupoOrcamento = GrupoContaOrcamento::getGrupoConta($iDesdobramentoEmpenho, $anousu);
        if ($oGrupoOrcamento && USE_PCASP) {

            $aDocumentosEvento = DocumentoContabil::getDocumentosPorGrupo($oGrupoOrcamento->getCodigo());
            if (isset($aDocumentosEvento['liquidacao'])) {

                $coddoc_liq         = $aDocumentosEvento['liquidacao'];
                $coddoc_liq_capital = $aDocumentosEvento['liquidacao'];
            }
            /**
             * Caso o empenho seja de amortizacao da divida, ou precatorios,
             * devemos procurar a contra partida na no lancamento de empenho.
             */
            if (in_array($oGrupoOrcamento->getCodigo(), array(10, 12, 13, 15))) {

                $coddoc_liq = $aDocumentosEvento['empenho'];
                $coddoc_liq_capital = $aDocumentosEvento['empenho'];
                $sWhereConta = "and contranslan.c46_ordem = 1 limit 1";
            }

            if (in_array($oGrupoOrcamento->getCodigo(), array(8, 9))) {

                $coddoc_liq         = $aDocumentosEvento['em_liquidacao'];
                $coddoc_liq_capital = $aDocumentosEvento['em_liquidacao'];
                if (!empenho::possuiLancamentoDeControle($iNumEmp, db_getsession('DB_anousu'), array($aDocumentosEvento['liquidacao']))) {

                    $coddoc_liq         = 3;
                    $coddoc_liq_capital = 3;
                }
            }

            if ($oGrupoOrcamento->getCodigo() == 8) {

                $oEmpenhoFinanceiro = new EmpenhoFinanceiro($iNumEmp);
                $aItensEmpenho      = $oEmpenhoFinanceiro->getItens();
                /**
                 * Verificamos se existe mais de uma conta para o item do material
                 */
                $oDaoMatmater   = db_utils::getDao("matmater");
                $aItens         = array();
                foreach ($aItensEmpenho as $oItem) {
                    $aItens[] = $oItem->getItemMaterialCompras()->getMaterial();
                }
                $sItens  = implode(",", $aItens);
                $sWhere  = "m63_codpcmater in({$sItens})";
                $sWhere .= " and m66_anousu = " . db_getsession("DB_anousu");
                $sWhere .= " and c61_instit  = " . db_getsession("DB_instit");

                $sSqlContaGrupo  = $oDaoMatmater->sql_query_grupo(
                    null,
                    "c61_reduz, m66_codcon, m60_codmater, m60_descr",
                    null,
                    $sWhere
                );
                $rsContaGrupo    = $oDaoMatmater->sql_record($sSqlContaGrupo);
                $aContasMaterial = array();
                $aItens          = db_utils::getCollectionByRecord($rsContaGrupo);
                foreach ($aItens as $oItem) {

                    if (!in_array($oItem->c61_reduz, $aContasMaterial)) {
                        $aContasMaterial[] = $oItem->c61_reduz;
                    }
                }

                $sWhereConta = " and c47_debito  = {$aContasMaterial[0]}";
            }
        }

        /**
         * Verificamos se o empenho nao � um empenho de prestacao de contas:
         */
        if (!empty($iNumEmp) && USE_PCASP) {

            $sSqlPrestacaoContas = "select 1 ";
            $sSqlPrestacaoContas .= "  from emppresta";
            $sSqlPrestacaoContas .= " where e45_numemp = {$iNumEmp}";
            $sSqlPrestacaoContas .= "   and extract(year from e45_data)::integer > 2012 ";
            $rsPrestacaoContas = db_query($sSqlPrestacaoContas);
            if (pg_num_rows($rsPrestacaoContas) > 0) {

                $coddoc_liq = 412;
                $coddoc_liq_capital = 412;
                $sWhereConta = " and contranslan.c46_ordem = 1 limit 1";
            }
        }

        $cont = 0;
        $iAnoSessao         = db_getsession("DB_anousu");
        $iInstituicaoSessao = db_getsession("DB_instit");
        $aDocumentosConferencia = array(208, 209, 210, 211, 412);

        if (USE_PCASP /*&& (in_array($coddoc_liq, $aDocumentosConferencia) || in_array($coddoc_liq_capital, $aDocumentosConferencia))*/) {

            /**
             * Buscamos as contas do pagamento para inverter
             */
            $c47_seqtranslr  = 0;
            $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($coddoc_liq, $iAnoSessao, $iInstituicaoSessao);
            $aLancamentos    = $oEventoContabil->getEventoContabilLancamento();
            $codhist         = $aLancamentos[0]->getHistorico();

            $sSqlBuscaContaPagamento  = "select c69_debito,";
            $sSqlBuscaContaPagamento .= "       c69_credito";
            $sSqlBuscaContaPagamento .= "  from conlancamemp";
            $sSqlBuscaContaPagamento .= "       inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamemp.c75_codlan";
            $sSqlBuscaContaPagamento .= "       inner join conlancamval on conlancamval.c69_codlan = conlancamemp.c75_codlan";
            $sSqlBuscaContaPagamento .= " where conlancamemp.c75_numemp = {$iNumEmp}";
            $sSqlBuscaContaPagamento .= "   and conlancamdoc.c71_coddoc = 5";
            $sSqlBuscaContaPagamento .= " order by conlancamval.c69_sequen asc";
            $sSqlBuscaContaPagamento .= " limit 1;";

            $rsBuscaContasPagamento = db_query($sSqlBuscaContaPagamento);
            if (pg_num_rows($rsBuscaContasPagamento) == 0 || !$rsBuscaContasPagamento) {
                throw new Exception("N�o foi poss�vel buscar a conta do pagamento.");
            }

            $oStdContaPagamento = db_utils::fieldsMemory($rsBuscaContasPagamento, 0);
            $c47_credito = $oStdContaPagamento->c69_debito;
        } else {

            $sSqlContaCreditoLiquidacao = "select  c47_credito, ";
            $sSqlContaCreditoLiquidacao .= "       c47_seqtranslr, ";
            $sSqlContaCreditoLiquidacao .= "       c46_codhist ";
            $sSqlContaCreditoLiquidacao .= "  from contranslan ";
            $sSqlContaCreditoLiquidacao .= "       inner join contrans on c45_seqtrans = c46_seqtrans ";
            $sSqlContaCreditoLiquidacao .= "       inner join contranslr on c47_seqtranslan = c46_seqtranslan ";
            $sSqlContaCreditoLiquidacao .= " where (c45_coddoc = $coddoc_liq_capital or c45_coddoc = $coddoc_liq) ";
            $sSqlContaCreditoLiquidacao .= "   and c45_anousu  = {$iAnoSessao}";
            $sSqlContaCreditoLiquidacao .= "   and c47_instit  = {$iInstituicaoSessao}";
            $sSqlContaCreditoLiquidacao .= "  {$sWhereConta}";
            $result  = @db_query($sSqlContaCreditoLiquidacao);
            $numrows = @pg_numrows($result);
            if ($numrows == 0) {
                throw new Exception('Lan�amento de ordem 1 sem conta a credito para lan�amento. Verifique conta PCASP ' . $codele);
            }
            db_fieldsmemory($result, 0);
        }

        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
        $this->arr_credito[$cont]    = $c47_credito;
        $this->arr_debito[$cont]     = $reduzido;
        $this->arr_histori[$cont]    = $codhist;
        $cont++;

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);
            if ($c47_debito != 0) {
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $this->arr_credito[$cont]    = $c47_credito;
                $this->arr_debito[$cont]     = $c47_debito;
                $this->arr_histori[$cont]    = $codhist;
                $cont++;
            }
        }
    }


    /**
     * Metodo que busca o par de contas cr�dito/d�bito para serem lan�adas cont�bilmente.
     *
     * Este m�todo � utilizado unicamente para buscar as contas quando o pagamento de empenho for
     * do tipo "Restos � Pagar", exerc�cio anterior
     *
     * @param integer $codcom - tipo de compra
     * @param integer $codele - c�digo do elemento do empenho
     * @param integer $anousu - ano do empenho
     * @param integer $numemp - sequencial do empenho
     * @todo refatorar
     */
    public function db_trans_liquida_resto($codcom, $codele, $anousu, $numemp, $iCodigoDocumento = 33)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_instit, $c47_seqtranslr, $c61_reduz, $c47_tiporesto, $c46_ordem;

        $this->cl_zera_variaveis();
        $this->coddoc = $iCodigoDocumento;
        $codele = $this->getVinculoPcasp($codele);

        if ($iCodigoDocumento == 39) {
            $anousu = db_getsession('DB_anousu');
        }

        /**
         * Caso o elemento esteja setado, procuramos o reduzido
         */
        $iAnoSessao         = db_getsession("DB_anousu");
        $iInstituicaoSessao = db_getsession("DB_instit");
        if (!empty($codele)) {

            $sWhereReduzido     = "     c61_codcon = {$codele}";
            $sWhereReduzido    .= " and c61_anousu = {$iAnoSessao}";
            $sWhereReduzido    .= " and c61_instit = {$iInstituicaoSessao}";
            $oDaoConPlanoReduz  = db_utils::getDao("conplanoreduz");
            $sSqlBuscaReduzido  = $oDaoConPlanoReduz->sql_query_file(null, null, "c61_reduz", null, $sWhereReduzido);
            $rsBuscaReduzido    = $oDaoConPlanoReduz->sql_record($sSqlBuscaReduzido);
            if ($oDaoConPlanoReduz->numrows > 0) {
                $codele = db_utils::fieldsMemory($rsBuscaReduzido, 0)->c61_reduz;
            }
        }

        /**
         * Buscamos o tipo de resto cadastrado para o empenho que estamos liquidando
         */
        $oDaoEmpResto   = db_utils::getDao("empresto");
        $sSqlBuscaResto = $oDaoEmpResto->sql_query_file(null, null, "e91_codtipo", null, "e91_numemp = {$numemp}");
        $rsBuscaResto   = $oDaoEmpResto->sql_record($sSqlBuscaResto);
        if ($oDaoEmpResto->erro_status == 0) {

            $this->sqlerro  = true;
            $this->erro_msg = "Tipo de resto a pagar n�o encontrado para o empenho {$numemp}. Contate suporte.";
        }

        $e91_codtipo = db_utils::fieldsMemory($rsBuscaResto, 0)->e91_codtipo;

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $cont = 0;
        $arr_lans = array();
        /**
         * Percorremos as contas encontradas e validamos se devemos lan�ar nestas ou n�o
         */
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto && !USE_PCASP) {
                continue;
            }

            if ($c47_debito != $codele && USE_PCASP && $c46_ordem == 1 && $iCodigoDocumento != 39) {
                continue;
            }

            //deixa passar de a institui��o for zero  ou entao se ela vier preenchida deve ser iqual � do db_getsession('DB_instit');
            if (($c47_instit == 0 || $c47_instit == '') ||
                (($c47_instit != '' && $c47_instit != 0) &&
                    $c47_instit == $iInstituicaoSessao)
            ) {

                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }

                $this->arr_credito[$cont]    = $c47_credito;
                $this->arr_debito[$cont]     = $c47_debito;
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    //os parametros codcom e codele n�o estam sendo utilizados ainda

    public function db_trans_estorna_liquida_resto($codcom, $codele, $anousu, $numemp, $iCodigoDocumento = 34)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_tiporesto, $c46_seqtranslan, $c47_instit, $c47_seqtranslr, $c61_reduz, $c46_ordem;
        $this->cl_zera_variaveis();
        $this->coddoc = $iCodigoDocumento;

        $iAnoSessao         = db_getsession("DB_anousu");
        $iInstituicaoSessao = db_getsession("DB_instit");
        $codele = $this->getVinculoPcasp($codele);

        if ($iCodigoDocumento == 40) {
            $anousu = $iAnoSessao;
        }

        if (!empty($codele)) {

            $sWhereReduzido    = "     c61_codcon = {$codele}";
            $sWhereReduzido   .= " and c61_anousu = {$iAnoSessao}";
            $sWhereReduzido   .= " and c61_instit = {$iInstituicaoSessao}";
            $oDaoConPlanoReduz = db_utils::getDao('conplanoreduz');
            $sSqlBuscaReduzido = $oDaoConPlanoReduz->sql_query_file(null, null, 'c61_reduz', null, $sWhereReduzido);
            $rsBuscaReduzido   = $oDaoConPlanoReduz->sql_record($sSqlBuscaReduzido);
            if ($oDaoConPlanoReduz->numrows > 0) {
                $codele = db_utils::fieldsMemory($rsBuscaReduzido, 0)->c61_reduz;
            }
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $oDaoEmpResto       = db_utils::getDao('empresto');
        $sSqlBuscaTipoResto = $oDaoEmpResto->sql_query_file(null, null, "e91_codtipo", null, "e91_numemp = {$numemp}");
        $rsBuscaTipoResto   = $oDaoEmpResto->sql_record($sSqlBuscaTipoResto);
        if ($oDaoEmpResto->erro_status == 0) {

            $this->sqlerro  = true;
            $this->erro_msg = "Tipo de resto n�o encontrado para o empenho {$numemp}. Contate o Suporte.";
        }

        $e91_codtipo = db_utils::fieldsMemory($rsBuscaTipoResto, 0)->e91_codtipo;


        //declara array para verifica��o
        $arr_lans = array();
        $cont     = 0;
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto && !USE_PCASP) {
                continue;
            }

            if ($c47_credito != $codele && USE_PCASP && $c46_ordem == 1 && $iCodigoDocumento != 40) {
                continue;
            }


            //deixa passar de a institui��o for zero  ou entao se ela vier preenchida deve ser iqual � do db_getsession('DB_instit');
            if (($c47_instit == 0 || $c47_instit == '')
                || (($c47_instit != '' && $c47_instit != 0) && $c47_instit == $iInstituicaoSessao)
            ) {
                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont]    = $c47_credito;
                $this->arr_debito[$cont]     = $c47_debito;
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    //o parametro codele ainda naum esta sendo utilizado

    function db_trans_pagamento_resto($codele, $reduz, $anousu, $numemp, $iCodDoc = 35)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_tiporesto, $c46_ordem;
        $this->cl_zera_variaveis();
        $this->coddoc = $iCodDoc;

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        //----------------------------------------------------------------------
        $sql = "select e91_codtipo from empresto where e91_numemp=$numemp and e91_anousu = " . db_getsession("DB_anousu");
        $result = @db_query($sql);
        $numrows = @pg_numrows($result);
        if ($numrows > 0) {
            $e91_codtipo = @pg_result($result, 0, 0);
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Resto a pagar n�o encontrado na tabela empresto... Contate suporte.";
        }
        //----------------------------------------------------------------------

        $cont = 0;

        require_once(modification("classes/empenho.php"));
        //declara array para verifica��o
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);
            if (!USE_PCASP || $this->coddoc == 35) {

                if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
                    continue;
                }
            }

            /**
             * Quando for o lan�amento de ordem 1, buscamos a conta creditada na liquida��o de RP
             */
            if ($c46_ordem == 1 && USE_PCASP && $this->coddoc != 35) {

                /**
                 * verifica se tem lancamento de controle de liquidacao e busca conta credito do controle
                 */
                if ($this->coddoc == 37 && empenho::possuiLancamentoDeControle($numemp, db_getsession("DB_anousu"), array(212))) {
                    $c47_debito = self::getContaCreditoLiquidacao($numemp, array(212), db_getsession("DB_anousu"));
                } elseif ($this->coddoc == 37 && empenho::possuiLancamentoDeControle($numemp, $anousu, array(208))) {
                    $c47_debito = self::getContaCreditoLiquidacao($numemp, array(208), $anousu);
                } elseif ($this->coddoc == 37 && empenho::possuiLancamentoDeControle($numemp, $anousu, array(210))) {
                    $c47_debito = self::getContaCreditoLiquidacao($numemp, array(210), $anousu);
                } elseif ($this->coddoc == 37 && empenho::possuiLancamentoDeControle($numemp, db_getsession("DB_anousu"), array(214))) {
                    $c47_debito = self::getContaCreditoLiquidacao($numemp, array(214), db_getsession("DB_anousu"));
                } else {

                    $sSqlContaCredito = " SELECT c69_credito
                                    FROM conlancamemp
                                    INNER JOIN conlancamdoc ON conlancamemp.c75_codlan = conlancamdoc.c71_codlan
                                    INNER JOIN conlancamval ON conlancamdoc.c71_codlan = conlancamval.c69_codlan
                                    INNER JOIN conlancam ON conlancamval.c69_codlan = conlancam.c70_codlan
                                    WHERE c75_numemp = {$numemp}
                                      AND c71_coddoc = 33
                                      AND c70_anousu = " . db_getsession("DB_anousu") . "
                                    ORDER BY c69_sequen
                                    LIMIT 1 ";

                    $rsBuscaContaCredito = db_query($sSqlContaCredito);

                    if (pg_num_rows($rsBuscaContaCredito) == 0) {

                        $this->sqlerro  = true;
                        $this->erro_msg = "Conta creditada na liquida��o de RP n�o encontrada. Contate o suporte.";
                    }
                    $iContaCredito  =  db_utils::fieldsMemory($rsBuscaContaCredito, 0)->c69_credito;
                    $c47_debito     = $iContaCredito;
                }
            }

            //------------------------------------------------------------------------
            //verifica��o para naum incluir duas vezes o mesmo seqtranslan
            if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                continue;
            } else {
                $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
            }
            //------------------------------------------------------------------------

            if ($c47_credito == 0 || $c47_credito == '') {
                $c47_credito = $reduz;
                $this->conta_emp = $c47_debito;
            }

            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $this->arr_credito[$cont]    = $c47_credito;
            $this->arr_debito[$cont]     = $c47_debito;
            $this->arr_histori[$cont]    = $c46_codhist;
            $cont++;
        }
    }

    function db_trans_estorna_pagamento_resto($codele, $reduz, $anousu, $numemp, $iCodDoc = 36)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_tiporesto, $c46_ordem;
        $this->cl_zera_variaveis();
        $this->coddoc = $iCodDoc;

        require_once(modification("classes/empenho.php"));
        $codele = $this->getVinculoPcasp($codele);

        if ($codele != "") {
            $this->result = $this->sql_record("select c61_reduz
                                           from conplanoreduz
                                          where c61_codcon = $codele
                                            and c61_anousu=" . db_getsession("DB_anousu") . "
                                            and c61_instit=" . db_getsession("DB_instit"));
            if ($this->numrows > 0) {
                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $sql = "select e91_codtipo from empresto where e91_numemp=$numemp and e91_anousu = " . db_getsession("DB_anousu");
        $result = @db_query($sql);
        $numrows = @pg_numrows($result);
        if ($numrows > 0) {
            $e91_codtipo = @pg_result($result, 0, 0);
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
        }

        $cont = 0;

        //declara array para verifica��o
        $arr_lans = array();
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if (!USE_PCASP || $this->coddoc == 36) {

                if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
                    continue;
                }
            }

            /**
             * Quando for o lan�amento de ordem um, buscamos a conta debitada na liquida��o de RP
             */
            if ($c46_ordem == 1 && USE_PCASP && $this->coddoc != 36) {

                /**
                 * verifica se tem lancamento de controle de liquidacao e busca conta credito do controle
                 */
                if ($this->coddoc == 38 && empenho::possuiLancamentoDeControle($numemp, db_getsession("DB_anousu"), array(212))) {
                    $c47_credito = self::getContaCreditoLiquidacao($numemp, array(212), db_getsession("DB_anousu"));
                } else {

                    $sSqlContaDebito = "  select c69_debito
                                  from conlancamemp
                                       inner join conlancamdoc  on conlancamemp.c75_codlan = conlancamdoc.c71_codlan
                                       inner join conlancamval  on conlancamdoc.c71_codlan = conlancamval.c69_codlan
                                       inner join conlancam     on conlancamval.c69_codlan = conlancam.c70_codlan
                                 where c75_numemp = {$numemp}
                                   and c71_coddoc = 37
                                   and c70_anousu = " . db_getsession("DB_anousu") . "
                                 order by c69_sequen
                                 limit 1";
                    $rsBuscaContaDebito = db_query($sSqlContaDebito);
                    if (pg_num_rows($rsBuscaContaDebito) == 0) {

                        $this->sqlerro  = true;
                        $this->erro_msg = "Conta debitada no estorno da liquida��o de RP n�o encontrada. Contate o suporte.";
                    }
                    $iContaDebito =  db_utils::fieldsMemory($rsBuscaContaDebito, 0)->c69_debito;
                    $c47_credito  = $iContaDebito;
                }
            }

            //------------------------------------------------------------------------
            //verifica��o para naum incluir duas vezes o mesmo seqtranslan
            if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                continue;
            } else {
                $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
            }
            //------------------------------------------------------------------------


            if ($c47_debito == 0 || $c47_debito == '') {
                $c47_debito = $reduz;
                $this->conta_emp = $c47_credito;
            }

            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $this->arr_credito[$cont]    = $c47_credito;
            $this->arr_debito[$cont]     = $c47_debito;
            $this->arr_histori[$cont]    = $c46_codhist;
            $cont++;
        }
    }

    function db_trans_estorna_empenho_resto($codcom = null, $anousu, $numemp)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_tiporesto;
        $this->cl_zera_variaveis();
        $this->coddoc = 32;

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $sql = "select e91_codtipo from empresto where e91_numemp=$numemp and e91_anousu = " . db_getsession("DB_anousu");
        $result = @db_query($sql);
        $numrows = @pg_numrows($result);
        if ($numrows > 0) {
            $e91_codtipo = @pg_result($result, 0, 0);
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
        }

        $cont = 0;
        $arr_lans = array();
        // echo $this->sql;
        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);

            if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
                continue;
            }

            //------------------------------------------------------------------------
            //verifica��o para naum incluir duas vezes o mesmo seqtranslan
            if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                continue;
            } else {
                $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
            }
            //------------------------------------------------------------------------

            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $this->arr_credito[$cont] = $c47_credito;
            $this->arr_debito[$cont] = $c47_debito;
            $this->arr_histori[$cont] = $c46_codhist;
            $cont++;
        }
    }

    function db_trans_estorna_empenho_resto_processado($codcom = null, $anousu, $numemp)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_tiporesto;
        $this->cl_zera_variaveis();
        $this->coddoc = 31;

        $this->result = $this->getRegrasTransacao($this->coddoc, $anousu, db_getsession('DB_anousu'));

        $sql = "select e91_codtipo from empresto where e91_numemp=$numemp and e91_anousu = " . db_getsession("DB_anousu");
        $result = @db_query($sql);
        $numrows = @pg_numrows($result);
        if ($numrows > 0) {
            $e91_codtipo = @pg_result($result, 0, 0);
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte.";
        }

        $cont = 0;
        $arr_lans = array();
        // echo $this->sql;
        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);

            if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
                continue;
            }

            //------------------------------------------------------------------------
            //verifica��o para naum incluir duas vezes o mesmo seqtranslan
            if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                continue;
            } else {
                $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
            }
            //------------------------------------------------------------------------

            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $this->arr_credito[$cont] = $c47_credito;
            $this->arr_debito[$cont] = $c47_debito;
            $this->arr_histori[$cont] = $c46_codhist;
            $cont++;
        }
    }

    // adicionada este metodo 03jul2006
    // fun��o unica para retornar lan�amentos de RP

    function db_trans_rp($documento, $numemp)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_tiporesto, $c46_obrigatorio;
        $this->cl_zera_variaveis();

        $this->coddoc = $documento;

        $arr_obrigatorio = array(); // quarda os lan�amentos que s�o obrigatorios

        $sql = "select e60_anousu from empempenho where e60_numemp=$numemp";
        $result = @db_query($sql);
        $numrows = @pg_numrows($result);
        if ($numrows > 0) {
            $anousu = @pg_result($result, 0, 0);
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Falha ao pesquisar na tabela de empenhos... Contate suporte ! ";
        }

        $sql = "select e91_codtipo from empresto where e91_numemp=$numemp and e91_anousu=" . db_getsession("DB_anousu");
        $result = @db_query($sql);
        $numrows = @pg_numrows($result);
        if ($numrows > 0) {
            $e91_codtipo = @pg_result($result, 0, 0);
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Resto a pagar n�o encotrado na tabela empresto... Contate suporte !";
        }

        // seleciona os lan�amentos
        $sql = " select c46_seqtranslan,c46_codhist,c46_obrigatorio
    from contrans
    inner join contranslan on c46_seqtrans = contrans.c45_seqtrans
    where c45_coddoc = " . $this->coddoc . " and
    c45_anousu = " . db_getsession("DB_anousu") . "  and
    c45_instit = " . db_getsession("DB_instit") . "
    ";
        $result = @db_query($sql);

        // db_criatabela($result);

        $numrows = @pg_numrows($result);
        if ($numrows > 0) {

            $cont = 0;
            for ($i = 0; $i < $numrows; $i++) {
                db_fieldsmemory($result, $i);

                $res_lancam = "select c47_seqtranslr,c47_debito,c47_credito,c47_tiporesto
        from contranslr
        where c47_seqtranslan = $c46_seqtranslan and
        c47_anousu = $anousu   /* anousu = empresto.anousu  */
        ";

                $this->result = $this->sql_record($res_lancam);
                // db_criatabela($this->result);

                if ($this->numrows > 0) {
                    for ($x = 0; $x < $this->numrows; $x++) {
                        db_fieldsmemory($this->result, $x);

                        // se tiver tiporesto configurado ent�o ele deve ser igual ao Tipo Resto do RP
                        if ($c47_tiporesto != '' && $c47_tiporesto != 0 && $e91_codtipo != $c47_tiporesto) {
                            continue;
                        }

                        if ($c46_obrigatorio == 't' || $c46_obrigatorio == 'true') {
                            $arr_obrigatorio[] = $c47_seqtranslr;
                        }
                        $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                        $this->arr_credito[$cont] = $c47_credito;
                        $this->arr_debito[$cont] = $c47_debito;
                        $this->arr_histori[$cont] = $c46_codhist;
                        $cont++;
                    } //end loop

                } else {
                    if ($c46_obrigatorio == 't' || $c46_obrigatorio == 'true') {

                        $this->sqlerro = true;
                        $this->erro_msg = "Lan�amento obrigat�rio, por�m n�o retornou registros ! ";
                        break;
                    }
                } // end if

            } // end loop

            // seleciona quantos lan�amentos s�o obrigatorios
            $sql = " select count(c46_seqtranslan) as quantidade_obrigatoria
      from contrans
      inner join contranslan on c46_seqtrans = contrans.c45_seqtrans
      where c45_coddoc = " . $this->coddoc . " and
      c45_anousu = " . db_getsession("DB_anousu") . "  and
      c45_instit = " . db_getsession("DB_instit") . "	 and
      c46_obrigatorio ='t'
      ";
            $result = @db_query($sql);
            $numrows = pg_numrows($result);
            if ($numrows > 0) {
                $qtd_obrigatoria = pg_result($result, 0, 0);
                if ($qtd_obrigatoria != sizeof($arr_obrigatorio)) {

                    $this->sqlerro = true;
                    $this->erro_msg = "Lan�amento obrigatorio sem registro. Verifique (Contabilidade,documentos,transa��es) ! ";
                }
            }
        } else {
            $this->sqlerro = true;
            $this->erro_msg = "Bloco de lan�amentos n�o localizados ( documento $documento, Empenho $numemp )";
        }
        /*
    echo "<br><br><br><br><br>";
    print_r($this->arr_credito);
    print_r($this->arr_debito);

    echo $this->erro_msg;
    echo "<br> lanc obrigatorio ".sizeof($arr_obrigatorio);
    print_r($arr_obrigatorio);
     */
    } // end method

    function db_trans_arrecada_receita($conta, $codcon, $anousu, $iCodigoDocumento = null, $iCodigoReceitaOrcamentaria = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara, $c46_ordem;

        $this->cl_zera_variaveis();
        $this->coddoc = 100;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, db_getsession("DB_anousu"), db_getsession('DB_anousu'));

        // codcon � o reduzido da receita no conplanoreduz ( c61_reduz )

        $cont = 0;
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if ($c46_ordem == 1 && $c47_credito == 0 && $c47_debito == 0) {

                $this->arr_credito[$cont]    = $codcon; // reduzido da receita
                $this->arr_debito[$cont]     = $conta; // reduzido caixa ou banco
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
                continue;
            }

            if ($c47_compara == 0) {

                $this->arr_credito[$cont]    = $c47_credito;
                // sem comparador ( debito , credito ) retorna lan�amentos encontrados
                $this->arr_debito[$cont]     = $c47_debito;
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            } elseif ($c47_compara == 1) {
                // comparador a debito
                // arrecada��o � sempre a credito, estorno � a debito

            } elseif ($c47_compara == 2) {
                // comparador = credito
                $this->arr_credito[$cont]    = $codcon; // reduzido da receita
                $this->arr_debito[$cont]     = $conta; // reduzido caixa ou banco
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            } elseif ($c47_compara == 4 && $c47_ref == $iCodigoReceitaOrcamentaria) {

                $this->arr_credito[$cont]    = $c47_credito;
                $this->arr_debito[$cont]     = $conta;
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    function db_trans_estorno_receita($conta, $codcon, $anousu, $iCodigoDocumento = null, $iCodigoReceitaOrcamentaria = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara;
        $this->cl_zera_variaveis();
        $this->coddoc = 101;
        if (!empty($iCodigoDocumento)) {
            $this->coddoc = $iCodigoDocumento;
        }

        $this->result = $this->getRegrasTransacao($this->coddoc, db_getsession("DB_anousu"), db_getsession('DB_anousu'));

        $cont = 0;
        for ($i = 0; $i < $this->numrows; $i++) {

            $oEstorno = db_utils::fieldsMemory($this->result, $i);

            if ($oEstorno->c46_ordem == 1 && $oEstorno->c47_credito == 0 && $oEstorno->c47_debito == 0) {

                $this->arr_credito[$cont]    = $conta; // reduzido da receita
                $this->arr_debito[$cont]     = $codcon; // reduzido caixa ou banco
                $this->arr_histori[$cont]    = $oEstorno->c46_codhist;
                $this->arr_seqtranslr[$cont] = $oEstorno->c47_seqtranslr;
                $cont++;
                continue;
            }

            if ($oEstorno->c47_compara == 0) {
                // sem comparador ( debito , credito ) retorna lan�amentos encontrados
                $this->arr_credito[$cont]    = $oEstorno->c47_credito;
                $this->arr_debito[$cont]     = $oEstorno->c47_debito;
                $this->arr_histori[$cont]    = $oEstorno->c46_codhist;
                $this->arr_seqtranslr[$cont] = $oEstorno->c47_seqtranslr;
                $cont++;
            } elseif ($oEstorno->c47_compara == 1) {
                // comparador a debito
                // arrecada��o � sempre a credito, estorno � a debito
                $this->arr_credito[$cont]    = $conta; // reduzido caixa ou banco
                $this->arr_debito[$cont]     = $codcon; // reduzido da receita
                $this->arr_histori[$cont]    = $oEstorno->c46_codhist;
                $this->arr_seqtranslr[$cont] = $oEstorno->c47_seqtranslr;
                $cont++;
            } elseif ($oEstorno->c47_compara == 2) {
                // comparador = credito
                // estorno � sempre a debito
            } elseif ($oEstorno->c47_compara == 4 && $oEstorno->c47_ref == $iCodigoReceitaOrcamentaria) {

                $this->arr_credito[$cont]    = $conta;
                $this->arr_debito[$cont]     = $oEstorno->c47_debito;
                $this->arr_histori[$cont]    = $oEstorno->c46_codhist;
                $this->arr_seqtranslr[$cont] = $oEstorno->c47_seqtranslr;
                $cont++;
            }
        }
    }

    function db_trans_controle_despesa_liquidacao($iCodigoDocumento)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara, $c46_ordem;
        $this->cl_zera_variaveis();
        $this->coddoc = $iCodigoDocumento;

        $this->result = $this->getRegrasTransacao($this->coddoc, db_getsession("DB_anousu"), db_getsession('DB_anousu'), 1);
        $cont = 0;

        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            $this->arr_credito[$cont]    = $c47_credito;
            $this->arr_debito[$cont]     = $c47_debito;
            $this->arr_histori[$cont]    = $c46_codhist;
            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $cont++;
        }
    }

    function db_trans_controle_despesa_liquidacao_material_permanente($iCodigoContaClassificacao, $iCodigoDocumento)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara, $c46_ordem;
        $this->cl_zera_variaveis();
        $this->coddoc = $iCodigoDocumento;

        $this->result = $this->getRegrasTransacao($this->coddoc, db_getsession("DB_anousu"), db_getsession('DB_anousu'));

        $cont = 0;
        for ($i = 0; $i < $this->numrows; $i++) {

            db_fieldsmemory($this->result, $i);

            if ($c46_ordem == 1 && count($this->arr_credito) == 0) {

                switch ($iCodigoDocumento) {

                    case 208:

                        $this->arr_debito[$cont]  = $iCodigoContaClassificacao;
                        $this->arr_credito[$cont] = $c47_credito;
                        break;

                    case 209:

                        $this->arr_debito[$cont]  = $c47_debito;
                        $this->arr_credito[$cont] = $iCodigoContaClassificacao;
                        break;

                    default:
                        throw new Exception("Rotina n�o preparada para executar o documento {$iCodigoDocumento}.");
                }

                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            } else if ($c46_ordem != 1) {

                $this->arr_credito[$cont]    = $c47_credito;
                $this->arr_debito[$cont]     = $c47_debito;
                $this->arr_histori[$cont]    = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    /*  suplemeta��es
  $anousu - normal
  $tipo - tipo de suplementa��(1001,1002,..etc)
  $red  - se � redu��o(true), valor negativo
   */

    function db_trans_suplem($anousu, $tipo, $red = false, $lSuplementacaoEspecial = false, $iInstit = null)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c47_seqtranslr, $doc_suplementacao, $doc_reducao;
        $this->cl_zera_variaveis();
        /**
         * alterar, para pegar os docs da tabela orcsuplemtipo
         */

        /*
     * Alterada a fun��o para passar o par�metro da institui��o
     * Quando n�o for informado nada no par�metro, ser� utilziada a institui��o da sess�o
     */
        if ($iInstit == null) {
            $iInstit = db_getsession("DB_instit");
        }

        $this->arr_credito = array();
        $this->arr_debito = array();
        $this->arr_histori = array();
        $this->arr_seqtranslr = array();
        $sDocSuplementacao = "o48_coddocsup";
        $sDocReducao = "o48_coddocred";
        if ($lSuplementacaoEspecial) {

            $sDocSuplementacao = " case when o48_suplcreditoespecial is null then o48_coddocsup else o48_suplcreditoespecial end ";
            $sDocReducao = " case when o48_redcreditoespecial  is null then o48_coddocred else o48_redcreditoespecial end  ";
        }

        //------ seleciona documentos -------------
        $sql = "select {$sDocSuplementacao} as doc_suplementacao,
    case when {$sDocReducao} > 0 then
       {$sDocReducao}
    else o48_arrecadmaior end as doc_reducao
    from orcsuplemtipo
    where o48_tiposup = $tipo";

        $this->result = $this->sql_record($sql);
        db_fieldsmemory($this->result, 0);
        if ($red == false)
            $this->coddoc = $doc_suplementacao;
        else
            $this->coddoc = $doc_reducao; // reduzido ou arrecada��o a maior ( parte que informa receita )
        // ------------ ------------ --------------
        $this->sql = $this
            ->sql_query_lr(
                null,
                "c47_seqtranslr,c46_codhist,c47_credito,c47_debito,c47_ref",
                'c46_ordem',
                "    c45_coddoc = " . $this->coddoc . "
                                      and c45_anousu=" . db_getsession("DB_anousu")
                    . "
                                      and c45_instit =" . $iInstit
                    . "
                                      and c47_anousu=" . db_getsession("DB_anousu"),
                $iInstit
            );
        $this->result = $this->sql_record($this->sql);
        $cont = 0;
        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);
            if ($c47_ref == 0 || ($c47_ref != 0 && $c47_ref == $codcom)) {
                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }
    /////////////////

    /**
     * recebe o numero do documento e retorna os lan�amentos
     */
    function db_trans_documento($documento, $conta)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c47_compara;
        $this->cl_zera_variaveis();
        $this->coddoc = $documento;

        $this->sql = $this
            ->sql_query_lr(
                null,
                "c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito,c47_compara",
                'c46_ordem',
                "c45_coddoc = " . $this->coddoc . " and c45_anousu=" . db_getsession("DB_anousu")
            );
        $this->result = $this->sql_record($this->sql);

        $cont = 0;
        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);

            if ($c47_credito == 0) {
                $this->arr_credito[$cont] = $conta;
            } else {
                $this->arr_credito[$cont] = $c47_credito;
            }
            if ($c47_debito == 0) {
                $this->arr_debito[$cont] = $conta;
            } else {
                $this->arr_debito[$cont] = $c47_debito;
            }
            $this->arr_histori[$cont] = $c46_codhist;
            $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
            $cont++;
        }
    }

    function db_trans_inscricao_rp($codcom, $codele, $anousu)
    {

        global $c46_codhist, $c47_credito, $c47_debito, $c47_ref, $c46_seqtranslan, $c47_seqtranslr, $c61_reduz, $c47_compara;
        $this->cl_zera_variaveis();
        $this->coddoc = 1007;

        $codele = $this->getVinculoPcasp($codele);
        if ($codele != "") {

            $sSqlCodigoConta = "select c61_reduz ";
            $sSqlCodigoConta .= "  from conplanoreduz ";
            $sSqlCodigoConta .= " where c61_codcon={$codele}";
            $sSqlCodigoConta .= "   and c61_anousu=" . db_getsession("DB_anousu");
            $sSqlCodigoConta .= "   and c61_instit=" . db_getsession("DB_instit");
            $this->result = $this->sql_record($sSqlCodigoConta);
            if ($this->numrows > 0) {
                db_fieldsmemory($this->result, 0);
                $codele = $c61_reduz;
            }
        }
        $this->sql = $this
            ->sql_query_lr(
                null,
                "c47_compara,c47_seqtranslr,c47_ref,c46_seqtranslan,c46_codhist,c47_credito,c47_debito",
                'c46_ordem',
                "    c45_coddoc = {$this->coddoc}
                                      and c45_anousu=" . db_getsession("DB_anousu")
                    . "
                                      and c47_anousu={$anousu}"
            );
        # echo $this->sql;
        $this->result = $this->sql_record($this->sql);
        $cont = 0;
        $arr_lans = array();

        for ($i = 0; $i < $this->numrows; $i++) {
            db_fieldsmemory($this->result, $i);
            if ($c47_compara == 1) {
                $comparador = $c47_debito;
            } else if ($c47_compara == 2) {
                $comparador = $c47_credito;
            } else if ($c47_compara == 3) {
                $comparador = $c47_ref;
            } else {
                $comparador = 0;
            }
            if (($c47_ref == '' || $c47_ref == 0 || ($c47_ref != 0 && ($c47_ref == $codcom || $c47_compara == 3)))
                && ($c47_compara == 0 || $comparador == $codele)
            ) {

                //------------------------------------------------------------------------
                //verifica��o para naum incluir duas vezes o mesmo seqtranslan
                if (array_key_exists($c46_seqtranslan, $arr_lans)) {
                    continue;
                } else {
                    $arr_lans[$c46_seqtranslan] = $c46_seqtranslan;
                }
                //------------------------------------------------------------------------

                $this->arr_credito[$cont] = $c47_credito;
                $this->arr_debito[$cont] = $c47_debito;
                $this->arr_histori[$cont] = $c46_codhist;
                $this->arr_seqtranslr[$cont] = $c47_seqtranslr;
                $cont++;
            }
        }
    }

    /**
     * Retorna a conta de vinculo com o pcasp.
     * � utilizado para a receita/Despesa.
     * @throws exception
     * @return integer conta do pcasp
     *
     */

    public static function getVinculoPcasp($iCodigoConta)
    {

        if (USE_PCASP) {

            $iAnoUsu = db_getsession("DB_anousu");

            $oDaoConplanoConplanoOrcamento = db_utils::getDao("conplanoconplanoorcamento");

            $sWhereTemVinculoPcasp = "    c72_conplanoorcamento = {$iCodigoConta} ";
            $sWhereTemVinculoPcasp .= "and c72_anousu            = {$iAnoUsu}";
            $sSqlTemVinculoPcasp = $oDaoConplanoConplanoOrcamento
                ->sql_query_file(null, "c72_conplano", null, $sWhereTemVinculoPcasp);
            $rsTemVinculoPcasp = $oDaoConplanoConplanoOrcamento->sql_record($sSqlTemVinculoPcasp);
            if ($oDaoConplanoConplanoOrcamento->numrows == 0) {
                $sMsgErro = "Conta de c�digo {$iCodigoConta} n�o possui v�nculo com o PCASP.\n ";
                $sMsgErro .= "Favor realize v�nculo com PCASP e tente novamente. ";
                throw new Exception($sMsgErro);
            }
            $iCodigoContaPcasp = db_utils::fieldsMemory($rsTemVinculoPcasp, 0)->c72_conplano;

            $oDaoConplanoReduz = db_utils::getDao("conplanoreduz");
            $sWhereAnalitica = "    c61_codcon = {$iCodigoContaPcasp} ";
            $sWhereAnalitica .= "and c61_anousu = {$iAnoUsu} ";
            $sWhereAnalitica .= "and c61_instit = " . db_getsession("DB_instit");
            $sSqlTemContaAnalitica = $oDaoConplanoReduz->sql_query(null, null, "c61_reduz", null, $sWhereAnalitica);
            $rsContaAnaliticaPcasp = $oDaoConplanoReduz->sql_record($sSqlTemContaAnalitica);
            if ($oDaoConplanoReduz->numrows == 0) {

                $sMsgErro = "Conta {$iCodigoContaPcasp} nao � uma conta anal�tica no PCASP.\n";
                $sMsgErro .= "Configure a conta corretamente, ou revise o v�nculo da conta {$iCodigoConta} com o PCASP.";
                throw new Exception($sMsgErro);
            }
            $iCodigoConta = $iCodigoContaPcasp;
        }
        return $iCodigoConta;
    }

    /**
     * @param integer $iCodigoDocumento - contrans.c45_coddoc
     * @param integer $iAnoContas - contranslr.c47_anousu
     * @param integer $iAnoTransacao - contrans.c45_anousu
     * @param integer $iOrdemLancamento - contranslan.c46_ordem
     * @return resource
     */
    public function getRegrasTransacao($iCodigoDocumento, $iAnoContas, $iAnoTransacao = null, $iOrdemLancamento = null)
    {

        $sWhere  = " c45_coddoc = $iCodigoDocumento ";
        $sWhere .= " and c47_anousu = $iAnoContas ";

        if (!empty($iAnoTransacao)) {
            $sWhere .= " and c45_anousu = $iAnoTransacao ";
        }

        if (!empty($iOrdemLancamento)) {
            $sWhere .= " and c46_ordem = $iOrdemLancamento ";
        }

        $this->sql = $this->sql_query_lr(null, "*", 'c46_ordem', $sWhere);

        /**
         * Verifica se sql atual � diferente do anterior, se for executa
         */
        if ($this->sql != $this->sSqlRegrasTransacao) {

            $this->rsRegrasTransacao = $this->sql_record($this->sql);
            $this->iRegrasTransacao = is_resource($this->rsRegrasTransacao) ? pg_num_rows($this->rsRegrasTransacao) : 0;
            $this->sSqlRegrasTransacao = $this->sql;
        }

        /**
         * Recupera o cache
         */
        $this->result  = $this->rsRegrasTransacao;
        $this->numrows = $this->iRegrasTransacao;

        return $this->rsRegrasTransacao;
    }

    /**
     * Verifica se o empenho possui lan�amento de controle. Estes lan�amentos s�o executados nos documentos
     * do tipo 200 / 201
     * @param $iSequencialEmpenho
     * @param bool $lEstorno
     * @return bool
     */
    public static function possuiLancamentoDeControle($iSequencialEmpenho, $lEstorno = false)
    {

        $iCodigoTipoDocumento = 200;
        if ($lEstorno) {
            $iCodigoTipoDocumento = 201;
        }
        $oDaoLancamentoEmpenho = new cl_conlancamemp();
        $sSqlBuscaDocumento    = $oDaoLancamentoEmpenho->sql_query_documentos(
            null,
            "conhistdoc.*",
            ' c70_codlan desc limit 1 ',
            "    c75_numemp = {$iSequencialEmpenho}
       and c53_tipo   = {$iCodigoTipoDocumento} "
        );
        $rsBuscaDocumento = $oDaoLancamentoEmpenho->sql_record($sSqlBuscaDocumento);
        if ($oDaoLancamentoEmpenho->numrows > 0) {
            return true;
        }
        return false;
    }
}
//|00|//cl_estrutura
//|10|//pega a picture de um determinado campo do orcparametro e gera um input text com a formatacao da mesma
//|15|//[variavel] = new cl_estrutura;
class cl_estrutura_sistema
{
    // cria variaveis de erro

    var $nomeform = "form1";
    var $reload = false;
    var $size = '50';
    var $mascara = true;
    var $input = false;
    var $db_opcao = 1;
    var $funcao_onchange = null;
    var $autocompletar = false;
    var $botao = false;

    function estrutura_sistema($picture = null)
    {

        $rotuloc = new rotulocampo;
        $clconparametro = new cl_conparametro;
        $rotuloc->label($picture);
        $title = "T" . $picture;
        $label = "L" . $picture;

        global ${$label}, ${$title}, ${$picture}, $mascara;
        if (!class_exists('cl_conparametro')) {
            db_msgbox('Classe conparametro n�o incluida!');
            exit;
        }
        $result = $clconparametro->sql_record($clconparametro->sql_query_file("", "$picture as mascara"));
        if ($clconparametro->numrows > 0) {
            db_fieldsmemory($result, 0);
            $tamanho = strlen($mascara);
        } else {
            db_msgbox('Configura��o de Parametros n�o encontrada ! Contate o suporte !');
            exit;
        }

        if ($this->funcao_onchange != null) {
            if ($this->autocompletar == false && $this->reload == false) {
                $funcao = $this->funcao_onchange;
            } else {
                $funcao = "onChange='js_mascara02_$picture(this.value);" . $this->funcao_onchange . ";'";
            }
        } else {
            $funcao = "onChange=\"js_mascara02_$picture(this.value);\"";
        }
        if ($this->mascara == true && $this->input == false) {
?>
            <tr>
                <td nowrap title="M�scara do campo <?= @$picture ?>">
                    <b>M�scara:</b>
                </td>
                <td>

                    <input name="mascara_<?= $picture ?>" readonly disabled size='<?= $this->size ?>' type="text" value="<?= $mascara ?>">

                </td>
            </tr>
        <?

        }
        if ($this->input == false) {
        ?>
            <tr>
                <td nowrap title="<?= @${$title} ?>">
                    <?= @${$label} ?>
                </td>
                <td>
                <?

            }
                ?>

                <input title="<?= @${$title} ?>" name="<?= $picture ?>" maxlength='<?= $tamanho ?>' size='<?= $this->size ?>' type="text" value="<?= @${$picture} ?>" onKeyPress="return js_mascara01_<?= $picture ?>(event,this.value);" <?= $funcao ?> <?= ($this->db_opcao == 22 || $this->db_opcao == 33 || $this->db_opcao == 3 ? "readonly style=\"background-color:#DEB887\" "
                                                                                                                                                                                                                                                                : "") ?>>
                <?

                if ($this->botao == true) {
                ?>
                    <input name='verifica' type="button" value='Verificar' onclick="js_mascara02_<?= $picture ?>(document.<?= $this
                                                                                                                                ->nomeform ?>.<?= $picture ?>.value);" <?= ($this
                                                                                                                                                                            ->db_opcao
                                                                                                                                                                            == 22
                                                                                                                                                                            || $this
                                                                                                                                                                            ->db_opcao
                                                                                                                                                                            == 33
                                                                                                                                                                            || $this
                                                                                                                                                                            ->db_opcao
                                                                                                                                                                            == 3 ? "disabled "
                                                                                                                                                                            : "") ?>>
                <?

                }
                ?>
                <?

                if ($this->input == false) {
                ?>
                </td>
            </tr>
        <?

                }
        ?>
        <script>
            function js_mascara01_<?= $picture ?>(evt, obj) {
                var evt = (evt) ? evt : (window.event) ? window.event : "";
                if (evt.charCode > 47 && evt.charCode < 58) { //8:backspace|46:delete|190:.
                    var str = '<?= $mascara ?>';
                    var tam = obj.length;
                    var dig = str.substr(tam, 1);
                    if (dig == ".") {
                        document.<?= $this->nomeform ?>.<?= $picture ?>.value = obj + ".";
                    }
                    return true;
                } else if (evt.charCode == '0') {
                    return true;
                } else {
                    return false;
                }
            }

            function js_mascara02_<?= $picture ?>(obj) {

                var str = '<?= $mascara ?>';
                var obj = document.<?= $this->nomeform ?>.<?= $picture ?>.value;
                while (obj.search(/\./) != '-1') {
                    obj = obj.replace(/\./, '');
                }
                <?

                if ($this->autocompletar == true) {
                ?>
                    if (obj != '') {
                        var tam = <?= strlen(str_replace(".", "", $mascara)) ?>;
                        for (var i = obj.length; i < tam; i++) {
                            obj = obj + "0";
                        }
                    }
                <?

                }
                ?>
                //analise da estrutura passada
                var nada = '';
                var matriz = str.split(nada);
                var tam = matriz.length;
                var arr = new Array();
                var cont = 0;
                for (i = 0; i < tam; i++) {
                    if (matriz[i] == '.') {
                        arr[cont] = i;
                        cont++;
                    }
                }
                //fim
                for (var i = 0; i < arr.length; i++) {
                    var pos = arr[i];
                    var strpos = obj.substr(pos, 1);
                    if (strpos != '' && strpos != '.') {
                        ini = obj.slice(0, pos);
                        fim = obj.slice(pos);
                        obj = ini + "." + fim;
                    }
                }
                document.<?= $this->nomeform ?>.<?= $picture ?>.value = obj;
                <?

                if ($this->reload == true) {
                ?>
                    obj = document.createElement('input');
                    obj.setAttribute('name', 'atualizar');
                    obj.setAttribute('type', 'hidden');
                    obj.setAttribute('value', "atualizar");
                    document.<?= $this->nomeform ?>.appendChild(obj);
                    document.<?= $this->nomeform ?>.submit();
                <?

                }
                ?>
            }

            function js_mascara03_<?= $picture ?>(obj) {
                obj = document.<?= $this->nomeform ?>.<?= $picture ?>.value;
                while (obj.search(/\./) != '-1') {
                    obj = obj.replace(/\./, '');
                }
                <?

                if ($this->autocompletar == true) {
                ?>
                    tam = <?= strlen(str_replace(".", "", $mascara)) ?>;
                    for (i = obj.length; i < tam; i++) {
                        obj = obj + "0";
                    }
                <?

                }
                ?>
                //analise da estrutura passada
                var str = '<?= $mascara ?>';
                var nada = '';
                var matriz = str.split(nada);
                var tam = matriz.length;
                var arr = new Array();
                var cont = 0;
                for (var i = 0; i < tam; i++) {
                    if (matriz[i] == '.') {
                        arr[cont] = i;
                        cont++;
                    }
                }
                //fim
                for (var i = 0; i < arr.length; i++) {
                    pos = arr[i];
                    strpos = obj.substr(pos, 1);
                    if (strpos != '' && strpos != '.') {
                        ini = obj.slice(0, pos);
                        fim = obj.slice(pos);
                        obj = ini + "." + fim;
                    }
                }
                document.<?= $this->nomeform ?>.<?= $picture ?>.value = obj;
            }
        </script>
<?

        $this->nomeform = "form1";
        $this->reload = false;
        $this->size = '50';
        $this->mascara = true;
        $this->input = false;
        $this->db_opcao = 1;
        $this->funcao_onchange = null;
        $this->autocompletar = false;
        $this->botao = false;
    }
}

function db_le_mae_sistema($codigo, $nivel = false)
{

    $retorno = "";
    if (substr($codigo, 11, 2) != '00') {
        if ($nivel == true) {
            $retorno = 9;
        } else {
            $retorno = substr($codigo, 0, 11) . '00';
        }
    }
    if ($retorno == "" && substr($codigo, 9, 4) != '0000') {
        if ($nivel == true) {
            $retorno = 8;
        } else {
            $retorno = substr($codigo, 0, 9) . '0000';
        }
    }
    if ($retorno == "" && substr($codigo, 7, 6) != '000000') {
        if ($nivel == true) {
            $retorno = 7;
        } else {
            $retorno = substr($codigo, 0, 7) . '000000';
        }
    }
    if ($retorno == "" && substr($codigo, 5, 8) != '00000000') {
        if ($nivel == true) {
            $retorno = 6;
        } else {
            $retorno = substr($codigo, 0, 5) . '00000000';
        }
    }
    if ($retorno == "" && substr($codigo, 4, 9) != '000000000') {
        if ($nivel == true) {
            $retorno = 5;
        } else {
            $retorno = substr($codigo, 0, 4) . '000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 3, 10) != '0000000000') {
        if ($nivel == true) {
            $retorno = 4;
        } else {
            $retorno = substr($codigo, 0, 3) . '0000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 2, 11) != '00000000000') {
        if ($nivel == true) {
            $retorno = 3;
        } else {
            $retorno = substr($codigo, 0, 2) . '00000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 1, 12) != '000000000000') {
        if ($nivel == true) {
            $retorno = 2;
        } else {
            $retorno = substr($codigo, 0, 1) . '000000000000';
        }
    }
    if ($retorno == "") {
        if ($nivel == true) {
            $retorno = 1;
        } else {
            $retorno = $codigo;
        }
    }
    return $retorno;
}
//codigo seria o estrutural fornecido
//$nivel seria qual o nivel do estrutural que � para retornar...
//$full= true se desejar que seja retornado o nivel desejado e o resto com zero.. false ele retornara s� ate o nivel desejado...

function db_le_corta_conplano($codigo, $nivel, $full = false)
{

    $retorno = "";
    if ($nivel == 9) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 13) . '00';
        } else {
            $retorno = substr($codigo, 0, 13);
        }
    }
    if ($nivel == 8) {
        if ($full == 8) {
            $retorno = substr($codigo, 0, 11) . '0000';
        } else {
            $retorno = substr($codigo, 0, 11);
        }
    }
    if ($nivel == 7) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 9) . '000000';
        } else {
            $retorno = substr($codigo, 0, 9);
        }
    }
    if ($nivel == 6) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 7) . '00000000';
        } else {
            $retorno = substr($codigo, 0, 7);
        }
    }
    if ($nivel == 5) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 5) . '0000000000';
        } else {
            $retorno = substr($codigo, 0, 5);
        }
    }
    if ($nivel == 4) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 4) . '00000000000';
        } else {
            $retorno = substr($codigo, 0, 4);
        }
    }
    if ($nivel == 3) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 3) . '000000000000';
        } else {
            $retorno = substr($codigo, 0, 3);
        }
    }
    if ($nivel == 2) {
        if ($full == true) {
            $retorno = substr($codigo, 0, 2) . '0000000000000';
        } else {
            $retorno = substr($codigo, 0, 2);
        }
    }
    if ($nivel == "1") {
        if ($full == true) {
            $retorno = substr($codigo, 0, 1) . '0000000000000';
        } else {
            $retorno = substr($codigo, 0, 1);
        }
    }
    return $retorno;
}

function db_le_mae_conplano($codigo, $nivel = false)
{

    $retorno = "";

    if ($retorno == "" && substr($codigo, 13, 2) != '00') {
        if ($nivel == true) {
            $retorno = 10;
        } else {
            $retorno = substr($codigo, 0, 13) . '00';
        }
    }
    if ($retorno == "" && substr($codigo, 11, 2) != '00') {
        if ($nivel == true) {
            $retorno = 9;
        } else {
            $retorno = substr($codigo, 0, 11) . '0000';
        }
    }
    if ($retorno == "" && substr($codigo, 9, 6) != '000000') {
        if ($nivel == true) {
            $retorno = 8;
        } else {
            $retorno = substr($codigo, 0, 9) . '000000';
        }
    }
    if ($retorno == "" && substr($codigo, 7, 8) != '00000000') {
        if ($nivel == true) {
            $retorno = 7;
        } else {
            $retorno = substr($codigo, 0, 7) . '00000000';
        }
    }
    if ($retorno == "" && substr($codigo, 5, 10) != '0000000000') {
        if ($nivel == true) {
            $retorno = 6;
        } else {
            $retorno = substr($codigo, 0, 5) . '0000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 4, 11) != '00000000000') {
        if ($nivel == true) {
            $retorno = 5;
        } else {
            $retorno = substr($codigo, 0, 4) . '00000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 3, 12) != '000000000000') {
        if ($nivel == true) {
            $retorno = 4;
        } else {
            $retorno = substr($codigo, 0, 3) . '000000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 2, 13) != '0000000000000') {
        if ($nivel == true) {
            $retorno = 3;
        } else {
            $retorno = substr($codigo, 0, 2) . '0000000000000';
        }
    }
    if ($retorno == "" && substr($codigo, 1, 14) != '00000000000000') {
        if ($nivel == true) {
            $retorno = 2;
        } else {
            $retorno = substr($codigo, 0, 1) . '00000000000000';
        }
    }
    if ($retorno == "") {
        if ($nivel == true) {
            $retorno = 1;
        } else {
            $retorno = $codigo;
        }
    }
    return $retorno;
}

function db_planosissaldo($anousu, $dataini, $datafim, $retsql = false, $where = '')
{

    if ($anousu == null)
        $anousu = db_getsession("DB_anousu");

    if ($dataini == null)
        $dataini = date('Y-m-d', db_getsession('DB_datausu'));

    if ($datafim == null)
        $datafim = date('Y-m-d', db_getsession('DB_datausu'));

    if ($where != '') {
        $condicao = " and " . $where;
    } else {
        $condicao = "";
    }

    $sql = "select   estrut_mae,
  estrut,
  c61_reduz,
  c60_descr,
  c60_finali,
  substr(fc_planosaldo,3,13)::float8 as saldo_anterior,
  substr(fc_planosaldo,16,13)::float8 as saldo_anterior_debito,
  substr(fc_planosaldo,29,13)::float8 as saldo_anterior_credito,
  substr(fc_planosaldo,42,13)::float8 as saldo_final,
  substr(fc_planosaldo,55,1)::varchar(1) as sinal_anterior,
  substr(fc_planosaldo,56,1)::varchar(1) as sinal_final
  from
  (select c.c64_estrut as estrut_mae,
  p.c60_estrut as estrut,
  c61_reduz,
  p.c60_descr,
  p.c60_finali,
  fc_planosaldo(" . db_getsession('DB_anousu')
        . ",c61_reduz,'$dataini','$datafim')
  from conplanoexe e
  inner join conplanoreduz r on r.c61_reduz = c62_reduz and r.c61_anousu=e.c62_anousu
  inner join conplanoref f on f.c65_codcon = c61_codcon
  inner join conplano p on r.c61_codcon = p.c60_codcon and p.c60_anousu=r.c61_anousu
  inner join conplanosis c on c.c64_codpla = f.c65_codpla
  where c62_anousu = $anousu $condicao) as x
  ";

    //db_criatabela(db_query($sql));exit;

    db_query(
        "create temporary table work_planosis(estrut_mae varchar(15),
  estrut varchar(15),
  c61_reduz integer,
  c60_descr varchar(60),
  c60_finali varchar(60),
  saldo_anterior float8,
  saldo_anterior_debito float8,
  saldo_anterior_credito float8,
  saldo_final float8,
  sinal_anterior varchar(1),
  sinal_final varchar(1))"
    );

    db_query("create index work_planosis_estrut on work_planosis(estrut)");

    $result = db_query($sql);

    //  db_criatabela($result);exit;
    $tot_anterior = 0;
    $tot_anterior_debito = 0;
    $tot_anterior_credito = 0;
    $tot_saldo_final = 0;
    global $estrut_mae, $estrut, $c61_reduz, $c60_descr, $c60_finali, $saldo_anterior, $saldo_anterior_debito, $saldo_anterior_credito, $saldo_final, $result_estrut, $sinal_anterior, $sinal_final, $c64_descr;
    $nivel = 0;
    for ($i = 0; $i < pg_numrows($result); $i++) {
        //  for($i = 0;$i < 4;$i++){
        db_fieldsmemory($result, $i);
        if ($sinal_anterior == "C")
            $saldo_anterior *= -1;
        if ($sinal_final == "C")
            $saldo_final *= -1;
        $tot_anterior = $saldo_anterior;
        $tot_anterior_debito = $saldo_anterior_debito;
        $tot_anterior_credito = $saldo_anterior_credito;
        $tot_saldo_final = $saldo_final;
        db_query(
            "insert into work_planosis values('$estrut_mae',
      '$estrut',
      $c61_reduz,
      '" . pg_scape_string($c60_descr) . "',
      '" . pg_scape_string($c60_finali) . "',
      $saldo_anterior,
      $saldo_anterior_debito,
      $saldo_anterior_credito,
      $saldo_final,
      '$sinal_anterior',
      '$sinal_final')"
        );
        $estrutural = $estrut_mae;
        $nivel = 10;
        for ($ii = 1; $ii < 11; $ii++) {
            if ($ii > 1) {
                $estrutural = db_le_mae_sistema($estrutural);
                $nivel = db_le_mae_sistema($estrutural, true);
            }
            $result_estrut = db_query("select saldo_anterior from work_planosis where estrut = '$estrutural'");
            //db_criatabela($result_estrut);
            if (@pg_numrows($result_estrut) != true) {
                $res = db_query("select c64_descr from conplanosis where c64_estrut = '$estrutural'");
                db_fieldsmemory($res, 0);

                $result_1 = db_query(
                    "insert into work_planosis values('$estrutural',
          '$estrutural',
          0,
          '$c64_descr',
          '$c64_descr',
          $saldo_anterior,
          $saldo_anterior_debito,
          $saldo_anterior_credito,
          $saldo_final,
          '$sinal_anterior',
          '$sinal_final')"
                );
            } else {

                db_query(
                    "update work_planosis set saldo_anterior = saldo_anterior + $tot_anterior ,
          saldo_anterior_debito = saldo_anterior_debito + $tot_anterior_debito ,
          saldo_anterior_credito = saldo_anterior_credito + $tot_anterior_credito ,
          saldo_final = saldo_final + $tot_saldo_final
          where estrut = '$estrutural' "
                );
            }
            if ($nivel == 1)
                break;
        }
    }
    //db_criatabela(db_query("select * from work_planosis"));exit;
    $sql = "select case when c61_reduz = 0 then estrut_mae else estrut end as estrutural,
    c61_reduz,
    c60_descr,
    c60_finali,
    abs(saldo_anterior) as saldo_anterior,
    abs(saldo_anterior_debito) as saldo_anterior_debito,
    abs(saldo_anterior_credito) as saldo_anterior_credito,
    abs(saldo_final) as saldo_final,
    case when saldo_anterior < 0 then 'C'
    when saldo_anterior > 0 then 'D'
    else ' ' end as  sinal_anterior,
    case when saldo_final < 0 then 'C'
    when saldo_final > 0 then 'D'
    else ' ' end as  sinal_final
    from work_planosis
    order by estrut_mae,estrut";

    if ($retsql == false) {
        return $result_final = db_query($sql);
        //     db_criatabela(db_query($sql));
    } else {
        return $sql;
    }
}

/**
 * @ atualiza��o dessa fun��o :14/04
 * @ A versao antiga consta abaixo com o nome "db_planocontassaldo_old()";
 * @ deixar false a op��o com encerramento
 */
function db_planocontassaldo_matriz(
    $anousu,
    $dataini,
    $datafim,
    $retsql = false,
    $where = '',
    $estrut_inicial = '',
    $acumula_reduzido = 'true',
    $encerramento = 'false',
    $join = '',
    $aOrcParametro = array()
) {

    //echo "<pre>";
    //print_r($aOrcParametro);
    //echo "</pre>";

    if ($anousu == null)
        $anousu = db_getsession("DB_anousu");
    if ($dataini == null)
        $dataini = date('Y-m-d', db_getsession('DB_datausu'));
    if ($datafim == null)
        $datafim = date('Y-m-d', db_getsession('DB_datausu'));
    if ($where != '') {
        $condicao = " and " . $where;
    } else {
        $condicao = "";
    }
    $pesq_estrut = "";
    if ($estrut_inicial != "") {
        // oberve a concatena��o da vari�vel
        $condicao .= "  and p.c60_estrut like '$estrut_inicial%' ";
    }

    if ($encerramento == '')
        $encerramento = false;

    $sql = "
    select
    estrut_mae,
    estrut,
    c61_reduz,
    c61_codcon,
    c61_codigo,
    c60_descr,
    c60_finali,
    c61_instit,
    round(substr(fc_planosaldonovo,3,14)::float8,2)::float8 as saldo_anterior,
    round(substr(fc_planosaldonovo,17,14)::float8,2)::float8 as saldo_anterior_debito,
    round(substr(fc_planosaldonovo,31,14)::float8,2)::float8 as saldo_anterior_credito,
    round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as saldo_final,
    substr(fc_planosaldonovo,59,1)::varchar(1) as sinal_anterior,
    substr(fc_planosaldonovo,60,1)::varchar(1) as sinal_final,
    c60_identificadorfinanceiro,
    c60_consistemaconta
    from
    --(select case when substr(p.c60_estrut,1,2) = '33' then '511100000000000'
    --             when substr(p.c60_estrut,1,2) = '34' then '511200000000000'
    --             when substr(p.c60_estrut,1,2) = '41' then '611100000000000'
    --             when substr(p.c60_estrut,1,2) = '49' then '611100000000000'
    --			   when substr(p.c60_estrut,1,2) = '42' then '611200000000000'
    --		      else p.c60_estrut end as estrut_mae,
    --	              case when substr(p.c60_estrut,1,2) = '33' then '511100000000000'
    --	                   when substr(p.c60_estrut,1,2) = '34' then '511200000000000'
    --	                   when substr(p.c60_estrut,1,2) = '41' then '611100000000000'
    --	                   when substr(p.c60_estrut,1,2) = '49' then '611100000000000'
    --		 	   when substr(p.c60_estrut,1,2) = '42' then '611200000000000'
    --		      else p.c60_estrut end as estrut,

    (select p.c60_estrut as estrut_mae,
    p.c60_estrut as estrut,
    c61_reduz,
    c61_codcon,
    c61_codigo,
    p.c60_descr,
    p.c60_finali,
    r.c61_instit,
    /* fc_planosaldonovo($anousu,c61_reduz,'$dataini','$datafim') */
    fc_planosaldonovo($anousu,c61_reduz,'$dataini','$datafim',$encerramento),
    p.c60_identificadorfinanceiro,
    c60_consistemaconta
    from conplanoexe e
    inner join conplanoreduz r on   r.c61_anousu = c62_anousu  and  r.c61_reduz = c62_reduz
    inner join conplano p on r.c61_codcon = c60_codcon and r.c61_anousu = c60_anousu
    left outer join consistema on c60_codsis = c52_codsis
		$join
    $pesq_estrut
    where c62_anousu = $anousu $condicao) as x
    ";
    #echo "<pre>$sql</pre>";
    // db_criatabela(db_query($sql));exit;

    db_query(
        "create temporary table work_pl (
    estrut_mae varchar(15),
    estrut varchar(15),
    c61_reduz integer,
    c61_codcon integer,
    c61_codigo integer,
    c60_descr varchar(50),
    c60_finali text,
    c61_instit integer,
    saldo_anterior float8,
    saldo_anterior_debito float8,
    saldo_anterior_credito float8,
    saldo_final float8,
    sinal_anterior varchar(1),
    sinal_final varchar(1),
    c60_identificadorfinanceiro character(1),
    c60_consistemaconta integer)"
    );
    //   db_query("create temporary table work_plano as $sql");
    db_query("create index work_pl_estrut on work_pl(estrut)");
    db_query("create index work_pl_estrutmae on work_pl(estrut_mae)");

    $result = db_query($sql);
    //db_criatabela($result);exit;
    $tot_anterior = 0;
    $tot_anterior_debito = 0;
    $tot_anterior_credito = 0;
    $tot_saldo_final = 0;
    global $seq;
    global $estrut_mae;
    global $estrut;
    global $c61_reduz;
    global $c61_codcon;
    global $c61_codigo;
    global $c60_codcon;
    global $c60_descr;
    global $c60_finali;
    global $c61_instit;
    global $saldo_anterior;
    global $saldo_anterior_debito;
    global $saldo_anterior_credito;
    global $saldo_final;
    global $result_estrut;
    global $sinal_anterior;
    global $sinal_final;
    global $c60_identificadorfinanceiro;
    global $c60_consistemaconta;
    global $sis;

    $work_planomae = array();
    $work_planoestrut = array();
    $work_plano = array();
    $seq = 0;

    for ($i = 0; $i < pg_numrows($result); $i++) {
        //  for($i = 0;$i < 20;$i++){
        db_fieldsmemory($result, $i);
        if ($sinal_anterior == "C")
            $saldo_anterior *= -1;
        if ($sinal_final == "C")
            $saldo_final *= -1;
        $tot_anterior         = dbround_php_52($saldo_anterior, 2);
        $tot_anterior_debito  = dbround_php_52($saldo_anterior_debito, 2);
        $tot_anterior_credito = dbround_php_52($saldo_anterior_credito, 2);
        $tot_saldo_final      = dbround_php_52($saldo_final, 2);

        if ($acumula_reduzido == true) {
            $key = array_search("$estrut_mae", $work_planomae);
        } else {
            $key = false;
        }
        if ($key === false) { // n�o achou
            $work_planomae[$seq] = $estrut_mae;
            $work_planoestrut[$seq] = $estrut;
            $work_plano[$seq] = array(
                0 => "$c61_reduz", 1 => "$c61_codcon", 2 => "$c61_codigo", 3 => "$c60_descr",
                4 => "$c60_finali", 5 => "$c61_instit", 6 => "$saldo_anterior", 7 => "$saldo_anterior_debito",
                8 => "$saldo_anterior_credito", 9 => "$saldo_final", 10 => "$sinal_anterior", 11 => "$sinal_final",
                12 => "$c60_identificadorfinanceiro", 13 => "$c60_consistemaconta"
            );
            $seq = $seq + 1;
        } else {
            $work_plano[$key][6] = dbround_php_52($work_plano[$key][6], 2) + dbround_php_52($tot_anterior, 2);
            $work_plano[$key][7] = dbround_php_52($work_plano[$key][7], 2) + dbround_php_52($tot_anterior_debito, 2);
            $work_plano[$key][8] = dbround_php_52($work_plano[$key][8], 2) + dbround_php_52($tot_anterior_credito, 2);
            $work_plano[$key][9] = dbround_php_52($work_plano[$key][9], 2) + dbround_php_52($tot_saldo_final, 2);
        }
        $estrutural = $estrut;

        for ($ii = 1; $ii < 10; $ii++) {
            $estrutural = db_le_mae_conplano($estrutural);
            $nivel = db_le_mae_conplano($estrutural, true);

            $key = array_search("$estrutural", $work_planomae);
            if ($key === false) { // n�o achou
                // busca no banco e inclui
                $res = db_query(
                    "select c60_descr,c60_finali,c60_codcon, c60_identificadorfinanceiro
            from conplano
            where c60_anousu=" . $anousu . " and c60_estrut = '$estrutural'"
                );
                if ($res == false || pg_numrows($res) == 0) {

                    $sMensagemErro = "Est� faltando cadastrar esse estrutural na contabilidade. N�vel : {$nivel}  Estrutural : {$estrutural} - ano: {$anousu}";

                    if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
                        throw new Exception($sMensagemErro);
                    }

                    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
                    exit;
                }
                db_fieldsmemory($res, 0);

                $work_planomae[$seq] = $estrutural;
                $work_planoestrut[$seq] = '';
                /// Validar Parametros do Orcamento para Acumular as Sinteticas (Estrutura e Instituicao)
                $work_plano[$seq] = (array(
                    0 => 0, 1 => 0, 2 => $c60_codcon, 3 => $c60_descr, 4 => $c60_finali, 5 => 0,
                    6 => $saldo_anterior, 7 => $saldo_anterior_debito, 8 => $saldo_anterior_credito, 9 => $saldo_final,
                    10 => $sinal_anterior, 11 => $sinal_final, 12 => $c60_identificadorfinanceiro, 13 => $c60_consistemaconta
                ));
                if (count($aOrcParametro) > 0) { // Se foram passados parametros...
                    if (!in_array(array($estrutural, $c61_instit), $aOrcParametro)) {
                        $work_plano[$seq] = (array(
                            0 => 0, 1 => 0, 2 => $c60_codcon, 3 => $c60_descr, 4 => $c60_finali, 5 => 0,
                            6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => '', 11 => '', 12 => '', 13 => 0
                        ));
                    }
                }

                $seq++;
            } else {

                /// Validar Parametros do Orcamento para Acumular as Sinteticas (Estrutura e Instituicao)
                if (count($aOrcParametro) > 0) { // Se foram passados parametros...
                    if (!in_array(array($estrutural, $c61_instit), $aOrcParametro)) {

                        continue;
                    }
                    //echo "<pre>";
                    //print_r(array($estrutural, $c61_instit));
                    //echo "</pre>";

                    $work_plano[$key][6] = dbround_php_52($work_plano[$key][6], 2) + dbround_php_52($tot_anterior, 2);
                    $work_plano[$key][7] = dbround_php_52($work_plano[$key][7], 2) + dbround_php_52($tot_anterior_debito, 2);
                    $work_plano[$key][8] = dbround_php_52($work_plano[$key][8], 2) + dbround_php_52($tot_anterior_credito, 2);
                    $work_plano[$key][9] = dbround_php_52($work_plano[$key][9], 2) + dbround_php_52($tot_saldo_final, 2);
                } else {
                    $work_plano[$key][6] = dbround_php_52($work_plano[$key][6], 2) + dbround_php_52($tot_anterior, 2);
                    $work_plano[$key][7] = dbround_php_52($work_plano[$key][7], 2) + dbround_php_52($tot_anterior_debito, 2);
                    $work_plano[$key][8] = dbround_php_52($work_plano[$key][8], 2) + dbround_php_52($tot_anterior_credito, 2);
                    $work_plano[$key][9] = dbround_php_52($work_plano[$key][9], 2) + dbround_php_52($tot_saldo_final, 2);
                }
            }
            if ($nivel == 1)
                break;
        }
    }
    for ($i = 0; $i < sizeof($work_planomae); $i++) {
        $mae = $work_planomae[$i];
        $estrut = $work_planoestrut[$i];
        $c61_reduz = $work_plano[$i][0];
        $c61_codcon = $work_plano[$i][1];
        $c61_codigo = $work_plano[$i][2];
        $c60_descr = $work_plano[$i][3];
        $c60_finali = $work_plano[$i][4];
        $c61_instit = $work_plano[$i][5];
        $saldo_anterior = $work_plano[$i][6];
        $saldo_anterior_debito = $work_plano[$i][7];
        $saldo_anterior_credito = $work_plano[$i][8];
        $saldo_final = $work_plano[$i][9];
        $sinal_anterior = $work_plano[$i][10];
        $sinal_final = $work_plano[$i][11];
        $c60_identificadorfinanceiro = $work_plano[$i][12];
        $c60_consistemaconta = $work_plano[$i][13];

        $sql = "insert into work_pl
        values ('$mae',
        '$estrut',
        $c61_reduz,
        $c61_codcon,
        $c61_codigo,
        '" . pg_escape_string($c60_descr) . "',
        '" . pg_escape_string($c60_finali) . "',
        $c61_instit,
        $saldo_anterior,
        $saldo_anterior_debito,
        $saldo_anterior_credito,
        $saldo_final,
        '$sinal_anterior',
        '$sinal_final',
        '$c60_identificadorfinanceiro',
        $c60_consistemaconta)";
        db_query($sql);
    }

    $sql = "select
      case when c61_reduz = 0 then
      estrut_mae
      else
      estrut
      end as estrutural,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c60_descr,
      c60_finali,
      c61_instit,
      abs(saldo_anterior) as saldo_anterior,
      abs(saldo_anterior_debito) as saldo_anterior_debito,
      abs(saldo_anterior_credito) as saldo_anterior_credito,
      abs(saldo_final) as saldo_final,
      case when saldo_anterior < 0 then  'C'
      when saldo_anterior > 0 then 'D'
      else ' '
      end as  sinal_anterior,
      case when saldo_final < 0 then 'C'
      when saldo_final > 0 then 'D'
      else ' '
      end as  sinal_final,
      case when c60_identificadorfinanceiro = 'N' then ''
           else c60_identificadorfinanceiro
      end as isf,
      case when c60_consistemaconta = 0 then ''
           when c60_consistemaconta = 1 then 'O'
           when c60_consistemaconta = 2 then 'P'
           else 'C'
      end as sis
      from work_pl
      order by estrut_mae,estrut";

    if ($retsql == false) {
        $result_final = db_query($sql);
        //db_criatabela($result_final); exit;
        return $result_final;
    } else {
        return $sql;
    }
}
/*
     * gera balancete com as contas de receita e despesa
     * como no modelos do plano de contas
     */

function db_planocontassaldo_desp_rec(
    $anousu,
    $dataini,
    $datafim,
    $retsql = false,
    $where = '',
    $estrut_inicial = '',
    $encerramento = 'false'
) {
    // anousu
    // where[] :   condi��es adicionais de pesquisa
    // encerramento[false/true] :  considera documentos de encerramento de exercicio
    // retsql  [false/true] : retorna somente sql
    // estrut_inicia []  :  pesquisa por estrutural
    //

    if ($anousu == null)
        $anousu = db_getsession("DB_anousu");
    if ($dataini == null)
        $dataini = date('Y-m-d', db_getsession('DB_datausu'));
    if ($datafim == null)
        $datafim = date('Y-m-d', db_getsession('DB_datausu'));
    if ($where != '') {
        $condicao = " and " . $where;
    } else {
        $condicao = "";
    }

    $pesq_estrut = "";
    if ($estrut_inicial != "") {
        $pesq_estrut = "  and p.c60_estrut like '$estrut_inicial%' ";
    }
    $sql = "
      select   estrut_mae,
      estrut,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c60_descr,
      c60_finali,
      c61_instit,
      round(substr(fc_planosaldonovo,3,14)::float8,2)::float8 as saldo_anterior,
      round(substr(fc_planosaldonovo,17,14)::float8,2)::float8 as saldo_anterior_debito,
      round(substr(fc_planosaldonovo,31,14)::float8,2)::float8 as saldo_anterior_credito,
      round(substr(fc_planosaldonovo,45,14)::float8,2)::float8 as saldo_final,
      substr(fc_planosaldonovo,59,1)::varchar(1) as sinal_anterior,
      substr(fc_planosaldonovo,60,1)::varchar(1) as sinal_final
      from
      (select p.c60_estrut as estrut_mae,
      p.c60_estrut as estrut,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      p.c60_descr,
      p.c60_finali,
      r.c61_instit,
      fc_planosaldonovo(" . db_getsession('DB_anousu')
        . ",c61_reduz,'$dataini','$datafim',$encerramento)
      from conplanoexe e
      inner join conplanoreduz r on r.c61_reduz = e.c62_reduz and r.c61_anousu=e.c62_anousu
      inner join conplano p on r.c61_codcon = p.c60_codcon and r.c61_anousu=p.c60_anousu
      $pesq_estrut
      where c62_anousu = $anousu $condicao";

    if (USE_PCASP) {
        $sql .= " and ( substr(p.c60_estrut,1,1)='3' or substr(p.c60_estrut,1,1)='4')) as x ";
    } else {
        $sql .= " and
                  ( substr(p.c60_estrut,1,1)='3' or substr(p.c60_estrut,1,1)='4'
                or fc_conplano_grupo(" . db_getsession("DB_anousu")
            . ", substr(p.c60_estrut,1,1)||'%', 9004) is true
                or fc_conplano_grupo(" . db_getsession("DB_anousu")
            . ", substr(p.c60_estrut,1,1)||'%', 9000) is true
                  )
                ) as x ";
    }
    db_query(
        "create temporary table work_plano (
      estrut_mae varchar(15),
      estrut varchar(15),
      c61_reduz integer,
      c61_codcon integer,
      c61_codigo integer,
      c60_descr varchar(50),
      c60_finali text,
      c61_instit integer,
      saldo_anterior float8,
      saldo_anterior_debito float8,
      saldo_anterior_credito float8,
      saldo_final float8,
      sinal_anterior varchar(1),
      sinal_final varchar(1)) "
    );
    db_query("create index work_plano_estrut on work_plano(estrut)");
    db_query("create index work_plano_estrutmae on work_plano(estrut_mae)");
    $result = db_query($sql);
    $tot_anterior = 0;
    $tot_anterior_debito = 0;
    $tot_anterior_credito = 0;
    $tot_saldo_final = 0;
    global $seq;
    global $estrut_mae;
    global $estrut;
    global $c61_reduz;
    global $c61_codcon;
    global $c61_codigo;
    global $c60_codcon;
    global $c60_descr;
    global $c60_finali;
    global $c61_instit;
    global $saldo_anterior;
    global $saldo_anterior_debito;
    global $saldo_anterior_credito;
    global $saldo_final;
    global $result_estrut;
    global $sinal_anterior;
    global $sinal_final;
    $work_planomae = array();
    $work_planoestrut = array();
    $work_plano = array();
    $seq = 0;
    for ($i = 0; $i < pg_numrows($result); $i++) {
        db_fieldsmemory($result, $i);
        if ($sinal_anterior == "C")
            $saldo_anterior *= -1;
        if ($sinal_final == "C")
            $saldo_final *= -1;
        $tot_anterior = $saldo_anterior;
        $tot_anterior_debito = $saldo_anterior_debito;
        $tot_anterior_credito = $saldo_anterior_credito;
        $tot_saldo_final = $saldo_final;
        $key = array_search("$estrut_mae", $work_planomae);
        if ($key === false) { // n�o achou
            $work_planomae[$seq] = $estrut_mae;
            $work_planoestrut[$seq] = $estrut;
            $work_plano[$seq] = array(
                0 => "$c61_reduz", 1 => "$c61_codcon", 2 => "$c61_codigo", 3 => "$c60_descr",
                4 => "$c60_finali", 5 => "$c61_instit", 6 => "$saldo_anterior", 7 => "$saldo_anterior_debito",
                8 => "$saldo_anterior_credito", 9 => "$saldo_final", 10 => "$sinal_anterior", 11 => "$sinal_final"
            );
            $seq = $seq + 1;
        } else {
            $work_plano[$key][6] += $tot_anterior;
            $work_plano[$key][7] += $tot_anterior_debito;
            $work_plano[$key][8] += $tot_anterior_credito;
            $work_plano[$key][9] += $tot_saldo_final;
        }
        $estrutural = $estrut;
        for ($ii = 1; $ii < 10; $ii++) {
            $estrutural = db_le_mae_conplano($estrutural);
            $nivel = db_le_mae_conplano($estrutural, true);

            $key = array_search("$estrutural", $work_planomae);
            if ($key === false) { // n�o achou
                // busca no banco e inclui
                $res = db_query(
                    "select c60_descr,c60_finali,c60_codcon from conplano where c60_anousu=" . db_getsession("DB_anousu")
                        . " and c60_estrut = '$estrutural'"
                );
                if ($res == false || pg_numrows($res) == 0) {
                    db_redireciona(
                        "db_erros.php?fechar=true&db_erro=Est� faltando cadastrar esse estrutural na contabilidade. N�vel : $nivel  Estrutural : $estrutural - ano: " + db_getsession("DB_anousu")
                    );
                    exit;
                }
                db_fieldsmemory($res, 0);

                $work_planomae[$seq] = $estrutural;
                $work_planoestrut[$seq] = '';
                $work_plano[$seq] = (array(
                    0 => 0, 1 => 0, 2 => $c60_codcon, 3 => $c60_descr, 4 => $c60_finali, 5 => 0,
                    6 => $saldo_anterior, 7 => $saldo_anterior_debito, 8 => $saldo_anterior_credito, 9 => $saldo_final,
                    10 => $sinal_anterior, 11 => $sinal_final
                ));
                $seq++;
            } else {
                $work_plano[$key][6] += $tot_anterior;
                $work_plano[$key][7] += $tot_anterior_debito;
                $work_plano[$key][8] += $tot_anterior_credito;
                $work_plano[$key][9] += $tot_saldo_final;
            }
            if ($nivel == 1)
                break;
        }
    }
    for ($i = 0; $i < sizeof($work_planomae); $i++) {
        $mae = $work_planomae[$i];
        $estrut = $work_planoestrut[$i];
        $c61_reduz = $work_plano[$i][0];
        $c61_codcon = $work_plano[$i][1];
        $c61_codigo = $work_plano[$i][2];
        $c60_descr = $work_plano[$i][3];
        $c60_finali = $work_plano[$i][4];
        $c61_instit = $work_plano[$i][5];
        $saldo_anterior = $work_plano[$i][6];
        $saldo_anterior_debito = $work_plano[$i][7];
        $saldo_anterior_credito = $work_plano[$i][8];
        $saldo_final = $work_plano[$i][9];
        $sinal_anterior = $work_plano[$i][10];
        $sinal_final = $work_plano[$i][11];

        $sql = "insert into work_plano
        values ('$mae',
        '$estrut',
        $c61_reduz,
        $c61_codcon,
        $c61_codigo,
        '" . pg_escape_string($c60_descr) . "',
        '" . pg_escape_string($c60_finali) . "',
        $c61_instit,
        $saldo_anterior,
        $saldo_anterior_debito,
        $saldo_anterior_credito,
        $saldo_final,
        '$sinal_anterior',
        '$sinal_final')

        ";
        db_query($sql);
    }

    $sql = "select case when c61_reduz = 0 then estrut_mae else estrut end as estrutural,
      c61_reduz,
      c61_codcon,
      c61_codigo,
      c60_descr,
      c60_finali,
      c61_instit,
      abs(saldo_anterior) as saldo_anterior,
      abs(saldo_anterior_debito) as saldo_anterior_debito,
      abs(saldo_anterior_credito) as saldo_anterior_credito,
      abs(saldo_final) as saldo_final,
      case when saldo_anterior < 0 then 'C'
      when saldo_anterior > 0 then 'D'
      else ' ' end as  sinal_anterior,
      case when saldo_final < 0 then 'C'
      when saldo_final > 0 then 'D'
      else ' ' end as  sinal_final
      from work_plano
      order by estrut_mae,estrut";

    if ($retsql == false) {
        $result_final = db_query($sql);
        // db_criatabela($result_final); exit;
        return $result_final;
    } else {
        return $sql;
    }
}
/*
     * status : desativada
     * mostrava o balancete completo, com todas as contas de nivel 3 e 4 abertas
     */

function db_planocontassaldo_completo(
    $anousu,
    $dataini,
    $datafim,
    $retsql = false,
    $where = '',
    $aOrcParametro = array(),
    $estrut_inicial = '',
    $acumula_reduzido = false,
    $encerramento = 'false'
) {

    return db_planocontassaldo_matriz(
        $anousu,
        $dataini,
        $datafim,
        $retsql,
        $where,
        $estrut_inicial,
        $acumula_reduzido,
        $encerramento,
        "",
        $aOrcParametro
    );
}

/**
 * status: desativada
 * foi a primeira fun��o criada. usando update em tabela tempor�ria
 */
function db_planocontassaldo(
    $anousu,
    $dataini,
    $datafim,
    $retsql = false,
    $where = '',
    $estrut_inicial = '',
    $acumula_reduzido = false,
    $encerramento = 'false'
) {

    return db_planocontassaldo_matriz(
        $anousu,
        $dataini,
        $datafim,
        $retsql,
        $where,
        $estrut_inicial,
        $acumula_reduzido,
        $encerramento
    );
}

function db_elementosaldo(
    $tipo_agrupa = 0,
    $tipo_saldo = 2,
    $where = '',
    $anousu = null,
    $dataini = null,
    $datafim = null,
    $retsql = false
) {

    if ($tipo_agrupa == 1) {
        $agrupa = ' o58_orgao, o40_descr ,';
        $agrupa1 = ' o58_orgao integer, o40_descr varchar(50),';
    } elseif ($tipo_agrupa == 2) {
        $agrupa = ' o58_orgao, o40_descr, o58_unidade , o41_descr,';
        $agrupa1 = ' o58_orgao integer, o40_descr varchar(50), o58_unidade integer, o41_descr varchar(50),';
    } else {
        $agrupa = '';
        $agrupa1 = '';
    }

    if ($anousu == null)
        $anousu = db_getsession("DB_anousu");

    if ($dataini == null)
        $dataini = date('Y-m-d', db_getsession('DB_datausu'));

    if ($datafim == null)
        $datafim = date('Y-m-d', db_getsession('DB_datausu'));

    if ($where != '') {
        $condicao = " and " . $where;
    } else {
        $condicao = "";
    }

    if ($tipo_saldo == 1)
        $tipo_pa = 'dot_ini';
    else
        $tipo_pa = 'empenhado - anulado';

    $sql = "
      select $agrupa codele ,elemento , descr,
      sum(dot_ini) 			as dot_ini,
      sum(saldo_anterior) 		as saldo_anterior,
      sum(empenhado) 			as empenhado,
      sum(anulado) 			as anulado,
      sum(liquidado)		 	as liquidado,
      sum(pago) 			as pago,
      sum(suplementado) 		as suplementado,
      sum(reduzido) 			as reduzido,
      sum(atual) 			as atual,
      sum(reservado) 			as reservado,
      sum(atual_menos_reservado) 	as atual_menos_reservado,
      sum(atual_a_pagar) 		as atual_a_pagar,
      sum(atual_a_pagar_liquidado) 	as atual_a_pagar_liquidado,
      sum(empenhado_acumulado) 	as empenhado_acumulado,
      sum(anulado_acumulado) 		as anulado_acumulado,
      sum(liquidado_acumulado) 	as liquidado_acumulado,
      sum(pago_acumulado) 		as pago_acumulado,
      sum(suplementado_acumulado) 	as suplementado_acumulado,
      sum(reduzido_acumulado) 		as reduzido_acumulado,
      sum(suplemen)  		        as suplemen,
      sum(especial) 		        as especial,
      sum(especial_acumulado)	        as especial_acumulado

      from
      (select o58_anousu, $agrupa
      o56_codele as codele,
      o56_elemento as elemento,
      o56_descr as descr,
      substr(fc_dotacaosaldo,3,12)::float8   as dot_ini,
      substr(fc_dotacaosaldo,16,12)::float8  as saldo_anterior,
      substr(fc_dotacaosaldo,29,12)::float8  as empenhado,
      substr(fc_dotacaosaldo,42,12)::float8  as anulado,
      substr(fc_dotacaosaldo,55,12)::float8  as liquidado,
      substr(fc_dotacaosaldo,68,12)::float8  as pago,
      substr(fc_dotacaosaldo,81,12)::float8  as suplementado,
      substr(fc_dotacaosaldo,094,12)::float8 as reduzido,
      substr(fc_dotacaosaldo,107,12)::float8 as atual,
      substr(fc_dotacaosaldo,120,12)::float8 as reservado,
      substr(fc_dotacaosaldo,133,12)::float8 as atual_menos_reservado,
      substr(fc_dotacaosaldo,146,12)::float8 as atual_a_pagar,
      substr(fc_dotacaosaldo,159,12)::float8 as atual_a_pagar_liquidado,
      substr(fc_dotacaosaldo,172,12)::float8 as empenhado_acumulado,
      substr(fc_dotacaosaldo,185,12)::float8 as anulado_acumulado,
      substr(fc_dotacaosaldo,198,12)::float8 as liquidado_acumulado,
      substr(fc_dotacaosaldo,211,12)::float8 as pago_acumulado,
      substr(fc_dotacaosaldo,224,12)::float8 as suplementado_acumulado,
      substr(fc_dotacaosaldo,237,12)::float8 as reduzido_acumulado,
      substr(fc_dotacaosaldo,250,12)::float8 as suplemen,
      substr(fc_dotacaosaldo,276,12)::float8 as especial,
      substr(fc_dotacaosaldo,289,12)::float8 as especial_acumulado
      from (select *, fc_dotacaosaldo($anousu,o58_coddot,$tipo_saldo,'$dataini','$datafim')
      from orcdotacao w
      inner join orcelemento e on w.o58_codele  = e.o56_codele
      and w.o58_anousu  = e.o56_anousu
      and e.o56_orcado is true
      inner join orcorgao    o on w.o58_orgao   = o.o40_orgao
      and o.o40_anousu  = $anousu
      inner join orcunidade  u on w.o58_unidade = u.o41_unidade
      and w.o58_orgao   = u.o41_orgao
      and u.o41_anousu  = $anousu
      where o58_anousu = $anousu $condicao
      order by o58_anousu ,
      $agrupa
      o56_codele,
      o56_elemento,
      o58_coddot
      ) as x
      ) as xx
      group by $agrupa codele, elemento,descr
      ";

    db_query(
        "create temporary table work_plano($agrupa1
      codele integer,
      elemento varchar(13),
      descr varchar(50),
      dot_ini float8,
      saldo_anterior float8,
      empenhado float8,
      anulado float8,
      liquidado float8,
      pago float8,
      suplementado float8,
      reduzido float8,
      atual float8,
      reservado float8,
      atual_menos_reservado float8,
      atual_a_pagar float8,
      atual_a_pagar_liquidado float8,
      empenhado_acumulado float8,
      anulado_acumulado float8,
      liquidado_acumulado float8,
      pago_acumulado float8,
      suplementado_acumulado float8,
      reduzido_acumulado float8,
      suplemen           float8,
      especial           float8,
      especial_acumulado float8 )"
    );
    if ($tipo_agrupa == 1) {
        db_query("create index work_plano_orgao_elemento on work_plano(o58_orgao,elemento)");
    } elseif ($tipo_agrupa == 1) {
        db_query("create index work_plano_orgao_unidade_elemento on work_plano(o58_orgao,o58_unidade,elemento)");
    } else {
        db_query("create index work_plano_elemento on work_plano(elemento)");
    }

    $result = db_query($sql);

    // db_criatabela($result);
    $tot_dot_ini = 0;
    $tot_saldo_anterior = 0;
    $tot_empenhado = 0;
    $tot_anulado = 0;
    $tot_liquidado = 0;
    $tot_pago = 0;
    $tot_suplementado = 0;
    $tot_reduzido = 0;
    $tot_atual = 0;
    $tot_reservado = 0;
    $tot_atual_menos_reservado = 0;
    $tot_atual_a_pagar = 0;
    $tot_atual_a_pagar_liquidado = 0;
    $tot_empenhado_acumulado = 0;
    $tot_anulado_acumulado = 0;
    $tot_liquidado_acumulado = 0;
    $tot_pago_acumulado = 0;
    $tot_suplementado_acumulado = 0;
    $tot_reduzido_acumulado = 0;
    $tot_suplemen = 0;
    $tot_especial = 0;
    $tot_especial_acumulado = 0;

    global $o58_orgao, $o40_descr, $o58_unidade, $o41_descr, $o56_descr, $codele, $elemento, $descr, $dot_ini, $saldo_anterior, $empenhado, $anulado, $liquidado, $pago, $suplementado, $reduzido, $atual, $reservado, $atual_menos_reservado, $atual_a_pagar, $atual_a_pagar_liquidado, $empenhado_acumulado, $anulado_acumulado, $liquidado_acumulado, $pago_acumulado, $suplementado_acumulado, $reduzido_acumulado, $especial, $especial_acumulado, $suplemen;
    // for($i = 0;$i < 10;$i++){
    for ($i = 0; $i < pg_numrows($result); $i++) {
        db_fieldsmemory($result, $i);
        $tot_dot_ini = $dot_ini;
        $tot_saldo_anterior = $saldo_anterior;
        $tot_empenhado = $empenhado;
        $tot_anulado = $anulado;
        $tot_liquidado = $liquidado;
        $tot_pago = $pago;
        $tot_suplementado = $suplementado;
        $tot_reduzido = $reduzido;
        $tot_atual = $atual;
        $tot_reservado = $reservado;
        $tot_atual_menos_reservado = $atual_menos_reservado;
        $tot_atual_a_pagar = $atual_a_pagar;
        $tot_atual_a_pagar_liquidado = $atual_a_pagar_liquidado;
        $tot_empenhado_acumulado = $empenhado_acumulado;
        $tot_anulado_acumulado = $anulado_acumulado;
        $tot_liquidado_acumulado = $liquidado_acumulado;
        $tot_pago_acumulado = $pago_acumulado;
        $tot_suplementado_acumulado = $suplementado_acumulado;
        $tot_reduzido_acumulado = $reduzido_acumulado;
        $tot_suplemen = $suplemen;
        $tot_especial = $especial;
        $tot_especial_acumulado = $especial_acumulado;

        if ($tipo_agrupa == 1) {
            $agrupa2 = $o58_orgao . ",'" . $o40_descr . "',";
            $agrupa3 = ' and o58_orgao = ' . $o58_orgao;
        } elseif ($tipo_agrupa == 2) {
            $agrupa2 = $o58_orgao . ",'" . $o40_descr . "'," . $o58_unidade . ",'" . $o41_descr . "',";
            $agrupa3 = ' and o58_orgao = ' . $o58_orgao . ' and o58_unidade = ' . $o58_unidade;
        } else {
            $agrupa2 = '';
            $agrupa3 = '';
        }
        db_query(
            "insert into work_plano values($agrupa2
          $codele,
          '$elemento',
          '$descr',
          $dot_ini,
          $saldo_anterior,
          $empenhado,
          $anulado,
          $liquidado,
          $pago,
          $suplementado,
          $reduzido,
          $atual,
          $reservado,
          $atual_menos_reservado,
          $atual_a_pagar,
          $atual_a_pagar_liquidado,
          $empenhado_acumulado,
          $anulado_acumulado,
          $liquidado_acumulado,
          $pago_acumulado,
          $suplementado_acumulado,
          $reduzido_acumulado,
          $suplemen,
          $especial,
          $especial_acumulado
          )"
        );
        $estrutural = $elemento;
        for ($ii = 1; $ii < 11; $ii++) {
            $estrutural = db_le_mae_sistema($estrutural);
            $nivel = db_le_mae_sistema($estrutural, true);
            $result_estrut = db_query("select dot_ini from work_plano where elemento = '$estrutural' $agrupa3");

            //       db_criatabela($result_estrut);
            if (@pg_numrows($result_estrut) != true) {
                $res = db_query(
                    "select o56_descr from orcelemento where o56_anousu = $anousu and o56_elemento = '$estrutural'"
                );
                if (@pg_numrows($res) != true)
                    break;
                db_fieldsmemory($res, 0);

                $result_1 = db_query(
                    "insert into work_plano values($agrupa2
              $codele,
              '$estrutural',
              '$o56_descr',
              $dot_ini,
              $saldo_anterior,
              $empenhado,
              $anulado,
              $liquidado,
              $pago,
              $suplementado,
              $reduzido,
              $atual,
              $reservado,
              $atual_menos_reservado,
              $atual_a_pagar,
              $atual_a_pagar_liquidado,
              $empenhado_acumulado,
              $anulado_acumulado,
              $liquidado_acumulado,
              $pago_acumulado,
              $suplementado_acumulado,
              $reduzido_acumulado,
              $suplemen,
              $especial,
              $especial_acumulado
              )"
                );
            } else {

                db_query(
                    "update work_plano set dot_ini = dot_ini + $tot_dot_ini,
              saldo_anterior = saldo_anterior + $tot_saldo_anterior,
              empenhado = empenhado + $tot_empenhado,
              anulado = anulado + $tot_anulado,
              liquidado = liquidado + $tot_liquidado,
              pago = pago + $tot_pago,
              suplementado = suplementado + $tot_suplementado,
              reduzido = reduzido + $tot_reduzido,
              atual = atual + $tot_atual,
              reservado = reservado + $tot_reservado,
              atual_menos_reservado = atual_menos_reservado + $tot_atual_menos_reservado,
              atual_a_pagar = atual_a_pagar + $tot_atual_a_pagar,
              atual_a_pagar_liquidado = atual_a_pagar_liquidado + $tot_atual_a_pagar_liquidado,
              empenhado_acumulado = empenhado_acumulado + $tot_empenhado_acumulado,
              anulado_acumulado = anulado_acumulado + $tot_anulado_acumulado,
              liquidado_acumulado = liquidado_acumulado + $tot_liquidado_acumulado,
              pago_acumulado = pago_acumulado + $tot_pago_acumulado,
              suplementado_acumulado = suplementado_acumulado + $tot_suplementado_acumulado,
              reduzido_acumulado = reduzido_acumulado + $tot_reduzido_acumulado,
              suplemen           = suplemen + $suplemen,
              especial           = especial     +   $tot_especial,
              especial_acumulado = especial_acumulado + $tot_especial_acumulado
              where elemento = '$estrutural' $agrupa3"
                );
            }
            if ($nivel == 1)
                break;
        }
    }

    $sql = "select *
        from work_plano
        order by $agrupa elemento";

    if ($retsql == false) {
        return $result_final = db_query($sql);
    } else {
        return $sql;
    }
}

function db_rcl($mesini, $mesfim, $instit)
{

    $clconrelinfo = new cl_conrelinfo;

    $w_instit = str_replace('-', ', ', $instit);
    $result_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores('5', $w_instit));

    $linhaini = 18 * ($mesini - 1);
    $linhafim = (18 * ($mesfim - 1)) + 17;
    $numlin = 0;
    $mes = 0;
    for ($p = 0; $p < 18; $p++) {
        $valor[$p] = 0;
    }
    global $c83_informacao;
    for ($i = $linhaini; $i < $linhafim; $i++) {
        if ($numlin == 18) {
            $mes = $mes + 1;
            $numlin = 0;
        }
        db_fieldsmemory($result_variaveis, $i);
        $valor[$numlin] += $c83_informacao;
        $numlin++;
    }
    $valorrec = $valor[0] + $valor[1] + $valor[2] + $valor[3] + $valor[4] + $valor[5] + $valor[6] + $valor[7];

    $valorded = $valor[8] + $valor[9] + $valor[11] + $valor[12] + $valor[13] + $valor[14] + $valor[16] + $valor[17];
    $rcl = $valorrec - $valorded;
    return $rcl;
}

/*
     *
     *
     */

function grupoconta(
    $anousu,
    $dataini = '2005-01-01',
    $datafim = '2005-12-31',
    $db_selinstit = 1,
    $retsql = false,
    $orc = false
) {
    /*
       *Esta Fun��o Agrupa as conta pela Sele��o do Relat�rio 21(tabela - orcparamrel) Sequencias (tabela - orcparamseq), os elemente s�o informados manualmente pelo usuario
       *OBS 1 ha fun��o pode retonar mais valores desde q tenha o cuidado de n�o mudar os nomes dos campos, tb se deve ter o cuidado de trazer valores em todos os SQL
       *     devido no final a fun��o sempre possuir um UNION SENDO ASSIM CUIDADO CUIDADO FAZER BACK TOMAR CUIDADO
       * OBS 2 n�o utilize mais fun��es como db_planocontassaldo_completo ou db_dotacaosaldo, utilize as q ja existe, se criar novas vai deixar o sistema mais lento
       *     ent�o grupe trabalhe SQL � mais vantagem.
       * ********Parmetros**************
       * $anousu
       * $dataini - data inicial de pesquisa
       * $datafim - data final de pesquisa
       * $db_selinstit  - Institui��es
       * $retsql - Retornar o SQL ou a Tabela
       * $orc - Op��o de retonar� valores or�amentarios ou de execu��o
       */

    $selinstit = str_replace('-', ', ', $db_selinstit);
    $where_rec = " o70_instit in ($selinstit)";
    $sele_work = "c61_instit in ($selinstit)";
    $where = "w.o58_instit in ($selinstit) ";

    global $estrutural, $c60_descr, $saldo_anterior, $saldo_anterior_debito, $saldo_anterior_credito, $saldo_final, $o57_fonte, $o57_descr, $saldo_inicial, $saldo_arrecadado, $anterior, $inicial, $executado;

    $orcparamrel = new cl_orcparamrel;
    //******************************************************************************************************************
    // � necessario realizar um for na tabela orcparamseq para q ha mesma esteja sempre atualizada automaticamente
    //******************************************************************************************************************
    $paramconta['0'] = $orcparamrel->sql_parametro('21', '0', str_replace('-', ', ', $db_selinstit));
    $paramconta['1'] = $orcparamrel->sql_parametro('21', '1', str_replace('-', ', ', $db_selinstit));
    $paramconta['2'] = $orcparamrel->sql_parametro('21', '2', str_replace('-', ', ', $db_selinstit));
    $paramconta['3'] = $orcparamrel->sql_parametro('21', '3', str_replace('-', ', ', $db_selinstit));
    $paramconta['4'] = $orcparamrel->sql_parametro('21', '4', str_replace('-', ', ', $db_selinstit));
    $paramconta['5'] = $orcparamrel->sql_parametro('21', '5', str_replace('-', ', ', $db_selinstit));
    $paramconta['6'] = $orcparamrel->sql_parametro('21', '6', str_replace('-', ', ', $db_selinstit));
    $paramconta['7'] = $orcparamrel->sql_parametro('21', '7', str_replace('-', ', ', $db_selinstit));
    $paramconta['8'] = $orcparamrel->sql_parametro('21', '8', str_replace('-', ', ', $db_selinstit));
    $paramconta['9'] = $orcparamrel->sql_parametro('21', '9', str_replace('-', ', ', $db_selinstit));
    $paramconta['10'] = $orcparamrel->sql_parametro('21', '10', str_replace('-', ', ', $db_selinstit));
    $paramconta['11'] = $orcparamrel->sql_parametro('21', '11', str_replace('-', ', ', $db_selinstit));
    $paramconta['12'] = $orcparamrel->sql_parametro('21', '12', str_replace('-', ', ', $db_selinstit));
    $paramconta['13'] = $orcparamrel->sql_parametro('21', '13', str_replace('-', ', ', $db_selinstit));
    $paramconta['14'] = $orcparamrel->sql_parametro('21', '14', str_replace('-', ', ', $db_selinstit));
    $paramconta['15'] = $orcparamrel->sql_parametro('21', '15', str_replace('-', ', ', $db_selinstit));
    $paramconta['16'] = $orcparamrel->sql_parametro('21', '16', str_replace('-', ', ', $db_selinstit));
    $paramconta['17'] = $orcparamrel->sql_parametro('21', '17', str_replace('-', ', ', $db_selinstit));
    $paramconta['18'] = $orcparamrel->sql_parametro('21', '18', str_replace('-', ', ', $db_selinstit));
    $paramconta['19'] = $orcparamrel->sql_parametro('21', '19', str_replace('-', ', ', $db_selinstit));

    if ($orc == false) { // testa o parametro, se sim � valores de EXECU��O
        // Esta fun��o esta sendo utilizada para trazer as contas do RECEITA E DESPESA, ATIVO,PASSIVO,DIMINUTIVO E AUMENTATIVO
        $sql1 = db_planocontassaldo_completo($anousu, $dataini, $datafim, true, $sele_work);
        $sql11 = "select " . "bbb.estrutural as estrutural, " . "bbb.c60_descr as c60_descr, "
            . "bbb.saldo_anterior as saldo_anterior, " . "bbb.saldo_anterior_debito as saldo_anterior_debito, "
            . "bbb.saldo_anterior_credito as saldo_anterior_credito, " . "bbb.saldo_final as saldo_final "
            . "from ($sql1) as bbb " . "where substr(bbb.estrutural,1,1)<>'3'";

        // Esta fun��o esta sendo utilizada para trazer as contas do DESPESA
        $sql2 = db_dotacaosaldo(8, 2, 4, true, $where, $anousu, $dataini, $datafim, null, null, true);
        $sql22 = "select " . "ccc.o58_elemento||'00' as estrutural, " . "ccc.o56_descr as c60_descr, "
            . "sum(ccc.saldo_anterior) as saldo_anterior, "
            . "sum(ccc.empenhado)-sum(anulado) as saldo_anterior_debito, "
            . "sum(ccc.liquidado) as saldo_anterior_credito, " . "sum(ccc.pago) as saldo_final "
            . "from ($sql2) as ccc " . "group by ccc.o58_elemento||'00',ccc.o56_descr ";

        // Esta tabela � criada para estruturar os valores de todos as contas de despesas,
        $creat_sql = "create temp table work as
          select o56_elemento||'00' as estrutural,o56_descr as c60_descr,0::float8 as valor1,0::float8 as valor2,0::float8 as valor3,0::float8 as valor4
          from orcelemento
          inner join conplano on c60_codcon = o56_codele and c60_anousu = o56_anousu
          where o56_anousu = $anousu";

        // Come�o da estrutura��o da tabela temporaria
        $result_rec = db_query($creat_sql);
        $result_rec = db_query($sql22);
        for ($i = 0; $i < pg_numrows($result_rec); $i++) {
            db_fieldsmemory($result_rec, $i);
            //aqui � colocado os valores da DB_DOTA��OSALDO recomento e alerto ao mesmo tempo pode, pode se buscar mais valores desde tome o cuidado citado acima no come�o da fun��o
            $valor1 = $saldo_anterior;
            $valor2 = $saldo_anterior_debito;
            $valor3 = $saldo_anterior_credito;
            $valor4 = $saldo_final;
            // n�o coloque o dedo nesses valores adcione abaixo e n�o esque�a de adiconar na tabela

            $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3,valor4 = valor4+$valor4 where work.estrutural = '$estrutural'";
            $result = db_query($sql);
            $executa = true;
            $conta = 0;
            while ($executa == true) {
                $estrutural = db_le_mae($estrutural, false);
                $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3,valor4 = valor4+$valor4 where work.estrutural = '"
                    . $estrutural . "00" . "'";
                $result = db_query($sql);
                if (substr($estrutural, 1, 12) == "0000000000000") {
                    $executa = false;
                }
                $conta++;
                if ($conta > 10)
                    $executa = false;
            }
        }
        //to listando todas as contas de despesas com valores
        $sql22 = "select * from work ";

        //nesse SQL � trabalhado novamente com a db_dota��o saldo como eu disse na OBS 2, caso n�o tenha lido recomento,
        // continuando eu agrupo as fun��es, quando eu fiz isso era pq eu queria os valores das fun�oes para colocar no Relat�rio BALAN�O FINANCEIRO
        $sql33 = "select " . "'F'||ddd.o58_funcao as estrutural, " . "ddd.o52_descr as c60_descr, "
            . "sum(ddd.saldo_anterior) as saldo_anterior, "
            . "sum(ddd.empenhado)-sum(anulado) as saldo_anterior_debito, "
            . "sum(ddd.liquidado) as saldo_anterior_credito, " . "sum(ddd.pago) as saldo_final "
            . "from ($sql2) as ddd " . "group by 'F'||ddd.o58_funcao,ddd.o52_descr ";
        // CUIDADO
        // CUIDADO
        //AREA DE UNION, aqui estou unindo todos os sqls para que eu tenha um unico para ent�o eu executar e tirar apenas os dados solicitados pelo usuario
        //dados esse q foi comentado assim, leia os comentarios n�o to gostando meu tempo ha atoa
        $sql12 = $sql11 . " union " . $sql22;
        $sql = $sql12 . " union " . $sql33;
        $result = db_query($sql);

        // aqui � filtrado das as conta selecionadas pelo parametros CARLOS OU PAULO se for um de vc(s) acerta o for nos parametros e troca para array
        $criatabela = 'create temp table work_grupconta(' . 'grupo 						varchar(4),'
            . 'estrut 						varchar(20),' . 'descr 						varchar(100),' . 'valor_ant 				float8,'
            . 'valor_debito    		float8,' . 'valor_credito  	 	float8,' . 'valor_final 				float8' . ')';
        global $estrutural, $c60_descr, $saldo_anterior, $saldo_anterior_debito, $saldo_anterior_credito, $saldo_final;
        db_query($criatabela);
        for ($i = 0; $i < pg_numrows($result); $i++) {
            db_fieldsmemory($result, $i);
            for ($x = 0; $x < count($paramconta); $x++) {
                if (in_array($estrutural, $paramconta[$x])) {
                    $g = $x;
                    db_query(
                        "insert into work_grupconta values (' " . $g . " '," . "'" . $estrutural . "'," . "'" . pg_escape_string($c60_descr)
                            . "'," . "$saldo_anterior," . "$saldo_anterior_debito," . "$saldo_anterior_credito,"
                            . "$saldo_final)"
                    );
                }
                if (substr($estrutural, 0, 1) == 'F') {
                    $estrutural = substr($estrutural, 1);
                    $g = 'F';
                    db_query(
                        "insert into work_grupconta values (' " . $g . " '," . "'" . $estrutural . "'," . "'" . pg_escape_string($c60_descr)
                            . "'," . "$saldo_anterior," . "$saldo_anterior_debito," . "$saldo_anterior_credito,"
                            . "$saldo_final)"
                    );
                }
            }
        }
    } else { // caso queira OR�AMENTARIA

        // vamos novamente para o SQL ja bem eu falei assim para cuidar bem dessas coisas ou seja tem um IF ha db_dotacaosaldo esta sendo utilizada uma vez e a receita tb
        // eu ja disse pode adcionar valores, mas n�o retire
        $sql1 = db_receitasaldo(11, 1, 3, true, $where_rec, $anousu, $dataini, $datafim, true);
        $grup_rec = "select ccc.o57_fonte as estrutural, " . "ccc.o57_descr as c60_descr, "
            . "sum(ccc.saldo_anterior) as anterior, "
            . "sum(ccc.saldo_inicial) + sum(ccc.saldo_prevadic_acum)as inicial, "
            . "sum(saldo_arrecadado) as executado " . "from ($sql1) as ccc group by ccc.o57_fonte,ccc.o57_descr ";

        $sql2 = db_dotacaosaldo(8, 2, 4, true, $where, $anousu, $dataini, $datafim, null, null, true);
        $sql11 = "select ccc.o58_elemento||'00' as estrutural, " . "ccc.o56_descr as c60_descr, "
            . "sum(ccc.saldo_anterior) as anterior, "
            . "sum(ccc.dot_ini) + sum(ccc.suplementado_acumulado) - sum(ccc.reduzido_acumulado) as inicial, "
            . "sum(ccc.empenhado)-sum(anulado) as executado "
            . "from ($sql2) as ccc group by ccc.o58_elemento||'00',ccc.o56_descr ";

        // preste aten�o aqui eu podia ter usado duas db_dotacaosaldo mas eu executaria ela duas vezes sendo assim eu agrupo
        $sql33 = "select " . "'F'||ddd.o58_funcao as estrutural, " . "ddd.o52_descr as c60_descr, "
            . "sum(ddd.saldo_anterior) as anterior, "
            . "sum(ddd.dot_ini) + sum(ddd.suplementado_acumulado) - sum(ddd.reduzido_acumulado) as inicial, "
            . "sum(ddd.empenhado)-sum(ddd.anulado) as executado " . "from ($sql2) as ddd "
            . "group by 'F'||ddd.o58_funcao,ddd.o52_descr ";

        //crio a trabela temporaria, nesse momento eu me pergunto, copio o comentario acima e col� aqui ou mando o comum ler a cima, bom � melhor copiar COMUM n�o l�
        // Esta tabela � criada para estruturar os valores de todos as contas de despesas,
        $creat_sql = "create temp table work as
          select o56_elemento||'00' as estrutural,o56_descr as c60_descr,0::float8 as valor1,0::float8 as valor2,0::float8 as valor3
          from orcelemento
          inner join conplano on c60_codcon = o56_codele and c60_anousu = o56_anousu
          where o56_anousu = $anousu";

        $result_rec = db_query($creat_sql);
        $result_rec = db_query($sql11);
        // novamente eu copio, da vontade de mandar ler o DICAS.PHP, torama q o comum q esteja dando manuten��o aqui nesse codigo seja um dos velhos IF velho THEN deve estar lembrando de mim ELSE pergunta para os velhos
        // Come�o da estrutura��o da tabela temporaria
        for ($i = 0; $i < pg_numrows($result_rec); $i++) {
            db_fieldsmemory($result_rec, $i);
            // eu ja disse o que pode fazer aqui, se n�o sei acima vai ler
            $valor1 = $anterior;
            $valor2 = $inicial;
            $valor3 = $executado;
            // troca por array
            $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3 where work.estrutural = '$estrutural'";
            $result = db_query($sql);
            $executa = true;
            $conta = 0;
            while ($executa == true) {
                $estrutural = db_le_mae($estrutural, false);
                // troca por array
                $sql = "update work set valor1 = valor1+$valor1,valor2 = valor2+$valor2,valor3 = valor3+$valor3 where work.estrutural = '"
                    . $estrutural . "00" . "'";
                $result = db_query($sql);
                if (substr($estrutural, 1, 12) == "0000000000000") {
                    $executa = false;
                }
                $conta++;
                if ($conta > 10)
                    $executa = false;
            }
        }
        $sql22 = "select * from work";

        $sql12 = $grup_rec . " union " . $sql22;
        $sql = $sql12 . " union " . $sql33;
        $result = db_query($sql);

        $criatabela = 'create temp table work_grupconta(' . 'grupo 						varchar(4),'
            . 'estrut 						varchar(20),' . 'descr 						varchar(100),' . 'valor_anti 				float8,'
            . 'valor_ini    		float8,' . 'valor_exec  	 	float8' . ')';
        global $estrutural, $c60_descr, $anterior, $inicial, $executado;
        db_query($criatabela);
        for ($i = 0; $i < pg_numrows($result); $i++) {
            db_fieldsmemory($result, $i);
            for ($x = 0; $x < count($paramconta); $x++) {
                if (in_array($estrutural, $paramconta[$x])) {
                    $g = $x;
                    // troca por array ME ORGULHE eu n�o tinha tempo
                    db_query(
                        "insert into work_grupconta values (' " . $g . " '," . "'" . $estrutural . "'," . "'" . pg_escape_string($c60_descr)
                            . "'," . "$anterior," . "$inicial," . "$executado )"
                    );
                }
                if (substr($estrutural, 0, 1) == 'F') {
                    $estrutural = substr($estrutural, 1);
                    $g = 'F';
                    //troca por array ME ORGULHE eu n�o tinha tempo
                    db_query(
                        "insert into work_grupconta values (' " . $g . " '," . "'" . $estrutural . "'," . "'" . pg_escape_string($c60_descr)
                            . "'," . "$anterior," . "$inicial," . "$executado )"
                    );
                }
            }
        }
    }
    $sqlfim = "select * from work_grupconta order by grupo,estrut";
    if ($retsql == false) {
        $resultado = db_query($sqlfim);
    } else {
        $resultado = $sqlfim;
    }
    return $resultado;
}

function calcula_rcl($anousu, $dtini, $dtfin, $db_selinstit, $matriz = false)
{

    global $o57_fonte, $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro;

    $result_rec = new cl_receita_saldo_mes;
    $result_rec->anousu = $anousu;
    $result_rec->dtini = $dtini;
    $result_rec->dtfim = $dtfin;
    $result_rec->usa_datas = 'sim';
    $result_rec->instit = "" . str_replace('-', ', ', $db_selinstit) . " ";
    $result_rec->sql_record();
    $result_rec = $result_rec->result;
    @db_query("drop table work_plano");

    // pega parametros do relatorio de rcl
    $orcparamrel = new cl_orcparamrel;
    $param[1] = $orcparamrel->sql_parametro('5', '1', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[2] = $orcparamrel->sql_parametro('5', '2', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[3] = $orcparamrel->sql_parametro('5', '3', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[4] = $orcparamrel->sql_parametro('5', '4', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[5] = $orcparamrel->sql_parametro('5', '5', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[6] = $orcparamrel->sql_parametro('5', '6', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[7] = $orcparamrel->sql_parametro('5', '7', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[8] = $orcparamrel->sql_parametro('5', '8', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[9] = $orcparamrel->sql_parametro('5', '9', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[10] = $orcparamrel->sql_parametro('5', '10', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[11] = $orcparamrel->sql_parametro('5', '11', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[12] = $orcparamrel->sql_parametro('5', '12', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[13] = $orcparamrel->sql_parametro('5', '13', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[14] = $orcparamrel->sql_parametro('5', '14', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[15] = $orcparamrel->sql_parametro('5', '15', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

    // inicio dedu��o
    $param[16] = $orcparamrel->sql_parametro('5', '16', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[17] = $orcparamrel->sql_parametro('5', '17', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[18] = $orcparamrel->sql_parametro('5', '18', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

    $total = 0;

    $rcl_matriz['janeiro'] = 0;
    $rcl_matriz['fevereiro'] = 0;
    $rcl_matriz['marco'] = 0;
    $rcl_matriz['abril'] = 0;
    $rcl_matriz['maio'] = 0;
    $rcl_matriz['junho'] = 0;
    $rcl_matriz['julho'] = 0;
    $rcl_matriz['agosto'] = 0;
    $rcl_matriz['setembro'] = 0;
    $rcl_matriz['outubro'] = 0;
    $rcl_matriz['novembro'] = 0;
    $rcl_matriz['dezembro'] = 0;

    for ($p = 1; $p <= 18; $p++) {
        // 18 � a quantidade de parametros ou linhas existentes nos parametros

        for ($i = 0; $i < pg_numrows($result_rec); $i++) {
            db_fieldsmemory($result_rec, $i);

            $estrutural = $o57_fonte;

            if (in_array($estrutural, $param[$p])) {
                if ($p == 18) {

                    $janeiro *= -1;
                    $fevereiro *= -1;
                    $marco *= -1;
                    $abril *= -1;
                    $maio *= -1;
                    $junho *= -1;
                    $julho *= -1;
                    $agosto *= -1;
                    $setembro *= -1;
                    $outubro *= -1;
                    $novembro *= -1;
                    $dezembro *= -1;
                }
                if ($p <= 15) {

                    $rcl_matriz['janeiro'] += $janeiro;
                    $rcl_matriz['fevereiro'] += $fevereiro;
                    $rcl_matriz['marco'] += $marco;
                    $rcl_matriz['abril'] += $abril;
                    $rcl_matriz['maio'] += $maio;
                    $rcl_matriz['junho'] += $junho;
                    $rcl_matriz['julho'] += $julho;
                    $rcl_matriz['agosto'] += $agosto;
                    $rcl_matriz['setembro'] += $setembro;
                    $rcl_matriz['outubro'] += $outubro;
                    $rcl_matriz['novembro'] += $novembro;
                    $rcl_matriz['dezembro'] += $dezembro;
                } else {

                    //                if (substr($estrutural,0,3) == "497") {
                    if (db_conplano_grupo($anousu, substr($estrutural, 0, 3) . "%", 9001) == true) {

                        $rcl_matriz['janeiro'] -= ($janeiro);
                        $rcl_matriz['fevereiro'] -= ($fevereiro);
                        $rcl_matriz['marco'] -= ($marco);
                        $rcl_matriz['abril'] -= ($abril);
                        $rcl_matriz['maio'] -= ($maio);
                        $rcl_matriz['junho'] -= ($junho);
                        $rcl_matriz['julho'] -= ($julho);
                        $rcl_matriz['agosto'] -= ($agosto);
                        $rcl_matriz['setembro'] -= ($setembro);
                        $rcl_matriz['outubro'] -= ($outubro);
                        $rcl_matriz['novembro'] -= ($novembro);
                        $rcl_matriz['dezembro'] -= ($dezembro);
                    } else {

                        $rcl_matriz['janeiro'] -= $janeiro;
                        $rcl_matriz['fevereiro'] -= $fevereiro;
                        $rcl_matriz['marco'] -= $marco;
                        $rcl_matriz['abril'] -= $abril;
                        $rcl_matriz['maio'] -= $maio;
                        $rcl_matriz['junho'] -= $junho;
                        $rcl_matriz['julho'] -= $julho;
                        $rcl_matriz['agosto'] -= $agosto;
                        $rcl_matriz['setembro'] -= $setembro;
                        $rcl_matriz['outubro'] -= $outubro;
                        $rcl_matriz['novembro'] -= $novembro;
                        $rcl_matriz['dezembro'] -= $dezembro;
                    }
                }
            }
        }
    }

    $total = $rcl_matriz['janeiro'] + $rcl_matriz['fevereiro'] + $rcl_matriz['marco'] + $rcl_matriz['abril']
        + $rcl_matriz['maio'] + $rcl_matriz['junho'] + $rcl_matriz['julho'] + $rcl_matriz['agosto']
        + $rcl_matriz['setembro'] + $rcl_matriz['outubro'] + $rcl_matriz['novembro'] + $rcl_matriz['dezembro'];

    if ($matriz == true) {
        return $rcl_matriz;
    }

    return $total;
}

function calcula_rcl2($anousu, $dtini, $dtfin, $db_selinstit, $matriz = false, $codrel = 5, $data = 0)
{

    require_once(modification("libs/db_utils.php"));
    require_once(modification("model/linhaRelatorioContabil.model.php"));
    global $o57_fonte, $janeiro, $fevereiro, $marco, $abril, $maio, $junho, $julho, $agosto, $setembro, $outubro, $novembro, $dezembro, $bimestre, $dt;

    if ($data == 0) {
        $dt = explode("-", $dtfin);
    } else {
        $dt = explode("-", $data);
    }

    $bimestre = (int) substr(db_retorna_periodo($dt[1], "B"), 0, 1);
    $bimestre = $dt[1];
    $flag_anterior = false;

    if ($anousu < db_getsession("DB_anousu")) { // Exercicio anterior

        $bimestre += 1;
        $flag_anterior = true;
    }

    $result_rec = new cl_receita_saldo_mes;
    $result_rec->anousu = $anousu;
    $result_rec->dtini = $dtini;
    $result_rec->dtfim = $dtfin;
    $result_rec->usa_datas = 'sim';
    $result_rec->instit = $db_selinstit;
    $result_rec->sql_record();
    $result_rec = $result_rec->result;
    @db_query("drop table work_plano");

    // pega parametros do relatorio de rcl
    $orcparamrel = new cl_orcparamrel;
    $param[1] = $orcparamrel->sql_parametro($codrel, '1', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[2] = $orcparamrel->sql_parametro($codrel, '2', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[3] = $orcparamrel->sql_parametro($codrel, '3', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[4] = $orcparamrel->sql_parametro($codrel, '4', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[5] = $orcparamrel->sql_parametro($codrel, '5', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[6] = $orcparamrel->sql_parametro($codrel, '6', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[7] = $orcparamrel->sql_parametro($codrel, '7', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[8] = $orcparamrel->sql_parametro($codrel, '8', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[9] = $orcparamrel->sql_parametro($codrel, '9', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[10] = $orcparamrel->sql_parametro($codrel, '10', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[11] = $orcparamrel->sql_parametro($codrel, '11', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[12] = $orcparamrel->sql_parametro($codrel, '12', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[13] = $orcparamrel->sql_parametro($codrel, '13', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[14] = $orcparamrel->sql_parametro($codrel, '14', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[15] = $orcparamrel->sql_parametro($codrel, '15', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

    // inicio dedu��o em 2007
    $param[16] = $orcparamrel->sql_parametro($codrel, '16', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[17] = $orcparamrel->sql_parametro($codrel, '17', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    $param[18] = $orcparamrel->sql_parametro($codrel, '18', 'f', str_replace('-', ', ', $db_selinstit), $anousu);

    if ($codrel == 27) {
        $param[19] = $orcparamrel->sql_parametro($codrel, '19', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[20] = $orcparamrel->sql_parametro($codrel, '20', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
        $param[21] = $orcparamrel->sql_parametro($codrel, '21', 'f', str_replace('-', ', ', $db_selinstit), $anousu);
    }

    if ($codrel == 59 || $codrel == 81 || $codrel == 166) {

        for ($iLinha = 1; $iLinha <= 22; $iLinha++) {

            $param[$iLinha] = new linhaRelatorioContabil($codrel, $iLinha);
            $param[$iLinha]->parametro = $param[$iLinha]->getParametros($anousu);
        }
    }

    $total = 0;

    $rcl_matriz2['janeiro'] = 0;
    $rcl_matriz2['fevereiro'] = 0;
    $rcl_matriz2['marco'] = 0;
    $rcl_matriz2['abril'] = 0;
    $rcl_matriz2['maio'] = 0;
    $rcl_matriz2['junho'] = 0;
    $rcl_matriz2['julho'] = 0;
    $rcl_matriz2['agosto'] = 0;
    $rcl_matriz2['setembro'] = 0;
    $rcl_matriz2['outubro'] = 0;
    $rcl_matriz2['novembro'] = 0;
    $rcl_matriz2['dezembro'] = 0;

    // Arrecadacao
    $rcl_matriz[0][1] = 0; // Janeiro
    $rcl_matriz[0][2] = 0; // Fevereiro
    $rcl_matriz[0][3] = 0; // Marco
    $rcl_matriz[0][4] = 0; // Abril
    $rcl_matriz[0][5] = 0; // Maio
    $rcl_matriz[0][6] = 0; // Junho
    $rcl_matriz[0][7] = 0; // Julho
    $rcl_matriz[0][8] = 0; // Agosto
    $rcl_matriz[0][9] = 0; // Setembro
    $rcl_matriz[0][10] = 0; // Outubro
    $rcl_matriz[0][11] = 0; // Novembro
    $rcl_matriz[0][12] = 0; // Dezembro

    // Deducoes
    $rcl_matriz[1][1] = 0; // Janeiro
    $rcl_matriz[1][2] = 0; // Fevereiro
    $rcl_matriz[1][3] = 0; // Marco
    $rcl_matriz[1][4] = 0; // Abril
    $rcl_matriz[1][5] = 0; // Maio
    $rcl_matriz[1][6] = 0; // Junho
    $rcl_matriz[1][7] = 0; // Julho
    $rcl_matriz[1][8] = 0; // Agosto
    $rcl_matriz[1][9] = 0; // Setembro
    $rcl_matriz[1][10] = 0; // Outubro
    $rcl_matriz[1][11] = 0; // Novembro
    $rcl_matriz[1][12] = 0; // Dezembro

    $tot_param = 18;
    $ult_param = 15;

    if ($codrel == 27) {
        $tot_param = 21;
        $ult_param = 18;
    } else if ($codrel == 59 || $codrel == 81 || $codrel = 166) {

        $tot_param = 22;
        $ult_param = 19;
    }

    for ($p = 1; $p <= $tot_param; $p++) {
        // 18 para 2007 e 21 para 2008 � a quantidade de parametros ou linhas existentes nos parametros

        for ($i = 0; $i < pg_numrows($result_rec); $i++) {

            $oReceita = db_utils::fieldsmemory($result_rec, $i);
            $estrutural = $oReceita->o57_fonte;
            $oParametro = $param[$p]->parametro;
            foreach ($oParametro->contas as $oEstrutural) {

                $oRetornoVerificacao = $param[$p]->match($oEstrutural, $oParametro->orcamento, $oReceita, 1);
                if ($oRetornoVerificacao->match) {

                    if ($oRetornoVerificacao->exclusao) {

                        $oReceita->janeiro *= -1;
                        $oReceita->fevereiro *= -1;
                        $oReceita->marco *= -1;
                        $oReceita->abril *= -1;
                        $oReceita->maio *= -1;
                        $oReceita->junho *= -1;
                        $oReceita->julho *= -1;
                        $oReceita->agosto *= -1;
                        $oReceita->setembro *= -1;
                        $oReceita->outubro *= -1;
                        $oReceita->novembro *= -1;
                        $oReceita->dezembro *= -1;
                    }
                    if ($p == $tot_param) {

                        $oReceita->janeiro *= -1;
                        $oReceita->fevereiro *= -1;
                        $oReceita->marco *= -1;
                        $oReceita->abril *= -1;
                        $oReceita->maio *= -1;
                        $oReceita->junho *= -1;
                        $oReceita->julho *= -1;
                        $oReceita->agosto *= -1;
                        $oReceita->setembro *= -1;
                        $oReceita->outubro *= -1;
                        $oReceita->novembro *= -1;
                        $oReceita->dezembro *= -1;
                    }
                    if ($p <= $ult_param) {

                        $rcl_matriz[0][1] += $oReceita->janeiro;
                        $rcl_matriz[0][2] += $oReceita->fevereiro;
                        $rcl_matriz[0][3] += $oReceita->marco;
                        $rcl_matriz[0][4] += $oReceita->abril;
                        $rcl_matriz[0][5] += $oReceita->maio;
                        $rcl_matriz[0][6] += $oReceita->junho;
                        $rcl_matriz[0][7] += $oReceita->julho;
                        $rcl_matriz[0][8] += $oReceita->agosto;
                        $rcl_matriz[0][9] += $oReceita->setembro;
                        $rcl_matriz[0][10] += $oReceita->outubro;
                        $rcl_matriz[0][11] += $oReceita->novembro;
                        $rcl_matriz[0][12] += $oReceita->dezembro;
                    } else {

                        if (db_conplano_grupo($anousu, substr($estrutural, 0, 3) . "%", 9001) == true) {

                            $rcl_matriz[1][1] += ($oReceita->janeiro);
                            $rcl_matriz[1][2] += ($oReceita->fevereiro);
                            $rcl_matriz[1][3] += ($oReceita->marco);
                            $rcl_matriz[1][4] += ($oReceita->abril);
                            $rcl_matriz[1][5] += ($oReceita->maio);
                            $rcl_matriz[1][6] += ($oReceita->junho);
                            $rcl_matriz[1][7] += ($oReceita->julho);
                            $rcl_matriz[1][8] += ($oReceita->agosto);
                            $rcl_matriz[1][9] += ($oReceita->setembro);
                            $rcl_matriz[1][10] += ($oReceita->outubro);
                            $rcl_matriz[1][11] += ($oReceita->novembro);
                            $rcl_matriz[1][12] += ($oReceita->dezembro);
                        } else {

                            $rcl_matriz[1][1] += $oReceita->janeiro;
                            $rcl_matriz[1][2] += $oReceita->fevereiro;
                            $rcl_matriz[1][3] += $oReceita->marco;
                            $rcl_matriz[1][4] += $oReceita->abril;
                            $rcl_matriz[1][5] += $oReceita->maio;
                            $rcl_matriz[1][6] += $oReceita->junho;
                            $rcl_matriz[1][7] += $oReceita->julho;
                            $rcl_matriz[1][8] += $oReceita->agosto;
                            $rcl_matriz[1][9] += $oReceita->setembro;
                            $rcl_matriz[1][10] += $oReceita->outubro;
                            $rcl_matriz[1][11] += $oReceita->novembro;
                            $rcl_matriz[1][12] += $oReceita->dezembro;
                        }
                    }
                }
            }
        }
    }

    if ($flag_anterior == false) { // Exercicio Atual
        for ($y = 1; $y <= $bimestre; $y++) {
            $total += $rcl_matriz[0][$y] - ($rcl_matriz[1][$y]);
        }
    } else { // Exercicio Anterior
        for ($y = $bimestre; $y <= 12; $y++) {
            $total += $rcl_matriz[0][$y] - ($rcl_matriz[1][$y]);
        }
    }

    if ($matriz == true) {
        // Arrecadacao - Deducoes
        $rcl_matriz2['janeiro'] = $rcl_matriz[0][1] - ($rcl_matriz[1][1]);
        $rcl_matriz2['fevereiro'] = $rcl_matriz[0][2] - ($rcl_matriz[1][2]);
        $rcl_matriz2['marco'] = $rcl_matriz[0][3] - ($rcl_matriz[1][3]);
        $rcl_matriz2['abril'] = $rcl_matriz[0][4] - ($rcl_matriz[1][4]);
        $rcl_matriz2['maio'] = $rcl_matriz[0][5] - ($rcl_matriz[1][5]);
        $rcl_matriz2['junho'] = $rcl_matriz[0][6] - ($rcl_matriz[1][6]);
        $rcl_matriz2['julho'] = $rcl_matriz[0][7] - ($rcl_matriz[1][7]);
        $rcl_matriz2['agosto'] = $rcl_matriz[0][8] - ($rcl_matriz[1][8]);
        $rcl_matriz2['setembro'] = $rcl_matriz[0][9] - ($rcl_matriz[1][9]);
        $rcl_matriz2['outubro'] = $rcl_matriz[0][10] - ($rcl_matriz[1][10]);
        $rcl_matriz2['novembro'] = $rcl_matriz[0][11] - ($rcl_matriz[1][11]);
        $rcl_matriz2['dezembro'] = $rcl_matriz[0][12] - ($rcl_matriz[1][12]);

        return $rcl_matriz2;
    }

    return $total;
}

//
// Funcao para verificar se um estrutura (ou parte dele) esta num grupo
//

function db_conplano_grupo($anousu = null, $estrut = "", $grupo = 0)
{

    if ($anousu == "" || $anousu == null) {
        $anousu = db_getsession("DB_anousu");
    }

    $sql_result = analiseQueryPlanoOrcamento("select fc_conplano_grupo($anousu, '$estrut', $grupo) as retorno");
    $result = db_query($sql_result);
    $numrows = pg_numrows($result);
    if ($numrows != 0) {
        $retorno = pg_result($result, 0, 0);
        if ($retorno == 't') {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
//funcao para montar a fonte e nota explicativa dos relatorios legais.

function notasExplicativas($oPdf, $iCodRel, $sPeriodo, $iTam, $lFonte = true)
{

    if (!class_exists("cl_orcparamrelnota")) {
        require_once(modification("classes/db_orcparamrelnota_classe.php"));
    }
    if (!class_exists("db_utils")) {
        require_once(modification("libs/db_utils.php"));
    }

    /**
     * Tamanhos das fontes para as Notas Explicativas
     */
    $nTamanhoFonteNota = 6;
    $nTamanhoFonteDados = 8;
    $clorcparamrelnota = new cl_orcparamrelnota();

    $sCampos = "o42_nota, o42_fonte, o42_tamanhofontedados, o42_tamanhofontenota";
    $sSqlNotasExplicativas = $clorcparamrelnota
        ->sql_query($iCodRel, db_getsession("DB_anousu"), db_getsession("DB_instit"), $sPeriodo, $sCampos);
    $rsNotas = $clorcparamrelnota->sql_record($sSqlNotasExplicativas);
    if ($clorcparamrelnota->numrows > 0) {
        $oNotas = db_utils::fieldsMemory($rsNotas, 0);
    }

    /**
     * Seta os tamanhos das fontes setada na tabela orcparamrelnota se ela for maior que zero,
     * Para as Notas Explicativas
     */
    if (isset($oNotas->o42_tamanhofontedados) && $oNotas->o42_tamanhofontedados > 0) {
        $nTamanhoFonteDados = $oNotas->o42_tamanhofontedados;
    }
    if (isset($oNotas->o42_tamanhofontenota) && $oNotas->o42_tamanhofontenota > 0) {
        $nTamanhoFonteNota = $oNotas->o42_tamanhofontenota;
    }

    $oPdf->setfont('arial', '', 8);

    if ($lFonte == true) {

        if (isset($oNotas->o42_fonte) && trim($oNotas->o42_fonte) != "") {

            $oPdf->setfont('arial', '', 8);
            $oPdf->cell($iTam, 3, "Fonte:", 0, 1, "L", 0);
            $oPdf->setfont('arial', '', $nTamanhoFonteNota);
            $oPdf->multicell($iTam, 3, $oNotas->o42_fonte, 0, "J");
        } else {
            $oPdf->cell($iTam, 3, 'Fonte: Contabilidade', "", 1, "L", 0);
        }
    }

    if (isset($oNotas->o42_nota) && trim($oNotas->o42_nota) != "") {

        $oPdf->ln(2);
        $oPdf->setfont('arial', '', 8);
        $oPdf->cell($iTam, 3, "NOTAS EXPLICATIVAS:", 0, 1, "L", 0);
        $oPdf->ln(2);
        $oPdf->setfont('arial', '', $nTamanhoFonteDados);
        $oPdf->multicell($iTam, 3, $oNotas->o42_nota, 0, "J");
    }
    $oPdf->setfont('arial', '', 6);
}

function db_varPatrimoniaisRpps($anousu, $dataini, $datafin, $iInstit)
{

    $aVariacoesAtivo = array();
    $aVariacoesPassivo = array();
    $aVariacoesExtraAtivo = array();
    $aVariacoesExtraPassivo = array();

    (float) $aVariacoesAtivo['ReceitasCorrentes'] = 0;
    (float) $aVariacoesAtivo['ReceitasCapital'] = 0;
    (float) $aVariacoesAtivo['IntraOrcamentarias'] = 0;
    (float) $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'] = 0;
    (float) $aVariacoesAtivo['IncorporacaoAtivos'] = 0;
    (float) $aVariacoesAtivo['DesincorporacaoPassivos'] = 0;
    (float) $aVariacoesAtivo['DeducaoReceita'] = 0;

    (float) $aVariacoesPassivo['DespesasCorrentes'] = 0;
    (float) $aVariacoesPassivo['DespesasCapital'] = 0;
    (float) $aVariacoesPassivo['IntraOrcamentarias'] = 0;
    (float) $aVariacoesPassivo['DesincorporacaoAtivos'] = 0;
    (float) $aVariacoesPassivo['IncorporacaoPassivos'] = 0;

    (float) $aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'] = 0;
    (float) $aVariacoesExtraAtivo['MovimentoFundosDebito'] = 0;
    (float) $aVariacoesExtraAtivo['IncorporacaoAtivos'] = 0;
    (float) $aVariacoesExtraAtivo['DesincorporacaoPassivos'] = 0;
    (float) $aVariacoesExtraAtivo['AjustesBensValoresCreditos'] = 0;
    (float) $aVariacoesExtraAtivo['AjustesExerciciosAnteriores'] = 0;

    (float) $aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'] = 0;
    (float) $aVariacoesExtraPassivo['MovimentoFundosCredito'] = 0;
    (float) $aVariacoesExtraPassivo['DesincorporacaoAtivos'] = 0;
    (float) $aVariacoesExtraPassivo['AjustesBensValoresCreditos'] = 0;
    (float) $aVariacoesExtraPassivo['IncorporacaoPassivos'] = 0;

    //
    // Balancete de Receita (db_receitasaldo)
    //
    $sSqlFiltro = ' o70_instit = ' . $iInstit;
    $rsReceitaSaldo = db_receitasaldo(3, 1, 3, true, $sSqlFiltro, $anousu, $dataini, $datafin);
    $iNumrowsReceita = pg_num_rows($rsReceitaSaldo);
    //   db_criatabela($rsReceitaSaldo);exit;

    for ($i = 0; $i < $iNumrowsReceita; $i++) {

        $oReceitaSaldo = db_utils::fieldsMemory($rsReceitaSaldo, $i);

        switch (substr($oReceitaSaldo->o57_fonte, 0, 4)) {
            case '4100':
                $aVariacoesAtivo['ReceitasCorrentes'] += (float) $oReceitaSaldo->saldo_arrecadado_acumulado;
                break;
            case '4200':
                $aVariacoesAtivo['ReceitasCapital'] += (float) $oReceitaSaldo->saldo_arrecadado_acumulado;
                break;
            case '4700':
                $aVariacoesAtivo['IntraOrcamentarias'] += (float) $oReceitaSaldo->saldo_arrecadado_acumulado;
                break;
            case '4800':
                $aVariacoesAtivo['IntraOrcamentarias'] += (float) $oReceitaSaldo->saldo_arrecadado_acumulado;
                break;
            case '9000':
                $aVariacoesAtivo['DeducaoReceita'] += (float) $oReceitaSaldo->saldo_arrecadado_acumulado;
                break;
        }
    }

    //
    // Balancete de Despesa (db_dotacaosaldo)
    //
    $sCondicaoDotacao = 'w.o58_instit = ' . $iInstit;
    $rsDotacaoSaldo = db_dotacaosaldo(7, 3, 4, true, $sCondicaoDotacao, $anousu, $dataini, $datafin);
    $iNumRowsDotacaoSaldo = pg_num_rows($rsDotacaoSaldo);
    //db_criatabela($rsDotacaoSaldo); exit;

    for ($i = 0; $i < $iNumRowsDotacaoSaldo; $i++) {
        $oDotacaoSaldo = db_utils::fieldsMemory($rsDotacaoSaldo, $i);

        if (substr($oDotacaoSaldo->o58_elemento, 0, 2) == '33' && substr($oDotacaoSaldo->o58_elemento, 3, 2) != '91') {
            $aVariacoesPassivo['DespesasCorrentes'] += (float) $oDotacaoSaldo->liquidado_acumulado;
        }
        if (substr($oDotacaoSaldo->o58_elemento, 0, 2) == '34' && substr($oDotacaoSaldo->o58_elemento, 3, 2) != '91') {
            $aVariacoesPassivo['DespesasCapital'] += (float) $oDotacaoSaldo->liquidado_acumulado;
        }
        if ((substr($oDotacaoSaldo->o58_elemento, 0, 2) == '33' || substr($oDotacaoSaldo->o58_elemento, 0, 2) == '34')
            && substr($oDotacaoSaldo->o58_elemento, 3, 2) == '91'
        ) {
            $aVariacoesPassivo['IntraOrcamentarias'] += (float) $oDotacaoSaldo->liquidado_acumulado;
        }
    }

    //
    // Balancete de verificacao (db_planocontassaldo_matriz)
    //
    $sCondicaoConta = ' c61_instit = ' . $iInstit;
    $rsContaSaldo = db_planocontassaldo_matriz($anousu, $dataini, $datafin, false, $sCondicaoConta);
    $iNumRowsContaSaldo = pg_num_rows($rsContaSaldo);
    //db_criatabela($rsContaSaldo); exit;

    for ($i = 0; $i < $iNumRowsContaSaldo; $i++) {

        $oContaSaldo = db_utils::fieldsMemory($rsContaSaldo, $i);

        if (substr($oContaSaldo->estrutural, 0, 15) == '612000000000000') {
            $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'] += (float) $oContaSaldo->saldo_final;
        }

        switch (substr($oContaSaldo->estrutural, 0, 15)) {
            case '613100000000000':
                $aVariacoesAtivo['IncorporacaoAtivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '613300000000000':
                $aVariacoesAtivo['DesincorporacaoPassivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '622200000000000':
                $aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'] += (float) $oContaSaldo->saldo_final;
                break;
            case '622300000000000':
                $aVariacoesExtraAtivo['MovimentoFundosDebito'] += (float) $oContaSaldo->saldo_final;
                break;
            case '623100000000000':
                $aVariacoesExtraAtivo['IncorporacaoAtivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '623200000000000':
                $aVariacoesExtraAtivo['AjustesBensValoresCreditos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '623300000000000':
                $aVariacoesExtraAtivo['DesincorporacaoPassivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '623800000000000':
                $aVariacoesExtraAtivo['AjustesExerciciosAnteriores'] += (float) $oContaSaldo->saldo_final;
                break;
            case '513100000000000':
                $aVariacoesPassivo['DesincorporacaoAtivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '513300000000000':
                $aVariacoesPassivo['IncorporacaoPassivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '522200000000000':
                $aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'] += (float) $oContaSaldo->saldo_final;
                break;
            case '522300000000000':
                $aVariacoesExtraPassivo['MovimentoFundosCredito'] += (float) $oContaSaldo->saldo_final;
                break;
            case '523100000000000':
                $aVariacoesExtraPassivo['DesincorporacaoAtivos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '523200000000000':
                $aVariacoesExtraPassivo['AjustesBensValoresCreditos'] += (float) $oContaSaldo->saldo_final;
                break;
            case '523300000000000':
                $aVariacoesExtraPassivo['IncorporacaoPassivos'] += (float) $oContaSaldo->saldo_final;
                break;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------------//

    //
    // Totalizadores
    //

    $aRetorno = array();

    // Receitas
    $aRetorno['Ativo']['ReceitasCorrentes'] = $aVariacoesAtivo['ReceitasCorrentes'];
    $aRetorno['Ativo']['ReceitasCapital'] = $aVariacoesAtivo['ReceitasCapital'];
    $aRetorno['Ativo']['IntraOrcamentarias'] = $aVariacoesAtivo['IntraOrcamentarias'];
    $aRetorno['Ativo']['DeducaoReceita'] = $aVariacoesAtivo['DeducaoReceita'];
    $aRetorno['Ativo']['Receitas'] = ($aVariacoesAtivo['ReceitasCorrentes'] + $aVariacoesAtivo['ReceitasCapital']
        + $aVariacoesAtivo['IntraOrcamentarias'] + $aVariacoesAtivo['DeducaoReceita']);

    // Interferencias Ativas
    $aRetorno['Ativo']['TransferenciasFinanceirasRecebidas'] = $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'];
    $aRetorno['Ativo']['InterferenciasAtivas'] = $aVariacoesAtivo['TransferenciasFinanceirasRecebidas'];

    // Mutacoes Ativas
    $aRetorno['Ativo']['IncorporacaoAtivos'] = $aVariacoesAtivo['IncorporacaoAtivos'];
    $aRetorno['Ativo']['DesincorporacaoPassivos'] = $aVariacoesAtivo['DesincorporacaoPassivos'];
    $aRetorno['Ativo']['MutacoesAtivas'] = ($aVariacoesAtivo['IncorporacaoAtivos']
        + $aVariacoesAtivo['DesincorporacaoPassivos']);

    //Total Orcamentaria Ativa
    $aRetorno['Ativo']['TotalOrcamentariaAtiva'] = ($aRetorno['Ativo']['Receitas']
        + $aRetorno['Ativo']['InterferenciasAtivas'] + $aRetorno['Ativo']['MutacoesAtivas']);

    $aRetorno['Passivo']['DespesasCorrentes'] = $aVariacoesPassivo['DespesasCorrentes'];
    $aRetorno['Passivo']['DespesasCapital'] = $aVariacoesPassivo['DespesasCapital'];
    $aRetorno['Passivo']['IntraOrcamentarias'] = $aVariacoesPassivo['IntraOrcamentarias'];
    $aRetorno['Passivo']['Despesas'] = ($aVariacoesPassivo['DespesasCorrentes']
        + $aVariacoesPassivo['DespesasCapital'] + $aVariacoesPassivo['IntraOrcamentarias']);

    $aRetorno['Passivo']['DesincorporacaoAtivos'] = $aVariacoesPassivo['DesincorporacaoAtivos'];
    $aRetorno['Passivo']['IncorporacaoPassivos'] = $aVariacoesPassivo['IncorporacaoPassivos'];
    $aRetorno['Passivo']['MutacoesPassivas'] = ($aVariacoesPassivo['DesincorporacaoAtivos']
        + $aVariacoesPassivo['IncorporacaoPassivos']);

    // Total Orcamentaria Passiva
    $aRetorno['Passivo']['TotalOrcamentariaPassiva'] = ($aRetorno['Passivo']['Despesas']
        + $aRetorno['Passivo']['MutacoesPassivas']);

    //
    //Extra-Orcamentario
    //

    // Interferencias Ativas
    $aRetorno['AtivoExtra']['TransferenciasFinanceirasRecebidas'] = $aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas'];
    $aRetorno['AtivoExtra']['MovimentoFundosDebito'] = $aVariacoesExtraAtivo['MovimentoFundosDebito'];
    $aRetorno['AtivoExtra']['InterferenciasAtivas'] = ($aVariacoesExtraAtivo['TransferenciasFinanceirasRecebidas']
        + $aVariacoesExtraAtivo['MovimentoFundosDebito']);
    // Acrescimos Patrimoniais
    $aRetorno['AtivoExtra']['IncorporacaoAtivos'] = $aVariacoesExtraAtivo['IncorporacaoAtivos'];
    $aRetorno['AtivoExtra']['AjustesBensValoresCreditos'] = $aVariacoesExtraAtivo['AjustesBensValoresCreditos'];
    $aRetorno['AtivoExtra']['DesincorporacaoPassivos'] = $aVariacoesExtraAtivo['DesincorporacaoPassivos'];
    $aRetorno['AtivoExtra']['AjustesExerciciosAnteriores'] = $aVariacoesExtraAtivo['AjustesExerciciosAnteriores'];
    $aRetorno['AtivoExtra']['AcrescimosPatrimoniais'] = ($aVariacoesExtraAtivo['IncorporacaoAtivos']
        + $aVariacoesExtraAtivo['AjustesBensValoresCreditos'] + $aVariacoesExtraAtivo['DesincorporacaoPassivos']);
    // Total Ativo Extra-Orcamentario
    $aRetorno['AtivoExtra']['TotalAtivoExtra'] = ($aRetorno['AtivoExtra']['InterferenciasAtivas']
        + $aRetorno['AtivoExtra']['AcrescimosPatrimoniais']);

    // Interferencias Passivas
    $aRetorno['PassivoExtra']['TransferenciasFinanceirasConcedidas'] = $aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas'];
    $aRetorno['PassivoExtra']['MovimentoFundosCredito'] = $aVariacoesExtraPassivo['MovimentoFundosCredito'];
    $aRetorno['PassivoExtra']['InterferenciasPassivas'] = ($aVariacoesExtraPassivo['TransferenciasFinanceirasConcedidas']
        + $aVariacoesExtraPassivo['MovimentoFundosCredito']);
    //Descrescimos Patrimoniais
    $aRetorno['PassivoExtra']['DesincorporacaoAtivos'] = $aVariacoesExtraPassivo['DesincorporacaoAtivos'];
    $aRetorno['PassivoExtra']['AjustesBensValoresCreditos'] = $aVariacoesExtraPassivo['AjustesBensValoresCreditos'];
    $aRetorno['PassivoExtra']['IncorporacaoPassivos'] = $aVariacoesExtraPassivo['IncorporacaoPassivos'];
    $aRetorno['PassivoExtra']['DecrescimosPatrimoniais'] = ($aVariacoesExtraPassivo['DesincorporacaoAtivos']
        + $aVariacoesExtraPassivo['AjustesBensValoresCreditos'] + $aVariacoesExtraPassivo['IncorporacaoPassivos']);
    // Total Passivo Extra-Orcamentario
    $aRetorno['PassivoExtra']['TotalPassivoExtra'] = ($aRetorno['PassivoExtra']['InterferenciasPassivas']
        + $aRetorno['PassivoExtra']['DecrescimosPatrimoniais']);

    $aRetorno['TotaisAtivo']['Soma'] = ($aRetorno['Ativo']['TotalOrcamentariaAtiva']
        + $aRetorno['AtivoExtra']['TotalAtivoExtra']);
    $aRetorno['TotaisPassivo']['Soma'] = ($aRetorno['Passivo']['TotalOrcamentariaPassiva']
        + $aRetorno['PassivoExtra']['TotalPassivoExtra']);

    $nSomaAtivo = $aRetorno['TotaisAtivo']['Soma'];
    $nSomaPassivo = $aRetorno['TotaisPassivo']['Soma'];

    if (($nSomaAtivo - $nSomaPassivo) < 0) {
        $nDeficitPatrimonial = abs($nSomaAtivo - $nSomaPassivo);
        (float) $nTotalAtivo = ($nSomaAtivo + abs($nSomaAtivo - $nSomaPassivo));
    } else {
        $nDeficitPatrimonial = '-';
        (float) $nTotalAtivo = ($nSomaAtivo);
    }

    if (($nSomaAtivo - $nSomaPassivo) > 0) {
        $nSuperavitPatrimonial = ($nSomaAtivo - $nSomaPassivo);
        (float) $nTotalPassivo = ($nSomaPassivo + abs($nSomaAtivo - $nSomaPassivo));
    } else {
        $nSuperavitPatrimonial = '-';
        (float) $nTotalPassivo = ($nSomaPassivo);
    }

    $aRetorno['TotaisAtivo']['TotalAtivo'] = $nTotalAtivo;
    $aRetorno['TotaisAtivo']['DeficitPatrimonial'] = $nDeficitPatrimonial;

    $aRetorno['TotaisPassivo']['TotalPassivo'] = $nTotalPassivo;
    $aRetorno['TotaisPassivo']['SuperavitPatrimonial'] = $nSuperavitPatrimonial;

    return $aRetorno;
}

function duplicaReceitaaCorrenteLiquida($iAnoUsu, $iCodigoRelatorio)
{

    $iExercAnt = $iAnoUsu - 1;
    $iExercicio = $iAnoUsu;
    $clorcparamelemento = db_utils::getDao("orcparamelemento");
    $clorcparamelemento->o44_anousu = $iExercAnt;
    $clorcparamelemento->o44_codparrel = $iCodigoRelatorio;
    $clorcparamelemento->excluir($iExercAnt, $iCodigoRelatorio);

    // Inclui elemento no exerc�cio anterior com base no atual;
    $sSqlWhere = " o44_codparrel = {$iCodigoRelatorio} ";
    $sSqlDuplicaEle = " select fc_duplica_exercicio('orcparamelemento', 'o44_anousu', " . db_getsession('DB_anousu')
        . ",{$iExercAnt},'{$sSqlWhere}');";
    $rsDuplicaEle = db_query($sSqlDuplicaEle);

    /**
     * Duplica a as configura��es padroes do relatorio e as configura��es do usuario.
     */
    $oDaoFiltroPadrao = db_utils::getDao("orcparamseqfiltropadrao");
    $sSqlApagaPadrao = $oDaoFiltroPadrao
        ->excluir(
            null,
            "o132_anousu = {$iExercAnt}
                                                  and o132_orcparamrel = {$iCodigoRelatorio}"
        );
    $sSqlinclui = $oDaoFiltroPadrao
        ->sql_query_file(
            null,
            "*",
            null,
            "o132_anousu = {$iExercicio}
                                                        and o132_orcparamrel = {$iCodigoRelatorio}"
        );

    $rsIncluirPadraoAnterior = $oDaoFiltroPadrao->sql_record($sSqlinclui);
    $aFiltrosPadraoIncluir = db_utils::getCollectionByRecord($rsIncluirPadraoAnterior);
    foreach ($aFiltrosPadraoIncluir as $oFiltroPadrao) {

        $oDaoFiltroPadrao->o132_filtro = $oFiltroPadrao->o132_filtro;
        $oDaoFiltroPadrao->o132_orcparamrel = $oFiltroPadrao->o132_orcparamrel;
        $oDaoFiltroPadrao->o132_orcparamseq = $oFiltroPadrao->o132_orcparamseq;
        $oDaoFiltroPadrao->o132_anousu = $iExercAnt;
        $oDaoFiltroPadrao->incluir(null);
    }

    $oDaoFiltroUsuario = db_utils::getDao("orcparamseqfiltroorcamento");
    $sSqlApagaOrcamento = $oDaoFiltroUsuario
        ->excluir(
            null,
            "o133_anousu = {$iExercAnt}
                                                  and o133_orcparamrel = {$iCodigoRelatorio}"
        );
    $sSqlinclui = $oDaoFiltroUsuario
        ->sql_query_file(
            null,
            "*",
            null,
            "o133_anousu = {$iExercicio}
                                                        and o133_orcparamrel = {$iCodigoRelatorio}"
        );
    $rsIncluirUsuarioAnterior = $oDaoFiltroUsuario->sql_record($sSqlinclui);
    $aFiltrosUsuarioIncluir = db_utils::getCollectionByRecord($rsIncluirUsuarioAnterior);

    foreach ($aFiltrosUsuarioIncluir as $oFiltroUsuario) {

        $oDaoFiltroUsuario->o133_filtro = $oFiltroUsuario->o133_filtro;
        $oDaoFiltroUsuario->o133_orcparamrel = $oFiltroUsuario->o133_orcparamrel;
        $oDaoFiltroUsuario->o133_orcparamseq = $oFiltroUsuario->o133_orcparamseq;
        $oDaoFiltroUsuario->o133_anousu = $iExercAnt;
        $oDaoFiltroUsuario->incluir(null);
    }
}

/**
 * Busca saldo conta contarrente
 *
 */
function getSaldoTotalContaCorrente($iAnousu, $iReduz, $iCC, $iMes, $iInstit, $sDataInicial=null,$sDataFinal=null)
{
    $sSaldoInicialAno = "select coalesce((SELECT sum(CASE WHEN c29_debito > 0 THEN c29_debito
                               WHEN c29_credito > 0 THEN -1 * c29_credito
                               ELSE 0 END) AS saldoinicialano
                   FROM contacorrente
                          INNER JOIN contacorrentedetalhe ON contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente
                          INNER JOIN contacorrentesaldo ON contacorrentesaldo.c29_contacorrentedetalhe = contacorrentedetalhe.c19_sequencial
                     AND contacorrentesaldo.c29_mesusu = 0
                     AND contacorrentesaldo.c29_anousu = $iAnousu
                   WHERE contacorrentedetalhe.c19_reduz = $iReduz
                     AND contacorrentedetalhe.c19_instit = $iInstit
                     AND contacorrentedetalhe.c19_conplanoreduzanousu = $iAnousu),0) as saldoinicialano";

    $rsInicAno = db_query($sSaldoInicialAno);

    $nSaldoInicialAno = db_utils::fieldsMemory($rsInicAno, 0)->saldoinicialano;

    $sTotalCreditoAno = "select coalesce((SELECT sum(c69_valor)
                   FROM conlancamval
                          INNER JOIN conlancam ON conlancam.c70_codlan = conlancamval.c69_codlan
                     AND conlancam.c70_anousu = conlancamval.c69_anousu
                          INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamval.c69_codlan
                          INNER JOIN conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                          INNER JOIN contacorrentedetalheconlancamval ON contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen
                          INNER JOIN contacorrentedetalhe ON contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe
                   WHERE c28_tipo = 'C'";
    if ($iMes == null && $sDataInicial != null && $sDataFinal != null) {
        $sTotalCreditoAno .= " AND c69_data < '{$sDataInicial}'";
    } else {
        $sTotalCreditoAno .= " AND DATE_PART('MONTH',c69_data) < {$iMes}";
    }
    $sTotalCreditoAno .= " AND DATE_PART('YEAR',c69_data) = $iAnousu
                     AND contacorrentedetalhe.c19_reduz = $iReduz
                     AND c19_instit = $iInstit
                   GROUP BY c28_tipo),0) as creditoano ";

    $nTotalCreditoAno = db_utils::fieldsMemory(db_query($sTotalCreditoAno), 0)->creditoano;

    $sTotalDebitoAno = "select coalesce((SELECT sum(c69_valor) AS debito
                   FROM conlancamval
                          INNER JOIN conlancam ON conlancam.c70_codlan = conlancamval.c69_codlan
                     AND conlancam.c70_anousu = conlancamval.c69_anousu
                          INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamval.c69_codlan
                          INNER JOIN conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                          INNER JOIN contacorrentedetalheconlancamval ON contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen
                          INNER JOIN contacorrentedetalhe ON contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe
                   WHERE c28_tipo = 'D'";
    if ($iMes == null && $sDataInicial != null && $sDataFinal != null) {
        $sTotalDebitoAno .= " AND c69_data < '{$sDataInicial}'";
    } else {
        $sTotalDebitoAno .= " AND DATE_PART('MONTH',c69_data) < {$iMes}";
    }
    $sTotalDebitoAno .= " AND DATE_PART('YEAR',c69_data) = $iAnousu
                     AND contacorrentedetalhe.c19_reduz = $iReduz
                     AND c19_instit = $iInstit
                   GROUP BY c28_tipo),0) as debito ";

    $nTotalDebitoAno = db_utils::fieldsMemory(db_query($sTotalDebitoAno), 0)->debito;

    $nSaldoInicialMes = $nSaldoInicialAno + $nTotalDebitoAno - $nTotalCreditoAno;

    $sTotalCreditoMes = "select coalesce((SELECT sum(c69_valor) as creditomes
                   FROM conlancamval
                          INNER JOIN conlancam ON conlancam.c70_codlan = conlancamval.c69_codlan
                     AND conlancam.c70_anousu = conlancamval.c69_anousu
                          INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamval.c69_codlan
                          INNER JOIN conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                          INNER JOIN contacorrentedetalheconlancamval ON contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen
                          INNER JOIN contacorrentedetalhe ON contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe
                   WHERE c28_tipo = 'C'";
    if ($iMes == null && $sDataInicial != null && $sDataFinal != null) {
        $sTotalCreditoMes .= " AND c69_data BETWEEN '{$sDataInicial}' AND '{$sDataFinal}'";
    } else {
        $sTotalCreditoMes .= " AND DATE_PART('MONTH',c69_data) = {$iMes}";
    }
    $sTotalCreditoMes .= " AND DATE_PART('YEAR',c69_data) = $iAnousu
                     AND contacorrentedetalhe.c19_reduz = $iReduz
                     AND c19_instit = $iInstit
                   GROUP BY c28_tipo),0) as creditomes";

    $nTotalCreditoMes = db_utils::fieldsMemory(db_query($sTotalCreditoMes), 0)->creditomes;

    $sTotalDebitosMes = "select coalesce((SELECT sum(c69_valor)
                   FROM conlancamval
                          INNER JOIN conlancam ON conlancam.c70_codlan = conlancamval.c69_codlan
                     AND conlancam.c70_anousu = conlancamval.c69_anousu
                          INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamval.c69_codlan
                          INNER JOIN conhistdoc ON conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                          INNER JOIN contacorrentedetalheconlancamval ON contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen
                          INNER JOIN contacorrentedetalhe ON contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe
                   WHERE c28_tipo = 'D'";
    if ($iMes == null && $sDataInicial != null && $sDataFinal != null) {
        $sTotalDebitosMes .= " AND c69_data BETWEEN '{$sDataInicial}' AND '{$sDataFinal}'";
    } else {
        $sTotalDebitosMes .= " AND DATE_PART('MONTH',c69_data) = {$iMes}";
    }
    $sTotalDebitosMes .= " AND DATE_PART('YEAR',c69_data) = $iAnousu
                     AND contacorrentedetalhe.c19_reduz = $iReduz
                     AND c19_instit = $iInstit
                   GROUP BY c28_tipo),0) as debitomes";

    $nTotalDebitoMes = db_utils::fieldsMemory(db_query($sTotalDebitosMes), 0)->debitomes;

    $nSaldoFinalMes = $nSaldoInicialMes + $nTotalDebitoMes - $nTotalCreditoMes;

    $sinal_ant = $nSaldoInicialMes < 0 ? 'C' : 'D';
    $sinal_final = $nSaldoFinalMes < 0 ? 'C' : 'D';

    $oSaldo                   = new stdClass;
    $oSaldo->cc               = $iCC;
    $oSaldo->sinal_ant        = $sinal_ant;
    $oSaldo->nSaldoInicialMes = db_formatar(abs($nSaldoInicialMes), "f");
    $oSaldo->debito           = db_formatar($nTotalDebitoMes, "f");
    $oSaldo->credito          = db_formatar($nTotalCreditoMes, "f");
    $oSaldo->saldo_final      = db_formatar(abs($nSaldoFinalMes), "f");
    $oSaldo->sinal_final      = $sinal_final;

    return $oSaldo;
}
/**
 * Busca o saldo da despesa
 * @param null $o58_elemento
 * @param string $campos
 * @param null $ordem
 * @param string $dbwhere
 * @return array|stdClass[]
 */
function getSaldoDespesa($o58_elemento = null, $campos = "*", $ordem = null, $dbwhere = "")
{
    $sql = "select ";
    if ($campos != "*") {
        $campos_sql = split("#", $campos);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
    } else {
        $sql .= $campos;
    }
    $sql .= " from work_dotacao ";
    $sql .= " inner join orcelemento on o58_codele = o56_codele and o58_anousu = o56_anousu ";
    $sql2 = "";
    if ($dbwhere == "") {
        if ($o58_elemento != null) {
            $sql2 .= " where work_dotacao.o58_elemento = '{$o58_elemento}' ";
        }
    } else if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
        $sql .= " order by ";
        $campos_sql = split("#", $ordem);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
    }
    return db_utils::getColectionByRecord(db_query($sql));
}


/**
 * Busca o saldo da despesa, jutamente com tabela empenho para verificar data de julgamento da senten�a
 * @param null $o58_elemento
 * @param string $campos
 * @param null $ordem
 * @param string $dbwhere
 * @return array|stdClass[]
 */
function getSaldoDespesaSentenca($o58_elemento = null, $campos = "*", $ordem = null, $dbwhere = "")
{
    $sql = "select ";
    if ($campos != "*") {
        $campos_sql = split("#", $campos);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
    } else {
        $sql .= $campos;
    }
    $sql .= " from work_dotacao ";
    $sql .= " inner join orcelemento  on o58_codele = o56_codele and o58_anousu = o56_anousu ";
    $sql .= " inner join empempenho   on o58_coddot = e60_coddot and o58_anousu = e60_anousu ";
    $sql .= " inner join conlancamemp on e60_numemp = c75_numemp ";
    $sql .= " inner join conlancam    on c75_codlan = c70_codlan ";
    $sql .= " inner join conlancamdoc on c71_codlan = c70_codlan ";
    $sql .= " inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sql2 = "";
    if ($dbwhere == "") {
        if ($o58_elemento != null) {
            $sql2 .= " where work_dotacao.o58_elemento = '{$o58_elemento}' ";
        }
    } else if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
        $sql .= " order by ";
        $campos_sql = split("#", $ordem);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
    }
    // echo $sql.'<br>';
    return db_utils::getColectionByRecord(db_query($sql));
}

/**
 * Busca valor liquidado pelo desdobramento, passando elemento em n�vel anal�tico
 * @param $where
 * @param $aAnousu
 * @param $instit
 * @param $dtIni
 * @param $dtFim
 * @param string|bool $fonte
 * @param $group
 * @return array|stdClass[]
 */

function getSaldoDesdobramento($where, $aAnousu, $instit, $dtIni, $dtFim, $fonte = "", $group)
{
    $aDatas = array();
    $dt_inicial = "";
    $dt_final   = "";

    if (count($aAnousu) == 2) {
        $aDatas[$aAnousu[0]] = $dtIni . 'a' . $aAnousu[0] . '-12-31';
        $aDatas[$aAnousu[1]] = $aAnousu[1] . '-01-01' . 'a' . $dtFim;
    }

    $sql = " SELECT 
                COALESCE(SUM(CASE
                    WHEN c53_tipo = 20 THEN ROUND(c70_valor, 2)
                    WHEN c53_tipo = 21 THEN ROUND(c70_valor * -(1::FLOAT8),2)
                    ELSE 0::FLOAT8
                    END),0) AS liquidado,
                    COALESCE(SUM(CASE
                            WHEN c53_tipo = 10 THEN ROUND(c70_valor, 2)
                            WHEN c53_tipo = 11 THEN ROUND(c70_valor * -(1::FLOAT8),2)
                            ELSE 0::FLOAT8
                        END),0) AS empenhado
                FROM (SELECT DISTINCT ON (c70_codlan)
                        c53_tipo,
                        c70_valor
                    FROM conlancamele
                        INNER JOIN conlancam ON c70_codlan = c67_codlan
                        INNER JOIN conlancamdoc ON c70_codlan = c71_codlan
                        INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                        INNER JOIN conplanoorcamentoanalitica ON c61_codcon = c67_codele AND c61_anousu = c70_anousu
                        INNER JOIN conplanoorcamento ON c61_codcon = c60_codcon AND c61_anousu = c60_anousu
                        INNER JOIN conlancamemp ON c70_codlan = c75_codlan
                        INNER JOIN empempenho ON e60_numemp = c75_numemp ";

    if ($fonte != "") {
        $sql .= " INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu ";
    }

    $sql .= " WHERE {$where} ";
    $sql .= " AND c70_anousu IN (" . implode(",", $aAnousu) . ")";
    $sql .= " AND e60_instit = {$instit}";
    $sql .= " AND c61_instit = {$instit}";
    $sql .= " AND c53_tipo IN (10, 11, 20, 21)";
    $sql .= " AND (";
    $i = 1;
    foreach ($aAnousu as $anousu) {

        if (count($aAnousu) == 2) {

            $dt_inicial = explode("a", $aDatas[$anousu]);
            $dt_final = explode("a", $aDatas[$anousu]);
            $sql .= "(c70_data BETWEEN '{$dt_inicial[0]}' AND '{$dt_final[1]}') ";
            if ($i < count($aAnousu)) {
                $sql .= " OR ";
            }
            $i++;
        } else {

            $sql .= "(c70_data BETWEEN '{$dtIni}' AND '{$dtFim}') ";
        }
    }

    $sql .= ")";
    if ($fonte != "" && !is_bool($fonte)) {
        $sql .= " AND o58_codigo IN ({$fonte}) ";
    }
    $sql .= " {$group} ) AS x";

    return db_utils::getColectionByRecord(db_query($sql));
}

/**
 * Busca valor arrecado decorrente de emenda parlamentar (k81_emparlamentar in (1,2))
 * @param $dtIni
 * @param $dtFim
 * @param $instit
 * @param $emenda
 * @return array|stdClass[]
 */
function getSaldoArrecadadoEmendaParlamentar($dtIni, $dtFim, $emenda = NULL)
{
    $sql = "SELECT SUM(
                        CASE
                            WHEN ( C53_TIPO = 100 AND K81_EMPARLAMENTAR IN (1,2) ) THEN ROUND(C70_VALOR,2)::FLOAT8
                            WHEN ( C53_TIPO = 101 AND K81_EMPARLAMENTAR IN (1,2) ) THEN ROUND(C70_VALOR*-1,2)::FLOAT8
                        ELSE 0::FLOAT8 END) AS ARRECADADO_EMENDA_PARLAMENTAR
                FROM CONLANCAMREC
                    INNER JOIN CONLANCAM ON C74_CODLAN = C70_CODLAN
                    INNER JOIN CONLANCAMDOC ON C71_CODLAN = C74_CODLAN
                    INNER JOIN CONHISTDOC ON C53_CODDOC = C71_CODDOC
                    INNER JOIN CONLANCAMCORRENTE ON C86_CONLANCAM = C74_CODLAN
                    INNER JOIN CORRENTE ON (C86_ID, C86_DATA, C86_AUTENT) = (CORRENTE.K12_ID, CORRENTE.K12_DATA, CORRENTE.K12_AUTENT)
                    INNER JOIN CORPLACAIXA ON (CORRENTE.K12_ID, CORRENTE.K12_DATA, CORRENTE.K12_AUTENT) = (K82_ID, K82_DATA, K82_AUTENT)
                    INNER JOIN PLACAIXAREC ON K82_SEQPLA = K81_SEQPLA
                    INNER JOIN ORCRECEITA ON (O70_ANOUSU, O70_CODREC) = (C74_ANOUSU, C74_CODREC)
                    INNER JOIN ORCFONTES ON (O70_CODFON, O70_ANOUSU) = (O57_CODFON, O57_ANOUSU)
                WHERE C74_DATA BETWEEN '{$dtIni}' AND '{$dtFim}'";

    if ($emenda)
        $sql .= " AND k81_emparlamentar IN (" . implode(",", $emenda) . ") AND O57_FONTE LIKE '4171%' ";
    else
        $sql .= " AND O57_FONTE LIKE '4171%' ";
    return db_utils::getColectionByRecord(db_query($sql));
}

/**
 * Busca valor arrecado ou fontes decorrente de emenda parlamentar (k81_emparlamentar in (1,2))
 * @param $dtIni
 * @param $dtFim
 * @param $instit
 * @param $emenda
 * @param $fontes
 * @param $execoes
 * @return array|stdClass[]
 */
function getSaldoArrecadadoEmendaParlamentarRelatorioReceita($dtIni, $dtFim, $emenda = NULL, $fontes = false, $execoes = NULL)
{
    $sql = "SELECT ";
    if ($fontes) {
        $sql .= " O57_FONTE ";
    } else {
        $sql .= " SUM(
            CASE
                WHEN ( C53_TIPO = 100 AND K81_EMPARLAMENTAR IN (1,2) ) THEN ROUND(C70_VALOR,2)::FLOAT8
                WHEN ( C53_TIPO = 101 AND K81_EMPARLAMENTAR IN (1,2) ) THEN ROUND(C70_VALOR*-1,2)::FLOAT8
            ELSE 0::FLOAT8 END) AS ARRECADADO_EMENDA_PARLAMENTAR ";
    }
    $sql .= " FROM CONLANCAMREC
                    INNER JOIN CONLANCAM ON C74_CODLAN = C70_CODLAN
                    INNER JOIN CONLANCAMDOC ON C71_CODLAN = C74_CODLAN
                    INNER JOIN CONHISTDOC ON C53_CODDOC = C71_CODDOC
                    INNER JOIN CONLANCAMCORRENTE ON C86_CONLANCAM = C74_CODLAN
                    INNER JOIN CORRENTE ON (C86_ID, C86_DATA, C86_AUTENT) = (CORRENTE.K12_ID, CORRENTE.K12_DATA, CORRENTE.K12_AUTENT)
                    INNER JOIN CORPLACAIXA ON (CORRENTE.K12_ID, CORRENTE.K12_DATA, CORRENTE.K12_AUTENT) = (K82_ID, K82_DATA, K82_AUTENT)
                    INNER JOIN PLACAIXAREC ON K82_SEQPLA = K81_SEQPLA
                    INNER JOIN ORCRECEITA ON (O70_ANOUSU, O70_CODREC) = (C74_ANOUSU, C74_CODREC)
                    INNER JOIN ORCFONTES ON (O70_CODFON, O70_ANOUSU) = (O57_CODFON, O57_ANOUSU)
                WHERE C74_DATA BETWEEN '{$dtIni}' AND '{$dtFim}'";

    if ($emenda) {
        $sql .= " AND k81_emparlamentar IN (" . implode(",", $emenda) . ") AND O57_FONTE LIKE '4171%'";
    } else {
        $sql .= " AND O57_FONTE LIKE '4171%' ";
    }
    if ($execoes) {
        $sql .= " AND o70_codigo NOT IN (" . implode(",", $execoes) . ") ";
    }
    return pg_fetch_array(db_query($sql));
}

function getDespesaExercAnterior($dtIni, $instit, $sElemento)
{

    $sql = "SELECT
                    o58_elemento,
                    o56_descr,
                    SUM (CASE
                        WHEN e50_compdesp IS NOT NULL THEN liquidado_compdesp
                        ELSE liquidado
                    END) AS liquidado,
                    SUM (CASE
                        WHEN e50_compdesp IS NOT NULL THEN empenhado_compdesp
                        ELSE empenhado
                    END) AS empenhado
                    FROM
                    (SELECT
                            o58_elemento,
                          o56_descr,
                          liquidado,
                          empenhado,
                          e50_compdesp,
                          (SELECT SUM(
                              CASE
                                  WHEN C53_TIPO = 20 THEN ROUND(C70_VALOR,2)::FLOAT8
                                  WHEN C53_TIPO = 21 THEN ROUND(C70_VALOR*-1,2)::FLOAT8
                              ELSE 0::FLOAT8 END) AS liquidado_compdesp
                              FROM pagordem
                                INNER JOIN conlancamord ON c80_codord = e50_codord
                                INNER JOIN conlancamemp ON c80_codlan = c75_codlan
                                INNER JOIN conlancam ON c75_codlan = c70_codlan
                                INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                                INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                              WHERE e50_numemp = e60_numemp
                                AND e50_compdesp < '{$dtIni}'
                                AND e50_codord = x.e50_codord) as liquidado_compdesp,
                           (SELECT SUM(
                              CASE
                                  WHEN C53_TIPO = 10 THEN ROUND(C70_VALOR,2)::FLOAT8
                                  WHEN C53_TIPO = 11 THEN ROUND(C70_VALOR*-1,2)::FLOAT8
                              ELSE 0::FLOAT8 END) AS empenhado_compdesp
                              FROM pagordem
                                INNER JOIN conlancamord ON c80_codord = e50_codord
                                INNER JOIN conlancamemp ON c80_codlan = c75_codlan
                                INNER JOIN conlancam ON c75_codlan = c70_codlan
                                INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                                INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                              WHERE e50_numemp = e60_numemp
                                AND e50_compdesp < '{$dtIni}'
                                AND e50_codord = x.e50_codord) as empenhado_compdesp
                    FROM
                      (SELECT o58_elemento,
                              o56_descr,
                              e60_numemp,
                              liquidado,
                              empenhado,
                              e50_compdesp,
                              e50_codord
                      FROM work_dotacao
                      INNER JOIN orcelemento ON o58_codele = o56_codele AND o58_anousu = o56_anousu
                      INNER JOIN empempenho ON o58_coddot = e60_coddot AND o58_anousu = e60_anousu
                      INNER JOIN pagordem ON e50_numemp = e60_numemp
                      WHERE o58_elemento LIKE '{$sElemento}'
                          AND o58_instit = {$instit}
                          AND (e60_datasentenca < '{$dtIni}' OR e50_compdesp < '{$dtIni}')) AS x) as xx GROUP BY 1, 2";

    return db_utils::getColectionByRecord(db_query($sql));
}

/**
 * Busca o saldo da receita
 * @param null $o57_fonte
 * @param string $campos
 * @param null $ordem
 * @param string $dbwhere
 * @return array|stdClass[]
 */
function getSaldoReceita($o57_fonte = null, $campos = "*", $ordem = null, $dbwhere = "")
{

    $sql = "select ";
    if ($campos != "*") {
        $campos_sql = split("#", $campos);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
    } else {
        $sql .= $campos;
    }
    $sql .= " from work_receita ";
    $sql2 = "";
    if ($dbwhere == "") {
        if ($o57_fonte != null) {
            $sql2 .= " where work_receita.o57_fonte = '{$o57_fonte}' ";
        }
    } else if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
        $sql .= " order by ";
        $campos_sql = split("#", $ordem);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
    }

    return db_utils::getColectionByRecord(db_query($sql));
}

/**
 * Fun��o que retorna a RCL no periodo indicado
 * @param DBDate $oDataFim
 * @param String $instits [Ex.: 1,2,3,4,5]
 * @return int|number
 * @throws BusinessException
 * @throws ParameterException
 */
function getRCL(DBDate $oDataFim, $instits)
{
    $oPeriodo = new Periodo;
    $oNovaDataFim = clone $oDataFim; //Salvo a data final
    $iMes = $oDataFim->getMes() != 12 ? ($oDataFim->getMes() - 11) + 12 : $oDataFim->getMes() - 11; //Calcula o mes separado por causa do meses que possuem 31 dias
    $oDataFim->modificarIntervalo("-11 month"); //Fa�o isso apenas para saber o ano
    $oDataFim = new DBDate($oDataFim->getAno() . "-" . $iMes . "-1"); //Aqui pego o primeiro dia do mes para montar a nova data de inicio
    $aPeriodoCalculo = DBDate::getMesesNoIntervalo($oDataFim, $oNovaDataFim); //Retorno o array com os anos e seus respectivos meses dentro do periodo informado

    $aCalculos = array();
    /*
         * Para cada periodo, fa�o o calculo da RCL e guardo tudo dentro do array $aCalculos
         */
    foreach ($aPeriodoCalculo as $ano => $mes) {
        $aCalculos[] = calcula_rcl2($ano, $ano . "-" . min(array_keys($aPeriodoCalculo[$ano])) . "-1", $ano . "-" . max(array_keys($aPeriodoCalculo[$ano])) . "-" . $oPeriodo->getPeriodoByMes(max(array_keys($aPeriodoCalculo[$ano])))->getDiaFinal(), $instits, true, 81);
    }
    $fSoma = 0;
    foreach ($aCalculos as $aCalculo) {
        $fSoma += array_sum($aCalculo);
    }
    return $fSoma;
}

/**
 * Busca total do valor pago dos RP n�o processados
 * @param $instits
 * @param $dtini
 * @param $dtfim
 * @param $sFuncao
 * @param $aSubFuncao
 * @param $aFonte
 * @return int
 */
function getSaldoRP($instits, $dtini, $dtfim, $sFuncao, $aSubFuncao, $aFonte)
{
    $fSaldo = 0;
    $clempresto = new cl_empresto;
    $sql_where_externo = "AND 1=1
    AND o58_orgao IN (1,
                      2,
                      3,
                      4,
                      5,
                      6,
                      7,
                      8,
                      9) and o58_funcao = {$sFuncao} and o58_subfuncao in (" . implode(",", $aSubFuncao) . ")";
    $sql_order = "where o15_codtri in (" . implode(",", $aFonte) . ") ORDER BY o58_orgao,
             e60_anousu,
             e60_codemp::integer";
    $sqlempresto = $clempresto->sql_rp_novo(db_getsession("DB_anousu"), "e60_instit in ($instits)", $dtini, $dtfim, "", $sql_where_externo, $sql_order);
    $aDados = db_utils::getColectionByRecord(db_query($sqlempresto));
    foreach ($aDados as $oResto) {
        $fSaldo += $oResto->vlrpagnproc;
    }
    return $fSaldo;
}

// para uso dos anexos da educa��o e saude
function getSaldoPlanoContaFonte($sFonte, $dtIni, $dtFim, $aInstits)
{
    db_inicio_transacao();

    $where = " c61_instit in ({$aInstits})";
    if (db_getsession("DB_anousu") > 2022)
        $where .= " and c61_codigo in ( select o15_codigo from orctiporec where o15_codigo in ($sFonte) ) ";
    else
        $where .= " and c61_codigo in ( select o15_codigo from orctiporec where o15_codtri in ($sFonte) ) ";
    $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtIni, $dtFim, false, $where, '111');
    $nTotalFinal = 0;
    for ($x = 0; $x < pg_numrows($result); $x++) {
        $oPlanoConta = db_utils::fieldsMemory($result, $x);
        if (($oPlanoConta->movimento == "S")
            && (($oPlanoConta->saldo_anterior + $oPlanoConta->saldo_anterior_debito + $oPlanoConta->saldo_anterior_credito) == 0)
        ) {
            continue;
        }
        if (substr($oPlanoConta->estrutural, 1, 14) == '00000000000000') {
            if ($oPlanoConta->saldo_final == "C") {
                $nTotalFinal -= $oPlanoConta->saldo_final;
            } else {
                $nTotalFinal += $oPlanoConta->saldo_final;
            }
        }
    }
    db_query("drop table if exists work_pl");
    db_fim_transacao();
    return $nTotalFinal;
}

// para uso dos anexos da educa��o e saude
function getRestosSemDisponilibidade($sFontes, $dtIni, $dtFim, $aInstits)
{
    db_inicio_transacao();
    db_query("drop table if exists work_pl");
    $clEmpResto = new cl_empresto();
    $sSqlOrder = "";
    $sCampos = " o15_codtri, sum(vlrpag) as pagorpp, sum(vlrpagnproc) as pagorpnp ";
    $sSqlWhere = " o15_codtri in ($sFontes) group by 1 ";
    $aEmpRestos = $clEmpResto->getRestosPagarFontePeriodo(db_getsession("DB_anousu"), $dtIni, $dtFim, $aInstits, $sCampos, $sSqlWhere, $sSqlOrder);

    $nValorRpPago = 0;
    foreach ($aEmpRestos as $oEmpResto) {
        $nValorRpPago += $oEmpResto->pagorpp + $oEmpResto->pagorpnp;
    }

    $dtIni = db_getsession("DB_anousu") . "-01-01";
    $where = " c61_instit in ({$aInstits})";
    $where .= " and c61_codigo in ( select o15_codigo from orctiporec where o15_codtri in ($sFontes) ) ";
    $result = db_planocontassaldo_matriz(db_getsession("DB_anousu"), $dtIni, $dtFim, false, $where, '111');
    $nTotalAnterior = 0;
    for ($x = 0; $x < pg_numrows($result); $x++) {
        $oPlanoConta = db_utils::fieldsMemory($result, $x);
        if (($oPlanoConta->movimento == "S")
            && (($oPlanoConta->saldo_anterior + $oPlanoConta->saldo_anterior_debito + $oPlanoConta->saldo_anterior_credito) == 0)
        ) {
            continue;
        }
        if (substr($oPlanoConta->estrutural, 1, 14) == '00000000000000') {
            if ($oPlanoConta->sinal_anterior == "C") {
                $nTotalAnterior -= $oPlanoConta->saldo_anterior;
            } else {
                $nTotalAnterior += $oPlanoConta->saldo_anterior;
            }
        }
    }

    $iSaldoRestosAPagarSemDisponibilidade = 0;
    if ($nValorRpPago > $nTotalAnterior) {
        $iSaldoRestosAPagarSemDisponibilidade = $nValorRpPago - $nTotalAnterior;
    }
    db_query("drop table if exists work_pl");
    db_fim_transacao();
    return  $iSaldoRestosAPagarSemDisponibilidade;
}

/**
 * Calculo final do relat�rio Anexo II da Educa��o, Contabilidade->Relatorios->Relat�rios de Acompanhamento
 * Este total � utilizado no Anexo I
 * @param $instits
 * @param $dtini
 * @param $dtfim
 * @param $anousu
 * @return int
 */
function getTotalAnexoIIEducacao($instits, $dtini, $dtfim, $anousu)
{
    db_inicio_transacao();
    $sWhereDespesa      = " o58_instit in({$instits})";
    criaWorkDotacao($sWhereDespesa, array($anousu), $dtini, $dtfim);
    $sWhereReceita      = "o70_instit in ({$instits})";
    criarWorkReceita($sWhereReceita, array($anousu), $dtini, $dtfim);
    $fSubTotal = 0;
    $aSubFuncoes = array(122, 272, 271, 361, 365, 366, 367, 843);
    $sFuncao     = "12";
    $aFonte      = array("'101','1101','15000001'");

    foreach ($aSubFuncoes as $iSubFuncao) {
        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao = {$sFuncao} and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (" . implode(",", $aFonte) . ") and o58_instit in ($instits) group by 1,2");
        if ($anousu > 2022)
            $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao = {$sFuncao} and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (" . implode(",", $aFonte) . ") and o58_instit in ($instits) group by 1,2");
        if (count($aDespesasProgramas) > 0) {
            foreach ($aDespesasProgramas as $oDespesaPrograma) {
                $fSubTotal += $oDespesaPrograma->pago;
            }
        }
    }

    $aDadoDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495%'");
    $fSaldoRP = getSaldoRP($instits, $dtini, $dtfim, $sFuncao, $aSubFuncoes, $aFonte);
    db_query("drop table if exists work_dotacao");
    db_query("drop table if exists work_receita");
    db_fim_transacao();
    return ($fSubTotal + abs($aDadoDeducao[0]->saldo_arrecadado_acumulado) + $fSaldoRP);
}

/**
 * Calculo final do relat�rio Anexo II da Educa��o, Contabilidade->Relatorios->Relat�rios de Acompanhamento
 * Este total � utilizado no Anexo I
 * @param $instits
 * @param $dtini
 * @param $dtfim
 * @param $anousu
 * @return int
 */
function getTotalAnexoIIEducacaoNovo($instits, $dtini, $dtfim, $anousu)
{
    db_inicio_transacao();
    db_query("drop table if exists work_pl");
    db_query("drop table if exists work_dotacao");
    db_query("drop table if exists work_receita");
    $nRPExercicioAnteriorSemSaldo = getRestosSemDisponilibidade("'101','15000001'", $dtini, $dtfim, $instits);
    $sWhereDespesa      = " o58_instit in({$instits})";
    criaWorkDotacao($sWhereDespesa, array($anousu), $dtini, $dtfim);
    $sWhereReceita      = "o70_instit in ({$instits})";
    criarWorkReceita($sWhereReceita, array($anousu), $dtini, $dtfim);
    $fSubTotal = 0;
    $aSubFuncoes = array(122, 272, 271, 361, 365, 366, 367, 843);
    $sFuncao     = "12";
    $aFonte      = array("'101','15000001'");
    $fTotalRPExercicio = 0;
    foreach ($aSubFuncoes as $iSubFuncao) {
        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago, coalesce(sum(atual_a_pagar+atual_a_pagar_liquidado),0) as apagar", null, "o58_funcao = {$sFuncao} and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (" . implode(",", $aFonte) . ") and o58_instit in ($instits) group by 1,2");
        if ($anousu > 2022)
            $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago, coalesce(sum(atual_a_pagar+atual_a_pagar_liquidado),0) as apagar", null, "o58_funcao = {$sFuncao} and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (" . implode(",", $aFonte) . ") and o58_instit in ($instits) group by 1,2");
        if (count($aDespesasProgramas) > 0) {
            foreach ($aDespesasProgramas as $oDespesaPrograma) {
                $fSubTotal += $oDespesaPrograma->pago;
                if ($dtfim == db_getsession("DB_anousu") . "-12-31") {
                    $fTotalRPExercicio += $oDespesaPrograma->apagar;
                }
            }
        }
    }
    $aDadoDeducao = getSaldoReceita(null, "sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado", null, "o57_fonte like '495%'");
    $nSaldoFinalFonte = getSaldoPlanoContaFonte("'101','15000001'", $dtini, $dtfim, $instits);
    db_query("drop table if exists work_pl");
    db_query("drop table if exists work_dotacao");
    db_query("drop table if exists work_receita");
    db_fim_transacao();

    $nRPExercicioSemSaldo = $fTotalRPExercicio - $nSaldoFinalFonte;
    if ($nRPExercicioSemSaldo < 0) {
        $nRPExercicioSemSaldo = 0;
    }
    $nValorAplicado = $fSubTotal + abs($aDadoDeducao[0]->saldo_arrecadado_acumulado) + $fTotalRPExercicio  + $nRPExercicioAnteriorSemSaldo -  $nRPExercicioSemSaldo;
    return $nValorAplicado;
}

/**
 * Calculo final do relat�rio Anexo II da Sa�de, Contabilidade->Relatorios->Relat�rios de Acompanhamento
 * Este total � utilizado no Anexo I
 * @param $instits
 * @param $dtini
 * @param $dtfim
 * @param $anousu
 * @return int
 */
function getTotalAnexoIISaude($instits, $dtini, $dtfim, $anousu)
{
    db_inicio_transacao();
    $sWhereDespesa      = " o58_instit in({$instits})";
    criaWorkDotacao($sWhereDespesa, array($anousu), $dtini, $dtfim);
    $fSubTotal = 0;
    $aSubFuncoes = array(122, 272, 271, 301, 302, 303, 304, 305, 306);
    $sFuncao     = "10";
    $aFonte      = array("'102','15000002'");

    foreach ($aSubFuncoes as $iSubFuncao) {
        $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao = {$sFuncao} and o58_subfuncao in ({$iSubFuncao}) and o15_codtri in (" . implode(",", $aFonte) . ") and o58_instit in ($instits) group by 1,2");
        if ($anousu > 2022)
            $aDespesasProgramas = getSaldoDespesa(null, "o58_programa,o58_anousu, coalesce(sum(pago),0) as pago", null, "o58_funcao = {$sFuncao} and o58_subfuncao in ({$iSubFuncao}) and o15_codigo in (" . implode(",", $aFonte) . ") and o58_instit in ($instits) group by 1,2");
        if (count($aDespesasProgramas) > 0) {
            foreach ($aDespesasProgramas as $oDespesaPrograma) {
                $fSubTotal += $oDespesaPrograma->pago;
            }
        }
    }

    db_query("drop table if exists work_dotacao");
    db_query("drop table if exists work_receita");
    db_fim_transacao();
    return $fSubTotal;
}

/**
 * Cria a tabela temporaria work_receita para gera��o dos relatorios de acompanhamento
 * @param $sWhere
 * @param $aAnousu
 * @param $dtini
 * @param $dtfim
 */
function criarWorkReceita($sWhere, $aAnousu, $dtini, $dtfim)
{
    $sSqlCriaTabela = "CREATE TEMP TABLE work_receita AS
                SELECT
                  substr(o57_fonte, 1, 1) ::int4 AS classe,
                  o57_fonte,
                  o57_descr,
                  substr(o57_fonte, 2, 1) ::int4 AS grupo,
                  substr(o57_fonte, 3, 1) ::int4 AS subgrupo,
                  substr(o57_fonte, 4, 1) ::int4 AS elemento,
                  substr(o57_fonte, 5, 1) ::int4 AS subelemento,
                  substr(o57_fonte, 6, 2) ::int4 AS item,
                  substr(o57_fonte, 8, 2) ::int4 AS subitem,
                  substr(o57_fonte, 10, 2) ::int4 AS desdobramento1,
                  substr(o57_fonte, 12, 2) ::int4 AS desdobramento2,
                  substr(o57_fonte, 14, 2) ::int4 AS desdobramento3,
                  o70_codrec,
                  o70_concarpeculiar,
                  o70_codigo,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 3, 12), ''), '0') AS float8) AS saldo_inicial,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 16, 12), ''), '0') AS float8) AS saldo_prevadic_acum,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 29, 12), ''), '0') AS float8) AS saldo_inicial_prevadic,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 42, 12), ''), '0') AS float8) AS saldo_anterior,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 55, 12), ''), '0') AS float8) AS saldo_arrecadado,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 68, 12), ''), '0') AS float8) AS saldo_a_arrecadar,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 81, 12), ''), '0') AS float8) AS saldo_arrecadado_acumulado,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 94, 12), ''), '0') AS float8) AS saldo_prev_anterior
                FROM (SELECT
                  o70_anousu,
                  o70_codrec,
                  o70_codfon,
                  o70_codigo,
                  o70_valor,
                  o70_reclan,
                  o70_instit,
                  o70_concarpeculiar,
                  o57_codfon,
                  o57_anousu,
                  o57_fonte,
                  o57_descr,
                  o57_finali,
                  fc_receitasaldo(1999, o70_codrec, 3, '1999-01-01', '1999-01-01')
                FROM orcreceita d
                INNER JOIN orcfontes e
                  ON d.o70_codfon = e.o57_codfon
                  AND e.o57_anousu = d.o70_anousu
                WHERE o70_anousu = 1999
                AND o70_instit in (99)
                ORDER BY o57_fonte) AS x; TRUNCATE work_receita; ";
    foreach ($aAnousu as $anousu) {
        $sSql = "
                  insert into work_receita
                  SELECT
                  substr(o57_fonte, 1, 1) ::int4 AS classe,
                  o57_fonte,
                  o57_descr,
                  substr(o57_fonte, 2, 1) ::int4 AS grupo,
                  substr(o57_fonte, 3, 1) ::int4 AS subgrupo,
                  substr(o57_fonte, 4, 1) ::int4 AS elemento,
                  substr(o57_fonte, 5, 1) ::int4 AS subelemento,
                  substr(o57_fonte, 6, 2) ::int4 AS item,
                  substr(o57_fonte, 8, 2) ::int4 AS subitem,
                  substr(o57_fonte, 10, 2) ::int4 AS desdobramento1,
                  substr(o57_fonte, 12, 2) ::int4 AS desdobramento2,
                  substr(o57_fonte, 14, 2) ::int4 AS desdobramento3,
                  o70_codrec,
                  o70_concarpeculiar,
                  o70_codigo,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 3, 12), ''), '0') AS float8) AS saldo_inicial,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 16, 12), ''), '0') AS float8) AS saldo_prevadic_acum,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 29, 12), ''), '0') AS float8) AS saldo_inicial_prevadic,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 42, 12), ''), '0') AS float8) AS saldo_anterior,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 55, 12), ''), '0') AS float8) AS saldo_arrecadado,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 68, 12), ''), '0') AS float8) AS saldo_a_arrecadar,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 81, 12), ''), '0') AS float8) AS saldo_arrecadado_acumulado,
                  CAST(COALESCE(NULLIF(substr(fc_receitasaldo, 94, 12), ''), '0') AS float8) AS saldo_prev_anterior
                FROM (SELECT
                  o70_anousu,
                  o70_codrec,
                  o70_codfon,
                  o70_codigo,
                  o70_valor,
                  o70_reclan,
                  o70_instit,
                  o70_concarpeculiar,
                  o57_codfon,
                  o57_anousu,
                  o57_fonte,
                  o57_descr,
                  o57_finali,
                  fc_receitasaldo($anousu, o70_codrec, 3, '{$dtini}', '{$dtfim}')
                FROM orcreceita d
                INNER JOIN orcfontes e
                  ON d.o70_codfon = e.o57_codfon
                  AND e.o57_anousu = d.o70_anousu
                WHERE o70_anousu = {$anousu}
                AND {$sWhere}
                ORDER BY o57_fonte) AS x;";
    }
    db_query($sSqlCriaTabela . $sSql) or die(pg_last_error());
}

/**
 * Cria a tabela temporaria work_dotacao para gera��o dos relatorios de acompanhamento
 * @param $sWhere
 * @param $aAnousu Array()
 * @param $dtini
 * @param $dtfim
 */
function criaWorkDotacao($sWhere, $aAnousu, $dtini, $dtfim)
{
    $aDatas = array();
    $dt_inicial = "";
    $dt_final   = "";
    if (count($aAnousu) == 2) {
        $aDatas[$aAnousu[0]] = $dtini . 'a' . $aAnousu[0] . '-12-31';
        $aDatas[$aAnousu[1]] = $aAnousu[1] . '-01-01' . 'a' . $dtfim;
    }

    $sSqlCriaTabela = " CREATE TABLE IF NOT EXISTS work_dotacao
                        (
                          o58_instit    INTEGER,
                          o58_anousu    INTEGER,
                          o58_orgao     INTEGER,
                          o58_unidade   INTEGER,
                          o58_funcao    INTEGER,
                          o58_subfuncao INTEGER,
                          o58_programa  INTEGER,
                          o58_projativ  INTEGER,
                          o58_codele    INTEGER,
                          o58_coddot    INTEGER,
                          o58_elemento CHARACTER varying,
                          o58_codigo INTEGER,
                          o15_codtri CHARACTER VARYING,
                          o15_codigo CHARACTER VARYING,
                          dot_ini DOUBLE PRECISION,
                          saldo_anterior DOUBLE PRECISION,
                          empenhado DOUBLE PRECISION,
                          anulado DOUBLE PRECISION,
                          liquidado DOUBLE PRECISION,
                          pago DOUBLE PRECISION,
                          suplementado DOUBLE PRECISION,
                          reduzido DOUBLE PRECISION,
                          atual DOUBLE PRECISION,
                          reservado DOUBLE PRECISION,
                          atual_menos_reservado DOUBLE PRECISION,
                          atual_a_pagar DOUBLE PRECISION,
                          atual_a_pagar_liquidado DOUBLE PRECISION,
                          empenhado_acumulado DOUBLE PRECISION,
                          anulado_acumulado DOUBLE PRECISION,
                          liquidado_acumulado DOUBLE PRECISION,
                          pago_acumulado DOUBLE PRECISION,
                          suplementado_acumulado DOUBLE PRECISION,
                          reduzido_acumulado DOUBLE PRECISION,
                          suplemen DOUBLE PRECISION,
                          suplemen_acumulado DOUBLE PRECISION,
                          especial DOUBLE PRECISION,
                          especial_acumulado DOUBLE PRECISION,
                          transfsup DOUBLE PRECISION,
                          transfsup_acumulado DOUBLE PRECISION,
                          transfred DOUBLE PRECISION,
                          transfred_acumulado DOUBLE PRECISION,
                          reservado_manual_ate_data DOUBLE PRECISION,
                          reservado_automatico_ate_data DOUBLE PRECISION,
                          reservado_ate_data DOUBLE PRECISION,
                          o55_tipo INTEGER,
                          o15_tipo INTEGER,
                          proj DOUBLE PRECISION,
                          ativ DOUBLE PRECISION,
                          oper DOUBLE PRECISION,
                          ordinario DOUBLE PRECISION,
                          vinculado DOUBLE PRECISION
                          ); TRUNCATE work_dotacao;";
    foreach ($aAnousu as $anousu) {

        $sSql .= " INSERT INTO work_dotacao
                    SELECT *,
                           (CASE
                                WHEN o55_tipo = 1 THEN dot_ini
                                ELSE 0
                            END) AS proj,
                           (CASE
                                WHEN o55_tipo = 2 THEN dot_ini
                                ELSE 0
                            END) AS ativ,
                           (CASE
                                WHEN o55_tipo = 3 THEN dot_ini
                                ELSE 0
                            END) AS oper,
                           (CASE
                                WHEN o15_tipo = 1 THEN dot_ini
                                ELSE 0
                            END) AS ordinario,
                           (CASE
                                WHEN o15_tipo <> 1 THEN dot_ini
                                ELSE 0
                            END) AS vinculado
                    FROM
                      (SELECT o58_instit,
                              o58_anousu,
                              o58_orgao,
                              o58_unidade,
                              o58_funcao,
                              o58_subfuncao,
                              o58_programa,
                              o58_projativ,
                              o56_codele AS o58_codele,
                              CASE
                                  WHEN 'nao'='sim' THEN 9999999
                                  ELSE o58_coddot
                              END AS o58_coddot,
                              CASE
                                  WHEN 'nao'='sim' THEN substr(o56_elemento,1,7)
                                  ELSE o56_elemento
                              END AS o58_elemento,
                              o58_codigo,
                              o15_codtri,
                              o15_codigo,
                              substr(fc_dotacaosaldo,3,12)::float8 AS dot_ini,
                              substr(fc_dotacaosaldo,16,12)::float8 AS saldo_anterior,
                              substr(fc_dotacaosaldo,29,12)::float8 AS empenhado,
                              substr(fc_dotacaosaldo,42,12)::float8 AS anulado,
                              substr(fc_dotacaosaldo,55,12)::float8 AS liquidado,
                              substr(fc_dotacaosaldo,68,12)::float8 AS pago,
                              substr(fc_dotacaosaldo,81,12)::float8 AS suplementado,
                              substr(fc_dotacaosaldo,094,12)::float8 AS reduzido,
                              substr(fc_dotacaosaldo,107,12)::float8 AS atual,
                              substr(fc_dotacaosaldo,120,12)::float8 AS reservado,
                              substr(fc_dotacaosaldo,133,12)::float8 AS atual_menos_reservado,
                              substr(fc_dotacaosaldo,146,12)::float8 AS atual_a_pagar,
                              substr(fc_dotacaosaldo,159,12)::float8 AS atual_a_pagar_liquidado,
                              substr(fc_dotacaosaldo,172,12)::float8 AS empenhado_acumulado,
                              substr(fc_dotacaosaldo,185,12)::float8 AS anulado_acumulado,
                              substr(fc_dotacaosaldo,198,12)::float8 AS liquidado_acumulado,
                              substr(fc_dotacaosaldo,211,12)::float8 AS pago_acumulado,
                              substr(fc_dotacaosaldo,224,12)::float8 AS suplementado_acumulado,
                              substr(fc_dotacaosaldo,237,12)::float8 AS reduzido_acumulado,
                              substr(fc_dotacaosaldo,250,12)::float8 AS suplemen,
                              substr(fc_dotacaosaldo,263,12)::float8 AS suplemen_acumulado,
                              substr(fc_dotacaosaldo,276,12)::float8 AS especial,
                              substr(fc_dotacaosaldo,289,12)::float8 AS especial_acumulado,
                              substr(fc_dotacaosaldo,303,12)::float8 AS transfsup,
                              substr(fc_dotacaosaldo,316,12)::float8 AS transfsup_acumulado,
                              substr(fc_dotacaosaldo,329,12)::float8 AS transfred,
                              substr(fc_dotacaosaldo,342,12)::float8 AS transfred_acumulado,
                              substr(fc_dotacaosaldo,355,12)::float8 AS reservado_manual_ate_data,
                              substr(fc_dotacaosaldo,368,12)::float8 AS reservado_automatico_ate_data,
                              substr(fc_dotacaosaldo,381,12)::float8 AS reservado_ate_data,
                              o55_tipo,
                              o15_tipo from ";
        if (count($aAnousu) == 2) {
            $dt_inicial = explode("a", $aDatas[$anousu]);
            $dt_final   = explode("a", $aDatas[$anousu]);
            $sSql .= " (SELECT *, fc_dotacaosaldo({$anousu},o58_coddot,2,'{$dt_inicial[0]}','{$dt_final[1]}')
                          FROM orcdotacao w
                          INNER JOIN orcelemento e ON w.o58_codele = e.o56_codele
                          AND e.o56_anousu = w.o58_anousu
                          AND e.o56_orcado IS TRUE
                          INNER JOIN orcprojativ ope ON w.o58_projativ = ope.o55_projativ
                          AND ope.o55_anousu = w.o58_anousu
                          INNER JOIN orctiporec ON orctiporec.o15_codigo = w.o58_codigo
                          WHERE o58_anousu = {$anousu} ";
        } else {
            $sSql .= " (SELECT *, fc_dotacaosaldo({$anousu},o58_coddot,2,'{$dtini}','{$dtfim}')
                          FROM orcdotacao w
                          INNER JOIN orcelemento e ON w.o58_codele = e.o56_codele
                          AND e.o56_anousu = w.o58_anousu
                          AND e.o56_orcado IS TRUE
                          INNER JOIN orcprojativ ope ON w.o58_projativ = ope.o55_projativ
                          AND ope.o55_anousu = w.o58_anousu
                          INNER JOIN orctiporec ON orctiporec.o15_codigo = w.o58_codigo
                          WHERE o58_anousu = {$anousu} ";
        }
        $sSql .= " AND {$sWhere}
                    ORDER BY o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa, o58_projativ, o56_codele, o56_elemento, o58_coddot, o58_codigo) AS x) AS xxx; ";
    }
    db_query($sSqlCriaTabela . $sSql) or die(pg_last_error());
}

// para uso dos anexos da educa��o e saude
function getSaldoAPagarRPFonte($sFontes, $dtIni, $dtFim, $aInstits)
{
    $clEmpResto = new cl_empresto();
    $sSqlOrder = "";
    $sCampos = " o15_codtri, sum(e91_vlremp) as vlremp, sum(e91_vlranu) as vlranu, sum(e91_vlrpag) as vlrpag ";
    $sSqlWhere = " o15_codtri in ($sFontes) group by 1 ";
    if (db_getsession("DB_anousu") > 2022)
        $sSqlWhere = " o15_codigo in ($sFontes) group by 1 ";
    $aEmpRestos = $clEmpResto->getRestosPagarFontePeriodo(db_getsession("DB_anousu"), $dtIni, $dtFim, $aInstits, $sCampos, $sSqlWhere, $sSqlOrder);

    $nValorARpPagar = 0;
    foreach ($aEmpRestos as $oEmpResto) {
        $nValorARpPagar += $oEmpResto->vlremp - $oEmpResto->vlranu - $oEmpResto->vlrpag;
    }
    return  $nValorARpPagar;
}
?>