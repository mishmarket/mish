<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Scheckout
*/
class Amasty_Scheckout_Model_Mysql4_Area_Store extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amscheckout/area_store', 'area_store_id');
    }
}