<?php
class workPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'task'         => '//*[@id="mainNavbar"]/div/menu/li[1]/a',
            'SR'           => '//*[@id="mainNavbar"]/div/menu/li[2]/a',
            'UR'           => '//*[@id="mainNavbar"]/div/menu/li[3]/a',
            'ER'           => '//*[@id="mainNavbar"]/div/menu/li[4]/a',
            'bug'          => '//*[@id="mainNavbar"]/div/menu/li[5]/a',
            'case'         => '//*[@id="mainNavbar"]/div/menu/li[6]/a',
            'request'      => '//*[@id="mainNavbar"]/div/menu/li[7]/a',
            'firstTab'     => '//*[@id="featureBar"]/menu/li[1]/a',
            'secondTab'    => '//*[@id="featureBar"]/menu/li[2]/a',
            'firstTabNum'  => '//*[@id="featureBar"]/menu/li[1]/a/span[2]',
            'secondTabNum' => '//*[@id="featureBar"]/menu/li[2]/a/span[2]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
