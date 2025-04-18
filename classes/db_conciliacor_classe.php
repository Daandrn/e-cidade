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

//MODULO: caixa
//CLASSE DA ENTIDADE conciliacor
class cl_conciliacor { 
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
   var $k84_sequencial = 0; 
   var $k84_conciliaitem = 0; 
   var $k84_id = 0; 
   var $k84_data_dia = null; 
   var $k84_data_mes = null; 
   var $k84_data_ano = null; 
   var $k84_data = null; 
   var $k84_autent = 0; 
   var $k84_conciliaorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k84_sequencial = int8 = Codigo sequencial 
                 k84_conciliaitem = int4 = Item da concilia��o 
                 k84_id = int4 = Autentica��o 
                 k84_data = date = Data Autentica��o 
                 k84_autent = int4 = C�digo Autentica��o 
                 k84_conciliaorigem = int4 = Origem 
                 ";
   //funcao construtor da classe 
   function cl_conciliacor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conciliacor"); 
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
       $this->k84_sequencial = ($this->k84_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_sequencial"]:$this->k84_sequencial);
       $this->k84_conciliaitem = ($this->k84_conciliaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_conciliaitem"]:$this->k84_conciliaitem);
       $this->k84_id = ($this->k84_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_id"]:$this->k84_id);
       if($this->k84_data == ""){
         $this->k84_data_dia = ($this->k84_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_data_dia"]:$this->k84_data_dia);
         $this->k84_data_mes = ($this->k84_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_data_mes"]:$this->k84_data_mes);
         $this->k84_data_ano = ($this->k84_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_data_ano"]:$this->k84_data_ano);
         if($this->k84_data_dia != ""){
            $this->k84_data = $this->k84_data_ano."-".$this->k84_data_mes."-".$this->k84_data_dia;
         }
       }
       $this->k84_autent = ($this->k84_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_autent"]:$this->k84_autent);
       $this->k84_conciliaorigem = ($this->k84_conciliaorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_conciliaorigem"]:$this->k84_conciliaorigem);
     }else{
       $this->k84_sequencial = ($this->k84_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k84_sequencial"]:$this->k84_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k84_sequencial){ 
      $this->atualizacampos();
     if($this->k84_conciliaitem == null ){ 
       $this->erro_sql = " Campo Item da concilia��o nao Informado.";
       $this->erro_campo = "k84_conciliaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k84_id == null ){ 
       $this->erro_sql = " Campo Autentica��o nao Informado.";
       $this->erro_campo = "k84_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k84_data == null ){ 
       $this->erro_sql = " Campo Data Autentica��o nao Informado.";
       $this->erro_campo = "k84_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k84_autent == null ){ 
       $this->erro_sql = " Campo C�digo Autentica��o nao Informado.";
       $this->erro_campo = "k84_autent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k84_conciliaorigem == null ){ 
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "k84_conciliaorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k84_sequencial == "" || $k84_sequencial == null ){
       $result = db_query("select nextval('conciliacor_k84_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conciliacor_k84_sequencial_seq do campo: k84_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k84_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conciliacor_k84_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k84_sequencial)){
         $this->erro_sql = " Campo k84_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k84_sequencial = $k84_sequencial; 
       }
     }
     if(($this->k84_sequencial == null) || ($this->k84_sequencial == "") ){ 
       $this->erro_sql = " Campo k84_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conciliacor(
                                       k84_sequencial 
                                      ,k84_conciliaitem 
                                      ,k84_id 
                                      ,k84_data 
                                      ,k84_autent 
                                      ,k84_conciliaorigem 
                       )
                values (
                                $this->k84_sequencial 
                               ,$this->k84_conciliaitem 
                               ,$this->k84_id 
                               ,".($this->k84_data == "null" || $this->k84_data == ""?"null":"'".$this->k84_data."'")." 
                               ,$this->k84_autent 
                               ,$this->k84_conciliaorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liga��o do item com a corrente ($this->k84_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liga��o do item com a corrente j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liga��o do item com a corrente ($this->k84_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k84_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k84_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10094,'$this->k84_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1736,10094,'','".AddSlashes(pg_result($resaco,0,'k84_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1736,10095,'','".AddSlashes(pg_result($resaco,0,'k84_conciliaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1736,10096,'','".AddSlashes(pg_result($resaco,0,'k84_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1736,10097,'','".AddSlashes(pg_result($resaco,0,'k84_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1736,10098,'','".AddSlashes(pg_result($resaco,0,'k84_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1736,10152,'','".AddSlashes(pg_result($resaco,0,'k84_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k84_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conciliacor set ";
     $virgula = "";
     if(trim($this->k84_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k84_sequencial"])){ 
       $sql  .= $virgula." k84_sequencial = $this->k84_sequencial ";
       $virgula = ",";
       if(trim($this->k84_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k84_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k84_conciliaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k84_conciliaitem"])){ 
       $sql  .= $virgula." k84_conciliaitem = $this->k84_conciliaitem ";
       $virgula = ",";
       if(trim($this->k84_conciliaitem) == null ){ 
         $this->erro_sql = " Campo Item da concilia��o nao Informado.";
         $this->erro_campo = "k84_conciliaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k84_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k84_id"])){ 
       $sql  .= $virgula." k84_id = $this->k84_id ";
       $virgula = ",";
       if(trim($this->k84_id) == null ){ 
         $this->erro_sql = " Campo Autentica��o nao Informado.";
         $this->erro_campo = "k84_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k84_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k84_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k84_data_dia"] !="") ){ 
       $sql  .= $virgula." k84_data = '$this->k84_data' ";
       $virgula = ",";
       if(trim($this->k84_data) == null ){ 
         $this->erro_sql = " Campo Data Autentica��o nao Informado.";
         $this->erro_campo = "k84_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k84_data_dia"])){ 
         $sql  .= $virgula." k84_data = null ";
         $virgula = ",";
         if(trim($this->k84_data) == null ){ 
           $this->erro_sql = " Campo Data Autentica��o nao Informado.";
           $this->erro_campo = "k84_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k84_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k84_autent"])){ 
       $sql  .= $virgula." k84_autent = $this->k84_autent ";
       $virgula = ",";
       if(trim($this->k84_autent) == null ){ 
         $this->erro_sql = " Campo C�digo Autentica��o nao Informado.";
         $this->erro_campo = "k84_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k84_conciliaorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k84_conciliaorigem"])){ 
       $sql  .= $virgula." k84_conciliaorigem = $this->k84_conciliaorigem ";
       $virgula = ",";
       if(trim($this->k84_conciliaorigem) == null ){ 
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "k84_conciliaorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k84_sequencial!=null){
       $sql .= " k84_sequencial = $this->k84_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k84_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10094,'$this->k84_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k84_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1736,10094,'".AddSlashes(pg_result($resaco,$conresaco,'k84_sequencial'))."','$this->k84_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k84_conciliaitem"]))
           $resac = db_query("insert into db_acount values($acount,1736,10095,'".AddSlashes(pg_result($resaco,$conresaco,'k84_conciliaitem'))."','$this->k84_conciliaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k84_id"]))
           $resac = db_query("insert into db_acount values($acount,1736,10096,'".AddSlashes(pg_result($resaco,$conresaco,'k84_id'))."','$this->k84_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k84_data"]))
           $resac = db_query("insert into db_acount values($acount,1736,10097,'".AddSlashes(pg_result($resaco,$conresaco,'k84_data'))."','$this->k84_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k84_autent"]))
           $resac = db_query("insert into db_acount values($acount,1736,10098,'".AddSlashes(pg_result($resaco,$conresaco,'k84_autent'))."','$this->k84_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k84_conciliaorigem"]))
           $resac = db_query("insert into db_acount values($acount,1736,10152,'".AddSlashes(pg_result($resaco,$conresaco,'k84_conciliaorigem'))."','$this->k84_conciliaorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga��o do item com a corrente nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k84_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga��o do item com a corrente nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k84_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k84_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k84_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k84_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10094,'$k84_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1736,10094,'','".AddSlashes(pg_result($resaco,$iresaco,'k84_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1736,10095,'','".AddSlashes(pg_result($resaco,$iresaco,'k84_conciliaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1736,10096,'','".AddSlashes(pg_result($resaco,$iresaco,'k84_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1736,10097,'','".AddSlashes(pg_result($resaco,$iresaco,'k84_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1736,10098,'','".AddSlashes(pg_result($resaco,$iresaco,'k84_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1736,10152,'','".AddSlashes(pg_result($resaco,$iresaco,'k84_conciliaorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conciliacor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k84_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k84_sequencial = $k84_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liga��o do item com a corrente nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k84_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liga��o do item com a corrente nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k84_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k84_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conciliacor";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conciliacor ";
     $sql .= "      inner join corrente  on  corrente.k12_id = conciliacor.k84_id and  corrente.k12_data = conciliacor.k84_data and  corrente.k12_autent = conciliacor.k84_autent";
     $sql .= "      inner join conciliaitem  on  conciliaitem.k83_sequencial = conciliacor.k84_conciliaitem";
     $sql .= "      inner join conciliaorigem  on  conciliaorigem.k96_sequencial = conciliacor.k84_conciliaorigem";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = corrente.k12_instit";
     $sql .= "      inner join db_depart  as a on   a.coddepto = corrente.k12_instit";
     $sql .= "      inner join db_depart  as b on   b.coddepto = corrente.k12_instit";
     $sql .= "      inner join conciliatipo  on  conciliatipo.k65_sequencial = conciliaitem.k83_conciliatipo";
     $sql .= "      inner join concilia  as c on   c.k68_sequencial = conciliaitem.k83_concilia";
     $sql2 = "";
     if($dbwhere==""){
       if($k84_sequencial!=null ){
         $sql2 .= " where conciliacor.k84_sequencial = $k84_sequencial "; 
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
   function sql_query_file ( $k84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conciliacor ";
     $sql2 = "";
     if($dbwhere==""){
       if($k84_sequencial!=null ){
         $sql2 .= " where conciliacor.k84_sequencial = $k84_sequencial "; 
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