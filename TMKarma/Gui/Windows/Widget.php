<?php

namespace ManiaLivePlugins\eXpansion\TMKarma\Gui\Windows;

use \ManiaLivePlugins\eXpansion\TMKarma\TMKarma;

class Widget extends \ManiaLive\Gui\Window {

    /**
     * @var \ManiaLivePlugins\eXpansion\TMKarma\Structures\Karma
     */
    protected $karma;

    /**
     * @var \ManiaLivePlugins\eXpansion\TMKarma\TMKarma
     */
    protected $plugin;
    protected $buttons;
    protected $background;
    protected $cupsContainer;
    protected $buttonsContainer;
    protected $link;
    protected $info;
    public $challengeData;
    private $xml;
    
    const CUPS_MAX = 5;

    function onConstruct() {
        $this->buttons = array(
            '+' => TMKarma::VOTE_GOOD,
            '++' => TMKarma::VOTE_BEAUTIFUL,
            '+++' => TMKarma::VOTE_FANTASTIC,
            '-' => TMKarma::VOTE_BAD,
            '--' => TMKarma::VOTE_POOR,
            '---' => TMKarma::VOTE_WASTE
        );

        // set the window's size
        // you won't do that for non-widgets!
        $this->setSize(18, 30);
        $this->setAlign("center", "top");

        // render background
        $this->background = new \ManiaLib\Gui\Elements\Quad();
        $this->background->setSize($this->sizeX, $this->sizeY);
        $this->background->setStyle("BgsPlayerCard");
        $this->background->setSubStyle("BgPlayerCardBig");
        $this->background->setAlign("center", "center");
        //$this->addComponent($this->background);
        // render cups
        $this->cupsContainer = new \ManiaLive\Gui\Controls\Frame(0, 8);
        $this->cupsContainer->setSize($this->sizeX, $this->sizeY);
        $this->cupsContainer->setAlign("right", "top");
        $this->cupsContainer->setPosX(10);
        $this->cupsContainer->setLayout(new \ManiaLib\Gui\Layouts\Line());
        $this->addComponent($this->cupsContainer);

        // render buttons
        $this->buttonsContainer = new \ManiaLive\Gui\Controls\Frame(0, 0);
        $this->buttonsContainer->setAlign("center", "top");
        $this->buttonsContainer->setSize($this->sizeX, 15);
        $this->addComponent($this->buttonsContainer);

        // apply layout to the frame that contains
        // the buttons, this way all buttons are positioned
        // automatically
        $layout = new \ManiaLib\Gui\Layouts\Flow(15, 15);
        $layout->setMargin(0.5, 0.5);
        $this->buttonsContainer->setLayout($layout);

        // render text information
        $this->info = new \ManiaLib\Gui\Elements\Label();
        $this->info->setTextSize(1);
        $this->info->setAlign('center', "top");
        $this->info->setTextColor('fff');
        $this->info->setPosition(0, 3);
        $this->addComponent($this->info);
        
        $move = new \ManiaLib\Gui\Elements\Quad(18, 14);
        $move->setAlign("center", "center");
        $move->setStyle("Bgs1InRace");
        $move->setSubStyle("BgEmpty");
        $move->setScriptEvents();
        $move->setId("enableMove");
        $move->setPosition(0,4);
        $this->addComponent($move);
        
        $this->xml = new \ManiaLive\Gui\Elements\Xml();
        
    }

