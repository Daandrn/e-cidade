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

//MODULO: Farmacia
///CLASSE DA ENTIDADE far_devolucao
class cl_far_devolucao { 
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
   var $fa22_i_codigo = 0; 
   var $fa22_i_cgsund = 0; 
   var $fa22_c_hora = 0; 
   var $fa22_i_login = 0; 
   var $fa22_d_data_dia = null; 
   var $fa22_d_data_mes = null; 
   var $fa22_d_data_ano = null; 
   var $fa22_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa22_i_codigo = int4 = C�digo 
                 fa22_i_cgsund = int4 = CGS 
                 fa22_c_hora = int4 = Hora 
                 fa22_i_login = int4 = Login 
                 fa22_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_far_devolucao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_devolucao"); 
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
       $this->fa22_i_codigo = ($this->fa22_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_i_codigo"]:$this->fa22_i_codigo);
       $this->fa22_i_cgsund = ($this->fa22_i_cgsund == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_i_cgsund"]:$this->fa22_i_cgsund);
       $this->fa22_c_hora = ($this->fa22_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_c_hora"]:$this->fa22_c_hora);
       $this->fa22_i_login = ($this->fa22_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_i_login"]:$this->fa22_i_login);
       if($this->fa22_d_data == ""){
         $this->fa22_d_data_dia = ($this->fa22_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_d_data_dia"]:$this->fa22_d_data_dia);
         $this->fa22_d_data_mes = ($this->fa22_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_d_data_mes"]:$this->fa22_d_data_mes);
         $this->fa22_d_data_ano = ($this->fa22_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_d_data_ano"]:$this->fa22_d_data_ano);
         if($this->fa22_d_data_dia != ""){
            $this->fa22_d_data = $this->fa22_d_data_ano."-".$this->fa22_d_data_mes."-".$this->fa22_d_data_dia;
         }
       }
     }else{
       $this->fa22_i_codigo = ($this->fa22_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa22_i_codigo"]:$this->fa22_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa22_i_codigo){ 
      $this->atualizacampos();
     if($this->fa22_i_cgsund == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "fa22_i_cgsund";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa22_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "fa22_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa22_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "fa22_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa22_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "fa22_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa22_i_codigo == "" || $fa22_i_codigo == null ){
       $result = db_query("select nextval('far_devolucao_fa22_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_devolucao_fa22_codigo_seq do campo: fa22_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa22_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_devolucao_fa22_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa22_i_codigo)){
         $this->erro_sql = " Campo fa22_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa22_i_codigo = $fa22_i_codigo; 
       }
     }
     if(($this->fa22_i_codigo == null) || ($this->fa22_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa22_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_devolucao(
                                       fa22_i_codigo 
                                      ,fa22_i_cgsund 
                                      ,fa22_c_hora
                                      ,fa22_i_login 
                                      ,fa22_d_data 
                       )
                values (
                                $this->fa22_i_codigo 
                               ,$this->fa22_i_cgsund 
                               ,'$this->fa22_c_hora' 
                               ,$this->fa22_i_login 
                               ,".($this->fa22_d_data == "null" || $this->fa22_d_data == ""?"null":"'".$this->fa22_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_devolucao ($this->fa22_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_devolucao j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_devolucao ($this->fa22_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa22_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa22_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14040,'$this->fa22_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2470,14040,'','".AddSlashes(pg_result($resaco,0,'fa22_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2470,14041,'','".AddSlashes(pg_result($resaco,0,'fa22_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2470,14043,'','".AddSlashes(pg_result($resaco,0,'fa22_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2470,14044,'','".AddSlashes(pg_result($resaco,0,'fa22_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2470,14042,'','".AddSlashes(pg_result($resaco,0,'fa22_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa22_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_devolucao set ";
     $virgula = "";
     if(trim($this->fa22_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa22_i_codigo"])){ 
       $sql  .= $virgula." fa22_i_codigo = $this->fa22_i_codigo ";
       $virgula = ",";
       if(trim($this->fa22_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "fa22_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa22_i_cgsund)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa22_i_cgsund"])){ 
       $sql  .= $virgula." fa22_i_cgsund = $this->fa22_i_cgsund ";
       $virgula = ",";
       if(trim($this->fa22_i_cgsund) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "fa22_i_cgsund";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa22_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa22_c_hora"])){ 
       $sql  .= $virgula." fa22_c_hora = $this->fa22_c_hora ";
       $virgula = ",";
       if(trim($this->fa22_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "fa22_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa22_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa22_i_login"])){ 
       $sql  .= $virgula." fa22_i_login = $this->fa22_i_login ";
       $virgula = ",";
       if(trim($this->fa22_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "fa22_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa22_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa22_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa22_d_data_dia"] !="") ){ 
       $sql  .= $virgula." fa22_d_data = '$this->fa22_d_data' ";
       $virgula = ",";
       if(trim($this->fa22_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "fa22_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa22_d_data_dia"])){ 
         $sql  .= $virgula." fa22_d_data = null ";
         $virgula = ",";
         if(trim($this->fa22_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "fa22_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($fa22_i_codigo!=null){
       $sql .= " fa22_i_codigo = $this->fa22_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa22_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14040,'$this->fa22_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa22_i_codigo"]) || $this->fa22_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2470,14040,'".AddSlashes(pg_result($resaco,$conresaco,'fa22_i_codigo'))."','$this->fa22_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa22_i_cgsund"]) || $this->fa22_i_cgsund != "")
           $resac = db_query("insert into db_acount values($acount,2470,14041,'".AddSlashes(pg_result($resaco,$conresaco,'fa22_i_cgsund'))."','$this->fa22_i_cgsund',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa22_c_hora"]) || $this->fa22_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2470,14043,'".AddSlashes(pg_result($resaco,$conresaco,'fa22_c_hora'))."','$this->fa22_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa22_i_login"]) || $this->fa22_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2470,14044,'".AddSlashes(pg_result($resaco,$conresaco,'fa22_i_login'))."','$this->fa22_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa22_d_data"]) || $this->fa22_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2470,14042,'".AddSlashes(pg_result($resaco,$conresaco,'fa22_d_data'))."','$this->fa22_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_devolucao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa22_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_devolucao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa22_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa22_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa22_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa22_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14040,'$fa22_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2470,14040,'','".AddSlashes(pg_result($resaco,$iresaco,'fa22_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2470,14041,'','".AddSlashes(pg_result($resaco,$iresaco,'fa22_i_cgsund'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2470,14043,'','".AddSlashes(pg_result($resaco,$iresaco,'fa22_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2470,14044,'','".AddSlashes(pg_result($resaco,$iresaco,'fa22_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2470,14042,'','".AddSlashes(pg_result($resaco,$iresaco,'fa22_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_devolucao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa22_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa22_i_codigo = $fa22_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_devolucao nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa22_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_devolucao nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa22_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa22_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_devolucao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
 
   function sql_query ( $fa22_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_devolucao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_devolucao.fa22_i_login";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = far_devolucao.fa22_i_cgsund";
     $sql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sql .= "      inner join cgs  as a on   a.z01_i_numcgs = cgs_und.z01_i_cgsund";

     $sql2 = "";

     if($dbwhere==""){
       if($fa22_i_codigo!=null ){
         $sql2 .= " where far_devolucao.fa22_i_codigo = $fa22_i_codigo "; 
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
   function sql_query_file ( $fa22_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_devolucao ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa22_i_codigo!=null ){
         $sql2 .= " where far_devolucao.fa22_i_codigo = $fa22_i_codigo "; 
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
  
   /*
    *Function utilizada no arquivo far2_devolucao001.php, na rotina de devolucao de medicamento
    */
   function sql_query_dev ( $fa22_i_codigo = null, $sCampos = "*", $sOrdem = null, $sDbwhere = ""){
     
     $sSql = "select ";

     if ($sCampos != "*" ) {

       $sCamposSql = explode("#",$sCampos);
       $sVirgula = "";

       for ($i = 0; $i < sizeof($sCamposSql); $i++){

         $sSql .= $sVirgula.$sCamposSql[$i];
         $sVirgula = ",";

       }

     }else{
       $sSql .= $sCampos;
     }
     $sSql .= " from far_devolucao ";
     $sSql .= "      left join far_devolucaomed on far_devolucaomed.fa23_i_devolucao = far_devolucao.fa22_i_codigo";
     $sSql .= "      inner join db_usuarios       on  db_usuarios.id_usuario         = far_devolucao.fa22_i_login";
     $sSql .= "      inner join cgs_und           on  cgs_und.z01_i_cgsund           = far_devolucao.fa22_i_cgsund";
     $sSql .= "      left  join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
     $sSql .= "      inner join cgs  as a         on   a.z01_i_numcgs                = cgs_und.z01_i_cgsund";
     $sSql .= "      inner join far_retiradaitens  on  far_retiradaitens.fa06_i_codigo = far_devolucaomed.fa23_i_retiradaitens";
     $sSql .= "      inner join far_matersaude     on  far_matersaude.fa01_i_codigo    = far_retiradaitens.fa06_i_matersaude";
     $sSql .= "      inner join matmater           on  matmater.m60_codmater           = far_matersaude.fa01_i_codmater";
     $sSql .= "      inner join matunid            on  matunid.m61_codmatunid          = matmater.m60_codmatunid";

     $sSql2 = "";

     if($sDbwhere == "") {

       if ($fa22_i_codigo != null ) {
         $sSql2 .= " where far_devolucao.fa22_i_codigo = $fa22_i_codigo ";
       }
     }else if($sDbwhere != ""){
       $sSql2 = " where $sDbwhere";
     }
     $sSql .= $sSql2;

     if ($sOrdem != null ){
       $sSql .= " order by ";
       $sCamposSql = explode("#",$sOrdem);
       $sVirgula = "";

       for ($i = 0;$i < sizeof($sCamposSql); $i++) {

         $sSql .= $sVirgula.$sCamposSql[$i];
         $sVirgula = ",";

       }
     }
     return $sSql;
  }


}
?>