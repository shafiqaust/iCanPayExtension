<?php

class Mageapps_Icanpay3dsv_Helper_Data extends Mage_Core_Helper_Abstract
{
	const KEY = 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282';

	public function getGateWayCredentials()
	{
		return array(
				'sec_key' => Mage::getStoreConfig('payment/mageapps_icanpay/sec_key'),
				'authenticate_id' => Mage::getStoreConfig('payment/mageapps_icanpay/authenticate_id'),
				'authenticate_pw' =>Mage::getStoreConfig('payment/mageapps_icanpay/authenticate_pw')
			);
	}



	

	public function mc_encrypt($encrypt)
	{
		$key = self::KEY;
	    $encrypt = serialize($encrypt);
	    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
	    $key = pack('H*', $key);
	    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
	    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
	    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    	return $encoded;
	}

	public function mc_decrypt($decrypt)
	{
		$key = self::KEY;
	    $decrypt = explode('|', $decrypt.'|');
	    $decoded = base64_decode($decrypt[0]);
	    $iv = base64_decode($decrypt[1]);

	    if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC))
	    { 
	    	return false; 
	    }
	    $key = pack('H*', $key);
	    $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
	    $mac = substr($decrypted, -64);
	    $decrypted = substr($decrypted, 0, -64);
	    $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
	    if($calcmac!==$mac)
	    { 
	    	return false; 
	    }
	    $decrypted = unserialize($decrypted);
	    return $decrypted;
	}
}