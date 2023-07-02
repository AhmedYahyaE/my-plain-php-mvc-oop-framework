<?php
// A custom class to read the .env file (along the lines of the famous vlucas /phpdotenv package)
namespace app\core;


class CustomDotEnvFileReading {
    protected string $dotEnvFilePath; // path to '.env' file

    public function __construct(string $dotEnvFilePath) { // object of this class is created in index.php (Entry Script)
        if (file_exists($dotEnvFilePath)) {
            $this->dotEnvFilePath = $dotEnvFilePath; // $dotEnvFilePath value comes from index.php
        } else { // if the '.env' file doesn't exist, show an error
            echo '<b>.env file</b> is NOT FOUND from <b>' . self::class . '.php</b> class<br>'; // print the class name dynamically
        }
    }


    public function readDotEnvFile():void { // load '.env' file

        if (!is_readable($this->dotEnvFilePath)) { // if '.env' file doesn't exist or is not readable
            echo '<b>.env file</b> does not exist or not readable from <b>' . self::class . '.php</b> class<br>';
        }

        // $dotEnvFileLines = file_get_contents($this->dotEnvFilePath); // Return Value is a string
        $dotEnvFileLines = file($this->dotEnvFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Reads the entire '.env' file into an array (every line of the .env file is an array element), and either ignore newlines or skip empty lines    // Return Value is an array        
    
        
        foreach ($dotEnvFileLines as $dotEnvFileLine) { // for every line of the '.env' file
            list($name, $value) = explode('=', $dotEnvFileLine, 2); // converting a line's name/value pair (i.e. name = value e.g. age = 15) to an array of name and value, with a maximum limit of 2 array elements (which are the name and value, and this means the name will be taken at the first '=', and any later '=' will be considered as part of the value), and storing them into two variables of $name, $value
            
            $name  = trim($name);
            $value = trim($value);
            
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) { // If the $name doesn't already exist as an array key in $_SERVER and $_ENV superglobals arrays
                // echo '<pre>', var_dump(sprintf('%s=%s', $name, $value)), '</pre>'; // format the string into this format or this way: '$name=$value'  in order to be able to be passed to putenv() function
                putenv(sprintf('%s=%s', $name, $value)); // We'll use the getenv() function in the __construct() method of the Database.php Class to get the environment variables put by the putenv() function     // We format $name and $value into this format: '$name=$value', and pass that formatted string to putenv() function
                // echo getenv($name) . '<br>';
                // echo '<pre>', var_dump($_ENV), '</pre>';
            }
        }
    }

}