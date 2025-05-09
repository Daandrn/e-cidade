<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: divida
//CLASSE DA ENTIDADE dividaprotprocesso
class cl_dividaprotprocesso { 
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
   var $v88_sequencial = 0; 
   var $v88_divida = 0; 
   var $v88_protprocesso = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v88_sequencial = int4 = Sequencial 
                 v88_divida = int4 = C�digo da Divida 
                 v88_protprocesso = int4 = N�mero do processo 
                 ";
   //funcao construtor da classe 
   function cl_dividaprotprocesso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dividaprotprocesso"); 
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
       $this->v88_sequencial = ($this->v88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v88_sequencial"]:$this->v88_sequencial);
       $this->v88_divida = ($this->v88_divida == ""?@$GLOBALS["HTTP_POST_VARS"]["v88_divida"]:$this->v88_divida);
       $this->v88_protprocesso = ($this->v88_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["v88_protprocesso"]:$this->v88_protprocesso);
     }else{
       $this->v88_sequencial = ($this->v88_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v88_sequencial"]:$this->v88_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v88_sequencial){ 
      $this->atualizacampos();
     if($this->v88_divida == null ){ 
       $this->erro_sql = " Campo C�digo da Divida nao Informado.";
       $this->erro_campo = "v88_divida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v88_protprocesso == null ){ 
       $this->erro_sql = " Campo N�mero do processo nao Informado.";
       $this->erro_campo = "v88_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v88_sequencial == "" || $v88_sequencial == null ){
       $result = db_query("select nextval('dividaprotprocesso_v88_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dividaprotprocesso_v88_sequencial_seq do campo: v88_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v88_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from dividaprotprocesso_v88_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v88_sequencial)){
         $this->erro_sql = " Campo v88_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v88_sequencial = $v88_sequencial; 
       }
     }
     if(($this->v88_sequencial == null) || ($this->v88_sequencial == "") ){ 
       $this->erro_sql = " Campo v88_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dividaprotprocesso(
                                       v88_sequencial 
                                      ,v88_divida 
                                      ,v88_protprocesso 
                       )
                values (
                                $this->v88_sequencial 
                               ,$this->v88_divida 
                               ,$this->v88_protprocesso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "divida processo ($this->v88_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "divida processo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "divida processo ($this->v88_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v88_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

    
     $resaco = $this->sql_record($this->sql_query_file($this->v88_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18804,'$this->v88_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3334,18804,'','".AddSlashes(pg_result($resaco,0,'v88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3334,18805,'','".AddSlashes(pg_result($resaco,0,'v88_divida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3334,18806,'','".AddSlashes(pg_result($resaco,0,'v88_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     
     return true;
   } 
   // funcao para alteracao
   function alterar ($v88_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update dividaprotprocesso set ";
     $virgula = "";
     if(trim($this->v88_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v88_sequencial"])){ 
       $sql  .= $virgula." v88_sequencial = $this->v88_sequencial ";
       $virgula = ",";
       if(trim($this->v88_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v88_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v88_divida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v88_divida"])){ 
       $sql  .= $virgula." v88_divida = $this->v88_divida ";
       $virgula = ",";
       if(trim($this->v88_divida) == null ){ 
         $this->erro_sql = " Campo C�digo da Divida nao Informado.";
         $this->erro_campo = "v88_divida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v88_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v88_protprocesso"])){ 
       $sql  .= $virgula." v88_protprocesso = $this->v88_protprocesso ";
       $virgula = ",";
       if(trim($this->v88_protprocesso) == null ){ 
         $this->erro_sql = " Campo N�mero do processo nao Informado.";
         $this->erro_campo = "v88_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v88_sequencial!=null){
       $sql .= " v88_sequencial = $this->v88_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v88_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18804,'$this->v88_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v88_sequencial"]) || $this->v88_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3334,18804,'".AddSlashes(pg_result($resaco,$conresaco,'v88_sequencial'))."','$this->v88_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v88_divida"]) || $this->v88_divida != "")
           $resac = db_query("insert into db_acount values($acount,3334,18805,'".AddSlashes(pg_result($resaco,$conresaco,'v88_divida'))."','$this->v88_divida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v88_protprocesso"]) || $this->v88_protprocesso != "")
           $resac = db_query("insert into db_acount values($acount,3334,18806,'".AddSlashes(pg_result($resaco,$conresaco,'v88_protprocesso'))."','$this->v88_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divida processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v88_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divida processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v88_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v88_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v88_sequencial=null,$dbwhere=null) { 
     
     
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v88_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18804,'$v88_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3334,18804,'','".AddSlashes(pg_result($resaco,$iresaco,'v88_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3334,18805,'','".AddSlashes(pg_result($resaco,$iresaco,'v88_divida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3334,18806,'','".AddSlashes(pg_result($resaco,$iresaco,'v88_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     
     $sql = " delete from dividaprotprocesso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v88_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v88_sequencial = $v88_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divida processo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v88_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divida processo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v88_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v88_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dividaprotprocesso";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dividaprotprocesso ";
     $sql .= "      inner join divida  on  divida.v01_coddiv = dividaprotprocesso.v88_divida";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = dividaprotprocesso.v88_protprocesso";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = divida.v01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = divida.v01_instit";
     $sql .= "      inner join proced  on  proced.v03_codigo = divida.v01_proced";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  as b on   b.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($v88_sequencial!=null ){
         $sql2 .= " where dividaprotprocesso.v88_sequencial = $v88_sequencial "; 
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
   function sql_query_file ( $v88_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dividaprotprocesso ";
     $sql2 = "";
     if($dbwhere==""){
       if($v88_sequencial!=null ){
         $sql2 .= " where dividaprotprocesso.v88_sequencial = $v88_sequencial "; 
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