<?php
//MODULO: sicom
//CLASSE DA ENTIDADE ctb202025
class cl_ctb202025
{
  // cria variaveis de erro
  var $rotulo = null;
  var $query_sql = null;
  var $numrows = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql = null;
  var $erro_banco = null;
  var $erro_msg = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $si96_sequencial = 0;
  var $si96_tiporegistro = 0;
  var $si96_codorgao = null;
  var $si96_codctb = 0;
  var $si96_codfontrecursos = 0;
  var $si96_saldocec = 0;
  var $si96_vlsaldoinicialfonte = 0;
  var $si96_vlsaldofinalfonte = 0;
  var $si96_mes = 0;
  var $si96_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si96_sequencial = int8 = sequencial
                 si96_tiporegistro = int8 = Tipo do  registro
                 si96_codorgao = varchar(2) = C�digo do �rg�o
                 si96_codctb = int8 = C�digo Identificador da Conta Banc�ria
                 si96_codfontrecursos = int8 = C�digo da fonte de recursos
                 si96_saldocec = int8 = Saldo comp�e ou n�o comp�e Caixa e Equivalentes de Caixa
                 si96_vlsaldoinicialfonte = float8 = Valor do Saldo do  In�cio do M�s
                 si96_vlsaldofinalfonte = float8 = Valor do Saldo do  Final do M�s
                 si96_mes = int8 = M�s
                 si96_instit = int8 = Institui��o
                 ";