    function onDraw() {
        $rate = 100 / self::CUPS_MAX;

        $votes = $this->karma->total;
        if (empty($votes))
            $votes = 0;
        $score = $this->karma->score;

        if (empty($score))
            $score = 0;

        $this->info->setText($score . "% " . $votes . " votes");

        // first we create all golden cups
        $this->cupsContainer->clearComponents();
        for ($i = 0; $i < $this->karma->score; $i += $rate) {
            $cup = new \ManiaLib\Gui\Elements\Quad();
            $cup->setStyle("BgRaceScore2");
            $cup->setSubStyle("Fame");
            $cup->setSize(3.5, 3.5);

            // add them to the container who will automatically position them
            $this->cupsContainer->addComponent($cup);
        }

        // all missing cups will be gray
        for (; $i <= 100; $i += $rate) {
            $cup = new \ManiaLib\Gui\Elements\Quad();
            $cup->setStyle("Icons64x64_1");
            $cup->setSubStyle("OfficialRace");
            $cup->setSize(3.5, 3.5);

            // add them to the container who will automatically position them
            //$this->cupsContainer->addComponent($cup);
        }


        //   $this->buttonsContainer->clearComponents();
        /*   foreach ($this->buttons as $text => $vote) {
          $frame = new \ManiaLive\Gui\Controls\Frame();
          $frame->setSize(4.5, 2.5);

          // render the button's background
          $ui = new \ManiaLib\Gui\Elements\Quad();
          $ui->setStyle("Bgs1InRace");
          $ui->setSubStyle("Empty");
          $ui->setBgcolor('fff9');
          $ui->setBgcolorFocus('2a29');
          $login = $this->getRecipient();
          if (isset($this->karma->votes[$login]) && $this->karma->votes[$login] == $vote) {
          $ui->setBgcolor('6f69');
          }
          $ui->setSize($frame->getSizeX(), $frame->getSizeY());
          $ui->setAction($this->createAction(array($this->plugin, 'doVote'), $vote));
          $frame->addComponent($ui);

          // render the label on top of the background
          $ui = new \ManiaLib\Gui\Elements\Label();
          $ui->setText($text);
          $ui->setAlign('center', "center");
          $ui->setTextSize(1);
          $ui->setSize($frame->getSizeX(), 2);
          $ui->setPosition($frame->getSizeX() / 2, -1);
          $frame->addComponent($ui);

          // add the frames to the buttons container who will position them
          $this->buttonsContainer->addComponent($frame);

          // render www.tm-karma.com
          $this->link = new \ManiaLib\Gui\Elements\Label($this->sizeX, 4);
          $this->link->setAlign("center", "top");
          //$this->link->setText('$l[http://www.tm-karma.com/Goto?uid=' . $this->karma->challengeUid . ']www.tm-karma.com$l');
          $this->link->setText('$l[http://www.tm-karma.com/]tm-karma$l');
          $this->link->setTextColor('fff');
          $this->link->setTextSize(1);
          $this->link->setPosition(0, 12);
          // $this->addComponent($this->link);

          }
         */
         $this->removeComponent($this->xml);
        $this->xml->setContent('    
        <script><!--
               
                       main () {     
                        declare Window <=> Page.GetFirstChild("' . $this->getId() . '");                 
                        declare MoveWindow = False;                       
                        declare CMlLabel lbl_clock <=> (Page.GetFirstChild("clock") as CMlLabel);
                        declare CMlLabel lbl_date <=> (Page.GetFirstChild("date") as CMlLabel);                        
                        declare CMlQuad  quad <=> (Page.GetFirstChild("enableMove") as CMlQuad);      
                        declare Vec3 LastDelta = <Window.RelativePosition.X, Window.RelativePosition.Y, 0.0>;
                        declare Vec3 DeltaPos = <0.0, 0.0, 0.0>;
                        declare Real lastMouseX = 0.0;
                        declare Real lastMouseY =0.0;                           
                        declare Text id = "TMKarma";      
                        
                        declare persistent Boolean exp_enableHudMove = False;
                        declare persistent Vec3[Text] windowLastPos;
                        declare persistent Vec3[Text] windowLastPosRel;
                                                                         
                         if (!windowLastPos.existskey(id)) {
                                windowLastPos[id] = <150.0, 52.0, 0.0>;
                               }
                         if (!windowLastPosRel.existskey(id)) {
                                windowLastPosRel[id] = <150.0, 52.0, 0.0>;
                              }
                        Window.PosnX = windowLastPos[id][0];
                        Window.PosnY = windowLastPos[id][1];
                        LastDelta = windowLastPosRel[id];
                        Window.RelativePosition = windowLastPosRel[id];                                                
                        
                        while(True) {                                             
                        if (exp_enableHudMove == True) {
                                quad.Show();
                                quad.Substyle="NavButtonBlink";     
                            
                            }
                        else {
                            quad.Hide();
                            quad.Substyle="BgEmpty";
                            
                        }
                          if (exp_enableHudMove == True && MouseLeftButton == True) {
                                     
                                              foreach (Event in PendingEvents) {

                                                    if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "enableMove")  {
                                                        lastMouseX = MouseX;
                                                        lastMouseY = MouseY;                                                            
                                                        MoveWindow = True;                                                           
                                                        }                                                                                                  
                                                }
                                        }
                                        else {
                                            MoveWindow = False;                                                                          
                                        }
                                        
                                if (MoveWindow) {                                                                                                    
                                    DeltaPos.X = MouseX - lastMouseX;
                                    DeltaPos.Y = MouseY - lastMouseY;
                                                                      
                                    LastDelta += DeltaPos;
                                    LastDelta.Z = 3.0;
                                    Window.RelativePosition = LastDelta;
                                    windowLastPos[id] = Window.AbsolutePosition;
                                    windowLastPosRel[id] = Window.RelativePosition;
                                    
                                    lastMouseX = MouseX;
                                    lastMouseY = MouseY;                            
                                    }
                                    yield;
                           }
                  
                  
                } 
                --></script>');
        $this->addComponent($this->xml);
        parent::onDraw();
    }

    function setPlugin(TMKarma $plugin) {
        $this->plugin = $plugin;
    }

    function setKarma(\ManiaLivePlugins\eXpansion\TMKarma\Structures\Karma $karma) {
        $this->karma = $karma;
    }

    public function destroy() {
        $this->clearComponents();
        parent::destroy();
    }

}

?>