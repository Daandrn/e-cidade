<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13051 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-PostgresMigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $sql = "SELECT setval('conplano_c60_codcon_seq',
                  (SELECT max(c60_codcon)
                   FROM conplano))";
        $sql1 = "SELECT setval('conplanoreduz_c61_reduz_seq',
                  (SELECT max(c61_reduz)
                   FROM conplanoreduz))";
        $this->execute($sql);
        $this->execute($sql1);
    }
}
