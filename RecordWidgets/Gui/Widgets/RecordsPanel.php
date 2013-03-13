<?php

namespace ManiaLivePlugins\eXpansion\RecordWidgets\Gui\Widgets;

use ManiaLivePlugins\eXpansion\Gui\Config;
use \ManiaLivePlugins\eXpansion\Gui\Elements\Button as myButton;

use ManiaLivePlugins\eXpansion\RecordWidgets\Gui\Controls\Recorditem;
use ManiaLivePlugins\eXpansion\RecordWidgets\Gui\Controls\DediItem;
use ManiaLive\Gui\ActionHandler;
class RecordsPanel extends \ManiaLive\Gui\Window {

    /** @var \ManiaLive\Gui\Controls\Frame */
    private $frame;
    private $actionDedi;
    private $actionLocal;
    private $btnDedi;
    private $btnLocal;
    
    public static $localrecords = array();
    public static $dedirecords = array();
    
    const SHOW_DEDIMANIA = 0x02;
    const SHOW_LOCALRECORDS = 0x04;

    private $showpanel = self::SHOW_LOCALRECORDS;

    protected function onConstruct() {
        parent::onConstruct();
        $this->setAlign("left", "top");

        $this->actionDedi = ActionHandler::getInstance()->createAction(array($this, "setPanel"), self::SHOW_DEDIMANIA);
        $this->actionLocal = ActionHandler::getInstance()->createAction(array($this, "setPanel"), self::SHOW_LOCALRECORDS);
        $this->btnDedi = new myButton();
        $this->btnDedi->setAction($this->actionDedi);
        $this->btnDedi->setText('$fffDedimania');
        $this->btnDedi->colorize(7774);
        $this->btnDedi->setPosX(2);
        $this->btnDedi->setScale(0.6);
        $this->addComponent($this->btnDedi);
        
        $this->btnLocal = new myButton();
        $this->btnLocal->setAction($this->actionLocal);
        $this->btnLocal->setText('$fffLocal');
        $this->btnLocal->colorize(7774);
        $this->btnLocal->setScale(0.6);
        $this->btnLocal->setPosX(20);
        $this->addComponent($this->btnLocal);
        

        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setAlign("left", "top");
        $this->frame->setPosition(3, -6);
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Column(-1));
        $this->addComponent($this->frame);
    }

    function onResize($oldX, $oldY) {
        parent::onResize($oldX, $oldY);
    }

    function onDraw() {
        $index = 1;
        $this->frame->clearComponents();
        $lbl = new \ManiaLib\Gui\Elements\Label(20, 5);
        $lbl->setAlign("left", "center");
        if ($this->showpanel == self::SHOW_DEDIMANIA)
            $lbl->setText('$fffDedimania Records');
        if ($this->showpanel == self::SHOW_LOCALRECORDS)
            $lbl->setText('$fffLocal Records');
        $lbl->setScale(0.9);
        $this->frame->addComponent($lbl);
        if ($this->showpanel == self::SHOW_DEDIMANIA) {
            if (!is_array(self::$dedirecords)) return;
            foreach (self::$dedirecords as $record) {
                if ($index > 30)
                    return;
                $this->frame->addComponent(new DediItem($index++, $record));
            }
        }

        if ($this->showpanel == self::SHOW_LOCALRECORDS) {
            foreach (self::$localrecords as $record) {
                if ($index > 30)
                    return;
                $this->frame->addComponent(new Recorditem($index++, $record));
            }
        }
        parent::onDraw();
    }

    function setPanel($login, $panel) {
        $this->showpanel = $panel;
        $this->redraw();
    }

    function destroy() {
        ActionHandler::getInstance()->deleteAction($this->actionDedin);
        ActionHandler::getInstance()->deleteAction($this->actionLocal);
        $this->btnDedi->destroy();
        $this->btnLocal->destroy();                
        $this->frame->clearComponents();
        $this->frame->destroy();
        $this->clearComponents();
        parent::destroy();
    }

}

?>
