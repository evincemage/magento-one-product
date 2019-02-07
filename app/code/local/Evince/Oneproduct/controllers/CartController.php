<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class Evince_Oneproduct_CartController extends Mage_Checkout_CartController
{
    /**
     * Minicart ajax update qty action
     */
    public function ajaxUpdateAction()
    {   
        if (!Mage::helper('oneproduct')->getIsEnabled()){ //if my module is not enabled
            return parent::ajaxUpdateAction();
        }
        
        if (!$this->_validateFormKey()) {
            Mage::throwException('Invalid form key');
        }
        $id = (int)$this->getRequest()->getParam('id');
        $qty = $this->getRequest()->getParam('qty');
        
        $result = array();
        if ($id) {
            try {
                if ($qty>1) {
                    $message = $this->__('Maximum allowed quantity is %s.', 1);
                    Mage::throwException($this->__('Maximum allowed quantity for this product is %s.', 1));
                }
                $cart = $this->_getCart();
                if (isset($qty)) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $qty = $filter->filter($qty);
                }

                $quoteItem = $cart->getQuote()->getItemById($id);
                if (!$quoteItem) {
                    Mage::throwException($this->__('Quote item is not found.'));
                }
                if ($qty == 0) {
                    $cart->removeItem($id);
                } else {
                    $quoteItem->setQty($qty)->save();
                }
                $this->_getCart()->save();

                $this->loadLayout();
                $result['content'] = $this->getLayout()->getBlock('minicart_content')->toHtml();

                $result['qty'] = $this->_getCart()->getSummaryQty();

                if (!$quoteItem->getHasError()) {
                    $result['message'] = $this->__('Item was updated successfully.');
                } else {
                    $result['notice'] = $quoteItem->getMessage();
                }
                $result['success'] = 1;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = isset($message)?$message:$this->__('Can not save item.');
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
