<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'programName'   => '//*[@id="projectviews"]/div[2]/div[1]/div/div/div/a',
            'endDate'       => '//*[@id="projectviews"]/div[2]/div[2]/div/div[6]/div',
            'personnelNav'  => "//*[@id='navbar']//a[@data-id='personnel']",
            'whitelistNav'  => '//*[@id="mainNavbar"]//a[@data-id="whitelist"]',
            'whitelistUser' => '//*[@id="table-personnel-whitelist"]/div[2]/div[1]/div/div[2]/div',
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
