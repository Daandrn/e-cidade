<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

//classes


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");

function xx($fd, $parq) {

  $fk = db_query("select a.nomearq,c.nomecam,f.sequen,f.referen,c.codcam,c.nulo
                     from db_sysforkey f
                          inner join db_sysarquivo a on a.codarq = f.codarq
                          inner join db_syscampo c   on c.codcam = f.codcam
                     where a.codarq = " . $parq . "
					 order by f.referen,f.sequen");
  $encerra = false;
  $qalias = "";

  if (pg_numrows($fk) > 0) {
    $Nfk = pg_numrows($fk);
    $arq = 0;
    $virgula = "";
    for($f = 0; $f < $Nfk; $f ++) {
      if ($arq != pg_result($fk, $f, "referen")) {
        if ($virgula != "") {
          fputs($fd, '";' . "\n");
        }
        $arq = pg_result($fk, $f, "referen");
        $qarq = db_query("select nomearq from db_sysarquivo where codarq = " . $arq);

        if (pg_num_rows($qarq) == 0 || !$qarq ) {

          echo("select nomearq from db_sysarquivo where codarq = " . $arq."; -- PARQ = {$parq} <br>");

        }


        if (strpos($GLOBALS ["temalias"], "-" . trim(pg_result($qarq, 0, 0))) > 0) {

          if (strpos($GLOBALS ["qualalias"], "a") > 0) {
            if (strpos($GLOBALS ["qualalias"], "b") > 0) {
              if (strpos($GLOBALS ["qualalias"], "c") > 0) {
                $qalias = "d";
              } else {
                $qalias = "c";
                $GLOBALS ["qualalias"] .= "-c";
              }
            } else {
              $qalias = "b";
              $GLOBALS ["qualalias"] .= "-b";
            }

          } else {
            $qalias = "a";
            $GLOBALS ["qualalias"] = "-a";
          }
        } else {
          $qalias = "";
        }

        $GLOBALS ["temalias"] .= "-" . trim(pg_result($qarq, 0, 0));
        if (pg_result($fk, $f, "nulo") == 't') {
          $join = 'left ';
        } else {
          $join = 'inner';
        }
        fputs($fd, '     $sql .= "      '.$join.' join ' . trim(pg_result($qarq, 0, 0)) . " " . ($qalias == "" ? "" : " as " . $qalias) . " on ");
        $virgula = "";
      }

      /*	      echo("select q.nomecam
                             from db_sysprikey p
			          inner join db_syscampo q on q.codcam = p.codcam
			     where codarq = ".$arq." and
			           sequen = ".pg_result($fk,$f,"sequen")); */

      $qk = db_query("select q.nomecam
                             from db_sysprikey p
			          inner join db_syscampo q on q.codcam = p.codcam
			     where codarq = " . $arq . " and
			           sequen = " . pg_result($fk, $f, "sequen"));

      if (pg_num_rows($qk) == 0 || !$qk ) {
          echo("select q.nomecam
                             from db_sysprikey p
			          inner join db_syscampo q on q.codcam = p.codcam
			     where codarq = " . $arq . " and
			           sequen = " . pg_result($fk, $f, "sequen")."; -- PARQ : {$parq}" );


      }

      fputs($fd, $virgula . ' ' . ($qalias == "" ? trim(pg_result($qarq, 0, 0)) : " " . $qalias) . '.' . trim(pg_result($qk, 0, 0)) . " = " . trim(pg_result($fk, $f, "nomearq")) . "." . trim(pg_result($fk, $f, "nomecam")));
      $encerra = true;
      $virgula = " and ";
    }
  }
  if ($encerra == true) {
    fputs($fd, '";' . "\n");
  }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<?
parse_str($HTTP_SERVER_VARS ["QUERY_STRING"]);
$root = substr($HTTP_SERVER_VARS ['SCRIPT_FILENAME'], 0, strrpos($HTTP_SERVER_VARS ['SCRIPT_FILENAME'], "/"));

//$codarq = 1594;

$lGeral   = true;

$sql      = " select codarq, ";
$sql     .= "        nomearq as nometab ";
$sql     .= "   from db_sysarquivo ";
if (isset($codarq) && $codarq != '') {
  $sql   .= "  where codarq = $codarq";
  $lGeral = false;

}
$sql     .= "   order by nomearq ";

$resulta  = db_query($sql);
$iNumrows = pg_num_rows($resulta);


//
// Variaveis para configura��o dos metodos a serem gerados
//

$lGerarIncluir         = true;
$lGerarAlterar         = true;
$lGerarExcluir         = true;
$lGerarSqlRecord       = true;
$lGerarSqlQuery        = true;
$lGerarSqlQueryFile    = true;
$lGerarMetodosExternos = true;

if ($lGeral) {
  echo "Total de classes a serem geradas : {$iNumrows} <br><br> ";
}
for ($iIndice = 0 ; $iIndice < $iNumrows; $iIndice++) {

db_fieldsmemory($resulta, $iIndice);

$GLOBALS ["temalias"]  = '';
$GLOBALS ["qualalias"] = '';

if ($lGeral) {
  echo "{$codarq} -- {$nometab} <br> ";
}

//continue;

$arquivo = $root . "/classes/" . "db_" . $nometab . "_classe.php";
if (! is_writable($root . "/classes")) {
  ?>
     <table width="100%">
  <tr>
    <td align="center">
    <h6>Sem permiss�o para gravar em "classes/" ou n�o existe.</h6>
    </td>
  </tr>
</table>
</body>
</html>
<?
  exit();
}

if (file_exists($arquivo) && ! is_writable($arquivo)) {
  ?>
<table width="100%">
  <tr>
    <td align="center">
    <h6>Sem permiss�o para gravar "classes/db_<?=$nometab?>_classe"</h6>
    </td>
  </tr>
</table>
</body>
</html>
<?
  exit();
}

//$arquivo = "/tmp/classes/"."db_".$nometab."_classe.php";
umask(74);
$fd = fopen($arquivo, "w");
// Tabelas
$qr = "where a.codarq = '$codarq'";
$sql = "select a.codarq,a.nomearq,m.codmod,m.nomemod, a.rotulo
          from db_sysmodulo m
               inner join db_sysarqmod am on am.codmod = m.codmod
               inner join db_sysarquivo a on a.codarq = am.codarq
          $qr
          order by codmod";
$result = db_query($sql);
$numrows = pg_numrows($result);
$RecordsetTabMod = $result;
if ($numrows == 0) {
  echo ("N�o foi encontrada nenhuma tabela com o argumento $nometab");
  continue;
  //exit();
} else {
  fputs($fd, "<?php\n");

  for($i = 0; $i < $numrows; $i ++) {
    $campo = db_query("select c.codcam,
	                           aa.nomearq,
	                           c.nomecam,
		                       c.conteudo,
							   c.valorinicial,
							   c.rotulo,
							   s.nomesequencia,
							   s.codsequencia,
							   c.nulo
                          from db_syscampo c
                               inner join db_sysarqcamp a   on a.codcam = c.codcam
                               inner join db_sysarquivo aa   on aa.codarq = a.codarq
	                       left outer join db_syssequencia s on s.codsequencia = a.codsequencia
                          where a.codarq = " . pg_result($result, $i, "codarq") . "order by a.seqarq");
    $Ncampos = pg_numrows($campo);
    if ($Ncampos > 0) {
      fputs($fd, "//MODULO: " . trim(pg_result($result, $i, "nomemod")) . "\n");
      fputs($fd, "//CLASSE DA ENTIDADE " . trim(pg_result($result, $i, "nomearq")) . "\n");
      fputs($fd, "class cl_" . trim(pg_result($result, $i, "nomearq")) . " { \n");
      fputs($fd, "  // cria variaveis de erro \n");
      fputs($fd, '  public $rotulo     = null; ' . "\n");
      fputs($fd, '  public $query_sql  = null; ' . "\n");
      fputs($fd, '  public $numrows    = 0; ' . "\n");
      fputs($fd, '  public $numrows_incluir = 0; ' . "\n");
      fputs($fd, '  public $numrows_alterar = 0; ' . "\n");
      fputs($fd, '  public $numrows_excluir = 0; ' . "\n");
      fputs($fd, '  public $erro_status= null; ' . "\n");
      fputs($fd, '  public $erro_sql   = null; ' . "\n");
      fputs($fd, '  public $erro_banco = null; ' . " \n");
      fputs($fd, '  public $erro_msg   = null; ' . " \n");
      fputs($fd, '  public $erro_campo = null; ' . " \n");
      fputs($fd, '  public $pagina_retorno = null;' . " \n");
      fputs($fd, "  // cria variaveis do arquivo \n");
      for($j = 0; $j < $Ncampos; $j ++) {
        $x = pg_result($campo, $j, "conteudo");
        if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "text") {
          $aspas = "null";
        } else if (substr($x, 0, 4) == "date") {
          $aspas = "data";
        } else if (substr($x, 0, 4) == "bool") {
          $aspas = "'f'";
        } else if (substr($x, 0, 9) == "timestamp") {
          $aspas = "timestamp";
        } else if (substr($x, 0, 4) == "time") {
          $aspas = "'00:00'";
        } else {
          $aspas = "0";
        }
        if ($aspas == "data" || $aspas == "timestamp") {
          fputs($fd, '  public $' . trim(pg_result($campo, $j, "nomecam")) . "_dia = null; \n");
          fputs($fd, '  public $' . trim(pg_result($campo, $j, "nomecam")) . "_mes = null; \n");
          fputs($fd, '  public $' . trim(pg_result($campo, $j, "nomecam")) . "_ano = null; \n");
          fputs($fd, '  public $' . trim(pg_result($campo, $j, "nomecam")) . " = null; \n");

          if ($aspas == "timestamp") {
            fputs($fd, '  public $' . trim(pg_result($campo, $j, "nomecam")) . "_hora = '00:00'; \n");
          }
        } else {
          fputs($fd, '  public $' . trim(pg_result($campo, $j, "nomecam")) . " = $aspas; \n");
        }

      }
      fputs($fd, "  // cria propriedade com as variaveis do arquivo \n");
      $sql = '  public $campos = "' . "\n";
      $espaco = "                 ";
      for($j = 0; $j < $Ncampos; $j ++) {
        $sql .= $espaco . trim(pg_result($campo, $j, "nomecam")) . " = " . trim(pg_result($campo, $j, "conteudo")) . " = " . trim(pg_result($campo, $j, "rotulo")) . " \n";
      }
      fputs($fd, $sql . "                 " . '";' . "\n");
      // function construtor da classe
      fputs($fd, "\n  //funcao construtor da classe \n");
      fputs($fd, "  function __construct() { \n");
      fputs($fd, "    //classes dos rotulos dos campos\n");
      fputs($fd, '    $this->rotulo = new rotulo("' . trim(pg_result($result, $i, "nomearq")) . '"); ' . "\n");
      fputs($fd, '    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);' . "\n");
      fputs($fd, '  }' . "\n");

      // metodo para erro
      fputs($fd, "\n  //funcao erro \n");
      fputs($fd, '  function erro($mostra,$retorna) { ' . "\n");
      fputs($fd, '    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )) {' . "\n");
      fputs($fd, '      echo "<script>alert(\"".$this->erro_msg."\");</script>";' . "\n");
      fputs($fd, '      if ($retorna==true) {' . "\n");
      fputs($fd, '        echo "<script>location.href=\'".$this->pagina_retorno."\'</script>";' . "\n");
      fputs($fd, '      }' . "\n");
      fputs($fd, '    }' . "\n");
      fputs($fd, '  }' . "\n");

      // inicio do metodo atualizavariaveis
      fputs($fd, "\n  // funcao para atualizar campos\n");
      fputs($fd, '  function atualizacampos($exclusao=false) {' . "\n");

      $varpk = "";
      $varpks = "";
      $pk = db_query("select a.nomearq,c.nomecam,p.sequen
                       from db_sysprikey p
                            inner join db_sysarquivo a on a.codarq = p.codarq
                            inner join db_syscampo c   on c.codcam = p.codcam
                       where a.codarq = " . pg_result($result, $i, "codarq") . "
		       order by p.sequen");

      if (pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        $virgula = "";
        for($p = 0; $p < $Npk; $p ++) {
          $varpk .= $virgula . '$this->' . trim(pg_result($pk, $p, "nomecam"));
          $varpks .= $virgula . '$' . trim(pg_result($pk, $p, "nomecam"));
          $virgula = '."-".';
        }
      }
      fputs($fd, '    if ($exclusao==false) {' . "\n");
      for($j = 0; $j < $Ncampos; $j ++) {
        $x = pg_result($campo, $j, "conteudo");
        if (substr($x, 0, 9) == "timestamp") {
          fputs($fd, '       if ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == "") {' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia);' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_mes"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes);' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_ano"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano);' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_hora = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_hora"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_hora);' . "\n");
          fputs($fd, '         if ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia != "") {' . "\n");
          fputs($fd, '   	   $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = "date \'".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano."-".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes."-".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia."\',time \'".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_hora."\'";' . "\n");
          fputs($fd, '         }' . "\n");
          fputs($fd, '       }' . "\n");
        } else if (substr($x, 0, 4) == "date") {
          fputs($fd, '       if ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == "") {' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia);' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_mes"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes);' . "\n");
          fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano = ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_ano"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano);' . "\n");
          fputs($fd, '         if ($this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia != "") {' . "\n");
          fputs($fd, '            $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = $this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano."-".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes."-".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia;' . "\n");
          fputs($fd, '         }' . "\n");
          fputs($fd, '       }' . "\n");
        } else if (substr($x, 0, 3) == "boo") {
          fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == "f"?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . ');' . "\n");
        } else {
          fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . ');' . "\n");
        }
      }
      fputs($fd, '     } else {' . "\n");
      for($j = 0; $j < $Ncampos; $j ++) {
        $x = pg_result($campo, $j, "conteudo");
        if (strpos($varpk, trim(pg_result($campo, $j, "nomecam"))) != 0) {
          if (substr($x, 0, 4) == "date") {
            fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . ');' . "\n");
          } else if (substr($x, 0, 9) == "timestamp") {
            fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = "date \'".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_ano."-".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_mes."-".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_dia."\',time \'".$this->' . trim(pg_result($campo, $j, "nomecam")) . '_hora."\'";' . "\n");
          } else if (substr($x, 0, 3) == "boo") {
            fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == "f"?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . ');' . "\n");
          } else {
            fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = ($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == ""?@$GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"]:$this->' . trim(pg_result($campo, $j, "nomecam")) . ');' . "\n");
          }
        }
      }
      fputs($fd, '     }' . "\n");
      fputs($fd, "   }\n");

      //
      // Gera o metodo de inclusao
      //
      if ($lGerarIncluir) {

        fputs($fd, "\n  // funcao para inclusao\n");
        fputs($fd, "  function incluir (");
        // Chave Primaria
        $pk = db_query("select p.codcam,a.nomearq,c.nomecam,p.sequen,c.conteudo
                         from db_sysprikey p
                              inner join db_sysarquivo a on a.codarq = p.codarq
                              inner join db_syscampo c   on c.codcam = p.codcam
                         where a.codarq = " . pg_result($result, $i, "codarq") . "
             order by p.sequen");
        if (pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
          $virgula = "";
          $virconc = "";
          for($p = 0; $p < $Npk; $p ++) {
            fputs($fd, $virgula . "$" . trim(pg_result($pk, $p, "nomecam")));
            $virgula = ",";
            $virconc = '.":"';
          }
        }
        fputs($fd, ") { \n");
        // atualizacao das variaveis
        fputs($fd, '      $this->atualizacampos();' . "\n");
        // inclusao de testes de erro em campos que aceitam null
        for($j = 0; $j < $Ncampos; $j ++) {
          if (strpos($varpk, trim(pg_result($campo, $j, "nomecam"))) == 0) {
            $nulo = pg_result($campo, $j, "nulo");
            $x = pg_result($campo, $j, "conteudo");
            if ($nulo == 'f' || $nulo == '') {

              if (substr($x, 0, 9) == "timestamp") {

                fputs($fd, '     if ($this->' . trim(pg_result($campo, $j, "nomecam")) . "_dia == null || \n");
                fputs($fd, '        $this->' . trim(pg_result($campo, $j, "nomecam")) . "_mes == null || \n");
                fputs($fd, '        $this->' . trim(pg_result($campo, $j, "nomecam")) . "_ano == null ) { \n");
                fputs($fd, '       $this->erro_sql = " Campo ' . trim(pg_result($campo, $j, "rotulo")) . ' n�o informado.";' . "\n");
                if (substr($x, 0, 4) == "date" || substr($x, 0, 9) == "timestamp") {
                  fputs($fd, '       $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '_dia";' . "\n");
                } else {
                  fputs($fd, '       $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '";' . "\n");
                }
                fputs($fd, '       $this->erro_banco = "";' . "\n");
                fputs($fd, '       $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
                fputs($fd, '       $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
                fputs($fd, '       $this->erro_status = "0";' . "\n");
                fputs($fd, "       return false;\n");
                fputs($fd, "     }\n");

              } else {
                fputs($fd, '     if ($this->' . trim(pg_result($campo, $j, "nomecam")) . " == null ) { \n");
                fputs($fd, '       $this->erro_sql = " Campo ' . trim(pg_result($campo, $j, "rotulo")) . ' n�o informado.";' . "\n");
                if (substr($x, 0, 4) == "date") {
                  fputs($fd, '       $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '_dia";' . "\n");
                } else {
                  fputs($fd, '       $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '";' . "\n");
                }
                fputs($fd, '       $this->erro_banco = "";' . "\n");
                fputs($fd, '       $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
                fputs($fd, '       $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
                fputs($fd, '       $this->erro_status = "0";' . "\n");
                fputs($fd, "       return false;\n");
                fputs($fd, "     }\n");
              }

            } else {
              $valorinicial = pg_result($campo, $j, "valorinicial");
              if ($valorinicial != '') {
                fputs($fd, '     if ($this->' . trim(pg_result($campo, $j, "nomecam")) . " == null ) { \n");
                fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = "' . $valorinicial . '";' . "\n");
                fputs($fd, "     }\n");
              }
            }

          }
        }
        // verifica se tem sequencia para a chave primaria
        $temsequencia = false;
        for($j = 0; $j < $Ncampos; $j ++) {
          $x = pg_result($campo, $j, "codsequencia");
          if ($x != 0) {
            $temsequencia = true;
            fputs($fd, '     if ($' . trim(pg_result($campo, $j, "nomecam")) . ' == "" || $' . trim(pg_result($campo, $j, "nomecam")) . ' == null ) {' . "\n");
            fputs($fd, '       $result = db_query("select nextval(\'' . trim(pg_result($campo, $j, "nomesequencia")) . "')\"); \n");
            fputs($fd, '       if ($result==false) {' . "\n");
            fputs($fd, '         $this->erro_banco = str_replace("\n","",@pg_last_error());' . "\n");
            fputs($fd, '         $this->erro_sql   = "Verifique o cadastro da sequencia: ' . trim(pg_result($campo, $j, "nomesequencia")) . ' do campo: ' . trim(pg_result($campo, $j, "nomecam")) . "\"; \n");
            fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
            fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
            fputs($fd, '         $this->erro_status = "0";' . "\n");
            fputs($fd, '         return false; ' . "\n");
            fputs($fd, "       }\n");
            fputs($fd, '       $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = pg_result($result,0,0); ' . "\n");
            fputs($fd, "     } else {\n");
            fputs($fd, '       $result = db_query("select last_value from ' . trim(pg_result($campo, $j, "nomesequencia")) . '");' . "\n");
            fputs($fd, '       if (($result != false) && (pg_result($result,0,0) < $' . trim(pg_result($campo, $j, "nomecam")) . ')) {' . "\n");
            fputs($fd, '         $this->erro_sql = " Campo ' . trim(pg_result($campo, $j, "nomecam")) . ' maior que �ltimo n�mero da sequencia.";' . "\n");
            fputs($fd, '         $this->erro_banco = "Sequencia menor que este n�mero.";' . "\n");
            fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
            fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
            fputs($fd, '         $this->erro_status = "0";' . "\n");
            fputs($fd, "         return false;\n");
            fputs($fd, '       } else {' . "\n");
            fputs($fd, '         $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = $' . trim(pg_result($campo, $j, "nomecam")) . '; ' . "\n");
            fputs($fd, '       }' . "\n");
            fputs($fd, "     }\n");
          }
        }
        if ($temsequencia == false) {
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, '       $this->' . trim(pg_result($pk, $p, "nomecam")) . ' = $' . trim(pg_result($pk, $p, "nomecam")) . '; ' . "\n");
            }
          }
        }
        // verifica chave primaria
        if (pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
          for($p = 0; $p < $Npk; $p ++) {
            fputs($fd, '     if (($this->' . trim(pg_result($pk, $p, "nomecam")) . ' == null) || ($this->' . trim(pg_result($pk, $p, "nomecam")) . ' == "") ) {' . " \n");
            fputs($fd, '       $this->erro_sql = " Campo ' . trim(pg_result($pk, $p, "nomecam")) . ' nao declarado.";' . "\n");
            fputs($fd, '       $this->erro_banco = "Chave Primaria zerada.";' . "\n");
            fputs($fd, '       $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
            fputs($fd, '       $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
            fputs($fd, '       $this->erro_status = "0";' . "\n");
            fputs($fd, "       return false;\n");
            fputs($fd, "     }\n");
          }
        }
        // insert
        fputs($fd, '     $' . 'sql = "insert into ' . trim(pg_result($result, $i, "nomearq")) . "(\n");
        $virgula = " ";
        for($j = 0; $j < $Ncampos; $j ++) {
          fputs($fd, "                                      " . $virgula . trim(pg_result($campo, $j, "nomecam")) . " \n");
          $virgula = ",";
        }
        fputs($fd, "                       )\n");
        fputs($fd, "                values (\n");
        $virgula = " ";
        for($j = 0; $j < $Ncampos; $j ++) {
          $x = pg_result($campo, $j, "conteudo");
          if (substr($x, 0, 9) == "timestamp") {
            $aspas = "";
            fputs($fd, '                               ' . $virgula . '".($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == "null" || $this->' . trim(pg_result($campo, $j, "nomecam")) . ' == ""?' . '"null"' . ':"' . $aspas . 'timestamp(".$this->' . trim(pg_result($campo, $j, "nomecam")) . '.")' . $aspas . '")."' . " \n");
          } else if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "time" || substr($x, 0, 4) == "text") {
            $aspas = "'";
            fputs($fd, '                               ' . $virgula . $aspas . '$this->' . trim(pg_result($campo, $j, "nomecam")) . $aspas . " \n");
          } else if (substr($x, 0, 4) == "date") {
            $aspas = "'";
            fputs($fd, '                               ' . $virgula . '".($this->' . trim(pg_result($campo, $j, "nomecam")) . ' == "null" || $this->' . trim(pg_result($campo, $j, "nomecam")) . ' == ""?' . '"null"' . ':"' . $aspas . '".$this->' . trim(pg_result($campo, $j, "nomecam")) . '."' . $aspas . '")."' . " \n");
          } else if (substr($x, 0, 4) == "bool") {
            $aspas = "'";
            fputs($fd, '                               ' . $virgula . $aspas . '$this->' . trim(pg_result($campo, $j, "nomecam")) . $aspas . " \n");
          } else {
            $aspas = "";
            fputs($fd, '                               ' . $virgula . $aspas . '$this->' . trim(pg_result($campo, $j, "nomecam")) . $aspas . " \n");
          }
          $virgula = ",";
        }
        fputs($fd, "                      )\";\n");
        fputs($fd, '     $result = db_query($sql); ' . "\n");
        fputs($fd, '     if ($result==false) { ' . "\n");
        fputs($fd, '       $this->erro_banco = str_replace("\n","",@pg_last_error());' . "\n");
        fputs($fd, '       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {' . "\n");
        fputs($fd, '         $this->erro_sql   = "' . pg_result($result, $i, 'rotulo') . ' (' . $varpk . ') nao Inclu�do. Inclusao Abortada.";' . "\n");
        fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
        fputs($fd, '         $this->erro_banco = "' . pg_result($result, $i, 'rotulo') . ' j� Cadastrado";' . "\n");
        fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
        fputs($fd, '       } else {' . "\n");
        fputs($fd, '         $this->erro_sql   = "' . pg_result($result, $i, 'rotulo') . ' (' . $varpk . ') nao Inclu�do. Inclusao Abortada.";' . "\n");
        fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
        fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
        fputs($fd, '       }' . "\n");
        fputs($fd, '       $this->erro_status = "0";' . "\n");
        fputs($fd, '       $this->numrows_incluir= 0;' . "\n");
        fputs($fd, '       return false;' . "\n");
        fputs($fd, "     }\n");
        fputs($fd, '     $this->erro_banco = "";' . "\n");
        fputs($fd, '     $this->erro_sql = "Inclusao efetuada com Sucesso\\\n";' . "\n");
        if (! empty($varpk)) {
          fputs($fd, '         $this->erro_sql .= "Valores : ".' . $varpk . ';' . "\n");
        }
        fputs($fd, '     $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
        fputs($fd, '     $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
        fputs($fd, '     $this->erro_status = "1";' . "\n");
        fputs($fd, '     $this->numrows_incluir= pg_affected_rows($result);' . "\n");

        fputs($fd, '     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);'."\n");
        fputs($fd, '     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)'."\n");
        fputs($fd, '       && ($lSessaoDesativarAccount === false))) {'."\n\n");
        if (pg_numrows($pk) > 0) {
          fputs($fd, '       $resaco = $this->sql_record($this->sql_query_file(');
          // coloca as chaves primerias
          if (pg_numrows($pk) > 0) {

            $Npk = pg_numrows($pk);
            $virgula = "";
            for($p = 0; $p < $Npk; $p ++) {

              fputs($fd, $virgula . '$this->' . trim(pg_result($pk, $p, "nomecam")));
              $virgula = ",";
            }
          }
          fputs($fd, '  ));' . "\n");
          fputs($fd, '       if (($resaco!=false)||($this->numrows!=0)) {' . "\n\n");
          fputs($fd, '         $resac = db_query("select nextval(\'db_acount_id_acount_seq\') as acount");' . "\n");
          fputs($fd, '         $acount = pg_result($resac,0,0);' . "\n");
          fputs($fd, '         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");' . "\n");
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, '         $resac = db_query("insert into db_acountkey values($acount,' . pg_result($pk, $p, "codcam") . ',\'$this->' . trim(pg_result($pk, $p, "nomecam")) . '\',\'I\')");' . "\n");
            }
          }
          for($j = 0; $j < $Ncampos; $j ++) {
            fputs($fd, '         $resac = db_query("insert into db_acount values($acount,' . pg_result($result, $i, 'codarq') . ',' . pg_result($campo, $j, "codcam") . ',\'\',\'".AddSlashes(pg_result($resaco,0,\'' . trim(pg_result($campo, $j, "nomecam")) . '\'))."\',".db_getsession(\'DB_datausu\').",".db_getsession(\'DB_id_usuario\').")");' . "\n");
          }
          fputs($fd, '       }' . "\n");
        }
        fputs($fd, '    }' . "\n");
        fputs($fd, '    return true;' . "\n");
        fputs($fd, "  }\n");

      }

      //
      // Gera o metodo de alteracao
      //
      if ($lGerarAlterar) {

        fputs($fd, "\n  // funcao para alteracao\n");
        fputs($fd, "  function alterar (");
        // Chave Primaria
        $pk = db_query("select c.codcam,a.nomearq,c.nomecam,p.sequen, c.conteudo
                         from db_sysprikey p
                              inner join db_sysarquivo a on a.codarq = p.codarq
                              inner join db_syscampo c   on c.codcam = p.codcam
                         where a.codarq = " . pg_result($result, $i, "codarq") . " order by p.sequen");
        if (pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
          $virgula = "";
          for($p = 0; $p < $Npk; $p ++) {
            fputs($fd, $virgula . "$" . trim(pg_result($pk, $p, "nomecam")) . "=null");
            $virgula = ",";
          }
        } else {
          fputs($fd, ' $oid=null ');
        }
        fputs($fd, ") { \n");
        fputs($fd, '      $this->atualizacampos();' . "\n");
        fputs($fd, '     $sql = " update ' . trim(pg_result($result, $i, "nomearq")) . ' set ";' . "\n");
        fputs($fd, '     $virgula = "";' . "\n");
        for($j = 0; $j < $Ncampos; $j ++) {
          $x = pg_result($campo, $j, "conteudo");
          $nulo = pg_result($campo, $j, "nulo");
          if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "text") {
            fputs($fd, '     if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')!="" || isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"])) { ' . "\n");
            $aspas = "'";
          } else if (substr($x, 0, 4) == "date") {
            fputs($fd, '     if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')!="" || isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"] !="") ) { ' . "\n");
            $aspas = "'";
          } else if (substr($x, 0, 9) == "timestamp") {
            fputs($fd, '     if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')!="" || isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"] !="") ) { ' . "\n");
            $aspas = "'";
          } else if (substr($x, 0, 4) == "bool") {
            fputs($fd, '     if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')!="" || isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"])) { ' . "\n");
            $aspas = "'";
          } else if (substr($x, 0, 3) == "int" || substr($x, 0, 3) == "flo") {
            fputs($fd, '     if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')!="" || isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"])) { ' . "\n");
            if ($nulo == 't') {
              fputs($fd, '        if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')=="" && isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"])) { ' . "\n");
              fputs($fd, '           $this->' . trim(pg_result($campo, $j, "nomecam")) . ' = "0" ; ' . "\n");
              fputs($fd, '        } ' . "\n");
            }
            $aspas = "";
          } else {
            fputs($fd, '     if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ')!="" || isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"])) { ' . "\n");
            $aspas = "";
          }
          fputs($fd, '       $sql  .= $virgula." ' . trim(pg_result($campo, $j, "nomecam")) . ' = ' . $aspas . '$this->' . trim(pg_result($campo, $j, "nomecam")) . $aspas . ' ";' . "\n");
          fputs($fd, '       $virgula = ",";' . "\n");

          // inclusao de testes de erro em campos que aceitam null
          if ($nulo == 'f' || $nulo == '') {
            fputs($fd, '       if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ") == null ) { \n");
            fputs($fd, '         $this->erro_sql = " Campo ' . trim(pg_result($campo, $j, "rotulo")) . ' n�o informado.";' . "\n");
            $x = pg_result($campo, $j, "conteudo");
            if (substr($x, 0, 4) == "date" || substr($x, 0, 9) == "timestamp") {
              fputs($fd, '         $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '_dia";' . "\n");
            } else {
              fputs($fd, '         $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '";' . "\n");
            }
            fputs($fd, '         $this->erro_banco = "";' . "\n");
            fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
            fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
            fputs($fd, '         $this->erro_status = "0";' . "\n");
            fputs($fd, "         return false;\n");
            fputs($fd, "       }\n");
          }
          fputs($fd, "     }");
          if (substr($x, 0, 4) == "date" || substr($x, 0, 9) == "timestamp") {
            fputs($fd, "     else{ \n");
            fputs($fd, '       if (isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '_dia"])) { ' . "\n");
            fputs($fd, '         $sql  .= $virgula." ' . trim(pg_result($campo, $j, "nomecam")) . ' = null ";' . "\n");
            fputs($fd, '         $virgula = ",";' . "\n");
            // inclusao de testes de erro em campos que aceitam null
            $nulo = pg_result($campo, $j, "nulo");
            if ($nulo == 'f' || $nulo == '') {
              fputs($fd, '         if (trim($this->' . trim(pg_result($campo, $j, "nomecam")) . ") == null ) { \n");
              fputs($fd, '           $this->erro_sql = " Campo ' . trim(pg_result($campo, $j, "rotulo")) . ' n�o informado.";' . "\n");
              $x = pg_result($campo, $j, "conteudo");
              if (substr($x, 0, 4) == "date") {
                fputs($fd, '           $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '_dia";' . "\n");
              } else {
                fputs($fd, '           $this->erro_campo = "' . trim(pg_result($campo, $j, "nomecam")) . '";' . "\n");
              }
              fputs($fd, '           $this->erro_banco = "";' . "\n");
              fputs($fd, '           $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
              fputs($fd, '           $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
              fputs($fd, '           $this->erro_status = "0";' . "\n");
              fputs($fd, "           return false;\n");
              fputs($fd, "         }\n");
            }
            fputs($fd, "       }\n");
            fputs($fd, "     }");
          }
          fputs($fd, "\n");
        }
        fputs($fd, '     $sql .= " where ";' . "\n");
        if (pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
          $virgula = "";
          for($p = 0; $p < $Npk; $p ++) {

            fputs($fd, "     if ($" . trim(pg_result($pk, $p, "nomecam")) . "!=null) {\n");
            fputs($fd, '       $sql .= "' . $virgula . " " . trim(pg_result($pk, $p, "nomecam")) . " = ");
            $x = pg_result($pk, $p, "conteudo");
            if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "text" || substr($x, 0, 4) == "bool" || substr($x, 0, 9) == "timestamp" || substr($x, 0, 4) == "date") {
              $aspas = "'";
            } else {
              $aspas = "";
            }
            fputs($fd, $aspas . '$this->' . trim(pg_result($pk, $p, "nomecam")) . $aspas);
            $virgula = " and ";
            fputs($fd, '";' . "\n");
            fputs($fd, '     }' . "\n");
          }
        } else {
          fputs($fd, '$sql .= "oid = \'$oid\'";');
        }
        //fputs($fd,'";'."\n");
        if (pg_numrows($pk) > 0) {

          //aqui
          fputs($fd, '     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);'."\n");
          fputs($fd, '     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)'."\n");
          fputs($fd, '       && ($lSessaoDesativarAccount === false))) {'."\n\n");
          fputs($fd, '       $resaco = $this->sql_record($this->sql_query_file(');
          // coloca as chaves primerias
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            $virgula = "";
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, $virgula . '$this->' . trim(pg_result($pk, $p, "nomecam")));
              $virgula = ",";
            }
          }
          fputs($fd, '));' . "\n");
          fputs($fd, '       if ($this->numrows>0) {' . "\n\n");
          fputs($fd, '         for($conresaco=0;$conresaco<$this->numrows;$conresaco++) {' . "\n\n");

          fputs($fd, '           $resac = db_query("select nextval(\'db_acount_id_acount_seq\') as acount");' . "\n");
          fputs($fd, '           $acount = pg_result($resac,0,0);' . "\n");
          fputs($fd, '           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");' . "\n");
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, '           $resac = db_query("insert into db_acountkey values($acount,' . pg_result($pk, $p, "codcam") . ',\'$this->' . trim(pg_result($pk, $p, "nomecam")) . '\',\'A\')");' . "\n");
            }
          }
          for($j = 0; $j < $Ncampos; $j ++) {
            fputs($fd, '           if (isset($GLOBALS["HTTP_POST_VARS"]["' . trim(pg_result($campo, $j, "nomecam")) . '"]) || $this->'.trim(pg_result($campo, $j, "nomecam")).' != "")' . "\n");
            fputs($fd, '             $resac = db_query("insert into db_acount values($acount,' . pg_result($result, $i, 'codarq') . ',' . pg_result($campo, $j, "codcam") . ',\'".AddSlashes(pg_result($resaco,$conresaco,\'' . trim(pg_result($campo, $j, "nomecam")) . '\'))."\',\'$this->' . trim(pg_result($campo, $j, "nomecam")) . '\',".db_getsession(\'DB_datausu\').",".db_getsession(\'DB_id_usuario\').")");' . "\n");
          }
          fputs($fd, '         }' . "\n");
          fputs($fd, '       }' . "\n");
          fputs($fd, '     }' . "\n");
        }
        //
        fputs($fd, '     $' . 'result = db_query($sql);' . "\n");
        fputs($fd, '     if ($result==false) { ' . "\n");
        fputs($fd, '       $this->erro_banco = str_replace("\n","",@pg_last_error());' . "\n");
        fputs($fd, '       $this->erro_sql   = "' . pg_result($result, $i, 'rotulo') . ' nao Alterado. Alteracao Abortada.\\\n";' . "\n");
        if (! empty($varpk)) {
          fputs($fd, '         $this->erro_sql .= "Valores : ".' . $varpk . ';' . "\n");
        }
        fputs($fd, '       $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
        fputs($fd, '       $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
        fputs($fd, '       $this->erro_status = "0";' . "\n");
        fputs($fd, '       $this->numrows_alterar = 0;' . "\n");
        fputs($fd, '       return false;' . "\n");
        fputs($fd, "     } else {\n");
        fputs($fd, '       if (pg_affected_rows($result)==0) {' . "\n");
        fputs($fd, '         $this->erro_banco = "";' . "\n");
        fputs($fd, '         $this->erro_sql = "' . pg_result($result, $i, 'rotulo') . ' nao foi Alterado. Alteracao Executada.\\\n";' . "\n");
        if (! empty($varpk)) {
          fputs($fd, '         $this->erro_sql .= "Valores : ".' . $varpk . ';' . "\n");
        }
        fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
        fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
        fputs($fd, '         $this->erro_status = "1";' . "\n");
        fputs($fd, '         $this->numrows_alterar = 0;' . "\n");
        fputs($fd, '         return true;' . "\n");
        fputs($fd, "       } else {\n");
        fputs($fd, '         $this->erro_banco = "";' . "\n");
        fputs($fd, '         $this->erro_sql = "Altera��o efetuada com Sucesso\\\n";' . "\n");
        if (! empty($varpk)) {
          fputs($fd, '         $this->erro_sql .= "Valores : ".' . $varpk . ';' . "\n");
        }
        fputs($fd, '        $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
        fputs($fd, '        $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
        fputs($fd, '        $this->erro_status = "1";' . "\n");
        fputs($fd, '        $this->numrows_alterar = pg_affected_rows($result);' . "\n");
        fputs($fd, '        return true;' . "\n");
        fputs($fd, "      }\n");
        fputs($fd, "    }\n");
        fputs($fd, "  }\n");

      }

      //
      // Gera metodo para exclusao
      //
      if ($lGerarExcluir) {

        fputs($fd, "\n  // funcao para exclusao \n");
        fputs($fd, "  function excluir (");
        // Chave Primaria
        $pk = db_query("select c.codcam,a.nomearq,c.nomecam,p.sequen,c.conteudo
                         from db_sysprikey p
                              inner join db_sysarquivo a on a.codarq = p.codarq
                              inner join db_syscampo c   on c.codcam = p.codcam
                         where a.codarq = " . pg_result($result, $i, "codarq") . " order by p.sequen");
        if (pg_numrows($pk) > 0) {
          $Npk = pg_numrows($pk);
          $virgula = "";
          for($p = 0; $p < $Npk; $p ++) {
            fputs($fd, $virgula . "$" . trim(pg_result($pk, $p, "nomecam")) . "=null");
            $virgula = ",";
          }

        } else {
          fputs($fd, ' $oid=null ');
        }
        fputs($fd, ',$dbwhere=null) { ' . "\n\n");
        //          fputs($fd,'     $this->atualizacampos(true);'."\n");
        if (pg_numrows($pk) > 0) {

          fputs($fd, '     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);'."\n");
          fputs($fd, '     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)'."\n");
          fputs($fd, '       && ($lSessaoDesativarAccount === false))) {'."\n\n");
          fputs($fd, '       if ($dbwhere==null || $dbwhere=="") {' . "\n\n");
          //aqui
          fputs($fd, '         $resaco = $this->sql_record($this->sql_query_file(');
          // coloca as chaves primerias
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            $virgula = "";
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, $virgula . '$' . trim(pg_result($pk, $p, "nomecam")));
              $virgula = ",";
            }
          }
          fputs($fd, '));' . "\n");
          fputs($fd, '       } else { ' . "\n");
          //aqui
          fputs($fd, '         $resaco = $this->sql_record($this->sql_query_file(');
          // coloca as chaves primerias
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            $virgula = "";
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, $virgula . 'null');
              $virgula = ",";
            }
          }
          fputs($fd, ',"*",null,$dbwhere));' . "\n");
          fputs($fd, '       }' . "\n");

          fputs($fd, '       if (($resaco != false) || ($this->numrows!=0)) {' . "\n\n");
          fputs($fd, '         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {' . "\n\n");
          fputs($fd, '           $resac  = db_query("select nextval(\'db_acount_id_acount_seq\') as acount");' . "\n");
          fputs($fd, '           $acount = pg_result($resac,0,0);' . "\n");
          fputs($fd, '           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");' . "\n");
          if (pg_numrows($pk) > 0) {
            $Npk = pg_numrows($pk);
            for($p = 0; $p < $Npk; $p ++) {
              fputs($fd, '           $resac  = db_query("insert into db_acountkey values($acount,' . pg_result($pk, $p, "codcam") . ',\'$' . trim(pg_result($pk, $p, "nomecam")) . '\',\'E\')");' . "\n");
            }
          }
          for($j = 0; $j < $Ncampos; $j ++) {
            fputs($fd, '           $resac  = db_query("insert into db_acount values($acount,' . pg_result($result, $i, 'codarq') . ',' . pg_result($campo, $j, "codcam") . ',\'\',\'".AddSlashes(pg_result($resaco,$iresaco,\'' . trim(pg_result($campo, $j, "nomecam")) . '\'))."\',".db_getsession(\'DB_datausu\').",".db_getsession(\'DB_id_usuario\').")");' . "\n");
          }
          fputs($fd, '         }' . "\n");
          fputs($fd, '       }' . "\n");
          fputs($fd, '     }' . "\n");
        }
        fputs($fd, '     $' . 'sql = " delete from ' . trim(pg_result($result, $i, "nomearq")) . "\n");
        fputs($fd, '                    where ";' . "\n");
        fputs($fd, '     $' . 'sql2 = "";' . "\n");
        if (pg_numrows($pk) > 0) {
          fputs($fd, '     if ($dbwhere==null || $dbwhere =="") {' . "\n");
          $Npk = pg_numrows($pk);
          $virgula = "";
          for($p = 0; $p < $Npk; $p ++) {
            fputs($fd, '        if ($' . trim(pg_result($pk, $p, "nomecam")) . ' != "") {' . "\n");
            fputs($fd, '          if ($sql2!="") {' . "\n");
            fputs($fd, '            $sql2 .= " and ";' . "\n");
            fputs($fd, '          }' . "\n");
            fputs($fd, '          $sql2 .= " ' . trim(pg_result($pk, $p, "nomecam")) . ' = ');
            $x = pg_result($pk, $p, "conteudo");
            if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "text" || substr($x, 0, 4) == "bool" || substr($x, 0, 9) == "timestamp" || substr($x, 0, 4) == "date")
              $aspas = "'";
            else
              $aspas = "";
            fputs($fd, $aspas . '$' . trim(pg_result($pk, $p, "nomecam")) . $aspas . ' ";' . "\n");
            fputs($fd, '        }' . "\n");
          }
          fputs($fd, '     } else {' . "\n");
          fputs($fd, '       $sql2 = $dbwhere;' . "\n");
          fputs($fd, '     }' . "\n");

        } else {
          fputs($fd, '     if ($dbwhere==null || $dbwhere =="") {' . "\n");
          fputs($fd, '       $sql2 = "oid = \'$oid\'";' . "\n");
          fputs($fd, '     } else {' . "\n");
          fputs($fd, '       $sql2 = $dbwhere;' . "\n");
          fputs($fd, '     }' . "\n");

        }
      }

      fputs($fd, '     $' . 'result = db_query($sql.$sql2);' . "\n");
      fputs($fd, '     if ($result==false) { ' . "\n");
      fputs($fd, '       $this->erro_banco = str_replace("\n","",@pg_last_error());' . "\n");
      fputs($fd, '       $this->erro_sql   = "' . pg_result($result, $i, 'rotulo') . ' nao Exclu�do. Exclus�o Abortada.\\\n";' . "\n");
      if (! empty($varpk)) {
        fputs($fd, '       $this->erro_sql .= "Valores : ".' . $varpks . ';' . "\n");
      }
      fputs($fd, '       $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
      fputs($fd, '       $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
      fputs($fd, '       $this->erro_status = "0";' . "\n");
      fputs($fd, '       $this->numrows_excluir = 0;' . "\n");
      fputs($fd, '       return false;' . "\n");
      fputs($fd, "     } else {\n");
      fputs($fd, '       if (pg_affected_rows($result)==0) {' . "\n");
      fputs($fd, '         $this->erro_banco = "";' . "\n");
      fputs($fd, '         $this->erro_sql = "' . pg_result($result, $i, 'rotulo') . ' nao Encontrado. Exclus�o n�o Efetuada.\\\n";' . "\n");
      if (! empty($varpk)) {
        fputs($fd, '         $this->erro_sql .= "Valores : ".' . $varpks . ';' . "\n");
      }
      fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
      fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
      fputs($fd, '         $this->erro_status = "1";' . "\n");
      fputs($fd, '         $this->numrows_excluir = 0;' . "\n");
      fputs($fd, '         return true;' . "\n");
      fputs($fd, "       } else {\n");
      fputs($fd, '         $this->erro_banco = "";' . "\n");
      fputs($fd, '         $this->erro_sql = "Exclus�o efetuada com Sucesso\\\n";' . "\n");
      if (! empty($varpk)) {
        fputs($fd, '         $this->erro_sql .= "Valores : ".' . $varpks . ';' . "\n");
      }
      fputs($fd, '         $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
      fputs($fd, '         $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
      fputs($fd, '         $this->erro_status = "1";' . "\n");
      fputs($fd, '         $this->numrows_excluir = pg_affected_rows($result);' . "\n");
      fputs($fd, '         return true;' . "\n");
      fputs($fd, "      }\n");
      fputs($fd, "    }\n");
      fputs($fd, "  }\n");

    }

    //
    // Gera o metodo do sql_record
    //
    if ($lGerarSqlRecord) {

      fputs($fd, "\n  // funcao do recordset \n");
      fputs($fd, '  function sql_record($sql) { ' . "\n");
      fputs($fd, '     $' . 'result = db_query($sql);' . "\n");
      fputs($fd, '     if ($result==false) {' . "\n");
      fputs($fd, '       $this->numrows    = 0;' . "\n");
      fputs($fd, '       $this->erro_banco = str_replace("\n","",@pg_last_error());' . "\n");
      fputs($fd, '       $this->erro_sql   = "Erro ao selecionar os registros.";' . "\n");
      fputs($fd, '       $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
      fputs($fd, '       $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
      fputs($fd, '       $this->erro_status = "0";' . "\n");
      fputs($fd, '       return false;' . "\n");
      fputs($fd, '     }' . "\n");
      fputs($fd, '     $this->numrows = pg_numrows($result);' . "\n");
      fputs($fd, '      if ($this->numrows==0) {' . "\n");
      fputs($fd, '        $this->erro_banco = "";' . "\n");
      fputs($fd, '        $this->erro_sql   = "Record Vazio na Tabela:' . trim(pg_result($result, $i, "nomearq")) . '";' . "\n");
      fputs($fd, '        $this->erro_msg   = "Usu�rio: \\\n\\\n ".$this->erro_sql." \\\n\\\n";' . "\n");
      fputs($fd, '        $this->erro_msg   .=  str_replace(' . "'" . '"' . "'" . ',"",str_replace("' . "'" . '","",  "Administrador: \\\n\\\n ".$this->erro_banco." \\\n"));' . "\n");
      fputs($fd, '        $this->erro_status = "0";' . "\n");
      fputs($fd, '        return false;' . "\n");
      fputs($fd, '      }' . "\n");
      fputs($fd, '    return $result;' . "\n");
      fputs($fd, '  }' . "\n");

    }

    //
    // Geracao do sql_query
    //
    if ($lGerarSqlQuery) {

      fputs($fd, "\n  // funcao do sql \n");
      fputs($fd, "  function sql_query ( ");
      if (pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        $virgula = "";
        for($p = 0; $p < $Npk; $p ++) {
          fputs($fd, $virgula . "$" . trim(pg_result($pk, $p, "nomecam")) . "=null");
          $virgula = ",";
        }
        fputs($fd, ',$campos="*"');
      } else {
        fputs($fd, '$oid = null,$campos="' . trim(pg_result($result, $i, "nomearq")) . '.oid,*"');
      }
      fputs($fd, ',$ordem=null,$dbwhere="") { ' . "\n");
      fputs($fd, '     $sql = "select ";' . "\n");
      fputs($fd, '     if ($campos != "*" ) {' . "\n");
      fputs($fd, '       $campos_sql = explode("#", $campos);' . "\n");
      fputs($fd, '       $virgula = "";' . "\n");
      fputs($fd, '       for($i=0;$i<sizeof($campos_sql);$i++) {' . "\n");
      fputs($fd, '         $sql .= $virgula.$campos_sql[$i];' . "\n");
      fputs($fd, '         $virgula = ",";' . "\n");
      fputs($fd, '       }' . "\n");
      fputs($fd, '     } else {' . "\n");
      fputs($fd, '       $sql .= $campos;' . "\n");
      fputs($fd, '     }' . "\n");
      fputs($fd, '     $sql .= " from ' . trim(pg_result($result, $i, "nomearq")) . ' ";' . "\n");
      //
      // insere inner joins para as tabelas pai
      //
      global $temalias;
      global $qualalias;
      xx($fd, pg_result($result, $i, "codarq"));
      $fk = db_query("select distinct f.referen
                       from db_sysforkey f
                       where codarq = " . pg_result($result, $i, "codarq") . "
             order by f.referen");

      if (pg_numrows($fk) > 0) {
        for($ref = 0; $ref < pg_numrows($fk); $ref ++) {
          xx($fd, pg_result($fk, $ref, "referen"));
        }
      }
      fputs($fd, '     $sql2 = "";' . "\n");
      fputs($fd, '     if ($dbwhere=="") {' . "\n");
      if (pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        for($p = 0; $p < $Npk; $p ++) {
          $x = pg_result($pk, $p, "conteudo");
          if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "text" || substr($x, 0, 4) == "bool" || substr($x, 0, 4) == "timestamp" || substr($x, 0, 4) == "date")
            $aspas = "'";
          else
            $aspas = "";
          if ($p == 0) {
            fputs($fd, "       if ($" . trim(pg_result($pk, $p, "nomecam")) . "!=null ) {" . "\n");
            fputs($fd, '         $sql2 .= " where ' . trim(pg_result($pk, $p, "nomearq")) . '.' . trim(pg_result($pk, $p, "nomecam")) . " = " . $aspas . "$" . trim(pg_result($pk, $p, "nomecam")) . $aspas . " \"; \n");
            fputs($fd, '       } ' . "\n");
          } else {
            fputs($fd, "       if ($" . trim(pg_result($pk, $p, "nomecam")) . "!=null ) {" . "\n");
            fputs($fd, '         if ($sql2!="") {' . "\n");
            fputs($fd, '            $sql2 .= " and ";' . "\n");
            fputs($fd, '         } else {' . "\n");
            fputs($fd, '            $sql2 .= " where ";' . "\n");
            fputs($fd, '         } ' . "\n");
            fputs($fd, '         $sql2 .= " ' . trim(pg_result($pk, $p, "nomearq")) . '.' . trim(pg_result($pk, $p, "nomecam")) . ' = ' . $aspas . "$" . trim(pg_result($pk, $p, "nomecam")) . $aspas . ' ";' . " \n");
            fputs($fd, '       } ' . "\n");
          }
        }
      } else {
        fputs($fd, '       if ( $oid != "" && $oid != null) {' . "\n");
        fputs($fd, '          $sql2 = " where ' . trim(pg_result($result, $i, "nomearq")) . '.oid = \'$oid\'";' . "\n");
        fputs($fd, '       }' . "\n");
      }
      fputs($fd, '     } else if ($dbwhere != "") {' . "\n");
      fputs($fd, '       $sql2 = " where $dbwhere";' . "\n");
      fputs($fd, '     }' . "\n");
      fputs($fd, '     $sql .= $sql2;' . "\n");
      fputs($fd, '     if ($ordem != null ) {' . "\n");
      fputs($fd, '       $sql .= " order by ";' . "\n");
      fputs($fd, '       $campos_sql = explode("#", $ordem);' . "\n");
      fputs($fd, '       $virgula = "";' . "\n");
      fputs($fd, '       for($i=0;$i<sizeof($campos_sql);$i++) {' . "\n");
      fputs($fd, '         $sql .= $virgula.$campos_sql[$i];' . "\n");
      fputs($fd, '         $virgula = ",";' . "\n");
      fputs($fd, '      }' . "\n");
      fputs($fd, '    }' . "\n");
      fputs($fd, '    return $sql;' . "\n");
      fputs($fd, '  }' . "\n");

    }

    //
    // Gera o metodo sql_query_file
    //
    if ($lGerarSqlQueryFile) {

      fputs($fd, "\n  // funcao do sql \n");
      fputs($fd, "  function sql_query_file ( ");
      if (pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        $virgula = "";
        for($p = 0; $p < $Npk; $p ++) {
          fputs($fd, $virgula . "$" . trim(pg_result($pk, $p, "nomecam")) . "=null");
          $virgula = ",";
        }
      } else {
        fputs($fd, '$oid = null');
      }
      fputs($fd, ',$campos="*",$ordem=null,$dbwhere="") { ' . "\n");
      fputs($fd, '     $sql = "select ";' . "\n");
      fputs($fd, '     if ($campos != "*" ) {' . "\n");
      fputs($fd, '       $campos_sql = explode("#", $campos);' . "\n");
      fputs($fd, '       $virgula = "";' . "\n");
      fputs($fd, '       for($i=0;$i<sizeof($campos_sql);$i++) {' . "\n");
      fputs($fd, '         $sql .= $virgula.$campos_sql[$i];' . "\n");
      fputs($fd, '         $virgula = ",";' . "\n");
      fputs($fd, '       }' . "\n");
      fputs($fd, '     } else {' . "\n");
      fputs($fd, '       $sql .= $campos;' . "\n");
      fputs($fd, '     }' . "\n");
      fputs($fd, '     $sql .= " from ' . trim(pg_result($result, $i, "nomearq")) . ' ";' . "\n");
      fputs($fd, '     $sql2 = "";' . "\n");
      fputs($fd, '     if ($dbwhere=="") {' . "\n");
      if (pg_numrows($pk) > 0) {
        $Npk = pg_numrows($pk);
        for($p = 0; $p < $Npk; $p ++) {
          $x = pg_result($pk, $p, "conteudo");
          if (substr($x, 0, 4) == "char" || substr($x, 0, 4) == "varc" || substr($x, 0, 4) == "text" || substr($x, 0, 4) == "bool" || substr($x, 0, 4) == "timestamp" || substr($x, 0, 4) == "date")
            $aspas = "'";
          else
            $aspas = "";
          if ($p == 0) {
            fputs($fd, "       if ($" . trim(pg_result($pk, $p, "nomecam")) . "!=null ) {" . "\n");
            fputs($fd, '         $sql2 .= " where ' . trim(pg_result($pk, $p, "nomearq")) . '.' . trim(pg_result($pk, $p, "nomecam")) . " = " . $aspas . "$" . trim(pg_result($pk, $p, "nomecam")) . $aspas . " \"; \n");
            fputs($fd, '       } ' . "\n");
          } else {
            fputs($fd, "       if ($" . trim(pg_result($pk, $p, "nomecam")) . "!=null ) {" . "\n");
            fputs($fd, '         if ($sql2!="") {' . "\n");
            fputs($fd, '            $sql2 .= " and ";' . "\n");
            fputs($fd, '         } else {' . "\n");
            fputs($fd, '            $sql2 .= " where ";' . "\n");
            fputs($fd, '         } ' . "\n");
            fputs($fd, '         $sql2 .= " ' . trim(pg_result($pk, $p, "nomearq")) . '.' . trim(pg_result($pk, $p, "nomecam")) . ' = ' . $aspas . "$" . trim(pg_result($pk, $p, "nomecam")) . $aspas . ' ";' . " \n");
            fputs($fd, '       } ' . "\n");
          }
        }
      }
      fputs($fd, '     } else if ($dbwhere != "") {' . "\n");
      fputs($fd, '       $sql2 = " where $dbwhere";' . "\n");
      fputs($fd, '     }' . "\n");
      fputs($fd, '     $sql .= $sql2;' . "\n");
      fputs($fd, '     if ($ordem != null ) {' . "\n");
      fputs($fd, '       $sql .= " order by ";' . "\n");
      fputs($fd, '       $campos_sql = explode("#", $ordem);' . "\n");
      fputs($fd, '       $virgula = "";' . "\n");
      fputs($fd, '       for($i=0;$i<sizeof($campos_sql);$i++) {' . "\n");
      fputs($fd, '         $sql .= $virgula.$campos_sql[$i];' . "\n");
      fputs($fd, '         $virgula = ",";' . "\n");
      fputs($fd, '      }' . "\n");
      fputs($fd, '    }' . "\n");
      fputs($fd, '    return $sql;' . "\n");
      fputs($fd, '  }' . "\n");

    }

    //
    // Gerando os metodos externos cadastrados na documentacao
    //
    if ($lGerarMetodosExternos) {

      $sql = "select codigoclass from db_sysclasses where codarq = " . pg_result($result, 0, 'codarq');
      $resultclass = db_query($sql);

      if (pg_numrows($resultclass) > 0) {
        for($c = 0; $c < pg_numrows($resultclass); $c ++) {
          // fputs($fd,trim(pg_result($recultclass,$c,'descrclass'))."\n");
          fputs($fd, "   " . str_replace("\r\n", "\n", trim(pg_result($resultclass, $c, 'codigoclass'))) . "\n");
        }
      }

    }

    //
    // Finaliza a classe
    //
    fputs($fd, "}\n");
  }
}

fputs($fd, "?>\n");
fclose($fd);

$sHtml  = " <table width='100%'> ";
$sHtml .= "   <tr> ";
$sHtml .= "     <td align='center'> ";
if ( ! $lGeral )  {
  $sHtml .= "       <h3>Conclu�do...</h3> ";
}
$sHtml .= "     </td> ";
$sHtml .= "   </tr> ";
$sHtml .= " </table> ";
echo $sHtml;

}

if ($lGeral) {
  echo "Total de classes geradas : $iIndice";
}


$sHtml  = "</body> ";
$sHtml .= "</html> ";
echo $sHtml;

?>
