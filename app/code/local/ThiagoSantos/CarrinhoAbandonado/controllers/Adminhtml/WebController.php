<?php

class ThiagoSantos_CarrinhoAbandonado_Adminhtml_WebController extends Mage_Adminhtml_Controller_Action {
          
   

      public function _initAction() {
            $this -> loadLayout()
             ->_setActiveMenu('carrinhoabandonado/items')
             ->_addBreadcrumb(Mage::helper('carrinhoabandonado')->__('Carrinho Abandonado'),
                              Mage::helper('carrinhoabandonado')->__('Relatório')
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

    public function exportCampanhasCsvAction()
    {
        $fileName   = 'export-campanhas.csv';
        $content    = $this->getLayout()->createBlock('carrinhoabandonado/adminhtml_report_campanha_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportEmailsCsvAction()
    {
        $fileName   = 'export-emails.csv';
        $content    = $this->getLayout()->createBlock('carrinhoabandonado/adminhtml_report_email_grid')
            ->getCsv();

        $this->_prepareDownloadResponse($fileName, $content);
    }

}
