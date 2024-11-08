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

$campos = "acervo.bi06_seq,
           acervo.bi06_titulo,
           localizacao.bi09_nome,
           localacervo.bi20_sequencia as dl_ordenacao,
           (select array(select bi23_codigo
                         from exemplar
                         where bi23_acervo = bi06_seq
                        )
           ) as dl_Exemplares,
           (select array(select bi01_nome
                         from autoracervo
                          inner join autor on bi01_codigo = bi21_autor
                         where bi21_acervo = bi06_seq
                        )
           ) as dl_autores,
           acervo.bi06_edicao,
           acervo.bi06_volume,
           acervo.bi06_dataedicao
          ";
?>