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

      public function adodoAction() {
            
            $email = Mage::getModel("carrinhoabandonado/email");;
            $email -> setCampanhaid(1);
            $email -> setEmail("tsantos@elnetcorp.com.br");
            $email -> setRevertido("NAO");
            $email -> save();
            
            exit();

            try {
                  $campanhas = Mage::getModel("carrinhoabandonado/campanha") -> getCollection() -> addFieldToFilter('campanhaid', array('eq' => 1)) -> addAttributeToSort('campanhaid', 'desc');
            } catch(Exception $e) {
                  var_dump($e);
            }
            var_dump($campanhas -> getSize());
            exit();
            //$campanha = $campanhas->getFirstItem();

            echo $campanha -> getNome();
            exit();
            foreach ($campanhas as $campanha) {
                  echo $campanha -> getNome();
                  echo "<Br>";

            }

            echo "<br><br>-----------<br><br>";

            $campanhas = Mage::getModel("carrinhoabandonado/campanha") -> getCollection() -> addAttributeToSort('campanhaid', 'desc');

            foreach ($campanhas as $campanha) {
                  echo $campanha -> getNome();
                  echo "<Br>";
            }

            var_dump($campanha);
            // $campanha->getFirstItem();
            // echo $campanha->getNome();
            exit();
            var_dump($campanha -> addAttributeToFilter("total_emails_enviados", array('eq' => '0')));

            exit();

            $campanha -> setNome("Campanha 2012 - 2");
            //$campanha->save();

            //echo $campanha->getCampanhaid();

            $email = Mage::getModel("carrinhoabandonado/email") -> load(1);
            $email -> setCampanhaid($campanha -> getCampanhaid());
            $email -> setEmail("thiagorow@gmail.com");
            $email -> setRevertido("NAO");
            $email -> setRevertidoEm(date('Y-m-d H:i:s', time()));
            $email -> save();

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
            
            //parametros para o redirect
            //Campanhas do Google Analytics
            //redirect
            //?utm_source=carrinho_abandonado&utm_medium=email&utm_campaign=Carrinho%2BAbandonado
            $_params = array('_query' => array('utm_source' => 'carrinho_abandonado', 'utm_medium' => 'email', 'utm_campaign' => 'Carrinho%2BAbandonado'));
            
            //se houver a campanha no email
            if ($_customerinfo[3]) {
                  $temail = Mage::getModel('carrinhoabandonado/email') 
                                    -> getCollection() 
                                    -> addFieldToFilter('campanhaid', array('eq' => $_customerinfo[3])) 
                                    -> addFieldToFilter('customer_id', array('eq' => $_customerinfo[0]));

                  if ($temail -> getSize() >= 1) 
                  {
                        $campanha = Mage::getModel('carrinhoabandonado/campanha') -> load($_customerinfo[3]);
                        $email = $temail -> getFirstItem();
                        //Se já estiver sido aberto, ignora
                        if (!$email -> getAberto()){
                              $email -> setAberto(1);    
                              $campanha -> setTotalEmailsAbertos($campanha -> getTotalEmailsAbertos() + 1);
                        }
                        //Se já tiver sido clicado, ignora
                        if(!$email->getClicado()){
                              $email -> setClicado(1);    
                              $campanha -> setTotalEmailsClicados($campanha -> getTotalEmailsClicados() + 1);
                        }
                        
                        $_params = array('_query' => array('utm_source' => 'carrinho_abandonado', 'utm_medium' => 'email', 'utm_campaign' => $campanha->getNome()));
                        
                        //email
                        $email->save();
                        //campanha
                        $campanha->save();
                  }
            }

            Mage::getSingleton('checkout/session') -> setCartWasUpdated(true);


            $this -> _redirect('checkout/cart/', $_params);
      }

}
