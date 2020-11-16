<?php

namespace clio\taxonomy;

class PDO{
    protected static $instance = [];
    protected static $credentials = [];

    function __construct(){
        throw new Exception("Don't instantiate through \"new\" keyword. Use static getInstance method instead.");
    }

    /**
     * Singleton method that gives you a PDO instance.
     * 
     * @return \PDO Database object.
     */
    public static function getInstance($db_instance = "default") : \PDO
    {
        if(!isset(self::$instance[$db_instance])){
            self::$instance[$db_instance] = self::buildObject($db_instance);
        }
        return self::$instance[$db_instance];
    }

    /**
     * Builds the database object.
     * 
     * @return \PDO The database object.
     */
    protected static function buildObject($db_instance) : \PDO
    {
        if(!isset(self::$credentials[$db_instance])){
            self::loadCredentials();
        }
        $dbhost = self::$credentials[$db_instance]["HOST"];
        $dbname = self::$credentials[$db_instance]["DATABASE"];
        $username = self::$credentials[$db_instance]["USER"];
        $password = self::$credentials[$db_instance]["PASSWORD"];
        $dbport = isset(self::$credentials[$db_instance]["PORT"]) ? (int)self::$credentials[$db_instance]["PORT"] : 3306;
        $charset = 'utf8' ;
        $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname};charset={$charset}";
        if(isset(self::$credentials[$db_instance]["ENGINE"]) && self::$credentials[$db_instance]["ENGINE"] == "mssql" )
        {
            $dsn = "sqlsrv:Server={$dbhost}\SQLEXPRESS;Database={$dbname};";
        }

        $pdo = new PDO\Connection($dsn, $username, $password);
        return $pdo;
    }

    /**
     * Whether database errors should be displayed.
     * 
     * @param bool $show Whether or not to show errors.
     * @return \PDO PDO instance for chaining purposes.
     */
    public static function showErrors(bool $show = true) : \PDO
    {
        if($show){
            self::getInstance()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }else{
            self::getInstance()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
        }
        return self::getInstance();
    }

    /**
     * Hides errors if displayed.
     * 
     * @return \PDO PDO instance for chaining purposes.
     */
    public static function hideErrors() : \PDO
    {
        return self::showErrors(false);
    }

    /**
     * Loads credentials, and makes sure the neccesary ones are set.
     */
    private static function loadCredentials() : void
    {
        if ($path = self::getHomeDir()) {
            $profiles = parse_ini_file($path."/.credentials",true);
            if(is_array($profiles)){
                foreach ($profiles as $name => $profile) {
                    self::$credentials[$name] = $profile;
                }
            }else{
                throw new \Exception("Couldn't find your db profile credentials.");
            }
        } else {
            throw new \Exception("Couldn't find your homedir.");
        }
    }

    /**
     * Gets the environment's HOME directory if available.
     *
     * @return null|string
     */
    private static function getHomeDir() : ?string
    {
        // On Linux/Unix-like systems, use the HOME environment variable
        if ($homeDir = getenv('HOME')) {
            return $homeDir;
        }else{
            throw new \Exception("Couldn't find your HOME path variable.");
        }
    }
}