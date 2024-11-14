<?php
class treePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firstLevel'   => "//*[@id='mainContent']/div/div[1]/div/menu/li[@z-type='item']",
            'secondLevelA' => "//*[@id='mainContent']/div/div[1]/div/menu/li[1]/menu/li[@z-type='item']",
            'secondLevelB' => "//*[@id='mainContent']/div/div[1]/div/menu/li[2]/menu/li[@z-type='item']",
            'thirdLevelB'  => "//*[@id='mainContent']/div/div[1]/div/menu/li[2]/menu/li[1]/menu/li[@z-type='item']",
            'fourthLevelB' => "//*[@id='mainContent']/div/div[1]/div/menu/li[2]/menu/li[1]/menu/li[1]/menu/li[@z-type='item']",
            'onlyStoryBtn' => "//*[@id='featureBar']/menu/div/label",
            'detail'       => "//*[@id='mainContent']/div/div[1]/div/menu/li[2]/menu/li/menu/li[1]/div/div/div/div/span[3]",
            'title'        => "//*[@id='detailBlock']/div[1]/div[1]/span[2]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
