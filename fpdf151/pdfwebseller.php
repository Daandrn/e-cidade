<?
//WebSeller
set_time_limit(0);
if (!defined('DB_BIBLIOT')) {

  session_cache_limiter('none');
  session_start();

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);

  define('FPDF_FONTPATH', 'font/');
  require_once('fpdf.php');
}


class PDF extends FPDF
{
  //|00|//pdf
  //|10|//Esta classe � uma extens�o da classe |fpdf| e difere da mesma pelo fato de que nesta  classe
  //|10|//foram alterados os m�todos |header| (cabe�alho da p�gina) de  |footer|  (rodap�)  para   que
  //|10|//atendessem as nossas necessidades, da seguinte maneira:
  //|10|//|header|     :    - O logotipo da prefeitura ficou alinhado a esquerda;
  //|10|//                  - Os dados da prefeitura tais como: nome,  ender�o,  munic�pio,  telefone,
  //|10|//                    email, e site ficaram alinhados a  esquerda,  ao  lado  do  logotipo  da
  //|10|//                    prefeitura;
  //|10|//               Contem ainda vari�veis livres para o desenvolvedor as quais  ser�o  impressas
  //|10|//               na parte superior direita da tela, s�o elas:
  //|10|//                  - head1, head2, head3, head4, head5, head6, head7, head8, head9
  //|10|//
  //|10|//|footer|     :    - contem dados como:
  //|10|//                      - programa que gerou o relat�rio;
  //|10|//                      - emissor;
  //|10|//                      - exerc�cio;
  //|10|//                      - data e hora da emiss�o;
  //|10|//                      - n�mero da p�gina.

  //Page header

