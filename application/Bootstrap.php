<?php

// application/Bootstrap.php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initController()
    {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        return $frontController;
    }

    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        
        $view->headTitle()->setSeparator(' - ')->append('Fedit');
        $view->headMeta()->appendHttpEquiv('Content-Type',
                                           'text/html; charset=utf-8');

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
}