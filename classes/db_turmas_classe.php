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

//MODULO: educa��o
//CLASSE DA ENTIDADE turmas
class cl_turmas { 
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
   var $ed05_i_codigo = 0; 
   var $ed05_i_escola = 0; 
   var $ed05_i_turno = 0; 
   var $ed05_i_serie = 0; 
   var $ed05_c_nome = null; 
   var $ed05_i_criterio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed05_i_codigo = int4 = C�digo da Turma 
                 ed05_i_escola = int8 = Escola 
                 ed05_i_turno = int8 = Turno 
                 ed05_i_serie = int8 = S�rie 
                 ed05_c_nome = char(40) = Nome da Turma 
                 ed05_i_criterio = int4 = Crit�rio 
                 ";
   //funcao construtor da classe 
   function cl_turmas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmas"); 
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
       $this->ed05_i_codigo = ($this->ed05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"]:$this->ed05_i_codigo);
       $this->ed05_i_escola = ($this->ed05_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_escola"]:$this->ed05_i_escola);
       $this->ed05_i_turno = ($this->ed05_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_turno"]:$this->ed05_i_turno);
       $this->ed05_i_serie = ($this->ed05_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_serie"]:$this->ed05_i_serie);
       $this->ed05_c_nome = ($this->ed05_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_c_nome"]:$this->ed05_c_nome);
       $this->ed05_i_criterio = ($this->ed05_i_criterio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_criterio"]:$this->ed05_i_criterio);
     }else{
       $this->ed05_i_codigo = ($this->ed05_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"]:$this->ed05_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed05_i_codigo){ 
      $this->atualizacampos();
     if($this->ed05_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed05_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_i_turno == null ){ 
       $this->erro_sql = " Campo Turno nao Informado.";
       $this->erro_campo = "ed05_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_i_serie == null ){ 
       $this->erro_sql = " Campo S�rie nao Informado.";
       $this->erro_campo = "ed05_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_c_nome == null ){ 
       $this->erro_sql = " Campo Nome da Turma nao Informado.";
       $this->erro_campo = "ed05_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed05_i_criterio == null ){ 
       $this->erro_sql = " Campo Crit�rio nao Informado.";
       $this->erro_campo = "ed05_i_criterio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed05_i_codigo == "" || $ed05_i_codigo == null ){
       $result = @pg_query("select nextval('turmas_ed05_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmas_ed05_i_codigo_seq do campo: ed05_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed05_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from turmas_ed05_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed05_i_codigo)){
         $this->erro_sql = " Campo ed05_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed05_i_codigo = $ed05_i_codigo; 
       }
     }
     if(($this->ed05_i_codigo == null) || ($this->ed05_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed05_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmas(
                                       ed05_i_codigo 
                                      ,ed05_i_escola 
                                      ,ed05_i_turno 
                                      ,ed05_i_serie 
                                      ,ed05_c_nome 
                                      ,ed05_i_criterio 
                       )
                values (
                                $this->ed05_i_codigo 
                               ,$this->ed05_i_escola 
                               ,$this->ed05_i_turno 
                               ,$this->ed05_i_serie 
                               ,'$this->ed05_c_nome' 
                               ,$this->ed05_i_criterio 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Turmas ($this->ed05_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Turmas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Turmas ($this->ed05_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed05_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1005008,'$this->ed05_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1005005,1005008,'','".AddSlashes(pg_result($resaco,0,'ed05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005005,1006064,'','".AddSlashes(pg_result($resaco,0,'ed05_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005005,1006062,'','".AddSlashes(pg_result($resaco,0,'ed05_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005005,1006063,'','".AddSlashes(pg_result($resaco,0,'ed05_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005005,1005009,'','".AddSlashes(pg_result($resaco,0,'ed05_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005005,1006315,'','".AddSlashes(pg_result($resaco,0,'ed05_i_criterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed05_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmas set ";
     $virgula = "";
     if(trim($this->ed05_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"])){ 
       $sql  .= $virgula." ed05_i_codigo = $this->ed05_i_codigo ";
       $virgula = ",";
       if(trim($this->ed05_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo da Turma nao Informado.";
         $this->erro_campo = "ed05_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_escola"])){ 
       $sql  .= $virgula." ed05_i_escola = $this->ed05_i_escola ";
       $virgula = ",";
       if(trim($this->ed05_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed05_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_turno"])){ 
       $sql  .= $virgula." ed05_i_turno = $this->ed05_i_turno ";
       $virgula = ",";
       if(trim($this->ed05_i_turno) == null ){ 
         $this->erro_sql = " Campo Turno nao Informado.";
         $this->erro_campo = "ed05_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_serie"])){ 
       $sql  .= $virgula." ed05_i_serie = $this->ed05_i_serie ";
       $virgula = ",";
       if(trim($this->ed05_i_serie) == null ){ 
         $this->erro_sql = " Campo S�rie nao Informado.";
         $this->erro_campo = "ed05_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_nome"])){ 
       $sql  .= $virgula." ed05_c_nome = '$this->ed05_c_nome' ";
       $virgula = ",";
       if(trim($this->ed05_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome da Turma nao Informado.";
         $this->erro_campo = "ed05_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed05_i_criterio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_criterio"])){ 
       $sql  .= $virgula." ed05_i_criterio = $this->ed05_i_criterio ";
       $virgula = ",";
       if(trim($this->ed05_i_criterio) == null ){ 
         $this->erro_sql = " Campo Crit�rio nao Informado.";
         $this->erro_campo = "ed05_i_criterio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed05_i_codigo!=null){
       $sql .= " ed05_i_codigo = $this->ed05_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed05_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1005008,'$this->ed05_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1005005,1005008,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_codigo'))."','$this->ed05_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_escola"]))
           $resac = pg_query("insert into db_acount values($acount,1005005,1006064,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_escola'))."','$this->ed05_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_turno"]))
           $resac = pg_query("insert into db_acount values($acount,1005005,1006062,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_turno'))."','$this->ed05_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_serie"]))
           $resac = pg_query("insert into db_acount values($acount,1005005,1006063,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_serie'))."','$this->ed05_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_c_nome"]))
           $resac = pg_query("insert into db_acount values($acount,1005005,1005009,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_c_nome'))."','$this->ed05_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed05_i_criterio"]))
           $resac = pg_query("insert into db_acount values($acount,1005005,1006315,'".AddSlashes(pg_result($resaco,$conresaco,'ed05_i_criterio'))."','$this->ed05_i_criterio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turmas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Turmas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed05_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed05_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1005008,'$ed05_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1005005,1005008,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005005,1006064,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005005,1006062,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005005,1006063,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005005,1005009,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005005,1006315,'','".AddSlashes(pg_result($resaco,$iresaco,'ed05_i_criterio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed05_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed05_i_codigo = $ed05_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Turmas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed05_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Turmas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed05_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed05_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:turmas";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmas ";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = turmas.ed05_i_escola";
     $sql .= "      inner join series  on  series.ed03_i_codigo = turmas.ed05_i_serie";
     $sql .= "      inner join turnos  on  turnos.ed10_i_codigo = turmas.ed05_i_turno";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = escolas.ed02_i_codigo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escolas.ed02_i_departamento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed05_i_codigo!=null ){
         $sql2 .= " where turmas.ed05_i_codigo = $ed05_i_codigo "; 
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
   function sql_query_file ( $ed05_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed05_i_codigo!=null ){
         $sql2 .= " where turmas.ed05_i_codigo = $ed05_i_codigo "; 
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