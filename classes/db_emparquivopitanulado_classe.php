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

//MODULO: Empenho
//CLASSE DA ENTIDADE emparquivopitanulado
class cl_emparquivopitanulado { 
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
   var $e16_sequencial = 0; 
   var $e16_idusuario = 0; 
   var $e16_emparquivopit = 0; 
   var $e16_dtanulacao_dia = null; 
   var $e16_dtanulacao_mes = null; 
   var $e16_dtanulacao_ano = null; 
   var $e16_dtanulacao = null; 
   var $e16_horaanulacao = null; 
   var $e16_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e16_sequencial = int4 = C�digo Sequencial 
                 e16_idusuario = int4 = C�digo do Usu�rio 
                 e16_emparquivopit = int4 = C�digo do Arquivo 
                 e16_dtanulacao = date = data da Anula��o 
                 e16_horaanulacao = char(5) = Hora 
                 e16_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_emparquivopitanulado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("emparquivopitanulado"); 
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
       $this->e16_sequencial = ($this->e16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_sequencial"]:$this->e16_sequencial);
       $this->e16_idusuario = ($this->e16_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_idusuario"]:$this->e16_idusuario);
       $this->e16_emparquivopit = ($this->e16_emparquivopit == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_emparquivopit"]:$this->e16_emparquivopit);
       if($this->e16_dtanulacao == ""){
         $this->e16_dtanulacao_dia = ($this->e16_dtanulacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao_dia"]:$this->e16_dtanulacao_dia);
         $this->e16_dtanulacao_mes = ($this->e16_dtanulacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao_mes"]:$this->e16_dtanulacao_mes);
         $this->e16_dtanulacao_ano = ($this->e16_dtanulacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao_ano"]:$this->e16_dtanulacao_ano);
         if($this->e16_dtanulacao_dia != ""){
            $this->e16_dtanulacao = $this->e16_dtanulacao_ano."-".$this->e16_dtanulacao_mes."-".$this->e16_dtanulacao_dia;
         }
       }
       $this->e16_horaanulacao = ($this->e16_horaanulacao == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_horaanulacao"]:$this->e16_horaanulacao);
       $this->e16_motivo = ($this->e16_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_motivo"]:$this->e16_motivo);
     }else{
       $this->e16_sequencial = ($this->e16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e16_sequencial"]:$this->e16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e16_sequencial){ 
      $this->atualizacampos();
     if($this->e16_idusuario == null ){ 
       $this->erro_sql = " Campo C�digo do Usu�rio nao Informado.";
       $this->erro_campo = "e16_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e16_emparquivopit == null ){ 
       $this->erro_sql = " Campo C�digo do Arquivo nao Informado.";
       $this->erro_campo = "e16_emparquivopit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e16_dtanulacao == null ){ 
       $this->erro_sql = " Campo data da Anula��o nao Informado.";
       $this->erro_campo = "e16_dtanulacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e16_horaanulacao == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "e16_horaanulacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e16_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "e16_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e16_sequencial == "" || $e16_sequencial == null ){
       $result = db_query("select nextval('emparquivopitanulado_e16_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: emparquivopitanulado_e16_sequencial_seq do campo: e16_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e16_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from emparquivopitanulado_e16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e16_sequencial)){
         $this->erro_sql = " Campo e16_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e16_sequencial = $e16_sequencial; 
       }
     }
     if(($this->e16_sequencial == null) || ($this->e16_sequencial == "") ){ 
       $this->erro_sql = " Campo e16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into emparquivopitanulado(
                                       e16_sequencial 
                                      ,e16_idusuario 
                                      ,e16_emparquivopit 
                                      ,e16_dtanulacao 
                                      ,e16_horaanulacao 
                                      ,e16_motivo 
                       )
                values (
                                $this->e16_sequencial 
                               ,$this->e16_idusuario 
                               ,$this->e16_emparquivopit 
                               ,".($this->e16_dtanulacao == "null" || $this->e16_dtanulacao == ""?"null":"'".$this->e16_dtanulacao."'")." 
                               ,'$this->e16_horaanulacao' 
                               ,'$this->e16_motivo' 
                      )";
                      
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivos do pit anulados ($this->e16_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivos do pit anulados j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivos do pit anulados ($this->e16_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e16_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e16_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14673,'$this->e16_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2581,14673,'','".AddSlashes(pg_result($resaco,0,'e16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2581,14674,'','".AddSlashes(pg_result($resaco,0,'e16_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2581,14677,'','".AddSlashes(pg_result($resaco,0,'e16_emparquivopit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2581,14675,'','".AddSlashes(pg_result($resaco,0,'e16_dtanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2581,14676,'','".AddSlashes(pg_result($resaco,0,'e16_horaanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2581,14678,'','".AddSlashes(pg_result($resaco,0,'e16_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e16_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update emparquivopitanulado set ";
     $virgula = "";
     if(trim($this->e16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e16_sequencial"])){ 
       $sql  .= $virgula." e16_sequencial = $this->e16_sequencial ";
       $virgula = ",";
       if(trim($this->e16_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "e16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e16_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e16_idusuario"])){ 
       $sql  .= $virgula." e16_idusuario = $this->e16_idusuario ";
       $virgula = ",";
       if(trim($this->e16_idusuario) == null ){ 
         $this->erro_sql = " Campo C�digo do Usu�rio nao Informado.";
         $this->erro_campo = "e16_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e16_emparquivopit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e16_emparquivopit"])){ 
       $sql  .= $virgula." e16_emparquivopit = $this->e16_emparquivopit ";
       $virgula = ",";
       if(trim($this->e16_emparquivopit) == null ){ 
         $this->erro_sql = " Campo C�digo do Arquivo nao Informado.";
         $this->erro_campo = "e16_emparquivopit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e16_dtanulacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao_dia"] !="") ){ 
       $sql  .= $virgula." e16_dtanulacao = '$this->e16_dtanulacao' ";
       $virgula = ",";
       if(trim($this->e16_dtanulacao) == null ){ 
         $this->erro_sql = " Campo data da Anula��o nao Informado.";
         $this->erro_campo = "e16_dtanulacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao_dia"])){ 
         $sql  .= $virgula." e16_dtanulacao = null ";
         $virgula = ",";
         if(trim($this->e16_dtanulacao) == null ){ 
           $this->erro_sql = " Campo data da Anula��o nao Informado.";
           $this->erro_campo = "e16_dtanulacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e16_horaanulacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e16_horaanulacao"])){ 
       $sql  .= $virgula." e16_horaanulacao = '$this->e16_horaanulacao' ";
       $virgula = ",";
       if(trim($this->e16_horaanulacao) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "e16_horaanulacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e16_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e16_motivo"])){ 
       $sql  .= $virgula." e16_motivo = '$this->e16_motivo' ";
       $virgula = ",";
       if(trim($this->e16_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "e16_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e16_sequencial!=null){
       $sql .= " e16_sequencial = $this->e16_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e16_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14673,'$this->e16_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e16_sequencial"]) || $this->e16_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2581,14673,'".AddSlashes(pg_result($resaco,$conresaco,'e16_sequencial'))."','$this->e16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e16_idusuario"]) || $this->e16_idusuario != "")
           $resac = db_query("insert into db_acount values($acount,2581,14674,'".AddSlashes(pg_result($resaco,$conresaco,'e16_idusuario'))."','$this->e16_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e16_emparquivopit"]) || $this->e16_emparquivopit != "")
           $resac = db_query("insert into db_acount values($acount,2581,14677,'".AddSlashes(pg_result($resaco,$conresaco,'e16_emparquivopit'))."','$this->e16_emparquivopit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e16_dtanulacao"]) || $this->e16_dtanulacao != "")
           $resac = db_query("insert into db_acount values($acount,2581,14675,'".AddSlashes(pg_result($resaco,$conresaco,'e16_dtanulacao'))."','$this->e16_dtanulacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e16_horaanulacao"]) || $this->e16_horaanulacao != "")
           $resac = db_query("insert into db_acount values($acount,2581,14676,'".AddSlashes(pg_result($resaco,$conresaco,'e16_horaanulacao'))."','$this->e16_horaanulacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e16_motivo"]) || $this->e16_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2581,14678,'".AddSlashes(pg_result($resaco,$conresaco,'e16_motivo'))."','$this->e16_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos do pit anulados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e16_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos do pit anulados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e16_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e16_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14673,'$e16_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2581,14673,'','".AddSlashes(pg_result($resaco,$iresaco,'e16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2581,14674,'','".AddSlashes(pg_result($resaco,$iresaco,'e16_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2581,14677,'','".AddSlashes(pg_result($resaco,$iresaco,'e16_emparquivopit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2581,14675,'','".AddSlashes(pg_result($resaco,$iresaco,'e16_dtanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2581,14676,'','".AddSlashes(pg_result($resaco,$iresaco,'e16_horaanulacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2581,14678,'','".AddSlashes(pg_result($resaco,$iresaco,'e16_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from emparquivopitanulado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e16_sequencial = $e16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos do pit anulados nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e16_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos do pit anulados nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e16_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:emparquivopitanulado";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emparquivopitanulado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = emparquivopitanulado.e16_idusuario";
     $sql .= "      inner join emparquivopit  on  emparquivopit.e14_sequencial = emparquivopitanulado.e16_emparquivopit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = emparquivopit.e14_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($e16_sequencial!=null ){
         $sql2 .= " where emparquivopitanulado.e16_sequencial = $e16_sequencial "; 
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
   function sql_query_file ( $e16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from emparquivopitanulado ";
     $sql2 = "";
     if($dbwhere==""){
       if($e16_sequencial!=null ){
         $sql2 .= " where emparquivopitanulado.e16_sequencial = $e16_sequencial "; 
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