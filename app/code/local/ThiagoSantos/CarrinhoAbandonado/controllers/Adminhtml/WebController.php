<?php

class ThiagoSantos_CarrinhoAbandonado_Adminhtml_WebController extends Mage_Adminhtml_Controller_Action {
          
   

      public function _initAction() {
            $this -> loadLayout()
             ->_setActiveMenu('carrinhoabandonado/items')
             ->_addBreadcrumb(Mage::helper('carrinhoabandonado')->__('Gerenciar Sonhos'), 
                              Mage::helper('carrinhoabandonado')->__('Gerenciar Sonhos')
                             );

            return $this;
      }

      public function indexAction() {
          //  $this -> _initAction()->_addContent("ooooooooooooou") -> renderLayout();
      }
      
      public function lodAction(){            
      
         $this->_initAction();
         $this->_addContent (
            $this->getLayout()->createBlock('carrinhoabandonado/adminhtml_report_campanha')
                             
            );
         $this->renderLayout();
      
            return $this;
      }

      
      public function viewAction(){            

         Mage::register('campanhaid', $this->getRequest()->getParam('campanhaid'));
         
         $this->_initAction();
         $this->_addContent ($this->getLayout()->createBlock('carrinhoabandonado/adminhtml_report_email')
                             );
         $this->renderLayout();
      
            return $this;
      }
      

}
