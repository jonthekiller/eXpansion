<?php

namespace ManiaLivePlugins\eXpansion\AutoLoad;

use ManiaLive\Utilities\Console;

class AutoLoad extends \ManiaLivePlugins\eXpansion\Core\types\ExpPlugin {

    private $plugins = array('eXpansion\Core'
        , 'eXpansion\AdminGroups'
        , 'eXpansion\Menu'
        , 'eXpansion\Adm'
        , 'eXpansion\Chat'
        , 'eXpansion\Chat_Admin'
        , 'eXpansion\CheckpointCount'
        , 'eXpansion\Database'
        , 'eXpansion\Emotes'
        , 'eXpansion\DonatePanel'
        , 'eXpansion\Faq'
        , 'eXpansion\JoinLeaveMessage'
        , 'eXpansion\LocalRecords'
        , 'eXpansion\ManiaExchange'
        , 'eXpansion\MapRatings'
        , 'eXpansion\Maps'
        , 'eXpansion\Notifications'
        , 'eXpansion\PersonalMessages'
        , 'eXpansion\Players'
        , 'eXpansion\TmKarma'
        , 'eXpansion\Votes'
        , 'eXpansion\Widgets_Clock'
        , 'eXpansion\Widgets_BestCheckpoints'
        , 'eXpansion\Widgets_EndRankings'
        , 'eXpansion\Widgets_PersonalBest'
        , 'eXpansion\Widgets_Record'
        , 'eXpansion\Widgets_Times'
    );

    public function exp_onLoad() {

        Console::println("[eXpansion Pack]AutoLoading eXpansion pack ... ");

        //We Need the plugin Handler
        $pHandler = \ManiaLive\PluginHandler\PluginHandler::getInstance();

        $recheck = array();
        $lastSize = 0;

        $recheck = $this->loadPlugins($this->plugins, $pHandler);

        do {
            $lastSize = sizeof($recheck);
            $recheck = $this->loadPlugins($this->plugins, $pHandler);
        } while (!empty($recheck) && $lastSize != sizeof($recheck));

        if (!empty($recheck)) {
            Console::println("[eXpansion Pack]AutoLoading eXpansion pack FAILED !! ");
            Console::println("[eXpansion Pack]All required plugins couldn't be loaded : ");
            foreach ($recheck as $pname) {
                Console::println("[eXpansion Pack]...................." . $pname);
            }
        }
    }

    public function loadPlugins($list, \ManiaLive\PluginHandler\PluginHandler $pHandler) {
        $recheck = array();

        foreach ($list as $pname) {
            try {
                if (!$pHandler->isLoaded($pname)) {
                    //Console::println("\n[eXpansion Pack]AutoLoading : Trying to Load $pname ... ");
                    if (!$pHandler->load($pname)) {
                        Console::println("[" . $pname . "]..............................FAIL -> will retry");
                        $recheck[] = $pname;
                    } else {

                        Console::println("[" . $pname . "]..............................SUCCESS");
                    }
                }
            } catch (\Exception $ex) {
                echo "STRANGE:" . $ex->getMessage() . "\n";
                $recheck[] = $pname;
            }
        }
        return $recheck;
    }

}

?>
