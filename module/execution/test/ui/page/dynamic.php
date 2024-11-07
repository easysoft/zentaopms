<?php
class dynamicPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'num'       => "//*[@id='featureBar']/menu/li[2]/a/span[2]",
            'detailNum' => "//*[@id='mainContent']/div/div/ul/li/div[2]/div/ul/li"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
