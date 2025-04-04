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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefa_aut
class cl_tarefa_aut { 
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
   var $at39_sequencia = 0; 
   var $at39_data_dia = null; 
   var $at39_data_mes = null; 
   var $at39_data_ano = null; 
   var $at39_data = null; 
   var $at39_hora = null; 
   var $at39_ip = null; 
   var $at39_tarefa = 0; 
   var $at39_usuario = 0; 
   var $at39_cancelada = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at39_sequencia = int4 = Autoriza��o 
                 at39_data = date = Data 
                 at39_hora = char(5) = Hora 
                 at39_ip = varchar(15) = IP 
                 at39_tarefa = int4 = Tarefa 
                 at39_usuario = int4 = Usu�rio 
                 at39_cancelada = bool = Cancelada 
                 ";
   //funcao construtor da classe 
   function cl_tarefa_aut() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa_aut"); 
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
       $this->at39_sequencia = ($this->at39_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_sequencia"]:$this->at39_sequencia);
       if($this->at39_data == ""){
         $this->at39_data_dia = ($this->at39_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_data_dia"]:$this->at39_data_dia);
         $this->at39_data_mes = ($this->at39_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_data_mes"]:$this->at39_data_mes);
         $this->at39_data_ano = ($this->at39_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_data_ano"]:$this->at39_data_ano);
         if($this->at39_data_dia != ""){
            $this->at39_data = $this->at39_data_ano."-".$this->at39_data_mes."-".$this->at39_data_dia;
         }
       }
       $this->at39_hora = ($this->at39_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_hora"]:$this->at39_hora);
       $this->at39_ip = ($this->at39_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_ip"]:$this->at39_ip);
       $this->at39_tarefa = ($this->at39_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_tarefa"]:$this->at39_tarefa);
       $this->at39_usuario = ($this->at39_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_usuario"]:$this->at39_usuario);
       $this->at39_cancelada = ($this->at39_cancelada == "f"?@$GLOBALS["HTTP_POST_VARS"]["at39_cancelada"]:$this->at39_cancelada);
     }else{
       $this->at39_sequencia = ($this->at39_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["at39_sequencia"]:$this->at39_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($at39_sequencia){ 
      $this->atualizacampos();
     if($this->at39_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "at39_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at39_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "at39_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at39_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "at39_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at39_tarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at39_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at39_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "at39_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at39_cancelada == null ){ 
       $this->erro_sql = " Campo Cancelada nao Informado.";
       $this->erro_campo = "at39_cancelada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at39_sequencia == "" || $at39_sequencia == null ){
       $result = db_query("select nextval('tarefa_aut_at39_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefa_aut_at39_sequencia_seq do campo: at39_sequencia"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at39_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefa_aut_at39_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $at39_sequencia)){
         $this->erro_sql = " Campo at39_sequencia maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at39_sequencia = $at39_sequencia; 
       }
     }
     if(($this->at39_sequencia == null) || ($this->at39_sequencia == "") ){ 
       $this->erro_sql = " Campo at39_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa_aut(
                                       at39_sequencia 
                                      ,at39_data 
                                      ,at39_hora 
                                      ,at39_ip 
                                      ,at39_tarefa 
                                      ,at39_usuario 
                                      ,at39_cancelada 
                       )
                values (
                                $this->at39_sequencia 
                               ,".($this->at39_data == "null" || $this->at39_data == ""?"null":"'".$this->at39_data."'")." 
                               ,'$this->at39_hora' 
                               ,'$this->at39_ip' 
                               ,$this->at39_tarefa 
                               ,$this->at39_usuario 
                               ,'$this->at39_cancelada' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autoriza��o para executar tarefas ($this->at39_sequencia) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autoriza��o para executar tarefas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autoriza��o para executar tarefas ($this->at39_sequencia) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at39_sequencia;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at39_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8813,'$this->at39_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1505,8813,'','".AddSlashes(pg_result($resaco,0,'at39_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1505,8814,'','".AddSlashes(pg_result($resaco,0,'at39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1505,8816,'','".AddSlashes(pg_result($resaco,0,'at39_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1505,8817,'','".AddSlashes(pg_result($resaco,0,'at39_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1505,8818,'','".AddSlashes(pg_result($resaco,0,'at39_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1505,8819,'','".AddSlashes(pg_result($resaco,0,'at39_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1505,8820,'','".AddSlashes(pg_result($resaco,0,'at39_cancelada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at39_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update tarefa_aut set ";
     $virgula = "";
     if(trim($this->at39_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_sequencia"])){ 
       $sql  .= $virgula." at39_sequencia = $this->at39_sequencia ";
       $virgula = ",";
       if(trim($this->at39_sequencia) == null ){ 
         $this->erro_sql = " Campo Autoriza��o nao Informado.";
         $this->erro_campo = "at39_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at39_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at39_data_dia"] !="") ){ 
       $sql  .= $virgula." at39_data = '$this->at39_data' ";
       $virgula = ",";
       if(trim($this->at39_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "at39_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at39_data_dia"])){ 
         $sql  .= $virgula." at39_data = null ";
         $virgula = ",";
         if(trim($this->at39_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "at39_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at39_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_hora"])){ 
       $sql  .= $virgula." at39_hora = '$this->at39_hora' ";
       $virgula = ",";
       if(trim($this->at39_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "at39_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at39_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_ip"])){ 
       $sql  .= $virgula." at39_ip = '$this->at39_ip' ";
       $virgula = ",";
       if(trim($this->at39_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "at39_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at39_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_tarefa"])){ 
       $sql  .= $virgula." at39_tarefa = $this->at39_tarefa ";
       $virgula = ",";
       if(trim($this->at39_tarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at39_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at39_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_usuario"])){ 
       $sql  .= $virgula." at39_usuario = $this->at39_usuario ";
       $virgula = ",";
       if(trim($this->at39_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "at39_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at39_cancelada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at39_cancelada"])){ 
       $sql  .= $virgula." at39_cancelada = '$this->at39_cancelada' ";
       $virgula = ",";
       if(trim($this->at39_cancelada) == null ){ 
         $this->erro_sql = " Campo Cancelada nao Informado.";
         $this->erro_campo = "at39_cancelada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at39_sequencia!=null){
       $sql .= " at39_sequencia = $this->at39_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at39_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8813,'$this->at39_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1505,8813,'".AddSlashes(pg_result($resaco,$conresaco,'at39_sequencia'))."','$this->at39_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_data"]))
           $resac = db_query("insert into db_acount values($acount,1505,8814,'".AddSlashes(pg_result($resaco,$conresaco,'at39_data'))."','$this->at39_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_hora"]))
           $resac = db_query("insert into db_acount values($acount,1505,8816,'".AddSlashes(pg_result($resaco,$conresaco,'at39_hora'))."','$this->at39_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_ip"]))
           $resac = db_query("insert into db_acount values($acount,1505,8817,'".AddSlashes(pg_result($resaco,$conresaco,'at39_ip'))."','$this->at39_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1505,8818,'".AddSlashes(pg_result($resaco,$conresaco,'at39_tarefa'))."','$this->at39_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1505,8819,'".AddSlashes(pg_result($resaco,$conresaco,'at39_usuario'))."','$this->at39_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at39_cancelada"]))
           $resac = db_query("insert into db_acount values($acount,1505,8820,'".AddSlashes(pg_result($resaco,$conresaco,'at39_cancelada'))."','$this->at39_cancelada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autoriza��o para executar tarefas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at39_sequencia;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autoriza��o para executar tarefas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at39_sequencia;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at39_sequencia;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at39_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at39_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8813,'$at39_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1505,8813,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1505,8814,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1505,8816,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1505,8817,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1505,8818,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1505,8819,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1505,8820,'','".AddSlashes(pg_result($resaco,$iresaco,'at39_cancelada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa_aut
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at39_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at39_sequencia = $at39_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autoriza��o para executar tarefas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at39_sequencia;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autoriza��o para executar tarefas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at39_sequencia;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at39_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefa_aut";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at39_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa ";
     $sql .= "      left join tarefa_aut              on tarefa_aut.at39_tarefa        = tarefa.at40_sequencial";
     $sql .= "      left join tarefa_autcanc          on tarefa_autcanc.at38_tarefaaut = tarefa_aut.at39_sequencia";
     $sql .= "      inner join db_usuarios            on db_usuarios.id_usuario        = tarefa.at40_responsavel";
     $sql .= "      left join db_usuarios as usuarios on usuarios.id_usuario           = tarefa_aut.at39_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at39_sequencia!=null ){
         $sql2 .= " where tarefa_aut.at39_sequencia = $at39_sequencia "; 
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
   function sql_query_file ( $at39_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa_aut ";
     $sql2 = "";
     if($dbwhere==""){
       if($at39_sequencia!=null ){
         $sql2 .= " where tarefa_aut.at39_sequencia = $at39_sequencia "; 
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