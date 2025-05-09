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
//CLASSE DA ENTIDADE mer_cardapio
class cl_mer_cardapio { 
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
   var $me01_i_codigo        = 0; 
   var $me01_c_nome        = null; 
   var $me01_i_percapita        = 0; 
   var $me01_f_versao        = 0; 
   var $me01_i_id        = 0; 
   var $me01_i_tipocardapio        = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me01_i_codigo = int4 = C�digo 
                 me01_c_nome = char(50) = Nome da Refei��o 
                 me01_i_percapita = int4 = Alunos Atendidos 
                 me01_f_versao = float4 = Vers�o 
                 me01_i_id = int4 = Numero ID 
                 me01_i_tipocardapio = int4 = Tipo de Card�pio 
                 ";
   //funcao construtor da classe 
   function cl_mer_cardapio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_cardapio"); 
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
       $this->me01_i_codigo = ($this->me01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_i_codigo"]:$this->me01_i_codigo);
       $this->me01_c_nome = ($this->me01_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_c_nome"]:$this->me01_c_nome);
       $this->me01_i_percapita = ($this->me01_i_percapita == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_i_percapita"]:$this->me01_i_percapita);
       $this->me01_f_versao = ($this->me01_f_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_f_versao"]:$this->me01_f_versao);
       $this->me01_i_id = ($this->me01_i_id == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_i_id"]:$this->me01_i_id);
       $this->me01_i_tipocardapio = ($this->me01_i_tipocardapio == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_i_tipocardapio"]:$this->me01_i_tipocardapio);
     }else{
       $this->me01_i_codigo = ($this->me01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me01_i_codigo"]:$this->me01_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me01_i_codigo){ 
      $this->atualizacampos();
     if($this->me01_c_nome == null ){ 
       $this->erro_sql = " Campo Nome da Refei��o nao Informado.";
       $this->erro_campo = "me01_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me01_i_percapita == null ){ 
       $this->erro_sql = " Campo Alunos Atendidos nao Informado.";
       $this->erro_campo = "me01_i_percapita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me01_f_versao == null ){ 
       $this->erro_sql = " Campo Vers�o nao Informado.";
       $this->erro_campo = "me01_f_versao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me01_i_id == null ){ 
       $this->erro_sql = " Campo Numero ID nao Informado.";
       $this->erro_campo = "me01_i_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me01_i_tipocardapio == null ){ 
       $this->erro_sql = " Campo Tipo de Card�pio nao Informado.";
       $this->erro_campo = "me01_i_tipocardapio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me01_i_codigo == "" || $me01_i_codigo == null ){
       $result = db_query("select nextval('mercardapio_me01_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mercardapio_me01_codigo_seq do campo: me01_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me01_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mercardapio_me01_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me01_i_codigo)){
         $this->erro_sql = " Campo me01_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me01_i_codigo = $me01_i_codigo; 
       }
     }
     if(($this->me01_i_codigo == null) || ($this->me01_i_codigo == "") ){ 
       $this->erro_sql = " Campo me01_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_cardapio(
                                       me01_i_codigo 
                                      ,me01_c_nome 
                                      ,me01_i_percapita 
                                      ,me01_f_versao 
                                      ,me01_i_id 
                                      ,me01_i_tipocardapio 
                       )
                values (
                                $this->me01_i_codigo 
                               ,'$this->me01_c_nome' 
                               ,$this->me01_i_percapita 
                               ,$this->me01_f_versao 
                               ,$this->me01_i_id 
                               ,$this->me01_i_tipocardapio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_cardapio ($this->me01_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_cardapio j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_cardapio ($this->me01_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me01_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me01_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12763,'$this->me01_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2235,12763,'','".AddSlashes(pg_result($resaco,0,'me01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2235,12764,'','".AddSlashes(pg_result($resaco,0,'me01_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2235,12766,'','".AddSlashes(pg_result($resaco,0,'me01_i_percapita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2235,13543,'','".AddSlashes(pg_result($resaco,0,'me01_f_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2235,13544,'','".AddSlashes(pg_result($resaco,0,'me01_i_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2235,17103,'','".AddSlashes(pg_result($resaco,0,'me01_i_tipocardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me01_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_cardapio set ";
     $virgula = "";
     if(trim($this->me01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me01_i_codigo"])){ 
       $sql  .= $virgula." me01_i_codigo = $this->me01_i_codigo ";
       $virgula = ",";
       if(trim($this->me01_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "me01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me01_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me01_c_nome"])){ 
       $sql  .= $virgula." me01_c_nome = '$this->me01_c_nome' ";
       $virgula = ",";
       if(trim($this->me01_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome da Refei��o nao Informado.";
         $this->erro_campo = "me01_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me01_i_percapita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me01_i_percapita"])){ 
       $sql  .= $virgula." me01_i_percapita = $this->me01_i_percapita ";
       $virgula = ",";
       if(trim($this->me01_i_percapita) == null ){ 
         $this->erro_sql = " Campo Alunos Atendidos nao Informado.";
         $this->erro_campo = "me01_i_percapita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me01_f_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me01_f_versao"])){ 
       $sql  .= $virgula." me01_f_versao = $this->me01_f_versao ";
       $virgula = ",";
       if(trim($this->me01_f_versao) == null ){ 
         $this->erro_sql = " Campo Vers�o nao Informado.";
         $this->erro_campo = "me01_f_versao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me01_i_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me01_i_id"])){ 
       $sql  .= $virgula." me01_i_id = $this->me01_i_id ";
       $virgula = ",";
       if(trim($this->me01_i_id) == null ){ 
         $this->erro_sql = " Campo Numero ID nao Informado.";
         $this->erro_campo = "me01_i_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me01_i_tipocardapio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me01_i_tipocardapio"])){ 
       $sql  .= $virgula." me01_i_tipocardapio = $this->me01_i_tipocardapio ";
       $virgula = ",";
       if(trim($this->me01_i_tipocardapio) == null ){ 
         $this->erro_sql = " Campo Tipo de Card�pio nao Informado.";
         $this->erro_campo = "me01_i_tipocardapio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me01_i_codigo!=null){
       $sql .= " me01_i_codigo = $this->me01_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me01_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12763,'$this->me01_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me01_i_codigo"]) || $this->me01_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2235,12763,'".AddSlashes(pg_result($resaco,$conresaco,'me01_i_codigo'))."','$this->me01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me01_c_nome"]) || $this->me01_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,2235,12764,'".AddSlashes(pg_result($resaco,$conresaco,'me01_c_nome'))."','$this->me01_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me01_i_percapita"]) || $this->me01_i_percapita != "")
           $resac = db_query("insert into db_acount values($acount,2235,12766,'".AddSlashes(pg_result($resaco,$conresaco,'me01_i_percapita'))."','$this->me01_i_percapita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me01_f_versao"]) || $this->me01_f_versao != "")
           $resac = db_query("insert into db_acount values($acount,2235,13543,'".AddSlashes(pg_result($resaco,$conresaco,'me01_f_versao'))."','$this->me01_f_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me01_i_id"]) || $this->me01_i_id != "")
           $resac = db_query("insert into db_acount values($acount,2235,13544,'".AddSlashes(pg_result($resaco,$conresaco,'me01_i_id'))."','$this->me01_i_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me01_i_tipocardapio"]) || $this->me01_i_tipocardapio != "")
           $resac = db_query("insert into db_acount values($acount,2235,17103,'".AddSlashes(pg_result($resaco,$conresaco,'me01_i_tipocardapio'))."','$this->me01_i_tipocardapio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me01_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me01_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12763,'$me01_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2235,12763,'','".AddSlashes(pg_result($resaco,$iresaco,'me01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2235,12764,'','".AddSlashes(pg_result($resaco,$iresaco,'me01_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2235,12766,'','".AddSlashes(pg_result($resaco,$iresaco,'me01_i_percapita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2235,13543,'','".AddSlashes(pg_result($resaco,$iresaco,'me01_f_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2235,13544,'','".AddSlashes(pg_result($resaco,$iresaco,'me01_i_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2235,17103,'','".AddSlashes(pg_result($resaco,$iresaco,'me01_i_tipocardapio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_cardapio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me01_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me01_i_codigo = $me01_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapio nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapio nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me01_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_cardapio";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapio ";
     $sql .= "      inner join mer_tipocardapio  on  mer_tipocardapio.me27_i_codigo = mer_cardapio.me01_i_tipocardapio";
     $sql2 = "";
     if($dbwhere==""){
       if($me01_i_codigo!=null ){
         $sql2 .= " where mer_cardapio.me01_i_codigo = $me01_i_codigo "; 
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
   function sql_query_file ( $me01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapio ";
     $sql2 = "";
     if($dbwhere==""){
       if($me01_i_codigo!=null ){
         $sql2 .= " where mer_cardapio.me01_i_codigo = $me01_i_codigo "; 
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