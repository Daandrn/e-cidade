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

//MODULO: escola
//CLASSE DA ENTIDADE turmaacprof
class cl_turmaacprof { 
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
   var $ed222_i_codigo = 0; 
   var $ed222_i_turmaac = 0; 
   var $ed222_i_rechumano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed222_i_codigo = int4 = C�digo 
                 ed222_i_turmaac = int4 = Turma 
                 ed222_i_rechumano = int4 = Profissional/Monitor 
                 ";
   //funcao construtor da classe 
   function cl_turmaacprof() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaacprof"); 
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
       $this->ed222_i_codigo = ($this->ed222_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"]:$this->ed222_i_codigo);
       $this->ed222_i_turmaac = ($this->ed222_i_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_turmaac"]:$this->ed222_i_turmaac);
       $this->ed222_i_rechumano = ($this->ed222_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_rechumano"]:$this->ed222_i_rechumano);
     }else{
       $this->ed222_i_codigo = ($this->ed222_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"]:$this->ed222_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed222_i_codigo){ 
      $this->atualizacampos();
     if($this->ed222_i_turmaac == null ){ 
       $this->erro_sql = " Campo Turma nao Informado.";
       $this->erro_campo = "ed222_i_turmaac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed222_i_rechumano == null ){ 
       $this->erro_sql = " Campo Profissional/Monitor nao Informado.";
       $this->erro_campo = "ed222_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed222_i_codigo == "" || $ed222_i_codigo == null ){
       $result = db_query("select nextval('turmaacprof_ed222_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaacprof_ed222_i_codigo_seq do campo: ed222_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed222_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmaacprof_ed222_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed222_i_codigo)){
         $this->erro_sql = " Campo ed222_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed222_i_codigo = $ed222_i_codigo; 
       }
     }
     if(($this->ed222_i_codigo == null) || ($this->ed222_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed222_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaacprof(
                                       ed222_i_codigo 
                                      ,ed222_i_turmaac 
                                      ,ed222_i_rechumano 
                       )
                values (
                                $this->ed222_i_codigo 
                               ,$this->ed222_i_turmaac 
                               ,$this->ed222_i_rechumano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turmaacprof ($this->ed222_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turmaacprof j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turmaacprof ($this->ed222_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed222_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14985,'$this->ed222_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2634,14985,'','".AddSlashes(pg_result($resaco,0,'ed222_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2634,14986,'','".AddSlashes(pg_result($resaco,0,'ed222_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2634,14987,'','".AddSlashes(pg_result($resaco,0,'ed222_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed222_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmaacprof set ";
     $virgula = "";
     if(trim($this->ed222_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"])){ 
       $sql  .= $virgula." ed222_i_codigo = $this->ed222_i_codigo ";
       $virgula = ",";
       if(trim($this->ed222_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed222_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed222_i_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_turmaac"])){ 
       $sql  .= $virgula." ed222_i_turmaac = $this->ed222_i_turmaac ";
       $virgula = ",";
       if(trim($this->ed222_i_turmaac) == null ){ 
         $this->erro_sql = " Campo Turma nao Informado.";
         $this->erro_campo = "ed222_i_turmaac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed222_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_rechumano"])){ 
       $sql  .= $virgula." ed222_i_rechumano = $this->ed222_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed222_i_rechumano) == null ){ 
         $this->erro_sql = " Campo Profissional/Monitor nao Informado.";
         $this->erro_campo = "ed222_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed222_i_codigo!=null){
       $sql .= " ed222_i_codigo = $this->ed222_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed222_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14985,'$this->ed222_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_codigo"]) || $this->ed222_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2634,14985,'".AddSlashes(pg_result($resaco,$conresaco,'ed222_i_codigo'))."','$this->ed222_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_turmaac"]) || $this->ed222_i_turmaac != "")
           $resac = db_query("insert into db_acount values($acount,2634,14986,'".AddSlashes(pg_result($resaco,$conresaco,'ed222_i_turmaac'))."','$this->ed222_i_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed222_i_rechumano"]) || $this->ed222_i_rechumano != "")
           $resac = db_query("insert into db_acount values($acount,2634,14987,'".AddSlashes(pg_result($resaco,$conresaco,'ed222_i_rechumano'))."','$this->ed222_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaacprof nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmaacprof nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed222_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed222_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed222_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14985,'$ed222_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2634,14985,'','".AddSlashes(pg_result($resaco,$iresaco,'ed222_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2634,14986,'','".AddSlashes(pg_result($resaco,$iresaco,'ed222_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2634,14987,'','".AddSlashes(pg_result($resaco,$iresaco,'ed222_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmaacprof
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed222_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed222_i_codigo = $ed222_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaacprof nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed222_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turmaacprof nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed222_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed222_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaacprof";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed222_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaacprof ";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaacprof.ed222_i_turmaac";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = turmaacprof.ed222_i_rechumano";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicender and  censomunic.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
     $sql2 = "";
     if($dbwhere==""){
       if($ed222_i_codigo!=null ){
         $sql2 .= " where turmaacprof.ed222_i_codigo = $ed222_i_codigo "; 
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
   function sql_query_file ( $ed222_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaacprof ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed222_i_codigo!=null ){
         $sql2 .= " where turmaacprof.ed222_i_codigo = $ed222_i_codigo "; 
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