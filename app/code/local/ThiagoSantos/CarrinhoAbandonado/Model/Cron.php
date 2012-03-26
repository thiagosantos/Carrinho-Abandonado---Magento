<?php

/**
 * Cron - Carrinho abandonado
 */
class ThiagoSantos_CarrinhoAbandonado_Model_Cron extends Varien_Object{



      /**
       * Execute
       */
	public function execute($observer){
	      Mage::log("carrinho abandonado ... ");
		$gerenciador = Mage::getModel('carrinhoabandonado/gerenciador');
            $gerenciador->_init();
            
            return $this;
	}


}
