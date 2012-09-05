<?php

class ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Abstract extends Mage_Core_Model_Mysql4_Collection_Abstract
{
      
    public function addAttributeToSort($attribute, $dir = "asc")
    {
            if (!is_string($attribute)) {
                  return $this;
            }
            $this -> setOrder($attribute, $dir);
            return $this;
      }
      
      public function addFieldToFilter($field, $condition=null)
      {
        
        $field = $this->_getMappedField($field);
        $this->_select->where($this->_getConditionSql($field, $condition), null);
        return $this;
      }   
}
