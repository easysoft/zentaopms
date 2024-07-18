<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'firstID'      => "//*[@id='table-productplan-browse']/div[2]/div[1]/div/div[1]/div",
            'firstTitle'   => "//*[@id='table-productplan-browse']/div[2]/div[1]/div/div[2]/div/a",
            'firstBegin'   => "//*[@id='table-productplan-browse']/div[2]/div[2]/div/div[2]/div",
            'firstEnd'     => "//*[@id='table-productplan-browse']/div[2]/div[2]/div/div[3]/div",
            'search'       => "//*[@id='featureBar']/menu/li[last()]",
            'searchBtn'    => "//*[@id='mainContent']/div[1]/div/div[1]/div[2]/button[1]",
            'searchInput1' => "//*[@id='mainContent']/div[1]/div/div[1]/div[1]/div[1]/table/tbody/tr/td[4]/input",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
