<?php
class treePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'pstoryDeveloping' => "(//div[@z-col='4'])[2]",
            'storyBacklog'     => "(//div[@z-col='8'])[2]",
            'storyTesting'     => "(//div[@z-col='17'])[2]",
            'bugWait'          => "(//div[@z-col='23'])[2]",
            'bugConfirmed'     => "(//div[@z-col='24'])[2]",
            'bugResolved'      => "(//div[@z-col='27'])[2]",
            'taskWait'         => "(//div[@z-col='32'])[2]",
            'taskDoing'        => "(//div[@z-col='34'])[2]",
            'taskDone'         => "(//div[@z-col='35'])[2]",
            'taskPause'        => "(//div[@z-col='36'])[2]",
            'taskCancel'       => "(//div[@z-col='37'])[2]",
            'taskClosed'       => "(//div[@z-col='38'])[2]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
