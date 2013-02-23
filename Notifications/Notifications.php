<?php

namespace ManiaLivePlugins\eXpansion\Notifications;

use \ManiaLivePlugins\eXpansion\Notifications\Gui\Widgets\Panel as NotificationPanel;
use \ManiaLivePlugins\eXpansion\Notifications\Structures\Item;

class Notifications extends \ManiaLivePlugins\eXpansion\Core\types\ExpPlugin {

    private $messages = array();

    function exp_onInit() {
        $this->setPublicMethod("send");  
    }

    function exp_onReady() {
        $this->enableDedicatedEvents();
		
		/**
		 * Redirecting The Announcements of the Admin Groups plugin
		 */
		$this->callPublicMethod('eXpansion\\Chat_Admin', 'exp_activateAnnounceRedirect', array($this, 'send'));
        
		$this->reDraw();
    }
	
    function send($message, $icon = null, $callback = null, $pluginid = null) {
        if (is_callable($callback) || $callback === null) {
            $item = new Item($icon, $message, $callback);
            $hash = spl_object_hash($item);
            $this->messages[$hash] = $item;
            $array = array_reverse($this->messages, true);
            $array = array_slice($array, 0, 7, true);
            $this->messages = array_reverse($array, true);
            $this->reDraw();
        } else {
            \ManiaLive\Utilities\Console::println("Adding a button failed from plugin:" . $pluginid . " button callback is not valid.");
        }
    }

    function reDraw() {
            $this->onPlayerConnect(NotificationPanel::RECIPIENT_ALL, true);
    }

    function onPlayerConnect($login, $isSpectator) {
        $info = NotificationPanel::Create($login);
        $info->setSize(100, 40);
        $info->setItems($this->messages);
        $info->setPosition(0, 38);
        
        $info->show();
    }

    public function onPlayerDisconnect($login) {
        NotificationPanel::Erase($login);       
    }

}

?>