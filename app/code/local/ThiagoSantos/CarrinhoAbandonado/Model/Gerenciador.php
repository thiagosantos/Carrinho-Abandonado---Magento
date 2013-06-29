<?php

/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 *
 * @todo Adicionar a possibilidade no nome da campanha do Google Analytics substituir ao CAMPANHA_NOME_DEFAULT
 * @todo Adicionar possibilidade de customização de variaveis aos campos do Google Analytics
 * @todo Trocar o Mage::log pelo log do modulo
 * @todo mudar a função __getCampanhaHash() para o Data Helper
 */

 class ThiagoSantos_CarrinhoAbandonado_Model_Gerenciador {

       const CAMPANHA_NOME_DEFAULT  = 'Campanha CA ';
      /**
       * Quote Collection
       */
      private $_quoteCollections    = null;
      private $campanhaAtual        = null;
      
      /**
       * @todo mudar para o helper
       */
      private function __getCampanhaHash(){
            return md5(date('Y-m-d'));
      }

      public function __construct() {
            $this -> loadCollection();
      }
      
      private function createCampanha(){
            
            Mage::log("carrinho abandonado - criando campanha ");
            if($this->_quoteCollections->getSize()<1)
            {
                  throw new Exception("Não foi possível criar a campanha");
	          Mage::log("carrinho abandonado - Não foi possivel criar a campanha. Não há carrinho abandonado suficiente.");
            }
            $campanha = Mage::getModel("carrinhoabandonado/campanha");

	    $g_a_campanha = Mage::helper("carrinhoabandonado/config")->getField("googleanalytics/utm_campaign");
	    if($g_a_campanha)
		$campanha->setNome($g_a_campanha. " - ".date("d/m/Y"));
	    else
	        $campanha->setNome(ThiagoSantos_CarrinhoAbandonado_Model_Gerenciador::CAMPANHA_NOME_DEFAULT." - ".date("d/m/Y"));
            
            $campanha->setCampanhaHash($this->__getCampanhaHash());
            //$campanha->setTotalEmailsEnviados($this->_quoteCollections->getSize());
            $campanha->save();
            $this->campanhaAtual = $campanha;
               Mage::log("carrinho abandonado - campanha criada ");
      }

      /**
       * iniciador do processo
       */
      public function _init() {

          $ativo = Mage::helper("carrinhoabandonado/config")->getField("general/active");

          //Verifica se o modulo está ativo
          if(!$ativo){
              Mage::log("CarrinhoAbandonado:: Disabled");
              return;
          }
            
            
            try{
                  $collection = $this -> getCollection();
                  //$campanhaAtual = null;
            
                  //cria uma nova campanha, ou carrega uma previamente
                  $_campanhahash = $this->__getCampanhaHash();
                  $campanhas = Mage::getModel("carrinhoabandonado/campanha")
                                    ->getCollection()
                                    ->addFieldToFilter('campanha_hash',array('eq'=>$_campanhahash));
                                    //->addAttributeToSort('campanhaid','desc');
                                    
                  if($campanhas->getSize()>=1)
                        $this->campanhaAtual = $campanhas->getFirstItem();
                  else{
                      $this->createCampanha()  ;
                  }
                  
                  //se a campanha já existir
                  /*if($_ultimaCampanha->getCampanhaHash() == $_campanhahash){
                        $this->campanhaAtual = $_ultimaCampanha;
                  }
                  else{
                        //se a campanha não já existir
                        $this->createCampanha();
                  }*/
            }
            catch(Exception $e){
                  //$this->createCampanha();
                  Mage::log("Exception da campanha, não tem emails suficientes para serem enviados");
                  return;
            }
           
            
            //$this->campanhaAtual->setTotalEmailsEnviados($this->_quoteCollections->getSize());
           // $this->save();
            foreach ($collection as $quote) {
                  $this -> sendMailAbandonedCart($quote);
            }

      }

      /**
       * Carrega uma coleção de cotação realizados pelos clientes.
       * Seria o mesmo que o carrinho abandonado.
       *
       * @todo Adicionar opção de tempo apartir da data atual,removendo os dias definido no adim.
       * 	 Qtd dias atrás para enviar o email
       */
      private function loadCollection() {
            Mage::log("carrinho abandonado - loadCollection ");

          //Module Config
          //model variables
          $daysT = Mage::helper("carrinhoabandonado/config")->getField("schedule/days");



            if ($this -> _quoteCollections != null)
                  return $this -> _quoteCollections;

            $collection = Mage::getResourceModel('reports/quote_collection');
            $collection -> prepareForAbandonedReport($storeIds, $filter = null);
            // $__dataIntervaloInit = date('Y-m-d', strtotime('-15 days', time()));
            //  $__dataIntervaloEnd = date('Y-m-d', strtotime('-60 days', time()));

          /**
           * É obrigatório digitar um valor númerico válido, maior que zero
           *
           */
          if(is_null($daysT) || $daysT == "" || $daysT<0 || !is_numeric($daysT))
                $daysT = 5;

            $__dataIntervalo = date('Y-m-d', strtotime("-$daysT days", time()));
            
            //$__dataIntervaloFim = date('Y-m-d', strtotime('-60 days', time()));
            /*
             $collection->getSelect()->where(
             "main_table.created_at <= '".$__dataIntervaloInit.
             " 23:59:59' and main_table.created_at >= '".$__dataIntervaloEnd." 00:00:00'"
             );
             * */
            $collection -> getSelect() -> where("main_table.created_at <= '" . $__dataIntervalo . " 23:59:59' and main_table.created_at >= '" . $__dataIntervalo . " 00:00:00'");
            $collection -> load();

            return $this -> _quoteCollections = $collection;
      }

      /**
       * Retorna uma coleção de cotações
       */
      private function getCollection() {
            if ($this -> _quoteCollections != null)
                  return $this -> _quoteCollections;

            return $this -> loadCollection();
      }

      /**
       * Envia o email por quote
       */
      private function sendMailAbandonedCart($quote) {
            Mage::log("carrinho abandonado - sendMailAbandonedCart ");

            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate -> setTranslateInline(false);

            /**
             * Customer
             */
            $customer = Mage::getModel('customer/customer') -> load($quote -> getCustomerId());
            
            $hash = Mage::helper('carrinhoabandonado') -> encrypt($customer,
                                                                  array('campanhaid'=>$this->campanhaAtual->getCampanhaid())
                                                                  );
            
            //Configurando o email 
            if($this->campanhaAtual->getCampanhaid()){
                     Mage::log("carrinho abandonado - configurando email ");
                     Mage::log("carrinho abandonado - ".$customer->getEmail()." - ".urlencode($hash));
                     
                  $temail = Mage::getModel('carrinhoabandonado/email') 
                                    -> getCollection() 
                                    -> addFieldToFilter('campanhaid', array('eq' => $this->campanhaAtual->getCampanhaid())) 
                                    -> addFieldToFilter('email', array('eq' => $customer->getEmail()));
                  
                  //Verifica se o email para essa campanha já foi disparado
                  if ($temail -> getSize() >= 1) 
                  {
                        return;
                  }
                  else{   
                           
                        $email = Mage::getModel("carrinhoabandonado/email");
                        $email->setCampanhaid($this->campanhaAtual->getCampanhaid());
                        $email->setEmail($customer->getEmail());
                        $email->setCustomerId($customer->getId());
                        $email->save();
                        
                        //vai incrementando aos poucos o final
                        $this->campanhaAtual->setTotalEmailsEnviados( $this->campanhaAtual->getTotalEmailsEnviados()+1 );
                        $this->campanhaAtual->save();
                  }
                     Mage::log("carrinho abandonado - email configurado ");
            }
            else
            {
                return;
            }
            
            

                  
            
         // return;
            
            /**
             * Envia eMail
             */

            //Configuracao do sistema
            $templateEmail = Mage::getStoreConfig('carrinhoabandonado/config/ca_email_template');
            $emailRemetente = Mage::getStoreConfig('carrinhoabandonado/config/ca_email_identity');
            //No caso de template invalido usa o default do projeto
            if (!isset($templateEmail) || $templateEmail == null || $templateEmail == "" || $templateEmail == 'carrinhoabandonado_config_ca_email_template')
                  $templateEmail = 'carrinhoabandonado_email_default';
            
            $translate -> setTranslateInline(true);
            
            Mage::getModel('core/email_template')
                        //-> addBcc('tsantos@elnetcorp.com.br') 
                        -> setDesignConfig(array('area' => 'frontend', 'store' => 1)) 
                        -> sendTransactional(   $templateEmail, 
                                    $emailRemetente, 
                                    $customer -> getEmail(), 
                                    $customer -> getName(), 
                                    array('customer' => $customer, 
                                          'quote' => $quote, 
                                          'hash' => $hash,                                          
                                    )
                                );
            
            
            $translate -> setTranslateInline(true);
      }

}
