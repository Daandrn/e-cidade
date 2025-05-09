<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class M9756FormularioServidor extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL
update avaliacaogrupopergunta set db102_identificadorcampo = 'trabalhador' where db102_sequencial = 3000148;
update avaliacaopergunta set db103_identificadorcampo = 'nmTrab' where db103_sequencial = 3000654;
update avaliacaopergunta set db103_identificadorcampo = 'cpfTrab' where db103_sequencial = 3000655;
update avaliacaopergunta set db103_identificadorcampo = 'nisTrab' where db103_sequencial = 3000656;
update avaliacaopergunta set db103_identificadorcampo = 'sexo' where db103_sequencial = 3000657;
update avaliacaopergunta set db103_identificadorcampo = 'racaCor' where db103_sequencial = 3000658;
update avaliacaopergunta set db103_identificadorcampo = 'estCiv' where db103_sequencial = 3000659;
update avaliacaopergunta set db103_identificadorcampo = 'grauInstr' where db103_sequencial = 3000660;
update avaliacaopergunta set db103_identificadorcampo = 'indPriEmpr' where db103_sequencial = 3000661;
update avaliacaopergunta set db103_identificadorcampo = 'nmSoc' where db103_sequencial = 3000662;
update avaliacaogrupopergunta set db102_identificadorcampo = 'nascimento' where db102_sequencial = 3000149;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto' where db103_sequencial = 3000663;
update avaliacaopergunta set db103_identificadorcampo = 'codMunic' where db103_sequencial = 3000664;
update avaliacaopergunta set db103_identificadorcampo = 'uf' where db103_sequencial = 3000665;
update avaliacaopergunta set db103_identificadorcampo = 'paisNascto' where db103_sequencial = 3000666;
update avaliacaopergunta set db103_identificadorcampo = 'paisNac' where db103_sequencial = 3000667;
update avaliacaopergunta set db103_identificadorcampo = 'nmMae' where db103_sequencial = 3000668;
update avaliacaopergunta set db103_identificadorcampo = 'nmPai' where db103_sequencial = 3000669;
update avaliacaogrupopergunta set db102_identificadorcampo = 'CTPS' where db102_sequencial = 3000150;
update avaliacaopergunta set db103_identificadorcampo = 'nrCtps' where db103_sequencial = 3000670;
update avaliacaopergunta set db103_identificadorcampo = 'serieCtps' where db103_sequencial = 3000671;
update avaliacaopergunta set db103_identificadorcampo = 'ufCtps' where db103_sequencial = 3000672;
update avaliacaogrupopergunta set db102_identificadorcampo = 'RIC' where db102_sequencial = 3000151;
update avaliacaopergunta set db103_identificadorcampo = 'nrRic' where db103_sequencial = 3000673;
update avaliacaopergunta set db103_identificadorcampo = 'orgaoEmissor' where db103_sequencial = 3000674;
update avaliacaopergunta set db103_identificadorcampo = 'dtExped' where db103_sequencial = 3000675;
update avaliacaogrupopergunta set db102_identificadorcampo = 'RG' where db102_sequencial = 3000152;
update avaliacaopergunta set db103_identificadorcampo = 'nrRg' where db103_sequencial = 3000676;
update avaliacaopergunta set db103_identificadorcampo = 'orgaoEmissor' where db103_sequencial = 3000677;
update avaliacaopergunta set db103_identificadorcampo = 'dtExped' where db103_sequencial = 3000678;
update avaliacaogrupopergunta set db102_identificadorcampo = 'RNE' where db102_sequencial = 3000153;
update avaliacaopergunta set db103_identificadorcampo = 'nrRne' where db103_sequencial = 3000679;
update avaliacaopergunta set db103_identificadorcampo = 'orgaoEmissor' where db103_sequencial = 3000680;
update avaliacaopergunta set db103_identificadorcampo = 'dtExped' where db103_sequencial = 3000681;
update avaliacaogrupopergunta set db102_identificadorcampo = 'OC' where db102_sequencial = 3000154;
update avaliacaopergunta set db103_identificadorcampo = 'nrOc' where db103_sequencial = 3000682;
update avaliacaopergunta set db103_identificadorcampo = 'orgaoEmissor' where db103_sequencial = 3000683;
update avaliacaopergunta set db103_identificadorcampo = 'dtExped' where db103_sequencial = 3000684;
update avaliacaopergunta set db103_identificadorcampo = 'dtValid' where db103_sequencial = 3000685;
update avaliacaogrupopergunta set db102_identificadorcampo = 'CNH' where db102_sequencial = 3000155;
update avaliacaopergunta set db103_identificadorcampo = 'nrRegCnh' where db103_sequencial = 3000686;
update avaliacaopergunta set db103_identificadorcampo = 'dtExped' where db103_sequencial = 3000687;
update avaliacaopergunta set db103_identificadorcampo = 'ufCnh' where db103_sequencial = 3000688;
update avaliacaopergunta set db103_identificadorcampo = 'dtValid' where db103_sequencial = 3000689;
update avaliacaopergunta set db103_identificadorcampo = 'dtPriHab' where db103_sequencial = 3000690;
update avaliacaopergunta set db103_identificadorcampo = 'categoriaCnh' where db103_sequencial = 3000691;
update avaliacaogrupopergunta set db102_identificadorcampo = 'brasil' where db102_sequencial = 3000156;
update avaliacaopergunta set db103_identificadorcampo = 'tpLograd' where db103_sequencial = 3000692;
update avaliacaopergunta set db103_identificadorcampo = 'dscLograd' where db103_sequencial = 3000693;
update avaliacaopergunta set db103_identificadorcampo = 'nrLograd' where db103_sequencial = 3000694;
update avaliacaopergunta set db103_identificadorcampo = 'complemento' where db103_sequencial = 3000695;
update avaliacaopergunta set db103_identificadorcampo = 'bairro' where db103_sequencial = 3000696;
update avaliacaopergunta set db103_identificadorcampo = 'cep' where db103_sequencial = 3000697;
update avaliacaopergunta set db103_identificadorcampo = 'codMunic' where db103_sequencial = 3000698;
update avaliacaopergunta set db103_identificadorcampo = 'uf' where db103_sequencial = 3000699;
update avaliacaogrupopergunta set db102_identificadorcampo = 'exterior' where db102_sequencial = 3000157;
update avaliacaopergunta set db103_identificadorcampo = 'paisResid' where db103_sequencial = 3000700;
update avaliacaopergunta set db103_identificadorcampo = 'dscLograd' where db103_sequencial = 3000701;
update avaliacaopergunta set db103_identificadorcampo = 'nrLograd' where db103_sequencial = 3000702;
update avaliacaopergunta set db103_identificadorcampo = 'complemento' where db103_sequencial = 3000703;
update avaliacaopergunta set db103_identificadorcampo = 'bairro' where db103_sequencial = 3000704;
update avaliacaopergunta set db103_identificadorcampo = 'nmCid' where db103_sequencial = 3000705;
update avaliacaopergunta set db103_identificadorcampo = 'codPostal' where db103_sequencial = 3000706;
update avaliacaogrupopergunta set db102_identificadorcampo = 'trabEstrangeiro' where db102_sequencial = 3000158;
update avaliacaopergunta set db103_identificadorcampo = 'dtChegada' where db103_sequencial = 3000707;
update avaliacaopergunta set db103_identificadorcampo = 'classTrabEstrang' where db103_sequencial = 3000708;
update avaliacaopergunta set db103_identificadorcampo = 'casadoBr' where db103_sequencial = 3000709;
update avaliacaopergunta set db103_identificadorcampo = 'filhosBr' where db103_sequencial = 3000710;
update avaliacaogrupopergunta set db102_identificadorcampo = 'infoDeficiencia' where db102_sequencial = 3000159;
update avaliacaopergunta set db103_identificadorcampo = 'defFisica' where db103_sequencial = 3000711;
update avaliacaopergunta set db103_identificadorcampo = 'defVisual' where db103_sequencial = 3000712;
update avaliacaopergunta set db103_identificadorcampo = 'defAuditiva' where db103_sequencial = 3000713;
update avaliacaopergunta set db103_identificadorcampo = 'defMental' where db103_sequencial = 3000714;
update avaliacaopergunta set db103_identificadorcampo = 'defIntelectual' where db103_sequencial = 3000715;
update avaliacaopergunta set db103_identificadorcampo = 'reabReadap' where db103_sequencial = 3000716;
update avaliacaopergunta set db103_identificadorcampo = 'infoCota' where db103_sequencial = 3000717;
update avaliacaopergunta set db103_identificadorcampo = 'observacao' where db103_sequencial = 3000718;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_1' where db102_sequencial = 3000160;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_1' where db103_sequencial = 3000719;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_1' where db103_sequencial = 3000720;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_1' where db103_sequencial = 3000721;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_1' where db103_sequencial = 3000722;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_1' where db103_sequencial = 3000723;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_1' where db103_sequencial = 3000724;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_1' where db103_sequencial = 3000725;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_2' where db102_sequencial = 3000161;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_2' where db103_sequencial = 3000726;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_2' where db103_sequencial = 3000727;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_2' where db103_sequencial = 3000728;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_2' where db103_sequencial = 3000729;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_2' where db103_sequencial = 3000730;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_2' where db103_sequencial = 3000731;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_2' where db103_sequencial = 3000732;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_3' where db102_sequencial = 3000162;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_3' where db103_sequencial = 3000733;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_3' where db103_sequencial = 3000734;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_3' where db103_sequencial = 3000735;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_3' where db103_sequencial = 3000736;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_3' where db103_sequencial = 3000737;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_3' where db103_sequencial = 3000738;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_3' where db103_sequencial = 3000739;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_4' where db102_sequencial = 3000163;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_4' where db103_sequencial = 3000740;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_4' where db103_sequencial = 3000741;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_4' where db103_sequencial = 3000742;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_4' where db103_sequencial = 3000743;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_4' where db103_sequencial = 3000744;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_4' where db103_sequencial = 3000745;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_4' where db103_sequencial = 3000746;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_5' where db102_sequencial = 3000164;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_5' where db103_sequencial = 3000747;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_5' where db103_sequencial = 3000748;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_5' where db103_sequencial = 3000749;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_5' where db103_sequencial = 3000750;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_5' where db103_sequencial = 3000751;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_5' where db103_sequencial = 3000752;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_5' where db103_sequencial = 3000753;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_6' where db102_sequencial = 3000165;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_6' where db103_sequencial = 3000754;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_6' where db103_sequencial = 3000755;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_6' where db103_sequencial = 3000756;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_6' where db103_sequencial = 3000757;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_6' where db103_sequencial = 3000758;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_6' where db103_sequencial = 3000759;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_6' where db103_sequencial = 3000760;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_7' where db102_sequencial = 3000166;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_7' where db103_sequencial = 3000761;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_7' where db103_sequencial = 3000762;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_7' where db103_sequencial = 3000763;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_7' where db103_sequencial = 3000764;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_7' where db103_sequencial = 3000765;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_7' where db103_sequencial = 3000766;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_7' where db103_sequencial = 3000767;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_8' where db102_sequencial = 3000167;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_8' where db103_sequencial = 3000768;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_8' where db103_sequencial = 3000769;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_8' where db103_sequencial = 3000770;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_8' where db103_sequencial = 3000771;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_8' where db103_sequencial = 3000772;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_8' where db103_sequencial = 3000773;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_8' where db103_sequencial = 3000774;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_9' where db102_sequencial = 3000168;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_9' where db103_sequencial = 3000775;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_9' where db103_sequencial = 3000776;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_9' where db103_sequencial = 3000777;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_9' where db103_sequencial = 3000778;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_9' where db103_sequencial = 3000779;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_9' where db103_sequencial = 3000780;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_9' where db103_sequencial = 3000781;
update avaliacaogrupopergunta set db102_identificadorcampo = 'dependente_10' where db102_sequencial = 3000169;
update avaliacaopergunta set db103_identificadorcampo = 'tpDep_10' where db103_sequencial = 3000782;
update avaliacaopergunta set db103_identificadorcampo = 'nmDep_10' where db103_sequencial = 3000783;
update avaliacaopergunta set db103_identificadorcampo = 'dtNascto_10' where db103_sequencial = 3000784;
update avaliacaopergunta set db103_identificadorcampo = 'cpfDep_10' where db103_sequencial = 3000785;
update avaliacaopergunta set db103_identificadorcampo = 'depIRRF_10' where db103_sequencial = 3000786;
update avaliacaopergunta set db103_identificadorcampo = 'depSF_10' where db103_sequencial = 3000787;
update avaliacaopergunta set db103_identificadorcampo = 'incTrab_10' where db103_sequencial = 3000788;
update avaliacaogrupopergunta set db102_identificadorcampo = 'aposentadoria' where db102_sequencial = 3000170;
update avaliacaopergunta set db103_identificadorcampo = 'trabAposent' where db103_sequencial = 3000789;
update avaliacaogrupopergunta set db102_identificadorcampo = 'contato' where db102_sequencial = 3000171;
update avaliacaopergunta set db103_identificadorcampo = 'fonePrinc' where db103_sequencial = 3000790;
update avaliacaopergunta set db103_identificadorcampo = 'foneAlternat' where db103_sequencial = 3000791;
update avaliacaopergunta set db103_identificadorcampo = 'emailPrinc' where db103_sequencial = 3000792;
update avaliacaopergunta set db103_identificadorcampo = 'emailAlternat' where db103_sequencial = 3000793;
update avaliacaogrupopergunta set db102_identificadorcampo = 'vinculo' where db102_sequencial = 3000172;
update avaliacaopergunta set db103_identificadorcampo = 'matricula' where db103_sequencial = 3000794;
update avaliacaopergunta set db103_identificadorcampo = 'tpRegTrab' where db103_sequencial = 3000795;
update avaliacaopergunta set db103_sequencial = 3000796 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000172 , db103_descricao = 'Regime Previdenciário' , db103_identificador = '1293' , db103_obrigatoria = 'true' , db103_ativo = 'false' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_camposql = '' where db103_sequencial = 3000796;
update avaliacaopergunta set db103_identificadorcampo = 'nrRecInfPrelim' where db103_sequencial = 3000797;
update avaliacaopergunta set db103_identificadorcampo = 'cadIni' where db103_sequencial = 3000798;
update avaliacaogrupopergunta set db102_identificadorcampo = 'infoCeletista' where db102_sequencial = 3000173;
update avaliacaopergunta set db103_identificadorcampo = 'dtAdm' where db103_sequencial = 3000799;
update avaliacaopergunta set db103_identificadorcampo = 'tpAdmissao' where db103_sequencial = 3000800;
update avaliacaopergunta set db103_identificadorcampo = 'indAdmissao' where db103_sequencial = 3000801;
update avaliacaopergunta set db103_identificadorcampo = 'tpRegJor' where db103_sequencial = 3000802;
update avaliacaopergunta set db103_identificadorcampo = 'natAtividade' where db103_sequencial = 3000803;
update avaliacaopergunta set db103_identificadorcampo = 'dtBase' where db103_sequencial = 3000804;
update avaliacaopergunta set db103_identificadorcampo = 'cnpjSindCategProf' where db103_sequencial = 3000805;
update avaliacaopergunta set db103_identificadorcampo = 'opcFGTS' where db103_sequencial = 3000806;
update avaliacaopergunta set db103_identificadorcampo = 'dtOpcFGTS' where db103_sequencial = 3000807;
update avaliacaogrupopergunta set db102_identificadorcampo = 'trabTemporario' where db102_sequencial = 3000174;
update avaliacaopergunta set db103_identificadorcampo = 'hipLeg' where db103_sequencial = 3000808;
update avaliacaopergunta set db103_identificadorcampo = 'justContr' where db103_sequencial = 3000809;
update avaliacaopergunta set db103_identificadorcampo = 'tpInclContr' where db103_sequencial = 3000810;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideTomadorServ' where db102_sequencial = 3000175;
update avaliacaopergunta set db103_identificadorcampo = 'tpInsc' where db103_sequencial = 3000811;
update avaliacaopergunta set db103_identificadorcampo = 'nrInsc' where db103_sequencial = 3000812;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideEstabVinc' where db102_sequencial = 3000176;
update avaliacaopergunta set db103_identificadorcampo = 'tpInsc' where db103_sequencial = 3000813;
update avaliacaopergunta set db103_identificadorcampo = 'nrInsc' where db103_sequencial = 3000814;
update avaliacaogrupopergunta set db102_identificadorcampo = 'ideTrabSubstituido' where db102_sequencial = 3000177;
update avaliacaopergunta set db103_identificadorcampo = 'cpfTrabSubst' where db103_sequencial = 3000815;
update avaliacaogrupopergunta set db102_identificadorcampo = 'aprend' where db102_sequencial = 3000178;
update avaliacaopergunta set db103_identificadorcampo = 'tpInsc' where db103_sequencial = 3000816;
update avaliacaopergunta set db103_identificadorcampo = 'nrInsc' where db103_sequencial = 3000817;
update avaliacaogrupopergunta set db102_identificadorcampo = 'infoEstatutario' where db102_sequencial = 3000179;
update avaliacaopergunta set db103_identificadorcampo = 'indProvim' where db103_sequencial = 3000818;
update avaliacaopergunta set db103_identificadorcampo = 'tpProv' where db103_sequencial = 3000819;
update avaliacaopergunta set db103_identificadorcampo = 'dtNomeacao' where db103_sequencial = 3000820;
update avaliacaopergunta set db103_identificadorcampo = 'dtPosse' where db103_sequencial = 3000821;
update avaliacaopergunta set db103_identificadorcampo = 'dtExercicio' where db103_sequencial = 3000822;
update avaliacaopergunta set db103_identificadorcampo = 'tpPlanRP' where db103_sequencial = 3000823;
update avaliacaogrupopergunta set db102_identificadorcampo = 'infoDecJud' where db102_sequencial = 3000180;
update avaliacaopergunta set db103_identificadorcampo = 'nrProcJud' where db103_sequencial = 3000824;
update avaliacaogrupopergunta set db102_identificadorcampo = 'infoContrato' where db102_sequencial = 3000181;
update avaliacaopergunta set db103_identificadorcampo = 'codCargo' where db103_sequencial = 3000825;
update avaliacaopergunta set db103_identificadorcampo = 'codFuncao' where db103_sequencial = 3000826;
update avaliacaopergunta set db103_identificadorcampo = 'codCateg' where db103_sequencial = 3000827;
update avaliacaopergunta set db103_identificadorcampo = 'codCarreira' where db103_sequencial = 3000828;
update avaliacaopergunta set db103_identificadorcampo = 'dtIngrCarr' where db103_sequencial = 3000829;
update avaliacaogrupopergunta set db102_identificadorcampo = 'remuneracao' where db102_sequencial = 3000182;
update avaliacaopergunta set db103_identificadorcampo = 'vrSalFx' where db103_sequencial = 3000830;
update avaliacaopergunta set db103_identificadorcampo = 'undSalFixo' where db103_sequencial = 3000831;
update avaliacaopergunta set db103_identificadorcampo = 'dscSalVar' where db103_sequencial = 3000832;
update avaliacaogrupopergunta set db102_identificadorcampo = 'duracao' where db102_sequencial = 3000183;
update avaliacaopergunta set db103_identificadorcampo = 'tpContr' where db103_sequencial = 3000833;
update avaliacaopergunta set db103_identificadorcampo = 'dtTerm' where db103_sequencial = 3000834;
update avaliacaopergunta set db103_identificadorcampo = 'clauAsseg' where db103_sequencial = 3000835;
update avaliacaogrupopergunta set db102_identificadorcampo = 'localTrabGeral' where db102_sequencial = 3000184;
update avaliacaopergunta set db103_identificadorcampo = 'tpInsc' where db103_sequencial = 3000836;
update avaliacaopergunta set db103_identificadorcampo = 'nrInsc' where db103_sequencial = 3000837;
update avaliacaopergunta set db103_identificadorcampo = 'descComp' where db103_sequencial = 3000838;
update avaliacaogrupopergunta set db102_identificadorcampo = 'localTrabDom' where db102_sequencial = 3000185;
update avaliacaopergunta set db103_identificadorcampo = 'tpLograd' where db103_sequencial = 3000839;
update avaliacaopergunta set db103_identificadorcampo = 'dscLograd' where db103_sequencial = 3000840;
update avaliacaopergunta set db103_identificadorcampo = 'nrLograd' where db103_sequencial = 3000841;
update avaliacaopergunta set db103_identificadorcampo = 'complemento' where db103_sequencial = 3000842;
update avaliacaopergunta set db103_identificadorcampo = 'bairro' where db103_sequencial = 3000843;
update avaliacaopergunta set db103_identificadorcampo = 'cep' where db103_sequencial = 3000844;
update avaliacaopergunta set db103_identificadorcampo = 'codMunic' where db103_sequencial = 3000845;
update avaliacaopergunta set db103_identificadorcampo = 'uf' where db103_sequencial = 3000846;
update avaliacaogrupopergunta set db102_identificadorcampo = 'horContratual' where db102_sequencial = 3000186;
update avaliacaopergunta set db103_identificadorcampo = 'qtdHrsSem' where db103_sequencial = 3000847;
update avaliacaopergunta set db103_identificadorcampo = 'tpJornada' where db103_sequencial = 3000848;
update avaliacaopergunta set db103_identificadorcampo = 'dscTpJorn' where db103_sequencial = 3000849;
update avaliacaopergunta set db103_identificadorcampo = 'tmpParc' where db103_sequencial = 3000850;
update avaliacaopergunta set db103_identificadorcampo = 'dia' where db103_sequencial = 3000851;
update avaliacaogrupopergunta set db102_identificadorcampo = 'filiacaoSindical' where db102_sequencial = 3000187;
update avaliacaopergunta set db103_identificadorcampo = 'cnpjSindTrab' where db103_sequencial = 3000852;
update avaliacaogrupopergunta set db102_identificadorcampo = 'sucessaoVinc' where db102_sequencial = 3000188;
update avaliacaopergunta set db103_identificadorcampo = 'cnpjEmpregAnt' where db103_sequencial = 3000853;
update avaliacaopergunta set db103_identificadorcampo = 'matricAnt' where db103_sequencial = 3000854;
update avaliacaopergunta set db103_identificadorcampo = 'dtIniVinculo' where db103_sequencial = 3000855;
update avaliacaopergunta set db103_identificadorcampo = 'observacao' where db103_sequencial = 3000856;
update avaliacaogrupopergunta set db102_identificadorcampo = 'afastamento' where db102_sequencial = 3000189;
update avaliacaopergunta set db103_identificadorcampo = 'dtIniAfast' where db103_sequencial = 3000857;
update avaliacaopergunta set db103_identificadorcampo = 'codMotAfast' where db103_sequencial = 3000858;
update avaliacaogrupopergunta set db102_identificadorcampo = 'desligamento' where db102_sequencial = 3000190;
update avaliacaopergunta set db103_identificadorcampo = 'dtDeslig' where db103_sequencial = 3000859;
SQL
        );
    }

    public function down()
    {
        // dow M9756EnvioEsocialApi remove coluna alterada
    }
}
