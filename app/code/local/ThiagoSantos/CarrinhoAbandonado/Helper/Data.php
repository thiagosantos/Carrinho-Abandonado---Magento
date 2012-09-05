<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 */

class ThiagoSantos_CarrinhoAbandonado_Helper_Data extends Mage_Core_Helper_Abstract {

      public function encrypt($customer, $_otherVars=array('campanhaid'=>'')) {

            //montar hash
            $id = $customer -> getId();
            $email = $customer -> getEmail();
            $name = "";
            
            

            $crypt = Mage::getModel('core/encryption');
            return $crypt -> encrypt($id . ';' . $name . ';' . $email . ';' . $_otherVars['campanhaid']);
      }

      public function decrypt($d) {
            $crypt = Mage::getModel('core/encryption');
            return $crypt -> decrypt($d);

      }

}
