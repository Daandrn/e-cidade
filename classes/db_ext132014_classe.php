<?
//MODULO: sicom
//CLASSE DA ENTIDADE ext132014
class cl_ext132014 { 
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
   var $si127_sequencial = 0; 
   var $si127_tiporegistro = 0; 
   var $si127_codreduzidoop = 0; 
   var $si127_tipodocumentoop = null; 
   var $si127_nrodocumento = null; 
   var $si127_codctb = 0; 
   var $si127_dtemissao_dia = null; 
   var $si127_dtemissao_mes = null; 
   var $si127_dtemissao_ano = null; 
   var $si127_dtemissao = null; 
   var $si127_vldocumento = 0; 
   var $si127_mes = 0; 
   var $si127_reg10 = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 si127_sequencial = int8 = sequencial 
                 si127_tiporegistro = int8 = Tipo do  registro 
                 si127_codreduzidoop = int8 = C�digo  Identificador da  Ordem 
                 si127_tipodocumentoop = varchar(2) = Tipo do  documento 
                 si127_nrodocumento = varchar(15) = N�mero do  Documento 
                 si127_codctb = int8 = Identificador da Conta Banc�ria 
                 si127_dtemissao = date = Data de emiss�o  do documento 
                 si127_vldocumento = float8 = Valor da OP 
                 si127_mes = int8 = M�s 
                 si127_reg10 = int8 = reg10 
                 ";
   //funcao construtor da classe 
   function cl_ext132014() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ext132014"); 
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
       $this->si127_sequencial = ($this->si127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_sequencial"]:$this->si127_sequencial);
       $this->si127_tiporegistro = ($this->si127_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_tiporegistro"]:$this->si127_tiporegistro);
       $this->si127_codreduzidoop = ($this->si127_codreduzidoop == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_codreduzidoop"]:$this->si127_codreduzidoop);
       $this->si127_tipodocumentoop = ($this->si127_tipodocumentoop == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_tipodocumentoop"]:$this->si127_tipodocumentoop);
       $this->si127_nrodocumento = ($this->si127_nrodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_nrodocumento"]:$this->si127_nrodocumento);
       $this->si127_codctb = ($this->si127_codctb == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_codctb"]:$this->si127_codctb);
       if($this->si127_dtemissao == ""){
         $this->si127_dtemissao_dia = ($this->si127_dtemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_dtemissao_dia"]:$this->si127_dtemissao_dia);
         $this->si127_dtemissao_mes = ($this->si127_dtemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_dtemissao_mes"]:$this->si127_dtemissao_mes);
         $this->si127_dtemissao_ano = ($this->si127_dtemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_dtemissao_ano"]:$this->si127_dtemissao_ano);
         if($this->si127_dtemissao_dia != ""){
            $this->si127_dtemissao = $this->si127_dtemissao_ano."-".$this->si127_dtemissao_mes."-".$this->si127_dtemissao_dia;
         }
       }
       $this->si127_vldocumento = ($this->si127_vldocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_vldocumento"]:$this->si127_vldocumento);
       $this->si127_mes = ($this->si127_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_mes"]:$this->si127_mes);
       $this->si127_reg10 = ($this->si127_reg10 == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_reg10"]:$this->si127_reg10);
     }else{
       $this->si127_sequencial = ($this->si127_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si127_sequencial"]:$this->si127_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($si127_sequencial){ 
      $this->atualizacampos();
     if($this->si127_tiporegistro == null ){ 
       $this->erro_sql = " Campo Tipo do  registro nao Informado.";
       $this->erro_campo = "si127_tiporegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si127_codreduzidoop == null ){ 
       $this->erro_sql = " Campo C�digo  Identificador da  Ordem nao Informado.";
       $this->erro_campo = "si127_codreduzidoop";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si127_tipodocumentoop == null ){ 
       $this->erro_sql = " Campo Tipo do  documento nao Informado.";
       $this->erro_campo = "si127_tipodocumentoop";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si127_codctb == null ){ 
       $this->si127_codctb = "0";
     }
     if($this->si127_dtemissao == null ){ 
       $this->erro_sql = " Campo Data de emiss�o  do documento nao Informado.";
       $this->erro_campo = "si127_dtemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si127_vldocumento == null ){ 
       $this->erro_sql = " Campo Valor da OP nao Informado.";
       $this->erro_campo = "si127_vldocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si127_mes == null ){ 
       $this->erro_sql = " Campo M�s nao Informado.";
       $this->erro_campo = "si127_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si127_reg10 == null ){ 
       $this->erro_sql = " Campo reg10 nao Informado.";
       $this->erro_campo = "si127_reg10";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($si127_sequencial == "" || $si127_sequencial == null ){
       $result = db_query("select nextval('ext132014_si127_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ext132014_si127_sequencial_seq do campo: si127_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->si127_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ext132014_si127_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si127_sequencial)){
         $this->erro_sql = " Campo si127_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si127_sequencial = $si127_sequencial; 
       }
     }
     if(($this->si127_sequencial == null) || ($this->si127_sequencial == "") ){ 
       $this->erro_sql = " Campo si127_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ext132014(
                                       si127_sequencial 
                                      ,si127_tiporegistro 
                                      ,si127_codreduzidoop 
                                      ,si127_tipodocumentoop 
                                      ,si127_nrodocumento 
                                      ,si127_codctb 
                                      ,si127_dtemissao 
                                      ,si127_vldocumento 
                                      ,si127_mes 
                                      ,si127_reg10 
                       )
                values (
                                $this->si127_sequencial 
                               ,$this->si127_tiporegistro 
                               ,$this->si127_codreduzidoop 
                               ,'$this->si127_tipodocumentoop' 
                               ,'$this->si127_nrodocumento' 
                               ,$this->si127_codctb 
                               ,".($this->si127_dtemissao == "null" || $this->si127_dtemissao == ""?"null":"'".$this->si127_dtemissao."'")." 
                               ,$this->si127_vldocumento 
                               ,$this->si127_mes 
                               ,$this->si127_reg10 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ext132014 ($this->si127_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ext132014 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ext132014 ($this->si127_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si127_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->si127_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2010878,'$this->si127_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2010356,2010878,'','".AddSlashes(pg_result($resaco,0,'si127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010879,'','".AddSlashes(pg_result($resaco,0,'si127_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010880,'','".AddSlashes(pg_result($resaco,0,'si127_codreduzidoop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010881,'','".AddSlashes(pg_result($resaco,0,'si127_tipodocumentoop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010882,'','".AddSlashes(pg_result($resaco,0,'si127_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010883,'','".AddSlashes(pg_result($resaco,0,'si127_codctb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010884,'','".AddSlashes(pg_result($resaco,0,'si127_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010885,'','".AddSlashes(pg_result($resaco,0,'si127_vldocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010886,'','".AddSlashes(pg_result($resaco,0,'si127_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2010356,2010887,'','".AddSlashes(pg_result($resaco,0,'si127_reg10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($si127_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ext132014 set ";
     $virgula = "";
     if(trim($this->si127_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_sequencial"])){ 
       $sql  .= $virgula." si127_sequencial = $this->si127_sequencial ";
       $virgula = ",";
       if(trim($this->si127_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "si127_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si127_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_tiporegistro"])){ 
       $sql  .= $virgula." si127_tiporegistro = $this->si127_tiporegistro ";
       $virgula = ",";
       if(trim($this->si127_tiporegistro) == null ){ 
         $this->erro_sql = " Campo Tipo do  registro nao Informado.";
         $this->erro_campo = "si127_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si127_codreduzidoop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_codreduzidoop"])){ 
       $sql  .= $virgula." si127_codreduzidoop = $this->si127_codreduzidoop ";
       $virgula = ",";
       if(trim($this->si127_codreduzidoop) == null ){ 
         $this->erro_sql = " Campo C�digo  Identificador da  Ordem nao Informado.";
         $this->erro_campo = "si127_codreduzidoop";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si127_tipodocumentoop)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_tipodocumentoop"])){ 
       $sql  .= $virgula." si127_tipodocumentoop = '$this->si127_tipodocumentoop' ";
       $virgula = ",";
       if(trim($this->si127_tipodocumentoop) == null ){ 
         $this->erro_sql = " Campo Tipo do  documento nao Informado.";
         $this->erro_campo = "si127_tipodocumentoop";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si127_nrodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_nrodocumento"])){ 
       $sql  .= $virgula." si127_nrodocumento = '$this->si127_nrodocumento' ";
       $virgula = ",";
     }
     if(trim($this->si127_codctb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_codctb"])){ 
        if(trim($this->si127_codctb)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si127_codctb"])){ 
           $this->si127_codctb = "0" ; 
        } 
       $sql  .= $virgula." si127_codctb = $this->si127_codctb ";
       $virgula = ",";
     }
     if(trim($this->si127_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si127_dtemissao_dia"] !="") ){ 
       $sql  .= $virgula." si127_dtemissao = '$this->si127_dtemissao' ";
       $virgula = ",";
       if(trim($this->si127_dtemissao) == null ){ 
         $this->erro_sql = " Campo Data de emiss�o  do documento nao Informado.";
         $this->erro_campo = "si127_dtemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["si127_dtemissao_dia"])){ 
         $sql  .= $virgula." si127_dtemissao = null ";
         $virgula = ",";
         if(trim($this->si127_dtemissao) == null ){ 
           $this->erro_sql = " Campo Data de emiss�o  do documento nao Informado.";
           $this->erro_campo = "si127_dtemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->si127_vldocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_vldocumento"])){ 
       $sql  .= $virgula." si127_vldocumento = $this->si127_vldocumento ";
       $virgula = ",";
       if(trim($this->si127_vldocumento) == null ){ 
         $this->erro_sql = " Campo Valor da OP nao Informado.";
         $this->erro_campo = "si127_vldocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si127_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_mes"])){ 
       $sql  .= $virgula." si127_mes = $this->si127_mes ";
       $virgula = ",";
       if(trim($this->si127_mes) == null ){ 
         $this->erro_sql = " Campo M�s nao Informado.";
         $this->erro_campo = "si127_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si127_reg10)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si127_reg10"])){ 
       $sql  .= $virgula." si127_reg10 = $this->si127_reg10 ";
       $virgula = ",";
       if(trim($this->si127_reg10) == null ){ 
         $this->erro_sql = " Campo reg10 nao Informado.";
         $this->erro_campo = "si127_reg10";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si127_sequencial!=null){
       $sql .= " si127_sequencial = $this->si127_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->si127_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2010878,'$this->si127_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_sequencial"]) || $this->si127_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010878,'".AddSlashes(pg_result($resaco,$conresaco,'si127_sequencial'))."','$this->si127_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_tiporegistro"]) || $this->si127_tiporegistro != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010879,'".AddSlashes(pg_result($resaco,$conresaco,'si127_tiporegistro'))."','$this->si127_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_codreduzidoop"]) || $this->si127_codreduzidoop != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010880,'".AddSlashes(pg_result($resaco,$conresaco,'si127_codreduzidoop'))."','$this->si127_codreduzidoop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_tipodocumentoop"]) || $this->si127_tipodocumentoop != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010881,'".AddSlashes(pg_result($resaco,$conresaco,'si127_tipodocumentoop'))."','$this->si127_tipodocumentoop',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_nrodocumento"]) || $this->si127_nrodocumento != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010882,'".AddSlashes(pg_result($resaco,$conresaco,'si127_nrodocumento'))."','$this->si127_nrodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_codctb"]) || $this->si127_codctb != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010883,'".AddSlashes(pg_result($resaco,$conresaco,'si127_codctb'))."','$this->si127_codctb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_dtemissao"]) || $this->si127_dtemissao != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010884,'".AddSlashes(pg_result($resaco,$conresaco,'si127_dtemissao'))."','$this->si127_dtemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_vldocumento"]) || $this->si127_vldocumento != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010885,'".AddSlashes(pg_result($resaco,$conresaco,'si127_vldocumento'))."','$this->si127_vldocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_mes"]) || $this->si127_mes != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010886,'".AddSlashes(pg_result($resaco,$conresaco,'si127_mes'))."','$this->si127_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["si127_reg10"]) || $this->si127_reg10 != "")
           $resac = db_query("insert into db_acount values($acount,2010356,2010887,'".AddSlashes(pg_result($resaco,$conresaco,'si127_reg10'))."','$this->si127_reg10',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ext132014 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si127_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ext132014 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si127_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si127_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($si127_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($si127_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2010878,'$si127_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2010356,2010878,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010879,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010880,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_codreduzidoop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010881,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_tipodocumentoop'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010882,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010883,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_codctb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010884,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_dtemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010885,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_vldocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010886,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2010356,2010887,'','".AddSlashes(pg_result($resaco,$iresaco,'si127_reg10'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ext132014
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si127_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si127_sequencial = $si127_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ext132014 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si127_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ext132014 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si127_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$si127_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ext132014";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $si127_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ext132014 ";
     $sql .= "      inner join ext102014  on  ext102014.si124_sequencial = ext132014.si127_reg10";
     $sql2 = "";
     if($dbwhere==""){
       if($si127_sequencial!=null ){
         $sql2 .= " where ext132014.si127_sequencial = $si127_sequencial "; 
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
   function sql_query_file ( $si127_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ext132014 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si127_sequencial!=null ){
         $sql2 .= " where ext132014.si127_sequencial = $si127_sequencial "; 
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
