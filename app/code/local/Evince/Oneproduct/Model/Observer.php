<?php

class Evince_Oneproduct_Model_Observer
{
    public function cartUpdate($observer)
    {   
        if (!Mage::helper('oneproduct')->getIsEnabled()){ //if my module is not enabled
                return true;
            }
        $data = $observer->info;
        foreach ($data as $itemId => $itemInfo) {
            if($itemInfo['qty']>1)
            {
                $message = Mage::helper('checkout')->__('Maximum allowed quantity for this product is %s.', 1);
                Mage::throwException($message);
            }
        }
    }
}