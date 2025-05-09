<?
//MODULO: sicom
//CLASSE DA ENTIDADE aoc112025
class cl_aoc112025
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
  var $si39_sequencial = 0;
  var $si39_tiporegistro = 0;
  var $si39_codreduzidodecreto = 0;
  var $si39_nrodecreto = 0;
  var $si39_tipodecretoalteracao = 0;
  var $si39_justificativa = null;
  var $si39_valoraberto = 0;
  var $si39_mes = 0;
  var $si39_reg10 = 0;
  var $si39_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si39_sequencial = int8 = sequencial
                 si39_tiporegistro = int8 = Tipo do registro
                 si39_codreduzidodecreto = int8 = C�digo do decreto
                 si39_nrodecreto = int8 = N�mero do Decreto
                 si39_tipodecretoalteracao = int8 = Tipo de Decreto
                 si39_justificativa = text = Justificativa
                 si39_valoraberto = float8 = Valor aberto
                 si39_mes = int8 = M�s
                 si39_reg10 = int8 = reg10
                 si39_instit = int8 = Institui��o
                 ";

  //funcao construtor da classe
  function cl_aoc112025()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("aoc112025");
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
      $this->si39_sequencial = ($this->si39_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_sequencial"] : $this->si39_sequencial);
      $this->si39_tiporegistro = ($this->si39_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_tiporegistro"] : $this->si39_tiporegistro);
      $this->si39_codreduzidodecreto = ($this->si39_codreduzidodecreto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_codreduzidodecreto"] : $this->si39_codreduzidodecreto);
      $this->si39_nrodecreto = ($this->si39_nrodecreto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_nrodecreto"] : $this->si39_nrodecreto);
      $this->si39_tipodecretoalteracao = ($this->si39_tipodecretoalteracao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_tipodecretoalteracao"] : $this->si39_tipodecretoalteracao);
      $this->si39_justificativa = ($this->si39_justificativa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_justificativa"] : $this->si39_justificativa);
      $this->si39_valoraberto = ($this->si39_valoraberto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_valoraberto"] : $this->si39_valoraberto);
      $this->si39_mes = ($this->si39_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_mes"] : $this->si39_mes);
      $this->si39_reg10 = ($this->si39_reg10 == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_reg10"] : $this->si39_reg10);
      $this->si39_instit = ($this->si39_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_instit"] : $this->si39_instit);
    } else {
      $this->si39_sequencial = ($this->si39_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si39_sequencial"] : $this->si39_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si39_sequencial)
  {
    $this->atualizacampos();
    if ($this->si39_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do registro nao Informado.";
      $this->erro_campo = "si39_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si39_codreduzidodecreto == null) {
      $this->si39_codreduzidodecreto = "0";
    }
    if ($this->si39_nrodecreto == null) {
      $this->si39_nrodecreto = "0";
    }
    if ($this->si39_tipodecretoalteracao == null) {
      $this->si39_tipodecretoalteracao = "0";
    }
    if ($this->si39_valoraberto == null) {
      $this->si39_valoraberto = "0";
    }
    if ($this->si39_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si39_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si39_reg10 == null) {
      $this->si39_reg10 = "0";
    }
    if ($this->si39_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si39_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si39_sequencial == "" || $si39_sequencial == null) {
      $result = db_query("select nextval('aoc112025_si39_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: aoc112025_si39_sequencial_seq do campo: si39_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si39_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from aoc112025_si39_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si39_sequencial)) {
        $this->erro_sql = " Campo si39_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si39_sequencial = $si39_sequencial;
      }
    }
    if (($this->si39_sequencial == null) || ($this->si39_sequencial == "")) {
      $this->erro_sql = " Campo si39_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into aoc112025(
                                       si39_sequencial
                                      ,si39_tiporegistro
                                      ,si39_codreduzidodecreto
                                      ,si39_nrodecreto
                                      ,si39_tipodecretoalteracao
                                      ,si39_justificativa
                                      ,si39_valoraberto
                                      ,si39_mes
                                      ,si39_reg10
                                      ,si39_instit
                       )
                values (
                                $this->si39_sequencial
                               ,$this->si39_tiporegistro
                               ,$this->si39_codreduzidodecreto
                               ,'$this->si39_nrodecreto'
                               ,$this->si39_tipodecretoalteracao
                               ,'$this->si39_justificativa'
                               ,$this->si39_valoraberto
                               ,$this->si39_mes
                               ,$this->si39_reg10
                               ,$this->si39_instit
                      )";

    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "aoc112025 ($this->si39_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "aoc112025 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "aoc112025 ($this->si39_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si39_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si39_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2009785,'$this->si39_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010268,2009785,'','" . AddSlashes(pg_result($resaco, 0, 'si39_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009786,'','" . AddSlashes(pg_result($resaco, 0, 'si39_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009787,'','" . AddSlashes(pg_result($resaco, 0, 'si39_codreduzidodecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009788,'','" . AddSlashes(pg_result($resaco, 0, 'si39_nrodecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009789,'','" . AddSlashes(pg_result($resaco, 0, 'si39_tipodecretoalteracao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009790,'','" . AddSlashes(pg_result($resaco, 0, 'si39_valoraberto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009791,'','" . AddSlashes(pg_result($resaco, 0, 'si39_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2009792,'','" . AddSlashes(pg_result($resaco, 0, 'si39_reg10')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010268,2011553,'','" . AddSlashes(pg_result($resaco, 0, 'si39_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }

    return true;
  }

  // funcao para alteracao
  function alterar($si39_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update aoc112025 set ";
    $virgula = "";
    if (trim($this->si39_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_sequencial"])) {
      if (trim($this->si39_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si39_sequencial"])) {
        $this->si39_sequencial = "0";
      }
      $sql .= $virgula . " si39_sequencial = $this->si39_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si39_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_tiporegistro"])) {
      $sql .= $virgula . " si39_tiporegistro = $this->si39_tiporegistro ";
      $virgula = ",";
      if (trim($this->si39_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do registro nao Informado.";
        $this->erro_campo = "si39_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si39_codreduzidodecreto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_codreduzidodecreto"])) {
      if (trim($this->si39_codreduzidodecreto) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si39_codreduzidodecreto"])) {
        $this->si39_codreduzidodecreto = "0";
      }
      $sql .= $virgula . " si39_codreduzidodecreto = $this->si39_codreduzidodecreto ";
      $virgula = ",";
    }
    if (trim($this->si39_nrodecreto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_nrodecreto"])) {
      if (trim($this->si39_nrodecreto) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si39_nrodecreto"])) {
        $this->si39_nrodecreto = "0";
      }
      $sql .= $virgula . " si39_nrodecreto = $this->si39_nrodecreto ";
      $virgula = ",";
    }
    if (trim($this->si39_tipodecretoalteracao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_tipodecretoalteracao"])) {
      if (trim($this->si39_tipodecretoalteracao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si39_tipodecretoalteracao"])) {
        $this->si39_tipodecretoalteracao = "0";
      }
      $sql .= $virgula . " si39_tipodecretoalteracao = $this->si39_tipodecretoalteracao ";
      $virgula = ",";
    }

    $sql .= $virgula . " si39_justificativa = '$this->si39_justificativa' ";
    $virgula = ",";

    if (trim($this->si39_valoraberto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_valoraberto"])) {
      if (trim($this->si39_valoraberto) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si39_valoraberto"])) {
        $this->si39_valoraberto = "0";
      }
      $sql .= $virgula . " si39_valoraberto = $this->si39_valoraberto ";
      $virgula = ",";
    }
    if (trim($this->si39_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_mes"])) {
      $sql .= $virgula . " si39_mes = $this->si39_mes ";
      $virgula = ",";
      if (trim($this->si39_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si39_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si39_reg10) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_reg10"])) {
      if (trim($this->si39_reg10) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si39_reg10"])) {
        $this->si39_reg10 = "0";
      }
      $sql .= $virgula . " si39_reg10 = $this->si39_reg10 ";
      $virgula = ",";
    }
    if (trim($this->si39_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si39_instit"])) {
      $sql .= $virgula . " si39_instit = $this->si39_instit ";
      $virgula = ",";
      if (trim($this->si39_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si39_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si39_sequencial != null) {
      $sql .= " si39_sequencial = $this->si39_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si39_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2009785,'$this->si39_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_sequencial"]) || $this->si39_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009785,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_sequencial')) . "','$this->si39_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_tiporegistro"]) || $this->si39_tiporegistro != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009786,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_tiporegistro')) . "','$this->si39_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_codreduzidodecreto"]) || $this->si39_codreduzidodecreto != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009787,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_codreduzidodecreto')) . "','$this->si39_codreduzidodecreto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_nrodecreto"]) || $this->si39_nrodecreto != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009788,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_nrodecreto')) . "','$this->si39_nrodecreto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_tipodecretoalteracao"]) || $this->si39_tipodecretoalteracao != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009789,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_tipodecretoalteracao')) . "','$this->si39_tipodecretoalteracao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_valoraberto"]) || $this->si39_valoraberto != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009790,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_valoraberto')) . "','$this->si39_valoraberto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_mes"]) || $this->si39_mes != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009791,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_mes')) . "','$this->si39_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_reg10"]) || $this->si39_reg10 != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2009792,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_reg10')) . "','$this->si39_reg10'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si39_instit"]) || $this->si39_instit != "")
          $resac = db_query("insert into db_acount values($acount,2010268,2011553,'" . AddSlashes(pg_result($resaco, $conresaco, 'si39_instit')) . "','$this->si39_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "aoc112025 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si39_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "aoc112025 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si39_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si39_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si39_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si39_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2009785,'$si39_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010268,2009785,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009786,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009787,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_codreduzidodecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009788,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_nrodecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009789,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_tipodecretoalteracao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009790,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_valoraberto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009791,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2009792,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_reg10')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010268,2011553,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si39_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from aoc112025
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si39_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si39_sequencial = $si39_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "aoc112025 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si39_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "aoc112025 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si39_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si39_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:aoc112025";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si39_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from aoc112025 ";
    $sql .= "      left  join aoc102020  on  aoc102020.si38_sequencial = aoc112025.si39_reg10";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si39_sequencial != null) {
        $sql2 .= " where aoc112025.si39_sequencial = $si39_sequencial ";
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

  // funcao do sql
    function sql_query_file($si39_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from aoc112025 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si39_sequencial != null) {
        $sql2 .= " where aoc112025.si39_sequencial = $si39_sequencial ";
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

    /**
     * @SICOM AOC112025
     *
     * @param $oDados10
     * @return string
     */
    public function sqlReg11($oDados10): string
    {
        return "SELECT '11' AS tiporegistro,
                    o46_codlei AS codreduzidodecreto,
                    o39_numero AS nrodecreto,
                    (CASE
                    WHEN o46_tiposup IN (1006, 1007, 1008, 1009, 1010, 1012) THEN 2
                    WHEN o46_tiposup IN (1001, 1002, 1003, 1004, 1005,1026,1027,1028,1029) THEN 1
                    WHEN o46_tiposup = 1012 THEN 6
                    WHEN o46_tiposup = 1013 THEN 7
                    WHEN o46_tiposup = 1014 THEN 9
                    WHEN o46_tiposup = 1015 THEN 10
                    WHEN o46_tiposup = 1016 THEN 8
                    WHEN o46_tiposup = 1017 THEN 5
                    WHEN o46_tiposup IN (1011, 1018, 1019, 2026) THEN 4
                    WHEN o46_tiposup = 1020 THEN 12
                    WHEN o46_tiposup = 1021 THEN 14
                    WHEN o46_tiposup = 1022 THEN 15
                    WHEN o46_tiposup IN (1023, 1024, 1025) THEN 11
                    END ) AS tipoDecretoAlteracao,
                    o39_justi as justificativa,
                    sum(o47_valor) AS valorAberto
                FROM orcsuplem
                    JOIN orcsuplemval ON o47_codsup = o46_codsup
                    JOIN orcprojeto ON o46_codlei = o39_codproj
                    JOIN orcsuplemtipo ON o46_tiposup = o48_tiposup
                    JOIN orcsuplemlan ON o49_codsup=o46_codsup AND o49_data IS NOT NULL
                WHERE o47_valor > 0
                    AND o46_codlei IN ({$oDados10->codigovinc})
                GROUP BY o46_codlei, o39_numero, o46_tiposup, o39_justi";
    }
}
