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

//MODULO: Vacinas
//CLASSE DA ENTIDADE vac_sala
class cl_vac_sala { 
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
   var $vc01_i_codigo = 0; 
   var $vc01_i_unidade = 0; 
   var $vc01_c_nome = null; 
   var $vc01_c_descr = null; 
   var $vc01_i_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc01_i_codigo = int4 = c�digo 
                 vc01_i_unidade = int4 = Unidade 
                 vc01_c_nome = char(20) = Nome 
                 vc01_c_descr = char(100) = Descri��o 
                 vc01_i_situacao = int4 = Situa��o 
                 ";
   //funcao construtor da classe 
   function cl_vac_sala() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_sala"); 
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
       $this->vc01_i_codigo = ($this->vc01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc01_i_codigo"]:$this->vc01_i_codigo);
       $this->vc01_i_unidade = ($this->vc01_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["vc01_i_unidade"]:$this->vc01_i_unidade);
       $this->vc01_c_nome = ($this->vc01_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["vc01_c_nome"]:$this->vc01_c_nome);
       $this->vc01_c_descr = ($this->vc01_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["vc01_c_descr"]:$this->vc01_c_descr);
       $this->vc01_i_situacao = ($this->vc01_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc01_i_situacao"]:$this->vc01_i_situacao);
     }else{
       $this->vc01_i_codigo = ($this->vc01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc01_i_codigo"]:$this->vc01_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc01_i_codigo){ 
      $this->atualizacampos();
     if($this->vc01_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "vc01_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc01_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "vc01_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc01_c_descr == null ){ 
       $this->erro_sql = " Campo Descri��o nao Informado.";
       $this->erro_campo = "vc01_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc01_i_situacao == null ){ 
       $this->erro_sql = " Campo Situa��o nao Informado.";
       $this->erro_campo = "vc01_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc01_i_codigo == "" || $vc01_i_codigo == null ){
       $result = db_query("select nextval('vac_sala_vc01_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_sala_vc01_i_codigo_seq do campo: vc01_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc01_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_sala_vc01_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc01_i_codigo)){
         $this->erro_sql = " Campo vc01_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc01_i_codigo = $vc01_i_codigo; 
       }
     }
     if(($this->vc01_i_codigo == null) || ($this->vc01_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc01_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_sala(
                                       vc01_i_codigo 
                                      ,vc01_i_unidade 
                                      ,vc01_c_nome 
                                      ,vc01_c_descr 
                                      ,vc01_i_situacao 
                       )
                values (
                                $this->vc01_i_codigo 
                               ,$this->vc01_i_unidade 
                               ,'$this->vc01_c_nome' 
                               ,'$this->vc01_c_descr' 
                               ,$this->vc01_i_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cadastro de Salas ($this->vc01_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cadastro de Salas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cadastro de Salas ($this->vc01_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc01_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc01_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16783,'$this->vc01_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2955,16783,'','".AddSlashes(pg_result($resaco,0,'vc01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2955,16784,'','".AddSlashes(pg_result($resaco,0,'vc01_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2955,16785,'','".AddSlashes(pg_result($resaco,0,'vc01_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2955,16786,'','".AddSlashes(pg_result($resaco,0,'vc01_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2955,16788,'','".AddSlashes(pg_result($resaco,0,'vc01_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc01_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_sala set ";
     $virgula = "";
     if(trim($this->vc01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc01_i_codigo"])){ 
       $sql  .= $virgula." vc01_i_codigo = $this->vc01_i_codigo ";
       $virgula = ",";
       if(trim($this->vc01_i_codigo) == null ){ 
         $this->erro_sql = " Campo c�digo nao Informado.";
         $this->erro_campo = "vc01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc01_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc01_i_unidade"])){ 
       $sql  .= $virgula." vc01_i_unidade = $this->vc01_i_unidade ";
       $virgula = ",";
       if(trim($this->vc01_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "vc01_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc01_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc01_c_nome"])){ 
       $sql  .= $virgula." vc01_c_nome = '$this->vc01_c_nome' ";
       $virgula = ",";
       if(trim($this->vc01_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "vc01_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc01_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc01_c_descr"])){ 
       $sql  .= $virgula." vc01_c_descr = '$this->vc01_c_descr' ";
       $virgula = ",";
       if(trim($this->vc01_c_descr) == null ){ 
         $this->erro_sql = " Campo Descri��o nao Informado.";
         $this->erro_campo = "vc01_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc01_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc01_i_situacao"])){ 
       $sql  .= $virgula." vc01_i_situacao = $this->vc01_i_situacao ";
       $virgula = ",";
       if(trim($this->vc01_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situa��o nao Informado.";
         $this->erro_campo = "vc01_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc01_i_codigo!=null){
       $sql .= " vc01_i_codigo = $this->vc01_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc01_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16783,'$this->vc01_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc01_i_codigo"]) || $this->vc01_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2955,16783,'".AddSlashes(pg_result($resaco,$conresaco,'vc01_i_codigo'))."','$this->vc01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc01_i_unidade"]) || $this->vc01_i_unidade != "")
           $resac = db_query("insert into db_acount values($acount,2955,16784,'".AddSlashes(pg_result($resaco,$conresaco,'vc01_i_unidade'))."','$this->vc01_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc01_c_nome"]) || $this->vc01_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,2955,16785,'".AddSlashes(pg_result($resaco,$conresaco,'vc01_c_nome'))."','$this->vc01_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc01_c_descr"]) || $this->vc01_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2955,16786,'".AddSlashes(pg_result($resaco,$conresaco,'vc01_c_descr'))."','$this->vc01_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc01_i_situacao"]) || $this->vc01_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2955,16788,'".AddSlashes(pg_result($resaco,$conresaco,'vc01_i_situacao'))."','$this->vc01_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de Salas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de Salas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc01_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc01_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16783,'$vc01_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2955,16783,'','".AddSlashes(pg_result($resaco,$iresaco,'vc01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2955,16784,'','".AddSlashes(pg_result($resaco,$iresaco,'vc01_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2955,16785,'','".AddSlashes(pg_result($resaco,$iresaco,'vc01_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2955,16786,'','".AddSlashes(pg_result($resaco,$iresaco,'vc01_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2955,16788,'','".AddSlashes(pg_result($resaco,$iresaco,'vc01_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_sala
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc01_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc01_i_codigo = $vc01_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cadastro de Salas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc01_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cadastro de Salas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc01_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc01_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_sala";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_sala ";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = vac_sala.vc01_i_unidade";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm and  cgm.z01_numcgm = unidades.sd02_i_diretor";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "      left  join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "      left  join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "      left  join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "      left  join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "      left  join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "      left  join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "      left  join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql2 = "";
     if($dbwhere==""){
       if($vc01_i_codigo!=null ){
         $sql2 .= " where vac_sala.vc01_i_codigo = $vc01_i_codigo "; 
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
   function sql_query_file ( $vc01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_sala ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc01_i_codigo!=null ){
         $sql2 .= " where vac_sala.vc01_i_codigo = $vc01_i_codigo "; 
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