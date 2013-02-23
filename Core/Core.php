<?php

namespace ManiaLivePlugins\eXpansion\Core;

use ManiaLive\Event\Dispatcher;
use ManiaLive\Utilities\Console;
/**
 * Description of Core
 *
 * @author oliverde8
 */
class Core extends types\ExpPlugin{
	
	private $lastGameMode;
	
	function exp_onInit() {
		parent::exp_onInit();
		$this->setVersion(self::getMaxVersion());
	}
	
	function exp_onLoad() {
		parent::exp_onLoad();
		
		$this->enableDedicatedEvents(\ManiaLive\DedicatedApi\Callback\Event::ON_BEGIN_MAP);
		
		Console::println(' #####################################################################');
		Console::println('[eXpension Pack] Enabling eXpension version:' . $this->getVersion() . ' . . .');
		
		$die = false;
		
		Console::println(' #####################################################################');
		
		if($die)die();
		
		$this->lastGameMode = \ManiaLive\Data\Storage::getInstance()->gameInfos->gameMode;
	}
	
	public function exp_onReady() {
		$this->onBeginMap(null, null, null);
	}


	function onBeginMap($map, $warmUp, $matchContinuation){
		$newGameMode = \ManiaLive\Data\Storage::getInstance()->gameInfos->gameMode;
		if($newGameMode != $this->lastGameMode){
			$this->lastGameMode = $newGameMode;
			
			$this->checkLoadedPlugins();
			$this->checkPluginsOnHold();
		}
	}
	
	private function checkLoadedPlugins(){
		$pHandler = \ManiaLive\PluginHandler\PluginHandler::getInstance();
		Console::println('#####################################################################');
		Console::println('[eXpension Pack] GameMode Changed Shutting down uncompatible plugins');
		foreach ($this->exp_getGameModeCompability() as $plugin => $compability) {
			$parts = explode('\\', $plugin);
			$plugin_id = $parts[1].'\\'.$parts[2];
			if(!$plugin::exp_checkGameCompability()){
				try{
					$this->callPublicMethod($plugin_id, 'exp_unload');
				}catch(\Exception $ex){ }
			}
		}
		Console::println('#####################################################################'."\n");
	}
	
	private function checkPluginsOnHold(){
		Console::println('#####################################################################');
		Console::println('[eXpension Pack] GameMode Changed Starting compatible plugins');
		if(!empty(types\BasicPlugin::$plugins_onHold)){
			$pHandler = \ManiaLive\PluginHandler\PluginHandler::getInstance();
			foreach(types\BasicPlugin::$plugins_onHold as $plugin_id){
				$className = '\\ManiaLivePlugins\\'.$plugin_id;
				//if($className::exp_checkGameCompability()){
					$pHandler->load($plugin_id);
				//}
			}
		}
		Console::println('#####################################################################'."\n");
	}

}

?>