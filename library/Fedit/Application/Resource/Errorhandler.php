<?php

class Fedit_Application_Resource_Errorhandler extends Zend_Application_Resource_ResourceAbstract
{
    /**
     *
     * @return Zend_Controller_Plugin_ErrorHandler      error handler plugin
     */
    public function init() {
        $opt = $this->getOptions();
        $controller = $this->getBootstrap()->getResource('frontController');

        if (isset($opt['enable']) && (bool) $opt['enable']) {
            $errorHandler = new Zend_Controller_Plugin_ErrorHandler;
            $errorHandler
                ->setErrorHandlerModule($opt['module'])
                ->setErrorHandlerController($opt['controller'])
                ->setErrorHandlerAction($opt['action']);
            $controller->registerPlugin($errorHandler);
            $controller->throwExceptions(false);
        }
        else {
            $controller->throwExceptions(true);
        }

        return isset($errorHandler) ? $errorHandler : false;
    }
}