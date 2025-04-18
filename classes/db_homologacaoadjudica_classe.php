<?
//MODULO: licitacao
//CLASSE DA ENTIDADE homologacaoadjudica
class cl_homologacaoadjudica
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
    var $l202_sequencial = 0;
    var $l202_licitacao = 0;
    var $l202_datahomologacao_dia = null;
    var $l202_datahomologacao_mes = null;
    var $l202_datahomologacao_ano = null;
    var $l202_datahomologacao = null;
    var $l202_dataadjudicacao_dia = null;
    var $l202_dataadjudicacao_mes = null;
    var $l202_dataadjudicacao_ano = null;
    var $l202_dataadjudicacao = null;
    var $l202_datareferencia_dia = null;
    var $l202_datareferencia_mes = null;
    var $l202_datareferencia_ano = null;
    var $l202_datareferencia = null;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 l202_sequencial = int4 = Sequencial
                 l202_licitacao = int4 = Licita��o
                 l202_datahomologacao = date = Data Homologa��o
                 l202_dataadjudicacao = date = Data Adjudica��o
                 l202_datareferencia = date = Data de Refer�ncia
                 ";
    //funcao construtor da classe
    function cl_homologacaoadjudica()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("homologacaoadjudica");
        $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
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
            $this->l202_sequencial = ($this->l202_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_sequencial"] : $this->l202_sequencial);
            $this->l202_licitacao = ($this->l202_licitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_licitacao"] : $this->l202_licitacao);
            if ($this->l202_datahomologacao == "") {
                $this->l202_datahomologacao_dia = ($this->l202_datahomologacao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao_dia"] : $this->l202_datahomologacao_dia);
                $this->l202_datahomologacao_mes = ($this->l202_datahomologacao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao_mes"] : $this->l202_datahomologacao_mes);
                $this->l202_datahomologacao_ano = ($this->l202_datahomologacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao_ano"] : $this->l202_datahomologacao_ano);
                if ($this->l202_datahomologacao_dia != "") {
                    $this->l202_datahomologacao = $this->l202_datahomologacao_ano . "-" . $this->l202_datahomologacao_mes . "-" . $this->l202_datahomologacao_dia;
                }
            }
            if ($this->l202_dataadjudicacao == "") {
                $this->l202_dataadjudicacao_dia = ($this->l202_dataadjudicacao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_dia"] : $this->l202_dataadjudicacao_dia);
                $this->l202_dataadjudicacao_mes = ($this->l202_dataadjudicacao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_mes"] : $this->l202_dataadjudicacao_mes);
                $this->l202_dataadjudicacao_ano = ($this->l202_dataadjudicacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_ano"] : $this->l202_dataadjudicacao_ano);
                if ($this->l202_dataadjudicacao_dia != "") {
                    $this->l202_dataadjudicacao = $this->l202_dataadjudicacao_ano . "-" . $this->l202_dataadjudicacao_mes . "-" . $this->l202_dataadjudicacao_dia;
                }
            }
            if ($this->l202_datareferencia == "") {
                $this->l202_datareferencia_dia = ($this->l202_datareferencia_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_datareferencia_dia"] : $this->l202_datareferencia_dia);
                $this->l202_datareferencia_mes = ($this->l202_datareferencia_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_datareferencia_mes"] : $this->l202_datareferencia_mes);
                $this->l202_datareferencia_ano = ($this->l202_dataadjudicacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_ano"] : $this->l202_dataadjudicacao_ano);
                if ($this->l202_datareferencia_dia != "") {
                    $this->l202_datareferencia = $this->l202_datareferencia_ano . "-" . $this->l202_datareferencia_mes . "-" . $this->l202_datareferencia_dia;
                }
            }
        } else {
            $this->l202_sequencial = ($this->l202_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l202_sequencial"] : $this->l202_sequencial);
        }
    }
    // funcao para inclusao
    function incluir($l202_sequencial)
    {
        $this->atualizacampos();
        if ($this->l202_licitacao == null) {
            $this->erro_sql = " Campo Licita��o nao Informado.";
            $this->erro_campo = "l202_licitacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($l202_sequencial == "" || $l202_sequencial == null) {
            $result = db_query("select nextval('homologacaoadjudica_l202_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: homologacaoadjudica_l202_sequencial_seq do campo: l202_sequencial";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->l202_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from homologacaoadjudica_l202_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $l202_sequencial)) {
                $this->erro_sql = " Campo l202_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->l202_sequencial = $l202_sequencial;
            }
        }
        if (($this->l202_sequencial == null) || ($this->l202_sequencial == "")) {
            $this->erro_sql = " Campo l202_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into homologacaoadjudica(
                                       l202_sequencial
                                      ,l202_licitacao
                                      ,l202_datahomologacao
                                      ,l202_dataadjudicacao
                                      ,l202_datareferencia
                       )
                values (
                                $this->l202_sequencial
                               ,$this->l202_licitacao
                               ," . ($this->l202_datahomologacao == "null" || $this->l202_datahomologacao == "" ? "null" : "'" . $this->l202_datahomologacao . "'") . "
                               ," . ($this->l202_dataadjudicacao == "null" || $this->l202_dataadjudicacao == "" ? "null" : "'" . $this->l202_dataadjudicacao . "'") . "
                               ," . ($this->l202_datareferencia == "null" || $this->l202_datareferencia == "" ? "null" : "'" . $this->l202_datareferencia . "'") . "
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Homologa��o e adjudica��o ($this->l202_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Homologa��o e adjudica��o j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Homologa��o e adjudica��o ($this->l202_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->l202_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->l202_sequencial));
        if (($resaco != false) || ($this->numrows != 0)) {
            $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
            $acount = pg_result($resac, 0, 0);
            $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
            $resac = db_query("insert into db_acountkey values($acount,2009446,'$this->l202_sequencial','I')");
            $resac = db_query("insert into db_acount values($acount,2010223,2009446,'','" . AddSlashes(pg_result($resaco, 0, 'l202_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010223,2009447,'','" . AddSlashes(pg_result($resaco, 0, 'l202_licitacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010223,2009448,'','" . AddSlashes(pg_result($resaco, 0, 'l202_datahomologacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010223,2009449,'','" . AddSlashes(pg_result($resaco, 0, 'l202_dataadjudicacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        return true;
    }
    // funcao para alteracao
    function alterar($l202_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update homologacaoadjudica set ";
        $virgula = "";
        if (trim($this->l202_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l202_sequencial"])) {
            $sql  .= $virgula . " l202_sequencial = $this->l202_sequencial ";
            $virgula = ",";
            if (trim($this->l202_sequencial) == null) {
                $this->erro_sql = " Campo Sequencial nao Informado.";
                $this->erro_campo = "l202_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l202_licitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l202_licitacao"])) {
            $sql  .= $virgula . " l202_licitacao = $this->l202_licitacao ";
            $virgula = ",";
            if (trim($this->l202_licitacao) == null) {
                $this->erro_sql = " Campo Licita��o nao Informado.";
                $this->erro_campo = "l202_licitacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l202_datahomologacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao_dia"] != "")) {
            $sql  .= $virgula . " l202_datahomologacao = '$this->l202_datahomologacao' ";
            $virgula = ",";
            if (trim($this->l202_datahomologacao) == null) {
                $this->erro_sql = " Campo Data Homologa��o nao Informado.";
                $this->erro_campo = "l202_datahomologacao_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao_dia"])) {
                $sql  .= $virgula . " l202_datahomologacao = null ";
                $virgula = ",";
                if (trim($this->l202_datahomologacao) == null) {
                    $this->erro_sql = " Campo Data Homologa��o nao Informado.";
                    $this->erro_campo = "l202_datahomologacao_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->l202_dataadjudicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_dia"] != "")) {
            $sql  .= $virgula . " l202_dataadjudicacao = '$this->l202_dataadjudicacao' ";
            $virgula = ",";
            /*if(trim($this->l202_dataadjudicacao) == null ){
              $this->erro_sql = " Campo Data Adjudica��o nao Informado.";
              $this->erro_campo = "l202_dataadjudicacao_dia";
              $this->erro_banco = "";
              $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
            }*/
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao_dia"])) {
                $sql  .= $virgula . " l202_dataadjudicacao = null ";
                $virgula = ",";
                /*if(trim($this->l202_dataadjudicacao) == null ){
                  $this->erro_sql = " Campo Data Adjudica��o nao Informado.";
                  $this->erro_campo = "l202_dataadjudicacao_dia";
                  $this->erro_banco = "";
                  $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
                  $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                  $this->erro_status = "0";
                  return false;
                }*/
            }
        }

        if (trim($this->l202_datareferencia) != "" ) {
            $sql  .= $virgula . " l202_datareferencia = '$this->l202_datareferencia' ";
            $virgula = ",";
            if (trim($this->l202_datareferencia) == null) {
                $this->erro_sql = " Campo Data de Referencia nao Informado.";
                $this->erro_campo = "l202_datareferencia_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        $sql .= " where ";
        if ($l202_sequencial != null) {
            $sql .= " l202_sequencial = $l202_sequencial";
        }

        $resaco = $this->sql_record($this->sql_query_file($this->l202_sequencial));
        if ($this->numrows > 0) {
            for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,2009446,'$this->l202_sequencial','A')");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l202_sequencial"]) || $this->l202_sequencial != "")
                    $resac = db_query("insert into db_acount values($acount,2010223,2009446,'" . AddSlashes(pg_result($resaco, $conresaco, 'l202_sequencial')) . "','$this->l202_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l202_licitacao"]) || $this->l202_licitacao != "")
                    $resac = db_query("insert into db_acount values($acount,2010223,2009447,'" . AddSlashes(pg_result($resaco, $conresaco, 'l202_licitacao')) . "','$this->l202_licitacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l202_datahomologacao"]) || $this->l202_datahomologacao != "")
                    $resac = db_query("insert into db_acount values($acount,2010223,2009448,'" . AddSlashes(pg_result($resaco, $conresaco, 'l202_datahomologacao')) . "','$this->l202_datahomologacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l202_dataadjudicacao"]) || $this->l202_dataadjudicacao != "")
                    $resac = db_query("insert into db_acount values($acount,2010223,2009449,'" . AddSlashes(pg_result($resaco, $conresaco, 'l202_dataadjudicacao')) . "','$this->l202_dataadjudicacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Homologa��o e adjudica��o nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l202_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Homologa��o e adjudica��o nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->l202_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->l202_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir($l202_sequencial = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($l202_sequencial));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }
        if (($resaco != false) || ($this->numrows != 0)) {
            for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,2009446,'$l202_sequencial','E')");
                $resac = db_query("insert into db_acount values($acount,2010223,2009446,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l202_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2010223,2009447,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l202_licitacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2010223,2009448,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l202_datahomologacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2010223,2009449,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l202_dataadjudicacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $sql = " delete from homologacaoadjudica
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($l202_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " l202_sequencial = $l202_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Homologa��o e adjudica��o nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $l202_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Homologa��o e adjudica��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $l202_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $l202_sequencial;
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
            $this->erro_sql   = "Record Vazio na Tabela:homologacaoadjudica";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    function sql_query($l202_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from homologacaoadjudica ";
        $sql .= "      inner join liclicita  on  liclicita.l20_codigo = homologacaoadjudica.l202_licitacao";
        $sql .= "      inner join db_config  on  db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao  on  licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      left join itenshomologacao on homologacaoadjudica.l202_sequencial = itenshomologacao.l203_homologaadjudicacao";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l202_sequencial != null) {
                $sql2 .= " where homologacaoadjudica.l202_sequencial = $l202_sequencial ";
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
    function sql_query_file($l202_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from homologacaoadjudica ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l202_sequencial != null) {
                $sql2 .= " where homologacaoadjudica.l202_sequencial = $l202_sequencial ";
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

    function sql_query_itens($l202_licitacao = null, $campos = "*", $ordem = null, $dbwhere = "", $joinPrecoReferencia = false)
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
        $sql .= " from pcprocitem ";
        $sql .= "      inner join pcproc                 on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem              on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita               on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      inner join db_depart              on db_depart.coddepto                  = solicita.pc10_depto";
        $sql .= "      left  join solicitemunid          on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid                on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join db_usuarios            on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
        $sql .= "      left  join solicitempcmater       on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater                on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join licitemobra            on licitemobra.obr06_pcmater           = pcmater.pc01_codmater";
        $sql .= "      left  join pcsubgrupo             on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo                 on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemele           on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
        $sql .= "      left  join orcelemento            on orcelemento.o56_codele              = solicitemele.pc18_codele";
        $sql .= "                                        and orcelemento.o56_anousu             = " . db_getsession("DB_anousu");
        $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left  join empautitem             on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
        $sql .= "                                        and empautitem.e55_sequen              = empautitempcprocitem.e73_sequen";
        $sql .= "      left  join empautoriza            on empautoriza.e54_autori              = empautitem.e55_autori ";
        $sql .= "      left  join cgm                    on empautoriza.e54_numcgm              = cgm.z01_numcgm ";
        $sql .= "      left  join empempaut              on empempaut.e61_autori                = empautitem.e55_autori ";
        $sql .= "      left  join empempenho             on empempenho.e60_numemp               = empempaut.e61_numemp ";
        $sql .= "      left join liclicitem              on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
        $sql .= "      left join pcorcamitemlic         on liclicitem.l21_codigo               = pcorcamitemlic.pc26_liclicitem";
        $sql .= "      left join pcorcamitem            on pcorcamitemlic.pc26_orcamitem       = pcorcamitem.pc22_orcamitem";
        $sql .= "      left join pcorcamjulg            on pcorcamitem.pc22_orcamitem          = pcorcamjulg.pc24_orcamitem";
        $sql .= "      left join pcorcamval             on (pc24_orcamitem, pc24_orcamforne)   = (pc23_orcamitem, pc23_orcamforne)";
        $sql .= "      left join pcorcamforne           on pc24_orcamforne                     = pc21_orcamforne";
        $sql .= "      left join cgm cgmforncedor       on pcorcamforne.pc21_numcgm            = cgmforncedor.z01_numcgm";
        $sql .= "      left join homologacaoadjudica    on l202_licitacao                      = l21_codliclicita";
        $sql .= "      left join itenshomologacao       on l203_homologaadjudicacao            = l202_sequencial";
        $sql2 = "";

        if ($joinPrecoReferencia) {
            $sql .= " LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem ";
            $sql .= " LEFT JOIN itemprecoreferencia ON si02_itemproccompra = pcorcamitemproc.pc31_orcamitem ";
        }

        if ($dbwhere == "") {
            if ($l202_licitacao != null && $l202_licitacao != "") {
                $sql2 .= " where liclicitem.l21_codliclicita = $l202_licitacao ";
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
        return $sql;
    }

    function sql_query_itens_semhomologacao($l202_licitacao = null, $campos = "*", $ordem = null, $dbwhere = "", $joinPrecoReferencia = false)
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
        $sql .= " from pcprocitem ";
        $sql .= "      inner join pcproc                 on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem              on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita               on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      inner join db_depart              on db_depart.coddepto                  = solicita.pc10_depto";
        $sql .= "      left  join solicitemunid          on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid                on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join db_usuarios            on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
        $sql .= "      left  join solicitempcmater       on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater                on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join licitemobra            on licitemobra.obr06_pcmater           = pcmater.pc01_codmater";
        $sql .= "      left  join pcsubgrupo             on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo                 on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemele           on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
        $sql .= "      left  join orcelemento            on orcelemento.o56_codele              = solicitemele.pc18_codele";
        $sql .= "                                        and orcelemento.o56_anousu             = " . db_getsession("DB_anousu");
        $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left  join empautitem             on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
        $sql .= "                                        and empautitem.e55_sequen              = empautitempcprocitem.e73_sequen";
        $sql .= "      left  join empautoriza            on empautoriza.e54_autori              = empautitem.e55_autori ";
        $sql .= "      left  join cgm                    on empautoriza.e54_numcgm              = cgm.z01_numcgm ";
        $sql .= "      left  join empempaut              on empempaut.e61_autori                = empautitem.e55_autori ";
        $sql .= "      left  join empempenho             on empempenho.e60_numemp               = empempaut.e61_numemp ";
        $sql .= "      left join liclicitem              on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
        $sql .= "      left join liclicita              on liclicita.l20_codigo                 = liclicitem.l21_codliclicita";
        $sql .= "      left join liclicitemlote         on liclicitemlote.l04_liclicitem        = liclicitem.l21_codigo";
        $sql .= "      left join pcorcamitemlic         on liclicitem.l21_codigo               = pcorcamitemlic.pc26_liclicitem";
        $sql .= "      left join pcorcamitem            on pcorcamitemlic.pc26_orcamitem       = pcorcamitem.pc22_orcamitem";
        $sql .= "      left join pcorcamjulg            on pcorcamitem.pc22_orcamitem          = pcorcamjulg.pc24_orcamitem";
        $sql .= "      left join pcorcamval             on (pc24_orcamitem, pc24_orcamforne)   = (pc23_orcamitem, pc23_orcamforne)";
        $sql .= "      left join pcorcamforne           on pc24_orcamforne                     = pc21_orcamforne";
        $sql .= "      left join cgm cgmforncedor       on pcorcamforne.pc21_numcgm            = cgmforncedor.z01_numcgm";
        $sql .= "      left join homologacaoadjudica    on l202_licitacao                      = l21_codliclicita";
        $sql .= "      left join itenshomologacao       on l203_homologaadjudicacao            = l202_sequencial AND l203_item = pc81_codprocitem";
        $sql2 = "";

        if ($joinPrecoReferencia) {
            $sql .= " LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem ";
            $sql .= " LEFT JOIN itemprecoreferencia ON si02_itemproccompra = pcorcamitemproc.pc31_orcamitem ";
        }

        if ($dbwhere == "") {
            if ($l202_licitacao != null && $l202_licitacao != "") {
                $sql2 .= " where liclicitem.l21_codliclicita = $l202_licitacao ";
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
        return $sql;
    }

    function sql_query_itens_comhomologacao($l202_licitacao = null, $campos = "*", $ordem = null, $dbwhere = "", $joinPrecoReferencia = false)
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
        $sql .= " from pcprocitem ";
        $sql .= "      inner join pcproc                 on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem              on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita               on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      inner join db_depart              on db_depart.coddepto                  = solicita.pc10_depto";
        $sql .= "      left  join solicitemunid          on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid                on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join db_usuarios            on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
        $sql .= "      left  join solicitempcmater       on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater                on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join licitemobra            on licitemobra.obr06_pcmater           = pcmater.pc01_codmater";
        $sql .= "      left  join pcsubgrupo             on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo                 on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemele           on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
        $sql .= "      left  join orcelemento            on orcelemento.o56_codele              = solicitemele.pc18_codele";
        $sql .= "                                        and orcelemento.o56_anousu             = " . db_getsession("DB_anousu");
        $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left  join empautitem             on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
        $sql .= "                                        and empautitem.e55_sequen              = empautitempcprocitem.e73_sequen";
        $sql .= "      left  join empautoriza            on empautoriza.e54_autori              = empautitem.e55_autori ";
        $sql .= "      left  join cgm                    on empautoriza.e54_numcgm              = cgm.z01_numcgm ";
        $sql .= "      left  join empempaut              on empempaut.e61_autori                = empautitem.e55_autori ";
        $sql .= "      left  join empempenho             on empempenho.e60_numemp               = empempaut.e61_numemp ";
        $sql .= "      left join liclicitem              on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
        $sql .= "      left join liclicita              on liclicita.l20_codigo                 = liclicitem.l21_codliclicita";
        $sql .= "      left join liclicitemlote         on liclicitemlote.l04_liclicitem        = liclicitem.l21_codigo";
        $sql .= "      left join pcorcamitemlic         on liclicitem.l21_codigo               = pcorcamitemlic.pc26_liclicitem";
        $sql .= "      left join pcorcamitem            on pcorcamitemlic.pc26_orcamitem       = pcorcamitem.pc22_orcamitem";
        $sql .= "      left join pcorcamjulg            on pcorcamitem.pc22_orcamitem          = pcorcamjulg.pc24_orcamitem";
        $sql .= "      left join pcorcamval             on (pc24_orcamitem, pc24_orcamforne)   = (pc23_orcamitem, pc23_orcamforne)";
        $sql .= "      left join pcorcamforne           on pc24_orcamforne                     = pc21_orcamforne";
        $sql .= "      left join cgm cgmforncedor       on pcorcamforne.pc21_numcgm            = cgmforncedor.z01_numcgm";
        $sql .= "      inner join homologacaoadjudica    on l202_licitacao                      = l21_codliclicita";
        $sql .= "      inner join itenshomologacao       on l203_homologaadjudicacao            = l202_sequencial AND l203_item = pc81_codprocitem";
        $sql2 = "";

        if ($joinPrecoReferencia) {
            $sql .= " LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem ";
            $sql .= " LEFT JOIN itemprecoreferencia ON si02_itemproccompra = pcorcamitemproc.pc31_orcamitem ";
        }

        if ($dbwhere == "") {
            if ($l202_licitacao != null && $l202_licitacao != "") {
                $sql2 .= " where liclicitem.l21_codliclicita = $l202_licitacao ";
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
    function sql_query_ultimo($campos = "*")
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
        $sql .= " from homologacaoadjudica ";
        $sql .= "      inner join liclicita  on  liclicita.l20_codigo = homologacaoadjudica.l202_licitacao";
        $sql .= "      inner join db_config  on  db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao  on  licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= " order by l202_sequencial desc limit 1";
        $campos_sql = explode("#", $ordem);
        $virgula = "";
        for ($i = 0; $i < sizeof($campos_sql); $i++) {
            $sql .= $virgula . $campos_sql[$i];
            $virgula = ",";
        }
        return $sql;
    }

    function sql_query_marcados($pc81_codprocitem = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from pcprocitem ";
        $sql .= "      inner join pcproc                 on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem              on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita               on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      inner join db_depart              on db_depart.coddepto                  = solicita.pc10_depto";
        $sql .= "      left  join solicitemunid          on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid                on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join db_usuarios            on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
        $sql .= "      left  join solicitempcmater       on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater                on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join pcsubgrupo             on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo                 on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemele           on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
        $sql .= "      left  join orcelemento            on orcelemento.o56_codele              = solicitemele.pc18_codele";
        $sql .= "                                       and orcelemento.o56_anousu              = " . db_getsession("DB_anousu");
        $sql .= "      left  join empautitempcprocitem   on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left  join empautitem             on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
        $sql .= "                                       and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
        $sql .= "      left  join empautoriza            on empautoriza.e54_autori              = empautitem.e55_autori ";
        $sql .= "      left  join cgm                    on empautoriza.e54_numcgm              = cgm.z01_numcgm ";
        $sql .= "      left  join empempaut              on empempaut.e61_autori                = empautitem.e55_autori ";
        $sql .= "      left  join empempenho             on empempenho.e60_numemp               = empempaut.e61_numemp ";
        $sql .= "      left  join liclicitem             on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($pc81_codprocitem != null) {
                $sql2 .= " where pcprocitem.pc81_codprocitem = $pc81_codprocitem ";
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

    function itensHomologados($l202_licitacao = null)
    {

        $sql = "select * from homologacaoadjudica
                  join itenshomologacao on l203_homologaadjudicacao = l202_sequencial
                  where l202_licitacao = " . $l202_licitacao;

        $rsItens = db_query($sql);
        //db_criatabela($rsItens);
        for ($iCont = 0; $iCont < pg_num_rows($rsItens); $iCont++) {

            $oItem[$iCont] = db_utils::fieldsMemory($rsItens, $iCont)->l203_item;
        }

        $oItem = implode(',', $oItem);

        return $oItem;
    }

    function excluirItens($l202_sequencial = null)
    {
        $sql = "delete  from itenshomologacao
                  where l203_homologaadjudicacao = " . $l202_sequencial;
        db_query($sql);
    }

    function verificaPrecoReferencia($l202_licitacao)
    {

        $sql = "select distinct pc80_codproc, pc80_data, pc80_usuario, nome, pc80_depto, descrdepto, pc80_resumo
      from liclicitem
      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc
      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem
      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero
      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto
      inner join db_usuarios  on  pcproc.pc80_usuario = db_usuarios.id_usuario
      where l21_codliclicita=$l202_licitacao";

        $rsCodProc = db_query($sql);
        $iCodProc  = db_utils::fieldsMemory($rsCodProc, 0)->pc80_codproc;

        $sql       = "select * from precoreferencia where si01_processocompra = $iCodProc";
        $rsPreRef  = db_query($sql);

        return pg_numrows($rsPreRef);
    }

    function alteraLicitacao($iLicitacao, $iSituacao)
    {
        $sql = "update liclicita set l20_licsituacao = $iSituacao where l20_codigo = $iLicitacao";
        db_query($sql);
    }

    /**
     * Verifica se os fornecedores vencedores est�o habilitados
     * @param $iLicitacao
     * @return bool
     */
    function validaFornecedoresHabilitados($iLicitacao)
    {
        //Busco todos os fornecedores da licita��o que ganharam itens
        $sSqlFornJulg = "SELECT DISTINCT pc24_orcamforne
                        FROM pcorcamforne
                        INNER JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                        INNER JOIN pcorcamjulg ON pc24_orcamforne = pc21_orcamforne and pc24_pontuacao = 1
                        INNER JOIN cgm ON cgm.z01_numcgm = pcorcamforne.pc21_numcgm
                        INNER JOIN pcorcam ON pcorcam.pc20_codorc = pcorcamforne.pc21_codorc
                        WHERE pc31_liclicita = {$iLicitacao}";
        $rsSqlFornJulg = db_query($sSqlFornJulg);
        $aFornJulg = pg_fetch_all_columns($rsSqlFornJulg, 0);

        //Busco todos os fornecedores habilitados para a licita��o
        $sSqlHabilita = "select distinct pc21_orcamforne from habilitacaoforn inner join pcorcamforne on l206_fornecedor = pc21_numcgm where l206_licitacao = {$iLicitacao}";
        $rsSqlHabilita = db_query($sSqlHabilita);
        $aFornHabilita = pg_fetch_all_columns($rsSqlHabilita, 0);

        //Se as quantidades forem diferentes, j� retorna false
        if (count($aFornJulg) > count($aFornHabilita)) {
            return false;
        } elseif (count(array_diff($aFornJulg, $aFornHabilita)) > 0) {
            return false;
        }
        return true;
    }

    /**
     * Busco data de julgamento
     * @param $iLicitacao
     * @return date
     **/
    function verificadatajulgamento($iLicitacao)
    {
        $sSqlJulg = "SELECT l11_data
                     FROM liclicitasituacao
                     WHERE l11_liclicita = {$iLicitacao}
                     AND l11_sequencial = (SELECT max(l11_sequencial) FROM liclicitasituacao WHERE l11_licsituacao = 1
                     AND l11_liclicita = {$iLicitacao})";
        $oResult = db_query($sSqlJulg);
        return db_utils::getColectionByRecord($oResult);
    }

    function getdataAdjudicacao($iLicitacao)
    {
        $sSql = "SELECT l202_dataadjudicacao
                        FROM homologacaoadjudica
                        WHERE l202_licitacao = {$iLicitacao}";
        $oResultdt = db_query($sSql);
        return db_utils::getColectionByRecord($oResultdt);
    }

    /**
     * Fun��o respons�vel por verificar se existem itens com o mesmo c�digo e
     * valores unit�rios diferentes julgados para o mesmo fornecedor.
     * @param int $iLicitacao - C�digo da Licita��o.
     * @param int $iRotina - 1 Adjudica��o(Inclus�o/Altera��o), 2 - Homologa��o(Inclus�o), 3 Homologa��o(Altera��o).
     * @param int $iHomologacao - C�digo da Homologa��o.
     * @param string $sItensHomologados - c�digo dos itens homologados durante a inclus�o da homologa��o
     * @return Exception
     **/
    function validacaoItensComFornecedorIgual($iLicitacao, $iRotina, $iHomologacao, $sItensHomologados)
    {

        $sCampos = "pc81_codprocitem,pc01_codmater,cgmforncedor.z01_numcgm,cgmforncedor.z01_nome,pc23_vlrun";
        $sOrder = "pc81_codprocitem";

        /* Consulta dos itens conforme a rotina executada */

        if ($iRotina == 1) {
            $sWhere = "pc11_servicoquantidade != 'f' and liclicitem.l21_codliclicita = $iLicitacao and pc24_pontuacao = 1 AND itenshomologacao.l203_sequencial is null";
            $rsItens = $this->sql_record($this->sql_query_itens_semhomologacao(null, $sCampos, $sOrder, $sWhere));
        }

        if ($iRotina == 2) {
            $sWhere = "pc11_servicoquantidade != 'f' and pc81_codprocitem in ($sItensHomologados) and liclicitem.l21_codliclicita = $iLicitacao and pc24_pontuacao = 1 AND pc81_codprocitem not in (select l203_item from homologacaoadjudica
            inner join itenshomologacao on l203_homologaadjudicacao = l202_sequencial where l202_licitacao = $iLicitacao)";
            $rsItens = $this->sql_record($this->sql_query_itens_semhomologacao(null, $sCampos, $sOrder, $sWhere));
        }

        if ($iRotina == 3) {
            $sWhere = "pc11_servicoquantidade != 'f' and liclicitem.l21_codliclicita = $iLicitacao and pc24_pontuacao = 1 AND itenshomologacao.l203_homologaadjudicacao = $iHomologacao";
            $rsItens = $this->sql_record($this->sql_query_itens_comhomologacao(null, $sCampos, $sOrder, $sWhere));
        }

        /* Array respons�vel por armazenar o c�digo do item/codigo do fornecedor associado com o valor unit�rio de cada item*/
        $aItens = array();
        $sItensInvalidos = "";

        /* Percorrendo sobre os itens e verificando se existem itens com
           mesmo fornecedor e valores unitarios diferentes */

        for ($i = 0; $i < pg_num_rows($rsItens); $i++) {
            $oItem = db_utils::fieldsMemory($rsItens, $i);
            // ind�ce do array com o c�digo do item/c�digo do fornecedor
            $indice = $oItem->pc01_codmater . $oItem->z01_numcgm;

            if (array_key_exists($indice, $aItens)) {

                if (strcmp($oItem->pc23_vlrun, $aItens[$indice]) != 0) {
                    $sItensInvalidos .= "\nItem $oItem->pc81_codprocitem - Fornecedor $oItem->z01_nome";
                }
            }

            $aItens[$indice] = $oItem->pc23_vlrun;
        }

        if ($sItensInvalidos != "") throw new Exception("Usu�rio: Inclus�o abortada, esta licita��o possui itens com o mesmo c�digo e valores unit�rios divergentes julgados para o mesmo fornecedor." . $sItensInvalidos);
    }

    /**
     * Fun��o respons�vel por verificar se todos os fornecedores ganhadores
     * (que est�o com pontua��o 1 na tabela pcorcamjulg) est�o habilitados.
     * @param int $iLicitacao - C�digo da Licita��o.
     * @return Exception
     **/
    function validacaoFornecedoresHabilitados($iCodigoLicitacao){
        $rsFornecedores = db_query("SELECT DISTINCT l205_fornecedor,z01_nome
                                        FROM credenciamento
                                        inner join cgm on z01_numcgm = l205_fornecedor
                                        WHERE l205_licitacao ={$iCodigoLicitacao}
                                            AND l205_fornecedor NOT IN
                                                (SELECT l206_fornecedor
                                                 FROM habilitacaoforn
                                                 WHERE l206_licitacao = {$iCodigoLicitacao})");
        $aFornecedores = db_utils::getCollectionByRecord($rsFornecedores);
        $sFornecedoresPendentes = "";
        foreach($aFornecedores as $fornecedor){
            $sFornecedoresPendentes .= "{$fornecedor->l205_fornecedor} - {$fornecedor->z01_nome}, ";
        }
        if ($sFornecedoresPendentes != ""){
            $mensagemErro = "Usu�rio: Inclus�o abortada. \n O(s) fornecedor(es) $sFornecedoresPendentes n�o est�(�o) habilitado(s).";
            return $mensagemErro;
        }
        return true;
    }

    /**
     * M�todo respons�vel por verificar se todos os fornecedores referente aos itens selecionados para homologa��o/publica��o est�o habiltiados.
     * @param int $iLicitacao - C�digo da Licita��o.
     * @param string $sCodigoFornecedores - C�digo dos fornecedores referente aos itens selecionados.
     * @param string $sRotina - "ratificacao" ou "homologacao"
     * @return String or Bool - retorna true em caso de sucesso e String com mensagem de erro caso possua fornecedores inabilitados.
     **/
    function validacaoFornecedoresInabilitados($iCodigoLicitacao,$sCodigoFornecedores,$sRotina){

        $aCodigoFornecedores = explode(',', $sCodigoFornecedores);
        $aCodigoFornecedores = array_unique($aCodigoFornecedores);

        $rsCodigoTipoTribunal = db_query("select * from liclicita inner join cflicita on l03_codigo = l20_codtipocom where l20_codigo = $iCodigoLicitacao");
        $tipoCompraTribunal = db_utils::fieldsMemory($rsCodigoTipoTribunal, 0)->l03_pctipocompratribunal;

        if(($tipoCompraTribunal == "102" || $tipoCompraTribunal == "103") && $sRotina == "ratificacao"){
            return true;
        }

        $sFornecedoresInabilitados = "";

        foreach($aCodigoFornecedores as $iCodigoFornecedor){

            $sSqlValidacaoRatificacao = "select * from habilitacaoforn inner join liclicita on l206_licitacao = l20_codigo inner join cflicita on l03_codigo = l20_codtipocom where l206_licitacao = $iCodigoLicitacao and l206_fornecedor = $iCodigoFornecedor and l03_pctipocompratribunal not in (102,103)";
            $sSqlValidacaoHomologacao = "select * from habilitacaoforn where l206_licitacao = $iCodigoLicitacao and l206_fornecedor = $iCodigoFornecedor";
    
            $sSql = $sRotina == "ratificacao" ? $sSqlValidacaoRatificacao : $sSqlValidacaoHomologacao;
            $rsFornecedorHabilitado = db_query($sSql);
            /* Caso n�o retornar nenhum registro, o fornecedor ainda n�o est� habilitado.*/
            if(pg_numrows($rsFornecedorHabilitado) == 0){
                $rsCgm = db_query("select * from cgm where z01_numcgm = $iCodigoFornecedor");
                $sDescricaoFornecedor = db_utils::fieldsMemory($rsCgm, 0)->z01_nome;
                $sFornecedoresInabilitados .= "{$iCodigoFornecedor} - $sDescricaoFornecedor, ";
            }
        }

        if ($sFornecedoresInabilitados != ""){
            $mensagemErro = "Usu�rio: Inclus�o abortada. \n O(s) fornecedor(es) $sFornecedoresInabilitados n�o est�(�o) habilitado(s).";
            return $mensagemErro;
        }

        return true;

    }

    /** O m�todo verifica se os fornecedores possuem a data de habilita��o maior que a data de homologa��o/publica��o.
     * @param int $iCodigoLicitacao - C�digo da Licita��o.
     * @param string $sDataHomologacao - Data de Homologa��o.
     * @param string $sCodigoFornecedores - C�digo dos fornecedores referente aos itens selecionados.
     * @param string $sRotina - "ratificacao" ou "homologacao"
     * @return String or Bool
     **/
    function validacaoDataHabilitacao($iCodigoLicitacao,$sDataHomologacao,$sCodigoFornecedores,$sRotina){
        $rsFornecedores = db_query("select * from liclicita inner join cflicita on l03_codigo = l20_codtipocom inner join habilitacaoforn on l206_licitacao = l20_codigo inner join cgm on z01_numcgm = l206_fornecedor where l20_codigo = $iCodigoLicitacao and l03_pctipocompratribunal not in (102,103) and l206_datahab > '$sDataHomologacao' and l206_fornecedor in ($sCodigoFornecedores)");
        $aFornecedores = db_utils::getCollectionByRecord($rsFornecedores);
        $sFornecedoresPendentes = "";
        foreach($aFornecedores as $fornecedor){
            $sFornecedoresPendentes .= "{$fornecedor->l206_fornecedor} - {$fornecedor->z01_nome}, ";
        }
        if ($sFornecedoresPendentes != "" && $sRotina == "homologacao"){
            $mensagemErro = "Usu�rio: Inclus�o abortada. \n O(s) fornecedor(es) $sFornecedoresPendentes possuem a data de habilita��o maior que a data de homologa��o.";
            return $mensagemErro;
        }
        if ($sFornecedoresPendentes != "" && $sRotina == "ratificacao"){
            $mensagemErro = "Usu�rio: Inclus�o abortada. \n O(s) fornecedor(es) $sFornecedoresPendentes possuem a data de habilita��o maior que a data de publica��o de ratifica��o.";
            return $mensagemErro;
        }
        return true;
    }

    /** O m�todo verifica se existe fornecedor(es) credenciados para a licita��o informada, caso o tipo de compra do tribunal seja 102/103
     * @param int $iCodigoLicitacao - C�digo da Licita��o.
     * @return Bool
     **/
    function validacaoCredenciamento($iCodigoLicitacao){

        $rsCodigoTipoTribunal = db_query("select * from liclicita inner join cflicita on l03_codigo = l20_codtipocom where l20_codigo = $iCodigoLicitacao");
        $tipoCompraTribunal = db_utils::fieldsMemory($rsCodigoTipoTribunal, 0)->l03_pctipocompratribunal;
        if($tipoCompraTribunal == "102" || $tipoCompraTribunal == "103"){

            $rsCredenciados = db_query("select * from credenciamento inner join liclicita on l205_licitacao = l20_codigo where l205_licitacao = $iCodigoLicitacao");

            if(pg_numrows($rsCredenciados) == 0){
                return false;
            }
        }

        return true;

    }

    function sqlItensHomologados($l202_licitacao = null,$l202_sequencial)
    {

        return "select
                    distinct pc01_codmater,
                    pc01_tabela,
                    pc01_taxa,
                    UPPER(CONCAT(pc01_descrmater, ' ', pc01_complmater)) AS descricao,
                    cgmforncedor.z01_nome,
                    cgmforncedor.z01_cgccpf,
                    UPPER(m61_abrev) as m61_abrev,
                    pc11_quant,
                    UPPER(LEFT(pc23_obs, 50)) AS marca,
                    pc23_valor,
                    pcorcamval.pc23_vlrun,
                    pcorcamval.pc23_percentualdesconto,
                    pcorcamval.pc23_perctaxadesctabela,
                    l203_homologaadjudicacao,
                    pc81_codprocitem,
                    l04_descricao,
                    pc11_seq,
                    l20_criterioadjudicacao,
                    l20_tipojulg,
                    liclicitem.l21_ordem
                from
                    pcprocitem
                inner join pcproc on
                    pcproc.pc80_codproc = pcprocitem.pc81_codproc
                inner join solicitem on
                    solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                inner join solicita on
                    solicita.pc10_numero = solicitem.pc11_numero
                inner join db_depart on
                    db_depart.coddepto = solicita.pc10_depto
                left join solicitemunid on
                    solicitemunid.pc17_codigo = solicitem.pc11_codigo
                left join matunid on
                    matunid.m61_codmatunid = solicitemunid.pc17_unid
                left join db_usuarios on
                    pcproc.pc80_usuario = db_usuarios.id_usuario
                left join solicitempcmater on
                    solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                left join pcmater on
                    pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                left join licitemobra on
                    licitemobra.obr06_pcmater = pcmater.pc01_codmater
                left join pcsubgrupo on
                    pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
                left join pctipo on
                    pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
                left join solicitemele on
                    solicitemele.pc18_solicitem = solicitem.pc11_codigo
                left join orcelemento on
                    orcelemento.o56_codele = solicitemele.pc18_codele
                    and orcelemento.o56_anousu = " . db_getsession("DB_anousu") . "
                left join empautitempcprocitem on
                    empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                left join empautitem on
                    empautitem.e55_autori = empautitempcprocitem.e73_autori
                    and empautitem.e55_sequen = empautitempcprocitem.e73_sequen
                left join empautoriza on
                    empautoriza.e54_autori = empautitem.e55_autori
                left join cgm on
                    empautoriza.e54_numcgm = cgm.z01_numcgm
                left join empempaut on
                    empempaut.e61_autori = empautitem.e55_autori
                left join empempenho on
                    empempenho.e60_numemp = empempaut.e61_numemp
                left join liclicitem on
                    liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                left join liclicita on
                    liclicita.l20_codigo = liclicitem.l21_codliclicita
                left join liclicitemlote on
                    liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                left join pcorcamitemlic on
                    liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem
                left join pcorcamitem on
                    pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
                left join pcorcamjulg on
                    pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
                left join pcorcamval on
                    (pc24_orcamitem,
                    pc24_orcamforne) = (pc23_orcamitem,
                    pc23_orcamforne)
                left join pcorcamforne on
                    pc24_orcamforne = pc21_orcamforne
                left join cgm cgmforncedor on
                    pcorcamforne.pc21_numcgm = cgmforncedor.z01_numcgm
                inner join homologacaoadjudica on
                    l202_licitacao = l21_codliclicita
                inner join itenshomologacao on
                    l203_homologaadjudicacao = l202_sequencial
                    and l203_item = pc81_codprocitem
                where
                    liclicitem.l21_codliclicita = {$l202_licitacao}
                    and pc24_pontuacao = 1
                    and itenshomologacao.l203_homologaadjudicacao = {$l202_sequencial}
                order by
                    z01_nome,
                    l04_descricao,
                    pc11_seq";

    }

    function sqlItensAdjudicados($l202_licitacao){
        return "select
                    distinct pc01_codmater,
                    pc01_tabela,
                    pc01_taxa,
                    UPPER(CONCAT(pc01_descrmater, ' ', pc01_complmater)) AS descricao,
                    cgmforncedor.z01_nome,
                    cgmforncedor.z01_cgccpf,
                    UPPER(m61_abrev) as m61_abrev,
                    pc11_quant,
                    UPPER(LEFT(pc23_obs, 50)) AS marca,
                    pc23_valor,
                    pcorcamval.pc23_vlrun,
                    pcorcamval.pc23_percentualdesconto,
                    pcorcamval.pc23_perctaxadesctabela,
                    l203_homologaadjudicacao,
                    pc81_codprocitem,
                    l04_descricao,
                    pc11_seq,
                    l20_criterioadjudicacao,
                    liclicitem.l21_ordem
                from
                    pcprocitem
                inner join pcproc on
                    pcproc.pc80_codproc = pcprocitem.pc81_codproc
                inner join solicitem on
                    solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                inner join solicita on
                    solicita.pc10_numero = solicitem.pc11_numero
                inner join db_depart on
                    db_depart.coddepto = solicita.pc10_depto
                left join solicitemunid on
                    solicitemunid.pc17_codigo = solicitem.pc11_codigo
                left join matunid on
                    matunid.m61_codmatunid = solicitemunid.pc17_unid
                left join db_usuarios on
                    pcproc.pc80_usuario = db_usuarios.id_usuario
                left join solicitempcmater on
                    solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                left join pcmater on
                    pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                left join licitemobra on
                    licitemobra.obr06_pcmater = pcmater.pc01_codmater
                left join pcsubgrupo on
                    pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
                left join pctipo on
                    pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
                left join solicitemele on
                    solicitemele.pc18_solicitem = solicitem.pc11_codigo
                left join orcelemento on
                    orcelemento.o56_codele = solicitemele.pc18_codele
                    and orcelemento.o56_anousu = " . db_getsession("DB_anousu") . "
                left join empautitempcprocitem on
                    empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                left join empautitem on
                    empautitem.e55_autori = empautitempcprocitem.e73_autori
                    and empautitem.e55_sequen = empautitempcprocitem.e73_sequen
                left join empautoriza on
                    empautoriza.e54_autori = empautitem.e55_autori
                left join cgm on
                    empautoriza.e54_numcgm = cgm.z01_numcgm
                left join empempaut on
                    empempaut.e61_autori = empautitem.e55_autori
                left join empempenho on
                    empempenho.e60_numemp = empempaut.e61_numemp
                left join liclicitem on
                    liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                left join liclicita on
                    liclicita.l20_codigo = liclicitem.l21_codliclicita
                left join liclicitemlote on
                    liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                left join pcorcamitemlic on
                    liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem
                left join pcorcamitem on
                    pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
                left join pcorcamjulg on
                    pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
                left join pcorcamval on
                    (pc24_orcamitem,
                    pc24_orcamforne) = (pc23_orcamitem,
                    pc23_orcamforne)
                left join pcorcamforne on
                    pc24_orcamforne = pc21_orcamforne
                left join cgm cgmforncedor on
                    pcorcamforne.pc21_numcgm = cgmforncedor.z01_numcgm
                left join homologacaoadjudica on
                    l202_licitacao = l21_codliclicita
                left join itenshomologacao on
                    l203_homologaadjudicacao = l202_sequencial
                    and l203_item = pc81_codprocitem
                where
                    liclicitem.l21_codliclicita = $l202_licitacao
                    and pc24_pontuacao = 1
                    and itenshomologacao.l203_sequencial is null
                order by
                    pc11_seq,
                    z01_nome";
    }

    function totalAdjudicadoPorFornecedor($l202_licitacao,$z01_nome){
        $sSql = "select
                    cgmforncedor.z01_nome,
                    SUM(pc23_valor) as total
                from
                    pcprocitem
                inner join pcproc on
                    pcproc.pc80_codproc = pcprocitem.pc81_codproc
                inner join solicitem on
                    solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                inner join solicita on
                    solicita.pc10_numero = solicitem.pc11_numero
                inner join db_depart on
                    db_depart.coddepto = solicita.pc10_depto
                left join solicitempcmater on
                    solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                left join pcmater on
                    pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                left join licitemobra on
                    licitemobra.obr06_pcmater = pcmater.pc01_codmater
                left join pcsubgrupo on
                    pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
                left join pctipo on
                    pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
                left join solicitemele on
                    solicitemele.pc18_solicitem = solicitem.pc11_codigo
                left join orcelemento on
                    orcelemento.o56_codele = solicitemele.pc18_codele
                    and orcelemento.o56_anousu = " . db_getsession("DB_anousu") . "
                left join empautitempcprocitem on
                    empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                left join empautitem on
                    empautitem.e55_autori = empautitempcprocitem.e73_autori
                    and empautitem.e55_sequen = empautitempcprocitem.e73_sequen
                left join empautoriza on
                    empautoriza.e54_autori = empautitem.e55_autori
                left join cgm on
                    empautoriza.e54_numcgm = cgm.z01_numcgm
                left join empempaut on
                    empempaut.e61_autori = empautitem.e55_autori
                left join empempenho on
                    empempenho.e60_numemp = empempaut.e61_numemp
                left join liclicitem on
                    liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                left join liclicita on
                    liclicita.l20_codigo = liclicitem.l21_codliclicita
                left join liclicitemlote on
                    liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                left join pcorcamitemlic on
                    liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem
                left join pcorcamitem on
                    pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
                left join pcorcamjulg on
                    pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
                left join pcorcamval on
                    (pc24_orcamitem,
                    pc24_orcamforne) = (pc23_orcamitem,
                    pc23_orcamforne)
                left join pcorcamforne on
                    pc24_orcamforne = pc21_orcamforne
                left join cgm cgmforncedor on
                    pcorcamforne.pc21_numcgm = cgmforncedor.z01_numcgm
                left join homologacaoadjudica on
                    l202_licitacao = l21_codliclicita
                left join itenshomologacao on
                    l203_homologaadjudicacao = l202_sequencial
                    and l203_item = pc81_codprocitem
                where
                    liclicitem.l21_codliclicita = $l202_licitacao
                    and pc24_pontuacao = 1
                    and itenshomologacao.l203_sequencial is null
                    and TRIM(cgmforncedor.z01_nome) = '$z01_nome'
                group by
                    cgmforncedor.z01_nome
                order by
                    cgmforncedor.z01_nome;";

                    $rsTotal = db_query($sSql);
                    return db_utils::fieldsMemory($rsTotal, 0)->total;
    }

    function totalHomologadoPorFornecedor($l202_licitacao = null,$l202_sequencial,$z01_nome){
        $sSql = "select
                    cgmforncedor.z01_nome,
                    SUM(pc23_valor) AS total
                from
                    pcprocitem
                inner join pcproc on
                    pcproc.pc80_codproc = pcprocitem.pc81_codproc
                inner join solicitem on
                    solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                inner join solicita on
                    solicita.pc10_numero = solicitem.pc11_numero
                inner join db_depart on
                    db_depart.coddepto = solicita.pc10_depto
                left join solicitemunid on
                    solicitemunid.pc17_codigo = solicitem.pc11_codigo
                left join matunid on
                    matunid.m61_codmatunid = solicitemunid.pc17_unid
                left join db_usuarios on
                    pcproc.pc80_usuario = db_usuarios.id_usuario
                left join solicitempcmater on
                    solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                left join pcmater on
                    pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                left join licitemobra on
                    licitemobra.obr06_pcmater = pcmater.pc01_codmater
                left join pcsubgrupo on
                    pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
                left join pctipo on
                    pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
                left join solicitemele on
                    solicitemele.pc18_solicitem = solicitem.pc11_codigo
                left join orcelemento on
                    orcelemento.o56_codele = solicitemele.pc18_codele
                    and orcelemento.o56_anousu = " . db_getsession("DB_anousu") . "
                left join empautitempcprocitem on
                    empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                left join empautitem on
                    empautitem.e55_autori = empautitempcprocitem.e73_autori
                    and empautitem.e55_sequen = empautitempcprocitem.e73_sequen
                left join empautoriza on
                    empautoriza.e54_autori = empautitem.e55_autori
                left join cgm on
                    empautoriza.e54_numcgm = cgm.z01_numcgm
                left join empempaut on
                    empempaut.e61_autori = empautitem.e55_autori
                left join empempenho on
                    empempenho.e60_numemp = empempaut.e61_numemp
                left join liclicitem on
                    liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                left join liclicita on
                    liclicita.l20_codigo = liclicitem.l21_codliclicita
                left join liclicitemlote on
                    liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                left join pcorcamitemlic on
                    liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem
                left join pcorcamitem on
                    pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
                left join pcorcamjulg on
                    pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
                left join pcorcamval on
                    (pc24_orcamitem,
                    pc24_orcamforne) = (pc23_orcamitem,
                    pc23_orcamforne)
                left join pcorcamforne on
                    pc24_orcamforne = pc21_orcamforne
                left join cgm cgmforncedor on
                    pcorcamforne.pc21_numcgm = cgmforncedor.z01_numcgm
                inner join homologacaoadjudica on
                    l202_licitacao = l21_codliclicita
                inner join itenshomologacao on
                    l203_homologaadjudicacao = l202_sequencial
                    and l203_item = pc81_codprocitem
                WHERE
                    liclicitem.l21_codliclicita = $l202_licitacao
                    AND pc24_pontuacao = 1
                    AND itenshomologacao.l203_homologaadjudicacao = $l202_sequencial
                    and TRIM(cgmforncedor.z01_nome) = '$z01_nome'
                GROUP BY
                    cgmforncedor.z01_nome
                ORDER BY
                    cgmforncedor.z01_nome;";

            $rsTotal = db_query($sSql);
            return db_utils::fieldsMemory($rsTotal, 0)->total;
    }

    function getValorTotal($codigoLicitacao,$codigoHomologacao){
        
        $sWhere = $codigoHomologacao == null ? "AND itenshomologacao.l203_sequencial is null" : "AND itenshomologacao.l203_homologaadjudicacao = $codigoHomologacao";

        $sSql = "SELECT 
                    SUM(pc23_valor) AS totalGeral
                FROM 
                    pcprocitem
                INNER JOIN pcproc ON 
                    pcproc.pc80_codproc = pcprocitem.pc81_codproc
                INNER JOIN solicitem ON 
                    solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                INNER JOIN solicita ON 
                    solicita.pc10_numero = solicitem.pc11_numero
                INNER JOIN db_depart ON 
                    db_depart.coddepto = solicita.pc10_depto
                LEFT JOIN solicitemunid ON 
                    solicitemunid.pc17_codigo = solicitem.pc11_codigo
                LEFT JOIN matunid ON 
                    matunid.m61_codmatunid = solicitemunid.pc17_unid
                LEFT JOIN db_usuarios ON 
                    pcproc.pc80_usuario = db_usuarios.id_usuario
                LEFT JOIN solicitempcmater ON 
                    solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                LEFT JOIN pcmater ON 
                    pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                LEFT JOIN licitemobra ON 
                    licitemobra.obr06_pcmater = pcmater.pc01_codmater
                LEFT JOIN pcsubgrupo ON 
                    pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo
                LEFT JOIN pctipo ON 
                    pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo
                LEFT JOIN solicitemele ON 
                    solicitemele.pc18_solicitem = solicitem.pc11_codigo
                LEFT JOIN orcelemento ON 
                    orcelemento.o56_codele = solicitemele.pc18_codele
                    AND orcelemento.o56_anousu = " . db_getsession("DB_anousu") . "
                LEFT JOIN empautitempcprocitem ON 
                    empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem
                LEFT JOIN empautitem ON 
                    empautitem.e55_autori = empautitempcprocitem.e73_autori
                    AND empautitem.e55_sequen = empautitempcprocitem.e73_sequen
                LEFT JOIN empautoriza ON 
                    empautoriza.e54_autori = empautitem.e55_autori
                LEFT JOIN cgm ON 
                    empautoriza.e54_numcgm = cgm.z01_numcgm
                LEFT JOIN empempaut ON 
                    empempaut.e61_autori = empautitem.e55_autori
                LEFT JOIN empempenho ON 
                    empempenho.e60_numemp = empempaut.e61_numemp
                LEFT JOIN liclicitem ON 
                    liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                LEFT JOIN liclicita ON 
                    liclicita.l20_codigo = liclicitem.l21_codliclicita
                LEFT JOIN liclicitemlote ON 
                    liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                LEFT JOIN pcorcamitemlic ON 
                    liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem
                LEFT JOIN pcorcamitem ON 
                    pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
                LEFT JOIN pcorcamjulg ON 
                    pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
                LEFT JOIN pcorcamval ON 
                    (pc24_orcamitem, pc24_orcamforne) = (pc23_orcamitem, pc23_orcamforne)
                LEFT JOIN homologacaoadjudica ON 
                    l202_licitacao = l21_codliclicita
                LEFT JOIN itenshomologacao ON 
                    l203_homologaadjudicacao = l202_sequencial
                    AND l203_item = pc81_codprocitem
                WHERE 
                    liclicitem.l21_codliclicita = $codigoLicitacao
                    AND pc24_pontuacao = 1 $sWhere";
            $rsTotalGeral = db_query($sSql);
            return db_utils::fieldsMemory($rsTotalGeral, 0)->totalgeral;

    }

    function getCodigoHomologacao($codigoLicitacao){
        $rsCodigoHomologacao = db_query("select l202_sequencial from homologacaoadjudica where l202_licitacao = $codigoLicitacao and l202_datahomologacao is not null");
        return db_utils::fieldsMemory($rsCodigoHomologacao, 0)->l202_sequencial;
    }

    function sqlGetResponsavel($codigoLicitacao,$tipoResponsavel){

        return "SELECT z01_nome, z01_cgccpf FROM liccomissaocgm INNER JOIN cgm ON l31_numcgm = z01_numcgm WHERE l31_licitacao = $codigoLicitacao AND l31_tipo = '$tipoResponsavel'";

    }
}
