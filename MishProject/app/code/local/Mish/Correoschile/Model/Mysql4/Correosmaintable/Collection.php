<?php

class Mish_Correoschile_Model_Mysql4_Correosmaintable_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('correoschile/correosmaintable');
    }
}