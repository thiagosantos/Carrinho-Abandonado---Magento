<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 */
class ThiagoSantos_CarrinhoAbandonado_Block_Adminhtml_Report_Campanha 
//extends Mage_Adminhtml_Block_Widget_Grid_Container

extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
         $this->_blockGroup = 'carrinhoabandonado';
        $this->_controller = 'adminhtml_report_campanha';
        $this->_headerText = Mage::helper('sales')->__('Carrinho Abandonado');
        //$this->_addButtonLabel = Mage::helper('sales')->__('Create New Order');
        parent::__construct();
        /*if (!Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/create')) {
            $this->_removeButton('add');
        }*/
    }
    
    
/*{
    public function __construct()
    {
        //$this->_blockGroup = 'carrinhoabandonado';
        $this->_controller = 'adminhtml_web';
       $this->_headerText = Mage::helper('carrinhoabandonado')->__('Simple Report');
        parent::__construct();
       
       // $this->_removeButton('add');
    }*/
}