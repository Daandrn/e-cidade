<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: material
//CLASSE DA ENTIDADE matordemitem
class cl_matordemitem { 
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
   var $m52_codlanc = 0; 
   var $m52_codordem = 0; 
   var $m52_numemp = 0; 
   var $m52_sequen = 0; 
   var $m52_quant = 0; 
   var $m52_valor = 0; 
   var $m52_vlruni = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m52_codlanc = int8 = C�digo sequencial do lan�amento 
                 m52_codordem = int8 = C�digo da ordem de compra 
                 m52_numemp = int4 = N�mero 
                 m52_sequen = int4 = Sequencia 
                 m52_quant = float8 = Quantidade 
                 m52_valor = float8 = Valor 
                 m52_vlruni = float8 = valor unit�rio 
                 ";
   //funcao construtor da classe 
   function cl_matordemitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matordemitem"); 
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
       $this->m52_codlanc = ($this->m52_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_codlanc"]:$this->m52_codlanc);
       $this->m52_codordem = ($this->m52_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_codordem"]:$this->m52_codordem);
       $this->m52_numemp = ($this->m52_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_numemp"]:$this->m52_numemp);
       $this->m52_sequen = ($this->m52_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_sequen"]:$this->m52_sequen);
       $this->m52_quant = ($this->m52_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_quant"]:$this->m52_quant);
       $this->m52_valor = ($this->m52_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_valor"]:$this->m52_valor);
       $this->m52_vlruni = ($this->m52_vlruni == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_vlruni"]:$this->m52_vlruni);
     }else{
       $this->m52_codlanc = ($this->m52_codlanc == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_codlanc"]:$this->m52_codlanc);
     }
   }
   // funcao para inclusao
   function incluir ($m52_codlanc){ 
      $this->atualizacampos();
     if($this->m52_codordem == null ){ 
       $this->erro_sql = " Campo C�digo da ordem de compra nao Informado.";
       $this->erro_campo = "m52_codordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m52_numemp == null ){ 
       $this->erro_sql = " Campo N�mero nao Informado.";
       $this->erro_campo = "m52_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m52_sequen == null ){ 
       $this->erro_sql = " Campo Sequencia nao Informado.";
       $this->erro_campo = "m52_sequen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m52_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "m52_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m52_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "m52_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m52_vlruni == null ){ 
       $this->erro_sql = " Campo valor unit�rio nao Informado.";
       $this->erro_campo = "m52_vlruni";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m52_codlanc == "" || $m52_codlanc == null ){
       $result = db_query("select nextval('matordemitem_m52_codlanc_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matordemitem_m52_codlanc_seq do campo: m52_codlanc"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m52_codlanc = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matordemitem_m52_codlanc_seq");
       if(($result != false) && (pg_result($result,0,0) < $m52_codlanc)){
         $this->erro_sql = " Campo m52_codlanc maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m52_codlanc = $m52_codlanc; 
       }
     }
     if(($this->m52_codlanc == null) || ($this->m52_codlanc == "") ){ 
       $this->erro_sql = " Campo m52_codlanc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matordemitem(
                                       m52_codlanc 
                                      ,m52_codordem 
                                      ,m52_numemp 
                                      ,m52_sequen 
                                      ,m52_quant 
                                      ,m52_valor 
                                      ,m52_vlruni 
                       )
                values (
                                $this->m52_codlanc 
                               ,$this->m52_codordem 
                               ,$this->m52_numemp 
                               ,$this->m52_sequen 
                               ,$this->m52_quant 
                               ,$this->m52_valor 
                               ,$this->m52_vlruni 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da ordem de compra ($this->m52_codlanc) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da ordem de compra j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da ordem de compra ($this->m52_codlanc) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codlanc;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m52_codlanc));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6281,'$this->m52_codlanc','I')");
       $resac = db_query("insert into db_acount values($acount,1008,6281,'','".AddSlashes(pg_result($resaco,0,'m52_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008,6219,'','".AddSlashes(pg_result($resaco,0,'m52_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008,6220,'','".AddSlashes(pg_result($resaco,0,'m52_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008,6221,'','".AddSlashes(pg_result($resaco,0,'m52_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008,6222,'','".AddSlashes(pg_result($resaco,0,'m52_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008,6285,'','".AddSlashes(pg_result($resaco,0,'m52_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1008,7823,'','".AddSlashes(pg_result($resaco,0,'m52_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m52_codlanc=null) { 
      $this->atualizacampos();
     $sql = " update matordemitem set ";
     $virgula = "";
     if(trim($this->m52_codlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_codlanc"])){ 
       $sql  .= $virgula." m52_codlanc = $this->m52_codlanc ";
       $virgula = ",";
       if(trim($this->m52_codlanc) == null ){ 
         $this->erro_sql = " Campo C�digo sequencial do lan�amento nao Informado.";
         $this->erro_campo = "m52_codlanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_codordem"])){ 
       $sql  .= $virgula." m52_codordem = $this->m52_codordem ";
       $virgula = ",";
       if(trim($this->m52_codordem) == null ){ 
         $this->erro_sql = " Campo C�digo da ordem de compra nao Informado.";
         $this->erro_campo = "m52_codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_numemp"])){ 
       $sql  .= $virgula." m52_numemp = $this->m52_numemp ";
       $virgula = ",";
       if(trim($this->m52_numemp) == null ){ 
         $this->erro_sql = " Campo N�mero nao Informado.";
         $this->erro_campo = "m52_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_sequen"])){ 
       $sql  .= $virgula." m52_sequen = $this->m52_sequen ";
       $virgula = ",";
       if(trim($this->m52_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "m52_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_quant"])){ 
       $sql  .= $virgula." m52_quant = $this->m52_quant ";
       $virgula = ",";
       if(trim($this->m52_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "m52_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_valor"])){ 
       $sql  .= $virgula." m52_valor = $this->m52_valor ";
       $virgula = ",";
       if(trim($this->m52_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "m52_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_vlruni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_vlruni"])){ 
       $sql  .= $virgula." m52_vlruni = $this->m52_vlruni ";
       $virgula = ",";
       if(trim($this->m52_vlruni) == null ){ 
         $this->erro_sql = " Campo valor unit�rio nao Informado.";
         $this->erro_campo = "m52_vlruni";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m52_codlanc!=null){
       $sql .= " m52_codlanc = $this->m52_codlanc";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m52_codlanc));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6281,'$this->m52_codlanc','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_codlanc"]))
           $resac = db_query("insert into db_acount values($acount,1008,6281,'".AddSlashes(pg_result($resaco,$conresaco,'m52_codlanc'))."','$this->m52_codlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_codordem"]))
           $resac = db_query("insert into db_acount values($acount,1008,6219,'".AddSlashes(pg_result($resaco,$conresaco,'m52_codordem'))."','$this->m52_codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_numemp"]))
           $resac = db_query("insert into db_acount values($acount,1008,6220,'".AddSlashes(pg_result($resaco,$conresaco,'m52_numemp'))."','$this->m52_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_sequen"]))
           $resac = db_query("insert into db_acount values($acount,1008,6221,'".AddSlashes(pg_result($resaco,$conresaco,'m52_sequen'))."','$this->m52_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_quant"]))
           $resac = db_query("insert into db_acount values($acount,1008,6222,'".AddSlashes(pg_result($resaco,$conresaco,'m52_quant'))."','$this->m52_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_valor"]))
           $resac = db_query("insert into db_acount values($acount,1008,6285,'".AddSlashes(pg_result($resaco,$conresaco,'m52_valor'))."','$this->m52_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_vlruni"]))
           $resac = db_query("insert into db_acount values($acount,1008,7823,'".AddSlashes(pg_result($resaco,$conresaco,'m52_vlruni'))."','$this->m52_vlruni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da ordem de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codlanc;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da ordem de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codlanc;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codlanc;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m52_codlanc=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m52_codlanc));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6281,'$m52_codlanc','E')");
         $resac = db_query("insert into db_acount values($acount,1008,6281,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_codlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008,6219,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008,6220,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008,6221,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008,6222,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008,6285,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008,7823,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matordemitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m52_codlanc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m52_codlanc = $m52_codlanc ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da ordem de compra nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m52_codlanc;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da ordem de compra nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m52_codlanc;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m52_codlanc;
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
        $this->erro_sql   = "Record Vazio na Tabela:matordemitem";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitem ";
     
     $sql .= "      inner join empempitem  	on empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join matordem  	on matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join orcelemento  	on orcelemento.o56_codele = empempitem.e62_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  	on pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  	on empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join empempaut	on empempaut.e61_numemp = empempenho.e60_numemp";
     $sql .= "      inner join empautoriza 	on empempaut.e61_autori = empautoriza.e54_autori ";
     $sql .= "      inner join cgm  		on cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  	on db_depart.coddepto = matordem.m51_depto";
     
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc "; 
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
   function sql_query_anulado( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitem ";
     
     $sql .= "      inner join empempitem  	on empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join matordem  	on matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join orcelemento  	on orcelemento.o56_codele = empempitem.e62_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  	on pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  	on empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join empempaut	on empempaut.e61_numemp = empempenho.e60_numemp";
     $sql .= "      inner join empautoriza 	on empempaut.e61_autori = empautoriza.e54_autori ";
     $sql .= "      inner join cgm  		on cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join db_depart  	on db_depart.coddepto = matordem.m51_depto";
     $sql .="       left join matordemanu on m53_codordem=matordemitem.m52_codordem";       
     
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc "; 
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
   function sql_query_file ( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc "; 
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
   function sql_query_ordcons ( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitem ";
     $sql .= "      inner join empempitem            on empempitem.e62_numemp           = matordemitem.m52_numemp";
     $sql .= "                                      and empempitem.e62_sequen           = matordemitem.m52_sequen";
     $sql .= "      inner join empempenho            on empempenho.e60_numemp           = empempitem.e62_numemp";
     $sql .= "      inner join matordem              on matordem.m51_codordem           = matordemitem.m52_codordem";
     $sql .= "      inner join cgm    	             on cgm.z01_numcgm                  = matordem.m51_numcgm";
     $sql .= "      inner join db_depart             on db_depart.coddepto              = matordem.m51_depto";
     $sql .= "      inner join orcelemento           on orcelemento.o56_codele          = empempitem.e62_codele"; 
     $sql .= "                                      and orcelemento.o56_anousu          = empempenho.e60_anousu";
     $sql .= "      inner join pcmater               on pcmater.pc01_codmater           = empempitem.e62_item";
     $sql .= "      left  join empempaut             on empempaut.e61_numemp            = empempenho.e60_numemp";
     $sql .= "      left  join empautoriza           on empempaut.e61_autori            = empautoriza.e54_autori";
     $sql .= "      left  join empautitem            on empempaut.e61_autori            = empautitem.e55_autori";
     $sql .= "                                       and empempitem.e62_sequen          = empautitem.e55_sequen ";
     $sql .= "      left  join empautitempcprocitem  on empautitempcprocitem.e73_autori = empautitem.e55_autori";
     $sql .= "                                      and empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
     $sql .= "      left  join pcprocitem            on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
     $sql .= "	    left  join solicitem             on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     //$sql .= "      left  join liclicitem            on liclicitem.l21_codpcprocitem    = empempitem.e62_sequen";
     $sql .= "      left  join liclicitem            on liclicitem.l21_codpcprocitem    = empautitempcprocitem.e73_pcprocitem";
     $sql .= "	    left  join pcorcamitemlic        on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo";
     $sql .= "      left  join pcorcamjulg           on pcorcamjulg.pc24_orcamitem      = pcorcamitemlic.pc26_orcamitem"; 
     $sql .= "                                      and pcorcamjulg.pc24_pontuacao      = 1";
     $sql .= "      left  join pcorcamval            on pcorcamval.pc23_orcamitem       = pcorcamjulg.pc24_orcamitem";
     $sql .= "                                      and pcorcamval.pc23_orcamforne      = pcorcamjulg.pc24_orcamforne";     
     $sql .="       inner join pctipocompra         on empempenho.e60_codcom            = pctipocompra.pc50_codcom" ;    
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc "; 
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
   function sql_query_ordem($m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordemitem ";
     $sql .= "      inner join matordem         on matordem.m51_codordem = matordemitem.m52_codordem";
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc ";
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
   function sql_query_servico ( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordemitem ";
    $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp
                                          and  empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empempitem.e62_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
     $sql .= "      inner join pcsubgrupo on pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join pctipo on pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";

     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc ";
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
	function sql_query_completo ( $m52_codlanc=null, $campos="*", $ordem=null, $dbwhere=""){
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
		$sql .= "	from matordemitem ";
		$sql .= "   	inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp
                                          and  empempitem.e62_sequen = matordemitem.m52_sequen";
		$sql .= "      	inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
		$sql .= "      	inner join orcelemento  on  orcelemento.o56_codele = empempitem.e62_codele
		 					AND orcelemento.o56_anousu = ".db_getsession("DB_anousu");
		$sql .= "      	inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
		$sql .= "      	inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
		$sql .= "      	inner join cgm  on  cgm.z01_numcgm = matordem.m51_numcgm";
		$sql .= "      	inner join pcsubgrupo on pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
		$sql .= "      	inner join pctipo on pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo ";
		$sql .= "		left join empempaut ON empempaut.e61_numemp = empempenho.e60_numemp";
		$sql .= " 		left join empautoriza ON empempaut.e61_autori = empautoriza.e54_autori";
		$sql .= " 		LEFT JOIN empautitem ON empempaut.e61_autori = empautitem.e55_autori
		 					AND empempitem.e62_sequen = empautitem.e55_sequen";
		$sql .= " 		LEFT JOIN empautitempcprocitem pcprocitemaut ON pcprocitemaut.e73_autori = empautitem.e55_autori
		 					AND pcprocitemaut.e73_sequen = empautitem.e55_sequen";
		$sql .= " 		LEFT JOIN pcprocitem ON pcprocitem.pc81_codprocitem = pcprocitemaut.e73_pcprocitem";
		$sql .= " 		LEFT JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
		$sql .= " 		LEFT JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo";
		$sql .= " 		LEFT JOIN matunid ON solicitemunid.pc17_unid = matunid.m61_codmatunid";

		$sql2 = "";
		if($dbwhere==""){
			if($m52_codlanc!=null ){
				$sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc ";
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
  function sql_query_emiteordem ( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matordemitem ";
     $sql .= "      inner join empempitem            on empempitem.e62_numemp           = matordemitem.m52_numemp";
     $sql .= "                                      and empempitem.e62_sequen           = matordemitem.m52_sequen";
     $sql .= "      inner join empempenho            on empempenho.e60_numemp           = empempitem.e62_numemp";
     $sql .= "      inner join matordem              on matordem.m51_codordem           = matordemitem.m52_codordem";
     $sql .= "      inner join cgm                   on cgm.z01_numcgm                  = matordem.m51_numcgm";
     $sql .= "      inner join db_depart             on db_depart.coddepto              = matordem.m51_depto";
     $sql .= "      inner join orcelemento           on orcelemento.o56_codele          = empempitem.e62_codele"; 
     $sql .= "                                      and orcelemento.o56_anousu          = empempenho.e60_anousu";
     $sql .= "      inner join pcmater               on pcmater.pc01_codmater           = empempitem.e62_item";
     $sql .= "      left  join empempaut             on empempaut.e61_numemp            = empempenho.e60_numemp";
     $sql .= "      left  join empautoriza           on empempaut.e61_autori            = empautoriza.e54_autori";
     $sql .= "      left  join empautitem            on empempaut.e61_autori            = empautitem.e55_autori";
     $sql .= "                                       and empempitem.e62_sequen          = empautitem.e55_sequen ";
     
     // verifica��o de empenhos de registro de preco
  
     $sql .= "       left join empautitempcprocitem        on empautitempcprocitem.e73_autori      = empautitem.e55_autori ";                
     $sql .= "                                            and empautitempcprocitem.e73_sequen      = empautitem.e55_sequen ";                       
     $sql .= "       left join pcprocitem rp               on rp.pc81_codprocitem                  = empautitempcprocitem.e73_pcprocitem ";
     $sql .= "       left join solicitem solrp             on solrp.pc11_codigo                    = rp.pc81_solicitem ";
     $sql .= "       left join solicita                    on solicita.pc10_numero                 = solrp.pc11_numero ";
     $sql .= "       left join solicitemvinculo            on solicitemvinculo.pc55_solicitemfilho = solrp.pc11_codigo ";   
     $sql .= "       left join solicitem compilacao        on solicitemvinculo.pc55_solicitempai   = compilacao.pc11_codigo ";
     $sql .= "       left join pcprocitem proccompilacao   on pc55_solicitempai                    = proccompilacao.pc81_solicitem "; 
     $sql .= "       left join liclicitem licitarp         on proccompilacao.pc81_codprocitem      = licitarp.l21_codpcprocitem ";
     $sql .= "       left join pcorcamitemlic pcitemrp     on licitarp.l21_codigo                  = pcitemrp.pc26_liclicitem ";
     $sql .= "       left join pcorcamjulg julgrp          on pcitemrp.pc26_orcamitem              = julgrp.pc24_orcamitem ";
     $sql .= "                                            and julgrp.pc24_pontuacao                = 1 ";
     $sql .= "       left join pcorcamval pcitemvalrp      on julgrp.pc24_orcamitem                = pcitemvalrp.pc23_orcamitem ";
     $sql .= "                                            and julgrp.pc24_orcamforne               = pcitemvalrp.pc23_orcamforne ";
     
                                                             
    //verficaao de empenhos gerados a partir de licitacao normal.
                                         
     $sql .= "       left join empautitempcprocitem  pcprocitemaut  on pcprocitemaut.e73_autori        = empautitem.e55_autori ";                
     $sql .= "                                                     and pcprocitemaut.e73_sequen        = empautitem.e55_sequen ";                    
     $sql .= "       left join pcprocitem                           on pcprocitem.pc81_codprocitem     = pcprocitemaut.e73_pcprocitem ";
     $sql .= "       left join solicitem                            on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem ";
     $sql .= "       left join liclicitem                           on liclicitem.l21_codpcprocitem    = pcprocitemaut.e73_pcprocitem ";
     $sql .= "       left join pcorcamitemlic                       on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo ";
     $sql .= "       left join pcorcamjulg                          on pcorcamjulg.pc24_orcamitem      = pcorcamitemlic.pc26_orcamitem "; 
     $sql .= "                                                     and pcorcamjulg.pc24_pontuacao      = 1 ";
     $sql .= "       left join pcorcamval                           on pcorcamval.pc23_orcamitem       = pcorcamjulg.pc24_orcamitem "; 
     $sql .= "                                                     and pcorcamval.pc23_orcamforne      = pcorcamjulg.pc24_orcamforne ";


    $sql .= "left join pcprocitem sl ON sl.pc81_codprocitem = empautitempcprocitem.e73_pcprocitem

left join pcorcamitemproc on pc31_pcprocitem = 	sl.pc81_codprocitem

LEFT JOIN pcorcamjulg julgsl ON pcorcamitemproc.pc31_orcamitem = julgsl.pc24_orcamitem
AND julgsl.pc24_pontuacao = 1

left join pcorcamitem pcorcamitemsl on pcorcamitemsl.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem

left join pcorcamval pcorcamvalsl on pcorcamvalsl.pc23_orcamitem = pcorcamitemsl.pc22_orcamitem
and pcorcamvalsl.pc23_orcamitem  = julgsl.pc24_orcamitem
and pcorcamvalsl.pc23_orcamforne = julgsl.pc24_orcamforne";

     $sql .="       inner join pctipocompra                on empempenho.e60_codcom                = pctipocompra.pc50_codcom" ;


     $sql .="       left join solicitemunid                on solicitemunid.pc17_codigo                = solicitem.pc11_codigo     " ;
     $sql .="       left join matunid                on solicitemunid.pc17_unid                = matunid.m61_codmatunid" ;
     $sql .="       left join matunid matunidaut               on empautitem.e55_unid                = matunidaut.m61_codmatunid" ;
     $sql .="       left join matunid matunidsol               on solicitemunid.pc17_unid                = matunidsol.m61_codmatunid" ;

/**
 * Adicionado para buscar Marca do processo de compra seguindo pelo acordo
 */
$sql .="       LEFT JOIN acordoitemexecutadoempautitem ON acordoitemexecutadoempautitem.ac19_autori = empautitem.e55_autori";
$sql .="       AND acordoitemexecutadoempautitem.ac19_sequen = empautitem.e55_sequen";
$sql .="       LEFT JOIN acordoitemexecutado ON acordoitemexecutadoempautitem.ac19_acordoitemexecutado = acordoitemexecutado.ac29_sequencial";

$sql .="       LEFT JOIN acordoempempitem ON acordoempempitem.ac44_empempitem = empempitem.e62_sequencial";
$sql .="       LEFT JOIN acordoitem ON acordoitem.ac20_sequencial = acordoitemexecutado.ac29_acordoitem ";
$sql .="       AND acordoitem.ac20_pcmater = pcmater.pc01_codmater";
$sql .="       LEFT JOIN acordoliclicitem ON acordoliclicitem.ac24_acordoitem=acordoitem.ac20_sequencial";
$sql .="       LEFT JOIN liclicitem licitaac ON licitaac.l21_codigo = acordoliclicitem.ac24_liclicitem";
$sql .="       LEFT JOIN pcorcamitemlic pcitemac ON pcitemac.pc26_liclicitem=licitaac.l21_codigo";
$sql .="       LEFT JOIN pcorcamitem pcorcamitemac ON pcorcamitemac.pc22_orcamitem=pcitemac.pc26_orcamitem";

$sql .="       LEFT JOIN pcorcamjulg pcorcamjulgac ON pcorcamjulgac.pc24_orcamitem=pcorcamitemac.pc22_orcamitem";
$sql .="       AND pcorcamjulgac.pc24_pontuacao = 1";
$sql .="       LEFT JOIN pcorcamval pcorcamvalac ON pcorcamvalac.pc23_orcamitem=pcorcamjulgac.pc24_orcamitem";
$sql .="       AND pcorcamvalac.pc23_orcamforne = pcorcamjulgac.pc24_orcamforne";

     $sql2 = "";
     if($dbwhere==""){
       if($m52_codlanc!=null ){
         $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc "; 
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
  
  /**
   * query para retornar entrada dos itens da ordem de compra
   * @param string $m52_codlanc
   * @param string $campos
   * @param string $ordem
   * @param string $dbwhere
   * @return string
   */
  function sql_query_entradas ( $m52_codlanc = null, $campos = "*", $ordem = null, $dbwhere = ""){
    
    $sql = "select ";
    
    if($campos != "*" ){
      
      $campos_sql = explode("#",$campos);
      $virgula    = "";
      
      for($i = 0; $i < sizeof($campos_sql); $i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
      
    } else {
      $sql .= $campos;
    }
    $sql .= " from matordemitem ";
    $sql .= "      inner join matestoqueitemoc 	on matordemitem.m52_codlanc               = matestoqueitemoc.m73_codmatordemitem ";
    $sql .= "      inner join matestoqueitem  	on matestoqueitemoc.m73_codmatestoqueitem = matestoqueitem.m71_codlanc ";
    $sql .= "      inner join matestoqueinimei  on matestoqueitem.m71_codlanc             = matestoqueinimei.m82_matestoqueitem";
    $sql .= "      inner join matestoqueini  	  on matestoqueinimei.m82_matestoqueini     = matestoqueini.m80_codigo";
    $sql .= "      inner join matestoque        on matestoqueitem.m71_codmatestoque       = matestoque.m70_codigo";
    $sql .= "      inner join matestoquetipo    on matestoqueini.m80_codtipo              = matestoquetipo.m81_codtipo ";
    $sql2 = "";
    if($dbwhere == "") {
      
      if($m52_codlanc != null ){
        $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc ";
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
  
  function sql_queryItemEmpenho ( $m52_codlanc=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matordemitem ";
    $sql .= "   inner join empempitem  	   on empempitem.e62_numemp    = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
    $sql .= "    left join matordemitemanu on matordemitem.m52_codlanc = matordemitemanu.m36_matordemitem ";
    $sql2 = "";
    if($dbwhere==""){
      if($m52_codlanc!=null ){
        $sql2 .= " where matordemitem.m52_codlanc = $m52_codlanc ";
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