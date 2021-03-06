<?php

namespace ManiaLivePlugins\eXpansion\Maps\Gui\Windows;

require_once(__DIR__ . "/gbxdatafetcher.inc.php");

use \ManiaLivePlugins\eXpansion\Maps\Gui\Controls\Additem;

class AddMaps extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    private $pager;

    /** @var  \DedicatedApi\Connection */
    private $connection;

    /** @var  \ManiaLive\Data\Storage */
    private $storage;
    private $items = array();
    private $gbx;
    private $btnAddAll;
    private $actionAddAll;
    private $label;

    protected function onConstruct() {
        parent::onConstruct();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();
        $this->gbx = new \GBXChallMapFetcher(true, false, false);
        $this->pager = new \ManiaLive\Gui\Controls\Pager();
        $this->mainFrame->addComponent($this->pager);

        $this->actionAddAll = $this->createAction(array($this, "addAllMaps"));

        $this->btnAddAll = new \ManiaLivePlugins\eXpansion\Gui\Elements\Button();
        $this->btnAddAll->setText("Add all");
        $this->btnAddAll->setAction($this->actionAddAll);
        $this->mainFrame->addComponent($this->btnAddAll);
    }

    function addMap($login, $filename) {
        try {
            $this->connection->addMap($filename);
            $mapinfo = $this->connection->getMapInfo($filename);
            $this->connection->chatSendServerMessage(__('Map %s $z$s$fffadded to playlist.', $this->getRecipient(), $mapinfo->name));
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__('Error:', $e->getMessage()));
        }
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->pager->setSize($this->sizeX, $this->sizeY - 12);
        $this->pager->setStretchContentX($this->sizeX);

        $this->btnAddAll->setPosition(4, -$this->sizeY + 6);
    }

    function onShow() {
        $this->populateList();
    }

    function populateList() {
        foreach ($this->items as $item) {
            $item->destroy();
        }

        $this->pager->clearItems();
        $this->items = array();

        $login = $this->getRecipient();

        /** @var \DedicatedApi\Structures\Version */
        $game = $this->connection->getVersion();
        $path = $this->connection->getMapsDirectory() . "/Downloaded/" . $game->titleId . "/*.Map.Gbx";

        $maps = glob($path);
        $x = 0;
        if (count($maps) >= 1) {
            foreach ($maps as $map) {
                $this->items[$x] = new Additem($x, $map, $this, $this->gbx, $this->sizeX);
                $this->pager->addItem($this->items[$x]);
                $x++;
            }
        }
    }

    function deleteMap($login, $filename) {
        try {
            unlink($filename);
            $file = explode("/", $filename);
            $this->connection->chatSendServerMessage(__("File '%s' deleted from filesystem!", $this->getRecipient(), end($file)));
            $this->populateList();
            $this->RedrawAll();
        } catch (\Exception $e) {
            $this->connection->chatSendServerMessage(__('$f00$oError $z$s$fff%s', $this->getRecipient(), $e->getMessage()));
        }
    }

    function addAllMaps($login) {
        $game = $this->connection->getVersion();
        $mapsDir = $this->connection->getMapsDirectory();
        $path = str_replace("\\", "/", $mapsDir) . "Downloaded/" . $game->titleId . "/*.Map.Gbx";

        $mapsAtDisk = glob($path);
//        $mapsAtServer = array();
//        foreach ($this->storage->maps as $map) {
//            $mapsAtServer[] = str_replace("\\", "/", $mapsDir . $map->fileName);
//        }
//        $mapDiff = array_diff($mapsAtServer, $mapsAtDisk);

        $this->connection->addMapList($mapsAtDisk);
        $this->connection->chatSendServerMessage("Added " . count($mapsAtDisk) . " maps to playlist.", $login);
    }

    function destroy() {
        $this->gbx = null;
        foreach ($this->items as $item) {
            $item->destroy();
        }
        $this->items = array();
        $this->btnAddAll->destroy();
        $this->connection = null;
        $this->storage = null;
        $this->pager->destroy();
        $this->clearComponents();

        parent::destroy();
    }

}

?>
