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
//CLASSE DA ENTIDADE custoapropria
class cl_custoapropria { 
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
   var $cc12_sequencial = 0; 
   var $cc12_custocriteriorateio = 0; 
   var $cc12_matestoqueinimei = 0; 
   var $cc12_qtd = 0; 
   var $cc12_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc12_sequencial = int4 = C�digo Sequencial 
                 cc12_custocriteriorateio = int4 = C�digo do Crit�rio 
                 cc12_matestoqueinimei = int4 = C�digo da movimentacao 
                 cc12_qtd = float8 = Quantidade de Itens 
                 cc12_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_custoapropria() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custoapropria"); 
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
       $this->cc12_sequencial = ($this->cc12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc12_sequencial"]:$this->cc12_sequencial);
       $this->cc12_custocriteriorateio = ($this->cc12_custocriteriorateio == ""?@$GLOBALS["HTTP_POST_VARS"]["cc12_custocriteriorateio"]:$this->cc12_custocriteriorateio);
       $this->cc12_matestoqueinimei = ($this->cc12_matestoqueinimei == ""?@$GLOBALS["HTTP_POST_VARS"]["cc12_matestoqueinimei"]:$this->cc12_matestoqueinimei);
       $this->cc12_qtd = ($this->cc12_qtd == ""?@$GLOBALS["HTTP_POST_VARS"]["cc12_qtd"]:$this->cc12_qtd);
       $this->cc12_valor = ($this->cc12_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["cc12_valor"]:$this->cc12_valor);
     }else{
       $this->cc12_sequencial = ($this->cc12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc12_sequencial"]:$this->cc12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc12_sequencial){ 
      $this->atualizacampos();
     if($this->cc12_custocriteriorateio == null ){ 
       $this->erro_sql = " Campo C�digo do Crit�rio nao Informado.";
       $this->erro_campo = "cc12_custocriteriorateio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc12_matestoqueinimei == null ){ 
       $this->erro_sql = " Campo C�digo da movimentacao nao Informado.";
       $this->erro_campo = "cc12_matestoqueinimei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc12_qtd == null ){ 
       $this->erro_sql = " Campo Quantidade de Itens nao Informado.";
       $this->erro_campo = "cc12_qtd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc12_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "cc12_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc12_sequencial == "" || $cc12_sequencial == null ){
       $result = db_query("select nextval('custoapropria_cc12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custoapropria_cc12_sequencial_seq do campo: cc12_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custoapropria_cc12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc12_sequencial)){
         $this->erro_sql = " Campo cc12_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc12_sequencial = $cc12_sequencial; 
       }
     }
     if(($this->cc12_sequencial == null) || ($this->cc12_sequencial == "") ){ 
       $this->erro_sql = " Campo cc12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custoapropria(
                                       cc12_sequencial 
                                      ,cc12_custocriteriorateio 
                                      ,cc12_matestoqueinimei 
                                      ,cc12_qtd 
                                      ,cc12_valor 
                       )
                values (
                                $this->cc12_sequencial 
                               ,$this->cc12_custocriteriorateio 
                               ,$this->cc12_matestoqueinimei 
                               ,$this->cc12_qtd 
                               ,$this->cc12_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Apropriacao dos custos ($this->cc12_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Apropriacao dos custos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Apropriacao dos custos ($this->cc12_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc12_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc12_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13473,'$this->cc12_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2358,13473,'','".AddSlashes(pg_result($resaco,0,'cc12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2358,13474,'','".AddSlashes(pg_result($resaco,0,'cc12_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2358,13475,'','".AddSlashes(pg_result($resaco,0,'cc12_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2358,13476,'','".AddSlashes(pg_result($resaco,0,'cc12_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2358,13477,'','".AddSlashes(pg_result($resaco,0,'cc12_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custoapropria set ";
     $virgula = "";
     if(trim($this->cc12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc12_sequencial"])){ 
       $sql  .= $virgula." cc12_sequencial = $this->cc12_sequencial ";
       $virgula = ",";
       if(trim($this->cc12_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "cc12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc12_custocriteriorateio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc12_custocriteriorateio"])){ 
       $sql  .= $virgula." cc12_custocriteriorateio = $this->cc12_custocriteriorateio ";
       $virgula = ",";
       if(trim($this->cc12_custocriteriorateio) == null ){ 
         $this->erro_sql = " Campo C�digo do Crit�rio nao Informado.";
         $this->erro_campo = "cc12_custocriteriorateio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc12_matestoqueinimei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc12_matestoqueinimei"])){ 
       $sql  .= $virgula." cc12_matestoqueinimei = $this->cc12_matestoqueinimei ";
       $virgula = ",";
       if(trim($this->cc12_matestoqueinimei) == null ){ 
         $this->erro_sql = " Campo C�digo da movimentacao nao Informado.";
         $this->erro_campo = "cc12_matestoqueinimei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc12_qtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc12_qtd"])){ 
       $sql  .= $virgula." cc12_qtd = $this->cc12_qtd ";
       $virgula = ",";
       if(trim($this->cc12_qtd) == null ){ 
         $this->erro_sql = " Campo Quantidade de Itens nao Informado.";
         $this->erro_campo = "cc12_qtd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc12_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc12_valor"])){ 
       $sql  .= $virgula." cc12_valor = $this->cc12_valor ";
       $virgula = ",";
       if(trim($this->cc12_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "cc12_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc12_sequencial!=null){
       $sql .= " cc12_sequencial = $this->cc12_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc12_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13473,'$this->cc12_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc12_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2358,13473,'".AddSlashes(pg_result($resaco,$conresaco,'cc12_sequencial'))."','$this->cc12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc12_custocriteriorateio"]))
           $resac = db_query("insert into db_acount values($acount,2358,13474,'".AddSlashes(pg_result($resaco,$conresaco,'cc12_custocriteriorateio'))."','$this->cc12_custocriteriorateio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc12_matestoqueinimei"]))
           $resac = db_query("insert into db_acount values($acount,2358,13475,'".AddSlashes(pg_result($resaco,$conresaco,'cc12_matestoqueinimei'))."','$this->cc12_matestoqueinimei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc12_qtd"]))
           $resac = db_query("insert into db_acount values($acount,2358,13476,'".AddSlashes(pg_result($resaco,$conresaco,'cc12_qtd'))."','$this->cc12_qtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc12_valor"]))
           $resac = db_query("insert into db_acount values($acount,2358,13477,'".AddSlashes(pg_result($resaco,$conresaco,'cc12_valor'))."','$this->cc12_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Apropriacao dos custos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc12_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Apropriacao dos custos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc12_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc12_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc12_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc12_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13473,'$cc12_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2358,13473,'','".AddSlashes(pg_result($resaco,$iresaco,'cc12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2358,13474,'','".AddSlashes(pg_result($resaco,$iresaco,'cc12_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2358,13475,'','".AddSlashes(pg_result($resaco,$iresaco,'cc12_matestoqueinimei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2358,13476,'','".AddSlashes(pg_result($resaco,$iresaco,'cc12_qtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2358,13477,'','".AddSlashes(pg_result($resaco,$iresaco,'cc12_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custoapropria
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc12_sequencial = $cc12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Apropriacao dos custos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc12_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Apropriacao dos custos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc12_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custoapropria";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoapropria ";
     $sql .= "      inner join matestoqueinimei  on  matestoqueinimei.m82_codigo = custoapropria.cc12_matestoqueinimei";
     $sql .= "      inner join custocriteriorateio  on  custocriteriorateio.cc08_sequencial = custoapropria.cc12_custocriteriorateio";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueinimei.m82_matestoqueitem";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoqueinimei.m82_matestoqueini";
     $sql .= "      inner join db_config  on  db_config.codigo = custocriteriorateio.cc08_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = custocriteriorateio.cc08_coddepto";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = custocriteriorateio.cc08_matunid";
     $sql2 = "";
     if($dbwhere==""){
       if($cc12_sequencial!=null ){
         $sql2 .= " where custoapropria.cc12_sequencial = $cc12_sequencial "; 
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
   function sql_query_file ( $cc12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custoapropria ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc12_sequencial!=null ){
         $sql2 .= " where custoapropria.cc12_sequencial = $cc12_sequencial "; 
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
  
  function sql_query_custoapropria( $cc12_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    
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
     $sql .= " from custoapropria ";
     $sql .= "      inner join matestoqueinimei    on cc12_matestoqueinimei    = m82_codigo      ";
     $sql .= "      inner join matestoqueini       on m82_matestoqueini        = m80_codigo      ";
     $sql .= "      inner join matestoquetipo      on m80_codtipo              = m81_codtipo     ";
     $sql .= "      inner join custocriteriorateio on cc12_custocriteriorateio = cc08_sequencial ";
     $sql .= "      inner join matestoqueitem      on m82_matestoqueitem       = m71_codlanc     ";
     $sql .= "      inner join matestoque          on m71_codmatestoque        = m70_codigo      ";
     $sql .= "      inner join matmater            on m70_codmatmater          =  m60_codmater   ";
     $sql .= "      inner join db_depart as depto1 on m80_coddepto             = depto1.coddepto ";
     $sql .= "      left join  matestoqueinimeiari on m49_codmatestoqueinimei  = m82_codigo      ";
     $sql .= "      left join  atendrequiitem      on m49_codatendrequiitem    = m43_codigo      ";
     $sql .= "      left join  matrequiitem        on m43_codmatrequiitem      = m41_codigo      ";
     $sql .= "      left join  matrequi            on m41_codmatrequi          = m40_codigo      ";
     $sql .= "      left join db_depart as drequi  on m40_depto                = drequi.coddepto ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc12_sequencial!=null ){
         $sql2 .= " where custoapropria.cc12_sequencial = $cc12_sequencial "; 
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