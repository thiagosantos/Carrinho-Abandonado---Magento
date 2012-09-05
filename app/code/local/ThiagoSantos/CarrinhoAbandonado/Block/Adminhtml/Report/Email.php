<?php
 
class ThiagoSantos_CarrinhoAbandonado_Block_Adminhtml_Report_Email 
//extends Mage_Adminhtml_Block_Widget_Grid_Container

extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    private $_campanhaName  = "";
    protected $_campanhaId    = "";  

    public function __construct()
    {
        $this->_blockGroup = 'carrinhoabandonado';
        $this->_controller = 'adminhtml_report_email';
        $this->_headerText = Mage::helper('sales')->__('Carrinho Abandonado');
        //$this->_addButtonLabel = Mage::helper('sales')->__('Create New Order');
        parent::__construct();
       
            $this->_removeButton('add');
       
    }
    
      /* public function setCampanhaid($campanhaid){
         $this->_campanhaId = $campanhaid;
         
         return $this;
   }*/
 
    

}