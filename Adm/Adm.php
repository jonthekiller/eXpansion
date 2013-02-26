<?php

namespace ManiaLivePlugins\eXpansion\Adm;

use \ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerOptions;
use \ManiaLivePlugins\eXpansion\Adm\Gui\Windows\GameOptions;
use ManiaLive\Gui\ActionHandler;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\AdminPanel;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerControlMain;

class Adm extends \ManiaLivePlugins\eXpansion\Core\types\ExpPlugin {

    function exp_onReady() {
        //    $methods = get_class_methods($this->connection);
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildStdMenu();

        if ($this->isPluginLoaded('eXpansion\Menu')) {
            $this->callPublicMethod('eXpansion\Menu', 'addSeparator', __('Server Management'), true);
            $this->callPublicMethod('eXpansion\Menu', 'addItem', __('Server Management'), null, array($this, 'serverControlMain'), true);            
        }

        $this->enableDedicatedEvents();
        ServerControlMain::$mainPlugin = $this;

        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        if (\ManiaLivePlugins\eXpansion\AdminGroups\AdminGroups::isInList($login)) {
            $info = AdminPanel::Create($login);
            $info->setSize(50, 20);
            $info->setPosition(-160, -46);
            $info->show();
        }
    }

    public function onPlayerDisconnect($login) {
        AdminPanel::Erase($login);
    }

    public function buildStdMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Options);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Server Options'), array($this, 'serverOptions'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Game Options'), array($this, 'gameOptions'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Match Settings'), array($this, 'matchSettings'), true);
    }

    public function serverOptions($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $window = ServerOptions::Create($login);
            $window->setTitle(__('Server Options'));
            $window->centerOnScreen();
            $window->setSize(160, 100);
            $window->show();
        }
    }

    public function gameOptions($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            $window = GameOptions::Create($login);
            $window->setTitle(__('Game Options'));
            $window->centerOnScreen();
            $window->setSize(160, 100);
            $window->show();
        }
    }

    public function serverManagement($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $window = Gui\Windows\ServerManagement::Create($login);
            $window->setTitle(__('Server Management'));            
            $window->setSize(60, 20);
            $window->centerOnScreen();
            $window->show();
        }
    }

    public function serverControlMain($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $window = Gui\Windows\ServerControlMain::Create($login);
            $window->setTitle(__('Server Management'));            
            $window->setSize(120, 20);
            $window->show();
        }
    }

    public function matchSettings($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            $window = Gui\Windows\MatchSettings::Create($login);
            $window->setTitle(__('Match Settings'));
            $window->centerOnScreen();
            $window->setSize(120, 100);
            $window->show();
        }
    }

    public function adminGroups($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            \ManiaLivePlugins\eXpansion\AdminGroups\AdminGroups::getInstance()->windowGroups($login);
        }
    }

}

?>