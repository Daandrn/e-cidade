<?
//MODULO: sicom
//CLASSE DA ENTIDADE dclrf2014
class cl_dclrf2014 { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $si157_sequencial = 0; 
   var $si157_codorgao = 0; 
   var $si157_vlsaldoatualconcgarantia = 0; 
   var $si157_recprivatizacao = 0; 
   var $si157_vlliqincentcontrib = 0; 
   var $si157_vlliqincentinstfinanc = 0; 
   var $si157_vlirpnpincentcontrib = 0; 
   var $si157_vlirpnpincentinstfinanc = 0; 
   var $si157_mes = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 si157_sequencial = int8 = sequencial 
                 si157_codorgao = int8 = C�digo do �rg�o 
                 si157_vlsaldoatualconcgarantia = float8 = Saldo atual das  concess�es 
                 si157_recprivatizacao = float8 = Receita de  Privatiza��o 
                 si157_vlliqincentcontrib = float8 = Valor Liquidado  de Incentivo 
                 si157_vlliqincentinstfinanc = float8 = Valor Liquidado  de Incentivo 
                 si157_vlirpnpincentcontrib = float8 = Valor Inscrito em  Restos 
                 si157_vlirpnpincentinstfinanc = float8 = Valor Inscrito em  Restos a Pagar 
                 si157_mes = int8 = M�s 
                 ";
   //funcao construtor da classe 
   function cl_dclrf2014() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dclrf2014"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->si157_sequencial = ($this->si157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_sequencial"]:$this->si157_sequencial);
       $this->si157_codorgao = ($this->si157_codorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_codorgao"]:$this->si157_codorgao);
       $this->si157_vlsaldoatualconcgarantia = ($this->si157_vlsaldoatualconcgarantia == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_vlsaldoatualconcgarantia"]:$this->si157_vlsaldoatualconcgarantia);
       $this->si157_recprivatizacao = ($this->si157_recprivatizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_recprivatizacao"]:$this->si157_recprivatizacao);
       $this->si157_vlliqincentcontrib = ($this->si157_vlliqincentcontrib == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_vlliqincentcontrib"]:$this->si157_vlliqincentcontrib);
       $this->si157_vlliqincentinstfinanc = ($this->si157_vlliqincentinstfinanc == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_vlliqincentinstfinanc"]:$this->si157_vlliqincentinstfinanc);
       $this->si157_vlirpnpincentcontrib = ($this->si157_vlirpnpincentcontrib == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_vlirpnpincentcontrib"]:$this->si157_vlirpnpincentcontrib);
       $this->si157_vlirpnpincentinstfinanc = ($this->si157_vlirpnpincentinstfinanc == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_vlirpnpincentinstfinanc"]:$this->si157_vlirpnpincentinstfinanc);
       $this->si157_mes = ($this->si157_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_mes"]:$this->si157_mes);
     }else{
       $this->si157_sequencial = ($this->si157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si157_sequencial"]:$this->si157_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($si157_sequencial){ 
      $this->atualizacampos();
     if($this->si157_codorgao == null ){ 
       $this->erro_sql = " Campo C�digo do �rg�o nao Informado.";
       $this->erro_campo = "si157_codorgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_vlsaldoatualconcgarantia == null ){ 
       $this->erro_sql = " Campo Saldo atual das  concess�es nao Informado.";
       $this->erro_campo = "si157_vlsaldoatualconcgarantia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_recprivatizacao == null ){ 
       $this->erro_sql = " Campo Receita de  Privatiza��o nao Informado.";
       $this->erro_campo = "si157_recprivatizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_vlliqincentcontrib == null ){ 
       $this->erro_sql = " Campo Valor Liquidado  de Incentivo nao Informado.";
       $this->erro_campo = "si157_vlliqincentcontrib";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_vlliqincentinstfinanc == null ){ 
       $this->erro_sql = " Campo Valor Liquidado  de Incentivo nao Informado.";
       $this->erro_campo = "si157_vlliqincentinstfinanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_vlirpnpincentcontrib == null ){ 
       $this->erro_sql = " Campo Valor Inscrito em  Restos nao Informado.";
       $this->erro_campo = "si157_vlirpnpincentcontrib";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_vlirpnpincentinstfinanc == null ){ 
       $this->erro_sql = " Campo Valor Inscrito em  Restos a Pagar nao Informado.";
       $this->erro_campo = "si157_vlirpnpincentinstfinanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si157_mes == null ){ 
       $this->erro_sql = " Campo M�s nao Informado.";
       $this->erro_campo = "si157_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($si157_sequencial == "" || $si157_sequencial == null ){
       $result = db_query("select nextval('dclrf2014_si157_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dclrf2014_si157_sequencial_seq do campo: si157_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->si157_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from dclrf2014_si157_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si157_sequencial)){
         $this->erro_sql = " Campo si157_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si157_sequencial = $si157_sequencial; 
       }
     }
     if(($this->si157_sequencial == null) || ($this->si157_sequencial == "") ){ 
       $this->erro_sql = " Campo si157_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dclrf2014(
                                       si157_sequencial 
                                      ,si157_codorgao 
                                      ,si157_vlsaldoatualconcgarantia 
                                      ,si157_recprivatizacao 
                                      ,si157_vlliqincentcontrib 
                                      ,si157_vlliqincentinstfinanc 
                                      ,si157_vlirpnpincentcontrib 
                                      ,si157_vlirpnpincentinstfinanc 
                                      ,si157_mes 
                       )
                values (
                                $this->si157_sequencial 
                               ,$this->si157_codorgao 
                               ,$this->si157_vlsaldoatualconcgarantia 
                               ,$this->si157_recprivatizacao 
                               ,$this->si157_vlliqincentcontrib 
                               ,$this->si157_vlliqincentinstfinanc 
                               ,$this->si157_vlirpnpincentcontrib 
                               ,$this->si157_vlirpnpincentinstfinanc 
                               ,$this->si157_mes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dclrf2014 ($this->si157_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "dclrf2014 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "dclrf2014 ($this->si157_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si157_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->si157_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2011199,'$this->si157_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2010386,2011199,'','".AddSlashes(pg_result($resaco,0,'si157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011200,'','".AddSlashes(pg_result($resaco,0,'si157_codorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011201,'','".AddSlashes(pg_result($resaco,0,'si157_vlsaldoatualconcgarantia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011202,'','".AddSlashes(pg_result($resaco,0,'si157_recprivatizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011203,'','".AddSlashes(pg_result($resaco,0,'si157_vlliqincentcontrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011204,'','".AddSlashes(pg_result($resaco,0,'si157_vlliqincentinstfinanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011205,'','".AddSlashes(pg_result($resaco,0,'si157_vlirpnpincentcontrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011206,'','".AddSlashes(pg_result($resaco,0,'si157_vlirpnpincentinstfinanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010386,2011207,'','".AddSlashes(pg_result($resaco,0,'si157_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($si157_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update dclrf2014 set ";
     $virgula = "";
     if(trim($this->si157_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_sequencial"])){ 
       $sql  .= $virgula." si157_sequencial = $this->si157_sequencial ";
       $virgula = ",";
       if(trim($this->si157_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "si157_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_codorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_codorgao"])){ 
       $sql  .= $virgula." si157_codorgao = $this->si157_codorgao ";
       $virgula = ",";
       if(trim($this->si157_codorgao) == null ){ 
         $this->erro_sql = " Campo C�digo do �rg�o nao Informado.";
         $this->erro_campo = "si157_codorgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_vlsaldoatualconcgarantia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_vlsaldoatualconcgarantia"])){ 
       $sql  .= $virgula." si157_vlsaldoatualconcgarantia = $this->si157_vlsaldoatualconcgarantia ";
       $virgula = ",";
       if(trim($this->si157_vlsaldoatualconcgarantia) == null ){ 
         $this->erro_sql = " Campo Saldo atual das  concess�es nao Informado.";
         $this->erro_campo = "si157_vlsaldoatualconcgarantia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_recprivatizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_recprivatizacao"])){ 
       $sql  .= $virgula." si157_recprivatizacao = $this->si157_recprivatizacao ";
       $virgula = ",";
       if(trim($this->si157_recprivatizacao) == null ){ 
         $this->erro_sql = " Campo Receita de  Privatiza��o nao Informado.";
         $this->erro_campo = "si157_recprivatizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_vlliqincentcontrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_vlliqincentcontrib"])){ 
       $sql  .= $virgula." si157_vlliqincentcontrib = $this->si157_vlliqincentcontrib ";
       $virgula = ",";
       if(trim($this->si157_vlliqincentcontrib) == null ){ 
         $this->erro_sql = " Campo Valor Liquidado  de Incentivo nao Informado.";
         $this->erro_campo = "si157_vlliqincentcontrib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_vlliqincentinstfinanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_vlliqincentinstfinanc"])){ 
       $sql  .= $virgula." si157_vlliqincentinstfinanc = $this->si157_vlliqincentinstfinanc ";
       $virgula = ",";
       if(trim($this->si157_vlliqincentinstfinanc) == null ){ 
         $this->erro_sql = " Campo Valor Liquidado  de Incentivo nao Informado.";
         $this->erro_campo = "si157_vlliqincentinstfinanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_vlirpnpincentcontrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_vlirpnpincentcontrib"])){ 
       $sql  .= $virgula." si157_vlirpnpincentcontrib = $this->si157_vlirpnpincentcontrib ";
       $virgula = ",";
       if(trim($this->si157_vlirpnpincentcontrib) == null ){ 
         $this->erro_sql = " Campo Valor Inscrito em  Restos nao Informado.";
         $this->erro_campo = "si157_vlirpnpincentcontrib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_vlirpnpincentinstfinanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_vlirpnpincentinstfinanc"])){ 
       $sql  .= $virgula." si157_vlirpnpincentinstfinanc = $this->si157_vlirpnpincentinstfinanc ";
       $virgula = ",";
       if(trim($this->si157_vlirpnpincentinstfinanc) == null ){ 
         $this->erro_sql = " Campo Valor Inscrito em  Restos a Pagar nao Informado.";
         $this->erro_campo = "si157_vlirpnpincentinstfinanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si157_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si157_mes"])){ 
       $sql  .= $virgula." si157_mes = $this->si157_mes ";
       $virgula = ",";
       if(trim($this->si157_mes) == null ){ 
         $this->erro_sql = " Campo M�s nao Informado.";
         $this->erro_campo = "si157_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si157_sequencial!=null){
       $sql .= " si157_sequencial = $this->si157_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->si157_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2011199,'$this->si157_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_sequencial"]) || $this->si157_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011199,'".AddSlashes(pg_result($resaco,$conresaco,'si157_sequencial'))."','$this->si157_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_codorgao"]) || $this->si157_codorgao != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011200,'".AddSlashes(pg_result($resaco,$conresaco,'si157_codorgao'))."','$this->si157_codorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_vlsaldoatualconcgarantia"]) || $this->si157_vlsaldoatualconcgarantia != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011201,'".AddSlashes(pg_result($resaco,$conresaco,'si157_vlsaldoatualconcgarantia'))."','$this->si157_vlsaldoatualconcgarantia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_recprivatizacao"]) || $this->si157_recprivatizacao != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011202,'".AddSlashes(pg_result($resaco,$conresaco,'si157_recprivatizacao'))."','$this->si157_recprivatizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_vlliqincentcontrib"]) || $this->si157_vlliqincentcontrib != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011203,'".AddSlashes(pg_result($resaco,$conresaco,'si157_vlliqincentcontrib'))."','$this->si157_vlliqincentcontrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_vlliqincentinstfinanc"]) || $this->si157_vlliqincentinstfinanc != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011204,'".AddSlashes(pg_result($resaco,$conresaco,'si157_vlliqincentinstfinanc'))."','$this->si157_vlliqincentinstfinanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_vlirpnpincentcontrib"]) || $this->si157_vlirpnpincentcontrib != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011205,'".AddSlashes(pg_result($resaco,$conresaco,'si157_vlirpnpincentcontrib'))."','$this->si157_vlirpnpincentcontrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_vlirpnpincentinstfinanc"]) || $this->si157_vlirpnpincentinstfinanc != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011206,'".AddSlashes(pg_result($resaco,$conresaco,'si157_vlirpnpincentinstfinanc'))."','$this->si157_vlirpnpincentinstfinanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si157_mes"]) || $this->si157_mes != "")
           $resac = db_query("insert into db_acount values($acount,2010386,2011207,'".AddSlashes(pg_result($resaco,$conresaco,'si157_mes'))."','$this->si157_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dclrf2014 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si157_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dclrf2014 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($si157_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($si157_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2011199,'$si157_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2010386,2011199,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011200,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_codorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011201,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_vlsaldoatualconcgarantia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011202,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_recprivatizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011203,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_vlliqincentcontrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011204,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_vlliqincentinstfinanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011205,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_vlirpnpincentcontrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011206,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_vlirpnpincentinstfinanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010386,2011207,'','".AddSlashes(pg_result($resaco,$iresaco,'si157_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from dclrf2014
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si157_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si157_sequencial = $si157_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "dclrf2014 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si157_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dclrf2014 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si157_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$si157_sequencial;
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
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:dclrf2014";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $si157_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from dclrf2014 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si157_sequencial!=null ){
         $sql2 .= " where dclrf2014.si157_sequencial = $si157_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $si157_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from dclrf2014 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si157_sequencial!=null ){
         $sql2 .= " where dclrf2014.si157_sequencial = $si157_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>
