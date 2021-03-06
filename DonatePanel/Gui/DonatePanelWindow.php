<?php

namespace ManiaLivePlugins\eXpansion\DonatePanel\Gui;

use ManiaLivePlugins\eXpansion\DonatePanel\DonatePanel;

class DonatePanelWindow extends \ManiaLive\Gui\Window {

    private $connection;
    private $container;
    public static $donatePlugin;
    private $items = array();
    private $xml;

    protected function onConstruct() {
        $this->setSize(80, 4);

        $bg = new \ManiaLib\Gui\Elements\Quad(77, 5);
        $bg->setAlign("left", "center");
        $bg->setPosition(-13, 1.5);
        $bg->setStyle("Bgs1InRace");
        $bg->setSubStyle("BgList");
        $this->addComponent($bg);


        $this->container = new \ManiaLive\Gui\Controls\Frame(3, 0);
        $this->container->setLayout(new \ManiaLib\Gui\Layouts\Line(100, 3));
        $this->addComponent($this->container);


        $ui = new \ManiaLib\Gui\Elements\Label(13, 2);
        $ui->setAlign('right', 'bottom');
        //$ui->setScale();
        $ui->setText('Donate');
        $ui->setStyle('TextStaticVerySmall');
        $ui->setTextColor('fff');
        $this->addComponent($ui);

        $donations = array(50, 100, 500, 1000, 2000);
        $x = 0;
        foreach ($donations as $text) {
            $this->items[$x] = new \ManiaLivePlugins\eXpansion\Gui\Elements\Button(25, 6);
            $this->items[$x]->setText($text);
            $this->items[$x]->setScale(0.4);
            $this->items[$x]->setAlign('left', 'center');
            $this->items[$x]->setAction($this->createAction(array($this, "Donate"), $text));
            $this->container->addComponent($this->items[$x]);
        }

        $move = new \ManiaLib\Gui\Elements\Quad(79, 6);
        $move->setAlign("left", "center");
        $move->setStyle("Bgs1InRace");
        $move->setPosition(-14, 2);
        $move->setSubStyle("BgEmpty");
        $move->setScriptEvents();
        $move->setId("enableMove");
        $this->addComponent($move);


        $this->xml = new \ManiaLive\Gui\Elements\Xml();
    }

    function Donate($login, $amount) {
        self::$donatePlugin->Donate($login, $amount);
    }

    public function onDraw() {
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
                        declare Text id = "DonatePanel";      
                        
                        declare persistent Boolean exp_enableHudMove = False;
                        declare persistent Vec3[Text] windowLastPos;
                        declare persistent Vec3[Text] windowLastPosRel;
                        
                        
                         if (!windowLastPos.existskey(id)) {
                                windowLastPos[id] = <44.00,-88.00, 0.0>;
                               }
                         if (!windowLastPosRel.existskey(id)) {
                                windowLastPosRel[id] = <44.00,-88.00, 0.0>;
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

    protected function onShow() {
        $posx = 30;
        $posy = 50;
        $this->container->setSize($this->getSizeX(), $this->getSizeX());
    }

    function destroy() {
        foreach ($this->items as $item)
            $item->destroy();

        $this->container->destroy();
        $this->connection = null;
        parent::destroy();
    }

}

?>