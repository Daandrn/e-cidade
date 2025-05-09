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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE suspensaofinaliza
class cl_suspensaofinaliza { 
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
   var $ar19_sequencial = 0; 
   var $ar19_suspensao = 0; 
   var $ar19_id_usuario = 0; 
   var $ar19_tipo = 0; 
   var $ar19_data_dia = null; 
   var $ar19_data_mes = null; 
   var $ar19_data_ano = null; 
   var $ar19_data = null; 
   var $ar19_hora = null; 
   var $ar19_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ar19_sequencial = int4 = C�digo da suspens�o finalizada 
                 ar19_suspensao = int4 = Suspens�o 
                 ar19_id_usuario = int4 = Usu�rio 
                 ar19_tipo = int4 = Tipo Finaliza��o 
                 ar19_data = date = Data Finaliza��o 
                 ar19_hora = char(5) = Hora Finaliza��o 
                 ar19_obs = text = Observa��o 
                 ";
   //funcao construtor da classe 
   function cl_suspensaofinaliza() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("suspensaofinaliza"); 
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
       $this->ar19_sequencial = ($this->ar19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_sequencial"]:$this->ar19_sequencial);
       $this->ar19_suspensao = ($this->ar19_suspensao == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_suspensao"]:$this->ar19_suspensao);
       $this->ar19_id_usuario = ($this->ar19_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_id_usuario"]:$this->ar19_id_usuario);
       $this->ar19_tipo = ($this->ar19_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_tipo"]:$this->ar19_tipo);
       if($this->ar19_data == ""){
         $this->ar19_data_dia = ($this->ar19_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_data_dia"]:$this->ar19_data_dia);
         $this->ar19_data_mes = ($this->ar19_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_data_mes"]:$this->ar19_data_mes);
         $this->ar19_data_ano = ($this->ar19_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_data_ano"]:$this->ar19_data_ano);
         if($this->ar19_data_dia != ""){
            $this->ar19_data = $this->ar19_data_ano."-".$this->ar19_data_mes."-".$this->ar19_data_dia;
         }
       }
       $this->ar19_hora = ($this->ar19_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_hora"]:$this->ar19_hora);
       $this->ar19_obs = ($this->ar19_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_obs"]:$this->ar19_obs);
     }else{
       $this->ar19_sequencial = ($this->ar19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ar19_sequencial"]:$this->ar19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ar19_sequencial){ 
      $this->atualizacampos();
     if($this->ar19_suspensao == null ){ 
       $this->erro_sql = " Campo Suspens�o nao Informado.";
       $this->erro_campo = "ar19_suspensao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar19_id_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "ar19_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar19_tipo == null ){ 
       $this->erro_sql = " Campo Tipo Finaliza��o nao Informado.";
       $this->erro_campo = "ar19_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar19_data == null ){ 
       $this->erro_sql = " Campo Data Finaliza��o nao Informado.";
       $this->erro_campo = "ar19_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar19_hora == null ){ 
       $this->erro_sql = " Campo Hora Finaliza��o nao Informado.";
       $this->erro_campo = "ar19_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ar19_obs == null ){ 
       $this->erro_sql = " Campo Observa��o nao Informado.";
       $this->erro_campo = "ar19_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ar19_sequencial == "" || $ar19_sequencial == null ){
       $result = db_query("select nextval('suspensaofinaliza_ar19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: suspensaofinaliza_ar19_sequencial_seq do campo: ar19_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ar19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from suspensaofinaliza_ar19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ar19_sequencial)){
         $this->erro_sql = " Campo ar19_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ar19_sequencial = $ar19_sequencial; 
       }
     }
     if(($this->ar19_sequencial == null) || ($this->ar19_sequencial == "") ){ 
       $this->erro_sql = " Campo ar19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into suspensaofinaliza(
                                       ar19_sequencial 
                                      ,ar19_suspensao 
                                      ,ar19_id_usuario 
                                      ,ar19_tipo 
                                      ,ar19_data 
                                      ,ar19_hora 
                                      ,ar19_obs 
                       )
                values (
                                $this->ar19_sequencial 
                               ,$this->ar19_suspensao 
                               ,$this->ar19_id_usuario 
                               ,$this->ar19_tipo 
                               ,".($this->ar19_data == "null" || $this->ar19_data == ""?"null":"'".$this->ar19_data."'")." 
                               ,'$this->ar19_hora' 
                               ,'$this->ar19_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Suspens�es finalizadas ($this->ar19_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Suspens�es finalizadas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Suspens�es finalizadas ($this->ar19_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar19_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ar19_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12690,'$this->ar19_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2217,12690,'','".AddSlashes(pg_result($resaco,0,'ar19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2217,12699,'','".AddSlashes(pg_result($resaco,0,'ar19_suspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2217,13290,'','".AddSlashes(pg_result($resaco,0,'ar19_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2217,13293,'','".AddSlashes(pg_result($resaco,0,'ar19_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2217,13291,'','".AddSlashes(pg_result($resaco,0,'ar19_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2217,13292,'','".AddSlashes(pg_result($resaco,0,'ar19_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2217,12700,'','".AddSlashes(pg_result($resaco,0,'ar19_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ar19_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update suspensaofinaliza set ";
     $virgula = "";
     if(trim($this->ar19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_sequencial"])){ 
       $sql  .= $virgula." ar19_sequencial = $this->ar19_sequencial ";
       $virgula = ",";
       if(trim($this->ar19_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo da suspens�o finalizada nao Informado.";
         $this->erro_campo = "ar19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar19_suspensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_suspensao"])){ 
       $sql  .= $virgula." ar19_suspensao = $this->ar19_suspensao ";
       $virgula = ",";
       if(trim($this->ar19_suspensao) == null ){ 
         $this->erro_sql = " Campo Suspens�o nao Informado.";
         $this->erro_campo = "ar19_suspensao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar19_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_id_usuario"])){ 
       $sql  .= $virgula." ar19_id_usuario = $this->ar19_id_usuario ";
       $virgula = ",";
       if(trim($this->ar19_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "ar19_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar19_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_tipo"])){ 
       $sql  .= $virgula." ar19_tipo = $this->ar19_tipo ";
       $virgula = ",";
       if(trim($this->ar19_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo Finaliza��o nao Informado.";
         $this->erro_campo = "ar19_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar19_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ar19_data_dia"] !="") ){ 
       $sql  .= $virgula." ar19_data = '$this->ar19_data' ";
       $virgula = ",";
       if(trim($this->ar19_data) == null ){ 
         $this->erro_sql = " Campo Data Finaliza��o nao Informado.";
         $this->erro_campo = "ar19_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_data_dia"])){ 
         $sql  .= $virgula." ar19_data = null ";
         $virgula = ",";
         if(trim($this->ar19_data) == null ){ 
           $this->erro_sql = " Campo Data Finaliza��o nao Informado.";
           $this->erro_campo = "ar19_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ar19_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_hora"])){ 
       $sql  .= $virgula." ar19_hora = '$this->ar19_hora' ";
       $virgula = ",";
       if(trim($this->ar19_hora) == null ){ 
         $this->erro_sql = " Campo Hora Finaliza��o nao Informado.";
         $this->erro_campo = "ar19_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ar19_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ar19_obs"])){ 
       $sql  .= $virgula." ar19_obs = '$this->ar19_obs' ";
       $virgula = ",";
       if(trim($this->ar19_obs) == null ){ 
         $this->erro_sql = " Campo Observa��o nao Informado.";
         $this->erro_campo = "ar19_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ar19_sequencial!=null){
       $sql .= " ar19_sequencial = $this->ar19_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ar19_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12690,'$this->ar19_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2217,12690,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_sequencial'))."','$this->ar19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_suspensao"]))
           $resac = db_query("insert into db_acount values($acount,2217,12699,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_suspensao'))."','$this->ar19_suspensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,2217,13290,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_id_usuario'))."','$this->ar19_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2217,13293,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_tipo'))."','$this->ar19_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_data"]))
           $resac = db_query("insert into db_acount values($acount,2217,13291,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_data'))."','$this->ar19_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_hora"]))
           $resac = db_query("insert into db_acount values($acount,2217,13292,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_hora'))."','$this->ar19_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ar19_obs"]))
           $resac = db_query("insert into db_acount values($acount,2217,12700,'".AddSlashes(pg_result($resaco,$conresaco,'ar19_obs'))."','$this->ar19_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspens�es finalizadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar19_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspens�es finalizadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ar19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ar19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ar19_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ar19_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12690,'$ar19_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2217,12690,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2217,12699,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_suspensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2217,13290,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2217,13293,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2217,13291,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2217,13292,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2217,12700,'','".AddSlashes(pg_result($resaco,$iresaco,'ar19_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from suspensaofinaliza
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ar19_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ar19_sequencial = $ar19_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspens�es finalizadas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ar19_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspens�es finalizadas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ar19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ar19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:suspensaofinaliza";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ar19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from suspensaofinaliza ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = suspensaofinaliza.ar19_id_usuario";
     $sql .= "      inner join suspensao  on  suspensao.ar18_sequencial = suspensaofinaliza.ar19_suspensao";
     $sql .= "      inner join db_config  on  db_config.codigo = suspensao.ar18_instit";
     $sql .= "      inner join procjur  on  procjur.v62_sequencial = suspensao.ar18_procjur";
     $sql2 = "";
     if($dbwhere==""){
       if($ar19_sequencial!=null ){
         $sql2 .= " where suspensaofinaliza.ar19_sequencial = $ar19_sequencial "; 
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
   function sql_query_file ( $ar19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from suspensaofinaliza ";
     $sql2 = "";
     if($dbwhere==""){
       if($ar19_sequencial!=null ){
         $sql2 .= " where suspensaofinaliza.ar19_sequencial = $ar19_sequencial "; 
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