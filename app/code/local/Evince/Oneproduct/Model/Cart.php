<?php

class Evince_Oneproduct_Model_Cart extends Mage_Checkout_Model_Cart
{
    public function addProduct($productInfo, $requestInfo=null){
        if (!Mage::helper('oneproduct')->getIsEnabled()){ //if my module is not enabled
            return parent::addProduct($productInfo, $requestInfo);
        } 
        
        $quote = Mage::getSingleton('checkout/session')->getQuote();
    	$cartItems = $quote->getAllVisibleItems();
        
        foreach ($cartItems as $item)
        {
            $cartproductIds[] = $item->getProductId();                     
        }

        $currentprodId = $productInfo->getEntityId();

        if(count($cartproductIds)>0) {        	
            $message = Mage::helper('checkout')->__('Can not add this product with other products at same time.');
            Mage::throwException($message);
        }  
        
        if($requestInfo['qty'] > 1) {
                $message = Mage::helper('checkout')->__('Maximum allowed quantity for this product is %s.', 1);
        	Mage::throwException($message);
        } 	
        
        parent::addProduct($productInfo, $requestInfo);
    }
}
