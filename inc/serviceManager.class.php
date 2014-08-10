<?php

require_once 'config.inc.php';
require_once 'service.class.php';

class ServiceManager {
    
    private static $config;
    
    public static function init() {

        if(is_null(self::$config)) {
            
            self::$config = new Config(parse_ini_file('sys-config.ini', true));
            
        }
        
    }
    
    public static function getConfig() {
        self::init();
        return self::$config;
    }
    
    public static function render() {
        
        foreach (self::$config->get('service') as $service => $data) {

            $service = new Service($service, $data, self::$config->get('verificationCode'));
            $service->fetch();
            echo $service->render();

        }
        
    }
    
}

?>