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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_cardapiodia
class cl_mer_cardapiodia { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me12_i_codigo        = 0; 
   var $me12_i_cardapio        = 0; 
   var $me12_i_tprefeicao        = 0; 
   var $me12_d_data_dia    = null; 
   var $me12_d_data_mes    = null; 
   var $me12_d_data_ano    = null; 
   var $me12_d_data        = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me12_i_codigo = int4 = C�digo 
                 me12_i_cardapio = int4 = Card�pio 
                 me12_i_tprefeicao = int4 = Tipo Refei��o 
                 me12_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_mer_cardapiodia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_cardapiodia"); 
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
       $this->me12_i_codigo = ($this->me12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_i_codigo"]:$this->me12_i_codigo);
       $this->me12_i_cardapio = ($this->me12_i_cardapio == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_i_cardapio"]:$this->me12_i_cardapio);
       $this->me12_i_tprefeicao = ($this->me12_i_tprefeicao == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_i_tprefeicao"]:$this->me12_i_tprefeicao);
       if($this->me12_d_data == ""){
         $this->me12_d_data_dia = ($this->me12_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_d_data_dia"]:$this->me12_d_data_dia);
         $this->me12_d_data_mes = ($this->me12_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_d_data_mes"]:$this->me12_d_data_mes);
         $this->me12_d_data_ano = ($this->me12_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_d_data_ano"]:$this->me12_d_data_ano);
         if($this->me12_d_data_dia != ""){
            $this->me12_d_data = $this->me12_d_data_ano."-".$this->me12_d_data_mes."-".$this->me12_d_data_dia;
         }
       }
     }else{
       $this->me12_i_codigo = ($this->me12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me12_i_codigo"]:$this->me12_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me12_i_codigo){ 
      $this->atualizacampos();
     if($this->me12_i_cardapio == null ){ 
       $this->erro_sql = " Campo Card�pio nao Informado.";
       $this->erro_campo = "me12_i_cardapio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me12_i_tprefeicao == null ){ 
       $this->erro_sql = " Campo Tipo Refei��o nao Informado.";
       $this->erro_campo = "me12_i_tprefeicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me12_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "me12_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me12_i_codigo == "" || $me12_i_codigo == null ){
       $result = db_query("select nextval('mercardapiodia_me12_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mercardapiodia_me12_codigo_seq do campo: me12_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me12_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mercardapiodia_me12_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me12_i_codigo)){
         $this->erro_sql = " Campo me12_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me12_i_codigo = $me12_i_codigo; 
       }
     }
     if(($this->me12_i_codigo == null) || ($this->me12_i_codigo == "") ){ 
       $this->erro_sql = " Campo me12_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_cardapiodia(
                                       me12_i_codigo 
                                      ,me12_i_cardapio 
                                      ,me12_i_tprefeicao 
                                      ,me12_d_data  
                       )
                values (
                                $this->me12_i_codigo 
                               ,$this->me12_i_cardapio 
                               ,$this->me12_i_tprefeicao 
                               ,".($this->me12_d_data == "null" || $this->me12_d_data == ""?"null":"'".$this->me12_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_cardapiodia ($this->me12_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_cardapiodia j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_cardapiodia ($this->me12_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me12_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me12_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12755,'$this->me12_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2233,12755,'','".AddSlashes(pg_result($resaco,0,'me12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2233,12756,'','".AddSlashes(pg_result($resaco,0,'me12_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2233,13284,'','".AddSlashes(pg_result($resaco,0,'me12_i_tprefeicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2233,14156,'','".AddSlashes(pg_result($resaco,0,'me12_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me12_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_cardapiodia set ";
     $virgula = "";
     if(trim($this->me12_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me12_i_codigo"])){ 
       $sql  .= $virgula." me12_i_codigo = $this->me12_i_codigo ";
       $virgula = ",";
       if(trim($this->me12_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "me12_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me12_i_cardapio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me12_i_cardapio"])){ 
       $sql  .= $virgula." me12_i_cardapio = $this->me12_i_cardapio ";
       $virgula = ",";
       if(trim($this->me12_i_cardapio) == null ){ 
         $this->erro_sql = " Campo Card�pio nao Informado.";
         $this->erro_campo = "me12_i_cardapio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me12_i_tprefeicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me12_i_tprefeicao"])){ 
       $sql  .= $virgula." me12_i_tprefeicao = $this->me12_i_tprefeicao ";
       $virgula = ",";
       if(trim($this->me12_i_tprefeicao) == null ){ 
         $this->erro_sql = " Campo Tipo Refei��o nao Informado.";
         $this->erro_campo = "me12_i_tprefeicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me12_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me12_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["me12_d_data_dia"] !="") ){ 
       $sql  .= $virgula." me12_d_data = '$this->me12_d_data' ";
       $virgula = ",";
       if(trim($this->me12_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "me12_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["me12_d_data_dia"])){ 
         $sql  .= $virgula." me12_d_data = null ";
         $virgula = ",";
         if(trim($this->me12_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "me12_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($me12_i_codigo!=null){
       $sql .= " me12_i_codigo = $this->me12_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me12_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12755,'$this->me12_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me12_i_codigo"]) || $this->me12_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2233,12755,'".AddSlashes(pg_result($resaco,$conresaco,'me12_i_codigo'))."','$this->me12_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me12_i_cardapio"]) || $this->me12_i_cardapio != "")
           $resac = db_query("insert into db_acount values($acount,2233,12756,'".AddSlashes(pg_result($resaco,$conresaco,'me12_i_cardapio'))."','$this->me12_i_cardapio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me12_i_tprefeicao"]) || $this->me12_i_tprefeicao != "")
           $resac = db_query("insert into db_acount values($acount,2233,13284,'".AddSlashes(pg_result($resaco,$conresaco,'me12_i_tprefeicao'))."','$this->me12_i_tprefeicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me12_d_data"]) || $this->me12_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2233,14156,'".AddSlashes(pg_result($resaco,$conresaco,'me12_d_data'))."','$this->me12_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapiodia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me12_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapiodia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me12_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me12_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12755,'$me12_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2233,12755,'','".AddSlashes(pg_result($resaco,$iresaco,'me12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2233,12756,'','".AddSlashes(pg_result($resaco,$iresaco,'me12_i_cardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2233,13284,'','".AddSlashes(pg_result($resaco,$iresaco,'me12_i_tprefeicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2233,14156,'','".AddSlashes(pg_result($resaco,$iresaco,'me12_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_cardapiodia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me12_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me12_i_codigo = $me12_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapiodia nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me12_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapiodia nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me12_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me12_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_cardapiodia";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapiodia ";
     $sql .= "      left join mer_tprefeicao  on  mer_tprefeicao.me03_i_codigo = mer_cardapiodia.me12_i_tprefeicao";
     $sql .= "      left join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapiodia.me12_i_cardapio";
     $sql .= "      left join turno  on  turno.ed15_i_codigo = mer_tprefeicao.me03_i_turno";
     $sql .= "      left join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql2 = "";
     if($dbwhere==""){
       if($me12_i_codigo!=null ){
         $sql2 .= " where mer_cardapiodia.me12_i_codigo = $me12_i_codigo "; 
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
   function sql_query_file ( $me12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapiodia ";
     $sql2 = "";
     if($dbwhere==""){
       if($me12_i_codigo!=null ){
         $sql2 .= " where mer_cardapiodia.me12_i_codigo = $me12_i_codigo "; 
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
function sql_query_horario ( $me12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapiodia ";
     $sql .= "      left join mer_tprefeicao  on  mer_tprefeicao.me03_i_codigo = mer_cardapiodia.me12_i_tprefeicao";
     $sql .= "      left join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapiodia.me12_i_cardapio";
     $sql .= "      left join turno  on  turno.ed15_i_codigo = mer_tprefeicao.me03_i_turno";
     $sql .= "      left join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql2 = "";
     if($dbwhere==""){
       if($me12_i_codigo!=null ){
         $sql2 .= " where mer_cardapiodia.me12_i_codigo = $me12_i_codigo "; 
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

   function sql_query_cardapiodiaescola ( $me12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapiodia ";
     $sql .= "   inner join mer_tprefeicao  on  mer_tprefeicao.me03_i_codigo = mer_cardapiodia.me12_i_tprefeicao";
     $sql .= "   inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapiodia.me12_i_cardapio";
     $sql .= "   inner join turno  on  turno.ed15_i_codigo = mer_tprefeicao.me03_i_turno";
     $sql .= "   inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql .= "   inner join mer_cardapiodiaescola  on  mer_cardapiodiaescola.me37_i_cardapiodia = mer_cardapiodia.me12_i_codigo";
     $sql .= "   inner join mer_cardapioescola  on  mer_cardapioescola.me32_i_codigo = mer_cardapiodiaescola.me37_i_cardapioescola";
     $sql .= "   inner join escola  on  escola.ed18_i_codigo = mer_cardapioescola.me32_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($me12_i_codigo!=null ){
         $sql2 .= " where mer_cardapiodia.me12_i_codigo = $me12_i_codigo "; 
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