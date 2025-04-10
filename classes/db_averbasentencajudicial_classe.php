<?
//MODULO: cadastro
//CLASSE DA ENTIDADE averbasentencajudicial
class cl_averbasentencajudicial { 
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
   var $j101_sequencial = 0; 
   var $j101_averbacao = 0; 
   var $j101_processojudicial = null; 
   var $j101_nomeespolio = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j101_sequencial = int4 = C�digo 
                 j101_averbacao = int4 = Averba��o 
                 j101_processojudicial = varchar(20) = Processo Judicial 
                 j101_nomeespolio = varchar(40) = Espolio 
                 ";
   //funcao construtor da classe 
   function cl_averbasentencajudicial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("averbasentencajudicial"); 
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
       $this->j101_sequencial = ($this->j101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j101_sequencial"]:$this->j101_sequencial);
       $this->j101_averbacao = ($this->j101_averbacao == ""?@$GLOBALS["HTTP_POST_VARS"]["j101_averbacao"]:$this->j101_averbacao);
       $this->j101_processojudicial = ($this->j101_processojudicial == ""?@$GLOBALS["HTTP_POST_VARS"]["j101_processojudicial"]:$this->j101_processojudicial);
       $this->j101_nomeespolio = ($this->j101_nomeespolio == ""?@$GLOBALS["HTTP_POST_VARS"]["j101_nomeespolio"]:$this->j101_nomeespolio);
     }else{
       $this->j101_sequencial = ($this->j101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j101_sequencial"]:$this->j101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j101_sequencial){ 
      $this->atualizacampos();
     if($this->j101_averbacao == null ){ 
       $this->erro_sql = " Campo Averba��o nao Informado.";
       $this->erro_campo = "j101_averbacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j101_processojudicial == null ){ 
       $this->erro_sql = " Campo Processo Judicial nao Informado.";
       $this->erro_campo = "j101_processojudicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j101_nomeespolio == null ){ 
       $this->erro_sql = " Campo Espolio nao Informado.";
       $this->erro_campo = "j101_nomeespolio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j101_sequencial == "" || $j101_sequencial == null ){
       $result = @pg_query("select nextval('averbasentencajudicial_j101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: averbasentencajudicial_j101_sequencial_seq do campo: j101_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j101_sequencial = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from averbasentencajudicial_j101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j101_sequencial)){
         $this->erro_sql = " Campo j101_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j101_sequencial = $j101_sequencial; 
       }
     }
     if(($this->j101_sequencial == null) || ($this->j101_sequencial == "") ){ 
       $this->erro_sql = " Campo j101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into averbasentencajudicial(
                                       j101_sequencial 
                                      ,j101_averbacao 
                                      ,j101_processojudicial 
                                      ,j101_nomeespolio 
                       )
                values (
                                $this->j101_sequencial 
                               ,$this->j101_averbacao 
                               ,'$this->j101_processojudicial' 
                               ,'$this->j101_nomeespolio' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Averba senten�a Judicial ($this->j101_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Averba senten�a Judicial j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Averba senten�a Judicial ($this->j101_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j101_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,11680,'$this->j101_sequencial','I')");
       $resac = pg_query("insert into db_acount values($acount,2011,11680,'','".AddSlashes(pg_result($resaco,0,'j101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2011,11681,'','".AddSlashes(pg_result($resaco,0,'j101_averbacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2011,11682,'','".AddSlashes(pg_result($resaco,0,'j101_processojudicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,2011,11683,'','".AddSlashes(pg_result($resaco,0,'j101_nomeespolio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update averbasentencajudicial set ";
     $virgula = "";
     if(trim($this->j101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j101_sequencial"])){ 
       $sql  .= $virgula." j101_sequencial = $this->j101_sequencial ";
       $virgula = ",";
       if(trim($this->j101_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "j101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j101_averbacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j101_averbacao"])){ 
       $sql  .= $virgula." j101_averbacao = $this->j101_averbacao ";
       $virgula = ",";
       if(trim($this->j101_averbacao) == null ){ 
         $this->erro_sql = " Campo Averba��o nao Informado.";
         $this->erro_campo = "j101_averbacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j101_processojudicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j101_processojudicial"])){ 
       $sql  .= $virgula." j101_processojudicial = '$this->j101_processojudicial' ";
       $virgula = ",";
       if(trim($this->j101_processojudicial) == null ){ 
         $this->erro_sql = " Campo Processo Judicial nao Informado.";
         $this->erro_campo = "j101_processojudicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j101_nomeespolio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j101_nomeespolio"])){ 
       $sql  .= $virgula." j101_nomeespolio = '$this->j101_nomeespolio' ";
       $virgula = ",";
       if(trim($this->j101_nomeespolio) == null ){ 
         $this->erro_sql = " Campo Espolio nao Informado.";
         $this->erro_campo = "j101_nomeespolio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j101_sequencial!=null){
       $sql .= " j101_sequencial = $this->j101_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j101_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,11680,'$this->j101_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j101_sequencial"]))
           $resac = pg_query("insert into db_acount values($acount,2011,11680,'".AddSlashes(pg_result($resaco,$conresaco,'j101_sequencial'))."','$this->j101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j101_averbacao"]))
           $resac = pg_query("insert into db_acount values($acount,2011,11681,'".AddSlashes(pg_result($resaco,$conresaco,'j101_averbacao'))."','$this->j101_averbacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j101_processojudicial"]))
           $resac = pg_query("insert into db_acount values($acount,2011,11682,'".AddSlashes(pg_result($resaco,$conresaco,'j101_processojudicial'))."','$this->j101_processojudicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j101_nomeespolio"]))
           $resac = pg_query("insert into db_acount values($acount,2011,11683,'".AddSlashes(pg_result($resaco,$conresaco,'j101_nomeespolio'))."','$this->j101_nomeespolio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Averba senten�a Judicial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j101_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Averba senten�a Judicial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j101_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j101_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,11680,'$j101_sequencial','E')");
         $resac = pg_query("insert into db_acount values($acount,2011,11680,'','".AddSlashes(pg_result($resaco,$iresaco,'j101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2011,11681,'','".AddSlashes(pg_result($resaco,$iresaco,'j101_averbacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2011,11682,'','".AddSlashes(pg_result($resaco,$iresaco,'j101_processojudicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,2011,11683,'','".AddSlashes(pg_result($resaco,$iresaco,'j101_nomeespolio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from averbasentencajudicial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j101_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j101_sequencial = $j101_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Averba senten�a Judicial nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j101_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Averba senten�a Judicial nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j101_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j101_sequencial;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:averbasentencajudicial";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbasentencajudicial ";
     $sql .= "      inner join averbacao  on  averbacao.j75_codigo = averbasentencajudicial.j101_averbacao";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = averbacao.j75_matric";
     $sql .= "      inner join averbatipo  on  averbatipo.j93_codigo = averbacao.j75_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($j101_sequencial!=null ){
         $sql2 .= " where averbasentencajudicial.j101_sequencial = $j101_sequencial "; 
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
   function sql_query_file ( $j101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbasentencajudicial ";
     $sql2 = "";
     if($dbwhere==""){
       if($j101_sequencial!=null ){
         $sql2 .= " where averbasentencajudicial.j101_sequencial = $j101_sequencial "; 
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
