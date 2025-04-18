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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_estoque
class cl_mer_estoque { 
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
   var $me18_i_codigo = 0; 
   var $me18_f_quant = 0; 
   var $me18_f_valor = 0; 
   var $me18_i_calendario = 0; 
   var $me18_i_escola = 0; 
   var $me18_i_codmater = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me18_i_codigo = int4 = C�digo 
                 me18_f_quant = float4 = Quantidade 
                 me18_f_valor = float4 = Valor 
                 me18_i_calendario = int4 = Calend�rio 
                 me18_i_escola = int4 = Escola 
                 me18_i_codmater = int4 = Item 
                 ";
   //funcao construtor da classe 
   function cl_mer_estoque() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_estoque"); 
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
       $this->me18_i_codigo = ($this->me18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_i_codigo"]:$this->me18_i_codigo);
       $this->me18_f_quant = ($this->me18_f_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_f_quant"]:$this->me18_f_quant);
       $this->me18_f_valor = ($this->me18_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_f_valor"]:$this->me18_f_valor);
       $this->me18_i_calendario = ($this->me18_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_i_calendario"]:$this->me18_i_calendario);
       $this->me18_i_escola = ($this->me18_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_i_escola"]:$this->me18_i_escola);
       $this->me18_i_codmater = ($this->me18_i_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_i_codmater"]:$this->me18_i_codmater);
     }else{
       $this->me18_i_codigo = ($this->me18_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me18_i_codigo"]:$this->me18_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me18_i_codigo){ 
      $this->atualizacampos();
     if($this->me18_f_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "me18_f_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me18_f_valor == null ){ 
       $this->me18_f_valor = "0";
     }
     if($this->me18_i_calendario == null ){ 
       $this->erro_sql = " Campo Calend�rio nao Informado.";
       $this->erro_campo = "me18_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me18_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "me18_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me18_i_codmater == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "me18_i_codmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me18_i_codigo == "" || $me18_i_codigo == null ){
       $result = db_query("select nextval('merestoque_me18_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: merestoque_me18_codigo_seq do campo: me18_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me18_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from merestoque_me18_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me18_i_codigo)){
         $this->erro_sql = " Campo me18_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me18_i_codigo = $me18_i_codigo; 
       }
     }
     if(($this->me18_i_codigo == null) || ($this->me18_i_codigo == "") ){ 
       $this->erro_sql = " Campo me18_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_estoque(
                                       me18_i_codigo 
                                      ,me18_f_quant 
                                      ,me18_f_valor 
                                      ,me18_i_calendario 
                                      ,me18_i_escola 
                                      ,me18_i_codmater 
                       )
                values (
                                $this->me18_i_codigo 
                               ,$this->me18_f_quant 
                               ,$this->me18_f_valor 
                               ,$this->me18_i_calendario 
                               ,$this->me18_i_escola 
                               ,$this->me18_i_codmater 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_estoque ($this->me18_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_estoque j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_estoque ($this->me18_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me18_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me18_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12734,'$this->me18_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2228,12734,'','".AddSlashes(pg_result($resaco,0,'me18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2228,12735,'','".AddSlashes(pg_result($resaco,0,'me18_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2228,12736,'','".AddSlashes(pg_result($resaco,0,'me18_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2228,12740,'','".AddSlashes(pg_result($resaco,0,'me18_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2228,12738,'','".AddSlashes(pg_result($resaco,0,'me18_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2228,12737,'','".AddSlashes(pg_result($resaco,0,'me18_i_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me18_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_estoque set ";
     $virgula = "";
     if(trim($this->me18_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me18_i_codigo"])){ 
       $sql  .= $virgula." me18_i_codigo = $this->me18_i_codigo ";
       $virgula = ",";
       if(trim($this->me18_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "me18_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me18_f_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me18_f_quant"])){ 
       $sql  .= $virgula." me18_f_quant = $this->me18_f_quant ";
       $virgula = ",";
       if(trim($this->me18_f_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "me18_f_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me18_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me18_f_valor"])){ 
        if(trim($this->me18_f_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["me18_f_valor"])){ 
           $this->me18_f_valor = "0" ; 
        } 
       $sql  .= $virgula." me18_f_valor = $this->me18_f_valor ";
       $virgula = ",";
     }
     if(trim($this->me18_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me18_i_calendario"])){ 
       $sql  .= $virgula." me18_i_calendario = $this->me18_i_calendario ";
       $virgula = ",";
       if(trim($this->me18_i_calendario) == null ){ 
         $this->erro_sql = " Campo Calend�rio nao Informado.";
         $this->erro_campo = "me18_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me18_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me18_i_escola"])){ 
       $sql  .= $virgula." me18_i_escola = $this->me18_i_escola ";
       $virgula = ",";
       if(trim($this->me18_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "me18_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me18_i_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me18_i_codmater"])){ 
       $sql  .= $virgula." me18_i_codmater = $this->me18_i_codmater ";
       $virgula = ",";
       if(trim($this->me18_i_codmater) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "me18_i_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me18_i_codigo!=null){
       $sql .= " me18_i_codigo = $this->me18_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me18_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12734,'$this->me18_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me18_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2228,12734,'".AddSlashes(pg_result($resaco,$conresaco,'me18_i_codigo'))."','$this->me18_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me18_f_quant"]))
           $resac = db_query("insert into db_acount values($acount,2228,12735,'".AddSlashes(pg_result($resaco,$conresaco,'me18_f_quant'))."','$this->me18_f_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me18_f_valor"]))
           $resac = db_query("insert into db_acount values($acount,2228,12736,'".AddSlashes(pg_result($resaco,$conresaco,'me18_f_valor'))."','$this->me18_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me18_i_calendario"]))
           $resac = db_query("insert into db_acount values($acount,2228,12740,'".AddSlashes(pg_result($resaco,$conresaco,'me18_i_calendario'))."','$this->me18_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me18_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,2228,12738,'".AddSlashes(pg_result($resaco,$conresaco,'me18_i_escola'))."','$this->me18_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me18_i_codmater"]))
           $resac = db_query("insert into db_acount values($acount,2228,12737,'".AddSlashes(pg_result($resaco,$conresaco,'me18_i_codmater'))."','$this->me18_i_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_estoque nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me18_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_estoque nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me18_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me18_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me18_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me18_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12734,'$me18_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2228,12734,'','".AddSlashes(pg_result($resaco,$iresaco,'me18_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2228,12735,'','".AddSlashes(pg_result($resaco,$iresaco,'me18_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2228,12736,'','".AddSlashes(pg_result($resaco,$iresaco,'me18_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2228,12740,'','".AddSlashes(pg_result($resaco,$iresaco,'me18_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2228,12738,'','".AddSlashes(pg_result($resaco,$iresaco,'me18_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2228,12737,'','".AddSlashes(pg_result($resaco,$iresaco,'me18_i_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_estoque
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me18_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me18_i_codigo = $me18_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_estoque nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me18_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_estoque nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me18_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me18_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_estoque";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_estoque ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = mer_estoque.me18_i_codmater";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = mer_estoque.me18_i_escola";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = mer_estoque.me18_i_calendario";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql2 = "";
     if($dbwhere==""){
       if($me18_i_codigo!=null ){
         $sql2 .= " where mer_estoque.me18_i_codigo = $me18_i_codigo "; 
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
   function sql_query_file ( $me18_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_estoque ";
     $sql2 = "";
     if($dbwhere==""){
       if($me18_i_codigo!=null ){
         $sql2 .= " where mer_estoque.me18_i_codigo = $me18_i_codigo "; 
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