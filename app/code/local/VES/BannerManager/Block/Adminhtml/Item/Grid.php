<?php

class VES_BannerManager_Block_Adminhtml_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('itemGrid');
      $this->setDefaultSort('item_id');
      $this->setDefaultDir('ASC');
      $this->setUseAjax(true);
      $this->setSaveParametersInSession(true);
      $this->setTemplate('ves_bannermanager/grid.phtml');
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('bannermanager/item')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('bannermanager_id', array(
          'header'    => Mage::helper('bannermanager')->__('ID'),
          'align'     =>'center',
          'width'     => '50px',
          'index'     => 'item_id',
      ));
	  $this->addColumn('filename', array(
          'header'    => Mage::helper('bannermanager')->__('Image'),
          'align'     =>'left',
          'index'     => 'filename',
	  	  'width'	  => '100px',
      ));
      
      $this->addColumn('banner_id', array(
          'header'    => Mage::helper('bannermanager')->__('Banner'),
          'align'     => 'left',
          'index'     => 'banner_id',
      	  'width'	  => '200px',
          'type'      => 'options',
          'options'   => Mage::getModel('bannermanager/banner')->getOptionArray(),
      ));
      
      $this->addColumn('title', array(
          'header'    => Mage::helper('bannermanager')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('bannermanager')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('bannermanager')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('bannermanager')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('bannermanager')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('bannermanager')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('bannermanager')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('bannermanager_id');
        $this->getMassactionBlock()->setFormFieldName('bannermanager');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('bannermanager')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('bannermanager')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('bannermanager/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('bannermanager')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('bannermanager')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }
	public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}