<?php

class ThiagoSantos_CarrinhoAbandonado_Model_Renderlayout_Observer {

      private $__cookieModel = "null";
      
      public function __construct() {
      
            
          
      }
      
      /**
       * return array contendo as informacoes do parametro do google analytics
       */
      private function _loadRefererCampaign(){
            
            //Cookie information
            $_cookie = $this->__cookieModel->get();
            
            
            //Request parameters
            $_requestie['utm_source']     = Mage::app()->getRequest()->getParam('utm_source');
            $_requestie['utm_medium']     = Mage::app()->getRequest()->getParam('utm_medium');
            $_requestie['utm_campaign']   = Mage::app()->getRequest()->getParam('utm_campaign');
            $_requestie['request']        = "1";
            
            //Preferencia
            
            if(   $_requestie['utm_source'] && 
                  $_requestie['utm_medium'] && 
                  $_requestie['utm_medium']
              ){
                  return $_requestie;
              }
            
            
            return $_cookie;
      }
      
      
      /**
       * _init manager referer information from the user
       * @param   Varien_Event_Observer $observer
       * @return  ThiagoSantos_CarrinhoAbandonado_Model_Renderlayout_Observer
       */
      public function init($observer,$layout=null) {
            $this->__cookieModel = Mage::getModel("carrinhoabandonado/cookie");
            $_refererInformation = $this->_loadRefererCampaign();
            //seta o cookie
            $this->__cookieModel->set($_refererInformation);           
            return $this;
      }

}
