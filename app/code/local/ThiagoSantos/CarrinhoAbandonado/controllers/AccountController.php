<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 *
 * @todo    Parametros do Google Analytics no pelo sistema.
 * 
 */

class ThiagoSantos_CarrinhoAbandonado_AccountController extends Mage_Core_Controller_Front_Action {

      
      protected function _getSession() {
            return Mage::getSingleton('customer/session');
      }

      /**
       * Converte o parametro, loga o usuário, redireciona para o /checkou/cart com o carrinho abandonado
       */
      public function accessAction() {

            $id = $this -> getRequest() -> getParam('c');

            if (!isset($id)) {
                  $session -> addError('URL inválida ou usuário inexistente.');
                  $this -> _redirect('/');
            }

            //0 - id //1 - Name //2 - email //3 - data de expiracao
            $csv = Mage::helper('carrinhoabandonado') -> decrypt($id);
            $_customerinfo = explode(';', $csv);

            $customer = Mage::getModel('customer/customer') -> load($_customerinfo[0]);

            $session = Mage::getSingleton('customer/session');
            //logout - necessário!
            $session -> logout();
            //logando novamente
            $session -> setCustomer($customer);

            //Adiciona os produtos no carrinho
            $cart = $customerQuote;

            /**
             * No caso do Magento ter alguma trigger, evento ou ação que delete o carrinho
             * abandonado no login, ou algum outro momento o descomentar o código abaixo faz-se
             * necessário, após estudo e analise.
             */
            /*  foreach($customerQuoteItems as $item ){
             $params = array(
             'product' => $item->getProduct()->getId(),
             'qty' => $item->getQty()
             );
             $cart->addProduct($item->getProduct());//,  $params);
             $cart->save();
             }
             */

            Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

            //Campanhas do Google Analytics
            //redirect
            //?utm_source=carrinho_abandonado&utm_medium=email&utm_campaign=Carrinho%2BAbandonado

            $_params = array('_query' => array( 'utm_source' => 'carrinho_abandonado', 
                                                'utm_medium' => 'email',
                                                'utm_campaign' => 'Carrinho%2BAbandonado')
                                              );

            $this -> _redirect('checkout/cart/', $_params);
      }

}
