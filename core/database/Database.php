<?php
// Database Connection Class
namespace app\core\database;
use app\core\CustomDotEnvFileReading;
use \PDO;



class Database {
    public \PDO $pdo_connection; // An object/instance of the PHP built-in \PDO class


    public function __construct() {
        // Creating an instance of my custom CustomDotEnvFileReading.php class to be able to read the .env file which contains our database login credentials
        (new CustomDotEnvFileReading(dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env'))->readDotEnvFile(); // create an object ON THE FLY    // pass in the path of the '.env' file

        $database_dsn      = getenv('DATABASE_DSN'); // $dsn (DSN prefix, host, port, dbname)    // Data Source Name    // putenv() function was used inside the readDotEnvFile() method in CustomDotEnvFileReading.php Class
        $database_username = getenv('DATABASE_USERNAME'); // putenv() function was used inside the readDotEnvFile() method in CustomDotEnvFileReading.php Class
        $database_password = getenv('DATABASE_PASSWORD'); // putenv() function was used inside the readDotEnvFile() method in CustomDotEnvFileReading.php Class

        $this->pdo_connection = new \PDO($database_dsn, $database_username, $database_password);
        $this->pdo_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

}