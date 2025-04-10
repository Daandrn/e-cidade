<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE censocartorio
class cl_censocartorio { 
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
   var $ed291_i_codigo = 0; 
   var $ed291_c_nome = null; 
   var $ed291_i_serventia = 0; 
   var $ed291_i_censomunic = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed291_i_codigo = int4 = C�digo 
                 ed291_c_nome = varchar(100) = Cart�rio 
                 ed291_i_serventia = int4 = Serventia 
                 ed291_i_censomunic = int4 = Munic�pio 
                 ";
   //funcao construtor da classe 
   function cl_censocartorio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censocartorio"); 
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
       $this->ed291_i_codigo = ($this->ed291_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed291_i_codigo"]:$this->ed291_i_codigo);
       $this->ed291_c_nome = ($this->ed291_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed291_c_nome"]:$this->ed291_c_nome);
       $this->ed291_i_serventia = ($this->ed291_i_serventia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed291_i_serventia"]:$this->ed291_i_serventia);
       $this->ed291_i_censomunic = ($this->ed291_i_censomunic == ""?@$GLOBALS["HTTP_POST_VARS"]["ed291_i_censomunic"]:$this->ed291_i_censomunic);
     }else{
       $this->ed291_i_codigo = ($this->ed291_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed291_i_codigo"]:$this->ed291_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed291_i_codigo){ 
      $this->atualizacampos();
     if($this->ed291_c_nome == null ){ 
       $this->erro_sql = " Campo Cart�rio nao Informado.";
       $this->erro_campo = "ed291_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed291_i_serventia == null ){ 
       $this->erro_sql = " Campo Serventia nao Informado.";
       $this->erro_campo = "ed291_i_serventia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed291_i_censomunic == null ){ 
       $this->erro_sql = " Campo Munic�pio nao Informado.";
       $this->erro_campo = "ed291_i_censomunic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed291_i_codigo == "" || $ed291_i_codigo == null ){
       $result = db_query("select nextval('censocartorio_ed291_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: censocartorio_ed291_i_codigo_seq do campo: ed291_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed291_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from censocartorio_ed291_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed291_i_codigo)){
         $this->erro_sql = " Campo ed291_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed291_i_codigo = $ed291_i_codigo; 
       }
     }
     if(($this->ed291_i_codigo == null) || ($this->ed291_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed291_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censocartorio(
                                       ed291_i_codigo 
                                      ,ed291_c_nome 
                                      ,ed291_i_serventia 
                                      ,ed291_i_censomunic 
                       )
                values (
                                $this->ed291_i_codigo 
                               ,'$this->ed291_c_nome' 
                               ,$this->ed291_i_serventia 
                               ,$this->ed291_i_censomunic 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Censo Cart�rios ($this->ed291_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Censo Cart�rios j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Censo Cart�rios ($this->ed291_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed291_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed291_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18006,'$this->ed291_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3183,18006,'','".AddSlashes(pg_result($resaco,0,'ed291_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3183,18007,'','".AddSlashes(pg_result($resaco,0,'ed291_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3183,18008,'','".AddSlashes(pg_result($resaco,0,'ed291_i_serventia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3183,18009,'','".AddSlashes(pg_result($resaco,0,'ed291_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed291_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update censocartorio set ";
     $virgula = "";
     if(trim($this->ed291_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed291_i_codigo"])){ 
       $sql  .= $virgula." ed291_i_codigo = $this->ed291_i_codigo ";
       $virgula = ",";
       if(trim($this->ed291_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed291_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed291_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed291_c_nome"])){ 
       $sql  .= $virgula." ed291_c_nome = '$this->ed291_c_nome' ";
       $virgula = ",";
       if(trim($this->ed291_c_nome) == null ){ 
         $this->erro_sql = " Campo Cart�rio nao Informado.";
         $this->erro_campo = "ed291_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed291_i_serventia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed291_i_serventia"])){ 
       $sql  .= $virgula." ed291_i_serventia = $this->ed291_i_serventia ";
       $virgula = ",";
       if(trim($this->ed291_i_serventia) == null ){ 
         $this->erro_sql = " Campo Serventia nao Informado.";
         $this->erro_campo = "ed291_i_serventia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed291_i_censomunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed291_i_censomunic"])){ 
       $sql  .= $virgula." ed291_i_censomunic = $this->ed291_i_censomunic ";
       $virgula = ",";
       if(trim($this->ed291_i_censomunic) == null ){ 
         $this->erro_sql = " Campo Munic�pio nao Informado.";
         $this->erro_campo = "ed291_i_censomunic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed291_i_codigo!=null){
       $sql .= " ed291_i_codigo = $this->ed291_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed291_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18006,'$this->ed291_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed291_i_codigo"]) || $this->ed291_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3183,18006,'".AddSlashes(pg_result($resaco,$conresaco,'ed291_i_codigo'))."','$this->ed291_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed291_c_nome"]) || $this->ed291_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,3183,18007,'".AddSlashes(pg_result($resaco,$conresaco,'ed291_c_nome'))."','$this->ed291_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed291_i_serventia"]) || $this->ed291_i_serventia != "")
           $resac = db_query("insert into db_acount values($acount,3183,18008,'".AddSlashes(pg_result($resaco,$conresaco,'ed291_i_serventia'))."','$this->ed291_i_serventia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed291_i_censomunic"]) || $this->ed291_i_censomunic != "")
           $resac = db_query("insert into db_acount values($acount,3183,18009,'".AddSlashes(pg_result($resaco,$conresaco,'ed291_i_censomunic'))."','$this->ed291_i_censomunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Censo Cart�rios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed291_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Censo Cart�rios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed291_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed291_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed291_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed291_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18006,'$ed291_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3183,18006,'','".AddSlashes(pg_result($resaco,$iresaco,'ed291_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3183,18007,'','".AddSlashes(pg_result($resaco,$iresaco,'ed291_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3183,18008,'','".AddSlashes(pg_result($resaco,$iresaco,'ed291_i_serventia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3183,18009,'','".AddSlashes(pg_result($resaco,$iresaco,'ed291_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from censocartorio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed291_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed291_i_codigo = $ed291_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Censo Cart�rios nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed291_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Censo Cart�rios nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed291_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed291_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:censocartorio";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed291_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from censocartorio ";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = censocartorio.ed291_i_censomunic";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = censomunic.ed261_i_censouf";
     $sql2 = "";
     if($dbwhere==""){
       if($ed291_i_codigo!=null ){
         $sql2 .= " where censocartorio.ed291_i_codigo = $ed291_i_codigo "; 
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
   function sql_query_file ( $ed291_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from censocartorio ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed291_i_codigo!=null ){
         $sql2 .= " where censocartorio.ed291_i_codigo = $ed291_i_codigo "; 
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