<?php

namespace App\Repositories\Patrimonial\Compras;

use App\Models\Patrimonial\Compras\Pcorcamitem;
use Illuminate\Support\Facades\DB;

class PcorcamitemRepository
{
    private Pcorcamitem $model;

    public function __construct()
    {
        $this->model = new Pcorcamitem();
    }

    public function all()
    {
        return Pcorcamitem::all();
    }

    /**
     * Busca um item pelo ID.
     *
     * @param int $id
     * @return Pcorcamitem
     */
    public function find($id)
    {
        return Pcorcamitem::findOrFail($id);
    }

    public function insert($dados): Pcorcamitem
    {
        $dados['pc22_orcamitem'] = $this->model->getNextval();
        return $this->model->create($dados);
    }

    public function excluir($pc22_orcamitem)
    {
        $sql = "DELETE FROM pcorcamitem WHERE pc22_orcamitem IN ($pc22_orcamitem)";
        return DB::statement($sql);
    }
}
