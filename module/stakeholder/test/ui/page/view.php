<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'name'           => "//*[@id='basicInfo']/table/tbody/tr[1]/td",
            'type'           => "//*[@id='basicInfo']/table/tbody/tr[9]/td",
            'key'            => "//*[@id='basicInfo']/table/tbody/tr[8]/td",
            'personality'    => "//*[@id='mainContent']/div[2]/div[1]/div[1]/div[1]/div[2]/p/span",
            'impactAnalysis' => "//*[@id='mainContent']/div[2]/div[1]/div[1]/div[2]/div[2]/p/span",
            'response'       => "//*[@id='mainContent']/div[2]/div[1]/div[1]/div[3]/div[2]/p/span",
            'communication'  => "//*[@id='mainContent']/div[2]/div[1]/div[2]/div[2]/ul/li/div[2]/div/div/p/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
