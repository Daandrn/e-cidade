BEGIN;

SELECT fc_startsession();

--DROP TABLE:
DROP TABLE IF EXISTS balancete102015 CASCADE;
DROP TABLE IF EXISTS balancete112015 CASCADE;
DROP TABLE IF EXISTS balancete122015 CASCADE;
DROP TABLE IF EXISTS balancete132015 CASCADE;
DROP TABLE IF EXISTS balancete142015 CASCADE;
DROP TABLE IF EXISTS balancete152015 CASCADE;
DROP TABLE IF EXISTS balancete162015 CASCADE;
DROP TABLE IF EXISTS balancete172015 CASCADE;
DROP TABLE IF EXISTS balancete182015 CASCADE;
DROP TABLE IF EXISTS balancete192015 CASCADE;
DROP TABLE IF EXISTS balancete202015 CASCADE;
DROP TABLE IF EXISTS balancete212015 CASCADE;
DROP TABLE IF EXISTS balancete222015 CASCADE;
--Criando drop sequences


-- Criando  sequences
-- TABELAS E ESTRUTURA

-- M�dulo: sicom
CREATE TABLE balancete102015(
si177_sequencial		int8 NOT NULL default 0,
si177_tiporegistro		int8 NOT NULL default 0,
si177_contacontaabil		int8 NOT NULL default 0,
si177_saldoinicial		float8 NOT NULL default 0,
si177_naturezasaldoinicial		varchar(1) NOT NULL ,
si177_totaldebitos		float8 NOT NULL default 0,
si177_totalcreditos		float8 NOT NULL default 0,
si177_saldofinal		float8 NOT NULL default 0,
si177_naturezasaldofinal		varchar(1) NOT NULL ,
si177_mes		int8 NOT NULL default 0,
si177_instit		int8 default 0,
CONSTRAINT balancete102015_sequ_pk PRIMARY KEY (si177_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete112015(
si178_sequencial		int8 NOT NULL default 0,
si178_tiporegistro		int8 NOT NULL default 0,
si178_contacontaabil		int8 NOT NULL default 0,
si178_codorgao		varchar(2) NOT NULL ,
si178_codunidadesub		varchar(8) NOT NULL ,
si178_codfuncao		varchar(2) NOT NULL ,
si178_codsubfuncao		varchar(3) NOT NULL ,
si178_codprograma		text NOT NULL ,
si178_idacao		varchar(4) NOT NULL ,
si178_idsubacao		varchar(4) NOT NULL ,
si178_naturezadespesa		int8 NOT NULL default 0,
si178_subelemento		varchar(2) NOT NULL ,
si178_codfontrecursos		int8 NOT NULL default 0,
si178_saldoinicialcd		float8 NOT NULL default 0,
si178_naturezasaldoinicialcd		varchar(1) NOT NULL ,
si178_totaldebitoscd		float8 NOT NULL default 0,
si178_totalcreditoscd		float8 NOT NULL default 0,
si178_saldofinalcd		float8 NOT NULL default 0,
si178_naturezasaldofinalcd		varchar(1) NOT NULL ,
si178_mes		int8 NOT NULL default 0,
si178_instit		int8 default 0,
CONSTRAINT balancete112015_sequ_pk PRIMARY KEY (si178_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete122015(
si179_sequencial		int8 NOT NULL default 0,
si179_tiporegistro		int8 NOT NULL default 0,
si179_contacontabil		int8 NOT NULL default 0,
si179_naturezareceita		int8 NOT NULL default 0,
si179_codfontrecursos		int8 NOT NULL default 0,
si179_saldoinicialcr		float8 NOT NULL default 0,
si179_naturezasaldoinicialcr		varchar(1) NOT NULL ,
si179_totaldebitoscr		float8 NOT NULL default 0,
si179_totalcreditoscr		float8 NOT NULL default 0,
si179_saldofinalcr		float8 NOT NULL default 0,
si179_naturezasaldofinalcr		varchar(1) NOT NULL ,
si179_mes		int8 NOT NULL default 0,
si179_instit		int8 default 0,
CONSTRAINT balancete122015_sequ_pk PRIMARY KEY (si179_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete132015(
si180_sequencial		int8 NOT NULL default 0,
si180_tiporegistro		int8 NOT NULL default 0,
si180_contacontabil		int8 NOT NULL default 0,
si180_codprograma		varchar(4) NOT NULL ,
si180_idacao		text NOT NULL ,
si180_idsubacao		varchar(4)  ,
si180_saldoIniciaipa		float8 NOT NULL default 0,
si180_naturezasaldoIniciaipa		varchar(1) NOT NULL ,
si180_totaldebitospa		float8 NOT NULL default 0,
si180_totalcreditospa		float8 NOT NULL default 0,
si180_saldofinaipa		float8 NOT NULL default 0,
si180_naturezasaldofinaipa		varchar(1) NOT NULL ,
si180_mes		int8 NOT NULL default 0,
si180_instit		int8 default 0,
CONSTRAINT balancete132015_sequ_pk PRIMARY KEY (si180_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete142015(
si181_sequencial		int8 NOT NULL default 0,
si181_tiporegistro		int8 NOT NULL default 0,
si181_contacontabil		int8 NOT NULL default 0,
si181_codorgao		varchar(2) NOT NULL ,
si181_codunidadesub		varchar(8) NOT NULL ,
si181_codunidadesuborig		varchar(8) NOT NULL ,
si181_codfuncao		varchar(2) NOT NULL ,
si181_codsubfuncao		varchar(3) NOT NULL ,
si181_codprograma		varchar(4) NOT NULL ,
si181_idacao		varchar(4) NOT NULL ,
si181_idsubacao		varchar(4)  ,
si181_naturezadespesa		int8 NOT NULL default 0,
si181_subelemento		varchar(2) NOT NULL ,
si181_codfontrecursos		int8 NOT NULL default 0,
si181_nroempenho		int8 NOT NULL default 0,
si181_anoinscricao		int8 NOT NULL default 0,
si181_saldoinicialrsp		float8 NOT NULL default 0,
si181_naturezasaldoinicialrsp		varchar(1) NOT NULL ,
si181_totaldebitosrsp		float8 NOT NULL default 0,
si181_totalcreditosrsp		float8 NOT NULL default 0,
si181_saldofinalrsp		float8 NOT NULL default 0,
si181_naturezasaldofinalrsp		varchar(1) NOT NULL ,
si181_mes		int8 NOT NULL default 0,
si181_instit		int8 default 0,
CONSTRAINT balancete142015_sequ_pk PRIMARY KEY (si181_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete152015(
si182_sequencial		int8 NOT NULL default 0,
si182_tiporegistro		int8 NOT NULL default 0,
si182_contacontabil		int8 NOT NULL default 0,
si182_atributosf		varchar(1) NOT NULL ,
si182_saldoinicialsf		float8 NOT NULL default 0,
si182_naturezasaldoinicialsf		varchar(1) NOT NULL ,
si182_totaldebitossf		float8 NOT NULL default 0,
si182_totalcreditossf		float8 NOT NULL default 0,
si182_saldofinalsf		float8 NOT NULL default 0,
si182_naturezasaldofinalsf		varchar(1) NOT NULL ,
si182_mes		int8 NOT NULL default 0,
si182_instit		int8 default 0,
CONSTRAINT balancete152015_sequ_pk PRIMARY KEY (si182_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete162015(
si183_sequencial		int8 NOT NULL default 0,
si183_tiporegistro		int8 NOT NULL default 0,
si183_contacontabil		int8 NOT NULL default 0,
si183_atributosf		varchar(1) NOT NULL ,
si183_codfontrecursos		int8 NOT NULL default 0,
si183_saldoinicialfontsf		float8 NOT NULL default 0,
si183_naturezasaldoinicialfontsf		varchar(1) NOT NULL ,
si183_totaldebitosfontsf		float8 NOT NULL default 0,
si183_totalcreditosfontsf		float8 NOT NULL default 0,
si183_saldofinalfontsf		float8 NOT NULL default 0,
si183_naturezasaldofinalfontsf		varchar(1) NOT NULL ,
si183_mes		int8 NOT NULL default 0,
si183_instit		int8 default 0,
CONSTRAINT balancete162015_sequ_pk PRIMARY KEY (si183_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete172015(
si184_sequencial		int8 NOT NULL default 0,
si184_tiporegistro		int8 NOT NULL default 0,
si184_contacontabil		int8 NOT NULL default 0,
si184_atributosf		varchar(1) NOT NULL ,
si184_codctb		int8 NOT NULL default 0,
si184_codfontrecursos		int8 NOT NULL default 0,
si184_saldoinicialctb		float8 NOT NULL default 0,
si184_naturezasaldoinicialctb		varchar(1) NOT NULL ,
si184_totaldebitosctb		float8 NOT NULL default 0,
si184_totalcreditosctb		float8 NOT NULL default 0,
si184_saldofinalctb		float8 NOT NULL default 0,
si184_naturezasaldofinalctb		varchar(1) NOT NULL ,
si184_mes		int8 NOT NULL default 0,
si184_instit		int8 default 0,
CONSTRAINT balancete172015_sequ_pk PRIMARY KEY (si184_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete182015(
si185_sequencial		int8 NOT NULL default 0,
si185_tiporegistro		int8 NOT NULL default 0,
si185_contacontabil		int8 NOT NULL default 0,
si185_codfontrecursos		int8 NOT NULL default 0,
si185_saldoinicialfr		float8 NOT NULL default 0,
si185_naturezasaldoinicialfr		varchar(1) NOT NULL ,
si185_totaldebitosfr		float8 NOT NULL default 0,
si185_totalcreditosfr		float8 NOT NULL default 0,
si185_saldofinalfr		float8 NOT NULL default 0,
si185_naturezasaldofinalfr		varchar(1) NOT NULL ,
si185_mes		int8 NOT NULL default 0,
si185_instit		int8 default 0,
CONSTRAINT balancete182015_sequ_pk PRIMARY KEY (si185_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete192015(
si186_sequencial		int8 NOT NULL default 0,
si186_tiporegistro		int8 NOT NULL default 0,
si186_contacontabil		int8 NOT NULL default 0,
si186_cnpjconsorcio		int8 NOT NULL default 0,
si186_saldoinicialconsor		float8 NOT NULL default 0,
si186_naturezasaldoinicialconsor		varchar(1) NOT NULL ,
si186_totaldebitosconsor		float8 NOT NULL default 0,
si186_totalcreditosconsor		float8 NOT NULL default 0,
si186_saldofinalconsor		float8 NOT NULL default 0,
si186_naturezasaldofinalconsor		varchar(1) NOT NULL ,
si186_mes		int8 NOT NULL default 0,
si186_instit		int8 default 0,
CONSTRAINT balancete192015_sequ_pk PRIMARY KEY (si186_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete202015(
si187_sequencial		int8 NOT NULL default 0,
si187_tiporegistro		int8 NOT NULL default 0,
si187_contacontabil		int8 NOT NULL default 0,
si187_cnpjconsorcio		int8 NOT NULL default 0,
si187_tiporecurso		int4 NOT NULL default 0,
si187_codfuncao		varchar(2) NOT NULL ,
si187_codsubfuncao		varchar(3) NOT NULL ,
si187_naturezadespesa		int8 NOT NULL default 0,
si187_subelemento		varchar(2) NOT NULL ,
si187_codfontrecursos		int8 NOT NULL default 0,
si187_saldoinicialconscf		float8 NOT NULL default 0,
si187_naturezasaldoinicialconscf		varchar(1) NOT NULL ,
si187_totaldebitosconscf		float8 NOT NULL default 0,
si187_totalcreditosconscf		float8 NOT NULL default 0,
si187_saldofinalconscf		float8 NOT NULL default 0,
si187_naturezasaldofinalconscf		varchar(1) NOT NULL ,
si187_mes		int8 NOT NULL default 0,
si187_instit		int8 default 0,
CONSTRAINT balancete202015_sequ_pk PRIMARY KEY (si187_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete212015(
si188_sequencial		int8 NOT NULL default 0,
si188_tiporegistro		int8 NOT NULL default 0,
si188_contacontabil		int8 NOT NULL default 0,
si188_cnpjconsorcio		int8 NOT NULL default 0,
si188_codfontrecursos		int8 NOT NULL default 0,
si188_saldoinicialconsorfr		float8 NOT NULL default 0,
si188_naturezasaldoinicialconsorfr		varchar(1) NOT NULL ,
si188_totaldebitosconsorfr		float8 NOT NULL default 0,
si188_totalcreditosconsorfr		float8 NOT NULL default 0,
si188_saldofinalconsorfr		float8 NOT NULL default 0,
si188_naturezasaldofinalconsorfr		varchar(1) NOT NULL ,
si188_mes		int8 NOT NULL default 0,
si188_instit		int8 default 0,
CONSTRAINT balancete212015_sequ_pk PRIMARY KEY (si188_sequencial));


-- M�dulo: sicom
CREATE TABLE balancete222015(
si189_sequencial		int8 NOT NULL default 0,
si189_tiporegistro		int8 NOT NULL default 0,
si189_contacontabil		int8 NOT NULL default 0,
si189_atributosf		varchar(1) NOT NULL ,
si189_codctb		int8 NOT NULL default 0,
si189_saldoInicialctbsf		float8 NOT NULL default 0,
si189_naturezasaldoinicialctbsf		varchar(1) NOT NULL ,
si189_totaldebitosctbsf		float8 NOT NULL default 0,
si189_totalcreditosctbsf		float8 NOT NULL default 0,
si189_saldofinalctbsf		float8 NOT NULL default 0,
si189_naturezasaldofinalctbsf		varchar(1) NOT NULL ,
si189_mes		int8 NOT NULL default 0,
si189_instit		int8 default 0,
CONSTRAINT balancete222015_sequ_pk PRIMARY KEY (si189_sequencial));




-- CHAVE ESTRANGEIRA





-- INDICES



COMMIT;
