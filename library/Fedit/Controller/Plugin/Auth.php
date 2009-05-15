<?php

class Fedit_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract{
	private $_auth;
	private $_acl;
	
	private $_noauth = array ('controller' => 'index', 'action' => 'login');
	private $_noacl = array ('controller' => 'index', 'action' => 'noaccess');
	
	public function __construct ()
    {
		$this->_auth = Fedit_Auth::getInstance();
		$this->_acl = new Fedit_Acl();
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
		if ($this->_auth->hasIdentity()) {
			$role = $this->_auth->role;
		}
		else {
			$role = 'guest';
		}
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$module = $request->getModuleName();

		if (!$this->_acl->has($module)) {
			$module = null;
		}
		if (!$this->acl->isAllowed($role, $module)) {
			if(!$this->auth->hasIdentity()) {
				$controller = $this->_noauth['controller'];
				$action = $this->_noauth['action'];
			}
			else {
				$controller = $this->_noacl['controller'];
				$action = $this->_noacl['action'];
			}
		}
        $request->setModuleName($module);
		$request->setControllerName($controller);
		$request->setActionName($action);
	}
}