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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_avisopaciente
class cl_tfd_avisopaciente { 
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
   var $tf21_i_codigo = 0; 
   var $tf21_i_pedidotfd = 0; 
   var $tf21_i_formaaviso = 0; 
   var $tf21_d_dataaviso_dia = null; 
   var $tf21_d_dataaviso_mes = null; 
   var $tf21_d_dataaviso_ano = null; 
   var $tf21_d_dataaviso = null; 
   var $tf21_c_horaaviso = null; 
   var $tf21_i_login = 0; 
   var $tf21_t_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf21_i_codigo = int4 = C�digo 
                 tf21_i_pedidotfd = int4 = Pedido 
                 tf21_i_formaaviso = int4 = Forma de Aviso 
                 tf21_d_dataaviso = date = Data 
                 tf21_c_horaaviso = char(5) = Hora 
                 tf21_i_login = int4 = Login 
                 tf21_t_obs = text = Avisado / Obs 
                 ";
   //funcao construtor da classe 
   function cl_tfd_avisopaciente() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_avisopaciente"); 
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
       $this->tf21_i_codigo = ($this->tf21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_i_codigo"]:$this->tf21_i_codigo);
       $this->tf21_i_pedidotfd = ($this->tf21_i_pedidotfd == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_i_pedidotfd"]:$this->tf21_i_pedidotfd);
       $this->tf21_i_formaaviso = ($this->tf21_i_formaaviso == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_i_formaaviso"]:$this->tf21_i_formaaviso);
       if($this->tf21_d_dataaviso == ""){
         $this->tf21_d_dataaviso_dia = ($this->tf21_d_dataaviso_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso_dia"]:$this->tf21_d_dataaviso_dia);
         $this->tf21_d_dataaviso_mes = ($this->tf21_d_dataaviso_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso_mes"]:$this->tf21_d_dataaviso_mes);
         $this->tf21_d_dataaviso_ano = ($this->tf21_d_dataaviso_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso_ano"]:$this->tf21_d_dataaviso_ano);
         if($this->tf21_d_dataaviso_dia != ""){
            $this->tf21_d_dataaviso = $this->tf21_d_dataaviso_ano."-".$this->tf21_d_dataaviso_mes."-".$this->tf21_d_dataaviso_dia;
         }
       }
       $this->tf21_c_horaaviso = ($this->tf21_c_horaaviso == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_c_horaaviso"]:$this->tf21_c_horaaviso);
       $this->tf21_i_login = ($this->tf21_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_i_login"]:$this->tf21_i_login);
       $this->tf21_t_obs = ($this->tf21_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_t_obs"]:$this->tf21_t_obs);
     }else{
       $this->tf21_i_codigo = ($this->tf21_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf21_i_codigo"]:$this->tf21_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf21_i_codigo){ 
      $this->atualizacampos();
     if($this->tf21_i_pedidotfd == null ){ 
       $this->erro_sql = " Campo Pedido nao Informado.";
       $this->erro_campo = "tf21_i_pedidotfd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf21_i_formaaviso == null ){ 
       $this->erro_sql = " Campo Forma de Aviso nao Informado.";
       $this->erro_campo = "tf21_i_formaaviso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf21_d_dataaviso == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tf21_d_dataaviso_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf21_c_horaaviso == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "tf21_c_horaaviso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf21_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "tf21_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf21_t_obs == null ){ 
       $this->erro_sql = " Campo Avisado / Obs nao Informado.";
       $this->erro_campo = "tf21_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf21_i_codigo == "" || $tf21_i_codigo == null ){
       $result = db_query("select nextval('tfd_avisopaciente_tf21_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_avisopaciente_tf21_i_codigo_seq do campo: tf21_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf21_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_avisopaciente_tf21_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf21_i_codigo)){
         $this->erro_sql = " Campo tf21_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf21_i_codigo = $tf21_i_codigo; 
       }
     }
     if(($this->tf21_i_codigo == null) || ($this->tf21_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf21_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_avisopaciente(
                                       tf21_i_codigo 
                                      ,tf21_i_pedidotfd 
                                      ,tf21_i_formaaviso 
                                      ,tf21_d_dataaviso 
                                      ,tf21_c_horaaviso 
                                      ,tf21_i_login 
                                      ,tf21_t_obs 
                       )
                values (
                                $this->tf21_i_codigo 
                               ,$this->tf21_i_pedidotfd 
                               ,$this->tf21_i_formaaviso 
                               ,".($this->tf21_d_dataaviso == "null" || $this->tf21_d_dataaviso == ""?"null":"'".$this->tf21_d_dataaviso."'")." 
                               ,'$this->tf21_c_horaaviso' 
                               ,$this->tf21_i_login 
                               ,'$this->tf21_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->tf21_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->tf21_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf21_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf21_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16425,'$this->tf21_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2877,16425,'','".AddSlashes(pg_result($resaco,0,'tf21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2877,16426,'','".AddSlashes(pg_result($resaco,0,'tf21_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2877,16427,'','".AddSlashes(pg_result($resaco,0,'tf21_i_formaaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2877,16428,'','".AddSlashes(pg_result($resaco,0,'tf21_d_dataaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2877,16429,'','".AddSlashes(pg_result($resaco,0,'tf21_c_horaaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2877,16431,'','".AddSlashes(pg_result($resaco,0,'tf21_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2877,16430,'','".AddSlashes(pg_result($resaco,0,'tf21_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf21_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_avisopaciente set ";
     $virgula = "";
     if(trim($this->tf21_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_codigo"])){ 
       $sql  .= $virgula." tf21_i_codigo = $this->tf21_i_codigo ";
       $virgula = ",";
       if(trim($this->tf21_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "tf21_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf21_i_pedidotfd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_pedidotfd"])){ 
       $sql  .= $virgula." tf21_i_pedidotfd = $this->tf21_i_pedidotfd ";
       $virgula = ",";
       if(trim($this->tf21_i_pedidotfd) == null ){ 
         $this->erro_sql = " Campo Pedido nao Informado.";
         $this->erro_campo = "tf21_i_pedidotfd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf21_i_formaaviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_formaaviso"])){ 
       $sql  .= $virgula." tf21_i_formaaviso = $this->tf21_i_formaaviso ";
       $virgula = ",";
       if(trim($this->tf21_i_formaaviso) == null ){ 
         $this->erro_sql = " Campo Forma de Aviso nao Informado.";
         $this->erro_campo = "tf21_i_formaaviso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf21_d_dataaviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso_dia"] !="") ){ 
       $sql  .= $virgula." tf21_d_dataaviso = '$this->tf21_d_dataaviso' ";
       $virgula = ",";
       if(trim($this->tf21_d_dataaviso) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tf21_d_dataaviso_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso_dia"])){ 
         $sql  .= $virgula." tf21_d_dataaviso = null ";
         $virgula = ",";
         if(trim($this->tf21_d_dataaviso) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tf21_d_dataaviso_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf21_c_horaaviso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_c_horaaviso"])){ 
       $sql  .= $virgula." tf21_c_horaaviso = '$this->tf21_c_horaaviso' ";
       $virgula = ",";
       if(trim($this->tf21_c_horaaviso) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "tf21_c_horaaviso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf21_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_login"])){ 
       $sql  .= $virgula." tf21_i_login = $this->tf21_i_login ";
       $virgula = ",";
       if(trim($this->tf21_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "tf21_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf21_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf21_t_obs"])){ 
       $sql  .= $virgula." tf21_t_obs = '$this->tf21_t_obs' ";
       $virgula = ",";
       if(trim($this->tf21_t_obs) == null ){ 
         $this->erro_sql = " Campo Avisado / Obs nao Informado.";
         $this->erro_campo = "tf21_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf21_i_codigo!=null){
       $sql .= " tf21_i_codigo = $this->tf21_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf21_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16425,'$this->tf21_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_codigo"]) || $this->tf21_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2877,16425,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_i_codigo'))."','$this->tf21_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_pedidotfd"]) || $this->tf21_i_pedidotfd != "")
           $resac = db_query("insert into db_acount values($acount,2877,16426,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_i_pedidotfd'))."','$this->tf21_i_pedidotfd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_formaaviso"]) || $this->tf21_i_formaaviso != "")
           $resac = db_query("insert into db_acount values($acount,2877,16427,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_i_formaaviso'))."','$this->tf21_i_formaaviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_d_dataaviso"]) || $this->tf21_d_dataaviso != "")
           $resac = db_query("insert into db_acount values($acount,2877,16428,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_d_dataaviso'))."','$this->tf21_d_dataaviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_c_horaaviso"]) || $this->tf21_c_horaaviso != "")
           $resac = db_query("insert into db_acount values($acount,2877,16429,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_c_horaaviso'))."','$this->tf21_c_horaaviso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_i_login"]) || $this->tf21_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2877,16431,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_i_login'))."','$this->tf21_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf21_t_obs"]) || $this->tf21_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2877,16430,'".AddSlashes(pg_result($resaco,$conresaco,'tf21_t_obs'))."','$this->tf21_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf21_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf21_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf21_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf21_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf21_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16425,'$tf21_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2877,16425,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2877,16426,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2877,16427,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_i_formaaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2877,16428,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_d_dataaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2877,16429,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_c_horaaviso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2877,16431,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2877,16430,'','".AddSlashes(pg_result($resaco,$iresaco,'tf21_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_avisopaciente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf21_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf21_i_codigo = $tf21_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf21_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf21_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf21_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_avisopaciente";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_avisopaciente ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_avisopaciente.tf21_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_avisopaciente.tf21_i_pedidotfd";
     $sql .= "      inner join tfd_formaaviso  on  tfd_formaaviso.tf20_i_codigo = tfd_avisopaciente.tf21_i_formaaviso";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf21_i_codigo!=null ){
         $sql2 .= " where tfd_avisopaciente.tf21_i_codigo = $tf21_i_codigo "; 
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
   function sql_query_file ( $tf21_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_avisopaciente ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf21_i_codigo!=null ){
         $sql2 .= " where tfd_avisopaciente.tf21_i_codigo = $tf21_i_codigo "; 
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
   function sql_query2( $tf21_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from tfd_avisopaciente ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_avisopaciente.tf21_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_avisopaciente.tf21_i_pedidotfd";
     $sql .= "      inner join tfd_formaaviso  on  tfd_formaaviso.tf20_i_codigo = tfd_avisopaciente.tf21_i_formaaviso";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf21_i_codigo!=null ){
         $sql2 .= " where tfd_avisopaciente.tf21_i_codigo = $tf21_i_codigo "; 
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