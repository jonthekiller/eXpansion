<?php

namespace ManiaLivePlugins\eXpansion\Faq;

class Faq extends \ManiaLivePlugins\eXpansion\Core\types\ExpPlugin {

    public function exp_onReady() {
        parent::exp_onReady();
        $this->enableDedicatedEvents();
        $this->registerChatCommand("faq", "showFaq", 0, true);
        Gui\Windows\FaqWindow::$mainPlugin = $this;
        
        $this->showFaq("reaby");
    }

    public function showFaq($login, $topic = "toc") {
        $player = $this->storage->getPlayerObject($login);
        
        $window = Gui\Windows\FaqWindow::Create($login, true);
        $window->setLanguage($player->language);
        $window->setTopic($topic);
        $window->setSize(160, 90);
        $window->show();
    }

}

?>
