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

//MODULO: dividaativa
//CLASSE DA ENTIDADE acertter
class cl_acertter { 
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
   var $v14_certid = 0; 
   var $v14_parcel = 0; 
   var $v14_vlrhis = 0; 
   var $v14_vlrcor = 0; 
   var $v14_vlrjur = 0; 
   var $v14_vlrmul = 0; 
   var $v14_codacertid = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v14_certid = int4 = certidao 
                 v14_parcel = int4 = parcelamento 
                 v14_vlrhis = float8 = valor historico 
                 v14_vlrcor = float8 = valor corrigido 
                 v14_vlrjur = float8 = valor dos juros 
                 v14_vlrmul = float8 = valor da multa 
                 v14_codacertid = int8 = C�digo 
                 ";
   //funcao construtor da classe 
   function cl_acertter() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acertter"); 
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
       $this->v14_certid = ($this->v14_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_certid"]:$this->v14_certid);
       $this->v14_parcel = ($this->v14_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_parcel"]:$this->v14_parcel);
       $this->v14_vlrhis = ($this->v14_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_vlrhis"]:$this->v14_vlrhis);
       $this->v14_vlrcor = ($this->v14_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_vlrcor"]:$this->v14_vlrcor);
       $this->v14_vlrjur = ($this->v14_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_vlrjur"]:$this->v14_vlrjur);
       $this->v14_vlrmul = ($this->v14_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_vlrmul"]:$this->v14_vlrmul);
       $this->v14_codacertid = ($this->v14_codacertid == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_codacertid"]:$this->v14_codacertid);
     }else{
       $this->v14_certid = ($this->v14_certid == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_certid"]:$this->v14_certid);
       $this->v14_parcel = ($this->v14_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["v14_parcel"]:$this->v14_parcel);
     }
   }
   // funcao para inclusao
   function incluir ($v14_certid,$v14_parcel){ 
      $this->atualizacampos();
     if($this->v14_vlrhis == null ){ 
       $this->erro_sql = " Campo valor historico nao Informado.";
       $this->erro_campo = "v14_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v14_vlrcor == null ){ 
       $this->erro_sql = " Campo valor corrigido nao Informado.";
       $this->erro_campo = "v14_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v14_vlrjur == null ){ 
       $this->erro_sql = " Campo valor dos juros nao Informado.";
       $this->erro_campo = "v14_vlrjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v14_vlrmul == null ){ 
       $this->erro_sql = " Campo valor da multa nao Informado.";
       $this->erro_campo = "v14_vlrmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v14_codacertid == null ){ 
       $this->erro_sql = " Campo C�digo nao Informado.";
       $this->erro_campo = "v14_codacertid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->v14_certid = $v14_certid; 
       $this->v14_parcel = $v14_parcel; 
     if(($this->v14_certid == null) || ($this->v14_certid == "") ){ 
       $this->erro_sql = " Campo v14_certid nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->v14_parcel == null) || ($this->v14_parcel == "") ){ 
       $this->erro_sql = " Campo v14_parcel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acertter(
                                       v14_certid 
                                      ,v14_parcel 
                                      ,v14_vlrhis 
                                      ,v14_vlrcor 
                                      ,v14_vlrjur 
                                      ,v14_vlrmul 
                                      ,v14_codacertid 
                       )
                values (
                                $this->v14_certid 
                               ,$this->v14_parcel 
                               ,$this->v14_vlrhis 
                               ,$this->v14_vlrcor 
                               ,$this->v14_vlrjur 
                               ,$this->v14_vlrmul 
                               ,$this->v14_codacertid 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->v14_certid."-".$this->v14_parcel) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->v14_certid."-".$this->v14_parcel) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v14_certid."-".$this->v14_parcel;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v14_certid,$this->v14_parcel));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,531,'$this->v14_certid','I')");
       $resac = db_query("insert into db_acountkey values($acount,563,'$this->v14_parcel','I')");
       $resac = db_query("insert into db_acount values($acount,107,531,'','".AddSlashes(pg_result($resaco,0,'v14_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,107,563,'','".AddSlashes(pg_result($resaco,0,'v14_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,107,533,'','".AddSlashes(pg_result($resaco,0,'v14_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,107,534,'','".AddSlashes(pg_result($resaco,0,'v14_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,107,535,'','".AddSlashes(pg_result($resaco,0,'v14_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,107,536,'','".AddSlashes(pg_result($resaco,0,'v14_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,107,7631,'','".AddSlashes(pg_result($resaco,0,'v14_codacertid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v14_certid=null,$v14_parcel=null) { 
      $this->atualizacampos();
     $sql = " update acertter set ";
     $virgula = "";
     if(trim($this->v14_certid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_certid"])){ 
       $sql  .= $virgula." v14_certid = $this->v14_certid ";
       $virgula = ",";
       if(trim($this->v14_certid) == null ){ 
         $this->erro_sql = " Campo certidao nao Informado.";
         $this->erro_campo = "v14_certid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v14_parcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_parcel"])){ 
       $sql  .= $virgula." v14_parcel = $this->v14_parcel ";
       $virgula = ",";
       if(trim($this->v14_parcel) == null ){ 
         $this->erro_sql = " Campo parcelamento nao Informado.";
         $this->erro_campo = "v14_parcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v14_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrhis"])){ 
       $sql  .= $virgula." v14_vlrhis = $this->v14_vlrhis ";
       $virgula = ",";
       if(trim($this->v14_vlrhis) == null ){ 
         $this->erro_sql = " Campo valor historico nao Informado.";
         $this->erro_campo = "v14_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v14_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrcor"])){ 
       $sql  .= $virgula." v14_vlrcor = $this->v14_vlrcor ";
       $virgula = ",";
       if(trim($this->v14_vlrcor) == null ){ 
         $this->erro_sql = " Campo valor corrigido nao Informado.";
         $this->erro_campo = "v14_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v14_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrjur"])){ 
       $sql  .= $virgula." v14_vlrjur = $this->v14_vlrjur ";
       $virgula = ",";
       if(trim($this->v14_vlrjur) == null ){ 
         $this->erro_sql = " Campo valor dos juros nao Informado.";
         $this->erro_campo = "v14_vlrjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v14_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrmul"])){ 
       $sql  .= $virgula." v14_vlrmul = $this->v14_vlrmul ";
       $virgula = ",";
       if(trim($this->v14_vlrmul) == null ){ 
         $this->erro_sql = " Campo valor da multa nao Informado.";
         $this->erro_campo = "v14_vlrmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v14_codacertid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v14_codacertid"])){ 
       $sql  .= $virgula." v14_codacertid = $this->v14_codacertid ";
       $virgula = ",";
       if(trim($this->v14_codacertid) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "v14_codacertid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v14_certid!=null){
       $sql .= " v14_certid = $this->v14_certid";
     }
     if($v14_parcel!=null){
       $sql .= " and  v14_parcel = $this->v14_parcel";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v14_certid,$this->v14_parcel));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,531,'$this->v14_certid','A')");
         $resac = db_query("insert into db_acountkey values($acount,563,'$this->v14_parcel','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_certid"]))
           $resac = db_query("insert into db_acount values($acount,107,531,'".AddSlashes(pg_result($resaco,$conresaco,'v14_certid'))."','$this->v14_certid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_parcel"]))
           $resac = db_query("insert into db_acount values($acount,107,563,'".AddSlashes(pg_result($resaco,$conresaco,'v14_parcel'))."','$this->v14_parcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrhis"]))
           $resac = db_query("insert into db_acount values($acount,107,533,'".AddSlashes(pg_result($resaco,$conresaco,'v14_vlrhis'))."','$this->v14_vlrhis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,107,534,'".AddSlashes(pg_result($resaco,$conresaco,'v14_vlrcor'))."','$this->v14_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrjur"]))
           $resac = db_query("insert into db_acount values($acount,107,535,'".AddSlashes(pg_result($resaco,$conresaco,'v14_vlrjur'))."','$this->v14_vlrjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_vlrmul"]))
           $resac = db_query("insert into db_acount values($acount,107,536,'".AddSlashes(pg_result($resaco,$conresaco,'v14_vlrmul'))."','$this->v14_vlrmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v14_codacertid"]))
           $resac = db_query("insert into db_acount values($acount,107,7631,'".AddSlashes(pg_result($resaco,$conresaco,'v14_codacertid'))."','$this->v14_codacertid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v14_certid."-".$this->v14_parcel;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v14_certid."-".$this->v14_parcel;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v14_certid."-".$this->v14_parcel;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v14_certid=null,$v14_parcel=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v14_certid,$v14_parcel));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,531,'$v14_certid','E')");
         $resac = db_query("insert into db_acountkey values($acount,563,'$v14_parcel','E')");
         $resac = db_query("insert into db_acount values($acount,107,531,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_certid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,107,563,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,107,533,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_vlrhis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,107,534,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,107,535,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_vlrjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,107,536,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_vlrmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,107,7631,'','".AddSlashes(pg_result($resaco,$iresaco,'v14_codacertid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acertter
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v14_certid != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v14_certid = $v14_certid ";
        }
        if($v14_parcel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v14_parcel = $v14_parcel ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v14_certid."-".$v14_parcel;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v14_certid."-".$v14_parcel;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v14_certid."-".$v14_parcel;
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
        $this->erro_sql   = "Record Vazio na Tabela:acertter";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v14_certid=null,$v14_parcel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acertter ";
     $sql .= "      inner join termo  on  termo.v07_parcel = acertter.v14_parcel";
     $sql .= "      inner join acertid  on  acertid.v15_codigo = acertter.v14_codacertid";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = termo.v07_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = acertid.v15_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($v14_certid!=null ){
         $sql2 .= " where acertter.v14_certid = $v14_certid "; 
       } 
       if($v14_parcel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " acertter.v14_parcel = $v14_parcel "; 
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
   function sql_query_file ( $v14_certid=null,$v14_parcel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acertter ";
     $sql2 = "";
     if($dbwhere==""){
       if($v14_certid!=null ){
         $sql2 .= " where acertter.v14_certid = $v14_certid "; 
       } 
       if($v14_parcel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " acertter.v14_parcel = $v14_parcel "; 
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