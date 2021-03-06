<?php

namespace ManiaLivePlugins\eXpansion\Players;

use ManiaLive\Event\Dispatcher;

class Players extends \ManiaLivePlugins\eXpansion\Core\types\ExpPlugin {

    public function exp_onInit() {
        parent::exp_onInit();
        //Oliverde8 Menu
        if ($this->isPluginLoaded('oliverde8\HudMenu')) {
            Dispatcher::register(\ManiaLivePlugins\oliverde8\HudMenu\onOliverde8HudMenuReady::getClass(), $this);
        }
    }

    public function exp_onReady() {
        $this->enableDedicatedEvents();
        $this->registerChatCommand("players", "showPlayerList", 0, true); // xaseco
        $this->registerChatCommand("plist", "showPlayerList", 0, true); // fast

        if ($this->isPluginLoaded('Standard\Menubar'))
            $this->buildMenu();

        if ($this->isPluginLoaded('eXpansion\Menu')) {
            $this->callPublicMethod('eXpansion\Menu', 'addSeparator', __('Players'), false);
            $this->callPublicMethod('eXpansion\Menu', 'addItem', __('Show Players'), null, array($this, 'showPlayerList'), false);
        }
    }

    public function onOliverde8HudMenuReady($menu) {
        $parent = $menu->findButton(array("admin", "Players"));
        $button["plugin"] = $this;
        if (!$parent) {
            $button["style"] = "Icons128x128_1";
            $button["substyle"] = "Profile";
            $parent = $menu->addButton("admin", "Players", $button);
        }

        $button["style"] = "Icons128x128_1";
        $button["substyle"] = "Profile";
        $button["plugin"] = $this;
        $button["function"] = 'showPlayerList';
        $parent = $menu->addButton($parent, "Show Players", $button);
        
        $parent = $menu->findButton(array("menu", "Players"));
        if (!$parent) {
            $button["style"] = "Icons128x128_1";
            $button["substyle"] = "Profile";
            $parent = $menu->addButton("menu", "Players", $button);
        }
        
        $button["style"] = "Icons128x128_1";
        $button["substyle"] = "Profile";
        $button["plugin"] = $this;
        $button["function"] = 'showPlayerList';
        $parent = $menu->addButton($parent, "Show Players", $button);
    }

    public function onPlayerDisconnect($login, $reason = null) {
        \ManiaLivePlugins\eXpansion\Players\Gui\Windows\Playerlist::Erase($login);
        $this->updateOpenedWindows();
    }

    public function onPlayerConnect($login, $isSpectator) {
        $this->updateOpenedWindows();        
    }

    public function updateOpenedWindows() {
        $windows = \ManiaLivePlugins\eXpansion\Players\Gui\Windows\Playerlist::GetAll();
        foreach ($windows as $window) {
            $login = $window->getRecipient();
            $this->showPlayerList($login);
        }
    }

    public function buildMenu() {
        $this->callPublicMethod('Standard\Menubar', 'initMenu', \ManiaLib\Gui\Elements\Icons64x64_1::Buddy);
        $this->callPublicMethod('Standard\Menubar', 'addButton', __('Players'), array($this, 'showPlayerList'), false);
    }

    public function showPlayerList($login) {
        \ManiaLivePlugins\eXpansion\Players\Gui\Windows\Playerlist::Erase($login);
        $window = \ManiaLivePlugins\eXpansion\Players\Gui\Windows\Playerlist::Create($login);
        $window->setTitle('Players');
        $window->setSize(160, 100);
        $window->centerOnScreen();
        $window->show();
    }

    public function onPlayerInfoChanged($playerInfo) {
        $this->updateOpenedWindows();
    }

}

?>
