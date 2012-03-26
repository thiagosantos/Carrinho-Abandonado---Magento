<?php

class ThiagoSantos_CarrinhoAbandonado_Model_Gerenciador{



	/**
	 * Quote Collection
	 */
	private $_quoteCollections = null;

    public function __construct(){
          $this->loadCollection();
    }
      
      /**
       * iniciador do processo
       */
      public function _init(){            
         
            $collection = $this->getCollection();
            
            foreach($collection as $quote){
               $this->sendMailAbandonedCart($quote);
            }
            
      }
      

	/**
	 * Carrega uma coleção de cotação realizados pelos clientes.
	 * Seria o mesmo que o carrinho abandonado.
	 *
	 * @todo Adicionar opção de tempo apartir da data atual,removendo os dias definido no adim.
	 * 	 Qtd dias atrás para enviar o email
	 */
	private function loadCollection(){
             Mage::log("carrinho abandonado - loadCollection ");
             
		if($this->_quoteCollections != null)
			return $this->_quoteCollections;
		
		$collection = Mage::getResourceModel('reports/quote_collection');
		$collection->prepareForAbandonedReport($storeIds, $filter = null);
           // $__dataIntervaloInit = date('Y-m-d', strtotime('-15 days', time()));
          //  $__dataIntervaloEnd = date('Y-m-d', strtotime('-60 days', time()));
            
            $__dataIntervalo = date('Y-m-d', strtotime('-7 days', time()));
		/*
            $collection->getSelect()->where(  
		                                  "main_table.created_at <= '".$__dataIntervaloInit.
		                                  " 23:59:59' and main_table.created_at >= '".$__dataIntervaloEnd." 00:00:00'"
                                          );
             * */
	     $collection->getSelect()->where("main_table.created_at <= '".$__dataIntervalo." 23:59:59' and main_table.created_at >= '".$__dataIntervalo." 00:00:00'");
          
	     $collection->load();
            
           return $this->_quoteCollections = $collection;
	}

      /**
       * Retorna uma coleção de cotação
       */
      private function getCollection(){
           
            if($this->_quoteCollections != null)
                  return $this->_quoteCollections;
            
            return $this->loadCollection();
      }

	
	/**
       * Envia o email por quote
       */
	private function sendMailAbandonedCart($quote){
	      Mage::log("carrinho abandonado - sendMailAbandonedCart ");
            
		$translate = Mage::getSingleton('core/translate');
		/* @var $translate Mage_Core_Model_Translate */
		$translate->setTranslateInline(false);
            
            /**
             * Customer
             */
            $customer = Mage::getModel('customer/customer')->load($quote->getCustomerId());
            $hash = Mage::helper('carrinhoabandonado')->encrypt($customer);
            
            /**
             * envia eMail
             */
            
            //Configuracao do sistema
            $templateEmail =  Mage::getStoreConfig('carrinhoabandonado/config/ca_email_template');
            $emailRemetente = Mage::getStoreConfig('carrinhoabandonado/config/ca_email_identity');
            //No caso de template invalido usa o default do projeto
            if(   !isset($templateEmail) || $templateEmail==null 
                  || $templateEmail == "" 
                  || $templateEmail=='carrinhoabandonado_config_ca_email_template'
              )
                  $templateEmail = 'carrinhoabandonado_email_default';
                  
                  
           Mage::getModel('core/email_template')
			->setDesignConfig(array('area'=>'frontend', 'store'=>1))
                  ->addBcc('tsantos@elnetcorp.com.br')
			->sendTransactional(
				$templateEmail,
				$emailRemetente,
				$customer->getEmail(),
				$customer->getName(),
				array('customer' => $customer, 'quote'=>$quote, 'hash' => $hash)
                 );
           
		$translate->setTranslateInline(true);
	}


}
