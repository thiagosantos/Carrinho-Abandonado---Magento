<?php

class ThiagoSantos_CarrinhoAbandonado_AccountController extends Mage_Core_Controller_Front_Action
{
      
      protected function _getSession(){
            return Mage::getSingleton('customer/session');
      }
 
      public function accessAction(){
            
            $id = $this->getRequest()->getParam('c');
            
            
           
           if(!isset($id)){
                   $session->addError('URL inválida ou usuário inexistente.');
                  $this->_redirect('/');
            }
            
            //0 - id //1 - Name //2 - email //3 - data de expiracao
            $csv = Mage::helper('carrinhoabandonado')->decrypt($id);
            $_customerinfo = explode(';', $csv);

         //   print_r($_customerinfo );
            //Customer
            $customer = Mage::getModel('customer/customer')->load($_customerinfo[0]);
            
          //  echo $customer->getName();
                        
           // $customerQuote = Mage::getModel('sales/quote')
            //                  ->loadByCustomer($customer->getId());
           
           //$customerQuoteItems = $customerQuote->getAllItems();
            
           
            $session = Mage::getSingleton('customer/session');
            //logout
            $session->logout();
            //logando novamente
            $session->setCustomer($customer);
           
            
            //Adiciona os produtos no carrinho
             $cart = $customerQuote;
                        
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
            
            //redirect 
            //?utm_source=carrinho_abandonado&utm_medium=email&utm_campaign=Carrinho%2BAbandonado
            
            $_params=   array('_query'=> array( 'utm_source'=>'carrinho_abandonado',
                                                'utm_medium'=>'email',
                                                'utm_campaign'=>'Carrinho%2BAbandonado'
                                               ) );
            
            $this->_redirect('checkout/cart/', $_params);
      }       
       
      
}
