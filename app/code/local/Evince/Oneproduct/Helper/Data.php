<?php
class Evince_Oneproduct_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getIsEnabled(){
        return Mage::getStoreConfigFlag('evince/evince_group/enabled'); //replace section & group with appropriate values.
    }
}
	 