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

//MODULO: Arrecada��o
//CLASSE DA ENTIDADE cadtipoconvenio
class cl_cadtipoconvenio { 
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
   var $ar12_sequencial = 0; 
   var $ar12_cadconveniomodalidade = 0; 
   var $ar12_nome = null; 
   var $ar12_sigla = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar12_sequencial = int4 = Sequ�ncial 
                 ar12_cadconveniomodalidade = int4 = Modalidade do conv�nio 
                 ar12_nome = varchar(50) = Nome 
                 ar12_sigla = varchar(3) = Sigla 
                 ";
   //funcao construtor da classe 
   function cl_cadtipoconvenio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadtipoconvenio"); 
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
       $this->ar12_sequencial = ($this->ar12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar12_sequencial"]:$this->ar12_sequencial);
       $this->ar12_cadconveniomodalidade = ($this->ar12_cadconveniomodalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ar12_cadconveniomodalidade"]:$this->ar12_cadconveniomodalidade);
       $this->ar12_nome = ($this->ar12_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ar12_nome"]:$this->ar12_nome);
       $this->ar12_sigla = ($this->ar12_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["ar12_sigla"]:$this->ar12_sigla);
     }else{
       $this->ar12_sequencial = ($this->ar12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar12_sequencial"]:$this->ar12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar12_sequencial){ 
      $this->atualizacampos();
     if($this->ar12_cadconveniomodalidade == null ){ 
       $this->erro_sql = " Campo Modalidade do conv�nio nao Informado.";
       $this->erro_campo = "ar12_cadconveniomodalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar12_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ar12_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar12_sigla == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "ar12_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar12_sequencial == "" || $ar12_sequencial == null ){
       $result = db_query("select nextval('cadtipoconvenio_ar12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadtipoconvenio_ar12_sequencial_seq do campo: ar12_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadtipoconvenio_ar12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar12_sequencial)){
         $this->erro_sql = " Campo ar12_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar12_sequencial = $ar12_sequencial; 
       }
     }
     if(($this->ar12_sequencial == null) || ($this->ar12_sequencial == "") ){ 
       $this->erro_sql = " Campo ar12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadtipoconvenio(
                                       ar12_sequencial 
                                      ,ar12_cadconveniomodalidade 
                                      ,ar12_nome 
                                      ,ar12_sigla 
                       )
                values (
                                $this->ar12_sequencial 
                               ,$this->ar12_cadconveniomodalidade 
                               ,'$this->ar12_nome' 
                               ,'$this->ar12_sigla' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de conv�nio ($this->ar12_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de conv�nio j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de conv�nio ($this->ar12_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar12_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar12_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12519,'$this->ar12_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2184,12519,'','".AddSlashes(pg_result($resaco,0,'ar12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2184,12520,'','".AddSlashes(pg_result($resaco,0,'ar12_cadconveniomodalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2184,12521,'','".AddSlashes(pg_result($resaco,0,'ar12_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2184,12522,'','".AddSlashes(pg_result($resaco,0,'ar12_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadtipoconvenio set ";
     $virgula = "";
     if(trim($this->ar12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar12_sequencial"])){ 
       $sql  .= $virgula." ar12_sequencial = $this->ar12_sequencial ";
       $virgula = ",";
       if(trim($this->ar12_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequ�ncial nao Informado.";
         $this->erro_campo = "ar12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar12_cadconveniomodalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar12_cadconveniomodalidade"])){ 
       $sql  .= $virgula." ar12_cadconveniomodalidade = $this->ar12_cadconveniomodalidade ";
       $virgula = ",";
       if(trim($this->ar12_cadconveniomodalidade) == null ){ 
         $this->erro_sql = " Campo Modalidade do conv�nio nao Informado.";
         $this->erro_campo = "ar12_cadconveniomodalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar12_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar12_nome"])){ 
       $sql  .= $virgula." ar12_nome = '$this->ar12_nome' ";
       $virgula = ",";
       if(trim($this->ar12_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ar12_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar12_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar12_sigla"])){ 
       $sql  .= $virgula." ar12_sigla = '$this->ar12_sigla' ";
       $virgula = ",";
       if(trim($this->ar12_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "ar12_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar12_sequencial!=null){
       $sql .= " ar12_sequencial = $this->ar12_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar12_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12519,'$this->ar12_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar12_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2184,12519,'".AddSlashes(pg_result($resaco,$conresaco,'ar12_sequencial'))."','$this->ar12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar12_cadconveniomodalidade"]))
           $resac = db_query("insert into db_acount values($acount,2184,12520,'".AddSlashes(pg_result($resaco,$conresaco,'ar12_cadconveniomodalidade'))."','$this->ar12_cadconveniomodalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar12_nome"]))
           $resac = db_query("insert into db_acount values($acount,2184,12521,'".AddSlashes(pg_result($resaco,$conresaco,'ar12_nome'))."','$this->ar12_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar12_sigla"]))
           $resac = db_query("insert into db_acount values($acount,2184,12522,'".AddSlashes(pg_result($resaco,$conresaco,'ar12_sigla'))."','$this->ar12_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de conv�nio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar12_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de conv�nio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar12_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar12_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar12_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar12_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12519,'$ar12_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2184,12519,'','".AddSlashes(pg_result($resaco,$iresaco,'ar12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2184,12520,'','".AddSlashes(pg_result($resaco,$iresaco,'ar12_cadconveniomodalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2184,12521,'','".AddSlashes(pg_result($resaco,$iresaco,'ar12_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2184,12522,'','".AddSlashes(pg_result($resaco,$iresaco,'ar12_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadtipoconvenio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar12_sequencial = $ar12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de conv�nio nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar12_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de conv�nio nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar12_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadtipoconvenio";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoconvenio ";
     $sql .= "      inner join cadconveniomodalidade  on  cadconveniomodalidade.ar15_sequencial = cadtipoconvenio.ar12_cadconveniomodalidade";
     $sql2 = "";
     if($dbwhere==""){
       if($ar12_sequencial!=null ){
         $sql2 .= " where cadtipoconvenio.ar12_sequencial = $ar12_sequencial "; 
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
   function sql_query_file ( $ar12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadtipoconvenio ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar12_sequencial!=null ){
         $sql2 .= " where cadtipoconvenio.ar12_sequencial = $ar12_sequencial "; 
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