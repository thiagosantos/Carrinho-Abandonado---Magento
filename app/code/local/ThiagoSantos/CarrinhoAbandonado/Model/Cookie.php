<?php

/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 */

/**
 * @todo - Adicionar opção de configuração do limite de tempo do cookie
 **/
 
 class ThiagoSantos_CarrinhoAbandonado_Model_Cookie{
       
      
       /**
        * Cookies que aceitos e utilizados pelo Carrinho Abandonado
        */
       private $__cookieKey = array('utm_source',
                                    'utm_medium',
                                    'utm_campaign'
                                    );
       
       function __construct(){}
       
       
       /**
        * Define os cookies para o carrinho abandonado
        */
       public function set($_array){
              if(!is_array($_array))
                  return false;
            
            foreach($_array as $k=>$v){
                  
                  Mage::getModel('core/cookie')->set($k, $v,86400*45);
            }
            
            return true;
       }
       
       /**
        * Retorna todos os cookies utilizados pelo CarrinhoAbandonado
        */
       public function get(){
             $_array = array();
             foreach($this->__cookieKey as $k=>$cookiename){
                   $_array[$cookiename] = Mage::getModel('core/cookie')->get($cookiename);
             }
             
             return $_array;
       }
       
 }
