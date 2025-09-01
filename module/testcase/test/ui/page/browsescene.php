<?php
class browseScenePage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'editBtn'      => "//*[@id='scenes']/div[2]/div[3]/div/div/div/nav/a[1]",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
