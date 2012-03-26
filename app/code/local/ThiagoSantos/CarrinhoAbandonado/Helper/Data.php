<?php
/**
* Pereira, Thiago Santos
* http://thiagosantos.com/ 
*
* @category   ThiagoSantos
* @package    ThiagoSantos_CarrinhoAbandonado
*/

 
class ThiagoSantos_CarrinhoAbandonado_Helper_Data extends Mage_Core_Helper_Abstract
{

      public function encrypt($customer, $urlencode = false){
            
            //montar hash
            $id         = $customer->getId();
            $name       = "";//$customer->getName();
            $email      = $customer->getEmail();
            
            $crypt = Mage::getModel('core/encryption');
            return $crypt->encrypt($id.';'.$name.';'.$email);
      }
      
      public function decrypt($d){
            $crypt = Mage::getModel('core/encryption');
            return $crypt->decrypt($d);
            
      }

}
