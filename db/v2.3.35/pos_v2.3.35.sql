insert into db_versao (db30_codver, db30_codversao, db30_codrelease, db30_data, db30_obs)  values (351, 3, 35, '2015-02-12', 'Tarefas: 97120, 97451, 97456, 97463, 97465, 97467, 97469, 97470, 97471, 97472, 97474, 97477, 97480, 97481, 97482, 97485, 97486, 97488, 97490, 97491, 97492, 97493, 97494, 97496, 97498, 97499, 97500, 97501, 97502, 97503, 97504, 97505, 97506, 97507, 97508, 97509, 97510, 97512, 97513, 97514, 97515, 97516, 97517, 97518, 97519, 97520, 97521, 97525, 97526, 97527, 97528, 97529, 97530, 97531, 97532, 97533, 97536, 97537, 97538, 97539, 97540, 97541, 97542, 97544, 97545, 97546, 97547, 97548, 97549, 97550, 97552, 97553, 97554, 97555, 97558, 97560, 97561, 97562, 97563, 97564, 97565, 97566, 97567, 97568, 97570, 97571, 97573, 97574, 97575, 97576, 97577, 97578, 97579, 97580, 97582, 97583, 97584, 97585, 97589, 97590, 97591, 97592');CREATE OR REPLACE FUNCTION fc_edurfatual(iMatricula numeric)
RETURNS varchar AS $$
DECLARE
  iAluno      integer;
  iTurma      integer;
  iCalendario integer;
  iSerie      integer;
  cSituacao   varchar;
  cConcluida  varchar;
  iLinhas     integer;
  iLinhas2    integer;
  iLinhas3    integer;
  iDiario     integer;
  iDiario2    integer;
  cRFatual    varchar;
BEGIN
  SELECT ed60_i_turma, ed60_i_aluno, trim(ed60_c_situacao), ed60_c_concluida, ed57_i_calendario, ed221_i_serie
    INTO iTurma, iAluno, cSituacao, cConcluida, iCalendario, iSerie
    FROM matricula
         inner join turma          on turma.ed57_i_codigo = matricula.ed60_i_turma
         inner join matriculaserie on ed221_i_matricula   = ed60_i_codigo
   WHERE ed60_i_codigo  = iMatricula
     AND ed221_c_origem = 'S'
   LIMIT 1;

  GET DIAGNOSTICS iLinhas = ROW_COUNT;

  IF iLinhas > 0 THEN
    IF cSituacao = 'CLASSIFICADO' OR cSituacao = 'AVAN�ADO' THEN
      cRFatual := 'A';
    ELSIF cSituacao = 'MATRICULADO' THEN
      SELECT ed95_i_codigo INTO iDiario
        FROM diario
             inner join aluno       on ed47_i_codigo = ed95_i_aluno
             inner join diariofinal on ed74_i_diario = ed95_i_codigo
             inner join regencia    on ed59_i_codigo = ed95_i_regencia
             inner join turma       on ed57_i_codigo = ed59_i_turma
       WHERE ed95_i_aluno           = iAluno
         AND ed95_i_calendario      = iCalendario
         AND ed95_i_serie           = iSerie
         AND ed59_c_condicao        = 'OB'
         AND ed74_c_resultadofinal != 'A'
         AND ed57_i_codigo          = iTurma;

      GET DIAGNOSTICS iLinhas2 = ROW_COUNT;

      IF iLinhas2 = 0 THEN
        cRFatual := 'A';
      ELSE
        SELECT ed95_i_codigo INTO iDiario2
          FROM diario
               inner join aluno       on ed47_i_codigo = ed95_i_aluno
               inner join diariofinal on ed74_i_diario = ed95_i_codigo
               inner join regencia    on ed59_i_codigo = ed95_i_regencia
               inner join turma       on ed57_i_codigo = ed59_i_turma
         WHERE ed95_i_aluno          = iAluno
           AND ed95_i_calendario     = iCalendario
           AND ed95_i_serie          = iSerie
           AND ed59_c_condicao       = 'OB'
           AND ed74_c_resultadofinal = 'R'
           AND ed57_i_codigo          = iTurma;
  
        GET DIAGNOSTICS iLinhas3 = ROW_COUNT;

        IF iLinhas3 > 0 THEN
          cRFatual := 'R';
        ELSE
          cRFatual := 'A';
        END IF;
      END IF;
    ELSE
      cRFatual := 'S';
    END IF;
  ELSE
    cRFatual := 'A';
  END IF;
  RETURN cRFatual;
END;
$$ LANGUAGE plpgsql;-- 00 Liberado para execucao
-- 01 Empenho nao pode ser anulado   ( saldo na data )
-- 02 Empenho nao pode ser anulado   ( saldo geral )
-- 03 Empenho nao pode ser liquidado ( saldo na data )
-- 04 Empenho nao pode ser liquidado ( saldo geral ) 
-- 05 Empenho nao pode ser anulado liquidacao ( saldo na data )
-- 06 Empenho nao pode ser anulado liquidacao ( saldo geral )
-- 07 Empenho nao pode ser pago      ( saldo na data )
-- 08 Empenho nao pode ser pago      ( saldo geral )
-- 09 Empenho nao pode ser anulado pagamento   ( saldo  na data )
-- 10 Empenho nao pode ser anulado pagamento   ( saldo geral )
-- 11 AUTORIZACAO SEM RESERVA DE SALDO                        
-- 12 SEM SALDO NA DOTACAO (codigo autorizacao) (saldo dotacao);
-- 13 PROCESSO AUTORIZADO (codigo da reserva)
-- 14 Empenho nao encontrado
-- 15 Nao usado
-- 16 Empenho nao encontrado no empresto ( nao e resto a pagar )

--drop function fc_verifica_lancamento (integer,date,integer,float8);
create or replace function fc_verifica_lancamento(integer,date,integer,float8) returns text as
$$
declare

  p_numemp         alias for $1;
  p_dtfim          alias for $2;
  p_doc            alias for $3;
  p_valor          alias for $4;

  c_lanc           record;

  v_dtini          date;
  v_erro           integer default 0; 
  
  v_vlremp         float8 default 0;
  v_vlrliq         float8 default 0;
  v_vlrpag         float8 default 0;

  v_vlremp_g       float8 default 0;
  v_vlrliq_g       float8 default 0;
  v_vlrpag_g       float8 default 0;

  v_saldodisprp    numeric default 0;

  m_erro           text;
   
  v_anousu         integer default 0;
  v_instit         integer ;

  v_saldo_dotacao  float8 default 0;
  v_reserva_valor  float8 default 0;

  dataemp          boolean;  --  permite empenho com data maior que o ultimo empenho
  dataserv         boolean;  --  permite empenho com data maior que a data do servidor
  maxdataemp       date;
      
