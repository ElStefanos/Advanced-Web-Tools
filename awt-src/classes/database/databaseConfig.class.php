<?php

namespace database;

use mysqli;
use notifications\notifications;

final class databaseConfig
{
    private static string $hostname = "localhost";
    private static string $database = "awt_development";
    private static string $username = "admin";
    private static string $password = "Hektor011!";
    private static string $key = "f504b7626bb37e15978130f4ebbb32fc4307a4be18d96484d677cd80882c678beb62090da401a9480701ce7b59918526adf656210a9d7a703a1dc218b10d14d4";
    private static int $keyLength;
    private $authorized;
    private string $fileHash;
    private $caller;
    private int $allow;
    private static array $allowedCallers = array(
        JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-autoLoader.php',
        ROOT . DIRECTORY_SEPARATOR . 'index.php',
        JOBS.'loaders'.DIRECTORY_SEPARATOR.'awt-themesLoader.php',
        CLASSES.'admin'.DIRECTORY_SEPARATOR.'admin.class.php',
        CLASSES.'admin'.DIRECTORY_SEPARATOR.'authentication.class.php',
        CLASSES.'admin'.DIRECTORY_SEPARATOR.'profiler.class.php',
        CLASSES.'plugins'.DIRECTORY_SEPARATOR.'plugins.class.php',
        CLASSES.'themes'.DIRECTORY_SEPARATOR.'themes.class.php',
        CLASSES.'menu'.DIRECTORY_SEPARATOR.'menu.class.php',
        CLASSES.'content'.DIRECTORY_SEPARATOR.'pluginInstaller.class.php',
        CLASSES.'content'.DIRECTORY_SEPARATOR.'themeInstaller.class.php',
        CLASSES.'paging'.DIRECTORY_SEPARATOR.'paging.class.php',
        CLASSES.'settings'.DIRECTORY_SEPARATOR.'settings.class.php',
        CLASSES.'media'.DIRECTORY_SEPARATOR.'albums.class.php',
        CLASSES.'media'.DIRECTORY_SEPARATOR.'media.class.php',
        CLASSES.'notifications'.DIRECTORY_SEPARATOR.'notifications.class.php',
        CLASSES.'updater'.DIRECTORY_SEPARATOR.'notifications.class.php',
    );

    private object $mysqli;

    private object $stmt;

    public function __construct()
    {
        databaseConfig::$keyLength = strlen(databaseConfig::$key);;

        $this->mysqli = new mysqli(databaseConfig::$hostname, databaseConfig::$username, databaseConfig::$password, databaseConfig::$database);

        if ($this->mysqli->connect_error) {
            die("Database Connection Error");
        }
    }

    public function checkAuthority()
    {
        $this->caller = debug_backtrace();
        $this->caller = $this->caller[0]['file'];
        

        if (!in_array($this->caller, databaseConfig::$allowedCallers)) {
            
            $this->fileHash = hash_file('SHA512', $this->caller);

            $this->stmt = $this->mysqli->prepare("SELECT * FROM `awt_access_authorization` WHERE `fileName` = ? AND `fileHash` = ?");
            $this->stmt->bind_param('ss', $this->caller, $this->fileHash);
            $this->stmt->execute();
            $this->stmt->store_result();
            $this->stmt->bind_result($this->authorized[0], $this->authorized[1], $this->authorized[2], $this->authorized[3]);
            $this->stmt->fetch();

            $this->allow = 0;

            if ($this->stmt->num_rows == 1) {
                $tempKey = databaseConfig::$key;
                $cut = substr($tempKey, 0, databaseConfig::$keyLength - strlen($this->authorized[3]));
                if ($cut . $this->authorized[3] == databaseConfig::$key) {
                    $this->allow = 1;
                    return 1;
                } else {
                    $this->allow = 0;
                    return 0;
                }
            }

            $this->stmt->close();
        } else {
            $this->allow = 1;
            return 1;
        }
    }

    public function getConfig()
    {   
        if ($this->allow == 1) {
            return $this->mysqli;
        } else {
            $notification = new notifications("Database", $this->getCaller(). " Tried to access database.", "incident");
            $notification->pushNotification();
            return false;
        }
    }

    public function getCaller()
    {   
        return $this->caller;
    }

    public function getSecretKey()
    {
        if ($this->allow == 0) return false;
        
        $cut = rand(10, databaseConfig::$keyLength - 10);
        $string = substr(databaseConfig::$key, $cut, databaseConfig::$keyLength);
        return $string;
    }

    public function authorizeUsage($action, $file, $hash, $key = '')
    {   
        if ($this->allow == 0) return false;
        
        $content = ucfirst($action);
        $notification = new notifications("Database", "$content for $file", "notice");
        $notification->pushNotification();

        if($key == '') $key = $this->getSecretKey();

        if($action == 'authorize') {
            $stmt = $this->mysqli->prepare("INSERT INTO `awt_access_authorization` (`fileName`, `fileHash`, `uniqueKey`) VALUES (?, ?, ?);");
            $stmt->bind_param('sss', $file, $hash, $key);
            $stmt->execute();
            $stmt->close();
            return true;
        }

        if($action == 'unauthorize') {
            $stmt = $this->mysqli->prepare("DELETE FROM `awt_access_authorization` WHERE `fileHash` = ?;");
            $stmt->bind_param('s', $hash);
            $stmt->execute();
            $stmt->close();
            return true;
        }

        return false;
    }

    public function checkForTable(string $tableName) {
        if ($this->allow == 0) return false;
    
        $query = "SELECT * FROM " . $this->mysqli->real_escape_string($tableName) . ";";
    
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            return false;
        }
    
        $result = $stmt->execute();
        $stmt->close();
    
        return $result;
    }
    
}
