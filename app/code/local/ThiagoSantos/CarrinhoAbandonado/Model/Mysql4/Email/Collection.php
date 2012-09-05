<?php
 
class ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Email_Collection extends ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Abstract
{
     
      
    public function _construct()
    {
        parent::_construct();
        $this->_init('carrinhoabandonado/email');
    }

}