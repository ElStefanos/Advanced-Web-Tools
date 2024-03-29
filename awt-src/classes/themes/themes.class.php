<?php

namespace themes;

use database\databaseConfig;
use admin\profiler;
use notifications\notifications;
use paging\renderer;

class themes extends modules
{
    private object $dataBase;
    private $authorized;
    private object $mysqli;
    protected array $themes;
    public array $activeTheme;
    public string $themeName;
    public string $themeVersion;
    public string $themeAuthor;
    public string $linkToThemeDir;

    public array $settingsPage;

    public function __construct()
    {
        $this->dataBase = new databaseConfig;
        $this->authorized = $this->dataBase->checkAuthority();

        $this->authorized = $this->dataBase->checkAuthority() == 1 or die("Fatal error database access for " . $this->dataBase->getCaller() . " was denied");
        $this->mysqli = $this->dataBase->getConfig();

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_themes` ORDER BY `id` ASC;");
        $stmt->execute();
        $this->themes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    public function getThemes()
    {
        return $this->themes;
    }

    public function getActiveTheme(): array
    {
        foreach ($this->themes as $key => $value) {
            if ($value['active'] == 1) {
                $this->activeTheme = $this->themes[$key];
                return $this->activeTheme;
            }
        }

        return array();
    }

    public function loadTheme() : string
    {
        global $builtInPages;
        global $theme;
        global $dependencies;
        global $engines;
        global $settings;
        global $navbar;
        global $widgets;
        global $aio;
        global $menu;
        global $pluginPages;
        global $pages;
        global $dashboardWidgets;
        global $floatingEditor;
        global $paging;

        $this->getActiveTheme();

        file_exists(THEMES . $this->activeTheme['name'] . DIRECTORY_SEPARATOR . "theme.php") or die("Error loading theme.");

        $this->linkToThemeDir = HOSTNAME . "awt-content/themes/" . $this->activeTheme['name'];

        ob_start();

        include_once THEMES . $this->activeTheme['name'] . DIRECTORY_SEPARATOR . "theme.php";

        $content = ob_get_contents();
        ob_end_clean();

        return $content;

    }

    public function loadCSS(string $path): string
    {
        return '<link rel="stylesheet" href="' . $this->linkToThemeDir . $path . '"/>';
    }

    public function getAssetLink(string $path)
    {
        return $this->linkToThemeDir . $path;
    }

    public function loadThemePage(string $name): string
    {
        global $theme;
        global $paging;
        global $render;

        $this->getActiveTheme();

        if(!array_key_exists($name, $paging->pages)) {
            die("error: $name does not exist in theme pages");
        }

        $themeId = (int) $this->activeTheme['id'];

        $this->linkToThemeDir = HOSTNAME . "awt-content/themes/" . $this->activeTheme['name'];

        if (!$this->checkForCustomizedPage($name)) {
            ob_start();

            include_once $paging->pages[$name]['path'];

            $contents = ob_get_contents();

            ob_end_clean();

            return $contents;
        }

        $content = "";

        $stmt = $this->mysqli->prepare("SELECT `page_name`, `content` FROM `awt_theme_page` WHERE `theme_id` = ? AND `page_name` = ?");

        $stmt->bind_param("is", $themeId , $name);

        if($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($name, $content);
            $stmt->fetch();
        } else {
            die("ERROR HAS OCCURED");
        }

        return $content;

    }

    public function checkForCustomizedPage(string $name): bool
    {
        $this->getActiveTheme();
    
        $themeId = $this->activeTheme['id'];  

        $stmt = $this->mysqli->prepare("SELECT * FROM `awt_theme_page` WHERE `theme_id` = ? AND `page_name` = ?;");
        
        $stmt->bind_param("is", $themeId, $name);
    
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
    
        if ($stmt->num_rows == 0) {
            return false;
        }
    
        return true;
    }

    public function getAllCustomizedPages(int $id)
    {

        $stmt = $this->mysqli->prepare("SELECT `id`, `page_name` FROM `awt_theme_page` WHERE `theme_id` = ?");

        $stmt->bind_param("i", $id);

        
        if($stmt->execute()) {
            $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            die("ERROR HAS OCCURED");
        }

        return $row;
    }

    public function revertChanges(int $id)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM `awt_theme_page` WHERE `theme_id` = ?");

        $stmt->bind_param("i", $id);

        if($stmt->execute()) {
            $stmt->close();
        } else {
            die("ERROR HAS OCCURED");
        }
    }

    public function uploadCustomPage(string $name, string $content)
    {
        $this->getActiveTheme();

        $themeId = (int) $this->activeTheme['id'];

        $renderer = new renderer();

        $content = $renderer::sanitizePage($content);

        if (!$this->checkForCustomizedPage($name)) {
            $stmt = $this->mysqli->prepare("INSERT INTO `awt_theme_page` (`theme_id`, `page_name`, `content`) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $themeId, $name, $content);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $this->mysqli->prepare("UPDATE `awt_theme_page` SET `content` = ? WHERE `page_name` = ? AND `theme_id` = ?;");
            $stmt->bind_param("ssi", $content, $name, $themeId);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function enableTheme(int $id, profiler $profiler)
    {

        if ($profiler->checkPermissions(0)) {

            $status = 1;

            $oldThemeStatus = 0;

            $stmt = $this->mysqli->prepare("UPDATE `awt_themes` SET `active` = ? WHERE `active` = ?;");
            $stmt->bind_param("ss", $oldThemeStatus, $status);
            $stmt->execute();

            $stmt = $this->mysqli->prepare("UPDATE `awt_themes` SET `active` = ? WHERE `id` = ?;");
            $stmt->bind_param("ss", $status, $id);
            $stmt->execute();

            $notification = new notifications("Themes", $profiler->name . " has changed sites theme.", "notice");
            $notification->pushNotification();

            return true;

        } else {
            return false;
        }

    }
}
