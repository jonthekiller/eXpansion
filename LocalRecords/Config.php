<?php

namespace ManiaLivePlugins\eXpansion\LocalRecords;

class Config extends \ManiaLib\Utils\Singleton {


    public $sendBeginMapNotices = true;
    public $sendRankingNotices = true;
    public $recordsCount = 30;

    public $lapsModeCount1lap = true;

    public $nbMap_rankProcess = 1;
    public $ranking = true;
    public $rankRefresh = 5;

    public $msg_secure = '#variable#%1$s  #record#secured their #rank#%2$s #record#. Local Record with time of #rank#%3$s #record#(#rank#$n-%5$s#record#)';  // %1$s - nickname; %2$s - rank; %3$s - time; %4$s - old rank; %5$s - time difference
    public $msg_new = '#variable#%1$s  #record#claimed the #rank#%2$s #record#. Local Record with time of #rank#%3$s';  // %1$s - nickname; %2$s - rank; %3$s - time
    public $msg_improved = '#variable#%1$s  #record#gained the #rank#%2$s #record#. Local Record with time of #rank#%3$s #record#(#rank#$n-%5$s#record#)';  // %1$s - nickname; %2$s - rank; %3$s - time; %4$s - old rank; %5$s - time difference

    public $msg_newMap = '#variable#%1$s  #record#Is a new Map. Currently no record!';  // %1$s - map name
    public $msg_BeginMap = '#record#Current record on #variable#%1$s  #record#is #variable#%2$s #record#by #variable#%3$s';  // %1$s - map name, %2$s - record, %3$s - nickname

    public $msg_personalBest = '#record#Personal Best: #variable#%1$s  #record#($n #variable#%2$s$n #record#)  Average: #variable#%3$s #record#($n #variable#%4$s #record#$n finishes $m)';  // %1$s - pb, %2$s - place (if any), %3$s - average, %4$s - # of finishes
    public $msg_noPB = '#admin_error# $iYou have not finished this map yet..';

    public $msg_showRank = '#record#Server rank: #variable#%1$s#record#/#variable#%2$s';  // %1$s - server rank, %2$s - total # of ranks
    public $msg_noRank = '#admin_error#$iNot enough local records to obtain ranking yet..';

}
?>