  function Header()
  {
    //#00#//header
    //#10#//Este m�todo � usado gerar o cabe�alho da p�gina. � chamado automaticamente por |addPage| e n�o
    //#10#//deve ser chamado diretamente pela aplica��o. A implementa��o em FPDF est�  vazia,  ent�o  voc�
    //#10#//precisa criar uma subclasse dele para  sobrepor o  m�todo  se  voc�  quiser  um  processamento
    //#10#//espec�fico para o cabe�alho.
    //#15#//header()
    //#99#//Exemplo:
    //#99#//class PDF extends FPDF
    //#99#//{
    //#99#//  function Header()
    //#99#//  {
    //#99#//    Seleciona fonte Arial bold 15
    //#99#//      $this->SetFont('Arial','B',15);
    //#99#//    Move para a direita
    //#99#//      $this->Cell(80);
    //#99#//    Titulo dentro de uma caixa
    //#99#//      $this->Cell(30,10,'Title',1,0,'C');
    //#99#//    Quebra de linha
    //#99#//      $this->Ln(20);
    //#99#//  }
    //#99#//}

    global $conn;
    global $result;
    global $url;
    global $iEscola;
    //Dados da institui��o

    //   echo ("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
    //   $dados = db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));

    $dados = db_query($conn, "select nomeinst,trim(ender)||','||trim(cast(numero as text)) as ender,munic,uf,telef,email,url,logo from db_config where codigo = " . db_getsession("DB_instit"));
    $url = @pg_result($dados, 0, "url");
    $this->SetXY(1, 1);
    $this->Image('imagens/files/' . pg_result($dados, 0, "logo"), 7, 3, 20);
    if ($_SESSION["DB_modulo"] == 1100747) {
      if (!isset($iEscola)) {
        $iEscola =   db_getsession("DB_coddepto");
      }

      //$this->Cell(100,32,"",1);
      $dados1 = db_query($conn, "select ed18_c_nome,
                                   j14_nome,
                                   ed18_i_numero,
                                   j13_descr,
                                   ed261_c_nome,
                                   ed260_c_sigla,
                                   ed18_c_email,
                                   ed18_c_logo,
                                   ed18_codigoreferencia
                             from escola
                              inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro
                              inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua
                              inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo
                              inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf
                              inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic
                              left join ruascep on ruascep.j29_codigo = ruas.j14_codigo
                              left join logradcep on logradcep.j65_lograd = ruas.j14_codigo
                              left join ceplogradouros on ceplogradouros.cp06_codlogradouro = logradcep.j65_ceplog
                              left join ceplocalidades on ceplocalidades.cp05_codlocalidades = ceplogradouros.cp06_codlocalidade
                             where ed18_i_codigo = " . $iEscola);
      $nome = pg_result($dados, 0, "nomeinst");
      $nomeescola = pg_result($dados1, 0, "ed18_c_nome");
      global $nomeinst;
      $nomeinst = pg_result($dados, 0, "nomeinst");
      if (strlen($nome) > 42 || strlen($nomeescola) > 42)
        $TamFonteNome = 8;
      else
        $TamFonteNome = 9;
      if (trim(pg_result($dados1, 0, "ed18_c_logo")) != "") {
        $this->Image('imagens/' . trim(pg_result($dados1, 0, "ed18_c_logo")), 180, 4, 20); // modifica a posicao da logo
      }
      $ruaescola = trim(pg_result($dados1, 0, "j14_nome"));
      $numescola = trim(pg_result($dados1, 0, "ed18_i_numero"));
      $bairroescola = trim(pg_result($dados1, 0, "j13_descr"));
      $cidadeescola = trim(pg_result($dados1, 0, "ed261_c_nome"));
      $estadoescola = trim(pg_result($dados1, 0, "ed260_c_sigla"));
      $emailescola = trim(pg_result($dados1, 0, "ed18_c_email"));
      $dados2 = db_query($conn, "select ed26_i_numero from telefoneescola where ed26_i_escola = " . db_getsession("DB_coddepto") . " LIMIT 1");
      if (pg_num_rows($dados2) > 0) {
        $telefoneescola = trim(pg_result($dados2, 0, "ed26_i_numero"));
      } else {
        $telefoneescola = "";
      }

      /**
       * Valida se a escola possui um c�digo referente cadastrado e o adiciona antes do nome da escola
       */
      $iCodigoReferencia = trim(pg_result($dados1, 0, "ed18_codigoreferencia"));

      if ($iCodigoReferencia != null) {
        $nomeescola = "{$iCodigoReferencia} - {$nomeescola}";
      }

      $this->SetFont('Arial', 'BI', $TamFonteNome);
      if (@$GLOBALS["atolegalcabecalho"] != "") {
        $this->Text(33, 8, $nome);
        $this->Text(33, 11, $nomeescola);
        //$this->SetFont('Arial', 'I', 8);

        $this->Text(33, 14, @$GLOBALS["atolegalcabecalho"]);

        $this->SetFont('Arial', 'I', 8);
        $this->Text(33, 18, $ruaescola . ", " . $numescola . " - " . $bairroescola);
        $this->Text(33, 21, $cidadeescola . " - " . $estadoescola);
        $this->Text(33, 24, $telefoneescola);
        $comprim = ($this->w - $this->rMargin - $this->lMargin);
        $this->Text(33, 28, ($emailescola != "" ? $emailescola . " + " : "") . $url);
      } else {
        $this->Text(33, 9, $nome);
        $this->Text(33, 14, $nomeescola);
        $this->SetFont('Arial', 'I', 8);
        $this->Text(33, 18, $ruaescola . ", " . $numescola . " - " . $bairroescola);
        $this->Text(33, 22, $cidadeescola . " - " . $estadoescola);
        $this->Text(33, 26, $telefoneescola);
        $comprim = ($this->w - $this->rMargin - $this->lMargin);
        $this->Text(33, 30, ($emailescola != "" ? strtolower($emailescola) : "")); //configurar para retornar email da escola ao inves da instituicao
      }

      $Espaco = $this->w - 80;
      $this->SetFont('Arial', '', 7);
      $margemesquerda = $this->lMargin;
      $this->setleftmargin($Espaco);
      $this->sety(6);
      $this->setfillcolor(235);
      //$this->roundedrect($Espaco - 3, 5, 75, 28, 2, 'DF', '123');
      $this->line(10, 33, $comprim, 33);
      $this->setfillcolor(255);
      //$this->multicell(0, 3, @$GLOBALS["head1"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head2"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head3"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head4"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head5"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head6"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head7"], 0, 1, "J", 0);
      //$this->multicell(0, 3, @$GLOBALS["head8"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head9"], 0, 1, "J", 0);
      $this->setleftmargin($margemesquerda);
      $this->SetY(35);
    } else {

      $dados = db_query($conn, "select nomeinst,
                                   db21_compl,
                                   trim(ender)||',
                                   '||trim(cast(numero as text)) as ender,
                                   trim(ender) as rua,
                                   munic,
                                   numero,
                                   uf,
                                   cgc,
                                   telef,
                                   email,
                                   url,
                                   logo
                            from db_config where codigo = " . db_getsession("DB_instit"));
      $url = @pg_result($dados, 0, "url");
      $this->SetXY(1, 1);
      $this->Image('imagens/files/' . pg_result($dados, 0, "logo"), 7, 3, 20);

      //$this->Cell(100,32,"",1);
      $nome = pg_result($dados, 0, "nomeinst");
      global $nomeinst;
      $nomeinst = pg_result($dados, 0, "nomeinst");

      if (strlen($nome) > 42)
        $TamFonteNome = 8;
      else
        $TamFonteNome = 9;

      $this->SetFont('Arial', 'BI', $TamFonteNome);
      $this->Text(33, 9, $nome);
      $this->SetFont('Arial', 'I', 8);
      $sComplento = substr(trim(pg_result($dados, 0, "db21_compl")), 0, 20);
      if ($sComplento != '' || $sComplento != null) {
        $sComplento = ", " . substr(trim(pg_result($dados, 0, "db21_compl")), 0, 20);
      }
      $this->Text(33, 14, trim(pg_result($dados, 0, "rua")) . ", " . trim(pg_result($dados, 0, "numero")) . $sComplento);
      $this->Text(33, 18, trim(pg_result($dados, 0, "munic")) . " - " . pg_result($dados, 0, "uf"));
      $this->Text(33, 22, trim(pg_result($dados, 0, "telef")) . "   -    CNPJ : " . db_formatar(pg_result($dados, 0, "cgc"), "cnpj"));
      $this->Text(33, 26, trim(pg_result($dados, 0, "email")));
      $comprim = ($this->w - $this->rMargin - $this->lMargin);
      //$this->Text(33, 30, $url);
      $Espaco = $this->w - 80;
      $this->SetFont('Arial', '', 7);
      $margemesquerda = $this->lMargin;
      $this->setleftmargin($Espaco);
      $this->sety(6);
      $this->setfillcolor(235);
      $this->roundedrect($Espaco - 3, 5, 75, 28, 2, 'DF', '123');
      $this->line(10, 33, $comprim, 33);
      $this->setfillcolor(255);
      $this->multicell(0, 3, @$GLOBALS["head1"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head2"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head3"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head4"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head5"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head6"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head7"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head8"], 0, 1, "J", 0);
      $this->multicell(0, 3, @$GLOBALS["head9"], 0, 1, "J", 0);
      $this->setleftmargin($margemesquerda);
      $this->SetY(35);
    }
  }

  //Page footer
  function Footer()
  {
    //#00#//footer
    //#10#//Este m�todo � usado para criar o rodap� da p�gina. Ele � automaticamente chamado por |addPage|
    //#10#//e |close| e n�o deve ser chamado diretamente pela aplica��o. A  implementa��o  em  FPDF  est�
    //#10#//vazia, ent�o voc�  deve  criar  uma  subclasse  e  sobrepor  o  m�todo  se  voc�  quiser   um
    //#10#//processamento espec�fico.
    //#15#//footer()
    //#99#//Exemplo:
    //#99#//class PDF extends FPDF
    //#99#//{
    //#99#//  function Footer()
    //#99#//  {
    //#99#//    Vai para 1.5 cm da borda inferior
    //#99#//      $this->SetY(-15);
    //#99#//    Seleciona Arial it�lico 8
    //#99#//      $this->SetFont('Arial','I',8);
    //#99#//    Imprime o n�mero da p�gina centralizado
    //#99#//      $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    //#99#//  }
    //#99#//}

    global $url;
    if ($this->imprime_rodape == true) {
      //Position at 1.5 cm from bottom
      $this->SetFont('Arial', '', 5);
      $this->text(10, $this->h - 8, 'Base: ' . db_base_ativa());
      $this->SetFont('Arial', 'I', 6);
      $this->SetY(-10);
      $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
      $nome = substr($nome, strrpos($nome, "/") + 1);
      $result_nomeusu = db_query("select nome as nomeusu from db_usuarios where id_usuario =" . db_getsession("DB_id_usuario"));
      if (pg_numrows($result_nomeusu) > 0) {
        $nomeusu = pg_result($result_nomeusu, 0, 0);
      }
      if (isset($nomeusu) && $nomeusu != "") {
        $emissor = $nomeusu;
      } else {
        $emissor = @$GLOBALS["DB_login"];
      }
      $this->Cell(0, 10, $nome . '     Emissor: ' . substr(ucwords(strtolower($emissor)), 0, 30) . '     Exerc�cio: ' . db_getsession("DB_anousu") . '    Data: ' . date("d-m-Y", db_getsession("DB_datausu")) . " - " . date("H:i:s"), "T", 0, 'C');
      $this->Cell(0, 10, 'P�gina ' . $this->PageNo() . ' de {nb}', 0, 1, 'R');
    }
  }

  // mudar o angulo do texto
  function TextWithDirection($x, $y, $txt, $direction = 'R')
  {
    $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
    if ($direction == 'R')
      $s = sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ($this->h - $y) * $this->k, $txt);
    elseif ($direction == 'L')
      $s = sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ($this->h - $y) * $this->k, $txt);
    elseif ($direction == 'U')
      $s = sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $txt);
    elseif ($direction == 'D')
      $s = sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ($this->h - $y) * $this->k, $txt);
    else
      $s = sprintf('BT %.2f %.2f Td (%s) Tj ET', $x * $this->k, ($this->h - $y) * $this->k, $txt);
    $this->_out($s);
  }

  // rotacionar o texto

  function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle = 0)
  {
    $txt = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));

    $font_angle += 90 + $txt_angle;
    $txt_angle *= M_PI / 180;
    $font_angle *= M_PI / 180;

    $txt_dx = cos($txt_angle);
    $txt_dy = sin($txt_angle);
    $font_dx = cos($font_angle);
    $font_dy = sin($font_angle);

    $s = sprintf(
      'BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',
      $txt_dx,
      $txt_dy,
      $font_dx,
      $font_dy,
      $x * $this->k,
      ($this->h - $y) * $this->k,
      $txt
    );
    $this->_out($s);
  }
}

//|XX|//