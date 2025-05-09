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

//MODULO: Custos
//CLASSE DA ENTIDADE parcustos
class cl_parcustos { 
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
   var $cc09_anousu = 0; 
   var $cc09_instit = 0; 
   var $cc09_mascaracustoplano = 0; 
   var $cc09_tipocontrole = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc09_anousu = int4 = Exerc�cio 
                 cc09_instit = int4 = Institui��o 
                 cc09_mascaracustoplano = int4 = C�digo 
                 cc09_tipocontrole = int4 = Tipo de Controle 
                 ";
   //funcao construtor da classe 
   function cl_parcustos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parcustos"); 
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
       $this->cc09_anousu = ($this->cc09_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["cc09_anousu"]:$this->cc09_anousu);
       $this->cc09_instit = ($this->cc09_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["cc09_instit"]:$this->cc09_instit);
       $this->cc09_mascaracustoplano = ($this->cc09_mascaracustoplano == ""?@$GLOBALS["HTTP_POST_VARS"]["cc09_mascaracustoplano"]:$this->cc09_mascaracustoplano);
       $this->cc09_tipocontrole = ($this->cc09_tipocontrole == ""?@$GLOBALS["HTTP_POST_VARS"]["cc09_tipocontrole"]:$this->cc09_tipocontrole);
     }else{
       $this->cc09_anousu = ($this->cc09_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["cc09_anousu"]:$this->cc09_anousu);
     }
   }
   // funcao para inclusao
   function incluir ($cc09_anousu){ 
      $this->atualizacampos();
     if($this->cc09_instit == null ){ 
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "cc09_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc09_mascaracustoplano == null ){ 
       $this->erro_sql = " Campo C�digo nao Informado.";
       $this->erro_campo = "cc09_mascaracustoplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc09_tipocontrole == null ){ 
       $this->erro_sql = " Campo Tipo de Controle nao Informado.";
       $this->erro_campo = "cc09_tipocontrole";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cc09_anousu = $cc09_anousu; 
     if(($this->cc09_anousu == null) || ($this->cc09_anousu == "") ){ 
       $this->erro_sql = " Campo cc09_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parcustos(
                                       cc09_anousu 
                                      ,cc09_instit 
                                      ,cc09_mascaracustoplano 
                                      ,cc09_tipocontrole 
                       )
                values (
                                $this->cc09_anousu 
                               ,$this->cc09_instit 
                               ,$this->cc09_mascaracustoplano 
                               ,$this->cc09_tipocontrole 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Par�metros do m�dulo custos ($this->cc09_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Par�metros do m�dulo custos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Par�metros do m�dulo custos ($this->cc09_anousu) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc09_anousu;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc09_anousu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12608,'$this->cc09_anousu','I')");
       $resac = db_query("insert into db_acount values($acount,2201,12608,'','".AddSlashes(pg_result($resaco,0,'cc09_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2201,12609,'','".AddSlashes(pg_result($resaco,0,'cc09_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2201,12610,'','".AddSlashes(pg_result($resaco,0,'cc09_mascaracustoplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2201,13413,'','".AddSlashes(pg_result($resaco,0,'cc09_tipocontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc09_anousu=null) { 
      $this->atualizacampos();
     $sql = " update parcustos set ";
     $virgula = "";
     if(trim($this->cc09_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc09_anousu"])){ 
       $sql  .= $virgula." cc09_anousu = $this->cc09_anousu ";
       $virgula = ",";
       if(trim($this->cc09_anousu) == null ){ 
         $this->erro_sql = " Campo Exerc�cio nao Informado.";
         $this->erro_campo = "cc09_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc09_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc09_instit"])){ 
       $sql  .= $virgula." cc09_instit = $this->cc09_instit ";
       $virgula = ",";
       if(trim($this->cc09_instit) == null ){ 
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "cc09_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc09_mascaracustoplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc09_mascaracustoplano"])){ 
       $sql  .= $virgula." cc09_mascaracustoplano = $this->cc09_mascaracustoplano ";
       $virgula = ",";
       if(trim($this->cc09_mascaracustoplano) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "cc09_mascaracustoplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc09_tipocontrole)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc09_tipocontrole"])){ 
       $sql  .= $virgula." cc09_tipocontrole = $this->cc09_tipocontrole ";
       $virgula = ",";
       if(trim($this->cc09_tipocontrole) == null ){ 
         $this->erro_sql = " Campo Tipo de Controle nao Informado.";
         $this->erro_campo = "cc09_tipocontrole";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc09_anousu!=null){
       $sql .= " cc09_anousu = $this->cc09_anousu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc09_anousu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12608,'$this->cc09_anousu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc09_anousu"]))
           $resac = db_query("insert into db_acount values($acount,2201,12608,'".AddSlashes(pg_result($resaco,$conresaco,'cc09_anousu'))."','$this->cc09_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc09_instit"]))
           $resac = db_query("insert into db_acount values($acount,2201,12609,'".AddSlashes(pg_result($resaco,$conresaco,'cc09_instit'))."','$this->cc09_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc09_mascaracustoplano"]))
           $resac = db_query("insert into db_acount values($acount,2201,12610,'".AddSlashes(pg_result($resaco,$conresaco,'cc09_mascaracustoplano'))."','$this->cc09_mascaracustoplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc09_tipocontrole"]))
           $resac = db_query("insert into db_acount values($acount,2201,13413,'".AddSlashes(pg_result($resaco,$conresaco,'cc09_tipocontrole'))."','$this->cc09_tipocontrole',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Par�metros do m�dulo custos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc09_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Par�metros do m�dulo custos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc09_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc09_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc09_anousu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc09_anousu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12608,'$cc09_anousu','E')");
         $resac = db_query("insert into db_acount values($acount,2201,12608,'','".AddSlashes(pg_result($resaco,$iresaco,'cc09_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2201,12609,'','".AddSlashes(pg_result($resaco,$iresaco,'cc09_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2201,12610,'','".AddSlashes(pg_result($resaco,$iresaco,'cc09_mascaracustoplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2201,13413,'','".AddSlashes(pg_result($resaco,$iresaco,'cc09_tipocontrole'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from parcustos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc09_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc09_anousu = $cc09_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Par�metros do m�dulo custos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc09_anousu;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Par�metros do m�dulo custos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc09_anousu;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc09_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:parcustos";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc09_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parcustos ";
     $sql .= "      inner join db_config  on  db_config.codigo = parcustos.cc09_instit";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = parcustos.cc09_mascaracustoplano";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($cc09_anousu!=null ){
         $sql2 .= " where parcustos.cc09_anousu = $cc09_anousu "; 
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
   function sql_query_file ( $cc09_anousu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from parcustos ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc09_anousu!=null ){
         $sql2 .= " where parcustos.cc09_anousu = $cc09_anousu "; 
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