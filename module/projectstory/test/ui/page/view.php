<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'storyName'        => "//*[@id='mainContent']/div/div[1]/div[1]/span[2]",
            'status'           => "//*[@id='zin_projectstory_view_tabPane']/div/div[2]/div[2]/span",
            'TargetLife'       => "//*[@id='mainContent']/div/div[2]/div[2]/div[1]/div[1]/ul/li[2]/a",
            'openedBy'         => "//*[@id='mainContent']/div/div[2]/div[2]//div[2]/div[2]//div[1]/div[3]/div[2]//span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
