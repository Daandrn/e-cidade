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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

$aux = new cl_arquivo_auxiliar;

?>
<html>

<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC bgcolor="#CCCCCC">
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td> &nbsp; </td>
    </tr>
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <form name="form1" method="post" action="">
            <table border="0">
              <tr>
                <td align="center">
                  <strong>Op��es:</strong>
                  <select name="verconvenio">
                    <option value="com">Com os Conv�nios selecionados</option>
                    <option value="sem">Sem os Conv�nios selecionados</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td nowrap width="50%">
                  <?
                  $aux->cabecalho      = "<strong>Conv�nios</strong>";
                  $aux->codigo         = "c206_sequencial";
                  $aux->descr          = "c206_objetoconvenio";
                  $aux->nomeobjeto     = 'convconvenios';
                  $aux->funcao_js      = 'js_mostra';
                  $aux->funcao_js_hide = 'js_mostra1';
                  $aux->sql_exec       = "";
                  $aux->func_arquivo   = "func_convconvenios.php";
                  $aux->parametros     = "+'&filtro=despesa'";
                  $aux->nomeiframe     = "db_iframe_convconvenios";
                  $aux->localjan       = "";
                  $aux->onclick        = "";
                  $aux->db_opcao       = 2;
                  $aux->tipo           = 2;
                  $aux->top            = 1;
                  $aux->linhas         = 10;
                  $aux->vwhidth        = 400;
                  $aux->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>
        </center>
        </form>
      </td>
    </tr>
  </table>
</body>

</html>