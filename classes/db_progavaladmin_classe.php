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

//MODULO: educa��o
//CLASSE DA ENTIDADE progavaladmin
class cl_progavaladmin { 
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
   var $ed116_i_codigo = 0; 
   var $ed116_i_progmatricula = 0; 
   var $ed116_i_questaoaval = 0; 
   var $ed116_i_opcaoquestao = 0; 
   var $ed116_i_usuario = 0; 
   var $ed116_d_data_dia = null; 
   var $ed116_d_data_mes = null; 
   var $ed116_d_data_ano = null; 
   var $ed116_d_data = null; 
   var $ed116_c_tipo = null; 
   var $ed116_i_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed116_i_codigo = int8 = C�digo 
                 ed116_i_progmatricula = int8 = Matr�cula 
                 ed116_i_questaoaval = int8 = Quest�o 
                 ed116_i_opcaoquestao = int8 = Resposta 
                 ed116_i_usuario = int8 = Usu�rio 
                 ed116_d_data = date = Data 
                 ed116_c_tipo = char(1) = Tipo de Avalia��o 
                 ed116_i_ano = int4 = Ano Referente 
                 ";
   //funcao construtor da classe 
   function cl_progavaladmin() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progavaladmin"); 
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
       $this->ed116_i_codigo = ($this->ed116_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_codigo"]:$this->ed116_i_codigo);
       $this->ed116_i_progmatricula = ($this->ed116_i_progmatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_progmatricula"]:$this->ed116_i_progmatricula);
       $this->ed116_i_questaoaval = ($this->ed116_i_questaoaval == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_questaoaval"]:$this->ed116_i_questaoaval);
       $this->ed116_i_opcaoquestao = ($this->ed116_i_opcaoquestao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_opcaoquestao"]:$this->ed116_i_opcaoquestao);
       $this->ed116_i_usuario = ($this->ed116_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_usuario"]:$this->ed116_i_usuario);
       if($this->ed116_d_data == ""){
         $this->ed116_d_data_dia = ($this->ed116_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_d_data_dia"]:$this->ed116_d_data_dia);
         $this->ed116_d_data_mes = ($this->ed116_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_d_data_mes"]:$this->ed116_d_data_mes);
         $this->ed116_d_data_ano = ($this->ed116_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_d_data_ano"]:$this->ed116_d_data_ano);
         if($this->ed116_d_data_dia != ""){
            $this->ed116_d_data = $this->ed116_d_data_ano."-".$this->ed116_d_data_mes."-".$this->ed116_d_data_dia;
         }
       }
       $this->ed116_c_tipo = ($this->ed116_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_c_tipo"]:$this->ed116_c_tipo);
       $this->ed116_i_ano = ($this->ed116_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_ano"]:$this->ed116_i_ano);
     }else{
       $this->ed116_i_codigo = ($this->ed116_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed116_i_codigo"]:$this->ed116_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed116_i_codigo){ 
      $this->atualizacampos();
     if($this->ed116_i_progmatricula == null ){ 
       $this->erro_sql = " Campo Matr�cula nao Informado.";
       $this->erro_campo = "ed116_i_progmatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed116_i_questaoaval == null ){ 
       $this->erro_sql = " Campo Quest�o nao Informado.";
       $this->erro_campo = "ed116_i_questaoaval";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed116_i_opcaoquestao == null ){ 
       $this->erro_sql = " Campo Resposta nao Informado.";
       $this->erro_campo = "ed116_i_opcaoquestao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed116_i_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "ed116_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed116_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed116_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed116_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Avalia��o nao Informado.";
       $this->erro_campo = "ed116_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed116_i_ano == null ){ 
       $this->erro_sql = " Campo Ano Referente nao Informado.";
       $this->erro_campo = "ed116_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed116_i_codigo == "" || $ed116_i_codigo == null ){
       $result = db_query("select nextval('progavaladmin_ed116_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progavaladmin_ed116_i_codigo_seq do campo: ed116_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed116_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progavaladmin_ed116_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed116_i_codigo)){
         $this->erro_sql = " Campo ed116_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed116_i_codigo = $ed116_i_codigo; 
       }
     }
     if(($this->ed116_i_codigo == null) || ($this->ed116_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed116_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progavaladmin(
                                       ed116_i_codigo 
                                      ,ed116_i_progmatricula 
                                      ,ed116_i_questaoaval 
                                      ,ed116_i_opcaoquestao 
                                      ,ed116_i_usuario 
                                      ,ed116_d_data 
                                      ,ed116_c_tipo 
                                      ,ed116_i_ano 
                       )
                values (
                                $this->ed116_i_codigo 
                               ,$this->ed116_i_progmatricula 
                               ,$this->ed116_i_questaoaval 
                               ,$this->ed116_i_opcaoquestao 
                               ,$this->ed116_i_usuario 
                               ,".($this->ed116_d_data == "null" || $this->ed116_d_data == ""?"null":"'".$this->ed116_d_data."'")." 
                               ,'$this->ed116_c_tipo' 
                               ,$this->ed116_i_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avalia��o Administrativa do Professor ($this->ed116_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avalia��o Administrativa do Professor j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avalia��o Administrativa do Professor ($this->ed116_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed116_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed116_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009166,'$this->ed116_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010182,1009166,'','".AddSlashes(pg_result($resaco,0,'ed116_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009167,'','".AddSlashes(pg_result($resaco,0,'ed116_i_progmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009168,'','".AddSlashes(pg_result($resaco,0,'ed116_i_questaoaval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009169,'','".AddSlashes(pg_result($resaco,0,'ed116_i_opcaoquestao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009170,'','".AddSlashes(pg_result($resaco,0,'ed116_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009171,'','".AddSlashes(pg_result($resaco,0,'ed116_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009172,'','".AddSlashes(pg_result($resaco,0,'ed116_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010182,1009173,'','".AddSlashes(pg_result($resaco,0,'ed116_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed116_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update progavaladmin set ";
     $virgula = "";
     if(trim($this->ed116_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_codigo"])){ 
       $sql  .= $virgula." ed116_i_codigo = $this->ed116_i_codigo ";
       $virgula = ",";
       if(trim($this->ed116_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ed116_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed116_i_progmatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_progmatricula"])){ 
       $sql  .= $virgula." ed116_i_progmatricula = $this->ed116_i_progmatricula ";
       $virgula = ",";
       if(trim($this->ed116_i_progmatricula) == null ){ 
         $this->erro_sql = " Campo Matr�cula nao Informado.";
         $this->erro_campo = "ed116_i_progmatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed116_i_questaoaval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_questaoaval"])){ 
       $sql  .= $virgula." ed116_i_questaoaval = $this->ed116_i_questaoaval ";
       $virgula = ",";
       if(trim($this->ed116_i_questaoaval) == null ){ 
         $this->erro_sql = " Campo Quest�o nao Informado.";
         $this->erro_campo = "ed116_i_questaoaval";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed116_i_opcaoquestao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_opcaoquestao"])){ 
       $sql  .= $virgula." ed116_i_opcaoquestao = $this->ed116_i_opcaoquestao ";
       $virgula = ",";
       if(trim($this->ed116_i_opcaoquestao) == null ){ 
         $this->erro_sql = " Campo Resposta nao Informado.";
         $this->erro_campo = "ed116_i_opcaoquestao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed116_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_usuario"])){ 
       $sql  .= $virgula." ed116_i_usuario = $this->ed116_i_usuario ";
       $virgula = ",";
       if(trim($this->ed116_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "ed116_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed116_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed116_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed116_d_data = '$this->ed116_d_data' ";
       $virgula = ",";
       if(trim($this->ed116_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed116_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_d_data_dia"])){ 
         $sql  .= $virgula." ed116_d_data = null ";
         $virgula = ",";
         if(trim($this->ed116_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed116_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed116_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_c_tipo"])){ 
       $sql  .= $virgula." ed116_c_tipo = '$this->ed116_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed116_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Avalia��o nao Informado.";
         $this->erro_campo = "ed116_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed116_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_ano"])){ 
       $sql  .= $virgula." ed116_i_ano = $this->ed116_i_ano ";
       $virgula = ",";
       if(trim($this->ed116_i_ano) == null ){ 
         $this->erro_sql = " Campo Ano Referente nao Informado.";
         $this->erro_campo = "ed116_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed116_i_codigo!=null){
       $sql .= " ed116_i_codigo = $this->ed116_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed116_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009166,'$this->ed116_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009166,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_i_codigo'))."','$this->ed116_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_progmatricula"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009167,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_i_progmatricula'))."','$this->ed116_i_progmatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_questaoaval"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009168,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_i_questaoaval'))."','$this->ed116_i_questaoaval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_opcaoquestao"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009169,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_i_opcaoquestao'))."','$this->ed116_i_opcaoquestao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009170,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_i_usuario'))."','$this->ed116_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009171,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_d_data'))."','$this->ed116_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009172,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_c_tipo'))."','$this->ed116_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed116_i_ano"]))
           $resac = db_query("insert into db_acount values($acount,1010182,1009173,'".AddSlashes(pg_result($resaco,$conresaco,'ed116_i_ano'))."','$this->ed116_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o Administrativa do Professor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed116_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o Administrativa do Professor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed116_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed116_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed116_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed116_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009166,'$ed116_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010182,1009166,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009167,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_i_progmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009168,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_i_questaoaval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009169,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_i_opcaoquestao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009170,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009171,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009172,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010182,1009173,'','".AddSlashes(pg_result($resaco,$iresaco,'ed116_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progavaladmin
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed116_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed116_i_codigo = $ed116_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avalia��o Administrativa do Professor nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed116_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avalia��o Administrativa do Professor nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed116_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed116_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:progavaladmin";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed116_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progavaladmin ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = progavaladmin.ed116_i_usuario";
     $sql .= "      inner join progmatricula  on  progmatricula.ed112_i_codigo = progavaladmin.ed116_i_progmatricula";
     $sql .= "      inner join progclasse  on  progclasse.ed107_i_codigo = progmatricula.ed112_i_progclasse";
     $sql .= "      inner join opcaoquestao  on  opcaoquestao.ed106_i_codigo = progavaladmin.ed116_i_opcaoquestao";
     $sql .= "      inner join questaoaval  on  questaoaval.ed108_i_codigo = progavaladmin.ed116_i_questaoaval";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = progmatricula.ed112_i_rhpessoal";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($ed116_i_codigo!=null ){
         $sql2 .= " where progavaladmin.ed116_i_codigo = $ed116_i_codigo "; 
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
   function sql_query_file ( $ed116_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progavaladmin ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed116_i_codigo!=null ){
         $sql2 .= " where progavaladmin.ed116_i_codigo = $ed116_i_codigo "; 
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