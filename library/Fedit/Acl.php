<?php
class Fedit_Acl extends Zend_Acl{
	
    public function __construct(){
    	
		$this->add(new Zend_Acl_Resource('admin'));
		
        $this->addRole(new Zend_Acl_Role('guest')); 
        $this->addRole(new Zend_Acl_Role('admin'), 'gues');

        // lababoranci moga tylko rejestrowac pacjentow
        $this->allow('guest', 'default');
        
        $this->allow('admin');
    }
}
