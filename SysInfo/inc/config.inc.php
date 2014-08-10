<?php

class Config {
    
    private $config;
    private $DELIMITER = '.';
    
    public function __construct($config, $delimiter = '.') {
        
        if(is_null($config)) {
            throw new ConfigException(ConfigExceptionType::CONFIG_IS_NULL);
        }
        
        if(!(is_array($config))) {
            throw new ConfigException(ConfigExceptionType::CONFIG_ISNT_ARRAY, null, gettype($config));
        }
        
        $this->config       = $config;
        $this->DELIMITER    = $delimiter;
    }

    public function get($path, $config = null) {

        if (is_null($config)) {
            $config = $this->config;
        }

        if (is_null($path)) {
            throw new ConfigException(ConfigExceptionType::PATH_IS_NULL, $path);
        }

        if (!(self::containsPath($path, $config))) {
            throw new ConfigException(ConfigExceptionType::UNKNOWN_PATH, $path, $path);
        }
        
        
        $cur = $config;
        if(strpos($path, $this->DELIMITER)) {
            $exp = explode($this->DELIMITER, $path);
            
            foreach ($exp as $key) {
                $cur = $cur[$key];
            }
            
            return $cur;
        }

        return $cur[$path];
    }

    public function containsPath($path, $config) {

        if (is_null($config)) {
            $config = $this->config;
        }
        
        $cur = $config;
        if(strpos($path, $this->DELIMITER)) {
            $exp = explode($this->DELIMITER, $path);
            
            foreach ($exp as $key) {
                $cur = $cur[$key];
            }
            
            return (!(is_null($cur)));
        }

        return (!(is_null($cur[$path])));
    }

}

class ConfigExceptionType {
    
    const UNKNOWN_PATH      = 'The given path is unknown (Path: %s).';
    const PATH_IS_NULL      = 'The given path is null.';
    const CONFIG_IS_NULL    = 'The given config is not valid or null.';
    const CONFIG_ISNT_ARRAY = 'The given config must be an instance of array (%s is wrong).';
    
}


class ConfigException extends Exception {
    
    private $path;
    
    public function __construct($message, $path = null, $argument = null, $code = 0, Exception $previous = null) {
        parent::__construct(sprintf('ConfigException: ' . $message, $argument), $code, $previous);

        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

}

?>