  //funcao construtor da classe
  function cl_ctb202025()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("ctb202025");
    $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
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
      $this->si96_sequencial = ($this->si96_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_sequencial"] : $this->si96_sequencial);
      $this->si96_tiporegistro = ($this->si96_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_tiporegistro"] : $this->si96_tiporegistro);
      $this->si96_codorgao = ($this->si96_codorgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_codorgao"] : $this->si96_codorgao);
      $this->si96_codctb = ($this->si96_codctb == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_codctb"] : $this->si96_codctb);
      $this->si96_codfontrecursos = ($this->si96_codfontrecursos == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_codfontrecursos"] : $this->si96_codfontrecursos);
      $this->si96_saldocec = ($this->si96_saldocec == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_saldocec"] : $this->si96_saldocec);
      $this->si96_vlsaldoinicialfonte = ($this->si96_vlsaldoinicialfonte == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_vlsaldoinicialfonte"] : $this->si96_vlsaldoinicialfonte);
      $this->si96_vlsaldofinalfonte = ($this->si96_vlsaldofinalfonte == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_vlsaldofinalfonte"] : $this->si96_vlsaldofinalfonte);
      $this->si96_mes = ($this->si96_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_mes"] : $this->si96_mes);
      $this->si96_instit = ($this->si96_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_instit"] : $this->si96_instit);
    } else {
      $this->si96_sequencial = ($this->si96_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si96_sequencial"] : $this->si96_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si96_sequencial)
  {
    $this->atualizacampos();
    if ($this->si96_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si96_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si96_codctb == null) {
      $this->si96_codctb = "0";
    }
    if ($this->si96_codfontrecursos == null) {
      $this->si96_codfontrecursos = "0";
    }
    if ($this->si96_saldocec == null) {
      $this->si96_saldocec = "0";
    }
    if ($this->si96_vlsaldoinicialfonte == null) {
      $this->si96_vlsaldoinicialfonte = "0";
    }
    if ($this->si96_vlsaldofinalfonte == null) {
      $this->si96_vlsaldofinalfonte = "0";
    }
    if ($this->si96_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si96_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si96_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si96_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si96_sequencial == "" || $si96_sequencial == null) {
      $result = db_query("select nextval('ctb202025_si96_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: ctb202025_si96_sequencial_seq do campo: si96_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si96_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from ctb202025_si96_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si96_sequencial)) {
        $this->erro_sql = " Campo si96_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si96_sequencial = $si96_sequencial;
      }
    }
    if (($this->si96_sequencial == null) || ($this->si96_sequencial == "")) {
      $this->erro_sql = " Campo si96_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into ctb202025(
                                       si96_sequencial
                                      ,si96_tiporegistro
                                      ,si96_codorgao
                                      ,si96_codctb
                                      ,si96_codfontrecursos
                                      ,si96_saldocec
                                      ,si96_vlsaldoinicialfonte
                                      ,si96_vlsaldofinalfonte
                                      ,si96_mes
                                      ,si96_instit
                       )
                values (
                                $this->si96_sequencial
                               ,$this->si96_tiporegistro
                               ,'$this->si96_codorgao'
                               ,$this->si96_codctb
                               ,$this->si96_codfontrecursos
                               ,$this->si96_saldocec
                               ,$this->si96_vlsaldoinicialfonte
                               ,$this->si96_vlsaldofinalfonte
                               ,$this->si96_mes
                               ,$this->si96_instit
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "ctb202025 ($this->si96_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "ctb202025 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "ctb202025 ($this->si96_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si96_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
//    $resaco = $this->sql_record($this->sql_query_file($this->si96_sequencial));
//    if (($resaco != false) || ($this->numrows != 0)) {
//      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//      $acount = pg_result($resac, 0, 0);
//      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//      $resac = db_query("insert into db_acountkey values($acount,2010562,'$this->si96_sequencial','I')");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010562,'','" . AddSlashes(pg_result($resaco, 0, 'si96_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010563,'','" . AddSlashes(pg_result($resaco, 0, 'si96_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2011319,'','" . AddSlashes(pg_result($resaco, 0, 'si96_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010564,'','" . AddSlashes(pg_result($resaco, 0, 'si96_codctb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010565,'','" . AddSlashes(pg_result($resaco, 0, 'si96_codfontrecursos')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010566,'','" . AddSlashes(pg_result($resaco, 0, 'si96_vlsaldoinicialfonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010567,'','" . AddSlashes(pg_result($resaco, 0, 'si96_vlsaldofinalfonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2010568,'','" . AddSlashes(pg_result($resaco, 0, 'si96_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010325,2011608,'','" . AddSlashes(pg_result($resaco, 0, 'si96_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//    }

    return true;
  }

  // funcao para alteracao
  function alterar($si96_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update ctb202025 set ";
    $virgula = "";
    if (trim($this->si96_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_sequencial"])) {
      if (trim($this->si96_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si96_sequencial"])) {
        $this->si96_sequencial = "0";
      }
      $sql .= $virgula . " si96_sequencial = $this->si96_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si96_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_tiporegistro"])) {
      $sql .= $virgula . " si96_tiporegistro = $this->si96_tiporegistro ";
      $virgula = ",";
      if (trim($this->si96_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si96_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si96_codorgao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_codorgao"])) {
      $sql .= $virgula . " si96_codorgao = '$this->si96_codorgao' ";
      $virgula = ",";
    }
    if (trim($this->si96_codctb) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_codctb"])) {
      if (trim($this->si96_codctb) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si96_codctb"])) {
        $this->si96_codctb = "0";
      }
      $sql .= $virgula . " si96_codctb = $this->si96_codctb ";
      $virgula = ",";
    }
    if (trim($this->si96_codfontrecursos) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_codfontrecursos"])) {
      if (trim($this->si96_codfontrecursos) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si96_codfontrecursos"])) {
        $this->si96_codfontrecursos = "0";
      }
      $sql .= $virgula . " si96_codfontrecursos = $this->si96_codfontrecursos ";
      $virgula = ",";
    }
    if (trim($this->si96_saldocec) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_saldocec"])) {
      if (trim($this->si96_saldocec) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si96_saldocec"])) {
        $this->si96_saldocec = "0";
      }
      $sql .= $virgula . " si96_saldocec = $this->si96_saldocec ";
      $virgula = ",";
    }
    if (trim($this->si96_vlsaldoinicialfonte) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_vlsaldoinicialfonte"])) {
      if (trim($this->si96_vlsaldoinicialfonte) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si96_vlsaldoinicialfonte"])) {
        $this->si96_vlsaldoinicialfonte = "0";
      }
      $sql .= $virgula . " si96_vlsaldoinicialfonte = $this->si96_vlsaldoinicialfonte ";
      $virgula = ",";
    }
    if (trim($this->si96_vlsaldofinalfonte) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_vlsaldofinalfonte"])) {
      if (trim($this->si96_vlsaldofinalfonte) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si96_vlsaldofinalfonte"])) {
        $this->si96_vlsaldofinalfonte = "0";
      }
      $sql .= $virgula . " si96_vlsaldofinalfonte = $this->si96_vlsaldofinalfonte ";
      $virgula = ",";
    }
    if (trim($this->si96_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_mes"])) {
      $sql .= $virgula . " si96_mes = $this->si96_mes ";
      $virgula = ",";
      if (trim($this->si96_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si96_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si96_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si96_instit"])) {
      $sql .= $virgula . " si96_instit = $this->si96_instit ";
      $virgula = ",";
      if (trim($this->si96_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si96_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si96_sequencial != null) {
      $sql .= " si96_sequencial = $this->si96_sequencial";
    }
//    $resaco = $this->sql_record($this->sql_query_file($this->si96_sequencial));
//    if ($this->numrows > 0) {
//      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2010562,'$this->si96_sequencial','A')");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_sequencial"]) || $this->si96_sequencial != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010562,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_sequencial')) . "','$this->si96_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_tiporegistro"]) || $this->si96_tiporegistro != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010563,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_tiporegistro')) . "','$this->si96_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_codorgao"]) || $this->si96_codorgao != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2011319,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_codorgao')) . "','$this->si96_codorgao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_codctb"]) || $this->si96_codctb != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010564,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_codctb')) . "','$this->si96_codctb'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_codfontrecursos"]) || $this->si96_codfontrecursos != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010565,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_codfontrecursos')) . "','$this->si96_codfontrecursos'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_vlsaldoinicialfonte"]) || $this->si96_vlsaldoinicialfonte != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010566,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_vlsaldoinicialfonte')) . "','$this->si96_vlsaldoinicialfonte'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_vlsaldofinalfonte"]) || $this->si96_vlsaldofinalfonte != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010567,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_vlsaldofinalfonte')) . "','$this->si96_vlsaldofinalfonte'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_mes"]) || $this->si96_mes != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2010568,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_mes')) . "','$this->si96_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si96_instit"]) || $this->si96_instit != "") {
//          $resac = db_query("insert into db_acount values($acount,2010325,2011608,'" . AddSlashes(pg_result($resaco, $conresaco, 'si96_instit')) . "','$this->si96_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        }
//      }
//    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ctb202025 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si96_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ctb202025 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si96_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si96_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si96_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si96_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
//    if (($resaco != false) || ($this->numrows != 0)) {
//      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2010562,'$si96_sequencial','E')");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010562,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010563,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2011319,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010564,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_codctb')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010565,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_codfontrecursos')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010566,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_vlsaldoinicialfonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010567,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_vlsaldofinalfonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2010568,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010325,2011608,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si96_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
    $sql = " delete from ctb202025
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si96_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si96_sequencial = $si96_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ctb202025 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si96_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ctb202025 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si96_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si96_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
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
      $this->numrows = 0;
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $this->numrows = pg_numrows($result);

    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:ctb202025";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      //return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si96_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ctb202025 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si96_sequencial != null) {
        $sql2 .= " where ctb202025.si96_sequencial = $si96_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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

    return $sql;
  }

  // funcao do sql
  function sql_query_file($si96_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ctb202025 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si96_sequencial != null) {
        $sql2 .= " where ctb202025.si96_sequencial = $si96_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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

    return $sql;
  }

  public function sql_Reg20Fonte($codctb, $ano, $mes, $codtce = false): string
  {
        $instit = db_getsession("DB_instit");

        $sqlAnd = "c61_reduz = $codctb ";
        $orderBy = "ORDER BY c61_reduz, 6";

        if ($codtce) {
            $sqlAnd = "c61_codtce = $codctb";
            $orderBy = "ORDER BY c61_codtce, 6";
        }

        return "WITH registro20 AS (
                SELECT c19_sequencial,
                       c61_reduz,
                       c61_codtce,
                       c61_codcon,
                       c61_codigo,
                       o15_codigo,
                       o15_codtri,
                       c61_instit,
                       fc_saldocontacorrente($ano, c19_sequencial, 103, $mes, c61_instit)
                FROM conplanoexe
                INNER JOIN conplanoreduz ON c61_anousu = c62_anousu AND c61_reduz = c62_reduz
                INNER JOIN conplano ON c61_codcon = c60_codcon AND c61_anousu = c60_anousu AND c60_codsis = 6
                INNER JOIN contacorrentedetalhe ON c19_conplanoreduzanousu = c61_anousu AND c19_reduz = c61_reduz
                LEFT JOIN orctiporec ON c19_orctiporec = o15_codigo
                WHERE c61_instit = $instit
                  AND c62_anousu = $ano
                  AND " . $sqlAnd . "
                " . $orderBy . "
            )
            SELECT c19_sequencial,
                   o15_codigo                                                      AS fontemovimento,
                   round(substr(fc_saldocontacorrente, 43, 15)::float8, 2)::float8 AS saldoinicial,
                   substr(fc_saldocontacorrente, 107, 1)::varchar(1)               AS nat_vlr_si,
                   round(substr(fc_saldocontacorrente, 59, 15)::float8, 2)::float8 AS debito,
                   round(substr(fc_saldocontacorrente, 75, 15)::float8, 2)::float8 AS credito,
                   round(substr(fc_saldocontacorrente, 91, 15)::float8, 2)::float8 AS saldofinal,
                   substr(fc_saldocontacorrente, 111, 1)::varchar(1)               AS nat_vlr_sf,
                   o15_codtri,
                   c61_reduz,
                   c61_codtce
            FROM registro20";
  }

  /**
   * @param $iAnoUsu
   * @param $instituicao
   * @param $oConta
   * @param $sDataInicial
   * @param $sDataFinal
   * @return string
   */
  public function saldosBalancete($iAnoUsu, $instituicao, $oConta, $sDataInicial, $sDataFinal): string
  {
    return " SELECT c61_reduz,
                    c61_codtce,
                    c61_codigo,
                    c61_instit,
                    round(substr(fc_planosaldonovo, 3, 14)::float8, 2)::float8  AS saldo_anterior,
                    round(substr(fc_planosaldonovo, 17, 14)::float8, 2)::float8 AS saldo_anterior_debito,
                    round(substr(fc_planosaldonovo, 31, 14)::float8, 2)::float8 AS saldo_anterior_credito,
                    round(substr(fc_planosaldonovo, 45, 14)::float8, 2)::float8 AS saldo_final,
                    substr(fc_planosaldonovo, 59, 1)::varchar(1)                AS sinal_anterior,
                    substr(fc_planosaldonovo, 60, 1)::varchar(1)                AS sinal_final
             FROM (SELECT c61_reduz,
                          c61_codtce,
                          c61_codigo,
                          c61_instit,
                          fc_planosaldonovo(2025, c61_reduz, '$sDataInicial', '$sDataFinal', false)
                   FROM conplanoexe
                   INNER JOIN conplanoreduz ON c61_anousu = c62_anousu AND c61_reduz = c62_reduz
                   INNER JOIN conplano p ON c61_codcon = c60_codcon AND c61_anousu = c60_anousu AND c60_codsis = 6
                   WHERE c62_anousu = $iAnoUsu
                     AND c61_instit = $instituicao
                     AND (c61_reduz = $oConta->codctb OR c61_codtce = $oConta->codctb)) AS x
             ORDER BY c61_reduz";
  }
}
