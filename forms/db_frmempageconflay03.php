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

$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e82_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e87_descgera");
?>
<script>
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";

  if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:.
    return true;
  }else{
    return false;
  }
}

function js_pesquisar(form){
  query = "&e80_codage=<?=$e80_codage?>";


  //selecionadas,naum selecionadas ou todas
  ordem.location.href = "emp4_empageconflay003_ordem.php?"+query;
}
function js_atualizar(){
        if(document.form1.e87_descgera.value == ''){
	  alert('Preencha o campo descri��o.');
          document.form1.e87_descgera.style.backgroundColor='#99A9AE';
          document.form1.e87_descgera.focus();
	  return false;
	}
	if(ordem.document.form1){
          obj = ordem.document.form1;
	  var coluna='';
	  var sep='';
	  for(i=0; i<obj.length; i++){
	    nome = obj[i].name.substr(0,5);

	    if(nome=="CHECK" && obj[i].checked==true){
	      ord = obj[i].name.substring(6);
	      codtipo =  eval("obj.e83_codtipo_"+obj[i].value+".value");
	      coluna += sep+obj[i].value;
	      sep= "XX";
	    }
	  }
	  if(coluna==''){
	    alert("Selecione um movimento!");
	    return false;
	  }
	  document.form1.movs.value = coluna;
	  document.form1.codtipo.value = codtipo;

	  return true;
        }else{
	  alert("Clique em pesquisar para selecionar um movimento!");
	  return false;
	}
	//return coluna ;

}

function js_ver(){


  query = "?e80_codage=<?=$e80_codage?>";
  if(document.form1.e83_codtipo.value!=0){
    query += "&e83_codtipo="+document.form1.e83_codtipo.value;
  }
  js_OpenJanelaIframe('','db_iframe_anula','func_empageconf001.php'+query,'Pesquisa',true);
}
function js_gerar(){


  query = "?e80_codage=<?=$e80_codage?>";
  if(document.form1.e83_codtipo.value!=0){
    query += "&e83_codtipo="+document.form1.e83_codtipo.value;
  }
  js_OpenJanelaIframe('','db_iframe_gerar','emp4_empageconflay003.php'+query,'Pesquisa',true);
}
function js_anular(){


  query = "?e80_codage=<?=$e80_codage?>";
  if(document.form1.e83_codtipo.value!=0){
    query += "&e83_codtipo="+document.form1.e83_codtipo.value;
  }
  js_OpenJanelaIframe('','db_iframe_anula','emp4_empageconfcanc001.php'+query,'Pesquisa',true);
}


</script>
<form name="form1" method="post" action="">
  <?=db_input('movs',10,'',true,'hidden',10);?>
  <?=db_input('codtipo',10,'',true,'hidden',10);?>