begin

  -- para documento numero 1 o valor da variavel p_numemp deve ser o numero da autorizacao
  -- significa que a autorizacao esta sendo empenhada e ira gerar o documento 1
  if p_doc = 1 then

    -- Busca Instituicao da Autorizacao
    select e54_instit
      into v_instit
      from empautoriza
     where e54_autori = p_numemp;
  
    -- verifica se pode empenhar com data anterior a do servidor ( retornaram a data )
    select e30_empdataemp,
           e30_empdataserv 
      into dataemp, dataserv
      from empparametro 
     where e39_anousu = substr(p_dtfim,1,4)::integer ;
      
    if (dataemp = false) then
      -- nao permite empenhar com data anterior ao ultimo empenho emitido
      select max(e60_emiss)
        into maxdataemp
        from empempenho
       where e60_instit = v_instit;

      if  p_dtfim < maxdataemp  then         
        return '11 VOC� N�O PODE EMPENHAR COM DATA INFERIOR AO ULTIMO EMPENHO EMITIDO. INSTITUICAO ('||v_instit||')';       
      end if;
      
    end if;     
   
    if (dataserv = false) then
      -- nao permite empenhar com data superior a data do servidor
      if  p_dtfim > current_date  then         
        return '11 VOC� N�O PODE EMPENHAR COM DATA SUPERIOR A DATA DO SISTEMA (SERVIDOR). INSTITUICAO ('||v_instit||')';       
      end if;
      
    end if;     
   
    -- testa se existe a reserva de saldo para est
    select o83_codres 
      into v_erro
      from orcreservaaut
     where o83_autori = p_numemp;

    if not found then
      return '11 AUTORIZA��O SEM RESERVA DE SALDO. (' || p_numemp || ').';
    else
      select substr(fc_dotacaosaldo(o80_anousu,o80_coddot,2,p_dtfim,p_dtfim),106,13)::float8, o80_valor
        into v_saldo_dotacao, v_reserva_valor
        from orcreserva 
       where o80_codres = V_ERRO;

      if v_saldo_dotacao >= v_reserva_valor then
        return '0  PROCESSO AUTORIZADO (' || v_erro || ').';
      else
        return '12 SEM SALDO NA DOTACAO (' || v_erro || ' - ' || v_saldo_dotacao || ').';
      end if;
  
    end if;
      
      
  end if;

  -- testa os lancamentos do empenho 
  select e60_emiss, 
         e60_anousu, 
         e60_instit
    into v_dtini, v_anousu 
    from empempenho 
   where e60_numemp = p_numemp;

  if v_dtini is null then
    return '14 Empenho n�o encontrado.';
  end if;

  if v_anousu < substr(p_dtfim,1,4)::integer then
      
    select round(round(e91_vlremp,2) - round(e91_vlranu,2),2)::float8,
           round(e91_vlrliq,2)::float8,
           round(e91_vlrpag,2)::float8
      into v_vlremp,v_vlrliq,v_vlrpag
      from empresto
     where e91_anousu = substr(p_dtfim,1,4)::integer 
       and e91_numemp = p_numemp;

    if v_vlremp is null then
      return '16 EMPENHO N�O CADASTRADO COMO RESTOS A PAGAR';
    end if;      
      
    v_dtini := (substr(p_dtfim,1,4)||'-01-01')::date;

    v_vlremp_g := v_vlremp;
    v_vlrliq_g := v_vlrliq;
    v_vlrpag_g := v_vlrpag;
      
  end if;


  if v_dtini > p_dtfim then
    return '14 Data informada menor que data de emiss�o do Empenho.';   
  end if;

  raise notice 'data ini % - EMP %', v_dtini, p_numemp;

  for c_lanc in  
      select c53_coddoc,
             c53_descr,
             c75_numemp,
             c70_data,
             c70_valor
        from conlancamemp 
             inner join conlancamdoc on c75_codlan = c71_codlan 
             inner join conlancam    on c70_codlan = c75_codlan 
             inner join conhistdoc   on c53_coddoc = c71_coddoc 
       where c75_numemp = p_numemp 
         and c75_data >= v_dtini
    order by c70_data  
  loop
    
    -- RAISE NOTICE '\n%,%,%,%,%',c_lanc.c53_coddoc,c_lanc.c53_descr,c_lanc.c75_numemp,c_lanc.c70_valor,c_lanc.c70_data;

    /**
     * Empenho - 10 
     */
    if c_lanc.c53_coddoc in(1, 82, 304, 308, 410, 500, 504) then
    
      if c_lanc.c70_data <= p_dtfim then
        v_vlremp := round(v_vlremp + c_lanc.c70_valor,2)::float8;
      end if;
      v_vlremp_g := round(v_vlremp_g + c_lanc.c70_valor,2)::float8;
      
    /**
     * Estorno de Empenho - 11
     */  
    elsif c_lanc.c53_coddoc in(2, 83, 305, 309, 411, 501, 505) then
    
      if c_lanc.c70_data <= p_dtfim then
        v_vlremp := round(v_vlremp - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlremp_g := round(v_vlremp_g - c_lanc.c70_valor,2)::float8;
     
    /**
     * Estorno de RP nao processado - 11
     */  
    elsif c_lanc.c53_coddoc = 32 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlremp := round(v_vlremp - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlremp_g := round(v_vlremp_g - c_lanc.c70_valor,2)::float8;
      
    /**
     * Liquidacao - 20
     */  
    elsif c_lanc.c53_coddoc in(3, 23, 39, 84, 412, 202, 204, 206, 306, 310, 502, 506) then
    
      if c_lanc.c70_data <= p_dtfim then
        v_vlrliq := round(v_vlrliq + c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrliq_g := round(v_vlrliq_g + c_lanc.c70_valor,2)::float8;
    
    /**
     * liquidacao de RP - 20
     */
    elsif c_lanc.c53_coddoc = 33 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrliq := round(v_vlrliq + c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrliq_g := round(v_vlrliq_g + c_lanc.c70_valor,2)::float8;
      
     /**
      * Anulacao de liquidacao - 21
      */
    elsif c_lanc.c53_coddoc in(4, 24, 40, 85, 203, 205, 207, 413, 307, 311, 503, 507) then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrliq := round(v_vlrliq - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrliq_g := round(v_vlrliq_g - c_lanc.c70_valor,2)::float8;
      
    /**
     * Anulacao de Liquidacao de RP - 21
     */  
    elsif c_lanc.c53_coddoc = 34 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrliq := round(v_vlrliq - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrliq_g := round(v_vlrliq_g - c_lanc.c70_valor,2)::float8;
    
      
    /**
     * pagamento - 30
     */
    elsif c_lanc.c53_coddoc = 5 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrpag := round(v_vlrpag + c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrpag_g := round(v_vlrpag_g + c_lanc.c70_valor,2)::float8;
      
     /**
      * estorno de pagamento - 31
      */  
    elsif c_lanc.c53_coddoc = 6 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrpag := round(v_vlrpag - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrpag_g := round(v_vlrpag_g - c_lanc.c70_valor,2)::float8;
      
     /**
      * Pagamento de RP - 30
      */ 
    elsif c_lanc.c53_coddoc = 35 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrpag := round(v_vlrpag + c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrpag_g := round(v_vlrpag_g + c_lanc.c70_valor,2)::float8;
     
    /**
     * estorno de pagamento de RP - 31
     */  
    elsif c_lanc.c53_coddoc = 36 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrpag := round(v_vlrpag - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrpag_g := round(v_vlrpag_g - c_lanc.c70_valor,2)::float8;
     
    /**
     * Pagamento de RP processado - 30
     */  
    elsif c_lanc.c53_coddoc = 37 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrpag := round(v_vlrpag + c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrpag_g := round(v_vlrpag_g + c_lanc.c70_valor,2)::float8;
    
      /**
       * Estorno de RP n�o processado - 31
       */
    elsif c_lanc.c53_coddoc = 38 then
      if c_lanc.c70_data <= p_dtfim then
        v_vlrpag := round(v_vlrpag - c_lanc.c70_valor,2)::float8;
      end if;
      v_vlrpag_g := round(v_vlrpag_g - c_lanc.c70_valor,2)::float8;
    end if;
       
  end loop;
  --raise notice '%,%,%',v_vlremp,v_vlrliq,v_vlrpag;

  if p_doc = 2 or p_doc = 32 then
  
    -- testar anulacao de empenho 
    -- precisa ter saldo para anular ( emp - liq > 0 ) 
    -- erro na data passada
    if round(v_vlremp - v_vlrliq,2)::float8 >= p_valor then
      m_erro := '0 PROCESSO AUTORIZADO.';
      v_erro := 0;
    else 
      m_erro :=  '01 N�o existe saldo para anular nesta data.';
      v_erro := 1;
    end if;

    -- erro geral no empenho
    if v_erro = 0 then
      if round(v_vlremp_g - v_vlrliq_g,2)::float8 >= p_valor then
        m_erro := '0 PROCESSO AUTORIZADO.';
        v_erro := 0;
      else 
        m_erro := '02 N�o existe saldo geral no empenho para anular.';
        v_erro := 2;
      end if;
    end if;
    
  end if;
 
  if p_doc = 3 or p_doc = 33 or p_doc = 23 then
  
    -- testar liquidacao de empenho 
    -- precisa ter saldo para anular ( emp - liq > 0 ) 
    -- erro na data passada 
    if round(v_vlremp - v_vlrliq,2)::float8 >= round(p_valor,2)::float8 then
      m_erro := '0 PROCESSO AUTORIZADO.';
      v_erro := 0;
    else 
      m_erro := '03 N�o existe saldo a liquidar neste data.';
      v_erro := 3;
    end if;

    -- erro geral no empenho
    if v_erro = 0 then
      if round(v_vlremp_g - v_vlrliq_g,2)::float8 >= p_valor then
        m_erro := '0 PROCESSO AUTORIZADO.';
        v_erro := 0;
      else 
        m_erro := '04 N�o existe saldo geral no empenho para liquidar.'; 
        v_erro := 4;
      end if;
    end if;
  end if;

  if p_doc = 4 or p_doc = 34 or p_doc = 24 then
 
    -- testar estorno de liquidacao de empenho 
    -- precisa ter saldo para anular ( emp - liq > 0 ) 
    -- erro na data passada

    if p_doc = 34 then
       
      select sum(case 
               when c71_coddoc = 33 then 
                 c70_valor 
               else 
                 c70_valor * -1 
             end)
        into v_saldodisprp
        from conlancam  
             inner join conlancamemp on c75_codlan = c70_codlan
             inner join conlancamdoc on c71_codlan = c70_codlan
       where c75_numemp = p_numemp 
         and c71_coddoc in (33, 34) 
         and extract (year from c70_data) = extract (year from p_dtfim);

      --raise notice 'disp: %', v_saldodisprp;

      if v_saldodisprp is null then
        v_saldodisprp = 0;
      end if;

      --raise notice 'doc: % - valor: % - disp: % - numemp: % - p_dtfim: %', p_doc, p_valor, v_saldodisprp, p_numemp, p_dtfim;

      if p_valor > v_saldodisprp then
        m_erro := '05 N�o existe saldo para anular a liquida��o nesta data.) Saldo Dispon�vel: '|| v_saldodisprp;
        v_erro := 5;
      end if;
        
    end if;
      
    if v_erro = 0 then
      if round(v_vlrliq - v_vlrpag,2)::float8 >= p_valor then
        m_erro := '0 PROCESSO AUTORIZADO.';
        v_erro := 0;
      else 
        m_erro := '05 N�o existe saldo para anular a liquida��o nesta data.';
        v_erro := 5;
      end if;
    end if;

    -- erro geral no empenho
    if v_erro = 0 then
      if round(v_vlrliq_g - v_vlrpag_g,2)::float8 >= p_valor then
        m_erro := '0 PROCESSO AUTORIZADO.';
        v_erro := 0;
      else 
        m_erro := '06 N�o existem saldo geral no empenho para estornar a liquida��o.';
        v_erro := 6;
      end if;
    end if;
      
  end if;
 

  if p_doc = 5 or p_doc = 35  or p_doc = 37 then

    -- testar pagamento de empenho 
    -- precisa ter saldo para anular ( emp - liq > 0 ) 
    -- erro na data passada
    if( round(v_vlrliq - v_vlrpag,2)::float8 >= p_valor) then
      v_erro := 0;
      m_erro := '0 PROCESSO AUTORIZADO.';
    else 
      m_erro := '07 N�o existe saldo a pagar nesta data. Empenho: ' || to_char(p_numemp, '9999999999');
      v_erro := 7;
    end if;

    -- erro geral no empenho
    if v_erro = 0 then
      if round(v_vlrliq_g - v_vlrpag_g,2)::float8 >= p_valor then
        m_erro := '0 PROCESSO AUTORIZADO.';
        v_erro := 0;
      else 
        m_erro := '08 N�o existe saldo geral a pagar no empenho.';
        v_erro := 8;
      end if;
    end if;
    
  end if;
 
  if p_doc = 6 or p_doc = 36  or p_doc = 38 then
  
    -- testar anulacao de pagamento empenho 
    -- precisa ter saldo para anular ( emp - liq > 0 ) 
    -- erro na data passada
    if v_vlrpag >= p_valor then
      m_erro := '0 PROCESSO AUTORIZADO.';
      v_erro := 0;
    else 
      m_erro := '09 N�o existe saldo para anular pagamento nesta data.';
      v_erro := 9;
    end if;

    -- erro geral no empenho
    if v_erro = 0 then
      if v_vlrpag_g >= p_valor then
        m_erro := '0 PROCESSO AUTORIZADO.';
        v_erro := 0;
      else  
        m_erro := '10 N�o existe saldo geral no empenho para anular o pagamento.';
        v_erro := 10;
      end if;
    end if;
    
  end if;
 

  --raise notice ' ate data %,%,%',v_vlremp,v_vlrliq,v_vlrpag;
  --raise notice ' geral    %,%,%',v_vlremp_g,v_vlrliq_g,v_vlrpag_g;

  return m_erro;

end;
$$ 
language 'plpgsql';create or replace function fc_calculaprecomedio(integer, integer, float8, boolean)
  returns numeric as
$$

declare

  iCodigoMatestoqueiniMei        alias for $1;
  iCodigoMatestoqueini           alias for $2;
  nQuantidadeMovimento           alias for $3;
  lRecursivo                     alias for $4;

  nPrecoMedio                    numeric default 0;
  iMaterial                      integer;
  iInstituicao                   integer;
  iAlmoxarifado                  integer;
  nValorEstoque                  numeric;
  nQuantidadeEstoque             numeric default 0;
  nValorEstoqueDiferenca         numeric default 0;
  nQuantidadeEstoqueDiferenca    numeric default 0;
  iTipoMovimento                 integer;
  iCodigoEstoque                 integer;
  iCodigoMovimento               integer;
  iCodigoEntradaItem             integer;
  nValorUnitario                 numeric default 0;
  dtMovimento                    date;
  tHora                          timestamp;
  tHoraMovimento                 time;
  lTemPrecoMedio                 boolean default false;
  rValoresPosteriores            record;
  lServico                       boolean;
  iDepto                         integer;
  nQuantidadeSaidasPosteriores   numeric default 0;
  nQuantidadeEntradasPosteriores numeric default 0;
  nSaidasNoPeriodo               numeric default 0;
  nSaldoNoPeriodo                numeric default 0;
  nSaldoAposPeriodo              numeric default 0;
  sMensagemEstoque               varchar;
  lEntradaAposPeriodo            boolean default false;
  sSqlPrecoMedio                 varchar;
begin

   iInstituicao = fc_getsession('DB_instit');
   if iInstituicao is null then 
     raise exception 'Instituicao n�o informada.';
   end if;  

   /**
    * Consultamos o codigo do material,
    * atraves da tabela matestoqueitem, com o campo new.m82_matestoqueitem.
    */
    select m70_codmatmater,
           (case when  m71_quant > 0 then
           coalesce(m71_valor/m71_quant, 0)
           else 0 end),
           m71_servico,
           m70_coddepto,
           m71_codlanc  
      into iMaterial,
           nValorUnitario,
           lServico,
           iAlmoxarifado,
           iCodigoEntradaItem
      from matestoqueitem 
           inner join matestoque       on m70_codigo  = m71_codmatestoque
           inner join matestoqueinimei on m71_codlanc = m82_matestoqueitem
      where m82_codigo  = iCodigoMatestoqueiniMei;

   /**
    * Consultamos o tipo da movimentacao
    */
   select m80_codtipo,
          m81_tipo,
          to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'),
          m80_data,
          m80_hora,
          m80_coddepto,
          instit
     into iCodigoMovimento,
          iTipoMovimento,
          tHora,
          dtMovimento,
          tHoraMovimento,
          iDepto,
          iInstituicao
     from matestoqueini 
          inner join matestoquetipo on m81_codtipo = m80_codtipo
          inner join DB_DEPART on m80_coddepto     = coddepto
    where m80_codigo = iCodigoMatestoqueini;

   /**
    * Soma a quantidade em estoque do item na instituicao 
    * 
    */
   select coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2) when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0),
          round(coalesce(sum(CASE when m81_tipo = 1 then round(round(m82_quant, 2)*m89_valorunitario, 5) 
                            when m81_tipo = 2 then round(m82_quant, 2)*round(m89_precomedio, 5)*-1 end), 0) , 2)
     into nQuantidadeEstoque,
          nValorEstoque  
     from matestoque
          inner join db_depart          on m70_coddepto       = coddepto  
          inner join matestoqueitem     on m70_codigo         = m71_codmatestoque
          inner join matestoqueinimei   on m82_matestoqueitem = m71_codlanc
          inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
          inner join matestoqueini      on m82_matestoqueini  = m80_codigo
          inner join matestoquetipo     on m81_codtipo        = m80_codtipo
    where instit           = iInstituicao
      and m70_codmatmater  = iMaterial
      and to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
      and m82_codigo <> iCodigoMatestoqueiniMei
      and m70_coddepto = iAlmoxarifado 
      and m81_tipo not in(4)
      and m71_servico is false;
   
   /**
     * verificamos se o item possui no mesmo movimento entradas para o mesmo item de estoque 
     */ 
    SELECT coalesce(sum(CASE when m81_tipo = 1 then round(m82_quant, 2) 
                             when m81_tipo = 2 then round(m82_quant,2)*-1 end), 0) as saldodif, 
           round(coalesce(sum(CASE when m81_tipo = 1 then round(round(round(m82_quant, 2)*m89_valorunitario, 5), 2) 
                            when m81_tipo = 2 then round(round(m82_quant, 2)*round(m89_precomedio, 5), 2)*-1 end), 0), 2) 
      into nQuantidadeEstoqueDiferenca,
          nValorEstoqueDiferenca   
      from matestoqueinimei 
           inner join matestoqueitem     on m71_codlanc          = m82_matestoqueitem 
           inner join matestoque         on m71_codmatestoque    = m70_codigo 
           inner join matestoqueinimeipm on m89_matestoqueinimei = m82_codigo 
           inner join matestoqueini      on m82_matestoqueini    = m80_codigo
           inner join matestoquetipo     on m80_codtipo          = m81_codtipo 
     where m70_codmatmater   = iMaterial 
       and m82_matestoqueini = iCodigoMatestoqueini 
       and m82_codigo        > iCodigoMatestoqueiniMei
       and m70_coddepto = iAlmoxarifado 
       and m81_tipo not in(4)
       and m71_servico is false;
       nQuantidadeEstoque := nQuantidadeEstoque - nQuantidadeEstoqueDiferenca;
       nValorEstoque      := nValorEstoque      - nValorEstoqueDiferenca;

   /**
    * Verificamos o ultimo preco medio da data do material para o item.
    */

   select round(m85_precomedio, 5)
     into nPrecoMedio
     from matmaterprecomedio
    where m85_matmater = iMaterial
      and m85_instit   = iInstituicao
      and m85_coddepto = iAlmoxarifado
      and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
    order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

    if ( not found or nPrecoMedio = 0 ) and iCodigoMovimento in (8) then

   select round(m85_precomedio, 5)
     into nPrecoMedio
     from matmaterprecomedio
    where m85_matmater = iMaterial
      and m85_instit   = iInstituicao
      and m85_precomedio > 0
      and m85_coddepto = ( select m80_coddepto
                             from matestoqueini
                                  inner join matestoqueinil  inil  on inil.m86_matestoqueini   = matestoqueini.m80_codigo
                                  inner join matestoqueinill inill on inill.m87_matestoqueinil = inil.m86_codigo
                            where inill.m87_matestoqueini = iCodigoMatestoqueini limit 1)
      and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora
    order by to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') desc limit 1;

      update matmaterprecomedio 
         set m85_precomedio = nPrecoMedio
       where m85_matmater = iMaterial
         and m85_instit   = iInstituicao
         and m85_coddepto = iAlmoxarifado
         and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') <= tHora;

    end if;

    if nQuantidadeEstoque = 0 then
       nValorEstoque := 0;
    end if;
    if  found then
     lTemPrecoMedio = true;  
   end if;
   nPrecoMedio := coalesce(nPrecoMedio, 0);
  /**
   * Verificamos as entradas no estoque (refletem no calculo do pre�o medio)
   * algumas entradas, que na verdade s�o cancelamentos de saidas, devem entrar no estoque
   * pelo preco m�dio atual, n�o alterando o pre�o do calculo m�dio. 
   */
  if iCodigoMovimento in(8, 1, 3, 12, 14, 15) then 
       
    /**
     * como o sistema j� inclui as informa��es do estoque na hora de verificarmos o pre�o m�dio, 
     * devemos deduzir a quantidade da entrada, (nQuantidade - m82_quant). a regra do calculo do pre�o m�dio �:
     * pegamos a quantidade anterior em estoque, e multiplicamos pelo ultimo pre�o m�dio.
     * - Somamos a nova entrada (quantidade e valor da entrada,) e dividimos o valor encontrado pela quantidade 
     * encontrada. o resultado dessa divis�o, encontramos o pre�o m�dio. 
     */
    --nValorEstoque      = round(nQuantidadeEstoque * nPrecoMedio, 2);
    nQuantidadeEstoque = nQuantidadeEstoque  + nQuantidadeMovimento;
    nValorEstoque      = round(nValorEstoque + (nQuantidadeMovimento*nValorUnitario), 2);
    nPrecoMedio        = 0;
    if nQuantidadeEstoque > 0 then  
      nPrecoMedio    = round( nValorEstoque / nQuantidadeEstoque, 5);
    end if;
  /**
   * Excluimos o pre�o medio para o movimento/hora
   */
    delete from matmaterprecomedio
     where m85_matmater = imaterial
       and m85_instit   = iInstituicao
       and m85_coddepto = iAlmoxarifado
       and to_timestamp(m85_data || ' ' || m85_hora, 'YYYY-MM-DD HH24:MI:SS') >= tHora;    

    insert into matmaterprecomedio
                  (m85_sequencial,
                   m85_matmater,
                   m85_instit,
                   m85_precomedio,
                   m85_data,
                   m85_hora,
                   m85_coddepto
                  )
           values (nextval('matmaterprecomedio_m85_sequencial_seq'),
                   iMaterial,
                   iInstituicao,
                   round(nPrecoMedio, 5),
                   dtMovimento,
                   tHoraMovimento,
                   iAlmoxarifado
                  );

  elsif iTipoMovimento = 2 and iCodigoMovimento not in(8, 9) then

    nValorUnitario = round(nPrecoMedio, 5);

  elsif iCodigoMovimento in(7, 6, 18, 9) then
   
    nValorUnitario = round(nPrecoMedio, 5);
  
  elsif iCodigoMovimento in (21) then 

    /**  
     * caso  a transferencia seja confirmada, 
     * temos que fazer a entrada no estoque ao mesmo valor da saida, pois a movimentacao no estoque 
     * nao existe a movimentacao de valores.
     * o codigo da transferencia est� na tabela mastoqueinil/matestoqueinill
     */ 
     select round(m89_precomedio, 5)
       into nPrecoMedio 
       from matestoqueinill
            inner join matestoqueinil     on m87_matestoqueinil = m86_codigo
            inner join matestoqueinimei   on m86_matestoqueini  = m82_matestoqueini
            inner join matestoqueinimeipm on m82_codigo         = m89_matestoqueinimei
            inner join matestoqueitem     on m82_matestoqueitem = m71_codlanc 
            inner join matestoque         on m70_codigo         = m71_codmatestoque
      where m70_codmatmater   = iMaterial 
        and m87_matestoqueini = iCodigoMatestoqueini
        and m71_servico is false;
    
     nValorUnitario = round(nPrecoMedio, 5);
  end if;

  delete from matestoqueinimeipm where m89_matestoqueinimei = iCodigoMatestoqueiniMei;
  insert into matestoqueinimeipm
              (m89_sequencial,
               m89_matestoqueinimei,
               m89_precomedio,
               m89_valorunitario,
               m89_valorfinanceiro
               )
       values (nextval('matestoqueinimeipm_m89_sequencial_seq'),
               iCodigoMatestoqueiniMei,
               round(nPrecoMedio, 5),
               round(nValorUnitario, 5),
               round(nQuantidadeMovimento * round(nValorUnitario, 5), 2)
              );
  return round(nPrecoMedio, 5); 
end;
$$
language 'plpgsql';create or replace function fc_saldo_item_estoque(integer, integer)
returns numeric
as $$
  declare

   nSaldo numeric default 0;
   iCodigoDepartamento alias for $1;
   iCodigoMaterial     alias for $2;

 begin

    select coalesce(sum(case when m81_tipo = 1 then m82_quant when m81_tipo = 2 then m82_quant * -1  else 0 end), 0)
      into nSaldo
      from matestoqueinimei
           inner join matestoqueini  on m82_matestoqueini  = m80_codigo
           inner join matestoqueitem on m82_matestoqueitem = m71_codlanc
           inner join matestoquetipo on m81_codtipo        = m80_codtipo
           inner join matestoque     on m71_codmatestoque  = m70_codigo
     where m70_coddepto    = iCodigoDepartamento
       and m70_codmatmater = iCodigoMaterial
       and m71_servico     is false;

    return nSaldo;

  end;
$$ language 'plpgsql';create or replace function fc_baixabanco( cod_ret integer, datausu date ) returns varchar as
$$
declare

  retorno                   boolean default false;

  r_codret                  record;
  r_idret                   record;
  r_divold                  record;
  r_receitas                record;
  r_idunica                 record;
  q_disrec                  record;
  r_testa                   record;

  x_totreg                  float8;
  valortotal                float8;
  valorjuros                float8;
  valormulta                float8;
  fracao                    float8;
  nVlrRec                   float8;
  nVlrTfr                   float8;
  nVlrRecm                  float8;
  nVlrRecj                  float8;

  _testeidret               integer;
  vcodcla                   integer;
  gravaidret                integer;
  v_nextidret               integer;
  conta                     integer;

  v_contador                integer;
  v_somador                 numeric(15,2) default 0;
  v_valor                   numeric(15,2) default 0;

  v_valor_sem_round         float8;
  v_diferenca_round         float8;

  dDataCalculoRecibo        date;
  dDataReciboUnica          date;

  v_contagem                integer;
  primeirarec               integer default 0;
  primeirarecj              integer default 0;
  primeirarecm              integer default 0;
  primeiranumpre            integer;
  primeiranumpar            integer;

  nBloqueado                integer;

  valorlanc                 float8;
  valorlancj                float8;
  valorlancm                float8;

  oidrec                    int8;

  autentsn                  boolean;

  valorrecibo               float8;

  v_total1                  float8 default 0;
  v_total2                  float8 default 0;

  v_estaemrecibopaga        boolean;
  v_estaemrecibo            boolean;
  v_estaemarrecadnormal     boolean;
  v_estaemarrecadunica      boolean;
  lVerificaReceita          boolean;
  lClassi                   boolean;
  lReciboPossuiPgtoParcial  boolean default false;

  nSimDivold                integer;
  nNaoDivold                integer;
  iQtdeParcelasAberto       integer;
  iQtdeParcelasRecibo       integer;

  nValorSimDivold           numeric(15,2) default 0;
  nValorNaoDivold           numeric(15,2) default 0;
  nValorTotDivold           numeric(15,2) default 0;

  nValorPagoDivold          numeric(15,2) default 0;
  nTotValorPagoDivold       numeric(15,2) default 0;

  nTotalRecibo              numeric(15,2) default 0;
  nTotalNovosRecibos        numeric(15,2) default 0;

  nTotalDisbancoOriginal    numeric(15,2) default 0;
  nTotalDisbancoDepois      numeric(15,2) default 0;

  iNumnovDivold             integer;
  iIdret                    integer;
  v_diferenca               float8 default 0;

  cCliente                  varchar(100);
  iIdRetProcessar           integer;

  -- Abatimentos
  lAtivaPgtoParcial         boolean default false;
  lInsereJurMulCorr         boolean default true;

  iAbatimento               integer;
  iAbatimentoArreckey       integer;
  iArreckey                 integer;
  iArrecadCompos            integer;
  iNumpreIssVar             integer;
  iNumpreRecibo             integer;
  iNumpreReciboAvulso       integer;
  iTipoDebitoPgtoParcial    integer;
  iTipoAbatimento           integer;
  iTipoReciboAvulso         integer;
  iReceitaCredito           integer;
  iRows                     integer;
  iSeqIdRet                 integer;
  iNumpreAnterior           integer default 0;

  nVlrCalculado             numeric(15,2) default 0;
  nVlrPgto                  numeric(15,2) default 0;
  nVlrJuros                 numeric(15,2) default 0;
  nVlrMulta                 numeric(15,2) default 0;
  nVlrCorrecao              numeric(15,2) default 0;
  nVlrHistCompos            numeric(15,2) default 0;
  nVlrJurosCompos           numeric(15,2) default 0;
  nVlrMultaCompos           numeric(15,2) default 0;
  nVlrCorreCompos           numeric(15,2) default 0;
  nVlrPgtoParcela           numeric(15,2) default 0;
  nVlrDiferencaPgto         numeric(15,2) default 0;
  nVlrTotalRecibopaga       numeric(15,2) default 0;
  nVlrTotalHistorico        numeric(15,2) default 0;
  nVlrTotalJuroMultaCorr    numeric(15,2) default 0;
  nVlrReceita               numeric(15,2) default 0;
  nVlrAbatido               numeric(15,2) default 0;
  nVlrDiferencaDisrec       numeric(15,2) default 0;
  nVlrInformado             numeric(15,2) default 0;
  nVlrTotalInformado        numeric(15,2) default 0;

  nVlrToleranciaPgtoParcial numeric(15,2) default 0;
  nVlrToleranciaCredito     numeric(15,2) default 0;

  nPercPgto                 numeric;
  nPercReceita              numeric;
  nPercDesconto             numeric;

  sSql                      text;

  iAnoSessao                integer;
  iInstitSessao             integer;

  rReciboPaga               record;
  rContador                 record;
  rRecordDisbanco           record;
  rRecordBanco              record;
  rRecord                   record;
  rRecibo                   record;
  rAcertoDiferenca          record;

  /**
   * variavel de controle do numpre , se tiver ativado o pgto parcial, e essa variavel for dif. de 0
   * os numpres a partir dele ser�o tratados como pgto parcial, abaixo, sem pgto parcial
   */
  iNumprePagamentoParcial   integer default 0;

  lRaise                    boolean default false;
  sDebug                    text;

begin

  -- Busca Dados Sess�o
  iInstitSessao := cast(fc_getsession('DB_instit') as integer);
  iAnoSessao    := cast(fc_getsession('DB_anousu') as integer);
  lRaise        := ( case when fc_getsession('DB_debugon') is null then false else true end );

  if lRaise is true then
    if trim(fc_getsession('db_debug')) <> '' then
      perform fc_debug('  <BaixaBanco>  - INICIANDO PROCESSAMENTO... ',lRaise,false,false);
    else
      perform fc_debug('  <BaixaBanco>  - INICIANDO PROCESSAMENTO... ',lRaise,true,false);
    end if;
  end if;

  /**
   * Verifica se esta configurado Pagamento Parcial
   * Buscamos o valor base setado na numpref campo k03_numprepgtoparcial
   * Consulta o tipo de debito configurado para Recibo Avulso
   * Consulta o parametro de tolerancia para pagamento parcial
   */
  select k03_pgtoparcial,
         k03_numprepgtoparcial,
         k03_reciboprot,
         coalesce(numpref.k03_toleranciapgtoparc,0)::numeric(15, 2),
         coalesce(numpref.k03_toleranciacredito,0)::numeric(15, 2)
    into lAtivaPgtoParcial,
         iNumprePagamentoParcial,
         iTipoReciboAvulso,
         nVlrToleranciaPgtoParcial,
         nVlrToleranciaCredito
    from numpref
   where numpref.k03_anousu = iAnoSessao
     and numpref.k03_instit = iInstitSessao;

   if lRaise is true then
     perform fc_debug('  <BaixaBanco>  - PARAMETROS DO NUMPREF '                                  ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - lAtivaPgtoParcial:  '||lAtivaPgtoParcial                 ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - iNumprePagamentoParcial:  '||iNumprePagamentoParcial     ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - iTipoReciboAvulso:  '||iTipoReciboAvulso                 ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - nVlrToleranciaPgtoParcial:  '||nVlrToleranciaPgtoParcial ,lRaise,false,false);
     perform fc_debug('  <BaixaBanco>  - nVlrToleranciaCredito:  '||nVlrToleranciaCredito         ,lRaise,false,false);
   end if;

   if iTipoReciboAvulso is null then
     return '2 - Operacao Cancelada. Tipo de Debito nao configurado para Recibo Avulso. ';
   end if;

    select k00_conta,
           autent,
           count(*)
      into conta,
           autentsn,
           vcodcla
      from disbanco
           inner join disarq on disarq.codret = disbanco.codret
     where disbanco.codret = cod_ret
       and disbanco.classi is false
       and disbanco.instit = iInstitSessao
  group by disarq.k00_conta,
           disarq.autent;

  if vcodcla is null or vcodcla = 0 then
    return '3 - ARQUIVO DE RETORNO DO BANCO JA CLASSIFICADO';
  end if;
  if conta is null or conta = 0 then
    return '4 - SEM CONTA CADASTRADA PARA ARRECADACAO. OPERACAO CANCELADA.';
  end if;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  - autentsn:  '||autentsn,lRaise,false,false);
  end if;

  select upper(munic)
  into cCliente
  from db_config
  where codigo = iInstitSessao;

  if autentsn is false then

    select nextval('discla_codcla_seq')
      into vcodcla;

    insert into discla (
      codcla,
      codret,
      dtcla,
      instit
    ) values (
      vcodcla,
      cod_ret,
      datausu,
      iInstitSessao
    );

   /**
    * Insere dados da baixa de Banco nesta tabela pois na pl que a chama o arquivo e divido em mais de uma classificacao
    */
   if lRaise is true then
     perform fc_debug('  <BaixaBanco> - 276 - '||cod_ret||','||vcodcla,lRaise,false,false);
   end if;

   insert into   tmp_classificaoesexecutadas("codigo_retorno", "codigo_classificacao")
          values (cod_ret, vcodcla);

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - vcodcla: '||vcodcla,lRaise,false,false);
    end if;

  else
    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - nao '||autentsn,lRaise,false,false);
    end if;
  end if;

/**
 * Aqui inicia pre-processamento do Pagamento Parcial
 */
  if lAtivaPgtoParcial is true then

    if lRaise then
      perform fc_debug('  <PgtoParcial>  - Parametro pagamento parcial ativado !',lRaise,false,false);
    end if;

    /*******************************************************************************************************************
     *  VERIFICA RECIBO AVULSO
     ******************************************************************************************************************/
    -- Caso exista algum recibo avulso que jah esteja pago, o sistema gera um novo recibo avulso
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 1 - VERIFICA RECIBO AVULSO',lRaise,false,false);
    end if;

    for rRecordDisbanco in

      select disbanco.*
        from disbanco
             inner join recibo   on recibo.k00_numpre   = disbanco.k00_numpre
             inner join arrepaga on arrepaga.k00_numpre = disbanco.k00_numpre
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and case when iNumprePagamentoParcial = 0
                  then true
                  else disbanco.k00_numpre > iNumprePagamentoParcial
              end
         and disbanco.instit = iInstitSessao

    loop

      select nextval('numpref_k03_numpre_seq')
        into iNumpreRecibo;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - lan�a recibo avulto j� pago ',lRaise,false,false);
      end if;

      insert into recibo ( k00_numcgm,
                 k00_dtoper,
               k00_receit,
               k00_hist,
               k00_valor,
               k00_dtvenc,
               k00_numpre,
               k00_numpar,
               k00_numtot,
               k00_numdig,
               k00_tipo,
               k00_tipojm,
               k00_codsubrec,
               k00_numnov
                         ) select k00_numcgm,
                                k00_dtoper,
                                k00_receit,
                              k00_hist,
                              k00_valor,
                              k00_dtvenc,
                              iNumpreRecibo,
                              k00_numpar,
                              k00_numtot,
                              k00_numdig,
                              k00_tipo,
                              k00_tipojm,
                              k00_codsubrec,
                              k00_numnov
                             from recibo
                            where recibo.k00_numpre = rRecordDisbanco.k00_numpre;


      insert into reciborecurso ( k00_sequen,
                    k00_numpre,
                    k00_recurso
                                ) select nextval('reciborecurso_k00_sequen_seq'),
                               iNumpreRecibo,
                               k00_recurso
                                    from reciborecurso
                                   where reciborecurso.k00_numpre = rRecordDisbanco.k00_numpre;


      insert into arrehist ( k00_numpre,
                             k00_numpar,
                             k00_hist,
                             k00_dtoper,
                             k00_hora,
                             k00_id_usuario,
                             k00_histtxt,
                             k00_limithist,
                             k00_idhist
                           ) values (
                             iNumpreRecibo,
                             1,
                             502,
                             datausu,
                             '00:00',
                             1,
                             'Recibo avulso referente a baixa do recibo avulso ja pago - Numpre : '||rRecordDisbanco.k00_numpre,
                             null,
                             nextval('arrehist_k00_idhist_seq')
                          );

      insert into arreproc ( k80_numpre,
                             k80_codproc )  select iNumpreRecibo,
                                                   arreproc.k80_codproc
                                              from arreproc
                                             where arreproc.k80_numpre = rRecordDisbanco.k00_numpre;

      insert into arrenumcgm ( k00_numpre,
                               k00_numcgm ) select iNumpreRecibo,
                                                   arrenumcgm.k00_numcgm
                                              from arrenumcgm
                                             where arrenumcgm.k00_numpre = rRecordDisbanco.k00_numpre;

      insert into arrematric ( k00_numpre,
                               k00_matric ) select iNumpreRecibo,
                                                   arrematric.k00_matric
                                              from arrematric
                                             where arrematric.k00_numpre = rRecordDisbanco.k00_numpre;

      insert into arreinscr ( k00_numpre,
                              k00_inscr )   select iNumpreRecibo,
                                                   arreinscr.k00_inscr
                                              from arreinscr
                                             where arreinscr.k00_numpre = rRecordDisbanco.k00_numpre;

      if lRaise then
        perform fc_debug('  <PgtoParcial>  - 1 - Alterando numpre disbanco ! novo numpre : '||iNumpreRecibo,lRaise,false,false);
      end if;

      update disbanco
         set k00_numpre = iNumpreRecibo
       where idret      = rRecordDisbanco.idret;

    end loop; --Fim do loop de valida��o da regra 1 para recibo avulso


    /*********************************************************************************
     *  GERA RECIBO PARA CARNE
     ********************************************************************************/
    -- Verifica se o pagamento eh referente a um Carne
    -- Caso seja entao eh gerado um recibopaga para os debitos
    -- do arrecad e acertado o numpre na tabela disbanco
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 2 - GERA RECIBO PARA CARNE!',lRaise,false,false);
    end if;

    for rRecordDisbanco in select disbanco.idret,
                                  disbanco.dtpago,
                                  disbanco.k00_numpre,
                                  disbanco.k00_numpar,
                                  ( select k00_dtvenc
                                      from (select k00_dtvenc
                                              from arrecad
                                             where arrecad.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arrecad.k00_numpar = disbanco.k00_numpar
                                                  end
                                           union
                                            select k00_dtvenc
                                              from arrecant
                                             where arrecant.k00_numpre = disbanco.k00_numpre
                                               and case
                                                     when disbanco.k00_numpar = 0 then true
                                                     else arrecant.k00_numpar = disbanco.k00_numpar
                                                   end
                                           union
                                            select k00_dtvenc
                                              from arreold
                                             where arreold.k00_numpre = disbanco.k00_numpre
                                               and case
                                                     when disbanco.k00_numpar = 0 then true
                                                     else arreold.k00_numpar = disbanco.k00_numpar
                                                   end
                                            ) as x limit 1
                                  ) as data_vencimento_debito
                            from disbanco
                            where disbanco.codret = cod_ret
                              and disbanco.classi is false
                              and disbanco.instit = iInstitSessao
                              and case when iNumprePagamentoParcial = 0
                                       then true
                                       else disbanco.k00_numpre > iNumprePagamentoParcial
                                   end
                              and exists ( select 1
                                             from arrecad
                                            where arrecad.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arrecad.k00_numpar  = disbanco.k00_numpar
                                                  end
                                            union all
                                           select 1
                                             from arrecant
                                            where arrecant.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arrecant.k00_numpar = disbanco.k00_numpar
                                                  end
                                           union all
                                           select 1
                                             from arreold
                                            where arreold.k00_numpre = disbanco.k00_numpre
                                              and case
                                                    when disbanco.k00_numpar = 0 then true
                                                    else arreold.k00_numpar = disbanco.k00_numpar
                                                  end
                                            limit 1 )
                              and not exists ( select 1
                                                 from issvar
                                                where issvar.q05_numpre = disbanco.k00_numpre
                                                  and issvar.q05_numpar = disbanco.k00_numpar
                                                limit 1 )
                              and not exists ( select 1
                                                 from tmpnaoprocessar
                                                where tmpnaoprocessar.idret = disbanco.idret )
                         order by disbanco.idret

    loop

      select nextval('numpref_k03_numpre_seq')
        into iNumpreRecibo;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Processando geracao de recibo para - Numpre: '||rRecordDisbanco.k00_numpre||'  Numpar: '||rRecordDisbanco.k00_numpar,lRaise,false,false);
      end if;

      select distinct
             arrecad.k00_tipo
        into rRecord
        from arrecad
       where arrecad.k00_numpre = rRecordDisbanco.k00_numpre
         and case
               when rRecordDisbanco.k00_numpar = 0
                 then true
               else arrecad.k00_numpar = rRecordDisbanco.k00_numpar
             end
       limit 1;

      if found then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Encontrou no arrecad - Gerando Recibo para o debito - Numpre: '||rRecordDisbanco.k00_numpre||'  Numpar: '||rRecordDisbanco.k00_numpar,lRaise,false,false);
        end if;

        select k00_codbco,
               k00_codage,
               fc_numbco(k00_codbco,k00_codage) as fc_numbco
          into rRecordBanco
          from arretipo
         where k00_tipo = rRecord.k00_tipo;

        insert into db_reciboweb ( k99_numpre,
                                   k99_numpar,
                                   k99_numpre_n,
                                   k99_codbco,
                                   k99_codage,
                                   k99_numbco,
                                   k99_desconto,
                                   k99_tipo,
                                   k99_origem
                                 ) values (
                                   rRecordDisbanco.k00_numpre,
                                   rRecordDisbanco.k00_numpar,
                                   iNumpreRecibo,
                                   coalesce(rRecordBanco.k00_codbco,0),
                                   coalesce(rRecordBanco.k00_codage,'0'),
                                   rRecordBanco.fc_numbco,
                                   0,
                                   2,
                                   1 );

        dDataCalculoRecibo := rRecordDisbanco.data_vencimento_debito;

        select ru.k00_dtvenc
          into dDataReciboUnica
          from recibounica ru
         where ru.k00_numpre = rRecordDisbanco.k00_numpre
           and rRecordDisbanco.k00_numpar = 0
           and ru.k00_dtvenc >= rRecordDisbanco.dtpago
         order by k00_dtvenc
         limit 1;

        if found then
          dDataCalculoRecibo := dDataReciboUnica;
        end if;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - ');
          perform fc_debug('  <PgtoParcial>  ---------------- Validando datas de vencimento ----------------');
          perform fc_debug('  <PgtoParcial>  - Op��es:');
          perform fc_debug('  <PgtoParcial>  - 1 - Pr�ximo dia util do vencimento do arrecad : ' || fc_proximo_dia_util(dDataCalculoRecibo));
          perform fc_debug('  <PgtoParcial>  - 2 - Data do Pagamento Banc�rio                : ' || rRecordDisbanco.dtpago);
          perform fc_debug('  <PgtoParcial>  ---------------------------------------------------------------');
          perform fc_debug('  <PgtoParcial>  - ');
          perform fc_debug('  <PgtoParcial>  - Op��o Default : "1" ');
        end if;

        if rRecordDisbanco.dtpago > fc_proximo_dia_util(dDataCalculoRecibo)  then -- Paguei Depois do Vencimento

          dDataCalculoRecibo := rRecordDisbanco.dtpago;

          if lRaise is true then
            perform fc_debug('  <PgtoParcial>  - Alterando para Op��o de Vencimento "2" ');
          end if;
        end if;

        if lRaise is true then

          perform fc_debug('  <PgtoParcial>');
          perform fc_debug('  <PgtoParcial>  - Rodando FC_RECIBO'    );
          perform fc_debug('  <PgtoParcial>  --- iNumpreRecibo      : ' || iNumpreRecibo      );
          perform fc_debug('  <PgtoParcial>  --- dDataCalculoRecibo : ' || dDataCalculoRecibo );
          perform fc_debug('  <PgtoParcial>  --- iAnoSessao         : ' || iAnoSessao         );
          perform fc_debug('  <PgtoParcial>');
        end if;

        select * from fc_recibo(iNumpreRecibo,dDataCalculoRecibo,dDataCalculoRecibo,iAnoSessao)
          into rRecibo;

        if rRecibo.rlerro is true then
          return '5 - '||rRecibo.rvmensagem||' Erro ao processar idret '||rRecordDisbanco.idret||'.';
        end if;

      else

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Nao encontrou no arrecad - Gerando Recibo para o debito - Numpre: '||rRecordDisbanco.k00_numpre||'  Numpar: '||rRecordDisbanco.k00_numpar,lRaise,false,false);
        end if;

        select distinct
               arrecant.k00_tipo
          into rRecord
          from arrecant
         where arrecant.k00_numpre = rRecordDisbanco.k00_numpre
         union
        select distinct
               arreold.k00_tipo
          from arreold
         where arreold.k00_numpre = rRecordDisbanco.k00_numpre
         limit 1;

        select k00_codbco,
               k00_codage,
               fc_numbco(k00_codbco,k00_codage) as fc_numbco
          into rRecordBanco
          from arretipo
         where k00_tipo = rRecord.k00_tipo;

        insert into db_reciboweb ( k99_numpre,
                                   k99_numpar,
                                   k99_numpre_n,
                                   k99_codbco,
                                   k99_codage,
                                   k99_numbco,
                                   k99_desconto,
                                   k99_tipo,
                                   k99_origem
                                 ) values (
                                   rRecordDisbanco.k00_numpre,
                                   rRecordDisbanco.k00_numpar,
                                   iNumpreRecibo,
                                   coalesce(rRecordBanco.k00_codbco,0),
                                   coalesce(rRecordBanco.k00_codage,'0'),
                                   rRecordBanco.fc_numbco,
                                   0,
                                   2,
                                   1 );

        if lRaise is true then
          perform fc_debug('<PgtoParcial>  - Lan�ou recibo caso sej� carne ',lRaise,false,false);
        end if;

        insert into recibopaga ( k00_numcgm,
         k00_dtoper,
         k00_receit,
         k00_hist,
         k00_valor,
         k00_dtvenc,
         k00_numpre,
         k00_numpar,
         k00_numtot,
         k00_numdig,
         k00_conta,
         k00_dtpaga,
         k00_numnov )
        select k00_numcgm,
               k00_dtoper,
               k00_receit,
               k00_hist,
               k00_valor,
               k00_dtvenc,
               k00_numpre,
               k00_numpar,
               k00_numtot,
               k00_numdig,
               0,
               datausu,
               iNumpreRecibo
          from arrecant
         where arrecant.k00_numpre = rRecordDisbanco.k00_numpre
           and case
                 when rRecordDisbanco.k00_numpar = 0 then true
                   else rRecordDisbanco.k00_numpar = arrecant.k00_numpar
               end
         union
        select k00_numcgm,
               k00_dtoper,
               k00_receit,
               k00_hist,
               k00_valor,
               k00_dtvenc,
               k00_numpre,
               k00_numpar,
               k00_numtot,
               k00_numdig,
               0,
               datausu,
               iNumpreRecibo
          from arreold
         where arreold.k00_numpre = rRecordDisbanco.k00_numpre
           and case
                 when rRecordDisbanco.k00_numpar = 0 then true
                   else rRecordDisbanco.k00_numpar = arreold.k00_numpar
               end ;

      end if;

      if rRecordDisbanco.k00_numpar = 0 then
        insert into tmplista_unica values (rRecordDisbanco.idret);
      end if;

      -- Acerta o conteudo da disbanco, alterando o numpre do carne pelo da recibopaga
      if lRaise then
        perform fc_debug('  <PgtoParcial>  - Acertando numpre do recibo gerado para o carne (arreold ou arrecant) numnov : '||iNumpreRecibo,lRaise,false,false);
      end if;

      if lRaise then
        perform fc_debug('  <PgtoParcial>  - 2 - Alterando numpre disbanco ! novo numpre : '||iNumpreRecibo,lRaise,false,false);
      end if;

      update disbanco
         set k00_numpre = iNumpreRecibo,
             k00_numpar = 0
       where idret = rRecordDisbanco.idret;

    end loop;

    if lRaise then
      perform fc_debug('  <PgtoParcial>  - Final processamento para geracao recibo para carne, '||clock_timestamp(),lRaise,false,false);
    end if;

   /**
     * Inserimos na tmpnaoprocessar os numpres dos idrets onde nao
     * sao encontrada as origens excluindo juro, multa e desconto
     */
    for r_idret in select idret, k00_numpre
                     from disbanco
                    where disbanco.codret = cod_ret
    loop

    perform 1
       from recibopaga
      where recibopaga.k00_numnov = r_idret.k00_numpre
        and recibopaga.k00_hist  not in ( 918, 400, 401 )
        and not
            exists ( select 1
                       from arrecad
                      where arrecad.k00_numpre = recibopaga.k00_numpre
                        and arrecad.k00_numpar = recibopaga.k00_numpar
                        and arrecad.k00_receit = recibopaga.k00_receit
                      union all
                     select 1
                       from arrecant
                      where arrecant.k00_numpre = recibopaga.k00_numpre
                        and arrecant.k00_numpar = recibopaga.k00_numpar
                        and arrecant.k00_receit = recibopaga.k00_receit
                      union all
                     select 1
                       from arreold
                      where arreold.k00_numpre = recibopaga.k00_numpre
                        and arreold.k00_numpar = recibopaga.k00_numpar
                        and arreold.k00_receit = recibopaga.k00_receit
                      union all
                     select 1
                       from arreprescr
                      where arreprescr.k30_numpre = recibopaga.k00_numpre
                        and arreprescr.k30_numpar = recibopaga.k00_numpar
                        and arreprescr.k30_receit = recibopaga.k00_receit
                      limit 1 );

      if found then

        if lRaise then
          perform fc_debug(' <baixabanco> N�O ENCONTRADA ORIGENS, INSERINDO NA tmpnaoprocessar '||r_idret.idret,lRaise,false,false);
        end if;

        insert into tmpnaoprocessar values (r_idret.idret);
      end if;

    end loop;

    /*******************************************************************************************************************
     *  N�O PROCESSA PAGAMENTOS DUPLICADOS EM RECIBOS DIFERENTES
     ******************************************************************************************************************/
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 4 - NAO PROCESSA PAGAMENTOS DUPLICADOS EM RECIBOS DIFERENTES!',lRaise,false,false);
    end if;
    for r_idret in

        select x.k00_numpre,
               x.k00_numpar,
               count(x.idret) as ocorrencias
          from ( select distinct
                        recibopaga.k00_numpre,
                        recibopaga.k00_numpar,
                        disbanco.idret
                   from disbanco
                        inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                  where disbanco.codret = cod_ret
                    and disbanco.classi is false
                    and case when iNumprePagamentoParcial = 0
                             then true
                             else disbanco.k00_numpre > iNumprePagamentoParcial
                         end

                    and disbanco.instit = iInstitSessao ) as x
                left  join numprebloqpag  on numprebloqpag.ar22_numpre = x.k00_numpre
                                         and numprebloqpag.ar22_numpar = x.k00_numpar
         where numprebloqpag.ar22_numpre is null
             and not exists ( select 1
                                from tmpnaoprocessar
                               where tmpnaoprocessar.idret = x.idret )
         group by x.k00_numpre,
                  x.k00_numpar
           having count(x.idret) > 1

    loop

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - ######## 1111 incluido no naoprocesar',lRaise,false,false);
      end if;

      for iRows in 1..( r_idret.ocorrencias - 1 ) loop

          if lRaise then
            perform fc_debug('  <PgtoParcial>  - Inserindo em nao processar - Pagamento duplicado em recibos diferentes',lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - ########  incluido no naprocesar',lRaise,false,false);
          end if;

          -- @todo - verificar esta logica, a principio parece estar inserindo aqui o mesmo recibo
          -- em arquivos (codret) diferentes

          insert into tmpnaoprocessar select coalesce(max(disbanco.idret),0)
                                     from disbanco
                                    where disbanco.codret = cod_ret
                                      and case when iNumprePagamentoParcial = 0
                                               then true
                                               else disbanco.k00_numpre > iNumprePagamentoParcial
                                           end
                                      and disbanco.classi is false
                                      and disbanco.instit = iInstitSessao
                                      and disbanco.k00_numpre in ( select recibopaga.k00_numnov
                                                                     from recibopaga
                                                                    where recibopaga.k00_numpre = r_idret.k00_numpre
                                                                      and recibopaga.k00_numpar = r_idret.k00_numpar )
                                      and not exists ( select 1
                                                         from tmpnaoprocessar
                                                        where tmpnaoprocessar.idret = disbanco.idret );

      end loop;

    end loop;


    /*********************************************************************************************************************
     *  EFETUA AJUSTE NOS RECIBOS QUE TENHAM ALGUMA PARCELA DE SUA ORIGEM, PAGA/CANCELADA/IMPORTADA PARA DIVIDA/PARCELADA
     *********************************************************************************************************************/
    --
    -- Processa somente os recibos que tenham todos debitos em aberto ou todos pagos
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 5 - EFETUA AJUSTE NOS RECIBOS QUE TENHAM ALGUMA PARCELA DE SUA ORIGEM',lRaise,false,false);
      perform fc_debug('  <PgtoParcial>           PAGA/CANCELADA/IMPORTADA PARA DIVIDA/PARCELADA!',lRaise,false,false);
    end if;

    for r_idret in
        select disbanco.idret,
               disbanco.k00_numpre as numpre,
               r.k00_numpre,
               r.k00_numpar,
               (select count(*)
                  from (select distinct
                               recibopaga.k00_numpre,
                               recibopaga.k00_numpar
                          from recibopaga
                               inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                                 and arrecad.k00_numpar = recibopaga.k00_numpar
                         where recibopaga.k00_numnov = disbanco.k00_numpre ) as x
               ) as qtd_aberto,
               (select count(*)
                  from (select distinct
                               k00_numpre,
                               k00_numpar
                          from recibopaga
                          where recibopaga.k00_numnov = disbanco.k00_numpre ) as x
               ) as qtd_recibo,
               exists ( select 1
                          from arrecad a
                         where a.k00_numpre = r.k00_numpre
                           and a.k00_numpar = r.k00_numpar ) as arrecad,
               exists ( select 1
                          from arrecant a
                         where a.k00_numpre = r.k00_numpre
                           and a.k00_numpar = r.k00_numpar ) as arrecant,
               exists ( select 1
                          from arreold a
                         where a.k00_numpre = r.k00_numpre
                           and a.k00_numpar = r.k00_numpar ) as arreold
          from disbanco
               inner join recibopaga r   on r.k00_numnov              = disbanco.k00_numpre
               left  join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                        and numprebloqpag.ar22_numpar = disbanco.k00_numpar
         where disbanco.codret = cod_ret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and numprebloqpag.ar22_numpre is null
           and case when iNumprePagamentoParcial = 0
                    then true
                    else disbanco.k00_numpre > iNumprePagamentoParcial
                end
           and not exists ( select 1
                              from tmpnaoprocessar
                             where tmpnaoprocessar.idret = disbanco.idret )
           order by disbanco.codret,
                  disbanco.idret,
                  disbanco.k00_numpre,
                  r.k00_numpre,
                  r.k00_numpar
    loop

      if lRaise is true then
        perform fc_debug('<PgtoParcial> Processando idret '||r_idret.idret||' Numpre: '||r_idret.numpre||'...',lRaise,false,false);
      end if;

      -- @todo - verificar esta logica com muita calma, acredito nao ser aqui o melhor lugar...
      if ( r_idret.qtd_aberto = r_idret.qtd_recibo ) or r_idret.qtd_aberto = 0 then
        if lRaise is true then
          perform fc_debug('<PgtoParcial> Continuando 1 ( qtd_aberto = qtd_recibo OU qtd_aberto = 0 )...',lRaise,false,false);
        end if;
        continue;
      end if;

      if r_idret.arrecad then
        perform 1 from arrecad where k00_numpre = r_idret.k00_numpre and k00_tipo = 3;
        if found then
          if lRaise is true then
        perform fc_debug('<PgtoParcial> Continuando 2 ( nao encontrou numpre na arrecad )...',lRaise,false,false);
      end if;
          continue;
        end if;
      elsif r_idret.arrecant then
        perform 1 from arrecant where k00_numpre = r_idret.k00_numpre and k00_tipo = 3;
        if found then
          if lRaise is true then
        perform fc_debug('<PgtoParcial> Continuando 3 ( nao encontrou numpre na arrecant )...',lRaise,false,false);
      end if;
          continue;
        end if;
      elsif r_idret.arreold then
        perform 1 from arreold where k00_numpre = r_idret.k00_numpre and k00_tipo = 3;
        if found then
          if lRaise is true then
             perform fc_debug('<PgtoParcial> Continuando 4 ( nao encontrou numpre na arreold )...',lRaise,false,false);
          end if;
          continue;
        end if;
      end if;

      --
      -- Se nao encontrar o numpre e numpar em nenhuma das tabelas : arrecad,arrecant,arreold
      --   insere em tmpnaoprocessar para nao processar do loop principal do processamento
      --
      if r_idret.arrecad is false and r_idret.arrecant is false and r_idret.arreold is false then
        perform 1 from tmpnaoprocessar where idret = r_idret.idret;
        if not found then

          if lRaise is true then
             perform fc_debug('<PgtoParcial> Inserindo idret '||r_idret.idret||' em tmpnaoprocessar...',lRaise,false,false);
          end if;
          insert into tmpnaoprocessar values (r_idret.idret);
        end if;
      elsif r_idret.arrecad is false then
        --
        --  Caso nao encontrar no arrecad deleta o numpre e numpar
        --    da recibopaga para ajustar o recibo, pressupondo que tenha sido pago ou cancelado
        --    uma parcela do recibo. Este ajuste no recibo é necessario para que o sistema encontre
        --    a diferenca entre o valor pago e o valor do recibo, gerando assim um credito com o valor
        --    da diferenca
        --

        if lRaise then
          perform fc_debug('  <PgtoParcial>  - Quantidade em aberto : '||r_idret.qtd_aberto||' Quantidade no recibo : '||r_idret.qtd_recibo                             ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Deletando da recibopaga -- numnov : '||r_idret.numpre||' numpre : '||r_idret.k00_numpre||' numpar : '||r_idret.k00_numpar,lRaise,false,false);
        end if;

        --
        -- Verificamos se o numnov que esta prestes a ser deletado poussui vinculo com alguma partilha
        -- Caso encontrado vinculo, o recibo nao e exclui�do e sera retornado erro no processamento
        --
        perform v77_processoforopartilha
           from processoforopartilhacusta
          where v77_numnov in (select k00_numnov
                                from recibopaga
                               where k00_numnov = r_idret.numpre
                                 and k00_numpre = r_idret.k00_numpre
                                 and k00_numpar = r_idret.k00_numpar);
        if found then
          raise exception   'Erro ao realizar exclusao de recibo da CGF (recibopaga) Numnov: % Numpre: % Numpar: % possuem vinculo com geracao de partilha de custas para processo do foro', r_idret.numpre, r_idret.k00_numpre, r_idret.k00_numpar;
        end if;

        delete from recibopaga
         where k00_numnov = r_idret.numpre
           and k00_numpre = r_idret.k00_numpre
           and k00_numpar = r_idret.k00_numpar;

      end if;

    end loop;

    /*******************************************************************************************************************
     *  GERA RECIBO PARA ISSQN VARIAVEL
     ******************************************************************************************************************/
    if lRaise then
      perform fc_debug('  <PgtoParcial> Regra 6 - GERA RECIBO PARA ISSQN VARIAVEL',lRaise,false,false);
    end if;
    -- Verifica se existe algum  referente a ISSQN Variavel que ja esteja quitado e o valor seja 0 (zero)
    -- Nesse caso sera gerado ARRECAD / ISSVAR / RECIBO para o  encontrado e acertado o numpre na tabela disbanco

    --
    -- Alterado o sql para buscar dados da disbanco de issqn vari�vel que est�o na recibopaga, jah realizava antes da alteracao,
    -- e buscar dados da disbanco de issqn variavel que nao tiveram seu pagamento por recibo, l�gica nova.
    --
    for rRecordDisbanco in select distinct
                                  disbanco.*,
                                  issvar_carne.q05_numpre as issvar_carne_numpre,
                                  issvar_carne.q05_numpar as issvar_carne_numpar
                             from disbanco
                                  left join recibopaga                        on recibopaga.k00_numnov            = disbanco.k00_numpre
                                  left join arrecant                          on arrecant.k00_numpre              = recibopaga.k00_numpre
                                                                             and arrecant.k00_numpar              = recibopaga.k00_numpar
                                                                             and arrecant.k00_receit              = recibopaga.k00_receit
                                  left join issvar as issvar_recibo           on issvar_recibo.q05_numpre         = arrecant.k00_numpre
                                                                             and issvar_recibo.q05_numpar         = arrecant.k00_numpar
                                  left join issvar as issvar_carne            on issvar_carne.q05_numpre          = disbanco.k00_numpre
                                                                             and issvar_carne.q05_numpar          = disbanco.k00_numpar
                                  left join arrecant as arrecant_issvar_carne on arrecant_issvar_carne.k00_numpre = disbanco.k00_numpre
                                                                             and arrecant_issvar_carne.k00_numpar = disbanco.k00_numpar
                            where disbanco.classi is false
                              and disbanco.codret = cod_ret
                              and disbanco.instit = iInstitSessao
                              and ( issvar_recibo.q05_numpre is not null or ( issvar_carne.q05_numpre is not null and arrecant_issvar_carne.k00_numpre is not null) )
                              and case when iNumprePagamentoParcial = 0
                                       then true
                                       else disbanco.k00_numpre > iNumprePagamentoParcial
                                   end
                              and not exists ( select 1
                                                 from tmpnaoprocessar
                                                where tmpnaoprocessar.idret = disbanco.idret )
                         order by disbanco.idret
    loop

      if lRaise is true then
          perform fc_debug('  <PgtoParcial> ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> PROCESSANDO IDRET '||rRecordDisbanco.idret||'...',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>                                                 ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Gerando recibos                                 ',lRaise,false,false);
      end if;

      --
      -- Alterado o sql para atender aos casos em que foi pago um issqn variavel por carn� ao inv�s de recibo
      --
      select distinct
             case
               when recibopaga.k00_numnov is not null and round(sum(recibopaga.k00_valor),2) > 0.00 then
                 round(sum(recibopaga.k00_valor),2)
               else
                 vlrpago
             end
        into nVlrTotalRecibopaga
        from disbanco
             left join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
       where disbanco.idret  = rRecordDisbanco.idret
         and disbanco.instit = iInstitSessao
       group by recibopaga.k00_numnov, disbanco.vlrpago ;

      if lRaise is true then

        perform fc_debug('  <PgtoParcial> Numpre Disbanco .........: '||rRecordDisbanco.k00_numpre                                                            ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial> Numpre IssVar ...........: '||rRecordDisbanco.issvar_carne_numpre||' Parcela: '||rRecordDisbanco.issvar_carne_numpar,lRaise,false,false);
        perform fc_debug('  <PgtoParcial> Valor Pago na Disbanco (recibopaga) ..: '||nVlrTotalRecibopaga                                                                   ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial> ',lRaise,false,false);
      end if;

      for rRecord in select distinct tipo,
                                        k00_numpre,
                                        k00_numpar,
                                        case
                                          when k00_valor = 0 then rRecordDisbanco.vlrpago
                                          else k00_valor
                                        end as k00_valor
                       from ( select distinct
                                     1 as tipo,
                                     recibopaga.k00_numpre,
                                     recibopaga.k00_numpar,
                                     round(sum(recibopaga.k00_valor),2) as k00_valor
                                from recibopaga
                                     inner join arrecant  c on c.k00_numpre = recibopaga.k00_numpre
                                                          and c.k00_numpar  = recibopaga.k00_numpar
                               where recibopaga.k00_numnov = rRecordDisbanco.k00_numpre
                               group by recibopaga.k00_numpre,
                                        recibopaga.k00_numpar
                               union
                              select 2 as tipo,
                                     rRecordDisbanco.issvar_carne_numpre as k00_numpre,
                                     rRecordDisbanco.issvar_carne_numpar as k00_numpar,
                                     rRecordDisbanco.vlrpago             as k00_valor
                               where rRecordDisbanco.issvar_carne_numpre is not null
                             ) as dados
                      order by k00_numpre, k00_numpar

      loop

        if lRaise is true then

          perform fc_debug('  <PgtoParcial> '                                                                                                          ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Calculando valor informado...'                                                                             ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor pago na Disbanco ...:'||rRecordDisbanco.vlrpago                                                      ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor do debito ..........:'||rRecord.k00_valor                                                            ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor do debito encontrado na tabela '||(case when rRecord.tipo = 1 then 'Recibopaga' else 'Disbanco' end ),lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor pago na disbanco ...:'||nVlrTotalRecibopaga                                                          ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Calculo ..................: ( Valor pago na Disbanco * ((( Valor do debito * 100 ) / Valor pago na disbanco ) / 100 ))',lRaise,false,false);
          perform fc_debug('  <PgtoParcial> Valor Informado ..........: ( '||coalesce(rRecordDisbanco.vlrpago,0)||' * ((( '||coalesce(rRecord.k00_valor,0)||' * 100 ) / '||coalesce(nVlrTotalRecibopaga,0)||' ) / 100 )) = '||( coalesce(rRecordDisbanco.vlrpago,0) * ((( coalesce(rRecord.k00_valor,0) * 100 ) / coalesce(nVlrTotalRecibopaga,0) ) / 100 )) ,lRaise,false,false);
        end if;

        nVlrInformado := ( rRecordDisbanco.vlrpago * ((( rRecord.k00_valor * 100 ) / nVlrTotalRecibopaga ) / 100 ));

        --if rRecord.k00_numpre != iNumpreAnterior then

          -- Gera Numpre do ISSQN Variavel
          select nextval('numpref_k03_numpre_seq')
            into iNumpreIssVar;

          -- Gera Numpre do Recibo
          select nextval('numpref_k03_numpre_seq')
            into iNumpreRecibo;

          iNumpreAnterior    := rRecord.k00_numpre;
          nVlrTotalInformado := 0;

          insert into arreinscr select distinct
                                       iNumpreIssVar,
                                       arreinscr.k00_inscr,
                                       arreinscr.k00_perc
                                  from arreinscr
                                 where arreinscr.k00_numpre = rRecord.k00_numpre;
        --end if;

        --
        -- Apenas excluimos o recibo quando o pagamento for por recibo (tipo = 1)
        --
        if rRecord.tipo = 1 then

          delete
            from recibopaga
           where k00_numnov = rRecordDisbanco.k00_numpre
             and k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar;
        end if;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial> Incluindo registros do Numpre '||rRecord.k00_numpre||' Parcela '||rRecord.k00_numpar||' na tabela arrecad como iss complementar com o novo numpre '||iNumpreIssVar,lRaise,false,false);
        end if;

        /*
         * Alterada a l�gica para inclus�o no arrecad.
         *
         * Ao inv�s de utilizar a data de opera��o e vencimento original do d�bito, esta sendo utilizada a data de processamento da baixa de banco
         * Isto devido a gera��o de corre��o, juro e multa indevidos para o d�bito pois esses valores ja est�o embutidos no valor total pago na disbanco.
         *
         */
        insert into arrecad ( k00_numpre,
                              k00_numpar,
                              k00_numcgm,
                              k00_dtoper,
                              k00_receit,
                              k00_hist,
                              k00_valor,
                              k00_dtvenc,
                              k00_numtot,
                              k00_numdig,
                              k00_tipo,
                              k00_tipojm
                            ) select iNumpreIssVar,
                                 arrecant.k00_numpar,
                                 arrecant.k00_numcgm,
                                 datausu,
                                 arrecant.k00_receit,
                                 arrecant.k00_hist,
                                 ( case
                                     when rRecord.tipo = 1
                                       then 0
                                     else rRecordDisbanco.vlrpago
                                   end ),
                                 datausu,
                                 1,
                                 arrecant.k00_numdig,
                                 arrecant.k00_tipo,
                                 arrecant.k00_tipojm
                                from arrecant
                               where arrecant.k00_numpre = rRecord.k00_numpre
                                 and arrecant.k00_numpar = rRecord.k00_numpar;

        insert into issvar ( q05_codigo,
                             q05_numpre,
                             q05_numpar,
                             q05_valor,
                             q05_ano,
                             q05_mes,
                             q05_histor,
                             q05_aliq,
                             q05_bruto,
                             q05_vlrinf
                           ) select nextval('issvar_q05_codigo_seq'),
                                iNumpreIssVar,
                                issvar.q05_numpar,
                                issvar.q05_valor,
                                issvar.q05_ano,
                                issvar.q05_mes,
                                'ISSQN Complementar gerado automaticamente atraves da baixa de banco devido a quitacao ',
                                issvar.q05_aliq,
                                issvar.q05_bruto,
                                nVlrInformado
                              from issvar
                             where q05_numpre = rRecord.k00_numpre
                               and q05_numpar = rRecord.k00_numpar;


        select k00_codbco,
               k00_codage,
               fc_numbco(k00_codbco,k00_codage) as fc_numbco
          into rRecordBanco
          from arretipo
         where k00_tipo = ( select k00_tipo
                              from arrecant
                             where arrecant.k00_numpre = rRecord.k00_numpre
                               and arrecant.k00_numpar = rRecord.k00_numpar
                             limit 1 );


        insert into db_reciboweb ( k99_numpre,
                                   k99_numpar,
                                   k99_numpre_n,
                                   k99_codbco,
                                   k99_codage,
                                   k99_numbco,
                                   k99_desconto,
                                   k99_tipo,
                                   k99_origem
                                 ) values (
                                   iNumpreIssVar,
                                   rRecord.k00_numpar,
                                   iNumpreRecibo,
                                   coalesce(rRecordBanco.k00_codbco,0),
                                   coalesce(rRecordBanco.k00_codage,'0'),
                                   rRecordBanco.fc_numbco,
                                   0,
                                   2,
                                   1
                                 );

         if lRaise is true then
           perform fc_debug('  <PgtoParcial>  - xxx - valor informado : '||nVlrInformado||' total : '||nVlrTotalInformado,lRaise,false,false);
         end if;

         nVlrTotalInformado := ( nVlrTotalInformado + nVlrInformado );

      end loop;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - 1 - valor antes disbanco : '||nVlrTotalInformado,lRaise,false,false);
      end if;

      if rRecordDisbanco.vlrpago != round(nVlrTotalInformado,2) then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Valor Pago na Disbanco diferente do Valor Total Informado... ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Valor Pago na Disbanco ....: '||rRecordDisbanco.vlrpago,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Valor Total Informado......: '||round(nVlrTotalInformado,2),lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Alterando o valor informado da issvar ajustando com a diferenca encontrada ('||(rRecordDisbanco.vlrpago - round(nVlrTotalInformado,2))||')',lRaise,false,false);
        end if;

        update issvar
           set q05_vlrinf = q05_vlrinf + (rRecordDisbanco.vlrpago - round(nVlrTotalInformado,2))
         where q05_codigo = ( select max(q05_codigo)
                                from issvar
                               where q05_numpre = iNumpreIssVar );
      end if;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - 2 - valor antes disbanco : '||nVlrTotalInformado,lRaise,false,false);
      end if;

      -- Gera Recibopaga
      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Gerando ReciboPaga',lRaise,false,false);
      end if;

      select * from fc_recibo(iNumpreRecibo,rRecordDisbanco.dtpago,rRecordDisbanco.dtpago,iAnoSessao)
        into rRecibo;

      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Fim do Processamento da ReciboPaga',lRaise,false,false);
      end if;

      if rRecibo.rlerro is true then
        return ' 6 - '||rRecibo.rvmensagem;
      end if;

      -- Acerta o conteudo da disbanco, alterando o numpre do ISSQN quitado pelo da recibopaga
      if lRaise then
        perform fc_debug('  <PgtoParcial>  - 3 - Alterando numpre disbanco ! novo numpre : '||iNumpreRecibo,lRaise,false,false);
      end if;

      update disbanco
         set vlrpago = round((vlrpago - nVlrTotalInformado),2),
             vlrtot  = round((vlrtot  - nVlrTotalInformado),2)
       where idret   = rRecordDisbanco.idret;

       /**
        * Comentando update da tmpantesprocessar pois gerava inconsistencia quando o debito
        * foi pago em duplicidade
        *
       update tmpantesprocessar
         set vlrpago = round((vlrpago - nVlrTotalInformado),2)
       where idret   = rRecordDisbanco.idret;*/

       perform * from recibopaga
         where k00_numnov = rRecordDisbanco.k00_numpre;

       if not found then

         update disbanco
            set k00_numpre = iNumpreRecibo,
                k00_numpar = 0,
                vlrpago    = round(nVlrTotalInformado,2),
                vlrtot     = round(nVlrTotalInformado,2)
          where idret      = rRecordDisbanco.idret;

          /*update tmpantesprocessar
             set vlrpago    = round(nVlrTotalInformado,2)
           where idret      = rRecordDisbanco.idret;*/

       else

         iSeqIdRet := nextval('disbanco_idret_seq');

         if lRaise is true then
           perform fc_debug('  <PgtoParcial>  - idret update : '||rRecordDisbanco.idret||' novo idret : '||iSeqIdRet||' valor antes disbanco : '||nVlrTotalInformado,lRaise,false,false);
         end if;

          insert into disbanco ( k00_numbco,
                                k15_codbco,
                                k15_codage,
                                codret,
                                dtarq,
                                dtpago,
                                vlrpago,
                                vlrjuros,
                                vlrmulta,
                                vlracres,
                                vlrdesco,
                                vlrtot,
                                cedente,
                                vlrcalc,
                                idret,
                                classi,
                                k00_numpre,
                                k00_numpar,
                                convenio,
                                instit )
                        select k00_numbco,
                               k15_codbco,
                               k15_codage,
                               codret,
                               dtarq,
                               dtpago,
                               round(nVlrTotalInformado,2),
                               0,
                               0,
                               0,
                               0,
                               round(nVlrTotalInformado,2),
                               cedente,
                               round(nVlrTotalInformado,2),
                               iSeqIdRet,
                               classi,
                               iNumpreRecibo,
                               0,
                               convenio,
                              instit
                         from disbanco
                        where disbanco.idret = rRecordDisbanco.idret;
           end if;

         if lRaise is true then
           perform fc_debug('  <PgtoParcial>  ',lRaise,false,false);
           perform fc_debug('  <PgtoParcial>  FIM DO PROCESSAMENTO DO IDRET '||rRecordDisbanco.idret,lRaise,false,false);
           perform fc_debug('  <PgtoParcial>  ',lRaise,false,false);
         end if;

    end loop;

    /*******************************************************************************************************************
     *  GERA ABATIMENTOS
     ******************************************************************************************************************/
    --
    -- Verifica se existe abatimentos sendo eles ( PAGAMENTO PARCIAL, CREDITO E DESCONTO )
    --

    if lRaise is true then
      perform fc_debug('  <PgtoParcial> Regra 7 - GERA ABATIMENTO ', lRaise,false,false);
    end if;

    for r_idret in

        select distinct
               disbanco.k00_numpre as numpre,
               disbanco.k00_numpar as numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               disbanco.dtpago,
               round(sum(recibopaga.k00_valor),2) as k00_valor,
               recibopaga.k00_dtpaga,
               disbanco.instit
          from disbanco
               inner join recibopaga     on disbanco.k00_numpre       = recibopaga.k00_numnov
               left  join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                        and numprebloqpag.ar22_numpar = disbanco.k00_numpar
         where disbanco.codret = cod_ret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and numprebloqpag.ar22_numpre is null
           and case when iNumprePagamentoParcial = 0
                    then true
                    else disbanco.k00_numpre > iNumprePagamentoParcial
                end
           and not exists ( select 1
                              from tmpnaoprocessar
                             where tmpnaoprocessar.idret = disbanco.idret )
           and exists ( select 1
                          from arrecad
                         where arrecad.k00_numpre = recibopaga.k00_numpre
                           and arrecad.k00_numpar = recibopaga.k00_numpar
                         union all
                        select 1
                          from arrecant
                         where arrecant.k00_numpre = recibopaga.k00_numpre
                           and arrecant.k00_numpar = recibopaga.k00_numpar
                         union all
                        select 1
                          from arreold
                         where arreold.k00_numpre = recibopaga.k00_numpre
                           and arreold.k00_numpar = recibopaga.k00_numpar
                         union all
                        select 1
                          from arreprescr
                         where arreprescr.k30_numpre = recibopaga.k00_numpre
                           and arreprescr.k30_numpar = recibopaga.k00_numpar
                          limit 1 )
      group by disbanco.k00_numpre,
               disbanco.k00_numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               disbanco.dtpago,
               disbanco.instit,
               recibopaga.k00_dtpaga
      order by disbanco.idret

    loop

      if lRaise is true then

        perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'=')                                  ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - IDRET : '||r_idret.idret                             ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'=')                                  ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '                                                    ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Numpre RECIBOPAGA : '||r_idret.numpre                ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Valor Pago        : '||r_idret.vlrpago::numeric(15,2),lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '                                                    ,lRaise,false,false);

      end if;

      --
      -- se o recibo estiver valido buscamos o valor calculado do recibo
      --
      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - Data recibopaga : '||r_idret.k00_dtpaga||' data pago banco : '||r_idret.dtpago,lRaise,false,false);
      end if;

      --
      -- Verificamos se o recibo que esta sendo pago tem algum pagamento parcial
      --   caso tenha pgto parcial recalcula a origem do debito
      --
      perform *
         from recibopaga r
              inner join arreckey           k    on k.k00_numpre       = r.k00_numpre
                                                and k.k00_numpar       = r.k00_numpar
                                                and k.k00_receit       = r.k00_receit
              inner join abatimentoarreckey ak   on ak.k128_arreckey   = k.k00_sequencial
              inner join abatimentodisbanco ab   on ab.k132_abatimento = ak.k128_abatimento
        where k00_numnov    = r_idret.numpre;

      if found then
        lReciboPossuiPgtoParcial := true;
      else

        lReciboPossuiPgtoParcial := false;
        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  ------------------------------------------'            ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Nao Encontrou Pagamento Parcial Anterior'            ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Numpre: '||r_idret.numpre||', IDRet: '||r_idret.idret,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  ------------------------------------------'            ,lRaise,false,false);
        end if;
      end if;

      /**
       * Validamos se o recibo foi gerado por regra, pois caso tenha
       * sido n�o deve recalcular a origem do d�bito
       * --Se for diferente de 0 n�o pode recalcular
       **/
      if lReciboPossuiPgtoParcial is true then

        perform *
         from recibopaga r
              inner join arreckey           k    on k.k00_numpre       = r.k00_numpre
                                                 and k.k00_numpar       = r.k00_numpar
                                                 and k.k00_receit       = r.k00_receit
              inner join abatimentoarreckey ak   on ak.k128_arreckey   = k.k00_sequencial
              inner join abatimentodisbanco ab   on ab.k132_abatimento = ak.k128_abatimento
              inner join db_reciboweb       dw   on r.k00_numnov       = dw.k99_numpre_n
        where k00_numnov   = r_idret.numpre
          and k99_desconto <> 0;

        if found then
          lReciboPossuiPgtoParcial := false;
        end if;

      end if;

      if lRaise then
        perform fc_debug('  <PgtoParcial>  - numpre : '||r_idret.numpre||' data para pagamento : '||fc_proximo_dia_util(r_idret.k00_dtpaga)||' data que foi pago : '||r_idret.dtpago||' encontrou outro abatimento : '||lReciboPossuiPgtoParcial,lRaise,false,false);
      end if;

      if fc_proximo_dia_util(r_idret.k00_dtpaga) >= r_idret.dtpago and lReciboPossuiPgtoParcial is false then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Calculado 1 ',lRaise,false,false);
        end if;

        select round(sum(k00_valor),2) as valor_total_recibo
          into nVlrCalculado
          from recibopaga
               inner join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov
         where recibopaga.k00_numnov = r_idret.numpre
           and disbanco.idret        = r_idret.idret
           and exists ( select 1
                          from arrecad
                         where arrecad.k00_numpre = recibopaga.k00_numpre
                           and arrecad.k00_numpar = recibopaga.k00_numpar
                         limit 1 );

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Valor calculado para recibo pago dentro do vencimento (recibopaga) : '||nVlrCalculado,lRaise,false,false);
        end if;

      else

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Calculado 2 ',lRaise,false,false);
        end if;

        select coalesce(round(sum(utotal),2),0)::numeric(15,2)
          into nVlrCalculado
          from ( select ( substr(fc_calcula,15,13)::float8 +
                          substr(fc_calcula,28,13)::float8 +
                          substr(fc_calcula,41,13)::float8 -
                          substr(fc_calcula,54,13)::float8 ) as utotal
                   from ( select fc_calcula( x.k00_numpre,
                                             x.k00_numpar,
                                             0,
                                             x.dtpago,
                                             x.dtpago,
                                             extract(year from x.dtpago)::integer)
                                        from ( select distinct
                                                      recibopaga.k00_numpre,
                                                      recibopaga.k00_numpar,
                                                      dtpago
                                                 from recibopaga
                                                      inner join disbanco    on disbanco.k00_numpre     = recibopaga.k00_numnov
                                                      inner join arrecad     on arrecad.k00_numpre      = recibopaga.k00_numpre
                                                                            and arrecad.k00_numpar      = recibopaga.k00_numpar
                                                where recibopaga.k00_numnov = r_idret.numpre
                                                  and disbanco.idret        = r_idret.idret ) as x
                        ) as y
                ) as z;

      end if;

      if nVlrCalculado is null then
        nVlrCalculado := 0;
      end if;

      perform 1
         from recibopaga
              inner join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov
              inner join arrecad  on arrecad.k00_numpre  = recibopaga.k00_numpre
                                 and arrecad.k00_numpar  = recibopaga.k00_numpar
              inner join issvar   on issvar.q05_numpre   = recibopaga.k00_numpre
                                 and issvar.q05_numpar   = recibopaga.k00_numpar
        where recibopaga.k00_numnov = r_idret.numpre
          and arrecad.k00_valor     = 0;

      if found then

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - **** ISSQN Variavel **** ',lRaise,false,false);
        end if;

        nVlrCalculado := r_idret.vlrpago;

      end if;


      if nVlrCalculado < 0 then
        return '7 - Debito com valor negativo - Numpre : '||r_idret.numpre;
      end if;


      nVlrPgto          := ( r_idret.vlrpago )::numeric(15,2);
      nVlrDiferencaPgto := ( nVlrCalculado - nVlrPgto )::numeric(15,2);

      if lRaise is true then

        perform fc_debug('  <PgtoParcial>  - Calculado ................: '||nVlrCalculado            ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Diferenca ................: '||nVlrDiferencaPgto        ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Tolerancia Pgto Parcial ..: '||nVlrToleranciaPgtoParcial,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - Tolerancia Credito .......: '||nVlrToleranciaCredito    ,lRaise,false,false);
        perform fc_debug('  <PgtoParcial>  - '                                                       ,lRaise,false,false);

      end if;

      -- Caso o Pagamento Parcial esteja ativado entao a verificado se o valor pago e igual ao total do
      -- e caso nao seja, tambem e verificado se a diferenca do pagamento e menor que a tolenrancia para pagamento
      if lRaise is true then
        perform fc_debug('  <PgtoParcial>  - nVlrDiferencaPgto: '||nVlrDiferencaPgto||', nVlrDiferencaPgto: '||nVlrDiferencaPgto||',  nVlrToleranciaPgtoParcial: '||nVlrToleranciaPgtoParcial,lRaise,false,false);
      end if;

      if nVlrDiferencaPgto > 0 and nVlrDiferencaPgto > nVlrToleranciaPgtoParcial then

        -- Percentual pago do debito
        nPercPgto          := (( nVlrPgto * 100 ) / nVlrCalculado )::numeric;

        -- Insere Abatimento
        select nextval('abatimento_k125_sequencial_seq')
          into iAbatimento;

        if lRaise is true then

          perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-'),lRaise,false,false);
          perform fc_debug('  PAGAMENTO PARCIAL : '||iAbatimento,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-'),lRaise,false,false);

        end if;

        insert into abatimento ( k125_sequencial,
                                 k125_tipoabatimento,
                                 k125_datalanc,
                                 k125_hora,
                                 k125_usuario,
                                 k125_instit,
                                 k125_valor,
                                 k125_perc
                               ) values (
                                 iAbatimento,
                                 1,
                                 datausu,
                                 to_char(current_timestamp,'HH24:MI'),
                                 cast(fc_getsession('DB_id_usuario') as integer),
                                 iInstitSessao,
                                 nVlrPgto,
                                 nPercPgto
                               );

        insert into abatimentodisbanco ( k132_sequencial,
                     k132_abatimento,
                     k132_idret
                     ) values (
                      nextval('abatimentodisbanco_k132_sequencial_seq'),
                      iAbatimento,
                      r_idret.idret
                    );


        -- Gera um Recibo Avulso
        select nextval('numpref_k03_numpre_seq')
          into iNumpreReciboAvulso;

        insert into abatimentorecibo ( k127_sequencial,
                                       k127_abatimento,
                                       k127_numprerecibo,
                                       k127_numpreoriginal
                                     ) values (
                                       nextval('abatimentorecibo_k127_sequencial_seq'),
                                       iAbatimento,
                                       iNumpreReciboAvulso,
                                       coalesce( (select k00_numpre
                                                    from tmpdisbanco_inicio_original
                                                   where idret = r_idret.idret), iNumpreReciboAvulso)
                                     );


        -- Geracao de Recibo Avulso por Receita e Pagamento;

        select distinct round(sum(recibopaga.k00_valor),2)
          into nVlrTotalRecibopaga
          from disbanco
               inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
         where disbanco.idret  = r_idret.idret
           and disbanco.instit = iInstitSessao;


        for rRecord in select distinct
                              recibopaga.k00_numcgm     as k00_numcgm,
                              recibopaga.k00_receit     as k00_receit,
                              round(sum(recibopaga.k00_valor),2) as k00_valor
                         from disbanco
                              inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                        where disbanco.idret  = r_idret.idret
                          and disbanco.instit = iInstitSessao
                     group by recibopaga.k00_receit,
                              recibopaga.k00_numcgm
        loop

          select k00_tipo
            into iTipoDebitoPgtoParcial
            from ( select ( select arrecad.k00_tipo
                              from arrecad
                             where arrecad.k00_numpre = recibopaga.k00_numpre
                               and arrecad.k00_numpar = recibopaga.k00_numpar

                             union

                            select arrecant.k00_tipo
                              from arrecant
                             where arrecant.k00_numpre = recibopaga.k00_numpre
                               and arrecant.k00_numpar = recibopaga.k00_numpar
                                limit 1
                          ) as k00_tipo
                     from disbanco
                          inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                    where disbanco.idret  = r_idret.idret
                      and disbanco.instit = iInstitSessao
                 ) as x;


          nPercReceita := ( (rRecord.k00_valor * 100) / nVlrTotalRecibopaga )::numeric(20,10);
          nVlrReceita  := trunc(( nVlrPgto * ( nPercReceita / 100 ))::numeric(15,2),2);

          if lRaise is true then
            perform fc_debug('  <PgtoParcial>  - <PgtoParcial> - Gerando recibo por receita e pagamento ',lRaise,false,false);
          end if;

          insert into recibo ( k00_numcgm,
                               k00_dtoper,
                               k00_receit,
                               k00_hist,
                               k00_valor,
                               k00_dtvenc,
                               k00_numpre,
                               k00_numpar,
                               k00_numtot,
                               k00_numdig,
                               k00_tipo,
                               k00_tipojm,
                               k00_codsubrec,
                               k00_numnov
                             ) values (
                               rRecord.k00_numcgm,
                               datausu,
                               rRecord.k00_receit,
                               504,
                               nVlrReceita,
                               datausu,
                               iNumpreReciboAvulso,
                               1,
                               1,
                               0,
                               iTipoDebitoPgtoParcial,
                               0,
                               0,
                               0
                             );


          insert into arrehist ( k00_numpre,
                                 k00_numpar,
                                 k00_hist,
                                 k00_dtoper,
                                 k00_hora,
                                 k00_id_usuario,
                                 k00_histtxt,
                                 k00_limithist,
                                 k00_idhist
                               ) values (
                                 iNumpreReciboAvulso,
                                 1,
                                 504,
                                 datausu,
                                 '00:00',
                                 1,
                                 'Recibo avulso referente pagamento parcial do recibo da CGF - numnov: ' || r_idret.numpre || ' idret: ' || r_idret.idret,
                                 null,
                                 nextval('arrehist_k00_idhist_seq')
                               );

          perform *
             from arrenumcgm
            where k00_numpre = iNumpreReciboAvulso
              and k00_numcgm = rRecord.k00_numcgm;

          if not found then

            insert into arrenumcgm ( k00_numcgm, k00_numpre ) values ( rRecord.k00_numcgm, iNumpreReciboAvulso );

          end if;
        end loop;


        -- Acerta as origens do Recibo Avulso de acordo os Numpres da recibopaga informado

        select array_to_string(array_accum(iNumpreReciboAvulso || '_' || arrematric.k00_matric || '_' || arrematric.k00_perc), ',')
          into sSql
          from recibopaga
               inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
         where recibopaga.k00_numnov = r_idret.numpre;

        insert into arrematric select distinct
                                      iNumpreReciboAvulso,
                                      arrematric.k00_matric,
                                      -- colocado 100 % fixo porque o numpre do recibo avulso gerado se trata de pagamento parcial
                                      -- e nao vai ter divisao de percentual entre mais de um numpre da mesma matricula
                                      100 as k00_perc
                                 from recibopaga
                                      inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
                                where recibopaga.k00_numnov = r_idret.numpre;


        insert into arreinscr  select distinct
                                      iNumpreReciboAvulso,
                                      arreinscr.k00_inscr,
                                      -- colocado 100 % fixo porque o numpre do recibo avulso gerado se trata de pagamento parcial
                                      -- e nao vai ter divisao de percentual entre mais de um numpre da mesma inscricao
                                      100 as k00_perc
                                 from recibopaga
                                      inner join arreinscr on arreinscr.k00_numpre = recibopaga.k00_numpre
                                where recibopaga.k00_numnov = r_idret.numpre;



        -- Percorre todos os debitos a serem abatidos

        for rRecord in select distinct
                              arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              arrecad.k00_hist,
                              arrecad.k00_receit,
                              arrecad.k00_tipo
                         from recibopaga
                              inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                                and arrecad.k00_numpar = recibopaga.k00_numpar
                                                and arrecad.k00_receit = recibopaga.k00_receit
                        where recibopaga.k00_numnov = r_idret.numpre
                     order by arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              arrecad.k00_receit
        loop

          select arreckey.k00_sequencial,
                 arrecadcompos.k00_sequencial
            into iArreckey,
                 iArrecadCompos
            from arreckey
                 left join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = rRecord.k00_hist;

          --
          -- Alteracao realizada conforme solicitacao da tarefa 75450 solicitada pela Catia Renata
          --   quanto tiver um recibo com desconto manual e for realizado um pagamento parcial o sistema
          --   utiliza como valor calculado o valor liquido (valor com o desconto manual 918)
          --   e deixa o desconto perdido no arrecad, abatimentoarreckey, arreckey sendo que o mesmo ja foi utilizado
          --   para resolver, deletamos o registro de historico 918 do arrecad.
          --

          delete
            from arrecad
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = 918;

          delete
            from abatimentoarreckey
           using arreckey
           where k00_sequencial = k128_arreckey
             and k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = 918;

          delete
            from arreckey
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = 918;

          if iArreckey is null then

            select nextval('arreckey_k00_sequencial_seq')
              into iArreckey;

            insert into arreckey ( k00_sequencial,
                                   k00_numpre,
                                   k00_numpar,
                                   k00_receit,
                                   k00_hist,
                                   k00_tipo
                                 ) values (
                                   iArreckey,
                                   rRecord.k00_numpre,
                                   rRecord.k00_numpar,
                                   rRecord.k00_receit,
                                   rRecord.k00_hist,
                                   rRecord.k00_tipo
                                 );

          end if;

          -- Insere ligacao do abatimento com o debito

          select nextval('abatimentoarreckey_k128_sequencial_seq')
            into iAbatimentoArreckey;

          insert into abatimentoarreckey ( k128_sequencial,
                                           k128_arreckey,
                                           k128_abatimento,
                                           k128_valorabatido,
                                           k128_correcao,
                                           k128_juros,
                                           k128_multa
                                         ) values (
                                           iAbatimentoArreckey,
                                           iArreckey,
                                           iAbatimento,
                                           0,
                                           0,
                                           0,
                                           0
                                         );

          if iArrecadCompos is not null then

            insert into abatimentoarreckeyarrecadcompos ( k129_sequencial,
                                                          k129_abatimentoarreckey,
                                                          k129_arrecadcompos,
                                                          k129_vlrhist,
                                                          k129_correcao,
                                                          k129_juros,
                                                          k129_multa
                                                        ) values (
                                                          nextval('abatimentoarreckeyarrecadcompos_k129_sequencial_seq'),
                                                          iAbatimentoArreckey,
                                                          iArrecadCompos,
                                                          0,
                                                          0,
                                                          0,
                                                          0
                                                        );
          end if;

        end loop;

        -- Consulta valor total historico do debito
        select round(sum(x.k00_valor),2) as k00_valor
          into nVlrTotalHistorico
          from ( select distinct arrecad.*
                   from recibopaga
                      inner join arrecad  on arrecad.k00_numpre = recibopaga.k00_numpre
                                       and arrecad.k00_numpar = recibopaga.k00_numpar
                                       and arrecad.k00_receit = recibopaga.k00_receit
                where recibopaga.k00_numnov = r_idret.numpre ) as x;

        if lRaise is true then

          perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Total Historico   : '||nVlrTotalHistorico,lRaise,false,false);

        end if;

        -- Valor a ser abatido do arrecad e igual ao percentual do pagamento sobre o total historico
        nVlrAbatido := trunc(( nVlrTotalHistorico * ( nPercPgto / 100 ))::numeric(15,2),2);

        nVlrTotalJuroMultaCorr := nVlrPgto - nVlrAbatido;

        -- Dilui o valor abatido do arrecad ate zerar o nVlrAbatido encontrado
        while round(nVlrAbatido,2) > 0 loop

          nPercPgto := (( nVlrAbatido * 100 ) / nVlrTotalHistorico )::numeric;

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'.')              ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Valor Abatido   : '||nVlrAbatido ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Perc  Pagamento : '||nPercPgto   ,lRaise,false,false);

          end if;

          for rRecord in select *,
                                case
                                  when k00_hist = 918 then 0
                                  else ( substr(fc_calcula,15,13)::float8 - substr(fc_calcula, 2,13)::float8 )::float8
                                end as vlrcorrecao,
                                case when k00_hist = 918 then 0::float8 else substr(fc_calcula,28,13)::float8 end as vlrjuros,
                                case when k00_hist = 918 then 0::float8 else substr(fc_calcula,41,13)::float8 end as vlrmulta
                           from ( select abatimentoarreckey.k128_sequencial,
                                         abatimentoarreckeyarrecadcompos.k129_sequencial,
                                         arrecad.*,
                                         arrecadcompos.*,
                                         fc_calcula( arrecad.k00_numpre,
                                                     arrecad.k00_numpar,
                                                     arrecad.k00_receit,
                                                     r_idret.dtpago,
                                                     r_idret.dtpago,
                                                     extract( year from r_idret.dtpago )::integer )
                                    from abatimentoarreckey
                                         inner join arreckey      on arreckey.k00_sequencial    = abatimentoarreckey.k128_arreckey
                                         left  join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
                                         left  join abatimentoarreckeyarrecadcompos on k129_abatimentoarreckey = abatimentoarreckey.k128_sequencial
                                         inner join arrecad       on arrecad.k00_numpre         = arreckey.k00_numpre
                                                                 and arrecad.k00_numpar         = arreckey.k00_numpar
                                                                 and arrecad.k00_receit         = arreckey.k00_receit
                                                                 and arrecad.k00_hist           = arreckey.k00_hist
                                   where abatimentoarreckey.k128_abatimento = iAbatimento
                                order by arrecad.k00_numpre asc,
                                         arrecad.k00_numpar asc,
                                         arrecad.k00_valor  desc
                                ) as x


          loop

            -- Caso tenha sido zerado a variavel nVlrAbatido entao sai do loop

            if nVlrAbatido <= 0 then

              exit;

            end if;

            nVlrPgtoParcela := trunc((rRecord.k00_valor * ( nPercPgto / 100 ))::numeric(20,10),2);

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - Valor Pagamento da Parcela: '||nVlrPgtoParcela,lRaise,false,false);
              perform fc_debug('  <PgtoParcial>  - lInsereJurMulCorr: '||lInsereJurMulCorr,lRaise,false,false);
            end if;

            if lInsereJurMulCorr then

              nVlrJuros         := trunc((rRecord.vlrjuros     * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrMulta         := trunc((rRecord.vlrmulta     * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrCorrecao      := trunc((rRecord.vlrcorrecao  * ( nPercPgto / 100 ))::numeric(20,10),2);

              nVlrHistCompos    := trunc((rRecord.k00_vlrhist  * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrJurosCompos   := trunc((rRecord.k00_juros    * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrMultaCompos   := trunc((rRecord.k00_multa    * ( nPercPgto / 100 ))::numeric(20,10),2);
              nVlrCorreCompos   := trunc((rRecord.k00_correcao * ( nPercPgto / 100 ))::numeric(20,10),2);

              if lRaise is true then
                perform fc_debug('  <PgtoParcial>  - nPercPgto:          : '||nPercPgto           ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.vlrjuros    : '||rRecord.vlrjuros    ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.vlrmulta    : '||rRecord.vlrmulta    ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.vlrcorrecao : '||rRecord.vlrcorrecao ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>'                                                ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_vlrhist : '||rRecord.k00_vlrhist ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_juros   : '||rRecord.k00_juros   ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_multa   : '||rRecord.k00_multa   ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - rRecord.k00_correcao: '||rRecord.k00_correcao,lRaise,false,false);

                perform fc_debug('  <PgtoParcial>  -'                                             ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  -'                                             ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrJuros      : '||nVlrJuros                ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrMulta      : '||nVlrMulta                ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrCorrecao   : '||nVlrCorrecao             ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - '                                            ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrHistCompos : '||nVlrHistCompos           ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrJurosCompos: '||nVlrJurosCompos          ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrMultaCompos: '||nVlrMultaCompos          ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - nVlrCorreCompos: '||nVlrCorreCompos          ,lRaise,false,false);
              end if;

            else

              nVlrJuros         := 0;
              nVlrMulta         := 0;
              nVlrCorrecao      := 0;

              nVlrHistCompos    := 0;
              nVlrJurosCompos   := 0;
              nVlrMultaCompos   := 0;
              nVlrCorreCompos   := 0;

            end if;
            if lRaise is true then

              perform fc_debug('  <PgtoParcial>  -    Numpre: '||lpad(rRecord.k00_numpre,10,'0')||' Numpar: '||lpad(rRecord.k00_numpar, 3,'0')||' Receita: '||rRecord.k00_receit||' Valor Parcela: '||rRecord.k00_valor::numeric(15,2)||' Corr: '||nVlrCorrecao::numeric(15,2)||' Juros: '||nVlrJuros::numeric(15,2)||' Multa: '||nVlrMulta::numeric(15,2)||' Valor Pago: '||nVlrPgtoParcela::numeric(15,2)||' Resto: '||nVlrAbatido::numeric(15,2),lRaise,false,false);

            end if;

            -- Nao deixa retornar o valor zerado

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - nVlrPgtoParcela: '||nVlrPgtoParcela||' rRecord.k00_hist: '||rRecord.k00_hist,lRaise,false,false);
            end if;

            if round(nVlrPgtoParcela,2) <= 0 and rRecord.k00_hist != 918 then

              if lRaise is true then

                perform fc_debug('  <PgtoParcial>  -    * Valor Parcela Menor que 0,01 - Corrige para 0,01 ',lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);

              end if;

              nVlrPgtoParcela := 0.01;

            end if;


            update abatimentoarreckey
               set k128_valorabatido = ( k128_valorabatido + nVlrPgtoParcela )::numeric(15,2),
                   k128_correcao     = ( k128_correcao     + nVlrCorrecao    )::numeric(15,2),
                   k128_juros        = ( k128_juros        + nVlrJuros       )::numeric(15,2),
                   k128_multa        = ( k128_multa        + nVlrMulta       )::numeric(15,2)
             where k128_sequencial   = rRecord.k128_sequencial;


            if rRecord.k129_sequencial is not null then

              update abatimentoarreckeyarrecadcompos
                 set k129_vlrhist      = ( k129_vlrhist  + nVlrHistCompos  )::numeric(15,2),
                     k129_correcao     = ( k129_correcao + nVlrCorreCompos )::numeric(15,2),
                     k129_juros        = ( k129_juros    + nVlrJurosCompos )::numeric(15,2),
                     k129_multa        = ( k129_multa    + nVlrMultaCompos )::numeric(15,2)
               where k129_sequencial   = rRecord.k129_sequencial;

            end if;


            nVlrAbatido := trunc(( nVlrAbatido - nVlrPgtoParcela )::numeric(20,10),2)::numeric(15,2);

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - nVlrAbatido: '||nVlrAbatido,lRaise,false,false);
            end if;

          end loop;

          if lRaise is true then
            perform fc_debug('  <PgtoParcial>  - lInsereJurMulCorr = False',lRaise,false,false);
          end if;

          lInsereJurMulCorr := false;

        end loop;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - iAbatimento: '||iAbatimento,lRaise,false,false);
        end if;

        select round(sum(abatimentoarreckey.k128_correcao) +
                     sum(abatimentoarreckey.k128_juros)    +
                     sum(abatimentoarreckey.k128_multa),2) as totaljuromultacorr
          into rRecord
          from abatimentoarreckey
         where abatimentoarreckey.k128_abatimento = iAbatimento;


        if lRaise is true then

          perform fc_debug('  <PgtoParcial>  - '                                                          ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Total - Juros/ Multa / Corr : '||rRecord.totaljuromultacorr,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - Total - Geral               : '||nVlrTotalJuroMultaCorr    ,lRaise,false,false);
          perform fc_debug('  <PgtoParcial>  - '                                                          ,lRaise,false,false);

        end if;


        if rRecord.totaljuromultacorr <> round(nVlrTotalJuroMultaCorr,2) then

          update abatimentoarreckey
             set k128_correcao = ( k128_correcao + ( nVlrTotalJuroMultaCorr - round(rRecord.totaljuromultacorr,2) ))::numeric(15,2)
           where k128_sequencial in ( select max(k128_sequencial)
                                         from abatimentoarreckey
                                        where k128_abatimento = iAbatimento );
        end if;

        for rRecord in select distinct
                              arrecad.*,
                              abatimentoarreckey.k128_valorabatido,
                              arrecadcompos.k00_sequencial                              as arrecadcompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_vlrhist ,0) as histcompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_correcao,0) as correcompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_juros   ,0) as juroscompos,
                              coalesce(abatimentoarreckeyarrecadcompos.k129_multa   ,0) as multacompos
                         from abatimentoarreckey
                              inner join arreckey                        on arreckey.k00_sequencial    = abatimentoarreckey.k128_arreckey
                              inner join arrecad                         on arrecad.k00_numpre         = arreckey.k00_numpre
                                                                        and arrecad.k00_numpar         = arreckey.k00_numpar
                                                                        and arrecad.k00_receit         = arreckey.k00_receit
                                                                        and arrecad.k00_hist           = arreckey.k00_hist
                              left  join arrecadcompos                   on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
                              left  join abatimentoarreckeyarrecadcompos on abatimentoarreckeyarrecadcompos.k129_abatimentoarreckey = abatimentoarreckey.k128_sequencial
                        where abatimentoarreckey.k128_abatimento = iAbatimento
                     order by arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              arrecad.k00_receit

        loop


          -- Caso o valor abata todo valor devido entao e quitado a tabela

          if round((rRecord.k00_valor - rRecord.k128_valorabatido),2) = 0 then

            insert into arrecantpgtoparcial ( k00_numpre,
                                              k00_numpar,
                                              k00_numcgm,
                                              k00_dtoper,
                                              k00_receit,
                                              k00_hist,
                                              k00_valor,
                                              k00_dtvenc,
                                              k00_numtot,
                                              k00_numdig,
                                              k00_tipo,
                                              k00_tipojm,
                                              k00_abatimento
                                            ) values (
                                              rRecord.k00_numpre,
                                              rRecord.k00_numpar,
                                              rRecord.k00_numcgm,
                                              rRecord.k00_dtoper,
                                              rRecord.k00_receit,
                                              rRecord.k00_hist,
                                              rRecord.k00_valor,
                                              rRecord.k00_dtvenc,
                                              rRecord.k00_numtot,
                                              rRecord.k00_numdig,
                                              rRecord.k00_tipo,
                                              rRecord.k00_tipojm,
                                              iAbatimento
                                            );
            delete
              from arrecad
             where k00_numpre = rRecord.k00_numpre
               and k00_numpar = rRecord.k00_numpar
               and k00_receit = rRecord.k00_receit
               and k00_hist   = rRecord.k00_hist;

          else

            update arrecad
             set k00_valor  = ( k00_valor - rRecord.k128_valorabatido )
           where k00_numpre = rRecord.k00_numpre
             and k00_numpar = rRecord.k00_numpar
             and k00_receit = rRecord.k00_receit
             and k00_hist   = rRecord.k00_hist;

          end if;


          if rRecord.arrecadcompos is not null then

            update arrecadcompos
               set k00_vlrhist    = ( k00_vlrhist  - rRecord.histcompos  ),
                   k00_correcao   = ( k00_correcao - rRecord.correcompos ),
                   k00_juros      = ( k00_juros    - rRecord.juroscompos ),
                   k00_multa      = ( k00_multa    - rRecord.multacompos )
             where k00_sequencial = rRecord.arrecadcompos;

          end if;

        end loop;

        -- Acerta NUMPRE da disbanco
        if lRaise then
          perform fc_debug('  <PgtoParcial>  - 4 - Alterando numpre disbanco ! novo numpre : '||iNumpreReciboAvulso,lRaise,false,false);
        end if;

        update disbanco
           set k00_numpre = iNumpreReciboAvulso,
               k00_numpar = 0
         where idret      = r_idret.idret;


      --
      -- FIM PGTO PARCIAL
      --
      -- INICIO CREDITO/DESCONTO
      -- validacao da tolerancia do credito
      -- se o valor da diferenca for menor que 0 (significa que � um credito)
      -- e se o valor absoluto da diferenca for maior que o valor da tolerancia para credito sera gerado o credito
      --
      --
      elsif nVlrDiferencaPgto != 0 and ( nVlrDiferencaPgto > 0 or ( nVlrDiferencaPgto < 0 and abs(nVlrDiferencaPgto) > nVlrToleranciaCredito) ) then


        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - nVlrDiferencaPgto: '||nVlrDiferencaPgto||' - nVlrToleranciaCredito: '||nVlrToleranciaCredito, lRaise, false, false);
        end if;

        select nextval('abatimento_k125_sequencial_seq')
          into iAbatimento;


        if nVlrDiferencaPgto > 0 then

          iTipoAbatimento   = 2;

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - DESCONTO : '||iAbatimento,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);

          end if;

        else

          iTipoAbatimento   = 3;
          nVlrDiferencaPgto := ( nVlrDiferencaPgto * -1 );

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - CREDITO : '||iAbatimento ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'-')      ,lRaise,false,false);

          end if;

        end if;


        nPercPgto := (( nVlrDiferencaPgto * 100 ) / r_idret.k00_valor )::numeric;

        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - Lancando Abatimento. nPercPgto: '||nPercPgto,lRaise,false,false);
        end if;

        insert into abatimento ( k125_sequencial,
                                 k125_tipoabatimento,
                                 k125_datalanc,
                                 k125_hora,
                                 k125_usuario,
                                 k125_instit,
                                 k125_valor,
                                 k125_perc,
                                 k125_valordisponivel
                               ) values (
                                 iAbatimento,
                                 iTipoAbatimento,
                                 datausu,
                                 to_char(current_timestamp,'HH24:MI'),
                                 cast(fc_getsession('DB_id_usuario') as integer),
                                 iInstitSessao,
                                 nVlrDiferencaPgto,
                                 nPercPgto,
                                 nVlrDiferencaPgto
                               );

        insert into abatimentodisbanco ( k132_sequencial,
                                         k132_abatimento,
                                         k132_idret
                                       ) values (
                                         nextval('abatimentodisbanco_k132_sequencial_seq'),
                                         iAbatimento,
                                         r_idret.idret
                                       );
        if lRaise is true then
          perform fc_debug('  <PgtoParcial>  - TipoAbatimento: '||iTipoAbatimento,lRaise,false,false);
        end if;

          if iTipoAbatimento = 3 then


          -- Gera um Recibo Avulso

          select nextval('numpref_k03_numpre_seq')
            into iNumpreReciboAvulso;

          if lRaise is true then
            perform fc_debug('  <PgtoParcial> -  ## Gerando recibo avulso. NumpreReciboAvulso: '||iNumpreReciboAvulso,lRaise,false,false);
          end if;

          insert into abatimentorecibo ( k127_sequencial,
                                         k127_abatimento,
                                         k127_numprerecibo,
                                         k127_numpreoriginal
                                       ) values (
                                         nextval('abatimentorecibo_k127_sequencial_seq'),
                                         iAbatimento,
                                         iNumpreReciboAvulso,
                                         coalesce( (select k00_numpre
                                                      from tmpdisbanco_inicio_original
                                                     where idret = r_idret.idret ), iNumpreReciboAvulso)
                                       );

          for rRecord in select k00_numcgm,
                                k00_tipo,
                                round(sum(k00_valor),2) as k00_valor
                           from ( select recibopaga.k00_numcgm,
                                         ( select arrecad.k00_tipo
                                             from arrecad
                                            where arrecad.k00_numpre = recibopaga.k00_numpre
                                              and arrecad.k00_numpar = recibopaga.k00_numpar
                                            union all
                                           select arrecant.k00_tipo
                                             from arrecant
                                            where arrecant.k00_numpre = recibopaga.k00_numpre
                                              and arrecant.k00_numpar = recibopaga.k00_numpar
                                            union all
                                           select arreold.k00_tipo
                                             from arreold
                                            where arreold.k00_numpre = recibopaga.k00_numpre
                                              and arreold.k00_numpar = recibopaga.k00_numpar
                                    union all
                                 select 1
                                   from arreprescr
                                  where arreprescr.k30_numpre = recibopaga.k00_numpre
                                    and arreprescr.k30_numpar = recibopaga.k00_numpar
                                         limit 1 ) as k00_tipo,
                                         recibopaga.k00_valor
                                    from disbanco
                                         inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
                                   where disbanco.idret  = r_idret.idret
                                     and disbanco.instit = iInstitSessao
                                ) as x
                       group by k00_numcgm,
                                k00_tipo
           loop

             nVlrReceita := ( rRecord.k00_valor * ( nPercPgto / 100 ) )::numeric(15,2);

             select k00_receitacredito
               into iReceitaCredito
               from arretipo
              where k00_tipo = rRecord.k00_tipo;

              if lRaise is true then
                perform fc_debug('  <PgtoParcial>  - iReceitaCredito: '||iReceitaCredito,lRaise,false,false);
              end if;

             if iReceitaCredito is null then
               return '8 - Receita de Credito nao configurado para o tipo : '||rRecord.k00_tipo;
             end if;

             if lRaise is true then
               perform fc_debug('  <PgtoParcial>  - ## lancando o recibo ref ao credito. ReceitaCredito: '||rRecord.k00_tipo||' ValorReceita: '||nVlrReceita,lRaise,false,false);
             end if;

             insert into recibo ( k00_numcgm,
                                  k00_dtoper,
                                  k00_receit,
                                  k00_hist,
                                  k00_valor,
                                  k00_dtvenc,
                                  k00_numpre,
                                  k00_numpar,
                                  k00_numtot,
                                  k00_numdig,
                                  k00_tipo,
                                  k00_tipojm,
                                  k00_codsubrec,
                                  k00_numnov
                                ) values (
                                  rRecord.k00_numcgm,
                                  datausu,
                                  iReceitaCredito,
                                  505,
                                  nVlrReceita,
                                  datausu,
                                  iNumpreReciboAvulso,
                                  1,
                                  1,
                                  0,
                                  iTipoReciboAvulso,
                                  0,
                                  0,
                                  0
                                );

             insert into arrehist ( k00_numpre,
                                    k00_numpar,
                                    k00_hist,
                                    k00_dtoper,
                                    k00_hora,
                                    k00_id_usuario,
                                    k00_histtxt,
                                    k00_limithist,
                                    k00_idhist
                                  ) values (
                                    iNumpreReciboAvulso,
                                    1,
                                    505,
                                    datausu,
                                    '00:00',
                                    1,
                                    'Recibo avulso referente ao credito do recibo da CGF - numnov: ' || r_idret.numpre || 'idret: ' || r_idret.idret,
                                    null,
                                    nextval('arrehist_k00_idhist_seq')
                                  );

             perform *
                from arrenumcgm
               where k00_numpre = iNumpreReciboAvulso
                 and k00_numcgm = rRecord.k00_numcgm;

             if not found then
               perform fc_debug('  <PgtoParcial>  - inserindo registro do recibo na arrenumcgm',lRaise,false,false);
               insert into arrenumcgm ( k00_numcgm, k00_numpre ) values ( rRecord.k00_numcgm, iNumpreReciboAvulso );

             end if;

           end loop;

           if lRaise is true then
             perform fc_debug('  <PgtoParcial>  - Inserindo na Arrematric [3]:'||iNumpreReciboAvulso,lRaise,false,false);
           end if;

           select array_to_string(array_accum(distinct iNumpreReciboAvulso || '-' || arrematric.k00_matric || '-' || arrematric.k00_perc),' , ')
             into sDebug
             from recibopaga
                  inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
            where recibopaga.k00_numnov = r_idret.numpre;

            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  - '||sDebug,lRaise,false,false);
            end if;

           insert into arrematric select distinct
                                         iNumpreReciboAvulso,
                                         arrematric.k00_matric,
                                         arrematric.k00_perc
                                    from recibopaga
                                         inner join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre
                                   where recibopaga.k00_numnov = r_idret.numpre;

           insert into arreinscr  select distinct
                                         iNumpreReciboAvulso,
                                         arreinscr.k00_inscr,
                                         arreinscr.k00_perc
                                    from recibopaga
                                         inner join arreinscr on arreinscr.k00_numpre = recibopaga.k00_numpre
                                   where recibopaga.k00_numnov = r_idret.numpre;

          if nVlrCalculado = 0 then

            if lRaise then
              perform fc_debug('  <PgtoParcial>  - 5 - Alterando numpre disbanco ! novo numpre : '||iNumpreReciboAvulso,lRaise,false,false);
            end if;

            update disbanco
               set k00_numpre = iNumpreReciboAvulso,
                   k00_numpar = 0
             where idret      = r_idret.idret;

          else

            if lRaise is true or true then
              perform fc_debug('  <PgtoParcial>  - Insere Disbanco',lRaise,false,false);
            end if;

            select nextval('disbanco_idret_seq')
              into iSeqIdRet;

            insert into disbanco (k00_numbco,
                                  k15_codbco,
                                  k15_codage,
                                  codret,
                                  dtarq,
                                  dtpago,
                                  vlrpago,
                                  vlrjuros,
                                  vlrmulta,
                                  vlracres,
                                  vlrdesco,
                                  vlrtot,
                                  cedente,
                                  vlrcalc,
                                  idret,
                                  classi,
                                  k00_numpre,
                                  k00_numpar,
                                  convenio,
                                  instit )
                           select k00_numbco,
                                  k15_codbco,
                                  k15_codage,
                                  codret,
                                  dtarq,
                                  dtpago,
                                  round(nVlrDiferencaPgto,2),
                                  0,
                                  0,
                                  0,
                                  0,
                                  round(nVlrDiferencaPgto,2),
                                  cedente,
                                  round(vlrcalc,2),
                                  iSeqIdRet,
                                  classi,
                                  iNumpreReciboAvulso,
                                  0,
                                  convenio,
                                 instit
                            from disbanco
                           where disbanco.idret = r_idret.idret;


            insert into tmpantesprocessar ( idret,
                                         vlrpago,
                                         v01_seq
                                       ) values (
                                         iSeqIdRet,
                                         nVlrDiferencaPgto,
                                         ( select nextval('w_divold_seq') )
                                       );

            update disbanco
               set vlrpago  = round(( vlrpago - nVlrDiferencaPgto ),2),
                   vlrtot   = round(( vlrtot  - nVlrDiferencaPgto ),2)
             where idret    = r_idret.idret;

            update tmpantesprocessar
               set vlrpago = round( vlrpago - nVlrDiferencaPgto,2 )
             where idret   = r_idret.idret;

          end if;

        end if;

        while nVlrDiferencaPgto > 0 loop

          nPercDesconto := (( nVlrDiferencaPgto * 100 ) / r_idret.k00_valor )::numeric;

          if lRaise is true then

            perform fc_debug('  <PgtoParcial>  - '||lpad('',100,'.')               ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Percentual : '||nPercDesconto     ,lRaise,false,false);
            perform fc_debug('  <PgtoParcial>  - Diferenca  : '||nVlrDiferencaPgto ,lRaise,false,false);

          end if;

          perform 1
             from recibopaga
            where recibopaga.k00_numnov = r_idret.numpre
              and recibopaga.k00_hist  != 918
              and exists ( select 1
                             from arrecad
                            where arrecad.k00_numpre = recibopaga.k00_numpre
                              and arrecad.k00_numpar = recibopaga.k00_numpar
                              and arrecad.k00_receit = recibopaga.k00_receit
                            union all
                           select 1
                             from arrecant
                            where arrecant.k00_numpre = recibopaga.k00_numpre
                              and arrecant.k00_numpar = recibopaga.k00_numpar
                              and arrecant.k00_receit = recibopaga.k00_receit
                            union all
                           select 1
                             from arreold
                            where arreold.k00_numpre = recibopaga.k00_numpre
                              and arreold.k00_numpar = recibopaga.k00_numpar
                              and arreold.k00_receit = recibopaga.k00_receit
                            union all
                           select 1
                             from arreprescr
                            where arreprescr.k30_numpre = recibopaga.k00_numpre
                              and arreprescr.k30_numpar = recibopaga.k00_numpar
                              and arreprescr.k30_receit = recibopaga.k00_receit
                            limit 1 );

          if not found then
            return '9 - Recibo '||r_idret.numpre||' inconsistente. IDRET : '||r_idret.idret;
          end if;

          for rRecord in select distinct
                                recibopaga.k00_numpre,
                                recibopaga.k00_numpar,
                                recibopaga.k00_receit,
                                recibopaga.k00_hist,
                                recibopaga.k00_numcgm,
                                recibopaga.k00_numtot,
                                recibopaga.k00_numdig,
                                ( select arrecad.k00_tipo
                                    from arrecad
                                   where arrecad.k00_numpre  = recibopaga.k00_numpre
                                     and arrecad.k00_numpar  = recibopaga.k00_numpar
                                   union all
                                  select arrecant.k00_tipo
                                    from arrecant
                                   where arrecant.k00_numpre = recibopaga.k00_numpre
                                     and arrecant.k00_numpar = recibopaga.k00_numpar
                                   union all
                                  select arreold.k00_tipo
                                    from arreold
                                   where arreold.k00_numpre = recibopaga.k00_numpre
                                     and arreold.k00_numpar = recibopaga.k00_numpar
                                   union all
                                  select 1
                                    from arreprescr
                                   where arreprescr.k30_numpre = recibopaga.k00_numpre
                                     and arreprescr.k30_numpar = recibopaga.k00_numpar
                                   limit 1 ) as k00_tipo,
                                round(sum(recibopaga.k00_valor),2) as k00_valor
                           from recibopaga
                          where recibopaga.k00_numnov = r_idret.numpre
                            and recibopaga.k00_hist  != 918
                            and exists ( select 1
                                           from arrecad
                                          where arrecad.k00_numpre = recibopaga.k00_numpre
                                            and arrecad.k00_numpar = recibopaga.k00_numpar
                                            and arrecad.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arrecant
                                          where arrecant.k00_numpre = recibopaga.k00_numpre
                                            and arrecant.k00_numpar = recibopaga.k00_numpar
                                            and arrecant.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arreold
                                          where arreold.k00_numpre = recibopaga.k00_numpre
                                            and arreold.k00_numpar = recibopaga.k00_numpar
                                            and arreold.k00_receit = recibopaga.k00_receit
                                          union all
                                         select 1
                                           from arreprescr
                                          where arreprescr.k30_numpre = recibopaga.k00_numpre
                                            and arreprescr.k30_numpar = recibopaga.k00_numpar
                                            and arreprescr.k30_receit = recibopaga.k00_receit
                                          limit 1 )
                       group by recibopaga.k00_numpre,
                                recibopaga.k00_numpar,
                                recibopaga.k00_receit,
                                recibopaga.k00_hist,
                                recibopaga.k00_numcgm,
                                recibopaga.k00_numtot,
                                recibopaga.k00_numdig
          loop

            if nVlrDiferencaPgto <= 0 then

              if lRaise is true then

                perform fc_debug('  <PgtoParcial>  - '     ,lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - SAIDA',lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - '     ,lRaise,false,false);

              end if;

              exit;

            end if;

            nVlrPgtoParcela := trunc((rRecord.k00_valor * ( nPercDesconto / 100 ))::numeric,2);


            if lRaise is true then
              perform fc_debug('  <PgtoParcial>  -   Numpre: '||lpad(rRecord.k00_numpre,10,'0')||' Numpar: '||lpad(rRecord.k00_numpar, 3,'0')||' Receita: '||lpad(rRecord.k00_receit,10,'0')||' Valor Parcela: '||rRecord.k00_valor::numeric(15,2)||' Valor Pago: '||nVlrPgtoParcela::numeric(15,2)||' Resto: '||nVlrDiferencaPgto::numeric(15,2),lRaise,false,false);
            end if;


            if nVlrPgtoParcela <= 0 then

              if lRaise is true then
                perform fc_debug('  <PgtoParcial>  -   * Valor Parcela Menor que 0,01 - Corrige para 0,01 ',lRaise,false,false);
                perform fc_debug('  <PgtoParcial>  - ',lRaise,false,false);
              end if;

              nVlrPgtoParcela := 0.01;

            end if;


            select k00_sequencial
              into iArreckey
              from arrecadacao.arreckey
             where k00_numpre = rRecord.k00_numpre
               and k00_numpar = rRecord.k00_numpar
               and k00_receit = rRecord.k00_receit
               and k00_hist   = rRecord.k00_hist;


            if not found then

              select nextval('arreckey_k00_sequencial_seq')
                into iArreckey;

              insert into arreckey ( k00_sequencial,
                                     k00_numpre,
                                     k00_numpar,
                                     k00_receit,
                                     k00_hist,
                                     k00_tipo
                                   ) values (
                                     iArreckey,
                                     rRecord.k00_numpre,
                                     rRecord.k00_numpar,
                                     rRecord.k00_receit,
                                     rRecord.k00_hist,
                                     rRecord.k00_tipo
                                   );
            end if;


            select k128_sequencial
              into iAbatimentoArreckey
              from abatimentoarreckey
                   inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey
             where abatimentoarreckey.k128_abatimento = iAbatimento
               and arreckey.k00_numpre = rRecord.k00_numpre
               and arreckey.k00_numpar = rRecord.k00_numpar
               and arreckey.k00_receit = rRecord.k00_receit
               and arreckey.k00_hist   = rRecord.k00_hist;

            if found then

              update abatimentoarreckey
                 set k128_valorabatido = ( k128_valorabatido + nVlrPgtoParcela )::numeric(15,2)
               where k128_sequencial   = iAbatimentoArreckey;

            else

              -- Insere ligacao do abatimento com o

              insert into abatimentoarreckey ( k128_sequencial,
                                               k128_arreckey,
                                               k128_abatimento,
                                               k128_valorabatido,
                                             k128_correcao,
                                             k128_juros,
                                             k128_multa
                                             ) values (
                                               nextval('abatimentoarreckey_k128_sequencial_seq'),
                                               iArreckey,
                                               iAbatimento,
                                               nVlrPgtoParcela,
                                               0,
                                               0,
                                               0
                                             );
            end if;

            nVlrDiferencaPgto := round(nVlrDiferencaPgto - nVlrPgtoParcela,2);

          end loop;

        end loop;

      end if; -- fim credito/desconto

    end loop;

    if lRaise is true then
      perform fc_debug('  <PgtoParcial>  -  FIM ABATIMENTO ',lRaise,false,false);
    end if;

  end if;

  /**
   * Fim do Pagamento Parcial
   */

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - processando numpres duplos com pagamento em cota unica e parcelado no mesmo arquivo...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.instit,
             disbanco.k00_numpre,
             disbanco.k00_numpar,
             coalesce((select count(*)
                         from recibopaga
                        where recibopaga.k00_numnov = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as quant_recibopaga,
             coalesce((select count(*)
                         from arrecad
                        where arrecad.k00_numpre = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as quant_arrecad_unica,
             coalesce((select max(k00_numtot)
                         from arrecad
                        where arrecad.k00_numpre = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as arrecad_unica_numtot,
             coalesce((select count(distinct k00_numpar)
                         from arrecad
                        where arrecad.k00_numpre = disbanco.k00_numpre
                          and disbanco.k00_numpar = 0),0) as arrecad_unica_quant_numpar,
             coalesce((select max(d2.idret)
                         from disbanco d2
                        where d2.k00_numpre = disbanco.k00_numpre
                          and d2.codret = disbanco.codret
                          and d2.idret <> disbanco.idret
                          and classi is false),0) as idret_mesmo_numpre
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
    order by idret
  loop

    -- idret_mesmo_numpre
    -- busca se tem algum numpre duplo no mesmo arquivo (significa que o contribuinte pagou no mesmo dia e banco e consequentemente no mesmo arquivo
    -- o numpre numpre 2 ou mais vezes

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - numpre: '||r_codret.k00_numpre||' - parcela: '||r_codret.k00_numpar||' - quant_recibopaga: '||r_codret.quant_recibopaga||' - quant_arrecad_unica: '||r_codret.quant_arrecad_unica||' - arrecad_unica_numtot: '||r_codret.arrecad_unica_numtot||' - arrecad_unica_quant_numpar: '||r_codret.arrecad_unica_quant_numpar,lRaise,false,false);
    end if;

    -- alteracao 1
    -- o sistema tem que descobrir nos casos de pagamento da unica e parcelado, qual o idret na unica de maior percentual (pois pode ter pago 2 unicas)
    -- e nao inserir na tabela "tmpnaoprocessar" o idret desse registro

    if r_codret.k00_numpar = 0 and r_codret.quant_arrecad_unica > 0 then

      if r_codret.arrecad_unica_quant_numpar <> r_codret.arrecad_unica_numtot then
        -- se for unica e a quantidade de parcelas em aberto for diferente da quantidade total de parcelas, significa que o contribuinte pagou como unica
        -- mas ja tem parcelas em aberto, e dessa forma o sistema nao vai processar esse registro para alguem verificar o que realmente vai ser feito,
        -- pois o contribuinte pagou o valor da unica mas nao tem mais todas as parcelas que formaram a unica em aberto

        if cCliente != 'ALEGRETE' then
          insert into tmpnaoprocessar values (r_codret.idret);

          if lRaise is true then
           perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (1): '||r_codret.idret,lRaise,false,false);
          end if;
        end if;

      else

        for r_testa in
          select idret,
                 k00_numpre,
                 k00_numpar
            from disbanco
           where disbanco.k00_numpre =  r_codret.k00_numpre
             and disbanco.codret     =  r_codret.codret
             and disbanco.idret      <> r_codret.idret
             and classi              is false
        loop

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - idret: '||r_testa.idret||' - numpar: '||r_testa.k00_numpar,lRaise,false,false);
          end if;

          -- busca a parcela unica de menor valor (maior percentual de desconto) paga por esse numpre
          select idret
          into iIdRetProcessar
          from disbanco
          where disbanco.k00_numpre =  r_codret.k00_numpre
                and disbanco.k00_numpar = 0
                and disbanco.codret     =  r_codret.codret
                and classi is false
          order by vlrpago limit 1;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - idret: '||r_testa.idret||' - iIdRetProcessar: '||iIdRetProcessar,lRaise,false,false);
          end if;

          -- senao for o registro da unica de maior percentual nao processa
          if iIdRetProcessar != r_testa.idret then

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (2): '||r_testa.idret,lRaise,false,false);
            end if;

            insert into tmpnaoprocessar values (r_testa.idret);

          else

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - NAO inserindo em tmpnaoprocessar (2): '||r_testa.idret,lRaise,false,false);
            end if;

          end if;

      end loop;

        select count(distinct disbanco2.idret)
          into v_contador
          from disbanco
               inner join recibopaga          on disbanco.k00_numpre  =  recibopaga.k00_numpre
                                             and disbanco.k00_numpar  =  0
               inner join disbanco disbanco2  on disbanco2.k00_numpre =  recibopaga.k00_numnov
                                             and disbanco2.k00_numpar =  0
                                             and disbanco2.codret     =  cod_ret
                                             and disbanco2.classi     is false
                                             and disbanco2.instit     =  iInstitSessao
                                             and disbanco2.idret      <> r_codret.idret
         where disbanco.codret = cod_ret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and disbanco.idret  = r_codret.idret;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - v_contador: '||v_contador,lRaise,false,false);
        end if;

        if v_contador = 1 then
          select distinct
                 disbanco2.idret
            into iIdret
            from disbanco
                 inner join recibopaga         on disbanco.k00_numpre  = recibopaga.k00_numpre
                                              and disbanco.k00_numpar  = 0
                 inner join disbanco disbanco2 on disbanco2.k00_numpre = recibopaga.k00_numnov
                                              and disbanco2.k00_numpar = 0
                                              and disbanco2.codret     = cod_ret
                                              and disbanco2.classi     is false
                                              and disbanco2.instit     = iInstitSessao
                                              and disbanco2.idret      <> r_codret.idret
           where disbanco.codret = cod_ret
             and disbanco.classi is false
             and disbanco.instit = iInstitSessao
             and disbanco.idret  = r_codret.idret;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - inserindo em nao processar (3) - idret1: '||iIdret||' - idret2: '||r_codret.idret,lRaise,false,false);
          end if;

  --        insert into tmpnaoprocessar values (r_codret.idret);
          insert into tmpnaoprocessar values (iIdret);

        elsif v_contador >= 1 then
          return '21 - IDRET ' || r_codret.idret || ' COM MAIS DE UM PAGAMENTO NO MESMO ARQUIVO. CONTATE SUPORTE PARA VERIFICA��ES!';
        end if;

      end if;

    end if;



    -- Validamos o numpre para ver se n�o está duplicado em algum lugar
    -- arrecad(k00_numpre) = recibopaga(k00_numnov)
    -- arrecad(k00_numpre) = recibo(k00_numnov)
    -- caso esteja n�o processa o numpre caindo em inconsistencia
    if exists ( select 1 from arrecad where arrecad.k00_numpre   = r_codret.k00_numpre limit 1)
          and ( exists ( select 1 from recibopaga where recibopaga.k00_numnov = r_codret.k00_numpre limit 1) or
                exists ( select 1 from recibo     where recibo.k00_numnov     = r_codret.k00_numpre limit 1) ) then
       if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (5): '||r_codret.idret,lRaise,false,false);
       end if;
       insert into tmpnaoprocessar values (r_codret.idret);
    end if;

    -- Validacao numpre na ISSVAR com numpar = 0 na DISBANCO para nao processar
    -- porem se o numpre estiver na db_reciboweb (k99_numpre_n) e na issvar (q05_numpre)
    -- significa que esse debito eh oriundo de uma integracao externa. Ex: Gissonline
    if r_codret.k00_numpar = 0
      and exists (select 1 from issvar where q05_numpre = r_codret.k00_numpre)
      and not exists (select 1 from db_reciboweb where k99_numpre_n = r_codret.k00_numpre) then
      if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo em tmpnaoprocessar (6): '||r_codret.idret,lRaise,false,false);
      end if;
      insert into tmpnaoprocessar values (r_codret.idret);
    end if;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    end if;

  end loop;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - inicio separando recibopaga...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  -- acertando recibos (recibopaga) com registros que foram importados divida e outros que nao foram importados, e estava gerando erro, entao a logica abaixo
  -- separa em dois recibos novos os casos
  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.instit,
             disbanco.k00_numpre,
             disbanco.k00_numpar,
             disbanco.vlrpago::numeric(15,2),
             (select round(sum(k00_valor),2)
                from recibopaga
               where k00_numnov = disbanco.k00_numpre) as recibopaga_sum_valor
       from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and k00_numpar = 0
         and exists (select 1 from recibopaga inner join divold on k00_numpre = k10_numpre and k00_numpar = k10_numpar where k00_numnov = disbanco.k00_numpre)
         and (select count(*) from recibopaga where k00_numnov = disbanco.k00_numpre) > 0
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by idret
  loop

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - vlrpago: '||r_codret.vlrpago||' - numpre: '||r_codret.k00_numpre||' - numpar: '||r_codret.k00_numpar,lRaise,false,false);
      end if;

      nSimDivold := 0;
      nNaoDivold := 0;

      nValorSimDivold := 0;
      nValorNaoDivold := 0;

      nTotValorPagoDivold := 0;

      nTotalRecibo       := 0;
      nTotalNovosRecibos := 0;

      perform * from (
      select  recibopaga.k00_numpre as recibopaga_numpre,
              recibopaga.k00_numpar as recibopaga_numpar,
              recibopaga.k00_receit as recibopaga_receit,
              recibopaga.k00_numnov,
              coalesce( (select count(*)
                           from divold
                                inner join divida  on divold.k10_coddiv  = divida.v01_coddiv
                                inner join arrecad on arrecad.k00_numpre = divida.v01_numpre
                                                  and arrecad.k00_numpar = divida.v01_numpar
                                                  and arrecad.k00_valor  > 0
                          where divold.k10_numpre = recibopaga.k00_numpre
                            and divold.k10_numpar = recibopaga.k00_numpar
                        ), 0 ) as divold,
              round(sum(k00_valor),2) as k00_valor
         from disbanco
              inner join recibopaga on disbanco.k00_numpre = recibopaga.k00_numnov
                                   and disbanco.k00_numpar = 0
        where disbanco.idret = r_codret.idret
        group by recibopaga.k00_numpre,
                 recibopaga.k00_numpar,
                 recibopaga.k00_receit,
                 recibopaga.k00_numnov,
                 divold
      ) as x where k00_valor < 0;

      if found then
        insert into tmpnaoprocessar values (r_codret.idret);
        perform fc_debug('  <BaixaBanco>  - idret '||r_codret.idret || ' - insert tmpnaoprocessar',lRaise,false,false);
      else

        for r_testa in
        select  recibopaga.k00_numpre as recibopaga_numpre,
                recibopaga.k00_numpar as recibopaga_numpar,
                recibopaga.k00_receit as recibopaga_receit,
                recibopaga.k00_numnov,
                coalesce( (select count(*)
                             from divold
                                  inner join divida  on divold.k10_coddiv = divida.v01_coddiv
                                  inner join arrecad on arrecad.k00_numpre = divida.v01_numpre
                                                   and arrecad.k00_numpar = divida.v01_numpar
               and arrecad.k00_valor > 0
                           where divold.k10_numpre = recibopaga.k00_numpre
                             and divold.k10_numpar = recibopaga.k00_numpar
--                           and divold.k10_receita = recibopaga.k00_receit
                          ),0) as divold,
                round(sum(k00_valor),2) as k00_valor
           from disbanco
                inner join recibopaga on disbanco.k00_numpre = recibopaga.k00_numnov
                                     and disbanco.k00_numpar = 0
          where disbanco.idret = r_codret.idret
          group by recibopaga.k00_numpre,
                   recibopaga.k00_numpar,
                   recibopaga.k00_receit,
                   recibopaga.k00_numnov,
                   divold
        loop

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - verificando recibopaga - numpre: '||r_testa.recibopaga_numpre||' - numpar: '||r_testa.recibopaga_numpar||' - divold: '||r_testa.divold||' - k00_valor: '||r_testa.k00_valor,lRaise,false,false);
          end if;

          if r_testa.divold > 0 then
            nSimDivold := nSimDivold + 1;
            nValorSimDivold := nValorSimDivold + r_testa.k00_valor;
          else
            nNaoDivold := nNaoDivold + 1;
            nValorNaoDivold := nValorNaoDivold + r_testa.k00_valor;
          end if;
          insert into tmpacerta_recibopaga_unif values (r_testa.recibopaga_numpre, r_testa.recibopaga_numpar, r_testa.recibopaga_receit, r_testa.k00_numnov, case when r_testa.divold > 0 then 1 else 2 end);

        end loop;

      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nSimDivold: '||nSimDivold||' - nNaoDivold: '||nNaoDivold||' - idret: '||r_codret.idret,lRaise,false,false);
      end if;

      if nSimDivold > 0 and nNaoDivold > 0 then

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - vai ser dividido...',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - nSimDivold: '||nSimDivold||' - nNaoDivold: '||nNaoDivold||' - idret: '||r_codret.idret,lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        end if;

        nValorTotDivold := nValorSimDivold + nValorNaoDivold;

        for rContador in select 1 as tipo union select 2 as tipo
          loop

          select nextval('numpref_k03_numpre_seq') into iNumnovDivold;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - inserindo em recibopaga - numnov: '||iNumnovDivold,lRaise,false,false);
            perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
          end if;

          insert into recibopaga
          (
          k00_numcgm,
          k00_dtoper,
          k00_receit,
          k00_hist,
          k00_valor,
          k00_dtvenc,
          k00_numpre,
          k00_numpar,
          k00_numtot,
          k00_numdig,
          k00_conta,
          k00_dtpaga,
          k00_numnov
          )
          select
          k00_numcgm,
          k00_dtoper,
          k00_receit,
          k00_hist,
          k00_valor,
          k00_dtvenc,
          k00_numpre,
          k00_numpar,
          k00_numtot,
          k00_numdig,
          k00_conta,
          k00_dtpaga,
          iNumnovDivold
          from recibopaga
          inner join tmpacerta_recibopaga_unif on
          recibopaga.k00_numpre = tmpacerta_recibopaga_unif.numpre and
          recibopaga.k00_numpar = tmpacerta_recibopaga_unif.numpar and
          recibopaga.k00_receit = tmpacerta_recibopaga_unif.receit and
          recibopaga.k00_numnov = tmpacerta_recibopaga_unif.numpreoriginal
          where tmpacerta_recibopaga_unif.tipo = rContador.tipo;

          insert into db_reciboweb
          (
          k99_numpre,
          k99_numpar,
          k99_numpre_n,
          k99_codbco,
          k99_codage,
          k99_numbco,
          k99_desconto,
          k99_tipo,
          k99_origem
          )
          select
          distinct
          k99_numpre,
          k99_numpar,
          iNumnovDivold,
          k99_codbco,
          k99_codage,
          k99_numbco,
          k99_desconto,
          k99_tipo,
          k99_origem
          from db_reciboweb
          inner join tmpacerta_recibopaga_unif on
          k99_numpre = tmpacerta_recibopaga_unif.numpre and
          k99_numpar = tmpacerta_recibopaga_unif.numpar and
          k99_numpre_n = tmpacerta_recibopaga_unif.numpreoriginal
          where tmpacerta_recibopaga_unif.tipo = rContador.tipo;

          insert into arrehist
          (
          k00_numpre,
          k00_numpar,
          k00_hist,
          k00_dtoper,
          k00_hora,
          k00_id_usuario,
          k00_histtxt,
          k00_limithist,
          k00_idhist
          )
          values
          (
          iNumnovDivold,
          0,
          930,
          current_date,
          to_char(now(), 'HH24:MI'),
          1,
          'criado automaticamente pela divisao automatica dos recibos durante a consistencia da baixa de banco - numpre original: ' || r_testa.k00_numnov,
          null,
          nextval('arrehist_k00_idhist_seq'));

          select nextval('disbanco_idret_seq') into v_nextidret;

          nValorPagoDivold := case when rContador.tipo = 1 then nValorSimDivold else nValorNaoDivold end / nValorTotDivold * r_codret.vlrpago;
          nTotValorPagoDivold := nTotValorPagoDivold + nValorPagoDivold;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - tipo: '||rContador.tipo||' - nValorSimDivold: '||nValorSimDivold||' - nValorNaoDivold: '||nValorNaoDivold||' - nValorTotDivold: '||nValorTotDivold||' - vlrpago: '||r_codret.vlrpago||' - nTotValorPagoDivold: '||nTotValorPagoDivold,lRaise,false,false);
          end if;

          if rContador.tipo = 2 then
            if nTotValorPagoDivold <> r_codret.vlrpago then
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - acertando nValorPagoDivold',lRaise,false,false);
              end if;
              nValorPagoDivold := r_codret.vlrpago - nTotValorPagoDivold;
            end if;
          end if;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - inserindo disbanco - idret: '||v_nextidret||' - vlrpago: '||nValorPagoDivold||' - numnov: '||iNumnovDivold||' - novo idret: '||v_nextidret,lRaise,false,false);
          end if;


          insert into disbanco (k00_numbco,
                                k15_codbco,
                                k15_codage,
                                codret,
                                dtarq,
                                dtpago,
                                vlrpago,
                                vlrjuros,
                                vlrmulta,
                                vlracres,
                                vlrdesco,
                                vlrtot,
                                cedente,
                                vlrcalc,
                                idret,
                                classi,
                                k00_numpre,
                                k00_numpar,
                                convenio,
                                instit )
                         select k00_numbco,
                                k15_codbco,
                                k15_codage,
                                codret,
                                dtarq,
                                dtpago,
                                round(nValorPagoDivold,2),
                                0,
                                0,
                                0,
                                0,
                                round(nValorPagoDivold,2),
                                cedente,
                                round(vlrcalc,2),
                                v_nextidret,
                                false,
                                iNumnovDivold,
                                0,
                                convenio,
                                instit
                           from disbanco
                          where idret = r_codret.idret;

          insert into tmpantesprocessar (idret, vlrpago, v01_seq) values (v_nextidret, nValorPagoDivold, (select nextval('w_divold_seq')) );

          select round(sum(k00_valor),2)
            into nTotalRecibo
            from recibopaga where k00_numnov = iNumnovDivold;

          nTotalNovosRecibos := nTotalNovosRecibos + nTotalRecibo;

        end loop;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - nTotalNovosRecibos: '||nTotalNovosRecibos||' - recibopaga_k00_valor: '||r_codret.recibopaga_sum_valor,lRaise,false,false);
          if round(nTotalNovosRecibos,2) <> round(r_codret.recibopaga_sum_valor,2) then
            return '22 - INCONSISTENCIA AO GERAR NOVOS RECIBOS NA DIVISAO. IDRET: ' || r_codret.idret || ' - NUMPRE RECIBO ORIGINAL: ' || r_codret.k00_numpre;
          end if;
        end if;

        /*delete
          from disbancotxtreg
          where disbancotxtreg.k35_idret = r_codret.idret;*/
        update disbancotxtreg
           set k35_idret = v_nextidret
         where k35_idret = r_codret.idret;

        delete
          from issarqsimplesregdisbanco
         where q44_disbanco = r_codret.idret;

        delete
          from disbanco
         where disbanco.idret = r_codret.idret;

--        return 'parou';
      else

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - NAO vai ser dividido...',lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  - nSimDivold: '||nSimDivold||' - nNaoDivold: '||nNaoDivold||' - idret: '||r_codret.idret,lRaise,false,false);
          perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        end if;

      end if;

      delete from tmpacerta_recibopaga_unif;

  end loop;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - fim separando recibopaga...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  select round(sum(vlrpago),2)
    into nTotalDisbancoOriginal
    from tmpdisbanco_inicio_original;

  select round(sum(vlrpago),2)
    into nTotalDisbancoDepois
    from disbanco
   where disbanco.codret = cod_ret
     and disbanco.classi is false
     and disbanco.instit = iInstitSessao;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - nTotalDisbancoOriginal: '||nTotalDisbancoOriginal||' - nTotalDisbancoDepois: '||nTotalDisbancoDepois,lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - inicio verificando se foi importado para divida',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  -- verifica se foi importado para divida, porem somente nos casos de pagamento por carne, ou seja, registros que estejam no arrecad pelo numpre e parcela
  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.instit
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
       /*
         and case when iNumprePagamentoParcial = 0
                  then true
                  else disbanco.k00_numpre > iNumprePagamentoParcial
              end
        */
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by idret
  loop

    -- inicio numpre/numpar (carne)
    for r_idret in
      select distinct
             1 as tipo,
             disbanco.dtarq,
             disbanco.dtpago,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join divold   on divold.k10_numpre = disbanco.k00_numpre
                                and divold.k10_numpar = disbanco.k00_numpar
             inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                and divida.v01_instit = iInstitSessao
             inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                and arrecad.k00_numpar = divida.v01_numpar
        and arrecad.k00_valor > 0
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.k00_numpar > 0
      union
      select distinct
             2 as tipo,
             disbanco.dtarq,
             disbanco.dtpago,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
       from disbanco
             inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre
             inner join divold       on divold.k10_numpre = db_reciboweb.k99_numpre
                                    and divold.k10_numpar = db_reciboweb.k99_numpar
             inner join divida       on divida.v01_coddiv = divold.k10_coddiv
                                    and divida.v01_instit = iInstitSessao
             inner join arrecad      on arrecad.k00_numpre = divida.v01_numpre
                                    and arrecad.k00_numpar = divida.v01_numpar
            and arrecad.k00_valor > 0
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.k00_numpar = 0
       union
      select distinct
             3 as tipo,
             disbanco.dtarq,
             disbanco.dtpago,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join divold   on divold.k10_numpre = disbanco.k00_numpre and disbanco.k00_numpar = 0
             inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                and divida.v01_instit = iInstitSessao
             inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                and arrecad.k00_numpar = divida.v01_numpar
                                and arrecad.k00_valor > 0
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.k00_numpar = 0

    loop

      --
      -- Verificamos se o idret ja nao teve um abatimento lancado.
      --   Quando temos um recibo que teve uma de suas origens(numpre, numpar) importadas para divida / parcelada
      --   antes do processamento do pagamento a baixa os retira do recibopaga para gerar uma diferenca e processa
      --   o pagamento parcial / credito normalmente
      -- Por isso no caso de existir regitros na abatimentodisbanco passamos para a proxima volta do for
      --
      perform *
         from abatimentodisbanco
        where k132_idret = r_codret.idret;

      if found then
        continue;
      end if;


      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
        perform fc_debug('  <BaixaBanco>  - divold idret: '||R_IDRET.idret||' - tipo: '||R_IDRET.tipo||' - vlrpago: '||R_IDRET.vlrpago,lRaise,false,false);
      end if;

      v_total1          := 0;
      v_total2          := 0;
      v_diferenca_round := 0;

      for r_divold in

        select distinct
               1 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor
          from disbanco
               inner join divold   on divold.k10_numpre = disbanco.k00_numpre
                                  and divold.k10_numpar = disbanco.k00_numpar
               inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                  and divida.v01_instit = iInstitSessao
               inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                  and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret and r_idret.tipo = 1
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and disbanco.k00_numpar > 0
        union
        select distinct
               2 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor
          from disbanco
               inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre and disbanco.k00_numpar = 0
               inner join divold       on divold.k10_numpre = db_reciboweb.k99_numpre
                                      and divold.k10_numpar = db_reciboweb.k99_numpar
               inner join divida       on divida.v01_coddiv = divold.k10_coddiv
                                      and divida.v01_instit = iInstitSessao
               inner join arrecad      on arrecad.k00_numpre = divida.v01_numpre
                                      and arrecad.k00_numpar = divida.v01_numpar
              and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret and r_idret.tipo = 2
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
         union
        select distinct
               3 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor
          from disbanco
               inner join divold   on divold.k10_numpre = disbanco.k00_numpre and disbanco.k00_numpar = 0
               inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                  and divida.v01_instit = iInstitSessao
               inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                  and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret and r_idret.tipo = 3
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and disbanco.k00_numpar = 0

      loop

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - somando v_total1 - v01_coddiv: '||r_divold.v01_coddiv||' - valor: '||r_divold.v01_valor,lRaise,false,false);
        end if;

        v_total1 := v_total1 + r_divold.v01_valor;

      end loop;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - idret: '||r_codret.idret||' - v_total1: '||v_total1,lRaise,false,false);
      end if;

--      select setval('w_divold_seq',1);

      for r_divold in
        select * from
        (
        select distinct
               1 as tipo,
               v01_coddiv,
               divida.v01_numpre,
               divida.v01_numpar,
               divida.v01_valor,
               nextval('w_divold_seq') as v01_seq
          from disbanco
               inner join divold   on divold.k10_numpre = disbanco.k00_numpre
                                  and divold.k10_numpar = disbanco.k00_numpar
               inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                  and divida.v01_instit = iInstitSessao
               inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                  and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
         where disbanco.idret  = r_codret.idret
           and disbanco.classi is false
           and disbanco.instit = iInstitSessao
           and r_idret.tipo = 1
           and disbanco.k00_numpar > 0
         union
         select distinct
               2 as tipo,
               v01_coddiv,
               v01_numpre,
               v01_numpar,
               v01_valor,
               nextval('w_divold_seq') as v01_seq
          from (
                 select distinct
                       v01_coddiv,
                       divida.v01_numpre,
                       divida.v01_numpar,
                       divida.v01_valor
                  from disbanco
                       inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre and disbanco.k00_numpar = 0
                       inner join divold   on divold.k10_numpre = db_reciboweb.k99_numpre
                                          and divold.k10_numpar = db_reciboweb.k99_numpar
                       inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                          and divida.v01_instit = iInstitSessao
                       inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                          and arrecad.k00_numpar = divida.v01_numpar
            and arrecad.k00_valor > 0
                 where disbanco.idret  = r_codret.idret
                   and disbanco.classi is false
                   and disbanco.instit = iInstitSessao
                   and r_idret.tipo = 2
              ) as x
        union
         select distinct
               3 as tipo,
               v01_coddiv,
               v01_numpre,
               v01_numpar,
               v01_valor,
               nextval('w_divold_seq') as v01_seq
          from (
                select distinct
                       v01_coddiv,
                       divida.v01_numpre,
                       divida.v01_numpar,
                       divida.v01_valor
                from disbanco
                     inner join divold   on divold.k10_numpre = disbanco.k00_numpre and disbanco.k00_numpar = 0
                     inner join divida   on divida.v01_coddiv = divold.k10_coddiv
                                        and divida.v01_instit = iInstitSessao
                     inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre
                                        and arrecad.k00_numpar = divida.v01_numpar
          and arrecad.k00_valor > 0
               where disbanco.idret  = r_codret.idret
                 and disbanco.classi is false
                 and disbanco.instit = iInstitSessao
                 and r_idret.tipo = 3
                 and disbanco.k00_numpar = 0
              ) as x
           ) as x
           order by v01_seq

      loop

        select nextval('disbanco_idret_seq')
          into v_nextidret;

        v_valor           := round(round(r_divold.v01_valor, 2) / v_total1 * round(r_idret.vlrpago, 2), 2);
        v_valor_sem_round := round(r_divold.v01_valor, 2) / v_total1 * round(r_idret.vlrpago, 2);

        v_diferenca_round := v_diferenca_round + (v_valor - v_valor_sem_round);

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo disbanco - processando idret: '||r_codret.idret||' - v01_coddiv: '||r_divold.v01_coddiv||' - valor: '||v_valor,lRaise,false,false);
        end if;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - v_valor: '||v_valor||' - v_valor_sem_round: '||v_valor_sem_round||' - v_diferenca_round: '||v_diferenca_round||' - seq: '||r_divold.v01_seq||' - tipo: '||r_divold.tipo,lRaise,false,false);
        end if;

        insert into disbanco (
          k00_numbco,
          k15_codbco,
          k15_codage,
          codret,
          dtarq,
          dtpago,
          vlrpago,
          vlrjuros,
          vlrmulta,
          vlracres,
          vlrdesco,
          vlrtot,
          cedente,
          vlrcalc,
          idret,
          classi,
          k00_numpre,
          k00_numpar,
          convenio,
          instit
        ) values (
          r_idret.k00_numbco,
          r_idret.k15_codbco,
          r_idret.k15_codage,
          cod_ret,
          r_idret.dtarq,
          r_idret.dtpago,
          v_valor,
          0,
          0,
          0,
          0,
          v_valor,
          '',
          0,
          v_nextidret,
          false,
          r_divold.v01_numpre,
          r_divold.v01_numpar,
          '',
          r_idret.instit
        );

        insert into tmpantesprocessar (idret, vlrpago, v01_seq) values (v_nextidret, v_valor, r_divold.v01_seq);

        v_total2 := v_total2 + v_valor;

      end loop;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - v_total2 antes da diferenca do round: '||v_total2,lRaise,false,false);
      end if;

      if v_diferenca_round <> 0 then
        update tmpantesprocessar set vlrpago = round(vlrpago - v_diferenca_round,2) where v01_seq = (select max(v01_seq) from tmpantesprocessar);
        update disbanco          set vlrpago = round(vlrpago - v_diferenca_round,2) where idret   = (select idret from tmpantesprocessar where v01_seq = (select max(v01_seq) from tmpantesprocessar));
        v_total2 := v_total2 - v_diferenca_round;
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - v_total2 depois da diferenca do round: '||v_total2,lRaise,false,false);
        end if;

      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - v_total2: '||v_total2||' - vlrpago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      if round(v_total2, 2) <> round(r_idret.vlrpago, 2) then
        return '23 - IDRET ' || r_codret.idret || ' INCONSISTENTE AO VINCULAR A DIVIDA ATIVA! CONTATE SUPORTE - VALOR SOMADO: ' || v_total2 || ' - VALOR PAGO: ' || r_idret.vlrpago || '!';
      end if;

      /*delete
        from disbancotxtreg
       where exists(select idret
                      from disbanco
                     where idret  = k35_idret
                       and codret = cod_ret); */
      update disbancotxtreg
         set k35_idret = v_nextidret
       where k35_idret = r_codret.idret;

      --
      -- Deletando da issarqsimplesregdisbanco pois pode o debito
      -- do simples ter sido importado para divida
      --
      delete
        from issarqsimplesregdisbanco
       where q44_disbanco = r_codret.idret;

      delete
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi = false
         and disbanco.idret  = r_codret.idret;

      delete
        from tmpantesprocessar
       where idret = r_codret.idret;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - DELETANDO DISBANCO E ANTESPROCESSAR...',lRaise,false,false);
      end if;

    end loop;
    -- fim numpre/numpar (carne)

  end loop;


  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - fim verificando se foi importado para divida',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - inicio PROCESSANDO REGISTROS...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  --------
  -------- PROCESSANDO REGISTROS
  --------
  for r_codret in
      select disbanco.codret,
             disbanco.idret,
             disbanco.k00_numpre,
             disbanco.k00_numpar,
             disbanco.vlrpago
        from disbanco
       where disbanco.codret = cod_ret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and disbanco.idret not in (select idret from tmpnaoprocessar)
    order by disbanco.idret
  loop
    gravaidret := 0;

    -- pelo NUMPRE
    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - iniciando registro disbanco - idret '||r_codret.idret,lRaise,false,false);
    end if;

    -- Verifica se eh recibo da emissao geral do issqn e na recibopaga esta com valor zerado
    -- caso positivo iremos atualizar o valor da recibopaga com o vlrpago da disbanco
    -- e gerar um arrehist para o caso
      select k00_numpre,
             k00_numpar,
             k00_receit,
             k00_hist,
             round(sum(k00_valor),2) as k00_valor
        into rReciboPaga
        from db_reciboweb
             inner join recibopaga  on k00_numnov = k99_numpre_n
       where k99_numpre_n = r_codret.k00_numpre
         and k99_tipo     = 6 -- Emissao Geral de ISSQN
    group by k00_numpre,
             k00_numpar,
             k00_receit,
             k00_hist
      having cast(round(sum(k00_valor),2) as numeric) = cast(0.00 as numeric);

    if found then
      update recibopaga
         set k00_valor  = r_codret.vlrpago
       where k00_numnov = r_codret.k00_numpre
         and k00_numpre = rReciboPaga.k00_numpre
         and k00_numpar = rReciboPaga.k00_numpar
         and k00_receit = rReciboPaga.k00_receit
         and k00_hist   = rReciboPaga.k00_hist;

      -- T24879: gerar arrehist para essa alteracao
      insert
        into arrehist(k00_idhist, k00_numpre, k00_numpar, k00_hist, k00_dtoper, k00_hora, k00_id_usuario, k00_histtxt, k00_limithist)
      values (nextval('arrehist_k00_idhist_seq'),
              rReciboPaga.k00_numpre,
              rReciboPaga.k00_numpar,
              rReciboPaga.k00_hist,
              cast(fc_getsession('DB_datausu') as date),
              to_char(current_timestamp, 'HH24:MI'),
              cast(fc_getsession('DB_id_usuario') as integer),
              'ALTERADO PELO ARQUIVO BANCARIO CODRET='||cast(r_codret.codret as text)||' IDRET='||cast(r_codret.idret as text),
              null);

    end if;

    v_estaemrecibopaga    := false;
    v_estaemrecibo        := false;
    v_estaemarrecadnormal := false;
    v_estaemarrecadunica  := false;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - verificando recibopaga...',lRaise,false,false);
      -- TESTE 1 - RECIBOPAGA
      -- alteracao 2 - sistema deve testar como ja faz na autentica se todos os registros da recibopaga estao na arrecad, e senao tem que dar inconsistencia
    end if;

    for r_idret in

    /**
     * @todo verificar numprebloqpag / alterar disbanco por recibopaga
     */
        select disbanco.k00_numpre as numpre,
               disbanco.k00_numpar as numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               round(sum(recibopaga.k00_valor),2) as k00_valor,
               disbanco.instit
          from disbanco
               inner join recibopaga     on disbanco.k00_numpre       = recibopaga.k00_numnov
               left  join numprebloqpag  on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                        and numprebloqpag.ar22_numpar = disbanco.k00_numpar
         where disbanco.idret  = r_codret.idret
           and disbanco.classi is false
       /*
           and case when iNumprePagamentoParcial = 0
                    then true
                    else disbanco.k00_numpre > iNumprePagamentoParcial
                end
        */
           and disbanco.instit = iInstitSessao
           and recibopaga.k00_conta = 0
           and numprebloqpag.ar22_numpre is null
      group by disbanco.k00_numpre,
               disbanco.k00_numpar,
               disbanco.idret,
               disbanco.k15_codbco,
               disbanco.k15_codage,
               disbanco.k00_numbco,
               disbanco.vlrpago,
               disbanco.vlracres,
               disbanco.vlrdesco,
               disbanco.vlrjuros,
               disbanco.vlrmulta,
               disbanco.instit
    loop

      v_estaemrecibopaga := true;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - recibopaga - numpre '||r_idret.numpre||' numpar '||r_idret.numpar,lRaise,false,false);
      end if;

      -- Verifica se algum numpre do recibo nao esta no arrecad
      -- caso nao esteja passa para o proximo e deixa inconsistente
      select coalesce(count(*),0)
        into iQtdeParcelasAberto
        from  (select distinct
                      arrecad.k00_numpre,
                      arrecad.k00_numpar
                 from recibopaga
                      inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                        and arrecad.k00_numpar = recibopaga.k00_numpar
                where k00_numnov = r_codret.k00_numpre ) as x;

      select coalesce(count(*),0)
        into iQtdeParcelasRecibo
        from (select distinct
                     recibopaga.k00_numpre,
                     recibopaga.k00_numpar
                from recibopaga
               where k00_numnov = r_codret.k00_numpre ) as x;

      if iQtdeParcelasAberto <> iQtdeParcelasRecibo then
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -   nao encontrou arrecad... gravaidret: '||gravaidret,lRaise,false,false);
        end if;
        continue;
      else
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  -   encontrou em arrecad... gravaidret: '||gravaidret,lRaise,false,false);
        end if;
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - entrou no update vlrcalc (1)...',lRaise,false,false);
      end if;

      -- Acerta vlrcalc
      update disbanco
         set vlrcalc = round((select (substr(fc_calcula,15,13)::float8+
                                substr(fc_calcula,28,13)::float8+
                                substr(fc_calcula,41,13)::float8-
                                substr(fc_calcula,54,13)::float8) as utotal
                          from (select fc_calcula(k00_numpre,k00_numpar,0,dtpago,dtpago,extract(year from dtpago)::integer)
                                  from disbanco
                                 where idret = r_codret.idret
                                   and codret = r_codret.codret
                                   and instit = iInstitSessao
                          ) as x
                       ),2)
       where idret  = r_codret.idret
         and codret = r_codret.codret
         and instit = r_idret.instit;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - saiu no update vlrcalc (1)...',lRaise,false,false);
      end if;

      gravaidret := r_codret.idret;
      retorno    := true;

      -- INSERE NO ARREPAGA OS PAGAMENTOS
      insert into arrepaga ( k00_numcgm,
                             k00_dtoper,
                             k00_receit,
                             k00_hist,
                             k00_valor,
                             k00_dtvenc,
                             k00_numpre,
                             k00_numpar,
                             k00_numtot,
                             k00_numdig,
                             k00_conta,
                             k00_dtpaga
                           ) select k00_numcgm,
                                    datausu,
                                k00_receit,
                                k00_hist,
                                round(sum(k00_valor),2) as k00_valor,
                                datausu,
                                k00_numpre,
                                k00_numpar,
                                k00_numtot,
                                k00_numdig,
                                conta,
                                datausu
                               from ( select k00_numcgm,
                                       k00_receit,
                                       case
                                         when exists ( select 1
                                                         from tmplista_unica
                                                        where idret = r_idret.idret ) then 990
                                         else k00_hist
                                       end as k00_hist,
                                       round((k00_valor / r_idret.k00_valor) * r_idret.vlrpago, 2) as k00_valor,
                                       k00_numpre,
                                       k00_numpar,
                                       k00_numtot,
                                       k00_numdig
                                  from recibopaga
                                 where k00_numnov = r_idret.numpre
                                    ) as x
                           group by k00_numcgm,
                          k00_receit,
                          k00_hist,
                          k00_numpre,
                          k00_numpar,
                          k00_numtot,
                          k00_numdig
                           order by k00_numpre,
                                    k00_numpar,
                                    k00_receit;



-- ALTERA SITUACAO DO ARREPAGA
      update recibopaga
         set k00_conta = conta,
             k00_dtpaga = datausu
       where k00_numnov = r_idret.numpre;

      v_contador := 0;
      v_somador  := 0;
      v_contagem := 0;

      for q_disrec in
          select k00_numpre,
                 k00_numpar,
                 k00_receit,
                 sum(round((k00_valor / r_idret.k00_valor) * r_idret.vlrpago, 2))
            from recibopaga
           where k00_numnov = r_idret.numpre
        group by k00_numpre,
                 k00_numpar,
                 k00_receit
          having sum(round(k00_valor,2)) <> 0.00::float8
      loop
        v_contagem := v_contagem + 1;
      end loop;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - v_contagem: '||v_contagem,lRaise,false,false);
      end if;

      for q_disrec in
          select k00_numpre,
                 k00_numpar,
                 k00_receit,
                 sum( round((k00_valor / r_idret.k00_valor) * r_idret.vlrpago, 2) )::numeric(15,2)
            from recibopaga
           where k00_numnov = r_idret.numpre
        group by k00_numpre,
                 k00_numpar,
                 k00_receit
          having sum(round(k00_valor,2)) <> 0.00::float8
      loop

        v_contador := v_contador + 1;
-- INSERE NO ARRECANT
        insert into arrecant  (
          k00_numpre,
          k00_numpar,
          k00_numcgm,
          k00_dtoper,
          k00_receit,
          k00_hist  ,
          k00_valor ,
          k00_dtvenc,
          k00_numtot,
          k00_numdig,
          k00_tipo  ,
          k00_tipojm
        ) select arrecad.k00_numpre,
                 arrecad.k00_numpar,
                 arrecad.k00_numcgm,
                 arrecad.k00_dtoper,
                 arrecad.k00_receit,
                 arrecad.k00_hist  ,
                 arrecad.k00_valor ,
                 arrecad.k00_dtvenc,
                 arrecad.k00_numtot,
                 arrecad.k00_numdig,
                 arrecad.k00_tipo  ,
                 arrecad.k00_tipojm
            from arrecad
                 inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre
                                       and arreinstit.k00_instit = iInstitSessao
           where arrecad.k00_numpre = q_disrec.k00_numpre
             and arrecad.k00_numpar = q_disrec.k00_numpar;
-- DELETE DO ARRECAD
        delete
          from arrecad
         using arreinstit
         where arreinstit.k00_numpre = arrecad.k00_numpre
           and arreinstit.k00_instit = iInstitSessao
           and arrecad.k00_numpre = q_disrec.k00_numpre
           and arrecad.k00_numpar = q_disrec.k00_numpar;

       -- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
        select arreidret.k00_numpre
          into _testeidret
          from arreidret
         where arreidret.k00_numpre = q_disrec.k00_numpre
           and arreidret.k00_numpar = q_disrec.k00_numpar
           and arreidret.idret      = r_idret.idret
           and arreidret.k00_instit = iInstitSessao;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - inserindo arreidret - numpre: '||q_disrec.k00_numpre||' - numpar: '||q_disrec.k00_numpar||' - idret: '||r_idret.idret,lRaise,false,false);
        end if;

        if _testeidret is null then
          insert into arreidret (
            k00_numpre,
            k00_numpar,
            idret,
            k00_instit
          ) values (
            q_disrec.k00_numpre,
            q_disrec.k00_numpar,
            r_idret.idret,
            r_idret.instit
          );
        end if;

        if q_disrec.sum != 0 then
          if autentsn is false then
-- GRAVA DISREC DAS RECEITAS PARA A CLASSIFICACAO
            v_somador := v_somador + q_disrec.sum;

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - inserindo disrec - receita: '||q_disrec.k00_receit||' - valor: '||q_disrec.sum||' - contador: '||v_contador||' - somador: '||v_somador||' - contagem: '||v_contagem,lRaise,false,false);
            end if;

            v_valor := q_disrec.sum;

            if v_contador = v_contagem then
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - vlrpago: '||r_idret.vlrpago||' - v_somador: '||v_somador,lRaise,false,false);
              end if;
              v_valor := v_valor + round(r_idret.vlrpago - v_somador,2);
            end if;

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - into disrec 1',lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - Verifica Receita',lRaise,false,false);
            end if;

            lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - Retorno verifica Receita: '||lVerificaReceita,lRaise,false,false);
            end if;

            if lVerificaReceita is false then
              return '24 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (1).';
            end if;

            perform * from disrec where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;

            if not found then

              v_valor := round(v_valor,2);

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -    inserindo disrec 1 - valor: '||v_valor||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;

              if v_valor > 0 then

                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - Inserindo na DISREC. valor: '||v_valor,lRaise,false,false);
                end if;

                insert into disrec (
                  codcla,
                  k00_receit,
                  vlrrec,
                  idret,
                  instit
                ) values (
                  vcodcla,
                  q_disrec.k00_receit,
                  v_valor,
                  r_idret.idret,
                  r_idret.instit
                );
              end if;

            else

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -    update disrec 1 - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;

              update disrec set vlrrec = vlrrec + round(v_valor,2)
              where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
            end if;

          end if;
        end if;

      end loop;

    end loop;

    if v_estaemrecibopaga is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em recibopaga...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em recibopaga...',lRaise,false,false);
      end if;
    end if;

-- arquivo recibo

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - verificando recibo...',lRaise,false,false);
      -- TESTE 2 -RECIBO AVULSO
    end if;

    for r_idret in
      select distinct
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join recibo       on disbanco.k00_numpre       = recibo.k00_numpre
             left join numprebloqpag on numprebloqpag.ar22_numpre = disbanco.k00_numpre
                                    and numprebloqpag.ar22_numpar = disbanco.k00_numpar
       where disbanco.idret  = r_codret.idret
         and disbanco.classi = false
         and disbanco.instit = iInstitSessao
         and numprebloqpag.ar22_sequencial is null
    loop

      v_estaemrecibo := true;

-- Verifica se algum numpre do recibo já esta pago
-- caso positivo passa para o proximo e deixa inconsistente
      perform recibo.k00_numpre
         from recibo
              inner join arrepaga  on arrepaga.k00_numpre = recibo.k00_numpre
                                  and arrepaga.k00_numpar = recibo.k00_numpar
        where recibo.k00_numpre = r_idret.numpre;

      if found then
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - recibo ja esta pago... gravaidret: '||gravaidret,lRaise,false,false);
        end if;
        continue;
      end if;

      -- Acerta vlrcalc
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - entrou no update vlrcalc (1)...',lRaise,false,false);
      end if;

      -- Acerta vlrcalc
      update disbanco
         set vlrcalc = round((select (substr(fc_calcula,15,13)::float8+
                                substr(fc_calcula,28,13)::float8+
                                substr(fc_calcula,41,13)::float8-
                                substr(fc_calcula,54,13)::float8) as utotal
                          from (select fc_calcula(k00_numpre,k00_numpar,0,dtpago,dtpago,extract(year from dtpago)::integer)
                                  from disbanco
                                 where idret = r_codret.idret
                                   and codret = r_codret.codret
                                   and instit = iInstitSessao
                          ) as x
                       ),2)
       where idret  = r_codret.idret
         and codret = r_codret.codret
         and instit = r_idret.instit;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - saiu no update vlrcalc (1)...',lRaise,false,false);
      end if;

      gravaidret := r_codret.idret;
      retorno    := true;

      -- INSERE NO ARREPAGA OS PAGAMENTOS
      select round(sum(k00_valor),2)
        into valorrecibo
        from recibo
       where k00_numpre = r_idret.numpre;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - xxx - numpre: '||r_idret.numpre||' - valorrecibo: '||valorrecibo||' - vlrpago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      if valorrecibo = 0 then
        valorrecibo := r_idret.vlrpago;
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - recibo... vlrpago: '||r_idret.vlrpago||' - valor recibo: '||valorrecibo,lRaise,false,false);
      end if;

     /**
      * Alterado para agrupar por receita quando for recibo avulso para n�o gerar registros duplicados
      * na arrepaga (k00_numpre k00_numpar k00_receit k00_hist)
      */
      insert into arrepaga (
        k00_numcgm,
        k00_dtoper,
        k00_receit,
        k00_hist,
        k00_valor,
        k00_dtvenc,
        k00_numpre,
        k00_numpar,
        k00_numtot,
        k00_numdig,
        k00_conta,
        k00_dtpaga
      ) select recibo.k00_numcgm,
               min(datausu),
               recibo.k00_receit,
               recibo.k00_hist,
               sum(round((recibo.k00_valor / valorrecibo) * r_idret.vlrpago, 2)),
               min(datausu),
               recibo.k00_numpre,
               recibo.k00_numpar,
               min(recibo.k00_numtot),
               min(recibo.k00_numdig),
               min(conta),
               min(datausu)
          from recibo
               inner join arreinstit  on arreinstit.k00_numpre = recibo.k00_numpre
                                     and arreinstit.k00_instit = iInstitSessao
         where recibo.k00_numpre = r_idret.numpre
      group by recibo.k00_numcgm,
               recibo.k00_numpre,
               recibo.k00_numpar,
               recibo.k00_receit,
               recibo.k00_hist;

      -- Verifica se o Total Pago é diferente do que foi Classificado (inserido na Arrepaga)
      v_diferenca := round(r_idret.vlrpago - (select round(sum(k00_valor),2) from arrepaga where k00_numpre = r_idret.numpre), 2);
      if v_diferenca > 0 then
        -- Altera maior receita com a diferenca encontrada
        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - recibo com diferenca de '||v_diferenca||' no classificado do idret '||r_idret.idret||' (numpre '||r_idret.numpre||' numpar '||r_idret.numpar||')',lRaise,false,false);
        end if;

        update arrepaga
           set k00_valor = k00_valor + v_diferenca
         where k00_numpre = r_idret.numpre
           and k00_receit = (select max(k00_receit) from arrepaga where k00_numpre = r_idret.numpre);
      end if;
      v_diferenca := 0; -- Seta valor anterior para garantir

-- ALTERA SITUACAO DO ARREPAGA
      for q_disrec in

          select arrepaga.k00_numpre,
                 arrepaga.k00_numpar,
                 arrepaga.k00_receit,
                 sum(round(arrepaga.k00_valor, 2))
            from arrepaga
                 inner join disbanco on disbanco.k00_numpre = arrepaga.k00_numpre
           where arrepaga.k00_numpre = r_idret.numpre
             and disbanco.idret      = r_codret.idret
             and disbanco.instit     = iInstitSessao
        group by arrepaga.k00_numpre,
                 arrepaga.k00_numpar,
                 arrepaga.k00_receit
      loop
-- INSERE NO ARRECANT
-- DELETE DO ARRECAD
-- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
        select arreidret.k00_numpre
          into _testeidret
          from arreidret
         where arreidret.k00_numpre = q_disrec.k00_numpre
           and arreidret.k00_numpar = q_disrec.k00_numpar
           and arreidret.idret      = r_idret.idret
           and arreidret.k00_instit = iInstitSessao;

        if _testeidret is null then
          insert into arreidret (
            k00_numpre,
            k00_numpar,
            idret,
            k00_instit
          ) values (
            q_disrec.k00_numpre,
            q_disrec.k00_numpar,
            r_idret.idret,
            r_idret.instit
          );
        end if;

        if q_disrec.sum != 0 then
          if autentsn is false then
-- GRAVA DISREC DAS RECEITAS PARA A CLASSIFICACAO
            lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);
            if lVerificaReceita is false then
              return '25 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (2).';
            end if;

            perform *
               from disrec
              where disrec.codcla     = vcodcla
                and disrec.k00_receit = q_disrec.k00_receit
                and disrec.idret      = r_idret.idret
                and disrec.instit     = r_idret.instit;
            if not found then
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - into disrec 2 - valor: '||q_disrec.sum||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;


              if round(q_disrec.sum,2) > 0 then

                insert into disrec (
                  codcla,
                  k00_receit,
                  vlrrec,
                  idret,
                  instit
                ) values (
                  vcodcla,
                  q_disrec.k00_receit,
                  round(q_disrec.sum,2),
                  r_idret.idret,
                  r_idret.instit
                );

             end if;

            else
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -    update disrec 2 - receita: '||q_disrec.k00_receit,lRaise,false,false);
              end if;
              update disrec set vlrrec = vlrrec + round(v_valor,2)
              where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
            end if;
            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - into disrec 3',lRaise,false,false);
            end if;
          end if;
        end if;

      end loop;

    end loop;

    if v_estaemrecibo is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em recibo...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em recibo...',lRaise,false,false);
      end if;
    end if;

    ----
    ---- PROCURANDO ARRECAD
    ----

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  - verificando arrecad...',lRaise,false,false);
      -- TESTE 3 - ARRECAD
    end if;

    for r_idret in
      select distinct
             1 as tipo,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join arrecad      on arrecad.k00_numpre = disbanco.k00_numpre
                                    and arrecad.k00_numpar = disbanco.k00_numpar
             inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre
                                    and arreinstit.k00_instit = iInstitSessao
             left join arrepaga      on arrepaga.k00_numpre = arrecad.k00_numpre
                                    and arrepaga.k00_numpar = arrecad.k00_numpar
                                    and arrepaga.k00_receit = arrecad.k00_receit
             left join numprebloqpag on numprebloqpag.ar22_numpre = arrecad.k00_numpre
                                    and numprebloqpag.ar22_numpar = arrecad.k00_numpar
       where disbanco.idret  = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and arrepaga.k00_numpre is null
         and numprebloqpag.ar22_sequencial is null
      union
      select distinct
             2 as tipo,
             disbanco.k00_numpre as numpre,
             disbanco.k00_numpar as numpar,
             disbanco.idret,
             disbanco.k15_codbco,
             disbanco.k15_codage,
             disbanco.k00_numbco,
             disbanco.vlrpago,
             disbanco.vlracres,
             disbanco.vlrdesco,
             disbanco.vlrjuros,
             disbanco.vlrmulta,
             disbanco.instit
        from disbanco
             inner join arrecad      on arrecad.k00_numpre = disbanco.k00_numpre
                                    and disbanco.k00_numpar = 0
             inner join arreinstit   on arreinstit.k00_numpre = arrecad.k00_numpre
                                    and arreinstit.k00_instit = iInstitSessao
             left join arrepaga      on arrepaga.k00_numpre = arrecad.k00_numpre
                                    and arrepaga.k00_numpar = arrecad.k00_numpar
                                    and arrepaga.k00_receit = arrecad.k00_receit
             left join numprebloqpag on numprebloqpag.ar22_numpre = arrecad.k00_numpre
                                    and numprebloqpag.ar22_numpar = arrecad.k00_numpar
       where disbanco.idret = r_codret.idret
         and disbanco.classi is false
         and disbanco.instit = iInstitSessao
         and arrepaga.k00_numpre is null
         and numprebloqpag.ar22_sequencial is null
    loop

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - ###### - tipo: '||r_idret.tipo,lRaise,false,false);
      end if;

      retorno := true;

      if r_idret.numpar = 0 then
        v_estaemarrecadunica  := true;
      else
        v_estaemarrecadnormal := true;
      end if;

      -- INSERE NO DISBANCO O VALOR CORRETO DO PAGAMENTO
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - codret : '||r_codret.codret||'-idret : '||r_codret.idret,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - arrecad - numpre: '||r_idret.numpre||' - numpar: '||r_idret.numpar||' - tot: '||x_totreg||' - pago: '||r_idret.vlrpago,lRaise,false,false);
      end if;

      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - entrou no update vlrcalc...',lRaise,false,false);
      end if;

        -- Acerta vlrcalc
        update disbanco
           set vlrcalc = round((select (substr(fc_calcula,15,13)::float8+
                                  substr(fc_calcula,28,13)::float8+
                                  substr(fc_calcula,41,13)::float8-
                                  substr(fc_calcula,54,13)::float8) as utotal
                            from (select fc_calcula(k00_numpre,k00_numpar,0,dtpago,dtpago,extract(year from dtpago)::integer)
                                    from disbanco
                                   where idret = r_codret.idret
                                     and codret = r_codret.codret
                                     and instit = iInstitSessao
                            ) as x
                         ),2)
         where idret  = r_codret.idret
           and codret = r_codret.codret
           and instit = r_idret.instit;

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - saiu no update vlrcalc...',lRaise,false,false);
        end if;

      if not r_idret.numpre is null then

        if lRaise is true then
          perform fc_debug('  <BaixaBanco>  - aaaaaaaaaaaaaaaaaaaaaaaa',lRaise,false,false);
        end if;

        if r_idret.numpar != 0 then

          -- TESTE 3.1 - ARRECAD COM PARCELA PREENCHIDA

          valortotal := r_idret.vlrpago+r_idret.vlracres-r_idret.vlrdesco;
          valorjuros := r_idret.vlrjuros;
          valormulta := r_idret.vlrmulta;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - valortotal: '||valortotal,lRaise,false,false);
          end if;

          select round(sum(arrecad.k00_valor),2) as k00_vlrtot
            into nVlrTfr
            from arrecad
                 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
           where arrecad.k00_numpre    = r_idret.numpre
             and arrecad.k00_numpar    = r_idret.numpar
             and arreinstit.k00_instit = r_idret.instit;

          primeirarec := 0;
          valorlanc   := 0;
          valorlancj  := 0;
          valorlancm  := 0;
          for r_receitas in
              select k00_numcgm,
                     k00_numtot,
                     k00_numdig,
                     k00_receit,
                     round(sum(k00_valor),2)::float8 as k00_valor,
                     k02_recjur,
                     k02_recmul
                from arrecad
                     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                     inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit
                     inner join tabrecjm   on tabrec.k02_codjm      = tabrecjm.k02_codjm
               where arrecad.k00_numpre    = r_idret.numpre
                 and arrecad.k00_numpar    = r_idret.numpar
                 and arreinstit.k00_instit = r_idret.instit
            group by k00_numcgm,
                     k00_numtot,
                     k00_numdig,
                     k00_receit,
                     k02_recjur,
                     k02_recmul
          loop

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - inicio do for...',lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
            end if;

            if r_receitas.k00_valor = 0 then
              fracao := 1::float8;
            else
              fracao := round((r_receitas.k00_valor*100)::float8/nVlrTfr,8)::float8/100::float8;
            end if;

            nVlrRec := to_char(round(valortotal * fracao,2),'9999999999999.99')::float8;

            -- juros
            nVlrRecj := to_char(round(valorjuros * fracao,2),'9999999999999.99')::float8;

            -- multa
            nVlrRecm := to_char(round(valormulta * fracao,2),'9999999999999.99')::float8;

            if lRaise then
              perform fc_debug('  <BaixaBanco>  - JUROS : '||nVlrRecj||' RECEITA : '||r_receitas.k02_recjur,lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - MULTA : '||nVlrRecm||' RECEITA : '||r_receitas.k02_recmul,lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - VALOR : '||nVlrRec ||' RECEITA : '||r_receitas.k00_receit,lRaise,false,false);
            end if;

            if r_receitas.k02_recjur = r_receitas.k02_recmul then
              nVlrRecj := nVlrRecj + nVlrRecm;
              nVlrRecm := 0;
            end if;

            if r_receitas.k02_recjur is null then
              nVlrRec  := nVlrRecm + nVlrRecj;
              nVlrRecj := 0;
              nVlrRecm := 0;
            end if;

            gravaidret := r_codret.idret;

            --
            -- Inserindo o valor da receita
            --
            if nVlrRec != 0 then
              if primeirarec = 0 then
                primeirarec := r_receitas.k00_receit;
              end if;
              valorlanc := round(valorlanc + nVlrRec,2)::float8;
              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - valorlanc: '||valorlanc,lRaise,false,false);
              end if;

              insert into arrepaga  (
                k00_numcgm,
                k00_dtoper,
                k00_receit,
                k00_hist  ,
                k00_valor ,
                k00_dtvenc,
                k00_numpre,
                k00_numpar,
                k00_numtot,
                k00_numdig,
                k00_conta ,
                k00_dtpaga
              ) values (
                r_receitas.k00_numcgm,
                datausu,
                r_receitas.k00_receit  ,
                991,
                nVlrRec,
                datausu ,
                r_idret.numpre,
                r_idret.numpar ,
                r_receitas.k00_numtot ,
                r_receitas.k00_numdig ,
                conta,
                datausu
              );
            end if;

            --
            -- Inserindo o valor do juros
            --
            if round(nVlrRecj,2)::float8 != 0 then
              if primeirarecj = 0 then
                primeirarecj := r_receitas.k02_recjur;
              end if;
              valorlancj := round(valorlancj + nVlrRecj,2)::float8;

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - Valor do juros '||nVlrRecj,lRaise,false,false);
                perform fc_debug('  <BaixaBanco>  - valorlancj: '||valorlancj,lRaise,false,false);
              end if;

              insert into arrepaga (
                k00_numcgm,
                k00_dtoper,
                k00_receit,
                k00_hist  ,
                k00_valor ,
                k00_dtvenc,
                k00_numpre,
                k00_numpar,
                k00_numtot,
                k00_numdig,
                k00_conta ,
                k00_dtpaga
              ) values (
                r_receitas.k00_numcgm,
                datausu,
                r_receitas.k02_recjur ,
                991,
                round(nVlrRecj,2)::float8,
                datausu,
                r_idret.numpre,
                r_idret.numpar ,
                r_receitas.k00_numtot ,
                r_receitas.k00_numdig  ,
                conta,
                datausu
              );
            end if;

            --
            -- Inserindo o valor da multa
            --
            if round(nVlrRecm,2)::float8 != 0 then

              if lRaise then
                perform fc_debug('  <BaixaBanco>  - Valor da multa : '||round(nVlrRecm,2),lRaise,false,false);
              end if;

              if primeirarecm = 0 then
                primeirarecm := r_receitas.k02_recmul;
              end if;
              valorlancm := round(valorlancm + nVlrRecm,2)::float8;

              insert into arrepaga (
                k00_numcgm,
                k00_dtoper,
                k00_receit,
                k00_hist  ,
                k00_valor ,
                k00_dtvenc,
                k00_numpre,
                k00_numpar,
                k00_numtot,
                k00_numdig,
                k00_conta ,
                k00_dtpaga
              ) values (
                r_receitas.k00_numcgm,
                datausu,
                r_receitas.k02_recmul ,
                991 ,
                round(nVlrRecm,2)::float8,
                datausu  ,
                r_idret.numpre,
                r_idret.numpar ,
                r_receitas.k00_numtot ,
                r_receitas.k00_numdig  ,
                conta,
                datausu
              );
            else
              if lRaise then
                perform fc_debug('  <BaixaBanco>  - nao processou multa - valor da multa : '||round(nVlrRecm,2),lRaise,false,false);
              end if;
            end if;

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - final do for...',lRaise,false,false);
              perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
            end if;

          end loop;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
            perform fc_debug('  <BaixaBanco>  - fora do for...',lRaise,false,false);
            perform fc_debug('  <BaixaBanco>  - ==========',lRaise,false,false);
          end if;

          valorlanc := round(valortotal - (valorlanc),2)::float8;

          if valorlanc != 0 then
            select oid
              into oidrec
              from arrepaga
             where k00_numpre = r_idret.numpre
               and k00_numpar = r_idret.numpar
               and k00_receit = primeirarec;

            update arrepaga
               set k00_valor = round(k00_valor + valorlanc,2)::float8
             where oid = oidrec ;
          end if;

          valorlancj := round(valorjuros - (valorlancj),2)::float8;
          if valorlancj != 0 then

            if lRaise then
              perform fc_debug('  <BaixaBanco>  - Somando juros na receira principal : '||valorlancj,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = r_idret.numpre
               and k00_numpar = r_idret.numpar
               and k00_receit = primeirarecj;

            -- comentei para teste

            update arrepaga
               set k00_valor = round(k00_valor + valorlancj,2)::float8
             where oid = oidrec;

          end if;

          valorlancm := round(valormulta - (valorlancm),2)::float8;
          if valorlancm != 0 then
            select oid
              into oidrec
              from arrepaga
             where k00_numpre = r_idret.numpre
               and k00_numpar = r_idret.numpar
               and k00_receit = primeirarecm;

            update arrepaga
               set k00_valor = round(k00_valor + valorlancm,2)::float8
             where oid = oidrec;

          end if;

          for q_disrec in
              select k00_receit,
                     round(sum(k00_valor),2) as sum
                from arrepaga
               where k00_numpre = r_idret.numpre
                 and k00_numpar = r_idret.numpar
                 and k00_dtoper = datausu
            group by k00_receit
          loop
            if q_disrec.sum != 0 then
              if autentsn is false then

                lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);
                if lVerificaReceita is false then
                  return '26 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (3).';
                end if;

                perform *
                   from disrec
                  where disrec.codcla = vcodcla
                    and disrec.k00_receit = q_disrec.k00_receit
                    and disrec.idret      = r_idret.idret
                    and disrec.instit     = r_idret.instit;
                if not found then
                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  - into disrec 4 - valor: '||q_disrec.sum||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;

                  if round(q_disrec.sum,2) > 0 then

                    insert into disrec (
                      codcla,
                      k00_receit,
                      vlrrec,
                      idret,
                      instit
                    ) values (
                      vcodcla,
                      q_disrec.k00_receit,
                      round(q_disrec.sum,2),
                      r_idret.idret,
                      r_idret.instit
                    );

                  end if;

                else

                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  -    update disrec 4 - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;

                  update disrec
                     set vlrrec = vlrrec + round(v_valor,2)
                   where disrec.codcla     = vcodcla
                     and disrec.k00_receit = q_disrec.k00_receit
                     and disrec.idret      = r_idret.idret
                     and disrec.instit     = r_idret.instit;

                end if;
                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - into disrec 5',lRaise,false,false);
                end if;
              end if;
            end if;
          end loop;

          insert into arrecant (
            k00_numcgm,
            k00_dtoper,
            k00_receit,
            k00_hist  ,
            k00_valor ,
            k00_dtvenc,
            k00_numpre,
            k00_numpar,
            k00_numtot,
            k00_numdig,
            k00_tipo  ,
            k00_tipojm
          ) select arrecad.k00_numcgm,
                   arrecad.k00_dtoper,
                   arrecad.k00_receit,
                   arrecad.k00_hist  ,
                   arrecad.k00_valor ,
                   arrecad.k00_dtvenc,
                   arrecad.k00_numpre,
                   arrecad.k00_numpar,
                   arrecad.k00_numtot,
                   arrecad.k00_numdig,
                   arrecad.k00_tipo  ,
                   arrecad.k00_tipojm
              from arrecad
                   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
             where arrecad.k00_numpre = r_idret.numpre
               and arrecad.k00_numpar = r_idret.numpar
               and arreinstit.k00_instit = r_idret.instit;

          delete
            from arrecad
           using arreinstit
           where arrecad.k00_numpre    = arreinstit.k00_numpre
             and arrecad.k00_numpre    = r_idret.numpre
             and arrecad.k00_numpar    = r_idret.numpar
             and arreinstit.k00_instit = r_idret.instit;

-- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
          select arreidret.k00_numpre
            into _testeidret
            from arreidret
           where arreidret.k00_numpre = r_idret.numpre
             and arreidret.k00_numpar = r_idret.numpar
             and arreidret.idret      = r_idret.idret
             and arreidret.k00_instit = r_idret.instit;

          if _testeidret is null then
            insert into arreidret (
              k00_numpre,
              k00_numpar,
              idret,
              k00_instit
            ) values (
              r_idret.numpre,
              r_idret.numpar,
              r_idret.idret,
              r_idret.instit
            );
          end if;

        else
          -- PARCELA UNICA
          -- TESTE 3.2 - ARRECAD COM PARCELA UNICA

          valortotal := r_idret.vlrpago+r_idret.vlracres-r_idret.vlrdesco;
          valorjuros := r_idret.vlrjuros;
          valormulta := r_idret.vlrmulta;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  -  unica - vlrtot '||valortotal||' - numpre: '||r_idret.numpre,lRaise,false,false);
          end if;


          select round(sum(arrecad.k00_valor),2) as k00_vlrtot
            into nVlrTfr
            from arrecad
                 inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
           where arrecad.k00_numpre    = r_idret.numpre
             and arreinstit.k00_instit = r_idret.instit;

          primeirarec := 0;
          valorlanc   := 0;
          valorlancj  := 0;
          valorlancm  := 0;

          for r_idunica in
            select distinct
                   arrecad.k00_numpre as numpre,
                   arrecad.k00_numpar as numpar
              from arrecad
                   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
             where arrecad.k00_numpre    = r_idret.numpre
               and arreinstit.k00_instit = r_idret.instit
          loop

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - dentro do for do arrecad - parcela: '||r_idunica.numpar,lRaise,false,false);
            end if;

            for r_receitas in
                select k00_numcgm,
                       k00_numtot,
                       k00_numdig,
                       k00_receit,
                       k00_tipo,
                       round(sum(k00_valor),2)::float8 as k00_valor,
                       k02_recjur,
                       k02_recmul
                  from arrecad
                       inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                       inner join tabrec     on tabrec.k02_codigo     = arrecad.k00_receit
                       inner join tabrecjm   on tabrec.k02_codjm      = tabrecjm.k02_codjm
                 where arrecad.k00_numpre    = r_idunica.numpre
                   and arrecad.k00_numpar    = r_idunica.numpar
                   and arreinstit.k00_instit = r_idret.instit
              group by k00_numcgm,
                       k00_numtot,
                       k00_numdig,
                       k00_receit,
                       k00_tipo,
                       k02_recjur,
                       k02_recmul
            loop

              --
              -- Modificação realizada devido ao erro gerado na tarefa 32607
              -- Motivo do erro:
              -- Foi pego o valor de 72.83 para um numpre de ISSQN Var, quando o arquivo do banco retornou, o  estava com valor zero no arrecad
              -- O que ocasionava erro nas linhas abaixo pois a variavel nVlrTfr que e resultado do somatorio do valor do  na tabela arrecad e
              -- utilizado para a divisão do valor da receita abaixo, estava igual a zero.
              --
              if r_receitas.k00_tipo = 3 and nVlrTfr = 0 then
                fracao := 100;
              else
                fracao := round((r_receitas.k00_valor*100)::float8/nVlrTfr,8)::float8/100::float8;
              end if;
              --
              -- fim da modificacao
              --


              nVlrRec := round(to_char(round(valortotal * fracao,2),'9999999999999.99')::float8,2)::float8;

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  -  rec '||r_receitas.k00_receit||' nVlrRec '||nVlrRec,lRaise,false,false);
              end if;
-- juros
              nVlrRecj := round(to_char(round(valorjuros * fracao,2),'9999999999999.99')::float8,2)::float8;

-- multa
              nVlrRecm := round(to_char(round(valormulta * fracao,2),'9999999999999.99')::float8,2)::float8;

              if r_receitas.k02_recjur = r_receitas.k02_recmul then
                nVlrRecj := nVlrRecj + nVlrRecm;
                nVlrRecm := 0;
              end if;

              if r_receitas.k02_recjur is null then
                nVlrRec  := nVlrRecm + nVlrRecj;
                nVlrRecj := 0;
                nVlrRecm := 0;
              end if;

              gravaidret := r_codret.idret;

              if lRaise is true then
                perform fc_debug('  <BaixaBanco>  - nVlrRec: '||nVlrRec,lRaise,false,false);
              end if;

              if nVlrRec != 0 then
                if primeirarec = 0 then
                  primeirarec := r_receitas.k00_receit;
                end if;
                primeiranumpre := r_idunica.numpre;
                primeiranumpar := r_idunica.numpar;
                valorlanc      := round(valorlanc + nVlrRec,2)::float8;

                insert into arrepaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist,
                  k00_valor,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta,
                  k00_dtpaga
                ) values (
                  r_receitas.k00_numcgm,
                  datausu,
                  r_receitas.k00_receit  ,
                  990 ,
                  round(nVlrRec,2)::float8,
                  datausu  ,
                  r_idunica.numpre,
                  r_idunica.numpar ,
                  r_receitas.k00_numtot,
                  r_receitas.k00_numdig  ,
                  conta,
                  datausu
                );
              end if;

              if round(nVlrRecj,2)::float8 != 0 then
                if primeirarecj = 0 then
                  primeirarecj := r_receitas.k02_recjur;
                end if;
                primeiranumpre := r_idunica.numpre;
                primeiranumpar := r_idunica.numpar;
                valorlancj     := round(valorlancj + nVlrRecj,2)::float8;
                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - juros '||nVlrRecj,lRaise,false,false);
                end if;

                insert into arrepaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist  ,
                  k00_valor ,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta ,
                  k00_dtpaga
                ) values (
                  r_receitas.k00_numcgm,
                  datausu,
                  r_receitas.k02_recjur ,
                  990,
                  round(nVlrRecj,2)::float8,
                  datausu,
                  r_idunica.numpre,
                  r_idunica.numpar ,
                  r_receitas.k00_numtot ,
                  r_receitas.k00_numdig  ,
                  conta,
                  datausu
                );
              end if;

              if round(nVlrRecm,2)::float8 != 0 then
                if primeirarecm = 0 then
                  primeirarecm := r_receitas.k02_recmul;
                end if;
                primeiranumpre := r_idunica.numpre;
                primeiranumpar := r_idunica.numpar;
                valorlancm     := round(valorlancm + nVlrRecm,2)::float8;

                insert into arrepaga (
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist  ,
                  k00_valor ,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_conta ,
                  k00_dtpaga
                ) values (
                  r_receitas.k00_numcgm,
                  datausu,
                  r_receitas.k02_recmul ,
                  990 ,
                  round(nVlrRecm,2)::float8,
                  datausu ,
                  r_idunica.numpre,
                  r_idunica.numpar ,
                  r_receitas.k00_numtot ,
                  r_receitas.k00_numdig  ,
                  conta,
                  datausu
                );
              end if;

            end loop;

            insert into arrecant (
              k00_numcgm,
              k00_dtoper,
              k00_receit,
              k00_hist  ,
              k00_valor ,
              k00_dtvenc,
              k00_numpre,
              k00_numpar,
              k00_numtot,
              k00_numdig,
              k00_tipo  ,
              k00_tipojm
            ) select arrecad.k00_numcgm,
                     arrecad.k00_dtoper,
                     arrecad.k00_receit,
                     arrecad.k00_hist  ,
                     arrecad.k00_valor ,
                     arrecad.k00_dtvenc,
                     arrecad.k00_numpre,
                     arrecad.k00_numpar,
                     arrecad.k00_numtot,
                     arrecad.k00_numdig,
                     arrecad.k00_tipo  ,
                     arrecad.k00_tipojm
                from arrecad
                     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
               where arrecad.k00_numpre    = r_idunica.numpre
                 and arrecad.k00_numpar    = r_idunica.numpar
                 and arreinstit.k00_instit = r_idret.instit;

            delete
              from arrecad
             using arreinstit
             where arrecad.k00_numpre    = arreinstit.k00_numpre
               and arrecad.k00_numpre    = r_idunica.numpre
               and arrecad.k00_numpar    = r_idunica.numpar
               and arreinstit.k00_instit = r_idret.instit;
-- TESTA SE EXISTE NUMPRE E NUMPAR NO ARREIDRET, NAO EXISTINDO INSERE O IDRET DO PAGAMENTO
            select arreidret.k00_numpre
              into _testeidret
              from arreidret
             where arreidret.k00_numpre = r_idunica.numpre
               and arreidret.k00_numpar = r_idunica.numpar
               and arreidret.idret      = r_idret.idret
               and arreidret.k00_instit = r_idret.instit;

            if _testeidret is null then
              insert into arreidret (
                k00_numpre,
                k00_numpar,
                idret,
                k00_instit
              ) values (
                r_idunica.numpre,
                r_idunica.numpar,
                r_idret.idret,
                r_idret.instit
              );
            end if;

          end loop;

          valorlanc  := round(valortotal - (valorlanc),2)::float8;
          valorlancj := round(valorjuros - (valorlancj),2)::float8;
          valorlancm := round(valormulta - (valorlancm),2)::float8;

          IF VALORLANC != 0  THEN

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  -  acerta 1 -- '||valorlanc,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = primeiranumpre
               and k00_numpar = primeiranumpar
               and k00_receit = primeirarec;

            update arrepaga
               set k00_valor = round(k00_valor + valorlanc,2)::float8
             where oid = oidrec ;
          end if;

          if valorlancj != 0 then

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  -  acerta 2 -- '||valorlancj,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = primeiranumpre
               and k00_numpar = primeiranumpar
               and k00_receit = primeirarecj;

            update arrepaga
               set k00_valor = round(k00_valor + valorlancj,2)::float8
             where oid = oidrec;

          end if;

          if valorlancm != 0 then

            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  -  acerta 3  -- '||valorlancm,lRaise,false,false);
            end if;

            select oid
              into oidrec
              from arrepaga
             where k00_numpre = primeiranumpre
               and k00_numpar = primeiranumpar
               and k00_receit = primeirarecm;

            update arrepaga
               set k00_valor = round(k00_valor + valorlancm,2)::float8
             where oid = oidrec;

          end if;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - antes for disrec - datausu: '||datausu,lRaise,false,false);
          end if;

          for q_disrec in
              select k00_receit,
                     round(sum(k00_valor),2) as sum
                from arrepaga
               where k00_numpre = r_idret.numpre
--                and k00_numpar = r_idret.numpar
                 and k00_dtoper = datausu
            group by k00_receit
          loop
            if q_disrec.sum != 0 then
              if autentsn is false then
                if lRaise is true then
                  perform fc_debug('  <BaixaBanco>  - into disrec 6',lRaise,false,false);
                end if;

                lVerificaReceita := fc_verificareceita(q_disrec.k00_receit);
                if lVerificaReceita is false then
                  return '27 - Receita: '||q_disrec.k00_receit||' n�o encontrada verifique o cadastro (4).';
                end if;

                perform * from disrec where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
                if not found then
                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  -    inserindo disrec 6 - valor: '||q_disrec.sum||' - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;

                  if round(q_disrec.sum,2) > 0 then

                    insert into disrec (
                      codcla,
                      k00_receit,
                      vlrrec,
                      idret,
                      instit
                    ) values (
                      vcodcla,
                      q_disrec.k00_receit,
                      round(q_disrec.sum,2),
                      r_idret.idret,
                      r_idret.instit
                    );

                  end if;

                else
                  if lRaise is true then
                    perform fc_debug('  <BaixaBanco>  -    update disrec 6 - receita: '||q_disrec.k00_receit,lRaise,false,false);
                  end if;
                  update disrec set vlrrec = vlrrec + round(v_valor,2)
                  where disrec.codcla = vcodcla and disrec.k00_receit = q_disrec.k00_receit and disrec.idret = r_idret.idret and disrec.instit = r_idret.instit;
                end if;
              end if;
            end if;
            if lRaise is true then
              perform fc_debug('  <BaixaBanco>  - durante for disrec',lRaise,false,false);
            end if;
          end loop;

          if lRaise is true then
            perform fc_debug('  <BaixaBanco>  - depois for disrec',lRaise,false,false);
          end if;

        end if;

      end if;

    end loop;

    if v_estaemarrecadnormal is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em arrecad normal...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em arrecad normal...',lRaise,false,false);
      end if;
    end if;

    if v_estaemarrecadunica is false then
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - nao esta em arrecad unica...',lRaise,false,false);
      end if;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - esta em arrecad unica...',lRaise,false,false);
      end if;
    end if;

-- pelo numpre do arrecad
    if gravaidret != 0 then
      if autentsn is false then
        insert into disclaret (
          codcla,
          codret
        ) values (
          vcodcla,
          r_codret.idret
        );
      end if;

      select ar22_sequencial
        into nBloqueado
        from numprebloqpag
             inner join disbanco on disbanco.k00_numpre = numprebloqpag.ar22_numpre
                                and disbanco.k00_numpar = numprebloqpag.ar22_numpar
        where disbanco.idret = r_codret.idret;

      if nBloqueado is not null and nBloqueado > 0 then
        lClassi = false;
      else
        lClassi = true;
      end if;

      if lRaise is true then
        if lClassi is true then
          perform fc_debug('  <BaixaBanco>  -  3 - Debito nao Bloqueado ',lRaise,false,false);
        else
          perform fc_debug('  <BaixaBanco>  -  4 - Debito Bloqueado '||r_codret.idret,lRaise,false,false);
        end if;
      end if;

      update disbanco
         set classi = lClassi
       where idret = r_codret.idret;
    else
      if lRaise is true then
        perform fc_debug('  <BaixaBanco>  - classi is false',lRaise,false,false);
      end if;
    end if;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  - finalizando registro disbanco - idret '||R_CODRET.IDRET,lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    end if;

  end loop;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - fim PROCESSANDO REGISTROS...',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  -  ',lRaise,false,false);
  end if;

  select sum(round(tmpantesprocessar.vlrpago,2))
    into v_total1
    from tmpantesprocessar
         inner join disbanco on tmpantesprocessar.idret = disbanco.idret
   where disbanco.classi is true;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  - ===============',lRaise,false,false);
    perform fc_debug('  <BaixaBanco>  - VCODCLA: '||VCODCLA,lRaise,false,false);
  end if;

  if autentsn is false then

    select sum(round(disrec.vlrrec,2))
      into v_total2
      from disrec
     where disrec.codcla = VCODCLA;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  |1| v_total1 (soma disbanco.vlrpago): '||v_total1||' - v_total2 (soma disrec.vlrrec): '||v_total2,lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
    end if;

    perform distinct
            disbanco.idret,
            disrec.idret
       from tmpantesprocessar
            inner join disbanco  on disbanco.idret = tmpantesprocessar.idret
                                and disbanco.classi is true
            left  join disrec    on disrec.idret = disbanco.idret
      where disrec.idret is null;

    if found and autentsn is false then
      return '28 - REGISTROS CLASSIFICADOS SEM DISREC';
    end if;

    v_diferenca = ( v_total1 - v_total2 );

    if cast(round(v_diferenca,2) as numeric) <> cast(round(0,2) as numeric) then

      if lRaise is true then
        perform fc_debug('============================',lRaise,false,false);
        perform fc_debug('<BaixaBanco> - Executar Acerto',lRaise,false,false);
        perform fc_debug('<BaixaBanco> - CodRet: '||cod_ret,lRaise,false,false);
        perform fc_debug('============================',lRaise,false,false);
      end if;

      for rAcertoDiferenca in  select idret,
                                      vlrpago as valor_disbanco,
                                      ( select sum(vlrrec)
                                          from disrec
                                         where disrec.idret = disbanco.idret) as valor_disrec
                                 from disbanco
                                where codret = cod_ret
                                  and cast(round(vlrpago,2) as numeric) <> cast(round((select sum(vlrrec)
                                                                                        from disrec
                                                                                       where disrec.idret = disbanco.idret),2) as numeric)
      loop

        nVlrDiferencaDisrec := ( rAcertoDiferenca.valor_disbanco - rAcertoDiferenca.valor_disrec );

        if lRaise is true then
          perform fc_debug('  <BaixaBanco> - Acerto de diferenca disrec | idret : '||rAcertoDiferenca.idret,lRaise,false,false);
          perform fc_debug('  <BaixaBanco> - valor disbanco : '||rAcertoDiferenca.valor_disbanco           ,lRaise,false,false);
          perform fc_debug('  <BaixaBanco> - valor disrec : '||rAcertoDiferenca.valor_disrec               ,lRaise,false,false);
        end if;

        update disrec
           set vlrrec = ( vlrrec + nVlrDiferencaDisrec )
         where idret  = rAcertoDiferenca.idret
           and codcla = VCODCLA
           and k00_receit = (select k00_receit
                               from disrec
                              where idret = rAcertoDiferenca.idret
                              order by vlrrec
                               desc limit 1);

      end loop;

      select sum(round(disrec.vlrrec,2))
        into v_total2
        from disrec
       where disrec.codcla /* = vcodcla;*/
       in (select codigo_classificacao
               from tmp_classificaoesexecutadas);

       perform fc_debug('  <BaixaBanco> - v_total2 ( soma disrec ) : ' ||v_total2, lRaise, false, false);
    end if;

    if lRaise is true then
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  |2| v_total1 (soma disbanco.vlrpago): '||v_total1||' - v_total2 (soma disrec.vlrrec): '||v_total2,lRaise,false,false);
      perform fc_debug('  <BaixaBanco>  ',lRaise,false,false);
    end if;

    if v_total1 <> v_total2 then

      return '29 - INCONSISTENCIA NOS VALORES PROCESSADOS DIFERENCA TOTAL DE '||(v_total1-v_total2);

    end if;

  end if;

  if lRaise is true then
    perform fc_debug('  <BaixaBanco>  - FIM DO PROCESSAMENTO... ',lRaise,false,true);
  end if;

  if retorno = false then
    return '30 - NAO EXISTEM DEBITOS PENDENTES PARA ESTE ARQUIVO';
  else
    return '1 - PROCESSO CONCLUIDO COM SUCESSO ';
  end if;

end;

$$ language 'plpgsql';drop function if exists fc_calculoiptu_cap_2008(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);

create or replace function fc_calculoiptu_cap_2008(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula 	  	alias   for $1; -- matricula
   iAnousu    	  	alias   for $2; -- exercicio
   bGerafinanc      alias   for $3; -- gera financeiro
   bAtualizap    	 	alias   for $4; -- atualiza parcela
   bNovonumpre	  	alias   for $5; -- novo numpre
   bCalculogeral   	alias   for $6; -- calculo geral
   bDemo        		alias   for $7; -- gera demonstrativo
   iParcelaini     	alias   for $8; -- parcela inicial
   iParcelafim     	alias   for $9; -- parcela final

   iIdbql           integer default 0;
   iNumcgm          integer default 0;
   iCodcli          integer default 0;
   iCodisen         integer default 0;
   iTipois          integer default 0;
   iParcelas        integer default 0;
   iNumconstr       integer default 0;
   iZona            integer default 0;
   iCodErro         integer default 0;

   dDatabaixa       date;

   nAreal           numeric default 0;
   nAreaTotalC      numeric default 0;
   nAreac           numeric default 0;
   nTotarea         numeric default 0;
   nFracao          numeric default 0;
   nFracaolote      numeric default 0;
   nAliquota        numeric default 0;
   nIsenaliq        numeric default 0;
   nArealo          numeric default 0;
   nVvc             numeric(15,2) default 0;
   nVvt             numeric(15,2) default 0;
   nVv              numeric(15,2) default 0;
   nViptu           numeric(15,2) default 0;
   nValorInflator   numeric(15,2) default 0;

   tRetorno         text default '';
   tDemo            text default '';

   bFinanceiro      boolean;
   bDadosIptu       boolean;
   bErro            boolean;
   bIsentaxas       boolean;
   bTempagamento    boolean;
   bEmpagamento     boolean;
   bTaxasCalculadas boolean;
   bRaise           boolean default false; -- true para habilitar raise na funcao principal
   bSubRaise        boolean default false; -- true para habilitar raise nas sub-funcoes

   rCfiptu          record;

begin

   bRaise    := false;   -- true para abilitar raise notice na funcao principal
   bSubRaise := false;   -- true para abilitar raise notice nas sub-funcoes

  if bRaise then
    raise notice 'IDBQL - %  AREAL - %  FRACAO - %  CGM - %   DATABAIXA - %   ERRO - %  RETORNO - %',  iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno;
  end if;



  /* VERIFICA SE OS PARAMETROS PASSADOS ESTAO CORRETOS */
  select riidbql, rnareal, rnfracao, rinumcgm, rdbaixa, rberro, rtretorno
    into iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno
    from fc_iptu_verificaparametros(iMatricula,iAnousu,iParcelaini,iParcelafim);
  if bRaise then
    raise notice 'IDBQL - %  AREAL - %  FRACAO - %  CGM - %   DATABAIXA - %   ERRO - %  RETORNO - %',  iIdbql,  nAreal,  nFracao,  iNumcgm,  dDatabaixa, bErro, tRetorno;
  end if;

  /* VERIFICA SE O CALCULO PODE SER REALIZADO */
  select rbErro,
         riCodErro
    into bErro,
         iCodErro
    from fc_iptu_verificacalculo(iMatricula,iAnousu,iParcelaini,iParcelafim);
  if bErro is true and bDemo is false then
    select fc_iptu_geterro(iCodErro,'') into tRetorno;
    return tRetorno;
  end if;

  /* VERIFICA SE MATRICULA ESTA BAIXADA */
  if dDataBaixa is not null and to_char(dDataBaixa,'Y')::integer <= iAnousu then

     /* criar funcao para exclusao de calculo */

     delete from arrecad using iptunump where k00_numpre = iptunump.j20_numpre and iptunump.j20_anousu = iAnousu and iptunump.j20_matric = iMatricula;
     delete from iptunump where j20_anousu = iAnousu and j20_matric = iMatricula;

     select fc_iptu_geterro(2, '') into tRetorno;
     return tRetorno;

  end if;


  /* CRIA AS TABELAS TEMPORARIAS */
  select * into bErro from fc_iptu_criatemptable(bSubRaise);

  /* GUARDA OS PARAMETROS DO CALCULO */
  select *
    from cfiptu into rCfiptu
	       left join infla on i02_codigo = j18_infla
	                      and extract (year from i02_data) = j18_anousu
	where j18_anousu = iAnousu;

  if rCfiptu.i02_valor is null then
     select fc_iptu_geterro(99, 'SEM VALOR DO INFLATOR CONFIGURADO!') into tRetorno;
     return tRetorno;
  end if;

  /* FRACIONA LOTE */
  if bRaise then
    raise notice 'PARAMETROS IPTU_FRACIONALOTE FRACAO DO LOTE : % -- % -- % -- % ',iMatricula, iAnousu, bDemo, bSubRaise;
  end if;
  select rnfracao, rtdemo, rtmsgerro, rberro
    into nFracaolote, tDemo, tRetorno, bErro
    from fc_iptu_fracionalote(iMatricula,iAnousu,bDemo,bSubRaise);
    update tmpdadosiptu set fracao = nFracaolote;
  if bRaise then
    raise notice 'RETORNO FC_IPTU_FRACIONALOTE --->>> FRACAO DO LOTE : % - DEMONS : % - MSGRETORNO : % - ERRO : % ',nFracaolote, tDemo, tRetorno, bErro;
  end if;

  /* VERIFICA PAGAMENTOS */
  if bRaise then
    raise notice 'PARAMETROS fc_iptu_verificapag VERIFICANDO PARGAMENTOS  : % -- % -- % -- % ',iMatricula, iAnousu, bDemo, bSubRaise;
  end if;
  select rbtempagamento, rbempagamento, rtmsgretorno, rberro
    into bTempagamento, bEmpagamento, tRetorno, bErro
    from fc_iptu_verificapag(iMatricula,iAnousu,bCalculogeral,bAtualizap,false,bDemo,bSubRaise);
  if bRaise then
    raise notice 'RETORNO fc_iptu_verificapag -->>> TEMPAGAMENTO : % -- EMPAGAMENTO % -- RETORNO % -- ERRO % ',bTempagamento, bEmpagamento, tRetorno, bErro;
  end if;

  /* CALCULA VALOR DO TERRENO */
  if bRaise then
    raise notice 'PARAMETROS fc_iptu_calculavvt_cap_2008  IDBQL : % -- FRACAO DO LOTE % -- DEMO % -- ERRO % ',iIdbql, nFracaolote, tRetorno, bErro;
  end if;
  select rnvvt, rnAreaTotalC,rnarea, rtdemo, rtmsgerro, rberro
    into nVvt, nAreaTotalC, nAreac, tDemo, tRetorno, bErro
    from fc_iptu_calculavvt_cap_2008(iIdbql, nFracaolote, iAnousu, bDemo, bSubRaise);
  if bRaise then
    raise notice 'RETORNO fc_iptu_calculavvt_cap_2008 -->>> Area Total Corrigida: % VVT : % -- AREA CORRIGIDA % --  RETORNO % -- ERRO % ',nAreaTotalC, nVvt, nAreac, tRetorno, bErro;
  end if;

  if bErro = true then
    select fc_iptu_geterro(6, '') into tRetorno;
    return tRetorno;
  end if;

  /* VERIFICA ISENCOES */
  if bRaise then
    raise notice 'PARAMETROS fc_iptu_verificaisencoes  MATRICULA % -- ANOUSU % -- DEMO % -- ERRO % ', iMatricula, iAnousu, bDemo, bSubRaise;
  end if;
  select ricodisen, ritipois, rnisenaliq, rbisentaxas, rnarealo
    into iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo
    from fc_iptu_verificaisencoes(iMatricula,iAnousu,bDemo,bSubRaise);
  if iTipois is not null then
    update tmpdadosiptu set tipoisen = iTipois;
  end if;
  if bRaise then
    raise notice 'RETORNO fc_iptu_verificaisencoes -->>> CODISEN : % -- TIPOISEN : % --  ALIQ INSEN : % -- INSENTAXAS: % -- AREALO : % ',iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo;
  end if;

  /* CALCULA VALOR DA CONSTRUCAO */
  if bRaise then
    raise notice 'PARAMETROS fc_iptu_calculavvc_cap_2008  MATRICULA % -- ANOUSU % -- DEMO % -- ERRO % ', iMatricula, iAnousu, bDemo, bSubRaise;
  end if;

     --
     -- Aten��o!
     -- No calculo do valor venal da constru��o o inflator e o seu valor podem ser alterados de acordo com a caracteristica
     -- da constru��o do grupo 6.
     -- Se a caracteristica for 60 ou 61 o inflator utilizado ser� o CUB
     -- Se a caracteristica for 62 ou 66 o inflator utilizado ser� o CUB_C
     -- Do contr�rio ser� o valor do inflator configurado nos par�metros do IPTU
     --
   select case
            when j35_caract in (60,61)
              then ( select i02_valor from infla where extract (year from i02_data) = iAnousu and i02_codigo = 'CUB')
            when j35_caract in (62,66)
              then ( select i02_valor from infla where extract (year from i02_data) = iAnousu and i02_codigo = 'CUB_C')
          end
     into nValorInflator
     from carlote
          inner join caracter on caracter.j31_codigo = carlote.j35_caract
    where j31_grupo = 6
      and j35_idbql = iIdbql;
    if nValorInflator = 0 then
      nValorInflator = rCfiptu.i02_valor::numeric;
    end if;

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, bErro
    from fc_iptu_calculavvc_cap_2008(iMatricula,iAnousu, nValorInflator::numeric, bDemo,bSubRaise);
  if bRaise then
    raise notice 'RETORNO fc_iptu_calculavvc_cap_2008 -->>> VVC : % -- AREA TOTAL : % --  NUMERO DE CONTRUCOES : % -- RETORNO : % -- ERRO : % ', nVvc, nTotarea, iNumconstr, tRetorno, bErro;
  end if;
  if nVvc is null or nVvc = 0 and iNumconstr <> 0 then
     select fc_iptu_geterro(22, '') into tRetorno;
     return tRetorno;
  end if;
	if bErro is true then
     select fc_iptu_geterro(22, '') into tRetorno;
     return tRetorno;
	end if;

  /* BUSCA A ALIQUOTA  */
  -- so executar se nao for isento
  if iNumconstr is not null and iNumconstr > 0 then
      select fc_iptu_getaliquota_cap_2008(iMatricula,iIdbql,iNumcgm,true,bSubRaise) into nAliquota;
  else
      select fc_iptu_getaliquota_cap_2008(iMatricula,iIdbql,iNumcgm,false,bSubRaise) into nAliquota;
  end if;

  if not found or nAliquota = 0 then
     select fc_iptu_geterro(13, '') into tRetorno;
     return tRetorno;
  end if;

/*--------- CALCULA O VALOR VENAL -----------*/

  nVv    := nVvc + nVvt;

  nViptu := nVv * ( nAliquota / 100 );

/*-------------------------------------------*/
  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;
  if not found or iParcelas = 0 then
     select fc_iptu_geterro(14, '') into tRetorno;
     return tRetorno;
  end if;

  select j34_zona
  into iZona
  from lote where j34_idbql = iIdbql;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;


  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim , aliq = nAliquota, matric = iMatricula;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas, zona = iZona, totareaconst = nTotArea ;

/* CALCULA AS TAXAS */
  select db21_codcli
    into iCodcli
    from db_config;

  if bRaise then
    raise notice 'PARAMETROS fc_iptu_calculataxas  ANOUSU % -- CODCLI % ',iAnousu, iCodcli;
  end if;


  select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,bSubRaise)
     into bTaxasCalculadas;

  if bRaise then
    raise notice 'RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - %',bTaxasCalculadas;
  end if;

/* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,bSubRaise )
     into tDemo;

/* GERA FINANCEIRO */
  if bDemo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,bDemo,bSubRaise)
      into bDadosIptu;
      if bGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,bCalculogeral,bTempagamento,bNovonumpre,bDemo,bSubRaise)
          into bFinanceiro;
      end if;
  else
     return tDemo;
  end if;

  if bDemo is false then
     update iptucalc set j23_manual = tDemo where j23_matric = iMatricula and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$  language 'plpgsql';create or replace function fc_iptu_taxalixo_cap_2008(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    bRaise         alias for $6;

    nValTaxa       numeric default 0;
    nValorBase     numeric default 0;
    nVlrEdi        numeric default 0;
    nVlrTes        numeric default 0;
    nVlrMin        numeric default 0;
    n80PercViptu   numeric default 0;
    nViptu         numeric default 0;
    iCaract        integer default 0;
    iIdbql         integer default 0;
    iNparc         integer default 0;
    bPredial       boolean default false;
    tSql           text    default '';

    nTestada         numeric;
    nTotalAreaConstr numeric;
    nFracao          float8;
begin
    /* nValorBase - valref do cfiptu */

    /* SO CALCULA SE FOR PREDIAL */
    if bRaise then
        raise notice 'CALCULANDO TAXA DE COLETA DE LIXO ...';
        raise notice ' receita - % aliq - % historico - % raise - % ',iReceita,iAliquota,iHistCalc,bRaise;
    end if;

  -- verifica a faixa que se enquadra a taxa de lixo
  select j71_valor * (select min(i02_valor) from infla where infla.i02_codigo = 'UFM' and extract (year from infla.i02_data) = tmpdadostaxa.anousu), fracao, predial
    into nValTaxa, nFracao, bPredial
    from carvalor, tmpdadostaxa, tmpdadosiptu, carlote
    where carlote.j35_caract = carvalor.j71_caract and carlote.j35_idbql = tmpdadostaxa.idbql and
          carvalor.j71_anousu = tmpdadostaxa.anousu;
  if not found then
    return false;
  end if;

   if bRaise then
      raise notice '--area constr : % valor edi : % Testada % valor testada % valor minimo % valtaxa %',nTotalAreaConstr,nVlrEdi,nTestada,nVlrTes,nVlrMin,nValTaxa;
   end if;

  if bPredial is true then

     if iPercIsen > 0 then
       nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
     end if;

     if nValTaxa > 0 then
       insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

       tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';
       if bRaise then
         raise notice '%',tSql;
       end if;
       execute tSql;
       return true;
     else
       return false;
     end if;

  else
     return false;
  end if;

end;
$$ language 'plpgsql';create or replace function fc_iptu_taxalixo_capivari_2015(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita        alias for $1;
    iAliquota       alias for $2;
    iHistCalc       alias for $3;
    iPercIsen       alias for $4;
    nValpar         alias for $5;
    lRaise          alias for $6;

    nValTaxa        numeric default 0;
    nValorBase      numeric default 0;
    nVlrEdi         numeric default 0;
    nVlrTes         numeric default 0;
    nVlrMin         numeric default 0;
    n80PercViptu    numeric default 0;
    nViptu          numeric default 0;
    iNumConstr      integer default 0;
    iIdbql          integer default 0;
    iNparc          integer default 0;
    iCaracteristica integer default 0;
    lPredial        boolean default false;
    tSql            text    default '';
    iAnousu         integer default 0;
    valor           numeric;

    nTestada         numeric;
    nTotalAreaConstr numeric;
    nFracao          float8;
begin
  /* nValorBase - valref do cfiptu */


  /* SO CALCULA SE FOR PREDIAL */
  if lRaise then
      raise notice 'CALCULANDO TAXA DE COLETA DE LIXO ...';
      raise notice ' receita - % aliq - % historico - % raise - % ',iReceita,iAliquota,iHistCalc,lRaise;
  end if;

  select count(*)
   into iNumConstr
    from iptuconstr
         inner join tmpdadostaxa on j39_matric = tmpdadostaxa.matric
   where j39_dtdemo is null;


  if iNumConstr > 0 then
    lPredial := true;
  end if;

  if lRaise then
    raise notice 'PREDIAL %', lPredial;
  end if;

  select anousu
    into iAnousu
    from tmpdadostaxa
         inner join tmpdadosiptu on tmpdadostaxa.matric = tmpdadostaxa.matric;

  -- verifica a faixa que se enquadra a taxa de lixo
  select j71_valor * (select min(i02_valor) from infla where infla.i02_codigo = 'UFM' and extract (year from infla.i02_data) = tmpdadostaxa.anousu), j31_codigo
    into nValTaxa, iCaracteristica
    from tmpdadostaxa
         left  join carlote      on carlote.j35_idbql   = tmpdadostaxa.idbql
         left  join caracter     on caracter.j31_codigo = carlote.j35_caract
         left  join carvalor     on carlote.j35_caract  = carvalor.j71_caract
   where caracter.j31_grupo = 6;

  if (not found and lPredial) or iCaracteristica = 60 then
    return false;
  end if;

  select min(i02_valor) into valor from infla where infla.i02_codigo = 'UFM' and extract (year from infla.i02_data) = iAnousu;

  if lRaise then
    raise notice 'valorrrrrrr  %  anousu % ----- %', valor, iAnousu, iPercIsen;
  end if;


  if lPredial is false then
    nValTaxa = 25 * (select min(i02_valor) from infla where infla.i02_codigo = 'UFM' and extract (year from infla.i02_data) = iAnousu);
  end if;

  if iPercIsen > 0 then
   nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
  end if;

  if nValTaxa > 0 then
    insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);

    tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';
    if lRaise then
     raise notice '%',tSql;
    end if;
    execute tSql;
    return true;
  else
    return false;
  end if;

end;
$$ language 'plpgsql';drop function if exists fc_iptu_verificaparametros(integer, integer);
drop function if exists fc_iptu_verificaparametros(integer, integer, integer, integer);

drop   type if exists tp_iptu_parametros;
create type tp_iptu_parametros as (riIdbql   integer,
                                   rnAreal   numeric,
																   rnFracao  numeric,
																   riNumcgm  integer,
																   rdBaixa   date,
																   rtRetorno text,
																   rbErro    boolean);
/**
 * @deprecated
 * Removido campos parcelaini e parcelafim
 *
 * Utilizar fc_iptu_verificaparametros( iMatricula, iAnousu )
 */
create or replace function fc_iptu_verificaparametros(integer, integer, integer, integer) returns tp_iptu_parametros as
$$
declare

  iMatricula alias for $1;
  iAnousu    alias for $2;
  parcelaini alias for $3; -- N�o utilizada no escopo
  parcelafim alias for $4; -- N�o utilizada no escopo

  lRaise     boolean default false;

  rtp_Retorno tp_iptu_parametros%ROWTYPE;

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );

  rtp_Retorno.riIdbql   := 0::integer;
  rtp_Retorno.rnAreal   := 0::numeric;
  rtp_Retorno.rnFracao  := 0::numeric;
  rtp_Retorno.riNumcgm  := 0::integer;
  rtp_Retorno.rdBaixa   := null::date;
  rtp_Retorno.rtRetorno := ''::text;
  rtp_Retorno.rbErro    := false::boolean;

  select into rtp_Retorno
         j01_idbql     as iIdbql,

         case when j34_areal = 0
              then j34_area
         else j34_areal
         end           as nAreal,

         j34_totcon    as nFracao,
         j01_numcgm    as iNumcgm,
         j01_baixa     as dBaixa,
         ''::text      as tRetorno,
         true::boolean as bErro
   from  iptubase
         inner join lote          on j34_idbql  = j01_idbql
         left outer join iptufrac on j25_anousu = iAnousu
                                 and j25_matric = j01_matric
   where j01_matric = iMatricula;

   /**
    * "Logica" abaixo deve ser verificada
    */
   if rtp_Retorno.riIdbql is null then

      rtp_Retorno.rbErro    := true;
      rtp_Retorno.rtRetorno := '16 matricula nao cadastrada';
   end if;

   if rtp_Retorno.rnAreal = 0 or rtp_Retorno.rnAreal is null then

      rtp_Retorno.rbErro    := true;
      rtp_Retorno.rtRetorno := '03 area do lote zerada';
   end if;

   if not rtp_Retorno.rdBaixa is null then

      rtp_Retorno.rbErro    := true;
      rtp_Retorno.rtRetorno := '02 matricula baixada';
   end if;

   perform fc_debug ( '' ,lRaise);
   perform fc_debug (' <iptu_verificaparametros> IDBQL     - ' || rtp_Retorno.riIdbql   , lRaise);
   perform fc_debug (' <iptu_verificaparametros> AREAL     - ' || rtp_Retorno.rnAreal   , lRaise);
   perform fc_debug (' <iptu_verificaparametros> FRACAO    - ' || rtp_Retorno.rnFracao  , lRaise);
   perform fc_debug (' <iptu_verificaparametros> CGM       - ' || rtp_Retorno.riNumcgm  , lRaise);
   perform fc_debug (' <iptu_verificaparametros> DATABAIXA - ' || rtp_Retorno.rdBaixa   , lRaise);
   perform fc_debug (' <iptu_verificaparametros> ERRO      - ' || rtp_Retorno.rbErro    , lRaise);
   perform fc_debug (' <iptu_verificaparametros> RETORNO   - ' || rtp_Retorno.rtRetorno , lRaise);
   perform fc_debug ( '' ,lRaise);

   return rtp_Retorno;

end;

$$ language 'plpgsql';

/**
 * Wrapper para fc_iptu_verificaparametros original passando apenas matricula e anousu
 */
create or replace function fc_iptu_verificaparametros(integer, integer) returns tp_iptu_parametros as
$$
declare

    iMatricula  alias for $1;
    iAnousu     alias for $2;

    rRetorno    record;

begin

    for rRetorno in
      select * from fc_iptu_verificaparametros(iMatricula, iAnousu, 0, 0)
    loop
      return rRetorno;
    end loop;

end;
$$ language 'plpgsql';create or replace function fc_calculoiptu_guaiba_2015(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) returns varchar(100) as
$$

declare

   iMatricula           alias   for $1;
   iAnousu              alias   for $2;
   lGerafinanceiro      alias   for $3;
   lAtualizaParcela     alias   for $4;
   lNovonumpre          alias   for $5;
   lCalculogeral        alias   for $6;
   lDemonstrativo       alias   for $7;
   iParcelaini          alias   for $8;
   iParcelafim          alias   for $9;

   iIdbql               integer default 0;
   iNumcgm              integer default 0;
   iCodcli              integer default 0;
   iCodisen             integer default 0;
   iTipois              integer default 0;
   iParcelas            integer default 0;
   iNumconstr           integer default 0;
   iZona                integer default 0;

   dDatabaixa           date;

   nAreal               numeric default 0;
   nAreac               numeric default 0;
   nTotarea             numeric default 0;
   nFracao              numeric default 0;
   nFracaolote          numeric default 0;
   nAliquota            numeric default 0;
   nRedutor             numeric default 0;
   nIsenaliq            numeric default 0;
   nArealo              numeric default 0;
   nVvc                 numeric(15,2) default 0;
   nVvt                 numeric(15,2) default 0;
   nVv                  numeric(15,2) default 0;
   nViptu               numeric(15,2) default 0;
   nValorMaxAnoAnterior numeric(15,2) default 0;

   tRetorno              text default '';
   tDemo                 text default '';

   bFinanceiro           boolean;
   bDadosIptu            boolean;
   lErro                 boolean;
   iCodErro              integer;
   tErro                 text;
   lIsentaxas            boolean;
   lTempagamento         boolean;
   lEmpagamento          boolean;
   bTaxasCalculadas      boolean;
   bDescontoRegular      boolean;
   nFatorComercializacao numeric;
   lRaise                boolean default false;
   iContador             integer default 0;

   rCfiptu              record;
   rCarConstr           record;

begin

  lRaise    := ( case when fc_getsession('DB_debugon') is null then false else true end );
  lRaise    := true;

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || lTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || lEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || lIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);

 /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  /**
   * Guarda os parametros do calculo
   */
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  /**
   * Calcula valor do terreno
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvt_guaiba_2015 Anousu: '||iAnousu||' - IDBQL: '||iIdbql||' - FRACAO DO LOTE: '||nFracaolote||' DEMO: '||lDemonstrativo||'- j18_vlrref: '||rCfiptu.j18_vlrref::numeric, lRaise);

  select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
    into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvt_guaiba_2015( iAnousu ,iIdbql, nFracaolote, rCfiptu.j18_vlrref::numeric, lDemonstrativo, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvt_guaiba_2015 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
    return tRetorno;
  end if;

  /**
   * Calcula valor da construcao
   */
  perform fc_debug('PARAMETROS fc_iptu_calculavvc_guaiba_2015 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu, lRaise);

  select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rlerro, riCodErro, rtErro
    into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
    from fc_iptu_calculavvc_guaiba_2015( iMatricula, iAnousu, lRaise );

  perform fc_debug('RETORNO fc_iptu_calculavvc_guaiba_2015 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRU��ES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  if nVvc is null or nVvc = 0 and iNumconstr <> 0 then

    select fc_iptu_geterro(22, '') into tRetorno;
    return tRetorno;
  end if;

  /**
   * Busca o fator de comercializa�ao do imovel
   */
  perform fc_debug('PARAMETROS fc_iptu_get_fator_comercializacao_guaiba_2015 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu, lRaise);

  select rnFatorComercializacao, rlerro, riCodErro, rtErro
    into nFatorComercializacao, lErro, iCodErro, tErro
    from fc_iptu_get_fator_comercializacao_guaiba_2015( iMatricula, iAnousu, lRaise );

  perform fc_debug('RETORNO fc_iptu_get_fator_comercializacao_guaiba_2015 -> nFatorComercializacao '||nFatorComercializacao, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;

  nVv := (nVvc + nVvt) * nFatorComercializacao;

  perform fc_debug('nVv := (nVvc + nVvt) * nFatorComercializacao', lRaise);
  perform fc_debug('nVv := ('||nVvc||' + '||nVvt||') * '||nFatorComercializacao, lRaise);
  perform fc_debug('nVv  = '||nVv, lRaise);

  /**
   * Busca a aliquota e redutor
   */
  if iNumconstr is not null and iNumconstr > 0 then

    perform fc_debug('PARAMETROS fc_iptu_getaliquotaredutor_guaiba_2015 iIdbql: '||iIdbql||' Predial - '||true||' - ANOUSU:'||iAnousu||' nVv - '||nVv, lRaise);

    select rnAliquota, rnRedutor
      into nAliquota, nRedutor
      from fc_iptu_getaliquotaredutor_guaiba_2015(iIdbql, true, iAnousu, nVv, lRaise );
  else

    perform fc_debug('PARAMETROS fc_iptu_getaliquotaredutor_guaiba_2015 iIdbql: '||iIdbql||' Predial - '||false||' - ANOUSU:'||iAnousu||' nVv - '||nVv, lRaise);

    select rnAliquota, rnRedutor
      into nAliquota, nRedutor
      from fc_iptu_getaliquotaredutor_guaiba_2015(iIdbql, false, iAnousu, nVv, lRaise );
  end if;

  perform fc_debug('RETORNO fc_iptu_getaliquotaredutor_guaiba_2015 -> nAliquota '||nAliquota||' nRedutor '||nRedutor, lRaise);
  perform fc_debug('', lRaise);

  if lErro is true then

    select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
    return tRetorno;
  end if;


  perform fc_debug('nVv := nVv * nAliquota / 100', lRaise);
  perform fc_debug('nVv := '||nVv||' * '||nAliquota|| ' / 100', lRaise);
  nVv := nVv * nAliquota / 100;
  perform fc_debug('nVv = '||nVv, lRaise);

  perform fc_debug('nViptu := nVv - nRedutor', lRaise);
  perform fc_debug('nViptu := '||nVv||' - '||nRedutor, lRaise);

  nViptu := nVv - nRedutor;

  /**
   * Verificamos se todas as constru�oes estao regulares para que possamos aplicar
   * o desconto de 20% ao valor total do IPTU
   */

  bDescontoRegular := false;
  if iNumconstr <> 0 then

    for rCarConstr in select j48_caract
                        from carconstr
                             inner join caracter on j48_caract = j31_codigo
                             inner join iptuconstr on j48_matric = j39_matric
                                                  and j39_idcons = j48_idcons
                       where j48_matric = iMatricula
                         and j39_dtdemo is null
                         and j31_grupo  = 58 loop
      /**
       * Adicionamos um contador ao for para verificarmos quantas caracteristas do grupo 52
       * foram informadas para esta matricula
       */
      iContador := iContador + 1;
      if rCarConstr.j48_caract = 30168 then

        bDescontoRegular := false;
        exit;
      elsif rCarConstr.j48_caract = 30167 then
        bDescontoRegular := true;
      end if;
    end loop;
  end if;

  /**
   * Caso o n�mero do contador de caracteristicas for inferior ao de constru��es, significa que
   * h� pelo menos uma constru��o que n�o fora informada a sua condi��o(grupo 52), portanto n�o
   * reber� desconto
   */
  if iContador < iNumconstr then
    bDescontoRegular := false;
  end if;

  if bDescontoRegular then

    perform fc_debug('MATRICULA REGULAR', lRaise);
    perform fc_debug('nViptu := '||nViptu||' * 0.8', lRaise);
    nViptu := nViptu * 0.8;
  end if;

  perform fc_debug('nViptu : ' || nViptu, lRaise);

  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim;

  if not found or iParcelas = 0 then

    select fc_iptu_geterro(14, '') into tRetorno;
    return tRetorno;
  end if;

  perform predial from tmpdadosiptu where predial is true;
  if found then
    insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
  else
    insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
  end if;

  update tmpdadosiptu
     set viptu = nViptu, codvenc = rCfiptu.j18_vencim;

  update tmpdadostaxa
     set anousu  = iAnousu,
         matric  = iMatricula,
         idbql   = iIdbql,
         valiptu = nViptu,
         valref  = rCfiptu.j18_vlrref,
         vvt     = nVvt,
         nparc   = iParcelas;

  perform fc_debug('PARAMETROS fc_iptu_calculataxas ANOUSU: '||iAnousu||' - CODCLI: '||iCodcli, lRaise);

  /**
   * Calcula as taxas
   */
  select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, false)
    into bTaxasCalculadas;

  perform fc_debug('RETORNO fc_iptu_calculataxas -> TAXASCALCULADAS: ' || bTaxasCalculadas, lRaise);

  /**
   * Monta o demonstrativo
   */
  select fc_iptu_demonstrativo(iMatricula, iAnousu, iIdbql, false)
    into tDemo;

  /**
   * Gera financeiro
   *  -> Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
   */
  if lDemonstrativo is false then

    select fc_iptu_geradadosiptu(iMatricula, iIdbql, iAnousu, nIsenaliq, lDemonstrativo, false)
      into bDadosIptu;

      if lGerafinanceiro then

        select fc_iptu_gerafinanceiro( iMatricula, iAnousu, iParcelaini, iParcelafim, lCalculogeral, lTempagamento, lNovonumpre, lDemonstrativo, false )
          into bFinanceiro;
      end if;
  else
     return tDemo;
  end if;

  if lDemonstrativo is false then

     update iptucalc
        set j23_manual = tDemo
      where j23_matric = iMatricula
        and j23_anousu = iAnousu;
  end if;

  perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise, false, true);

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;
$$ language 'plpgsql';create or replace function fc_iptu_taxalimpeza_guaiba_2015(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita          alias for $1;
    iAliquota         alias for $2;
    iHistCalc         alias for $3;
    iPercIsen         alias for $4;
    nValpar           alias for $5;
    lRaise            alias for $6;

    nValTaxa          numeric default 0;
    nValorBase        numeric default 0;
    nFator            numeric default 0;

    iCaracter         integer default 0;
    iIdbql            integer default 0;
    iNparc            integer default 0;
    iAnousu           integer default 0;
    iZona             integer default 0;
    nLimpeza          numeric default 0;
    nTestada          numeric default 0;
    iCartaxa          integer default 0;
    nAreaTotConstr    numeric default 0;
    iMatricula        integer;

    bPredial          boolean default false;
    tSql              text    default '';

    rCarConstr        record;
    rCarLote          record;
    rConstr           record;
    rIDConstr         record;

    lPavimentacao     boolean default false;
    iCarPavimentacao  integer default 0;

    iCarMuro          integer default 0;

    nTaxaLimpAliq     numeric(15,2) default 0;
    nVlrTaxaLimp      numeric(15,2) default 0;
    nValInflator      numeric(15,2) default 0;
    iTaxaLimpCar      integer default 0;
    iCarTipoOcupacao  integer default 0;

begin

    perform fc_debug('CALCULANDO TAXA DE COLETA DE Limpeza ...',lRaise,false,false);
    perform fc_debug('receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistCalc,lRaise,false,false);

    select predial
      into bPredial
      from tmpdadosiptu;

    select anousu,
           idbql,
           matric
    into iAnousu,
         iIdbql,
         iMatricula
    from tmpdadostaxa;

    if not found then
      return false;
    end if;

    tSql := ' select distinct on (iptuconstr.j39_matric, j39_idcons)
                     iptuconstr.j39_matric,
                     j39_idcons,
                     j39_ano,
                     j39_area::numeric
              from iptuconstr
              where iptuconstr.j39_dtdemo is null
              and iptuconstr.j39_idprinc is true
              and iptuconstr.j39_matric = '||iMatricula;

    for rConstr in execute tSql loop

      if iCarTipoOcupacao = 0 then -- se ainda nao encontrou caracteristica

        for rIDConstr in select j48_caract,j31_pontos::float8, j31_grupo, j31_descr, j32_descr
          from carconstr
          inner join caracter on j48_caract = j31_codigo
          inner join cargrup  on j31_grupo = j32_grupo
          where j48_matric = rConstr.j39_matric and j48_idcons = rConstr.j39_idcons loop

          if rIDConstr.j31_grupo = 24 then

            if rIDConstr.j48_caract in (100, 102, 104, 124, 125, 126, 132, 133, 134, 165, 166, 167, 189, 201) then
              iCarTipoOcupacao = 32; -- residencial
            elsif rIDConstr.j48_caract in (101, 207, 103, 105, 109, 128, 136, 151, 190, 204, 205, 206) then
              iCarTipoOcupacao = 33; -- comercial
            elsif rIDConstr.j48_caract in (106, 107, 127, 156, 160) then
              iCarTipoOcupacao = 34; -- industrial
            end if;

          end if;

        end loop;

      end if;

    end loop;

    for rCarLote in select j35_caract, j31_grupo, j31_descr, j32_descr, extract (year from j35_dtlanc) as j35_anolanc
      from carlote
      inner join caracter on j31_codigo = j35_caract
      inner join cargrup  on j32_grupo = j31_grupo
      where j35_idbql = iIdbql loop

      if rCarLote.j31_grupo = 4 then
        iCarPavimentacao = rCarLote.j35_caract;
        lPavimentacao = true;
      end if;

      if rCarLote.j31_grupo = 31 then
        iCarMuro = rCarLote.j35_caract;
      end if;

    end loop;

    if lPavimentacao is false then
      select j38_caract
      into iCaracter
      from carface
      inner join testpri on j49_face = j38_face
      inner join caracter on j31_codigo = j38_caract
      where j31_grupo = 33 and j49_idbql = iIdbql;
      if iCaracter in (199, 152, 153) then -- com
        iCarPavimentacao = 7;
      elsif iCaracter = 200 then -- sem
        iCarPavimentacao = 8;
      end if;
    end if;

    if bPredial is false and iCarPavimentacao = 7 and iCarMuro = 142 then
      -- se territorial and com pavimentacao and sem muro
      iTaxaLimpCar = 37;
    elsif bPredial is false and iCarPavimentacao = 8 then
      -- se territorial and sem pavimentacao
      iTaxaLimpCar = 38;
    elsif bPredial is true and iCarTipoOcupacao in (33, 34) then
      -- se predial and tipo de ocupacao igual a comercio ou industria
      iTaxaLimpCar = 41;
    elsif bPredial is true and iCarPavimentacao = 7 then
      -- se predial and com pavimentacao
      iTaxaLimpCar = 39;
    elsif bPredial is true and iCarPavimentacao = 8 then
      -- se predial and sem pavimentacao
      iTaxaLimpCar = 40;
    elsif bPredial is false and iCarPavimentacao = 7 and iCarMuro = 141 then
      -- se territorial and com pavimentacao and com muro
      iTaxaLimpCar = 42;
    -- O QUE SIGNIFICA TERRITORIAL DA SEGUNDA DIVISAO
    end if;

    if iTaxaLimpCar is null or iTaxaLimpCar = 0 then
      return false;
    end if;

    select j73_aliq
    into nTaxaLimpAliq
    from caraliq
    where j73_anousu = iAnousu and
    j73_caract = iTaxaLimpCar;

    select distinct i02_valor
    from cfiptu
    into nValinflator
    inner join infla on j18_infla = i02_codigo
    where cfiptu.j18_anousu = iAnousu
    and date_part('Y',i02_data) = iAnousu;
    if nValinflator is null or nValinflator = 0 then
      nValInflator = 1;
    end if;

    if nTaxaLimpAliq is not null then
      nVlrTaxaLimp = nValInflator * nTaxaLimpAliq;
    end if;

    insert into tmptaxapercisen values (iReceita,iPercIsen,0,nVlrTaxaLimp);

    if iPercIsen > 0 then
       nVlrTaxaLimp := nVlrTaxaLimp * (100 - iPercIsen) / 100;
    end if;

    if lRaise is true or 1=2 then
      raise notice 'bPredial: % - iCarPavimentacao: % - iCarMuro: % - iCarTipoOcupacao: % - iTaxaLimpCar: % - nTaxaLimpAliq: % - nValInflator: % - nVlrTaxaLimpa % - iPercIsen: %', bPredial, iCarPavimentacao, iCarMuro, iCarTipoOcupacao, iTaxaLimpCar, nTaxaLimpAliq, nValInflator, nVlrTaxaLimp, iPercIsen;
    end if;

    tSql := 'insert into tmprecval values ('||iReceita||','||nVlrTaxaLimp||','||iHistCalc||',true)';
    execute tSql;

    return true;

end;
$$ language 'plpgsql';create or replace function fc_iptu_taxalixo_guaiba_2015(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita       alias for $1;
    iAliquota      alias for $2;
    iHistCalc      alias for $3;
    iPercIsen      alias for $4;
    nValpar        alias for $5;
    lRaise         alias for $6;


    nValTaxa       numeric default 0;
    nValorBase     numeric default 0;
    nFator         numeric default 0;

    iCaract        integer default 0;
    iIdbql         integer default 0;
    iNparc         integer default 0;
    iAnousu        integer default 0;
    iZona          integer default 0;
    nLimpeza       numeric default 0;
    nTestada       numeric default 0;
    iCartaxa       integer default 0;
    nAreaTotConstr numeric default 0;
    iMatricula     integer;

    bPredial       boolean default false;
    tSql           text    default '';

    rCarConstr    record;
    rCarLote      record;
    rConstr       record;
    rIDConstr     record;

    nTaxaLixoAliq numeric(15,2) default 0;
    nVlrTaxaLixo  numeric(15,2) default 0;
    nValInflator  numeric(15,2) default 0;
    iTaxaLixoCar  integer default 0;

begin

    perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO ...',lRaise,false,false);
    perform fc_debug('receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistCalc,lRaise,false,false);

    select predial
      into bPredial
      from tmpdadosiptu;

    select anousu,
           idbql,
           matric
    into iAnousu,
         iIdbql,
         iMatricula
    from tmpdadostaxa;

    if not found then
      return false;
    end if;

    if bPredial is true then

      tSql := ' select distinct on (iptuconstr.j39_matric, j39_idcons)
                       iptuconstr.j39_matric,
                       j39_idcons,
                       j39_ano,
                       j39_area::numeric
                from iptuconstr
                where iptuconstr.j39_dtdemo is null
                and iptuconstr.j39_idprinc is true
                and iptuconstr.j39_matric = '||iMatricula;

      for rConstr in execute tSql loop

        if iTaxaLixoCar = 0 then -- se ainda nao encontrou caracteristica

          for rIDConstr in select j48_caract,j31_pontos::float8, j31_grupo, j31_descr, j32_descr
            from carconstr
            inner join caracter on j48_caract = j31_codigo
            inner join cargrup  on j31_grupo = j32_grupo
            where j48_matric = rConstr.j39_matric and j48_idcons = rConstr.j39_idcons loop

            if rIDConstr.j31_grupo = 24 then

              if rIDConstr.j48_caract in (100, 102, 104, 124, 125, 126, 132, 133, 134, 165, 166, 167, 189, 201) then
                iTaxaLixoCar = 32; -- residencial
              elsif rIDConstr.j48_caract in (101, 207, 103, 105, 109, 128, 136, 151, 190, 204, 205, 206) then
                iTaxaLixoCar = 33; -- comercial
              elsif rIDConstr.j48_caract in (106, 107, 127, 156, 160) then
                iTaxaLixoCar = 34; -- industrial
              end if;

            end if;

          end loop;

        end if;

      end loop;

      if iTaxaLixoCar is null or iTaxaLixoCar = 0 then
        return false;
      end if;

      select j73_aliq
      into nTaxaLixoAliq
      from caraliq
      where j73_anousu = iAnousu and
      j73_caract = iTaxaLixoCar;

      select distinct i02_valor
      from cfiptu
      into nValinflator
      inner join infla on j18_infla = i02_codigo
      where cfiptu.j18_anousu = iAnousu
      and date_part('Y',i02_data) = iAnousu;
      if nValinflator is null or nValinflator = 0 then
        nValInflator = 1;
      end if;

      if nTaxaLixoAliq is not null then
        nVlrTaxaLixo = nValInflator * nTaxaLixoAliq;
      end if;

      insert into tmptaxapercisen values (iReceita,iPercIsen,0,nVlrTaxaLixo);

      if iPercIsen > 0 then
         nVlrTaxaLixo := nVlrTaxaLixo * (100 - iPercIsen) / 100;
      end if;

      perform fc_debug('nValinflator: '||nValinflator||' - nTaxaLixoAliq: '||nTaxaLixoAliq||', iTaxaLixoCar: '||iTaxaLixoCar||' - iPercIsen: '||iPercIsen||' - nVlrTaxaLixo: '||nVlrTaxaLixo, lRaise);

      tSql := 'insert into tmprecval values ('||iReceita||','||nVlrTaxaLixo||','||iHistCalc||',true)';
      execute tSql;

    end if;

    return true;

end;
$$ language 'plpgsql';create or replace function fc_iptu_calculavvc_guaiba_2015( iMatricula      integer,
                                                           iAnousu         integer,
                                                           lRaise          boolean,

                                                           OUT rnVvc       numeric(15,2),
                                                           OUT rnTotarea   numeric,
                                                           OUT riNumconstr integer,
                                                           OUT rtDemo      text,
                                                           OUT rtMsgerro   text,
                                                           OUT rlErro      boolean,
                                                           OUT riCodErro   integer,
                                                           OUT rtErro      text
                                                          ) returns record as
$$
declare

    iMatricula     alias for $1;
    iAnousu        alias for $2;
    lRaise         alias for $3;

    nValorVenalTotal     numeric(15,2) default 0;
    iNumeroedificacoes   integer default 0;
    nValorVenal          numeric;
    rFatorDepreciacao    record;
    rValorM2             record;
    lEdificacao          boolean;
    nAreaconstr          numeric(15,2) default 0;
    lMatriculaPredial    boolean;

    tSqlConstr           text    default '';
    lAtualiza            boolean default true;
    rConstr              record;

begin

    perform fc_debug('', lRaise);
    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('* INICIANDO CALCULO DO VALOR VENAL DA CONSTRUCAO', lRaise);

    rnVvc       := 0;
    rnTotarea   := 0;
    riNumconstr := 0;
    rtDemo      := '';
    rtMsgerro   := 'Retorno ok';
    rlErro      := 'f';
    riCodErro   := 0;
    rtErro      := '';

    tSqlConstr :=               ' select * ';
    tSqlConstr := tSqlConstr || '  from iptuconstr';
    tSqlConstr := tSqlConstr || ' where j39_matric = ' || iMatricula;
    tSqlConstr := tSqlConstr || '   and j39_dtdemo is null';

    perform fc_debug('Select buscando as contrucoes : ' || tSqlConstr, lRaise);

    for rConstr in execute tSqlConstr loop

      if rConstr.j39_area is null or rConstr.j39_area = 0 then

        rlErro    := true;
        riCodErro := 112;
        rtErro    := '';
        return;
      end if;

      lEdificacao := true;

      perform fc_debug('FATOR DE DEPRECIACAO', lRaise);
      rFatorDepreciacao := fc_iptu_get_fator_depreciacao_guaiba_2015( iMatricula, rConstr.j39_idcons, iAnousu, lRaise);
      perform fc_debug('MATRICULA : ' || iMatricula || ' IDCONSTR: ' || rConstr.j39_idcons || 'VALOR: ' || rFatorDepreciacao.rnFatorDepreciacao, lRaise);

      if rFatorDepreciacao.rlErro then

        rlErro    := true;
        riCodErro := rFatorDepreciacao.riCodErro;
        rtErro    := rFatorDepreciacao.rtErro;
        return;
      end if;

      perform fc_debug('VALOR DO METRO QUADRADO', lRaise);

      rValorM2 := fc_iptu_get_valor_metro_quadrado_guaiba_2015( iMatricula, rConstr.j39_idcons, iAnousu, lRaise);
      perform fc_debug('MATRICULA : ' || iMatricula || ' IDCONSTR: ' || rConstr.j39_idcons || 'VALOR: ' || rFatorDepreciacao.rnFatorDepreciacao, lRaise);

      if rValorM2.rlErro then

        rlErro    := true;
        riCodErro := rValorM2.riCodErro;
        rtErro    := rValorM2.rtErro;
        return;
      end if;

      perform fc_debug(' VVC usando formula: ( rConstr.j39_area * rValorM2 * nFatorDepreciacao )', lRaise);
      perform fc_debug('  -> Valores: ( '||rConstr.j39_area||' * '||rValorM2.rnVm2||' * '||rFatorDepreciacao.rnFatorDepreciacao||' )', lRaise);

      nValorVenal        := ( rConstr.j39_area * rValorM2.rnVm2 * rFatorDepreciacao.rnFatorDepreciacao );
      perform fc_debug(' VVC : '||coalesce(nValorVenal,0),lRaise);

      nValorVenalTotal   := nValorVenalTotal + nValorVenal;
      perform fc_debug(' VVC total: '||coalesce(nValorVenalTotal,0),lRaise);

      nAreaconstr        := nAreaconstr + rConstr.j39_area;
      perform fc_debug('Area Construida: ' || coalesce(nAreaconstr,0),lRaise);
      iNumeroedificacoes := iNumeroedificacoes + 1;

      insert into tmpiptucale (anousu, matric,idcons,areaed,vm2,pontos,valor,edificacao)
               values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, rValorM2.rnVm2, 0, nValorVenal, lEdificacao);
      if lAtualiza then

        update tmpdadosiptu set predial = true;
        lAtualiza = false;
      end if;

    end loop;

    perform matric
       from tmpiptucale
    where edificacao is true;

    if found then
      lMatriculaPredial = true;
    else
      lMatriculaPredial = false;
    end if;

    if lMatriculaPredial is true then

      rnVvc       := nValorVenalTotal;
      rnTotarea   := nAreaconstr;
      riNumconstr := iNumeroedificacoes;
      rtDemo      := '';
      rlErro      := 'f';

      update tmpdadosiptu set vvc = rnVvc;
    else

      delete from tmpiptucale;
      update tmpdadosiptu set predial = false;
    end if;

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('', lRaise);

  return;

end;
$$  language 'plpgsql';create or replace function fc_iptu_calculavvt_guaiba_2015( integer, integer, numeric, numeric, boolean, boolean,
                                                           OUT rnVvt        numeric(15,2),
                                                           OUT rnAreaTotalC numeric,
                                                           OUT rnArea       numeric,
                                                           OUT rnTestada    numeric,
                                                           OUT rtDemo       text,
                                                           OUT rtMsgerro    text,
                                                           OUT rbErro       boolean,
                                                           OUT riCoderro    integer,
                                                           OUT rtErro       text ) returns record as
$$
declare

    iAnousu        alias for $1;
    iIdbql         alias for $2;
    nFracao        alias for $3;
    nVlrref        alias for $4;
    lDemonstrativo alias for $5;
    lRaise         alias for $6;

    rnArealote                    numeric;
    rnAreaCorrigida               numeric;
    rnVm2terreno                  numeric;
    nFatorSituacao                numeric;
    nFatorPedologia               numeric;
    nFatorTopografia              numeric;
    nFatorNivel                   numeric;
    nFatorGleba                   numeric;
    nFatorDepreciacaoProfundidade numeric;

begin

    rnVvt        := 0;
    rnAreaTotalC := 0;
    rnArea       := 0;
    rnTestada    := 0;
    rtDemo       := '';
    rtMsgerro    := '';
    rbErro       := 'f';
    riCoderro    := 0;
    rtErro       := '';

    perform fc_debug('' || lpad('',60,'-'), lRaise);
    perform fc_debug('* INICIANDO CALCULO DO VALOR VENAL TERRITORIAL',lRaise);

    select case when sum(j34_areal) = 0
                then sum(j34_area)
                else sum(j34_areal)  end
           into rnArealote
      from lote
           inner join testpri on j49_idbql  = j34_idbql
           inner join testada on j36_idbql  = j49_idbql
                             and j36_face   = j49_face
                             and j36_codigo = j49_codigo
           inner join face    on j37_face   = j49_face
     where j34_idbql = iIdbql;

    if rnArealote is null then

      /**
       * Testada principal nao cadastrada
       */
      rbErro    := 't';
      riCodErro := 6;
      rtErro    := '';
      return;
    end if;

    perform fc_debug('AREA REAL DO LOTE: ' || rnArealote,lRaise);

    select coalesce( j81_valorterreno, 0 )
      into rnVm2terreno
      from facevalor fv
           inner join testpri tp on fv.j81_face = tp.j49_face
     where j81_anousu    = iAnousu
       and tp.j49_idbql  = iIdbql;

    if not found or rnVm2terreno = 0 then

      /**
       * Nao encontrado valor do M2 do terreno para face (facevalor)
       */
      rbErro    := true;
      riCodErro := 25;
      rtErro    := '';
      return;
    end if;

    perform fc_debug('nVlrM2Terreno = ' || rnVm2terreno, lRaise);

    rnAreaCorrigida := ( rnArealote * ( nFracao / 100 ) );
    rnArea          := rnAreaCorrigida;
    perform fc_debug('rnAreaCorrigida = ' || rnAreaCorrigida, lRaise);

    /**
     * Fator de Deprecia��o de Profundidade
     */
    nFatorDepreciacaoProfundidade := ( select fc_iptu_get_fator_profundidade_guaiba_2015( iIdbql, iAnousu, lRaise ) );
    if nFatorDepreciacaoProfundidade = 0 then

      rbErro    := true;
      riCodErro := 111;
      rtErro    := '';
      return;
    end if;

    /**
     * Fator de Situa��o
     */
    nFatorSituacao                := ( select fc_iptu_getfatorcarlote( 50, iIdbql, iAnousu ) );
    if nFatorSituacao = 0 then

      rbErro    := true;
      riCodErro := 101;
      rtErro    := '50 - FATOR DE SITUACAO';
      return;
    end if;

    /**
     * Fator de Topografia
     */
    nFatorTopografia              := ( select fc_iptu_getfatorcarlote( 48, iIdbql, iAnousu ) );
    if nFatorTopografia = 0 then

      rbErro    := true;
      riCodErro := 101;
      rtErro    := '48 - FATOR DE TOPOGRAFIA';
      return;
    end if;

    /**
     * Fator de Pedologia
     */
    nFatorPedologia               := ( select fc_iptu_getfatorcarlote( 51, iIdbql, iAnousu ) );
    if nFatorPedologia = 0 then

      rbErro    := true;
      riCodErro := 101;
      rtErro    := '51 - FATOR DE PEDOLOGIA';
      return;
    end if;

    /**
     * Fator de Gleba
     */
    nFatorGleba                   := ( select fc_iptu_getfatorgleba_guaiba_2015( iIdbql ) );

    -- VVT :=  At x Vm2 x Fp x Fs x Ft x Fpe X Gleba
    rnVvt := ( rnAreaCorrigida               *
               rnVm2terreno                  *
               nFatorDepreciacaoProfundidade *
               nFatorSituacao                *
               nFatorTopografia              *
               nFatorPedologia               *
               nFatorGleba );

    perform fc_debug('Calculando VVT utilizando formula: VVT :=  At x Vm2 x Fp x Fs x Ft x Fpe X Gleba',     lRaise);
    perform fc_debug(' -> Valores: VVT := '||rnAreaCorrigida||' x '||rnVm2terreno||' x '||nFatorDepreciacaoProfundidade||' x '||nFatorSituacao||' x '||nFatorTopografia||' x '||nFatorPedologia||' x '||nFatorGleba, lRaise);
    perform fc_debug('AREA CORRIG BRUTA (RAIZ QUADRADA DA PROFUNDIDADE): ' || rnAreaCorrigida,               lRaise);
    perform fc_debug('VALOR METRO QUADRADO DO TERRENO:                   ' || rnVm2terreno,                  lRaise);
    perform fc_debug('FATOR DEPRECIACAO PROFUNDIDADE:                    ' || nFatorDepreciacaoProfundidade, lRaise);
    perform fc_debug('FATOR SITUACAO:                                    ' || nFatorSituacao,                lRaise);
    perform fc_debug('FATOR TOPOGRAFIA:                                  ' || nFatorTopografia,              lRaise);
    perform fc_debug('FATOR PEDOLOGIA:                                   ' || nFatorPedologia,               lRaise);
    perform fc_debug('FATOR GLEBA:                                       ' || nFatorGleba,                   lRaise);
    perform fc_debug('VALOR VENAL TERRENO:                               ' || rnVvt,                         lRaise);

    update tmpdadosiptu set vvt = rnVvt, vm2t = rnVm2terreno, areat = rnAreaCorrigida;

    perform fc_debug('' || lpad('',60,'-'), lRaise);

    return;

end;
$$  language 'plpgsql';create or replace function fc_iptu_getaliquotaredutor_guaiba_2015( iIdbql         integer,
                                                                   lPredial       boolean,
                                                                   iAnousu        integer,
                                                                   nVlrVenal      numeric(15,2),
                                                                   lRaise         boolean,

                                                                   OUT rnAliquota numeric,
                                                                   OUT rnRedutor  numeric) returns record as

$$
declare

    iIdbql               alias for $1;
    lPredial             alias for $2;
    iAnousu              alias for $3;
    nVlrVenal            alias for $4;
    lRaise               alias for $5;

    iCarPavimentacao     integer default 0;
    iCarMuro             integer default 0;
    iCarPasseio          integer default 0;
    iAnoPasseio          integer;
    iAnoMuro             integer;
    iAnoCaracteristica   integer;
    iAnoAliquota         integer;

    rCarLote             record;

begin

  rnAliquota := 1.00;
  rnRedutor  := 0;

  /* EXECUTAR SOMENTE SE NAO TIVER ISENCAO */
  perform fc_debug('DEFININDO QUAL ALIQUOTA APLICAR ...', lRaise);
  perform fc_debug('IPTU : '|| case when lPredial is true then 'PREDIAL' else 'TERRITORIAL' end, lRaise);
  perform fc_debug('VALOR VENAL '||nVlrVenal, lRaise);

  /**
   * Definimos o valor da aliquota e do redutor de acordo com o VVT
   */
  if lPredial then

    if    nVlrVenal <= 150000.00                           then rnAliquota := 0.15;
    elsif nVlrVenal > 150000.00 and nVlrVenal <= 230000.00 then rnAliquota := 0.30; rnRedutor := 225.00;
    elsif nVlrVenal > 230000.00 and nVlrVenal <= 320000.00 then rnAliquota := 0.50; rnRedutor := 460.00;
    elsif nVlrVenal > 320000.00 and nVlrVenal <= 500000.00 then rnAliquota := 0.60; rnRedutor := 320.00;
    elsif nVlrVenal > 500000.00 and nVlrVenal <= 750000.00 then rnAliquota := 0.70; rnRedutor := 500.00;
    elsif nVlrVenal > 750000.00                            then rnAliquota := 0.80; rnRedutor := 750.00;
    end if;
  else

    if    nVlrVenal <= 80000.00                            then rnAliquota := 0.50;
    elsif nVlrVenal >  80000.00 and nVlrVenal <= 230000.00 then rnAliquota := 0.75; rnRedutor := 200.00;
    elsif nVlrVenal > 230000.00 and nVlrVenal <= 320000.00 then rnAliquota := 0.90; rnRedutor := 365.00;
    elsif nVlrVenal > 320000.00 and nVlrVenal <= 500000.00 then rnAliquota := 1.00; rnRedutor := 320.00;
    elsif nVlrVenal > 500000.00 and nVlrVenal <= 750000.00 then rnAliquota := 1.10; rnRedutor := 500.00;
    elsif nVlrVenal > 750000.00                            then rnAliquota := 1.20; rnRedutor := 750.00;
    end if;
  end if;

  perform fc_debug('rnRedutor '||rnRedutor||' rnAliquota '||rnAliquota, lRaise);

  /**
   * Consultamos as caracteristicas de muro, passeio e pavimenta�ao do imovel para determinar
   * a regra de negocio a ser usada na aliquota
   */
  for rCarLote in select j31_codigo, j31_grupo, extract(year from j35_dtlanc) as j35_dtlanc
                    from caracter
                         inner join carlote on j35_caract = j31_codigo
                   where j35_idbql = iIdbql
                     and j31_grupo in (30, 31, 4) loop

    if rCarLote.j31_grupo = 4 then
      iCarPavimentacao := rCarLote.j31_codigo;
    elsif rCarLote.j31_grupo = 30 then

      iCarPasseio := rCarLote.j31_codigo;
      iAnoPasseio := rCarLote.j35_dtlanc;
    elsif rCarLote.j31_grupo = 31 then

      iCarMuro := rCarLote.j31_codigo;
      iAnoMuro := rCarLote.j35_dtlanc;
    end if;
  end loop;

  perform fc_debug('iCarPavimentacao '||iCarPavimentacao, lRaise);
  perform fc_debug('iCarPasseio '     ||iCarPasseio, lRaise);
  perform fc_debug('iAnoPasseio '     ||iAnoPasseio, lRaise);
  perform fc_debug('iCarMuro '        ||iCarMuro, lRaise);
  perform fc_debug('iAnoMuro '        ||iAnoMuro, lRaise);


  /**
   * Se qualquer uma das caracter�sticas n�o for informada, n�o ser� aplicada nenhuma corre��o sobre a al�quota
   */
  if iCarPavimentacao != 0 and iCarPasseio != 0 and iCarMuro != 0 then

    if iCarPavimentacao = 7 then

      /**
       * Caso o imovel nao tenha passeio ou muro, a aliquota sera acrescida em 0.10%
       * e mais 0.05% para cada ano, que o imovel se mantiver neste estado, ate que a sua
       * situa�ao, quanto a isto, seja regularizada(Com muro e com passeio)
       */
      if iCarMuro = 142 or iCarPasseio = 140 then

        /**
         * Vericamos o menor ano de lancamento das caracteriscas entre passeio e muro
         * para o usarmos na proxima verifica�ao
         */
        if iAnoPasseio < iAnoMuro then
          iAnoCaracteristica := iAnoPasseio;
        else
          iAnoCaracteristica := iAnoMuro;
        end if;

        /**
         * Comparamos o ano atual com o ano coletado anteriormente, para sabermos sua diferen�a.
         * Assim podemos saber quantos acrescimos de 0.05% devemos colocar na aliquota
         */
        iAnoAliquota := iAnousu - iAnoCaracteristica;
        rnAliquota   := rnAliquota + 0.10 + 0.05 * iAnoAliquota;
      end if;

    elsif iCarPavimentacao = 8 then

      /**
       * Se o lote for desprovido de pavimenta�ao, a aliquota sera reduzida em 0.02%
       */
      rnAliquota := rnAliquota - 0.02;
    end if;
  end if;

  -- rnAliquota := rnAliquota;

  perform fc_debug('ALIQUOTA FINAL '|| rnAliquota, lRaise);

  execute 'update tmpdadosiptu set aliq = '||rnAliquota;

  return;

end;
$$ language 'plpgsql';create or replace function fc_iptu_get_fator_comercializacao_guaiba_2015( iMatricula                integer,
                                                                          iAnousu                   integer,
                                                                          lRaise                    boolean,

                                                                         OUT rlErro                 boolean,
                                                                         OUT riCodErro              integer,
                                                                         OUT rtErro                 text,
                                                                         OUT rnFatorComercializacao numeric(15,2)) returns record as
$$
declare

  iMatricula alias for $1;
  iAnousu    alias for $2;
  lRaise     alias for $3;

begin

  rlErro                 := false;
  riCodErro              := 0;
  rtErro                 := '';
  rnFatorComercializacao := 0;

  select j110_fator
    into rnFatorComercializacao
    from iptubase
         inner join lote      on j01_idbql = j34_idbql
         inner join zonafator on j34_zona  = j110_zona
   where j01_matric  = iMatricula
     and j110_anousu = iAnousu;

  if rnFatorComercializacao is null then

    rlErro    := true;
    riCodErro := 108;
    rtErro    := iAnousu;
    return;
  end if;

  rnFatorComercializacao := rnFatorComercializacao / 100;

  return;

end;
$$  language 'plpgsql';create or replace function fc_iptu_get_fator_depreciacao_guaiba_2015( iMatricula             integer,
                                                                      iIdContrucao           integer,
                                                                      iAnousu                integer,
                                                                      lRaise                 boolean,

                                                                      OUT rlErro             boolean,
                                                                      OUT riCodErro          integer,
                                                                      OUT rtErro             text,
                                                                      OUT rnFatorDepreciacao numeric) returns record as
$$
declare

  iMatricula        alias for $1;
  iIdContrucao      alias for $2;
  iAnousu           alias for $3;
  lRaise            alias for $4;

  iCaracteristica46 integer default 0;
  iCaracteristica47 integer default 0;

begin

  rlErro             := false;
  riCodErro          := 0;
  rtErro             := '';
  rnFatorDepreciacao := 0;

  select j48_caract
    into iCaracteristica46
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 46
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica46 is null then

    rlErro    := true;
    riCodErro := 102;
    rtErro    := '46 - ESTADO DE CONSERVACAO';
    return;
  end if;

  perform fc_debug('iCaracteristica46 ' || iCaracteristica46, lRaise);

  select j48_caract
    into iCaracteristica47
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 47
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica47 is null then

    rlErro    := true;
    riCodErro := 102;
    rtErro    := '47 - IDADE APARENTE';
    return;
  end if;

  perform fc_debug('iCaracteristica47 ' || iCaracteristica47, lRaise);

  select j140_valor
    into rnFatorDepreciacao
    from agrupamentocaracteristicavalor
   where j140_sequencial in ( select grupo_46.j139_agrupamentocaracteristicavalor
                                from agrupamentocaracteristica grupo_46
                               where grupo_46.j139_caracter = iCaracteristica46
                                 and  exists ( select 1
                                                 from agrupamentocaracteristica grupo_47
                                                where grupo_47.j139_caracter                       = iCaracteristica47
                                                  and grupo_47.j139_anousu                         = iAnousu
                                                  and grupo_46.j139_agrupamentocaracteristicavalor = grupo_47.j139_agrupamentocaracteristicavalor ) );

  perform fc_debug(' <iptu_get_valor_fator_depreciacao> Buscando fator depreciacao utilizando parametros:' , lRaise);
  perform fc_debug(' <iptu_get_valor_fator_depreciacao> iMatricula      : ' || iMatricula                  , lRaise);
  perform fc_debug(' <iptu_get_valor_fator_depreciacao> iIdContrucao    : ' || iIdContrucao                , lRaise);
  perform fc_debug(' <iptu_get_valor_fator_depreciacao> Anousu          : ' || iAnousu                     , lRaise);
  perform fc_debug(' <iptu_get_valor_fator_depreciacao> Valor Retornado : ' || rnFatorDepreciacao          , lRaise);
  perform fc_debug('', lRaise);

  if rnFatorDepreciacao is null then

    rlErro    := true;
    riCodErro := 109;
    rtErro    := iAnousu;
    return;
  end if;

end;
$$  language 'plpgsql';create or replace function fc_iptu_getfatorgleba_guaiba_2015(integer) returns numeric as
$$

  select case when j34_area <= 5000                           then 1
              when j34_area >= 5001   and j34_area <= 8000    then 0.889
              when j34_area >= 8001   and j34_area <= 11000   then 0.717
              when j34_area >= 11001  and j34_area <= 14000   then 0.629
              when j34_area >= 14001  and j34_area <= 17000   then 0.572
              when j34_area >= 17001  and j34_area <= 20000   then 0.531
              when j34_area >= 20001  and j34_area <= 25000   then 0.499
              when j34_area >= 25001  and j34_area <= 30000   then 0.466
              when j34_area >= 30001  and j34_area <= 35000   then 0.435
              when j34_area >= 35001  and j34_area <= 40000   then 0.411
              when j34_area >= 40001  and j34_area <= 50000   then 0.391
              when j34_area >= 50001  and j34_area <= 60000   then 0.367
              when j34_area >= 60001  and j34_area <= 80000   then 0.342
              when j34_area >= 80001  and j34_area <= 100000  then 0.316
              when j34_area >= 100001 and j34_area <= 120000  then 0.289
              when j34_area >= 120001 and j34_area <= 150000  then 0.269
              when j34_area >= 150001 and j34_area <= 200000  then 0.251
              when j34_area >= 200001 and j34_area <= 250000  then 0.229
              when j34_area >= 250001 and j34_area <= 300000  then 0.201
              when j34_area >= 300001 and j34_area <= 350000  then 0.196
              when j34_area >= 350001 and j34_area <= 400000  then 0.185
              when j34_area >= 400001 and j34_area <= 450000  then 0.176
              when j34_area >= 450000.01                      then 0.169
           else 1
         end as fator_gleba
    from lote
   where j34_idbql = $1;

$$
language 'sql';create or replace function fc_iptu_get_fator_profundidade_guaiba_2015(integer, integer, boolean) returns numeric as
$$
declare

    iIdbql                   alias for $1;
    iAnousu                  alias for $2;
    lRaise                   alias for $3;

    iCaracteristica          integer default 0;
    nFator                   numeric default 0;
    nProfundidadeEquivalente numeric default 0;
    nFatorRetorno            numeric default 0;
    nAreaLote                numeric default 0;
    nTestada                 numeric default 0;

begin

  perform fc_debug( '<iptu_get_fator_profundidade> Verifica Fator Profundidade', lRaise);
  perform fc_debug( '<iptu_get_fator_profundidade> Idbql: ' || iIdbql, lRaise);

  select case
             when max(j36_testle) is null or max(j36_testle) = 0 then
               max(j36_testad)
             else
               max(j36_testle) end as testada,
         sum(j34_areal)
    into nTestada, nAreaLote
    from lote
         inner join testpri tp on tp.j49_idbql = lote.j34_idbql
         inner join testada t  on t.j36_idbql  = tp.j49_idbql
                              and t.j36_face   = tp.j49_face
   where j34_setor||j34_quadra||j34_lote in (select j34_setor||j34_quadra||j34_lote
                                             from lote
                                            where j34_idbql = iIdbql);

  if nTestada = 0 or nTestada is null then
    return 0;
  end if;

  nProfundidadeEquivalente := nAreaLote / nTestada;


  perform fc_debug( '<iptu_get_fator_profundidade> nProfundidadeEquivalente: ' || nProfundidadeEquivalente, lRaise);

  /**
   * Vericamos o resultado da divisao entre area do lote e a testada para definirmos qual o fator sera usado
   */
  if nProfundidadeEquivalente >= 1 and nProfundidadeEquivalente <= 10 then

    nFatorRetorno := 0.71;
  elsif nProfundidadeEquivalente > 10 and nProfundidadeEquivalente <= 20 then

    nFatorRetorno := nProfundidadeEquivalente / 20;
    perform fc_debug( '<iptu_get_fator_profundidade> Aplicando: ' || nProfundidadeEquivalente || ' / 20', lRaise);
  elsif nProfundidadeEquivalente > 20 and nProfundidadeEquivalente <= 35 then

    nFatorRetorno := 1;
  elsif nProfundidadeEquivalente > 35 and nProfundidadeEquivalente <= 70 then

    nFatorRetorno := 35 / nProfundidadeEquivalente;
    perform fc_debug( '<iptu_get_fator_profundidade> Aplicando: 35 / ' || nProfundidadeEquivalente, lRaise);
  elsif nProfundidadeEquivalente > 70 then

    nFatorRetorno := 0.71;
  end if;

  perform fc_debug( '<iptu_get_fator_profundidade> Fator Retornado: ' || nFatorRetorno, lRaise);

  return nFatorRetorno;

end;
$$  language 'plpgsql';create or replace function fc_iptu_get_valor_fator_depreciacao_guaiba_2015( iMatricula             integer,
                                                                            iIdContrucao           integer,
                                                                            lRaise                 boolean,

                                                                            OUT rlErro             boolean,
                                                                            OUT riCodErro          integer,
                                                                            OUT rtErro             text,
                                                                            OUT rnFatorDepreciacao numeric) returns record as
$$
declare

  iMatricula        alias for $1;
  iIdContrucao      alias for $2;
  lRaise            alias for $3;

  iCaracteristica46 integer default 0;
  iCaracteristica47 integer default 0;

begin

  rlErro             := false;
  riCodErro          := 0;
  rtErro             := '';
  rnFatorDepreciacao := 0;


  select j48_caract
    into iCaracteristica46
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 46
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica46 is null then

    rlErro    := true;
    riCodErro := 102;
    rtErro    := '46 - ESTADO DE CONSERVACAO';
    return;
  end if;

  perform fc_debug('iCaracteristica46 ' || iCaracteristica46, lRaise);

  select j48_caract
    into iCaracteristica47
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 47
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica46 is null then

    rlErro    := true;
    riCodErro := 102;
    rtErro    := '47 - IDADE APARENTE';
    return;
  end if;

  perform fc_debug('iCaracteristica47 ' || iCaracteristica47, lRaise);

  select j140_valor
    into rnFatorDepreciacao
    from agrupamentocaracteristicavalor
   where j140_sequencial in ( select grupo_46.j139_agrupamentocaracteristicavalor
                                from agrupamentocaracteristica grupo_46
                               where grupo_46.j139_caracter = iCaracteristica46
                                 and  exists ( select 1
                                                 from agrupamentocaracteristica grupo_47
                                                where grupo_47.j139_caracter                       = iCaracteristica47
                                                  and grupo_46.j139_agrupamentocaracteristicavalor = grupo_47.j139_agrupamentocaracteristicavalor ) );

  perform fc_debug(' <iptu_get_valor_medio_carconstr> Buscando fator depreciacao utilizando parametros:'      , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> iMatricula      : ' || iMatricula                       , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> iIdContrucao    : ' || iIdContrucao                     , lRaise);
  perform fc_debug(' <iptu_get_valor_medio_carconstr> Valor Retornado : ' || coalesce( rnFatorDepreciacao, 0 ) , lRaise);
  perform fc_debug('', lRaise);

  return;

end;
$$  language 'plpgsql';create or replace function fc_iptu_get_valor_metro_quadrado_guaiba_2015( iMatricula    integer,
                                                                         iIdContrucao  integer,
                                                                         iAnousu       integer,
                                                                         lRaise        boolean,

                                                                         OUT rlErro    boolean,
                                                                         OUT riCodErro integer,
                                                                         OUT rtErro    text,
                                                                         OUT rnVm2     numeric) returns record as
$$
declare

  iMatricula        alias for $1;
  iIdContrucao      alias for $2;
  iAnousu           alias for $3;
  lRaise            alias for $4;

  iCaracteristica43 integer default 0;
  iCaracteristica44 integer default 0;
  iCaracteristica45 integer default 0;

begin

  rlErro    := false;
  riCodErro := 0;
  rtErro    := '';
  rnVm2     := 0;

  select j48_caract
    into iCaracteristica43
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 43
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica43 is null then

    rlErro    := true;
    riCodErro := 104;
    rtErro    := '43 - TIPO';
    return;
  end if;

  perform fc_debug('iCaracteristica43 ' || iCaracteristica43, lRaise);

  select j48_caract
    into iCaracteristica44
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 44
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica44 is null then

    rlErro    := true;
    riCodErro := 104;
    rtErro    := '44 - PADRAO';
    return;
  end if;

  perform fc_debug('iCaracteristica44 ' || iCaracteristica44, lRaise);

  select j48_caract
    into iCaracteristica45
    from carconstr
         inner join caracter on j48_caract = j31_codigo
   where j31_grupo  = 45
     and j48_idcons = iIdContrucao
     and j48_matric = iMatricula;

  if iCaracteristica45 is null then

    rlErro    := true;
    riCodErro := 104;
    rtErro    := '45 - USO';
    return;
  end if;

  perform fc_debug('iCaracteristica45 ' || iCaracteristica45, lRaise);

  select j140_valor
    into rnVm2
    from agrupamentocaracteristicavalor
   where j140_sequencial in ( select grupo_43.j139_agrupamentocaracteristicavalor
                                from agrupamentocaracteristica grupo_43
                               where grupo_43.j139_caracter = iCaracteristica43
                                 and  exists ( select 1
                                                 from agrupamentocaracteristica grupo_44
                                                where grupo_44.j139_caracter                       = iCaracteristica44
                                                  and grupo_44.j139_anousu                         = iAnousu
                                                  and grupo_43.j139_agrupamentocaracteristicavalor = grupo_44.j139_agrupamentocaracteristicavalor )
                                 and  exists ( select 1
                                                 from agrupamentocaracteristica grupo_45
                                                where grupo_45.j139_caracter                       = iCaracteristica45
                                                  and grupo_45.j139_anousu                         = iAnousu
                                                  and grupo_43.j139_agrupamentocaracteristicavalor = grupo_45.j139_agrupamentocaracteristicavalor ) );

  perform fc_debug(' <iptu_get_valor_metro_quadrado> Buscando fator depreciacao utilizando parametros:' , lRaise);
  perform fc_debug(' <iptu_get_valor_metro_quadrado> iMatricula      : ' || iMatricula                  , lRaise);
  perform fc_debug(' <iptu_get_valor_metro_quadrado> iIdContrucao    : ' || iIdContrucao                , lRaise);
  perform fc_debug(' <iptu_get_valor_metro_quadrado> Anousu          : ' || iAnousu                     , lRaise);
  perform fc_debug(' <iptu_get_valor_metro_quadrado> Valor Retornado : ' || rnVm2                       , lRaise);
  perform fc_debug('', lRaise);

  if rnVm2 is null then

    rlErro    := true;
    riCodErro := 110;
    rtErro    := iAnousu;
    return;
  end if;

end;
$$  language 'plpgsql';create or replace function fc_iptu_calculavvt_mar_2011(integer,integer,numeric,integer,boolean,boolean) returns tp_iptu_calculavvt as
$$
declare

  iMatricula            alias for $1;
  iIdbql                alias for $2;
  nFracao               alias for $3;
  iAnousu               alias for $4;
  lMostrademo           alias for $5;
  lRaise                alias for $6;

  nVvt					        numeric default 0;
  nAreaTerreno			    numeric default 1;
  nAreaCalc   			    numeric default 1;
  nAreaTributada			  numeric default 0;
  nValorUnitarioM			  numeric default 1;
  cSubLote              char(3);
  nMedidaTestada			  numeric default 0;
  iCaracterSituacao     integer default 0;

  iPosicaoFiscal        integer;

  nFatorSituacao		    numeric default 1;
  nFatorCaracteristica  numeric default 1;
  nFatorNivel           numeric default 1;
  nFatorNroFrentes      numeric default 1;
  nFatorGleba           numeric default 0;
  cPlanta               char(10);
  cQuadra               char(5);
  iCaractAreaLote       integer;
  nFracaoArredondada    numeric(15,10);

  rtp_iptu_calculavvt   tp_iptu_calculavvt%ROWTYPE;

begin

  rtp_iptu_calculavvt.rnVvt        := 0;
  rtp_iptu_calculavvt.rnAreaTotalC := 0;
  rtp_iptu_calculavvt.rnArea       := 0;
  rtp_iptu_calculavvt.rnTestada    := 0;
  rtp_iptu_calculavvt.rtDemo       := '';
  rtp_iptu_calculavvt.rtMsgerro    := '';
  rtp_iptu_calculavvt.rbErro       := 'f';
  rtp_iptu_calculavvt.riCoderro    := 0;
  rtp_iptu_calculavvt.rtErro       := '';

  perform fc_debug('',lRaise,false,false);
  perform fc_debug('Iniciando calculo do valor venal territorial ...',lRaise,false,false);
  perform fc_debug('Processando tabela [Fator de profundidade]',lRaise,false,false);
  perform fc_debug('',lRaise,false,false);

	/**
   * Busca posi��o fiscal
   */
	select j35_caract
	  into iCaractAreaLote
	  from carlote
         inner join caracter on caracter.j31_codigo = carlote.j35_caract
   where caracter.j31_grupo = 45
     and carlote.j35_idbql = iIdbql;

	if not found or iCaractAreaLote = 0 then

    rtp_iptu_calculavvt.rtMsgerro := 'Sem caracteristica de area e lote definida [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
  end if;

	select  j35_caract
    into iPosicaoFiscal
    from carlote
         inner join caracter on caracter.j31_codigo = carlote.j35_caract
   where caracter.j31_grupo = 44
     and carlote.j35_idbql  = iIdbql;

	if not found or iPosicaoFiscal = 0 then

    rtp_iptu_calculavvt.rtMsgerro := 'Sem posi��o fiscal definida [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
  end if;

	/* Busca a �rea do terreno */
	select j34_area
	  into nAreaTerreno
	  from lote
	 where j34_idbql = iIdbql;

	if not found or nAreaTerreno = 0 then

	  rtp_iptu_calculavvt.rtMsgerro := '�rea n�o encontrado para terreno [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
	end if;

	/* Buscando valor m2 do terreno */
	select j05_valor, j36_testad, substr( trim(j40_refant), length(trim(j40_refant))-2, 3 )
	  into nValorUnitarioM, nMedidaTestada, cSubLote
	  from lote
         inner join iptubase      on iptubase.j01_idbql         = lote.j34_idbql
         inner join iptuant       on iptubase.j01_matric        = iptuant.j40_matric
		     inner join testada       on testada.j36_idbql          = lote.j34_idbql
		     inner join testpri       on testpri.j49_idbql          = testada.j36_idbql
                                 and testpri.j49_face           = testada.j36_face
                                 and testpri.j49_codigo         = testada.j36_codigo
         inner join loteloc       on loteloc.j06_idbql          = lote.j34_idbql
         inner join setorlocvalor on setorlocvalor.j05_setorloc = loteloc.j06_setorloc
                                 and setorlocvalor.j05_anousu   = iAnousu
	 where lote.j34_idbql = iIdbql;

	if not found or nValorUnitarioM = 0 then

    rtp_iptu_calculavvt.rtMsgerro := 'Valor n�o encontrado para o setor de localiza��o [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
	end if;

	perform fc_debug('Valor encontrado m2 lote = ' || nValorUnitarioM, lRaise, false, false);

	/* Busca fator da situacao */
	select j74_fator, carlote.j35_caract
	  into nFatorSituacao, iCaracterSituacao
	  from carlote
         inner join caracter on caracter.j31_codigo = carlote.j35_caract
                            and caracter.j31_grupo  = 21
         inner join carfator on carfator.j74_caract = caracter.j31_codigo
                            and carfator.j74_anousu = iAnousu
   where carlote.j35_idbql = iIdbql;

	if not found or nFatorSituacao = 0 then

	  rtp_iptu_calculavvt.rtMsgerro := 'Fator de situacao n�o encontrado para o lote [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
	end if;

	/* Busca fator da caracteristica */
	select j74_fator
	  into nFatorCaracteristica
	  from carlote
         inner join caracter on caracter.j31_codigo = carlote.j35_caract
                            and caracter.j31_grupo  = 22
         inner join carfator on carfator.j74_caract = caracter.j31_codigo
                            and carfator.j74_anousu = iAnousu
   where carlote.j35_idbql = iIdbql;

	if not found or nFatorCaracteristica = 0 then

	  rtp_iptu_calculavvt.rtMsgerro := 'Fator de caracteristica n�o encontrado para o lote [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
	end if;

	/* Busca fator da nivel */
	select j74_fator
	  into nFatorNivel
	  from carlote
         inner join caracter on caracter.j31_codigo = carlote.j35_caract
                            and caracter.j31_grupo  = 23
         inner join carfator on carfator.j74_caract = caracter.j31_codigo
                            and carfator.j74_anousu = iAnousu
   where carlote.j35_idbql = iIdbql;

	if not found or nFatorNivel = 0 then

	  rtp_iptu_calculavvt.rtMsgerro := 'Fator de nivel n�o encontrado para o lote [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
	end if;

	/* Busca fator da nro de frentes */
	select j74_fator
	  into nFatorNroFrentes
	  from carlote
         inner join caracter on caracter.j31_codigo = carlote.j35_caract
                            and caracter.j31_grupo  = 25
         inner join carfator on carfator.j74_caract = caracter.j31_codigo
                            and carfator.j74_anousu = iAnousu
   where carlote.j35_idbql = iIdbql;

	if not found or nFatorNroFrentes = 0 then

	  rtp_iptu_calculavvt.rtMsgerro := 'Fator de nro de frentes n�o encontrado para o lote [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
    rtp_iptu_calculavvt.rbErro    := 't';
    return rtp_iptu_calculavvt;
	end if;

  /* buscando planta para calcular reducao */
  select j05_codigoproprio, loteloc.j06_quadraloc
    into cPlanta, cQuadra
    from lote
         inner join loteloc           on loteloc.j06_idbql   = lote.j34_idbql
         inner join cadastro.setorloc on setorloc.j05_codigo = loteloc.j06_setorloc
   where lote.j34_idbql = iIdbql;

  if nAreaTerreno > 4000 then

    if ( cPlanta = '0174' or cPlanta = '174A' ) or cQuadra = 'AREA' or iCaractAreaLote = 600 then

      /* Busca fator da gleba */
      select j71_valor
        into nFatorGleba
        from carlote
             inner join carvalor on carvalor.j71_caract = 44
                                and carvalor.j71_anousu = iAnousu
                                and nAreaTerreno between j71_ini
                                and j71_fim
      where carlote.j35_idbql = iIdbql;

      if not found or nFatorGleba = 0 then

        rtp_iptu_calculavvt.rtMsgerro := 'Fator de gleba n�o encontrado para o lote [ IDBQL : '||coalesce(iIdbql,0)||' ] ';
        rtp_iptu_calculavvt.rbErro    := 't';
        return rtp_iptu_calculavvt;

      end if;

      if nFatorGleba < nValorUnitarioM then
        nValorUnitarioM = nFatorGleba;
      end if;

    end if;

  end if;

	/* Calculo do valor venal do terreno */
  nFracaoArredondada := nFracao / 100;
  nAreaCalc          := nAreaTerreno * nFracaoArredondada;
  nVvt               := nAreaCalc * nValorUnitarioM;
  nVvt               := nVvt * ( nFatorSituacao * nFatorCaracteristica * nFatorNivel * nFatorNroFrentes );
  nVvt               := round( nVvt,2 );

  rtp_iptu_calculavvt.rnVvt  := nVvt;
  rtp_iptu_calculavvt.rnArea := nAreaTributada;

  update tmpdadosiptu set vvt =  nVvt, areat = nAreaCalc, vm2t = nValorUnitarioM;

  return rtp_iptu_calculavvt;

end;
$$ language 'plpgsql';insert into db_versaoant (db31_codver,db31_data) values (351, current_date);
select setval ('db_versaousu_db32_codusu_seq',(select max (db32_codusu) from db_versaousu));
select setval ('db_versaousutarefa_db28_sequencial_seq',(select max (db28_sequencial) from db_versaousutarefa));
select setval ('db_versaocpd_db33_codcpd_seq',(select max (db33_codcpd) from db_versaocpd));
select setval ('db_versaocpdarq_db34_codarq_seq',(select max (db34_codarq) from db_versaocpdarq));create table bkp_db_permissao_20150212_112229 as select * from db_permissao;
create temp table w_perm_filhos as 
select distinct 
       i.id_item        as filho, 
       p.id_usuario     as id_usuario, 
       p.permissaoativa as permissaoativa, 
       p.anousu         as anousu, 
       p.id_instit      as id_instit, 
       m.modulo         as id_modulo  
  from db_itensmenu i  
       inner join db_menu      m  on m.id_item_filho = i.id_item 
       inner join db_permissao p  on p.id_item       = m.id_item_filho 
                                 and p.id_modulo     = m.modulo 
 where coalesce(i.libcliente, false) is true;

create index w_perm_filhos_in on w_perm_filhos(filho);

create temp table w_semperm_pai as 
select distinct m.id_item       as pai, m.id_item_filho as filho 
  from db_itensmenu i 
       inner join db_menu            m  on m.id_item   = i.id_item 
       left  outer join db_permissao p  on p.id_item   = m.id_item 
                                       and p.id_modulo = m.modulo 
 where p.id_item is null 
   and coalesce(i.libcliente, false) is true;
create index w_semperm_pai_in on w_semperm_pai(filho);
insert into db_permissao (id_usuario,id_item,permissaoativa,anousu,id_instit,id_modulo) 
select distinct wf.id_usuario, wp.pai, wf.permissaoativa, wf.anousu, wf.id_instit, wf.id_modulo 
  from w_semperm_pai wp 
       inner join w_perm_filhos wf on wf.filho = wp.filho 
       where not exists (select 1 from db_permissao p 
                    where p.id_usuario = wf.id_usuario 
                      and p.id_item    = wp.pai 
                      and p.anousu     = wf.anousu 
                      and p.id_instit  = wf.id_instit 
                      and p.id_modulo  = wf.id_modulo); 
delete from db_permissao
 where not exists (select a.id_item 
                     from db_menu a 
                    where a.modulo = db_permissao.id_modulo 
                      and (a.id_item       = db_permissao.id_item or 
                           a.id_item_filho = db_permissao.id_item) );
delete from db_itensfilho    
 where not exists (select 1 from db_arquivos where db_arquivos.codfilho = db_itensfilho.codfilho);

CREATE FUNCTION acerta_permissao_hierarquia() RETURNS varchar AS $$ 

 declare  

   i integer default 1; 

   BEGIN 

  while i < 5 loop   

    insert into db_permissao select distinct 
                                 db_permissao.id_usuario, 
                                 db_menu.id_item, 
                                 db_permissao.permissaoativa, 
                                 db_permissao.anousu, 
                                 db_permissao.id_instit, 
                                 db_permissao.id_modulo 
                            from db_permissao 
                                 inner join db_menu on db_menu.id_item_filho = db_permissao.id_item 
                                                   and db_menu.modulo        = db_permissao.id_modulo 
                           where not exists ( select 1 
                                                from db_permissao as p 
                                               where p.id_item    = db_menu.id_item 
                                                 and p.id_usuario = db_permissao.id_usuario 
                                                 and p.anousu     = db_permissao.anousu 
                                                 and p.id_instit  = db_permissao.id_instit 
                                                 and p.id_modulo  = db_permissao.id_modulo );

  i := i+1; 

 end loop;

return 'Processo concluido com sucesso!';
END; 
$$ LANGUAGE 'plpgsql' ;

select acerta_permissao_hierarquia();
drop function acerta_permissao_hierarquia();create or replace function fc_executa_ddl(text) returns boolean as $$ 
  declare  
    sDDL     alias for $1;
    lRetorno boolean default true;
  begin   
    begin 
      EXECUTE sDDL;
    exception 
      when others then 
        raise info 'Error Code: % - %', SQLSTATE, SQLERRM;
        lRetorno := false;
    end;  
    return lRetorno;
  end; 
  $$ language plpgsql ;

  select fc_executa_ddl('ALTER TABLE '||quote_ident(table_schema)||'.'||quote_ident(table_name)||' ENABLE TRIGGER ALL;') 
  from information_schema.tables 
   where table_schema not in ('pg_catalog', 'pg_toast', 'information_schema')
     and table_schema !~ '^pg_temp'
     and table_type = 'BASE TABLE'
   order by table_schema, table_name;

                                                                                                       
SELECT CASE WHEN EXISTS (SELECT 1 FROM pg_authid WHERE rolname = 'dbseller')                           
  THEN fc_grant('dbseller', 'select', '%', '%') ELSE -1 END;                                           
SELECT CASE WHEN EXISTS (SELECT 1 FROM pg_authid WHERE rolname = 'plugin')                             
  THEN fc_grant('plugin', 'select', '%', '%') ELSE -1 END;                                             
SELECT fc_executa_ddl('GRANT CREATE ON TABLESPACE '||spcname||' TO dbseller;')                         
  FROM pg_tablespace                                                                                   
 WHERE spcname !~ '^pg_' AND EXISTS (SELECT 1 FROM pg_authid WHERE rolname = 'dbseller');              
                                                                                                       
  delete from db_versaoant where not exists (select 1 from db_versao where db30_codver = db31_codver); 
  delete from db_versaousu where not exists (select 1 from db_versao where db30_codver = db32_codver); 
  delete from db_versaocpd where not exists (select 1 from db_versao where db30_codver = db33_codver); 
                                                                                                       
/*select fc_schemas_dbportal();*/
