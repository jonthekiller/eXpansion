<?php

namespace ManiaLivePlugins\eXpansion\Adm\Gui\Windows;

use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as OkButton;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Inputbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;
use ManiaLivePlugins\eXpansion\Adm\Gui\Controls\MatchSettingsFile;
use ManiaLive\Gui\ActionHandler;
use \ManiaLivePlugins\eXpansion\Adm\Structures\CustomPoint;

class RoundPoints extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    public static $plugin = null;
    private $pager;

    /** @var \DedicatedApi\Connection */
    private $connection;

    /** @var \ManiaLive\Data\Storage */
    private $storage;
    private $items = array();
    private $cancel;
    private $actionCancel;
    private $rpoints = array();

    protected function onConstruct() {
        parent::onConstruct();
        $login = $this->getRecipient();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->pager = new \ManiaLive\Gui\Controls\Pager();
        $this->mainFrame->addComponent($this->pager);

        $this->actionCancel = $this->createAction(array($this, "Cancel"));


        $this->cancel = new OkButton();
        $this->cancel->setText(__("Close", $login));
        $this->cancel->setAction($this->actionCancel);
        $this->mainFrame->addComponent($this->cancel);


        $this->rpoints[] = new CustomPoint('Formula 1 GP New', array(25, 18, 15, 12, 10, 8, 6, 4, 2, 1));
        $this->rpoints[] = new CustomPoint('Formula 1 GP Old', array(10, 8, 6, 5, 4, 3, 2, 1));
        $this->rpoints[] = new CustomPoint('MotoGP', array(25, 20, 16, 13, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1));
        $this->rpoints[] = new CustomPoint('MotoGP + 5', array(30, 25, 21, 18, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1));
        $this->rpoints[] = new CustomPoint('Formula ET Season 1', array(12, 10, 9, 8, 7, 6, 5, 4, 4, 3, 3, 3, 2, 2, 2, 1));
        $this->rpoints[] = new CustomPoint('Formula ET Season 2', array(15, 12, 11, 10, 9, 8, 7, 6, 6, 5, 5, 4, 4, 3, 3, 3, 2, 2, 2, 1));
        $this->rpoints[] = new CustomPoint('Formula ET Season 3', array(15, 12, 11, 10, 9, 8, 7, 6, 6, 5, 5, 4, 4, 3, 3, 3, 2, 2, 2, 2, 1));
        $this->rpoints[] = new CustomPoint('Champ Car World Series', array(31, 27, 25, 23, 21, 19, 17, 15, 13, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1));
        $this->rpoints[] = new CustomPoint('Superstars', array(20, 15, 12, 10, 8, 6, 4, 3, 2, 1));
        $this->rpoints[] = new CustomPoint('Simple 5', array(5, 4, 3, 2, 1));
        $this->rpoints[] = new CustomPoint('Simple 10', array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1));
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->pager->setSize($this->sizeX, $this->sizeY - 8);
        $this->pager->setStretchContentX($this->sizeX);
        $this->cancel->setPosition($this->sizeX - 20, -$this->sizeY + 6);
    }

    function onShow() {
        $this->populateList();
    }

    function populateList() {
        foreach ($this->items as $item)
            $item->destroy();
        $this->pager->clearItems();
        $this->items = array();

        $login = $this->getRecipient();
        $x = 0;

        $login = $this->getRecipient();
        $this->items[$x] = new \ManiaLivePlugins\eXpansion\Adm\Gui\Controls\CustomPointctrl($x, new CustomPoint(__('Current points', $login), $this->connection->getRoundCustomPoints()), $this, $login, $this->sizeX);
        $this->pager->addItem($this->items[$x]);
        $x++;

        foreach ($this->rpoints as $points) {
            $this->items[$x] = new \ManiaLivePlugins\eXpansion\Adm\Gui\Controls\CustomPointctrl($x, $points, $this, $login, $this->sizeX);
            $this->pager->addItem($this->items[$x]);
            $x++;
        }
    }

    function setPoints($login, $points) {
        self::$plugin->setPoints($login, $points);
        $this->erase($login);
    }

    function Cancel($login) {
        $this->erase($login);
    }

    function destroy() {
        foreach ($this->items as $item)
            $item->destroy();

        $this->items = array();
        $this->pager->destroy();
        $this->cancel->destroy();
        $this->connection = null;
        $this->storage = null;
        $this->clearComponents();
        parent::destroy();
    }

}

?>
