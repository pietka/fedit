<?php

setlocale(LC_ALL, 'pl_PL');
date_default_timezone_set('Europe/Warsaw');
error_reporting(E_ALL | E_STRICT);

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// define path to root
defined('APPLICATION_ROOT')
    || define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/../'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
    
// Define application public
defined('APPLICATION_PUBLIC')
    || define('APPLICATION_PUBLIC', APPLICATION_ROOT . '/public/');

if (defined('INCLUDE_PATH_PREFIX'))
    $prefix = INCLUDE_PATH_PREFIX;
else
    $prefix = dirname(__FILE__) . '/../';
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    $prefix.'library',
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
Zend_Registry::set('Zend_Application', $application);

$instance = Zend_Loader_Autoloader::getInstance();
$instance->setFallbackAutoloader(true);

$application->bootstrap();

$front = Zend_Controller_Front::getInstance();

// wyłaczone aby mozna bylo skorzystac z handlera bluepapricowego, rozszwrzajacego podstawowy
//$front->setParam('noErrorHandler',true);
$front->addModuleDirectory(APPLICATION_PATH . '/modules');
//$front->setModuleControllerDirectoryName('controllers');
$options = $application->getOptions();

Zend_Locale::setDefault('pl_PL');

if(!defined('APPLICATION_NO_RUN')) {
    // Define base url to application
    defined('APPLICATION_SERVER')
        || define('APPLICATION_SERVER', 'http://' . $_SERVER['HTTP_HOST']);

    $directory = dirname($_SERVER['PHP_SELF']);
    if($directory == '/')
        $directory = '';
    else $directory = ''; // for vhosts on windows
    
    defined('APPLICATION_URL')
        || define('APPLICATION_URL', APPLICATION_SERVER . $directory . '/');
    Zend_Registry::set('base', APPLICATION_URL);

    $application->run();

}

