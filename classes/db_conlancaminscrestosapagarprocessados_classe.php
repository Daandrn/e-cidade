<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE conlancaminscrestosapagarprocessados
class cl_conlancaminscrestosapagarprocessados
{
  // cria variaveis de erro 
  var $rotulo     = null;
  var $query_sql  = null;
  var $numrows    = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql   = null;
  var $erro_banco = null;
  var $erro_msg   = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo 
  var $c108_sequencial = 0;
  var $c108_codlan = 0;
  var $c108_inscricaorestosapagarprocessados = 0;
  // cria propriedade com as variaveis do arquivo 
  var $campos = "
                 c108_sequencial = int4 = Sequencial 
                 c108_codlan = int4 = Código Lançamento 
                 c108_inscricaorestosapagarprocessados = int4 = Sequencial 
                 ";
  //funcao construtor da classe 
  function cl_conlancaminscrestosapagarprocessados()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("conlancaminscrestosapagarprocessados");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }
  //funcao erro 
  function erro($mostra, $retorna)
  {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
      echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
      if ($retorna == true) {
        echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
      }
    }
  }
  // funcao para atualizar campos
  function atualizacampos($exclusao = false)
  {
    if ($exclusao == false) {
      $this->c108_sequencial = ($this->c108_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["c108_sequencial"] : $this->c108_sequencial);
      $this->c108_codlan = ($this->c108_codlan == "" ? @$GLOBALS["HTTP_POST_VARS"]["c108_codlan"] : $this->c108_codlan);
      $this->c108_inscricaorestosapagarprocessados = ($this->c108_inscricaorestosapagarprocessados == "" ? @$GLOBALS["HTTP_POST_VARS"]["c108_inscricaorestosapagarprocessados"] : $this->c108_inscricaorestosapagarprocessados);
    } else {
      $this->c108_sequencial = ($this->c108_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["c108_sequencial"] : $this->c108_sequencial);
    }
  }
  // funcao para inclusao
  function incluir($c108_sequencial)
  {
    $this->atualizacampos();
    if ($this->c108_codlan == null) {
      $this->erro_sql = " Campo Código Lançamento nao Informado.";
      $this->erro_campo = "c108_codlan";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->c108_inscricaorestosapagarprocessados == null) {
      $this->erro_sql = " Campo Sequencial nao Informado.";
      $this->erro_campo = "c108_inscricaorestosapagarprocessados";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($c108_sequencial == "" || $c108_sequencial == null) {
      $result = db_query("select nextval('conlancaminscrestosapagarprocessados_c108_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: conlancaminscrestosapagarprocessados_c108_sequencial_seq do campo: c108_sequencial";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->c108_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from conlancaminscrestosapagarprocessados_c108_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $c108_sequencial)) {
        $this->erro_sql = " Campo c108_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->c108_sequencial = $c108_sequencial;
      }
    }
    if (($this->c108_sequencial == null) || ($this->c108_sequencial == "")) {
      $this->erro_sql = " Campo c108_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into conlancaminscrestosapagarprocessados(
                                       c108_sequencial 
                                      ,c108_codlan 
                                      ,c108_inscricaorestosapagarprocessados 
                       )
                values (
                                $this->c108_sequencial 
                               ,$this->c108_codlan 
                               ,$this->c108_inscricaorestosapagarprocessados 
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = " ($this->c108_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = " já Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql   = " ($this->c108_sequencial) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->c108_sequencial;
    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->c108_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19505,'$this->c108_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3467,19505,'','".AddSlashes(pg_result($resaco,0,'c108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3467,19506,'','".AddSlashes(pg_result($resaco,0,'c108_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3467,19507,'','".AddSlashes(pg_result($resaco,0,'c108_inscricaorestosapagarprocessados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
    }
    return true;
  }
  // funcao para alteracao
  function alterar($c108_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update conlancaminscrestosapagarprocessados set ";
    $virgula = "";
    if (trim($this->c108_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c108_sequencial"])) {
      $sql  .= $virgula . " c108_sequencial = $this->c108_sequencial ";
      $virgula = ",";
      if (trim($this->c108_sequencial) == null) {
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "c108_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c108_codlan) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c108_codlan"])) {
      $sql  .= $virgula . " c108_codlan = $this->c108_codlan ";
      $virgula = ",";
      if (trim($this->c108_codlan) == null) {
        $this->erro_sql = " Campo Código Lançamento nao Informado.";
        $this->erro_campo = "c108_codlan";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->c108_inscricaorestosapagarprocessados) != "" || isset($GLOBALS["HTTP_POST_VARS"]["c108_inscricaorestosapagarprocessados"])) {
      $sql  .= $virgula . " c108_inscricaorestosapagarprocessados = $this->c108_inscricaorestosapagarprocessados ";
      $virgula = ",";
      if (trim($this->c108_inscricaorestosapagarprocessados) == null) {
        $this->erro_sql = " Campo Sequencial nao Informado.";
        $this->erro_campo = "c108_inscricaorestosapagarprocessados";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if ($c108_sequencial != null) {
      $sql .= " c108_sequencial = $this->c108_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->c108_sequencial));
    if ($this->numrows > 0) {
      /*for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19505,'$this->c108_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c108_sequencial"]) || $this->c108_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3467,19505,'".AddSlashes(pg_result($resaco,$conresaco,'c108_sequencial'))."','$this->c108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c108_codlan"]) || $this->c108_codlan != "")
           $resac = db_query("insert into db_acount values($acount,3467,19506,'".AddSlashes(pg_result($resaco,$conresaco,'c108_codlan'))."','$this->c108_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c108_inscricaorestosapagarprocessados"]) || $this->c108_inscricaorestosapagarprocessados != "")
           $resac = db_query("insert into db_acount values($acount,3467,19507,'".AddSlashes(pg_result($resaco,$conresaco,'c108_inscricaorestosapagarprocessados'))."','$this->c108_inscricaorestosapagarprocessados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }*/
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->c108_sequencial;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->c108_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->c108_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao 
  function excluir($c108_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($c108_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      /*for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19505,'$c108_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3467,19505,'','".AddSlashes(pg_result($resaco,$iresaco,'c108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3467,19506,'','".AddSlashes(pg_result($resaco,$iresaco,'c108_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3467,19507,'','".AddSlashes(pg_result($resaco,$iresaco,'c108_inscricaorestosapagarprocessados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }*/
    }
    $sql = " delete from conlancaminscrestosapagarprocessados
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($c108_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " c108_sequencial = $c108_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $c108_sequencial;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = " nao Encontrado. Exclusão n�o Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $c108_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $c108_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset 
  function sql_record($sql)
  {
    $result = db_query($sql);
    if ($result == false) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:conlancaminscrestosapagarprocessados";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql 
  function sql_query($c108_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conlancaminscrestosapagarprocessados ";
    $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancaminscrestosapagarprocessados.c108_codlan";
    $sql .= "      inner join inscricaorestosapagarprocessados  on  inscricaorestosapagarprocessados.c107_sequencial = conlancaminscrestosapagarprocessados.c108_inscricaorestosapagarprocessados";
    $sql .= "      inner join db_config  on  db_config.codigo = inscricaorestosapagarprocessados.c107_instit";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaorestosapagarprocessados.c107_usuario";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c108_sequencial != null) {
        $sql2 .= " where conlancaminscrestosapagarprocessados.c108_sequencial = $c108_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
  // funcao do sql 
  function sql_query_file($c108_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from conlancaminscrestosapagarprocessados ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($c108_sequencial != null) {
        $sql2 .= " where conlancaminscrestosapagarprocessados.c108_sequencial = $c108_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
