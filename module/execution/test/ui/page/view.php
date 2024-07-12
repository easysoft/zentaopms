<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'executionName' => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div[1]/div",
            'status'        => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div[1]/span[2]",
            'acl'           => "//*[@id='mainContent']/div[1]/div[1]/div[2]/div[1]/span[3]",
            'plannedBegin'  => "//*[@id='mainContent']/div[2]/div[1]/div/table[3]/tbody/tr/td/div/div[1]/span[2]",
            'plannedEnd'    => "//*[@id='mainContent']/div[2]/div[1]/div/table[3]/tbody/tr/td/div/div[2]/span[2]",
            'edit'          => "//*[@id='mainContent']/div[3]/div/a[last()-1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
