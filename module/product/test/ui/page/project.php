<?php
class projectPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'linkBtn' => '//*[@id="actionBar"]/a[1]',
            /*tab标签*/
            'allTab'        => '//*[@id="featureBar"]/menu/li[1]/a',
            'unfinishedTab' => '//*[@id="featureBar"]/menu/li[2]/a',
            'waitingTab'    => '//*[@id="featureBar"]/menu/li[3]/a',
            'doingTab'      => '//*[@id="featureBar"]/menu/li[4]/a',
            'suspendedTab'  => '//*[@id="featureBar"]/menu/li[5]/a',
            'closedTab'     => '//*[@id="featureBar"]/menu/li[6]/a',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
