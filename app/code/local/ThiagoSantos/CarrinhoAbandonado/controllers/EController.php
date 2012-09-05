<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 *
 *
 */

class ThiagoSantos_CarrinhoAbandonado_EController extends Mage_Core_Controller_Front_Action {

      public function indexAction() {
            $id = $this -> getRequest() -> getParam('c');

            //Nao estiver setado, volta.
            if (!isset($id)) {
                  return;
            }

            $csv = Mage::helper('carrinhoabandonado') -> decrypt($id);
            $_customerinfo = explode(';', $csv);

            $temail = Mage::getModel('carrinhoabandonado/email') -> getCollection() -> addFieldToFilter('campanhaid', array('eq' => $_customerinfo[3])) -> addFieldToFilter('customer_id', array('eq' => $_customerinfo[0]));

            if ($temail -> getSize() >= 1) {

                  $email = $temail -> getFirstItem();
                  //Se já estiver sido aberto, ignora
                  if ($email -> getAberto())
                        return;

                  $email -> setAberto(1);
                  $email -> save();
                  //Campanha
                  $campanha = Mage::getModel('carrinhoabandonado/campanha') -> load($_customerinfo[3]);
                  $campanha -> setTotalEmailsAbertos($campanha -> getTotalEmailsAbertos() + 1);
                  $campanha -> save();
            }

      }



}
            