<?php

namespace ManiaLivePlugins\eXpansion\Adm;

use ManiaLive\Event\Dispatcher;
use DedicatedApi\Structures\GameInfos;
use ManiaLive\Gui\ActionHandler;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerOptions;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\GameOptions;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\AdminPanel;
use ManiaLivePlugins\eXpansion\Adm\Gui\Windows\ServerControlMain;

class Adm extends \ManiaLivePlugins\eXpansion\Core\types\ExpPlugin {

    public function exp_onInit() {
        parent::exp_onInit();
        //Oliverde8 Menu
        if ($this->isPluginLoaded('oliverde8\HudMenu')) {
            Dispatcher::register(\ManiaLivePlugins\oliverde8\HudMenu\onOliverde8HudMenuReady::getClass(), $this);
        }
    }

    function exp_onReady() {
        //    $methods = get_class_methods($this->connection);
        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildStdMenu();

        if ($this->isPluginLoaded('eXpansion\Menu')) {
            $this->callPublicMethod('eXpansion\Menu', 'addSeparator', __('Server Management'), true);
            $this->callPublicMethod('eXpansion\Menu', 'addItem', __('Server Management'), null, array($this, 'serverControlMain'), true);
            //  $this->callPublicMethod('eXpansion\Menu', 'addItem', __('Force Scores'), null, array($this, 'forceScores'), true);
        }

        $this->enableDedicatedEvents();
        ServerControlMain::$mainPlugin = $this;
        Gui\Windows\RoundPoints::$plugin = $this;

        foreach ($this->storage->players as $player)
            $this->onPlayerConnect($player->login, false);
        foreach ($this->storage->spectators as $player)
            $this->onPlayerConnect($player->login, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        if (\ManiaLivePlugins\eXpansion\AdminGroups\AdminGroups::hasPermission($login,'server_admin')) {
            $info = AdminPanel::Create($login);
            $info->setSize(50, 20);
            $info->setPosition(-160, -46);
            $info->show();
        }
    }

    public function onPlayerDisconnect($login, $reason = null) {
        AdminPanel::Erase($login);
    }

    public function onOliverde8HudMenuReady($menu) {

        $parent = $menu->findButton(array('admin', 'Server Options'));
        if (!$parent) {
            $button["style"] = "Icons128x128_1";
            $button["substyle"] = "Options";
            $button["plugin"] = $this;
            $parent = $menu->addButton("admin", "Server Options", $button);
        }

        $button["style"] = "Icons128x128_1";
        $button["substyle"] = "Options";
        $button["plugin"] = $this;
        $button["function"] = "serverOptions";
        $button["permission"] = "server_admin";
        $menu->addButton($parent, "Server Window", $button);

        $parent = $menu->findButton(array('admin', 'Game Options'));
        if (!$parent) {
            $button["style"] = "Icons128x128_1";
            $button["substyle"] = "ProfileAdvanced";
            $button["plugin"] = $this;
            $parent = $menu->addButton("admin", "Game Options", $button);
        }

        $button["style"] = "Icons128x128_1";
        $button["substyle"] = "ProfileAdvanced";
        $button["plugin"] = $this;
        $button["function"] = "gameOptions";
        $button["permission"] = "game_gamemode";
        $menu->addButton($parent, "Game Window", $button);

        $button["style"] = "Icons128x128_1";
        $button["substyle"] = "Save";
        $button["plugin"] = $this;
        $button["function"] = "matchSettings";
        $button["permission"] = "game_match";
        $menu->addButton($parent, "Match Settings", $button);
    }

    public function buildStdMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons128x128_1::Options);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Server ControlPanel'), array($this, 'serverControlMain'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Server Options'), array($this, 'serverOptions'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Game Options'), array($this, 'gameOptions'), true);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Match Settings'), array($this, 'matchSettings'), true);
    }

    public function serverOptions($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $window = ServerOptions::Create($login);
            $window->setTitle(__('Server Options', $login));
            $window->centerOnScreen();
            $window->setSize(160, 80);
            $window->show();
        }
    }

    public function forceScores($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $gamemode = $this->storage->gameInfos->gameMode;
            if ($gamemode == GameInfos::GAMEMODE_ROUNDS || $gamemode == GameInfos::GAMEMODE_TEAM) {
                $window = Gui\Windows\ForceScores::Create($login);
                $window->setTitle(__('Force Scores', $login));
                $window->centerOnScreen();
                $window->setSize(160, 80);
                $window->show();
            } else {
                $this->exp_chatSendServerMessage("ForceScores can be used only with rounds or team mode", $login);
            }
        }
    }

    public function gameOptions($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            $window = GameOptions::Create($login);
            $window->setTitle(__('Game Options', $login));
            $window->setSize(160, 50);
            $window->centerOnScreen();
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

    public function roundPoints($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $window = Gui\Windows\RoundPoints::Create($login);
            $window->setTitle(__('Custom Round Points'));
            $window->setSize(160, 70);
            $window->centerOnScreen();
            $window->show();
        }
    }

    public function serverControlMain($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            $window = Gui\Windows\ServerControlMain::Create($login);
            $window->setTitle(__('Server Management', $login));
            $window->setSize(120, 20);
            $window->show();
        }
    }

    public function matchSettings($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            $window = Gui\Windows\MatchSettings::Create($login);
            $window->setTitle(__('Match Settings', $login));
            $window->centerOnScreen();
            $window->setSize(160, 100);
            $window->show();
        }
    }

    public function scriptSettings($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            if ($this->storage->gameInfos->gameMode == GameInfos::GAMEMODE_SCRIPT) {
                $window = Gui\Windows\ScriptSettings::Create($login);
                $window->setTitle(__('Script Settings', $login));
                $window->centerOnScreen();
                $window->setSize(160, 100);
                $window->show();
            } else {
                $this->exp_chatSendServerMessage("ScriptSettings available only in script mode", $login);
            }
        }
    }

    public function dbTools($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'server_admin')) {
            if ($this->isPluginLoaded("eXpansion\Database")) {
                $this->callPublicMethod("eXpansion\Database", "showDbMaintainance", $login);
            } else {
                $this->exp_chatSendServerMessage("Database plugin not loaded!", $login);
            }
        }
    }

    public function adminGroups($login) {
        if ($this->callPublicMethod('eXpansion\AdminGroups', 'getPermission', $login, 'game_settings')) {
            \ManiaLivePlugins\eXpansion\AdminGroups\AdminGroups::getInstance()->windowGroups($login);
        }
    }

    public function setPoints($login, $points) {
        try {
            $nick = $this->storage->getPlayerObject($login)->nickName;
            $ipoints = implode(",", $points);
            $msg = exp_getMessage('#admin_action#Admin %s $z$s#admin_action#sets custom round points to #variable#%s');
            $this->exp_chatSendServerMessage($msg, null, array($nick, $ipoints));
            $this->connection->setRoundCustomPoints($points);
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__('#error#Error: %s', $login, $e->getMessage()), $login);
        }
    }

}

?>