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
//ini_set("display_errors","on");
//MODULO: contabilidade
//CLASSE DA ENTIDADE conplano
class cl_conplano
{
  // cria variaveis de erro
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $c60_codcon = 0;
  var $c60_anousu = 0;
  var $c60_estrut = null;
  var $c60_descr = null;
  var $c60_finali = null;
  var $c60_codsis = 0;
  var $c60_codcla = 0;
  var $c60_consistemaconta = 0;
  var $c60_identificadorfinanceiro = null;
  var $c60_naturezasaldo = 0;
  var $c60_funcao = null;
  var $c60_tipolancamento = null;
  var $c60_desdobramneto = null;
  var $c60_subtipolancamento = null;

  var $c60_nregobrig = null;
  var $c60_cgmpessoa = null;
  var $c60_naturezadareceita = null;
  var $c60_infcompmsc;
  var $aContasCgmPessoa = array();
  // cria propriedade com as variaveis do arquivo
  var $campos = "
  c60_codcon = int4 = C�digo
  c60_anousu = int4 = Exerc�cio
  c60_estrut = varchar(15) = Estrutural
  c60_descr = varchar(50) = Descri��o da conta
  c60_finali = text = Finalidade
  c60_codsis = int4 = Sistema
  c60_codcla = int4 = Classifica��o
  c60_consistemaconta = int4 = consistemaconta
  c60_identificadorfinanceiro = char(1) = Identificador financeiro
  c60_naturezasaldo = int4 = naturezasaldo
  c60_funcao = text = Fun��o
  c60_tipolancamento = int8 = TipoLancamento
  c60_subtipolancamento = int8 = Subtipo
  c60_desdobramneto = int8 = Desdobramento
  c60_nregobrig = int8 = Registro
  c60_cgmpessoa = int8 = Cgm
  c60_naturezadareceita = int8 = natureza da receita
  c60_infcompmsc = int4 = Inf. Comp. MSC
  ";
  //funcao construtor da classe
  function cl_conplano()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("conplano");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    $this->aContasCgmPessoa[0] = array('1134101', '1134102', '1134103');
    $this->aContasCgmPessoa[1] = array('121210401', '121210402', '121210403', '121210404', '121210405', '121210406', '121210407', '121210408', '121210409', '121210410', '121210499', '121210501', '121210502', '121210503', '121210504', '121210505', '121210506', '121210507', '121210508', '121210509', '121210510', '121210511', '121210512', '121210513', '121210514', '121210515', '121210599');
  }
  //funcao erro
  function erro($mostra, $retorna)
  {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
      echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }
  // funcao para atualizar campos
  function atualizacampos($exclusao = false)
  {
    if ($exclusao == false) {
      $this->c60_codcon = ($this->c60_codcon == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_codcon"] : $this->c60_codcon);
      $this->c60_anousu = ($this->c60_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_anousu"] : $this->c60_anousu);
      $this->c60_estrut = ($this->c60_estrut == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_estrut"] : $this->c60_estrut);
      $this->c60_descr = ($this->c60_descr == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_descr"] : $this->c60_descr);
      $this->c60_finali = ($this->c60_finali == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_finali"] : $this->c60_finali);
      $this->c60_codsis = ($this->c60_codsis == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_codsis"] : $this->c60_codsis);
      $this->c60_codcla = ($this->c60_codcla == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_codcla"] : $this->c60_codcla);
      $this->c60_consistemaconta = ($this->c60_consistemaconta == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"] : $this->c60_consistemaconta);
      $this->c60_identificadorfinanceiro = ($this->c60_identificadorfinanceiro == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"] : $this->c60_identificadorfinanceiro);
      $this->c60_naturezasaldo = ($this->c60_naturezasaldo == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"] : $this->c60_naturezasaldo);
      $this->c60_funcao = ($this->c60_funcao == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_funcao"] : $this->c60_funcao);
      $this->c60_tipolancamento = ($this->c60_tipolancamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_tipolancamento"] : $this->c60_tipolancamento);
      $this->c60_subtipolancamento = ($this->c60_subtipolancamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_subtipolancamento"] : $this->c60_subtipolancamento);
      $this->c60_desdobramneto = ($this->c60_desdobramneto == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_desdobramneto"] : $this->c60_desdobramneto);
      $this->c60_infcompmsc = ($this->c60_infcompmsc == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_infcompmsc"] : $this->c60_infcompmsc);

      //
      $this->c60_nregobrig = ($this->c60_nregobrig == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_nregobrig"] : $this->c60_nregobrig);
      $this->c60_cgmpessoa = ($this->c60_cgmpessoa == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_cgmpessoa"] : $this->c60_cgmpessoa);
      $this->c60_naturezadareceita = ($this->c60_naturezadareceita == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_naturezadareceita"] : $this->c60_naturezadareceita);
    } else {
      $this->c60_codcon = ($this->c60_codcon == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_codcon"] : $this->c60_codcon);
      $this->c60_anousu = ($this->c60_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["c60_anousu"] : $this->c60_anousu);
    }
  }
  // funcao para inclusao
  function incluir($c60_codcon = null, $c60_anousu = null, $subtipo = null, $iTipoConta = null)
  {
    $this->atualizacampos();
    if ($this->c60_estrut == null) {
      $this->erro_sql = " Campo Estrutural nao Informado.";
      $this->erro_campo = "c60_estrut";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_descr == null) {
      $this->erro_sql = " Campo Descri��o da conta nao Informado.";
      $this->erro_campo = "c60_descr";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_codsis == null) {
      $this->erro_sql = " Campo Sistema nao Informado.";
      $this->erro_campo = "c60_codsis";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_codcla == null) {
      $this->erro_sql = " Campo Classifica��o nao Informado.";
      $this->erro_campo = "c60_codcla";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_consistemaconta == null) {
      $this->erro_sql = " Campo consistemaconta nao Informado.";
      $this->erro_campo = "c60_consistemaconta";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_identificadorfinanceiro == null) {
      $this->erro_sql = " Campo Identificador financeiro nao Informado.";
      $this->erro_campo = "c60_identificadorfinanceiro";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_naturezasaldo == null) {
      $this->erro_sql = " Campo naturezasaldo nao Informado.";
      $this->erro_campo = "c60_naturezasaldo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_nregobrig == null) {
      $this->erro_sql = " Campo N Registro Obrigatorio nao Informado.";
      $this->erro_campo = "c60_nregobrig";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c60_cgmpessoa == null && $iTipoConta == 1  && (in_array(substr($this->c60_estrut, 0, 7), $this->aContasCgmPessoa[0])
      || in_array(substr($this->c60_estrut, 0, 9), $this->aContasCgmPessoa[1]))) {
      $this->erro_sql = " Campo CGM Pessoa nao Informado.";
      $this->erro_campo = "c60_cgmpessoa";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    } else if ((!in_array(substr($this->c60_estrut, 0, 7), $this->aContasCgmPessoa[0]) && !in_array(substr($this->c60_estrut, 0, 9), $this->aContasCgmPessoa[1])) || (isset($iTipoConta) && $iTipoConta == 0)) {
      $this->c60_cgmpessoa = 'null';
    }
    if ($c60_codcon == "" || $c60_codcon == null) {
      $result = db_query("select nextval('conplano_c60_codcon_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: conplano_c60_codcon_seq do campo: c60_codcon";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c60_codcon = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from conplano_c60_codcon_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $c60_codcon)) {
        $this->erro_sql = " Campo c60_codcon maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->c60_codcon = $c60_codcon;
      }
    }
    if (($this->c60_codcon == null) || ($this->c60_codcon == "")) {
      $this->erro_sql = " Campo c60_codcon nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if (($this->c60_anousu == null) || ($this->c60_anousu == "")) {
      $this->erro_sql = " Campo c60_anousu nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    /**
     * tipo de Detalhamento do Sistema ==7 entao obriga o preenchimento dos campos
     */
    if ($this->c60_codsis == 7) {
      if (trim($this->c60_tipolancamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_tipolancamento"])) {
        if (trim($this->c60_tipolancamento) == null) {
          $this->erro_sql = " Campo Tipo lancamento nao Informado.";
          $this->erro_campo = "c60_tipolancamento";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }

      if (($this->c60_tipolancamento != null) || ($this->c60_tipolancamento != "")) {
        if (isset($subtipo) && $subtipo == " ") {
          $this->erro_sql = " Campo sub tipolancamento nao Informado.";
          $this->erro_campo = "c60_subtipolancamento";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        } else {
          $subtipo = $this->c60_subtipolancamento;
        }
        //a valida��o e realizada via javascript no arquivo de form   para  o c60_desdobramneto
      }
    }

    $sql = "insert into conplano(
    c60_codcon
    ,c60_anousu
    ,c60_estrut
    ,c60_descr
    ,c60_finali
    ,c60_codsis
    ,c60_codcla
    ,c60_consistemaconta
    ,c60_identificadorfinanceiro
    ,c60_naturezasaldo
    ,c60_funcao
    ,c60_tipolancamento
    ,c60_subtipolancamento
    ,c60_desdobramneto
    ,c60_nregobrig
    ,c60_cgmpessoa
    ,c60_naturezadareceita
    ,c60_infcompmsc
    )
    values (
    $this->c60_codcon
    ,$this->c60_anousu
    ,'$this->c60_estrut'
    ,'$this->c60_descr'
    ,'$this->c60_finali'
    ,$this->c60_codsis
    ,$this->c60_codcla
    ,$this->c60_consistemaconta
    ,'$this->c60_identificadorfinanceiro'
    ,$this->c60_naturezasaldo
    ,'$this->c60_funcao'
    ," . ($this->c60_tipolancamento == '' ? 'null' : $this->c60_tipolancamento) . "
    ," . ($subtipo == '' ? 'null' : $subtipo) . "
    ," . ($this->c60_desdobramneto == '' ? 'null' : $this->c60_desdobramneto) . "
    ,$this->c60_nregobrig
    ," . ($this->c60_cgmpessoa == '' ? 'null' : $this->c60_cgmpessoa) . "
    ," . ($this->c60_naturezadareceita == null ? 'null' : $this->c60_naturezadareceita) . "
    ," . ($this->c60_infcompmsc == null || $this->c60_infcompmsc == '' ? 'null' : $this->c60_infcompmsc) . "
    )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = "Plano de Contas ($this->c60_codcon." - ".$this->c60_anousu) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "Plano de Contas nao Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql   = "Plano de Contas ($this->c60_codcon." - ".$this->c60_anousu) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->c60_codcon . "-" . $this->c60_anousu;
    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->c60_codcon, $this->c60_anousu));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,5220,'$this->c60_codcon','I')");
      $resac = db_query("insert into db_acountkey values($acount,8059,'$this->c60_anousu','I')");
      $resac = db_query("insert into db_acount values($acount,774,5220,'','" . AddSlashes(pg_result($resaco, 0, 'c60_codcon')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,8059,'','" . AddSlashes(pg_result($resaco, 0, 'c60_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,5221,'','" . AddSlashes(pg_result($resaco, 0, 'c60_estrut')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,5222,'','" . AddSlashes(pg_result($resaco, 0, 'c60_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,5223,'','" . AddSlashes(pg_result($resaco, 0, 'c60_finali')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,5224,'','" . AddSlashes(pg_result($resaco, 0, 'c60_codsis')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,5225,'','" . AddSlashes(pg_result($resaco, 0, 'c60_codcla')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,18504,'','" . AddSlashes(pg_result($resaco, 0, 'c60_consistemaconta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,18505,'','" . AddSlashes(pg_result($resaco, 0, 'c60_identificadorfinanceiro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,18506,'','" . AddSlashes(pg_result($resaco, 0, 'c60_naturezasaldo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,774,18534,'','" . AddSlashes(pg_result($resaco, 0, 'c60_funcao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }
    return true;
  }
  // funcao para alteracao
  function alterar($c60_codcon = null, $c60_anousu = null, $subtipo = null, $iTipoConta = null)
  {
    $this->atualizacampos();
    $sql = " update conplano set ";
    $virgula = "";
    if (trim($this->c60_codcon) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codcon"])) {
      $sql  .= $virgula . " c60_codcon = $this->c60_codcon ";
      $virgula = ",";
      if (trim($this->c60_codcon) == null) {
        $this->erro_sql = " Campo C�digo nao Informado.";
        $this->erro_campo = "c60_codcon";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_anousu"])) {
      $sql  .= $virgula . " c60_anousu = $this->c60_anousu ";
      $virgula = ",";
      if (trim($this->c60_anousu) == null) {
        $this->erro_sql = " Campo Exerc�cio nao Informado.";
        $this->erro_campo = "c60_anousu";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_estrut) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_estrut"])) {
      $sql  .= $virgula . " c60_estrut = '$this->c60_estrut' ";
      $virgula = ",";
      if (trim($this->c60_estrut) == null) {
        $this->erro_sql = " Campo Estrutural nao Informado.";
        $this->erro_campo = "c60_estrut";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_descr) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_descr"])) {
      $sql  .= $virgula . " c60_descr = '$this->c60_descr' ";
      $virgula = ",";
      if (trim($this->c60_descr) == null) {
        $this->erro_sql = " Campo Descri��o da conta nao Informado.";
        $this->erro_campo = "c60_descr";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_finali) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_finali"])) {
      $sql  .= $virgula . " c60_finali = '$this->c60_finali' ";
      $virgula = ",";
    }
    if (trim($this->c60_codsis) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codsis"])) {
      $sql  .= $virgula . " c60_codsis = $this->c60_codsis ";
      $virgula = ",";
      if (trim($this->c60_codsis) == null) {
        $this->erro_sql = " Campo Sistema nao Informado.";
        $this->erro_campo = "c60_codsis";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_codcla) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codcla"])) {
      $sql  .= $virgula . " c60_codcla = $this->c60_codcla ";
      $virgula = ",";
      if (trim($this->c60_codcla) == null) {
        $this->erro_sql = " Campo Classifica��o nao Informado.";
        $this->erro_campo = "c60_codcla";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_consistemaconta) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"])) {
      $sql  .= $virgula . " c60_consistemaconta = $this->c60_consistemaconta ";
      $virgula = ",";
      if (trim($this->c60_consistemaconta) == null) {
        $this->erro_sql = " Campo consistemaconta nao Informado.";
        $this->erro_campo = "c60_consistemaconta";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_identificadorfinanceiro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"])) {
      $sql  .= $virgula . " c60_identificadorfinanceiro = '$this->c60_identificadorfinanceiro' ";
      $virgula = ",";
      if (trim($this->c60_identificadorfinanceiro) == null) {
        $this->erro_sql = " Campo Identificador financeiro nao Informado.";
        $this->erro_campo = "c60_identificadorfinanceiro";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_naturezasaldo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"])) {
      $sql  .= $virgula . " c60_naturezasaldo = $this->c60_naturezasaldo ";
      $virgula = ",";
      if (trim($this->c60_naturezasaldo) == null) {
        $this->erro_sql = " Campo naturezasaldo nao Informado.";
        $this->erro_campo = "c60_naturezasaldo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_funcao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_funcao"])) {
      $sql  .= $virgula . " c60_funcao = '$this->c60_funcao' ";
      $virgula = ",";
      if (trim($this->c60_funcao) == null) {
        $this->erro_sql = " Campo Fun��o nao Informado.";
        $this->erro_campo = "c60_funcao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c60_infcompmsc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_infcompmsc"])) {
      $sql  .= $virgula . " c60_infcompmsc = '$this->c60_infcompmsc' ";
      $virgula = ",";
    }

    /**
     * tipo de Detalhamento do Sistema ==7 entao obriga o preenchimento dos campos
     */
    if ($this->c60_codsis == 7) {
      if (trim($this->c60_tipolancamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_tipolancamento"])) {
        $sql  .= $virgula . " c60_tipolancamento = $this->c60_tipolancamento ";
        $virgula = ",";
        if (trim($this->c60_tipolancamento) == null) {
          $this->erro_sql = " Campo Tipo lancamento nao Informado.";
          $this->erro_campo = "c60_tipolancamento";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }

      if (($this->c60_tipolancamento != null) || ($this->c60_tipolancamento != "")) {
        if ($subtipo ==  " ") {
          $this->erro_sql = " Campo sub tipolancamento nao Informado.";
          $this->erro_campo = "c60_subtipolancamento";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        } else {
          $sql  .= $virgula . " c60_subtipolancamento = $subtipo";
          $virgula = ",";
        }
      }

      //a valida��o e realizada via javascript no arquivo de form
      if (trim($this->c60_desdobramneto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_desdobramneto"])) {
        if ($this->c60_desdobramneto == "0") {
          $sql  .= $virgula . " c60_desdobramneto = null";
          $virgula = ",";
        } else {
          $sql  .= $virgula . " c60_desdobramneto = $this->c60_desdobramneto ";
          $virgula = ",";
        }
      } else {
        $sql  .= $virgula . " c60_desdobramneto = null";
        $virgula = ",";
      }
    } else {
      $sql  .= $virgula . " c60_tipolancamento = null";
      $virgula = ",";

      $sql  .= $virgula . " c60_subtipolancamento = null";
      $virgula = ",";

      $sql  .= $virgula . " c60_desdobramneto =null";
      $virgula = ",";
    }

    if (trim($this->c60_nregobrig) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_nregobrig"])) {
      $sql  .= $virgula . " c60_nregobrig = '$this->c60_nregobrig' ";
      $virgula = ",";
      if (trim($this->c60_nregobrig) == null) {
        $this->erro_sql = " Campo Fun��o nao Informado.";
        $this->erro_campo = "c60_nregobrig";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if (trim($this->c60_naturezadareceita) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_naturezadareceita"])) {
      $sql  .= $virgula . " c60_naturezadareceita = '$this->c60_naturezadareceita' ";
      $virgula = ",";
      if (trim($this->c60_naturezadareceita) == null) {
        $this->erro_sql = " Campo Fun��o nao Informado.";
        $this->erro_campo = "c60_naturezadareceita";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if ((trim($this->c60_cgmpessoa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c60_cgmpessoa"]))
      && (in_array(substr($this->c60_estrut, 0, 7), $this->aContasCgmPessoa[0]) || in_array(substr($this->c60_estrut, 0, 9), $this->aContasCgmPessoa[1])) && $iTipoConta == 1
    ) {
      $sql  .= $virgula . " c60_cgmpessoa = $this->c60_cgmpessoa ";
      $virgula = ",";
      if ($this->c60_cgmpessoa == null) {
        $this->erro_sql = " Campo Cgm Pessoa nao Informado.";
        $this->erro_campo = "c60_cgmpessoa";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else if ((in_array(substr($this->c60_estrut, 0, 7), $this->aContasCgmPessoa[0]) || in_array(substr($this->c60_estrut, 0, 9), $this->aContasCgmPessoa[1])) && $this->c60_cgmpessoa == null && $iTipoConta == 1) {
      $this->erro_sql = " Campo Cgm Pessoa nao Informado.";
      $this->erro_campo = "c60_cgmpessoa";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql .= " where ";
    if ($c60_codcon != null) {
      $sql .= " c60_codcon = $this->c60_codcon";
    }
    if ($c60_anousu != null) {
      $sql .= " and  c60_anousu = $this->c60_anousu";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->c60_codcon, $this->c60_anousu));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,5220,'$this->c60_codcon','A')");
        $resac = db_query("insert into db_acountkey values($acount,8059,'$this->c60_anousu','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_codcon"]) || $this->c60_codcon != "")
          $resac = db_query("insert into db_acount values($acount,774,5220,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_codcon')) . "','$this->c60_codcon'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_anousu"]) || $this->c60_anousu != "")
          $resac = db_query("insert into db_acount values($acount,774,8059,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_anousu')) . "','$this->c60_anousu'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_estrut"]) || $this->c60_estrut != "")
          $resac = db_query("insert into db_acount values($acount,774,5221,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_estrut')) . "','$this->c60_estrut'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_descr"]) || $this->c60_descr != "")
          $resac = db_query("insert into db_acount values($acount,774,5222,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_descr')) . "','$this->c60_descr'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_finali"]) || $this->c60_finali != "")
          $resac = db_query("insert into db_acount values($acount,774,5223,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_finali')) . "','$this->c60_finali'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_codsis"]) || $this->c60_codsis != "")
          $resac = db_query("insert into db_acount values($acount,774,5224,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_codsis')) . "','$this->c60_codsis'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_codcla"]) || $this->c60_codcla != "")
          $resac = db_query("insert into db_acount values($acount,774,5225,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_codcla')) . "','$this->c60_codcla'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"]) || $this->c60_consistemaconta != "")
          $resac = db_query("insert into db_acount values($acount,774,18504,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_consistemaconta')) . "','$this->c60_consistemaconta'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"]) || $this->c60_identificadorfinanceiro != "")
          $resac = db_query("insert into db_acount values($acount,774,18505,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_identificadorfinanceiro')) . "','$this->c60_identificadorfinanceiro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"]) || $this->c60_naturezasaldo != "")
          $resac = db_query("insert into db_acount values($acount,774,18506,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_naturezasaldo')) . "','$this->c60_naturezasaldo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["c60_funcao"]) || $this->c60_funcao != "")
          $resac = db_query("insert into db_acount values($acount,774,18534,'" . AddSlashes(pg_result($resaco, $conresaco, 'c60_funcao')) . "','$this->c60_funcao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = @db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Plano de Contas nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->c60_codcon . "-" . $this->c60_anousu;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Plano de Contas nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->c60_codcon . "-" . $this->c60_anousu;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->c60_codcon . "-" . $this->c60_anousu;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  function excluir($c60_codcon = null, $c60_anousu = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($c60_codcon, $c60_anousu));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,5220,'$c60_codcon','E')");
        $resac = db_query("insert into db_acountkey values($acount,8059,'$c60_anousu','E')");
        $resac = db_query("insert into db_acount values($acount,774,5220,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_codcon')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,8059,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,5221,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_estrut')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,5222,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,5223,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_finali')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,5224,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_codsis')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,5225,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_codcla')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,18504,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_consistemaconta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,18505,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_identificadorfinanceiro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,18506,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_naturezasaldo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,774,18534,'','" . AddSlashes(pg_result($resaco, $iresaco, 'c60_funcao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from conplano
    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($c60_codcon != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " c60_codcon = $c60_codcon ";
      }
      if ($c60_anousu != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " c60_anousu = $c60_anousu ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Plano de Contas nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_sql .= "Valores : " . $c60_codcon . "-" . $c60_anousu;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Plano de Contas nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $c60_codcon . "-" . $c60_anousu;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $c60_codcon . "-" . $c60_anousu;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset
  function sql_record($sql)
  {
    $result = db_query($sql);
    if ($result == false) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:conplano";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  function sql_query($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu=c60_anousu ";
    $sql .= "      left join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon and conplanoconta.c63_anousu = conplanoreduz.c61_anousu	 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $sql2 .= " where conplano.c60_codcon = $c60_codcon ";

        if ($c60_anousu != null) {
          $sql2 .= " where conplano.c60_codcon = $c60_codcon and c60_anousu=$c60_anousu";
        }
      } elseif ($c60_anousu != null) {
        $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql2 .= ($sql2 != "" ? " and " : " where ") . " c60_anousu=" . db_getsession("DB_anousu") . " and c61_instit = " . db_getsession("DB_instit");

    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  // funcao do sql
  function sql_query_file($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $sql2 .= " where conplano.c60_codcon = $c60_codcon ";
      }
      if ($c60_anousu != null) {
        if ($sql2 != "") {
          $sql2 .= " and ";
        } else {
          $sql2 .= " where ";
        }
        $sql2 .= " conplano.c60_anousu = $c60_anousu ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function db_verifica_conplano($conplano, $anousu)
  {

    $nivel = db_le_mae_conplano($conplano, true);
    if ($nivel == 1) {
      return true;
    }

    $cod_mae = db_le_mae_conplano($conplano, false);
    $this->sql_record($this->sql_query_file("", "", "c60_estrut", "", " c60_anousu=$anousu and  c60_estrut='$cod_mae'"));

    if ($this->numrows < 1) {

      $this->erro_msg = 'Procedimento abortado. Estrutural acima n�o encontrado!';
      return false;
    }
    if ($nivel == 9) {
      return true;
    }
    if ($nivel == 8) {
      $codigo = substr($conplano, 0, 9) . "00";
      $where = "substr(c60_estrut,1,11)='$codigo' and substr(c60_estrut,12,4)<>'0000' ";
    }
    if ($nivel == 7) {
      $codigo = substr($conplano, 0, 7) . "00";
      $where = "substr(c60_estrut,1,9)='$codigo' and substr(c60_estrut,10,6)<>'000000' ";
    }
    if ($nivel == 6) {
      $codigo = substr($conplano, 0, 5) . "00";
      $where = "substr(c60_estrut,1,7)='$codigo' and substr(c60_estrut,8,8)<>'00000000' ";
    }
    if ($nivel == 5) {
      $codigo = substr($conplano, 0, 5) . "0";
      $where = "substr(c60_estrut,1,5)='$codigo' and substr(c60_estrut,6,10)<>'0000000000' ";
    }
    if ($nivel == 4) {
      $codigo = substr($conplano, 0, 3) . "0";
      $where = "substr(c60_estrut,1,4)='$codigo' and substr(c60_estrut,5,11)<>'00000000000' ";
    }
    if ($nivel == 3) {
      $codigo = substr($conplano, 0, 2) . "0";
      $where = "substr(c60_estrut,1,3)='$codigo' and substr(c60_estrut,4,12)<>'000000000000' ";
    }
    if ($nivel == 2) {
      $codigo = substr($conplano, 0, 1) . "0";
      $where = "substr(c60_estrut,1,2)='$codigo' and substr(c60_estrut,3,13)<>'0000000000000' ";
    }
    // $result= $this->sql_record($this->sql_query_file("","","c60_estrut","",$where." and c60_anousu=$anousu "));
    // if($this->numrows>0){
    //     $this->erro_msg = 'Inclus�o abortada. Existe uma conta de n�vel inferior cadastrada!';
    //     return false;
    // }
    $this->erro_msg = 'Conplano v�lido!';
    return true;
  }
  function sql_query_geral($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= ",c60_tipolancamento,c60_subtipolancamento, c60_desdobramneto,c60_nregobrig";
    $sql .= " from conplano ";
    $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
    $sql .= "      left join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu = c60_anousu";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null && $c60_anousu != null) {
        $sql2 .= " where conplano.c60_codcon = $c60_codcon and c60_anousu=" . $c60_anousu;
      } else {
        $sql2 .= " where conplano.c60_anousu=" . db_getsession("DB_anousu");
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere  ";
    }
    $x      = @db_query("select prefeitura from db_config where codigo=" . db_getsession("DB_instit"));
    $libera = @pg_result($x, 0, 0);
    $dbw = '';
    if ($libera == "t") {
      //$dbw = " c61_instit is null or ";
    } else {
      //$sql2 .= ($sql2!=""?" and ":" where ") . " ( $dbw ( c61_instit is not null and c61_instit = " . db_getsession("DB_instit")." ))";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_vs_planocontas($c60_codcon = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from vs_planocontas ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $sql2 .= " where conplano.c60_codcon = $c60_codcon ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function db_verifica_conplano_exclusao($conplano, $anousu)
  {
    $nivel = db_le_mae_conplano($conplano, true);
    $cod_mae = db_le_mae_conplano($conplano, false);

    $this->erro_status = 1;
    if ($nivel == 9) {
      return true;
    }
    if ($nivel == 8) {
      $codigo = substr($conplano, 0, 11);
      $where = "substr(c60_estrut,1,11)='$codigo' and substr(c60_estrut,12,4)<>'0000' ";
    }
    if ($nivel == 7) {
      $codigo = substr($conplano, 0, 7);
      $where = "substr(c60_estrut,1,9)='$codigo' and substr(c60_estrut,10,6)<>'000000' ";
    }
    if ($nivel == 6) {
      $codigo = substr($conplano, 0, 7);
      $where = "substr(c60_estrut,1,7)='$codigo' and substr(c60_estrut,8,8)<>'00000000' ";
    }
    if ($nivel == 5) {
      $codigo = substr($conplano, 0, 5);
      $where = "substr(c60_estrut,1,5)='$codigo' and substr(c60_estrut,6,10)<>'0000000000' ";
    }
    if ($nivel == 4) {
      $codigo = substr($conplano, 0, 4);
      $where = "substr(c60_estrut,1,4)='$codigo' and substr(c60_estrut,5,11)<>'00000000000' ";
    }
    if ($nivel == 3) {
      $codigo = substr($conplano, 0, 3);
      $where = "substr(c60_estrut,1,3)='$codigo' and substr(c60_estrut,4,12)<>'000000000000' ";
    }
    if ($nivel == 2) {
      $codigo = substr($conplano, 0, 2);
      $where = "substr(c60_estrut,1,2)='$codigo' and substr(c60_estrut,3,13)<>'0000000000000' ";
    }
    if ($nivel == 1) {
      $codigo = substr($conplano, 0, 1);
      $where = "substr(c60_estrut,1,1)='$codigo' and substr(c60_estrut,2,14)<>'00000000000000' ";
    }
    $result = $this->sql_record($this->sql_query_file("", "", "c60_estrut", "", $where . " and c60_anousu=$anousu "));
    if ($this->numrows > 0) {
      $this->erro_status = 0;
      $this->erro_msg = 'Exclus�o abortada. Existe uma conta de n�vel inferior cadastrada!';
      return false;
    }
    $this->erro_msg = 'Conplano com  permiss�o de exclus�o!';
    $this->erro_status = 1;
    return true;
  }
  function sql_query_ele($c60_codcon = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c60_anousu=c61_anousu";
    $sql .= "      inner join conplanoexe  on  conplanoexe.c62_reduz   = conplanoreduz.c61_reduz and conplano.c60_anousu = conplanoreduz.c61_anousu";
    if (!USE_PCASP) {
      $sql .= "      inner join orcelemento  on  orcelemento.o56_codele     = conplano.c60_codcon and conplano.c60_anousu = orcelemento.o56_anousu";
    }
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $dbwhere = "  conplano.c60_codcon = $c60_codcon";
      }
    }
    if ($dbwhere != "") {
      $dbwhere .= " and ";
    }
    $sql2 .= " where $dbwhere  c62_anousu= " . db_getsession("DB_anousu");
    $sql2 .= " and c61_instit = " . db_getsession("DB_instit");

    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_reduz($c60_codcon = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c60_anousu=c61_anousu";
    $sql .= "      inner join conplanoexe     on  conplanoexe.c62_reduz   = conplanoreduz.c61_reduz and c61_anousu=c62_anousu";
    $sql .= "      inner join conclass  on  conclass.c51_codcla           = conplano.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis       = conplano.c60_codsis";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $dbwhere = "  conplano.c60_codcon = $c60_codcon";
      }
    }
    if ($dbwhere != "") {
      $dbwhere .= " and ";
    }
    $sql2 .= " where $dbwhere  c62_anousu= " . db_getsession("DB_anousu");
    $sql2 .= " and c61_instit = " . db_getsession("DB_instit");

    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query_tudo($c60_codcon = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c60_anousu=c61_anousu";
    $sql .= "      inner join conplanoexe     on  conplanoexe.c62_reduz   = conplanoreduz.c61_reduz and c61_anousu=c62_anousu";
    $sql .= "      inner join conclass  on  conclass.c51_codcla           = conplano.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis       = conplano.c60_codsis";
    $sql .= "      left join conplanocontacorrente on c60_codcon = c18_codcon and c60_anousu=c18_anousu";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $dbwhere = "  conplano.c60_codcon = $c60_codcon";
      }
    }
    if ($dbwhere != "") {
      $dbwhere .= " and ";
    }
    $sql2 .= " where $dbwhere  c62_anousu= " . db_getsession("DB_anousu");
    $sql2 .= " and c61_instit = " . db_getsession("DB_instit");

    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  function sql_query2($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
    $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu=c60_anousu ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null) {
        $sql2 .= " where conplano.c60_codcon = $c60_codcon ";
        if ($c60_anousu != null) {
          $sql2 .= "  and c60_anousu=$c60_anousu";
        }
      } elseif ($c60_anousu != null) {
        $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql2 .= ($sql2 != "" ? " and " : " where ") . " c60_anousu=" . db_getsession("DB_anousu") . " and c61_instit = " . db_getsession("DB_instit");

    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  /**
   * Busca Plano de Contas
   * @param Integer $c60_anousu
   * @param String $campos
   * @param String $ordem
   * @param String $dbwhere
   * @return string
   */
  function sql_query_planocontas($c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano                                                             ";
    $sql .= "      left join conplanoreduz on conplano.c60_codcon       =  conplanoreduz.c61_codcon  ";
    $sql .= "                             and conplano.c60_anousu       = conplanoreduz.c61_anousu  ";
    //$sql .= "                             and conplanoreduz.c61_instit  = ".db_getsession("DB_instit");
    $sql .= "      left join conplanoconta on conplanoreduz.c61_codcon = conplanoconta.c63_codcon  ";
    $sql .= "                             and conplanoreduz.c61_anousu  = conplanoconta.c63_anousu                        ";
    $sql .= "      left join orcfontes     on conplanoreduz.c61_anousu  = orcfontes.o57_anousu      ";
    $sql .= "                             and conplanoreduz.c61_codcon = orcfontes.o57_codfon                             ";
    $sql .= "      left join orcelemento   on conplanoreduz.c61_anousu  = orcelemento.o56_anousu    ";
    $sql .= "                             and conplanoreduz.c61_codcon = orcelemento.o56_codele                           ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_anousu != null) {
        $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  /**
   *
   * Busca o Plano PCASP
   * @return string
   */
  function sql_query_dados_plano($c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano                                                                                           ";
    $sql .= "      left join conplanoreduz         on conplano.c60_codcon         =  conplanoreduz.c61_codcon         ";
    $sql .= "                                     and conplano.c60_anousu         = conplanoreduz.c61_anousu          ";
    $sql .= "      left join conplanoconta         on conplano.c60_codcon         = conplanoconta.c63_codcon          ";
    $sql .= "                                     and conplano.c60_anousu         = conplanoconta.c63_anousu          ";
    $sql .= "      left join conplanocontabancaria on conplano.c60_codcon         = conplanocontabancaria.c56_codcon  ";
    $sql .= "                                     and conplano.c60_anousu         = conplanocontabancaria.c56_anousu  ";
    $sql .= "      inner join conclass             on conplano.c60_codcla         = conclass.c51_codcla               ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_anousu != null) {
        $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_pcasp_orcamento_analitico($c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano                                                                                           ";
    $sql .= "      inner join conplanoreduz              on conplano.c60_codcon         =  conplanoreduz.c61_codcon         ";
    $sql .= "                                           and conplano.c60_anousu         = conplanoreduz.c61_anousu          ";
    $sql .= "      inner join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_conplano = conplano.c60_codcon ";
    $sql .= "                                           and conplanoconplanoorcamento.c72_anousu   = conplano.c60_anousu ";
    $sql .= "      inner join conplanoorcamento          on conplanoorcamento.c60_codcon           = conplanoconplanoorcamento.c72_conplanoorcamento";
    $sql .= "                                           and conplanoorcamento.c60_anousu           = conplanoconplanoorcamento.c72_anousu";
    $sql .= "      inner join conplanoorcamentoanalitica on conplanoorcamentoanalitica.c61_codcon  = conplanoorcamento.c60_codcon";
    $sql .= "                                           and conplanoorcamentoanalitica.c61_anousu  = conplanoorcamento.c60_anousu ";


    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_anousu != null) {
        $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  function sql_query_dados_banco($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conplano ";
    $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
    $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
    $sql .= "      left join conplanoreduz  on  conplanoreduz.c61_codcon = conplano.c60_codcon and c61_anousu = c60_anousu";
    $sql .= "      left join conplanoconta  on  conplanoconta.c63_codcon = conplano.c60_codcon and c63_anousu = c60_anousu";
    $sql .= "      left join conplanocontabancaria  on c56_codcon = conplano.c60_codcon and c56_anousu = c60_anousu";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c60_codcon != null && $c60_anousu != null) {
        $sql2 .= " where conplano.c60_codcon = $c60_codcon and c60_anousu=" . $c60_anousu;
      } else {
        $sql2 .= " where conplano.c60_anousu=" . db_getsession("DB_anousu");
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere  ";
    }
    $x      = @db_query("select prefeitura from db_config where codigo=" . db_getsession("DB_instit"));
    $libera = @pg_result($x, 0, 0);
    $dbw = '';
    if ($libera == "t") {
      //$dbw = " c61_instit is null or ";
    } else {
      //$sql2 .= ($sql2!=""?" and ":" where ") . " ( $dbw ( c61_instit is not null and c61_instit = " . db_getsession("DB_instit")." ))";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
