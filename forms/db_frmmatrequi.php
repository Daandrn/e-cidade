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
$clmatrequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
  if (isset($m40_codigo)&&$m40_codigo!=""){
    $result_jahatend=$clmatrequiitem->sql_record(
        $clmatrequiitem->sql_query_atend(
            null,
            '*',
            null,
            "m41_codmatrequi=$m40_codigo and (m43_codigo is not null OR m102_sequencial is not null)"
        )
    );

    if ($clmatrequiitem->numrows!=0){
       $opcao= $db_opcao;
       $db_opcao=3;
       $db_botao=false;
       db_msgbox('J� existe um atendimento ou uma anula��o para esta requisi��o!!');
    }
  }
  $id_usuario = db_getsession('DB_id_usuario');
  $sql = "select *
    from db_permissao p
        inner join db_itensmenu m on m.id_item = p .id_item
        inner join db_menu on db_menu.id_item_filho = m.id_item
            where p.anousu = ".date('Y')."
              and p.id_item in (select id_item from db_itensmenu where descricao = 'Cancelar Finalizacao' and help = 'Cancelar Finalizacao' and funcao = '' and desctec = 'Cancelar Finalizacao')
              and db_menu.modulo = 480
              and id_usuario = {$id_usuario}";
  $res = pg_query($sql);
  $permissao = 0;

  if (pg_numrows($res) > 0 || $id_usuario == 1) {
    $permissao = 1;
  }
?>
<form name="form1" method="post" action="">
    <center>
        <fieldset style="width: 50%" ><legend><b>Dados da requisi��o</b></legend>
            <table border="0">
                <tr>
                    <td nowrap title="<?=@$Tm40_codigo?>">
                        <b>C�digo: </b>
                        <?//=@$Lm40_codigo?>
                    </td>
                    <td>
                        <? db_input('m40_codigo',10,$Im40_codigo,true,'text',3,"") ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tm40_data?>">
                        <?=@$Lm40_data?>
                    </td>
                    <td>
                        <? db_inputdata('m40_data',@$m40_data_dia,@$m40_data_mes,@$m40_data_ano,true,'text',3,"") ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tm40_depto?>">
                        <? db_ancora(@$Lm40_depto,"js_pesquisam40_depto(true);",3); ?>
                    </td>
                    <td>
                        <? db_input('m40_depto',6,$Im40_depto,true,'text',3," onchange='js_pesquisam40_depto(false);'") ?>
                        <? db_input('descrdepto',40,$Idescrdepto,true,'text',3,'') ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tm40_login?>">
                        <? db_ancora(@$Lm40_login,"js_pesquisam40_login(true);",3); ?>
                    </td>
                    <td>
                        <? db_input('m40_login',10,$Im40_login,true,'text',3," onchange='js_pesquisam40_login(false);'") ?>
                        <? db_input('nome',40,$Inome,true,'text',3,'') ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tm40_hora?>">
                        <?=@$Lm40_hora?>
                    </td>
                    <td>
                        <? db_input('m40_hora',5,$Im40_hora,true,'text',3,"") ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tm40_almox?>">
                        <?=@$Lm40_almox?>
                    </td>
                    <td>
                        <?
                            $result_depusu = $cldb_depusu->sql_record(
                                                $cldb_depusu->sql_query_almoxusu(
                                                    null,
                                                    null,
                                                    " distinct m91_codigo as almoxarifado, db_depart.descrdepto",
                                                    null,
                                                    "     db_depusu.id_usuario = " . db_getsession("DB_id_usuario") . " and db_depart.instit = ".db_getsession("DB_instit") . " order by almoxarifado")
                                                );
                            if ($cldb_depusu->numrows>0){
                                db_selectrecord('m40_almox',$result_depusu,true,($db_opcao == 1?1:3));
                            } else {
                                echo "Nenhum almoxarifado dispon�vel!";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tm40_obs?>">
                        <?=@$Lm40_obs?>
                    </td>
                    <td>
                        <? db_textarea('m40_obs',10,50,$Im40_obs,true,'text',$db_opcao,"") ?>
                    </td>
                </tr>
            </table>
        </fieldset>
  </center>
  <?
    if (isset($opcao)){
        $db_opcao=@$opcao;
    }
  ?>
    <input
        name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
        type="submit"
        id="db_opcao"
        value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?>
    >
    <input
        name="pesquisar"
        type="button"
        id="pesquisar"
        value="Pesquisar"
        onclick="js_pesquisa();"
    >
</form>
<script>
function js_pesquisam40_depto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.m40_depto.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.m40_depto.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.m40_depto.focus();
    document.form1.m40_depto.value = '';
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.m40_depto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisam40_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.m40_login.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.m40_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.m40_login.focus();
    document.form1.m40_login.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.m40_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_matrequi','func_matrequi.php?funcao_js=parent.js_preenchepesquisa|m40_codigo&departamento=true&permissao=<? echo $permissao; ?>','Pesquisa',true,"0");
}
function js_preenchepesquisa(chave){
  db_iframe_matrequi.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;\n";
    if($db_opcao==3||$db_opcao==33){
      echo " parent.iframe_matrequiitem.location.href='mat1_matrequiitemalt001.php?m40_codigo='+chave+'&db_opcao=3'; \n";
    }else{
      echo " parent.iframe_matrequiitem.location.href='mat1_matrequiitemalt001.php?m40_codigo='+chave;\n";
    }
    echo " parent.document.formaba.matrequiitem.disabled = false;\n";
  }
  ?>
}
</script>
