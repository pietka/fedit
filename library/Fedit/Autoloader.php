<?php
/**
 * ----------------------------------------------------------------
 * Copyright (C) 2009 Agencja Interaktywna Blue Paprica
 * 		 Maciej Pałubicki. All rights reserved.
 * ----------------------------------------------------------------
 *
 * /library/BluePaprica/Autoloader.php
 *
 * @author      Tomasz Rżany (koszi) <tr@bluepaprica.com>
 * @desc        Autoloader do  modelow i formulrzy modulow
 * 
 * @copyright   Copyright (C) 2008 Agencja Interaktywna Blue Paprica
 *              Maciej Pałubicki. All rights reserved.
 */
require_once 'Zend/Loader/Autoloader/Interface.php';

class BluePaprica_Autoloader implements Zend_Loader_Autoloader_Interface
{

    /**
     * Mapa odwzorowań
     * @var <type>
     */
    private $_map = array(
        'Form'      => '/forms/',
        'Widget'    => '/widgets/',
        'Service'   => '/services/',
        'Controller'=> array(
                'Admin' => '/controllers/Admin/',
                'Front' => '/controllers/Front/'
        ),
    );

    /**
     * Wyjatki dla klas
     * @var class=>prefix
     */
    private $_exceptions = array(
        'Auth_AdminController'      => 'Auth/controllers/AdminController.php',
        'Core_Auth_AdminController' => 'Auth/controllers/AdminController.php',
        'Auth_FrontController'      => 'Auth/controllers/FrontController.php',
    );

    /**
     * Flaga okreslająca modul klasy Core/Application
     * @var <type>
     */
    private $_is_core = false;

    public function autoload($class)
    {
//        $l = BluePaprica_Importer_Logger::getInstance();
//        $l->setObservable($this);
//        $l->writeLog(array($class));
        
        //@todo: sprawdzanie odbywa chyba sie juz wczesniej,w zend loaderze
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }

        $facade = BluePaprica_Module_Facade::getInstance();
        $map = $facade->getClassesMap();

        if(array_key_exists($class,$map)) {
             require $map[$class];
        }
        else {
            /* Rozbijamy nazwe po "_" */
            $parts = explode('_',$class);

            /* Core lub nazwa konretengo modulu */
            $appModule = $parts[0];

            if($appModule == 'Core') array_shift($parts);

            // modul ACL|AUTH|CMS|NEWS etc
            $module = $parts[0];

            if(array_key_exists($class,$this->_exceptions)) {
                // sciezka dla wyjatkow
                $file = $this->_exceptions[$class];
            } else {
                $prefix = $this->_getPrefixPathForClass($parts);
                $file   = $module . $prefix;
                $file  .= implode('/',$parts) .'.php';
            }
            
            $file = $appModule == 'Core' ? APPLICATION_ROOT . '/library/Core/' . $file :
                 BluePaprica_Module_Facade::$modulePath.'/'.$file;

            if(!file_exists($file)){
                require_once 'Zend/Exception.php';
                throw new Zend_Exception("File \"$file\" does not exist!!!");
            }

            require $file;
            if(!class_exists($class, false) && !interface_exists($class, false)){
                require_once 'Zend/Exception.php';
                throw new Zend_Exception("Class \"$class\" was not found in the file: \"$file\"!!!");
            } else {
                // zapis sciezki klasy do mapy klas
                $facade->addClassToMap($class,$file);
            }
        }
    }

    /**
     * Generuje prefix sciezki dla szukanej klasy
     * @param <type> $parts
     */
    private function _getPrefixPathForClass(&$parts) {
        if(array_key_exists($parts[1], $this->_map) && $parts[1] != 'Controller') {
           $prefix = $this->_map[$parts[1]];
           unset($parts[1]);
        }
        else if(isset($parts[2]) && substr($parts[2], -10, 10) == 'Controller' ) {
           // w przypadku kontrolerow sciezka jest budowanana nieco inaczej
           //@desc parts[1] = Admin | Front, tak wiec wybieramy prefix z mapy
           $prefix = $this->_map['Controller'][$parts[1]];
           unset($parts[1]);
        } else {
            // defaultwo prefix wskazuje na modele, gdyz te nie maja zadnego
            // specyficznego przedrostka w nazwie
            $prefix = '/models/';
        }
        unset($parts[0]);
        return $prefix;
    }
}
?>
