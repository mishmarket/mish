<?php
class Evince_Trackyourorder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();     
        $this->renderLayout();
    }
    public function validate(){

    }
    public function initOrder(){
        if ($data = $this->getRequest()->getPost()) {
            $orderId = $data["order_id"];
            $email = $data["email"];
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $cEmail = $order->getCustomerEmail();
            if($cEmail == trim($email)){
                Mage::register('current_order',$order);    
            } else {
                Mage::register('current_order',Mage::getModel("sales/order"));    
            }
            
        }
    }
    public function trackAction()
    {
       
        $post = $this->getRequest()->getPost();
        if ( $post ) {
            try {
                if (!Zend_Validate::is(trim($post['order_id']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['email']), 'NotEmpty')) {
                    $error = true;
                }       
                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }   
                if ($error) {
                    throw new Exception();
                }
                $this->initOrder($post);
                $order = Mage::registry('current_order');
                if($order->getId()){
                    
                    $this->getResponse()->setBody($this->getLayout()->getMessagesBlock()->getGroupedHtml().$this->_getGridHtml());
                    return;
                    
                } else {
                    Mage::getSingleton('core/session')->addError(Mage::helper('contacts')->__('Order is Unavailable.Please try again later'));
                    $this->getResponse()->setBody($this->getLayout()->getMessagesBlock()->getGroupedHtml());
                    return;
                }
                

            }catch (Exception $e) {
                Mage::getSingleton('core/session')->addError(Mage::helper('trackyourorder')->__('Please Enter Order Detail.'));
                $this->getResponse()->setBody($this->getLayout()->getMessagesBlock()->getGroupedHtml());
                    return;
                
            }
        } else {
            $this->_redirect('*/*/');
        }

    }
     protected function _getGridHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load("trackyourorder_index_track");
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        return $output;
    }
}