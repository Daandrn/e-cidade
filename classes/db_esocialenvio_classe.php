<?php
//MODULO: esocial
//CLASSE DA ENTIDADE esocialenvio
class cl_esocialenvio
{
    // cria variaveis de erro
    const SITUACAO_NAO_ENVIADO = 1;
    const SITUACAO_ENVIADO = 2;
    const SITUACAO_PROCESSANDO = 3;
    const SITUACAO_ERRO_ENVIO = 4;
    public $rotulo     = null;
    public $query_sql  = null;
    public $numrows    = 0;
    public $numrows_incluir = 0;
    public $numrows_alterar = 0;
    public $numrows_excluir = 0;
    public $erro_status = null;
    public $erro_sql   = null;
    public $erro_banco = null;
    public $erro_msg   = null;
    public $erro_campo = null;
    public $pagina_retorno = null;
    // cria variaveis do arquivo
    public $rh213_sequencial = 0;
    public $rh213_evento = 0;
    public $rh213_empregador = 0;
    public $rh213_responsavelpreenchimento = null;
    public $rh213_dados = null;
    public $rh213_md5 = null;
    public $rh213_situacao = 0;
    public $rh213_msgretorno = null;
    public $rh213_dataprocessamento = null;
    public $rh213_ambienteenvio = null;
    public $rh213_protocolo = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 rh213_sequencial = int4 = C�digo
                 rh213_evento = int4 = C�digo do Evento
                 rh213_empregador = int4 = empregador
                 rh213_responsavelpreenchimento = varchar(255) = Respons�vel Preenchimento
                 rh213_dados = text = Dados
                 rh213_md5 = varchar(32) = MD5
                 rh213_situacao = int4 = Situacao
                 rh213_msgretorno = text = Mensagem de Retorno
                 rh213_dataprocessamento = date = Data Processamento
                 rh213_ambienteenvio = int4 = Ambiente de Envio
                 rh213_protocolo = int4 = Número do Protocolo
                 ";
    //funcao construtor da classe
    public function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("esocialenvio");
        $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }
    //funcao erro
    public function erro($mostra, $retorna)
    {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }
    // funcao para atualizar campos
    public function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->rh213_sequencial = ($this->rh213_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"] : $this->rh213_sequencial);
            $this->rh213_evento = ($this->rh213_evento == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_evento"] : $this->rh213_evento);
            $this->rh213_empregador = ($this->rh213_empregador == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_empregador"] : $this->rh213_empregador);
            $this->rh213_responsavelpreenchimento = ($this->rh213_responsavelpreenchimento == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_responsavelpreenchimento"] : $this->rh213_responsavelpreenchimento);
            $this->rh213_dados = ($this->rh213_dados == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_dados"] : $this->rh213_dados);
            $this->rh213_md5 = ($this->rh213_md5 == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_md5"] : $this->rh213_md5);
            $this->rh213_situacao = ($this->rh213_situacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_situacao"] : $this->rh213_situacao);
            $this->rh213_msgretorno = ($this->rh213_msgretorno == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_msgretorno"] : $this->rh213_msgretorno);
            $this->rh213_dataprocessamento = ($this->rh213_dataprocessamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_dataprocessamento"] : $this->rh213_dataprocessamento);
            $this->rh213_ambienteenvio = ($this->rh213_ambienteenvio == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_ambienteenvio"] : $this->rh213_ambienteenvio);
            $this->rh213_protocolo = ($this->rh213_protocolo == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_protocolo"] : $this->rh213_protocolo);
        } else {
            $this->rh213_sequencial = ($this->rh213_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"] : $this->rh213_sequencial);
        }
    }
    // funcao para Inclus�o
    public function incluir($rh213_sequencial)
    {
        $this->atualizacampos();
        if ($this->rh213_evento == null) {
            $this->erro_sql = " Campo C�digo do Evento n�o informado.";
            $this->erro_campo = "rh213_evento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh213_empregador == null) {
            $this->erro_sql = " Campo empregador n�o informado.";
            $this->erro_campo = "rh213_empregador";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh213_responsavelpreenchimento == null) {
            $this->erro_sql = " Campo Respons�vel Preenchimento n�o informado.";
            $this->erro_campo = "rh213_responsavelpreenchimento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh213_dados == null) {
            $this->erro_sql = " Campo Dados n�o informado.";
            $this->erro_campo = "rh213_dados";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh213_md5 == null) {
            $this->erro_sql = " Campo MD5 n�o informado.";
            $this->erro_campo = "rh213_md5";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh213_situacao == null) {
            $this->erro_sql = " Campo Situacao n�o informado.";
            $this->erro_campo = "rh213_situacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->rh213_dataprocessamento == null) {
            $this->erro_sql = " Campo Data Processamento n�o informado.";
            $this->erro_campo = "rh213_dataprocessamento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh213_ambienteenvio == null) {
            $this->erro_sql = " Campo Ambiente de Envio n�o informado.";
            $this->erro_campo = "rh213_ambienteenvio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        // if ($this->rh213_protocolo == null) {
        //     $this->erro_sql = " Campo Protocolo n�o informado.";
        //     $this->erro_campo = "rh213_protocolo";
        //     $this->erro_banco = "";
        //     $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        //     $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        //     $this->erro_status = "0";
        //     return false;
        // }
        if ($rh213_sequencial == "" || $rh213_sequencial == null) {

            $result = db_query("select nextval('esocialenvio_rh213_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = " Verifique o cadastro da sequencia: esocialenvio_rh213_sequencial_seq do campo: rh213_sequencial";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh213_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from esocialenvio_rh213_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $rh213_sequencial)) {
                $this->erro_sql = " Campo rh213_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh213_sequencial = $rh213_sequencial;
            }
        }
        if (($this->rh213_sequencial == null) || ($this->rh213_sequencial == "")) {
            $this->erro_sql = " Campo rh213_sequencial n�o declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into esocialenvio(
                                       rh213_sequencial
                                      ,rh213_evento
                                      ,rh213_empregador
                                      ,rh213_responsavelpreenchimento
                                      ,rh213_dados
                                      ,rh213_md5
                                      ,rh213_situacao
                                      ,rh213_msgretorno
                                      ,rh213_dataprocessamento
                                      ,rh213_ambienteenvio
                                      ,rh213_protocolo
                       )
                values (
                                $this->rh213_sequencial
                               ,$this->rh213_evento
                               ,$this->rh213_empregador
                               ,'$this->rh213_responsavelpreenchimento'
                               ,'$this->rh213_dados'
                               ,'$this->rh213_md5'
                               ,$this->rh213_situacao
                               ,'$this->rh213_msgretorno'
                               ," . ($this->rh213_dataprocessamento == null ? 'NULL' : "'" . $this->rh213_dataprocessamento) . "'" . "
                               ,'$this->rh213_ambienteenvio'
                               ,'$this->rh213_protocolo'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Fila de envio para o eSocial ($this->rh213_sequencial) n�o Inclu�do. Inclus�o Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Fila de envio para o eSocial j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Fila de envio para o eSocial ($this->rh213_sequencial) n�o Inclu�do. Inclus�o Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclus�o efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->rh213_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        /*if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->rh213_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1009543,'$this->rh213_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010244,1009543,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010244,1009544,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_evento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010244,1009545,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010244,1009546,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_responsavelpreenchimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010244,1009547,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_dados')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010244,1009548,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_md5')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010244,1009549,'','" . AddSlashes(pg_result($resaco, 0, 'rh213_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }*/
        return true;
    }
    // funcao para alteracao
    public function alterar($rh213_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update esocialenvio set ";
        $virgula = "";
        if (trim($this->rh213_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"])) {
            $sql  .= $virgula . " rh213_sequencial = $this->rh213_sequencial ";
            $virgula = ",";
            if (trim($this->rh213_sequencial) == null) {
                $this->erro_sql = " Campo C�digo n�o informado.";
                $this->erro_campo = "rh213_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_evento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_evento"])) {
            $sql  .= $virgula . " rh213_evento = $this->rh213_evento ";
            $virgula = ",";
            if (trim($this->rh213_evento) == null) {
                $this->erro_sql = " Campo C�digo do Evento n�o informado.";
                $this->erro_campo = "rh213_evento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_empregador) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_empregador"])) {
            $sql  .= $virgula . " rh213_empregador = $this->rh213_empregador ";
            $virgula = ",";
            if (trim($this->rh213_empregador) == null) {
                $this->erro_sql = " Campo empregador n�o informado.";
                $this->erro_campo = "rh213_empregador";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_responsavelpreenchimento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_responsavelpreenchimento"])) {
            $sql  .= $virgula . " rh213_responsavelpreenchimento = '$this->rh213_responsavelpreenchimento' ";
            $virgula = ",";
            if (trim($this->rh213_responsavelpreenchimento) == null) {
                $this->erro_sql = " Campo Respons�vel Preenchimento n�o informado.";
                $this->erro_campo = "rh213_responsavelpreenchimento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_dados) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_dados"])) {
            $sql  .= $virgula . " rh213_dados = '$this->rh213_dados' ";
            $virgula = ",";
            if (trim($this->rh213_dados) == null) {
                $this->erro_sql = " Campo Dados n�o informado.";
                $this->erro_campo = "rh213_dados";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_md5) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_md5"])) {
            $sql  .= $virgula . " rh213_md5 = '$this->rh213_md5' ";
            $virgula = ",";
            if (trim($this->rh213_md5) == null) {
                $this->erro_sql = " Campo MD5 n�o informado.";
                $this->erro_campo = "rh213_md5";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_situacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_situacao"])) {
            $sql  .= $virgula . " rh213_situacao = $this->rh213_situacao ";
            $virgula = ",";
            if (trim($this->rh213_situacao) == null) {
                $this->erro_sql = " Campo Situacao n�o informado.";
                $this->erro_campo = "rh213_situacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_msgretorno) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_msgretorno"])) {
            $sql  .= $virgula . " rh213_msgretorno = '$this->rh213_msgretorno' ";
            $virgula = ",";
        }
        if (trim($this->rh213_dataprocessamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_dataprocessamento"])) {
            $sql  .= $virgula . " rh213_dataprocessamento = " . ($this->rh213_dataprocessamento == null ? 'NULL' : "'" . $this->rh213_dataprocessamento) . "'" . " ";
            $virgula = ",";
            if (trim($this->rh213_dataprocessamento) == null) {
                $this->erro_sql = " Campo Data Processamento n�o informado.";
                $this->erro_campo = "rh213_dataprocessamento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_ambienteenvio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_ambienteenvio"])) {
            $sql  .= $virgula . " rh213_ambienteenvio = $this->rh213_ambienteenvio ";
            $virgula = ",";
            if (trim($this->rh213_ambienteenvio) == null) {
                $this->erro_sql = " Campo Ambiente de Envio n�o informado.";
                $this->erro_campo = "rh213_ambienteenvio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh213_protocolo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh213_protocolo"])) {
            $sql  .= $virgula . " rh213_protocolo = $this->rh213_protocolo ";
            $virgula = ",";
            if (trim($this->rh213_protocolo) == null) {
                $this->erro_sql = " Campo Ambiente de Envio n�o informado.";
                $this->erro_campo = "rh213_protocolo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($rh213_sequencial != null) {
            $sql .= " rh213_sequencial = $this->rh213_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        /*if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->rh213_sequencial));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1009543,'$this->rh213_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_sequencial"]) || $this->rh213_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009543,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_sequencial')) . "','$this->rh213_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_evento"]) || $this->rh213_evento != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009544,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_evento')) . "','$this->rh213_evento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_empregador"]) || $this->rh213_empregador != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009545,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_empregador')) . "','$this->rh213_empregador'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_responsavelpreenchimento"]) || $this->rh213_responsavelpreenchimento != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009546,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_responsavelpreenchimento')) . "','$this->rh213_responsavelpreenchimento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_dados"]) || $this->rh213_dados != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009547,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_dados')) . "','$this->rh213_dados'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_md5"]) || $this->rh213_md5 != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009548,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_md5')) . "','$this->rh213_md5'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh213_situacao"]) || $this->rh213_situacao != "") {
                        $resac = db_query("insert into db_acount values($acount,1010244,1009549,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh213_situacao')) . "','$this->rh213_situacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }*/
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Fila de envio para o eSocial n�o Alterado. Altera��o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->rh213_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Fila de envio para o eSocial n�o foi Alterado. Altera��o Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->rh213_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->rh213_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    public function excluir($rh213_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        /*if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($rh213_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac  = db_query("insert into db_acountkey values($acount,1009543,'$rh213_sequencial','E')");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009543,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009544,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_evento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009545,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009546,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_responsavelpreenchimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009547,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_dados')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009548,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_md5')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,1010244,1009549,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh213_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }*/
        $sql = " delete from esocialenvio
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh213_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh213_sequencial = $rh213_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Fila de envio para o eSocial n�o Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $rh213_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Fila de envio para o eSocial n�o Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $rh213_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $rh213_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao do recordset
    public function sql_record($sql)
    {
        $result = db_query($sql);
        if (!$result) {
            $this->numrows    = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:esocialenvio";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    public function sql_query($rh213_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql  = "select {$campos}";
        $sql .= "  from esocialenvio ";
        $sql .= "  left join esocialrecibo on rh213_sequencial = rh215_esocialenvio";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh213_sequencial)) {
                $sql2 .= " where esocialenvio.rh213_sequencial = $rh213_sequencial ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }
    // funcao do sql
    public function sql_query_file($rh213_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql  = "select {$campos} ";
        $sql .= "  from esocialenvio ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh213_sequencial)) {
                $sql2 .= " where esocialenvio.rh213_sequencial = $rh213_sequencial ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function checkQueue()
    {
        $sql = "SELECT COUNT(*) as queue FROM esocialenvio WHERE rh213_empregador = " . db_getsession("DB_instit") . " AND rh213_situacao = " . self::SITUACAO_NAO_ENVIADO;
        $result = db_query($sql);
        return db_utils::fieldsMemory($result, 0)->queue > 0;
    }

    public function setSituacaoEnviado($rh213_sequencial)
    {
        $this->rh213_situacao = self::SITUACAO_ENVIADO;
        $this->rh213_sequencial = $rh213_sequencial;
        $this->rh213_msgretorno = '';
        $this->rh213_dataprocessamento = date('Y-m-d h:i:s');
        $this->alterar($rh213_sequencial);
    }

    public function setSituacaoErroEnvio($rh213_sequencial, $rh213_msgretorno)
    {
        $this->rh213_situacao = self::SITUACAO_ERRO_ENVIO;
        $this->rh213_sequencial = $rh213_sequencial;
        $this->rh213_msgretorno = str_replace("'", "\'", $rh213_msgretorno);
        $this->rh213_dataprocessamento = date('Y-m-d h:i:s');
        $this->alterar($rh213_sequencial);
    }

    public function setSituacaoProcessando()
    {
        $this->erro_status = "1";
        $sql = "UPDATE esocialenvio SET rh213_situacao = " . self::SITUACAO_PROCESSANDO . " WHERE rh213_situacao = " . self::SITUACAO_NAO_ENVIADO;
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Fila de envio para o eSocial n�o Atualizada. Atualiza��o Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }
    }

    public function setProtocolo($rh213_sequencial, $rh213_protocolo)
    {
        $this->erro_status = "1";
        $sql = "UPDATE esocialenvio SET rh213_protocolo = '{$rh213_protocolo}' WHERE rh213_sequencial = {$rh213_sequencial}";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Fila de envio para o eSocial n�o Atualizada. Atualiza��o Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }
    }

    public function deleteErros()
    {
        $this->erro_status = "1";

        // SQL para selecionar os registros a serem exclu�dos
        $sql = "SELECT rh213_sequencial FROM esocialenvio
                LEFT JOIN esocialrecibo ON rh213_sequencial = rh215_esocialenvio 
                WHERE rh213_empregador = (SELECT numcgm FROM db_config WHERE codigo = " . db_getsession("DB_instit") . ")
                AND rh213_situacao = " . self::SITUACAO_ERRO_ENVIO . "
                AND rh215_esocialenvio IS NULL"; // Adiciona condi��o para verificar se n�o h� dados na tabela esocialrecibo

        $rsQuery = db_query($sql);
        //db_criatabela($rsQuery);exit;

        if (!$rsQuery) {
            throw new Exception(pg_last_error());
        }

        // Inicia uma transa��o
        db_inicio_transacao();

        for ($iCont = 0;$iCont < pg_num_rows($rsQuery); $iCont++) {
            $dados = db_utils::fieldsMemory($rsQuery,$iCont);
            $sqlDelete = "DELETE FROM esocialenvio WHERE rh213_sequencial = {$dados->rh213_sequencial}";
            $result = db_query($sqlDelete);
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Falha ao excluir registro com erro. Exclus�o Abortada.\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                $this->numrows_excluir = 0;
                return false;
            }
        }

        db_fim_transacao(); // Conclui a transa��o se tudo ocorreu bem

        return true; // Retorna verdadeiro se a exclus�o foi bem-sucedida
    }
}
