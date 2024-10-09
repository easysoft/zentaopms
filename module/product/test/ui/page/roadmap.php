<?php
class roadmapPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'iterationInfo' => "//*[@id='mainContent']/div/div[1]/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
