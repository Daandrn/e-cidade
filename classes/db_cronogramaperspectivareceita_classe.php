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

//MODULO: orcamento
//CLASSE DA ENTIDADE cronogramaperspectivareceita
class cl_cronogramaperspectivareceita { 
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
   var $o126_sequencial = 0; 
   var $o126_codrec = 0; 
   var $o126_anousu = 0; 
   var $o126_cronogramaperspectiva = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o126_sequencial = int4 = C�digo Sequencial 
                 o126_codrec = int4 = C�digo da Receita 
                 o126_anousu = int4 = Ano da Receita 
                 o126_cronogramaperspectiva = int4 = Perspectiva do Cronograma 
                 ";
   //funcao construtor da classe 
   function cl_cronogramaperspectivareceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramaperspectivareceita"); 
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
       $this->o126_sequencial = ($this->o126_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o126_sequencial"]:$this->o126_sequencial);
       $this->o126_codrec = ($this->o126_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o126_codrec"]:$this->o126_codrec);
       $this->o126_anousu = ($this->o126_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o126_anousu"]:$this->o126_anousu);
       $this->o126_cronogramaperspectiva = ($this->o126_cronogramaperspectiva == ""?@$GLOBALS["HTTP_POST_VARS"]["o126_cronogramaperspectiva"]:$this->o126_cronogramaperspectiva);
     }else{
       $this->o126_sequencial = ($this->o126_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o126_sequencial"]:$this->o126_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o126_sequencial){ 
      $this->atualizacampos();
     if($this->o126_codrec == null ){ 
       $this->erro_sql = " Campo C�digo da Receita nao Informado.";
       $this->erro_campo = "o126_codrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o126_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Receita nao Informado.";
       $this->erro_campo = "o126_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o126_cronogramaperspectiva == null ){ 
       $this->erro_sql = " Campo Perspectiva do Cronograma nao Informado.";
       $this->erro_campo = "o126_cronogramaperspectiva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o126_sequencial == "" || $o126_sequencial == null ){
       $result = db_query("select nextval('cronogramaperspectivareceita_o126_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramaperspectivareceita_o126_sequencial_seq do campo: o126_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o126_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cronogramaperspectivareceita_o126_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o126_sequencial)){
         $this->erro_sql = " Campo o126_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o126_sequencial = $o126_sequencial; 
       }
     }
     if(($this->o126_sequencial == null) || ($this->o126_sequencial == "") ){ 
       $this->erro_sql = " Campo o126_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramaperspectivareceita(
                                       o126_sequencial 
                                      ,o126_codrec 
                                      ,o126_anousu 
                                      ,o126_cronogramaperspectiva 
                       )
                values (
                                $this->o126_sequencial 
                               ,$this->o126_codrec 
                               ,$this->o126_anousu 
                               ,$this->o126_cronogramaperspectiva 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Receitas da perspectiva ($this->o126_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Receitas da perspectiva j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Receitas da perspectiva ($this->o126_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o126_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o126_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14884,'$this->o126_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2620,14884,'','".AddSlashes(pg_result($resaco,0,'o126_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2620,14885,'','".AddSlashes(pg_result($resaco,0,'o126_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2620,14886,'','".AddSlashes(pg_result($resaco,0,'o126_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2620,14887,'','".AddSlashes(pg_result($resaco,0,'o126_cronogramaperspectiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o126_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cronogramaperspectivareceita set ";
     $virgula = "";
     if(trim($this->o126_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o126_sequencial"])){ 
       $sql  .= $virgula." o126_sequencial = $this->o126_sequencial ";
       $virgula = ",";
       if(trim($this->o126_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "o126_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o126_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o126_codrec"])){ 
       $sql  .= $virgula." o126_codrec = $this->o126_codrec ";
       $virgula = ",";
       if(trim($this->o126_codrec) == null ){ 
         $this->erro_sql = " Campo C�digo da Receita nao Informado.";
         $this->erro_campo = "o126_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o126_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o126_anousu"])){ 
       $sql  .= $virgula." o126_anousu = $this->o126_anousu ";
       $virgula = ",";
       if(trim($this->o126_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Receita nao Informado.";
         $this->erro_campo = "o126_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o126_cronogramaperspectiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o126_cronogramaperspectiva"])){ 
       $sql  .= $virgula." o126_cronogramaperspectiva = $this->o126_cronogramaperspectiva ";
       $virgula = ",";
       if(trim($this->o126_cronogramaperspectiva) == null ){ 
         $this->erro_sql = " Campo Perspectiva do Cronograma nao Informado.";
         $this->erro_campo = "o126_cronogramaperspectiva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o126_sequencial!=null){
       $sql .= " o126_sequencial = $this->o126_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o126_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14884,'$this->o126_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o126_sequencial"]) || $this->o126_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2620,14884,'".AddSlashes(pg_result($resaco,$conresaco,'o126_sequencial'))."','$this->o126_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o126_codrec"]) || $this->o126_codrec != "")
           $resac = db_query("insert into db_acount values($acount,2620,14885,'".AddSlashes(pg_result($resaco,$conresaco,'o126_codrec'))."','$this->o126_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o126_anousu"]) || $this->o126_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2620,14886,'".AddSlashes(pg_result($resaco,$conresaco,'o126_anousu'))."','$this->o126_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o126_cronogramaperspectiva"]) || $this->o126_cronogramaperspectiva != "")
           $resac = db_query("insert into db_acount values($acount,2620,14887,'".AddSlashes(pg_result($resaco,$conresaco,'o126_cronogramaperspectiva'))."','$this->o126_cronogramaperspectiva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas da perspectiva nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o126_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas da perspectiva nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o126_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o126_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o126_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o126_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14884,'$o126_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2620,14884,'','".AddSlashes(pg_result($resaco,$iresaco,'o126_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2620,14885,'','".AddSlashes(pg_result($resaco,$iresaco,'o126_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2620,14886,'','".AddSlashes(pg_result($resaco,$iresaco,'o126_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2620,14887,'','".AddSlashes(pg_result($resaco,$iresaco,'o126_cronogramaperspectiva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cronogramaperspectivareceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o126_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o126_sequencial = $o126_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Receitas da perspectiva nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o126_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Receitas da perspectiva nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o126_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o126_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramaperspectivareceita";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o126_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramaperspectivareceita ";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = cronogramaperspectivareceita.o126_anousu and  orcreceita.o70_codrec = cronogramaperspectivareceita.o126_codrec";
     $sql .= "      inner join cronogramaperspectiva  on  cronogramaperspectiva.o124_sequencial = cronogramaperspectivareceita.o126_cronogramaperspectiva";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and  orcfontes.o57_anousu = orcreceita.o70_anousu";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cronogramaperspectiva.o124_idusuario";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = cronogramaperspectiva.o124_ppaversao";
     $sql2 = "";
     if($dbwhere==""){
       if($o126_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivareceita.o126_sequencial = $o126_sequencial "; 
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
   function sql_query_file ( $o126_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cronogramaperspectivareceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o126_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivareceita.o126_sequencial = $o126_sequencial "; 
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
  
  function sql_query_receita( $o126_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcreceita ";
     $sql .= "      left join cronogramaperspectivareceita on  orcreceita.o70_anousu = cronogramaperspectivareceita.o126_anousu ";
     $sql .= "                                            and  orcreceita.o70_codrec = cronogramaperspectivareceita.o126_codrec";
     $sql .= "      left join orctiporec      on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes      on  orcfontes.o57_codfon = orcreceita.o70_codfon and  orcfontes.o57_anousu = orcreceita.o70_anousu";
     $sql .= "      inner join conplanoreduz  on  orcfontes.o57_codfon = c61_codcon and  orcfontes.o57_anousu = conplanoreduz.c61_anousu";
     $sql .= "      inner join concarpeculiar on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($o126_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivareceita.o126_sequencial = $o126_sequencial "; 
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
     return analiseQueryPlanoOrcamento($sql);
  }
  
function sql_query_receitaplano( $o126_sequencial=null,$campos="*",$ordem=null,$dbwhere="", $iPerspectiva){ 
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
     $sql .= " from orcfontes ";
     $sql .= "      left join orcreceita     on  orcfontes.o57_codfon  = orcreceita.o70_codfon ";
     $sql .= "                              and  orcfontes.o57_anousu  = orcreceita.o70_anousu";
     $sql .= "      left join orcfontesdes   on  orcfontes.o57_codfon  = orcfontesdes.o60_codfon ";
     $sql .= "                              and  orcfontes.o57_anousu  = orcfontesdes.o60_anousu";
     $sql .= "      left join cronogramaperspectivareceita on  orcreceita.o70_anousu = cronogramaperspectivareceita.o126_anousu ";
     $sql .= "                                            and  orcreceita.o70_codrec = cronogramaperspectivareceita.o126_codrec ";
     $sql .= "                                            and  o126_cronogramaperspectiva = {$iPerspectiva}";
     $sql .= "      left join orctiporec      on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      left join conplanoreduz  on  orcfontes.o57_codfon = c61_codcon and  orcfontes.o57_anousu = conplanoreduz.c61_anousu";
     $sql .= "      left join concarpeculiar on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($o126_sequencial!=null ){
         $sql2 .= " where cronogramaperspectivareceita.o126_sequencial = $o126_sequencial "; 
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
     return analiseQueryPlanoOrcamento($sql);
  }

  /**
   * Retorna todas as Metas de uma receita
   *
   * @param $sCampos
   * @param $sWhere
   * @param $sOrder
   * @param $sGroup
   * @return string
   */
  public function sql_query_metas_receita($sCampos = '*', $sWhere = null, $sOrder = null, $sGroup = null) {

    $sSqlMetas = "select {$sCampos} ";
    $sSqlMetas .= " from cronogramaperspectivareceita";
    $sSqlMetas .= "      left join cronogramametareceita on o127_cronogramaperspectivareceita = o126_sequencial";
    if (!empty($sWhere)) {
      $sSqlMetas .= " where {$sWhere}";
    }

    if (!empty($sOrder)) {
      $sSqlMetas .= " order by {$sOrder}";
    }

    if (!empty($sGroup)) {
      $sSqlMetas .= " group by {$sGroup}";
    }
   return $sSqlMetas;
  }
}