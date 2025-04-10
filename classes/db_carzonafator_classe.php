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

//MODULO: Cadastro
//CLASSE DA ENTIDADE carzonafator
class cl_carzonafator { 
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
   var $j96_sequencial = 0; 
   var $j96_caracter = 0; 
   var $j96_zona = 0; 
   var $j96_anousu = 0; 
   var $j96_fator = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j96_sequencial = int4 = Sequencial 
                 j96_caracter = int4 = Caracter 
                 j96_zona = int4 = Zona 
                 j96_anousu = int4 = Ano Uso 
                 j96_fator = float8 = Fator 
                 ";
   //funcao construtor da classe 
   function cl_carzonafator() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("carzonafator"); 
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
       $this->j96_sequencial = ($this->j96_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_sequencial"]:$this->j96_sequencial);
       $this->j96_caracter = ($this->j96_caracter == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_caracter"]:$this->j96_caracter);
       $this->j96_zona = ($this->j96_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_zona"]:$this->j96_zona);
       $this->j96_anousu = ($this->j96_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_anousu"]:$this->j96_anousu);
       $this->j96_fator = ($this->j96_fator == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_fator"]:$this->j96_fator);
     }else{
       $this->j96_sequencial = ($this->j96_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j96_sequencial"]:$this->j96_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j96_sequencial){ 
      $this->atualizacampos();
     if($this->j96_caracter == null ){ 
       $this->erro_sql = " Campo Caracter nao Informado.";
       $this->erro_campo = "j96_caracter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_zona == null ){ 
       $this->erro_sql = " Campo Zona nao Informado.";
       $this->erro_campo = "j96_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_anousu == null ){ 
       $this->erro_sql = " Campo Ano Uso nao Informado.";
       $this->erro_campo = "j96_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j96_fator == null ){ 
       $this->erro_sql = " Campo Fator nao Informado.";
       $this->erro_campo = "j96_fator";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j96_sequencial == "" || $j96_sequencial == null ){
       $result = db_query("select nextval('carzonafator_j96_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: carzonafator_j96_sequencial_seq do campo: j96_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j96_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from carzonafator_j96_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j96_sequencial)){
         $this->erro_sql = " Campo j96_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j96_sequencial = $j96_sequencial; 
       }
     }
     if(($this->j96_sequencial == null) || ($this->j96_sequencial == "") ){ 
       $this->erro_sql = " Campo j96_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into carzonafator(
                                       j96_sequencial 
                                      ,j96_caracter 
                                      ,j96_zona 
                                      ,j96_anousu 
                                      ,j96_fator 
                       )
                values (
                                $this->j96_sequencial 
                               ,$this->j96_caracter 
                               ,$this->j96_zona 
                               ,$this->j96_anousu 
                               ,$this->j96_fator 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cargo Zona Fator ($this->j96_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cargo Zona Fator j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cargo Zona Fator ($this->j96_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j96_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15076,'$this->j96_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2652,15076,'','".AddSlashes(pg_result($resaco,0,'j96_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2652,15077,'','".AddSlashes(pg_result($resaco,0,'j96_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2652,15078,'','".AddSlashes(pg_result($resaco,0,'j96_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2652,15080,'','".AddSlashes(pg_result($resaco,0,'j96_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2652,15081,'','".AddSlashes(pg_result($resaco,0,'j96_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j96_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update carzonafator set ";
     $virgula = "";
     if(trim($this->j96_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_sequencial"])){ 
       $sql  .= $virgula." j96_sequencial = $this->j96_sequencial ";
       $virgula = ",";
       if(trim($this->j96_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j96_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_caracter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_caracter"])){ 
       $sql  .= $virgula." j96_caracter = $this->j96_caracter ";
       $virgula = ",";
       if(trim($this->j96_caracter) == null ){ 
         $this->erro_sql = " Campo Caracter nao Informado.";
         $this->erro_campo = "j96_caracter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_zona"])){ 
       $sql  .= $virgula." j96_zona = $this->j96_zona ";
       $virgula = ",";
       if(trim($this->j96_zona) == null ){ 
         $this->erro_sql = " Campo Zona nao Informado.";
         $this->erro_campo = "j96_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_anousu"])){ 
       $sql  .= $virgula." j96_anousu = $this->j96_anousu ";
       $virgula = ",";
       if(trim($this->j96_anousu) == null ){ 
         $this->erro_sql = " Campo Ano Uso nao Informado.";
         $this->erro_campo = "j96_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j96_fator)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j96_fator"])){ 
       $sql  .= $virgula." j96_fator = $this->j96_fator ";
       $virgula = ",";
       if(trim($this->j96_fator) == null ){ 
         $this->erro_sql = " Campo Fator nao Informado.";
         $this->erro_campo = "j96_fator";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j96_sequencial!=null){
       $sql .= " j96_sequencial = $this->j96_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j96_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15076,'$this->j96_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_sequencial"]) || $this->j96_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2652,15076,'".AddSlashes(pg_result($resaco,$conresaco,'j96_sequencial'))."','$this->j96_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_caracter"]) || $this->j96_caracter != "")
           $resac = db_query("insert into db_acount values($acount,2652,15077,'".AddSlashes(pg_result($resaco,$conresaco,'j96_caracter'))."','$this->j96_caracter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_zona"]) || $this->j96_zona != "")
           $resac = db_query("insert into db_acount values($acount,2652,15078,'".AddSlashes(pg_result($resaco,$conresaco,'j96_zona'))."','$this->j96_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_anousu"]) || $this->j96_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2652,15080,'".AddSlashes(pg_result($resaco,$conresaco,'j96_anousu'))."','$this->j96_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j96_fator"]) || $this->j96_fator != "")
           $resac = db_query("insert into db_acount values($acount,2652,15081,'".AddSlashes(pg_result($resaco,$conresaco,'j96_fator'))."','$this->j96_fator',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cargo Zona Fator nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cargo Zona Fator nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j96_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j96_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j96_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15076,'$j96_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2652,15076,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2652,15077,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2652,15078,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2652,15080,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2652,15081,'','".AddSlashes(pg_result($resaco,$iresaco,'j96_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from carzonafator
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j96_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j96_sequencial = $j96_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cargo Zona Fator nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j96_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cargo Zona Fator nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j96_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j96_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:carzonafator";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j96_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carzonafator ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = carzonafator.j96_caracter";
     $sql .= "      inner join zonas  on  zonas.j50_zona = carzonafator.j96_zona";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j96_sequencial!=null ){
         $sql2 .= " where carzonafator.j96_sequencial = $j96_sequencial "; 
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
   function sql_query_file ( $j96_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from carzonafator ";
     $sql2 = "";
     if($dbwhere==""){
       if($j96_sequencial!=null ){
         $sql2 .= " where carzonafator.j96_sequencial = $j96_sequencial "; 
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