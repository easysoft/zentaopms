<?php
class casesPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'allCasesNum' => "//*[@id='featureBar']/menu/li[1]/a/span[2]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
