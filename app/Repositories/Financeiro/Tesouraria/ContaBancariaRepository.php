<?php

namespace App\Repositories\Financeiro\Tesouraria;

use App\Domain\Financeiro\Tesouraria\ContaBancarias;
use App\Models\Financeiro\Tesouraria\ContaBancaria;
use App\Repositories\Financeiro\Tesouraria\ContaBancariaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContaBancariaRepository implements ContaBancariaRepositoryInterface
{
    private ContaBancaria $model;

    public function __construct()
    {
        $this->model = new ContaBancaria;
    }

    public function saveByContaBancaria(ContaBancarias $dadosContaBancaria): ?ContaBancaria
    {

        $dados = [
            "db83_descricao"          => $dadosContaBancaria->getDb83Descricao(),
            "db83_bancoagencia"       => $dadosContaBancaria->getDb83Bancoagencia(),
            "db83_conta"              => $dadosContaBancaria->getDb83Conta(),
            "db83_dvconta"            => $dadosContaBancaria->getDb83DvConta(),
            "db83_identificador"      => $dadosContaBancaria->getDb83Identificador(),
            "db83_codigooperacao"     => $dadosContaBancaria->getDb83Codigooperacao(),
            "db83_tipoconta"          => $dadosContaBancaria->getDb83Tipoconta(),
            "db83_contaplano"         => $dadosContaBancaria->getDb83Contaplano(),
            "db83_convenio"           => $dadosContaBancaria->getDb83Convenio(),
            "db83_tipoaplicacao"      => $dadosContaBancaria->getDb83Tipoaplicacao(),
            "db83_numconvenio"        => $dadosContaBancaria->getDb83Numconvenio(),
            "db83_nroseqaplicacao"    => $dadosContaBancaria->getDb83Nroseqaplicacao(),
            "db83_codigoopcredito"    => $dadosContaBancaria->getDb83Codigoopcredito(),

        ];

        return $this->model->create($dados);
    }

    public function update(int $sequencialcontabancaria, array $dadosContaBancaria): bool
    {

        return DB::table('contabancaria')->where('db83_sequencial',$sequencialcontabancaria)->update($dadosContaBancaria);

    }

    public function delete(int $sequencialcontabancaria): bool
    {

        return DB::table('contabancaria')->where('db83_sequencial',$sequencialcontabancaria)->delete();

    }

    public function searchSequentialAccounts(): ?Collection
    {
        $result = $this->model
                ->orderBy('db83_sequencial', 'desc')
                ->limit(1)
                ->get(['db83_sequencial']);

        return $result;
    }

    public function checkAccountExists(int $sequencial): ?Collection
    {
        $result = $this->model
                ->where('db83_sequencial', $sequencial)
                ->limit(1)
                ->get(['db83_sequencial']);

        return $result;
    }

    public function checkAllTables(int $sequencial,int $reduzido,int $instituicao): ?Collection
    {
        $result = $this->model
        ->join('conplanocontabancaria', function ($join) {
            $join->on('contabancaria.db83_sequencial', '=', 'conplanocontabancaria.c56_contabancaria');
        })
        ->join('conplano', function ($join) {
            $join->on('conplanocontabancaria.c56_codcon', '=', 'conplano.c60_codcon')
                 ->on('conplanocontabancaria.c56_anousu', '=', 'conplano.c60_anousu');
        })
        ->join('conplanoreduz', function ($join) {
            $join->on('conplanocontabancaria.c56_codcon', '=', 'conplanoreduz.c61_codcon')
                 ->on('conplanocontabancaria.c56_anousu', '=', 'conplanoreduz.c61_anousu');
        })
        ->join('saltes', function ($join) {
            $join->on('saltes.k13_reduz', '=', 'conplanoreduz.c61_reduz')
                 ->on('conplanoreduz.c61_anousu', '=', 'conplano.c60_anousu');
        })
        ->join('conplanoconta', function ($join) {
            $join->on('conplanoconta.c63_codcon', '=', 'conplanocontabancaria.c56_codcon')
                 ->on('conplanoconta.c63_anousu', '=', 'conplanoreduz.c61_anousu');
        })
        ->join('empagetipo', function ($join) {
            $join->on('empagetipo.e83_conta', '=', 'saltes.k13_conta');
        })
        ->join('conplanoexe', function ($join) {
            $join->on('conplanoexe.c62_reduz', '=', 'saltes.k13_reduz')
                 ->on('conplanoexe.c62_anousu', '=', 'conplanoreduz.c61_anousu');
        })
        ->join('conplanocontacorrente', function ($join) {
            $join->on('conplanocontacorrente.c18_codcon', '=', 'conplanocontabancaria.c56_codcon')
                 ->on('conplanocontacorrente.c18_anousu', '=', 'conplanoreduz.c61_anousu');
        })
        ->where('contabancaria.db83_sequencial', $sequencial )
        ->where('conplanoreduz.c61_reduz', $reduzido )
        ->where('conplanoreduz.c61_instit', $instituicao )
        ->limit(1)
        ->get([
            'contabancaria.*',
            'conplanoconta.*',
            'conplanocontabancaria.*',
            'conplano.*',
            'conplanoreduz.*',
            'saltes.*',
            'empagetipo.*',
            'conplanoexe.*',
            'conplanocontacorrente.*'
        ]);

        return $result;
    }

    public function checkRepeated(int $agencia, string $conta, int $tipoconta, string $fonte, int $nroseqaplicacao): ?Collection
    {

        $result = $this->model
            ->join('conplanocontabancaria', 'contabancaria.db83_sequencial', '=', 'conplanocontabancaria.c56_contabancaria')
            ->join('conplano', 'conplanocontabancaria.c56_codcon', '=', 'conplano.c60_codcon')
            ->join('conplanoreduz', 'conplanocontabancaria.c56_codcon', '=', 'conplanoreduz.c61_codcon')
            ->join('saltes', 'saltes.k13_reduz', '=', 'conplanoreduz.c61_reduz')
            ->join('conplanoconta', 'conplanoconta.c63_codcon', '=', 'conplanocontabancaria.c56_codcon')
            ->join('empagetipo', 'empagetipo.e83_conta', '=', 'saltes.k13_reduz')
            ->join('conplanoexe', 'conplanoexe.c62_reduz', '=', 'saltes.k13_reduz')
            ->join('conplanocontacorrente', 'conplanocontacorrente.c18_codcon', '=', 'conplanocontabancaria.c56_codcon')
            ->where('contabancaria.db83_bancoagencia', $agencia)
            ->where('contabancaria.db83_conta', $conta)
            ->where('contabancaria.db83_tipoconta', $tipoconta)
            ->where('conplanoreduz.c61_codigo', $fonte)
            ->when($nroseqaplicacao, function ($query, $nroseqaplicacao) {
                return $query->where('contabancaria.db83_nroseqaplicacao', $nroseqaplicacao);
            })
            ->limit(1)
            ->get([
                'conplanoreduz.c61_reduz'
            ]);

        return $result;
    }
}
