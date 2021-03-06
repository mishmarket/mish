<?php
class Mirasvit_Rma_Block_Adminhtml_Condition_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct ()
    {
        parent::__construct();
        $this->_objectId = 'condition_id';
        $this->_controller = 'adminhtml_condition';
        $this->_blockGroup = 'rma';


        $this->_updateButton('save', 'label', Mage::helper('rma')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('rma')->__('Delete'));


        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('rma')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action + 'back/edit/');
            }
        ";

        return $this;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getCondition()
    {
        if (Mage::registry('current_condition') && Mage::registry('current_condition')->getId()) {
            return Mage::registry('current_condition');
        }
    }

    public function getHeaderText ()
    {
        if ($condition = $this->getCondition()) {
            return Mage::helper('rma')->__("Edit Condition '%s'", $this->htmlEscape($condition->getName()));
        } else {
            return Mage::helper('rma')->__('Create New Condition');
        }
    }

    /************************/

}