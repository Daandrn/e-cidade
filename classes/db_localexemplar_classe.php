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

//MODULO: biblioteca
//CLASSE DA ENTIDADE localexemplar
class cl_localexemplar {
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
   var $bi27_codigo = 0;
   var $bi27_localacervo = 0;
   var $bi27_exemplar = 0;
   var $bi27_letra = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 bi27_codigo = int8 = C�digo
                 bi27_localacervo = int8 = Localiza��o do Acervo
                 bi27_exemplar = int8 = Exemplar
                 bi27_letra = char(1) = Letra
                 ";
   //funcao construtor da classe
   function cl_localexemplar() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("localexemplar");
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
       $this->bi27_codigo = ($this->bi27_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi27_codigo"]:$this->bi27_codigo);
       $this->bi27_localacervo = ($this->bi27_localacervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi27_localacervo"]:$this->bi27_localacervo);
       $this->bi27_exemplar = ($this->bi27_exemplar == ""?@$GLOBALS["HTTP_POST_VARS"]["bi27_exemplar"]:$this->bi27_exemplar);
       $this->bi27_letra = ($this->bi27_letra == ""?@$GLOBALS["HTTP_POST_VARS"]["bi27_letra"]:$this->bi27_letra);
     }else{
       $this->bi27_codigo = ($this->bi27_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi27_codigo"]:$this->bi27_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($bi27_codigo){
      $this->atualizacampos();
     if($this->bi27_localacervo == null ){
       $this->erro_sql = " Campo Localiza��o do Acervo nao Informado.";
       $this->erro_campo = "bi27_localacervo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi27_exemplar == null ){
       $this->erro_sql = " Campo Exemplar nao Informado.";
       $this->erro_campo = "bi27_exemplar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi27_codigo == "" || $bi27_codigo == null ){
       $result = db_query("select nextval('localexemplar_bi27_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: localexemplar_bi27_codigo_seq do campo: bi27_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->bi27_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from localexemplar_bi27_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi27_codigo)){
         $this->erro_sql = " Campo bi27_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi27_codigo = $bi27_codigo;
       }
     }
     if(($this->bi27_codigo == null) || ($this->bi27_codigo == "") ){
       $this->erro_sql = " Campo bi27_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into localexemplar(
                                       bi27_codigo
                                      ,bi27_localacervo
                                      ,bi27_exemplar
                                      ,bi27_letra
                       )
                values (
                                $this->bi27_codigo
                               ,$this->bi27_localacervo
                               ,$this->bi27_exemplar
                               ,'$this->bi27_letra'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordem do Exemplar na Localiza��o ($this->bi27_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordem do Exemplar na Localiza��o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordem do Exemplar na Localiza��o ($this->bi27_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi27_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bi27_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12804,'$this->bi27_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2243,12804,'','".AddSlashes(pg_result($resaco,0,'bi27_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2243,12805,'','".AddSlashes(pg_result($resaco,0,'bi27_localacervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2243,12806,'','".AddSlashes(pg_result($resaco,0,'bi27_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2243,12807,'','".AddSlashes(pg_result($resaco,0,'bi27_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($bi27_codigo=null) {
      $this->atualizacampos();
     $sql = " update localexemplar set ";
     $virgula = "";
     if(trim($this->bi27_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi27_codigo"])){
       $sql  .= $virgula." bi27_codigo = $this->bi27_codigo ";
       $virgula = ",";
       if(trim($this->bi27_codigo) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "bi27_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi27_localacervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi27_localacervo"])){
       $sql  .= $virgula." bi27_localacervo = $this->bi27_localacervo ";
       $virgula = ",";
       if(trim($this->bi27_localacervo) == null ){
         $this->erro_sql = " Campo Localiza��o do Acervo nao Informado.";
         $this->erro_campo = "bi27_localacervo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi27_exemplar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi27_exemplar"])){
       $sql  .= $virgula." bi27_exemplar = $this->bi27_exemplar ";
       $virgula = ",";
       if(trim($this->bi27_exemplar) == null ){
         $this->erro_sql = " Campo Exemplar nao Informado.";
         $this->erro_campo = "bi27_exemplar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi27_letra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi27_letra"])){
       $sql  .= $virgula." bi27_letra = '$this->bi27_letra' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($bi27_codigo!=null){
       $sql .= " bi27_codigo = $this->bi27_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bi27_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12804,'$this->bi27_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi27_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2243,12804,'".AddSlashes(pg_result($resaco,$conresaco,'bi27_codigo'))."','$this->bi27_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi27_localacervo"]))
           $resac = db_query("insert into db_acount values($acount,2243,12805,'".AddSlashes(pg_result($resaco,$conresaco,'bi27_localacervo'))."','$this->bi27_localacervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi27_exemplar"]))
           $resac = db_query("insert into db_acount values($acount,2243,12806,'".AddSlashes(pg_result($resaco,$conresaco,'bi27_exemplar'))."','$this->bi27_exemplar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bi27_letra"]))
           $resac = db_query("insert into db_acount values($acount,2243,12807,'".AddSlashes(pg_result($resaco,$conresaco,'bi27_letra'))."','$this->bi27_letra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem do Exemplar na Localiza��o nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi27_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem do Exemplar na Localiza��o nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi27_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi27_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($bi27_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bi27_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12804,'$bi27_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2243,12804,'','".AddSlashes(pg_result($resaco,$iresaco,'bi27_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2243,12805,'','".AddSlashes(pg_result($resaco,$iresaco,'bi27_localacervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2243,12806,'','".AddSlashes(pg_result($resaco,$iresaco,'bi27_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2243,12807,'','".AddSlashes(pg_result($resaco,$iresaco,'bi27_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from localexemplar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bi27_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bi27_codigo = $bi27_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem do Exemplar na Localiza��o nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi27_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem do Exemplar na Localiza��o nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi27_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi27_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:localexemplar";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $bi27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from localexemplar ";
     $sql .= "      inner join localacervo  on  localacervo.bi20_codigo = localexemplar.bi27_localacervo";
     $sql .= "      inner join exemplar  on  exemplar.bi23_codigo = localexemplar.bi27_exemplar";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = localacervo.bi20_acervo";
     $sql .= "      inner join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql .= "      inner join aquisicao  on  aquisicao.bi04_codigo = exemplar.bi23_aquisicao";
     $sql2 = "";
     if($dbwhere==""){
       if($bi27_codigo!=null ){
         $sql2 .= " where localexemplar.bi27_codigo = $bi27_codigo ";
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
   function sql_query_file ( $bi27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from localexemplar ";
     $sql2 = "";
     if($dbwhere==""){
       if($bi27_codigo!=null ){
         $sql2 .= " where localexemplar.bi27_codigo = $bi27_codigo ";
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