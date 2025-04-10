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

//MODULO: Custos
//CLASSE DA ENTIDADE custoplanilhacustoapropria
class cl_custoplanilhacustoapropria { 
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
   var $cc18_sequencial = 0; 
   var $cc18_custoapropria = 0; 
   var $cc18_custoplanilhaapuracao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc18_sequencial = int4 = Sequencial 
                 cc18_custoapropria = int4 = Custo a Propria 
                 cc18_custoplanilhaapuracao = int4 = Custo Planilha Apura��o 
                 ";
   //funcao construtor da classe 
   function cl_custoplanilhacustoapropria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoplanilhacustoapropria"); 
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
       $this->cc18_sequencial = ($this->cc18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc18_sequencial"]:$this->cc18_sequencial);
       $this->cc18_custoapropria = ($this->cc18_custoapropria == ""?@$GLOBALS["HTTP_POST_VARS"]["cc18_custoapropria"]:$this->cc18_custoapropria);
       $this->cc18_custoplanilhaapuracao = ($this->cc18_custoplanilhaapuracao == ""?@$GLOBALS["HTTP_POST_VARS"]["cc18_custoplanilhaapuracao"]:$this->cc18_custoplanilhaapuracao);
     }else{
       $this->cc18_sequencial = ($this->cc18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc18_sequencial"]:$this->cc18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc18_sequencial){ 
      $this->atualizacampos();
     if($this->cc18_custoapropria == null ){ 
       $this->erro_sql = " Campo Custo a Propria nao Informado.";
       $this->erro_campo = "cc18_custoapropria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc18_custoplanilhaapuracao == null ){ 
       $this->erro_sql = " Campo Custo Planilha Apura��o nao Informado.";
       $this->erro_campo = "cc18_custoplanilhaapuracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc18_sequencial == "" || $cc18_sequencial == null ){
       $result = db_query("select nextval('custoplanilhacustoapropria_cc18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoplanilhacustoapropria_cc18_sequencial_seq do campo: cc18_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoplanilhacustoapropria_cc18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc18_sequencial)){
         $this->erro_sql = " Campo cc18_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc18_sequencial = $cc18_sequencial; 
       }
     }
     if(($this->cc18_sequencial == null) || ($this->cc18_sequencial == "") ){ 
       $this->erro_sql = " Campo cc18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoplanilhacustoapropria(
                                       cc18_sequencial 
                                      ,cc18_custoapropria 
                                      ,cc18_custoplanilhaapuracao 
                       )
                values (
                                $this->cc18_sequencial 
                               ,$this->cc18_custoapropria 
                               ,$this->cc18_custoplanilhaapuracao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo Planilha Custo a Pr�pria ($this->cc18_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo Planilha Custo a Pr�pria j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo Planilha Custo a Pr�pria ($this->cc18_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc18_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15132,'$this->cc18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2663,15132,'','".AddSlashes(pg_result($resaco,0,'cc18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2663,15133,'','".AddSlashes(pg_result($resaco,0,'cc18_custoapropria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2663,15134,'','".AddSlashes(pg_result($resaco,0,'cc18_custoplanilhaapuracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoplanilhacustoapropria set ";
     $virgula = "";
     if(trim($this->cc18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc18_sequencial"])){ 
       $sql  .= $virgula." cc18_sequencial = $this->cc18_sequencial ";
       $virgula = ",";
       if(trim($this->cc18_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "cc18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc18_custoapropria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc18_custoapropria"])){ 
       $sql  .= $virgula." cc18_custoapropria = $this->cc18_custoapropria ";
       $virgula = ",";
       if(trim($this->cc18_custoapropria) == null ){ 
         $this->erro_sql = " Campo Custo a Propria nao Informado.";
         $this->erro_campo = "cc18_custoapropria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc18_custoplanilhaapuracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc18_custoplanilhaapuracao"])){ 
       $sql  .= $virgula." cc18_custoplanilhaapuracao = $this->cc18_custoplanilhaapuracao ";
       $virgula = ",";
       if(trim($this->cc18_custoplanilhaapuracao) == null ){ 
         $this->erro_sql = " Campo Custo Planilha Apura��o nao Informado.";
         $this->erro_campo = "cc18_custoplanilhaapuracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc18_sequencial!=null){
       $sql .= " cc18_sequencial = $this->cc18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15132,'$this->cc18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc18_sequencial"]) || $this->cc18_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2663,15132,'".AddSlashes(pg_result($resaco,$conresaco,'cc18_sequencial'))."','$this->cc18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc18_custoapropria"]) || $this->cc18_custoapropria != "")
           $resac = db_query("insert into db_acount values($acount,2663,15133,'".AddSlashes(pg_result($resaco,$conresaco,'cc18_custoapropria'))."','$this->cc18_custoapropria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc18_custoplanilhaapuracao"]) || $this->cc18_custoplanilhaapuracao != "")
           $resac = db_query("insert into db_acount values($acount,2663,15134,'".AddSlashes(pg_result($resaco,$conresaco,'cc18_custoplanilhaapuracao'))."','$this->cc18_custoplanilhaapuracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Planilha Custo a Pr�pria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc18_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Planilha Custo a Pr�pria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc18_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc18_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15132,'$cc18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2663,15132,'','".AddSlashes(pg_result($resaco,$iresaco,'cc18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2663,15133,'','".AddSlashes(pg_result($resaco,$iresaco,'cc18_custoapropria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2663,15134,'','".AddSlashes(pg_result($resaco,$iresaco,'cc18_custoplanilhaapuracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoplanilhacustoapropria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc18_sequencial = $cc18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo Planilha Custo a Pr�pria nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc18_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo Planilha Custo a Pr�pria nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc18_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoplanilhacustoapropria";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhacustoapropria ";
     $sql .= "      inner join custoapropria  on  custoapropria.cc12_sequencial = custoplanilhacustoapropria.cc18_custoapropria";
     $sql .= "      inner join custoplanilhaapuracao  on  custoplanilhaapuracao.cc17_sequencial = custoplanilhacustoapropria.cc18_custoplanilhaapuracao";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = custoapropria.cc12_matestoqueinimei";
     $sql .= "      inner join custocriteriorateio  on  custocriteriorateio.cc08_sequencial = custoapropria.cc12_custocriteriorateio";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanilhaapuracao.cc17_custoplanoanalitica";
     $sql .= "      inner join custoplanilhaorigem  on  custoplanilhaorigem.cc14_sequencial = custoplanilhaapuracao.cc17_custoplanilhaorigem";
     $sql .= "      inner join custoplanilha  as a on   a.cc15_sequencial = custoplanilhaapuracao.cc17_custoplanilha";
     $sql2 = "";
     if($dbwhere==""){
       if($cc18_sequencial!=null ){
         $sql2 .= " where custoplanilhacustoapropria.cc18_sequencial = $cc18_sequencial "; 
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
   function sql_query_file ( $cc18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoplanilhacustoapropria ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc18_sequencial!=null ){
         $sql2 .= " where custoplanilhacustoapropria.cc18_sequencial = $cc18_sequencial "; 
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