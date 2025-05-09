<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_permherda
class cl_db_permherda { 
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
   var $id_usuario = 0; 
   var $id_perfil = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_usuario = int4 = Cod. Usu�rio 
                 id_perfil = int4 = Cod. Usu�rio 
                 ";
   //funcao construtor da classe 
   function cl_db_permherda() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_permherda"); 
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
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->id_perfil = ($this->id_perfil == ""?@$GLOBALS["HTTP_POST_VARS"]["id_perfil"]:$this->id_perfil);
     }else{
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->id_perfil = ($this->id_perfil == ""?@$GLOBALS["HTTP_POST_VARS"]["id_perfil"]:$this->id_perfil);
     }
   }
   // funcao para inclusao
   function incluir ($id_usuario,$id_perfil){ 
      $this->atualizacampos();
       $this->id_usuario = $id_usuario; 
       $this->id_perfil = $id_perfil; 
     if(($this->id_usuario == null) || ($this->id_usuario == "") ){ 
       $this->erro_sql = " Campo id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->id_perfil == null) || ($this->id_perfil == "") ){ 
       $this->erro_sql = " Campo id_perfil nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_permherda(
                                       id_usuario 
                                      ,id_perfil 
                       )
                values (
                                $this->id_usuario 
                               ,$this->id_perfil 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Herda permiss�es de usu�rios ($this->id_usuario."-".$this->id_perfil) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Herda permiss�es de usu�rios j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Herda permiss�es de usu�rios ($this->id_usuario."-".$this->id_perfil) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_perfil;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) 
     		 && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->id_usuario,$this->id_perfil  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','I')");
         $resac = db_query("insert into db_acountkey values($acount,4768,'$this->id_perfil','I')");
         $resac = db_query("insert into db_acount values($acount,639,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,639,4768,'','".AddSlashes(pg_result($resaco,0,'id_perfil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_usuario=null,$id_perfil=null) { 
      $this->atualizacampos();
     $sql = " update db_permherda set ";
     $virgula = "";
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
        if(trim($this->id_usuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
           $this->id_usuario = "0" ; 
        } 
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_perfil)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_perfil"])){ 
        if(trim($this->id_perfil)=="" && isset($GLOBALS["HTTP_POST_VARS"]["id_perfil"])){ 
           $this->id_perfil = "0" ; 
        } 
       $sql  .= $virgula." id_perfil = $this->id_perfil ";
       $virgula = ",";
       if(trim($this->id_perfil) == null ){ 
         $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
         $this->erro_campo = "id_perfil";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_usuario!=null){
       $sql .= " id_usuario = $this->id_usuario";
     }
     if($id_perfil!=null){
       $sql .= " and  id_perfil = $this->id_perfil";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) 
     		 && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->id_usuario,$this->id_perfil));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','A')");
           $resac = db_query("insert into db_acountkey values($acount,4768,'$this->id_perfil','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]) || $this->id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,639,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["id_perfil"]) || $this->id_perfil != "")
             $resac = db_query("insert into db_acount values($acount,639,4768,'".AddSlashes(pg_result($resaco,$conresaco,'id_perfil'))."','$this->id_perfil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Herda permiss�es de usu�rios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_perfil;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Herda permiss�es de usu�rios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_perfil;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_perfil;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_usuario=null,$id_perfil=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) 
     		 && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($id_usuario,$id_perfil));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,568,'$id_usuario','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4768,'$id_perfil','E')");
           $resac  = db_query("insert into db_acount values($acount,639,568,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,639,4768,'','".AddSlashes(pg_result($resaco,$iresaco,'id_perfil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from db_permherda
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_usuario = $id_usuario ";
        }
        if($id_perfil != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_perfil = $id_perfil ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Herda permiss�es de usu�rios nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_usuario."-".$id_perfil;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Herda permiss�es de usu�rios nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_usuario."-".$id_perfil;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_usuario."-".$id_perfil;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_permherda";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $id_usuario=null,$id_perfil=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_permherda ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_permherda.id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_permherda.id_usuario = $id_usuario "; 
       } 
       if($id_perfil!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permherda.id_perfil = $id_perfil "; 
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
   function sql_query_file ( $id_usuario=null,$id_perfil=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_permherda ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_permherda.id_usuario = $id_usuario "; 
       } 
       if($id_perfil!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permherda.id_perfil = $id_perfil "; 
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
  
  function sql_query_db_userinst ( $id_usuario = null, $id_perfil = null, $campos = "*", $ordem = null, $dbwhere = "") {
  	
  	$sql = "select ";
  	if ($campos != "*" ) {
  		
  		$campos_sql = explode("#",$campos);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {
  			
  			$sql     .= $virgula.$campos_sql[$i];
  			$virgula  = ",";
  		}
  	} else {
  		$sql .= $campos;
  	}
  	$sql .= " from db_permherda ";
  	$sql .= "      inner join db_userinst on db_userinst.id_usuario = db_permherda.id_usuario";
  	$sql2 = "";
  	
  	if ($dbwhere == "") {
  		
  		if ($id_usuario != null ) {
  			$sql2 .= " where db_permherda.id_usuario = $id_usuario ";
  		}
  		if ($id_perfil != null ) {
  			
  			if ($sql2 != "") {
  			  $sql2 .= " and ";
  			} else {
  			  $sql2 .= " where ";
  			}
  			$sql2 .= " db_permherda.id_perfil = $id_perfil ";
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