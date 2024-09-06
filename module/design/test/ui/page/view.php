<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'designName'    => "//*[@id='mainContent']/div[2]/div[2]/div/div[2]/table/tbody/tr[1]/td",
            'linkedProduct' => "//*[@id='mainContent']/div[2]/div[2]/div/div[2]/table/tbody/tr[2]/td",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
