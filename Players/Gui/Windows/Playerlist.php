<?php

namespace ManiaLivePlugins\eXpansion\Players\Gui\Windows;

use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as OkButton;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Inputbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;
use ManiaLivePlugins\eXpansion\Players\Gui\Controls\Playeritem;
use ManiaLive\Gui\ActionHandler;
use ManiaLivePlugins\eXpansion\AdminGroups\AdminGroups;

class Playerlist extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    private $pager;
    private $connection;
    private $storage;
    private $items = array();

    protected function onConstruct() {
        parent::onConstruct();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->pager = new \ManiaLive\Gui\Controls\Pager();
        $this->mainFrame->addComponent($this->pager);
    }

    function ignorePlayer($login, $target) {
        try {
            $login = $this->getRecipient();
            if (!AdminGroups::hasPermission($login, 'player_ignore')) {
                $this->connection->chatSendServerMessage(__('$ff3$iYou are not allowed to do that!'), $login);
            }
            $player = $this->storage->getPlayerObject($target);
            $admin = $this->storage->getPlayerObject($login);
            $this->connection->ignore($target);
            $this->connection->chatSendServerMessage(__('$%sz was ignored by admin.', $player->nickName));
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__("Error: %s", $e->getMessage()));
        }
    }

    function kickPlayer($login, $target) {
        try {
            $login = $this->getRecipient();
            if (!AdminGroups::hasPermission($login, 'player_kick')) {
                $this->connection->chatSendServerMessage(__('$ff3$iYou are not allowed to do that!'), $login);
            }
            $player = $this->storage->getPlayerObject($target);
            $admin = $this->storage->getPlayerObject($login);
            $this->connection->kick($target, __("Please behave next time you visit the server!"));
            $this->connection->chatSendServerMessage(__('$%sz was kicked from the server by admin.', $player->nickName));
            // can't use notice...since $this->storage->players too slow.
            // $this->connection->sendNotice($this->storage->players, $player->nickName . '$z were kicked from the server by admin.');
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__("Error: %s", $e->getMessage()));
        }
    }

    function banPlayer($login, $target) {
        try {
            $login = $this->getRecipient();
            if (!AdminGroups::hasPermission($login, 'player_ban')) {
                $this->connection->chatSendServerMessage(__('$ff3$iYou are not allowed to do that!'), $login);
            }
            $player = $this->storage->getPlayerObject($target);
            $admin = $this->storage->getPlayerObject($login);
            $this->connection->ban($target, __("You are now banned from the server."));
            $this->connection->chatSendServerMessage(__('%s$z has been banned from the server.', $player->nickName));
            //$this->connection->sendNotice($this->storage->players, $player->nickName . '$z has been banned from the server.');
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__("Error: %s", $e->getMessage()));
        }
    }

    function blacklistPlayer($login, $target) {
        try {
            $login = $this->getRecipient();
            if (!AdminGroups::hasPermission($login, 'player_black')) {
                $this->connection->chatSendServerMessage(__('$ff3$iYou are not allowed to do that!'), $login);
            }
            $player = $this->storage->getPlayerObject($target);
            $admin = $this->storage->getPlayerObject($login);
            $this->connection->banAndBlackList($target, __("You are now blacklisted from the server."), true);
            $this->connection->chatSendServerMessage(__('%s$z has been blacklisted from the server.', $player->nickName));
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__("Error: %s", $e->getMessage()));
        }
    }

    function toggleSpec($login, $target) {
        try {
            $login = $this->getRecipient();
            if (!AdminGroups::hasPermission($login, 'player_spec')) {
                $this->connection->chatSendServerMessage(__('$ff3$iYou are not allowed to do that!'), $login);
            }
            $player = $this->storage->getPlayerObject($target);
            if ($player->forceSpectator == 0) {
                $this->connection->forceSpectator($target, 1);
                $this->connection->sendNotice($target, __('Admin has forced you to specate!', $login));
            }
            if ($player->forceSpectator == 1) {
                $this->connection->forceSpectator($target, 0);
                $this->connection->sendNotice($target, __("Admin has released you from specate to play.", $login));
            }
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__("Error: %s", $login, $e->getMessage()), $login);
        }
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->pager->setSize($this->sizeX - 2, $this->sizeY - 8);
        $this->pager->setStretchContentX($this->sizeX);
        $this->pager->setPosition(4, -4);
    }

    function onShow() {
        $this->populateList();
    }

    function populateList() {

        foreach ($this->items as $item)
            $item->destroy();
        $this->pager->clearItems();
        $this->items = array();

        $x = 0;
        $login = $this->getRecipient();
        $isadmin = AdminGroups::hasPermission($login, "server_admin");
        try {
            foreach ($this->storage->players as $player) {
                $this->items[$x] = new Playeritem($x++, $player, $this, $isadmin, $this->getRecipient(), $this->sizeX);
                $this->pager->addItem($this->items[$x]);
            }
            foreach ($this->storage->spectators as $player) {
                $this->items[$x] = new Playeritem($x++, $player, $this, $isadmin, $this->getRecipient(), $this->sizeX);
                $this->pager->addItem($this->items[$x]);
            }
        } catch (\Exception $e) {
            
        }
    }

    function destroy() {
        $this->connection = null;
        $this->storage = null;
        foreach ($this->items as $item)
            $item->destroy();

        $this->items = null;


        parent::destroy();
    }

}

?>
