<?php // app/core/autoloader.php
class autoloader
{
    protected $directories = array();

    private function loadClass($class)
    {
        if($class[0] == '\\')
        {
            $class = substr($class, 1);
        }
        $class = str_replace(array('\\','_'), '/', $class).'.php';

        foreach($this->directories as $directory)
        {
            if(file_exists($path = $directory.'/'.$class))
            {
                require_once $path; 
                return true;                
            }
        }
    }

    public function register()
    {
        spl_autoload_extensions('php');
        spl_autoload_register(array($this,'loadClass'));

    }
    public function addDirectories($directories)
    {
        $this->directories = (array) $directories;

    }
}