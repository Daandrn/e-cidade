<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: empenho
//CLASSE DA ENTIDADE empagemovdetalhetransmissao
class cl_empagemovdetalhetransmissao { 
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
   var $e74_sequencial = 0; 
   var $e74_empagemov = 0; 
   var $e74_codigodebarra = null; 
   var $e74_valornominal = 0; 
   var $e74_datavencimento_dia = null; 
   var $e74_datavencimento_mes = null; 
   var $e74_datavencimento_ano = null; 
   var $e74_datavencimento = null; 
   var $e74_valorjuros = 0; 
   var $e74_valordesconto = 0; 
   var $e74_tipofatura = 0; 
   var $e74_linhadigitavel = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e74_sequencial = int4 = sequencial empagemovdetalhetransmissao 
                 e74_empagemov = int4 = C�digo do Movimento 
                 e74_codigodebarra = varchar(100) = C�digo de Barras 
                 e74_valornominal = float8 = Valor Nominal 
                 e74_datavencimento = date = Data do Vencimento 
                 e74_valorjuros = float8 = Valor de Juros 
                 e74_valordesconto = float8 = Valor de Desconto 
                 e74_tipofatura = int4 = Tipo de Fatura 
                 e74_linhadigitavel = varchar(100) = Linha Digit�vel 
                 ";
   //funcao construtor da classe 
   function cl_empagemovdetalhetransmissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagemovdetalhetransmissao"); 
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
       $this->e74_sequencial = ($this->e74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_sequencial"]:$this->e74_sequencial);
       $this->e74_empagemov = ($this->e74_empagemov == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_empagemov"]:$this->e74_empagemov);
       $this->e74_codigodebarra = ($this->e74_codigodebarra == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_codigodebarra"]:$this->e74_codigodebarra);
       $this->e74_valornominal = ($this->e74_valornominal == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_valornominal"]:$this->e74_valornominal);
       if($this->e74_datavencimento == ""){
         $this->e74_datavencimento_dia = ($this->e74_datavencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_datavencimento_dia"]:$this->e74_datavencimento_dia);
         $this->e74_datavencimento_mes = ($this->e74_datavencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_datavencimento_mes"]:$this->e74_datavencimento_mes);
         $this->e74_datavencimento_ano = ($this->e74_datavencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_datavencimento_ano"]:$this->e74_datavencimento_ano);
         if($this->e74_datavencimento_dia != ""){
            $this->e74_datavencimento = $this->e74_datavencimento_ano."-".$this->e74_datavencimento_mes."-".$this->e74_datavencimento_dia;
         }
       }
       $this->e74_valorjuros = ($this->e74_valorjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_valorjuros"]:$this->e74_valorjuros);
       $this->e74_valordesconto = ($this->e74_valordesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_valordesconto"]:$this->e74_valordesconto);
       $this->e74_tipofatura = ($this->e74_tipofatura == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_tipofatura"]:$this->e74_tipofatura);
       $this->e74_linhadigitavel = ($this->e74_linhadigitavel == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_linhadigitavel"]:$this->e74_linhadigitavel);
     }else{
       $this->e74_sequencial = ($this->e74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e74_sequencial"]:$this->e74_sequencial);
     }
   }
   // funcao para Inclus�o
   function incluir ($e74_sequencial){ 
      $this->atualizacampos();
     if($this->e74_empagemov == null ){ 
       $this->erro_sql = " Campo C�digo do Movimento n�o informado.";
       $this->erro_campo = "e74_empagemov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e74_valornominal == null ){ 
       $this->e74_valornominal = "0";
     }
     if($this->e74_datavencimento == null ){ 
       $this->e74_datavencimento = "null";
     }
     if($this->e74_valorjuros == null ){ 
       $this->e74_valorjuros = "0";
     }
     if($this->e74_valordesconto == null ){ 
       $this->e74_valordesconto = "0";
     }
     if($this->e74_tipofatura == null ){ 
       $this->e74_tipofatura = "0";
     }
     if($e74_sequencial == "" || $e74_sequencial == null ){
       $result = db_query("select nextval('empagemovdetalhetransmissao_e74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagemovdetalhetransmissao_e74_sequencial_seq do campo: e74_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empagemovdetalhetransmissao_e74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e74_sequencial)){
         $this->erro_sql = " Campo e74_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e74_sequencial = $e74_sequencial; 
       }
     }
     if(($this->e74_sequencial == null) || ($this->e74_sequencial == "") ){ 
       $this->erro_sql = " Campo e74_sequencial n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagemovdetalhetransmissao(
                                       e74_sequencial 
                                      ,e74_empagemov 
                                      ,e74_codigodebarra 
                                      ,e74_valornominal 
                                      ,e74_datavencimento 
                                      ,e74_valorjuros 
                                      ,e74_valordesconto 
                                      ,e74_tipofatura 
                                      ,e74_linhadigitavel 
                       )
                values (
                                $this->e74_sequencial 
                               ,$this->e74_empagemov 
                               ,'$this->e74_codigodebarra' 
                               ,$this->e74_valornominal 
                               ,".($this->e74_datavencimento == "null" || $this->e74_datavencimento == ""?"null":"'".$this->e74_datavencimento."'")." 
                               ,$this->e74_valorjuros 
                               ,$this->e74_valordesconto 
                               ,$this->e74_tipofatura 
                               ,'$this->e74_linhadigitavel' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Detalhamentos de movimento com tipo de transmiss�o ($this->e74_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Detalhamentos de movimento com tipo de transmiss�o j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Detalhamentos de movimento com tipo de transmiss�o ($this->e74_sequencial) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e74_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e74_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20057,'$this->e74_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3595,20057,'','".AddSlashes(pg_result($resaco,0,'e74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20058,'','".AddSlashes(pg_result($resaco,0,'e74_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20059,'','".AddSlashes(pg_result($resaco,0,'e74_codigodebarra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20060,'','".AddSlashes(pg_result($resaco,0,'e74_valornominal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20061,'','".AddSlashes(pg_result($resaco,0,'e74_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20062,'','".AddSlashes(pg_result($resaco,0,'e74_valorjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20063,'','".AddSlashes(pg_result($resaco,0,'e74_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,20133,'','".AddSlashes(pg_result($resaco,0,'e74_tipofatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3595,21278,'','".AddSlashes(pg_result($resaco,0,'e74_linhadigitavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($e74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empagemovdetalhetransmissao set ";
     $virgula = "";
     if(trim($this->e74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_sequencial"])){ 
       $sql  .= $virgula." e74_sequencial = $this->e74_sequencial ";
       $virgula = ",";
       if(trim($this->e74_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial empagemovdetalhetransmissao n�o informado.";
         $this->erro_campo = "e74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e74_empagemov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_empagemov"])){ 
       $sql  .= $virgula." e74_empagemov = $this->e74_empagemov ";
       $virgula = ",";
       if(trim($this->e74_empagemov) == null ){ 
         $this->erro_sql = " Campo C�digo do Movimento n�o informado.";
         $this->erro_campo = "e74_empagemov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e74_codigodebarra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_codigodebarra"])){ 
       $sql  .= $virgula." e74_codigodebarra = '$this->e74_codigodebarra' ";
       $virgula = ",";
     }
     if(trim($this->e74_valornominal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_valornominal"])){ 
        if(trim($this->e74_valornominal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e74_valornominal"])){ 
           $this->e74_valornominal = "0" ; 
        } 
       $sql  .= $virgula." e74_valornominal = $this->e74_valornominal ";
       $virgula = ",";
     }
     if(trim($this->e74_datavencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_datavencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e74_datavencimento_dia"] !="") ){ 
       $sql  .= $virgula." e74_datavencimento = '$this->e74_datavencimento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e74_datavencimento_dia"])){ 
         $sql  .= $virgula." e74_datavencimento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->e74_valorjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_valorjuros"])){ 
        if(trim($this->e74_valorjuros)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e74_valorjuros"])){ 
           $this->e74_valorjuros = "0" ; 
        } 
       $sql  .= $virgula." e74_valorjuros = $this->e74_valorjuros ";
       $virgula = ",";
     }
     if(trim($this->e74_valordesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_valordesconto"])){ 
        if(trim($this->e74_valordesconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e74_valordesconto"])){ 
           $this->e74_valordesconto = "0" ; 
        } 
       $sql  .= $virgula." e74_valordesconto = $this->e74_valordesconto ";
       $virgula = ",";
     }
     if(trim($this->e74_tipofatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_tipofatura"])){ 
        if(trim($this->e74_tipofatura)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e74_tipofatura"])){ 
           $this->e74_tipofatura = "0" ; 
        } 
       $sql  .= $virgula." e74_tipofatura = $this->e74_tipofatura ";
       $virgula = ",";
     }
     if(trim($this->e74_linhadigitavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e74_linhadigitavel"])){ 
       $sql  .= $virgula." e74_linhadigitavel = '$this->e74_linhadigitavel' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e74_sequencial!=null){
       $sql .= " e74_sequencial = $this->e74_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e74_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20057,'$this->e74_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_sequencial"]) || $this->e74_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3595,20057,'".AddSlashes(pg_result($resaco,$conresaco,'e74_sequencial'))."','$this->e74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_empagemov"]) || $this->e74_empagemov != "")
             $resac = db_query("insert into db_acount values($acount,3595,20058,'".AddSlashes(pg_result($resaco,$conresaco,'e74_empagemov'))."','$this->e74_empagemov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_codigodebarra"]) || $this->e74_codigodebarra != "")
             $resac = db_query("insert into db_acount values($acount,3595,20059,'".AddSlashes(pg_result($resaco,$conresaco,'e74_codigodebarra'))."','$this->e74_codigodebarra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_valornominal"]) || $this->e74_valornominal != "")
             $resac = db_query("insert into db_acount values($acount,3595,20060,'".AddSlashes(pg_result($resaco,$conresaco,'e74_valornominal'))."','$this->e74_valornominal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_datavencimento"]) || $this->e74_datavencimento != "")
             $resac = db_query("insert into db_acount values($acount,3595,20061,'".AddSlashes(pg_result($resaco,$conresaco,'e74_datavencimento'))."','$this->e74_datavencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_valorjuros"]) || $this->e74_valorjuros != "")
             $resac = db_query("insert into db_acount values($acount,3595,20062,'".AddSlashes(pg_result($resaco,$conresaco,'e74_valorjuros'))."','$this->e74_valorjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_valordesconto"]) || $this->e74_valordesconto != "")
             $resac = db_query("insert into db_acount values($acount,3595,20063,'".AddSlashes(pg_result($resaco,$conresaco,'e74_valordesconto'))."','$this->e74_valordesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_tipofatura"]) || $this->e74_tipofatura != "")
             $resac = db_query("insert into db_acount values($acount,3595,20133,'".AddSlashes(pg_result($resaco,$conresaco,'e74_tipofatura'))."','$this->e74_tipofatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e74_linhadigitavel"]) || $this->e74_linhadigitavel != "")
             $resac = db_query("insert into db_acount values($acount,3595,21278,'".AddSlashes(pg_result($resaco,$conresaco,'e74_linhadigitavel'))."','$this->e74_linhadigitavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Detalhamentos de movimento com tipo de transmiss�o n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e74_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Detalhamentos de movimento com tipo de transmiss�o n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($e74_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e74_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20057,'$e74_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3595,20057,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20058,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20059,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_codigodebarra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20060,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_valornominal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20061,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20062,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_valorjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20063,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,20133,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_tipofatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3595,21278,'','".AddSlashes(pg_result($resaco,$iresaco,'e74_linhadigitavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empagemovdetalhetransmissao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e74_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e74_sequencial = $e74_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Detalhamentos de movimento com tipo de transmiss�o n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e74_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Detalhamentos de movimento com tipo de transmiss�o n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:empagemovdetalhetransmissao";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($e74_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 
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
     $sql .= "  from empagemovdetalhetransmissao ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagemovdetalhetransmissao.e74_empagemov";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e74_sequencial)) {
         $sql2 .= " where empagemovdetalhetransmissao.e74_sequencial = $e74_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
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
   function sql_query_file ( $e74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "  from empagemovdetalhetransmissao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e74_sequencial)){
         $sql2 .= " where empagemovdetalhetransmissao.e74_sequencial = $e74_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
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
   * Valida se codigo de barras ja esta configurado em algum outro movimento
   *
   * @param integer $iMovimento - movimento para ignorar
   * @param integer $iCodigoBarras - Codigo de barras
   * @return string
   */
  public function sql_query_busca_codigo_barras($iCodigoBarras, $iMovimento) {

    $sSql  = " select 1 ";
    $sSql .= "   from empagemovdetalhetransmissao ";
    $sSql .= "        left join empageconfgera on empageconfgera.e90_codmov = empagemovdetalhetransmissao.e74_empagemov ";
    $sSql .= "  where e74_codigodebarra = '{$iCodigoBarras}'";
    $sSql .= "    and e74_empagemov != $iMovimento ";
    $sSql .= "    and (e90_cancelado is null or e90_cancelado = false) ";

    return $sSql;
  }

}
