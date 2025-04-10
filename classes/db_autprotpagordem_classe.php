<?php
//MODULO: protocolo
//CLASSE DA ENTIDADE autprotpagordem
class cl_autprotpagordem {
  // cria variaveis de erro
  public $rotulo     = null;
  public $query_sql  = null;
  public $numrows    = 0;
  public $numrows_incluir = 0;
  public $numrows_alterar = 0;
  public $numrows_excluir = 0;
  public $erro_status= null;
  public $erro_sql   = null;
  public $erro_banco = null;
  public $erro_msg   = null;
  public $erro_campo = null;
  public $pagina_retorno = null;
  // cria variaveis do arquivo
  public $p107_sequencial = 0;
  public $p107_autorizado = 'f';
  public $p107_codord = 0;
  public $p107_protocolo = 0;
  public $p107_dt_cadastro_dia = null;
  public $p107_dt_cadastro_mes = null;
  public $p107_dt_cadastro_ano = null;
  public $p107_dt_cadastro = null;
  // cria propriedade com as variaveis do arquivo
  public $campos = "
                 p107_sequencial = int4 = p107_sequencial
                 p107_autorizado = bool = Autorizado
                 p107_codord = int4 = Ordem
                 p107_protocolo = int4 = Protocolo
                 p107_dt_cadastro = date = Data de Cadastro
                 ";

