<?php

namespace settings;

use admin\profiler;
use cache\cache;
use database\databaseConfig;

class settings
{
    private object $database;
    private object $mysqli;
    private object $profiler;
    private object $cache;
    public array $allSettings;

    public function __construct()
    {
        $this->database = new databaseConfig;

        $this->database->checkAuthority() == 1 or die("Fatal error database access for " . $this->database->getCaller() . " was denied");

        $this->mysqli = $this->database->getConfig();

        $this->profiler = new profiler;

        $this->cache = new cache;

        $this->fetchSettings();
        
    }

    public function fetchSettings() {
        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_settings` ORDER BY `id` ASC;");
        $stmt->execute();
        $this->allSettings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    public function getSettingsValue(string $name)
    {
        foreach ($this->allSettings as $key => $value) {
            if ($value['name'] == $name) return $value['value'];
        }
        return false;
    }

    public function getSettingsPermission(string $name)
    {
        foreach ($this->allSettings as $key => $value) {
            if ($value['name'] == $name) return $value['required_permission_level'];
        }
        return false;
    }

    public function setSetting(string $name, string $value, int $required_permission = 0)
    {

        if ($perm = $this->getSettingsValue($name) !== false) {

            if (!$this->profiler->checkPermissions($perm)) return false;

            $stmt = $this->mysqli->prepare("UPDATE `awt_settings` SET `value` = ?, `required_permission_level` = ? WHERE `name` = ?;");
            $stmt->bind_param('sss', $value, $required_permission, $name);
            $stmt->execute();
            $stmt->close();

            return true;
        }


        return false;
    }

    public function createSetting(string $name, string $value)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO `awt_settings` (`name`, `value`, `required_permission_level`) VALUES (?, ?, ?);");
        $stmt->bind_param('ssi', $name, $value, $this->profiler->permissionLevel);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function checkIfSettingExists(string $name) {
        foreach ($this->allSettings as $key => $value) {
            if ($value['name'] == $name) return true;
        }
        return false;
    }
}
