<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 */

class ThiagoSantos_CarrinhoAbandonado_Helper_Log extends Mage_Core_Helper_Abstract {

    static private $t = null;

    static function getSingleton(){
        if(is_null(ThiagoSantos_CarrinhoAbandonado_Helper_Log::t))
            ThiagoSantos_CarrinhoAbandonado_Helper_Log::$t = new ThiagoSantos_CarrinhoAbandonado_Helper_Log();

        return ThiagoSantos_CarrinhoAbandonado_Helper_Log::$t;
    }

    /**
     * @param $message
     */
    function log($message){
        if(Mage::helper("carrinhoabandonado/config")->getField("debug/active"))
            return Mage::log($message,null,"carrinhoabandonado.log");
    }

}
