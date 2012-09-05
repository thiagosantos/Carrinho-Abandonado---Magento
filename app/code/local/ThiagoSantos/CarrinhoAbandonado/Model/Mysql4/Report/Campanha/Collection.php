<?php

class ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Report_Campanha_Collection
extends ThiagoSantos_CarrinhoAbandonado_Model_Mysql4_Abstract {

      public function _construct() {
            parent::_construct();
            $this -> _init('carrinhoabandonado/campanha');
      }

      protected function _joinFields($from = '', $to = '') {
            $this -> addFieldToFilter('created_at', array("from" => $from, "to" => $to, "datetime" => true));
            $this -> getSelect() -> group('nome');
            //$this -> getSelect() -> columns(array('value' => 'SUM(value)'));

            return $this;
      }

      public function setDateRange($from, $to) {
            $this -> _reset() -> _joinFields($from, $to);
            return $this;
      }

      public function load($printQuery = false, $logQuery = false) {
            if ($this -> isLoaded()) {
                  return $this;
            }
            parent::load($printQuery, $logQuery);
            return $this;
      }

      public function setStoreIds($storeIds) {
            return $this;
      }

}
