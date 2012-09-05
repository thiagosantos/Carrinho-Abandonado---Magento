<?php

class ThiagoSantos_CarrinhoAbandonado_Model_Ordersave_Observer {
      
      public function addCookieToOrder($observer,$_a=null){
            Mage::log("observer iniciado");
           
           try{ 
            $order = new Mage_Sales_Model_Order();
            $incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            $order->loadByIncrementId($incrementId);
 
            $cookieModel = Mage::getModel("carrinhoabandonado/cookie");
            
            $cookies = $cookieModel->get();
            //orderInfos
            $order->setTsUtmSource($cookies['utm_source']);
            $order->setTsUtmMedium($cookies['utm_medium']);
            $order->setTsUtmCampaign($cookies['utm_campaign']);
            
            $order->save();
            
            //adiciona a conversão a campanha do carrinho abandonado
            $campanhas = Mage::getModel("carrinhoabandonado/campanha")
                              ->getCollection()
                              ->addFieldToFilter("nome",$cookies['utm_campaign']);
            
            
                              
            if($campanhas->getSize()>=1)
            {
                  Mage::log("campanha encontrada");
               $campanha = $campanhas->getFirstItem();               
               $campanha->setTotalEmailsRevertidos($campanha->getTotalEmailsRevertidos()+1);
               $campanha->save();
               
               $temail = Mage::getModel('carrinhoabandonado/email') 
                                    -> getCollection() 
                                    -> addFieldToFilter('campanhaid', 
                                                            array('eq' => $campanha->getCampanhaid())
                                                        ) 
                                    -> addFieldToFilter('email', 
                                                            array('eq' => $order->getCustomerEmail() )
                                                      );
                  if($temail->getSize()>=1){
                        Mage::log("email encontrado");
                        $email = $temail->getFirstItem();
                        $email->setOrderId($incrementId);
                        $email->setTotalPedidoComprado($order->getGrandTotal());
                        $email->setRevertido("SIM");
                        $email->setRevertidoEm(date("Y-m-d H:i:s"));
                        $email->save();
                  }                                
                  
                  Mage::log("campanha finalizada");                                        
                  
            }
            
            Mage::log("observer fechado");
            return $order;
           }
           catch(Exception $e){}
      }
      
}
