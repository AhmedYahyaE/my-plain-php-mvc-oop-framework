<?php
// Autoloading all application classes - This class is 'require_once'-ed one time only in index.php (Entry script)
// Pay attention to the fact that the class name must be exactly the same as its file name (for example: a file named 'Test.php' must contain a class named 'Test'), and it's MANDATORY that namespaces must match the project folders structure/arrangement. This is very important for autoloading to work properly.
// Note: Autoloading mainly depends on classes namespaces (We 'require' classes based on their namespaces that MATCHES the project folder structure).
// Note: The spl_autoload_register() function is triggered AUTOMATICALLY by PHP whenever a class or interface is referenced but not yet defined or loaded, or whenever you try to instantiate a class, call a static method, access a constant, or use the 'instanceof' operator with an unknown class or interface
namespace app\core;

class Autoloading {
    public function __construct() {
        define('APP_PATH', dirname(__DIR__)); // The application root directory    // the 'app' folder path ('app' folder is the folder which contains the 'core' folder which contains the 'Autoloading.php' file)    // echo dirname(__FILE__) . '<br>';    is the same as echo __DIR__ . '<br>';    is the same as    echo realpath(__DIR__) . '<br>';
        /* echo APP_PATH . '<br>';
        exit; */

        spl_autoload_register(function($className) { // spl_autoload_register() function is automatically invoked when a new object is created, then it calls its callback function (in its arguments), and that callback function takes the class name of the object fully with its complete namespace, because we later 'require_once' the class DEPENDING ON its namespace
            $className = str_replace('app', '', $className); // remove the repeated first part of the class (file) namespace (i.e. 'app' part) (i.e. remove 'app' from for example 'app\controllers\SiteController.php'), because APP_PATH already has the 'app' part (for example, C:\Users\Monarch\app)
            // echo $className . '<br>';
            // exit;

            $className .= '.php'; // add the file extension '.php' to the file name    // $className .= '.php';    is the same as    $className = $className . '.php';
            // echo $className . '<br>';
            // exit;

            $fileToBeRequired = APP_PATH . $className;
            // echo $fileToBeRequired . '<br>';
            // exit;

            if (file_exists($fileToBeRequired)) {
                require_once $fileToBeRequired;
            } else {
                echo 'File not found <b>from Autoloading.php<b><br>';
            }
        });
        
    }

}

$autloadingObject = new Autoloading();