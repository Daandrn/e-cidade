<?
//MODULO: sicom
//CLASSE DA ENTIDADE \d flpgo102024
class cl_flpgo102024 {
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
    var $si195_sequencial = 0;
    var $si195_tiporegistro = 0;
    var $si195_codvinculopessoa = 0;
    var $si195_regime = null;
    var $si195_indtipopagamento = null;
    var $si195_dsctipopagextra = null;
    var $si195_indsituacaoservidorpensionista = null;
    var $si195_datatransferenciareserva = null;
    var $si195_nrocpfinstituidor = null;
    var $si195_datobitoinstituidor_dia = null;
    var $si195_datobitoinstituidor_mes = null;
    var $si195_datobitoinstituidor_ano = null;
    var $si195_datobitoinstituidor = null;
    var $si195_tipodependencia = 0;
    var $si195_dscdependencia = null;
    var $si195_optouafastpreliminar = null;
    var $si195_datfastpreliminar_dia = null;
    var $si195_datfastpreliminar_mes = null;
    var $si195_datfastpreliminar_ano = null;
    var $si195_datfastpreliminar = null;
    var $si195_indpensionista = null;
    var $si195_datconcessaoaposentadoriapensao_dia = null;
    var $si195_datconcessaoaposentadoriapensao_mes = null;
    var $si195_datconcessaoaposentadoriapensao_ano = null;
    var $si195_datconcessaoaposentadoriapensao = null;
    var $si195_dsccargo = null;
    var $si195_codcargo = 0;
    var $si195_sglcargo = null;
    var $si195_dscapo = null;
    var $si195_natcargo = 0;
    var $si195_indcessao = null;
    var $si195_dsclotacao = null;
    var $si195_indsalaaula = null;
    var $si195_dedicacaoexclusiva = null;
    var $si195_vlrcargahorariasemanal = 0;
    var $si195_datefetexercicio_dia = null;
    var $si195_datefetexercicio_mes = null;
    var $si195_datefetexercicio_ano = null;
    var $si195_datefetexercicio = null;
    var $si195_datcomissionado_dia = null;
    var $si195_datcomissionado_mes = null;
    var $si195_datcomissionado_ano = null;
    var $si195_datcomissionado = null;
    var $si195_datexclusao_dia = null;
    var $si195_datexclusao_mes = null;
    var $si195_datexclusao_ano = null;
    var $si195_datexclusao = null;
    var $si195_datcomissionadoexclusao_dia = null;
    var $si195_datcomissionadoexclusao_mes = null;
    var $si195_datcomissionadoexclusao_ano = null;
    var $si195_datcomissionadoexclusao = null;
    var $si195_vlrremuneracaobruta = 0;
    var $si195_vlrdescontos = 0;
    var $si195_vlrremuneracaoliquida = 0;
    var $si195_natsaldoliquido = null;
    var $si195_mes = 0;
    var $si195_inst = 0;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 si195_sequencial = int8 = si195_sequencial
                 si195_tiporegistro = int8 = Tipo registro
                 si195_codvinculopessoa = int8 = C�digo do v�nculo do agente p�blico
                 si195_regime = varchar(1) = Civil (C) ou Militar (M)
                 si195_indtipopagamento = varchar(1) = Tipo de pagamento
                 si195_dsctipopagextra = varchar(150) = Descri��o do tipo de pagamento extra
                 si195_indsituacaoservidorpensionista = varchar(1) = Indica a situa��o do servidor p�blico
                 si195_dataTransferenciaReserva = date = Data de transferencia reserva
                 si195_nrocpfinstituidor = varchar(11) = N�mero do CPF do instituidor da pens�o
                 si195_datobitoinstituidor = date = Data de �bito do instituidor
                 si195_tipodependencia = int8 = Tipo de depend�ncia do pensionista
                 si195_dscdependencia = varchar(150) = Descricao da dependencia do pensionista
                 si195_indpensionista = int8 = Indica se a pensao e de natureza
                 si195_datconcessaoaposentadoriapensao = date = Data de concess�o da aposentadoria
                 si195_datfastpreliminar = date = Data do afastamento preeliminar a aposentadoria
                 si195_dsccargo = varchar(120) = Nome do cargo
                 si195_codcargo = int8 = C�digo do cargo
                 si195_sglcargo = varchar(3) = Sigla de Cargo
                 si195_dscapo = varchar(3) = Descri��o do tipo de cargo do agente pol�tico
                 si195_natcargo = int8 = Natureza do Cargo
                 si195_indcessao = varchar(3) = Servidor Cedido com �nus
                 si195_dsclotacao = varchar(250) = Descri��o da lota��o
                 si195_indsalaaula = varchar(1) = Atividade em sala de aula para o professor
                 si195_dedicacaoexclusiva = varchar(1) = Dedica��o exclusiva
                 si195_vlrcargahorariasemanal = int8 = Valor da carga horaria
                 si195_datefetexercicio = date = Data de exerc�cio no cargo
                 si195_datcomissionado = date = Data de ingresso no cargo comissionado
                 si195_datexclusao = date = Data de exclus�o
                 si195_datcomissionadoexclusao = date = Data de t�rmino do exerc�cio no cargo comissionado
                 si195_vlrremuneracaobruta = float8 = Valor total dos rendimentos
                 si195_vlrdescontos = float8 = Valor das dedu��es obrigat�rias
                 si195_vlrremuneracaoliquida = float8 = Valor total dos rendimentos liquidos
                 si195_natsaldoliquido = varchar(1) = Natureza do saldo remunerat�rio liquido
                 si195_mes = int8 = si195_mes
                 si195_inst = int8 = si195_inst
                 ";
    //funcao construtor da classe
    function cl_flpgo102024() {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("flpgo102024");
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
            $this->si195_sequencial = ($this->si195_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_sequencial"]:$this->si195_sequencial);
            $this->si195_tiporegistro = ($this->si195_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_tiporegistro"]:$this->si195_tiporegistro);
            $this->si195_codvinculopessoa = ($this->si195_codvinculopessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codvinculopessoa"]:$this->si195_codvinculopessoa);
            $this->si195_regime = ($this->si195_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_regime"]:$this->si195_regime);
            $this->si195_indtipopagamento = ($this->si195_indtipopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_indtipopagamento"]:$this->si195_indtipopagamento);
            $this->si195_dsctipopagextra = ($this->si195_dsctipopagextra == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dsctipopagextra"]:$this->si195_dsctipopagextra);
            $this->si195_indsituacaoservidorpensionista = ($this->si195_indsituacaoservidorpensionista == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_indsituacaoservidorpensionista"]:$this->si195_indsituacaoservidorpensionista);
            $this->si195_datatransferenciareserva = ($this->si195_datatransferenciareserva == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datatransferenciareserva"]:$this->si195_datatransferenciareserva);
            $this->si195_nrocpfinstituidor = ($this->si195_nrocpfinstituidor == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_nrocpfinstituidor"]:$this->si195_nrocpfinstituidor);
            if($this->si195_datobitoinstituidor == ""){
                $this->si195_datobitoinstituidor_dia = ($this->si195_datobitoinstituidor_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datobitoinstituidor_dia"]:$this->si195_datobitoinstituidor_dia);
                $this->si195_datobitoinstituidor_mes = ($this->si195_datobitoinstituidor_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datobitoinstituidor_mes"]:$this->si195_datobitoinstituidor_mes);
                $this->si195_datobitoinstituidor_ano = ($this->si195_datobitoinstituidor_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datobitoinstituidor_ano"]:$this->si195_datobitoinstituidor_ano);
                if($this->si195_datobitoinstituidor_dia != ""){
                    $this->si195_datconcessaoaposentadoriapensao = $this->si195_datobitoinstituidor_ano."-".$this->si195_datobitoinstituidor_mes."-".$this->si195_datobitoinstituidor_dia;
                }
            }
            if($this->si195_datconcessaoaposentadoriapensao == ""){
                $this->si195_datconcessaoaposentadoriapensao_dia = ($this->si195_datconcessaoaposentadoriapensao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao_dia"]:$this->si195_datconcessaoaposentadoriapensao_dia);
                $this->si195_datconcessaoaposentadoriapensao_mes = ($this->si195_datconcessaoaposentadoriapensao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao_mes"]:$this->si195_datconcessaoaposentadoriapensao_mes);
                $this->si195_datconcessaoaposentadoriapensao_ano = ($this->si195_datconcessaoaposentadoriapensao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao_ano"]:$this->si195_datconcessaoaposentadoriapensao_ano);
                if($this->si195_datconcessaoaposentadoriapensao_dia != ""){
                    $this->si195_datconcessaoaposentadoriapensao = $this->si195_datconcessaoaposentadoriapensao_ano."-".$this->si195_datconcessaoaposentadoriapensao_mes."-".$this->si195_datconcessaoaposentadoriapensao_dia;
                }
            }
            if($this->si195_datfastpreliminar == ""){
                $this->si195_datfastpreliminar_dia = ($this->si195_datfastpreliminar_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datfastpreliminar_dia"]:$this->si195_datfastpreliminar_dia);
                $this->si195_datfastpreliminar_mes = ($this->si195_datfastpreliminar_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datfastpreliminar_mes"]:$this->si195_datfastpreliminar_mes);
                $this->si195_datfastpreliminar_ano = ($this->si195_datfastpreliminar_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datfastpreliminar_ano"]:$this->si195_datfastpreliminar_ano);
                if($this->si195_datfastpreliminar_dia != ""){
                    $this->si195_datfastpreliminar = $this->si195_datfastpreliminar_ano."-".$this->si195_datfastpreliminar_mes."-".$this->si195_datfastpreliminar_dia;
                }
            }
            $this->si195_tipodependencia = ($this->si195_tipodependencia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_tipodependencia"]:$this->si195_tipodependencia);
            $this->si195_dscdependencia = ($this->si195_dscdependencia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dscdependencia"]:$this->si195_dscdependencia);
            $this->si195_indpensionista = ($this->si195_indpensionista == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_indpensionista"]:$this->si195_indpensionista);
            $this->si195_optouafastpreliminar = ($this->si195_optouafastpreliminar == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_optouafastpreliminar"]:$this->si195_optouafastpreliminar);
            $this->si195_dsccargo = ($this->si195_dsccargo == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dsccargo"]:$this->si195_dsccargo);
            $this->si195_codcargo = ($this->si195_codcargo == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codcargo"]:$this->si195_codcargo);
            $this->si195_sglcargo = ($this->si195_sglcargo == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_sglcargo"]:$this->si195_sglcargo);
            $this->si195_dscapo = ($this->si195_dscapo == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dscapo"]:$this->si195_dscapo);
            $this->si195_natcargo = ($this->si195_natcargo == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_natcargo"]:$this->si195_natcargo);
            $this->si195_indcessao = ($this->si195_indcessao == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_indcessao"]:$this->si195_indcessao);
            $this->si195_dsclotacao = ($this->si195_dsclotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dsclotacao"]:$this->si195_dsclotacao);
            $this->si195_indsalaaula = ($this->si195_indsalaaula == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_indsalaaula"]:$this->si195_indsalaaula);
            $this->si195_dedicacaoexclusiva = ($this->si195_dedicacaoexclusiva == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dedicacaoexclusiva"]:$this->si195_dedicacaoexclusiva);
            $this->si195_vlrcargahorariasemanal = ($this->si195_vlrcargahorariasemanal == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_vlrcargahorariasemanal"]:$this->si195_vlrcargahorariasemanal);
            if($this->si195_datefetexercicio == ""){
                $this->si195_datefetexercicio_dia = ($this->si195_datefetexercicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio_dia"]:$this->si195_datefetexercicio_dia);
                $this->si195_datefetexercicio_mes = ($this->si195_datefetexercicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio_mes"]:$this->si195_datefetexercicio_mes);
                $this->si195_datefetexercicio_ano = ($this->si195_datefetexercicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio_ano"]:$this->si195_datefetexercicio_ano);
                if($this->si195_datefetexercicio_dia != ""){
                    $this->si195_datefetexercicio = $this->si195_datefetexercicio_ano."-".$this->si195_datefetexercicio_mes."-".$this->si195_datefetexercicio_dia;
                }
            }
            if($this->si195_datcomissionado == ""){
                $this->si195_datcomissionado_dia = ($this->si195_datcomissionado_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datcomissionado_dia"]:$this->si195_datcomissionado_dia);
                $this->si195_datcomissionado_mes = ($this->si195_datcomissionado_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datcomissionado_mes"]:$this->si195_datcomissionado_mes);
                $this->si195_datcomissionado_ano = ($this->si195_datcomissionado_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datcomissionado_ano"]:$this->si195_datcomissionado_ano);
                if($this->si195_datcomissionado_dia != ""){
                    $this->si195_datcomissionado = $this->si195_datcomissionado_ano."-".$this->si195_datcomissionado_mes."-".$this->si195_datcomissionado_dia;
                }
            }
            if($this->si195_datexclusao == ""){
                $this->si195_datexclusao_dia = ($this->si195_datexclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datexclusao_dia"]:$this->si195_datexclusao_dia);
                $this->si195_datexclusao_mes = ($this->si195_datexclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datexclusao_mes"]:$this->si195_datexclusao_mes);
                $this->si195_datexclusao_ano = ($this->si195_datexclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datexclusao_ano"]:$this->si195_datexclusao_ano);
                if($this->si195_datexclusao_dia != ""){
                    $this->si195_datexclusao = $this->si195_datexclusao_ano."-".$this->si195_datexclusao_mes."-".$this->si195_datexclusao_dia;
                }
            }
            if($this->si195_datcomissionadoexclusao == ""){
                $this->si195_datcomissionadoexclusao_dia = ($this->si195_datcomissionadoexclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datcomissionadoexclusao_dia"]:$this->si195_datcomissionadoexclusao_dia);
                $this->si195_datcomissionadoexclusao_mes = ($this->si195_datcomissionadoexclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datcomissionadoexclusao_mes"]:$this->si195_datcomissionadoexclusao_mes);
                $this->si195_datcomissionadoexclusao_ano = ($this->si195_datcomissionadoexclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_datcomissionadoexclusao_ano"]:$this->si195_datcomissionadoexclusao_ano);
                if($this->si195_datcomissionadoexclusao_dia != ""){
                    $this->si195_datcomissionadoexclusao = $this->si195_datcomissionadoexclusao_ano."-".$this->si195_datcomissionadoexclusao_mes."-".$this->si195_datcomissionadoexclusao_dia;
                }
            }
            $this->si195_vlrremuneracaobruta = ($this->si195_vlrremuneracaobruta == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_vlrremuneracaobruta"]:$this->si195_vlrremuneracaobruta);
            $this->si195_natsaldoliquido = ($this->si195_natsaldoliquido == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_natsaldoliquido"]:$this->si195_natsaldoliquido);
            $this->si195_vlrremuneracaoliquida = ($this->si195_vlrremuneracaoliquida == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_vlrremuneracaoliquida"]:$this->si195_vlrremuneracaoliquida);
            $this->si195_vlrdescontos = ($this->si195_vlrdescontos == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_vlrdescontos"]:$this->si195_vlrdescontos);
            $this->si195_mes = ($this->si195_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_mes"]:$this->si195_mes);
            $this->si195_inst = ($this->si195_inst == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_inst"]:$this->si195_inst);
        }else{
            $this->si195_sequencial = ($this->si195_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_sequencial"]:$this->si195_sequencial);
        }
    }
    // funcao para inclusao
    function incluir ($si195_sequencial){
        $this->atualizacampos();

        if($this->si195_tiporegistro == null ){
            $this->erro_sql = " Campo Tipo registro n�o informado.";
            $this->erro_campo = "si195_tiporegistro";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_codvinculopessoa == null ){
            $this->erro_sql = " C�digo de matr�cula n�o informado.";
            $this->erro_campo = "si195_codvinculopessoa";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_regime == null ){
            $this->erro_sql = " Campo Civil (C) ou Militar (M) n�o informado.";
            $this->erro_campo = "si195_regime";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_indtipopagamento == null ){
            $this->erro_sql = " Campo Tipo de pagamento n�o informado.";
            $this->erro_campo = "si195_indtipopagamento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_dsctipopagextra == null ){
            $this->si195_dsctipopagextra = null;
        }
        if($this->si195_indsituacaoservidorpensionista == null ){
            $this->erro_sql = " Campo Indica a situa��o do servidor p�blico n�o informado.";
            $this->erro_campo = "si195_indsituacaoservidorpensionista";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }

        if($this->si195_codcargo == null ){
            $this->si195_codcargo=0;
        }

        if($this->si195_vlrcargahorariasemanal == null ){
            $this->si195_vlrcargahorariasemanal = "0";
        }

        if($this->si195_vlrremuneracaobruta == null ){
            $this->erro_sql = " Campo Valor total dos rendimentos n�o informado.";
            $this->erro_campo = "si195_vlrremuneracaobruta";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_vlrremuneracaoliquida == null ){
            $this->erro_sql = " Campo Valor total dos rendimentos liquidos n�o informado.";
            $this->erro_campo = "si195_vlrremuneracaoliquida";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_vlrdescontos == null ){
            $this->erro_sql = " Campo Valor das dedu��es obrigat�rias n�o informado.";
            $this->erro_campo = "si195_vlrdescontos";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_mes == null ){
            $this->erro_sql = " Campo si195_mes n�o informado.";
            $this->erro_campo = "si195_mes";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->si195_inst == null ){
            $this->erro_sql = " Campo si195_inst n�o informado.";
            $this->erro_campo = "si195_inst";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        if($si195_sequencial == "" || $si195_sequencial == null ){
            $result = db_query("select nextval('flpgo102024_si195_sequencial_seq')");
            if($result==false){
                $this->erro_banco = str_replace("","",@pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: flpgo102024_si195_sequencial_seq do campo: si195_sequencial";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
            $this->si195_sequencial = pg_result($result,0,0);
        }else{
            $result = db_query("select last_value from flpgo102024_si195_sequencial_seq");
            if(($result != false) && (pg_result($result,0,0) < $si195_sequencial)){
                $this->erro_sql = " Campo si195_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }else{
                $this->si195_sequencial = $si195_sequencial;
            }
        }
        if(($this->si195_sequencial == null) || ($this->si195_sequencial == "") ){
            $this->erro_sql = " Campo si195_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }

        $sql = "insert into flpgo102024(
                                       si195_sequencial
                                      ,si195_codvinculopessoa
                                      ,si195_tiporegistro
                                      ,si195_regime
                                      ,si195_indtipopagamento
                                      ,si195_dsctipopagextra
                                      ,si195_indsituacaoservidorpensionista
                                      ,si195_datatransferenciareserva
                                      ,si195_nrocpfinstituidor
                                      ,si195_datobitoinstituidor
                                      ,si195_tipodependencia
                                      ,si195_dscdependencia
                                      ,si195_optouafastpreliminar
                                      ,si195_datfastpreliminar
                                      ,si195_indpensionista
                                      ,si195_datconcessaoaposentadoriapensao
                                      ,si195_dsccargo
                                      ,si195_codcargo
                                      ,si195_sglcargo
                                      ,si195_dscapo
                                      ,si195_natcargo
                                      ,si195_indcessao
                                      ,si195_dsclotacao
                                      ,si195_indsalaaula
                                      ,si195_dedicacaoexclusiva
                                      ,si195_vlrcargahorariasemanal
                                      ,si195_datefetexercicio
                                      ,si195_datcomissionado
                                      ,si195_datexclusao
                                      ,si195_datcomissionadoexclusao
                                      ,si195_vlrremuneracaobruta
                                      ,si195_vlrdescontos
                                      ,si195_vlrremuneracaoliquida
                                      ,si195_natsaldoliquido
                                      ,si195_mes
                                      ,si195_inst
                       )
                values (
                                $this->si195_sequencial
                               ,$this->si195_codvinculopessoa
                               ,$this->si195_tiporegistro
                               ,'$this->si195_regime'
                               ,'$this->si195_indtipopagamento'
                               ,'$this->si195_dsctipopagextra'
                               ,'$this->si195_indsituacaoservidorpensionista'
                               ,".($this->si195_datatransferenciareserva == "null" || $this->si195_datatransferenciareserva == ""?"null":"'".$this->si195_datatransferenciareserva."'")."
                               ,'$this->si195_nrocpfinstituidor'
                               ,".($this->si195_datobitoinstituidor== "null" || $this->si195_datobitoinstituidor == ""?"null":"'".$this->si195_datobitoinstituidor."'")."
                               ,".($this->si195_tipodependencia== "null" || $this->si195_tipodependencia == ""?"0":"'".$this->si195_tipodependencia."'")."
                               ,'$this->si195_dscdependencia'
                               ,$this->si195_optouafastpreliminar
                               ,".($this->si195_datfastpreliminar == "null" || $this->si195_datfastpreliminar == ""?"null":"'".$this->si195_datfastpreliminar."'")."
                               ,".($this->si195_indpensionista == null ? 'NULL' : $this->si195_indpensionista)."
                               ,".($this->si195_datconcessaoaposentadoriapensao == "null" || $this->si195_datconcessaoaposentadoriapensao == ""?"null":"'".$this->si195_datconcessaoaposentadoriapensao."'")."
                               ,'$this->si195_dsccargo'
                               ,$this->si195_codcargo
                               ,'$this->si195_sglcargo'
                               ,'$this->si195_dscapo'
                               ,".($this->si195_natcargo == "null" || $this->si195_natcargo == ""?"null":"'".$this->si195_natcargo."'")."
                               ,'$this->si195_indcessao'
                               ,'$this->si195_dsclotacao'
                               ,'$this->si195_indsalaaula'
                               ,'$this->si195_dedicacaoexclusiva'
                               ,$this->si195_vlrcargahorariasemanal
                               ,".($this->si195_datefetexercicio == "null" || $this->si195_datefetexercicio == ""?"null":"'".$this->si195_datefetexercicio."'")."
                               ,".($this->si195_datcomissionado == "null" || $this->si195_datcomissionado == ""?"null":"'".$this->si195_datcomissionado."'")."
                               ,".($this->si195_datexclusao == "null" || $this->si195_datexclusao == ""?"null":"'".$this->si195_datexclusao."'")."
                               ,".($this->si195_datcomissionadoexclusao == "null" || $this->si195_datcomissionadoexclusao == ""?"null":"'".$this->si195_datcomissionadoexclusao."'")."
                               ,'$this->si195_vlrremuneracaobruta'
                               ,'$this->si195_vlrdescontos'
                               ,'$this->si195_vlrremuneracaoliquida'
                               ,'$this->si195_natsaldoliquido'
                               ,$this->si195_mes
                               ,$this->si195_inst
                      )";
        $result = db_query($sql);
            if($result==false){
            $this->erro_banco = str_replace("","",@pg_last_error());
            if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
                $this->erro_sql   = "flpgo102024 ($this->si195_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_banco = "flpgo102024 j� Cadastrado";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            }else{
                $this->erro_sql   = "flpgo102024 ($this->si195_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : ".$this->si195_sequencial;
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->si195_sequencial  ));
            if(($resaco!=false)||($this->numrows!=0)){

                /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac,0,0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,1009276,'$this->si195_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010195,1009276,'','".AddSlashes(pg_result($resaco,0,'si195_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009277,'','".AddSlashes(pg_result($resaco,0,'si195_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009278,'','".AddSlashes(pg_result($resaco,0,'si195_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009279,'','".AddSlashes(pg_result($resaco,0,'si195_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009280,'','".AddSlashes(pg_result($resaco,0,'si195_indtipopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009281,'','".AddSlashes(pg_result($resaco,0,'si195_indsituacaoservidorpensionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009282,'','".AddSlashes(pg_result($resaco,0,'si195_datconcessaoaposentadoriapensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009283,'','".AddSlashes(pg_result($resaco,0,'si195_dsccargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009284,'','".AddSlashes(pg_result($resaco,0,'si195_sglcargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009285,'','".AddSlashes(pg_result($resaco,0,'si195_reqcargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009286,'','".AddSlashes(pg_result($resaco,0,'si195_indcessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009287,'','".AddSlashes(pg_result($resaco,0,'si195_dsclotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009288,'','".AddSlashes(pg_result($resaco,0,'si195_vlrcargahorariasemanal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009289,'','".AddSlashes(pg_result($resaco,0,'si195_datefetexercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009290,'','".AddSlashes(pg_result($resaco,0,'si195_datexclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009291,'','".AddSlashes(pg_result($resaco,0,'si195_natsaldobruto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009292,'','".AddSlashes(pg_result($resaco,0,'si195_vlrremuneracaobruta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009293,'','".AddSlashes(pg_result($resaco,0,'si195_natsaldoliquido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009294,'','".AddSlashes(pg_result($resaco,0,'si195_vlrremuneracaoliquida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009295,'','".AddSlashes(pg_result($resaco,0,'si195_vlrdescontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009297,'','".AddSlashes(pg_result($resaco,0,'si195_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010195,1009298,'','".AddSlashes(pg_result($resaco,0,'si195_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
            }
        }
        return true;
    }
    // funcao para alteracao
    function alterar ($si195_sequencial=null) {
        $this->atualizacampos();
        $sql = " update flpgo102024 set ";
        $virgula = "";
        if(trim($this->si195_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_sequencial"])){
            $sql  .= $virgula." si195_sequencial = $this->si195_sequencial ";
            $virgula = ",";
            if(trim($this->si195_sequencial) == null ){
                $this->erro_sql = " Campo si195_sequencial n�o informado.";
                $this->erro_campo = "si195_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_tiporegistro"])){
            $sql  .= $virgula." si195_tiporegistro = $this->si195_tiporegistro ";
            $virgula = ",";
            if(trim($this->si195_tiporegistro) == null ){
                $this->erro_sql = " Campo Tipo registro n�o informado.";
                $this->erro_campo = "si195_tiporegistro";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_codvinculopessoa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_codvinculopessoa"])){
            $sql  .= $virgula." si195_codvinculopessoa = $this->si195_codvinculopessoa ";
            $virgula = ",";
            if(trim($this->si195_codvinculopessoa) == null ){
                $this->erro_sql = " C�digo de matr�cula n�o informado.";
                $this->erro_campo = "si195_codvinculopessoa";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_regime"])){
            $sql  .= $virgula." si195_regime = '$this->si195_regime' ";
            $virgula = ",";
            if(trim($this->si195_regime) == null ){
                $this->erro_sql = " Campo Civil (C) ou Militar (M) n�o informado.";
                $this->erro_campo = "si195_regime";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_indtipopagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_indtipopagamento"])){
            $sql  .= $virgula." si195_indtipopagamento = '$this->si195_indtipopagamento' ";
            $virgula = ",";
            if(trim($this->si195_indtipopagamento) == null ){
                $this->erro_sql = " Campo Tipo de pagamento n�o informado.";
                $this->erro_campo = "si195_indtipopagamento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_dsctipopagextra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_dsctipopagextra"])){
            $sql  .= $virgula." si195_dsctipopagextra = '$this->si195_dsctipopagextra' ";
            $virgula = ",";
            if(trim($this->si195_dsctipopagextra) == null ){
                $this->erro_sql = " Campo Descri��o do tipo de pagamento extra n�o informado.";
                $this->erro_campo = "si195_dsctipopagextra";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_indsituacaoservidorpensionista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_indsituacaoservidorpensionista"])){
            $sql  .= $virgula." si195_indsituacaoservidorpensionista = '$this->si195_indsituacaoservidorpensionista' ";
            $virgula = ",";
            if(trim($this->si195_indsituacaoservidorpensionista) == null ){
                $this->erro_sql = " Campo Indica a situa��o do servidor p�blico n�o informado.";
                $this->erro_campo = "si195_indsituacaoservidorpensionista";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_datatransferenciareserva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_datatransferenciareserva"])) {
            $sql .= $virgula . " si195_datatransferenciareserva = '$this->si195_datatransferenciareserva' ";
            $virgula = ",";
        }
        if(trim($this->si195_dscdependencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_dscdependencia"])) {
            $sql .= $virgula . " si195_dscdependencia = '$this->si195_dscdependencia' ";
            $virgula = ",";
        }
        if(trim($this->si195_optouafastpreliminar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_optouafastpreliminar"])) {
            $sql .= $virgula . " si195_optouafastpreliminar = '$this->si195_optouafastpreliminar' ";
            $virgula = ",";
        }
        if(trim($this->si195_indpensionista)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_indpensionista"])) {
            $sql .= $virgula . " si195_indpensionista = '$this->si195_indpensionista' ";
            $virgula = ",";
        }
        if(trim($this->si195_datconcessaoaposentadoriapensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao_dia"] !="") ){
            $sql  .= $virgula." si195_datconcessaoaposentadoriapensao = '$this->si195_datconcessaoaposentadoriapensao' ";
            $virgula = ",";
        }     else{
            if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao_dia"])){
                $sql  .= $virgula." si195_datconcessaoaposentadoriapensao = null ";
                $virgula = ",";
            }
        }
        if(trim($this->si195_datfastpreliminar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_datfastpreliminar_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si195_datfastpreliminar_dia"] !="") ){
            $sql  .= $virgula." si195_datfastpreliminar = '$this->si195_datfastpreliminar' ";
            $virgula = ",";
        }     else{
            if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datfastpreliminar_dia"])){
                $sql  .= $virgula." si195_datfastpreliminar = null ";
                $virgula = ",";
            }
        }
        if(trim($this->si195_dsccargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_dsccargo"])){
            $sql  .= $virgula." si195_dsccargo = '$this->si195_dsccargo' ";
            $virgula = ",";
            if(trim($this->si195_dsccargo) == null ){
                $this->erro_sql = " Campo Nome do cargo n�o informado.";
                $this->erro_campo = "si195_dsccargo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if(trim($this->si195_indcessao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_indcessao"])){
            $sql  .= $virgula." si195_indcessao = '$this->si195_indcessao' ";
            $virgula = ",";
        }
        if(trim($this->si195_dsclotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_dsclotacao"])){
            $sql  .= $virgula." si195_dsclotacao = '$this->si195_dsclotacao' ";
            $virgula = ",";
        }
        if(trim($this->si195_indsalaaula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_indsalaaula"])){
            $sql  .= $virgula." si195_indsalaaula = '$this->si195_indsalaaula' ";
            $virgula = ",";
        }
        if(trim($this->si195_dedicacaoexclusiva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_dedicacaoexclusiva"])){
            $sql  .= $virgula." si195_dedicacaoexclusiva = '$this->si195_dedicacaoexclusiva' ";
            $virgula = ",";
        }
        if(trim($this->si195_vlrcargahorariasemanal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrcargahorariasemanal"])){
            if(trim($this->si195_vlrcargahorariasemanal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrcargahorariasemanal"])){
                $this->si195_vlrcargahorariasemanal = "0" ;
            }
            $sql  .= $virgula." si195_vlrcargahorariasemanal = $this->si195_vlrcargahorariasemanal ";
            $virgula = ",";
        }
        if(trim($this->si195_datefetexercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio_dia"] !="") ){
            $sql  .= $virgula." si195_datefetexercicio = '$this->si195_datefetexercicio' ";
            $virgula = ",";
            if(trim($this->si195_datefetexercicio) == null ){
                $this->erro_sql = " Campo Data de exerc�cio no cargo n�o informado.";
                $this->erro_campo = "si195_datefetexercicio_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }     else{
            if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio_dia"])){
                $sql  .= $virgula." si195_datefetexercicio = null ";
                $virgula = ",";
                if(trim($this->si195_datefetexercicio) == null ){
                    $this->erro_sql = " Campo Data de exerc�cio no cargo n�o informado.";
                    $this->erro_campo = "si195_datefetexercicio_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if(trim($this->si195_datexclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_datexclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si195_datexclusao_dia"] !="") ){
            $sql  .= $virgula." si195_datexclusao = '$this->si195_datexclusao' ";
            $virgula = ",";
        }     else{
            if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datexclusao_dia"])){
                $sql  .= $virgula." si195_datexclusao = null ";
                $virgula = ",";
            }
        }
        if(trim($this->si195_vlrremuneracaobruta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrremuneracaobruta"])){
            $sql  .= $virgula." si195_vlrremuneracaobruta = $this->si195_vlrremuneracaobruta ";
            $virgula = ",";
            if(trim($this->si195_vlrremuneracaobruta) == null ){
                $this->erro_sql = " Campo Valor total dos rendimentos n�o informado.";
                $this->erro_campo = "si195_vlrremuneracaobruta";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_natsaldoliquido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_natsaldoliquido"])){
            $sql  .= $virgula." si195_natsaldoliquido = '$this->si195_natsaldoliquido' ";
            $virgula = ",";
            if(trim($this->si195_natsaldoliquido) == null ){
                $this->erro_sql = " Campo Natureza do saldo remunerat�rio liquido n�o informado.";
                $this->erro_campo = "si195_natsaldoliquido";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_vlrremuneracaoliquida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrremuneracaoliquida"])){
            $sql  .= $virgula." si195_vlrremuneracaoliquida = $this->si195_vlrremuneracaoliquida ";
            $virgula = ",";
            if(trim($this->si195_vlrremuneracaoliquida) == null ){
                $this->erro_sql = " Campo Valor total dos rendimentos liquidos n�o informado.";
                $this->erro_campo = "si195_vlrremuneracaoliquida";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_vlrdescontos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrdescontos"])){
            $sql  .= $virgula." si195_vlrdescontos = $this->si195_vlrdescontos ";
            $virgula = ",";
            if(trim($this->si195_vlrdescontos) == null ){
                $this->erro_sql = " Campo Valor das dedu��es obrigat�rias n�o informado.";
                $this->erro_campo = "si195_vlrdescontos";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_mes"])){
            $sql  .= $virgula." si195_mes = $this->si195_mes ";
            $virgula = ",";
            if(trim($this->si195_mes) == null ){
                $this->erro_sql = " Campo si195_mes n�o informado.";
                $this->erro_campo = "si195_mes";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if(trim($this->si195_inst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_inst"])){
            $sql  .= $virgula." si195_inst = $this->si195_inst ";
            $virgula = ",";
            if(trim($this->si195_inst) == null ){
                $this->erro_sql = " Campo si195_inst n�o informado.";
                $this->erro_campo = "si195_inst";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if($si195_sequencial!=null){
            $sql .= " si195_sequencial = $this->si195_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
//        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
//                && ($lSessaoDesativarAccount === false))) {
//
//            $resaco = $this->sql_record($this->sql_query_file($this->si195_sequencial));
//            if($this->numrows>0){
//
//                for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
//
//                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//                    $acount = pg_result($resac,0,0);
//                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
//                    $resac = db_query("insert into db_acountkey values($acount,1009276,'$this->si195_sequencial','A')");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_sequencial"]) || $this->si195_sequencial != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009276,'".AddSlashes(pg_result($resaco,$conresaco,'si195_sequencial'))."','$this->si195_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_tiporegistro"]) || $this->si195_tiporegistro != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009277,'".AddSlashes(pg_result($resaco,$conresaco,'si195_tiporegistro'))."','$this->si195_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_regime"]) || $this->si195_regime != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009279,'".AddSlashes(pg_result($resaco,$conresaco,'si195_regime'))."','$this->si195_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_indtipopagamento"]) || $this->si195_indtipopagamento != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009280,'".AddSlashes(pg_result($resaco,$conresaco,'si195_indtipopagamento'))."','$this->si195_indtipopagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_indsituacaoservidorpensionista"]) || $this->si195_indsituacaoservidorpensionista != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009281,'".AddSlashes(pg_result($resaco,$conresaco,'si195_indsituacaoservidorpensionista'))."','$this->si195_indsituacaoservidorpensionista',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datconcessaoaposentadoriapensao"]) || $this->si195_datconcessaoaposentadoriapensao != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009282,'".AddSlashes(pg_result($resaco,$conresaco,'si195_datconcessaoaposentadoriapensao'))."','$this->si195_datconcessaoaposentadoriapensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_dsccargo"]) || $this->si195_dsccargo != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009283,'".AddSlashes(pg_result($resaco,$conresaco,'si195_dsccargo'))."','$this->si195_dsccargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_sglcargo"]) || $this->si195_sglcargo != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009284,'".AddSlashes(pg_result($resaco,$conresaco,'si195_sglcargo'))."','$this->si195_sglcargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_reqcargo"]) || $this->si195_reqcargo != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009285,'".AddSlashes(pg_result($resaco,$conresaco,'si195_reqcargo'))."','$this->si195_reqcargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_indcessao"]) || $this->si195_indcessao != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009286,'".AddSlashes(pg_result($resaco,$conresaco,'si195_indcessao'))."','$this->si195_indcessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_dsclotacao"]) || $this->si195_dsclotacao != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009287,'".AddSlashes(pg_result($resaco,$conresaco,'si195_dsclotacao'))."','$this->si195_dsclotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrcargahorariasemanal"]) || $this->si195_vlrcargahorariasemanal != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009288,'".AddSlashes(pg_result($resaco,$conresaco,'si195_vlrcargahorariasemanal'))."','$this->si195_vlrcargahorariasemanal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datefetexercicio"]) || $this->si195_datefetexercicio != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009289,'".AddSlashes(pg_result($resaco,$conresaco,'si195_datefetexercicio'))."','$this->si195_datefetexercicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_datexclusao"]) || $this->si195_datexclusao != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009290,'".AddSlashes(pg_result($resaco,$conresaco,'si195_datexclusao'))."','$this->si195_datexclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrremuneracaobruta"]) || $this->si195_vlrremuneracaobruta != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009292,'".AddSlashes(pg_result($resaco,$conresaco,'si195_vlrremuneracaobruta'))."','$this->si195_vlrremuneracaobruta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_natsaldoliquido"]) || $this->si195_natsaldoliquido != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009293,'".AddSlashes(pg_result($resaco,$conresaco,'si195_natsaldoliquido'))."','$this->si195_natsaldoliquido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrremuneracaoliquida"]) || $this->si195_vlrremuneracaoliquida != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009294,'".AddSlashes(pg_result($resaco,$conresaco,'si195_vlrremuneracaoliquida'))."','$this->si195_vlrremuneracaoliquida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_vlrdescontos"]) || $this->si195_vlrdescontos != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009295,'".AddSlashes(pg_result($resaco,$conresaco,'si195_vlrdescontos'))."','$this->si195_vlrdescontos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_mes"]) || $this->si195_mes != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009297,'".AddSlashes(pg_result($resaco,$conresaco,'si195_mes'))."','$this->si195_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                    if(isset($GLOBALS["HTTP_POST_VARS"]["si195_inst"]) || $this->si195_inst != "")
//                        $resac = db_query("insert into db_acount values($acount,1010195,1009298,'".AddSlashes(pg_result($resaco,$conresaco,'si195_inst'))."','$this->si195_inst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//                }
//            }
//        }
        $result = db_query($sql);
        if($result==false){
            $this->erro_banco = str_replace("","",@pg_last_error());
            $this->erro_sql   = "flpgo102024 nao Alterado. Alteracao Abortada.\n";
            $this->erro_sql .= "Valores : ".$this->si195_sequencial;
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "flpgo102024 nao foi Alterado. Alteracao Executada.\n";
                $this->erro_sql .= "Valores : ".$this->si195_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\n";
                $this->erro_sql .= "Valores : ".$this->si195_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir ($si195_sequencial=null,$dbwhere=null) {

        /*$lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
          && ($lSessaoDesativarAccount === false))) {

          if ($dbwhere==null || $dbwhere=="") {

            $resaco = $this->sql_record($this->sql_query_file($si195_sequencial));
          } else {
            $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
          }
          if (($resaco != false) || ($this->numrows!=0)) {

            for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

              $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
              $acount = pg_result($resac,0,0);
              $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
              $resac  = db_query("insert into db_acountkey values($acount,1009276,'$si195_sequencial','E')");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009276,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009277,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009278,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_nrodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009279,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009280,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_indtipopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009281,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_indsituacaoservidorpensionista'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009282,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_datconcessaoaposentadoriapensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009283,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_dsccargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009284,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_sglcargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009285,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_reqcargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009286,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_indcessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009287,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_dsclotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009288,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_vlrcargahorariasemanal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009289,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_datefetexercicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009290,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_datexclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009291,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_natsaldobruto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009292,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_vlrremuneracaobruta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009293,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_natsaldoliquido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009294,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_vlrremuneracaoliquida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009295,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_vlrdescontos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009297,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              $resac  = db_query("insert into db_acount values($acount,1010195,1009298,'','".AddSlashes(pg_result($resaco,$iresaco,'si195_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
          }
        }*/
        $sql = " delete from flpgo102024
                    where ";
        $sql2 = "";
        if($dbwhere==null || $dbwhere ==""){
            if($si195_sequencial != ""){
                if($sql2!=""){
                    $sql2 .= " and ";
                }
                $sql2 .= " si195_sequencial = $si195_sequencial ";
            }
        }else{
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if($result==false){
            $this->erro_banco = str_replace("","",@pg_last_error());
            $this->erro_sql   = "flpgo102024 nao Exclu�do. Exclus�o Abortada.\n";
            $this->erro_sql .= "Valores : ".$si195_sequencial;
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        }else{
            if(pg_affected_rows($result)==0){
                $this->erro_banco = "";
                $this->erro_sql = "flpgo102024 nao Encontrado. Exclus�o n�o Efetuada.\n";
                $this->erro_sql .= "Valores : ".$si195_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            }else{
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
                $this->erro_sql .= "Valores : ".$si195_sequencial;
                $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao do recordset
    function sql_record($sql) {
        $result = db_query($sql);
        if($result==false){
            $this->numrows    = 0;
            $this->erro_banco = str_replace("","",@pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if($this->numrows==0){
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:flpgo102024";
            $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    function sql_query ( $si195_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from flpgo102024 ";
        $sql2 = "";
        if($dbwhere==""){
            if($si195_sequencial!=null ){
                $sql2 .= " where flpgo102024.si195_sequencial = $si195_sequencial ";
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
    function sql_query_file ( $si195_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
        $sql .= " from flpgo102024 ";
        $sql2 = "";
        if($dbwhere==""){
            if($si195_sequencial!=null ){
                $sql2 .= " where flpgo102024.si195_sequencial = $si195_sequencial ";
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
