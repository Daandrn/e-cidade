<?php
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE contacorrentedetalhe
class cl_contacorrentedetalhe {
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
   var $c19_sequencial = 0;
   var $c19_contacorrente = 0;
   var $c19_orctiporec = 0;
   var $c19_instit = 0;
   var $c19_concarpeculiar = null;
   var $c19_contabancaria = 0;
   var $c19_reduz = 0;
   var $c19_numemp = 0;
   var $c19_numcgm = 0;
   var $c19_orcunidadeanousu = 0;
   var $c19_orcunidadeorgao = 0;
   var $c19_orcunidadeunidade = 0;
   var $c19_orcorgaoanousu = 0;
   var $c19_orcorgaoorgao = 0;
   var $c19_conplanoreduzanousu = 0;
   var $c19_acordo = 0;
   var $c19_estrutural = null;
   var $c19_orcdotacao = 0;
   var $c19_orcdotacaoanousu = 0;
   var $c19_programa = 0;
   var $c19_projativ = 0;
   var $c19_emparlamentar = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 c19_sequencial = int4 = Sequencial
                 c19_contacorrente = int4 = C�digo
                 c19_orctiporec = int4 = Recurso
                 c19_instit = int4 = Cod. Institui��o
                 c19_concarpeculiar = varchar(100) = Caracter�stica Peculiar
                 c19_contabancaria = int4 = Codigo sequencial da conta bancaria
                 c19_reduz = int4 = C�digo da Conta
                 c19_numemp = int4 = Seq. Empenho
                 c19_numcgm = int4 = N�mero do Credor
                 c19_orcunidadeanousu = int4 = Exerc�cio
                 c19_orcunidadeorgao = int4 = �rg�o
                 c19_orcunidadeunidade = int4 = Unidade
                 c19_orcorgaoanousu = int4 = Exerc�cio do �rg�o
                 c19_orcorgaoorgao = int4 = �rg�o
                 c19_conplanoreduzanousu = int4 = Exerc�cio
                 c19_acordo = int4 = Acordo
                 c19_estrutural = varchar(15) = Estrutural
                 c19_orcdotacao = int4 = Orcdota��o
                 c19_orcdotacaoanousu = int4 = Orcdota��o Ano
                 c19_programa = varchar(4) = C�digo do Programa
                 c19_projativ = varchar(4) = C�digo da A��o
                 c19_emparlamentar = int4 = Referente a Emenda Parlamentar
                 ";
   //funcao construtor da classe
   function cl_contacorrentedetalhe() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contacorrentedetalhe");
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
       $this->c19_sequencial = ($this->c19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_sequencial"]:$this->c19_sequencial);
       $this->c19_contacorrente = ($this->c19_contacorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"]:$this->c19_contacorrente);
       $this->c19_orctiporec = ($this->c19_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"]:$this->c19_orctiporec);
       $this->c19_instit = ($this->c19_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_instit"]:$this->c19_instit);
       $this->c19_concarpeculiar = ($this->c19_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_concarpeculiar"]:$this->c19_concarpeculiar);
       $this->c19_contabancaria = ($this->c19_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"]:$this->c19_contabancaria);
       $this->c19_reduz = ($this->c19_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_reduz"]:$this->c19_reduz);
       $this->c19_numemp = ($this->c19_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_numemp"]:$this->c19_numemp);
       $this->c19_numcgm = ($this->c19_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_numcgm"]:$this->c19_numcgm);
       $this->c19_orcunidadeanousu = ($this->c19_orcunidadeanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"]:$this->c19_orcunidadeanousu);
       $this->c19_orcunidadeorgao = ($this->c19_orcunidadeorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"]:$this->c19_orcunidadeorgao);
       $this->c19_orcunidadeunidade = ($this->c19_orcunidadeunidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"]:$this->c19_orcunidadeunidade);
       $this->c19_orcorgaoanousu = ($this->c19_orcorgaoanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"]:$this->c19_orcorgaoanousu);
       $this->c19_orcorgaoorgao = ($this->c19_orcorgaoorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"]:$this->c19_orcorgaoorgao);
       $this->c19_conplanoreduzanousu = ($this->c19_conplanoreduzanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"]:$this->c19_conplanoreduzanousu);
       $this->c19_acordo = ($this->c19_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_acordo"]:$this->c19_acordo);
       $this->c19_estrutural = ($this->c19_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_estrutural"]:$this->c19_estrutural);
       $this->c19_orcdotacao = ($this->c19_orcdotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"]:$this->c19_orcdotacao);
       $this->c19_orcdotacaoanousu = ($this->c19_orcdotacaoanousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"]:$this->c19_orcdotacaoanousu);
       $this->c19_programa = ($this->c19_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_programa"]:$this->c19_programa);
       $this->c19_projativ = ($this->c19_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_projativ"]:$this->c19_projativ);
       $this->c19_emparlamentar = ($this->c19_emparlamentar == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_emparlamentar"]:$this->c19_emparlamentar);
     }else{
       $this->c19_sequencial = ($this->c19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c19_sequencial"]:$this->c19_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c19_sequencial){
      $this->atualizacampos();
     if($this->c19_contacorrente == null ){
       $this->c19_contacorrente = "null";
     }
     if($this->c19_orctiporec == null ){
       $this->c19_orctiporec = "null";
     }
     if($this->c19_instit == null ){
       $this->c19_instit = "null";
     }
     if($this->c19_concarpeculiar == null ){
       $this->c19_concarpeculiar = "null";
     }
     if($this->c19_contabancaria == null ){
       $this->c19_contabancaria = "null";
     }
     if($this->c19_reduz == null ){
       $this->c19_reduz = "null";
     }
     if($this->c19_numemp == null ){
       $this->c19_numemp = "null";
     }
     if($this->c19_numcgm == null ){
       $this->c19_numcgm = "null";
     }
     if($this->c19_orcunidadeanousu == null ){
       $this->c19_orcunidadeanousu = "null";
     }
     if($this->c19_orcunidadeorgao == null ){
       $this->c19_orcunidadeorgao = "null";
     }
     if($this->c19_orcunidadeunidade == null ){
       $this->c19_orcunidadeunidade = "null";
     }
     if($this->c19_orcorgaoanousu == null ){
       $this->c19_orcorgaoanousu = "null";
     }
     if($this->c19_orcorgaoorgao == null ){
       $this->c19_orcorgaoorgao = "null";
     }
     if($this->c19_conplanoreduzanousu == null ){
       $this->c19_conplanoreduzanousu = "null";
     }
     if($this->c19_acordo == null ){
       $this->c19_acordo = "null";
     }
     if($this->c19_estrutural == null ){
       $this->c19_estrutural = "null";
     }
     if($this->c19_orcdotacao == null ){
       $this->c19_orcdotacao = "null";
     }
     if($this->c19_orcdotacaoanousu == null ){
       $this->c19_orcdotacaoanousu = "null";
     }
     if($this->c19_programa == null ){
       $this->c19_programa = "null";
     }
     if($this->c19_projativ == null ){
       $this->c19_projativ = "null";
     }
     if($this->c19_emparlamentar == null ){
      $this->c19_emparlamentar = "null";
    }
     if($c19_sequencial == "" || $c19_sequencial == null ){
       $result = db_query("select nextval('contacorrentedetalhe_c19_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contacorrentedetalhe_c19_sequencial_seq do campo: c19_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c19_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from contacorrentedetalhe_c19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c19_sequencial)){
         $this->erro_sql = " Campo c19_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c19_sequencial = $c19_sequencial;
       }
     }
     if(($this->c19_sequencial == null) || ($this->c19_sequencial == "") ){
       $this->erro_sql = " Campo c19_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contacorrentedetalhe(
                                       c19_sequencial
                                      ,c19_contacorrente
                                      ,c19_orctiporec
                                      ,c19_instit
                                      ,c19_concarpeculiar
                                      ,c19_contabancaria
                                      ,c19_reduz
                                      ,c19_numemp
                                      ,c19_numcgm
                                      ,c19_orcunidadeanousu
                                      ,c19_orcunidadeorgao
                                      ,c19_orcunidadeunidade
                                      ,c19_orcorgaoanousu
                                      ,c19_orcorgaoorgao
                                      ,c19_conplanoreduzanousu
                                      ,c19_acordo
                                      ,c19_estrutural
                                      ,c19_orcdotacao
                                      ,c19_orcdotacaoanousu
                                      ,c19_programa
                                      ,c19_projativ
                                      ,c19_emparlamentar
                       )
                values (
                                $this->c19_sequencial
                               ,$this->c19_contacorrente
                               ,$this->c19_orctiporec
                               ,$this->c19_instit
                               ,$this->c19_concarpeculiar
                               ,$this->c19_contabancaria
                               ,$this->c19_reduz
                               ,$this->c19_numemp
                               ,$this->c19_numcgm
                               ,$this->c19_orcunidadeanousu
                               ,$this->c19_orcunidadeorgao
                               ,$this->c19_orcunidadeunidade
                               ,$this->c19_orcorgaoanousu
                               ,$this->c19_orcorgaoorgao
                               ,$this->c19_conplanoreduzanousu
                               ,$this->c19_acordo
                               ,$this->c19_estrutural
                               ,$this->c19_orcdotacao
                               ,$this->c19_orcdotacaoanousu
                               ,'$this->c19_programa'
                               ,'$this->c19_projativ'
                               ,$this->c19_emparlamentar
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conta Corrente Detalhe ($this->c19_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conta Corrente Detalhe j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conta Corrente Detalhe ($this->c19_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c19_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c19_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19648,'$this->c19_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3492,19648,'','".AddSlashes(pg_result($resaco,0,'c19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19649,'','".AddSlashes(pg_result($resaco,0,'c19_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19650,'','".AddSlashes(pg_result($resaco,0,'c19_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19651,'','".AddSlashes(pg_result($resaco,0,'c19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19652,'','".AddSlashes(pg_result($resaco,0,'c19_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19653,'','".AddSlashes(pg_result($resaco,0,'c19_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19654,'','".AddSlashes(pg_result($resaco,0,'c19_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19655,'','".AddSlashes(pg_result($resaco,0,'c19_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19656,'','".AddSlashes(pg_result($resaco,0,'c19_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19657,'','".AddSlashes(pg_result($resaco,0,'c19_orcunidadeanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19658,'','".AddSlashes(pg_result($resaco,0,'c19_orcunidadeorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19659,'','".AddSlashes(pg_result($resaco,0,'c19_orcunidadeunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19660,'','".AddSlashes(pg_result($resaco,0,'c19_orcorgaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19661,'','".AddSlashes(pg_result($resaco,0,'c19_orcorgaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19665,'','".AddSlashes(pg_result($resaco,0,'c19_conplanoreduzanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,19704,'','".AddSlashes(pg_result($resaco,0,'c19_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,20732,'','".AddSlashes(pg_result($resaco,0,'c19_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,20733,'','".AddSlashes(pg_result($resaco,0,'c19_orcdotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3492,20734,'','".AddSlashes(pg_result($resaco,0,'c19_orcdotacaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($c19_sequencial=null) {
      $this->atualizacampos();
     $sql = " update contacorrentedetalhe set ";
     $virgula = "";
     if(trim($this->c19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_sequencial"])){
       $sql  .= $virgula." c19_sequencial = $this->c19_sequencial ";
       $virgula = ",";
       if(trim($this->c19_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "c19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c19_contacorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"])){
        if(trim($this->c19_contacorrente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"])){
           $this->c19_contacorrente = "0" ;
        }
       $sql  .= $virgula." c19_contacorrente = $this->c19_contacorrente ";
       $virgula = ",";
     }
     if(trim($this->c19_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"])){
        if(trim($this->c19_orctiporec)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"])){
           $this->c19_orctiporec = "0" ;
        }
       $sql  .= $virgula." c19_orctiporec = $this->c19_orctiporec ";
       $virgula = ",";
     }
     if(trim($this->c19_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_instit"])){
        if(trim($this->c19_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_instit"])){
           $this->c19_instit = "0" ;
        }
       $sql  .= $virgula." c19_instit = $this->c19_instit ";
       $virgula = ",";
     }
     if(trim($this->c19_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_concarpeculiar"])){
       $sql  .= $virgula." c19_concarpeculiar = '$this->c19_concarpeculiar' ";
       $virgula = ",";
     }
     if(trim($this->c19_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"])){
        if(trim($this->c19_contabancaria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"])){
           $this->c19_contabancaria = "0" ;
        }
       $sql  .= $virgula." c19_contabancaria = $this->c19_contabancaria ";
       $virgula = ",";
     }
     if(trim($this->c19_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_reduz"])){
        if(trim($this->c19_reduz)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_reduz"])){
           $this->c19_reduz = "0" ;
        }
       $sql  .= $virgula." c19_reduz = $this->c19_reduz ";
       $virgula = ",";
     }
     if(trim($this->c19_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_numemp"])){
        if(trim($this->c19_numemp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_numemp"])){
           $this->c19_numemp = "0" ;
        }
       $sql  .= $virgula." c19_numemp = $this->c19_numemp ";
       $virgula = ",";
     }
     if(trim($this->c19_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_numcgm"])){
        if(trim($this->c19_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_numcgm"])){
           $this->c19_numcgm = "0" ;
        }
       $sql  .= $virgula." c19_numcgm = $this->c19_numcgm ";
       $virgula = ",";
     }
     if(trim($this->c19_orcunidadeanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"])){
        if(trim($this->c19_orcunidadeanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"])){
           $this->c19_orcunidadeanousu = "0" ;
        }
       $sql  .= $virgula." c19_orcunidadeanousu = $this->c19_orcunidadeanousu ";
       $virgula = ",";
     }
     if(trim($this->c19_orcunidadeorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"])){
        if(trim($this->c19_orcunidadeorgao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"])){
           $this->c19_orcunidadeorgao = "0" ;
        }
       $sql  .= $virgula." c19_orcunidadeorgao = $this->c19_orcunidadeorgao ";
       $virgula = ",";
     }
     if(trim($this->c19_orcunidadeunidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"])){
        if(trim($this->c19_orcunidadeunidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"])){
           $this->c19_orcunidadeunidade = "0" ;
        }
       $sql  .= $virgula." c19_orcunidadeunidade = $this->c19_orcunidadeunidade ";
       $virgula = ",";
     }
     if(trim($this->c19_orcorgaoanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"])){
        if(trim($this->c19_orcorgaoanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"])){
           $this->c19_orcorgaoanousu = "0" ;
        }
       $sql  .= $virgula." c19_orcorgaoanousu = $this->c19_orcorgaoanousu ";
       $virgula = ",";
     }
     if(trim($this->c19_orcorgaoorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"])){
        if(trim($this->c19_orcorgaoorgao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"])){
           $this->c19_orcorgaoorgao = "0" ;
        }
       $sql  .= $virgula." c19_orcorgaoorgao = $this->c19_orcorgaoorgao ";
       $virgula = ",";
     }
     if(trim($this->c19_conplanoreduzanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"])){
        if(trim($this->c19_conplanoreduzanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"])){
           $this->c19_conplanoreduzanousu = "0" ;
        }
       $sql  .= $virgula." c19_conplanoreduzanousu = $this->c19_conplanoreduzanousu ";
       $virgula = ",";
     }
     if(trim($this->c19_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_acordo"])){
        if(trim($this->c19_acordo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_acordo"])){
           $this->c19_acordo = "0" ;
        }
       $sql  .= $virgula." c19_acordo = $this->c19_acordo ";
       $virgula = ",";
     }
     if(trim($this->c19_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_estrutural"])){
       $sql  .= $virgula." c19_estrutural = '$this->c19_estrutural' ";
       $virgula = ",";
     }
     if(trim($this->c19_orcdotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"])){
        if(trim($this->c19_orcdotacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"])){
           $this->c19_orcdotacao = "0" ;
        }
       $sql  .= $virgula." c19_orcdotacao = $this->c19_orcdotacao ";
       $virgula = ",";
     }
     if(trim($this->c19_orcdotacaoanousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"])){
        if(trim($this->c19_orcdotacaoanousu)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"])){
           $this->c19_orcdotacaoanousu = "0" ;
        }
       $sql  .= $virgula." c19_orcdotacaoanousu = $this->c19_orcdotacaoanousu ";
       $virgula = ",";
     }
     if(trim($this->c19_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_programa"])){
        if(trim($this->c19_programa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_programa"])){
           $this->c19_programa = "''" ;
        }
       $sql  .= $virgula." c19_programa = '$this->c19_programa'";
       $virgula = ",";
     }
     if(trim($this->c19_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_projativ"])){
        if(trim($this->c19_projativ)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_projativ"])){
           $this->c19_projativ = "''" ;
        }
       $sql  .= $virgula." c19_projativ = '$this->c19_projativ'";
       $virgula = ",";
     }
     if(trim($this->c19_emparlamentar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c19_emparlamentar"])){
      if(trim($this->c19_emparlamentar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c19_emparlamentar"])){
         $this->c19_emparlamentar = "0" ;
      }
      $sql  .= $virgula." c19_emparlamentar = $this->c19_emparlamentar ";
      $virgula = ",";
    }
     $sql .= " where ";
     if($c19_sequencial!=null){
       $sql .= " c19_sequencial = $this->c19_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c19_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19648,'$this->c19_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_sequencial"]) || $this->c19_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3492,19648,'".AddSlashes(pg_result($resaco,$conresaco,'c19_sequencial'))."','$this->c19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_contacorrente"]) || $this->c19_contacorrente != "")
             $resac = db_query("insert into db_acount values($acount,3492,19649,'".AddSlashes(pg_result($resaco,$conresaco,'c19_contacorrente'))."','$this->c19_contacorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orctiporec"]) || $this->c19_orctiporec != "")
             $resac = db_query("insert into db_acount values($acount,3492,19650,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orctiporec'))."','$this->c19_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_instit"]) || $this->c19_instit != "")
             $resac = db_query("insert into db_acount values($acount,3492,19651,'".AddSlashes(pg_result($resaco,$conresaco,'c19_instit'))."','$this->c19_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_concarpeculiar"]) || $this->c19_concarpeculiar != "")
             $resac = db_query("insert into db_acount values($acount,3492,19652,'".AddSlashes(pg_result($resaco,$conresaco,'c19_concarpeculiar'))."','$this->c19_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_contabancaria"]) || $this->c19_contabancaria != "")
             $resac = db_query("insert into db_acount values($acount,3492,19653,'".AddSlashes(pg_result($resaco,$conresaco,'c19_contabancaria'))."','$this->c19_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_reduz"]) || $this->c19_reduz != "")
             $resac = db_query("insert into db_acount values($acount,3492,19654,'".AddSlashes(pg_result($resaco,$conresaco,'c19_reduz'))."','$this->c19_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_numemp"]) || $this->c19_numemp != "")
             $resac = db_query("insert into db_acount values($acount,3492,19655,'".AddSlashes(pg_result($resaco,$conresaco,'c19_numemp'))."','$this->c19_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_numcgm"]) || $this->c19_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,3492,19656,'".AddSlashes(pg_result($resaco,$conresaco,'c19_numcgm'))."','$this->c19_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeanousu"]) || $this->c19_orcunidadeanousu != "")
             $resac = db_query("insert into db_acount values($acount,3492,19657,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcunidadeanousu'))."','$this->c19_orcunidadeanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeorgao"]) || $this->c19_orcunidadeorgao != "")
             $resac = db_query("insert into db_acount values($acount,3492,19658,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcunidadeorgao'))."','$this->c19_orcunidadeorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcunidadeunidade"]) || $this->c19_orcunidadeunidade != "")
             $resac = db_query("insert into db_acount values($acount,3492,19659,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcunidadeunidade'))."','$this->c19_orcunidadeunidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoanousu"]) || $this->c19_orcorgaoanousu != "")
             $resac = db_query("insert into db_acount values($acount,3492,19660,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcorgaoanousu'))."','$this->c19_orcorgaoanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcorgaoorgao"]) || $this->c19_orcorgaoorgao != "")
             $resac = db_query("insert into db_acount values($acount,3492,19661,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcorgaoorgao'))."','$this->c19_orcorgaoorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_conplanoreduzanousu"]) || $this->c19_conplanoreduzanousu != "")
             $resac = db_query("insert into db_acount values($acount,3492,19665,'".AddSlashes(pg_result($resaco,$conresaco,'c19_conplanoreduzanousu'))."','$this->c19_conplanoreduzanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_acordo"]) || $this->c19_acordo != "")
             $resac = db_query("insert into db_acount values($acount,3492,19704,'".AddSlashes(pg_result($resaco,$conresaco,'c19_acordo'))."','$this->c19_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_estrutural"]) || $this->c19_estrutural != "")
             $resac = db_query("insert into db_acount values($acount,3492,20732,'".AddSlashes(pg_result($resaco,$conresaco,'c19_estrutural'))."','$this->c19_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacao"]) || $this->c19_orcdotacao != "")
             $resac = db_query("insert into db_acount values($acount,3492,20733,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcdotacao'))."','$this->c19_orcdotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c19_orcdotacaoanousu"]) || $this->c19_orcdotacaoanousu != "")
             $resac = db_query("insert into db_acount values($acount,3492,20734,'".AddSlashes(pg_result($resaco,$conresaco,'c19_orcdotacaoanousu'))."','$this->c19_orcdotacaoanousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conta Corrente Detalhe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c19_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Conta Corrente Detalhe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($c19_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c19_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19648,'$c19_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3492,19648,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19649,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_contacorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19650,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19651,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19652,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19653,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19654,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19655,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19656,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19657,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcunidadeanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19658,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcunidadeorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19659,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcunidadeunidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19660,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcorgaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19661,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcorgaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19665,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_conplanoreduzanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,19704,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,20732,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,20733,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcdotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3492,20734,'','".AddSlashes(pg_result($resaco,$iresaco,'c19_orcdotacaoanousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from contacorrentedetalhe
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c19_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c19_sequencial = $c19_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conta Corrente Detalhe nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c19_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Conta Corrente Detalhe nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c19_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:contacorrentedetalhe";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= "  from contacorrentedetalhe ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = contacorrentedetalhe.c19_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = contacorrentedetalhe.c19_instit";
     $sql .= "      left  join orctiporec  on  orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec";
     $sql .= "      left  join orcorgao  on  orcorgao.o40_anousu = contacorrentedetalhe.c19_orcorgaoanousu and  orcorgao.o40_orgao = contacorrentedetalhe.c19_orcorgaoorgao";
     $sql .= "      left  join orcunidade  on  orcunidade.o41_anousu = contacorrentedetalhe.c19_orcunidadeanousu and  orcunidade.o41_orgao = contacorrentedetalhe.c19_orcunidadeorgao and  orcunidade.o41_unidade = contacorrentedetalhe.c19_orcunidadeunidade";
     $sql .= "      left  join orcdotacao  on  orcdotacao.o58_anousu = contacorrentedetalhe.c19_orcdotacaoanousu and  orcdotacao.o58_coddot = contacorrentedetalhe.c19_orcdotacao";
     $sql .= "      left  join conplanoreduz  on  conplanoreduz.c61_reduz = contacorrentedetalhe.c19_conplanoreduzanousu and  conplanoreduz.c61_anousu = contacorrentedetalhe.c19_reduz";
     $sql .= "      left  join empempenho  on  empempenho.e60_numemp = contacorrentedetalhe.c19_numemp";
     $sql .= "      left  join concarpeculiar  on  concarpeculiar.c58_sequencial = contacorrentedetalhe.c19_concarpeculiar";
     $sql .= "      left  join contabancaria  on  contabancaria.db83_sequencial = contacorrentedetalhe.c19_contabancaria";
     $sql .= "      left  join bancoagencia                 on contabancaria.db83_bancoagencia   = bancoagencia.db89_sequencial ";
     $sql .= "      left  join acordo  on  acordo.ac16_sequencial = contacorrentedetalhe.c19_acordo";
     $sql .= "      left  join contacorrente                on contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
     $sql .= "      left  join db_tipoinstit                on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      left  join db_estruturavalor            on db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql .= "      left  join orcorgao  as c               on c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      left  join conplano  as d               on d.c60_codcon = conplanoreduz.c61_codcon and   d.c60_anousu = conplanoreduz.c61_anousu";
     $sql .= "      left  join orcdotacao                   on orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      left  join pctipocompra                 on pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      left  join emptipo                      on emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      left  join concarpeculiarclassificacao  on concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
     $sql .= "      left  join db_depart                    on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
     $sql .= "      left  join acordogrupo                  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      left  join acordosituacao               on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      left  join acordocomissao               on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
    // $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
    // $sql .= "      inner join db_config  as a on   a.codigo = orcorgao.o40_instit";
    // $sql .= "      inner join db_config  as d on   d.codigo = conplanoreduz.c61_instit";
    // $sql .= "      inner join db_config  as b on   b.codigo = orcunidade.o41_instit";
     //$sql .= "      inner join orctiporec  as d on   d.o15_codigo = conplanoreduz.c61_codigo";
    // $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
    // $sql .= "      inner join db_config  as d on   d.codigo = empempenho.e60_instit";
     //$sql .= "      inner join db_estruturavalor  as d on   d.db121_sequencial = concarpeculiar.c58_db_estruturavalor";
    // $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     //$sql .= "      inner join concarpeculiar  as d on   d.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($c19_sequencial!=null ){
         $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
   function sql_query_file ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contacorrentedetalhe ";
     $sql2 = "";
     if($dbwhere==""){
       if($c19_sequencial!=null ){
         $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
   function sql_query_saldo($c19_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from contacorrentedetalhe ";
     $sql .= " inner join contacorrentesaldo on c19_sequencial = c29_contacorrentedetalhe";

     $sql2 = "";
     if($dbwhere==""){
       if($c19_sequencial!=null ){
         $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
   function sql_query_lancamentos($c19_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from contacorrentedetalhe ";
     $sql .= " left join contacorrentedetalheconlancamval on contacorrentedetalhe.c19_sequencial               = contacorrentedetalheconlancamval.c28_contacorrentedetalhe ";
     $sql .= " left join conlancamval                     on contacorrentedetalheconlancamval.c28_conlancamval = c69_sequen";

     $sql2 = "";
     if($dbwhere==""){
       if($c19_sequencial!=null ){
         $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
   function sql_query_fileAtributos ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $sql .= "       from contacorrentedetalhe ";
    $sql .= "            left  join contacorrentedetalheconlancamval on contacorrentedetalhe.c19_sequencial = contacorrentedetalheconlancamval.c28_contacorrentedetalhe";
    $sql .= "            inner join contacorrente  on  contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
    $sql .= "            left join conlancamval                     on contacorrentedetalheconlancamval.c28_conlancamval = conlancamval.c69_sequen ";
    $sql .= "            inner join db_config                        on contacorrentedetalhe.c19_instit =  db_config.codigo ";
    $sql .= "            left join cgm                              on cgm.z01_numcgm                  = contacorrentedetalhe.c19_numcgm ";
    $sql .= "            left join concarpeculiar                   on concarpeculiar.c58_sequencial   = contacorrentedetalhe.c19_concarpeculiar";
    $sql .= "            left join contabancaria                    on contabancaria.db83_sequencial   = contacorrentedetalhe.c19_contabancaria";
    $sql .= "            left join bancoagencia                     on contabancaria.db83_bancoagencia   = bancoagencia.db89_sequencial ";
    $sql .= "            left join orctiporec                       on orctiporec.o15_codigo           = contacorrentedetalhe.c19_orctiporec";
    $sql .= "            left join empempenho                       on empempenho.e60_numemp           = contacorrentedetalhe.c19_numemp";
    $sql .= "            left join emppresta                        on emppresta.e45_numemp            = empempenho.e60_numemp";
    $sql .= "            left join orcorgao                         on orcorgao.o40_anousu             = contacorrentedetalhe.c19_orcorgaoanousu ";
    $sql .= "                                                      and orcorgao.o40_orgao              = contacorrentedetalhe.c19_orcorgaoorgao";
    $sql .= "            left join orcunidade                       on orcunidade.o41_anousu           = contacorrentedetalhe.c19_orcunidadeanousu ";
    $sql .= "                                                      and orcunidade.o41_orgao            = contacorrentedetalhe.c19_orcunidadeorgao ";
    $sql .= "                                                      and orcunidade.o41_unidade          = contacorrentedetalhe.c19_orcunidadeunidade";

    $sql .= "            inner join conplanoreduz                    on contacorrentedetalhe.c19_reduz = conplanoreduz.c61_reduz ";
    $sql .= "                                                       and conplanoreduz.c61_anousu       = contacorrentedetalhe.c19_conplanoreduzanousu ";
    $sql .= "            inner join conplano                         on conplano.c60_codcon            = conplanoreduz.c61_codcon ";
    $sql .= "                                                       and conplano.c60_anousu            = conplanoreduz.c61_anousu ";
    $sql .= "            left join acordo                           on acordo.ac16_sequencial          = contacorrentedetalhe.c19_acordo ";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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

   function sql_query_contacorrente_cgm ($c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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

    $sql .= "       from contacorrentedetalhe ";
    $sql .= "            inner join contacorrente  on  contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
    $sql .= "            inner join cgm            on cgm.z01_numcgm                = contacorrentedetalhe.c19_numcgm ";
    $sql2 = "";
    if($dbwhere==""){
      if($c19_sequencial!=null ){
        $sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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

  function sql_query_viewDetalhes ( $c19_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from contacorrentedetalhe ";
  	$sql .= "      left  join cgm                          on cgm.z01_numcgm = contacorrentedetalhe.c19_numcgm";
  	$sql .= "      left  join db_config                    on db_config.codigo = contacorrentedetalhe.c19_instit";
  	$sql .= "      left  join orctiporec                   on orctiporec.o15_codigo = contacorrentedetalhe.c19_orctiporec";
  	$sql .= "      left  join orcorgao                     on orcorgao.o40_anousu = contacorrentedetalhe.c19_orcorgaoanousu and  orcorgao.o40_orgao = contacorrentedetalhe.c19_orcorgaoorgao";
  	$sql .= "      left  join orcunidade                   on orcunidade.o41_anousu = contacorrentedetalhe.c19_orcunidadeanousu and  orcunidade.o41_orgao = contacorrentedetalhe.c19_orcunidadeorgao and  orcunidade.o41_unidade = contacorrentedetalhe.c19_orcunidadeunidade";
  	$sql .= "      left  join conplanoreduz                on conplanoreduz.c61_reduz = contacorrentedetalhe.c19_conplanoreduzanousu and  conplanoreduz.c61_anousu = contacorrentedetalhe.c19_reduz";
  	$sql .= "      left  join empempenho                   on empempenho.e60_numemp = contacorrentedetalhe.c19_numemp";
  	$sql .= "      left  join concarpeculiar               on concarpeculiar.c58_sequencial = contacorrentedetalhe.c19_concarpeculiar";
  	$sql .= "      left  join contabancaria                on contabancaria.db83_sequencial = contacorrentedetalhe.c19_contabancaria";
  	$sql .= "      left  join bancoagencia                 on contabancaria.db83_bancoagencia   = bancoagencia.db89_sequencial ";
  	$sql .= "      left  join acordo                       on acordo.ac16_sequencial = contacorrentedetalhe.c19_acordo";
  	$sql .= "      left  join contacorrente                on contacorrente.c17_sequencial = contacorrentedetalhe.c19_contacorrente";
  	$sql .= "      left  join db_tipoinstit                on db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
  	$sql .= "      left  join db_estruturavalor            on db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
  	$sql .= "      left  join orcorgao  as c               on c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
  	$sql .= "      left  join conplano  as d               on d.c60_codcon = conplanoreduz.c61_codcon and   d.c60_anousu = conplanoreduz.c61_anousu";
  	$sql .= "      left  join orcdotacao                   on orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
  	$sql .= "      left  join pctipocompra                 on pctipocompra.pc50_codcom = empempenho.e60_codcom";
  	$sql .= "      left  join emptipo                      on emptipo.e41_codtipo = empempenho.e60_codtipo";
  	$sql .= "      left  join concarpeculiarclassificacao  on concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
  	$sql .= "      left  join db_depart                    on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
  	$sql .= "      left  join acordogrupo                  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
  	$sql .= "      left  join acordosituacao               on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
  	$sql .= "      left  join acordocomissao               on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($c19_sequencial!=null ){
  			$sql2 .= " where contacorrentedetalhe.c19_sequencial = $c19_sequencial ";
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
     * @param $instituicao
     * @param $validaTipoPfPj
     * @param $filtroCredores
     * @param $anoSessao
     * @return string
     */
    function consultaEfdReinf($instituicao, $validaTipoPfPj, $filtroCredores, $anoSessao): string
    {
        return " SELECT * FROM
                   (SELECT DISTINCT e60_codemp||'/'||e60_anousu AS empenho,
                                    e50_codord AS op,
                                    e69_numero AS nro_nota,
                                    e69_dtnota AS data_nota,
                                    c69_data AS data_pgto,
                
                                    CASE
                                        WHEN e102_vlrbruto > 0 THEN coalesce(e102_vlrbruto, 0)
                                        ELSE coalesce(e70_valor, 0)
                                    END AS valor_bruto,
                                       
                                    CASE
                                        WHEN e102_vlrbruto > 0 THEN coalesce(e102_vlrbase, 0)
                                        ELSE coalesce(e23_valorbase, 0)
                                    END AS valor_base,
                                       
                                    CASE
                                        WHEN e102_vlrbruto > 0 THEN coalesce(e102_vlrir, 0)
                                        ELSE CASE
                                                 WHEN e21_retencaotipocalc IN (1, 2) THEN coalesce(retencaoreceitas.e23_valorretencao, 0)
                                                 ELSE 0
                                             END
                                    END AS valor_irrf,
                                       
                                    e50_naturezabemservico AS natureza_rendimento,
                                       
                                     CASE
                                         WHEN e102_vlrbase > 0 THEN 't'
                                         ELSE 'f'
                                     END AS reten_terceiros,
                                       
                                     CASE
                                         WHEN e102_codord is null THEN e60_numcgm||' - '||cgm.z01_nome
                                         ELSE cgm_pagordemreinf.z01_numcgm||' - '||cgm_pagordemreinf.z01_nome
                                     END AS beneficiario,
                
                                     e60_numcgm||' - '||cgm.z01_nome AS credor_emp,
                                     coalesce(e70_valor, 0) AS valor_op,
                                     e60_numemp
                    FROM contacorrentedetalhe
                    JOIN contacorrentedetalheconlancamval ON c28_contacorrentedetalhe = c19_sequencial
                    JOIN conlancamval ON c69_sequen = c28_conlancamval
                    JOIN conlancamemp ON c75_codlan = c69_codlan
                    JOIN conlancamdoc ON c71_codlan = c75_codlan
                    JOIN conhistdoc ON c71_coddoc = c53_coddoc
                    JOIN empempenho ON e60_numemp = c75_numemp
                    JOIN empelemento ON e64_numemp = e60_numemp
                    JOIN orcelemento ON (o56_codele, o56_anousu) = (e64_codele, e60_anousu)
                    JOIN cgm AS cgm ON e60_numcgm = cgm.z01_numcgm
                    JOIN conlancamord ON c80_codlan = c75_codlan
                    JOIN pagordem ON e50_codord = c80_codord
                    JOIN pagordemele ON e53_codord = c80_codord
                    JOIN pagordemnota ON e71_codord = c80_codord
                    JOIN empnota ON (e69_numemp, e69_codnota) = (e60_numemp, e71_codnota) 
                    JOIN empnotaele ON e69_codnota = e70_codnota
                
                    LEFT JOIN pagordemreinf ON e102_codord = e50_codord
                    LEFT JOIN cgm AS empresa ON empresa.z01_numcgm = e50_empresadesconto
                
                    LEFT JOIN cgm AS cgm_pagordemreinf ON cgm_pagordemreinf.z01_numcgm = e102_numcgm
                
                    LEFT JOIN retencaopagordem ON pagordem.e50_codord = retencaopagordem.e20_pagordem
                    LEFT JOIN retencaoreceitas ON retencaoreceitas.e23_retencaopagordem = retencaopagordem.e20_sequencial 
                              AND e23_ativo = 't'
                
                    LEFT JOIN retencaotiporec ON retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec
                
                    JOIN coremp c1 ON (c80_codord, c80_data) = (k12_codord, c1.k12_data)
                    JOIN corrente c2 ON (c1.k12_id, c1.k12_data, c1.k12_autent) = (c2.k12_id, c2.k12_data, c2.k12_autent)
                    JOIN corempagemov c3 ON (c3.k12_id, c3.k12_data, c3.k12_autent) = (c2.k12_id, c2.k12_data, c2.k12_autent)
                    JOIN empord ON (e82_codord, e82_codmov) = (c80_codord, k12_codmov)
                
                    WHERE e50_cattrabalhador IS NULL
                      AND o56_elemento NOT LIKE '331%'
                      AND e60_numcgm NOT IN (SELECT numcgm FROM db_config)
                      AND c53_tipo = 30
                      AND e53_vlrpag > 0
                      AND e60_instit = {$instituicao}
                      {$validaTipoPfPj}
                      {$filtroCredores}
                      AND (e21_retencaotipocalc IN (1, 2) OR o56_elemento LIKE '333903614%' OR e50_retencaoir = 't')
                      AND date_part('year', c69_data)::int4 = {$anoSessao}) AS x
                ORDER BY credor_emp, beneficiario, e60_numemp, 2, 5 DESC, valor_irrf DESC ";
    }

    function detalhamentoPorFonte($iAnoUsu,$c61_reduz, $aInstit, $iMes, $sDataInicial=null, $sDataFinal=null){
      $sSql = "select 
          c19_orctiporec as codtri,
          round(substr(fc_saldocontacorrente, 43, 15)::float8, 2)::float8 AS saldo_anterior,
          substr(fc_saldocontacorrente, 107, 1)::varchar(1)               AS sinal_anterior,
          round(substr(fc_saldocontacorrente, 59, 15)::float8, 2)::float8 AS valor_debito,
          round(substr(fc_saldocontacorrente, 75, 15)::float8, 2)::float8 AS valor_credito,
          round(substr(fc_saldocontacorrente, 91, 15)::float8, 2)::float8 AS saldo_final,
          substr(fc_saldocontacorrente, 111, 1)::varchar(1)               AS sinal_final                   
          from
          (
          select
              c19_sequencial,
              c19_orctiporec,";
      if ($iMes == null && $sDataInicial != null && $sDataFinal != null) {
        $sSql .= "fc_saldocontacorrenteperiodo({$iAnoUsu}, c19_sequencial, 103, '{$sDataInicial}', '{$sDataFinal}', c19_instit) as fc_saldocontacorrente";
      } else {
        $sSql .= "fc_saldocontacorrente({$iAnoUsu}, c19_sequencial, 103, {$iMes}, c19_instit)";
      }
      $sSql .= " from
              contabilidade.contacorrentedetalhe
          where
              c19_instit in ({$aInstit})
              and c19_reduz = {$c61_reduz}
              and c19_conplanoreduzanousu = {$iAnoUsu}) as x
              order by c19_orctiporec asc;";
      $result = db_query($sSql);
      $aContaCorrente = pg_fetch_all($result);
      $aContaCorrenteDados = array();
      $aTotalPorCodtri = array();

      //Converte fontes antigas pra o codigo atual e soma as fontes iguais
      foreach ($aContaCorrente as $aCC){
            $clDeParaRecurso = new DeParaRecurso;
            $codtri = substr($clDeParaRecurso->getDePara($aCC['codtri']), 0, -1);
        if (isset($aTotalPorCodtri[$codtri])){
            $saldoAnterior = $aTotalPorCodtri[$codtri]['sinal_anterior'] == 'D' ? $aTotalPorCodtri[$codtri]['saldo_anterior'] * -1 : $aTotalPorCodtri[$codtri]['saldo_anterior'];
            $aCCSaldoAnterior = $aCC['sinal_anterior'] == 'D' ? $aCC['saldo_anterior'] * -1 : $aCC['saldo_anterior'];
            $saldoAnterior = $saldoAnterior + $aCCSaldoAnterior;

            $saldoFinal = $aTotalPorCodtri[$codtri]['sinal_final'] == 'D' ? $aTotalPorCodtri[$codtri]['saldo_final'] * -1 : $aTotalPorCodtri[$codtri]['saldo_final'];
            $aCCSaldoFinal = $aCC['sinal_final'] == 'D' ? $aCC['saldo_final'] * -1 : $aCC['saldo_final'];
            $saldoFinal = $saldoFinal + $aCCSaldoFinal;

            $valorDebito = $aTotalPorCodtri[$codtri]['valor_debito'] + $aCC['valor_debito'];
            $valorCredito = $aTotalPorCodtri[$codtri]['valor_credito'] + $aCC['valor_credito'];

            if ($saldoAnterior <= 0){
                $aTotalPorCodtri[$codtri]['sinal_anterior'] = 'D';
            }else{
                $aTotalPorCodtri[$codtri]['sinal_anterior'] = 'C';
            }
            if ($saldoFinal <= 0){
                $aTotalPorCodtri[$codtri]['sinal_final'] = 'D';
            }else{
                $aTotalPorCodtri[$codtri]['sinal_final'] = 'C';
            }

            $aTotalPorCodtri[$codtri]['codtri'] = $codtri;
            $aTotalPorCodtri[$codtri]['saldo_anterior'] = abs($saldoAnterior);
            $aTotalPorCodtri[$codtri]['valor_debito'] = $valorDebito;
            $aTotalPorCodtri[$codtri]['valor_credito'] = $valorCredito;
            $aTotalPorCodtri[$codtri]['saldo_final'] = abs($saldoFinal);
        }else {   
          $aCC['codtri'] = $codtri;
          $aTotalPorCodtri[$codtri] = $aCC;
        }
      }

      //Filtra fontes que n�o estejam zeradas
      foreach($aTotalPorCodtri as $aCC){
          if($aCC['saldo_anterior'] == 0 && $aCC['valor_debito'] == 0 && $aCC['valor_credito'] == 0 && $aCC['saldo_final'] == 0) {
              continue;
          }
          $aContaCorrenteDados[] = $aCC;
      }

      //Ordena as fontes por ordem crescente
      usort($aContaCorrenteDados, function ($ccA, $ccB) {
          return strcmp($ccA['codtri'], $ccB['codtri']);
      });

      return $aContaCorrenteDados;
    }

    function detalhamentoPorFonteext($iAnoUsu,$c61_reduz, $aInstit, $iMes){
      $sSql = "select 
          c19_orctiporec as codtri,
          round(substr(fc_saldocontacorrente, 43, 15)::float8, 2)::float8 AS saldo_anterior,
          substr(fc_saldocontacorrente, 107, 1)::varchar(1)               AS sinal_anterior,
          round(substr(fc_saldocontacorrente, 59, 15)::float8, 2)::float8 AS valor_debito,
          round(substr(fc_saldocontacorrente, 75, 15)::float8, 2)::float8 AS valor_credito,
          round(substr(fc_saldocontacorrente, 91, 15)::float8, 2)::float8 AS saldo_final,
          substr(fc_saldocontacorrente, 111, 1)::varchar(1)               AS sinal_final                   
          from
          (
          select
              c19_sequencial,
              c19_orctiporec,
              fc_saldocontacorrente({$iAnoUsu}, c19_sequencial, 103, {$iMes}, c19_instit)
          from
              contabilidade.contacorrentedetalhe
          inner join	conplanoreduz on c19_reduz = c61_reduz
            	and c61_anousu = c19_conplanoreduzanousu
          where
              c19_instit in ({$aInstit})
              and ( c19_reduz = {$c61_reduz} or c61_codtce = {$c61_reduz} )
              and c19_conplanoreduzanousu = {$iAnoUsu}) as x
              order by c19_orctiporec asc;";
      $result = db_query($sSql);
      $aContaCorrente = pg_fetch_all($result);
      $aContaCorrenteDados = array();
      $aTotalPorCodtri = array();

      //Converte fontes antigas pra o codigo atual e soma as fontes iguais
      foreach ($aContaCorrente as $aCC){
            $clDeParaRecurso = new DeParaRecurso;
            $codtri = substr($clDeParaRecurso->getDePara($aCC['codtri']), 0, -1);
  
        if (isset($aTotalPorCodtri[$codtri])){
            $saldoAnterior = $aTotalPorCodtri[$codtri]['sinal_anterior'] == 'D' ? $aTotalPorCodtri[$codtri]['saldo_anterior'] * -1 : $aTotalPorCodtri[$codtri]['saldo_anterior'];
            $aCCSaldoAnterior = $aCC['sinal_anterior'] == 'D' ? $aCC['saldo_anterior'] * -1 : $aCC['saldo_anterior'];
            $saldoAnterior = $saldoAnterior + $aCCSaldoAnterior;

            $saldoFinal = $aTotalPorCodtri[$codtri]['sinal_final'] == 'D' ? $aTotalPorCodtri[$codtri]['saldo_final'] * -1 : $aTotalPorCodtri[$codtri]['saldo_final'];
            $aCCSaldoFinal = $aCC['sinal_final'] == 'D' ? $aCC['saldo_final'] * -1 : $aCC['saldo_final'];
            $saldoFinal = $saldoFinal + $aCCSaldoFinal;

            $valorDebito = $aTotalPorCodtri[$codtri]['valor_debito'] + $aCC['valor_debito'];
            $valorCredito = $aTotalPorCodtri[$codtri]['valor_credito'] + $aCC['valor_credito'];

            if ($saldoAnterior <= 0){
                $aTotalPorCodtri[$codtri]['sinal_anterior'] = 'D';
                $saldoNovo = $aTotalPorCodtri[$codtri]['sinal_anterior'] == 'D' ? $aTotalPorCodtri[$codtri]['saldo_anterior'] * -1 : $aTotalPorCodtri[$codtri]['saldo_anterior'] + ($aCCSaldoAnterior);
              
                if ($saldoNovo > $aCCSaldoAnterior){
                  $aTotalPorCodtri[$codtri]['sinal_anterior'] = 'C';
                }
            }else{
                $aTotalPorCodtri[$codtri]['sinal_anterior'] = 'C';
            }
            if ($saldoFinal <= 0){
                $aTotalPorCodtri[$codtri]['sinal_final'] = 'D';
                $saldoNovof = $aTotalPorCodtri[$codtri]['sinal_final'] == 'D' ? $aTotalPorCodtri[$codtri]['saldo_final'] * -1 : $aTotalPorCodtri[$codtri]['saldo_final'] + ($aCCSaldoFinal);
                if ($saldoNovof > $aCCSaldoFinal){
                  $aTotalPorCodtri[$codtri]['sinal_final'] = 'C';
                }
            }else{
                $aTotalPorCodtri[$codtri]['sinal_final'] = 'C';
            }

            $aTotalPorCodtri[$codtri]['codtri'] = $codtri;
            $aTotalPorCodtri[$codtri]['saldo_anterior'] = abs($saldoAnterior);
            $aTotalPorCodtri[$codtri]['valor_debito'] = $valorDebito;
            $aTotalPorCodtri[$codtri]['valor_credito'] = $valorCredito;
            $aTotalPorCodtri[$codtri]['saldo_final'] = abs($saldoFinal);
        }else {   
          $aCC['codtri'] = $codtri;
          $aTotalPorCodtri[$codtri] = $aCC;
        }
      }

      //Filtra fontes que n�o estejam zeradas
      foreach($aTotalPorCodtri as $aCC){
          if($aCC['saldo_anterior'] == 0 && $aCC['valor_debito'] == 0 && $aCC['valor_credito'] == 0 && $aCC['saldo_final'] == 0) {
              continue;
          }
          $aContaCorrenteDados[] = $aCC;
      }

      //Ordena as fontes por ordem crescente
      usort($aContaCorrenteDados, function ($ccA, $ccB) {
          return strcmp($ccA['codtri'], $ccB['codtri']);
      });

      return $aContaCorrenteDados;
    }
}