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

//MODULO: Laborat�rio
//CLASSE DA ENTIDADE lab_valorrefselgrupo
class cl_lab_valorrefselgrupo { 
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
   var $la51_i_codigo = 0; 
   var $la51_i_valorrefsel = 0; 
   var $la51_i_referencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la51_i_codigo = int4 = C�digo 
                 la51_i_valorrefsel = int4 = Valor Referencial Selecion�vel 
                 la51_i_referencia = int4 = Refer�ncia 
                 ";
   //funcao construtor da classe 
   function cl_lab_valorrefselgrupo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_valorrefselgrupo"); 
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
       $this->la51_i_codigo = ($this->la51_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la51_i_codigo"]:$this->la51_i_codigo);
       $this->la51_i_valorrefsel = ($this->la51_i_valorrefsel == ""?@$GLOBALS["HTTP_POST_VARS"]["la51_i_valorrefsel"]:$this->la51_i_valorrefsel);
       $this->la51_i_referencia = ($this->la51_i_referencia == ""?@$GLOBALS["HTTP_POST_VARS"]["la51_i_referencia"]:$this->la51_i_referencia);
     }else{
       $this->la51_i_codigo = ($this->la51_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la51_i_codigo"]:$this->la51_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la51_i_codigo){ 
      $this->atualizacampos();
     if($this->la51_i_valorrefsel == null ){ 
       $this->erro_sql = " Campo Valor Referencial Selecion�vel nao Informado.";
       $this->erro_campo = "la51_i_valorrefsel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la51_i_referencia == null ){ 
       $this->erro_sql = " Campo Refer�ncia nao Informado.";
       $this->erro_campo = "la51_i_referencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la51_i_codigo == "" || $la51_i_codigo == null ){
       $result = db_query("select nextval('lab_valorrefselgrupo_la51_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_valorrefselgrupo_la51_i_codigo_seq do campo: la51_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la51_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_valorrefselgrupo_la51_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la51_i_codigo)){
         $this->erro_sql = " Campo la51_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la51_i_codigo = $la51_i_codigo; 
       }
     }
     if(($this->la51_i_codigo == null) || ($this->la51_i_codigo == "") ){ 
       $this->erro_sql = " Campo la51_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_valorrefselgrupo(
                                       la51_i_codigo 
                                      ,la51_i_valorrefsel 
                                      ,la51_i_referencia 
                       )
                values (
                                $this->la51_i_codigo 
                               ,$this->la51_i_valorrefsel 
                               ,$this->la51_i_referencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_valorrefselgrupo ($this->la51_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_valorrefselgrupo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_valorrefselgrupo ($this->la51_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la51_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la51_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16604,'$this->la51_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2916,16604,'','".AddSlashes(pg_result($resaco,0,'la51_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2916,16605,'','".AddSlashes(pg_result($resaco,0,'la51_i_valorrefsel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2916,16606,'','".AddSlashes(pg_result($resaco,0,'la51_i_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la51_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_valorrefselgrupo set ";
     $virgula = "";
     if(trim($this->la51_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la51_i_codigo"])){ 
       $sql  .= $virgula." la51_i_codigo = $this->la51_i_codigo ";
       $virgula = ",";
       if(trim($this->la51_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "la51_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la51_i_valorrefsel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la51_i_valorrefsel"])){ 
       $sql  .= $virgula." la51_i_valorrefsel = $this->la51_i_valorrefsel ";
       $virgula = ",";
       if(trim($this->la51_i_valorrefsel) == null ){ 
         $this->erro_sql = " Campo Valor Referencial Selecion�vel nao Informado.";
         $this->erro_campo = "la51_i_valorrefsel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la51_i_referencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la51_i_referencia"])){ 
       $sql  .= $virgula." la51_i_referencia = $this->la51_i_referencia ";
       $virgula = ",";
       if(trim($this->la51_i_referencia) == null ){ 
         $this->erro_sql = " Campo Refer�ncia nao Informado.";
         $this->erro_campo = "la51_i_referencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la51_i_codigo!=null){
       $sql .= " la51_i_codigo = $this->la51_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la51_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16604,'$this->la51_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la51_i_codigo"]) || $this->la51_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2916,16604,'".AddSlashes(pg_result($resaco,$conresaco,'la51_i_codigo'))."','$this->la51_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la51_i_valorrefsel"]) || $this->la51_i_valorrefsel != "")
           $resac = db_query("insert into db_acount values($acount,2916,16605,'".AddSlashes(pg_result($resaco,$conresaco,'la51_i_valorrefsel'))."','$this->la51_i_valorrefsel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la51_i_referencia"]) || $this->la51_i_referencia != "")
           $resac = db_query("insert into db_acount values($acount,2916,16606,'".AddSlashes(pg_result($resaco,$conresaco,'la51_i_referencia'))."','$this->la51_i_referencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_valorrefselgrupo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la51_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_valorrefselgrupo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la51_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la51_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la51_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la51_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16604,'$la51_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2916,16604,'','".AddSlashes(pg_result($resaco,$iresaco,'la51_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2916,16605,'','".AddSlashes(pg_result($resaco,$iresaco,'la51_i_valorrefsel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2916,16606,'','".AddSlashes(pg_result($resaco,$iresaco,'la51_i_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_valorrefselgrupo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la51_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la51_i_codigo = $la51_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_valorrefselgrupo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la51_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_valorrefselgrupo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la51_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la51_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_valorrefselgrupo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la51_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_valorrefselgrupo ";
     $sql .= "      inner join lab_tiporeferenciaalfa  on  lab_tiporeferenciaalfa.la29_i_codigo = lab_valorrefselgrupo.la51_i_referencia";
     $sql .= "      inner join lab_valorreferenciasel  on  lab_valorreferenciasel.la28_i_codigo = lab_valorrefselgrupo.la51_i_valorrefsel";
     $sql .= "      inner join lab_valorreferencia  as a on   a.la27_i_codigo = lab_tiporeferenciaalfa.la29_i_valorref";
     $sql2 = "";
     if($dbwhere==""){
       if($la51_i_codigo!=null ){
         $sql2 .= " where lab_valorrefselgrupo.la51_i_codigo = $la51_i_codigo "; 
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
   function sql_query_file ( $la51_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_valorrefselgrupo ";
     $sql2 = "";
     if($dbwhere==""){
       if($la51_i_codigo!=null ){
         $sql2 .= " where lab_valorrefselgrupo.la51_i_codigo = $la51_i_codigo "; 
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