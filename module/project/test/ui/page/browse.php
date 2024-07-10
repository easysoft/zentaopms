<?php
class browsePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'settings' => "//*[@id='navbar']//a[@data-id='settings']/span",
            'editBtn'  => "//*[@id='table-project-browse']/div[2]/div[3]/div/div[1]/div/nav/a[2]/i",
            'projectName' => "//*[@id='table-project-browse']/div[2]/div[1]/div/div[2]/div/a",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
