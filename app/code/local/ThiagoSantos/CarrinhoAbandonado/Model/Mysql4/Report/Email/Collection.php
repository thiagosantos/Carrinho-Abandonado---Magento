<?php
 
class ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Report_Email_Collection 
extends ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Abstract
{
     
      
    public function _construct()
    {
        parent::_construct();
        $this->_init('carrinhoabandonado/email');
    }
    
    public function setCampanhaid($campanhaid=null){
          if(!is_null($campanhaid))
            $this->_addFieldToFilter('campanhaid', array('eq'=>$campanhaid));
          
          return $this;
    }

}