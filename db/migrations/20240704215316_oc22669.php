<?php

use Phinx\Migration\AbstractMigration;

class Oc22669 extends AbstractMigration
{

    public function up()
    {
        $sql = "
        BEGIN;
        update itbi.paritbi set it24_inclusaoautomaticapromitente = false where it24_inclusaoautomaticapromitente is null;
        alter table itbi.paritbi alter column it24_inclusaoautomaticapromitente set not null;
        alter table itbi.paritbi alter column it24_inclusaoautomaticapromitente set default false;
        COMMIT;
        ";
        $this->execute($sql);
    }
}