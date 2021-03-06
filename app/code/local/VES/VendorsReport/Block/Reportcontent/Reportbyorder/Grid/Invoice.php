<?php

class VES_VendorsReport_Block_Reportcontent_Reportbyorder_Grid_Invoice extends Mage_Adminhtml_Block_Widget_Grid {

    protected $_requestData = null;
    protected $_filter = null;

    public function __construct() {
        parent::__construct();
        $this->setId('reportorderinvoiceGrid');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        //set request data
        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('top_filter'));
        $requestData['type_id'] = $this->getRequest()->getParam('type_id');
        $this->_requestData = $requestData;
    }
    
    protected function _prepareCollection() {
        $data = Mage::helper('inventoryreports/order')->getOrderReportCollection($this->_requestData);
        if(is_array($data)){
            $collection = $data['collection'];
            $this->_filter = $data['filter'];
        } else {
            $collection = $data;
        }
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns() {
        $this->addColumn('order_id', array(
            'header' => Mage::helper('inventoryreports')->__("Order's No."),
            'align' => 'right',
            'index' => 'order_id',
            'type' => 'number',
            'width' => '50px',
        ));
        
        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
        
        $this->addColumn('count_invoice_id', array(
            'header' => Mage::helper('inventoryreports')->__('Number of Invoices'),
            'align' => 'right',
            'index' => 'count_invoice_id',
            'type' => 'number',
            'width' => '50px',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));
        
        $this->addColumn('sum_invoice_item_qty', array(
            'header' => Mage::helper('inventoryreports')->__('Total Item(s) Invoiced'),
            'align' => 'right',
            'index' => 'sum_invoice_item_qty',
            'type' => 'number',
            'width' => '50px',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));
        
        $this->addColumn('sum_base_subtotal_invoiced', array(
            'header' => Mage::helper('inventoryreports')->__('Subtotal (Base) Invoiced'),
            'align' => 'right',
            'index' => 'sum_base_subtotal_invoiced',
            'type' => 'currency',
            'currency' => 'base_currency_code',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('sum_subtotal_invoiced', array(
            'header' => Mage::helper('inventoryreports')->__('Subtotal (Purchased) Invoiced'),
            'align' => 'right',
            'index' => 'sum_subtotal_invoiced',
            'type' => 'currency',
            'currency' => 'order_currency_code',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('sum_base_tax_amount_invoiced', array(
            'header' => Mage::helper('inventoryreports')->__('Tax (Base) Invoiced'),
            'align' => 'right',
            'index' => 'sum_base_tax_amount_invoiced',
            'type' => 'currency',
            'currency' => 'base_currency_code',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('sum_tax_amount_invoiced', array(
            'header' => Mage::helper('sales')->__('Tax (Purchased) Invoiced'),
            'align' => 'right',
            'index' => 'sum_tax_amount_invoiced',
            'type' => 'currency',
            'currency' => 'order_currency_code',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('sum_base_grand_total_invoiced', array(
            'header' => Mage::helper('inventoryreports')->__('G.T. (Base) Invoiced'),
            'align' => 'right',
            'index' => 'sum_base_grand_total_invoiced',
            'type' => 'currency',
            'currency' => 'base_currency_code',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

        $this->addColumn('sum_grand_total_invoiced', array(
            'header' => Mage::helper('inventoryreports')->__('G.T. (Purchased) Invoiced'),
            'align' => 'right',
            'index' => 'sum_grand_total_invoiced',
            'type' => 'currency',
            'currency' => 'order_currency_code',
            'filter_condition_callback' => array($this, '_filterCallback'),
        ));

//        $this->addExportType('*/*/exportInvoicedCsv', Mage::helper('adminhtml')->__('CSV'));
//        $this->addExportType('*/*/exportInvoicedExcel', Mage::helper('adminhtml')->__('Excel XML'));

        return parent::_prepareColumns();
    }
    
    protected function _filterCallback($collection, $column){
        $filter = $column->getFilter()->getValue();
        $filterData = $this->getFilterData();
        $arr = array();
        foreach ($collection as $item) {
            $fieldValue = $item->getData($column->getId());
            $pass = TRUE;
            if (isset($filter['from']) && $filter['from'] >= 0) {
                if (floatval($fieldValue) < floatval($filter['from'])) {
                    $pass = FALSE;
                }
            }
            if ($pass) {
                if (isset($filter['to']) && $filter['to'] >= 0) {
                    if (floatval($fieldValue) > floatval($filter['to'])) {
                        $pass = FALSE;
                    }
                }
            }
            if ($pass) {
                $item->setData($column->getId(),$fieldValue);
                $arr[] = $item;
            }
        }
        $temp = Mage::helper('inventoryreports')->_tempCollection(); // A blank collection
        $count = count($arr);
        for ($i = 0; $i < $count; $i++) {
            $temp->addItem($arr[$i]);
        }
        $this->setCollection($temp);
    }

    public function getGridUrl() {
        return $this->getUrl('vendors/report_report/reportinvoicegrid', array('type_id' => 'sales', 'top_filter' => $this->getRequest()->getParam('top_filter')));
    }

}
