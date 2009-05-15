<?php

class Fedit_Auth_Adapter extends Zend_Auth_Adapter_Interface
{

    private $_login;
    private $_passwd;

    public function authenticate()
    {
        $code = Zend_Auth_Result::SUCCESS;
        $results = new Zend_Auth_Result($code, $identity);
        return $results;
    }

    public function setLogin($login)
    {
        $this->_login = $login;
    }

    public function setPasswd($passwd)
    {
        $this->_passwd = $passwd;
    }
}
