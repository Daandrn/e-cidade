<?php

use ECidade\Suporte\Phinx\PostgresMigration;

class Oc13171 extends PostgresMigration
{
    CONST PMPIRAPORA = '23539463000121';

    public function up()
    {
        $arrInstit = $this->getInstituicaoByCnpj(self::PMPIRAPORA);
        if(!empty($arrInstit)) {
            $this->execute("update discla set dtaute = dtcla where codcla in (3116,3117,3118,3120,3121,3124,3127,3128,3129,3130,3217,3303) and dtaute is null");
        }
    }

    public function down()
    {
        $arrInstit = $this->getInstituicaoByCnpj(self::PMPIRAPORA);
        if(!empty($arrInstit)) {
            $this->execute("update discla set dtaute = null where codcla in (3116,3117,3118,3120,3121,3124,3127,3128,3129,3130,3217,3303) and dtaute = dtcla");
        }
    }

    /**
     * Verifica se existe uma instituição para o codcli
     * @param string $cnpj
     * @return Array
     */
    public function getInstituicaoByCnpj($cnpj = NULL)
    {
        $arr = array();
        if($cnpj){
            $sSql = "select codigo from db_config where cgc = '{$cnpj}'";
            $arr = $this->fetchAll($sSql);
        }
        return $arr;
    }
}
