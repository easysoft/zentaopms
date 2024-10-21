<?php
class createPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'execution' => "//*[@id='mainContent']/div/div[2]/form/div[2]/div/div/div/span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
