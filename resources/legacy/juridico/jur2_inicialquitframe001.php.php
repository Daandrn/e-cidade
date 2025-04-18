<?
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_inicial_classe.php");
  include("classes/db_inicialcodforo_classe.php");
  include("classes/db_iptubase_classe.php");
  include("classes/db_promitente_classe.php");
  include("classes/db_propri_classe.php");
  include("classes/db_jurpeticoes_classe.php");
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_POST_VARS);

  $clinicial = new cl_inicial;
  $clinicialcodforo = new cl_inicialcodforo;
  $cliptubase = new cl_iptubase;
  $clpromitente = new cl_promitente;
  $clpropri = new cl_propri;
  $cljurpeticoes = new cl_jurpeticoes;

  $clrotulo = new rotulocampo;
  $clrotulo->label("v50_inicial");
  $clrotulo->label("v52_descr");
  $clrotulo->label("v53_descr");
  $clrotulo->label("v54_descr");
  $clrotulo->label("z01_nome");

  $db_botao=1;
  $db_opcao=1;
  $dadosini="";

  if(isset($iniciais)){
    
     $matriz = explode("x",$iniciais);
     for($s=0; $s < sizeof($matriz); $s++){
       $inicial=$matriz[$s];
       
       if($inicial!=""){
       	/*
	 $sqlerro=false;
         db_inicio_transacao();
         
	 $cljurpeticoes->v60_inicial=$inicial;
	 $cljurpeticoes->v60_tipopet=2;
	 $cljurpeticoes->v60_data=date('Y-m-d',db_getsession('DB_datausu'));
	 $cljurpeticoes->v60_hora=db_hora();
	 $cljurpeticoes->v60_usuario=db_getsession('DB_id_usuario');
	 $cljurpeticoes->v60_texto="''";
	 $cljurpeticoes->incluir(null);
	 if ($cljurpeticoes->erro_status==0){
	   $erro=$cljurpeticoes->erro_msg;
	   $sqlerro=true;
	   db_msgbox($erro);
	 }
     db_fim_transacao($sqlerro);
     
  	 $res = $clinicial->sql_record($clinicial->sql_query($inicial,"z01_nome as advogado,v57_oab")); 
 	 $numrows= $clinicial->numrows;
	 if($numrows==0){
 	  echo   "<script>parent.location.href='jur2_inicialquit001.php?testini=false';</script>";
	 }
 	 $result = $clinicialcodforo->sql_record($clinicialcodforo->sql_query_file($inicial,"v55_codforo")); 
	 $numrows= $clinicialcodforo->numrows;
	 if($numrows==0){
 	  echo   "<script>parent.location.href='jur2_inicialquit001.php?codforo=false';</script>";
	 }
	 
	 $sql="
	   select distinct k00_inscr,k00_matric from inicial
		  inner join inicialcert 
			  on v50_inicial=v51_inicial 
		inner join inicialcodforo
			on v55_inicial=v51_inicial
		inner join certid 
			on v13_certid=v51_certidao
		inner join certdiv 
			on v14_certid=v13_certid
		inner join divida 
			on v14_coddiv=v01_coddiv
		left outer join arreinscr 
			on arreinscr.k00_numpre=v01_numpre
		left outer join arrematric
			on arrematric.k00_numpre=v01_numpre
	  where v50_inicial = $inicial";
	  */
	 $sql="select k00_inscr,k00_matric from inicialnumpre
                      left outer join arreinscr on arreinscr.k00_numpre=v59_numpre
                      left outer join arrematric on arrematric.k00_numpre=v59_numpre
               where v59_inicial = $inicial";
	  
         $result = pg_query($sql);
         if (pg_numrows($result)!=0){
	   db_fieldsmemory($result,0);
	 }
	 
       if(isset($k00_matric)&&$k00_matric!=""){
  	   $modo="matricula";
	   $j01_matric = $k00_matric;
 	   $chave = $j01_matric;
         }
       if(isset($k00_inscr)&&$k00_inscr!=""){
   	   $modo="inscricao";
 	   $q02_inscr = $k00_inscr;
	   $chave = $q02_inscr;
         }
      
	   $dadosini .= "xx".@$inicial."ww".@$chave."ww".@$modo;
	   	   
      } 
    }
    
    echo "
       <script>
           jan = window.open('jur2_inicialquit002.php?dadosini=$dadosini','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
           jan.moveTo(0,0);
           //location.href='jur2_inicialquit001.php';
       </script>";  
  }
   
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
<script>
function js_pesquisar(inicial){
  location.href="jur2_inicialquit001.php?v50_inicial="+inicial;
}
function js_marca(obj){
  var OBJ = document.form1;
  for(i=0;i<OBJ.length;i++){
    if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);
    }
  }
  return false;
}
function js_gera(){
  obj=document.getElementsByTagName("INPUT");
  var nums="";
  var t="";
  var ent=false;
  for(var i=0; i<obj.length; i++){
    if(obj[i].type=="checkbox"){
       if(obj[i].checked){
         nums += t+obj[i].value;
         var ent=true;
       } 	 
       t="x";
    }
  }
  if(ent==false){
    alert("Marque um das op��es!");
  }else{
    obj=document.getElementsByTagName("INPUT");
    var inis="";
    var t="";
   var ent=false;
    for(var i=0; i<obj.length; i++){
       if(obj[i].type=="checkbox"){
         if(obj[i].checked){
           inis += t+obj[i].value;
           var ent=true;
           t="x";
         } 	 
       }
    }
    document.form1.iniciais.value=inis;  
    document.form1.submit();
  }  
}
</script>
</head>
<body bgcolor=#CCCCCC  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="596" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
<form name="form1" method="post" action="">
  <tr>
   <td valign="top">
       <input type="hidden" name="iniciais">
       <br><br>
	<table border="1"  align="center">	    
        <tr>
	  <td colspan="6" align="center">
   	  <b>INICIAIS QUITADAS</b>
	  </td>
	</tr>  
        <tr>
          <td><a  title='Inverte Marca��o' href='' onclick='return js_marca(this);return false;'><b>T</b></a></td>
          <td><b>Inicial</b></td>
          <td><b>Situa��o</b></td>
          <td><b>Localiza��o</b></td>
          <td><b>Advogado</b></td>
         </tr>
	<?
      $result=$clinicial->sql_record($clinicial->sql_query_sit("","cgm.z01_nome,v50_inicial,v52_descr,v54_descr","v50_inicial","v56_codsit=8 and v60_inicial is null"));
	  $numrows=$clinicial->numrows;
	  for($i=0; $i<$numrows; $i++){
  	    db_fieldsmemory($result,$i);
	    echo "<tr>  
                  <td align='left'><input name='check_".$i."' type='checkbox' value='$v50_inicial'></td>
                  <td>$v50_inicial</td>
	          <td>$v52_descr</td>
	          <td>$v54_descr</td>
	          <td>$z01_nome</td>
		  </tr>";
	           
	  }  
      ?>  
        </table>
    </td>
  </tr>
</form>
</table>
</body>
</html>
<script>
function js_veri(){
  if(document.form1.v50_inicial.value==""){
    alert("Indique uma inicial!");
    return false;
  }
  return true;
}
function js_pesquisav50_inicial(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicial1|0';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostrainicial';
  }
}
function js_mostrainicial1(chave){
  document.form1.v50_inicial.value=chave;    
  db_iframe.hide();
}
function js_mostrainicial(chave,erro){
  if(erro==true){
    document.form1.v50_inicial.focus(); 
  }else{
    document.form1.v50_inicial.value=chave;    
  }
}
</script>
