<?php

namespace ManiaLivePlugins\eXpansion\LocalRecords\Gui\Controls;

use ManiaLivePlugins\eXpansion\LocalRecords\Structures\Record;
use ManiaLivePlugins\eXpansion\Gui\Elements\ListBackGround;

use ManiaLivePlugins\eXpansion\Gui\Gui;

/**
 * Description of RecItem
 *
 * @author oliverde8
 */
class RecItem extends \ManiaLive\Gui\Control {
    
    private $label_rank, $label_nick, $label_score, $label_avgScore, $label_nbFinish;
    private $bg;
    private $widths;
     
    function __construct($indexNumber, $login, Record $record, $widths) { 
        $this->widths = $widths;
        $this->sizeY = 4;
        $this->bg = new ListBackGround($indexNumber, 100, 4);
        $this->addComponent($this->bg);
        
        $this->frame = new \ManiaLive\Gui\Controls\Frame();
        $this->frame->setSize(100, 4);
        $this->frame->setPosY(0);
        $this->frame->setLayout(new \ManiaLib\Gui\Layouts\Line());
        $this->addComponent($this->frame);
        
        $this->label_rank = new \ManiaLib\Gui\Elements\Label(10, 4);
        $this->label_rank->setAlign('left', 'center');
        $this->label_rank->setScale(0.8);
        $this->label_rank->setText($record->place.".");
        $this->frame->addComponent($this->label_rank);

        $this->label_nick = new \ManiaLib\Gui\Elements\Label(10., 4);
        $this->label_nick->setAlign('left', 'center');
        $this->label_nick->setScale(0.8);
        $this->label_nick->setText($record->nickName);
        $this->frame->addComponent($this->label_nick);
        
        $this->label_score = new \ManiaLib\Gui\Elements\Label(10, 4);
        $this->label_score->setAlign('left', 'center');
        $this->label_score->setScale(0.8);
        $this->label_score->setText(\ManiaLive\Utilities\Time::fromTM($record->time));
        $this->frame->addComponent($this->label_score);
        
        $this->label_avgScore = new \ManiaLib\Gui\Elements\Label(10, 4);
        $this->label_avgScore->setAlign('left', 'center');
        $this->label_avgScore->setScale(0.8);
        $this->label_avgScore->setText(\ManiaLive\Utilities\Time::fromTM($record->avgScore));
        $this->frame->addComponent($this->label_avgScore);
        
        $this->label_nbFinish = new \ManiaLib\Gui\Elements\Label(10, 4);
        $this->label_nbFinish->setAlign('left', 'center');
        $this->label_nbFinish->setScale(0.8);
        $this->label_nbFinish->setText($record->nbFinish);
        $this->frame->addComponent($this->label_nbFinish);
    }
    
    
    public function onResize($oldX, $oldY) {
        $scaledSizes = Gui::getScaledSize($this->widths, ($this->getSizeX()/.8) - 5);
        $this->bg->setSizeX($this->getSizeX()-5);
        $this->label_rank->setSizeX($scaledSizes[0]);
        $this->label_nick->setSizeX($scaledSizes[1]);
        $this->label_score->setSizeX($scaledSizes[2]);
        $this->label_avgScore->setSizeX($scaledSizes[3]);
        $this->label_nbFinish->setSizeX($scaledSizes[4]);
    }
}

?>
