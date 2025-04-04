<?
//MODULO: Configuracoes
//CLASSE DA ENTIDADE documentoatributo
class cl_documentoatributo { 
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
   var $db45_sequencial = 0; 
   var $db45_documento = 0; 
   var $db45_codcam = 0; 
   var $db45_descricao = null; 
   var $db45_valordefault = null; 
   var $db45_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db45_sequencial = int4 = C�digo Atributo 
                 db45_documento = int4 = Documento 
                 db45_codcam = int4 = Campo Refer�ncia 
                 db45_descricao = varchar(100) = Descri��o 
                 db45_valordefault = varchar(100) = Valor Default 
                 db45_tipo = int4 = Tipo de Atributo 
                 ";
   //funcao construtor da classe 
   function cl_documentoatributo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("documentoatributo"); 
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
       $this->db45_sequencial = ($this->db45_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_sequencial"]:$this->db45_sequencial);
       $this->db45_documento = ($this->db45_documento == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_documento"]:$this->db45_documento);
       $this->db45_codcam = ($this->db45_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_codcam"]:$this->db45_codcam);
       $this->db45_descricao = ($this->db45_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_descricao"]:$this->db45_descricao);
       $this->db45_valordefault = ($this->db45_valordefault == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_valordefault"]:$this->db45_valordefault);
       $this->db45_tipo = ($this->db45_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_tipo"]:$this->db45_tipo);
     }else{
       $this->db45_sequencial = ($this->db45_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_sequencial"]:$this->db45_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db45_sequencial){ 
      $this->atualizacampos();
     if($this->db45_documento == null ){ 
       $this->erro_sql = " Campo Documento nao Informado.";
       $this->erro_campo = "db45_documento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db45_codcam == null ){ 
       $this->db45_codcam = "0";
     }
     if($this->db45_descricao == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "db45_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db45_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Atributo nao Informado.";
       $this->erro_campo = "db45_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db45_sequencial == "" || $db45_sequencial == null ){
       $result = db_query("select nextval('documentoatributo_db45_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: documentoatributo_db45_sequencial_seq do campo: db45_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db45_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from documentoatributo_db45_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db45_sequencial)){
         $this->erro_sql = " Campo db45_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db45_sequencial = $db45_sequencial; 
       }
     }
     if(($this->db45_sequencial == null) || ($this->db45_sequencial == "") ){ 
       $this->erro_sql = " Campo db45_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into documentoatributo(
                                       db45_sequencial 
                                      ,db45_documento 
                                      ,db45_codcam 
                                      ,db45_descricao 
                                      ,db45_valordefault 
                                      ,db45_tipo 
                       )
                values (
                                $this->db45_sequencial 
                               ,$this->db45_documento 
                               ,$this->db45_codcam 
                               ,'$this->db45_descricao' 
                               ,'$this->db45_valordefault' 
                               ,$this->db45_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atributos do Documento ($this->db45_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atributos do Documento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atributos do Documento ($this->db45_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db45_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15678,'$this->db45_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2750,15678,'','".AddSlashes(pg_result($resaco,0,'db45_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15679,'','".AddSlashes(pg_result($resaco,0,'db45_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15680,'','".AddSlashes(pg_result($resaco,0,'db45_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15681,'','".AddSlashes(pg_result($resaco,0,'db45_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15682,'','".AddSlashes(pg_result($resaco,0,'db45_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15683,'','".AddSlashes(pg_result($resaco,0,'db45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db45_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update documentoatributo set ";
     $virgula = "";
     if(trim($this->db45_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_sequencial"])){ 
       $sql  .= $virgula." db45_sequencial = $this->db45_sequencial ";
       $virgula = ",";
       if(trim($this->db45_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Atributo nao Informado.";
         $this->erro_campo = "db45_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_documento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_documento"])){ 
       $sql  .= $virgula." db45_documento = $this->db45_documento ";
       $virgula = ",";
       if(trim($this->db45_documento) == null ){ 
         $this->erro_sql = " Campo Documento nao Informado.";
         $this->erro_campo = "db45_documento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_codcam"])){ 
        if(trim($this->db45_codcam)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db45_codcam"])){ 
           $this->db45_codcam = "0" ; 
        } 
       $sql  .= $virgula." db45_codcam = $this->db45_codcam ";
       $virgula = ",";
     }
     if(trim($this->db45_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_descricao"])){ 
       $sql  .= $virgula." db45_descricao = '$this->db45_descricao' ";
       $virgula = ",";
       if(trim($this->db45_descricao) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "db45_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_valordefault)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_valordefault"])){ 
       $sql  .= $virgula." db45_valordefault = '$this->db45_valordefault' ";
       $virgula = ",";
     }
     if(trim($this->db45_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_tipo"])){ 
       $sql  .= $virgula." db45_tipo = $this->db45_tipo ";
       $virgula = ",";
       if(trim($this->db45_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Atributo nao Informado.";
         $this->erro_campo = "db45_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db45_sequencial!=null){
       $sql .= " db45_sequencial = $this->db45_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db45_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15678,'$this->db45_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_sequencial"]) || $this->db45_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2750,15678,'".AddSlashes(pg_result($resaco,$conresaco,'db45_sequencial'))."','$this->db45_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_documento"]) || $this->db45_documento != "")
           $resac = db_query("insert into db_acount values($acount,2750,15679,'".AddSlashes(pg_result($resaco,$conresaco,'db45_documento'))."','$this->db45_documento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_codcam"]) || $this->db45_codcam != "")
           $resac = db_query("insert into db_acount values($acount,2750,15680,'".AddSlashes(pg_result($resaco,$conresaco,'db45_codcam'))."','$this->db45_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_descricao"]) || $this->db45_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2750,15681,'".AddSlashes(pg_result($resaco,$conresaco,'db45_descricao'))."','$this->db45_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_valordefault"]) || $this->db45_valordefault != "")
           $resac = db_query("insert into db_acount values($acount,2750,15682,'".AddSlashes(pg_result($resaco,$conresaco,'db45_valordefault'))."','$this->db45_valordefault',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_tipo"]) || $this->db45_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2750,15683,'".AddSlashes(pg_result($resaco,$conresaco,'db45_tipo'))."','$this->db45_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atributos do Documento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atributos do Documento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db45_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db45_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15678,'$db45_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2750,15678,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15679,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_documento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15680,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15681,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15682,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15683,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from documentoatributo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db45_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db45_sequencial = $db45_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atributos do Documento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db45_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atributos do Documento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db45_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db45_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:documentoatributo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db45_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from documentoatributo ";
     $sql .= "      left  join db_syscampo  on  db_syscampo.codcam = documentoatributo.db45_codcam";
     $sql .= "      inner join documento  on  documento.db44_sequencial = documentoatributo.db45_documento";
     $sql2 = "";
     if($dbwhere==""){
       if($db45_sequencial!=null ){
         $sql2 .= " where documentoatributo.db45_sequencial = $db45_sequencial "; 
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
   function sql_query_file ( $db45_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from documentoatributo ";
     $sql2 = "";
     if($dbwhere==""){
       if($db45_sequencial!=null ){
         $sql2 .= " where documentoatributo.db45_sequencial = $db45_sequencial "; 
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
