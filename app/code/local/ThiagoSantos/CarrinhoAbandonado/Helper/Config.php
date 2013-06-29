<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 */

class ThiagoSantos_CarrinhoAbandonado_Helper_Config extends Mage_Core_Helper_Abstract {


	function getField($field = null){
		
		if(is_null($field) || $field == "")
			return false;

		return Mage::getStoreConfig("carrinhoabandonado/".$field);	
	

	}

}
