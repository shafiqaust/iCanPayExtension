<?php
class Mageapps_Icanpay3dsv_Block_Icanpay extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getIcanpay()     
     { 
        if (!$this->hasData('mageapps_icanpay3dsv')) {
            $this->setData('mageapps_icanpay3dsv', Mage::registry('mageapps_icanpay3dsv'));
        }
        return $this->getData('mageapps_icanpay3dsv');
        
    }
}