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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE tpcontra
class cl_tpcontra {
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
   var $h13_codigo = 0;
   var $h13_regime = 0;
   var $h13_tpcont = null;
   var $h13_descr = null;
   var $h13_tipocargo = 0;
   var $h13_tipocargodescr = null;
   var $h13_dscapo = null;
   var $h13_categoria = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 h13_codigo = int4 = C�digo
                 h13_regime = int4 = Codigo do Regime do Func.
                 h13_tpcont = varchar(2) = Tipo de contrato
                 h13_descr = varchar(40) = Descri��o do Tipo
                 h13_tipocargo = int4 = Tipo de cargo;
                 h13_tipocargodescr = varchar(150) Descri��o do tipo de cargo;
                 h13_dscapo        = varchar (3) Descri��o do tipo de cargo
                 h13_categoria = int4 = Categoria e-Social;
                 ";
   //funcao construtor da classe
   function cl_tpcontra() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tpcontra");
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
       $this->h13_codigo = ($this->h13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_codigo"]:$this->h13_codigo);
       $this->h13_regime = ($this->h13_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_regime"]:$this->h13_regime);
       $this->h13_tpcont = ($this->h13_tpcont == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_tpcont"]:$this->h13_tpcont);
       $this->h13_descr = ($this->h13_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_descr"]:$this->h13_descr);
       $this->h13_tipocargo = ($this->h13_tipocargo == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_tipocargo"]:$this->h13_tipocargo);
       $this->h13_tipocargodescr = ($this->h13_tipocargodescr == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_tipocargodescr"]:$this->h13_tipocargodescr);
       $this->h13_dscapo = ($this->h13_dscapo == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_dscapo"]:$this->h13_dscapo);
       $this->h13_categoria = ($this->h13_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_categoria"]:$this->h13_categoria);
     }else{
       $this->h13_codigo = ($this->h13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h13_codigo"]:$this->h13_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($h13_codigo){
      $this->atualizacampos();
     if($this->h13_regime == null ){
       $this->erro_sql = " Campo Codigo do Regime do Func. nao Informado.";
       $this->erro_campo = "h13_regime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h13_tpcont == null ){
       $this->erro_sql = " Campo Tipo de contrato nao Informado.";
       $this->erro_campo = "h13_tpcont";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h13_descr == null ){
       $this->erro_sql = " Campo Descri��o do Tipo nao Informado.";
       $this->erro_campo = "h13_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h13_tipocargo == null ){
       $this->erro_sql = " Campo Tipo de cargo nao Informado.";
       $this->erro_campo = "h13_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h13_categoria == null ){
       $this->erro_sql = " Campo Categoria e-Social nao Informado.";
       $this->erro_campo = "h13_categoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h13_codigo == "" || $h13_codigo == null ){
       $result = db_query("select nextval('tpcontra_h13_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tpcontra_h13_codigo_seq do campo: h13_codigo";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->h13_codigo = pg_result($result,0,0);
     }else{
         $this->h13_codigo = $h13_codigo;
     }

     if(($this->h13_codigo == null) || ($this->h13_codigo == "") ){
       $this->erro_sql = " Campo h13_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tpcontra(
                                       h13_codigo
                                      ,h13_regime
                                      ,h13_tpcont
                                      ,h13_descr
                                      ,h13_tipocargo
                                      ,h13_tipocargodescr
                                      ,h13_dscapo
                                      ,h13_categoria
                       )
                values (
                                $this->h13_codigo
                               ,$this->h13_regime
                               ,'$this->h13_tpcont'
                               ,'$this->h13_descr'
                               ,$this->h13_tipocargo
                               ,'$this->h13_tipocargodescr'
                               ,'$this->h13_dscapo'
                               ,$this->h13_categoria
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de contrato por regime ($this->h13_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de contrato por regime j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de contrato por regime ($this->h13_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h13_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h13_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7639,'$this->h13_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,597,7639,'','".AddSlashes(pg_result($resaco,0,'h13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,597,4510,'','".AddSlashes(pg_result($resaco,0,'h13_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,597,4511,'','".AddSlashes(pg_result($resaco,0,'h13_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,597,4512,'','".AddSlashes(pg_result($resaco,0,'h13_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($h13_codigo=null) {
      $this->atualizacampos();
     $sql = " update tpcontra set ";
     $virgula = "";
     if(trim($this->h13_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_codigo"])){
       $sql  .= $virgula." h13_codigo = $this->h13_codigo ";
       $virgula = ",";
       if(trim($this->h13_codigo) == null ){
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "h13_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h13_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_regime"])){
       $sql  .= $virgula." h13_regime = $this->h13_regime ";
       $virgula = ",";
       if(trim($this->h13_regime) == null ){
         $this->erro_sql = " Campo Codigo do Regime do Func. nao Informado.";
         $this->erro_campo = "h13_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h13_tpcont)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_tpcont"])){
       $sql  .= $virgula." h13_tpcont = '$this->h13_tpcont' ";
       $virgula = ",";
       if(trim($this->h13_tpcont) == null ){
         $this->erro_sql = " Campo Tipo de contrato nao Informado.";
         $this->erro_campo = "h13_tpcont";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h13_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_descr"])){
       $sql  .= $virgula." h13_descr = '$this->h13_descr' ";
       $virgula = ",";
       if(trim($this->h13_descr) == null ){
         $this->erro_sql = " Campo Descri��o do Tipo nao Informado.";
         $this->erro_campo = "h13_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h13_tipocargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_regime"])){
       $sql  .= $virgula." h13_tipocargo = $this->h13_tipocargo ";
       $virgula = ",";
       if(trim($this->h13_tipocargo) == null ){
         $this->erro_sql = " Campo Tipo cargo nao Informado.";
         $this->erro_campo = "h13_tipocargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h13_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_regime"])){
       $sql  .= $virgula." h13_categoria = $this->h13_categoria ";
       $virgula = ",";
       if(trim($this->h13_categoria) == null ){
         $this->erro_sql = " Campo Categoria e-Social nao Informado.";
         $this->erro_campo = "h13_categoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h13_tipocargodescr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_tipocargodescr"])) {
       $sql .= $virgula . " h13_tipocargodescr = '$this->h13_tipocargodescr' ";
       $virgula = ",";
     }

     if(trim($this->h13_dscapo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h13_dscapo"])) {
       $sql .= $virgula . " h13_dscapo = '$this->h13_dscapo' ";
       $virgula = ",";
     }

     $sql .= " where ";
     if($h13_codigo!=null){
       $sql .= " h13_codigo = $this->h13_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h13_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7639,'$this->h13_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h13_codigo"]))
           $resac = db_query("insert into db_acount values($acount,597,7639,'".AddSlashes(pg_result($resaco,$conresaco,'h13_codigo'))."','$this->h13_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h13_regime"]))
           $resac = db_query("insert into db_acount values($acount,597,4510,'".AddSlashes(pg_result($resaco,$conresaco,'h13_regime'))."','$this->h13_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h13_tpcont"]))
           $resac = db_query("insert into db_acount values($acount,597,4511,'".AddSlashes(pg_result($resaco,$conresaco,'h13_tpcont'))."','$this->h13_tpcont',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h13_descr"]))
           $resac = db_query("insert into db_acount values($acount,597,4512,'".AddSlashes(pg_result($resaco,$conresaco,'h13_descr'))."','$this->h13_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de contrato por regime nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h13_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de contrato por regime nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h13_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h13_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($h13_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h13_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7639,'$h13_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,597,7639,'','".AddSlashes(pg_result($resaco,$iresaco,'h13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,597,4510,'','".AddSlashes(pg_result($resaco,$iresaco,'h13_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,597,4511,'','".AddSlashes(pg_result($resaco,$iresaco,'h13_tpcont'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,597,4512,'','".AddSlashes(pg_result($resaco,$iresaco,'h13_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tpcontra
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h13_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h13_codigo = $h13_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de contrato por regime nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h13_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de contrato por regime nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h13_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h13_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tpcontra";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tpcontra ";
     $sql2 = "";
     if($dbwhere==""){
       if($h13_codigo!=null ){
         $sql2 .= " where tpcontra.h13_codigo = $h13_codigo ";
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
   function sql_query_file ( $h13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from tpcontra ";
     $sql2 = "";
     if($dbwhere==""){
       if($h13_codigo!=null ){
         $sql2 .= " where tpcontra.h13_codigo = $h13_codigo ";
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
