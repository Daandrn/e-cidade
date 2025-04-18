<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: fiscal
//CLASSE DA ENTIDADE tiaf
class cl_tiaf { 
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
   var $y90_codtiaf = 0; 
   var $y90_data_dia = null; 
   var $y90_data_mes = null; 
   var $y90_data_ano = null; 
   var $y90_data = null; 
   var $y90_atend = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y90_codtiaf = int4 = C�digo Tiaf 
                 y90_data = date = Data do tiaf 
                 y90_atend = bool = Atendido 
                 ";
   //funcao construtor da classe 
   function cl_tiaf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tiaf"); 
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
       $this->y90_codtiaf = ($this->y90_codtiaf == ""?@$GLOBALS["HTTP_POST_VARS"]["y90_codtiaf"]:$this->y90_codtiaf);
       if($this->y90_data == ""){
         $this->y90_data_dia = ($this->y90_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["y90_data_dia"]:$this->y90_data_dia);
         $this->y90_data_mes = ($this->y90_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["y90_data_mes"]:$this->y90_data_mes);
         $this->y90_data_ano = ($this->y90_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["y90_data_ano"]:$this->y90_data_ano);
         if($this->y90_data_dia != ""){
            $this->y90_data = $this->y90_data_ano."-".$this->y90_data_mes."-".$this->y90_data_dia;
         }
       }
       $this->y90_atend = ($this->y90_atend == "f"?@$GLOBALS["HTTP_POST_VARS"]["y90_atend"]:$this->y90_atend);
     }else{
       $this->y90_codtiaf = ($this->y90_codtiaf == ""?@$GLOBALS["HTTP_POST_VARS"]["y90_codtiaf"]:$this->y90_codtiaf);
     }
   }
   // funcao para inclusao
   function incluir ($y90_codtiaf){ 
      $this->atualizacampos();
     if($this->y90_data == null ){ 
       $this->erro_sql = " Campo Data do tiaf nao Informado.";
       $this->erro_campo = "y90_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y90_atend == null ){ 
       $this->erro_sql = " Campo Atendido nao Informado.";
       $this->erro_campo = "y90_atend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y90_codtiaf == "" || $y90_codtiaf == null ){
       $result = db_query("select nextval('tiaf_y90_codtiaf_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tiaf_y90_codtiaf_seq do campo: y90_codtiaf"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y90_codtiaf = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tiaf_y90_codtiaf_seq");
       if(($result != false) && (pg_result($result,0,0) < $y90_codtiaf)){
         $this->erro_sql = " Campo y90_codtiaf maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y90_codtiaf = $y90_codtiaf; 
       }
     }
     if(($this->y90_codtiaf == null) || ($this->y90_codtiaf == "") ){ 
       $this->erro_sql = " Campo y90_codtiaf nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tiaf(
                                       y90_codtiaf 
                                      ,y90_data 
                                      ,y90_atend 
                       )
                values (
                                $this->y90_codtiaf 
                               ,".($this->y90_data == "null" || $this->y90_data == ""?"null":"'".$this->y90_data."'")." 
                               ,'$this->y90_atend' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tiaf ($this->y90_codtiaf) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tiaf j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tiaf ($this->y90_codtiaf) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y90_codtiaf;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y90_codtiaf));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7346,'$this->y90_codtiaf','I')");
       $resac = db_query("insert into db_acount values($acount,1222,7346,'','".AddSlashes(pg_result($resaco,0,'y90_codtiaf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1222,7347,'','".AddSlashes(pg_result($resaco,0,'y90_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1222,7348,'','".AddSlashes(pg_result($resaco,0,'y90_atend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y90_codtiaf=null) { 
      $this->atualizacampos();
     $sql = " update tiaf set ";
     $virgula = "";
     if(trim($this->y90_codtiaf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y90_codtiaf"])){ 
       $sql  .= $virgula." y90_codtiaf = $this->y90_codtiaf ";
       $virgula = ",";
       if(trim($this->y90_codtiaf) == null ){ 
         $this->erro_sql = " Campo C�digo Tiaf nao Informado.";
         $this->erro_campo = "y90_codtiaf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y90_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y90_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["y90_data_dia"] !="") ){ 
       $sql  .= $virgula." y90_data = '$this->y90_data' ";
       $virgula = ",";
       if(trim($this->y90_data) == null ){ 
         $this->erro_sql = " Campo Data do tiaf nao Informado.";
         $this->erro_campo = "y90_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["y90_data_dia"])){ 
         $sql  .= $virgula." y90_data = null ";
         $virgula = ",";
         if(trim($this->y90_data) == null ){ 
           $this->erro_sql = " Campo Data do tiaf nao Informado.";
           $this->erro_campo = "y90_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->y90_atend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y90_atend"])){ 
       $sql  .= $virgula." y90_atend = '$this->y90_atend' ";
       $virgula = ",";
       if(trim($this->y90_atend) == null ){ 
         $this->erro_sql = " Campo Atendido nao Informado.";
         $this->erro_campo = "y90_atend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y90_codtiaf!=null){
       $sql .= " y90_codtiaf = $this->y90_codtiaf";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y90_codtiaf));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7346,'$this->y90_codtiaf','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y90_codtiaf"]))
           $resac = db_query("insert into db_acount values($acount,1222,7346,'".AddSlashes(pg_result($resaco,$conresaco,'y90_codtiaf'))."','$this->y90_codtiaf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y90_data"]))
           $resac = db_query("insert into db_acount values($acount,1222,7347,'".AddSlashes(pg_result($resaco,$conresaco,'y90_data'))."','$this->y90_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y90_atend"]))
           $resac = db_query("insert into db_acount values($acount,1222,7348,'".AddSlashes(pg_result($resaco,$conresaco,'y90_atend'))."','$this->y90_atend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tiaf nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y90_codtiaf;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tiaf nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y90_codtiaf;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y90_codtiaf;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y90_codtiaf=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y90_codtiaf));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7346,'$y90_codtiaf','E')");
         $resac = db_query("insert into db_acount values($acount,1222,7346,'','".AddSlashes(pg_result($resaco,$iresaco,'y90_codtiaf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1222,7347,'','".AddSlashes(pg_result($resaco,$iresaco,'y90_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1222,7348,'','".AddSlashes(pg_result($resaco,$iresaco,'y90_atend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tiaf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y90_codtiaf != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y90_codtiaf = $y90_codtiaf ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tiaf nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y90_codtiaf;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tiaf nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y90_codtiaf;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y90_codtiaf;
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
        $this->erro_sql   = "Record Vazio na Tabela:tiaf";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y90_codtiaf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiaf ";
     $sql2 = "";
     if($dbwhere==""){
       if($y90_codtiaf!=null ){
         $sql2 .= " where tiaf.y90_codtiaf = $y90_codtiaf "; 
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
   function sql_query_file ( $y90_codtiaf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tiaf ";
     $sql2 = "";
     if($dbwhere==""){
       if($y90_codtiaf!=null ){
         $sql2 .= " where tiaf.y90_codtiaf = $y90_codtiaf "; 
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
   function sql_querytiaf ( $y90_codtiaf=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
 	 $sql .= " from (select *, ";
     $sql .= " case when y95_numcgm is not null ";
     $sql .= " then y95_numcgm else ";
     $sql .= "    (case when y94_inscr is not null then y94_inscr ";
     $sql .= "    end) ";
     $sql .= " end as numero, ";
	 $sql .= " case when y95_numcgm is not null ";
	 $sql .= "    then y95_numcgm else ";
     $sql .= "   (case when y94_inscr is not null ";
     $sql .= "     then q02_numcgm ";
     $sql .= "   end) ";
     $sql .= " end as z01_numcgm, ";
     $sql .= " case when y95_numcgm is not null ";
     $sql .= "     then 'cgm' else ";
     $sql .= "     (case when y94_inscr is not null ";
     $sql .= "          then 'inscricao' ";
     $sql .= " end) ";
     $sql .= " end as tipo ";
	 $sql .= " from tiaf ";
     $sql .= " left join tiafcgm on y95_codtiaf = y90_codtiaf ";
     $sql .= " left join tiafinscr on y94_codtiaf = y90_codtiaf ";
     $sql .= " inner join tiafprazo on y96_codtiaf = y90_codtiaf ";
     $sql .= " left join issbase on q02_inscr = y94_inscr ";
     $sql .= " where ".$dbwhere.") ";
	 $sql .= " as x ";
	 $sql .= " inner join cgm on x.z01_numcgm = cgm.z01_numcgm ";
     $sql2 = "";
     /*if($dbwhere==""){
       if($y90_codtiaf!=null ){
         $sql2 .= " where tiaf.y90_codtiaf = $y90_codtiaf "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }*/
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