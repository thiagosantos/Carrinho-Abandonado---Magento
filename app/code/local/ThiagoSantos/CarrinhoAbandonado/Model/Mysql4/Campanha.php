<?php
 
class ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Campanha extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('carrinhoabandonado/campanha', 'campanhaid');
    }
  
}
