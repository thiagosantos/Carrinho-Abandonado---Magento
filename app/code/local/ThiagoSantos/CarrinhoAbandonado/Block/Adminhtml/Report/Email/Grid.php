<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * #Agradecimentos a Thiago Silveira pela orientação dos campos não padrões do Magento
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_CarrinhoAbandonado
 */
class ThiagoSantos_CarrinhoAbandonado_Block_Adminhtml_Report_Email_Grid 
extends Mage_Adminhtml_Block_Widget_Grid
{
 
     
 
    public function __construct()
    {
            parent::__construct();
       
            $this->setDefaultSort('aberto');
            $this->setDefaultDir('DESC');
            $this->setId('gridSimple');                  
    }


    protected function _prepareCollection()
    {
        /*
         * @todo Mover esse conteúdo para dentro de Model/Mysql4/Email/Collection.php
         */
        if(!is_null(Mage::registry('campanhaid'))){
              $firstname = Mage::getResourceSingleton('customer/customer')->getAttribute('firstname');     
              $lastname  = Mage::getResourceSingleton('customer/customer')->getAttribute('lastname');              
              $telephone = Mage::getResourceSingleton('customer/address')->getAttribute('telephone');
            
              $collection = Mage::getModel('carrinhoabandonado/report_email')
                              ->getCollection()
                              ->addFieldToFilter('campanhaid', array('eq'=>Mage::registry('campanhaid')) );
                              
              $collection->getSelect()
              ->joinLeft(
                        array('address_entity_table' => 'customer_address_entity'),
                        'address_entity_table.parent_id = main_table.customer_id',
                        array('is_active'=>'is_active')
                     )                      
              ->joinLeft(
                        array('address_telephone_table' => $telephone->getBackend()->getTable()),
                        'address_telephone_table.entity_id = address_entity_table.entity_id
                         AND address_telephone_table.attribute_id = '.(int) $telephone->getAttributeId() . '
                         ',
                        array('telephone'=>'value')
                     )
                ->joinLeft(
                        array('customer_lastname_table' => $lastname->getBackend()->getTable()),
                        'customer_lastname_table.entity_id = main_table.customer_id
                         AND customer_lastname_table.attribute_id = '.(int) $lastname->getAttributeId() . '
                         ',
                        array('seller_lastname'=>'value')
                     )
                     ->joinLeft(
                        array('customer_firstname_table' =>$firstname->getBackend()->getTable()),
                        'customer_firstname_table.entity_id = main_table.customer_id
                         AND customer_firstname_table.attribute_id = '.(int) $firstname->getAttributeId() . '
                         ',
                        array('seller_firstname'=>'value')
                     )
             ->columns(new Zend_Db_Expr("CONCAT(`customer_firstname_table`.`value`, ' ',`customer_lastname_table`.`value`) AS fullname"))
             ->group('emailid');                     
                     

                $this->setCollection($collection);

        }
        parent::_prepareCollection();

    }

    /**
     * Formato diferente da disposição das campanhas
     * Para a exibição do relatório por dia mostra-se todos os campos
     * @todo formatar as colunas variando de acordo com o filtro de exibição (day,month,year)
     */
    protected function _prepareColumnsToDay(){

    }

    protected function _prepareColumns()
    {
        $this->addColumn('customer_id', array(
            'header'    =>Mage::helper('reports')->__('Customer Id'),
            'index'     =>'customer_id',
            'sortable'  => false
        ));
        
        
        $this->addColumn('email', array(
            'header'    =>Mage::helper('reports')->__('Email'),
            'index'     =>'email',
            'sortable'  => false
        ));
        
        
            $this->addColumn('fullname', array(
            'header'    =>Mage::helper('reports')->__('Nome'),
            'index'     =>'fullname',
            'sortable'  => false
        ));

        
        $this->addColumn('fulltel', array(
            'header'    =>Mage::helper('reports')->__('Telefone'),
            'index'     =>'fulltel',
            'sortable'  => false
        ));
        
        
        
        $this->addColumn('aberto', array(
            'header'    =>Mage::helper('reports')->__('Mala aberta'),
            'index'     =>'aberto',
             'type'      => 'options',
             'options'   => array(
              0 => '',
              1 => 'SIM',
          ),
            'sortable'  => true,
            
        ));
        
        
        $this->addColumn('clicado', array(
            'header'    =>Mage::helper('reports')->__('Mala clicada'),
            'index'     =>'clicado',
            'sortable'  => true,
             'type'      => 'options',
             'options'   => array(
              0 => '',
              1 => 'SIM',
          ),
        ));
        
        
        $this->addColumn('revertido', array(
            'header'    =>Mage::helper('reports')->__('Revertido'),
            'index'     =>'revertido',
            'sortable'  => false
        ));
        
        $this->addColumn('order_id', array(
            'header'    =>Mage::helper('reports')->__('Pedido'),
            'index'     =>'order_id',
            'sortable'  => false
        ));
        
        $this->addColumn('revertido_em', array(
            'header'    =>Mage::helper('reports')->__('Revertido em'),
            'index'     =>'revertido_em',
            'sortable'  => true,
           // 'type'=>'date'
        ));
        
        $this->addColumn('created_at', array(
            'header'    =>Mage::helper('reports')->__('Mala enviada'),
            'index'     =>'created_at',
            'sortable'  => false,
            'type'=>'date'
        ));
        
            $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('customer')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                //'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('customer')->__('Mais info'),
                        'url'       => array('base'=> '*/customer/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'customer_id',
                'is_system' => true,
        ));
        
        
        
      
//     $this->addColumn('action',
  //              array(
   //                 'header'    => Mage::helper('sales')->__('Action'),
    //                'width'     => '50px',
     //               'type'      => 'action',
    //                'getter'     => 'getId',
    //                'actions'   => array(
    //                    array(
    //                        'caption' => Mage::helper('sales')->__('View'),
    //                        'url'     => array('base'=>'*/*/view'),
    //                        'field'   => 'campanhaid'
    //                    )
    //                ),
     //               'filter'    => false,
     //               'sortable'  => false,
     //               'index'     => 'stores',
    //               'is_system' => true,
    //        ));
            
            
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
            
            
            $this->addExportType('*/*/exportEmailsCsv', Mage::helper('reports')->__('CSV'));
 
            return parent::_prepareColumns();
    }


 
}
