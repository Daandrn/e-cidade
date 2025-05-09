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

//MODULO: patrimonio
//CLASSE DA ENTIDADE bensempnotaitem
class cl_bensempnotaitem { 
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
   var $e136_sequencial = 0; 
   var $e136_bens = 0; 
   var $e136_empnotaitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e136_sequencial = int4 = Sequencial 
                 e136_bens = int4 = Bem 
                 e136_empnotaitem = int4 = Empnotaitem 
                 ";
   //funcao construtor da classe 
   function cl_bensempnotaitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensempnotaitem"); 
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
       $this->e136_sequencial = ($this->e136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e136_sequencial"]:$this->e136_sequencial);
       $this->e136_bens = ($this->e136_bens == ""?@$GLOBALS["HTTP_POST_VARS"]["e136_bens"]:$this->e136_bens);
       $this->e136_empnotaitem = ($this->e136_empnotaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e136_empnotaitem"]:$this->e136_empnotaitem);
     }else{
       $this->e136_sequencial = ($this->e136_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e136_sequencial"]:$this->e136_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e136_sequencial){ 
      $this->atualizacampos();
     if($this->e136_bens == null ){ 
       $this->erro_sql = " Campo Bem nao Informado.";
       $this->erro_campo = "e136_bens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e136_empnotaitem == null ){ 
       $this->erro_sql = " Campo Empnotaitem nao Informado.";
       $this->erro_campo = "e136_empnotaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e136_sequencial == "" || $e136_sequencial == null ){
       $result = db_query("select nextval('bensempnotaitem_e136_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensempnotaitem_e136_sequencial_seq do campo: e136_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e136_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensempnotaitem_e136_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e136_sequencial)){
         $this->erro_sql = " Campo e136_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e136_sequencial = $e136_sequencial; 
       }
     }
     if(($this->e136_sequencial == null) || ($this->e136_sequencial == "") ){ 
       $this->erro_sql = " Campo e136_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensempnotaitem(
                                       e136_sequencial 
                                      ,e136_bens 
                                      ,e136_empnotaitem 
                       )
                values (
                                $this->e136_sequencial 
                               ,$this->e136_bens 
                               ,$this->e136_empnotaitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "bensempnotaitem ($this->e136_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "bensempnotaitem j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "bensempnotaitem ($this->e136_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e136_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e136_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18887,'$this->e136_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3349,18887,'','".AddSlashes(pg_result($resaco,0,'e136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3349,18888,'','".AddSlashes(pg_result($resaco,0,'e136_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3349,18889,'','".AddSlashes(pg_result($resaco,0,'e136_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e136_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bensempnotaitem set ";
     $virgula = "";
     if(trim($this->e136_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e136_sequencial"])){ 
       $sql  .= $virgula." e136_sequencial = $this->e136_sequencial ";
       $virgula = ",";
       if(trim($this->e136_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "e136_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e136_bens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e136_bens"])){ 
       $sql  .= $virgula." e136_bens = $this->e136_bens ";
       $virgula = ",";
       if(trim($this->e136_bens) == null ){ 
         $this->erro_sql = " Campo Bem nao Informado.";
         $this->erro_campo = "e136_bens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e136_empnotaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e136_empnotaitem"])){ 
       $sql  .= $virgula." e136_empnotaitem = $this->e136_empnotaitem ";
       $virgula = ",";
       if(trim($this->e136_empnotaitem) == null ){ 
         $this->erro_sql = " Campo Empnotaitem nao Informado.";
         $this->erro_campo = "e136_empnotaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e136_sequencial!=null){
       $sql .= " e136_sequencial = $this->e136_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e136_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18887,'$this->e136_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e136_sequencial"]) || $this->e136_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3349,18887,'".AddSlashes(pg_result($resaco,$conresaco,'e136_sequencial'))."','$this->e136_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e136_bens"]) || $this->e136_bens != "")
           $resac = db_query("insert into db_acount values($acount,3349,18888,'".AddSlashes(pg_result($resaco,$conresaco,'e136_bens'))."','$this->e136_bens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e136_empnotaitem"]) || $this->e136_empnotaitem != "")
           $resac = db_query("insert into db_acount values($acount,3349,18889,'".AddSlashes(pg_result($resaco,$conresaco,'e136_empnotaitem'))."','$this->e136_empnotaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bensempnotaitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e136_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bensempnotaitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e136_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e136_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e136_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e136_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18887,'$e136_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3349,18887,'','".AddSlashes(pg_result($resaco,$iresaco,'e136_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3349,18888,'','".AddSlashes(pg_result($resaco,$iresaco,'e136_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3349,18889,'','".AddSlashes(pg_result($resaco,$iresaco,'e136_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bensempnotaitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e136_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e136_sequencial = $e136_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "bensempnotaitem nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e136_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "bensempnotaitem nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e136_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e136_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensempnotaitem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensempnotaitem ";
     $sql .= "      inner join bens  on  bens.t52_bem = bensempnotaitem.e136_bens";
     $sql .= "      inner join empnotaitem  on  empnotaitem.e72_sequencial = bensempnotaitem.e136_empnotaitem";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
     $sql .= "      inner join empnota  as a on   a.e69_codnota = empnotaitem.e72_codnota";
     $sql2 = "";
     if($dbwhere==""){
       if($e136_sequencial!=null ){
         $sql2 .= " where bensempnotaitem.e136_sequencial = $e136_sequencial "; 
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
   function sql_query_file ( $e136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensempnotaitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e136_sequencial!=null ){
         $sql2 .= " where bensempnotaitem.e136_sequencial = $e136_sequencial "; 
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
   function sql_query_bens_ativos ( $e136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from bensempnotaitem ";
    $sql .= "      inner join empnotaitem on empnotaitem.e72_sequencial = bensempnotaitem.e136_empnotaitem  ";
    $sql .= "      inner join empnota     on empnota.e69_codnota        = empnotaitem.e72_codnota           ";
    $sql .= "      inner join empempenho  on empempenho.e60_numemp      = empnota.e69_numemp                ";
    $sql .= "      inner join bens        on bens.t52_bem               = bensempnotaitem.e136_bens         ";
    $sql .= "      inner join bensplaca   on bensplaca.t41_bem          = bens.t52_bem                      ";
    $sql .= "      left  join bensbaix    on bensbaix.t55_codbem        = bens.t52_bem                      ";
    $sql2 = "";
    if($dbwhere==""){
      if($e136_sequencial!=null ){
        $sql2 .= " where bensempnotaitem.e136_sequencial = $e136_sequencial ";
      }
      } else if($dbwhere != ""){
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
   function sql_query_bens_nota ( $e136_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from bensempnotaitem ";
  	$sql .= "      inner join bens          on  bens.t52_bem = bensempnotaitem.e136_bens";
  	$sql .= "      inner join empnotaitem   on  empnotaitem.e72_sequencial = bensempnotaitem.e136_empnotaitem";
  	$sql .= "      inner join cgm           on  cgm.z01_numcgm = bens.t52_numcgm";
  	$sql .= "      inner join db_config     on  db_config.codigo = bens.t52_instit";
  	$sql .= "      inner join db_depart     on  db_depart.coddepto = bens.t52_depart";
  	$sql .= "      inner join clabens       on  clabens.t64_codcla = bens.t52_codcla";
  	$sql .= "      inner join bensmarca     on  bensmarca.t65_sequencial = bens.t52_bensmarca";
  	$sql .= "      inner join bensmodelo    on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
  	$sql .= "      inner join bensmedida    on  bensmedida.t67_sequencial = bens.t52_bensmedida";
  	$sql .= "      inner join empempitem 		on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
  	$sql .= "      inner join empnota  as a on  a.e69_codnota = empnotaitem.e72_codnota";
  	$sql .= "      inner join empnotaele    on  empnotaele.e70_codnota = a.e69_codnota";
  	$sql .= "      inner join orcelemento   on  orcelemento.o56_codele = empnotaele.e70_codele";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($e136_sequencial!=null ){
  			$sql2 .= " where bensempnotaitem.e136_sequencial = $e136_sequencial ";
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