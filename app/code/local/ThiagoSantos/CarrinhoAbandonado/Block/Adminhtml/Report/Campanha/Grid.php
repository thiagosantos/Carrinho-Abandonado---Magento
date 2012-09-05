<?php
 
class ThiagoSantos_CarrinhoAbandonado_Block_Adminhtml_Report_Campanha_Grid 
extends Mage_Adminhtml_Block_Report_Grid
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('gridSimple');
    }
 
    protected function _prepareCollection()
    {
        parent::_prepareCollection();
        $this->getCollection()->initReport('carrinhoabandonado/report_campanha_collection');
        //$this->getCollection()->initReport('awesome/report_simple_collection');
 
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('nome', array(
            'header'    =>Mage::helper('reports')->__('Nome da campanha'),
            'index'     =>'nome',
            'sortable'  => false
        ));
        
        $this->addColumn('total_emails_enviados', array(
            'header'    =>Mage::helper('reports')->__('Total de emails enviados'),
            'index'     =>'total_emails_enviados',
            'sortable'  => false
        ));
        
        $this->addColumn('total_emails_abertos', array(
            'header'    =>Mage::helper('reports')->__('Total de emails abertos'),
            'index'     =>'total_emails_abertos',
            'sortable'  => false
        ));
        
         $this->addColumn('total_emails_clicados', array(
            'header'    =>Mage::helper('reports')->__('Total de emails clicados'),
            'index'     =>'total_emails_clicados',
            'sortable'  => false
        ));
        
        $this->addColumn('total_emails_revertidos', array(
            'header'    =>Mage::helper('reports')->__('Total de emails revertidos'),
            'index'     =>'total_emails_revertidos',
            'sortable'  => false
        ));
        
      $this->addColumn('action',
                array(
                    'header'    => Mage::helper('sales')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
                    'actions'   => array(
                        array(
                            'caption' => Mage::helper('sales')->__('View'),
                            'url'     => array('base'=>'*/*/view'),
                            'field'   => 'campanhaid'
                        )
                    ),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));
            
            
 /*
        $currencyCode = $this->getCurrentCurrencyCode();
 
        $this->addColumn('value', array(
            'header'    =>Mage::helper('reports')->__('Value'),
            'index'     =>'value',
            'currency_code' => $currencyCode,
            'total'     =>'sum',
            'type'      =>'currency'
            ));
      */
            
            
            $this->addExportType('*/*/exportSimpleCsv', Mage::helper('reports')->__('CSV'));
 
            return parent::_prepareColumns();
    }
 
}