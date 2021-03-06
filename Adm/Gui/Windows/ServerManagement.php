<?php

namespace ManiaLivePlugins\eXpansion\Adm\Gui\Windows;

use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as myButton;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Inputbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Checkbox;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Ratiobutton;
use ManiaLivePlugins\eXpansion\Adm\Gui\Controls\MatchSettingsFile;
use ManiaLive\Gui\ActionHandler;

/**
 * Server Controlpanel Main window
 * 
 * @author Petri
 */
class ServerManagement extends \ManiaLivePlugins\eXpansion\Gui\Windows\Window {

    /** @var \DedicatedApi\Connection */
    private $connection;

    /** @var \ManiaLive\Data\Storage */
    private $storage;
    private $frame;
    private $closeButton;
    private $actions;
    private $btn1, $btn2;

    protected function onConstruct() {
        parent::onConstruct();
        $config = \ManiaLive\DedicatedApi\Config::getInstance();
        $this->connection = \DedicatedApi\Connection::factory($config->host, $config->port);
        $this->storage = \ManiaLive\Data\Storage::getInstance();

        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Line());

        $this->actions = new \stdClass();
        $this->actions->close = ActionHandler::getInstance()->createAction(array($this, "close"));
        $this->actions->stopServer = ActionHandler::getInstance()->createAction(array($this, "stopServer"));
        $this->actions->stopManialive = ActionHandler::getInstance()->createAction(array($this, "stopManialive"));

        $this->btn1 = new myButton(40, 6);
        $this->btn1->setText(__("Stop Server", $this->getRecipient()));
        $this->btn1->setAction($this->actions->stopServer);
        $this->btn1->colorize("d00");
        $this->frame->addComponent($this->btn1);

        $this->btn2 = new myButton(40, 6);
        $this->btn2->setText(__("Stop Manialive", $this->getRecipient()));
        $this->btn2->setAction($this->actions->stopManialive);
        $this->btn2->colorize("d00");
        $this->frame->addComponent($this->btn2);


        $this->addComponent($this->frame);

        $this->closeButton = new myButton(30, 6);
        $this->closeButton->setText(__("Cancel",$this->getRecipient()));
        $this->closeButton->setAction($this->actions->close);
        $this->addComponent($this->closeButton);
    }

    function stopServer($login) {
        $this->connection->stopServer();
    }

    function stopManialive($login) {
        die();
    }

    function close() {
        $this->Erase($this->getRecipient());
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
        $this->frame->setPosition(0, -8);
        $this->closeButton->setPosition($this->sizeX - 28, -($this->sizeY - 6));
    }

    function destroy() {
        ActionHandler::getInstance()->deleteAction($this->actions->close);
        ActionHandler::getInstance()->deleteAction($this->actions->stopServer);
        ActionHandler::getInstance()->deleteAction($this->actions->stopManialive);
        $this->closeButton->destroy();
        $this->btn1->destroy();
        $this->btn2->destroy();
        unset($this->actions);
        parent::destroy();
    }

}

?>
