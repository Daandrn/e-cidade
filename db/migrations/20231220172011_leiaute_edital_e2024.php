<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class LeiauteEditalE2024 extends PostgresMigration
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
        $sql = "

        BEGIN;

            ALTER TABLE obrasdadoscomplementares ADD COLUMN db150_planilhatce Integer;

            ALTER TABLE obrasdadoscomplementareslote ADD COLUMN db150_planilhatce Integer;

            ALTER TABLE liclancedital ADD COLUMN l47_email varchar(200);

        COMMIT;

        ";
        $this->execute($sql);
    }
}