<center>
  <table border='0' cellpadding='0' cellspacing='0'>
      <tr>
        <td>
          <table>
	    <tr>
	      <td nowrap title="<?=@$Te80_codage?>" align='right'>
	      <?=$Le80_codage?>
	      </td>
	      <td nowrap>
		 <? db_input('e80_codage',10,$Ie80_codage,true,'text',3)?>
	      <?=$Le80_data?>
	       <?
                  $result07= $clempage->sql_record($clempage->sql_query_file(null, "*", null, " e80_codage = $e80_codage and e80_instit = " . db_getsession("DB_instit")));
                  db_fieldsmemory($result07,0);
                   $arr_x  = explode("-",$e80_data);
		   $e80_data_ano = $arr_x[0];
		   $e80_data_mes = $arr_x[1];
		   $e80_data_dia = $arr_x[2];

		 db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',3);


		 $result05 = $clempagemod->sql_record($clempagemod->sql_query_file($e84_codmod));
		 db_fieldsmemory($result05,0);

	         echo  $Le84_codmod;
		 db_input('e84_codmod',20,'',true,'hidden',3);
		 db_input('e84_descr',20,'',true,'text',3);

	       ?>
	      </td>
	    </tr>
	    <tr>
	      <td>
	         <?=$Le87_descgera?>
	      </td>
	      <td>
		 <?db_input('e87_descgera',40,'',true,'text',1);?>
	      </td>
	    </tr>
	</table>

      </td>
      <td align='left'>
        <table width='100%' cellpadding='0' cellspacing='0' >
	  <tr>
	    <td nowrap><b>Data gera��o:</b></td>
	    <td>
              <?db_inputdata('dtin',date('d',db_getsession('DB_datausu')),date('m',db_getsession('DB_datausu')),date('Y',db_getsession('DB_datausu')),true,'text',1);?>
	    </td>
	  </tr>
	  <tr>
	    <td><b>Data dep�sito:</b></td>
	    <td>
              <?db_inputdata('deposito',date('d',db_getsession('DB_datausu')),date('m',db_getsession('DB_datausu')),date('Y',db_getsession('DB_datausu')),true,'text',1);?>
	    </td>
	  </tr>
	  <tr>
	    <td><b>Conv�nio:</b></td>
	    <td>
	    <?
            $result =  $clempagetipo->sql_record($clempagetipo->sql_query($e83_codtipo,'e83_convenio as e83_conv'));
	    if($clempagetipo->numrows>0){
	      db_fieldsmemory($result,0);
	    }else{
	      $e83_conv='0';
	    }
            db_input('e83_conv',10,'',true,'text',3,'');
	    ?>
	    </td>
	  </tr>

	</table>
      </td>
    </tr>
    <tr>
      <td colspan='2' align='center'>
      <?if(isset($arquivo) && isset($atualizar) && $sqlerro == false){?>
	<input name="emite" type="button" id="pesquisar" value="Reemitir arquivo" onclick='return js_emitir();'>
      <?}?>
	<input name="atualizar" type="submit" id="pesquisar" value="Gerar arquivo" onclick='return js_atualizar();'>
	<input name="entrar_codord" type="button" id="pesquisar" value="Pesquisar" onclick='js_pesquisar(this.form);' >
	<input name="fechar" type="button"  value="Fechar" onclick='parent.db_iframe_gerar.hide();' >

	    <b>Total: </b>
	     <?=db_input('total',10,'',true,'text',3)?>

      </td>
    </tr>
   <tr>
     <td colspan='2' >
       <iframe name="ordem"   width="760" height="270" marginwidth="0" marginheight="0" frameborder="0" >
       </iframe>
     *<small>Sem conta cadastrada</small>
     </td>
   </tr>
  </table>
  </center>
</form>
<script>
document.form1.total.value='0.00';
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho02.hide();
}


function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}

//------------------------------------------------------------
function js_mostraempempenho(chave,erro){
  if(erro==true){
    document.form1.e60_numemp.focus();
    document.form1.e60_numemp.value = '';
  }
}
function js_mostraempempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}

//-----------------------------------------------------------
//---ordem 01
function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e82_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione uma ordem menor que a segunda!");
      document.form1.e82_codord.focus();
      document.form1.e82_codord.value = '';
      return false;
    }
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord01+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
  }
}
function js_mostrapagordem(chave,erro){
  if(erro==true){
    document.form1.e82_codord.focus();
    document.form1.e82_codord.value = '';
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e82_codord.value = chave1;
  db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae82_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e82_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione uma ordem maior que a primeira");
      document.form1.e82_codord02.focus();
      document.form1.e82_codord02.value = '';
      return false;
    }
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord02+'&funcao_js=parent.js_mostrapagordem02','Pesquisa',false);
  }
}
function js_mostrapagordem02(chave,erro){
  if(erro==true){
    document.form1.e82_codord02.focus();
    document.form1.e82_codord02.value = '';
  }
}
function js_mostrapagordem102(chave1,chave2){
  document.form1.e82_codord02.value = chave1;
  db_iframe_pagordem.hide();
}

//---------------------------------------------------
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
//------------------------------------------------------------
</script>