  //funcao construtor da classe
  function __construct() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("autprotpagordem");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  //funcao erro
  function erro($mostra,$retorna) {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )) {
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if ($retorna==true) {
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }

  // funcao para atualizar campos
  function atualizacampos($exclusao=false) {
    if ($exclusao==false) {
       $this->p107_sequencial = ($this->p107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_sequencial"]:$this->p107_sequencial);
       $this->p107_autorizado = ($this->p107_autorizado == "f"?@$GLOBALS["HTTP_POST_VARS"]["p107_autorizado"]:$this->p107_autorizado);
       $this->p107_codord = ($this->p107_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_codord"]:$this->p107_codord);
       $this->p107_protocolo = ($this->p107_protocolo == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_protocolo"]:$this->p107_protocolo);
       if ($this->p107_dt_cadastro == "") {
         $this->p107_dt_cadastro_dia = ($this->p107_dt_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro_dia"]:$this->p107_dt_cadastro_dia);
         $this->p107_dt_cadastro_mes = ($this->p107_dt_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro_mes"]:$this->p107_dt_cadastro_mes);
         $this->p107_dt_cadastro_ano = ($this->p107_dt_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro_ano"]:$this->p107_dt_cadastro_ano);
         if ($this->p107_dt_cadastro_dia != "") {
            $this->p107_dt_cadastro = $this->p107_dt_cadastro_ano."-".$this->p107_dt_cadastro_mes."-".$this->p107_dt_cadastro_dia;
         }
       }
     } else {
       $this->p107_sequencial = ($this->p107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p107_sequencial"]:$this->p107_sequencial);
     }
   }

  // funcao para inclusao
  function incluir ($p107_sequencial) {
      $this->atualizacampos();
     if ($this->p107_autorizado == null ) {
       $this->erro_sql = " Campo Autorizado n�o informado.";
       $this->erro_campo = "p107_autorizado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->p107_codord == null ) {
       $this->erro_sql = " Campo Ordem n�o informado.";
       $this->erro_campo = "p107_codord";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->p107_protocolo == null ) {
       $this->erro_sql = " Campo Protocolo n�o informado.";
       $this->erro_campo = "p107_protocolo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->p107_dt_cadastro == null ) {
       $this->erro_sql = " Campo Data de Cadastro n�o informado.";
       $this->erro_campo = "p107_dt_cadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($p107_sequencial == "" || $p107_sequencial == null ) {
       $result = db_query("select nextval('autprotpagordem_p107_sequencial_seq')");
       if ($result==false) {
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: autprotpagordem_p107_sequencial_seq do campo: p107_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->p107_sequencial = pg_result($result,0,0);
     } else {
       $result = db_query("select last_value from autprotpagordem_p107_sequencial_seq");
       if (($result != false) && (pg_result($result,0,0) < $p107_sequencial)) {
         $this->erro_sql = " Campo p107_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       } else {
         $this->p107_sequencial = $p107_sequencial;
       }
     }
     if (($this->p107_sequencial == null) || ($this->p107_sequencial == "") ) {
       $this->erro_sql = " Campo p107_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autprotpagordem(
                                       p107_sequencial
                                      ,p107_autorizado
                                      ,p107_codord
                                      ,p107_protocolo
                                      ,p107_dt_cadastro
                       )
                values (
                                $this->p107_sequencial
                               ,'$this->p107_autorizado'
                               ,$this->p107_codord
                               ,$this->p107_protocolo
                               ,".($this->p107_dt_cadastro == "null" || $this->p107_dt_cadastro == ""?"null":"'".$this->p107_dt_cadastro."'")."
                      )";
     $result = db_query($sql);
     if ($result==false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
         $this->erro_sql   = "autprotpagordem ($this->p107_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "autprotpagordem j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql   = "autprotpagordem ($this->p107_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p107_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p107_sequencial  ));
       if (($resaco!=false)||($this->numrows!=0)) {

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009352,'$this->p107_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010208,1009352,'','".AddSlashes(pg_result($resaco,0,'p107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010208,1009360,'','".AddSlashes(pg_result($resaco,0,'p107_autorizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010208,1009353,'','".AddSlashes(pg_result($resaco,0,'p107_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010208,1009354,'','".AddSlashes(pg_result($resaco,0,'p107_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010208,1009355,'','".AddSlashes(pg_result($resaco,0,'p107_dt_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
    }
    return true;
  }

  // funcao para alteracao
  function alterar ($p107_sequencial=null) {
      $this->atualizacampos();
     $sql = " update autprotpagordem set ";
     $virgula = "";
     if (trim($this->p107_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p107_sequencial"])) {
       $sql  .= $virgula." p107_sequencial = $this->p107_sequencial ";
       $virgula = ",";
       if (trim($this->p107_sequencial) == null ) {
         $this->erro_sql = " Campo p107_sequencial n�o informado.";
         $this->erro_campo = "p107_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p107_autorizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p107_autorizado"])) {
       $sql  .= $virgula." p107_autorizado = '$this->p107_autorizado' ";
       $virgula = ",";
       if (trim($this->p107_autorizado) == null ) {
         $this->erro_sql = " Campo Autorizado n�o informado.";
         $this->erro_campo = "p107_autorizado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p107_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p107_codord"])) {
       $sql  .= $virgula." p107_codord = $this->p107_codord ";
       $virgula = ",";
       if (trim($this->p107_codord) == null ) {
         $this->erro_sql = " Campo Ordem n�o informado.";
         $this->erro_campo = "p107_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p107_protocolo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p107_protocolo"])) {
       $sql  .= $virgula." p107_protocolo = $this->p107_protocolo ";
       $virgula = ",";
       if (trim($this->p107_protocolo) == null ) {
         $this->erro_sql = " Campo Protocolo n�o informado.";
         $this->erro_campo = "p107_protocolo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p107_dt_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro_dia"] !="") ) {
       $sql  .= $virgula." p107_dt_cadastro = '$this->p107_dt_cadastro' ";
       $virgula = ",";
       if (trim($this->p107_dt_cadastro) == null ) {
         $this->erro_sql = " Campo Data de Cadastro n�o informado.";
         $this->erro_campo = "p107_dt_cadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if (isset($GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro_dia"])) {
         $sql  .= $virgula." p107_dt_cadastro = null ";
         $virgula = ",";
         if (trim($this->p107_dt_cadastro) == null ) {
           $this->erro_sql = " Campo Data de Cadastro n�o informado.";
           $this->erro_campo = "p107_dt_cadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if ($p107_sequencial!=null) {
       $sql .= " p107_sequencial = $this->p107_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p107_sequencial));
       if ($this->numrows>0) {

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009352,'$this->p107_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p107_sequencial"]) || $this->p107_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010208,1009352,'".AddSlashes(pg_result($resaco,$conresaco,'p107_sequencial'))."','$this->p107_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p107_autorizado"]) || $this->p107_autorizado != "")
             $resac = db_query("insert into db_acount values($acount,1010208,1009360,'".AddSlashes(pg_result($resaco,$conresaco,'p107_autorizado'))."','$this->p107_autorizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p107_codord"]) || $this->p107_codord != "")
             $resac = db_query("insert into db_acount values($acount,1010208,1009353,'".AddSlashes(pg_result($resaco,$conresaco,'p107_codord'))."','$this->p107_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p107_protocolo"]) || $this->p107_protocolo != "")
             $resac = db_query("insert into db_acount values($acount,1010208,1009354,'".AddSlashes(pg_result($resaco,$conresaco,'p107_protocolo'))."','$this->p107_protocolo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p107_dt_cadastro"]) || $this->p107_dt_cadastro != "")
             $resac = db_query("insert into db_acount values($acount,1010208,1009355,'".AddSlashes(pg_result($resaco,$conresaco,'p107_dt_cadastro'))."','$this->p107_dt_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if ($result==false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autprotpagordem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p107_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "autprotpagordem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p107_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p107_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir ($p107_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($p107_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009352,'$p107_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010208,1009352,'','".AddSlashes(pg_result($resaco,$iresaco,'p107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010208,1009360,'','".AddSlashes(pg_result($resaco,$iresaco,'p107_autorizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010208,1009353,'','".AddSlashes(pg_result($resaco,$iresaco,'p107_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010208,1009354,'','".AddSlashes(pg_result($resaco,$iresaco,'p107_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010208,1009355,'','".AddSlashes(pg_result($resaco,$iresaco,'p107_dt_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from autprotpagordem
                    where ";
     $sql2 = "";
     if ($dbwhere==null || $dbwhere =="") {
        if ($p107_sequencial != "") {
          if ($sql2!="") {
            $sql2 .= " and ";
          }
          $sql2 .= " p107_sequencial = $p107_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result==false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autprotpagordem nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p107_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "autprotpagordem nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p107_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p107_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
      }
    }
  }

  // funcao do recordset
  function sql_record($sql) {
     $result = db_query($sql);
     if ($result==false) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if ($this->numrows==0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:autprotpagordem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    return $result;
  }

  // funcao do sql
  function sql_query ( $p107_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
     $sql = "select ";
     if ($campos != "*" ) {
       $campos_sql = explode("#", $campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }
     $sql .= " from autprotpagordem ";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = autprotpagordem.p107_codord";
     $sql .= "      inner join protocolos  on  protocolos.p101_sequencial = autprotpagordem.p107_protocolo";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pagordem.e50_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = protocolos.p101_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protocolos.p101_coddeptoorigem and  db_depart.coddepto = protocolos.p101_coddeptodestino";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($p107_sequencial!=null ) {
         $sql2 .= " where autprotpagordem.p107_sequencial = $p107_sequencial ";
       }
     } else if ($dbwhere != "") {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if ($ordem != null ) {
       $sql .= " order by ";
       $campos_sql = explode("#", $ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
      }
    }
    return $sql;
  }

  // funcao do sql
  function sql_query_file ( $p107_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
     $sql = "select ";
     if ($campos != "*" ) {
       $campos_sql = explode("#", $campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }
     $sql .= " from autprotpagordem ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($p107_sequencial!=null ) {
         $sql2 .= " where autprotpagordem.p107_sequencial = $p107_sequencial ";
       }
     } else if ($dbwhere != "") {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if ($ordem != null ) {
       $sql .= " order by ";
       $campos_sql = explode("#", $ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
      }
    }
    return $sql;
  }
}
?>
