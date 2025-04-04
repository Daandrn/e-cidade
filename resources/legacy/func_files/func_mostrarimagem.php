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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

if ( !empty($oid) ) {
  // Inicia transa��o (Obrigat�rio)
  db_query("begin");

  // Abrindo o objeto no modo leitura "r" passando como par�metro o OID.
  $objetoleitura = pg_lo_open($oid,"r");

  // Setando Cabe�alho do browser para interpretar que o bin�rio que ser� carregado � de uma foto do tipo JPEG.
  header("Content-Type: image/jpeg");

  // Lendo bin�rio da foto.
  $mostrar = pg_lo_read_all($objetoleitura);

  // Fechando Objeto que foi aberto para leitura
  pg_lo_close($objetoleitura);

  // Finaliza transa��o
  db_query("commit");
}
?>