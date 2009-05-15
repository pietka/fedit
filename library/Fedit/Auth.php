<?php

class Fedit_Auth extends Zend_Auth{
	
	public $login = null;
	public $pass = null;
	public $role = null;
	
	protected static $_instance = null;
	
	protected function __construct()
    {
    }
	 	
	public static function getInstance()
    {
		
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
	public function setParam(Zend_Auth_Adapter_Interface $adapter)
    {

		$results = $adapter->getResultRowObject();
		$this->login = $results->login;
		$this->pass = $results->pass;
		$this->role = $results->role;
	}
}